<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Attribute;
use App\Repositories\Eloquent\AttributeValueRepository;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\AttributeRepository;
use Excel;

class AttributeResourceController extends BaseController
{
    public function __construct(
        AttributeRepository $Attribute,
        AttributeValueRepository $attributeValueRepository
    )
    {
        parent::__construct();
        $this->repository = $Attribute;
        $this->attributeValueRepository = $attributeValueRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $attributes = $this->repository
                ->orderBy('id','desc')
                ->paginate($limit);

            return $this->response
                ->success()
                ->count($attributes->total())
                ->data($attributes->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('attribute.name'))
            ->view('attribute.index')
            ->output();
    }
    public function create(Request $request)
    {
        $attribute = $this->repository->newInstance([]);

        $salesmen = $this->salesmanRepository->getActiveSalesmen();

        return $this->response->title(trans('attribute.name'))
            ->view('attribute.create')
            ->data(compact('attribute','salesmen'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $attribute = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('attribute.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('attribute'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('attribute'))
                ->redirect();
        }
    }
    public function show(Request $request,Attribute $attribute)
    {
        if ($attribute->exists) {
            $view = 'attribute.show';
        } else {
            $view = 'attribute.create';
        }
        $salesmen = $this->salesmanRepository->getActiveSalesmen();
        return $this->response->title(trans('app.view') . ' ' . trans('attribute.name'))
            ->data(compact('attribute','salesmen'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Attribute $attribute)
    {
        try {
            $attributes = $request->all();
            $attribute->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('attribute.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('attribute'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('attribute/' . $attribute->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,Attribute $attribute)
    {
        try {
            $this->repository->delete([$attribute->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('attribute.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('attribute'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('attribute'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->delete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('attribute.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('attribute'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('attribute'))
                ->redirect();
        }
    }

}
