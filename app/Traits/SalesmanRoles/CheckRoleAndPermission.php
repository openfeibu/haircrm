<?php

namespace App\Traits\SalesmanRoles;

use Illuminate\Support\Str;


trait CheckRoleAndPermission
{
    /**
     * Property for caching role.
     *
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected $role;


    /**
     * Set role for the model.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function setRole($role)
    {
        $this->role = app('App\Repositories\Eloquent\SalesmanRoleRepository')->findRoleBySlug($role);
    }

    /**
     * Check if the user has at least one role.
     *
     * @param int|string|array $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role->slug == $role;
    }


    /**
     * Check if the user has the permission.
     *
     * @param int|string|array $permission
     * @return bool
     */
    public function canDo($permission)
    {
        return $this->role->hasPermission($permission);
    }
    /**
     * Handle dynamic method calls.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (starts_with($method, 'is')) {
            return $this->hasRole(snake_case(substr($method, 2), config('model.provider_roles.separator', '.')));
        }

        return parent::__call($method, $parameters);
    }

}
