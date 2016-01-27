<?php
/**
 * ECSHOP 安装程序 之 模型
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: lib_installer.php 1384 2016-01-14 08:18:32Z zhaoyincheng $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/**
 * 获得GD的版本号
 *
 * @access  public
 * @return  string     返回版本号，可能的值为0，1，2
 */
function get_gd_version()
{
    include_once(ROOT_PATH . 'tools/controllers/includes/cls_image.php');
    return cls_image::gd_version();
}

/**
 * 是否支持GD
 *
 * @access  public
 * @return  boolean     成功返回true，失败返回false
 */
function has_supported_gd()
{
    return get_gd_version() === 0 ? false : true;
}

/**
 * 检测服务器上是否存在指定的文件类型
 *
 * @access  public
 * @param   array     $file_types        文件路径数组，形如array('dwt'=>'', 'lbi'=>'', 'dat'=>'')
 * @return  string    全部可写返回空串，否则返回以逗号分隔的文件类型组成的消息串
 */
function file_types_exists($file_types)
{
    $msg = '';
    foreach ($file_types as $file_type => $file_path)
    {
        if (!file_exists($file_path)){
            $msg .= $_LANG['cannt_support_' . $file_type] . ', ';
        }
    }
    $msg = preg_replace("/,\s*$/", '', $msg);
    return $msg;
}

/**
 * 获得系统的信息
 *
 * @access  public
 * @return  array     系统各项信息组成的数组
 */
function get_system_info($_LANG=array())
{
    $system_info = array();
    /* 检查系统基本参数 */
    $system_info[] = array($_LANG['php_os'], PHP_OS);
    $system_info[] = array($_LANG['php_ver'], PHP_VERSION);

    /* 检查MYSQL支持情况 */
    $mysql_enabled = function_exists('mysql_connect') ? $_LANG['support'] : $_LANG['not_support'];
    $system_info[] = array($_LANG['does_support_mysql'], $mysql_enabled);

    /* 检查图片处理函数库 */
    $gd_ver = get_gd_version();
    $gd_ver = empty($gd_ver) ? $_LANG['not_support'] : $gd_ver;
    if ($gd_ver > 0)
    {
        if (PHP_VERSION >= '4.3' && function_exists('gd_info')){
            $gd_info = gd_info();
            $jpeg_enabled = ($gd_info['JPEG Support']        === true) ? $_LANG['support'] : $_LANG['not_support'];
            $gif_enabled  = ($gd_info['GIF Create Support'] === true) ? $_LANG['support'] : $_LANG['not_support'];
            $png_enabled  = ($gd_info['PNG Support']        === true) ? $_LANG['support'] : $_LANG['not_support'];
        }else{
            if (function_exists('imagetypes')){
                $jpeg_enabled = ((imagetypes() & IMG_JPG) > 0) ? $_LANG['support'] : $_LANG['not_support'];
                $gif_enabled  = ((imagetypes() & IMG_GIF) > 0) ? $_LANG['support'] : $_LANG['not_support'];
                $png_enabled  = ((imagetypes() & IMG_PNG) > 0) ? $_LANG['support'] : $_LANG['not_support'];
            }else{
                $jpeg_enabled = $_LANG['not_support'];
                $gif_enabled  = $_LANG['not_support'];
                $png_enabled  = $_LANG['not_support'];
            }
        }
    }else{
        $jpeg_enabled = $_LANG['not_support'];
        $gif_enabled  = $_LANG['not_support'];
        $png_enabled  = $_LANG['not_support'];
    }
    $system_info[] = array($_LANG['gd_version'], $gd_ver);
    $system_info[] = array($_LANG['jpeg'], $jpeg_enabled);
    $system_info[] = array($_LANG['gif'],  $gif_enabled);
    $system_info[] = array($_LANG['png'],  $png_enabled);

    /* 检查系统是否支持以dwt,lib,dat为扩展名的文件 */
//    $file_types = array(
//            'dwt' => ROOT_PATH . 'themes/default/index.dwt',
//            'lbi' => ROOT_PATH . 'themes/default/library/member.lbi',
//            'dat' => ROOT_PATH . 'includes/codetable/ipdata.dat'
//        );
//    $exists_info = file_types_exists($file_types);
//    $exists_info = empty($exists_info) ? $_LANG['support_dld'] : $exists_info;
//    $system_info[] = array($_LANG['does_support_dld'], $exists_info);

    /* 服务器是否安全模式开启 */
    $safe_mode = ini_get('safe_mode') == '1' ? $_LANG['safe_mode_on'] : $_LANG['safe_mode_off'];
    $system_info[] = array($_LANG['safe_mode'], $safe_mode);
    return $system_info;
}

