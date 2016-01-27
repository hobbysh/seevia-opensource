<?php

class installsController extends AppController{
 	var $uses ="";

    function index($page=1){
		$flag=false;
		$lock=true;
		$this->set('flag', $flag);
		$path = WWW_ROOT. '/data/install.lock';
		if(!file_exists($path)){
			$lock=false;
		}
		$agreement_path = WWW_ROOT . '/data/tools/agreement.txt';
		if(file_exists($agreement_path)){
			$agreement=fopen($agreement_path, "r");
		   flock($agreement, LOCK_EX);
		   $content = fread($agreement, filesize($agreement_path));
		   flock($agreement, LOCK_UN);
			$this->set('agreement', $content);
			fclose ($agreement);
		}
		$this->set('lock', $lock);
		include_once(ROOT_PATH . 'tools/controllers/includes/lib_env_checker.php');
	    include_once(ROOT_PATH . 'tools/controllers/includes/checking_dirs.php');
	    $dir_checking = check_dirs_priv($checking_dirs,$this->_LANG);
	    $templates_root = array(
	        'temp' => dirname(ROOT_PATH).'/app/web/views/themed');
	    $this->set('templates_root',$templates_root);
	    $template_checking = check_templates_priv($templates_root);
	    $rename_priv = check_rename_priv();
	    $disabled = '""';
	    if ($dir_checking['result'] === 'ERROR'
	            || !empty($template_checking)
	            || !empty($rename_priv)
	            || !function_exists('mysql_connect'))
	    {
	        $disabled = 'disabled="true"';
	    }
	    $has_unwritable_tpl = 'yes';
	    if (empty($template_checking)){
	        $template_checking = $this->_LANG['all_are_writable'];
	        $has_unwritable_tpl = 'no';
	    }
	    $ui = (!empty($_POST['user_interface']))?$_POST['user_interface'] : "seevia";
	    $ucapi = (!empty($_POST['ucapi']))?$_POST['ucapi'] : "seevia";
	    $ucfounderpw = (!empty($_POST['ucfounderpw']))?$_POST['ucfounderpw'] : "seevia";
	    $this->set('ucapi', $ucapi);
	    $this->set('ucfounderpw', $ucfounderpw);
	    $this->set('installer_lang', $this->installer_lang);
	    $this->set('system_info', get_system_info($this->_LANG));
		if(!empty(get_system_info($this->_LANG))){
			$flag=true;
			$this->set('flag', $flag);
		}
	    $this->set('dir_checking', $dir_checking['detail']);
	    $this->set('has_unwritable_tpl', $has_unwritable_tpl);
	    $this->set('template_checking', $template_checking);
	    $this->set('rename_priv', $rename_priv);
		if(isset($_REQUEST["is_ajax"])&&$_REQUEST["is_ajax"]==1){
			Configure::write('debug',0);
	    	$this->layout="ajax";
			$result['lock']=$lock;
			$result['system_info']=get_system_info($this->_LANG);
			$result['dir_checking']=$dir_checking['detail'];
			$result['template_checking']=$template_checking;
			$result['has_unwritable_tpl']=$has_unwritable_tpl;
			$result['disabled']=$disabled;
	    	die(json_encode($result));
		}
	    $this->set('disabled', $disabled);
	    $this->set('userinterface', $ui);
		$this->set("title_for_layout",'Seevia-O2O安装');
	}
	
	function welcome(){
		$ucapi = (!empty($_POST['ucapi']))?$_POST['ucapi'] : "seevia";
	    $ucfounderpw = (!empty($_POST['ucfounderpw']))?$_POST['ucfounderpw'] : "seevia";
		$this->set('ucapi', $ucapi);
	    $this->set('ucfounderpw', $ucfounderpw);
	    $this->set('installer_lang', $this->installer_lang);
	}
	
	function setting(){
		if (!has_supported_gd()){
	        $checked = 'checked="checked"';
	        $disabled = 'disabled="true"';
	    }else{
	        $checked = '';
	        $disabled = '';
	    }
	    $show_timezone = PHP_VERSION >= '5.1' ? 'yes' : 'no';
	    $ui = (!empty($_POST['user_interface']))?$_POST['user_interface'] : "seevia";
	    $ucapi = (!empty($_POST['ucapi']))?$_POST['ucapi'] : "seevia";
	    $ucfounderpw = (!empty($_POST['ucfounderpw']))?$_POST['ucfounderpw'] : "seevia";
	    $this->set('ucapi', $ucapi);
	    $this->set('ucfounderpw', $ucfounderpw);
	    $this->set('installer_lang', $this->installer_lang);
	    $this->set('checked', $checked);
	    $this->set('disabled', $disabled);
	    $this->set('show_timezone', $show_timezone);
	    $this->set('local_timezone', get_local_timezone());
	    $this->set('timezones', get_timezone_list($this->installer_lang));
	    $this->set('userinterface', $ui);
	}
	
	function get_db_list(){
		$db_host    = isset($_POST['db_host']) ? trim($_POST['db_host']) : '';
	    $db_port    = isset($_POST['db_port']) ? trim($_POST['db_port']) : '';
	    $db_user    = isset($_POST['db_user']) ? trim($_POST['db_user']) : '';
	    $db_pass    = isset($_POST['db_pass']) ? trim($_POST['db_pass']) : '';
	    include_once(ROOT_PATH . 'tools/controllers/includes/cls_json.php');
	    $json = new JSON();
	    $databases  = get_all_db($db_host, $db_port, $db_user, $db_pass);
	    if (sizeof($databases)==0){
	    	$result = array('msg'=> 'db_erro');
	        echo $json->encode($result);
	    }else{
	        $result = array('msg'=> 'OK', 'list'=>implode(',', $databases));
	        echo $json->encode($result);
	    }
	    exit;
	}
	
