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

class OrderExpressHualeiExport implements FromCollection,WithEvents
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

        $orders = OnbuyOrderModel::when($this->ids ,function ($query) {
                return $query->whereIn('id',$this->ids);
            })->orderBy('date','desc')->get();
        $this->count = $orders->count();

        $header_data = [
            ['客户单号','转单号','运输方式','目的国家','寄件人公司名','寄件人姓名','寄件人地址','寄件人电话','寄件人邮编','寄件人传真','收件人公司名','收件人姓名','州,省','城市','联系地址','收件人电话','收件人邮箱','收件人邮编','收件人传真','订单备注','重量','海关报关品名1','配货信息1','申报价值1','申报品数量1','配货备注1','海关报关品名2','配货信息2','申报价值2','申报品数量2','配货备注2','海关报关品名3','配货信息3','申报价值3','申报品数量3','配货备注3','海关报关品名4','配货信息4','申报价值4','申报品数量4','配货备注4','海关报关品名5','配货信息5','申报价值5','申报品数量5','配货备注5'
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
                $address.= ', '.$order->delivery_address['line_2'];
            }
            if($order->delivery_address['line_3'])
            {
                $address.= ', '.$order->delivery_address['line_3'];
            }
            $date = date('m/d/Y',strtotime($order->date));
            $order_data[$i] = [
	            $order->order_id,'','专线E速小包-敏感',$order->delivery_address['country_code'],'','','','','','','',$order->buyer_name,$order->delivery_address['county'],$order->delivery_address['town'],$address,$order->buyer_phone,$order->buyer_email,$order->delivery_address['postcode'],'',''
            ];
	        $weight = 0;
	        $product_data = [];
	        
            foreach ($products as $p_key => $product)
            {
            	$weight += $product->weight;
                $data = [$product->en_name, $product->ch_name, $product->min_price, $product->quantity,""];
	            $product_data = array_merge($product_data,$data);
            }
	        $weight = $weight/1000;
	        $order_data[$i] = array_merge($order_data[$i],[$weight],$product_data);
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