<?php

/**
 * This is a component to send email from CakePHP using PHPMailer.
 *
 * @link http://bakery.cakephp.org/articles/view/94
 * @see http://bakery.cakephp.org/articles/view/94
 */
App::import('Vendor', 'Phpmailer', array('file' => 'phpmailer/class.phpmailer.php'));
class EmailComponent
{
    /*
   * Send email using SMTP Auth by default.
   */
    public $from = 'phpmailer@cakephp';
    public $fromName = 'Cake PHP-Mailer';
    public $smtpUserName = '';  // SMTP username
    public $smtpPassword = ''; // SMTP password
    public $smtpHostNames = '';  // specify main and backup server
    public $text_body = null;
    public $html_body = null;
    public $to = null;
    public $toName = null;
    public $addccto = null;
    public $addcctoName = null;
    public $addbccto = null;
    public $addbcctoName = null;
    public $subject = null;
    public $cc = null;
    public $bcc = null;
    public $template = 'email/default';
    public $attachments = null;
    public $controller;
    public $is_ssl = 0;
    public $is_mail_smtp = 0;
    public $smtp_port = 25;//email
    public $configs;
    public $smtpauth = true;

    public function startup($controller)
    {
        $this->controller = $controller;
    }

    public function bodyText()
    {
        /* This is the body in plain text for non-HTML mail clients
     */
      ob_start();
        $temp_layout = $this->controller->layout;
        $this->controller->layout = '';  // Turn off the layout wrapping
      $this->controller->render($this->template.'_text');
        $mail = ob_get_clean();
        $this->controller->layout = $temp_layout; // Turn on layout wrapping again
      return $mail;
    }

    public function bodyHTML()
    {
        /* This is HTML body text for HTML-enabled mail clients
     */
      ob_start();
        $temp_layout = $this->controller->layout;
        $this->controller->layout = 'email';  //  HTML wrapper for my html email in /app/views/layouts
      $this->controller->render($this->template.'_html');
        $mail = ob_get_clean();
        $this->controller->layout = $temp_layout; // Turn on layout wrapping again
      return $mail;
    }

    public function attach($filename, $asfile = '')
    {
        if (empty($this->attachments)) {
            $this->attachments = array();
            $this->attachments[0]['filename'] = $filename;
            $this->attachments[0]['asfile'] = $asfile;
        } else {
            $count = count($this->attachments);
            $this->attachments[$count + 1]['filename'] = $filename;
            $this->attachments[$count + 1]['asfile'] = $asfile;
        }
    }

