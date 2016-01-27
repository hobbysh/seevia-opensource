<?php

/*****************************************************************************
 * Seevia 在线调查控制
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 PageModuleCssController 的投票控制器.
 */
class PageModuleCssController extends AppController
{
    /*
     *@var $name
     */
    public $name = 'PageModuleCss';
    public $uses = array('PageModule','PageType','PageAction');

    /**
     * getcss方法，获得css.
     *
     * @param array $m PageModule数组
     *
     * @return css 根据数组内容返回对应css
     */
    public function getcss($pageaction_id)
    {
        $conditions = array();
        $conditions['PageModule.page_action_id'] = $pageaction_id;
        //取模块详细信息 code信息 id code对应关系
        $module_infos = $this->PageModule->get_module_infos($this->locale, $conditions);
        $this->layout = 'ajax';
        header('Content-type: text/css; charset: UTF-8');
        $this->set('module_infos', $module_infos['module_infos']);
    }
}
