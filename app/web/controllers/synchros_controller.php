<?php

App::import('Vendor', 'Opauth', array('file' => 'Opauth.php'));
class SynchrosController extends AppController
{
    public $name = 'Synchros';
    public $helpers = array('Html');
    public $uses = array('User','SynchroUser','RegionI18n','UserApp','Template');
    public $components = array('RequestHandler','Cookie','Session','Captcha');
    public $userAppNames = array();

    /*
        获取API配置信息
        return $config
    */
    public function get_api_config()
    {
        $user_app_name = array('wechatauth' => '微信');
        $config = array();
        $Strategy = array();
        $key_list = array();
        $app_list = $this->UserApp->find('all', array('conditions' => array('UserApp.status' => '1'), 'fields' => array('UserApp.name', 'UserApp.type', 'UserApp.app_key', 'UserApp.app_code')));
        if (!empty($app_list)) {
            foreach ($app_list as $k => $v) {
                $key_list['key'] = $v['UserApp']['app_key'];
                $key_list['secret'] = $v['UserApp']['app_code'];
                $Strategy[$v['UserApp']['type']] = $key_list;
                $key_list = array();
                $user_app_name[$v['UserApp']['type']] = $v['UserApp']['name'];
            }
        }
        if (!empty($Strategy)) {
            $this->userAppNames = $user_app_name;
            $config = array(
                'path' => '/synchros/opauth/',
                'callback_url' => '/synchros/callback/',
                'security_salt' => 'LDFmiilYf8Fyw5W10rx4W1KsVrabQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m',
                'Strategy' => $Strategy,
            );

            return $config;
        }

        return false;
    }

    //授权加载
    public function opauth()
    {
    	$_GET=$this->clean_xss($_GET);
        if(isset($_GET['action_code'])&&!empty($_GET['action_code'])){
            $action_code=$_GET['action_code'];
            $_SESSION['API_Action_Code']=$action_code;
        }
        $config = $this->get_api_config();
        $o2 = new Opauth($config);
    }

