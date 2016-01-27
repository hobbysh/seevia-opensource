<?php

uses('sanitize');
/**
 *这是一个名为 PagesController 的页面控制器.
 *
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 */
class PagesController extends AppController
{
    public $name = 'Pages';
    public $helpers = array('Html','Flash','Cache');
    public $uses = array('Flash','FlashImage','Article','Product','CategoryProduct','ProductsCategory','UserApp','Page','PageI18n','TopicProduct');
    public $components = array('RequestHandler','Cookie','Session','Captcha','Email');
    /**
     *主页.
     */
    public function home()
    {
        $this->page_init();
        $this->pageTitle = $this->configs['shop_title'];

        //取文章重要性在（2:滚动显示;3:置顶且滚动显示）
        //$this->set('home_articles',$this->Article->findscroll(LOCALE));
        $page_flash = $this->Flash->find('first', array('conditions' => array('Flash.type' => 'H'), 'fields' => array('Flash.width', 'Flash.height')));
        if (!empty($page_flash) && !empty($page_flash['FlashImage'])) {
            $this->set('flashes', $page_flash);
            $flash_image = $this->FlashImage->get_module_infos($page_flash);
            $this->set('flash_image', $flash_image);
        }//flash轮播
        //flash
        //$this->Flash->set_locale($this->locale);
        $cache_key = md5('find_page_flash'.'_'.$this->locale);
//		$condition = "";
//		$condition["modified <"] = date("Y-m-d")." 23:23:59";
//		$condition["modified >"] = date("Y-m-d")." 00:00:01";
//		$user_log_data = $this->User->find("all",array("conditions"=>$condition,"limit"=>"5"));

    /*	$this->set("new_o",$this->Order->find("all",array('cache'=>$this->short,"limit"=>"5",'fields'=>array('Order.city','Order.shipping_name'),'order'=>array('Order.id desc'))));

        $flash_condition = array();
        $flash_condition[] = "H";
        $flash_condition[] = "customize";
        $page_flash_data = $this->Flash->find("all",array('cache'=>$this->short,"conditions"=>array("Flash.type"=>$flash_condition),'fields'=>array('Flash.width','Flash.height','Flash.type')));
        foreach( $page_flash_data as $k=>$v ) {
            $page_flash2[$v["Flash"]["type"]] = $v;
        }
        $page_flash2 = cache::read($cache_key);
        if(!$page_flash2&&false) {
            $flash_condition = array();
            $flash_condition[] = "H";
            $flash_condition[] = "customize";

            $page_flash_data = $this->Flash->find("all",array('cache'=>$this->short,"conditions"=>array("Flash.type"=>$flash_condition),'fields'=>array('Flash.width','Flash.height','Flash.type')));
            foreach( $page_flash_data as $k=>$v ) {
                $page_flash2[$v["Flash"]["type"]] = $v;
            }
            cache::write($cache_key,$page_flash2);
        }
        $this->set('flashes2',$page_flash2); //flash轮播*/
        //首页文章
        $home_articles = $this->Article->find_second_home_article(6);//Module
        if (!empty($home_articles)) {
            foreach ($home_articles as $k => $v) {
                if (isset($this->configs['article_title_length']) && $this->configs['article_title_length'] > 0) {
                    $home_articles[$k]['ArticleI18n']['sub_title'] = $this->Article->sub_str($v['ArticleI18n']['title'], $this->configs['article_title_length']);
                }
            }
        }
        $this->set('home_articles', $home_articles);
        //在线调研
        $now = date('Y-m-d H:i:s');
        $votes = ClassRegistry::init('Vote')->find('first', array('conditions' => array('Vote.start_time <=' => $now, 'Vote.end_time >=' => $now, 'Vote.status' => 1), 'order' => 'Vote.modified desc'));
        //pr($votes);exit;
        if (!empty($votes)) {
            ClassRegistry::init('Vote')->set_locale($this->locale);
            ClassRegistry::init('VoteOption')->set_locale($this->locale);
            $votes['VoteOptions'] = ClassRegistry::init('VoteOption')->find('all', array('conditions' => array('VoteOption.vote_id' => $votes['Vote']['id'], 'VoteOption.status' => 1)));
            $this->set('votes', $votes);
            //pr($votes);die;
        }
        // 同步处理
        $syns = $this->UserApp->find('list', array('conditions' => array('UserApp.status' => 1), 'fields' => array('UserApp.type')));
        $this->set('syns', $syns);
        //首页推荐，促销，最新商品
        $sale = false;
        $price = false;
        $sale = true;
        if (!(!isset($this->configs['show_product_price']) || (isset($this->configs['show_product_price']) && $this->configs['show_product_price'] == 1))) {
            $price = true;
        }

        //按顶级分类推荐，促销，最新商品   改为所有分类 chenfan
//		if($_SERVER['HTTP_HOST'] == 'www.superelectricscience.com')
//			$this->Product->products_tab(array('set'=>'home_products_tab'),$sale,$price,'r');
//		else
            $this->Product->products_tab(array('set' => 'home_products_tab'), $sale, $price);

        if (isset($this->CategoryProduct->allinfo['P']['all_catids']) && sizeof($this->CategoryProduct->allinfo['P']['all_catids']) > 0) {
            $this->Product->viewVars[3]['home_category_products'] = array();
            //take all home_show cat_list from mysql to array,then get value from the array
            $cat_home = $this->CategoryProduct->find('list', array('conditions' => array('CategoryProduct.Home_show' => 1), 'fields' => array('CategoryProduct.id')));
            $p_cat = $this->ProductsCategory->find('list', array('conditions' => array('ProductsCategory.category_id' => $cat_home), 'fields' => array('category_id', 'product_id')));
            foreach ($this->CategoryProduct->allinfo['P']['home_all_ids'] as $k => $v) {
                //查看是否是首页显示的分类
                if (in_array($v, $cat_home) && isset($this->CategoryProduct->allinfo['P']['subids'][$v])) {
                    //old//$expand_pids=$this->ProductsCategory->find('list',array("conditions"=>array("category_id"=> $this->CategoryProduct->allinfo['P']['subids'][$v]),"fields"=>array("category_id","product_id")));
                    $expand_pids = array();
                    foreach ($this->CategoryProduct->allinfo['P']['subids'][$v] as $kpc => $vpc) {
                        if (isset($p_cat[$vpc])) {
                            $expand_pids[] = $p_cat[$vpc];
                        }
                    }
                    $all_pro_infos = $this->Product->products_tab(array('category_id' => $this->CategoryProduct->allinfo['P']['subids'][$v], 'expand_pids' => $expand_pids), $sale, $price);
                    foreach ($all_pro_infos as $kk => $vv) {
                        foreach ($vv as $kkk => $vvv) {
                            $all_pro_infos[$kk][$kkk]['Product']['category_id'] = $v;
                        }
                    }
                    //$this->Product->viewVars[3]['home_category_products'][$v] = $this->Product->products_tab(array('category_id'=>$this->CategoryProduct->allinfo['P']['subids'][$v],'expand_pids'=>$expand_pids));
                    $this->Product->viewVars[3]['home_category_products'][$v] = $all_pro_infos;
                }
            }
        }
        //获取首页显示的分类的首页显示商品的数量
        $catNum = $this->CategoryProduct->homeNum();
        $this->set('catNum', $catNum);
        //获取首页显示的分类的首页显示商品的过滤关键字
        $catKeys = $this->CategoryProduct->homeShowkeywords($this->locale);
        $this->set('catKeys', $catKeys);
        //获取首页显示的分类的首页显示商品的排序方式
        $catOrders = $this->CategoryProduct->homeShoworders();
        $this->set('catOrders', $catOrders);
        //获取首页显示的分类的首页显示商品的排序方式
        $catImgs = $this->CategoryProduct->homeCatimgs();
        $this->set('catImgs', $catImgs);
        //获取所有商品的正反两张图
//		$allImgs=$this->ProductGallery->getAll();
//		$this->set('allImgs',$allImgs);
        //获取所有分类的描述
        $this->CategoryProduct->set_locale($this->locale);
        $allCatDesc = $this->CategoryProduct->getAllDesc();
//		所有批发价
//		$all_volumes=$this->ProductVolume->getAll();
//		$this->set('all_volumes',$all_volumes);

        $this->set('allCatDesc', $allCatDesc);
        //销售排行查询
        $toplist = $this->Product->sale_rank();

        $this->set('toplist', $toplist);
        //首页显示专题信息
        $conditions = '';
        $conditions = array('Topic.start_time <=' => DateTime,'Topic.end_time >=' => DateTime);
        $conditions['Topic.status'] = 1;
        $conditions['Topic.front'] = 1;
        $topics = $this->Topic->find('all', array('conditions' => $conditions, 'order' => 'Topic.created DESC'));

        if (isset($topics) && !empty($topics)) {
            //			$topic_products = $this->find('list', array('fields' => array('TopicProduct.topic_id','TopicProduct.product_id')));
            $topic_products = $this->TopicProduct->find('all', array('fields' => array('TopicProduct.topic_id', 'TopicProduct.product_id')));
            $topic_products2 = array();
            foreach ($topic_products as $tpk => $tpv) {
                $topic_products2[$tpv['TopicProduct']['topic_id']][] = $tpv['TopicProduct']['product_id'];
            }
            unset($topic_products);

            foreach ($topics as $k => $v) {
                if (isset($topic_products2[$v['Topic']['id']])) {
                    $t_ids = $topic_products2[$v['Topic']['id']];
                }
                if (isset($t_ids) && !empty($t_ids)) {
                    if (isset($v['Topic']['front_num']) && $v['Topic']['front_num'] > 0) {
                        $limit = $v['Topic']['front_num'];
                    } else {
                        $limit = '';
                    }
                    $t_products = $this->Product->find('all', array('conditions' => array('Product.id' => $t_ids), 'order' => $v['Topic']['orderby'], 'limit' => $limit, 'fields' => 'Product.id,Product.shop_price,Product.img_thumb,Product.img_detail,ProductI18n.name'));

                    if (isset($t_products) && !empty($t_products)) {
                        foreach ($t_products as $kk => $vv) {
                            $t_products[$kk]['ProductI18n']['sub_name'] = $this->Product->sub_str($vv['ProductI18n']['name'], 20);
                        }
                        $topics[$k]['Product'] = $t_products;
                    }
                }
            }
            $this->set('topics', $topics);
        }
    }
    
