<?php

/**
 * AppModel类，应用模型.
 */
class AppModel extends Model
{
    public $locale = 'chi';
    public $cache_config = false;

    public function find($type, $params = array())
    {
        //echo $this->locale;
        if (isset($this->cache_config) && $this->cache_config !== false) { //判断是否不读取缓存数据
            $model = isset($this->name) ? '_'.$this->name : 'appmodel';
            $paramsHash = serialize($params);
            $cache_key = md5($model.'_'.$type.'_'.$this->locale.'_'.$paramsHash); //根据模型名称，type参数，多语言，params组成缓存唯一序号
            $data = cache::read($cache_key, $this->cache_config);

            //print_r($data);
            if ($data) {
                return $data;
            } else {
                $data = parent::find($type, $params);
                cache::write($cache_key, $data, $this->cache_config); //将数据库的值写入缓存
                return $data;
            }
        } else {
            return parent::find($type, $params);
        }
    }
}
