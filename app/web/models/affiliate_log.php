<?php

/**
 * 推荐来源日志模型.
 *
 * @todo 这类可能没有用，方法名称不对，需要查一下
 */
class AffiliateLog extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'AffiliateLog';

    public function get_affiliate_log_count($affiliate_conditions)
    {
        $total = $this->find('count', array('conditions' => $affiliate_conditions));

        return $total;
    }

    public function get_affiliate_log_all($all_uid, $page, $rownum)
    {
        $user_affiliate = $this->find('all', array('conditions' => array('AffiliateLog.user_id' => $all_uid),
                    'order' => 'AffiliateLog.created DESC',
                    'page' => $page,
                    'limit' => $rownum, ));

        return $user_affiliate;
    }
}
