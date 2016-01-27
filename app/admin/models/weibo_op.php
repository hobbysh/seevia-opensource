<?php

/**
 * 友情链接模型.
 */
class WeiboOp extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'edi';
    /*
     * @var $name WeiboOp 
     */
    public $name = 'WeiboOp';

    public $belongsTo = array(
        //微博APP表	
        'WeiboRb' => array(
                'className' => 'WeiboRb',
                'conditions' => 'WeiboRb.id=WeiboOp.robort_id',
                'order' => '',
                'dependent' => true,
                'foreignKey' => '',
                ),
    );
}
