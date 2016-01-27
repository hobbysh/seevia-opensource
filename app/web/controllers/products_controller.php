<?php

/*****************************************************************************
 * Seevia 商品
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
uses('sanitize');
/**attr_id_infos
    *这是一个名为 ProductsController 的商品控制器
    *
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses
    */
    /*
    *商品显示
    *
    *对于Products这张表的查寻
    *
    *@author   hechang 
    *@version  $Id$
    *@package  模块管理（文档标记）
    */
App::import('Vendor', 'weibo2', array('file' => 'saetv2.php'));
App::import('Vendor', 'qq', array('file' => 'Tencent.php'));
class ProductsController extends AppController
{
    public $name = 'Products';
    public $components = array('Pagination','RequestHandler','Captcha','Email','Phpexcel'); // Added
    public $helpers = array('Html','Pagination','Flash','Cache','Time','Xml','Rss','Text'); // Added
    public $uses = array('BrandI18n','UserAction','ProductPublicTemplate','Product','ProductI18n','Flash','UserMessage','ProductsCategory','CategoryProduct','CategoryFilter','ProductAlsobought','ProductGallery','ProductRelation','ProductArticle','Article','Comment','ProductType','UserRank','ProductRank','BookingProduct','Brand','ProductAttribute','ProductTypeAttribute','ProductLocalePrice','Tag','ProductVolume','UserProductGallery','User','TagI18n','BookingProduct','MailTemplate','Tag','SynchroUser','UserApp','OauthLog','UserLike','PackageProduct','ScoreLog','SkuProduct','Enquiry','Attribute','UserFavorite','ProductStyle');
    
    /**
     *显示.
     *
     *@param $id
     */
    public function clear()
    {
        unset($_SESSION['pro_attr']);
        die();
    }

    public function show()
    {
        pr($_SESSION['pro_attr']);
        die();
    }

    public function view_world($id = '')
    {
        $this->layout = 'product_world';
        $this->view($id);
    }

