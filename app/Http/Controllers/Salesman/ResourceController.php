<?php

namespace App\Http\Controllers\Salesman;

use App\Models\Customer;
use App\Models\Order;
use Route,Auth;
use App\Http\Controllers\Salesman\Controller as BaseController;
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
        $customer_count = Customer::where('salesman_id',Auth::user()->id)->count();
        $order_count = Order::where('salesman_id',Auth::user()->id)->count();
        $today_order_count = Order::where('salesman_id',Auth::user()->id)->where('created_at','>=',date('Y-m-d 00:00:00'))->count();
        $purchase_price = Order::where('salesman_id',Auth::user()->id)->sum('purchase_price');
        $selling_price = Order::where('salesman_id',Auth::user()->id)->sum('selling_price');

        return $this->response->title(trans('app.admin.panel'))
            ->view('home')
            ->data(compact('customer_count','order_count','today_order_count','purchase_price','selling_price'))
            ->output();
    }
    public function dashboard()
    {
        return $this->response->title('测试')
            ->view('dashboard')
            ->output();
    }
}
