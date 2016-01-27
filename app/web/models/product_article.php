<?php

/**
 * 商品相关文章模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 * @var 商品关联
 */
class ProductArticle extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ProductArticle';
    public $belongsTo = array(
        'Article' => array(
            'className' => 'Article',
            'foreignKey' => 'article_id',
        ),
    );

    /**
     * 函数find_product_article 商品详细.
     *
     * @param $id 商品号
     * @param $locale 商品语言
     * @param $params 商品排列
     * @param $article_categorys 商品分类
     *
     * @return $article_categorys 商品分类后的信息
     */
    public function find_product_article($id, $locale)
    {
        $params = array('order' => array('ProductArticle.modified DESC'),
            //	'fields' =>	array('ProductArticle.id','ProductArticle.article_id','ProductArticle.product_id','ProductArticle.is_double'
            //					,'Article.id','Article.category_id'),
            'conditions' => array(' ProductArticle.article_id= '.$id),
        );
        $article_categorys = $this->find('all', $params, $this->name.$locale);
        //	pr($article_categorys);
        return $article_categorys;
    }
}
