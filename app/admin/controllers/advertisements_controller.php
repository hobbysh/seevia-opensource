<?php

/*****************************************************************************
 * Seevia 广告管理
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
class AdvertisementsController extends AppController
{
    public $name = 'Advertisements';
    public $helpers = array('Html','Pagination','Ckeditor');
    public $components = array('Pagination','RequestHandler');
    public $uses = array('Template','Advertisement','AdvertisementI18n','AdvertisementPosition','Language','AdvertisementEffect','AdvertisementEffectDefault','OperatorLog');

    public function index($id = 0, $page = 1)
    {
        $this->redirect('/advertisement_positions/view/'.$id);
        $this->menu_path = array('root' => '/cms/','sub' => '/advertisement_positions/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['ad_position_list'],'url' => '/advertisement_positions/');
        $advertisement_position = $this->AdvertisementPosition->find('first', array('conditions' => array('id' => $id)));
        $this->navigations[] = array('name' => $this->ld['ads_list'].'-'.$advertisement_position['AdvertisementPosition']['name'],'url' => '');

        $condition = '';
        $condition['Advertisement.advertisement_position_id'] = $id;
        //关键字
        $advertisements_keywords = '';
        if (isset($this->params['url']['advertisements_keywords']) && $this->params['url']['advertisements_keywords'] != '') {
            $advertisements_keywords = $this->params['url']['advertisements_keywords'];
            $condition['AdvertisementI18n.name like'] = '%'.$advertisements_keywords.'%';
        }
        $advertisement_position_id = '';
        if (isset($this->params['url']['advertisement_position_id']) && $this->params['url']['advertisement_position_id'] != '') {
            $advertisement_position_id = $this->params['url']['advertisement_position_id'];
            $condition['Advertisement.advertisement_position_id'] = $advertisement_position_id;
        }
        $this->Advertisement->set_locale($this->locale);
        $total = $this->Advertisement->find('count', array('conditions' => $condition));//统计全部广告列表总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'Advertisement';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'advertisements','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Advertisement');
        $this->Pagination->init($condition, $parameters, $options);
        $fields[] = 'Advertisement.id';
        $fields[] = 'AdvertisementI18n.name';
        $fields[] = 'Advertisement.advertisement_position_id';
        $fields[] = 'Advertisement.media_type';
        $fields[] = 'AdvertisementI18n.start_time';
        $fields[] = 'AdvertisementI18n.end_time';
        $fields[] = 'Advertisement.click_count';
        $fields[] = 'Advertisement.orderby';
        $advertisement_list = $this->Advertisement->find('all', array('conditions' => $condition, 'order' => 'orderby asc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));
        $this->set('advertisement_list', $advertisement_list);//广告列表列表
        $this->set('id', $id);
        $this->set('advertisements_keywords', $advertisements_keywords);
        $fields = array();
        $condition = '';
        $fields[] = 'id';
        $fields[] = 'name';
        $advertisement_position_list = $this->AdvertisementPosition->find('all', array('conditions' => $condition, 'order' => 'orderby asc', 'fields' => $fields));
        $this->set('advertisement_position_list', $advertisement_position_list);//广告位置列表
        $this->set('advertisement_position_id', $advertisement_position_id);//广告位置id
        $advertisement_position_data = array();
        foreach ($advertisement_position_list as $k => $v) {
            $advertisement_position_data[$v['AdvertisementPosition']['id']] = $v['AdvertisementPosition']['name'];
        }
        $this->set('advertisement_position_data', $advertisement_position_data);//数据在列表上显示用
        $this->set('title_for_layout', $this->ld['ads_list_management'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($posid = 0, $id = 0)
    {
        //		$this->menu_path = array('root'=>'/cms/','sub'=>'/advertisement_positions/');
//        $this->set("title_for_layout",$this->ld['ads_list_management']." - ".$this->configs['shop_name']);
//        $this->navigations[]=array('name'=>$this->ld['manager_interface'],'url'=>'');
//        $this->navigations[]=array('name'=>$this->ld['ad_position_list'],'url' => '/advertisement_positions/');
        $this->menu_path = array('root' => '/system/','sub' => '/themes/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manager_templates'],'url' => '/themes/');

        $advertisement_position = $this->AdvertisementPosition->find('first', array('conditions' => array('id' => $posid)));
        if (!empty($advertisement_position)) {
            $template_Info = $this->Template->find('first', array('conditions' => array('Template.name' => $advertisement_position['AdvertisementPosition']['template_name'])));
            if (!empty($template_Info)) {
                $this->navigations[] = array('name' => $this->ld['template'].' - '.$template_Info['Template']['description'],'url' => '/themes/view/'.$template_Info['Template']['id']);
            }
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$advertisement_position['AdvertisementPosition']['name'],'url' => '/advertisement_positions/view/'.$posid);
        }
        if ($id != 0) {
            $this->set('id', $id);
            $advertisement_effects = $this->AdvertisementEffect->find('first', array('fields' => array('type'), 'conditions' => array('advertisements_id' => $id)));
            if (isset($advertisement_effects['AdvertisementEffect']['type'])) {
                $this->set('advertisement_effects_type', $advertisement_effects['AdvertisementEffect']['type']);
            }
        }
        $advertisement_effect = $this->AdvertisementEffectDefault->getformatcode('chi');
        $this->set('advertisement_effect_first_type', $advertisement_effect[0]['AdvertisementEffectDefault']['type']);
        if (is_array($advertisement_effect)) {
            $array_data = array();
            $configs_2 = array();
            $adeffctdefault_type = array();
            $advertisement_effect_default = array();
            $advertisement_effect_default = json_decode($advertisement_effect[0]['AdvertisementEffectDefault']['configs']);
            $advertisement_effect_con = array();
            if (isset($advertisement_effect_default)) {
                foreach ($advertisement_effect_default as $k => $v) {
                    $advertisement_effect_con[$k] = (array) $advertisement_effect_default[$k];
                }
                $this->set('advertisement_effect_con', $advertisement_effect_con);
            }
            foreach ($advertisement_effect as $k => $v) {
                $array_data[$v['AdvertisementEffectDefault']['type']] = $v['AdvertisementEffectDefault']['name'];
                $configs_2[$k] = (array) json_decode($v['AdvertisementEffectDefault']['configs']);
                $adeffctdefault_type[$v['AdvertisementEffectDefault']['type']] = $v['AdvertisementEffectDefault']['show_link'];
            }
            $this->set('array_data', $array_data);
            $this->set('configs_2', $configs_2);
            $this->set('adeffctdefault_type', $adeffctdefault_type);
        }
        $this->set('advertisement_effect', $advertisement_effect);
//        $array_data=array("0"=>"特效1","1"=>"特效2");
//        $this->set("array_data",$array_data);
        //$this->navigations[]=array('name'=>$this->ld['ads_list']."-".$advertisement_position["AdvertisementPosition"]["name"],'url' => '/advertisements/index/'.$posid);
        if ($id != 0) {
            $advertisementeffect = $this->AdvertisementEffect->find('first', array('conditions' => array('AdvertisementEffect.advertisements_id' => $id)));
            if (!empty($advertisementeffect)) {
                $ad_config = isset($advertisementeffect['AdvertisementEffect']['configs']) ? $advertisementeffect['AdvertisementEffect']['configs'] : '';
                $config = json_decode($ad_config);
                $m = '';
                if ($config) {
                    foreach ($config as $k => $v) {
                        $m[] = $v;
                    }
                    $this->set('m', $m);
                }
                $ad_images = isset($advertisementeffect['AdvertisementEffect']['images']) ? $advertisementeffect['AdvertisementEffect']['images'] : '';
                $images = json_decode($ad_images);
                $n = '';
                if (!empty($images)) {
                    foreach ($images as $k => $v) {
                        $n[] = (array) $v;
                    }
                }
                $this->set('n', $n);

                $this->set('advertisementeffect', $advertisementeffect);
            }
        }
        if ($id == 0) {
            $this->navigations[] = array('name' => $this->ld['add_ad'],'url' => '');
        }
        if ($this->RequestHandler->isPost()) {
            $this->data['Advertisement']['orderby'] = !empty($this->data['Advertisement']['orderby']) ? $this->data['Advertisement']['orderby'] : '50';
            if (isset($this->data['Advertisement']['id']) && $this->data['Advertisement']['id'] != '') {
                $this->Advertisement->save(array('Advertisement' => $this->data['Advertisement'])); //关联保存
            } else {
                $this->Advertisement->saveAll(array('Advertisement' => $this->data['Advertisement'])); //关联保存
                $id = $this->Advertisement->getLastInsertId();
            }
             //获取数据并存储
              //js处理有问题
        // 	if($this->data['AdvertisementEffect']['type']=="imagemenu"){  //特效1 默认启用
        if (isset($this->data['AdvertisementEffect']['status']) && $this->data['AdvertisementEffect']['status'] == 1) {
            if ($this->data['AdvertisementEffect']['type'] != -1) {
                if (!empty($this->data['AdvertisementEffect']['id'])) { //编辑特效
                     $this->data['AdvertisementEffect']['advertisements_id'] = $id;
                    $this->data['AdvertisementEffect']['locale'] = 'chi';
                    $this->data['AdvertisementEffect']['configs'] = json_encode($this->data['AdvertisementEffect']['configs']);
                    $this->data['AdvertisementEffect']['images'] = json_encode($this->data['AdvertisementEffect']['photo']);
                    $this->AdvertisementEffect->saveAll(array('AdvertisementEffect' => $this->data['AdvertisementEffect'])); //json的储
                } else {
                    //添加特效
                     $this->data['AdvertisementEffect']['id'] = '';
                    $this->data['AdvertisementEffect']['advertisements_id'] = $id;
                    $this->data['AdvertisementEffect']['locale'] = 'chi';
                    $this->data['AdvertisementEffect']['configs'] = json_encode($this->data['AdvertisementEffect']['configs']);
                    $this->data['AdvertisementEffect']['images'] = json_encode($this->data['AdvertisementEffect']['photo']);
                    //json的存储
                    $this->AdvertisementEffect->saveAll(array('AdvertisementEffect' => $this->data['AdvertisementEffect']));
                }
            }
        }
//	     	}elseif($this->data['AdvertisementEffect']['type']=="-1"&&!isset($this->data["Advertisement"]["id"])&&$this->data["Advertisement"]["id"]==""){
//	     	}else{
//		     	if(!empty($this->data["AdvertisementEffect"]["id"])){
//		     	echo "3";die;
//		     	$this->data["AdvertisementEffect"]["advertisements_id"]=$id;
//		     	$this->data["AdvertisementEffect"]["locale"]="chi";
//		     	if($this->data["AdvertisementEffect"]["type"]=="0"){ $this->data["AdvertisementEffect"]["type"]="imagemenu";
//		     	unset($this->data['AdvertisementEffect']['configs']);
//		     	}else{
//		     	$this->data["AdvertisementEffect"]["type"]="bxslider";
//		     	}
//		     	$this->data['AdvertisementEffect']['configs']=json_encode($this->data['AdvertisementEffect']['configs2']);
//				$this->data['AdvertisementEffect']['images']=json_encode($this->data['AdvertisementEffect']["photo"]);
//				
//			    $this->AdvertisementEffect->saveAll(array("AdvertisementEffect"=>$this->data["AdvertisementEffect"])); //json的储
//		     	}else{//不是特效1 启用添加
//		     	//	echo "5";die;
//		     	//pr($this->data['AdvertisementEffect']);die;
//		     	$this->data["AdvertisementEffect"]["id"]="";
//		     	$this->data["AdvertisementEffect"]["advertisements_id"]=$id;
//		     	$this->data["AdvertisementEffect"]["locale"]="chi";	 
//		     	if($this->data["AdvertisementEffect"]["type"]=="0"){
//		     	unset($this->data['AdvertisementEffect']['configs']);
//		     	$this->data["AdvertisementEffect"]["type"]="imagemenu";}else{
//		     	$this->data["AdvertisementEffect"]["type"]="bxslider"; }  	
//		     	$this->data['AdvertisementEffect']['configs']=json_encode($this->data['AdvertisementEffect']['configs2']);
//				$this->data['AdvertisementEffect']['images']=json_encode($this->data['AdvertisementEffect']["photo"]);
//				//json的存储
//				pr($this->data['AdvertisementEffect']);die;
//			    $this->AdvertisementEffect->saveAll(array("AdvertisementEffect"=>$this->data["AdvertisementEffect"])); 	
//		     	}
//	     	}

             //$this->AdvertisementI18n->deleteAll(array("Advertisement_id"=>$this->data['Advertisement']['id']));
            $this->AdvertisementI18n->deleteAll(array('Advertisement_id' => $this->data['Advertisement']['id'])); //删除原有多语言
            foreach ($this->data['AdvertisementI18n'] as $v) {
                $Advertisementi18n_info = array(
                      'locale' => $v['locale'],
                      'advertisement_id' => $id,
                       'name' => $v['name'],
                       'description' => $v['description'],
                      'url' => $v['url'],
                      'url_type' => $v['url_type'],
                      'start_time' => $v['start_time'],
                      'end_time' => $v['end_time'],
                      'code' => $v['code'],
                  );
                $this->AdvertisementI18n->saveAll(array('AdvertisementI18n' => $Advertisementi18n_info));//更新多语言
            }
            foreach ($this->data['AdvertisementI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            $this->set('posid', $posid);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_or_edit_ad'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/advertisements/index/'.$posid);
        }
        //取得广告位
        $templatenamearr = $this->AdvertisementPosition->find('first', array('conditions' => array('id' => $posid), 'fields' => 'template_name'));
        $advertisement_positions = $this->AdvertisementPosition->find('all', array('conditions' => array('template_name' => !empty($templatenamearr) ? $templatenamearr['AdvertisementPosition']['template_name'] : ''), 'fields' => array('id', 'name')));
        $this->set('advertisement_positions', $advertisement_positions);
        $advertisements_data = $this->Advertisement->localeformat($id);
        if (isset($advertisements_data['AdvertisementI18n'][$this->locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$advertisements_data['AdvertisementI18n'][$this->locale]['name'],'url' => '');
        }
        $this->set('advertisements_data', $advertisements_data);
        $this->set('posid', $posid);
    }

    /**
     *删除一个广告列表.
     *
     *@param int $id 输入广告列表ID
     */
    public function remove($id)
    {
        $this->Advertisement->hasOne = array();
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_the_ad_list_failure'];
        $pn = $this->AdvertisementI18n->find('list', array('fields' => array('AdvertisementI18n.advertisement_id', 'AdvertisementI18n.name'), 'conditions' => array('AdvertisementI18n.advertisement_id' => $id, 'AdvertisementI18n.locale' => $this->locale)));
        $this->Advertisement->deleteAll(array('id' => $id));
        $this->AdvertisementEffect->deleteAll(array('advertisements_id' => $id));
        $this->AdvertisementI18n->deleteAll(array('Advertisement_id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除广告:id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_the_ad_list_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function changestatus($id)
    {
        if (!empty($id)) {
            $status = $result = $this->AdvertisementEffect->find('first', array('conditions' => array('AdvertisementEffect.id' => $id)));
            if ($status['AdvertisementEffect']['status'] == '1') {
                $result = $this->AdvertisementEffect->save(array('id' => $id, 'status' => 0));
            } else {
                $result = $this->AdvertisementEffect->save(array('id' => $id, 'status' => 1));
            }
        }
        $result['flag'] = 1;
           //$result["message"] = "操作成功";
         Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function add()
    {
        $code = $_POST['code'];
        $id = $_POST['id'];
        $result = array();
        $configs = $this->AdvertisementEffectDefault->getformatconflgs($code);
        $result['code'] = $code;
        foreach ($configs as $k => $v) {
            if (empty($v['code']) && empty($v['default']) && empty($v['type']) && empty($v['remark'])) {
                $result['flag'] = 2;
            }
        }
//		$advertisement_effect_all = $this->AdvertisementEffectDefault->getformatcode("chi");
//        foreach($advertisement_effect_all as $k=>$v){
//       		if($code==$v['AdvertisementEffectDefault']['type']){
//       	    	$configs=json_decode($v['AdvertisementEffectDefault']['configs']);
//       		}
//       }
          if (isset($id)) {
              $advertisement_effect = $this->AdvertisementEffect->find('first', array('conditions' => array('type' => $code, 'advertisements_id' => $id)));
              if (isset($advertisement_effect) && !empty($advertisement_effect)) {
                  //	$advertisement_effect=array_merge($advertisement_effect,$configs);
                    $result['flag'] = 1;
                  $result_configs = array();
                  $result_configs = array();
                  $result_configs = (array) json_decode($advertisement_effect['AdvertisementEffect']['configs']);
                //	$result["message"]=$result_configs;
                    foreach ($configs as $k => $v) {
                        //	$configs[$k]['default']=$result_configs[$v['code']];
                        $configs[$k]['default_value'] = $result_configs[$v['code']];
                    }
                  $result_config[0] = $configs;
                  $result['message'] = $result_config;
              } else {
                  $result['flag'] = 1;
                  $result_configs = array();
                  $result_configs[0] = $configs;
                  $result['message'] = $result_configs;
              }
          } else {
              $result['flag'] = 1;
              $result_configs = array();
              $result_configs[0] = $configs;
              $result['message'] = $result_configs;
          }
   //   $result['flag']=0;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
