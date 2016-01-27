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
class store extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'Store';

    public $hasOne = array('StoreI18n' => array('className' => 'StoreI18n',
                              'conditions' => '',
                              'order' => 'Store.id',
                              'dependent' => true,
                              'foreignKey' => 'store_id',
                        ),
                  );

    public function set_locale($locale)
    {
        $conditions = " StoreI18n.locale = '".$locale."'";
        $this->hasOne['StoreI18n']['conditions'] = $conditions;
    }

    //数组结构调整
    public function localeformat($id)
    {
        $lists_formated = array();
        $lists = $this->find('all', array('conditions' => array('Store.id' => $id)));
        foreach ($lists as $k => $v) {
            $lists_formated['Store'] = $v['Store'];
            $lists_formated['StoreI18n'][] = $v['StoreI18n'];
            foreach ($lists_formated['StoreI18n'] as $key => $val) {
                $lists_formated['StoreI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    //去除没权限的店铺
    public function store_operator($store_list)
    {
        foreach ($store_list as $k => $v) {
            $ware_operators = array();
            $ware_operators = explode(',', $v['Store']['operator_id']);
            if (!in_array($_SESSION['Operator_Info']['Operator']['id'], $ware_operators)) {
                unset($store_list[$k]);
            }
        }

        return $store_list;
    }
}