    function view($id = 0){
    		 $this->layout = 'default';
	        if (!is_numeric($id) || $id < 1) {
	            $this->pageTitle = $this->ld['invalid_id'].' - '.$this->configs['shop_title'];
	            $this->flash($this->ld['invalid_id'], '/', 5);
	            return;
	        }
	        $conditions = array('Page.id' => $id,'Page.status' => '1');
	        $this->Page->set_locale($this->locale);
	        $page = $this->Page->find('first', array('conditions' => $conditions));
	        if (empty($page)) {
	            $this->pageTitle = $this->ld['page'].' - '.$this->configs['shop_title'];
	            $this->flash($this->ld['page'].$this->ld['not_exist'], '/', 5);
	            return;
	        } elseif (!empty($topic)) {
	            $this->pageTitle = $page['PageI18n']['title'].' - '.$this->configs['shop_title'];
	        }
	        $this->set('page', $page);
	        $this->set('meta_description', $page['PageI18n']['meta_description'].' '.$this->configs['seo-des']);
	        $this->set('meta_keywords', $page['PageI18n']['meta_keywords'].' '.$this->configs['seo-key']);
	        $this->ur_heres[] = array('name' => $page['PageI18n']['title'],'url' => '');
	        $this->pageTitle = $page['PageI18n']['title'].' - '.$this->configs['shop_title'];
    }

