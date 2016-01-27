<?php

/*****************************************************************************
 * Seevia ��ҵ��ģ�徲̬����ģ��
 * ===========================================================================
 * ��Ȩ���� �Ϻ�ʵ������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�ã�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ===========================================================================
 * $����: �Ϻ�ʵ��$
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
