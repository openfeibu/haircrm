<?php

return [

    /*
     * Package.
     */
    'package'   => 'salesman_roles',

    /*
     * Modules.
     */
    'modules'   => ['salesman_role', 'salesman_permission'],

    'salesman_role'       => [
        'model' => [
            'model'                 => \App\Models\SalesmanRole::class,
            'table'                 => 'salesman_roles',
            //'presenter'             => \Litepie\Roles\Repositories\Presenter\RoleItemPresenter::class,
            'hidden'                => [],
            'visible'               => [],
            'guarded'               => ['*'],
            'slugs'                 => ['slug' => 'name'],
            'dates'                 => ['deleted_at'],
            'appends'               => [],
            'fillable'              => ['name',  'slug',  'description',  'level'],
            'translatables'         => [],
            'upload_folder'         => 'roles/role',
            'uploads'               => [],
            'casts'                 => [],
            'revision'              => [],
            'perPage'               => '20',
            'search'        => [
                'name'  => 'like',
                'status',
            ]
        ],

        'controller' => [
            'provider'  => 'Litepie',
            'package'   => 'Roles',
            'module'    => 'Role',
        ],

    ],

    'salesman_permission'       => [
        'model' => [
            'model'                 => \App\Models\SalesmanPermission::class,
            'table'                 => 'salesman_permissions',
           // 'presenter'             => \Litepie\Roles\Repositories\Presenter\PermissionItemPresenter::class,
            'hidden'                => [],
            'visible'               => [],
            'guarded'               => ['*'],
            'slugs'                 => ['slug' => 'name'],
            'dates'                 => ['deleted_at'],
            'appends'               => [],
            'fillable'              => ['parent_id', 'name', 'slug', 'icon', 'is_menu',  'description', 'order'],
            'translatables'         => [],
            'upload_folder'         => 'roles/permission',
            'uploads'               => [],
            'casts'                 => [],
            'revision'              => [],
            'perPage'               => '20',
            'search'        => [
                'name'  => 'like',
                'status',
            ]
        ],

        'controller' => [
            'provider'  => 'Litepie',
            'package'   => 'Roles',
            'module'    => 'Permission',
        ],

    ],
    /*
    |--------------------------------------------------------------------------
    | Slug Separator
    |--------------------------------------------------------------------------
    |
    | Here you can change the slug separator. This is very important in matter
    | of magic method __call() and also a `Slugable` trait. The default value
    | is a dot.
    |
     */
    'separator'  => '.',

    /*
    |--------------------------------------------------------------------------
    | Roles, Permissions and Allowed "Pretend"
    |--------------------------------------------------------------------------
    |
    | You can pretend or simulate package behavior no matter what is in your
    | database. It is really useful when you are testing you application.
    | Set up what will methods is(), can() and allowed() return.
    |
     */
    'pretend'    => [
        'enabled' => false,
        'options' => [
            'is'      => true,
            'can'     => true,
            'allowed' => true,
        ],
    ],

];
