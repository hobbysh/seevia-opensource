<?php

    /*****************************************************************************
 * Seevia 招聘管理
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
/**
 *这是一个名为 ResumesController 的控制器
 *后台简历管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class ResumesController extends AppController
{
    public $name = 'Resumes';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('Resume','InformationResource','ResumeEducation','ResumeExperience','ResumeLanguage','OperatorLog','Job');

    /**
     *显示简历列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('resumes_view');
        $this->menu_path = array('root' => '/cms/','sub' => '/resumes/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['recruitment_management'],'url' => '');

        $condition = '';
        $keywords = '';     //关键字
        //职位
        if (isset($this->params['url']['job_id']) && $this->params['url']['job_id'] != '') {
            $job_id = $this->params['url']['job_id'];
            $condition['and']['job_id'] = $job_id;
            $this->set('job_id', $job_id);
        }
        //关键字
        if (isset($this->params['url']['keywords']) && $this->params['url']['keywords'] != '') {
            $keywords = trim($this->params['url']['keywords']);
            $condition['and']['or']['Resume.name like'] = '%'.$keywords.'%';
            $condition['and']['or']['Resume.email like'] = '%'.$keywords.'%';
            $condition['and']['or']['Resume.mobile like'] = '%'.$keywords.'%';
            $condition['and']['or']['Resume.apartments like'] = '%'.$keywords.'%';
        }
        if (isset($this->params['url']['experience_id']) && $this->params['url']['experience_id'] != '-1') {
            $experience_id = $this->params['url']['experience_id'];
            $condition['and']['Resume.experience_id'] = $experience_id;
            $this->set('experience_id', $experience_id);
        }
        $total = $this->Resume->find('count', array('conditions' => $condition));//统计全部简历总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'Resume';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'Resumes','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Resume');
        $this->Pagination->init($condition, $parameters, $options);

        $resume_list = $this->Resume->find('all', array('conditions' => $condition, 'order' => 'created desc', 'limit' => $rownum, 'page' => $page));
        $this->set('resume_list', $resume_list);//简历列表
        $this->set('keywords', $keywords);//关键字选中
        $this->set('title_for_layout', $this->ld['resume_list'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $informationresource_info = $this->InformationResource->information_formated(array('education_type', 'experience_type', 'department_type', 'job_type', 'education_type', 'certificate_type'), $this->locale);
        $this->set('informationresource_info', $informationresource_info);

        $this->Job->set_locale($this->locale);
        //职位列表
        $job_list = $this->Job->find('all', array('fields' => array('Job.id', 'JobI18n.name'), 'conditions' => array('Job.status' => '1')));
        $this->set('job_list', $job_list);
        $job_list_data = array();
        foreach ($job_list as $k => $v) {
            $job_list_data[$v['Job']['id']] = $v['JobI18n']['name'];
        }
        $this->set('job_list_data', $job_list_data);
    }

    /**
     *简历 新增/编辑.
     *
     *@param int $id 输入简历ID
     */
    public function view($id = 0)
    {
        $resume_info = $this->Resume->find('first', array('conditions' => array('Resume.id' => $id)));
        if (empty($resume_info)) {
            $this->redirect('/resumes/');
        }
        $this->menu_path = array('root' => '/cms/','sub' => '/resumes/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['recruitment_management'],'url' => '/resumes/');
        $this->navigations[] = array('name' => $this->ld['resume_preview'].' - '.$resume_info['Resume']['name'],'url' => '');
        $this->set('title_for_layout', $this->ld['resume_preview'].' - '.$resume_info['Resume']['name'].' - '.$this->configs['shop_name']);
        $resume_education_infos = $this->ResumeEducation->find('all', array('conditions' => array('ResumeEducation.resume_id' => $id)));
        $resume_experience_infos = $this->ResumeExperience->find('all', array('conditions' => array('ResumeExperience.resume_id' => $id)));
        $resume_language_infos = $this->ResumeLanguage->find('all', array('conditions' => array('ResumeLanguage.resume_id' => $id)));
        $this->set('resume_info', $resume_info);
        $this->set('resume_education_infos', $resume_education_infos);
        $this->set('resume_experience_infos', $resume_experience_infos);
        $this->set('resume_language_infos', $resume_language_infos);
        $informationresource_info = $this->InformationResource->information_formated(array('language_master_type', 'language_type', 'certificate_type', 'education_type', 'experience_type', 'department_type', 'job_type', 'education_type'), $this->locale);
        $this->set('informationresource_info', $informationresource_info);
        $this->set('year', date('Y'));

        $this->Job->set_locale($this->locale);
        //职位列表
        $job_list = $this->Job->find('all', array('fields' => array('Job.id', 'JobI18n.name'), 'conditions' => array('Job.status' => '1')));
        $job_list_data = array();
        foreach ($job_list as $k => $v) {
            $job_list_data[$v['Job']['id']] = $v['JobI18n']['name'];
        }
        $this->set('job_list_data', $job_list_data);
    }
    /**
     *简历 批量操作.
     */
    public function batch_operations()
    {
        $ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $condition['Resume.id'] = $ids;
        $resume_info_list = $this->Resume->find('list', array('fields' => array('Resume.id', 'Resume.avatar'), 'conditions' => array('Resume.id' => $ids)));
        $this->ResumeEducation->deleteAll(array('ResumeEducation.resume_id' => $ids)); //删除相关教育背景
        $this->ResumeExperience->deleteAll(array('ResumeExperience.resume_id' => $ids)); //删除相关工作经验
        $this->ResumeLanguage->deleteAll(array('ResumeLanguage.resume_id' => $ids)); //删除相关语言数据
        $this->Resume->deleteAll($condition);
        $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
        foreach ($resume_info_list as $k => $v) {
            if ($v != '') {
                $avatar_image = $img_dir.$v;
                if (file_exists($avatar_image)) {
                    @unlink($avatar_image);
                }
            }
        }
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        die(json_encode($result));
    }
    /**
     *删除一个简历.
     *
     *@param int $id 输入简历ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $pn = $this->Resume->find('first', array('fields' => array('Resume.id', 'Resume.name', 'Resume.avatar'), 'conditions' => array('Resume.id' => $id)));
        $this->ResumeEducation->deleteAll(array('ResumeEducation.resume_id' => $id)); //删除相关教育背景
        $this->ResumeExperience->deleteAll(array('ResumeExperience.resume_id' => $id)); //删除相关工作经验
        $this->ResumeLanguage->deleteAll(array('ResumeLanguage.resume_id' => $id)); //删除相关语言数据
        $this->Resume->deleteAll(array('Resume.id' => $id));
        $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
        if ($pn['Resume']['avatar'] != '') {
            $avatar_image = $img_dir.$pn['Resume']['avatar'];
            if (file_exists($avatar_image)) {
                unlink($avatar_image);
            }
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete_resume'].':id '.$id.' '.$pn['Resume']['name'], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
