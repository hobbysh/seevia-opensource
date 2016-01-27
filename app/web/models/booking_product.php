<?php

/*****************************************************************************
 * Seevia 资金
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
class BookingProduct extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'BookingProduct';
    public $useTable = 'product_bookings';
    public $belongsTo = array(
            'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id', ),
          'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'product_id', ),
        );
    public function get_booking()
    {
        $content = array('fields' => array('BookingProduct.product_id',
                                            'ProductI18n.name',
                                            'ProductI18n.shop_price',
                                            'Product.status',
                                            'BookingProduct.dispose_time', ));
        $bookingAmount = $this->find('all', $content);

        return $bookingAmount;
    }
}
