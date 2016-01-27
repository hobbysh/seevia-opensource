<?php

/*****************************************************************************
 * Seevia 邮件
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 NewsletterController 的控制器
 *新消息控制器.
 */
class NewsletterController extends AppController
{
    /*
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses
    */
    public $name = 'Newsletter';
    public $helpers = array('Html');
    public $uses = array('NewsletterList','MailTemplate');
    public $components = array('RequestHandler','Email');

    /**
     * 小纸条添加.
     */
    public function add()
    {
        $result['type'] = 2;
        $result['msg'] = $this->ld['subscribe'].$this->ld['failed'];
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            if ($this->NewsletterList->check_unique_email($_POST['email'])) {
                $result['type'] = 1;
                $result['msg'] = $this->ld['not_repeat_subscribe'];
            } else {
                $email = array(
                                'id' => '',
                                'status' => 1,
                                'email' => $_POST['email'],
                                );
                if ($this->NewsletterList->check_unique_email_by_email($_POST['email'])) {
                    $letter_list = $this->NewsletterList->findbyemail($_POST['email']);
                    $id = $letter_list['NewsletterList']['id'];
                } else {
                    $this->NewsletterList->save($email);
                    $id = $this->NewsletterList->id;
                }
                $result['email'] = $_POST['email'];
                $result['msg'] = $this->ld['subscribe'].$this->ld['successfully'];
                /* 发送激活邮件 */
                $shop_name = $this->configs['shop_name'];
                $send_date = date('Y-m-d');
                //生成 md5加密 code  == id + email
                $code = md5($id.$_POST['email']);
                $url = $this->server_host.$this->webroot.'newsletter/verify/'.$id.'/'.$code.'/';
                //$url = "/$id/$code";
                $template = $this->MailTemplate->find("code = 'news_letter_lists' and status = 1");
                $template_str = $template['MailTemplateI18n']['html_body'];
                /* 商店网址 */
                $shop_url = $this->server_host.$this->webroot;
                eval("\$template_str = \"$template_str\";");
                $text_body = $template['MailTemplateI18n']['text_body'];
                eval("\$text_body = \"$text_body\";");
                $subject = $template['MailTemplateI18n']['title'];
                eval("\$subject = \"$subject\";");
                $mailsendqueue = array(
                    'sender_name' => $shop_name,//发送从姓名
                    'receiver_email' => ';'.$to_email,//接收人姓名;接收人地址
                    'cc_email' => ';',//抄送人
                    'bcc_email' => ';',//暗送人
                    'title' => $subject,//主题 
                    'html_body' => $template_str,//内容
                    'text_body' => $text_body,//内容
                    'sendas' => 'html',
                );
                $this->Email->send_mail(LOCALE, $this->configs['email_the_way'], $mailsendqueue);

                if ($this->Email->send_mail(LOCALE, $this->configs['email_the_way'], $mailsendqueue)) {
                    $result['type'] = 0;
                } else {
                    $result['type'] = 1;
                }
            }
        }
        die(json_encode($result));
            //$this->set('result',$result);
            $this->layout = 'ajax';
    }
    /**
     *验证.
     *
     *@param $Id
     *@param $code
     */
    public function verify($id, $code)
    {
        $this->pageTitle = $this->ld['activation'].$this->ld['subscribe'].' - '.$this->configs['shop_title'];
        $email = $this->NewsletterList->findbyid($id);
        if ($code != md5($id.$email['NewsletterList']['email'])) {
            $this->flash($this->ld['invalid_url'], '/', '');
        } elseif ($email['NewsletterList']['status'] == 1) {
            $this->flash($this->ld['activation'].$this->ld['successfully'], '/', '');
        } else {
            $email['NewsletterList']['status'] = 1;
            $this->NewsletterList->save($email);
                              // 是否发送订阅通知
             if ($this->configs['email_notification'] == 1) {
                 /* 发送通知 */
                $shop_name = $this->configs['shop_name'];
                 $send_date = date('Y-m-d');
                 $email = $email['NewsletterList']['email'];
                 $template = $this->MailTemplate->find("code = 'email_notification' and status = '1'");
                 $template_str = $template['MailTemplateI18n']['html_body'];
                /* 商店网址 */
                $shop_url = $this->server_host.$this->webroot;
                 eval("\$template_str = \"$template_str\";");
                 $text_body = $template['MailTemplateI18n']['text_body'];
                 eval("\$text_body = \"$text_body\";");
                 $subject = $template['MailTemplateI18n']['title'];
                 eval("\$subject = \"$subject\";");
                 $mailsendqueue = array(
                    'sender_name' => $shop_name,//发送从姓名
                    'receiver_email' => ';'.$to_email,//接收人姓名;接收人地址
                    'cc_email' => ';',//抄送人
                    'bcc_email' => ';',//暗送人
                    'title' => $subject,//主题 
                    'html_body' => $template_str,//内容
                    'text_body' => $text_body,//内容
                    'sendas' => 'html',
                );
                 $this->Email->send_mail(LOCALE, $this->configs['email_the_way'], $mailsendqueue);
             }

            $this->flash($this->ld['activation'].$this->ld['successfully'], '/', '');
        }
    }
    /**
     *取消.
     *
     *@param $Id
     *@param $code
     */
    public function cancel($id, $code)
    {
        $this->pageTitle = $this->ld['unsubscribe'].' - '.$this->configs['shop_title'];

        $email = $this->NewsletterList->findbyid($id);
        if ($code != md5($id.$email['NewsletterList']['email'])) {
            $this->flash($this->ld['invalid_url'], '/', '');
        }
        if ($email['NewsletterList']['status'] == 1) {
            $this->flash($this->ld['unsubscribe'].$this->ld['successfully'], '/', '');
        }
        $email['NewsletterList']['status'] = 2;
        $this->NewsletterList->save($email);
        $this->flash($this->ld['unsubscribe'].$this->ld['successfully'], '/', '');
    }
}
