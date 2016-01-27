<?php

/**
 * 资金日志模型.
 */
class UserPointLog extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name UserBalanceLog 资金日志
     */
    public $name = 'UserPointLog';

    public $belongsTo = array('User' => array(
                            'className' => 'User',
                            'conditions' => 'User.id=UserPointLog.user_id',
                            'order' => '',
                            'dependent' => true,
                            'foreignKey' => '',
                        ),
                    );
}
