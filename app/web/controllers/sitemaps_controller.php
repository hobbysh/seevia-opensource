<?php

/*****************************************************************************
 * Seevia 网站导航
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 SitemapsController 的站点地图控制器.
 */
class  SitemapsController extends AppController
{
    /*
    *@var $name
    *@var $helpers
    *@var $uses
    *@var $cacheQueries
    *@var $cacheAction
    */
    public $name = 'Sitemaps';
    public $helpers = array('Time','Xml');
    public $uses = array('Brand','Product','Article','Sitemap','Topic','CategoryProduct');
    public $cacheQueries = true;
    public $cacheAction = '1 hour';

    /**
     *显示分类.
     */
    public function view()
    {
        Configure::write('debug', 1);
        //专题
        $Topic_list = $this->Topic->find_topics_fields(LOCALE);
        $this->set('Topic_list', $Topic_list);
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Promotion');
            //促销
            $nows = date('Y-m-d H:i:s');
            $Promotion_list = $this->Promotion->find('all', array('conditions' => array('Promotion.status' => 1, 'Promotion.end_time >' => $nows)));
            $this->set('Promotion_list', $Promotion_list);
        }
        //商品类目
        $product_cat = $this->CategoryProduct->tree('P', 0, LOCALE, $this);
        $this->set('product_cat', $product_cat['tree']);
        //pr($product_cat);exit();
        //品牌类目
        $brands = $this->Brand->findassoc(LOCALE);
        $this->set('brands', $brands);
        //推荐商品
        $rec_products = $this->Product->find('all', array('conditions' => array("1=1 and Product.status = '1' and Product.recommand_flag='1' ")));
        $this->set('rec_products', $rec_products);
        //商品 
        $products = $this->Product->find('all', array('conditions' => array("1=1 and Product.status = '1' and Product.recommand_flag='0' ")));
    //	pr($products);exit();
        $this->set('products', $products);
        //文章
        $articles = $this->Article->find('all', array('conditions' => array('1=1')));
        $this->set('articles', $articles);
        //文章类目
        $article_cat = $this->CategoryArticle->tree('A', 0, LOCALE);

        $this->set('article_cat', $article_cat['tree']);
        $ur_heres = array();
        $ur_heres[] = array('name' => $this->ld['home'],'url' => '/');
        $ur_heres[] = array('name' => $this->ld['sitemap'],'url' => '/sitemaps');
        $this->set('ur_heres', $ur_heres);
        $this->pageTitle = $this->ld['sitemap'].' - '.$this->configs['shop_title'];
        $categories_tree = array();
        //$this->page_init();
        $this->set('categories_tree', $categories_tree);
        $sitemap_list = $this->Sitemap->find('all', array('conditions' => array("1=1 and Sitemap.status = '1'"), 'order' => 'Sitemap.orderby'));
        $this->set('sitemaps', $sitemap_list);
        $this->layout = 'xml/default';
    }
    /**
     *显示.
     */
    public function index()
    {
        $this->page_init();
        //所有文章
        $articles = $this->Article->find('all', array('conditions' => array('Article.status' => 1, 'Article.recommand' => 1), 'order' => 'Article.orderby'));
        $article_list = array();
        if (!empty($articles)) {
            foreach ($articles as $k => $v) {
                $article_list[$v['Article']['category_id']][] = $v;
            }
        }
        $this->set('article_lists', $article_list);
        $this->ur_heres[] = array('name' => $this->ld['sitemap'],'url' => '/sitemaps');
        $this->pageTitle = $this->ld['sitemap'].' - '.$this->configs['shop_title'];
        $this->layout = 'default';
    }
}
