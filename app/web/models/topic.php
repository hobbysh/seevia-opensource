<?php

/**
 * 专题模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 * @var 设置模型关联
 */
class topic extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    public $name = 'Topic';
    public $hasOne = array('TopicI18n' => array('className' => 'TopicI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'topic_id',
        ),
    );

    //数组结构调整
    /**
     * 函数localeformat 数组结构调整.
     *
     * @param $id 专题号
     * @param $lists 专题列表
     * @param $lists_formated 列表信息
     *
     * @return $lists_formated 列表信息
     */
    public function localeformat($id)
    {
        $lists = $this->findAll("Topic.id = '".$id."'");
        //	pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['Topic'] = $v['Topic'];
            $lists_formated['TopicI18n'][] = $v['TopicI18n'];
            foreach ($lists_formated['TopicI18n'] as $key => $val) {
                $lists_formated['TopicI18n'][$val['locale']] = $val;
            }
        }
        //	pr($lists_formated);
        return $lists_formated;
    }

    /**
     * 函数get_topics 获取主题内容.
     *
     * @param $rownum 限制行数
     * @param $page 页数
     */
    public function get_topics($rownum, $page)
    {
        $topics = $this->find('all', array('cache' => $this->short, 'conditions' => array('1=1'),
                    'order' => 'Topic.created asc',
                    'limit' => $rownum,
                    'page' => $page, ));

        return $topics;
    }

    public function get_one_day_promotions($filter)
    {
        $one_day_promotions = $this->find('all', array('cache' => $this->short, 'conditions' => array($filter),
                    'fields' => array('Topic.id'), ));

        return $one_day_promotions;
    }

    public function find_topics($locale)
    {
        $topics = $this->find('all', array('cache' => $this->short, 'conditions' => array('1=1'),
                    'order' => 'Topic.created DESC', ),
                        'page_home_'.$locale);

        return $topics;
    }

    public function find_topics_fields($locale)
    {
        $topics = $this->find('all', array('cache' => $this->short, 'conditions' => array('1=1'),
                    'order' => 'Topic.created DESC',
                    'fields' => array('Topic.id', 'Topic.modified', 'TopicI18n.title'), ),
                        'all_topic_'.$locale);

        return $topics;
    }

        /*
    * 函数get_module_home_topic 获取专题列表
    * @param $params 查询条件
    * @return $module_topic_infos 专题列表
    */
    public function get_module_home_topic($params)
    {
        $conditions['Topic.status'] = 1;
        $limit = 10;
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['Topic.category_id'] = $params['type_id'];
        }
        if (isset($params['start_time'])) {
            $conditions['Topic.start_time <='] = $params['start_time'];
        }
        if (isset($params['end_time'])) {
            $conditions['Topic.end_time <='] = $params['end_time'];
        }
        $module_topic_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Topic.'.$order));

        return $module_topic_infos;
    }
    /*
    * 函数get_module_infos 获取专题列表
    * @param $params 查询条件
    * @return $module_topic 专题列表
    */
    public function get_module_infos($params)
    {
        $conditions['Topic.status'] = 1;
        $limit = 10;
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'orderby,Topic.created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['Topic.category_id'] = $params['type_id'];
        }
        if (isset($params['start_time'])) {
            $conditions['Topic.start_time <='] = $params['start_time'];
        }
        if (isset($params['end_time'])) {
            $conditions['Topic.end_time >='] = $params['end_time'];
        }
        //分页start
        $total = $this->find('count', array('conditions' => $conditions));
        App::import('Component', 'Paginationmodel');
        $pagM = new PaginationModelComponent();
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'topics','action' => 'index','page' => $page,'limit' => $limit);
        //分页参数
        $options = array('page' => $page,'show' => $limit,'modelClass' => $this->name,'total' => $total);
        //pr($conditions);die;
        $pages = $pagM->init($conditions, $parameters, $options); // Added
        //pr($pages);
        //分页end
        $module_topic_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Topic.'.$order, 'page' => $page));
        $module_topic['topic'] = $module_topic_infos;
        $module_topic['paging'] = $pages;

        return $module_topic;
    }
    /*
    * 函数get_module_topic_product 获取专题关联商品
    * @param $params 查询条件
    * @return $topic_product 返回专题关联商品
    */
    public function get_module_topic_product($params)
    {
        $conditions['Topic.status'] = 1;
        if (isset($params['id'])) {
            $conditions['Topic.id'] = $params['id'];
        }
        if (isset($params['topicInfo'])) {
            $topic_infos = $params['topicInfo'];
        } else {
            $topic_infos = array();
        }
        if (!empty($topic_infos)) {
            //专题编号
            $topic_id = $topic_infos['Topic']['id'];
            //关联商品
            $TopicProduct = ClassRegistry::init('TopicProduct');
            $TopicProduct_list = $TopicProduct->find('all', array('conditions' => array('TopicProduct.topic_id' => $topic_id, 'TopicProduct.status' => '1')));
            $product_list = array();
            if (!empty($TopicProduct_list) && sizeof($TopicProduct_list) > 0) {
                foreach ($TopicProduct_list as $k => $v) {
                    $ProductId_arr[] = $v['TopicProduct']['product_id'];
                }
                $Product = ClassRegistry::init('Product');
                $product_list = $Product->find('all', array('fields' => array('Product.id', 'Product.img_thumb', 'Product.img_detail', 'Product.market_price', 'Product.shop_price', 'ProductI18n.name'), 'conditions' => array('Product.id' => $ProductId_arr, 'Product.status' => '1')));
            }
            $topic_product['TopicProduct'] = $product_list;
        }

        return $topic_product;
    }
    /*
    * 函数get_topic_info 获取专题详细信息
    * @param $params 查询条件
    * @return $topic_infos 专题详细信息
    */
    public function get_topic_info($params)
    {
        $conditions['Topic.status'] = 1;
        if (isset($params['id'])) {
            $conditions['Topic.id'] = $params['id'];
        }
        if (isset($params['topicInfo'])) {
            $topic_infos = $params['topicInfo'];
        } else {
            $topic_infos = array();
        }
        if (!empty($topic_infos)) {
            //专题编号
            $topic_id = $topic_infos['Topic']['id'];
            //关联商品
            $TopicProduct = ClassRegistry::init('TopicProduct');
            $TopicProduct_list = $TopicProduct->find('all', array('conditions' => array('TopicProduct.topic_id' => $topic_id, 'TopicProduct.status' => '1')));
            $product_list = array();
            if (!empty($TopicProduct_list) && sizeof($TopicProduct_list) > 0) {
                foreach ($TopicProduct_list as $k => $v) {
                    $ProductId_arr[] = $v['TopicProduct']['product_id'];
                }
                $Product = ClassRegistry::init('Product');
                $product_list = $Product->find('all', array('fields' => array('Product.id', 'Product.img_thumb', 'Product.img_detail', 'Product.market_price', 'Product.shop_price', 'ProductI18n.name'), 'conditions' => array('Product.id' => $ProductId_arr, 'Product.status' => '1')));
            }
            $topic_infos['TopicProduct'] = $product_list;

            //关联文章
            $TopicArticle = ClassRegistry::init('TopicArticle');
            $TopicArticle_list = $TopicArticle->find('all', array('conditions' => array('TopicArticle.topic_id' => $topic_id)));
            $Article_list = array();
            if (!empty($TopicArticle_list) && sizeof($TopicArticle_list) > 0) {
                foreach ($TopicArticle_list as $k => $v) {
                    $ArticleId_arr[] = $v['TopicArticle']['article_id'];
                }
                $Article = ClassRegistry::init('Article');
                $Article_list = $Article->find('all', array('' => array('Article.id', 'ArticleI18n.title', 'Article.created'), 'conditions' => array('Article.id' => $ArticleId_arr, 'Article.status' => '1')));
            }
            $topic_infos['TopicArticle'] = $Article_list;
        }

        return $topic_infos;
    }
    /*
    * 函数get_module_topic_info 获取专题详细信息
    * @param $params 查询条件
    * @return $module_topic_infos 专题详细信息
    */
    public function get_module_topic_info($params)
    {
        $module_topic_infos = array();
        if (isset($params['topicInfo'])) {
            $module_topic_infos = $params['topicInfo'];
        }

        return $module_topic_infos;
    }
    /*
    * 函数get_module_topic_articles 获取专题关联文章
    * @param $params 查询条件
    * @return $Article_list 返回专题关联文章
    */
    public function get_module_topic_article($params)
    {
        $conditions['Topic.status'] = 1;
        if (isset($params['id'])) {
            $conditions['Topic.id'] = $params['id'];
        }
        if (isset($params['topicInfo'])) {
            $topic_infos = $params['topicInfo'];
        } else {
            $topic_infos = array();
        }
        if (!empty($topic_infos)) {
            //专题编号
            $topic_id = $topic_infos['Topic']['id'];
            //关联文章
            $TopicArticle = ClassRegistry::init('TopicArticle');
            $TopicArticle_list = $TopicArticle->find('all', array('conditions' => array('TopicArticle.topic_id' => $topic_id)));
            $Article_list = array();
            if (!empty($TopicArticle_list) && sizeof($TopicArticle_list) > 0) {
                foreach ($TopicArticle_list as $k => $v) {
                    $ArticleId_arr[] = $v['TopicArticle']['article_id'];
                }
                $Article = ClassRegistry::init('Article');
                $Article_list = $Article->find('all', array('' => array('Article.id', 'ArticleI18n.title', 'Article.created'), 'conditions' => array('Article.id' => $ArticleId_arr, 'Article.status' => '1')));
            }
        }
        if (empty($Article_list)) {
            $Article_list = 1;
        }
        //pr($Article_list);
        return $Article_list;
    }
}
