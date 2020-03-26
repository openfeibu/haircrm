<?php

namespace App\Services;

use Log;
use File;
use Session;
use Storage;
use Excel;
use App\Imports\DemoImport;
use Illuminate\Http\Request;

class ExcelService
{
    protected $request;

    function __construct(Request $request)
    {
        $this->request = $request;
        $this->upload_folder = '/uploads/excel/';
    }

    public function uploadExcel()
    {
        $file = $this->request->file;

        isVaildExcel($file);

        $res = (new DemoImport)->toArray($file)[0];

        return $res;
    }

}
