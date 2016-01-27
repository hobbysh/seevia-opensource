<?php

/*****************************************************************************
 * Seevia 模块管理
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
class PageModulesController extends AppController
{
    public $name = 'PageModules';
    public $helpers = array('Html','Pagination','Tinymce','fck','Form','Javascript','Ckeditor');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('PageModule','PageModuleI18n','Resource','SeoKeyword','CategoryArticle','InformationResource','Template','Template','PageAction','PageType','OperatorLog','CategoryProduct');
    public $module_types = array('module_parent' => '父模块','module_article' => '文章','module_product' => '商品','module_article_category' => '文章分类','module_product_category' => '商品分类','module_brand' => '品牌','module_topic' => '专题','module_flash' => 'flash轮播','module_promotion' => '促销信息(商品)','module_help_information' => '帮助信息','module_hotel_recommends' => '热门酒店推荐','module_hotel_promotions' => '促销信息(酒店)','module_hotel_search' => '酒店搜索模块','module_home_help' => '帮助导航','module_trip_destination' => '目的地简介','module_trip_recommend' => '线路推荐','module_trip_img' => '景点图片','module_microblog' => '微博动态','module_weather' => '天气预报','module_traffic' => '飞行交通','module_trip_note' => '旅游小贴士','module_trip_list' => '行程列表','module_trip_view' => '行程详细','module_hotel_list' => '酒店列表','module_site_nearby' => '附近景点','module_trip_hot' => '热门线路','module_hotel_view' => '酒店详细','module_advertisement' => '广告模块','module_notice' => '公告模块','module_link' => '外部链接','module_user' => '会员模块','module_comment' => '评论模块');
    public $module_position = array('top' => '顶部','left' => '左边','right' => '右边');
    public $module_ordertype = array('module_article' => array('created desc' => '按时间递减','orderby' => '按排序递增'),'module_product' => array('created desc' => '按时间递减','price desc' => '按价格递减','code asc' => '按货号递增'),'module_article_category' => array('orderby' => '按排序递增'),'module_product_category' => array('orderby' => '按排序递增'),'module_brand' => array('id desc' => '按品牌id递减','code asc' => '按品牌编码递增','created desc' => '按创建时间递减'),'module_topic' => array('end_time desc' => '按结束时间递减','orderby' => '按排序递增'),'module_journey' => array('created' => '按时间递减'),'module_attraction' => array('orderby' => '按排序递增'),'module_flash' => array('orderby' => '按排序递增'),'module_promotion' => array('end_time desc' => '按促销结束时间递减'),'module_help_information' => array('orderby' => '按排序递增'),'module_hotel_information' => array('orderby' => '按排序递增'));

    //页面列表
    public function index()
    {
        /*判断权限*/
        $this->operator_privilege('modules_view');
        $this->menu_path = array('root' => '/cms/','sub' => '/page_modules/');
        /*end*/
        $this->set('title_for_layout', $this->ld['module_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['module_management'],'url' => '/page_modules/');

        $conditions = array();

        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $conditions['and']['or']['PageAction.controller like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['PageAction.action like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['PageAction.name like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
        if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {
            $conditions['and']['PageAction.status'] = $_REQUEST['status'];
            $this->set('status', $_REQUEST['status']);
        }
        $page_list = $this->PageAction->find('all', array('conditions' => $conditions));
        $this->set('page_list', $page_list);
    }
    //页面编辑
    public function view($id = 0)
    {
        $this->menu_path = array('root' => '/cms/','sub' => '/page_modules/');
        $this->set('title_for_layout', $this->ld['add_edit_page'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['module_management'],'url' => '/page_modules/');
        $this->navigations[] = array('name' => $this->ld['add_edit_page'],'url' => '/page_modules/');

        if ($this->RequestHandler->isPost()) {
            $this->PageAction->save($this->data);
            $this->redirect('/page_modules/');
        }
            //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_edit_page'].':id '.$id, $this->admin['id']);
        }
        $this->data = $this->PageAction->find('first', array('conditions' => array('PageAction.id' => $id)));
    }
    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('modules_remove');
        /*end*/
        //删除页面下样样式 模块
        $page_style_codes = $this->PageType->find('list', array('conditions' => array('id' => $id), 'fields' => 'PageType.code'));
        $page_module_ids = $this->PageModule->find('list', array('conditions' => array('PageModule.code' => $page_style_codes), 'fields' => 'PageModule.id'));
        $this->PageModule->deleteAll(array('PageModule.id' => $page_module_ids));
        $this->PageModuleI18n->deleteAll(array('PageModuleI18n.module_id' => $page_module_ids));
        $this->PageType->deleteAll(array('PageType.id' => $id));
        $this->PageAction->deleteAll(array('PageAction.id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除页面 样式 模块:id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_the_ad_list_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
        //$this->redirect('/page_modules');
    }
    //页面样式列表
    public function page_style_list($id = '')
    {
        $page_info = $this->PageAction->find('first', array('conditions' => array('PageAction.id' => $id), 'fields' => 'PageAction.name'));
        $this->menu_path = array('root' => '/cms/','sub' => '/page_modules/');
        /*end*/
        $this->set('title_for_layout', $page_info['PageAction']['name'].' - '.$this->ld['style_list'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['module_management'],'url' => '/page_modules/');
        $this->navigations[] = array('name' => $page_info['PageAction']['name'].' - '.$this->ld['style_list'],'url' => '/page_modules/page_style_list/'.$id);

        if ($id != '' && $id != 0) {
            $page_style_list = $this->PageType->find('all', array('conditions' => array('PageType.id' => $id)));
        } else {
            $page_style_list = $this->PageType->find('all');
        }
        $tem = $this->Template->find('list', array('fields' => 'Template.name,Template.description'));
        $this->set('tem', $tem);
        $this->set('id', $id);
        $this->set('page_style_list', $page_style_list);
    }
    //页面样式列表
    public function page_style_view($id = 0)
    {
        $id = '';
        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
        }
        $this->menu_path = array('root' => '/cms/','sub' => '/page_modules/');
        $this->data = $this->PageType->find('first', array('conditions' => array('PageType.id' => $id)));
        if ($id != 0) {
            $id = $this->data['PageType']['id'];
        }
        $page_info = $this->PageAction->find('first', array('conditions' => array('PageAction.id' => $id), 'fields' => 'PageAction.name'));
        $this->set('id', $id);
        /*end*/
        $this->set('title_for_layout', $this->ld['add_edit_page'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['module_management'],'url' => '/page_modules/');
        $this->navigations[] = array('name' => $page_info['PageAction']['name'].'- '.$this->ld['style_list'],'url' => '/page_modules/page_style_list/'.$id);
        $this->navigations[] = array('name' => $this->ld['add_edit_page'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            //查询code是否重复
              $id = $_REQUEST['data']['PageType']['id'];
            $style_info = $this->PageType->find('all', array('conditions' => array('PageType.code' => $_REQUEST['data']['PageType']['code'], 'PageType.id <>' => $id)));
            if (!empty($style_info)) {
                $msg = '样式编码重复！';
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="/admin/page_modules/page_style_view/'.$id.'?id='.$id.'"</script>';
                die();
            }
            $style_info = $this->PageType->find('all', array('conditions' => array('PageType.id' => $_REQUEST['data']['PageType']['id'], 'PageType.template_code' => $_REQUEST['data']['PageType']['template_code'], 'PageType.id <>' => $id)));
            if (!empty($style_info)) {
                $msg = '页面模板样式重复！';
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="/admin/page_modules/page_style_view/'.$id.'?id='.$id.'"</script>';
                die();
            }
             //查询模板是否重复

             $this->PageType->save($_REQUEST['data']);
            $id = $_REQUEST['id'];
             //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑页面样式模板:id '.$id, $this->admin['id']);
            }
            $this->redirect('/page_modules/page_style_list/'.$id);
        }
        $tem_list = $this->Template->find('list', array('fields' => 'Template.name,Template.description'));
        $this->set('tem_list', $tem_list);
        $page_list = $this->PageAction->find('list', array('fields' => 'PageAction.id,PageAction.name'));
        $this->set('page_list', $page_list);
    }
    public function page_style_remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('modules_remove');
        /*end*/
        //删除样式 模块
        $code = $this->PageType->find('list', array('conditions' => array('id' => $id), 'fields' => 'PageType.code'));
        $page_module_ids = $this->PageModule->find('list', array('conditions' => array('PageModule.code' => $code), 'fields' => 'PageModule.id'));
        $this->PageModule->deleteAll(array('PageModule.id' => $page_module_ids));
        $this->PageModuleI18n->deleteAll(array('PageModuleI18n.module_id' => $page_module_ids));
        $this->PageType->deleteAll(array('PageType.id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除页面样式:id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = 'success';
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //模块列表
    public function module_list($code = '')
    {
        $conditions = array();
        if ($code != '') {
            $conditions['and']['PageModule.code'] = $code;
            $this->set('code', $code);
        }

        if (isset($_REQUEST['code']) && $_REQUEST['code'] != '-1') {
            if ($_REQUEST['code'] == 0) { //无样式对应的模块
                $style_list = $this->PageType->find('list', array('fields' => 'PageType.code'));
                $conditions['NOT']['PageModule.code '] = $style_list;
            }
            $conditions['and']['PageModule.code'] = $_REQUEST['code'];
            $this->set('code', $_REQUEST['code']);
            $code = $_REQUEST['code'];
        }
        /*判断权限*/
        $this->operator_privilege('modules_view');
        $page_style_info = $this->PageType->find('first', array('conditions' => array('PageType.code' => $code)));
        $page_info = $this->PageAction->find('first', array('conditions' => array('id' => $page_style_info['PageType']['id'])));
        /*end*/
        $this->set('title_for_layout', $this->ld['module_list'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['module_management'],'url' => '/page_modules/');
        $this->navigations[] = array('name' => $page_info['PageAction']['name'].'-'.$this->ld['style_list'],'url' => '/page_modules/page_style_list/'.$page_info['PageAction']['id']);
        $this->navigations[] = array('name' => $page_style_info['PageType']['name'].$this->ld['module_list'],'url' => '/page_modules/module_list/'.$code);

        $modules_tree = $this->PageModule->tree($this->locale, $conditions);
        $this->set('code', $code);
        $this->set('modules_tree', $modules_tree);
        $this->set('module_types', $this->module_types);
        $this->set('module_position', $this->module_position);
        $this->set('style_list', $this->PageType->find('list', array('fields' => 'PageType.code,PageType.name')));
    }

    public function module_view($id = 0, $code = '')
    {
        /*判断权限*/
        $this->operator_privilege('modules_edit');
        /*end*/
        $this->set('title_for_layout', $this->ld['module_edit'].' - '.$this->ld['module_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['module_management'],'url' => '/page_modules/');
        $this->set('code', $code);
        if (!empty($id)) {
            $this->data = $this->PageModule->localeformat($id);
            $page_style_info = $this->PageType->find('first', array('conditions' => array('PageType.code' => $this->data['PageModule']['code'])));
            $page_info = $this->PageAction->find('first', array('conditions' => array('id' => $page_style_info['PageType']['id'])));
            $this->navigations[] = array('name' => $page_info['PageAction']['name'].' - '.$this->ld['style_list'],'url' => '/page_modules/page_style_list/'.$page_info['PageAction']['id']);
            $this->navigations[] = array('name' => $page_style_info['PageType']['name'].'-'.$this->ld['module_list'],'url' => '/page_modules/module_list/'.$this->data['PageModule']['code']);
            $this->navigations[] = array('name' => $this->ld['module_edit'],'url' => '');
            $this->set('modules_info', $this->data);
            $this->data['PageModuleI18n'][$this->backend_locale]['name'] = empty($this->data['PageModuleI18n'][$this->backend_locale]['name']) ? '' : $this->data['PageModuleI18n'][$this->backend_locale]['name'];
            $this->navigations[] = array('name' => $this->data['PageModuleI18n'][$this->backend_locale]['name'],'url' => '');
        } elseif ($code != '') {
            $page_style_info = $this->PageType->find('first', array('conditions' => array('PageType.code' => $code)));
            $page_info = $this->PageAction->find('first', array('conditions' => array('id' => $page_style_info['PageType']['id'])));
            $this->navigations[] = array('name' => $page_info['PageAction']['name'].'-'.$this->ld['style_list'],'url' => '/page_modules/page_style_list/'.$page_info['PageAction']['id']);
            $this->navigations[] = array('name' => $page_style_info['PageType']['name'].'-'.$this->ld['module_list'],'url' => '/page_modules/module_list/'.$code);
            $this->navigations[] = array('name' => $this->ld['add_module'],'url' => '');
        }

        if ($this->RequestHandler->isPost()) {
            $_REQUEST['data']['PageModule']['orderby'] = !empty($_REQUEST['data']['PageModule']['orderby']) ? $_REQUEST['data']['PageModule']['orderby'] : 50;
            if ($_REQUEST['data']['PageModule']['type'] == 'module_article') {
                $_REQUEST['data']['PageModule']['model'] = 'Article';
            }
            if ($_REQUEST['data']['PageModule']['type'] == 'module_product') {
                $_REQUEST['data']['PageModule']['model'] = 'Product';
            }
            if ($_REQUEST['data']['PageModule']['type'] == 'module_article_category') {
                $_REQUEST['data']['PageModule']['model'] = 'Category';
            }
            if ($_REQUEST['data']['PageModule']['type'] == 'module_product_category') {
                $_REQUEST['data']['PageModule']['model'] = 'CategoryProduct';
            }
            if ($_REQUEST['data']['PageModule']['type'] == 'module_brand') {
                $_REQUEST['data']['PageModule']['model'] = 'Brand';
            }
            if ($_REQUEST['data']['PageModule']['type'] == 'module_topic') {
                $_REQUEST['data']['PageModule']['model'] = 'Topic';
            }
            if ($_REQUEST['data']['PageModule']['type'] == 'module_help_information') {
                $_REQUEST['data']['PageModule']['model'] = 'Article';
            }
            if ($_REQUEST['data']['PageModule']['type'] == 'module_promotion') {
                $_REQUEST['data']['PageModule']['model'] = 'Promotion';
            }
            $_REQUEST['data']['PageModule']['limit'] = ($_REQUEST['data']['PageModule']['limit'] == '') ? '10' : $_REQUEST['data']['PageModule']['limit'];
            $this->PageModule->save($_REQUEST['data']); //保存
            $id = $this->PageModule->getLastInsertId();
            $this->PageModuleI18n->deleteAll(array('module_id' => $id)); //删除原有多语言
            foreach ($_REQUEST['data']['PageModuleI18n'] as $v) {
                $moduleI18n_info = array(
                                   'id' => isset($v['id']) ? $v['id'] : '',
                                   'locale' => $v['locale'],
                                   'module_id' => !empty($v['module_id']) ? $v['module_id'] : $id,
                                   'name' => isset($v['name']) ? $v['name'] : '',
                                   'title' => isset($v['title']) ? $v['title'] : '',
                             );
                $this->PageModuleI18n->save(array('PageModuleI18n' => $moduleI18n_info));//更新多语言
            }

            foreach ($_REQUEST['data']['PageModuleI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['shop_edit_shop'].':'.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/page_modules/module_list/'.$_REQUEST['data']['PageModule']['code']);
        }

        $modules_tree = $this->PageModule->parent_tree($this->locale, $code);
        $this->set('modules_tree', $modules_tree);
        $this->set('module_ordertype', $this->module_ordertype);
        $this->set('module_types', $this->module_types);
        $this->set('module_position', $this->module_position);
        $product_category_tree = $this->CategoryProduct->tree('P', $this->locale);
        $article_category_tree = $this->CategoryArticle->tree('all', $this->locale);
        $this->set('product_category_tree', $product_category_tree);
        $this->set('article_category_tree', $article_category_tree);
        $Resource_info = $this->Resource->getformatcode(array('link_type'), $this->locale);
        $this->set('link_type', $Resource_info['link_type']);
        $style_list = $this->PageType->find('list', array('fields' => 'PageType.code,PageType.name'));
        $this->set('style_list', $style_list);
        //资源库信息
        //自定义类型获取
        $informationresource_info = $this->InformationResource->information_formated(array('flash_custom_type'), $this->locale);
        if (!empty($informationresource_info)) {
            $this->set('flash_type', $informationresource_info['flash_custom_type']);
        }
    }

    public function module_remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('modules_remove');
        /*end*/
        $page_info = $this->PageModule->find('first', array('conditions' => array('PageModule.id' => $id)));
        $this->PageModule->hasOne = array();
        $pn = $this->PageModuleI18n->find('list', array('fields' => array('PageModuleI18n.module_id', 'PageModuleI18n.name'), 'conditions' => array('PageModuleI18n.module_id' => $id, 'PageModuleI18n.locale' => $this->locale)));
        $this->PageModule->deleteAll(array('id' => $id));
        $this->PageModuleI18n->deleteAll(array('module_id' => $id));
        $module_data = $this->PageModule->find('all', array('conditions' => array('parent_id' => $id), 'fields' => 'id'));
        foreach ($module_data as $k => $v) {
            $this->PageModule->save(array('PageModule' => array('id' => $v['PageModule']['id'], 'parent_id' => 0)));
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_product_module'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $this->redirect('/page_modules/module_list/'.$page_info['PageModule']['code']);
    }

    public function act_view($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $code = $_POST['code'];
        $rname = '';
        $name_code = $this->PageModule->find('all', array('fields' => 'PageModule.code'));
        if (isset($name_code) && !empty($name_code)) {
            foreach ($name_code as $vv) {
                $rname[] = $vv['PageModule']['code'];
            }
        } else {
            $result['code'] = '1';
        }
        if ($id == 0) {
            if (isset($code) && $code != '') {
                if (in_array($code, $rname)) {
                    $result['code'] = '0';
                } else {
                    $result['code'] = '1';
                }
            }
        } else {
            $PageModule_count = $this->PageModule->find('first', array('conditions' => array('PageModule.id' => $id)));
            if ($PageModule_count['PageModule']['code'] != $code && in_array($code, $rname)) {
                $result['code'] = '0';
                //   $result['msg'] = "用户名重复";
            } else {
                $result['code'] = '1';
            }
        }
        die(json_encode($result));
    }
    public function toggle_on_page_status()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->PageAction->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function toggle_on_page_style_status()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->PageType->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function toggle_on_status()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->PageModule->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *批量处理.
     */
    public function batch($code = '')
    {
        $art_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        if (isset($this->params['url']['act_type']) && $this->params['url']['act_type'] != '0') {
            if ($this->params['url']['act_type'] == 'delete') {
                $this->PageModule->hasOne = array();
                $condition['PageModule.id'] = $art_ids;
                $this->PageModule->deleteAll($condition);
                $this->PageModuleI18n->deleteAll(array('module_id' => $art_ids));
                $module_data = $this->PageModule->find('all', array('conditions' => array('parent_id' => $art_ids), 'fields' => 'id'));
                foreach ($module_data as $k => $v) {
                    $this->PageModule->save(array('PageModule' => array('id' => $v['PageModule']['id'], 'parent_id' => 0)));
                }
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
                }
                $this->redirect('/page_modules/');
            }
            if ($this->params['url']['act_type'] == 'a_status') {
                $condition['PageModule.id'] = $art_ids;
                $this->PageModule->updateAll(array('PageModule.status' => $_REQUEST['is_yes_no']), array('PageModule.id' => $art_ids));
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
                }
                $this->redirect('/page_modules/module_list/'.$code);
            }
        } else {
            $this->redirect('/page_modules/module_list/'.$code);
        }
    }

    //列表箭头排序
    public function changeorder($updowm, $id, $nextone, $code)
    {
        //如果值相等重新自动排序
        $a = $this->PageModule->query('SELECT DISTINCT `parent_id`
			FROM `svcart_page_modules` as PageModule
			WHERE `code` = "'.$code.'"
			GROUP BY `orderby` , `parent_id`
			HAVING count( * ) >1');
        foreach ($a as $v) {
            $this->PageModule->Behaviors->attach('Containable');
            $all = $this->PageModule->find('all', array('conditions' => array('PageModule.parent_id' => $v['PageModule']['parent_id']), 'order' => 'PageModule.id asc', 'contain' => false));
            foreach ($all as $k => $vv) {
                $all[$k]['PageModule']['orderby'] = $k + 1;
            }
            $this->PageModule->saveAll($all);
        }
        if ($nextone == 0) {
            $module_one = $this->PageModule->find('first', array('conditions' => array('PageModule.id' => $id)));
            if ($updowm == 'up') {
                $module_change = $this->PageModule->find('first', array('conditions' => array('PageModule.orderby <' => $module_one['PageModule']['orderby'], 'PageModule.parent_id' => 0), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $module_change = $this->PageModule->find('first', array('conditions' => array('PageModule.orderby >' => $module_one['PageModule']['orderby'], 'PageModule.parent_id' => 0), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        if ($nextone == 'next') {
            $module_one = $this->PageModule->find('first', array('conditions' => array('PageModule.id' => $id)));
            if ($updowm == 'up') {
                $module_change = $this->PageModule->find('first', array('conditions' => array('PageModule.orderby <' => $module_one['PageModule']['orderby'], 'PageModule.parent_id' => $module_one['PageModule']['parent_id']), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $module_change = $this->PageModule->find('first', array('conditions' => array('PageModule.orderby >' => $module_one['PageModule']['orderby'], 'PageModule.parent_id' => $module_one['PageModule']['parent_id']), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        $t = $module_one['PageModule']['orderby'];
        $module_one['PageModule']['orderby'] = $module_change['PageModule']['orderby'];
        $module_change['PageModule']['orderby'] = $t;
        if (isset($module_change['PageModule']['status']) && $module_change['PageModule']['code'] != '') {
            $this->PageModule->saveAll($module_one);
            $this->PageModule->saveAll($module_change);
        }
        $conditions['PageModule.code'] = $code;
        $modules_tree = $this->PageModule->tree($this->locale, $conditions);
        $this->set('modules_tree', $modules_tree);
        $this->set('module_ordertype', $this->module_ordertype);
        $this->set('module_types', $this->module_types);
        $this->set('module_position', $this->module_position);
        Configure::write('debug', 1);
        $this->render('module_list');
        $this->layout = 'ajax';
    }

    public function getCats()
    {
        if (isset($_REQUEST['type'])) {
            $type = $_REQUEST['type'];
        }
        $category_tree = array();
        if ($type == 'L') {
            $Resource_info = $this->Resource->resource_formated(array('link_type'), $this->locale);
            $category_tree = $Resource_info['link_type'];
        } elseif ($type == 'F') {
            $informationresource_info = $this->InformationResource->information_formated(array('flash_custom_type'), $this->locale);
            if (!empty($informationresource_info)) {
                $category_tree = $informationresource_info['flash_custom_type'];
            }
        } else {
            $category_tree = $this->CategoryProduct->tree($type, $this->locale);
        }
        $result['category_tree'] = $category_tree;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function check_style()
    {
        $code = $_REQUEST;
        $modules_tree = $this->PageModule->parent_tree($this->locale, $code);
        if (!empty($modules_tree)) {
            $result['flag'] = 1;
            $result['modules_tree'] = $modules_tree;
        } else {
            $result['flag'] = 0;
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
