<?php

return [

/*
 * Modules .
 */
    'modules'  => ['salesman'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'salesman'     => [
        'model'        => 'App\Models\Salesman',
        'table'        => 'salesmen',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['email','name','en_name','entry_date','password', 'api_token', 'remember_token','active','order','status','ig','imessage','mobile','monthly_performance_target','yearly_performance_target','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/salesman',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
            'en_name' => 'like',
        ],
    ],

];
