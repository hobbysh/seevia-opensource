<?php

/*****************************************************************************
 * Seevia 导航设置
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
class NavigationsController extends AppController
{
    public $name = 'Navigations';
    public $helpers = array('Html','Pagination','Svshow');
    public $components = array('Pagination','RequestHandler');
    public $uses = array('Topic','Brand','CategoryArticle','Article','Product','Navigation','NavigationI18n','Resource','OperatorLog','CategoryProduct','Page','PageI18n');
    public function index($page = 1)
    {
        $this->operator_privilege('navigations_view');
        $this->menu_path = array('root' => '/cms/','sub' => '/navigations/');
        $this->set('title_for_layout', $this->ld['navigations'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['navigations'],'url' => '');

        $condition = '';
        //导航筛选查询条件
        $type = '';
        $controller = '';
        $navigation_name = '';
      /* 	if(isset($this->params['url']['type']) && $this->params['url']['type'] != ''){
                  $condition["Navigation.type"] = $this->params['url']['type'];
                  $type = $this->params['url']['type'];
           }
           if(isset($this->params['url']['controller']) && $this->params['url']['controller'] != ''){
               $condition["Navigation.controller"] = $this->params['url']['controller'];
                  $controller = $this->params['url']['controller'];
           }
           if(isset($this->params['url']['navigation_name']) && $this->params['url']['navigation_name'] != ''){
               $navigation_name = $this->params['url']['navigation_name'];
            $condition2["NavigationI18n.name like"]= "%$navigation_name%";
            $navigationid = $this->NavigationI18n->find('list', array('fields'=>array('NavigationI18n.navigation_id'),'conditions'=>$condition2));
            $navigationid[] = 0;
            $condition["Navigation.id"]= $navigationid;
        }*/

        $total = $this->Navigation->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
       //       	$this->configs["show_count"] = (int)$this->configs["show_count"]?$this->configs["show_count"]:'20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $rownum = $total > 0 ? $total : 20;
        $sortClass = 'Navigation';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }

        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'navigations','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Navigation');
        $this->Pagination->init($condition, $parameters, $options);

        $orderby = 'Navigation.orderby,Navigation.id';
        $condition['Navigation.type'] = 'T';
        $navigations_data_t = $this->Navigation->alltree($condition, $orderby, $rownum, $page, $this->backend_locale);
        $condition['Navigation.type'] = 'H';
        $navigations_data_h = $this->Navigation->alltree($condition, $orderby, $rownum, $page, $this->backend_locale);
        $condition['Navigation.type'] = 'B';
        $navigations_data_b = $this->Navigation->alltree($condition, $orderby, $rownum, $page, $this->backend_locale);
        $condition['Navigation.type'] = 'M';
        $navigations_data_m = $this->Navigation->alltree($condition, $orderby, $rownum, $page, $this->backend_locale);
        $condition['Navigation.type'] = 'PM';
        $navigations_data_pm = $this->Navigation->alltree($condition, $orderby, $rownum, $page, $this->backend_locale);
        $condition['Navigation.type'] = 'PB';
        $navigations_data_pb = $this->Navigation->alltree($condition, $orderby, $rownum, $page, $this->backend_locale);
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('navigation_type', 'navigation_system_parameters'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        $this->set('navigations_data_t', $navigations_data_t);
        $this->set('navigations_data_h', $navigations_data_h);
        $this->set('navigations_data_b', $navigations_data_b);
        $this->set('navigations_data_m', $navigations_data_m);
        $this->set('navigations_data_pm', $navigations_data_pm);
        $this->set('navigations_data_pb', $navigations_data_pb);
        $this->set('type', $type);
        $this->set('controller', $controller);
        $this->set('navigation_name', $navigation_name);
        $this->set('types', $Resource_info['navigation_type']);
        $this->set('controllers', $Resource_info['navigation_system_parameters']);
    }

    public function view($id = 0, $nav_type = '')
    {
        $this->menu_path = array('root' => '/cms/','sub' => '/navigations/');
        $this->set('id', $id);
        $this->set('nav_type', $nav_type);//导航位置
        if (empty($id)) {
            $this->operator_privilege('navigations_add');
        } else {
            $this->operator_privilege('navigations_edit');
        }
        $this->set('title_for_layout', $this->ld['edit_navigation_setting'].' - '.$this->ld['navigations'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['navigations'],'url' => '/navigations/');
        if ($this->RequestHandler->isPost()) {
            $parent_id = $this->data['Navigation']['parent_id'];
            if (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 0) {
                $navigation_first = $this->Navigation->find('first', array('conditions' => array('Navigation.parent_id' => $parent_id), 'order' => 'orderby asc', 'limit' => '1'));
                $this->data['Navigation']['orderby'] = $navigation_first['Navigation']['orderby'];
                // 取出所有导航的 序值加1
                $na_all = $this->Navigation->find('all');
                foreach ($na_all as $k => $v) {
                    $na_all[$k]['Navigation']['orderby'] = $v['Navigation']['orderby'] + 1;
                }
                $this->Navigation->saveAll($na_all);
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 1) {
                $navigation_last = $this->Navigation->find('first', array('conditions' => array('Navigation.parent_id' => $parent_id), 'order' => 'orderby desc', 'limit' => '1'));
                $this->data['Navigation']['orderby'] = $navigation_last['Navigation']['orderby'] + 1;
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 2) {
                $navigation_change = $this->Navigation->find('first', array('conditions' => array('Navigation.id' => $_REQUEST['orderby_sel'])));
                $this->data['Navigation']['orderby'] = $navigation_change['Navigation']['orderby'] + 1;
                $na_all = $this->Navigation->find('all', array('conditions' => array('Navigation.orderby >' => $navigation_change['Navigation']['orderby'])));
                foreach ($na_all as $k => $v) {
                    $na_all[$k]['Navigation']['orderby'] = $v['Navigation']['orderby'] + 1;
                }
                $this->Navigation->saveAll($na_all);
            } else {
                if (!isset($this->data['Navigation']['id']) && $this->data['Navigation']['id'] == '') {
                    $this->data['Navigation']['orderby'] = 1;
                }
            }
            if (isset($this->data['Navigation']['id']) && $this->data['Navigation']['id'] != '') {
                $this->Navigation->save(array('Navigation' => $this->data['Navigation'])); //关联保存
            } else {
                $this->Navigation->saveAll(array('Navigation' => $this->data['Navigation'])); //关联保存
                $id = $this->Navigation->getLastInsertId();
            }
            $this->NavigationI18n->deleteAll(array('navigation_id' => $id));
            foreach ($this->data['NavigationI18n'] as $v) {
                $navigationI18n_info = array(
                    'locale' => $v['locale'],
                    'navigation_id' => $id,
                    'name' => isset($v['name']) ? $v['name'] : '',
                    'url' => $v['url'],
                    'description' => $v['description'],
                    'img01' => $v['img01'],
                    'img02' => $v['img02'],
                );
                $this->NavigationI18n->saveall(array('NavigationI18n' => $navigationI18n_info));//更新多语言
            }
            foreach ($this->data['NavigationI18n'] as $k => $v) {
                if ($v['locale'] == $this->backend_locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_navigation_setting'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/navigations/');
        }
        $this->data = $this->Navigation->localeformat($id);
        if (isset($this->data['NavigationI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit_navigation_setting'],'url' => '');
            $this->navigations[] = array('name' => $this->data['NavigationI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_navigation'],'url' => '');
        }
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
        //商品分类
        $c_p_info = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.status' => 1, 'CategoryProductI18n.locale' => $this->backend_locale, 'CategoryProduct.type' => 'P')));
        if (isset($c_p_info) && $c_p_info != '' && count($c_p_info) > 0) {
            $this->set('c_p_info', $c_p_info);
        }
        //文章分类
        $c_a_info = $this->CategoryArticle->find('all', array('conditions' => array('CategoryArticle.status' => 1, 'CategoryArticleI18n.locale' => $this->backend_locale, 'CategoryArticle.type' => 'A')));
        if (isset($c_a_info) && $c_a_info != '' && count($c_a_info) > 0) {
            $this->set('c_a_info', $c_a_info);
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
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Promotion');
            //所有促销活动
            $pro_info = $this->Promotion->find('all');
            if (isset($pro_info) && $pro_info != '' && count($pro_info) > 0) {
                $this->set('pro_info', $pro_info);
            }
        }
        $this->Navigation->set_locale($this->backend_locale);
        $navigation_data = $this->Navigation->find('all', array('conditions' => array('Navigation.parent_id' => '0'), 'fields' => array('Navigation.id', 'NavigationI18n.name')));
        $this->set('navigation_data', $navigation_data);
    }

    /**
     *列表显示修改.
     */
    public function toggle_on_status()
    {
        $this->Navigation->hasMany = array();
        $this->Navigation->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Navigation->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表新窗口修改.
     */
    public function toggle_on_target()
    {
        $this->Navigation->hasMany = array();
        $this->Navigation->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        if ($val == 0) {
            $val = '_self';
        } elseif ($val == 1) {
            $val = '_blank';
        }
        $result = array();
        if ($this->Navigation->save(array('id' => $id, 'target' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表排序修改.
     */
    public function update_navigation_orderby()
    {
        $this->Navigation->hasMany = array();
        $this->Navigation->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->Navigation->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *删除一个导航.
     *
     *@param int $id 输入导航ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_navigation_failure'];
        $pn = $this->NavigationI18n->find('list', array('fields' => array('NavigationI18n.navigation_id', 'NavigationI18n.name'), 'conditions' => array('NavigationI18n.navigation_id' => $id, 'NavigationI18n.locale' => $this->backend_locale)));
        $nIds = $this->Navigation->find('list', array('conditions' => array('Navigation.parent_id' => $id), 'fields' => 'Navigation.id'));
        if (!empty($nIds)) {
            $this->Navigation->deleteAll(array('Navigation.id' => $nIds));
            $this->NavigationI18n->deleteAll(array('navigation_id' => $nIds));
            $nnIds = $this->Navigation->find('list', array('conditions' => array('Navigation.parent_id' => $nIds), 'fields' => 'Navigation.id'));
            if (!empty($nnIds)) {
                $this->Navigation->deleteAll(array('Navigation.id' => $nnIds));
                $this->NavigationI18n->deleteAll(array('navigation_id' => $nnIds));
            }
        }

        $this->Navigation->deleteAll(array('Navigation.id' => $id));
        $this->NavigationI18n->deleteAll(array('navigation_id' => $id));

        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_navigation'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }

        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_navigation_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function search($a, $keyword = '')
    {
        $result['message'] = $this->ld['search_failed'];
        $result['flag'] = 2;
        $condition = '';
        if ($a == 'p') {
            $condition['ProductI18n.locale'] = $this->backend_locale;
            $condition['ProductI18n.name like'] = "%$keyword%";
            $p_info = $this->Product->find('all', array('conditions' => $condition));
            if (count($p_info) > 0) {
                $result['cat'] = '<select id="product"  onChange="changeNa(this.value)"><option value="0">'.$this->ld['please_select'].'</option>';
                foreach ($p_info as $v) {
                    $result['cat'] .= '<option value="'.$v['Product']['id'].'/'.$v['ProductI18n']['name'].'">'.$v['ProductI18n']['name'].'</option>';
                }
                $result['cat'] .= '</select><br><input type="text" id="p_key" value="" /><input type="button" onclick="search(\''.$a.'\')" value="'.$this->ld['search_products'].'" />';
                $result['status'] = '1';
            } else {
                $result['cat'] = '<select id="product"  onChange="changeNa(this.value)"><option value="0">'.$this->ld['please_select'].'</option></select><br><input type="text" id="p_key" value="" /><input type="button" onclick="search(\''.$a.'\')" value="'.$this->ld['search_products'].'" />';
            }
            $result['message'] = $this->ld['search_success'];
            $result['flag'] = 1;
        }
        if ($a == 'a') {
            $condition['ArticleI18n.locale'] = $this->backend_locale;
            $condition['ArticleI18n.title like'] = "%$keyword%";
            $a_info = $this->Article->find('all', array('conditions' => $condition));
            if (count($a_info) > 0) {
                $result['cat'] = '<select id="article"  onChange="changeNa(this.value)"><option value="0">'.$this->ld['please_select'].'</option>';
                foreach ($a_info as $v) {
                    $result['cat'] .= '<option value="'.$v['Article']['id'].'/'.$v['ArticleI18n']['title'].'">'.$v['ArticleI18n']['title'].'</option>';
                }
                $result['cat'] .= '</select><br><input type="text" id="a_key" value="" /><input type="button" onclick="search(\''.$a.'\')" value="'.$this->ld['search_articles'].'" />';
                $result['status'] = '1';
            } else {
                $result['cat'] = '<select id="product"  onChange="changeNa(this.value)"><option value="0">'.$this->ld['please_select'].'</option></select><br><input type="text" id="a_key" value="" /><input type="button" onclick="search(\''.$a.'\')" value="'.$this->ld['search_articles'].'" />';
            }
            $result['message'] = $this->ld['search_success'];
            $result['flag'] = 1;
        }
        if ($a == 'sp') {
            $condition['PageI18n.locale'] = $this->backend_locale;
            $condition['PageI18n.title like'] = "%$keyword%";
            $sp_info = $this->Page->find('all', array('conditions' => $condition));
            if (count($sp_info) > 0) {
                $result['cat'] = '<select id="staticpage"  onChange="changeNa(this.value)"><option value="0">'.$this->ld['please_select'].'</option>';
                foreach ($sp_info as $v) {
                    $result['cat'] .= '<option value="'.$v['Page']['id'].'">'.$v['PageI18n']['title'].'</option>';
                }
                $result['cat'] .= '</select><br><input type="text" id="sp_key" value="" /><input type="button" onclick="search(\''.$a.'\')" value="'.$this->ld['search'].'" />';
                $result['status'] = '1';
            } else {
                $result['cat'] = '<select id="product"  onChange="changeNa(this.value)"><option value="0">'.$this->ld['please_select'].'</option></select><br><input type="text" id="sp_key" value="" /><input type="button" onclick="search(\''.$a.'\')" value="'.$this->ld['search'].'" />';
            }
            $result['message'] = $this->ld['search_success'];
            $result['flag'] = 1;
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //seo链接
    public function changeUrl()
    {
        $path = $_POST['url'];
        $url = explode('/', $_POST['url']);
        //如果是分类的话 判断是 商品分类 还是文章分类
        if ($url[1] == 'categories') {
        	$category_type=$url[0];
        	$ca_info = array();
        	$ca_pro_info = array();
        	if($category_type=='A'){
        		$ca_info = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $url[2])));
        	}else{
        		$ca_pro_info = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $url[2])));
        	}
	        if (!empty($ca_pro_info) && $ca_pro_info['CategoryProduct']['type'] == 'P') {
	            $path = $this->Navigation->seo_link_path(array('type' => 'PC', 'id' => $url[2], 'name' => $url[3]));
	        }
	        if (!empty($ca_info) && $ca_info['CategoryArticle']['type'] == 'A') {
	            $path = $this->Navigation->seo_link_path(array('type' => 'AC', 'name' => $url[3], 'id' => $url[2]));
	        }
        }
        if ($url[1] == 'products') {
            $path = $this->Navigation->seo_link_path(array('type' => 'P', 'id' => $url[2], 'name' => $url[3]));
        }
        if ($url[1] == 'articles') {
            $path = $this->Navigation->seo_link_path(array('type' => 'A', 'id' => $url[2], 'name' => $url[3]));
        }
        $result['url'] = $path;
        $result['message'] = $this->ld['modified_successfully'];
        $result['flag'] = 1;
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function changeorder($updowm, $id, $nextone, $position)
    {
        //如果值相等重新自动排序
        $a = $this->Navigation->query('SELECT DISTINCT `parent_id` 
			FROM `svcms_navigations` as Navigation
			GROUP BY `orderby` , `parent_id` 
			HAVING count( * ) >1');
        foreach ($a as $v) {
            $all = $this->Navigation->find('all', array('conditions' => array('Navigation.parent_id' => $v['Navigation']['parent_id'])));
            foreach ($all as $k => $vv) {
                $all[$k]['Navigation']['orderby'] = $k + 1;
            }
            $this->Navigation->saveAll($all);
        }
        if ($nextone == 0) {
            $navigation_one = $this->Navigation->find('first', array('conditions' => array('Navigation.id' => $id)));
            if ($updowm == 'up') {
                $navigation_change = $this->Navigation->find('first', array('conditions' => array('Navigation.orderby <' => $navigation_one['Navigation']['orderby'], 'Navigation.parent_id' => 0, 'Navigation.type' => $position), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $navigation_change = $this->Navigation->find('first', array('conditions' => array('Navigation.orderby >' => $navigation_one['Navigation']['orderby'], 'Navigation.parent_id' => 0, 'Navigation.type' => $position), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        if ($nextone == 'next') {
            $navigation_one = $this->Navigation->find('first', array('conditions' => array('Navigation.id' => $id)));
            if ($updowm == 'up') {
                $navigation_change = $this->Navigation->find('first', array('conditions' => array('Navigation.orderby <' => $navigation_one['Navigation']['orderby'], 'Navigation.parent_id' => $navigation_one['Navigation']['parent_id'], 'Navigation.type' => $position), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $navigation_change = $this->Navigation->find('first', array('conditions' => array('Navigation.orderby >' => $navigation_one['Navigation']['orderby'], 'Navigation.parent_id' => $navigation_one['Navigation']['parent_id'], 'Navigation.type' => $position), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        $t = $navigation_one['Navigation']['orderby'];
        $navigation_one['Navigation']['orderby'] = $navigation_change['Navigation']['orderby'];
        $navigation_change['Navigation']['orderby'] = $t;
        if (isset($navigation_change['Navigation']['status']) && $navigation_change['Navigation']['type'] != '') {
            $this->Navigation->saveAll($navigation_one);
            $this->Navigation->saveAll($navigation_change);
        }
        $orderby = 'Navigation.orderby,Navigation.id';
        $rownum = 100;
        $condition = '';
        $page = 1;
        $condition['Navigation.type'] = $position;
        $navigations_data = $this->Navigation->alltree($condition, $orderby, $rownum, $page, $this->backend_locale);
        $this->set('navigations_data', $navigations_data);
        $Resource_info = $this->Resource->getformatcode(array('navigation_type', 'navigation_system_parameters'), $this->locale);
        $this->set('Resource_info', $Resource_info);
        if ($position == 't') {
            $name = $this->ld['top_navigation'];
            $id = 'foldtablelist_t';
        }
        if ($position == 'm') {
            $name = $this->ld['middle_navigation'];
            $id = 'foldtablelist_m';
        }
        if ($position == 'b') {
            $name = $this->ld['bottom_navigation'];
            $id = 'foldtablelist_b';
        }
        if ($position == 'h') {
            $name = $this->ld['help_navigation'];
            $id = 'foldtablelist_h';
        }
        if ($position == 'pb') {
            $name = $this->ld['mobile_bottom_navigation'];
            $id = 'foldtablelist_pb';
        }
        if ($position == 'pm') {
            $name = $this->ld['mobile_middle_navigation'];
            $id = 'foldtablelist_pm';
        }
        $this->set('position', $position);
        $this->set('name', $name);
        $this->set('id', $id);
        Configure::write('debug', 1);
        $this->layout = 'ajax';
    }
//排序下拉框
    public function searchNa($type, $id = 0)
    {
        if ($id != 0) {
            $na_one = $this->Navigation->find('first', array('conditions' => array('Navigation.id' => $id)));
            $na_info = $this->Navigation->find('all', array('conditions' => array('Navigation.parent_id' => $na_one['Navigation']['id'], 'NavigationI18n.locale' => $this->backend_locale)));
        } else {
            $na_info = $this->Navigation->find('all', array('conditions' => array('Navigation.parent_id' => $id, 'NavigationI18n.locale' => $this->backend_locale, 'Navigation.type' => $type)));
        }
        $result['flag'] = 2;
        if (!empty($na_info) && sizeof($na_info) > 0) {
            $result['flag'] = 1;
            $select_data = array();
            $select_data[] = array(
                'id' => 0,
                'value' => $this->ld['root_navigate'],
            );
            foreach ($na_info as $v) {
                $select_data[] = array(
                        'id' => $v['Navigation']['id'],
                        'value' => $v['NavigationI18n']['name'],
                    );
            }
            $result['select_data'] = $select_data;
        } else {
            $result['flag'] = 0;
            $result['datahtml'] = $this->ld['no_lower_navigation'];
        }
        $result['message'] = $this->ld['modified_successfully'];
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function searchtype($type, $id = 0, $this_id = 0)
    {
        $this->Navigation->set_locale($this->backend_locale);
        $navigation_data = $this->Navigation->find('all', array('conditions' => array('Navigation.parent_id' => '0', 'Navigation.type' => $type), 'fields' => array('Navigation.id', 'NavigationI18n.name')));

        $select_data = array();
        $select_data[] = array(
            'id' => 0,
            'value' => $this->ld['root_navigate'],
        );
        if (!empty($navigation_data) && sizeof($navigation_data) > 0) {
            foreach ($navigation_data as $v) {
                if ($v['Navigation']['id'] != $this_id) {
                    $select_data[] = array(
                        'id' => $v['Navigation']['id'],
                        'value' => $v['NavigationI18n']['name'],
                    );
                }
            }
        }
        $result['select_data'] = $select_data;
        $result['message'] = $this->ld['modified_successfully'];
        $result['flag'] = 1;
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
