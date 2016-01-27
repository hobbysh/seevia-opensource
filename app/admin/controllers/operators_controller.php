<?php

/*****************************************************************************
 * Seevia 操作员管理
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
class OperatorsController extends AppController
{
    public $name = 'Operators';
    public $components = array('Captcha','Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor','Svshow');
    public $uses = array('Operator','OperatorRole','Language','OperatorLog','OperatorAction');

    public function ajax_login()
    {
        Configure::write('debug', 0);
        $result['code'] = 0;
        //$result['message'] = "未知错误";
        if ($this->RequestHandler->isPost()) {
            $this->layout = 'ajax';
            $counts = $this->Cookie->read('count_login');
            // 判断验证码
            if ($counts >= 2 && $this->configs['admin_captcha'] == 1 && (!isset($_REQUEST['authnum']) || $this->captcha->check($_REQUEST['authnum']) == false)) {
                $result['message'] = $this->ld['verification_code_error.'].$_REQUEST['authnum'];
                $result['code'] = 100;
                $result['count_login'] = $counts;
                die(json_encode($result));
            }

            $operator = trim($_REQUEST['operator']);
            $operator_pwd = trim($_REQUEST['operator_pwd']);
            $operator = $this->Operator->findbyname($operator);
            // 判断账户是否存在
            if (!$operator) {
                $result['message'] = $this->ld['user_not_exist'];
                ++$counts;
                $this->set('count_login', $counts);
                $this->Cookie->write('count_login', $counts, false, time() + 600);
                $result['code'] = 101;
                $result['count_login'] = $counts;
                die(json_encode($result));
            }
            //判断登陆次数
            if ($counts >= 5) {
                $result['message'] = $this->ld['login_time_error'];
                $result['code'] = 102;
                $result['count_login'] = $counts;
                die(json_encode($result));
            }
            // 判断密码
            if ($operator_pwd != $operator['Operator']['password']) {
                ++$counts;
                $this->set('count_login', $counts);
                $this->Cookie->write('count_login', $counts, false, time() + 600);
                $result['message'] = $this->ld['password_error'];
                $result['code'] = 103;
                $result['count_login'] = $counts;
                die(json_encode($result));
            }
            // 判断状态
            if ($operator['Operator']['status'] != 1) {
                switch ($operator['Operator']['status']) {
                    case 0:
                    $result['message'] = $this->ld['account_number'].$this->ld['account_number_invalid_state'];
                    break;
                    case 2:
                    $result['message'] = $this->ld['account_number'].$this->ld['account_number_frozen'];
                    break;
                    case 3:
                    $result['message'] = $this->ld['account_number'].$this->ld['account_number_logged_out'];
                    break;
                }
                $result['code'] = 104;
                $result['count_login'] = $counts;
                die(json_encode($result));
            }
            // 登陆成功
                $this->Cookie->delete('count_login');
            $operator['Operator']['last_login_time'] = date('Y-m-d H:i:s');
            $operator['Operator']['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
            $operator['Operator']['session'] = session_id();
            $operator['Operator']['default_lang'] = $_REQUEST['locale'];
            $this->Operator->save($operator);//更新IP地址  和  登入时间
                //管理员管理权限
                if (isset($_REQUEST['cookie_session']) && $_REQUEST['cookie_session'] != '0') {
                    $this->Cookie->write('session', session_id(), false, '15 day');
                } else {
                    $this->Cookie->delete('session');
                }

            if ($operator['Operator']['template_code'] == 'default') {
                $result['url'] = '/admin/pages/home';
            } else {
                if (isset($_SESSION['url']) && $_SESSION['url'] != '/admin/pages/home') {
                    $result['url'] = $_SESSION['url'];
                    unset($_SESSION['url']);
                } else {
                    $result['url'] = '';
                }
            }
        }
        die(json_encode($result));
    }

    public function index($page = 1)
    {
        $this->operator_privilege('operators_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        //设置子菜单位置
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        //定义导航显示
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operators'],'url' => '/operators/');
        /*
         $operator_name = $this->Operator->find("first",array("conditions"=>array("Operator.id"=>1)));
         if($this->admin['name']==$operator_name['Operator']['name']){
         $this->set('operator_name',$operator_name['Operator']['name']);
         }*/
         $type_id = $this->Operator->find('first', array('fields' => array('Operator.type', 'Operator.type_id', 'Operator.id', 'Operator.actions'), 'conditions' => array('Operator.name' => $this->admin['name'])));
    //	$condition ="Operator.type=>".$type;
        $condition = array();
    //	$condition["Operator.type_id"]=$type_id['Operator']['type_id'];
        $_SESSION['type_id'] = $type_id['Operator']['type_id'];
        $_SESSION['id'] = $type_id['Operator']['id'];
        $_SESSION['type'] = $type_id['Operator']['type'];
        $_SESSION['actions'] = $type_id['Operator']['actions'];
        if ($_SESSION['type'] != 'S' && !isset($this->params['url']['type']) && !isset($this->params['url']['type_id'])) {
            $this->set('type', $_SESSION['type']);
            $this->set('type_id', $_SESSION['type_id']);
        }
        if (!empty($this->params['url']['type'])) {
            if ($this->params['url']['type'] == 'S') {
                $condition['Operator.type'] = $this->params['url']['type'];
                if (isset($condition['Operator.type_id'])) {
                    unset($condition['Operator.type_id']);
                }
            }
            if ($this->params['url']['type'] == 'D' && $this->params['url']['type_id'] != '0') {
                $condition['Operator.type'] = $this->params['url']['type'];
                $condition['Operator.type_id'] = $this->params['url']['type_id'];
                $this->set('type', $this->params['url']['type']);
                $this->set('type_id', $this->params['url']['type_id']);
            }
            if ($this->params['url']['type'] == 'D' && $this->params['url']['type_id'] == '0') {
                $condition['Operator.type'] = $this->params['url']['type'];
                $condition['Operator.type_id <'] = $this->params['url']['type_id'];
                $this->set('type', $this->params['url']['type']);
                $this->set('type_id', $this->params['url']['type_id']);
            }
        }
        if (!isset($this->params['url']['type']) || !isset($this->params['url']['type'])) {
            $condition['Operator.type'] = $_SESSION['type'];
            $condition['Operator.type_id'] = isset($_SESSION['type_id']) ? $_SESSION['type_id'] : 0;
        }
