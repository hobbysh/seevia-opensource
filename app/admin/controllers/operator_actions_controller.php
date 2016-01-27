<?php

class OperatorActionsController extends AppController
{
    public $name = 'OperatorActions';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('OperatorAction','OperatorActionI18n');

    public function index()
    {
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rights_management'],'url' => '/operator_actions/');
        $this->menu_path = array('root' => '/web_application/','sub' => '/operator_actions/');
        $condition = '';
        if (isset($this->params['url']['name']) && !empty($this->params['url']['name'])) {
            $condition['OperatorActionI18n.name like'] = '%'.$this->params['url']['name'].'%';
        }
        $this->OperatorAction->set_locale($this->locale);

        $total = $this->OperatorAction->find('count', array('conditions' => $condition));
        $sortClass = 'OperatorAction';
        $page = 1;
        $rownum = isset($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters = array($rownum,$page);
        $options = array();
        $page = $this->Pagination->init($condition, $parameters, $options, $total, $rownum, $sortClass);

        $operator_action_data = $this->OperatorAction->find('all', array('conditions' => $condition, 'rownum' => $rownum, 'page' => $page, 'order' => 'OperatorAction.orderby asc'));
      //  $operator_action_data = $this->OperatorAction->find("threaded",array("order"=>"orderby asc"));
      //  $operator_action_data = $this->OperatorAction->find("all",array("order"=>"orderby asc"));
        $action_tree = $this->OperatorAction->alltree();
        //pr($action_tree);
        $this->set('action_tree', $action_tree);
        $this->set('operator_action_data', $operator_action_data);
        $this->set('title_for_layout', '权限管理'.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    public function view($id = 0)
    {
        $this->set('title_for_layout', '添加/编辑权限- 权限管理'.' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rights_management'],'url' => '/operator_actions/');
        $this->navigations[] = array('name' => $this->ld['add_edit_permissions'],'url' => '');
        $this->menu_path = array('root' => '/web_application/','sub' => '/operator_actions/');
        if ($this->RequestHandler->isPost()) {
            $this->data['OperatorAction']['orderby'] = !empty($this->data['OperatorAction']['orderby']) ? $this->data['OperatorAction']['orderby'] : '50';
            if (isset($this->data['OperatorAction']['id']) && $this->data['OperatorAction']['id'] != 0) {
                $this->OperatorAction->save(array('OperatorAction' => $this->data['OperatorAction'])); //关联保存
                $id = $this->data['OperatorAction']['id'];
            } else {
                $this->OperatorAction->saveAll(array('OperatorAction' => $this->data['OperatorAction'])); //关联保存
                $id = $this->OperatorAction->getLastInsertId();
            }
            $this->OperatorActionI18n->deleteall(array('operator_action_id' => $id)); //删除原有多语言
            foreach ($this->data['OperatorActionI18n'] as $v) {
                $v['operator_action_id'] = $id;
                $this->OperatorActionI18n->saveAll(array('OperatorActionI18n' => $v));//更新多语言
            }
             //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑权限:id '.$id, $this->admin['id']);
            }
            $this->redirect('/operator_actions/');
        }

        $operator_action_data = $this->OperatorAction->localeformat($id);
        //var_dump($operator_action_data);
        $this->set('operator_action_data', $operator_action_data);
        //pr($operator_action_data);
        $this->OperatorAction->set_locale($this->locale);
        $operator_action_parent = $this->OperatorAction->find('threaded');
        $action_tree = $this->OperatorAction->tree($this->locale);
        $this->set('action_tree', $action_tree);
        //pr($operator_action_parent);
        $this->set('operator_action_parent', $operator_action_parent);
    }

    //列表修改排序//无用函数
    public function update_operator_action_orderby()
    {
        $this->OperatorAction->hasMany = array();
        $this->OperatorAction->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = '请输入正确的排序数据！';
        }
        if (is_numeric($val) && $this->OperatorAction->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //列表状态修改//无用函数
    public function toggle_on_status()
    {
        $this->OperatorAction->hasMany = array();
        $this->OperatorAction->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->OperatorAction->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = '删除权限失败';
        $this->OperatorAction->deleteAll(array('OperatorAction.id' => $id));
        $this->OperatorActionI18n->deleteAll(array('operator_action_id' => $id));
        $this->removechild($id);
        $result['flag'] = 1;
        $result['message'] = '删除权限成功';
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function batch_operations()
    {
        $this->OperatorAction->hasOne = array();
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->OperatorAction->deleteAll(array('id' => $v));
            $this->OperatorActionI18n->deleteAll(array('operator_action_id' => $v));
            $thid->removechild($v);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }

    public function removechild($id = 0)
    {
        $child_actions = $this->OperatorAction->find('list', array('fields' => array('OperatorAction.id'), 'conditions' => array('OperatorAction.parent_id' => $id)));
        if (!empty($child_actions)) {
            foreach ($child_actions as $v) {
                $this->OperatorAction->deleteAll(array('OperatorAction.id' => $v));
                $this->OperatorActionI18n->deleteAll(array('operator_action_id' => $v));
                $this->removechild($v);
            }
        }
    }
}
