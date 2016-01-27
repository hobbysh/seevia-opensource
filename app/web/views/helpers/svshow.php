<?php

/*****************************************************************************
 * Seevia svshow
 *===========================================================================
 * 版权所有上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 *---------------------------------------------------------------------------
 *这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *不允许对程序代码以任何形式任何目的的再发布。
 *===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/

/*
    rewrite ^/(cn|jp|en)/(.*)-P([0-9]+)\.html$  /$1/index.php?url=/products/$3;
    rewrite ^/(cn|jp|en)/(.*)-A([0-9]+)\.html$  /$1/index.php?url=/articles/$3;
    rewrite ^/(cn|jp|en)/(.*)-PC([0-9]+)\.html$ /$1/index.php?url=/categories/$3;
    rewrite ^/(cn|jp|en)/(.*)-AC([0-9]+)\.html$ /$1/index.php?url=/articles/category/$3;
    rewrite ^/(cn|jp|en)/(.*)-TP([0-9]+)\.html$ /$1/index.php?url=/topics/$2;
    rewrite ^/(cn|jp|en)/(.*)-BV([0-9]+)\.html$ /$1/index.php?url=/brands/view/$2;
*/

class SVshowHelper extends HTMLHelper
{
    //构造函数
    public function SVshowHelper()
    {
        $this->themes_host = Configure::read('themes_host');
        //$this->shop_default_img = Configure::read('shop_default_img');
        $this->shop_default_img = '/';
        if (substr($this->themes_host, -1) == '/') {
            $this->themes_host = substr($this->themes_host, 0, -1);
        }
    }

    //重载link方法
    public function link($title, $url = null, $options = array(), $confirmMessage = false)
    {
        $escapeTitle = false;
        if ($url !== null) {
            $url = $this->url($url);
        } else {
            $url = $this->url($title);
            $title = $url;
            $escapeTitle = false;
        }
        if (isset($options['escape'])) {
            $escapeTitle = $options['escape'];
        }

        if ($escapeTitle === true) {
            $title = h($title);
        } elseif (is_string($escapeTitle)) {
            $title = htmlentities($title, ENT_QUOTES, $escapeTitle);
        }

        if (!empty($options['confirm'])) {
            $confirmMessage = $options['confirm'];
            unset($options['confirm']);
        }
        if ($confirmMessage) {
            $confirmMessage = str_replace("'", "\'", $confirmMessage);
            $confirmMessage = str_replace('"', '\"', $confirmMessage);
            $options['onclick'] = "return confirm('{$confirmMessage}');";
        } elseif (isset($options['default']) && $options['default'] == false) {
            if (isset($options['onclick'])) {
                $options['onclick'] .= ' event.returnValue = false; return false;';
            } else {
                $options['onclick'] = 'event.returnValue = false; return false;';
            }
            unset($options['default']);
        }

        return sprintf($this->tags['link'], $url, $this->_parseAttributes($options), $title);
    }

    //链接地址特殊字符格式化
    public function url_name_format($name)
    {
        $name = str_replace('\\', '-', $name);
        $name = str_replace(',', '-', $name);
        $name = str_replace('&', '-', $name);
        $name = str_replace('?', '-', $name);
        $name = str_replace('/', '-', $name);
        $name = str_replace('%', '-', $name);
        $name = str_replace(' ', '-', $name);

        return urlencode($name);
    }

    //金额格式化
    public function price_format($price, $config, $rate = 1)
    {
        $price = round($price, 2);

        return sprintf($config, $price * $rate);
    }

    //获取图片路径
    public function image_path($path, $locale = 'chi')
    {
        if (empty($path)) {
            $path = $this->shop_default_img;
        }
        if (strpos($path, '://') === false) {
            if ($path[0] !== '/') {
                $path = '/themed/'.$this->theme.'/img/'.$path;
            } else {
                $path = $path;
            }
            //$path = $this->assetTimestamp($this->webroot($path));
        }

        $path = $this->cdn_img($path, $locale);

        return $path;
    }

