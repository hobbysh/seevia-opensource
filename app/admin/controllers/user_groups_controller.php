<?php

/*****************************************************************************
 * Seevia 会员等级
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
*****************************************************************************/
class UserGroupsController  extends AppController
{
    public $name = 'UserGroups';

    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('UserGroup','OperatorLog');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('user_groups_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        /*end*/
        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        $this->set('title_for_layout', $this->ld['user_group'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists');
        $this->navigations[] = array('name' => $this->ld['user_group'],'url' => '');
        $this->set('navigations', $this->navigations);
        $conditions = '';
        $cond['conditions'] = $conditions;
        //分页
        $total = $this->UserGroup->find('count', $cond);//获取总记录数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];//当前页
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'UserGroup','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserGroup');
        $this->Pagination->init($conditions, $parameters, $options);
        $cond['limit'] = $rownum;
        $cond['page'] = $page;
        $cond['order'] = 'UserGroup.id';
        $user_group_list = $this->UserGroup->find('all', $cond);
        $this->set('user_group_list', $user_group_list);
    }

    public function view($id = 0)
    {
        if ($id == '0') {
            $this->operator_privilege('user_groups_add');
        } else {
            $this->operator_privilege('user_groups_edit');
        }
        /*end*/
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists');
        if ($id == '0') {
            $this->set('title_for_layout', $this->ld['user_group'].' - '.$this->ld['add'].$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['user_group'].' - '.$this->ld['add'],'url' => '');
        } else {
            $this->set('title_for_layout', $this->ld['user_group'].' - '.$this->ld['edit'].$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['user_group'].' - '.$this->ld['edit'],'url' => '');
        }
        $this->set('navigations', $this->navigations);
        if ($this->RequestHandler->isPost()) {
            if ($this->data['UserGroup']['id'] == '0') {
                $data['UserGroup']['created'] = date();
            } else {
                $data['UserGroup']['id'] = $this->data['UserGroup']['id'];
            }
            $data['UserGroup']['name'] = $this->data['UserGroup']['name'];
            $data['UserGroup']['description'] = $this->data['UserGroup']['description'];
            $data['UserGroup']['status'] = $this->data['UserGroup']['status'] != '' ? $this->data['UserGroup']['status'] : '0';

            if ($this->data['UserGroup']['id'] == '0') {
                //添加
                $this->UserGroup->save($data['UserGroup']);
                $id = $this->UserGroup->id;//获取上一个插入记录的id
            } else {
                //编辑
                $this->UserGroup->save($data['UserGroup']);
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $log_message = '';
                if ($this->data['UserGroup']['id'] == '0') {
                    $log_message = $this->ld['log_add_user_rank'];
                } else {
                    $log_message = $this->ld['log_edit_user_rank'];
                }
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$log_message.':id '.$id, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $UserGroup = $this->UserGroup->findbyId($id);
        $this->set('id', $id);
        $this->set('UserGroup', $UserGroup);
    }

    /*
        删除等级
    */
    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('user_groups_remove');
        /*end*/
        $this->UserGroup->deleteAll(array('id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_remove_user_rank'].':id '.$id, $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }

    /*
        批量删除
    */
    public function removeAll()
    {
        $this->operator_privilege('user_groups_remove');
        if ($this->RequestHandler->isPost()) {
            $art_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
            foreach ($art_ids as $k => $v) {
                $this->UserGroup->delete(array('UserGroup.id' => $v));
            }
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['batch_delete'].':'.$this->ld['user_group'], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }
}
