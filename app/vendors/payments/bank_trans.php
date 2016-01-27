<?php
class bank_trans {
	
	var $gateway;           //网关地址
    var $_key;			  	//安全校验码
    var $partner;           //合作伙伴ID
    var $sign_type='MD5';         //签名方式 系统默认
    var $mysign;            //签名结果
    var $_input_charset='UTF-8';    //字符编码格式
    var $transport='https';         //访问模式
    
	var $response=array();
	var $config;
	var $config_cn= array(
		"bank"=>array(
			"name" => "银行",
			"type" => "textarea"
		)
	);
	var $config_en= array(
		"bank"=>array(
			"name" => "Alipay Account",
			"type" => "textarea"
		)
	);    
	function bank_trans(){
    	
    }

    function __construct(){
        $this->bank_trans();
    }
    
    function go($order, $payment_config){

		$total_fee    = $order['amount'];	//订单总金额，显示在支付宝收银台里的“应付总额”里

		//扩展功能参数——默认支付方式
		/*
		$pay_mode	  = $_POST['pay_bank'];
		if ($pay_mode == "directPay") {
			$paymethod    = "directPay";	//默认支付方式，四个值可选：bankPay(网银); cartoon(卡通); directPay(余额); CASH(网点支付)
			$defaultbank  = "";
		}
		else {
			$paymethod    = "bankPay";		//默认支付方式，四个值可选：bankPay(网银); cartoon(卡通); directPay(余额); CASH(网点支付)
			$defaultbank  = $pay_mode;		//默认网银代号，代号列表见http://club.alipay.com/read.php?tid=8681379
		}*/
		$paymethod = "";
		$defaultbank  = "";
		//扩展功能参数——防钓鱼
		//请慎重选择是否开启防钓鱼功能
		//exter_invoke_ip、anti_phishing_key一旦被使用过，那么它们就会成为必填参数
		//开启防钓鱼功能后，服务器、本机电脑必须支持远程XML解析，请配置好该环境。
		//若要使用防钓鱼功能，请打开class文件夹中alipay_function.php文件，找到该文件最下方的query_timestamp函数，根据注释对该函数进行修改
		//建议使用POST方式请求数据
		$anti_phishing_key  = '';			//防钓鱼时间戳
		$exter_invoke_ip = '';				//获取客户端的IP地址，建议：编写获取客户端IP地址的程序

		//扩展功能参数——其他
		$extra_common_param = '';			//自定义参数，可存放任何内容（除=、&等特殊字符外），不会显示在页面上
		$buyer_email		= '';			//默认买家支付宝账号

		//扩展功能参数——分润(若要使用，请按照注释要求的格式赋值)
		$royalty_type		= "";			//提成类型，该值为固定值：10，不需要修改
		$royalty_parameters	= "";
		
		$show_url = '';
		$body = '';
		
		$logistics_fee		= "0.00";				//物流费用，即运费。
		$logistics_type		= "EXPRESS";			//物流类型，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
		$logistics_payment	= "SELLER_PAY";			//物流支付方式，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
		$x='';
		if($payment_config['foo']==3)
			$x='create_partner_trade_by_buyer';
		else
			$x='trade_create_by_buyer';

		$parameter = array(
		        "service"			=> $x,	//接口名称，不需要修改
		        "payment_type"		=> "1",               			//交易类型，不需要修改

		        "partner"			=> $payment_config['partner'],
		        "seller_email"		=> $payment_config['account'],
		        "return_url"		=> $payment_config['return_url'],
		        "notify_url"		=> $payment_config['return_url'],
		        "_input_charset"	=> $this->_input_charset,
		        "show_url"			=> $show_url,

		        //从订单数据中动态获取到的必填参数
		        "out_trade_no"		=> $order['id'],
		        "subject"			=> $order['subject'],
		        "body"				=> $body,
		        "price"			=> $total_fee,

		        //扩展功能参数——网银提前
		        "paymethod"			=> $paymethod,
		        "defaultbank"		=> $defaultbank,

		        //扩展功能参数——防钓鱼
		        "anti_phishing_key"	=> $anti_phishing_key,
				"exter_invoke_ip"	=> $exter_invoke_ip,

				//扩展功能参数——自定义参数
				"buyer_email"		=> $buyer_email,
		        "extra_common_param"=> $extra_common_param,
				
				//extra
				"logistics_fee"=>$logistics_fee,		
				"logistics_type"=>$logistics_type,		
				"logistics_payment"=>$logistics_payment,	
				
				'quantity'=>1,
				
				//扩展功能参数——分润
		        "royalty_type"		=> $royalty_type,
		        "royalty_parameters"=> $royalty_parameters
		);

		//构造请求函数
		$this->service($parameter,$payment_config['key']);
		$sHtmlText = $this->build_form();
		return $sHtmlText;

    }
    
