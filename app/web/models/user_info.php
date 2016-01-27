<?php

/**
 * 用户信息模型.
 */
class UserInfo extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'UserInfo';
    public $hasOne = array('UserInfoI18n' => array('className' => 'UserInfoI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'user_info_id',
        ),
    );

//用户等级整合数组
    public function findinfoassoc($values_id)
    {
        if (!empty($values_id)) {
            $condition = array('UserInfo.id' => $values_id,
                'UserInfo.status' => 1,
                'UserInfo.front' => 1, );
        } else {
            $condition = "UserInfo.status = '1' and UserInfo.front = '1'";
        }

        $lists = $this->findAll($condition);
        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['UserInfo']['id']]['UserInfo'] = $v['UserInfo'];
                if (is_array($v['UserInfoI18n'])) {
                    //$lists_formated[$v['UserInfo']['id']]['UserInfoI18n']=$v['UserInfoI18n'];
                    $lists_formated[$v['UserInfo']['id']]['UserInfo']['name'] = $v['UserInfoI18n']['name'];
                    $lists_formated[$v['UserInfo']['id']]['UserInfo']['user_info_values'] = $v['UserInfoI18n']['user_info_values'];
                    $lists_formated[$v['UserInfo']['id']]['UserInfo']['message'] = $v['UserInfoI18n']['message'];
                    $lists_formated[$v['UserInfo']['id']]['UserInfo']['remark'] = $v['UserInfoI18n']['remark'];
                }
                //$lists_formated[$v['UserInfo']['id']]['UserInfo']['name']='';
                //foreach($lists_formated[$v['UserInfo']['id']]['UserInfoI18n'] as $key => $val){
                //		$lists_formated[$v['UserInfo']['id']]['UserInfo']['name'] .=$val['name'] . " | ";
                //}
            }
        }
        //	pr($lists_formated);
        return $lists_formated;
    }
}