/**
 * 获得数据库列表
 *
 * @access  public
 * @param   string      $db_host        主机
 * @param   string      $db_port        端口号
 * @param   string      $db_user        用户名
 * @param   string      $db_pass        密码
 * @return  mixed       成功返回数据库列表组成的数组，失败返回空
 */
function get_all_db($db_host, $db_port, $db_user, $db_pass)
{
    $databases = array();
    $filter_dbs = array('information_schema', 'mysql');
    $db_host = construct_db_host($db_host, $db_port);
    $conn = @mysql_connect($db_host, $db_user, $db_pass);
    if ($conn === false){
        return $databases;
    }
    keep_right_conn($conn);
    $result = mysql_query('SHOW DATABASES', $conn);
    if ($result !== false){
        while (($row = mysql_fetch_assoc($result)) !== false)
        {
            if (in_array($row['Database'], $filter_dbs)){
                continue;
            }
            $databases[] = $row['Database'];
        }
    }
    @mysql_close($conn);
    return $databases;
}

/**
 * 获得时区列表，如有重复值，只保留第一个
 *
 * @access  public
 * @return  array
 */
function get_timezone_list($lang)
{
    if (file_exists(WWW_ROOT . '/data/tools/inc_timezones_' . $lang . '.php')){
        include_once(WWW_ROOT . '/data/tools/inc_timezones_' . $lang . '.php');
    }else{
        include_once(WWW_ROOT . '/data/tools/inc_timezones_zh_cn.php');
    }
    return array_unique($timezones);
}

/**
 * 获得服务器所在时区
 *
 * @access  public
 * @return  string     返回时区串，形如Asia/Shanghai
 */
function get_local_timezone()
{
    if (PHP_VERSION >= '5.1'){
        $local_timezone = date_default_timezone_get();
    }else{
         $local_timezone = '';
    }
    return $local_timezone;
}

/**
 * 创建指定名字的数据库
 *
 * @access  public
 * @param   string      $db_host        主机
 * @param   string      $db_port        端口号
 * @param   string      $db_user        用户名
 * @param   string      $db_pass        密码
 * @param   string      $db_name        数据库名
 * @return  boolean     成功返回true，失败返回false
 */
function create_data($db_host, $db_port, $db_user, $db_pass, $db_name)
{
    $db_host = construct_db_host($db_host, $db_port);
    $conn = @mysql_connect($db_host, $db_user, $db_pass);
    if ($conn === false){
        return false;
    }
    $mysql_version = mysql_get_server_info($conn);
    keep_right_conn($conn, $mysql_version);
    if (mysql_select_db($db_name, $conn) === false){
        $sql = $mysql_version >= '4.1' ? "CREATE DATABASE $db_name DEFAULT CHARACTER SET " . EC_DB_CHARSET : "CREATE DATABASE $db_name";
        if (mysql_query($sql, $conn) === false){
            return false;
        }
    }else{
       	$sql2 = "DROP DATABASE $db_name";
	    if (mysql_query($sql2, $conn) === false){
	    	return false;
	    }
        $sql = $mysql_version >= '4.1' ? "CREATE DATABASE $db_name DEFAULT CHARACTER SET " . EC_DB_CHARSET : "CREATE DATABASE $db_name";
        if (mysql_query($sql, $conn) === false){
            return false;
        }
    }
    @mysql_close($conn);
    return true;
}

/**
 * 保证进行正确的数据库连接（如字符集设置）
 *
 * @access  public
 * @param   string      $conn                      数据库连接
 * @param   string      $mysql_version        mysql版本号
 * @return  void
 */