    //获取图片标签
    public function image($path, $locale = 'chi', $options = array())
    {
        $path = $this->image_path($path, $locale);

        //echo $path;
        if (!isset($options['alt'])) {
            $options['alt'] = '';
        }

        $url = false;
        if (!empty($options['url'])) {
            $url = $options['url'];
            unset($options['url']);
        }

        $image = sprintf($this->tags['image'], $path, $this->_parseAttributes($options, null, '', ' '));

        if ($url) {
            return sprintf($this->tags['link'], $this->url($url), null, $image);
        }

        return $image;
    }

    //seo 取链接地址
    public function seo_link_path($options = array())
    {
        //pr($options);
        $seo_url = Configure::read('seo_url');
        //pr($seo_url);
        $path = '';
        if (!empty($options['type'])) {
            if ($seo_url == 1) {
                switch ($options['type']) {
                    case 'P': //商品详细页
                        $path = '/'.$this->url_name_format($options['name']).'-P'.$options['id'].'.html';
                        break;
                    case 'PC': //商品分类页
                        $path = '/'.$this->url_name_format($options['name']).'-PC'.$options['id'].'.html';
                        break;
                    case 'A': //文章详细页
                        $path = '/'.$this->url_name_format($options['name']).'-A'.$options['id'].'.html';
                        break;
                    case 'AC': //文章分类页
                        $path = '/'.$this->url_name_format($options['name']).'-AC'.$options['id'].'.html';
                        break;
                    case 'TP': //文章分类页
                        $path = '/'.$this->url_name_format($options['name']).'-TP'.$options['id'].'.html';
                        break;
                    case 'BV': //品牌页
                        $path = '/'.$this->url_name_format($options['name']).'-BV'.$options['id'].'.html';
                        break;
                    case 'AV': //文章视频页
                        $path = '/'.$this->url_name_format($options['name']).'-AV'.$options['id'].'.html';
                        break;
                    case 'IMG': //文章视频页
                        $path = $options['url'];
                        break;
                    default :
                        $path = '';
                }
            } else {
                switch ($options['type']) {
                    case 'P': //商品详细页
                        $path = '/products/'.$options['id'].'';
                        break;
                    case 'PC': //商品分类页
                        $path = '/categories/'.$options['id'].'';
                        break;
                    case 'A': //文章详细页
                        $path = '/articles/'.$options['id'].'';
                        break;
                    case 'AC': //文章分类页
                        $path = '/articles/category/'.$options['id'].'';
                        break;
                    case 'TP': //文章分类页
                        $path = '/topics/'.$options['id'].'';
                        break;
                    case 'BV': //品牌页
                        $path = '/brands/'.$options['id'].'';
                        break;
                    case 'AV': //文章视频页
                        $path = '/articles/video/'.$options['id'];
                        break;
                    case 'IMG': //文章视频页
                        $path = $options['url'];
                        break;
                    default :
                        $path = '';
                }
            }
        }

        return $path;
    }
    //seo 取链接地址
    public function seo_link_url($options = array())
    {
        $seo_url = Configure::read('seo_url');
        $path = '';
        if (!empty($options['type'])) {
            if ($seo_url == 1) {
                switch ($options['type']) {
                    case 'P': //商品详细页
                        $path = '/'.$this->url_name_format($options['name']).'-P'.$options['id'].'.html';
                        break;
                    case 'PC': //商品分类页
                        $path = '/'.$this->url_name_format($options['name']).'-PC'.$options['id'].'.html';
                        break;
                    case 'A': //文章详细页
                        $path = '/'.$this->url_name_format($options['name']).'-A'.$options['id'].'.html';
                        break;
                    case 'AC': //文章分类页
                        $path = '/'.$this->url_name_format($options['name']).'-AC'.$options['id'].'.html';
                        break;
                    case 'TP': //文章分类页
                        $path = '/'.$this->url_name_format($options['name']).'-TP'.$options['id'].'.html';
                        break;
                    default :
                        $path = '';
                }
            } else {
                switch ($options['type']) {
                    case 'P': //商品详细页
                        $path = '/products/'.$options['id'].'';
                        break;
                    case 'PC': //商品分类页
                        $path = '/categories/'.$options['id'].'';
                        break;
                    case 'A': //文章详细页
                        $path = '/articles/'.$options['id'].'';
                        break;
                    case 'AC': //文章分类页
                        $path = '/articles/category/'.$options['id'].'';
                        break;
                    case 'TP': //文章分类页
                        $path = '/topics/'.$options['id'].'';
                        break;
                    case 'BV': //品牌页
                        $path = '/brands/'.$options['id'].'';
                        break;
                    default :
                        $path = '';
                }
            }
        }

        return $this->url($path);
    }
    //seo 取链接
    public function seo_link($options = array())
    {
        $path = $this->seo_link_path($options);
        $sub_name = empty($options['sub_name']) ? $options['name'] : $options['sub_name'];
        $sub_name = htmlspecialchars($sub_name);
        if (isset($options['img'])) {
            if (empty($options['img'])) {
                $options['img'] = '/theme/default/images/default.png';
            }
            $sub_name = $this->image($options['img'], array('alt' => $options['name']));
        }
        $optarr = array();
        if (isset($options['target'])) {
            $optarr = array('title' => $options['name'],'target' => $options['target']);
        } else {
            $optarr = array('title' => $options['name']);
        }

        if (isset($options['class'])) {
            $optarr['class'] = $options['class'];
        }

        return $this->link($sub_name, $path, $optarr);
    }

