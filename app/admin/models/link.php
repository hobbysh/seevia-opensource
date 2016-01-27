<?php

/*****************************************************************************
 * svcms  友情链接模型
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
class link extends AppModel
{
    public $useDbConfig = 'cms';
    /*
     * @var $name Link 友情链接
     */
    public $name = 'Link';

    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('LinkI18n' => array('className' => 'LinkI18n',
                              'conditions' => '',
                              'order' => 'Link.id',
                              'dependent' => true,
                              'foreignKey' => 'link_id',
                        ),
                  );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " LinkI18n.locale = '".$locale."'";
        $this->hasOne['LinkI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param string $id 输入分类编号
     *
     * @return $lists_formated 返回友情链接所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Link.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Link'] = $v['Link'];
            $lists_formated['LinkI18n'][] = $v['LinkI18n'];
            foreach ($lists_formated['LinkI18n'] as $key => $val) {
                $lists_formated['LinkI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
}
