<?php

namespace App\Http\Controllers;

use Log;

use Illuminate\Http\Request;

use App\Http\Requests;

use EasyWeChat\Foundation\Application;

class WeixinController extends Controller
{
	public static $baiduMapApiUrl = "http://api.map.baidu.com/geocoder/v2/?output=json&pois=1&ak=16a13b37a67c9102c3d825b5dc1674f9&location=";
	
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function($message){
            switch ($message->Event) {
				case 'subscribe':
					return self::getSubscribeResp();
				case 'LOCATION':
					return self::getEventLocationResp($message);
				default:
					break;
			}
			switch ($message->MsgType) {
				case 'text':
					return self::getTextResp();
				case 'image':
					return self::getImageResp();
				case 'voice':
					return self::getVoiceResp($message);
				case 'video':
					return self::getVideoResp();
				case 'location':
					return self::getLocationResp();
				case 'link':
					return self::getLinkResp();
				default:
					break;
			}
        });

        Log::info('return response.');

        return $wechat->server->serve();
    }
	
	public function getSubscribeResp() {
		return "欢迎关注 IMS1101-house_price_analysis！";
	}

	public function getTextResp() {
		return "这是文本！";
	}
	
	public function getImageResp() {
		return "这是图片！";
	}
	
	public function getVoiceResp($message) {
		return "你说的是".$message->MsgType."吗？".$message->Recongnition;
	}
	
	public function getVideoResp() {
		return "这是视频！";
	}
	
	public function getLocationResp() {
		return "这是坐标！";
	}
	
	public function getLinkResp() {
		return "这是链接！";
	}
	
	public function getEventLocationResp($message) {
		$url = self::$baiduMapApiUrl . $message->Latitude . "," . $message->Longitude;
		return $url;
		$html = file_get_contents($url);
		$obj=json_decode($html);
		$city =  $obj->result->addressComponent->city;
		$district =  $obj->result->addressComponent->district;
		//return $city . "地理位置纬度：" . $message->Latitude . "，地理位置经度：" . $message->Longitude . "，地理位置精度：" . $message->Precision . $html;
		return "您所在的行政区是" . $city . $district;
	}
}
