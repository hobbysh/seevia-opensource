<?php

/**
 * 公众平台模型.
 */
class OpenModel extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    /*
     * @var $name OpenModel
     */
    public $name = 'OpenModel';

    public function getInfoByOpenType($openType)
    {
        return $this->find('first', array('conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1, 'open_type' => $openType)));
    }

    public function getInfoById($id)
    {
        return $this->find('first', array('conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1, 'id' => $id)));
    }

    public function getInfoByTypeId($openTypeId)
    {
        return $this->find('first', array('conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1, 'open_type_id' => $openTypeId)));
    }
}
