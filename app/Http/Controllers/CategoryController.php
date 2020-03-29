<?php

namespace App\Http\Controllers;


use App\Repositories\Eloquent\CategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Log;

class CategoryController extends BaseController
{
    public function __construct(CategoryRepository $category)
    {
        parent::__construct();
        $this->repository = $category;
    }
    public function getCategories(Request $request)
    {
        $id = $request->get('id',0);
        $categories = $this->repository->where('parent_id',$id)->get();
        return $categories;
    }
    public function getCategoriesTree(Request $request)
    {
        $categories = $this->repository->getCategoriesSelectTreeCache();
        return $categories;
    }
}
