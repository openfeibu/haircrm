<?php

return [

    /*
     * Modules .
     */
    'modules'  => ['goods','goods_attribute_value'],


    /*
     * Views for the page  .
     */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'goods'     => [
        'model'        => 'App\Models\Goods',
        'table'        => 'goods',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name', 'category_id','attribute_id',  'purchase_price','selling_price','category_ids','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],
    'goods_attribute_value'     => [
        'model'        => 'App\Models\GoodsAttributeValue',
        'table'        => 'goods_attribute_value',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['goods_id', 'attribute_id','attribute_value_id', 'purchase_price','selling_price','created_at','updated_at'],
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
