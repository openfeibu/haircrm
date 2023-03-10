<?php

return [

    /*
     * Modules .
     */
    'modules'  => ['carry'],


    /*
     * Views for the page  .
     */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

    // Modale variables for page module.

    'carry'     => [
        'model'        => 'App\Models\Carry',
        'table'        => 'carries',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name', 'sign', 'paypal', 'onbuy', 'tracking_url', 'status'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],

    'carry_product'     => [
        'model'        => 'App\Models\Onbuy\CarryProduct',
        'table'        => 'carry_products',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['carry_id','name','code','type'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],

    'carry_product_price'     => [
        'model'        => 'App\Models\Onbuy\CarryProductPrice',
        'table'        => 'carry_product_prices',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['carry_id',  'carry_product_id',  'first_weight',  'additional_weight',  'first_weight_shipping',  'additional_weight_shipping',  'registered_fee',  'max_weight',  'country_code',  'country'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],
    'product_bid_task'     => [
        'model'        => 'App\Models\Onbuy\ProductBidTask',
        'table'        => 'onbuy_product_bid_tasks',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['bid_id', 'sku','seller_id'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],

    'order'     => [
        'model'        => 'App\Models\Onbuy\Order',
        'table'        => 'onbuy_orders',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['order_id', 'onbuy_internal_reference','date','updated_at','cancelled_at','shipped_at','status','site_id','site_name','price_subtotal','price_delivery','price_total','price_discount','sales_fee_ex_VAT','sales_fee_inc_VAT','currency_code','dispatched','delivery_service','stripe_transaction_id','paypal_capture_id','buyer_name','buyer_email','buyer_phone','buyer_ip_address','billing_address','delivery_address','fee_boost_marketing_fee_excluding_vat','fee_category_fee_excluding_vat','fee_delivery_fee_excluding_vat','fee_total_fee_excluding_vat','fee_vat_rate','fee_total_fee_including_vat','tax_total','tax_subtotal','tax_delivery','delivery_tag','tracking_number','tracking_supplier_name','tracking_url','is_refund','seller_id','shipping_fee'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
        'status' => ['Awaiting Dispatch','Dispatched','Refunded','Complete','Cancelled','Cancelled By Seller','Cancelled By Buyer','Partially Dispatched','Partially Refunded'],
    ],

    'order_product'     => [
        'model'        => 'App\Models\Onbuy\OrderProduct',
        'table'        => 'onbuy_order_products',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['order_id', 'onbuy_internal_reference','name','sku','seller_delivery_template_id','price_delivery_total','condition','condition_id','quantity','quantity_dispatched','unit_price','total_price','expected_dispatch_date','expected_delivery_date','file_location_prefix','opc','image_urls','tax_delivery','tax_product','tax_total','commission_fee','commission_fee_including_tax','tracking_number','tracking_supplier_name','tracking_url','seller_id'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],
    'seller_product'=> [
        'model'        => 'App\Models\Onbuy\SellerProduct',
        'table'        => 'onbuy_seller_product',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['seller_id', 'product_sku','created_at','updated_at'],
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
