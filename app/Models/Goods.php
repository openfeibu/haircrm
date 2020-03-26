<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class Goods extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.goods.goods';

    public $appends = ['attr_value_id_arr'];

    public function getAttrValueIdArrAttribute()
    {
        return GoodsAttributeValue::where('goods_id',$this->attributes['id'])->pluck('attribute_value_id')->toArray();
    }

}