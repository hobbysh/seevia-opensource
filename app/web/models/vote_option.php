<?php

/**
 * 在线调查选项模型.
 */
class VoteOption extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    public $name = 'VoteOption';
    public $hasOne = array('VoteOptionI18n' => array('className' => 'VoteOptionI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'vote_option_id',
        ),
    );

    public function set_locale($locale)
    {
        $conditions = " VoteOptionI18n.locale = '".$locale."'";
        $this->hasOne['VoteOptionI18n']['conditions'] = $conditions;
    }

    public function get_vote_options($vote_options_conditions)
    {
        $vote_options = $this->find('all', array('order' => array('VoteOption.option_count ASC', 'VoteOption.modified ASC'),
                    'conditions' => $vote_options_conditions, ));

        return $vote_options;
    }

    public function find_vote_options($id)
    {
        $vote_options = $this->find('all', array('order' => array('VoteOption.option_count ASC', 'VoteOption.modified ASC'),
                    'fields' => array('VoteOption.id', 'VoteOption.option_count', 'VoteOption.vote_id',
                        'VoteOptionI18n.name',
                    ),
                    'conditions' => array('VoteOption.status' => 1, 'VoteOption.vote_id' => $id), ));

        return $vote_options;
    }
}
