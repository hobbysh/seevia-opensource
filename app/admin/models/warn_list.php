<?php

/*****************************************************************************
 * svsys 警告列表
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
*****************************************************************************/
class WarnList extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'default';
    public $name = 'WarnList';

    public function save_warning($param)
    {
        if (isset($param['type']) && isset($param['type_id'])) {
            if (isset($param['type_param'])) {
                $w = $this->find('first', array('conditions' => array('type' => $param['type'], 'type_id' => $param['type_id'], 'type_param' => $param['type_param'], 'status <>' => 2)));
            } else {
                $w = $this->find('first', array('conditions' => array('type' => $param['type'], 'type_id' => $param['type_id'], 'status <>' => 2)));
            }
            if (empty($w)) {
                $param['times'] = 1;
                $param['last_time'] = date('Y-m-d H:i:s');
                $paran['status'] = 0;

                $this->saveAll(array('WarnList' => $param));
            } else {
                $param['id'] = $w['WarnList']['id'];
                $param['times'] = $w['WarnList']['times'] + 1;
                $param['last_time'] = date('Y-m-d H:i:s');
                $this->save(array('WarnList' => $param));
            }
        }
    }
}
