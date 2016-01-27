<?php

/**
 * 广告条模型.
 */
class advertisement extends AppModel
{
    public $useDbConfig = 'cms';
    /*
     * @var $name 广告条
     */
    public $name = 'Advertisement';
    /*
     * @var $order Advertisement 广告条
     */
    public $order = 'Advertisement.orderby,Advertisement.id desc';
    /*
     * @var $hasOne array 广告多语言表
     */
    public $hasOne = array('AdvertisementI18n' => array('className' => 'AdvertisementI18n',
                'conditions' => array('locale' => LOCALE),
                'fields' => array('name','url', 'url_type'),
                'dependent' => true,
                'foreignKey' => 'advertisement_id',
                  ),
                'AdvertisementEffect' => array('className' => 'AdvertisementEffect',
                'conditions' => array('AdvertisementEffect.status' => '1'),
                'fields' => array('configs','images'),
                'dependent' => true,
                'foreignKey' => 'advertisements_id', ),
    );

    /**
     * 获得所有有效广告条，并且按位置重组.
     *
     * @return $data 数组重组为多个code的子数组，调用方式为$return['code']
     */
    public function findAvailableList($adv_position = array())
    {
        $params = array('cache' => $this->short,
                        'conditions' => array(//'Advertisement.advertisement_position_id' => $id,
               // 'AdvertisementI18n.start_time <=' => DateTime,
             //   'AdvertisementI18n.end_time >=' => DateTime,
                'AdvertisementI18n.start_time <=' => date('Y-m-d 00:00:00'),
                'AdvertisementI18n.end_time >=' => date('Y-m-d 23:59:59'),
                'Advertisement.status' => 1,
                'advertisement_position_id' => $adv_position, ),
                    'orderby' => 'orderby asc',
            'fields' => array('AdvertisementEffect.type','AdvertisementEffect.configs','AdvertisementEffect.status','AdvertisementEffect.images','advertisement_position_id','Advertisement.code','Advertisement.media_type','AdvertisementI18n.url','AdvertisementI18n.url_type','AdvertisementI18n.name','AdvertisementI18n.code'),
        );
        $data_temp = $this->find('all', $params);
       // pr($data_temp);      
        $data = array();
        if ($data_temp) {
            foreach ($data_temp as $k => $v) {
                $v['AdvertisementEffect']['config'] = array();
                $v['AdvertisementEffect']['config'] = json_decode($v['AdvertisementEffect']['configs']);
                $v['AdvertisementEffect']['config'] = (array) $v['AdvertisementEffect']['config'];
                $tmp = array();
                $v['AdvertisementEffect']['image'] = array();
                $tmp = json_decode($v['AdvertisementEffect']['images']);
                if (isset($tmp) && is_array($tmp)) {
                    foreach ($tmp as $vv) {
                        $v['AdvertisementEffect']['image'][] = (array) $vv;
                    }
                }
                $data[$v['Advertisement']['advertisement_position_id']][] = $v;
            }
        }

        return $data;
    }
}
