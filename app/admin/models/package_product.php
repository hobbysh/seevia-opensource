<?php

/*****************************************************************************
 * svoms  ��װ��Ʒģ��
 * ===========================================================================
 * ��Ȩ����  �Ϻ�ʵ������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�ã�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ===========================================================================
 * $����: �Ϻ�ʵ��$
 * $Id$
*****************************************************************************/
class PackageProduct extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name PackageProduct ��װ��Ʒģ��
     */
    public $name = 'PackageProduct';

     /**
      * ���� find_package_product ��ѯ��װ��Ʒ.
      *
      *@var ����Ʒ��id
      *@var ������װ��Ʒ����
      */
     public function find_package_product($product_id)
     {
         $package_products = $this->find('all', array('conditions' => array('PackageProduct.product_id' => $product_id), 'order' => 'PackageProduct.orderby asc,PackageProduct.created asc'));

         return $package_products;
     }
}
