<?php

/**
 * 购物车模型.
 */
class cart extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Cart 商品表
     */
    public $name = 'Cart';

    public $hasMany = array(
                    'CartProductValue' => array(
                    'className' => 'CartProductValue',
                    'conditions' => '',
                    'order' => 'CartProductValue.id',
                    'fields' => 'CartProductValue.id,CartProductValue.cart_id,CartProductValue.attribute_id,CartProductValue.attribute_value,CartProductValue.attr_price',
                    'dependent' => true,
                    'foreignKey' => 'cart_id',
                ),
    );
}
