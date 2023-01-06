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

class OrderExpressCneExport implements FromCollection,WithEvents
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
            ['订单号','网络渠道','平台','类型','件数','重量','长','宽','高','发件人税号','VAT税号','EORI税号','IOSS税号','收件人','收件单位','收件地址1','收件地址2','收件地址3','收件邮编','收件国家','收件省州','收件城市','收件邮箱','收件电话','收件短信','进口清关税号','物品中文描述','物品英文描述','物品数量','物品单价','币种','海关编码','SKU','标签','备注'
            ]
        ];
        $order_data = [];
        $i = $sn = 0;

        foreach ($orders as $key => $order)
        {
            $products =  OnbuyOrderProductModel::join('onbuy_products','onbuy_products.sku','=','onbuy_order_products.sku')->where('onbuy_order_products.order_id',$order->order_id)->get(['onbuy_order_products.quantity','onbuy_products.sku','onbuy_products.product_url','onbuy_products.en_name','onbuy_products.ch_name','onbuy_products.weight','onbuy_products.min_price']);

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
//                $order->order_id,'燕文专线惠选-普货',$order->buyer_name,$order->buyer_phone,$order->buyer_email,"","",$order->delivery_address['country_code'],$order->delivery_address['county'],$order->delivery_address['town'],$order->delivery_address['postcode'],$address,"","","","","",$date,"美元","否","",""
                $order->order_id/*订单号*/,'CNE全球快捷'/*网络渠道*/,'OTHER'/*平台*/,'包裹'/*类型*/,
            ];
            $total_weight = 0;
            $total_quantity = 0;
            $product_data = [];

            $ch_name = $en_name = $quantity = $price = $sku = $currency = '' ;
            $j=0;
            foreach ($products as $p_key => $product)
            {
                $total_weight += $product->weight * $product->quantity;
                $total_quantity += $product->quantity;
                $ch_name .= $ch_name ? PHP_EOL.$ch_name : $product->ch_name;
                $en_name .= $en_name ? PHP_EOL.$en_name : $product->en_name;
                $quantity .= $quantity ? PHP_EOL.$quantity : $product->quantity;
                $price .= $price ? PHP_EOL.$price : $product->min_price;
                $sku .= $sku ? PHP_EOL.$sku : $product->sku;
                $currency .= $currency ? PHP_EOL.'英镑' : '英镑';
//                $data = [$product->ch_name, $product->en_name, $product->quantity, $product->weight,$product->min_price,"","",""];
//                $order_data[$i] = array_merge($order_data[$i],$data);
                $j++;
            }
            $total_weight = $total_weight/1000;
            $order_data[$i] = array_merge($order_data[$i],[$total_quantity/*件数*/,$total_weight/*重量*/,''/*长*/,''/*宽*/,''/*高*/,''/*发件人税号*/,''/*VAT税号*/,''/*EORI税号*/,''/*IOSS税号*/,$order->buyer_name/*收件人*/,''/*收件单位*/,$address/*收件地址1*/,''/*收件地址2*/,''/*收件地址3*/,$order->delivery_address['postcode']/*收件邮编*/,$order->delivery_address['country_code']/*收件国家*/,$order->delivery_address['county']/*收件省州*/,$order->delivery_address['town']/*收件城市*/,$order->buyer_email/*收件邮箱*/,$order->buyer_phone/*收件电话*/,''/*收件短信*/,''/*进口清关税号*/,$ch_name/*物品中文描述*/,$en_name/*物品英文描述*/,$quantity/*物品数量*/,$price/*物品单价*/,$currency/*币种*/,''/*海关编码*/,$sku/*SKU*/,''/*标签*/,''/*备注*/]);
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
                $columns = excel_column_out_arr(Coordinate::columnIndexFromString("AI"));
                foreach ($columns as $key => $column)
                {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(15);
//                    for ($i = 0; $i<=$this->count+1; $i++) {
//                        $event->sheet->getDelegate()->getStyle($column . $i)->getAlignment()->setWrapText(true);
//                    }

                }
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
                //$event->sheet->getDelegate()->getColumnDimension('A')->setWidth(30);
                //设置行高，$i为数据行数
                for ($i = 0; $i<=$this->count+1; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(30);
                    $event->sheet->getDelegate()->getStyle("A" . $i.":AI".$i)->getAlignment()->setWrapText(true);
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