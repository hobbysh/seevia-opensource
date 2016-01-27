<?php
class 99bill{	
	var $gateway='https://www.99bill.com/gateway/recvMerchantInfoAction.htm'; //网关地址 有了version是否还需要
    var $_key;			  				//安全校验码
    var $partner;          			    //合作伙伴ID
    var $transport='https';     		//访问模式
	var $merchantAcctId="";                 //人民币网关账户号///请登录快钱系统获取用户编号，用户编号后加01即为人民币网关账户号。
     		
    var $mysign;                        //签名结果
    var $burl;           			
    var $inputCharset='3';    		//字符编码格式 1代表UTF-8; 2代表GBK; 3代表gb2312
    var $language='1';					//语言种类.固定选择值。只能选择1、2、3,1代表中文；2代表英文,默认值为1
    var $sign_type='1'					//签名类型.固定值,1代表MD5签名,当前版本固定为1
    var $version="v2.0";				//网关版本.固定值，快钱会根据版本号来调用对应的接口处理程序。本代码版本号固定为v2.0 
    var $payerContactType="1";			//支付人联系方式类型.固定选择值,只能选择1,1代表Email
	var $payType="00";					//支付方式.固定选择值,只能选择00、10、11、12、13、14,00：组合支付（网关支付页面显示快钱支持的各种支付方式，推荐使用）10：银行卡支付（网关支付页面只显示银行卡支付）.11：电话银行支付（网关支付页面只显示电话支付）.12：快钱账户支付（网关支付页面只显示快钱账户支付）.13：线下支付（网关支付页面只显示线下支付方式）
	var $redoFlag="0";					//同一订单禁止重复提交标志,固定选择值： 1、0,1代表同一订单号只允许提交1次；0表示同一订单号在没有支付成功的前提下可重复提交多次。默认为0建议实物购物车结算类商户采用0；虚拟产品类商户采用1
	var $pid;                           ///合作伙伴在快钱的用户编号
	
	
	    
	var $response=array();
	var $config= array(
		"account"=>array(
			"name" => "收款帐号",
			"type" => "text"
		),
		"key"=>array(
			"name" => "商户密钥",
			"type" => "text"
		)	
	);
    function 99bill(){
    	
    }

    function __construct(){
        $this->99bill();
    }


    function go($order, $payment_config){		    
		//支付人姓名
		///可为中文或英文字符
		$payerName="";
		//支付人联系方式
		///只能选择Email或手机号
		$payerContact="";
		//商户订单号
		///由字母、数字、或[-][_]组成
		$orderId="";		
		//订单金额
		///以分为单位，必须是整型数字
		///比方2，代表0.02元
		$orderAmount="";			
		//订单提交时间
		///14位数字。年[4位]月[2位]日[2位]时[2位]分[2位]秒[2位]
		///如；20080101010101
		$orderTime=date('YmdHis');
		//商品名称
		///可为中文或英文字符
		$productName="";
		//商品数量
		///可为空，非空时必须为数字
		$productNum="";
		//商品代码
		///可为字符或者数字
		$productId="";
		//商品描述
		$productDesc="";
		//扩展字段1
		///在支付结束后原样返回给商户
		$ext1="";
		//扩展字段2
		///在支付结束后原样返回给商户
		$ext2="";			
		//快钱的合作伙伴的账户号
		///如未和快钱签订代理合作协议，不需要填写本参数
		$pid=""; ///合作伙伴在快钱的用户编号
		
		
		$this->_key=trim($payment_config['kq_key']['value']);
		$this->partner=trim($payment_config['kq_account']['value']);
		$parameter=array(
		       '$merchant_acctid'    => trim($this->partner);                 			//人民币账号 不可空
		       '$key'               = trim($this->_key);
		       'inputCharset'      = 1;                                                //字符集 默认1=utf-8
		       'page_url'           = $payment_config['return_url'];					  //与bg_url不能同时为空 回调地址
		       'bg_url'             = $payment_config['return_url'];
		       'version'            = $this->version;
		       'language'           = $this->language;
		       'sign_type'          = $this->sign_type;                                 //签名类型 不可空 固定值 1:md5
		       'payer_name'         = $payment_config['payerName'];
		       'payer_contact_type' = $this->payerContactType;
		       'payer_contact'      = $payerContact;
		       'orderId'           = $order['log_id'];                                    //商户订单号 不可空
		       'orderAmount'       = $order['total'] * 100;                        		  //商户订单金额 不可空
		       'order_time'         = $this->local_date('YmdHis',$db,$order['created']);            //商户订单提交时间 不可空 14位
		       'product_name'       = '';
		       'productNum'        = '';
		       'productId'         = '';
		       'product_desc'       = '';
		       'ext1'               = '';
		       'ext2'               = '';
		       'pay_type'           = '00';                                                //支付方式 不可空
		       'bank_id'            = '';
		       'redo_flag'          = '0';
		       'pid'                = '';

		)

		$this->service($parameter);
		return $this->bulid_form();
	}
	
