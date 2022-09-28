<?php

namespace App\Http\Controllers\Admin\Onbuy;

use App\Http\Controllers\Admin\ResourceController as BaseResourceController;
use Xigen\Library\OnBuy\Auth;

/**
 * Resource controller class for page.
 */
class BaseController extends BaseResourceController
{
    public $onbuy_token;

    public function __construct()
    {
        parent::__construct();
        $this->getOnbuyToken();
    }
    private function getOnbuyToken()
    {
        $config = [
            'consumer_key' => config('onbuy.consumer_key'),
            'secret_key' => config('onbuy.secret_key'),
        ];
        $auth = new Auth(
            $config
        );
        $this->onbuy_token = $auth->getToken();
        return $this->onbuy_token;
    }
}