//		 if(isset($this->params['url']['type'])&&$this->params['url']['type']=="D"){
//		    	$condition["Operator.type"]=$this->params['url']['type'];
//		    	$condition["Operator.type_id"]=$this->params['url']['type_id'];
//		 }
        $total = $this->Operator->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'operators','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Operator');
        $this->Pagination->init($condition, $parameters, $options);
        $url = '?';
        if (isset($this->params['url']['type'])) {
            $url .= 'type='.$this->params['url']['type'];
        }
        if (isset($this->params['url']['type_id'])) {
            $url .= '&type_id='.$this->params['url']['type_id'];
        }
        $this->set('url', $url);
        $_SESSION['index_url'] = $url;
        $operator_data = $this->Operator->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'Operator.id'));
        $this->set('operator_data', $operator_data);
        $this->set('title_for_layout', $this->ld['operators'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($id = 0)
    {
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        if (empty($id)) {
            $this->operator_privilege('operators_add');
        } else {
            $this->operator_privilege('operators_edit');
        }
        if (isset($_GET['type'])) {
            $this->set('type', $_GET['type']);
        } else {
            $this->set('type', '');
        }
        if (isset($_GET['type']) && $_GET['type'] == 'S') {
            $this->set('type', $_GET['type']);
        }
        if (isset($_GET['type']) && $_GET['type'] == 'D' && $_GET['type_id'] != 0) {
            $this->set('type', $_GET['type']);
            $this->set('view_type_id', $_GET['type_id']);
        } else {
            //if(isset($_GET['type'])&&isset($_GET['type_id'])){
                $this->set('type', isset($_GET['type']) ? $_GET['type'] : '');
            $this->set('view_type_id', isset($_GET['type_id']) ? $_GET['type_id'] : '');
        //	}
        }
        if (isset($_SESSION['type'])) {
            $this->set('view_type', $_SESSION['type']);
        }
        if (isset($_SESSION['type_id'])) {
            $this->set('type_id', $_SESSION['type_id']);
        }
        if (isset($_SESSION['actions'])) {
            $this->set('actions', $_SESSION['actions']);
        }
//		$operator_zhu_actions=$this->Operator->find("first",array("fields"=>array("Operator.actions"),"conditions"=>array("Operator.name"=>$this->admin['name'])));
//		if($operator_zhu_actions=="all"){
//			$this->set('operator_zhu_actions',$operator_zhu_actions);
//		}
        $this->set('title_for_layout', $this->ld['add_edit_operator'].'- '.$this->ld['operators'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operators'],'url' => '/operators/');
        $this->navigations[] = array('name' => $this->ld['add_edit_operator'],'url' => '');
        if ($this->RequestHandler->isPost()) {
            if ($_SESSION['type_id'] == '0' && empty($this->data['Operator']['type_id'])) {
                $this->data['Operator']['type'] = 'S';
                $this->data['Operator']['type_id'] = '0';
            } elseif ($_SESSION['type_id'] != '0' && empty($this->data['Operator']['type_id'])) {
                $this->data['Operator']['type'] = 'D';
                $this->data['Operator']['type_id'] = $_SESSION['type_id'];
            }
    //		 if($svshow->operator_privilege('dealer_list')){
                if (isset($this->data['Operator']['type_id'])) {
                    if ($this->data['Operator']['type_id'] == '0' && $this->data['Operator']['type'] != 'D') {
                        $this->data['Operator']['type'] = 'S';
                        $this->data['Operator']['type_id'] = $this->data['Operator']['type_id'];
                    } else {
                        $this->data['Operator']['type'] = 'D';
                        $this->data['Operator']['type_id'] = $this->data['Operator']['type_id'];
                    }
                }
    //	   }
        $operator_info = $this->Operator->findbyid($this->data['Operator']['id']);
            $name = isset($this->data['Operator']['name']) ? $this->data['Operator']['name'] : $operator_info['Operator']['name'];
            if (empty($this->data['Operator']['id'])) {
                $operator_name_count = $this->Operator->find('count', array('conditions' => array('Operator.name' => $name)));
                if ($operator_name_count == 1) {
                    $result_code = 1;
                /*   echo "<script>alert('用户名重复')</script>";*/
                $this->redirect('/operators/view/0/');
                }
            } else {
                $operator_count = $this->Operator->find('first', array('conditions' => array('Operator.id' => $this->data['Operator']['id'])));
                $operator_name_count = $this->Operator->find('list', array('fields' => array('Operator.id', 'Operator.name')));
                if ($operator_count['Operator']['name'] != $name && in_array($name, $operator_name_count)) {
                    $result_code = 2;
                    $this->redirect('/operators/view/'.$this->data['Operator']['id']);
                }
            }
//		if(!empty($this->params['form']['oldpassword']) && !empty($this->params['form']['newpassword']) && !empty($this->params['form']['confirmpassword'])){
        if (!empty($this->params['form']['newpassword']) && !empty($this->params['form']['confirmpassword'])) {
            //		if(!empty($id)&&strcmp(md5($this->params['form']['oldpassword']),$operator_info['Operator']['password']) != 0){
//			$result_code= 2;
//			$this->redirect('/operators/view/'.$this->data['Operator']['id']);
//		}
            if (!empty($id) && strcmp($this->params['form']['newpassword'], $this->params['form']['confirmpassword']) != 0) {
            } else {
                $this->data['Operator']['password'] = md5($this->params['form']['newpassword']);
            }
        } else {
            $this->data['Operator']['password'] = $operator_info['Operator']['password'];
            if (!empty($this->params['form']['newpassword']) && !empty($this->params['form']['confirmpassword'])) {
                $this->data['Operator']['password'] = md5($this->params['form']['newpassword']);
            }
        }
            if (empty($result_code)) {
                $this->data['Operator']['orderby'] = !empty($this->data['Operator']['orderby']) ? $this->data['Operator']['orderby'] : '50';
            //权限
            if (isset($this->params['form']['operator_role']) && !empty($this->params['form']['operator_role'])) {
                $this->data['Operator']['role_id'] = implode(';', $this->params['form']['operator_role']);
            }
                if (isset($this->params['form']['OperatorAction']) && !empty($this->params['form']['OperatorAction'])) {
                    $this->data['Operator']['actions'] = implode(';', $this->params['form']['OperatorAction']);
                }
                $this->data['Operator']['actions'] = isset($this->data['Operator']['actions']) ? $this->data['Operator']['actions'] : '';
                $this->data['Operator']['role_id'] = isset($this->data['Operator']['role_id']) ? $this->data['Operator']['role_id'] : 0;
                if (isset($this->data['Operator']['id']) && $this->data['Operator']['id'] != '') {
                    $this->Operator->save(array('Operator' => $this->data['Operator'])); //关联保存
                } else {
                    $this->Operator->saveAll(array('Operator' => $this->data['Operator'])); //关联保存
                }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                if ($id == 0) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add'].$this->ld['operator'].':'.$this->data['Operator']['name'], $this->admin['id']);
                } else {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_operator'].':id '.$id.' '.$this->data['Operator']['name'], $this->admin['id']);
                }
            }
                $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
            }
        }
        $operator_data = $this->Operator->find('first', array('conditions' => array('id' => $id)));
        $operator_data['Operator']['action_arr'] = explode(';', $operator_data['Operator']['actions']);
        $this->set('operator_data', $operator_data);

        $this->set('id', $id);
        $this->OperatorAction->set_locale($this->backend_locale);
        $OperatorAction_Data=$this->OperatorAction->tree($this->backend_locale);
        $OperatorActions=array();
        //应用判断//判断应用是否使用
        $all_infos = $this->apps;
        foreach($OperatorAction_Data as $k=>$v){
            if ($v['OperatorAction']['app_code'] != '' && !in_array($v['OperatorAction']['app_code'], $this->apps['codes'])) {
                unset($OperatorAction_Data[$k]);
            }
            $SubAction=isset($v['SubAction'])?$v['SubAction']:array();
            foreach($SubAction as $kk=>$vv){
                if(isset($vv['SubAction'])&&!empty($vv['SubAction'])){
                    $SubAction[$kk]['children']=$vv['SubAction'];
                    unset($SubAction[$kk]['SubAction']);
                }
            }
            if(!empty($SubAction)){
                $OperatorAction_Data[$k]['children']=$SubAction;
                unset($OperatorAction_Data[$k]['SubAction']);
            }
        }
        foreach($OperatorAction_Data as $k=>$v){
        	if(!isset($v['OperatorAction'])||!isset($v['OperatorActionI18n'])){
        		unset($OperatorAction_Data[$k]);
        	}
        }
        $OperatorActions=$OperatorAction_Data;
        $dealer_actions = array();
        $dealer_actions['product']['products_view']['products_mgt'] = true;
        $dealer_actions['order']['所有']['所有'] = true;
        $dealer_actions['system']['operators_view']['所有'] = true;
        $dealer_actions['ware']['所有']['所有'] = true;
        $dealer_actions['dealers']['dealer_view']['所有'] = true;
        $this->set('dealer_actions', $dealer_actions);
        $this->set('OperatorActions', $OperatorActions);
        //角色
        $this->OperatorRole->set_locale($this->locale);
        $res = $this->OperatorRole->find('all');
        $operator_roles = array();
        foreach ($res as $k => $v) {
            $operator_roles[$v['OperatorRole']['id']]['OperatorRole'] = $v['OperatorRole'];
            $operator_roles[$v['OperatorRole']['id']]['OperatorRole']['name'] = '';
            $operator_roles[$v['OperatorRole']['id']]['OperatorRoleI18n'][] = $v['OperatorRoleI18n'];
            if (!empty($operator_roles[$v['OperatorRole']['id']]['OperatorRoleI18n'])) {
                foreach ($operator_roles[$v['OperatorRole']['id']]['OperatorRoleI18n'] as $vv) {
                    $operator_roles[$v['OperatorRole']['id']]['OperatorRole']['name'] = $vv['name'];
                }
            }
        }
        $this->data = $this->Operator->find('first', array('conditions' => array('id' => $id)));
        $this->data['Operator']['role_arr'] = explode(';', $this->data['Operator']['role_id']);
        $this->set('operator_roles', $operator_roles);

        $template_list = $this->Template->find('list', array('conditions' => array('Template.status' => 1), 'fields' => 'Template.name'));
        $this->set('template_list', $template_list);
    }

    //列表状态修改
    public function toggle_on_status()
    {
        $this->Operators->hasMany = array();
        $this->Operators->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Operator->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_operator_failure'];
        $pn = $this->Operator->find('list', array('fields' => array('Operator.id', 'Operator.name'), 'conditions' => array('Operator.id' => $id)));
        $this->Operator->deleteAll(array('id' => $id));
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_operator'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_operator_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function act_view($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $name = $_POST['name'];
        $rname = '';
        $name_code = $this->Operator->find('all', array('fields' => 'Operator.name'));
        if (isset($name_code) && !empty($name_code)) {
            foreach ($name_code as $vv) {
                $rname[] = $vv['Operator']['name'];
            }
        } else {
            $result['code'] = '1';
        }
        if ($id == 0) {
            if (isset($name) && $name != '') {
                if (in_array($name, $rname)) {
                    $result['code'] = '0';
                    //   $result['msg'] = "用户名重复";
                } else {
                    $result['code'] = '1';
                }
            }
        } else {
            $operator_count = $this->Operator->find('first', array('conditions' => array('Operator.id' => $id)));
        //      $operator_name_count=$this->Operator->find("list",array("fields"=>array("Operator.id","Operator.name")));
            if ($operator_count['Operator']['name'] != $name && in_array($name, $rname)) {
                $result['code'] = '0';
                //   $result['msg'] = "用户名重复";
            } else {
                $result['code'] = '1';
            }
        }
        die(json_encode($result));
    }

    public function act_passview($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $user_old_password = $_POST['user_old_password'];
        $operator_count = $this->Operator->find('first', array('conditions' => array('Operator.id' => $id)));
        if (!empty($operator_count['Operator']['password']) && strcmp(md5($user_old_password), $operator_count['Operator']['password']) != 0) {
            $result['code'] = '0';
            // $result['msg'] = "旧密码不正确";
        } else {
            $result['code'] = '1';
        }
        die(json_encode($result));
    }

    public function batch_operations()
    {
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->Operator->deleteAll(array('id' => $v));
        }
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_operator_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function produce_password()
    {
        $password = $_POST['password'];
        if ($password == '1') {
            // 随机生成 8 位数字或字母
            $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
            $code = '';
            for ($i = 0; $i < 8; ++$i) {
                $code .= $pattern{mt_rand(0, 61)};
            }
            $result['code'] = $code;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