    public function view($id = '', $page = 1)
    {
        $id_count = explode('_', $id);
        if (count($id_count) == 2) {
            if (isset($_SESSION['svcart']['products'][$id])) {
                if (isset($_SESSION['svcart']['products'][$id]['Product'])) {
                    $file_url = $_SESSION['svcart']['products'][$id]['Product']['file_url'];
                    $id = isset($id_count[0]) ? $id_count[0] : '';
                    $_SESSION['product'][$id]['file_show_flag'] = 1;
                    $_SESSION['product'][$id]['file_url'] = $file_url;
                    $this->set('file_url', $file_url);
                    $this->set('file_show_flag', 1);
                }
            }
            $id = isset($id_count[0]) ? $id_count[0] : '';
        }
        $result = $this->Product->check_product($id, $this->ld, $this->configs);
        if (!$result['flag']) {
            Configure::write('debug', 0);
            $this->flash($result['title'], '/', '');
        }
        if (isset($_GET['file_url']) && $_GET['file_url'] == 1) {
            $_SESSION['product'][$id]['file_show_flag'] = 1;
            $file_url = isset($_SESSION['product'][$id]['file_url']) ? $_SESSION['product'][$id]['file_url'] : '';
            $this->set('file_url', $file_url);
            $this->set('file_show_flag', $_GET['file_url']);
        }
        $this->set('product_id', $id);
        unset($_SESSION['pro_attr'][$id]);
        $_SESSION['pro_attr']['upload_img_pid'] = '';
        //喜欢该商品的人
        $like_list = $this->UserLike->find('all', array('fields' => array('UserLike.*', 'User.id', 'User.img01'), 'conditions' => array('UserLike.type' => 'P', 'UserLike.type_id' => $id, 'UserLike.action' => 'like'), 'order' => 'UserLike.created', 'limit' => 24, 'joins' => array(array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('UserLike.user_id = User.id'),
                         ))));
        $this->set('like_num', count($like_list));
        $this->set('like_list', $like_list);
        //取商品数据
        $product = $this->Product->find('first', array('conditions' => array('Product.id' => $id)));
        //$this->set('all_app_codes', $this->all_app_codes);
        //$this->set('app_infos',$this->app_infos);
        //title
        $this->pageTitle = $product['ProductI18n']['name'].' - '.$this->configs['shop_title'];
        //面包屑
        $r_ur_heres[] = array('name' => $product['ProductI18n']['name'], 'url' => '');
        $root_category_id = $product['Product']['category_id'];
        $category_parent = $this->Product->find('all', array('conditions' => array('category_id' => $root_category_id), 'limit' => 1));

//         if(!empty($category_parent)){
//        	$catroot_category_id=$category_parent[0]['Product']['category_id'];
//	        $i=0;
//	        $cat_parent =$this->CategoryProduct->find('all',array("conditions"=>array('CategoryProduct.id'=>$catroot_category_id)));
//	        while(!empty($cat_parent) && $i++<10){
//	        	//$tempurl=$this->server_host."/".$cat_parent[0]['CategoryProductI18n']['name']."-PC".$cat_parent[0]['CategoryProduct']['id'].".html";
//	        	$tempurl=$this->server_host."/categories/".$cat_parent[0]['CategoryProduct']['id'];
//	        	$r_ur_heres[] = array('name' => $cat_parent[0]['CategoryProductI18n']['name'], 'url' =>$tempurl);
//	        	$catroot_category_id=$cat_parent[0]['CategoryProduct']['parent_id'];
//	        	$cat_parent=$this->CategoryProduct->find('all',array("conditions"=>array('CategoryProduct.id'=>$catroot_category_id)));
//	        }
//        }
        $ur_heres = array_reverse($r_ur_heres);
        foreach ($ur_heres as $v) {
            $this->ur_heres[] = $v;
        }
        $this->set('meta_description', $product['ProductI18n']['meta_description'].' '.$this->configs['seo-des']);
        $this->set('meta_keywords', $product['ProductI18n']['meta_keywords'].' '.$this->configs['seo-key']);
        $favorites_flag = true;
        //判断当前用户是否已收藏该商品
        if (isset($_SESSION['User'])) {
            $user_id = $_SESSION['User']['User']['id'];
            //判断是否收藏
            $favorites = $this->UserFavorite->find('first', array('conditions' => array('type' => 'p', 'type_id' => $id, 'user_id' => $user_id)));
            if (empty($favorites)) {
                $favorites_flag = true;
            } else {
                $favorites_flag = false;
            }
        }
        $this->set('favorites_flag', $favorites_flag);
        //是否可以评论
        $can_comment = false;
        if (!empty($product)) {
            //记录商品浏览历史
            $pro_view_log = array();
            if (isset($_COOKIE['pro_view_log']) && !empty($_COOKIE['pro_view_log'])) {
                $pro_view_log = explode(';', $_COOKIE['pro_view_log']);
            }
            if (!in_array($product['Product']['id'], $pro_view_log)) {
                $pro_view_log[] = $product['Product']['id'];
                $pro_view_log_txt = implode(';', $pro_view_log);
                setcookie('pro_view_log', $pro_view_log_txt, time() + 60 * 60 * 24, '/');
            }
            $product['Product']['view_stat'] = $product['Product']['view_stat'] + 1;
            $this->Product->save($product);
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('Order');
                $this->loadModel('OrderProduct');

                if (!empty($_SESSION['User']['User']['id'])) {
                    $order_ids = $this->Order->find('list', array('conditions' => array('Order.status' => 1, 'shipping_status' => 2, 'Order.user_id' => $_SESSION['User']['User']['id']), 'fields' => array('Order.id')));
                    if (!empty($order_ids)) {
                        $sku_pro_code = $this->SkuProduct->find('list', array('fields' => 'SkuProduct.sku_product_code', 'conditions' => array('SkuProduct.product_code' => $product['Product']['code'])));
                        $can_comment_cond['OrderProduct.Order_id'] = $order_ids;
                        if (!empty($sku_pro_code)) {
                            $can_comment_cond['OrderProduct.product_code'] = $sku_pro_code;
                        } else {
                            $can_comment_cond['OrderProduct.product_id'] = $id;
                        }
                        $can_comment_ = $this->OrderProduct->find('count', array('conditions' => $can_comment_cond));
                        $can_comment = $can_comment_ > 0 ? true : false;
                    }
                }
            } elseif (constant('Version') == 'o2o') {
                if (!empty($_SESSION['User']['User']['id'])) {
                    $enquiries_list = $this->Enquiry->find('count', array('conditions' => array('Enquiry.part_num' => $product['Product']['code'], 'Enquiry.user_id' => $_SESSION['User']['User']['id'], 'Enquiry.status' => '3')));
                    $can_comment = $enquiries_list > 0 ? true : false;
                }
            }
        }
        $this->set('can_comment', $can_comment);

        //同步授权分享图片
        $UserAppImg['QQWeibo'] = 'qq.jpg';
        $UserAppImg['QQ'] = 'qie.png';
        $UserAppImg['SinaWeibo'] = 'sina.png';
        $UserAppImg['Google'] = 'google.png';
        $UserAppImg['Facebook'] = 'bule_face.png';
        //同步授权分享
        $UserApp_list = $this->UserApp->find('all', array('fields' => array('UserApp.type'), 'conditions' => array('UserApp.status' => '1', 'UserApp.type !=' => 'Wechat')));
        $SynchroUser_list = array();

        //同步授权分享状态
        if (isset($_SESSION['User']['User']['id'])) {
            $SynchroUser_list = $this->SynchroUser->find('all', array('conditions' => array('SynchroUser.User_id' => $_SESSION['User']['User']['id'])));
        }

        foreach ($UserApp_list as $k => $v) {
            $UserApp_list[$k]['status'] = '0';
            $UserApp_list[$k]['img'] = isset($UserAppImg[$v['UserApp']['type']]) ? $UserAppImg[$v['UserApp']['type']] : '';
            $UserApp_list[$k]['UserApp']['type'] = strtolower($v['UserApp']['type']);
            foreach ($SynchroUser_list as $kk => $vv) {
                if ($v['UserApp']['type'] == $vv['SynchroUser']['type']) {
                    $UserApp_list[$k]['status'] = $vv['SynchroUser']['status'];
                }
            }
        }

        $this->set('UserApp_list', $UserApp_list);
        //商品类型
    //	if($product['Product']['product_type_id'] > 0){
        $product_type = $this->ProductType->find('first', array('conditions' => array('ProductType.id' => $product['Product']['product_type_id'])));
        $this->set('product_type', $product_type);
        $tmp_p = $this->ProductAlsobought->find_product_alsobought($id);
        $tmp_also = array();
        foreach ($tmp_p as $k => $v) {
            $tmp_also[$v['ProductAlsobought']['alsobought_product_id']] = $this->Product->findbyid($v['ProductAlsobought']['alsobought_product_id']);
        }
        $this->data['price_product'] = $tmp_also;
        $this->params['id'] = $id;
        $this->params['pass'] = $product['Product']['category_id'];
        $this->params['page'] = $page;
        $this->params['option_type_id'] = $product['Product']['option_type_id'];
        $this->params['code'] = $product['Product']['code'];
        $this->params['ControllerObj'] = $this;//控制器对象
        $this->page_init($this->params);
        //销售排行
        $this->set('toplist', $this->Product->sale_rank());
        if (!is_numeric($id) || $id < 1) {
            $this->flash($this->ld['invalid_id'], '/', '');
            //return;
        }
        $this->Product->ids[$id] = $id;
        $product['ProductI18n']['description'] = str_replace('img.ioco.cn', 'img.seeworlds.cn', $product['ProductI18n']['description']);
    }

    /*********高级搜索开始*********/
    /*********ad_search   *********/
    /**
     *高级搜索.
     *
     *@param $type
     *@param $keywords
     *@param $category_id
     *@param $brand_id
     *@param $min_price
     *@param $max_price
     *@param $page
     *@param $orderby
     *@param $rownum
     *@param $showtype
     */
    public function advancedsearch($keyword = '', $page = 1, $limit = 12, $order_field = 0, $order_type = 0, $showtype = 0, $type = 0, $brand_id = 0, $min_price = 0, $max_price = 0, $flag = 0)
    {
        $this->set('search_type', 'p');
        //Todo 改成后台可以控制
        if (isset($_SESSION['template_use']) && $_SESSION['template_use'] == 'arcotek') {
            $limit = 50;
        }
        $this->page_init();
        $order_field = UrlDecode($order_field);
        $order_type = UrlDecode($order_type);
        $limit = UrlDecode($limit);
        $showtype = 'L';
        //$keyword = trim($keyword, " ");
       // $keyword = UrlDecode($keyword);

       //带冒号的关键字，对GET过来的参数做替代处理
       if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
           $keyword = $_GET['keyword'];
       }
        if ($keyword == 'All') {
            $keyword = '';
        }
        $strkeyword = $keyword;
        if ($strkeyword == '') {
            $strkeyword = 'All Product';
        }
        $this->product_order_field = $order_field;
        if (trim($keyword) != '') {
            $keyword = preg_split('#\s+#', $keyword);
        }
        //pr($keyword);
        //面包屑
        $this->ur_heres[] = array('name' => $this->ld['search'].':'.$strkeyword, 'url' => '');
        //搜索轮播
        $flash_condition['flash_type'] = 'AS';
        $flash_list = $this->Flash->get_module_infos($flash_condition);
        $this->set('flash_list', $flash_list);
        $this->set('meta_description', $strkeyword);
        $this->set('meta_keywords', $strkeyword);
        if ($order_field == 'Product.created') {
            $this->pageTitle = '新品上架 - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];
        } elseif ($order_field == 'Product.sale_stat') {
            $this->pageTitle = '火爆团购- '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];
        } else {
            $this->pageTitle = $strkeyword.' - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];
        }
        if (empty($limit)) {
            $limit = isset($this->configs['products_category_page_size']) ? $this->configs['products_category_page_size'] : ((!empty($limit)) ? $limit : 20);
        }
        if (empty($showtype)) {
            $showtype = isset($this->configs['products_list_showtype']) ? $this->configs['products_list_showtype'] : ((!empty($showtype)) ? $showtype : 'L');
        }
        $order_fields = array('Product.created','Product.sale_stat','Product.shop_price');
        if (!empty($order_field) && in_array($order_field, $order_fields)) {
        } else {
            $order_field = 'Product.sale_stat';
            $order_type = 'desc';
        }
        if ($limit == 'all') {
            $limit = 99999;
        }
        $this->set('search_eye', 1);
        $conditions['AND']['Product.status'] = 1;
        $conditions['AND']['Product.forsale'] = 1;
        $conditions['AND']['Product.bestbefore'] = 0;
        $conditions2['OR']['AND']['Product.status'] = 1;
        $conditions2['OR']['AND']['Product.forsale'] = 1;
        $conditions2['OR']['AND']['Product.bestbefore'] = 0;
        $conditions2['OR']['OR']['Product.recommand_flag'] = 1;
        $conditions2['OR']['OR']['Product.promotion_status'] = 1;
        if ($type == 'promotion') {//促销商品
        } elseif ($type == 'new_arrival') {
            //新品
            $order = 'Product.created desc';
        } elseif ($type == 'recommend') {
            //推荐
            $conditions['AND']['Product.recommand_flag'] = 1;
        } else {
            //type不合法时跳转报错
            //$this->render('/errors/');
        }
    //	$bran_sel=array();
    // $bran_sel=$this->Brand->find('list',array('fields'=>array('Brand.id','BrandI18n.name'));
        //模糊搜索
        if (isset($this->configs['product_search_type']) && $this->configs['product_search_type'] == '0') {
            if (is_array($keyword) && sizeof($keyword) > 0) {
                foreach ($keyword as $k => $v) {
                    $conditions['AND']['OR'][0]['OR'][$k]['Product.code like'] = "%$v%";
                    $conditions['AND']['OR'][1]['OR'][$k]['ProductI18n.name like'] = "%$v%";
                    $conditions['AND']['OR'][2]['OR'][$k]['ProductI18n.meta_keywords like'] = "%$v%";
                    $brand_ids_array = $this->BrandI18n->find('all', array('fields' => array('BrandI18n.brand_id'), 'conditions' => array('BrandI18n.name like' => "%$v%")));
                    if (is_array($brand_ids_array) && isset($brand_ids_array) && !empty($brand_ids_array) && sizeof($brand_ids_array) > 0) {
                        $brand_ids = array();
                        foreach ($brand_ids_array as $kk => $vv) {
                            $brand_ids[$kk] = $vv['BrandI18n']['brand_id'];
                        }
                        $conditions['AND']['OR'][3]['OR'][$k]['Product.brand_id'] = $brand_ids;
                    }
                    $tag_conditions['and']['OR'][$k]['name like'] = "%$v%";
                }
                $tag_in = $this->Tag->find('first');
                $tag_conditions = array();
                $keywords = array();
                if (is_array($keyword)) {
                    foreach ($keyword as $k => $v) {
                        $tag_conditions['and']['or'][$k]['TagI18n.name like'] = "%$v%";
                    }
                }
                $tag_conditions['and']['type'] = 'P';
            //  $tag_conditions['and']['TagI18n.name like'] ="%$keyword%";
                $tag_infos = $this->Tag->find('all', array('conditions' => $tag_conditions, 'fields' => 'Tag.type_id', 'group' => 'Tag.type_id'));
                if (!empty($tag_infos)) {
                    $pids = array();
                    foreach ($tag_infos as $t) {
                        $pids[] = $t['Tag']['type_id'];
                    }
                    $conditions['AND']['OR'][4]['OR']['Product.id'] = $pids;
                }
            }
            $this->set('keyword', $keyword);
        } elseif (isset($this->configs['product_search_type']) && $this->configs['product_search_type'] == '1') {
            //绝对搜索
         if (is_array($keyword) && sizeof($keyword) > 0) {
             foreach ($keyword as $k => $v) {
                 $conditions['AND']['OR'][0]['and'][$k]['Product.code like'] = "%$v%";
                 $conditions['AND']['OR'][1]['and'][$k]['ProductI18n.name like'] = "%$v%";
                 $conditions['AND']['OR'][2]['and'][$k]['ProductI18n.meta_keywords like'] = "%$v%";
                 $brand_ids_array = $this->BrandI18n->find('all', array('fields' => array('BrandI18n.brand_id'), 'conditions' => array('BrandI18n.name like' => "%$v%")));
                 if (is_array($brand_ids_array) && isset($brand_ids_array) && !empty($brand_ids_array) && sizeof($brand_ids_array) > 0) {
                     $brand_ids = array();
                     foreach ($brand_ids_array as $kk => $vv) {
                         $brand_ids[$kk] = $vv['BrandI18n']['brand_id'];
                     }
                     $conditions['AND']['OR'][3]['and'][$k]['Product.brand_id'] = $brand_ids;
                 }
                 $tag_conditions['and']['OR'][$k]['name'] = $v;
             }

             $tag_in = $this->Tag->find('first');
             $tag_conditions = array();
             $keywords = array();
             if (is_array($keyword)) {
                 foreach ($keyword as $k => $v) {
                     $tag_conditions['and']['or'][$k]['TagI18n.name'] = $v;
                 }
             }
            // $tag_conditions['and']['or']['type'] ='P';
            //    $tag_conditions['and']['TagI18n.name like'] ="%$keyword%";
            $tag_infos = $this->Tag->find('all', array('conditions' => $tag_conditions, 'fields' => 'Tag.type_id', 'group' => 'Tag.type_id'));
             if (!empty($tag_infos)) {
                 $pids = array();
                 foreach ($tag_infos as $t) {
                     $pids[] = $t['Tag']['type_id'];
                 }
                 $conditions['AND']['OR'][4]['Product.id'] = $pids;
             }
         }
            $this->set('keyword', $keyword);
        }
        if ($strkeyword == 'All Product') {
            $this->set('keyword', '');
        } else {
            $this->set('keyword', $strkeyword);
        }
        if (isset($keyword) && !empty($keyword)) {
            $tag_in = $this->Tag->find('first');
            $tag_conditions = array();
            $keywords = array();
            if (is_array($keyword)) {
                foreach ($keyword as $k => $v) {
                    $tag_conditions['and']['or'][$k]['TagI18n.name like'] = "%$v%";
                }
            }
            $tag_conditions['and']['type'] = 'P';
            //    $tag_conditions['and']['TagI18n.name like'] ="%$keyword%";
                $tag_infos = $this->Tag->find('all', array('conditions' => $tag_conditions, 'fields' => 'Tag.type_id', 'group' => 'Tag.type_id'));
            if (!empty($tag_infos)) {
                $pids = array();
                foreach ($tag_infos as $t) {
                    $pids[] = $t['Tag']['type_id'];
                }
                $conditions['AND']['OR'][4]['Product.id'] = $pids;
            }
        }
        $this->Attribute->set_locale(LOCALE);
        $public_attr_ids = $this->ProductTypeAttribute->getattrids(0);

        $attr_id_infos = $this->Attribute->find('list', array('conditions' => array('Attribute.id' => $public_attr_ids), 'fields' => array('Attribute.code', 'Attribute.id')));
        $this->set('attr_id_infos', $attr_id_infos);
        $options = array();
        $options['conditions'] = $conditions;
        $options2 = array();
        $options2['conditions'] = $conditions2;
        //排序的判断
        if (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] !== 'forsale' && $this->configs['product_order'] !== 'category') {
            $options['order'] = $this->configs['product_order'];
        } elseif (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] == 'category') {
            $options['order'] = 'category_id';
        } else {
            $options['order'] = $order_field.' '.$order_type;
        }
       // $options['order'] =$this->configs['product_order'];
        $options['limit'] = $limit;
        $options['page'] = $page;
        $pro = $this->Product->find_all_products($options);
        //pr($options);
        $options['set'] = 'products';
        $options2['set2'] = 'products2';
        if ($min_price != 0 || $max_price != 0) {
            $conditions['AND']['Product.shop_price >='] = $min_price;
            $conditions['AND']['Product.shop_price <='] = $max_price;
        }
        //var_dump($pro);
        //品牌1
        if ($brand_id != 0) {
            $conditions['AND']['Brand.id'] = $brand_id;
            $this->set('cat_eye', $brand_id);
        }
        $options['conditions'] = $conditions;
        //$options['conditions'] = $conditions;
         $this->Product->find_all_products($options);
        //$this->Product->find_all_products($options2);
         //分页start
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'products','action' => 'advancedsearch','keyword' => $strkeyword,'page' => $page,'limit' => $limit,'order_field' => $order_field,'order_type' => $order_type,'showtype' => $showtype,'type' => $type,'brand_id' => $brand_id,'min_price' => $min_price,'max_price' => $max_price);
        //分页参数
        $page_options = array('page' => $page,'show' => $limit,'modelClass' => 'Product');
        $page = $this->Pagination->init($conditions, $parameters, $page_options); // Added
        //分页end
        $this->set('pages_list', $page);
        //品牌
       // foreach($pro as $k=>$v){
        //	$x=$this->Brand->findbyid($v['Product']['brand_id']);
        //	$brand_array[$v['Product']['brand_id']]= array();
        //	$brand_array[$v['Product']['brand_id']]['name']=$x['BrandI18n']['name'];
        //	$brand_array[$v['Product']['brand_id']]['cat']=$v['Product']['category_id'];
       // }
        //var_dump($brand_array);
        //$this->set("brand",$brand_array);
        //$this->set("pro",$pro);
         $this->set('price_eye', $max_price);
        //$this->Product->find_all_products($options); //model
        //$this->set('keyword',$keyword);
        if (!empty($pro)) {
            $brand_array = array();
            $category_array = array();
            $brand_ids = array();
            $category_ids = array();
            $brand_names = array();
            $categories = array();
            foreach ($pro as $k => $v) {
                if (!in_array($v['Product']['brand_id'], $brand_ids)) {
                    $brand_ids[] = $v['Product']['brand_id'];
                }
                if (!in_array($v['Product']['category_id'], $category_ids)) {
                    $category_ids[] = $v['Product']['category_id'];
                }
            }
            $brand_array = $this->Brand->find('all', array('fields' => 'Brand.id,BrandI18n.name'));
            foreach ($brand_array as $b) {
                $brand_names[$b['Brand']['id']] = $b['BrandI18n']['name'];
            }
            if (!empty($category_ids)) {
                $category_array = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.id' => $category_ids), 'fields' => 'CategoryProduct.id,CategoryProductI18n.name'));
                foreach ($category_array as $b) {
                    $categories[$b['CategoryProduct']['id']] = $b['CategoryProductI18n']['name'];
                }
            }
            $this->set('brand_names', $brand_names);
            $this->set('categories', $categories);
        }
        $this->layout = 'default_search';
        if (isset($_POST['flag']) && $_POST['flag'] == 1) {
            //执行导出(高级搜索)

            $limit1 = 99999;
            $options = array();
            $options['conditions'] = $conditions;
            if ($this->configs['product_order'] == 'category') {
                $options['order'] = 'category_id';
            } else {
                $options['order'] = $this->configs['product_order'];
            }
            $options['limit'] = $limit1;
            $options['page'] = 1;
           // $options['set'] = 'products';
               $pro1 = $this->Product->find_all_products($options);//搜索结果
               $this->Attribute->set_locale(LOCALE);
            $public_attr_ids = $this->ProductTypeAttribute->getattrids(0);

            $pubile_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $public_attr_ids), 'fields' => 'Attribute.id,AttributeI18n.name'));
            $pat = array();
            if (!empty($pubile_attr_info)) {
                foreach ($pubile_attr_info as $k => $p) {
                    $pat[$p['Attribute']['id']] = $p['AttributeI18n']['name'];
                }
            }
            //TODO 改成后台可以定义 列值，属性应用需要判断
                $allproduct = array();
            Configure::write('debug', 0);
            $data = array();
            $data[] = array('Description','Part No.','Mfg','Qty','D/C','USD','Delivery','Notes');
            $allproduct = $data;
            $ii = 0;
            foreach ($pro1 as $k => $v) {
                ++$ii;
                $allproducts = array();
                $pab = array();
                foreach ($v['ProductAttribute' ] as $pa) {
                    if ($pa[ 'attribute_id'] == $attr_id_infos[ 'dc' ] && !empty($pa['attribute_value'])) {
                        $pab[$attr_id_infos[ 'dc' ]] = $pa['attribute_value'];
                    }
                    if ($pa[ 'attribute_id'] == $attr_id_infos[ 'delivery' ] && !empty($pa['attribute_value'])) {
                        $pab[$attr_id_infos[ 'delivery' ]] = $pa['attribute_value'];
                    }
                    if ($pa[ 'attribute_id'] == $attr_id_infos[ 'notes' ] && !empty($pa['attribute_value'])) {
                        $pab[$attr_id_infos[ 'notes' ]] = $pa['attribute_value'];
                    }
                }
                $product = $this->ProductI18n->find('first', array('conditions' => array('ProductI18n.product_id ' => $v['Product']['id']), 'recursive' => -1));
                $allproducts[] = $product['ProductI18n']['name'];
                $allproducts[] = $v['Product']['code'];
                $allproducts[] = (isset($brand_names[$v['Product']['brand_id']]) ? $brand_names[$v['Product']['brand_id']] : '-');//mfg
                $allproducts[] = $v['Product']['quantity'];
                $allproducts[] = (isset($pab[$attr_id_infos[ 'dc' ]]) ? $pab[$attr_id_infos[ 'dc' ]] : '-');//dc
                $allproducts[] = (isset($v['Product']['custom_price']) && $v['Product']['custom_price'] != '' ? $v['Product']['custom_price'] : $v['Product']['shop_price']);
                $allproducts[] = (isset($pab[$attr_id_infos[ 'delivery' ]]) ? $pab[$attr_id_infos[ 'delivery' ]] : '-');//delivery
                $allproducts[] = (isset($pab[$attr_id_infos['notes']]) ? $pab[$attr_id_infos['notes']] : '-');//notes
                $allproduct[] = $allproducts;
            }
            $this->Phpexcel->output('products_export_'.date('YmdHis').'.xls', $allproduct);
            exit();
            die;
        }
    }

    //添加提问
    public function ajax_add_message()
    {
        Configure::write('debug',0);
        $this->layout="ajax";
        if ($this->RequestHandler->isPost()) {
		if (isset($_POST)) {
			$_POST=$this->clean_xss($_POST);
		}
            $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
            $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;
            if(!empty($product_id)){
	            $pro_info = $this->Product->find('first', array('fields' => array('ProductI18n.name'), 'conditions' => array('Product.id' => $product_id)));
	            if (!empty($pro_info)) {
	                $msg_title = $pro_info['ProductI18n']['name'];
	            } else {
	                $msg_title = '';
	            }
	            $message = array(
	                            'from_id' => isset($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '0',
	                            'user_id' => isset($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '0',
	                            'user_name' => isset($_SESSION['User']['User']['name']) ? $_SESSION['User']['User']['name'] : '',
	                            'msg_title' => $msg_title,
	                            'value_id' => $product_id,
	                            'type' => 'P',
	                            'msg_content' => isset($_POST['content']) ? $_POST['content'] : '',
	                            'status' => 1,
	                            );
	            $result['message'] = $this->ld['message'].$this->ld['successfully'].' '.$this->ld['waiting_reply'];
	            $this->UserMessage->save($message);
            }else if(!empty($parent_id)){
            		$msg_title = $this->ld['reply'];
            		$message = array(
            				'parent_id'=>$parent_id,
	                            'from_id' => isset($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '0',
	                            'user_id' => isset($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '0',
	                            'user_name' => isset($_SESSION['User']['User']['name']) ? $_SESSION['User']['User']['name'] : '',
	                            'msg_title' => $this->ld['reply'],
	                            'value_id' => '0',
	                            'type' => 'P',
	                            'msg_content' => isset($_POST['content']) ? $_POST['content'] : '',
	                            'status' => 1,
	                            );
			$result['message'] = $this->ld['reply'].$this->ld['successfully'];
			$this->UserMessage->save($message);
            }
            if(!empty($result)){
	            $shop_name = $this->configs['shop_name'];
	            $send_date = date('Y-m-d');
	            $email_text = $msg_title.'<br>'.$_POST['content'];
	            /* 商店网址 */
	            $shop_url = $this->server_host.$this->webroot;
	            $receiver_email = $this->configs['vip-email'].';'.$this->configs['vip-email'];
	            $mail_send_queue = array(
	                                    'id' => '',
	                                    'sender_name' => isset($_SESSION['User']['User']['name']) ? $_SESSION['User']['User']['name'] : $shop_name,
	                                    'receiver_email' => $receiver_email,
	                                    'cc_email' => ';',
	                                    'bcc_email' => ';',
	                                    'title' => $msg_title,
	                                    'html_body' => $email_text,
	                                    'text_body' => $email_text,
	                                    'sendas' => 'html',
	                                    'flag' => 0,
	                                    'pri' => 0,
	                                    );
	            $result_mail = $this->Email->send_mail(LOCALE, 1, $mail_send_queue, $this->configs);
	            die(json_encode($result));
            }else{
            		$result['message'] = 'Error';
            		die(json_encode($result));
            }
        }
    }
    
    /*
        
    */
    function ajax_product_comment($product_id=0){
        Configure::write('debug',0);
        $this->layout = 'ajax';
        $_GET=$this->clean_xss($_GET);
        $page=1;
        $callback=isset($_GET['callback'])?$_GET['callback']:'';
        $product_id=isset($_GET['product_id'])?$_GET['product_id']:$product_id;
        $limit=isset($_GET['count'])?$_GET['count']:5;
        $start=isset($_GET['start'])?$_GET['start']:1;
        if(isset($_GET['start'])){
            $page=ceil($start/$limit);
        }
        $this->set('callback',$callback);
        $this->set('product_id',$product_id);
        $this->set('page',$page);
        $this->set('limit',$limit);
        $this->set('start',$start);
        
        $conditions="";
        $conditions['Comment.type'] = 'P';
        $conditions['Comment.parent_id'] = 0;
        $conditions['Comment.status'] = 1;
        $conditions['Comment.type_id'] = $product_id;
        $total = $this->Comment->find('count', array('conditions' => $conditions));
        
        //get参数
        $parameters['get'] = array('callback'=>$callback,'product_id'=>$product_id,'start'=>$page,'count'=>$limit);
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'products','action' => 'ajax_product_comment','page' => $page,'limit' => $limit);
        
        $comment_infos=$this->Comment->find('all', array('conditions' => $conditions, 'limit' => $limit, 'page' => $page, 'order' => 'Comment.created desc', 'fields' => 'Comment.id,Comment.type_id,Comment.title,Comment.parent_id,Comment.user_id,Comment.img,Comment.content,Comment.is_public,Comment.status,Comment.user_id,Comment.created'));
        
        $comment_id=array();
        $comment_user_id=array();
        if(!empty($comment_infos)){
            foreach($comment_infos as $k=>$v){
                $comment_id[]=$v['Comment']['id'];
                $comment_user_id[]=$v['Comment']['user_id'];
            }
            $comment_users=$this->User->find('all', array('conditions' => array('User.id' => $comment_user_id), 'fields' => 'User.id,User.name,User.img01'));
            foreach($comment_infos as $k=>$v){
                foreach ($comment_users as $user_k => $user_v) {
                    if ($v['Comment']['user_id'] == $user_v['User']['id']) {
                        $comment_infos[$k]['User'] = $user_v['User'];
                    }
                }
            }
        }
        $this->set('comment_infos',$comment_infos);
        $this->set('comment_total',$total);
        $this->render('ajax_product_comment');
    }
    
    //商品评论的回复
    public function reply_comment()
    {
        //新增评论
        $imgaddr = WWW_ROOT.'img/comment_reply/'.date('Ym').'/';
        if (!empty($_POST['img'])) {
            move_uploaded_file($_POST['img'], $imgaddr.$_POST['img']);
        }
        if ($this->RequestHandler->isPost()) {
            $this->data['Comment']['user_id'] = !empty($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '';//用户id
            $this->data['Comment']['parent_id'] = !empty($_POST['parent_id']) ? $_POST['parent_id'] : 0;
            $this->data['Comment']['content'] = !empty($_POST['content']) ? $_POST['content'] : '';//用户日志
            $this->data['Comment']['created'] = date('Y-m-d H:i:s');//用户创建时间
            $this->data['Comment']['modified'] = date('Y-m-d H:i:s');//用户修改时间
            $this->data['Comment']['img'] = !empty($_POST['img']) ? '/img/comment_reply/'.date('Ym').'/'.$_POST['img'] : '';
            $this->data['Comment']['status'] = 1;//日志默认状态（有效）
            $this->data['Comment']['rank'] = 5;
            $this->data['Comment']['type'] = 'P';
            $this->Comment->save(array('Comment' => $this->data['Comment']));
        }
        if (empty($_POST['img'])) {
            $_FILES = '';
        }
        //查询该日志的评论
        $cond['joins'] = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('Comment.user_id = User.id'),
                         ), );
        $condition = array('Comment.parent_id' => $_POST['parent_id'],'Comment.status' => 1);
        $cond['conditions'] = $condition;
        $cond['order'] = 'modified desc';
        $cond['limit'] = 16;
        $cond['fields'] = array('Comment.*','User.id','User.img01','User.name');
        $reply_list = $this->Comment->find('all', $cond);
        //该商品的评论数量
        $comment_num = $this->Comment->find('count', array('conditions' => array('Comment.parent_id' => $_POST['parent_id'], 'Comment.status' => 1)));
        $this->set('reply_list', $reply_list);
        $this->set('comment_num', $comment_num);
        $this->set('comment_id', $_POST['parent_id']);
        $parent_id = $this->Comment->find('first', array('conditions' => array('Comment.id' => $_POST['parent_id'])));
        $this->set('comment_name', $parent_id['Comment']['name']);
        $this->layout = 'ajax';
        $this->render('product_comment');
    }

    //添加评论(图片，表情)
    public function add_comment()
    {
        Configure::write('debug', 1);
        //图片，表情，分享，可能增加对应处理方法
        if ($this->RequestHandler->isPost()) {
            //是否使用评论验证码
            $comment_captcha = isset($this->configs['comment_captcha']) && $this->configs['comment_captcha'] == '1' ? true : false;
            if ($comment_captcha) {
                if (!isset($this->data['Comment']['authnum']) || isset($this->data['Comment']['authnum']) && $this->captcha->check($this->data['Comment']['authnum']) == false) {
                    echo '<script type="text/javascript">alert("'.$this->ld['incorrect_verification_code'].'");	window.location.href="/products/'.$this->data['Comment']['type_id'].'#maodian"</script>';
                    die();
                }
            }
            if (isset($this->data['Score']) && sizeof($this->data['Score']) > 0) {
                foreach ($this->data['Score'] as $k => $v) {
                    $score_data['type'] = 'P';
                    $score_data['type_id'] = !empty($this->data['Comment']['type_id']) ? $this->data['Comment']['type_id'] : '';
                    $score_data['user_id'] = !empty($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '';
                    $score_data['score_id'] = $k;
                    $score_data['value'] = $v;
                    $score_data['created'] = date('Y-m-d H:i:s');//用户创建时间
                    $score_data['modified'] = date('Y-m-d H:i:s');//用户修改时间
                    $this->ScoreLog->saveAll(array('ScoreLog' => $score_data));
                }
            }
            $status = 0;
            if (isset($this->configs['enable_user_comment_check']) && $this->configs['enable_user_comment_check'] == 0) {
                $status = 1;
            }
            $this->data['Comment']['user_id'] = !empty($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '';//用户id
            $this->data['Comment']['content'] = !empty($this->data['Comment']['content']) ? $this->data['Comment']['content'] : '';//用户日志
            $this->data['Comment']['type_id'] = !empty($this->data['Comment']['type_id']) ? $this->data['Comment']['type_id'] : '';
            $this->data['Comment']['created'] = date('Y-m-d H:i:s');//用户创建时间
            $this->data['Comment']['modified'] = date('Y-m-d H:i:s');//用户修改时间
            $this->data['Comment']['status'] = $status;//评论审核默认状态（有效）
            $this->data['Comment']['rank'] = 5;
            $this->data['Comment']['type'] = 'P';
            $this->data['Comment']['parent_id'] = '0';
            $this->data['Comment']['is_public'] = !empty($this->data['Comment']['is_public']) ? $this->data['Comment']['is_public'] : '0';
            $this->data['Comment']['ipaddr'] = $this->RequestHandler->getClientIP();
            //替换表情
            $oauth_content = $this->data['Comment']['content'];
            $oauth_content = preg_replace("/<img.+?\/>/", '', $oauth_content);
            $oauth_content = strlen($oauth_content) == 0 || $oauth_content == '' ? 'http://www.seevia.cn/' : $oauth_content;
            //图片处理
            if (isset($_FILES['upfile']['tmp_name']) && !empty($_FILES['upfile']['tmp_name'])) {
                pr($_FILES);
                //图片上传处理
                $imgname_arr = explode('.', strtolower($_FILES['upfile']['name']));//获取文件名
                if ($imgname_arr[1] == 'jpg' || $imgname_arr[1] == 'gif' || $imgname_arr[1] == 'png' || $imgname_arr[1] == 'bmp' || $imgname_arr[1] == 'jpeg') {
                    //判断文件格式（限制图片格式）
                    $img_thumb_name = md5($imgname_arr[0].time());
                    $image_name = $img_thumb_name.'.'.$imgname_arr[1];
                    $imgaddr = WWW_ROOT.'img/comment/'.date('Ym').'/';
                    $image_width = 180;
                    $image_height = 180;
                    $img_detail = str_replace($image_name, '', $imgaddr);
                    $this->mkdirs($imgaddr);
                    move_uploaded_file($_FILES['upfile']['tmp_name'], $imgaddr.$image_name);
                    $this->data['Comment']['img'] = '/img/comment/'.date('Ym').'/'.$image_name;
                }
            } else {
                $this->data['Comment']['img'] = '';
            }
            $sina_list = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.User_id' => $_SESSION['User']['User']['id'], 'SynchroUser.type' => 'SinaWeibo', 'SynchroUser.status' => '1')));
            $qq_list = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.User_id' => $_SESSION['User']['User']['id'], 'SynchroUser.type' => 'QQWeibo', 'SynchroUser.status' => '1')));
            if (!empty($sina_list)) {
                if ($this->data['Comment']['img'] == '') {
                    $this->statuses_update($oauth_content, $sina_list);
                } else {
                    $this->statuses_upload($oauth_content, $this->data['Comment']['img'], $sina_list);
                }
            }
            if (!empty($qq_list)) {
                $this->add_weibo_pic($oauth_content, $this->data['Blog']['img'], $qq_list);
            }
            $this->Comment->save(array('Comment' => $this->data['Comment']));
            $comm_id = $this->Comment->id;
            //获取商品信息
            $pro_info = $this->Product->find('first', array('fields' => array('Product.id,ProductI18n.name'), 'conditions' => array('Product.id' => $this->data['Comment']['type_id'], 'ProductI18n.locale' => $this->locale)));
            if (isset($pro_info['Product'])) {
                //动作记录
                $action_data['UserAction']['user_id'] = $this->data['Comment']['user_id'];
                $action_data['UserAction']['type'] = 'comment';
                $action_data['UserAction']['type_id'] = $comm_id;
                $action_data['UserAction']['content'] = $_SESSION['User']['User']['name']."评论了商品 <a href='/products/".$pro_info['Product']['id']."'>".(isset($pro_info['ProductI18n']['name']) ? $pro_info['ProductI18n']['name'] : $type).'</a>'.(($this->data['Comment']['content'] != '') ? ' - '.$this->data['Comment']['content'] : '');
                $action_data['UserAction']['img'] = isset($this->data['Comment']['img']) ? $this->data['Comment']['img'] : '';
                $action_data['UserAction']['created'] = date('Y-m-d H:i:s');//用户创建时间
                $action_data['UserAction']['modified'] = date('Y-m-d H:i:s');//用户修改时间
                $this->UserAction->save($action_data['UserAction']);
            }
            $this->redirect('/products/'.$this->data['Comment']['type_id'].'#maodian');
        }
    }

    //创建路径
    public function mkdirs($path, $mode = 0777)
    {
        $dirs = explode('/', $path);
        $pos = strrpos($path, '.');
        if ($pos === false) {
            $subamount = 0;
        } else {
            $subamount = 1;
        }
        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
            $thispath = '';
            for ($cc = 0; $cc <= $c; ++$cc) {
                $thispath .= $dirs[$cc].'/';
            }
            if (!file_exists($thispath)) {
                mkdir($thispath, $mode);
            }
        }
    }

    //添加评论
    public function ajax_add_comment()
    {
        if ($this->RequestHandler->isPost()) {
        	if(isset($_POST)){
        		$_POST=$this->clean_xss($_POST);
        	}
            $result['message'] = $this->ld['add'].' '.$this->ld['reviews'].' '.$this->ld['failed'];
            $status = 0;
            $no_error = 1;
            if (isset($this->configs['comment_captcha']) && $this->configs['comment_captcha'] == 1 && isset($_POST['captcha']) && $this->captcha->check($_POST['captcha']) == false) {
                $no_error = 0;
                $result['message'] = $this->ld['verify_code'].$this->ld['not_correct'];
            }
            if (isset($this->configs['enable_user_comment_check']) && $this->configs['enable_user_comment_check'] == 0) {
                $status = 1;
            }
            if ($_POST['rank'] == '') {
                $no_error = 0;
                $result['message'] = $this->ld['please_select'].$this->ld['comment_rank'];
            } elseif ($_POST['content'] == '') {
                $no_error = 0;
                $result['message'] = $this->ld['reviews'].' '.$this->ld['can_not_empty'];
            }
            $type = isset($_POST['type']) ? $_POST['type'] : 'P';
            $type_id = isset($_POST['product_id']) ? $_POST['product_id'] : (isset($_POST['article_id']) ? $_POST['article_id'] : '');
            $comment = array(
                'type' => $type,
                'type_id' => $type_id,
                'email' => $_SESSION['User']['User']['email'],
                'status' => $status,//评论是否要审核
                'content' => trim($_POST['content']),
                'user_id' => $_SESSION['User']['User']['id'],
                'name' => $_SESSION['User']['User']['name'],
                'rank' => intval($_POST['rank']),
                'ipaddr' => $this->RequestHandler->getClientIP(),
            );
            $result['product_id'] = isset($_POST['product_id']) ? $_POST['product_id'] : '';
            if ($no_error) {
                $this->Comment->save(array('Comment' => $comment));
                $result['error'] = '0';
                $result['message'] = $this->ld['add'].' '.$this->ld['reviews'].' '.$this->ld['successfully'];
            } else {
                $result['error'] = '1';
            }
        } else {
            $result['error'] = '1';
            $result['message'] = $this->ld['invalid_operation'];
        }
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        die(json_encode($result));
    }

    //缺货登记
    public function add_booking()
    {
        $_SESSION['login_back'] = '/products/'.$_POST['product_id'];
        if ($this->RequestHandler->isPost()) {
            if (isset($_SESSION['User']['User']['id'])) {
                $user = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                $no_error = 1;
                $booking = array('product_id' => $_POST['product_id'],'contact_man' => $user['User']['name'],'email' => $user['User']['email']);
                $booking['user_id'] = $_SESSION['User']['User']['id'];
                $now_time = date('Y-m-d H:i:s');
                $booking['booking_time'] = $now_time;
                //判断是否已经登记过
                $a = $this->BookingProduct->find('all', array('conditions' => array('BookingProduct.user_id' => $_SESSION['User']['User']['id'], 'BookingProduct.product_id' => $_POST['product_id'])));
                if (count($a) > 0) {
                    $no_error = 0;
                }
                if ($no_error) {
                    $this->BookingProduct->save($booking);
                    $result['message'] = $this->ld['booking_successfully'];
                    $result['type'] = 0;
                } else {
                    $result['message'] = $this->ld['have_been_book'];
                    $result['type'] = 2;
                }
            } else {
                $result['type'] = 1;
                $result['message'] = $this->ld['time_out_relogin'];
            }
            if (!isset($_POST['is_ajax'])) {
                $this->page_init();
                $id = isset($booking['product_id']) ? $booking['product_id'] : '';
                $this->pageTitle = isset($result['message']) ? $result['message'] : ''.' - '.$this->configs['shop_title'];
                $this->flash(isset($result['message']) ? $result['message'] : '', '/products/add_booking_page/'.$id, 10);
            }
            $this->set('result', $result);
            $this->layout = 'ajax';
        }
    }

    //推荐好友
    public function recommend_friend()
    {
        if (isset($_POST['is_ajax']) && isset($_POST['id'])) {
            $product = $this->Product->find('first', array('conditions' => array('Product.id' => $_POST['id'])));
            $result['type'] = 1;
            $result['message'] = '';
            $this->set('result', $result);
            $this->set('product', $product);
            $this->layout = 'ajax';
        }
    }

    //发送推荐好友邮件
    public function send_recommend_email()
    {
        if (isset($_POST['email_address'])) {
            $product = $this->Product->find('first', array('conditions' => array('Product.id' => $_POST['product_id'])));
            $shop_name = $this->configs['shop_name'];
            $send_date = date('Y-m-d');
            $this->Email->smtpHostNames = ''.$this->configs['mail-smtp'].'';
            $this->Email->smtpUserName = ''.$this->configs['mail-account'].'';
            $this->Email->smtpPassword = ''.$this->configs['mail-password'].'';
            $this->Email->is_ssl = $this->configs['mail-ssl'];
            $this->Email->is_mail_smtp = $this->configs['mail-service'];
            $this->Email->smtp_port = $this->configs['mail-port'];
            $this->Email->from = ''.$this->configs['mail-account'].'';
                //要发的邮件地址
                $this->Email->to = ''.$_POST['email_address'].'';
            $this->Email->fromName = $shop_name;
            $template = $this->MailTemplate->find("code = 'recommend_email' and status = 1");
            $template_str = $template['MailTemplateI18n']['html_body'];
            if (isset($_SESSION['User']) && isset($_SESSION['User']['User']['name'])) {
                $template_str = str_replace('$user_name', '我是'.$_SESSION['User']['User']['name'].',', $template_str);
            } else {
                $template_str = str_replace('$user_name', '', $template_str);
            }
            $template_str = str_replace('$product_name', $product['ProductI18n']['name'], $template_str);
            $template_str = str_replace('$content', $_POST['email_content'], $template_str);
                //$text_body=str_replace('$product_description',$product['ProductI18n']['description'],$text_body);
                $template_str = str_replace('$purl', $_POST['url'], $template_str);
            $this->Email->html_body = ''.$template_str.'';
            $text_body = $template['MailTemplateI18n']['text_body'];
            if (isset($_SESSION['User']) && isset($_SESSION['User']['User']['name'])) {
                $text_body = str_replace('$user_name', '我是'.$_SESSION['User']['User']['name'].',', $text_body);
            } else {
                $text_body = str_replace('$user_name', '', $text_body);
            }
            $text_body = str_replace('$product_name', $product['ProductI18n']['name'], $text_body);
            $text_body = str_replace('$content', $_POST['email_content'], $text_body);
                //$text_body=str_replace('$product_description',$product['ProductI18n']['description'],$text_body);
                $text_body = str_replace('$url', $_POST['url'], $text_body);
            $this->Email->text_body = $text_body;
            $subject = $template['MailTemplateI18n']['title'];
            $mail_send_queue = array(
                                        'id' => '',
                                        'sender_name' => $shop_name,
                                        'receiver_email' => $_POST['email_address'].';'.$_POST['email_address'],
                                        'cc_email' => ';',
                                        'bcc_email' => ';',
                                        'title' => $subject,
                                        'html_body' => $template_str,
                                        'text_body' => $text_body,
                                        'sendas' => 'text',
                                        'flag' => 0,
                                        'pri' => 0,
                                        );
            @$this->Email->send_mail(LOCALE, $this->configs['mail-encode'], $mail_send_queue);
            $result['type'] = 1;
            $result['message'] = $this->ld['mail_sent_successfully'];
        } else {
            $result['type'] = 0;
            $result['message'] = $this->ld['send_mail_failed'];
        }
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }

    //根据属性id获取属性的价格
    public function select_attr_price()
    {
		if(isset($_POST)){
			$_POST=$this->clean_xss($_POST);
		}
        $result['type'] = 0;
        $result['message'] = 'failed';
        if (isset($_POST['attr_arr']) && !empty($_POST['attr_arr'])) {
            $p_id = $_POST['pro_id'];
            foreach ($_POST['attr_arr'] as $k => $v) {
                $p_id .=  '.'.$v;
            }
            foreach ($_POST['attr_arr'] as $k => $v) {
                $_POST['attr_id'] = $v;
                $pa_info = $this->ProductAttribute->find('first', array('conditions' => array('ProductAttribute.id' => $_POST['attr_id'])));
                if (!empty($pa_info)) {
                    $result['type'] = 1;
                    $result['message'] = 'success';
                    $result['price'][$k] = $pa_info['ProductAttribute']['attribute_price'];
                    if (isset($_SESSION['attr_price'][$_POST['pro_id']][$pa_info['ProductAttribute']['attribute_id']])) {
                        $_SESSION['attr_price'][$_POST['pro_id']]['total'] -= $_SESSION['attr_price'][$_POST['pro_id']][$pa_info['ProductAttribute']['attribute_id']];
                    }
                    if (!isset($_SESSION['attr_price'][$_POST['pro_id']]['total'])) {
                        $_SESSION['attr_price'][$_POST['pro_id']]['total'] = 0;
                    }
                    $att_codes = $this->Attribute->get_product_attribute_codes();
                    $pa_codes = isset($att_codes[$pa_info['ProductAttribute']['attribute_id']]) ? $att_codes[$pa_info['ProductAttribute']['attribute_id']] : $pa_info['ProductAttribute']['attribute_id'];
                    if ($pa_codes == 'EWC') {
                        if (isset($pa_info['ProductAttribute']['attribute_value']) && strtolower($pa_info['ProductAttribute']['attribute_value']) == 'yes' && isset($_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_img']['GB'])) {
                            $_SESSION['pro_attr'][$_POST['pro_id']]['attr_img'][$pa_codes] = isset($_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_img']['GB']) ? $_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_img']['GB'] : '';
                            $_SESSION['pro_attr'][$_POST['pro_id']]['attr_back_img'][$pa_codes] = isset($_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_back_img']['GB']) ? $_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_back_img']['GB'] : '';
                        } else {
                            if (isset($_SESSION['pro_attr'][$_POST['pro_id']]['attr_img']['EWC'])) {
                                unset($_SESSION['pro_attr'][$_POST['pro_id']]['attr_img']['EWC']);
                            }
                            if (isset($_SESSION['pro_attr'][$_POST['pro_id']]['attr_back_img']['EWC'])) {
                                unset($_SESSION['pro_attr'][$_POST['pro_id']]['attr_back_img']['EWC']);
                            }
                        }
                    } elseif ($pa_codes == 'GP' || $pa_codes == 'PG') {
                        $_SESSION['pro_attr'][$_POST['pro_id']]['attr_back_img'][$pa_codes] = $pa_info['ProductAttribute']['attribute_image_path'];
                    } elseif ($pa_codes == 'GB') {
                        $_SESSION['pro_attr'][$_POST['pro_id']]['attr_img'][$pa_codes] = $pa_info['ProductAttribute']['attribute_image_path'];
                        $_SESSION['pro_attr'][$_POST['pro_id']]['attr_back_img'][$pa_codes] = $pa_info['ProductAttribute']['attribute_back_image_path'];
                        $_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_img'][$pa_codes] = $pa_info['ProductAttribute']['attribute_related_image_path'];
                        $_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_back_img'][$pa_codes] = $pa_info['ProductAttribute']['attribute_related_back_image_path'];
                        if (isset($_SESSION['pro_attr'][$_POST['pro_id']]['attr_img']['EWC'])) {
                            $_SESSION['pro_attr'][$_POST['pro_id']]['attr_img']['EWC'] = isset($_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_img']['GB']) ? $_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_img']['GB'] : '';
                            $_SESSION['pro_attr'][$_POST['pro_id']]['attr_back_img']['EWC'] = isset($_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_back_img']['GB']) ? $_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_back_img']['GB'] : '';
                        }
                    } elseif ($pa_codes == 'KG') {
                        //$_SESSION['pro_attr'][$_POST['pro_id']]['attr_img'][$pa_codes]=$pa_info['ProductAttribute']['attribute_image_path'];
                //$_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_img'][$pa_codes]=$pa_info['ProductAttribute']['attribute_related_image_path'];
                if (isset($_SESSION['pro_attr'][$_POST['pro_id']]['upload_path']) && !empty($_SESSION['pro_attr'][$_POST['pro_id']]['upload_path'])) {
                    $_SESSION['pro_attr'][$_POST['pro_id']]['attr_img']['KG'] = $pa_info['ProductAttribute']['attribute_related_image_path'];
                } else {
                    $_SESSION['pro_attr'][$_POST['pro_id']]['attr_img']['KG'] = $pa_info['ProductAttribute']['attribute_image_path'];
                }
                    } elseif ($pa_codes != '') {
                        $_SESSION['pro_attr'][$_POST['pro_id']]['attr_img'][$pa_codes] = $pa_info['ProductAttribute']['attribute_image_path'];
                        $_SESSION['pro_attr'][$_POST['pro_id']]['attr_back_img'][$pa_codes] = $pa_info['ProductAttribute']['attribute_back_image_path'];
                //$_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_img'][$pa_codes]=$pa_info['ProductAttribute']['attribute_related_image_path'];
                //$_SESSION['pro_attr'][$_POST['pro_id']]['attr_related_back_img'][$pa_codes]=$pa_info['ProductAttribute']['attribute_related_back_image_path'];
                    }
                    $_SESSION['attr_price'][$_POST['pro_id']][$pa_info['ProductAttribute']['attribute_id']] = $pa_info['ProductAttribute']['attribute_price'];
                    $_SESSION['attr_price'][$_POST['pro_id']]['total'] += $_SESSION['attr_price'][$_POST['pro_id']][$pa_info['ProductAttribute']['attribute_id']];
                    $result['total'] = $_SESSION['attr_price'][$_POST['pro_id']]['total'];
                }
            }
        }
        $_SESSION['pro_attr']['upload_img_pid'] = $p_id;
        $_SESSION['pro_attr'][$p_id] = $_SESSION['pro_attr'][$_POST['pro_id']];
        $_SESSION['pro_test'] = $_SESSION['pro_attr'][$p_id];
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }

    public function uploadPic()
    {
        $result['code'] = 0;
        $result['msg'] = 'not null';
        $pro_id = $_GET['pro_id'];
        $pta_id = $_GET['pta_id'];
        if ($this->RequestHandler->isPost()) {
            $pa_info = $this->ProductAttribute->find('first', array('conditions' => array('ProductAttribute.locale' => $this->model_locale['product'], 'ProductAttribute.attribute_id' => $pta_id, 'ProductAttribute.product_id' => $pro_id), 'fields' => 'ProductAttribute.attribute_value'));
            $upload = explode(':', $pa_info['ProductAttribute']['attribute_value']);
            $max_size = isset($upload[1]) && !empty($upload[1]) ? $upload[1] : 500;
            $types = explode(',', $upload[0]);
            $imgInfo = $_FILES['upload_img'];
            $info = pathinfo($imgInfo['name']);
            if (!in_array($info['extension'], $types)) {
                $result['msg'] = 'Format Error';
            } elseif ($imgInfo['size'] == 0 || $imgInfo['size'] > $max_size * 1024) {
                $result['msg'] = 'Size Error';
            } else {
                $dir_root = dirname($_SERVER['DOCUMENT_ROOT']).'/data/';
                if (!is_dir($dir_root.'/files/')) {
                    @mkdir($dir_root.'/files/', 0777);
                    @chmod($dir_root.'/files/', 0777);
                }
                $dir_root = dirname($_SERVER['DOCUMENT_ROOT']).'/data/files/';
                if (!is_dir($dir_root.'/upload_logo/')) {
                    @mkdir($dir_root.'/upload_logo/', 0777);
                    @chmod($dir_root.'/upload_logo/', 0777);
                }
                $img_name = date('Ymd').rand().'.'.$info['extension'];
                $img_path = dirname($_SERVER['DOCUMENT_ROOT']).'/data/files/upload_logo/'.$img_name;
                $img_url = 'http://'.$_SERVER['HTTP_HOST'].'/files/upload_logo/'.$img_name;
                if (move_uploaded_file($imgInfo['tmp_name'], $img_path)) {
                    //$pro_id = isset($_SESSION['pro_attr']['upload_img_pid'])?$_SESSION['pro_attr']['upload_img_pid']:$pro_id;
                    $_SESSION['pro_attr'][$pro_id]['upload_path'] = $img_path;
                    $_SESSION['pro_attr'][$pro_id]['upload_img_url'] = $img_url;
                    //$_SESSION['pro_test']=$_SESSION['pro_attr'][$pro_id];
                    $result['code'] = 1;
                    $result['msg'] = 'Success';
                    $result['img_name'] = $img_name;
                }
            }
        }
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }

    public function createPic()
    {
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('ImgSet2');
            $pro_id = isset($_GET['pro_id']) && $_GET['pro_id'] != '' ? $_GET['pro_id'] : '';
            if (isset($pro_id) && !empty($pro_id)) {
                $_SESSION['pro_test'] = $_SESSION['pro_attr'][$pro_id];
            }
            if (isset($_SESSION['pro_test']['attr_img'])) {
                $pagarm_front = $this->ImgSet2->pagram_init($_SESSION['pro_test']['attr_img']);
                $this->creathead();
                header('Content-Type: image/gif');
                die($this->ImgSet2->getImg($pagarm_front, $_SESSION['pro_test']['upload_path'], $_SESSION['pro_test']['params']));
            }

            return false;
        }
    }

    public function createBackPic()
    {
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('ImgSet2');
            if (isset($_SESSION['pro_test']['attr_back_img'])) {
                $pagarm_front = $this->ImgSet2->pagram_init_back($_SESSION['pro_test']['attr_back_img']);
                $this->creathead();
                header('Content-Type: image/gif');
                die($this->ImgSet2->getImg($pagarm_front, '', '', 2));
            }
            die();

            return false;
        }
    }

    public function creathead()
    {
        header('Content-Type:application/x-javascript');
        header('Cache-Control: public');
        header('Pragma: cache');
        $offset = 60 * 60 * 24;  //强制缓一天
        $ExpStr = 'Expires: '.gmdate('D, d M Y H:i:s', time() + $offset).' GMT';
        header($ExpStr);
    }

    public function auto_search($keyword = '')
    {
        Configure::write('debug', 2);
        $result['flag'] = 0;
        if (trim($keyword) != '') {
            $result['flag'] = 1;
            //搜素商品
            $products = $this->Product->auto_search($keyword);
            if (!empty($products)) {
                $result['product'] = $products;
            }
            //搜索分类
            $product_categories = $this->CategoryProduct->auto_search($keyword);
            if (!empty($product_categories)) {
                $result['product_categories'] = $product_categories;
            }
        }
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    public function view_canvas($product_id)
    {
        $this->set('product_id', $product_id);
        $condition = '';
        $condition['Attribute.code'] = array('waikecolor','waikemoban');
        $condition['Attribute.status'] = 1;
        $attribute_type = $this->Attribute->find('list', array('fields' => array('Attribute.code', 'Attribute.id'), 'conditions' => $condition));
        if (!empty($attribute_type)) {
            $product_attribute = $this->ProductAttribute->find('all', array('conditions' => array('ProductAttribute.locale' => $this->model_locale['product'], 'ProductAttribute.attribute_id' => $attribute_type, 'ProductAttribute.product_id' => $product_id), 'order' => 'ProductAttribute.orderby'));
            $product_attribute_color = array();
            $product_attribute_moban = array();
            foreach ($product_attribute as $k => $v) {
                if ($v['ProductAttribute']['attribute_id'] == $attribute_type['waikecolor']) {
                    $product_attribute_color[$k]['ProductAttribute'] = $v['ProductAttribute'];
                }
                if ($v['ProductAttribute']['attribute_id'] == $attribute_type['waikemoban']) {
                    $product_attribute_moban[$k]['ProductAttribute'] = $v['ProductAttribute'];
                }
            }
            $this->set('product_attribute_color', $product_attribute_color);
            $this->set('product_attribute_moban', $product_attribute_moban);
        }
        $this->page_init();
        $this->layout = 'default_full';
    }

    public function film($id = '')
    {
        $this->set('product_id', $id);
        if (isset($_GET['file_url']) && $_GET['file_url'] == 1) {
            $file_url = isset($_SESSION['product'][$id]['file_url']) ? $_SESSION['product'][$id]['file_url'] : '';
            $_SESSION['product'][$id]['file_show_flag'] = 1;
            $this->set('file_url', $file_url);
            $this->set('file_show_flag', $_GET['file_url']);
        } else {
            unset($_SESSION['product']);
        }
        $this->view($id);
        $this->navigations[] = array('url' => 'film');
    }

    public function view_color($product_id)
    {
        $this->set('product_id', $product_id);
        $condition = '';
        $condition['Attribute.code'] = array('pencilcolor');
        $condition['Attribute.status'] = 1;
        $attribute_type = $this->Attribute->find('list', array('fields' => array('Attribute.code', 'Attribute.id'), 'conditions' => $condition));
        if (!empty($attribute_type)) {
            $product_attribute = $this->ProductAttribute->find('all', array('conditions' => array('ProductAttribute.locale' => $this->model_locale['product'], 'ProductAttribute.attribute_id' => $attribute_type, 'ProductAttribute.product_id' => $product_id), 'order' => 'ProductAttribute.orderby'));
            $product_attribute_pencilcolor = array();
            foreach ($product_attribute as $k => $v) {
                if ($v['ProductAttribute']['attribute_id'] == $attribute_type['pencilcolor']) {
                    $product_attribute_pencilcolor[$k]['ProductAttribute'] = $v['ProductAttribute'];
                }
            }
            $this->set('product_attribute_pencilcolor', $product_attribute_pencilcolor);
        }
        $this->page_init();
        $this->layout = 'default_full';
    }

    public function upload_add($product_id)
    {
        if ($this->RequestHandler->isPost()) {
            $product = array();
            if (empty($product_id)) {
                $msg = '请选择商品';
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/products/view_canvas/"</script>';
                die();
            }
            $product['id'] = $product_id;
            $files = $_FILES;
            if (isset($files['img_file_url']['tmp_name']) && !empty($files['img_file_url']['tmp_name'])) {
                $file_name = $files['img_file_url']['name'];
                $file_types = explode('.', $file_name);
                $dian_count = count($file_types) - 1;
                $file_type = isset($file_types[$dian_count]) ? $file_types[$dian_count] : '';
                if ($file_type != 'jpg') {
                    $msg = '请上传jpg格式的图';
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/products/view_canvas/"</script>';
                    die();
                }
                $file_size = $files['img_file_url']['size'];
                if ($file_size > 100 * 1024) {
                    $msg = '文件大小不能大于100kb';
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/products/view_canvas/"</script>';
                    die();
                }
                $dir_root = dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/data';
                if (!is_dir($dir_root.'/files/')) {
                    mkdir($dir_root.'/files/', 0777);
                    @chmod($dir_root.'/files/', 0777);
                }
                move_uploaded_file($files['img_file_url']['tmp_name'], dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/data/files/'.date('Ymd').$file_name);
                @chmod(dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/data/files/'.date('Ymd').$file_name, 0777);
                $file_path = dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/data/files/'.date('Ymd').$file_name;
                $product['file_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/files/'.date('Ymd').$file_name;
                $this->Product->save(array('Product' => $product));
            }
            $this->redirect('/products/'.$product_id);
        }
    }

    public function get_url($product_id)
    {
        $result = array();
        $product = array();
        $product['id'] = $product_id;
        $file_name = rand(0, 100).rand(0, 100).'.png';
        $data = isset($_POST['data']) ? $_POST['data'] : '';
        $result['flag'] = 0;
        $result['msg'] = '下载失败';
        $uri = substr($data, strpos($data, ',') + 1);
        $dir_root = dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/data';
        if (!is_dir($dir_root.'/files/')) {
            mkdir($dir_root.'/files/', 0777);
            @chmod($dir_root.'/files/', 0777);
        }
        @chmod(dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/data/files/'.$file_name, 0777);
        $file_path = dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/data/files/'.$file_name;
        file_put_contents($file_path, base64_decode($uri));
        $product['file_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/files/'.$file_name;
        $this->Product->save(array('Product' => $product));
        $result['flag'] = 1;
        $result['product_id'] = $product_id;
        $result['file_url'] = $product['file_url'];
        unset($_SESSION['product']);
        $_SESSION['product'][$product_id]['file_url'] = $product['file_url'];
        $_SESSION['product'][$product_id]['file_show_flag'] = 1;
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }

    public function getProductPrice($product)
    {
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('FenxiaoDistributor');
            $this->loadModel('FenxiaoDistributorLevel');
            $this->loadModel('FenxiaoProduct');
            $distibutorInfo = $this->FenxiaoDistributor->find('first', array('conditions' => array('FenxiaoDistributor.user_id' => $_SESSION['User']['User']['id'])));
            $productcat_ids = !empty($distibutorInfo) && $distibutorInfo['FenxiaoDistributor']['productcat_ids'] != '' ? $distibutorInfo['FenxiaoDistributor']['productcat_ids'] : 0;
            $conditions = 'FenxiaoProduct.productcat_id in ('.$productcat_ids.')';
            $my_products = $this->FenxiaoProduct->find('list', array('conditions' => $conditions, 'fields' => array('product_id')));
            if (in_array($product['Product']['id'], $my_products)) {
                $level_id = $distibutorInfo['FenxiaoDistributor']['distributor_level_id'];
                $distributor_level_info = $this->FenxiaoDistributorLevel->find('first', array('conditions' => array('id' => $level_id)));
                if (!empty($distributor_level_info)) {
                    $rate = $distributor_level_info['FenxiaoDistributorLevel']['discount'] / 100;

                    return $product['Product']['shop_price'] * $rate;
                }
            }

            return $product['Product']['shop_price'];
        }
    }

    public function share()
    {
        if ($this->RequestHandler->isPost()) {
            $content = ($_POST['pr_content'] != '' ? $_POST['pr_content'].'    ' : '').'商品名称：'.$_POST['pr_name'].';商品价格：'.$_POST['pr_price'].';详情请见：'.($this->server_host.'/products/'.$_POST['pr_id']);
            $pic = $_POST['pr_img'];
            $sina_list = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.User_id' => $_SESSION['User']['User']['id'], 'SynchroUser.type' => 'SinaWeibo', 'SynchroUser.status' => '1')));
            $qq_list = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.User_id' => $_SESSION['User']['User']['id'], 'SynchroUser.type' => 'QQWeibo', 'SynchroUser.status' => '1')));
            if (!empty($sina_list)) {
                $this->statuses_upload($content, $pic, $sina_list);
            }
            if (!empty($qq_list)) {
                $this->add_weibo_pic($content, $pic, $qq_list);
            }
        }
        $this->redirect('/products/'.$_POST['pr_id']);
    }

    /**
     * 发表带图片的微博 sina.
     *
     * @param object $SaeTOAuthV2 SaeTOAuthV2 Object
     * @param string $status      发布内容
     * @param string $pic         发布图片
     * @param array  $sina_list   用户参数
     *
     * @return array 微博接口调用结果
     */
    public function statuses_upload($status = '新内容', $pic = '', $sina_list)
    {
        $SaeTOAuthV2 = $this->saetoauthv2($sina_list);
        $url = 'statuses/upload';
        $parameters = array();
        $parameters['status'] = $status;//要发布的微博文本内容，必须做URLencode，内容不超过140个汉字。
        if ($pic != '') {
            $parameters['pic'] = '@'.$this->server_host.$pic;//要上传的图片，仅支持JPEG、GIF、PNG格式，图片大小小于5M。
        }
        $wb_result = $SaeTOAuthV2->post($url, $parameters, true);
        if (isset($wb_result['id']) && isset($wb_result['user'])) {
            //分享记录
            $this->data['OauthLog']['user_id'] = $_SESSION['User']['User']['id'];
            $this->data['OauthLog']['oauth_type'] = 'sina';
            $this->data['OauthLog']['content'] = $status;
            $this->data['OauthLog']['content'] .= $pic;
            $this->data['OauthLog']['modified'] = date('Y-m-d H:i:s');
            $this->OauthLog->saveAll(array('OauthLog' => $this->data['OauthLog']));
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    /**
     * 发表不带图片的微博 sina.
     *
     * @param object $SaeTOAuthV2 SaeTOAuthV2 Object
     * @param string $status      发布内容
     * @param array  $sina_list   用户参数
     *
     * @return array 微博接口调用结果
     */
    public function statuses_update($status = '新内容', $sina_list)
    {
        $SaeTOAuthV2 = $this->saetoauthv2($sina_list);
        $url = 'statuses/update';
        $parameters = array();
        $parameters['status'] = $status;//要发布的微博文本内容，必须做URLencode，内容不超过140个汉字。
        $wb_result = $SaeTOAuthV2->post($url, $parameters);
        if (isset($wb_result['id']) && isset($wb_result['user'])) {
            //分享记录
            $this->data['OauthLog']['user_id'] = $_SESSION['User']['User']['id'];
            $this->data['OauthLog']['oauth_type'] = 'sina';
            $this->data['OauthLog']['content'] = $status;
            $this->data['OauthLog']['modified'] = date('Y-m-d H:i:s');
            $this->OauthLog->saveAll(array('OauthLog' => $this->data['OauthLog']));
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    /**
     * 发表带图片的微博 qq.
     *
     * @param object $sdk     OpenApiV3 Object
     * @param string $openid  openid
     * @param string $openkey openkey
     * @param string $pf      平台
     *
     * @return array 微博接口调用结果
     */
    public function add_weibo_pic($status = '新内容', $pic = '', $qq_list)
    {
        $_SESSION['t_access_token'] = $qq_list['SynchroUser']['oauth_token'];
        $_SESSION['t_openid'] = $qq_list['SynchroUser']['account'];
        $t_client_id = $this->UserApp->find('first', array('conditions' => array('UserApp.type' => 'QQWeibo')));
        $_SESSION['t_client_id'] = $t_client_id['UserApp']['app_key'];
        $sdk = new Tencent();
        $params = array(
            'content' => $status,
        );
        if ($pic != '') {
            $multi = array('pic' => WWW_ROOT.$pic);
            $r = $sdk->api('t/add_pic', $params, 'POST', $multi);
        } else {
            $r = $sdk->api('t/add', $params, 'POST');
        }

        return $r;
    }

    public function saetoauthv2($sina_list)
    {
        $SaeTOAuthV2 = new SaeTOAuthV2($sina_list['SynchroUser']['account'], '', $sina_list['SynchroUser']['oauth_token']);

        return $SaeTOAuthV2;
    }

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

    //ajax判断购买数量和库存
    public function buy_quantity()
    {
        if ($this->RequestHandler->isPost()) {
        	if(isset($_POST)){
        		$_POST=$this->clean_xss($_POST);
        	}
            $result['message'] = $this->ld['failed'];
            $result['flag'] = '1';
            if (!isset($_POST['quantity'])||$_POST['quantity'] == '') {
                $no_error = 0;
                $result['message'] = $this->ld['please_select'].$this->ld['quantity'];
            }
            $pro_code = isset($_POST['pro_code']) ? $_POST['pro_code'] : '';
            $buy = isset($_POST['quantity']) ? $_POST['quantity'] : '';
            $this->Product->hasOne = array();
            $this->Product->hasMany = array();
            $quantity = $this->Product->find('first', array('conditions' => array('Product.code' => $pro_code), 'fields' => 'Product.quantity'));
            if ($buy > $quantity['Product']['quantity']) {
                $result['flag'] = '1';
            } else {
                $result['flag'] = '0';
            }
        }
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        die(json_encode($result));
    }

    public function updatebalance()
    {
        if ($this->RequestHandler->isPost()) {
        	if(isset($_POST)){
        		$_POST=$this->clean_xss($_POST);
        	}
            $user_info = $this->User->find_user_by_id($_POST['user_id']);
            if (!empty($user_info)) {
                $result['balance'] = $user_info['User']['balance'];
            }
        }
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        die(json_encode($result));
    }

    public function check_sales_attribute($pro_code = '')
    {
    	$pro_code=$this->clean_xss($pro_code);
        if ($this->RequestHandler->isPost()) {
		if(isset($_POST)){
			$_POST=$this->clean_xss($_POST);
		}
            $this->layout = 'ajax';
            Configure::write('debug', 0);
            $result['flag'] = '0';
            $sku_pro_info = $this->Product->find('first', array('conditions' => array('Product.code' => $pro_code)));

            if (empty($sku_pro_info)) {
                $result['data'] = $this->ld['not_yet'].$this->ld['product'];
            } else {
                $result['flag'] = '1';
                $result['data'] = $sku_pro_info;
            }
            $sku_code_list = array();
            $sku_code_list_infos = $this->SkuProduct->find('list', array('conditions' => array('SkuProduct.product_code' => $pro_code), 'fields' => 'SkuProduct.sku_product_code,SkuProduct.price'));
            foreach ($sku_code_list_infos as $k => $v) {
                $sku_code_list[] = $k;
            }
            if (!empty($sku_code_list) && !empty($sku_pro_info)) {
                $sku_id_list = $this->Product->find('list', array('conditions' => array('Product.code' => $sku_code_list), 'fields' => 'Product.id'));
                $attr_cond['ProductAttribute.product_id'] = $sku_id_list;
                $attr_cond['ProductAttribute.locale'] = LOCALE;
if(isset($_REQUEST['attr_id'])){
                	foreach($_REQUEST['attr_id'] as $k=>$v){
                		if(isset($_REQUEST['attr_value'][$k])){
                			$attr_other_cond=array();
                			$attr_other_cond['ProductAttribute.attribute_id']=$v;
                			$attr_other_cond['ProductAttribute.attribute_value']=$_REQUEST['attr_value'][$k];
                			$attr_cond['and']['or'][]=$attr_other_cond;
                		}
                	}
                }
                $attr_info = $this->ProductAttribute->find('all', array('fields' => array('ProductAttribute.product_id','count(*) as `pro_count`'), 'conditions' => $attr_cond,'group'=>'ProductAttribute.product_id','order'=>'pro_count desc'));
                if (!empty($attr_info)) {
                    $attr_pro_info = $this->Product->find('first', array('conditions' => array('Product.id' => $attr_info[0]['ProductAttribute']['product_id'])));
                    if (!empty($attr_pro_info)) {
                        $attr_pro_info['Product']['shop_price'] = $sku_code_list_infos[$attr_pro_info['Product']['code']] == '0.00' ? $attr_pro_info['Product']['shop_price'] : $sku_code_list_infos[$attr_pro_info['Product']['code']];

                        $result['flag'] = '2';
                        $result['data'] = $attr_pro_info;

                        $pro_type_id = !empty($sku_pro_info['Product']['product_type_id']) ? $sku_pro_info['Product']['product_type_id'] : 0;

                        $pro_type_info = $this->ProductType->find('first', array('conditions' => array('ProductType.id' => $pro_type_id)));

                        if (!empty($pro_type_info) && $pro_type_info['ProductType']['customize'] == 1) {
                            $result['is_customize'] = 1;
                        } else {
                            $result['is_customize'] = 0;
                        }
                    }
                }
            }
            die(json_encode($result));
        } else {
            $this->redirect('/');
        }
    }
    public function enquiry_search()
    {
	if(isset($_POST)){
		$_POST=$this->clean_xss($_POST);
	}
        $result['flag'] = 0;
        $keyword = isset($_POST['keyword'])?$_POST['keyword']:'';
        if (trim($keyword) != '') {
            $result['flag'] = 1;
            //按换行切切割字符串
            $code_or_name = explode("\n", $keyword);
            foreach ($code_or_name as $k => $v) {
                $conditions['OR'][]['Product.code like'] = '%'.$v.'%';
                $conditions['OR'][]['ProductI18n.name like'] = '%'.$v.'%';
            }
            //搜素商品
            $products = $this->Product->find('all', array('conditions' => $conditions, 'fields' => 'Product.id,Product.code,ProductI18n.name'));
            if (!empty($products)) {
                $result['product'] = $products;
            }
        }

        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }
    public function product_search_by_id()
    {
    	if(isset($_POST)){
		$_POST=$this->clean_xss($_POST);
	}
        $result['flag'] = 0;
        $keyword = isset($_POST['keyword'])?$_POST['keyword']:'';
        if ($keyword != '') {
            $result['flag'] = 1;
            //搜素商品
            $products = $this->Product->find('all', array('conditions' => array('Product.id' => $keyword), 'fields' => 'Product.id,Product.shop_price,Product.code,Product.img_thumb,ProductI18n.name'));
            //pr($products);
            if (!empty($products)) {
                $result['product'] = $products;
            }
        }

        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }
    //验证码
    public function captcha()
    {
        if ($this->RequestHandler->isPost()) {
            $securimage_code_value = $_SESSION['securimage_code_value'];
            $this->layout = 'ajax';
            Configure::write('debug', 0);
            die(json_encode($securimage_code_value));
        } else {
            $this->layout = 'blank'; //a blank layout
            $this->captcha->show(); //dynamically creates an image
            exit();
        }
    }

    public function set_cart_product_value($pro_id = 0)
    {
        if ($this->RequestHandler->isPost()) {
            $this->loadModel('ProductTypeAttribute');
            $this->loadModel('StyleTypeGroup');
            $this->loadModel('StyleTypeGroupAttributeValue');

            $this->Attribute->set_locale(LOCALE);
            $this->layout = 'ajax';
            Configure::write('debug', 1);
            $cart_pro_code = isset($_POST['pro_code']) ? $_POST['pro_code'] : '';

            $cond['or']['Product.id'] = $pro_id;
            $cond['or']['Product.code'] = $cart_pro_code;
            $pro_infos = $this->Product->find('all', array('conditions' => $cond));

            $sku_pro_info = array();
            $cart_pro_info = array();
            if (!empty($pro_infos)) {
                foreach ($pro_infos as $v) {
                    if ($v['Product']['id'] == $pro_id) {
                        $sku_pro_info = $v;
                    }
                    if ($v['Product']['code'] == $cart_pro_code) {
                        $cart_pro_info = $v;
                    }
                }
            }
            $this->set('cart_pro_code', $cart_pro_code);
            if (!empty($sku_pro_info) && !empty($cart_pro_info)) {
                $pro_type_id = $sku_pro_info['Product']['product_type_id'];
                //$pro_style_id=$sku_pro_info['Product']['product_style_id'];
                $pro_style_id = 0;

                //属性列表
                $attr_ids = $this->ProductTypeAttribute->getattrids($pro_type_id);
                $pro_type_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids, 'Attribute.status' => 1, 'Attribute.type' => 'customize')));

                $customize_attr = array();//定制属性列表
                foreach ($pro_type_attr_info as $v) {
                    $customize_attr[$v['Attribute']['id']]['attr_id'] = $v['Attribute']['id'];
                    $customize_attr[$v['Attribute']['id']]['attr_name'] = $v['AttributeI18n']['name'];
                    $customize_attr[$v['Attribute']['id']]['default_value'] = $v['AttributeI18n']['default_value'];
                    $customize_attr[$v['Attribute']['id']]['is_customize'] = 0;
                    if ($v['Attribute']['attr_type'] == 1 && $v['Attribute']['attr_input_type'] == 1) {
                        $sel_list = array();
                        $img_list = array();
                        $price_list = array();
                        foreach ($v['AttributeOption'] as $vv) {
                            $sel_list[$vv['option_name']] = $vv['option_value'];
                            $price_list[$vv['option_name']] = $vv['price'];
                            if (!empty($vv['attribute_option_image1']) || !empty($v['Attribute']['attribute_img'])) {
                                $img_list[$vv['option_value']] = !empty($vv['attribute_option_image1']) ? $vv['attribute_option_image1'] : $this->configs['products_default_image'];
                            }
                        }
                        $customize_attr[$v['Attribute']['id']]['select_value'] = $sel_list;
                        $customize_attr[$v['Attribute']['id']]['select_img'] = $img_list;
                        $customize_attr[$v['Attribute']['id']]['select_price'] = $price_list;
                    }
                }
                $customize_attr_list = array();
                foreach ($attr_ids as $ak => $av) {
                    foreach ($customize_attr as $ck => $cv) {
                        if ($av == $ck) {
                            $customize_attr_list[$ck] = $cv;
                        }
                    }
                }
                //商品属性
                $pro_attr_list = array();
                if (!empty($cart_pro_info['ProductAttribute'])) {
                    foreach ($cart_pro_info['ProductAttribute'] as $v) {
                        $pro_attr_list[$v['attribute_value']] = $v['attribute_value'];
                    }
                }
                //版型规格信息
                $styletypegrouplist = $this->StyleTypeGroup->getstyletypegrouplist($pro_style_id, $pro_type_id, $pro_attr_list);
                //版型规格尺寸id
                $styletypegroupids = array();
                foreach ($styletypegrouplist as $kk => $vv) {
                    $styletypegroupids[] = $kk;
                }
                $style_type_group_id = isset($styletypegroupids[0]) ? $styletypegroupids[0] : 0;
                //版型规格尺寸信息
                $attrvalueInfo = $this->StyleTypeGroupAttributeValue->getattrvaluelist($pro_style_id, $pro_type_id, $style_type_group_id);
                if (!empty($attrvalueInfo)) {
                    foreach ($attrvalueInfo as $v) {
                        if (isset($customize_attr_list[$v['StyleTypeGroupAttributeValue']['attribute_id']])) {
                            $customize_attr_list[$v['StyleTypeGroupAttributeValue']['attribute_id']]['is_customize'] = 1;
                            if (!empty($v['StyleTypeGroupAttributeValue']['default_value'])) {
                                $customize_attr_list[$v['StyleTypeGroupAttributeValue']['attribute_id']]['default_value'] = $v['StyleTypeGroupAttributeValue']['default_value'];
                            }
                            $customize_attr_list[$v['StyleTypeGroupAttributeValue']['attribute_id']]['select_value'] = split("\r\n", $v['StyleTypeGroupAttributeValue']['select_value']);
                        }
                    }
                }

                $this->set('customize_attr', $customize_attr_list);
            }
        } else {
            $this->redirect('/');
        }
    }
    //到店量体
    public function measure_master()
    {
        if ($this->RequestHandler->isPost()) {
            $this->loadModel('ProductTypeAttribute');
            $this->loadModel('StyleTypeGroup');
            $this->loadModel('StyleTypeGroupAttributeValue');
            $user_name = isset($_SESSION['User']['User']['name']) ? $_SESSION['User']['User']['name'] : '亲';
            if (isset($_SESSION['User']['User']['id'])) {
                $this->loadModel('UserConfig');
                $user_config = $this->UserConfig->get_myconfig($_SESSION['User']['User']['id']);
                if (!empty($user_config)) {
                    $this->set('measure_time', $user_config['UserConfig']['modified']);
                }
                $id = $_SESSION['User']['User']['id'];
                $this->loadModel('UserStyle');
                $this->loadModel('OrderProduct');
                $pro_id = isset($_POST['pro_id']) ? $_POST['pro_id'] : 0;
                if ($pro_id != 0) {
                    $pro_info = $this->Product->find('first', array('conditions' => array('Product.id' => $pro_id)));
                    //$condition["UserStyle.style_id"]=$pro_info['Product']['product_style_id'];
                    $condition['UserStyle.type_id'] = $pro_info['Product']['product_type_id'];
                    if (isset($pro_info['Product']['product_type_id'])) {
                        $this->loadModel('ProductType');
                        $type_name = $this->ProductType->get_type_name($pro_info['Product']['product_type_id']);
                        $this->set('type_name', $type_name);
                        $this->set('type_id', $pro_info['Product']['product_type_id']);
                    }
                }

                $condition['UserStyle.user_id'] = $id;
                //$condition["UserStyle.default_status"]=1;
                $joins = array(
                    array('table' => 'svoms_product_style_i18ns',
                      'alias' => 'ProductStyleI18n',
                      'type' => 'left',
                      'conditions' => array("ProductStyleI18n.style_id = UserStyle.style_id and ProductStyleI18n.locale='".LOCALE."'"),
                     ),
                    array('table' => 'svoms_product_type_i18ns',
                      'alias' => 'ProductTypeI18n',
                      'type' => 'left',
                      'conditions' => array("ProductTypeI18n.type_id = UserStyle.type_id and ProductTypeI18n.locale='".LOCALE."'"),
                     ),
                );

                $fields[] = 'UserStyle.*';
                $fields[] = 'ProductStyleI18n.style_name';
                $fields[] = 'ProductTypeI18n.name';
                $user_style_info = $this->UserStyle->find('all', array('conditions' => $condition, 'fields' => $fields, 'joins' => $joins, 'order' => 'UserStyle.created desc'));

                $userstyle_ids = array();
                foreach ($user_style_info as $v) {
                    $userstyle_ids[] = $v['UserStyle']['id'];
                }

                $order_pro_cond['Order.user_id'] = $id;
                $order_pro_cond['Order.status'] = 1;
                $order_pro_cond['Order.shipping_status'] = 2;
                $order_pro_cond['OrderProduct.user_style_id'] = $userstyle_ids;
                $order_pro_cond['ProductI18n.locale'] = LOCALE;
                $order_pro_info = $this->OrderProduct->find('all', array('conditions' => $order_pro_cond, 'order' => 'Order.created asc'));

                $user_style_ids = array();
                $order_pro_data = array();
                foreach ($order_pro_info as $k => $v) {
                    $user_style_ids[] = $v['OrderProduct']['user_style_id'];
                    $order_pro_data[$v['OrderProduct']['user_style_id']] = $v;
                }

                $product_type_data = array();
                $product_style_data = array();
                $user_style_data = array();
                foreach ($user_style_info as $v) {
                    if (in_array($v['UserStyle']['id'], $user_style_ids)) {
                        $product_type_data[$v['UserStyle']['type_id']] = $v['ProductTypeI18n']['name'];
                        $product_style_data[$v['UserStyle']['style_id']] = $v['ProductStyleI18n']['style_name'];
                        $user_style_data[$v['UserStyle']['type_id']][] = $v;
                    } else {
                        $user_style_data[$v['UserStyle']['type_id']][] = $v;
                    }
                }

            /*$user_style_info=$this->UserStyle->find('first',array("conditions"=>$condition,'fields'=>$fields,'joins'=>$joins,"order"=>"UserStyle.created asc"));
                
                $userstyle_id=$user_style_info['UserStyle']['id'];
                
                $order_pro_cond['Order.user_id']=$id;
                $order_pro_cond['Order.status']=1;
                $order_pro_cond['Order.shipping_status']=2;
                $order_pro_cond['OrderProduct.user_style_id']=$userstyle_id;
                $order_pro_cond['ProductI18n.locale']=LOCALE;
                $order_pro_info=$this->OrderProduct->find('all',array('conditions'=>$order_pro_cond,'order'=>'Order.created asc'));
                
                $user_style_ids=array();
                $order_pro_data=array();
                foreach($order_pro_info as $k=>$v){
                    $user_style_ids[]=$v['OrderProduct']['user_style_id'];
                    $order_pro_data[$v['OrderProduct']['user_style_id']]=$v;
                }
                pr($order_pro_data);
                $product_type_data=array();
                $product_style_data=array();
                $user_style_data=array();

                if($user_style_info['UserStyle']['id']==$userstyle_id){
                    $product_type_data[$user_style_info['UserStyle']['type_id']]=$user_style_info['ProductTypeI18n']['name'];
                    $product_style_data[$user_style_info['UserStyle']['style_id']]=$user_style_info['ProductStyleI18n']['style_name'];
                    $user_style_data[$user_style_info['UserStyle']['type_id']][]=$user_style_info;
                }*/
                if (isset($pro_info['Product']['option_type_id']) && $pro_info['Product']['option_type_id'] == 1) {
                    //分别查询套装子商品
                    $package_pro = $this->PackageProduct->find('all', array('conditions' => array('PackageProduct.product_id' => $pro_id), 'fields' => 'PackageProduct.package_product_id,PackageProduct.package_product_name'));
                    if (count($package_pro) > 0) {
                        //$pro_style_id=array();
                        $pro_type_id = array();
                        foreach ($package_pro as $pk => $pv) {
                            $pkg_pro_info = $this->Product->find('first', array('conditions' => array('Product.id' => $pv['PackageProduct']['package_product_id'])));
                            //array_push($pro_style_id,$pkg_pro_info['Product']['product_style_id']);
                            array_push($pro_type_id, $pkg_pro_info['Product']['product_type_id']);
                        }
                        //$condition["UserStyle.style_id"]=$pro_style_id;
                        $condition['UserStyle.type_id'] = $pro_type_id;
                    }
                    $user_style_info = $this->UserStyle->find('all', array('conditions' => $condition, 'fields' => $fields, 'joins' => $joins, 'order' => 'UserStyle.created asc'));

                    $userstyle_ids = array();
                    $check_style_id = array();
                    $check_type_id = array();

                    foreach ($user_style_info as $k => $v) {
                        $userstyle_ids[] = $v['UserStyle']['id'];
                    }

                    $order_pro_cond['Order.user_id'] = $id;
                    $order_pro_cond['Order.status'] = 1;
                    $order_pro_cond['Order.shipping_status'] = 2;
                    $order_pro_cond['OrderProduct.user_style_id'] = $userstyle_ids;
                    $order_pro_cond['ProductI18n.locale'] = LOCALE;
                    $order_pro_info = $this->OrderProduct->find('all', array('conditions' => $order_pro_cond, 'order' => 'Order.created asc'));
                    $user_style_ids = array();
                    $order_pro_data = array();
                    foreach ($order_pro_info as $k => $v) {
                        $user_style_ids[] = $v['OrderProduct']['user_style_id'];
                        $order_pro_data[$v['OrderProduct']['user_style_id']] = $v;
                    }
                    $product_type_data = array();
                    $product_style_data = array();
                    $user_style_data = array();
                    foreach ($user_style_info as $k => $v) {
                        if (!empty($check_style_id) && in_array($v['UserStyle']['style_id'], $check_style_id) && !empty($check_type_id) && in_array($v['UserStyle']['type_id'], $check_type_id)) {
                            //unset($user_style_info[$k]);
                        } else {
                            array_push($check_style_id, $v['UserStyle']['style_id']);
                            array_push($check_type_id, $v['UserStyle']['type_id']);
                        }
                    }
                    //pr($user_style_info);
                    foreach ($user_style_info as $v) {
                        if (in_array($v['UserStyle']['id'], $user_style_ids)) {
                            $product_type_data[$v['UserStyle']['type_id']] = $v['ProductTypeI18n']['name'];
                            $product_style_data[$v['UserStyle']['style_id']] = $v['ProductStyleI18n']['style_name'];
                            $user_style_data[$v['UserStyle']['type_id']][] = $v;
                        } else {
                            $user_style_data[$v['UserStyle']['type_id']][] = $v;
                        }
                    }
                    //pr($user_style_data);
                    if (count($pro_type_id) >= 1) {
                        $this->loadModel('ProductType');
                        $type_name_arr = array();
                        foreach ($pro_type_id as $pv) {
                            $type_name = $this->ProductType->get_type_name($pv);
                            $type_name_arr[$pv] = $type_name;
                        }
                          //pr($type_name_arr);
                    }
                    $this->set('type_name_arr', $type_name_arr);
                    $this->set('option_type_id', $pro_info['Product']['option_type_id']);
                    $this->set('user_style_ids', $user_style_ids);
                }
                if (isset($_POST['show_type'])) {
                    $this->set('show_type', $_POST['show_type']);
                }
                if (isset($_POST['basic_price'])) {
                    $this->set('basic_price', $_POST['basic_price']);
                }
                if (isset($_POST['accessory_price'])) {
                    $this->set('accessory_price', $_POST['accessory_price']);
                }
                if (isset($_POST['total_prices'])) {
                    $this->set('total_prices', $_POST['total_prices']);
                }
                //pr($user_style_data);
                $this->set('user_style_list', $user_style_data);
                $this->set('order_pro_data', $order_pro_data);
                //$this->set('userstyle_id',$userstyle_id);
                //pr($order_pro_data);
                $this->set('product_type_data', $product_type_data);
                $this->set('product_style_data', $product_style_data);
            }
            $this->Attribute->set_locale(LOCALE);
            $this->layout = 'ajax';
            Configure::write('debug', 1);
//			$cart_pro_code=isset($_POST['pro_code'])?$_POST['pro_code']:'';
//			
//			$cond['or']['Product.id']=$pro_id;
//			$cond['or']['Product.code']=$cart_pro_code;
//			$pro_infos=$this->Product->find("all",array("conditions"=>$cond));
//			
//			$sku_pro_info=array();
//			$cart_pro_info=array();
//			if(!empty($pro_infos)){
//				foreach($pro_infos as $v){
//					if($v['Product']['id']==$pro_id){
//						$sku_pro_info=$v;
//					}
//					if($v['Product']['code']==$cart_pro_code){
//						$cart_pro_info=$v;
//					}
//				}
//			}
//			$this->set('cart_pro_code',$cart_pro_code);
            $this->set('user_name', $user_name);
        } else {
            $this->redirect('/');
        }
    }
}
