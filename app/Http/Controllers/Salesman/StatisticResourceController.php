<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Salesman\ResourceController as BaseController;
use App\Repositories\Eloquent\AssessmentRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\SalesmanRepository;
use Illuminate\Http\Request;
use App\Models\Salesman;
use Route;
use Auth;


/**
 * Resource controller class for page.
 */
class StatisticResourceController extends BaseController
{

    public function __construct(
        CustomerRepository $customerRepository,
        AssessmentRepository $assessmentRepository,
        SalesmanRepository $salesmanRepository
    )
    {
        parent::__construct();
        $this->customerRepository = $customerRepository;
        $this->assessmentRepository = $assessmentRepository;
        $this->salesmanRepository = $salesmanRepository;
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
    public function assessment(Request $request)
    {
        $year_month = $request->year_month ?? date('Y-m');
        $first_day = $year_month.'-01 00:00:00';
        $last_day = date('Y-m-d 23:59:59', strtotime("$first_day +1 month -1 day"));

        $assessments = $this->assessmentRepository->orderBy('order','asc')->orderBy('id','asc')->get();
        $performance_bonus = setting('performance_bonus');
        $salesman = Salesman::where('id',Auth::user()->id)->first();

        $total_bonus = $total_score = 0;
        $salesman->assessment = $this->salesmanRepository->getAssessment(Auth::user()->id,$first_day,$last_day);
        foreach ($assessments as $key => $assessment)
        {
            $get_bonus = $score = 0;
            if($assessment->type == 'performance'){
                $completion_rate = sprintf("%01.2f", $salesman->assessment[$assessment->slug]/$assessment->standard*100);
                $bonus = $assessment['proportion'] / 100 * $performance_bonus;
                $score = round(($completion_rate > 100 ? 100 :  ($completion_rate / 100 * $bonus))/$performance_bonus * 100,2);
                if($completion_rate >= $assessment->lowest_completion_rate){
                    if($completion_rate >= 100){
                        $get_bonus = $bonus;
                    }else{
                        $get_bonus = round($bonus * $completion_rate/100,2);
                    }

                }
            }
            $total_bonus += $get_bonus;
            $total_score += $score;
        }
        $salesman->total_bonus = ceil($total_bonus);
        $salesman->total_score = $total_score;

        return $this->response->title(trans('statistic.name'))
            ->view('statistic.assessment')
            ->data(compact('assessments','salesman','performance_bonus','year_month'))
            ->output();
    }

}