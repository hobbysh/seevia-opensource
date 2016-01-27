<?php

/*****************************************************************************
 * Seevia 专题管理
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为ContactsController的控制器
 *控制联系方式.
 *
 *@var
 *@var
 *@var
 *@var
 */
class ContactsController extends AppController
{
    public $name = 'Contacts';
    public $helpers = array('Html');
    public $uses = array('MailTemplate','Contact','MailSendQueue','Application','ApplicationConfig','ApplicationConfigI18n','InformationResourceI18n','Resource');
    public $components = array('RequestHandler','Email');
    /**
     *公司管理页.
     */
    public function index()
    {
    	 $_GET=$this->clean_xss($_GET);
        $this->layout = 'default_full';
        $this->pageTitle = $this->ld['contact_us'].' - '.$this->configs['shop_title'];
        $params['industry'] = isset($this->configs['contacts-industry']) ? $this->configs['contacts-industry'] : '';
        $params['learn_us'] = isset($this->configs['contacts-learn-us']) ? $this->configs['contacts-learn-us'] : '';
        $this->page_init($params);

        //资源信息
        $contact_us_type = array();
        $contact_us_type_data = !empty($this->system_resources['contact_us_type']) && sizeof($this->system_resources['contact_us_type']) > 1 ? $this->system_resources['contact_us_type'] : array();
        if (isset($contact_us_type_data[''])) {
            unset($contact_us_type_data['']);
        }
        foreach ($contact_us_type_data as $k => $v) {
            $contact_us_type[] = $k;
        }
        if (isset($_GET['type']) && in_array($_GET['type'], $contact_us_type)) {
            $this->set('contact_us_type', $_GET['type']);
        }
        if (!empty($contact_us_type_data)) {
            $this->set('contact_us_type_data', $contact_us_type_data);
        }
        $this->ur_heres[] = array('name' => $this->ld['contact_us'],'url' => '/contacts/');
        $industry = $this->Config->find('first', array('conditions' => array('Config.code' => 'contacts-industry')));
        $learn_us = $this->Config->find('first', array('conditions' => array('Config.code' => 'contacts-learn-us')));
        $this->set('industry', explode(';', $industry['ConfigI18n']['value']));
        $this->set('learn_us', explode(';', $learn_us['ConfigI18n']['value']));
        if ($this->RequestHandler->isPost()) {
        	Configure::write('debug', 0);
        	$this->layout='ajax';
            $contact_type = isset($this->data['Contact']['type']) && isset($contact_us_type_data[$this->data['Contact']['type']]) ? $contact_us_type_data[$this->data['Contact']['type']] : '';
            $company_name = isset($this->data['Contact']['company']) ? $this->data['Contact']['company'] : '';
            $company_type = isset($this->data['Contact']['company_type']) ? $this->data['Contact']['company_type'] : '';
            $from_type = isset($this->data['Contact']['from']) ? $this->data['Contact']['from'] : '';
            $this->data['Contact']['company_type'] = isset($this->data['Contact']['company_type']) ? $this->data['Contact']['company_type'] : '';
            $this->data['Contact']['from'] = isset($this->data['Contact']['from']) ? $this->data['Contact']['from'] : '';
            $connect_person = isset($this->data['Contact']['contact_name']) ? $this->data['Contact']['contact_name'] : '';
            $email = isset($this->data['Contact']['email']) ? $this->data['Contact']['email'] : '';
            $mobile = isset($this->data['Contact']['mobile']) ? $this->data['Contact']['mobile'] : '';
            $qq = isset($this->data['Contact']['qq']) ? $this->data['Contact']['qq'] : '';
            $msn = isset($this->data['Contact']['msn']) ? $this->data['Contact']['msn'] : '';
            $skype = isset($this->data['Contact']['skype']) ? $this->data['Contact']['skype'] : '';
            $content = isset($this->data['Contact']['content']) ? $this->data['Contact']['content'] : '';
            $this->data['Contact']['address'] = isset($this->data['Contact']['address']) ? $this->data['Contact']['address'] : '';
            $this->data['Contact']['company_url'] = isset($this->data['Contact']['web']) ? $this->data['Contact']['web'] : '';
            $appointment_date = isset($this->data['Contact']['parameter_01']) ? $this->data['Contact']['parameter_01'] : '';
            $appointment_time = isset($this->data['Contact']['parameter_02']) ? $this->data['Contact']['parameter_02'] : '';
            $parameter_03 = isset($this->data['Contact']['parameter_03']) ? $this->data['Contact']['parameter_03'] : '';
            $age = isset($this->data['Contact']['age']) ? $this->data['Contact']['age'] : 0;
            $sex = isset($this->data['Contact']['sex']) ? ($this->data['Contact']['sex'] == '1' ? $this->ld['user_male'] : $this->ld['user_female']) : $this->ld['privacy'];
            $this->data['Contact']['resolution'] = (isset($this->data['Contact']['width']) ? $this->data['Contact']['width'] : '0').'*'.(isset($this->data['Contact']['height']) ? $this->data['Contact']['width'] : '0');
            $this->data['Contact']['ip_address'] = $this->real_ip();
            $this->data['Contact']['browser'] = $this->getbrowser();
            $this->data['Contact']['locale'] = LOCALE;
            $contact_data_count=0;
            foreach($this->data['Contact'] as $v){
            		if(empty($v)){
            			$contact_data_count++;
            		}else if(trim($v)==""){
            			$contact_data_count++;
            		}
            }
            if($contact_data_count>5){
            		$result_arr['code'] = 0;
            		$result_arr['msg'] = $this->ld['save_basic_info'];
            		die(json_encode($result_arr));
            }
            $this->Contact->save($this->data['Contact']);
            $shop_name = $this->configs['shop_name'];
            $send_date = date('Y-m-d');
            $email_text = $this->ld['company_name'].':'.$company_name.'<br>';
            $email_text .= $this->ld['domain'].':'.$this->data['Contact']['company_url'].'<br>';
            $email_text .= $this->ld['industry'].':'.$company_type.'<br>';
            $email_text .= $this->ld['contact'].':'.$connect_person.'<br>';
            $email_text .= $this->ld['e-mail'].':'.$email.'<br>';
            $email_text .= 'QQ:'.$qq.'<br>';
            $email_text .= 'MSN:'.$msn.'<br>';
            $email_text .= 'SKYPE:'.$skype.'<br>';
            $email_text .= $this->ld['how_did_you_learn_about_us'].':'.$from_type.'<br>';
            $email_text .= $this->ld['message'].':'.$content.'<br><br>';
            $email_text .= $this->ld['date'].':'.$send_date;
            $template = $this->MailTemplate->find("code = 'contact_us' and status = 1");
            $template_str = $template['MailTemplateI18n']['html_body'];
		/* 商店网址 */
		$shop_url = $this->server_host.$this->webroot;
		$text_body = $template['MailTemplateI18n']['text_body'];
		$subject = $template['MailTemplateI18n']['title'];
		eval("\$subject = \"$subject\";");
            $receiver_email_str = isset($this->configs['contacts-email']) ? $this->configs['contacts-email'] : '';
            $receiver_email_arr = explode(',', $receiver_email_str);
            foreach ($receiver_email_arr as $v) {
                $receiver_email[] = $v.';'.$v;
            }
            if (empty($template)) {
                $mail_send_queue = array(
                                        'id' => '',
                                        'sender_name' => $shop_name,
                                        'receiver_email' => $receiver_email,
                                        'cc_email' => ';',
                                        'bcc_email' => ';',
                                        'title' => $this->ld['contact_email'],
                                        'html_body' => $email_text,
                                        'text_body' => $email_text,
                                        'sendas' => 'html',
                                        'flag' => 0,
                                        'pri' => 0,
                                        );
            } else {
                $html_body = $template['MailTemplateI18n']['html_body'];
                $text_body = $template['MailTemplateI18n']['text_body'];
                $html_body = str_replace('$shop_name', $shop_name, $html_body);
                $html_body = str_replace('$send_date', $send_date, $html_body);
                if (!empty($appointment_date)) {
                    $html_body = str_replace('$appointment_date', $appointment_date, $html_body);
                    $text_body = str_replace('$appointment_date', $appointment_date, $text_body);
                }
                if (!empty($appointment_time)) {
                    $html_body = str_replace('$appointment_time', $appointment_time, $html_body);
                    $text_body = str_replace('$appointment_time', $appointment_time, $text_body);
                }
                $html_body = str_replace('$contact_type', $contact_type, $html_body);
                $html_body = str_replace('$consignee', $connect_person, $html_body);
                $html_body = str_replace('$company', $company_name, $html_body);
                $html_body = str_replace('$url', $this->data['Contact']['company_url'], $html_body);
                $html_body = str_replace('$type', $company_type, $html_body);
                $html_body = str_replace('$contact_name', $connect_person, $html_body);
                $html_body = str_replace('$email', $email, $html_body);
                $html_body = str_replace('$mobile', $mobile, $html_body);
                $html_body = str_replace('$qq', $qq, $html_body);
                $html_body = str_replace('$msn', $msn, $html_body);
                $html_body = str_replace('$skype', $skype, $html_body);
                $html_body = str_replace('$from', $this->data['Contact']['from'], $html_body);
                $html_body = str_replace('$remark', $content, $html_body);
                $html_body = str_replace('$sex', $sex, $html_body);
                $html_body = str_replace('$age', $age, $html_body);
                $html_body = str_replace('$parameter_03', $parameter_03, $html_body);
                $text_body = str_replace('$contact_type', $contact_type, $text_body);
                $text_body = str_replace('$shop_name', $shop_name, $text_body);
                $text_body = str_replace('$send_date', $send_date, $text_body);
                $text_body = str_replace('$consignee', $connect_person, $text_body);
                $text_body = str_replace('$company', $company_name, $text_body);
                $text_body = str_replace('$url', $this->data['Contact']['company_url'], $text_body);
                $text_body = str_replace('$type', $company_type, $text_body);
                $text_body = str_replace('$contact_name', $connect_person, $text_body);
                $text_body = str_replace('$email', $email, $text_body);
                $text_body = str_replace('$mobile', $mobile, $text_body);
                $text_body = str_replace('$qq', $qq, $text_body);
                $text_body = str_replace('$msn', $msn, $text_body);
                $text_body = str_replace('$skype', $skype, $text_body);
                $text_body = str_replace('$from', $this->data['Contact']['from'], $text_body);
                $text_body = str_replace('$remark', $content, $text_body);
                $text_body = str_replace('$sex', $sex, $text_body);
                $text_body = str_replace('$age', $age, $text_body);
                $text_body = str_replace('$parameter_03', $parameter_03, $text_body);
                $mail_send_queue = array(
                                        'id' => '',
                                        'sender_name' => $shop_name,
                                        'receiver_email' => $receiver_email,
                                        'cc_email' => ';',
                                        'bcc_email' => ';',
                                        'title' => $this->ld['contact_email'],
                                        'html_body' => $html_body,
                                        'text_body' => $text_body,
                                        'sendas' => 'html',
                                        'flag' => 0,
                                        'pri' => 0,
                                        );
            }
            $result = $this->Email->send_mail(LOCALE, 1, $mail_send_queue, $this->configs);
            $msg = isset($this->configs['contactus_conversion']) && $this->configs['contactus_conversion'] != '' ? $this->configs['contactus_conversion'] : $this->ld['information_submitted'];
                $this->set('msg', $msg);
            $result_arr['code'] = 1;
            $result_arr['msg'] = $msg;
            die(json_encode($result_arr));
            $this->redirect('/contacts');
                //$this->flash("信息已提交，我们会尽快与您联系。谢谢！".$this->configs['contactus_conversion'],isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"/",3);
        }
        $js_languages = array(
                               'company_name_not_empty' => $this->ld['company_name'].$this->ld['can_not_empty'],
                               'invalid_email' => $this->ld['email'].$this->ld['format'].$this->ld['not_correct'],
                            'please_choose_company_type' => $this->ld['please_select'].$this->ld['industry'],
                        //	"connect_person_can_not_empty" =>  $this->ld['connect_person'].$this->ld['can_not_empty'],
                            'mobile_can_not_empty' => $this->ld['mobile'].$this->ld['can_not_empty'],
                            'content_can_not_empty' => $this->ld['content'].$this->ld['can_not_empty'],
                            );
        $this->set('js_languages', $js_languages);
    }

