<?php

/*****************************************************************************
 * Seevia 用户
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
class TravelHotelPromotion extends AppModel
{
    public $name = 'TravelHotelPromotion';

    public $belongsTo = array(
        'TravelHotel' => array(
        'className' => 'TravelHotel',
        'foreignKey' => 'travel_hotel_id',
           'conditions' => 'TravelHotelPromotion.travel_hotel_id =TravelHotel.id',
        'order' => '',
        'dependent' => true,
        ),
    );
    public function get_module_infos()
    {
    }
    //获取促销信息
    public function get_travel_hotel_promotions($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['destination_id'])) {
            $conditions['TravelHotel.destination_id'] = $params['destination_id'];
        }
        if ($params['controller'] == 'pages') {
            $conditions['TravelHotelPromotion.home_show'] = 1;
        } elseif ($params['controller'] == 'travel_destinations') {
            $conditions['TravelHotelPromotion.destination_show'] = 1;
        }
        $conditions['TravelHotelPromotion.status'] = 1;
        $conditions['TravelHotelPromotion.recommand'] = 1;
        $conditions['TravelHotelPromotion.start <'] = DateTime;
        $conditions['TravelHotelPromotion.end >'] = DateTime;
        $hotel_promotion_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'TravelHotelPromotion.'.$order, 'fields' => 'TravelHotelPromotion.id,TravelHotelPromotion.name,TravelHotelPromotion.promotion_price,TravelHotelPromotion.travel_hotel_id,TravelHotelPromotion.home_show,TravelHotelPromotion.destination_show'));

        return $hotel_promotion_infos;
    }
}
