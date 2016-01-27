<?php

/*****************************************************************************
 * Seevia 角色管理
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
class RolesController extends AppController
{
    public $name = 'OperatorRoles';
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler','Email'); // Added
    public $uses = array('OperatorRole','OperatorRoleI18n','Operator','OperatorAction','OperatorActionI18n','Application','Language','OperatorLog');

    public function index()
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/

        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operator_roles'],'url' => '/roles/');
        $this->OperatorRole->set_locale($this->locale);
        $condition = '';
        //角色搜索筛选条件
        $role_name = '';
        if (isset($this->params['url']['role_name']) && !empty($this->params['url']['role_name'])) {
            $condition['OperatorRoleI18n.name like'] = '%'.$this->params['url']['role_name'].'%';
            $role_name = $this->params['url']['role_name'];
        }
        $total = $this->OperatorRole->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'OperatorRole';
        $page = 1;
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters = array($rownum,$page);
        $options = array();
        $page = $this->Pagination->init($condition, $parameters, $options, $total, $rownum, $sortClass);
        $res = $this->OperatorRole->find('all', array('conditions' => $condition, 'rownum' => $rownum, 'page' => $page, 'order' => 'OperatorRole.created DESC'));

        $roles = $this->Operator->find('all');
        $role_list = array();
        if (!empty($res) && sizeof($res) > 0) {
            $operactions_ids = array();
            foreach ($res as $k => $v) {
                $role_list[$v['OperatorRole']['id']]['OperatorRole'] = $v['OperatorRole'];
                if (is_array($v['OperatorRoleI18n'])) {
                    $role_list[$v['OperatorRole']['id']]['OperatorRoleI18n'] = $v['OperatorRoleI18n'];
                }
                $action_lists = explode(';', $v['OperatorRole']['actions']);
                if (!empty($action_lists) && sizeof($role_list) > 0) {
                    foreach ($action_lists as $kk => $vv) {
                        $operactions_ids[$vv] = $vv;
                    }
                }

                $i = 1;
                foreach ($roles as $key => $value) {
                    $role_id = $value['Operator']['role_id'];
                    $arr = explode(';', $role_id);
                    if (in_array($role_list[$v['OperatorRole']['id']]['OperatorRole']['id'], $arr)) {
                        ++$i;
                    }
                }
                $role_list[$v['OperatorRole']['id']]['OperatorRole']['number'] = $i;
            }

            $this->OperatorAction->set_locale($this->backend_locale);
            $actionInfos = $this->OperatorAction->find('all', array('conditions' => array('OperatorAction.id' => $operactions_ids)));
            if (!empty($actionInfos) && sizeof($actionInfos) > 0) {
                $actionlist = array();
                foreach ($actionInfos as $k => $v) {
                    $actionlist[$v['OperatorAction']['id']] = $v['OperatorActionI18n']['name'];
                }

                foreach ($res as $k => $v) {
                    $action_lists = explode(';', $v['OperatorRole']['actions']);
                    $actiontxt = '';
                    if (!empty($action_lists) && sizeof($role_list) > 0) {
                        foreach ($action_lists as $kk => $vv) {
                            $actiontxt .= isset($actionlist[$vv]) ? $actionlist[$vv].';' : '';
                        }
                    }
                    if ($actiontxt != '') {
                        $actiontxt = substr($actiontxt, 0, strlen($actiontxt) - 1);
                    }
                    $role_list[$v['OperatorRole']['id']]['OperatorRole']['actionses'] = $actiontxt;
                }
            }
        }
        $this->set('role_list', $role_list);
        $this->set('role_name', $role_name);
        $this->set('title_for_layout', $this->ld['operator_roles'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function edit($id)
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_edit');
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/
        $this->set('title_for_layout', $this->ld['role_edit_role'].' - '.$this->ld['operator_roles'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operator_roles'],'url' => '/roles/');
        $this->navigations[] = array('name' => $this->ld['role_edit_role'],'url' => '');
        $operators = $this->Operator->find('all');//取得操作员列表
        $this->set('operators', $operators);
        $this->set('role_id', $id);
        if ($this->RequestHandler->isPost()) {
            $this->data['OperatorRole']['orderby'] = !empty($this->data['OperatorRole']['orderby']) ? $this->data['OperatorRole']['orderby'] : 50;
            if (isset($_REQUEST['competence'])) {
                $competence = $_REQUEST['competence'];
                $competence = implode(';', $competence);
                $this->data['OperatorRole']['actions'] = $competence;
            }
            $this->OperatorRole->save($this->data); //保存
                foreach ($this->data['OperatorRoleI18n'] as $v) {
                    $operatorrolei18n_info = array(
                                //   'id'=>	isset($v['id'])?$v['id']:'',
                                   'id' => $v['id'],
                                   'locale' => $v['locale'],
                                   'operator_role_id' => isset($v['operator_role_id']) ? $v['operator_role_id'] : $this->data['OperatorRole']['id'],
                                   'name' => isset($v['name']) ? $v['name'] : '',
                             );
                    $this->OperatorRoleI18n->saveall(array('OperatorRoleI18n' => $operatorrolei18n_info));//更新多语言
                }
            foreach ($operators as $k => $v) {
                if ($v['Operator']['role_id'] == 0) {
                    if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                        if (in_array($v['Operator']['id'], $_REQUEST['operators'])) {
                            $operators[$k]['Operator']['role_id'] = $this->data['OperatorRole']['id'];
                            $this->Operator->save($operators[$k]);
                        }
                    }
                } else {
                    $role_ids = explode(';', $v['Operator']['role_id'].';');
                    foreach ($role_ids as $key => $vaule) {
                        if (empty($vaule)) {
                            unset($role_ids[$key]);
                        }
                    }
                    if ($v['Operator']['id'] == 13) {
                    }
                    if (in_array($this->data['OperatorRole']['id'], $role_ids)) {
                        if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                            if (in_array($v['Operator']['id'], $_REQUEST['operators'])) {
                            } else {
                                foreach ($role_ids as $kkk => $vvv) {
                                    if ($vvv == $this->data['OperatorRole']['id']) {
                                        unset($role_ids[$kkk]);
                                    }
                                }
                                $operators[$k]['Operator']['role_id'] = implode(';', $role_ids);
                                $this->Operator->save($operators[$k]);
                            }
                        }
                    } else {
                        if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                            if (in_array($v['Operator']['id'], $_REQUEST['operators'])) {
                                $operators[$k]['Operator']['role_id'] .= ';'.$this->data['OperatorRole']['id'];
                                $this->Operator->save($operators[$k]);
                            }
                        }
                    }
                }
            }
            foreach ($this->data['OperatorRoleI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_role'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
                }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
        }
        $this->data = $this->OperatorRole->localeformat($id);
        $this->OperatorAction->set_locale($this->backend_locale);
        $operatoraction = $this->OperatorAction->alltree_hasname();
        $this->set('actions_arr', explode(';', $this->data['OperatorRole']['actions']));
        $this->set('operatorrole', $this->data);
        //应用判断
        $all_infos = $this->apps['codes'];
        foreach ($operatoraction as $k => $v) {
            if ($v['OperatorAction']['app_code'] != '' && !in_array($v['OperatorAction']['app_code'], $all_infos)) {
                unset($operatoraction[$k]);
            }
            if (isset($v['SubAction']) && count($v['SubAction']) > 0) {
                foreach ($v['SubAction'] as $kk => $vv) {
                    if (isset($vv['OperatorAction'])) {
                        if ($vv['OperatorAction']['app_code'] != '' && !in_array($vv['OperatorAction']['app_code'], $all_infos)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'applications_view' && isset($this->configs['use_app']) && $this->configs['use_app'] == 0 && (!isset($_SESSION['use_app']) || $_SESSION['use_app'] != 1)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'languages_view' && (($this->Language->find('count')) <= 0)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'payments_view' && (($this->Payment->find('count', array('conditions' => array('Payment.status' => 1)))) == 0)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                    }
                }
            }
        }
        $this->set('operatoraction', $operatoraction);
    }

    public function add()
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_add');
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/
        $this->set('title_for_layout', $this->ld['role_add_role'].' - '.$this->ld['operator_roles'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operator_roles'],'url' => '/roles/');
        $this->navigations[] = array('name' => $this->ld['role_add_role'],'url' => '');
        $operators = $this->Operator->find('all');//取得操作员列表
        $this->set('operators', $operators);
        if ($this->RequestHandler->isPost()) {
            $this->data['OperatorRole']['orderby'] = !empty($this->data['OperatorRole']['orderby']) ? $this->data['OperatorRole']['orderby'] : 50;
            $this->data['OperatorRole']['store_id'] = !empty($this->data['OperatorRole']['store_id']) ? $this->data['OperatorRole']['store_id'] : 0;
            $this->data['OperatorRole']['actions'] = !empty($this->data['OperatorRole']['actions']) ? $this->data['OperatorRole']['actions'] : 0;
            if (isset($_REQUEST['competence'])) {
                $competence = $_REQUEST['competence'];
                $competence = implode(';', $competence);
                $this->data['OperatorRole']['actions'] = $competence;
            }
            $this->OperatorRole->saveall($this->data['OperatorRole']); //保存
                  $id = $this->OperatorRole->id;
                  //新增角色多语言
                  if (is_array($this->data['OperatorRoleI18n'])) {
                      foreach ($this->data['OperatorRoleI18n'] as $k => $v) {
                          $v['operator_role_id'] = $id;
                          $this->OperatorRoleI18n->id = '';
                          $this->OperatorRoleI18n->saveall(array('OperatorRoleI18n' => $v));
                      }
                  }
            if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                foreach ($_REQUEST['operators'] as $k => $v) {
                    $operator = $this->Operator->findbyid($v);
                    if (!empty($operator['Operator']['role_id'])) {
                        $operator['Operator']['role_id'] .= ';'.$id;
                    } else {
                        $operator['Operator']['role_id'] = $id;
                    }
                    $this->Operator->save($operator);
                }
            }
            foreach ($this->data['OperatorRoleI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_role'].':'.$userinformation_name, $this->admin['id']);
                }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
        }
        $this->OperatorAction->set_locale($this->backend_locale);
        $operatoraction = $this->OperatorAction->alltree_hasname();
        foreach ($operatoraction as $k => $v) {
            $operatoraction[$k]['OperatorAction']['name'] = $v['OperatorActionI18n']['name'];
            if (isset($v['SubAction'])) {
                foreach ($v['SubAction'] as $kk => $vv) {
                    $operatoraction[$k]['SubAction'][$kk]['OperatorAction']['name'] = $vv['OperatorActionI18n']['name'];
                }
            }
        }
         //应用判断
        $all_infos = $this->apps['codes'];
        foreach ($operatoraction as $k => $v) {
            if ($v['OperatorAction']['app_code'] != '' && !in_array($v['OperatorAction']['app_code'], $all_infos)) {
                unset($operatoraction[$k]);
            }
            if (isset($v['SubAction']) && count($v['SubAction']) > 0) {
                foreach ($v['SubAction'] as $kk => $vv) {
                    if (isset($vv['OperatorAction'])) {
                        if ($vv['OperatorAction']['app_code'] != '' && !in_array($vv['OperatorAction']['app_code'], $all_infos)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'applications_view' && isset($this->configs['use_app']) && $this->configs['use_app'] == 0 && (!isset($_SESSION['use_app']) || $_SESSION['use_app'] != 1)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'languages_view' && (($this->Language->find('count')) <= 0)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'payments_view' && (($this->Payment->find('count', array('conditions' => array('Payment.status' => 1)))) == 0)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                    }
                }
            }
        }
        $this->set('operatoraction', $operatoraction);
    }

    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_remove');
        /*end*/
        $pn = $this->OperatorRoleI18n->find('list', array('fields' => array('OperatorRoleI18n.operator_role_id', 'OperatorRoleI18n.name'), 'conditions' => array('OperatorRoleI18n.operator_role_id' => $id, 'OperatorRoleI18n.locale' => $this->locale)));
        $this->OperatorRole->deleteAll(array('OperatorRole.id' => $id));
        $this->OperatorRoleI18n->deleteAll(array('OperatorRole.operator_role_id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_role'].':id '.' '.$pn[$id], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }

    public function batch_operations()
    {
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->OperatorRole->deleteAll(array('OperatorRole.id' => $v));
            $this->OperatorRoleI18n->deleteAll(array('OperatorRole.operator_role_id' => $v));
        }
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }

    public function getOperatorActionByRole()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['code'] = 0;
        $result['msg'] = 'No Data!';
        if ($this->RequestHandler->isPost()) {
            $operator_role_ids_str = $_POST['operator_role_ids'];
            $operator_role_ids = explode(';', $operator_role_ids_str);
            $this->OperatorRole->set_locale($this->backend_locale);
            $opera_data = $this->OperatorRole->find('list', array('conditions' => array('OperatorRole.id' => $operator_role_ids), 'fields' => array('OperatorRole.id', 'OperatorRole.actions')));
            $operator_action_ids_str = '';
            $operator_action_ids = array();
            foreach ($opera_data as $k => $v) {
                if (!empty($v) && $v != '') {
                    $operator_action_id = explode(';', $v);
                    foreach ($operator_action_id as $vv) {
                        $operator_action_ids[$vv] = $vv;
                    }
                }
            }
            if (!empty($operator_action_ids)) {
                $operator_action_ids_str = implode(';', $operator_action_ids);
            }
            $result['code'] = $operator_action_ids_str == '' ? 1 : 2;
            $result['msg'] = $operator_action_ids_str;
        }
        die(json_encode($result));
    }
}
