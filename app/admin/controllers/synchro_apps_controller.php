<?php

/*****************************************************************************
 * Seevia 用户同步管理
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
class SynchroAppsController extends AppController
{
    public $name = 'SynchroApps';
    public $components = array('Pagination','RequestHandler','Email');
    public $helpers = array('Html','Pagination');
    public $uses = array('UserApp','OperatorLog','SynchroUser');
    public $user_apps = array('QQ' => 'QQ','SinaWeibo' => 'SinaWeibo','QQWeibo' => 'QQWeibo','Facebook' => 'Facebook','Google' => 'Google','Wechat' => 'Wechat');
    public $user_apps_type = array();

    public function index($page = 1)
    {
        $this->fun_outh();
        $this->operator_privilege('synchro_apps_view');
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->set('title_for_layout', $this->ld['user_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['user_management'],'url' => '');

        $condition = array();

        $total = $this->UserApp->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'UserChat';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'synchro_apps','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserApp');
        $this->Pagination->init($condition, $parameters, $options);
        $data = $this->UserApp->find('all', array('fields' => array('UserApp.id', 'UserApp.name', 'UserApp.type', 'UserApp.created', 'UserApp.status'), 'conditions' => $condition, 'order' => 'UserApp.status desc,UserApp.id'));
        $this->set('data', $data);
    }

    public function view($id = 0)
    {
        $this->fun_outh();
        if (empty($id)) {
            $this->operator_privilege('synchro_apps_add');
        } else {
            $this->operator_privilege('synchro_apps_edit');
        }
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->set('title_for_layout', $this->ld['user_synchronous_interface'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['user_management'],'url' => '/synchro_apps/');
        $this->navigations[] = array('name' => $this->ld['user_synchronous_interface'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            if (isset($this->data['UserApp']['status'])) {
                $this->UserApp->updateAll(array('UserApp.status' => 0), array('UserApp.type' => $this->data['UserApp']['type']));
            }
            $this->data['UserApp']['status'] = isset($this->data['UserApp']['status']) ? $this->data['UserApp']['status'] : '0';
            $this->UserApp->save($this->data);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'接口添加/编辑:id '.$id, $this->admin['id']);
            }
            $this->redirect('/synchro_apps/');
        }

        if ($id > 0) {
            $syn_apps = $this->UserApp->find('first', array('conditions' => array('UserApp.id' => $id)));
            $this->set('syn_apps', $syn_apps);
        }
        $this->set('user_apps_type_lists', $this->user_apps_type);
    }

    public function remove($id = 0)
    {
        $this->operator_privilege('synchro_apps_delete');
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->fun_outh();
        $this->UserApp->deleteAll(array('UserApp.id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除接口:id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        die(json_encode($result));
    }

    public function removeall()
    {
        $this->operator_privilege('synchro_apps_delete');
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->fun_outh();

        $checkboxes_str = $_REQUEST['checkboxes'];
        $Ids = '';
        foreach ($checkboxes_str as $k => $v) {
            $Ids = $Ids.$v.',';
            $this->UserApp->deleteAll(array('UserApp.id' => $v));
        }
        if ($Ids != '') {
            $Ids = substr($Ids, 0, strlen($Ids) - 1);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].'删除接口:'.$Ids, $this->admin['id']);
            }
        }
        $result['message'] = $this->ld['deleted_success'];
        die(json_encode($result));
    }

    protected function fun_outh()
    {
        $this->user_apps_type['SinaWeibo'] = $this->ld['sina'];
        $this->user_apps_type['QQWeibo'] = $this->ld['QQWeibo'];
        $this->user_apps_type['Facebook'] = 'Facebook';
        $this->user_apps_type['Google'] = 'Google';
        $this->user_apps_type['Wechat'] = $this->ld['wechat'];
        $this->user_apps_type['QQ'] = $this->ld['qq'];
    }

    /*
        用户取消授权
    */
    public function remove_userbind($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['code'] = 0;
        $result['message'] = $this->ld['delete_failure'];
        if ($this->SynchroUser->deleteAll(array('id' => $id))) {
            $result['code'] = 1;
            $result['message'] = $this->ld['deleted_success'];
        }
        die(json_encode($result));
    }
}
