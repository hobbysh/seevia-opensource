<?php

/*****************************************************************************
 * Seevia 模块页面、样式管理
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
 *模块页面、样式管理.
 *
 *对于PageAction、PageModule的增删改查
 *
 *@author   zhaoyincheng 
 *
 *@version  $Id$
 */
class PageActionsController extends AppController
{
    /*
    *控制器的名字
    */
    public $name = 'PageActions';
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
    public $uses = array('PageModule','PageModuleI18n','Resource','SeoKeyword','InformationResource','Template','Template','PageAction','PageType','OperatorLog');
    /*
    *预定义变量：模块位置
    */
    public $module_position = array('top' => '顶部','left' => '左边','right' => '右边');

    /**
     *pageaction主页.
     *
     *不做任何处理
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function index()
    {
        $this->redirect('/page_types/');
    }
    /**
     *pageaction列表页.
     *
     *呈现数据库表PageAction的数据
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function view($id, $page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('page_types_edit');
        if (!isset($id) || !is_numeric($id)) {
            $this->redirect('/page_types/');
        } else {
            $this->redirect('/page_types/'.$id);
        }
        $this->set('id', $id);
        $pagetype_info = $this->PageType->find('first', array('conditions' => array('id' => $id)));
        if (empty($pagetype_info)) {
            $this->redirect('/page_types/');
        }
        $this->menu_path = array('root' => '/system/','sub' => '/page_types/');
        $this->set('title_for_layout', $pagetype_info['PageType']['name'].'模块 - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['module_management'],'url' => '/page_types/');
        $this->navigations[] = array('name' => $pagetype_info['PageType']['name'].'模块','url' => '/page_actions/'.$id);

        $conditions['PageAction.page_type_id'] = $id;
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $conditions['and']['or']['PageAction.controller like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['PageAction.action like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['PageAction.name like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
        if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {
            $conditions['and']['PageAction.status'] = $_REQUEST['status'];
            $this->set('status', $_REQUEST['status']);
        }
        $cond['conditions'] = $conditions;
        //分页
        $total = $this->PageAction->find('count', $cond);//获取总记录数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];//当前页
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'PageAction','action' => 'view','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'PageAction');
        $this->Pagination->init($conditions, $parameters, $options);
        $cond['limit'] = $rownum;
        $cond['page'] = $page;
        $cond['order'] = 'id';
        $pageaction_list = $this->PageAction->find('all', $cond);
        $this->set('pageaction_list', $pageaction_list);
        $this->set('pagetype_info', $pagetype_info);
    }

    /**
     *pageaction的增加、编辑页、pagemodule的列表显示页.
     *
     *对pageaction表的数据添加、编辑及显示当前某块的样式列表
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function page_action_view($id = 0)
    {
        /*判断权限*/
        $this->operator_privilege('page_types_edit');
        if (!is_numeric($id)) {
            $this->redirect('/page_types/');
        }
        if ($this->RequestHandler->isPost()) {
            if ($id != 0) {
                $this->data['PageAction']['id'] = $id;
            }
            $this->PageAction->save($this->data['PageAction']);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'修改模块信息:'.$id, $this->admin['id']);
            }
            if (isset($this->data['PageAction']['page_type_id']) && $this->data['PageAction']['page_type_id'] != '0') {
                $pagetype_info = $this->PageType->find('first', array('conditions' => array('id' => $this->data['PageAction']['page_type_id'])));
                $this->redirect('/page_types/view/'.$this->data['PageAction']['page_type_id'].'/'.$pagetype_info['PageType']['code']);
            } else {
                $this->redirect('/page_actions/'.$this->data['PageAction']['page_type_id']);
            }
        }
        $type_id = isset($_GET['type_id']) ? $_GET['type_id'] : '0';
        $pagetype_info = $this->PageType->find('first', array('conditions' => array('id' => $type_id)));
        if (empty($pagetype_info)) {
            $id = 0;
        } else {
            $template_Info = $this->Template->find('first', array('conditions' => array('Template.name' => $pagetype_info['PageType']['code'])));
        }
        $this->set('id', $id);
        $this->set('type_id', $type_id);
        $this->set('pagetype_info', $pagetype_info);