	function create_config_file(){
		$db_host    = isset($_POST['db_host'])      ?   trim($_POST['db_host']) : '';
	    $db_port    = isset($_POST['db_port'])      ?   trim($_POST['db_port']) : '';
	    $db_user    = isset($_POST['db_user'])      ?   trim($_POST['db_user']) : '';
	    $db_pass    = isset($_POST['db_pass'])      ?   trim($_POST['db_pass']) : '';
	    $db_name    = isset($_POST['db_name'])      ?   trim($_POST['db_name']) : '';
	    $timezone   = isset($_POST['timezone'])     ?   trim($_POST['timezone']) : 'Asia/Shanghai';
	    $result = create_config($db_host, $db_port, $db_user, $db_pass, $db_name,$timezone);
	    if ($result === false){
	        echo "create_config_file erro";
	    }else{
	        echo 'OK';
	    }
	    exit;
	}
	
	function create_database(){
		$db_host    = isset($_POST['db_host'])      ?   trim($_POST['db_host']) : '';
	    $db_port    = isset($_POST['db_port'])      ?   trim($_POST['db_port']) : '';
	    $db_user    = isset($_POST['db_user'])      ?   trim($_POST['db_user']) : '';
	    $db_pass    = isset($_POST['db_pass'])      ?   trim($_POST['db_pass']) : '';
	    $db_name    = isset($_POST['db_name'])      ?   trim($_POST['db_name']) : '';
	    $result = create_data($db_host, $db_port, $db_user, $db_pass, $db_name);
	    if ($result === false){
	        echo "create_database erro";
	    }else{
	        echo 'OK';
	    }
	    exit;
	}
	
	function install_base_data(){
        $sql_files = array(
	        	WWW_ROOT . '/data/tools/o2o_CreateTable.sql',
	        	WWW_ROOT . '/data/tools/o2o_DefaultData.sql'
		    );
		if(constant("Product")=="AllInOne"){
			$sql_files[] = WWW_ROOT . '/data/tools/o2o-allinone.sql';
	    }
        $sql_files[] = WWW_ROOT . '/data/tools/o2o_dictionaries.sql';
	    $db_host    = isset($_POST['db_host'])      ?   trim($_POST['db_host']) : '';
	    $db_user    = isset($_POST['db_user'])      ?   trim($_POST['db_user']) : '';
	    $db_pass    = isset($_POST['db_pass'])      ?   trim($_POST['db_pass']) : '';
	    $db_name    = isset($_POST['db_name'])      ?   trim($_POST['db_name']) : '';
	    $result = install_data($db_host, $db_user, $db_pass, $db_name,$sql_files);
	    if ($result === false)
	    {
	        echo "install_base_data erro";
	    }else{
	        echo 'OK';
	    }
	    exit;
	}
	
	function create_admin_passport(){
		$admin_name         = isset($_POST['admin_name'])       ? json_str_iconv(trim($_POST['admin_name'])) : '';
	    $admin_password     = isset($_POST['admin_password'])   ? trim($_POST['admin_password']) : '';
	    $admin_password2    = isset($_POST['admin_password2'])  ? trim($_POST['admin_password2']) : '';
	    $db_host    = isset($_POST['db_host'])      ?   trim($_POST['db_host']) : '';
	    $db_user    = isset($_POST['db_user'])      ?   trim($_POST['db_user']) : '';
	    $db_pass    = isset($_POST['db_pass'])      ?   trim($_POST['db_pass']) : '';
	    $db_name    = isset($_POST['db_name'])      ?   trim($_POST['db_name']) : '';
	    $result = create_admin_passport($db_host, $db_user, $db_pass, $db_name,$admin_name, $admin_password,$admin_password2);
	    if ($result === false)
	    {
	        echo "create_admin_passport erro";
	    }else{
	        echo 'OK';
	    }
	    exit;
	}
	
	function do_others(){
		$db_host    = isset($_POST['db_host'])      ?   trim($_POST['db_host']) : '';
	    $db_user    = isset($_POST['db_user'])      ?   trim($_POST['db_user']) : '';
	    $db_pass    = isset($_POST['db_pass'])      ?   trim($_POST['db_pass']) : '';
	    $db_name    = isset($_POST['db_name'])      ?   trim($_POST['db_name']) : '';
		$system_lang = isset($_POST['system_lang'])     ? $_POST['system_lang'] : 'zh_cn';
	    $captcha = isset($_POST['disable_captcha'])     ? intval($_POST['disable_captcha']) : '0';
	    $goods_types = isset($_POST['goods_types'])     ? $_POST['goods_types'] : array();
	    $install_demo = isset($_POST['install_demo'])   ? $_POST['install_demo'] : 0;
	    $install_lang = isset($_POST['install_lang'])   ? $_POST['install_lang'] : 0;
	    $integrate = isset($_POST['userinterface'])   ? trim($_POST['userinterface']) : 'ecshop';
	    $result = do_others($db_host, $db_user, $db_pass, $db_name,$system_lang, $captcha, $goods_types, $install_demo,$install_lang, $integrate);
	    if ($result === false)
	    {
	        echo "do_others erro";
	    }else{
	        echo 'OK';
	    }
	    exit;
	}
}
?>