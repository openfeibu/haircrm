<?php

return [

    /*
     * Modules .
     */
    'modules'  => ['category'],


    /*
     * Views for the page  .
     */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'category'     => [
        'model'        => 'App\Models\Category',
        'table'        => 'categories',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name', 'parent_id', 'top_parent_id','category_ids' ,'supplier_id','attribute_id','order','weight'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'title'  => 'name',
        ],
    ],

];
