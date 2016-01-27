<?php

/*****************************************************************************
 * svsns 自定义菜单
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
class OpenMenu extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    public $name = 'OpenMenu';

    public $locale = '';

    public $acionts_parent_format = array();
    public $api_acionts_parent_format = array();

    public function tree($cond)
    {
        $actions = $this->find('all', $cond);
        $this->acionts_parent_format = array();
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                $this->acionts_parent_format[$v['OpenMenu']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }

    public function subcat_get($action_id)
    {
        $subcat = array();
        if (isset($this->acionts_parent_format[$action_id]) && is_array($this->acionts_parent_format[$action_id])) {
            foreach ($this->acionts_parent_format[$action_id] as $k => $v) {
                $action = $v;
                if (isset($this->acionts_parent_format[$v['OpenMenu']['id']]) && is_array($this->acionts_parent_format[$v['OpenMenu']['id']])) {
                    $action['SubMenu'] = $this->subcat_get($v['OpenMenu']['id']);
                }
                $subcat[$k] = $action;
            }
        }

        return $subcat;
    }
}
