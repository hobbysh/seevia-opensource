<?php

/*****************************************************************************
 * Seevia 支付方式
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 PaymentsController 的支付方式控制器.
 */
class PaymentsController extends AppController
{
    /*
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses*/
    public $name = 'Payments';
    public $uses = array('PaymentApiLog','Order','OrderProduct','Payment');

    /**
     *函数 user_index 进入支付页面.
     */
    public function user_index()
    {
    }
    //跳转支付
    public function index($type = '')
    {
        $order_code = $this->get_order_code();
        $referer = isset($_GET['referer']) ? $_GET['referer'] : 'unknow';
        $order_save_file = array(
                            'order_code' => $order_code,
                            'order_locale' => 'eng',
                            'order_currency' => 'USD',
                            'order_domain' => @$_SERVER[HTTP_HOST],
                            'user_id' => '1',
                            'status' => 1,
                            'consignee' => '',
                            'email' => '',
                            'total' => $_GET['total'],
                            'subtotal' => $_GET['total'],
                            'pack_name' => '虚拟',
                            'consignee' => '',
                            'address' => '',
                            'zipcode' => '',
                            'telephone' => '',
                            'mobile' => '',
                            'best_time' => '',
                            'sign_building' => '',
                            'invoice_no' => '',
                            'note' => '',
                            'referer' => $referer,
                            'invoice_type' => '0',
                            'invoice_payee' => '0',
                            'invoice_content' => '0',
                            'how_oos' => '0',
                            );

        $this->Order->save($order_save_file);
        $new_order_id = $this->Order->id;
        //echo $new_order_id;
        //生成订单结束
        //生成order_product
        $order_produvt_save_file = array(
                            'order_id' => $new_order_id,
                            'product_id' => 0,
                            'product_name' => $_GET['id'],
                            'product_code' => $_GET['id'],
                            'product_quntity' => 1,//数量
                            'product_price' => $_GET['total'],
                            'product_weight' => 0,
                            'note' => '',
                            'extension_code' => '0',
                            'product_attrbute' => '',
                            );
        $this->OrderProduct->save($order_produvt_save_file);
        echo 'Please waitting, we are Connecting to paypal site......';
        sleep(5);
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.code' => $type)));
        $payment_config = unserialize($payment['Payment']['config']);//die();
        App::import('Vendor', 'payments/'.$payment['Payment']['code']);
        $balance_payment = new $payment['Payment']['code']();
        //在线支付增加api日志
        $order_pack = array(
                'order_id' => $order_code,
                'type_id' => $new_order_id,
                'amount' => $_GET['total'],
                'item_id' => $_GET['id'],
        );
        $this->PaymentApiLog->save($order_pack);
        echo $balance_payment->go($order_pack, $payment_config);
        die;
    }

    public function responds($type)
    {
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.code' => $type)));
        $payment_config = unserialize($payment['Payment']['config']);//die();
        App::import('Vendor', 'payments/'.$payment['Payment']['code']);
        $payment = new $payment['Payment']['code']();
        $payment->notify($payment_config);
        if ($payment->get_trade_status()) {
            $id = $payment->get_track_id();
            $this->redirect('/responds/test/'.$id);
        } else {
            $this->redirect('/responds/test');
        }

//		//拿状态
//		$payment->get_trade_status();
//		//拿订单号
//		$payment->get_track_id();
//		//检查付款金额 $amount 订单总价
//		$payment->check_amount($amount);
    }

    /**
     *获取代码.
     */
    public function get_order_code()
    {
        mt_srand((double) microtime() * 1000000);
        $sn = date('Ymd').str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $a = 0;
        $b = 0;
        $c = 0;
        for ($i = 1;$i <= 12;++$i) {
            if ($i % 2) {
                $b += substr($sn, $i - 1, 1);
            } else {
                $a += substr($sn, $i - 1, 1);
            }
        }

        $c = (10 - ($a * 3 + $b) % 10) % 10;

        return $sn.$c;
    }

    public function test()
    {
        //echo $_SERVER[HTTP_HOST];
        die();
    }
}
