<?php
	App::import('Vendor','support' ,array('file'=>'authorizenet.class.php'));
class authorizenet_aim{
		var $config;
		var $config_cn= array(
			"account"=>array(
				"name" => "Authorize.net[AIM]账号",
				"type" => "text"
			),
			"key"=>array(
				"name" => "Authorize.net[AIM]KEY",
				"type" => "text"
			),
			"used"=>array(
				"name" => "是否启用直接支付",
				"type" => "radio"
			),
			"used_mod"=>array(
				"name" => "是否启用测试模式",
				"type" => "radio"
			)

//			"currencycode"=>array(
//				"name" => "Authorize.net[AIM]货币",
//				"type" => "select"
//			)

		);
		var $config_en= array(
			"account"=>array(
				"name" => "Authorize.net[AIM] Account",
				"type" => "text"
			),
			"key"=>array(
				"name" => "Authorize.net[AIM]KEY",
				"type" => "text"
			),
			"used"=>array(
				"name" => "Whether to enable direct payment",
				"type" => "radio"
			),
			"used_mod"=>array(
				"name" => "Whether to enable test mod",
				"type" => "radio"
			)
			
//			"currencycode"=>array(
//				"name" => "Authorize.net[AIM] Currency",
//				"type" => "select"
//			)

		);
		var $response=array();
		var $loginid='';
		var $_key='';
		var $currencycode='';
		var $is_success=0;
		var $msg;
		var $order=array();
		
		
	function authorizenet_aim(){
    	
    }

    function __construct(){
        $this->authorizenet_aim();
    }
	
	//order_list
	
	//$order, $payment_config
	/*
		$order['payerName'];
		$order['payerAdderss']['address'];
		$order['payerAdderss']['city'];
		$order['payerAdderss']['state'];
		$order['payerAdderss']['country'];
		$order['x_zip'];		
		$order['payerEmail'];
		$order['payerPhone'];
		$order['card_num'];
		$order['amount'];		//总价
		$order['expDate'];    // 0315 march of 2015/03/ 
		$order['CAVV'];    // 信用卡


	*/
	//new list $order['card_num'],$order['name'],$order['payerAdderss']
	function go_auth_only($order, $payment_config){
		
		$this->loginid=$payment_config['account'];
		$this->_key=$payment_config['key'];
		$this->order=$order;
		$return_array=array();
		//pr($order);die();
		$isrealcard=$this->validateCreditcard_number($order['card_num']);
		if($isrealcard != 'This is a valid credit card number'){
			$return_array['return_code']=4;
		    $return_array['reason_text']=$isrealcard;
			return $return_array;
		}
		$a = new authorizenet_class;
		if($payment_config['used_mod'] == '0'){
			$a->change_mod('test');
		}else{
			$a->change_mod('now');	
		}
		// You login using your login, login and tran_key, or login and password.  It
		// varies depending on how your account is setup.
		// I believe the currently reccomended method is to use a tran_key and not
		// your account password.  See the AIM documentation for additional information.
		 
		$a->add_field('x_login', $this->loginid);
		$a->add_field('x_tran_key', $this->_key);
		//$a->add_field('x_password', 'CHANGE THIS TO YOUR PASSWORD');

		$a->add_field('x_version', '3.1');
		$a->add_field('x_type', 'AUTH_ONLY');//AUTH_ONLY,CAPTURE_ONLY,CREDIT,PRIOR_AUTH_CAPTURE,AUTH_CAPTURE
		$a->add_field('x_test_request', 'TRUE');    // Just a test transaction
		$a->add_field('x_relay_response', 'FALSE');

		// You *MUST* specify '|' as the delim char due to the way I wrote the class.
		// I will change this in future versions should I have time.  But for now, just
		// make sure you include the following 3 lines of code when using this class.

		$a->add_field('x_delim_data', 'TRUE');
		$a->add_field('x_delim_char', '|');     
		$a->add_field('x_encap_char', '');


		// Setup fields for customer information.  This would typically come from an
		// array of POST values froma secure HTTPS form.

		$a->add_field('x_first_name', $order['payerName']);
		$a->add_field('x_last_name', '');
		$a->add_field('x_address', $order['payerAdderss']['address']);
	//	$a->add_field('x_city', $order['payerAdderss']['city']);
		$a->add_field('x_state', $order['payerAdderss']['state']);
		$a->add_field('x_zip', $order['x_zip']);
		$a->add_field('x_country', $order['payerAdderss']['country']);
		$a->add_field('x_email', $order['payerEmail']);
		$a->add_field('x_phone',$order['payerPhone']);


		// Using credit card number '4007000000027' performs a successful test.  This
		// allows you to test the behavior of your script should the transaction be
		// successful.  If you want to test various failures, use '4222222222222' as
		// the credit card number and set the x_amount field to the value of the 
		// Response Reason Code you want to test.  
		// 
		// For example, if you are checking for an invalid expiration date on the
		// card, you would have a condition such as:
		// if ($a->response['Response Reason Code'] == 7) ... (do something)
		//
		// Now, in order to cause the gateway to induce that error, you would have to
		// set x_card_num = '4222222222222' and x_amount = '7.00'

		//  Setup fields for payment information
		$a->add_field('x_method', 'CC');
		$a->add_field('x_card_num', $order['card_num']);
		//$a->add_field('x_card_num', '4007000000027');   // test successful visa
		//$a->add_field('x_card_num', '370000000000002');   // test successful american express
		//$a->add_field('x_card_num', '6011000000000012');  // test successful discover
		//$a->add_field('x_card_num', '5424000000000015');  // test successful mastercard
		// $a->add_field('x_card_num', '4222222222222');    // test failure card number
		$a->add_field('x_amount', number_format($order['amount'], 2));
		$a->add_field('x_exp_date', $order['expDate']);    // march of 2015/03/
		$a->add_field('x_card_code', $order['CAVV']);    // Card CAVV Security code

		// Process the payment and output the results
		switch ($a->process()) {

		   case 1:  // Successs
		   	  $return_array['return_code']=1;
		   	  $return_array['Transaction ID']=$a->get_response_transacton_id();
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
		      
		   case 2:  // Declined
		      $return_array['return_code']=2;
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
		      
		   case 3:  // Error
		      $return_array['return_code']=3;
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
	      case 0:  // Error
		      $return_array['return_code']=0;
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
		}

		// The following two functions are for debugging and learning the behavior
		// of authorize.net's response codes.  They output nice tables containing
		// the data passed to and recieved from the gateway.

		//$a->dump_fields();      // outputs all the fields that we set
		//$a->dump_response();    // outputs the response from the payment gateway
		return $return_array;

	}
	
