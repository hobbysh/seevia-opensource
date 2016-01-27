<?php

/**
 * 文章分类模型.
 */
class ArticleCategory extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name ArticleCategory 文章分类表
     */
    public $name = 'ArticleCategory';

    /**
     * findcountassoc方法，取得id=>count.
     *
     * @return $lists_formated 返回格式列表
     */
    public function findcountassoc()
    {
        $lists = $this->find('all', array('fields' => array('id', 'count(*) as count'), 'group' => 'id'));
        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['ArticleCategory']['id']] = $v['0']['count'];
            }
        }

        return $lists_formated;
    }

    //扩展分类
    /**
     * handle_other_cat方式，扩展分类.
     *
     * @param $article_id 输入文章id
     * @param $cat_list 输入列表
     *
     * @return boolen 返回是否正确
     */
    public function handle_other_cat($article_id, $cat_list)
    {
        //查询现有的扩展分类
        $res = $this->findAll('ArticleCategory.article_id = '.$article_id.'');
        $exist_list = array();
        foreach ($res as $k => $v) {
            $exist_list[$k] = $v['ArticleCategory']['category_id'];
        }
        //删除不再有的分类
        $delete_list = array_diff($exist_list, $cat_list);
        if ($delete_list) {
            $condition = array('ArticleCategory.category_id' => $delete_list, 'ArticleCategory.article_id = '.$article_id.'');
            $this->deleteAll($condition);
        }
        //添加新加的分类
        $add_list = array_diff($cat_list, $exist_list, array(0));
        foreach ($add_list as $k => $cat_id) {
            $other_cat_info = array(
                'product_id' => $product_id,
                'category_id' => $add_list[$k],
            );
            $this->saveAll(array('ArticleCategory' => $other_cat_info));
        }

        return true;
    }

    /**
     * find_indx_all方法，查找所有.
     *
     * @param $category_id 输入类别id
     * @param $locale 输入语言
     *
     * @return article_categorys 返回文章类别
     */
    public function find_indx_all($category_id, $locale)
    {
        $params = array(
            'order' => array('ArticleCategory.modified DESC'),
            'conditions' => array(' ArticleCategory.category_id in ('.$category_id.')'),
        );
        $article_categorys = $this->find('all', $params, $this->name.$locale);

        return $article_categorys;

        //"all",array( "conditions" =>array(" ArticleCategory.category_id in (".$category_id.")"))
    }
    /**
     * 函数get_module_infos方法，获取分类文章列表数据.
     *
     * @param  查询参数集合
     *
     * @return $category_article 根据param，返回分类文章列表数组
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        $c_locale = 'chi';
        if (isset($params['locale'])) {
            $c_locale = $params['locale'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['id'])) {
            $conditions['Article.category_id'] = $params['id'];
        }
        if ($params['type'] == 'module_help_information') {
            $conditions['Article.type'] = 'H';
        }
        $conditions['Article.status'] = 1;
        $Article = ClassRegistry::init('Article');
        //分页start
        $total = $Article->find('count', array('conditions' => $conditions));
        App::import('Component', 'Paginationmodel');
        $pagination = new PaginationModelComponent();

        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'category/'.$params['id'],'page' => $page,'limit' => $limit);
        //分页参数
        $options = array('page' => $page,'show' => $limit,'modelClass' => 'Article','total' => $total);
        //pr($conditions);die;
        $pages = $pagination->init($conditions, $parameters, $options); // Added
        //pr($pages);
        //分页end


        $category_article_infos = $Article->find('all', array('conditions' => $conditions, 'page' => $page, 'limit' => $limit, 'order' => 'Article.'.$order, 'fields' => 'Article.id,Article.category_id,Article.file,Article.created,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.subtitle,ArticleI18n.content,ArticleI18n.meta_description'));
        //pr($category_article_infos);die;
        if (!empty($category_article_infos)) {
            //$reg = "/<[^>]+>(.*)<\/[^>]+>/";
            foreach ($article_infos as $k => $v) {
                $category_article_infos[$k]['ArticleI18n']['des_content'] = $this->cutstr($v['ArticleI18n']['meta_description'], 80);
            }
        }
        $CategoryArticle = ClassRegistry::init('CategoryArticle');
        $category_article['category_name'] = $CategoryArticle->get_articlecategory_name_by_id($params['id'], $c_locale);
        $category_article['category_detail'] = $CategoryArticle->get_articlecategory_detail_by_id($params['id'], $c_locale);
        $category_article['category_article'] = $category_article_infos;
        $category_article['paging'] = $pages;

        return $category_article;
    }
}
