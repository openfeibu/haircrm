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
        'fillable'     => ['name','ch_name', 'en_name', 'sku','group_sku',  'price','stock','product_listing_id','product_listing_condition_id','condition','handling_time','boost_marketing_commission','original_price','min_price','purchase_price','weight','product_encoded_id','delivery_weight','delivery_template_id','opc','product_url','image_url','sale_price','out_inventory','inventory','purchase_url','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],

    'product_bid'     => [
        'model'        => 'App\Models\Onbuy\ProductBid',
        'table'        => 'onbuy_product_bid',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['start_date', 'end_date','start_time',  'end_time','everyday','active','created_at','updated_at'],
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
        'fillable'     => ['bid_id', 'sku'],
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
        'fillable'     => ['order_id', 'onbuy_internal_reference','date','updated_at','cancelled_at','shipped_at','status','site_id','site_name','price_subtotal','price_delivery','price_total','price_discount','sales_fee_ex_VAT','sales_fee_inc_VAT','currency_code','dispatched','delivery_service','stripe_transaction_id','paypal_capture_id','buyer_name','buyer_email','buyer_phone','buyer_ip_address','billing_address','delivery_address','fee_boost_marketing_fee_excluding_vat','fee_category_fee_excluding_vat','fee_delivery_fee_excluding_vat','fee_total_fee_excluding_vat','fee_vat_rate','fee_total_fee_including_vat','tax_total','tax_subtotal','tax_delivery','delivery_tag','tracking_number','tracking_supplier_name','tracking_url'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
        'status' => ['Awaiting Dispatch','Dispatched','Refunded','Complete','Cancelled','Cancelled By Seller','Cancelled By Buyer','Partially Dispatched','Partially Refunded'],
        'excel' => [
            '订单号' =>  'order_id',
            '运单号' => 'tracking_number',
            '查询链接'=>'tracking_url',
            '快递公司' => 'tracking_supplier_name',
        ],
    ],

    'order_product'     => [
        'model'        => 'App\Models\Onbuy\OrderProduct',
        'table'        => 'onbuy_order_products',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['order_id', 'onbuy_internal_reference','name','sku','seller_delivery_template_id','price_delivery_total','condition','condition_id','quantity','quantity_dispatched','unit_price','total_price','expected_dispatch_date','expected_delivery_date','file_location_prefix','opc','image_urls','tax_delivery','tax_product','tax_total','commission_fee','commission_fee_including_tax','tracking_number','tracking_supplier_name','tracking_url'],
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
