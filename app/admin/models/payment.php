<?php

/*****************************************************************************
 * svoms 支付方式模型
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
class payment extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Payment 支付方式
     */
    public $name = 'Payment';

    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('PaymentI18n' => array('className' => 'PaymentI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'payment_id',
                        ),
                    );

    public $acionts_parent_format = array();

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " PaymentI18n.locale = '".$locale."'";
        $this->hasOne['PaymentI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 输入分类编号
     *
     * @return array $lists_formated 返回支付方式所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Payment.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Payment'] = $v['Payment'];
            $lists_formated['PaymentI18n'][] = $v['PaymentI18n'];
            foreach ($lists_formated['PaymentI18n'] as $key => $val) {
                $lists_formated['PaymentI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    /**
     * payment_effective_list方法，获取有效的支付方式.
     *
     * @param string $locale 语言代码
     *
     * @return array $payment_list 返回支付方式的信息
     */
    public function payment_effective_list($locale)
    {
        $fields = array('Payment.id','Payment.code','PaymentI18n.name');
        $payment_list = $this->find('all', array('conditions' => array('Payment.status' => 1, 'PaymentI18n.locale' => $locale), 'fields' => $fields));

        return $payment_list;
    }

    /**
     * get_payment_name方法，获取有效的支付方式.
     *
     * @param string $id     支付方式id
     * @param string $locale 语言代码
     *
     * @return array $payment_list 返回支付方式的名称
     */
    public function get_payment_name($id, $locale)
    {
        $fields = array('Payment.id','Payment.code','PaymentI18n.name');
        $payment_name = $this->find('first', array('conditions' => array('Payment.id' => $id, 'Payment.status' => 1, 'PaymentI18n.locale' => $locale), 'fields' => $fields));

        return $payment_name;
    }

    public function tree($cond)
    {
        $actions = $this->find('all', $cond);
        $this->acionts_parent_format = array();
        if (!empty($actions) && is_array($actions)) {
            foreach ($actions as $k => $v) {
                $this->acionts_parent_format[$v['Payment']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
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

    public function getOrderPayments()
    {
        $this->acionts_parent_format = array();
        $payments = $this->find('all', array('order' => array('Payment.orderby asc'),
                    'conditions' => array('Payment.status' => 1, 'Payment.order_use_flag' => 1),
                    'fields' => array('Payment.id', 'Payment.parent_id', 'Payment.code', 'Payment.version', 'PaymentI18n.status', 'Payment.fee', 'Payment.config', 'Payment.is_cod', 'Payment.is_online', 'Payment.supply_use_flag', 'Payment.order_use_flag', 'PaymentI18n.name', 'PaymentI18n.description',
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
                    'fields' => array('Payment.id', 'Payment.code', 'Payment.version', 'PaymentI18n.status', 'Payment.fee', 'Payment.config', 'Payment.is_cod', 'Payment.is_online', 'Payment.supply_use_flag', 'Payment.order_use_flag', 'PaymentI18n.name', 'PaymentI18n.description',
                        ), ));

        return $payments;
    }
}
