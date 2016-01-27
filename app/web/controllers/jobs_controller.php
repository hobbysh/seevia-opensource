<?php

/**
 *这是一个名为 JobsController 的控制器
 *职位管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class JobsController extends AppController
{
    public $name = 'Jobs';
    public $components = array('Pagination','RequestHandler','Cookie');
    public $helpers = array('Pagination','Html','Form','Javascript');
    public $uses = array('Job','InformationResource','JobI18n','CategoryArticle');

    /**
     *显示职位列表.
     */
    public function index($page = 1, $limit = 10, $department_id = 0, $type_id = 0, $order = 0, $address = 0, $name = '')
    {
    	 $_GET=$this->clean_xss($_GET);
        $this->layout = 'default';
        $this->pageTitle = $this->ld['talent_wanted'].' - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => '人才招聘','url' => '/jobs/');
        
        $conditions = '';
        $conditions['Job.status'] = 1;
        if (isset($_GET['job_name']) && $_GET['job_name'] != '') {
            $job_name = $_GET['job_name'];
            $conditions['JobI18n.name like'] = '%'.$job_name.'%';
            $this->set('job_name', $job_name);
        }
        if (isset($_GET['department_id']) && $_GET['department_id'] != -1) {
            $department_id = $_GET['department_id'];
            $conditions['Job.department_id'] = $department_id;
            $this->set('department_id', $department_id);
        }
        if (isset($_GET['type_id']) && $_GET['type_id'] != -1) {
            $type_id = $_GET['type_id'];
            $conditions['Job.type_id'] = $type_id;
            $this->set('type_id', $type_id);
        }
        if (isset($_GET['address']) && $_GET['address'] != -1) {
            $address = $_GET['address'];
            $conditions['JobI18n.address'] = $address;
            $this->set('address', $address);
        }
        if (isset($_GET['order']) && $_GET['order'] != -1) {
            $order = $_GET['order'];
            $this->set('order', $order);
        }
        //get参数
        $limit = $limit;
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'jobs', 'action' => 'index', 'page' => $page, 'limit' => $limit,'department_id' => $department_id ,'type_id' => $type_id ,'order' => $order ,'address' => $address,'name' => $name);
        //分页参数
        $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'Job');
        $this->Pagination->init($conditions, $parameters, $options); // Added
        $job_infos = $this->Job->find('all', array('conditions' => $conditions, 'page' => $page, 'limit' => $limit, 'order' => $order));
        $counts = $this->Job->find('count', array('conditions' => $conditions));
        $this->set('counts', $counts);
        $this->set('job_infos', $job_infos);
        //资源库信息
        $informationresource_infos = $this->InformationResource->code_information_formated(array('education_type', 'department_type', 'job_type'), $this->locale);
        $this->set('informationresource_infos', $informationresource_infos);

        //获取所有工作地点
        $addrss_infos = $this->JobI18n->find('list', array('conditions' => array('JobI18n.locale' => LOCALE, 'JobI18n.address !=' => ''), 'fields' => 'JobI18n.address', 'group' => 'JobI18n.address'));
        $this->set('addrss_infos', $addrss_infos);
        $job_cat_info = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.code' => 'JOB')));
        if (isset($job_cat_info) && !empty($job_cat_info)) {
            $this->set('detail', $job_cat_info['CategoryArticleI18n']['detail']);
        }
    }

    /**
     *职位 新增/编辑.
     *
     *@param int $id 输入职位ID
     */
    public function view($id = 0)
    {
        $job_info = $this->Job->find('first', array('conditions' => array('Job.id' => $id)));
        //资源库信息
        $informationresource_infos = $this->InformationResource->code_information_formated(array('experience_type', 'education_type', 'department_type', 'job_type'), $this->locale);
        $this->set('informationresource_infos', $informationresource_infos);
        $this->set('job_info', $job_info);
        $this->pageTitle = $job_info['JobI18n']['name'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['talent_wanted'],'url' => '/jobs/');
        $this->ur_heres[] = array('name' => $job_info['JobI18n']['name'],'url' => '');
        $this->layout = 'default';
    }
}
