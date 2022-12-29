<?php
namespace App\Services\Paypal;

use App\Helpers\ErrorCode;
use App\Models\Onbuy\Onbuy;
use GuzzleHttp\Client;
use App\Exceptions\OutputServerMessageException;
use Cache;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class BaseService
{
    protected $provider;

    public function __construct($seller_id)
    {
        $onbuy = Onbuy::where('seller_id',$seller_id)->first();
        $config = config('paypal');
        $config['live'] = [
            'client_id' => $onbuy['paypal_client_id'],
            'client_secret' => $onbuy['paypal_client_secret'],
            'app_id' => $onbuy['paypal_app_id'],
        ];

        $this->provider = new PayPalClient($config);
        $this->provider->getAccessToken();
    }

}