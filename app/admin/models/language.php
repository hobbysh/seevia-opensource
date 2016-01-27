<?php

    /*****************************************************************************
 * svsys 语言模型
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
class language extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'default';
    /*
     * @var $name Language 后台语言
     */
    public $name = 'Language';

    /*
     * @var $info Language 格式数据
     */
    public $info = array();

    /**
     * getinfo方法，取有效语言.
     */
    public function getinfo()
    {
        $this->info['languages'] = $this->find('all');
        $this->info['backend'] = array();
        $this->info['front'] = array();
        $this->info['backend_locales'] = array();
        $this->info['front_locales'] = array();
        foreach ($this->info['languages'] as $k => $v) {
            if ($v['Language']['backend'] == 1) {
                $this->info['backend_locales'][] = $v;
            }
            if ($v['Language']['front'] == 1) {
                $this->info['front_locales'][] = $v;
            }

            $backend_temp[$v['Language']['is_default']][$v['Language']['backend']] = $v['Language'];
            $front_temp[$v['Language']['is_default']][$v['Language']['front']] = $v['Language'];
        }
        $this->info['backend'] = isset($backend_temp['1']['1']) ? $backend_temp['1']['1'] : $backend_temp['0']['1'];
        $this->info['front'] = isset($front_temp['1']['1']) ? $front_temp['1']['1'] : $front_temp['0']['1'];
    }

    public function findalllang_assoc()
    {
        return $this->find('list', array('conditions' => array('Language.backend' => '1'), 'fields' => array('Language.id', 'Language.locale')));
    }
}
