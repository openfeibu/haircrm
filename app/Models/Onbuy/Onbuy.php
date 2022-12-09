<?php

namespace App\Models\Onbuy;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class Onbuy extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.onbuy.onbuy';

    public static function getAll()
    {
        return self::orderBy('default','desc')
        ->orderBy('order','asc')
        ->orderBy('id','asc')
        ->get();
    }
}