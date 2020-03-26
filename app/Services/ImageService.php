<?php

namespace App\Services;

use App\Models\Media;
use App\Models\MediaFolder;
use Log;
use File;
use Storage;
use Illuminate\Http\Request;
use Route,Auth,Hash,Input,Image;
use Intervention\Image\ImageManager;

class ImageService
{
    protected $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 上传图片
     * 注意：用户必须是已登录状态
     *
     * @param  file $files  要上传的图片文件
     * @param  string $usage 图片的用途，并将上传的图片文件存放到public/uploads/$usage中
     *
     * @return array        图片链接
     */
    public function uploadImages($files, $usage,$is_thumb = 0)
    {
        if(is_array($files['file']))
        {
            $all_files = $files['file'];
        }
        else{
            $all_files[] = $files['file'];
        }
        isVaildImage($all_files);
        return $this->uploadImagesHandle($all_files,$usage,$is_thumb);
    }

    private function uploadImagesHandle($files, $usage='common',$is_thumb)
    {
        //如果文件夹不存在，则创建文件夹
        $directory = $usage;

        $url = '/'.rtrim(ltrim($usage,'/'),'/');
        $media_folder_id = MediaFolder::where('path',$url)->value('id');

        $thumb_url = $url.'/thumb';
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory, 0755, true);
            if($is_thumb)
            {
                $thumb_directory = $directory . DIRECTORY_SEPARATOR . 'thumb';
                Storage::makeDirectory($thumb_directory, 0755, true);
            }
        }

        //保存图片文件到服务器
        $i = 0;
        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();
            $image_name = date('YmdHis').rand(100000, 999999) . '.' . $extension;
            $img = $url.'/'.$image_name;

            Storage::put($img, file_get_contents($file->getRealPath()));

            $images_url[$i]['img_url'] = $usage.'/'.$image_name;
            $images_url[$i]['usage'] = $usage;
            $images_url[$i]['created_at'] = date("Y-m-d H:i:s");

            $imgs_url[$i] = $images_url[$i]['img_url'];
            $imgs_url_full[$i] = url('/image/original'.$imgs_url[$i] );
            if($is_thumb)
            {
                $thumb = $thumb_url.'/'.$image_name;
                $thumbs_url[$i] = $usage.'/thumb/'.$image_name;
                $thumbs_url_full[$i] = url('/image/original'.$thumbs_url[$i]);
                image_png_size_add(storage_path().$img,storage_path().$thumb);
            }
            if($media_folder_id)
            {
                Media::create([
                    'media_folder_id' => $media_folder_id,
                    'path' => $url,
                    'name' => $image_name,
                    'url' => $images_url[$i]['img_url']
                ]);
            }
            $i++;
        }

        if($is_thumb)
        {
            return [
                'image_url' => $imgs_url,
                'image_url_full' => $imgs_url_full,
                'thumb_url'=> $thumbs_url,
                'thumb_url_full'=> $thumbs_url_full,
            ];
        }
        return [
            'image_url' => $imgs_url,
            'image_url_full' => $imgs_url_full,
        ];

    }

    public function uploadFiles($files, $usage)
    {
        if(is_array($files['file']))
        {
            $all_files = $files['file'];
        }
        else{
            $all_files[] = $files['file'];
        }
        isVaildFile($all_files);
        return $this->uploadFilesHandle($all_files,$usage);
    }

    private function uploadFilesHandle($files, $usage='common')
    {
        //如果文件夹不存在，则创建文件夹
        $directory = $usage;

        $url = '/'.rtrim(ltrim($usage,'/'),'/');
        $media_folder_id = MediaFolder::where('path',$url)->value('id');

        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory, 0755, true);
        }

        //保存图片文件到服务器
        $i = 0;
        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();
            $file_name = date('YmdHis').rand(100000, 999999) . '.' . $extension;

            Storage::put( $url.'/'.$file_name, file_get_contents($file->getRealPath()));

            $images_url[$i]['file_url'] = $usage.'/'.$file_name;
            $images_url[$i]['usage'] = $usage;
            $images_url[$i]['created_at'] = date("Y-m-d H:i:s");

            $files_url[$i] = $images_url[$i]['file_url'];
            $files_url_full[$i] = url('/image/original'.$files_url[$i] );

            if($media_folder_id)
            {
                Media::create([
                    'media_folder_id' => $media_folder_id,
                    'path' => $url,
                    'name' => $file_name,
                    'url' => $images_url[$i]['file_url']
                ]);
            }
            $i++;
        }
        return [
            'file_url' => $files_url,
            'file_url_full' => $files_url_full,
        ];

    }
}