<?php
/**
 * Wechat strategy for Opauth
 * based on https://developers.facebook.com/docs/authentication/server-side/
 * 
 * More information on Opauth: http://opauth.org
 * 
 * @link         http://opauth.org
 * @package      Opauth.WechatStrategy
 * @license      MIT License
 */

class WechatStrategy extends OpauthStrategy{
	
	/**
	 * Compulsory config keys, listed as unassociative arrays
	 */
	public $expects = array('key', 'secret');
	
	/**
	 * Optional config keys with respective default values, listed as associative arrays
	 */
	public $defaults = array(
		'redirect_uri' => '{complete_url_to_strategy}wechat_callback'
	);

	/**
	 * Auth request
	 */
	public function request(){
		$appid=$this->strategy['key'];
		$redirect_uri=$this->strategy['redirect_uri'];
		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=".time()."#wechat_redirect";
		$params = array();
		$this->clientGet($url, $params);
	}
	
	/**
	 * Internal callback, after Wechat's OAuth
	 */
	public function wechat_callback(){
		if(isset($_REQUEST['code'])){
			$code=$_REQUEST['code'];
			$appid=$this->strategy['key'];
			$secret=$this->strategy['secret'];
			$get_token_url="https://api.weixin.qq.com/sns/oauth2/access_token";
			$params = array(
				'appid' =>$this->strategy['key'],
				'secret' => $this->strategy['secret'],
				'code' => $_REQUEST['code'], 
				'grant_type' => 'authorization_code'
			);
			$response = $this->serverPost($get_token_url,$params,null,$headers);
			if(empty($response)){
				$error = array(
					'code' => 'Get access token error',
					'message' => 'Failed when attempting to get access token',
					'raw' => array(
						'headers' => $headers
					)
				);
				$this->errorCallback($error);
			}
			$results=json_decode($response,true);
			if(empty($results['access_token'])){
				$error = array(
					'code' => 'Get access token error',
					'message' => 'Failed when attempting to get access token',
					'raw' => array(
						'headers' => $headers
					)
				);
				$this->errorCallback($error);
			}
			$wechatuser=$this->getuser($results['access_token'],$results['openid']);
			if(!empty($wechatuser->openid)){
				$this->auth = array(
					'provider' => 'wechat',
					'uid' => $wechatuser->openid,
					'info' => array(
						'name' => $wechatuser->nickname,
						'sex' => $wechatuser->sex,
						'nickname' => $wechatuser->nickname,
						'image' => $wechatuser->headimgurl
					),
					'credentials' => array(
						'token' => $results['access_token'],
						'expires' => date('c', time() + $results['expires_in'])
					),
					'raw' => $wechatuser
				);
				$this->callback();
			}else{
				$error = array(
					'code' => 'Get User error',
					'message' => 'Failed when attempting to query for user information',
					'raw' => array(
						'access_token' => $access_token,
						'headers' => $headers
					)
				);
				$this->errorCallback($error);
			}
		}else{
			$error = array(
				'code' => 'Get access token error',
				'message' => 'Failed when attempting to get access token',
				'raw' => array(
					'headers' => $headers
				)
			);
			$this->errorCallback($error);
		}
	}
	
	private function getuser($access_token,$openid){
		$get_user_url="https://api.weixin.qq.com/sns/userinfo";
		$wechatuser = $this->serverget($get_user_url, array('access_token' => $access_token,'openid'=>$openid,'lang'=>'zh_CN'));
		if (!empty($wechatuser)){
			return json_decode($wechatuser);
		}else{
			$error = array(
				'code' => 'Get User error',
				'message' => 'Failed when attempting to query for user information',
				'raw' => array(
					'access_token' => $access_token,
					'headers' => $headers
				)
			);
			$this->errorCallback($error);
		}
	} 
}
