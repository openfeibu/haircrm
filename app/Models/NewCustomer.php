<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class NewCustomer extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.customer.new_customer';

    public $appends = ['mark_desc'];

    public function getMarkDescAttribute()
    {
        return trans('new_customer.mark.'.$this->attributes['mark']);
    }

}