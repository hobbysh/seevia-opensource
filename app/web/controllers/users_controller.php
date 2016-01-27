<?php

uses('sanitize');
/**
 *这是一个名为 PagesController 的页面控制器.
 *
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 */
class UsersController extends AppController
{
    public $name = 'Users';
    public $helpers = array('Html','Flash','Cache','Pagination');
    public $uses = array('UserPointLog','Application','UserAddress','User','Product','UserRank','MailTemplate','ProductI18n','Region','Comment','Blog','UserFans','SynchroUser','UserBalanceLog','Flash','UserApp','Payment','PaymentApiLog','Enquiry','UserRankLog','Template','ScoreLog','OpenModel','UserConfig');
    public $components = array('RequestHandler','Cookie','Session','Captcha','Email','Pagination');
    public $cacheQueries = false;
    public $cacheAction = '1 hour';

    public function index()
    {
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = $this->ld['user_center'].' - '.$this->configs['shop_title'];
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        if (constant('Product') == 'AllInOne') {
            //当前订单信息（该用户最新3个订单）
            $this->loadModel('Order');
            $order_list = $this->Order->find('all', array('conditions' => array('Order.user_id' => $_SESSION['User']['User']['id']), 'order' => 'Order.created desc', 'limit' => '3'));
            $this->set('order_list', $order_list);
        }
        //猜您可能会喜欢（最新推荐的8个商品）
        $pro_like = $this->Product->find('all', array('conditions' => array('Product.recommand_flag' => '1','Product.status'=>'1','alone'=>'1','forsale'=>'1'), 'fields' => 'Product.img_thumb,Product.img_detail,Product.id,Product.promotion_end,Product.promotion_start,ProductI18n.name,Product.market_price,Product.promotion_price,Product.promotion_status,Product.shop_price', 'order' => 'Product.modified desc', 'limit' => '8'));
        foreach ($pro_like as $k => $v) {
            //cdn
            //$v['Product']['img_thumb']=str_replace('img.ioco.cn','img.seeworlds.cn',$vv['Product']['img_thumb']);
            //判断是否促销产品
            if ($this->Product->is_promotion($v)) {
                $pro_like[$k]['Product']['off'] = floor((1 - ($v['Product']['promotion_price'] / $v['Product']['shop_price'])) * 100);
                //$vancl_pro[$k]['Product']['shop_price'] = $vancl_pro[$k]['Product']['promotion_price'];
            }
        }
        $this->set('pro_like', $pro_like);
        $id = $_SESSION['User']['User']['id'];
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($id);
        $this->set('focuscount', $focus);
        //分享绑定显示判断
        $app_share = $this->UserApp->app_status();
        $this->set('app_share', $app_share);
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Order');
            $this->loadModel('Payment');
             //取得我的订单（最新的3条订单）
            $condition = " Order.user_id='".$_SESSION['User']['User']['id']."' ";
            $my_orders = $this->Order->my_list($condition, 3, 1);
            if (empty($my_orders)) {
                $my_orders = array();
            } else {
                $my_order_ids = array();
                foreach ($my_orders as $k => $v) {
                    //获取订单ID
                    $my_order_ids[] = $v['Order']['id'];
                    //获取该订单使用的付款方式
                    $payment_info = $this->Payment->find('first', array('conditions' => array('Payment.id' => $v['Order']['payment_id'])));
                    $my_orders[$k]['Order']['payment_name'] = $payment_info['PaymentI18n']['name'];
                    $my_orders[$k]['Order']['payment_is_cod'] = $payment_info['Payment']['is_cod'];
                    if (empty($v['Order']['consignee'])) {
                        //获取该订单的收货人
                        $address = $this->UserAddress->find_user_address($v['Order']['user_id']);
                        $my_orders[$k]['Order']['consignee'] = $address[0]['UserAddress']['consignee'];
                    }
                    //去掉优惠后，我需要付款的总额
                    $my_orders[$k]['Order']['need_paid'] = number_format($v['Order']['total'] - $v['Order']['money_paid'] - $v['Order']['point_fee'] - $v['Order']['discount'], 2, '.', '') + 0;
                }
            }
            //本月消费
            $years = date('m');
            $order_month_count = 0;
            $order_year = $this->Order->find('all', array(
                    'conditions' => array('Order.user_id' => $_SESSION['User']['User']['id'], 'Order.payment_status' => 2, 'month(Order.created)' => $years), ));
            foreach ($order_year as $k => $v) {
                $order_month_count += ($v['Order']['total'] - $v['Order']['point_fee']);
            }
             //总消费
            $order_all_count = 0;
            $order_all = $this->Order->find('all', array(
                    'conditions' => array('Order.user_id' => $_SESSION['User']['User']['id'], 'Order.payment_status' => 2), ));
            foreach ($order_all as $k => $v) {
                $order_all_count += ($v['Order']['total'] - $v['Order']['point_fee']);
            }
             //待支付订单
            $pay_orderscount = $this->Order->find('count', array(
                    'conditions' => array('Order.user_id' => $_SESSION['User']['User']['id'], 'Order.payment_status' => 0), ));
             //待收货订单
            $receiving_orderscount = $this->Order->find('count', array(
                    'conditions' => array('Order.user_id' => $_SESSION['User']['User']['id'], 'Order.payment_status' => 2, 'Order.status' => 1, 'Order.shipping_status' => 1), ));
            //待评论订单
            $pro_comments = array();
            //获取购买过但未评论的商品
            $comment_orders = $this->Order->find('all', array('conditions' => array('Order.user_id' => $_SESSION['User']['User']['id'], 'Order.status' => '1', 'Order.shipping_status' => '2', 'Order.payment_status' => '2')));
            foreach ($comment_orders as $k => $v) {
                foreach ($v['OrderProduct'] as $kk => $vv) {
                    $counts = $this->Comment->find('count', array('conditions' => array('Comment.type_id' => $vv['product_id'], 'Comment.type' => 'P', 'Comment.status' => 1, 'Comment.user_id' => $_SESSION['User']['User']['id'])));//获取我的评论
                     if ($counts == 0) {
                         $pro_first = $this->Product->find('first', array('conditions' => array('Product.id' => $vv['product_id']), 'fields' => 'Product.img_thumb,Product.img_detail'));//获取我的评论
                        $pro_comments[$vv['id']] = $vv;
                         $pro_comments[$vv['id']]['product_img_thumb'] = $pro_first['Product']['img_thumb'];
                         $pro_comments[$vv['id']]['product_img_detail'] = $pro_first['Product']['img_detail'];
                        //获取回复数量
                        $pro_comments[$vv['id']]['count'] = $this->Comment->find('count', array('conditions' => array('Comment.type_id' => $vv['product_id'], 'Comment.type' => 'P', 'Comment.status' => 1)));//获取我的评论
                     }
                }
            }
            $this->set('order_all_count', $order_all_count);
            $this->set('order_month_count', $order_month_count);
            $this->set('pay_orderscount', $pay_orderscount);
            $this->set('pro_comments', $pro_comments);
            $this->set('my_orders', $my_orders);
            $this->set('receiving_orderscount', $receiving_orderscount);
        }
        $this->set('user_list', $user_list);

