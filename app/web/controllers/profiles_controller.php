<?php

/*****************************************************************************
 * Seevia 用户我的信息
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
uses('sanitize');
/**
 *这是一个名为 ProfilesController 的用户编辑控制器.
 */
class ProfilesController extends AppController
{
    /*
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses
    */

    public $name = 'Profiles';
    public $helpers = array('Html');
    public $components = array('RequestHandler'); // Added 
    public $uses = array('User','UserAddress','UserInfo','UserInfoValue','Region');

    /**
     *函数 user_index 用于进入用户的信息.
     */
    public function user_index()
    {
        //未登录转登录页
        if (!isset($_SESSION['User'])) {
            $this->redirect('/login/');
        }
        $this->page_init();
        $this->getHeadBarInformation($_SESSION['User']['User']['id']);
        if ($this->RequestHandler->isPost()) {
            $birthday = trim($this->params['form']['date']);
            $telephone = trim($this->params['form']['Utel0']).'-'.trim($this->params['form']['Utel1']).'-'.
            trim($this->params['form']['Utel2']);
            $this->data['User']['birthday'] = $birthday;
            $this->data['UserAddress']['telephone'] = $telephone;
            $this->data['UserAddress']['regions'] = '';
            foreach ($this->data['Address']['Region'] as $k => $v) {
                $this->data['UserAddress']['regions'] .= $v.' ';
            }
            $this->User->save($this->data['User']);
            $this->UserAddress->save($this->data['UserAddress']);
            if (!empty($this->params['form']['info_value']) && is_array($this->params['form']['info_value'])) {
                foreach ($this->params['form']['info_value'] as $k => $v) {
                    $info_value = array(
                                             'id' => $this->params['form']['info_value_id'][$k],
                                             'user_id' => $this->data['User']['id'],
                                             'user_info_id' => $k,
                                             'value' => $this->params['form']['info_value'][$k],
                              );
                    $this->UserInfoValue->save(array('UserInfoValue' => $info_value));
                }
            }
            $this->pageTitle = $this->ld['tips_edit_success'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['tips_edit_success'], '/profiles/', '');
        }
         //当前位置
         $this->ur_heres[] = array('name' => __($this->ld['my_information'], true),'url' => '');
        $this->set('ur_heres', $this->ur_heres);

        $user_id = $_SESSION['User']['User']['id'];
         //echo $user_id;
         //取得个人信息
         $user_info = $this->User->find(" User.id = '".$user_id."'");
        $this->data['profiles'] = $user_info;
         //用户默认地址
         $default_address = $this->UserAddress->find(" UserAddress.user_id = '".$user_id."'");
        if (!empty($default_address)) {
            $this->data['profiles']['UserAddress'] = $default_address['UserAddress'];
        }
         //用户项目信息
           $condition = ' UserInfoValue.user_id='.$user_id;
        $res = $this->UserInfoValue->findall($condition);
        $values_id = array();
        foreach ($res as $k => $v) {
            $user_info_value[$v['UserInfoValue']['user_info_id']]['UserInfoValue'] = $v['UserInfoValue'];
            $values_id[$k] = $v['UserInfoValue']['user_info_id'];
        }
        $user_infoarr = $this->UserInfo->findinfoassoc('');
    //	   pr($this->data);
           if (isset($user_info_value)) {
               foreach ($user_infoarr as $k => $v) {
                   if (isset($user_info_value[$k])) {
                       $user_infoarr[$k]['value'] = $user_info_value[$k]['UserInfoValue'];
                   }
               }
           }
        $this->pageTitle = $this->ld['my_information'].' - '.$this->configs['shop_title'];
        $js_languages = array('page_number_expand_max' => $this->ld['page_number'].$this->ld['not_exist'],
                               'address_label_not_empty' => $this->ld['address'].$this->ld['label'].$this->ld['can_not_empty'],
                               'invalid_email' => $this->ld['email'].$this->ld['format'].$this->ld['not_correct'],
                               'zip_code_not_empty' => $this->ld['zip'].$this->ld['can_not_empty'],
                               'invalid_tel_number' => $this->ld['telephone'].$this->ld['format'].$this->ld['not_correct'],
                               'tel_number_not_empty' => $this->ld['telephone'].$this->ld['can_not_empty'],
                               'invalid_mobile_number' => $this->ld['mobile'].$this->ld['format'].$this->ld['not_correct'],
                               'mobile_phone_not_empty' => $this->ld['mobile'].$this->ld['can_not_empty'],
                               'address_detail_not_empty' => $this->ld['address'].$this->ld['can_not_empty'],
                               'consignee_name_not_empty' => $this->ld['consignee'].$this->ld['can_not_empty'],
                            'fill_one_contact' => $this->ld['please_enter'].$this->ld['one_contact'],
                            'please_choose' => $this->ld['please_select'],
                            'choose_area' => $this->ld['please_select'].$this->ld['region'],
               );
        $this->set('js_languages', $js_languages);
        $this->set('user_infoarr', array_values($user_infoarr));
        $this->set('default_address', $default_address);
        $this->layout = 'default_user';
        $this->set('selected_menu', 'menu_user');
    }

