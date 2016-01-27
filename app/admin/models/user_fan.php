<?php

    /*
        svsns_user_fans
        用户互动管理
        可根据用户ID/名称和登陆时间范围搜索，列表显示 用户ID,用户头像，用户名称，用户粉丝数，关注数，日记数，同步设置的
        ICO(同步了就彩色，失效了则黑白) ，查看明细页，显示该用户的粉丝清单头像，和关注清单头像 根据最新关注时间降序排列
    */
class UserFan extends AppModel
{
    public $useDbConfig = 'sns';
    public $name = 'UserFan';

    public function getInfoById($id)
    {
        $data = array();
        $cond['or']['UserFan.user_id'] = $id;
        $cond['or']['UserFan.fan_id'] = $id;
        $_data = $this->find('all', array('conditions' => $cond));
        if (!empty($_data)) {
            $user_ids = array();
            foreach ($_data as $k => $v) {
                $user_ids[$v['UserFan']['user_id']] = $v['UserFan']['user_id'];
                $user_ids[$v['UserFan']['fan_id']] = $v['UserFan']['fan_id'];
            }
            $User = ClassRegistry::init('User');
            $user_list = $User->find('all', array('conditions' => array('User.id' => $user_ids), 'fields' => array('User.id', 'User.name', 'User.img01')));
            foreach ($_data as $k => $v) {
                foreach ($user_list as $kk => $vv) {
                    if ($v['UserFan']['user_id'] == $vv['User']['id']) {
                        $v['UserFan']['user']['name'] = $vv['User']['name'];
                        $v['UserFan']['user']['img'] = $vv['User']['img01'];
                    }
                    if ($v['UserFan']['fan_id'] == $vv['User']['id']) {
                        $v['UserFan']['fan_user']['name'] = $vv['User']['name'];
                        $v['UserFan']['fan_user']['img'] = $vv['User']['img01'];
                    }
                }

                if ($v['UserFan']['user_id'] == $id) {
                    $data['FanList'][$k] = $v;
                } elseif ($v['UserFan']['fan_id'] == $id) {
                    $data['UserList'][$k] = $v;
                }
            }
        }

        return $data;
    }
}
