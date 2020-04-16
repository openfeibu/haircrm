<?php

namespace App\Exports;

use App\Models\Customer;
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

class CustomerExport implements FromCollection,WithEvents
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

        $title = '客户信息表';

        $customers = app(Customer::class)
            ->when($this->ids ,function ($query) {
                return $query->whereIn('id',$this->ids);
            })->when($this->search ,function ($query){
                foreach($this->search as $field => $value)
                {
                    return $query->where($field,'like','%'.$value.'%');
                }
            })->orderBy('id','desc')->get();
        $this->count = $customers->count();
        $header_data = [
            [trans('customer.label.name'),trans('salesman.label.name'),trans('customer.label.ig'),trans('customer.label.from'),trans('customer.label.email'),trans('customer.label.mobile'),trans('customer.label.imessage'),trans('customer.label.whatsapp'),trans('customer.label.address'),trans('customer.label.order_count'),trans('customer.label.remark')]
        ];
        $customer_data = [];
        $i = $sn = 0;
        foreach ($customers as $key => $customer)
        {
            $sn++;
            $customer_data[$i] = [
                $customer->name,$customer->salesman_name,$customer->ig,$customer->from,$customer->email,$customer->mobile,$customer->imessage,$customer->whatsapp,$customer->address,$customer->order_count,$customer->remark
            ];
            $i++;

        }
        $data = array_merge($header_data,$customer_data);
        return  new Collection($data);

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {

                //设置列宽
                $columns = ['A','B','C','D','E','F','G','H','I','J','K'];
                foreach ($columns as $key => $column)
                {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(15);
                }
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
                //$event->sheet->getDelegate()->getColumnDimension('A')->setWidth(30);
                //设置行高，$i为数据行数
                for ($i = 0; $i<=$this->count+1; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(30);
                }
                //设置区域单元格垂直居中
                $event->sheet->getDelegate()->getStyle('A1:K'.($this->count+1))->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->getStyle('A1:K'.($this->count+1))->getAlignment()->setHorizontal('center');
               // $event->sheet->getDelegate()->getStyle('A4:H4')->getAlignment()->setHorizontal('left');

                //$event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(50);

                $event->sheet->getDelegate()->getStyle('A1:K1')->getFont()->setSize('16');
                $event->sheet->getDelegate()->getStyle('A2:K'.($this->count+1))->getFont()->setSize('15');

            }
        ];
    }
}