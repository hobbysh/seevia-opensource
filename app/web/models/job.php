<?php

/**
 * 职位模型.
 *
 * @todo 一些函数改用find list
 */
class job extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name Job 
     */
    public $name = 'Job';
    /*
     * @var $cacheQueries boolen 是否开启缓存：是。
     */
    public $cacheQueries = true;
    /*
     * @var $cacheAction 1day 缓存时间：1天。
     */
    public $cacheAction = '1 day';
    /*
     * @var $hasOne array 关联多语言表
     */
    public $hasOne = array('JobI18n' => array('className' => 'JobI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'job_id',
        ),
    );
}
