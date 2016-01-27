<?php

/*****************************************************************************
 * svcms  职位模型
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
class job extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name Job 关于职位的模块
     */
    public $name = 'Job';
    public $hasOne = array('JobI18n' => array('className' => 'JobI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'job_id',
                        ),
                  );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale 语言代码
     */
    public function set_locale($locale)
    {
        $conditions = " JobI18n.locale = '".$locale."'";
        $this->hasOne['JobI18n']['conditions'] = $conditions;
    }
    /**
     * localeformat方法，数组结构调整.
     *
     * @param string $id 输入分类编号
     *
     * @return $lists_formated 返回职位所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Job.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Job'] = $v['Job'];
            $lists_formated['JobI18n'][] = $v['JobI18n'];
            foreach ($lists_formated['JobI18n'] as $key => $val) {
                $lists_formated['JobI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
}
