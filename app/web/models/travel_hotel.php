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
class TravelHotel extends AppModel
{
    public $name = 'TravelHotel';
    public $hasMany = array(
        'TravelHotelPromotion' => array(
            'conditions' => 'TravelHotelPromotion.status=1',
            'className' => 'TravelHotelPromotion',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'travel_hotel_id',
        ),
        'TravelHotelRoom' => array(
            'conditions' => 'TravelHotelRoom.status=1',
            'className' => 'TravelHotelRoom',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'travel_hotel_id',
        ),
        'TravelGallary' => array(
            'className' => 'TravelGallary',
            'conditions' => 'TravelGallary.type = "H"',
            'order' => 'TravelGallary.orderby',
            'dependent' => true,
            'foreignKey' => 'type_id',
        ),
    );
    public $belongsTo = array(
        'TravelDestination' => array(
        'className' => 'TravelDestination',
        'foreignKey' => 'destination_id',
           'conditions' => 'TravelHotel.destination_id=TravelDestination.id',
        'order' => '',
        'dependent' => true,
        ),
        'TravelDestinationArea' => array(
        'className' => 'TravelDestinationArea',
        'foreignKey' => 'destination_area_id',
           'conditions' => 'TravelHotel.destination_area_id=TravelDestinationArea.id',
        'order' => '',
        'dependent' => true,
        ),
    );
    public function get_module_infos()
    {
    }
    //热门酒店推荐
    public function get_travel_hotel_recommends($params)
    {
        $conditions = array();
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['TravelHotel.category_id'] = $params['type_id'];
        }
        if (isset($params['destination_id'])) {
            $conditions['TravelHotel.destination_id'] = $params['destination_id'];
        }
        if ($params['controller'] == 'pages') {
            $conditions['TravelHotel.home_show'] = 1;
        }
        $conditions['TravelHotel.status'] = 1;
        $conditions['TravelHotel.hot'] = 1;
        $conditions['TravelHotel.recommand'] = 1;
        $hotel_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'TravelHotel.'.$order, 'fields' => 'TravelHotel.id,TravelHotel.destination_id,TravelHotel.destination_area_id,TravelHotel.star,TravelHotel.short_description,TravelHotel.name,TravelDestination.name,TravelDestinationArea.name'));

        return $hotel_infos;
    }
    //热门酒店促销
    public function get_travel_hotel_promotions($params)
    {
        $conditions = array();
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['TravelHotel.category_id'] = $params['type_id'];
        }
        if (isset($params['destination_id'])) {
            $conditions['TravelHotel.destination_id'] = $params['destination_id'];
        }
        $conditions['TravelHotel.status'] = 1;
        $p_conditions = array();
        if ($params['controller'] == 'pages') {
            $p_conditions['TravelHotelPromotion.status'] = 1;
            $p_conditions['TravelHotelPromotion.home_show'] = 1;
            $this->hasMany['ProductI18n']['conditions'] = $p_conditions;
            $hotel_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'TravelHotel.'.$order, 'fields' => 'TravelHotel.id,TravelHotel.destination_id,TravelHotel.destination_area_id,TravelHotel.star,TravelHotel.short_description,TravelHotel.name,TravelDestination.name,TravelDestinationArea.name'));
        } elseif ($params['controller'] == 'travel_destinations') {
            $p_conditions['TravelHotelPromotion.status'] = 1;
            $p_conditions['TravelHotelPromotion.destination_show'] = 1;
            $this->hasMany['TravelHotelPromotion']['conditions'] = $p_conditions;
            $hotel_infos = $this->find('all', array('conditions' => $conditions, 'order' => 'TravelHotel.'.$order, 'fields' => 'TravelHotel.id,TravelHotel.destination_id,TravelHotel.destination_area_id,TravelHotel.star,TravelHotel.short_description,TravelHotel.name,TravelDestination.name,TravelDestinationArea.name'));
        } else {
            $hotel_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'TravelHotel.'.$order, 'fields' => 'TravelHotel.id,TravelHotel.destination_id,TravelHotel.destination_area_id,TravelHotel.star,TravelHotel.short_description,TravelHotel.name,TravelDestination.name,TravelDestinationArea.name'));
        }
        if ($params['controller'] == 'travel_destinations') {
            $i = 1;
            foreach ($hotel_infos as $k => $h) {
                if ($i > $limit) {
                    unset($hotel_infos[$k]);
                    continue;
                }
                if (empty($h['TravelHotelPromotion'])) {
                    unset($hotel_infos[$k]);
                    continue;
                } else {
                    $name = '';
                    foreach ($h['TravelHotelPromotion'] as $p) {
                        $name .= $p['name'].',';
                    }
                    $name = $this->sub_str($name, 28);
                    $hotel_infos[$k]['TravelHotel']['promotion_name'] = $name;
                    ++$i;
                }
            }
        }

        return $hotel_infos;
    }
    //下拉框里面的信息
    public function selectInfos($destination_id)
    {
        if ($destination_id != 0 || $destination_id != '') {
            $hotel_infos = $this->find('all', array('conditions' => array('TravelHotel.status' => 1, 'TravelHotel.destination_id' => $destination_id), 'recursive' => -1, 'fields' => 'TravelHotel.id,TravelHotel.name,TravelHotel.english', 'order' => 'TravelHotel.english'));
        } else {
            $hotel_infos = $this->find('all', array('conditions' => array('TravelHotel.status' => 1), 'recursive' => -1, 'fields' => 'TravelHotel.id,TravelHotel.name,TravelHotel.english', 'order' => 'TravelHotel.english'));
        }

        return $hotel_infos;
    }

    public function hotel_format_id($ids)
    {
        $hotel_infos = $this->find('all', array('conditions' => array('TravelHotel.id' => $ids, 'TravelHotel.status' => 1), 'fields' => 'TravelHotel.id,TravelHotel.name,TravelHotel.star', 'recursive' => -1));
        $hotel_format_id = array();
        if (!empty($hotel_infos)) {
            foreach ($hotel_infos as $v) {
                $hotel_format_id[$v['TravelHotel']['id']] = $v['TravelHotel'];
            }
        }

        return $hotel_format_id;
    }
}
