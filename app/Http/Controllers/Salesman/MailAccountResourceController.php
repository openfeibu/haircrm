<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Salesman\ResourceController as BaseController;
use App\Models\MailAccount;
use App\Repositories\Eloquent\MailAccountRepository;
use Illuminate\Http\Request;
use Auth;

class MailAccountResourceController extends BaseController
{
    public function __construct(
        MailAccountRepository $mailAccountRepository
    )
    {
        parent::__construct();
        $this->repository = $mailAccountRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $mail_accounts = $this->repository
                ->where('salesman_id',Auth::user()->id)
                ->orderBy('id','desc')
                ->paginate($limit);
            foreach ($mail_accounts as $key => $mail_account)
            {
                $mail_account->admin_name = get_admin_detail($mail_account->admin_model,$mail_account->admin_id);
            }
            return $this->response
                ->success()
                ->count($mail_accounts->total())
                ->data($mail_accounts->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('mail_account.name'))
            ->view('mail_account.index')
            ->output();
    }
    public function create(Request $request)
    {
        $mail_account = $this->repository->newInstance([]);

        return $this->response->title(trans('mail_account.name'))
            ->view('mail_account.create')
            ->data(compact('mail_account'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();
            $attributes['admin_id'] = Auth::user()->id;
            $attributes['admin_model'] = get_admin_model(Auth::user());
            $attributes['salesman_id'] = Auth::user()->id;

            $mail_account = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('mail_account.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('mail_account'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('mail_account'))
                ->redirect();
        }
    }
    public function show(Request $request,MailAccount $mail_account)
    {
        if ($mail_account->exists) {
            $view = 'mail_account.show';
        } else {
            $view = 'mail_account.create';
        }
        return $this->response->title(trans('app.view') . ' ' . trans('mail_account.name'))
            ->data(compact('mail_account'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,MailAccount $mail_account)
    {
        try {
            $attributes = $request->all();

            $mail_account->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('mail_account.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('mail_account'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('mail_account/' . $mail_account->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,MailAccount $mail_account)
    {
        try {
            $this->repository->forceDelete([$mail_account->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('mail_account.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('mail_account'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('mail_account'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('mail_account.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('mail_account'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('mail_account'))
                ->redirect();
        }
    }

}
