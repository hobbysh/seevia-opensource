<?php

/**
 * 商品地区价模型.
 */
class ProductLocalePrice extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ProductLocalePrice';

    public function get_locale_price($locale_price_conditions)
    {
        $locale_price = $this->find('all', array(
                    'fields' => array('ProductLocalePrice.product_price', 'ProductLocalePrice.product_id'),
                    'conditions' => $locale_price_conditions, ));

        return $locale_price;
    }

    public function find_locale_price($products_ids_list, $locale)
    {
        $locale_price = $this->find('all', array(
                    'fields' => array('ProductLocalePrice.product_price',
                        'ProductLocalePrice.product_id', ), 'conditions' => array('ProductLocalePrice.product_id' => $products_ids_list, 'ProductLocalePrice.locale' => $locale, 'ProductLocalePrice.status' => 1), ));

        return $locale_price;
    }

    public function find_product_price($id, $locale)
    {
        $product_price = $this->find('ProductLocalePrice.product_id ='.$id." and ProductLocalePrice.status = '1' and ProductLocalePrice.locale = '".$locale."'");

        return $product_price;
    }
}
