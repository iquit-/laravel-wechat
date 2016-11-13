<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use EasyWeChat\Foundation\Application;

class GameRoomController extends Controller
{
	public function serve()
	{
		$config = [
			'oauth' => [
				'scopes'   => ['snsapi_userinfo'],
				'callback' => '/oauth_callback',
			],
		];
		
		$app = new Application($config);
		
		$oauth = $app->oauth;
		
		if (empty($_SESSION['wechat_user'])) {
			$_SESSION['target_url'] = 'user/profile';
			return $oauth->redirect();
		}
		$user = $_SESSION['wechat_user'];
		return $user;
	}
	
	public function oauthCallback() {
		$config = [
			// ...
		];
		
		$app = new Application($config);
		
		$oauth = $app->oauth;
		// 获取 OAuth 授权结果用户信息
		$user = $oauth->user();
		$_SESSION['wechat_user'] = $user->toArray();
		$targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];
		return response()->json(['name' => 'oauthCallback', 'state' => 'CA']);
	}
	
}
