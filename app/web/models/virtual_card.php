<?php

/**
 * 虚拟卡模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 */
class VirtualCard extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'VirtualCard';

    public function find_virtual_card_by_id($id, $number)
    {
        $VirtualCards = $this->VirtualCard->cache_find('all', array('conditions' => array("VirtualCard.product_id='".$id."' and VirtualCard.order_id='0'"), 'limit' => $number)); //标注

        return $VirtualCards;
    }
}
