<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\SalesmanRoleRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SalesmanRoleRepository extends BaseRepository implements SalesmanRoleRepositoryInterface
{


    public function boot()
    {
        $this->fieldSearchable = config('model.salesman_roles.salesman_role.model.search');
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return config('model.salesman_roles.salesman_role.model.model');
    }

    /**
     * Find a user by its key.
     *
     * @param type $key
     *
     * @return type
     */
    public function findRoleBySlug($key)
    {
        return $this->model->whereSlug($key)->first();
    }
}
