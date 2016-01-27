<?php

/*****************************************************************************
 * Seevia 前台控制
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为AppController的控制器
 *应用程序控制器.
 */
class AppController extends Controller
{
    /*
     *@var $view 对应视图
     *@var $locale 所用语言
     *@var $useDbConfig 数据库配置
     *@var $information_info 资源信息
     *@var $helpers 引用的帮助类
     *@var $uses	引用的数据库模型
     *@var $configs 系统参数
     *@var $languages 多语言信息
     *@var $navigations 导航
     *@var $components 引用的组件
     */
    public $view = 'Theme';
    public $locale = '';
    public $useDbConfig = 'default';
    public $server_host = '';

    public $languages = array();
    public $model_locale = array(   //支持单语言模式的对象
        'product' => LOCALE,
        'brand' => LOCALE,
        'category' => LOCALE,
    );

    public $information_resources = array();
    public $system_resources = array();
    public $configs = array();
    public $ur_heres = array();
    public $components = array('RequestHandler','Cookie','Session');
    public $helpers = array('combinator.combinator','Html','Javascript','Form','Svshow','Minify','Cache','HtmlSeevia');
    public $uses = array('Topic','Application','ProductI18n','AdvertisementI18n','UserRank','ProductRank','Language','Config','Navigation','Brand','CategoryProduct','Article','LanguageDictionary','UserConfig','Template','Link','Product','Currency','SystemResource','User','InformationResource','Advertisement','AdvertisementI18n','AdvertisementPosition','Region','VoteLog','Vote','VoteOption','ProductTypeAttribute','ArticleCategory','CategoryArticle','UserRankLog','UserApp');
    public $x = '';
    public $y = '';
    public $short = array('config' => 'short','use' => true);
    public $all_app_codes = array();
    public $app_infos = array();
    public $page_infos = array();
    public $pageTitle = '';
    public $is_mobile = 0;

    /**
     *调用action之前.
     */
    public function beforeFilter()
    {
    	
        @session_start();
        //session有效域
        $this->Session->path = '/';
        //时区设置	todo
        @$time_zone = include ROOT.'time_zone.php';
        @ini_set('date.timezone', empty($time_zone[$this->configs['default_timezone']]) ? 'Asia/Shanghai' : $time_zone[$this->configs['default_timezone']]);
        unset($time_zone);
        //当前域名
        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $this->server_host = 'http://'.$host;
        $this->set('server_host', $this->server_host);
        $this->webroot = '/';
        $this->set('webroot', $this->webroot);
        //$d=explode('.',$host);
        // 订单来源
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer_arr = array();
            $referer_arr = explode('/', $_SERVER['HTTP_REFERER']);
            if (!in_array($host, $referer_arr)) {
                $this->Cookie->write('referer', $_SERVER['HTTP_REFERER'], false, time() + 3600 * 24 * 365);
            }
        }
        //多语言
        //取商店配置
        $this->configs = $this->Config->getformatcode();
        $this->set_locale();//设定语言
        $this->check_version();
//		$this->Application->init($this->locale);
//		//获取所有可用应用的信息
//		$app_infos=$this->Application->availables();
//		$this->app_infos=$app_infos;
//		$this->set('app_infos',$app_infos);
//		//获取所有应用code的集合
//		$all_app_codes=$this->Application->getcodes();
//		$this->all_app_codes=$all_app_codes;
//		$this->set('all_app_codes',$all_app_codes);
//        $app_ay = $this->Application->init2($this->locale);
//        $app_infos = $app_ay['Applications'];
//        $this->all_app_codes = $app_ay['codes'];
//        $this->app_infos = $app_infos;
//        $this->set('all_app_codes', $this->all_app_codes);
//        $this->set('app_infos', $app_infos);
        $this->set('meta_description', $this->configs['seo-des']);
        $this->set('meta_keywords', $this->configs['seo-key']);
        $shop_default_img = '/themed/default/img/default.jpg';
        if (isset($this->configs['shop_default_img']) && $this->configs['shop_default_img'] != '') {
            $shop_default_img = $this->configs['shop_default_img'];
        }
        if (!isset($this->configs['products_default_image']) || $this->configs['products_default_image'] == '') {
            $this->configs['products_default_image'] = $shop_default_img;
        }
        $this->set('configs', $this->configs);
        $this->set('shop_default_img', $shop_default_img);
        Configure::write('shop_default_img', $shop_default_img);
        //$this->configs['product_auto_search']=0;//模拟设置搜索联想功能

