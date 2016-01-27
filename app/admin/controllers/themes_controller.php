<?php

/*****************************************************************************
 * Seevia 模板管理
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
class ThemesController extends AppController
{
    public $name = 'Themes';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Tinymce','fck','Ckeditor');
    public $uses = array('Advertisement','AdvertisementPosition','AdvertisementPosition','Advertisement','Config','Template','ConfigI18n','Application','OperatorLog','PageType');

    public function index()
    {
        /*判断权限*/
        $this->operator_privilege('themes_view');
        $this->menu_path = array('root' => '/system/','sub' => '/themes/');
        /*end*/
        $this->pageTitle = $this->ld['manager_templates'].' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manager_templates'],'url' => '/themes/');
        $this->set('title_for_layout', $this->pageTitle);
        //$x=ll;
        /* 获得当前的模版的信息 */
        $curr_template = $this->Template->find("where is_default ='1'");
        if ($curr_template) {
            $curr_template_arr = $this->get_template_info($curr_template['Template']['name']);
            $curr_template_arr['template_style'] = $curr_template['Template']['template_style'];
            $curr_template_arr['template_img'] = $curr_template['Template']['template_img'];
            //模块信息
            $page_type_list = $this->PageType->find('all', array('conditions' => array('PageType.code' => $curr_template['Template']['name'])));
            $this->set('page_type_list', $page_type_list);
            //pr($page_type_list);
        } else {
            $curr_template_arr = $this->get_template_info('SV_DEFAULT');
            $curr_template_arr['template_style'] = '';
        }
        $curr_template_arr['id'] = $curr_template['Template']['id'];
        /* 获得目录可用的模版 */
        $available_templates = $this->Template->find('all', array('conditions' => array('is_default' => '0')));
        $theme_styles = array();
        $base_theme_styles_mp = $this->Template->find('all', array('conditions' => array('status <>' => 0), 'fields' => array('name', 'template_style', 'template_img')));
        $base_theme_styles = array();
        $tmp3 = array();
        foreach ($base_theme_styles_mp as $k1 => $v1) {
            $tmp3 = array();
            $v1['Template']['template_style'] = explode(',', $v1['Template']['template_style']);
            $v1['Template']['template_img'] = explode(',', $v1['Template']['template_img']);
            $i = 0;
            foreach ($v1['Template']['template_style'] as $k2 => $v2) {
                $tmp3['Template']['template_img'][$v2] = @$v1['Template']['template_img'][$i];
                ++$i;
            }
            $base_theme_styles[$v1['Template']['name']]['template_style'] = $v1['Template']['template_style'];
            $base_theme_styles[$v1['Template']['name']]['template_img'] = $tmp3['Template']['template_img'];
        }
        $this->set('duoyu', $base_theme_styles);
        //var_dump($base_theme_styles);//应有颜色
//	    $has_theme_styles=$this->Template->find("all",array("fields"=>array("name","template_style")));
//	    //var_dump($has_theme_styles);
//	    foreach($has_theme_styles as $k2=>$v2){
//	    	//var_dump($v2);
//	    	//var_dump($base_theme_styles[$v2["Template"]['name']]["template_style"]);
//
//	    }
        //var_dump($base_theme_styles);
        //var_dump($curr_template_arr);
