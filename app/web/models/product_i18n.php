<?php

/**
 * 商品多语言模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 */
class ProductI18n extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ProductI18n';

    public function get_productI18ns($product_conditions)
    {
        $productI18ns = $this->find('all', array(
                    'fields' => array('ProductI18n.id', 'ProductI18n.name', 'ProductI18n.product_id'),
                    'conditions' => $product_conditions, ));

        return $productI18ns;
    }

    public function find_productI18ns($products_ids_list, $locale)
    {
        $productI18ns = $this->find('all', array('cache' => $this->short,
                    'fields' => array('ProductI18n.id', 'ProductI18n.name', 'ProductI18n.seller_note', 'ProductI18n.product_id'),
                    'conditions' => array('ProductI18n.product_id' => $products_ids_list, 'ProductI18n.locale' => $locale), ), 'ProductI18n_'.$locale);
        $productI18ns_list = array();
        foreach ($productI18ns as $k => $v) {
            $productI18ns_list[$v['ProductI18n']['product_id']] = $v;
        }

        return $productI18ns_list;
    }

    public function find_product18n_pid($condition_p18n)
    {
        $product18n_pid = $this->find('list', array('conditions' => array($condition_p18n), 'fields' => array('ProductI18n.product_id')));

        return $product18n_pid;
    }
}
