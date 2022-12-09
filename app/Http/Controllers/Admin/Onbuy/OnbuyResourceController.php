<?php

namespace App\Http\Controllers\Admin\Onbuy;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Onbuy\Onbuy;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;

/**
 * Resource controller class for page.
 */
class OnbuyResourceController extends BaseController
{
    /**
     * Initialize page resource controller.
     *
     */
    public function __construct()
    {
        parent::__construct();

    }
    public function index(Request $request){
        if ($this->response->typeIs('json')) {
            $data = Onbuy::getAll();
            return $this->response
                ->success()
                ->data($data)
                ->output();
        }
        return $this->response->title(trans('onbuy.name'))
            ->view('onbuy.onbuy.index')
            ->output();
    }
    public function create(Request $request)
    {
        $onbuy = $this->repository->newInstance([]);

        return $this->response->title(trans('app.admin.panel'))
            ->view('onbuy.onbuy.create')
            ->data(compact('onbuy'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $onbuy = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('onbuy.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('onbuy/' ))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('onbuy/'))
                ->redirect();
        }
    }
    public function show(Request $request,Onbuy $onbuy)
    {
        if ($onbuy->exists) {
            $view = 'onbuy.onbuy.show';
        } else {
            $view = 'onbuy.onbuy.new';
        }

        return $this->response->title(trans('app.view') . ' ' . trans('onbuy.name'))
            ->data(compact('onbuy'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Onbuy $onbuy)
    {
        try {
            $attributes = $request->all();

            $onbuy->update($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('onbuy.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('onbuy/'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('onbuy/'))
                ->redirect();
        }
    }
    public function destroy(Request $request,Onbuy $onbuy)
    {
        try {
            $onbuy->forceDelete();

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('onbuy.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('onbuy'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('onbuy'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('onbuy.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('onbuy'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('onbuy'))
                ->redirect();
        }
    }

}