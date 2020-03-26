<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\SupplierRepository;

class SupplierResourceController extends BaseController
{
    public function __construct(SupplierRepository $supplier)
    {
        parent::__construct();
        $this->repository = $supplier;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $suppliers = $this->repository
                ->orderBy('id','desc')
                ->paginate($limit);

            return $this->response
                ->success()
                ->count($suppliers->total())
                ->data($suppliers->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('supplier.name'))
            ->view('supplier.index')
            ->output();
    }
    public function create(Request $request)
    {
        $supplier = $this->repository->newInstance([]);

        return $this->response->title(trans('supplier.name'))
            ->view('supplier.create')
            ->data(compact('supplier'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $supplier = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('supplier.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('supplier'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('supplier'))
                ->redirect();
        }
    }
    public function show(Request $request,Supplier $supplier)
    {
        if ($supplier->exists) {
            $view = 'supplier.show';
        } else {
            $view = 'supplier.create';
        }

        return $this->response->title(trans('app.view') . ' ' . trans('supplier.name'))
            ->data(compact('supplier'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Supplier $supplier)
    {
        try {
            $attributes = $request->all();

            $supplier->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('supplier.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('supplier'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('supplier/' . $supplier->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,Supplier $supplier)
    {
        try {
            $this->repository->forceDelete([$supplier->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('supplier'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('supplier'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('supplier'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('supplier'))
                ->redirect();
        }
    }
}