    //原始发送方法
    public function send($mailsendname = '')
    {
        $mail = new PHPMailer();
        if ($this->is_mail_smtp == 0) {
            $mail->IsMail();// set mailer to use SMTP
        } else {
            $mail->IsSMTP();
        }
        $mail->SMTPAuth = $this->smtpauth;// turn on SMTP authentication
        if ($this->is_ssl == 1) {
            $mail->SMTPSecure = 'ssl';
        }
        $mail->Port = $this->smtp_port;
        $mail->Host = $this->smtpHostNames;
        if ($this->is_mail_smtp == 1) {
            $mail->Username = $this->smtpUserName;
            $mail->Password = $this->smtpPassword;
        }
        //$mail->SMTPDebug = 10;
        if ($mailsendname != '') {
            $emailname = explode(';', $mailsendname);
            $mail->FromName = $emailname[0];
            $mail->From = $emailname[1];
            $mail->AddReplyTo($emailname[1], $emailname[0]);
        } else {
            $mail->From = $this->from;
            $mail->FromName = $this->fromName;
            $mail->AddReplyTo($this->from, $this->fromName);
        }
        if (is_array($this->to)) {
            for ($i = 0;$i < count($this->to);++$i) {
                $mail->AddAddress($this->to[$i], $this->toName[$i]);
            }
        } else {
            $mail->AddAddress($this->to, $this->toName);
        }

        if (!empty($this->addccto) && !empty($this->addcctoName)) {
            if (is_array($this->addccto)) {
                for ($i = 0;$i < count($this->addccto);++$i) {
                    $mail->AddCC($this->addccto[$i], $this->addcctoName[$i]);
                }
            } else {
                $mail->AddCC($this->addccto, $this->addcctoName);
            }
        }
        if (!empty($this->addbccto) && !empty($this->addbcctoName)) {
            if (is_array($this->addbccto)) {
                for ($i = 0;$i < count($this->addbccto);++$i) {
                    $mail->AddBCC($this->addbccto[$i], $this->addbcctoName[$i]);
                }
            } else {
                $mail->AddBCC($this->addbccto, $this->addbcctoName);
            }
        }
        $mail->CharSet = 'UTF-8';
        $mail->WordWrap = 50;
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                if (empty($attachment['asfile'])) {
                    $mail->AddAttachment($attachment['filename']);
                } else {
                    $mail->AddAttachment($attachment['filename'], $attachment['asfile']);
                }
            }
        }
        $mail->IsHTML(true);
        $mail->Subject = $this->subject;
        $mail->Body = $this->html_body;
        $mail->AltBody = $this->text_body;
        $result = $mail->Send();
        if ($result == false) {
            $result = $mail->ErrorInfo;
        }

        return $result;
    }

    //改写发送方法
    public function send_mail($locale, $status, $mailsendqueue, $mail_config, $mailsendname = '')
    {
        if (isset($status) && $status != 1) {
            $this_model = new Model(false, 'mail_send_queues');
            $this_model->deleteAll(array('flag' => '5'));
            $mail_send_queue_info = $this_model->find('all', array('limit' => '10', 'order' => 'id asc'));
            $this_model->saveAll($mailsendqueue);

            return false;
        } else {
            $this->set_appconfigs($locale);
            $this->smtpauth = isset($mail_config['mail-requires-authorization']) ? trim($mail_config['mail-requires-authorization']) : 1;
            $this->is_ssl = trim($mail_config['mail-ssl']);
            $this->is_mail_smtp = trim($mail_config['mail-service']);
            $this->smtp_port = trim($mail_config['mail-port']);
            $this->smtpHostNames = trim($mail_config['mail-smtp']);
            $this->smtpUserName = trim($mail_config['mail-account']);
            $this->smtpPassword = trim($mail_config['mail-password']);
            $this->from = $mail_config['mail-address'];
            $this_model = new Model(false, 'mail_send_histories');
            $this->sendAs = $mailsendqueue['sendas'];
            $this->fromName = $mailsendqueue['sender_name'];
            $subject = $mailsendqueue['title'];
            $this->subject = $mailsendqueue['title'];
                //eval("\$subject = \"$subject\";");
                //$this->subject="=?utf-8?B?".base64_encode($subject)."?=";
                if (is_array($mailsendqueue['receiver_email'])) {
                    $to_email_and_name_arr = $mailsendqueue['receiver_email'];
                    $i = 0;
                    foreach ($to_email_and_name_arr as $k => $v) {
                        if (strpos($v, ';')) {
                            $to_email_and_name = explode(';', $v);
                            $to_name[$i] = $to_email_and_name[0];
                            $to_email[$i] = $to_email_and_name[1];
                            ++$i;
                        }
                    }
                } else {
                    $to_email_and_name = explode(';', $mailsendqueue['receiver_email']);
                    $to_name[] = $to_email_and_name[0];
                    $to_email[] = $to_email_and_name[1];
                }
            $mailsendqueue['receiver_email'] = json_encode($mailsendqueue['receiver_email']);

            if (is_array($mailsendqueue['cc_email'])) {
                $addcc_to_email_and_name_arr = $mailsendqueue['cc_email'];
                $i = 0;
                foreach ($addcc_to_email_and_name_arr as $k => $v) {
                    if (strpos($v, ';')) {
                        $addcc_to_email_and_name = explode(';', $v);
                        $addcc_to_name[$i] = $addcc_to_email_and_name[0];//�ռ�������
                            $addcc_to_email[$i] = $addcc_to_email_and_name[1];//�ռ���email
                            ++$i;
                    }
                }
            } else {
                if (trim($mailsendqueue['cc_email']) != '' && trim($mailsendqueue['cc_email']) != ';') {
                    $addcc_to_email_and_name = explode(';', $mailsendqueue['cc_email']);
                    if (trim($addcc_to_email_and_name[0]) != '' && trim($addcc_to_email_and_name[1]) != ';') {
                        $addcc_to_name[] = $addcc_to_email_and_name[0];//�ռ�������
                            $addcc_to_email[] = $addcc_to_email_and_name[1];//�ռ���email
                    }
                }
            }
            $mailsendqueue['cc_email'] = json_encode($mailsendqueue['cc_email']);
            if (is_array($mailsendqueue['bcc_email'])) {
                $addbcc_to_email_and_name_arr = $mailsendqueue['bcc_email'];
                $i = 0;
                foreach ($addbcc_to_email_and_name_arr as $k => $v) {
                    if (strpos($v, ';')) {
                        $addbcc_to_email_and_name = explode(';', $v);
                        $addbcc_to_name[$i] = $addbcc_to_email_and_name[0];//�ռ�������
                            $addbcc_to_email[$i] = $addbcc_to_email_and_name[1];//�ռ���email
                            ++$i;
                    }
                }
            } else {
                if (trim($mailsendqueue['bcc_email']) != '' && trim($mailsendqueue['bcc_email']) != ';') {
                    $addbcc_to_email_and_name = explode(';', $mailsendqueue['bcc_email']);
                    if (trim($addbcc_to_email_and_name[0]) != '' && trim($addbcc_to_email_and_name[1]) != ';') {
                        $addbcc_to_name[] = $addbcc_to_email_and_name[0];//�ռ�������
                            $addbcc_to_email[] = $addbcc_to_email_and_name[1];//�ռ���email
                    }
                }
            }
            $mailsendqueue['bcc_email'] = json_encode($mailsendqueue['bcc_email']);

//			  	$addcc_to_email_and_name = explode(";",$mailsendqueue['cc_email']);
//			  	$addcc_to_name = $addcc_to_email_and_name[0];
//			  	$addcc_to_email = $addcc_to_email_and_name[1];
//			  	$addbcc_to_email_and_name = explode(";",$mailsendqueue['bcc_email']);
//			  	$addbcc_to_name = $addbcc_to_email_and_name[0];
//			  	$addbcc_to_email = $addbcc_to_email_and_name[1];
                $this->html_body = $mailsendqueue['html_body'];
            $this->text_body = $mailsendqueue['text_body'];
            for ($i = 0;$i < count($to_email);++$i) {
                $this->toName[$i] = trim($to_name[$i]);
                $this->to[$i] = trim($to_email[$i]);
            }
            if (isset($addcc_to_name)) {
                for ($i = 0;$i < count($addcc_to_name);++$i) {
                    $this->addcctoName[$i] = trim($addcc_to_name[$i]);
                    $this->addccto[$i] = trim($addcc_to_email[$i]);
                }
            }
            if (isset($addbcc_to_name)) {
                for ($i = 0;$i < count($addbcc_to_name);++$i) {
                    $this->addbcctoname[$i] = trim($addbcc_to_name[$i]);
                    $this->addbccto[$i] = trim($addbcc_to_email[$i]);
                }
            }
//				$this->addcctoName=trim($addcc_to_name);
//				$this->addccto=trim($addcc_to_email);
//				$this->addbcctoName=trim($addbcc_to_name);
//				$this->addbccto=trim($addbcc_to_email);
                $mail_status = $this->send($mailsendname);
            $this_model->saveAll($mailsendqueue);
            if ($mail_status) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function test($locale = 'chi')
    {
        $this_model = new Model(false, 'configs');
        $config = $this_model->find('all', array('conditions' => array('code' => array('smtp_ssl', 'mail_service', 'smtp_port', 'smtp_host', 'smtp_user', 'smtp_pass', 'smtp_user'))));
        foreach ($config as $k => $v) {
            $config_id[] = $v['Model']['id'];
            $config_code[$v['Model']['code']] = $v['Model']['id'];
        }
        $this_model = new Model(false, 'config_i18ns');
        $configi18n = $this_model->find('all', array('conditions' => array('config_id' => $config_id, 'locale' => $locale), 'fields' => array('config_id', 'value')));
        foreach ($configi18n as $k => $v) {
            $value[$v['Model']['config_id']] = $v['Model']['value'];
        }
        foreach ($config_code as $k => $v) {
            $configs[$k] = $value[$v];
        }
    }

    public function set_configs($locale = 'chi')
    {
        $this_model = new Model(false, 'configs');
        $config = $this_model->find('all', array('conditions' => array('code' => array('smtp_ssl', 'mail_service', 'smtp_port', 'smtp_host', 'smtp_user', 'smtp_pass', 'smtp_user'))));
        if (empty($config)) {
            return false;
        }
        foreach ($config as $k => $v) {
            $config_id[] = $v['Model']['id'];
            $config_code[$v['Model']['code']] = $v['Model']['id'];
        }
        $this_model = new Model(false, 'config_i18ns');
        $configi18n = $this_model->find('all', array('conditions' => array('config_id' => $config_id, 'locale' => $locale), 'fields' => array('config_id', 'value')));
        foreach ($configi18n as $k => $v) {
            $value[$v['Model']['config_id']] = $v['Model']['value'];
        }
        foreach ($config_code as $k => $v) {
            $configs[$k] = $value[$v];
        }
    }

    public function set_appconfigs($locale = 'chi')
    {
        $this_model = new Model(false, 'application_configs');
        $appconfig = $this_model->find('all', array('conditions' => array('code' => array('APP-MAIL-SSL', 'APP-MAIL-SERVICE', 'APP-MAIL-PORT', 'APP-MAIL-SMTP', 'APP-MAIL-ACCOUNT', 'APP-MAIL-PASSWORD', 'APP-MAIL-ACCOUNT'))));
        if (empty($appconfig)) {
            return false;
        }
        foreach ($appconfig as $k => $v) {
            $config_id[] = $v['Model']['id'];
            $config_code[$v['Model']['code']] = $v['Model']['id'];
        }
        $this_model = new Model(false, 'application_config_i18ns');
        $appconfigi18n = $this_model->find('all', array('conditions' => array('app_config_id' => $config_id, 'locale' => $locale), 'fields' => array('app_config_id', 'value')));
        foreach ($appconfigi18n as $k => $v) {
            $value[$v['Model']['app_config_id']] = $v['Model']['value'];
        }
        foreach ($config_code as $k => $v) {
            $configs[$k] = $value[$v];
        }
    }
}
