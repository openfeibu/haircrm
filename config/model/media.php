<?php

return [

/*
 * Modules .
 */
    'modules'  => ['media','media_fold'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'media'     => [
        'model'        => 'App\Models\Media',
        'table'        => 'media',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['media_folder_id','name','description','url','path','mediaable_id','mediaable_type'],
        'translate'    => [],
        'upload_folder' => '/media',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'title'  => 'name',
        ],
    ],
    'media_folder'     => [
        'model'        => 'App\Models\MediaFolder',
        'table'        => 'media_folders',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['parent_id','name','path','url','description','type','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/media',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'title'  => 'name',
        ],
    ],
];
