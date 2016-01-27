<?php

/*****************************************************************************
 * Seevia Flash
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
class FlashHelper extends AppHelper
{
    public $helpers = array('Javascript');
    public $options = array(
        'width' => 100,
        'height' => 100,
    );
    public $defaultVersionRequirement = '9.0.0';
    public $initialized = false;
    public function init($options = array())
    {
        if (!empty($options)) {
            $this->options = am($this->options, $options);
        }
        $this->initialized = true;
        $view = &ClassRegistry::getObject('view');
        if (is_object($view)) {
            $view->addScript($this->Javascript->link('swfobject'));

            return true;
        } else {
            return $this->Javascript->link('swfobject');
        }
    }

    public function renderSwf($swfFile, $width = null, $height = null, $divDomId = false, $options = array())
    {
        $options = am($this->options, $options);
        if (is_null($width)) {
            $width = $options['width'];
        }
        if (is_null($height)) {
            $height = $options['height'];
        }
        $ret = '';
        if (!$this->initialized) {
            $init = $this->init($options);
            if (is_string($init)) {
                $ret = $init;
            }
            $this->initialized = true;
        }
        $flashvars = '{}';
        $params = '{wmode : "opaque"}';
        $attributes = '{}';
        if (isset($options['flashvars'])) {
            $flashvars = $this->Javascript->object($options['flashvars']);
        }
        if (isset($options['params'])) {
            $params = $this->Javascript->object($options['params']);
        }
        if (isset($options['attributes'])) {
            $attributes = $this->Javascript->object($options['attributes']);
        }

        if ($divDomId === false) {
            $divDomId = uniqid('c_');
            $ret .= '<div id="'.$divDomId.'"></div>';
        }
        if (isset($options['version'])) {
            $version = $options['version'];
        } else {
            $version = $this->defaultVersionRequirement;
        }
        if (isset($options['install'])) {
            $install = $options['install'];
        } else {
            $install = '';
        }

        $swfLocation = $swfFile;
        $ret .= $this->Javascript->codeBlock(
            'swfobject.embedSWF("'.$swfLocation.'", "'.$divDomId.'", "'.$width.'", "'.$height.'", "'.$version.'","'.$install.'", '.$flashvars.', '.$params.', '.$attributes.');');

        return $ret;
    }
}
