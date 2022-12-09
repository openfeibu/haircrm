<?php

namespace App\Http\Controllers\Admin\Onbuy;

use App\Http\Controllers\Admin\Onbuy\BaseController;
use App\Models\Onbuy\Product as OnbuyProductModel;
use App\Models\Onbuy\ProductBid as OnbuyProductBidModel;
use App\Models\Onbuy\ProductBidTask as OnbuyProductBidTaskModel;
use App\Models\Onbuy\OrderProduct as OnbuyOrderProductModel;
use App\Models\Onbuy\ProductBidTask;
use Illuminate\Http\Request;
use Xigen\Library\OnBuy\Product\Product;
use Xigen\Library\OnBuy\Product\Listing;
use DB;
use App\Services\Onbuy\ListingService;
use App\Models\Onbuy\Onbuy;

class ListingResourceController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $onbuy_list = Onbuy::getAll();
        $search = $request->get('search',[]);
        if(!isset($search['seller_id']) || !$search['seller_id'])
        {
            $search['seller_id'] = $onbuy_list->toArray()[0]['seller_id'];
        }
        if ($this->response->typeIs('json')) {
            $products = OnbuyProductModel::when($search ,function ($query) use ($search){
                foreach($search as $field => $value)
                {
                    if($value)
                    {
                        if($field == 'sku'|| $field == 'seller_id')
                        {
                            $query->where($field,$value);
                        }else{
                            $query->where($field,'like','%'.$value.'%');
                        }
                    }

                }
            });
            $products = $products->orderBy('created_at','desc')->paginate($request->get('limit',50));
            $onbuy_fee = (float)setting('onbuy_fee');
            $gbp_to_rmb = (float)setting('gbp_to_rmb');
            foreach ($products as $key=> $product)
            {
                $product->min_price_expect = round($product->min_price * (1-$onbuy_fee )* $gbp_to_rmb, 2);

                $product->original_price_expect = round($product->original_price * (1-$onbuy_fee) * $gbp_to_rmb, 2);
                $product->freight_expect =  international_freight($product->weight);

                $product->cost = $product->freight_expect + $product->purchase_price;
                $product->min_price_advice =  round($product->cost / $gbp_to_rmb / (1-$onbuy_fee),2);

                $product->min_price_profit_expect = round($product->min_price_expect - $product->cost,2);
                $product->original_price_profit_expect = round($product->original_price_expect - $product->cost,2);

                $product->total_quantity = OnbuyOrderProductModel::join('onbuy_orders','onbuy_orders.order_id','=','onbuy_order_products.order_id')
                    ->selectRaw("SUM(onbuy_order_products.quantity) as total_quantity ")
                    ->whereIn('onbuy_orders.status',['Awaiting Dispatch','Dispatched','Partially Dispatched','Complete'])
                    ->where('onbuy_order_products.seller_id',$product->seller_id)
                    ->where('onbuy_order_products.sku',$product->sku)
                    ->value('total_quantity') ?: 0;
                $product->need_purchase = $product->total_quantity - $product->inventory - $product->out_inventory;

                $product->is_auto_pricing = ProductBidTask::where('seller_id',$product->seller_id)->where('sku',$product->sku)->value('id') ? 1 : 0;
            }
            return $this->response
                ->success()
                ->count($products->total())
                ->data($products->toArray()['data'])
                ->output();

        }

        return $this->response->title(trans('goods.name'))
            ->view('onbuy.listing.index')
            ->data(['limit' => $request->get('limit',50),'onbuy_list' => $onbuy_list])
            ->output();
    }
    public function update(Request $request, OnbuyProductModel $listing)
    {
        try {
            $attributes = $request->all();
            $listing->update($attributes);
            return $this->response->message(trans('messages.success.updated'))
                ->code(0)
                ->status('success')
                ->url(guard_url('onbuy/listing' . $listing->id))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('onbuy/listing/' . $listing->id))
                ->redirect();
        }

    }
    public function sync(Request $request)
    {
        $seller_id = $request->get('seller_id','');
        $this->syncHandle($seller_id);
        return $this->response->message(trans('messages.operation.success'))
            ->status("success")
            ->http_code(202)
            ->url(guard_url('goods'))
            ->redirect();
    }
    public function syncHandle($seller_id,$offset=0,$limit=50)
    {
        $onbuy_token = getOnbuyToken($seller_id);
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
        $picked = false;
        foreach ($products['results'] as $key => $product)
        {
            $is_exist = OnbuyProductModel::where('seller_id',$seller_id)->where('sku',$product['sku'])->value('id');
            if($is_exist)
            {
                $picked = true;
                break;
            }
            if(!$is_exist)
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
                    'seller_id' => $seller_id,
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
        //还不是最新数据
        if(!$picked){
            $this->syncHandle($seller_id,$offset+$limit);
        }
        return true;
    }
    public function destroy(Request $request,OnbuyProductModel $listing)
    {
        try {
            $listing->delete();
            return $this->response->message(trans('messages.success.deleted', ['Module' => '产品']))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('onbuy/listing'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('onbuy/listing'))
                ->redirect();
        }
    }

    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            OnbuyProductModel::destroy($ids);

            return $this->response->message(trans('messages.operation.success'))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('onbuy/listing'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('onbuy/listing'))
                ->redirect();
        }
    }
    public function automatic(Request $request)
    {
        try {
            $attributes = $request->all();
            $product_bid = OnbuyProductBidModel::create($attributes);

            $data = [];
            foreach ($attributes['skus'] as $sku)
            {
                $data[] = [
                    'sku' => $sku,
                    'bid_id' => $product_bid->id,
                    'seller_id' => $attributes['seller_id'],
                ];
            }
            OnbuyProductBidTaskModel::whereIn('sku',$attributes['skus'])->delete();
            DB::table('onbuy_product_bid_tasks')->insert($data);
            return $this->response->message(trans('messages.operation.success'))
                ->code(0)
                ->status('success')
                ->url(guard_url('onbuy/listing'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('onbuy/listing'))
                ->redirect();
        }

    }
    public function restorePrice(Request $request)
    {
        $seller_id = $request->get('seller_id');
        $list_service = new ListingService($seller_id);
        $list_service->restorePrice(true);
        return $this->response->message(trans('messages.operation.success'))
            ->code(0)
            ->status('success')
            ->url(guard_url('onbuy/listing'))
            ->redirect();
    }
    public function getWinning()
    {
        //$this->list_service->restorePrice();
        exit;
        $this->list_service->automatic();
        exit;
        $onbuy_token = getOnbuyToken();
        $listing = new Listing($onbuy_token);

//        $listing->getListing(
//            ['last_created' => 'desc'],
//            [],
//            20,
//            0
//        );
//        $products = $listing->getResponse();
//        var_dump($products);exit;

        $listing->getWinningListing([
            "0426386615889",
            "0711719894858",
            "0711719874263"
        ]);
        var_dump($listing->getResponse());exit;
    }
}
