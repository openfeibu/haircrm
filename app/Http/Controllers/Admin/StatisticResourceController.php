<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\MailScheduleReport;
use App\Models\NewCustomer;
use App\Models\Salesman;
use App\Models\Order;
use App\Models\OrderGoods;
use Route;
use App\Traits\AdminUser\AdminUserPages;
use App\Http\Response\ResourceResponse;
use App\Traits\Theme\ThemeAndViews;
use App\Traits\AdminUser\RoutesAndGuards;

/**
 * Resource controller class for page.
 */
class StatisticResourceController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }
    public function trade(Request $request){
        $hot_goods_list = OrderGoods::join('orders','orders.id','=','order_goods.order_id')->where('orders.pay_status','paid')->groupBy('order_goods.goods_id')->orderBy('count','desc')->selectRaw('count(*) as count,sum(order_goods.number) as sum,order_goods.goods_id,CONCAT(order_goods.goods_name," ",order_goods.attribute_value) as goods_name')->limit(10)->get()->toArray();

        $hot_goods_name_list = array_column($hot_goods_list,'goods_name');
        $hot_goods_names = implode("<br />",$hot_goods_name_list);

        $begin_month = date('Y-m-01 00:00:00');
        $end_month = date('Y-m-d 23:59:59', strtotime("$begin_month +1 month -1 day"));
        $this_month_hot_goods_list = OrderGoods::join('orders','orders.id','=','order_goods.order_id')->where('orders.pay_status','paid')->whereBetween('orders.created_at',[$begin_month,$end_month])->groupBy('order_goods.goods_id')->orderBy('count','desc')->selectRaw('count(*) as count,sum(order_goods.number) as sum, order_goods.goods_id,CONCAT(order_goods.goods_name," ",order_goods.attribute_value) as goods_name')->limit(10)->get()->toArray();
        $this_month_hot_goods_name_list = array_column($this_month_hot_goods_list,'goods_name');
        $this_month_hot_goods_names = implode("<br />",$this_month_hot_goods_name_list);

        return $this->response->title(trans('statistic.name'))
            ->view('statistic.trade')
            ->data(compact('hot_goods_names','this_month_hot_goods_names','hot_goods_list','this_month_hot_goods_list'))
            ->output();
    }
    public function getTrading(Request $request)
    {
        try {
            $attributes = $request->all();
            $date_type = isset($attributes['date_type']) && $attributes['date_type'] ? $attributes['date_type'] : 'days';
            $salesman_id = isset($attributes['salesman_id']) && $attributes['salesman_id'] ? $attributes['salesman_id'] : '';
            $series_data = [];
            $order_count_arr = $turnover_arr =  [];
            switch ($date_type)
            {
                case 'days':
                    $date_arr = get_weeks();
                    foreach ($date_arr as $key => $date)
                    {
                        $order_count_arr[$key] = Order::when($salesman_id,function ($query) use ($salesman_id){
                            $query->where('salesman_id',$salesman_id);
                        })->whereBetween('paid_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->count();
                        $turnover_arr[$key] =  Order::when($salesman_id,function ($query) use ($salesman_id){
                            $query->where('salesman_id',$salesman_id);
                        })->whereBetween('paid_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->sum('paid_total');
                    }
                    break;
                case 'this_month':
                    $date_arr = get_month_days();
                    foreach ($date_arr as $key => $date)
                    {
                        if($date<= date('Y-m-d'))
                        {
                            $order_count_arr[] = Order::when($salesman_id,function ($query) use ($salesman_id){
                                $query->where('salesman_id',$salesman_id);
                            })->whereBetween('paid_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->count();
                            $turnover_arr[] =  Order::when($salesman_id,function ($query) use ($salesman_id){
                                $query->where('salesman_id',$salesman_id);
                            })->whereBetween('paid_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->sum('paid_total');
                        }else{
                            $order_count_arr[] = 0;
                            $order_count_arr[] = 0;
                        }
                    }
                    break;
                case 'last_month':
                    $date_arr = get_month_days(date("Y-m", strtotime("-1 month")));
                    foreach ($date_arr as $key => $date)
                    {
                        if($date<= date('Y-m-d'))
                        {
                            $order_count_arr[] = Order::when($salesman_id,function ($query) use ($salesman_id){
                                $query->where('salesman_id',$salesman_id);
                            })->whereBetween('paid_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->count();
                            $turnover_arr[] =  Order::when($salesman_id,function ($query) use ($salesman_id){
                                $query->where('salesman_id',$salesman_id);
                            })->whereBetween('paid_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->sum('paid_total');
                        }else{
                            $order_count_arr[] = 0;
                            $order_count_arr[] = 0;
                        }
                    }
                    break;
                case 'this_year':
                    $date_arr = get_months();
                    foreach ($date_arr as $key => $month)
                    {
                        $first_day = $month."-01 00:00:00";
                        $end_day = date('Y-m-d 23:59:59', strtotime("$first_day +1 month -1 day"));
                        if($month <= date('Y-m'))
                        {
                            $order_count_arr[] = Order::when($salesman_id,function ($query) use ($salesman_id){
                                $query->where('salesman_id',$salesman_id);
                            })->whereBetween('paid_at',[$first_day,$end_day])->where('pay_status','paid')->count();
                            $turnover_arr[] =  Order::when($salesman_id,function ($query) use ($salesman_id){
                                $query->where('salesman_id',$salesman_id);
                            })->whereBetween('paid_at',[$first_day,$end_day])->where('pay_status','paid')->sum('paid_total');
                        }else{
                            $order_count_arr[] = 0;
                            $order_count_arr[] = 0;
                        }
                    }
                    break;
                case 'last_year':
                    $date_arr = get_months(date("Y", strtotime("-1 year")));
                    foreach ($date_arr as $key => $month)
                    {
                        $first_day = $month."-01 00:00:00";
                        $end_day = date('Y-m-d 23:59:59', strtotime("$first_day +1 month -1 day"));
                        if($month <= date('Y-m'))
                        {
                            $order_count_arr[] = Order::when($salesman_id,function ($query) use ($salesman_id){
                                $query->where('salesman_id',$salesman_id);
                            })->whereBetween('paid_at',[$first_day,$end_day])->where('pay_status','paid')->count();
                            $turnover_arr[] =  Order::when($salesman_id,function ($query) use ($salesman_id){
                                $query->where('salesman_id',$salesman_id);
                            })->whereBetween('paid_at',[$first_day,$end_day])->where('pay_status','paid')->sum('paid_total');
                        }else{
                            $order_count_arr[] = 0;
                            $order_count_arr[] = 0;
                        }
                    }
                    break;
            }
            /*
            $series = [
                [
                    'name' => '成交量',
                    'type' => 'line',
                    'data' => $order_count_arr,
                ],
                [
                    'name' => '成交额',
                    'type' => 'line',
                    'data' => $turnover_arr,
                ]
            ];
            */
            $date_arr = array_values($date_arr);
            $order_count_arr = array_values($order_count_arr);
            $turnover_arr = array_values($turnover_arr);
            return $this->response
                ->success()
                ->data(compact('date_arr','order_count_arr','turnover_arr'))
                ->json();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('/'))
                ->redirect();
        }
    }
    public function monthNewCustomers()
    {
        $salesmen = Salesman::where('active','1')->where('monthly_performance_target','>','0')->orderBy('order','asc')->orderBy('id','desc')->get()->toArray();

        return $this->response->title(trans('statistic.name'))
            ->view('statistic.month_new_customer')
            ->data(compact('salesmen'))
            ->output();
    }
    public function getMonthNewCustomers(Request $request)
    {
        $year_month = $request->get('year_month',date('Y-m'));
        $salesman_id = $request->salesman_id;
        $date_arr = get_month_days($year_month);
        $new_customer_arr = [];
        foreach ($date_arr as $key => $date)
        {
            if($date<= date('Y-m-d'))
            {
                $new_customer_arr[] = NewCustomer::where('salesman_id',$salesman_id)->whereBetween('created_at',[$date.' 00:00:00',$date.' 23:59:59'])->count();
            }else{
                $new_customer_arr[] = 0;
            }
        }
        return $this->response
            ->success()
            ->data(compact('date_arr','new_customer_arr'))
            ->json();
    }
}