<?php

/*****************************************************************************
 * svoms  材料管理模型
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
*****************************************************************************/
class material extends AppModel
{
    public $useDbConfig = 'oms';
    public $name = 'Material';

    public $hasOne = array('MaterialI18n' => array('className' => 'MaterialI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'product_material_id',
            ),
    );
    public function set_locale($locale)
    {
        $conditions = " MaterialI18n.locale = '".$locale."'";
        $this->hasOne['MaterialI18n']['conditions'] = $conditions;
    }

    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Material.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Material'] = $v['Material'];
            $lists_formated['MaterialI18n'][] = $v['MaterialI18n'];
            foreach ($lists_formated['MaterialI18n'] as $key => $val) {
                $lists_formated['MaterialI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    /*
    *获取产品分类结构树
    */
    public function tree($locale = 'chi')
    {
        $material_tree = array();
        $this->set_locale($locale);
        $conditions['MaterialI18n.locale'] = $locale;
        $conditions['Material.status'] = '1';
        $cond['conditions'] = $conditions;
        $cond['fields'] = array('Material.id','Material.code','Material.quantity','Material.unit','Material.orderby','Material.status','MaterialI18n.name');
        $cond['order'] = array('Material.orderby asc,Material.created asc');
        $Material_list = $this->find('all', $cond);
        if (is_array($Material_list)) {
            foreach ($Material_list as $k => $v) {
                $material_tree[$v['Material']['code']] = $v['MaterialI18n']['name'];
            }
        }

        return $material_tree;
    }
}