    /**
     *关闭.
     */
    public function closed()
    {
        if ($this->configs['shop_temporal_closed'] == 1) {
            //用户关店
            $this->page_init();
            $this->set('shop_logo', $this->configs['shop_logo']);
            $this->pageTitle = $this->ld['shop_closed'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['shop_closed'].'  '.$this->configs['closed_reason'], '/', 999999999999999);
        } elseif ($this->configs['shop_temporal_closed'] == 2) {
            //系统关店
            $this->layout = '';
            $this->set('closed_reason', $this->configs['closed_reason']);
        }
    }

    /**
     *关闭.
     */
    public function homepage()
    {
        //取当前模板
        $this->page_init();
        $this->pageTitle = $this->configs['shop_title'];
        $this->layout = 'default_page';
    }

    /**
     *分栏1.
     */
    public function homeone()
    {
        $this->page_init();
        $conditions = '';
        $conditions = array('Topic.start_time <=' => DateTime,'Topic.end_time >=' => DateTime);
        $conditions['Topic.status'] = 1;
        $conditions['Topic.front'] = 1;
        $page_flash = $this->Flash->find('first', array('cache' => $this->short, 'conditions' => array('Flash.type' => 'H'), 'fields' => array('Flash.width', 'Flash.height')));
        if (!empty($page_flash) && !empty($page_flash['FlashImage'])) {
            $this->set('flashes', $page_flash);
        }
        $cache_key = md5('find_page_flash'.'_'.$this->locale);
        $topics = $this->Topic->find('all', array('conditions' => $conditions, 'order' => 'Topic.created DESC'));
        if (isset($topics) && !empty($topics)) {
            foreach ($topics as $k => $v) {
                $t_ids = $this->TopicProduct->find_topic_product_ids($v['Topic']['id']);
                if (isset($t_ids) && !empty($t_ids)) {
                    if (isset($v['Topic']['front_num']) && $v['Topic']['front_num'] > 0) {
                        $limit = $v['Topic']['front_num'];
                    } else {
                        $limit = '';
                    }
                    $t_products = $this->Product->find('all', array('conditions' => array('Product.id' => $t_ids), 'order' => $v['Topic']['orderby'], 'limit' => $limit, 'fields' => 'Product.id,Product.img_thumb,Product.img_detail,ProductI18n.name'));
                    if (isset($t_products) && !empty($t_products)) {
                        foreach ($t_products as $kk => $vv) {
                            $t_products[$kk]['ProductI18n']['sub_name'] = $this->Product->sub_str($vv['ProductI18n']['name'], 20);
                        }
                        $topics[$k]['Product'] = $t_products;
                    }
                }
            }
            $this->set('topics', $topics);
        }
        $this->pageTitle = $this->configs['shop_title'];
        $this->layout = 'default_page';
        $news_info = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.code' => 'NEWS')));
        $this->set('cat_id', $news_info['CategoryArticle']['id']);
        $article_infos = $this->Article->find('all', array('conditions' => array('Article.category_id' => $news_info['CategoryArticle']['id'], 'Article.status' => 1, 'Article.front' => 1), 'limit' => 20, 'order' => 'Article.orderby asc'));
        if (!empty($article_infos)) {
            $this->set('article_infos', $article_infos);
        }
        //获取杂志下的第一篇文章
        $mg_info = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.code' => 'MAGA'), 'fields' => 'CategoryArticle.id'));
        if (!empty($mg_info)) {
            $mg_art_info = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.category_id' => $mg_info['CategoryArticle']['id'])));
            $this->set('mg_art_info', $mg_art_info);
        }
        //首页文章
        $home_articles = $this->Article->find_second_home_article(6);//Module
        if (!empty($home_articles)) {
            foreach ($home_articles as $k => $v) {
                if (isset($this->configs['article_title_length']) && $this->configs['article_title_length'] > 0) {
                    $home_articles[$k]['ArticleI18n']['sub_title'] = $this->Article->sub_str($v['ArticleI18n']['title'], $this->configs['article_title_length']);
                }
            }
        }
        $this->set('home_articles', $home_articles);
    }

