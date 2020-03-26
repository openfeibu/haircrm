<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\GoodsRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class GoodsRepository extends BaseRepository implements GoodsRepositoryInterface
{
    public function model()
    {
        return config('model.goods.goods.model');
    }

}