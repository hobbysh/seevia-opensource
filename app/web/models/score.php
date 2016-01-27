<?php

/*****************************************************************************
 * svoms 评分
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
class score extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'Score';
    public $hasOne = array('ScoreI18n' => array('className' => 'ScoreI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'score_id',
                        ),
                  );

    public function set_locale($locale)
    {
        $conditions = " ScoreI18n.locale = '".$locale."'";
        $this->hasOne['ScoreI18n']['conditions'] = $conditions;
    }

    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Score.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Score'] = $v['Score'];
            $lists_formated['ScoreI18n'][] = $v['ScoreI18n'];
            foreach ($lists_formated['ScoreI18n']as $key => $val) {
                $lists_formated['ScoreI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
}
