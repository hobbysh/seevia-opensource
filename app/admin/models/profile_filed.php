<?php

class ProfileFiled extends AppModel
{
    /*
    * @var $useDbConfig ���ݿ�����
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
     * localeformat����������ṹ����.
     *
     * @param int $id ���������
     *
     * @return array $lists_formated ����Profile�������Ե���Ϣ
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
