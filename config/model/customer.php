<?php

return [

/*
 * Modules .
 */
    'modules'  => ['customer'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'customer'     => [
        'model'        => 'App\Models\Customer',
        'table'        => 'customers',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name','salesman_id','salesman_name','ig','from','email','mobile','imessage','whatsapp','address','order_count','created_at','updated_at','deleted_at'],
        'translate'    => [],
        'upload_folder' => '/customer',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
        'from' => [
            'ins','facebook','客户介绍','其他'
        ],
        'excel' => [
            '客户名称' =>  'name',
            'IG号' => 'ig',
            '客户来源'=>'from',
            '邮箱' => 'email',
            '手机号' => 'mobile',
            'imessage' => 'imessage',
            'whatsapp' => 'whatsapp',
            '地址' => 'address' ,
            '业务员' => 'salesman_name',
            '下单次数' => 'order_count',
        ],
    ],
    'new_customer'     => [
        'model'        => 'App\Models\NewCustomer',
        'table'        => 'new_customers',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['salesman_id','salesman_name','company_name','company_website','nickname','email','mobile','imessage','whatsapp','main_product','ig','ig_follower_count','ig_secondary','facebook','mark','created_at','updated_at','deleted_at'],
        'translate'    => [],
        'upload_folder' => '/new_customer',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'nickname'  => 'like',
        ],
        'excel' => [
            '公司名称' => 'company_name',
            '公司网址' => 'company_website',
            '联系人昵称' => 'nickname',
            '联系人邮箱' => 'email',
            '联系电话' => 'mobile',
            'Imassage' => 'imessage',
            'Whatsapp' => 'whatsapp',
            '主打产品' => 'main_product',
            'IG 号' => 'ig',
            'IG 粉丝' => 'ig_follower_count',
            'IG 2' => 'ig_sec',
            'Facebook' => 'facebook',
            '备注' => 'remark',
            '业务员' => 'salesman_name',
        ],
    ],
];
