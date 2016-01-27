<?php

class SynchroUser extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    public $name = 'SynchroUser';

    /**
     * checkUsertoken方法，验证用户token是否过期,过期则自动删除该记录.
     *
     * @param  $userid 用户Id
     * @param $type 需要查询的app类型
     * @param $_date 过期天数
     */
    public function checkUsertoken($userid = '', $type = array(), $_date = 7)
    {
        $cond['SynchroUser.user_id'] = $userid;
        if (!empty($type) && $sizeof($type) > 0) {
            $cond['SynchroUser.type'] = $type;
        } else {
            //查询当前所有可用的UserApp类型
            $UserApp = ClassRegistry::init('UserApp');
            $type = $UserApp->find('list', array('fields' => array('UserApp.type'), 'conditions' => array('UserApp.status' => '1')));
            $cond['SynchroUser.type'] = $type;
        }
        $SynchroUser_list = $this->find('list', array('fields' => array('SynchroUser.id', 'SynchroUser.created'), 'conditions' => $cond));
        if (!empty($SynchroUser_list) && sizeof($SynchroUser_list) > 0) {
            foreach ($SynchroUser_list as $k => $v) {
                $this_time = date('Y-m-d H:i:s', time());
                $this_date = floor((strtotime($this_time) - strtotime($v)) / 86400);
                if ($this_date > $_date) {
                    $this->deleteAll(array('id' => $k));
                }
            }
        }
    }
}