	/**构造函数
	*从配置文件及入口文件中初始化变量
	*$parameter 需要签名的参数数组
    */
	function service($parameter){
		$this->inputCharset=empty($parameter['inputCharset'])?$this->inputCharset:$parameter['inputCharset'];
		$this->burl=$parameter['bg_url'];
		$this->payer_name=$parameter['payer_name'];
		$this->orderId=$parameter['orderId'];
		$this->orderAmount=$parameter['orderAmount'];
		$this->productNum=$parameter['productNum'];
		$this->productId=$parameter['productId'];
		$this->mysign=$this->build_mysign();
		
	}
			
	/**构造表单提交HTML
	*return 表单提交HTML文本
    */
    
    function bulid_form(){
        $def_url  = '<div style="text-align:center"><form name="kqPay" style="text-align:center;" method="post" action="'.$this->gateway.'" target="_blank">';
        $def_url .= "<input type='hidden' name='inputCharset' value='" . $this->inputCharset . "' />";
        $def_url .= "<input type='hidden' name='bgUrl' value='" . $this->burl . "' />";
        $def_url .= "<input type='hidden' name='pageUrl' value='" . $this->burl . "' />";
        $def_url .= "<input type='hidden' name='version' value='" . $this->version . "' />";
        $def_url .= "<input type='hidden' name='language' value='" . $this->language . "' />";
        $def_url .= "<input type='hidden' name='signType' value='" . $this->sign_type . "' />";
        $def_url .= "<input type='hidden' name='signMsg' value='" . $this->mysign . "' />";
        $def_url .= "<input type='hidden' name='merchantAcctId' value='" . $this->merchantAcctId . "' />";
        $def_url .= "<input type='hidden' name='payerName' value='" . $this->$payerName . "' />";
        $def_url .= "<input type='hidden' name='payerContactType' value='" . $this->payerContactType . "' />";
        $def_url .= "<input type='hidden' name='payerContact' value='" . $this->$payerContact . "' />";
        $def_url .= "<input type='hidden' name='orderId' value='" . $this->orderId . "' />";
        $def_url .= "<input type='hidden' name='orderAmount' value='" . $this->orderAmount . "' />";
        $def_url .= "<input type='hidden' name='orderTime' value='" . $this->orderTime . "' />";
        $def_url .= "<input type='hidden' name='productName' value='" . this->productName . "' />";
        $def_url .= "<input type='hidden' name='payType' value='" . $this->payType . "' />";
        $def_url .= "<input type='hidden' name='productNum' value='" . $this->productNum . "' />";
        $def_url .= "<input type='hidden' name='productId' value='" . $this->productId . "' />";
        $def_url .= "<input type='hidden' name='productDesc' value='" . $this->productDesc . "' />";
        $def_url .= "<input type='hidden' name='ext1' value='" . $this->ext1 . "' />";
        $def_url .= "<input type='hidden' name='ext2' value='" . $this->ext2 . "' />";
        $def_url .= "<input type='hidden' name='redoFlag' value='" . $this->redoFlag ."' />";
        $def_url .= "<input type='hidden' name='pid' value='" . $this->pid . "' />";
        $def_url .= "<input type='submit' name='submit' value='" . "立即使用快钱支付" . "' />";
        $def_url .= "</form></div></br>";
		$def_url .=."<script>document.forms['kqPay'].submit();</script>";
        return $def_url;
    }
    
