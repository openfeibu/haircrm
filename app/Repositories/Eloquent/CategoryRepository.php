<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Eloquent\CategoryRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Cache;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function model()
    {
        return config('model.category.category.model');
    }
    public function getAllCategoriesCache()
    {
        if (Cache::has('all_categories')) {
            return Cache::get('all_categories');
        }
        $data = $this->getAllCategories();
        Cache::forever('all_categories', $data);
        return $data;
    }
    public function getAllCategories()
    {
        $categories = $this->orderBy('order','asc')->orderBy('id','asc')->get()->toArray();
        return $categories;
    }
    public function getCategoriesCache($parent_id=0)
    {
        if (Cache::has('categories')) {
            return Cache::get('categories');
        }
        $data = $this->getCategories($parent_id);
        Cache::forever('categories', $data);
        return $data;
    }
    public function getCategories($parent_id=0)
    {
        $data = [];
        $categories = $this->where('parent_id',$parent_id)->orderBy('order','asc')->orderBy('id','asc')->get();
        foreach ($categories as $key => $category)
        {
            $data[$key] = [
                'title' => $category->name,
                'id' => $category->id,
                'parent_id' => $category->parent_id,
                'order' => $category->order,
                'attribute_id' => $category->attribute_id,
                'spread' => false
            ];
            $data[$key]['children'] = $this->getCategories($category->id);
        }
        return $data;
    }
    public function getCategoriesSelectTreeCache($parent_id=0)
    {
        if (Cache::has('categories_select_tree')) {
            return Cache::get('categories_select_tree');
        }
        $data = $this->getCategoriesSelectTree($parent_id);
        Cache::forever('categories_select_tree', $data);
        return $data;
    }
    public function getCategoriesSelectTree($parent_id=0)
    {

        $data = [];
        $categories = $this->where('parent_id',$parent_id)->orderBy('order','asc')->orderBy('id','asc')->get();
        foreach ($categories as $key => $category)
        {
            $data[$key] = [
                'title' => $category->name,
                'label' => $category->name,
                'id' => $category->id,
                'parent_id' => $category->parent_id,
                'order' => $category->order,
                'attribute_id' => $category->attribute_id,
                'spread' => false
            ];
            $data[$key]['children'] = $this->getCategoriesSelectTree($category->id);
        }
        return $data;
    }

    public function forgetCategoriesSelectTree()
    {
        Cache::forget('categories');
        Cache::forget('all_categories');
        Cache::forget('categories_select_tree');
    }
    public function getTopParentId($parent_id=0)
    {
        if($parent_id == 0)
        {
            return 0;
        }
        $parent = $this->where('id',$parent_id)->first(['id','parent_id']);
        if($parent->parent_id)
        {
            return $this->getTopParentId($parent->parent_id);
        }
        return $parent->id;
    }
    public function getCategoryIds($id,$ids=[])
    {
        if(!$id)
        {
            return '';
        }
        $category = $this->where('id',$id)->first(['id','parent_id']);
        $ids[] = $category->id;
        if($category->parent_id)
        {
            return $this->getCategoryIds($category->parent_id,$ids);
        }
        return $ids;

    }
    public function getFieldValue($category_id,$field)
    {
        $category = $this->where('id',$category_id)->first(['id','parent_id',$field]);
        if(!$category[$field])
        {
            if(!$category->parent_id)
            {
                return 0;
            }
            return $this->getFieldValue($category->parent_id,$field);
        }
        return $category[$field];
    }
    public function getWeight($category_id)
    {
        $category = $this->where('id',$category_id)->first(['id','parent_id','weight']);

        if(!$category->weight || $category->weight <= 0)
        {
            if(!$category->parent_id)
            {
                return 0;
            }
            return $this->getWeight($category->parent_id);
        }
        return $category->weight;
    }
    public function getFreightCategoryId($category_id)
    {
        $category = $this->where('id',$category_id)->first(['id','parent_id','freight_category_id']);

        if(!$category->freight_category_id)
        {
            if(!$category->parent_id)
            {
                return 0;
            }
            return $this->getFreightCategoryId($category->parent_id);
        }
        return $category->freight_category_id;

    }
    public function getSupplierId($category_id)
    {
        $category = $this->where('id',$category_id)->first(['id','parent_id','supplier_id']);

        if(!$category->supplier_id)
        {
            if(!$category->parent_id)
            {
                return 0;
            }
            return $this->getSupplierId($category->parent_id);
        }
        return $category->supplier_id;

    }
    public function getAttributeId($category_id)
    {
        $category = $this->where('id',$category_id)->first(['id','parent_id','attribute_id']);

        if(!$category->attribute_id)
        {
            if(!$category->parent_id)
            {
                return 0;
            }
            return $this->getAttributeId($category->parent_id);
        }
        return $category->attribute_id;

    }
    public function getAttributeContent(Request $request)
    {
        $attribute_id = $request->id;
        $attribute = $this->repository->find($attribute_id);
        $attribute_values = $this->attributeValueRepository->getAttributeValues($attribute_id);

        $content = $this->title(trans('attribute.name'))
            ->view('attribute.content')
            ->data(compact('attribute_values','attribute_id'))
            ->render();
        var_dump($content);exit;
    }
    public function getSubIds($category_id=0,$sub_ids=[]){
        $ids = Category::where('parent_id',$category_id)->pluck('id')->toArray();
        $sub_ids = array_merge($sub_ids,$ids);
        foreach ($ids as $key=> $id)
        {
            $sub_ids = $this->getSubIds($id,$sub_ids);
        }
        return $sub_ids;
    }

}