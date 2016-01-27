<?php

/*****************************************************************************
 * svsys 会员等级
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
class UserChat extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    public $name = 'UserChat';

    public function getInfoById($id)
    {
        $data = array();
        $cond['or']['UserChat.user_id'] = $id;
        $cond['or']['UserChat.to_user_id'] = $id;
        $_data = $this->find('all', array('conditions' => $cond, 'order' => 'UserChat.created desc'));
        if (!empty($_data)) {
            $user_ids = array();
            foreach ($_data as $k => $v) {
                $user_ids[$v['UserChat']['user_id']] = $v['UserChat']['user_id'];
                $user_ids[$v['UserChat']['to_user_id']] = $v['UserChat']['to_user_id'];
            }
            $User = ClassRegistry::init('User');
            $user_list = $User->find('list', array('conditions' => array('User.id' => $user_ids), 'fields' => array('User.id', 'User.name')));

            foreach ($_data as $k => $v) {
                $v['UserChat']['user'] = isset($user_list[$v['UserChat']['user_id']]) ? $user_list[$v['UserChat']['user_id']] : '';
                $v['UserChat']['to_user'] = isset($user_list[$v['UserChat']['to_user_id']]) ? $user_list[$v['UserChat']['to_user_id']] : '';
                $data[$k] = $v;
            }
        }

        return $data;
    }
}
