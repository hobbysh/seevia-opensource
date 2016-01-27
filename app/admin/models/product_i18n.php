<?php

/*****************************************************************************
 * svoms 商品多语言模型
 * @var $name 用来解决PHP4中的一些奇怪的类名
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
                    'fields' => array('ProductI18n.id', 'ProductI18n.name', 'ProductI18n.product_id'),
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
