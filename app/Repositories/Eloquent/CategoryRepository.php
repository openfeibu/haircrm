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
                'spread' => true
            ];
            $data[$key]['children'] = $this->getCategories($category->id);
        }
        return $data;
    }
    public function getCategoriesSelectTree($parent_id=0)
    {
        if (Cache::has('categories_select_tree')) {
            return Cache::get('categories_select_tree');
        }
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
                'spread' => false
            ];
            $data[$key]['children'] = $this->getCategoriesSelectTree($category->id);
        }
        Cache::forever('categories_select_tree', $data);
        return $data;
    }
    public function forgetCategoriesSelectTree()
    {
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
}