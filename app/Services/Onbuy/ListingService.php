<?php
namespace App\Services\Onbuy;

use App\Models\Onbuy\Onbuy;
use App\Models\Onbuy\Product as OnbuyProductModel;
use App\Models\Onbuy\SellerProduct;
use App\Models\Schedule;
use GuzzleHttp\Client;
use App\Exceptions\OutputServerMessageException;
use Log, DB;
use App\Models\Onbuy\ProductBid;
use App\Models\Onbuy\ProductBidTask;
use Xigen\Library\OnBuy\Product\Product;
use Xigen\Library\OnBuy\Product\Listing;

class ListingService
{
    public $seller_id;

    public function __construct($seller_id)
    {
        $this->seller_id = $seller_id;

    }
    /*
    public function automatic()
    {
        $time = date("H:i:s");
        $date = date("Y-m-d");
        ProductBid::where('everyday',1)
            ->whereRaw(" IF (`start_time` > `end_time`, (".$time." > `start_time` or ". $time." < `end_time`), (".$time." >= `start_time` and ". $time." <= `end_time`))" )
            ->orWhere(function($query) use ($date, $time){
            $query->whereRaw(" IF (`start_time` > `end_time`, (".$time." > `start_time` or ". $time." < `end_time`), (".$time." >= `start_time` and ". $time." <= `end_time`))" );
            //$query->where('everyday',0)->whereRaw('start_time','<=','end_time')->where('start_date','<=',$date)->where('end_date','>=',$date)->where('start_time','<=',$time)->where('end_time','>=',$time);

        });
    }*/
    public function automatic()
    {
        $time = date("H:i:s");
        $date = date("Y-m-d");
		Log::info('price_auto date:'.$date);
        $product_bid_ids = ProductBid::where('seller_id',$this->seller_id)->where('active',1)->whereRaw(" IF (`start_time` > `end_time`, ('".$time."' > `start_time` or '". $time."' < `end_time`), ('".$time."' >= `start_time` and '". $time."' <= `end_time`))" )->pluck('id')->toArray();
        if(!$product_bid_ids)
        {
            echo "0";
            return true;
        }
        $tasks = ProductBidTask::join('onbuy_products','onbuy_product_bid_tasks.sku','=','onbuy_products.sku')
            ->where('onbuy_products.min_price','>',0)
            ->whereIn('onbuy_product_bid_tasks.bid_id',$product_bid_ids)
            ->groupBy('onbuy_product_bid_tasks.sku')
            ->get(['onbuy_products.sku','onbuy_products.price','onbuy_products.min_price'])->toArray();
        if(!$tasks)
        {
            echo "0";
            return true;
        }
        $skus = array_column($tasks,'sku');
        $tasks = array_combine($skus,$tasks);
        $onbuy_token = getOnbuyToken($this->seller_id);
        $listing = new Listing($onbuy_token);
        $listing->getWinningListing($skus);
        $response =$listing->getResponse();
        if(!$response['success'] || count($response['results']) == 0)
        {
            echo "0";
            return true;
        }
        $winning_listings = $response['results'];
        $data = [];
        foreach ($winning_listings as $key => $winning)
        {
            if(isset($winning['lead_price']) && $tasks[$winning['sku']]['min_price'] > 0 && (($tasks[$winning['sku']]['price'] > $winning['lead_price'] &&  $tasks[$winning['sku']]['min_price'] <= $winning['lead_price'])))
            {
                $data[] = [
                    "sku" => $winning['sku'],
                    "price" => $winning['lead_price'] - rand(1,2)/100,
                ];
            }
        }
        if(!$data)
        {
            echo "0";
            return true;
        }
        DB::beginTransaction();
        foreach ($data as $item)
        {
            \App\Models\Onbuy\Product::where('sku',$item['sku'])->update([
                'price' => $item['price']
            ]);
        }
        $onbuy_token = getOnbuyToken($this->seller_id);
        $listing = new Listing($onbuy_token);
        $listing->updateListingBySku($data);
        DB::commit();
        echo "success";
        return $listing->getResponse();
    }
    public function restorePrice($is_manual=false)
    {
        $date = date("Y-m-d");
		Log::info($date);
        if($is_manual)
        {

            $schedule = Schedule::create([
                'name' => 'restore_onbuy_price',
                'date' => $date,
                'success' => 0,
            ]);
        }else
        {
            $h = intval(date("G"));

            if($h<intval(setting('onbuy_restore_hours')))
            {
                return false;
            }
            $schedule = Schedule::where('name','restore_onbuy_price')->where('date',$date)->where('success',1)->first();
            if($schedule)
            {
                return true;
            }else{
                $schedule = Schedule::create([
                    'name' => 'restore_onbuy_price',
                    'date' => $date,
                    'success' => 0,
                ]);
            }
        }

        $product_bid_ids = ProductBid::where('seller_id',$this->seller_id)->where('active',1)->pluck('id')->toArray();
        if($product_bid_ids){
            $this->restorePriceHandle($product_bid_ids);
        }

        $schedule->success = 1;
        $schedule->save();
        return true;

    }
    public function restorePriceHandle($product_bid_ids, $offset=0, $limit=50)
    {
        $tasks = ProductBidTask::join('onbuy_products','onbuy_product_bid_tasks.sku','=','onbuy_products.sku')
            ->whereIn('onbuy_product_bid_tasks.bid_id',$product_bid_ids)
            ->where('onbuy_products.min_price','<>',0)
            ->offset($offset)
            ->limit($limit)
            ->groupBy('onbuy_product_bid_tasks.sku')
            ->get(['onbuy_products.sku','onbuy_products.price','onbuy_products.min_price','onbuy_products.original_price'])->toArray();
        if(!$tasks)
        {
            return true;
        }

        DB::beginTransaction();
        $data = [];
        foreach ($tasks as $item)
        {
            \App\Models\Onbuy\Product::where('sku',$item['sku'])
                ->update([
                    'price' => $item['original_price']
                ]);
            $data[] = [
                "sku" => $item['sku'],
                "price" => $item['original_price'],
            ];
        }
        $onbuy_token = getOnbuyToken($this->seller_id);
        $listing = new Listing($onbuy_token);
        $listing->updateListingBySku($data);
        $result = $listing->getResponse();
        if($result['success'])
        {
            DB::commit();
            $this->restorePriceHandle($product_bid_ids, $offset+$limit);
            return true;
        }
        DB::rollback();
        return false;
    }

