<?php

/**
 * 用户资金账户模型.
 */
class UserAccount extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'UserAccount';

    /**
     * 函数add_account 用于添加用户.
     *
     * @param $user_id 用户号
     * @param $amount 资金数量
     * @param $payment_time 支付时间
     * @param $admin_user 管理员名
     * @param $admin_note 管理员注释
     * @param $process_type 操作类型
     * @param $payment 支付渠道的名称
     * @param $created 创建时间
     * @param $account_info 账目信息
     *
     * @return true  账目添加成功
     * @return false 账目添加失败
     */
    public function add_account($user_id, $amount, $payment_time, $admin_user, $admin_note, $process_type, $payment)
    {
        $created = date('Y-m-d H:i:s');
        $account_info = array(
            'user_id' => $user_id,
            'amount' => $amount,
            'paid_time' => $payment_time,
            'process_type' => $process_type,
            'admin_user' => $admin_user,
            'admin_note' => $admin_note,
            'payment' => $payment,
            'created' => $created,
        );
        if ($this->save(array('UserAccount' => $account_info))) {
            return true;
        } else {
            return false;
        }
    }

    /* --------------------------------------------------------------------------------------------------------------------------------------------- */

    public function user_account($userId)
    {
        $user_account = $this->find('all', array('conditions' => 'UserAccount.user_id ='.$userId));

        return $user_account;
    }
}
