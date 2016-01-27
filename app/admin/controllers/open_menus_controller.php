<?php

/*****************************************************************************
 * Seevia 自定义菜单
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
 *菜单管理.
 *
 *对于OpenMenus这张表的增删改查
 *
 *@author   weizhngye 
 *
 *@version  $Id$
 */
class OpenMenusController extends AppController
{
    /*
    *控制器的名字
    */
    public $name = 'OpenMenus';
    /*
    *引用的助手
    */
    public $helpers = array('Html','Pagination','Tinymce','fck','Form','Javascript','Ckeditor');
    /*
    *引用的组件
    */
    public $components = array('Pagination','RequestHandler','Email');
    /*
    *引用的model
    */
    public $uses = array('OpenMenu','InformationResource','OperatorLog','OpenModel','OpenUserMessage','OpenModel');

    /**
     *OpenMenus主页列表.
     *
     *呈现数据库表OpenMenus的数据
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('open_menus_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_menus/');
        /*end*/
        $this->set('title_for_layout', $this->ld['open_menus'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['open_menus'],'url' => '/open_menus/');
        $conditions = array();
        if (isset($_REQUEST['openType']) && $_REQUEST['openType'] != '') {
            $conditions['and']['OpenMenu.open_type'] = $_REQUEST['openType'];
            $this->set('openType', $_REQUEST['openType']);
        }
        if (isset($_REQUEST['open_type_id']) && $_REQUEST['open_type_id'] != '') {
            $conditions['and']['OpenMenu.open_type_id'] = $_REQUEST['open_type_id'];
            $this->set('open_type_id', $_REQUEST['open_type_id']);
        }
        $cond = array();
        $cond['conditions'] = $conditions;
        $cond['order'] = 'OpenMenu.orderby';
        $menu = $this->OpenMenu->tree($cond);//取所有菜单
        //获取微信公众号类型（订阅号）及认证状态
        $open_type = $this->OpenModel->find('all', array('conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1)));
        $this->set('open_type', $open_type);
        $this->set('menu', $menu);
    }

    /**
     *自定义菜单接口.
     *
     *发送后台设置的自定义菜单到微信
     *
     *@author   zhta
     *
     *@version  $Id$
     */
    public function api_menu_action($open_type_id)
    {
        $this->operator_privilege('open_menus_action');
        Configure::write('debug', 1);
        $cond = array();
        $cond['order'] = 'OpenMenu.orderby';
        $cond['conditions'] = array('OpenMenu.status' => 1);
        $menu = $this->OpenMenu->tree($cond);
        //重组提交接口参数
        foreach ($menu as $k => $v) {
            $api_openmenu = array();
            $api_submenu = array();
            $api_openmenu['name'] = $v['OpenMenu']['name'];
            if (isset($v['SubMenu'])) {
                foreach ($v['SubMenu'] as $kk => $vv) {
                    $api_submenu['type'] = $vv['OpenMenu']['type'];
                    $api_submenu['name'] = $vv['OpenMenu']['name'];
                    if ($api_submenu['type'] == 'click') {
                        $api_submenu['key'] = $vv['OpenMenu']['key'];
                    }
                    if ($api_submenu['type'] == 'view') {
                        $api_submenu['url'] = $vv['OpenMenu']['url'];
                    }
                    $api_openmenu['sub_button'][] = $api_submenu;
                }
            } else {
                $api_openmenu['type'] = $v['OpenMenu']['type'];
                if ($api_openmenu['type'] == 'click') {
                    $api_openmenu['key'] = $v['OpenMenu']['key'];
                }
                if ($api_openmenu['type'] == 'view') {
                    $api_openmenu['url'] = $v['OpenMenu']['url'];
                }
            }
            $api_tree['button'][] = $api_openmenu;
        }
        $api_data = $this->to_josn($api_tree);

        $openModelInfo = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type_id' => $open_type_id, 'OpenModel.status' => 1, 'OpenModel.verify_status' => 1)));

        if (empty($openModelInfo)) {
            echo '<meta charset=utf-8 /><script type="text/javascript">alert("Error!Not Found");window.location.href="/admin/open_menus/"</script>';
            die();
        }
        $openType = $openModelInfo['OpenModel']['open_type'];
        $appId = $openModelInfo['OpenModel']['app_id'];
        $appSecret = $openModelInfo['OpenModel']['app_secret'];
        if (!$this->OpenModel->validateToken($openModelInfo)) {
            //无效重新获取
            $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
            $openModelInfo['OpenModel']['token'] = $accessToken;
            $this->OpenModel->save($openModelInfo);
        }
        $ch = curl_init('https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$openModelInfo['OpenModel']['token']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $api_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json;encoding=utf-8',
            'Content-Length: '.strlen($api_data), )
        );
        $rs_json = curl_exec($ch);
        $rs = json_decode($rs_json, true);
        $msg = isset($rs['errmsg'])?$rs['errmsg']:'Api Error';
        $this->OpenUserMessage->saveMsg('menu/create', $api_data, 0, $openModelInfo['OpenModel']['open_type_id'], 0, $msg, $rs_json);
        echo '<meta charset=utf-8 /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/admin/open_menus/"</script>';
        die();
    }

    /**
     *OpenMenus修改页和添加页.
     *
     *增加和修改数据库表OpenMenus的记录
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function view($id = 0)
    {
        /*判断权限*/
        if ($id == 0) {
            $this->operator_privilege('open_menus_add');
        } else {
            $this->operator_privilege('open_menus_edit');
        }
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_menus/');
        $this->set('title_for_layout', $this->ld['add_edit_page'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['open_menus'],'url' => '/open_menus/');
        $this->navigations[] = array('name' => $this->ld['add_edit_page'],'url' => '/open_menus/view/'.$id);
        if ($this->RequestHandler->isPost()) {
            if ($this->data['OpenMenu']['type'] == 'view') {
                $this->data['OpenMenu']['key'] = '';
            } else {
                $this->data['OpenMenu']['url'] = '';
            }
            $this->OpenMenu->save($this->data);
            /*操作员日志*/
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['modify'].$this->ld['open_menus'].'id:'.$id, $this->admin['id']);
            }

            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        //所有的一级菜单
        $parentmenu = $this->OpenMenu->find('all', array('conditions' => array('OpenMenu.parent_id' => '0', 'OpenMenu.id !=' => $id)));
        $parent_count = count($parentmenu);
        $this->set('parent_count', $parent_count);
        foreach ($parentmenu as $k => $v) {
            $sub_count = $this->OpenMenu->find('count', array('conditions' => array('OpenMenu.parent_id' => $v['OpenMenu']['id'])));
            if ($sub_count > 5) {
                unset($parentmenu[$k]);
            }
        }
        $this->set('parentmenu', $parentmenu);
        $this->data = $this->OpenMenu->find('first', array('conditions' => array('OpenMenu.id' => $id)));
        $this->set('id', $id);
    }

