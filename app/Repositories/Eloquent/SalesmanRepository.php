<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Models\NewCustomer;
use App\Models\Order;
use App\Repositories\Eloquent\SalesmanRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SalesmanRepository extends BaseRepository implements SalesmanRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.salesman.salesman.search');
    }
    public function model()
    {
        return config('model.salesman.salesman.model');
    }
    public function getActiveSalesmen()
    {
        return $this->where('active',1)->orderBy('active','desc')->orderBy('order','asc')->orderBy('id','desc')->get();
    }
    public function getSalesmen()
    {
        return $this->orderBy('active','desc')->orderBy('order','asc')->orderBy('id','desc')->get();
    }
    public function getAssessment($salesman_id,$first_day,$last_day)
    {
        //WhatsApp
        $create_whatsapp_count = NewCustomer::where('salesman_id',$salesman_id)
            ->whereBetween('created_at',[$first_day,$last_day])
            ->whereNotNull('whatsapp')
            ->count();
        //收集客户数
        $create_new_customer_count = NewCustomer::where('salesman_id',$salesman_id)
            ->whereBetween('created_at',[$first_day,$last_day])
            ->count();
        //新客户数
        $create_customer_count = Customer::where('salesman_id',$salesman_id)
            ->whereBetween('created_at',[$first_day,$last_day])
            ->count();

        //首购单数
        $new_purchase_order_count = Order::selectRaw('count(*) as count')
            ->where('salesman_id',$salesman_id)
            ->where('pay_status','paid')
            ->whereBetween('paid_at',[$first_day,$last_day])
            ->whereRaw("(select count(*) from orders as B where B.customer_id = orders.customer_id AND B.pay_status = 'paid' AND B.paid_at < '".$first_day."') = 0 ")
            ->groupBy('customer_id')->get()->count();
        //回购单数
        $order = Order::where('salesman_id',$salesman_id)
            ->where('pay_status','paid')
            ->whereBetween('paid_at',[$first_day,$last_day])
            ->count();
        $repurchase_order_count = $order-$new_purchase_order_count;

        //给客户发报价单，3人/天
        $quotation_customer_count = Order::where('salesman_id',$salesman_id)
            ->whereBetween('created_at',[$first_day,$last_day])
            ->groupBy('customer_id')->get()->count();
        return compact('create_whatsapp_count','create_new_customer_count','create_customer_count','new_purchase_order_count','repurchase_order_count','quotation_customer_count');
    }
}