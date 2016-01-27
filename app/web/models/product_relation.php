<?php

/**
 * 商品关联模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 * @var 设置属性
 */
class ProductRelation extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ProductRelation';
    public $belongsTo = array(
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'related_product_id',
        ),
    );
    /*     * 获取同级分类商品 */

    public function get_related_categories($id)
    {
        $related_categories = $this->find('list', array('fields' => array('ProductRelation.related_product_id'),
                    'conditions' => array('ProductRelation.product_id' => $id), ));

        return $related_categories;
    }

    public function find_relation_ids($conditions)
    {
        $relation_ids = $this->find('all', array('fields' => array('ProductRelation.product_id', 'ProductRelation.related_product_id'), 'conditions' => $conditions, 'recursive' => '1', 'order' => 'ProductRelation.orderby'));

        return $relation_ids;
    }
}
