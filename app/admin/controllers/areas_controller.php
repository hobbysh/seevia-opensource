<?php

/*****************************************************************************
 * Seevia 区域管理
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
class AreasController extends AppController
{
    public $name = 'Areas';
    public $helpers = array('Html');
    public $uses = array('Region','RegionI18n','OperatorLog');

    public function index($pid = 0)
    {
        /*判断权限*/
        $this->operator_privilege('zone_view');
        /*end*/
        $this->set('title_for_layout', $this->ld['reviews_regions'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reviews_regions'],'url' => '/areas/');

        $area_list = $this->Region->getarealist($pid, $this->locale);
    //	pr($area_list);exit;
     //   echo $pid;exit;
        $region_parents = $this->Region->get_parents($pid);
        $num = '1';
        if (!empty($region_parents)) {
            $count = count($region_parents);
            for ($i = $count - 1;$i >= 0;--$i) {
                ++$num;
                $this->navigations[] = array('name' => $region_parents[$i]['name'],'url' => '/areas/index/'.$region_parents[$i]['id']);
            }
        }
        switch ($num) {
              case 1:
                $num_name = $this->ld['top_level_region'];
                break;
              case 2:
                $num_name = $this->ld['two_level_regions'];
                break;
              case 3:
                $num_name = $this->ld['three_level_regions'];
                break;
              case 4:
                $num_name = $this->ld['four_level_regions'];
                break;
              default:
                $num_name = $this->ld['unknown_region'];
                break;
        }
        $languages = $this->languages;
        $this->set('area_languages', $languages);

        $this->set('num_name', $num_name);
        $this->set('area_list', $area_list);
        $this->set('locale', $this->locale);
        $this->set('pid', $pid);
    }

     /*------------------------------------------------------ */
//-- 新增编辑地区
/*------------------------------------------------------ */
    public function view($region_id = 0, $pid = 0)
    {
        $this->set('title_for_layout', $this->ld['add_region'].'-'.$this->ld['reviews_regions'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reviews_regions'],'url' => '/areas/');
        $this->navigations[] = array('name' => $this->ld['add_region'],'url' => '');
        $languages = $this->languages;
        $this->set('area_languages', $languages);

        $this->set('pid', $pid);
    //	$Region_info = $this->Region->find("","","Region.id desc");
    //	pr($languages);exit;
       $region = $this->Region->findbyid($region_id);
      // pr($region);exit;
       $edit_region = $this->RegionI18n->find('all', array('conditions' => array('region_id' => $region_id)));
    //	pr($edit_region);die();
       $this->set('region', $region);
        $this->set('edit_region', $edit_region);
        if ($this->RequestHandler->isPost()) {
            // echo $pid;
        // pr($this->data);exit;
        $region_info = array(
                'id' => isset($this->data['Region']['id']) ? $this->data['Region']['id'] : '',
                'parent_id' => isset($this->data['Region']['parent_id']) ? $this->data['Region']['parent_id'] : 0,
                'level' => isset($this->data['Region']['level']) ? $this->data['Region']['level'] : '0',
                'agency_id' => isset($region['Region']['agency_id']) ? $region['Region']['agency_id'] : 0,
                'param01' => isset($region['Region']['param01']) ? $region['Region']['param01'] : '',
                'param02' => isset($region['Region']['param02']) ? $region['Region']['param02'] : '',
                'param03' => isset($region['Region']['param03']) ? $region['Region']['param03'] : '',
                'orderby' => !empty($this->data['Region']['orderby']) ? $this->data['Region']['orderby'] : 50,
                'abbreviated' => !empty($this->data['Region']['orderby']) ? $this->data['Region']['abbreviated'] : '',
        );

            $this->Region->saveAll(array('Region' => $region_info));
            $id = $this->Region->id;

            if (is_array($this->data['RegionI18n'])) {
                foreach ($this->data['RegionI18n'] as $k => $v) {
                    $v['region_id'] = isset($this->data['RegionI18n']['id']) ? $this->data['RegionI18n']['id'] : '';
                    $v['region_id'] = $id;
                    $v['description'] = '';

                    $this->RegionI18n->saveAll(array('RegionI18n' => $v));
                }
                foreach ($this->data['RegionI18n'] as $k => $v) {
                    if ($v['locale'] == $this->locale) {
                        $userinformation_name = $v['name'];
                    }
                }
            }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_region'].$userinformation_name, $this->admin['id']);
        }
            $this->redirect('/areas');
        }
    //$this->flash("地区 ".$userinformation_name." 编辑成功。",'/areas/index/'.$this->params['form']['parent_id'],10);
    }
/*------------------------------------------------------ */
//-- 删除地区
/*------------------------------------------------------ */
    public function remove($id)
    {
        $this->pageTitle = $this->ld['deleted_region'].'-'.$this->ld['reviews_regions'].' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reviews_regions'],'url' => '/areas/');
        $this->navigations[] = array('name' => $this->ld['deleted_region'],'url' => '');
        $pn = $this->RegionI18n->find('list', array('fields' => array('RegionI18n.region_id', 'RegionI18n.name'), 'conditions' => array('RegionI18n.region_id' => $id, 'RegionI18n.locale' => $this->locale)));
        $this->Region->deleteAll("Region.id = '".$id."'", false);
        $this->RegionI18n->deleteAll("RegionI18n.region_id = '".$id."'");
           //操作员日志	        
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_region'].$pn[$id], $this->admin['id']);
            }
        $this->redirect('/areas');
    }
/*------------------------------------------------------ */
//-- ajax修改未命名的区域
/*------------------------------------------------------ */
    public function ajaxeditregion($new_region_name, $regioni18n_id)
    {
        if ($new_region_name != '' && $regioni18n_id > 0) {
            $this->RegionI18n->updateAll(
                array('RegionI18n.name' => "'".$new_region_name."'"),
                array('RegionI18n.id' => "'".$regioni18n_id."'")
            );
            $msg = $this->ld['regional_new_name_success'];
        } else {
            $msg = $this->ld['reconfirm_name_fill_area'];
        }
        Configure::write('debug', 0);
        $result['type'] = '0';
        $result['msg'] = $msg;
        die(json_encode($result));
    }
}
