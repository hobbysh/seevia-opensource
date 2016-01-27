<?php

/*****************************************************************************
 * Seevia 菜单管理
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
class MenusController extends AppController
{
    public $name = 'Menus';
    public $helpers = array('Html');
    public $uses = array('OperatorMenu','OperatorMenuI18n','SystemResource');
    public $components = array('RequestHandler');

    public function index()
    {
        $this->pageTitle = '菜单管理'.' - '.$this->configs['shop_name'];
        $this->set('title_for_layout', $this->pageTitle);
        $this->menu_path = array('root' => '/web_application/','sub' => '/menus/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => '菜单管理','url' => '/menus/');
        $this->set('navigations', $this->navigations);
        $this->OperatorMenu->set_locale($this->backend_locale);
        $menus_tree = $this->OperatorMenu->tree('tree', $this->backend_locale, $this->apps['codes'], $this->admin['action_codes']);//取树形结构
        $this->set('menus_tree', $menus_tree);
    }
    //编辑页(新增编辑)
    public function view($id = 0)
    {
        if (!empty($id)) {
            $this->pageTitle = '编辑菜单 - 菜单管理'.' - '.$this->configs['shop_name'];
            $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
            $this->navigations[] = array('name' => '菜单管理','url' => '/menus/');
        } else {
            $this->pageTitle = '添加菜单 - 菜单管理'.' - '.$this->configs['shop_name'];
            $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
            $this->navigations[] = array('name' => '菜单管理','url' => '/menus/');
        }
        $this->set('title_for_layout', $this->pageTitle);
        $this->menu_path = array('root' => '/web_application/','sub' => '/menus/');
        if ($this->RequestHandler->isPost()) {
            $this->data['OperatorMenu']['orderby'] = !empty($this->data['OperatorMenu']['orderby']) ? $this->data['OperatorMenu']['orderby'] : 50;
            $this->OperatorMenu->saveAll($this->data['OperatorMenu']);
            $id = $this->OperatorMenu->id;
            $this->OperatorMenuI18n->deleteAll(array('operator_menu_id' => $id)); //删除原有多语言
            foreach ($this->data['OperatorMenuI18n'] as $k => $v) {
                $menuI18n_info = array(
                      'locale' => $k,
                       'operator_menu_id' => $id,
                      'name' => isset($v['name']) ? $v['name'] : '',
                );
                $this->OperatorMenuI18n->saveAll(array('OperatorMenuI18n' => $menuI18n_info));//更新多语言
            }
            foreach ($this->data['OperatorMenuI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            if (!empty($id)) {
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑菜单:id '.$id.' '.$userinformation_name, $this->admin['id']);
                }
            } else {
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加菜单:'.$userinformation_name, $this->admin['id']);
                }
            }
            $this->redirect('/menus/');
        }

        $this->data = $this->OperatorMenu->localeformat($id);
        $parentmenu = $this->OperatorMenu->find('all', array('conditions' => array('OperatorMenu.parent_id' => 0, 'OperatorMenuI18n.locale' => $this->backend_locale)));
        $this->set('parentmenu', $parentmenu);
        if (isset($this->data['OperatorMenuI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$this->data['OperatorMenuI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        }
        //leo20090722导航显示
        //$this->navigations[] = array('name'=>$this->data["OperatorMenuI18n"]["name"],'url'=>'');
        $this->set('navigations', $this->navigations);
        //取版本标识
        /*$this->SystemResource->set_locale($this->locale);
        $this->set('section',$this->SystemResource->find_assoc('section'));*/
    }
    public function remove($id)
    {
        $result = $this->OperatorMenu->find('first', array('conditions' => array('parent_id' => $id)));
        if ($result) {
            $result['flag'] = 0;
            $result['message'] = '删除失败，改菜单还有子菜单';
        } else {
            $pn = $this->OperatorMenuI18n->find('list', array('fields' => array('OperatorMenuI18n.operator_menu_id', 'OperatorMenuI18n.name'), 'conditions' => array('OperatorMenuI18n.operator_menu_id' => $id, 'OperatorMenuI18n.locale' => $this->locale)));
            $this->OperatorMenu->delete($id);
            $this->OperatorMenuI18n->deleteAll(array('operator_menu_id' => $id)); //删除原有多语言

            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除菜单:id '.$id, $this->admin['id']);
            }
            $result['flag'] = 1;
            $result['message'] = $this->ld['delete_the_ad_list_success'];
        }
        die(json_encode($result));
    }
}
