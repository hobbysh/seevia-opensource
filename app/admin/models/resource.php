<?php

/*****************************************************************************
 * svsys  系统资源库模型
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
class resource extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';
    /*
     * @var $name Resource 系统资源库
     */
                //var $useTable ='base_system_resources'; 
                public $name = 'Resource';
    public $locale = '';
    /*
     * @var $hasOne array 关联系统资源库多语言表
     */
    public $acionts_parent_format = array();
    public $hasOne = array('ResourceI18n' => array('className' => 'ResourceI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'resource_id',
                        ),
                    );

    public $cache_config = 'day';

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $this->locale = $locale;
        $conditions ['ResourceI18n.locale'] = $locale;
        $this->hasOne['ResourceI18n']['conditions'] = $conditions;
    }

    public function tree($cond)
    {
        //
        $actions = $this->find('all', $cond);
        $this->acionts_parent_format = array();
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                $this->acionts_parent_format[$v['Resource']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }

    /**
     * resource_formated方法，系统资源数据指定代码信息库数据获取.
     *
     * @param string $code        信息代码数组
     * @param string $locale      语言代码
     * @param bool   $parent_show 是否包括父信息
     *
     * @return array $sr_parent_data 返回系统资源数据指定代码信息库数据获取
     */
    public function getformatcode($code, $locale, $parent_show = true)
    {
        $condition = '';
        $condition['Resource.code'] = $code;
        $condition['Resource.status'] = 1;
        $condition['ResourceI18n.locale'] = $locale;
        $this->locale = $locale;
        $system_resource_data_parent = $this->find('all', array('conditions' => $condition, 'fields' => array('Resource.status', 'Resource.id', 'Resource.code')));
        $parent_id_array = array();
        $system_resource_data_parent_format = array();
        foreach ($system_resource_data_parent as $k => $v) {
            $parent_id_array[] = $v['Resource']['id'];
            $system_resource_data_parent_format[$v['Resource']['id']] = $v;
        }
        $condition = '';
        $condition['Resource.parent_id'] = $parent_id_array;
        $condition['ResourceI18n.locale'] = $locale;
        $condition['Resource.status'] = 1;
        $system_resource_data = $this->find('all', array('conditions' => $condition, 'order' => 'Resource.orderby', 'fields' => array('Resource.id', 'ResourceI18n.name', 'Resource.resource_value', 'Resource.parent_id', 'Resource.code')));

        $sr_parent_data = array();
        foreach ($system_resource_data as $k => $v) {
            $sr_parent_data[$system_resource_data_parent_format[$v['Resource']['parent_id']]['Resource']['code']][$v['Resource']['resource_value']] = $v['ResourceI18n']['name'];
        }

        return $sr_parent_data;
    }

    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Resource.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Resource'] = $v['Resource'];
            $lists_formated['ResourceI18n'][] = $v['ResourceI18n'];
            foreach ($lists_formated['ResourceI18n'] as $key => $val) {
                $lists_formated['ResourceI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    public function find_assoc($condition, $condition2 = array())
    {
        //	$this->set_locale($this->locale);
        $conditions = $this->find('first', array('conditions' => array('code' => $condition)));
        $condition2['Resource.parent_id'] = $conditions['Resource']['id'];
        $tree = $this->find('all', array('conditions' => $condition2));
        $array_assoc = array();
        foreach ($tree as $k => $v) {
            $array_assoc[$v['Resource']['resource_value']] = $v['ResourceI18n']['name'];
        }

        return $array_assoc;
    }

    public function subcat_get($action_id)
    {
        $subcat = array();
        //echo $action_id;
        //pr($this->acionts_parent_format);
        if (isset($this->acionts_parent_format[$action_id]) && is_array($this->acionts_parent_format[$action_id])) {
            foreach ($this->acionts_parent_format[$action_id] as $k => $v) {
                $action = $v;

                if (isset($this->acionts_parent_format[$v['Resource']['id']]) && is_array($this->acionts_parent_format[$v['Resource']['id']])) {
                    //echo 111;
                    $action['SubMenu'] = $this->subcat_get($v['Resource']['id']);
                //pr($action);
                }
                /*
                else{ 
                    $action['SubMenu']='';
                }
                */
                //pr($action);
                $subcat[$k] = $action;
            }
        }
        //pr($subcat);
        return $subcat;
    }
}
