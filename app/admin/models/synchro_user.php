<?php

class SynchroUser extends AppModel
{
    public $useDbConfig = 'sns';
    public $name = 'SynchroUser';

    public function getInfoByUser($userid)
    {
        $data = array();
        $cond['SynchroUser.user_id'] = $userid;
        $_data = $this->find('all', array('conditions' => $cond, 'fields' => array('SynchroUser.id', 'SynchroUser.user_id', 'SynchroUser.type', 'SynchroUser.status')));
        if (!empty($_data)) {
            foreach ($_data as $v) {
                $data[$v['SynchroUser']['user_id']][$v['SynchroUser']['type']] = $v['SynchroUser']['status'];
            }
        }

        return $data;
    }
}
