<?php

/*****************************************************************************
 * svsys 插件设置模型
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
    public $codes = array();

    public $hasMany = array(
        'ApplicationConfig' => array('className' => 'ApplicationConfig',
            'conditions' => '',
            'fields' => '',
            'dependent' => true,
            'foreignKey' => 'app_id',
        ),
        'ApplicationConfigI18n' => array(
                'className' => 'ApplicationConfigI18n',
                'order' => '',
                'dependent' => true,
                'foreignKey' => 'app_id',
        ),
        'ApplicationI18n' => array(
                'className' => 'ApplicationI18n',
                'conditions' => '',
                'order' => '',
                'dependent' => true,
                'foreignKey' => 'app_id',
        ),
    );

    public function config($code, $att_code)
    {
        if (isset($this->Applications[$code]['configs'][$att_code])) {
            return $this->Applications[$code]['configs'][$att_code];
        }

        return;
    }

    public function init($locale)
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

    //所有安装的应用的codes
    public function getallcodes()
    {
        $all = $this->find('all', array('fields' => 'Application.code,Application.status', 'order' => 'Application.created desc'));
        $codes = array();
        foreach ($all as $v) {
            if ($v['Application']['code'] == 'APP-SHOP' && $v['Application']['status'] == 0) {
                continue;
            }
            $codes[] = $v['Application']['code'];
        }

        return $codes;
    }

    //所有安装的应用的ids
    public function getallids()
    {
        $all = $this->find('all', array('fields' => 'Application.id'));
        $ids = array();
        foreach ($all as $v) {
            $ids[] = $v['Application']['id'];
        }

        return $ids;
    }

    //所有安装并且启用的应用的ids
    public function getuseids()
    {
        $all = $this->find('all', array('conditions' => array('Application.status' => 1), 'fields' => 'Application.id'));
        $ids = array();
        foreach ($all as $v) {
            $ids[] = $v['Application']['id'];
        }

        return $ids;
    }
}
