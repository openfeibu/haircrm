<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/categories', 'CategoryController@getCategories')->name('category.index');
Route::get('/categories_tree', 'CategoryController@getCategoriesTree')->name('category.tree');
Route::get('/attribute_content', 'AttributeController@getContent')->name('attribute.content');
Route::get('/test', 'HomeController@test');
Route::get('/add_goods', 'HomeController@addGoods');
Route::get('/add_goods_attribute_value', 'HomeController@addGoodsAttributeValue');
Route::get('/tracking_number/{tracking_number}', 'HomeController@trackingNumber');
Route::get('/payment_sn/{payment_sn}', 'HomeController@paymentSn');

Route::get('/update', 'HomeController@update');
Route::get('/check_new_customer', 'HomeController@checkNewCustomer');
// Admin  routes  for user
Route::group([
    'namespace' => 'Admin',
    'prefix' => 'admin'
], function () {
    Auth::routes();
    Route::get('password', 'UserController@getPassword');
    Route::post('password', 'UserController@postPassword');

    Route::get('update_cache', 'UserController@updateCache');

    Route::get('/', 'ResourceController@home')->name('home');
    Route::get('/dashboard', 'ResourceController@dashboard')->name('dashboard');

    Route::resource('system_page', 'SystemPageResourceController');
    Route::post('/system_page/destroyAll', 'SystemPageResourceController@destroyAll')->name('system_page.destroy_all');
    Route::get('/setting/company', 'SettingResourceController@company')->name('setting.company.index');
    Route::post('/setting/updateCompany', 'SettingResourceController@updateCompany');
    Route::get('/setting/publicityVideo', 'SettingResourceController@publicityVideo')->name('setting.publicity_video.index');
    Route::post('/setting/updatePublicityVideo', 'SettingResourceController@updatePublicityVideo');
    Route::get('/setting/station', 'SettingResourceController@station')->name('setting.station.index');
    Route::post('/setting/updateStation', 'SettingResourceController@updateStation');
    Route::get('/setting/parameter', 'SettingResourceController@parameter')->name('setting.parameter.index');
    Route::post('/setting/updateParameter', 'SettingResourceController@updateParameter');

    Route::resource('permission', 'PermissionResourceController');
    Route::resource('role', 'RoleResourceController');


    Route::group(['prefix' => 'page','as' => 'page.'], function ($router) {
        Route::resource('page', 'PageResourceController');
        Route::resource('category', 'PageCategoryResourceController');
    });
    Route::group(['prefix' => 'menu'], function ($router) {
        Route::get('index', 'MenuResourceController@index');
    });


    Route::post('/media_folder/store', 'MediaResourceController@folderStore')->name('media_folder.store');
    Route::delete('/media_folder/destroy', 'MediaResourceController@folderDestroy')->name('media_folder.destroy');
    Route::put('/media_folder/update/{media_folder}', 'MediaResourceController@folderUpdate')->name('media_folder.update');
    Route::get('/media', 'MediaResourceController@index')->name('media.index');
    Route::put('/media/update/{media}', 'MediaResourceController@update')->name('media.update');
    Route::post('/media/upload', 'MediaResourceController@upload')->name('media.upload');
    Route::delete('/media/destroy', 'MediaResourceController@destroy')->name('media.destroy');

    Route::post('/upload/{config}/{path?}', 'UploadController@upload')->where('path', '(.*)');

    Route::resource('category', 'CategoryResourceController');
    Route::get('/attribute_content', 'CategoryResourceController@getAttributeContent')->name('category.attribute_content');
    Route::post('/category/increment_price', 'CategoryResourceController@incrementPrice')->name('category.increment_price');
    Route::post('/category/decrement_price', 'CategoryResourceController@decrementPrice')->name('category.decrement_price');

    Route::resource('customer', 'CustomerResourceController');
    Route::get('get_customer', 'CustomerResourceController@getCustomer')->name('customer.get_customer');
    Route::post('/customer/destroyAll', 'CustomerResourceController@destroyAll')->name('customer.destroy_all');
    Route::get('customer_import', 'CustomerResourceController@import')->name('customer.import');
    Route::post('/customer_import/submit', 'CustomerResourceController@submitImport')->name('customer.submit_import');
    Route::get('/customer_download', 'CustomerResourceController@download')->name('customer.download');

    Route::resource('new_customer', 'NewCustomerResourceController');
    Route::post('/new_customer/destroyAll', 'NewCustomerResourceController@destroyAll')->name('new_customer.destroy_all');
    Route::get('new_customer_import', 'NewCustomerResourceController@import')->name('new_customer.import');
    Route::post('/new_customer_import/submit', 'NewCustomerResourceController@submitImport')->name('new_customer.submit_import');
    Route::get('/new_customer_download', 'NewCustomerResourceController@download')->name('new_customer.download');

     Route::get('/new_customer_download_email_excel', 'NewCustomerResourceController@downloadEmailExcel')->name('new_customer.download_email_excel');
    Route::get('new_customer/mail/count', 'NewCustomerResourceController@mailCount')->name('new_customer.mail.count');

    Route::resource('goods', 'GoodsResourceController');
    Route::post('/goods/update_attribute','GoodsResourceController@updateAttribute')->name('goods.update_attribute');
    Route::post('/goods/destroy_list', 'GoodsResourceController@destroyList')->name('goods.destroy_list');
    Route::resource('goods_attribute_value', 'GoodsAttributeValueResourceController');
    Route::post('/goods_attribute_value/destroyAll', 'GoodsAttributeValueResourceController@destroyAll')->name('goods_attribute_value.destroy_all');
    Route::get('/category_goods','GoodsResourceController@categoryGoods')->name('goods.category_goods');

    Route::resource('salesman', 'SalesmanResourceController');
    Route::post('/salesman/destroyAll', 'SalesmanResourceController@destroyAll')->name('salesman.destroy_all');

    Route::resource('order', 'OrderResourceController');
    Route::post('/order/destroyAll', 'OrderResourceController@destroyAll')->name('order.destroy_all');

    Route::post('/order/pay', 'OrderResourceController@pay')->name('order.pay');
    Route::post('/order/to_delivery', 'OrderResourceController@toDelivery')->name('order.to_delivery');
    Route::post('/order/cancel', 'OrderResourceController@cancel')->name('order.cancel');
    Route::post('/order/receive', 'OrderResourceController@receive')->name('order.receive');
    Route::post('/order/return', 'OrderResourceController@returnOrder')->name('order.return');

    Route::get('/order_download/purchase_order', 'OrderResourceController@downloadPurchaseOrder')->name('order.download_purchase_order');
    Route::get('/order_download/quotation_list', 'OrderResourceController@downloadQuotationList')->name('order.download_quotation_list');

    Route::resource('order_goods', 'OrderGoodsResourceController');
    Route::post('/order_goods/destroyAll', 'OrderGoodsResourceController@destroyAll')->name('order_goods.destroy_all');

    Route::resource('mail_account', 'MailAccountResourceController');
    Route::post('/mail_account/destroyAll', 'MailAccountResourceController@destroyAll')->name('mail_account.destroy_all');
    Route::resource('mail_template', 'MailTemplateResourceController');
    Route::post('/mail_template/destroyAll', 'MailTemplateResourceController@destroyAll')->name('mail_template.destroy_all');
    Route::resource('mail_schedule', 'MailScheduleResourceController');
    Route::post('/mail_schedule/destroyAll', 'MailScheduleResourceController@destroyAll')->name('mail_schedule.destroy_all');
    Route::post('mail_schedule/send/new_customer', 'MailScheduleResourceController@sendNewCustomer')->name('mail.send.new_customer');
    Route::resource('mail_schedule_report', 'MailScheduleReportResourceController');
    Route::post('/mail_schedule_report/destroyAll', 'MailScheduleReportResourceController@destroyAll')->name('mail_schedule_report.destroy_all');

    Route::resource('admin_user', 'AdminUserResourceController');
    Route::post('/admin_user/destroyAll', 'AdminUserResourceController@destroyAll')->name('admin_user.destroy_all');
    Route::resource('permission', 'PermissionResourceController');
    Route::post('/permission/destroyAll', 'PermissionResourceController@destroyAll')->name('permission.destroy_all');
    Route::resource('role', 'RoleResourceController');
    Route::post('/role/destroyAll', 'RoleResourceController@destroyAll')->name('role.destroy_all');
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('locked', 'UserController@locked');

    Route::get('statistic/trade', 'StatisticResourceController@trade')->name('statistic.trade');
    Route::get('statistic/trading', 'StatisticResourceController@getTrading')->name('statistic.trading');
    Route::get('statistic/month_new_customers', 'StatisticResourceController@monthNewCustomers')->name('statistic.month_new_customers');
    Route::get('statistic/get_month_new_customers', 'StatisticResourceController@getMonthNewCustomers')->name('statistic.get_month_new_customers');
});
Route::group([
    'namespace' => 'Salesman',
    'prefix' => 'salesman',
    'as' => 'salesman.',
], function () {
    Auth::routes();
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('/', 'ResourceController@home')->name('home');
    Route::get('/home', 'ResourceController@home');
    Route::get('password', 'UserController@getPassword');
    Route::post('password', 'UserController@postPassword');

    Route::resource('permission', 'PermissionResourceController');
    Route::post('/permission/destroyAll', 'PermissionResourceController@destroyAll')->name('permission.destroy_all');
    Route::resource('role', 'RoleResourceController');
    Route::post('/role/destroyAll', 'RoleResourceController@destroyAll')->name('role.destroy_all');

    Route::post('/upload/{config}/{path?}', 'UploadController@upload')->where('path', '(.*)');
    Route::post('/file/{config}/{path?}', 'UploadController@uploadFile')->where('path', '(.*)');


    Route::resource('customer', 'CustomerResourceController');
    Route::get('get_customer', 'CustomerResourceController@getCustomer')->name('customer.get_customer');
    Route::post('/customer/destroyAll', 'CustomerResourceController@destroyAll')->name('customer.destroy_all');
    Route::get('customer_import', 'CustomerResourceController@import')->name('customer.import');
    Route::post('/customer_import/submit', 'CustomerResourceController@submitImport')->name('customer.submit_import');

    Route::resource('new_customer', 'NewCustomerResourceController');
    Route::post('/new_customer/destroyAll', 'NewCustomerResourceController@destroyAll')->name('new_customer.destroy_all');
    Route::get('new_customer_import', 'NewCustomerResourceController@import')->name('new_customer.import');
    Route::post('/new_customer_import/submit', 'NewCustomerResourceController@submitImport')->name('new_customer.submit_import');
    Route::get('new_customer/mail/count', 'NewCustomerResourceController@mailCount')->name('new_customer.mail.count');

    Route::resource('order', 'OrderResourceController');
    Route::post('/order/destroyAll', 'OrderResourceController@destroyAll')->name('order.destroy_all');

    Route::post('/order/pay', 'OrderResourceController@pay')->name('order.pay');
    Route::post('/order/to_delivery', 'OrderResourceController@toDelivery')->name('order.to_delivery');
    Route::post('/order/cancel', 'OrderResourceController@cancel')->name('order.cancel');
    Route::post('/order/receive', 'OrderResourceController@receive')->name('order.receive');
    Route::post('/order/return', 'OrderResourceController@returnOrder')->name('order.return');

    Route::get('/order_download/quotation_list', 'OrderResourceController@downloadQuotationList')->name('order.download_quotation_list');
    Route::get('/category_goods','GoodsResourceController@categoryGoods')->name('goods.category_goods');
    Route::get('locked', 'UserController@locked');

    Route::resource('order_goods', 'OrderGoodsResourceController');
    Route::post('/order_goods/destroyAll', 'OrderGoodsResourceController@destroyAll')->name('order_goods.destroy_all');

    Route::resource('mail_account', 'MailAccountResourceController');
    Route::post('/mail_account/destroyAll', 'MailAccountResourceController@destroyAll')->name('mail_account.destroy_all');
    Route::resource('mail_template', 'MailTemplateResourceController');
    Route::post('/mail_template/destroyAll', 'MailTemplateResourceController@destroyAll')->name('mail_template.destroy_all');
    Route::resource('mail_schedule', 'MailScheduleResourceController');
    Route::post('/mail_schedule/destroyAll', 'MailScheduleResourceController@destroyAll')->name('mail_schedule.destroy_all');
    Route::post('mail_schedule/send/new_customer', 'MailScheduleResourceController@sendNewCustomer')->name('mail.send.new_customer');
    Route::resource('mail_schedule_report', 'MailScheduleReportResourceController');
    Route::post('/mail_schedule_report/destroyAll', 'MailScheduleReportResourceController@destroyAll')->name('mail_schedule_report.destroy_all');

    Route::get('/getMonthNewCustomers', 'ResourceController@getMonthNewCustomers')->name('statistic.getMonthNewCustomers');

});
/*
Route::group([
    'namespace' => 'Pc',
    'as' => 'pc.',
], function () {
    Auth::routes();
    Route::get('/user/login','Auth\LoginController@showLoginForm');
    Route::get('/','HomeController@home')->name('home');


    Route::get('email-verification/index','Auth\EmailVerificationController@getVerificationIndex')->name('email-verification.index');
    Route::get('email-verification/error','Auth\EmailVerificationController@getVerificationError')->name('email-verification.error');
    Route::get('email-verification/check/{token}', 'Auth\EmailVerificationController@getVerification')->name('email-verification.check');
    Route::get('email-verification-required', 'Auth\EmailVerificationController@required')->name('email-verification.required');

    Route::get('verify/send', 'Auth\LoginController@sendVerification');
    Route::get('verify/{code?}', 'Auth\LoginController@verify');

});
*/
//Route::get('
///{slug}.html', 'PagePublicController@getPage');
/*
Route::group(
    [
        'prefix' => trans_setlocale() . '/admin/menu',
    ], function () {
    Route::post('menu/{id}/tree', 'MenuResourceController@tree');
    Route::get('menu/{id}/test', 'MenuResourceController@test');
    Route::get('menu/{id}/nested', 'MenuResourceController@nested');

    Route::resource('menu', 'MenuResourceController');
   // Route::resource('submenu', 'SubMenuResourceController');
});
*/