<?php

/**
 * 商店设置模型.
 */
class config extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'default';
    /*
     * @var $name Config 参数设置表
     */
    public $name = 'Config';
    /*
     * @var $hasOne array 参数设置多语言表
     */
    public $hasOne = array('ConfigI18n' => array('className' => 'ConfigI18n',
            'conditions' => array('locale' => 'chi'),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'config_id',
        ),
    );
    
    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions ['ConfigI18n.locale'] = $locale;
        $this->locale = $locale;
        $this->hasOne['ConfigI18n']['conditions'] = $conditions;
    }
    
    /*
     * @var $cacheQueries true 是否开启缓存：是。
     */
    public $cacheQueries = true;

    /**
     * getlist方法，获取所输入id对应的详细信息.
     *
     * @param $store_id 输入id
     *
     * @return $configs 返回id所对应的数据并升序输出
     */
    public function getlist($store_id = 0)
    {
        $condition = " store_id = '".$store_id."'";
        $configs = $this->findAll($condition, '', 'orderby asc');

        return $configs;
    }

    /**
     * getformatcode方法，获得格式代码.
     *
     * @param $locale 输入语言
     * @param $store_id 输入id
     *
     * @return $configs_formatcode 判断是否存在 如果不存在返回0 如果存在 返回参数
     */
    public function getformatcode($store_id = 0)
    {
        $condition = " store_id = '".$store_id."' AND status = 1";
        $configs = $this->find('all', array('cache' => $this->short, 'order' => 'orderby asc', 'fields' => array('Config.code', 'ConfigI18n.value'), 'conditions' => array($condition)));

        $configs_formatcode = array();
        if (is_array($configs)) {
            foreach ($configs as $v) {
                $configs_formatcode[$v['Config']['code']] = $v['ConfigI18n']['value'];
            }
        }
        if (!isset($configs_formatcode['use_sku'])) {
            $configs_formatcode['use_sku'] = 0;
        }

        return $configs_formatcode;
    }
}
