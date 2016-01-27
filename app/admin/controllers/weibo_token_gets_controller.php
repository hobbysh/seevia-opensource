<?php
/**
  *这是一个名为 WeiboRbsController 的控制器
  *后台商品管理控制器
  *@var $name
  *@var $components
  *@var $helpers
  *@var $uses
  */

App::import('Vendor','weibo2' ,array('file'=>'saetv2.php'));
//include(ROOT."/vendors/nusoap/nusoap.php");
App::import("Vendor", "nusoap");
shops_controller.php
App::import('Controller', 'Commons');//加载公共控制器
class WeiboTokenGetsController extends AppController{
    var $name='WeiboTokenGets'; 
    var $components=array('Pagination','RequestHandler');
    var $helpers=array('Html','Pagination');
    var $uses=array('WeiboRb','WeiboOp','WeiboOpLog','SynchroOperator','Resource','Application','ApplicationConfig','ApplicationConfigI18n','WeiboTeam','WeiboLog','WeiboThm','OperatorLog');

	function wbtest(){
//		$keys=array();
//		$keys['code']='27f3199ec4b7141b7f2ebe1dd7e9db15';
//		$keys['redirect_uri']='http://c60216d.ioco.dev/admin/weibo_token_gets/response';
//		
//		$wb_token = $SaeTOAuthV2->getAccessToken('code',$keys);
//		pr($wb_token);die;
//		$url='emotions';
//		$parameters=array();
//		$parameters['source']='1963541527';
//		$parameters['access_token']='2.00je4LOCdqosIC8a08ce1f7e7hgtyD';
//		$parameters['type ']='face';
//		$parameters['language']='cnname';
//		$wb_result = $SaeTOAuthV2->get($url,$parameters);
//		pr($wb_result);die;
		$SaeTOAuthV2 =$this->saetoauthv2();
		$url='trends';
		$parameters=array();
		$parameters['uid']='26229841';//
		$wb_result = $SaeTOAuthV2->get($url,$parameters);
		pr($wb_result);die;
		$url='trends/follow';
		$parameters=array();
		$parameters['trend_name']='经典';//26229841
		$wb_result = $SaeTOAuthV2->post($url,$parameters);
		pr($wb_result);die;
	}
	function get_access_token($response=''){
		$shop_oper=$this->SynchroOperator->find('first',array('conditions'=>array('SynchroOperator.status'=>1)));
		$app_key=$shop_oper['SynchroOperator']['app_key'];
		$app_secret=$shop_oper['SynchroOperator']['app_secret'];
		$SaeTOAuthV2 = new SaeTOAuthV2($app_key,$app_secret);
		$url="http://".$_SERVER['HTTP_HOST'].'/admin/weibo_token_gets/response';
		$url='http://c60216d.ioco.dev/admin/weibo_token_gets/response';
		if(empty($response)){
			$get_code_url = $SaeTOAuthV2->getAuthorizeURL($url);
			$this->redirect($get_code_url);
		}else{
			$keys=array();
			$keys['code']=$response['code'];
			$keys['redirect_uri']='http://c60216d.ioco.dev/admin/weibo_token_gets/response';
			$wb_token = $SaeTOAuthV2->getAccessToken('code',$keys);
			$shop_oper['SynchroOperator']['code']=$response['code'];
			if(isset($wb_token['access_token'])){
				$shop_oper["SynchroOperator"]["access_token"] = $wb_token["access_token"];
			}
			$this->SynchroOperator->save($shop_oper);
			//操作员日志
			if( $this->configs['operactions-log'] == 1){
				$this->OperatorLog->log(date("H:i:s").' '.$this->ld['operator'].' '.$this->admin['name'].' '.'更新微博code access_token',$this->admin['id']);
			}
			pr($response);
			pr($wb_token);die;
		}

	}
	function response(){
		pr($_REQUEST);
		if(!empty($_REQUEST)){
			$this->get_access_token($_REQUEST);
		}else{
			pr($_REQUEST);die;
		}		
	}
		//appkey token
	function saetoauthv2(){
		$shop_oper=$this->SynchroOperator->find('first',array('conditions'=>array('SynchroOperator.status'=>1)));
		$app_key=$shop_oper['SynchroOperator']['app_key'];
		$app_secret=$shop_oper['SynchroOperator']['app_secret'];
		$access_token=$shop_oper['SynchroOperator']['access_token'];
		$SaeTOAuthV2 = new SaeTOAuthV2($app_key,$app_secret,$access_token);
		return $SaeTOAuthV2;
	}
}
?>