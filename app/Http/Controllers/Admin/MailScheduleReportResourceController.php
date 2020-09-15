<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\MailScheduleReport;
use App\Models\NewCustomer;
use App\Repositories\Eloquent\MailScheduleReportRepository;
use Illuminate\Http\Request;
use Auth,DB;

class MailScheduleReportResourceController extends BaseController
{
    public function __construct(
        MailScheduleReportRepository $mailScheduleReportRepository
    )
    {
        parent::__construct();
        $this->repository = $mailScheduleReportRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        if ($this->response->typeIs('json')) {
            $mail_schedule_reports =
                MailScheduleReport::where('mail_schedule_id',$request->get('mail_schedule_id'))
                ->when($search,function ($query) use ($search){
                    foreach($search as $field => $value)
                    {
                        if($value)
                        {
                            $query->where($field,'like','%'.$value.'%');
                        }
                    }
                })
                ->orderBy('id','desc')
                ->paginate($limit);

            return $this->response
                ->success()
                ->count($mail_schedule_reports->total())
                ->data($mail_schedule_reports->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('mail_schedule_report.name'))
            ->view('mail_schedule_report.index')
            ->output();
    }
    public function create(Request $request)
    {
        $mail_schedule_report = $this->repository->newInstance([]);

        return $this->response->title(trans('mail_schedule_report.name'))
            ->view('mail_schedule_report.create')
            ->data(compact('mail_schedule_report'))
            ->output();
    }
    public function store(Request $request)
    {
        try {

        } catch (Exception $e) {

        }
    }
    public function show(Request $request,MailScheduleReport $mail_schedule_report)
    {

    }
    public function update(Request $request,MailScheduleReport $mail_schedule_report)
    {
        try {
            $attributes = $request->all();


        } catch (Exception $e) {

        }
    }
    public function destroy(Request $request,MailScheduleReport $mail_schedule_report)
    {
        try {
            $this->repository->forceDelete([$mail_schedule_report->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('mail_schedule_report.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('mail_schedule_report'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('mail_schedule_report'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('mail_schedule_report.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('mail_schedule_report'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('mail_schedule_report'))
                ->redirect();
        }
    }

}
