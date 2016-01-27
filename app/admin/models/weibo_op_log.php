<?php

/**
 * 友情链接模型.
 */
class WeiboOpLog extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'edi';
    /*
     * @var $name WeiboOpLog 
     */
    public $name = 'WeiboOpLog';

    public $belongsTo = array(
        //微博APP表	
        'WeiboRb' => array(
                'className' => 'WeiboRb',
                'conditions' => 'WeiboRb.id=WeiboOpLog.robort_id',
                'order' => '',
                'dependent' => true,
                'foreignKey' => '',
                ),
    );
}
