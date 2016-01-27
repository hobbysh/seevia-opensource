<?php

/**
 * 多语言数据字典模型.
 */
class LanguageDictionary extends AppModel
{
    public $useDbConfig = 'default';
    public $useTable = 'dictionaries';
    /*
     * @var $name LanguageDictionary 语言代码表
     */
    public $name = 'LanguageDictionary';
    /*
     * @var $cacheQueries true 缓存是否开启：是。
     */
    public $cacheQueries = true;
    /**
     * getformatcode方法，获语言种类代码.
     *
     * @param $locale 输入语言代码
     *
     * @return $languages_formatcode 返回语言内容
     */
    public function getformatcode($locale)
    {
        $node['config'] = 'node';
        $node['use'] = true;
            //	$languages = $this->findallbylocale($locale);
            $languages = $this->find('all', array('fields' => array('LanguageDictionary.name', 'LanguageDictionary.value'), 'conditions' => array('locale' => $locale, 'location' => 'front')));
        $languages_formatcode = array();
        if (is_array($languages)) {
            foreach ($languages as $v) {
                $languages_formatcode[$v['LanguageDictionary']['name']] = $v['LanguageDictionary']['value'];
            }
        }

        return $languages_formatcode;
    }

    public function getformatcodewap($locale)
    {
        $node['config'] = 'node';
        $node['use'] = true;
            //	$languages = $this->findallbylocale($locale);
            $languages = $this->find('all', array('fields' => array('LanguageDictionary.name', 'LanguageDictionary.value'), 'conditions' => array('locale' => $locale, 'location' => 'wap')));
        $languages_formatcode = array();
        if (is_array($languages)) {
            foreach ($languages as $v) {
                $languages_formatcode[$v['LanguageDictionary']['name']] = $v['LanguageDictionary']['value'];
            }
        }

        return $languages_formatcode;
    }
}
