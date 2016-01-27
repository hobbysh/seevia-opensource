<?php

/*****************************************************************************
 * Seevia 用户资金
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为BalancesController的结算控制器.
 */
class BalancesController extends AppController
{
    /*
    *@var $name
    *@var $helpers
    *@var $components
    *@var $uses
*/
    public $name = 'Balances';
    public $components = array('Pagination','RequestHandler'); // Added 
    public $helpers = array('Pagination'); // Added 
    public $uses = array('Order','User','Payment','UserBalanceLog','UserAccount','PaymentApiLog');
    public $layout = 'default_full';

    /**
     *函数 index 用于进入资金日志页面.
     */
    public function index($page = 1)
    {
        //登录验证
        $this->checkSessionUser();
        $this->page_init();                        //页面初始化 
        $this->layout = 'usercenter';            //引入模版
        //页面标题
        $this->pageTitle = $this->ld['account_balance'].' - '.$this->configs['shop_title'];
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => __($this->ld['account_balance'], true),'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $user_id = $_SESSION['User']['User']['id'];
        //虚拟账户交易记录
        $user_account = $this->UserAccount->user_account($user_id);
        $this->set('user_account', $user_account);
        $condition = '';
        $condition['UserBalanceLog.user_id'] = $user_id;
         //分页start
        //get参数
        $limit = 5;
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'balances', 'action' => 'index', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'UserBalanceLog');
        $page = $this->Pagination->init($condition, $parameters, $options); // Added
        //分页end
        //虚拟账户交易记录 
        $my_balance_list = $this->UserBalanceLog->find('all', array('conditions' => $condition, 'limit' => $limit, 'page' => $page));
     //	$user_account=$this->UserAccount->find("all",array("conditions"=>$condition,"limit"=>$limit,"page"=>$page));
        //用户信息
        $user_info = $this->User->find_user_by_id($_SESSION['User']['User']['id']);
        //付款方式
        $payments = $this->Payment->availables();
        $this->set('my_balance_list', $my_balance_list);
        $this->set('payments', $payments);
        $this->set('my_balance', $user_info['User']['balance']);
    }

    /**
     *函数user_balance_deposit 用于资金存放.
     */
    public function balance_deposit_old()
    {
    	 if (isset($_POST)) {
        	$_POST=$this->clean_xss($_POST);
        }
        $no_error = 1;
        if (!isset($_POST['is_ajax'])) {
            if ($_POST['amount_num'] == '') {
                $no_error = 0;//larger_zero
                $_POST['msg'] = $this->ld['supply'].$this->ld['amount'].$this->ld['can_not_empty'];
            } elseif ($_POST['amount_num'] == 0) {
                $no_error = 0;//larger_zero
                $_POST['msg'] = $this->ld['supply'].$this->ld['amount'].$this->ld['larger_zero'];
            } elseif (!isset($_POST['payment_id']) || $_POST['payment_id'] == '' || $_POST['payment_id'] < 1) {
                $no_error = 0;
                $_POST['msg'] = $this->ld['please_select'].$this->ld['payment'];
            } else {
                $_POST['money'] = $_POST['amount_num'];
            }
            $url_format = isset($_POST['msg']) ? $_POST['msg'] : '';
        }
        if (!(isset($_POST['msg']))) {
            $modified = date('Y-m-d H:i:s');
            $user_id = $_SESSION['User']['User']['id'];
            $user_info = $this->User->find_user_by_id($user_id);
            $user_money = $user_info['User']['balance'] + $_POST['money'];
            $amount_money = $_POST['money'];
            $payment_id = $_POST['payment_id'];
            $pay = $this->Payment->get_payment_id($payment_id);
            $pay_php = $pay['Payment']['php_code'];
            $account_info = array(
                            'id' => '',
                            'user_id' => $user_id,
                            'amount' => $amount_money,
                            'payment' => $payment_id,
                            'status' => 0,
                            );
            $this->UserAccount->save($account_info);
            $account_id = $this->UserAccount->id;
            $pay_log = array();
            $pay_log['id'] = '';
            $pay_log['payment_code'] = $pay['Payment']['code'];
            $pay_log['type'] = 1;
            $pay_log['type_id'] = $account_id;
            $pay_log['amount'] = $amount_money;
            $pay_log['is_paid'] = 0;
            $this->PaymentApiLog->save($pay_log);
            $log_id = $this->PaymentApiLog->id;
            $pay_created = $this->PaymentApiLog->findbyid($log_id);
            $order = array(
                    'total' => $amount_money,
                    'log_id' => $log_id,
                    'created' => $pay_created['PaymentApiLog']['created'],
                    );
            $message = array(
            'msg' => $this->ld['supply_method_is'].':'.$pay['PaymentI18n']['name'],
            'url' => '',
        );
            $_POST['msg'] = $this->ld['supply_method_is'].':'.$pay['PaymentI18n']['name'];
            $str = '$pay_class = new '.$pay['Payment']['code'].'();';
            if ($pay['Payment']['code'] == 'bank' || $pay['Payment']['code'] == 'post' || $pay['Payment']['code'] == 'COD' ||  $pay['Payment']['code'] == 'account_pay') {
                $pay_message = $pay['PaymentI18n']['description'];
                $url_format = $pay_message;
                $this->set('pay_message', $pay_message);
            } elseif ($pay['Payment']['code'] == 'alipay') {
                eval($pay_php);
                eval($str);
                $url = $pay_class->get_code($order, $pay, $this);
                $url_format = "<input type=\"button\" onclick=\"window.open('".$url."')\" value=\"".$this->ld['alipay_pay_immedia'].'" />';
                $this->set('pay_button', $url);
            } else {
                eval($pay_php);
                eval($str);
                $url = $pay_class->get_code($order, $pay, $this);
                $url_format = $url;
                $this->set('pay_message', $url);
            }
        } else {
            $message = array(
        'msg' => $_POST['msg'],
        'url' => '',
        );
        }
        if (!isset($_POST['is_ajax'])) {
            $this->page_init();
            $this->pageTitle = $_POST['msg'];
            $flash_url = '/balances';
            $this->flash($url_format, $flash_url, 10);
        }
        $this->set('result', $message);
        $this->layout = 'ajax';
    }

    /**
     *函数user_balance_deposit 用于支付资金.
     */
    public function user_pay_balance()
    {
        $no_error = 1;
        if ($this->RequestHandler->isPost()) {
            $pay_log = $this->PaymentApiLog->find_payment_log_by_id($_POST['id']);
            $pay = $this->Payment->find_pay_by_code($pay_log['PaymentApiLog']['payment_code']);
            $order_pr = array(
                    'total' => $pay_log['PaymentApiLog']['amount'],
                    'log_id' => $pay_log['PaymentApiLog']['id'],
                    'created' => $pay_log['PaymentApiLog']['created'],
                    );
        //	$result['msg'] = $this->ld['supply'];
                $result['msg'] = $this->ld['supply_method_is'].':'.$pay['PaymentI18n']['name'];
            $pay_php = $pay['Payment']['php_code'];
            $str = '$pay_class = new '.$pay['Payment']['code'].'();';
            if ($pay['Payment']['code'] == 'bank' || $pay['Payment']['code'] == 'post' || $pay['Payment']['code'] == 'COD' ||  $pay['Payment']['code'] == 'account_pay') {
                $pay_message = $pay['PaymentI18n']['description'];
                $url_format = $pay_message;
                $this->set('pay_message', $pay_message);
            } elseif ($pay['Payment']['code'] == 'alipay') {
                eval($pay_php);
                eval($str);
                $url = $pay_class->get_code($order_pr, $pay, $this);
                $url_format = "<input type=\"button\" onclick=\"window.open('".$url."')\" value=\"".$this->ld['alipay_pay_immedia'].'" />';
                $this->set('pay_button', $url);
            } else {
                eval($pay_php);
                eval($str);
                $url = $pay_class->get_code($order_pr, $pay, $this);
                $url_format = $url;
                $this->set('pay_message', $url);
            }
            $result['type'] = 0;
        }
        if (!isset($_POST['is_ajax'])) {
            $this->page_init();
            $this->pageTitle = $result['msg'];
            $flash_url = $this->server_host.$this->user_webroot.'balances';
            $this->flash($url_format, $flash_url, 10);
        }
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

	
	//订单检查
	public function check_order(){
	    	Configure::write('debug', 0);
        	$this->layout = 'ajax';
		$result="NO";
		$orfo = $this->Order->find('first', array('conditions' => array('Order.order_code' => $_POST["order_id"],'Order.payment_status' =>'2','Order.status' =>'1')));
	    	if (!empty($orfo)) {
	    		$result=$orfo['Order']['id'];
	    	}
        	die($result);
	}

    /**
     *函数balance_deposit 用于资金存放.
     */
    public function balance_deposit2()
    {
        $this->pageTitle = '支付 - '.$this->configs['shop_title'];
        if (!empty($_GET['code'])&&!empty($_GET['other_data'])){
            $other_data_str=$_GET['other_data'];
            $other_data_arr=explode("_",$other_data_str);
            $_POST['amount_num']=isset($other_data_arr[0])?$other_data_arr[0]:0;
            $_POST['payment_id']=isset($other_data_arr[1])?$other_data_arr[1]:0;
            $_POST['invoice']=isset($other_data_arr[2])?$other_data_arr[2]:0;
            $_POST['item_name']=isset($other_data_arr[3])?$other_data_arr[3]:0;
        }
        if (!isset($_POST['amount_num'])||!($_POST['amount_num'] > 0) ||!isset($_POST['payment_id'])|| !($_POST['payment_id'] > 0)) {
            die('参数错误');
        }
        $orderid = $_POST['invoice'];
        $order_code = $_POST['item_name'];
        if (constant('Product') == 'AllInOne') {
            $orfo = $this->Order->find('first', array('conditions' => array('Order.id' => $orderid)));
            if (!empty($orfo)) {
                $orfo['Order']['sub_pay'] = $_POST['payment_id'];
                $this->Order->save($orfo['Order']);
            }
        }
        $modified = date('Y-m-d H:i:s');
        $user_id = $_SESSION['User']['User']['id'];
        $user_info = $this->User->findbyid($user_id);
        $user_money = $user_info['User']['balance'] + $_POST['amount_num'];
        $amount_money = $_POST['amount_num'];
        $payment_id = $_POST['payment_id'];
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.id' => $payment_id)));
        $this->Cookie->write('pay_type', $payment_id);
        $account_info = array(
                                'user_id' => $user_id,
                                'amount' => $amount_money,
                                'payment' => $payment_id,
                                'status' => 0,
                                );
        $this->UserAccount->save($account_info);
        $account_id = $this->UserAccount->id;
        $order_id = isset($_SESSION['order']['ever_id']) ? $_SESSION['order']['ever_id'] : $account_id;
        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
	    try {
	        $payment_config = unserialize($payment['Payment']['config']);
	        if($payment['Payment']['code']=="weixinpay"){
		                $this->layout = 'default';
		                $amt=$amount_money*100;
		                $wechatpay_type=false;
		                if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		                    App::import('Vendor', 'Weixinpay', array('file' => 'WxPayPubHelper.php'));
		                    $jsApi = new JsApi_pub($payment_config['APPID'],$payment_config['MCHID'],$payment_config['KEY'],$payment_config['APPSECRET']);
		                    if (empty($_GET['code'])){
		                        $request_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		                        $other_data=$amount_money."_".$payment_id."_".$orderid."_".$order_code;
		                        $request_url.="?other_data=".$other_data;
		                		//触发微信返回code码
		                		$wechat_pay_url = $jsApi->createOauthUrlForCode($request_url);
		                		Header("Location: $wechat_pay_url"); 
		                	}else
		                	{
		                		//获取code码，以获取openid
		                	    $code = $_GET['code'];
		                		$jsApi->setCode($code);
		                		$openid = $jsApi->getOpenId();
		                	}
		                    if(!empty($openid)){
		                        $unifiedOrder = new UnifiedOrder_pub($payment_config['APPID'],$payment_config['MCHID'],$payment_config['KEY'],$payment_config['APPSECRET']);
		                        $unifiedOrder->setParameter("openid",$openid);//商品描述
		                        $unifiedOrder->setParameter("body",$order_code);//商品描述
		                    	//自定义订单号，此处仅作举例
		                    	$timeStamp = time();
		                    	$out_trade_no = $order_code;
		                    	$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
		                    	$unifiedOrder->setParameter("total_fee",$amt);//总金额
		                    	$unifiedOrder->setParameter("notify_url",'http://'.$host.$this->webroot.'responds/weixin_notify');//通知地址
		                    	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		                        $prepay_id = $unifiedOrder->getPrepayId();
		                        $jsApi->setPrepayId($prepay_id);
		                        $jsApiParameters = $jsApi->getParameters();
		                        if(!empty($jsApiParameters)){
		                            $json_result=json_decode($jsApiParameters);
		                            $code_url = isset($json_result->paySign)?$jsApiParameters:'';
		                            $this->set('url2', $code_url);
		                        }
		                    }else{
		                        throw new SDKRuntimeException("支付失败,OpenId 获取失败");
		                    }
		                }else{
					$this->layout="ajax";
					$wechatpay_type=true;
					App::import('Vendor', 'Weixinpay', array('file' => 'WxPay.Api.php'));
					App::import('Vendor', 'Phpqcode', array('file' => 'phpqrcode.php'));
					$input = new WxPayUnifiedOrder();
					$notify = new NativePay();
					$input->SetKey($payment_config['KEY']);
					$input->SetBody($order_code);
					$input->SetAttach($order_code);
					$input->SetOut_trade_no($order_code);
					$input->SetAppid($payment_config['APPID']);
					$input->SetMch_id($payment_config['MCHID']);
					$input->SetTotal_fee($amt);
					$input->SetTime_start(date("YmdHis"));
					$input->SetTime_expire(date("YmdHis", time() + 600));
					$input->SetGoods_tag($order_code);
					$input->SetNotify_url('http://'.$host.$this->webroot.'responds/weixin_notify');
					$input->SetTrade_type("NATIVE");
					$input->SetProduct_id($order_code);
					$result = $notify->GetPayUrl($input);
					$url2 = isset($result["code_url"])?$result["code_url"]:'';
					$this->set('url2', $url2);
				}
					$this->set('order_code',$order_code);
					$this->set('wechatpay_type',$wechatpay_type);
			}else{
				$pay_form_txt="";
				
				$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
	        		if($this->RequestHandler->isMobile()&&$payment['Payment']['code']=='alipay'){//手机支付宝支付访问
					$payment_api_log = array(
						'payment_code' => $payment['Payment']['code'],
						'type' => 1,
						'type_id' => $orderid,
						'order_id' => $order_code,
						'order_currency' => 'CHY',
						'amount' => $amount_money,
					);
					$this->PaymentApiLog->save($payment_api_log);
					
					$alipay_config=array();
	        			//合作身份者id，以2088开头的16位纯数字
	        			$alipay_config['partner']= isset($payment_config['partner'])?$payment_config['partner']:'';
	        			//收款支付宝账号，一般情况下收款账号就是签约账号
	        			$alipay_config['seller_id']= isset($payment_config['partner'])?$payment_config['partner']:'';
	        			//商户的私钥（后缀是.pen）文件相对路径
					$alipay_config['private_key_path']	= ROOT.'/vendors/payments/alipaywap/key/rsa_private_key.pem';
					//支付宝公钥（后缀是.pen）文件相对路径
					$alipay_config['ali_public_key_path']= ROOT.'/vendors/payments/alipaywap/key/rsa_public_key.pem';
	        			//签名方式 不需修改
					$alipay_config['sign_type']    = strtoupper('RSA');
					//字符编码格式 目前支持 gbk 或 utf-8
					$alipay_config['input_charset']= strtolower('utf-8');
	        			//ca证书路径地址，用于curl中ssl校验
					//请保证cacert.pem文件在当前文件夹目录中
					$alipay_config['cacert']    = ROOT.'/vendors/payments/alipaywap/cacert.pem';
					//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
					$alipay_config['transport']    = 'http';
					if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false&&$payment['Payment']['code']=='alipay') {
						$alipay_config['is_wechat']    = '1';
					}else{
						$alipay_config['is_wechat']    = '0';
					}
					$out_trade_no=$this->PaymentApiLog->id."_".$order_code;
					$alipay_parameter=array(
						"service" => "alipay.wap.create.direct.pay.by.user",
						"partner" => trim($alipay_config['partner']),
						"seller_id" => trim($alipay_config['seller_id']),
						"payment_type"	=> '1',
						"notify_url"	=> 'http://'.$host.$this->webroot.'responds/return_code/'.$payment['Payment']['code'].'/1/wap',
						"return_url"	=>  'http://'.$host.$this->webroot.'responds/return_code/'.$payment['Payment']['code'].'/0/wap',
						"out_trade_no"	=> $out_trade_no,
						"subject"	=> '['.$payment_api_log['order_id'].']'.' - '.$orfo['OrderProduct'][0]['product_name'],
						"total_fee"	=> $amount_money,
						"show_url"	=> '',
						"body"	=> '',
						"it_b_pay"	=> '',
						"extern_token"	=> '',
						"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
					);
					$alipaySubmit_classfile=ROOT."/vendors/payments/alipaywap/alipay_submit.class.php";
					include_once($alipaySubmit_classfile);
					$alipaySubmit = new AlipaySubmit($alipay_config);
					$html_text = $alipaySubmit->buildRequestForm($alipay_parameter,"get", "支付");
					$pay_form_txt=$html_text;
	        		}else{
					App::import('Vendor', 'payments/'.$payment['Payment']['code']);
					$balance_payment = new $payment['Payment']['code']();
					if ($payment['Payment']['is_online'] == 1) {   //在线支付增加api日志
						$payment_api_log = array(
							'payment_code' => $payment['Payment']['code'],
							'type' => 1,
							'type_id' => $orderid,
							'order_id' => $order_code,
							'order_currency' => 'CHY',
							'amount' => $amount_money
						);
						$this->PaymentApiLog->save($payment_api_log);
						$payment_api_log['id'] = $this->PaymentApiLog->id;
						$payment_config['cancel_return'] = 'http://'.$host.$this->webroot;
						$payment_config['return_url'] = 'http://'.$host.$this->webroot.'responds/return_code/'.$payment['Payment']['code'].'/0/pc';
						$payment_config['notify_url'] = 'http://'.$host.$this->webroot.'responds/return_code/'.$payment['Payment']['code'].'/1/pc';
						$payment_config['payerName'] = '['.$payment_api_log['order_id'].']'.' - '.$orfo['OrderProduct'][0]['product_name'];
						$payment_api_log['created'] = date('Y-m-d H:i:s');
						$payment_api_log['subject'] = '['.$payment_api_log['order_id'].']'.' - '.$orfo['OrderProduct'][0]['product_name'];
						$payment_config['payerName'] = '['.$payment_api_log['order_id'].']'.' - '.$orfo['OrderProduct'][0]['product_name'];
						if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false&&$payment['Payment']['code']=='alipay') {
							$payment_config['is_wechat']    = '1';
						}else{
							$payment_config['is_wechat']    = '0';
						}
						$api_code = $balance_payment->go($payment_api_log, $payment_config);
						$pay_form_txt=$api_code;
					} else {
						$this->layout = 'usercenter';
						$this->set('msg', $payment['PaymentI18n']['description']);
					}
	        		}
	        		echo "<style type='text/css'>body{display:none;}</style>";
	        		if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false&&$payment['Payment']['code']=='alipay') {
	        			$this->set('pay_form_txt',$pay_form_txt);
	        		}else{
	        			echo $pay_form_txt;
	        			exit();
	        		}
			}
		} catch (Exception $e) {
	         	echo 'Caught exception: '.$e->getMessage()."\n";
		}
    }

    public function balance_deposit()
    {
        if (!($_POST['amount_num'] > 0) || !($_POST['payment_id'] > 0)) {
            die('参数错误');
        }
        $modified = date('Y-m-d H:i:s');
        $user_id = $_SESSION['User']['User']['id'];
        $user_info = $this->User->findbyid($user_id);
        $user_money = $user_info['User']['balance'] + $_POST['amount_num'];
        $amount_money = $_POST['amount_num'];
        $payment_id = $_POST['payment_id'];
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.id' => $payment_id)));
        $this->Cookie->write('pay_type', $payment_id);
        $account_info = array(
                'user_id' => $user_id,
                'amount' => $amount_money,
                'payment' => $payment_id,
                'status' => 0,
                );
        $this->UserAccount->save($account_info);
        $account_id = $this->UserAccount->id;
        $order_id = isset($_SESSION['order']['ever_id']) ? $_SESSION['order']['ever_id'] : $account_id;
        try {
            $payment_config = unserialize($payment['Payment']['config']);
            App::import('Vendor', 'payments/'.$payment['Payment']['code']);
            $balance_payment = new $payment['Payment']['code']();
            if ($payment['Payment']['is_online'] == 1) {   //在线支付增加api日志
                $payment_api_log = array(
                    'payment_code' => $payment['Payment']['code'],
                    'type' => 1,
                    'type_id' => $account_id,
                    'order_id' => $order_id,
                    'order_currency' => 'CHY',
                    'amount' => $amount_money,
                );
                $this->PaymentApiLog->save($payment_api_log);
//				$ids=$this->PaymentApiLog->find('first',array('conditions'=>array('PaymentApiLog.type_id'=>$orderid),'fields'=>array('PaymentApiLog.id')));
                $payment_api_log['id'] = $this->PaymentApiLog->id;
                $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
                $payment_config['cancel_return'] = 'http://'.$host.$this->webroot;
                $payment_config['return_url'] = 'http://'.$host.$this->webroot.'responds/return_code/'.$payment['Payment']['code'];
                $payment_api_log['subject'] = '['.$payment_api_log['order_id'].']'.' - '.'充值'.$amount_money.'元';
                $api_code = $balance_payment->go($payment_api_log, $payment_config);
                echo  $api_code;
                exit();
            } else {
                die();
            }
        } catch (Exception $e) {
            echo 'Caught exception: '.$e->getMessage()."\n";
        }
    }
}