    //回调函数
    public function callback()
    {
        /*
            判断是否为手机版
        */
        $is_mobileflag = false;
        if ($this->is_mobile) {
            $is_mobileflag = true;
        }
        $_GET=$this->clean_xss($_GET);
        if (isset($_GET['code']) && isset($_GET['state']) && $_GET['state'] == 'qq') {
            //QQ互联登陆
            $this->redirect('/synchros/opauth/qq/qq_callback?code='.$_GET['code']);
        }
        if (isset($_SESSION['wechatuser'])) {
            $_SESSION['opauth'] = $_SESSION['wechatuser'];
            unset($_SESSION['wechatuser']);
        } else {
            $config = $this->get_api_config();
            $Opauth = new Opauth($config, false);
        }
        $response = array();
        $response = isset($_SESSION['opauth']) ? $_SESSION['opauth'] : array();
        //unset($_SESSION['opauth']);
        if (isset($response['auth']['uid'])) {
            if(isset($_SESSION['API_Action_Code'])&&!empty($_SESSION['API_Action_Code'])){
                $action_code=$_SESSION['API_Action_Code'];
                $this->set('action_code',$_SESSION['API_Action_Code']);
                unset($_SESSION['API_Action_Code']);
            }
            $u_id = isset($response['auth']['raw']['openid']) ? $response['auth']['raw']['openid'] : $response['auth']['uid'];
            $local_me = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.account' => $u_id, 'type' => $response['auth']['provider'])));
            if (!empty($local_me)&&isset($action_code)&&$action_code=="api_bind") {
                $this->flash("<font color='red'>该用户已被绑定</font>", array('controller' => '/'), 5);
                return;
            }
            if (!empty($local_me)) {
                //已绑定用户
                //未登录绑定用户
                if (!isset($_SESSION['User']['User'])) {
                    $users = $this->User->find('first', array('conditions' => array('User.id' => $local_me['SynchroUser']['user_id'])));
                    $_SESSION['User'] = $users;
                    $this->SynchroUser->updateAll(array('SynchroUser.oauth_token' => "'".$response['auth']['credentials']['token']."'"), array('SynchroUser.id' => $local_me['SynchroUser']['id']));
                    if ($is_mobileflag) {
                        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users/';
                    } else {
                        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/user_socials/index/'.$_SESSION['User']['User']['id'];
                    }
                    $this->redirect($back_url);
                } else {
                    $us = $this->User->find('first', array('conditions' => array('User.id' => $local_me['SynchroUser']['user_id'])));
                    if ($_SESSION['User']['User']['id'] != $us['User']['id']) {
                        $msg = '该用户已被绑定';
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="/users"</script>';
                        die();
                    } else {
                        if ($is_mobileflag) {
                            $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users/';
                        } else {
                            $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/user_socials/index/'.$_SESSION['User']['User']['id'];
                        }
                        $this->redirect($back_url);
                    }
                }
            } else {
                if (isset($_SESSION['User']['User'])) {
                    $user_data = array(
                        'user_id' => $_SESSION['User']['User']['id'],
                        'account' => $u_id,
                        'oauth_token' => $response['auth']['credentials']['token'],
                        'type' => $response['auth']['provider'],
                        'oauth_token_secret' => '',
                        'created' => date('Y-m-d H:i:s', time()),
                    );
                    $this->SynchroUser->save($user_data);
                    if ($is_mobileflag) {
                        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users/';
                    } else {
                        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/user_socials/index/'.$_SESSION['User']['User']['id'];
                    }
                    $this->redirect($back_url);
                } else {
                    $this->pageTitle = '账号绑定 - '.$this->configs['shop_title'];                    //页面初始化
                    //当前位置
                    $this->ur_heres[] = array('name' => '账号绑定','url' => '');
                    $this->set('ur_heres', $this->ur_heres);
                    $this->set('u_id', $u_id);
                    $this->set('response', $response);
                    $this->set('userAppNames', $this->userAppNames);

                    if ($is_mobileflag) {
                        $this->layout = 'mobile/default_full';
                        $this->render('mobile/callback');
                        Configure::write('debug', 0);
                    }
                }
            }
        } else {
            $msg = '接口异常,丢失用户，授权失败！请稍后再试';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/users"</script>';
            die();
        }
    }

    //检查授权状态
    public function checktoken($type)
    {
        $result['flag'] = 0;
        $result['status'] = '';
        $syn_config = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.User_id' => $_SESSION['User']['User']['id'], 'type' => $type)));
        if (!empty($syn_config)) {
            if ($syn_config['SynchroUser']['status'] == '0') {
                $this->SynchroUser->updateAll(array('SynchroUser.status' => '1'), array('SynchroUser.id' => $syn_config['SynchroUser']['id']));
                $result['status'] = 1;
            } elseif ($syn_config['SynchroUser']['status'] == '1') {
                $this->SynchroUser->updateAll(array('SynchroUser.status' => '0'), array('SynchroUser.id' => $syn_config['SynchroUser']['id']));
                $result['status'] = 0;
            }
            $result['flag'] = 1;
        }
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }

    public function checkdata()
    {
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        if ($this->RequestHandler->isPost()) {
            $type = isset($_POST['type']) && $_POST['type'] != '' ? trim($_POST['type']) : '';
            $value = isset($_POST['value']) && $_POST['value'] != '' ? trim($_POST['value']) : '';
            $result['code'] = 0;
            $result['msg'] = 'Not found data';
            switch ($type) {
                case 'email':
                    $cond['or']['User.user_sn'] = $value;
                    $cond['or']['User.email'] = $value;
                    $is_email = $this->User->find('first', array('conditions' => $cond));
                    if (!empty($is_email)) {
                        $result['msg'] = $this->ld['email_already_exists'];
                    } else {
                        $result['code'] = 1;
                        $result['msg'] = '';
                    }
                    break;
                default:
                    break;
            }
            die(json_encode($result));
        }
    }

