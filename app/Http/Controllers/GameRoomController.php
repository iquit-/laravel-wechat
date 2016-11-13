<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use EasyWeChat\Foundation\Application;

class GameRoomController extends Controller
{
	public static $config = [
		'oauth' => [
			'scopes'   => ['snsapi_userinfo'],
			'callback' => '/oauth_callback',
		],
	];
		
	$app = new Application($config);
		
	$oauth = $app->oauth;
		
	public function serve()
	{
		if (empty($_SESSION['wechat_user'])) {
			$_SESSION['target_url'] = 'user/profile';
			return $oauth->redirect();
		}
	}
}
