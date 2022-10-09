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
use DB;

class OrderResourceController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
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
            $order_products = $order_products->orderBy('onbuy_orders.date','desc')->paginate($request->get('limit',50));
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
                ->selectRaw("*,SUM(onbuy_order_products.quantity) as total_quantity ")
                ->whereIn('onbuy_orders.status',['Awaiting Dispatch','Dispatched','Partially Dispatched','Complete'])
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
                ->orderBy('onbuy_orders.date','desc')
                ->paginate($request->get('limit',50));

            foreach ($order_products as $key=> $order_product)
            {
                $product = OnbuyProductModel::where('sku',$order_product['sku'])->first(['product_url','id','total_in_inventory']);
                if($product)
                {
                    $order_product->product_url = $product->product_url;
                    $order_product->total_in_inventory = $product->total_in_inventory;
                    $order_product->product_id = $product->id;
                }else{
                    $order_product->product_url = '';
                    $order_product->total_in_inventory = 0;
                    $order_product->product_id = 0;

                }
                $order_product->inventory_balance = $order_product->total_in_inventory - $order_product->total_quantity;
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
                foreach ($orders['results'] as $key => $order)
                {

                    $data = [
                        'onbuy_internal_reference' => $order['onbuy_internal_reference'],
                        'date' => $order['date'],
                        'updated_at' => $order['updated_at'],
                        'cancelled_at' => $order['cancelled_at'],
                        'shipped_at' => $order['shipped_at'],
                        'status' => $order['status'],
                        'site_id' => $order['site_id'],
                        'site_name' => $order['site_name'],
                        'price_subtotal' => $order['price_subtotal'],
                        'price_delivery' => $order['price_delivery'],
                        'price_total' => $order['price_total'],
                        'price_discount' => $order['price_discount'],
                        'sales_fee_ex_VAT' => $order['sales_fee_ex_VAT'],
                        'sales_fee_inc_VAT' => $order['sales_fee_inc_VAT'],
                        'currency_code' => $order['currency_code'],
                        'dispatched' => $order['dispatched'],
                        'delivery_service' => $order['delivery_service'],
                        'stripe_transaction_id' => $order['stripe_transaction_id'],
                        'paypal_capture_id' => $order['paypal_capture_id'],
                        'buyer_name' => $order['buyer']['name'],
                        'buyer_email' => $order['buyer']['email'],
                        'buyer_phone' => $order['buyer']['phone'],
                        'buyer_ip_address' => $order['buyer']['ip_address'],
                        'billing_address' => json_encode($order['billing_address']),
                        'delivery_address' => json_encode($order['delivery_address']),
                        'fee_boost_marketing_fee_excluding_vat' => $order['fee']['boost_marketing_fee_excluding_vat'],
                        'fee_category_fee_excluding_vat' => $order['fee']['category_fee_excluding_vat'],
                        'fee_delivery_fee_excluding_vat' => $order['fee']['delivery_fee_excluding_vat'],
                        'fee_total_fee_excluding_vat' => $order['fee']['total_fee_excluding_vat'],
                        'fee_vat_rate' => $order['fee']['vat_rate'],
                        'fee_total_fee_including_vat' => $order['fee']['total_fee_including_vat'],
                        'tax_total' => $order['tax']['tax_total'],
                        'tax_subtotal' => $order['tax']['tax_subtotal'],
                        'tax_delivery' => $order['tax']['tax_delivery'],
                        'delivery_tag' => $order['delivery_tag'],
                    ];
                    OnbuyOrderModel::where('order_id',$order['order_id'])->update($data);
                }
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
        $this->syncHandle();
        return $this->response->message(trans('messages.operation.success'))
            ->status("success")
            ->http_code(202)
            ->url(guard_url('onbuy/order'))
            ->redirect();
    }
    public function syncHandle($offset=0,$limit=50)
    {
        $onbuy_token = getOnbuyToken();
        $orders = new Order($onbuy_token);
        $orders->getOrder(
            ['status' => 'all'],
            ['created' => 'desc'],
            $limit,
            $offset
        );
        $orders = $orders->getResponse();
        if(count($orders['results']) <=0 )
        {
            return true;
        }
        $data = [];
        $order_product_data = [] ;
        $picked = false;
        foreach ($orders['results'] as $key => $order)
        {
            $is_exist = OnbuyOrderModel::where('order_id',$order['order_id'])->value('id');
            if($is_exist)
            {
                $picked = true;
                break;
            }
            if(!$is_exist)
            {

                $data[$key] = [
                   'order_id' => $order['order_id'],
                   'onbuy_internal_reference' => $order['onbuy_internal_reference'],
                   'date' => $order['date'],
                   'updated_at' => $order['updated_at'],
                   'cancelled_at' => $order['cancelled_at'],
                   'shipped_at' => $order['shipped_at'],
                   'status' => $order['status'],
                   'site_id' => $order['site_id'],
                   'site_name' => $order['site_name'],
                   'price_subtotal' => $order['price_subtotal'],
                   'price_delivery' => $order['price_delivery'],
                   'price_total' => $order['price_total'],
                   'price_discount' => $order['price_discount'],
                   'sales_fee_ex_VAT' => $order['sales_fee_ex_VAT'],
                   'sales_fee_inc_VAT' => $order['sales_fee_inc_VAT'],
                   'currency_code' => $order['currency_code'],
                   'dispatched' => $order['dispatched'],
                   'delivery_service' => $order['delivery_service'],
                   'stripe_transaction_id' => $order['stripe_transaction_id'],
                   'paypal_capture_id' => $order['paypal_capture_id'],
                   'buyer_name' => $order['buyer']['name'],
                   'buyer_email' => $order['buyer']['email'],
                   'buyer_phone' => $order['buyer']['phone'],
                   'buyer_ip_address' => $order['buyer']['ip_address'],
                   'billing_address' => json_encode($order['billing_address']),
                   'delivery_address' => json_encode($order['delivery_address']),
                   'fee_boost_marketing_fee_excluding_vat' => $order['fee']['boost_marketing_fee_excluding_vat'],
                   'fee_category_fee_excluding_vat' => $order['fee']['category_fee_excluding_vat'],
                   'fee_delivery_fee_excluding_vat' => $order['fee']['delivery_fee_excluding_vat'],
                   'fee_total_fee_excluding_vat' => $order['fee']['total_fee_excluding_vat'],
                   'fee_vat_rate' => $order['fee']['vat_rate'],
                   'fee_total_fee_including_vat' => $order['fee']['total_fee_including_vat'],
                   'tax_total' => $order['tax']['tax_total'],
                   'tax_subtotal' => $order['tax']['tax_subtotal'],
                   'tax_delivery' => $order['tax']['tax_delivery'],
                   'delivery_tag' => $order['delivery_tag'],
               ];

                foreach($order['products'] as $product)
                {
                    $order_product_data[] = [
                        'order_id' => $order['order_id'],
                        'onbuy_internal_reference' => $product['onbuy_internal_reference'],
                        'name' => $product['name'],
                        'sku' => $product['sku'],
                        'seller_delivery_template_id' => $product['seller_delivery_template_id'],
                        'price_delivery_total' => $product['price_delivery_total'],
                        'condition' => $product['condition'],
                        'condition_id' => $product['condition_id'],
                        'quantity' => $product['quantity'],
                        'quantity_dispatched' => $product['quantity_dispatched'],
                        'unit_price' => $product['unit_price'],
                        'total_price' => $product['total_price'],
                        'expected_dispatch_date' => $product['expected_dispatch_date'],
                        'expected_delivery_date' => $product['expected_delivery_date'],
                        'file_location_prefix' => $product['file_location_prefix'],
                        'opc' => $product['opc'],
                        'image_urls' => json_encode($product['image_urls']),
                        'tax_delivery' => $product['tax']['tax_delivery'],
                        'tax_product' => $product['tax']['tax_product'],
                        'tax_total' => $product['tax']['tax_total'],
                        'commission_fee' => $product['fee']['commission_fee'],
                        'commission_fee_including_tax' => $product['fee']['commission_fee_including_tax'],
                    ];
                }
            }
        }
        //插入数据库
        if(count($data))
        {
            DB::table("onbuy_orders")->insert($data);
            if(count($order_product_data))
            {
                DB::table("onbuy_order_products")->insert($order_product_data);
            }

        }
        //还不是最新数据
        if(!$picked){
            $this->syncHandle($offset+$limit);
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
