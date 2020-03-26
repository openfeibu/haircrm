<?php

return [

/*
 * Modules .
 */
    'modules'  => ['supplier'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'supplier'     => [
        'model'        => 'App\Models\Supplier',
        'table'        => 'suppliers',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name', 'code'],
        'translate'    => [],
        'upload_folder' => '/supplier',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],

];