        //分类统计，产品统计个数
        $catcount = $this->CategoryProduct->find('count', array('cache' => $this->short, 'conditions' => array('type' => 'P')));
        $prountcount = $this->Product->find('count', array('cache' => $this->short, 'conditions' => array('Product.status' => 1, 'forsale' => 1, 'alone' => 1)));
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('ShippingArea');
            $shippingareacount = $this->ShippingArea->find('count', array('cache' => $this->short, 'conditions' => array('status' => 1)));
            $this->set('catcount', $catcount);
            $this->set('productcount', $prountcount);
            $this->set('shippingareacount', $shippingareacount);
        }
        //判断网店的等级
        /*if(empty($this->configs['rank']) || $this->configs['rank']<=1){
            if($this->params['controller']=='carts' || $this->params['controller']=='users'){
                die('error');
            }
        }*/

        //设置模板服务器
        /*
        if(!empty($this->configs['themes_host'])){
            Configure::write('themes_host', $this->configs['themes_host']);
        }
        */
        $this->themes_host = Configure::read('themes_host');
        $this->set('themes_host', $this->themes_host);

        Configure::write('seo_url', (isset($this->configs['seo_url'])) ? $this->configs['seo_url'] : 0);

        //商店是否关闭
        if ((($this->configs['shop_temporal_closed'] == '1' || $this->configs['shop_temporal_closed'] == '2') && $this->params['action'] != 'closed') || $host == 'co.ioco.test') {
            $this->redirect('/pages/closed');
            exit;
        }

        //是否必须登录才能访问
        if (isset($this->configs['access_right']) && $this->configs['access_right'] == 1 && empty($_SESSION['User']['User']['id']) && $this->params['controller'] != 'users') {
            $this->redirect('/users/login');
            exit;
        }
        $this->information_info = $this->InformationResource->information_formated(true, $this->locale);
        $this->set('information_info', $this->information_info);

        //字典语言 todo
        $this->ld = $this->LanguageDictionary->getformatcode(LOCALE);
        $this->set('ld', $this->ld);

        //定义首页ur_here
        $this->ur_heres[0] = array('name' => $this->ld['home'],'url' => '/');
        //商品排序方式 新飞
        $this->product_order_field = '';
        //高级系统资源库 内定
        $this->system_resources = $this->SystemResource->resource_formated(true, LOCALE);
        $this->set('system_resources', $this->system_resources);

        //普通资源库
        //$this->information_resources = $this->InformationResource->information_formated(true,LOCALE);
        $this->set('information_resources', $this->information_info);

        //导航
        $navigations = $this->Navigation->get_types(LOCALE);
        //判断是否有自己导航
        $isHave = false;
        if (isset($navigations['H']) && is_array($navigations['H'])) {
            foreach ($navigations['H'] as $k => $v) {
                if (!empty($v['SubMenu']) && is_array($v['SubMenu'])) {
                    $isHave = true;
                }
            }
        }
        $this->set('isHave', $isHave);
        $this->set('navigations', $navigations);
        //todo
        unset($_SESSION['back_url']);
        //帮助中心文章
        $helparticle = $this->Article->find('all', array('cache' => $this->short, 'conditions' => array('type' => 'H'), 'limit' => 3, 'fields' => array('Article.id', 'ArticleI18n.title')));
        $this->set('helparticle', $helparticle);

    //  $conditions = array('Topic.start_time <='=>DateTime,'Topic.end_time >='=>DateTime);
        $conditions = array('Topic.start_time <=' => date('Y-m-d 00:00:00'),'Topic.end_time >=' => date('Y-m-d 23:59:59'));
        $topics = $this->Topic->find('all', array('cache' => $this->short, 'conditions' => $conditions, 'fields' => array('Topic.id', 'TopicI18n.title')));
        $this->set('globaltopic', $topics);
        $_SESSION['custom_page'] = false;
        $mobile_status = $this->Template->find('first', array('conditions' => array('is_default' => 1), 'fields' => array('Template.mobile_status', 'Template.name')));
        // check for mobile devices
        // @todo 增加参数设置，Mobile可以控制
        if ((isset($_GET['is_mobile']) && $_GET['is_mobile'] == '1')) {
            $_SESSION['is_mobile'] = '1';
            $_SESSION['template_use'] = 'default';
        }
        if (isset($_GET['is_mobile']) && $_GET['is_mobile'] == '0') {
            $_SESSION['is_mobile'] = '0';
        }

        if ((isset($_SESSION['is_mobile']) && $_SESSION['is_mobile'] == '1') || (($this->RequestHandler->isMobile() && $mobile_status['Template']['mobile_status'] == '1') && !isset($_SESSION['is_mobile']))) {
            $view_file = file_exists(ROOT.DS.APP_DIR.'/views/themed/'.'default'.DS.strtolower($this->name).DS.'mobile/'.$this->action.'.ctp');
            if ($view_file) {
                $this->is_mobile = true;
                $this->set('is_mobile', true);
                //$this->autoRender = false;
            }
        }

        //数据库启用的模版
        $template_list = $this->Template->find('all', array('cache' => $this->short, 'conditions' => array('Template.status !=' => 0), 'fields' => array('Template.name', 'Template.is_default', 'Template.template_style')));
        $this->set('template_list', $template_list);
        //默认模板及可用模板列表
        $template_name_arr = array();
        foreach ($template_list as $k => $v) {
            $template_name_arr[] = $v['Template']['name'];
            if ($v['Template']['is_default'] == 1) {
                $default_template = $v;
            }
        }

        //搜索物理文件可选的模版信息 todo
        if (!empty($this->configs['can_select_template']) || !isset($this->configs['can_select_template'])) {
            $available_templates = array();
            $theme_dir = ROOT.DS.APP_DIR.'/views/themed/';
            $template_dir = @opendir($theme_dir);
            while ($file = readdir($template_dir)) {
                if ($file != '.' && $file != '..' && is_dir($theme_dir.$file) && $file != '.svn' && $file != 'index.htm') {
                    $available_templates[] = $this->get_template_info($file, $theme_dir);
                }
            }
            $can_select_template = array();
            if (isset($available_templates) && sizeof($available_templates) > 0) {
                foreach ($available_templates as $k => $v) {
                    if (in_array($v['code'], $template_name_arr)) {
                        $can_select_template[] = $v;
                    }
                }
            }
            $this->set('can_select_template', $can_select_template);
        }
        //设置模板
        if (isset($_GET['themes']) && in_array($_GET['themes'], $template_name_arr)) {
            $_SESSION['template_use'] = $_GET['themes'];
        } elseif (empty($_SESSION['template_use']) && isset($default_template)) {
            $_SESSION['template_use'] = $default_template['Template']['name'];
        }
        if (empty($_SESSION['template_use']) || !in_array($_SESSION['template_use'], $template_name_arr)) {
            $_SESSION['template_use'] = 'default';
        }
        $this->Cookie->write('template', $_SESSION['template_use']);
        $this->set('template_use', $_SESSION['template_use']);
        $this->theme = $_SESSION['template_use'];
        App::build(array(
        'views' => array(ROOT.'/web/views/themed/'.$this->theme.'/'),
    ));
        //设置模板样式
        $template_style = '';
        foreach ($template_list as $k => $v) {
            if ($v['Template']['name'] == $this->theme) {
                $template_style = $v['Template']['template_style'];
            }
        }
        if (isset($_GET['theme_style'])) {
            $template_style = $_GET['theme_style'];
        } elseif (isset($_SESSION['template_style'])) {
            $template_style = $_SESSION['template_style'];
        } elseif (empty($template_style)) {
            $template_style = 'green';
        }

        $_SESSION['template_style'] = $template_style;
        $this->Cookie->write('template_style', $template_style);
        $this->set('template_style', $template_style);

        /*
            微信同步登录参数配置
        */
        $wechat_info = $this->UserApp->find('first', array('conditions' => array('UserApp.type' => 'Wechat', 'UserApp.status' => 1)));
        if (!empty($wechat_info)) {
            $this->wechat_loginobj = array(
                'appid' => $wechat_info['UserApp']['app_key'],
                'redirect_uri' => urlencode($this->server_host.'/synchros/wechatcallback'),
                'state' => time(),
            );
            $this->set('wechat_loginobj', $this->wechat_loginobj);
        }

        /*
            微信访问
        */
        if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            //微信访问
            $this->loadModel('OpenModel');
            $this->loadModel('OpenConfig');
            $open_wechat_info = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type' => 'wechat', 'OpenModel.status' => 1), 'order' => 'OpenModel.id'));
            if (!empty($open_wechat_info)) {
                $open_config_cond = array(
                    'open_type' => 'wechat',
                    'open_type_id' => $open_wechat_info['OpenModel']['open_type_id'],
                );
                $open_config = $this->OpenConfig->tree($open_config_cond, LOCALE);
                $this->set('open_config', $open_config);
                $this->set('open_wechat_info', $open_wechat_info['OpenModel']);
            }
        }
    }

    public function afterFilter()
    {
    }

    /**
     *页面初始化todo.
     */
    public function page_init($params = '')
    {
        if ($this->params['controller'] == 'users' && $this->params['action'] == 'reset_password' ||
                $this->params['controller'] == 'users' && $this->params['action'] == 'forget_password' ||
        $this->params['controller'] == 'users' && $this->params['action'] == 'login' ||
         $this->params['controller'] == 'users' && $this->params['action'] == 'register' ||
          ($this->params['controller'] == 'carts' && $this->params['action'] == 'index' && (isset($_GET['ajax']) && $_GET['ajax'] == 1))
          ) {
            unset($_SESSION['back_url']);
        } else {
            $_SESSION['login_back'] = $this->here;
        }

        //取当前模板
        $template = $this->Template->find('first', array('conditions' => array('is_default' => 1), 'fields' => array('Template.name')));
        $template_name = empty($template) ? 'none' : $template['Template']['name'];
        $ad_position = $this->AdvertisementPosition->find('list', array('cache' => $this->short, 'conditions' => array('template_name' => $template_name), 'fields' => array('code')));
        $positionidarr = array();
        foreach ($ad_position as $k => $v) {
            $positionidarr[] = $k;
        }
        $advertisement_list = $this->Advertisement->findAvailableList($positionidarr);
        foreach ($ad_position as $k => $v) {
            $this->data['advertisement_list'][$v] = !empty($advertisement_list[$k]) ? $advertisement_list[$k] : array();
        }
        if (isset($this->data['advertisement_list'])) {
            $this->set('advertisement_lists', $this->data['advertisement_list']);
        }

        //商品分类树
        $this->CategoryProduct->tree('P', 0, $this->model_locale['category']);

        $this->set('product_categories_tree', $this->CategoryProduct->allinfo['P']['tree']);
        //var_dump($this->CategoryProduct->allinfo);
        $this->set('product_categories_assoc', $this->CategoryProduct->allinfo['P']['assoc']);

        //文章分类树
        $this->CategoryArticle->tree('A', 0, $this->model_locale['category']);
        $this->set('article_categories_tree', $this->CategoryArticle->allinfo['A']['tree']);
        $this->article_categories_assoc = $this->CategoryArticle->allinfo['A']['assoc'];
        $this->set('article_categories_assoc', $this->CategoryArticle->allinfo['A']['assoc']);
        $this->article_categories_subids = $this->CategoryArticle->allinfo['A']['subids'];
        //pr($this->CategoryArticle->tree('A',0,$this->model_locale['category']));
        $this->set('article_categories_subids', $this->CategoryArticle->allinfo['A']['subids']);

        //友情链接
            $links = $this->Link->find('all', array('cache' => $this->short, 'conditions' => array('Link.status' => 1), 'fields' => array('Link.id', 'Link.target', 'LinkI18n.url', 'LinkI18n.img01', 'LinkI18n.name', 'LinkI18n.locale')));
        $this->set('links', $links);

        //热门关键字'
        if (!empty($this->configs['home_search_keywords'])) {
            $hot_search_keywords = explode(' ', trim($this->configs['home_search_keywords']));
            $this->set('hot_search_keywords', $hot_search_keywords);
        }

        if (!isset($_SESSION['User']['User']['name']) && isset($_COOKIE['user_info']) && !empty($_COOKIE['user_info'])) {
            $user_info = unserialize(stripslashes($_COOKIE['user_info']));
                //填写的用户信息到session
                $_SESSION['User']['User'] = $user_info['User'];
        }

        $this->loadModel('PageModule');
        $this->loadModel('PageType');
            //应用装过后载入pagemodel
            $this->loadModel('PageAction');
        if (isset($_SESSION['template_use'])) {
            $template = $_SESSION['template_use'];
        } else {
            $template = $template_name;
        }
        $is_mobile_status = $this->is_mobile ? '1' : '0';
        $page_type = $this->PageType->find('first', array('conditions' => array('status' => '1', 'page_type' => $is_mobile_status, 'code' => $this->theme)));
        if (!empty($page_type)) {
            $page_info = $this->PageAction->find('first', array('conditions' => array('controller' => $this->params['controller'], 'action' => $this->params['action'], 'status' => '1', 'page_type_id' => $page_type['PageType']['id'])));
        }
            //pr($page_info);
            if (isset($page_info) && !empty($page_info)) {
                $_SESSION['custom_page'] = true;
                //根据code集合，获取不同位置的模块 code 信息
                $pageaction_id = $page_info['PageAction']['id'];
                $position_moduel_infos = $this->PageModule->get_position_moduels($this->locale, $pageaction_id);
                //pr($position_moduel_infos);
                $conditions = array();
                $conditions['PageModule.page_action_id'] = $pageaction_id;
                //取模块详细信息 code信息 id code对应关系
                $module_infos = $this->PageModule->get_module_infos($this->locale, $conditions);
                $module_style_infos = preg_replace('/\.\.\//', Configure::read('themes_host').'/theme/default/', $page_type['PageType']['css']);

                $code_infos = $module_infos['code_infos'];
                $id_codes_infos = $module_infos['id_code_infos'];
                $PageModules = array();
                $subPageModules = array();
                $params['controller'] = $this->params['controller'];
                $params['action'] = $this->params['action'];
                //pr($module_infos['module_infos']);
                foreach ($module_infos['module_infos'] as $k => $m) {
                    //不是父节点 且 model不为空的情况
                    if ($m['PageModule']['type'] != 'module_parent' && $m['PageModule']['model'] != '') {
                        //pr($m);
                        //参数设置
                        $params['custom'] = '';
                        if (!empty($m['PageModule']['limit']) && $m['PageModule']['limit'] != '') {
                            $params['limit'] = $m['PageModule']['limit'];
                        }
                        if (!empty($m['PageModule']['orderby_type']) && $m['PageModule']['limit'] != '') {
                            $params['order'] = $m['PageModule']['orderby_type'];
                        }

                        $params['type'] = $m['PageModule']['type'];
                        if ($m['PageModule']['type_id'] != '') {
                            $params['type_id'] = $m['PageModule']['type_id'];
                        } else {
                            unset($params['type_id']);
                        }
                        if ($m['PageModule']['parameters'] != '') {
                            $params['parameters'] = $m['PageModule']['parameters'];
                        } else {
                            unset($params['parameters']);
                        }
                        $this->loadModel($m['PageModule']['model']);
                        $this_module_infos = @$this->$m['PageModule']['model']->$m['PageModule']['function']($params);
                        if ($m['PageModule']['parent_id'] == 0) {
                            //获取一级模块信息
                            $PageModules[$m['PageModule']['position']][$m['PageModule']['code']] = $this_module_infos;
                        } elseif (isset($id_codes_infos[$m['PageModule']['parent_id']])) {
                            //获取二级模块信息
                            $subPageModules[$id_codes_infos[$m['PageModule']['parent_id']]][$m['PageModule']['position']][$m['PageModule']['code']] = $this_module_infos;
                        }
                    }
                }
                $this->set('pageaction_id', $pageaction_id);
                $this->set('module_style_infos', $module_style_infos);
                $this->set('position_moduel_infos', $position_moduel_infos);
                $this->set('module_infos', $module_infos['module_infos']);
                $this->set('code_infos', $code_infos);
                $this->set('PageModules', $PageModules);
                $this->set('subPageModules', $subPageModules);
                if (!empty($page_info['PageAction']['layout'])) {
                    $this->set('layout', $page_info['PageAction']['layout']);
                    //pr($page_info['PageAction']['layout']);
                    $this->page_layout = $page_info['PageAction']['layout'];
                }
            }
    }

    /**
     *调用action之后，调用ctp之前.
     */
    public function beforeRender()
    {
        if (!empty($this->pageTitle)) {
            $this->set('title_for_layout', $this->pageTitle);
        }
        $this->set('ur_heres', $this->ur_heres);

        //所有查到的商品初始化（商品多语言，价格,及set）
        $this->product_init();

        if (isset($this->product_order_field)) {
            $this->set('product_order_field', $this->product_order_field);
        }

        //页面压缩
        if (@$this->gzip_enabled() && false) {
            @$this->set('gzip_is_start', 1);
            @ob_start('ob_gzhandler');
        } else {
            @$this->set('gzip_is_start', 0);
            @ob_start();
        }

        //性能统计，占用内存
        $this->data['memory_useage'] = number_format((memory_get_usage() / 1024), 3, '.', '');
        $db = &ConnectionManager::getDataSource($this->useDbConfig);
        $this->set('queriesCnt', $db->_queriesCnt);
        $this->set('queriesTime', $db->_queriesTime);
        $this->_setErrorLayout();

        //判断是都是模块化的页面
    }

    public function render($action = null, $layout = null, $file = null)
    {  
    	if(isset($this->render)&&!empty($this->render)){
		if (isset($this->render['layout'])) {
			$layout = $this->render['layout'];
		}
		
		if (isset($this->render['action'])) {
			$action = $this->render['action'];
		}
    	}else if (isset($_SESSION['custom_page']) && $_SESSION['custom_page']) {
            // if in mobile mode, check for a valid view and use it
                if ($this->is_mobile) {
                    $layout = '/mobile/default_full';
                    $action = DS.strtolower($this->name).'/mobile/'.$this->action;
                   //     $this->render(DS.strtolower($this->name).'/mobile/'.$this->action);
                } else {
                    if (isset($this->page_layout)) {
                        //pr($this->page_layout);
                            $this->layout = $this->page_layout;
                    } else {
                        $this->layout = 'default_full';
                    }
                    $action = '/elements/custom_page';
                //		$this->render('/elements/custom_page');
                }
        }

        $out = parent::render($action, $layout);
        if(defined(CDN_PATH) && CDN_PATH!="")
	 $out = $this->replacePicUrl($out,CDN_PATH);
        return $out;
    }

    /**
     * 获得系统是否启用了 gzip.
     *
     *@return $enabled_gzip 输出
     */
    public function gzip_enabled()
    {
        static $enabled_gzip = null;
        if ($enabled_gzip === null) {
            $enabled_gzip = ($this->configs['enable_gzip'] && function_exists('ob_gzhandler'));
        }

        return $enabled_gzip;
    }

    /**
     *获得模板信息.
     *
     *@param $template_name 输入
     *@param $theme_dir 输入
     *
     *@return $info 输出
     */
    public function get_template_info($template_name, $theme_dir)
    {
        //pr($template_name);
        $info = array();
        $ext = array('png', 'gif', 'jpg', 'jpeg');
        $info['code'] = $template_name;
        $info['screenshot'] = '';
        if (file_exists($theme_dir.$template_name.'/readme.txt') && !empty($template_name)) {
            $arr = array_slice(file($theme_dir.$template_name.'/readme.txt'), 0, 8);
            $template_name = explode(': ', $arr[0]);
            //pr($template_name);
            $template_style = explode(': ', $arr[7]);
            $template_uri = explode(': ', $arr[1]);
            $template_desc = explode(': ', $arr[2]);
            $template_version = explode(': ', $arr[3]);
            $template_author = explode(': ', $arr[4]);
            $author_uri = explode(': ', $arr[5]);
            $template_description = explode(': ', $arr[6]);
            $info['description'] = isset($template_description[1]) ? trim($template_description[1]) : '';
            $info['style'] = explode(',', $template_style[1]);
            $info['name'] = isset($template_name[1]) ? trim($template_name[1]) : '';
            $info['uri'] = isset($template_uri[1]) ? trim($template_uri[1]) : '';
            $info['desc'] = isset($template_desc[1]) ? trim($template_desc[1]) : '';
            $info['version'] = isset($template_version[1]) ? trim($template_version[1]) : '';
            $info['author'] = isset($template_author[1]) ? trim($template_author[1]) : '';
            $info['author_uri'] = isset($author_uri[1]) ? trim($author_uri[1]) : '';
        } else {
            $info['description'] = '';
            $info['style'] = '';
            $info['name'] = '';
            $info['uri'] = '';
            $info['desc'] = '';
            $info['version'] = '';
            $info['author'] = '';
            $info['author_uri'] = '';
        }
        $screenshot = isset($info['style'][0]) ? 'screenshot_'.$info['style'][0] : 'screenshot';
        foreach ($ext as $val) {
            if (file_exists($theme_dir.$info['code']."/{$screenshot}.{$val}")) {
                $info['screenshot'] = '/themed/'.$info['code']."/{$screenshot}.{$val}";
                break;
            }
        }

        return $info;
    }

    /**
     *用户中心Head下方订单状态结果 todo.
     *
     *@param $user_id输入
     *无输出
     */
    public function getHeadBarInformation($user_id)
    {
        $user = $this->User->find('first', array('cache' => $this->short, 'conditions' => array('User.id ' => $user_id), 'fields' => array('User.rank', 'User.name', 'User.last_login_time')));
        $user_rank = '';
        /*取得用户等级
        if($user['User']['rank'] == 0) {
            $user_rank= $this->ld['normal'].$this->ld['user'];//假的
        } else {
            $user_rank = $this->UserRank->get_rank($user['User']['rank'],LOCALE);
        }*/
        $this->set('last_login', $user['User']['last_login_time']);//最后登录时间
        $this->set('user_rank', $user_rank);//用户等级
        $this->set('user_name', $user['User']['name']);//用户
    }

    /**
     *登录验证.
     */
    public function checkSessionUser()
    {
        if (!isset($_SESSION['User'])) {
            $user_id = $_SESSION['User']['User']['id'];
            $this->UserRankLog->checkUserRank($user_id);
            $_SESSION['login_back'] = $this->here;
            $this->redirect('/users/login');
        }
    }

    /*
     *设定语言
     *
     */
    public function set_locale()
    {
        $this->languages = $this->Language->findalllang();
        foreach ($this->languages as $k => $v) {
            if ($v['Language']['is_default'] == 1) {
                $default_lng = $v['Language']['locale'];
            }
        }
        if (!isset($default_lng)) {
            foreach ($this->languages as $k => $v) {
                //pr($v);
                if ($v['Language']['front'] == 1) {
                    $default_lng = $v['Language']['locale'];
                }
                break;
            }
        }
        $this->languages_assoc = $this->Language->findalllang_assoc();
        if ($this->Cookie->read('lng') != '' && !in_array($this->params['controller'], array('products'))) {
            //define('LOCALE',$this->Cookie->read('lng'));
        }
        if (!defined('LOCALE')) {
            define('LOCALE', $default_lng);
        } elseif (defined('LOCALE') && in_array(LOCALE, $this->languages_assoc)) {        //目录式多语言
        } else {
            //todo 跳转到默认语言首页
            echo '这个语言未开通';
            die();
        }
        $this->locale = LOCALE;
        //$_SESSION['lo']=LOCALE;
        $this->Cookie->write('lng', LOCALE, time() + 3600 * 24 * 365);
        //设置当前路径的不同语言链接
        $request_uri = $_SERVER['REQUEST_URI'];
        if (!empty($this->languages[LOCALE]['Language']['map'])) {
            $request_uri = str_replace('/'.$this->languages[LOCALE]['Language']['map'].'/', '', $request_uri);
        }
        foreach ($this->languages as $k => $v) {
            $this->languages[$k]['Language']['url'] = $this->server_host.(empty($v['Language']['map']) ? '' : '/'.$v['Language']['map'].'/').$request_uri;
        }
        $this->set('languages', $this->languages);
        //单语言模型处理
        if (isset($this->configs['default_language_model']) && $this->configs['default_language_model'] != '') {
            $default_language_model = $this->configs['default_language_model'];
        } else {
            $default_language_model = '';
        }

        $single_model = explode(',', $default_language_model);
        foreach ($this->model_locale as $k => $v) {
            if (in_array($k, $single_model)) {
                $this->model_locale[$k] = $default_lng;
                if ($k == 'product') {
                    $this->Product->set_locale($default_lng);
                    $this->ProductTypeAttribute->set_locale($default_lng);
                }
                if ($k == 'category') {
                    $this->CategoryProduct->set_locale($default_lng);
                    $this->CategoryArticle->set_locale($default_lng);
                }
                if ($k == 'brand') {
                    $this->Brand->set_locale($default_lng);
                }
            }
        }
    }

    /**
     *初始化，商品多语言，会员等级价.
     */
    public function product_init()
    {
        //if(empty($this->Product->ids))
        //	return;
        $this->Product->productI18ns_list = array();//商品多语言
        $this->Product->product_ranks = array();
        $this->Product->user_rank_list = array();
        //var_dump($this->Config);
        //$products_comments = $this->Comment->find_comment_rank($this->Product->ids);//取商品评论平均值和评论人数
        $this->Product->productI18ns_list = $this->ProductI18n->find_productI18ns($this->Product->ids, $this->model_locale['product']);//model
    /*	$this->Product->product_ranks = $this->ProductRank->find_rank_by_product_ids($this->Product->ids);
    //	$this->Product->user_rank_list=$this->UserRank->findrank();

        if(isset($this->Product->product_ranks) && sizeof($this->Product->product_ranks)>0){
              foreach($this->Product->product_ranks as $k=>$v){
                    if(isset($v) && sizeof($v)>0){
                         foreach($v as $kk=>$vv){
                              if($vv['ProductRank']['is_default_rank'] == 1){
                              }			  	  	 			  	  	 	 	$this->Product->product_ranks[$k][$kk]['ProductRank']['discount'] = ($this->Product->user_rank_list[$vv['ProductRank']['rank_id']]['UserRank']['discount']/100);

                         }
                    }
              }
        }	*/
        $this->Product->products_name_length = empty($this->configs['products_name_length']) ? 20 : $this->configs['products_name_length'];
        foreach ($this->Product->viewVars as $depth => $vars) {
            if ($depth == 0) {
                foreach ($vars as $k => $v) {
                    //cdn
                    $v = $this->cdn_img($v);
                    $this->set($k, $this->Product->product_locale_format($v));
                }
            } elseif ($depth == 1) {
                foreach ($vars as $k => $v) {
                    foreach ($v as $kk => $vv) {
                        //cdn
                        $vv = $this->cdn_img($vv);
                        $v[$kk] = $this->Product->product_locale_format($vv);
                    }
                    $this->set($k, $v);
                }
            } elseif ($depth == 2) {
                foreach ($vars as $k => $v) {
                    foreach ($v as $kk => $vv) {
                        foreach ($vv as $kkk => $vvv) {
                            //cdn
                            $vvv = $this->cdn_img($vvv);
                            $v[$kk][$kkk] = $this->Product->product_locale_format($vvv);
                        }
                    }
                    $this->set($k, $v);
                }
            } elseif ($depth == 3) {
                foreach ($vars as $k => $v) {
                    foreach ($v as $kk => $vv) {
                        foreach ($vv as $kkk => $vvv) {
                            foreach ($vvv as $kkkk => $vvvv) {
                                //cdn
                                $vvvv = $this->cdn_img($vvvv);
                                $v[$kk][$kkk][$kkkk] = $this->Product->product_locale_format($vvvv);
                            }
                        }
                    }
                    $this->set($k, $v);
                }
            }
        }
        $this->Product->viewVars = array();
    }

    public function _setErrorLayout()
    {
        if ($this->name == 'CakeError') {
            $this->beforeFilter();
            if (Configure::read('debug') == 0) {
                $this->layout = 'error';
            }
            //$this->render('/errors/missing_controller.ctp');
        }
    }

    /**
     *将外网商品图片路径更换为cdn路径.
     */
    public function cdn_img($pro)
    {
        //cdn
        if ($this->Config->hasOne['ConfigI18n']['conditions']['locale'] == 'chi') {
            $pro['Product']['img_thumb'] = str_replace('img.ioco.cn', 'img.seeworlds.cn', $pro['Product']['img_thumb']);
        } else {
            $pro['Product']['img_thumb'] = str_replace('img.ioco.cn', 'img.seeworlds.com', $pro['Product']['img_thumb']);
        }

        return $pro;
    }
    //zhou add to change the ld
    public function change_ld_wap($locale = 'chi')
    {
        $ld = $this->LanguageDictionary->getformatcodewap($locale);
        $this->set('ld', $ld);
    }

    public function is_thm_sb()
    {
        $thm = Configure::read('themes_host');
        $uthm = isset($_GET['themes']) ? $_GET['themes'] : $_SESSION['template_use'];
        $url = $thm.'/themed/'.$uthm.'/css/common.css';
        $Headers = get_headers($url);
        if (@preg_match('|404|', $Headers[0])) {
            //off
            return false;
        } else {
            //in
            return true;
        }
    }
    
    function clean_xss($mix){
	    	if(is_array($mix)){
	    		foreach($mix as $k=> $v){
	    			
	    			$str = $this->clean_xss($v);
	    			$mix[$k]=$str;
	    		}
			return $mix;
		}else if(is_string($mix)){
		//	pr($mix);
			$str = trim($mix);  //清理空格
			$str = strip_tags($str);   //过滤html标签
			$str = htmlspecialchars($str,ENT_QUOTES);   //过滤html标签'
		//	pr($str);
			return $str;
		}
	}
	
	/*
		检查版本信息
	*/
	function check_version(){
		if(isset($this->configs['version'])&&defined('Version')){
			$version_config=$this->configs['version'];
			if($version_config!=Version){
				$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
			        $host = 'http://'.$host;
			        header('Location:'.$host.'/tools/upgrades');
			        exit();
			}
		}
	}
	
			/** 
	 * @param  string $content 要替换的内容 
	 * @param  string $strUrl 内容中图片要加的域名 
	 * @return string  
	 * @eg  
	 */  
	function replacePicUrl($content = null, $strUrl = null) {  
	    if ($strUrl) {  
	        //提取图片路径的src的正则表达式 并把结果存入$matches中    
	        preg_match_all("/<img(.*)src=\"\/([^\"]+)\"[^>]+>/isU",$content,$matches);  
	        $img = "";    
	        if(!empty($matches)) {    
	        //注意，上面的正则表达式说明src的值是放在数组的第三个中    
	        $img = $matches[2];    
	        }else {    
	           $img = "";    
	        }  
	          if (!empty($img)) {    
	                $patterns= array();    
	                $replacements = array();    
	                pr($img);
	                foreach($img as $imgItem){    
	                    $final_imgUrl = $strUrl.$imgItem;    
	                    $replacements[] = $final_imgUrl;    
	                    $img_new = "/".preg_replace("/\//i","\/","/".$imgItem)."/";    
	                    $patterns[] = $img_new;    
	                }    
	                //让数组按照key来排序    
	                ksort($patterns);    
	                ksort($replacements);    
	    
	                //替换内容    
	                $vote_content = preg_replace($patterns, $replacements, $content);  
	          
	                return $vote_content;  
	        }else {  
	            return $content;  
	        }                     
	    } else {  
	        return $content;  
	    }  
	}  
	
	
}
?>