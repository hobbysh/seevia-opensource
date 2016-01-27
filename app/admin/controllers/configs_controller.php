<?php

/*****************************************************************************
 * Seevia 商店设置
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
class ConfigsController extends AppController
{
    public $name = 'Configs';
    public $components = array('Pagination','RequestHandler'); // Added 
    public $helpers = array('Pagination','Javascript'); // Added 
    public $uses = array('Config','ConfigI18n','Dictionary','SystemResource','OperatorLog');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('site_settings_view');
        /*end*/

        $this->menu_path = array('root' => '/web_application/','sub' => '/configs/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $title = isset($this->backend_locale) && $this->backend_locale == 'eng' ? $this->ld['shop_configs'].' '.$this->ld['region_view'] : $this->ld['shop_configs'].$this->ld['region_view'];
        $this->navigations[] = array('name' => $title,'url' => '/configs/');
        //资源库信息
        $this->SystemResource->set_locale($this->backend_locale);
        $systemresource_info = $this->SystemResource->find('all', array('conditions' => array('code !=' => '', 'parent_id' => '34'), 'fields' => 'SystemResource.code,SystemResourceI18n.name', 'order' => 'orderby asc'));
        //$systemresource_info = $this->Config->find("all",array("group"=>"Config.group_code",'fields'=>"Config.group_code","order"=>"Config.group_code "));
           //
        $log_type = '';
        $show_name = '';
        $sub_group = '';
        $version = '';
        $condition = '';
        $config_keywords = '';     //关键字
        //关键字
        if (isset($this->params['url']['config_keywords']) && $this->params['url']['config_keywords'] != '') {
            $config_keywords = $this->params['url']['config_keywords'];
            $condition['and']['or']['ConfigI18n.name like'] = '%'.$config_keywords.'%';
            $condition['and']['or']['Config.code like'] = '%'.$config_keywords.'%';
        }
        //pr($this->params['url']);
        if (isset($this->params['url']['log_type']) && $this->params['url']['log_type'] != 'all') {
            $condition['Config.type'] = $this->params['url']['log_type'];
            $log_type = $this->params['url']['log_type'];
        }
        if (isset($this->params['url']['show_name']) && $this->params['url']['show_name'] != 'all') {
            $condition['Config.group_code'] = $this->params['url']['show_name'];
            $show_name = $this->params['url']['show_name'];
        }
        if (isset($this->params['url']['version']) && $this->params['url']['version'] != 'all') {
            $condition['Config.section'] = $this->params['url']['version'];
            $version = $this->params['url']['version'];
        }
        if (isset($this->params['url']['sub_group']) && $this->params['url']['sub_group'] != '') {
            $condition['Config.subgroup_code'] = $this->params['url']['sub_group'];
            $sub_group = $this->params['url']['sub_group'];
        }
        //$condition["Config.type !="] = "hidden";
        $this->Config->set_locale($this->backend_locale);
        $total = $this->Config->find('count', array('conditions' => $condition));

        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'configs','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Config');
        $this->Pagination->init($condition, $parameters, $options);
        $configs_list = $this->Config->find('all', array('conditions' => $condition, 'order' => 'Config.group_code,Config.subgroup_code,Config.orderby asc', 'limit' => $rownum, 'page' => $page));
        $this->set('configs_list', $configs_list);
        $this->set('log_type', $log_type);
        $this->set('show_name', $show_name);
        $this->set('version', $version);
        $this->set('sub_group', $sub_group);
        $this->set('config_keywords', $config_keywords);
        //pr($systemresource_info);
        $this->set('config_group_code', $systemresource_info);
        if (!empty($this->params['url'])) {
            $url = $this->params['url']['url'].'?';
            //$url="";
            foreach ($this->params['url'] as $k => $v) {
                if ($k == 'url') {
                } else {
                    $url .= $k.'='.$v.'&';
                }
            }
        }
        $_SESSION['index_url'] = $url;
        //$this->set('section',$systemresource_info["section"]);
        $this->set('title_for_layout', $title.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($id = 0)
    {
        /*判断权限*/
        if ($id == 0) {
            $this->operator_privilege('site_settings_add');
        } else {
            $this->operator_privilege('site_settings_edit');
        }
        /*end*/
        $this->menu_path = array('root' => '/web_application/','sub' => '/configs/');

        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => isset($this->backend_locale) && $this->backend_locale == 'eng' ? $this->ld['shop_configs'].' '.$this->ld['region_view'] : $this->ld['shop_configs'].$this->ld['region_view'],'url' => '/configs/');

        if ($this->RequestHandler->isPost()) {
            $config_code = !empty($this->data['Config']['code']) ? $this->data['Config']['code'] : '';
            if ($id == 0) {
                $this->data['Config']['orderby'] = !empty($this->data['Config']['orderby']) ? $this->data['Config']['orderby'] : '50';
                $this->Config->saveall(array('Config' => $this->data['Config']));
                $id = $this->Config->getLastInsertId();
                if (is_array($this->data['ConfigI18n'])) {
                    foreach ($this->data['ConfigI18n'] as $k => $v) {
                        $v['config_id'] = $id;
                        $v['config_code'] = $config_code;
                        $this->ConfigI18n->saveall(array('ConfigI18n' => $v));
                        if ($v['locale'] == $this->backend_locale) {
                            $userinformation_name = $v['name'];
                        }
                    }
                }
            } else {
                $id = $this->data['Config']['id'];

                $this->ConfigI18n->deleteAll(array('config_id' => $id));
                foreach ($this->data['ConfigI18n'] as  $k => $v) {
                    $v['config_id'] = $id;
                    $this->ConfigI18n->saveAll(array('ConfigI18n' => $v));
                }
                $this->Config->save($this->data); //保存
            }

            foreach ($this->data['ConfigI18n'] as $k => $v) {
                if ($v['locale'] == $this->backend_locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑商店设置: '.$userinformation_name, $this->admin['id']);
            }
            //$this->flash("商店设置  ".$userinformation_name."  编辑成功。点击这里继续编辑该商店设置。",'/configs/edit/'.$id,10);
            $this->redirect('/configs');
        }

        $this->Config->hasOne = array('ConfigI18n' => array('className' => 'ConfigI18n',
                    'conditions' => '',
                    'order' => 'Config.orderby asc',
                    'dependent' => true,
                    'foreignKey' => 'config_id',
                ),
            );

        $this->data = $this->Config->localeformat($id);

        $this->set('configs_info', $this->data);
//		pr($this->data);
//		pr($this->backend_locales);
//		$this->SystemResource->set_locale($this->locale);
//		$this->set('section',$this->SystemResource->find_assoc('section'));
        $title = isset($this->backend_locale) && $this->backend_locale == 'eng' ? $this->ld['shop_configs'].' '.$this->ld['region_view'] : $this->ld['shop_configs'].$this->ld['region_view'];
        $this->set('title_for_layout', $title.' - '.$this->configs['shop_name']);

        //leo20090722导航显示
        if (isset($this->data['ConfigI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->data['ConfigI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => isset($this->backend_locale) && $this->backend_locale == 'eng' ? $this->ld['add'].' '.$this->ld['shop_configs'] : $this->ld['add'].$this->ld['shop_configs'],'url' => '');
        }
        $this->set('navigations', $this->navigations);
    }

    public function add()
    {
        /*判断权限*/
        $this->operator_privilege('shop_set_add');
        /*end*/
        $this->pageTitle = '商店设置管理 - 商店设置管理'.' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => '功能管理','url' => '');
        $this->navigations[] = array('name' => '商店设置管理','url' => '/configs/');
        $this->navigations[] = array('name' => '编辑实商店设置','url' => '');
        $this->set('navigations', $this->navigations);
        $this->SystemResource->set_locale($this->locale);
        $this->set('section', $this->SystemResource->find_assoc('section'));
        if ($this->RequestHandler->isPost()) {
            $this->data['Config']['orderby'] = !empty($this->data['Config']['orderby']) ? $this->data['Config']['orderby'] : '50';
            $this->Config->saveall(array('Config' => $this->data['Config']));
            $id = $this->Config->getLastInsertId();
            if (is_array($this->data['ConfigI18n'])) {
                foreach ($this->data['ConfigI18n'] as $k => $v) {
                    $v['config_id'] = $id;
                    $this->ConfigI18n->saveall(array('ConfigI18n' => $v));
                    if ($v['locale'] == $this->locale) {
                        $userinformation_name = $v['name'];
                    }
                }
            }
                //操作员日志
                if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                    $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'增加商店设置:'.$userinformation_name, 'operation');
                }
            $this->flash('商店设置  '.$userinformation_name.'  添加成功。点击这里继续编辑该商店设置。', '/configs/edit/'.$id, 10);
        }
    }

    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('site_settings_remove');
        /*end*/
        $this->ConfigI18n->deleteAll(array('ConfigI18n.config_id' => $id));
        $this->Config->delete(array('Config.id' => $id));
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 删除网站设置:id '.$id, $this->admin['id']);
        }
        $this->redirect('/configs/');
    }
   //批量处理
   public function batch()
   {
       $this->Config->hasOne = array();
       $this->Config->hasOne = array('ConfigI18n' => array('className' => 'ConfigI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'config_id',
                        ),
                 );
       if (isset($this->params['url']['act_type']) && !empty($this->params['url']['checkbox'])) {
           $id_arr = $this->params['url']['checkbox'];
           $condition = '';
           for ($i = 0;$i <= count($id_arr) - 1;++$i) {
               if ($this->params['url']['act_type'] == 'delete') {
                   $condition['Config.id'] = $id_arr[$i];
               }
           }
           $this->Config->deleteAll($condition);
               //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'批量删除商店设置', 'operation');
            }
           $this->flash('删除成功', '/configs/', 10);
       } else {
           $this->flash('请选择内容', '/configs/', '');
       }
   }
    /**
     *列表只读修改.
     */
    public function toggle_on_readonly()
    {
        $this->Config->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Config->save(array('id' => $id, 'readonly' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *列表状态修改.
     */
    public function toggle_on_status()
    {
        $this->Config->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Config->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
   //AJAX修改排序
    public function update_config_orderby()
    {
        $this->Config->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->Config->updateAll(
            array('orderby' => "'".$val."'"),
            array('id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function update_table_frame()
    {
        $table_array = array(
                  'svcart_advertisements',
      'svcart_advertisement_i18ns',
      'svcart_articles',
      'svcart_article_categories',
      'svcart_article_i18ns',
      'svcart_booking_products',
      'svcart_brands',
      'svcart_brand_i18ns',
      'svcart_cards',
      'svcart_card_i18ns',
      'svcart_carts',
      'svcart_categories',
      'svcart_category_i18ns',
      'svcart_comments',
      'svcart_configs',
      'svcart_config_i18ns',
      'svcart_coupons',
      'svcart_coupon_types',
      'svcart_coupon_type_i18ns',
      'svcart_departments',
      'svcart_department_i18ns',
      'svcart_flashes',
      'svcart_flash_images',
      'svcart_languages',
      'svcart_language_dictionaries',
      'svcart_links',
      'svcart_link_i18ns',
      'svcart_mail_templates',
      'svcart_mail_template_i18ns',
      'svcart_navigations',
      'svcart_navigation_i18ns',
      'svcart_newsletter_lists',
      'svcart_operators',
      'svcart_operator_actions',
      'svcart_operator_action_i18ns',
      'svcart_operator_logs',
      'svcart_operator_menus',
      'svcart_operator_menu_i18ns',
      'svcart_operator_roles',
      'svcart_operator_role_i18ns',
      'svcart_orders',
      'svcart_order_actions',
      'svcart_order_cards',
      'svcart_order_packagings',
      'svcart_order_products',
      'svcart_packagings',
      'svcart_packaging_i18ns',
      'svcart_payments',
      'svcart_payment_api_logs',
      'svcart_payment_i18ns',
      'svcart_products',
      'svcart_products_categories',
      'svcart_product_articles',
      'svcart_product_attributes',
      'svcart_product_galleries',
      'svcart_product_gallery_i18ns',
      'svcart_product_i18ns',
      'svcart_product_ranks',
      'svcart_product_relations',
      'svcart_product_types',
      'svcart_product_type_attributes',
      'svcart_product_type_attribute_i18ns',
      'svcart_product_type_i18ns',
      'svcart_promotions',
      'svcart_promotion_i18ns',
      'svcart_promotion_products',
      'svcart_providers',
      'svcart_provider_products',
      'svcart_regions',
      'svcart_region_i18ns',
      'svcart_sessions',
      'svcart_shippings',
      'svcart_shipping_areas',
      'svcart_shipping_area_i18ns',
      'svcart_shipping_area_regions',
      'svcart_shipping_i18ns',
      'svcart_stores',
      'svcart_store_i18ns',
      'svcart_store_products',
      'svcart_templates',
      'svcart_topics',
      'svcart_topic_i18ns',
      'svcart_topic_products',
      'svcart_users',
      'svcart_user_accounts',
      'svcart_user_addresses',
      'svcart_user_balance_logs',
      'svcart_user_configs',
      'svcart_user_config_i18ns',
      'svcart_user_favorites',
      'svcart_user_friends',
      'svcart_user_friend_cats',
      'svcart_user_infos',
      'svcart_user_info_i18ns',
      'svcart_user_info_values',
      'svcart_user_messages',
      'svcart_user_point_logs',
      'svcart_user_ranks',
      'svcart_user_rank_i18ns',
      'svcart_virtual_cards',

        );
        foreach ($table_array as $k => $v) {
            $table_name = $v;
            $table_name_serial = $v.'_id_seq';
            $sql = 'select count(*)+1 as count from '.$table_name;
            $infor = $this->Config->query($sql);
            $sql = 'CREATE SEQUENCE svcart_user_infos_id_seq START '.$infor[0][0]['count'];
            $this->Config->query($sql);
            $sql = 'ALTER SEQUENCE svcart_user_infos_id_seq RESTART WITH '.$infor[0][0]['count'];
            $info_info = $this->Config->query($sql);
            if (empty($info_info)) {
                echo $k.'<br />';
            } else {
            }
        }
        die();
    }
}
