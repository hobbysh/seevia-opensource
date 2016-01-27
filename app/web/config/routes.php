<?php
    $short = array('config' => 'short','use' => true);
    if (!defined('LOCALE')) {
        $lngsModel = ClassRegistry::init('Language');
        $lngs = $lngsModel->find('first', array('cache' => $short, 'conditions' => array('is_default' => '1'), 'fields' => array('Language.locale', 'Language.front')));
        if ($lngs['Language']['front'] == 0) {
            $lngs2 = $lngsModel->find('first', array('cache' => $short, 'conditions' => array('front' => '1'), 'fields' => array('Language.locale', 'Language.front')));
            define('LOCALE', $lngs2['Language']['locale']);
        } else {
            define('LOCALE', $lngs['Language']['locale']);
        }
    }

    Router::connect('/closed', array('controller' => 'users', 'action' => 'closed'));
    Router::parseExtensions('rss', 'xml');
    Router::connect('/sitemaps', array('controller' => 'sitemaps', 'action' => 'index'));
    Router::connect('/sitemap', array('controller' => 'sitemaps', 'action' => 'view'));
    Router::connect('/:controller/:id/*', array('action' => 'view'), array('pass' => array('id'), 'id' => '[0-9]+'));

/*	
    $configModel = ClassRegistry::init('Config'); 
    $all_infos=$configModel->find('first',array('cache'=>$short,'conditions' => array('Config.code'=>'home-path')));
    
    @todo
    根据$configs['ConfigI18n']['value']值的定义来判断 生成route变量
    购物首页 HOME array('controller' => 'pages', 'action' => 'home')
    咨询页 CMS array('controller' => 'articles', 'action' => 'home')
    广告页 CUSTOMER array('controller' => 'pages', 'action' => 'homepage')
    商品分类 PC:id array('controller' => 'products', 'action' => 'view',':id'))
    商品 PRODUCT:id array('controller' => 'products', 'action' => 'home',':id')
    文章分类 AC:id array('controller' => 'articles', 'action' => 'view',':id'))
    文章 ARTICLE:id array('controller' => 'articles', 'action' => 'home',':id')
    专题列表 TOPICS array('controller' => 'pages', 'action' => 'home')
    专题页 TOPIC:id array('controller' => 'pages', 'action' => 'home',':id')
    促销列表 PROMOTIONS array('controller' => 'pages', 'action' => 'home')
    促销页 PROMOTION:id array('controller' => 'pages', 'action' => 'home',':id')
    登录 LOGIN array('controller' => 'pages', 'action' => 'login')

   $value=@explode(':',$all_infos['ConfigI18n']['value']);   
    switch($value['0']){
        case 'HOME':
                Router::connect('/', array('controller' => 'pages', 'action' => 'home'));break;
        case 'HOMEONE':
                Router::connect('/', array('controller' => 'pages', 'action' => 'homeone'));break;
        case 'HOMETWO':
                Router::connect('/', array('controller' => 'pages', 'action' => 'hometwo'));break;
        case 'HOMETHREE':
                Router::connect('/', array('controller' => 'pages', 'action' => 'homethree'));break;						
        case 'TOPICHOME':
                Router::connect('/', array('controller' => 'pages', 'action' => 'hometopics'));break;		
        case 'CMS':
                Router::connect('/', array('controller' => 'articles', 'action' => 'home'));break;
        case 'CUSTOMER':
                Router::connect('/', array('controller' => 'pages', 'action' => 'homepage'));break;
        case 'PC':
                Router::connect('/', array('controller' => 'categories', 'action' => 'view',$value['1']));break;
        case 'PRODUCT':
                Router::connect('/', array('controller' => 'products', 'action' => 'view',$value['1']));break;
        case 'AC':
                Router::connect('/', array('controller' => 'categories', 'action' => 'view',$value['1']));break;
        case 'ARTICLE':
                Router::connect('/', array('controller' => 'articles', 'action' => 'view',$value['1']));break;
        case 'TOPICS':
                Router::connect('/', array('controller' => 'topics', 'action' => 'index'));break;	
        case 'TOPIC':
                Router::connect('/', array('controller' => 'topics', 'action' => 'view',$value['1']));break;
        case 'PROMOTIONS':
                Router::connect('/', array('controller' => 'promotions', 'action' => 'index'));break;	
        case 'PROMOTION':
                Router::connect('/', array('controller' => 'promotions', 'action' => 'view',$value['1']));break;		
        case 'LOGIN':
                Router::connect('/', array('controller' => 'users', 'action' => 'login'));break;
        case 'CUSTOM_MADE':
                Router::connect('/', array('controller' => 'pages', 'action' => 'custom_made'));break;
        default:
                Router::connect('/', array('controller' => 'pages', 'action' => 'home'));break;
    }
*/

    $RoutesModel = ClassRegistry::init('Route');
    $home_infos = $RoutesModel->find('first', array('cache' => $short, 'conditions' => array('Route.url' => '/')));
    if (empty($home_infos)) {
        Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
    } else {
        //pr($home_infos);首页设置了替换路径
        Router::connect('/', array('controller' => $home_infos['Route']['controller'], 'action' => $home_infos['Route']['action'], $home_infos['Route']['model_id']));
    }
    $all_infos = $RoutesModel->find('all', array('cache' => $short, 'conditions' => array('Route.status' => '1')));
    foreach ($all_infos as $k => $v) {
        Router::connect('/'.$v['Route']['url'], array('controller' => $v['Route']['controller'], 'action' => $v['Route']['action'], $v['Route']['model_id']));
    }
    $tva = ClassRegistry::init('Application')->find('first', array('cache' => $short, 'conditions' => array('Application.code' => 'APP-TRAVEL'), 'fields' => array('Application.code')));
    //var_dump($tva);
    if (!empty($tva)) {
        $td = ClassRegistry::init('TravelDestination')->find('list', array('conditions' => array('TravelDestination.status' => 1), 'fields' => array('TravelDestination.id', 'TravelDestination.self_link')));
        if (!empty($td)) {
            foreach ($td as $tk => $tu) {
                if (!empty($tu)) {
                    Router::connect($tu, array('controller' => 'travel_destinations', 'action' => 'view', $tk));
                }
            }
        }
    }
    Router::connect('/api/uc', array('controller' => 'ucs', 'action' => 'index'));
    Router::connect('/wap', array('controller' => 'waps', 'action' => 'index'));
    Router::connect('/soap/:controller/:action/*', array('prefix' => 'soap', 'soap' => true));