    //替换地址
    public function url_replace($options = array(), $defaults = array())
    {
        $url = '/';
        $defaults = $defaults;
        foreach ($options as $k => $v) {
            if (isset($defaults[$k])) {
                $defaults[$k] = $v;
            }
        }
        if ($defaults) {
            $url .= implode('/', $defaults);
        }

        return $this->url($url);
    }

    //商品地址
    public function sku_product_link($id, $name, $code, $config)
    {
        $name = str_replace('\\', '-', $name);
        $name = str_replace(',', '-', $name);
        $name = str_replace('&', '-', $name);
        $name = str_replace('?', '-', $name);
        $name = str_replace('/', '-', $name);
        $name = str_replace('%', '-', $name);
        $name = str_replace(' ', '-', $name);
        if ($config == 1) {
            return '/products/sku/'.$name.'/'.$code;
        } else {
            return '/products/'.$id;
        }
    }

    //外网cdn
    //将外网商品图片路径更换为cdn路径
    public function cdn_img($path, $locale = 'chi')
    {
        //cdn
        if ($locale == 'chi') {
            $path = str_replace('img.ioco.cn', 'img.seeworlds.cn', $path);
        } else {
            $path = str_replace('img.ioco.cn', 'img.seeworlds.com', $path);
        }

        return $path;
    }

    //字符串截取（带中文）
    public function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
    {
        if ($code == 'UTF-8') {
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);
            if (count($t_string[0]) - $start > $sublen) {
                return implode('', array_slice($t_string[0], $start, $sublen)).'...';
            }

            return implode('', array_slice($t_string[0], $start, $sublen));
        } else {
            $start = $start * 2;
            $sublen = $sublen * 2;
            $strlen = strlen($string);
            $tmpstr = '';
            for ($i = 0; $i < $strlen; ++$i) {
                if ($i >= $start && $i < ($start + $sublen)) {
                    if (ord(substr($string, $i, 1)) > 129) {
                        $tmpstr .= substr($string, $i, 2);
                    } else {
                        $tmpstr .= substr($string, $i, 1);
                    }
                }
                if (ord(substr($string, $i, 1)) > 129) {
                    $i++;
                }
            }
            if (strlen($tmpstr) < $strlen) {
                $tmpstr .= '...';
            }

            return $tmpstr;
        }
    }

    /*
        去除字符串空格
    */
    public function emptyreplace($str)
    {
        $str = trim($str);
        $str = strip_tags($str, '');
        $str = ereg_replace("\t", '', $str);
        $str = ereg_replace("\r\n", '', $str);
        $str = ereg_replace("\r", '', $str);
        $str = ereg_replace("\n", '', $str);
        $str = ereg_replace(' ', ' ', $str);

        return trim($str);
    }

    public function imgfilehave($fileurl)
    {
        if (@fopen($fileurl, 'r')) {
            return true;
        } else {
            return false;
        }
    }
}
