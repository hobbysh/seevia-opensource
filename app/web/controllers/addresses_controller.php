<?php

/**
 * Seevia 用户收货地址.
 *
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @utl 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
 */
uses('sanitize');
/**
 *这是一个名为AddressesController的地址控制器.
 */
class AddressesController extends AppController
{
    /*
    *@var $name 名称
    */
    public $name = 'Addresses';
    /*
    *@var $helpers 帮助
    */
    public $helpers = array('Html');
    /*
    *@var $uses 关联的模板
    */
    public $uses = array('UserAddress','Region','User','UserFans','Blog','UserApp');
    /*
    *@var $components 关联的组件
    */
    public $components = array('RequestHandler');

    /**
     *函数user_index 用于进入用户地址簿页面.
     */
    public function index()
    {
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                        //页面初始化 
        //面包屑开始 
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['account_address_book'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        //面包屑结束 

        //获得我的收获地址
        $user_id = $_SESSION['User']['User']['id'];
        $userinfo = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        $this->set('userinfo', $userinfo);
        $this->set('user_list', $userinfo);
        //分享绑定显示判断
        $app_share = $this->UserApp->app_status();
        $this->set('app_share', $app_share);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);

        $this->getHeadBarInformation($user_id);
        //获取地址薄数量
        $num = $this->UserAddress->find('count', array('conditions' => array('UserAddress.user_id' => $user_id)));
        $this->set('num', $num);
        $this->data['user_address'] = $this->UserAddress->find_user_address($user_id);//添加到model中
        foreach ($this->data['user_address'] as $k => $v) {
            $this->data['user_address'][$k]['UserAddress']['telephone_all'] = $this->data['user_address'][$k]['UserAddress']['telephone'];
            $region_array = explode(' ', trim($v['UserAddress']['regions']));
            $this->data['user_address'][$k]['UserAddress']['regions_id'] = $this->data['user_address'][$k]['UserAddress']['regions'];
            if (is_array($region_array) && sizeof($region_array) > 0) {
                foreach ($region_array as $a => $b) {
                    if ($b == $this->ld['please_select']) {
                        unset($region_array[$a]);
                    }
                }
            } else {
                $region_array[] = 0;
            }
            $this->data['user_address'][$k]['UserAddress']['regions'] = $this->Region->find_regions_name($region_array);//添加到model中

            if (!empty($v['UserAddress']['province']) && !empty($v['UserAddress']['city'])) {
                $regions_arr[] = $v['UserAddress']['country'];
                $regions_arr[] = $v['UserAddress']['province'];
                $regions_arr[] = $v['UserAddress']['city'];
            }
        }
        if (isset($regions_arr) && sizeof($regions_arr) > 0) {
            $regions_arr = array_flip(array_flip($regions_arr));//去除重复Id
            $_regions_list = $this->Region->find('all', array('conditions' => array('Region.id' => $regions_arr), 'fields' => array('Region.id', 'RegionI18n.name')));
            foreach ($_regions_list as $k => $v) {
                $regions_list[$v['Region']['id']] = $v['RegionI18n']['name'];
            }
            foreach ($this->data['user_address'] as $k => $v) {
                if (!empty($v['UserAddress']['province']) && !empty($v['UserAddress']['city'])) {
                    $this->data['user_address'][$k]['UserAddress']['country'] = $regions_list[$v['UserAddress']['country']];
                    $this->data['user_address'][$k]['UserAddress']['province'] = $regions_list[$v['UserAddress']['province']];
                    $this->data['user_address'][$k]['UserAddress']['city'] = $regions_list[$v['UserAddress']['city']];
                }
            }
        }
        $this->pageTitle = $this->ld['account_address_book'].' - '.$this->configs['shop_title'];
    }
    /**
     *函数user_deladdress 用于删除地址.
     *
     *@param $id
     */
    public function user_deladdress($id)
    {
        $this->UserAddress->deleteall("UserAddress.id='".$id."'");
        $user_info = $this->User->find('first', array('conditions' => array('User.address_id' => $id)));
        if (!empty($user_info)) {
            $this->User->updateAll(array('User.address_id' => 0), array('User.id' => $user_info['User']['id']));
        }
        //显示的页面
        $this->redirect('/addresses');
    }

