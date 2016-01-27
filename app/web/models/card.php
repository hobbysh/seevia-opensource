<?php

/**
 * 贺卡模型.
 */
class card extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Card 贺卡表
     */
    public $name = 'Card';
    /*
     * @var $hasOne array 贺卡多语言表
     */
    public $hasOne = array('CardI18n' => array('className' => 'CardI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'card_id',
        ),
    );

    public function find_card_lists()
    {
        $card_lists = $this->find('all',
                        array(
                            'fields' => array('Card.id', 'Card.img01', 'Card.fee', 'Card.free_money', 'CardI18n.name', 'CardI18n.description'),
                            'order' => array('Card.created desc'),
                            'conditions' => array('Card.status' => 1), ));

        return $card_lists;
    }

    public function get_cards_info($cards_info_conditions)
    {
        $cards_info = $this->find('all',
                        array('fields' => array('Card.id', 'Card.img01', 'Card.fee', 'Card.free_money', 'CardI18n.name', 'CardI18n.description'),
                            'conditions' => $cards_info_conditions, ));

        return $cards_info;
    }
}
