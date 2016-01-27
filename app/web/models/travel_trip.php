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
class TravelTrip extends AppModel
{
    public $name = 'TravelTrip';
    public $hasMany = array(
        'TravelTripScedule' => array(
            'className' => 'TravelTripScedule',
            'order' => 'TravelTripScedule.orderby',
            'conditions' => array('TravelTripScedule.status' => '1'),
            'dependent' => true,
            'foreignKey' => 'travel_trip_id',
        ),
    );

    //热门线路
    public function get_travel_trip_hots($params)
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
        if (isset($params['trip_id'])) {
            $conditions['TravelTrip.id'] = $params['trip_id'];
        }
        $conditions['TravelTrip.status'] = 1;
        $conditions['TravelTrip.hot'] = 1;
        //$conditions['TravelTrip.recommand'] = 1;
        $trip_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'TravelTrip.'.$order, 'fields' => 'TravelTrip.id,TravelTrip.price,TravelTrip.name,TravelTrip.img01'));

        return $trip_infos;
    }

    //推荐线路
    public function get_travel_trip_recommends($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order']) && trim($params['order']) != '') {
            $order = $params['order'];
        }
        if (isset($params['trip_id'])) {
            $conditions['TravelTrip.id'] = $params['trip_id'];
        }
        $conditions['TravelTrip.status'] = 1;
        $conditions['TravelTrip.recommand'] = 1;
        //$conditions['TravelTrip.recommand'] = 1;
        $trip_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'TravelTrip.'.$order, 'fields' => 'TravelTrip.id,TravelTrip.price,TravelTrip.name,TravelTrip.img01'));

        return $trip_infos;
    }
}
