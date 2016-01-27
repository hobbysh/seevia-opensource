<?php

/*****************************************************************************
 * Seevia 用户收藏
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 ArticlesController 的控制器
 *文章控制器.
 *
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 */
/**
 *文章显示.
 *
 *对于Articles这张表的查寻
 *
 *@author   hechang 
 *
 *@version  $Id$
 */
class   ArticlesController extends AppController
{
    public $name = 'Articles';
    public $components = array('Pagination'); // Added
    public $uses = array('Flash','Article','Comment','ArticleCategory','Product','Sitemap','ProductArticle','Tag','ProductLocalePrice','UserRank','ProductRank','ArticleI18n','ArticleGallery','ArticleGalleryI18n','Attitude','UploadFile','CategoryArticle','CategoryArticleI18n','BlockWord','OpenModel');
    public $helpers = array('Pagination','Time','Xml','Rss','Text','Flash'); //,'HtmlCache'
    /**
    *显示文章首页.
    *
    *@param $locale 输入语言
    *@param $cat_id 输入id
    *@param $page 输入分页
    */
    public function index($page = 1)
    {
        $this->params['page'] = $page;

        $this->pageTitle = $this->ld['article'].$this->ld['home'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['article'].$this->ld['home'],'url' => '');
        $this->params['locale'] = LOCALE;
        if (isset($this->configs['article_category_page_list_number']) && !empty($this->configs['article_category_page_list_number'])) {
            $this->params['ControllerObj'] = $this;//控制器对象
        }
        $this->page_init($this->params);
        $this->getVotes();
    }
    /**
     *显示其他详细信息 如相册 文章详细.
     *
     *@param $id 输入id
     *@param $local 输入地区
     *@param $name 输入名字
     */
    public function view($id = '')
    {
        $this->parmas['id'] = $id;
        $this->params['locale'] = LOCALE;
        $article = $this->Article->find('first', array('conditions' => array('Article.id' => $id), 'fields' => 'Article.id,Article.type,Article.status,Article.category_id,Article.template,Article.layout,Article.displayed_title,Article.file_url,Article.displayed_add_time,Article.upload_file_id,Article.created,Article.showtime,ArticleI18n.title,ArticleI18n.subtitle,ArticleI18n.author,ArticleI18n.content,ArticleI18n.meta_description,ArticleI18n.meta_keywords,ArticleI18n.created,ArticleI18n.img01'));
        $this->set('article', $article);
        if ($article['Article']['type'] == 'M' && $article['Article']['template'] == '' && $article['Article']['layout'] == '') {
            $article_galleries = $this->ArticleGallery->find('all', array('conditions' => array('ArticleGallery.article_id' => $id), 'order' => 'ArticleGallery.orderby'));
            $this->set('article_galleries', $article_galleries);
            $this->layout = 'default_magazine';
            $this->render('magazine', $this->layout);
        }
        $word = $this->BlockWord->find('all');
        $this->set('word', $word);
	 if (empty($article)) {
            $this->pageTitle = $this->ld['article'].$this->ld['not_exist'].' - '.$this->configs['shop_title'];
            $msg = $this->ld['article'].$this->ld['not_exist'];
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/"</script>';
            die();
            $flag = 0;
            return;
        } elseif ($article['Article']['status'] != 1) {
            $this->pageTitle = $this->ld['article'].$this->ld['not_exist'].' - '.$this->configs['shop_title'];
            $msg = $this->ld['article'].$this->ld['not_exist'];
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/"</script>';
            die();
            $flag = 0;
            return;
        }
        if (!empty($article) && $article['Article']['status'] == '1') {
            $this->pageTitle = $article['ArticleI18n']['title'].' - '.$this->configs['shop_title'];
        }
        $this->page_init($this->params);
        $this->set('meta_description', $article['ArticleI18n']['meta_description'].' '.$this->configs['seo-des']);
        $this->set('meta_keywords', $article['ArticleI18n']['meta_keywords'].' '.$this->configs['seo-key']);
        if (!empty($article) && $article['Article']['upload_file_id'] != '') {
            $file_info = $this->UploadFile->find('first', array('conditions' => array('UploadFile.id' => $article['Article']['upload_file_id']), 'fields' => 'UploadFile.id,UploadFile.name,UploadFile.file_url'));
            if (!empty($file_info)) {
                $article['Article']['upload_file_name'] = $file_info['UploadFile']['name'];
                $article['Article']['upload_file_url'] = $file_info['UploadFile']['file_url'];
            }
        }
        $this->Article->updateAll(array('Article.clicked' => 'Article.clicked + 1'), array('Article.id' => $article['Article']['id']));
        $r_ur_heres[] = array('name' => $article['ArticleI18n']['title'], 'url' => '');
        $tempurl = '';
        //分类的根
        $root_category_id = $article['Article']['category_id'];
        $category_parent = $this->Article->find('first', array('conditions' => array('category_id' => $root_category_id), 'limit' => 1, 'fields' => 'Article.category_id'));
        if (!empty($category_parent)) {
            $catroot_category_id = $category_parent['Article']['category_id'];
            $i = 0;
            $cat_parent = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $catroot_category_id, 'CategoryArticleI18n.locale' => LOCALE), 'fields' => 'CategoryArticle.id,CategoryArticle.link,CategoryArticle.parent_id,CategoryArticleI18n.name'));
            while (!empty($cat_parent) && $i++ < 10) {
                $tempurl = '/articles/category/'.$cat_parent['CategoryArticle']['id'];
                if ($cat_parent['CategoryArticle']['link'] == '') {
                    $r_ur_heres[] = array('name' => $cat_parent['CategoryArticleI18n']['name'], 'url' => $tempurl);
                } elseif (isset($this->CategoryArticle->allinfo['A']['direct_subids'][$cat_parent['CategoryArticle']['id']]) && $cat_parent['CategoryArticle']['id'] == $topcat2['CategoryArticle']['id']) {
                    $r_ur_heres[] = array('name' => $topcat2['CategoryArticleI18n']['name'], 'url' => '/'.$topcat2['CategoryArticle']['link']);
                }
                $catroot_category_id = $cat_parent['CategoryArticle']['parent_id'];
                $cat_parent = array();
                $cat_parent = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $catroot_category_id), 'fields' => 'CategoryArticle.id,CategoryArticle.link,CategoryArticle.parent_id,CategoryArticleI18n.name'));
            }
        }
        if (!empty($r_ur_heres)) {
            $ur_heres = array_reverse($r_ur_heres);
            foreach ($ur_heres as $v) {
                $this->ur_heres[] = $v;
            }
        }
        /* 设置文章模板布局 */
        if (!empty($article['Article']['layout'])) {
            $this->render['layout'] = $article['Article']['layout'];
        } else {
            $this->layout = 'default_full';
        }
        /* 设置文章模板 */
        if (!empty($article['Article']['template']) && $article['Article']['template'] != 'default') {
            $this->render['action']=$article['Article']['template'];
        }
    }
    /**
     *订阅.
     *
     *@param $category_id 输入id
     */
    public function rss($category_id = 0)
    {
        $this->layout = '/rss/articles';
        if ($category_id > 0) {
            $condition['and']['or']['Article.category_id'] = $category_id;
        }
        $condition['and']['and']['Article.status'] = 1;
        $article_list = $this->Article->find('all', array('conditions' => $condition, 'order' => 'Article.created desc', 'limit' => '7'));
        $this->set('this_locale', LOCALE);
        $this->set('this_url', $this->server_host.$this->webroot);
        $this->set('dynamic', '文章动态');
        $this->set('this_config', $this->configs);
        $this->set('articles', $article_list);
        Configure::write('debug', 0);
    }
    /**
     *推荐订阅.
     *
     *@param $limit 输入限制条件
     */
    public function recommend_rss($limit = 7)
    {
        $this->layout = '/rss/articles';

        $condition['Article.recommand'] = 1;
        $article_list = $this->Article->find('all', array('conditions' => $condition, 'order' => 'Article.orderby asc,Article.created desc', 'limit' => $limit));
        $this->set('this_locale', LOCALE);
        $this->set('this_url', $this->server_host.$this->webroot);
        $this->set('dynamic', '文章推荐');
        $this->set('this_config', $this->configs);
        $this->set('articles', $article_list);
        Configure::write('debug', 0);
    }

    /*
    *获取当前文章分类的分类名称
    *@param $id 输入id
    */
    public function get_category_name($id)
    {
        $category_name = '';
        $Category_info = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $id)));
        if (isset($Category_info['CategoryArticleI18n'])) {
            return $Category_info['CategoryArticleI18n']['name'];
        } else {
            return;
        }
    }

    /**
     *文章的类别判断.
     *
     *@param $cat_id 输入id
     *@param $locale 输入语言
     *@param $page 输入页数
     *@param $orderby 输入整理
     *@param $rownum 输入行数
     */
    public function category($id, $page = 1, $limit = 0, $order_field = 0, $order_type = 0, $showtype = 0, $type = 0, $keyword = '')
    {
        $this->set('article_id', $id);
        $this->params['id'] = $id;
        $this->params['page'] = $page;
        $this->params['locale'] = LOCALE;

        //设置网页标题
        $this->pageTitle = $this->get_category_name($id).' - '.$this->configs['shop_title'];
        $this->page_init($this->params);

        $order_field = UrlDecode($order_field);
        $order_type = UrlDecode($order_type);
        $limit = UrlDecode($limit);
        $showtype = UrlDecode($showtype);
        $keyword = UrlDecode($keyword);
        $flag = 1;

        //id不存在跳转
        if (!is_numeric($id) || $id < 1) {
            $this->pageTitle = $this->ld['parameter_error'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['parameter_error'], '/', 5);

            return;
        }
        $CategoryArticleInfo = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $id)));

        if (empty($CategoryArticleInfo)) {
            $this->pageTitle = $this->ld['classificatory'].$this->ld['not_exist'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['classificatory'].$this->ld['not_exist'], '/', 5);

            return;
        }
        $this->set('CategoryArticleInfo', $CategoryArticleInfo);
        //扩展分类

        //该分类上级分类
        $topcat = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $id, 'CategoryArticleI18n.locale' => LOCALE)));
        if (isset($topcat['CategoryArticle']['link']) && $topcat['CategoryArticle']['link'] != '') {
            $this->redirect($topcat['CategoryArticle']['link']);
        }
        $topcat2 = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $topcat['CategoryArticle']['parent_id']), 'fields' => 'CategoryArticle.id,CategoryArticle.parent_id,CategoryArticleI18n.name'));
        if ($topcat['CategoryArticle']['parent_id'] != '') {
            if ($topcat2 != '' && $topcat2['CategoryArticle']['parent_id'] != '0') {
                $parent_id = $topcat2['CategoryArticle']['parent_id'];
                $this->set('pid', $topcat['CategoryArticle']['parent_id']);
            } else {
                $parent_id = $topcat['CategoryArticle']['parent_id'];
            }
            $cat_infos = $this->CategoryArticle->find('all', array('condtions' => array('CategoryArticle.parent_id' => $parent_id)));

            if (!empty($cat_infos) && sizeof($cat_infos) > 0) {
                $this->set('sub_cat_infos', $cat_infos);
                $this->set('cid', $id);
            }
        }
        $this->set('detail', $topcat['CategoryArticleI18n']['detail']);
        $this->set('meta_description', $topcat['CategoryArticleI18n']['meta_description']);
        $this->set('meta_keywords', $topcat['CategoryArticleI18n']['meta_keywords']);
        $this->pageTitle = $topcat['CategoryArticleI18n']['name'].' - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];//
        if (empty($topcat)) {
            $category = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => 0)));
        } else {
            //ctp显示方式
            if ($topcat['CategoryArticle']['code'] == 'anli') {
                $this->set('optdemo', $topcat['CategoryArticle']['code']);
            }
            $category = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $topcat['CategoryArticle']['parent_id'])));
        }
        $category = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $id)));
        $nullcat = array();
        $nullcat['CategoryArticleI18n']['name'] = '';
        $nullcat['CategoryArticle']['id'] = 0;
        $this->set('category', empty($category) ? $nullcat : $category);
        $this->set('categoryname', empty($topcat) ? array() : $topcat);
        //面包屑
        $tempurl = '/articles/category/'.$topcat['CategoryArticle']['id'];
        $r_ur_heres[] = array('name' => $topcat['CategoryArticleI18n']['name'], 'url' => $tempurl);

        //分类的根
        $root_category_id = $id;

        $i = 0;
        while (!empty($this->CategoryArticle->allinfo['A']['assoc'][$root_category_id]['CategoryArticle']['parent_id']) && $i++ < 10) {
            $root_category_id = $this->CategoryArticle->allinfo['A']['assoc'][$root_category_id]['CategoryArticle']['parent_id'];
            $r_ur_heres[] = array('name' => $this->CategoryArticle->allinfo['A']['assoc'][$root_category_id]['CategoryArticleI18n']['name'], 'url' => '/articles/category/'.$this->CategoryArticle->allinfo['A']['assoc'][$root_category_id]['CategoryArticle']['id']);
        }
        $ur_heres = array_reverse($r_ur_heres);
        foreach ($ur_heres as $v) {
            $this->ur_heres[] = $v;
        }

        //seo
        //pr($category);
