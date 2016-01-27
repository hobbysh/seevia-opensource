<?php

    /*
        svsns_user_messages
        站内信管理
        1、可根据接收用户ID/名称和创建时间搜索，列表显示，用户ID,用户名，站内信内容，创建时间，可以新增、编辑、删除。可导入。
        2、会员列表，操作内容里新增发站内信。
    */
class UserMessagesController extends AppController
{
    public $name = 'UserMessages';
    public $uses = array('UserMessage','User');
    public $components = array('Pagination','RequestHandler','Phpexcel','EcFlagWebservice');//,'EcFlagWebservice'
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');

    /*
        站内信列表显示
    */
    public function index($page = 1)
    {
        $this->operator_privilege('user_messages_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/user_messages/');//设置导航栏
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');//设置路径1
        $this->navigations[] = array('name' => $this->ld['station_letter_manage'],'url' => '/user_messages/');//设置路径2
        $conditions = '';
        $conditions['UserMessage.parent_id'] = '0';
        $conditions['UserMessage.from_id'] = '0';
        $conditions['UserMessage.to_id !='] = '0';
        $conditions['UserMessage.status'] = '1';
        //用户ID/用户名
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
            $_cond['or']['User.name like'] = '%'.$_REQUEST['keyword'].'%';
            $_cond['or']['User.email like'] = '%'.$_REQUEST['keyword'].'%';
            $_cond['or']['User.first_name like'] = '%'.$_REQUEST['keyword'].'%';
            $_cond['or']['User.last_name like'] = '%'.$_REQUEST['keyword'].'%';
            $user_ids = $this->User->find('list', array('fields' => 'User.id', 'conditions' => $_cond));
            $conditions['or']['UserMessage.user_id'] = $user_ids;
            $conditions['or']['UserMessage.user_name like'] = '%'.$_REQUEST['keyword'].'%';
            $conditions['or']['UserMessage.user_email like'] = '%'.$_REQUEST['keyword'].'%';
            $conditions['or']['UserMessage.msg_content like'] = '%'.$_REQUEST['keyword'].'%';
            $conditions['or']['UserMessage.msg_title like'] = '%'.$_REQUEST['keyword'].'%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        //开始时间
        if (isset($_REQUEST['start_date_time']) && $_REQUEST['start_date_time'] != '') {
            $conditions['and']['created >='] = $_REQUEST['start_date_time'];
            $this->set('start_date_time', $_REQUEST['start_date_time']);
        }
        //结束时间
        if (isset($_REQUEST['end_date_time']) && $_REQUEST['end_date_time'] != '') {
            $conditions['and']['created <='] = $_REQUEST['end_date_time'];
            $this->set('end_date_time', $_REQUEST['end_date_time']);
        }
        $cond['conditions'] = $conditions;
        //分页
        $total = $this->UserMessage->find('count', $cond);//获取总记录数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];//当前页
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'UserMessage','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserMessage');
        $this->Pagination->init($conditions, $parameters, $options);
        $cond['limit'] = $rownum;
        $cond['page'] = $page;
        $cond['order'] = 'created desc';
        $cond['fields'] = array('id','user_id','user_name','msg_content','created');
        $MessageInfo = $this->UserMessage->find('all', $cond);
        $this->set('MessageInfo', $MessageInfo);
        //设置页面标题
        $title = $this->ld['station_letter_manage'];
        $this->set('title_for_layout', $title.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    /*
        会员列表
    */
    public function userview($page = 1)
    {
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/user_messages/');//设置导航栏
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');//设置路径1
        $this->navigations[] = array('name' => $this->ld['station_letter_manage'],'url' => '/user_messages/');//设置路径2
        $this->navigations[] = array('name' => $this->ld['members_list'],'url' => '/user_messages/userview');//设置路径2
        $conditions = '';
        $conditions['and']['status'] = 1;
        //用户ID/用户名
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
            $conditions['or']['id like'] = '%'.$_REQUEST['keyword'].'%';
            $conditions['or']['name like'] = '%'.$_REQUEST['keyword'].'%';
            $conditions['or']['email like'] = '%'.$_REQUEST['keyword'].'%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        $cond['conditions'] = $conditions;
        //分页
        $total = $this->User->find('count', $cond);//获取总记录数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];//当前页
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'user_messages','action' => 'userview','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserMessage');
        $this->Pagination->init($conditions, $parameters, $options);
        $cond['limit'] = $rownum;
        $cond['page'] = $page;
        $cond['order'] = 'id';
        $cond['fields'] = array('id','name','email');
        $userinfo = $this->User->find('all', $cond);
        $this->set('userinfo', $userinfo);
        //设置页面标题
        $title = $this->ld['station_letter_manage'].' - '.$this->ld['members_list'];
        $this->set('title_for_layout', $title.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($id = 0, $user_id = 0)
    {
        if ($id == 0) {
            $this->operator_privilege('user_messages_add');
            if ($user_id == 0) {
                $this->redirect('/user_messages/userview');
            } else {
                $userinfo = $this->User->find('first', array('conditions' => array('id' => $user_id)));
                if (empty($userinfo)) {
                    $this->redirect('/user_messages/userview');
                }
            }
        } else {
            $this->operator_privilege('user_messages_edit');
        }
        $this->menu_path = array('root' => '/crm/','sub' => '/user_messages/');//设置导航栏
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');//设置路径1
        $this->navigations[] = array('name' => $this->ld['station_letter_manage'],'url' => '/user_messages/');//设置路径2
        $title = $this->ld['station_letter_manage'].' - '.$this->ld['log_send_station_letter'];
        $messageInfo = $this->UserMessage->find('all', array('fields' => array('id', 'user_id', 'user_name', 'user_email', 'msg_title', 'msg_content'), 'conditions' => array('id' => $id)));
    }

    /*
        发送、编辑站内信
    */
    public function addmessage($id = 0)
    {
        if ($id == 0) {
            $this->operator_privilege('user_messages_add');
        } else {
            $this->operator_privilege('user_messages_edit');
        }
        $this->operator_privilege('user_messages_add');
        $this->menu_path = array('root' => '/crm/','sub' => '/user_messages/');//设置导航栏
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');//设置路径1
        $this->navigations[] = array('name' => $this->ld['station_letter_manage'],'url' => '/user_messages/');//设置路径2
        $title = $this->ld['station_letter_manage'].' - '.$this->ld['log_send_station_letter'];

        //查找用户信息
        if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '') {
            $userinfo = $this->User->find('first', array('conditions' => array('id' => $_REQUEST['user_id'])));
            if (!empty($userinfo)) {
                $this->set('userinfo', $userinfo);

                $this->navigations[] = array('name' => $this->ld['members_list'],'url' => '/user_messages/userview');//设置路径
                $this->navigations[] = array('name' => $this->ld['log_send_station_letter'],'url' => '');//设置路径
            } else {
                $this->redirect('/user_messages/userview');
            }
        }

        if ($this->RequestHandler->isPost()) {
            $msg['msg_title'] = $_POST['msg_title'];
            $msg['msg_content'] = $_POST['msg_content'];
            if (isset($_POST['msg_id']) && $_POST['msg_id'] != '') {
                $msg['id'] = $_POST['msg_id'];
                $msg['modified'] = date('Y-m-d H:i:s');
            } else {
                $msg['from_id'] = '0';
                $msg['to_id'] = $_POST['user_id'];
                $msg['user_id'] = $_POST['user_id'];
                $msg['user_name'] = $_POST['user_name'];
                $msg['user_email'] = $_POST['user_email'];
                $msg['status'] = 1;
                $msg['created'] = date('Y-m-d H:i:s');
            }
            $this->UserMessage->save($msg);

            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $log_message = '';
                if (isset($_POST['msg_id']) && $_POST['msg_id'] == '') {
                    $log_message = $this->ld['log_send_station_letter'];
                } else {
                    $log_message = $this->ld['log_edit_station_letter'];
                }
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$log_message.':id '.$id.' '.$_POST['msg_title'], $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        //查找站内信信息
        if ($id != 0) {
            $messageInfo = $this->UserMessage->find('first', array('fields' => array('id', 'user_id', 'user_name', 'user_email', 'msg_title', 'msg_content'), 'conditions' => array('id' => $id)));
            if (!empty($messageInfo)) {
                $userinfo = $this->User->find('first', array('conditions' => array('id' => $messageInfo['UserMessage']['user_id'])));
                $this->set('userinfo', $userinfo);

                $this->set('messageInfo', $messageInfo);
                $this->navigations[] = array('name' => '站内信编辑','url' => '');//设置路径
            } else {
                $this->redirect('/user_messages/');
            }
        }
        //设置页面标题
        $this->set('title_for_layout', $title.' - '.$this->configs['shop_name']);
    }

    /*
        删除站内信
    */
    public function remove($id)
    {
        $this->operator_privilege('user_messages_remove');
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_route_failure'];
        $message['id'] = $id;
        $message['status'] = 0;
        if ($this->UserMessage->save($message)) {
            $result['flag'] = 1;
            $result['message'] = $this->ld['delete_article_success'];
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_remove_station_letter'].':id '.$id, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
        批量删除站内信
    */
    public function removeAll()
    {
        $art_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $condition['UserMessage.id'] = $art_ids;
        $this->UserMessage->updateAll(array('UserMessage.status' => '2'), array('UserMessage.id' => $art_ids));

        $pagename = isset($_REQUEST['deltype']) ? '/user_messages/'.$_REQUEST['deltype'] : '/user_messages/';
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['batch_delete'].':'.$this->ld['log_remove_station_letter'], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }
}
