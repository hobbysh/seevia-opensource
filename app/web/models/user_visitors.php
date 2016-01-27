<?php

/**
 * 用户粉丝模型.
 */
class UserVisitors extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    public $name = 'UserVisitors';
    /*查询最近访客的id*/
    public function find_visitors_byuserid($user_id)
    {
        $visitors = $this->find('list', array('conditions' => array('user_id' => $user_id), 'fields' => array('UserVisitors.visitor_id'), 'limit' => 24, 'order' => 'UserVisitors.modified desc'));

        return $visitors;
    }
    public function AbsoluteUrl()
    {
        global $HTTP_SERVER_VARS;
        $HTTPS = @$HTTP_SERVER_VARS['HTTPS'];
        $HTTP_HOST = $HTTP_SERVER_VARS['HTTP_HOST'];
        $SCRIPT_URL = @$HTTP_SERVER_VARS['SCRIPT_URL'];
        $PATH_INFO = @$HTTP_SERVER_VARS['PATH_INFO'];
        $REQUEST_URI = $HTTP_SERVER_VARS['REQUEST_URI'];
        $SCRIPT_NAME = $HTTP_SERVER_VARS['SCRIPT_NAME'];
        $QUERY_STRING = $HTTP_SERVER_VARS['QUERY_STRING'];

        $HTTPS = @$HTTP_SERVER_VARS['HTTPS'];
        $HTTP_HOST = $_SERVER['HTTP_HOST'];
        $SCRIPT_URL = $_SERVER['REQUEST_URI'];
        $PATH_INFO = $_SERVER['PATH_INFO'];
        $REQUEST_URI = $_SERVER['REQUEST_URI'];
        $SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
        $QUERY_STRING = $_SERVER['HTTP_HOST'];

        if (get_magic_quotes_gpc() == 1) {
            $QUERY_STRING = stripslashes($QUERY_STRING);
        }
        if ($QUERY_STRING != '') {
            $QUERY_STRING = '?'.$QUERY_STRING;
        }
        $uri_http = (((strtolower($HTTPS) == 'off') or ($HTTPS == 0)) ? 'http' : 'https').'://'.$HTTP_HOST;
        $url = '';
        if (isset($SCRIPT_URL)) {
            $url = $SCRIPT_URL;
        } elseif (isset($PATH_INFO)) {
            $url = $PATH_INFO;
        } elseif (isset($REQUEST_URI)) {
            $url = $REQUEST_URI;
        } elseif (isset($SCRIPT_NAME)) {
            $url = $SCRIPT_NAME;
        }
        if (empty($url)) {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = $uri_http.$url;
        }
       // $url=$_SERVER['HTTP_REFERER'];
        return $url;
    }
}