    /**
     *函数user_change_region 用户地址.
     *
     *@param $region_id
     *@param $level
     *@param $target
     */
    public function user_change_region($region_id, $level, $target)
    {
        $low_region = $this->Region->find_low_region($region_id);//添加到model中
        $this->set('level', $level);
        $this->set('targets', $target);
        $this->set('low_region', $low_region);
        $this->set('province_list',    $this->get_regions(1));
        //显示的页面
        $this->layout = 'ajax';
    }

    //add by Gin
    /**
    *函数user_show_edit 用于编辑地址.
    */
    public function show_edit($id = 0)
    {
    	 $id=intval($id);
        //登录验证
        $this->checkSessionUser();
        if (!empty($id) || $id == 0) {
            $this->layout = 'usercenter';            //引入模版
            $this->page_init();                        //页面初始化 
            //面包屑开始 
            $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
            $this->ur_heres[] = array('name' => $this->ld['account_address_book'],'url' => '/addresses');
            $this->ur_heres[] = array('name' => $this->ld['modify'],'url' => '');
            $this->set('ur_heres', $this->ur_heres);
            //面包屑结束
            if (isset($_SESSION['User']['User']['id'])) {
                $user_id = $_SESSION['User']['User']['id'];
                $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
                $this->set('user_list', $user_list);
                //pr($user_list);
                //粉丝数量
                $fans = $this->UserFans->find_fanscount_byuserid($user_id);
                $this->set('fanscount', $fans);
                //日记数量
                $blog = $this->Blog->find_blogcount_byuserid($user_id);
                $this->set('blogcount', $blog);
                //关注数量
                $focus = $this->UserFans->find_focuscount_byuserid($user_id);
                $this->set('focuscount', $focus);
            }
            $this->getHeadBarInformation($id);

            $address = $this->UserAddress->find('first', array('conditions' => array('UserAddress.id' => $id)));//编辑
            $this->set('address', $address);
            if ($this->RequestHandler->isPost()) {
            	  if(isset($this->data)){$this->data=$this->clean_xss($this->data);}
                if (isset($this->data['Address']['RegionUpdate']) && !empty($this->data['Address']['RegionUpdate'])) {
                    $this->data['address']['regions'] = (isset($this->data['Address']['RegionUpdate'][0]) ? $this->data['Address']['RegionUpdate'][0] : '').' '.(isset($this->data['Address']['RegionUpdate'][1]) ? $this->data['Address']['RegionUpdate'][1] : '').' '.(isset($this->data['Address']['RegionUpdate'][2]) ? $this->data['Address']['RegionUpdate'][2] : '');
                    $this->data['address']['country'] = isset($this->data['Address']['RegionUpdate'][0]) ? $this->data['Address']['RegionUpdate'][0] : '';
                    $this->data['address']['province'] = isset($this->data['Address']['RegionUpdate'][1]) ? $this->data['Address']['RegionUpdate'][1] : '';
                    $this->data['address']['city'] = isset($this->data['Address']['RegionUpdate'][2]) ? $this->data['Address']['RegionUpdate'][2] : '';
                }
                $address = $this->UserAddress->save($this->data['address']);//添加到model中 
                $this->redirect('/addresses');
            }
        } else {
            $this->redirect('/addresses');
        }
    }
    /**
     *设为默认地址.
     */
    public function defaultaddress($addressid = 0)
    {
        if ($addressid != 0) {
            $this->User->updateAll(array('address_id' => $addressid), array('User.id' => $_SESSION['User']['User']['id']));
        }
        $this->redirect('/addresses');
    }
}
