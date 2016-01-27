<?php

/*****************************************************************************
 * svsys 信息库模型
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
class InformationResource extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'default';
    /*
     * @var $name InformationResource 信息库
     */
    public $name = 'InformationResource';
    //var $useTable = 'information_resources'; 
    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('InformationResourceI18n' => array('className' => 'InformationResourceI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'information_resource_id',
                        ),
                    );

    public $acionts_parent_format = array();

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " InformationResourceI18n.locale = '".$locale."'";
        $this->hasOne['InformationResourceI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 输入分类编号
     *
     * @return array $lists_formated 返回信息库所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('InformationResource.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['InformationResource'] = $v['InformationResource'];
            $lists_formated['InformationResourceI18n'][] = $v['InformationResourceI18n'];
            foreach ($lists_formated['InformationResourceI18n'] as $key => $val) {
                $lists_formated['InformationResourceI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    /**
     * information_formated方法，指定代码信息库数据获取.
     *
     * @param string $code        信息代码数组
     * @param string $locale      语言代码
     * @param bool   $parent_show 是否包括父信息
     *
     * @return array $sr_parent_data 返回信息库所有语言的信息
     */
    public function information_formated($code, $locale, $parent_show = true)
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
    //$info=$this->find("all");
    //pr($info);
    //pr(	$system_resource_data);
        $sr_parent_data = array();
        foreach ($system_resource_data as $k => $v) {
            $sr_parent_data[$system_resource_data_parent_format[$v['InformationResource']['parent_id']]['InformationResource']['code']][$v['InformationResource']['information_value']] = $v['InformationResourceI18n']['name'];
        }

        return $sr_parent_data;
    }
    /**
     * all_information_formated方法，指定代码信息库数据获取多语言的数据.
     *
     * @param string $code 信息代码数组
     * @
     *
     * @param bool $parent_show 是否包括父信息
     *
     * @return array $all_lang_resources 返回信息库所有语言的信息多语言
     */
    public function all_information_formated($code)
    {
        $condition = '';
        $condition['InformationResource.code'] = $code;
        $system_resource_data_parent = $this->find('all', array('conditions' => $condition, 'fields' => array('InformationResource.id', 'InformationResource.code'), 'order' => 'InformationResource.orderby,InformationResource.id'));
        $parent_id_array = array();
        $system_resource_data_parent_format = array();
        foreach ($system_resource_data_parent as $k => $v) {
            $parent_id_array[] = $v['InformationResource']['id'];
            $system_resource_data_parent_format[$v['InformationResource']['id']] = $v['InformationResource']['code'];
        }
        $condition = '';
        $condition['InformationResource.parent_id'] = $parent_id_array;
        $system_resource_data = $this->find('all', array('conditions' => $condition, 'order' => 'InformationResource.orderby,InformationResource.id'));
        $all_lang_resources = array();
        foreach ($system_resource_data as $v) {
            foreach ($v['InformationResourceI18n'] as $vv) {
                $all_lang_resources[$system_resource_data_parent_format[$v['InformationResource']['parent_id']]][$vv['locale']][$v['InformationResource']['information_value']] = $vv['name'];
                $all_lang_resource_id_array['id'] = $v['InformationResource']['parent_id'];
                $all_lang_resource_id_array[$v['InformationResource']['information_value']][$vv['locale']] = $vv['id'];
                $all_lang_resources[$system_resource_data_parent_format[$v['InformationResource']['parent_id']].'_id_array'] = $all_lang_resource_id_array;
            }
        }
            //	pr($all_lang_resources);

        return $all_lang_resources;
    }

    public function tree($cond)
    {
        $actions = $this->find('all', $cond);
        $this->acionts_parent_format = array();
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                $this->acionts_parent_format[$v['InformationResource']['parent_id']][] = $v;
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

                if (isset($this->acionts_parent_format[$v['InformationResource']['id']]) && is_array($this->acionts_parent_format[$v['InformationResource']['id']])) {
                    $action['SubMenu'] = $this->subcat_get($v['InformationResource']['id']);
                }
                $subcat[$k] = $action;
            }
        }
        //pr($subcat);
        return $subcat;
    }
}
