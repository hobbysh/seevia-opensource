<?php

/*****************************************************************************
 * svoms  套装商品模型
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
class PackageProduct extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name PackageProduct 套装商品模型
     */
    public $name = 'PackageProduct';

     /**
      * 函数 find_package_product 查询套装商品.
      *
      *@var 主商品的id
      *@var 返回套装商品数组
      */
     public function find_package_product($product_id)
     {
         $package_products = $this->find('all', array('conditions' => array('PackageProduct.product_id' => $product_id), 'order' => 'PackageProduct.orderby asc,PackageProduct.created asc'));

         return $package_products;
     }
}
