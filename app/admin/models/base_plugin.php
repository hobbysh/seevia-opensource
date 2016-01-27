<?php

/**
 * 支付方式模型.
 */
class BasePlugin extends AppModel
{
    public $useDbConfig = 'default_base';
    public $useTable = 'base_plugins';
    /*
     * @var $name plugin 支付方式
     */
    public $name = 'BasePlugin';

    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasMany = array('BasePluginConfig' => array('className' => 'BasePluginConfig',
            'conditions' => '',
            'fields' => 'BasePluginConfig.id,BasePluginConfig.code,BasePluginConfig.value,BasePluginConfig.description',
            'dependent' => true,
            'foreignKey' => 'plugin_id',
        ),
    );
}
