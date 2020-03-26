<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Order;
use App\Models\OrderGoods;
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
            $this->repository->delete([$order->id]);

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
