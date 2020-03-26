<?php
namespace App\Services;

use GuzzleHttp\Client;
use App\Exceptions\OutputServerMessageException;

class LBSService
{
    public function __construct()
    {
        $this->key = config('lbs.web_key');
        $this->client = new Client();
    }
    public function geocode_regeo($location)
    {
        $url = "https://apis.map.qq.com/ws/geocoder/v1/?key=".$this->key."&location=".$location.'&get_poi=0';
        $res = $this->client->get($url);
        $data = json_decode($res->getBody()->getContents(),true);

        if($data['status'])
        {
            throw new \App\Exceptions\OutputServerMessageException($data['message']);
        }

        return $data;
    }

}