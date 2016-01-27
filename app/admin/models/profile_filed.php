<?php

class ProfileFiled extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';
    public $name = 'profiles_fields';
    public $hasOne = array(
                    'ProfilesFieldI18n' => array(
                        'className' => 'ProfilesFieldI18n',
                        'order' => '',
                        'dependent' => true,
                        'foreignKey' => 'profiles_field_id',
                    ),
                  );

    public function set_locale($locale)
    {
        $this->hasOne['ProfilesFieldI18n']['conditions'] = " ProfilesFieldI18n.locale = '".$locale."'";
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 输入分类编号
     *
     * @return array $lists_formated 返回Profile所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('ProfileFiled.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['ProfileFiled'] = $v['ProfileFiled'];
            $lists_formated['ProfilesFieldI18n'][] = $v['ProfilesFieldI18n'];
            foreach ($lists_formated['ProfilesFieldI18n']as $key => $val) {
                $lists_formated['ProfilesFieldI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
}
