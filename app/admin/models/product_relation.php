<?php

/*****************************************************************************
 * svcms  商品模型
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
class ProductRelation extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product 商品
     */
    public $name = 'ProductRelation';

    /**
     * 取得图片关联图片.
     *
     * @param int $product_id 图片ID
     *
     * @return array 关联图片信息
     */
    public function get_product_relation_product($product_id)
    {
        $product_relation_list = $this->find('all', array('conditions' => array('product_id' => $product_id), 'order' => 'orderby asc'));
        $product_relation_id_array = array();//初始化图片ID数组
        foreach ($product_relation_list as $k => $v) {
            $product_relation_id_array[] = $v['ProductRelation']['related_product_id'];
        }
        $Product = ClassRegistry::init('Product');
        $Product->set_locale($this->locale);
        $ld = ClassRegistry::init('Dictionary');
        $this->ld = $ld->getformatcode($this->locale);
        $product_relation_info = $Product->find('all', array('conditions' => array('Product.id' => $product_relation_id_array), 'fields' => array('Product.id', 'Product.code', 'ProductI18n.name')));//取得关联图片信息
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
