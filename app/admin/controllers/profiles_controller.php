<?php

/*****************************************************************************
 * Seevia 档案配置
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id: edb222670ea886bec5aeb884e452fe4e33cdf63a $
*****************************************************************************/
class  ProfilesController extends AppController
{
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor','fck','Tinymce');
    public $name = 'Profile';
    public $components = array('Pagination','RequestHandler','Cookie','Phpexcel','Phpcsv');
    public $uses = array('Profile','ProfileI18n','OperatorLog','ProfilesFieldI18n','ProfileFiled');

    public function index($page = 1)
    {
        $this->operator_privilege('profiles_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/profiles/');
        $this->set('title_for_layout', $this->ld['file_allocation'].'-'.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['file_allocation'],'url' => '/profiles/');
        $conditions = '';
        $select_group = '';
        $profiles_keywords = '';
        $this->Profile->set_locale($this->backend_locale);
        $group_tree = $this->Profile->find('all', array('group' => 'Profile.group', 'fields' => 'Profile.group,ProfileI18n.name'));
        $this->set('group_tree', $group_tree);
        if (isset($this->params['url']['profile_group']) && $this->params['url']['profile_group'] != '') {
            $conditions['and']['Profile.group'] = $this->params['url']['profile_group'];
            $select_group = $this->params['url']['profile_group'];
        }
        //关键字
        if (isset($this->params['url']['profiles_keywords']) && $this->params['url']['profiles_keywords'] != '') {
            $profiles_keywords = $this->params['url']['profiles_keywords'];
            $this->set('profiles_keywords', $profiles_keywords);
            $conditions['and']['or']['ProfileI18n.name like'] = "%$profiles_keywords%";
            $conditions['and']['or']['Profile.code like'] = "%$profiles_keywords%";
        }
        $this->set('select_group', $select_group);
        //分页
        $total = $this->Profile->find('count', array('conditions' => $conditions));
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'Profile','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Profile');
        $this->Pagination->init($conditions, $parameters, $options);
        $profiles_list = $this->Profile->find('all', array('conditions' => $conditions, 'order' => 'Profile.orderby,Profile.id', 'page' => $page, 'limit' => $rownum));
        /*
        if(empty($profiles_list)){
            $this->redirect("/profiles/index");
        }
        */
        $this->set('profiles', $profiles_list);
    }

     //删除
     public function remove($id = null)
     {
         $this->operator_privilege('profiles_remove');
         if (!empty($id)) {
             $this->Profile->deleteAll(array('Profile.id' => $id));
             $this->ProfileI18n->deleteAll(array('ProfileI18n.profile_id' => $id));
             $ProfileFiled_ids = $this->ProfileFiled->find('list', array('conditions' => array('ProfileFiled.profile_id' => $id)));
             $this->ProfileFiled->deleteAll(array('ProfileFiled.id' => $ProfileFiled_ids));
             $this->ProfilesFieldI18n->deleteAll(array('ProfilesFieldI18n.profiles_field_id' => $ProfileFiled_ids));
            //操作员日记
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.'操作员'.$this->admin['name'].' '.'删除档案:id '.$id, $this->admin['id']);
            }
             $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
         }
     }

    //编辑
     public function view($id = '', $page = 1)
     {
         $this->operator_privilege('profiles_edit');
         $this->set('title_for_layout', '添加/编辑'.'-'.$this->ld['file_allocation'].'-'.$this->configs['shop_name']);
         $this->menu_path = array('root' => '/system/','sub' => '/profiles/');
         //导航设置
         $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
         $this->navigations[] = array('name' => $this->ld['file_allocation'],'url' => '/profiles/');
         $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');

         if ($this->RequestHandler->isPost()) {
             $this->Profile->save($this->data['Profile']);
             $id = $this->Profile->id;
             $this->ProfileI18n->deleteAll(array('ProfileI18n.profile_id' => $id));
             foreach ($this->data['ProfileI18n'] as $v) {
                 $data = array();
                 $data['ProfileI18n']['locale'] = $v['locale'];
                 $data['ProfileI18n']['profile_id'] = $id;
                 $data['ProfileI18n']['name'] = $v['name'];
                 $data['ProfileI18n']['description'] = $v['description'];
                 $this->ProfileI18n->saveAll($data);
             }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑档案:id '.$id, $this->admin['id']);
            }
             $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
         }
         $this->set('id', $id);
         $profile_data = $this->Profile->localeformat($id);
         $this->set('profile_data', $profile_data);

         $this->ProfileFiled->set_locale($this->backend_locale);
         $conditions['ProfileFiled.profile_id'] = $id;
        //分页
        $total = $this->ProfileFiled->find('count', array('conditions' => $conditions));
         if (isset($_GET['page']) && $_GET['page'] != '') {
             $page = $_GET['page'];
         }
         $rownum = !empty($this->configs['show_count']) ?     $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
         $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'ProfileFiled','action' => 'index','page' => $page,'limit' => $rownum);
         $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'ProfileFiled');
         $this->Pagination->init($conditions, $parameters, $options);
         $profilefiled = $this->ProfileFiled->find('all', array('conditions' => array('profile_id' => $id), 'order' => 'orderby asc', 'page' => $page, 'limit' => $rownum));
         $this->set('profilefiled', $profilefiled);
     }

     //获取档案分类下拉
     public function getdropdownlist()
     {
         $group = $_REQUEST['group'];
         $group_list = $this->Profile->find('all', array('conditions' => array('Profile.group' => $group, 'ProfileI18n.locale' => $this->backend_locale), 'fields' => 'Profile.code,ProfileI18n.name'));
        //pr($group_list);
         if (empty($group_list)) {
             $result['flag'] = 2;
             $result['content'] = $this->ld['sku_exists_not_repeated'];//没有查询到对应的profile
         }
         if (isset($group_list)) {
             $result['flag'] = 1;
             $result['content'] = $group_list;
         }
         Configure::write('debug', 0);
         $this->layout = 'ajax';
         die(json_encode($result));
     }

     //保存档案分类下拉
     public function saveprofilegroup()
     {
         $id = $_REQUEST['id'];
         $group = $_REQUEST['group'];
         if (isset($id) && $group != 0) {
             $saveid = $this->Profile->save(array('id' => $id, 'group' => $group));
         } else {
             $result['flag'] = 2;
             $result['content'] = $this->ld['sku_exists_not_repeated'];
         }
         if (!empty($saveid)) {
             $result['flag'] = 2;
             $result['content'] = $this->ld['sku_exists_not_repeated'];
         }
         if (isset($saveid)) {
             $result['flag'] = 1;
             $result['content'] = $saveid;
            //$result["id"] = $id;
         }
         Configure::write('debug', 0);
         $this->layout = 'ajax';
         die(json_encode($result));
     }
     ////////////////////////////删除
     public function removeall()
     {
         $checkboxes = $_REQUEST['postData'];
         $this->Profile->deleteAll(array('Profile.id' => $checkboxes));
         $this->ProfileI18n->deleteAll(array('ProfileI18n.profile_id' => $checkboxes));
         $ProfileFiled_ids = $this->ProfileFiled->find('list', array('conditions' => array('ProfileFiled.profile_id' => $checkboxes)));
         $this->ProfileFiled->deleteAll(array('ProfileFiled.id' => $ProfileFiled_ids));
         $this->ProfilesFieldI18n->deleteAll(array('ProfilesFieldI18n.profiles_field_id' => $ProfileFiled_ids));
        //操作员日记
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.'操作员'.$this->admin['name'].' '.'删除档案:id'.(implode(';', $checkboxes)), $this->admin['id']);
        }
         Configure::write('debug', 0);
         $this->layout = 'ajax';
         die(json_encode($result));
     }

    public function profile_export($export_action = 'all_export')
    {
        $profile_export = 'profile_export';
        $this->Profile->set_locale($this->backend_locale);
        $this->ProfileFiled->set_locale($this->backend_locale);
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $profile_export, 'Profile.status' => 1)));
        $excel = array();
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            foreach ($profilefiled_info as $k => $v) {
                $tmp[] = $v['ProfilesFieldI18n']['description'];
                $fields_array[] = $v['ProfileFiled']['code'];
            }

            $excel[] = $tmp;
            $conditions = '';
            if (isset($_REQUEST['profiles_keywords']) && $_REQUEST['profiles_keywords'] != '') {
                $profiles_keywords = $_REQUEST['profiles_keywords'];
                $this->set('profiles_keywords', $profiles_keywords);
                $conditions['and']['or']['ProfileI18n.name like'] = "%$profiles_keywords%";
                $conditions['and']['or']['Profile.code like'] = "%$profiles_keywords%";
            }
            if (isset($_REQUEST['profile_group']) && $_REQUEST['profile_group'] != '') {
                $conditions['and']['Profile.group'] = $_REQUEST['profile_group'];
            }
            if (isset($_REQUEST['checkboxes'])) {
                $conditions['and']['Profile.id'] = $_REQUEST['checkboxes'];
            }
            if ($export_action != 'category_export') {
                $export_data1 = $this->Profile->find('all', array('order' => 'Profile.orderby,Profile.id', 'conditions' => $conditions));
                $profile_ids = array();
                $profile_datas = array();
                foreach ($export_data1 as $v) {
                    $profile_ids[] = $v['Profile']['id'];
                    $profile_datas[$v['Profile']['id']] = $v;
                }
                $export_data2 = $this->ProfileFiled->find('all', array('order' => 'ProfileFiled.profile_id,ProfileFiled.orderby,ProfileFiled.id', 'conditions' => array('ProfileFiled.profile_id' => $profile_ids)));
                $export_data = array();
                foreach ($export_data2 as $k => $v) {
                    if (isset($profile_datas[$v['ProfileFiled']['profile_id']])) {
                        $export_data[] = array_merge($v, $profile_datas[$v['ProfileFiled']['profile_id']]);
                    } else {
                        $export_data[] = $v;
                    }
                }
                foreach ($export_data as $k => $v) {
                    $profile_tmp = array();
                    foreach ($fields_array as $kk => $vv) {
                        $fields_kk = explode('.', $vv);
                        $profile_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                    }
                    $excel[] = $profile_tmp;
                }
            } else {
                $export_data1 = $this->Profile->find('all', array('order' => 'Profile.orderby,Profile.id', 'conditions' => $conditions));
                $profile_ids = array();
                $profile_datas = array();
                $export_group_data = array();
                $export_group_data_ids = array();
                foreach ($export_data1 as $k => $v) {
                    $profile_ids[] = $v['Profile']['id'];
                    $profile_datas[$v['Profile']['id']] = $v;
                    $export_group_data[$v['Profile']['group']][] = $v;
                    $export_group_data_ids[$v['Profile']['group']][] = $v['Profile']['id'];
                }
                $export_data2 = $this->ProfileFiled->find('all', array('order' => 'ProfileFiled.profile_id,ProfileFiled.orderby,ProfileFiled.id', 'conditions' => array('ProfileFiled.profile_id' => $profile_ids)));
                $export_data = array();
                foreach ($export_data2 as $k => $v) {
                    if (isset($profile_datas[$v['ProfileFiled']['profile_id']])) {
                        $export_data[] = array_merge($v, $profile_datas[$v['ProfileFiled']['profile_id']]);
                    } else {
                        $export_data[] = $v;
                    }
                }
                $export_group_data = array();
                foreach ($export_data as $k => $v) {
                    $export_group_data[$v['Profile']['group']][] = $v;
                }
                foreach ($export_group_data as $k => $v) {
                    $excel[] = array($k);
                    foreach ($v as $kk => $vv) {
                        $profile_tmp = array();
                        foreach ($fields_array as $kkk => $vvv) {
                            $fields_kk = explode('.', $vvv);
                            $profile_tmp[] = isset($vv[$fields_kk[0]][$fields_kk[1]]) ? $vv[$fields_kk[0]][$fields_kk[1]] : '';
                        }
                        $excel[] = $profile_tmp;
                    }
                }
            }
            $this->Phpexcel->output('profile_export'.date('YmdHis').'.xls', $excel);
            die();
        } else {
            $this->redirect('/profiles/index');
        }
    }

    public function uploadprofiles()
    {
        $this->operator_privilege('profiles_add');
        $this->menu_path = array('root' => '/system/','sub' => '/profiles/');
        $this->set('title_for_layout', $this->ld['file_allocation'].'-'.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['file_allocation'],'url' => '/profiles/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $this->Profile->set_locale($this->backend_locale);
        $flag_code = 'profile_import';
        $profilefiled_codes = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        if (isset($profilefiled_codes) && !empty($profilefiled_codes)) {
            $this->set('profilefiled_codes', $profilefiled_codes);
        }
    }

    public function uploadprofilepreview()
    {
        $this->operator_privilege('profiles_add');
        $this->menu_path = array('root' => '/system/','sub' => '/profiles/');
        $this->set('title_for_layout', $this->ld['file_allocation'].'-'.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['file_allocation'],'url' => '/profiles/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '/profiles/uploadprofiles');
        $this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
        $flag_code = 'profile_import';
        $this->Profile->set_locale($this->backend_locale);
        $this->ProfileFiled->set_locale($this->backend_locale);
        set_time_limit(300);
        if (!empty($_FILES['file'])) {
            if ($_FILES['file']['error'] > 0) {
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/articles/uploadarticles';</script>";
                die();
            } else {
                $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
                $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
                if (empty($profilefiled_info)) {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/articles/uploadarticles';</script>";
                    die();
                }
                $key_arr = array();
                foreach ($profilefiled_info as $k => $v) {
                    $key_arr[] = $v['ProfileFiled']['code'];
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
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('上传文件格式不标准');window.location.href='/admin/articles/uploadarticles';</script>";
                        }
                        ++$i;
                    }
                    $temp = array();
                    foreach ($row as $k => $v) {
                        $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                    }
                    if (!isset($temp) || empty($temp)) {
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/articles/uploadarticles';</script>";
                        die();
                    }
                    $data[] = $temp;
                }
                fclose($handle);
                $this->set('profilefiled_info', $profilefiled_info);
                $this->set('uploads_list', $data);
            }
        } else {
            $this->redirect('/profiles/index');
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

    public function batch_add_profiles()
    {
        $this->operator_privilege('profiles_add');
        if ($this->RequestHandler->isPost()) {
            $checkbox_arr = $_REQUEST['checkboxes'];
            $this->Profile->hasOne = array();
            $this->Profile->hasMany = array();
            $count_num = 0;
            foreach ($this->data as $key => $data) {
                if (!in_array($key, $checkbox_arr)) {
                    continue;
                }
                $profiledata = $this->Profile->find('first', array('conditions' => array('Profile.code' => $data['Profile']['code'])));
                $profile_data = empty($profiledata['Profile']) ? array() : $profiledata['Profile'];
                $profile_data['code'] = $data['Profile']['code'];
                $profile_data['group'] = $data['Profile']['group'];
                $profile_data['orderby'] = !empty($data['Profile']['orderby']) ? $data['Profile']['orderby'] : 50;
                $profile_data['status'] = !empty($data['Profile']['status']) ? $data['Profile']['status'] : 1;
                if (empty($profile_data['id'])) {
                    $this->Profile->saveAll(array('Profile' => $profile_data));
                    $Profile['id'] = $this->Profile->id;
                } else {
                    $this->Profile->save(array('Profile' => $profile_data));
                    $Profile['id'] = $profile_data['id'];
                }
                ++$count_num;
                if (!empty($Profile['id'])) {
                    if (!empty($this->front_locales) && is_array($this->front_locales)) {
                        foreach ($this->front_locales as $k => $v) {
                            $profileI18ndata = $this->ProfileI18n->find('first', array('conditions' => array('locale' => $v['Language']['locale'], 'profile_id' => $Profile['id'])));
                            $profile_I18n_data = empty($profileI18ndata['ProfileI18n']) ? array() : $profileI18ndata['ProfileI18n'];
                            $profile_I18n_data['locale'] = $v['Language']['locale'];
                            $profile_I18n_data['profile_id'] = $Profile['id'];
                            $profile_I18n_data['name'] = $data['ProfileI18n']['name'];
                            $profile_I18n_data['description'] = $data['ProfileI18n']['description'];
                            $this->ProfileI18n->saveAll(array('ProfileI18n' => $profile_I18n_data));
                        }
                    }
                    $profilefielddata = $this->ProfileFiled->find('first', array('conditions' => array('ProfileFiled.profile_id' => $Profile['id'], 'ProfileFiled.code' => $data['ProfileFiled']['code'])));
                    $profilefield_data = empty($profilefielddata['ProfileFiled']) ? array() : $profilefielddata['ProfileFiled'];
                    $profilefield_data['profile_id'] = $Profile['id'];
                    $profilefield_data['code'] = $data['ProfileFiled']['code'];
                    $profilefield_data['format'] = $data['ProfileFiled']['format'];
                    $profilefield_data['orderby'] = !empty($data['ProfileFiled']['orderby']) ? $data['ProfileFiled']['orderby'] : 50;
                    $profilefield_data['status'] = !empty($data['ProfileFiled']['status']) ? $data['ProfileFiled']['status'] : 1;
                    if (empty($profilefield_data['id'])) {
                        $this->ProfileFiled->saveAll(array('ProfileFiled' => $profilefield_data));
                        $ProfileFiled['id'] = $this->ProfileFiled->id;
                    } else {
                        $this->ProfileFiled->save(array('ProfileFiled' => $profilefield_data));
                        $ProfileFiled['id'] = $profilefield_data['id'];
                    }
                    if (!empty($this->front_locales) && is_array($this->front_locales)) {
                        foreach ($this->front_locales as $k => $v) {
                            $profilefieldI18ndata = $this->ProfilesFieldI18n->find('first', array('conditions' => array('locale' => $v['Language']['locale'], '	profiles_field_id' => $ProfileFiled['id'])));
                            $profile_field_I18n_data = empty($profilefieldI18ndata['ProfilesFieldI18n']) ? array() : $profilefieldI18ndata['ProfilesFieldI18n'];
                            $profile_field_I18n_data['locale'] = $v['Language']['locale'];
                            $profile_field_I18n_data['profiles_field_id'] = $ProfileFiled['id'];
                            $profile_field_I18n_data['name'] = $data['ProfilesFieldI18n']['name'];
                            $profile_field_I18n_data['description'] = $data['ProfilesFieldI18n']['description'];
                            $this->ProfilesFieldI18n->saveAll(array('ProfilesFieldI18n' => $profile_field_I18n_data));
                        }
                    }
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['bulk_upload'].$this->ld['file_allocation'], $this->admin['id']);
            }
        }
        $this->redirect('/profiles/index');
    }

    public function download_csv_example()
    {
        $flag_code = 'profile_import';
        $this->Profile->set_locale($this->backend_locale);
        $this->ProfileFiled->set_locale($this->backend_locale);
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));

        $excel = array();
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            foreach ($profilefiled_info as $k => $v) {
                $tmp[] = $v['ProfilesFieldI18n']['description'];
                $fields_array[] = $v['ProfileFiled']['code'];
            }
            $excel[] = $tmp;

            $export_data1 = $this->Profile->find('all', array('order' => 'Profile.orderby,Profile.id', 'limit' => 10));
            $profile_ids = array();
            $profile_datas = array();
            foreach ($export_data1 as $v) {
                $profile_ids[] = $v['Profile']['id'];
                $profile_datas[$v['Profile']['id']] = $v;
            }
            $export_data2 = $this->ProfileFiled->find('all', array('order' => 'ProfileFiled.profile_id,ProfileFiled.orderby,ProfileFiled.id', 'conditions' => array('ProfileFiled.profile_id' => $profile_ids), 'limit' => 10));
            $export_data = array();
            foreach ($export_data2 as $k => $v) {
                if (isset($profile_datas[$v['ProfileFiled']['profile_id']])) {
                    $export_data[] = array_merge($v, $profile_datas[$v['ProfileFiled']['profile_id']]);
                } else {
                    $export_data[] = $v;
                }
            }
            foreach ($export_data as $k => $v) {
                $profile_tmp = array();
                foreach ($fields_array as $kk => $vv) {
                    $fields_kk = explode('.', $vv);
                    $profile_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                }
                $excel[] = $profile_tmp;
            }
            $this->Phpcsv->output('profile_import'.date('Ymd').'.csv', $excel);
            die();
        } else {
            $this->redirect('/profiles/index');
        }
    }
}
