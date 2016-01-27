<?php

/**
 * 货币模型.
 */
class currency extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Currency 货币表
     */
    public $name = 'Currency';
    /*
     * @var $hasMany array 关联货币多语言表
     */
    public $hasMany = array('CurrencyI18n' => array('className' => 'CurrencyI18n',
            'order' => '',
            'conditions' => "CurrencyI18n.status = '1'",
            'dependent' => true,
            'foreignKey' => 'currency_id',
        ),
    );

    public function get_currencies($currencies_conditions)
    {
        $currencies = $this->find('all', array('conditions' => $currencies_conditions, 'currencies'));

        return $currencies;
    }
}
