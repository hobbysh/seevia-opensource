<?php

/**
 *这是一个名为 QuotesController 的控制器
 *退款退货控制器.
 */
class QuotesController extends AppController
{
    public $name = 'Quotes';//对应试图
        public $uses = array('Quote','QuoteProduct','Product','ProductI18n','ProductTypeAttribute','Attribute','ProductAttribute','Brand','MailTemplateI18n','MailTemplate','Enquiry');//使用的数据库表
        public $components = array('Pagination','RequestHandler','Phpexcel','Cookie','Session','Captcha','Email');//,分页
        public $helpers = array('Pagination','Html','Form','Javascript');//分页样式

        public function index($page = 1)
        {
            $this->operator_privilege('quotes_view');
            $this->operation_return_url(true);//设置操作返回页面地址
            $this->menu_path = array('root' => '/crm/','sub' => '/quotes/');
            $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['quote_manage'],'url' => '');
            //根据商品的名称或货号来搜
            $product_keywords = '';
            $next_condition = '';
            $condition = '';
            if (isset($_REQUEST['product_keywords']) && $_REQUEST['product_keywords'] != '') {
                $product_keywords = trim($_REQUEST['product_keywords']);
                $next_condition['or']['QuoteProduct.product_code like'] = "%$product_keywords%";
                $this->QuoteProduct->hasOne = array();
                $quote_ids = $this->QuoteProduct->find('list', array('conditions' => $next_condition, 'fields' => 'QuoteProduct.quote_id'));
                $condition['Quote.id'] = $quote_ids;
            }
            $this->set('product_keywords', $product_keywords);

            if (isset($this->params['url']['date1']) && $this->params['url']['date1'] != '') {
                $condition['and']['Quote.created >='] = ''.$this->params['url']['date1'].' 00:00:00';
                $this->set('date1', $this->params['url']['date1']);
            }

