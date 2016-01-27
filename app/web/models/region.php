<?php

/**
 * 区域模型.
 */
class region extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name 用来解决PHP4中的一些奇怪的类名
     * @var $hasOne 设置模型关联 */
    public $name = 'Region';
    public $hasOne = array('RegionI18n' => array('className' => 'RegionI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'region_id',
        ),
    );
    /**
     *函数set_locale,设置区域语言.
     *
     *@param $locale 地域语言类型
     *@param $conditions 区域信息
     */
    public function set_locale($locale)
    {
        $conditions = " RegionI18n.locale = '".$locale."'";
        $this->hasOne['RegionI18n']['conditions'] = $conditions;
    }

    /**
     * 函数strtoid,获取区域名.
     *
     * @param $str 地域
     * @param $last_name 地域名
     *
     * @return 地域信息
     */
    public function strtoid($str)
    {
        $last_name = array_pop(explode(' ', trim($str)));

        return $this->find("RegionI18n.name ='".$last_name."'");
    }

    /**
     * 函数strtoid,获取区域号.
     *
     * @param $str 地域名
     * @param $names 分割后的地域名
     * @param $ids 地域分类号
     *
     * @return $ids 地域分类号
     */
    public function strtoids($str)
    {
        $names = explode(' ', trim($str));
        //	pr($names);
        $node['config'] = 'node';
        $node['use'] = true;
        $ids = $this->find('list', array('conditions' => array('RegionI18n.name' => $names), 'cache' => $node, 'recursive' => 1));
        //	pr($ids);
        return $ids;
    }

    /**
     *
     */
    public function find_regions_name($region_array)
    {
        $node['config'] = 'node';
        $node['use'] = true;
        $regions = $this->find('all', array('cache' => $node, 'conditions' => array('Region.id' => $region_array)));
        $regions_name = '';
        if (is_array($regions) && sizeof($regions) > 0) {
            foreach ($regions as $kk => $vv) {
                $regions_name .= isset($vv['RegionI18n']['name']) ? $vv['RegionI18n']['name'].' ' : '';
            }
        }

        return $regions_name;
    }

    public function find_low_region($region_id)
    {
        $low_region = $this->findAll("Region.parent_id = '".$region_id."'"); //标记
        return $low_region;
    }

    public function find_region_name_arr($region_array)
    {
        $node['config'] = 'node';
        $node['use'] = true;
        $region_name_arr = $this->find('all', array('cache' => $node, 'fields' => array('Region.id', 'Region.parent_id', 'Region.level', 'RegionI18n.name'),
                    'conditions' => array('Region.id' => $region_array), ));

        return $region_name_arr;
    }

    //查询一级地区
    public function find_top_regions()
    {
        $node['config'] = 'node';
        $node['use'] = true;
        $top_regions = $this->find('all', array('cache' => $node, 'conditions' => array('Region.parent_id' => '0')));
        $top_regions_num = count($top_regions);

        return $top_regions_num;
    }
}
