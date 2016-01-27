<?php

/*****************************************************************************
 * svoms  用户模型
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
class user extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name User 用户
     */
    public $name = 'User';

    /**
     * user_name_array方法，获取用户名称数据以user_id为键值.
     *
     * @param array $user_id_array 用户ID数组
     *
     * @return array $sr_parent_data 返回用户名称数据以user_id为键值
     */
    public function user_name_array($user_id_array = array())
    {
        $condition = '';
        if (!empty($user_id_array)) {
            $condition['id'] = $user_id_array;
        }
        $fields = array('id','name');
        $user_data = $this->find('all', array('conditions' => $condition, 'fields' => $fields));
        $user_data_format = array();
        foreach ($user_data as $k => $v) {
            $user_data_format[$v['User']['id']] = $v['User']['name'];
        }

        return $user_data;
    }
    /**
     * update_user_balance方法，更新用户余额.
     *
     * @param int   $user_id 用户ID数组
     * @param float $amount  金额
     */
    public function update_user_balance($user_id, $amount)
    {
        $user_info = $this->find('first', array('conditions' => array('User.id' => $user_id)));
        $user_money = $user_info['User']['balance'] + $amount;
        $update_info = array(
            'id' => $user_id,
            'balance' => $user_money,
        );
        $this->User->save(array('User' => $update_info));
    }

    /**
     *检测用昵称是否存在.
     */
    public function check_user_name_exist($name)
    {
        $user = $this->find('first', array('conditions' => array('User.name' => $name)));

        return empty($user) ? false : true;
    }
    /**
     *检测用户邮箱是否存在.
     */
    public function check_user_email_exist($email)
    {
        $user = $this->find('first', array('conditions' => array('User.email' => $email)));

        return empty($user) ? false : true;
    }
    /**
     *检测用户手机是否存在.
     */
    public function check_user_mobile_exist($mobile)
    {
        $user = $this->find('first', array('conditions' => array('User.mobile' => $mobile)));

        return empty($user) ? false : true;
    }
}
