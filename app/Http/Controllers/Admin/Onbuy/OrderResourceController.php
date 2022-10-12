<?php

namespace App\Http\Controllers\Admin\Onbuy;

use App\Http\Controllers\Admin\Onbuy\BaseController;
use App\Models\Onbuy\Product as OnbuyProductModel;
use App\Models\Onbuy\Order as OnbuyOrderModel;
use App\Models\Onbuy\OrderProduct as OnbuyOrderProductModel;
use Illuminate\Http\Request;
use Xigen\Library\OnBuy\Product\Product;
use Xigen\Library\OnBuy\Product\Listing;
use Xigen\Library\OnBuy\Order\Order;
use App\Services\Onbuy\OrderService;
use DB;

class OrderResourceController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->orderService = new OrderService();
    }

    public function index(Request $request)
    {
        if ($this->response->typeIs('json')) {
            $search = $request->get('search',[]);
            $order_products = OnbuyOrderProductModel::join('onbuy_orders','onbuy_orders.order_id','=','onbuy_order_products.order_id')->when($search ,function ($query) use ($search){
                foreach($search as $field => $value)
                {
                    if($value)
                    {
                        switch ($field)
                        {
                            case 'onbuy_order_products.sku':
                                $query->where('onbuy_order_products.sku',$value);
                                break;
                            case 'date':
                                $date = explode('~', $value);
                                $query->where('onbuy_orders.date','>=', $date[0].' 00:00:00')->where('onbuy_orders.date','<=', $date[1]." 23:59:59");
                                break;
                            default :
                                $query->where($field,'like','%'.$value.'%');
                                break;
                        }

                    }

                }
            });
            $order_products = $order_products->orderBy('onbuy_orders.date','desc')->paginate($request->get('limit',50),['onbuy_orders.order_id','onbuy_orders.paypal_capture_id','onbuy_orders.date','onbuy_orders.status','onbuy_order_products.image_urls','onbuy_order_products.name','onbuy_order_products.sku','onbuy_order_products.expected_dispatch_date','onbuy_order_products.quantity','onbuy_order_products.tracking_number','onbuy_order_products.tracking_supplier_name','onbuy_order_products.tracking_url','onbuy_order_products.unit_price','onbuy_order_products.total_price','onbuy_order_products.commission_fee_including_tax']);
            foreach ($order_products as $key=> $order_product)
            {
                $order_product->product_url = OnbuyProductModel::where('sku',$order_product['sku'])->value('product_url');
            }

            return $this->response
                ->success()
                ->count($order_products->total())
                ->data($order_products->toArray()['data'])
                ->output();

        }

        return $this->response->title("onbuy 订单")
            ->view('onbuy.order.index')
            ->data(['limit' => $request->get('limit',50)])
            ->output();
    }
    public function products(Request $request)
    {
        if ($this->response->typeIs('json')) {
            $search = $request->get('search',[]);
            $order_products = OnbuyOrderProductModel::join('onbuy_orders','onbuy_orders.order_id','=','onbuy_order_products.order_id')
                ->join('onbuy_products','onbuy_products.sku','=','onbuy_order_products.sku')
                ->selectRaw("onbuy_order_products.*,SUM(onbuy_order_products.quantity) as total_quantity, (SUM(onbuy_order_products.quantity) - `onbuy_products`.`out_inventory`) as need_out, onbuy_products.product_url,onbuy_products.inventory,onbuy_products.out_inventory,onbuy_products.id as product_id,onbuy_products.purchase_url")
                ->whereIn('onbuy_orders.status',['Awaiting Dispatch','Dispatched','Partially Dispatched','Complete'])
                //->whereRaw('need_out > 0')
                ->when($search ,function ($query) use ($search){
                    foreach($search as $field => $value)
                    {
                        if($value) {
                            switch ($field) {
                                case 'onbuy_order_products.sku':
                                    $query->where('onbuy_order_products.sku', $value);
                                    break;
                                case 'date':
                                    $date = explode('~', $value);
                                    $query->where('onbuy_orders.date','>=', $date[0].' 00:00:00')->where('onbuy_orders.date','<=', trim($date[1])." 23:59:59");
                                    break;
                                default :
                                    $query->where($field, 'like', '%' . $value . '%');
                                    break;
                            }
                        }
                    }
                });
            $order_products = $order_products
                ->groupBy('onbuy_order_products.sku')
                ->orderBy('need_out','desc')
                ->orderBy('onbuy_orders.date','desc')
                ->paginate($request->get('limit',50));

            foreach ($order_products as $key=> $order_product)
            {
                /*
                $product = OnbuyProductModel::where('sku',$order_product['sku'])->first(['product_url','id','out_inventory','inventory','purchase_url']);
                if($product)
                {
                    $order_product->product_url = $product->product_url;
                    $order_product->inventory = $product->inventory;
                    $order_product->out_inventory = $product->out_inventory;
                    $order_product->product_id = $product->id;
                    $order_product->purchase_url = $product->purchase_url;
                }else{
                    $order_product->product_url = '';
                    $order_product->inventory = 0;
                    $order_product->out_inventory = 0;
                    $order_product->product_id = 0;
                    $order_product->purchase_url = '';

                }*/
                $order_product->need_purchase = $order_product->total_quantity - $order_product->inventory - $order_product->out_inventory;
            }

            return $this->response
                ->success()
                ->count($order_products->total())
                ->data($order_products->toArray()['data'])
                ->output();

        }
        return $this->response->title("onbuy 产品出单量")
            ->view('onbuy.order.products')
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
    public function syncUpdate(Request $request)
    {
        try {
            $data = $request->all();
            $order_ids = $data['order_ids'];
            $order_ids = implode(",",$order_ids);
            $onbuy_token = getOnbuyToken();
            $orders = new Order($onbuy_token);

            $orders->getOrder(
                [
                    'status' => 'all',
                    'order_ids' => $order_ids,
                ],
                [
                    'created' => 'desc'
                ]
            );
            $orders = $orders->getResponse();
            if(count($orders['results']) !=0 )
            {
                $this->orderService->syncUpdate($orders['results']);
            }

            return $this->response->message(trans('messages.operation.success'))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('onbuy/order'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('onbuy/order'))
                ->redirect();
        }
    }
    public function sync()
    {
        $this->orderService->syncHandle();
        return $this->response->message(trans('messages.operation.success'))
            ->status("success")
            ->http_code(202)
            ->url(guard_url('onbuy/order'))
            ->redirect();
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
                ->url(guard_url('onbuy/order'))
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
                ->url(guard_url('onbuy/order'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('onbuy/order'))
                ->redirect();
        }
    }
    public function getWinning()
    {
        $this->list_service->restorePrice();
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
