<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Salesman;
use App\Repositories\Eloquent\SalesmanPermissionRepository;
use App\Repositories\Eloquent\SalesmanRoleRepository;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\SalesmanRepository;

class SalesmanResourceController extends BaseController
{
    public function __construct(
        SalesmanRepository $salesman,
        SalesmanPermissionRepository $permissions,
        SalesmanRoleRepository $roles
    )
    {
        parent::__construct();
        $this->repository = $salesman;
        $this->permissions = $permissions;
        $this->roles = $roles;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $salesmen = $this->repository
                ->orderBy('active','desc')
                ->orderBy('id','desc')
                ->paginate($limit);

            return $this->response
                ->success()
                ->count($salesmen->total())
                ->data($salesmen->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('salesman.name'))
            ->view('salesman.index')
            ->output();
    }
    public function create(Request $request)
    {
        $salesman = $this->repository->newInstance([]);
        $roles       = $this->roles->all();

        return $this->response->title(trans('salesman.name'))
            ->view('salesman.create')
            ->data(compact('salesman','roles'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();
            if(isset($attributes['active']) && $attributes['active'])
            {
                $attributes['status'] = 'Active';
            }else{
                $attributes['status'] = 'Locked';
            }
            $roles          = $request->get('roles');
            $attributes['api_token'] = str_random(60);
            $salesman = $this->repository->create($attributes);

            $salesman->roles()->sync($roles);
            return $this->response->message(trans('messages.success.created', ['Module' => trans('salesman.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('salesman'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('salesman'))
                ->redirect();
        }
    }
    public function show(Request $request,Salesman $salesman)
    {
        if ($salesman->exists) {
            $view = 'salesman.show';
        } else {
            $view = 'salesman.create';
        }
        $roles = $this->roles->all();
        return $this->response->title(trans('app.view') . ' ' . trans('salesman.name'))
            ->data(compact('salesman','roles'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Salesman $salesman)
    {
        try {
            $attributes = $request->all();
            if(isset($attributes['active']))
            {
                if($attributes['active'])
                {
                    $attributes['status'] = 'Active';
                }else{
                    $attributes['status'] = 'Locked';
                }
            }

            $roles          = $request->get('roles');
            $salesman->update($attributes);
            if($roles)
            {
                $salesman->roles()->sync($roles);
            }


            return $this->response->message(trans('messages.success.updated', ['Module' => trans('salesman.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('salesman'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('salesman/' . $salesman->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,Salesman $salesman)
    {
        try {
            $this->repository->forceDelete([$salesman->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('salesman.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('salesman'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('salesman'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('salesman.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('salesman'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('salesman'))
                ->redirect();
        }
    }

}
