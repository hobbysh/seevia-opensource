<?php

/*****************************************************************************
 * svcms  商品模型
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
class product extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product 商品
     */
    public $name = 'Product';

    /*
     * @var $hasOne array 关联商品多语言表//注释download会报错！
     */
    public $hasOne = array('ProductI18n' => array(
                        'className' => 'ProductI18n',
                        'order' => '',
                        'dependent' => true,
                        'foreignKey' => 'product_id',
                    ),

                  );
    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = '';
        $conditions = " ProductI18n.locale = '".$locale."'";
        $this->hasOne['ProductI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，商品数组结构调整.
     *
     * @param int $id 输入商品编号
     *
     * @return array $product_info_formated 返回商品数组
     */
    public function localeformat($id)
    {
        $product_info = $this->find('all', array('conditions' => array('Product.id' => $id)));
        //pr($product_info);
        $product_info_formated = array();
        foreach ($product_info as $k => $v) {
            $v['Product']['promotion_start'] = substr($v['Product']['promotion_start'], 0, 10);
            $v['Product']['promotion_end'] = substr($v['Product']['promotion_end'], 0, 10);
            $product_info_formated['Product'] = $v['Product'];
            $product_info_formated['ProductI18n'][] = $v['ProductI18n'];

            foreach ($product_info_formated['ProductI18n'] as $key => $val) {
                $product_info_formated['ProductI18n'][$val['locale']] = $val;
            }
        }

        return $product_info_formated;
    }

    /**
     * product_count方法，商品数量统计
     *
     * @return array $lists_formated 返回商品所有数量的数据
     */
    public function product_count()
    {
        $this->hasOne = array();
        $this->hasMany = array();
    //	$lists=$this->find("all",array("conditions"=>array("status"=>1,"forsale"=>1),'fields' => array('category_id', 'count(category_id) as count'),"group"=>"category_id"));
        $lists = $this->find('all', array('conditions' => array('status' => 1), 'fields' => array('category_id', 'count(category_id) as count'), 'group' => 'category_id'));
        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['Product']['category_id']] = $v['0']['count'];
            }
        }

        return $lists_formated;
    }

    /**
     * order_product_detail_format_get方法，订单取商品详细.
     *
     * @param array $product_id_array 商品ID数组
     *
     * @return array $lists_formated 返回商品订单取商品详细
     */
    public function order_product_detail_format_get($product_id_array = array())
    {
        $condition = '';
        $condition['id'] = $product_id_array;
        $order_product_detail = $this->find('all', array('conditions' => $condition));
        $order_product_detail_format = array();
        foreach ($order_product_detail as $k => $v) {
            $order_product_detail_format[$v['Product']['id']] = $v;
        }

        return $order_product_detail_format;
    }

    /**
     * product_first_get方法，获取一个商品信息.
     *
     * @param string $product_code 商品货号
     * @param string $locale       语言代码
     *
     * @return array $lists_formated 返回一个商品信息
     */
    public function product_first_get($product_code, $locale)
    {
        $condition = '';
        $condition['Product.code'] = $product_code;
        $condition['ProductI18n.locale'] = $locale;
        $product_first_get = $this->find('first', array('conditions' => $condition, 'fields' => array('Product.id', 'Product.code', 'Product.quantity', 'Product.frozen_quantity', 'Product.shop_price', 'Product.shop_price', 'Product.weight', 'ProductI18n.name')));

        return $product_first_get;
    }

    /**
     * check_code_unique方法，检查货号唯一.
     *
     * @param string $code 商品货号
     *
     * @return bool
     */
    public function check_code_unique($code)
    {
        $num = $this->find('count', array('conditions' => array('Product.code' => $code)));
        if ($num) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * auto_code方法，自动补全.
     *
     * @param string $code 商品货号
     *
     * @return bool
     */
    public function auto_code($code)
    {
        $code = $this->find('first', array('conditions' => array('Product.code like' => "$code%"), 'order' => 'Product.id desc', 'fields' => array('Product.code')));

        if (!empty($code) && strlen($code['Product']['code']) >= 12) {
            return $code['Product']['code'] + 1;
        } else {
            $j = 12 - strlen($code);
            for ($i = 0; $i < $j; ++$i) {
                $code = $code.'0';
            }
            ++$code;

            return $code;
        }
    }

    public function up_under_foz($total_stock, $code)
    {
        $this->updateAll(array('quantity' => $total_stock.'- frozen_quantity'), array('Product.code' => $code));
        $p = $this->find('first', array('conditions' => array('Product.code' => $code)));
        if ($p['Product']['quantity'] > 0) {
            $this->updateAll(array('forsale' => 1), array('Product.code' => $code));
        } else {
            $this->updateAll(array('forsale' => 0), array('Product.code' => $code));
        }
    }

    public function re_quantity($reduce_frozen, $total_stock, $code)
    {
        //套装子商品库存处理
        $PackageProduct = ClassRegistry::init('PackageProduct');
        $package_list = $PackageProduct->find('all', array('conditions' => array('PackageProduct.product_code' => $code)));
        if (!empty($PackageProduct)) {
            foreach ($PackageProduct as $pack_k => $pack_v) {
                $p = $this->find('first', array('conditions' => array('Product.code' => $pack_v['PackageProduct']['package_product_code']), 'fields' => array('Product.id', 'Product.frozen_quantity')));
                $frozen_quantity = $p['Product']['frozen_quantity'] - $reduce_frozen;
                $p['Product']['frozen_quantity'] = $frozen_quantity < 0 ? 0 : $frozen_quantity;
                $this->save(array('Product' => $p['Product']));
                $this->up_under_foz($total_stock, $pack_v['PackageProduct']['package_product_code']);
            }
        }
        $p = $this->find('first', array('conditions' => array('Product.code' => $code), 'fields' => array('Product.id', 'Product.frozen_quantity')));
        $frozen_quantity = $p['Product']['frozen_quantity'] - $reduce_frozen;
        $p['Product']['frozen_quantity'] = $frozen_quantity < 0 ? 0 : $frozen_quantity;
        $this->save(array('Product' => $p['Product']));
        $this->up_under_foz($total_stock, $code);
    }

    //未付款发货
    public function down_quantity($quantity, $code)
    {
        //套装子商品库存处理
        $PackageProduct = ClassRegistry::init('PackageProduct');
        $package_list = $PackageProduct->find('all', array('conditions' => array('PackageProduct.product_code' => $code)));
        if (!empty($package_list)) {
            foreach ($package_list as $pack_k => $pack_v) {
                $p = $this->find('first', array('conditions' => array('Product.code' => $pack_v['PackageProduct']['package_product_code'])));
                $num = $p['Product']['quantity'] - $quantity;
                if ($num > 0) {
                    $this->updateAll(array('forsale' => 1, 'quantity' => $num), array('Product.code' => $pack_v['PackageProduct']['package_product_code']));
                } else {
                    $this->updateAll(array('forsale' => 0, 'quantity' => $num), array('Product.code' => $pack_v['PackageProduct']['package_product_code']));
                }
            }
        }
        $p = $this->find('first', array('conditions' => array('Product.code' => $code)));
        $num = $p['Product']['quantity'] - $quantity;
        if ($num > 0) {
            $this->updateAll(array('forsale' => 1, 'quantity' => $num), array('Product.code' => $code));
        } else {
            $this->updateAll(array('forsale' => 0, 'quantity' => $num), array('Product.code' => $code));
        }
    }

    //未付款 设为未发货
    public function up_quantity($quantity, $code)
    {
        //套装子商品库存处理
        $PackageProduct = ClassRegistry::init('PackageProduct');
        $package_list = $PackageProduct->find('all', array('conditions' => array('PackageProduct.product_code' => $code)));
        if (!empty($package_list)) {
            foreach ($package_list as $pack_k => $pack_v) {
                $p = $this->find('first', array('conditions' => array('Product.code' => $pack_v['PackageProduct']['package_product_code'])));
                $num = $p['Product']['quantity'] + $quantity;
                if ($num > 0) {
                    $this->updateAll(array('forsale' => 1, 'quantity' => $num), array('Product.code' => $pack_v['PackageProduct']['package_product_code']));
                } else {
                    $this->updateAll(array('forsale' => 0, 'quantity' => $num), array('Product.code' => $pack_v['PackageProduct']['package_product_code']));
                }
            }
        }
        $p = $this->find('first', array('conditions' => array('Product.code' => $code)));
        $num = $p['Product']['quantity'] + $quantity;
        if ($num > 0) {
            $this->updateAll(array('forsale' => 1, 'quantity' => $num), array('Product.code' => $code));
        } else {
            $this->updateAll(array('forsale' => 0, 'quantity' => $num), array('Product.code' => $code));
        }
    }

    //取出所有商品id 和 price 的到对应关系
    public function getIdPrices($ids)
    {
        $allInfos = $this->find('all', array('conditions' => array('Product.id' => $ids), 'recursive' => -1));
        $idPrices = '';
        if (!empty($allInfos)) {
            foreach ($allInfos as $v) {
                $idPrices[$v['Product']['id']] = $v['Product']['shop_price'];
            }
        }

        return $idPrices;
    }

    //取出所有商品id 和 quantity 的到对应关系
    public function getIdQuantities($ids)
    {
        $allInfos = $this->find('all', array('conditions' => array('Product.id' => $ids), 'recursive' => -1));
        $idPrices = '';
        if (!empty($allInfos)) {
            foreach ($allInfos as $v) {
                $idPrices[$v['Product']['id']] = $v['Product']['quantity'];
            }
        }

        return $idPrices;
    }

    public function getCodeQuantities($codes)
    {
        $allInfos = $this->find('all', array('conditions' => array('Product.code' => $codes), 'recursive' => -1));
        $codePrices = '';
        if (!empty($allInfos)) {
            foreach ($allInfos as $v) {
                $codePrices[$v['Product']['code']] = $v['Product']['quantity'];
            }
        }

        return $codePrices;
    }

    //取出商品 code name 对应关系
    public function product_code_name($codes)
    {
        $this->set_locale($this->locale);
        $product_code_names = $this->find('all', array('conditions' => array('Product.code' => $codes), 'fields' => array('Product.code', 'Product.id', 'ProductI18n.name')));
        //$product_names = $this->find('list',array('fields' =>array('ProductI18n.product_id','ProductI18n.name')));
        $product = array();
        foreach ($product_code_names as $pck => $pcv) {
            $product[$pcv['Product']['code']] = $pcv['ProductI18n']['name'];
        }

        return $product;
    }

    public function product_cate_count($cat_pids = array())
    {
        $this->hasOne = array();
        $this->hasMany = array();
        $lists = array();
        foreach ($cat_pids as $k => $v) {
            $c = $this->find('first', array('conditions' => array('status' => 1, 'forsale' => 1, 'id' => $v), 'fields' => array('count(*) as count')));
            $lists[$k] = $c['0']['count'];
        }

        return $lists;
    }

    /*
        商品属性
        根据商品Id调整主商品库存
    */
    public function set_product_quantity($pro_sku_code, $pro_ids)
    {
        $proInfo = $this->find('first', array('conditions' => array('Product.code' => $pro_sku_code), 'fields' => array('Product.id')));
        $pro_sku_list = $this->find('list', array('conditions' => array('Product.id' => $pro_ids), 'fields' => array('Product.quantity')));
        if (!empty($pro_sku_list) && !empty($pro_sku_list)) {
            $quantity = 0;
            foreach ($pro_sku_list as $k => $v) {
                $quantity = $quantity + $v;
            }
            $data['quantity'] = $quantity;
            $data['id'] = $proInfo['Product']['id'];
            $this->save(array('Product' => $data));
        }
    }
    /*
        根据商品货号查询结果输出Id数组
    */
    public function get_product_id_by_code($code)
    {
        $product_id = array();
        if (!empty($code) && sizeof($code) > 0) {
            $product_id = $this->find('first', array('conditions' => array('Product.code' => $code), 'fields' => 'Product.id'));
        }

        return $product_id;
    }

    /*
        订单库存修改
    */
    public function updateskupro($pro_code, $quantity, $flag)
    {
        $SkuProduct = ClassRegistry::init('SkuProduct');
        $code_list = $SkuProduct->find('list', array('conditions' => array('SkuProduct.sku_product_code' => $pro_code), 'fields' => array('SkuProduct.product_code')));
        if (!empty($code_list)) {
            $pro_list = $this->find('all', array('conditions' => array('Product.code' => $code_list), 'fields' => array('Product.id', 'Product.quantity')));
            foreach ($pro_list as $v) {
                if ($flag == true) {
                    $data['quantity'] = $v['Product']['quantity'] - $quantity;
                    $data['id'] = $v['Product']['id'];
                } elseif ($flag == false) {
                    $data['quantity'] = $v['Product']['quantity'] + $quantity;
                    $data['id'] = $v['Product']['id'];
                } else {
                    $data['quantity'] = $v['Product']['quantity'];
                    $data['id'] = $v['Product']['id'];
                }
                $this->save(array('Product' => $data));
            }
        }
    }

    public function getOrderProductPriceList($pro_id, $pro_code)
    {
        $idPrices = array();
        $pro_cond['OR']['Product.id'] = $pro_id;
        $pro_cond['OR']['Product.code'] = $pro_code;
        $all_pro_info = $this->find('all', array('fields' => array('Product.id', 'Product.code', 'Product.shop_price'), 'conditions' => $pro_cond, 'recursive' => -1));
        if (!empty($all_pro_info)) {
            $pro_sku_list = array();
            foreach ($all_pro_info as $v) {
                $pro_sku_list[$v['Product']['code']] = $v['Product']['id'];
                $idPrices[$v['Product']['id'].$v['Product']['code']] = $v['Product']['shop_price'];
            }
            $skuPrice = array();
            $SkuProduct = ClassRegistry::init('SkuProduct');
            $sku_cond['SkuProduct.sku_product_code'] = $pro_code;
            $sku_pro_info = $SkuProduct->find('all', array('conditions' => $sku_cond));
            foreach ($sku_pro_info as $v) {
                if (!isset($pro_sku_list[$v['SkuProduct']['product_code']])) {
                    continue;
                }
                $key_str = $pro_sku_list[$v['SkuProduct']['product_code']].$v['SkuProduct']['sku_product_code'];
                $idPrices[$key_str] = $v['SkuProduct']['price'];
            }
        }

        return $idPrices;
    }

    public function getOrderProductPrice($pro_id = 0, $pro_code = '')
    {
        $OrderProductPrice = '0.00';
        $pro_info = $this->find('first', array('conditions' => array('Product.id' => $pro_id)));
        if (!empty($pro_info)) {
            $OrderProductPrice = $pro_info['Product']['shop_price'];
            if ($pro_code != '' && $pro_info['Product']['code'] != $pro_code) {
                $SkuProduct = ClassRegistry::init('SkuProduct');
                $sku_cond['SkuProduct.product_code'] = $pro_info['Product']['code'];
                $sku_cond['SkuProduct.sku_product_code'] = $pro_code;
                $sku_pro_info = $SkuProduct->find('first', array('conditions' => $sku_cond));
                if (!empty($sku_pro_info)) {
                    $OrderProductPrice = $sku_pro_info['SkuProduct']['price'];
                }
            }
        }

        return $OrderProductPrice;
    }
    //判断商品是否为套装
    public function checkProductType($pro_id = 0)
    {
        $option_type_id = 0;
        $pro_info = $this->find('first', array('conditions' => array('Product.id' => $pro_id)));
        if (!empty($pro_info)) {
            $option_type_id = $pro_info['Product']['option_type_id'];

            return $option_type_id;
        }

        return $option_type_id;
    }
}
