<?php

/**
 * 付款日志模型.
 */
class PaymentApiLog extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Payment 付款日志表
     */
    public $name = 'PaymentApiLog';

    public function get_payment_api_logs($conditions)
    {
        $api_logs = $this->find('all', array('conditions' => $conditions));

        return $api_logs;
    }

    public function find_payment_log_by_id($id)
    {
        $payment_log = $this->findbyid($id);

        return $payment_log;
    }
}
