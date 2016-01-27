<?php

/**
 * 用户留言模型.
 */
class UserMessage extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'UserMessage';

    public function get_user_message($conditions)
    {
        $my_messages = $this->find('all', array('conditions' => $conditions));

        return $my_messages;
    }

    public function find_new_messages($id)
    {
        $new_messages = $this->find('all', array('conditions' => array('UserMessage.user_id' => $id), 'order' => 'UserMessage.created ', 'limit' => 4));

        return $new_messages;
    }

    public function find_product_message($id)
    {
        $joins = array(
            array(
                    'table' => 'svoms_users',
                    'alias' => 'User',
                    'type' => 'left',
                    'conditions' => array('UserMessage.user_id=User.id'),
                ),
        );
        $fields = array('UserMessage.*','User.img01');
        $conditions = array(
            'UserMessage.status' => '1',
            'UserMessage.to_id' => '0',
            'UserMessage.type' => 'P',
            'UserMessage.value_id' => $id,
        );
        $product_message = $this->find('all', array('joins' => $joins, 'fields' => $fields, 'conditions' => $conditions));

        return $product_message;
    }

    public function find_replies_list($my_messages_parent_id)
    {
        $conditions = array(
            'UserMessage.parent_id' => $my_messages_parent_id,
            'UserMessage.from_id' => 0,
            'UserMessage.status' => 1,
        );
        $replies_list = $this->find('all', array('fields' => $fields, 'conditions' => $conditions));

        return $replies_list;
    }
}
