<?php

namespace App\Http\Controllers\Admin\Onbuy;

use App\Exports\Onbuy\OrderExpressFourPxExport;
use App\Exports\Onbuy\OrderExpressHualeiExport;
use App\Exports\Onbuy\OrderExpressYanwenExport;
use App\Http\Controllers\Admin\Onbuy\BaseController;
use App\Imports\Onbuy\OrderExpressImport;
use App\Models\Onbuy\Product as OnbuyProductModel;
use App\Models\Onbuy\Order as OnbuyOrderModel;
use App\Models\Onbuy\OrderProduct as OnbuyOrderProductModel;
use App\Models\Onbuy\SellerProduct;
use App\Services\Paypal\TrackingService;
use Illuminate\Http\Request;
use Xigen\Library\OnBuy\Product\Product;
use Xigen\Library\OnBuy\Product\Listing;
use Xigen\Library\OnBuy\Order\Order;
use App\Services\Onbuy\OrderService;
use App\Models\Onbuy\Onbuy;
use DB;
use Excel;

class OrderResourceController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $onbuy_list = Onbuy::getAll();
        $search = $request->get('search',[]);
        if(!isset($search['onbuy_orders.seller_id']) || !$search['onbuy_orders.seller_id'])
        {
            $search['onbuy_orders.seller_id'] = $onbuy_list->toArray()[0]['seller_id'];
        }
        if ($this->response->typeIs('json')) {
            $orders = OnbuyOrderModel::join('onbuy_order_products','onbuy_orders.order_id','=','onbuy_order_products.order_id')
                ->when($search ,function ($query) use ($search){
                    foreach($search as $field => $value)
                    {
                        if($value)
                        {
                            switch ($field)
                            {
                                case 'onbuy_order_products.name':
                                    $query->where($field,'like','%'.$value.'%');
                                    break;
                                case 'onbuy_orders.date':
                                    $date = explode('~', $value);
                                    $query->where($field,'>=', $date[0].' 00:00:00')->where($field,'<=', $date[1]." 23:59:59");
                                    break;
                                default :
                                    $query->where($field,$value);
                                    break;
                            }

                        }

                    }
                });
            $orders = $orders->groupBy('onbuy_orders.order_id')->orderBy('onbuy_orders.date','desc')->paginate($request->get('limit',50),['onbuy_orders.*']);


            $gbp_to_rmb = (float)setting('gbp_to_rmb');
            $profit_expect = 0;
            foreach ($orders as $key=> $order)
            {

                $order_products = OnbuyOrderProductModel::join('onbuy_products','onbuy_products.sku','=','onbuy_order_products.sku')->where('onbuy_order_products.order_id',$order->order_id)->get(['onbuy_order_products.image_urls','onbuy_order_products.name','onbuy_order_products.sku','onbuy_order_products.expected_dispatch_date','onbuy_order_products.quantity','onbuy_order_products.tracking_number','onbuy_order_products.tracking_supplier_name','onbuy_order_products.tracking_url','onbuy_order_products.unit_price','onbuy_order_products.total_price','onbuy_order_products.commission_fee_including_tax','onbuy_products.product_url','onbuy_products.purchase_price','onbuy_products.weight']);
                $weight = 0;
                $total_purchase_price = 0;
                foreach ($order_products as $product_key => $order_product)
                {
                    $order_product->total_purchase_price = $order_product->purchase_price *  $order_product->quantity;
                    $weight += $order_product->weight ? $order_product->weight * $order_product->quantity : 0;
                    $total_purchase_price += $order_product->total_purchase_price;
                }
                $order->weight = $weight;
                $order->total_purchase_price = $total_purchase_price;
                $order->order_products = $order_products;
                $order->freight_expect = international_freight($weight);

                $order->cost = $order->shipping_fee ? $total_purchase_price+$order->shipping_fee :  $total_purchase_price+$order->freight_expect;

				//$order->paypal_fee = bcadd(bcmul($order->price_total, 0.044,6),0.2,6);
	            $order->paypal_fee = round($order->price_total * 0.044 + 0.2,2);
                $price_gbp_to_rmb = round(($order->price_total - $order->fee_total_fee_including_vat- $order->tax_total - $order->paypal_fee) * $gbp_to_rmb,2);

                $order->profit_expect = $order->status == "Refunded" ? 0 : round($price_gbp_to_rmb - $order->cost,2);

                $profit_expect += $order->profit_expect;
            }
            $profit_expect = round($profit_expect ,2);
            return $this->response
                ->success()
                ->count($orders->total())
                ->data($orders->toArray()['data'])
                ->totalRow(compact('profit_expect'))
                ->output();

        }

        return $this->response->title("onbuy 订单")
            ->view('onbuy.order.index')
            ->data([
                'limit' => $request->get('limit',50),
                'search' => $search,
                'onbuy_list' => $onbuy_list
            ])
            ->output();

    }
    public function products(Request $request)
    {
        $onbuy_list = Onbuy::getAll();
        if ($this->response->typeIs('json')) {
            $search = $request->get('search',[]);
//            if(!isset($search['onbuy_orders.seller_id']) || !$search['onbuy_orders.seller_id'])
//            {
//                $search['onbuy_orders.seller_id'] = $onbuy_list->toArray()[0]['seller_id'];
//            }
            $order_products = OnbuyOrderProductModel::join('onbuy_orders','onbuy_orders.order_id','=','onbuy_order_products.order_id')
                ->join('onbuy_products','onbuy_products.sku','=','onbuy_order_products.sku')
                ->selectRaw("onbuy_order_products.*,SUM(onbuy_order_products.quantity) as total_quantity, (SUM(onbuy_order_products.quantity) - `onbuy_products`.`out_inventory`) as need_out, onbuy_products.product_url,onbuy_products.inventory,onbuy_products.out_inventory,onbuy_products.id as product_id,onbuy_products.purchase_url,onbuy_products.purchase_price,onbuy_products.ch_name,onbuy_products.en_name")
                ->whereIn('onbuy_orders.status',['Awaiting Dispatch','Dispatched','Partially Dispatched','Complete'])
	            ->where('onbuy_orders.is_refund',0)
                //->whereRaw('need_out > 0')
                ->when($search ,function ($query) use ($search){
                    foreach($search as $field => $value)
                    {
                        if($value) {
                            switch ($field) {
                                case 'onbuy_order_products.sku':
                                    $query->where('onbuy_order_products.sku', $value);
                                    break;
                                case 'onbuy_orders.seller_id':
                                    $query->where('onbuy_orders.seller_id', $value);
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
                $order_product->products = SellerProduct::join('onbuy','onbuy.seller_id','onbuy_seller_product.seller_id')
                    ->where('onbuy_seller_product.product_sku',$order_product->sku)
                    ->get(['onbuy.name as seller_name','onbuy.seller_id','onbuy_seller_product.product_sku']);
            }

            return $this->response
                ->success()
                ->count($order_products->total())
                ->data($order_products->toArray()['data'])
                ->output();

        }
        return $this->response->title("onbuy 产品出单量")
            ->view('onbuy.order.products')
            ->data(['limit' => $request->get('limit',50),'onbuy_list' => $onbuy_list])
            ->output();
    }
    public function update(Request $request, OnbuyOrderModel $order)
    {
        try {
            $attributes = $request->all();
	        $order->update($attributes);
            return $this->response->message(trans('messages.success.updated'))
                ->code(0)
                ->status('success')
                ->url(guard_url('onbuy/listing' . $order->id))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('onbuy/listing/' . $order->id))
                ->redirect();
        }

    }
    public function updateAddress(Request $request, OnbuyOrderModel $onbuy_order)
    {
	    try {
		    $attributes = $request->all();
		    $data = [
			    'name' => $attributes['name'] ?? '',
			    'line_1' => $attributes['line_1'] ?? '',
			    'line_2' => $attributes['line_2'] ?? '',
			    'line_3' => $attributes['line_3'] ?? '',
			    'town' => $attributes['town'] ?? '',
			    'county' => $attributes['county'] ?? '',
			    'postcode' => $attributes['postcode'] ?? '',
			    'country' => $attributes['country'] ?? '',
			    'country_code' => $attributes['country_code'] ?? '',
		    ];

		    $onbuy_order->update([
		    	'delivery_address' => json_encode($data),
		    ]);
		    return $this->response->message(trans('messages.success.updated'))
			    ->code(0)
			    ->status('success')
			    ->url(guard_url('onbuy/order' . $onbuy_order->id))
			    ->redirect();
	    } catch (Exception $e) {
		    return $this->response->message($e->getMessage())
			    ->code(400)
			    ->status('error')
			    ->url(guard_url('onbuy/order/' . $onbuy_order->id))
			    ->redirect();
	    }
	
    }
    public function syncUpdate(Request $request)
    {
        try {
            $data = $request->all();
            $order_ids = $data['order_ids'];
            $order_ids = implode(",",$order_ids);
            $onbuy_token = getOnbuyToken($data['seller_id']);
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
            $orderService = new OrderService($data['seller_id']);
            if(count($orders['results']) !=0 )
            {
                $orderService->syncUpdate($orders['results']);
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
    public function sync(Request $request)
    {
        $seller_id = $request->get('seller_id');
        $orderService = new OrderService($seller_id);
        $orderService->syncHandle();
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
    public function exportExpressYanwen(Request $request)
    {
        $data = $request->all();
        $ids = $data['ids'] ?? ['130','180'];
        $name = '燕文物流'.date('YmdHis').'.xlsx';
        $search = $request->input('search',[]);
        return Excel::download(new OrderExpressYanwenExport($ids,$search), $name);
    }
	public function exportExpressHualei(Request $request)
	{
		$data = $request->all();
		$ids = $data['ids'] ?? ['130','180'];
		$name = '华磊'.date('YmdHis').'.xlsx';
		$search = $request->input('search',[]);
		return Excel::download(new OrderExpressHualeiExport($ids,$search), $name);
	}
    public function exportExpressFourPx(Request $request)
    {
        $data = $request->all();
        $ids = $data['ids'] ?? ['130','180'];
        $name = '4PX'.date('YmdHis').'.xlsx';
        $search = $request->input('search',[]);
        return Excel::download(new OrderExpressFourPxExport($ids,$search), $name);
    }

    public function importExpress(Request $request)
    {

        set_time_limit(0);
        $file = $request->file;
        $seller_id = $request->get('seller_id');
        $express = $request->get('express');

        isVaildExcel($file);
        $res = (new OrderExpressImport())->toArray($file)[0];
        $res = array_filter($res);
        $all_sheet_count = count($res);

        switch ($express){
            case 'yanwen':
            case '4px':
                $config_express = config('express.'.$express);
                break;
            default:
                return $this->response->message("express 字段错误")
                    ->status("success")
                    ->code(400)
                    ->url(guard_url('onbuy/order'))
                    ->redirect();
                break;
        }
        $excel_key_arr = $config_express['excel'];

        $items = [];

        $header_arr = $res[0];
        $header_keys = [];

        $flip_header_arr = array_flip($res[0]);

        foreach ($excel_key_arr as $key => $header)
        {
            $header_keys[$header] = isset($flip_header_arr[$key]) ? $flip_header_arr[$key] : '';

        }

        $data = [];
        $salesmen = [];
        $success_count=0;
        $count = $all_sheet_count-1;
        for ($i=1;$i<$all_sheet_count;$i++)
        {
            if($res[$i])
            {
                foreach ($header_keys as $header_key => $header_i) {
                    $data[$i][$header_key] = $res[$i][$header_i] ?? '';
                }
                $data[$i]['tracking_supplier_name'] = $data[$i]['tracking_supplier_name'] ?: $config_express['tracking_supplier_name']['onbuy'];
                $data[$i]['paypal_tracking_supplier_name'] = $config_express['tracking_supplier_name']['paypal'];
                $data[$i]['tracking_url'] = $data[$i]['tracking_url'] ?: sprintf($config_express['tracking_url'],$data[$i]['tracking_number']);
            }
        }
        DB::beginTransaction();
        $logisticsInfo['trackers'] = [];
        try{
            if(!count($data))
            {
                return $this->response->message(trans("messages.excel.not_found_data"))
                    ->status("success")
                    ->code(400)
                    ->url(guard_url('onbuy/order'))
                    ->redirect();
            }

            $dispatch_orders = [];
            $i = 0;
            foreach($data as $key => $express)
            {

                if(!$express['order_id'] || !$express['tracking_number'])
                {
                    continue;
                }

                $dispatch_orders[$i] = [
                    'order_id' => $express['order_id'],
                    "tracking" => [
                        //"tracking_id" => "bar",
                        "supplier_name" =>  $express['tracking_supplier_name'],
                        "number" =>  $express['tracking_number'],
                        "url" =>  $express['tracking_url'],
                    ]
                ];
                $logisticsInfo['trackers'][$i] = [
                        'transaction_id'=> \App\Models\Onbuy\Order::where('order_id',$express['order_id'])->value('paypal_capture_id'),
                        'tracking_number'=> $express['tracking_number'],
                        'status'=>'SHIPPED',
                        'carrier'=> $express['paypal_tracking_supplier_name'],
                ];
                $i++;
                OnbuyOrderModel::where('status','Awaiting Dispatch')
                    ->where('order_id',$express['order_id'])
                    ->update([
                        'tracking_number' => $express['tracking_number'],
                        'tracking_supplier_name' => $express['tracking_supplier_name'],
                        'tracking_url' => $express['tracking_url'],
                        'status' => 'Dispatched'
                    ]);

                OnbuyOrderProductModel::where('order_id',$express['order_id'])
                    ->update([
                        'tracking_number' => $express['tracking_number'],
                        'tracking_supplier_name' => $express['tracking_supplier_name'],
                        'tracking_url' => $express['tracking_url'],
                    ]);

                $success_count++;
            }

            if(count($dispatch_orders)) {
                $onbuy_token = getOnbuyToken($seller_id);
                $order = new Order($onbuy_token);
                $order->dispatchOrder($dispatch_orders);
                $res = $order->getResponse();
            }
			/*
            if(count($logisticsInfo['trackers']))
            {
                $trackingService = new TrackingService($seller_id);
                $tracking_res = $trackingService->addTracking($logisticsInfo);
                if(isset($tracking_res['type']) && $tracking_res['type'] == 'error')
                {
                    return $this->response->message("paypal 跟踪物流信息失败，请查看日志文件")
                        ->status("success")
                        ->code(0)
                        ->url(guard_url('onbuy/order'))
                        ->redirect();
                }
            }
			*/

            if($res['success'])
            {
                DB::commit();
                return $this->response->message("共发现".$count."条数据，排除空行后共成功导入".$success_count."条")
                    ->status("success")
                    ->code(0)
                    ->url(guard_url('onbuy/order'))
                    ->redirect();
            }else{
                return $this->response->message("上传数据失败")
                    ->status("success")
                    ->code(400)
                    ->url(guard_url('onbuy/order'))
                    ->redirect();
            }

        }
        catch (Exception $e) {
            DB::rollback();
            return $this->response->message("上传数据失败")
                ->status("success")
                ->code(400)
                ->url(guard_url('onbuy/order'))
                ->redirect();
        }

    }
    public function importShippingFee(Request $request)
    {
        set_time_limit(0);
        $file = $request->file;
        $seller_id = $request->get('seller_id');
        $express = $request->get('express');
        isVaildExcel($file);
        $res = (new OrderExpressImport())->toArray($file)[0];
        $res = array_filter($res);
        $all_sheet_count = count($res);
        switch ($express){
            case 'yanwen':
                $initial_line = 3;
                $config_express = config('express.'.$express);
                break;
            case '4px':
                $initial_line = 0;
                $config_express = config('express.'.$express);
                break;
            default:
                return $this->response->message("express 字段错误")
                    ->status("success")
                    ->code(400)
                    ->url(guard_url('onbuy/order'))
                    ->redirect();
                break;
        }

        $excel_key_arr = $config_express['shipping_excel'];

        $header_keys = [];

        $flip_header_arr = array_flip($res[$initial_line]);

        foreach ($excel_key_arr as $key => $header)
        {
            $header_keys[$header] = isset($flip_header_arr[$key]) ? $flip_header_arr[$key] : '';

        }
        $data = [];
        $success_count=0;
        $count = $all_sheet_count-1;
        for ($i=$initial_line+1;$i<$all_sheet_count;$i++)
        {
            if($res[$i])
            {
                foreach ($header_keys as $header_key => $header_i) {
                    $data[$i][$header_key] = $res[$i][$header_i] ?? '';
                }
            }
        }
        DB::beginTransaction();
        try{
            if(!count($data))
            {
                return $this->response->message(trans("messages.excel.not_found_data"))
                    ->status("success")
                    ->code(400)
                    ->url(guard_url('onbuy/order'))
                    ->redirect();
            }

            foreach($data as $key => $item)
            {
                if(!$item['order_id'])
                {
                    continue;
                }
                $shipping_fee = floatval(str_replace(' CNY','',$item['shipping_fee']));
                var_dump($shipping_fee);exit;
                OnbuyOrderModel::where('order_id',$item['order_id'])
                    ->update([
                        'shipping_fee' => $shipping_fee,
                    ]);

                $success_count++;
            }

            DB::commit();
            return $this->response->message("共发现".$count."条数据，排除空行后共成功导入".$success_count."条")
                ->status("success")
                ->code(0)
                ->url(guard_url('onbuy/order'))
                ->redirect();

        }
        catch (Exception $e) {
            DB::rollback();
            return $this->response->message("上传数据失败")
                ->status("success")
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
