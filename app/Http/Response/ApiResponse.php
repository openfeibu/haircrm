<?php

namespace App\Http\Response;


class ApiResponse extends Response
{
    public function json()
    {
        return response()->json([
            'msg' => $this->getMessage(),
            'code' => $this->getCode(),
            'data' => $this->getData(),
            'count' => $this->getCount(),
        ], $this->http_code);
    }
}
