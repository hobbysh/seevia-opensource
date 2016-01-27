<?php

/*****************************************************************************
 * Seevia 用户收藏
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 RespondsController 的回复控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class RespondsController extends AppController
{
    public $name = 'Responds';
    public $helpers = array('Html','Flash');
    public $uses = array('Product','Flash','Payment','ProductAlsobought','UserBalanceLog','UserPointLog','PaymentApiLog','User','UserAccount','MailTemplate','MailSendQueue');
    public $components = array('RequestHandler','Email');

    /**
     *显示页.
     *
     *@param $code
     */
    public function index($code)
    {
        $this->pageTitle = $this->ld['payment_result'].' - '.$this->configs['shop_title'];
        /* start */
        $pay = $this->Payment->find_pay_by_code($code);//添加到model中
        eval($pay['Payment']['php_code']);
        $str = '$pay_class = new '.$pay['Payment']['code'].'();';
        eval($str);

        if ($code == 'moneybookers') {
            if (isset($_GET['status']) && $_GET['status'] == md5('ok'.$_GET['oid']) && isset($_GET['oid'])) {
                $payment_log = $this->PaymentApiLog->find_payment_log_by_id($_GET['oid']);//添加到model中
                if ($payment_log['PaymentApiLog']['type'] > 0) {
                    $account = $this->UserAccount->findbyid($payment_log['PaymentApiLog']['type_id']);//标注
                    $this->update_balance($payment_log, $account);
                }
                if ($payment_log['PaymentApiLog']['type'] < 1 && constant('Product') == 'AllInOne') {
                    $this->loadModel('Order');
                    $this->loadModel('OrderProduct');
                    $this->loadModel('VirtualCard');
                    $order_total = $this->Order->findbyid($payment_log['PaymentApiLog']['type_id']);//标注
                       //$this->virtual_card($order_total);
                    $this->update_order($payment_log, $order_total);
                }
                $msg = $this->ld['successful_to_pay'];
            } else {
                $fail = 1;
                $msg = $this->ld['failure_to_pay'];
                $this->set('fail', $fail);
            }
        } else {
            $result = $pay_class->respond($this);
            if ($result) {
                if ($result === 'is_paid') {
                    $msg = '已成功支付';
                } else {
                    $msg = $this->ld['successful_to_pay'];
                }
            } else {
                $fail = 1;
                $msg = $this->ld['failure_to_pay'];
                $this->set('fail', $fail);
            }
        }
        $this->set('msg', $msg);
        $this->set('languages', LOCALE);
        $this->page_init();
        $this->set('categories_tree', $categories_tree = array());
        $this->set('brands', $brands = array());
        $this->layout = 'default_full';
    }

    /**
     *显示页.
     *
     *@param $code
     */
    public function return_code($code,$is_notify=0,$equipment_type="pc")
    {
        Configure::write('debug',1);
    	 if($is_notify==1){
	 	Configure::write('debug',0);
	}
        $this->pageTitle = '支付完成'.' - '.$this->configs['shop_name'];
        $this->ur_heres[] = array('name' => $this->ld['checkout_center'],'url' => '');
        $price_format = !empty($this->configs['price_format']) ? $this->configs['price_format'] : '￥%s元';
        $payment = $this->Payment->findbycode($code);//添加到model中
        if(empty($payment)){$this->redirect('/');}
        $payment_config = unserialize($payment['Payment']['config']);
        $alipay_amount=0;
        $trade_status = 0;
        if($equipment_type=='wap'&&$code=='alipay'){//手机支付宝支付回调
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
        	
        	$alipaySubmit_classfile=ROOT."/vendors/payments/alipaywap/alipay_notify.class.php";
		include_once($alipaySubmit_classfile);
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		$payment_api_log_id_str = isset($_REQUEST['out_trade_no'])?$_REQUEST['out_trade_no']:0;
		$payment_api_log_id_arr=split("_",$payment_api_log_id_str);
		$payment_api_log_id=$payment_api_log_id_arr[0];//支付记录Id
//		$payment_api_log_id=isset($_REQUEST['out_trade_no'])?$_REQUEST['out_trade_no']:0;
		$verify_result = $alipayNotify->verifyReturn();
		$alipay_amount=isset($_REQUEST['total_fee'])?$_REQUEST['total_fee']:0;
		$alipay_trade_status=isset($_REQUEST['trade_status'])?$_REQUEST['trade_status']:'';
		$trade_status = 0;
		if($alipay_trade_status=='TRADE_FINISHED'||$alipay_trade_status=='TRADE_SUCCESS'){
			$trade_status = 1;
		}
        }else{
        	App::import('Vendor', 'payments/'.$payment['Payment']['code']);
        	$response_payment = new $code();
        	$response_payment->notify($payment_config);
        	$payment_api_log_id = $response_payment->get_track_id();
        	if ($code == 'authorizenet_aim' && isset($_SESSION['aim']) && !empty($_SESSION['aim'])) {
	            $response_payment = unserialize(base64_decode($_SESSION['aim']));
	            unset($_SESSION['aim']);
	        }
	        $trade_status = $response_payment->get_trade_status();
	        if($is_notify==1){
	        	$verify_result = $alipayNotify->notify_verify();
	        }else{
	        	$verify_result = $response_payment->return_verify();
	        }
        }
        $payment_api_log = $this->PaymentApiLog->find('first', array('conditions' => array('PaymentApiLog.id' => $payment_api_log_id)));
        if(empty($payment_api_log)&&$is_notify==0){
        	$this->flash('支付失败', '/pages/home', 60);
        }else if(empty($payment_api_log)&&$is_notify==1){
        	echo "fail";
        	die();
        }
        if ($payment_api_log['PaymentApiLog']['type'] == '2') {
            //充值支付
            $back_url = '/users/deposit';
        } else {
        	$amount_result = false;
        	if(isset($response_payment)){
        		$amount_result = $response_payment->check_amount($payment_api_log['PaymentApiLog']['amount']);
        	}else if(isset($alipay_amount)){
        		$amount_result = floatval($alipay_amount)==floatval($payment_api_log['PaymentApiLog']['amount'])?true:false;
        	}
            //订单支付
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('Order');
                $this->loadModel('OrderProduct');
                $this->loadModel('OrderAction');
                $orderp_info = $this->OrderProduct->find('first', array('conditions' => array('OrderProduct.order_id' => $payment_api_log['PaymentApiLog']['type_id'])));
                $foo = $this->Order->find('first', array('conditions' => array('Order.id' => $payment_api_log['PaymentApiLog']['type_id'])));
                if (!empty($foo)) {
                    $back_url = '/orders/view/'.$payment_api_log['PaymentApiLog']['type_id'];
                }
                $this->set('order_code', $foo['Order']['order_code']);
                $this->set('need_pay', $foo['Order']['total']);
                if ($foo['Order']['referer'] != 'unknow') {
                	
                }
            }
        }
        if (isset($payment_api_log['PaymentApiLog']['is_paid'])) { //获得支付记录id号，判断支付状态
            if ($payment_api_log['PaymentApiLog']['is_paid'] == 1) { //已经标志完成了的
                $response['code'] = '101';
                $response['msg'] = '支付已完成';
            } else {
                //获取完成状态
                if ($trade_status == 1) {
                    if(!$amount_result){
                        //判断金额
                        $response['code'] = '102'; //金额错误，
                        $response['msg'] = '系统错误';
                    } elseif ($verify_result) { //判断签名
                        $response['code'] = '103';//签名错误
                        $response['msg'] = '系统错误';
                    } else {
                        $response['code'] = '0';
                        $response['msg'] = '支付成功 ';//.$response_payment->get_remark();
                    }
                } elseif ($status == 2) {
                    $response['code'] = '202';
                    $response['msg'] = '支付等待 ';//.$response_payment->get_remark();
                } else {
                    $response['code'] = '104';//付款状态错误
                    $response['msg'] = '系统错误';
                }
            }
        } else {
            $response['code'] = '100'; //支付记录号不存在
            $response['msg'] = '系统错误';
        }
        if ($response['code'] == 0) {
            if ($payment_api_log['PaymentApiLog']['type'] == '2') {
                //充值支付
                $user_id = $payment_api_log['PaymentApiLog']['type_id'];
                $add_money = $payment_api_log['PaymentApiLog']['amount'];
                $user_Info = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
                if (!empty($user_Info)) {
                    $user_money = $user_Info['User']['balance'];
                    $user_money = $user_money + $add_money;
                    $user_data['id'] = $user_id;
                    $user_data['balance'] = $user_money;
                    $this->User->save($user_data);
                    $user_Info['User']['balance'] = $user_money;
                    $_SESSION['User'] = $user_Info;
                    $payment_api_log['is_paid'] = '1';
                    $this->PaymentApiLog->save($payment_api_log);
                    //添加资金日志
                    $BalanceLog['UserBalanceLog']['user_id'] = $user_id;
                    $BalanceLog['UserBalanceLog']['amount'] = $payment_api_log['PaymentApiLog']['amount'];
                    $BalanceLog['UserBalanceLog']['admin_user'] = $user_Info['User']['balance'];
                    $BalanceLog['UserBalanceLog']['admin_note'] = '';
                    $BalanceLog['UserBalanceLog']['system_note'] = '用户余额:'.$user_data['balance'].'元';
                    $BalanceLog['UserBalanceLog']['log_type'] = 'B';
                    $BalanceLog['UserBalanceLog']['type_id'] = $payment_api_log['PaymentApiLog']['id'];
                    $BalanceLog['UserBalanceLog']['created'] = date('Y-m-d H:i:s', time());
                    $this->UserBalanceLog->save($BalanceLog);
                }
                $msg = '支付成功';
            } else {
                //订单支付
                if (constant('Product') == 'AllInOne') {
                    $this->loadModel('Order');
                    $this->loadModel('OrderProduct');
                    $this->update_order($payment_api_log, $orderp_info);
                    $this->ex_pay_to($foo, $orderp_info['OrderProduct']['product_name']);
                    $msg = $this->ld['your_order'].':'.$foo['Order']['order_code'].'&nbsp;'.$this->ld['order_total'].':'.sprintf($price_format, $payment_api_log['PaymentApiLog']['amount']).$this->ld['successful_to_pay'];
                    $this->OrderAction->saveAll(array('OrderAction' => array(
		            'order_id' => $foo['Order']['id'],
		            'from_operator_id' => 0,
		            'user_id' => $foo['Order']['user_id'],
		            'order_status' => 1,
		            'payment_status' => 2,
		            'shipping_status' => $foo['Order']['shipping_status'],
		            'action_note' => $this->ld['successful_to_pay'],
		            )));
		      $payment_api_log['is_paid'] = '1';
                }
            }
        } elseif ($response['code'] == '101') {
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('Order');
                $this->loadModel('OrderProduct');
                $this->ex_pay_to($foo, $orderp_info['OrderProduct']['product_name']);
                $msg = $this->ld['your_order'].':'.$foo['Order']['order_code'].'&nbsp;'.$this->ld['order_total'].':'.sprintf($price_format, $payment_api_log['PaymentApiLog']['amount']).$this->ld['successful_to_pay'];
            }
        } else {
            $msg = $this->ld['failure_to_pay'];
        }
        $this->PaymentApiLog->save($payment_api_log);
        $this->Cookie->write('pay_type', $payment_api_log['PaymentApiLog']['payment_code']);
        $this->page_init();
        $this->set('response', $response);
        if($is_notify==0){
	        if (isset($back_url)) {
	            $this->flash($msg, $back_url, 60);
	        } else {
	            $this->flash($msg, '/pages/home', 60);
	        }
        }else{
        	if ($response['code'] == 0||$response['code'] == '101') {
        		echo "success";
        	}else{
        		echo "fail";
        	}
        	die();
        }
    }

    public function update_order2($order)
    {
        //已付款 未发货的商品冻结库存处理
        $this->frozen_quantity($order);
        $now = date('Y-m-d H:i:s');
        $this->Order->updateAll(array('Order.status' => 1, 'Order.payment_status' => 2, 'Order.payment_time' => "'".$now."'"), array('Order.id' => $order));
    }

    public function ex_pay_to($foo, $code)
    {
        if ($foo['Order']['referer'] != 'unknow') {
            @file_get_contents('http://'.$foo['Order']['referer'].'/respond.php?code=paypal2&ioco_id='.$code);
        }
    }

    /**
     *更新点.
     *
     *@param $payment_log
     *@param $account
     */
    public function update_point($payment_log, $account)
    {
        $payment_log['PaymentApiLog']['is_paid'] = '1';
        $this->PaymentApiLog->save($payment_log);
        $balance_log = array(
                                    'id' => '',
                                    'user_id' => $account['UserAccount']['user_id'],
                                    'amount' => $account['UserAccount']['amount'],
                                    'log_type' => 'B',
                                    'system_note' => '用户充值',
                                    'type_id' => $payment_log['PaymentApiLog']['type_id'],
                                    );
        $this->UserBalanceLog->save($balance_log);
        $user_info = $this->User->find_user_by_id($account['UserAccount']['user_id']);//调用model
               $user_info['User']['point'] += $account['UserAccount']['amount'] * $this->configs['buy_point_proportion'];
        $this->User->save($user_info);
        $account['UserAccount']['paid_time'] = date('Y-m-d H:i:s');
        $account['UserAccount']['status'] = 1;
        $this->UserAccount->save($account);
    }

    //虚拟卡发邮件
    /**
    *虚拟卡发邮件.
    *
    *@param $order_total
    */
    public function virtual_card($order_total)
    {
        $order_products = $this->OrderProduct->findallbyorder_id($order_total['Order']['id']);//标注
        $virtualcards_info = '';
        if (isset($order_products) && sizeof($order_products) > 0) {
            foreach ($order_products as $k => $v) {
                if ($v['OrderProduct']['extension_code'] == 'virtual_card') {
                    $VirtualCards = $this->VirtualCard->find_virtual_card_by_id($v['OrderProduct']['product_id'], $v['OrderProduct']['product_quntity']);//调用model
                    if (!empty($VirtualCards)) {
                        foreach ($VirtualCards as $kk => $vv) {
                            $vv['VirtualCard']['is_saled'] = 1;
                            $vv['VirtualCard']['order_id'] = $order_total['Order']['id'];
                            $this->VirtualCard->save($vv['VirtualCard']);
                        /* 解密 */
                        if ($vv['VirtualCard']['crc32'] == 0 || $vv['VirtualCard']['crc32'] == crc32(AUTH_KEY)) {
                            $vv['VirtualCard']['card_sn'] = $this->decrypt($vv['VirtualCard']['card_sn']);
                            $vv['VirtualCard']['card_password'] = $this->decrypt($vv['VirtualCard']['card_password']);
                            $virtualcards_info .= '------------------------------------- <br />';
                            $virtualcards_info .= '卡号：'.$vv['VirtualCard']['card_sn'].'<br />';
                            $virtualcards_info .= '卡片密码：'.$vv['VirtualCard']['card_password'].'<br />';
                            $virtualcards_info .= '截至日期：'.$vv['VirtualCard']['end_date'].'<br />';
                            $virtualcards_info .= '------------------------------------- <br />';
                        }
                        }
                    }
                }
            }
        }
        if (!empty($virtualcards_info)) {
            $consignee = $order_total['Order']['consignee'];//template
                $order_sn = $order_total['Order']['order_code'];//template
                $product_name = '';//template
                $shop_name = $this->configs['shop_name'];//template
                $shop_url = $this->server_host.$this->webroot;//template
                $send_date = date('Y-m-d H:m:s');//template
                //读模板
                $template = 'virtual_vard';
            $template = $this->MailTemplate->find("code = '$template' and status = '1'");//标注
                //模板赋值
                $html_body = $template['MailTemplateI18n']['html_body'];
            eval("\$html_body = \"$html_body\";");
            $text_body = $template['MailTemplateI18n']['text_body'];
            eval("\$text_body = \"$text_body\";");
                //主题赋值
                $title = $template['MailTemplateI18n']['title'];
            eval("\$title = \"$title\";");
            if ($this->configs['enable_auto_send_mail'] == 0) {
                $mailsendqueue = array(
                           'sender_name' => $shop_name,//发送从姓名
                           'receiver_email' => $consignee.';'.$order_total['Order']['email'],//接收人姓名;接收人地址
                         'cc_email' => ';',//抄送人
                         'bcc_email' => ';',//暗送人
                          'title' => $title,//主题 
                           'html_body' => $html_body,//内容
                          'text_body' => $text_body,//内容
                         'sendas' => 'html',
                     );
                $this->MailSendQueue->saveAll(array('MailSendQueue' => $mailsendqueue));//保存邮件队列
            } else {
                $subject = $template['MailTemplateI18n']['title'];
                eval("\$subject = \"$subject\";");
                $mailsendqueue = array(
                        'sender_name' => $shop_name,//发送从姓名
                        'receiver_email' => ';'.$to_email,//接收人姓名;接收人地址
                        'cc_email' => ';',//抄送人
                        'bcc_email' => ';',//暗送人
                        'title' => $subject,//主题 
                        'html_body' => $template_str,//内容
                        'text_body' => $text_body,//内容
                        'sendas' => 'html',
                    );
                $this->Email->send_mail(LOCALE, $this->configs['email_the_way'], $mailsendqueue);
            }
        }
    }

    /**
     *解密.
     *
     *@param $str
     *@param $key
     *
     *@return $coded
     */
    public function decrypt($str, $key = AUTH_KEY)
    {
        $coded = '';
        $keylength = strlen($key);
        $str = base64_decode($str);
        for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength) {
            $coded .= substr($str, $i, $keylength) ^ $key;
        }

        return $coded;
    }

    /**
     *文档.
     *
     *@param $id
     */
    public function test($id = '')
    {
        //$this->pageTitle = $this->ld['payment_result'].' - '.$this->configs['shop_title'];
        if ($id != '') {
            $msg = $this->ld['payment_result'].':'.$this->ld['successfully'];
        } else {
            $fail = 1;
            //$msg = $this->ld['payment_result'].":".$this->ld['failed'];
            $this->set('fail', $fail);
        }
        //$this->set('msg',$msg);
        $this->page_init();
        $this->flash($ld['successfully_registered_into_user_center'], array('controller' => 'users/index'), '');
        //$this->layout = 'default_full';
    }

    /**
     *更新平衡
     *
     *@param $payment_log
     *@param $account
     */
    public function update_balance($payment_log, $account)
    {
        $payment_log['PaymentApiLog']['is_paid'] = '1';
        $this->PaymentApiLog->save($payment_log);
        $balance_log = array(
                                    'id' => '',
                                    'user_id' => $account['UserAccount']['user_id'],
                                    'amount' => $account['UserAccount']['amount'],
                                    'log_type' => 'B',
                                    'system_note' => '用户充值',
                                    'type_id' => $payment_log['PaymentApiLog']['type_id'],
                                    );
        $this->UserBalanceLog->save($balance_log);
        $user_info = $this->User->findbyid($account['UserAccount']['user_id']);//标注
               $user_info['User']['balance'] += $account['UserAccount']['amount'];
        $this->User->save($user_info);
        $account['UserAccount']['paid_time'] = date('Y-m-d H:i:s');
        $account['UserAccount']['status'] = 1;
        $this->UserAccount->save($account);
    }

	//微信支付回调（订单）
	public function weixin_notify(){
		App::import('Vendor', 'Weixinpay', array('file' => 'WxPay.Api.php'));
		$notify = new PayNotifyCallBack();
		$res=$notify->Handle(false);
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		$result = WxPayResults::Init($xml);
		$this->loadModel('Order');
		$this->loadModel('OrderProduct');
		$this->loadModel('OrderAction');
		$orfo = $this->Order->find('first', array('conditions' => array('Order.order_code' => $result["out_trade_no"])));
	    	if (!empty($orfo)) {
			$orfo['Order']['money_paid']=$orfo['Order']['total'];
	    		$this->update_order(array(),$orfo);
	    		$this->OrderAction->saveAll(array('OrderAction' => array(
		            'order_id' => $orfo['Order']['id'],
		            'from_operator_id' => 0,
		            'user_id' => $orfo['Order']['user_id'],
		            'order_status' => 1,
		            'payment_status' => 2,
		            'shipping_status' => $orfo['Order']['shipping_status'],
		            'action_note' => $this->ld['successful_to_pay'],
		            )));
	    	}
	}
    
    //微信支付回调（充值）
	public function weixin_balance(){
		App::import('Vendor', 'Weixinpay', array('file' => 'WxPay.Api.php'));
		$notify = new PayNotifyCallBack();
		$res=$notify->Handle(false);
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		$result = WxPayResults::Init($xml);
		$this->loadModel('PaymentApiLog');
		$out_trade_no=isset($result["out_trade_no"])?$result["out_trade_no"]:'';
		$out_trade_no_arr=split("_",$out_trade_no);
		$api_log_id=$out_trade_no_arr[0];
		$payment_api_log = $this->PaymentApiLog->find('first', array('conditions' => array('PaymentApiLog.id' => $api_log_id,'is_paid'=>'0')));
	    	if (!empty($payment_api_log)){
                //充值支付
                $user_id = $payment_api_log['PaymentApiLog']['type_id'];
                $add_money = $payment_api_log['PaymentApiLog']['amount'];
                $user_Info = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
                if (!empty($user_Info)){
                    $user_money = $user_Info['User']['balance'];
                    $user_money = $user_money + $add_money;
                    $user_data['id'] = $user_id;
                    $user_data['balance'] = $user_money;
                    $this->User->save($user_data);
                    $user_Info['User']['balance'] = $user_money;
                    $_SESSION['User'] = $user_Info;
                    $payment_api_log['PaymentApiLog']['is_paid'] = '1';
                    $this->PaymentApiLog->save($payment_api_log);
                    //添加资金日志
                    $BalanceLog['UserBalanceLog']['user_id'] = $user_id;
                    $BalanceLog['UserBalanceLog']['amount'] = $payment_api_log['PaymentApiLog']['amount'];
                    $BalanceLog['UserBalanceLog']['admin_user'] = $user_Info['User']['balance'];
                    $BalanceLog['UserBalanceLog']['admin_note'] = '';
                    $BalanceLog['UserBalanceLog']['system_note'] = '用户余额:'.$user_data['balance'].'元';
                    $BalanceLog['UserBalanceLog']['log_type'] = 'B';
                    $BalanceLog['UserBalanceLog']['type_id'] = $payment_api_log['PaymentApiLog']['id'];
                    $BalanceLog['UserBalanceLog']['created'] = date('Y-m-d H:i:s', time());
                    $this->UserBalanceLog->save($BalanceLog);
                }
	    }
	}

    
    /**
     *更新订单.
     *
     *@param $payment_log
     *@param $order_total
     */
    public function update_order($payment_log=array(), $order_total=array())
    {
        /* 改变订单状态 */
        $order_total['Order']['payment_status'] = '2';
        $order_total['Order']['status'] = '1';
        if($payment_log){
        	$payment_log['PaymentApiLog']['is_paid'] = '1';
        	$order_total['Order']['money_paid'] = $payment_log['PaymentApiLog']['amount'];
        }
        $order_total['Order']['payment_time'] = date('Y-m-d H:i:s');
        if($payment_log){
        	$this->PaymentApiLog->save($payment_log);
        }
        $this->Order->save($order_total);
            //已付款 未发货的商品冻结库存处理
            $this->frozen_quantity($order_total['Order']['id']);
            // 超过订单金额赠送积分
            if (isset($this->configs['order_smallest']) && isset($this->configs['out_order_points']) && $this->configs['order_smallest'] <= $order_total['Order']['subtotal'] && $this->configs['out_order_points'] > 0) {
                $user_info = $this->User->findbyid($order_total['Order']['user_id']);//标注
                $user_info['User']['point'] += $this->configs['out_order_points'];
                $user_info['User']['user_point'] += $this->configs['out_order_points'];
                $this->User->save($user_info);
                $point_log = array('id' => '',
                                    'user_id' => $order_total['Order']['user_id'],
                                    'point' => $this->configs['out_order_points'],
                                    'log_type' => 'B',
                                    'system_note' => '超过订单金额 '.$this->configs['order_smallest'].' 赠送积分',
                                    'type_id' => $order_total['Order']['id'],
                                    );
                $this->UserPointLog->save($point_log);
            }
            //下单是否送积分
            if (isset($this->configs['order_points']) && $this->configs['order_points']) {
                $user_info = $this->User->findbyid($order_total['Order']['user_id']);//标注
                $user_info['User']['point'] += $this->configs['order_points'];
                $user_info['User']['user_point'] += $this->configs['order_points'];
                $this->User->save($user_info);
                $point_log = array('id' => '',
                                    'user_id' => $order_total['Order']['user_id'],
                                    'point' => $this->configs['order_points'],
                                    'log_type' => 'B',
                                    'system_note' => '下单送积分',
                                    'type_id' => $order_total['Order']['id'],
                                    );
                $this->UserPointLog->save($point_log);
            }
            // 商品送积分
            $product_ids = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_total['Order']['id'])));//标注
            $product_point = array();
        $send_coupon = array();
        $mun = 0;
        $product_alsobought = array();
        $product_size = sizeof($product_ids);
        if (isset($product_ids) && sizeof($product_ids) > 0) {
            foreach ($product_ids as $k => $v) {
                //ProductAlsobought
                    if (isset($also) && isset($product_ids[$also]['OrderProduct']['product_id']) && $product_ids[$also]['OrderProduct']['product_id'] != '' && isset($v['OrderProduct']['product_id']) && $v['OrderProduct']['product_id'] != '') {
                        if ($product_size > 0 && $mun > 0) {
                            $product_alsobought[$mun] = array('id' => '','product_id' => $product_ids[$also]['OrderProduct']['product_id'],'alsobought_product_id' => $v['OrderProduct']['product_id']);
                        } else {
                            $also = $k;
                        }
                        ++$mun;
                    }
                $product = $this->Product->findbyid($v['OrderProduct']['product_id']);//标注
                    $product_point[$k] = array(
                                                'point' => $product['Product']['point'] * $v['OrderProduct']['product_quntity'],
                                                'name' => $product['ProductI18n']['name'],
                                                );
                if ($product['Product']['coupon_type_id'] > 0) {
                    $send_coupon[] = $product['Product']['coupon_type_id'];
                }
            }
        }
        if (isset($product_alsobought) && sizeof($product_alsobought) > 0) {
            $this->ProductAlsobought->saveall($product_alsobought);
        }
        if (is_array($product_point) && sizeof($product_point) > 0) {
            foreach ($product_point as $k => $v) {
                if ($v['point'] > 0) {
                    $user_info = $this->User->findbyid($order_total['Order']['user_id']);//标注
                        $user_info['User']['point'] += $v['point'];
                    $user_info['User']['user_point'] += $v['point'];
                    $this->User->save($user_info);
                    $point_log = array('id' => '',
                                            'user_id' => $order_total['Order']['user_id'],
                                            'point' => $v['point'],
                                            'log_type' => 'B',
                                            'system_note' => '商品 '.$v['name'].' 送积分',
                                            'type_id' => $order_total['Order']['id'],
                                            );
                    $this->UserPointLog->save($point_log);
                }
            }
        }
                //是否送优惠券
                if (isset($this->configs['send_coupons']) && $this->configs['send_coupons'] == 1 && constant('Product') == 'AllInOne') {
                    $this->loadModel('CouponType');
                    $now = date('Y-m-d H:i:s');
                    $order_coupon_type = $this->CouponType->find('all', array('conditions' => array('CouponType.send_type' => 2, 'CouponType.send_start_date <=' => $now, 'CouponType.send_end_date >=' => $now)));
                    if (is_array($order_coupon_type) && sizeof($order_coupon_type) > 0) {
                        //	$coupon_arr = $this->Coupon->findall("1=1",'DISTINCT Coupon.sn_code');
                $coupon_arr_list = $this->Coupon->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));//标注
                $coupon_arr = array();
                        if (is_array($coupon_arr_list) && sizeof($coupon_arr_list) > 0) {
                            foreach ($coupon_arr_list as $k => $v) {
                                $coupon_arr[] = $v;
                            }
                        }
                        $coupon_count = count($coupon_arr);
                        $num = 0;
                        if ($coupon_count > 0) {
                            $num = $coupon_arr[$coupon_count - 1];
                        }
                        foreach ($order_coupon_type as $k => $v) {
                            if ($v['CouponType']['min_products_amount'] < $order_total['Order']['subtotal']) {
                                if (isset($coupon_sn)) {
                                    $num = $coupon_sn;
                                }
                                $num = substr($num, 2, 10);
                                $num = $num ? floor($num / 10000) : 100000;
                                $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                                $order_coupon = array(
                                                    'id' => '',
                                                    'coupon_type_id' => $v['CouponType']['id'],
                                                    'user_id' => $order_total['Order']['user_id'],
                                                    'sn_code' => $coupon_sn,
                                                    );
                                $this->Coupon->save($order_coupon);
                            }
                        }
                    }// order send end
                   if (is_array($send_coupon) && sizeof($send_coupon) > 0) {
                       //	$coupon_arr = $this->Coupon->findall("1=1",'DISTINCT Coupon.sn_code');
                $coupon_arr_list = $this->Coupon->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));//标注
                $coupon_arr = array();
                       if (is_array($coupon_arr_list) && sizeof($coupon_arr_list) > 0) {
                           foreach ($coupon_arr_list as $k => $v) {
                               $coupon_arr[] = $v;
                           }
                       }
                       $coupon_count = count($coupon_arr);
                       $num = 0;
                       if ($coupon_count > 0) {
                           $num = $coupon_arr[$coupon_count - 1];
                       }
                       foreach ($send_coupon as $type_id) {
                           if (isset($coupon_sn)) {
                               $num = $coupon_sn;
                           }
                           $pro_coupon_type = $this->CouponType->findbyid($type_id);//标注
                            $num = substr($num, 2, 10);
                           $num = $num ? floor($num / 10000) : 100000;
                           $coupon_sn = $pro_coupon_type['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                           $pro_coupon = array(
                                                'id' => '',
                                                'coupon_type_id' => $pro_coupon_type['CouponType']['id'],
                                                'user_id' => $order_total['Order']['user_id'],
                                                'sn_code' => $coupon_sn,
                                                );
                           $this->Coupon->save($pro_coupon);
                       }
                   }
                }
    }

    //已付款 未发货的商品库存处理
    public function frozen_quantity($order_id)
    {
        if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 0) {
            $order_data = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id), 'fields' => 'id,shipping_status'));
            if (isset($order_data['Order']['shipping_status']) && $order_data['Order']['shipping_status'] == 0) {
                $order_products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));
                foreach ($order_products as $opk => $opv) {
                    //已付款 未发货的商品冻结库存处理
                    if (!empty($opv['OrderProduct']['product_code'])) {
                        $product_frozen = $this->Product->find('first', array('conditions' => array('Product.code' => $opv['OrderProduct']['product_code']), 'fields' => 'Product.id,Product.code,Product.frozen_quantity,Product.quantity'));
                        if (!empty($product_frozen)) {
                            $product_frozen['Product']['frozen_quantity'] = $product_frozen['Product']['frozen_quantity'] + $opv['OrderProduct']['product_quntity'];
                            $product_frozen['Product']['quantity'] = $product_frozen['Product']['quantity'] - $opv['OrderProduct']['product_quntity'];
                            $this->Product->save(array('Product' => $product_frozen['Product']));
                        }
                    }
                }
            }
        }
    }
}
