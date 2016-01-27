<?php

/*****************************************************************************
 * svcms  �����ģ��
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
class page extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'cms';

    /*
     * @var $name �����
     */
    public $name = 'Page';
    /*
     * @var $hasOne array ���µĶ�����ģ��
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
    //����ṹ����
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
