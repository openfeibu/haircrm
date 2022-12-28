<?php
namespace App\Services\Paypal;

use App\Helpers\ErrorCode;
use GuzzleHttp\Client;
use App\Exceptions\OutputServerMessageException;
use Cache;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class BaseService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->getAccessToken();
    }

}