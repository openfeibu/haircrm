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

class NewCustomerExport implements FromCollection,WithEvents
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

        $new_customers = app(NewCustomer::class)
            ->when($this->ids ,function ($query) {
                return $query->whereIn('id',$this->ids);
            })->when($this->search ,function ($query){
                foreach($this->search as $field => $value)
                {
                    return $query->where($field,'like','%'.$value.'%');
                }
            })->orderBy('id','desc')->get();

        $this->count = $new_customers->count();
        $header_data = [
            [trans('salesman.label.name'),trans('new_customer.label.company_name'),trans('new_customer.label.company_website'),trans('new_customer.label.nickname'),trans('new_customer.label.email'),trans('new_customer.label.mobile'),trans('new_customer.label.imessage'),trans('new_customer.label.whatsapp'),trans('new_customer.label.main_product'),trans('new_customer.label.ig'),trans('new_customer.label.ig_follower_count'),trans('new_customer.label.ig_sec'),trans('new_customer.label.facebook'),trans('new_customer.label.mark'),trans('new_customer.label.remark')]
        ];
        $new_customer_data = [];
        $i = $sn = 0;
        foreach ($new_customers as $key => $new_customer)
        {
            $sn++;
            $new_customer_data[$i] = [
                $new_customer->salesman_name,$new_customer->company_name,$new_customer->company_website,$new_customer->nickname,$new_customer->email,$new_customer->mobile,$new_customer->imessage,$new_customer->whatsapp,$new_customer->main_product,$new_customer->ig,$new_customer->ig_follower_count,$new_customer->ig_secondary,$new_customer->facebook,$new_customer->mark_desc,$new_customer->remark
            ];
            $i++;

        }
        $data = array_merge($header_data,$new_customer_data);
        return  new Collection($data);

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {

                //设置列宽
                $columns = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O'];
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
                $event->sheet->getDelegate()->getStyle('A1:O'.($this->count+1))->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->getStyle('A1:O'.($this->count+1))->getAlignment()->setHorizontal('center');
               // $event->sheet->getDelegate()->getStyle('A4:H4')->getAlignment()->setHorizontal('left');

                //$event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(50);

                $event->sheet->getDelegate()->getStyle('A1:O1')->getFont()->setSize('16');
                $event->sheet->getDelegate()->getStyle('A2:O'.($this->count+1))->getFont()->setSize('15');

            }
        ];
    }
}