function keep_right_conn($conn, $mysql_version='')
{
    if ($mysql_version === ''){
        $mysql_version = mysql_get_server_info($conn);
    }
	if ($mysql_version >= '4.1'){
        mysql_query('SET character_set_connection=' . EC_DB_CHARSET . ', character_set_results=' . EC_DB_CHARSET . ', character_set_client=binary', $conn);
        if ($mysql_version > '5.0.1')
        {
            mysql_query("SET sql_mode=''", $conn);
        }
    }
}

/**
 * 创建配置文件
 *
 * @access  public
 * @param   string      $db_host        主机
 * @param   string      $db_port        端口号
 * @param   string      $db_user        用户名
 * @param   string      $db_pass        密码
 * @param   string      $db_name        数据库名
 * @param   string      $timezone       时区
 * @return  boolean     成功返回true，失败返回false
 */
function create_config($db_host, $db_port, $db_user, $db_pass, $db_name,$timezone)
{
	$db_host2 =construct_db_host($db_host, $db_port);
    $content2 = '<?' ."php\n";
    $content2 .= "// database host\n";
    $content2 .= "\$db_host   = \"$db_host2\";\n\n";
    $content2 .= "// database name\n";
    $content2 .= "\$db_name   = \"$db_name\";\n\n";
    $content2 .= "// database username\n";
    $content2 .= "\$db_user   = \"$db_user\";\n\n";
    $content2 .= "// database password\n";
    $content2 .= "\$db_pass   = \"$db_pass\";\n\n";
    $content2 .= "// table prefix\n";
    $content2 .= "\$prefix    = \"\";\n\n";
    $content2 .= "\$timezone    = \"$timezone\";\n\n";
    $content2 .= "\$cookie_path    = \"/\";\n\n";
    $content2 .= "\$cookie_domain    = \"\";\n\n";
    $content2 .= "\$session = \"1440\";\n\n";
    $content2 .= "define('EC_CHARSET','".EC_CHARSET."');\n\n";
    $content2 .= "define('ADMIN_PATH','admin');\n\n";
    $content2 .= "define('AUTH_KEY', 'this is a key');\n\n";
    $content2 .= "define('OLD_AUTH_KEY', '');\n\n";
    $content2 .= "define('API_TIME', '');\n\n";
    $content2 .= '?>';
    $fp2 = @fopen(WWW_ROOT . '/data/tools/config.php', 'wb+');
    if (!$fp2){
        return false;
    }
    if (!@fwrite($fp2, trim($content2))){
        return false;
    }
    @fclose($fp2);
	$content = '<?' ."php\n";
    $content .= "	define('MYSQL_HOST',\"$db_host\");\n";
    $content .= "	define('MYSQL_DB',\"$db_name\");\n";
    $content .= "	define('MYSQL_LOGIN',\"$db_user\");\n";
    $content .= "	define('MYSQL_PASSWORD',\"$db_pass\");\n";
    $content .= '?>';
    $db_path=dirname(ROOT_PATH) . '/data/database.php';
    $fp = @fopen($db_path, 'wb+');
    if (!$fp){
        return false;
    }

    if (!@fwrite($fp, trim($content))){
        return false;
    }
    @fclose($fp);
    return true;
}

/**
 * 把host、port重组成指定的串
 *
 * @access  public
 * @param   string      $db_host        主机
 * @param   string      $db_port        端口号
 * @return  string      host、port重组后的串，形如host:port
 */
function construct_db_host($db_host, $db_port)
{
    return $db_host . ':' . $db_port;
}

/**
 * 安装数据
 *
 * @access  public
 * @param   array         $sql_files        SQL文件路径组成的数组
 * @return  boolean       成功返回true，失败返回false
 */
function install_data($db_host, $db_user, $db_pass,$db_name,$sql_files)
{
    include_once(ROOT_PATH . 'tools/controllers/includes/cls_mysql.php');
    include_once(ROOT_PATH . 'tools/controllers/includes/cls_sql_executor.php');
    $db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
    $se = new sql_executor($db, EC_DB_CHARSET,'','','a.txt');
    $result = $se->run_all($sql_files);
//    print_r($result);
	if(!empty($result)&&$result === false){
		return false;
	}
    return true;
}