    /**
     *OpenMenus删除的方法.
     *
     *删除OpenMenus的记录
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('open_menus_remove');
        /*end*/
        $system_info = $this->OpenMenu->findById($id);
        $res = $this->OpenMenu->find('count', array('conditions' => array('OpenMenu.parent_id' => $id)));
        $result = array();
        if ($res > 0) {
            $result['flag'] = 2;
        } else {
            $this->OpenMenu->delete(array('OpenMenu.id' => $id));
                /*
                *操作员日志
                *
                *记录删除的情况
                *
                *@author   weizhengye 
                */
                if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除页面 样式 菜单', $this->admin['id']);
                }
            $result['flag'] = 1;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //列表箭头排序
    public function changeorder($updowm, $id, $nextone)
    {
        //如果值相等重新自动排序
        $a = $this->OpenMenu->query('SELECT DISTINCT `parent_id` 
			FROM `svsns_open_menus` as OpenMenu
			GROUP BY `orderby` , `parent_id` 
			HAVING count( * ) >1');
        foreach ($a as $v) {
            $this->OpenMenu->Behaviors->attach('Containable');
            $all = $this->OpenMenu->find('all', array('conditions' => array('OpenMenu.parent_id' => $v['OpenMenu']['parent_id']), 'order' => 'OpenMenu.id asc', 'contain' => false));
            foreach ($all as $k => $vv) {
                $all[$k]['OpenMenu']['orderby'] = $k + 1;
            }
            $this->OpenMenu->saveAll($all);
        }
        if ($nextone == 0) {
            $menu_one = $this->OpenMenu->find('first', array('conditions' => array('OpenMenu.id' => $id)));
            if ($updowm == 'up') {
                $menu_change = $this->OpenMenu->find('first', array('conditions' => array('OpenMenu.orderby <' => $menu_one['OpenMenu']['orderby'], 'OpenMenu.parent_id' => 0), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $menu_change = $this->OpenMenu->find('first', array('conditions' => array('OpenMenu.orderby >' => $menu_one['OpenMenu']['orderby'], 'OpenMenu.parent_id' => 0), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        if ($nextone == 'next') {
            $menu_one = $this->OpenMenu->find('first', array('conditions' => array('OpenMenu.id' => $id)));
            if ($updowm == 'up') {
                $menu_change = $this->OpenMenu->find('first', array('conditions' => array('OpenMenu.orderby <' => $menu_one['OpenMenu']['orderby'], 'OpenMenu.parent_id' => $menu_one['OpenMenu']['parent_id']), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $menu_change = $this->OpenMenu->find('first', array('conditions' => array('OpenMenu.orderby >' => $menu_one['OpenMenu']['orderby'], 'OpenMenu.parent_id' => $menu_one['OpenMenu']['parent_id']), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        $t = $menu_one['OpenMenu']['orderby'];
        $menu_one['OpenMenu']['orderby'] = $menu_change['OpenMenu']['orderby'];
        $menu_change['OpenMenu']['orderby'] = $t;
        if ($menu_change['OpenMenu']['type'] != '') {
            $this->OpenMenu->saveAll($menu_one);
            $this->OpenMenu->saveAll($menu_change);
            $arr_ = $this->OpenMenu->find('first', array('conditions' => array('OpenMenu.id' => $menu_change['OpenMenu']['id'])));
        }
        $conditions = array();
        $cond = array();
        $cond['conditions'] = $conditions;
        $cond['order'] = 'OpenMenu.orderby';
        $menu = $this->OpenMenu->tree($cond);//取所有菜单
        $this->set('menu', $menu);
        Configure::write('debug', 1);
        $this->render('index');
        $this->layout = 'ajax';
    }

    public function toggle_on_status()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        //判断权限
        if (!$this->operator_privilege('open_menus_edit', false)) {
            die(json_encode(array('flag' => 2, 'content' => $this->ld['have_no_operation_perform'])));
        }
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $open_menu_data['id'] = $id;
        $open_menu_data['status'] = $val;
        $open_menu_info = $this->OpenMenu->find('first', array('fields' => array('OpenMenu.id', 'OpenMenu.name'), 'conditions' => array('OpenMenu.id' => $id)));
        $result = array();
        if (!empty($open_menu_info) && is_numeric($val) && $this->OpenMenu->save(array('OpenMenu' => $open_menu_data))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑自定义['.$open_menu_info['OpenMenu']['name'].']状态：'.$val.'.'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
    /*
        $data   需要转换josn提交的数据
    */
    public function to_josn($data)
    {
        $this->arrayRecursive($data, 'urlencode');
        $json = json_encode($data);

        return urldecode($json);
    }

    /************************************************************** 
    * 对数组中所有元素做处理,保留中文 
    * @param string &$array 要处理的数组
    * @param string $function 要执行的函数 
    * @return boolean $apply_to_keys_also 是否也应用到key上 
    * @access public 
    * 
    *************************************************************/
    public function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        --$recursive_counter;
    }
}
