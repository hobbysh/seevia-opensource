<?php
class account_pay{
	//余额支付
	var $response=array();

	function account_pay(){
    	
    }

    function __construct(){
        $this->account_pay();
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

	function build_form2($order){
		$sHtmlText="";


		$sHtmlText.='<form name="payform" target="_blank" id="payform" style="text-align:center;" action="" method="post" >';
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
		$sHtmlText.= "</form>";	

		return $sHtmlText;
	}

}