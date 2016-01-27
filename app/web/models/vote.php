<?php

/**
 * 在线调查模型.
 */
class vote extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    public $name = 'Vote';
    public $hasOne = array('VoteI18n' => array('className' => 'VoteI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'vote_id',
        ),
    );
    /*
     * @var $hasMany array 关联VoteOption的选项表
     */
    public $hasMany = array('VoteOption' => array('className' => 'VoteOption',
            'conditions' => array('status' => 1),
            'fields' => array('id', 'vote_id','option_count','status'),
            'order' => ' orderby ',
            'dependent' => true,
            'foreignKey' => 'vote_id',
        ),
    );
    public function set_locale($locale)
    {
        $conditions = " VoteI18n.locale = '".$locale."'";
        $this->hasOne['VoteI18n']['conditions'] = $conditions;
    }

    public function get_votes($votes_conditions)
    {
        $votes = $this->find('all', array('orderby' => 'Vote.modified desc',
                    'conditions' => $votes_conditions, ));

        return $votes;
    }

    public function find_votes($now)
    {
        $votes = $this->find('all', array('orderby' => 'Vote.modified desc',
                    'fields' => array('Vote.id', 'Vote.can_multi', 'Vote.vote_count',
                        'VoteI18n.name',
                    ),
                    'conditions' => array('Vote.start_time <=' => $now, ' Vote.end_time  >= ' => $now, 'Vote.status ' => 1), ));

        return $votes;
    }
    /**
     *函数get_module_infos方法，在线调研模块相关.
     *
     *@param $params,查询条件
     *
     *@return $module_vote_infos 返回在线调研内容
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }

        if (isset($params['id'])) {
            $conditions['Vote.id'] = $params['id'];
        }
        $now = date('Y-m-d H:i:s');
        $conditions['Vote.status'] = 1;
        $conditions['Vote.start_time <='] = $now;
        $conditions['Vote.end_time >='] = $now;
        $module_vote_infos = $this->find('first', array('conditions' => $conditions));
        if (!empty($module_vote_infos) && sizeof($module_vote_infos) > 0) {
            $VoteOption_ids = array();
            foreach ($module_vote_infos['VoteOption'] as $k => $v) {
                $VoteOption_ids[] = $v['id'];
            }
            $VoteOption = ClassRegistry::init('VoteOption');
            $VoteOption_list = $VoteOption->find('all', array('conditions' => array('VoteOption.id' => $VoteOption_ids, 'VoteOption.status' => '1')));

            //pr($VoteOption_list);
            foreach ($module_vote_infos['VoteOption'] as $mk => $mv) {
                foreach ($VoteOption_list as $vk => $vv) {
                    if ($vv['VoteOptionI18n']['vote_option_id'] == $mv['id']) {
                        $module_vote_infos['VoteOption'][$mk]['name'] = $vv['VoteOptionI18n']['name'];
                    }
                }
            }
            //pr($module_vote_infos['VoteOption']);
        }

        return $module_vote_infos;
    }
}
