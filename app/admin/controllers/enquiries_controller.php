<?php

/*****************************************************************************
 * Seevia 询价
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
class EnquiriesController extends AppController
{
    public $name = 'Enquiries';
    public $helpers = array('Pagination','Html');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('Enquiry','InformationResource','InformationResourceI18n','OperatorLog','Product','User','UserBalanceLog');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('enquiries_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/enquiries/');
        $this->set('title_for_layout', $this->ld['enquiry'].'-'.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['enquiry'],'url' => '');
        //$this->navigations[]=array('name'=>$this->ld['Enquiry_us'],'url'=>'');
        $condition = '';
        if (isset($this->params['url']['kword_name']) && $this->params['url']['kword_name'] != '') {
            $product_codes = $this->Product->find('all', array('conditions' => array('ProductI18n.name like' => '%'.$this->params['url']['kword_name'].'%')));
            $product_code_list = array();
            foreach ($product_codes as $k => $v) {
                $product_code_list[] = $v['Product']['code'];
            }
            if (sizeof($product_code_list) > 0) {
                $condition['and']['or']['part_num'] = $product_code_list;
            }
            $user_cond['or']['User.name like'] = '%'.$this->params['url']['kword_name'].'%';
            $user_cond['or']['User.user_sn like'] = '%'.$this->params['url']['kword_name'].'%';
            $user_ids = $this->User->find('list', array('fields' => array('User.id'), 'conditions' => $user_cond));
            $condition['and']['or']['user_id'] = $user_ids;
            $condition['and']['or']['contact_person like'] = '%'.$this->params['url']['kword_name'].'%';
            $condition['and']['or']['email like'] = '%'.$this->params['url']['kword_name'].'%';
            //$condition["and"]["or"]["website like"] = "%".$this->params['url']['kword_name']."%";
            $this->set('kword_name', $this->params['url']['kword_name']);
        }
        if (isset($this->params['url']['date']) && $this->params['url']['date'] != '') {
            $condition['and']['created >='] = $this->params['url']['date'].' 00:00:00';
            $this->set('date', $this->params['url']['date']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
            $condition['and']['created <='] = $this->params['url']['date2'].' 23:59:59';
            $this->set('date2', $this->params['url']['date2']);
        }
        if (isset($this->params['url']['enquiry_status']) && $this->params['url']['enquiry_status'] != '') {
            $condition['status'] = $this->params['url']['enquiry_status'];
            $this->set('enquiry_status', $this->params['url']['enquiry_status']);
        }
        $total = $this->Enquiry->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'Enquiries','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Enquiry');
        $this->Pagination->init($condition, $parameters, $options);
        $Enquiry_info = $this->Enquiry->find('all', array('conditions' => $condition, 'order' => 'id desc', 'page' => $page, 'limit' => $rownum));
        if (!empty($Enquiry_info) && sizeof($Enquiry_info) > 0) {
            $product_code = array();
            $user_id_arr = array();
            foreach ($Enquiry_info as $k => $v) {
                //$product_code[]=$v['Enquiry']['part_num'];
                $code_arr = explode(';', $v['Enquiry']['part_num']);
                foreach ($code_arr as $cv) {
                    $product_code[] = $cv;
                }
                $user_id_arr[] = $v['Enquiry']['user_id'];
            }

            $this->Product->set_locale($this->backend_locale);
            $product_code_arr = $this->Product->find('all', array('fields' => array('Product.code', 'ProductI18n.name'), 'conditions' => array('Product.code' => $product_code, 'ProductI18n.locale' => $this->backend_locale)));
            //pr($product_code);
            $product_code_list = array();
            foreach ($product_code_arr as $k => $v) {
                $product_code_list[$v['Product']['code']] = $v['ProductI18n']['name'];
            }
            $this->set('product_code_list', $product_code_list);
            $user_info_list = $this->User->find('list', array('fields' => array('User.id', 'User.name'), 'conditions' => array('User.id' => $user_id_arr)));
            $this->set('user_info_list', $user_info_list);
        }
        $this->set('Enquiry_info', $Enquiry_info);
        $this->set($this->ld['enquiry'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($id)
    {
        /*判断权限*/
    //	$this->operator_privilege('enquiries_detail');
        /*end*/
        $this->Product->set_locale($this->backend_locale);
        $this->menu_path = array('root' => '/crm/','sub' => '/enquiries/');
        $this->set('title_for_layout', $this->ld['enquiry'].'-'.$this->ld['details_view'].'-'.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['enquiry'],'url' => '/Enquiries/');
        $this->navigations[] = array('name' => $this->ld['view_detail'],'url' => '');
        $shop_name = $this->configs['shop_name'];
        $Enquiry_list = $this->Enquiry->find('first', array('conditions' => array('Enquiry.id' => $id)));
        $this->set('Enquiry_list', $Enquiry_list);
        if (!empty($Enquiry_list)) {
            $this->set('enquiry_status', $Enquiry_list['Enquiry']['status']);
            $product_code = array();
            $code_arr = explode(';', $Enquiry_list['Enquiry']['part_num']);
            foreach ($code_arr as $cv) {
                $product_code[] = $cv;
            }
            $product_Info = $this->Product->find('first', array('fields' => array('ProductI18n.name'), 'conditions' => array('Product.code' => $product_code)));
            if (!empty($product_Info)) {
                $this->set('product_Info', $product_Info);
            }
            $user_Info = $this->User->find('first', array('conditions' => array('User.id' => $Enquiry_list['Enquiry']['user_id'])));
            if (!empty($user_Info)) {
                $this->set('user_Info', $user_Info);
            }
        }
        if ($this->RequestHandler->isPost()) {
            $this->Enquiry->save(array('Enquiry' => $this->data['Enquiry']));
            if ($Enquiry_list['Enquiry']['status'] == '0' && $this->data['Enquiry']['status'] == '1') {
                $price = explode(';', $this->data['Enquiry']['target_price']);
                if (count($price) == 1) {
                    $price = $price[0];
                    $user = $this->User->find('first', array('conditions' => array('User.id' => $this->data['Enquiry']['user_id'])));
                    $user['User']['balance'] = bcsub($user['User']['balance'], $price, 2);
                    $this->User->save($user['User']);

                    //记录资金日志
                    $BalanceLog['UserBalanceLog']['user_id'] = $this->data['Enquiry']['user_id'];
                    $BalanceLog['UserBalanceLog']['amount'] = '-'.$price;
                    $BalanceLog['UserBalanceLog']['admin_user'] = $this->admin['name'];
                    $BalanceLog['UserBalanceLog']['admin_note'] = '';
                    $BalanceLog['UserBalanceLog']['system_note'] = $this->ld['enquiry'].':'.$product_Info['ProductI18n']['name'].';'.$this->ld['price'].':'.$price.$this->ld['app_yuan'].';余额:'.($user['User']['balance']).$this->ld['app_yuan'];
                    $BalanceLog['UserBalanceLog']['log_type'] = 'B';
                    $BalanceLog['UserBalanceLog']['type_id'] = 0;
                    $this->UserBalanceLog->save($BalanceLog);
                }
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
    }

    //删除
    public function batch()
    {
        //echo "-=-s=a-f=a-s=fd-=";
        /*判断权限*/
    //	$this->operator_privilege('enquiries_remove');
        /*end*/
        $art_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        if (!empty($art_ids)) {
            $condition['Enquiry.id'] = $art_ids;
            $this->Enquiry->deleteAll($condition);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'批量删除询价', $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
        } else {
            //  $this->flash("请选择处理",'/articles/','');
           $this->redirect('/enquiries/');
        }
    }
}
