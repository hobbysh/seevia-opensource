<?php

/*****************************************************************************
 * svsys 实体店
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
    //var $useDbConfig ='sys';
    public $name = 'PageModule';

    public $hasOne = array('PageModuleI18n' => array('className' => 'PageModuleI18n',
                              'conditions' => '',
                              'order' => 'PageModule.id',
                              'dependent' => true,
                              'foreignKey' => 'module_id',
                        ),
                  );

    /**
     * set_locale方法，.
     *
     * @param $locale
     */
    public function set_locale($locale)
    {
        $conditions = " PageModuleI18n.locale = '".$locale."'";
        $this->hasOne['PageModuleI18n']['conditions'] = $conditions;
    }
    /**
     * tree方法，.
     *
     * @param $locale
     * @param $conditions
     *
     * @return 
     */
    public function tree($locale, $conditions)
    {
        $this->modules_parent_format = array();
        $this->set_locale($locale);
        $modules = $this->find('all', array('conditions' => $conditions, 'order' => 'PageModule.orderby asc,PageModule.created asc'));
        if (is_array($modules)) {
            foreach ($modules as $k => $v) {
                $this->modules_parent_format[$v['PageModule']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }
    /**
     * parent_tree方法，.
     *
     * @param $locale
     * @param $code
     *
     * @return 
     */
    public function parent_tree($locale, $code)
    {
        $this->modules_parent_format = array();
        $this->set_locale($locale);
        if (!empty($code)) {
            $modules = $this->find('all', array('conditions' => array('PageModule.type' => 'module_parent', 'PageModule.code' => $code), 'order' => 'PageModule.orderby asc,PageModule.created asc'));
        } else {
            $modules = $this->find('all', array('conditions' => array('PageModule.type' => 'module_parent'), 'order' => 'PageModule.orderby asc,PageModule.created asc'));
        }
        if (is_array($modules)) {
            foreach ($modules as $k => $v) {
                $this->modules_parent_format[$v['PageModule']['parent_id']][] = $v;
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
        if (isset($this->modules_parent_format[$category_type_id]) && is_array($this->modules_parent_format[$category_type_id])) {
            //判断parent_id = 0 的数据
            foreach ($this->modules_parent_format[$category_type_id]as $k => $v) {
                $PageModule = $v; //parent_id 为 0 的数据
                if (isset($this->modules_parent_format[$v['PageModule']['id']]) && is_array($this->modules_parent_format[$v['PageModule']['id']])) {
                    $PageModule['SubPageModule'] = $this->subcat_get($v['PageModule']['id']);
                }
                $subcat[$k] = $PageModule;
            }
        }

        return $subcat;
    }
    /** localeformat 数组结构调整
     *@param $id id
     *return $lists_formated
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('PageModule.id' => $id)));
    //	pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['PageModule'] = $v['PageModule'];
            $lists_formated['PageModuleI18n'][] = $v['PageModuleI18n'];
            foreach ($lists_formated['PageModuleI18n'] as $key => $val) {
                $lists_formated['PageModuleI18n'][$val['locale']] = $val;
            }
        }
    //	pr($lists_formated);
        return $lists_formated;
    }
    /** get_flash_module 获取轮播的模块数据
     *return $flash_module_info.
     */
    public function get_flash_module($locale)
    {
        $this->set_locale($locale);
        $info = $this->find('all', array('conditions' => array('PageModule.type' => 'module_flash'), 'fields' => 'PageModule.id,PageModuleI18n.name'));
        $flash_module_info = array();
        foreach ($info as $m) {
            $flash_module_info[$m['PageModule']['id']] = $m['PageModuleI18n']['name'];
        }

        return $flash_module_info;
    }
}
