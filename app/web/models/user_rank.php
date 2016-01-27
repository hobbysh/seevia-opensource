<?php

/**
 * 用户等级.
 */
class UserRank extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'UserRank';
    public $cacheQueries = true;
    public $cacheAction = '1 day';
    public $hasOne = array('UserRankI18n' => array('className' => 'UserRankI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'user_rank_id',
        ),
    );

    /**
     * 函数get_rank 获取会员等级.
     *
     * @param $rank_id 会员等级号
     * @param $locale 会员语言
     *
     * @return $rank_name 会员等级名称
     */
    public function get_rank($rank_id, $locale)
    {
        $rank = $this->find("UserRank.id = $rank_id");
        $rank_name = $rank['UserRankI18n']['name'];

        return $rank_name;
    }
    /**
     * 函数get_rank_code 获取会员等级code.
     *
     * @param $rank_id 会员等级号
     * @param $locale 会员语言
     *
     * @return $rank_code 会员等级code
     */
    public function get_rank_code($rank_id)
    {
        $rank = $this->find("UserRank.id = $rank_id");
        $rank_code = $rank['UserRank']['code'];

        return $rank_code;
    }
    /**
     * 函数findrank,用户等级整合数组.
     *
     * @param $condition 用户信息
     * @param $cache_key 缓存识别
     * @param $lists_formated 用户列表
     * @param $lists 用户列表
     *
     * @return $lists_formated 用户等级整合列表
     */
    public function findrank()
    {
        $condition = '';
        $cache_key = md5($this->name.'_findrank');

        $lists_formated = cache::read($cache_key);
        if ($lists_formated) {
            return $lists_formated;
        } else {
            $lists = $this->find('all', array('conditions' => $condition));

            $lists_formated = array();
            if (is_array($lists)) {
                foreach ($lists as $k => $v) {
                    $lists_formated[$v['UserRank']['id']]['UserRank'] = $v['UserRank'];
                    if (is_array($v['UserRankI18n'])) {
                        $lists_formated[$v['UserRank']['id']]['UserRankI18n'][] = $v['UserRankI18n'];
                    }
                    $lists_formated[$v['UserRank']['id']]['UserRank']['name'] = '';
                    foreach ($lists_formated[$v['UserRank']['id']]['UserRankI18n'] as $key => $val) {
                        $lists_formated[$v['UserRank']['id']]['UserRank']['name'] .= $val['name'].' | ';
                    }
                }
            }
            cache::write($cache_key, $lists_formated);

            return $lists_formated;
        }
    }

    public function user_upgrade_vip($user_id)
    {
        $user_rank_id = 0;
        $UserRand_info = $this->find('list', array('fields' => array('UserRank.id', 'UserRank.code'), 'order' => 'UserRank.id'));
        if (!empty($UserRand_info)) {
            $UserRand_list = array();
            foreach ($UserRand_info as $k => $v) {
                $UserRand_list[$v] = $k;
            }
            $UserInfo = ClassRegistry::init('User');
            $user_info = $UserInfo->find('first', array('fields' => array('User.id', 'User.rank'), 'conditions' => array('User.id' => $user_id)));
            $user_rank_id = isset($user_info['User']['rank']) ? $user_info['User']['rank'] : 0;
            if (isset($UserRand_info[$user_rank_id]) && $UserRand_info[$user_rank_id] == 'verified') {
                $user_info['User']['id'] = $user_id;
                $user_info['User']['rank'] = isset($UserRand_list['vip']) ? $UserRand_list['vip'] : $user_rank_id;
                $UserInfo->save($user_info);
            }
        }
    }
}
