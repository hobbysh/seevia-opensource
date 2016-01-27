<?php

/*****************************************************************************
 * Seevia 广告位置管理
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
class AdvertisementPositionsController extends AppController
{
    public $name = 'AdvertisementPositions';
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler');
    public $uses = array('Advertisement','AdvertisementI18n','AdvertisementPosition','Template','AdvertisementPosition','Advertisement','OperatorLog');

    public function index($templatename = 'default', $page = 1)
    {
        $this->operator_privilege('advertisement_positions_view');
        $this->menu_path = array('root' => '/cms/','sub' => '/advertisement_positions/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['ad_position_list'],'url' => '');

        //模板
        $template = $this->Template->find('all');
        //pr($template);die;
        $this->set('template', $template);
        if ($templatename == '') {
            foreach ($template as $k => $v) {
                if ($v['Template']['is_default'] == 1) {
                    $templatename = $v['Template']['name'];
                }
            }
        }
        $this->set('defaulttemplate', $templatename);

        $condition['template_name'] = $templatename;
        $total = $this->AdvertisementPosition->find('count', array('conditions' => $condition));//统计全部广告位置总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'AdvertisementPosition';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'advertisement_positions','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'AdvertisementPosition');
        $this->Pagination->init($condition, $parameters, $options);
        $fields[] = 'id';
        $fields[] = 'name';
        $fields[] = 'ad_width';
        $fields[] = 'ad_height';
        $fields[] = 'position_desc';
        $fields[] = 'template_name';
        $fields[] = 'orderby';
        $fields[] = 'code';

        $advertisement_position_list = $this->AdvertisementPosition->find('all', array('conditions' => $condition, 'order' => 'orderby asc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));
        //对比base库
        $new_ad_code = $this->checkadver($templatename);
        $condition['code'] = $new_ad_code;
        $new_advertisement_position_list = $this->AdvertisementPosition->find('all', array('conditions' => $condition, 'order' => 'orderby asc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));
        if (!empty($new_advertisement_position_list)) {
            foreach ($new_advertisement_position_list as $k => $v) {
                $v['AdvertisementPosition']['is_new'] = '1';
                $tmp[]['AdvertisementPosition'] = $v['AdvertisementPosition'];
            }
            $advertisement_position_list = array_merge($advertisement_position_list, $tmp);
        }

        $this->set('advertisement_position_list', $advertisement_position_list);//广告位置列表
        $this->set('title_for_layout', $this->ld['ads_position'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($id = 0, $template_name = 'default')
    {
        if (empty($id)) {
            $this->operator_privilege('advertisement_positions_add');
        } else {
            $this->operator_privilege('advertisement_positions_edit');
        }
//		$this->menu_path = array('root'=>'/cms/','sub'=>'/advertisement_positions/');
//		$this->set("title_for_layout",$this->ld['ad_position']." - ".$this->configs['shop_name']);
//		$this->navigations[]=array('name'=>$this->ld['manager_interface'],'url'=>'');
//		$this->navigations[]=array('name'=>$this->ld['ad_position_list'],'url' => '/advertisement_positions/');

        $this->menu_path = array('root' => '/system/','sub' => '/themes/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manager_templates'],'url' => '/themes/');
        $template_Info = $this->Template->find('first', array('conditions' => array('Template.name' => $template_name)));
        if (!empty($template_Info)) {
            $this->navigations[] = array('name' => $this->ld['template'].' - '.$template_Info['Template']['description'],'url' => '/themes/view/'.$template_Info['Template']['id']);
        }
        $this->set('templatename', isset($_REQUEST['templatename']) ? $_REQUEST['templatename'] : $template_name);
        if ($this->RequestHandler->isPost()) {
            $this->data['AdvertisementPosition']['orderby'] = !empty($this->data['AdvertisementPosition']['orderby']) ? $this->data['AdvertisementPosition']['orderby'] : '50';
            $this->data['AdvertisementPosition']['ad_width'] = !empty($this->data['AdvertisementPosition']['ad_width']) ? $this->data['AdvertisementPosition']['ad_width'] : '100';
            $this->data['AdvertisementPosition']['ad_height'] = !empty($this->data['AdvertisementPosition']['ad_height']) ? $this->data['AdvertisementPosition']['ad_height'] : '100';
            if (isset($this->data['AdvertisementPosition']['id']) && $this->data['AdvertisementPosition']['id'] != '') {
                $this->AdvertisementPosition->save(array('AdvertisementPosition' => $this->data['AdvertisementPosition'])); //关联保存
            } else {
                $this->AdvertisementPosition->saveAll(array('AdvertisementPosition' => $this->data['AdvertisementPosition'])); //关联保存
            }
            //操作员日志
              if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                  $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['ad_position'].'  id '.$id.' '.$this->data['AdvertisementPosition']['name'], $this->admin['id']);
              }
            $this->redirect('/advertisement_positions/index/'.$this->data['AdvertisementPosition']['template_name']);
        }

        $advertisement_position = $this->AdvertisementPosition->find('first', array('conditions' => array('id' => $id)));
        if (isset($advertisement_position['AdvertisementPosition']['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$advertisement_position['AdvertisementPosition']['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_ad_position'],'url' => '');
        }
        $this->set('advertisement_position', $advertisement_position);
        $js_code = '<script';
        $js_code .= ' src='.'"'.$this->webroot.'advertisements/show/'.$id.'"'.'></script>';
        $site_url = $this->webroot.'advertisements/show/'.$id;
        $this->set('js_code', $js_code);

        if (!empty($advertisement_position)) {
            $condition = '';
            $fields[] = 'id';
            $fields[] = 'name';
            $advertisement_position_list = $this->AdvertisementPosition->find('all', array('conditions' => $condition, 'order' => 'orderby asc', 'fields' => $fields));
            $advertisement_position_data = array();
            foreach ($advertisement_position_list as $k => $v) {
                $advertisement_position_data[$v['AdvertisementPosition']['id']] = $v['AdvertisementPosition']['name'];
            }
            $this->set('advertisement_position_data', $advertisement_position_data);//数据在列表上显示用
            $advertisement_list = $this->Advertisement->find('all', array('conditions' => array('advertisement_position_id' => $id)));
            $this->set('advertisement_list', $advertisement_list);
        }
    }

    public function position($templatename = '', $page = 1)
    {
        $this->operator_privilege('advertisement_positions_edit');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['ad_position'],'url' => '');
        //模板
        $template = $this->Template->find('all', array());
        //pr($template);
        $this->set('template', $template);

        if ($templatename == '') {
            foreach ($template as $k => $v) {
                if ($v['Template']['is_default'] == 1) {
                    $templatename = $v['Template']['name'];
                }
            }
        }
        $this->set('defaulttemplate', $templatename);

        $condition = "template_name='".$templatename."'";
        $this->AdvertisementPosition->useDbConfig = 'cms';
        $total = $this->AdvertisementPosition->find('count', array('conditions' => $condition));//统计全部广告位置总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'AdvertisementPosition';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : 20;
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'advertisement_positions','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'AdvertisementPosition');
        $this->Pagination->init($condition, $parameters, $options);
        $fields[] = 'id';
        $fields[] = 'name';
        $fields[] = 'code';
        $fields[] = 'ad_width';
        $fields[] = 'ad_height';
        $fields[] = 'position_desc';
        $fields[] = 'template_name';
        $fields[] = 'orderby';
        $advertisement_position_list = $this->AdvertisementPosition->find('all', array('conditions' => $condition, 'order' => 'orderby asc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));
        //echo "<pre>";print_r($advertisement_position_list);
        $this->set('advertisement_position_list', $advertisement_position_list);//广告位置列表
        $this->set('title_for_layout', $this->ld['ad_position'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->layout = 'window';
    }
    public function position2($templatename = '', $page = 1)
    {
        $this->set('title_for_layout', $this->ld['ad_position'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['ad_position'],'url' => '');

        $this->layout = 'window';
    }

    /**
     *列表排序修改.
     */
    public function update_advertisement_positions_orderby()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->AdvertisementPosition->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *列表高修改.
     */
    public function update_advertisement_positions_ad_height()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter__correct_height'];
        }
        if (is_numeric($val) && $this->AdvertisementPosition->save(array('id' => $id, 'ad_height' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        $pn = $this->AdvertisementPosition->find('first', array('conditions' => array('AdvertisementPosition.id' => $id), 'fields' => 'AdvertisementPosition.name'));
        $pname = $pn['AdvertisementPosition']['name'];
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'修改广告列表高度:id '.$id.' '.$pname, $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *列表宽修改.
     */
    public function update_advertisement_positions_ad_width()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_width'];
        }
        if (is_numeric($val) && $this->AdvertisementPosition->save(array('id' => $id, 'ad_width' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        $pn = $this->AdvertisementPosition->find('list', array('fields' => array('AdvertisementPosition.id', 'AdvertisementPosition.name'), 'conditions' => array('AdvertisementPosition.id' => $id)));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'修改广告列表宽度:id '.$id.' '.@$pn[$id], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表名称修改.
     */
    public function update_advertisement_positions_name()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->AdvertisementPosition->updateAll(
            array('name' => "'".$val."'"),
            array('id' => $id)
        );
        $pn = $this->AdvertisementPosition->find('list', array('fields' => array('AdvertisementPosition.id', 'AdvertisementPosition.name'), 'conditions' => array('AdvertisementPosition.id' => $id)));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'修改广告位名称:id '.$id.' '.@$pn[$id], $this->admin['id']);
        }
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *删除一个广告位置.
     *
     *@param int $id 输入广告位置ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_the_ad_position_failure'];

        $pn = $this->AdvertisementPosition->find('list', array('fields' => array('AdvertisementPosition.id', 'AdvertisementPosition.name'), 'conditions' => array('AdvertisementPosition.id' => $id)));
        $this->AdvertisementPosition->deleteAll(array('id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除广告位:id '.$id.' '.@$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_the_ad_position_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //比较广告位 base
    public function checkadver($template_name)
    {
        $ad_info = $this->AdvertisementPosition->find('list', array('conditions' => array('template_name' => $template_name), 'fields' => 'code'));
        $base_ad_info = $this->AdvertisementPosition->find('list', array('conditions' => array('template_name' => $template_name), 'fields' => 'code'));
        $new_ad = array_diff($base_ad_info, $ad_info);

        return $new_ad;

        foreach ($ad_info as $a) {
            $ad_info2['AdvertisementPosition'] = $a['AdvertisementPosition'];
            $this->AdvertisementPosition->save($ad_info2);
        }
        foreach ($ad_info as $v) {
            $adver_info = $this->Advertisement->find('all', array('conditions' => array('advertisement_position_id' => $v['AdvertisementPosition']['id'])));
            foreach ($adver_info as $vv) {
                //$vv['Advertisement']['id']='';
                //$vv['AdvertisementI18n']['id']='';
                $adver_info2['Advertisement'] = $vv['Advertisement'];
                $adver_info2['AdvertisementI18n'] = $vv['AdvertisementI18n'];
                $this->Advertisement->saveAll($adver_info2);
            }
        }
    }

    //安装指定广告位
    public function install($template_name, $code)
    {
        $ad_info = $this->AdvertisementPosition->find('all', array('conditions' => array('code' => $code, 'template_name' => $template_name)));
        foreach ($ad_info as $a) {
            $ad_info2['AdvertisementPosition'] = $a['AdvertisementPosition'];
            $this->AdvertisementPosition->save($ad_info2);
        }
        foreach ($ad_info as $v) {
            $adver_info = $this->Advertisement->find('all', array('conditions' => array('advertisement_position_id' => $v['AdvertisementPosition']['id'])));
        //	die(json_encode($adver_info));
            foreach ($adver_info as $vv) {
                //	$vv['Advertisement']['id']='';
            //	$vv['AdvertisementI18n']['id']='';
                $adver_info2['Advertisement'] = $vv['Advertisement'];
                $adver_info2['AdvertisementI18n'] = $vv['AdvertisementI18n'];
                $this->Advertisement->saveAll($adver_info2);
            }
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_the_ad_position_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
