<?php
namespace App\Services\Paypal;

use App\Helpers\ErrorCode;
use App\Services\Paypal\BaseService;
use GuzzleHttp\Client;
use App\Exceptions\OutputServerMessageException;
use Log;

class TrackingService extends BaseService
{

    public function __construct($seller_id)
    {
        parent::__construct($seller_id);
    }
    public function addTracking($data=[])
    {
        $res = $this->provider->addBatchTracking($data);
        Log::info('addBatchTracking: ');
        Log::info($res);
        return $res;
    }

}