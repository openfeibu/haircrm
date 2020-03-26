<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\CategoryRepository;
use Tree;

class CategoryResourceController extends BaseController
{
    public function __construct(CategoryRepository $category)
    {
        parent::__construct();
        $this->repository = $category;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $categories = $this->repository->getCategories();

        $categories = json_encode($categories);

        return $this->response->title(trans('category.name'))
            ->view('category.index')
            ->data(compact('categories'))
            ->output();
    }
    public function create(Request $request)
    {
        $categories = $this->repository->get()->toArray();
        $categories = Tree::getTree($categories);
        $category = $this->repository->newInstance([]);
        $parent_id = $request->get('parent_id',0);
        return $this->response->title(trans('category.name'))
            ->view('category.create')
            ->data(compact('category','categories','parent_id'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            if(isset($attributes['split']['/']) && $attributes['split']['/'] == 'on')
            {
                $categories = preg_split("/(\/|\n)/",$attributes['categories']);
            }else{
                $categories = preg_split("/(\n)/",$attributes['categories']);
            }

            $parent_id = $attributes['parent_id'];

            $top_parent_id = $this->repository->getTopParentId($parent_id);
            $category_id_arr =$this->repository->getCategoryIds($parent_id);
            $category_ids = $category_id_arr ? implode(',',$category_id_arr) : null;

            $data = [];
            foreach ($categories as $category)
            {
                $data[] = [
                    'name' => $category,
                    'parent_id' => $parent_id,
                    'top_parent_id' => $top_parent_id,
                    'category_ids' => $category_ids,
                ];
            }

            Category::insert($data);
            //$category = $this->repository->create($attributes);
            $this->repository->forgetCategoriesSelectTree();
            return $this->response->message(trans('messages.success.created', ['Module' => trans('category.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('category'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('category'))
                ->redirect();
        }
    }
    public function show(Request $request,Category $category)
    {
        if ($category->exists) {
            $view = 'category.show';
        } else {
            $view = 'category.create';
        }

        return $this->response->title(trans('app.view') . ' ' . trans('category.name'))
            ->data(compact('category'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Category $category)
    {
        try {
            $attributes = $request->all();

            $category->update($attributes);
            $this->repository->forgetCategoriesSelectTree();
            return $this->response->message(trans('messages.success.updated', ['Module' => trans('category.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('category'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('category/' . $category->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,Category $category)
    {
        try {
            $this->repository->forceDelete([$category->id]);
            $this->repository->forgetCategoriesSelectTree();
            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('category.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('category'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('category'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);
            $this->repository->forgetCategoriesSelectTree();
            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('category.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('category'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('category'))
                ->redirect();
        }
    }

    /*
     *
     $categories = $this->repository->get();
        foreach ($categories as $key => $category)
        {
            $parent_id = $category['parent_id'];

            $top_parent_id = $this->repository->getTopParentId($parent_id);
            $category_id_arr =$this->repository->getCategoryIds($parent_id);
            $category_ids = $category_id_arr ? implode(',',$category_id_arr) : null;
var_dump($top_parent_id,$category_ids);
            $this->repository->update([
                'top_parent_id' => $top_parent_id,
                'category_ids' => $category_ids,
            ],$category['id']);

        }
        exit;
     */
}
