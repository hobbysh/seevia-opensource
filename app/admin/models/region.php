<?php

/*****************************************************************************
 * svoms  地区模型
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
class region extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Region 地区模型
     */
    public $name = 'Region';
    //var $useTable = 'base_regions';
    /*
     * @var $hasOne array 关联地区多语言表
     */
    public $hasOne = array('RegionI18n' => array(
                    'className' => 'RegionI18n',
                    'order' => '',
                    'dependent' => true,
                    'foreignKey' => 'region_id',
                ),
                        );

    /*
     * @var $region_parents_arr array 组建地区树型用
     */
    public $region_parents_arr = array();
    public $acionts_parent_format = array();

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " RegionI18n.locale = '".$locale."'";
        $this->hasOne['RegionI18n']['conditions'] = $conditions;
    }

    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Region.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Region'] = $v['Region'];
            $lists_formated['RegionI18n'][] = $v['RegionI18n'];
            foreach ($lists_formated['RegionI18n']as $key => $val) {
                $lists_formated['RegionI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    public function tree($cond)
    {
        $actions = $this->find('all', $cond);
        $this->acionts_parent_format = array();
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                $this->acionts_parent_format[$v['Region']['parent_id']][] = $v;
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
                if (isset($this->acionts_parent_format[$v['Region']['id']]) && is_array($this->acionts_parent_format[$v['Region']['id']])) {
                    $action['SubMenu'] = $this->subcat_get($v['Region']['id']);
                }
                $subcat[$k] = $action;
            }
        }

        return $subcat;
    }

    /**
     * getarealist方法，地区列表.
     *
     * @param int    $pid    上一级地区ID
     * @param string $locale 语言代码
     *
     * @return array $lists 返回类型树
     */
    public function getarealist($pid, $locale = 'zh_cn')
    {
        $this->set_locale($locale);
        $condition['Region.parent_id'] = $pid;
        $node['config'] = 'node';
        $node['use'] = true;
        $lists = $this->find('all', array('cache' => $node, 'conditions' => $condition));
        foreach ($lists as $k => $v) {
            if ($v['RegionI18n']['name'] == '') {
                $lists[$k]['RegionI18n']['name'] = '未命名';
            }
        }

        return $lists;
    }

    /**
     * localeformat方法，查找地区.
     *
     * @param int $id 输入查找地区编号
     *
     * @return array $locales_formated 返回查找地区数组
     */
    public function locales_formated($id)
    {
        $node['config'] = 'node';
        $node['use'] = true;
        $condition['Region.id'] = $id;
        $lists = $this->find('all', array('cache' => $node, 'conditions' => $condition));
        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['Region']['id']]['Region'] = $v['Region'];
                if (is_array($v['RegionI18n'])) {
                    $lists_formated[$v['Region']['id']]['RegionI18n'][] = $v['RegionI18n'];
                }
                $lists_formated[$v['Region']['id']]['Region']['name'] = '';
                foreach ($lists_formated[$v['Region']['id']]['RegionI18n'] as $key => $val) {
                    $lists_formated[$v['Region']['id']]['Region']['name'] = $val['name'];
                }
            }
        }

        return $lists_formated;
    }
    public function get_parents($id)
    {
        $condition = " id = '".$id."' ";
        $region = $this->findById($id);
        if (!empty($region)) {
            $this->region_parents_arr[] = array('id' => $region['Region']['id'],'name' => $region['RegionI18n']['name']);
        }
        if (!empty($region['Region']['parent_id'])) {
            return $this->get_parents($region['Region']['parent_id']);
        } else {
            return $this->region_parents_arr;
        }
    }

    public function get_name($locale = 'chi', $id)
    {
        $name = $this->find('first', array('conditions' => array('Region.id' => $id), 'fields' => 'RegionI18n.name'));

        return $name['RegionI18n']['name'];
    }
 
    
/////////
}?>
