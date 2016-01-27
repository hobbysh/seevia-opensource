<?php

/**
 * 公众平台.
 */
class OpenUserMessage extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    /*
     * @var $name OpenUser
     */
    public $name = 'OpenUserMessage';

    public function getListByUserId($userId)
    {
        $fields = array('OpenModel.img','OpenUserMessage.*','OpenUser.headimgurl');
        $joins = array(
                array('table' => 'svsns_open_users',
                      'alias' => 'OpenUser',
                      'type' => 'inner',
                      'conditions' => array('OpenUser.id = OpenUserMessage.open_user_id'),
                     ),
                array('table' => 'svsns_open_models',
                      'alias' => 'OpenModel',
                      'type' => 'inner',
                      'conditions' => array('OpenModel.open_type_id = OpenUserMessage.open_type_id'),
                     ), );

        $message = $this->find('all', array('fields' => $fields, 'conditions' => array('OpenUserMessage.open_user_id' => $userId), 'order' => 'OpenUserMessage.id desc, OpenUserMessage.created desc', 'joins' => $joins));

        return $message;
    }

    public function getListByOpenTypeId($OpenTypeId)
    {
        $message = array();

        $fields1 = array('OpenKeywordError.*','OpenUser.nickname');
        $joins1 = array(
                array('table' => 'svsns_open_users',
                      'alias' => 'OpenUser',
                      'type' => 'inner',
                      'conditions' => array('OpenUser.id = OpenKeywordError.open_user_id'),
                     ), );

        $OpenKeywordError = ClassRegistry::init('OpenKeywordError');
        $message1 = $OpenKeywordError->find('all', array('conditions' => array('OpenKeywordError.open_type_id' => $OpenTypeId), 'order' => 'OpenKeywordError.created desc', 'joins' => $joins1, 'fields' => $fields1));

        $fields2 = array('OpenUserMessage.*','OpenUser.nickname');
        $joins2 = array(
                array('table' => 'svsns_open_users',
                      'alias' => 'OpenUser',
                      'type' => 'inner',
                      'conditions' => array('OpenUser.id = OpenUserMessage.open_user_id'),
                     ), );

        $message2 = $this->find('all', array('conditions' => array('OpenUserMessage.open_type_id' => $OpenTypeId), 'order' => 'OpenUserMessage.id desc, OpenUserMessage.created desc', 'joins' => $joins2, 'fields' => $fields2));

        $_message = array();

        foreach ($message2 as $k => $v) {
            $message2_data['created'] = $v['OpenUserMessage']['created'];
            $message2_data['OpenUserMessage'] = $v['OpenUserMessage'];
            $message2_data['OpenUser'] = $v['OpenUser'];
            $_message[] = $message2_data;
        }
        foreach ($message1 as $k => $v) {
            $message1_data['created'] = $v['OpenKeywordError']['created'];
            $message1_data['OpenKeywordError'] = $v['OpenKeywordError'];
            $message1_data['OpenUser'] = $v['OpenUser'];
            $_message[] = $message1_data;
        }
        $_message = $this->array_sort($_message, 'created');

        if (sizeof($_message) > 0) {
            foreach ($_message as $k => $v) {
                $message_data['id'] = isset($v['OpenUserMessage']['id']) ? $v['OpenUserMessage']['id'] : $v['OpenKeywordError']['id'];
                $message_data['nickname'] = isset($v['OpenUser']['nickname']) ? $v['OpenUser']['nickname'] : '';
                $message_data['open_type_id'] = isset($v['OpenUserMessage']['open_type_id']) ? $v['OpenUserMessage']['id'] : $v['OpenKeywordError']['open_type_id'];
                $message_data['open_user_id'] = isset($v['OpenUserMessage']['open_user_id']) ? $v['OpenUserMessage']['id'] : $v['OpenKeywordError']['open_user_id'];
                $message_data['send_from'] = isset($v['OpenUserMessage']['send_from']) ? $v['OpenUserMessage']['send_from'] : '1';
                $message_data['msgtype'] = isset($v['OpenUserMessage']['msgtype']) ? $v['OpenUserMessage']['msgtype'] : 'text';
                $message_data['message'] = isset($v['OpenUserMessage']['message']) ? $v['OpenUserMessage']['message'] : $v['OpenKeywordError']['keyword'];
                $message_data['created'] = isset($v['OpenUserMessage']['created']) ? $v['OpenUserMessage']['created'] : $v['OpenKeywordError']['created'];
                $message_data['modified'] = isset($v['OpenUserMessage']['modified']) ? $v['OpenUserMessage']['id'] : $v['OpenKeywordError']['modified'];

                $message_data['type'] = isset($v['OpenUserMessage']) ? 'UM' : 'KE';
                $message[] = $message_data;
            }
        }

        return $message;
    }

    public function array_sort($arr, $keys, $type = 'desc')
    {
        if (!empty($arr) && sizeof($arr) > 0) {
            $keysvalue = $new_array = array();
            foreach ($arr as $k => $v) {
                $keysvalue[$k] = $v[$keys];
            }
            if ($type == 'asc') {
                asort($keysvalue);
            } else {
                arsort($keysvalue);
            }
            reset($keysvalue);
            foreach ($keysvalue as $k => $v) {
                $new_array[$k] = $arr[$k];
            }

            return $new_array;
        } else {
            return array();
        }
    }

    public function saveMsg($msgType, $msg, $openId, $openTypeId, $sendFrom, $return_code, $return_message, $openType = 'wechat')
    {
        $userMsg = array();
        $userMsg['OpenUserMessage']['open_type'] = $openType;
        $userMsg['OpenUserMessage']['open_type_id'] = $openTypeId;
        $userMsg['OpenUserMessage']['open_user_id'] = $openId;
        $userMsg['OpenUserMessage']['send_from'] = $sendFrom;
        $userMsg['OpenUserMessage']['msgtype'] = $msgType;
        $userMsg['OpenUserMessage']['message'] = $msg;
        $userMsg['OpenUserMessage']['return_code'] = $return_code;
        $userMsg['OpenUserMessage']['return_message'] = $return_message;
        $this->saveAll($userMsg);
    }
}