//		$this->menu_path = array('root'=>'/system/','sub'=>'/page_types/');
//		$this->navigations[] = array('name'=>$this->ld['manage_system'],'url'=>'');
//		$this->navigations[] = array('name'=>$this->ld['module_management'],'url'=>'/page_types/');
//		$this->navigations[] = array('name'=>$this->ld['module']." - ".$this->ld['add_edit_page'],'url'=>'/page_types/'.$type_id);

        $this->menu_path = array('root' => '/system/','sub' => '/themes/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manager_templates'],'url' => '/themes/');
        if (!empty($template_Info)) {
            $this->navigations[] = array('name' => $this->ld['template'].' - '.$template_Info['Template']['description'],'url' => '/themes/view/'.$template_Info['Template']['id']);
            $this->navigations[] = array('name' => $this->ld['module'].' - '.$this->ld['add_edit_page'],'url' => '/page_types/view/'.$type_id.'/'.$pagetype_info['PageType']['code']);
        }
        if ($id == 0) {
            $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
            $this->set('title_for_layout', $this->ld['page'].$this->ld['add'].' - '.$this->configs['shop_name']);
        } else {
            $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
            $this->set('title_for_layout', $this->ld['page'].$this->ld['edit'].' - '.$this->configs['shop_name']);
        }
        $this->set('pagetype_list', $this->PageType->find('all'));
        if ($id != 0) {
            $page_action_info = $this->PageAction->find('first', array('conditions' => array('id' => $id)));
            $this->set('page_action_info', $page_action_info);
            $conditions['page_action_id'] = $id;
            $cond['conditions'] = $conditions;
            $pagemodule_list = $this->PageModule->tree($this->locale, $conditions);
            $this->set('pagemodule_list', $pagemodule_list);
        }
    }

    /**
     *pagemodule列表箭头排序.
     *
     *pagemodule列表箭头排序
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function changeorder($updowm, $id, $nextone, $action_id)
    {
        //如果值相等重新自动排序
        $a = $this->PageModule->query('SELECT DISTINCT `parent_id`
			FROM `svsys_page_modules` as PageModule
			WHERE `page_action_id` = "'.$action_id.'"
			GROUP BY `orderby` , `parent_id`
			HAVING count( * ) >1');
        foreach ($a as $v) {
            $this->PageModule->Behaviors->attach('Containable');
            $all = $this->PageModule->find('all', array('conditions' => array('PageModule.parent_id' => $v['PageModule']['parent_id']), 'order' => 'PageModule.id asc', 'contain' => false));
            foreach ($all as $k => $vv) {
                $all[$k]['PageModule']['orderby'] = $k + 1;
            }
            $this->PageModule->saveAll($all);
        }
        if ($nextone == 0) {
            $module_one = $this->PageModule->find('first', array('conditions' => array('PageModule.id' => $id)));
            if ($updowm == 'up') {
                $module_change = $this->PageModule->find('first', array('conditions' => array('PageModule.orderby <' => $module_one['PageModule']['orderby'], 'PageModule.page_action_id' => $module_one['PageModule']['page_action_id'], 'PageModule.parent_id' => 0), 'order' => 'orderby desc'));
            }
            if ($updowm == 'down') {
                $module_change = $this->PageModule->find('first', array('conditions' => array('PageModule.orderby >' => $module_one['PageModule']['orderby'], 'PageModule.page_action_id' => $module_one['PageModule']['page_action_id'], 'PageModule.parent_id' => 0), 'order' => 'orderby asc'));
            }
        }
        if ($nextone == 'next') {
            $module_one = $this->PageModule->find('first', array('conditions' => array('PageModule.id' => $id)));
            if ($updowm == 'up') {
                $module_change = $this->PageModule->find('first', array('conditions' => array('PageModule.orderby <' => $module_one['PageModule']['orderby'], 'PageModule.page_action_id' => $module_one['PageModule']['page_action_id'], 'PageModule.parent_id' => $module_one['PageModule']['parent_id']), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $module_change = $this->PageModule->find('first', array('conditions' => array('PageModule.orderby >' => $module_one['PageModule']['orderby'], 'PageModule.page_action_id' => $module_one['PageModule']['page_action_id'], 'PageModule.parent_id' => $module_one['PageModule']['parent_id']), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        $t = $module_one['PageModule']['orderby'];
        $module_one['PageModule']['orderby'] = $module_change['PageModule']['orderby'];
        $module_change['PageModule']['orderby'] = $t;
        if (isset($module_change['PageModule']['status']) && $module_change['PageModule']['page_action_id'] != '') {
            $this->PageModule->saveAll($module_one);
            $this->PageModule->saveAll($module_change);
        }
        $conditions['PageModule.page_action_id'] = $action_id;
        $pagemodule_list = $this->PageModule->tree($this->locale, $conditions);
        $this->set('pagemodule_list', $pagemodule_list);
        $this->set('id', $action_id);
        Configure::write('debug', 1);
        $this->render('ajax_module_list');
        $this->layout = 'ajax';
    }

    /**
     *pagemodule的增加、编辑页.
     *
     *对pagemodule表的数据添加、编辑
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function page_module_view($id = 0)
    {
        /*判断权限*/
        $this->operator_privilege('page_types_edit');
        if (!is_numeric($id)) {
            $id = 0;
        }