//        if (empty($limit)) {
//        }
        $limit = ($limit > 0) ? $limit : (isset($this->configs['products_category_page_size']) ? $this->configs['products_category_page_size'] : 20);
 //       $limit = isset($this->configs['products_category_page_size']) ? $this->configs['products_category_page_size'] : ((!empty($limit)) ? $limit : 20);

        if (empty($showtype)) {
            $showtype = isset($this->configs['products_list_showtype']) ? $this->configs['products_list_showtype'] : ((!empty($showtype)) ? $showtype : 'L');
        }
        $order_fields = array('Article.created');
        if (!empty($order_field) && in_array($order_field, $order_fields)) {
        } else {
            $order_field = 'Article.created';
            $order_type = 'desc';
        }

        if ($limit == 'all') {
            $limit = 99999;
        }
        //扩展分类
        $kuozhan = $this->ArticleCategory->find('list', array('conditions' => array('category_id' => $id), 'fields' => 'article_id'));

        $conditions['OR']['AND']['Article.category_id'] = $id;
        $conditions['AND']['Article.status'] = 1;
        $conditions['OR']['OR']['Article.id'] = $kuozhan;

        if ($type == 'recommend') {//推荐
        }
        if ($keyword != '') {
            $conditions['OR']['ArticleI18n.title like'] = "%$keyword%";
            $conditions['OR']['ArticleI18n.meta_description like'] = "%$keyword%";
        }
        //pr($conditions);
        //分页start
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'category','id' => $id,'page' => $page,'limit' => $limit,'order_field' => $order_field,'order_type' => $order_type,'showtype' => $showtype,'type' => $type,'keyword' => $keyword);
        //分页参数
        $options = array('page' => $page,'show' => $limit,'modelClass' => 'Article');

        $page = $this->Pagination->init($conditions, $parameters, $options); // Added
        //分页end
        $options = array();
        $options['conditions'] = $conditions;
        //pr($conditions);
        //$options['order'] = $order_field.' '.$order_type;
        $options['order'] = 'orderby asc,Article.created desc';
        $options['limit'] = $limit;
        $options['page'] = $page;
        $this->set('id', $id);
        $articles_flash = $this->Flash->find("type ='AC' and page_id='$id' ");
        if (!empty($articles_flash) && !empty($articles_flash['FlashImage'])) {
            $this->set('flashes', $articles_flash); //flash轮播
        }

        $articles = $this->Article->find('all', $options); //model

        $this->set('articles', $articles);

        /* 设置文章模板布局 */
        if (!empty($topcat['CategoryArticle']['layout'])) {
            $this->layout = $topcat['CategoryArticle']['layout'];
        } else {
            $this->layout = 'default';
        }
        /* 设置文章模板 */
        if (!empty($topcat['CategoryArticle']['template']) && $topcat['CategoryArticle']['template'] != 'default') {
            $this->render($topcat['CategoryArticle']['template'], $this->layout);
        }
    }

    /**
     *搜索.
     *
     *@param $tag 输入标签
     *@param $page 输入页
     *@param $orderby 输入类型
     *@param $rownum 输入行数
     */
    public function search($keyword = '', $page = 1, $limit = 0, $order_field = 0, $order_type = 0, $showtype = 0, $type = 0)
    {
        $this->set('search_type', 'a');
        $this->page_init();
        $order_field = UrlDecode($order_field);
        $order_type = UrlDecode($order_type);
        $limit = UrlDecode($limit);
        $showtype = UrlDecode($showtype);

       //带冒号的关键字，对GET过来的参数做替代处理
       if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
           $keyword = $_GET['keyword'];
       }

        $keyword = UrlDecode($keyword);

        $keyword = addslashes($keyword);
        //面包屑
        $this->ur_heres[] = array('name' => $this->ld['search'], 'url' => '');
        $this->ur_heres[] = array('name' => $this->ld['article'], 'url' => '/articles');
        $this->ur_heres[] = array('name' => $keyword, 'url' => '');

        //seo
        $this->set('meta_description', $keyword);
        $this->set('meta_keywords', $keyword);
        $this->pageTitle = $keyword.' - '.$this->configs['shop_title'];

        if (empty($limit)) {
            $limit = isset($this->configs['products_category_page_size']) ? $this->configs['products_category_page_size'] : ((!empty($limit)) ? $limit : 20);
        }
        if (empty($showtype)) {
            $showtype = isset($this->configs['products_list_showtype']) ? $this->configs['products_list_showtype'] : ((!empty($showtype)) ? $showtype : 'L');
        }
        $order_fields = array('Article.created');
        if (!empty($order_field) && in_array($order_field, $order_fields)) {
        } else {
            $order_field = 'Article.created';
            $order_type = 'desc';
        }

        if ($limit == 'all') {
            $limit = 99999;
        }

        $conditions['AND']['Article.status'] = 1;

        if ($type == 'recommend') {//推荐
        }
        if ($keyword != '') {
            $conditions['OR']['ArticleI18n.title like'] = "%$keyword%";
            $conditions['OR']['ArticleI18n.meta_description like'] = "%$keyword%";
        }
        if (is_array($keyword) && sizeof($keyword) > 0) {
            foreach ($keyword as $k => $v) {
                $tag_conditions['and']['or'][]['name like'] = "%$v%";
            }
        }
        $tag_conditions['and']['type'] = 'A';
        $tag_infos = $this->Tag->find('all', array('conditions' => $tag_conditions, 'fields' => 'Tag.type_id', 'group' => 'Tag.type_id'));
        if (!empty($tag_infos)) {
            $aids = array();
            foreach ($tag_infos as $t) {
                $aids[] = $t['Tag']['type_id'];
            }
            $conditions['OR']['Article.id'] = $aids;
        }
        $nullcat = array();
        $nullcat['CategoryArticleI18n']['name'] = '';
        $nullcat['CategoryArticle']['id'] = 0;
        $this->set('CategoryArticle', $nullcat);
        $this->set('article_categories_direct_subids', $this->CategoryArticle->allinfo['A']['direct_subids']);

        //分页start
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'search','page' => $page,'limit' => $limit,'order_field' => $order_field,'order_type' => $order_type,'showtype' => $showtype,'type' => $type,'keyword' => $keyword);
        //分页参数
        $options = array('page' => $page,'show' => $limit,'modelClass' => 'Article');
        $page = $this->Pagination->init($conditions, $parameters, $options); // Added
        //分页end
        $options = array();
        $options['conditions'] = $conditions;
        $options['order'] = $order_field.' '.$order_type;
        $options['limit'] = $limit;
        $options['page'] = $page;

        $articles = $this->Article->find('all', $options); //model
        $this->set('keyword', $keyword);
       // pr($articles);
        $this->set('articles', $articles);
    }
    /**
     *杂志.
     **/
    public function magazine()
    {
        $this->page_init();
        $this->layout = 'default_magazine';
        $this->pageTitle = '杂志 - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => '杂志', 'url' => '');
    }
    /**
     *网站地图.
     */
    public function sitemap()
    {
        $this->page_init();
        $this->layout = 'default_full';

        return;
        Configure::write('debug', 0);
        //文章
        $articles = $this->Article->find('all', array('conditions' => array('Article.status' => 1), 'order' => 'Article.created DESC'));
        $this->set('articles', $articles);
        //文章类目
        $article_cat = $this->CategoryArticle->tree('A', 0, LOCALE);
        //pr($article_cat);
        $this->set('article_cat', $article_cat['tree']);
        $ur_heres = array();
        $ur_heres[] = array('name' => $this->ld['home'],'url' => '/');
        $ur_heres[] = array('name' => $this->ld['sitemap'],'url' => '/sitemaps');
    //	$this->set('languages',LOCALE);
        $this->set('ur_heres', $ur_heres);
        $this->pageTitle = $this->ld['sitemap'].' - '.$this->configs['shop_title'];
        $categories_tree = array();
        $this->set('categories_tree', $categories_tree);
        //pr($categories_tree);
        $sitemap_list = $this->Sitemap->findall("1=1 and Sitemap.status = '1'");
        $this->set('sitemaps', $sitemap_list);

        $this->layout = 'xml/default';
    }
    public function home()
    {
        $this->layout = 'articles_home';
        $this->pageTitle = '资讯中心 - '.$this->configs['shop_title'];
        $this->page_init();
        $articles_flash = $this->Flash->find("type ='AH'");
        if (!empty($articles_flash) && !empty($articles_flash['FlashImage'])) {
            $this->set('flashes', $articles_flash); //flash轮播
        }
//L1
        $category_id_L1 = $this->CategoryArticle->find('all', array('conditions' => array('code' => 'L1'), 'fields' => array('id', 'CategoryArticleI18n.name'), 'order' => 'orderby asc'));
        //pr($category_id_L1);
        $cat_id_L1 = array();
        for ($i = 0;$i < count($category_id_L1); ++$i) {
            $cat_id_L1[$i]['id'] = $category_id_L1[$i]['CategoryArticle']['id'];
            $cat_id_L1[$i]['CategoryName'] = $category_id_L1[$i]['CategoryArticleI18n']['name'];
        }

        //pr($cat_id_L1);
        $homearticle_listsL1_arr = array();
        for ($i = 0;$i < count($cat_id_L1);++$i) {
            //扩展分类文章id
            $kuozhang_l = $this->ArticleCategory->find('list', array('conditions' => array('category_id' => $this->CategoryArticle->allinfo['A']['subids'][$cat_id_L1[$i]['id']]), 'fields' => 'article_id'));

            $condition = array();
            $condition['or']['category_id'] = $this->CategoryArticle->allinfo['A']['subids'][$cat_id_L1[$i]['id']];
            $condition['or']['Article.id'] = $kuozhang_l;
            $condition['and']['Article.status'] = 1;
            //pr($condition);
            $homearticle_listsL1_arr[$i]['ArticleInfo'] = $this->Article->find('all', array('conditions' => $condition, 'order' => 'Article.created desc,orderby asc', 'limit' => '25'));
            $homearticle_listsL1_arr[$i]['CategoryName'] = $cat_id_L1[$i]['CategoryName'];
            $homearticle_listsL1_arr[$i]['CategoryId'] = $cat_id_L1[$i]['id'];
        //	pr($homearticle_listsL1);
        }

        $this->set('homearticle_listsL1', $homearticle_listsL1_arr);
        //pr($homearticle_listsL1_arr);


        $category_id_C1 = $this->CategoryArticle->find('all', array('conditions' => array('code' => 'C1'), 'fields' => array('id', 'CategoryArticleI18n.name')));
        //pr($category_id_L1);
        $cat_id_C1 = array();
        for ($i = 0;$i < count($category_id_C1); ++$i) {
            $cat_id_C1[$i]['id'] = $category_id_C1[$i]['CategoryArticle']['id'];
            $cat_id_C1[$i]['CategoryName'] = $category_id_C1[$i]['CategoryArticleI18n']['name'];
        }

        //pr($cat_id_L1);
        $homearticle_listsC1_arr = array();
        for ($i = 0;$i < count($cat_id_C1);++$i) {
            //扩展分类文章id
            $kuozhang_c = $this->ArticleCategory->find('list', array('conditions' => array('category_id' => $this->CategoryArticle->allinfo['A']['subids'][$cat_id_C1[$i]['id']]), 'fields' => 'article_id'));

            $condition = array();
            $condition['or']['category_id'] = $this->CategoryArticle->allinfo['A']['subids'][$cat_id_C1[$i]['id']];
            $condition['or']['Article.id'] = $kuozhang_c;
            $condition['and']['Article.status'] = 1;

            $homearticle_listsC1_arr[$i]['ArticleInfo'] = $this->Article->find('all', array('conditions' => $condition, 'order' => 'Article.created desc,orderby asc', 'limit' => '25'));
            $homearticle_listsC1_arr[$i]['CategoryName'] = $cat_id_C1[$i]['CategoryName'];
            $homearticle_listsC1_arr[$i]['CategoryId'] = $cat_id_C1[$i]['id'];
            //pr($homearticle_listsC1_arr);
        }

        $this->set('homearticle_listsC1', $homearticle_listsC1_arr);

        $category_id_R1 = $this->CategoryArticle->find('all', array('conditions' => array('code' => 'R1'), 'fields' => array('id', 'CategoryArticleI18n.name')));
        //pr($category_id_L1);
        $cat_id_R1 = array();
        for ($i = 0;$i < count($category_id_R1); ++$i) {
            $cat_id_R1[$i]['id'] = $category_id_R1[$i]['CategoryArticle']['id'];
            $cat_id_R1[$i]['CategoryName'] = $category_id_R1[$i]['CategoryArticleI18n']['name'];
        }

        //pr($cat_id_L1);
        $homearticle_listsR1_arr = array();
        for ($i = 0;$i < count($cat_id_R1);++$i) {
            //扩展分类文章id
            $kuozhang_r = $this->ArticleCategory->find('list', array('conditions' => array('category_id' => $this->CategoryArticle->allinfo['A']['subids'][$cat_id_R1[$i]['id']]), 'fields' => 'article_id'));

            $condition = array();
            $condition['or']['category_id'] = $this->CategoryArticle->allinfo['A']['subids'][$cat_id_R1[$i]['id']];
            $condition['or']['Article.id'] = $kuozhang_r;
            $condition['and']['Article.status'] = 1;

            $homearticle_listsR1_arr[$i]['ArticleInfo'] = $this->Article->find('all', array('conditions' => $condition, 'order' => 'Article.created desc,orderby asc', 'limit' => '25'));
            $homearticle_listsR1_arr[$i]['CategoryName'] = $cat_id_R1[$i]['CategoryName'];
            $homearticle_listsR1_arr[$i]['CategoryId'] = $cat_id_R1[$i]['id'];
        //	pr($homearticle_listsL1);
        }

        $this->set('homearticle_listsR1', $homearticle_listsR1_arr);
        $this->ur_heres[] = array('name' => $this->ld['article'],'url' => '');
    }
    public function helpcenter($page = 1, $limit = 0)
    {
        $limit = isset($this->configs['products_category_page_size']) ? $this->configs['products_category_page_size'] : ((!empty($limit)) ? $limit : 1);
        $condition = "Article.type='H' and status=1";
                //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'helpcenter','page' => $page,'limit' => $limit);
        $options = array('page' => $page,'show' => $limit,'modelClass' => 'Article');

        $this->Pagination->init($condition, $parameters, $options);
        //pr($page);
        //$my_orders=$this->Order->get_my_orders($condition,$rownum,$page);
        $articles = $this->Article->find('all', array('conditions' => $condition, 'limit' => $limit, 'page' => $page));
        $this->set('articles', $articles);
    }
    //在线调研
    public function getVotes()
    {
        $now = date('Y-m-d H:i:s');
        $votes = $this->Vote->find('first', array('conditions' => array('Vote.start_time <=' => $now, 'Vote.end_time >=' => $now, 'Vote.status' => 1), 'order' => 'Vote.modified desc'));
        if (!empty($votes)) {
            $this->Vote->set_locale($this->locale);
            $this->VoteOption->set_locale($this->locale);
            $votes['VoteOptions'] = $this->VoteOption->find('all', array('conditions' => array('VoteOption.vote_id' => $votes['Vote']['id'], 'VoteOption.status' => 1)));
            $this->set('votes', $votes);
        }
    }

    public function module_hotel_promotions()
    {
        $this->layout = 'default';
        $this->page_init();
    }

    /**
     * 添加文章 顶 踩 ..
     */
    public function ajax_add_attitude()
    {
    	 //登录验证
        $this->checkSessionUser();
        if (isset($_POST)) {
        	$_POST=$this->clean_xss($_POST);
        }
        $attitude = array();
        $attitude['type'] = isset($_POST['type']) ? $_POST['type'] : 'A';
        $attitude['type_id'] = isset($_POST['type_id']) ? $_POST['type_id'] : '0';
        $attitude['action'] = isset($_POST['action']) ? $_POST['action'] : '0';
        $attitude['user_id'] = isset($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '0';
        //没有登陆跳到登录页
        if ($attitude['user_id'] == '0') {
            $result['flag'] = 0;
            $result['msg'] = '请先登录！';
        } else {
            $this->Attitude->save($attitude);
            unset($attitude['user_id']);
            $action_count = $this->Attitude->find('count', array('conditions' => $attitude));
        //修改文章的踩 顶的总数
            $result['flag'] = 1;
            $result['msg'] = 'Success';
            $result['count'] = $action_count;
        }
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }
    /**
     * 函数video 视频详情(传视频id).
     *
     * @param $id 视频id
     * @param $pages 评论页数
     */
    public function video($id = 0, $pages = 1)
    {
        $this->parmas['id'] = $id;
        $this->parmas['page'] = $pages;
        //屏蔽字符
        /*$word=$this->BlockWord->find("all");
        $this->set("word",$word);*/
        //获取文章视频
        $conditions = array();
        $conditions['type'] = 'V';
        $conditions['Article.id'] = $id;
        $video = $this->Article->find('first', array('conditions' => $conditions));

        $this->set('video', $video);

        //pr($video['Article']['category_id']);
        //获取相关视频列表
        $video_list = $this->Article->find('all', array('conditions' => array('Article.type' => 'V', 'Article.category_id' => $video['Article']['category_id'])));
        $this->set('video_list', $video_list);
        $video_type = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $video['Article']['category_id'])));
        //pr($video);
        $this->set('video_type', $video_type);
        $ur_heres = array();
        $r_ur_heres[] = array('name' => $video['ArticleI18n']['subtitle'],'url' => '');//视频详细标题名称
        $r_ur_heres[] = array('name' => $video['ArticleI18n']['title'],'url' => '');//视频标题名称
        $r_ur_heres[] = array('name' => $video_type['CategoryArticleI18n']['name'],'url' => '');//分类名称
        $ur_heres = array_reverse($r_ur_heres);
        foreach ($ur_heres as $v) {
            $this->ur_heres[] = $v;
        }
        $this->pageTitle = $video['ArticleI18n']['title'].' - '.$this->configs['shop_title'];
        //判断是否收藏过该视频
        //用户是否登录
        $boolean_collect = 'false';
        $boolean_recommend = 'false';
