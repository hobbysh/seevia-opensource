<?php

/**
 * AppModel类，应用模型.
 */
class AppModel extends Model
{
    /*
     * @var $locale 多语言变量 默认中文
     */
    public $locale = 'chi';
    public $short = array('config' => 'short','use' => true);
    /**
     * 重构find方法，增加缓存的调用.
     *
     * @author 笨笨天才@20101127
     *
     * $params['cache']=array('use'=>true,'config'=>'short');
     * short是core.php定义的Cache:Config，默认为null
     *
     * @param string $type                      输入类型
     * @param mixed  $params                    输入参数
     * @param bool   $params['cache']['use']    是否使用缓存标志
     * @param string $params['cache']['config'] 缓存类型
     *
     * @return mixed $data 返回值
     */
    public function find($type, $params = array())
    {
        //!isset($params['cache'])
        if ((isset($params['cache']) && $params['cache']['use'] !== false)) { //判断是否不读取缓存数据
            $cache_config = isset($params['cache']['config']) ? $params['cache']['config'] : null;
            $model = isset($this->name) ? '_'.$this->name : 'appmodel';
            $paramsHash = md5(serialize($params));
            $cache_key = $model.'_'.$type.'_'.$this->locale.'_'.$paramsHash; //根据模型名称，type参数，多语言，params组成缓存唯一序号
            //	echo $params['cache']['config']."----".$cache_key."<br />";
            //pr($cache_config);
            $data = cache::read($cache_key, $cache_config);

            //print_r($params);
            if ($data) {
                //	echo "cached:".$cache_key."<br /><pre>";
                return $data;
            } else {
                //echo "not find cached:".$cache_key."<br /><pre>";
                //print_r($params);
                $data = parent::find($type, $params);
                cache::write($cache_key, $data, $cache_config); //将数据库的值写入缓存
                return $data;
            }
        } else {
            //echo "no cache:".$cache_key."<br /><pre>";
            return parent::find($type, $params);
        }
    }
    /**
     * 函数sub_str 用于截取商品内容加以处理.
     *
     * @todo 公共函数放到controllers中
     *
     * @param $str 商品内容
     * @param $length 长度
     * @param $append 路径可用
     * @param $strlength 计算长度
     * @param $newstr 截取后的商品内容
     *
     * @return $newstr 截取后的商品内容
     */
    public function sub_str($str, $length = 0, $append = true)
    {
        $str = trim($str);
        $strlength = strlen($str);

        if ($length == 0 || $length >= $strlength) {
            return $str;
        } elseif ($length < 0) {
            $length = $strlength + $length;
            if ($length < 0) {
                $length = $strlength;
            }
        }

        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, 0, $length, 'utf-8');
        } elseif (function_exists('iconv_substr')) {
            $newstr = iconv_substr($str, 0, $length, 'utf-8');
        } else {
            //$newstr = trim_right(substr($str, 0, $length));
            $newstr = substr($str, 0, $length);
        }

        if ($append && $str != $newstr) {
            $newstr .= '...';
        }

        return $newstr;
    }
}