//		$this->menu_path = array('root'=>'/system/','sub'=>'/page_types/');
//		$this->navigations[] = array('name'=>$this->ld['manage_system'],'url'=>'');
//		$this->navigations[] = array('name'=>$this->ld['module_management'],'url'=>'/page_types/');

        $this->menu_path = array('root' => '/system/','sub' => '/themes/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manager_templates'],'url' => '/themes/');

        if ($id != 0) {
            $this->data = $this->PageModule->localeformat($id);
            $this->set('modules_info', $this->data);
            if (empty($this->data['PageModule'])) {
                $this->redirect('/page_types/');
            }
            $type_id = isset($this->data['PageModule']['page_action_id']) ? $this->data['PageModule']['page_action_id'] : '';
            $pageactionInfo = $this->PageAction->find('first', array('conditions' => array('PageAction.id' => $type_id)));
            $pagetypeInfo = $this->PageType->find('first', array('conditions' => array('PageType.id' => $pageactionInfo['PageAction']['page_type_id'])));
            $this->navigations[] = array('name' => $this->ld['module'].' - '.$this->ld['add_edit_page'],'url' => '/page_types/'.$type_id);
            $this->navigations[] = array('name' => $this->ld['module'].$this->ld['page'].$this->ld['edit'],'url' => '/page_actions/page_action_view/'.$type_id.'?type_id='.$pagetypeInfo['PageType']['id']);
            $this->navigations[] = array('name' => $this->ld['page'].$this->ld['style'].$this->ld['edit'],'url' => '');
            $this->set('title_for_layout', $this->ld['page'].$this->ld['style'].$this->ld['edit'].' - '.$this->configs['shop_name']);
        } else {
            $type_id = isset($_GET['action_id']) ? $_GET['action_id'] : '';
            $pageactionInfo = $this->PageAction->find('first', array('conditions' => array('PageAction.id' => $type_id)));
            $pagetypeInfo = $this->PageType->find('first', array('conditions' => array('PageType.id' => $pageactionInfo['PageAction']['page_type_id'])));
            $this->navigations[] = array('name' => $this->ld['module'].' - '.$this->ld['add_edit_page'],'url' => '/page_types/'.$type_id);
            $this->navigations[] = array('name' => $this->ld['module'].$this->ld['page'].$this->ld['edit'],'url' => '/page_actions/page_action_view/'.$type_id.'?type_id='.$pagetypeInfo['PageType']['id']);
            $this->navigations[] = array('name' => $this->ld['page'].$this->ld['style'].$this->ld['add'],'url' => '');
            $this->set('title_for_layout', $this->ld['page'].$this->ld['style'].$this->ld['add'].' - '.$this->configs['shop_name']);
        }
        $this->set('page_action_id', $type_id);
        if ($this->RequestHandler->isPost()) {
            $_REQUEST['data']['PageModule']['orderby'] = !empty($_REQUEST['data']['PageModule']['orderby']) ? $_REQUEST['data']['PageModule']['orderby'] : 50;
            $_REQUEST['data']['PageModule']['limit'] = ($_REQUEST['data']['PageModule']['limit'] == '') ? '10' : $_REQUEST['data']['PageModule']['limit'];
            $this->PageModule->save($_REQUEST['data']); //保存
            $id = $this->PageModule->getLastInsertId();
            $this->PageModuleI18n->deleteAll(array('module_id' => $id)); //删除原有多语言
            foreach ($_REQUEST['data']['PageModuleI18n'] as $v) {
                $moduleI18n_info = array(
                                   'id' => isset($v['id']) ? $v['id'] : '',
                                   'locale' => $v['locale'],
                                   'module_id' => !empty($v['module_id']) ? $v['module_id'] : $id,
                                   'name' => isset($v['name']) ? $v['name'] : '',
                                   'title' => isset($v['title']) ? $v['title'] : '',
                             );
                $this->PageModuleI18n->save(array('PageModuleI18n' => $moduleI18n_info));//更新多语言
            }
            foreach ($_REQUEST['data']['PageModuleI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            /*
            *操作员日志
            *
            *记录删除的情况
            *
            *@author   zhaoyincheng 
            *@version  $Id$ 
            */
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['shop_edit_shop'].':'.$userinformation_name, $this->admin['id']);
            }
            $pageactionInfo = $this->PageAction->find('first', array('conditions' => array('id' => $_REQUEST['data']['PageModule']['page_action_id'])));
            //$pagetype_info=$this->PageType->find('first',array('conditions'=>array('id'=>$pageactionInfo['PageAction']['page_type_id'])));
            $this->redirect('/page_actions/page_action_view/'.$pageactionInfo['PageAction']['id'].'?type_id='.$pageactionInfo['PageAction']['page_type_id']);

            //$this->redirect('/page_actions/page_action_view/'.$_REQUEST['data']['PageModule']['page_action_id'].'?type_id='.$pageactionInfo['PageAction']['page_type_id']);
        }
        $modules_tree = $this->PageModule->parent_tree($this->locale, null);
        $this->set('modules_tree', $modules_tree);
        $this->Resource->set_locale($this->locale);
        $module_parent_id = $this->Resource->find('first', array('conditions' => array('Resource.code' => 'module_type', 'status' => '1')));
        if (!empty($module_parent_id)) {
            $module_types = $this->Resource->find('all', array('conditions' => array('Resource.parent_id' => $module_parent_id['Resource']['id'], 'status' => '1'), 'order' => 'Resource.id'));
        } else {
            $module_types = array();
        }
        $this->set('module_types', $module_types);
        $this->set('module_position', $this->module_position);
        $page_action_list = $this->PageAction->find('all', array('conditions' => array('status' => '1'), 'order' => 'PageAction.id'));
        $this->set('page_action_list', $page_action_list);
    }

    /**
     *pageaction的删除.
     *
     *对pageaction表的数据删除操作
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if (!$this->operator_privilege('page_types_reomve', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $this->PageAction->delete($id);
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_the_ad_list_success'];
        die(json_encode($result));
    }

    /**
     *pageaction的批量删除.
     *
     *对pageaction表的数据批量删除操作
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function removeAll($id)
    {
        $page_action_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        if ($page_action_ids != 0) {
            foreach ($page_action_ids as $k => $v) {
                $this->PageAction->deleteAll(array('id' => $v));
            }
        }
        $this->redirect('/page_actions/'.$id);
    }

    /**
     *pageaction的状态ajax修改.
     *
     *对pageaction表的某条数据的状态进行ajax修改
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function toggle_on_page_status()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        /*判断权限*/
        if (!$this->operator_privilege('action_view', false)) {
            die(json_encode(array('flag' => 2, 'content' => $this->ld['have_no_operation_perform'])));
        }
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->PageAction->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            /*
            *操作员日志
            *
            *记录删除的情况
            *
            *@author   zhaoyincheng 
            *@version  $Id$ 
            */
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        die(json_encode($result));
    }

    /**
     *pagemodule的删除.
     *
     *对pagemodule表的删除操作
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function module_remove($id, $type_id)
    {
        /*判断权限*/
        $this->operator_privilege('page_types_reomve');
        $this->PageModuleI18n->delete(array('module_id' => $id));
        $this->PageModule->delete($id);
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_the_ad_list_success'];

        $pageactionInfo = $this->PageAction->find('first', array('conditions' => array('id' => $type_id)));
        $this->redirect('/page_actions/page_action_view/'.$type_id.'?type_id='.$pageactionInfo['PageAction']['page_type_id']);
    }

    /**
     *pagemodule的ajax修改状态.
     *
     *对pagemodule表的某条数据进行状态修改
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function toggle_on_status()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->PageModule->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            /*
            *操作员日志
            *
            *记录删除的情况
            *
            *@author   zhaoyincheng 
            *@version  $Id$ 
            */
                if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
                }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *pagemodule的code字段验证.
     *
     *对pagemodule表code字段进行重复验证
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function check_code($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $code = $_POST['code'];
        $rname = '';
        $name_code = $this->PageModule->find('all', array('fields' => 'PageModule.code'));
        if (isset($name_code) && !empty($name_code)) {
            foreach ($name_code as $vv) {
                $rname[] = $vv['PageModule']['code'];
            }
        } else {
            $result['code'] = '1';
        }
        if ($id == 0) {
            if (isset($code) && $code != '') {
                if (in_array($code, $rname)) {
                    $result['code'] = '0';
                } else {
                    $result['code'] = '1';
                }
            }
        } else {
            $PageModule_count = $this->PageModule->find('first', array('conditions' => array('PageModule.id' => $id)));
            if ($PageModule_count['PageModule']['code'] != $code && in_array($code, $rname)) {
                $result['code'] = '0';
            } else {
                $result['code'] = '1';
            }
        }
        die(json_encode($result));
    }

    /**
     *根据module_type获取Model和function名称.
     *
     *根据module_type获取Model和function名称
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function set_modelorfunction()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $res_val = $_POST['res_val'];
        $resInfo = $this->Resource->find('first', array('conditions' => array('Resource.resource_value' => $res_val)));
        if (isset($resInfo) && !empty($resInfo)) {
            $result['status'] = '1';
            $val = $resInfo['ResourceI18n']['description'];
            if ($val != '') {
                $val_arr = explode(';', $val);
                $val_model_arr = explode(':', $val_arr[0]);
                $val_function_arr = explode(':', $val_arr[1]);
                $result['model'] = $val_model_arr[1];
                $result['function'] = $val_function_arr[1];
            } else {
                $result['status'] = '0';
                $result['model'] = $val_model_arr[1];
                $result['function'] = $val_function_arr[1];
            }
        } else {
            $result['status'] = '0';
            $result['model'] = $val_model_arr[1];
            $result['function'] = $val_function_arr[1];
        }
        die(json_encode($result));
    }
}