    function go2($order, $payment_config){
		$sHtmlText = $this->build_form2($payment_config['co']);
		return $sHtmlText;

    }   


    /**构造函数
	*从配置文件及入口文件中初始化变量
	*$parameter 需要签名的参数数组
	*$key 安全校验码
    */
    function service($parameter,$key) {
        $this->gateway	      = "https://mapi.alipay.com/gateway.do?";
        //echo $key;
        $this->_key  = $key;
        $this->parameter      = para_filter($parameter);

        //设定_input_charset的值,为空值的情况下默认为GBK
        if($parameter['_input_charset'] == '')
            $this->parameter['_input_charset'] = 'GBK';

        $this->_input_charset   = $this->parameter['_input_charset'];
		
        //获得签名结果
        $sort_array   = arg_sort($this->parameter);    //得到从字母a到z排序后的签名参数数组
        $this->mysign = build_mysign($sort_array,$this->_key,$this->sign_type);
    }

    /********************************************************************************/

    /**构造表单提交HTML
	*return 表单提交HTML文本
     */
    function build_form() {
		//GET方式传递
        $sHtml = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/><form id='alipaysubmit' name='alipaysubmit' action='".$this->gateway."_input_charset=".$this->parameter['_input_charset']."' method='get'>";
		//POST方式传递（GET与POST二必选一）
		//$sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->gateway."_input_charset=".$this->parameter['_input_charset']."' method='post'>";

        while (list ($key, $val) = each ($this->parameter)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }

        $sHtml = $sHtml."<input type='hidden' name='sign' value='".$this->mysign."'/>";
        $sHtml = $sHtml."<input type='hidden' name='sign_type' value='".$this->sign_type."'/>";

		//submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<input type='submit' value='支付宝确认付款'></form>";
		
		$sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
		
        return $sHtml;
    }
    
    
    /********************************************************************************/

    /**构造表单提交HTML
	*return 表单提交HTML文本
     */
    function build_form2($str) {
		//GET方式传递
        $sHtml = $str;

        return $sHtml;
    }
    
    
    /**构造函数
	*从配置文件中初始化变量
	*$partner 合作身份者ID
	*$key 安全校验码
	*$sign_type 签名类型
	*$_input_charset 字符编码格式
	*$transport 访问模式
     */
    function notify($config) {

        if($this->transport == "https") {
            $this->gateway = "https://www.alipay.com/cooperate/gateway.do?";
        }else {
            $this->gateway = "http://notify.alipay.com/trade/notify_query.do?";
        }
        $this->partner          = $config['partner'];
        $this->_key    = $config['key'];
        $this->mysign           = "";
        $this->response = $_GET;
        
    }

    /********************************************************************************/

