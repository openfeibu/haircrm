<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller as BaseController;
use App\Repositories\Eloquent\CategoryRepository;
use App\Traits\Theme\ThemeAndViews;
use App\Traits\AdminUser\RoutesAndGuards;
use App\Traits\AdminUser\AdminUserPages;
use App\Http\Response\ResourceResponse;
use Illuminate\Support\Facades\Cache;

class UserController extends BaseController
{
    use RoutesAndGuards, ThemeAndViews, AdminUserPages;

    /**
     * Initialize public controller.
     *
     * @return null
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        parent::__construct();
        if (!empty(app('auth')->getDefaultDriver())) {
            $this->middleware('auth:' . app('auth')->getDefaultDriver());
        }
        $this->response = app(ResourceResponse::class);
        $this->categoryRepository = $categoryRepository;
        $this->setTheme();
    }

    /**
     * Show dashboard for each user.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return $this->response
            ->layout('user')
            ->title('修改密码')
            ->view('home')
            ->output();
    }

    public function updateCache()
    {
        $this->categoryRepository->forgetCategoriesSelectTree();
        $this->categoryRepository->getCategoriesSelectTreeCache();
        $this->categoryRepository->getCategoriesCache();

        return $this->response->message('更新成功！')
            ->code(0)
            ->status('success')
            ->redirect();

    }
}
