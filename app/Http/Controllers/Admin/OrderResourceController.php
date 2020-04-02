<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\OutputServerMessageException;
use App\Exports\PurchaseOrderExport;
use App\Exports\QuotationListExport;
use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Customer;
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

class OrderResourceController extends BaseController
{
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
                ->orderBy('id','desc')
                ->paginate($limit);

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

        return $this->response->title(trans('order.name'))
            ->view('order.create')
            ->data(compact('order','salesmen'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $salesman = $this->salesmanRepository->find($attributes['salesman_id']);
            $attributes['salesman_name'] = $salesman->name;
            $attributes['salesman_en_name'] = $salesman->en_name;

            $customer = $this->customerRepository->find($attributes['customer_id']);
            $attributes['customer_name'] = $customer->name;
            $attributes['order_sn'] = build_order_sn();

            $carts = $attributes['carts'];
            $purchase_price = $selling_price = $number = 0;
            foreach ($carts as $key => $cart)
            {
                $purchase_price += $cart['purchase_price'] * $cart['number'];
                $selling_price += $cart['selling_price'] * $cart['number'];
                $number += $cart['number'];
            }
            $attributes['purchase_price'] = $purchase_price;
            $attributes['selling_price'] = $selling_price;
            $attributes['number'] = $number;

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

        return $this->response->title(trans('app.view') . ' ' . trans('order.name'))
            ->data(compact('order','order_goods_list'))
            ->view('order.show')
            ->output();
    }
    public function update(Request $request,Order $order)
    {
        try {
            $attributes = $request->all();

            $salesman = $this->salesmanRepository->find($attributes['salesman_id']);
            $attributes['salesman_name'] = $salesman->name;
            $customer = $this->customerRepository->find($attributes['customer_id']);
            $attributes['customer_name'] = $customer->name;

            $carts = $attributes['carts'];
            $purchase_price = $selling_price = $number = 0;
            foreach ($carts as $key => $cart)
            {
                $purchase_price += $cart['purchase_price'] * $cart['number'];
                $selling_price += $cart['selling_price'] * $cart['number'];
                $number += $cart['number'];
            }
            $attributes['purchase_price'] = $purchase_price;
            $attributes['selling_price'] = $selling_price;
            $attributes['number'] = $number;

            $order->update($attributes);

            foreach ($carts as $key => $cart)
            {
                $data = [
                    'purchase_price' => $cart['purchase_price'],
                    'selling_price' => $cart['selling_price'],
                    'number' => $cart['number'],
                ];
                $this->orderGoodsRepository->update($data,$cart['id']);
            }

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

    public function downloadPurchaseOrder(Request $request)
    {
        $data = $request->all();
        $ids = $data['ids'];
        $name = '采购表'.date('YmdHis').'.xlsx';
        return Excel::download(new PurchaseOrderExport($ids), $name);
    }
    public function downloadQuotationList(Request $request)
    {
        $data = $request->all();
        $ids = $data['ids'];
        $name = '报价表'.date('YmdHis').'.xlsx';
        return Excel::download(new QuotationListExport($ids), $name);
    }
    //付款
    public function pay(Request $request)
    {
        try {
            $attributes = $request->all();
            $id = $attributes['id'];
            $order = $this->repository->find($id);
            $payment = $this->paymentRepository->find($attributes['payment_id']);
            if($order->pay_status == 'paid')
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $order->update([
                'pay_status' => 'paid',
                'payment_id' => $payment->id,
                'payment_name' => $payment->name,
                'payment_sn' => $attributes['payment_sn']
            ]);

            return $this->response->message(trans('messages.operation.success'))
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
    //发货
    public function toDelivery(Request $request)
    {
        try {
            $attributes = $request->all();
            $id = $attributes['id'];
            $order = $this->repository->find($id);

            if($order->shipping_status == 'shipped')
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $order->update([
                'shipping_status' => 'shipped',
                'tracking_number' => $attributes['tracking_number']
            ]);

            return $this->response->message(trans('messages.operation.success'))
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
    //取消
    public function cancel(Request $request)
    {
        try {
            $attributes = $request->all();
            $id = $attributes['id'];
            $order = $this->repository->find($id);

            if($order->shipping_status == 'shipped')
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $order->update([
                'order_status' => 'cancelled',
                'pay_status' => 'refunded',
            ]);

            return $this->response->message(trans('messages.operation.success'))
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
    //收获
    public function receive(Request $request)
    {
        try {
            $attributes = $request->all();
            $id = $attributes['id'];
            $order = $this->repository->find($id);

            if($order->shipping_status != 'shipped')
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $order->update([
                'shipping_status' => 'received',
            ]);

            return $this->response->message(trans('messages.operation.success'))
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
    public function returnOrder(Request $request)
    {
        try {
            $attributes = $request->all();
            $id = $attributes['id'];
            $order = $this->repository->find($id);

            if($order->shipping_status != 'received')
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $order->update([
                'order_status' => 'returned',
                'shipping_status' => 'refunded',
                'pay_status' => 'refunded'
            ]);

            return $this->response->message(trans('messages.operation.success'))
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
