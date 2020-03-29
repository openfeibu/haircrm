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

        $goods_list = $this->repository->getGoodsList($goods->id,['goods_attribute_value.purchase_price','goods_attribute_value.selling_price','goods_attribute_value.id as goods_attribute_value_id','goods.name as goods_name','goods.id','goods.id as goods_id','goods.category_id','goods.attribute_id','goods.category_ids','goods.selling_price as goods_selling_price','attribute_values.value as attribute_value']);

        return $this->response->message(trans('messages.operation.success'))
            ->data(compact('goods','goods_list'))
            ->status("success")
            ->url(guard_url('goods'))
            ->redirect();
    }

}
