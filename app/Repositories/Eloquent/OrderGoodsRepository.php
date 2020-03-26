<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\OrderGoodsRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class OrderGoodsRepository extends BaseRepository implements OrderGoodsRepositoryInterface
{
    public function model()
    {
        return config('model.order.order_goods.model');
    }
    public function getOrderGoodsList($order_id)
    {
        return $this->where('order_id',$order_id)->orderBy('id','asc')->get();
    }
}