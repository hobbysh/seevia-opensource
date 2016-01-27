<?php
/**
 * QQ Weibo strategy for Opauth
 * based on http://wiki.open.t.qq.com/index.php
 * 
 * More information on Opauth: http://opauth.org
 * 
 * @link         http://opauth.org
 * @package      Opauth.QQStrategy
 * @license      MIT License
 */

class QQStrategy extends OpauthStrategy{
	
	/**
	 * Compulsory config keys, listed as unassociative arrays
	 */
	public $expects = array('key', 'secret');
	
	/**
	 * Optional config keys with respective default values, listed as associative arrays
	 */
	public $defaults = array(
		'redirect_uri' => '{complete_url_to_strategy}qq_callback'
	);

	/**
	 * Auth request
	 */
	public function request(){
		$url="https://graph.qq.com/oauth2.0/authorize";
		$params = array(
			'which' => 'ConfirmPage',
			'client_id' => $this->strategy['key'],
			'redirect_uri' => $this->strategy['strategy_callback_url'],
			//'response_type' => 'token',
			'response_type' => 'code',
			'state'=>'qq',
			'scope' => 'all'
		);
		$this->clientGet($url, $params);
	}
	
	/**
	 * Internal callback, after QQWeibo's OAuth
	 */
	public function qq_callback(){
		if (array_key_exists('code', $_GET) && !empty($_GET['code'])){
			$url = "https://graph.qq.com/oauth2.0/token";
			$params = array(
				'client_id' =>$this->strategy['key'],
				'client_secret' => $this->strategy['secret'],
				'redirect_uri'=> $this->strategy['strategy_callback_url'],
				'code' => $_GET['code'],       
				'grant_type' => 'authorization_code'
			);
			$response = $this->serverPost($url, $params, null, $headers);
			if (empty($response)){
				$error = array(
					'code' => 'Get access token error',
					'message' => 'Failed when attempting to get access token',
					'raw' => array(
						'headers' => $response
					)
				);
				$this->errorCallback($error);
			}
			parse_str($response, $results);
			if(!empty($results)&&isset($results['access_token'])){
				$qquser = $this->getOpenId($results);
				if(isset($qquser->nickname)){
	      			$this->auth = array(
						'provider' => 'QQ',
						'uid' => $qquser->nickname,
						'info' => array(
							'name' => $qquser->nickname,
							'location' => $qquser->province,
							'nickname' => $qquser->nickname,
							'image' => $qquser->figureurl_qq_2
						),
						'credentials' => array(
							'token' => $results['access_token'],
							'expires' => date('c', time() + $results['expires_in'])
						),
						'raw' => $qquser
					);
					$this->callback();
				}else{
					$error = array(
						'code' => 'Get qq user error',
						'message' => 'Failed when attempting to get access token',
						'raw' => array(
							'headers' => $qquser
						)
					);
					$this->errorCallback($error);
				}
			}else{
				$error = array(
					'code' => 'Get qq user error',
					'message' => 'Failed when attempting to get access token',
					'raw' => array(
						'headers' => $results
					)
				);
				$this->errorCallback($error);
			}
		}
		else
		{
			$error = array(
				'code' => $_GET['error'],
				'message' => $_GET['error_description'],
				'raw' => $_GET
			);
			$this->errorCallback($error);
		}
	}
	
	private function getOpenId($results){
		$qquser=null;
		$access_token=$results['access_token'];
		$url="https://graph.qq.com/oauth2.0/me";
		$params = array(
			'access_token' =>$access_token,
		);
		$qquserOpenIdstr = $this->serverget($url,$params);
		$qquserOpenIdstr=str_replace("callback(","",$qquserOpenIdstr);
		$qquserOpenIdstr=str_replace(" );","",$qquserOpenIdstr);
		$qquserOpenId=json_decode($qquserOpenIdstr);
		if(!empty($qquserOpenId->openid)){
			$userparams=array(
				'access_token'=>$access_token,
				'oauth_consumer_key'=>$this->strategy['key'],
				'openid'=>$qquserOpenId->openid
			);
			$qquserurl="https://graph.qq.com/user/get_user_info";
			$result=$this->serverget($qquserurl,$userparams);
			$qquser=json_decode($result);
		}
		return $qquser;
	}
}
