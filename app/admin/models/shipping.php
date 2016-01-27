<?php

/*****************************************************************************
 * svoms  配送模型方式
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
class shipping extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Shipping 配送方式
     */
    public $name = 'Shipping';

    /*
     * @var $hasOne array 关联配送方式多语言表
     */
    public $hasOne = array('ShippingI18n' => array('className' => 'ShippingI18n',
                                                  'order' => '',
                                                  'dependent' => true,
                                                  'foreignKey' => 'shipping_id',
                                                 ),
                        );
    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " ShippingI18n.locale = '".$locale."'";
        $this->hasOne['ShippingI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 输入分类编号
     *
     * @return array $lists_formated 返回配送方式所有语言的信息
     */
    public function localeformat($id)
    {
        $shipping_data = $this->find('all', array('conditions' => array('Shipping.id' => $id)));
        $shipping_data_formated = array();
        foreach ($shipping_data as $k => $v) {
            $shipping_data_formated['Shipping'] = $v['Shipping'];
            $shipping_data_formated['ShippingI18n'][] = $v['ShippingI18n'];
            foreach ($shipping_data_formated['ShippingI18n'] as $key => $val) {
                $shipping_data_formated['ShippingI18n'][$val['locale']] = $val;
            }
        }

        return $shipping_data_formated;
    }

    /**
     * shipping_effective_list方法，获取有效的配送方式.
     *
     * @param string $locale 语言代码
     *
     * @return array $lists_formated 返回获取有效的配送方式
     */
    public function shipping_effective_list($locale, $all_app_codes)
    {
        $fields = array('Shipping.id','Shipping.code','ShippingI18n.name');
        $shipping_list = $this->find('all', array('conditions' => array('Shipping.status' => 1, 'ShippingI18n.locale' => $locale), 'fields' => $fields));
        $xx = array();
        foreach ($shipping_list as $k => $v) {
            if (in_array('APP-DSP-'.strtoupper($v['Shipping']['code']), $all_app_codes)) {
                $xx[] = $v;
            }
        }

        return $xx;
    }

    /**
     * shipping_effective_list方法，获取有效的配送方式.
     *
     * @param string $locale        语言代码
     * @param string $all_app_codes 应用信息
     *
     * @return array $lists_formated 返回获取有效的配送方式
     */
    public function shipping_effective_list_beta($locale)
    {
        $fields = array('Shipping.id','Shipping.code','ShippingI18n.name');
        $shipping_list = $this->find('all', array('conditions' => array('Shipping.status' => 1, 'ShippingI18n.locale' => $locale), 'fields' => $fields));
        $xx = array();
        foreach ($shipping_list as $k => $v) {
            $xx[] = $v;
        }

        return $xx;
    }
    /**
     * get_shipping_name方法，获取有效的配送方式.
     *
     * @param string $id     配送方式id
     * @param string $locale 语言代码
     *
     * @return array $shipping_name 返回配送方式的名称
     */
    public function get_shipping_name($id, $locale)
    {
        $fields = array('Shipping.id','Shipping.code','ShippingI18n.name');
        $shipping_name = $this->find('first', array('conditions' => array('Shipping.id' => $id, 'Shipping.status' => 1, 'ShippingI18n.locale' => $locale), 'fields' => $fields));

        return $shipping_name;
    }
}
