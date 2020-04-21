<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\GoodsRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Models\Goods;

class GoodsRepository extends BaseRepository implements GoodsRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.goods.goods.search');
    }
    public function model()
    {
        return config('model.goods.goods.model');
    }
    public function getGoodsList($id,$columns=[])
    {
        $columns = $columns ? $columns : ['goods_attribute_value.purchase_price','goods_attribute_value.selling_price','goods_attribute_value.id as goods_attribute_value_id','goods.name as goods_name','goods.id','goods.id as goods_id','goods.category_id','goods.attribute_id','goods.category_ids','goods.purchase_price as goods_purchase_price','goods.selling_price as goods_selling_price','attribute_values.value as attribute_value'];


        $goods_list = Goods::leftJoin('goods_attribute_value','goods.id','=','goods_attribute_value.goods_id')
            ->leftJoin('attribute_values','attribute_values.id','=','goods_attribute_value.attribute_value_id')
            ->where('goods.id',$id)
            ->orderBy('attribute_values.order','asc')
            ->orderBy('attribute_values.id','asc')
            ->get($columns);

        foreach ($goods_list as $Key => $goods_value)
        {
            if(!$goods_value->attribute_id)
            {
                $goods_value->purchase_price = $goods_value->goods_purchase_price;
                $goods_value->selling_price = $goods_value->goods_selling_price;
            }
            $goods_value->freight_category_id = app(CategoryRepository::class)->getFieldValue($goods_value->category_id,'freight_category_id');
            $goods_value->weight = app(CategoryRepository::class)->getWeight($goods_value->category_id);
            //$goods->goods_name = $goods->goods_name.' '.$goods->attribute_value;
            $goods_value->list_id = $goods_value->goods_id .'-'. $goods_value->goods_attribute_value_id;
        }
        return $goods_list;
    }
}