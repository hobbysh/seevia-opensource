<?php

/**
 * 邮件模板模型.
 */
class MailTemplate extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';
    /*
     * @var $name MailTemplate 邮件模板表
     */
    public $name = 'MailTemplate';
    /*
     * @var $hasOne array 关联邮件模板多语言表
     */
    public $hasOne = array('MailTemplateI18n' => array('className' => 'MailTemplateI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => 'MailTemplate.id',
            'dependent' => true,
            'foreignKey' => 'mail_template_id',
        ),
    );

    //数组结构调整
    /**
     * localeformat方法，数组结构调整。.
     *
     * @param $id 输入id
     *
     * @return $lists_formated 查找所有与输入id相等的数据，并遍历出来塞到数组中，返回出来。
     */
    public function localeformat($id)
    {
        $lists = $this->findAll("MailTemplate.id = '".$id."'");
        //	pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['MailTemplate'] = $v['MailTemplate'];
            $lists_formated['MailTemplateI18n'][] = $v['MailTemplateI18n'];
            foreach ($lists_formated['MailTemplateI18n'] as $key => $val) {
                $lists_formated['MailTemplateI18n'][$val['locale']] = $val;
            }
        }
        //	pr($lists_formated);
        return $lists_formated;
    }
}
