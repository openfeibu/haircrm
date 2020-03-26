<?php
namespace App\Traits;

use Auth;
use App\Services\ImageService;
use Filer,Input;
use Request;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

trait Upload
{
    /**
     * Upload folder to the given path
     *
     * @param string $config
     * @param string $path
     *
     * @return array|json
     */
    public function upload($config, $path)
    {
        $path   = explode('/', $path);
        $file   = array_pop($path);
        $folder = implode('/', $path);

        if (Request::hasFile($file)) {
            $ufolder         = $this->uploadFolder($config);
            $array = app(ImageService::class)->uploadImages(Input::all(),$ufolder);

            $array['path'] = $array['image_url'] = $array['image_url'][0];

            $ext = pathinfo($array['path'], PATHINFO_EXTENSION);

            if (in_array($ext, config('filer.image_extensions'))) {
                $array['url'] = url('image/original' . '/' . ltrim($array['path'],'/'));
            } else {
                $array['url'] = url('image/download' . '/' . ltrim($array['path'],'/'));
            }

            $data = [
                'code' => 0,
                'message' => '上传成功',
                'data' => [
                    'url' => $array['url'],
                    'path' => $array['path'],
                ],
            ];
            return response()->json($data)
                ->setStatusCode(203, 'UPLOAD_SUCCESS');
        }

    }

    public function uploadFile($config, $path)
    {
        $path   = explode('/', $path);
        $file   = array_pop($path);

        if (Request::hasFile($file)) {
            $ufolder         = $this->uploadFolder($config);
            $array = app(ImageService::class)->uploadFiles(Input::all(),$ufolder);

            $array['path'] = $array['file_url'] = $array['file_url'][0];

            $ext = pathinfo($array['path'], PATHINFO_EXTENSION);

            if (in_array($ext, config('filer.image_extensions'))) {
                $array['url'] = url('image/original' . '/' . ltrim($array['path'],'/'));
            } else {
                $array['url'] = url('image/download' . '/' . ltrim($array['path'],'/'));
            }

            $data = [
                'code' => 0,
                'message' => '上传成功',
                'data' => [
                    'url' => $array['url'],
                    'path' => $array['path'],
                ],
            ];
            return response()->json($data)
                ->setStatusCode(203, 'UPLOAD_SUCCESS');
        }

    }
    /**
     * Return the upload folder path.
     *
     * @param type $config
     * @param string $folder
     * @return string
     */
    public function uploadFolder($config, $folder='')
    {
        $path = config($config . '.upload_folder');

        if (empty($path)) {
            throw new FileNotFoundException('Invalid upload configuration value.');
        }

        return $folder ? "{$path}/{$folder}" : "{$path}";

    }
}