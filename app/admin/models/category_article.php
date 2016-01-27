<?php

/*****************************************************************************
 * svcms  文章分类
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
class CategoryArticle extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name ArticleCategory 关于文章扩展分类的模块
     */
    public $name = 'CategoryArticle';

    /*
     * @var $hasOne array 关联文章分类多语言表//注释download会报错！
     */
    public $hasOne = array('CategoryArticleI18n' => array(
                        'className' => 'CategoryArticleI18n',
                        'order' => '',
                        'dependent' => true,
                        'foreignKey' => 'category_id',
                    ),
                  );

    /*
     * @var $categories_parent_format array 关联类别格式
     */
    public $categories_parent_format = array();

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = '';
        $conditions = " CategoryArticleI18n.locale = '".$locale."'";
        $this->hasOne['CategoryArticleI18n']['conditions'] = $conditions;
    }

    /*
    *获取文章分类结构树
    */
    public function tree($type = 'A', $locale = 'chi', $id = 'all')
    {
        $this->categories_parent_format = array();
        $this->set_locale($locale);
        if ($id != 'all') {
            $conditions['CategoryArticle.id !='] = $id;
        }
        if ($type != 'all') {
            $conditions['CategoryArticle.type'] = $type;
        }
        $conditions['CategoryArticleI18n.locale'] = $locale;
        $cond['conditions'] = $conditions;
        $cond['fields'] = array('CategoryArticle.id','CategoryArticle.parent_id','CategoryArticle.type','CategoryArticle.sub_type','CategoryArticle.orderby','CategoryArticle.status','CategoryArticle.img01','CategoryArticleI18n.name');
        $cond['order'] = array('CategoryArticle.orderby asc,CategoryArticle.created asc');
        $CategoryArticle_list = $this->find('all', $cond);
        if (is_array($CategoryArticle_list)) {
            foreach ($CategoryArticle_list as $k => $v) {
                $this->categories_parent_format[$v['CategoryArticle']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }

    /**
     * subcat_get方法，获得subcat.
     *
     * @param int $category_id 输入id
     *
     * @return array $subcat 根据id检索相对应的数据并返回
     */
    public function subcat_get($category_id)
    {
        $subcat = array();
        if (isset($this->categories_parent_format[$category_id]) && is_array($this->categories_parent_format[$category_id])) {
            //判断parent_id = 0 的数据
            foreach ($this->categories_parent_format[$category_id] as $k => $v) {
                $category = $v; //parent_id 为 0 的数据
                if (isset($this->categories_parent_format[$v['CategoryArticle']['id']]) && is_array($this->categories_parent_format[$v['CategoryArticle']['id']])) {
                    $category['SubCategory'] = $this->subcat_get($v['CategoryArticle']['id']);
                }
                $subcat[$k] = $category;
                $this->all_subcat[$v['CategoryArticle']['id']][] = $v['CategoryArticle']['id'];
                if (isset($this->all_subcat[$v['CategoryArticle']['parent_id']])) {
                    $this->all_subcat[$v['CategoryArticle']['parent_id']] = array_merge($this->all_subcat[$v['CategoryArticle']['parent_id']], $this->all_subcat[$v['CategoryArticle']['id']]);
                } else {
                    $this->all_subcat[$v['CategoryArticle']['parent_id']] = $this->all_subcat[$v['CategoryArticle']['id']];
                }
            }
        }

        return $subcat;
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 输入分类编号
     *
     * @return array $lists_formated 返回文章所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('CategoryArticle.id' => $id)));
        $lists_formated = array();
    //	pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['CategoryArticle'] = $v['CategoryArticle'];
            $lists_formated['CategoryArticleI18n'][] = $v['CategoryArticleI18n'];
            foreach ($lists_formated['CategoryArticleI18n']as $key => $val) {
                $lists_formated['CategoryArticleI18n'][$val['locale']] = $val;
            }
        }
        //pr($lists_formated);
        return $lists_formated;
    }
}
