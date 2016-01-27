<?php
/*****************************************************************************
 * SV-Cart 路径配置文件
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id: routes.php 996 2015-09-16 04:48:35Z zhaoyincheng $
*****************************************************************************/
	Router::connect('/', array('controller' => 'pages', 'action' => 'login'));
	Router::connect('/login', array('controller' => 'pages', 'action' =>'login'));
    Router::connect('/act_login/', array('controller' => 'pages', 'action' => 'act_login'));
    Router::connect('/log_out/', array('controller' => 'pages', 'action' => 'log_out'));
	Router::connect('/tests', array('controller' => 'tests', 'action' => 'index'));
	Router::connect('/:controller/:id',array('action' => 'view'),array('pass' => array('id'),'id' => '[0-9]+'));
	Router::connect('/soap/:controller/:action/*', array('prefix'=>'soap', 'soap'=>true));
	 
?>