    /**
     *分栏2（左右）.
     */
    public function hometwo()
    {
        $this->page_init();
        $conditions = '';
        $conditions = array('Topic.start_time <=' => DateTime,'Topic.end_time >=' => DateTime);
        $conditions['Topic.status'] = 1;
        $conditions['Topic.front'] = 1;
        $topics = $this->Topic->find('all', array('conditions' => $conditions, 'order' => 'Topic.created DESC'));
        if (isset($topics) && !empty($topics)) {
            foreach ($topics as $k => $v) {
                $t_ids = $this->TopicProduct->find_topic_product_ids($v['Topic']['id']);
                if (isset($t_ids) && !empty($t_ids)) {
                    if (isset($v['Topic']['front_num']) && $v['Topic']['front_num'] > 0) {
                        $limit = $v['Topic']['front_num'];
                    } else {
                        $limit = '';
                    }
                    $t_products = $this->Product->find('all', array('conditions' => array('Product.id' => $t_ids), 'order' => $v['Topic']['orderby'], 'limit' => $limit, 'fields' => 'Product.id,Product.img_thumb,Product.img_detail,ProductI18n.name'));
                    if (isset($t_products) && !empty($t_products)) {
                        foreach ($t_products as $kk => $vv) {
                            $t_products[$kk]['ProductI18n']['sub_name'] = $this->Product->sub_str($vv['ProductI18n']['name'], 20);
                        }
                        $topics[$k]['Product'] = $t_products;
                    }
                }
            }
            $this->set('topics', $topics);
        }
        $this->pageTitle = $this->configs['shop_title'];
        $this->layout = 'default';
    }

