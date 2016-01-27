<?php

/*****************************************************************************
 * Seevia 分类控制器
* ===========================================================================
* 版权所有  上海实玮网络科技有限公司，并保留所有权利。
* 网站地址: http://www.seevia.cn
* ---------------------------------------------------------------------------
* 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
* 不允许对程序代码以任何形式任何目的的再发布。
* ===========================================================================
* $开发: 上海实玮$
* $Id$*/
/**
 *分类显示.
 *
 *对于CategoryProduct这张表的增删改查
 *
 *@author   hechang 
 *
 *@version  $Id$
 */
class CategoriesController extends AppController
{
    /*
     * @var $name
     * @var $helpers
     * @var $uses
     * @var $components
     * @var $cacheQueries
     * @var $cacheAction
     */

    public $name = 'Categories';
    public $components = array('Pagination', 'Cookie','Phpexcel'); // Added
    public $helpers = array('Html', 'Pagination', 'Flash');
    public $uses = array('Brand','CategoryProduct','Comment','Product','Flash','ProductsCategory','UserRank', 'ProductI18n', 'ProductRelation', 'ProductRank', 'ProductLocalePrice', 'CategoryFilter', 'ProductType', 'ProductTypeAttribute','ProductAttribute','ProductGallery','ProductVolume','Score','ScoreLog');
    public $cacheQueries = true;
    public $cacheAction = '1 hour';

    /** 
     * 函数view 显示分类
     * 前台访问路径为：/categories/view/100  或者  /categories/100.
     *
     * @param $id   分类id
     * @param $page 页数
     * @param $orderby	排序方式
     * @param $rownum  	一页多少个
     * @param $showtype 显示方式（一行多少个）
     * @param $brand_id 品牌id    
     * @param $min_price 最小价	
     * @param $max_price 最大价	
     * @param $filters	筛选条件
     * @param $keyword 关键字
     * @param $filters_attr_price 筛选单价
     */
    public function view($id, $page = 1, $limit = 0, $order_field = 0, $order_type = 0, $showtype = 0, $type = 0, $brand_id = 0, $min_price = -1, $max_price = -1, $filters_attr_price = 0, $filters = '', $keyword = '')
    {
    	 if(isset($_POST)){
    	 	$_POST=$this->clean_xss($_POST);
    	 }
        $this->set('category_id', $id);
        if ($min_price == -1) {
            $min_price = isset($_POST['min_price']) ? $_POST['min_price'] : -1;
        }
        if ($max_price == -1) {
            $max_price = isset($_POST['max_price']) ? $_POST['max_price'] : -1;
        }
        $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
        $keyword = $this->clean_xss($keyword);
        $strkeyword = $keyword;
        if ($keyword != '') {
            $keyword = preg_split('#\s+#', $keyword);
        }
	$filters = $this->clean_xss($filters);
        $order_field = $this->clean_xss($order_field);
        $order_type = $this->clean_xss($order_type);
        $limit = $this->clean_xss($limit);
        $showtype = $this->clean_xss($showtype);
        $category = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $id)));
        $this->set('category', $category);
        $this->set('meta_description', $category['CategoryProductI18n']['meta_description'].' '.$this->configs['seo-des']);
        $this->set('meta_keywords', $category['CategoryProductI18n']['meta_keywords'].' '.$this->configs['seo-key']);
        if ($this->is_mobile) {
            $this->pageTitle = $category['CategoryProductI18n']['name'];
        } else {
            $show_page = sprintf($this->ld['page'], $page);
            $this->pageTitle = $category['CategoryProductI18n']['name'].' - '.$show_page.' - '.$this->configs['shop_title'];//
        }
        $this->params['id'] = $id;
        $this->params['category_id'] = $id;
        $this->params['page'] = $page;
        $this->params['ControllerObj'] = $this;//控制器对象
        $this->page_init($this->params);
        $products_ids = $this->Product->query("select `Product`.`id` from `svoms_products` as `Product` left join `svoms_products_categories` as `ProductsCategory` ON (`Product`.`id` =`ProductsCategory`.`product_id`) where (`Product`.`category_id` = '".$id."' or `ProductsCategory`.`category_id` = '".$id."') and `Product`.`status`='1' and `Product`.`forsale`='1'");
        //pr($products_ids);
        $p_ids = array();
        foreach ($products_ids as $v) {
            array_push($p_ids, $v['Product']['id']);
        }
        //pr($p_ids);
        //分类筛选
//        $sale=false;
//		$price=false;
        $page_conditions = array('Product.id' => $p_ids,'Product.status' => '1','Product.forsale' => '1');
        $this->Product->belongsTo = array();
        $this->Product->hasMany = array();
        //分页start
        $total = $this->Product->find('count', array('conditions' => $page_conditions));
        if ($limit == 0) {
            $limit = 8;
        }
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'categories','action' => 'view/'.$id,'page' => $page,'limit' => $limit);
        //分页参数
        $page_options = array('page' => $page,'show' => $limit,'modelClass' => $this->name,'total' => $total);
        //pr($page_conditions);die;
        $pages = $this->Pagination->init($page_conditions, $parameters, $page_options); // Added
        //pr($pages);
        //分页end
