<?php

/*****************************************************************************
 * svsys 管理员日志
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
class OperatorLog extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';
    public $name = 'OperatorLog';

     //重载log
    public function log($info, $adminid)
    {
        $conf = new Configure();
        $cookie = new CookieComponent();

        $cookie->key = $conf->read('Security.salt');

        $cookie_session = $cookie->read('session');
        $session = isset($cookie_session) && $cookie_session != '' ? $cookie_session : session_id();
        //echo $session;die;
        $remak = empty($_POST) ? '' : 'post|'.serialize($_POST);
        $remak .= empty($_GET) ? '' : 'get|'.serialize($_GET);
        $loginfo = $this->find('first', array('conditions' => array('OperatorLog.operator_id' => $adminid), 'order' => 'OperatorLog.created desc'));
//		$now=strtotime(date("Y-m-d H:i:s"));//转换成时间戳和最后修改时间相减除以60s得出分钟
//		$lasttime=strtotime($loginfo['OperatorLog']['created']);
//		$time_difference=(int)(($now-$lasttime)/60);//相差多少分钟
        if ($loginfo['OperatorLog']['session_id'] == $session) {
            //session相同,在日志内容中追加日志
            $logdata['OperatorLog']['id'] = $loginfo['OperatorLog']['id'];
            $logdata['OperatorLog']['info'] = $info.'<br>'.$loginfo['OperatorLog']['info'];
            $logdata['OperatorLog']['action_url'] = $this->AbsoluteUrl().'<br>'.$loginfo['OperatorLog']['action_url'];
            $logdata['OperatorLog']['remark'] = $remak.'<br>'.$loginfo['OperatorLog']['remark'];
            $this->save($logdata);
        } else {
            $OperatorLogs = array(
                'operator_id' => $adminid,
                'session_id' => $session,
                'ipaddress' => $this->real_ip(),
                'action_url' => $this->AbsoluteUrl(),
                'info' => $info,
                'type' => 1,
                'remark' => $remak,
            );
            $this->saveAll(array('OperatorLog' => $OperatorLogs));
        }
//		if($time_difference<=20){
//			//在20分钟以内,在日志内容中追加日志
//			$logdata['OperatorLog']['id']=$loginfo['OperatorLog']['id'];
//			$logdata['OperatorLog']['info']=$info.'<br>'.$loginfo['OperatorLog']['info'];
//			$logdata['OperatorLog']['action_url']=$this->AbsoluteUrl().'<br>'.$loginfo['OperatorLog']['action_url'];
//			$logdata['OperatorLog']['remark']=$remak.'<br>'.$loginfo['OperatorLog']['remark'];
//			$this->save($logdata);
//		}else{
//			//在20分钟以外，添加新日志
//			/* 增加记录post和get参数 */
//			$OperatorLogs = array(
//				"operator_id"=>$adminid,
//				"ipaddress"=>$this->real_ip(),
//				"action_url"=>$this->AbsoluteUrl(),
//				"info"=>$info,
//				"type"=>1,
//				"remark"=>$remak
//			);
//			$this->saveAll(array("OperatorLog"=>$OperatorLogs));
//		}
    }

    /**
     * 获得用户的真实IP地址.
     *
     * @return string
     */
    public function real_ip()
    {
        static $realip = null;
        if ($realip !== null) {
            return $realip;
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

        return $realip;
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
