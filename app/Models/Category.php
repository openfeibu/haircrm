<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class Category extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.category.category';

    public $timestamps = false;

    protected $appends = ['parent_names'];

    public function getParentNamesAttribute()
    {
        $category_ids = $this->attributes['category_ids'];
        if(!$category_ids)
        {
            return "最顶级";
        }
        $category_id_arr = explode(',',$category_ids);
        $parent_name_arr = [];
        foreach ($category_id_arr as $key => $category_id)
        {
            $name = self::where('id',$category_id)->value('name');
            array_unshift($parent_name_arr,$name);
        }
        return implode(' - ',$parent_name_arr);
    }
}