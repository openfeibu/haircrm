<?php

return [
    'name'        => '订单',
    'names'       => '订单',
    'title' => '订单管理',
    'label'       => [
        'order_sn' => '订单号',
        'number' => '数量',
        'purchase_price' => '¥进货价',
        'selling_price' => '$出售价',
        'order_status' => '订单状态',
        'shipping_status' => '配送状态',
        'pay_status' => '付款状态',
        'payment_sn' => '支付单号',
        'tracking_number' => '运单号',
        'weight' => '重量KG',
        'freight' => '$运费',
        'paypal_fee' => '$PayPal',
        'total' => '$总出售价',
        'paid_total' => '$实际到账',
        'paid_at' => '支付时间',
        'shipped_at' => '派送时间',
        'address' => '收货手机和地址'
    ],
    'order_status' => [
        'unconfirmed' => '未确认',
        'confirmed' => '已确认',
        'cancelled' => '已取消',
        'returned' => '退货',
    ],
    'shipping_status' => [
        'unshipped' => '未发货',
        'shipped' => '已发货',
        'received' => '已送达',
        'returned' => '退货'
    ],
    'pay_status' => [
        'unpaid' => '未付款',
        'paid' => '已付款',
        'refunded' => '退款',
    ],
    'operation' => [
        'confirm' => '确认',
        'pay' => '付款',
        //'prepare' => '配货',
        'ship' => '发货',
        'cancel' => '取消',
        //'invalid' => '无效',
        'return' => '退货',
        'unpay' => '设为未付款',
        'unship' => '未发货',
        'cancel_ship' => '取消发货',
        'receive' => '收货',
        'to_delivery' => '发货',
    ],
];