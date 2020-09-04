<?php

namespace App\Http\Controllers\Pc;

use App\Repositories\Eloquent\CategoryRepository;
use Illuminate\Http\Request;
use Route,Auth;
use App\Http\Controllers\Pc\Controller as BaseController;


class CategoryController extends BaseController
{
    public function __construct(CategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * Show dashboard for each user.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category_id = $request->get('id');
       // $categories =
        return $this->response->title('首页')
            ->view('category.index')
            ->data(compact('banners'))
            ->output();
    }

}
