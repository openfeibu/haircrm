<?php

namespace App\Models;

use App\Repositories\Eloquent\OrderRepository;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity,SoftDeletes;

    protected $config = 'model.order.order';

    public $appends = ['order_status_desc','shipping_status_desc','pay_status_desc','operation'];

    public function getOrderStatusDescAttribute()
    {
        return isset($this->attributes['order_status']) ? trans('order.order_status.'.$this->attributes['order_status']) : '';
    }
    public function getShippingStatusDescAttribute()
    {
        return isset($this->attributes['shipping_status']) ? trans('order.shipping_status.'.$this->attributes['shipping_status']) : '';
    }
    public function getPayStatusDescAttribute()
    {
        return isset($this->attributes['pay_status']) ? trans('order.pay_status.'.$this->attributes['pay_status']) : '';
    }
    public function getOperationAttribute()
    {
        return isset($this->attributes['order_status']) ? app(OrderRepository::class)->operation($this->attributes) : [];
    }
}