<?php

/*****************************************************************************
 * Seevia 用户留言
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
uses('sanitize');
/**
 *这是一个名为 MessagesController 的邮件控制器.
 */
class MessagesController extends AppController
{
    /*
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses
    */
    public $name = 'Messages';
    public $components = array('Pagination','Email'); // Added 
    public $helpers = array('Pagination'); // Added
    public $uses = array('UserMessage','Order','MailTemplate','Application','ApplicationConfig','ApplicationConfigI18n','UserFans','Blog');
    public $layout = 'usercenter';
    /**
     *函数 index 进入留言查询.
     *
     *@param $page 
     *@param $page2 
     */
    //查看留言 - Start

    public function index($page = 1)
    {
        //登录验证
        $this->checkSessionUser();

        $this->page_init();      //页面初始化
        $this->layout = 'usercenter';

        //页面标题
        $this->pageTitle = $this->ld['account_message'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_title'];

        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['account_message'], 'url' => '');
        $this->set('ur_heres', $this->ur_heres);

        $user_id = $_SESSION['User']['User']['id'];
        if (isset($_SESSION['User']['User']['id'])) {
            //pr($_SESSION['User']['User']['id']);
            $id = $_SESSION['User']['User']['id'];
            $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
            $this->set('user_list', $user_list);
            //pr($user_list);
            //粉丝数量
            $fans = $this->UserFans->find_fanscount_byuserid($id);
            $this->set('fanscount', $fans);
            //日记数量
            $blog = $this->Blog->find_blogcount_byuserid($id);
            $this->set('blogcount', $blog);
            //关注数量
            $focus = $this->UserFans->find_focuscount_byuserid($id);
            $this->set('focuscount', $focus);
        }
        $condition = " UserMessage.user_id='".$user_id."' and UserMessage.parent_id = 0";

        if (isset($this->params['url']['status']) && !empty($this->params['url']['status'])) {
            $this->set('status', $this->params['url']['status']);
        }
       //分页start
        //get参数
        $limit = 5;
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'messages', 'action' => 'index', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'UserMessage');
        $page = $this->Pagination->init($condition, $parameters, $options); // Added
        //分页end


       //取得我的留言 
       $my_messages = $this->UserMessage->find('all', array('conditions' => $condition, 'limit' => $limit, 'page' => $page, 'order' => 'created'));

       //取得我未读的留言 
       $condition .= ' and UserMessage.is_read=0';
        $condition .= ' and UserMessage.status=1';
        $my_messages2 = $this->UserMessage->find('all', array('conditions' => $condition));

        if (empty($my_messages)) {
            $my_messages = array();
        }
        if (empty($my_messages2)) {
            $my_messages2 = array();
        }
        $my_messages_parent_id = array();
        $my_messages_parent_id[] = 0;
        $p_ids = array();
        $o_ids = array();
       //取得我留言的回复 
       foreach ($my_messages as $k => $v) {
           $my_messages_parent_id[] = $v['UserMessage']['id'];
           if ($v['UserMessage']['type'] == 'P' && $v['UserMessage']['value_id'] > 0) {
               $p_ids[] = $v['UserMessage']['value_id'];
           }
           if ($v['UserMessage']['type'] == 'O' && $v['UserMessage']['value_id'] > 0) {
               $o_ids[] = $v['UserMessage']['value_id'];
           }
       }
        $p_id = array('UserMessage.parent_id' => $my_messages_parent_id);
        $replies_list = $this->UserMessage->get_user_message($p_id);

        $replies_list_format = array();
        if (is_array($replies_list) && sizeof($replies_list) > 0) {
            foreach ($replies_list as $k => $v) {
                $replies_list_format[$v['UserMessage']['parent_id']][] = $v;
            }
        }
        foreach ($my_messages as $k => $v) {
            if (isset($replies_list_format[$v['UserMessage']['id']])) {
                $my_messages[$k]['Reply'] = $replies_list_format[$v['UserMessage']['id']];
            }
        }

