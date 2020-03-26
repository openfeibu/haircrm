<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\SalesmanRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SalesmanRepository extends BaseRepository implements SalesmanRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.salesman.salesman.search');
    }
    public function model()
    {
        return config('model.salesman.salesman.model');
    }
    public function getActiveSalesmen()
    {
        return $this->orderBy('active','desc')->orderBy('order','asc')->orderBy('id','desc')->get();
    }

}