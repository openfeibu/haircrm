<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\AttributeValueRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AttributeValueRepository extends BaseRepository implements AttributeValueRepositoryInterface
{
    public function model()
    {
        return config('model.attribute.attribute_value.model');
    }
    public function getAttributeValues($attribute_id)
    {
        $attribute_values = $this->where('attribute_id',$attribute_id)->orderBy('order','asc')->orderBy('id','asc')->get();
        return $attribute_values;
    }
}