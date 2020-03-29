<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class BaseController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->response = app(ApiResponse::class);
    }
}
