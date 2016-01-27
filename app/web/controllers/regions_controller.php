<?php

/*****************************************************************************
 * Seevia 用户收藏
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 RegionsController 的区域控制器.
 *
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 */
class RegionsController extends AppController
{
    public $name = 'Regions';
    public $helpers = array('Html','Form','Cache');
    public $uses = array('Region');
    public $components = array('RequestHandler');
    public $cacheQueries = true;
    public $cacheAction = '1 hour';
    public $regions_selects = array();

    /**
     *选择.
     *
     *@param $str
     *@param $address_id
     */
    public function choice($str = '', $address_id = 0)
    {
        Configure::write('debug', 0);
        $regions = $this->Region->find('threaded');
        if (isset($_POST)) {
        	$_POST=$this->clean_xss($_POST);
        }
        $str = '';
        if (isset($_POST['str'])) {
            $is_error = count(explode($this->ld['please_select'], $_POST['str']));
            if ($is_error > 1) {
                $count = strpos($_POST['str'], $this->ld['please_select']);
                $_POST['str'] = substr($_POST['str'], 0, $count);
            }
            if (trim($_POST['str']) == $this->ld['please_select']) {
                $_POST['str'] == '';
            }
            $str = $_POST['str'];
        }
        $this->children($regions, $str);
        for ($i = 0;$i < 4;++$i) {
            if (isset($this->regions_selects) && sizeof($this->regions_selects) > 0 && isset($this->regions_selects[sizeof($this->regions_selects) - 1])) {
                if (isset($this->regions_selects[sizeof($this->regions_selects) - 1]['select']) && sizeof($this->regions_selects[sizeof($this->regions_selects) - 1]['select']) == 2) {
                    foreach ($this->regions_selects[sizeof($this->regions_selects) - 1]['select'] as $a => $b) {
                        if ($a != $this->ld['please_select'] && isset($a)) {
                            $str .= ' '.$a;
                            $this->regions_selects = array();
                            $this->children($regions, $str);
                        }
                    }
                }
            }
        }
        $this->set('regions_selects', $this->regions_selects);
        if (isset($_POST['address_id'])) {
            $this->set('address_id', $_POST['address_id']);
        }
        $this->layout = 'ajax';
    }

    /**
     *子分类.
     *
     *@param $tree
     *@param $str
     */
    public function children($tree, $str)
    {
    	 if (isset($_POST)) {
        	$_POST=$this->clean_xss($_POST);
        }
        if (isset($_POST['local_area']) && $_POST['local_area'] != '') {
            $local_area = $_POST['local_area'];
        } else {
            $local_area = LOCALE;
        }
        $region_id_array = explode(' ', trim($str));
        $region_str = '';
        $this->ld = $this->LanguageDictionary->getformatcode($local_area);
        if (sizeof($region_id_array) > 0) {
            foreach ($region_id_array as $k => $v) {
                if ($v != $this->ld['please_select']) {
                    $region_info = $this->Region->find('first', array('conditions' => array('Region.id' => $v)));
                    if ($k < sizeof($region_id_array) - 1) {
                        $region_str .= $region_info['Region']['id'].' ';
                    } else {
                        $region_str .= $region_info['Region']['id'];
                    }
                }
            }
        }
        $region_array = explode(' ', trim($region_str));
        $deep = sizeof($region_array);
        $select['default'] = $region_array[0];
        foreach ($tree as $k => $v) {
            //	$select['select'][$v['RegionI18n']['region_id']]=$v['RegionI18n']['name'];
            $select['select'][$this->ld['please_select']] = $this->ld['please_select'];
            $select['select'][$v['Region']['id']] = $v['RegionI18n']['name'];
        //	$select['select'][$v['RegionI18n']['name']]=$v['RegionI18n']['name'];
        //	if($region_array[0]==$v['RegionI18n']['name'] && isset($v['children'])){
            if ($region_array[0] == $v['Region']['id'] && isset($v['children'])) {
                $subtree = $v['children'];
            }
        }
        $this->regions_selects[] = $select;
        if ($deep >= 1 && isset($subtree) && sizeof($subtree)) {
            $this->children($subtree, implode(' ', array_slice($region_array, 1)));
        }
    }

