<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\Goods;
use App\Models\NewCustomer;
use App\Models\Order;
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

        $goods_count = Goods::count();

        return $this->response->title(trans('app.admin.panel'))
            ->view('home')
            ->data(compact('customer_count','new_customer_count','order_count','today_order_count','yesterday_order_count','today_purchase_price','yesterday_purchase_price','purchase_price','yesterday_selling_price','today_selling_price','selling_price','goods_count'))
            ->output();
    }
    public function dashboard()
    {
        return $this->response->title('测试')
            ->view('dashboard')
            ->output();
    }
}
