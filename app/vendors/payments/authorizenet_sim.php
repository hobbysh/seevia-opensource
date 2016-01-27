<?php
class authorizenet_sim{
		var $config;
		var $config_cn= array(
			"account"=>array(
				"name" => "Authorize.net[SIM]账号",
				"type" => "text"
			),
			"key"=>array(
				"name" => "Authorize.net[SIM]KEY",
				"type" => "text"
			),
			"currencycode"=>array(
				"name" => "Authorize.net[SIM]货币",
				"type" => "select"
			)

		);
		var $config_en= array(
			"account"=>array(
				"name" => "Authorize.net[SIM] Account",
				"type" => "text"
			),
			"key"=>array(
				"name" => "Authorize.net[SIM]KEY",
				"type" => "text"
			),
			"currencycode"=>array(
				"name" => "Authorize.net[SIM] Currency",
				"type" => "select"
			)

		);
		var $response=array();
		var $loginid='';
		var $_key='';
		var $currencycode='';
		var $all_state=0;
		var $order_state=0;
		var $msg='';
		var $x_cust_id='';
		
		
	function authorizenet_sim(){
    	
    }

    function __construct(){
        $this->authorizenet_sim();
    }
	
	
	//$order, $payment_config
	function go2($order, $payment_config){
		$this->loginid=$payment_config['account'];
		$this->_key=$payment_config['key'];
		$sHtmlText = $this->build_form($order,$payment_config);
		return $sHtmlText;
	} 
	
	function build_form($order,$payment_config){
		$loginID        = $this->loginid;   
		$transactionKey = $this->_key;   
		$amount         = number_format($order['amount'], 2);   
		$description     = $order['subject'];   
		$label             = "pay with SIM"; // The is the label on the 'submit' button   
		$testMode        = "true";   // 是否开启测试功能， 如果开启，则网上付款都是测试，paypal也有此功能，只是方式不一样   
		$url            = "https://test.authorize.net/gateway/transact.dll"; //  这个是测试地址，实际付款地址为：    $url = "https://secure.authorize.net/gateway/transact.dll"   

		$invoice    = date('YmdHis');   
		// a sequence number is randomly generated   
		$sequence    = rand(1, 1000);   
		// a timestamp is generated   
		$timeStamp    = time ();   
		   
		if( phpversion() >= '5.1.2' ){    
			$fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey); 
		}   
		else{ 
			$fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey)); 
		}
		$dt='<div>';	   
		$dt.= "Amount: $amount <br />";   
		$dt.= "Description: $description <br />";   	   
		// 创建html 表单，里面包含了必须的SIM 内容   		   
		$dt.= "<FORM method='post' action='$url' target='_blank' >";   
		$oid=$order['order_id'];
		$rurl=$payment_config['return_url'];
		// Additional fields can be added here as outlined in the SIM integration guide   
		// at: http://developer.authorize.net   
		$dt.= "    <INPUT type='hidden' name='x_login' value='$loginID' />";             // ID   
		$dt.= "    <INPUT type='hidden' name='x_amount' value='$amount' />";             // 付款金额   
		$dt.= "    <INPUT type='hidden' name='x_description' value='$description' />";   // 描述   
		$dt.= "    <INPUT type='hidden' name='x_invoice_num' value='$invoice' />";   
		$dt.= "    <INPUT type='hidden' name='x_fp_sequence' value='$sequence' />";   
		$dt.= "    <INPUT type='hidden' name='x_fp_timestamp' value='$timeStamp' />";
		$dt.= "    <INPUT TYPE=HIDDEN NAME='x_cust_id' VALUE='$oid'>";         //order id
		$dt.= "    <INPUT type='hidden' name='x_fp_hash' value='$fingerprint' />";   
		$dt.= "    <INPUT type='hidden' name='x_test_request' value='$testMode' />";    
		$dt.= "    <INPUT type='hidden' name='x_show_form' value='PAYMENT_FORM' />";
		$dt.= "    <INPUT type='hidden' name='x_relay_response' value='TRUE' />";
		$dt.= "    <INPUT type='hidden' name='x_relay_url' value='$rurl' />";   
		$dt.= "    <input type='submit' value='$label' style='margin:5px 0;'/>";   
		$dt.= "</FORM>"; 
		$dt.='</div>';
		return $dt;
	}	
	
	function notify($config){
		$this->all_state=$_POST['x_response_code'];
		$this->order_state=$_POST['x_response_reason_code'];
		$this->msg=$_POST['x_response_reason_text'];
		$this->x_cust_id=$_POST['x_cust_id'];
		$this->x_amount=$_POST['x_amount'];
	}

	function get_track_id(){
		return $this->x_cust_id;	
	}
	
	function get_trade_status(){	
		if($this->order_state == 1){
			return 1;

        }else{
			return 0;
		}
	}
	
	function check_amount($amount){
		return ($this->x_amount == $amount);
	}	
	
	function return_verify(){
		return true;
	}	
	
}