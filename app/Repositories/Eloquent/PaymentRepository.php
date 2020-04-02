<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\PaymentRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{

    public function boot()
    {
        $this->fieldSearchable = config('model.payment.payment.search');
    }
    public function model()
    {
        return config('model.payment.payment.model');
    }

}