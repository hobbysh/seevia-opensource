<?php

/**
 * 模板模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 * @var 设置缓存
 * @var 设置缓存时间
 */
class template extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    public $name = 'Template';
    public $cacheQueries = true;
    public $cacheAction = '1 day';

    /**
     * 函数find_template 用于选择模板
     *
     * @param $sql sql语句
     * @param $cache_key 缓存的识别码
     * @param $template 缓存中读取模板
     *
     * @return $template 模板信息
     */
    public function find_template($sql)
    {
        $cache_key = md5($this->name.'_'.$sql);

        $template = cache::read($cache_key);
        if ($template) {
            return $template;
        } else {
            $template = $this->findAll($sql);
            cache::write($cache_key, $template);

            return $template;
        }
    }
}
