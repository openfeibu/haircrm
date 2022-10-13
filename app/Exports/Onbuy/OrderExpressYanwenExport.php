<?php

namespace App\Exports\Onbuy;

use App\Models\Onbuy\Order as OnbuyOrderModel;
use App\Models\Onbuy\OrderProduct as OnbuyOrderProductModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class OrderExpressYanwenExport implements FromCollection,WithEvents
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

        $title = '燕文物流';

        $orders = OnbuyOrderModel::when($this->ids ,function ($query) {
                return $query->whereIn('id',$this->ids);
            })->orderBy('date','desc')->get();
        $this->count = $orders->count();

        $header_data = [
            ['订单号','产品名称','收件人姓名','收件人电话','收件人邮箱','收件人税号','收件人公司','收件人国家','收件人省/州','收件人城市','收件人邮编','收件人地址','收件人门牌号','寄件人税号信息','包装尺寸【长】cm','包装尺寸【宽】cm','包装尺寸【高】cm','收款到账日期','币种类型','是否含电','拣货单信息','IOSS税号','中文品名1','英文品名1','单票数量1','重量1(g)','申报价值1','商品材质1','商品海关编码1','商品链接1','中文品名2','英文品名2','单票数量2','重量2(g)','申报价值2','商品材质2','商品海关编码2','商品链接2','中文品名3','英文品名3','单票数量3','重量3(g)','申报价值3','商品材质3','商品海关编码3','商品链接3','中文品名4','英文品名4','单票数量4','重量4(g)','申报价值4','商品材质4','商品海关编码4','商品链接4','中文品名5','英文品名5','单票数量5','重量5(g)','申报价值5','商品材质5','商品海关编码5','商品链接5'
            ]
        ];
        $order_data = [];
        $i = $sn = 0;

        foreach ($orders as $key => $order)
        {
            $products =  OnbuyOrderProductModel::join('onbuy_products','onbuy_products.sku','=','onbuy_order_products.sku')->where('onbuy_order_products.order_id',$order->order_id)->get(['onbuy_order_products.quantity','onbuy_products.product_url','onbuy_products.en_name','onbuy_products.ch_name','onbuy_products.weight','onbuy_products.min_price']);

            $sn++;
            $address = $order->delivery_address['line_1'];
            if($order->delivery_address['line_2'])
            {
                $address.= ' '.$order->delivery_address['line_2'];
            }
            if($order->delivery_address['line_3'])
            {
                $address.= ' '.$order->delivery_address['line_3'];
            }
            $date = date('m/d/Y',strtotime($order->date));
            $order_data[$i] = [
                $order->order_id,'燕文专线惠选-普货',$order->buyer_name,$order->buyer_phone,$order->buyer_email,"","",$order->delivery_address['country_code'],$order->delivery_address['county'],$order->delivery_address['town'],$order->delivery_address['postcode'],$address,"","","","","",$date,"美元","否","",""
            ];
            foreach ($products as $p_key => $product)
            {
                $data = [$product->ch_name, $product->en_name, $product->quantity, $product->weight,$product->min_price,"","",""];
                $order_data[$i] = array_merge($order_data[$i],$data);
            }
            $i++;

        }
        $data = array_merge($header_data,$order_data);
        return  new Collection($data);

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {

                //设置列宽
                //$columns = ['A','B','C','D','E','F','G','H','I','J','K'];
                $columns = excel_column_out_arr(Coordinate::columnIndexFromString("BJ"));
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