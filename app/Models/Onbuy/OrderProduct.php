<?php

namespace App\Models\Onbuy;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class OrderProduct extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.onbuy.order_product';

    public $timestamps = false;

    public function getImageUrlsAttribute($value)
    {
        return json_decode($value, true);
    }
    public function getBillingAddressAttribute($value)
    {
        return json_decode($value, true);
    }
    public function getDeliveryAddressAttribute($value)
    {
        return json_decode($value, true);
    }
}