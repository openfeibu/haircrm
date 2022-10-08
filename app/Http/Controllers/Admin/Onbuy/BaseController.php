<?php

namespace App\Http\Controllers\Admin\Onbuy;

use App\Http\Controllers\Admin\ResourceController as BaseResourceController;
use Xigen\Library\OnBuy\Auth;

/**
 * Resource controller class for page.
 */
class BaseController extends BaseResourceController
{

    public function __construct()
    {
        parent::__construct();
    }
}