	function go_prior_auth_capture($order, $payment_config){
		$this->loginid=$payment_config['account'];
		$this->_key=$payment_config['key'];
		$this->order=$order;
		$return_array=array();
		//pr($order);die();
		$isrealcard=$this->validateCreditcard_number($order['card_num']);
		if($isrealcard != 'This is a valid credit card number'){
			$return_array['return_code']=4;
		    $return_array['reason_text']=$isrealcard;
			return $return_array;
		}
		if(!isset($order['transaction_id']) || empty($order['transaction_id'])){
			$return_array['return_code']=6;
		    $return_array['reason_text']='A valid referenced transaction ID is required';
			return $return_array;		
		}
		$a = new authorizenet_class;
		if($payment_config['used_mod'] == '0'){
			$a->change_mod('test');
		}else{
			$a->change_mod('now');	
		}

		// You login using your login, login and tran_key, or login and password.  It
		// varies depending on how your account is setup.
		// I believe the currently reccomended method is to use a tran_key and not
		// your account password.  See the AIM documentation for additional information.
		 
		$a->add_field('x_login', $this->loginid);
		$a->add_field('x_tran_key', $this->_key);
		//$a->add_field('x_password', 'CHANGE THIS TO YOUR PASSWORD');

		$a->add_field('x_version', '3.1');
		$a->add_field('x_type', 'PRIOR_AUTH_CAPTURE');//AUTH_ONLY,CAPTURE_ONLY,CREDIT,PRIOR_AUTH_CAPTURE,AUTH_CAPTURE
		$a->add_field('x_trans_id', $order['transaction_id']);	//if CAPTURE,this is always should be send 
		$a->add_field('x_test_request', 'TRUE');    // Just a test transaction
		$a->add_field('x_relay_response', 'FALSE');

		// You *MUST* specify '|' as the delim char due to the way I wrote the class.
		// I will change this in future versions should I have time.  But for now, just
		// make sure you include the following 3 lines of code when using this class.

		$a->add_field('x_delim_data', 'TRUE');
		$a->add_field('x_delim_char', '|');     
		$a->add_field('x_encap_char', '');


		// Setup fields for customer information.  This would typically come from an
		// array of POST values froma secure HTTPS form.


		$a->add_field('x_email', $order['payerEmail']);
		$a->add_field('x_phone',$order['payerPhone']);


		// Using credit card number '4007000000027' performs a successful test.  This
		// allows you to test the behavior of your script should the transaction be
		// successful.  If you want to test various failures, use '4222222222222' as
		// the credit card number and set the x_amount field to the value of the 
		// Response Reason Code you want to test.  
		// 
		// For example, if you are checking for an invalid expiration date on the
		// card, you would have a condition such as:
		// if ($a->response['Response Reason Code'] == 7) ... (do something)
		//
		// Now, in order to cause the gateway to induce that error, you would have to
		// set x_card_num = '4222222222222' and x_amount = '7.00'

		//  Setup fields for payment information
		$a->add_field('x_method', 'CC');
		$a->add_field('x_card_num', $order['card_num']);
		//$a->add_field('x_card_num', '4007000000027');   // test successful visa
		//$a->add_field('x_card_num', '370000000000002');   // test successful american express
		//$a->add_field('x_card_num', '6011000000000012');  // test successful discover
		//$a->add_field('x_card_num', '5424000000000015');  // test successful mastercard
		// $a->add_field('x_card_num', '4222222222222');    // test failure card number
		$a->add_field('x_amount', number_format($order['amount'], 2));
		$a->add_field('x_exp_date', $order['expDate']);    // march of 2015/03/
		$a->add_field('x_card_code', $order['CAVV']);    // Card CAVV Security code

		// Process the payment and output the results
		switch ($a->process()) {

		   case 1:  // Successs
		   	  $return_array['return_code']=1;
		   	 // $return_array['Transaction ID']=$a->get_response_transacton_id();
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
		      
		   case 2:  // Declined
		      $return_array['return_code']=2;
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
		      
		   case 3:  // Error
		      $return_array['return_code']=3;
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
	      case 0:  // Error
		      $return_array['return_code']=0;
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
		}

		// The following two functions are for debugging and learning the behavior
		// of authorize.net's response codes.  They output nice tables containing
		// the data passed to and recieved from the gateway.

		//$a->dump_fields();      // outputs all the fields that we set
		//$a->dump_response();    // outputs the response from the payment gateway
		return $return_array;
	}
	
