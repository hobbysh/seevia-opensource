<?php

/**
 * 商品属性模型.
 */
class ProductAttribute extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product 商品属性
     */
    public $name = 'ProductAttribute';

    /**
     * product_list_format.
     *
     * @param $list_arr
     */
    public function product_list_format($p_ids, $pat_ids, $loc)
    {
        $info = $this->find('all', array('conditions' => array('ProductAttribute.product_id' => $p_ids, 'ProductAttribute.attribute_id' => $pat_ids, 'ProductAttribute.LOCALE' => $loc), 'fields' => 'ProductAttribute.product_id,ProductAttribute.attribute_id,ProductAttribute.attribute_value'));
        $list_arr = array();
        if (!empty($info)) {
            foreach ($info as $v) {
                $list_arr[$v['ProductAttribute']['product_id']][$v['ProductAttribute']['attribute_id']] = $v['ProductAttribute']['attribute_value'];
            }
        }

        return $list_arr;
    }

    public function getAttrInfo($pro_id, $locale = 'chi')
    {
        $data = array();
        $cond['ProductAttribute.product_id'] = $pro_id;
        $cond['ProductAttribute.locale'] = $locale;
        $_data = $this->find('all', array('conditions' => $cond));
        if (!empty($_data)) {
            foreach ($_data as $k => $v) {
                $data[$v['ProductAttribute']['product_id']][$v['ProductAttribute']['attribute_id']] = $v['ProductAttribute']['attribute_value'];
            }
        }

        return $data;
    }
}
