<?php

/*****************************************************************************
 * Seevia 用户管理
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 UsersController 的控制器
 *后台用户管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class UsersController extends AppController
{
    public $name = 'Users';
    public $components = array('Pagination','RequestHandler','Phpexcel','Orderfrom','Phpcsv','Email');
    public $helpers = array('Pagination');
    public $uses = array('ConfigI18n','Resource','User','UserAddress','UserBalanceLog','UserPointLog','Application','OperatorLog','UserChat','UserLike','UserFan','UserMessage','UserVisitors','Blog','UserAction','Comment','SynchroUser','UserRank','UserRankLog','Operator','ScoreLog','Profile','ProfileFiled','UserConfig','MailTemplate');

    public $UserApp_Type = array('SinaWeibo' => 'sina.png','QQWeibo' => 'qq.jpg','wechat' => 'wechat.png','wechatauth' => 'wechat.png','QQ' => 'qie.png');

    /**
     *显示用户列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('users_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
        $condition = '';
        $condition['User.type ='] = 0;
        //会员搜索筛选条件
        if (isset($_REQUEST['user_keyword']) && $_REQUEST['user_keyword'] != '') {
            $condition['or']['User.user_sn like'] = '%'.$_REQUEST['user_keyword'].'%';
            $condition['or']['User.email like'] = '%'.$_REQUEST['user_keyword'].'%';
            $condition['or']['User.mobile like'] = '%'.$_REQUEST['user_keyword'].'%';
            $condition['or']['User.name like'] = '%'.$_REQUEST['user_keyword'].'%';
            $condition['or']['User.first_name like'] = '%'.$_REQUEST['user_keyword'].'%';
            $this->set('user_keyword', $_REQUEST['user_keyword']);
        }
        if (isset($_REQUEST['min_balance']) && $_REQUEST['min_balance'] != '') {
            $condition['User.balance >='] = $_REQUEST['min_balance'];
            $this->set('min_balance', $_REQUEST['min_balance']);
        }
        if (isset($_REQUEST['max_balance']) && $_REQUEST['max_balance'] != '') {
            $condition['User.balance <='] = $_REQUEST['max_balance'];
            $this->set('max_balance', $_REQUEST['max_balance']);
        }
        if (isset($_REQUEST['min_points']) && $_REQUEST['min_points'] != '') {
            $condition['User.point >='] = $_REQUEST['min_points'];
            $this->set('min_points', $_REQUEST['min_points']);
        }
        if (isset($_REQUEST['max_points']) && $_REQUEST['max_points'] != '') {
            $condition['User.point <='] = $_REQUEST['max_points'];
            $this->set('max_points', $_REQUEST['max_points']);
        }
        if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') {
            $condition['User.created  >='] = $_REQUEST['start_date'];
            $this->set('start_date', $_REQUEST['start_date']);
        }
        if (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') {
            $condition['User.created  <='] = $_REQUEST['end_date'].' 23:59:59';
            $this->set('end_date', $_REQUEST['end_date']);
        }
        if (isset($_REQUEST['verify_status']) && $_REQUEST['verify_status'] != '-1') {
            $condition['User.verify_status'] = $_REQUEST['verify_status'];
            $this->set('verify_status', $_REQUEST['verify_status']);
        }
        //会员来源搜索菜单
        $type_arr = array();
        $ta_str = '';
        if (isset($_REQUEST['ta']) && $_REQUEST['ta'] != '') {
            $type_arr = explode(',', $_REQUEST['ta']);
            foreach ($type_arr as $k => $v) {
                $type_arr_detail = explode(':', $v);
                if (sizeof($type_arr_detail) == 2) {
                    $condition['and']['or'][$k]['User.user_type'] = $type_arr_detail[0];
                    $condition['and']['or'][$k]['User.user_type_id'] = $type_arr_detail[1];
                }
                $ta_str = $_REQUEST['ta'];
            }
        }
        $this->set('type_arr', $type_arr);
        $this->set('ta_str', $ta_str);
        //订单来源
        $total = $this->User->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'User';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'users','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'User');
        $this->Pagination->init($condition, $parameters, $options);
        if (isset($_GET['email_flag']) && $_GET['email_flag'] == 1) {
            $condition['User.email !='] = '';
            $users_email_id = $this->User->find('list', array('fields' => 'User.id', 'conditions' => $condition));
            if (!empty($users_email_id)) {
                $this->User->updateAll(array('User.email_flag' => '1'), array('User.id' => $users_email_id));
                $this->redirect('/email_lists/');
            }
        }
        $users_list = $this->User->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'created desc'));
        $users_ids = array();
        if (isset($users_list)) {
            foreach ($users_list as $k => $v) {
                $users_ids[] = $v['User']['id'];
            }
        }
        if (sizeof($users_ids) > 0) {
            $SynchroUser_list = $this->SynchroUser->getInfoByUser($users_ids);
            $this->set('SynchroUser_list', $SynchroUser_list);
            $this->set('user_App_list', $this->UserApp_Type);
        }
        $user_name = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : '';
        $min_balance = isset($_REQUEST['min_balance']) ? $_REQUEST['min_balance'] : '';
        $max_balance = isset($_REQUEST['max_balance']) ? $_REQUEST['max_balance'] : '';
        $min_points = isset($_REQUEST['min_points']) ? $_REQUEST['min_points'] : '';
        $max_points = isset($_REQUEST['max_points']) ? $_REQUEST['max_points'] : '';
        $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
        $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
        $verify_status = isset($_REQUEST['verify_status']) ? $_REQUEST['verify_status'] : '';
        $this->set('users_list', $users_list);
        $this->set('user_name', $user_name);
        $this->set('min_balance', $min_balance);
        $this->set('max_balance', $max_balance);
        $this->set('min_points', $min_points);
        $this->set('max_points', $max_points);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        //资源库信息
        $language = $this->backend_locale;
        $Resource_info = $this->Resource->getformatcode(array('verify_status', 'order_status', 'shipping_status', 'payment_status'), $language);
        $this->set('Resource_info', $Resource_info);

        //会员等级
        $this->UserRank->set_locale($this->backend_locale);
        $rank_list = $this->UserRank->find('all');
        $rank_data = array();
        foreach ($rank_list as $k => $v) {
            $rank_data[$v['UserRank']['id']] = $v['UserRankI18n']['name'];
        }
        $this->set('rank_data', $rank_data);
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Order');
            $this->Orderfrom->get($this);
            //获取用户订单信息
            $all_order_infos = $this->Order->find('all', array('recursive' => -1, 'conditions' => array('Order.status <>' => 0, 'user_id' => $users_ids)));
            if (!empty($all_order_infos)) {
                foreach ($all_order_infos as $v) {
                    $v['Order']['should_pay'] = $v['Order']['total'] - $v['Order']['money_paid'] - $v['Order']['point_fee'] - $v['Order']['discount'];
                    $v['Order']['subtotal'] = $v['Order']['subtotal'];
                    $v['Order']['total'] = $v['Order']['total'];
                    $user_order_infos[$v['Order']['user_id']][] = $v;
                    $this->set('user_order_infos', $user_order_infos);
                }
            }
            $user_order_infos_ori = $this->Order->findAllByUserId('3');
            foreach ($user_order_infos_ori as $v) {
                $v['Order']['should_pay'] = $v['Order']['total'] - $v['Order']['money_paid'] - $v['Order']['point_fee'] - $v['Order']['discount'];
                $user_order_infos2[] = $v['Order'];
            }
        }
        if (constant('Version') == 'o2o') {
            $this->Orderfrom->get($this);
        }
        if (!empty($this->params['url'])) {
            $url = $this->params['url']['url'].'?';
            foreach ($this->params['url'] as $k => $v) {
                if ($k == 'url') {
                } else {
                    $url .= $k.'='.$v.'&';
                }
            }
        }
        $_SESSION['index_url'] = $url;
        $this->set('title_for_layout', $this->ld['users_search'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    /**
     *会员认证和取消.
     *
     *@param string $act 要处理的代码  即  认证 或  取消
     *@param int $id 用户ID
     */
    public function user_status()
    {
        $this->operator_privilege('users_confirm');
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $status_code=isset($_POST['status_code'])?$_POST['status_code']:'1';
        $user_id=isset($_POST['user_id'])?$_POST['user_id']:0;
        $unvalidate_note=isset($_POST['remark'])?$_POST['remark']:'';
        $user_info=$this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
        
        if (!empty($user_info)) {
        	$user_data=array();
        	$user_data['id']=$user_id;
        	$user_data['verify_status']=$status_code;
        	$user_data['unvalidate_note']=$unvalidate_note;
        	$this->User->save(array("User"=>$user_data));
        	
		$mailtemplete_code="user_authentication";
		$this->MailTemplate->set_locale($this->backend_locale);
		$template = $this->MailTemplate->find('first', array('conditions' => array('MailTemplate.code' => $mailtemplete_code, 'MailTemplate.status' => 1)));
        	if (!empty($template)&&!empty($user_info['User']['email'])) {
        		extract($user_info['User'],EXTR_PREFIX_ALL,'User');
        		
	        	$Resource_info = $this->Resource->getformatcode(array('verify_status'), $this->backend_locale);
	        	$verify_status_name=isset($Resource_info['verify_status'][$status_code])?$Resource_info['verify_status'][$status_code]:'';
	        	
			$html_body = $template['MailTemplateI18n']['html_body'];
			@eval("\$html_body = \"$html_body\";");
			$text_body = $template['MailTemplateI18n']['text_body'];
			@eval("\$text_body = \"$text_body\";");
			$title = $template['MailTemplateI18n']['title'];
			
			$mailsendqueue = array(
	                    'sender_name' => empty($this->configs['shop_name']) ? '--' : $this->configs['shop_name'],//发送从姓名
	                    'receiver_email' => $user_info['User']['name'].';'.$user_info['User']['email'],//接收人姓名;接收人地址
	                    'cc_email' => '',//抄送人
	                    'bcc_email' => '',//暗送人
	                    'title' => $title,//主题
	                    'html_body' => $html_body,//内容
	                    'text_body' => $text_body,//内容
	                    'sendas' => 'html',
	                );
	        	$mail_result=$this->Email->send_mail($this->backend_locale,1,$mailsendqueue,$this->configs);
		}
        }
        $result['flag'] = 1;
        $result['message'] = 'operation_success';
        die(json_encode($result));
    }

    /**
     *编辑会员页.
     *
     *@param int $id 用户ID
     */
    public function view($id)
    {
        if ($this->admin['type'] == 'S') {
            //会员来源
            $this->user_type();
        }
        $this->operator_privilege('users_edit');
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->set('title_for_layout', $this->ld['edit_member'].'-'.$this->ld['users_search'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
        $this->navigations[] = array('name' => $this->ld['edit_member'],'url' => '');
        $baler = $this->Application->find('first', array('conditions' => array('Application.code' => 'APP-SHOP')));
        if (!empty($baler)) {
            $this->set('baler', 1);
        }
        $user_info = $this->User->find('first', array('conditions' => array('id' => $id)));//会员基本信息
        $this->navigations[] = array('name' => $user_info['User']['name'],'url' => '');//导航显示

        $this->loadModel('UserStyle');
        $this->loadModel('ProductStyle');
        $this->loadModel('ProductStyleI18n');
        //用户模板
        $user_style_list = $this->UserStyle->find('all', array('conditions' => array('UserStyle.user_id' => $id), 'order' => 'UserStyle.type_id'));
        //用户模板弹窗版型下拉
        $this->ProductStyle->set_locale($this->backend_locale);
        $style_list = $this->ProductStyle->find('all', array('conditions' => array('ProductStyle.status' => 1), 'order' => 'ProductStyle.orderby asc'));
        $this->set('style_list', $style_list);
        //商品属性组
        $this->loadModel('ProductType');
        $product_type_tree = $this->ProductType->product_type_tree($this->backend_locale);
        $this->set('product_type_tree', $product_type_tree);
        foreach ($user_style_list as $k => $v) {
            foreach ($style_list as $sk => $sv) {
                if ($v['UserStyle']['style_id'] == $sv['ProductStyle']['id']) {
                    $user_style_list[$k]['UserStyle']['style_name'] = $sv['ProductStyleI18n']['style_name'];
                }
            }
        }
        foreach ($user_style_list as $uk => $uv) {
            foreach ($product_type_tree as $tk => $tv) {
                if ($uv['UserStyle']['type_id'] == $tv['ProductType']['id']) {
                    $user_style_list[$uk]['UserStyle']['attr_name'] = $tv['ProductTypeI18n']['name'];
                }
            }
        }
        $this->set('user_style_list', $user_style_list);

        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('user_config_type', 'verify_status', 'order_status', 'shipping_status', 'payment_status'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);

        //用户模板end
        if ($this->RequestHandler->isPost()) {
            $user_info = $this->User->findbyid($this->data['User']['id']);
            if (!empty($this->data['User']['new_password']) && !empty($this->data['User']['new_password2']) && $this->data['User']['new_password'] != '' && $this->data['User']['new_password2'] != '') {
                if (strcmp($this->data['User']['new_password'], $this->data['User']['new_password2']) != 0) {
                    $this->redirect('/users/'.$this->data['User']['id']);
                } else {
                    $this->data['User']['password'] = md5($this->data['User']['new_password']);
                }
            } else {
                $this->data['User']['password'] = $user_info['User']['password'];
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_member'].'id: '.$id.' '.$this->data['User']['name'], $this->admin['id']);
            }
            $this->User->save($this->data);
            /*会员来源*/
            if (!empty($_POST['user_type']) && is_string($_POST['user_type'])) {
                $type_arr = explode(':', $_POST['user_type']);
                $usertype_arr['User']['user_id'] = $id;
                $usertype_arr['User']['user_type'] = $type_arr[0];
                $usertype_arr['User']['user_type_id'] = $type_arr[1];
                $this->User->save($usertype_arr);
            }

            /*
                用户配置信息
            */
            if (isset($this->data['UserConfig'])) {
                foreach ($this->data['UserConfig'] as $kk => $vv) {
                    if (empty($vv)) {
                        continue;
                    }
                    $this->UserConfig->deleteAll(array('UserConfig.user_id' => $id, 'UserConfig.type' => $kk));
                    foreach ($vv as $uck => $ucv) {
                        $config_data['UserConfig']['user_id'] = $id;
                        $config_data['UserConfig']['user_rank'] = 0;
                        $config_data['UserConfig']['code'] = $uck;
                        $config_data['UserConfig']['type'] = $kk;
                        $config_data['UserConfig']['value'] = $ucv;
                        $this->UserConfig->saveAll($config_data);
                    }
                }
            }

            /* 资金 */
            if (!empty($_POST['balance']) && is_numeric($_POST['balance'])) {
                if ($_POST['balance_type']) {
                    $BalanceLog['UserBalanceLog']['user_id'] = $id;
                    $BalanceLog['UserBalanceLog']['amount'] = $_POST['balance'];
                    $BalanceLog['UserBalanceLog']['admin_user'] = $this->admin['name'];
                    $BalanceLog['UserBalanceLog']['admin_note'] = '';
                    $BalanceLog['UserBalanceLog']['system_note'] = $this->ld['system'].$this->ld['increase_the_user_funds'].':'.$_POST['balance'].$this->ld['app_yuan'].';余额:'.($this->data['User']['balance'] + $_POST['balance']).$this->ld['app_yuan'];
                    $BalanceLog['UserBalanceLog']['log_type'] = 'B';
                    $BalanceLog['UserBalanceLog']['type_id'] = 0;
                    $this->UserBalanceLog->save($BalanceLog);
                    $this->User->updateAll(array('User.balance' => 'User.balance + '.$_POST['balance']), array('User.id =' => "$id"));
                    //操作员日志
                    if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.sprintf($this->ld['log_member_available_funds_increase'], $this->data['User']['name']).':'.$_POST['balance'], $this->admin['id']);
                    }
                } else {
                    $BalanceLog['UserBalanceLog']['user_id'] = $id;
                    $BalanceLog['UserBalanceLog']['amount'] = '-'.$_POST['balance'];
                    $BalanceLog['UserBalanceLog']['admin_user'] = $this->admin['name'];
                    $BalanceLog['UserBalanceLog']['admin_note'] = '';
                    $BalanceLog['UserBalanceLog']['system_note'] = $this->ld['system'].$this->ld['reduce_the_user_balance'].':'.$_POST['balance'].$this->ld['app_yuan'].';余额:'.($this->data['User']['balance'] - $_POST['balance']).$this->ld['app_yuan'];
                    $BalanceLog['UserBalanceLog']['log_type'] = 'B';
                    $BalanceLog['UserBalanceLog']['type_id'] = 0;
                    $this->UserBalanceLog->save($BalanceLog);
                    $this->User->updateAll(array('User.balance' => 'User.balance - '.$_POST['balance']), array('User.id =' => "$id"));
                    //操作员日志
                    if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.sprintf($this->ld['log_member_available_funds_reduce'], $this->data['User']['name']).':'.$_POST['balance'], $this->admin['id']);
                    }
                }
            }
            /* 冻结资金 */
            if (!empty($_POST['frozen']) && is_numeric($_POST['frozen'])) {
                if ($_POST['frozen_type']) {
                    $this->User->updateAll(array('User.frozen' => 'User.frozen + '.$_POST['frozen']), array('User.id =' => "$id"));
                    //操作员日志
                    if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.sprintf($this->ld['log_member_freezing_funds_increase'], $this->data['User']['name']).':'.$_POST['frozen'], $this->admin['id']);
                    }
                } else {
                    $this->User->updateAll(array('User.frozen' => 'User.frozen - '.$_POST['frozen']), array('User.id =' => "$id"));
                    //操作员日志
                    if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.sprintf($this->ld['log_member_freezing_funds_reduce'], $this->data['User']['name']).':'.$_POST['frozen'], $this->admin['id']);
                    }
                }
            }
            /* 积分 */
            if (!empty($_POST['point']) && is_numeric($_POST['point'])) {
                if ($_POST['point_type']) {
                    $PointLog['UserPointLog']['user_id'] = $id;
                    $PointLog['UserPointLog']['point'] = $_POST['point'];
                    $PointLog['UserPointLog']['admin_user'] = $this->admin['name'];
                    $PointLog['UserPointLog']['admin_note'] = '';
                    $PointLog['UserPointLog']['system_note'] = $this->ld['operator'].':'.$this->admin['name'].$this->ld['add_the_user_points'];
                    $PointLog['UserPointLog']['log_type'] = 'A';
                    $PointLog['UserPointLog']['type_id'] = 0;
                    $this->UserPointLog->save($PointLog);
                    $this->User->updateAll(array('User.point' => 'User.point + '.$_POST['point']), array('User.id =' => "$id"));
                    //操作员日志
                    if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.sprintf($this->ld['log_member_consumer_points_increase'], $this->data['User']['name']).':'.$_POST['point'], $this->admin['id']);
                    }
                } else {
                    $PointLog['UserPointLog']['user_id'] = $id;
                    $PointLog['UserPointLog']['point'] = '-'.$_POST['point'];
                    $PointLog['UserPointLog']['admin_user'] = $this->admin['name'];
                    $PointLog['UserPointLog']['admin_note'] = '';
                    $PointLog['UserPointLog']['system_note'] = $this->ld['operator'].':'.$this->admin['name'].' '.$this->ld['deduct_the_user_points'];
                    $PointLog['UserPointLog']['log_type'] = 'A';
                    $PointLog['UserPointLog']['type_id'] = 0;
                    $this->UserPointLog->save($PointLog);
                    $this->User->updateAll(array('User.point' => 'User.point - '.$_POST['point']), array('User.id =' => "$id"));
                    //操作员日志
                    if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.sprintf($this->ld['log_member_consumer_points_reduce'], $this->data['User']['name']).':'.$_POST['point'], $this->admin['id']);
                    }
                }
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Order');
            $this->Order->belongsTo = array();
            //会员订单
            $orders_list = $this->Order->find('all', array('conditions' => array('user_id' => $id), 'order' => 'id desc'));
            $price_format = $this->configs['price_format'];
            foreach ($orders_list as $k => $v) {
                $orders_list[$k]['Order']['should_pay'] = $v['Order']['total'] - $v['Order']['money_paid'] - $v['Order']['point_fee'] - $v['Order']['discount'];
                $orders_list[$k]['Order']['subtotal'] = $v['Order']['subtotal'];
                $orders_list[$k]['Order']['total'] = $v['Order']['total'];
            }
            $this->set('orders_list', $orders_list);
        }

        //会员资金日志
        $balances_list = $this->UserBalanceLog->find('all', array('conditions' => array('user_id' => $id), 'order' => 'UserBalanceLog.created desc'));
        foreach ($balances_list as $k => $v) {
            if ($v['UserBalanceLog']['log_type'] == 'O') {
                $join_order = $this->Order->findbyid($v['UserBalanceLog']['type_id']);
                $balances_list[$k]['Order'] = $join_order['Order'];
            }
        }
        $this->set('balances_list', $balances_list);

        //会员等级
        $this->UserRank->set_locale($this->locale);
        $rank_list = $this->UserRank->find('all');
        $rank_data = array();
        foreach ($rank_list as $k => $v) {
            $rank_data[$v['UserRank']['id']] = $v['UserRankI18n']['name'];
        }

        //会员等级日志
        $user_rank_log = $this->UserRankLog->find('all', array('conditions' => array('UserRankLog.user_id' => $id), 'order' => 'UserRankLog.created desc'));
        if (!empty($user_rank_log) && sizeof($user_rank_log) > 0) {
            //查询操作员信息
            $operator_list = $this->Operator->find('list', array('fields' => array('Operator.id', 'Operator.name'), 'conditions' => array('Operator.status' => '1')));
            $this->set('operator_list', $operator_list);

            if (isset($user_info) && $user_info['User']['rank'] != '0') {
                $user_rank_max_end = $this->UserRankLog->find('first', array('conditions' => array('UserRankLog.user_id' => $id), 'order' => 'UserRankLog.end_date desc'));
                if (!empty($user_rank_max_end)) {
                    $this->set('userrank_open_time', $user_rank_max_end['UserRankLog']['start_date'] != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($user_rank_max_end['UserRankLog']['start_date'])) : '');
                    $this->set('userrank_end_time', $user_rank_max_end['UserRankLog']['end_date'] != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($user_rank_max_end['UserRankLog']['end_date'])) : '');
                }
            }
        }

        if (!empty($Resource_info['user_config_type'])) {
            $user_config_types = array();
            foreach ($Resource_info['user_config_type'] as $k => $v) {
                $user_config_types[] = $k;
            }
            $this->UserConfig->set_locale($this->backend_locale);
            $users_config_group_list=array();
            $default_user_config_list = array();
            $user_config_list = array();
            $user_config_info = $this->UserConfig->find('all', array('conditions' => array('UserConfig.user_id' => array(0, $id), 'UserConfig.type' => $user_config_types),'order'=>'UserConfig.created'));
            foreach ($user_config_info as $k => $v) {
                if(!empty($v['UserConfig']['group_code'])){
                    $users_config_group_list[$v['UserConfig']['group_code']]=$v['UserConfig']['group_code'];
                }
                if ($v['UserConfig']['user_id'] == 0) {
                    $default_user_config_list[$v['UserConfig']['type']][$v['UserConfig']['group_code']][$v['UserConfig']['code']]['name'] = $v['UserConfigI18n']['name'];
                    $default_user_config_list[$v['UserConfig']['type']][$v['UserConfig']['group_code']][$v['UserConfig']['code']]['value_type'] = $v['UserConfig']['value_type'];
                    $default_user_config_list[$v['UserConfig']['type']][$v['UserConfig']['group_code']][$v['UserConfig']['code']]['user_config_values'] = $v['UserConfigI18n']['user_config_values'];
                    $default_user_config_list[$v['UserConfig']['type']][$v['UserConfig']['group_code']][$v['UserConfig']['code']]['value'] = $v['UserConfig']['value'];
                } else {
                    $user_config_list[$v['UserConfig']['type']][$v['UserConfig']['code']] = $v['UserConfig']['value'];
                }
            }
            $this->set('default_user_config_list', $default_user_config_list);
            $this->set('user_config_list', $user_config_list);
            
            if(!empty($users_config_group_list)){
                $this->Resource->set_locale($this->backend_locale);
                $user_config_group_code=$this->Resource->find('all',array('fields'=>array('Resource.resource_value','ResourceI18n.name'),'conditions'=>array('Resource.resource_value'=>$users_config_group_list)));
                foreach($user_config_group_code as $v){
                    $user_config_group_list[$v['Resource']['resource_value']]=$v['ResourceI18n']['name'];
                }
                $this->set('user_config_group_list', $user_config_group_list);
            }
        }

        /*
        	同步绑定信息
        */
        $SynchroUser_list = $this->SynchroUser->find('all', array('conditions' => array('SynchroUser.user_id' => $id, 'SynchroUser.status' => 1)));
        $this->set('SynchroUser_list', $SynchroUser_list);
        $this->set('user_App_list', $this->UserApp_Type);

        /*
        	互动
        */
        $user_fan_list = $this->UserFan->getInfoById($id);
        $this->set('user_fan_list', $user_fan_list);

        $this->set('user_info', $user_info);
        $this->set('rank_list', $rank_list);
        $this->set('user_rank_data', $rank_data);
        $this->set('user_rank_log', $user_rank_log);
    }

    public function useraddress($user_id = 0, $type = 'list')
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            //仅限ajax访问
            Configure::write('debug', 1);
            $this->layout = 'ajax';
            $this->set('type', $type);
            if ($type == 'list') {
                $user_address = $this->UserAddress->find('all', array('conditions' => array('user_id' => $user_id)));
                if (!empty($user_address)) {
                    $this->loadModel('Region');
                    $region_ids = array();
                    foreach ($user_address as $v) {
                        $region_ids[$v['UserAddress']['country']] = $v['UserAddress']['country'];
                        $region_ids[$v['UserAddress']['province']] = $v['UserAddress']['province'];
                        $region_ids[$v['UserAddress']['city']] = $v['UserAddress']['city'];
                        $region_ids[$v['UserAddress']['district']] = $v['UserAddress']['district'];
                    }
                    $this->Region->set_locale($this->backend_locale);
                    $region_info = $this->Region->find('all', array('conditions' => array('Region.id' => $region_ids)));
                    $regions_list = array();
                    foreach ($region_info as $v) {
                        $regions_list[$v['Region']['id']] = $v['RegionI18n']['name'];
                    }
                    $this->set('regions_list', $regions_list);
                }
                $this->set('user_address', $user_address);
            } elseif ($type == 'edit') {
                $addr_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
                $user_addressInfo = $this->UserAddress->find('first', array('conditions' => array('UserAddress.id' => $addr_id)));
                $this->set('user_addressInfo', $user_addressInfo);
            } elseif ($type == 'save') {
                $result['code'] = 0;
                $user_addr_data = isset($_POST['data']['UserAddress']) ? $_POST['data']['UserAddress'] : array();
                if (!empty($user_addr_data)) {
                    $regions_data = split(' ', trim($user_addr_data['regions']));
                    $user_addr_data['country'] = isset($regions_data[0]) ? $regions_data[0] : 0;
                    $user_addr_data['province'] = isset($regions_data[1]) ? $regions_data[1] : 0;
                    $user_addr_data['city'] = isset($regions_data[2]) ? $regions_data[2] : 0;
                    $user_addr_data['district'] = isset($regions_data[3]) ? $regions_data[3] : 0;
                    if ($this->UserAddress->save($user_addr_data)) {
                        $result['code'] = 1;
                    }
                }
                die(json_encode($result));
            } elseif ($type == 'del') {
                $result['code'] = 0;
                $addr_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
                if ($this->UserAddress->delete(array('id' => $addr_id))) {
                    $result['code'] = 1;
                    $num = $this->User->find('count', array('conditions' => array('User.id' => $user_id, 'User.address_id' => $addr_id)));
                    if ($num > 0) {
                        $this->User->updateAll(array('User.address_id' => 0), array('User.id' => $user_id));
                    }
                }
                die(json_encode($result));
            }
        } else {
            $this->redirect('/users/index');
        }
    }

    /*
        ajax显示用户订单及订单商品列表
    */
    public function user_order_list($user_id = 0, $page = 1)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            //仅限ajax访问
            Configure::write('debug', 1);
            $this->layout = 'ajax';
            if (constant('Product') == 'AllInOne') {
                //查找当前用户的所有订单
                $this->loadModel('Product');
                $this->loadModel('Order');
                $this->loadModel('OrderProduct');
                $this->loadModel('Payment');
                $this->loadModel('PaymentI18n');
                $this->loadModel('ShippingI18n');
                $order_cond['Order.user_id'] = $user_id;
                $total = $this->Order->find('count', array('conditions' => $order_cond));
                $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
                if (isset($_GET['page']) && $_GET['page'] != '') {
                    $page = $_GET['page'];
                }
                $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
                $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 5);
                $parameters['get'] = array();
                //地址路由参数（和control,action的参数对应）
                $parameters['route'] = array('controller' => 'users','action' => 'user_order_list','page' => $page,'limit' => $rownum);
                $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Order');
                $this->Pagination->init($order_cond, $parameters, $options);
                $order_list = $this->Order->find('all', array('conditions' => $order_cond, 'page' => $page, 'limit' => $rownum, 'order' => 'Order.created desc'));
                if (!empty($order_list) && sizeof($order_list) > 0) {
                    $this->Payment->set_locale($this->backend_locale);
                    $payment_names = $this->PaymentI18n->find('list', array('fields' => array('PaymentI18n.payment_id', 'PaymentI18n.name'), 'conditions' => array('PaymentI18n.locale' => $this->locale)));
                    $payment_iscods = $this->Payment->find('list', array('fields' => array('Payment.id', 'Payment.is_cod'), 'conditions' => array('Payment.status' => 1)));
                    $shipping_names = $this->ShippingI18n->find('list', array('fields' => array('ShippingI18n.shipping_id', 'ShippingI18n.name'), 'conditions' => array('ShippingI18n.locale' => $this->locale)));

                    $order_ids = array();
                    foreach ($order_list as $k => $v) {
                        $order_ids[] = $v['Order']['id'];
                        $order_list[$k]['Order']['paymenttype'] = isset($payment_iscods[$v['Order']['payment_id']]) ? $payment_iscods[$v['Order']['payment_id']] : '';
                        $order_list[$k]['Order']['payment_name'] = isset($payment_names[$v['Order']['payment_id']]) ? $payment_names[$v['Order']['payment_id']] : '';
                        $order_list[$k]['Order']['shipping_name'] = isset($shipping_names[$v['Order']['shipping_id']]) ? $shipping_names[$v['Order']['shipping_id']] : '';
                    }
                    $order_product_list = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_ids), 'order' => 'OrderProduct.order_id,OrderProduct.created'));
                    $order_product_info = array();
                    $order_product_ids = array();//记录订单商品Id
                    foreach ($order_product_list as $v) {
                        $order_product_info[$v['OrderProduct']['order_id']][] = $v;
                        $order_product_ids[$v['OrderProduct']['product_id']] = $v['OrderProduct']['product_id'];
                    }
                    $pro_list = $this->Product->find('list', array('fields' => array('Product.id', 'Product.img_thumb'), 'conditions' => array('Product.id' => $order_product_ids)));

                    //资源库信息
                    $Resource_info = $this->Resource->getformatcode(array('order_status', 'shipping_status', 'payment_status'), $this->backend_locale);
                    $this->set('Resource_info', $Resource_info);
                    $this->set('order_list', $order_list);
                    $this->set('order_product_info', $order_product_info);
                    $this->set('pro_list', $pro_list);
                }
            }
        } else {
            $this->redirect('/users/index');
        }
    }

    /*
    	ajax获取用户评论
    */
    public function user_comm_list($user_id = 0, $page = 1)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            //仅限ajax访问
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $condition['Comment.user_id'] = $user_id;
            $condition['Comment.parent_id'] = 0;

            $total = $this->Comment->find('count', array('conditions' => $condition));
            $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
            if (isset($_GET['page']) && $_GET['page'] != '') {
                $page = $_GET['page'];
            }
            $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
            $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'users','action' => 'user_comm_list','page' => $page,'limit' => $rownum);
            $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Comment');
            $this->Pagination->init($condition, $parameters, $options);
            $_data = $this->Comment->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'created desc'));
            $data = array();
            if (!empty($_data)) {
                $comm_ids = array();
                $user_ids = array();
                $user_ids[$user_id] = $user_id;
                $comm_type_ids = array();
                foreach ($_data as $v) {
                    $comm_ids[] = $v['Comment']['id'];
                    $comm_type_ids[$v['Comment']['type']][$v['Comment']['type_id']] = $v['Comment']['type_id'];
                }
                $commdata = array();
                $comm_data = $this->Comment->find('all', array('conditions' => array('Comment.parent_id' => $comm_ids), 'order' => 'Comment.created asc'));
                if (isset($comm_type_ids['P'])) {
                    $ProductI18n = ClassRegistry::init('ProductI18n');
                    $pro_cond['ProductI18n.product_id'] = $comm_type_ids['P'];
                    $pro_cond['ProductI18n.locale'] = $this->backend_locale;
                    $pro_list = $ProductI18n->find('list', array('conditions' => $pro_cond, 'fields' => array('ProductI18n.product_id', 'ProductI18n.name')));
                }
                if (isset($comm_type_ids['A'])) {
                    $ArticleI18n = ClassRegistry::init('ArticleI18n');
                    $article_cond['ArticleI18n.article_id'] = $comm_type_ids['A'];
                    $article_cond['ArticleI18n.locale'] = $this->backend_locale;
                    $article_list = $ArticleI18n->find('list', array('conditions' => $article_cond, 'fields' => array('ArticleI18n.article_id', 'ArticleI18n.title')));
                }
                foreach ($comm_data as $k => $v) {
                    $user_ids[$v['Comment']['user_id']] = $v['Comment']['user_id'];
                }
                $user_list = $this->User->find('list', array('conditions' => array('User.id' => $user_ids), 'fields' => array('User.id', 'User.name')));
                foreach ($comm_data as $k => $v) {
                    $v['Comment']['user'] = isset($user_list[$v['Comment']['user_id']]) ? $user_list[$v['Comment']['user_id']] : '';
                    $commdata[$v['Comment']['parent_id']][] = $v;
                }
                foreach ($_data as $k => $v) {
                    if ($v['Comment']['type'] == 'P') {
                        $v['Comment']['object'] = isset($pro_list[$v['Comment']['type_id']]) ? $pro_list[$v['Comment']['type_id']] : '';
                    } elseif ($v['Comment']['type'] == 'A') {
                        $v['Comment']['object'] = isset($article_list[$v['Comment']['type_id']]) ? $article_list[$v['Comment']['type_id']] : '';
                    } else {
                        $v['Comment']['object'] = '';
                    }
                    $v['Comment']['user'] = isset($user_list[$v['Comment']['user_id']]) ? $user_list[$v['Comment']['user_id']] : $this->ld['user_not_exist'];
                    $v['CommentList'] = isset($commdata[$v['Comment']['id']]) ? $commdata[$v['Comment']['id']] : array();
                    $data[$k] = $v;
                }

                /*
                    评分记录
                */
                $score_log_list = $this->ScoreLog->getInfoById($user_id, $this->backend_locale);
                foreach ($data as $ck => $cv) {
                    foreach ($score_log_list as $sk => $sv) {
                        if ($sk == ($cv['Comment']['type'].'-'.$cv['Comment']['type_id'].'-'.$cv['Comment']['user_id'])) {
                            $data[$ck]['score_log_list'] = $sv;
                        }
                    }
                }
            }
            $this->set('comm_list', $data);
        } else {
            $this->redirect('/users/index');
        }
    }

    /*
    	ajax获取日志
    */
    public function user_blog_list($user_id = 0, $page = 1)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            //仅限ajax访问
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $condition['Blog.user_id'] = $user_id;
            $condition['Blog.parent_id'] = 0;

            $total = $this->Blog->find('count', array('conditions' => $condition));
            $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
            if (isset($_GET['page']) && $_GET['page'] != '') {
                $page = $_GET['page'];
            }
            $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
            $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'users','action' => 'user_chat_list','page' => $page,'limit' => $rownum);
            $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Blog');
            $this->Pagination->init($condition, $parameters, $options);
            $_data = $this->Blog->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'created desc'));
            $data = array();
            if (!empty($_data)) {
                $blog_ids = array();
                $user_ids = array();
                $user_ids[$id] = $user_id;
                foreach ($_data as $v) {
                    $blog_ids[] = $v['Blog']['id'];
                }
                $commdata = array();
                $comm_data = $this->Blog->find('all', array('conditions' => array('Blog.parent_id' => $blog_ids), 'order' => 'Blog.created asc'));
                foreach ($comm_data as $k => $v) {
                    $user_ids[$v['Blog']['user_id']] = $v['Blog']['user_id'];
                }
                $user_list = $this->User->find('list', array('conditions' => array('User.id' => $user_ids), 'fields' => array('User.id', 'User.name')));
                foreach ($comm_data as $k => $v) {
                    $v['Blog']['user'] = isset($user_list[$v['Blog']['user_id']]) ? $user_list[$v['Blog']['user_id']] : $this->ld['user_not_exist'];
                    $commdata[$v['Blog']['parent_id']][] = $v;
                }
                foreach ($_data as $k => $v) {
                    $v['Blog']['user'] = isset($user_list[$v['Blog']['user_id']]) ? $user_list[$v['Blog']['user_id']] : $this->ld['user_not_exist'];
                    $v['CommentList'] = isset($commdata[$v['Blog']['id']]) ? $commdata[$v['Blog']['id']] : array();
                    $data[$k] = $v;
                }
            }
            $this->set('blog_list', $data);
        } else {
            $this->redirect('/users/index');
        }
    }

    /*
    	ajax获取私信
    */
    public function user_chat_list($user_id = 0, $page = 1)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            //仅限ajax访问
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $condition['or']['UserChat.user_id'] = $user_id;
            $condition['or']['UserChat.to_user_id'] = $user_id;

            $total = $this->UserChat->find('count', array('conditions' => $condition));
            $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
            if (isset($_GET['page']) && $_GET['page'] != '') {
                $page = $_GET['page'];
            }
            $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
            $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'users','action' => 'user_chat_list','page' => $page,'limit' => $rownum);
            $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserChat');
            $this->Pagination->init($condition, $parameters, $options);
            $_data = $this->UserChat->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'created desc'));
            $data = array();
            if (!empty($_data)) {
                $user_ids = array();
                foreach ($_data as $k => $v) {
                    $user_ids[$v['UserChat']['user_id']] = $v['UserChat']['user_id'];
                    $user_ids[$v['UserChat']['to_user_id']] = $v['UserChat']['to_user_id'];
                }
                $user_list = $this->User->find('list', array('conditions' => array('User.id' => $user_ids), 'fields' => array('User.id', 'User.name')));
                foreach ($_data as $k => $v) {
                    $v['UserChat']['user'] = isset($user_list[$v['UserChat']['user_id']]) ? $user_list[$v['UserChat']['user_id']] : $this->ld['user_not_exist'];
                    $v['UserChat']['to_user'] = isset($user_list[$v['UserChat']['to_user_id']]) ? $user_list[$v['UserChat']['to_user_id']] : $this->ld['user_not_exist'];
                    $data[$k] = $v;
                }
            }
            $this->set('user_chat_list', $data);
        } else {
            $this->redirect('/users/index');
        }
    }

    /*
    	ajax获取用户收藏
    */
    public function user_likes_list($user_id = 0, $page = 1)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            //仅限ajax访问
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $condition['UserLike.user_id'] = $user_id;

            $total = $this->UserLike->find('count', array('conditions' => $condition));
            $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
            if (isset($_GET['page']) && $_GET['page'] != '') {
                $page = $_GET['page'];
            }
            $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
            $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'users','action' => 'user_likes_list','page' => $page,'limit' => $rownum);
            $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserLike');
            $this->Pagination->init($condition, $parameters, $options);
            $_data = $this->UserLike->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'created desc'));

            $data = array();
            if (!empty($_data)) {
                $pro_ids = array();
                foreach ($_data as $v) {
                    if ($v['UserLike']['type'] == 'P') {
                        $pro_ids[] = $v['UserLike']['type_id'];
                    }
                }
                $ProductI18n = ClassRegistry::init('ProductI18n');
                $pro_cond['ProductI18n.product_id'] = $pro_ids;
                $pro_cond['ProductI18n.locale'] = $this->backend_locale;
                $pro_list = $ProductI18n->find('list', array('conditions' => $pro_cond, 'fields' => array('ProductI18n.product_id', 'ProductI18n.name')));
                foreach ($_data as $k => $v) {
                    if ($v['UserLike']['type'] == 'P') {
                        $v['UserLike']['object'] = isset($pro_list[$v['UserLike']['type_id']]) ? $pro_list[$v['UserLike']['type_id']] : $v['UserLike']['type_id'];
                    } else {
                        $v['UserLike']['object'] = $v['UserLike']['type_id'];
                    }
                    $data[$k] = $v;
                }
            }
            $this->set('user_likes_list', $data);
        } else {
            $this->redirect('/users/index');
        }
    }

    /*
    	ajax获取用户留言
    */
    public function user_message_list($user_id = 0, $page = 1)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            //仅限ajax访问
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $condition['UserMessage.from_id'] = $user_id;
            $condition['UserMessage.to_id'] = 0;
            $condition['UserMessage.parent_id'] = 0;

            $total = $this->UserMessage->find('count', array('conditions' => $condition));
            $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
            if (isset($_GET['page']) && $_GET['page'] != '') {
                $page = $_GET['page'];
            }
            $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
            $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'users','action' => 'user_message_list','page' => $page,'limit' => $rownum);
            $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserMessage');
            $this->Pagination->init($condition, $parameters, $options);
            $_data = $this->UserMessage->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'created desc'));
            $data = array();
            if (!empty($_data)) {
                $user_message_ids = array();
                $user_ids = array();
                $user_ids[$user_id] = $user_id;
                $user_message_type_ids = array();
                foreach ($_data as $v) {
                    $user_message_ids[] = $v['UserMessage']['id'];
                    $user_message_type_ids[$v['UserMessage']['type']][$v['UserMessage']['value_id']] = $v['UserMessage']['value_id'];
                }
                if (isset($user_message_type_ids['P'])) {
                    $ProductI18n = ClassRegistry::init('ProductI18n');
                    $pro_cond['ProductI18n.product_id'] = $user_message_type_ids['P'];
                    $pro_cond['ProductI18n.locale'] = $this->backend_locale;
                    $pro_list = $ProductI18n->find('list', array('conditions' => $pro_cond, 'fields' => array('ProductI18n.product_id', 'ProductI18n.name')));
                }
                if (isset($user_message_type_ids['A'])) {
                    $ArticleI18n = ClassRegistry::init('ArticleI18n');
                    $article_cond['ArticleI18n.article_id'] = $user_message_type_ids['A'];
                    $article_cond['ArticleI18n.locale'] = $this->backend_locale;
                    $article_list = $ArticleI18n->find('list', array('conditions' => $article_cond, 'fields' => array('ArticleI18n.article_id', 'ArticleI18n.title')));
                }
                $commdata = array();
                $comm_data = $this->UserMessage->find('all', array('conditions' => array('UserMessage.parent_id' => $user_message_ids), 'order' => 'UserMessage.created desc'));
                foreach ($comm_data as $k => $v) {
                    $user_ids[$v['UserMessage']['user_id']] = $v['UserMessage']['user_id'];
                }
                $User = ClassRegistry::init('User');
                $user_list = $User->find('list', array('conditions' => array('User.id' => $user_ids), 'fields' => array('User.id', 'User.name')));
                foreach ($comm_data as $k => $v) {
                    $v['UserMessage']['user_name'] = isset($user_list[$v['UserMessage']['user_id']]) ? $user_list[$v['UserMessage']['user_id']] : '';
                    $commdata[$v['UserMessage']['parent_id']][] = $v;
                }
                foreach ($_data as $k => $v) {
                    if ($v['UserMessage']['type'] == 'P') {
                        $v['UserMessage']['object'] = isset($pro_list[$v['UserMessage']['value_id']]) ? $pro_list[$v['UserMessage']['value_id']] : '';
                    } elseif ($v['UserMessage']['type'] == 'A') {
                        $v['UserMessage']['object'] = isset($article_list[$v['UserMessage']['value_id']]) ? $article_list[$v['UserMessage']['value_id']] : '';
                    } else {
                        $v['UserMessage']['object'] = '';
                    }
                    $v['UserMessage']['user_name'] = isset($user_list[$v['UserMessage']['user_id']]) ? $user_list[$v['UserMessage']['user_id']] : '';
                    $v['CommentList'] = isset($commdata[$v['UserMessage']['id']]) ? $commdata[$v['UserMessage']['id']] : array();
                    $data[$k] = $v;
                }
            }
            $this->set('user_message_list', $data);
        } else {
            $this->redirect('/users/index');
        }
    }

    public function user_point_list($user_id = 0, $page = 1)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            //仅限ajax访问
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $this->UserPointLog->belongsTo = array();

            $condition['UserPointLog.user_id'] = $user_id;
            $total = $this->UserPointLog->find('count', array('conditions' => $condition));
            $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
            if (isset($_GET['page']) && $_GET['page'] != '') {
                $page = $_GET['page'];
            }
            $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
            $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'users','action' => 'user_point_list','page' => $page,'limit' => $rownum);
            $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserPointLog');
            $this->Pagination->init($condition, $parameters, $options);
            $data = $this->UserPointLog->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'UserPointLog.created desc'));
            $this->set('user_point_list', $data);
            $point_log_type = array();
            $Resource_info = $this->Resource->getformatcode(array('point_log_type'), $this->backend_locale);
            if (!empty($Resource_info['point_log_type'])) {
                $point_log_type = $Resource_info['point_log_type'];
            }
            $this->set('point_log_type', $point_log_type);
        } else {
            $this->redirect('/users/index');
        }
    }

    public function user_coupon_list($user_id = 0, $page = 1)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            //仅限ajax访问
            Configure::write('debug', 1);
            $this->layout = 'ajax';

            $this->loadModel('Coupon');
            $this->loadModel('CouponType');

            $this->set('user_id', $user_id);
            $coupon_fields = array('Coupon.*','CouponTypeI18n.name','CouponType.type','CouponType.send_type','CouponType.money','CouponType.min_amount','CouponType.use_end_date','CouponType.use_start_date','CouponType.created');
            $coupon_joins = array(
                array('table' => 'svoms_coupon_types',
                      'alias' => 'CouponType',
                      'type' => 'inner',
                      'conditions' => array('CouponType.id = Coupon.coupon_type_id'),
                     ),
                array('table' => 'svoms_coupon_type_i18ns',
                      'alias' => 'CouponTypeI18n',
                      'type' => 'inner',
                      'conditions' => array('CouponType.id = CouponTypeI18n.coupon_type_id and CouponTypeI18n.locale="'.$this->locale.'"'),
                     ), );
            $coupon_list = $this->Coupon->find('all', array('conditions' => array('Coupon.user_id' => $user_id), 'fields' => $coupon_fields, 'joins' => $coupon_joins));
            $this->set('coupon_list', $coupon_list);
            $coupontype = array();
            $Resource_info = $this->Resource->getformatcode(array('coupontype'), $this->backend_locale);
            if (!empty($Resource_info['coupontype'])) {
                $coupontype = $Resource_info['coupontype'];
            }
            $this->set('coupontype', $coupontype);
        } else {
            $this->redirect('/users/index');
        }
    }

    /**
     *添加会员页.
     */
    public function add()
    {
        $this->operator_privilege('users_add');
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->set('title_for_layout', $this->ld['edit_member'].'-'.$this->ld['users_search'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
        $this->navigations[] = array('name' => $this->ld['add_user'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            if (!isset($this->data['User']['user_sn']) || $this->data['User']['user_sn'] == '') {
                $this->data['User']['user_sn'] = $this->data['User']['email'] != '' ? $this->data['User']['email'] : $this->data['User']['name'];
            }
            $this->data['User']['password'] = md5($this->data['User']['new_password']);
            $this->data['User']['name'] = $this->data['User']['name'];
            $this->data['User']['mobile'] = $this->data['User']['mobile'];
            $this->data['User']['admin_note'] = $this->data['User']['admin_note'];
            $this->data['User']['admin_note2'] = $this->data['User']['admin_note2'];
            $this->data['User']['email'] = $this->data['User']['email'];
            $this->data['User']['sex'] = $this->data['User']['sex'];
            $this->User->saveAll($this->data);
            if (!empty($this->params['form']['info_value']) && is_array($this->params['form']['info_value'])) {
                foreach ($this->params['form']['info_value']as $k => $v) {
                    if (isset($this->params['form']['info_value_id'][$k]) && is_array($this->params['form']['info_value_id'][$k])) {
                        $this->params['form']['info_value_id'][$k] = implode(';', $this->params['form']['info_value_id'][$k]);
                    }
                    $info_value = array('id' => '','user_id' => $this->User->getLastInsertId(),'user_info_id' => $this->params['form']['info_value'][$k],'value' => !empty($this->params['form']['info_value_id'][$k]) ? $this->params['form']['info_value_id'][$k] : '',
                    );
                    $this->UserInfoValue->save(array('UserInfoValue' => $info_value));
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_user'].$this->data['User']['name'], $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
    }

    /**
     * 删除会员.
     *
     *@param int $id 输入会员ID
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if (!$this->operator_privilege('users_remove', false)) {
            if ($this->RequestHandler->isPost()) {
                die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
            } else {
                $this->redirect('/users/');
            }
        }
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $user_info = $this->User->findById($id);
        $this->User->deleteAll(array('id' => $id));
        $this->UserAddress->deleteAll(array('UserAddress.user_id' => $id));//删除用户地址
        $this->UserLike->deleteAll(array('UserLike.user_id' => $id));//删除用户行为
        $condition['or']['UserFan.user_id'] = $id;
        $condition['or']['UserFan.fan_id'] = $id;
        $this->UserFan->deleteAll($condition);//删除用户粉丝
        $this->UserMessage->deleteAll(array('UserMessage.user_id' => $id));//删除用户留言
        $this->UserVisitors->deleteAll(array('UserVisitors.user_id' => $id));//删除用户访问
        $this->Blog->deleteAll(array('Blog.user_id' => $id));//删除用户日志
        $this->Comment->deleteAll(array('Comment.user_id' => $id));//删除用户评论
        $this->UserAction->deleteAll(array('UserAction.user_id' => $id));//删除用户动作
        $this->SynchroUser->deleteAll(array('SynchroUser.user_id' => $id));//删除用户授权
        $this->UserPointLog->deleteAll(array('UserPointLog.user_id' => $id));//删除会员积分日志

        $user_img_root = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
        if ($user_info['User']['img01'] != '') {
            $img_file1 = $user_img_root.$user_info['User']['img01'];
            if (file_exists($img_file1)) {
                unlink($img_file1);
            }
        }
        if ($user_info['User']['img02'] != '') {
            $img_file2 = $user_img_root.$user_info['User']['img02'];
            if (file_exists($img_file2)) {
                unlink($img_file2);
            }
        }
        if ($user_info['User']['img03'] != '') {
            $img_file3 = $user_img_root.$user_info['User']['img03'];
            if (file_exists($img_file3)) {
                unlink($img_file3);
            }
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id.' '.$user_info['User']['name'], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/users/');
        }
    }

    /**
     * 批量处理.
     *
     *@param int $id 输入会员ID
     */
    public function batch_operations()
    {
        $this->User->hasOne = array();
        $user_checkboxes = isset($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : array();
        $user_ids = array();//记录用户id
        foreach ($user_checkboxes as $k => $v) {
            $user_ids[] = $v;
        }
        if (sizeof($user_ids) > 0) {
            $user_Img = array();//记录用户头像地址
            $userInfo_list = $this->User->find('all', array('fields' => array('User.id', 'User.img01', 'User.img02', 'User.img03'), 'conditions' => array('User.id' => $user_ids)));
            foreach ($userInfo_list as $k => $v) {
                $user_Img[$v['User']['id']]['img01'] = $v['User']['img01'];
                $user_Img[$v['User']['id']]['img02'] = $v['User']['img02'];
                $user_Img[$v['User']['id']]['img03'] = $v['User']['img03'];
            }
            $user_img_root = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
            foreach ($user_ids as $k => $v) {
                $this->User->deleteAll(array('id' => $v));
                $this->UserAddress->deleteAll(array('UserAddress.user_id' => $v));//删除用户地址
                $this->UserLike->deleteAll(array('UserLike.user_id' => $v));//删除用户行为
                $condition['or']['UserFan.user_id'] = $v;
                $condition['or']['UserFan.fan_id'] = $v;
                $this->UserFan->deleteAll($condition);//删除用户粉丝
                $this->UserMessage->deleteAll(array('UserMessage.user_id' => $v));//删除用户留言
                $this->UserVisitors->deleteAll(array('UserVisitors.user_id' => $v));//删除用户访问
                $this->Blog->deleteAll(array('Blog.user_id' => $v));//删除用户日志
                $this->Comment->deleteAll(array('Comment.user_id' => $v));//删除用户评论
                $this->UserAction->deleteAll(array('UserAction.user_id' => $v));//删除用户动作
                $this->SynchroUser->deleteAll(array('SynchroUser.user_id' => $v));//删除用户授权
                $this->UserPointLog->deleteAll(array('UserPointLog.user_id' => $v));//删除会员积分日志

                if ($user_Img[$v]['img01'] != '') {
                    $img_file1 = $user_img_root.$user_Img[$v]['img01'];
                    if (file_exists($img_file1)) {
                        unlink($img_file1);
                    }
                }
                if ($user_Img[$v]['img02'] != '') {
                    $img_file2 = $user_img_root.$user_Img[$v]['img02'];
                    if (file_exists($img_file2)) {
                        unlink($img_file2);
                    }
                }
                if ($user_Img[$v]['img03'] != '') {
                    $img_file3 = $user_img_root.$user_Img[$v]['img03'];
                    if (file_exists($img_file3)) {
                        unlink($img_file3);
                    }
                }
            }

            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
            }
        }

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }

    /**
     * 新增订单时用。。搜索用户  old chenfan.
     */
    public function order_search_user_information_old($page = 1)
    {
        Configure::write('debug', 1);
        $condition = '';
        $condition['User.status'] = '1';
        //pr($_REQUEST['keyword']);
        $this->set('user_mobile', $this->params['url']['user_mobile']);
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
            $condition['or']['User.email like'] = '%'.$_REQUEST['keyword'].'%';
            $condition['or']['User.mobile like'] = '%'.$_REQUEST['keyword'].'%';
            $condition['or']['User.name like'] = '%'.$_REQUEST['keyword'].'%';
            $this->set('user_keyword', $_REQUEST['keyword']);
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'搜索用户'.'.'.$_REQUEST['keyword'], $this->admin['id']);
        }
        $total = $this->User->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'User';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'users','action' => 'order_search_user_information','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'User');
        $this->Pagination->init($condition, $parameters, $options);
        $user_list = $this->User->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum));
        $uaInfos = $this->UserAddress->find('all');
        $mobiles = '';
        if (!empty($uaInfos)) {
            foreach ($uaInfos as $v) {
                $mobiles[$v['UserAddress']['user_id']] = $v['UserAddress']['mobile'];
            }
        }
        $this->set('mobiles', $mobiles);
        $this->set('user_list', $user_list);
        $this->layout = 'window';
    }

    /**
     * 新增订单时用。。搜索用户  new  chenfan.
     */
    public function order_search_user_information($page = 1)
    {
        $condition = '';
        $condition['User.status'] = '1';
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $keywords = trim($_REQUEST['keywords']);
            $condition['or']['User.email like'] = '%'.$keywords.'%';
            $condition['or']['User.mobile like'] = '%'.$keywords.'%';
            $condition['or']['User.name like'] = '%'.$keywords.'%';
            $condition['or']['User.first_name like'] = '%'.$keywords.'%';
        }
        $user_list = $this->User->find('all', array('conditions' => $condition, 'fields' => 'User.id,User.user_sn,User.name,User.address_id,User.mobile,User.email,User.first_name'));
        foreach ($user_list as $k => $v) {
            $user_list[$k]['User']['consignee'] = '';
            if ($v['User']['address_id'] != 0 && $v['User']['address_id'] != '') {
                $addressInfo = $this->UserAddress->find('first', array('conditions' => array('UserAddress.id' => $v['User']['address_id']), 'fields' => 'UserAddress.consignee'));
                if (!empty($addressInfo) && $addressInfo['UserAddress']['consignee'] != '' && $addressInfo['UserAddress']['consignee'] != $v['User']['first_name']) {
                    $user_list[$k]['User']['consignee'] = $addressInfo['UserAddress']['consignee'];
                }
            }
        }
        Configure::write('debug', 0);
        $result['type'] = '0';
        $result['message'] = $user_list;
        die(json_encode($result));
    }

    public function balance_index($page = 1)
    {
        $this->operator_privilege('balance_log_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/users/balance_index/');
        $this->set('title_for_layout', $this->ld['money_diary'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['money_diary'],'url' => '/users/balance_index');
        $condition = '';
        if (isset($this->params['url']['user_email']) && $this->params['url']['user_email'] != '') {
            $condition['User.email like'] = '%'.$this->params['url']['user_email'].'%';
            $this->set('user_email', $this->params['url']['user_email']);
        }
        if (isset($this->params['url']['user_name']) && $this->params['url']['user_name'] != '') {
            $condition['User.name like'] = '%'.$this->params['url']['user_name'].'%';
            $this->set('user_name', $this->params['url']['user_name']);
        }
        if (isset($this->params['url']['min_balance']) && $this->params['url']['min_balance'] != '') {
            $condition['User.balance >='] = $this->params['url']['min_balance'];
            $this->set('min_balance', $this->params['url']['min_balance']);
        }
        if (isset($this->params['url']['max_balance']) && $this->params['url']['max_balance'] != '') {
            $condition['User.balance <='] = $this->params['url']['max_balance'];
            $this->set('max_balance', $this->params['url']['max_balance']);
        }
        if (isset($this->params['url']['start_date']) && $this->params['url']['start_date'] != '') {
            $condition['User.created  >='] = $this->params['url']['start_date'];
            $this->set('start_date', $this->params['url']['start_date']);
        }
        if (isset($this->params['url']['end_date']) && $this->params['url']['end_date'] != '') {
            $condition['User.created  <='] = $this->params['url']['end_date'].' 23:59:59';
            $this->set('end_date', $this->params['url']['end_date']);
        }
        $total = $this->UserBalanceLog->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'UserBalanceLog';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'users','action' => 'balance_index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserBalanceLog');
        $this->Pagination->init($condition, $parameters, $options);
        $start_date = isset($this->params['url']['start_date']) ? $this->params['url']['start_date'] : '';
        $end_date = isset($this->params['url']['end_date']) ? $this->params['url']['end_date'] : '';
        $min_balance = isset($this->params['url']['min_balance']) ? $this->params['url']['min_balance'] : '';
        $max_balance = isset($this->params['url']['max_balance']) ? $this->params['url']['max_balance'] : '';
        $log_list = $this->UserBalanceLog->find('all', array('conditions' => $condition, 'order' => 'UserBalanceLog.created desc'));
        $Resource_info = $this->Resource->getformatcode(array('verify_status'), $this->locale);
        $this->set('Resource_info', $Resource_info);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('min_balance', $min_balance);
        $this->set('max_balance', $max_balance);
        $this->set('log_list', $log_list);
    }

    /**
     * 批量处理.
     *
     *@param int $id 输入日志ID
     */
    public function batch_logs()
    {
        $this->UserBalanceLog->belongsTo = array();
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->UserBalanceLog->deleteAll(array('UserBalanceLog.id' => $v), false);
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        die(json_encode($result));
    }

    /**
     *查找用户订单.
     */
    public function user_order($id)
    {
        $user_order_infos = array();
        if (constant('Product') == 'AllInOne') {
            //当前订单信息（该用户最新3个订单）
            $this->loadModel('Order');
            //获取用户订单信息
            $user_order_infos = $this->Order->find('all',array('conditions'=>array('Order.user_id'=>$id,'Order.status <>'=>0),'order'=>'Order.created desc'));
            foreach ($user_order_infos as $k => $v) {
                $user_order_infos[$k]['Order']['should_pay'] = $v['Order']['total'] - $v['Order']['money_paid'] - $v['Order']['point_fee'] - $v['Order']['discount'];
            }
        }
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('verify_status', 'order_status', 'shipping_status', 'payment_status'), $this->locale);
        $this->set('Resource_info', $Resource_info);
        $this->set('user_order_infos', $user_order_infos);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
    }

    //用户的导出;;
    public function export_act($code)
    {
        $checkboxes = isset($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $condition = '';
        $condition['User.id'] = $checkboxes;
        $this->search_result($condition, $code, $code);
    }

    //订单的搜索导出;;
     public function search_result($condition = '', $actout_type = '', $code = '')
     {
         $this->loadModel('Profile');
         $this->loadModel('ProfileFiled');
         $this->loadModel('ProfilesFieldI18n');
         $this->Profile->hasOne = array();
         $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $code, 'Profile.status' => 1)));
         $newdata = array();
         if (isset($profile_id) && !empty($profile_id)) {
             $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1, 'ProfilesFieldI18n.locale' => $this->backend_locale), 'order' => 'ProfileFiled.orderby asc'));
             $tmp = array();
             $fields_array = array();
             foreach ($profilefiled_info as $k => $v) {
                 $tmp[] = $v['ProfilesFieldI18n']['description'];
                 $fields_array[] = $v['ProfileFiled']['code'];
             }
             $Resource_info = array();
             if (in_array('User.status', $fields_array)) {
                 $Resource_info = $this->Resource->getformatcode(array('status'), $this->backend_locale);
             }
             if (in_array('APP-DEALER', $this->apps['codes'])) {
                 $this->loadModel('Dealer');
                 $dealers_list = $this->Dealer->find('list', array('fields' => array('id', 'name'), 'order' => 'orderby'));
             }
