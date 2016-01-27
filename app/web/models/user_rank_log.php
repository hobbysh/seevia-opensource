<?php

/*****************************************************************************
 * svoms  上传文件模型
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
class UserRankLog extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name
     */
    public $name = 'UserRankLog';

    /*
        验证用户会员是否到期,到期则自动降级
    */
    public function checkUserRank($user_id)
    {
        $max_end_time = $this->find('first', array('fields' => 'max(end_date) as max_end_time', array('conditions' => array('UserRankLog.user_id' => $user_id))));
        if (!empty($max_end_time[0])) {
            $this_time = date('Y-m-d H:i:s');
            $end_time = $max_end_time[0]['max_end_time'];
            if ($end_time != '0000-00-00 00:00:00' && $end_time != '') {
                if (strtotime($this_time) >= strtotime($end_time)) {
                    //会员已到期
                    $userranklog['rank_id'] = '0';
                    $userranklog['user_id'] = $user_id;
                    $userranklog['start_date'] = '0000-00-00 00:00:00';
                    $userranklog['end_date'] = '0000-00-00 00:00:00';
                    $userranklog['operator_id'] = '0';//系统
                    //系统操作无需支付金额，默认支付成功
                    $userranklog['balance'] = '0.00';
                    $userranklog['pay_status'] = '1';
                    $userranklog['created'] = date('Y-m-d H:i:s', time());
                    $userranklog['modified'] = date('Y-m-d H:i:s', time());

                    if ($this->save($userranklog)) {
                        //用户表
                        $UserInfo = ClassRegistry::init('User');
                        //修改用户表记录
                        $data['User']['id'] = $user_id;
                        $data['User']['rank'] = '0';
                        $UserInfo->save(array('User' => $data['User']));
                        //更新session值
                        $user_list = $UserInfo->find('first', array('conditions' => array('User.id' => $user_id)));
                        $_SESSION['User'] = $user_list;
                    }
                }
            }
        }
    }
}
