<?php

class HtmlSeeviaHelper extends AppHelper
{
    public $themes_host;
    public $helpers = array('Javascript','Html'); //used for seamless degradation when MinifyAsset is set to false;
    public function HtmlSeeviaHelper()
    {
        $this->themes_host = Configure::read('themes_host');
    }
    public function js($assets)
    {
        e($this->Javascript->link($this->right_path($assets, 'js')));
    }

    public function css($assets)
    {
        //	pr($this->right_path($assets,'css'));
         e($this->Html->css($this->right_path($assets, 'css')));
    }
    public function right_path($assets, $ext)
    {
        foreach ($assets as $k => $v) {
            //pr($v);pr($ext);
            if (strpos($v, '.'.$ext) === false) {
                //	$assets[$k] = $this->themes_host.'/'.$v.'.'.$ext;
                $assets[$k] = '/'.$ext.'/'.$v.'.'.$ext;
            } else {
                //	$assets[$k] = $this->themes_host.'/'.$v;
                $assets[$k] = '/'.$ext.'/'.$v;
            }

            //斜杠重复
            $assets[$k] = str_replace('//', '/', $assets[$k]);
            $assets[$k] = str_replace('//', '/', $assets[$k]);
            $assets[$k] = str_replace(':/', '://', $assets[$k]);
        }
    //	pr($assets);
        return $assets;
    }
}
