<?php

/*****************************************************************************
 * svoms  商品文章
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
class ProductArticle extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product 商品
     */
    public $name = 'ProductArticle';

    /**
     * 取得图片关联文章
     *
     * @param int $product_id 图片ID
     *
     * @return array 关联文章信息
     */
    public function get_product_relation_articles($product_id)
    {
        $product_relation_article_list = $this->find('all', array('conditions' => array('product_id' => $product_id), 'order' => array('orderby asc')));
        //获取图片关联文章的ID数组
        $product_relation_article_id_array = array();
        foreach ($product_relation_article_list as $k => $v) {
            $product_relation_article_id_array[] = $v['ProductArticle']['article_id'];
        }
        $Article = ClassRegistry::init('Article');
        //获取关联文章数据
        $Article->set_locale($this->locale);
        $ld = ClassRegistry::init('Dictionary');
        $this->ld = $ld->getformatcode($this->locale);
        $relation_article_data = $Article->find('all', array('conditions' => array('Article.id' => $product_relation_article_id_array, 'Article.status' => 1), 'fields' => array('Article.id', 'ArticleI18n.title')));
        $relation_article_data_format = array();//格式化数组
        foreach ($relation_article_data as $k => $v) {
            $relation_article_data_format[$v['Article']['id']] = $v;
        }
        //文章名称处理
        foreach ($product_relation_article_list as $k => $v) {
            $product_relation_article_list[$k]['ProductArticle']['title'] = '';
            if (isset($relation_article_data_format[$v['ProductArticle']['article_id']]) && is_array($relation_article_data_format[$v['ProductArticle']['article_id']])) {
                $linked_type = $v['ProductArticle']['is_double'] == 0 ? $this->ld['unidirectional'] : $this->ld['each_other_relation'];
                $product_relation_article_list[$k]['ProductArticle']['title'] = $relation_article_data_format[$v['ProductArticle']['article_id']]['ArticleI18n']['title']." -- [$linked_type]";
            } else {
                $product_relation_article_list[$k]['ProductArticle']['title'] = '';
            }
        }

        return $product_relation_article_list;
    }

    /**
     * 取得文章关联图片.
     *
     * @param int $article_id 文章ID
     *
     * @return array 关联图片信息
     */
    public function get_article_relation_products($article_id)
    {
        $article_relation_product_list = $this->find('all', array('conditions' => array('article_id' => $article_id), 'order' => array('orderby asc')));
        //获取文章关联图片的ID数组
        $article_relation_product_id_array = array();
        foreach ($article_relation_product_list as $k => $v) {
            $article_relation_product_id_array[] = $v['ProductArticle']['product_id'];
        }
        $ld = ClassRegistry::init('Dictionary');
        $this->ld = $ld->getformatcode($this->locale);
        $Product = ClassRegistry::init('Product');
        //获取关联图片数据
        $relation_product_data = $Product->find('all', array('conditions' => array('Product.id' => $article_relation_product_id_array, 'Product.status' => 1), 'fields' => array('Product.id', 'Product.code', 'ProductI18n.name')));
        $relation_product_data_format = array();//格式化数组
        foreach ($relation_product_data as $k => $v) {
            $relation_product_data_format[$v['Product']['id']] = $v;
        }
        //图片名称处理
        foreach ($article_relation_product_list as $k => $v) {
            $article_relation_product_list[$k]['ProductArticle']['name'] = '';
            if (isset($relation_product_data_format[$v['ProductArticle']['product_id']]) && is_array($relation_product_data_format[$v['ProductArticle']['product_id']])) {
                $linked_type = $v['ProductArticle']['is_double'] == 0 ? $this->ld['unidirectional'] : $this->ld['each_other_relation'];
                $article_relation_product_list[$k]['ProductArticle']['name'] = $relation_product_data_format[$v['ProductArticle']['product_id']]['Product']['code'].'--'.$relation_product_data_format[$v['ProductArticle']['product_id']]['ProductI18n']['name']." -- [$linked_type]";
            } else {
                $article_relation_product_list[$k]['ProductArticle']['name'] = '';
            }
        }

        return $article_relation_product_list;
    }
}
