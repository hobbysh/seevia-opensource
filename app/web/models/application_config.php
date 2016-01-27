<?php

/**
 *  插件设置模型.
 */
class ApplicationConfig extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'default';
    /*

     * @var $name Config 商店设置表
     */
    public $name = 'ApplicationConfig';
    public $locale = '';
    public $hasOne = array(
        'ApplicationConfigI18n' => array(
                'className' => 'ApplicationConfigI18n',
                'conditions' => array('locale' => LOCALE),
                'order' => '',
                'dependent' => true,
                'foreignKey' => 'app_config_id',
        ),
    );
}
