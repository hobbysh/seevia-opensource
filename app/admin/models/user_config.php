<?php

/*****************************************************************************
 * svoms  用户配置模型
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
class UserConfig extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name UserConfig 用户配置
     */
    public $name = 'UserConfig';

    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('UserConfigI18n' => array('className' => 'UserConfigI18n',
            'conditions' => '',
            'order' => 'UserConfig.orderby asc',
            'dependent' => true,
            'foreignKey' => 'user_config_id',
        ),
    );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions ['UserConfigI18n.locale'] = $locale;
        $this->locale = $locale;
        $this->hasOne['UserConfigI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，结构调整.
     *
     * @param int $id 输入文章编号
     *
     * @return array $lists_formated 返回品牌所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('UserConfig.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['UserConfig'] = $v['UserConfig'];
            $lists_formated['UserConfigI18n'][] = $v['UserConfigI18n'];
            foreach ($lists_formated['UserConfigI18n'] as $key => $val) {
                $lists_formated['UserConfigI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
}
