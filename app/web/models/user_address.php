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
    public $name = 'UserAddress';

    public function find_user_address($user_id)
    {
        $find_user_address = $this->find('all', array('conditions' => "UserAddress.user_id = '".$user_id."'", 'order' => 'UserAddress.created desc'));

        return $find_user_address;
    }

    public function find_count_addresses($user_id)
    {
        $count_addresses = $this->find('count', array('conditions' => "UserAddress.user_id = '".$user_id."'"));

        return $count_addresses;
    }

    public function find_address_by_id($address_by_id)
    {
        $address = $this->find('first', array('conditions' => "UserAddress.id = '".$address_by_id."'"));//标记
        return $address;
    }

    public function find_same_address($id, $user_id)
    {
        $address = $this->find('UserAddress.id = '.$id.' and UserAddress.user_id ='.$user_id);

        return $address;
    }
}
