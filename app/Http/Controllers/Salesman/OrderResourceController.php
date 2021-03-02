<?php

namespace App\Http\Controllers\Salesman;

use App\Exports\PurchaseOrderExport;
use App\Exports\QuotationListExport;
use App\Http\Controllers\Salesman\ResourceController as BaseController;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\GoodsAttributeValue;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\Payment;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\OrderGoodsRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\SalesmanRepository;
use App\Repositories\Eloquent\SupplierRepository;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\OrderRepository;
use Excel;
use Auth;
use App\Traits\Order\Handle as OrderHandle;

class OrderResourceController extends BaseController
{
    use OrderHandle;

    public function __construct(
        OrderRepository $orderRepository,
        OrderGoodsRepository $orderGoodsRepository,
        SalesmanRepository $salesmanRepository,
        CustomerRepository $customerRepository,
        SupplierRepository $supplierRepository,
        PaymentRepository $paymentRepository
    )
    {
        parent::__construct();
        $this->repository = $orderRepository;
        $this->orderGoodsRepository = $orderGoodsRepository;
        $this->salesmanRepository = $salesmanRepository;
        $this->customerRepository = $customerRepository;
        $this->supplierRepository = $supplierRepository;
        $this->paymentRepository = $paymentRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $orders = $this->repository
                ->where('salesman_id',Auth::user()->id)
                ->orderBy('id','desc')
                ->paginate($limit,['id','order_sn','customer_id','customer_name','address','salesman_id','salesman_name','selling_price','number','order_status','shipping_status','pay_status','payment_id','payment_name','payment_sn','tracking_number','weight','freight','total','paypal_fee','remark','created_at']);
            foreach ($orders as $key => $order)
            {
                $order->goods_list = $this->orderGoodsRepository->getOrderGoodsList($order->id);
            }
            return $this->response
                ->success()
                ->count($orders->total())
                ->data($orders->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('order.name'))
            ->view('order.index')
            ->output();
    }
    public function create(Request $request)
    {
        $order = $this->repository->newInstance([]);

        $salesmen = $this->salesmanRepository->getActiveSalesmen();

        $freight_area_code = 'US';

        return $this->response->title(trans('order.name'))
            ->view('order.create')
            ->data(compact('order','salesmen','freight_area_code'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $attributes['salesman_id'] = Auth::user()->id;
            $attributes['salesman_name'] = Auth::user()->name;
            $attributes['salesman_en_name'] = Auth::user()->en_name;

            $customer = $this->customerRepository->find($attributes['customer_id']);
            $attributes['customer_name'] = $customer->name;
            $attributes['order_sn'] = build_order_sn();
            $freight_area_code = $customer->area_code ?? 'US';

            $carts = $attributes['carts'];
            $purchase_price = $selling_price = $number = $weight = $freight = $paypay_fee = 0;
            $freight_category_id = 0;
            foreach ($carts as $key => $cart)
            {
                if(!$cart['attribute_id']) {
                    $carts[$key]['purchase_price'] = $cart['purchase_price'] = Goods::where('id',$cart['goods_id'])->value('purchase_price');
                }else{
                    $carts[$key]['purchase_price'] = $cart['purchase_price'] = GoodsAttributeValue::where('id',$cart['goods_attribute_value_id'])->value('purchase_price');
                }
                $purchase_price += $cart['purchase_price'] * $cart['number'];
                $selling_price += $cart['selling_price'] * $cart['number'];
                $number += $cart['number'];
                $weight += $cart['weight'] * $cart['number'];
                $freight_category_id = $cart['freight_category_id'];
            }
            $freight = $freight_category_id ? get_freight($freight_area_code,$freight_category_id,$weight) : 0;
            $paypal_fee = floor((($selling_price+$freight) * setting('paypal_fee')) * 100)/100;
            $attributes['purchase_price'] = $purchase_price;
            $attributes['selling_price'] = $selling_price;
            $attributes['number'] = $number;
            $attributes['paypal_fee'] = $paypal_fee;
            $attributes['weight'] = $weight;
            $attributes['freight'] = $freight;
            $attributes['total'] = $selling_price + $freight + $paypal_fee;

            $order = $this->repository->create($attributes);

            $data = [];
            foreach ($carts as $key => $cart)
            {
                $supplier = $this->supplierRepository->getSupplier($cart['goods_id']);
                $data[$key] = [
                    'order_id' => $order->id,
                    'order_sn' => $order->order_sn,
                    'goods_id' => $cart['goods_id'],
                    'goods_name' => $cart['goods_name'],
                    'attribute_value_id' => $cart['attribute_value_id'] ?? 0,
                    'attribute_value' => $cart['attribute_value'] ?? '',
                    'goods_attribute_value_id' => $cart['goods_attribute_value_id'] ?? 0,
                    'purchase_price' => $cart['purchase_price'],
                    'selling_price' => $cart['selling_price'],
                    'number' => $cart['number'],
                    'supplier_id' => $supplier['id'],
                    'supplier_name' => $supplier['name'],
                    'weight' => $cart['weight'],
                    'freight_category_id' => $cart['freight_category_id'],
                    'remark' => $cart['remark'] ?? '',
                ];
            }
            OrderGoods::insert($data);
            Customer::where('id',$customer->id)->increment('order_count');
            Customer::where('id',$customer->id)->update([
                'address' =>  $attributes['address']
            ]);
            return $this->response->message(trans('messages.success.created', ['Module' => trans('order.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('order'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('order'))
                ->redirect();
        }
    }
    public function show(Request $request,Order $order)
    {
        $order_goods_list = $this->orderGoodsRepository->getOrderGoodsList($order->id);
        $customer = $this->customerRepository->find($order->customer_id);
        $freight_area_code = $customer->area_code ?? 'US';

        return $this->response->title(trans('app.view') . ' ' . trans('order.name'))
            ->data(compact('order','order_goods_list','customer','freight_area_code'))
            ->view('order.show')
            ->output();
    }
    public function update(Request $request,Order $order)
    {
        try {
            $attributes = $request->all();

            if(isset($attributes['customer_id']) && $attributes['customer_id']) {
                $customer = $this->customerRepository->find($attributes['customer_id']);
                $attributes['customer_name'] = $customer->name;
                $freight_area_code = $customer->area_code ?? 'US';
            }

            if(isset($attributes['carts']) && $attributes['carts']) {
                $carts = $attributes['carts'];
                $selling_price = $number = $weight = $freight = 0;
                $freight_category_id = 0;
                foreach ($carts as $key => $cart) {

                    $selling_price += $cart['selling_price'] * $cart['number'];
                    $number += $cart['number'];
                    $weight += $cart['weight'] * $cart['number'];
                    $freight_category_id = $cart['freight_category_id'];
                }
                $freight = $freight_category_id ? get_freight($freight_area_code,$freight_category_id,$weight) : 0;
                $paypal_fee = floor((($selling_price+$freight) * setting('paypal_fee')) * 100)/100;
                $attributes['selling_price'] = $selling_price;
                $attributes['number'] = $number;
                $attributes['paypal_fee'] = $paypal_fee;
                $attributes['weight'] = $weight;
                $attributes['freight'] = $freight;
                $attributes['total'] = $selling_price + $freight + $paypal_fee;

                foreach ($carts as $key => $cart) {
                    $data = [
                        'selling_price' => $cart['selling_price'],
                        'number' => $cart['number'],
                        'remark' => $cart['remark'] ?? '',
                        'weight' => $cart['weight'],
                    ];
                    $this->orderGoodsRepository->update($data, $cart['id']);
                }

            }

            $order->update($attributes);
            return $this->response->message(trans('messages.success.updated', ['Module' => trans('order.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('order'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('order/' . $order->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,Order $order)
    {
        try {
            $this->repository->delete([$order->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('order.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('order'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('order'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->delete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('order.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('order'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('order'))
                ->redirect();
        }
    }

}
