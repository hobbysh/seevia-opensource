<?php

/*****************************************************************************
 * Seevia 杂志管理
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
class EmailListsController extends AppController
{
    public $name = 'EmailLists';
    public $helpers = array('Html','Tinymce','fck','Ckeditor','Pagination');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('MailTemplate','MailTemplateI18n','Resource','UserRank','NewsletterList','MailSendQueue','User','OperatorLog','UserGroup');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('email_lists_view');
        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        /*end*/
        $this->set('title_for_layout', $this->ld['subscription'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');

        $this->MailTemplate->set_locale($this->locale);
        $condition['type'] = 'magazine';

        $total = $this->MailTemplate->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'UserChat';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'email_lists','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'MailTemplate');
        $this->Pagination->init($condition, $parameters, $options);

        $MailTemplate_list = $this->MailTemplate->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'MailTemplate.created desc'));

        //资源库信息
        $this->Resource->set_locale($this->locale);
        $Resource_info = $this->Resource->getformatcode($this->locale, false);
        $this->UserRank->set_locale($this->locale);
        $user_rank_data = $this->UserRank->find('all');
        $this->set('MailTemplate_list', $MailTemplate_list);
        $this->set('Resource_info', $Resource_info);
        $this->set('user_rank_data', $user_rank_data);
    }

    public function edit($id)
    {
        /*判断权限*/
        $this->operator_privilege('email_lists_edit');
        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        //pr($this->configs);
        /*end*/
        $this->set('title_for_layout', $this->ld['edit'].' - '.$this->ld['subscription'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $id = $this->data['MailTemplate']['id'];

            //$this->MailTemplate->deleteall("id = '$id'",false); 
            //$this->MailTemplateI18n->deleteall("mail_template_id = '$id'",false);
            foreach ($this->data['MailTemplateI18n'] as $v) {
                $mailTemplateI18n_info = array(
                   'id' => isset($v['id']) ? $v['id'] : '',
                   'locale' => $v['locale'],
                   'mail_template_id' => isset($v['mail_template_id']) ? $v['mail_template_id'] : $id,
                   'title' => isset($v['title']) ? $v['title'] : '',
                   'text_body' => isset($v['text_body']) ? $v['text_body'] : '',
                   'html_body' => isset($v['html_body']) ? $v['html_body'] : '',
                   'sms_body' => isset($v['sms_body']) ? $v['sms_body'] : '',
                   'description' => isset($v['html_body']) ? $v['description'] : '',
                );
                $this->MailTemplateI18n->saveall(array('MailTemplateI18n' => $mailTemplateI18n_info));//更新多语言
            }
            $this->data['MailTemplate']['type'] = 'magazine';

            $this->MailTemplate->save($this->data); //保存
            $this->set('type', true);
            foreach ($this->data['MailTemplateI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $title = $v['title'];
                    eval("\$title = \"$title\";");
                    $userinformation_name = $title;
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->configs['edit'].':id '.$id.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/email_lists/');
            //$this->flash("杂志 ".$userinformation_name." 编辑成功。点击这里继续编辑该杂志",'/email_lists/edit/'.$id,10);
        }
        $this->data = $this->MailTemplate->localeformat($id);
        $group_list = $this->UserGroup->find('list', array('conditions' => array('UserGroup.status' => 1), 'fields' => 'UserGroup.id,UserGroup.name'));
        $this->set('group_list', $group_list);//绑定分组搜索下拉
        $this->set('this->data', $this->data);
        //leo20090722导航显示
    //	$this->navigations[] = array('name'=>$this->data["MailTemplateI18n"][$this->locale]["title"],'url'=>'');
    //pr(	$this->data );
    }
    //发送短信
    public function send_sms($smssendqueue)
    {
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('SmsSendQueue');
        }else{
            return false;
        }
        $uid = 'SV00038';//用户名
        $passwd = '111111';//密码
        //$message=$sms[0]['sms_body'].date("Y-m-d H:i:s")."【实玮网络】";
        //$telphone="13818606243";
        //pr($smssendqueue);die;
        foreach ($smssendqueue as $k => $v) {
            $client = new SoapClient('http://mb345.com:999/ws/LinkWS.asmx?wsdl', array('encoding' => 'UTF-8'));
            $sendParam = array(
                'CorpID' => $uid,
                'Pwd' => $passwd,
                'Mobile' => $v['SmsSendQueue']['phone'],
                'Content' => $v['SmsSendQueue']['content'],
                'Cell' => '',
                'SendTime' => '',
                );
            //pr($sendParam);die();
            $result = $client->BatchSend($sendParam);
            $result = $result->BatchSendResult;
        }
    }

    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $pn = $this->MailTemplateI18n->find('list', array('fields' => array('MailTemplateI18n.mail_template_id', 'MailTemplateI18n.title'), 'conditions' => array('MailTemplateI18n.mail_template_id' => $id, 'MailTemplateI18n.locale' => $this->locale)));
        $this->MailTemplate->deleteAll("MailTemplate.id='".$id."'");
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_magazine'].':id '.$id.@$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function add()
    {

        /*判断权限*/
        $this->operator_privilege('email_lists_add');
        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        /*end*/
        $this->set('title_for_layout', $this->ld['edit'].' - '.$this->ld['subscription'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['MailTemplate']['type'] = 'magazine';

            $this->MailTemplate->save($this->data); //保存
            $this->set('type', true);
            $id = $this->MailTemplate->id;
            //新增多语言
            if (is_array($this->data['MailTemplateI18n'])) {
                foreach ($this->data['MailTemplateI18n'] as $k => $v) {
                    $v['mail_template_id'] = $id;
                    $this->MailTemplateI18n->id = '';
                    $this->MailTemplateI18n->save($v);
                }
            }
            foreach ($this->data['MailTemplateI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $title = $v['title'];
                    eval("\$title = \"$title\";");
                    $userinformation_name = $title;
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].':'.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/email_lists/');
            //$this->flash("杂志 ".$userinformation_name." 添加成功。点击这里继续编辑该杂志",'/email_lists/edit/'.$id,10);
        }
    }
    //选择发送对象和发送方式
    public function insert_email_queue($select_send)
    {
        $this->operator_privilege('email_lists_email');
    //	$mailtemplate_data = $this->MailTemplate->findbyid($id);
        $usermode = $_POST['data']['MailTemplate']['usermode'];
        $toppri = $_POST['data']['MailTemplate']['toppri'];
        $id = $_POST['data']['MailTemplate']['id'];
        $group = $_POST['group_id'];//杂志订阅用户分组
        $mailtemplate_data = $this->MailTemplate->find('first', array('conditions' => array('MailTemplate.id' => $id)));
        //pr($mailtemplate_data);die;
        //模板赋值
        $html_body = $mailtemplate_data['MailTemplateI18n']['html_body'];
        @eval("\$html_body = \"$html_body\";");
        $text_body = $mailtemplate_data['MailTemplateI18n']['text_body'];
        @eval("\$text_body = \"$text_body\";");
        $sms_body = $mailtemplate_data['MailTemplateI18n']['sms_body'];
        @eval("\$sms_body = \"$sms_body\";");
        //主题赋值
        $title = $mailtemplate_data['MailTemplateI18n']['title'];
        eval("\$title = \"$title\";");
        //pr($select_send);die;
        if ($select_send == 'only_send_email') {
            //pr($_POST);die;
            $this->only_send_email($usermode, $html_body, $text_body, $title, $id, $toppri, $group);
        } elseif ($select_send == 'only_send_sms') {
            $this->only_send_sms($usermode, $sms_body);
        } elseif ($select_send = 'send_emailandsms') {
            $this->only_send_email($usermode, $html_body, $text_body, $title, $id, $toppri, $group);
            $this->only_send_sms($usermode, $sms_body);
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_send_email'], $this->admin['id']);
        }
        $this->set('type', true);
        $this->redirect('/email_lists/');
    //	$this->flash("操作成功，点击这里返回列表",'/email_lists/',10);
    }
    //仅发送邮件
    public function only_send_email($usermode, $html_body, $text_body, $title, $id, $toppri, $group)
    {
        if ($usermode == 'newsletter_user') {
            //这是杂志订阅用户
            $condition['status'] = '1';
            if (isset($group) && !empty($group)) {
                $condition['group_id'] = $group;
            }
            $newsletterlist_data = $this->NewsletterList->find('all', array('conditions' => $condition));
            foreach ($newsletterlist_data as $k => $v) {
        	  $unsubscribe_link=$this->server_host."/newsletter/cancel/".$v['NewsletterList']['id']."/".(md5($v['NewsletterList']['id'].$v['NewsletterList']['email']));
        	  $html_body=str_replace('$unsubscribe_link',$unsubscribe_link,$html_body);
        	  $text_body=str_replace('$unsubscribe_link',$unsubscribe_link,$text_body);
                $shop_name = $this->configs['shop_name'];//template
                $mailsendqueue = array(
			'sender_name' => $shop_name,//发送从姓名
			'receiver_email' => $v['NewsletterList']['email'].';'.$v['NewsletterList']['email'],//接收人姓名;接收人地址
			'cc_email' => ';',//抄送人
			'bcc_email' => ';',//暗送人
			'title' => $title,//主题 
			'html_body' => $html_body,//内容
			'text_body' => $text_body,//内容
			'sendas' => 'html',
			'pri' => $toppri,
                );
                $this->MailSendQueue->saveAll(array('MailSendQueue' => $mailsendqueue));//保存邮件队列
                $this->Email->send_mail($this->locale,$this->configs['mail-setting'], $mailsendqueue, $this->configs);
            }
            $mailpdate = array(
                'last_send' => date('Y-m-d H:i:s'),
                'id' => $id,
            );
            $this->MailTemplate->save($mailpdate);
        } elseif ($usermode == 'user_all') {
            //全体会员
            $condition['status'] = '1';
            $user_data = $this->User->find('all', array('conditions' => $condition));

            foreach ($user_data as $k => $v) {
                $shop_name = $this->configs['shop_name'];//template
                $mailsendqueue = array(
                           'sender_name' => $shop_name,//发送从姓名
                           'receiver_email' => $v['User']['name'].';'.$v['User']['email'],//接收人姓名;接收人地址
                         'cc_email' => ';',//抄送人
                         'bcc_email' => ';',//暗送人
                          'title' => $title,//主题 
                           'html_body' => $html_body,//内容
                          'text_body' => $text_body,//内容
                         'sendas' => 'html',
                        'pri' => $toppri,
                     );
                $this->MailSendQueue->saveAll(array('MailSendQueue' => $mailsendqueue));//保存邮件队列
                $this->Email->send_mail($this->locale, $this->configs['mail-setting'], $mailsendqueue, $this->configs);
            }
            $mailpdate = array(
                'last_send' => date('Y-m-d H:i:s'),
                'id' => $id,
            );
            $this->MailTemplate->save($mailpdate);
        } elseif ($usermode == 'user_email_flag') {
            //订阅会员
            $mailpdate = array(
                'user_email_flag' => 1,
                'id' => $id,
            );
            $this->MailTemplate->save($mailpdate);
        } elseif ($usermode > 0) {
            $condition['rank'] = $usermode;
            $condition['status'] = '1';
            $user_data = $this->User->find('all', array('conditions' => $condition));
            foreach ($user_data as $k => $v) {
                $shop_name = $this->configs['shop_name'];//template
                $mailsendqueue = array(
                           'sender_name' => $shop_name,//发送从姓名
                           'receiver_email' => $v['User']['email'].';'.$v['User']['name'],//接收人姓名;接收人地址
                         'cc_email' => ';',//抄送人
                         'bcc_email' => ';',//暗送人
                          'title' => $title,//主题 
                           'html_body' => $html_body,//内容
                          'text_body' => $text_body,//内容
                         'sendas' => 'html',
                        'pri' => $toppri,
                     );
                $this->MailSendQueue->saveAll(array('MailSendQueue' => $mailsendqueue));//保存邮件队列
                $this->Email->send_mail($this->locale, $this->configs['mail-setting'], $mailsendqueue, $this->configs);
            }
            $mailpdate = array(
                'last_send' => date('Y-m-d H:i:s'),
                'id' => $id,
            );
            $this->MailTemplate->save($mailpdate);
        }
    }
    //仅发送短信
    public function only_send_sms($usermode, $sms_body)
    {
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('SmsSendQueue');
            $this->loadModel('SmsSendHistory');
        }else{
            return false;
        }
        if ($usermode == 'newsletter_user') {
            $condition['status'] = '1';
            $newsletterlist_data = $this->NewsletterList->find('all', array('conditions' => $condition));
            $message = $sms_body.' '.date('Y-m-d H:i:s').'【实玮网络】';
            foreach ($newsletterlist_data as $k => $v) {
                $shop_name = $this->configs['shop_name'];//template
                if ($v['NewsletterList']['mobile'] != '') {
                    $smssendqueue = array(
                               'phone' => $v['NewsletterList']['mobile'],//手机
                               'content' => $message,//短信内容
                     );
                    $this->SmsSendQueue->saveAll(array('SmsSendQueue' => $smssendqueue));//保存短信队列
                }
            }
            //从短信队列中读取要发送的短信
            $smssendqueue = $this->SmsSendQueue->find('all', array('conditions' => array('flag' => 0)));
            //pr($smssendqueue);die;
            //$this->send_sms($smssendqueue);
            foreach ($smssendqueue as $k => $v) {
                $sms_send_history = array(
                    'phone' => $v['SmsSendQueue']['phone'],
                    'content' => $v['SmsSendQueue']['content'],
                    'send_date' => date('Y-m-d H:i:s'),
                    'flag' => '1',
                );
                $sms_send_queue = array(
                    'id' => $v['SmsSendQueue']['id'],
                    'send_date' => date('Y-m-d H:i:s'),
                    'flag' => '1',
                );
                $this->SmsSendHistory->saveAll(array('SmsSendHistory' => $sms_send_history));
                $this->SmsSendQueue->saveAll(array('SmsSendQueue' => $sms_send_queue));
            }
        } elseif ($usermode == 'user_all') {
            $condition['status'] = '1';
            $user_data = $this->User->find('all', array('conditions' => $condition));
            $message = $sms_body.' '.date('Y-m-d H:i:s').'【实玮网络】';
            foreach ($user_data as $k => $v) {
                $shop_name = $this->configs['shop_name'];//template
                if ($v['User']['mobile'] != '') {
                    $smssendqueue = array(
                               'phone' => $v['User']['mobile'],//手机
                               'content' => $message,//短信内容
                     );
                    $this->SmsSendQueue->saveAll(array('SmsSendQueue' => $smssendqueue));//保存短信队列
                }
            }
            //从短信队列中读取要发送的短信
            $smssendqueue = $this->SmsSendQueue->find('all', array('conditions' => array('flag' => 0)));
            //$this->send_sms($smssendqueue);
            foreach ($smssendqueue as $k => $v) {
                $sms_send_history = array(
                    'phone' => $v['phone'],
                    'content' => $v['content'],
                    'send_date' => date('Y-m-d H:i:s'),
                    'flag' => '1',
                );
                $sms_send_queue = array(
                    'id' => $v['SmsSendQueue']['id'],
                    'send_date' => date('Y-m-d H:i:s'),
                    'flag' => '1',
                );
                $this->SmsSendHistory->saveAll(array('SmsSendHistory' => $sms_send_history));
                $this->SmsSendQueue->saveAll(array('SmsSendQueue' => $sms_send_queue));
            }
        } elseif ($usermode == 'user_email_flag') {
            $condition['status'] = '1';
            $condition['email_flag'] = '1';
            $user_data = $this->User->find('all', array('conditions' => $condition));
            $message = $sms_body.' '.date('Y-m-d H:i:s').'【实玮网络】';
            foreach ($user_data as $k => $v) {
                $shop_name = $this->configs['shop_name'];//template
                if ($v['User']['mobile'] != '') {
                    $smssendqueue = array(
                               'phone' => $v['User']['mobile'],//手机
                               'content' => $message,//短信内容
                     );
                    $this->SmsSendQueue->saveAll(array('SmsSendQueue' => $smssendqueue));//保存短信队列
                }
            }
            //从短信队列中读取要发送的短信
            $smssendqueue = $this->SmsSendQueue->find('all', array('conditions' => array('flag' => 0)));
            //$this->send_sms($smssendqueue);
            foreach ($smssendqueue as $k => $v) {
                $sms_send_history = array(
                    'phone' => $v['phone'],
                    'content' => $v['content'],
                    'send_date' => date('Y-m-d H:i:s'),
                    'flag' => '1',
                );
                $sms_send_queue = array(
                    'id' => $v['SmsSendQueue']['id'],
                    'send_date' => date('Y-m-d H:i:s'),
                    'flag' => '1',
                );
                $this->SmsSendHistory->saveAll(array('SmsSendHistory' => $sms_send_history));
                $this->SmsSendQueue->saveAll(array('SmsSendQueue' => $sms_send_queue));
            }
        }
    }
    //发送邮件测试
    public function send_email_test($email, $mailid)
    {
        //pr($_POST['data']['MailTemplateI18n'][0]['html_body']);die;
        //模板赋值
        $html_body = $_POST['data']['MailTemplateI18n'][0]['html_body'];
        eval("\$html_body = \"$html_body\";");
        $text_body = $_POST['data']['MailTemplateI18n'][0]['text_body'];
        eval("\$text_body = \"$text_body\";");
        //主题赋值
        $title = $_POST['data']['MailTemplateI18n'][0]['title'];
        eval("\$title = \"$title\";");
        if ($this->RequestHandler->isPost()) {
            $id = $this->data['MailTemplate']['id'];

            //$this->MailTemplate->deleteall("id = '$id'",false); 
            //$this->MailTemplateI18n->deleteall("mail_template_id = '$id'",false); 
            foreach ($this->data['MailTemplateI18n'] as $v) {
                $mailTemplateI18n_info = array(
                   'id' => isset($v['id']) ? $v['id'] : '',
                   'locale' => $v['locale'],
                   'mail_template_id' => isset($v['mail_template_id']) ? $v['mail_template_id'] : $id,
                   'title' => isset($v['title']) ? $v['title'] : '',
                   'text_body' => isset($v['text_body']) ? $v['text_body'] : '',
                   'html_body' => isset($v['html_body']) ? $v['html_body'] : '',
                   'sms_body' => isset($v['sms_body']) ? $v['sms_body'] : '',
                   'description' => isset($v['html_body']) ? $v['description'] : '',
                );
                $this->MailTemplateI18n->saveAll(array('MailTemplateI18n' => $mailTemplateI18n_info));//更新多语言
            }
            $this->data['MailTemplate']['type'] = 'magazine';

            $this->MailTemplate->save($this->data); //保存
            $this->set('type', true);
            foreach ($this->data['MailTemplateI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $title = $v['title'];
                    eval("\$title = \"$title\";");
                    $userinformation_name = $title;
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->configs['edit'].':'.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/email_lists/');
            //$this->flash("杂志 ".$userinformation_name." 编辑成功。点击这里继续编辑该杂志",'/email_lists/edit/'.$id,10);
        }
        $shop_name = $this->configs['shop_name'];//template
        //$this->operator_privilege('email_lists_sms');
        $mailsendqueue = array(
                           'sender_name' => $shop_name,//发送从姓名
                           'receiver_email' => $email.';'.$email,//接收人姓名;接收人地址
                         'cc_email' => ';',//抄送人
                         'bcc_email' => ';',//暗送人
                          'title' => $title,//主题 
                           'html_body' => $html_body,//内容
                          'text_body' => $text_body,//内容
                         'sendas' => 'html',
                     );
        $this->MailSendQueue->saveAll(array('MailSendQueue' => $mailsendqueue));//保存邮件队列
        $this->Email->send_mail($this->locale, $this->configs['mail-setting'], $mailsendqueue, $this->configs);
        $this->redirect('/email_lists/');
    }
    //发送短信测试
    public function send_sms_test($phone, $mailid)
    {
        //pr($_POST['data']['MailTemplateI18n'][0]['sms_body']);die;
        //pr($phone);die;
        //模板赋值
//		$html_body=$_POST['data']['MailTemplateI18n'][0]['html_body'];
//		eval("\$html_body = \"$html_body\";");
        $sms_body = $_POST['data']['MailTemplateI18n'][0]['sms_body'];
        eval("\$sms_body = \"$sms_body\";");
        $message = $sms_body.' '.date('Y-m-d H:i:s').'【实玮网络】';
        //主题赋值
        $title = $_POST['data']['MailTemplateI18n'][0]['title'];
        eval("\$title = \"$title\";");
        if ($this->RequestHandler->isPost()) {
            $id = $this->data['MailTemplate']['id'];

            //$this->MailTemplate->deleteall("id = '$id'",false); 
            //$this->MailTemplateI18n->deleteall("mail_template_id = '$id'",false); 
            foreach ($this->data['MailTemplateI18n'] as $v) {
                $mailTemplateI18n_info = array(
                   'id' => isset($v['id']) ? $v['id'] : '',
                   'locale' => $v['locale'],
                   'mail_template_id' => isset($v['mail_template_id']) ? $v['mail_template_id'] : $id,
                   'title' => isset($v['title']) ? $v['title'] : '',
                   'text_body' => isset($v['text_body']) ? $v['text_body'] : '',
                   'html_body' => isset($v['html_body']) ? $v['html_body'] : '',
                   'sms_body' => isset($v['sms_body']) ? $v['sms_body'] : '',
                   'description' => isset($v['html_body']) ? $v['description'] : '',
                );
                $this->MailTemplateI18n->saveAll(array('MailTemplateI18n' => $mailTemplateI18n_info));//更新多语言
            }
            $this->data['MailTemplate']['type'] = 'magazine';

            $this->MailTemplate->save($this->data); //保存
            $this->set('type', true);
            foreach ($this->data['MailTemplateI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $title = $v['title'];
                    eval("\$title = \"$title\";");
                    $userinformation_name = $title;
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->configs['edit'].':'.$userinformation_name, $this->admin['id']);
            }

            //$this->flash("杂志 ".$userinformation_name." 编辑成功。点击这里继续编辑该杂志",'/email_lists/edit/'.$id,10);
        }
        $shop_name = $this->configs['shop_name'];//template
        
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('SmsSendQueue');
            $this->loadModel('SmsSendHistory');
            
            $smssendqueue = array(
                                   'phone' => $phone,//手机
                                   'content' => $message,//短信内容
                         );
            $this->SmsSendQueue->saveAll(array('SmsSendQueue' => $smssendqueue));//保存短信队列
            //从短信队列中读取要发送的短信
            $smssendqueue = $this->SmsSendQueue->find('first', array('conditions' => array('flag' => 0), 'order' => 'created desc'));
            foreach ($smssendqueue as $k => $v) {
                $sms_send_history = array(
                        'phone' => $v['phone'],
                        'content' => $v['content'],
                        'send_date' => date('Y-m-d H:i:s'),
                        'flag' => '1',
                    );
                $sms_send_queue = array(
                        'id' => $v['id'],
                        'send_date' => date('Y-m-d H:i:s'),
                        'flag' => '1',
                    );
                $this->SmsSendHistory->saveAll(array('SmsSendHistory' => $sms_send_history));
                $this->SmsSendQueue->saveAll(array('SmsSendQueue' => $sms_send_queue));
            }
        }
        $this->redirect('/email_lists/');
    }

    //订阅会员列表
    public function email_flag_user($page = 1)
    {
        $this->operator_privilege('newsletter_lists_view');
        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        $this->set('title_for_layout', $this->ld['subscription'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['subscriber'],'url' => '');
        $condition = 'User.email_flag=1';
        $total = $this->User->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'User';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'users','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'User');
        $this->Pagination->init($condition, $parameters, $options);
        if (isset($_GET['email_flag']) && $_GET['email_flag'] == 1) {
            $condition['User.email !='] = '';
            $users_email_id = $this->User->find('list', array('fields' => 'User.id', 'conditions' => $condition));
            if (!empty($users_email_id)) {
                $this->User->updateAll(array('User.email_flag' => '1'), array('User.id' => $users_email_id));
                $this->redirect('/email_lists/');
            }
        }
        $users_list = $this->User->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'created desc'));
        //pr($users_list);
        $this->set('users_list', $users_list);
        $this->set('title_for_layout', $this->ld['subscriber'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function test_mail_notice()
    {
        $this->Application->init('chi');
        $receiver_emails = isset($this->configs['mail-notic']) ? $this->configs['mail-notic'] : '';
            //$template_str =$this->ld['test_mail_received_correct'];
        $template_str = '订阅邮件已发送';
        $title = '';
        $email_id = isset($_POST['email_id']) ? $_POST['email_id'] : '';//订阅邮件id
        if (!empty($email_id)) {
            $template = $this->MailTemplate->localeformat($email_id);
            //pr($template);die;
            $title = $template['MailTemplateI18n'][$this->locale]['title'];
            $template_str = $template['MailTemplateI18n'][$this->locale]['html_body'];
        }
        $mailsendqueue = array(
            'sender_name' => $this->configs['shop_name'],//发送人姓名
            'receiver_email' => $receiver_emails.';'.$receiver_emails,//接收人姓名;接收人地址
            'cc_email' => ';',//抄送人
            'bcc_email' => ';',//抄送人
            'title' => $title,//主题
            'html_body' => $template_str,//内容
            'text_body' => $template_str,//内容
            'sendas' => 'html',
        );
        $this->Email = new EmailComponent();
        $result = $this->Email->send_mail('chi', 1, $mailsendqueue, $this->configs);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

//	//杂志用户订阅列表
//	function journal_user($page=1){
//		$this->operator_privilege('email_flag_user_list');
//		$this->menu_path = array('root'=>'/crm/','sub'=>'/email_lists/');
//		$this->set("title_for_layout",$this->ld['subscription']." - ".$this->configs['shop_name']);
//		$this->navigations[] = array('name'=>$this->ld['manager_customers'],'url'=>'');
//		$this->navigations[] = array('name'=>$this->ld['subscription'],'url'=>'/email_lists/');
//		$this->navigations[] = array('name'=>'杂志订阅会员列表','url'=>'');
//		$condition="User.email_flag=1";
//		$total=$this->User->find('count',array("conditions"=>$condition));
//		$this->configs['show_count'] = $this->configs['show_count']>$total?$total:$this->configs['show_count'];
//		$sortClass='User';
//		if(isset($_GET['page'])&&$_GET['page']!=""){
//			$page=$_GET['page'];
//		}
//		$this->configs["show_count"] = (int)$this->configs["show_count"]?$this->configs["show_count"]:'20';
//		$rownum=!empty($this->configs["show_count"]) ? $this->configs["show_count"]: ((!empty($rownum)) ? $rownum : 20);
//		$parameters['get'] = array();
//		//地址路由参数（和control,action的参数对应）
//		$parameters['route'] = array('controller'=>'users','action'=>'index','page'=>$page,'limit'=>$rownum);
//		$options = Array('page'=>$page,'show'=>$rownum,'total'=>$total,'modelClass'=>'User');
//		$this->Pagination->init($condition,$parameters,$options);
//		if(isset($_GET['email_flag'])&&$_GET['email_flag']==1){
//			$condition["User.email !="]='';
//			$users_email_id=$this->User->find("list",array("fields"=>"User.id","conditions"=>$condition));
//			if(!empty($users_email_id)){
//				$this->User->updateAll(array('User.email_flag'=>"1"),array('User.id'=>$users_email_id));
//				$this->redirect('/email_lists/');
//			}
//		}
//		$users_list=$this->User->find("all",array("conditions"=>$condition,"page"=>$page,"limit"=>$rownum,"order"=>"created desc"));
//		//pr($users_list);
//		$this->set('users_list',$users_list);
//		$this->set("title_for_layout",$this->ld['users_search']." - ".$this->ld['page']." ".$page." - ".$this->configs['shop_name']);
//	}
}
