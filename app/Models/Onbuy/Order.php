<?php

namespace App\Models\Onbuy;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class Order extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.onbuy.order';

    public $timestamps = false;

    public $appends = ['ch_date'];

    public function getDeliveryAddressAttribute($value)
    {
        return json_decode($value, true);
    }
    public function getChDateAttribute()
    {
        return isset($this->attributes['date']) ?    date('Y-m-d H:i:s',strtotime("+7hour",strtotime($this->attributes['date']))) : '';
    }
}