//		$template_dir = opendir(WWW_ROOT.'thm/');
//	    while ($file = readdir($template_dir))
//	    {
//	        if ($file != '.' && $file != '..' && is_dir('../themed/' . $file) && $file != '.svn' && $file != 'index.htm')
//	        {
//	            $available_templates[] = $this->get_template_info($file);
//	            //$theme_styles[$file] = $this->theme_styles($file);
//	        }
//	    }
        $this->set('theme_styles', $theme_styles);
        $install_templates = $available_templates;
        //closedir($template_dir);
        $tem = array();
        $temp = array();//find('all',array('fields' => array('DISTINCT WeiboConfig.type')));
        $template_list = $this->Template->find('all', array('conditions' => array('is_default' => 0, 'status' => 1), 'fields' => array('DISTINCT Template.name')));//可用模板
        foreach ($template_list as $k => $v) {
            $tem[$k] = $v['Template']['name'];
        }
        $template_in = $this->Template->find('all', array('fields' => array('DISTINCT Template.name')));//数据库模板信息
        foreach ($template_in as $k => $v) {
            $temp[$k] = $v['Template']['name'];
        }
        /*
        foreach($available_templates as $k=>$v ){
            if(in_array($v["Template"]['name'],$tem)){
                $available_templates[$k]["Template"]['flag']="1";
            }else{
                $available_templates[$k]["Template"]['flag']="";
            }
            if(in_array($v["Template"]['name'],$temp)){
            }else{
                unset($available_templates[$k]);
            }
            if($v["Template"]['name']===$curr_template_arr['name']){
                unset($available_templates[$k]);
            }
        }
        */
      /*未安装模板信息*/
        foreach ($install_templates as $k => $v) {
            if (in_array($v['Template']['name'], $temp)) {
                unset($install_templates[$k]);
            }
        }
        /*收费模板信息*/
        $fei_thm = $this->Template->find('all', array('conditions' => array('is_default' => 0, 'status' => 2)));
        //var_dump($fei_thm);
        $tem_fei = array();
        foreach ($fei_thm as $k => $v) {
            $tem_fei[] = $v['Template']['name'];
        }
        //$tem_fei[]='seevia';
        $fei = $this->Template->find('all', array('conditions' => array('status' => 2)));
        $fei_thm_base = array();
        $fei_name = array();
        foreach ($fei as $k => $v) {
            $fei_thm_base[$v['Template']['name']] = $v;
            $fei_name[] = $v['Template']['name'];
        }
        foreach ($tem_fei as $k => $v) {
            if (in_array($v, $fei_name)) {
                unset($fei_thm_base[$v]);
            }
        }
        $this->set('curr_template', $curr_template_arr);
        $this->set('available_templates', $available_templates);
        $this->set('install_templates', $install_templates);
        $this->set('fei_templates', $fei_thm);
        $this->set('fei_thm_base', $fei_thm_base);
    }
    //模版新增、编辑
    public function view($id = 0, $template_name = 'default')
    {
        $this->menu_path = array('root' => '/system/','sub' => '/themes/');
        /*判断权限*/
        $this->operator_privilege('themes_edit');
        /*end*/
        $this->set('title_for_layout', $this->ld['template'].'-'.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manager_templates'],'url' => '/themes/');
        $template_list = $this->Template->find('first', array('conditions' => array('Template.id' => $id)));
        if (!empty($template_list['Template'])) {
            $this->navigations[] = array('name' => $this->ld['template'].' - '.$template_list['Template']['description'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        }
        if (isset($template_list['Template']['mobile_css']) && $template_list['Template']['mobile_css'] != '') {
            $template_list['Template']['mobile_css'] = json_decode($template_list['Template']['mobile_css'], true);
        }
        $this->set('template_list', $template_list);

        if (!empty($template_list)) {
            //模块信息
            $page_type_list = $this->PageType->find('all', array('conditions' => array('PageType.code' => $template_list['Template']['name'])));
            $this->set('page_type_list', $page_type_list);

            //广告位列表
            $AdvertisementPosition_fields[] = 'id';
            $AdvertisementPosition_fields[] = 'name';
            $AdvertisementPosition_fields[] = 'ad_width';
            $AdvertisementPosition_fields[] = 'ad_height';
            $AdvertisementPosition_fields[] = 'position_desc';
            $AdvertisementPosition_fields[] = 'template_name';
            $AdvertisementPosition_fields[] = 'orderby';
            $AdvertisementPosition_fields[] = 'code';
            $AdvertisementPosition_conditions = array('AdvertisementPosition.template_name' => $template_list['Template']['name']);
            $AdvertisementPosition_cond = array('conditions' => $AdvertisementPosition_conditions,'fields' => $AdvertisementPosition_fields,'order' => 'orderby asc');
            $advertisement_position_list = $this->AdvertisementPosition->find('all', $AdvertisementPosition_cond);
            $this->set('advertisement_position_list', $advertisement_position_list);
        }
        if ($this->RequestHandler->isPost()) {
            $this->data['Template']['name'] = isset($this->data['Template']['name']) ? $this->data['Template']['name'] : '';
            $this->data['Template']['description'] = isset($this->data['Template']['description']) ? $this->data['Template']['description'] : '';
            $this->data['Template']['status'] = isset($this->data['Template']['status']) ? $this->data['Template']['status'] : 0;
            $this->data['Template']['template_style'] = isset($this->data['Template']['template_style']) ? $this->data['Template']['template_style'] : '';
            if (isset($this->data['Template']['is_default']) && $this->data['Template']['is_default'] == 1) {
                $this->Template->updateAll(array('is_default' => 0));
            }
            $this->data['Template']['is_default'] = isset($this->data['Template']['is_default']) ? $this->data['Template']['is_default'] : 0;
            if (isset($this->data['Template']['mobile_css']) && is_array($this->data['Template']['mobile_css']) && sizeof($this->data['Template']['mobile_css']) > 0) {
                $this->data['Template']['mobile_css'] = json_encode($this->data['Template']['mobile_css']);
            }
            $this->Template->save($this->data);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].$this->ld['template'].':id '.$id.' '.$this->data['Template']['name'], $this->admin['id']);
            }
            $this->redirect('/themes/');
        }
    }

    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('themes_remove');
        /*end*/
        $this->Template->deleteAll(array('Template.id' => $id));
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['remove'].' '.$this->ld['templates'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_the_ad_list_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function templatecopy($id, $name)
    {
        $templateInfo = $this->Template->find('first', array('conditions' => array('Template.id' => $id)));
        if (!empty($templateInfo)) {
            unset($templateInfo['Template']['id']);
            $templateInfo_copy = $templateInfo;
            $templateInfo_copy['Template']['name'] = $name;
            $templateInfo_copy['Template']['description'] = $name;
            $templateInfo_copy['Template']['is_default'] = '0';
            $templateInfo_copy['Template']['created'] = date('Y-m-d H:i:s', time());
            $templateInfo_copy['Template']['modified'] = date('Y-m-d H:i:s', time());
            $this->Template->save($templateInfo_copy);
        }
        $this->redirect('/themes/');
    }

    /*
        ajax验证模板名称
    */
    public function check_themes_name()
    {
        $this->operator_privilege('themes_edit');
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $template_name = $_POST['template_name'];
            $Templateinfo = $this->Template->find('first', array('conditions' => array('Template.name' => $template_name)));
            if (empty($Templateinfo)) {
                $result['code'] = 1;
                $result['msg'] = '';
            } else {
                $result['code'] = 0;
                $result['msg'] = $this->ld['template_name_already_exists'];
            }
            die(json_encode($result));
        } else {
            $this->redirect('/themes/');
        }
    }

    /*
        ajax修改模板描述
    */
    public function update_themes_desc()
    {
        $this->operator_privilege('themes_edit');
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $val = isset($_REQUEST['val']) ? $_REQUEST['val'] : '';
        $result['flag'] = 2;
        $result['content'] = $this->ld['templates'].' '.$this->ld['not_exist'];
        $Templateinfo = $this->Template->find('first', array('conditions' => array('Template.id' => $id)));
        if (!empty($Templateinfo)) {
            $Templateinfo['Template']['description'] = $val;
            $this->Template->save($Templateinfo);

            $result['flag'] = 1;
            $result['content'] = stripslashes($val);

            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].$this->ld['template'].':id '.$id.' '.$this->ld['name'].':'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
        ajax修改模板样式
    */
    public function update_themes_style()
    {
        $this->operator_privilege('themes_edit');
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $val = isset($_REQUEST['val']) ? $_REQUEST['val'] : '';
        $result['flag'] = 2;
        $result['content'] = $this->ld['templates'].' '.$this->ld['not_exist'];
        $Templateinfo = $this->Template->find('first', array('conditions' => array('Template.id' => $id)));
        if (!empty($Templateinfo)) {
            $Templateinfo['Template']['template_style'] = $val;
            $this->Template->save($Templateinfo);

            $result['flag'] = 1;
            $result['content'] = stripslashes($val);

            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].$this->ld['template'].':id '.$id.' '.$this->ld['module_style'].':'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
        ajax修改模板状态
    */
    public function update_themes_status()
    {
        $this->operator_privilege('themes_edit');
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $val = isset($_REQUEST['val']) ? $_REQUEST['val'] : '';
        $result['flag'] = 2;
        $result['content'] = $this->ld['templates'].' '.$this->ld['not_exist'];
        $Templateinfo = $this->Template->find('first', array('conditions' => array('Template.id' => $id)));
        if (!empty($Templateinfo)) {
            $Templateinfo['Template']['status'] = $val;
            $this->Template->save($Templateinfo);

            $result['flag'] = 1;
            $result['content'] = stripslashes($val);

            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].$this->ld['template'].':id '.$id.' '.$this->ld['status'].':'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //模版广告位安装
    public function installadver($code)
    {
        $ad_info = $this->AdvertisementPosition->find('all', array('conditions' => array('template_name' => $code)));
        foreach ($ad_info as $a) {
            $ad_info2['AdvertisementPosition'] = $a['AdvertisementPosition'];
            $this->AdvertisementPosition->save($ad_info2);
        }
        foreach ($ad_info as $v) {
            $adver_info = $this->Advertisement->find('all', array('conditions' => array('advertisement_position_id' => $v['AdvertisementPosition']['id'])));
            foreach ($adver_info as $vv) {
                //$vv['Advertisement']['id']='';
                //$vv['AdvertisementI18n']['id']='';
                $adver_info2['Advertisement'] = $vv['Advertisement'];
                $adver_info2['AdvertisementI18n'] = $vv['AdvertisementI18n'];
                $this->Advertisement->saveall($adver_info2);
            }
        }
    }

    public function installthemed()
    {
        //安装
        Configure::write('debug', 2);
        $code = $_REQUEST['code'];
        $this->installadver($code);
        $curr = $this->get_template_info_in($code);
        $this->data['Template']['name'] = $curr['code'];
        $this->data['Template']['author'] = $curr['author'];
        $this->data['Template']['url'] = $curr['author_uri'];
        $this->data['Template']['version'] = $curr['version'];
        $this->data['Template']['description'] = $curr['description'];
        $this->data['Template']['template_style'] = $curr['style'];
        $this->Template->saveall($this->data['Template']);
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['installation_template'].':'.$curr['description'], $this->admin['id']);
        }
        die(1);
    }

    public function get_cache_dirs($dirs)
    {
        if (is_array($dirs)) {
            $cache_dirs = array();
            foreach ($dirs as $dir) {
                if (is_dir(ROOT.$dir) && is_dir(ROOT.$dir.'/tmp/cache')) {
                    $cache_dirs[] = ROOT.$dir.'/tmp/cache/';
                    $cache_dirs[] = ROOT.$dir.'/tmp/cache/models/';
                    $cache_dirs[] = ROOT.$dir.'/tmp/cache/persistent/';
                    $cache_dirs[] = ROOT.$dir.'/tmp/cache/views/';
                }
            }

            return $cache_dirs;
        }
    }

    public function usethemed()
    {
        Configure::write('debug', 0);
        $code = $_REQUEST['code'];
        //判断是否安装过广告位
        $num = $this->AdvertisementPosition->find('count', array('conditions' => array('AdvertisementPosition.template_name' => $code)));
        if ($num == 0) {
            $this->installadver($code);
        }
        $curr_template_arr = $this->get_template_info($code);
        //var_dump($curr_template_arr);
        $temp = $this->Template->findbyname($code);
        if (isset($temp['Template']['id']) && isset($curr_template_arr['style'][0])) {
            $temp['Template']['template_style'] = $curr_template_arr['style'][0];
            $this->Template->save($temp['Template']);
        }
        $this->clear_temp_cache();
    //	if(){
    //		$this->Template->updateAll(array('Template.template_style' => $curr_template_arr['style'][0]),array('Template.name' => $code));
    //	}
        $this->Template->updateAll(array('Template.is_default' => '0'));
        $this->Template->updateAll(array('Template.is_default' => '1'), array('Template.name' => $code));
        ///改模版主页
    //	echo 1;
        $route = $this->Template->find('first', array('conditions' => array('Template.name' => $code), 'fields' => array('Template.homepage')));
        $cid = $this->Config->find('first', array('conditions' => array('Config.code' => 'route'), 'fields' => array('Config.id')));
        $this->ConfigI18n->updateAll(array('ConfigI18n.value' => "'".$route['Template']['homepage']."'"), array('ConfigI18n.config_id' => $cid['Config']['id']));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_set_default_template'].':'.$temp['Template']['description'], $this->admin['id']);
        }
        die();
    }

    public function clear_temp_cache()
    {
        $dirs = array('Seevia','sv-user');
        $cache_dirs = $this->get_cache_dirs($dirs);
        $cache_key = md5('template_list_');
        foreach ($cache_dirs as $dir) {
            $folder = @opendir($dir);
            if ($folder === false) {
                continue;
            }
            while ($file = readdir($folder)) {
                if ($file == '.' || $file == '..' || $file == '.svn') {
                    continue;
                }
                if (is_file($dir.$file) && ($dir.$file == $dir.'cake_'.$cache_key || $dir.$file == $dir.'cake_model_default_svcart_templates')) {
                    if (@unlink($dir.$file)) {
                        ++$count;
                    }
                }
            }
            closedir($folder);
        }
    }

    //卸载广告位
    public function deleteadver($code)
    {
        $ad_info = $this->AdvertisementPosition->find('all', array('conditions' => array('template_name' => $code)));
        foreach ($ad_info as $v) {
            $id = $v['AdvertisementPosition']['id'];
            $this->Advertisement->deleteall("Advertisement.advertisement_position_id='$id'");
        }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'卸载广告位 ', $this->admin['id']);
            }
        $this->AdvertisementPosition->deleteall("AdvertisementPosition.template_name='$code'");
    }

    public function deletethemed()
    {
        //卸载
        Configure::write('debug', 2);
        $name = $_REQUEST['code'];
        $this->deleteadver($name);
        $this->Template->deleteAll("Template.name='$name'");
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['uninstall_template'].':'.$name, $this->admin['id']);
        }
        die(1);
    }

    public function currencythemed()
    {
        //是否可用
        Configure::write('debug', 0);
        $code = $_REQUEST['code'];
        $flag = $_REQUEST['flag'];
        if ($flag == 'yes') {
            $this->Template->updateAll(array('Template.status' => '1'), array('Template.name' => $code));
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['set_template'].':'.$code.$this->ld['available_templates_for_current'], $this->admin['id']);
            }
        }
        if ($flag == 'no') {
            $this->Template->updateAll(array('Template.status' => '0'), array('Template.name' => $code));
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['set_template'].':'.$code.$this->ld['unuseable_template'], $this->admin['id']);
            }
        }
        die();
    }

    public function select_style($themename)
    {
        Configure::write('debug', 0);
        $curr_template = $this->Template->find("where name ='$themename'");
        if ($curr_template) {
            $this->Template->updateAll(array('is_default' => 0));
            $curr_template['Template']['template_style'] = $_POST['template_style'];
            $curr_template['Template']['is_default'] = 1;
            $this->Template->save($curr_template);
        }
        $this->clear_temp_cache();
        die();
    }

    public function tmp_show()
    {
        /* 获得当前的模版的信息 */
        $curr_template = $this->Template->find("where is_default ='1'");
        if ($curr_template) {
            $curr_template_arr = $this->get_template_info($curr_template['Template']['name']);
            $curr_template_arr['template_style'] = $curr_template['Template']['template_style'];
        } else {
            $curr_template_arr = $this->get_template_info('SV_DEFAULT');
            $curr_template_arr['template_style'] = '';
        }
        /* 获得目录可用的模版 */
        $available_templates = $this->Template->find('all', array('conditions' => array('status' => 1)));
        $theme_styles = array();
        $base_theme_styles_mp = $this->Template->find('all', array('conditions' => array('status <>' => 0), 'fields' => array('name', 'template_style', 'template_img')));
        //var_dump($base_theme_styles_mp);
        $base_theme_styles = array();
        $base_theme_styles = array();
        $tmp3 = array();
        foreach ($base_theme_styles_mp as $k1 => $v1) {
            $tmp3 = array();
            $v1['Template']['template_style'] = explode(',', $v1['Template']['template_style']);
            $v1['Template']['template_img'] = explode(',', $v1['Template']['template_img']);
            $i = 0;
            foreach ($v1['Template']['template_style'] as $k2 => $v2) {
                $tmp3['Template']['template_img'][$v2] = @$v1['Template']['template_img'][$i];
                ++$i;
            }
            $base_theme_styles[$v1['Template']['name']]['template_style'] = $v1['Template']['template_style'];
            $base_theme_styles[$v1['Template']['name']]['template_img'] = $tmp3['Template']['template_img'];
        }
        $this->set('theme_styles', $theme_styles);
        $this->set('duoyu', $base_theme_styles);
        $install_templates = $available_templates;
        $tem = array();
        $temp = array();
        $template_list = $this->Template->find('all', array('conditions' => array('is_default' => 0, 'status' => 1), 'fields' => array('DISTINCT Template.name')));//可用模板
        foreach ($template_list as $k => $v) {
            $tem[$k] = $v['Template']['name'];
        }
        $template_in = $this->Template->find('all', array('fields' => array('DISTINCT Template.name')));//数据库模板信息
        foreach ($template_in as $k => $v) {
            $temp[$k] = $v['Template']['name'];
        }
        foreach ($available_templates as $k => $v) {
            if (in_array($v['Template']['name'], $tem)) {
                $available_templates[$k]['Template']['flag'] = '1';
            } else {
                $available_templates[$k]['Template']['flag'] = '';
            }
            if (in_array($v['Template']['name'], $temp)) {
            } else {
                unset($available_templates[$k]);
            }
            if ($v['Template']['name'] === $curr_template_arr['name']) {
                unset($available_templates[$k]);
            }
        }
      /*未安装模板信息*/
        foreach ($install_templates as $k => $v) {
            if (in_array($v['Template']['name'], $temp)) {
                unset($install_templates[$k]);
            }
        }
        /*收费模板信息*/
        $fei_thm = $this->Template->find('all', array('conditions' => array('is_default' => 0, 'status' => 2)));

        $tem_fei = array();
        foreach ($fei_thm as $k => $v) {
            $tem_fei[] = $v['Template']['name'];
        }
        //$tem_fei[]='seevia';
        $fei = $this->Template->find('all', array('conditions' => array('status' => 2)));
        $fei_thm_base = array();
        $fei_name = array();
        foreach ($fei as $k => $v) {
            $fei_thm_base[$v['Template']['name']] = $v;
            $fei_name[] = $v['Template']['name'];
        }
        foreach ($tem_fei as $k => $v) {
            if (in_array($v, $fei_name)) {
                unset($fei_thm_base[$v]);
            }
        }
        // var_dump($available_templates);
        //var_dump($curr_template_arr);
        $this->set('curr_template',       $curr_template_arr);
        $this->set('available_templates', $available_templates);
        $this->set('install_templates', $install_templates);
        $this->set('fei_templates', $fei_thm);
        $this->set('fei_thm_base', $fei_thm_base);
    }

    public function get_template_info($template_name)
    {
        $info = array();
        $ext = array('png', 'gif', 'jpg', 'jpeg');
        $info['code'] = $template_name;
        $info['screenshot'] = '';
        if (empty($template_name)) {
            return false;
        }
        $temp = $this->Template->find('first', array('conditions' => array('Template.name' => $template_name)));
        $tmp2 = $this->Template->find('first', array('conditions' => array('Template.name' => $template_name)));
        if (!empty($temp) && !empty($template_name)) {
            $template_name = $temp['Template']['name'];
            $template_style = $tmp2['Template']['template_style'];
            //$template_uri       = $temp["Template"]["name"];
           // $template_desc      = $temp["Template"]["name"];
            $template_version = $temp['Template']['version'];
            $template_author = $temp['Template']['author'];
            $author_uri = $temp['Template']['url'];
            $template_description = $temp['Template']['description'];
            $info['style'] = explode(',', $template_style);
            $info['name'] = isset($template_name) ? trim($template_name) : '';
            //$info['uri']        = isset($template_uri) ? trim($template_uri) : '';
           // $info['desc']       = isset($template_desc) ? trim($template_desc) : '';
            $info['version'] = isset($template_versio) ? trim($template_version) : '';
            $info['author'] = isset($template_author) ? trim($template_author) : '';
            $info['description'] = isset($template_description) ? trim($template_description) : '';
            $info['author_uri'] = isset($author_uri[1]) ? trim($author_uri) : '';
        } else {
            $info['description'] = '';
            $info['style'] = '';
            $info['name'] = '';
            $info['uri'] = '';
            $info['desc'] = '';
            $info['version'] = '';
            $info['author'] = '';
            $info['author_uri'] = '';
        }
        $screenshot = isset($info['style'][0]) ? 'screenshot_'.$info['style'][0] : 'screenshot';
        foreach ($ext as $val) {
            if (file_exists('../themed/'.$info['code']."/{$screenshot}.{$val}")) {
                $info['screenshot'] = '/themed/'.$info['code']."/{$screenshot}.{$val}";
                break;
            }
        }

        return $info;
    }

    public function get_template_info_in($template_name)
    {
        $info = array();
        $ext = array('png', 'gif', 'jpg', 'jpeg');
        $info['code'] = $template_name;
        $info['screenshot'] = '';
        if (empty($template_name)) {
            return false;
        }
        $temp = $this->Template->find('first', array('conditions' => array('Template.name' => $template_name)));
        var_dump($temp);
        if (!empty($temp) && !empty($template_name)) {
            $template_name = $temp['Template']['name'];
            $template_style = $temp['Template']['template_style'];
            $template_style = explode(',', $template_style);
            //$template_uri       = $temp["Template"]["name"];
           // $template_desc      = $temp["Template"]["name"];
            $template_version = $temp['Template']['version'];
            $template_author = $temp['Template']['author'];
            $author_uri = $temp['Template']['url'];
            $template_description = $temp['Template']['description'];
            $info['style'] = $template_style[0];
            $info['name'] = isset($template_name) ? trim($template_name) : '';
            //$info['uri']        = isset($template_uri) ? trim($template_uri) : '';
           // $info['desc']       = isset($template_desc) ? trim($template_desc) : '';
            $info['version'] = isset($template_versio) ? trim($template_version) : '';
            $info['author'] = isset($template_author) ? trim($template_author) : '';
            $info['description'] = isset($template_description) ? trim($template_description) : '';
            $info['author_uri'] = isset($author_uri[1]) ? trim($author_uri) : '';
        } else {
            $info['description'] = '';
            $info['style'] = '';
            $info['name'] = '';
            $info['uri'] = '';
            $info['desc'] = '';
            $info['version'] = '';
            $info['author'] = '';
            $info['author_uri'] = '';
        }
        $screenshot = isset($info['style']) ? 'screenshot_'.$info['style'] : 'screenshot';
//	    foreach ($ext AS $val)
//	    {
//	        if (file_exists('../themed/' .  $info['code'] . "/{$screenshot}.{$val}"))
//	        {
//	            $info['screenshot'] = '/themed/' .  $info['code'] . "/{$screenshot}.{$val}";
//	            break;
//	        }
//	    }
        return $info;
    }

    public function theme_styles($tpl_name, $flag = 1)
    {
        if (empty($tpl_name) && $flag == 1) {
            return 0;
        }
        /* 获得可用的模版 */
        $temp = '';
        $start = 0;
        $available_templates = array();
        $dir = '../themed/'.$tpl_name.'/css/';
        $tpl_style_dir = @opendir($dir);
        while ($file = readdir($tpl_style_dir)) {
            if ($file != '.' && $file != '..' && is_file($dir.$file) && $file != '.svn' && $file != 'index.htm') {
                if (eregi('^(style|style_)(.*)*', $file)) {
                    // 取模板风格缩略图

                    $start = strpos($file, '.');
                    $temp = substr($file, 0, $start);
                    $temp = explode('_', $temp);
                    if (count($temp) == 2) {
                        $available_templates[] = $temp[1];
                    }
                }
            }
        }
        @closedir($tpl_style_dir);
        $templates_temp = array('');
        if (count($available_templates) > 0) {
            foreach ($available_templates as $value) {
                $templates_temp[] = $value;
            }
        }

        return $templates_temp;
    }

    public function rss_str()
    {
        $this->layout = 'ajax';
        $rssfeed = array('http://htdocs.trunk.seevia.cn/products/templete_rss/10');
        for ($i = 0;$i < sizeof($rssfeed);++$i) {
            //分解开始
            $buff = '';
            $rss_str = '';
            //打开rss地址，并读取，读取失败则中止
            if (!@get_headers($rssfeed[$i])) {
                return array();
            }
            $fp = fopen($rssfeed[$i], 'r') or die("can not open $rssfeed");
            while (!feof($fp)) {
                $buff .= fgets($fp, 4096);
            }
            //关闭文件打开
            fclose($fp);
            //建立一个 XML 解析器
            $parser = xml_parser_create();
            //xml_parser_set_option -- 为指定 XML 解析进行选项设置
            xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
            //xml_parse_into_struct -- 将 XML 数据解析到数组$values中
            xml_parse_into_struct($parser, $buff, $values, $idx);
            //xml_parser_free -- 释放指定的 XML 解析器
            xml_parser_free($parser);
            foreach ($values as $val) {
                $tag = @$val['tag'];
                $type = @$val['type'];
                $value = @$val['value'];
                //标签统一转为小写
                $tag = strtolower($tag);
                if ($tag == 'item' && $type == 'open') {
                    $is_item = 1;
                } elseif ($tag == 'item' && $type == 'close') {
                    //构造输出字符串
                    $templates[] = array('title' => $title,'link' => $link,'pubdate' => $pubdate,'img_thumb' => $img_thumb,'shop_price' => $shop_price);
                    $is_item = 0;
                }
                //仅读取item标签中的内容
                if (@$is_item == 1) {
                    if ($tag == 'title') {
                        $title = $value;
                    } elseif ($tag == 'link') {
                        $link = $value;
                    } elseif ($tag == 'pubdate') {
                        $pubdate = $value;
                    } elseif ($tag == 'img_thumb') {
                        $img_thumb = $value;
                    } elseif ($tag == 'shop_price') {
                        $shop_price = $value;
                    }
                }
            }
        }
        $this->set('templates', $templates);
        Configure::write('debug', 0);
        //die(json_encode($rss_str));
    }

    public function show_css()
    {
        Configure::write('debug', 0);
        $result = array();
        $result['code'] = 0;
        $result['css'] = '';
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
        $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
        $result['code2'] = $code;
        $result['type'] = $type;
        if ($type == 'show' && !empty($code)) {
            $css = $this->Template->find('first', array('conditions' => array('name' => $code), 'fields' => array('Template.show_css')));
            $result['css'] = $css['Template']['show_css'];
        } elseif ($type == 'edit' && !empty($code)) {
            $scss = isset($_POST['show_css']) ? $_POST['show_css'] : '';
            $x = $this->Template->updateAll(array('Template.show_css' => "'".$scss."'"), array('Template.name' => $code));
        }
        die(json_encode($result));
    }

    public function edit_css($code)
    {
        $this->operator_privilege('themes_edit');
        $this->set('title_for_layout', '编辑CSS - 模板管理'.' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => '界面管理','url' => '');
        $this->navigations[] = array('name' => '模板管理','url' => '/themes/');
        $this->navigations[] = array('name' => '编辑模板css','url' => '');
        $css = $this->Template->find('first', array('conditions' => array('name' => $code), 'fields' => array('Template.show_css')));
        $this->set('id', $code);
        $this->set('css_info', $css['Template']['show_css']);
        if ($this->RequestHandler->isPost()) {
            $scss = isset($_REQUEST['css_info']) ? $_REQUEST['css_info'] : '';
            $x = $this->Template->updateAll(array('Template.show_css' => "'".$scss."'"), array('Template.name' => $code));
            $this->redirect('/themes/');
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑模版css ', $this->admin['id']);
        }
    }

    public function object_array($array)
    {
        if (is_object($array)) {
            $array = (array) $array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = $this->object_array($value);
            }
        }

        return $array;
    }

     /**
      *函数mobile_css_config 手机模板页面设置.
      *
      *@prama $name 模板名
      */
     public function mobile_css_config($name = 'default')
     {
         $this->operator_privilege('themes_edit');
         $this->menu_path = array('root' => '/system/','sub' => '/themes/');
         $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
         $this->navigations[] = array('name' => $this->ld['manager_templates'],'url' => '/themes/');
         $this->navigations[] = array('name' => $this->ld['mobilephone_set'],'url' => '/themes/mobile_css_config/');

         $this->set('title_for_layout', $this->ld['mobilephone_set'].' - '.$this->configs['shop_name']);
         $theme_info = $this->Template->find('first', array('conditions' => array('name' => $name)));
         if (!empty($theme_info)) {
             $this->data = json_decode($theme_info['Template']['mobile_css'], true);
         }
         if ($this->RequestHandler->isPost()) {
             $color_config = array();
             $theme_info = $this->Template->find('first', array('conditions' => array('name' => $name)));
             $color_config['id'] = $theme_info['Template']['id'];
             $color_config['mobile_css'] = json_encode($_REQUEST);
                /*
                $css = '';
                $css .= $this->color_change(".ui-title","color",$_REQUEST['header_font_color']);
                $css .= $this->color_change(".ui-title","text-shadow",$_REQUEST['header_font_shadow_color']);
                $css .= $this->color_change(".ui-header","background",$_REQUEST['header_background_color1'],$_REQUEST['header_background_color2']);
                $css .= $this->color_change(".ui-header","border-color",$_REQUEST['header_frame_color']);
                $css .= $this->color_change(".ui-collapsible-heading .ui-btn","color",$_REQUEST['title_font_color']);
                $css .= $this->color_change(".ui-collapsible-heading .ui-btn","text-shadow",$_REQUEST['title_font_shadow_color']);
                $css .= $this->color_change(".ui-collapsible-heading .ui-btn","background",$_REQUEST['title_background_color1'],$_REQUEST['title_background_color2']);
                $css .= $this->color_change(".ui-collapsible-heading .ui-btn,.ui-collapsible-content","border-color",$_REQUEST['title_frame_color']);

                $css .= $this->color_change(".ui-collapsible-content .ui-listview .ui-btn .ui-link-inherit","color",$_REQUEST['home_list_font_color']);
                $css .= $this->color_change(".ui-collapsible-content .ui-listview .ui-btn .ui-link-inherit","text-shadow",$_REQUEST['home_list_font_shadow_color']);
                $css .= $this->color_change(".ui-collapsible-content .ui-listview .ui-btn","background",$_REQUEST['home_list_background_color1'],$_REQUEST['home_list_background_color2']);
                $css .= $this->color_change(".ui-collapsible-content .ui-listview .ui-btn","border-color",$_REQUEST['home_list_frame_color']);

                $css .= $this->color_change(".ui-footer .ui-link","color",$_REQUEST['foot_font_color']);
                $css .= $this->color_change(".ui-footer .ui-link","text-shadow",$_REQUEST['foot_font_shadow_color']);
                $css .= $this->color_change(".ui-footer","background",$_REQUEST['foot_background_color1'],$_REQUEST['foot_background_color2']);
                $css .= $this->color_change(".ui-footer","border-color",$_REQUEST['foot_frame_color']);
                $css .= $this->color_change(".ui-footer .workselected","background",$_REQUEST['foot_hightlight_background_color1'],$_REQUEST['foot_hightlight_background_color2']);//底部高亮背景
                $css .= $this->color_change(".ui-footer .workselected .ui-link","color",$_REQUEST['foot_hightlight_font_color']);
                $css .= $this->color_change(".ui-footer .workselected .ui-link","text-shadow",$_REQUEST['foot_hightlight_shadow_color']);


                $css .= $this->color_change(".homeproducttopic .ui-collapsible-content","background",$_REQUEST['home_product_background_color1'],$_REQUEST['home_product_background_color2']);
                $css .= $this->color_change(".homeproducttopic .ui-collapsible-content a","border-color",$_REQUEST['home_product_frame_color']);
                $css .= $this->color_change(".homeproducttopic .ui-collapsible-content a span","background",$_REQUEST['home_product_price_background_color1'],$_REQUEST['home_product_price_background_color2']);
                $css .= $this->color_change(".homeproducttopic .ui-collapsible-content a span","color",$_REQUEST['home_product_price_font_color']);

                $css .= $this->color_change(".ui-header .ui-btn, .ui-footer .ui-btn","background",$_REQUEST['head_button_background_color1'],$_REQUEST['head_button_background_color2']);
                $css .= $this->color_change(".ui-header .ui-btn, .ui-footer .ui-btn","color",$_REQUEST['head_button_font_color']);
                $css .= $this->color_change(".ui-header .ui-btn, .ui-footer .ui-btn","text-shadow",$_REQUEST['head_button_font_shadow_color']);
                $css .= $this->color_change(".ui-header .ui-btn, .ui-footer .ui-btn","border-color",$_REQUEST['head_button_frame_color']);

                $css .= $this->color_change(".ui-listview .ui-btn","background",$_REQUEST['list_background_color1'],$_REQUEST['list_background_color2']);
                $css .= $this->color_change(".ui-listview .ui-btn .ui-link-inherit","color",$_REQUEST['list_font_color']);
                $css .= $this->color_change(".ui-listview .ui-btn .ui-link-inherit","text-shadow",$_REQUEST['list_font_shadow_color']);
                $css .= $this->color_change(".pro_img","background",$_REQUEST['product_img_background_color1'],$_REQUEST['product_img_background_color2']);
                $css .= $this->color_change(".pro_img","border-color",$_REQUEST['product_img_background_frame_color']);
                $css .= $this->color_change(".pro_img span","border-color",$_REQUEST['product_img_frame_color']);
                $css .= $this->color_change(".picdate","background",$_REQUEST['product_attr_background_color1'],$_REQUEST['product_attr_background_color2']);
                $css .= $this->color_change(".picdate","color",$_REQUEST['product_attr_font_color']);
                $css .= $this->color_change(".picdate","text-shadow",$_REQUEST['product_attr_font_shadow_color']);
                $css .= $this->color_change(".picdate .newpic i","color",$_REQUEST['product_attr_price_color']);
                $css .= $this->color_change(".picdate .newpic i","text-shadow",$_REQUEST['product_attr_price_shadow_color']);
                $css .= $this->color_change(".picdate","border-color",$_REQUEST['product_attr_frame_color']);//详细页属性边框
                $css .= $this->color_change(".pro_date","background",$_REQUEST['product_desc_background_color1'],$_REQUEST['product_desc_background_color2']);
                $css .= $this->color_change(".pro_date","color",$_REQUEST['product_desc_font_color']);
                $css .= $this->color_change(".pro_date","text-shadow",$_REQUEST['product_desc_font_shadow_color']);
                $css .= $this->color_change(".pro_date","border-color",$_REQUEST['product_desc_frame_color']);

                $css .= $this->color_change(".per_date span, .next_date span","color",$_REQUEST['next_product_name_color']);
                $css .= $this->color_change(".per_date span, .next_date span","text-shadow",$_REQUEST['next_product_name_shadow_color']);
                $css .= $this->color_change("#last_pro_price, #next_pro_price","color",$_REQUEST['next_product_price_color']);
                $css .= $this->color_change("#last_pro_price, #next_pro_price","text-shadow",$_REQUEST['next_product_price_shadow_color']);
                $_REQUEST['next_shadow_color']
                $css .= $this->color_change(".per_date .ui-btn, .next_date .ui-btn","color",$_REQUEST['next_color']);//详细页按钮背景
                $css .= $this->color_change(".per_date .ui-btn .ui-btn-text, .next_date .ui-btn .ui-btn-text","text-shadow",$_REQUEST['next_shadow_color']);
                $css .= $this->color_change(".per_date .ui-btn, .next_date .ui-btn","background",$_REQUEST['product_button_background_color1'],$_REQUEST['product_button_background_color2']);
                $css .= $this->color_change(".per_date .ui-btn, .next_date .ui-btn","border-color",$_REQUEST['product_button_frame_color']);
                $css .= $_REQUEST['custom_css'];
                $css .= ".ui-collapsible-heading .ui-icon-minus{ background-image:url(".$_REQUEST['arrow_up'].")}";
                $css .= ".ui-collapsible-heading .ui-icon-plus{ background-image:url(".$_REQUEST['arrow_down'].")}";
                $css .= ".homeproducttopic .ui-collapsible-content .z{ background-image:url(".$_REQUEST['arrow_left']."),url(".$_REQUEST['arrow_right'].");}";
                $css .= ".homearticle .ui-collapsible-content .ui-listview .ui-icon-arrow-r,.ui-btn-icon-left .ui-btn-inner .ui-icon-arrow-r, .ui-btn-icon-right .ui-btn-inner .ui-icon-arrow-r{ background:url(".$_REQUEST['arrow_list'].") no-repeat 50%;}";
                $color_config['arrow_up']= $_REQUEST['arrow_up'];
                $color_config['arrow_down']= $_REQUEST['arrow_down'];
                $color_config['arrow_right']= $_REQUEST['arrow_right'];
                $color_config['arrow_left']= $_REQUEST['arrow_left'];
                $color_config['arrow_list']= $_REQUEST['arrow_list'];
                */

                $this->Template->save($color_config);
             $this->redirect('/themes/mobile_css_config/'.$theme_info['Template']['name']);
         }
     }

    /**
     *函数color_change 保存定制颜色数据.
     */
    public function color_change($sele, $attr, $valu, $valu2 = '')
    {
        if ($valu == '') {
            return '';
        }
        if ($attr == 'text-shadow') {
            //return $sele."{".$attr.":".$valu."0 1px 0!important}";
            //$tmp = $sele."{".$attr.":"."none!important}";
            $tmp = $sele.'{'.$attr.':'.$valu.'!important}';

            return $tmp;
        } elseif ($valu2 == '') {
            return $sele.'{'.$attr.':'.$valu.'!important}';
        } else {
            if ($attr == 'color') {
                return $sele.'{'.$attr.':'.$valu.'!important}';
            } elseif ($attr == 'background') {
                $tmp = $sele.'{'.$attr.':'.'-webkit-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                $tmp .= $sele.'{'.$attr.':'.'-moz-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                $tmp .= $sele.'{'.$attr.':'.'-ms-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                $tmp .= $sele.'{'.$attr.':'.'-o-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                $tmp .= $sele.'{'.$attr.':'.'linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';

                return $tmp;
            } elseif ($attr == 'border-color') {
                return $sele.'{'.$attr.':'.$valu.'!important}';
            }
        }
    }

    public function test()
    {
        //$this->layout=null;

        $template_list = $this->Template->find('first', array('conditions' => array('Template.id' => 1)));
        if (!empty($template_list['Template'])) {
            $this->navigations[] = array('name' => $this->ld['template'].' - '.$template_list['Template']['description'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        }
        if (isset($template_list['Template']['mobile_css']) && $template_list['Template']['mobile_css'] != '') {
            $template_list['Template']['mobile_css'] = json_decode($template_list['Template']['mobile_css'], true);
        }
        $this->set('template_list', $template_list);
    }
}
