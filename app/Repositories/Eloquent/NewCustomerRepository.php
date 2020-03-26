<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\NewCustomerRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class NewCustomerRepository extends BaseRepository implements NewCustomerRepositoryInterface
{

    public function boot()
    {
        $this->fieldSearchable = config('model.customer.new_customer.search');
    }
    public function model()
    {
        return config('model.customer.new_customer.model');
    }

}