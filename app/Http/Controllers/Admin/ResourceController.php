<?php

namespace App\Http\Controllers\Admin;

use App\Models\Salesman;
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
        //今日报价订单量
        $today_order_count = Order::where('created_at','>=',date('Y-m-d 00:00:00'))->count();
        //昨日报价订单量
        $yesterday_order_count = Order::whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('-1day')),date('Y-m-d 23:59:59',strtotime('-1day'))])->count();
        //总订单量
        $order_count = Order::count();

        //今日成交订单量
        $today_paid_order_count = Order::where('paid_at','>=',date('Y-m-d 00:00:00'))->where('pay_status','paid')->count();
        //昨日成交订单量
        $yesterday_paid_order_count = Order::whereBetween('paid_at',[date('Y-m-d 00:00:00',strtotime('-1day')),date('Y-m-d 23:59:59',strtotime('-1day'))])->where('pay_status','paid')->count();
        //总成交订单量
        $order_paid_count = Order::where('pay_status','paid')->count();

        //今日销售额
        $today_purchase_price = Order::where('paid_at','>=',date('Y-m-d 00:00:00'))->where('pay_status','paid')->sum('purchase_price');
        //昨日拿货价
        $yesterday_purchase_price = Order::whereBetween('paid_at',[date('Y-m-d 00:00:00',strtotime('-1day')),date('Y-m-d 23:59:59',strtotime('-1day'))])->where('pay_status','paid')->sum('purchase_price');
        //总拿货价
        $purchase_price = Order::where('pay_status','paid')->sum('purchase_price');

        //今日销售额
        $today_selling_price = Order::where('paid_at','>=',date('Y-m-d 00:00:00'))->where('pay_status','paid')->sum('selling_price');
        //昨日销售额
        $yesterday_selling_price= Order::whereBetween('paid_at',[date('Y-m-d 00:00:00',strtotime('-1day')),date('Y-m-d 23:59:59',strtotime('-1day'))])->where('pay_status','paid')->sum('selling_price');
        //总销售额
        $selling_price = Order::where('pay_status','paid')->sum('selling_price');

        //营销邮件数量
        $mail_sent_count = MailScheduleReport::where('sent',1)->where('status','success')->count();
        $goods_count = Goods::count();

        $begin_month = date('Y-m-01 00:00:00');
        $end_month = date('Y-m-d 23:59:59', strtotime("$begin_month +1 month -1 day"));

        $begin_year = date('Y-01-01 00:00:00');
        $end_year = date('Y-12-31 23:59:59');

        $unshipped_count =  Order::where('shipping_status','unshipped')->where('pay_status','paid')->count();

        $salesmen = Salesman::where('active','1')->where('monthly_performance_target','>','0')->orderBy('order','asc')->orderBy('id','desc')->get()->toArray();

        $total_month_performance = $total_year_performance = 0;

        //月总业绩目标
        $total_monthly_performance_target = setting('total_monthly_performance_target');
        //年总业绩目标
        $total_yearly_performance_target = setting('total_yearly_performance_target');

        foreach ($salesmen as $key => $salesman)
        {
            $salesmen[$key]['month_performance'] = Order::whereBetween('paid_at',[$begin_month,$end_month])->where('salesman_id',$salesman['id'])->sum('paid_total');
            $salesmen[$key]['month_performance_percent'] = round(($salesmen[$key]['month_performance']/$salesman['monthly_performance_target'])*100).'%';
        }
        //月总业绩
        $total_month_performance = Order::whereBetween('paid_at',[$begin_month,$end_month])->sum('paid_total');
        $total_month_performance_percent = $total_monthly_performance_target ? round(($total_month_performance/$total_monthly_performance_target)*100).'%' : '/';

        //年总业绩
        $total_year_performance = Order::whereBetween('paid_at',[$begin_year,$end_year])->sum('paid_total');
        $total_year_performance_percent = $total_yearly_performance_target ? round(($total_year_performance/$total_yearly_performance_target)*100).'%' : '/';

        return $this->response->title(trans('app.admin.panel'))
            ->view('home')
            ->data(compact('customer_count','new_customer_count','order_count','today_order_count','yesterday_order_count','today_paid_order_count','yesterday_paid_order_count','order_paid_count','today_purchase_price','yesterday_purchase_price','purchase_price','yesterday_selling_price','today_selling_price','selling_price','goods_count','mail_sent_count','unshipped_count','salesmen','total_monthly_performance_target','total_month_performance','total_month_performance_percent','total_yearly_performance_target','total_year_performance','total_year_performance_percent'))
            ->output();
    }
    public function dashboard()
    {
        return $this->response->title('测试')
            ->view('dashboard')
            ->output();
    }


}
