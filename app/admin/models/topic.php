<?php

/*****************************************************************************
 * svsys 专题
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
class topic extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    public $name = 'Topic';
    public $hasOne = array('TopicI18n' => array('className' => 'TopicI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'topic_id',
                        ),
                  );

    public function set_locale($locale)
    {
        $conditions = " TopicI18n.locale = '".$locale."'";
        $this->hasOne['TopicI18n']['conditions'] = $conditions;
    }

    //数组结构调整
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => "Topic.id = '".$id."'"));
        $lists_formated = array();
        //pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['Topic'] = $v['Topic'];
            $lists_formated['TopicI18n'][] = $v['TopicI18n'];
            foreach ($lists_formated['TopicI18n'] as $key => $val) {
                $lists_formated['TopicI18n'][$val['locale']] = $val;
            }
        }
        //pr($lists_formated);
        return $lists_formated;
    }
}
