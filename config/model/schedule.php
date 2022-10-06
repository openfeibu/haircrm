<?php

return [

    /*
     * Package.
     */
    'package'   => 'schedule',

    /*
     * Modules.
     */
    'modules'   => ['schedule'],

    'schedule'     => [
        'model'        => 'App\Models\Schedule',
        'table'        => 'schedule',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name', 'date' ,'success'],
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
