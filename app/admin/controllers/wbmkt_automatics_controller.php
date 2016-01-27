<?php

/*****************************************************************************
 * Seevia 短信日志
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
class WbmktAutomaticsController extends AppController
{
    public $name = 'WbmktAutomatics';
    public $uses = array('WbmktAutomatic');

    public function index()
    {
        $this->navigations[] = array('name' => '微营销管理','url' => '');
        $this->navigations[] = array('name' => '自动微营销','url' => '/wbmkt_automatics/');
        $this->set('title_for_layout', '自动微营销'.' - '.$this->configs['shop_name']);

        $NewProduct_list = $this->WbmktAutomatic->find('all', array('conditions' => array('WbmktAutomatic.wb_type' => '0')));
        $ProductSold_list = $this->WbmktAutomatic->find('all', array('conditions' => array('WbmktAutomatic.wb_type' => '1')));
        $BuyerSpraise_list = $this->WbmktAutomatic->find('all', array('conditions' => array('WbmktAutomatic.wb_type' => '2')));
        $WindowRecommended_list = $this->WbmktAutomatic->find('all', array('conditions' => array('WbmktAutomatic.wb_type' => '3')));
        $this->set('NewProduct_list', $NewProduct_list);
        $this->set('ProductSold_list', $ProductSold_list);
        $this->set('BuyerSpraise_list', $BuyerSpraise_list);
        $this->set('WindowRecommended_list', $WindowRecommended_list);
    }

    public function updata($id)
    {
        $this->data['WbmktAutomatic']['id'] = $id;
        if (isset($this->data['WbmktAutomatic']['img_status'])) {
        } else {
            $this->data['WbmktAutomatic']['img_status'] = 0;
        }
        $this->WbmktAutomatic->save($this->data['WbmktAutomatic']);
        $this->redirect('/wbmkt_automatics/');
    }

    //修改状态
    public function toggle_on_status()
    {
        $this->WbmktAutomatic->hasMany = array();
        $this->WbmktAutomatic->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->WbmktAutomatic->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
