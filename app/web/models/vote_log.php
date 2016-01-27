<?php

/**
 * 在线调查日志模型.
 */
class VoteLog extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    public $name = 'VoteLog';

    public function get_vote_log($vote_id, $ip_address)
    {
        $vote_log = $this->find('VoteLog.vote_id = '.$vote_id." and VoteLog.ip_address = '".$ip_address."'");

        return $vote_log;
    }
}