    function build_mysign(){
    	//生成加密签名串
		///请务必按照如下顺序和规则组成加密串！
		$signMsgVal='';
		$signMsgVal=$this->appendParam($signMsgVal,"inputCharset",$this->inputCharset);
		$signMsgVal=$this->appendParam($signMsgVal,"bgUrl",$this->burl);
		$signMsgVal=$this->appendParam($signMsgVal,"version",$this->version);
		$signMsgVal=$this->appendParam($signMsgVal,"language",$this->language);
		$signMsgVal=$this->appendParam($signMsgVal,"signType",$this->sign_type);
		$signMsgVal=$this->appendParam($signMsgVal,"merchantAcctId",$this->merchantAcctId);
		$signMsgVal=$this->appendParam($signMsgVal,"payerName",$this->$payerName);
		$signMsgVal=$this->appendParam($signMsgVal,"payerContactType",$this->payerContactType);
		$signMsgVal=$this->appendParam($signMsgVal,"payerContact",$this->$payerContact);
		$signMsgVal=$this->appendParam($signMsgVal,"orderId",$this->orderId);
		$signMsgVal=$this->appendParam($signMsgVal,"orderAmount",$this->orderAmount);
		$signMsgVal=$this->appendParam($signMsgVal,"orderTime",$this->orderTime);
		$signMsgVal=$this->appendParam($signMsgVal,"productName",$this->productName);
		$signMsgVal=$this->appendParam($signMsgVal,"productNum",$this->productNum);
		$signMsgVal=$this->appendParam($signMsgVal,"productId",$this->productId);
		$signMsgVal=$this->appendParam($signMsgVal,"productDesc",$this->productDesc);
		$signMsgVal=$this->appendParam($signMsgVal,"ext1",$this->ext1);
		$signMsgVal=$this->appendParam($signMsgVal,"ext2",$this->ext2);
		$signMsgVal=$this->appendParam($signMsgVal,"payType",$this->payType);	
		$signMsgVal=appendParam($signMsgVal,"redoFlag",$this->redoFlag);
		$signMsgVal=$this->appendParam($signMsgVal,"pid",$this->pid);
		$signMsgVal=$this->appendParam($signMsgVal,"key",$this->_key);
		$signMsg= strtoupper(md5($signMsgVal));  
		return $signMsg;	
    }
    
    
    
    //功能函数。将变量值不为空的参数组成字符串
	function appendParam($returnStr,$paramId,$paramValue){

		if($returnStr!=""){
			
				if($paramValue!=""){
					
					$returnStr.="&".$paramId."=".$paramValue;
				}
			
		}else{
		
			If($paramValue!=""){
				$returnStr=$paramId."=".$paramValue;
			}
		}
		
		return $returnStr;
	}
	//功能函数。将变量值不为空的参数组成字符串。结束
	
	 /**
     * 响应操作
     */
 	function respond($db)
    {
    	
        $payment = $db->Payment->findbycode('kuaiqian');
        eval($payment['Payment']['config']);    
		$merchant_acctid = $payment_arr['kq_account']['value'];
		$key = $payment_arr['kq_key']['value'];
        $get_merchant_acctid = trim($_REQUEST['merchantAcctId']);
        $pay_result          = trim($_REQUEST['payResult']);
        $version             = trim($_REQUEST['version']);
        $language            = trim($_REQUEST['language']);
        $sign_type           = trim($_REQUEST['signType']);
        $pay_type            = trim($_REQUEST['payType']);
        $bank_id             = trim($_REQUEST['bankId']);
        $order_id            = trim($_REQUEST['orderId']);
        $order_time          = trim($_REQUEST['orderTime']);
        $order_amount        = trim($_REQUEST['orderAmount']);
        $deal_id             = trim($_REQUEST['dealId']);
        $bank_deal_id        = trim($_REQUEST['bankDealId']);
        $deal_time           = trim($_REQUEST['dealTime']);
        $pay_amount          = trim($_REQUEST['payAmount']);
        $fee                 = trim($_REQUEST['fee']);
        $ext1                = trim($_REQUEST['ext1']);
        $ext2                = trim($_REQUEST['ext2']);
        $err_code            = trim($_REQUEST['errCode']);
        $sign_msg            = trim($_REQUEST['signMsg']);

        //生成加密串。必须保持如下顺序。
        $merchant_signmsgval = '';
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"merchantAcctId",$merchant_acctid);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"version",$version);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"language",$language);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"signType",$sign_type);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"payType",$pay_type);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"bankId",$bank_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"orderId",$order_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"orderTime",$order_time);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"orderAmount",$order_amount);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"dealId",$deal_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"bankDealId",$bank_deal_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"dealTime",$deal_time);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"payAmount",$pay_amount);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"fee",$fee);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"ext1",$ext1);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"ext2",$ext2);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"payResult",$pay_result);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"errCode",$err_code);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"key",$key);
        $merchant_signmsg    = md5($merchant_signmsgval);

        //首先对获得的商户号进行比对
        if ($get_merchant_acctid != $merchant_acctid)
        {
            //商户号错误
            return false;
        }

        if (strtoupper($sign_msg) == strtoupper($merchant_signmsg))
        {
            if ($pay_result == 10 || $pay_result == 00)
            {
			 	return true;

            }else{
                //'支付结果失败';
                return false;
            }
        }
        else
        {
            //'密钥校对错误';
            return false;
        }
    }

}