    /**对notify_url的认证
	*返回的验证结果：true/false
     */
    function notify_verify() {
        //获取远程服务器ATN结果，验证是否是支付宝服务器发来的请求
        if($this->transport == "https") {
            $veryfy_url = $this->gateway. "service=notify_verify" ."&partner=" .$this->partner. "&notify_id=".$_POST["notify_id"];
        } else {
            $veryfy_url = $this->gateway. "partner=".$this->partner."&notify_id=".$_POST["notify_id"];
        }
        $veryfy_result = $this->get_verify($veryfy_url);
		
        //生成签名结果
		if(empty($_POST)) {							//判断POST来的数组是否为空
			return false;
		}
		else {		
			$post          = para_filter($_POST);	    //对所有POST返回的参数去空
			$sort_post     = arg_sort($post);	    //对所有POST反馈回来的数据排序
			$this->mysign  = build_mysign($sort_post,$this->_key,$this->sign_type);   //生成签名结果
	
			//写日志记录
			log_result("veryfy_result=".$veryfy_result."\n notify_url_log:sign=".$_POST["sign"]."&mysign=".$this->mysign.",".create_linkstring($sort_post));
	
			//判断veryfy_result是否为ture，生成的签名结果mysign与获得的签名结果sign是否一致
			//$veryfy_result的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//mysign与sign不等，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_POST["sign"]) {
				return true;
			} else {
				return false;
			}
		}
    }

    /********************************************************************************/

    /**对return_url的认证
	*return 验证结果：true/false
     */
    function return_verify() {
        //获取远程服务器ATN结果，验证是否是支付宝服务器发来的请求
        if($this->transport == "https") {
            $veryfy_url = $this->gateway. "service=notify_verify" ."&partner=" .$this->partner. "&notify_id=".$_GET["notify_id"];
        } else {
            $veryfy_url = $this->gateway. "partner=".$this->partner."&notify_id=".$_GET["notify_id"];
        }
        $veryfy_result = $this->get_verify($veryfy_url);

        //生成签名结果
		if(empty($_GET)) {							//判断GET来的数组是否为空
			return false;
		}
		else {
			$get          = para_filter($_GET);	    //对所有GET反馈回来的数据去空
			unset($get['url']);
			$sort_get     = arg_sort($get);		    //对所有GET反馈回来的数据排序
			//pr($sort_get);
			//pr($this);
			$this->mysign  = build_mysign($sort_get,$this->_key,$this->sign_type);    //生成签名结果
	
			//写日志记录
			//log_result("veryfy_result=".$veryfy_result."\n return_url_log:sign=".$_GET["sign"]."&mysign=".$this->mysign."&".create_linkstring($sort_get));
	
			//判断veryfy_result是否为ture，生成的签名结果mysign与获得的签名结果sign是否一致
			//$veryfy_result的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//mysign与sign不等，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_GET["sign"]) {            
				return true;
			}else {
				return false;
			}
		}
    }

    /********************************************************************************/

    /**获取远程服务器ATN结果
	*$url 指定URL路径地址
	*return 服务器ATN结果集
     */
    function get_verify($url,$time_out = "60") {
        $urlarr     = parse_url($url);
        $errno      = "";
        $errstr     = "";
        $transports = "";
        if($urlarr["scheme"] == "https") {
            $transports = "ssl://";
            $urlarr["port"] = "443";
        } else {
            $transports = "tcp://";
            $urlarr["port"] = "80";
        }
        //pr($urlarr);
        $fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
        if(!$fp) {
            die("ERROR: $errno - $errstr<br />\n");
        } else {
            fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
            fputs($fp, "Host: ".$urlarr["host"]."\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $urlarr["query"] . "\r\n\r\n");
            while(!feof($fp)) {
                $info[]=@fgets($fp, 1024);
            }
            fclose($fp);
            $info = implode(",",$info);
            pr($errstr);
            return $info;
        }
    }
	
	function get_track_id(){
		//pr($this->response);
//		if(isset($this->response['out_trade_no']))
			return $this->response['out_trade_no'];
//		else
//			return $this->response['trade_no'];
		
	}
	
	function get_alipay_no(){
		return $this->response['trade_no'];		
	}
	
	function check_amount($amount){
		return ($this->response['total_fee'] == $amount);
	}
	
	function get_trade_status(){
		
		if($this->response['trade_status'] == 'WAIT_SELLER_SEND_GOODS' || $_GET['trade_status'] == 'TRADE_FINISHED'|| $_GET['trade_status'] == 'TRADE_SUCCESS'){
			return 1;

        }elseif($_GET['trade_status'] == 'TRADE_PENDING'){
			return 2;
		}else{
			return 0;
		}
	}
	
	function get_remark() {
		return $this->response['trade_no']."[".$this->response['buyer_email']."]".$this->response['trade_status'];
	}
	
	
	function response($msg){
		return $msg; 	
	} 
	
}



/**生成签名结果
 *$array要签名的数组
 *return 签名结果字符串
*/
function build_mysign($sort_array,$key,$sign_type = "MD5") {
    $prestr = create_linkstring($sort_array);     	//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
    $prestr = $prestr.$key;							//把拼接后的字符串再与安全校验码直接连接起来
    $mysgin = sign($prestr,$sign_type);			    //把最终的字符串签名，获得签名结果
    return $mysgin;
}	

