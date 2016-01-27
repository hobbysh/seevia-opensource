<?php

/*****************************************************************************
 * svoms  StyleTypeGroup 版型规格表 模型
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
class StyleTypeGroup extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product 商品
     */
    public $name = 'StyleTypeGroup';

    /*
        获取版型尺寸规格列表
        $style_id 版型ID
        $type_id 商品属性组ID
        $group_name 规格值
    */
    public function getstyletypegrouplist($style_id, $type_id, $group_name = '')
    {
        $cond['StyleTypeGroup.style_id'] = $style_id;
        $cond['StyleTypeGroup.type_id'] = $type_id;
        if ($group_name != '') {
            $cond['StyleTypeGroup.group_name'] = $group_name;
        }
        $styletypegrouplist = $this->find('list', array('fields' => array('StyleTypeGroup.id', 'StyleTypeGroup.group_name'), 'conditions' => $cond, 'order' => 'StyleTypeGroup.orderby'));

        return $styletypegrouplist;
    }
}
