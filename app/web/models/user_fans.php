<?php

/**
 * 用户粉丝模型.
 */
class UserFans extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    public $name = 'UserFans';

    /*查询一个用户的粉丝数量*/
    public function find_fanscount_byuserid($user_id)
    {
        $fans = $this->find('count', array('conditions' => array('user_id' => $user_id))); //用户的粉丝数量
        return $fans;
    }
    /*查询一个用户的关注数量*/
    public function find_focuscount_byuserid($user_id)
    {
        $fans = $this->find('count', array('conditions' => array('fan_id' => $user_id))); //用户的粉丝数量
        return $fans;
    }
    /*查询一个用户的关注用户的id*/
    public function find_attention_byuserid($user_id)
    {
        $attention_id = $this->find('all', array('conditions' => array('fan_id' => $user_id), 'fields' => array('UserFans.user_id'))); //用户的粉丝数量
        return $attention_id;
    }

    /*查询用户粉丝数量*/
    public function find_fanscount_byuseridarr($user_idarr)
    {
        $fans = $this->find('all', array('fields' => array('user_id', 'count(fan_id) as fanscount'), 'conditions' => array('user_id' => $user_idarr), 'group' => 'user_id')); //用户的粉丝数量
        return $fans;
    }

    /*查询用户关注数量*/
    public function find_focuscount_byuseridarr($user_idarr)
    {
        $fans = $this->find('all', array('fields' => array('fan_id', 'count(user_id) as focuscount'), 'conditions' => array('fan_id' => $user_idarr), 'group' => 'fan_id')); //用户的粉丝数量
        return $fans;
    }
}