    public function syncHandle($offset=0,$limit=50)
    {
        DB::beginTransaction();
        try{
            $onbuy_token = getOnbuyToken($this->seller_id);
            $listing = new Listing($onbuy_token);
            $listing->getListing(
                ['last_created' => 'desc'],
                [],
                $limit,
                $offset
            );
            $products = $listing->getResponse();
            if(count($products['results']) <=0 )
            {
                return true;
            }
            $data = [];
            $seller_product_data = [];
            $picked = false;
            foreach ($products['results'] as $key => $product)
            {
                $is_exist_seller_product = SellerProduct::where('seller_id',$this->seller_id)->where('product_sku',$product['sku'])->value('id');
                $is_exist_product = OnbuyProductModel::where('sku',$product['sku'])->value('id');
                if($is_exist_seller_product && $is_exist_product)
                {
                    $picked = true;
                }

                if(!$is_exist_product)
                {
                    $data[$key] = [
                        'name' => $product['name'],
                        'sku' => $product['sku'],
                        'group_sku' => $product['group_sku'],
                        'price' => $product['price'],
                        'stock' => $product['stock'],
                        'product_listing_id' => $product['product_listing_id'],
                        'product_listing_condition_id' => $product['product_listing_condition_id'],
                        'condition' => $product['condition'],
                        'handling_time' => $product['handling_time'],
                        'boost_marketing_commission' => $product['boost_marketing_commission'],
                        'product_encoded_id' => $product['product_encoded_id'],
                        'delivery_weight' => $product['delivery_weight'],
                        'delivery_template_id' => $product['delivery_template_id'],
                        'opc' => $product['opc'],
                        'product_url' => $product['product_url'],
                        'image_url' => $product['image_url'],
                        'sale_price' => $product['sale_price'],
                        'min_price' => 0,
                        'original_price' => $product['price'],
                        'created_at' => $product['created_at'],
                        'updated_at' => $product['updated_at'],
                    ];
                }
                if(!$is_exist_seller_product){
                    $seller_product_data[$key] = [
                        'seller_id' => $this->seller_id,
                        'product_sku' => $product['sku'],
                        'created_at' => $product['created_at'],
                        'updated_at' => $product['updated_at'],
                    ];
                }
            }
            //插入数据库
            if(count($data))
            {
                DB::table("onbuy_products")->insert($data);
            }
            if(count($seller_product_data))
            {
                DB::table("onbuy_seller_product")->insert($seller_product_data);
            }
            DB::commit();
            //还不是最新数据
            if(!$picked){
                $this->syncHandle($offset+$limit);
            }
            return true;

        } catch (Exception $e){
            DB::rollback();
            throw new OutputServerMessageException($e->getMessage());
        }

    }
}