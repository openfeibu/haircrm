<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Salesman\ResourceController as BaseController;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\Goods;
use App\Models\GoodsAttributeValue;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\OrderGoodsRepository;
use App\Repositories\Eloquent\SalesmanRepository;
use App\Repositories\Eloquent\SupplierRepository;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\OrderRepository;

class OrderGoodsResourceController extends BaseController
{
    public function __construct(
        OrderRepository $orderRepository,
        OrderGoodsRepository $orderGoodsRepository,
        SalesmanRepository $salesmanRepository,
        CustomerRepository $customerRepository,
        SupplierRepository $supplierRepository
    )
    {
        parent::__construct();
        $this->repository = $orderGoodsRepository;
        $this->orderRepository = $orderRepository;
        $this->salesmanRepository = $salesmanRepository;
        $this->customerRepository = $customerRepository;
        $this->supplierRepository = $supplierRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $order_goods_list = $this->repository
                ->orderBy('id','desc')
                ->paginate($limit);

            return $this->response
                ->success()
                ->count($order_goods_list->total())
                ->data($order_goods_list->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('order_goods.name'))
            ->view('order_goods.index')
            ->output();
    }
    public function create(Request $request)
    {

    }
    public function store(Request $request)
    {
        try {
            $cart = $request->all();
            $order = $this->orderRepository->find($cart['order_id']);
            $supplier = $this->supplierRepository->getSupplier($cart['goods_id']);
            if(!$cart['attribute_id']) {
                $cart['purchase_price'] = Goods::where('id',$cart['goods_id'])->value('purchase_price');
            }else{
                $cart['purchase_price'] = GoodsAttributeValue::where('id',$cart['goods_attribute_value_id'])->value('purchase_price');
            }
            $data = [
                'order_id' => $order->id,
                'order_sn' => $order->order_sn,
                'goods_id' => $cart['goods_id'],
                'goods_name' => $cart['goods_name'],
                'attribute_value_id' => $cart['attribute_value_id'] ?? 0,
                'attribute_value' => $cart['attribute_value'] ?? '',
                'goods_attribute_value_id' => $cart['goods_attribute_value_id'] ?? 0,
                'purchase_price' => $cart['purchase_price'],
                'selling_price' => $cart['selling_price'],
                'number' => 1,
                'supplier_id' => $supplier['id'],
                'supplier_name' => $supplier['name'],
                'supplier_code' => $supplier['code'],
                'weight' => $cart['weight'],
                'freight_category_id' => $cart['freight_category_id'],
                'remark' => $cart['remark'] ?? '',
            ];
            $customer = $this->customerRepository->find($order->customer_id);
            $freight_area_code = $customer->area_code ?? 'US';
            $weight = $order['weight'] + $cart['weight'];
            $freight =  get_freight($freight_area_code,$cart['freight_category_id'],$weight) ;
            $selling_price = $order['selling_price'] + $cart['selling_price'];
            $paypal_fee = floor((($selling_price+$freight) * setting('paypal_fee')) * 100)/100;

            $order_goods = OrderGoods::create($data);
            $order = $this->orderRepository->update([
                'purchase_price' => $order['purchase_price'] + $cart['purchase_price'],
                'selling_price' => $selling_price,
                'number' => $order['number'] + 1,
                'weight' => $weight,
                'freight' => $freight,
                'paypal_fee' => $paypal_fee,
                'total_price' => $selling_price + $freight + $paypal_fee
            ],$order->id);

            $cart['id'] = $order_goods['id'];

            return $this->response->message(trans('messages.success.created', ['Module' => trans('goods.name')]))
                ->code(0)
                ->data($cart)
                ->status('success')
                ->url(guard_url('orders'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('orders'))
                ->redirect();
        }
    }
    public function show(Request $request,Order $order)
    {

    }
    public function update(Request $request,Order $order)
    {

    }
    public function destroy(Request $request,OrderGoods $order_good)
    {
        try {

            $order= $this->orderRepository->find($order_good->order_id);
            $customer = $this->customerRepository->find($order->customer_id);
            $freight_area_code = $customer->area_code ?? 'US';

            $weight = $order->weight - $order_good->weight * $order_good->number;
            $freight = $order_good->freight_category_id ? get_freight($freight_area_code, $order_good->freight_category_id,$weight) : 0;
            $selling_price = $order['selling_price'] - $order_good->selling_price * $order_good->number;
            $paypal_fee = floor((($selling_price+$freight) * setting('paypal_fee')) * 100)/100;

            $this->repository->delete([$order_good->id]);
            $order = $this->orderRepository->update([
                'purchase_price' => $order['purchase_price'] - $order_good->purchase_price * $order_good->number,
                'selling_price' => $selling_price,
                'number' => $order['number'] - $order_good['number'],
                'weight' => $weight,
                'freight' => $freight,
                'paypal_fee' => $paypal_fee,
                'total_price' => $selling_price + $freight + $paypal_fee
            ],$order->id);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('order_goods.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('order_goods'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('order_goods'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {

    }
}
