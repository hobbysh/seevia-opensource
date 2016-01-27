<?php

/**
 * 用户地址簿模型.
 */
class UserAddress extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name UserAddress 用户地址簿
     */
    public $name = 'UserAddress';
    /**
     * user_addresses_get方法，获取用户的地址簿数据.
     *
     * @param int $user_id 用户ID
     *
     * @return array $user_addresses_data 返回获取用户的地址簿数据
     */
    public function user_addresses_get($user_id)
    {
        $user_addresses_data = $this->find('all', array('conditions' => array('user_id' => $user_id)));

        return $user_addresses_data;
    }
}
