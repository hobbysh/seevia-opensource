<?php
/**
 * $seevia$
 * $Id: core.php 1367 2016-01-08 08:36:20Z zhaoyincheng $
*/
	include_once(dirname(ROOT)."/data/config.php");
	if(isset($_GET['debug'])){
		Configure::write('debug',$_GET['debug']);
	}else{
		Configure::write('debug', 0);
	}
	
	Configure::write('debug', 1);
	Configure::write('App.encoding', 'UTF-8');
	Configure::write('Cache.disable', true);
	define('LOG_ERROR', 2);
	Configure::write('Security.salt', 'a1b9f79d12e5d1f3db8393165155b839');
	Configure::write('Session.save', 'php');
	Configure::write('Session.cookie', 'IOCOADMIN');
	Configure::write('Session.timeout', '600');
	Configure::write('Session.start', true);
	Configure::write('Session.checkAgent', true);
	Configure::write('Security.level', 'medium');

	Configure::write('themes_host', '/test');
	
	
		define('DateTime', date('Y-m-d H:i:s'));
		define('Today', date('Y-m-d'));
		define('StartTime',"00:00:00");
		define('EndTime',"23:59:59");
		
?>