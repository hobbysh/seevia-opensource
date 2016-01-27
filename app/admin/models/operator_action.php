<?php

/*****************************************************************************
 * svsys 操作员操作权限模型
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
class OperatorAction extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';

    public $name = 'OperatorAction';
    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('OperatorActionI18n' => array('className' => 'OperatorActionI18n',
                              'conditions' => '',
                              'order' => 'OperatorAction.id',
                              'dependent' => true,
                              'foreignKey' => 'operator_action_id',
                        ),
                  );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " OperatorActionI18n.locale = '".$locale."'";
        $this->hasOne['OperatorActionI18n']['conditions'] = $conditions;
    }

    public function localeformat($id)
    {
        $node['config'] = 'node';
        $node['use'] = true;
        $lists = $this->find('all', array('cache' => $node, 'conditions' => array('OperatorAction.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['OperatorAction'] = $v['OperatorAction'];
            $lists_formated['OperatorActionI18n'][] = $v['OperatorActionI18n'];
            foreach ($lists_formated['OperatorActionI18n'] as $key => $val) {
                $lists_formated['OperatorActionI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    public $acionts_parent_format = array();
    public function alltree_hasname()
    {
        $conditions['OperatorAction.status'] = 1;
        $actions = $this->find('all', array('conditions' => $conditions, 'order' => 'orderby asc'));
        $this->acionts_parent_format = array();
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                $v['OperatorAction']['name'] = $v['OperatorActionI18n']['name'];
                $this->acionts_parent_format[$v['OperatorAction']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }
    /*
    *获取权限结构树
    */
    public function tree($locale = 'chi')
    {
        $this->acionts_parent_format = array();
        $this->set_locale($locale);
        $conditions['OperatorActionI18n.locale'] = $locale;
        $conditions['OperatorAction.status'] = '1';
        $cond['conditions'] = $conditions;

        $cond['order'] = array('OperatorAction.orderby asc,OperatorAction.created asc');
        $action_list = $this->find('all', $cond);

        if (is_array($action_list)) {
            foreach ($action_list as $k => $v) {
                $this->acionts_parent_format[$v['OperatorAction']['parent_id']][] = $v;
            }
        }
        //pr($this->subcat_get(0));
        return $this->subcat_get(0);
    }
    public function alltree()
    {
        //
        $conditions = '';
        $actions = $this->find('all', array('conditions' => $conditions, 'order' => 'orderby asc'));
        $this->acionts_parent_format = array();
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                $this->acionts_parent_format[$v['OperatorAction']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }

    public function subcat_get($action_id)
    {
        $subcat = array();
        if (isset($this->acionts_parent_format[$action_id]) && is_array($this->acionts_parent_format[$action_id])) {
            //判断parent_id = 0 的数据
            foreach ($this->acionts_parent_format[$action_id] as $k => $v) {
                $action = $v;//parent_id 为 0 的数据
                if (isset($this->acionts_parent_format[$v['OperatorAction']['id']]) && is_array($this->acionts_parent_format[$v['OperatorAction']['id']])) {
                    $action['SubAction'] = $this->subcat_get($v['OperatorAction']['id']);
                } else {
                }
                $subcat[$k] = $action;
            }
        }

        return $subcat;
    }
}