            if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
                $condition['and']['Quote.created <='] = ''.$this->params['url']['date2'].' 23:59:59';
                $this->set('date2', $this->params['url']['date2']);
            }

            if (isset($this->params['url']['customer_name']) && $this->params['url']['customer_name'] != '') {
                $condition['and']['Quote.customer_name like'] = '%'.$this->params['url']['customer_name'].'%';
                $this->set('customer_name', $this->params['url']['customer_name']);
            }

            //邮件发送状态
            if (isset($this->params['url']['is_sendmail']) && $this->params['url']['is_sendmail'] != '-1') {
                $condition['and']['Quote.is_sendmail'] = $this->params['url']['is_sendmail'];
                $this->set('is_sendmail', $this->params['url']['is_sendmail']);
            }

            $total = $this->Quote->find('count', array('conditions' => $condition));//统计所有商品总数
            if (isset($_GET['page']) && $_GET['page'] != '') {
                $page = $_GET['page'];
            }
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
            $parameters['route'] = array('controller' => 'Quote','action' => 'index','page' => $page,'limit' => $rownum);
            $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Quote');
            $this->Pagination->init($condition, $parameters, $options);
            $quotes_list = $this->Quote->find('all', array('conditions' => $condition, 'limit' => $rownum, 'page' => $page, 'order' => 'id desc'));

            if (!empty($quotes_list) && isset($this->params['url']['pro_show']) && $this->params['url']['pro_show'] == '1') {
                $this->set('showitem', $this->params['url']['pro_show']);

                $quote_ids = array();
                foreach ($quotes_list as $k => $v) {
                    $quote_ids[] = $v['Quote']['id'];
                }

                $quote_products_list = $this->QuoteProduct->find('all', array('conditions' => array('QuoteProduct.quote_id' => $quote_ids), 'order' => 'QuoteProduct.quote_id,QuoteProduct.id'));
                $quotes_product_codes = array();
                foreach ($quotes_list as $k => $v) {
                    foreach ($quote_products_list as $kk => $vv) {
                        if ($v['Quote']['id'] == $vv['QuoteProduct']['quote_id']) {
                            $quotes_list[$k]['QuoteProduct'][] = $vv;
                            $quotes_product_codes[$vv['QuoteProduct']['product_code']] = $vv['QuoteProduct']['product_code'];
                        }
                    }
                }

                $this->Product->set_locale($this->backend_locale);
                $quotes_product_info = $this->Product->find('all', array('fields' => array('Product.code', 'ProductI18n.name'), 'conditions' => array('Product.code' => $quotes_product_codes)));

                $quote_product_list = array();
                if (!empty($quotes_product_info)) {
                    foreach ($quotes_product_info as $v) {
                        $quote_product_list[$v['Product']['code']] = $v['ProductI18n']['name'];
                    }
                }
                $this->set('quote_product_list', $quote_product_list);
            }
            $this->set('title_for_layout', $this->ld['quote_manage'].' - '.$this->ld['page'].' '.$page.' - '.' - '.$this->configs['shop_name']);
            $this->set('quotes_list', $quotes_list);
        }

    public function ajax_find_product_id($enquiry_id)
    {
        if ($this->RequestHandler->isPost()) {
            $result['flag'] = 0;
            $result['msg'] = 'not found';
            if (!empty($enquiry_id)) {
                $p_code = $this->Enquiry->find('first', array('conditions' => array('Enquiry.id' => $enquiry_id)));
                $pid = $this->Product->find('first', array('conditions' => array('Product.code' => $p_code['Enquiry']['part_num'])));
                $result['product_id'] = isset($pid['Product']['id']) ? $pid['Product']['id'] : 0;
                $result['flag'] = 1;
                $result['msg'] = 'success';
            }
            die(json_encode($result));
        }
    }
    public function view($id = 0)
    {
        $this->operator_privilege('quotes_edit');
        $this->menu_path = array('root' => '/crm/','sub' => '/quotes/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['quote_manage'],'url' => '/quotes');
        if (isset($_REQUEST['enquiry_id']) && !empty($_REQUEST['enquiry_id'])) {
            $enquiry_info = $this->Enquiry->find('first', array('conditions' => array('Enquiry.id' => $_REQUEST['enquiry_id'])));

            $product_code = explode(';', $enquiry_info['Enquiry']['part_num']);
            $product_attr = explode(';', $enquiry_info['Enquiry']['attribute']);
            $product_qty = explode(';', $enquiry_info['Enquiry']['qty']);
            $product_tp = explode(';', $enquiry_info['Enquiry']['target_price']);
            $this->Product->set_locale($this->backend_locale);
            $p_info = $this->Product->find('all', array('conditions' => array('Product.code' => $product_code), 'fields' => 'Product.id,Product.code,Product.brand_id,Product.quantity,Product.shop_price'));
            $pkey = 0;
            foreach ($p_info as $pk => $pv) {
                $brand_code = $this->Brand->find('first', array('conditions' => array('Brand.Id' => $pv['Product']['brand_id']), 'fields' => array('Brand.code')));
                if (!empty($pv)) {
                    $quote_products_list[$pkey]['QuoteProduct']['product_code'] = $pv['Product']['code'];
                    $quote_products_list[$pkey]['QuoteProduct']['brand_code'] = $brand_code['Brand']['code'];
                    $quote_products_list[$pkey]['QuoteProduct']['qty_offered'] = $pv['Product']['quantity'];
                    $quote_products_list[$pkey]['QuoteProduct']['offered_price'] = $pv['Product']['shop_price'];
                    $quote_products_list[$pkey]['QuoteProduct']['target_price'] = isset($product_tp[$pk]) ? $product_tp[$pk] : '';
                    $quote_products_list[$pkey]['QuoteProduct']['data_code'] = isset($product_attr[$pk]) ? $product_attr[$pk] : '';
                    $quote_products_list[$pkey]['QuoteProduct']['qty_requested'] = isset($product_qty[$pk]) ? $product_qty[$pk] : '';
                    $quote_products_list[$pkey]['QuoteProduct']['payment_terms'] = isset($enquiry_info['Enquiry']['remark']) ? $enquiry_info['Enquiry']['remark'] : '';
                }
                ++$pkey;
            }
            $this->set('quote_products_list', $quote_products_list);
            $this->set('enquiry_id', $_REQUEST['enquiry_id']);
        }
        if (isset($_REQUEST['pid'])) {
            $this->set('title_for_layout', $this->ld['quote_create'].' -'.$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['quote_create'],'url' => '');
            $index = 0;
            $this->Product->set_locale($this->backend_locale);
            $productid = explode(',', $_REQUEST['pid']);
            $quote_products_list = array();
                //取出所有公共属性
                $this->Attribute->set_locale($this->backend_locale);
            $public_attr_ids = $this->ProductTypeAttribute->getattrids(0);
            $public_attr_info = array();
            $public_attr_list = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $public_attr_ids, 'Attribute.status' => 1)));

            foreach ($productid as $pid) {
                $product_name = $this->Product->find('first', array('conditions' => array('Product.id' => $pid)));
                if (!empty($product_name)) {
                    $p_ids = $product_name['Product']['id'];
                }
                if (!empty($public_attr_info)) {
                    foreach ($public_attr_info as $pat) {
                        $pat_ids[] = $pat['Attribute']['id'];
                    }
                    $attr_info = $this->ProductAttribute->product_list_format($p_ids, $pat_ids, $this->backend_locale);
                    $i = 0;
                    foreach ($public_attr_info as $pa) {
                        if (isset($attr_info[$product_name['Product']['id']][$pa['Attribute']['id']])) {
                            $attr[$i] = $attr_info[$product_name['Product']['id']][$pa['Attribute']['id']] != '' ? $attr_info[$product_name['Product']['id']][$pa['Attribute']['id']] : '-';
                            ++$i;
                        }
                    }
                }
                $brand_code = $this->Brand->find('first', array('conditions' => array('Brand.Id' => $product_name['Product']['brand_id']), 'fields' => array('Brand.code')));
                if (!empty($product_name)) {
                    $quote_products_list[$index]['QuoteProduct']['product_code'] = $product_name['Product']['code'];
                    $quote_products_list[$index]['QuoteProduct']['brand_code'] = $brand_code['Brand']['code'];
                    $quote_products_list[$index]['QuoteProduct']['qty_offered'] = $product_name['Product']['quantity'];
                    $quote_products_list[$index]['QuoteProduct']['offered_price'] = $product_name['Product']['shop_price'];
                    $quote_products_list[$index]['QuoteProduct']['data_code'] = isset($attr[0]) ? $attr[0] : '';
                    $quote_products_list[$index]['QuoteProduct']['delivery'] = isset($attr[1]) ? $attr[1] : '';
                    $quote_products_list[$index]['QuoteProduct']['notes'] = isset($attr[2]) ? $attr[2] : '';
                }
                ++$index;
            }
            $this->set('quote_products_list', $quote_products_list);
        } else {
            if ($id > 0) {
                $this->set('title_for_layout', $this->ld['quote_edit'].' - '.$id.' - '.$this->configs['shop_name']);
                $this->navigations[] = array('name' => $this->ld['quote_edit'].' -'.$id,'url' => '');
                $quote_products_list = $this->QuoteProduct->find('all', array('conditions' => array('QuoteProduct.quote_id' => $id)));
                $this->set('quote_products_list', $quote_products_list);
            } else {
                $this->set('title_for_layout', $this->ld['quote_create'].' -'.$this->configs['shop_name']);
                $this->navigations[] = array('name' => $this->ld['quote_create'],'url' => '');
            }
        }

        $quote_list = $this->Quote->find('first', array('conditions' => array('Quote.id' => $id)));
        if ($quote_list['Quote']['enquiry_id'] != 0) {
            $this->set('enquiry_id', $quote_list['Quote']['enquiry_id']);
        }
        if (!empty($enquiry_info) && empty($quote_list)) {
            $quote_list['Quote']['customer_name'] = $enquiry_info['Enquiry']['contact_person'];
            $quote_list['Quote']['email'] = $enquiry_info['Enquiry']['email'];
            $quote_list['Quote']['contact_person'] = $enquiry_info['Enquiry']['contact_person'];
        }
        $this->set('quote_list', $quote_list);
    }

    public function Remove($id)
    {
        $this->operator_privilege('quotes_remove');
        $this->Quote->delete(array('Quote.id' => $id));
        $this->QuoteProduct->deleteAll(array('QuoteProduct.quote_id' => $id));
        $this->redirect('/quotes');
    }

    /**
     *  批量删除.
     */
    public function removeAll()
    {
        $chat_checkboxes = $_POST['checkboxes'];
        if ($chat_checkboxes != 0) {
            $this->Quote->deleteAll(array('Quote.id' => $chat_checkboxes));
            $result['flag'] = 1;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function saveprouduct($id)
    {
        $this->operator_privilege('quotes_edit');
        $data = date('Y-m-d');
        $Quote_data = $_REQUEST['data1'];
        if ($id > 0) {
            $Quote_data['Quote']['id'] = $id;
        }
        if ($Quote_data['Quote']['inquire_date'] == '') {
            $Quote_data['Quote']['inquire_date'] = $data;
        }
        $Quote_data['Quote']['quoted_by'] = $this->admin['name'];
        $this->Quote->saveAll($Quote_data);
        if ($id == 0) {
            $newid = $this->Quote->id;
        } else {
            $this->QuoteProduct->deleteAll(array('QuoteProduct.quote_id' => $id));
        }
        if (isset($_REQUEST['data'])) {
            foreach ($_REQUEST['data'] as $k => $v) {
                if ($id > 0) {
                    $v['QuoteProduct']['quote_id'] = $id;
                    $quote_products_list = $this->QuoteProduct->find('count', array('conditions' => array('QuoteProduct.quote_id' => $id, 'QuoteProduct.product_code' => $v['QuoteProduct']['product_code'])));
                    pr($quote_products_list);
                    if ($quote_products_list > 0) {
                        $this->QuoteProduct->deleteAll(array('QuoteProduct.quote_id' => $id, 'QuoteProduct.product_code' => $v['QuoteProduct']['product_code']));
                    }
                } else {
                    $v['QuoteProduct']['quote_id'] = $newid;
                    $quote_products_list = $this->QuoteProduct->find('count', array('conditions' => array('QuoteProduct.quote_id' => $newid, 'QuoteProduct.product_code' => $v['QuoteProduct']['product_code'])));
                    if ($quote_products_list > 0) {
                        $this->QuoteProduct->deleteAll(array('QuoteProduct.quote_id' => $newid, 'QuoteProduct.product_code' => $v['QuoteProduct']['product_code']));
                    }
                }
                $this->QuoteProduct->saveAll($v);
            }
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
    }

    public function sendemail($id)
    {
        $this->operator_privilege('quotes_edit');
        $data = date('Y-m-d');
        $Quote_data = $_REQUEST['data1'];
        if ($id > 0) {
            $Quote_data['Quote']['id'] = $id;
        }
        if ($Quote_data['Quote']['inquire_date'] == '') {
            $Quote_data['Quote']['inquire_date'] = $data;
        }
        $Quote_data['Quote']['quoted_by'] = $this->admin['name'];
        $Quote_data['Quote']['is_sendmail'] = '1';
        $result = $this->Quote->saveAll($Quote_data);
        if ($id == 0) {
            $newid = $this->Quote->id;
        } else {
            $this->QuoteProduct->deleteAll(array('QuoteProduct.quote_id' => $id));
        }
        if (isset($_REQUEST['data'])) {
            foreach ($_REQUEST['data'] as $k => $v) {
                if ($id > 0) {
                    $v['QuoteProduct']['quote_id'] = $id;
                    $quote_products_list = $this->QuoteProduct->find('count', array('conditions' => array('QuoteProduct.quote_id' => $id, 'QuoteProduct.product_code' => $v['QuoteProduct']['product_code'])));
                    if ($quote_products_list > 0) {
                        $this->QuoteProduct->deleteAll(array('QuoteProduct.quote_id' => $id, 'QuoteProduct.product_code' => $v['QuoteProduct']['product_code']));
                    }
                } else {
                    $v['QuoteProduct']['quote_id'] = $newid;
                    $quote_products_list = $this->QuoteProduct->find('count', array('conditions' => array('QuoteProduct.quote_id' => $newid, 'QuoteProduct.product_code' => $v['QuoteProduct']['product_code'])));
                    if ($quote_products_list > 0) {
                        $this->QuoteProduct->deleteAll(array('QuoteProduct.quote_id' => $newid, 'QuoteProduct.product_code' => $v['QuoteProduct']['product_code']));
                    }
                }
                $this->QuoteProduct->saveAll($v);
            }

            if ($result && isset($this->configs['mail-account']) && !empty($this->configs['mail-account'])) {
                $send_date = date('Y-m-d');
                $shop_name = $this->configs['shop_name'];
//    			$this->Email->smtpHostNames = "".$this->configs['mail-smtp']."";
//			    $this->Email->smtpUserName = "".$this->configs['mail-account']."";
//			    $this->Email->smtpPassword = "".$this->configs['mail-password']."";
//				$this->Email->is_ssl = $this->configs['mail-ssl'];
//				$this->Email->is_mail_smtp = $this->configs['mail-service'];
//				$this->Email->smtp_port = $this->configs['mail-port'];
//			    $this->Email->from = "".$this->configs['mail-account']."";
                //要发的邮件地址
//			    $this->Email->to = $_REQUEST['data1']['Quote']['email'];
//			    $this->Email->fromName =$shop_name;
                $template = $this->MailTemplate->find("code = 'quotes_product' and status = 1");
                /*
                //模板1
                $template_str=$template['MailTemplateI18n']['html_body'];
                $Operator=$this->admin['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$this->admin['email'];
                $template_str=str_replace('$customer_name',$_REQUEST['data1']['Quote']['customer_name'],$template_str);
                   $template_str=str_replace('$contact_person',$_REQUEST['data1']['Quote']['contact_person'],$template_str);
                   $template_str=str_replace('$sent_date',$send_date,$template_str);
                   $template_str=str_replace('$email',$_REQUEST['data1']['Quote']['email'],$template_str);
                   $template_str=str_replace('$quoted_by',$Operator,$template_str);
                   $template_str=str_replace('$remark',$_REQUEST['data1']['Quote']['remark'],$template_str);
                   $email_product_info='<table cellpadding="0" cellspacing="0" width="100%" style="border:1px solid black;"><tbody><tr class="thead">';
                   $email_product_info.='<th class="thname thbang" style="border-right:1px solid black;">Part Num</th><th class="thname thbang" style="border-right:1px solid black;">Brand</th><th class="thqty" style="border-right:1px solid black;">Qty Offered</th><th class="thqty" style="border-right:1px solid black;">Qty Requested</th><th class="thqty" style="border-right:1px solid black;">Offered Price</th><th class="thqty" style="border-right:1px solid black;">Target Price</th><th class="thname thbang" style="border-right:1px solid black;">Payment Terms</th><th style="border-right:1px solid black;">D/C</th><th>Delivery</th></tr>';
                foreach($_REQUEST['data'] as $k=>$v){
                    $email_product_info.='<tr><td style="text-align:center;border-top:1px solid black;border-right:1px solid black;">'.$v['QuoteProduct']['product_code'];
                    $email_product_info.='</td><td style="text-align:center;border-top:1px solid black;border-right:1px solid black;">'.$v['QuoteProduct']['brand_code'];
                    $email_product_info.='</td><td style="text-align:center;border-top:1px solid black;border-right:1px solid black;">'.$v['QuoteProduct']['qty_offered'];
                    $email_product_info.='</td><td style="text-align:center;border-top:1px solid black;border-right:1px solid black;">'.$v['QuoteProduct']['qty_requested'];
                    $email_product_info.='</td><td style="text-align:center;border-top:1px solid black;border-right:1px solid black;">'.$v['QuoteProduct']['offered_price'];
                    $email_product_info.='</td><td style="text-align:center;border-top:1px solid black;border-right:1px solid black;">'.$v['QuoteProduct']['target_price'];
                    $email_product_info.='</td><td style="text-align:center;border-top:1px solid black;border-right:1px solid black;">'.$v['QuoteProduct']['payment_terms'];
                    $email_product_info.='</td><td style="text-align:center;border-top:1px solid black;border-right:1px solid black;">'.$v['QuoteProduct']['data_code'];
                    $email_product_info.='</td><td style="text-align:center;border-top:1px solid black;border-right:1px solid black;">'.$v['QuoteProduct']['delivery'].'</td></tr>';
                    $email_product_info.='</td><td style="text-align:center;border-top:1px solid black;">'.$v['QuoteProduct']['notes'].'</td></tr>';
                }
                $email_product_info.='</tbody></table>';
                $template_str=str_replace('$products_info',$email_product_info,$template_str);
                  $this->Email->html_body = $template_str;
                $subject = $template['MailTemplateI18n']['title'];
                $subject=str_replace('$shop_name',$shop_name,$subject);
                $this->Email->html_body = $template_str;
                */
                //模板2
                if ($Quote_data['Quote']['mail_title'] == '0') {
                    $mail_title = '';
                }
                if ($Quote_data['Quote']['mail_title'] == '1') {
                    $mail_title = "<b style='font-size:20pt;'>Arcotek International Ltd.</b><br/><span style='font-size:10pt;'>Unit 609, Tower 1, Cheung Sha Wan Plaza, 833 Cheung Sha Wan Road<br/>Tel:(852) 2381-7880  Fax:(852) 2381-2550  Web: www.arcotek.com</span>";
                }
                if ($Quote_data['Quote']['mail_title'] == '2') {
                    $mail_title = "<b style='font-size:20pt;'>Arcotek Elektronik GmbH</b><br/><span style='font-size:10pt;'>Rupert-Mayer-StraBe 44, Geb. 64-07, D-81379, Munchen, Germany<br/>Phone : (49) 89-54805-112  Fax: (49) 89-54805-123  web: www.arcoinc.com<br/>Geschäftsführer Adil Ansari;   Amtsgericht München;   HRB Nummer 157088   VAT# DE814432181</span>";
                }
                if ($Quote_data['Quote']['mail_title'] == '3') {
                    $mail_title = "<b style='font-size:20pt;'>ARCO, INC.</b><br/><span style='font-size:10pt;'>ISO 9001:2008 Certified<br/>300 State Route 17, Unit K, Mahwah, NJ 07430 USA<br/>Phone : 201-828-9808   Fax: 201-828-5955  web: www.arcoinc.com</span>";
                }
                $template_str = $template['MailTemplateI18n']['html_body'];
                $Operator = $this->admin['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$this->admin['email'];
                $template_str = str_replace('$customer_name', $_REQUEST['data1']['Quote']['customer_name'], $template_str);
                $template_str = str_replace('$contact_person', $_REQUEST['data1']['Quote']['contact_person'], $template_str);
                $template_str = str_replace('$sent_date', $send_date, $template_str);
                $template_str = str_replace('$email', $_REQUEST['data1']['Quote']['email'], $template_str);
                $template_str = str_replace('$quoted_by', $Operator, $template_str);
                $template_str = str_replace('$remark', $_REQUEST['data1']['Quote']['remark'], $template_str);
                $template_str = str_replace('$mail_title', $mail_title, $template_str);
                $email_product_info = '';
                foreach ($_REQUEST['data'] as $k => $v) {
                    $email_product_info .= '<table width="400" border="0" cellspacing="0" cellpadding="0"><tr><td width="110px;" style="line-height:22px; padding-left:5px; border-top:1px solid; border-left:1px solid; border-right:1px solid; border-color:#000;">'.$this->ld['code'].'</td><td style="line-height:22px; padding-left:5px; border-top:1px solid; border-right:1px solid; border-color:#000;">&nbsp;'.$v['QuoteProduct']['product_code'].'</td></tr>';
                    $email_product_info .= '<tr><td width="110px;" style="line-height:22px; padding-left:5px; border-top:1px solid; border-left:1px solid; border-right:1px solid; border-color:#000;">'.$this->ld['brand'].'</td><td style="line-height:22px; padding-left:5px; border-top:1px solid; border-right:1px solid; border-color:#000;">&nbsp;'.$v['QuoteProduct']['brand_code'].'</td></tr>';
                    $email_product_info .= '<tr><td width="110px;" style="line-height:22px; padding-left:5px; border-top:1px solid; border-left:1px solid; border-right:1px solid; border-color:#000;">'.$this->ld['qty_offered'].'</td><td style="line-height:22px; padding-left:5px; border-top:1px solid; border-right:1px solid; border-color:#000;">&nbsp;'.$v['QuoteProduct']['qty_offered'].'</td></tr>';
                    $email_product_info .= '<tr><td width="110px;" style="line-height:22px; padding-left:5px; border-top:1px solid; border-left:1px solid; border-right:1px solid; border-color:#000;">'.$this->ld['qty_req'].'</td><td style="line-height:22px; padding-left:5px; border-top:1px solid; border-right:1px solid; border-color:#000;">&nbsp;'.$v['QuoteProduct']['qty_requested'].'</td></tr>';
                    $email_product_info .= '<tr><td width="110px;" style="line-height:22px; padding-left:5px; border-top:1px solid; border-left:1px solid; border-right:1px solid; border-color:#000;">'.$this->ld['offered_price'].'</td><td style="line-height:22px; padding-left:5px; border-top:1px solid; border-right:1px solid; border-color:#000;">&nbsp;'.$v['QuoteProduct']['offered_price'].'</td></tr>';
                    $email_product_info .= '<tr><td width="110px;" style="line-height:22px; padding-left:5px; border-top:1px solid; border-left:1px solid; border-right:1px solid; border-color:#000;">'.$this->ld['target_price'].'</td><td style="line-height:22px; padding-left:5px; border-top:1px solid; border-right:1px solid; border-color:#000;">&nbsp;'.$v['QuoteProduct']['target_price'].'</td></tr>';
                    $email_product_info .= '<tr><td width="110px;" style="line-height:22px; padding-left:5px; border-top:1px solid; border-left:1px solid; border-right:1px solid; border-color:#000;">'.$this->ld['attribute'].'</td><td style="line-height:22px; padding-left:5px; border-top:1px solid; border-right:1px solid; border-color:#000;">&nbsp;'.(isset($v['QuoteProduct']['data_code']) ? $v['QuoteProduct']['data_code'] : '').'</td></tr>';
                    $email_product_info .= '<tr><td width="110px;" style="line-height:22px; padding-left:5px; border:1px solid;">'.$this->ld['notes'].'</td><td style="line-height:22px; padding-left:5px; border-top:1px solid; border-bottom:1px solid; border-right:1px solid; border-color:#000;">&nbsp;'.(isset($v['QuoteProduct']['notes']) ? $v['QuoteProduct']['notes'] : '').'</td></tr></table><br/>';
                }
                $template_str = str_replace('$products_info', $email_product_info, $template_str);
                //$this->Email->html_body = $template_str;


                $subject = $template['MailTemplateI18n']['title'];
                $subject = str_replace('$shop_name', $shop_name, $subject);
                $mail_send_queue = array(
                                        'id' => '',
                                        'sender_name' => $this->admin['name'],
                                        'receiver_email' => $_REQUEST['data1']['Quote']['customer_name'].';'.$_REQUEST['data1']['Quote']['email'],
                                        'cc_email' => ';',
                                        'bcc_email' => ';',
                                        'title' => $subject,
                                        'html_body' => $template_str,
                                        'text_body' => $template_str,
                                        'sendas' => 'html',
                                        'flag' => 0,
                                        'pri' => 0,
                                        );
                //$mailsendname=$this->admin['name'].";".$this->admin['email'].";".$this->admin['name'].";".$this->admin['email'];
                $this->Email->send_mail($this->backend_locale, 1, $mail_send_queue, $this->configs);
            }
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
    }
      /**
       *搜索商品供AJAX使用.
       */
      public function searchProducts()
      {
          $this->operator_privilege('quotes_edit');
          $condition = '';
        //设置返回初始参数
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['no_products_result'];
        //搜索条件
        $product_keyword = empty($_REQUEST['product_keyword']) ? '' : $_REQUEST['product_keyword'];//关键字
        //设置语言
        $this->Product->set_locale($this->backend_locale);
        //初始化条件

        if ($product_keyword != '') {
            $keyword = preg_split('#\s+#', $product_keyword);
            foreach ($keyword as $k => $v) {
                $conditions_p18n['AND']['or'][0]['and'][]['ProductI18n.name like'] = "%$v%";
            }
            $product18n_pid = $this->ProductI18n->find_product18n_pid($conditions_p18n); //model
            $condition['AND']['OR']['Product.id'] = $product18n_pid;
            $condition['AND']['OR']['Product.code like'] = "%$v%";
        }
          $fields[] = 'Product.id';
          $fields[] = 'Product.code';
          $fields[] = 'ProductI18n.name';
          $product_tree = $this->Product->find('all', array('conditions' => $condition, 'order' => 'Product.id desc', 'fields' => $fields));
          $p_arr = array();
          $i = 0;
          foreach ($product_tree as $k => $v) {
              $p_arr[$i] = $v;
              ++$i;
          }
          if (count($p_arr) > 0) {
              $result['flag'] = 1;
              $result['content'] = $p_arr;
          }
          Configure::write('debug', 0);
          $this->layout = 'ajax';
          die(json_encode($result));
      }

    //上传单个商品 返回表格预览
        public function submit_single()
        {
            $this->operator_privilege('quotes_edit');
            Configure::write('debug', 0);
            $result['flag'] = 2;
            $result['content'] = '操作失败！';
            $product_code = empty($_REQUEST['product']) ? '' : $_REQUEST['product'];
            $index = empty($_REQUEST['k']) ? '0' : $_REQUEST['k'];
            $this->Product->set_locale($backend_locale);
            $product_name = $this->Product->find('first', array('conditions' => array('Product.code' => $product_code)));
            //取出所有公共属性
            $this->Attribute->set_locale($this->backend_locale);
            $public_attr_ids = $this->ProductTypeAttribute->getattrids(0);
            $public_attr_info = array();
            $public_attr_list = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $public_attr_ids, 'Attribute.status' => 1)));
            if (!empty($product_name)) {
                $p_ids = $product_name['Product']['id'];
            }
            if (!empty($public_attr_info)) {
                foreach ($public_attr_info as $pat) {
                    $pat_ids[] = $pat['Attribute']['id'];
                }
            }
            $attr_info = $this->ProductAttribute->product_list_format($p_ids, $pat_ids, $backend_locale);
            $i = 0;
            foreach ($public_attr_info as $pa) {
                $attr[$i] = $attr_info[$product_name['Product']['id']][$pa['Attribute']['id']] != '' ? $attr_info[$product_name['Product']['id']][$pa['Attribute']['id']] : '-';
                ++$i;
            }
            $brand_code = $this->Brand->find('first', array('conditions' => array('Brand.Id' => $product_name['Product']['brand_id']), 'fields' => array('Brand.code')));
            if (!empty($product_name)) {
                $product_data = array();
                $product_data[$index]['code'] = $product_name['Product']['code'];
                $product_data[$index]['brand_id'] = isset($brand_code['Brand']['code']) ? $brand_code['Brand']['code'] : '';
                $product_data[$index]['quantity'] = $product_name['Product']['quantity'];
                $product_data[$index]['shop_price'] = $product_name['Product']['shop_price'];
                $product_data[$index]['attr'] = $attr;
                $result['content'] = $product_data;
                $result['flag'] = 1;
                die(json_encode($result));
            } else {
                $this->layout = 'ajax';
                Configure::write('debug', 0);
                die(json_encode($reslut));
            }
        }

    /**
     *列表批量操作.
     *
     *@param string $type 类型
     */
    public function batch_operations($type)
    {
        $this->Quote->hasOne = array();
        $quote_checkboxes = array();

        if ($type == 'export_csv') {
            $this->noprofile_exprot_out($_REQUEST['export_csv']);
        }
        exit();
    }

      //无配置文档时导出
      public function noprofile_exprot_out($export_csv)
      {
          $this->Quote->hasOne = array();
          $quote_checkboxes = array();
          $quote_check = array();
          $str = 'Date,Sales,Customer,Contact,Part #,MGF,Qty Req,T/P,Qty Offered,D/C,Dly,Q/P';
          if ($export_csv == 'choice_export') {
              if (isset($_POST['method'])) {
                  $quote_check = explode('&checkboxes[]=', $_POST['method']);
              }
              foreach ($quote_check as $k => $v) {
                  if (!empty($v)) {
                      $quote_checkboxes[] = $v;
                  }
              }
          }
          if (!empty($quote_checkboxes) && $export_csv == 'choice_export') {
              $p = $this->Quote->find('all', array('conditions' => array('Quote.id' => $quote_checkboxes)));
          }
          if ($export_csv == 'all_export_csv') {
              $p = $this->Quote->find('all');
          }
          $p_ids = array();
          if (!empty($p)) {
              foreach ($p as $s) {
                  $p_ids[] = $s['Quote']['id'];
              }
          }
          $newdatas = array();
          $datas = array();
          $datas[] = array($str);
          foreach ($datas[0] as $v) {
              $newdatas[] = explode(',', $v);
          }
          if (!empty($p)) {
              foreach ($p as $v) {
                  $quote_product = $this->QuoteProduct->find('all', array('conditions' => array('QuoteProduct.quote_id ' => $v['Quote']['id'])));
                  foreach ($quote_product as $pv) {
                      $newdata = array();
                      $newdata[] = date('YmdHis');
                      $newdata[] = $v['Quote']['quoted_by'];
                      $newdata[] = $v['Quote']['customer_name'];
                      $newdata[] = $v['Quote']['contact_person'];
                      $newdata[] = $pv['QuoteProduct']['product_code'];
                      $newdata[] = $pv['QuoteProduct']['brand_code'];
                      $newdata[] = $pv['QuoteProduct']['qty_requested'];
                      $newdata[] = $pv['QuoteProduct']['target_price'];
                      $newdata[] = $pv['QuoteProduct']['qty_offered'];
                      $newdata[] = $pv['QuoteProduct']['data_code'];
                      $newdata[] = $pv['QuoteProduct']['delivery'];
                      $newdata[] = $pv['QuoteProduct']['offered_price'];
                      $newdatas[] = $newdata;
                  }
              }
          }
          $this->Phpexcel->output('Quotes'.date('YmdHis').'.xls', $newdatas);
          exit;
      }
}
