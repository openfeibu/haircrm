<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\GoodsAttributeValue;
use App\Repositories\Eloquent\AttributeRepository;
use App\Repositories\Eloquent\AttributeValueRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\GoodsAttributeValueRepository;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\GoodsRepository;
use Illuminate\Support\Facades\Cache;

class GoodsAttributeValueResourceController extends BaseController
{
    public function __construct(
        GoodsRepository $goodsRepository,
        GoodsAttributeValueRepository $repository,
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
        $this->goodsRepository = $goodsRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {

    }
    public function create(Request $request)
    {

    }
    public function store(Request $request)
    {

    }
    public function show(Request $request,GoodsAttributeValue $goods_attribute_value)
    {

    }
    public function update(Request $request,GoodsAttributeValue $goods_attribute_value)
    {
        try {
            $attributes = $request->all();

            $goods_attribute_value->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('goods.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('goods'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('goods'))
                ->redirect();
        }
    }
    public function destroy(Request $request,GoodsAttributeValue $goods_attribute_value)
    {
        try {
            $this->repository->forceDelete([$goods_attribute_value->id]);

            $is_exist_goods_attribute_value = $this->repository->where('goods_id',$goods_attribute_value->goods_id)->first(['id']);
            if(!$is_exist_goods_attribute_value)
            {
                $this->goodsRepository->forceDelete([$goods_attribute_value->goods_id]);
            }
            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('goods.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('goods'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('goods'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];

            foreach ($ids as $id)
            {
                $goods_attribute_value = $this->repository->find($id,['goods_id']);
                $this->repository->forceDelete([$id]);
                $is_exist_goods_attribute_value = $this->repository->where('goods_id',$goods_attribute_value->goods_id)->first(['id']);
                if(!$is_exist_goods_attribute_value)
                {
                    $this->goodsRepository->forceDelete([$goods_attribute_value->goods_id]);
                }
            }


            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('goods.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('goods'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('goods'))
                ->redirect();
        }
    }
}
