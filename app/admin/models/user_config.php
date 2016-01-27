<?php

/*****************************************************************************
 * svoms  �û�����ģ��
 * ===========================================================================
 * ��Ȩ����  �Ϻ�ʵ������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�ã�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ===========================================================================
 * $����: �Ϻ�ʵ��$
 * $Id$
*****************************************************************************/
class UserConfig extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name UserConfig �û�����
     */
    public $name = 'UserConfig';

    /*
     * @var $hasOne array ������������Ա�
     */
    public $hasOne = array('UserConfigI18n' => array('className' => 'UserConfigI18n',
            'conditions' => '',
            'order' => 'UserConfig.orderby asc',
            'dependent' => true,
            'foreignKey' => 'user_config_id',
        ),
    );

    /**
     * set_locale�������������Ի���.
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
     * localeformat�������ṹ����.
     *
     * @param int $id �������±��
     *
     * @return array $lists_formated ����Ʒ���������Ե���Ϣ
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
