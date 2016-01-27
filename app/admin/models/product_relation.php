<?php

/*****************************************************************************
 * svcms  ��Ʒģ��
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
class ProductRelation extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product ��Ʒ
     */
    public $name = 'ProductRelation';

    /**
     * ȡ��ͼƬ����ͼƬ.
     *
     * @param int $product_id ͼƬID
     *
     * @return array ����ͼƬ��Ϣ
     */
    public function get_product_relation_product($product_id)
    {
        $product_relation_list = $this->find('all', array('conditions' => array('product_id' => $product_id), 'order' => 'orderby asc'));
        $product_relation_id_array = array();//��ʼ��ͼƬID����
        foreach ($product_relation_list as $k => $v) {
            $product_relation_id_array[] = $v['ProductRelation']['related_product_id'];
        }
        $Product = ClassRegistry::init('Product');
        $Product->set_locale($this->locale);
        $ld = ClassRegistry::init('Dictionary');
        $this->ld = $ld->getformatcode($this->locale);
        $product_relation_info = $Product->find('all', array('conditions' => array('Product.id' => $product_relation_id_array), 'fields' => array('Product.id', 'Product.code', 'ProductI18n.name')));//ȡ�ù���ͼƬ��Ϣ
        $product_relation_info_format = array();
        foreach ($product_relation_info as $k => $v) {
            $product_relation_info_format[$v['Product']['id']] = $v;
        }

        foreach ($product_relation_list as $k => $v) {
            if (!empty($product_relation_info_format[$v['ProductRelation']['related_product_id']]) && is_array($product_relation_info_format[$v['ProductRelation']['related_product_id']])) {
                if (isset($product_relation_info_format[$v['ProductRelation']['related_product_id']]['ProductI18n']) && is_array($product_relation_info_format[$v['ProductRelation']['related_product_id']]['ProductI18n'])) {
                    $product_relation_list[$k]['ProductRelation']['name'] = $product_relation_info_format[$v['ProductRelation']['related_product_id']]['Product']['code'].'--'.$product_relation_info_format[$v['ProductRelation']['related_product_id']]['ProductI18n']['name'];
                }
            }
            if (!empty($product_relation_list[$k]['ProductRelation']['name'])) {
                $linked_type = $v['ProductRelation']['is_double'] == 0 ? $this->ld['unidirectional'] : $this->ld['each_other_relation'];
                $product_relation_list[$k]['ProductRelation']['name'] = @$product_relation_list[$k]['ProductRelation']['name']." -- [$linked_type]";
            }
        }

        return $product_relation_list;
    }
}
