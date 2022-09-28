<?php

namespace App\Http\Controllers\Admin\Onbuy;

use App\Http\Controllers\Admin\Onbuy\BaseController;
use App\Models\Onbuy\Product as OnbuyProductModel;
use Illuminate\Http\Request;
use Xigen\Library\OnBuy\Product\Product;
use Xigen\Library\OnBuy\Product\Listing;
use DB;

class ListingResourceController extends BaseController
{

    public function index(Request $request)
    {
        if ($this->response->typeIs('json')) {
            $search = $request->get('search',[]);
            $products = OnbuyProductModel::when($search ,function ($query) use ($search){
                foreach($search as $field => $value)
                {
                    if($value)
                    {
                        if($field == 'sku')
                        {
                            $query->where('sku',$value);
                        }else{
                            $query->where($field,'like','%'.$value.'%');
                        }
                    }

                }
            });
            $products = $products->orderBy('created_at','desc')->paginate($request->get('limit',50));
            foreach ($products as $key=> $product)
            {
                $product->min_price_expect = number_format($product->min_price * (1-setting('onbuy_fee') )* setting('gbp_to_rmb'), 2);

                $product->original_price_expect = number_format($product->original_price * (1-setting('onbuy_fee') ) * setting('gbp_to_rmb'), 2);
                $product->freight_expect = $product->weight ? ($product->weight * 0.058 + 18) : 0;

                $product->cost = $product->freight_expect + $product->purchase_price;
                $product->min_price_advice =  number_format($product->cost / setting('gbp_to_rmb') / (1-setting('onbuy_fee')),2);

                $product->min_price_profit_expect = number_format($product->min_price_expect - $product->cost,2);
                $product->original_price_profit_expect = number_format($product->min_price_expect - $product->cost,2);
            }
            return $this->response
                ->success()
                ->count($products->total())
                ->data($products->toArray()['data'])
                ->output();
            /*
            $listing = new Listing($this->onbuy_token);

            $listing->getListing(
                ['last_created' => 'desc'],
                [],
                20,
                0
            );
            $products = $listing->getResponse();
            return $this->response
                ->success()
                ->count($products['metadata']['total_rows'])
                ->data($products['results'])
                ->output();*/

        }

        return $this->response->title(trans('goods.name'))
            ->view('onbuy.listing.index')
            ->data(['limit' => $request->get('limit',50)])
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
    public function sync()
    {
        $this->syncHandle();
        return $this->response->message(trans('messages.operation.success'))
            ->status("success")
            ->http_code(202)
            ->url(guard_url('goods'))
            ->redirect();
    }
    public function syncHandle($offset=0,$limit=50)
    {
        $listing = new Listing($this->onbuy_token);
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
            $is_exist = OnbuyProductModel::where('sku',$product['sku'])->value('id');
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
                    'min_price' => $product['price'],
                    'original_price' => $product['price'],
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
            $this->syncHandle($offset+$limit);
        }
        return true;
    }
    public function getWinning()
    {
        $listing = new Listing($this->getToken());

        $listing->getListing(
            ['last_created' => 'desc'],
            [],
            20,
            0
        );
        $products = $listing->getResponse();
        var_dump($products);exit;

        $listing->getWinningListing([
            "0714131824724",
        ]);
        var_dump($listing->getResponse());exit;
    }
}