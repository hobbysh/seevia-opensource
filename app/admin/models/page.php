<?php

/*****************************************************************************
 * svcms  广告条模型
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
class page extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';

    /*
     * @var $name 广告条
     */
    public $name = 'Page';
    /*
     * @var $hasOne array 文章的多语言模块
     */
    public $hasOne = array(
            'PageI18n' => array('className' => 'PageI18n',
                                'conditions' => '',
                                'order' => '',
                                'dependent' => true,
                                'foreignKey' => 'page_id',
            ),
    );

    public function set_locale($locale)
    {
        $conditions = " PageI18n.locale = '".$locale."'";
        $this->hasOne['PageI18n']['conditions'] = $conditions;
    }
    //数组结构调整
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => "Page.id = '".$id."'"));
        $lists_formated = array();
        //pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['Page'] = $v['Page'];
            $lists_formated['PageI18n'][] = $v['PageI18n'];
            foreach ($lists_formated['PageI18n'] as $key => $val) {
                $lists_formated['PageI18n'][$val['locale']] = $val;
            }
        }
        //pr($lists_formated);
        return $lists_formated;
    }
}
