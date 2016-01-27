<?php

/**
 * 商品类型属性值模型.
 */
class ProductTypeAttribute extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    //var $useDbConfig ='oms';
    public $name = 'ProductTypeAttribute';

    /*
        获取当前属性组关联属性id
    */
    public function getattrids($pro_type_id = 0)
    {
        $attr_ids = array();
        $attr_ids = $this->find('list', array('fields' => array('ProductTypeAttribute.attribute_id'), 'conditions' => array('ProductTypeAttribute.product_type_id' => $pro_type_id), 'order' => 'ProductTypeAttribute.orderby'));

        return $attr_ids;
    }
}
