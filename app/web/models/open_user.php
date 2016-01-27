<?php

/**
 * 公众平台 关注用户模型.
 */
class OpenUser extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    /*
     * @var $name OpenUser
     */
    public $name = 'OpenUser';

    public function getUserIdByOpenId($openId)
    {
        $info = $this->find('first', array('conditions' => array('openid' => $openId)));

        return isset($info['OpenUser']['id']) ? $info['OpenUser']['id'] : '0';
    }

    public function getInfoByOpenId($openId)
    {
        $data = $this->find('first', array('conditions' => array('openid' => $openId)));

        return $data;
    }
}
