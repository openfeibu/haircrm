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

class OrderExpressFourPxExport implements FromCollection,WithEvents
{

    use RegistersEventListeners;

    public $count = 0;
    public $express_type = [
        '联邮通经济挂号-普货(O5)',
        '联邮通经济SRM-带电(JY)',
        '联邮通标准挂号-普货(QC)',
        '联邮通经济挂号-带电(JW)',
        '联邮通标准挂号-带电(OH)',
    ];

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
//            ['订单号','产品名称','收件人姓名','收件人电话','收件人邮箱','收件人税号','收件人公司','收件人国家','收件人省/州','收件人城市','收件人邮编','收件人地址','收件人门牌号','寄件人税号信息','包装尺寸【长】cm','包装尺寸【宽】cm','包装尺寸【高】cm','收款到账日期','币种类型','是否含电','拣货单信息','IOSS税号','中文品名1','英文品名1','单票数量1','重量1(g)','申报价值1','商品材质1','商品海关编码1','商品链接1','中文品名2','英文品名2','单票数量2','重量2(g)','申报价值2','商品材质2','商品海关编码2','商品链接2','中文品名3','英文品名3','单票数量3','重量3(g)','申报价值3','商品材质3','商品海关编码3','商品链接3','中文品名4','英文品名4','单票数量4','重量4(g)','申报价值4','商品材质4','商品海关编码4','商品链接4','中文品名5','英文品名5','单票数量5','重量5(g)','申报价值5','商品材质5','商品海关编码5','商品链接5'
//            ]
            [
                '客户单号','服务商单号','运输方式','目的国家','发件人公司名','发件人姓名','发件人省名','发件人城市名','发件人地址','发件人电话','发件人邮编','发件人传真','收件人公司名','收件人姓名','收件人州 \ 省','收件人城市','收件人地址','收件人门牌号','收件人护照号码','收件人电话','收件人手机','收件人邮箱','收件人邮编','收件人传真','买家ID','VAT号码','交易ID','保险类型','保险价值','投保人','投保人身份证','货物名称','包装与数量','订单备注','重量','是否带电池','电池类型','是否需要签名服务','是否退件','是否单独报关','包裹种类','EORI号码','代收货款金额','代收货款币种','分销商编码','运费','IOSS号码','申报保险费','运费/申报保险费/商品申报币种',
                '海关报关品名1','海关报关品名(中)1','配货信息1','申报价值1','申报品URL1','申报品数量1','海关货物编号1','配货备注1','海关报关品名2','海关报关品名(中)2','配货信息2','申报价值2','申报品URL2','申报品数量2','海关货物编号2','配货备注2','海关报关品名3','海关报关品名(中)3','配货信息3','申报价值3','申报品URL3','申报品数量3','海关货物编号3','配货备注3','海关报关品名4','海关报关品名(中)4','配货信息4','申报价值4','申报品URL4','申报品数量4','海关货物编号4','配货备注4','海关报关品名5','海关报关品名(中)5','配货信息5','申报价值5','申报品URL5','申报品数量5','海关货物编号5','配货备注5'
            ]
        ];
        $order_data = [];
        $i = $sn = 0;

        foreach ($orders as $key => $order)
        {
            $products =  OnbuyOrderProductModel::join('onbuy_products','onbuy_products.sku','=','onbuy_order_products.sku')->where('onbuy_order_products.order_id',$order->order_id)->get(['onbuy_order_products.quantity','onbuy_products.product_URL','onbuy_products.en_name','onbuy_products.ch_name','onbuy_products.weight','onbuy_products.min_price']);

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
                $order->order_id,'','O5',$order->delivery_address['country_code'],'','ZHIJIE WU','GuangDong','GuangZhou','A601 FUAN GARDEN TIANHE DISTRICT GUANGZHOU CITY GUANGDONG PROVINCE','18820785637','510665','','',$order->buyer_name,$order->delivery_address['county'],$order->delivery_address['town'],$address,'','',$order->buyer_phone,$order->buyer_phone,$order->buyer_email,$order->delivery_address['postcode'],'','','','','','','','','','','',

            ];
            $weight = 0;
            $product_data = [];
            foreach ($products as $p_key => $product)
            {
                $weight += $product->weight;
                //'海关报关品名1','海关报关品名(中)1','配货信息1','申报价值1','申报品URL1','申报品数量1','海关货物编号1','配货备注1',
                $data = [$product->en_name, $product->ch_name,'',$product->min_price,'',$product->quantity,'',''];
                $product_data = array_merge($product_data,$data);
            }
            $weight = $weight/1000;
            $order_data[$i] = array_merge($order_data[$i],[$weight,'N','','','','','','','','','','','','','USD'],$product_data);
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
                $columns = excel_column_out_arr(Coordinate::columnIndexFromString("CJ"));
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
                /*
                //设置区域单元格垂直居中
                $event->sheet->getDelegate()->getStyle('A1:K'.($this->count+1))->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->getStyle('A1:K'.($this->count+1))->getAlignment()->setHorizontal('center');
                // $event->sheet->getDelegate()->getStyle('A4:H4')->getAlignment()->setHorizontal('left');

                //$event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(50);

                $event->sheet->getDelegate()->getStyle('A1:K1')->getFont()->setSize('16');
                $event->sheet->getDelegate()->getStyle('A2:K'.($this->count+1))->getFont()->setSize('15');
                */
            }
        ];
    }
}