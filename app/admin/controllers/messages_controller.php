<?php

/*****************************************************************************
 * Seevia 留言查询
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
class MessagesController extends AppController
{
    public $name = 'Messages';
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler','Email','Phpexcel');
    public $uses = array('MailSendQueue','MailTemplate','UserMessage','User','ProductType','Resource','Product','ProductI18n','OperatorLog');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('messages_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/messages/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['message_search'],'url' => '/messages/');
        $this->set('title_for_layout', $this->ld['message_search'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);

        $condition['UserMessage.parent_id'] = 0;
        $condition['UserMessage.from_id !='] = 0;
        $condition['UserMessage.to_id'] = 0;
        if (isset($this->params['url']['type']) && $this->params['url']['type'] != '') {
            $condition['UserMessage.type ='] = $this->params['url']['type'];
            $this->set('type', $this->params['url']['type']);
        }
        if (isset($this->params['url']['title']) && $this->params['url']['title'] != '') {
            $condition['or']['UserMessage.msg_title LIKE'] = '%'.$this->params['url']['title'].'%';
            $condition['or']['UserMessage.msg_title LIKE'] = '%'.$this->params['url']['title'].'%';
            $this->set('titles', $this->params['url']['title']);
        }
        if (isset($this->params['url']['end_time']) && $this->params['url']['end_time'] != '') {
            $condition['UserMessage.created <='] = $this->params['url']['end_time'];
            $this->set('end_time', $this->params['url']['end_time']);
        }
        if (isset($this->params['url']['start_time']) && $this->params['url']['start_time'] != '') {
            $condition['UserMessage.created >='] = $this->params['url']['start_time'];
            $this->set('start_time', $this->params['url']['start_time']);
        }
        if (isset($this->params['url']['reply_status']) && $this->params['url']['reply_status'] != '') {
            $reply_status = $this->params['url']['reply_status'];
            $replycount = $this->UserMessage->find('all', array('fields' => array('UserMessage.parent_id', 'count(*) as replycount'), 'conditions' => array('UserMessage.parent_id !=' => '0'), 'group' => 'UserMessage.parent_id'));
            $reply_ids = array();
            if (!empty($replycount) && sizeof($replycount) > 0) {
                foreach ($replycount as $k => $v) {
                    $reply_ids[] = $v['UserMessage']['parent_id'];
                }
                if ($reply_status == 0) {
                    $condition['UserMessage.id not'] = $reply_ids;
                } else {
                    $condition['UserMessage.id'] = $reply_ids;
                }
            }
            $this->set('reply_status', $this->params['url']['reply_status']);
        }
        $total = $this->UserMessage->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'UserMessage';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'messages','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserMessage');
        $this->Pagination->init($condition, $parameters, $options);
        $UserMessage_list = $this->UserMessage->find('all', array('conditions' => $condition, 'order' => 'created desc', 'limit' => $rownum, 'page' => $page));

        if (!empty($UserMessage_list) && sizeof($UserMessage_list) > 0) {
            //统计是否回复
            $message_ids = array();
            foreach ($UserMessage_list as $k => $v) {
                $message_ids[] = $v['UserMessage']['id'];
            }
            $reply_count = $this->UserMessage->find('all', array('fields' => array('UserMessage.parent_id', 'count(*) as replycount'), 'conditions' => array('UserMessage.parent_id' => $message_ids), 'group' => 'UserMessage.parent_id'));
            $replycount_list = array();
            if (!empty($reply_count) && sizeof($reply_count) > 0) {
                foreach ($reply_count as $k => $v) {
                    $replycount_list[$v['UserMessage']['parent_id']] = $v[0]['replycount'];
                }
            }
            $this->set('replycount_list', $replycount_list);
            //关联信息
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('Order');
                $this->Order->hasMany = array();
                $this->Order->hasOne = array();
                $order_data = $this->Order->find('all', array('fields' => array('Order.id', 'Order.order_code')));
                $order_list = array();
                foreach ($order_data as $k => $v) {
                    $order_list[$v['Order']['id']] = $v;
                }
                $this->set('order_list', $order_list);
            }
            $this->Product->hasMany = array();
            $fields[] = 'Product.id';
            $fields[] = 'ProductI18n.name';
            $this->Product->set_locale($this->backend_locale);
            $products_data = $this->Product->find('all', array('order' => 'Product.id desc', 'fields' => $fields));
            $products_list = array();
            foreach ($products_data as $k => $v) {
                $products_list[$v['Product']['id']] = $v['ProductI18n']['name'];
            }
            $this->set('products_list', $products_list);
        }
        $this->set('UserMessage_list', $UserMessage_list);
    }

    public function search($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('messages_search');
        $this->menu_path = array('root' => '/crm/','sub' => '/messages/');
        /*end*/
        $this->set('title_for_layout', $this->ld['pending_message'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['pending_message'],'url' => '/messages/search/unprocess');
        $condition['UserMessage.status ='] = '0';
        $condition['UserMessage.parent_id ='] = '0';
        $total = $this->UserMessage->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'UserMessage';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'messages','action' => 'search','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserMessage');
        $this->Pagination->init($condition, $parameters, $options);
        $UserMessage_list = $this->UserMessage->find('all', array('conditions' => $condition, 'order' => 'id desc', 'limit' => $rownum, 'page' => $page));
        foreach ($UserMessage_list as $k => $v) {
            $user_id = $UserMessage_list[$k]['UserMessage']['user_id'];
            $wh['id'] = $user_id;
            $User_list = $this->User->find('all', array('conditions' => $wh));
            $UserMessage_list[$k]['UserMessage']['name'] = '';
            $UserMessage_list[$k]['UserMessage']['rank'] = '';
            foreach ($User_list as $key => $value) {
                $UserMessage_list[$k]['UserMessage']['name'] = $value['User']['name'];
                $UserMessage_list[$k]['UserMessage']['rank'] = $value['User']['rank'];
            }
        }
        $this->set('UserMessage_list', $UserMessage_list);
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Order');
               //20090722新增
               $this->Order->hasMany = array();
            $this->Order->hasOne = array();
            $order_data = $this->Order->find('all', array('fields' => array('Order.id', 'Order.order_code')));
            $order_list = array();
            foreach ($order_data as $k => $v) {
                $order_list[$v['Order']['id']] = $v;
            }
            $this->set('order_list', $order_list);
        }
        $this->Product->hasMany = array();
        $fields[] = 'Product.id';
        $fields[] = 'ProductI18n.name';
        $this->Product->set_locale($this->backend_locale);
        $products_data = $this->Product->find('all', array('order' => 'Product.id desc', 'fields' => $fields));
        $products_list = array();
        foreach ($products_data as $k => $v) {
            $products_list[$v['Product']['id']] = $v['ProductI18n']['name'];
        }
        $this->set('products_list', $products_list);
        //end 
        if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
            $this->set('ex_page', $this->params['url']['page']);
        }
        /*CSV导出*/
        if (isset($_REQUEST['export']) && $_REQUEST['export'] === 'export') {
            $data = array();
            $newdata = array();
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['statistics_pending_messages'], $this->admin['id']);
            }
            $filename = $this->ld['export_pending_messages'].date('Ymd').'.xls';
            $data[] = array($this->ld['statistics_pending_messages'],$this->ld['date'],$this->ld['number'],$this->ld['user_name'], $this->ld['meessage_title'],$this->ld['type'],$this->ld['message_time']);
            $newdata = $data;
            foreach ($UserMessage_list as $key => $val) {
                $data = array();
                $data[] = ' ';
                $data[] = date('Y-m-d', time());
                $data[] = $val['UserMessage']['id'];
                $data[] = $val['UserMessage']['user_name'];
                $data[] = $val['UserMessage']['msg_title'];
                $data[] = $val['UserMessage']['type'];
                $data[] = $val['UserMessage']['created'];
                $newdata[] = $data;
            }
            $this->Phpexcel->output($filename, $newdata);
            exit;
        }
    }

    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('messages_remove');
        /*end*/
        $this->UserMessage->deleteAll(array('UserMessage.id' => $id));
        $this->UserMessage->deleteAll(array('UserMessage.parent_id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete_message'].':id '.$id, $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }

    public function remove_search($id)
    {
        $pn = $this->UserMessage->find('list', array('fields' => array('UserMessage.id', 'UserMessage.msg_title'), 'conditions' => array('UserMessage.id' => $id)));
        $this->UserMessage->deleteAll("UserMessage.id='$id'");
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete_dending_message'].':'.$pn[$id], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }

    public function view($id)
    {
        /*判断权限*/
        $this->operator_privilege('messages_edit');
        /*end*/
        $this->menu_path = array('root' => '/crm/','sub' => '/messages/');
        $this->set('title_for_layout', $this->ld['reply_message'].' - '.$this->ld['message_search'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['message_search'],'url' => '/messages/');
        $this->navigations[] = array('name' => $this->ld['reply_message'],'url' => '');
        $usermessage = $this->UserMessage->findById($id);
        $user_info = $this->User->findById($usermessage['UserMessage']['user_id']);
        $this->navigations[] = array('name' => $user_info['User']['name'],'url' => '');
        if ($this->RequestHandler->isPost()) {
            if ($this->data['UserMessage']['msg_content'] != '') {
                $this->data['UserMessage']['status'] = '1';
                $this->data['UserMessage']['created'] = date('Y-m-d H:i:s');
                $this->data['UserMessage']['user_name'] = $this->admin['name'];
                $this->UserMessage->save($this->data);
                $this->UserMessage->updateAll(
                          array('UserMessage.status' => '1', 'UserMessage.is_read' => '0'),
                          array('UserMessage.id' => $id)
                       );
                $usermessage = $this->UserMessage->findById($id);
                $wh['parent_id'] = $id;
                $restore = $this->UserMessage->find($wh);
                $usermessage = $this->UserMessage->findById($id);
                $this->MailTemplate->set_locale($this->locale);
                $template = 'message_revert';
                $template = $this->MailTemplate->find("code = '$template' and status = '1'");
                $shop_name = $this->configs['shop_name'];
                $usermessage = $this->UserMessage->findById($id);
                $wh['parent_id'] = $id;
                $restore = $this->UserMessage->find($wh);
                $sent_date = date('Y-m-d H:m:s');
                $restore_content = $restore['UserMessage']['msg_content'];
                $msg_content = $usermessage['UserMessage']['msg_content'];
                $user_name = $usermessage['UserMessage']['user_name'];
                $created = $usermessage['UserMessage']['created'];
                $msg_title = $usermessage['UserMessage']['msg_title'];
                $msg_type = $this->ld['message'];
                /* 商店网址 */
                $shop_url = $this->server_host.$this->webroot;
                $url = $shop_url.'/user/messages/';
                //读模板
                $template = 'message_revert';
                $this->MailTemplate->set_locale($this->locale);
                $template = $this->MailTemplate->find("code = '$template' and status = '1'");
                //模板赋值
                $html_body = $template['MailTemplateI18n']['html_body'];
                eval("\$html_body = \"$html_body\";");
                $text_body = $template['MailTemplateI18n']['text_body'];
                eval("\$text_body = \"$text_body\";");
                //主题赋值
                $title = $template['MailTemplateI18n']['title'];
                eval("\$title = \"$title\";");
                $mailsendqueue = array(
                       'sender_name' => $shop_name,//发送从姓名
                       'receiver_email' => $user_name.';'.$usermessage['UserMessage']['user_email'],//接收人姓名;接收人地址
                     'cc_email' => ';',//抄送人
                     'bcc_email' => ';',//暗送人
                      'title' => $title,//主题 
                       'html_body' => $html_body,//内容
                      'text_body' => $text_body,//内容
                     'sendas' => 'html',
                 );
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['reply_message'].':id '.$id.' '.$msg_title, $this->admin['id']);
                }
                $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
            }
        }

        $wh['parent_id'] = $id;
        $restore = $this->UserMessage->find('all', array('conditions' => $wh));
        $this->set('usermessage', $usermessage);
        if (!empty($restore)) {
            $this->set('restore', $restore);
        }
    }

    public function view_search($id)
    {
        $this->operator_privilege('messages_search');
        $this->menu_path = array('root' => '/crm/','sub' => '/messages/');
        $this->pageTitle = $this->ld['reply_message'].' - '.$this->ld['message_search'].' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['message_search'],'url' => '/operators/');
        $this->navigations[] = array('name' => $this->ld['reply_message'],'url' => '');
        $usermessage = $this->UserMessage->findById($id);
        $user_info = $this->User->findById($usermessage['UserMessage']['user_id']);
        $this->navigations[] = array('name' => $user_info['User']['name'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            if ($this->data['UserMessage']['msg_content'] != '') {
                $this->UserMessage->deleteAll('UserMessage.parent_id='.$this->data['UserMessage']['parent_id']);
                $this->UserMessage->save($this->data);
                $this->UserMessage->updateAll(
                          array('UserMessage.status' => '1'),
                          array('UserMessage.id' => $id)
                       );
                $shop_name = $this->configs['shop_name'];
                $usermessage = $this->UserMessage->findById($id);
                $wh['parent_id'] = $id;
                $restore = $this->UserMessage->find($wh);
                $sent_date = date('Y-m-d H:m:s');
                $restore_content = $restore['UserMessage']['msg_content'];
                $msg_content = $usermessage['UserMessage']['msg_content'];
                $user_name = $usermessage['UserMessage']['user_name'];
                $created = $usermessage['UserMessage']['created'];
                $msg_title = $usermessage['UserMessage']['msg_title'];
                //资源库信息
                //$this->Resource->set_locale($this->locale);
                //$Resource_info = $this->Resource->resource_formated(false);
                   //
                //$msg_type = $Resource_info["msg_type"][$usermessage['UserMessage']['msg_type']];
                /* 商店网址 */
                $shop_url = $this->server_host.$this->webroot;
                $url = $shop_url.'/user/messages/';
                //读模板
                $template = 'message_revert';
                $this->MailTemplate->set_locale($this->locale);
                $template = $this->MailTemplate->find("code = '$template' and status = '1'");
                //模板赋值
                $html_body = $template['MailTemplateI18n']['html_body'];
                eval("\$html_body = \"$html_body\";");
                $text_body = $template['MailTemplateI18n']['text_body'];
                eval("\$text_body = \"$text_body\";");
                //主题赋值
                $title = $template['MailTemplateI18n']['title'];
                eval("\$title = \"$title\";");
                $mailsendqueue = array(
                       'sender_name' => $shop_name,//发送从姓名
                       'receiver_email' => $user_name.';'.$usermessage['UserMessage']['user_email'],//接收人姓名;接收人地址
                     'cc_email' => ';',//抄送人
                     'bcc_email' => ';',//暗送人
                      'title' => $title,//主题 
                       'html_body' => $html_body,//内容
                      'text_body' => $text_body,//内容
                     'sendas' => 'html',
                 );

                $this->Email->send_mail($this->locale, $this->configs['email_the_way'], $mailsendqueue, $this->configs);
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['reply_message'].':'.$msg_title, $this->admin['id']);
                }
                $this->flash($this->ld['message'].' '.$msg_title.' '.$this->ld['message'], '/messages/search/', 10);
            } else {
                $this->flash('- '.$this->ld['reply_content_not_empty'], '/messages/view_search/'.$id, 10, false);
            }
        }

        $wh['parent_id'] = $id;
        $restore = $this->UserMessage->find($wh);
        //pr( $restore );
        $this->set('usermessage', $usermessage);
        if (!empty($restore)) {
            $this->set('restore', $restore);
        }
    }

    //批量处理
    public function batch()
    {
        if (isset($this->params['url']['act_type']) && !empty($this->params['url']['checkboxes'])) {
            $id_arr = $this->params['url']['checkboxes'];
            if ($this->params['url']['act_type'] == 'delete') {
                $this->UserMessage->deleteAll(array('UserMessage.id' => $id_arr));
                $this->UserMessage->deleteAll(array('UserMessage.parent_id' => $id_arr));
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['batch_delete_messages'], $this->admin['id']);
                }
            }
        }
        if ($this->params['url']['search'] != 'search') {
            $this->redirect('/messages/');
        } else {
            $this->redirect('/messages/search/');
        }
    }
    public function product($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('messages_view');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['question_query'],'url' => '/messages/product/');

        $condition = '';
        if (isset($user_id) && $user_id != 'none') {
            $userlist = $this->User->findById($user_id);

            $condition['UserMessage.user_name LIKE'] = '%'.$userlist['User']['name'].'%';
        }
        if (isset($this->params['url']['mods']) && $this->params['url']['mods'] != '') {
            $condition['UserMessage.msg_type ='] = $this->params['url']['mods'];
            $this->set('modssss', $this->params['url']['mods']);
        }
        if (isset($this->params['url']['title']) && $this->params['url']['title'] != '') {
            $condition['UserMessage.msg_title LIKE'] = '%'.$this->params['url']['title'].'%';
            $this->set('titles', $this->params['url']['title']);
        }
        if (isset($this->params['url']['end_time']) && $this->params['url']['end_time'] != '') {
            $condition['UserMessage.created <'] = $this->params['url']['end_time'];
            $this->set('end_time', $this->params['url']['end_time']);
        }
        if (isset($this->params['url']['start_time']) && $this->params['url']['start_time'] != '') {
            $condition['UserMessage.created >'] = $this->params['url']['start_time'];
            $this->set('start_time', $this->params['url']['start_time']);
        }
        $condition['UserMessage.parent_id ='] = 0;
        $condition['UserMessage.type ='] = 'P';
        $total = $this->UserMessage->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'UserMessage';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'messages','action' => 'product','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserMessage');
        $this->Pagination->init($condition, $parameters, $options);
        $UserMessage_list = $this->UserMessage->find('all', array('conditions' => $condition, 'order' => 'created desc', 'limit' => $rownum, 'page' => $page));
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Order');
               //20090722新增
               $this->Order->hasMany = array();
            $this->Order->hasOne = array();
            $order_data = $this->Order->find('all', array('fields' => array('Order.id', 'Order.order_code')));
            $order_list = array();
            foreach ($order_data as $k => $v) {
                $order_list[$v['Order']['id']] = $v;
            }
            $this->set('order_list', $order_list);
        }
        $this->Product->hasMany = array();
        $this->Product->hasOne = array('ProductI18n' => array(
                                                  'className' => 'ProductI18n',
                                                  'order' => '',
                                                  'dependent' => true,
                                                  'foreignKey' => 'product_id',
                                                 ),
                        );
        $fields[] = 'Product.id';
        $fields[] = 'ProductI18n.name';
        $this->Product->set_locale($backend_locale);
        $products_data = $this->Product->find('all', array('order' => 'Product.id desc', 'fields' => $fields));

        $products_list = array();
        foreach ($products_data as $k => $v) {
            $products_list[$v['Product']['id']] = $v['ProductI18n']['name'];
        }
        $this->set('products_list', $products_list);
        $this->set('msg_type', $this->ld['message']);
        //end 
        $this->set('UserMessage_list', $UserMessage_list);
        $this->pageTitle = $this->ld['question_query'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name'];
    }

    public function product_view($id)
    {
        /*判断权限*/
        $this->operator_privilege('messages_view');
        /*end*/
        $this->pageTitle = $this->ld['reply_questions'].' - '.$this->ld['question_query'].' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['question_query'],'url' => '/messages/product/');
        $this->navigations[] = array('name' => $this->ld['reply_questions'],'url' => '');
        $usermessage = $this->UserMessage->findById($id);
        $user_info = $this->User->findById($usermessage['UserMessage']['user_id']);
        $this->navigations[] = array('name' => $user_info['User']['name'],'url' => '');
        $wh['parent_id'] = $id;
        $restore = $this->UserMessage->find($wh);
        if ($this->RequestHandler->isPost()) {
            if ($this->data['UserMessage']['msg_content'] != '') {
                $this->UserMessage->deleteAll('UserMessage.parent_id='.$this->data['UserMessage']['parent_id']);
                $this->data['UserMessage']['created'] = date('Y-m-d H:i:s');
                $this->UserMessage->save($this->data);
                $this->UserMessage->updateAll(
                          array('UserMessage.status' => '1'),
                          array('UserMessage.id' => $id)
                       );
                $usermessage = $this->UserMessage->findById($id);
                $this->MailTemplate->set_locale($this->locale);
                $template = 'message_revert';
                $template = $this->MailTemplate->find("code = '$template' and status = '1'");
                $shop_name = $this->configs['shop_name'];
                $sent_date = date('Y-m-d H:m:s');
                $restore_content = $restore['UserMessage']['msg_content'];
                $msg_content = $usermessage['UserMessage']['msg_content'];
                $user_name = $usermessage['UserMessage']['user_name'];
                $created = $usermessage['UserMessage']['created'];
                $msg_title = $usermessage['UserMessage']['msg_title'];
                /* 商店网址 */
                $shop_url = $this->server_host.$this->webroot;
                $url = $shop_url.'/user/messages/';
                //读模板
                $template = 'message_revert';
                $this->MailTemplate->set_locale($this->locale);
                $template = $this->MailTemplate->find("code = '$template' and status = '1'");
                //模板赋值
                $html_body = $template['MailTemplateI18n']['html_body'];
                eval("\$html_body = \"$html_body\";");
                $text_body = $template['MailTemplateI18n']['text_body'];
                eval("\$text_body = \"$text_body\";");
                //主题赋值
                $title = $template['MailTemplateI18n']['title'];
                eval("\$title = \"$title\";");
                $mailsendqueue = array(
                       'sender_name' => $shop_name,//发送从姓名
                       'receiver_email' => $user_name.';'.$usermessage['UserMessage']['user_email'],//接收人姓名;接收人地址
                     'cc_email' => ';',//抄送人
                     'bcc_email' => ';',//暗送人
                      'title' => $title,//主题 
                       'html_body' => $html_body,//内容
                      'text_body' => $text_body,//内容
                     'sendas' => 'html',
                 );
                $this->Email->send_mail($this->locale, $this->configs['email_the_way'], $mailsendqueue, $this->configs);
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['reply_message'].':id '.$id.' '.$msg_title, $this->admin['id']);
                }
                $this->flash($this->ld['message'].' '.$msg_title.$this->ld['reply_success'], '/messages/product/', 10);
            } else {
                $this->flash('- '.$this->ld['reply_content_not_empty'], '/messages/product_view/'.$id, 10, false);
            }
        }
        $this->set('usermessage', $usermessage);
        if (!empty($restore)) {
            $this->set('restore', $restore);
        }
    }
}
