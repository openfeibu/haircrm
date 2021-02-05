<?php

namespace App\Http\Controllers\Salesman;

use App\Models\Customer;
use App\Models\NewCustomer;
use App\Models\Order;
use Route,Auth;
use App\Http\Controllers\Salesman\Controller as BaseController;
use App\Traits\AdminUser\AdminUserPages;
use App\Http\Response\ResourceResponse;
use App\Traits\Theme\ThemeAndViews;
use App\Traits\AdminUser\RoutesAndGuards;
use Illuminate\Http\Request;

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
        $begin_month = date('Y-m-01 00:00:00');
        $end_month = date('Y-m-d 23:59:59', strtotime("$begin_month +1 month -1 day"));

        $begin_year = date('Y-01-01 00:00:00');
        $end_year = date('Y-12-31 23:59:59');

        $customer_count = Customer::where('salesman_id',Auth::user()->id)->count();
        $new_customer_count = NewCustomer::where('salesman_id',Auth::user()->id)->count();
        $order_count = Order::where('salesman_id',Auth::user()->id)->where('pay_status','paid')->count();
        $today_order_count = Order::where('salesman_id',Auth::user()->id)->where('pay_status','paid')->where('paid_at','>=',date('Y-m-d 00:00:00'))->count();
        $paid_total = Order::where('salesman_id',Auth::user()->id)->sum('paid_total');

        $total_month_performance = $total_year_performance = 0;

        $monthly_performance_target = Auth::user()->monthly_performance_target;
        $yearly_performance_target = Auth::user()->yearly_performance_target;
        //月总业绩
        $total_month_performance = Order::where('salesman_id',Auth::user()->id)->whereBetween('paid_at',[$begin_month,$end_month])->sum('paid_total');
        $total_month_performance_percent = $monthly_performance_target ? round(($total_month_performance/$monthly_performance_target)*100).'%' : '/';
        //年总业绩
        $total_year_performance = Order::where('salesman_id',Auth::user()->id)->whereBetween('paid_at',[$begin_year,$end_year])->sum('paid_total');
        $total_year_performance_percent = $yearly_performance_target ? round(($total_year_performance/$yearly_performance_target)*100).'%' : '/';

        return $this->response->title(trans('app.admin.panel'))
            ->view('home')
            ->data(compact('customer_count','order_count','today_order_count','paid_total','new_customer_count','monthly_performance_target','total_month_performance','total_month_performance_percent','yearly_performance_target','total_year_performance','total_year_performance_percent'))
            ->output();
    }

    public function getMonthNewCustomers(Request $request)
    {
        $date_arr = get_month_days();
        $new_customer_arr = [];
        foreach ($date_arr as $key => $date)
        {
            if($date<= date('Y-m-d'))
            {
                $new_customer_arr[] = NewCustomer::where('salesman_id',Auth::user()->id)->whereBetween('created_at',[$date.' 00:00:00',$date.' 23:59:59'])->count();
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
