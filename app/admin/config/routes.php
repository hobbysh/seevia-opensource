<?php
/*****************************************************************************
 * Seevia ·�������ļ�
 * ===========================================================================
 * ��Ȩ����  �Ϻ�ʵ������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�ã�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ===========================================================================
 * $����: �Ϻ�ʵ��$
 * $Id$
*****************************************************************************/
    Router::connect('/', array('controller' => 'pages', 'action' => 'login'));
    Router::connect('/login', array('controller' => 'pages', 'action' => 'login'));
    Router::connect('/act_login/', array('controller' => 'pages', 'action' => 'act_login'));
    Router::connect('/log_out/', array('controller' => 'pages', 'action' => 'log_out'));
    Router::connect('/tests', array('controller' => 'tests', 'action' => 'index'));
    Router::connect('/:controller/:id', array('action' => 'view'), array('pass' => array('id'), 'id' => '[0-9]+'));
    Router::connect('/soap/:controller/:action/*', array('prefix' => 'soap', 'soap' => true));
