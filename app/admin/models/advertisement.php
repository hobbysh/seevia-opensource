<?php

/*****************************************************************************
 * svcms  广告条模型
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
class advertisement extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';

    /*
     * @var $name 广告条
     */
    public $name = 'Advertisement';
    /*
     * @var $order Advertisement 广告条
     */
    public $order = 'orderby asc,Advertisement.id desc';

    /*
     * @var $hasOne array 广告多语言表
     */
    public $hasOne = array('AdvertisementI18n' => array('className' => 'AdvertisementI18n',
            'conditions' => '',
            'dependent' => true,
            'foreignKey' => 'advertisement_id',
        ),
    );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale 语言代码
     */
    public function set_locale($locale)
    {
        $conditions = " AdvertisementI18n.locale = '".$locale."'";
        $this->hasOne['AdvertisementI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，广告数组结构调整.
     *
     * @param int $id 输入文章编号
     *
     * @return array $lists_formated 返回广告所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Advertisement.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Advertisement'] = $v['Advertisement'];
            $lists_formated['AdvertisementI18n'][] = $v['AdvertisementI18n'];
            foreach ($lists_formated['AdvertisementI18n'] as $key => $val) {
                $lists_formated['AdvertisementI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    /**
     * 获得所有有效广告条，并且按位置重组.
     *
     * @return $data 数组重组为多个code的子数组，调用方式为$return['code']
     */
    public function findAvailableList()
    {
        $params = array(
            'conditions' => array(//'Advertisement.advertisement_position_id' => $id,
              //  'AdvertisementI18n.start_time <=' => DateTime,
                //'AdvertisementI18n.end_time >=' => DateTime,
                    'AdvertisementI18n.start_time <=' => date('y-m-d 00:00:00'),
                  'AdvertisementI18n.end_time >=' => date('y-m-d 23:59:59'),
                'Advertisement.status' => 1, ),
            'fields' => array('Advertisement.code','Advertisement.media_type','AdvertisementI18n.url','AdvertisementI18n.url_type','AdvertisementI18n.name','AdvertisementI18n.code'),
        );
        $data_temp = $this->find('all', $params);
        $data = array();
        if ($data_temp) {
            foreach ($data_temp as $k => $v) {
                $data[$v['Advertisement']['code']][] = $v;
            }
        }

        return $data;
    }
}
