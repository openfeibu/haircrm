<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\MediaFolderRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class MediaFolderRepository extends BaseRepository implements MediaFolderRepositoryInterface
{
    public function model()
    {
        return config('model.media.media_folder.model');
    }
    public function folders($parent_id=0)
    {
        return $this->where('parent_id',$parent_id)->orderBy('name','asc')->get();
    }
    public function parent_folders_nav($fold,$html="")
    {
        $parent_folder = $this->where('id',$fold->parent_id)->first();

        if($parent_folder)
        {
            $html =  '<span lay-separator="">/</span>'. '<a href="'.route('media.index').'?folder_id='.$parent_folder->id.'">'.$parent_folder->name.'</a>' . $html;

            return $this->parent_folders_nav($parent_folder,$html);
        }

        return $html;
    }
}