<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Language files for Menus Module
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default for menus module,
    | and it is used by the /view files in this module
    |
     */
    'name'        => '邮箱发送记录',
    'names'       => '邮箱发送记录',
    'title' => '邮箱发送记录管理',
    'label'       => [
        'mail_template_name'   => '模板名称',
        'mail_account_username' => '发送邮箱账号',
        'email'         => '目标邮箱',
        'status'        => '状态',
        'mail_return' => '返回',
        'send_at' => '发送时间',
        'sent' => '已发送',
    ],
    'status' => [
        'waiting' => '等待中',
        'sending' => '发送中',
        'success' => '成功',
        'failed' => '失败',
        'read' => '已读',
    ],
];
