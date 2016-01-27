<?php

/**
 * 信息资源模型.
 */
class InformationResource extends AppModel
{
    //var $useDbConfig ='default_base';
     //var $useTable = 'information_resources'; 
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'default';
    /*
     * @var $name InformationResource 资源管理表
     */
    public $name = 'InformationResource';
    /*
     * @var $actions_parent_format array 制作分类树用的
     */
    public $acionts_parent_format = array();
    /*
     * @var $hasOne array 关联资源管理多语言表
     */
    public $hasOne = array('InformationResourceI18n' => array('className' => 'InformationResourceI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'information_resource_id',
        ),
    );

    /**
     * tree方法，主键设置.
     *
     * @param $actions 输入方法
     *
     * @return $this->subcat_get('0') 返回资源上级id为0的数据
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
     * alltree方法,所有的主键设置.
     *
     * @param $condition 输入条件
     * @param $filed 输入提交
     * @param $order 输入命令
     * @param $rownum 输入给出
     * @param $page 输入页
     *
     * @return $this->subcat_get('0') 返回 返回资源上级id为0的数据
     */
    public function alltree($condition, $filed, $order, $rownum, $page)
    {
        //
        $actions = $this->findAll();
        $this->acionts_parent_format = array(); //先致空
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                // $v['SystemResource']['name'] = $v['SystemResourceI18n']['name'];
                $this->acionts_parent_format[$v['InformationResource']['parent_id']][] = $v;
            }
        }
        //	pr($this->acionts_parent_format);exit;
        return $this->subcat_get('0');
    }

    /**
     * subcat_get方法，获取所输入id相对应的数据.
     *
     * @param $action_id 输入id
     *
     * @return $subcat 返回id所对应的数据，如果存在并且为数组则赋值，否则为空。
     */
    public function subcat_get($action_id)
    {
        $subcat = array();
        //echo $action_id;
        //pr($this->acionts_parent_format);
        if (isset($this->acionts_parent_format[$action_id]) && is_array($this->acionts_parent_format[$action_id])) {
            foreach ($this->acionts_parent_format[$action_id] as $k => $v) {
                $action = $v;

                if (isset($this->acionts_parent_format[$v['InformationResource']['id']]) && is_array($this->acionts_parent_format[$v['InformationResource']['id']])) {
                    $action['SubMenu'] = $this->subcat_get($v['InformationResource']['id']);
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
     * localeformat方法，返回语言所对应的数据.
     *
     * @param $id 输入id
     *
     * @return $lists_formated 检索对应id的数据，
     */
    public function localeformat($id)
    {
        $lists = $this->findAll("InformationResource.id = '".$id."'");
        //	pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['InformationResource'] = $v['InformationResource'];
            $lists_formated['InformationResourceI18n'][] = $v['InformationResourceI18n'];
            foreach ($lists_formated['InformationResourceI18n'] as $key => $val) {
                $lists_formated['InformationResourceI18n'][$val['locale']] = $val;
            }
        }
        //	pr($lists_formated);
        return $lists_formated;
    }

    /**
     * find_tree方法，查找主键.
     *
     * @param $condition 输入条件
     *
     * @return $tree 返回主键
     */
    public function find_tree($condition)
    {
        $node['config'] = 'node';
        $node['use'] = true;
        $conditions = $this->find('all', array('conditions' => $conditions, 'cache' => $node));
        $tree = $this->find('all', array('conditions' => array('parent_id' => $conditions['InformationResource']['id']), 'cache' => $node));

        return $tree;
    }

//leo20090626
    /**
     * information_formated方法，数据格式化.
     *
     * @param $mystatus 输入条件
     * @param $locale 输入语言
     *
     * @return $redource 返回关于相对应得资源代码
     */
    public function information_formated($mystatus = true, $locale)
    {
        $conditions = '';
        $node['config'] = 'node';
        $node['use'] = true;
        //	$actions=$this->findAll($conditions,'','InformationResource.orderby asc');
        $actions = $this->find('all', array('orderby' => 'InformationResource.orderby asc',
                    'fields' => array('InformationResource.id', 'InformationResource.parent_id', 'InformationResource.code', 'InformationResource.information_value', 'InformationResourceI18n.name', 'InformationResourceI18n.description', 'InformationResourceI18n.id'),
                    'conditions' => array($conditions), ), $this->name.'resource_formated'.$locale);
        $this->acionts_parent_format = array();
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                $this->acionts_parent_format[$v['InformationResource']['parent_id']][] = $v;
            }
            $redource = array();
            foreach ($this->subcat_get('0') as $k => $v) {
                if (isset($v['InformationResourceI18n']) && $v['InformationResourceI18n'] != '') {
                    if ($mystatus) {
                        $redource[$v['InformationResource']['code']][$v['InformationResourceI18n']['id']] = $v['InformationResourceI18n']['name'];
                    }
                    if (!empty($v['SubMenu'])) {
                        foreach ($v['SubMenu'] as $kk => $vv) {
                            $redource[$v['InformationResource']['code']][$vv['InformationResourceI18n']['id']] = $vv['InformationResourceI18n']['name'];
                        }
                    }
                }
            }

            return $redource;
        }
    }
    /**
     * code_information_formated方法，指定代码信息库数据获取.
     *
     * @param string $code        信息代码数组
     * @param string $locale      语言代码
     * @param bool   $parent_show 是否包括父信息
     *
     * @return array $sr_parent_data 返回信息库所有语言的信息
     */
    public function code_information_formated($code, $locale, $parent_show = true)
    {
        $condition = '';
        $condition['InformationResource.code'] = $code;
        $condition['InformationResourceI18n.locale'] = $locale;
        $system_resource_data_parent = $this->find('all', array('conditions' => $condition, 'fields' => array('InformationResource.id', 'InformationResource.code')));
        $parent_id_array = array();
        $system_resource_data_parent_format = array();
        foreach ($system_resource_data_parent as $k => $v) {
            $parent_id_array[] = $v['InformationResource']['id'];
            $system_resource_data_parent_format[$v['InformationResource']['id']] = $v;
        }
        $condition = '';
        $condition['InformationResource.parent_id'] = $parent_id_array;
        $condition['InformationResourceI18n.locale'] = $locale;
        $system_resource_data = $this->find('all', array('conditions' => $condition, 'order' => 'InformationResource.orderby asc', 'fields' => array('InformationResource.id', 'InformationResourceI18n.name', 'InformationResource.information_value', 'InformationResource.parent_id', 'InformationResource.code')));
        $sr_parent_data = array();
        foreach ($system_resource_data as $k => $v) {
            $sr_parent_data[$system_resource_data_parent_format[$v['InformationResource']['parent_id']]['InformationResource']['code']][$v['InformationResource']['information_value']] = $v['InformationResourceI18n']['name'];
        }

        return $sr_parent_data;
    }
}
