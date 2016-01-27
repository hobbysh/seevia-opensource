<?php
class weixinpay {
	var $gateway;           //网关地址
    var $_key;			  	//安全校验码
    var $partner;           //合作伙伴ID
    var $sign_type='MD5';         //签名方式 系统默认
    var $mysign;            //签名结果
    var $_input_charset='UTF-8';    //字符编码格式
    var $transport='https';         //访问模式
    var $ld;
	var $response=array();
	var $config;
	var $config_cn= array(
		"APPID"=>array(
			"name" => "绑定支付的APPID",
			"type" => "text"
		),
		"MCHID"=>array(
			"name" => "商户号",
			"type" => "text"
		),
		"KEY"=>array(
			"name" => "商户支付密钥",
			"type" => "text"
		),
		"APPSECRET"=>array(
			"name" => "公众帐号secert",
			"type" => "text"
		)
	);
	var $config_en= array(
		"APPID"=>array(
			"name" => "APPID",
			"type" => "text"
		),
		"MCHID"=>array(
			"name" => "MCHID",
			"type" => "text"
		),
		"KEY"=>array(
			"name" => "KEY",
			"type" => "text"
		),
		"APPSECRET"=>array(
			"name" => "APPSECRET",
			"type" => "text"
		)
	);
    function weixinpay(){
    	
    }

    function __construct(){
        $this->weixinpay();
    }
}
?>