/**
 * 创建管理员帐号
 *
 * @access  public
 * @param   string      $admin_name
 * @param   string      $admin_password
 * @param   string      $admin_password2
 * @param   string      $admin_email
 * @return  boolean     成功返回true，失败返回false
 */
function create_admin_passport($db_host, $db_user, $db_pass, $db_name,$admin_name, $admin_password, $admin_password2)
{
    include_once(ROOT_PATH . 'tools/controllers/includes/cls_mysql.php');
    include_once(ROOT_PATH . 'tools/controllers/includes/lib_common.php');
    $db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
    $sql = "INSERT INTO svsys_operators".
                "(name,password, created, actions,status)".
            "VALUES ".
                "('$admin_name','".md5($admin_password). "', " .gmtime(). ", 'all','1')";
    $result = $db->query($sql,  'SILENT');
    if(!empty($result)&&$result === false){
    	return false;
    }
    return true;
}

/**
 * 其它设置
 *
 * @access  public
 * @param   string      $system_lang            系统语言
 * @param   string      $disable_captcha        是否开启验证码
 * @param   array       $goods_types            预选商品类型
 * @param   string      $install_demo           是否安装测试数据
 * @param   string      $integrate_code         用户接口
 * @return  boolean     成功返回true，失败返回false
 */
function do_others($db_host, $db_user, $db_pass,$db_name,$system_lang, $captcha, $goods_types, $install_demo,$install_lang, $integrate_code)
{
    /* 安装测试数据 */
    if (intval($install_demo)){
        if (file_exists(WWW_ROOT . '/data/tools/o2o_DemoData.sql')){
            $sql_files = array(WWW_ROOT . '/data/tools/o2o_DemoData.sql');
        }
        $result = install_data($db_host, $db_user, $db_pass,$db_name,$sql_files);
        if(!empty($result)&&$result === false){
        	return false;
        }
    }
    /* 修改多语言 */
    if (intval($install_lang))
    {
        if (file_exists(WWW_ROOT . '/data/tools/lang.sql')){
            $sql_files = array(WWW_ROOT .'/data/tools/lang.sql');
        }
        $result = install_data($db_host, $db_user, $db_pass,$db_name,$sql_files);
        if(!empty($result)&&$result === false){
        	return false;
        }
    }
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
    return true;
}

/**
 * 取得当前的域名
 *
 * @access  public
 *
 * @return  string      当前的域名
 */
function get_domain()
{
    /* 协议 */
    $protocol = http();
    /* 域名或IP地址 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])){
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }elseif (isset($_SERVER['HTTP_HOST'])){
        $host = $_SERVER['HTTP_HOST'];
    }else{
        /* 端口 */
        if (isset($_SERVER['SERVER_PORT'])){
            $port = ':' . $_SERVER['SERVER_PORT'];
            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol)){
                $port = '';
            }
        }else{
            $port = '';
        }
		if (isset($_SERVER['SERVER_NAME'])){
            $host = $_SERVER['SERVER_NAME'] . $port;
        }elseif (isset($_SERVER['SERVER_ADDR'])){
            $host = $_SERVER['SERVER_ADDR'] . $port;
        }
    }
    return $protocol . $host;
}

/**
 * 获得 ECSHOP 当前环境的 URL 地址
 *
 * @access  public
 *
 * @return  void
 */
function url()
{
    $PHP_SELF = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $ecserver = 'http://'.$_SERVER['HTTP_HOST'].($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '');
    $default_appurl = $ecserver.substr($PHP_SELF, 0, strpos($PHP_SELF, 'tools/') - 1);
    return $default_appurl;
}

/**
 * 获得 ECSHOP 当前环境的 HTTP 协议方式
 *
 * @access  public
 *
 * @return  void
 */
function http()
{
    return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
}

function insertconfig($s, $find, $replace)
{
    if(preg_match($find, $s)){
        $s = preg_replace($find, $replace, $s);
    }else{
        // 插入到最后一行
        $s .= "\r\n".$replace;
    }
    return $s;
}

