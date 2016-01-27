<?php

/**
 *这是一个名为 ResumesController 的控制器
 *简历控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class ResumesController extends AppController
{
    public $name = 'Resumes';
    public $components = array('Pagination','RequestHandler','Cookie');
    public $helpers = array('Pagination','Html','Form','Javascript');
    public $uses = array('Resume','ResumeEducation','ResumeExperience','ResumeLanguage','Job','JobI18n');
    /**
     *显示简历列表.
     */
    public function index($page = 1)
    {
        $this->pageTitle = $this->ld['fill_in_personal_resume'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['talent_wanted'],'url' => '/jobs/');
        $this->ur_heres[] = array('name' => $this->ld['fill_in_personal_resume'],'url' => '');

        if (isset($_GET['job_id']) && $_GET['job_id'] != '') {
            $this->set('job_id', $_GET['job_id']);
        }

        $base_info = @unserialize($this->Cookie->read('base_info_cookie'));
        if (!empty($base_info)) {
            $this->set('base_info', $base_info);
            $resume_info = $this->Resume->find('first', array('conditions' => array('Resume.id' => $base_info['resume_id'])));
            $this->set('data', $resume_info);
        }
        if (isset($base_info) && isset($base_info['resume_id'])) {
            $all_education_infos = $this->ResumeEducation->find('all', array('conditions' => array('ResumeEducation.resume_id' => $base_info['resume_id'])));
            if (!empty($all_education_infos)) {
                $this->set('all_education_infos', $all_education_infos);
            }
            $all_experience_infos = $this->ResumeExperience->find('all', array('conditions' => array('ResumeExperience.resume_id' => $base_info['resume_id'])));
            if (!empty($all_experience_infos)) {
                $this->set('all_experience_infos', $all_experience_infos);
            }
            $all_language_infos = $this->ResumeLanguage->find('all', array('conditions' => array('ResumeLanguage.resume_id' => $base_info['resume_id'])));
            if (!empty($all_language_infos)) {
                $this->set('all_language_infos', $all_language_infos);
            }
        }

        //资源库信息
        $informationresource_infos = $this->InformationResource->code_information_formated(array('language_master_type', 'language_type', 'certificate_type', 'education_type', 'experience_type', 'department_type', 'job_type', 'education_type'), $this->locale);
        $this->set('informationresource_infos', $informationresource_infos);

        //职位列表
        $job_list = $this->Job->find('all', array('fields' => array('Job.id', 'JobI18n.name'), 'conditions' => array('Job.status' => '1')));
        $this->set('job_list', $job_list);
    }

    /**
     *简历 新增/编辑.
     *
     *@param int $id 输入简历ID
     */
    public function view($id = 0)
    {
        $this->pageTitle = $this->ld['resume_preview'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['talent_wanted'],'url' => '/jobs/');
        $this->ur_heres[] = array('name' => $this->ld['fill_in_personal_resume'],'url' => '/resumes/');
        $this->ur_heres[] = array('name' => $this->ld['resume_preview'],'url' => '');
        $base_info = @unserialize($this->Cookie->read('base_info_cookie'));
        if (!empty($base_info)) {
            $this->set('base_info', $base_info);
            $resume_info = $this->Resume->find('first', array('conditions' => array('Resume.id' => $base_info['resume_id'])));
            $this->set('resume_info', $resume_info);
        }
        if (isset($base_info) && isset($base_info['resume_id'])) {
            $all_education_infos = $this->ResumeEducation->find('all', array('conditions' => array('ResumeEducation.resume_id' => $base_info['resume_id'])));
            if (!empty($all_education_infos)) {
                $this->set('resume_education_infos', $all_education_infos);
            }
            $all_experience_infos = $this->ResumeExperience->find('all', array('conditions' => array('ResumeExperience.resume_id' => $base_info['resume_id'])));
            if (!empty($all_experience_infos)) {
                $this->set('resume_experience_infos', $all_experience_infos);
            }
            $all_language_infos = $this->ResumeLanguage->find('all', array('conditions' => array('ResumeLanguage.resume_id' => $base_info['resume_id'])));
            if (!empty($all_language_infos)) {
                $this->set('resume_language_infos', $all_language_infos);
            }
        }
        //资源库信息
        $informationresource_infos = $this->InformationResource->code_information_formated(array('language_master_type', 'language_type', 'certificate_type', 'education_type', 'experience_type', 'department_type', 'job_type', 'education_type'), $this->locale);
        $this->set('informationresource_infos', $informationresource_infos);
        //职位列表
        $job_list = $this->Job->find('all', array('fields' => array('Job.id', 'JobI18n.name'), 'conditions' => array('Job.status' => '1')));
        $this->set('job_list', $job_list);
        $this->set('year', date('Y'));
        $this->layout = 'default_full';
    }
    //保存简历基本信息
    public function save_base_info()
    {
    	 if (isset($_POST)) {
        	$_POST=$this->clean_xss($_POST);
        }
        Configure::write('debug', 1);
        $this->layout='ajax';
        $result['falg'] = 0;
        $result['msg'] = 'failed';
        if (isset($_POST['data']['Resume'])) {
            if (isset($_POST['resume_id']) && $_POST['resume_id'] != '') {
                $_POST['data']['Resume']['id'] = $_POST['resume_id'];
            }
            //$_POST['data']['Resume']['birthday'] = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
            $_POST['data']['Resume']['introduce'] = str_replace("\n", '<br />', $_POST['data']['Resume']['introduce']);
            $this->Resume->save($_POST['data']['Resume']);
            $_POST['resume_id'] = $this->Resume->id;
            unset($_POST['data']);
            $this->Cookie->write('base_info_cookie', serialize($_POST), false, 3600 * 24 * 7);
            $result['falg'] = 1;
            $result['msg'] = 'success';
            $result['resume_id'] = $this->Resume->id;
            die(json_encode($result));
        }
    }
    //添加信息
    public function addinfo()
    {
        Configure::write('debug', 1);
        if (isset($_POST)) {
        	$_POST=$this->clean_xss($_POST);
        }
        $info_type = $_POST['info_type'];
        $count = $_POST['count'];
        $this->set('count', $count);
        $this->set('info_type', $info_type);
        $result = array();

        $lan = $_POST['lan'];
        if ($lan != '') {
            $ld = $this->LanguageDictionary->getformatcode($lan);
            $this->set('ld', $ld);
            $this->locale = $lan;
            $this->InformationResource->hasOne = array('InformationResourceI18n' => array('className' => 'InformationResourceI18n',
                'conditions' => array('locale' => $lan),
                'order' => '',
                'dependent' => true,
                'foreignKey' => 'information_resource_id',
            ),
            );
        }
        //资源库信息
        $informationresource_infos = $this->InformationResource->code_information_formated(array('language_master_type', 'language_type', 'certificate_type', 'education_type', 'experience_type', 'department_type', 'job_type', 'education_type'), $this->locale);
        $this->set('informationresource_infos', $informationresource_infos);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }
    //保存信息
    public function save_info()
    {
        Configure::write('debug', 1);
        if (isset($_POST)) {
        	$_POST=$this->clean_xss($_POST);
        }
        $result = array();
        if ($_POST['info_type'] == 'education') {
            $_POST['data']['ResumeEducation']['resume_id'] = $_POST['resume_id'];
            $_POST['data']['ResumeEducation']['start_time'] = $_POST['start_year'].'-'.$_POST['start_month'];
            if ($_POST['end_year'] != '' || $_POST['end_month'] != '') {
                if ($_POST['end_year'] == '') {
                    $_POST['end_year'] = intval(date('Y'));
                }
                if ($_POST['end_month'] == '') {
                    $_POST['end_month'] = intval(date('m'));
                }
                $end_time = $_POST['end_year'].'-'.$_POST['end_month'];
            }
            if ($_POST['end_year'] == '' && $_POST['end_month'] == '') {
                $end_time = '';
            }
            $_POST['data']['ResumeEducation']['description'] = str_replace("\n", '<br />', $_POST['data']['ResumeEducation']['description']);
            $_POST['data']['ResumeEducation']['end_time'] = $end_time;
            $this->ResumeEducation->save($_POST['data']['ResumeEducation']);
            $result['id'] = $this->ResumeEducation->id;
            $all_education_infos = $this->ResumeEducation->find('all', array('conditions' => array('ResumeEducation.resume_id' => $_POST['resume_id'])));
            //$this->Cookie->write('education_infos',serialize($all_education_infos),false,3600 * 24 * 7);
        } elseif ($_POST['info_type'] == 'experience') {
            $_POST['data']['ResumeExperience']['resume_id'] = $_POST['resume_id'];
            $_POST['data']['ResumeExperience']['start_time'] = $_POST['start_year'].'-'.$_POST['start_month'];
            if ($_POST['end_year'] != '' || $_POST['end_month'] != '') {
                if ($_POST['end_year'] == '') {
                    $_POST['end_year'] = intval(date('Y'));
                }
                if ($_POST['end_month'] == '') {
                    $_POST['end_month'] = intval(date('m'));
                }
                $end_time = $_POST['end_year'].'-'.$_POST['end_month'];
            }
            if ($_POST['end_year'] == '' && $_POST['end_month'] == '') {
                $end_time = '';
            }
            $_POST['data']['ResumeExperience']['end_time'] = $end_time;
            $_POST['data']['ResumeExperience']['description'] = str_replace("\n", '<br />', $_POST['data']['ResumeExperience']['description']);
            $this->ResumeExperience->save($_POST['data']['ResumeExperience']);
            $result['id'] = $this->ResumeExperience->id;
            $all_experience_infos = $this->ResumeExperience->find('all', array('conditions' => array('ResumeExperience.resume_id' => $_POST['resume_id'])));
            //$this->Cookie->write('experience_infos',serialize($all_experience_infos),false,3600 * 24 * 7);
        } elseif ($_POST['info_type'] == 'language') {
            $_POST['data']['ResumeLanguage']['resume_id'] = $_POST['resume_id'];
            $this->ResumeLanguage->save($_POST['data']['ResumeLanguage']);
            $result['id'] = $this->ResumeLanguage->id;
            $all_language_infos = $this->ResumeLanguage->find('all', array('conditions' => array('ResumeLanguage.resume_id' => $_POST['resume_id'])));
            //$this->Cookie->write('language_infos',serialize($all_language_infos),false,3600 * 24 * 7);
        }
        $this->set('result', $result);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //删除信息
    public function delete_info()
    {
    	 if (isset($_POST)) {
        	$_POST=$this->clean_xss($_POST);
        }
        Configure::write('debug', 0);
        if ($_POST['info_type'] == 'education') {
            $this->ResumeEducation->deleteall(array('ResumeEducation.id' => $_POST['info_id']));
            $all_education_infos = $this->ResumeEducation->find('all', array('conditions' => array('ResumeEducation.id' => $_POST['resume_id'])));
            $this->Cookie->write('education_infos', serialize($all_education_infos), false, 3600 * 24 * 7);
        } elseif ($_POST['info_type'] == 'experience') {
            $this->ResumeExperience->deleteall(array('ResumeExperience.id' => $_POST['info_id']));
            $all_experience_infos = $this->ResumeExperience->find('all', array('conditions' => array('ResumeExperience.id' => $_POST['resume_id'])));
            $this->Cookie->write('experience_infos', serialize($all_experience_infos), false, 3600 * 24 * 7);
        } elseif ($_POST['info_type'] == 'language') {
            $this->ResumeLanguage->deleteall(array('ResumeLanguage.id' => $_POST['info_id']));
            $all_language_infos = $this->ResumeLanguage->find('all', array('conditions' => array('ResumeLanguage.resume_id' => $_POST['resume_id'])));
            $this->Cookie->write('all_language_infos', serialize($all_language_infos), false, 3600 * 24 * 7);
        }
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //上传图片
    public function uploadPic()
    {
        $this->layout = 'ajax';
        Configure::write('debug', 0);

        $result['code'] = 0;
        $result['msg'] = 'not null';
        if ($this->RequestHandler->isPost()) {
            $max_size = 3;
            $types = array('png','gif','jpg','jpeg','JPG','PNG','JPEG','GIF');
            $image_ext = 'jpg、jpeg、png、gif';
            $imgInfo = $_FILES['avatar_file'];
            $info = pathinfo($imgInfo['name']);
            if (!in_array($info['extension'], $types)) {
                $result['msg'] = '只支持以下图片格式'.$image_ext;
            } elseif ($imgInfo['size'] == 0 || $imgInfo['size'] > ($max_size * 1048576)) {
                $result['msg'] = 'Size Error';
            } else {
                $dir_root = WWW_ROOT.'media/';
                if (!is_dir($dir_root.'resume_avatar/')) {
                    @mkdir($dir_root.'resume_avatar/', 0777);
                    @chmod($dir_root.'resume_avatar/', 0777);
                } else {
                    @chmod($dir_root.'resume_avatar/', 0777);
                }
                $img_name = date('Ymd').rand().'.'.$info['extension'];
                $img_path = WWW_ROOT.'media/resume_avatar/'.$img_name;
                $img_url = '/media/resume_avatar/'.$img_name;
                if (move_uploaded_file($imgInfo['tmp_name'], $img_path)) {
                    $result['code'] = 1;
                    $result['msg'] = 'Success';
                    $result['upload_img_url'] = $img_url;
                }
            }
        }

        die(json_encode($result));
    }
}