    public function apibind()
    {
        /*
            判断是否为手机版
        */
        $is_mobileflag = false;
        $mobile_status = $this->Template->find('first', array('conditions' => array('is_default' => 1), 'fields' => array('Template.mobile_status')));
        if ((isset($_SESSION['is_mobile']) && $_SESSION['is_mobile'] == '1') || (($this->RequestHandler->isMobile() && $mobile_status['Template']['mobile_status'] == '1') && !isset($_SESSION['is_mobile']))) {
            $is_mobileflag = true;
        }
        if ($this->RequestHandler->isPost()) {
            $type = isset($this->data['type']) && trim($this->data['type']) != '' ? $this->data['type'] : '';
            $api_type = isset($this->data['api_type']) && trim($this->data['api_type']) != '' ? $this->data['api_type'] : '';
            $oauth_token = isset($this->data['oauth_token']) && trim($this->data['oauth_token']) != '' ? $this->data['oauth_token'] : '';
            $u_id = isset($this->data['u_id']) && trim($this->data['u_id']) != '' ? $this->data['u_id'] : '';
            $email = isset($this->data['email']) && trim($this->data['email']) != '' ? $this->data['email'] : '';
            $password = isset($this->data['password']) && trim($this->data['password']) != '' ? md5($this->data['password']) : '';
            $back_url="/";
            if(isset($_SESSION['login_back'])){
                $back_url = $_SESSION['login_back'];
            }else if(isset($_SESSION['User']['User'])){
                $back_url = '/user_socials/index/'.$_SESSION['User']['User']['id'];
            }
            if ($type == 'register') {
                $user_name = isset($this->data['user_name']) && trim($this->data['user_name']) != '' ? $this->data['user_name'] : '';
                $user_nickname = isset($this->data['user_nickname']) && trim($this->data['user_nickname']) != '' ? $this->data['user_nickname'] : '';
                if ($user_name != '') {
                    $username = $user_name;
                } else {
                    if ($user_nickname != '') {
                        $username = $user_nickname;
                    } else {
                        $username = $email;
                    }
                }
                $new_user['user_sn'] = $email;
                $new_user['email'] = $email;
                $new_user['first_name'] = $username;
                $new_user['name'] = $username;
                $new_user['img01'] = isset($this->data['img']) ? $this->data['img'] : '';
                $this->User->save($new_user);
                $_SESSION['User'] = $this->User->find('first', array('conditions' => array('User.id' => $this->User->id)));
                $user_data = array(
                        'user_id' => $_SESSION['User']['User']['id'],
                        'account' => $u_id,
                        'oauth_token' => $oauth_token,
                        'type' => $api_type,
                        'oauth_token_secret' => '',
                        'created' => date('Y-m-d H:i:s', time()),
                    );
                $this->SynchroUser->save($user_data);
                $this->redirect($back_url);
            } elseif ($type == 'login') {
                $login_type = isset($this->data['login_type']) && trim($this->data['login_type']) != '' ? $this->data['login_type'] : 'user_sn';
                $result['code'] = 0;
                $result['msg'] = $this->ld['login_name'].'或密码错误';
                if ($login_type == 'user_sn') {
                    $user_cond['User.user_sn'] = $email;
                } elseif ($login_type == 'email') {
                    $user_cond['User.email'] = $email;
                } else {
                    $user_cond['User.mobile'] = $email;
                }
                $user_cond['User.password'] = $password;
                $userInfo = $this->User->find('first', array('conditions' => $user_cond));
                if (!empty($userInfo)) {
                    $_SESSION['User'] = $userInfo;
                    $user_data = array(
                        'user_id' => $userInfo['User']['id'],
                        'account' => $u_id,
                        'oauth_token' => $oauth_token,
                        'type' => $api_type,
                        'oauth_token_secret' => '',
                        'created' => date('Y-m-d H:i:s', time()),
                    );
                    $this->SynchroUser->save($user_data);
                    $result['code'] = 1;
                    $result['msg'] = $back_url;
                }
                $this->layout = 'ajax';
                Configure::write('debug', 0);
                die(json_encode($result));
            } elseif ($type == 'fast_login') {
                $user_name = isset($this->data['user_name']) && trim($this->data['user_name']) != '' ? $this->data['user_name'] : '';
                $user_nickname = isset($this->data['user_nickname']) && trim($this->data['user_nickname']) != '' ? $this->data['user_nickname'] : '';
                if ($user_name != '') {
                    $username = $user_name;
                } else {
                    if ($user_nickname != '') {
                        $username = $user_nickname;
                    } else {
                        $username = $email;
                    }
                }
                $new_user['user_sn'] = $username.'@'.$api_type;
                $new_user['email'] = $email;
                $new_user['first_name'] = $username;
                $new_user['name'] = $username;
                $new_user['img01'] = isset($this->data['img']) ? $this->data['img'] : '';
                $this->User->save($new_user);
                $_SESSION['User'] = $this->User->find('first', array('conditions' => array('User.id' => $this->User->id)));
                $user_data = array(
                        'user_id' => $_SESSION['User']['User']['id'],
                        'account' => $u_id,
                        'oauth_token' => $oauth_token,
                        'type' => $api_type,
                        'oauth_token_secret' => '',
                        'created' => date('Y-m-d H:i:s', time()),
                    );
                $this->SynchroUser->save($user_data);
                $this->redirect($back_url);
            }
        }
        $this->redirect('/');
    }

