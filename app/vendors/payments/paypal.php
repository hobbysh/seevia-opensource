<?php
class paypal{
		var $config;
		var $config_cn= array(
			"account"=>array(
				"name" => "Paypal账号",
				"type" => "text"
			)
		);
		var $config_en= array(
			"account"=>array(
				"name" => "Paypal Account",
				"type" => "text"
			)
		);
		var $response=array();
		var $busy='';
		var $return_url='';
		var $cancel_return='';
		
	function alipay(){
    	
    }

    function __construct(){
        $this->alipay();
    }
	
	function go($order, $payment_config){
		$this->busy=$payment_config['account'];
		$this->return_url=$payment_config['return_url'];
		$this->cancel_return=$payment_config['cancel_return'];
		//echo $this->return_url;
		//die();
		$sHtmlText = $this->build_form($order);
		return $sHtmlText;
	}

	function go2($order, $payment_config){
		$this->busy=$payment_config['account'];
		$this->return_url=$payment_config['return_url'];
		$this->cancel_return=$payment_config['cancel_return'];
		//echo $this->return_url;
		//die();
		$sHtmlText = $this->build_form2($order);
		return $sHtmlText;
	}

	function build_form($order){
		$sHtmlText="";
		$sHtmlText.= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>";
		$currency_code='USD';
		if($order['order_currency']=='Euro'){
			$currency_code='EUR';
		}else if($order['order_currency']=='Dollar'){
			$currency_code='USD';
		}else if($order['order_currency']=='Pound'){
			$currency_code='GBP';
		}else if($order['order_currency']=='CA_Dollar'){
			$currency_code='CAD';
		}else if($order['order_currency']=='AU_Dollar'){
			$currency_code='AUD';
		}else if($order['order_currency']=='Francs'){
			$currency_code='CHF';
		}else if($order['order_currency']=='hk'){
			$currency_code='HKD';
		}else{
			$currency_code='USD';
		}

		$sHtmlText.='<form name="payform" id="payform" style="text-align:center;" action="https://www.paypal.com/cgi-bin/webscr" method="post" >';
		$sHtmlText.="<input type='hidden' name='cmd' value='_xclick'/>";
		$sHtmlText.="<input type='hidden' name='business' value='".$this->busy."'/>";
		$sHtmlText.="<input type='hidden' name='item_name' value='".$order['subject']."'/>
		<input type='hidden' name='amount' value='".$order['amount']."'/>
		<input type='hidden' name='currency_code' value='".$currency_code."'/>
		<input type='hidden' name='return' value='".$this->return_url."' />
		<input type='hidden' name='invoice' value='".$order['order_id']."'/><!-- 订单号 -->
		<input type='hidden' name='charset' value='utf-8'/>
		<input type='hidden' name='no_shipping' value='1'/>
		<input type='hidden' name='no_note' value='1' />
		<input type='hidden' name='notify_url' value='".$this->return_url."' />
		<input type='hidden' name='rm' value='2'/>
		<input type='hidden' name='cancel_return'   value='".$this->cancel_return."' /><!-- 取消页面 -->";
		$sHtmlText.= '<input type="submit" style="width:80px; height:28px; background:url(http://www.ioco.cn/img/paypal-logo.jpg) no-repeat;display:block;margin:5px 0;text-indent: -9999px"></form>';	
		$sHtmlText.="<script>document.forms['payform'].submit();</script>";

		return $sHtmlText;
	}
	
	function build_form2($order){
		$sHtmlText="";
		$currency_code='USD';
		if($order['order_currency']=='Euro'){
			$currency_code='EUR';
		}else if($order['order_currency']=='Dollar'){
			$currency_code='USD';
		}else if($order['order_currency']=='Pound'){
			$currency_code='GBP';
		}else if($order['order_currency']=='CA_Dollar'){
			$currency_code='CAD';
		}else if($order['order_currency']=='AU_Dollar'){
			$currency_code='AUD';
		}else if($order['order_currency']=='Francs'){
			$currency_code='CHF';
		}else if($order['order_currency']=='hk'){
			$currency_code='HKD';
		}else{
			$currency_code='USD';
		}

		$sHtmlText.='<form name="payform" target="_blank" id="payform" style="text-align:center;" action="https://www.paypal.com/cgi-bin/webscr" method="post" >';
		$sHtmlText.="<input type='hidden' name='cmd' value='_xclick'/>";
		$sHtmlText.="<input type='hidden' name='business' value='".$this->busy."'/>";
		$sHtmlText.="<input type='hidden' name='item_name' value='".$order['subject']."'/>
		<input type='hidden' name='amount' value='".$order['amount']."'/>
		<input type='hidden' name='currency_code' value='".$currency_code."'/>
		<input type='hidden' name='return' value='".$this->return_url."' />
		<input type='hidden' name='invoice' value='".$order['order_id']."'/><!-- 订单号 -->
		<input type='hidden' name='charset' value='utf-8'/>
		<input type='hidden' name='no_shipping' value='1'/>
		<input type='hidden' name='no_note' value='1' />
		<input type='hidden' name='notify_url' value='".$this->return_url."' />
		<input type='hidden' name='rm' value='2'/>
		<input type='hidden' name='cancel_return' value='".$this->cancel_return."' /><!-- 取消页面 -->";
		$sHtmlText = $sHtmlText.'<input type="submit" style="width:80px; height:28px; background:url(http://www.ioco.cn/img/paypal-logo.jpg) no-repeat;display:block;margin:5px 0;text-indent: -9999px" />';		
		$sHtmlText.= "</form>";	
		return $sHtmlText;
	}
	
	
	
	function notify($config){
		$this->busy=$payment_config['account'];
		$req = 'cmd=_notify-validate';
        foreach ($_POST as $key => $value)
        {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }
		
        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) ."\r\n\r\n";
        $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
        $test=array();
        $test['item_name'] = $_POST['item_name'];
        $test['item_number'] = $_POST['item_number'];
        $test['payment_status'] = $_POST['payment_status'];
        $test['payment_amount'] = $_POST['mc_gross'];
        $test['payment_currency'] = $_POST['mc_currency'];
        $test['txn_id'] = $_POST['txn_id'];
        $test['receiver_email'] = $_POST['receiver_email'];
        $test['payer_email'] = $_POST['payer_email'];
        $test['order_sn'] = $_POST['invoice'];
        $test['memo'] = !empty($_POST['memo']) ? $_POST['memo'] : '';
        $test['action_note'] =  $test['txn_id'] . '（paypal 交易号）' . $test['memo'];
		$this->response=$test;
		pr($this->response);
	}
	
	//返回订单号
	function get_track_id(){
		return $this->response['order_sn'];	
	}
	
	function get_trade_status(){	
		if($this->response['payment_status'] == 'Completed'){
			return 1;

        }else{
			return 0;
		}
	}
	
	function check_amount($amount){
		return ($this->response['payment_amount'] == $amount);
	}	
	
	function return_verify(){
		return true;
	}	
}