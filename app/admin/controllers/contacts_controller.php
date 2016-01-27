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
class ContactsController extends AppController
{
    public $name = 'Contacts';
    public $helpers = array('Pagination','Html');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('Contact','InformationResource','InformationResourceI18n','OperatorLog');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('contacts_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/contacts/');
        /*end*/
        //$isHave=$this->Application->find('first',array('conditions'=>array('Application.code'=>'APP-NAV-CONTACTS')));
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['contacts_us'],'url' => '');

        $condition = '';
        if (isset($this->params['url']['kword_name']) && $this->params['url']['kword_name'] != '') {
            $condition['and']['or']['company like'] = '%'.$this->params['url']['kword_name'].'%';
            $condition['and']['or']['company_url like'] = '%'.$this->params['url']['kword_name'].'%';
            $condition['and']['or']['contact_name like'] = '%'.$this->params['url']['kword_name'].'%';
            $condition['and']['or']['email like'] = '%'.$this->params['url']['kword_name'].'%';
            $condition['and']['or']['parameter_01 like'] = '%'.$this->params['url']['kword_name'].'%';
            $condition['and']['or']['parameter_02 like'] = '%'.$this->params['url']['kword_name'].'%';
            $condition['and']['or']['parameter_03 like'] = '%'.$this->params['url']['kword_name'].'%';
            $this->set('kword_name', $this->params['url']['kword_name']);
        }
        if (isset($this->params['url']['contact_us_type']) && trim($this->params['url']['contact_us_type'])!= '') {
            $condition['and']['contact_type'] = trim($this->params['url']['contact_us_type']);
            $this->set('contact_us_type', $this->params['url']['contact_us_type']);
        }
        if (isset($this->params['url']['date']) && $this->params['url']['date'] != '') {
            $condition['and']['created >='] = $this->params['url']['date'].' 00:00:00';
            $this->set('date', $this->params['url']['date']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
            $condition['and']['created <='] = $this->params['url']['date2'].' 23:59:59';
            $this->set('date2', $this->params['url']['date2']);
        }
        $total = $this->Contact->find('count', array('conditions' => $condition));
        $sortClass = 'Contact';
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'contacts','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Contact');
        $this->Pagination->init($condition, $parameters, $options);
        $contact_info = $this->Contact->find('all', array('conditions' => $condition, 'order' => 'id desc', 'page' => $page, 'limit' => $rownum));
        $this->set('contact_info', $contact_info);
           //信息库
           $this->InformationResource->set_locale($this->locale);
        $InformationResource = $this->InformationResource->information_formated('company_type', $this->locale);
        $this->set('InformationResource', $InformationResource);
        $Resource_info = $this->Resource->getformatcode(array('contact_us_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        $this->set('title_for_layout', $this->ld['contacts_us'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($id)
    {
        /*判断权限*/
        $this->operator_privilege('contacts_detail');
        $this->menu_path = array('root' => '/crm/','sub' => '/contacts/');
        /*end*/

        $this->InformationResource->set_locale($this->locale);
        $all_information_info = $this->InformationResource->find('all');

        foreach ($all_information_info as $v) {
            $information_info[$v['InformationResourceI18n']['id']] = $v['InformationResourceI18n']['name'];
        }
        $this->set('information_info', $information_info);
        $this->set('title_for_layout', $this->ld['contacts_us'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['contacts_us'],'url' => '/contacts/');
        $this->navigations[] = array('name' => $this->ld['view_detail'],'url' => '');
	
        $shop_name = $this->configs['shop_name'];
        $this->data = $this->Contact->findbyid($id);
        $this->set('this->data', $this->data);
        $this->navigations[] = array('name' => $this->data['Contact']['company'],'url' => '');
        $Resource_info = $this->Resource->getformatcode(array('contact_us_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        
        $locale_google_translate_code=$this->Language->info['backend']['google_translate_code'];
        $this->set('locale_google_translate_code',$locale_google_translate_code);
    }

    //批量处理
    public function batch()
    {
        if (!empty($_REQUEST['checkboxes'])) {
            foreach ($_REQUEST['checkboxes'] as $k => $v) {
                $art_ids[] = $v;
            }
            $condition['Contact.id'] = $art_ids;
            $this->Contact->deleteAll($condition);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        } else {
            $this->redirect('/contacts/');
        }
    }

    /**
     *删除联系我们.
     *
     *@param int $id 输入ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_route_failure'];

        $this->Contact->deleteAll(array('Contact.id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_article'].' '.' id:'.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_article_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
    public function quick_reply(){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
        	$contact_id=isset($_POST['contact_id'])?$_POST['contact_id']:0;
        	$quick_reply_content=isset($_POST['quick_reply_content'])?$_POST['quick_reply_content']:'';
        	$contact_data = $this->Contact->findbyid($contact_id);
        	$result=array();
        	$result['flag']='0';
        	$result['message']='Data Error';
        	if(!empty($contact_data)&&!empty($quick_reply_content)){
        		$mailsendqueue = array(
	                    'sender_name' => empty($this->configs['shop_name']) ? '--' : $this->configs['shop_name'],//发送从姓名
	                    'receiver_email' => $contact_data['Contact']['contact_name'].';'.$contact_data['Contact']['email'],//接收人姓名;接收人地址
	                    'cc_email' => '',//抄送人
	                    'bcc_email' => '',//暗送人
	                    'title' => $this->ld['contacts_us'].'-'.$this->ld['reply'],//主题
	                    'html_body' => $quick_reply_content,//内容
	                    'text_body' => $quick_reply_content,//内容
	                    'sendas' => 'html',
	                );
	              $mail_result=$this->Email->send_mail($this->backend_locale,1,$mailsendqueue,$this->configs);
	              if($mail_result){
	              	$result['flag']='1';
	              	$result['message']=$this->ld['send_success'];
	              }else{
	              	$result['message']=$mail_result;
	              }
        	}
        	die(json_encode($result));
    }
}
