<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Salesman\ResourceController as BaseController;
use App\Models\Goods;
use App\Models\GoodsAttributeValue;
use App\Repositories\Eloquent\AttributeRepository;
use App\Repositories\Eloquent\AttributeValueRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\GoodsAttributeValueRepository;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\GoodsRepository;

class GoodsResourceController extends BaseController
{
    public function __construct(
        GoodsRepository $repository,
        GoodsAttributeValueRepository $goodsAttributeValueRepository,
        CategoryRepository $categoryRepository,
        AttributeRepository $attributeRepository,
        AttributeValueRepository $attributeValueRepository
    )
    {
        parent::__construct();
        $this->repository = $repository;
        $this->attributeRepository = $attributeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->attributeValueRepository = $attributeValueRepository;
        $this->goodsAttributeValueRepository = $goodsAttributeValueRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function categoryGoods(Request $request)
    {
        $category_id = $request->get('category_id');

        $goods = $this->repository->where('category_id',$category_id)->first();
        if(!$goods)
        {
            return $this->response->message(trans('messages.operation.success'))
                ->data([])
                ->status("success")
                ->url(guard_url('goods'))
                ->redirect();
        }

        $goods_attribute_values_obj = $this->goodsAttributeValueRepository->join('attribute_values','attribute_values.id','goods_attribute_value.attribute_value_id')->where('goods_id',$goods->id)->get(['goods_attribute_value.id','goods_attribute_value.goods_id', 'goods_attribute_value.attribute_value_id','goods_attribute_value.selling_price','attribute_values.value as attribute_value']);
        $goods_attribute_values = [];

        foreach ($goods_attribute_values_obj as $key => $goods_attribute_value)
        {
            $goods_attribute_values[$goods_attribute_value->attribute_value_id] = $goods_attribute_value->toArray();
        }
        $goods->attribute_values = $goods_attribute_values;
        return $this->response->message(trans('messages.operation.success'))
            ->data($goods->toArray())
            ->status("success")
            ->url(guard_url('goods'))
            ->redirect();
    }

}
