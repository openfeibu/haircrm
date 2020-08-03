<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Salesman\ResourceController as BaseController;
use Auth;
use App\Imports\NewCustomerImport;
use App\Models\NewCustomer;
use App\Repositories\Eloquent\SalesmanRepository;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\NewCustomerRepository;

class NewCustomerResourceController extends BaseController
{
    public function __construct(
        NewCustomerRepository $new_customer,
        SalesmanRepository $salesmanRepository
    )
    {
        parent::__construct();
        $this->repository = $new_customer;
        $this->salesmanRepository= $salesmanRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        if ($this->response->typeIs('json')) {
            $new_customers = app(NewCustomer::class);
            $new_customers = $new_customers->where('salesman_id',Auth::user()->id)->when($search,function ($query) use ($search){
                foreach($search as $field => $value)
                {
                    if($value)
                    {
                        if($field == 'email_not_null')
                        {
                            if($value == 1)
                            {
                                 $query->whereNotNull('email')->where('email','<>','');
                            }
                        }else{
                             $query->where($field,'like','%'.$value.'%');
                        }

                    }
                }
            });

            $new_customers = $new_customers->orderBy('id','desc')->paginate($limit);

            return $this->response
                ->success()
                ->count($new_customers->total())
                ->data($new_customers->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('new_customer.name'))
            ->view('new_customer.index')
            ->output();
    }
    public function create(Request $request)
    {
        $new_customer = $this->repository->newInstance([]);

        $salesmen = $this->salesmanRepository->getActiveSalesmen();

        return $this->response->title(trans('new_customer.name'))
            ->view('new_customer.create')
            ->data(compact('new_customer','salesmen'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $attributes['salesman_id'] = Auth::user()->id;
            $attributes['salesman_name'] = Auth::user()->name;
            $new_customer = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('new_customer.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('new_customer'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('new_customer'))
                ->redirect();
        }
    }
    public function show(Request $request,NewCustomer $new_customer)
    {
        if ($new_customer->exists) {
            $view = 'new_customer.show';
        } else {
            $view = 'new_customer.create';
        }
        $salesmen = $this->salesmanRepository->getActiveSalesmen();
        return $this->response->title(trans('app.view') . ' ' . trans('new_customer.name'))
            ->data(compact('new_customer','salesmen'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,NewCustomer $new_customer)
    {
        try {
            $attributes = $request->all();

            $new_customer->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('new_customer.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('new_customer'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('new_customer/' . $new_customer->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,NewCustomer $new_customer)
    {
        try {
            $this->repository->delete([$new_customer->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('new_customer.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('new_customer'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('new_customer'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->delete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('new_customer.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('new_customer'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('new_customer'))
                ->redirect();
        }
    }
    public function import(Request $request)
    {
        return $this->response->title(trans('salesman.name'))
            ->view('new_customer.import')
            ->output();
    }
    public function submitImport(Request $request)
    {

        set_time_limit(0);
        $file = $request->file;
        isVaildExcel($file);
        $res = (new NewCustomerImport)->toArray($file)[0];
        $res = array_filter($res);
        $all_sheet_count = count($res);

        $excel_key_arr = config('model.customer.new_customer.excel');

        $items = [];

        $header_arr = $res[0];
        $header_keys = [];
        foreach ($header_arr as $key => $header)
        {
            $header_keys[$excel_key_arr[$header]] = $key;
        }
        $data = [];
        $salesmen = [];
        $success_count=0;
        $count = $all_sheet_count-1;
        for ($i=1;$i<$all_sheet_count;$i++)
        {
            if(count(array_filter($res[$i]))>2)
            {
                if(isset($res[$i][0]) && $res[$i][0])
                {
                    if(strpos($res[$i][0],'客户') !== false)
                    {
                        continue;
                    }
                    $reg="/(\d{4})[\.|\-|~](\d{2})[\.|\-|~](\d{2})/";
                    if(preg_match($reg,$res[$i][0],$parts))
                    {
                        if(checkdate($parts[2],$parts[3],$parts[1]))
                        {
                            continue;
                        }
                    }
                }
                if(isset($res[$i][1]) && $res[$i][1])
                {
                    if(strpos($res[$i][1],'客户') !== false)
                    {
                        continue;
                    }
                    $reg="/(\d{4})[\.|\-|~](\d{2})[\.|\-|~](\d{2})/";
                    if(preg_match($reg,$res[$i][1],$parts))
                    {
                        if(checkdate($parts[2],$parts[3],$parts[1]))
                        {
                            continue;
                        }
                    }
                }

                foreach ($header_keys as $header_key => $header_i) {
                    $data[$i][$header_key] = $res[$i][$header_i];
                }
                if($data[$i]['company_name']) {
                    $data[$i]['salesman_id'] = Auth::user()->id;
                    $data[$i]['salesman_name'] = Auth::user()->name;
                    $data[$i]['created_at'] = $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                    $success_count++;
                }
            }
        }

        $insert_res = NewCustomer::insert($data);

        if(!count($data))
        {
            return $this->response->message(trans("messages.excel.not_found_data"))
                ->status("success")
                ->code(400)
                ->url(guard_url('new_customer_import'))
                ->redirect();
        }
        if($insert_res)
        {
            return $this->response->message("共发现".$count."条数据，排除空行后共成功上传".$success_count."条")
                ->status("success")
                ->code(200)
                ->url(guard_url('new_customer'))
                ->redirect();
        }else{
            return $this->response->message("上传数据失败")
                ->status("success")
                ->code(400)
                ->url(guard_url('new_customer_import'))
                ->redirect();
        }
    }
    public function mailCount(Request $request)
    {
        $data = $request->all();
        $ids = $data['ids'] ?? [];
        $search = $request->input('search',[]);
        $new_customers = app(NewCustomer::class)
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
            ->orderBy('id','desc')->get(['email']);

        $count = $new_customers->count();

        if($count <= 0)
        {
            return $this->response->message("未发现任何可发送的邮箱")
                ->status("error")
                ->code(400)
                ->url(guard_url('mail_schedule'))
                ->redirect();
        }
        return $this->response
            ->status("success")
            ->data([
                'count' => $count,
            ])
            ->redirect();
    }
}