    /**
     *分栏3（左中右）.
     */
    public function homethree()
    {
        $this->page_init();
        $this->pageTitle = $this->configs['shop_title'];
        //取文章重要性在（2:滚动显示;3:置顶且滚动显示）
        //$this->set('home_articles',$this->Article->findscroll(LOCALE));
        $page_flash = $this->Flash->find('first', array('cache' => $this->short, 'conditions' => array('Flash.type' => 'H'), 'fields' => array('Flash.width', 'Flash.height')));
        if (!empty($page_flash) && !empty($page_flash['FlashImage'])) {
            $this->set('flashes', $page_flash);
        }//flash轮播
        //flash
        //$this->Flash->set_locale($this->locale);
        //pr(Locale);
        $cache_key = md5('find_page_flash'.'_'.$this->locale);
//		$condition = "";
//		$condition["modified <"] = date("Y-m-d")." 23:23:59";
//		$condition["modified >"] = date("Y-m-d")." 00:00:01";
//		$user_log_data = $this->User->find("all",array("conditions"=>$condition,"limit"=>"5"));

    /*	$this->set("new_o",$this->Order->find("all",array('cache'=>$this->short,"limit"=>"5",'fields'=>array('Order.city','Order.shipping_name'),'order'=>array('Order.id desc'))));

        $flash_condition = array();
        $flash_condition[] = "H";
        $flash_condition[] = "customize";
        $page_flash_data = $this->Flash->find("all",array('cache'=>$this->short,"conditions"=>array("Flash.type"=>$flash_condition),'fields'=>array('Flash.width','Flash.height','Flash.type')));
        foreach( $page_flash_data as $k=>$v ) {
            $page_flash2[$v["Flash"]["type"]] = $v;
        }
        $page_flash2 = cache::read($cache_key);
        if(!$page_flash2&&false) {
            $flash_condition = array();
            $flash_condition[] = "H";
            $flash_condition[] = "customize";

            $page_flash_data = $this->Flash->find("all",array('cache'=>$this->short,"conditions"=>array("Flash.type"=>$flash_condition),'fields'=>array('Flash.width','Flash.height','Flash.type')));
            foreach( $page_flash_data as $k=>$v ) {
                $page_flash2[$v["Flash"]["type"]] = $v;
            }
            cache::write($cache_key,$page_flash2);
        }
        $this->set('flashes2',$page_flash2); //flash轮播*/
        //首页文章
        $home_articles = $this->Article->find_second_home_article(6);//Module
        if (!empty($home_articles)) {
            foreach ($home_articles as $k => $v) {
                if (isset($this->configs['article_title_length']) && $this->configs['article_title_length'] > 0) {
                    $home_articles[$k]['ArticleI18n']['sub_title'] = $this->Article->sub_str($v['ArticleI18n']['title'], $this->configs['article_title_length']);
                }
            }
        }
        $conditions = '';
        $conditions = array('Topic.start_time <=' => DateTime,'Topic.end_time >=' => DateTime);
        $conditions['Topic.status'] = 1;
        $conditions['Topic.front'] = 1;
        $topics = $this->Topic->find('all', array('conditions' => $conditions, 'order' => 'Topic.created DESC'));
        if (isset($topics) && !empty($topics)) {
            foreach ($topics as $k => $v) {
                $t_ids = $this->TopicProduct->find_topic_product_ids($v['Topic']['id']);
                if (isset($t_ids) && !empty($t_ids)) {
                    if (isset($v['Topic']['front_num']) && $v['Topic']['front_num'] > 0) {
                        $limit = $v['Topic']['front_num'];
                    } else {
                        $limit = '';
                    }
                    $t_products = $this->Product->find('all', array('conditions' => array('Product.id' => $t_ids), 'order' => $v['Topic']['orderby'], 'limit' => $limit, 'fields' => 'Product.id,Product.img_thumb,Product.img_detail,ProductI18n.name'));
                    if (isset($t_products) && !empty($t_products)) {
                        foreach ($t_products as $kk => $vv) {
                            $t_products[$kk]['ProductI18n']['sub_name'] = $this->Product->sub_str($vv['ProductI18n']['name'], 20);
                        }
                        $topics[$k]['Product'] = $t_products;
                    }
                }
            }
            $this->set('topics', $topics);
        }
        $this->set('home_articles', $home_articles);
        $this->layout = 'default';
    }

