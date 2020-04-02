<?php

return [

/*
 * Modules .
 */
    'modules'  => ['payment'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'payment'     => [
        'model'        => 'App\Models\Payment',
        'table'        => 'payments',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name'],
        'translate'    => ['name'],
        'upload_folder' => '/payment',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],

];