    /**
     *函数 user_edit_profiles 用于编辑用户.
     */
    public function user_edit_profiles()
    {
        $mrClean = new Sanitize();
        if ($this->RequestHandler->isPost()) {
            $no_error = 1;
            $flash_url = $this->server_host.$this->user_webroot.'/profiles';

            if (!isset($_POST['is_ajax'])) {
                $this->page_init();
            /*	if(trim($_POST['data']['User']['email']) == ""){
                    $this->pageTitle = "".$this->ld['e-mail_empty']."";
                     $this->flash($this->ld['e-mail_empty'],$flash_url,10);	
                     $no_error = 0;	
                }else if(!ereg("^[-a-zA-Z0-9_.]+@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$_POST['data']['User']['email'])){
                    $this->pageTitle = "".$this->ld['email'].$this->ld['format'].$this->ld['not_correct']."";
                     $this->flash($this->ld['email'].$this->ld['format'].$this->ld['not_correct'],$flash_url,10);	
                     $no_error = 0;	
                }
                        
                if(in_array($this->ld['please_select'],$_POST['data']['Address']['Region'])){
                    $this->pageTitle = "".$this->ld['please_select'].$this->ld['region']."";
                     $this->flash($this->ld['please_select'].$this->ld['region'],$flash_url,10);	
                     $no_error = 0;	
                }else{
                    $region_info = $this->Region->findbyparent_id($_POST['data']['Address']['Region'][count($_POST['data']['Address']['Region'])-1]);
                    if(isset($region_info['Region'])){		
                    $this->pageTitle = "".$this->ld['please_select'].$this->ld['region']."";
                     $this->flash($this->ld['please_select'].$this->ld['region'],$flash_url,10);	
                     $no_error = 0;					
                    }
                }
                if(trim($_POST['data']['UserAddress']['address']) == ""){
                    $this->pageTitle = "".$this->ld['address'].$this->ld['can_not_empty']."";
                     $this->flash($this->ld['address'].$this->ld['can_not_empty'],$flash_url,10);	
                     $no_error = 0;						
                }	
                if(trim($_POST['Utel1']) == ""  && $no_error == 1){
                    $this->pageTitle = "".$this->ld['telephone'].$this->ld['can_not_empty']."";
                     $this->flash($this->ld['telephone'].$this->ld['can_not_empty'],$flash_url,10);	
                     $no_error = 0;						
                }
                if(trim($_POST['data']['UserAddress']['mobile']) == ""  && $no_error == 1){
                    $this->pageTitle = "".$this->ld['mobile'].$this->ld['can_not_empty']."";
                     $this->flash($this->ld['mobile'].$this->ld['can_not_empty'],$flash_url,10);	
                     $no_error = 0;					
                }
                */
            //	$telephone = $_POST['Utel1'];
            //	if($_POST['Utel2'] != ""){
            //		$telephone .= "-".$_POST['Utel2'];
            //	}
            //	$regions = implode(" ",$_POST['data']['Address']['Region']);
                $brithday = $_POST['data']['User']['birthday']['y'];
                $brithday += '-'.$_POST['data']['User']['birthday']['m'];
                $brithday += '-'.$_POST['data']['User']['birthday']['d'];
                $address = array('id' => $_POST['data']['UserAddress']['id']/*,'address'=>$_POST['data']['UserAddress']['address']*/,'mobile' => $_POST['data']['UserAddress']['mobile']/*,'telephone'=>$telephone,'regions'=>$regions*/);
                $user = array('id' => $_POST['data']['User']['id'] /*,'email'=>$_POST['data']['User']['email']*/ ,'sex' => $_POST['data']['User']['sex'] ,'birthday' => $brithday);
            } else {
                $address = (array) json_decode(StripSlashes($_POST['address']));
                $user = (array) json_decode(StripSlashes($_POST['user']));
            }
            if ($user['birthday'] == '') {
                unset($user['birthday']);
            }

            if ($no_error == 1) {
                $address['user_id'] = $user['id'];

                $this->User->save($user);
                $this->UserAddress->save($address);
            }
            if (!isset($_POST['is_ajax']) && $no_error == 1) {
                if (isset($_POST['info_value_id'])) {
                    foreach ($_POST['info_value_id'] as $k => $v) {
                        //pr($_POST['info_value'][$k]);
                            if ($_POST['ValueInfoType'][$k] == 'checkbox' && !empty($_POST['info_value'][$k]) && is_array($_POST['info_value'][$k])) {
                                $_POST['info_value'][$k] = implode(';', $_POST['info_value'][$k]);
                            }
                        $info_value = array(
                                             'id' => !empty($v) ? intval($v) : '',
                                             'user_id' => intval($user['id']),
                                             'user_info_id' => intval($_POST['ValueInfoId'][$k]),
                                             'value' => isset($_POST['info_value'][$k]) ? $_POST['info_value'][$k] : '',
                              );
                        if ($no_error == 1) {
                            $this->UserInfoValue->save($info_value);
                        }
                    }
                }

                $this->pageTitle = ''.$this->ld['tips_edit_success'].'';
                $this->flash($this->ld['tips_edit_success'], $flash_url, 10);
            } elseif ($no_error == 1) {
                $info = $_POST['info'];
                $info_arr = explode(',', $info);
                if (!empty($info_arr) && is_array($info_arr)) {
                    foreach ($info_arr as $k => $v) {
                        if (!empty($v)) {
                            $arr = explode(' ', $v);
                            $info_value = array(
                                             'id' => !empty($arr[1]) ? intval($arr[1]) : '',
                                             'user_id' => intval($user['id']),
                                             'user_info_id' => intval($arr[2]),
                                             'value' => $arr[0],
                              );
                            if ($no_error == 1) {
                                $this->UserInfoValue->save($info_value);
                            }
                        }
                    }
                }
            }
            $result['msg'] = $this->ld['tips_edit_success'];
            $result['type'] = 0;
        }

        if (isset($_POST['is_ajax'])) {
            $this->set('result', $result);
            $this->layout = 'ajax';
        }
    }
    public function user_show_email()
    {
        $user_info_old = $this->User->find(" User.id = '".$_SESSION['User']['User']['id']."'");
        $email_old = $user_info_old['User']['email'];
        //pr($user_info_old);
        if (!isset($_SESSION['User'])) {
            $this->redirect('/login/');
        }
        //pr($_SESSION['User']);
        $this->page_init();
        $this->getHeadBarInformation($_SESSION['User']['User']['id']);
        $this->layout = 'default_user';
        $this->set('selected_menu', 'menu_email');
        $this->set('email', $email_old);
    }
    public function user_show_password()
    {
        if (!isset($_SESSION['User'])) {
            $this->redirect('/login/');
        }
        $this->page_init();
        $this->getHeadBarInformation($_SESSION['User']['User']['id']);
        $this->layout = 'default_user';
        $this->set('selected_menu', 'menu_pass');
        //pr($_SESSION['User']);
        //$this->set('password',$_SESSION['User']['User']['password']);
    }
    public function user_edit_email()
    {
        $user_info_old = $this->User->find(" User.id = '".$_SESSION['User']['User']['id']."'");
        $password_old = $user_info_old['User']['password'];
        //pr($user_info_old);
        $this->page_init();
        $flash_url = $this->server_host.$this->user_webroot.'profiles/show_email';
        if (trim($_POST['email']) == '') {
            $this->pageTitle = ''.$this->ld['e-mail_empty'].'';
            $this->flash($this->ld['e-mail_empty'], $flash_url, 10);
        } elseif (md5($_POST['password']) != $password_old) {
            $this->pageTitle = '密码不正确';
            $this->flash('密码不正确', $flash_url, 10);
        } elseif (!ereg("^[-a-zA-Z0-9_.]+@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$", $_POST['email'])) {
            $this->pageTitle = ''.$this->ld['email'].$this->ld['format'].$this->ld['not_correct'].'';
            $this->flash($this->ld['email'].$this->ld['format'].$this->ld['not_correct'], $flash_url, 10);
        } else {
            $user = array('id' => $_POST['id'] ,'email' => $_POST['email'] ,'name' => $_POST['email']);
            $this->User->save($user);
            $this->flash('修改完成', $flash_url, 10);
        }
        if (isset($_POST['is_ajax'])) {
            $this->set('result', $result);
            $this->layout = 'ajax';
        }
    }
    public function user_edit_password()
    {
        $user_info_old = $this->User->find(" User.id = '".$_SESSION['User']['User']['id']."'");
        $password_old = $user_info_old['User']['password'];
        $this->page_init();
        $flash_url = $this->server_host.$this->user_webroot.'profiles/show_password';
     //echo "^[a-zA-Z0-9_.]$".$_POST['new_password'];
                if (trim($_POST['password']) == '') {
                    $this->pageTitle = ''.$this->ld['password'].$this->ld['can_not_empty'].'';
                    $this->flash($this->ld['password'].$this->ld['can_not_empty'], $flash_url, 10);
                } elseif (md5($_POST['password']) != $password_old) {
                    $this->pageTitle = '原密码不正确';
                    $this->flash('原密码不正确', $flash_url, 10);
                } elseif ($_POST['new_password'] == '' || $_POST['new_password'] != $_POST['new_password1']) {
                    $this->pageTitle = '新密码不能为空或者新密码两次输入不一样';
                    $this->flash('新密码不能为空或者新密码两次输入不一样', $flash_url, 10);
                } elseif (strlen($_POST['new_password'])  <  '4' || strlen($_POST['new_password']) > '28') {
                    $this->pageTitle = '新密码长度不对';
                    $this->flash('新密码长度不对', $flash_url, 10);
                } else {
                    $user = array('id' => $_POST['id'] ,'password' => md5($_POST['new_password']));
                    $this->User->save($user);
                    $this->flash('修改完成', $flash_url, 10);
                }

        if (isset($_POST['is_ajax'])) {
            $this->set('result', $result);
            $this->layout = 'ajax';
        }
    }
}