       //取得我未读的留言 
       $my_messages2_parent_id = array();
        $my_messages2_parent_id[] = 0;
        $p_ids2 = array();
        $o_ids2 = array();
        $p_id2 = array();
       //取得未读的留言 回复 
       foreach ($my_messages2 as $km => $vm) {
           $my_messages2_parent_id[] = $vm['UserMessage']['id'];
           if ($v['UserMessage']['type'] == 'P' && $vm['UserMessage']['value_id'] > 0) {
               $p_ids2[] = $vm['UserMessage']['value_id'];
           }
           if ($vm['UserMessage']['type'] == 'O' && $vm['UserMessage']['value_id'] > 0) {
               $o_ids2[] = $vm['UserMessage']['value_id'];
           }
       }
        $p_id2[] = array('UserMessage.parent_id' => $my_messages2_parent_id);
        $p_id2[] = array('UserMessage.is_read' => 0);
        $replies_list2 = $this->UserMessage->get_user_message($p_id2);
        $replies_list_format2 = array();
        if (is_array($replies_list2) && sizeof($replies_list2) > 0) {
            foreach ($replies_list2 as $kr => $vr) {
                $replies_list_format2[$vr['UserMessage']['parent_id']][] = $vr;
            }
        }
        $reply_count = 0;
        foreach ($my_messages2 as $kmm => $vmm) {
            if (isset($replies_list_format2[$vmm['UserMessage']['id']])) {
                $my_messages2[$kmm]['Reply'] = $replies_list_format2[$vmm['UserMessage']['id']];
                ++$reply_count;
            }
        }
        $this->set('reply_count', $reply_count);
        $this->set('my_messages', $my_messages);
        $this->set('my_messages2', $my_messages2);
    }

    /**
     *函数 AddMessage 添加留言.
     */
    public function AddMessage()
    {
        $mrClean = new Sanitize();
        $order_id = 0;
         //获取页面参数
         if (isset($_POST['order_id'])) {
             $order_id = $_POST['order_id'];
             $message_type = 0;
         } else {
             $message_type = 0;
         }
        $created = date('Y-m-d H:i:s');

        $messages = array(
            'msg_title' => isset($_POST['title'])   ? trim($_POST['title'])  : '',
            'msg_content' => isset($_POST['messagecontent'])   ? trim($_POST['messagecontent'])  : 0,
            'msg_type' => isset($message_type)   ? trim($message_type)  : '',
            'type' => isset($_POST['order_type'])   ? trim($_POST['order_type'])  : '',
            'user_id' => $_SESSION['User']['User']['id'],
            'user_email' => $_SESSION['User']['User']['email'],
            'user_name' => $_SESSION['User']['User']['name'],
            'value_id' => $order_id,
            'status' => 0,
            'created' => $created,
            );

        $this->UserMessage->save(array('UserMessage' => $messages));//添加留言 
        //如果安装了邮件系统发邮件通知
                $shop_name = $this->configs['shop_name'];
        $send_date = date('Y-m-d');
        $email_text = '';
        $email_text .= $this->ld['nickname'].':'.trim($_SESSION['User']['User']['name']).'<br>';
        $email_text .= $this->ld['subject'].':'.trim($_POST['title']).'<br>';
        $email_text .= $this->ld['message'].':'.trim($_POST['messagecontent']).'<br><br>';
        $email_text .= $this->ld['date'].':'.$send_date;
        $template = $this->MailTemplate->find("code = 'contact_us' and status = 1");
        $template_str = $template['MailTemplateI18n']['html_body'];
        $this->Email->smtpHostNames = ''.$this->configs['mail-smtp'].'';
        $this->Email->smtpUserName = ''.$this->configs['mail-account'].'';
        $this->Email->smtpPassword = ''.$this->configs['mail-password'].'';
        $this->Email->is_ssl = $this->configs['mail-ssl'];
        $this->Email->is_mail_smtp = $this->configs['mail-service'];
        $this->Email->smtp_port = $this->configs['mail-port'];
        $this->Email->from = ''.$this->configs['mail-account'].'';
                //从应用表查处要发的邮件地址
//				$config_info=$this->ApplicationConfig->find('first',array('conditions'=>array('ApplicationConfig.code'=>'APP-VIP-EMAIL')));
//				$email=$this->ApplicationConfigI18n->find('first',array('conditions'=>array('ApplicationConfigI18n.app_config_id'=>$config_info['ApplicationConfig']['id'],'ApplicationConfigI18n.locale'=>$this->locale)));
                $receiver_email = $this->configs['vip-email'];
        $this->Email->to = ''.$this->data['Contact']['email'].'';
        $this->Email->fromName = $shop_name;
        $this->Email->html_body = ''.$template_str.'';
        $text_body = $template['MailTemplateI18n']['text_body'];
                 //eval("\$text_body = \"$text_body\";");
                $this->Email->text_body = $text_body;
        $subject = $template['MailTemplateI18n']['title'];
        eval("\$subject = \"$subject\";");
        $mail_send_queue = array(
                                        'id' => '',
                                        'sender_name' => $shop_name,
                                        'receiver_email' => $receiver_email,
                                        'cc_email' => ';',
                                        'bcc_email' => ';',
                                        'title' => '留言通知邮件',
                                        'html_body' => $email_text,
                                        'text_body' => $email_text,
                                        'sendas' => 'html',
                                        'flag' => 0,
                                        'pri' => 0,
                                        );
        $this->Email->send_mail($this->locale, 1, $mail_send_queue, $this->configs);
        $this->redirect('/messages');//显示的页面
    }

    /**
     *函数 select_message 查看留言内容.
     */
    public function select_message($id)
    {
        $condition = array();
        $condition['or']['id'] = $id;
        $condition['or']['parent_id'] = $id;

        $this->UserMessage->updateAll(
               array('UserMessage.is_read' => 1),
               array($condition)
           );

        $my_message = $this->UserMessage->find('first', array('conditions' => array('UserMessage.id' => $id)));
        $reply_message = $this->UserMessage->find('first', array('conditions' => array('UserMessage.parent_id' => $id)));
        $result['msg_content'] = $my_message['UserMessage']['msg_content'];
        if (!empty($reply_message)) {
            $result['reply_content'] = $reply_message['UserMessage']['msg_content'];
        }
        Configure::write('debug', 0);
        die(json_encode($result));
    }

    public function select_message2($id)
    {
        $condition = array();
        $condition['or']['id'] = $id;
        $condition['or']['parent_id'] = $id;

        $this->UserMessage->updateAll(
           array('UserMessage.is_read' => 1),
           array($condition)
           );
        $condition = array();
           //取得我未读的留言
           $condition['UserMessage.is_read'] = 0;
        $condition['UserMessage.status'] = 1;
        $count_unread_msg = $this->UserMessage->find('count', array('conditions' => $condition));
        $result['count'] = $count_unread_msg;
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }
    /**
     *函数 user_delete_message 删除所购商品留言信息.
     */
    public function delete_message($id, $status)
    {

         //登录验证
        $this->checkSessionUser();

        $this->UserMessage->deleteAll("UserMessage.id='".$id."'  and UserMessage.user_id = '".$_SESSION['User']['User']['id']."'");
        if ($status > 0) {
            $this->redirect('/messages?status=2');
        } else {
            $this->redirect('/messages');
        }
    }
}
