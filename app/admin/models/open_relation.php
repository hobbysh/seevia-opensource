<?php

/**
 * 公众平台.
 */
class OpenRelation extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    /*
     * @var $name OpenUser
     */
    public $name = 'OpenRelation';
    public function getListByUserId($userId)
    {
        return $this->find('all', array('conditions' => array('OpenRelation.open_user_id' => $userId), 'order' => 'OpenRelation.created desc'));
    }

    public function getOpenUserIdListByCode($code, $type, $openType)
    {
        $list = $this->find('all', array('conditions' => array('OpenRelation.type_id' => $code, 'OpenRelation.type' => $type, 'OpenRelation.open_type' => $openType)));
        $openUserIdList = array();
        if (!empty($list)) {
            foreach ($list as $v) {
                if (!in_array($v['OpenRelation']['open_user_id'], $openUserIdList)) {
                    $openUserIdList[] = $v['OpenRelation']['open_user_id'];
                }
            }
        }

        return $openUserIdList;
    }
}