$products_list = $this->Product->find('all', array('conditions' => array('Product.id' => $p_ids), 'page' => $page, 'limit' => $limit, 'fields' => 'Product.id,ProductI18n.name,Product.img_detail,Product.img_thumb,Product.img_detail,ProductI18n.description,ProductI18n.seller_note'));
        $scorelog_list = array();
        if (!empty($products_list) && sizeof($products_list) > 0) {
            $cate_pro_ids = array();
            foreach ($products_list as $k => $v) {
                $cate_pro_ids[] = $v['Product']['id'];
            }
            $_scorelog_list = $this->ScoreLog->find('all', array('fields' => array('count(value) as countnum', 'sum(value) as sumnum', 'ScoreLog.score_id', 'ScoreLog.type_id'), 'conditions' => array('ScoreLog.type' => 'P', 'ScoreLog.type_id' => $cate_pro_ids), 'group' => 'ScoreLog.type_id,ScoreLog.score_id'));

            if (!empty($_scorelog_list) && sizeof($_scorelog_list) > 0) {
                foreach ($_scorelog_list as $k => $v) {
                    $v[0]['average'] = $v[0]['sumnum'] / $v[0]['countnum'];
                    $scorelog_list[$v['ScoreLog']['type_id']][$v['ScoreLog']['score_id']] = $v[0];
                }
            }
        }
        $product['products_list'] = $products_list;
        $product['paging'] = $pages;
        $product['scorelog_list'] = $scorelog_list;

        $this->Score->set_locale(LOCALE);
        $score_conditions['Score.status'] = 1;
        $score_conditions['Score.type'] = 'P';
        $score_conditions['ScoreI18n.value !='] = '';
        $score_list = $this->Score->find('all', array('conditions' => $score_conditions));
        $product['score_list'] = $score_list;

        //教师团队的子分类数组绑定下拉
        $parent_category = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $id), 'fields' => 'CategoryProduct.parent_id,CategoryProductI18n.name'));
        $child_category = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.parent_id' => $parent_category['CategoryProduct']['parent_id']), 'fields' => 'CategoryProduct.id,CategoryProductI18n.name'));
        //pr($child_category);
        $product['category'] = $child_category;
        $product['category_id'] = $id;
        /*$sm=$this->CategoryProduct->get_module_category_product($this->params);*/
        //获取当前分类的轮播图片
        $flash_list = $this->Flash->find('first', array('conditions' => array('Flash.page_id' => $id, 'Flash.page' => 'PC')));
        $this->set('flash_list', $flash_list);
        $this->set('sm', $product);
        //$this->layout="default_category";
        //pr($sm);
        //id不存在跳转
        if (!is_numeric($id) || $id < 0) {
            $this->pageTitle = $this->ld['parameter_error'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['parameter_error'], '/', 5);

            return;
        }
        //取该分类的信息
        if (empty($this->CategoryProduct->allinfo['P']['assoc'][$id])) {
            $this->pageTitle = $this->ld['classificatory'].$this->ld['not_exist'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['classificatory'].$this->ld['not_exist'], '/', 5);

            return;
        }

        //面包屑
        //$tempurl=$this->server_host."/".$category['CategoryProductI18n']['name']."-PC".$category['CategoryProduct']['id'].".html";
        $tempurl = '/categories/'.$category['CategoryProduct']['id'];
        $r_ur_heres[] = array('name' => $category['CategoryProductI18n']['name'], 'url' => $tempurl);

        //分类的根
        $root_category_id = $id;
        $i = 0;
        while (!empty($this->CategoryProduct->allinfo['P']['assoc'][$root_category_id]['CategoryProduct']['parent_id']) && $i++ < 10) {
            $root_category_id = $this->CategoryProduct->allinfo['P']['assoc'][$root_category_id]['CategoryProduct']['parent_id'];
            //$tempurl=$this->server_host."/".$this->CategoryProduct->allinfo['P']['assoc'][$root_category_id]['CategoryProductI18n']['name']."-PC".$this->CategoryProduct->allinfo['P']['assoc'][$root_category_id]['CategoryProduct']['id'].".html";
            $tempurl = '/categories/'.$this->CategoryProduct->allinfo['P']['assoc'][$root_category_id]['CategoryProduct']['id'];
            $r_ur_heres[] = array('name' => $this->CategoryProduct->allinfo['P']['assoc'][$root_category_id]['CategoryProductI18n']['name'], 'url' => $tempurl);
        }
        $ur_heres = array_reverse($r_ur_heres);
        foreach ($ur_heres as $v) {
            $this->ur_heres[] = $v;
        }

       //一级分类
       $top_categroy_id = $this->CategoryProduct->get_top_category_id($id);
        $this->set('top_categroy_id', $top_categroy_id);
        //seo

        if (empty($limit)) {
            $limit = isset($this->configs['products_category_page_size']) ? $this->configs['products_category_page_size'] : ((!empty($limit)) ? $limit : 20);
        }
        if (empty($showtype)) {
            $showtype = isset($this->configs['products_list_showtype']) ? $this->configs['products_list_showtype'] : ((!empty($showtype)) ? $showtype : 'L');
        }
        $order_fields = array('Product.created','Product.shop_price','Product.sale_stat');
        if (!empty($order_field) && in_array($order_field, $order_fields)) {
        } else {
            $order_field = 'Product.created';
            $order_type = 'desc';
        }
        if ($limit == 'all') {
            $limit = 99999;
        }

        //判断排序
         if (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] !== 'forsale' && $this->configs['product_order'] !== 'category') {
             $options['order'] = $this->configs['product_order'];
         } elseif (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] == 'category') {
             $options['order'] = 'category_id';
         } else {
             $options['order'] = $order_field.' '.$order_type;
         }

       //$options['order'] = $order_field.' '.$order_type;

        $options['limit'] = $limit;
        $options['page'] = $page;
        $options['set'] = 'products';
       // pr($this->configs['product_order']);
       // pr($options);
      //  die; 

        if ($min_price != -1) {
            $conditions['AND']['Product.shop_price >='] = $min_price;
            $this->set('min_price', $min_price);
        }
        if ($max_price != -1) {
            $conditions['AND']['Product.shop_price <='] = $max_price;
            $this->set('max_price', $max_price);
        }
        $filter_condition = array();
        //按价格排序  
        if ($filters_attr_price != 0) {
            //	$price_condition_array=array();
            $price_array = explode(';', $filters_attr_price);
            foreach ($price_array as $k => $v) {
                $price_condition = explode('-', $v);
                $product_ids_price_array = $this->Product->find('list', array('fields' => 'Pr
        		oduct.id', 'conditions' => array('Product.shop_price >=' => $price_condition[0], 'Product.shop_price <=' => $price_condition[1])));
                $filter_condition[]['and']['Product.id'] = $product_ids_price_array;
                $conditions[]['and']['Product.id'] = $product_ids_price_array;
            }
        }
        if (!empty($filters)) {
            $filters_array_condition = '';
            $filters_array = explode(';', $filters);
            foreach ($filters_array as $k => $v) {
                $filer_value = explode(',', $v);
            //	pr($filer_value);
                $product_ids_array = $this->ProductAttribute->find('list', array('fields' => 'ProductAttribute.product_id', 'conditions' => array('ProductAttribute.attribute_id' => $filer_value[0], 'ProductAttribute.attribute_value' => $filer_value[1], 'ProductAttribute.locale' => $this->locale)));
                $filter_condition[]['and']['Product.id'] = $product_ids_array;
                $conditions[]['and']['Product.id'] = $product_ids_array;
            }
//        	pr($filter_condition);die;
        }
      //  pr($filter_condition);
        $this->set('filters_attr_price', $filters_attr_price);
        $this->set('filters', $filters);
        $category_count = count($this->CategoryProduct->allinfo['P']['subids'][$id]);
        $this->set('filter_condition', $filter_condition);
        $this->set('category_count', $category_count);
           /* 设置文章模板布局 */
        if (!empty($category['CategoryProduct']['layout'])) {
            $this->layout = $category['CategoryProduct']['layout'];
        }

        /* 设置文章模板 */
        if (!empty($category['CategoryProduct']['template'])) {
            //	echo $category['CategoryProduct']['template'];
            $this->render($category['CategoryProduct']['template'], $this->layout);
        }
    }

    /**
     * 函数detail 显示详细.
     *
     * @param $id
     */
    public function detail($id)
    {
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['promotion'].$this->ld['activity'], 'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->pageTitle = $this->ld['promotion'].$this->ld['activity'].'- '.$this->configs['shop_title'];

        $info = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $id)));
        $this->set('info', $info);
    }

    /**
     * 函数bestbefore 过往精品.
     *
     * @param $id
     */
    public function bestbefore($id)
    {
        $this->page_init();
        $cat = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $id)));
        //$tempurl=$this->server_host."/".$cat['CategoryProductI18n']['name']."-PC".$id.".html";
        $tempurl = '/categories/'.$cat['CategoryProduct']['id'];
        $this->ur_heres[] = array('name' => $cat['CategoryProductI18n']['name'], 'url' => $tempurl);
        $this->ur_heres[] = array('name' => '过往精品', 'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->pageTitle = '过往精品'.'- '.$this->configs['shop_title'];
        $bestbefore = $this->Product->find('all', array('conditions' => array('Product.forsale' => 0, 'Product.sale_stat >' => '0', 'Product.category_id' => $id)));
        foreach ($bestbefore as $k => $v) {
            $bestbefore[$k] = $this->Product->product_locale_format($v, 20);
        }

        $this->set('bestbefore', $bestbefore);
    }
}
