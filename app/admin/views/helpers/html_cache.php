<?php

/*****************************************************************************
 * Seevia 商业版模板静态缓存模块
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/

class HtmlCacheHelper extends Helper
{
    public $path = null;

    public function __construct()
    {
    }

    public function afterLayout()
    {
        if (Configure::read('debug') == 0) {
            $view = &ClassRegistry::getObject('view');
            $path = $this->params['url']['url'];
            $path = implode(DS, array_filter(explode('/', $path)));
            if ($path !== '') {
                $path = DS.ltrim($path, DS);
            }
            $this->path = WWW_ROOT.$path.DS.'index.html';
            $file = new File($this->path, true);
            $file->write($view->output);
        }
    }
}
