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
    public $name = 'ProductAttribute';

    public function find_product_attr($id)
    {
        $product_attr = $this->find('all', array(
                    'fields' => array('ProductAttribute.id', 'ProductAttribute.product_id', 'ProductAttribute.attribute_id', 'ProductAttribute.attribute_value', 'ProductAttribute.attribute_price'),
                    'conditions' => array('ProductAttribute.product_id' => $id), ));

        return $product_attr;
    }

    public function find_product_attr_list($id, $locale = 'chi')
    {
        $product_attr_lists = array();
        $product_attr = $this->find('all', array(
                    'fields' => array('ProductAttribute.id', 'ProductAttribute.product_id', 'ProductAttribute.attribute_id', 'ProductAttribute.attribute_value', 'ProductAttribute.attribute_price'),
                    'conditions' => array('ProductAttribute.product_id' => $id, 'locale' => $locale), ));
        if (isset($product_attr) && sizeof($product_attr) > 0) {
            foreach ($product_attr as $k => $v) {
                $product_attr_lists[$v['ProductAttribute']['product_id']][$v['ProductAttribute']['attribute_id']][$v['ProductAttribute']['attribute_value']] = $v;
            }
        }

        return $product_attr_lists;
    }

    public function find_promotion_product_attribute($id)
    {
        $promotion_product_attribute = $this->find('all', array('conditions' => array('ProductAttribute.product_id' => $id)));

        return $promotion_product_attribute;
    }

    public function find_product_attribute_array_list($product_condition)
    {
        $product_attribute_array_list = $this->find('all', array('conditions' => $product_condition, 'group' => array('ProductAttribute.attribute_value', 'ProductAttribute.attribute_id'), 'fields' => array('ProductAttribute.attribute_value', 'ProductAttribute.attribute_id')));

        return $product_attribute_array_list;
    }

    public function find_all_product_attributes($category_ids, $all_product_attribute_names)
    {
        $all_product_attributes = $this->find('all', array('conditions' => array('Product.category_id' => $category_ids, 'Product.status' => 1, 'Product.forsale' => 1, 'ProductAttribute.attribute_value' => $all_product_attribute_names), 'group' => 'ProductAttribute.attribute_value', 'fields' => array('ProductAttribute.attribute_value', 'ProductAttribute.product_id')));

        return $all_product_attributes;
    }
}
