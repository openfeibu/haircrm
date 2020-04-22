<?php

return [

/*
 * Modules .
 */
    'modules'  => ['freight','freight_category'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'freight'     => [
        'model'        => 'App\Models\Freight',
        'table'        => 'freight',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['freight_category_id','freight_area_id','freight_area_code','first_freight','continued_freight'],
        'translate'    => [],
        'upload_folder' => '/freight',
        'encrypt'      => ['id'],
        'revision'     => [],
        'perPage'      => '20',
        'search'        => [
            'freight_area_code'  => 'like',

        ],
    ],
    'freight_category'     => [
        'model'        => 'App\Models\FreightCategory',
        'table'        => 'freight_categories',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['id','name'],
        'translate'    => [],
        'upload_folder' => '/freight_category',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],
    'freight_area'     => [
        'model'        => 'App\Models\FreightArea',
        'table'        => 'freight_areas',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['id','name'],
        'translate'    => [],
        'upload_folder' => '/freight_category',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],
];
