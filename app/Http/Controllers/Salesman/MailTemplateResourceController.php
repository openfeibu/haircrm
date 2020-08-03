<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Salesman\ResourceController as BaseController;
use App\Models\MailTemplate;
use App\Repositories\Eloquent\MailTemplateRepository;
use Illuminate\Http\Request;
use Auth;

class MailTemplateResourceController extends BaseController
{
    public function __construct(
        MailTemplateRepository $mailTemplateRepository
    )
    {
        parent::__construct();
        $this->repository = $mailTemplateRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $mail_templates = $this->repository
                ->where('salesman_id',Auth::user()->id)
                ->orderBy('id','desc')
                ->paginate($limit);
            foreach ($mail_templates as $key => $mail_template)
            {
                $mail_template->admin_name = get_admin_detail($mail_template->admin_model,$mail_template->admin_id);
            }
            return $this->response
                ->success()
                ->count($mail_templates->total())
                ->data($mail_templates->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('mail_template.name'))
            ->view('mail_template.index')
            ->output();
    }
    public function create(Request $request)
    {
        $mail_template = $this->repository->newInstance([]);

        return $this->response->title(trans('mail_template.name'))
            ->view('mail_template.create')
            ->data(compact('mail_template'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();
            $attributes['admin_id'] = Auth::user()->id;
            $attributes['admin_model'] = get_admin_model(Auth::user());
            $attributes['salesman_id'] = Auth::user()->id;

            if(isset($attributes['active']) && $attributes['active'])
            {
                $attributes['active'] = '1';
            }else{
                $attributes['active'] = '0';
            }

            $mail_template = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('mail_template.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('mail_template'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('mail_template'))
                ->redirect();
        }
    }
    public function show(Request $request,MailTemplate $mail_template)
    {
        if ($mail_template->exists) {
            $view = 'mail_template.show';
        } else {
            $view = 'mail_template.create';
        }

        return $this->response->title(trans('app.view') . ' ' . trans('mail_template.name'))
            ->data(compact('mail_template'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,MailTemplate $mail_template)
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

            $mail_template->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('mail_template.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('mail_template'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('mail_template/' . $mail_template->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,MailTemplate $mail_template)
    {
        try {
            $this->repository->forceDelete([$mail_template->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('mail_template.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('mail_template'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('mail_template'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('mail_template.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('mail_template'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('mail_template'))
                ->redirect();
        }
    }

}
