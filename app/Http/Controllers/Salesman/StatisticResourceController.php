<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Salesman\ResourceController as BaseController;
use App\Repositories\Eloquent\CustomerRepository;
use Illuminate\Http\Request;
use App\Models\Salesman;
use Route;
use Auth;


/**
 * Resource controller class for page.
 */
class StatisticResourceController extends BaseController
{

    public function __construct(CustomerRepository $customerRepository)
    {
        parent::__construct();
        $this->customerRepository = $customerRepository;
    }

    public function customer()
    {
        return $this->response->title(trans('statistic.name'))
            ->view('statistic.customer')
            ->output();
    }
    public function get_customers_statistics(Request $request)
    {
        $salesman_id = Auth::user()->id;
        $statistic_data = $this->customerRepository->statistic($salesman_id);
        return $this->response
            ->success()
            ->data($statistic_data)
            ->json();

    }

}