/********************************************************************************/

/**把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	*$array 需要拼接的数组
	*return 拼接完成以后的字符串
*/
function create_linkstring($array) {
    $arg  = "";
    while (list ($key, $val) = each ($array)) {
        $arg.=$key."=".$val."&";
    }
    $arg = substr($arg,0,count($arg)-2);		     //去掉最后一个&字符
    return $arg;
}

/********************************************************************************/

/**除去数组中的空值和签名参数
	*$parameter 签名参数组
	*return 去掉空值与签名参数后的新签名参数组
 */
function para_filter($parameter) {
    $para = array();
    while (list ($key, $val) = each ($parameter)) {
        if($key == "sign" || $key == "sign_type" || $val == "")continue;
        else	$para[$key] = $parameter[$key];
    }
    return $para;
}

/********************************************************************************/

/**对数组排序
	*$array 排序前的数组
	*return 排序后的数组
 */
function arg_sort($array) {
    ksort($array);
    reset($array);
    return $array;
}

/********************************************************************************/

/**签名字符串
	*$prestr 需要签名的字符串
	*return 签名结果
 */
function sign($prestr,$sign_type) {
    $sign='';
    if($sign_type == 'MD5') {
        $sign = md5($prestr);
    }elseif($sign_type =='DSA') {
        //DSA 签名方法待后续开发
        die("DSA 签名方法待后续开发，请先使用MD5签名方式");
    }else {
        die("支付宝暂不支持".$sign_type."类型的签名方式");
    }
    return $sign;
}

/********************************************************************************/

// 日志消息,把支付宝返回的参数记录下来
// 请注意服务器是否开通fopen配置
function  log_result($word) {
    $fp = fopen("log.txt","a");
    flock($fp, LOCK_EX) ;
    fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}	

/********************************************************************************/

/**实现多种字符编码方式
	*$input 需要编码的字符串
	*$_output_charset 输出的编码格式
	*$_input_charset 输入的编码格式
	*return 编码后的字符串
 */
function charset_encode($input,$_output_charset ,$_input_charset) {
    $output = "";
    if(!isset($_output_charset) )$_output_charset  = $_input_charset;
    if($_input_charset == $_output_charset || $input ==null ) {
        $output = $input;
    } elseif (function_exists("mb_convert_encoding")) {
        $output = mb_convert_encoding($input,$_output_charset,$_input_charset);
    } elseif(function_exists("iconv")) {
        $output = iconv($_input_charset,$_output_charset,$input);
    } else die("sorry, you have no libs support for charset change.");
    return $output;
}

/********************************************************************************/

/**实现多种字符解码方式
	*$input 需要解码的字符串
	*$_output_charset 输出的解码格式
	*$_input_charset 输入的解码格式
	*return 解码后的字符串
 */
function charset_decode($input,$_input_charset ,$_output_charset) {
    $output = "";
    if(!isset($_input_charset) )$_input_charset  = $_input_charset ;
    if($_input_charset == $_output_charset || $input ==null ) {
        $output = $input;
    } elseif (function_exists("mb_convert_encoding")) {
        $output = mb_convert_encoding($input,$_output_charset,$_input_charset);
    } elseif(function_exists("iconv")) {
        $output = iconv($_input_charset,$_output_charset,$input);
    } else die("sorry, you have no libs support for charset changes.");
    return $output;
}

/*********************************************************************************/

/**用于防钓鱼，调用接口query_timestamp来获取时间戳的处理函数
注意：由于低版本的PHP配置环境不支持远程XML解析，因此必须服务器、本地电脑中装有支持DOMDocument、SSL的PHP配置环境。建议本地调试时使用PHP开发软件
*$partner 合作身份者ID
*return 时间戳字符串
*/
function query_timestamp($partner) {
    $URL = "https://mapi.alipay.com/gateway.do?service=query_timestamp&partner=".$partner;
	$encrypt_key = "";
//若要使用防钓鱼，请取消下面的4行注释
//    $doc = new DOMDocument();
//    $doc->load($URL);
//    $itemEncrypt_key = $doc->getElementsByTagName( "encrypt_key" );
//    $encrypt_key = $itemEncrypt_key->item(0)->nodeValue;
//    return $encrypt_key;
}
  

?>