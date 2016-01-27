<?php

/*****************************************************************************
 * Seevia 模块管理
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
 *模块类型管理.
 *
 *对于PageType这张表的增删改查
 *
 *@author   weizhngye 
 *
 *@version  $Id$
 */
class PageTypesController extends AppController
{
    /*
    *控制器的名字
    */
    public $name = 'PageTypes';
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

    /**
     *pagetype主页列表.
     *
     *呈现数据库表PageType的数据
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function index()
    {
        /*判断权限*/
        $this->operator_privilege('page_types_view');
        $this->menu_path = array('root' => '/system/','sub' => '/page_types/');
        /*end*/
        $this->set('title_for_layout', $this->ld['module_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['module_management'],'url' => '/page_types/');
        $conditions = array();
        if (isset($_REQUEST['type']) && $_REQUEST['type'] != '') {
            $conditions['and']['or']['PageType.page_type like'] = '%'.$_REQUEST['type'].'%';
            $this->set('type1', $_REQUEST['type']);
        }
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $conditions['and']['or']['PageType.name like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
        if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {
            $conditions['and']['PageType.status'] = $_REQUEST['status'];
            $this->set('status', $_REQUEST['status']);
        }
        $page_list = $this->PageType->find('all', array('conditions' => $conditions));
        $this->set('page_list', $page_list);
    }

    /**
     *pagetype修改页和添加页.
     *
     *增加和修改数据库表PageType的记录（如果更新和添加的状态是有效的话，让其他未修改的pagetype的状态改成失效）
     *
     *@author   weizhngye 
     *
     *@version  $Id$
     */
    public function view($id = 0, $template_name = 'default')
    {
        if (!is_numeric($id) || $id == 0) {
            $this->operator_privilege('page_types_add');
        } else {
            $this->operator_privilege('page_types_edit');
        }
        $this->set('title_for_layout', $this->ld['add_edit_page'].' - '.$this->configs['shop_name']);

//		$this->menu_path = array('root'=>'/system/','sub'=>'/page_types/');
//		$this->navigations[] = array('name'=>$this->ld['manage_system'],'url'=>'');
//		$this->navigations[] = array('name'=>$this->ld['module_management'],'url'=>'/page_types/');
//		$this->navigations[] = array('name'=>"模块 - ".$this->ld['add_edit_page'],'url'=>'');

        $this->menu_path = array('root' => '/system/','sub' => '/themes/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manager_templates'],'url' => '/themes/');

        $template_list = $this->Template->find('all');
        $template_Info = array();
        foreach ($template_list as $k => $v) {
            if ($template_name == $v['Template']['name']) {
                $template_Info = $v;
            }
            $templatelist[$v['Template']['name']] = $v['Template']['description'];
        }
        $this->set('template_list', $templatelist);
        if (!empty($template_Info)) {
            $this->set('template_Info', $template_Info);
            $this->navigations[] = array('name' => $this->ld['template'].' - '.$template_Info['Template']['description'],'url' => '/themes/view/'.$template_Info['Template']['id']);
        }
        $this->navigations[] = array('name' => $this->ld['module'].' - '.$this->ld['add_edit_page'],'url' => '');
        if ($this->RequestHandler->isPost()) {
            //先判断type的action是否有效,有效的话属性相同的其他的type都无效
            if ($this->data['PageType']['status'] == '1') {
                //让其他type表无效
                $this->PageType->updateAll(array('PageType.status' => '0'), array('PageType.id !=' => $id, 'PageType.page_type' => $this->data['PageType']['page_type'], 'code' => $this->data['PageType']['code']));
            }
            $this->PageType->save($this->data);
             /*
            *操作员日志
            *
            *记录更新添加的情况
            *
            *@author   weizhengye 
            *@version  $Id$
            */
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_edit_page'].':id '.$id, $this->admin['id']);
            }
            $template_Info = array();
            foreach ($template_list as $k => $v) {
                if ($this->data['PageType']['code'] == $v['Template']['name']) {
                    $template_Info = $v;
                }
            }
            if (empty($template_Info)) {
                $this->redirect('/page_types/view/'.$id.'/'.$this->data['PageType']['code']);
            } else {
                $this->redirect('/themes/view/'.$template_Info['Template']['id']);
            }
        }
        $this->data = $this->PageType->find('first', array('conditions' => array('PageType.id' => $id)));
        $this->set('id', $id);
        if (!empty($this->data)) {
            $conditions['PageAction.page_type_id'] = $id;
            $cond['conditions'] = $conditions;
            $cond['order'] = 'id';
            $pageaction_list = $this->PageAction->find('all', $cond);
            $this->set('pageaction_list', $pageaction_list);
        } else {
            $this->data['PageType']['code'] = $template_name;
        }
    }

    /**
     *pagetype删除的方法.
     *
     *删除PageType的记录（并删除他下面对应的pageaction和PageModule和PageModuleI18n下的记录）
     *
     *@author   weizhngye 
     *
     *@version  $Id$
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        /*判断权限*/
        if (!$this->operator_privilege('page_types_reomve', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        /*end*/
        //删除页面下样样式 模块
        //查找pageaction的id
        $page_action_id = $this->PageAction->find('list', array('conditions' => array('PageAction.page_type_id' => $id), 'fields' => 'PageAction.id'));
        //查找PageModule的id
        $page_module_ids = $this->PageModule->find('list', array('conditions' => array('PageModule.page_action_id' => $page_action_id), 'fields' => 'PageModule.id'));
        //查找删除PageModulPageModuleI18neI18n的id
        $this->PageModuleI18n->deleteAll(array('PageModuleI18n.module_id' => $page_module_ids));
        $this->PageModule->deleteAll(array('PageModule.id' => $page_module_ids));
        $this->PageAction->deleteAll(array('PageAction.id' => $page_action_id));
        $this->PageType->deleteAll(array('PageType.id' => $id));

        /*
        *操作员日志
        *
        *记录删除的情况
        *
        *@author   weizhengye 
        *@version  $Id$ 
        */

        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除页面 样式 模块:id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_the_ad_list_success'];

        die(json_encode($result));
    }
}
