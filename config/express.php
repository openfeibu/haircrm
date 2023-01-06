<?php
/**
 * 查询 paypal 对应快递字段 https://developer.paypal.com/docs/tracking/reference/carriers/
 *
 */
return [

    'yanwen' => [
        'excel' => [
            '订单号' =>  'order_id',
            '运单号' => 'tracking_number',
            '查询链接'=>'tracking_url',
            '快递公司' => 'tracking_supplier_name',
        ],
        'shipping_excel' => [
            '订单号' =>  'order_id',
            '账单金额(元)'=>  'shipping_fee'
        ],
        'tracking_url' => 'https://track.yw56.com.cn/en/querydel',
        'tracking_supplier_name' => [
            'onbuy' => 'Unknown',
            'paypal' => 'YANWEN_CN'
        ],

    ],
    '4px' => [
        'excel' => [
            '客户单号' =>  'order_id',
            '4px单号' => 'tracking_number',
            '查询链接' => 'tracking_url',
            '快递公司' => 'tracking_supplier_name',
        ],
        'shipping_excel' => [
            '客户单号' =>  'order_id',
            '总金额'=>  'shipping_fee'
        ],
        'tracking_url' => 'https://track.4px.com/#/result/0/%s',
        'tracking_supplier_name' => [
            'onbuy' => '4PX',
            'paypal' => 'FOUR_PX_EXPRESS'
        ],

    ],
    'cne' => [
        'excel' => [
            '客户单号' =>  'order_id',
            '4px单号' => 'tracking_number',
            '查询链接' => 'tracking_url',
            '快递公司' => 'tracking_supplier_name',
        ],
        'shipping_excel' => [
            '客户单号' =>  'order_id',
            '总金额'=>  'shipping_fee'
        ],
        'tracking_url' => 'https://www.cne.com/English/?no=%s',
        'tracking_supplier_name' => [
            'onbuy' => 'Unknown',
            'paypal' => 'CNEXPS'
        ],

    ],
];