//		if(!empty($_SESSION['User']['User']['id'])){
//			$userid=$_SESSION['User']['User']['id'];
//			$liked=$this->UserLike->find('first',array('conditions'=>array('user_id'=>$userid,'type'=>'A','type_id'=>$id,"action"=>"like")));
//			$recommend=$this->UserLike->find('first',array('conditions'=>array('user_id'=>$userid,'type'=>'A','type_id'=>$id,"action"=>"recommend")));
//			if(!empty($liked)){
//				$boolean_collect="true";
//			}else{
//				$boolean_collect="false";
//			}
//			if(!empty($recommend)){
//				$boolean_recommend="true";
//			}else{
//				$boolean_recommend="false";
//			}
//		}
        //pr($boolean_collect);
        $this->set('boolean_recommend', $boolean_recommend);
        $this->set('boolean_collect', $boolean_collect);
        //分页start
        //视频评论
        //$video_comments = $this->Comment->find_comments_by_num($id,3,'A');//model

        $cond['Comment.type_id'] = $id;
        $cond['Comment.status'] = 1;
        $cond['Comment.type'] = 'A';
        $limit = 12;
        $options['conditions'] = $cond;
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'video/'.$id,'page' => $pages,'limit' => $limit);
        //分页参数
        $options = array('page' => $pages,'show' => $limit,'modelClass' => 'Comment');
        $page = $this->Pagination->init($cond, $parameters, $options); // Added
        //分页end
        $comment_conditions['type_id'] = $id;
        $comment_conditions['type'] = 'A';
        $comment_conditions['status'] = 1;
        $video_comments = $this->Comment->find('all', array('conditions' => $comment_conditions, 'limit' => $limit, 'page' => $pages, 'order' => 'modified desc'));