    /**
     *专题购物首页.
     */
    public function hometopics()
    {
        $this->page_init();
        $this->pageTitle = $this->configs['shop_title'];
        //取文章重要性在（2:滚动显示;3:置顶且滚动显示）
        //$this->set('home_articles',$this->Article->findscroll(LOCALE));
        $page_flash = $this->Flash->find('first', array('cache' => $this->short, 'conditions' => array('Flash.type' => 'H'), 'fields' => array('Flash.width', 'Flash.height')));
        if (!empty($page_flash) && !empty($page_flash['FlashImage'])) {
            $this->set('flashes', $page_flash);
        }//flash轮播
        //flash
        //$this->Flash->set_locale($this->locale);
        $cache_key = md5('find_page_flash'.'_'.$this->locale);
//		$condition = "";
//		$condition["modified <"] = date("Y-m-d")." 23:23:59";
//		$condition["modified >"] = date("Y-m-d")." 00:00:01";
//		$user_log_data = $this->User->find("all",array("conditions"=>$condition,"limit"=>"5"));

    /*	$this->set("new_o",$this->Order->find("all",array('cache'=>$this->short,"limit"=>"5",'fields'=>array('Order.city','Order.shipping_name'),'order'=>array('Order.id desc'))));

        $flash_condition = array();
        $flash_condition[] = "H";
        $flash_condition[] = "customize";
        $page_flash_data = $this->Flash->find("all",array('cache'=>$this->short,"conditions"=>array("Flash.type"=>$flash_condition),'fields'=>array('Flash.width','Flash.height','Flash.type')));
        foreach( $page_flash_data as $k=>$v ) {
            $page_flash2[$v["Flash"]["type"]] = $v;
        }
        $page_flash2 = cache::read($cache_key);
        if(!$page_flash2&&false) {
            $flash_condition = array();
            $flash_condition[] = "H";
            $flash_condition[] = "customize";

            $page_flash_data = $this->Flash->find("all",array('cache'=>$this->short,"conditions"=>array("Flash.type"=>$flash_condition),'fields'=>array('Flash.width','Flash.height','Flash.type')));
            foreach( $page_flash_data as $k=>$v ) {
                $page_flash2[$v["Flash"]["type"]] = $v;
            }
            cache::write($cache_key,$page_flash2);
        }
        $this->set('flashes2',$page_flash2); //flash轮播*/
        //首页文章
        $home_articles = $this->Article->find_second_home_article(6);//Module
        if (!empty($home_articles)) {
            foreach ($home_articles as $k => $v) {
                if (isset($this->configs['article_title_length']) && $this->configs['article_title_length'] > 0) {
                    $home_articles[$k]['ArticleI18n']['sub_title'] = $this->Article->sub_str($v['ArticleI18n']['title'], $this->configs['article_title_length']);
                }
            }
        }
        $this->set('home_articles', $home_articles);
        $this->set('home_show', $this->CategoryArticle->find('all', array('conditions' => array('CategoryArticle.home_show' => 1))));
        //首页推荐，促销，最新商品
        $this->Product->products_tab(array('set' => 'home_products_tab'));
        //按顶级分类推荐，促销，最新商品
        if (isset($this->CategoryProduct->allinfo['P']['direct_subids'][0]) && sizeof($this->CategoryProduct->allinfo['P']['direct_subids'][0]) > 0) {
            $this->Product->viewVars[3]['home_category_products'] = array();
            foreach ($this->CategoryProduct->allinfo['P']['direct_subids'][0] as $k => $v) {
                $this->Product->viewVars[3]['home_category_products'][$v] = $this->Product->products_tab(array('category_id' => $this->CategoryProduct->allinfo['P']['subids'][$v]));
            }
        }
        //销售排行查询
        $toplist = $this->Product->sale_rank();
        $this->set('toplist', $toplist);
        $this->page_init();
        $this->pageTitle = $this->configs['shop_title'];
        $conditions = array('Topic.start_time <=' => DateTime,'Topic.end_time >=' => DateTime);
        $conditions['Topic.status'] = 1;
        $topics = $this->Topic->find('all', array('conditions' => $conditions, 'order' => 'Topic.created DESC'));
        foreach ($topics as $k => $v) {
            $t_ids = $this->TopicProduct->find_topic_product_ids($v['Topic']['id']);
            if (isset($t_ids) && !empty($t_ids)) {
                if (isset($v['Topic']['front_num']) && $v['Topic']['front_num'] > 0) {
                    $limit = $v['Topic']['front_num'];
                } else {
                    $limit = '';
                }
                $t_products = $this->Product->find('all', array('conditions' => array('Product.id' => $t_ids), 'order' => $v['Topic']['orderby'], 'limit' => $limit));
                $topics[$k]['Product'] = $t_products;
            }
        }
        $this->set('topics', $topics);
        $this->layout = 'default';
    }

