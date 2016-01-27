<?php

/*****************************************************************************
 * Seevia 用户管理
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
class NewsletterListsController extends AppController
{
    public $name = 'NewsletterLists';
    public $components = array('Pagination','RequestHandler','Email','Phpexcel','Phpcsv');
    public $helpers = array('Pagination','Html','Form');
    public $uses = array('NewsletterList','Resource','OperatorLog','UserGroup','Profile');
    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('newsletter_lists_view');
        /*end*/
        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists/');

        $condition = '';
        if (isset($this->params['url']['email']) && $this->params['url']['email'] != '') {
            $condition['NewsletterList.email like'] = '%'.$this->params['url']['email'].'%';
            $this->set('email', $this->params['url']['email']);
        }
        if (isset($this->params['url']['group_id']) && $this->params['url']['group_id'] != '') {
            $condition['NewsletterList.group_id'] = $this->params['url']['group_id'];
            $this->set('group_id', $this->params['url']['group_id']);
        }
        if (isset($this->params['url']['mystatus']) && $this->params['url']['mystatus'] != '') {
            $condition['NewsletterList.status'] = $this->params['url']['mystatus'];
            $this->set('mystatus', $this->params['url']['mystatus']);
        }
        if (isset($this->params['url']['date']) && $this->params['url']['date'] != '') {
            $condition['NewsletterList.created  >='] = $this->params['url']['date'];
            $this->set('date', $this->params['url']['date']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
            $condition['NewsletterList.created  <='] = $this->params['url']['date2'].' 23:59:59';
            $this->set('date2', $this->params['url']['date2']);
        }
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $total = count($this->NewsletterList->find('all', array('conditions' => $condition, 'fields' => 'DISTINCT NewsletterList.id')));
        $sortClass = 'NewsletterList';
        $rownum = isset($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'newsletter_lists','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'NewsletterList');
        $this->Pagination->init($condition, $parameters, $options);
        $newsletterlist_data = $this->NewsletterList->find('all', array('conditions' => $condition, 'limit' => $rownum, 'page' => $page));
        $group_list = $this->UserGroup->find('list', array('conditions' => array('UserGroup.status' => 1), 'fields' => 'UserGroup.id,UserGroup.name'));
        if (empty($newsletterlist_data) && $page > 1) {
            $this->redirect('/newsletter_lists/');
        }
        foreach ($newsletterlist_data as $nk => $nv) {
            foreach ($group_list as $gk => $gv) {
                if ($nv['NewsletterList']['group_id'] == $gk) {
                    $newsletterlist_data[$nk]['NewsletterList']['group'] = $gv;
                }
            }
        }
        //资源库信息
        $this->Resource->set_locale($this->locale);
        $Resource_info = $this->Resource->getformatcode('newsletter_lis', $this->locale, false);
        $this->set('Resource_info', $Resource_info);
        $this->set('group_list', $group_list);//绑定分组搜索下拉
        $this->set('newsletterlist_data', $newsletterlist_data);
        $this->set('title_for_layout', $this->ld['magazine_user'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    /**
     *定时器编辑/新增.
     *
     *@param int $id 输入定时器ID，新增时不传
     */
    public function view($id = 0)
    {
        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        $group_list = $this->UserGroup->find('list', array('conditions' => array('UserGroup.status' => 1), 'fields' => 'UserGroup.id,UserGroup.name'));
        $this->set('group_list', $group_list);
        if (empty($id)) {
            $this->operator_privilege('newsletter_add');
            $this->set('title_for_layout', $this->ld['add'].'- '.$this->ld['magazine_user'].' - '.$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
            $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists/');
            if ($this->RequestHandler->isPost()) {
                $this->data['NewsletterList']['email'] = !empty($this->data['NewsletterList']['email']) ? $this->data['NewsletterList']['email'] : '';//email
            $this->data['NewsletterList']['mobile'] = !empty($this->data['NewsletterList']['mobile']) ? $this->data['NewsletterList']['mobile'] : '';//手机
            //checkbox数据处理
            $this->data['NewsletterList']['status'] = !empty($this->data['NewsletterList']['status']) ? $this->data['NewsletterList']['status'] : '0';//有效状态*/
            $this->NewsletterList->save(array('NewsletterList' => $this->data['NewsletterList']));
            //操作员日志

            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 新增订阅'.$this->ld['vip'], $this->admin['id']);
            }
                $this->redirect('/newsletter_lists/');
            }
        } else {
            $this->operator_privilege('newsletter_lists_edit');
            $this->set('title_for_layout', $this->ld['edit'].'-'.$this->ld['magazine_user'].' - '.$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
            $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists/');
            $cronjob_info = $this->NewsletterList->find('first', array('conditions' => array('NewsletterList.id' => $id)));
            $this->set('cronjob_info', $cronjob_info);
            if ($this->RequestHandler->isPost()) {
                //pr($this->data["NewsletterList"]);die;
                $this->NewsletterList->save(array('NewsletterList' => $this->data['NewsletterList']));
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 编辑订阅'.$this->ld['vip'].' id:'.$id, $this->admin['id']);
                }
                $this->redirect('/newsletter_lists/');
            }
        }
    }
    public function export()
    {
        $condition = '';
        if (isset($this->params['url']['mystatus']) && $this->params['url']['mystatus'] != '') {
            $condition['NewsletterList.status'] = $this->params['url']['mystatus'];
            $this->set('mystatus', $this->params['url']['mystatus']);
        }
        if (isset($this->params['url']['email']) && $this->params['url']['email'] != '') {
            $condition['NewsletterList.email like'] = '%'.$this->params['url']['email'].'%';
            $this->set('email', $this->params['url']['email']);
        }
        if (isset($this->params['url']['date']) && $this->params['url']['date'] != '') {
            $condition['NewsletterList.created  >='] = $this->params['url']['date'];
            $this->set('date', $this->params['url']['date']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
            $condition['NewsletterList.created  <='] = $this->params['url']['date2'].' 23:59:59';
            $this->set('date2', $this->params['url']['date2']);
        }
        //pr($condition);die;
        $newsletterlist_data = $this->NewsletterList->find('all', array('conditions' => $condition));
        $out = '邮箱,手机,状态[1:确认 2:退订]'."\n";
        foreach ($newsletterlist_data as $key => $val) {
            $out .= $val['NewsletterList']['email'].',';
            $out .= $val['NewsletterList']['mobile'].',';
            $out .= $val['NewsletterList']['status']."\n";
        }
        header('Content-type: application/vnd.ms-excel;charset=gbk');
        header('Content-Disposition: attachment; filename=email_list.csv');
        echo iconv('utf-8', 'gbk//IGNORE', $out."\n");
        Configure::write('debug', 0);
        exit;
    }
    public function change_status($status)
    {
        foreach ($_REQUEST['checkboxes'] as $k => $v) {
            if ($status == 'unsubscribe') {
                $order_info = array(
                    'status' => '2',
                    'id' => $v,
                );

                $this->NewsletterList->save($order_info);
            }
            if ($status == 'remove') {
                $this->NewsletterList->deleteAll(array('NewsletterList.id' => $v));
            }
            if ($status == 'confirm') {
                $order_info = array(
                    'status' => '1',
                    'id' => $v,
                );

                $this->NewsletterList->save($order_info);
            }
        }
        //$this->flash("邮件订阅操作成功，点击这里返回列表页。",'/newsletter_lists/',10);
        $this->redirect('/newsletter_lists/');
    }
    public function unsubscribe($id)
    {
        $order_info = array(
            'status' => '2',
            'id' => $id,
            );
        $this->NewsletterList->save($order_info);
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 取消订阅:id '.$id, $this->admin['id']);
        }
        $this->redirect('/newsletter_lists/');
    }
    public function confirm($id)
    {
        $order_info = array(
            'status' => '1',
            'id' => $id,
            );
        $this->NewsletterList->save($order_info);
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 确认订阅:id '.$id, $this->admin['id']);
        }
        $this->redirect('/newsletter_lists/');
    }
    public function remove($id)
    {
        $this->NewsletterList->deleteAll(array('NewsletterList.id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 删除订阅用户:id '.$id, $this->admin['id']);
        }
        $this->redirect('/newsletter_lists/');
    }

    /*
        订阅用户实例下载
    */
    public function download_csv_example()
    {
        $this->loadModel('ProfileFiled');
        $this->loadModel('ProfilesFieldI18n');
        $this->Profile->set_locale($this->backend_locale);
        $this->Profile->hasOne = array();
        $flag_code = 'newsletter_export';
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $tmp = array();
        $fields_array = array();
        $newdatas = array();
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            foreach ($profilefiled_info as $k => $v) {
                $tmp[] = $v['ProfilesFieldI18n']['description'];
                $fields_array[] = $v['ProfileFiled']['code'];
            }
        }
        $newdatas[] = $tmp;

        $filename = '杂志订阅用户导出'.date('Ymd').'.csv';
        $user_info = $this->NewsletterList->find('all', array('order' => 'NewsletterList.id desc', 'limit' => 10));
        foreach ($user_info as $k => $v) {
            $user_tmp = array();
            foreach ($fields_array as $kk => $vv) {
                $fields_kk = explode('.', $vv);
                $user_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
            }
            $newdatas[] = $user_tmp;
        }
        $this->Phpcsv->output($filename, $newdatas);
        exit();
    }

    /*
        订阅用户批量导入
    */
    public function uploadusers()
    {
        /*判断权限*/
        $this->operator_privilege('users_add');
        /*end*/

        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $flag_code = 'user_export';

        $this->Profile->set_locale($this->locale);
        $profilefiled_codes = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
        if (isset($profilefiled_codes) && !empty($profilefiled_codes)) {
            $this->set('profilefiled_codes', $profilefiled_codes);
        }
    }

    public function uploaduserspreview()
    {
        /*判断权限*/
        $this->operator_privilege('users_add');
        /*end*/
        $this->loadModel('ProfileFiled');
        $this->loadModel('ProfilesFieldI18n');

        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '/newsletter_lists/uploadusers');
        $this->navigations[] = array('name' => $this->ld['batch_upload_user'],'url' => '');
        $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
        $flag_code = 'newsletter_export';
        $this->set('extension_code', array('' => $this->ld['real_product'], 'virtual_card' => $this->ld['virtual_cards']));
        $this->Profile->set_locale($this->locale);
        set_time_limit(300);
        if (!empty($_FILES['file'])) {
            if ($_FILES['file']['error'] > 0) {
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/newsletter_lists/uploadusers';</script>";
                die();
            } else {
                $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
                $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
                if (empty($profilefiled_info)) {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/newsletter_lists/uploadusers';</script>";
                    die();
                }
                $key_arr = array();
                foreach ($profilefiled_info as $k => $v) {
                    $fields_k = explode('.', $v['ProfileFiled']['code']);
                    $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                }
                $csv_export_code = 'gb2312';
                $i = 0;
                while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                    if ($i == 0) {
                        $check_row = $row[0];
                        $row_count = count($row);
                        $check_row = iconv('GB2312', 'UTF-8', $check_row);
                        $num_count = count($profilefiled_info);
                        if ($row_count > $num_count || $check_row != $profilefiled_info[0]['ProfilesFieldI18n']['description']) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('上传文件格式不标准');window.location.href='/admin/newsletter_lists/uploadusers';</script>";
                            die();
                        }
                        ++$i;
                    }
                    $temp = array();
                    foreach ($row as $k => $v) {
                        $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                    }
                    if (!isset($temp) || empty($temp)) {
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/newsletter_lists/uploadusers';</script>";
                        die();
                    }
                    $data[] = $temp;
                }
                fclose($handle);
                $this->set('profilefiled_info', $profilefiled_info);
                $this->set('uploads_list', $data);
            }
        } else {
            $this->redirect('/newsletter_lists/uploadusers');
        }
    }

    public function batch_add_user()
    {
        /*判断权限*/
        $this->operator_privilege('users_add');
        /*end*/
        $this->loadModel('NewsletterList');
        if (!empty($this->data)) {
            $checkbox_arr = $_REQUEST['checkbox'];
            foreach ($this->data as $key => $data) {
                if (!in_array($key, $checkbox_arr)) {
                    continue;
                }
                if ($data['mobile'] == '' && $data['email'] == '') {
                    continue;
                }
                $this->NewsletterList->saveAll($data);
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'批量上传会员', $this->admin['id']);
            }
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['import_success']."');window.location.href='/admin/newsletter_lists/index';</script>";
            die();
        }
    }

    public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"')
    {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = '';
        $eof = false;
        while ($eof != true) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) {
                $eof = true;
            }
        }
        $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for ($_csv_i = 0; $_csv_i < count($_csv_data); ++$_csv_i) {
            $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
            $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }

        return empty($_line) ? false : $_csv_data;
    }
}