    /**
     *2选择.
     *
     *@param $str
     *@param $address_id
     */
    public function twochoice($str = '', $address_id = 0)
    {
    	 if (isset($_POST)) {
        	$_POST=$this->clean_xss($_POST);
        }
        if (isset($_POST['local_area']) && $_POST['local_area'] != '') {
            $local_area = $_POST['local_area'];
        } else {
            $local_area = 'chi';
        }

        $this->Region->set_locale($local_area);
        $regions = $this->Region->find('threaded');
        $str = '';
        if (isset($_POST['str'])) {
            $str = $_POST['str'];
        }
        $this->children($regions, $str);
        for ($i = 0;$i < 4;++$i) {
            if (isset($this->regions_selects) && sizeof($this->regions_selects) > 0 && isset($this->regions_selects[sizeof($this->regions_selects) - 1])) {
                if (isset($this->regions_selects[sizeof($this->regions_selects) - 1]['select']) && sizeof($this->regions_selects[sizeof($this->regions_selects) - 1]['select']) == 2) {
                    foreach ($this->regions_selects[sizeof($this->regions_selects) - 1]['select'] as $a => $b) {
                        if ($a != $this->ld['please_choose']) {
                            $str .= ' '.$a;
                            $this->regions_selects = array();
                            $this->children($regions, $str);
                        }
                    }
                }
            }
        }
        if (isset($_POST['ii'])) {
            $this->set('ii', $_POST['ii']);
        }
        $this->set('regions_selects', $this->regions_selects);

        if (isset($_POST['updateaddress_id'])) {
            $this->set('updateaddress_id', $_POST['updateaddress_id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
    }

    /**
     *函数 choice 用于获取区域信息.
     *
     *@param $str
     *@param $address_id
     */
    public function user_choice($str = '', $address_id = 0)
    {
        Configure::write('debug', 0);
        $regions = $this->Region->find('threaded');
        $str = '';
        if (isset($_POST['str'])) {
            $is_error = count(explode($this->ld['please_select'], $_POST['str']));
            if ($is_error > 1) {
                $count = strpos($_POST['str'], $this->ld['please_select']);
                $_POST['str'] = substr($_POST['str'], 0, $count);
            }
            if (trim($_POST['str']) == $this->ld['please_select']) {
                $_POST['str'] == '';
            }
            $str = $_POST['str'];
        }
        if (isset($_POST['id'])) {
            $this->set('ad_id', $_POST['id']);
        }
        $this->children($regions, $str);
        for ($i = 0;$i < 4;++$i) {
            if (isset($this->regions_selects) && sizeof($this->regions_selects) > 0 && isset($this->regions_selects[sizeof($this->regions_selects) - 1])) {
                if (isset($this->regions_selects[sizeof($this->regions_selects) - 1]['select']) && sizeof($this->regions_selects[sizeof($this->regions_selects) - 1]['select']) == 2) {
                    foreach ($this->regions_selects[sizeof($this->regions_selects) - 1]['select'] as $a => $b) {
                        if ($a != $this->ld['please_select']) {
                            $str .= ' '.$a;
                            $this->regions_selects = array();
                            $this->children($regions, $str);
                        }
                    }
                }
            }
        }
        $this->set('regions_selects', $this->regions_selects);
        if (isset($_POST['address_id'])) {
            $this->set('address_id', $_POST['address_id']);
        }
        $this->layout = 'ajax';
    }

    /**
     *函数 children 用于区域的选择.
     *
     *@param $tree
     *@param $str
     */
    public function user_children($tree, $str)
    {
        $region_id_array = explode(' ', trim($str));
        $region_str = '';
        if (sizeof($region_id_array) > 0) {
            foreach ($region_id_array as $k => $v) {
                if ($v != $this->ld['please_select']) {
                    $region_info = $this->Region->findbyid($v);
                    if ($k < sizeof($region_id_array) - 1) {
                        $region_str .= $region_info['Region']['id'].' ';
                    } else {
                        $region_str .= $region_info['Region']['id'];
                    }
                }
            }
        }
        $region_array = explode(' ', trim($region_str));
        $deep = sizeof($region_array);
        $select['default'] = $region_array[0];
        foreach ($tree as $k => $v) {
            //	$select['select'][$v['RegionI18n']['region_id']]=$v['RegionI18n']['name'];
            $select['select'][$this->ld['please_select']] = $this->ld['please_select'];
            $select['select'][$v['Region']['id']] = $v['RegionI18n']['name'];
        //	$select['select'][$v['RegionI18n']['name']]=$v['RegionI18n']['name'];
        //	if($region_array[0]==$v['RegionI18n']['name'] && isset($v['children'])){
            if ($region_array[0] == $v['Region']['id'] && isset($v['children'])) {
                $subtree = $v['children'];
            }
        }
        $this->regions_selects[] = $select;
        if ($deep >= 1 && isset($subtree) && sizeof($subtree)) {
            $this->children($subtree, implode(' ', array_slice($region_array, 1)));
        }
    }

    /**
     *函数 twochoice 用于获取修改后的区域信息.
     *
     *@param $str
     *@param $address_id
     */
    public function user_twochoice($str = '', $address_id = 0)
    {
        Configure::write('debug', 0);
        $regions = $this->Region->find('threaded');
        $str = '';
        if (isset($_POST['str'])) {
            $is_error = count(explode($this->ld['please_select'], $_POST['str']));
            if ($is_error > 1) {
                $count = strpos($_POST['str'], $this->ld['please_select']);
                $_POST['str'] = substr($_POST['str'], 0, $count);
            }
            if (trim($_POST['str']) == $this->ld['please_select']) {
                $_POST['str'] == '';
            }
            $str = $_POST['str'];
        }
        $this->children($regions, $str);
        for ($i = 0;$i < 4;++$i) {
            if (isset($this->regions_selects) && sizeof($this->regions_selects) > 0 && isset($this->regions_selects[sizeof($this->regions_selects) - 1])) {
                if (isset($this->regions_selects[sizeof($this->regions_selects) - 1]['select']) && sizeof($this->regions_selects[sizeof($this->regions_selects) - 1]['select']) == 2) {
                    foreach ($this->regions_selects[sizeof($this->regions_selects) - 1]['select'] as $a => $b) {
                        if ($a != $this->ld['please_select']) {
                            $str .= ' '.$a;
                            $this->regions_selects = array();
                            $this->children($regions, $str);
                        }
                    }
                }
            }
        }
        $this->set('regions_selects', $this->regions_selects);
        if (isset($_POST['updateaddress_id'])) {
            $this->set('updateaddress_id', $_POST['updateaddress_id']);
        }
        $this->layout = 'ajax';
    }
    public function uncheckchoice($str = '', $address_id = 0)
    {
    	 if (isset($_POST)) {
        	$_POST=$this->clean_xss($_POST);
        }
        if (isset($_POST['local_area']) && $_POST['local_area'] != '') {
            $local_area = $_POST['local_area'];
        } else {
            $local_area = 'chi';
        }

        $this->Region->set_locale($local_area);
        $regions = $this->Region->find('threaded');
        $str = '';
        if (isset($_POST['str'])) {
            $str = $_POST['str'];
        }
        $this->children($regions, $str);
        for ($i = 0;$i < 4;++$i) {
            if (isset($this->regions_selects) && sizeof($this->regions_selects) > 0 && isset($this->regions_selects[sizeof($this->regions_selects) - 1])) {
                if (isset($this->regions_selects[sizeof($this->regions_selects) - 1]['select']) && sizeof($this->regions_selects[sizeof($this->regions_selects) - 1]['select']) == 2) {
                    foreach ($this->regions_selects[sizeof($this->regions_selects) - 1]['select'] as $a => $b) {
                        if ($a != $this->ld['please_choose']) {
                            $str .= ' '.$a;
                            $this->regions_selects = array();
                            $this->children($regions, $str);
                        }
                    }
                }
            }
        }
        if (isset($_POST['ii'])) {
            $this->set('ii', $_POST['ii']);
        }
        $this->set('regions_selects', $this->regions_selects);

        if (isset($_POST['updateaddress_id'])) {
            $this->set('updateaddress_id', $_POST['updateaddress_id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
    }
}
