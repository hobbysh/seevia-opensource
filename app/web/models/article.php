<?php

/**
 * 文章模型.
 *
 * @todo 公共函数分离，不需要函数整合，一些函数改用find list
 */
class article extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name Article 关于文章的模块
     */
    public $name = 'Article';
    /*
     * @var $cacheQueries true 缓存是否开启：是。
     */
    public $cacheQueries = true;
    /*
     * @var $cacheAction 1day 缓存时间：1天。
     */
    public $cacheAction = '1 day';
    /*
     * @var $hasOne array 文章的多语言模块
     */

    public $hasOne = array('ArticleI18n' => array('className' => 'ArticleI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'article_id',
        ), /* ,
              'ArticleCategory' =>array
              (
              'className'     => 'ArticleCategory',
              'order'        => '',
              'dependent'    =>  true,
              'foreignKey'   => 'article_id'
              ) */
    );
    /**
     * hot_list方法,热门列表获取.
     *
     * @param $number 输入数字
     * @param $type 输入类型
     *
     * @return $List 返回列表
     */
    public function hot_list($number, $type)
    {
        $List = array();
        $conditions = " status ='1' ";
        if ($type) {
            $conditions .= " AND type=$type ";
        }
        $List = $this->find('all', array('order' => array('Article.clicked desc'),
                    'fields' => array('Article.id',
                        'Article.category_id', 'Article.modified', 'Article.created',
                        'ArticleI18n.title', ),
                    'conditions' => array($conditions), 'limit' => $number, ));

        //	pr($List);
        return $List;
    }

    /**
     * get_list方法，获取列表.
     *
     * @param $articles_id 输入文章id
     * @param $store_id 输入id
     *
     * @return $Lists 返回列表
     *
     * @todo 修改get_list以及相关调用
     */
    public function get_list($articles_id, $store_id = '')
    {
        $Lists = array();
        $conditions = "Article.status ='1'";
        if ($articles_id != '') {
            $conditions .= ' AND Article.id in ('.$articles_id.')';
        }
        if ($store_id != '') {
            $conditions .= " AND Article.store_id='".$store_id."'";
        }
        $Lists = $this->find('all', array('conditions' => array($conditions), 'order' => 'Article.orderby asc', 'fields' => array(
                        'Article.id', 'ArticleI18n.meta_description',
                        'Article.category_id', 'Article.file_url', 'ArticleI18n.title', 'Article.author_email', 'Article.created', 'Article.modified', )));

        return $Lists;
    }

    /**
     * findscroll方法,滚动文章.
     *
     * @param $locale 输入语言
     *
     * @return $article_list 返回文章列表
     */
    public function findscroll($locale = '')
    {
        $conditions = "Article.status ='1' and Article.importance in ('2','3')";
        $article_list = $this->find('all', array('conditions' => array($conditions), 'order' => 'Article.orderby asc', 'fields' => array(
                        'ArticleI18n.title', )));

        return $article_list;
    }

    /**
     * find_home_article方法，查询主要文章.
     *
     * @param $locale 输入语言
     *
     * @return $home_article 返回主要文章
     */
    public function find_home_article($locale)
    {
        $cache_key = md5($this->name.'_'.$locale.'_'.'find_home_article');
        $home_article = cache::read($cache_key);
        if ($home_article) {
            return $home_article;
        } else {
            $home_article = $this->find_home_article('all', array('order' => array('Article.modified DESC'),
                        'conditions' => array('Article.status' => '1',
                            'Article.front' => '1',
                        ),
                        'limit' => 10,
                    ));
            cache::write($cache_key, $home_article);

            return $home_article;
        }
    }

    /**
     * findcountassoc方法,.
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

    /**
     * handle_other_cat方法，扩展分类.
     *
     * @param $article_id 输入文章id
     * @param $cat_list 输入列表信息
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
     * find_indx_all方法，查找所有文章类别.
     *
     * @param $category_id 输入类别id
     * @param $locale 输入语言
     *
     * @return $article_categorys 返回文章类别
     */
    public function find_indx_all($category_id, $locale)
    {
        $params = array('order' => array('ArticleCategory.modified DESC'),
            'conditions' => array(' ArticleCategory.category_id in ('.$category_id.')'),
        );
        $article_categorys = $this->find('all', $params, $this->name.$locale);

        return $article_categorys;

        //"all",array( "conditions" =>array(" ArticleCategory.category_id in (".$category_id.")"))
    }

    /**
     * cutstr方法，.
     *
     * @param $string 输入字符串
     * @param $length 输入长度
     * @param $dot 输入点
     *
     * @return $string      返回字符串
     * @return $strcut.$dot 返回结构
     * @return $strcut      返回结构
     *
     * @todo 标注 这个可能在controller里面没有用到或者统一到app_model
     */
    public function cutstr($string, $length, $dot = ' ...')
    {
        global $charset;
        $oldstr = strlen($string);
        if (strlen($string) <= $length) {
            return $string;
        }

        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
        if (function_exists('mb_substr')) {
            $string = mb_substr($string, 0, $length, 'utf-8');
            $charset = 'utf-8';
        } elseif (function_exists('iconv_substr')) {
            $string = iconv_substr($string, 0, $length, 'utf-8');
            $charset = 'utf-8';
        }
        $strcut = '';
        if (strtolower($charset) == 'utf-8') {
            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    ++$n;
                    ++$noc;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    ++$n;
                }

                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }

            $strcut = substr($string, 0, $n);
        } else {
            for ($i = 0; $i < $length; ++$i) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            }
        }

        $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        if ($oldstr > strlen($strcut)) {
            return $strcut.$dot;
        }

        return $strcut;
    }

    /**
     * sub_str方法.
     *
     * @param $str 输入字符串
     * @param $length 输入长度
     * @param $append 输入追加数据
     *
     * @return $str    返回字符串
     * @return $newstr 返回新的字符串
     *
     * @todo  标注 这个可能在controller里面没有用到
     */
    public function sub_str($str, $length = 0, $append = true)
    {
        $str = trim($str);
        $strlength = strlen($str);

        if ($length == 0 || $length >= $strlength) {
            return $str;
        } elseif ($length < 0) {
            $length = $strlength + $length;
            if ($length < 0) {
                $length = $strlength;
            }
        }

        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, 0, $length, 'utf-8');
        } elseif (function_exists('iconv_substr')) {
            $newstr = iconv_substr($str, 0, $length, 'utf-8');
        } else {
            //$newstr = trim_right(substr($str, 0, $length));
            $newstr = substr($str, 0, $length);
        }

        if ($append && $str != $newstr) {
            $newstr .= '...';
        }

        return $newstr;
    }

    public function find_list_by_cat($condition, $rownum, $page)
    {
        $list_by_cat = $this->find('all', array('conditions' => $condition, 'fields' => array('Article.id'), 'order' => array("Article.$orderby asc"), 'limit' => $rownum, 'page' => $page));

        return $list_by_cat;
    }

    public function find_second_home_article($number)
    {
        $home_article = $this->find('all', array('cache' => $this->short, 'order' => array('Article.orderby ASC,Article.modified DESC'),
                    'fields' => array('Article.id', 'Article.file_url', 'Article.category_id',
                        'ArticleI18n.title', 'ArticleI18n.content',
                    ),
                    'conditions' => array('Article.status' => '1',
                        'Article.front' => '1',
                    ),
                    'limit' => $number,
                ));

        return $home_article;
    }

    public function find_article_infos($a_ids)
    {
        $article_infos = $this->find('all', array('conditions' => array('Article.id' => $a_ids),
                    'fields' => array('ArticleI18n.title', 'Article.id'), ));

        return $article_infos;
    }

    public function get_all_articles($locale)
    {
        $articles = $this->find('all', array('conditions' => array('1=1'),
                    'order' => 'Article.created DESC',
                    'fields' => array('Article.id', 'ArticleI18n.title', 'Article.category_id'), ),
                        'all_articles_'.$locale);

        return $articles;
    }

    public function get_articles_all($ids)
    {
        $arr_articles = $this->find('all', array('order' => 'Article.orderby',
                    'conditions' => array('Article.status' => '1',
                        'Article.category_id' => $ids, ),
                    'fields' => array('Article.id', 'Article.file_url', 'ArticleI18n.title', 'Article.category_id'), ));

        return $arr_articles;
    }
    //获取模块文章
    public function get_module_articles($conditions, $limit, $order)
    {
        $article_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Article.'.$order, 'fields' => 'Article.id,Article.category_id,Article.created,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.content,ArticleI18n.meta_description'));
        if (!empty($article_infos)) {
            //$reg = "/<[^>]+>(.*)<\/[^>]+>/";
            foreach ($article_infos as $k => $v) {
                $article_infos[$k]['ArticleI18n']['des_content'] = $this->cutstr($v['ArticleI18n']['meta_description'], 80);
            }
        }

        return $article_infos;
    }
    /**
     * 函数get_module_infos方法，获取模块文章列表数据.
     *
     * @param  查询参数集合
     *
     * @return $article_infos 根据param，返回文章列表数组
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['ControllerObj'])) {
            if (isset($params['ControllerObj']->configs['article_category_page_list_number'])) {
                $limit = $params['ControllerObj']->configs['article_category_page_list_number'];
            }
        }
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['cat_id'])) {
            $conditions['Article.category_id'] = $params['cat_id'];
        }
        if ($params['type'] == 'module_help_information') {
            $conditions['Article.type'] = 'H';
        }
        $conditions['Article.status'] = 1;
        //分页start
        $total = $this->find('count', array('conditions' => $conditions));
        App::import('Component', 'Paginationmodel');
        $pagination = new PaginationModelComponent();

        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'index','page' => $page,'limit' => $limit);
        //分页参数
        $options = array('page' => $page,'show' => $limit,'modelClass' => $this->name,'total' => $total);
        //pr($conditions);die;
        $pages = $pagination->init($conditions, $parameters, $options); // Added
        //pr($pages);
        //分页end
        $article_infos = $this->find('all', array('conditions' => $conditions, 'page' => $page, 'limit' => $limit, 'order' => 'Article.'.$order, 'fields' => 'Article.id,Article.category_id,Article.created,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.subtitle,ArticleI18n.content,ArticleI18n.meta_description'));
        if (!empty($article_infos)) {
            //$reg = "/<[^>]+>(.*)<\/[^>]+>/";
            foreach ($article_infos as $k => $v) {
                $article_infos[$k]['ArticleI18n']['des_content'] = $this->cutstr($v['ArticleI18n']['meta_description'], 80);
            }
        }
        $article_infos['article'] = $article_infos;
        $article_infos['paging'] = $pages;

        return $article_infos;
    }
    /**
     * 函数get_module_article_video 获取文章视频.
     *
     * @param  参数集合
     *
     * @return $video_infos 返回文章视频信息
     */
    public function get_module_article_video($params)
    {
        $conditions = '';
        $limit = 1;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['id'])) {
            $conditions['Article.id'] = $params['id'];
        }
        $conditions['Article.type'] = 'V';
        $conditions['Article.status'] = 1;
        $video_infos = $this->find('first', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Article.'.$order, 'fields' => 'Article.id,Article.video,Article.upload_video,Article.video_competence,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.content,ArticleI18n.meta_description'));

        return $video_infos;
    }
    /**
     * 函数get_module_homearticle 获取首页文章轮播.
     *
     * @param  参数集合
     *
     * @return $module_flash_infos 返回文章轮播信息
     */
    public function get_module_homearticle($params)
    {
        $conditions = '';
        $limit = 1;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['flash_type'])) {
            $conditions['Flash.page'] = $params['flash_type'];
        } else {
            $conditions['Flash.page'] = 'AC';
        }
        if (isset($params['flash_type_id'])) {
            $conditions['Flash.page_id'] = $params['flash_type_id'];
        } elseif (isset($params['type_id'])) {
            $conditions['Flash.page_id'] = $params['type_id'];
        }
        $Flash = ClassRegistry::init('Flash');
        $conditions['Flash.type'] = '0';
        $module_flash_infos = $Flash->find('first', array('conditions' => $conditions, 'fields' => array('Flash.width', 'Flash.height', 'Flash.page_id')));

        return $module_flash_infos;
    }
    /**
     * 函数get_module_relation_video 获取文章相关视频.
     *
     * @param  参数集合
     *
     * @return $video_infos 返回文章相关视频信息
     */
    public function get_module_relation_video($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['id'])) {
            $conditions['Article.id'] = $params['id'];
        }
        $conditions['Article.type'] = 'V';
        $conditions['Article.status'] = 1;
        $video = $this->find('first', array('conditions' => $conditions, 'fields' => 'Article.id,Article.video,Article.video_competence,Article.category_id,ArticleI18n.title'));
        //查询相同分类的视频
        $conditions['Article.category_id'] = $video['Article']['category_id'];
        unset($conditions['Article.id']);
        $relation_video = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Article.'.$order, 'fields' => 'Article.id,Article.video,ArticleI18n.img01,Article.category_id,ArticleI18n.title,ArticleI18n.subtitle'));
        //查询相同分类的视频
        return $relation_video;
    }
    /**
     * 函数get_module_video_comment方法，获取模块视频评论数据.
     *
     * @param  查询参数集合
     *
     * @return $comment_infos 根据param，返回视频评论数组
     */
    public function get_module_video_comment($params)
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
        $order = 'created desc';
        if (isset($params['order'])) {
            $order = 'created desc';
        }
        if (isset($params['id'])) {
            $conditions['Comment.type_id'] = $params['id'];
        }
        $conditions['Comment.type'] = 'A';
        $conditions['Comment.parent_id'] = 0;
        $conditions['Comment.status'] = 1;
        if (isset($params['id'])) {
            $ProductComment = ClassRegistry::init('Comment');
            $comment_infos['comment'] = $ProductComment->find('all', array('conditions' => $conditions, 'page' => $page, 'limit' => $limit, 'order' => 'Comment.'.$order, 'fields' => 'Comment.id,Comment.type_id,Comment.title,Comment.parent_id,Comment.user_id,Comment.img,Comment.content,Comment.status,Comment.user_id,Comment.created'));
            //拼装用户信息
            if (!empty($comment_infos)) {
                $User = ClassRegistry::init('User');
                foreach ($comment_infos['comment'] as $k => $v) {
                    $user_info = $User->find('first', array('conditions' => array('User.id' => $v['Comment']['user_id']), 'fields' => 'User.id,User.name,User.img01'));
                    $comment_infos['comment'][$k]['User'] = $user_info['User'];
                    $comment_infos['comment'][$k]['Reply'] = $ProductComment->find('all', array('conditions' => array('Comment.parent_id' => $v['Comment']['id']), 'order' => 'created desc'));
                    if (!empty($comment_infos['comment'][$k]['Reply'])) {
                        foreach ($comment_infos['comment'][$k]['Reply'] as $kk => $vv) {
                            $user_reply = $User->find('first', array('conditions' => array('User.id' => $vv['Comment']['user_id']), 'fields' => 'User.id,User.name,User.img01'));
                            $comment_infos['comment'][$k]['Reply'][$kk]['User'] = $user_reply['User'];
                        }
                    }
                }
            }
            //表情数组
            $Expression = array('/微笑','/撇嘴','/好色','/发呆','/得意','/流泪','/害羞','/睡觉','/尴尬','/呲牙','/惊讶','/冷汗','/抓狂','/偷笑','/可爱','/傲慢','/犯困','/流汗','/大兵','/咒骂','/折磨/','/衰','/擦汗','/抠鼻','/鼓掌','/坏笑','/左哼哼','/右哼哼','/鄙视','/委屈','/阴险','/亲亲','/可怜','/爱情','/飞吻','/怄火','/回头','/献吻','/左太极');
            $comment_infos['expression'] = $Expression;
            $BlockWord = ClassRegistry::init('BlockWord');
            $word = $BlockWord->find('all');
            $comment_infos['word'] = $word;
            $comment_infos['product_id'] = $params['id'];
            if (empty($comment_infos)) {
                $comment_infos = 1;
            }

            return $comment_infos;
        }
    }
    /**
     * 函数get_module_home_article 获取首页推荐的文章.
     *
     * @param  参数集合
     *
     * @return $article_infos 返回首页推荐文章信息
     */
    public function get_module_home_article($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['Article.category_id'] = $params['type_id'];
        }
        /*$conditions['Article.recommand'] = 1;*/
        $conditions['Article.status'] = 1;
        $conditions['Article.front'] = 1;
        $article_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Article.'.$order, 'fields' => 'Article.id,Article.category_id,Article.created,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.content,ArticleI18n.meta_description'));
        if (!empty($article_infos)) {
            foreach ($article_infos as $k => $v) {
                $article_infos[$k]['ArticleI18n']['des_content'] = $this->cutstr($v['ArticleI18n']['meta_description'], 80);
            }
        }

        return $article_infos;
    }
    /**
     * 函数get_module_video_recommend 获取推荐视频.
     *
     * @param  参数集合
     *
     * @return $video_list 返回推荐视频
     */
    public function get_module_video_recommend($params)
    {
        $conditions = '';
        $limit = 4;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created desc';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        $conditions['Article.type'] = 'V';
        $conditions['Article.status'] = '1';
        $conditions['Article.recommand'] = '1';
        $video_list = $this->find('all', array('fields' => array('Article.id', 'Article.category_id', 'Article.created', 'ArticleI18n.title', 'ArticleI18n.img01', 'ArticleI18n.subtitle'), 'conditions' => $conditions, 'order' => $order, 'limit' => $limit));

        return $video_list;
    }
    /**
     * 函数get_recommand_article_infos 获取每个分类的推荐的文章.
     *
     * @param  参数集合
     *
     * @return $article_infos 返回推荐文章信息
     */
    public function get_recommand_article_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['Article.category_id'] = $params['type_id'];
        }
        $conditions['Article.recommand'] = 1;
        $conditions['Article.status'] = 1;
        $article_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Article.'.$order, 'fields' => 'Article.id,Article.category_id,Article.created,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.content,ArticleI18n.meta_description'));
        if (!empty($article_infos)) {
            foreach ($article_infos as $k => $v) {
                $article_infos[$k]['ArticleI18n']['des_content'] = $this->cutstr($v['ArticleI18n']['meta_description'], 80);
            }
        }

        return $article_infos;
    }

    public function relation_articles($id)
    {
        $article_infos = $this->find('all', array('conditions' => array('Article.id' => $id), 'fields' => 'Article.id,ArticleI18n.title'));
        $relation_infos = array();
        if (!empty($article_infos)) {
            foreach ($article_infos as $a) {
                $relation_infos[$a['Article']['id']] = $a['ArticleI18n']['title'];
            }
        }

        return $relation_infos;
    }

    /*
    *获取文章列表
    */
    public function get_article_lists($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created desc';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        if (isset($params['id'])) {
            $conditions['Article.category_id'] = $params['id'];
        }
        $conditions['Article.status'] = '1';

        //分页start
        $total = $this->find('count', array('conditions' => $conditions));
        App::import('Component', 'Paginationmodel');
        $pagM = new PaginationModelComponent();

        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'category','page' => $page,'limit' => $limit);
        //分页参数
        $options = array('page' => $page,'show' => $limit,'modelClass' => $this->name,'total' => $total);
        //pr($conditions);die;
        $pages = $pagM->init($conditions, $parameters, $options); // Added
        //pr($pages);
        //分页end
        $article_lists = $this->find('all', array('fields' => array('Article.id', 'Article.created', 'ArticleI18n.title', 'ArticleI18n.subtitle', 'ArticleI18n.content', 'ArticleI18n.author', 'ArticleI18n.img01', 'ArticleI18n.meta_description'), 'conditions' => $conditions, 'order' => $order, 'page' => $page, 'limit' => $limit));
        $article_infos['article_list'] = $article_lists;
        $article_infos['paging'] = $pages;

        return $article_infos;
    }

    /**
     * 函数get_module_article_infos 获取文章详情.
     *
     * @param  参数集合
     *
     * @return $article_infos 返回文章详情
     */
    public function get_module_article_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created desc';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        if (isset($params['id'])) {
            $conditions['Article.id'] = $params['id'];
        }
        $conditions['Article.status'] = '1';
        $article_infos = $this->find('first', array('fields' => array('Article.id', 'Article.category_id', 'Article.created', 'ArticleI18n.title', 'ArticleI18n.subtitle', 'ArticleI18n.content', 'ArticleI18n.author', 'ArticleI18n.img01'), 'conditions' => $conditions, 'order' => $order, 'page' => $page, 'limit' => $limit));
        if (!empty($article_infos)) {
            //查询文章相册
            $ArticleGallery = ClassRegistry::init('ArticleGallery');
            $ArticleGallery_infos = $ArticleGallery->find('all', array('conditions' => array('ArticleGallery.article_id' => $article_infos['Article']['id'], 'ArticleGallery.status' => 1)));
            if (!empty($ArticleGallery_infos)) {
                $article_infos['ArticleGallery'] = $ArticleGallery_infos;
            }
        }

        return $article_infos;
    }
    /**
     * 函数get_module_article_recommend 获取推荐文章.
     *
     * @param  参数集合
     *
     * @return $article_list 返回推荐文章
     */
    public function get_module_article_recommend($params)
    {
        $conditions = '';
        $limit = 4;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created desc';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        $conditions['Article.status'] = '1';
        $conditions['Article.recommand'] = '1';
        $article_list = $this->find('all', array('fields' => array('Article.id', 'Article.category_id', 'Article.created', 'ArticleI18n.title', 'ArticleI18n.img01', 'ArticleI18n.subtitle', 'ArticleI18n.meta_description'), 'conditions' => $conditions, 'order' => $order, 'limit' => $limit));

        return $article_list;
    }
    /**
     * 函数get_module_article_comment，获取模块文章评论数据.
     *
     * @params  查询参数集合
     *
     * @return $comment_infos 根据param，返回评论内容数组
     */
    public function get_module_article_comment($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'orderby';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['id'])) {
            $conditions['Comment.type_id'] = $params['id'];
        }
        $conditions['Comment.type'] = 'A';
        $conditions['Comment.parent_id'] = 0;
        $conditions['Comment.status'] = 1;
        $ArticleComment = ClassRegistry::init('Comment');
        $comment_infos = $ArticleComment->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Comment.'.$order, 'fields' => 'Comment.id,Comment.type_id,Comment.title,Comment.parent_id,Comment.name,Comment.content,Comment.status,Comment.created'));
        //拼装用户信息
        if (!empty($comment_infos)) {
            foreach ($comment_infos as $k => $v) {
                //$video_comments[$k]['User']=$this->User->find("first",array("conditions"=>array("User.id"=>$v['Comment']['user_id'])));
            $comment_infos[$k]['Reply'] = $ArticleComment->find('all', array('conditions' => array('Comment.parent_id' => $v['Comment']['id']), 'order' => 'created desc'));
            /*if(!empty($comment_infos[$k]['Reply'])){
                foreach($comment_infos[$k]['Reply'] as $kk=>$vv){
                    $comment_infos[$k]['Reply'][$kk]['User']=$this->User->find("first",array("conditions"=>array("User.id"=>$vv['Comment']['user_id'])));
                }
            }*/
            }
        }
        if (empty($comment_infos)) {
            $comment_infos = 1;
        }

        return $comment_infos;
    }
}
