<?php

/**
 * 商品属性组、属性关联模型.
 */
class ProductTypeAttribute extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name ProductTypeAttribute 商品类型属性模型
     */
    public $name = 'ProductTypeAttribute';

    /**
     * get_attr_count_array方法，计算各种属性的个数.
     *
     * @param int $product_type_id 类型ID
     *
     * @return array $product_type_attribute_group_format 返回计算各种属性的个数
     */
    public function get_attr_count_array($product_type_id = array())
    {
        $this->hasOne = array();
        $product_type_attribute_group = $this->find('all', array('conditions' => array('product_type_id' => $product_type_id), 'group' => 'product_type_id', 'fields' => array('count(id) as num,product_type_id')));
        $product_type_attribute_group_format = array();
        foreach ($product_type_attribute_group as $k => $v) {
            $product_type_attribute_group_format[$v['ProductTypeAttribute']['product_type_id']] = $v['0']['num'];
        }

        return $product_type_attribute_group_format;
    }

    /*
    	获取当前属性组关联属性id
    */
    public function getattrids($pro_type_id = 0)
    {
        $attr_ids = array();
        $attr_ids = $this->find('list', array('fields' => array('ProductTypeAttribute.attribute_id'), 'conditions' => array('ProductTypeAttribute.product_type_id' => $pro_type_id), 'order' => 'ProductTypeAttribute.id'));

        return $attr_ids;
    }

    /*
    	获取当前属性组关联属性信息
    */
    public function get_associated_attributes($pro_type_id = 0, $locale = 'chi')
    {
        $all_attr_list = array();
        $Attribute = ClassRegistry::init('Attribute');
        $Attribute->set_locale($locale);
        $attr_ids = $this->getattrids($pro_type_id);
        if (!empty($attr_ids)) {
            $all_attr_list = $Attribute->find('all', array('conditions' => array('Attribute.status' => 1, 'Attribute.id' => $attr_ids)));
        }

        return $all_attr_list;
    }
}