function getgpc($k, $var='G')
{
    switch($var)
    {
        case 'G': $var = &$_GET; break;
        case 'P': $var = &$_POST; break;
        case 'C': $var = &$_COOKIE; break;
        case 'R': $var = &$_REQUEST; break;
    }

    return isset($var[$k]) ? $var[$k] : '';
}

function var_to_hidden($k, $v)
{
    return "<input type=\"hidden\" name=\"$k\" value=\"$v\" />";
}

function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE)
{
    $return = '';
    $matches = parse_url($url);
    $host = $matches['host'];
    $path = $matches['path'] ? $matches['path'].'?'.$matches['query'].($matches['fragment'] ? '#'.$matches['fragment'] : '') : '/';
    $port = !empty($matches['port']) ? $matches['port'] : 80;
    if($post){
        $out = "POST $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
        //$out .= "Referer: $boardurl\r\n";
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host\r\n";
        $out .= 'Content-Length: '.strlen($post)."\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cache-Control: no-cache\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
        $out .= $post;
    }else{
        $out = "GET $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
        //$out .= "Referer: $boardurl\r\n";
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
    }
    $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
    if(!$fp){
        return '';//note $errstr : $errno \r\n
    }else{
        stream_set_blocking($fp, $block);
        stream_set_timeout($fp, $timeout);
        @fwrite($fp, $out);
        $status = stream_get_meta_data($fp);
        if(!$status['timed_out']){
            while (!feof($fp))
            {
                if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")){
                    break;
                }
            }
            $stop = false;
            while(!feof($fp) && !$stop)
            {
                $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                $return .= $data;
                if($limit){
                    $limit -= strlen($data);
                    $stop = $limit <= 0;
                }
            }
        }
        @fclose($fp);
        return $return;
    }
}

function save_uc_config($config)
{
    $success = false;
    list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset, $ucapi, $ucip) = explode('|', $config);
/*
    $content = '<?' ."php\n";
    $content .= "define('UC_CONNECT', 'mysql');\n\n";
    $content .= "define('UC_DBHOST', '$ucdbhost');\n\n";
    $content .= "define('UC_DBUSER', '$ucdbuser');\n\n";
    $content .= "define('UC_DBPW', '$ucdbpw');\n\n";
    $content .= "define('UC_DBNAME', '$ucdbname');\n\n";
    $content .= "define('UC_DBCHARSET', '$ucdbcharset');\n\n";
    $content .= "define('UC_DBTABLEPRE', '`$ucdbname`.$uctablepre');\n\n";
    $content .= "define('UC_DBCONNECT', '0');\n\n";
    $content .= "define('UC_KEY', '$appauthkey');\n\n";
    $content .= "define('UC_API', '$ucapi');\n\n";
    $content .= "define('UC_CHARSET', '$uccharset');\n\n";
    $content .= "define('UC_IP', '$ucip');\n\n";
    $content .= "define('UC_APPID', '$appid');\n\n";
    $content .= "define('UC_PPP', '20');\n\n";
    $content .= '?>';
*/
    $cfg = array(
                    'uc_id' => $appid,
                    'uc_key' => $appauthkey,
                    'uc_url' => $ucapi,
                    'uc_ip' => $ucip,
                    'uc_connect' => 'mysql',
                    'uc_charset' => $uccharset,
                    'db_host' => $ucdbhost,
                    'db_user' => $ucdbuser,
                    'db_name' => $ucdbname,
                    'db_pass' => $ucdbpw,
                    'db_pre' => $uctablepre,
                    'db_charset' => $ucdbcharset,
                );
    $content = "<?php\r\n";
    $content .= "\$cfg = " . var_export($cfg, true) . ";\r\n";
    $content .= "?>";
    $fp = @fopen(ROOT_PATH . 'data/config_temp.php', 'wb+');
    if (!$fp){
        $result['error'] = 1;
        $result['message'] = $_LANG['ucenter_datadir_access'];
        die($GLOBALS['json']->encode($result));
    }
    if (!@fwrite($fp, $content)){
        $result['error'] = 1;
        $result['message'] = $_LANG['ucenter_tmp_config_error'];
        die($GLOBALS['json']->encode($result));
    }
    @fclose($fp);
    return true;
}
?>