    /**
     *杂志.
     */
    public function magazine()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if (!empty($_POST['email'])) {
            $email = $_POST['email'];
            $news_list = $this->NewsletterList->find('first', array('conditions' => array('NewsletterList.email' => $email)));
            if (empty($news_list)) {
                $this->data['NewsletterList']['email'] = $email;
                $this->data['NewsletterList']['status'] = 1;
                $this->NewsletterList->saveAll(array('NewsletterList' => $this->data['NewsletterList']));
                $result['error'] = 1;
                $result['msg'] = $this->ld['success_sub'];
                die(json_encode($result));

                return;
            } else {
                //      $this->data['NewsletterList']['id']=$news_list['NewsletterList']['id'];
              //     $this->data['NewsletterList']['status']=1;
           //        $this->NewsletterList->save(array("NewsletterList" => $this->data['NewsletterList']));
                   $result['error'] = 2;
                $result['msg'] = $this->ld['email_has_been_sub'];
                die(json_encode($result));

                return;
            }
        }
    }

    /**
     *定制首页.
     */
    public function custom_made()
    {
        $this->pageTitle = $this->configs['shop_title'];
        $this->layout = 'default_full';
        $article_ids = $this->Comment->find('list', array('conditions' => array('Comment.status' => 1, 'Comment.type' => 'A'), 'fields' => 'Comment.type_id'));
        $articles = $this->Article->find('all', array('conditions' => $article_ids, 'fields' => array('Article.id', 'ArticleI18n.title')));
        $article_titles = array();
        foreach ($articles as $a) {
            $article_titles[$a['Article']['id']] = $a['ArticleI18n']['title'];
        }
        $this->set('article_titles', $article_titles);
        $this->page_init();
    }
    
    
    public function jsdemo()
    {
        $this->page_init();
        $this->pageTitle = $this->configs['shop_title'];
        $this->layout = 'default_page';
    }
    
    /*
    	过度跳转页面
    */
    function redirect_link(){
    	 $this->pageTitle = $this->configs['shop_title'];
        $this->layout = null;
        
        $redirect_link_url=isset($_REQUEST['redirect_link_url'])?$_REQUEST['redirect_link_url']:'';
        if(!empty($redirect_link_url)){
        	if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        		
        	}else{
        		header('Location:'.$redirect_link_url);
        	}
        }else{
        	$this->redirect("/");
        }
    }
}
