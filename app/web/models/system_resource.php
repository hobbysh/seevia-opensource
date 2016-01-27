<?php

/**
 * 系统资源模型.
 */
class SystemResource extends AppModel
{
    /*
     * @var $name SystemResource 系统资源库
     */            public $useDbConfig = 'default';
    public $useTable = 'resources';
    public $name = 'SystemResource';
    /**/
    public $acionts_parent_format = array();
    public $hasOne = array('SystemResourceI18n' => array('className' => 'SystemResourceI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'resource_id',
        ),
    );

    /**
     * 函数tree 系统资源.
     *
     * @param $actions 参数
     * @param $conditions 条件
     * @param $actions_arr 整合数
     *
     * @return 系统资源信息
     */
    public function tree($actions = 'all')
    {
        //
        $conditions = " status ='1' ";
        if ($actions != 'all') {
            $conditions .= 'AND Operator_menu.operator_action_code in('.$actions.')';
        }
        $actions_arr = $this->findAll($conditions, '', 'orderby asc');
        $this->acionts_parent_format = array(); //先致空
        if (is_array($actions_arr)) {
            foreach ($actions_arr as $k => $v) {
                $v['SystemResource']['name'] = $v['SystemResourceI18n']['name'];
                $this->acionts_parent_format[$v['SystemResource']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get('0');
    }

    /**
     * 函数alltree 所有系统资源.
     *
     * @param $condition 条件参数
     * @param $filed 所属文档
     * @param $order 排列
     * @param $rownum 行数
     * @param $page 页码
     * @param $actions 所有信息
     *
     * @return 所有系统资源信息
     */
    public function alltree($condition, $filed, $order, $rownum, $page)
    {
        //
        $actions = $this->findAll();
        $this->acionts_parent_format = array(); //先致空
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                // $v['SystemResource']['name'] = $v['SystemResourceI18n']['name'];
                $this->acionts_parent_format[$v['SystemResource']['parent_id']][] = $v;
            }
        }
        //	pr($this->acionts_parent_format);exit;
        return $this->subcat_get('0');
    }

    /**
     * 函数subcat_get 系统资源信息.
     *
     * @param $action_id 参数
     * @param $subcat 数组
     *
     * @return $subcat 菜单信息
     */
    public function subcat_get($action_id)
    {
        $subcat = array();
        //echo $action_id;
        //pr($this->acionts_parent_format);
        if (isset($this->acionts_parent_format[$action_id]) && is_array($this->acionts_parent_format[$action_id])) {
            foreach ($this->acionts_parent_format[$action_id] as $k => $v) {
                $action = $v;

                if (isset($this->acionts_parent_format[$v['SystemResource']['id']]) && is_array($this->acionts_parent_format[$v['SystemResource']['id']])) {
                    $action['SubMenu'] = $this->subcat_get($v['SystemResource']['id']);
                } else {
                    $action['SubMenu'] = '';
                }
                $subcat[$k] = $action;
            }
        }
        //pr($action);
        return $subcat;
    }

    /**
     * 函数localeformat 语言类型.
     *
     * @param $id 系统资源号
     * @param $lists 列表
     * @param $lists_formated 语言类型
     *
     * @return $lists_formated 语言类型
     */
    public function localeformat($id)
    {
        $lists = $this->findAll("SystemResource.id = '".$id."'");
        //	pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['SystemResource'] = $v['SystemResource'];
            $lists_formated['SystemResourceI18n'][] = $v['SystemResourceI18n'];
            foreach ($lists_formated['SystemResourceI18n'] as $key => $val) {
                $lists_formated['SystemResourceI18n'][$val['locale']] = $val;
            }
        }
        //	pr($lists_formated);
        return $lists_formated;
    }

    /**
     * 函数find_tree 查看系统资源.
     *
     * @param $condition 参数
     * @param $conditions 系统资源信息
     * @param $tree 系统资源号
     *
     * @return $tree 系统资源信息
     */
    public function find_tree($condition)
    {
        $conditions = $this->find($condition);
        $tree = $this->find('parent_id = '.$conditions['SystemResource']['id']);

        return $tree;
    }

//leo20090626
    /**
     * 函数resource_formated 系统资源列表.
     *
     * @param $mystatus 生分默认为TRUE
     * @param $locale 语言编码
     * @param $actions 专题资源信息
     *
     * @return $redource 资源信息
     */
    public function resource_formated($mystatus = true, $locale)
    {
        $conditions = '';
//	$actions=$this->findAll($conditions,'','SystemResource.orderby asc');
        $actions = $this->find('all', array('cache' => $this->short, 'orderby' => 'SystemResource.orderby asc',
                    'fields' => array('SystemResource.id', 'SystemResource.parent_id', 'SystemResource.code', 'SystemResource.resource_value', 'SystemResourceI18n.resource_id', 'SystemResourceI18n.name'),
                    'conditions' => array($conditions), ), $this->name.'resource_formated'.$locale);
        $this->acionts_parent_format = array();
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                $this->acionts_parent_format[$v['SystemResource']['parent_id']][] = $v;
            }
            $redource = array();
            foreach ($this->subcat_get('0') as $k => $v) {
                if ($mystatus) {
                    $redource[$v['SystemResource']['code']][$v['SystemResource']['resource_value']] = $v['SystemResourceI18n']['name'];
                }
                if (!empty($v['SubMenu'])) {
                    foreach ($v['SubMenu'] as $kk => $vv) {
                        $redource[$v['SystemResource']['code']][$vv['SystemResource']['resource_value']] = $vv['SystemResourceI18n']['name'];
                    }
                }
            }

            return $redource;
        }
    }
}
