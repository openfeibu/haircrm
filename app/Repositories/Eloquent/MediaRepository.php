<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\MediaRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Services\UploadsManagerService;

class MediaRepository extends BaseRepository implements MediaRepositoryInterface
{
    public function model()
    {
        return config('model.media.media.model');
    }
    public function media_list($folder_id)
    {
        return $this->where('media_folder_id',$folder_id)->orderBy('updated_at','desc')->get();
    }
    public function deleteMedia($url)
    {
        $media = $this->where('url',$url)->first();

        if(!$media)
        {
            return ;
            throw new DataNotFoundException();
        }

        app(UploadsManagerService::class)->deleteFile($media->url);

        $this->forceDelete([$media->id]);

    }
}