<?php
class tenpay{
    function tenpay()
    {
    }

    function __construct()
    {
        $this->tenpay();
    }
    
	function go($order, $payment_config){
		$this->busy=$payment_config['tenpay_account'];
		$this->return_url=$payment_config['return_url'];
		$this->cancel_return=$payment_config['cancel_return'];
		//echo $this->return_url;
		//die();
		$sHtmlText = $this->build_form2($order);
		return $sHtmlText;
	}
	
    function get_code($order, $payment,$db)
    {
		eval($payment['Payment']['config']);
		$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
    	
        $cmd_no = '1';
        // 订单号 
        $sp_billno = $order['log_id'];
        // 交易日期 
        $today = date('Ymd');
        /* 将商户号+年月日+流水号 */
        $bill_no = str_pad($order['log_id'], 10, 0, STR_PAD_LEFT);
        // $tenpay_account 商家号
      	$tenpay_account = $payment_arr['tenpay_account']['value'];
        $transaction_id = $tenpay_account.$today.$bill_no;
        if(isset($order['order_code'])){
        	$desc = $order['order_code'];
        	$attach = '';			//订单
        }else{
        	$desc =   '会员充值';
        	$attach = 'voucher';					//充值
        }
        
        
        $tenpay_key = $payment_arr['tenpay_key']['value'];
        // 银行类型:支持纯网关和财付通 
        $bank_type = '0';
        
        // 订单描述
        $desc = $order['log_id'];
        
        // 返回的路径 
        $return_url = "http://".$host.$db->webroot."responds/index/tenpay";
        // 总金额 
        $total_fee = floatval($order['total']) * 100;
        // 货币类型 
        $fee_type = '1';
        // 数字签名 
        $sign_text = "cmdno=" . $cmd_no . "&date=" . $today . "&bargainor_id=" . $tenpay_account .
          "&transaction_id=" . $transaction_id . "&sp_billno=" . $sp_billno .
          "&total_fee=" . $total_fee . "&fee_type=" . $fee_type . "&return_url=" . $return_url .
          "&attach=" . $attach . "&key=" . $tenpay_key;
        $sign = strtoupper(md5($sign_text));

        /* 交易参数 */
        $parameter = array(
            'cmdno'             => $cmd_no,                     // 业务代码, 财付通支付支付接口填  1
            'date'              => $today,                      // 商户日期：如20051212
            'bank_type'         => $bank_type,                  // 银行类型:支持纯网关和财付通
            'desc'              => $desc,                       // 交易的商品名称
            'purchaser_id'      => '',                          // 用户(买方)的财付通帐户,可以为空
            'bargainor_id'      => $tenpay_account,  			// 商家的财付通商户号
            'transaction_id'    => $transaction_id,             // 交易号(订单号)，由商户网站产生(建议顺序累加)
            'sp_billno'         => $sp_billno,                  // 商户系统内部的定单号,最多10位
            'total_fee'         => $total_fee,                  // 订单金额
            'fee_type'          => $fee_type,                   // 现金支付币种
            'return_url'        => $return_url,                 // 接收财付通返回结果的URL
            'attach'            => $attach,                     // 用户自定义签名
            'sign'              => $sign                        // MD5签名
        //  'sys_id'            => '99887766'                 //sv-cart C账号 不参与签名
        );

        $button  = '<br /><form style="text-align:center;" action="https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi"  target="_blank" style="margin:0px;padding:0px" >';

        foreach ($parameter AS $key=>$val)
        {
            $button  .= "<input type='hidden' name='$key' value='$val' />";
        }

        $button  .= "<input src='http://lyceem3.projects.seevia.cn/images/tenpay.gif' value='立即使用财付通支付' type='image'></form><br />";

        return $button;
    }

    /**
     * 响应操作
     */
    function respond($db)
    {
        /*取返回参数*/
        $cmd_no         = $_GET['cmdno'];
        $pay_result     = $_GET['pay_result'];
        $pay_info       = $_GET['pay_info'];
        $bill_date      = $_GET['date'];
        $bargainor_id   = $_GET['bargainor_id'];
        $transaction_id = $_GET['transaction_id'];
        $sp_billno      = $_GET['sp_billno'];
        $total_fee      = $_GET['total_fee'];
        $fee_type       = $_GET['fee_type'];
        $attach         = $_GET['attach'];
        $sign           = $_GET['sign'];

        $payment    = $db->Payment->findbycode('tenpay');
		eval($payment['Payment']['config']);
        $tenpay_key = $payment_arr['tenpay_key']['value'];
        $payment_log = $db->PaymentApiLog->findbyid($sp_billno);
        //检查是否已成功支付
		if($payment_log['PaymentApiLog']['is_paid'] == "1"){
 			return false;
 		}
		/*
        if ($attach == 'voucher')
        {
            $account = $db->UserAccount->findbyid($payment_log['PaymentApiLog']['type_id']);
	        if ($total_fee != $account['UserAccount']['amount'])
	        {
	            return false;
	        }
        }
        else
        {
            $order_total = $db->Order->findbyid($payment_log['PaymentApiLog']['type_id']);
	        if ($total_fee != $order_total['Order']['total'])
	        {
	            return false;
	        }
        }*/
		
		if($payment_log['PaymentApiLog']['type'] > 0){
            $account = $db->UserAccount->findbyid($payment_log['PaymentApiLog']['type_id']);
	        if ($total_fee != $account['UserAccount']['amount'])
	        {
	            return false;
	        }
		}else{
            $order_total = $db->Order->findbyid($payment_log['PaymentApiLog']['type_id']);
	        if ($total_fee != $order_total['Order']['total'])
	        {
	            return false;
	        }
		}
		
		
		
        /* 如果pay_result大于0则表示支付失败 */
        if ($pay_result > 0)
        {
            return false;
        }
        
        /* 检查支付的金额是否相符 */
     //   if (!check_money($log_id, $total_fee / 100))
     //   {
     //       return false;
      //  }

        /* 检查数字签名是否正确 */
        $sign_text  = "cmdno=" . $cmd_no . "&pay_result=" . $pay_result .
                          "&date=" . $bill_date . "&transaction_id=" . $transaction_id .
                            "&sp_billno=" . $sp_billno . "&total_fee=" . $total_fee .
                            "&fee_type=" . $fee_type . "&attach=" . $attach .
                            "&key=" . $tenpay_key;
        $sign_md5 = strtoupper(md5($sign_text));
        if ($sign_md5 != $sign)
        {
            return false;
        }
        else
        {
			if($payment_log['PaymentApiLog']['type'] > 0){
			$db->update_order($payment_log,$order_total);        			           

			 return true;			
			}else{
			$db->update_balance($payment_log,$account);        			           
			 return true;			
			}
        }
    }
}
?>