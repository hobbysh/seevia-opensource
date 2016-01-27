<?php

/**
 * 自定义导航模型.
 */
class navigation extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name Navigation 导航条模块
     */
    public $name = 'Navigation';
    /*
     * @var $cacheQueries true 缓存是否开启：是。
     */
    public $cacheQueries = true;
    /*
     * @var $cacheAction 1day 缓存时间：1天。
     */
    public $cacheAction = '1 day';
    /*
     * @var $hasOne array 关联导航条多语言表
     */
    public $hasOne = array('NavigationI18n' => array('className' => 'NavigationI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'navigation_id',
        ),
    );

    /**
     * get_types方法，判断语言种类并返回起相对应的导航条名称.
     *
     * @param $locale 输入语言种类
     *
     * @return $navigations_array 当语言种类存在则输出所有类型；否则排列所有类型值为1的并按照升序输出。
     */
    public function get_types()
    {
        $navigations_array = array();
        $condition = "status ='1'";//缓存'cache'=>$this->short,
            $navigations = $this->find('all', array('conditions' => $condition, 'order' => 'orderby asc', 'fields' => array('Navigation.id', 'Navigation.type', 'Navigation.parent_id', 'Navigation.target', 'Navigation.orderby', 'Navigation.status', 'NavigationI18n.locale', 'NavigationI18n.navigation_id', 'NavigationI18n.name', 'NavigationI18n.url', 'NavigationI18n.description', 'NavigationI18n.img01', 'NavigationI18n.img02')));
        if (is_array($navigations)) {
            foreach ($navigations as $k => $v) {
                if ($v['Navigation']['parent_id'] == 0) {
                    //		$navigations_array[$v['Navigation']['type']][$v['Navigation']['parent_id']]['SubMenu'][] = $v;
                        //	}else{
                        $navigations_array[$v['Navigation']['type']][$v['Navigation']['id']] = $v;
                }
            }
        }
        foreach ($navigations as $k => $v) {
            if ($v['Navigation']['parent_id'] > 0&&isset($navigations_array[$v['Navigation']['type']][$v['Navigation']['parent_id']])) {
                $navigations_array[$v['Navigation']['type']][$v['Navigation']['parent_id']]['SubMenu'][] = $v;
            }
        }

        return $navigations_array;
    }
}
