<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\AttributeRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    public function model()
    {
        return config('model.attribute.attribute.model');
    }
    public function getAttributeValues($attribute_id)
    {
        app(AttributeValueRepository::class)->where('attribute');
    }
}