<?php

namespace App\Exports;

use App\Models\NewCustomer;
use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Repositories\Eloquent\OrderGoodsRepository;
use App\Repositories\Eloquent\OrderRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class NewCustomerEmailExport implements FromCollection,WithEvents
{

    use RegistersEventListeners;

    public $count = 0;

    public function __construct($ids=[],$search=[])
    {
        $this->ids = $ids;
        $this->search = $search;
    }

    public function collection()
    {

        $title = '客户邮箱信息表';

        $new_customers = app(NewCustomer::class)
            ->when($this->ids ,function ($query) {
                return $query->whereIn('id',$this->ids);
            })->when($this->search ,function ($query){

                foreach($this->search as $field => $value)
                {
                    if($value)
                    {
                        if($field == 'salesman_id')
                        {
                            $query->where('salesman_id',$value);
                        }else if($field == 'email_not_null')
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
            })->orderBy('id','desc')->get(['email']);

        $this->count = $new_customers->count();
        $header_data = [
            [trans('new_customer.label.email')]
        ];
        $new_customer_data = [];
        $i = $sn = 0;
        foreach ($new_customers as $key => $new_customer)
        {
            $sn++;
            $new_customer_data[$i] = [
                $new_customer->email
            ];
            $i++;

        }
       // $data = array_merge($header_data,$new_customer_data);
        return  new Collection($new_customer_data);

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {

                //设置列宽
                $columns = ['A'];
                foreach ($columns as $key => $column)
                {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(20);
                }
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
                //$event->sheet->getDelegate()->getColumnDimension('A')->setWidth(30);
                //设置行高，$i为数据行数
                for ($i = 0; $i<=$this->count+1; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(30);
                }
                //设置区域单元格垂直居中
//                $event->sheet->getDelegate()->getStyle('A1:O'.($this->count+1))->getAlignment()->setVertical('center');
//                $event->sheet->getDelegate()->getStyle('A1:O'.($this->count+1))->getAlignment()->setHorizontal('center');

            }
        ];
    }
}