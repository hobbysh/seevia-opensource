<?php
class upgradesController extends AppController{
	
	public $name="upgrades";//控制器
	public $uses = array('Config','ConfigI18n');
	
	function index(){
		$this->set("title_for_layout",'Seevia-系统升级');
	}
	
	/*
		检查版本
	*/
	function checkextension(){
		$this->set("title_for_layout",'Seevia-检查版本');
		$user_agree=isset($_POST['user_agree'])?$_POST['user_agree']:'1';
		
		if($user_agree=='1'){
			$this->Config->set_locale($this->installer_locale);
			$version_config_data=$this->Config->find('first',array('conditions'=>array('Config.code'=>'version')));
			$version_config=isset($version_config_data['Config'])?$version_config_data['ConfigI18n']['value']:'';
			$this->set('version_config',$version_config);
		}else{
			$this->redirect("/upgrades/");
		}
	}
	
	function upgrade_action(){
		Configure::write('debug',1);
		$this->layout="ajax";
		
		$upgrade_result=array();
		$upgrade_result['flag']='0';
		$upgrade_result['message']='System Error';
		$web_version=isset($_POST['web_version'])?$_POST['web_version']:'';
		$upgrade_sql_file=array();
		switch($web_version){
			case "v0.7":
				if(!empty($this->getUpgradeFile('v0.7'))){
					$upgrade_sql_file[]=$this->getUpgradeFile('v0.7');
				}
        	}
        	if(!empty($upgrade_sql_file)){
        		$sql_result=$this->execSQL($upgrade_sql_file);
        		if($sql_result){
        			$upgrade_result['flag']='1';
				$upgrade_result['message']='升级成功';
				$this->Config->set_locale($this->installer_locale);
				$version_config_info=$this->Config->find('first',array('conditions'=>array('Config.code'=>'version')));
				if(!empty($version_config_info)){
					$version_config_id=$version_config_info['Config']['id'];
					$this->ConfigI18n->updateAll(array('ConfigI18n.value'=>"'".Version."'"),array('ConfigI18n.config_id'=>$version_config_id));
					
					//创建锁定文件
					$db_path=dirname(ROOT_PATH). '/data/install.lock';
					//echo $db_path;
					$fp = @fopen($db_path, 'wb+');
					if (!$fp){
						return false;
					}
					if (!@fwrite($fp, "install")){
						return false;
					}
					@fclose($fp);
				}
        		}else{
				$upgrade_result['message']='升级执行失败,请联系系统供应商协助处理。';
        		}
        	}else{
        		$upgrade_result['message']='升级执行失败,请下载最新的系统安装包重试。';
        	}
		die(json_encode($upgrade_result));
	}
	
	function execSQL($sql_file_path){
		$mysql_host=MYSQL_HOST;
    		$mysql_db=MYSQL_DB;
    		$mysql_login=MYSQL_LOGIN;
    		$mysql_password=MYSQL_PASSWORD;
    		App::import('Vendor', 'sqlclient', array('file' => 'DbManager.class.php'));
    		$dbcon=new DBManager($mysql_host,$mysql_login,$mysql_password,$mysql_db);
    		$sql_result=$dbcon->run_all($sql_file_path);
    		return $sql_result;
	}
	
	//输出脚本文件地址
	function getUpgradeFile($version_code){
		$system_code=Product;
		$sql_file=WWW_ROOT."data/tools/upgrade/".$system_code."/".$version_code.".sql";
		if(file_exists($sql_file)){
			return $sql_file;
		}
		return "";
	}
	
	/*
		检查文件
	*/
	function upgrade_check(){
		Configure::write('debug',0);
	    	$this->layout="ajax";
		$result=array();
		$result['flag']='1';
		$dbConfig = WWW_ROOT . 'data/database.php';
		$lockFile=WWW_ROOT.'data/install.lock';
		if(!file_exists($dbConfig)){
			$result['flag']='0';
		}
		if(file_exists($lockFile)){
			$result['flag']='0';
		}
		die(json_encode($result));
	}
}
?>