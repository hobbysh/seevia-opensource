<?php

/*****************************************************************************
 * Seevia 素材管理
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *素材管理.
 *
 *对于OpenElement这张表的增删改查
 *
 *@author   weizhngye 
 *
 *@version  $Id$
 */
class OpenElementsController extends AppController
{
    /*
    *控制器的名字
    */
    public $name = 'OpenElements';

    /*
    *引用的model
    */
    public $uses = array('OpenElement','OpenModel');
    public $helpers = array('Html','Flash','Cache','Pagination');

    public function view($id)
    {
        $this->layout = 'default_full';
        $material = $this->OpenElement->find('first', array('conditions' => array('OpenElement.id' => $id)));
        $app_list = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type' => 'wechat')));
        $flag = 0;
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            $flag = 1;
        }
        $this->set('flag', $flag);
        $this->set('material', $material);
        $this->set('appid', $app_list['OpenModel']['app_id']);
        if (!empty($material)) {
            $this->pageTitle = $material['OpenElement']['title'].' - '.$this->configs['shop_title'];
        }
    }

    /**
     *OpenElement 预览.
     *
     *传进去的id的内容一个呈现
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function preview($id=0)
    {
        $this->layout = 'default_full';
        $cond['or'][]['OpenElement.id']=$id;
        $cond['or'][]['OpenElement.parent_id']=$id;
        $material = $this->OpenElement->find('all', array('conditions' => $cond, 'order' => 'OpenElement.created asc'));
        $app_list = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type' => 'wechat')));
        $flag = 0;
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            $flag = 1;
        }
        $this->set('flag', $flag);
        $this->set('material', $material);
        $this->set('appid', $app_list['OpenModel']['app_id']);
        if (!empty($material[0])) {
            $this->pageTitle = $material[0]['OpenElement']['title'].' - '.$this->configs['shop_title'];
        }
    }
}
