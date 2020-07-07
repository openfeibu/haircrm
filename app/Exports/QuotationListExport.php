<?php

namespace App\Exports;

use DB;
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

class QuotationListExport implements FromCollection,WithEvents
{

    use RegistersEventListeners;

    public $count = 0;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function collection()
    {

        $title = 'Feibu Hair Quotation';
        $orders_statistics =  Order::select(DB::raw("sum(`freight`) as freight,sum(`weight`) as weight,sum(`paypal_fee`) as paypal_fee,sum(`total`) as total,sum(`selling_price`) as selling_price,sum(`number`) as number"))->whereIn('id',$this->ids)->first();

        $orders = Order::whereIn('orders.id',$this->ids)->orderBy('id','desc')->get(['created_at'])->toArray();

        $order_goods_list = Order::join('order_goods','order_goods.order_id','=','orders.id')
            ->whereIn('orders.id',$this->ids)
            ->orderBy('order_goods.supplier_id','asc')
            ->orderBy('order_goods.id','asc')
            ->get(['order_goods.goods_name','order_goods.attribute_value','order_goods.purchase_price','order_goods.selling_price','order_goods.number','orders.salesman_en_name','orders.customer_name']);
        $customer_names = implode('、',array_unique(array_column($order_goods_list->toArray(),'customer_name')));
        $salesman_en_names = implode('、',array_unique(array_column($order_goods_list->toArray(),'salesman_en_name')));
        $count = $order_goods_list->count();

        $this->count = $count + 3;
        $order_data = [
            [$title],
            ['To:'.$customer_names,'Date：'.date('m/d',strtotime($orders[0]['created_at'])),'','Sales:'.$salesman_en_names,''],
            ['Item','Length','PCS','The Unit Price','Total']
        ];
        $order_goods_data = [];
        $i = $sn = 0;
        foreach ($order_goods_list as $key => $order_goods)
        {
            $sn++;
            $order_goods_data[$i] = [
                $order_goods->goods_name,$order_goods->attribute_value,$order_goods->number,'$'.$order_goods->selling_price,'$'.($order_goods->selling_price * $order_goods->number)
            ];
            $i++;

        }
        $order_goods_data[$i] = [
            [''],
            [''],
            ['Total P/P of hair','',$orders_statistics->number,'','$'.$orders_statistics->selling_price],
            ['Shipping fee:','','$'.$orders_statistics->freight,'Paypal fee:','$'.$orders_statistics->paypal_fee],
            ['Total:','','','$'.$orders_statistics->total]
        ];
        $data = array_merge($order_data,$order_goods_data);
        return  new Collection($data);

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {

                //设置列宽
                $columns = ['B','C','D','E'];
                foreach ($columns as $key => $column)
                {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(15);
                }
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(30);
                //设置行高，$i为数据行数
                for ($i = 0; $i<=$this->count+5; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(30);
                }
                //设置区域单元格垂直居中
                $event->sheet->getDelegate()->getStyle('A1:E'.($this->count+5))->getAlignment()->setVertical('center');

                $event->sheet->getDelegate()->getStyle('A1:E'.($this->count+5))->getAlignment()->setHorizontal('center');

               // $event->sheet->getDelegate()->getStyle('A4:H4')->getAlignment()->setHorizontal('left');

                //$event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(50);

                $event->sheet->getDelegate()->getStyle('A1:H1')->getFont()->setSize('18');
                $event->sheet->getDelegate()->getStyle('A2:H2')->getFont()->setSize('14');
                $event->sheet->getDelegate()->getStyle('A3:H3')->getFont()->setSize('14');
                $event->sheet->getDelegate()->getStyle('A'.($this->count+3).':B'.($this->count+3))->getFont()->setSize('16');
                $event->sheet->getDelegate()->getStyle('A'.($this->count+4).':B'.($this->count+4))->getFont()->setSize('16');
                $event->sheet->getDelegate()->getStyle('D'.($this->count+4).':D'.($this->count+4))->getFont()->setSize('16');
                $event->sheet->getDelegate()->getStyle('A'.($this->count+5).':E'.($this->count+5))->getFont()->setSize('16');
                //设置区域单元格字体、颜色、背景等，其他设置请查看 applyFromArray 方法，提供了注释

                $event->sheet->getDelegate()->getStyle('A3:E3')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                    'fill' => [
                        'fillType' => 'linear', //线性填充，类似渐变
                        'rotation' => 45, //渐变角度
                        'startColor' => [
                            'rgb' => '1DAAE4' //初始颜色
                        ],
                        //结束颜色，如果需要单一背景色，请和初始颜色保持一致
                        'endColor' => [
                            'argb' => '1DAAE4'
                        ]
                    ]
                ]);

                $event->sheet->getDelegate()->getStyle('A1:E'.($this->count+5))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                ]);


                $event->sheet->getDelegate()->mergeCells('A1:E1');
                $event->sheet->getDelegate()->mergeCells('B2:C2');
                $event->sheet->getDelegate()->mergeCells('D2:E2');
                $event->sheet->getDelegate()->mergeCells('A'.($this->count+3).':B'.($this->count+3));
                $event->sheet->getDelegate()->mergeCells('A'.($this->count+4).':B'.($this->count+4));
                $event->sheet->getDelegate()->mergeCells('A'.($this->count+5).':C'.($this->count+5));
                $event->sheet->getDelegate()->mergeCells('D'.($this->count+5).':E'.($this->count+5));
            }
        ];
    }
}