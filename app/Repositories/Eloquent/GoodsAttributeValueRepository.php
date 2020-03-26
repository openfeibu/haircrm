<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\GoodsAttributeValueRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class GoodsAttributeValueRepository extends BaseRepository implements GoodsAttributeValueRepositoryInterface
{
    public function model()
    {
        return config('model.goods.goods_attribute_value.model');
    }

}