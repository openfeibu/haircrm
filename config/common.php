<?php

return [
    'img_type' => [
        'jpeg','jpg','gif','gpeg','png'
    ],
    'file_type' => [
        'xlsx','xls','csv','txt','doc','dos','pdf','docx','rar','zip'
    ],
    'img_size' => 1024 * 1024 * 10,
    'file_size' => 1024 * 1024 * 10,
    'default_avatar' => '/system/avatar.jpeg',
    'auth_file' => '/system/auth_file.jpeg',
    'qq_map_key' => env('QQ_MAP_WEB_KEY'),
    'uploads' => [
        'storage' => 'local',
        'path' => '/uploads',
    ],
    'fedex_url' => 'https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=%s&cntry_code=cn&locale=en_CN',
    'paypal_url' => 'https://www.paypal.com/activity/payment/%s',
    'overseas_email_suffix' => ['@hotmail.com','@msn.com','@yahoo.com','@gmail.com','@aim.com','@aol.com','@mail.com','@walla.com','@inbox.com']
];
