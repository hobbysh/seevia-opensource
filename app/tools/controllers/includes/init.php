<?php
/* �������д��� */
@ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

/* ������к��ļ�������ص�״̬��Ϣ */
clearstatcache();

/* ����վ��� */
define('ROOT_PATH', str_replace('tools/controllers/includes/init.php', '', str_replace('\\', '/', __FILE__)));

if (isset($_SERVER['PHP_SELF'])){
    define('PHP_SELF', $_SERVER['PHP_SELF']);
}else{
    define('PHP_SELF', $_SERVER['SCRIPT_NAME']);
}
/* ����汾�ı��� */
define('EC_CHARSET','utf-8');
define('EC_DB_CHARSET','utf8');
require(ROOT_PATH . 'tools/controllers/includes/lib_base.php');
require(ROOT_PATH . 'tools/controllers/includes/lib_common.php');
require(ROOT_PATH . 'tools/controllers/includes/lib_time.php');
require(ROOT_PATH . 'tools/controllers/includes/lib_installer.php');
/* ����HTTPͷ������֤�����ʶ��UTF8���� */
header('Content-type: text/html; charset='.EC_CHARSET);
@set_time_limit(360);
?>