        /**
         *实际id.
         */
        public function real_ip()
        {
            static $realip = null;

            if ($realip !== null) {
                return $realip;
            }

            if (isset($_SERVER)) {
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);

                    if ($ip != 'unknown') {
                        $realip = $ip;

                        break;
                    }
                }
                } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                    $realip = $_SERVER['HTTP_CLIENT_IP'];
                } else {
                    if (isset($_SERVER['REMOTE_ADDR'])) {
                        $realip = $_SERVER['REMOTE_ADDR'];
                    } else {
                        $realip = '0.0.0.0';
                    }
                }
            } else {
                if (getenv('HTTP_X_FORWARDED_FOR')) {
                    $realip = getenv('HTTP_X_FORWARDED_FOR');
                } elseif (getenv('HTTP_CLIENT_IP')) {
                    $realip = getenv('HTTP_CLIENT_IP');
                } else {
                    $realip = getenv('REMOTE_ADDR');
                }
            }

            preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
            $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

            return $realip;
        }
    /**
     *获得游览器.
     */
    public function getbrowser()
    {
        global $_SERVER;

        $agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = '';
        $browser_ver = '';

        if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
            $browser = 'OmniWeb';
            $browser_ver = $regs[2];
        }

        if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Netscape';
            $browser_ver = $regs[2];
        }

        if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Safari';
            $browser_ver = $regs[1];
        }

        if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
            $browser = 'Internet Explorer';
            $browser_ver = $regs[1];
        }

        if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
            $browser = 'Opera';
            $browser_ver = $regs[1];
        }

        if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
            $browser = '(Internet Explorer '.$browser_ver.') NetCaptor';
            $browser_ver = $regs[1];
        }

        if (preg_match('/Maxthon/i', $agent, $regs)) {
            $browser = '(Internet Explorer '.$browser_ver.') Maxthon';
            $browser_ver = '';
        }

        if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'FireFox';
            $browser_ver = $regs[1];
        }

        if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Lynx';
            $browser_ver = $regs[1];
        }

        if ($browser != '') {
            return $browser.' '.$browser_ver;
        } else {
            return 'Unknow browser';
        }
    }
}
