<?php

/**
 * 商品批发模型  chenfan.
 */
class ProductVolume extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ProductVolume';

    public function getProductVolumes($p_ids)
    {
        $allInfos = $this->find('all', array('conditions' => array('ProductVolume.product_id' => $p_ids), 'order' => 'ProductVolume.volume_number asc'));
        $all = array();
        if (!empty($allInfos)) {
            foreach ($allInfos as $v) {
                $all[$v['ProductVolume']['product_id']][] = $v;
            }
        }

        return $all;
    }
}
