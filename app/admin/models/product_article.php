<?php

/*****************************************************************************
 * svoms  ��Ʒ����
 * ===========================================================================
 * ��Ȩ����  �Ϻ�ʵ������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�ã�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ===========================================================================
 * $����: �Ϻ�ʵ��$
 * $Id$
*****************************************************************************/
class ProductArticle extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product ��Ʒ
     */
    public $name = 'ProductArticle';

    /**
     * ȡ��ͼƬ��������
     *
     * @param int $product_id ͼƬID
     *
     * @return array ����������Ϣ
     */
    public function get_product_relation_articles($product_id)
    {
        $product_relation_article_list = $this->find('all', array('conditions' => array('product_id' => $product_id), 'order' => array('orderby asc')));
        //��ȡͼƬ�������µ�ID����
        $product_relation_article_id_array = array();
        foreach ($product_relation_article_list as $k => $v) {
            $product_relation_article_id_array[] = $v['ProductArticle']['article_id'];
        }
        $Article = ClassRegistry::init('Article');
        //��ȡ������������
        $Article->set_locale($this->locale);
        $ld = ClassRegistry::init('Dictionary');
        $this->ld = $ld->getformatcode($this->locale);
        $relation_article_data = $Article->find('all', array('conditions' => array('Article.id' => $product_relation_article_id_array, 'Article.status' => 1), 'fields' => array('Article.id', 'ArticleI18n.title')));
        $relation_article_data_format = array();//��ʽ������
        foreach ($relation_article_data as $k => $v) {
            $relation_article_data_format[$v['Article']['id']] = $v;
        }
        //�������ƴ���
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
     * ȡ�����¹���ͼƬ.
     *
     * @param int $article_id ����ID
     *
     * @return array ����ͼƬ��Ϣ
     */
    public function get_article_relation_products($article_id)
    {
        $article_relation_product_list = $this->find('all', array('conditions' => array('article_id' => $article_id), 'order' => array('orderby asc')));
        //��ȡ���¹���ͼƬ��ID����
        $article_relation_product_id_array = array();
        foreach ($article_relation_product_list as $k => $v) {
            $article_relation_product_id_array[] = $v['ProductArticle']['product_id'];
        }
        $ld = ClassRegistry::init('Dictionary');
        $this->ld = $ld->getformatcode($this->locale);
        $Product = ClassRegistry::init('Product');
        //��ȡ����ͼƬ����
        $relation_product_data = $Product->find('all', array('conditions' => array('Product.id' => $article_relation_product_id_array, 'Product.status' => 1), 'fields' => array('Product.id', 'Product.code', 'ProductI18n.name')));
        $relation_product_data_format = array();//��ʽ������
        foreach ($relation_product_data as $k => $v) {
            $relation_product_data_format[$v['Product']['id']] = $v;
        }
        //ͼƬ���ƴ���
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