//		$average_product_comment = 0;//平均评论得分
//		if(!empty($video_comments)){
//			$all_rank = 0;
//			foreach($video_comments as $k=>$v){
//				$all_rank += $v['Comment']['rank'];
//			}
//			$average_product_comment = ceil($all_rank/count($video_comments));
//		}
        //拼装用户信息
        if (!empty($video_comments)) {
            foreach ($video_comments as $k => $v) {
                $video_comments[$k]['User'] = $this->User->find('first', array('conditions' => array('User.id' => $v['Comment']['user_id'])));
                $video_comments[$k]['Reply'] = $this->Comment->find('all', array('conditions' => array('Comment.parent_id' => $v['Comment']['id']), 'order' => 'created desc'));
                if (!empty($video_comments[$k]['Reply'])) {
                    foreach ($video_comments[$k]['Reply'] as $kk => $vv) {
                        $video_comments[$k]['Reply'][$kk]['User'] = $this->User->find('first', array('conditions' => array('User.id' => $vv['Comment']['user_id'])));
                    }
                }
            }
        }
        $this->set('video_comments', $video_comments);
        $articlesInfo = $this->Article->find('all', array('fields' => array('Article.id', 'ArticleI18n.title', 'ArticleI18n.img01', 'ArticleI18n.subtitle'), 'conditions' => array('Article.recommand' => '1', 'Article.status' => '1', 'Article.type' => 'V'), 'order' => array('Article.modified desc'), 'limit' => '4'));
        $this->set('articlesInfo', $articlesInfo);
        if (isset($this->configs['articles_comment_condition'])) {
            $this->set('comment_condition', $this->configs['articles_comment_condition']);
        }
        $this->page_init($this->parmas);
    }
    //新增文章视频评论
    public function add_video_comment()
    {
        //pr($_POST);die;
        //获取用户email，name
        if ($this->RequestHandler->isPost()) {
            $uid = $_POST['user_id'];
            $user = $this->User->find_user_by_id($uid);
            $status = 0;
            if (isset($this->configs['enable_user_comment_check']) && $this->configs['enable_user_comment_check'] == 0) {
                $status = 1;
            }
            $this->data['Comment']['user_id'] = $uid;
            $this->data['Comment']['email'] = isset($user['User']['email']) ? $user['User']['email'] : '';
            $this->data['Comment']['name'] = isset($user['User']['name']) ? $user['User']['name'] : '';
            $this->data['Comment']['content'] = !empty($this->data['Comment']['content']) ? $this->data['Comment']['content'] : '';//用户评论
            $this->data['Comment']['type_id'] = !empty($this->data['Comment']['type_id']) ? $this->data['Comment']['type_id'] : '';
            $this->data['Comment']['status'] = $status;//评论审核默认状态（有效）
            $this->data['Comment']['rank'] = 5;
            $this->data['Comment']['type'] = 'A';
            $this->data['Comment']['parent_id'] = '0';
            $this->data['Comment']['ipaddr'] = $this->RequestHandler->getClientIP();
            $this->data['Comment']['created'] = date('Y-m-d H:i:s');//用户创建时间
            $this->data['Comment']['modified'] = date('Y-m-d H:i:s');//用户修改时间

            //图片处理
            if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0) {
                $file_true = ($_FILES['userImg']['size'] / 1048576) > 2 ? false : true;//判断图片大小
                if ($file_true) {
                    //图片上传处理
                    $imgname_arr = explode('.', $_FILES['upfile']['name']);//获取文件名
                    if ($imgname_arr[1] == 'jpg' || $imgname_arr[1] == 'gif' || $imgname_arr[1] == 'png' || $imgname_arr[1] == 'bmp' || $imgname_arr[1] == 'jpeg') {
                        //判断文件格式（限制图片格式）
                        $img_thumb_name = md5($imgname_arr[0].time());
                        $image_name = $img_thumb_name.'.'.$imgname_arr[1];
                        $imgaddr = WWW_ROOT.'media/comment/'.date('Ym').'/';
                        $image_width = 180;
                        $image_height = 180;
                        $img_detail = str_replace($image_name, '', $imgaddr);
                        $this->mkdirs($imgaddr);
                        move_uploaded_file($_FILES['upfile']['tmp_name'], $imgaddr.$image_name);
                        $this->data['Comment']['img'] = '/media/comment/'.date('Ym').'/'.$image_name;
                    }
                }
            }
            $this->Comment->save(array('Comment' => $this->data['Comment']));
            //pr($this->data['Comment']);die;
        }
        $this->redirect('/articles/video/'.$this->data['Comment']['type_id']);
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
    //视频评论的回复
    public function reply_comment()
    {
        //ajax刷新还是页面刷新?
        //pr($_POST);
        //新增评论
//		$imgaddr = WWW_ROOT."img/comment_reply/".date('Ym')."/";
//		
//		if(!empty($_POST['img'])){
//			move_uploaded_file($_POST['img'],$imgaddr.$_POST['img']);
//		}
        if ($this->RequestHandler->isPost()) {
            $this->data['Comment']['user_id'] = !empty($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '';//用户id
            $this->data['Comment']['parent_id'] = !empty($_POST['parent_id']) ? $_POST['parent_id'] : 0;
            $this->data['Comment']['content'] = !empty($_POST['content']) ? $_POST['content'] : '';//用户日志
            $this->data['Comment']['created'] = date('Y-m-d H:i:s');//用户创建时间
            $this->data['Comment']['modified'] = date('Y-m-d H:i:s');//用户修改时间
            //$this->data['Comment']['img']=!empty($_POST['img']) ?"/img/comment_reply/".date('Ym')."/".$_POST['img']: "";
            $this->data['Comment']['status'] = 1;//日志默认状态（有效）
            $this->data['Comment']['rank'] = 5;
            $this->data['Comment']['type'] = 'A';
            $this->Comment->save(array('Comment' => $this->data['Comment']));
        }

//		if(empty($_POST['img'])){
//			$_FILES="";
//		}
        //查询该视频的评论
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
        //pr($reply_list);
        //该视频的评论数量
        $comment_num = $this->Comment->find('count', array('conditions' => array('Comment.parent_id' => $_POST['parent_id'], 'Comment.status' => 1)));
        //pr($comment_num);
        $this->set('reply_list', $reply_list);
        $this->set('comment_num', $comment_num);
        $this->set('comment_id', $_POST['parent_id']);
        $parent_id = $this->Comment->find('first', array('conditions' => array('Comment.id' => $_POST['parent_id'])));
        $this->set('comment_name', $parent_id['Comment']['name']);
       // pr($parent_id);
        //pr($reply_;list);	 
        $this->layout = 'ajax';
        $this->render('video_comment');
    }

    public function video_play($id = 0)
    {
        //读取视频
    //	echo $id;exit();
        $video = $this->Article->find('first', array('conditions' => array('Article.id' => $id)));
        $file_path = $_SERVER['DOCUMENT_ROOT'].$video['Article']['upload_video'];
        if (isset($video['Article']['video_competence'])) {
            $video_competence = explode(',', $video['Article']['video_competence']);
        }
        $user_video_type = '';
        if (isset($_SESSION['User']['User']['rank']) && $_SESSION['User']['User']['rank'] == 0) {
            $user_video_type = 2;
        }
        if (isset($video_competence) && in_array($user_video_type, $video_competence) || $video_competence[0] == '') {
            if (is_file($file_path)) {
                //pr($_SERVER['HTTP_USER_AGENT']);die;
                if ($this->isIos()) {
                    header('location: '.$video['Article']['upload_video']);
                } else {
                    header('Content-type: video/mp4');
                    @readfile($this->server_host.$video['Article']['upload_video']);
                }
            }
        }
        exit();
    }
    //判断IOS
    public function isIos($user_agent = null)
    {
        if (!isset($user_agent)) {
            $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        }
        if (strpos($user_agent, 'iPhone') !== false || strpos($user_agent, 'iPad') !== false) {
            $check_ios = true;
        } else {
            $check_ios = false;
        }

        return $check_ios;
    }
    /* 文件下载 */
    public function downloadfile($id, $cat_id = 1)
    {
        $this->layout = null;
        Configure::write('debug', 1);
        $articleInfo = $this->Article->find('first', array('fields' => array('ArticleI18n.title', 'Article.file'), 'conditions' => array('Article.id' => $id, 'Article.status' => '1')));
        if (!empty($articleInfo) && $articleInfo['Article']['file'] != '') {
            $file_name = $articleInfo['Article']['file'];
            $file_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
            $file_path = $file_dir.$file_name;
            if (file_exists($file_path)) {
                $file = fopen($file_path, 'r');//打开文件	
                Header('Content-type: application/octet-stream');
                Header('Accept-Ranges: bytes');
                Header('Accept-Length: '.filesize($file_path));
                Header('Content-Disposition: attachment; filename='.$file_name);
                echo fread($file, filesize($file_dir.$file_name));
                fclose($file);
                exit;
            } else {
                $this->redirect('/articles/category/'.$cat_id);
            }
            die;
        } else {
            $this->redirect('/articles/category/'.$cat_id);
        }
    }
}//end class
