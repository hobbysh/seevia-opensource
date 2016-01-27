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
    	@param string $open_type_id  公众平台账号
    	@return $open_config_data
    */
    public function tree($open_type_id = '')
    {
        $open_config_data = array();
        $open_config_ids = array();
        $cond = array();
        if ($open_type_id != '') {
            $cond['OpenConfig.open_type_id'] = $open_type_id;
        }
        $open_config_list = $this->find('all', array('conditions' => $cond));
        if (!empty($open_config_list)) {
            $OpenElement = ClassRegistry::init('OpenElement');//引入素材Model
            $open_config_data = array();
            foreach ($open_config_list as $k => $v) {
                if (!in_array($v['OpenConfig']['id'], $open_config_ids)) {
                    $open_config_ids[] = $v['OpenConfig']['id'];
                    $open_config_data[$v['OpenConfig']['code']]['OpenConfig'] = $v['OpenConfig'];
                    $open_config_data[$v['OpenConfig']['code']]['OpenConfigsI18n'][$v['OpenConfigsI18n']['locale']] = $v['OpenConfigsI18n'];
                } else {
                    $open_config_data[$v['OpenConfig']['code']]['OpenConfigsI18n'][$v['OpenConfigsI18n']['locale']] = $v['OpenConfigsI18n'];
                }
            }
        }

        return $open_config_data;
    }
}
