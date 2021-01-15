<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\MailScheduleReport;
use App\Models\NewCustomer;
use App\Models\Order;
use App\Models\OrderGoods;
use Route;
use App\Http\Controllers\Admin\Controller as BaseController;
use App\Traits\AdminUser\AdminUserPages;
use App\Http\Response\ResourceResponse;
use App\Traits\Theme\ThemeAndViews;
use App\Traits\AdminUser\RoutesAndGuards;

class ResourceController extends BaseController
{
    use AdminUserPages,ThemeAndViews,RoutesAndGuards;

    public function __construct()
    {
        parent::__construct();
        if (!empty(app('auth')->getDefaultDriver())) {
            $this->middleware('auth:' . app('auth')->getDefaultDriver());
           // $this->middleware('role:' . $this->getGuardRoute());
            $this->middleware('permission:' .Route::currentRouteName());
            $this->middleware('active');
        }
        $this->response = app(ResourceResponse::class);
        $this->setTheme();
    }
    /**
     * Show dashboard for each user.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {

        $customer_count = Customer::count();
        $new_customer_count = NewCustomer::count();
        //今日订单量
        $today_order_count = Order::where('created_at','>=',date('Y-m-d 00:00:00'))->count();
        //昨日订单量
        $yesterday_order_count = Order::whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('-1day')),date('Y-m-d 23:59:59',strtotime('-1day'))])->count();
        //总订单量
        $order_count = Order::count();

        //今日成交订单量
        $today_paid_order_count = Order::where('created_at','>=',date('Y-m-d 00:00:00'))->where('pay_status','paid')->count();
        //昨日成交订单量
        $yesterday_paid_order_count = Order::whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('-1day')),date('Y-m-d 23:59:59',strtotime('-1day'))])->where('pay_status','paid')->count();
        //总成交订单量
        $order_paid_count = Order::where('pay_status','paid')->count();

        //今日销售额
        $today_purchase_price = Order::where('created_at','>=',date('Y-m-d 00:00:00'))->where('pay_status','paid')->sum('purchase_price');
        //昨日拿货价
        $yesterday_purchase_price = Order::whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('-1day')),date('Y-m-d 23:59:59',strtotime('-1day'))])->where('pay_status','paid')->sum('purchase_price');
        //总拿货价
        $purchase_price = Order::where('pay_status','paid')->sum('purchase_price');

        //今日销售额
        $today_selling_price = Order::where('created_at','>=',date('Y-m-d 00:00:00'))->where('pay_status','paid')->sum('selling_price');
        //昨日销售额
        $yesterday_selling_price= Order::whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('-1day')),date('Y-m-d 23:59:59',strtotime('-1day'))])->where('pay_status','paid')->sum('selling_price');
        //总销售额
        $selling_price = Order::where('pay_status','paid')->sum('selling_price');

        //营销邮件数量
        $mail_sent_count = MailScheduleReport::where('sent',1)->where('status','success')->count();
        $goods_count = Goods::count();

        $hot_goods_list = OrderGoods::join('orders','orders.id','=','order_goods.order_id')->where('orders.pay_status','paid')->groupBy('order_goods.goods_id')->orderBy('count','desc')->selectRaw('count(*) as count,sum(order_goods.number) as sum,order_goods.goods_id,CONCAT(order_goods.goods_name," ",order_goods.attribute_value) as goods_name')->limit(10)->get()->toArray();

        $hot_goods_name_list = array_column($hot_goods_list,'goods_name');
        $hot_goods_names = implode("<br />",$hot_goods_name_list);

        $begin_month = date('Y-m-01 00:00:00');
        $end_month = date('Y-m-d 23:59:59', strtotime("$begin_month +1 month -1 day"));
        $this_month_hot_goods_list = OrderGoods::join('orders','orders.id','=','order_goods.order_id')->where('orders.pay_status','paid')->whereBetween('orders.created_at',[$begin_month,$end_month])->groupBy('order_goods.goods_id')->orderBy('count','desc')->selectRaw('count(*) as count,sum(order_goods.number) as sum, order_goods.goods_id,CONCAT(order_goods.goods_name," ",order_goods.attribute_value) as goods_name')->limit(10)->get()->toArray();
        $this_month_hot_goods_name_list = array_column($this_month_hot_goods_list,'goods_name');
        $this_month_hot_goods_names = implode("<br />",$this_month_hot_goods_name_list);

        return $this->response->title(trans('app.admin.panel'))
            ->view('home')
            ->data(compact('customer_count','new_customer_count','order_count','today_order_count','yesterday_order_count','today_paid_order_count','yesterday_paid_order_count','order_paid_count','today_purchase_price','yesterday_purchase_price','purchase_price','yesterday_selling_price','today_selling_price','selling_price','goods_count','mail_sent_count','hot_goods_names','this_month_hot_goods_names','hot_goods_list','this_month_hot_goods_list'))
            ->output();
    }
    public function dashboard()
    {
        return $this->response->title('测试')
            ->view('dashboard')
            ->output();
    }

    public function getTrading(Request $request)
    {
        try {
            $attributes = $request->all();
            $date_type = isset($attributes['date_type']) && $attributes['date_type'] ? $attributes['date_type'] : 'days';
            $series_data = [];
            $order_count_arr = $turnover_arr =  [];
            switch ($date_type)
            {
                case 'days':
                    $date_arr = get_weeks();
                    foreach ($date_arr as $key => $date)
                    {
                        $order_count_arr[$key] = Order::whereBetween('created_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->count();
                        $turnover_arr[$key] =  Order::whereBetween('created_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->sum('total');
                    }
                    break;
                case 'this_month':
                    $date_arr = get_month_days();
                    foreach ($date_arr as $key => $date)
                    {
                        if($date<= date('Y-m-d'))
                        {
                            $order_count_arr[] = Order::whereBetween('created_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->count();
                            $turnover_arr[] =  Order::whereBetween('created_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->sum('total');
                        }else{
                            $order_count_arr[] = 0;
                            $order_count_arr[] = 0;
                        }
                    }
                    break;
                case 'last_month':
                    $date_arr = get_month_days(date("m", strtotime("-1 month")));
                    foreach ($date_arr as $key => $date)
                    {
                        if($date<= date('Y-m-d'))
                        {
                            $order_count_arr[] = Order::whereBetween('created_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->count();
                            $turnover_arr[] =  Order::whereBetween('created_at',[$date.' 00:00:00',$date.' 23:59:59'])->where('pay_status','paid')->sum('total');
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
                            $order_count_arr[] = Order::whereBetween('created_at',[$first_day,$end_day])->where('pay_status','paid')->count();
                            $turnover_arr[] =  Order::whereBetween('created_at',[$first_day,$end_day])->where('pay_status','paid')->sum('total');
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
                            $order_count_arr[] = Order::whereBetween('created_at',[$first_day,$end_day])->where('pay_status','paid')->count();
                            $turnover_arr[] =  Order::whereBetween('created_at',[$first_day,$end_day])->where('pay_status','paid')->sum('total');
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
}
