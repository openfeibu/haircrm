<?php

namespace App\Exports;

use App\Models\AdminUser;
use App\Models\Category;
use Auth;
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

class PurchaseOrderExport implements FromCollection,WithEvents
{

    use RegistersEventListeners;

    public $count = 0;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function collection()
    {

        $title = '采购计划表';

        $orders = Order::whereIn('orders.id',$this->ids)->orderBy('id','desc')->get(['created_at'])->toArray();

        $order_goods_list = Order::join('order_goods','order_goods.order_id','=','orders.id')
            //->join('suppliers','suppliers.id','=','order_goods.supplier_id')
            ->whereIn('orders.id',$this->ids)
            ->orderBy('order_goods.supplier_code','asc')
            ->get(['order_goods.supplier_name','order_goods.supplier_code','order_goods.goods_name','order_goods.attribute_value','order_goods.purchase_price','order_goods.selling_price','order_goods.number','order_goods.remark']);

        $count = $order_goods_list->count();

        $sum_purchase_price = Order::whereIn('id',$this->ids)->sum('purchase_price');

        $this->count = $count + 3;
        $order_data = [
            [$title],
            ['部门：跨境电商','','','采购时间：',date('Y/m/d',strtotime($orders[0]['created_at'])),'A','总金额：',$sum_purchase_price],
            ['供应链','序号','采购项目(品目)名称','采购数量','尺寸','金额','总金额','备注'],
        ];
        $order_goods_data = [];
        $i = $sn = 0;
        $supplier_field = 'supplier_code';
//        if(Auth::user() instanceof AdminUser)
//        {
//            $supplier_field = 'supplier_name';
//        }

        foreach ($order_goods_list as $key => $order_goods)
        {
            $sn++;
            $goods_name = strtolower($order_goods->goods_name);
            foreach (trans('category.categories') as $category_en => $category_ch)
            {
                if(strpos($goods_name,$category_en) !== false)
                {
                    $goods_name = substr_replace($goods_name,$category_ch,strpos($goods_name,$category_en),strlen($category_en));
                    //$goods_name = str_replace($category_en,$category_ch,$goods_name);
                }
            }
            $order_goods_data[$i] = [
                $order_goods->{$supplier_field},$sn,$goods_name,$order_goods->number,$order_goods->attribute_value,$order_goods->purchase_price,$order_goods->purchase_price * $order_goods->number, $order_goods->remark
            ];
            $i++;
        }

        $data = array_merge($order_data,$order_goods_data);
        return  new Collection($data);

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {

                //设置列宽
                $columns = ['A','B','D','E','F','G','H'];
                foreach ($columns as $key => $column)
                {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(15);
                }
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
                //设置行高，$i为数据行数
                for ($i = 0; $i<=$this->count; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(30);
                }
                //设置区域单元格垂直居中
                $event->sheet->getDelegate()->getStyle('A1:H'.$this->count)->getAlignment()->setVertical('center');

                $event->sheet->getDelegate()->getStyle('A1:H'.$this->count)->getAlignment()->setHorizontal('center');

               // $event->sheet->getDelegate()->getStyle('A4:H4')->getAlignment()->setHorizontal('left');

                //$event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(50);

                $event->sheet->getDelegate()->getStyle('A1:H1')->getFont()->setSize('18');
                $event->sheet->getDelegate()->getStyle('A2:H2')->getFont()->setSize('14');
                $event->sheet->getDelegate()->getStyle('A3:H3')->getFont()->setSize('14');
                //设置区域单元格字体、颜色、背景等，其他设置请查看 applyFromArray 方法，提供了注释

                $event->sheet->getDelegate()->getStyle('A3:H3')->applyFromArray([
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

//                $event->sheet->getDelegate()->getStyle('A1:G4')->applyFromArray([
//                    'borders' => [
//                        'allBorders' => [
//                            'borderStyle' => 'thin',
//                        ],
//                    ],
//                ]);


                $event->sheet->getDelegate()->mergeCells('A1:H1');
                $event->sheet->getDelegate()->mergeCells('A2:B2');
            }
        ];
    }
}