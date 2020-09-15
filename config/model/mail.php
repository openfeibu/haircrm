<?php

return [

    /*
     * Modules .
     */
    'modules'  => ['mail_schedule'],


    /*
     * Views for the page  .
     */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

    'mail_schedule'     => [
        'model'        => 'App\Models\MailSchedule',
        'table'        => 'mail_schedules',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['admin_id','admin_model','title','interval','per_hour_mail','mail_count','send_count','success_count','failed_count','active','last_at','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
        ],
        'interval' => 240,
        'per_hour_mail' => 20
    ],
    'mail_schedule_report'     => [
        'model'        => 'App\Models\MailScheduleReport',
        'table'        => 'mail_schedule_reports',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['mail_template_id','mail_account_id','mail_template_name','mail_account_username','name','email','mail_schedule_id','status','sent','mail_return','send_at'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => [],
        'perPage'      => '20',
        'search'        => [
            'status'
        ],
        'status' => [
            'waiting' ,
            'sending' ,
            'success',
            'failed' ,
            'read' ,
        ],
    ],
    'mail_template'     => [
        'model'        => 'App\Models\MailTemplate',
        'table'        => 'mail_templates',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['admin_id','admin_model','salesman_id','name','subject','content','active','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
        ],
    ],
    'mail_account'     => [
        'model'        => 'App\Models\MailAccount',
        'table'        => 'mail_accounts',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['admin_id','admin_model','salesman_id','driver','host','port','from_address','username','password','address','name','from_name','encryption'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
        ],
        'from_name' => 'Feibu Hair',
        'host' => [
            'smtp.ym.163.com',
            'smtp.qiye.aliyun.com',
            'smtp.163.com',
            'smtp.qq.com',
        ],
        'port' => [
            '994',
            '465'
        ],
    ],
    'mail_schedule_mail_account'     => [
        'model'        => 'App\Models\MailScheduleMailAccount',
        'table'        => 'mail_schedule_mail_account',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['id','mail_schedule_id','mail_account_id','send_count'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
        ],
    ],
    'mail_schedule_mail_template'     => [
        'model'        => 'App\Models\MailScheduleMailTemplate',
        'table'        => 'mail_schedule_mail_template',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['id','mail_schedule_id','mail_template_id','send_count'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
        ],
    ],
];
