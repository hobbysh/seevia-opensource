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
    public function getOpenIdListByUserId($userIds)
    {
        $list = $this->find('all', array('conditions' => array('OpenUser.id' => $userIds)));
        $openIdList = array();
        if (!empty($list)) {
            foreach ($list as $v) {
                if (!in_array($v['OpenUser']['openid'], $openIdList)) {
                    $openIdList[$v['OpenUser']['id']] = $v['OpenUser']['openid'];
                }
            }
        }

        return $openIdList;
    }

    public function getUserIdByOpenId($openId)
    {
        $info = $this->find('first', array('conditions' => array('openid' => $openId)));

        return $info['OpenUser']['id'];
    }

    public function getInfoByOpenId($openId)
    {
        $data = $this->find('first', array('conditions' => array('openid' => $openId)));

        return $data;
    }
}
