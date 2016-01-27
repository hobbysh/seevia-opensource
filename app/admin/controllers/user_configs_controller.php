<?php

/*****************************************************************************
 * Seevia 用户设置管理
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
class UserConfigsController extends AppController
{
    public $name = 'UserConfigs';
    public $components = array('Pagination','RequestHandler','Phpexcel','Orderfrom');
    public $helpers = array('Pagination');
    public $uses = array('UserConfig','UserConfigI18n','ConfigI18n','Resource','User','Application','OperatorLog','UserAction','Operator');
    public $user_configs_type = array();

    public function index($page = 1)
    {
        //$this->operator_privilege('user_configs_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
        $this->navigations[] = array('name' => $this->ld['user_config_management'],'url' => '/user_configs');

        //用户配置类型
        $Resource_info = $this->Resource->getformatcode(array('user_config_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);

        $this->UserConfig->set_locale($this->backend_locale);

        $condition['UserConfig.user_id'] = 0;

        //会员搜索筛选条件
        if (isset($_REQUEST['user_config_type']) && ($_REQUEST['user_config_type'] != ''&&$_REQUEST['user_config_type'] != '0')) {
            $condition['UserConfig.type'] = $_REQUEST['user_config_type'];
            $this->set('user_config_type', $_REQUEST['user_config_type']);
        }
        if (isset($_REQUEST['user_config_group_code']) && ($_REQUEST['user_config_group_code'] != ''&&$_REQUEST['user_config_group_code'] != '0')) {
            $condition['UserConfig.group_code'] = $_REQUEST['user_config_group_code'];
            $this->set('user_config_group_code', $_REQUEST['user_config_group_code']);
        }
        if (isset($_REQUEST['user_config_keyword']) && $_REQUEST['user_config_keyword'] != '') {
            $condition['or']['UserConfig.code like'] = '%'.$_REQUEST['user_config_keyword'].'%';
            $condition['or']['UserConfigI18n.name like'] = '%'.$_REQUEST['user_config_keyword'].'%';
            $condition['or']['UserConfigI18n.description like'] = '%'.$_REQUEST['user_config_keyword'].'%';
            $this->set('user_config_keyword', $_REQUEST['user_config_keyword']);
        }
        $total = $this->UserConfig->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);

        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'user_configs','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserConfig');
        $this->Pagination->init($condition, $parameters, $options);
        $users_config_list = $this->UserConfig->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'UserConfig.created desc'));
        $this->set('users_config_list', $users_config_list);

        $this->set('title_for_layout', $this->ld['user_config_management'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        
        $Resource_group_list=array();
        $users_config_group_list=array();
        if(!empty($users_config_list)){
            foreach($users_config_list as $v){
                if(empty($v['UserConfig']['group_code']))continue;
                if(trim($v['UserConfig']['group_code'])=='')continue;
                $users_config_group_list[$v['UserConfig']['group_code']]=$v['UserConfig']['group_code'];
            }
            if(!empty($users_config_group_list)){
                $this->Resource->set_locale($this->backend_locale);
                $Resource_group_code=$this->Resource->find('all',array('fields'=>array('Resource.resource_value','ResourceI18n.name'),'conditions'=>array('Resource.resource_value'=>$users_config_group_list)));
                foreach($Resource_group_code as $v){
                    $Resource_group_list[$v['Resource']['resource_value']]=$v['ResourceI18n']['name'];
                }
            }
        }
        $this->set('Resource_group_list', $Resource_group_list);
        
        $Resource_info = $this->Resource->getformatcode(array('user_config_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
    }

    //新增/编辑页
    public function view($id = 0)
    {
        //设置导航路径,名称
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->set('title_for_layout', $this->ld['user_config_management'].' - '.$this->configs['shop_name']);
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
        $this->navigations[] = array('name' => $this->ld['user_config_management'],'url' => '/user_configs/');
        $this->navigations[] = array('name' => $this->ld['add_edit'],'url' => '');
        if ($this->RequestHandler->isPost()) {
            //主表
            $this->UserConfig->save($this->data);

            $user_config_id = $this->UserConfig->id;
            //多语言表
            $this->UserConfigI18n->deleteall(array('user_config_id' => $user_config_id)); //删除原有多语言
            foreach ($this->data['UserConfigI18n'] as $v) {
                $userconfigi18n_info = array(
                      'locale' => $v['locale'],
                      'user_config_id' => $user_config_id,
                       'name' => isset($v['name']) ? $v['name'] : '',
                      'description' => $v['description'],
                    'user_config_values' => $v['user_config_values'],
                  );
                $this->UserConfigI18n->saveAll(array('UserConfigI18n' => $userconfigi18n_info));//更新多语言
            }
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑:id '.$id, $this->admin['id']);
            }
            $this->redirect('/user_configs/');
        }
        $this->data = $this->UserConfig->localeformat($id);
        $this->set('user_configs', $this->data);
        $this->set('user_configs_type_lists', $this->user_configs_type);

        $Resource_info = $this->Resource->getformatcode(array('user_config_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
    }

    //删除
    public function remove($id = 0)
    {
        //$this->operator_privilege('user_configs_delete');
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->UserConfig->deleteAll(array('UserConfig.id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除:id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        die(json_encode($result));
    }

    //批量删除
    public function removeall()
    {
        //$this->operator_privilege('user_configs_delete');
        Configure::write('debug', 1);
        $this->layout = 'ajax';

        $checkboxes_str = $_REQUEST['checkboxes'];

        $Ids = '';
        foreach ($checkboxes_str as $k => $v) {
            $Ids = $Ids.$v.',';
            $this->UserConfig->deleteAll(array('UserConfig.id' => $v));
        }
        if ($Ids != '') {
            $Ids = substr($Ids, 0, strlen($Ids) - 1);
            //
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].'删除:'.$Ids, $this->admin['id']);
            }
        }
        $result['flag'] = 1;
        Configure::write('debug', 0);
        die(json_encode($result));
    }
    
    function user_configs_group(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $user_config_type=isset($_POST['user_config_type'])?$_POST['user_config_type']:'';
        $result=array();
        $result['flag'] = 0;
        $group_data=array();
        if(!empty($user_config_type)){
            $user_config_type="user_config_".$user_config_type;
            $Resource_info = $this->Resource->getformatcode(array($user_config_type), $this->backend_locale);
            if(!empty($Resource_info[$user_config_type])){
                $result['flag'] = 1;
                $group_data=$Resource_info[$user_config_type];
            }
        }
        $result['group_data'] = $group_data;
        die(json_encode($result));
    }
}
