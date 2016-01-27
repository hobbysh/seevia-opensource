<?php

/*****************************************************************************
 * Seevia 网站设置
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
/**
 *这是一个名为 ConfigvaluesController 的控制器
 *后台商店设置控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class ConfigvaluesController extends AppController
{
    public $name = 'Configvalues';
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('NavigationI18n','Navigation','Product','Article','Topic','CategoryArticle','Resource','Config','ConfigI18n','Dictionary','Config','ConfigI18n','OperatorLog','CategoryProduct','Brand','Route');

    /**
     *显示商店的各个内容.
     */
    public function index($group_code = 'website')
    {
        $this->operator_privilege('configvalues_view');
        $this->menu_path = array('root' => '/system/','sub' => '/configvalues/');
        $this->set('title_for_layout', $this->ld['shop_configs'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['shop_configs'],'url' => '/configvalues/');
        //所有商品
        $p_info = $this->Product->find('all', array('conditions' => array('Product.status' => 1, 'ProductI18n.locale' => $this->backend_locale)));

        if (isset($p_info) && $p_info != '' && count($p_info) > 0) {
            $this->set('p_info', $p_info);
        }
        //所有文章
        $a_info = $this->Article->find('all', array('conditions' => array('Article.status' => 1, 'ArticleI18n.locale' => $this->backend_locale)));
        if (isset($a_info) && $a_info != '' && count($a_info) > 0) {
            $this->set('a_info', $a_info);
        }
        //所有品牌
        $b_info = $this->Brand->find('all', array('conditions' => array('Brand.status' => 1, 'BrandI18n.locale' => $this->backend_locale)));
        if (isset($b_info) && $b_info != '' && count($b_info) > 0) {
            $this->set('b_info', $b_info);
        }
        //所有专题
        $topic_info = $this->Topic->find('all');
        if (isset($topic_info) && $b_info != '' && count($topic_info) > 0) {
            $this->set('topic_info', $topic_info);
        }
        //商品分类
        $c_p_info = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.status' => 1, 'CategoryProductI18n.locale' => $this->backend_locale, 'CategoryProduct.type' => 'P')));
        if (isset($c_p_info) && $c_p_info != '' && count($c_p_info) > 0) {
            $this->set('c_p_info', $c_p_info);
        }
        //pr($c_p_info);
        //文章分类
            $c_a_info = $this->CategoryArticle->find('all', array('conditions' => array('CategoryArticle.status' => 1, 'CategoryArticleI18n.locale' => $this->backend_locale, 'CategoryArticle.type' => 'A')));
        if (isset($c_a_info) && $c_a_info != '' && count($c_a_info) > 0) {
            $this->set('c_a_info', $c_a_info);
        }
        //显示首页控制器路径地址
        $home_url = $this->Route->find('first', array('conditions' => array('Route.url' => '/')));
        $this->set('home_url', $home_url);
        //pr($home_url);
//		if(isset($_GET['type'])&&$_GET['type']==1){
//			
//		}
        $this->Config->hasOne = array();
        //资源库信息//直接从base表里面读取system的数据
        $Resource_info = $this->Resource->getformatcode(array('configvalues'), $this->backend_locale);
        $this->set('config_group_codes', $Resource_info['configvalues']);
        //获取子分组
        $subgroupcode = array();
        foreach ($Resource_info['configvalues'] as $k => $v) {
            $subgroupcode[] = $k.'_set';
        }
        $sub_group_cond['Resource.code'] = $subgroupcode;
        $sub_group_cond['ResourceI18n.locale'] = $this->backend_locale;
        $sub_group_Info = $this->Resource->find('all', array('fields' => array('Resource.id', 'Resource.code', 'ResourceI18n.name'), 'conditions' => $sub_group_cond, 'order' => 'Resource.orderby,Resource.id'));
        $sub_group_Ids = array();
        $sub_group_codes = array();
        foreach ($sub_group_Info as $v) {
            $sub_group_Ids[] = $v['Resource']['id'];
        }
        $sub_group_set_cond['Resource.parent_id'] = $sub_group_Ids;
        $sub_group_set_cond['ResourceI18n.locale'] = $this->backend_locale;
        $sub_group_set_Info = $this->Resource->find('all', array('fields' => array('Resource.parent_id', 'Resource.code', 'ResourceI18n.name'), 'conditions' => $sub_group_set_cond, 'order' => 'Resource.orderby,Resource.id'));
        $sub_group_list = array();
        foreach ($sub_group_set_Info as $v) {
            $parent_code_arr = explode('_', $v['Resource']['code']);
            $sub_group_codes[$v['Resource']['code']] = $v['ResourceI18n']['name'];
            $sub_group_list[$parent_code_arr[0]][] = $v['Resource']['code'];
        }
        $this->set('config_sub_group_codes', $sub_group_codes);
        $this->Config->hasMany = array('ConfigI18n' => array('className' => 'ConfigI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'config_id',
                        ),
                  );
        //店表里没有的 base里面有的增加
        $lans = $this->Language->find('list', array('fields' => 'Language.id,Language.locale'));
        $basics = array();
        $group_codes = array();
        foreach ($Resource_info['configvalues'] as $k => $v) {
            $group_codes[] = $k;
        }
        $configs = $this->Config->find('all', array('conditions' => array('Config.group_code' => $group_codes, 'Config.readonly' => 0, 'Config.status' => 1), 'order' => 'Config.orderby,Config.group_code'));
        $config_group_list = array();
        $val = array();
        foreach ($configs as $k => $v) {
            $val['Config'] = $v['Config'];
            foreach ($v['ConfigI18n'] as $kk => $vv) {
                if ($vv['locale'] == $this->backend_locale) {
                    $val['Config']['name'] = @$vv['name'];
                }
                $val['ConfigI18n'][$vv['locale']] = $vv;
                if ($v['Config']['type'] == 'radio' || $v['Config']['type'] == 'checkbox' || $v['Config']['type'] == 'image') {
                    $val['ConfigI18n'][$vv['locale']]['options'] = explode("\n", $vv['options']);
                }
            }
            $config_groups[$v['Config']['group_code']][$v['Config']['subgroup_code']][] = $val;
        }
        //排序处理
        $config_groups_data = array();
        foreach ($config_groups as $k => $v) {
            if (isset($sub_group_list[$k]) && is_array($sub_group_list[$k]) && sizeof($sub_group_list[$k]) > 0) {
                foreach ($sub_group_list[$k] as $vv) {
                    if (isset($config_groups[$k][$vv])) {
                        $config_groups_data[$k][$vv] = $config_groups[$k][$vv];
                    }
                }
            }
        }
        $this->set('group_codes', $group_codes);
        $this->set('config_groups', $config_groups_data);
    }

    /**
     *保存商店设置.
     *
     *@param string $group_code 分组代码由资源库提供
     */
    public function edit($group_code = '')
    {
        $node['config'] = 'node';
        $node['use'] = true;
        $this->operator_privilege('configvalues_edit');
        if ($this->RequestHandler->isPost()) {
            //首页控制器有值
            if (isset($_REQUEST['Route']) && !empty($_REQUEST['Route'])) {
                $rurl = $this->Route->find('first', array('conditions' => array('Route.url' => '/')));
                if (!empty($_REQUEST['Route']['url'])) {
                    $route_url = explode('/', $_REQUEST['Route']['url']);
                }//字符串分割成数组
                if (isset($route_url)) {
                    if (empty($rurl)) {
                        //判断里面是否添加相同的数据
                        //foreach($this->data[])
                        $this->data['Route']['controller'] = isset($route_url[1]) ? $route_url[1] : 'pages';
                        $this->data['Route']['url'] = '/';
                        $this->data['Route']['action'] = isset($route_url[3]) ? $route_url[2] : (isset($route_url[2]) ? 'view' : 'index');
                        if ($route_url[1] == 'pages') {
                            $this->data['Route']['action'] = 'home';
                        }
                        $this->data['Route']['model_id'] = isset($route_url[3]) ? $route_url[3] : $route_url[2];
                        $this->Route->save(array('Route' => $this->data['Route']));
                    } else {
                        $this->data['Route']['controller'] = isset($route_url[1]) ? $route_url[1] : 'pages';
                        $this->data['Route']['url'] = '/';
                        $this->data['Route']['action'] = isset($route_url[3]) ? $route_url[2] : (isset($route_url[2]) && !empty($route_url[2]) ? 'view' : 'index');
                        if ($route_url[1] == 'pages') {
                            $this->data['Route']['action'] = 'home';
                        }
                        $this->data['Route']['model_id'] = isset($route_url[3]) ? $route_url[3] : $route_url[2];
                        $this->data['Route']['id'] = $rurl['Route']['id'];
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                }
                //pr($route_url);die;
                unset($this->data['Route']);
            }
            foreach ($this->data as $k => $v) {
                $all_locale = array();
                if (isset($v['value']) && is_array($v['value'])) {
                    if (isset($v['value']['tmp_name']) && !empty($v['value']['tmp_name'])) {
                        move_uploaded_file($v['value']['tmp_name'], dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/data/favicon.ico');
                        $v['value'] = 'http://'.$_SERVER['HTTP_HOST'].'/favicon.ico';
                    } elseif (isset($v['value']['tmp_name']) && empty($v['value']['tmp_name'])) {
                        continue;
                    } else {
                        $v['value'] = implode(';', $v['value']);
                    }
                }
                if (isset($v['value'])) {
                    $value = $v['value'];
                } else {
                    $value = '';
                }
                $this->ConfigI18n->saveAll(array('id' => $v['id'], 'locale' => $v['locale'], 'config_id' => $v['config_id'], 'value' => $value));
                if (isset($v['type']) && $v['type'] == 'nav_select') {
                    $infos = $this->ConfigI18n->find('all', array('conditions' => array('ConfigI18n.config_id' => $v['config_id'])));
                    //导航处理
                    foreach ($this->front_locales as $lv) {
                        $all_locale[] = $lv['Language']['locale'];
                    }
                    foreach ($infos as $vv) {
                        $url = '/'.$vv['ConfigI18n']['param01'].'/';
                        if (in_array($vv['ConfigI18n']['locale'], $all_locale)) {
                            $name[$vv['ConfigI18n']['locale']] = $vv['ConfigI18n']['param02'];
                        }
                    }
                //	pr($url);pr($v['value']);pr($name);pr($all_locale);
                    $this->change_nav($url, $v['value'], $name, $all_locale);//第一个参数$url,本为$code(由code改写的路径)
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['modify_shop_configs'], $this->admin['id']);
            }
            $all_configs = $this->Config->getformatcode_all();
            $update_configs = $all_configs[$this->locale];
            //$this->flash('商店设置修改成功','/'.$_SESSION['cart_back_url'],'');
            $this->redirect('/configvalues/index/'.$group_code);
        }
    }

    //导航表的插入 和 修改
    public function change_nav($code, $type, $name, $all_locale)
    {
        //判断是第一次插入还是对已经存在数据进行修改
        //$url='/'.strtolower($code[1]).'/';
        $url = $code;
        $isHave = $this->Navigation->find('first', array('conditions' => array('NavigationI18n.url' => $url)));
        if (!empty($isHave)) {
            if ($type == '0') {
                $this->NavigationI18n->deleteAll(array('NavigationI18n.url' => $url));
                $this->Navigation->deleteAll(array('Navigation.id' => $isHave['Navigation']['id']));
            } else {
                $isHave['Navigation']['type'] = $type;
                $this->Navigation->save($isHave);
            }
        } elseif (empty($isHave) && $type != '0') {
            $nav_info['type'] = $type;
            $nav_info['status'] = 1;
            $nav_info['parent_id'] = 0;
            $nav_info['target'] = '_self';
            $nav_info['controller'] = 'pages';
            $this->Navigation->saveAll($nav_info);
            $id = $this->Navigation->id;
            $this->NavigationI18n->deleteAll(array('NavigationI18n.navigation_id' => $id));
            foreach ($all_locale as $k => $v) {
                $navi18n_info[$k]['navigation_id'] = $id;
                $navi18n_info[$k]['locale'] = $v;
                $navi18n_info[$k]['url'] = $url;
                $navi18n_info[$k]['name'] = $name[$v];
            }
            $this->NavigationI18n->saveAll($navi18n_info);
        }
    }

    /**
     *保存邮件服务器设置.
     */
    public function mail_settings_edit()
    {
        if ($this->RequestHandler->isPost()) {
            foreach ($this->data as $k => $v) {
                if (isset($v['value']) && is_array($v['value'])) {
                    $v['value'] = implode(';', $v['value']);
                }
                if (isset($v['value'])) {
                    $value = $v['value'];
                } else {
                    $value = '';
                }
                $value = addslashes($value);
                $this->ConfigI18n->updateAll(array('value' => "'".$value."'"), array('id' => $v['id']));
            }
            $this->redirect('/configvalues/mail_settings/');
        }
    }

    /**
     *邮件服务器设置显示.
     */
    public function mail_settings()
    {
        $this->set('title_for_layout', $this->ld['email_setting'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['email_setting'],'url' => '/configvalues/mail_settings/');
        $condition2['Config.group_code'] = 'email';
        $this->Config->hasOne = array();
        $this->Config->hasMany = array('ConfigI18n' => array('className' => 'ConfigI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'config_id',
                        ),
                  );
        $config = $this->Config->find('all', array('conditions' => $condition2, 'order' => 'Config.orderby,Config.created,Config.id'));
        $basics = array();
        $condition3['name'] = '';
        $name_arr = '';
        foreach ($config as $k => $v) {
            $val['Config'] = $v['Config'];
            foreach ($v['ConfigI18n'] as $kk => $vv) {
                if ($vv['locale'] == $this->locale) {
                    $val['Config']['name'] = @$vv['name'];
                }
                $val['ConfigI18n'][$vv['locale']] = $vv;
                if ($v['Config']['type'] == 'radio' || $v['Config']['type'] == 'checkbox' || $v['Config']['type'] == 'image') {
                    $val['ConfigI18n'][$vv['locale']]['options'] = explode("\n", $vv['options']);
                }
                $vv = '';
            }
            if (empty($name_arr[$val['Config']['group_code']])) {
                $condition3['name'] = $val['Config']['group_code'];
                $condition3['locale'] = $this->locale;
                $Dictionary = $this->Dictionary->find($condition3);
                $name_arr[$val['Config']['group_code']] = $Dictionary['Dictionary']['value'];
            }
            $config[$k]['Config']['group_code'] = $name_arr[$val['Config']['group_code']];
            $basics[$config[$k]['Config']['group_code']][] = $val;
            $val = '';
        }
        $sumbasic = count($basics);
        $this->set('sumbasic', $sumbasic);
        $this->set('basics', $basics);
        $this->Config->hasOne = array('ConfigI18n' => array('className' => 'ConfigI18n',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'config_id',
                        ),
                  );
    }

    public function test_allemail($receiver_emails = '')
    {
        $this->Application->init('chi');
        $receiver_emails = isset($_POST['receiver_emails']) ? $_POST['receiver_emails'] : '';
        $receiver_email = explode("\n", $receiver_emails);
        $template_str = $this->ld['test_mail_received_correct'];
        $mailsendqueue = array(
                'sender_name' => $this->configs['shop_name'],//发送人姓名
                'receiver_email' => $receiver_email,//接收人姓名;接收人地址
                'cc_email' => ';',//抄送人
                'bcc_email' => ';',//抄送人
                'title' => '测试邮件',//主题
                'html_body' => $template_str,//内容
                'text_body' => $template_str,//内容
                'sendas' => 'html',
            );
        $this->Email = new EmailComponent();
        $result = $this->Email->send_mail('chi', 1, $mailsendqueue, $this->configs);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *发送测试邮件.
     *
     *@param string $email_addr 接收人EMAIL
     *@param string $smtp_host 邮件服务器地址
     *@param string $smtp_user 发送人EMAIL帐号
     *@param string $smtp_pass 发送人EMAIL密码
     *@param string $smtp_port 邮件服务器端口
     *@param string $smtp_ssl_value 是否用SSL加密发送
     */
    public function test_email()
    {
        $smtp_auth = isset($_POST['smtp_auth']) ? $_POST['smtp_auth'] : 1;
        $smtp_host = isset($_POST['smtp_host']) ? $_POST['smtp_host'] : '';
        $smtp_user = isset($_POST['smtp_user']) ? $_POST['smtp_user'] : '';
        $smtp_port = isset($_POST['smtp_port']) ? $_POST['smtp_port'] : '';
        $mail_service = isset($_POST['mail_service']) ? $_POST['mail_service'] : '';
        $smtp_ssl_value = isset($_POST['smtp_ssl_value']) ? $_POST['smtp_ssl_value'] : '';
        $smtp_pass = isset($_POST['smtp_pass']) ? $_POST['smtp_pass'] : '';
        $email_addr = isset($_POST['email_addr']) ? $_POST['email_addr'] : '';
        $this->Email->smtpauth = $smtp_auth;
        $this->Email->sendAs = 'html';
        $this->Email->is_ssl = $smtp_ssl_value;
//		$this->Email->is_mail_smtp=$this->configs['mail_service'];
        $this->Email->is_mail_smtp = $mail_service;
        $this->Email->smtp_port = $smtp_port;
        $this->Email->smtpHostNames = ''.$smtp_host.'';
        $this->Email->smtpUserName = ''.$smtp_user.'';
        $this->Email->smtpPassword = ''.$smtp_pass.'';
        $this->Email->fromName = $this->ld['test_email'];
//		$all_infos=$this->Application->getcodes();
        $this->Email->subject = $this->configs['shop_name'];
//		$this->Email->from = "".$this->configs['smtp_user']."";
        $this->Email->from = ''.$smtp_user.'';
        /* 商店网址 */
//		$shop_url = $this->server_host.$this->webroot;
        $template_str = $this->ld['test_mail_received_correct'];
        $this->Email->html_body = $template_str;
        $this->Email->to = ''.$email_addr.'';
        $this->Email->toName = ''.$email_addr.'';
        $result = $this->Email->send();
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function changehome($route, $keyword = '')
    {
        //商品分类
        if ($route == 'PC') {
            $s_info = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.status' => 1, 'CategoryProductI18n.locale' => $this->locale, 'CategoryProduct.type' => 'P')));
        }
        if ($route == 'PRODUCT' && $keyword != 'no') {
            $condition['ProductI18n.locale'] = $this->locale;
            $condition['ProductI18n.name like'] = "%$keyword%";
            $s_info = $this->Product->find('all', array('conditions' => $condition));
        }
        if ($route == 'AC') {
            $s_info = $this->CategoryArticle->find('all', array('conditions' => array('CategoryArticle.status' => 1, 'CategoryArticleI18n.locale' => $this->locale, 'CategoryArticle.type' => 'A')));
        }
        if ($route == 'ARTICLE' && $keyword != 'no') {
            $condition['ArticleI18n.locale'] = $this->locale;
            $condition['ArticleI18n.title like'] = "%$keyword%";
            $s_info = $this->Article->find('all', array('conditions' => $condition));
        }
        if ($route == 'TOPIC') {
            $this->Topic->set_locale($this->locale);
            $s_info = $this->Topic->find('all');
        }
        if ($route == 'PROMOTION' && constant('Product') == 'AllInOne') {
            $this->loadModel('Promotion');
            $this->Promotion->set_locale($this->locale);
            $s_info = $this->Promotion->find('all');
        }
        if (isset($s_info) && $s_info != '') {
            $this->set('info', $s_info);
        }
        $this->set('route', $route);
        Configure::write('debug', 1);
        $this->layout = 'ajax';
//	    die(json_encode($result));
    }

    /**
     *函数 change_shop_prompt 修改后台弹出框是否显示.
     *
     *@param
     */
    public function change_shop_prompt()
    {
        $config_info = $this->Config->find('first', array('conditions' => array('Config.code' => 'shop_prompt')));
        if (!empty($config_info)) {
            $config_info['ConfigI18n']['value'] = 0;
            $this->Config->saveAll($config_info);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['flag'] = 1;
        die(json_encode($result));
    }
}
