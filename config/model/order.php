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
        'fillable'     => ['order_sn','customer_id','customer_name','address','salesman_id','salesman_name','purchase_price','selling_price','number'],
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
            'title'  => 'goods_id',
        ],
    ],
];
