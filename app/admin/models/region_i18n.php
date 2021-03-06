<?php

/*****************************************************************************
 * svoms  商品多语言模型
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
/**
 * @var 用来解决PHP4中的一些奇怪的类名
 */
class RegionI18n extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'RegionI18n';

    public function getNames($locale)
    {
        $rname = $this->find('all', array('conditions' => array('RegionI18n.locale' => $locale)));
        foreach ($rname as $k => $v) {
            $rname[$v['RegionI18n']['region_id']] = $v['RegionI18n']['name'];
        }

        return $rname;
    }
}