    /*
        微信扫描二维码登录返回处理
    */
    public function wechatcallback()
    {
        if (!empty($_GET['code'])){
            $code = $_GET['code'];
            $config = $this->get_api_config();
            
            $appid = $config['Strategy']['Wechat']['key'];
            $secret = $config['Strategy']['Wechat']['secret'];
            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
            $params = array(
                'appid' => $appid,
                'secret' => $secret,
                'code' => $code,
                'grant_type' => 'authorization_code',
            );
            $results = $this->https_request($get_token_url, $params);
            if (empty($results)) {
                $error = array(
                    'code' => 'Get access token error',
                    'message' => 'Failed when attempting to get access token',
                    'raw' => array(
                        'headers' => $results,
                    ),
                );
            } else {
                if (empty($results['access_token'])) {
                    $error = array(
                        'code' => 'Get access token error',
                        'message' => 'Failed when attempting to get access token',
                        'raw' => array(
                            'headers' => $results,
                        ),
                    );
                } else {
                    $access_token = $results['access_token'];
                    $get_user_url = 'https://api.weixin.qq.com/sns/userinfo';
                    $user_results = $this->https_request($get_user_url, array('access_token' => $results['access_token'], 'openid' => $results['openid'], 'lang' => 'zh_CN'));
                    if (isset($results['openid'])) {
                        $wechatuser['auth'] = array(
                            'provider' => 'wechat',
                            'uid' => $user_results['openid'],
                            'info' => array(
                                'name' => $user_results['nickname'],
                                'sex' => $user_results['sex'],
                                'nickname' => $user_results['nickname'],
                                'image' => $user_results['headimgurl'],
                            ),
                            'credentials' => array(
                                'token' => $results['access_token'],
                                'expires' => date('c', time() + $results['expires_in']),
                            ),
                            'raw' => $results,
                        );
                    } else {
                        $error = array(
                            'code' => 'Get wechat user error',
                            'message' => 'Failed when attempting to get access token',
                            'raw' => array(
                                'headers' => $user_results,
                            ),
                        );
                    }
                }
            }
            if (isset($wechatuser)) {
                $_SESSION['wechatuser'] = $wechatuser;
                $this->redirect('/synchros/callback/');
            } else {
                $msg = isset($error) ? $error['code'].'/r/n'.$error['message'] : '接口异常,丢失用户，授权失败！请稍后再试';
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/users/login";</script>';
                die();
            }
        } else {
            $this->redirect('/');
        }
    }

    /*
        调用接口
    */
    private function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return json_decode($output, true);
    }
}
