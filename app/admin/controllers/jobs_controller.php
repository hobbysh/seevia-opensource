<?php

/**
 *这是一个名为 JobsController 的控制器
 *后台职位管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class JobsController extends AppController
{
    public $name = 'Jobs';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('Job','InformationResource','JobI18n','OperatorLog');
    /**
     *显示职位列表.
     */
    public function index($page = 1)
    {
        //$this->operator_privilege('Jobs_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/cms/','sub' => '/resumes/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['recruitment_management'],'url' => '/resumes/');
        $this->navigations[] = array('name' => $this->ld['position_management'],'url' => '');
        $this->Job->set_locale($this->locale);
        $condition = '';
        $job_keywords = '';     //关键字
        //关键字
        if (isset($this->params['url']['keywords']) && $this->params['url']['keywords'] != '') {
            $keywords = $this->params['url']['keywords'];
            $keywords = str_replace('_', '[_]', $keywords);
            $keywords = str_replace('%', '[%]', $keywords);
            $condition['and']['or']['JobI18n.name like'] = '%'.$keywords.'%';
            $condition['and']['or']['JobI18n.detail like'] = '%'.$keywords.'%';
            $this->set('keywords', $this->params['url']['keywords']);//关键字选中
        }
        if (isset($this->params['url']['experience_id']) && $this->params['url']['experience_id'] != '-1') {
            $experience_id = $this->params['url']['experience_id'];
            $condition['and']['Job.experience_id'] = $experience_id;
            $this->set('experience_id', $experience_id);
        }
        if (isset($this->params['url']['education_id']) && $this->params['url']['education_id'] != '-1') {
            $education_id = $this->params['url']['education_id'];
            $condition['and']['Job.education_id'] = $education_id;
            $this->set('education_id', $education_id);
        }
        if (isset($this->params['url']['type_id']) && $this->params['url']['type_id'] != '-1') {
            $type_id = $this->params['url']['type_id'];
            $condition['and']['Job.type_id'] = $type_id;
            $this->set('type_id', $type_id);
        }
        if (isset($this->params['url']['department_id']) && $this->params['url']['department_id'] != '-1') {
            $department_id = $this->params['url']['department_id'];
            $condition['and']['Job.department_id'] = $department_id;
            $this->set('department_id', $department_id);
        }
        $total = $this->Job->find('count', array('conditions' => $condition));//统计全部职位总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'Job';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }

        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);

        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'Jobs','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Job');
        $this->Pagination->init($condition, $parameters, $options);
        $job_list = $this->Job->find('all', array('conditions' => $condition, 'order' => 'Job.created desc', 'limit' => $rownum, 'page' => $page));
        $this->set('job_list', $job_list);//职位列表

        $this->set('title_for_layout', $this->ld['job_list'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $informationresource_info = $this->InformationResource->information_formated(array('education_type', 'experience_type', 'department_type', 'job_type', 'education_type'), $this->locale);
        $this->set('informationresource_info', $informationresource_info);
    }

    /**
     *职位 新增/编辑.
     *
     *@param int $id 输入职位ID
     */
    public function view($id = 0)
    {
        if (empty($id)) {
            //	$this->operator_privilege('Jobs_add');
        } else {
            //	$this->operator_privilege('Jobs_edit');
        }
        $this->menu_path = array('root' => '/cms/','sub' => '/resumes/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['recruitment_management'],'url' => '/resumes/');
        $this->navigations[] = array('name' => $this->ld['position_management'],'url' => '/jobs/');
        $this->set('title_for_layout', $this->ld['add_edit_position'].'- '.$this->ld['position_management'].' - '.$this->configs['shop_name']);
        if ($this->RequestHandler->isPost()) {
            if (isset($this->data['Job']['id']) && $this->data['Job']['id'] != '') {
                $this->Job->save(array('Job' => $this->data['Job'])); //关联保存
            } else {
                $this->Job->saveAll(array('Job' => $this->data['Job'])); //关联保存
                $id = $this->Job->getLastInsertId();
            }
            $this->JobI18n->deleteall(array('Job_id' => $this->data['Job']['id'])); //删除原有多语言
            foreach ($this->data['JobI18n'] as $v) {
                $jobi18n_info = array(
                    'locale' => $v['locale'],
                    'job_id' => $id,
                    'name' => isset($v['name']) ? $v['name'] : '',
                    'detail' => $v['detail'],
                    'address' => $v['address'],
                  );
                $this->JobI18n->saveAll(array('JobI18n' => $jobi18n_info));//更新多语言
            }
            foreach ($this->data['JobI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_edit_position'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $back_url_arr = explode('/', $back_url);
            if (isset($back_url_arr[1]) && $back_url_arr[1] == 'jobs') {
                $this->redirect($back_url);
            } else {
                $this->redirect('/jobs/');
            }
        }
        $this->data = $this->Job->localeformat($id);
        //导般 名称设置
        if (!empty($this->data['JobI18n'][$this->locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].(isset($this->data['JobI18n'][$this->locale]['name']) ? $this->data['JobI18n'][$this->locale]['name'] : ''),'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_position'],'url' => '');
        }

        //学历
        $informationresource_info = $this->InformationResource->information_formated(array('education_type', 'experience_type', 'department_type', 'job_type', 'education_type'), $this->locale);
        if (!empty($informationresource_info)) {
            $this->set('job_types', isset($informationresource_info['job_type']) ? $informationresource_info['job_type'] : '');
            $this->set('experience_types', isset($informationresource_info['experience_type']) ? $informationresource_info['experience_type'] : '');
            $this->set('department_types', isset($informationresource_info['department_type']) ? $informationresource_info['department_type'] : '');
            $this->set('education_types', isset($informationresource_info['education_type']) ? $informationresource_info['education_type'] : '');
        }
    }

    public function act_view($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $code = $_POST['code'];
        $rname = '';
        $name_code = $this->Job->find('all', array('fields' => 'Job.code'));
        if (isset($name_code) && !empty($name_code)) {
            foreach ($name_code as $vv) {
                $rname[] = $vv['Job']['code'];
            }
        } else {
            $result['code'] = '1';
        }
        if ($id == 0) {
            if (isset($code) && $code != '') {
                if (in_array($code, $rname)) {
                    $result['code'] = '0';
                } else {
                    $result['code'] = '1';
                }
            }
        } else {
            $Job_count = $this->Job->find('first', array('conditions' => array('Job.id' => $id)));
            if ($Job_count['Job']['code'] != $code && in_array($code, $rname)) {
                $result['code'] = '0';
                //   $result['msg'] = "用户名重复";
            } else {
                $result['code'] = '1';
            }
        }
        die(json_encode($result));
    }
    /**
     *职位 批量操作.
     */
    public function batch_operations()
    {
        $job_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $condition['Job.id'] = $job_ids;
        $this->Job->deleteAll($condition);
        $this->JobI18n->deleteAll(array('JobI18n.job_id' => $job_ids));
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        @Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        die(json_encode($result));
    }

    /**
     *列表职位名称修改.
     */
    public function update_job_name()
    {
        $this->Job->hasMany = array();
        $this->Job->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->JobI18n->updateAll(
            array('name' => "'".$val."'"),
            array('Job_id' => $id, 'locale' => $this->locale)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *列表推荐修改.
     */
    public function toggle_on_status()
    {
        $this->Job->hasMany = array();
        $this->Job->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Job->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['change_position_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *删除一个职位.
     *
     *@param int $id 输入职位ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $pn = $this->JobI18n->find('list', array('fields' => array('JobI18n.job_id', 'JobI18n.name'), 'conditions' => array('JobI18n.job_id' => $id, 'JobI18n.locale' => $this->locale)));
        $this->Job->deleteAll(array('Job.id' => $id));
        $this->JobI18n->deleteAll(array('JobI18n.job_id' => $id));
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除职位:id '.$id, $this->admin['id']);
            }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
