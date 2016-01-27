<?php

class ProfileFiledsController extends AppController
{
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor','fck','Tinymce');
    public $name = 'ProfileFiled';
    public $components = array('Pagination','RequestHandler','Cookie');
    public $uses = array('Profile','ProfileI18n','OperatorLog','ProfilesFieldI18n','ProfileFiled');

    public function index($id = 0, $page = 1)
    {
        $this->redirect('/profiles/');
        $this->operator_privilege('profiles_seeall');
        $this->menu_path = array('root' => '/system/','sub' => '/profiles/');
        $profile = $this->Profile->findByid($id);
        $this->set('title_for_layout', $profile['Profile']['name'].'-'.$this->ld['file_allocation'].'-'.$this->configs['shop_name']);
        $this->set('id', $id);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['file_allocation'],'url' => '/profiles/');
        $this->navigations[] = array('name' => $profile['Profile']['name'],'url' => '/profile_fileds/index/'.$id);

        $conditions = '';
        $group = '1';
        $page = '';
        $rownum = '';
        $page = 1;
        $statu = 'all';
        $new_group = '';
        if (isset($_REQUEST['profilegroup'])) {
            $group = empty($_REQUEST['profilegroup']) ? '1' : $_REQUEST['profilegroup'];
            $this->set('group', $group);
        }
        $conditions['ProfileFiledI18n.locale'] = $this->locale;
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
        $profilefiled = $this->ProfileFiled->find('all', array('conditions' => array('ProfileFiled.locale' => $this->locale, 'profile_id' => $id), 'order' => 'orderby asc', 'page' => $page, 'limit' => $rownum));
        //pr($group_tree);
        $this->set('group', $group);
        $this->set('id', $id);
        //$this->set('group_tree',$group_tree);
        $this->set('profilefiled', $profilefiled);
    }

    //删除
    public function remove($uid = '', $id = '')
    {
        if (!empty($id)) {
            $this->ProfileFiled->deleteAll(array('ProfileFiled.id' => $id));
            $this->ProfilesFieldI18n->deleteAll(array('ProfilesFieldI18n.profiles_field_id' => $id));
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除档案:id '.$id.' '.$pn[$id], $this->admin['id']);
            }
        }
        $this->redirect('/profiles/view/'.$uid);
    }
    /**
     *列表状态修改.
     */
    public function toggle_on_status()
    {
        $this->operator_privilege('profile_filed_edit');//定时器编辑权限

        $id = $_REQUEST['id'];
        $group = $_REQUEST['group'];
        $profileid = $_REQUEST['profileid'];
        $group_status = $this->ProfileFiled->find('first', array('conditions' => array('ProfileFiledI18n.locale' => $this->locale, 'ProfileFiled.profile_id' => $profileid, 'ProfileFiled.id' => $id)));
        $group_arr = explode(',', $group_status['ProfileFiled']['group']);
        $status = '';
        if (in_array($group, $group_arr)) {
            foreach ($group_arr as $k => $v) {
                if ($group == $v) {
                    unset($group_arr[$k]);
                }
            }
            $status = false;
        } else {
            array_push($group_arr, $group);
            $status = true;
        }
        $str = implode(',', $group_arr);
        $profile_info = array('id' => $id,'group' => $str);

        $result = array();
        if (is_numeric($group) && $this->ProfileFiled->save($profile_info)) {
            //$this->Product->save(array("id"=>$id,"forsale"=>$val,"operator_id" => $this->admin['id']))
            $result['flag'] = 1;
            $result['content'] = $str;
            $result['status'] = $status;
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'定时器id'.'.'.$id.' '.'状态'.'.'.$val, $this->admin['id']);
            }
            //$this->product_log_save($id);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //编辑
    public function view($id = '', $uid = 0)
    {
        $this->Profile->set_locale($this->backend_locale);
        $profile = $this->Profile->findByid($id);
        if (empty($profile)) {
            $this->redirect('/profiles/');
        }
        $this->set('title_for_layout', $this->ld['add'].'/'.$this->ld['edit'].'-'.$profile['ProfileI18n']['name'].'-'.$this->ld['file_allocation'].$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['file_allocation'],'url' => '/profiles/');
        $this->navigations[] = array('name' => $profile['ProfileI18n']['name'],'url' => '/profiles/view/'.$id);
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '/profile_fileds/view/'.$id.'/'.$uid);
        $this->menu_path = array('root' => '/system/','sub' => '/profiles/');
        $this->set('id', $id);
        $this->set('uid', $uid);
        $profilefiled_data = $this->ProfileFiled->localeformat($uid);
        $this->set('profilefiled_data', $profilefiled_data);

        if ($this->RequestHandler->isPost()) {
            $this->ProfileFiled->save($this->data['ProfileFiled']);
            $uid = $this->ProfileFiled->id;
            $this->ProfilesFieldI18n->deleteAll(array('ProfilesFieldI18n.profiles_field_id' => $uid));
            foreach ($this->data['ProfilesFieldI18n'] as $v) {
                $data = array();
                $data['ProfilesFieldI18n']['locale'] = $v['locale'];
                $data['ProfilesFieldI18n']['profiles_field_id'] = $uid;
                $data['ProfilesFieldI18n']['name'] = $v['name'];
                $data['ProfilesFieldI18n']['description'] = $v['description'];
                $this->ProfilesFieldI18n->saveAll($data);
            }
               //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑详情:', $this->admin['id']);
            }
            $this->redirect('/profiles/view/'.$profile['Profile']['id']);
        }
    }
}
