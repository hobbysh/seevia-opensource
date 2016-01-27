<?php

/*****************************************************************************
 * svoms  资金日志模型
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
class UserBalanceLog extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name UserBalanceLog 资金日志
     */
    public $name = 'UserBalanceLog';

    public $belongsTo = array('User' => array(
                            'className' => 'User',
                            'conditions' => 'User.id=UserBalanceLog.user_id',
                            'order' => '',
                            'dependent' => true,
                            'foreignKey' => '',
                        ),
                    );

    /**
     * order_user_balance_log_info方法，获取用户资金日志.
     *
     * @param $type_id 类型编号
     * @param $user_id 用户ID
     *
     * @return $balance_log 返回获取用户资金日志
     */
    public function order_user_balance_log_info($type_id, $user_id)
    {
        $condition['type_id'] = $type_id;
        $condition['user_id'] = $user_id;
        $condition['log_type'] = 'O';
        $balance_log = $this->find('first', array('conditions' => $condition));
        $balance_log['UserBalanceLog']['amount'] = !empty($balance_log['UserBalanceLog']['amount']) ? $balance_log['UserBalanceLog']['amount'] : '0';

        return $balance_log;
    }
}
