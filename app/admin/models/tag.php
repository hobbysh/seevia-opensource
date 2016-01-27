<?php

/*****************************************************************************
 * svsys 标签
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
class tag extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    public $name = 'Tag';
    public $hasOne = array('TagI18n' => array('className' => 'TagI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'tag_id',
                        ),
                  );

    public function set_locale($locale)
    {
        $conditions = " TagI18n.locale = '".$locale."'";
        $this->hasOne['TagI18n']['conditions'] = $conditions;
    }

    //数组结构调整
    public function localeformat($type_id, $type)
    {
        $lists = $this->find('all', array('conditions' => array('Tag.type_id' => $type_id, 'Tag.type' => $type)));
        //pr($lists);
        $lists_formated = array();
        if (!empty($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['TagI18n']['locale']][] = $v['TagI18n']['name'];
            }
        }

        return $lists_formated;
    }
}