//			$this->UserAddress->hasOne = array();
//	 	 	 $this->User->hasOne = array('UserAddress'=>array(
//				'className'    => 'UserAddress',
// 				'conditions'   => 'UserAddress.user_id=User.id',
//				'order'        => '',
//			 	'foreignKey'   => 'user_id'
//			 ));
             //分页
            $cond['joins'] = array(
                        array('table' => 'svoms_user_addresses',
                              'alias' => 'UserAddress',
                              'type' => 'left',
                              'conditions' => array('User.id = UserAddress.user_id'),
                             ), );
             $cond['conditions'] = $condition;
             $cond['order'] = 'User.id desc';
             $cond['fields'] = $fields_array;
            //$orders_list=$this->User->find("all",array('conditions'=>$condition,'fields'=>$fields_array,"order"=>"User.id asc"));
             $orders_list = $this->User->find('all', $cond);
             $newdata[] = $tmp;
             $order_code_array = array();
             foreach ($orders_list as $k => $v) {
                 $order_products_flag = 0;
                 $datas = array();
//	         	if(!in_array($v['Order']['order_code'],$order_code_array)){
//					$order_code_array[]=$v['Order']['order_code'];
//				}else{
//					$order_products_flag=1;
//				}
                 foreach ($fields_array as $kk => $vv) {
                     $fields_kk = explode('.', $vv);
                     if (isset($order_products_flag) && $order_products_flag == 1 && stristr($vv, 'Order.')) {
                         //在里面
                        $datas[] = '';
                     } else {
                         if ($vv == 'Order.type') {
                             if ($v['Order']['type'] == 'fenxiao') {
                                 $datas[] = '分销';
                             } elseif ($v['Order']['type'] == 'taobao') {
                                 $datas[] = '淘宝';
                             } elseif ($v['Order']['type'] == 'dealer') {
                                 $datas[] = '经销商';
                             } else {
                                 $datas[] = '本站';
                             }
                         } elseif ($vv == 'Order.type_id') {
                             if (isset($v['Order']['type']) && $v['Order']['type'] == 'dealer') {
                                 $dealer_name = isset($dealers_list[$v['Order']['type_id']]) ? $dealers_list[$v['Order']['type_id']] : '';
                                 $datas[] = $dealer_name;
                             } else {
                                 $datas[] = $v['Order']['type_id'];
                             }
                         } elseif ($vv == 'Order.status') {
                             if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                                 $datas[] = isset($Resource_info['order_status'][$v['Order']['status']]) ? $Resource_info['order_status'][$v['Order']['status']] : '';
                             } else {
                                 $datas[] = '';
                             }
                         } elseif ($vv == 'Order.payment_status') {
                             if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                                 $datas[] = isset($Resource_info['payment_status'][$v['Order']['payment_status']]) ? $Resource_info['payment_status'][$v['Order']['payment_status']] : '';
                             } else {
                                 $datas[] = '';
                             }
                         } elseif ($vv == 'Order.shipping_status') {
                             if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                                 $datas[] = isset($Resource_info['shipping_status'][$v['Order']['shipping_status']]) ? $Resource_info['shipping_status'][$v['Order']['shipping_status']] : '';
                             } else {
                                 $datas[] = '';
                             }
                         } else {
                             $datas[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                         }
                     }
                 }
                 $newdata[] = $datas;
             }
         }
         $this->Phpexcel->output($actout_type.date('YmdHis').'.xls', $newdata);
         exit;
     }

    public function user_type()
    {
        $user_type = array();
        $user_type_arr = array('ioco' => $this->ld['order_site']);
        if (!in_array('APP-PRODUCTS', $this->apps['codes'])) {
            $user_type['ioco'] = array('网站' => $this->ld['website']);
        } else {
            $user_type['ioco'] = array('网站' => $this->ld['website'],'批发' => $this->ld['order_wholesale']);
        }
        if (constant('Product') == 'AllInOne') {
            $user_type_arr['taobao'] = '淘宝';
            $this->loadModel('TaobaoShop');
            $user_type_arr2 = $this->TaobaoShop->find('all', array('conditions' => array('status' => 1), 'order' => 'orderby'));
            if (!empty($user_type_arr2)) {
                foreach ($user_type_arr2 as $k => $v) {
                    $user_type['taobao'][$v['TaobaoShop']['nick']] = $v['TaobaoShop']['nick'];
                }
            }
            if (in_array('APP-JINGDONG', $this->apps['codes'])) {
                //$order_type_arr['jingdong']='京东';
                //$order_type['jingdong']=array('艾婷家居京东店'=>'艾婷家居京东店');
                $user_type_arr['jingdong'] = '京东';
                $this->loadModel('JingdongShop');
                $user_type_arr2 = $this->JingdongShop->find('all', array('conditions' => array('status' => 1), 'order' => 'orderby'));
                if (!empty($user_type_arr2)) {
                    foreach ($user_type_arr2 as $k => $v) {
                        $user_type['jingdong'][$v['JingdongShop']['vender_id']] = $v['JingdongShop']['nick'];
                    }
                }
            }
        //TODO 判断门店应用

            $user_type_arr['store'] = $this->ld['order_store'];//门店
            $this->loadModel('Store');
            $this->Store->set_locale($this->backend_locale);
            $stores = $this->Store->find('all', array('conditions' => array('status' => 1), 'fields' => array('store_sn', 'StoreI18n.name'), 'order' => 'orderby'));
            if (!empty($stores)) {
                foreach ($stores as $kk => $vv) {
                    $user_type['store'][$vv['Store']['store_sn']] = $vv['StoreI18n']['name'];
                    $user_type_arr[$vv['Store']['store_sn']] = $vv['StoreI18n']['name'];
                }
            }

        //分销

            $user_type_arr['fenxiao'] = '分销';
            $this->loadModel('TaobaoShop');
            $user_type_arr2 = $this->TaobaoShop->find('all', array('conditions' => array('status' => 1, 'is_fenxiao' => 1), 'order' => 'orderby'));
            if (!empty($user_type_arr2)) {
                foreach ($user_type_arr2 as $k => $v) {
                    $user_type['fenxiao'][$v['TaobaoShop']['nick']] = $v['TaobaoShop']['nick'];
                }
            }
        }
        $this->set('user_type_arr', $user_type_arr);
        $this->set('user_type', $user_type);
    }

    //用户私信
    public function userchat($page = 1)
    {
        $this->operator_privilege('userchat_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/user_fans/');//设置导航栏
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['member_interaction_management'],'url' => '/user_fans/');
        $this->navigations[] = array('name' => $this->ld['private_letter_management'],'url' => '/users/userchat');
        $condition = array();
        //私信搜索条件
        if (isset($_REQUEST['sender']) && $_REQUEST['sender'] != '') {
            $cond1['or']['User.name like'] = '%'.$_REQUEST['sender'].'%';
            $cond1['or']['User.email like'] = '%'.$_REQUEST['sender'].'%';
            $cond1['or']['User.first_name like'] = '%'.$_REQUEST['sender'].'%';
            $cond1['or']['User.last_name like'] = '%'.$_REQUEST['sender'].'%';
            $user_ids1 = $this->User->find('list', array('fields' => 'User.id', 'conditions' => $cond1));
            $condition['UserChat.user_id'] = $user_ids1;
            $this->set('sender', $_REQUEST['sender']);
        }
        if (isset($_REQUEST['receiver']) && $_REQUEST['receiver'] != '') {
            $cond2['or']['User.name like'] = '%'.$_REQUEST['receiver'].'%';
            $cond2['or']['User.email like'] = '%'.$_REQUEST['receiver'].'%';
            $cond2['or']['User.first_name like'] = '%'.$_REQUEST['receiver'].'%';
            $cond2['or']['User.last_name like'] = '%'.$_REQUEST['receiver'].'%';
            $user_ids2 = $this->User->find('list', array('fields' => 'User.id', 'conditions' => $cond2));
            $condition['UserChat.to_user_id'] = $user_ids2;

            $this->set('receiver', $_REQUEST['receiver']);
        }
        if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') {
            $condition['UserChat.created  >='] = $_REQUEST['start_date'];
            $this->set('start_date', $_REQUEST['start_date']);
        }
        if (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') {
            $condition['UserChat.created  <='] = $_REQUEST['end_date'].' 23:59:59';
            $this->set('end_date', $_REQUEST['end_date']);
        }

        $total = $this->UserChat->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'UserChat';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'users','action' => 'userchat','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserChat');
        $this->Pagination->init($condition, $parameters, $options);
        $chat_list = $this->UserChat->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'created desc'));
        $user_arr = $this->User->find('all', array('conditions' => array('User.status' => '1'), 'fields' => array('User.id', 'User.name')));
        foreach ($chat_list as $k => $v) {
            foreach ($user_arr as $kk => $vv) {
                if ($v['UserChat']['user_id'] == $vv['User']['id']) {
                    $chat_list[$k]['UserChat']['user_name'] = $vv['User']['name'];
                }
                if ($v['UserChat']['to_user_id'] == $vv['User']['id']) {
                    $chat_list[$k]['UserChat']['to_user_name'] = $vv['User']['name'];
                }
            }
        }
        //设置页面标题
        $this->set('title_for_layout', $this->ld['private_letter_management'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->set('chat_list', $chat_list);
    }

    /**
     * 删除私信
     *
     *@param int $id 输入会员ID
     */
    public function removechat($id)
    {
        $this->operator_privilege('userchat_remove');
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $chat_list = $this->UserChat->find('all', array('conditions' => array('UserChat.id' => $id)));
        $this->UserChat->deleteAll(array('id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 删除私信:id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function removeallchat()
    {
        $userchat_checkboxes = $_REQUEST['checkboxes'];
        foreach ($userchat_checkboxes as $k => $v) {
            $this->UserChat->deleteAll(array('UserChat.id' => $v), false);
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }

    /*
        确认等级修改
    */
    public function ajax_setupgrade($user_id = 0)
    {
        if ($this->RequestHandler->isPost()) {
            $result['code'] = 0;
            $result['message'] = $this->ld['modify_failed'];

            $rank_id = isset($_POST['rank_id']) ? $_POST['rank_id'] : '0';
            $start_date = isset($_POST['start_time']) && $_POST['start_time'] != '' ? $_POST['start_time'] : '0000-00-00 00:00:00';
            $end_date = isset($_POST['end_time']) && $_POST['end_time'] != '' ? $_POST['end_time'] : '0000-00-00 00:00:00';
            $rankInfoflag = false;
            if ($rank_id != '0') {
                if ($start_date == '') {
                    $result['message'] = $this->ld['start_time_not_empty'];
                } elseif ($end_date == '') {
                    $result['message'] = $this->ld['end_time_not_empty'];
                } else {
                    if (strtotime($end_date) < strtotime($start_date)) {
                        $result['message'] = $this->ld['end_time_less_start_time'];
                    } elseif (strtotime($end_date) == strtotime($start_date)) {
                        $result['message'] = $this->ld['end_time_equal_start_time'];
                    } else {
                        $rankInfoflag = true;
                    }
                }
            } else {
                $rankInfoflag = true;
            }

            if ($rankInfoflag) {
                //等级信息
                $userRankInfo = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $rank_id, 'UserRankI18n.locale' => $this->locale)));

                $userranklog['rank_id'] = $rank_id;
                $userranklog['user_id'] = $user_id;
                $userranklog['start_date'] = $start_date;
                $userranklog['end_date'] = $end_date;
                $userranklog['operator_id'] = $this->admin['id'];//当前管理员
                //管理员操作无需支付金额，默认支付成功
                $userranklog['balance'] = isset($userRankInfo['UserRank']['balance']) ? $userRankInfo['UserRank']['balance'] : '0.00';
                $userranklog['pay_status'] = '1';
                $userranklog['created'] = date('Y-m-d H:i:s', time());
                $userranklog['modified'] = date('Y-m-d H:i:s', time());

                if ($this->UserRankLog->save($userranklog)) {
                    $result['code'] = 1;
                    //修改用户表记录
                    $data['User']['id'] = $user_id;
                    $data['User']['rank'] = $rank_id;
                    $this->User->save(array('User' => $data['User']));
                    //会员信息
                    $userInfo = $this->User->find('first', array('fields' => array('User.id', 'User.name'), 'conditions' => array('User.id' => $user_id)));
                    if (empty($userRankInfo)) {
                        $result['name'] = $this->ld['vip'];
                    } else {
                        $result['name'] = $userRankInfo['UserRankI18n']['name'];
                    }
                    $result['message'] = $this->ld['operation_success'];
                    //操作员日志
                    if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_edit_user_rank'].':'.$userInfo['User']['name'].' - '.$result['name'], $this->admin['id']);
                    }
                }
            }
            Configure::write('debug', 1);
            $this->layout = 'ajax';
            die(json_encode($result));
        } else {
            $this->redirect('/admin/users/');
        }
    }

    public function uploadusers()
    {
        $this->operator_privilege('users_upload');
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
        $this->navigations[] = array('name' => $this->ld['batch_upload_user'],'url' => '');
        $flag_code = 'user_export';

        $this->Profile->set_locale($this->locale);
        $profilefiled_codes = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
        if (isset($profilefiled_codes) && !empty($profilefiled_codes)) {
            $this->set('profilefiled_codes', $profilefiled_codes);
        }
    }

    public function uploaduserspreview()
    {
        $this->operator_privilege('users_upload');
        if ($this->RequestHandler->isPost()) {
            $this->menu_path = array('root' => '/crm/','sub' => '/users/');
            $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
            $this->navigations[] = array('name' => $this->ld['batch_upload_user'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
            $this->set('title_for_layout', $this->ld['preview'].' - '.$this->configs['shop_name']);
            $flag_code = 'user_export';
            $this->Profile->set_locale($this->locale);
            set_time_limit(300);
            if (!empty($_FILES['file'])) {
                if ($_FILES['file']['error'] > 0) {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/users/uploadusers';</script>";
                    die();
                } else {
                    $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                    $this->Profile->set_locale($this->locale);
                    $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
                    $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
                    if (empty($profilefiled_info)) {
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/users/uploadusers';</script>";
                        die();
                    }
                    $key_arr = array();
                    $key_desc=array();
                    $key_code=array();
                    foreach ($profilefiled_info as $k => $v) {
				$fields_k=array();
				$fields_k = explode('.', $v['ProfileFiled']['code']);
				$key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
				$key_desc[]= $v['ProfilesFieldI18n']['description'];
				$key_code[$v['ProfilesFieldI18n']['description']]=isset($fields_k[1]) ? $fields_k[1] : '';
                    }
                    $this->set('key_code',$key_code);
                    $preview_key=array();
                    $csv_export_code = 'gb2312';
                    $i = 0;
                    while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                        if ($i == 0) {
					foreach ($row as $k => $v) {
						$preview_key[]=iconv('GB2312', 'UTF-8', $v);
	                                if (isset($key_arr[$k]) && !empty($key_arr[$k])) {
	                                    continue;
	                                } 
	                            }
                            $check_row = $row[0];
                            $row_count = count($row);
                            $check_row = iconv('GB2312', 'UTF-8', $check_row);
                            $num_count = count($profilefiled_info);
                            if ($row_count > $num_count || $check_row != $profilefiled_info[0]['ProfilesFieldI18n']['description']) {
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('上传文件格式不标准');window.location.href='/admin/users/uploadusers';</script>";
                                die();
                            }
                            ++$i;
                        }
                        $temp = array();
                        foreach ($row as $k => $v) {
                        	  $data_key_code=isset($key_code[$preview_key[$k]])?$key_code[$preview_key[$k]]:'';
                            $temp[$preview_key[$k]] = ($v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v));
                            if (isset($key_arr[$k]) && !empty($key_arr[$k])) {
                               	$temp[$data_key_code] = ($v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v));
                            }
                        }
                        if (!isset($temp) || empty($temp)) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/users/uploadusers';</script>";
                            die();
                        }
                        $data[] = $temp;
                    }
                    fclose($handle);
                    $this->set('profilefiled_info', $profilefiled_info);
                    $this->set('uploads_list', $data);
                    $i = 0;
                    $discount = array();
                    $info = array();
                    foreach ($data as $k => $v) {
                        if ($k == 0) {
                            continue;
                        }
                        if ($v['mobile'] != '') {
                            $info = $this->User->find('first', array('conditions' => array('User.mobile' => $v['mobile'])));
                        }
                        if (empty($info) && $v['email'] != '') {
                            $info = $this->User->find('first', array('conditions' => array('User.email' => $v['email'])));
                        }
                        if (empty($info) && $v['name'] != '') {
                            $info = $this->User->find('first', array('conditions' => array('User.name' => $v['name'])));
                        }
                        if (!empty($info)) {
                            $discount[$k] = 'discount';
                            ++$i;
                        }
                    }
                    if ($i > 0) {
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('导入数据有".$i."条与本站重复');</script>";
                    }
                    $this->set('discount', $discount);
                    $this->set('extension_code', array('' => $this->ld['real_product'], 'virtual_card' => $this->ld['virtual_cards']));
                }
            }
        } else {
            $this->redirect('/users/');
        }
    }

    /*
        保存用户模板
    */
    public function update_user_style()
    {
        $this->operator_privilege('users_edit');
        if ($this->RequestHandler->isPost()) {
            $this->layout = 'ajax';
            Configure::write('debug', 1);
            $this->loadModel('UserStyle');
            $this->loadModel('UserStyleValue');
            $result['code'] = 0;
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
            $del_id = isset($_POST['del_id']) ? $_POST['del_id'] : 0;
            if (isset($this->data['UserStyle'])) {
                if ($this->data['UserStyle']['default_status'] == 1) {
                    //将其他默认状态改成0（相同版型，相同规格，相同属性组）
                    $this->UserStyle->updateAll(array('UserStyle.default_status' => '0'), array('user_id' => $user_id, 'style_id' => $this->data['UserStyle']['style_id'], 'type_id' => $this->data['UserStyle']['type_id'], 'attribute_code' => $this->data['UserStyle']['attribute_code']));
                }
                $this->data['UserStyle']['user_style_name'] = $this->data['UserStyle']['user_style_name'].'-'.date('Y-m-d');
                $this->UserStyle->saveAll($this->data['UserStyle']);
                $user_id = isset($this->data['UserStyle']['user_id']) ? $this->data['UserStyle']['user_id'] : 0;
                $user_style_id = $this->UserStyle->id;
            }
            if (isset($this->data['UserStyleValue'])) {
                $this->UserStyleValue->deleteAll(array('UserStyleValue.user_style_id' => $user_style_id));
                foreach ($this->data['UserStyleValue'] as $k => $v) {
                    $this->data['UserStyleValue'][$k]['user_style_id'] = isset($user_style_id) ? $user_style_id : 0;
                }
                $this->UserStyleValue->saveAll($this->data['UserStyleValue']);
            }
            if (isset($del_id) && $del_id != 0) {
                $this->UserStyle->deleteAll(array('UserStyle.id' => $del_id));
                $this->UserStyleValue->deleteAll(array('UserStyleValue.user_style_id' => $del_id));
            }
            if (isset($user_id) && $user_id != 0) {
                $this->loadModel('ProductStyle');
                $this->loadModel('ProductType');
                $this->ProductType->set_locale($this->backend_locale);
                $type_list = $this->ProductType->find('all', array('conditions' => array('ProductType.status' => 1, 'ProductType.id !=' => 0)));
                $user_style_list = $this->UserStyle->find('all', array('conditions' => array('UserStyle.user_id' => $user_id), 'order' => 'UserStyle.type_id'));

                $style_name = $this->ProductStyle->product_style_tree($this->backend_locale);
                foreach ($user_style_list as $k => $v) {
                    foreach ($style_name as $sk => $sv) {
                        if ($v['UserStyle']['style_id'] == $sv['ProductStyle']['id']) {
                            $user_style_list[$k]['UserStyle']['style_name'] = $sv['ProductStyleI18n']['style_name'];
                        }
                    }
                }
                foreach ($user_style_list as $uk => $uv) {
                    foreach ($type_list as $tk => $tv) {
                        if ($uv['UserStyle']['type_id'] == $tv['ProductType']['id']) {
                            $user_style_list[$uk]['UserStyle']['attr_name'] = $tv['ProductTypeI18n']['name'];
                        }
                    }
                }
                $this->set('user_style_list', $user_style_list);
            }
        }
    }
    /*
        显示编辑用户模板
    */
    public function edit_user_style()
    {
        $this->operator_privilege('users_edit');
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        $this->loadModel('UserStyle');
        $this->loadModel('UserStyleValue');
        $this->loadModel('StyleTypeGroup');
        $result['code'] = 0;
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
        $style_id = isset($_POST['edit_id']) ? $_POST['edit_id'] : 0;
        //用户模板
        $user_style = $this->UserStyle->find('first', array('conditions' => array('UserStyle.id' => $style_id)));
        //规格下拉
        $group_name = $this->StyleTypeGroup->find('all', array('conditions' => array('StyleTypeGroup.style_id' => $user_style['UserStyle']['style_id'], 'StyleTypeGroup.type_id' => $user_style['UserStyle']['type_id'], 'StyleTypeGroup.status' => 1), 'order' => 'StyleTypeGroup.orderby asc', 'fields' => 'StyleTypeGroup.id,StyleTypeGroup.group_name'));
        if (count($group_name) > 0) {
            $result['group_name'] = $group_name;
        }
        if (count($user_style) > 0) {
            $result['user_style'] = $user_style;
            $result['code'] = 1;
        } else {
            $result['code'] = 0;
        }
        die(json_encode($result));
    }
    /*
        显示规格下拉
    */
    public function show_group_type()
    {
        $this->operator_privilege('users_edit');
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        $this->loadModel('StyleTypeGroup');
        $result['code'] = 0;
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
        $style_id = isset($_POST['style_id']) ? $_POST['style_id'] : 0;
        $product_type = isset($_POST['product_type']) ? $_POST['product_type'] : 0;
        //规格下拉
        $group_name = $this->StyleTypeGroup->find('all', array('conditions' => array('StyleTypeGroup.style_id' => $style_id, 'StyleTypeGroup.type_id' => $product_type, 'StyleTypeGroup.status' => 1), 'order' => 'StyleTypeGroup.orderby asc', 'fields' => 'StyleTypeGroup.id,StyleTypeGroup.group_name'));
        if (count($group_name) > 0) {
            $result['group_name'] = $group_name;
            $result['code'] = 1;
        } else {
            $result['code'] = 0;
        }
        die(json_encode($result));
    }
    /*
        显示规格属性，修改范围
    */
    public function show_attr_value()
    {
        $this->operator_privilege('users_edit');
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        $this->loadModel('StyleTypeGroupAttributeValue');
        $this->loadModel('ProductTypeAttribute');
        $this->loadModel('Attribute');
        $this->loadModel('UserStyleValue');
        $this->loadModel('UserStyle');
        $this->Attribute->set_locale($this->backend_locale);

        $result['code'] = 0;
        $style_id = isset($_POST['style_id']) ? $_POST['style_id'] : 0;
        $product_type = isset($_POST['product_type']) ? $_POST['product_type'] : 0;
        $group_name = isset($_POST['group_name']) ? $_POST['group_name'] : 0;
        $user_style_id = isset($_POST['user_style_id']) ? $_POST['user_style_id'] : 0;
        //规格下拉
        $attr_list = $this->StyleTypeGroupAttributeValue->find('all', array('conditions' => array('StyleTypeGroupAttributeValue.style_id' => $style_id, 'StyleTypeGroupAttributeValue.type_id' => $product_type, 'StyleTypeGroupAttributeValue.style_type_group_id' => $group_name), 'fields' => 'StyleTypeGroupAttributeValue.default_value,StyleTypeGroupAttributeValue.select_value,StyleTypeGroupAttributeValue.attribute_id'));
        $attr_ids = $this->ProductTypeAttribute->getattrids($product_type);
        $attr_group = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids, 'Attribute.status' => 1, 'Attribute.type' => 'customize'), 'fields' => 'Attribute.id,Attribute.code,Attribute.attr_type,AttributeI18n.name,AttributeI18n.default_value,AttributeI18n.attr_value', 'order' => 'Attribute.id'));

        $attr_values = array();
        $attr_value_infos = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids), 'fields' => array('Attribute.id', 'AttributeI18n.default_value')));
        foreach ($attr_value_infos as $k => $v) {
            $attr_values[$v['Attribute']['id']] = $v['AttributeI18n']['default_value'];
        }
        $show_attr_list = array();
        foreach ($attr_group as $gk => $gv) {
            $show_attr_data = array();

            $select_value = array();
            if (!empty($gv['AttributeOption'])) {
                foreach ($gv['AttributeOption'] as $vv) {
                    $select_value[$vv['option_value']] = $vv['option_name'];
                }
            }
            $show_attr_data['attribute_id'] = $gv['Attribute']['id'];
            $show_attr_data['attr_name'] = $gv['AttributeI18n']['name'];
            $show_attr_data['default_value'] = $gv['AttributeI18n']['default_value'];
            $show_attr_data['select_value'] = $select_value;
            $show_attr_data['attr_type'] = $gv['Attribute']['attr_type'];
            foreach ($attr_list as $ak => $av) {
                if ($gv['Attribute']['id'] == $av['StyleTypeGroupAttributeValue']['attribute_id']) {
                    $show_attr_data['default_value'] = !empty($av['StyleTypeGroupAttributeValue']['default_value']) ? $av['StyleTypeGroupAttributeValue']['default_value'] : $attr_values[$av['StyleTypeGroupAttributeValue']['attribute_id']];
                    $show_attr_data['select_value'] = $av['StyleTypeGroupAttributeValue']['select_value'];
                }
            }
            $show_attr_list[$gk] = $show_attr_data;
        }
        if (!empty($user_style_id) && $user_style_id != 0) {
            $user_style_data = $this->UserStyle->find('first', array('conditions' => array('UserStyle.id' => $user_style_id)));
            $user_style_value_data = $this->UserStyleValue->find('all', array('conditions' => array('UserStyleValue.user_style_id' => $user_style_id)));
            $user_style_value_data_list = array();
            foreach ($user_style_value_data as $v) {
                $user_style_value_data_list[$v['UserStyleValue']['attribute_id']] = $v['UserStyleValue']['attribute_value'];
            }
            $this->set('user_style_data', $user_style_data);
            $this->set('user_style_value_data_list', $user_style_value_data_list);
        }
        //版型规格尺寸列表
        $attrvaluelist = array();
        $pro_type_attr_type_list = array();//属性修改可选值列表
        if (isset($group_name)) {
            $attrvalueInfo = $this->StyleTypeGroupAttributeValue->getattrvaluelist($style_id, $product_type, $group_name);
            foreach ($attrvalueInfo as $v) {
                $attrids[] = $v['StyleTypeGroupAttributeValue']['attribute_id'];
                $attrvaluelist[$v['StyleTypeGroupAttributeValue']['attribute_id']] = $v['StyleTypeGroupAttributeValue']['default_value'];
                if (trim($v['StyleTypeGroupAttributeValue']['select_value']) != '') {
                    $pro_type_attr_type_list[$v['StyleTypeGroupAttributeValue']['attribute_id']] = split("\r\n", $v['StyleTypeGroupAttributeValue']['select_value']);
                }
            }
            foreach ($attr_values as $k => $v) {
                if (empty($attrvaluelist[$k])) {
                    $attrvaluelist[$k] = $v;
                }
            }
        }
        $this->set('attrvaluelist', $attrvaluelist);
        $this->set('show_attr_list', $show_attr_list);
    }

    public function batch_add_user()
    {
        if (!empty($this->data)) {
            $checkbox_arr = $_REQUEST['checkbox'];
            $i = 0;
            $j = 0;
            foreach ($this->data as $key => $data) {
                if (!in_array($key, $checkbox_arr)) {
                    continue;
                }
                if ($data['email'] == '') {
                    continue;
                }
                $info = array();
                if ($data['mobile'] != '') {
                    $info = $this->User->find('first', array('conditions' => array('User.mobile' => $data['mobile'])));
                }
                if (empty($info)) {
                    $info = $this->User->find('first', array('conditions' => array('User.email' => $data['email'])));
                }
                if (empty($info)) {
                    $info = $this->User->find('first', array('conditions' => array('User.name' => $data['name'])));
                }
                if (!empty($info)) {
                    $data['User'] = $info['User'];
                    ++$j;
                } else {
                    $data['id'] = '';
                    $data['User'] = $data;
                    ++$i;
                }
                $this->User->saveAll($data['User']);
                $user_id = $this->User->id;
                if (isset($data['UserAddress'])) {
                    $user_address['UserAddress'] = $data['UserAddress'];
                    $user_address['UserAddress']['user_id'] = $user_id;
                    if (empty($data['UserAddress']['consignee']) && isset($data['User']['name'])) {
                        $user_address['UserAddress']['consignee'] = $data['User']['name'];
                    }
                    if (empty($data['UserAddress']['email']) && isset($data['User']['email'])) {
                        $user_address['UserAddress']['email'] = $data['User']['email'];
                    }
                    if (empty($data['UserAddress']['mobile']) && isset($data['User']['mobile'])) {
                        $user_address['UserAddress']['mobile'] = $data['User']['mobile'];
                    }
                    //获取区域ID
                    $order_country_id = '';
                    $order_province_id = '';
                    $order_city_id = '';
                    if (isset($data['UserAddress']['country'])) {
                        $country = $this->RegionI18n->find('first', array('conditions' => array('RegionI18n.locale' => $this->locale, 'RegionI18n.name' => $data['UserAddress']['country']), 'fields' => 'RegionI18n.region_id'));
                        if (!empty($country)) {
                            $order_country_id = $country['RegionI18n']['region_id'];
                            $user_address['UserAddress']['country'] = $order_country_id;
                        }
                    }
                    if (isset($data['UserAddress']['province'])) {
                        $province = $this->RegionI18n->find('first', array('conditions' => array('RegionI18n.locale' => $this->locale, 'RegionI18n.name' => $data['UserAddress']['province']), 'fields' => 'RegionI18n.region_id'));
                        if (!empty($province)) {
                            $order_province_id = $province['RegionI18n']['region_id'];
                            $user_address['UserAddress']['province'] = $order_province_id;
                        }
                    }
                    if (isset($data['UserAddress']['city'])) {
                        $city = $this->RegionI18n->find('first', array('conditions' => array('RegionI18n.locale' => $this->locale, 'RegionI18n.name' => $data['UserAddress']['city']), 'fields' => 'RegionI18n.region_id'));
                        if (!empty($city)) {
                            $order_city_id = $city['RegionI18n']['region_id'];
                            $user_address['UserAddress']['city'] = $order_city_id;
                        }
                    }
                    $this->UserAddress->saveAll($user_address);
                    $aId = $this->UserAddress->id;
                    $this->User->updateAll(array('User.address_id' => $aId), array('User.id' => $user_id));
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'批量上传会员', $this->admin['id']);
            }
            $count_k = $i + $j;
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$count_k.'条'.$this->ld['import_success'].' 新增'.$i.'条 编辑'.$j."条');window.location.href='/admin/users'</script>";
        }
    }

    public function download_csv_example()
    {
        $this->loadModel('ProfilesFieldI18n');
        $this->Profile->set_locale($this->backend_locale);
        $this->Profile->hasOne = array();
        $flag_code = 'user_export';
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $tmp = array();
        $fields_array = array();
        $newdatas = array();
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            foreach ($profilefiled_info as $k => $v) {
                $tmp[] = $v['ProfilesFieldI18n']['description'];
                $fields_array[] = $v['ProfileFiled']['code'];
            }
        }
        $newdatas[] = $tmp;
        $user_info = $this->User->find('all', array('order' => 'User.id desc', 'limit' => 10));//'recursive'=>-1
        $user_ids = array();
        foreach ($user_info as $k => $v) {
            $user_ids[] = $v['User']['id'];
        }
        $filename = '会员导出'.date('Ymd').'.csv';
        $useradd_info = $this->UserAddress->find('all', array('conditions' => array('UserAddress.user_id' => $user_ids)));
        foreach ($user_info as $k => $v) {
            foreach ($useradd_info as $kk => $vv) {
                if ($vv['UserAddress']['user_id'] == $v['User']['id']) {
                    $user_info[$k]['UserAddress'] = $vv['UserAddress'];
                }
            }
        }
        foreach ($user_info as $k => $v) {
            $user_tmp = array();
            foreach ($fields_array as $kk => $vv) {
                $fields_kk = explode('.', $vv);
                $user_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
            }
            $newdatas[] = $user_tmp;
        }
        $this->Phpcsv->output($filename, $newdatas);
        exit;
    }

    public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"')
    {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = '';
        $eof = false;
        while ($eof != true) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) {
                $eof = true;
            }
        }
        $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for ($_csv_i = 0; $_csv_i < count($_csv_data); ++$_csv_i) {
            $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
            $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }

        return empty($_line) ? false : $_csv_data;
    }

    public function config()
    {
        $this->operator_privilege('configvalues_view');
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
        $this->navigations[] = array('name' => $this->ld['vip'].$this->ld['set_up'],'url' => '');
        $this->set('title_for_layout', $this->ld['vip'].$this->ld['set_up'].' - '.$this->configs['shop_name']);

        if ($this->RequestHandler->isPost()) {
            if (!empty($this->data)) {
                foreach ($this->data as $vv) {
                    $data = array();
                    $vv['value'] = isset($vv['value']) ? $vv['value'] : 0;
                    $data = $vv;
                    $this->ConfigI18n->saveAll($data);
                }
            }
            $this->redirect('/users');
        }

        $resource_code = 'user_set';
        $group_code = 'user';

        $Resource_info = $this->Resource->find('first', array('conditions' => array('Resource.code' => $resource_code, 'Resource.status' => 1)));
        if (!empty($Resource_info)) {
            $resource_cond['Resource.parent_id'] = $Resource_info['Resource']['id'];
            $resource_cond['Resource.status'] = 1;
            $resource_cond['ResourceI18n.locale'] = $this->backend_locale;
            $Resource_list_info = $this->Resource->find('all', array('conditions' => $resource_cond, 'order' => 'orderby'));
            $resource_list = array();
            foreach ($Resource_list_info as $v) {
                $resource_list[$v['Resource']['code']] = $v['ResourceI18n']['name'];
            }

            $this->Config->hasOne = array();
            $this->Config->hasMany = array('ConfigI18n' => array('className' => 'ConfigI18n',
                                  'conditions' => '',
                                  'order' => '',
                                  'dependent' => true,
                                  'foreignKey' => 'config_id',
                            ),
                      );

            $conditions['Config.group_code'] = $group_code;
            $conditions['Config.status'] = 1;
            $conditions['Config.readonly'] = 0;
            $configs = $this->Config->find('all', array('conditions' => $conditions, 'order' => 'Config.orderby,Config.group_code'));
            $config_group_list = array();
            $val = array();
            foreach ($configs as $k => $v) {
                $val['Config'] = $v['Config'];
                foreach ($v['ConfigI18n'] as $kk => $vv) {
                    if ($vv['locale'] == $this->backend_locale) {
                        $val['Config']['name'] = @$vv['name'];
                    }
                    $val['ConfigI18n'][$vv['locale']] = $vv;
                    if ($v['Config']['type'] == 'radio' || $v['Config']['type'] == 'checkbox' || $v['Config']['type'] == 'image') {
                        $val['ConfigI18n'][$vv['locale']]['options'] = explode("\n", $vv['options']);
                    }
                }
                $config_groups[$v['Config']['subgroup_code']][] = $val;
            }
            $this->set('resource_list', $resource_list);
            $this->set('config_groups', $config_groups);
        } else {
            $this->redirect('/users');
        }
    }

    public function ajaxuploadavatar($user_id = 0, $inputName = '')
    {
        $this->operator_privilege('users_edit');
        $result['code'] = 0;
        $result['msg'] = 'not file';
        if ($this->RequestHandler->isPost()) {
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
            $max_file = '3'; //图片大小限制
            $max_width = '300';            //大图最大宽度
            $thumb_width = '180';        //小图最大宽度
            $thumb_height = '180';        //小图最大高度
            $img_root = 'media/users/'.date('Ym').'/';
            $imgaddr = WWW_ROOT.'media/users/'.date('Ym').'/';
            $this->mkdirs($imgaddr);
            @chmod($imgaddr, 0777);
            if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
                $userfile_name = $_FILES[$inputName]['name'];
                $userfile_tmp = $_FILES[$inputName]['tmp_name'];
                $userfile_size = $_FILES[$inputName]['size'];
                $userfile_type = $_FILES[$inputName]['type'];
                $filename = basename($_FILES[$inputName]['name']);
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
                $result['msg'] = '上传失败';
                if (strlen($error) == 0) {
                    $image_location = $imgaddr.md5(date('Y-m-d h:i:s').$user_id.$userfile_name).'.'.$file_ext;
                    $image_name = '/'.$img_root.md5(date('Y-m-d h:i:s').$user_id.$userfile_name).'.'.$file_ext;

                    if (move_uploaded_file($userfile_tmp, $image_location)) {
                        $width = $this->getWidth($image_location);
                        $height = $this->getHeight($image_location);

                        if ($width < $thumb_width || $height < $thumb_height) {
                            $result['msg'] = '图片尺寸太小';
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
                            $result['code'] = 1;
                            $result['img_url'] = $image_name;
                            $result['msg'] = '';
                        }
                    }
                }
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
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

    public function check_user_sn_exist($id = 0)
    {
        $result['code'] = 0;
        $result['msg'] = '';
        if ($this->RequestHandler->isPost()) {
            $user_sn = isset($_POST['user_sn']) ? $_POST['user_sn'] : '';
            $user_sn_count = $this->User->find('count', array('conditions' => array('User.user_sn' => $user_sn, 'User.id !=' => $id)));
            if ($user_sn_count == 0) {
                $result['code'] = 1;
            } else {
                $result['msg'] = $this->ld['username_already_exists'];
            }
            die(json_encode($result));
        } else {
            $this->redirect('/users');
        }
    }

    public function check_user_data()
    {
        $result['code'] = 0;
        $result['msg'] = '';
        if ($this->RequestHandler->isPost()) {
            $mobile = $_REQUEST['mobile'];
            $isset_mobile = $this->User->check_user_mobile_exist($mobile);
            if ($isset_mobile) {
                $result['code'] = 0;
                $result['msg'] = '手机号已存在';
            } else {
                $result['code'] = 1;
                $result['msg'] = '';
            }
            die(json_encode($result));
        } else {
            $this->redirect('/users');
        }
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
                if(move_uploaded_file($userfile_tmp, $file_location)) {
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
}
