<?php


/**
 * 用户余额日志模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 */
class UserBalanceLog extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'UserBalanceLog';

    /**
     * 函数add_log 添加日志.
     *
     * @param $user_id 用户号
     * @param $amount 资金数
     * @param $admin_user 管理员名称
     * @param $admin_note 管理员注释
     * @param $system_note 系统注释
     * @param $log_type 日志类型
     * @param $type_id 关联编号
     * @param $created 创建时间
     * @param $log_info 日志内容
     *
     * @return true  日志添加成功
     * @return false 日志添加失败
     */
    public function add_log($user_id, $amount, $admin_user, $admin_note, $system_note, $log_type, $type_id)
    {
        $created = date('Y-m-d H:i:s');
        $log_info = array(
            'user_id' => $user_id,
            'amount' => $amount,
            'log_type' => $log_type,
            'admin_user' => $admin_user,
            'admin_note' => $admin_note,
            'system_note' => $system_note,
            'type_id' => $type_id,
            'created' => $created,
        );
        if ($this->save(array('UserBalanceLog' => $log_info))) {
            return true;
        } else {
            return false;
        }
    }
}
