<?php

return [

    /*
     * Modules .
     */
    'modules'  => ['product'],


    /*
     * Views for the page  .
     */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'product'     => [
        'model'        => 'App\Models\Onbuy\Product',
        'table'        => 'onbuy_products',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name', 'sku','group_sku',  'price','stock','product_listing_id','product_listing_condition_id','condition','handling_time','boost_marketing_commission','original_price','min_price','purchase_price','weight','product_encoded_id','delivery_weight','delivery_template_id','opc','product_url','image_url','sale_price','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],

];
