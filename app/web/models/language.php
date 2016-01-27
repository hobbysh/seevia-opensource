<?php

/**
 * 语言模型.
 */
class language extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'default';
    /*
     * @var $name Language 语言表
     */
    public $name = 'Language';

    /**
     * findalllang方法，是否在前台显示.
     *
     * @return $language 返回是否在前台显示
     */
    public function findalllang()
    {
        $language = $this->find('all', array('cache' => $this->short, 'conditions' => "Language.front = '1' ", 'order' => 'is_default desc', 'fields' => array('Language.locale', 'Language.is_default', 'Language.front', 'Language.map', 'Language.name', 'Language.google_translate_code')));
        $return = array();
        foreach ($language as $k => $v) {
            $return[$v['Language']['locale']] = $v;
        }

        return $return;
    }

    public function findalllang_assoc()
    {
        return $this->find('list', array('cache' => $this->short, 'conditions' => array('Language.front' => '1'), 'fields' => array('Language.id', 'Language.locale')));
    }
}
