<?php

/*****************************************************************************
 * svsys 操作员角色
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
class OperatorRole extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';
    public $name = 'OperatorRole';
    public $hasOne = array('OperatorRoleI18n' => array('className' => 'OperatorRoleI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'operator_role_id',
                        ),
                  );

    public function set_locale($locale)
    {
        $conditions = " OperatorRoleI18n.locale = '".$locale."'";
        $this->hasOne['OperatorRoleI18n']['conditions'] = $conditions;
    }

    public function getRoleItem()
    {
        $data = $this->find('all');
        foreach ($data as $k => $v) {
            $v['OperatorRole']['name'] = '';
            if (!empty($v['OperatorRoleI18n'])) {
                foreach ($v['OperatorRoleI18n'] as $vri) {
                    $v['OperatorRole']['name'] .= $vri['name'].'|';
                }
            } else {
                $v['OperatorRole']['name'] = 'empty';
            }
            //$data[$k] = $v;
            $arr[$v['OperatorRole']['id']] = $v['OperatorRole'];
        }
        //pr($data);
        //return $data;
        return $arr;
    }

    //角色数组结构调整
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('OperatorRole.id' => $id)));
        foreach ($lists as $k => $v) {
            $lists_formated['OperatorRole'] = $v['OperatorRole'];
            $lists_formated['OperatorRoleI18n'][] = $v['OperatorRoleI18n'];
            foreach ($lists_formated['OperatorRoleI18n'] as $key => $val) {
                $lists_formated['OperatorRoleI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
}