	function go_auth_capture($order, $payment_config){
		$this->loginid=$payment_config['account'];
		$this->_key=$payment_config['key'];
		$this->order=$order;
		$return_array=array();
		//pr($order);die();
		$isrealcard=$this->validateCreditcard_number($order['card_num']);
		if($isrealcard != 'This is a valid credit card number'){
			$return_array['return_code']=4;
		    $return_array['reason_text']=$isrealcard;
			return $return_array;
		}
		$a = new authorizenet_class;
		if($payment_config['used_mod'] == '0'){
			$a->change_mod('test');
		}else{
			$a->change_mod('now');	
		}

		// You login using your login, login and tran_key, or login and password.  It
		// varies depending on how your account is setup.
		// I believe the currently reccomended method is to use a tran_key and not
		// your account password.  See the AIM documentation for additional information.
		 
		$a->add_field('x_login', $this->loginid);
		$a->add_field('x_tran_key', $this->_key);
		//$a->add_field('x_password', 'CHANGE THIS TO YOUR PASSWORD');

		$a->add_field('x_version', '3.1');
		$a->add_field('x_type', 'AUTH_CAPTURE');//AUTH_ONLY,CAPTURE_ONLY,CREDIT,PRIOR_AUTH_CAPTURE,AUTH_CAPTURE
		$a->add_field('x_test_request', 'TRUE');    // Just a test transaction
		$a->add_field('x_relay_response', 'FALSE');

		// You *MUST* specify '|' as the delim char due to the way I wrote the class.
		// I will change this in future versions should I have time.  But for now, just
		// make sure you include the following 3 lines of code when using this class.

		$a->add_field('x_delim_data', 'TRUE');
		$a->add_field('x_delim_char', '|');     
		$a->add_field('x_encap_char', '');


		// Setup fields for customer information.  This would typically come from an
		// array of POST values froma secure HTTPS form.

		$a->add_field('x_first_name', $order['payerName']);
		$a->add_field('x_last_name', '');
		$a->add_field('x_address', $order['payerAdderss']['address']);
		$a->add_field('x_state', $order['payerAdderss']['state']);
		$a->add_field('x_zip', $order['x_zip']);
		$a->add_field('x_country', $order['payerAdderss']['country']);
		$a->add_field('x_email', $order['payerEmail']);
		$a->add_field('x_phone',$order['payerPhone']);


		// Using credit card number '4007000000027' performs a successful test.  This
		// allows you to test the behavior of your script should the transaction be
		// successful.  If you want to test various failures, use '4222222222222' as
		// the credit card number and set the x_amount field to the value of the 
		// Response Reason Code you want to test.  
		// 
		// For example, if you are checking for an invalid expiration date on the
		// card, you would have a condition such as:
		// if ($a->response['Response Reason Code'] == 7) ... (do something)
		//
		// Now, in order to cause the gateway to induce that error, you would have to
		// set x_card_num = '4222222222222' and x_amount = '7.00'

		//  Setup fields for payment information
		$a->add_field('x_method', 'CC');
		$a->add_field('x_card_num', $order['card_num']);
		//$a->add_field('x_card_num', '4007000000027');   // test successful visa
		//$a->add_field('x_card_num', '370000000000002');   // test successful american express
		//$a->add_field('x_card_num', '6011000000000012');  // test successful discover
		//$a->add_field('x_card_num', '5424000000000015');  // test successful mastercard
		// $a->add_field('x_card_num', '4222222222222');    // test failure card number
		$a->add_field('x_amount', number_format($order['amount'], 2));
		$a->add_field('x_exp_date', $order['expDate']);    // march of 2015/03/
		//$a->add_field('x_card_code', $order['CAVV']);    // Card CAVV Security code

		// Process the payment and output the results
		switch ($a->process()) {

		   case 1:  // Successs
		   	  $return_array['return_code']=1;
		   	  $return_array['Transaction ID']=$a->get_response_transacton_id();
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
		      
		   case 2:  // Declined
		      $return_array['return_code']=2;
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
		      
		   case 3:  // Error
		      $return_array['return_code']=3;
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
	      case 0:  // Error
		      $return_array['return_code']=0;
		      $return_array['reason_text']=$a->get_response_reason_text();
		      break;
		}

		// The following two functions are for debugging and learning the behavior
		// of authorize.net's response codes.  They output nice tables containing
		// the data passed to and recieved from the gateway.

		//$a->dump_fields();      // outputs all the fields that we set
		//$a->dump_response();    // outputs the response from the payment gateway
		return $return_array;

	}	
	
	
	
	
	function validateCreditcard_number($credit_card_number)
	{
	    // Get the first digit
	    $firstnumber = substr($credit_card_number, 0, 1);
	    // Make sure it is the correct amount of digits. Account for dashes being present.
	    switch ($firstnumber)
	    {
	        case 3:
	            if (!preg_match('/^3\d{3}[ \-]?\d{6}[ \-]?\d{5}$/', $credit_card_number))
	            {
	                return 'This is not a valid American Express card number';
	            }
	            break;
	        case 4:
	            if (!preg_match('/^4\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number))
	            {
	                return 'This is not a valid Visa card number';
	            }
	            break;
	        case 5:
	            if (!preg_match('/^5\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number))
	            {
	                return 'This is not a valid MasterCard card number';
	            }
	            break;
	        case 6:
	            if (!preg_match('/^6011[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number))
	            {
	                return 'This is not a valid Discover card number';
	            }
	            break;
	        default:
	            return 'This is not a valid credit card number';
	    }
	    // Here's where we use the Luhn Algorithm
	    $credit_card_number = str_replace('-', '', $credit_card_number);
	    $map = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
	                0, 2, 4, 6, 8, 1, 3, 5, 7, 9);
	    $sum = 0;
	    $last = strlen($credit_card_number) - 1;
	    for ($i = 0; $i <= $last; $i++)
	    {
	        $sum += $map[$credit_card_number[$last - $i] + ($i & 1) * 10];
	    }
	    if ($sum % 10 != 0)
	    {
	        return 'This is not a valid credit card number';
	    }
	    // If we made it this far the credit card number is in a valid format
	    return 'This is a valid credit card number';	
	    //echo validateCreditcard_number('4111-1111-1111-1111'); // This is a valid credit card number
		//echo validateCreditcard_number('4111-1111-1111-1112'); // This is not a valid credit card number
		//echo validateCreditcard_number('5558-545f-1234');      // This is not a valid MasterCard card number
		//echo validateCreditcard_number('9876-5432-1012-3456'); // This is not a valid credit card number
	}
	
	function check($pay_info,$card_info){
		$payment_api_log['payerAdderss']['address']=$pay_info['address'];
		$payment_api_log['payerAdderss']['state']=$pay_info['RegionI18n']['name'];
		$payment_api_log['payerAdderss']['country']=$pay_info['RegionI18n']['name'];	
		$payment_api_log['x_zip']=$pay_info['zipcode'];
		$payment_api_log['payerEmail']=$pay_info['email'];
		$payment_api_log['payerPhone']=$pay_info['telephone'];
		$payment_api_log['amount']=$pay_info['subtotal'];
		$payment_api_log['payerName']=$card_info['card_name'];
		$payment_api_log['card_num']=$card_info['card_num'];
		$payment_api_log['expDate']=$card_info['cdate'];
		$payment_api_log['CAVV']=$card_info['card_cavv'];	
		
		return	$payment_api_log;
		
	}
}