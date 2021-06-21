<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\CustomerRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Models\Order;
use App\Models\Customer;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.customer.customer.search');
    }

    public function model()
    {
        return config('model.customer.customer.model');
    }
    public function getSalesmanCustomers($salesman_id)
    {
        return $this->where('salesman_id',$salesman_id)->orderBy('name','asc')->orderBy('id','desc')->get();
    }

    public function statistic($salesman_id)
    {
        $first = 1; //1表示每周星期一为开始日期 0表示每周日为开始日期
        $w = date('w',strtotime(date('Y-m-d'))); //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $this_week_first_day = date('Y-m-d 00:00:00',strtotime(date('Y-m-d')." -".($w ? $w - $first : 6).' days'));
        $this_week_last_day = date('Y-m-d 23:59:59',strtotime("$this_week_first_day +6 days"));
        $last_week_first_day = date('Y-m-d 00:00:00',strtotime("$this_week_first_day - 7 days"));//上周开始时间
        $last_week_last_day = date('Y-m-d 23:59:59',strtotime("$this_week_first_day - 1 days"));//上周结束时间

        $this_month_first_day =date('Y-m-01 00:00:00');
        $this_month_last_day = date('Y-m-d 23:59:59', strtotime("$this_month_first_day +1 month -1 day"));
        $last_month_first_day = date('Y-m-01 00:00:00', strtotime('-1 month'));
        $last_month_last_day = date('Y-m-t 23:59:59', strtotime('-1 month'));

        //周概览
        $this_week_quotation_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('created_at',[$this_week_first_day,$this_week_last_day])
            ->groupBy('customer_id')->get()->count();

        $last_week_quotation_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('created_at',[$last_week_first_day, $last_week_last_day])
            ->groupBy('customer_id')->get()->count();

        $this_week_add_customer_count = Customer::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('created_at',[$this_week_first_day,$this_week_last_day])
            ->count();

        $last_week_add_customer_count = Customer::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('created_at',[$last_week_first_day, $last_week_last_day])
            ->count();

        $this_week_purchase_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('paid_at',[$this_week_first_day,$this_week_last_day])
            ->where('pay_status','paid')
            ->whereRaw("(select count(*) from orders as B where B.customer_id = orders.customer_id AND B.paid_at < '".$this_week_first_day."' AND B.pay_status ='paid') = 0 ")
            ->groupBy('customer_id')->get()->count();

        $last_week_purchase_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('paid_at',[$last_week_first_day,$last_week_last_day])->where('pay_status','paid')
            ->whereRaw("(select count(*) from orders as B where B.customer_id = orders.customer_id AND B.paid_at < '".$last_week_first_day."' AND B.pay_status ='paid') = 0 ")
            ->groupBy('customer_id')->get()->count();

        $this_week_repurchase_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('paid_at',[$this_week_first_day,$this_week_last_day])
            ->where('pay_status','paid')
            ->whereRaw("(select count(*) from orders as B where B.customer_id = orders.customer_id  AND B.pay_status ='paid') > 1 ")
            ->groupBy('customer_id')->get()->count();

        $last_week_repurchase_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('paid_at',[$last_week_first_day,$last_week_last_day])->where('pay_status','paid')
            ->whereRaw("(select count(*) from orders as B where B.customer_id = orders.customer_id AND B.paid_at < '".$last_week_last_day."' AND B.pay_status ='paid') > 1 ")
            ->groupBy('customer_id')->get()->count();

        //月概览
        $this_month_quotation_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('created_at',[$this_month_first_day,$this_month_last_day])
            ->groupBy('customer_id')->get()->count();

        $last_month_quotation_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('created_at',[$last_month_first_day, $last_month_last_day])
            ->groupBy('customer_id')->get()->count();

        $this_month_add_customer_count = Customer::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('created_at',[$this_month_first_day,$this_month_last_day])
            ->count();

        $last_month_add_customer_count = Customer::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('created_at',[$last_month_first_day, $last_month_last_day])
            ->count();

        $this_month_purchase_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('paid_at',[$this_month_first_day,$this_month_last_day])
            ->where('pay_status','paid')
            ->whereRaw("(select count(*) from orders as B where B.customer_id = orders.customer_id AND B.paid_at < '".$this_month_first_day."' AND B.pay_status ='paid') = 0 ")
            ->groupBy('customer_id')->get()->count();

        $last_month_purchase_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('paid_at',[$last_month_first_day,$last_month_last_day])->where('pay_status','paid')
            ->whereRaw("(select count(*) from orders as B where B.customer_id = orders.customer_id AND B.paid_at < '".$last_month_first_day."' AND B.pay_status ='paid') = 0 ")
            ->groupBy('customer_id')->get()->count();

        $this_month_repurchase_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('paid_at',[$this_month_first_day,$this_month_last_day])
            ->where('pay_status','paid')
            ->whereRaw("(select count(*) from orders as B where B.customer_id = orders.customer_id  AND B.pay_status ='paid') > 1 ")
            ->groupBy('customer_id')->get()->count();

        $last_month_repurchase_customer_count = Order::when($salesman_id,function ($query) use ($salesman_id){
            return $query->where('salesman_id',$salesman_id);
        })
            ->whereBetween('paid_at',[$last_month_first_day,$last_month_last_day])->where('pay_status','paid')
            ->whereRaw("(select count(*) from orders as B where B.customer_id = orders.customer_id AND B.paid_at < '".$last_month_last_day."' AND B.pay_status ='paid') > 1 ")
            ->groupBy('customer_id')->get()->count();

        return compact('this_week_quotation_customer_count','last_week_quotation_customer_count','this_week_add_customer_count','last_week_add_customer_count','this_week_purchase_customer_count','last_week_purchase_customer_count','this_week_repurchase_customer_count','last_week_repurchase_customer_count','this_month_quotation_customer_count','last_month_quotation_customer_count','this_month_add_customer_count','last_month_add_customer_count','this_month_purchase_customer_count','last_month_purchase_customer_count','this_month_repurchase_customer_count','last_month_repurchase_customer_count');
    }

}