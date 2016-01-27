<?php

/**
 * 商品多语言模型.
 *
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
