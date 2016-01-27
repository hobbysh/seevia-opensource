<?php

/**
 * 支付方式模型.
 */
class payment extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Payment 付款表
     */
    public $name = 'Payment';
    /*
     * @var $hasOne array 付款语言对应表
     */
    public $hasOne = array('PaymentI18n' => array('className' => 'PaymentI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'payment_id',
        ),
    );

    public $acionts_parent_format = array();

    /**
     * availables方法，按照升序的方式显示支付方式.
     *
     * @return array $payments 按照Payment中的status、order_use_flag字段以及PaymentI18n表中的status字段来排序，并将排序的结果放进一个数组中返回。
     */
    public function availables()
    {
        $payments = $this->find('all', array('order' => array('Payment.orderby asc'),
                    'conditions' => array('Payment.status' => 1, 'Payment.order_use_flag' => 1, 'PaymentI18n.status' => 1),
                    'fields' => array('Payment.id', 'Payment.code', 'Payment.version', 'PaymentI18n.status', 'Payment.fee', 'Payment.config', 'Payment.is_cod', 'Payment.is_online', 'Payment.supply_use_flag', 'Payment.order_use_flag', 'Payment.logo', 'PaymentI18n.name', 'PaymentI18n.description',
                        ), ));

        return $payments;
    }
    public function cac_availables()
    {
        $payments = $this->find('all', array('order' => array('Payment.orderby asc'),
                    'conditions' => array('Payment.status' => 1, 'Payment.is_getinshop' => 1, 'Payment.order_use_flag' => 1, 'PaymentI18n.status' => 1),
                    'fields' => array('Payment.id', 'Payment.code', 'Payment.version', 'PaymentI18n.status', 'Payment.fee', 'Payment.config', 'Payment.is_cod', 'Payment.is_online', 'Payment.supply_use_flag', 'Payment.order_use_flag', 'Payment.logo', 'PaymentI18n.name', 'PaymentI18n.description',
                        ), ));

        return $payments;
    }

    public function find_pay_by_code($code)
    {
        $pay = $this->findbycode($code);

        return $pay;
    }

    public function get_payment_id($payment_id)
    {
        $pay = $this->findbyid($payment_id);

        return $pay;
    }

    public function getOrderPayments()
    {
        $this->acionts_parent_format = array();
        $payments = $this->find('all', array('order' => array('Payment.orderby asc'),
                    'conditions' => array('Payment.status' => 1, 'Payment.order_use_flag' => 1),
                    'fields' => array('Payment.id', 'Payment.parent_id', 'Payment.code', 'Payment.version', 'PaymentI18n.status', 'Payment.fee', 'Payment.config', 'Payment.is_cod', 'Payment.is_online', 'Payment.supply_use_flag', 'Payment.order_use_flag', 'Payment.logo', 'PaymentI18n.name', 'PaymentI18n.description',
                        ), ));
        if (!empty($payments) && is_array($payments)) {
            foreach ($payments as $k => $v) {
                $this->acionts_parent_format[$v['Payment']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }

    public function getOrderChildPayments($parent_id)
    {
        $payments = $this->find('all', array('order' => array('Payment.orderby asc'),
                    'conditions' => array('Payment.parent_id' => $parent_id, 'Payment.status' => 1, 'Payment.order_use_flag' => 1),
                    'fields' => array('Payment.id', 'Payment.code', 'Payment.version', 'PaymentI18n.status', 'Payment.fee', 'Payment.config', 'Payment.is_cod', 'Payment.is_online', 'Payment.supply_use_flag', 'Payment.order_use_flag', 'Payment.logo', 'PaymentI18n.name', 'PaymentI18n.description',
                        ), ));

        return $payments;
    }

    public function subcat_get($action_id)
    {
        $subcat = array();
        if (isset($this->acionts_parent_format[$action_id]) && is_array($this->acionts_parent_format[$action_id])) {
            foreach ($this->acionts_parent_format[$action_id] as $k => $v) {
                $action = $v;
                if (isset($this->acionts_parent_format[$v['Payment']['id']]) && is_array($this->acionts_parent_format[$v['Payment']['id']])) {
                    $action['SubMenu'] = $this->subcat_get($v['Payment']['id']);
                }
                $subcat[$k] = $action;
            }
        }

        return $subcat;
    }
}
