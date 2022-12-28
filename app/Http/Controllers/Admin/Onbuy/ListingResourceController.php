<?php

namespace App\Http\Controllers\Admin\Onbuy;

use App\Http\Controllers\Admin\Onbuy\BaseController;
use App\Models\Onbuy\Product as OnbuyProductModel;
use App\Models\Onbuy\ProductBid as OnbuyProductBidModel;
use App\Models\Onbuy\ProductBidTask as OnbuyProductBidTaskModel;
use App\Models\Onbuy\OrderProduct as OnbuyOrderProductModel;
use App\Models\Onbuy\ProductBidTask;
use App\Models\Onbuy\SellerProduct;
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

        if ($this->response->typeIs('json')) {
            $products = OnbuyProductModel::when($search ,function ($query) use ($search){
                foreach($search as $field => $value)
                {
                    if($value)
                    {
                        if($field == 'sku')
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

                $auto_pricing = ProductBidTask::join('onbuy_product_bid','onbuy_product_bid.id','=','onbuy_product_bid_tasks.bid_id')->where('onbuy_product_bid_tasks.seller_id',$product->seller_id)->where('onbuy_product_bid_tasks.sku',$product->sku)->first();
                $product->is_auto_pricing = $auto_pricing ? $auto_pricing->start_time.'~'.$auto_pricing->end_time : '';
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

    public function destroy(Request $request,OnbuyProductModel $listing)
    {
        try {
            $listing->delete();
            SellerProduct::where('product_sku',$listing['sku'])->delete();
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

}
