<?php

/*****************************************************************************
 * svoms  商品销售属性模型
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
class SkuProduct extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product 商品
     */
    public $name = 'SkuProduct';

    /*
    * check_sku_pro 方法，检查该商品是否为主商品
    * @param string $code 商品货号
    * @return boolean
    */
    public function check_sku_pro($code)
    {
        $num = $this->find('count', array('conditions' => array('SkuProduct.product_code' => $code)));
        if ($num > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
    * check_sku 方法，检查该商品是否为子商品
    * @param string $code 商品货号
    * @return boolean
    */
    public function check_sku($code)
    {
        $num = $this->find('count', array('conditions' => array('SkuProduct.sku_product_code' => $code)));
        if ($num > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
    * get_pro_code 方法，获取当前商品的主商品货号
    * @param string $code 子商品货号
    * @return $pro_code 主商品货号
    */
    public function get_pro_code($code)
    {
        $pro_code = '';
        $sku_proInfo = $this->find('first', array('fields' => array('SkuProduct.product_code'), 'conditions' => array('SkuProduct.sku_product_code' => $code)));
        if (!empty($sku_proInfo)) {
            $pro_code = $sku_proInfo['SkuProduct']['product_code'];
        }

        return $pro_code;
    }

    /*
    * get_pro_code 方法，获取主商品下子商品Id
    * @param string $code 主商品货号
    * @return pro_code
    */
    public function get_sku_pro_ids($code)
    {
        $lds = array();
        $sku_code_list = $this->find('list', array('fields' => array('SkuProduct.sku_product_code'), 'conditions' => array('SkuProduct.product_code' => $code)));
        $ProductModel = ClassRegistry::init('Product');
        $lds = $ProductModel->find('list', array('conditions' => array('Product.code' => $sku_code_list), 'fields' => array('Product.id')));

        return $lds;
    }

    /*
        添加子商品数据
    */
    public function saveskupro($pro_code, $sku_code_list)
    {
        $ProductModel = ClassRegistry::init('Product');
        $ProductI18nModel = ClassRegistry::init('ProductI18n');
        $proInfo = $ProductModel->find('all', array('conditions' => array('Product.code' => $pro_code)));
        $product_info_formated = array();
        foreach ($proInfo as $k => $v) {
            $v['Product']['promotion_start'] = substr($v['Product']['promotion_start'], 0, 10);
            $v['Product']['promotion_end'] = substr($v['Product']['promotion_end'], 0, 10);
            $product_info_formated['Product'] = $v['Product'];
            $product_info_formated['ProductI18n'][] = $v['ProductI18n'];
        }
        foreach ($product_info_formated['ProductI18n'] as $key => $val) {
            $product_info_formated['ProductI18n'][$val['locale']] = $val;
            unset($product_info_formated['ProductI18n'][$key]);
        }
        $proInfo = $product_info_formated;
        if (!empty($proInfo)) {
            unset($proInfo['Product']['id']);
            foreach ($proInfo['ProductI18n'] as $k => $v) {
                unset($proInfo['ProductI18n'][$k]['id']);
                unset($proInfo['ProductI18n'][$k]['product_id']);
            }
        }

        foreach ($sku_code_list as $k => $v) {
            $sku_pro_code[] = $v['Product']['code'];
        }
        $_sku_pro_code_list = $ProductModel->find('all', array('conditions' => array('Product.code' => $sku_pro_code)));

        foreach ($_sku_pro_code_list as $k => $v) {
            $sku_pro_code_list[strtolower($v['Product']['code'])] = $v;
        }
        foreach ($sku_code_list as $k => $v) {
            $_pro_data = $proInfo;
            if (isset($sku_pro_code_list[$v['Product']['code']]) && !empty($sku_pro_code_list[$v['Product']['code']])) {
                $_pro_data['Product']['id'] = $sku_pro_code_list[$v['Product']['code']]['Product']['id'];
                $ProductI18nModel->deleteAll(array('ProductI18n.product_id' => $sku_pro_code_list[$v['Product']['code']]['Product']['id']));
            }
            $_pro_data['Product']['option_type_id'] = 0;
            $_pro_data['Product']['code'] = $v['Product']['code'];
            $_pro_data['Product']['quantity'] = $v['Product']['quantity'];
            $_pro_data['Product']['shop_price'] = $v['Product']['price'];
            $_pro_data['Product']['alone'] = '0';
            $ProductModel->saveAll($_pro_data['Product']);
            $_product_id = $ProductModel->id;
            foreach ($proInfo['ProductI18n'] as $kk => $vv) {
                $_pro_data['ProductI18n'][$kk]['product_id'] = $_product_id;
                $_pro_data['ProductI18n'][$kk]['name'] = $vv['name'].' '.$v['Product']['attr_val1'].' '.$v['Product']['attr_val2'];
                $ProductI18nModel->saveAll($_pro_data['ProductI18n'][$kk]);
            }
        }
    }
    //是否为销售属性商品
    //$code_arr货号数组
    public function sale_sku_product($code_arr, $locale = 'chi')
    {
        $code = array();
        if (sizeof($code_arr) > 0) {
            foreach ($code_arr as $ck => $cv) {
                $Product = ClassRegistry::init('Product');
                $parent_id = $Product->find('first', array('fields' => array('Product.id', 'Product.code', 'Product.shop_price', 'Product.quantity'), 'conditions' => array('Product.code' => $cv)));
                if (!empty($parent_id)) {
                    $ProductAttribute = ClassRegistry::init('ProductAttribute');
                    $pro_attr = $ProductAttribute->find('all', array('conditions' => array('ProductAttribute.product_id' => $parent_id['Product']['id'], 'ProductAttribute.locale' => $locale), 'order' => 'ProductAttribute.product_type_attribute_id'));
                    $code[$ck]['sku_product']['Product'] = $parent_id['Product'];
                    $code[$ck]['sku_product']['ProductAttribute'] = $pro_attr;
                } else {
                    $code[$ck]['sku_product'] = false;
                }
            }
        }

        return $code;
    }
}
