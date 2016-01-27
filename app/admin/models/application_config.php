<?php

/**
 *  �������ģ��.
 */
class ApplicationConfig extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'default';
    /*
     * @var $name Config �̵����ñ�
     */
    public $name = 'ApplicationConfig';
    public $locale = '';
    public $hasMany = array(
        'ApplicationConfigI18n' => array(
                'className' => 'ApplicationConfigI18n',
                'order' => '',
                'dependent' => true,
                'foreignKey' => 'app_config_id',
        ),
    );

    /**
     * localeformat����������ṹ����.
     *
     * @param array $params id,app_id,group_code
     *
     * @return array $lists_formated ���������������Ե���Ϣ
     */
    public function localeformat($params)
    {
        $cond = array();
        if (isset($params['id'])) {
            $cond['ApplicationConfig.id'] = $params['id'];
        }
        if (isset($params['app_id'])) {
            $cond['ApplicationConfig.app_id'] = $params['app_id'];
        }
        if (isset($params['group_code'])) {
            $cond['ApplicationConfig.group_code'] = $params['group_code'];
        }
        if (isset($params['is_readyonly'])) {
            $cond['ApplicationConfig.type !='] = 'read_only';
        }
        $lists = $this->find('all', array('conditions' => $cond, 'order' => 'ApplicationConfig.orderby'));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated_data = array();
            $lists_formated_data['ApplicationConfig'] = $v['ApplicationConfig'];
            foreach ($v['ApplicationConfigI18n'] as $kk => $vv) {
                $lists_formated_data['ApplicationConfigI18n'][$vv['locale']] = $vv;
            }
            $lists_formated[] = $lists_formated_data;
        }
        if (!empty($lists_formated) && sizeof($lists_formated) > 0) {
            $group_lists_formated = array();
            foreach ($lists_formated as $k => $v) {
                $group_lists_formated[$v['ApplicationConfig']['subgroup_code']][] = $v;
            }
            $lists_formated = $group_lists_formated;
        }

        return $lists_formated;
    }
}
