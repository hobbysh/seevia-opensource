<?php

/*****************************************************************************
 * Seevia 用户中心验证码
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为AuthnumsController的用于获取布局控制器.
 */
class AuthnumsController extends AppController
{
    /*
    *@var $name
    *@var $helpers
    *@var $uses
    */
    public $name = 'Authnums';
    public $helpers = array('Html');
    public $uses = array();
    /**
     *函数 user_get_authnums 用于获取布局.
     */
    public function user_get_authnums()
    {
        //		echo "<pre>";
//		print_r($_SESSION);
        $this->layout = 'authnums';
    }
}
