<?php

 /*****************************************************************************
 * svsys 商店设置模型
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
class config extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'default';
    /*
     * @var $name Config 商店设置表
     */
    public $name = 'Config';
    public $locale = '';
    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('ConfigI18n' => array('className' => 'ConfigI18n',
            'conditions' => '',
            'order' => 'Config.orderby asc',
            'dependent' => true,
            'foreignKey' => 'config_id',
        ),
    );
    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions ['ConfigI18n.locale'] = $locale;
        $this->locale = $locale;
        $this->hasOne['ConfigI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 输入分类编号
     *
     * @return array $lists_formated 返回商店设置所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Config.id' => $id), 'order' => 'ConfigI18n.id'));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Config'] = $v['Config'];
            $lists_formated['ConfigI18n'][] = $v['ConfigI18n'];
            foreach ($lists_formated['ConfigI18n'] as $key => $val) {
                $lists_formated['ConfigI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    /**
     * getformatcode方法，获取商店设置参数.
     *
     * @return array $configs_formatcode 返回商店设置供后台使用的信息
     */
    public function getformatcode()
    {
        $configs = $this->find('all', array('conditions' => array('Config.status' => 1), 'fields' => array('Config.code', 'ConfigI18n.value')));
        //pr($configs);
        $configs_formatcode = array();
        if (is_array($configs)) {
            foreach ($configs as $v) {
                $configs_formatcode[$v['Config']['code']] = $v['ConfigI18n']['value'];
            }
        }

        return $configs_formatcode;
    }

    public function getformatcode_all($store_id = 0)
    {
        $condition = " store_id = '".$store_id."'";
        $configs = $this->find('all', array('conditions' => $condition, 'order' => 'orderby asc', 'fields' => array('ConfigI18n.locale', 'Config.code', 'ConfigI18n.value')));
        $configs_formatcode = array();
        if (is_array($configs)) {
            foreach ($configs as $v) {
                $configs_formatcode[$v['ConfigI18n']['locale']][$v['Config']['code']] = $v['ConfigI18n']['value'];
            }
        }

        if (!isset($configs_formatcode['use_sku'])) {
            $configs_formatcode['use_sku'] = 0;
        }

        return $configs_formatcode;
    }
}
