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
class UserRank extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'UserRank';
    public $hasOne = array('UserRankI18n' => array('className' => 'UserRankI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'user_rank_id',
                        ),
                  );

    public function set_locale($locale)
    {
        $conditions = " UserRankI18n.locale = '".$locale."'";
        $this->hasOne['UserRankI18n']['conditions'] = $conditions;
    }
    //数组结构调整
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => "UserRank.id = '".$id."'"));
        $lists_formated = array();
        //pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['UserRank'] = $v['UserRank'];
            $lists_formated['UserRankI18n'][] = $v['UserRankI18n'];
            foreach ($lists_formated['UserRankI18n'] as $key => $val) {
                $lists_formated['UserRankI18n'][$val['locale']] = $val;
            }
        }
        //pr($lists_formated);
        return $lists_formated;
    }

    //用户等级整合数组
    public function findrank()
    {
        $condition = '';
        $lists = $this->find('all');
        foreach ($lists as $k => $v) {
            $lists[$k]['UserRank']['name'] = $lists[$k]['UserRankI18n']['name'];
        }

        return $lists;
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
