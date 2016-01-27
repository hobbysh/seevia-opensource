<?php

/*****************************************************************************
 * Seevia 店铺管理管理
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
class ModulesController extends AppController
{
    public $name = 'Modules';
    public $helpers = array('Html','Pagination','Tinymce','fck','Form','Javascript','Ckeditor');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('Module','ModuleI18n','Resource','SeoKeyword','OperatorLog');
    public $module_types = array('module_article' => '文章','module_product' => '商品','module_article_category' => '文章分类','module_product_category' => '商品分类','module_brand' => '品牌','module_topic' => '专题','module_journey' => '行程','module_attraction' => '景点','module_flash' => 'flash','module_promotion' => '促销信息','module_help_information' => '帮助信息','module_hotel_information' => '酒店信息');
    public $module_position = array('top' => '顶部','left' => '左边','right' => '右边');
    public $module_ordertype = array('module_article' => array('created desc' => '按时间递减','orderby' => '按排序递增'),'module_product' => array('created desc' => '按时间递减','price desc' => '按价格递减','code asc' => '按货号递增'),'module_article_category' => array('orderby' => '按排序递增'),'module_product_category' => array('orderby' => '按排序递增'),'module_brand' => array('id desc' => '按品牌id递减','code asc' => '按品牌编码递增','created desc' => '按创建时间递减'),'module_topic' => array('end_time desc' => '按结束时间递减','orderby' => '按排序递增'),'module_journey' => array('created' => '按时间递减'),'module_attraction' => array('orderby' => '按排序递增'),'module_flash' => array('orderby' => '按排序递增'),'module_promotion' => array('end_time desc' => '按促销结束时间递减'),'module_help_information' => array('orderby' => '按排序递增'),'module_hotel_information' => array('orderby' => '按排序递增'));
    public function index()
    {
        /*判断权限*/
        $this->operator_privilege('modules_view');
        /*end*/
        $this->set('title_for_layout', $this->ld['module_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['module_management'],'url' => '/modules/');

        $modules_tree = $this->Module->tree($this->locale);
        $this->set('modules_tree', $modules_tree);
    //	pr($modules_tree);die;
//      $this->Module->set_locale($this->locale);
//   	$condition='';
//   	$total = $this->Module->find("count",array("conditions"=>$condition));
//   	$this->configs['show_count'] = $this->configs['show_count']>$total?$total:$this->configs['show_count'];
//	    $sortClass='Module';
//	    $page=1;
//		$rownum=!empty($this->configs['show_count']) ? $this->configs['show_count']: ((!empty($rownum)) ? $rownum : 20);
//
//	    $parameters=Array($rownum,$page);
//	    $options=Array();
//     	$page  = $this->Pagination->init($condition,$parameters,$options,$total,$rownum,$sortClass);
//		$module_list=$this->Module->find("all",array("conditions"=>$condition,"rownum"=>$rownum,"page"=>$page,"order"=>"Module.orderby,Module.created desc"));
//   	$this->set('module_list',$module_list);
        $this->set('module_types', $this->module_types);
        $this->set('module_position', $this->module_position);
    }
    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('modules_remove');
        /*end*/
        $this->Module->hasOne = array();
        $pn = $this->ModuleI18n->find('list', array('fields' => array('ModuleI18n.module_id', 'ModuleI18n.name'), 'conditions' => array('ModuleI18n.module_id' => $id, 'ModuleI18n.locale' => $this->locale)));
        $this->Module->deleteAll(array('id' => $id));
        $this->ModuleI18n->deleteAll(array('module_id' => $id));
        $module_data = $this->Module->find('all', array('conditions' => array('parent_id' => $id), 'fields' => 'id'));
        foreach ($module_data as $k => $v) {
            $this->Module->save(array('Module' => array('id' => $v['Module']['id'], 'parent_id' => 0)));
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_product_module'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $this->redirect('/modules');
    }
    public function view($id = 0)
    {
        /*判断权限*/
        $this->operator_privilege('modules_edit');
        /*end*/
        $this->set('title_for_layout', $this->ld['module_edit'].' - '.$this->ld['module_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['module_management'],'url' => '/modules/');
        $this->navigations[] = array('name' => $this->ld['module_edit'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['Module']['orderby'] = !empty($this->data['Module']['orderby']) ? $this->data['Module']['orderby'] : 50;

            $this->Module->save($this->data); //保存
            $id = $this->Module->getLastInsertId();
            $this->ModuleI18n->deleteAll(array('module_id' => $id)); //删除原有多语言
            foreach ($this->data['ModuleI18n'] as $v) {
                $moduleI18n_info = array(
                                   'id' => isset($v['id']) ? $v['id'] : '',
                                   'locale' => $v['locale'],
                                   'module_id' => !empty($v['module_id']) ? $v['module_id'] : $id,
                                   'name' => isset($v['name']) ? $v['name'] : '',
                                   'title' => isset($v['title']) ? $v['title'] : '',
                             );
                $this->ModuleI18n->save(array('ModuleI18n' => $moduleI18n_info));//更新多语言
            }

            foreach ($this->data['ModuleI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['shop_edit_shop'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/modules');
        }
        if (!empty($id)) {
            $this->data = $this->Module->localeformat($id);
            //pr($this->data);
            $this->set('modules_info', $this->data);
            //leo20090722导航显示
            $this->data['ModuleI18n'][$this->backend_locale]['name'] = empty($this->data['ModuleI18n'][$this->backend_locale]['name']) ? '' : $this->data['ModuleI18n'][$this->backend_locale]['name'];
            $this->navigations[] = array('name' => $this->data['ModuleI18n'][$this->backend_locale]['name'],'url' => '');
        }

        $modules_tree = $this->Module->tree($this->locale);
        $this->set('modules_tree', $modules_tree);
//		$module_ordertype=array();
//		foreach($this->module_types as $k=>$v){
//			$module_ordertype[$k]=$this->orderby_type;
//		}
//		$this->set('module_ordertype',$module_ordertype);
//		pr($this->module_ordertype);
        $this->set('module_ordertype', $this->module_ordertype);
        $this->set('module_types', $this->module_types);
        $this->set('module_position', $this->module_position);
    }

    public function act_view($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $code = $_POST['code'];
        $rname = '';
        $name_code = $this->Module->find('all', array('fields' => 'Module.code'));
        if (isset($name_code) && !empty($name_code)) {
            foreach ($name_code as $vv) {
                $rname[] = $vv['Module']['code'];
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
            $Module_count = $this->Module->find('first', array('conditions' => array('Module.id' => $id)));
            if ($Module_count['Module']['code'] != $code && in_array($code, $rname)) {
                $result['code'] = '0';
                //   $result['msg'] = "用户名重复";
            } else {
                $result['code'] = '1';
            }
        }
        die(json_encode($result));
    }
    public function toggle_on_status()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Module->save(array('id' => $id, 'status' => $val))) {
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
    /**
     *批量处理.
     */
    public function batch()
    {
        $art_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        if (isset($this->params['url']['act_type']) && $this->params['url']['act_type'] != '0') {
            if ($this->params['url']['act_type'] == 'delete') {
                $this->Module->hasOne = array();
                $condition['Module.id'] = $art_ids;
                $this->Module->deleteAll($condition);
                $this->ModuleI18n->deleteAll(array('module_id' => $art_ids));
                $module_data = $this->Module->find('all', array('conditions' => array('parent_id' => $art_ids), 'fields' => 'id'));
                foreach ($module_data as $k => $v) {
                    $this->Module->save(array('Module' => array('id' => $v['Module']['id'], 'parent_id' => 0)));
                }
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
                }
                $this->redirect('/modules/');
            }
            if ($this->params['url']['act_type'] == 'a_status') {
                $condition['Module.id'] = $art_ids;
                $this->Module->updateAll(array('Module.status' => $_REQUEST['is_yes_no']), array('Module.id' => $art_ids));
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
                }
                $this->redirect('/modules/');
            }
        } else {
            $this->redirect('/modules/');
        }
    }

    //列表箭头排序
    public function changeorder($updowm, $id, $nextone)
    {
        //如果值相等重新自动排序
        $a = $this->Module->query('SELECT DISTINCT `parent_id` 
			FROM `svcart_modules` as Module
			GROUP BY `orderby` , `parent_id` 
			HAVING count( * ) >1');
        foreach ($a as $v) {
            $this->Module->Behaviors->attach('Containable');
            $all = $this->Module->find('all', array('conditions' => array('Module.parent_id' => $v['Module']['parent_id']), 'order' => 'Module.id asc', 'contain' => false));
            foreach ($all as $k => $vv) {
                $all[$k]['Module']['orderby'] = $k + 1;
            }
            $this->Module->saveAll($all);
        }
        if ($nextone == 0) {
            $module_one = $this->Module->find('first', array('conditions' => array('Module.id' => $id)));
            if ($updowm == 'up') {
                $module_change = $this->Module->find('first', array('conditions' => array('Module.orderby <' => $module_one['Module']['orderby'], 'Module.parent_id' => 0), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $module_change = $this->Module->find('first', array('conditions' => array('Module.orderby >' => $module_one['Module']['orderby'], 'Module.parent_id' => 0), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        if ($nextone == 'next') {
            $module_one = $this->Module->find('first', array('conditions' => array('Module.id' => $id)));
            if ($updowm == 'up') {
                $module_change = $this->Module->find('first', array('conditions' => array('Module.orderby <' => $module_one['Module']['orderby'], 'Module.parent_id' => $module_one['Module']['parent_id']), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $module_change = $this->Module->find('first', array('conditions' => array('Module.orderby >' => $module_one['Module']['orderby'], 'Module.parent_id' => $module_one['Module']['parent_id']), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        $t = $module_one['Module']['orderby'];
        $module_one['Module']['orderby'] = $module_change['Module']['orderby'];
        $module_change['Module']['orderby'] = $t;
        if (isset($module_change['Module']['status']) && $module_change['Module']['code'] != '') {
            $this->Module->saveAll($module_one);
            $this->Module->saveAll($module_change);
        }
        $modules_tree = $this->Module->tree($this->locale);
        $this->set('modules_tree', $modules_tree);
        $this->set('module_ordertype', $this->module_ordertype);
        $this->set('module_types', $this->module_types);
        $this->set('module_position', $this->module_position);
        Configure::write('debug', 1);
        $this->render('index');
        $this->layout = 'ajax';
    }
}
