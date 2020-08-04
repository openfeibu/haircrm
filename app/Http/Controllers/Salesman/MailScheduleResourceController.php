<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Salesman\ResourceController as BaseController;
use App\Models\MailSchedule;
use App\Models\MailScheduleReport;
use App\Models\NewCustomer;
use App\Repositories\Eloquent\MailScheduleRepository;
use Illuminate\Http\Request;
use Auth,DB;

class MailScheduleResourceController extends BaseController
{
    public function __construct(
        MailScheduleRepository $mailScheduleRepository
    )
    {
        parent::__construct();
        $this->repository = $mailScheduleRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $mail_schedules = $this->repository
                ->where('admin_model',get_admin_model(Auth::user()))
                ->where('admin_id',Auth::user()->id)
                ->orderBy('id','desc')
                ->paginate($limit);
            foreach ($mail_schedules as $key => $mail_schedule)
            {
                $mail_schedule->admin_name = get_admin_detail($mail_schedule->admin_model,$mail_schedule->admin_id);
                $mail_schedule->account_usernames = implode($mail_schedule->accounts->pluck('username')->toArray(),',');
                $mail_schedule->template_names = implode($mail_schedule->templates->pluck('name')->toArray(),',');
            }
            return $this->response
                ->success()
                ->count($mail_schedules->total())
                ->data($mail_schedules->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('mail_schedule.name'))
            ->view('mail_schedule.index')
            ->output();
    }
    public function create(Request $request)
    {
        $mail_schedule = $this->repository->newInstance([]);

        return $this->response->title(trans('mail_schedule.name'))
            ->view('mail_schedule.create')
            ->data(compact('mail_schedule'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();
            $attributes['admin_id'] = Auth::user()->id;
            $attributes['admin_model'] = get_admin_model(Auth::user());

            $mail_schedule = $this->repository->create($attributes);

            $accounts = $request->get('accounts');

            $mail_schedule->accounts()->sync($accounts);

            $templates = $request->get('templates');

            $mail_schedule->templates()->sync($templates);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('mail_schedule.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('mail_schedule'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('mail_schedule'))
                ->redirect();
        }
    }
    public function show(Request $request,MailSchedule $mail_schedule)
    {
        if ($mail_schedule->exists) {
            $view = 'mail_schedule.show';
        } else {
            $view = 'mail_schedule.create';
        }
        return $this->response->title(trans('app.view') . ' ' . trans('mail_schedule.name'))
            ->data(compact('mail_schedule'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,MailSchedule $mail_schedule)
    {
        try {
            $attributes = $request->all();

            if(isset($attributes['active']))
            {
                if($attributes['active'])
                {
                    $attributes['active'] = '1';
                }else{
                    $attributes['active'] = '0';
                }
            }

            $mail_schedule->update($attributes);

            $accounts = $request->get('mail_accounts');
            if($accounts)
            {
                $mail_schedule->accounts()->sync($accounts);
            }
            $templates = $request->get('mail_templates');
            if($templates) {
                $mail_schedule->templates()->sync($templates);
            }

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('mail_schedule.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('mail_schedule'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('mail_schedule/' . $mail_schedule->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,MailSchedule $mail_schedule)
    {
        try {
            $this->repository->forceDelete([$mail_schedule->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('mail_schedule.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('mail_schedule'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('mail_schedule'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('mail_schedule.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('mail_schedule'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('mail_schedule'))
                ->redirect();
        }
    }
    public function sendNewCustomer(Request $request)
    {
        try {

            $attributes = $request->all();
            $attributes['admin_id'] = Auth::user()->id;
            $attributes['admin_model'] = get_admin_model(Auth::user());

            $ids = $attributes['ids'] ?? [];
            $search = $request->input('search',[]);
            $new_customers = DB::table('new_customers')
                ->select(DB::raw('email'))
                ->when($ids ,function ($query) use ($ids){
                     $query->whereIn('id',$ids);
                })->when($search,function ($query) use ($search){
                    foreach($search as $field => $value)
                    {
                        if($value)
                        {
                            if($field == 'salesman_id')
                            {
                                 $query->where('salesman_id',$value);
                            }else{
                                 $query->where($field,'like','%'.$value.'%');
                            }
                        }
                    }
                })
                ->whereNotNull('email')
                ->where('email','<>','')
                ->orderBy('id','desc')->get();

            $count = $new_customers->count();

            if($count <= 0)
            {
                return $this->response->message("未发现任何可发送的邮箱")
                    ->status("error")
                    ->code(400)
                    ->url(guard_url('mail_schedule'))
                    ->redirect();
            }
            $attributes['mail_count'] = $count;
            $mail_schedule = $this->repository->create($attributes);

            $accounts = $request->get('account_ids');

            $mail_schedule->accounts()->sync($accounts);

            $templates = $request->get('template_ids');

            $mail_schedule->templates()->sync($templates);

            $mail_schedule_reports = DB::table('new_customers')
                ->select(DB::raw("email,'".$mail_schedule->id."' as mail_schedule_id " ))
                ->when($ids ,function ($query) use ($ids){
                    return $query->whereIn('id',$ids);
                })->when($search,function ($query) use ($search){
                    foreach($search as $field => $value)
                    {
                        if($value)
                        {
                            if($field == 'salesman_id')
                            {
                                return $query->where('salesman_id',$value);
                            }else{
                                return $query->where($field,'like','%'.$value.'%');
                            }
                        }
                    }
                })
                ->whereNotNull('email')
                ->where('email','<>','')
                ->orderBy('id','desc')->get();
            $mail_schedule_reports = json_decode(json_encode($mail_schedule_reports), true);
            DB::table('mail_schedule_reports')->insert($mail_schedule_reports);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('mail_schedule.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('mail_schedule'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('mail_schedule'))
                ->redirect();
        }




    }
}
