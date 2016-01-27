<?php

/*****************************************************************************
 * svcms  属性选项
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
class AttributeOption extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product 商品
     */
    public $name = 'AttributeOption';

    public function getattroption($attr_id = 0, $locale = 'chi')
    {
        $option_data = array();
        $option_info = array();
        $conditions['AttributeOption.attribute_id'] = $attr_id;
        $conditions['AttributeOption.status'] = 1;
        $conditions['AttributeOption.locale'] = $locale;
        $option_data = $this->find('all', array('conditions' => $conditions, 'order' => 'AttributeOption.attribute_id,AttributeOption.id'));
        if (!empty($option_data)) {
            foreach ($option_data as $v) {
                $option_info[$v['AttributeOption']['attribute_id']][$v['AttributeOption']['option_name']] = $v['AttributeOption']['option_value'];
            }
        }

        return $option_info;
    }

    public function getattroptionprice($attr_id, $locale = 'chi')
    {
        $price_data = array();
        $price_info = array();
        $conditions['AttributeOption.attribute_id'] = $attr_id;
        $conditions['AttributeOption.status'] = 1;
        $conditions['AttributeOption.locale'] = $locale;
        $price_data = $this->find('all', array('conditions' => $conditions, 'order' => 'AttributeOption.attribute_id,AttributeOption.id'));
        if (!empty($price_data)) {
            foreach ($price_data as $v) {
                $price_info[$v['AttributeOption']['attribute_id']][$v['AttributeOption']['option_value']] = $v['AttributeOption']['price'];
            }
        }

        return $price_info;
    }
}
