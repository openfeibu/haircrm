<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity,SoftDeletes;

    protected $config = 'model.customer.customer';
    protected $appends = ['order_count'];

    public function getOrderCountAttribute()
    {
        return isset($this->attributes['id']) && $this->attributes['id'] ? Order::where('customer_id',$this->attributes['id'])->where('pay_status','paid')->count() : '0';
    }
}