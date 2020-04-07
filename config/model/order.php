<?php

return [

    /*
     * Modules .
     */
    'modules'  => ['order','order_goods'],


    /*
     * Views for the page  .
     */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'order'     => [
        'model'        => 'App\Models\Order',
        'table'        => 'orders',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['order_sn','customer_id','customer_name','address','salesman_id','salesman_name','purchase_price','selling_price','number','order_status','shipping_status','pay_status','payment_id','payment_name','payment_sn','tracking_number'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'order_sn'  => 'like',
            'customer_id' => '=',
            'customer_name' => 'like',
            'salesman_id' => '=',
            'salesman_name' => 'like'
        ],
        'btn_class' => [
            'order_status' => [
                'unconfirmed' => 'layui-btn-primary',
                'confirmed' => 'layui-btn-normal',
                'cancelled' => 'layui-btn-warm',
                'returned' => 'layui-btn-danger',
            ],
            'shipping_status' => [
                'unshipped' => 'layui-btn-primary',
                'shipped' => 'layui-btn-normal',
                'received' => 'layui-btn-normal',
                'returned' => 'layui-btn-danger',
            ],
            'pay_status' => [
                'unpaid' => 'layui-btn-primary',
                'paid' => 'layui-btn-normal',
                'refunded' => 'layui-btn-danger',
            ],
        ]
    ],
    'order_goods'     => [
        'model'        => 'App\Models\OrderGoods',
        'table'        => 'order_goods',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['order_id','order_sn','goods_id','goods_name', 'attribute_value_id','goods_attribute_value_id','attribute_value','supplier_id','supplier_name', 'purchase_price','selling_price','number','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['goods_id'],
        'perPage'      => '20',
        'search'        => [
        ],
    ],
];
