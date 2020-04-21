<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomerExport;
use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Imports\CustomerImport;
use App\Models\Customer;
use App\Models\Freight;
use App\Models\Salesman;
use App\Repositories\Eloquent\NewCustomerRepository;
use App\Repositories\Eloquent\SalesmanRepository;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\CustomerRepository;
use Excel;

class CustomerResourceController extends BaseController
{
    public function __construct(
        CustomerRepository $customer,
        NewCustomerRepository $newCustomerRepository,
        SalesmanRepository $salesmanRepository
    )
    {
        parent::__construct();
        $this->repository = $customer;
        $this->salesmanRepository= $salesmanRepository;
        $this->newCustomerRepository = $newCustomerRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $customers = $this->repository
                ->orderBy('id','desc')
                ->paginate($limit);

            return $this->response
                ->success()
                ->count($customers->total())
                ->data($customers->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('customer.name'))
            ->view('customer.index')
            ->output();
    }
    public function create(Request $request)
    {
        $customer = $this->repository->newInstance([]);

        $salesmen = $this->salesmanRepository->getActiveSalesmen();
        $new_customer_id = $request->get('new_customer_id','');
        if($new_customer_id)
        {
            $new_customer = $this->newCustomerRepository->find($new_customer_id);
            $customer->name = $new_customer->nickname;
            $customer->email = $new_customer->email;
            $customer->ig = $new_customer->ig;
            $customer->mobile = $new_customer->mobile;
            $customer->imessage = $new_customer->imessage;
            $customer->whatsapp = $new_customer->mobile;
            $customer->salesman_id = $new_customer->salesman_id;
        }

        return $this->response->title(trans('customer.name'))
            ->view('customer.create')
            ->data(compact('customer','salesmen','new_customer_id'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $salesman = $this->salesmanRepository->find($attributes['salesman_id']);
            if(isset($attributes['new_customer_id']) && $attributes['new_customer_id'])
            {
                $this->newCustomerRepository->update(['mark' => 'order'],$attributes['new_customer_id']);
            }
            $attributes['salesman_name'] = $salesman->name;
            $customer = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('customer.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('customer'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('customer'))
                ->redirect();
        }
    }
    public function show(Request $request,Customer $customer)
    {
        if ($customer->exists) {
            $view = 'customer.show';
        } else {
            $view = 'customer.create';
        }
        $salesmen = $this->salesmanRepository->getActiveSalesmen();
        return $this->response->title(trans('app.view') . ' ' . trans('customer.name'))
            ->data(compact('customer','salesmen'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Customer $customer)
    {
        try {
            $attributes = $request->all();
            if(isset($attributes['salesman_id']))
            {
                $salesman = $this->salesmanRepository->find($attributes['salesman_id']);
                $attributes['salesman_name'] = $salesman->name;
            }
            $customer->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('customer.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('customer'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('customer/' . $customer->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,Customer $customer)
    {
        try {
            $this->repository->delete([$customer->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('customer.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('customer'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('customer'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->delete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('customer.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('customer'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('customer'))
                ->redirect();
        }
    }
    public function import(Request $request)
    {
        return $this->response->title(trans('salesman.name'))
            ->view('customer.import')
            ->output();
    }
    public function submitImport(Request $request)
    {

        set_time_limit(0);
        $file = $request->file;
        isVaildExcel($file);
        $res = (new CustomerImport)->toArray($file)[0];
        $res = array_filter($res);
        $all_sheet_count = count($res);

        $excel_key_arr = config('model.customer.customer.excel');

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
            if($res[$i])
            {
                foreach ($header_keys as $header_key => $header_i) {
                    $data[$i][$header_key] = $res[$i][$header_i];
                }

                if($data[$i]['name'])
                {
                    $salesman_name = $data[$i]['salesman_name'];
                    if (isset($salesmen[$salesman_name])) {
                        $salesman_id = $salesmen[$salesman_name];
                    } else {
                        $salesman = Salesman::where('name', $salesman_name)->orWhere('en_name',$salesman_name)->first(['id','name']);
                        $salesman_name = $salesman ? $salesman->name : $salesman_name;
                        $salesman_id = $salesman ? $salesman->id : 0;
                        $salesmen[$salesman_name] = $salesman_id;
                    }
                    $data[$i]['salesman_name'] = $salesman_name;
                    $data[$i]['salesman_id'] = $salesman_id;
                    $data[$i]['created_at'] = $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                    $success_count++;
                }
            }
        }

        $insert_res = Customer::insert($data);
        if(!count($data))
        {
            return $this->response->message(trans("messages.excel.not_found_data"))
                ->status("success")
                ->code(400)
                ->url(guard_url('customer_import'))
                ->redirect();
        }
        if($insert_res)
        {
            return $this->response->message("共发现".$count."条数据，排除空行后共成功上传".$success_count."条")
                ->status("success")
                ->code(200)
                ->url(guard_url('customer'))
                ->redirect();
        }else{
            return $this->response->message("上传数据失败")
                ->status("success")
                ->code(400)
                ->url(guard_url('customer_import'))
                ->redirect();
        }

    }
    public function getCustomer(Request $request)
    {
        $customer = $this->repository->find($request->id,['address','area_code']);
        return $this->response
            ->success()
            ->data($customer->toArray())
            ->json();
    }
    public function download(Request $request)
    {
        $data = $request->all();
        $ids = $data['ids'] ?? [];
        $name = '客户信息表'.date('YmdHis').'.xlsx';
        $search = $request->input('search',[]);
        return Excel::download(new CustomerExport($ids,$search), $name);
    }
}