        if (isset($this->configs['phistory-ustatus']) && $this->configs['phistory-ustatus'] == '1') {
            //商品浏览历史
            $params['controller'] = $this->params['controller'];
            $params['action'] = $this->params['action'];
            $params['ControllerObj'] = $this;//控制器对象
            $pro_log_list = $this->Product->pro_view_log($params);
            $this->set('pro_log_list', $pro_log_list);
        }
        /*
            判断是否为手机版
        */
        if ($this->is_mobile) {
            $this->layout = 'mobile/default_full';
            $this->render('mobile/index');
            Configure::write('debug', 1);
        }
    }

    //注册
    public function register()
    {
        $this->set('type', isset($_GET['type']) ? $_GET['type'] : 0);
        $this->set('sev', $this->server_host);
        $this->layout = 'default_full';            //引入模版
        $this->page_init();
        $this->pageTitle = $this->ld['register'].' - '.$this->configs['shop_title'];                //页面初始化
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['register'],'url' => '');
        /*
            判断是否为手机版
        */
        if ($this->is_mobile) {
            $this->layout = 'mobile/default_full';
            $this->render('mobile/register');
            Configure::write('debug', 1);
        }

        $messege_error = '';                        //报错提示变量
        //登录注册轮播
        $flash_conditions['Flash.page'] = 'LR';
        $flash_conditions['Flash.type'] = '0';
        $flash_list = $this->Flash->find('first', array('conditions' => $flash_conditions));
        $this->set('flash_list', $flash_list);
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('FenxiaoDistributorLevel');
            //获取分销商等级集合
            $fenxiao_distributor_levels = $this->FenxiaoDistributorLevel->find('all', array('fields' => array('id', 'name')));
            $this->set('fenxiao_distributor_levels', $fenxiao_distributor_levels);
        }
        $syns = $this->UserApp->find('list', array('conditions' => array('UserApp.status' => 1, 'UserApp.type !=' => 'Wechat'), 'fields' => array('UserApp.type')));
        $this->set('syns', $syns);
        if ($this->RequestHandler->isPost()) {
            $tmp_x = $this->User->find('first', array('conditions' => array('User.email' => $this->data['Users']['email'])));
            if (!empty($tmp_x)) {
                //判断email是否have
                $this->data['Users']['email'] = $this->data['Users']['email'];
                if (isset($_POST['is_ajax'])) {
                    $error_no = 1;
                    $messege_error = $this->ld['email_already_exists'];
                    $back_url = '';
                    $this->layout = 'ajax';
                    Configure::write('debug', 0);
                    $result = array(
                        'result' => $messege_error,
                        'message' => $messege_error,
                        'back_url' => $back_url,
                        'error_no' => $error_no,
                        'check_email' => $this->data['Users']['email'],
                    );
                    die(json_encode($result));
                }
                $this->flash("<font color='red'>".$this->ld['email_already_exists'].'</font>', array('controller' => '/'), '');

                return;
            }
            if (!isset($this->data['Users']['name']) || (isset($this->data['Users']['name']) && $this->data['Users']['name'] == '')) {
                $this->data['Users']['name'] = $this->data['Users']['email'];
            }
            //是否使用注册验证码
            $register_captcha = isset($this->configs['register_captcha']) && $this->configs['register_captcha'] == '1' ? true : false;
            if ($register_captcha) {
                if (!isset($this->data['Users']['authnum']) || isset($this->data['Users']['authnum']) && $this->captcha->check($this->data['Users']['authnum']) == false) {
                    //判断验证码是否正确
                    $this->data['Users']['email'] = $this->data['Users']['email'];
                    if (isset($_POST['is_ajax'])) {
                        $messege_error = $this->ld['incorrect_verification_code'];
                        $error_no = 1;
                        $back_url = '';
                        $this->layout = 'ajax';
                        Configure::write('debug', 0);
                        $result = array(
                            'result' => $messege_error,
                            'message' => $messege_error,
                            'back_url' => $back_url,
                            'error_no' => $error_no,
                            'check_email' => $this->data['Users']['email'],
                        );
                        die(json_encode($result));
                    }
                    $this->flash("<font color='red'>".$this->ld['incorrect_verification_code'].'</font>', array('controller' => '/'), '');

                    return;
                }
            }
            //添加用户
            $psw = $this->data['Users']['password'];
            $this->data['Users']['password'] = md5($this->data['Users']['password']);
            $this->data['Users']['user_sn'] = $this->data['Users']['email'];
            $this->data['Users']['email'] = $this->data['Users']['email'];
            //preg_match("/(.*)@.*/",$this->data["Users"]["email"],$m);
            $this->data['Users']['last_login_time'] = gmdate('Y-m-d H:i:s', time());
            //填写的用户信息到session
            $x = $this->User->save($this->data['Users']);
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('FenxiaoDistributor');
                //如果是分销商 添加分销商数据
                if (isset($this->data['Users']['type']) && $this->data['Users']['type'] == 1) {
                    $distributorInfo['FenxiaoDistributor']['user_id'] = $this->User->id;
                    $this->FenxiaoDistributor->save($distributorInfo);
                }
            }
            if (isset($this->data['Address']['RegionUpdate'])) {
                $this->data['UserAddress']['consignee'] = $this->data['Users']['name'];
                $this->data['UserAddress']['regions'] = (isset($this->data['Address']['RegionUpdate'][0]) ? $this->data['Address']['RegionUpdate'][0] : '1').' '.(isset($this->data['Address']['RegionUpdate'][1]) ? $this->data['Address']['RegionUpdate'][1] : '').' '.(isset($this->data['Address']['RegionUpdate'][2]) ? $this->data['Address']['RegionUpdate'][2] : '');
                $this->data['UserAddress']['country'] = isset($this->data['Address']['RegionUpdate'][0]) ? $this->data['Address']['RegionUpdate'][0] : '1';
                $this->data['UserAddress']['province'] = isset($this->data['Address']['RegionUpdate'][1]) ? $this->data['Address']['RegionUpdate'][1] : '';
                $this->data['UserAddress']['city'] = isset($this->data['Address']['RegionUpdate'][2]) ? $this->data['Address']['RegionUpdate'][2] : '';
                if (isset($this->data['Users']['mobile'])) {
                    $this->data['UserAddress']['mobile'] = $this->data['Users']['mobile'];
                }
                $this->data['UserAddress']['user_id'] = $this->User->id;
                $this->UserAddress->save($this->data['UserAddress']);
                $this->data['Users']['address_id'] = $this->UserAddress->id;
                $this->data['Users']['id'] = $this->User->id;
                $this->User->save($this->data['Users']);
            }
            //判断注册是否送积分$this->app_infos
            $user_info = $this->User->find('first', array('conditions' => array('User.id' => $this->User->id)));
            if (isset($this->configs['register_gift_points']) && $this->configs['register_gift_points'] == 1) {
                $register = isset($this->configs['point-register']) ? $this->configs['point-register'] : 0;
                if (isset($register) && $register > 0) {
                    $user_info['User']['point'] = $register;
                    $user_info['User']['user_point'] = $register;
                    $this->User->save($user_info['User']);
                    $user_point_log = array('id' => '',
                              'user_id' => $user_info['User']['id'],
                              'point' => $register,
                              'log_type' => 'R',
                              'system_note' => $this->ld['registration_gift_points'],
                              'type_id' => '0',
                            );
                    $this->UserPointLog->save($user_point_log);
                }
            }

            //判断是否送优惠券  start chenfan 2012/05/25
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('CouponType');
                $this->loadModel('Coupon');
                $now = date('Y-m-d H:i:s');
                $coupon_type = $this->CouponType->find('all', array('conditions' => "CouponType.send_type = '4' and CouponType.send_start_date <= '".$now."' and  CouponType.send_end_date >='".$now."'"));
                if (is_array($coupon_type) && sizeof($coupon_type) > 0) {
                    $coupon_arr_list = $this->Coupon->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));
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
                    foreach ($coupon_type as $k => $v) {
                        if (isset($coupon_sn)) {
                            $num = $coupon_sn;
                        }
                        $num = substr($num, 2, 10);
                        $num = $num ? floor($num / 10000) : 100000;
                        $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                        $coupon = array(
                                  'id' => '',
                                  'coupon_type_id' => $v['CouponType']['id'],
                                  'sn_code' => $coupon_sn,
                                  'user_id' => $user_info['User']['id'],
                        );
                        $this->Coupon->save($coupon);
                    }
                }
            }
            //优惠券 end
            if ($x) {
                if (in_array('APP-UC-BASE', $this->all_app_codes)) {
                    $this->uc_on();
                    App::import('Vendor', 'uc/uc_client', array('file' => 'client.php'));
                    $arr = uc_user_register($this->data['Users']['name'], $psw, $this->data['Users']['email']);
                    $arr = uc_user_login($this->data['Users']['name'], $psw);
                    sleep(1);
                }
                $_SESSION['User'] = $user_info;
                $_SESSION['User']['User']['type_level_id'] = 0;
                //跳转到提示页
                if (isset($_SESSION['login_back']) && $_SESSION['login_back'] == '/flashes/index/H') {
                    unset($_SESSION['login_back']);
                }
                $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users/index';
//				$lan=array('/en','/cn','/jp');
//				$back_url=str_replace($lan,'',$back_url);
                //$this->flash($this->ld['successfully_registered_into_user_center'],$back_url,2);
                if (isset($_POST['is_ajax'])) {
                    $messege_error = $this->ld['successfully_registered_into_user_center'];
                    $error_no = 0;
                    $this->layout = 'ajax';
                    $result = array(
                        'result' => $messege_error,
                        'message' => $messege_error,
                        'back_url' => $back_url,
                        'error_no' => $error_no,
                        'check_email' => $this->data['Users']['user_sn'],
                        'user_data' => $user_info,
                    );
                    Configure::write('debug', 0);
                    die(json_encode($result));
                }
                $this->redirect($back_url);
            } else {
                $this->flash($this->ld['fail_regist'], array('controller' => 'users/register'), '');
            }
        }
        $this->set('messege_error', $messege_error);
    }

    /**
     *登录.
     */
    public function login()
    {
        $this->layout = 'default_full';
        $this->pageTitle = $this->ld['login'].' - '.$this->configs['shop_title'];                    //页面初始化
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['login'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $messege_error = '';                        //报错提示变量
        $this->set('sev', $this->server_host);
        //登录注册轮播
        $flash_conditions['Flash.page'] = 'LR';
        $flash_conditions['Flash.type'] = '0';
        $flash_list = $this->Flash->find('first', array('conditions' => $flash_conditions));
        $this->set('flash_list', $flash_list);
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Payment');
            $config_value = $this->Payment->find('first', array('conditions' => array('Payment.code' => 'alipay'), 'fields' => array('Payment.config')));
            $config_value = unserialize($config_value['Payment']['config']);
        }
        if (isset($config_value['login'])) {
            $this->set('fei', $config_value['login']);
        }
        $syns = $this->UserApp->find('list', array('conditions' => array('UserApp.status' => 1, 'UserApp.type !=' => 'Wechat'), 'fields' => array('UserApp.type')));
        $this->set('syns', $syns);
        if ($this->RequestHandler->isPost()) {
            //是否使用登录验证码
            $use_captcha = isset($this->configs['use_captcha']) && $this->configs['use_captcha'] == '1' ? true : false;
            if ($use_captcha) {
                if (!isset($this->data['Users']['authnum']) || isset($this->data['Users']['authnum']) && $this->captcha->check($this->data['Users']['authnum']) == false) {
                    if (isset($this->configs['login_back_url']) && $this->configs['login_back_url'] == 0) {
                        if (isset($_SESSION['login_back']) && $_SESSION['login_back'] == '/flashes/index/H') {
                            unset($_SESSION['login_back']);
                        }
                        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
                    } elseif ($this->configs['login_back_url'] == 1) {
                        $back_url = '/';
                    } elseif ($this->configs['login_back_url'] == 2) {
                        $back_url = '/users/index';
                    }
                    $messege_error = $this->ld['incorrect_verification_code'];
                    //判断验证码是否正确
                    if (isset($_POST['is_ajax'])) {
                        $error_no = 1;
                        $back_url = '';
                        $this->layout = 'ajax';
                        Configure::write('debug', 1);
                        $result = array(
                            'result' => $messege_error,
                            'message' => $messege_error,
                            'back_url' => $back_url,
                            'error_no' => $error_no,
                        );
                        die(json_encode($result));
                    }

                    $this->set('messege_error', $messege_error);

                    return;
                }
            }
            //判断用户是否存在
            $ps = empty($_POST['md5password']) ? md5($_POST['password']) : $_POST['md5password'];
            $login_type = isset($_POST['login_type']) ? $_POST['login_type'] : 'user_sn';//判断用户登录的用户名方式
            $user_cond = array();
            if ($login_type == 'user_sn') {
                	$user_cond['User.user_sn'] = $_POST['user_name'];
            } elseif ($login_type == 'email') {
                	$user_cond['User.email'] = $_POST['user_name'];
            } else {
                	$user_cond['User.mobile'] = $_POST['user_name'];
            }
            $user_cond['User.password'] = $ps;
            $users = $this->User->find('first', array('conditions' => $user_cond));
            if (in_array('APP-UC-BASE', $this->all_app_codes)) {
                $this->uc_on();
                App::import('Vendor', 'uc/uc_client', array('file' => 'client.php'));
            }
            if ($users == null) {
                if (isset($_POST['is_ajax'])) {
                    $error_no = 1;
                    $messege_error = $this->ld['id_password_wrong'];
                    $back_url = '';
                    $this->set('messege_error', $messege_error);
                    $this->set('error_no', $error_no);
                    $this->set('back_url', $back_url);
                    $this->layout = 'ajax';
                    $this->render('login_result');
                    return;
                }
                if (in_array('APP-UC-BASE', $this->all_app_codes)) {
                    $arr = uc_user_login($_POST['user_name'], $_POST['password']);
                    if (isset($arr['0']) && $arr['0'] > 0) {
                        $data = uc_get_user($login_name);
                        //添加用户
                        $this->data['Users']['password'] = md5($_POST['password']);
                        $this->data['Users']['user_sn'] = $_POST['user_name'];
                        //preg_match("/(.*)@.*/",$this->data["Users"]["email"],$m);
                        $this->data['Users']['last_login_time'] = gmdate('Y-m-d H:i:s', time());
                        //$this->data["Users"]['name']=$m[1];
                        //填写的用户信息到session
                        $x = $this->User->save($this->data['Users']);
                        //判断注册是否送积分$this->app_infos
                        $user_info = $this->User->find('first', array('conditions' => array('User.id' => $this->User->id)));
                        if (isset($this->configs['point-register']) && $this->configs['point-register'] > 0) {
                            $register = $this->configs['point-register'];
                            if (isset($register) && $register > 0) {
                                $user_info['User']['point'] = $register;
                                $user_info['User']['user_point'] = $register;
                                $this->User->save($user_info);
                                $user_point_log = array('id' => '',
                                          'user_id' => $user_info['User']['id'],
                                          'point' => $register,
                                          'log_type' => 'R',
                                          'system_note' => $this->ld['registration_gift_points'],
                                          'type_id' => '0',
                                        );
                                $this->UserPointLog->save($user_point_log);
                            }
                        }
                        //判断是否送优惠券  start chenfan 2012/05/25

                            $now = date('Y-m-d H:i:s');
                        $coupon_type = $this->CouponType->find('all', array('conditions' => "CouponType.send_type = '4' and CouponType.send_start_date <= '".$now."' and  CouponType.send_end_date >='".$now."'"));
                        if (is_array($coupon_type) && sizeof($coupon_type) > 0) {
                            //	$coupon_arr = $this->Coupon->findall("1=1",'DISTINCT Coupon.sn_code');
                                $coupon_arr_list = $this->Coupon->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));
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
                            foreach ($coupon_type as $k => $v) {
                                if (isset($coupon_sn)) {
                                    $num = $coupon_sn;
                                }
                                $num = substr($num, 2, 10);
                                $num = $num ? floor($num / 10000) : 100000;
                                $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                                $coupon = array(
                                              'id' => '',
                                              'coupon_type_id' => $v['CouponType']['id'],
                                              'sn_code' => $coupon_sn,
                                              'user_id' => $user_info['User']['id'],
                                    );
                                $this->Coupon->save($coupon);
                            }
                        }

                        if ($x) {
                            $_SESSION['User'] = $user_info;
                            //跳转到提示页
                            if (isset($this->configs['login_back_url']) && $this->configs['login_back_url'] == 0) {
                                if (isset($_SESSION['login_back']) && $_SESSION['login_back'] == '/flashes/index/H') {
                                    unset($_SESSION['login_back']);
                                }
                                $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
                            } elseif ($this->configs['login_back_url'] == 1) {
                                $back_url = '/';
                            } elseif ($this->configs['login_back_url'] == 2) {
                                $back_url = '/users/index';
                            }
//							$lan=array('/en','/cn','/jp');
//							$back_url=str_replace($lan,'',$back_url);
                            $this->flash($this->ld['successfully_registered_into_user_center'], $back_url, 2);
                        } else {
                            $this->flash($this->ld['fail_regist'], array('controller' => 'users/register'), '');
                        }
                    }
                }
                $messege_error = $this->ld['id_password_wrong'];
            } else {
                //判断是否是分销商
                //如果是分销商 添加分销商等级
                if ($users['User']['type'] == 1) {
                    $distributorInfo = $this->FenxiaoDistributor->find('first', array('conditions' => array('FenxiaoDistributor.user_id' => $users['User']['id'])));
                    $users['User']['type_level_id'] = isset($distributorInfo['FenxiaoDistributor']['distributor_level_id']) ? $distributorInfo['FenxiaoDistributor']['distributor_level_id'] : 0;
                }
                if (isset($_POST['status']) && $_POST['status'] == 1) {
                    //选择自动登录的，将用户保存到cookie，设为2周有效
                    setcookie('user_info', serialize($users), time() + 60 * 60 * 24 * 14, '/');
                } else {
                    setcookie('user_info', null, time() - 60 * 60 * 24 * 14, '/');
                }
                //将用户信息存到session
                $_SESSION['User'] = $users;
                $x = $users['User']['id'];
                $this->User->updateAll(array('User.last_login_time' => "'".gmdate('Y-m-d H:i:s', time())."'"), array('User.id' => $x));
                /*
                    验证用户是否会员到期
                */
                $this->UserRankLog->checkUserRank($x);
                if (in_array('APP-UC-BASE', $this->all_app_codes)) {
                    $arr = uc_user_login($_POST['user_name'], $_POST['password']);
                    if (isset($arr['0']) && $arr['0'] == '-1') {
                        $rs = mysql_fetch_object($result);
                        $mails = $rs->user_email;
                        $arr = uc_user_register($_POST['user_name'], $_POST['password'], $_POST['user_name']);
                        $arr = uc_user_login($_POST['user_name'], $_POST['password']);
                    }
                    if (isset($arr['0']) && $arr['0'] > 0) {
                        $arr = uc_user_synlogin($arr['0']);
                    }//var_dump($this->app_infos['APP-UC-BASE']['configs']);}
                }
                if (isset($_SESSION['login_back'])) {
                    if ($_SESSION['login_back'] == '/en/' || $_SESSION['login_back'] == '/cn/' || $_SESSION['login_back'] == '/jp/') {
                        $_SESSION['login_back'] = '/';
                    }
                }
                if (isset($this->configs['login_back_url']) && $this->configs['login_back_url'] == 0) {
                    if (isset($_SESSION['login_back']) && $_SESSION['login_back'] == '/flashes/index/H') {
                        unset($_SESSION['login_back']);
                    }
                    $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
                } elseif ($this->configs['login_back_url'] == 1) {
                    $back_url = '/';
                } elseif ($this->configs['login_back_url'] == 2) {
                    $back_url = '/users/index';
                }
                //pr($_SESSION);die;
                $lan = array('/en/','/cn/','/jp/');
                $back_url = str_replace($lan, '/', $back_url);
                $rank_code = $this->UserRank->get_rank_code($users['User']['rank']);
                if (isset($_POST['is_ajax'])) {
                    $error_no = 0;
                    $this->set('back_url', $back_url);
                    $this->set('user_name', $users['User']['name']);
                    $this->set('user_rank', $rank_code);
                    $this->set('error_no', $error_no);
                    $this->set('messege_error', $messege_error);
                    $this->set('user_data', $users);
                    $this->layout = 'ajax';
                    $this->render('login_result');

                    return;
                }
            //	echo $back_url;exit();
                $this->redirect($back_url);
                exit();
            }
        }
        $this->set('messege_error', $messege_error);
        /*
            判断是否为手机版
        */
        if ($this->is_mobile) {
            $this->layout = 'mobile/default_full';
            $this->render('mobile/login');
            Configure::write('debug', 1);
        }
        if(!empty($this->wechat_loginobj)){
            if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
                $this->redirect("/synchros/opauth/wechat");
            }
        }
    }

    public function cellphone_login()
    {
        $ps = empty($_POST['md5password']) ? md5($_POST['password']) : $_POST['md5password'];
        $users = $this->User->find('first', array('conditions' => array('user_sn' => $_POST['user_name'], 'password' => $ps)));
        if (in_array('APP-UC-BASE', $this->all_app_codes)) {
            $this->uc_on();
            App::import('Vendor', 'uc/uc_client', array('file' => 'client.php'));
        }
        $result = array();
        if ($users == null) {
            $result['flag'] = 0;
            $result['msg'] = $this->ld['id_password_wrong'];//'user name or password error!';
        } else {
            $result['flag'] = 1;
            if (isset($_POST['status']) && $_POST['status'] == 1) {
                setcookie('user_info', serialize($users), time() + 60 * 60 * 24 * 14, '/');
            }
            //将用户信息存到session
            $_SESSION['User'] = $users;
            $x = $users['User']['id'];
            $this->User->updateAll(array('User.last_login_time' => "'".gmdate('Y-m-d H:i:s', time())."'"), array('User.id' => $x));
            if (in_array('APP-UC-BASE', $this->all_app_codes)) {
                $arr = uc_user_login($_POST['user_name'], $_POST['password']);
                if (isset($arr['0']) && $arr['0'] == '-1') {
                    $rs = mysql_fetch_object($result);
                    $mails = $rs->user_email;
                    $arr = uc_user_register($_POST['user_name'], $_POST['password'], $_POST['user_name']);
                    $arr = uc_user_login($_POST['user_name'], $_POST['password']);
                }
                if (isset($arr['0']) && $arr['0'] > 0) {
                    $arr = uc_user_synlogin($arr['0']);
                }
            }
        }
        if (isset($_SESSION['login_back'])) {
            if ($_SESSION['login_back'] == '/en/' || $_SESSION['login_back'] == '/cn/' || $_SESSION['login_back'] == '/jp/') {
                $_SESSION['login_back'] = '/';
            }
        }
        if ($_SESSION['login_back'] == '/flashes/index/H') {
            unset($_SESSION['login_back']);
        }
        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
        $lan = array('/en','/cn','/jp');
        $back_url = str_replace($lan, '', $back_url);
        $result['url'] = $back_url;
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }

    public function uc_on()
    {
        define('ROOT_PATH', str_replace('api', '', str_replace('\\', '/', dirname(dirname(dirname(__FILE__))))).DS.'vendors'.DS.'uc'.DS);
        define('UC_CONNECT', 'mysql');
        define('UC_DBHOST', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBHOST']);
        define('UC_DBUSER', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBUSER']);
        define('UC_DBPW', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBPW']);
        define('UC_DBNAME', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBNAME']);
        define('UC_DBCHARSET', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBCHARSET']);
        define('UC_DBTABLEPRE', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBTABLEPRE']);
        define('UC_DBCONNECT', '0');
        define('UC_KEY', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-KEY']);
        define('UC_API', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-API']);
        define('UC_CHARSET', 'utf-8');
        define('UC_IP', 1);
        define('UC_APPID', 1);
        define('UC_PPP', '20');
        define('UC_CLIENT_VERSION', '1.5.0');  //note UCenter 版本标识
        define('UC_CLIENT_RELEASE', '20081031');
        define('API_DELETEUSER', 1);    //note 用户删除 API 接口开关
        define('API_RENAMEUSER', 1);    //note 用户改名 API 接口开关
        define('API_GETTAG', 1);        //note 获取标签 API 接口开关
        define('API_SYNLOGIN', 1);      //note 同步登录 API 接口开关
        define('API_UPDATEPW', 1);      //note 更改用户密码 开关
        define('API_UPDATEBADWORDS', 1);//note 更新关键字列表 开关
        define('API_UPDATEHOSTS', 1);   //note 更新域名解析缓存 开关
        define('API_UPDATEAPPS', 1);    //note 更新应用列表 开关
        define('API_UPDATECLIENT', 1);  //note 更新客户端缓存 开关
        define('API_UPDATECREDIT', 1);  //note 更新用户积分 开关
        define('API_GETCREDITSETTINGS', 1);  //note 向 UCenter 提供积分设置 开关
        define('API_GETCREDIT', 1);     //note 获取用户的某项积分 开关
        define('API_UPDATECREDITSETTINGS', 1);  //note 更新应用积分设置 开关
        define('API_RETURN_SUCCEED', '1');
        define('API_RETURN_FAILED', '-1');
        define('API_RETURN_FORBIDDEN', '-2');
        define('IN_ECS', true);
    }

    public function edit_headimg()
    {
        //登录验证
        $this->checkSessionUser();
        $this->pageTitle = $this->ld['upload_photos'].' - '.$this->configs['shop_title'];
        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                    //页面初始化
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['upload_photos'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $id = $_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
        $_SESSION['User'] = $user_list;
        if ($user_list['User']['address_id'] != '0') {
            //获取我的地址
            $user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
        }
        $this->set('user_list', $user_list);
        //分享绑定显示判断
        $app_share = $this->UserApp->app_status();
        $this->set('app_share', $app_share);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($id);
        $this->set('focuscount', $focus);
        $max_file = '3';            //图片大小限制
        $max_width = '300';            //大图最大宽度
        $thumb_width = '180';        //小图最大宽度
        $thumb_height = '180';        //小图最大高度
        $this->set('max_width', $max_width);
        $this->set('thumb_width', $thumb_width);
        $this->set('thumb_height', $thumb_height);
        if ($this->RequestHandler->isPost()) {
            if (isset($_POST['imgurl']) && strlen($_POST['imgurl']) > 0) {
		  $_POST=$this->clean_xss($_POST);
                $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
                $image_url = $_POST['imgurl'];
                $image_location = $img_dir.$image_url;
                $x1 = $_POST['x1'];
                $y1 = $_POST['y1'];
                $x2 = $_POST['x2'];
                $y2 = $_POST['y2'];
                $w = $_POST['w'];
                $h = $_POST['h'];
                //Scale the image to the thumb_width set above
                $scale = $thumb_width / $w;
                $cropped = $this->resizeThumbnailImage($image_location, $image_location, $w, $h, $x1, $y1, $scale);
                //Reload the page again to view the thumbnail
                $data['User']['img01'] = $image_url;
                $data['User']['id'] = $id;
                $this->User->save($data);
                $this->redirect('/users/edit_headimg');
            }
        }
    }

    /*
    	头像上传处理
    */
    public function uploadheadimg()
    {
        $user_id = $_SESSION['User']['User']['id'];

        //支持的图片格式
        $allowed_image_types = array(
            array('image/pjpeg' => 'jpg'),
            array('image/jpeg' => 'jpg'),
            array('image/jpeg' => 'jpeg'),
            array('image/jpg' => 'jpg'),
            array('image/png' => 'png'),
            array('image/x-png' => 'png'),
            array('image/gif' => 'gif'),
        );
        $image_ext = 'jpg、jpeg、png、gif';
        $max_file = '3';            //图片大小限制
        $max_width = '300';            //大图最大宽度
        $thumb_width = '180';        //小图最大宽度
        $thumb_height = '180';        //小图最大高度
        $img_root = 'media/users/'.date('Ym').'/';
        $imgaddr = WWW_ROOT.'media/users/'.date('Ym').'/';
        $this->mkdirs($imgaddr);
        @chmod($imgaddr, 0777);
        $result['code'] = '0';
        $result['error'] = '文件不存在';
        $error = '';
        if ($this->RequestHandler->isPost()) {
            if (isset($_FILES['userImg'])) {
                if ((!empty($_FILES['userImg'])) && ($_FILES['userImg']['error'] == 0)) {
                    $userfile_name = $_FILES['userImg']['name'];
                    $userfile_tmp = $_FILES['userImg']['tmp_name'];
                    $userfile_size = $_FILES['userImg']['size'];
                    $userfile_type = $_FILES['userImg']['type'];
                    $filename = basename($_FILES['userImg']['name']);
                    $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
                    foreach ($allowed_image_types as $mime_type => $ext) {
                        foreach ($ext as $_mime_type => $_ext) {
                            if ($file_ext == $_ext && $userfile_type == $_mime_type) {
                                $error = '';
                                break;
                            } else {
                                $error = '只支持以下图片格式'.$image_ext;
                            }
                        }
                        if (strlen($error) == 0) {
                            break;
                        }
                    }
                    if ($userfile_size > ($max_file * 1048576)) {
                        $error = '图片最大限制'.$max_file.'MB';
                    }
                } else {
                    $error = '上传失败';
                }
                if (strlen($error) == 0) {
                    $image_location = $imgaddr.md5(date('Y-m-d h:i:s').$user_id.$userfile_name).'.'.$file_ext;
                    $image_name = '/'.$img_root.md5(date('Y-m-d h:i:s').$user_id.$userfile_name).'.'.$file_ext;

                    if (move_uploaded_file($userfile_tmp, $image_location)) {
                        $width = $this->getWidth($image_location);
                        $height = $this->getHeight($image_location);

                        if ($width < $thumb_width || $height < $thumb_height) {
                            $error = '图片尺寸太小';
                            if (file_exists($image_location)) {
                                unlink($image_location);
                            }
                        } else {
                            if ($width > $max_width) {
                                $scale = $max_width / $width;
                                $uploaded = $this->resizeImage($image_location, $width, $height, $scale);
                            } else {
                                $scale = 1;
                                $uploaded = $this->resizeImage($image_location, $width, $height, $scale);
                            }

                            $width = $this->getWidth($image_location);
                            $height = $this->getHeight($image_location);

                            $result['code'] = '1';
                            $result['img_url'] = $image_name;
                            $result['width'] = $width;
                            $result['height'] = $height;
                        }
                    } else {
                        $error = '上传失败';
                    }
                }
            }
            $result['error'] = $error;
        }
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }

    /*
    	删除头像图片
    */
    public function clearimg()
    {
        if ($this->RequestHandler->isPost()) {
            $result['code'] = '0';
            $result['error'] = '文件不存在';
            $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
            $img_url = $img_dir.(isset($_POST['img_url']) ? $_POST['img_url'] : '');
            if (file_exists($img_url)) {
                $result['code'] = '1';
                $result['error'] = '';
                unlink($img_url);
            }
            $this->layout = 'ajax';
            Configure::write('debug', 0);
            die(json_encode($result));
        } else {
            $this->redirect('/users/edit_headimg');
        }
    }

    /*
    	获取图片高度
    */
    public function getHeight($image)
    {
        $size = getimagesize($image);
        $height = $size[1];

        return $height;
    }

    /*
    	获取图片宽度
    */
    public function getWidth($image)
    {
        $size = getimagesize($image);
        $width = $size[0];

        return $width;
    }

    /*
    	等比例调整图片
    */
    public function resizeImage($image, $width, $height, $scale)
    {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch ($imageType) {
            case 'image/gif':
                $source = imagecreatefromgif($image);
                break;
            case 'image/pjpeg':
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($image);
                break;
            case 'image/png':
            case 'image/x-png':
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);

        switch ($imageType) {
            case 'image/gif':
                imagegif($newImage, $image);
                break;
              case 'image/pjpeg':
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($newImage, $image, 90);
                break;
            case 'image/png':
            case 'image/x-png':
                imagepng($newImage, $image);
                break;
        }

        chmod($image, 0777);

        return $image;
    }

    /*
        裁剪头像图片
    */
    public function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale)
    {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);

        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch ($imageType) {
            case 'image/gif':
                $source = imagecreatefromgif($image);
                break;
            case 'image/pjpeg':
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($image);
                break;
            case 'image/png':
            case 'image/x-png':
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
        switch ($imageType) {
            case 'image/gif':
                imagegif($newImage, $thumb_image_name);
                break;
              case 'image/pjpeg':
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($newImage, $thumb_image_name, 90);
                break;
            case 'image/png':
            case 'image/x-png':
                imagepng($newImage, $thumb_image_name);
                break;
        }
        chmod($thumb_image_name, 0777);

        return $thumb_image_name;
    }

    /**
     *编辑我的个人档案.
     */
    public function edit()
    {
        //登录验证
        $this->checkSessionUser();
        $this->pageTitle = $this->ld['account_profile'].' - '.$this->configs['shop_title'];
        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                    //页面初始化
        $id = $_SESSION['User']['User']['id'];
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['account_profile'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        if ($this->RequestHandler->isPost()) {
            if(isset($_POST['submit_review'])){
                $this->data['Users']['verify_status'] = '1';
                $this->data['Users']['unvalidate_note'] = '';
            }
            $this->data['UserAddress']['user_id'] = $id;
            if (isset($this->data['Address']['RegionUpdate'])) {
                $this->data['UserAddress']['regions'] = (isset($this->data['Address']['RegionUpdate'][0]) ? $this->data['Address']['RegionUpdate'][0] : '1').' '.(isset($this->data['Address']['RegionUpdate'][1]) ? $this->data['Address']['RegionUpdate'][1] : '').' '.(isset($this->data['Address']['RegionUpdate'][2]) ? $this->data['Address']['RegionUpdate'][2] : '');
            }
            $this->data['UserAddress']['country'] = isset($this->data['Address']['RegionUpdate'][0]) ? $this->data['Address']['RegionUpdate'][0] : '1';
            $this->data['UserAddress']['province'] = isset($this->data['Address']['RegionUpdate'][1]) ? $this->data['Address']['RegionUpdate'][1] : '';
            $this->data['UserAddress']['city'] = isset($this->data['Address']['RegionUpdate'][2]) ? $this->data['Address']['RegionUpdate'][2] : '';
            if (isset($this->data['UserAddress']['mobile'])) {
                $this->data['Users']['mobile'] = $this->data['UserAddress']['mobile'];
            }
            $this->data['UserAddress']['user_id'] = $this->User->id;
            $this->UserAddress->save($this->data['UserAddress']);
            $this->data['Users']['address_id'] = $this->UserAddress->id;
            $this->User->save($this->data['Users']);
            
            if(isset($this->data['UserConfig']['user_review'])&&!empty($this->data['UserConfig']['user_review'])){
                foreach($this->data['UserConfig']['user_review'] as $k=>$v){
                    $user_config_data=array(
                        'id'=>$v['id'],
                        'user_id'=>$id,
                        'type'=>'user_review',
                        'code'=>$k,
                        'value'=>$v['value']
                    );
                    $this->UserConfig->save($user_config_data);
                }
            }
            //跳转到提示页
            $this->flash($this->ld['success_regist'], '/users/edit', '');
        }
        if (isset($_SESSION['User']['User']['id'])) {
            //分享绑定显示判断
            $app_share = $this->UserApp->app_status();
            $this->set('app_share', $app_share);
            //获取我的信息
            $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
            $_SESSION['User'] = $user_list;
            if ($user_list['User']['address_id'] != '0') {
                //获取我的地址
                $user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
            }
            $this->set('user_list', $user_list);
            //粉丝数量
            $fans = $this->UserFans->find_fanscount_byuserid($id);
            $this->set('fanscount', $fans);
            //日记数量
            $blog = $this->Blog->find_blogcount_byuserid($id);
            $this->set('blogcount', $blog);
            //关注数量
            $focus = $this->UserFans->find_focuscount_byuserid($id);
            $this->set('focuscount', $focus);

            $userRank_end_time = '';
            if ($user_list['User']['rank'] != '0') {
                $max_end_time = $this->UserRankLog->find('first', array('fields' => 'max(end_date) as max_end_time', 'conditions' => array('UserRankLog.user_id' => $_SESSION['User']['User']['id'])));
                if (!empty($max_end_time[0])) {
                    $this_time = date('Y-m-d H:i:s');
                    $end_time = $max_end_time[0]['max_end_time'];
                    if ($end_time != '0000-00-00 00:00:00' && $end_time != '') {
                        if (strtotime($this_time) >= strtotime($end_time)) {
                            //会员已到期
                            $userRank_end_time = '';
                        } else {
                            $userRank_end_time = date('Y-m-d', strtotime($end_time));
                        }
                    }
                }
            }
            $this->set('userRank_end_time', $userRank_end_time);
        }
        //会员等级列表
        $rank_list = $this->UserRank->find('all', array('conditions' => array('UserRankI18n.locale' => $this->locale)));
        $user_rank_data = array();
        foreach ($rank_list as $k => $v) {
            $user_rank_data[$v['UserRank']['id']] = $v['UserRankI18n']['name'];
        }
        $this->set('user_rank_data', $user_rank_data);
        
        //用户配置信息（审核信息）
        $this->UserConfig->set_locale($this->locale);
        $users_config_group_list=array();
        $user_review_configs_data= $this->UserConfig->find('all', array('conditions' => array('UserConfig.user_id' =>array(0,$id), 'type' => 'user_review'),'order'=>'UserConfig.created'));
        $review_configs=array();
        $user_review_data=array();
        if(!empty($user_review_configs_data)){
            foreach($user_review_configs_data as $v){
                if(!empty($v['UserConfig']['group_code'])){
                    $users_config_group_list[$v['UserConfig']['group_code']]=$v['UserConfig']['group_code'];
                }
                if($v['UserConfig']['user_id']==0){
                    $review_configs[$v['UserConfig']['group_code']][]=$v;
                }else{
                    $user_review_data[$v['UserConfig']['type']][$v['UserConfig']['code']]=$v['UserConfig'];
                }
            }
        }
        if(!empty($users_config_group_list)){
            $user_config_group_code=$this->SystemResource->find('all',array('fields'=>array('SystemResource.resource_value','SystemResourceI18n.name'),'conditions'=>array('SystemResource.resource_value'=>$users_config_group_list)));
            foreach($user_config_group_code as $v){
                $user_config_group_list[$v['SystemResource']['resource_value']]=$v['SystemResourceI18n']['name'];
            }
            $this->set('user_config_group_list', $user_config_group_list);
        }
        $this->set('review_configs',$review_configs);
        $this->set('user_review_data',$user_review_data);
    }

    /**
     *修改个人密码.
     */
    public function edit_pwd()
    {
        //登录验证
        $this->checkSessionUser();
        $this->pageTitle = $this->ld['change_password'].' - '.$this->configs['shop_title'];
        $this->layout = 'usercenter';        //引入模版
        $this->page_init();                    //页面初始化
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['change_password'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        if ($this->RequestHandler->isPost()) {
            if (isset($this->data['User']['password1']) && $this->data['User']['password2'] != md5($this->data['User']['password1'])) {
                $this->flash($this->ld['pwd_error'], '/users/edit_pwd', 5);
            } else {
                $this->User->updateAll(
                                          array('User.password' => "'".md5($this->data['User']['password'])."'"),
                                          array('User.id' => $this->data['User']['id'])
                                       );
                    //跳转到提示页
                    $this->flash($this->ld['success_regist'], '/users/edit_pwd', 3);
            }
        }
        if (isset($_SESSION['User']['User']['id'])) {
            //分享绑定显示判断
            $app_share = $this->UserApp->app_status();
            $this->set('app_share', $app_share);
            //获取我的信息
            $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
            $_SESSION['User'] = $user_list;
            if ($user_list['User']['address_id'] != '0') {
                //获取我的地址
                $user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
            }
            $this->set('user_list', $user_list);
            $id = $_SESSION['User']['User']['id'];
            //粉丝数量
            $fans = $this->UserFans->find_fanscount_byuserid($id);
            $this->set('fanscount', $fans);
            //日记数量
            $blog = $this->Blog->find_blogcount_byuserid($id);
            $this->set('blogcount', $blog);
            //关注数量
            $focus = $this->UserFans->find_focuscount_byuserid($id);
            $this->set('focuscount', $focus);
        }
    }

    /**
     *找回密码.
     */
    public function forget_password()
    {
        //页面初始化
        //$this->layout = "default_full";//引入模版
        $this->pageTitle = $this->ld['forget_password'].' - '.$this->configs['shop_title'];
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['forget_password']);
        $this->set('ur_heres', $this->ur_heres);
        $forget_error = '';
        if ($this->RequestHandler->isPost()) {
            if (isset($_POST['email']) && !empty($_POST['email'])) {
                //根据页面填写的Email查询用户
                $user_name = $this->User->find('first', array('conditions' => array('User.email' => $_POST['email'])));
                if (empty($user_name)) {
                    $forget_error = $this->ld['not_exist_email'];
                    $code = 0;
                } else {
                    $condiction['User.email'] = $_POST['email'];
                    $condiction['User.created >'] = date('Y-m-d');
                    $condiction['User.created <'] = date('Y-m-d').' 23:59:59';
                    //申请时间的限制
                    $SmsSend_count = $this->User->find('count', array('conditions' => $condiction));
                    if ($SmsSend_count >= 3) {
                        $forget_error = $this->ld['pwd_error_three_times'];
                    } else {
                        //修改密码
//						$passwd=$this->genPasswd(6);
//						$pwd=md5($passwd);
//						$this->User->updateAll(
//						              array('User.password' =>"'$pwd'"),
//						              array('User.id' => $user_name['User']['id'])
//						           );
                        //发送邮件
                        $mail_info = array();
                //		$this->MailTemplate->set_locale($this->locale);
                        $mail_info['template'] = $this->MailTemplate->find("code = 'send_password' and status = 1 and locale='".$this->locale."'");
                //		$mail_info["title"] = "IOCO找回密码";
                        $mail_info['shop_url'] = $this->server_host.$this->webroot;
                        $mail_info['shop_name'] = $this->configs['shop_name'];
                        $email_md5 = md5($user_name['User']['email']);
                        $mail_info['reset_email'] = $this->server_host.$this->webroot.'users/reset_password?em='.$email_md5;
                        $mail_info['sender'] = $user_name['User']['name'];
                        $mail_info['receiver'] = $user_name['User']['email'];
//						$mail_info['reset_pwd'] = $passwd;
                        $this->User->updateAll(
                                      array('User.mail_pass' => "'".$email_md5."'", 'User.mail_pass_expire_time' => "'".date('Y-m-d H:i:s', strtotime('+1 day'))."'"),
                                      array('User.id' => $user_name['User']['id'])
                                   );

                        //调用发邮件的方法
                        $this->__sendMail($mail_info);
                        $code = 1;
                        $forget_error = $this->ld['sent_password'];
                    }
                }

                if (isset($_POST['is_ajax']) && $_POST['is_ajax'] == '1') {
                    Configure::write('debug', 1);
                    $this->layout = 'ajax';
                    $this->set('forget_error', $forget_error);
                    $this->set('code', $code);
                    $this->render('forget_result');
                } else {
                    $this->flash($forget_error, array('controller' => '/'), '');
                }
            }
        }
        $this->set('forget_error', $forget_error);
    }

    //找回密码成功提示页
//	function forget_susseful()
//	{
//    	$this->page_init();						//页面初始化
//    	$this->layout = "default_full";			//引入模版
//    	$this->pageTitle = "View - ".$this->configs['shop_title'];
//    	//当前位置
//        $this->ur_heres[] = array('name'=>$this->ld['forget_password']);
//        $this->set('ur_heres',$this->ur_heres);
//	}
    public function error()
    {
        $this->flash('adfadsf', '/', 8);
    }

    //重置密码
    public function reset_password()
    {
        if (isset($_SESSION['User']['User'])) {
            unset($_SESSION['User']);
        }
        if ($this->RequestHandler->isPost()) {
            //Configure::write('debug', 1);
            if (isset($_POST['ps']) && !empty($_POST['ps'])) {
                $this->User->updateAll(array('User.password' => "'".md5($_POST['ps'])."'", 'User.mail_pass' => "''"), array('User.id' => $_SESSION['us']));
            }
            $this->pageTitle = '信息提示'.'-'.$this->configs['shop_title'];
            $this->set('url', '/users/login/');
            $this->set('message', '已成功修改，请以新密码重新登入');
            $this->layout = 'flash';

            return;
        }
        if (!isset($_GET['em']) && empty($_GET['em'])) {
            $this->redirect('/');
        }
        $us = $this->User->find('first', array('conditions' => array('User.mail_pass' => $_GET['em']), 'fields' => array('User.id', 'User.mail_pass_expire_time')));
        if ($us['User']['mail_pass_expire_time'] < date('Y-m-d H:i:s')) {
            $this->pageTitle = '信息提示'.'-'.$this->configs['shop_title'];
            $this->set('url', '/users/login/');
            $this->set('message', '重置链接已过期，请重新申请');
            $this->layout = 'flash';

            return;
        }
//		var_dump($us['User']['mail_pass_expire_time'] < date('Y-m-d H:i:s',strtotime('+1 day')));
        if (empty($us)) {
            $this->redirect('/');
        }
        $this->pageTitle = $this->ld['reset_pwd'].'-'.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['reset_pwd']);
        $this->page_init();                        //页面初始化
        $this->layout = 'default_full';
        $_SESSION['us'] = $us['User']['id'];
    }

    //用户确认认证
    public function user_verifyemail()
    {
        $this->page_init();
        $this->pageTitle = $this->ld['send_confirm_mail'].' - '.$this->configs['shop_title'];
        if ($this->RequestHandler->isPost()) {
            $email = $_POST['email'];
            $activation_code = $_POST['activation_code'];
            $user_info = $this->User->find('first', array('conditions' => array('User.email' => $email, 'User.activation_code' => $activation_code)));
            if (!empty($user_info)) {
            } else {
            }
        }
        $this->layout = 'default_full';
    }

    /*
    	用户充值
    */
    public function deposit($page = 1, $limit = 10)
    {
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = $this->ld['user_deposit'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/');
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_deposit'], 'url' => '');
        $id = $_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));

        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //分享绑定显示判断
        $app_share = $this->UserApp->app_status();
        $this->set('app_share', $app_share);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($id);
        $this->set('focuscount', $focus);

        //支付方式
        $payment_list = $this->Payment->find('all', array('conditions' => array('Payment.parent_id !=' => 0, 'Payment.supply_use_flag' => 1, 'Payment.is_online' => 1, 'Payment.status' => '1')));

        $this->set('payment_list', $payment_list);
        //用户资金日志
        $condition['UserBalanceLog.user_id'] = $id;
        $total = $this->UserBalanceLog->find('count', array('conditions' => $condition));//获取总记录数
        $parameters['get'] = array();
        $parameters['route'] = array('controller' => 'Users','action' => 'deposit','page' => $page,'limit' => $limit);
        $options = array('page' => $page,'show' => $limit,'modelClass' => 'UserBalanceLog');
        $this->Pagination->init($condition, $parameters, $options);
        $user_balance_log = $this->UserBalanceLog->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $limit, 'order' => 'UserBalanceLog.created desc'));
        $this->set('user_balance_log', $user_balance_log);
    }

    public function setbalance()
    {
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'default_full';            //引入模版
        $this->pageTitle = $this->ld['user_deposit'].' - '.$this->configs['shop_title'];
        Configure::write('debug',0);
        $this->loadModel('PaymentApiLog');
        if (!empty($_GET['code'])&&!empty($_GET['other_data'])){
            $other_data_str=$_GET['other_data'];
            $other_data_arr=explode("_",$other_data_str);
            $payment_api_id=isset($other_data_arr[2])?$other_data_arr[2]:0;
            $payment_log_info=$this->PaymentApiLog->find('first',array('conditions'=>array('PaymentApiLog.id'=>$payment_api_id)));
            $this->data['pay']['money']=isset($other_data_arr[1])?$other_data_arr[1]:0;
            $this->data['pay']['payment_type']=isset($other_data_arr[0])?$other_data_arr[0]:0;
        }
        if ($this->RequestHandler->isPost()||isset($this->data['pay'])) {
        	if(isset($this->data)){
        		$this->data=$this->clean_xss($this->data);
        	}
            $pay_url = '';
            $message = '操作失败';
            $code = '0';
            if (isset($this->data['pay']) && !empty($this->data['pay'])){
            		$this->data['pay']['payment_type']=intval($this->data['pay']['payment_type']);
            		$this->data['pay']['money']=floatval($this->data['pay']['money']);
                $payment = $this->Payment->find('first', array('conditions' => array('Payment.id' => $this->data['pay']['payment_type'], 'Payment.status' => '1')));
                if (isset($payment) && !empty($payment)) {
                    //用户Id
                    $user_id = $_SESSION['User']['User']['id'];
                    //获取用户信息
                    $user_info = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
                    //定义路径
                    $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
                    $this->set('payment_code',$payment['Payment']['code']);
                    
                    if($payment['Payment']['code']=='weixinpay'){
                        $payment_amount=$this->data['pay']['money'];
                        $amount_money = $payment_amount;
                        //在线支付增加api日志
                        $payment_api_log = array(
                            'id'=>isset($payment_log_info['PaymentApiLog']['id'])?$payment_log_info['PaymentApiLog']['id']:0,
                            'payment_code' => $payment['Payment']['code'],
                            'type' => 2,//充值
                            'type_id' => $user_id,//用户Id
                            'order_currency' => 'CHY',
                            'amount' => $payment_amount//需要支付的金额
                        );
                        $this->PaymentApiLog->save($payment_api_log);
                        $payment_api_log['id'] = $this->PaymentApiLog->id;
                        $payment_config = unserialize($payment['Payment']['config']);
                        
                        $amt=$amount_money*100;
                        try {
                            $wechatpay_type=false;
                            if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
                                App::import('Vendor', 'Weixinpay', array('file' => 'WxPayPubHelper.php'));
                                $jsApi = new JsApi_pub($payment_config['APPID'],$payment_config['MCHID'],$payment_config['KEY'],$payment_config['APPSECRET']);
                                if (empty($_GET['code'])){
                                    $request_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                                    $other_data=$this->data['pay']['payment_type']."_".$this->data['pay']['money']."_".$payment_api_log['id'];
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
                                    $unifiedOrder->setParameter("openid","$openid");//商品描述
    	                            $unifiedOrder->setParameter("body","用户充值[金额：".$payment_amount."]");//商品描述
                                	//自定义订单号，此处仅作举例
                                	$timeStamp = time();
                                	$out_trade_no = $payment_api_log['id'];
                                	$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
                                	$unifiedOrder->setParameter("total_fee",$amt);//总金额
                                	$unifiedOrder->setParameter("notify_url",'http://'.$host.$this->webroot.'responds/weixin_balance');//通知地址
                                	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
                                    $prepay_id = $unifiedOrder->getPrepayId();
                                    $jsApi->setPrepayId($prepay_id);
    	                            $jsApiParameters = $jsApi->getParameters();
                                    if(!empty($jsApiParameters)){
                                        $json_result=json_decode($jsApiParameters);
                                        $code_url = isset($json_result->paySign)?$jsApiParameters:'';
                                    }
                                }else{
                                    throw new SDKRuntimeException("支付失败,OpenId 获取失败");
                                }
                            }else{
                            	Configure::write('debug', 0);
                    		$this->layout = 'ajax'; 
                                $wechatpay_type=true;
                                App::import('Vendor', 'Weixinpay', array('file' => 'WxPay.Api.php'));
                    			App::import('Vendor', 'Phpqcode', array('file' => 'phpqrcode.php'));
                            	$input = new WxPayUnifiedOrder();
                                $input->SetKey($payment_config['KEY']);
                    			$input->SetBody("用户充值[金额：".$payment_amount."]");
                    			$input->SetAttach("用户充值");
                    			$input->SetOut_trade_no($payment_api_log['id']."_".time()."_".rand(0,1000));
                                $input->SetAppid($payment_config['APPID']);
        			            $input->SetMch_id($payment_config['MCHID']);
                    			$input->SetTotal_fee($amt);
                    			$input->SetTime_start(date("YmdHis"));
                    			$input->SetTime_expire(date("YmdHis", time() + 600));
                    			$input->SetGoods_tag("用户充值");
                    			$input->SetNotify_url('http://'.$host.$this->webroot.'responds/weixin_balance');
                    			$input->SetProduct_id($payment_api_log['id']);
                                $input->SetTrade_type("NATIVE");
                                $notify = new NativePay();
                                $result = $notify->GetPayUrl($input);
                                $code_url = isset($result["code_url"])?$result["code_url"]:'';
                            }
                            $this->set('wechatpay_type',$wechatpay_type);
                            $message = '';
                            $code = '1';
                        } catch (Exception $e) {
                            $message = '支付失败，Caught exception: '.$e->getMessage();
                            $code = '0';
                        }
                    }else{
                        //判断支付方式是否存在
                        $payment['Payment']['code'] = strtolower($payment['Payment']['code']);
                        try {
                            $payment_config = unserialize($payment['Payment']['config']);
                            App::import('Vendor', 'payments/'.$payment['Payment']['code']);
                            $balance_payment = new $payment['Payment']['code']();
                            if ($payment['Payment']['is_online'] == 1) {
                                //在线支付增加api日志
                                $payment_api_log = array(
                                    'payment_code' => $payment['Payment']['code'],
                                    'type' => 2,//充值
                                    'type_id' => $user_id,//用户Id
                                    'order_currency' => 'CHY',
                                    'amount' => $this->data['pay']['money'],//需要支付的金额
                                );
                                $this->PaymentApiLog->save($payment_api_log);
                                //记录支付日志Id
                                $payment_api_log['id'] = $this->PaymentApiLog->id;
                            }
                            $payment_api_log['name'] = $user_info['User']['name'];
                            $payment_api_log['payerAdderss'] = $user_info['User']['address_id'];
                            $payment_api_log['payerName'] = $user_info['User']['name'];
                            $payment_api_log['created'] = date('Y-m-d H:i:s', time());
                            $payment_config['cancel_return'] = 'http://'.$host.$this->webroot;
                            $payment_config['return_url'] = 'http://'.$host.$this->webroot.'responds/return_code/'.$payment['Payment']['code'];
                            //描述
                            $payment_api_log['subject'] = '['.$user_info['User']['name'].']用户充值';
                            $payment_api_log['host'] = $host;
                            if ($payment['Payment']['code'] == 'money' || $payment['Payment']['code'] == 'bank_trans'  || $payment['Payment']['code'] == 'pos_pay') {
                                $payment_config['co'] = '';
                            }
                            $api_code = $balance_payment->go2($payment_api_log, $payment_config);
                            $_SESSION['api_code'] = $api_code;
                            $message = '';
                            $code = '1';
                        } catch (Exception $e) {
                            $message = '支付失败，Caught exception: '.$e->getMessage();
                            $code = '0';
                        }
                    }
                } else {
                    $message = '该支付方式无效或不可用!';
                    $code = '0';
                }
            }
            if (isset($api_code)) {
                $this->layout=null;
                $result['pay_url'] = isset($api_code) ? $api_code : $pay_url;
                $this->set('pay_url', $api_code);
            }else if(isset($code_url)&&$code_url!=""){
                $this->set('pay_url', $code_url);
                $this->set('payment_api_id',$payment_api_log['id']);
            }else {
                //跳转到提示页
                $this->flash($message, '/users/deposit', '');
            }
        } else {
            $this->redirect('/users/deposit');
        }
    }
    
    public function checkwechatpay(){
        //登录验证
        $this->checkSessionUser();
        Configure::write('debug', 0);
        $result['code']=0;
        if ($this->RequestHandler->isPost()) {
            $user_id = $_SESSION['User']['User']['id'];
            $this->loadModel('PaymentApiLog');
            
            $payment_api_id=isset($_POST['payment_api_id'])?$_POST['payment_api_id']:0;
            
            $conditions['PaymentApiLog.payment_code']="weixinpay";
            $conditions['PaymentApiLog.type']=2;
            $conditions['PaymentApiLog.type_id']=$user_id;
            $conditions['PaymentApiLog.id']=$payment_api_id;
            
            $payment_api_log =$this->PaymentApiLog->find('first',array('conditions'=>$conditions));
            if(isset($payment_api_log)&&$payment_api_log['PaymentApiLog']['is_paid']=='1'){
                $result=1;
            }
        }
        die(json_encode($result));
    }

    public function enquiries($page = 1)
    {
    	$_GET=$this->clean_xss($_GET);
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = $this->ld['enquiry'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/');
        $this->ur_heres[] = array('name' => $this->ld['enquiry'], 'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $user_id = $_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //分享绑定显示判断
        $app_share = $this->UserApp->app_status();
        $this->set('app_share', $app_share);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);

        $enquiry_status = '';
        if (isset($_GET['enquiry_status']) && $_GET['enquiry_status'] != '') {
            $enquiry_status = $_GET['enquiry_status'];
            if ($enquiry_status != '-1') {
                $condition['Enquiry.status'] = $enquiry_status;
            } else {
                $condition['Enquiry.status'] = array('0','1');
            }
        }
        $score_status = '0';
        if (isset($_GET['score_status']) && $_GET['score_status'] != '') {
            $score_status = $_GET['score_status'];
        }
        $this->set('enquiry_status', $enquiry_status);
        $condition['Enquiry.user_id'] = $user_id;
        $total = $this->Enquiry->find('count', array('conditions' => $condition));
        $limit = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array('enquiry_status='.$enquiry_status.'&score_status='.$score_status);
        $parameters['route'] = array('controller' => 'users', 'action' => 'enquiries', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit,'total' => $total, 'modelClass' => 'Enquiry');
        $page = $this->Pagination->init($condition, $parameters, $options); //Added
        //分页end
        $enquiries_list = $this->Enquiry->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $limit, 'order' => 'Enquiry.created desc'));
        $this->set('enquiries_list', $enquiries_list);
        if (!empty($enquiries_list) && sizeof($enquiries_list) > 0) {
            $product_code = array();
            foreach ($enquiries_list as $k => $v) {
                $pc_arr = explode(';', $v['Enquiry']['part_num']);
                foreach ($pc_arr as $pk => $pv) {
                    $product_code[] = $pv;
                }
            }
            $product_code_arr = $this->Product->find('all', array('fields' => array('Product.id', 'Product.code', 'ProductI18n.name'), 'conditions' => array('Product.code' => $product_code, 'ProductI18n.locale' => $this->locale, 'Product.status' => '1', 'Product.forsale' => '1')));
            $product_code_list = array();
            $product_id_list = array();
            foreach ($product_code_arr as $k => $v) {
                $product_code_list[$v['Product']['code']] = $v['ProductI18n']['name'];
                $product_id_list[$v['Product']['code']] = $v['Product']['id'];
            }
            $this->set('product_code_list', $product_code_list);
            $this->set('product_id_list', $product_id_list);
            if ($score_status != '0') {
                $_scorelog_list = $this->ScoreLog->find('all', array('fields' => array('count(*) as countnum', 'ScoreLog.type_id'), 'conditions' => array('ScoreLog.type' => 'P', 'ScoreLog.type_id' => $product_id_list, 'ScoreLog.user_id' => $user_id), 'group' => 'ScoreLog.type_id'));
                $scorelog_list = array();
                foreach ($_scorelog_list as $k => $v) {
                    $scorelog_list[$v['ScoreLog']['type_id']] = $v[0]['countnum'];
                }
                $this->set('scorelog_list', $scorelog_list);
            }
        }
    }

    /*
        ajax动态获取用户信息，并更新$_session
    */
    public function ajax_getUserInfo()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $userInfo = $this->User->changeUserSession();//输出用户信息
        if (empty($userInfo)) {
            $result['code'] = '0';
            $result['msg'] = 'user_not_exist';
            die(json_encode($result));
        } else {
            die(json_encode($userInfo));
        }
    }

    public function real_ip()
    {
        static $realip = null;
        if ($realip !== null) {
            return $realip;
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

        return $realip;
    }

    /**
     *产生激活码.
     */
    public function get_activation_code()
    {
        mt_srand((double) microtime() * 1000000);
        $code = md5(date('H:i:s').mt_rand(1, 9999));

        return $code;
    }

    /**
     *推荐商品.
     */
    public function recgoods()
    {
        $this->layout = 'usercenter';
    }

    /**
     *我的标签.
     */
    public function labels()
    {
        $this->layout = 'usercenter';
    }

    /**
     *我的好友.
     */
    public function friends()
    {
        $this->layout = 'usercenter';
    }

    /**
     *我的推荐.
     */
    public function recomment()
    {
        $this->layout = 'usercenter';
    }

    /**
     *我的优惠券.
     */
    public function coupons()
    {
        $this->layout = 'usercenter';
    }

    /**
     *已购买.
     */
    public function purchased()
    {
        $this->layout = 'usercenter';
    }

    //验证码
    public function captcha()
    {
        if ($this->RequestHandler->isPost()) {
            $securimage_code_value = isset($_SESSION['securimage_code_value']) ? $_SESSION['securimage_code_value'] : '';
            $this->layout = 'ajax';
            Configure::write('debug', 0);
            die(json_encode($securimage_code_value));
        } else {
            $this->layout = 'blank'; //a blank layout
            $this->captcha->show(); //dynamically creates an image
            exit();
        }
    }

    //用户确认认证
    public function check_input()
    {
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost()) {
            if (!empty($_POST['account'])) {
                $result['type'] = 'account';
                $result['type_id'] = $_POST['type_id'];
                if ($this->User->find('first', array('conditions' => array('User.name' => $_POST['account'])))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['nickname_exists'];
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['nickname_be_used'];
                }
                die(json_encode($result));
            }
            if (!empty($_POST['sn_email'])) {
                $condition = array();
                $condition['or']['User.user_sn'] = $_POST['sn_email'];
                $condition['or']['User.email'] = $_POST['sn_email'];
                $result['type'] = 'sn_email';
                $result['type_id'] = $_POST['type_id'];
                if ($this->User->find('first', array('conditions' => $condition))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['email_has_been_registered'];
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['email_can_be_used'];
                }
                die(json_encode($result));
            }
            if (!empty($_POST['email'])) {
                $result['type'] = 'email';
                $result['type_id'] = $_POST['type_id'];
                if ($this->User->find('first', array('conditions' => array('User.email' => $_POST['email'])))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['email_exists'];
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['email_can_be_used'];
                }
                die(json_encode($result));
            }
            if (!empty($_POST['mobile'])) {
                $result['type'] = 'mobile';
                $result['type_id'] = $_POST['type_id'];
                if ($this->User->find('first', array('conditions' => array('User.mobile' => $_POST['mobile'])))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['mobile_exists'];
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['mobile_can_be_used'];
                }
                die(json_encode($result));
            }
        }
        die();
    }

    public function bind()
    {
        //登录验证
        $this->checkSessionUser();
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => '账号绑定','url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->pageTitle = '账号绑定'.' - '.$this->configs['shop_title'];
        $synchro_user = ClassRegistry::init('SynchroUser')->find('list', array('conditions' => array('SynchroUser.user_id' => $_SESSION['User']['User']['id']), 'fields' => array('SynchroUser.type', 'SynchroUser.email')));
        $this->set('synchro_user', $synchro_user);
        $this->layout = 'usercenter';
        $this->page_init();
        $this->set('sev', $this->server_host);
    }

    //客户退出
    public function logout()
    {
        unset($_SESSION['User']);
        if (isset($_SESSION['svcart'])) {
            unset($_SESSION['svcart']);
        }
        if (isset($_SESSION['payment_tp']) && !empty($_SESSION['payment_tp'])) {
            unset($_SESSION['payment_tp']);
        }
        if (isset($_SESSION['payment_tk']) && !empty($_SESSION['payment_tk'])) {
            unset($_SESSION['payment_tk']);
        }
        setcookie('user_info', '', time() - 60 * 60 * 24 * 14, '/');
        //$this->redirect(array("action"=>"login"));
        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
        $lan = array('/en/','/cn/','/jp/');
        $back_url = str_replace($lan, '/', $back_url);
        $this->redirect($back_url);
    }

    //发邮件
    public function __sendMail($arg = array())
    {
        $user_name = isset($arg['sender']) ? $arg['sender'] : '';
        eval("\$user_name = \"$user_name\";");
        if (isset($arg['reset_email'])) {
            $reset_email = $arg['reset_email'];
            eval("\$reset_email = \"$reset_email\";");
        }
        if (isset($arg['shop_name'])) {
            $shop_name = $arg['shop_name'];
            eval("\$shop_name = \"$shop_name\";");
        }
        if (isset($arg['shop_url'])) {
            $shop_url = $arg['shop_url'];
            eval("\$shop_url = \"$shop_url\";");
        }
        $send_date = date('Y-m-d H:i:s');
        eval("\$send_date = \"$send_date\";");
        if (isset($arg['template']['MailTemplateI18n']['title'])) {
            $subject = $arg['template']['MailTemplateI18n']['title'];
            eval("\$subject = \"$subject\";");
        }
        $html_body = $arg['template']['MailTemplateI18n']['html_body'];
        $aa = addslashes($html_body);
        eval("\$html_body = \"$aa\";");
        $text_body = $arg['template']['MailTemplateI18n']['text_body'];
        eval("\$text_body = \"$text_body\";");
        $mail_send_queue = array(
                'id' => '',
                'sender_name' => $arg['sender'],
                'receiver_email' => ';'.$arg['receiver'],//接收人姓名;接收人地址
                'cc_email' => ';'.$arg['receiver'],
                'bcc_email' => ';'.$arg['receiver'],
                'title' => $subject,
                'html_body' => $html_body,
                'text_body' => $text_body,
                'sendas' => 'html',
                'flag' => 0,
                'pri' => 0,
        );
        /*
        if(!empty($arg['phone'])){
            $this->save_sms($arg['phone'],$mail_send_queue['text_body'],$arg['user_id'],$arg['user_type']);
        }
        */
        $result = $this->Email->send_mail($this->locale, 1, $mail_send_queue, $this->configs);//$this->configs['email_the_way']
    }

     //随即产生密码
    public function genPasswd($num)
    {
        $passwd = '';
        $chars = array(
                  'digits' => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
                  'lower' => array(
                      'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
                      'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
                  ),
                  'upper' => array(
                      'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                      'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                  ),
              );
        $charTypes = array_keys($chars);
        $numTypes = count($charTypes) - 1;
        for ($i = 0; $i < $num; ++$i) {
            $charType = $charTypes[ mt_rand(0, $numTypes) ];
            $passwd .= $chars[$charType][
                      mt_rand(0, count($chars[$charType]) - 1)
                  ];
        }

        return $passwd;
    }

    public function synchro_user()
    {
        $this->pageTitle = $this->ld['member_login'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['member_login'], 'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->set('sev', $this->server_host);
    }

    public function syn_reg()
    {
        if ($this->RequestHandler->isPost()) {
            $user = array();
            $user['User']['user_sn'] = $_SESSION['syn_pkg']['email'];
            $user['User']['name'] = isset($_POST['user_name']) ? $_POST['user_name'] : $_SESSION['syn_pkg']['email'];
            $user['User']['first_name'] = $_SESSION['syn_pkg']['email'];
            $user['User']['img01'] = isset($_SESSION['syn_pkg']['img01']) ? $_SESSION['syn_pkg']['img01'] : '';
            $user['User']['img02'] = isset($_SESSION['syn_pkg']['img02']) ? $_SESSION['syn_pkg']['img02'] : '';
            $user['User']['img03'] = isset($_SESSION['syn_pkg']['img03']) ? $_SESSION['syn_pkg']['img03'] : '';
            $user['User']['password'] = md5($_SESSION['syn_pkg']['email']);
            $user['User']['email'] = isset($_POST['user_email']) ? $_POST['user_email'] : '';
            $user['User']['mobile'] = isset($_POST['user_phone']) ? $_POST['user_phone'] : '';
            $user['User']['unvalidate_note'] = $_SESSION['syn_pkg']['type'];
            $this->User->save($user);
            $uid = $this->User->id;
            $info = array();
            $info['SynchroUser']['user_id'] = $uid;
            $info['SynchroUser']['email'] = $_SESSION['syn_pkg']['email'];
            $info['SynchroUser']['account'] = $_SESSION['syn_pkg']['account'];
            $info['SynchroUser']['type'] = $_SESSION['syn_pkg']['type'];
            $info['SynchroUser']['oauth_token'] = $_SESSION['syn_pkg']['oauth_token'];
            $info['SynchroUser']['oauth_token_secret'] = $_SESSION['syn_pkg']['oauth_token_secret'];
            ClassRegistry::init('SynchroUser')->save($info);
            $_SESSION['User'] = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
            $this->get_back();
        }
        die();
    }

    public function check_syn_mail()
    {
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost()) {
            if (!empty($_POST['user_name'])) {
                if ($this->User->find('first', array('conditions' => array('User.name' => $_POST['user_name'])))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['nickname_exists'];
                    die(json_encode($result));
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['email_can_be_used'];
                }
            }
            if (!empty($_POST['user_email'])) {
                if ($this->User->find('first', array('conditions' => array('User.email' => $_POST['user_email'])))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['email_has_been_registered'];
                    die(json_encode($result));
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['email_can_be_used'];
                }
            }
            die(json_encode($result));
        }
    }

    public function syn_reg2()
    {
        if ($this->RequestHandler->isPost()) {
            $info = array();
            $info['SynchroUser']['user_id'] = $_SESSION['User']['User']['id'];
            $info['SynchroUser']['email'] = $_SESSION['syn_pkg']['email'];
            $info['SynchroUser']['account'] = $_SESSION['syn_pkg']['account'];
            $info['SynchroUser']['type'] = $_SESSION['syn_pkg']['type'];
            $info['SynchroUser']['oauth_token'] = $_SESSION['syn_pkg']['oauth_token'];
            $info['SynchroUser']['oauth_token_secret'] = $_SESSION['syn_pkg']['oauth_token_secret'];
            //$x=ClassRegistry::init("SynchroUser")->find('list',array('conditions'=>array('SynchroUser.user_id'=>$info['SynchroUser']['user_id'],'SynchroUser.account'=>$info['SynchroUser']['account'],'SynchroUser.user_id'=>$info['SynchroUser']['type']),'fields'=>array('SynchroUser.id')));
            ClassRegistry::init('SynchroUser')->save($info);
            $user = $_SESSION['User'];
            $user['User']['img01'] = isset($_SESSION['syn_pkg']['img01']) ? $_SESSION['syn_pkg']['img01'] : '';
            $user['User']['img02'] = isset($_SESSION['syn_pkg']['img02']) ? $_SESSION['syn_pkg']['img02'] : '';
            $user['User']['img03'] = isset($_SESSION['syn_pkg']['img03']) ? $_SESSION['syn_pkg']['img03'] : '';
            if (empty($user['User']['unvalidate_note'])) {
                $user['User']['unvalidate_note'] = $_SESSION['syn_pkg']['type'];
            } else {
                $user['User']['unvalidate_note'] .= ','.$_SESSION['syn_pkg']['type'];
            }
            ClassRegistry::init('User')->save($user);
            $_SESSION['User'] = $this->User->find('first', array('conditions' => array('User.id' => $user['User']['id'])));
            $this->get_back();
        }
        die();
    }

    public function syn_check()
    {
        $result['type'] = 2;
        $result['message'] = $this->ld['invalid_operation'];
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        $user_id = $_POST['id'];
        $ps = $_POST['ps'];
        $ps = md5($ps);
        $x = $this->User->find('first', array('conditions' => array('User.email' => $user_id, 'User.password' => $ps)));
        if (!empty($x)) {
            $result['type'] = 0;
            $_SESSION['User'] = $x;
        }
        die(json_encode($result));
    }

    public function get_back()
    {
        if (isset($_SESSION['login_back'])) {
            if ($_SESSION['login_back'] == '/en/' || $_SESSION['login_back'] == '/cn/' || $_SESSION['login_back'] == '/jp/') {
                $_SESSION['login_back'] = '/';
            }
        } else {
            $_SESSION['login_back'] = '/';
        }
        if ($_SESSION['login_back'] == '/flashes/index/H') {
            unset($_SESSION['login_back']);
        }
        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
//		$lan=array('/en','/cn','/jp');
//		$back_url=str_replace($lan,'',$back_url);
        $this->redirect($back_url);
    }

    public function test()
    {
        unset($_SESSION);
        die();
    }

    //创建路径
    public function mkdirs($path, $mode = 0777)
    {
        $dirs = explode('/', $path);
        $pos = strrpos($path, '.');
        if ($pos === false) {
            $subamount = 0;
        } else {
            $subamount = 1;
        }
        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
            $thispath = '';
            for ($cc = 0; $cc <= $c; ++$cc) {
                $thispath .= $dirs[$cc].'/';
            }
            if (!file_exists($thispath)) {
                mkdir($thispath, $mode);
                chmod($thispath, $mode);
            } else {
                @chmod($thispath, $mode);
            }
        }
    }

    public function other_login()
    {
        $syns = $this->UserApp->find('list', array('conditions' => array('UserApp.status' => 1, 'UserApp.type !=' => 'Wechat'), 'fields' => array('UserApp.type')));
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        if (!empty($syns)) {
            $result['type'] = 0;
            $result['syns'] = $syns;
        } else {
            $result['type'] = 1;
            $result['syns'] = '';
        }
        die(json_encode($result));
    }
    
    function ajax_upload_files(){
        Configure::write('debug',0);
        $this->layout = 'ajax';
        $result=array();
        $result['code'] = 0;
        $result['msg'] = 'not file';
        if($this->RequestHandler->isPost()){
            $file_root = 'media/users/files/';
            $fileaddr = WWW_ROOT.'media/users/files/';
            $this->mkdirs($fileaddr);
            
            $fileCode=isset($_POST['fileCode'])?$_POST['fileCode']:'';
            
            if(!empty($fileCode)&&!empty($_FILES[$fileCode])){
                $userfile_name = $_FILES[$fileCode]['name'];
                $userfile_tmp = $_FILES[$fileCode]['tmp_name'];
                $userfile_size = $_FILES[$fileCode]['size'];
                $userfile_type = $_FILES[$fileCode]['type'];
                $filename = basename($_FILES[$fileCode]['name']);
                $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
                
                $file_location = $fileaddr.md5(date('Y-m-d h:i:s').$userfile_name).'.'.$file_ext;
                $file_name = '/'.$file_root.md5(date('Y-m-d h:i:s').$userfile_name).'.'.$file_ext;
                if (move_uploaded_file($userfile_tmp, $file_location)) {
                    $result['code'] = 1;
                    $result['file_name'] = $file_name;
                    $result['file_location'] = $file_location;
                    $result['file_type'] = mime_content_type($file_location);
                    $result['msg'] = '';
                }else{
                    $result['msg'] = 'File not found';
                }
            }
        }
        die(json_encode($result));
    }
    
    function ajax_remove_files(){
        Configure::write('debug',0);
        $this->layout = 'ajax';
        $result=array();
        $result['code'] = 0;
        $result['msg'] = 'not file';
        if($this->RequestHandler->isPost()){
            $fileaddr = WWW_ROOT;
            $file_root = isset($_POST['FileUrl'])?$_POST['FileUrl']:'';
            if(!empty($file_root)){
                $fileaddr.=$file_root;
            }
            if(is_file($fileaddr)){
                @unlink($fileaddr);
                $result['code'] = 1;
                $result['msg'] = '';
            }
        }
        die(json_encode($result));
    }
    
    function ajax_login(){
    	 	Configure::write('debug',1);
        	$this->layout = null;
        	
        	$syns = $this->UserApp->find('list', array('conditions' => array('UserApp.status' => 1, 'UserApp.type !=' => 'Wechat'), 'fields' => array('UserApp.type')));
        	$this->set('syns', $syns);
        	if($this->RequestHandler->isPost()){
        		
        	}else{
        		//$this->redirect('/pages/home');
        	}
    }
    
    function ajax_register(){
    	 	Configure::write('debug',1);
        	$this->layout = null;
        	
        	if($this->RequestHandler->isPost()){
        		
        	}else{
        		//$this->redirect('/pages/home');
        	}
    }
}
