<?php

namespace App\Http\Controllers;

use Log;

use Illuminate\Http\Request;

use App\Http\Requests;

use EasyWeChat\Foundation\Application;

class WeixinController extends Controller
{
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function($message){
            return "欢迎关注 IMS1101-house_price_analysis！";
        });

        Log::info('return response.');

        return $wechat->server->serve();
    }
}
