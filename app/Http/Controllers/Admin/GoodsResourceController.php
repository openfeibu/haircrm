<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
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
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $goods_list = GoodsAttributeValue::rightJoin('goods','goods.id','=','goods_attribute_value.goods_id')
                ->join('attribute_values','attribute_values.id','=','goods_attribute_value.attribute_value_id')
                ->orderBy('goods.id','desc')
                ->orderBy('attribute_values.order','asc')
                ->orderBy('attribute_values.id','asc')
                ->paginate($limit,['goods_attribute_value.*','goods.name as goods_name','goods.category_id','goods.category_ids','attribute_values.value as attribute_value']);

            foreach ($goods_list as $Key => $goods)
            {
                $goods->goods_name = $goods->goods_name.' '.$goods->attribute_value;
            }
            return $this->response
                ->success()
                ->count($goods_list->total())
                ->data($goods_list->toArray()['data'])
                ->output();
        }
        $categories = $this->categoryRepository->getCategoriesSelectTree();

        $categories = json_encode($categories);

        return $this->response->title(trans('goods.name'))
            ->view('goods.index')
            ->data(compact('categories'))
            ->output();
    }
    public function create(Request $request)
    {
        $goods = $this->repository->newInstance([]);

        $attribute = $this->attributeRepository->where('id',1)->first();
        $attribute_values = $this->attributeValueRepository->getAttributeValues($attribute->id);

        $categories = $this->categoryRepository->getCategories();

        $categories = json_encode($categories);

        return $this->response->title(trans('goods.name'))
            ->view('goods.create')
            ->data(compact('goods','attribute','attribute_values','categories'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $category_id = $attributes['category_id'];
            $category = $this->categoryRepository->find($category_id);
            $category_ids = $category->category_ids ? $category_id .','.$category->category_ids : $category_id;
            $category_id_arr = explode(',',$category_ids);
            /*
            $category_id_arr = $attributes['category_id'];
            $category_id_arr = array_reverse($category_id_arr);
            $category_id = $category_id_arr[0];
            $category_ids = implode(',',$category_id_arr);
            */

            $i = 0;
            $goods_attribute_values = [];
            foreach ($attributes['attribute_value'] as $key => $status)
            {
                $attribute_value_id = $key;
                $status = translate_on_off($status);
                $purchase_price = $attributes['purchase_price'][$attribute_value_id];
                $selling_price = $attributes['selling_price'][$attribute_value_id];
                if($status && $purchase_price && $selling_price){
                    $goods_attribute_values[] = [
                        'attribute_value_id' => $attribute_value_id,
                        'purchase_price' => $purchase_price,
                        'selling_price' => $selling_price
                    ];
                }
                $i++;
            }
            if(!$goods_attribute_values)
            {
                return $this->response->message("至少选择一个尺寸，且进货价、出售价不能为空")
                    ->code(400)
                    ->status('error')
                    ->url(guard_url('goods/create'))
                    ->redirect();
            }
            $categories_name_arr = $this->categoryRepository->whereIn('id',$category_id_arr)->orderBy('id','asc')->pluck('name')->toArray();
            $categories_names = implode(" ",$categories_name_arr);

            $goods = $this->repository->create([
                'category_id' => $category_id,
                'category_ids' => $category_ids,
                'name' => $categories_names,
            ]);
            foreach ($goods_attribute_values as $key => $goods_attribute_value)
            {
                $goods_attribute_values[$key]['goods_id'] = $goods->id;
            }
            GoodsAttributeValue::insert($goods_attribute_values);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('goods.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('goods'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('goods/create'))
                ->redirect();
        }
    }
    public function show(Request $request,Goods $good)
    {
        if ($good->exists) {
            $view = 'goods.show';
        } else {
            $view = 'goods.create';
        }
        $goods = $good;
        $goods_attribute_values_obj = $this->goodsAttributeValueRepository->where('goods_id',$goods->id)->get();
        $goods_attribute_values = [];

        foreach ($goods_attribute_values_obj as $key => $goods_attribute_value)
        {
            $goods_attribute_values[$goods_attribute_value->attribute_value_id] = $goods_attribute_value->toArray();
        }

        $attribute = $this->attributeRepository->where('id',1)->first();
        $attribute_values = $this->attributeValueRepository->getAttributeValues($attribute->id);

        return $this->response->title(trans('app.view') . ' ' . trans('goods.name'))
            ->data(compact('goods','goods_attribute_values','attribute_values'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Goods $good)
    {
        $goods = $good;
        try {

            $attributes = $request->all();

            $i = 0;
            $attribute_value_id_arr = $goods_attribute_values = [];
            foreach ($attributes['attribute_value'] as $key => $status)
            {
                $attribute_value_id = $key;
                $status = translate_on_off($status);
                $purchase_price = $attributes['purchase_price'][$attribute_value_id];
                $selling_price = $attributes['selling_price'][$attribute_value_id];
                if($status && $purchase_price){
                    $attribute_value_id_arr[] = $attribute_value_id;
                    $goods_attribute_values[] = [
                        'attribute_value_id' => $attribute_value_id,
                        'purchase_price' => $purchase_price,
                        'selling_price' => $selling_price
                    ];
                }
                $i++;
                //var_dump($status);
            }

            GoodsAttributeValue::where('goods_id',$goods->id)->whereNotIn('attribute_value_id',$attribute_value_id_arr)->delete();

            foreach ($goods_attribute_values as $key => $goods_attribute_value)
            {
                if(in_array($goods_attribute_value['attribute_value_id'],$goods->attr_value_id_arr))
                {
                    GoodsAttributeValue::where('goods_id',$goods->id)->where('attribute_value_id',$goods_attribute_value['attribute_value_id'])->update([ 'purchase_price' => $goods_attribute_value['purchase_price'],'selling_price' => $goods_attribute_value['selling_price'] ]);
                }else{
                    $this->goodsAttributeValueRepository->create([
                        'goods_id' => $goods->id,
                        'attribute_value_id' => $goods_attribute_value['attribute_value_id'],
                        'purchase_price' => $goods_attribute_value['purchase_price'],
                        'selling_price' => $goods_attribute_value['selling_price'],
                    ]);
                }
            }

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('goods.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('goods'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('goods/' . $goods->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,Goods $good)
    {
        try {
            $this->repository->forceDelete([$good->id]);

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

            $this->repository->forceDelete($ids);

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

        $goods_attribute_values_obj = $this->goodsAttributeValueRepository->join('attribute_values','attribute_values.id','goods_attribute_value.attribute_value_id')->where('goods_id',$goods->id)->get(['goods_attribute_value.*','attribute_values.value as attribute_value']);
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
