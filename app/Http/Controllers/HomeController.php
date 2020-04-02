<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Log,Mail;

class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function test()
    {
        $email = '1270864834@qq.com';
        $send = Mail::send('email', ['email' => $email,'name' => '吴志杰'], function($message) use($email) {
            $message->from(config('mail.from')['address'],config('mail.from')['name']);
            $message->subject('['.config('app.name').'] 邀请好友');
            $message->to($email);
        });
        var_dump($send);exit;
    }
}
