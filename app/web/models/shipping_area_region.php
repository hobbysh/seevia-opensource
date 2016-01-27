<?php

/**
 * 配送区域范围模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 */
class ShippingAreaRegion extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ShippingAreaRegion';

    public function find_shipping_area_region_ids($id)
    {
        $shipping_area_region_ids = $this->find('list', array('conditions' => array('ShippingAreaRegion.region_id' => $id)));

        return $shipping_area_region_ids;
    }

    public function find_shipping_area_regions($id)
    {
        $shipping_area_regions = $this->find('all', array('conditions' => array('ShippingAreaRegion.id' => $id)));

        return $shipping_area_regions;
    }
}
