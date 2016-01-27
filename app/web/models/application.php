<?php

/**
 *  插件设置模型.
 */
class application extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'default';
    /*
     * @var $name Config 商店设置表
     */
    public $name = 'Application';
    public $locale = '';
    public $Applications = array();

    public $hasMany = array(
        'ApplicationConfig' => array('className' => 'ApplicationConfig',
            'conditions' => '',
            'fields' => '',
            'dependent' => true,
            'foreignKey' => 'app_id',
        ),
        'ApplicationConfigI18n' => array(
                'className' => 'ApplicationConfigI18n',
                'conditions' => array('locale' => LOCALE),
                'order' => '',
                'dependent' => true,
                'foreignKey' => 'app_id',
        ),
    );
    public function config($code, $att_code)
    {
        return $this->Applications[$code]['configs'][$att_code];
    }
    public function availables()
    {
        return $this->Applications;
    }
    public function init($locale)
    {
        $this->Applications = array();
        $info = array();
        $info = $this->find('all', array('conditions' => array('Application.status' => 1), 'fields' => array('Application.code', 'Application.status')));

        foreach ($info as $v) {
            $this->Applications[$v['Application']['code']]['status'] = $v['Application']['status'];
            foreach ($v['ApplicationConfig'] as $vv) {
                foreach ($v['ApplicationConfigI18n']  as $vvv) {
                    if ($vvv['app_config_id'] == $vv['id'] && $vvv['locale'] == $locale) {
                        $this->Applications[$v['Application']['code']]['configs'][$vv['code']] = $vvv['value'];
                    }
                }
            }
        }
    }
    public function getcodes()
    {
        $all = $this->find('all', array('conditions' => array('Application.status' => 1), 'fields' => 'Application.code'));
        $codes = array();
        foreach ($all as $v) {
            $codes[] = $v['Application']['code'];
        }

        return $codes;
    }

    public function init2($locale = LOCALE)
    {
        $this->Applications = array();
        $info = array();
        $codes = array();

        $info = $this->find('all', array('conditions' => array('Application.status' => 1), 'fields' => array('Application.code', 'Application.status')));

        foreach ($info as $v) {
            $this->Applications[$v['Application']['code']]['status'] = $v['Application']['status'];

            foreach ($v['ApplicationConfig'] as $vv) {
                foreach ($v['ApplicationConfigI18n']  as $vvv) {
                    if ($vvv['app_config_id'] == $vv['id'] && $vvv['locale'] == $locale) {
                        $this->Applications[$v['Application']['code']]['configs'][$vv['code']] = $vvv['value'];
                    }
                }
            }
            if ($v['Application']['code'] == 'APP-SHOP' && $v['Application']['status'] == 0) {
                continue;
            }
            $codes[] = $v['Application']['code'];
        }

        $rs = array();
        $rs['Applications'] = $this->Applications;
        $rs['codes'] = $codes;

        return $rs;
    }
}
