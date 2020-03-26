<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\OutputServerMessageException;
use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Media;
use App\Models\MediaFolder;
use App\Repositories\Eloquent\MediaFolderRepository;
use App\Repositories\Eloquent\MediaRepository;
use App\Services\UploadsManagerService;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Input;

class MediaResourceController extends BaseController
{
    public function __construct(UploadsManagerService $managerService,
                                ImageService $image,
                                MediaFolderRepository $mediaFolderRepository,
                                MediaRepository $mediaRepository)
    {
        $this->manager = $managerService;
        $this->mediaFolderRepository = $mediaFolderRepository;
        $this->mediaRepository = $mediaRepository;
        $this->image = $image;
        parent::__construct();

    }
    public function index(Request $request)
    {
        $folder_id = $request->get('folder_id',0);

        $folders = $this->mediaFolderRepository->folders($folder_id);

        $media_list = $this->mediaRepository->media_list($folder_id);

        $current_folder = [];
        $nav = '';
        if($folder_id)
        {
            $current_folder = $this->mediaFolderRepository->find($folder_id);
            $nav = $this->mediaFolderRepository->parent_folders_nav($current_folder,'<span lay-separator="">/</span>'. '<a href="'.route('media.index').'?folder_id='.$folder_id.'">'.$current_folder->name.'</a>');
        }

        return $this->response->title(trans('media.name'))
            ->data(compact('media_list','folders','current_folder','nav','folder_id'))
            ->view('media.index')
            ->output();
    }

    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $media = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('media.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('media/' . $media->id))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('media'))
                ->redirect();
        }
    }

    public function update(Request $request,Media $media)
    {
        try {
            $attributes = $request->all();
            $attributes['name'] = trim($attributes['name'],'/');

            if($attributes['name'] != $media->name)
            {
                $attributes['url'] = rtrim($media->path,'/').'/'.$attributes['name'];

                $this->manager->renameFile($media->url,$attributes['url']);
            }

            $media->update($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('media.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('media/' . $media->id))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('media/' . $media->id))
                ->redirect();
        }
    }
    public function destroy(Request $request)
    {
        try {
            $url = $request->get('url');

            $this->mediaRepository->deleteMedia($url);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('media.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('media'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('media'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('media.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('media'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('media'))
                ->redirect();
        }
    }
    public function folderDestroy(Request $request)
    {
        try {
            $url = $request->get('url');
            if($url ==  '/')
            {
                throw new OutputServerMessageException('禁止删除');
            }

            $media_folder = $this->mediaFolderRepository->where('url',$url)->first();

            $this->manager->deleteDirectory($media_folder->url);

            $this->mediaFolderRepository->forceDelete([$media_folder->id]);
            $this->mediaRepository->deleteWhere(['media_folder_id' => $media_folder->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('media.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('media'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('media'))
                ->redirect();
        }
    }
    public function folderStore(Request $request)
    {
        try {
            $attributes = $request->all();
            $attributes['parent_id'] = $attributes['folder_id'];
            if($attributes['folder_id'])
            {
                $folders = $this->mediaFolderRepository->find($attributes['folder_id']);
                $attributes['path'] = $folders->path.'/'.$attributes['name'];
            }else{
                $attributes['path'] = '/'.$attributes['name'];
            }
            $attributes['url'] = $attributes['path'];

            $result = $this->manager->createDirectory($attributes['path']);

            $media_folder = $this->mediaFolderRepository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('media.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('media'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('media'))
                ->redirect();
        }
    }
    public function folderUpdate(Request $request,MediaFolder $media_folder)
    {
        try {
            $attributes = $request->all();

            $attributes['name'] = trim(trim($attributes['name'],'/'));

            $parent_path = substr($media_folder->path,0,strrpos($media_folder->path,"/"));

            if($attributes['name'] != $media_folder->name)
            {

                $attributes['url'] = $attributes['path'] = $parent_path . '/' .$attributes['name'];

                $this->manager->renameFolder($media_folder->path,$attributes['path']);
            }

            $media_folder->update($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('media.name')]))
                ->status('success')
                ->url(guard_url('media/'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('media/'))
                ->redirect();
        }
    }
    public function upload(Request $request)
    {
        try {
            $folder_id = $request->input('folder_id',0);
            if($folder_id)
            {
                $folders = $this->mediaFolderRepository->find($folder_id);
                $ufolder = $folders->path;
            }else{
                $ufolder = '';
            }
            $array = $this->image->uploadImages(Input::all(),$ufolder);

            $url = $array['image_url'][0];

            $name = pathinfo($url, PATHINFO_BASENAME);
            $path = pathinfo($url, PATHINFO_DIRNAME);

            $data = [
                'media_folder_id' => $folder_id,
                'name' => $name,
                'path' => $path,
                'url' => $url
            ];
            $this->mediaRepository->create($data);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('media.name')]))
                ->status('success')
                ->url(guard_url('media/'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('media/'))
                ->redirect();
        }
    }
}
