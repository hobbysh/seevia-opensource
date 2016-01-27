<?php

/*****************************************************************************
 * Seevia 实体店
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
class PageModule extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'default';

    /*
     * @var $name PageModule 参数设置表
     */
    public $name = 'PageModule';

    public $hasOne = array('PageModuleI18n' => array('className' => 'PageModuleI18n',
                              'conditions' => '',
                              'order' => 'PageModule.id',
                              'dependent' => true,
                              'foreignKey' => 'module_id',
                        ),
                  );
    public $PageModules_parent_format = array();

    /**
     * set_locale方法，获得查询条件.
     *
     * @param string $locale 传入语言
     */
    public function set_locale($locale)
    {
        $conditions = " PageModuleI18n.locale = '".$locale."'";
        $this->hasOne['PageModuleI18n']['conditions'] = $conditions;
    }

    /**
     * tree方法，获得树形数组.
     *
     * @param string $locale     传入语言
     * @param array  $conditions 查询条件
     *
     * @return array $$this->subcat_get(0) 根据条件检索相对应的数据并返回树形数组
     */
    public function tree($locale, $conditions = '')
    {
        $conditions['PageModule.status'] = 1;
        $this->PageModules_parent_format = array();
        $this->set_locale($locale);
        $PageModules = $this->find('all', array('conditions' => $conditions, 'order' => 'PageModule.orderby asc,PageModule.created asc'));
        if (is_array($PageModules)) {
            foreach ($PageModules as $k => $v) {
                $this->PageModules_parent_format[$v['PageModule']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }

    /**
     * subcat_get方法，获得subcat.
     *
     * @param int $category_type_id 输入id
     *
     * @return array $subcat 根据id检索相对应的数据并返回
     */
    public function subcat_get($category_type_id)
    {
        $subcat = array();
        if (isset($this->PageModules_parent_format[$category_type_id]) && is_array($this->PageModules_parent_format[$category_type_id])) {
            //判断parent_id = 0 的数据
            foreach ($this->PageModules_parent_format[$category_type_id]as $k => $v) {
                $PageModule = $v; //parent_id 为 0 的数据
                if (isset($this->PageModules_parent_format[$v['PageModule']['id']]) && is_array($this->PageModules_parent_format[$v['PageModule']['id']])) {
                    $PageModule['SubPageModule'] = $this->subcat_get($v['PageModule']['id']);
                }
                $subcat[$k] = $PageModule;
            }
        }

        return $subcat;
    }

    /**
     * localeformat方法，获得lists_formated.
     *
     * @param int $id 输入id
     *
     * @return array $lists_formated 根据id检索相对应的数据并返回
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('PageModule.id' => $id)));
        foreach ($lists as $k => $v) {
            $lists_formated['PageModule'] = $v['PageModule'];
            $lists_formated['PageModuleI18n'][] = $v['PageModuleI18n'];
            foreach ($lists_formated['PageModuleI18n'] as $key => $val) {
                $lists_formated['PageModuleI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    /**
     * get_position_moduels方法，获得position_moduels.
     *
     * @param string $locale        传入语言
     * @param int    $pageaction_id 传入id
     *
     * @return array $lists_formated 根据条件检索相对应的数据并返回
     */
    public function get_position_moduels($locale, $pageaction_id)
    {
        $position_moduels = array();
        $conditions['PageModule.status'] = 1;
        $conditions['PageModule.parent_id'] = 0;
        $conditions['PageModule.page_action_id'] = $pageaction_id;
        $this->set_locale($locale);
        $module_infos = $this->find('all', array('conditions' => $conditions, 'fields' => 'PageModule.code,PageModule.position', 'order' => 'PageModule.orderby asc,PageModule.created asc'));
        if (!empty($module_infos)) {
            foreach ($module_infos as $m) {
                $position_moduels[$m['PageModule']['position']][$m['PageModule']['code']] = $m['PageModule']['code'];
            }
        }

        return $position_moduels;
    }

    /**
     * get_module_infos方法，获得infos.
     *
     * @param string $locale     传入语言
     * @param array  $conditions 查询条件
     *
     * @return array $lists_formated 根据条件检索相对应的数据获取模块详细信息父子关系
     */
    public function get_module_infos($locale, $conditions)
    {
        $code_infos = array();
        $style_codes = array();
        $id_code_infos = array();
        $infos = array();
        $conditions['PageModule.status'] = 1;
        $this->set_locale($locale);
        $module_infos = $this->find('all', array('conditions' => $conditions, 'order' => 'PageModule.orderby'));
        $module_parent_infos = $this->find('list', array('conditions' => array('PageModule.status' => 1, 'PageModule.parent_id' => 0), 'fields' => 'PageModule.id'));
        if (!empty($module_infos)) {
            foreach ($module_infos as $k => $m) {
                //父类状态为0时删除子类
                if ($m['PageModule']['parent_id'] != 0 && !in_array($m['PageModule']['parent_id'], $module_parent_infos)) {
                    unset($module_infos[$k]);
                }

                //$action_ids[] = $m['PageModule']['page_action_id'];
                $code_infos[$m['PageModule']['code']]['type'] = $m['PageModule']['type'];
                $code_infos[$m['PageModule']['code']]['file_name'] = $m['PageModule']['file_name'];
                $code_infos[$m['PageModule']['code']]['type_id'] = $m['PageModule']['type_id'];
                $code_infos[$m['PageModule']['code']]['name'] = $m['PageModuleI18n']['name'];
                $code_infos[$m['PageModule']['code']]['title'] = $m['PageModuleI18n']['title'];
                //$code_infos[$m['PageModule']['code']]['element_type'] = $m['PageModule']['element_type'];
                $code_infos[$m['PageModule']['code']]['function'] = $m['PageModule']['function'];
                $id_code_infos[$m['PageModule']['id']] = $m['PageModule']['code'];
                $id_status_infos[$m['PageModule']['id']] = $m['PageModule']['status'];
            }
        }
        //$infos['action_ids'] = $action_ids;
        $infos['module_infos'] = $module_infos;
        $infos['code_infos'] = $code_infos;
        $infos['id_code_infos'] = $id_code_infos;

        return $infos;
    }
}
