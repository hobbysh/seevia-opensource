<?php

/*****************************************************************************
 * svoms  StyleTypeGroupAttributeValue  商品版型规格尺寸表模型
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
class StyleTypeGroupAttributeValue extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product 商品
     */
    public $name = 'StyleTypeGroupAttributeValue';

    /*
        获取版型规格尺寸默认值列表
        @var $style_id 版型
        @var $type_id  属性组
        @var $style_type_group_id 规格
    */
    public function getattrvaluelist($style_id, $type_id, $style_type_group_id)
    {
        $attrvaluelist = $this->find('all', array('conditions' => array('StyleTypeGroupAttributeValue.style_id' => $style_id, 'StyleTypeGroupAttributeValue.type_id' => $type_id, 'StyleTypeGroupAttributeValue.style_type_group_id' => $style_type_group_id)));

        return $attrvaluelist;
    }
}
