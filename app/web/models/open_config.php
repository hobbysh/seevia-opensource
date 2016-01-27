<?php

/**
 * 公众平台配置表.
 */
class OpenConfig extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'sns';

    /*
     * @var $name OpenConfig 公众平台配置表
     */
    public $name = 'OpenConfig';

    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('OpenConfigsI18n' => array('className' => 'OpenConfigsI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'open_config_id',
                        ),
                    );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = '';
        $conditions = " OpenConfigsI18n.locale = '".$locale."'";
        $this->hasOne['OpenConfigsI18n']['conditions'] = $conditions;
    }

    /*
    	tree方法，获取配置表数据
    	@param string $options  查询条件
    	@return $open_config_data
    */
    public function tree($options = array(), $locale = 'chi')
    {
        $open_config_data = array();
        $cond = array();
        if (isset($options['open_type'])) {
            $cond['OpenConfig.open_type'] = $options['open_type'];
        }
        if (isset($options['open_type_id'])) {
            $cond['OpenConfig.open_type_id'] = $options['open_type_id'];
        }
        if (isset($options['code'])) {
            $cond['OpenConfig.code'] = $options['code'];
        }
        $cond['OpenConfig.status'] = '1';
        $cond['OpenConfigsI18n.locale'] = $locale;
        $open_config_list = $this->find('all', array('conditions' => $cond));
        if (!empty($open_config_list)) {
            $open_config_data = array();
            foreach ($open_config_list as $k => $v) {
                if ($v['OpenConfigsI18n']['value'] == '') {
                    continue;
                }
                if (isset($options['open_type'])) {
                    $open_config_data[$v['OpenConfig']['code']] = $v['OpenConfigsI18n'];
                } else {
                    $open_config_data[$v['OpenConfig']['open_type']][$v['OpenConfig']['code']] = $v['OpenConfigsI18n'];
                }
            }
        }

        return $open_config_data;
    }
}
