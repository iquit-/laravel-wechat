<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use EasyWeChat\Foundation\Application;

class GameRoomController extends Controller
{
	public function serve()
	{
		if (empty($_SESSION['wechat_user'])) {
			$_SESSION['target_url'] = 'user/profile';
			return $oauth->redirect();
		}
		$user = $_SESSION['wechat_user'];
		return $user;
	}
	
	public function oauthCallback() {
		$config = [
			'debug'     => true,
			'app_id'    => 'wxcb86c424bde09527',
			'secret'    => '7b254b38b5a7c1d75288425e871bfb95',
			'token'     => 'weixin',
			'log' => [
				'level' => 'debug',
				'file'  => '/tmp/easywechat.log',
			],
			'oauth' => [
				'scopes'   => ['snsapi_userinfo'],
				'callback' => '/oauthCallback',
			],
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
