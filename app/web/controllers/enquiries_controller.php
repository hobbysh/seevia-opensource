<?php

/*****************************************************************************
 * Seevia 询价
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为EnquiryController的控制器
 *控制联系方式.
 *
 *@var
 *@var
 *@var
 *@var
 */
class EnquiriesController extends AppController
{
    public $name = 'Enquiries';
    public $helpers = array('Html');
    public $uses = array('User','MailTemplate','Enquiry','MailSendQueue','InformationResourceI18n','ApplicationConfig','ApplicationConfigI18n','Product');
    public $components = array('RequestHandler','Email');
    /*
     *函数index 询价单页面
     *@param	product_id 商品id
    */
    public function index($product_id = 0)
    {
    	 $_GET=$this->clean_xss($_GET);
    	 $product_id=intval($product_id);
        if (isset($this->configs['open_enquiry']) && $this->configs['open_enquiry'] == 0) {
            $this->redirect('/');
        }
        $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;
        $attr = isset($_GET['attr']) ? $_GET['attr'] : '';
        $this->pageTitle = $this->ld['enquiry_form'].' - '.$this->configs['shop_name'];
        $this->ur_heres[] = array('name' => $this->ld['enquiry'], 'url' => '');
        $this->set('product_id', $product_id);
        if ($product_id != 0) {
            //pr($product_id.' '.$attr1.' '.$attr2);
            //查询商品货号及价格
            $pro_info = $this->Product->find('first', array('conditions' => array('Product.id' => $product_id), 'fields' => 'Product.code,Product.img_thumb,ProductI18n.name,Product.shop_price'));
            if (!empty($pro_info)) {
                $code = $pro_info['Product']['code'];
                $shop_price = $pro_info['Product']['shop_price'];
                $img = $pro_info['Product']['img_thumb'];
                $name = $pro_info['ProductI18n']['name'];
                $pro_id = $pro_info['Product']['id'];
                $this->set('pro_id', $pro_id);
                $this->set('code', $code);
                $this->set('shop_price', $shop_price);
                $this->set('img', $img);
                $this->set('name', $name);

                if (!empty($pro_info['ProductAttribute']) && $attr == '') {
                    foreach ($pro_info['ProductAttribute'] as $v) {
                        $attr .= $v['attribute_value'].' ';
                    }
                }
            }
            $this->set('attr', $attr);
        }
        if ($this->RequestHandler->isPost()) {
        	$this->data=$this->clean_xss($this->data);
            $currency = $this->ld['RMB'];
            $this->data['Enquiry']['company_type'] = 0;
            //联系人
            if (!empty($this->data['Enquiry']['user_id'])) {
                $user_info = $this->User->find('first', array('conditions' => array('User.id' => $this->data['Enquiry']['user_id']), 'fields' => 'User.name,User.mobile,User.email'));
                if (empty($this->data['Enquiry']['contact_person'])) {
                    $this->data['Enquiry']['contact_person'] = $user_info['User']['name'];
                }
                if (empty($this->data['Enquiry']['tel1'])) {
                    $this->data['Enquiry']['tel1'] = $user_info['User']['mobile'];
                }
                if (empty($this->data['Enquiry']['email'])) {
                    $this->data['Enquiry']['email'] = $user_info['User']['email'];
                }
            }
            $this->data['Enquiry']['ip_address'] = $this->real_ip();
            $this->data['Enquiry']['browser'] = $this->getbrowser();
            $this->data['Enquiry']['locale'] = LOCALE;
            if (count($this->data['Enquiry']['part_num']) > 1) {
                $this->data['Enquiry']['part_num'] = implode(';', $this->data['Enquiry']['part_num']);
            } else {
                $this->data['Enquiry']['part_num'] = $this->data['Enquiry']['part_num'][0];
            }
            if (count($this->data['Enquiry']['attribute']) > 1) {
                $this->data['Enquiry']['attribute'] = implode(';', $this->data['Enquiry']['attribute']);
            } else {
                $this->data['Enquiry']['attribute'] = $this->data['Enquiry']['attribute'][0];
            }
            if (count($this->data['Enquiry']['qty']) > 1) {
                $this->data['Enquiry']['qty'] = implode(';', $this->data['Enquiry']['qty']);
            } else {
                $this->data['Enquiry']['qty'] = $this->data['Enquiry']['qty'][0];
            }
            if (count($this->data['Enquiry']['target_price']) > 1) {
                $this->data['Enquiry']['target_price'] = implode(';', $this->data['Enquiry']['target_price']);
            } else {
                $this->data['Enquiry']['target_price'] = $this->data['Enquiry']['target_price'][0];
                $price = $this->data['Enquiry']['target_price'];
            }
            //存储数据
            $request = $this->Enquiry->save($this->data['Enquiry']);
            if (isset($_POST['is_ajax'])) {
                if ($request) {
                    $result['flag'] = 1;
                    $result['content'] = $this->ld['information_submitted'].$this->configs['contactus_conversion'];
                }
                Configure::write('debug', 0);
                $this->layout = 'ajax';
                die(json_encode($result));
            }
            //发送询价接收通知邮件
            if ($request && isset($this->configs['enquiry-email']) && !empty($this->configs['enquiry-email'])) {
                $send_date = date('Y-m-d');
                $shop_name = $this->configs['shop_name'];
                //模板code查询
                $template = $this->MailTemplate->find("code = 'enquiry_email' and status = 1");
                $template_str = $template['MailTemplateI18n']['html_body'];
                $template_str = str_replace('$consignee', $_SESSION['User']['User']['name'], $template_str);
                $template_str = str_replace('$formated_add_time', DateTime, $template_str);
//				$email_product_info=$this->ld['sku'].": ".$this->data['Enquiry']['part_num']."<br>";
//				$email_product_info.=$this->ld['attribute'].": ".$this->data['Enquiry']['attribute']."<br>";
//				$email_product_info.=$this->ld['price'].": ".$this->data['Enquiry']['target_price']."<br>";
//				$email_product_info.=$this->ld['qty_f'].": ".$this->data['Enquiry']['qty']."<br>";
                $email_product_info = '<table width="400" border="0" cellspacing="0" cellpadding="0"><thead><td>'.$this->ld['sku'].'</td><td>'.$this->ld['attribute'].'</td><td>'.$this->ld['price'].'</td><td>'.$this->ld['qty_f'].'</td></thead>';
                //pr($this->data['Enquiry']);
                $part_num_arr = explode(';', $this->data['Enquiry']['part_num']);
                $attribute_arr = explode(';', $this->data['Enquiry']['attribute']);
                $target_price_arr = explode(';', $this->data['Enquiry']['target_price']);
                $qty_arr = explode(';', $this->data['Enquiry']['qty']);
                for ($i = 0;$i < count($part_num_arr);++$i) {
                    $email_product_info .= '<tr><td>'.$part_num_arr[$i].'</td><td>'.$attribute_arr[$i].'</td><td>'.$target_price_arr[$i].'</td><td>'.$qty_arr[$i].'</td></tr>';
                }
                $email_product_info .= '</table>';
                $remark = $this->data['Enquiry']['remark'];
                $shop_url = $this->server_host.$this->webroot;
                $template_str = str_replace('$remark', $remark, $template_str);
                $template_str = str_replace('$sent_date', $send_date, $template_str);
                $template_str = str_replace('$products_info', $email_product_info, $template_str);
                $template_str = str_replace('$shop_url', $shop_url, $template_str);
                $template_str = str_replace('$shop_name', $shop_name, $template_str);
                $subject = $template['MailTemplateI18n']['title'];
                $subject = str_replace('$shop_name', $shop_name, $subject);
                $receiver_email = $this->configs['enquiry-email'].';'.$this->configs['enquiry-email'];
                $mail_send_queue = array(
                                        'id' => '',
                                        'sender_name' => $shop_name,
                                        'receiver_email' => $receiver_email,
                                        'cc_email' => ';',
                                        'bcc_email' => ';',
                                        'title' => $subject,
                                        'html_body' => $template_str,
                                        'text_body' => $template_str,
                                        'sendas' => 'html',
                                        'flag' => 0,
                                        'pri' => 0,
                                        );
                $this->Email->send_mail(LOCALE, 1, $mail_send_queue, $this->configs);
            }
            $url = $this->data['Enquiry']['product_id'] == 0 ? '/' : '/products/'.$this->data['Enquiry']['product_id'];
            $msg = isset($this->configs['contactus_conversion']) && $this->configs['contactus_conversion'] != '' ? $this->configs['contactus_conversion'] : $this->ld['information_submitted'];

            $this->layout = 'ajax';
            $result['flag'] = 2;
            $result['content'] = $msg;
            $result['url'] = $url;
            die(json_encode($result));
        }
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
