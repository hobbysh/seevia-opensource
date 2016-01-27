<?php
	
/*****************************************************************************
 * SEEVIA 应用控制器
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id: app_controller.php 1372 2016-01-14 05:24:38Z zhaoyincheng $
*****************************************************************************/

class AppController extends Controller {
	var $uses ='';
	var $_LANG=array();
	var $err=array();
	var $installer_lang="";
	var $installer_locale="chi";
	/**
	*前过滤器
	*/
	function beforeFilter(){
		//当前域名
		$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
		$this->server_host = "http://".$host;
		$this->set('server_host',$this->server_host);
		if (!defined('IN_ECS')){
			define('IN_ECS', true);
		}
		require(dirname(__FILE__) . '/includes/init.php');
		
		/* 初始化语言变量 */
		$installer_lang = isset($_REQUEST['lang']) ? trim($_REQUEST['lang']) : 'zh_cn';
		if ($installer_lang != 'zh_cn' && $installer_lang != 'zh_tw' && $installer_lang != 'en_us')
		{
			$installer_lang = 'zh_cn';
		}
		$this->installer_lang=$installer_lang;
		
		/* 加载安装程序所使用的语言包 */
		$installer_lang_package_path = ROOT_PATH . 'tools/languages/' . $installer_lang . '.php';
		if (file_exists($installer_lang_package_path))
		{
			include_once($installer_lang_package_path);
			$this->_LANG=$_LANG;
			$this->set('lang', $this->_LANG);
		}else{
			die('Can\'t find language package!');
		}
		
		$installer_locale=isset($_LANG['locale'])?$_LANG['locale']:'chi';
		$this->installer_locale=$installer_locale;
		
		/* 初始化流程控制变量 */
		if (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/data/install.lock'))
		{
			array_push($this->err,$this->_LANG['has_locked_installer']);

			if (isset($_REQUEST['IS_AJAX_REQUEST']) && $_REQUEST['IS_AJAX_REQUEST'] === 'yes'){
		//		die(implode(',', $err->get_all()));
			}
			$this->set('err_msg', $this->err);
		//	$this->redirect("/errors");
		}
	}
}
?>