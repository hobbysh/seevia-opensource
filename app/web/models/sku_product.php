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

    //是否为销售属性商品
    //$code_arr货号数组
    public function sale_sku_product($code_arr)
    {
        $code = array();
        if (sizeof($code_arr) > 0) {
            $code_list = array();
            foreach ($code_arr as $k => $v) {
                foreach ($v as $vv) {
                    $code_list[$vv] = $vv;
                }
            }
            $Product = ClassRegistry::init('Product');
            $fields = array('Product.id','Product.code','Product.shop_price','Product.quantity','ProductI18n.name');
            $sku_pro_info = $Product->find('all', array('fields' => $fields, 'conditions' => array('Product.code' => $code_list)));
            foreach ($code_arr as $ck => $cv) {
                foreach ($cv as $cv_vv) {
                    foreach ($sku_pro_info as $kk => $vv) {
                        if ($cv_vv == $vv['Product']['code']) {
                            $code[$ck][$cv_vv]['sku_product'] = $vv;
                        }
                    }
                }
            }
        }
        return $code;
    }
    
    function sku_price_range($product_code=""){
    		$conditions="";
    		if(!empty($product_code)){
    			$conditions['SkuProduct.product_code']=$product_code;
    		}
    		$sku_info=$this->find('all',array('conditions'=>$conditions,'fields'=>array("product_code","Min(price) as min_price","Max(price) as max_price"),'group'=>'product_code'));
    		$sku_price_data=array();
    		foreach($sku_info as $v){
    			if($v[0]['min_price']!=$v[0]['max_price']){
    				$sku_price_data[$v['SkuProduct']['product_code']]=$v[0];
    			}
    		}
    		return $sku_price_data;
    }
}
