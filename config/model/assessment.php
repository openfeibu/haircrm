<?php

return [

/*
 * Modules .
 */
    'modules'  => ['assessment'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'assessment'     => [
        'model'        => 'App\Models\Assessment',
        'table'        => 'assessments',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'slugs'        => ['slug' => 'name'],
        'fillable'     => ['name', 'slug', 'description', 'standard', 'proportion','lowest_completion_rate','type','order'],
        'translate'    => [],
        'upload_folder' => '/setting',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',

        ],
    ],

];
