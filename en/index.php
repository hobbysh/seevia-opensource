<?php
//	echo dirname($_SERVER['SCRIPT_FILENAME'])."/index.php";exit();
	if (!defined('LOCALE')) {
		define('LOCALE', 'eng');
	}
	/* index配置文件 */
	include(dirname(dirname($_SERVER['SCRIPT_FILENAME']))."/index.php");
?>