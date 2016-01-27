<?php

/* 同样商品购买。
*
*@var $name 用来解决PHP4中的一些奇怪的类名
*/

class ProductAlsobought extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ProductAlsobought';

    public function find_product_alsobought($id)
    {
        $product_alsobought = $this->find('all', array('conditions' => array('OR' => array('ProductAlsobought.product_id' => $id, 'ProductAlsobought.alsobought_product_id' => $id)),
                    'fields' => array('ProductAlsobought.product_id', 'ProductAlsobought.alsobought_product_id'),
                        //	'limit'=>10
                ));

        return $product_alsobought;
    }
}
