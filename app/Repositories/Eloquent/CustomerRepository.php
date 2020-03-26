<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\CustomerRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.customer.customer.search');
    }

    public function model()
    {
        return config('model.customer.customer.model');
    }
    public function getSalesmanCustomers($salesman_id)
    {
        return $this->where('salesman_id',$salesman_id)->orderBy('name','asc')->orderBy('id','desc')->get();
    }

}