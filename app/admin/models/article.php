<?php

/*****************************************************************************
 * svcms  文章模型
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
     * @var $hasOne array 文章的多语言模块
     */
    public $hasOne = array(
            'ArticleI18n' => array('className' => 'ArticleI18n',
                                    'conditions' => '',
                                    'order' => '',
                                    'dependent' => true,
                                    'foreignKey' => 'article_id',
            ),
    );

    public $belongsTo = array(
        'Document' => array(
        'className' => 'Document',
        'foreignKey' => 'upload_file_id',
           'conditions' => 'Article.upload_file_id=Document.id',
        'order' => '',
        'dependent' => true,
        ),
           );
    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale 语言代码
     */
    public function set_locale($locale)
    {
        $conditions = " ArticleI18n.locale = '".$locale."'";
        $this->hasOne['ArticleI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 输入文章编号
     *
     * @return array $lists_formated 返回文章所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Article.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Article'] = $v['Article'];
            $lists_formated['ArticleI18n'][] = $v['ArticleI18n'];
            $lists_formated['Document'] = $v['Document'];
            foreach ($lists_formated['ArticleI18n'] as $key => $val) {
                $lists_formated['ArticleI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
    public function article_counts()
    {
        $this->hasOne = array();
        $this->hasMany = array();
        $lists = $this->find('all', array('conditions' => array('status' => 1), 'fields' => array('category_id', 'count(category_id) as count'), 'group' => 'category_id'));

        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['Article']['category_id']] = $v['0']['count'];
            }
        }
        //	pr($lists_formated);
        return $lists_formated;
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

    /**
     * get_module_articles方法，获取模块文章.
     *
     * @param  $conditions 查询条件
     * @param $limit 查询数量
     * @param $order 查询排序
     *
     * @return $article_infos 文章内容
     */
    public function get_module_articles($conditions, $limit, $order)
    {
        $article_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Article.'.$order, 'fields' => 'Article.id,Article.category_id,Article.created,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.content,ArticleI18n.meta_description,Article.file_url'));
        if (!empty($article_infos)) {
            //$reg = "/<[^>]+>(.*)<\/[^>]+>/";
            foreach ($article_infos as $k => $v) {
                $article_infos[$k]['ArticleI18n']['des_content'] = $this->cutstr($v['ArticleI18n']['meta_description'], 80);
            }
        }

        return $article_infos;
    }
}
