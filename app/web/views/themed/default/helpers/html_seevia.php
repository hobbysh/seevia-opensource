<?php

class HtmlSeeviaHelper extends AppHelper
{
    public $themes_host;
    public $helpers = array('Javascript','Html'); //used for seamless degradation when MinifyAsset is set to false;

    public function HtmlSeeviaHelper()
    {
        $this->themes_host = '/themes/'.$this->theme.'/';
    }

    public function js($assets)
    {
        e($this->Javascript->link($this->right_path($assets, 'js')));
    }

    public function css($assets)
    {
        e($this->Html->css($this->right_path($assets, 'css')));
    }

    public function right_path($assets, $ext)
    {
        foreach ($assets as $k => $v) {
            if (strpos($v, '.'.$ext) === false) {
                $assets[$k] = $v.'.'.$ext;
            //	$assets[$k] = '/'.$v.'.'.$ext;
            } else {
                $assets[$k] = $v;
            //	$assets[$k] = '/'.$v.'.'.$ext;
            }
            //斜杠重复
            $assets[$k] = str_replace('//', '/', $assets[$k]);
            $assets[$k] = str_replace('//', '/', $assets[$k]);
            $assets[$k] = str_replace(':/', '://', $assets[$k]);
        }

        return $assets;
    }
}
