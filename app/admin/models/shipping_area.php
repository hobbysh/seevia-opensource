<?php

/*****************************************************************************
 * svoms 配送地区方式
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
class ShippingArea extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name ShippingArea 配送地区
     */
    public $name = 'ShippingArea';

    /*
     * @var $hasOne array 关联配送地区多语言表
     */
    public $hasOne = array('ShippingAreaI18n' => array(
                            'className' => 'ShippingAreaI18n',
                            'order' => '',
                            'dependent' => true,
                            'foreignKey' => 'shipping_area_id',
                        ),
                    );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param $locale
     */
    public function set_locale($locale)
    {
        $conditions = " ShippingAreaI18n.locale = '".$locale."'";
        $this->hasOne['ShippingAreaI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 输入分类编号
     *
     * @return array $lists_formated 返回配送地区所有语言的信息
     */
    public function localeformat($id)
    {
        $shipping_area_data = $this->find('all', array('conditions' => array('ShippingArea.id' => $id)));
        $shipping_area_data_formated = array();
        foreach ($shipping_area_data as $k => $v) {
            $shipping_area_data_formated['ShippingArea'] = $v['ShippingArea'];
            $shipping_area_data_formated['ShippingAreaI18n'][] = $v['ShippingAreaI18n'];
            foreach ($shipping_area_data_formated['ShippingAreaI18n'] as $key => $val) {
                $shipping_area_data_formated['ShippingAreaI18n'][$val['locale']] = $val;
            }
        }

        return $shipping_area_data_formated;
    }
}
