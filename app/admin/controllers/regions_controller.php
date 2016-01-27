<?php

/**
 *这是一个名为 RegionsController 的控制器
 *后台区域控制控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class RegionsController extends AppController
{
    public $name = 'Regions';
    public $components = array('RequestHandler','Pagination','Phpexcel','Phpcsv');
    public $helpers = array('Html','Form','Pagination');
    public $uses = array('RegionI18n','Region');

    public function index($page = 1)
    {
        $this->operator_privilege('region_view');
        $this->menu_path = array('root' => '/system/','sub' => '/regions/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reviews_regions'],'url' => '');

        $this->Region->set_locale($this->backend_locale);
        $condition = '';
        $condition['Region.parent_id'] = 0;
        $total = $this->Region->find('count', array('conditions' => $condition));
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'regions','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'User');
        $this->Pagination->init($condition, $parameters, $options);
        $cond = array(
            'conditions' => $condition,
            'order' => 'Region.orderby,Region.id',
            'fields' => array('Region.*','RegionI18n.name'),
            'page' => $page,
            'limit' => $rownum,
        );
        $region_list = $this->Region->find('all', $cond);
        $this->set('region_list', $region_list);

        if (!empty($region_list)) {
            $region_ids = array();
            foreach ($region_list as $k => $v) {
                $region_ids[] = $v['Region']['id'];
            }
            $region_child_info = $this->Region->find('all', array('conditions' => array('Region.parent_id' => $region_ids), 'fields' => array('Region.parent_id', 'count(*) as data_count'), 'group' => 'Region.parent_id', 'recursive' => -1));
            $region_child_list = array();
            foreach ($region_child_info as $v) {
                $region_child_list[$v['Region']['parent_id']] = $v[0]['data_count'];
            }
            $this->set('region_child_list', $region_child_list);
        }
        $this->set('title_for_layout', $this->ld['reviews_regions'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('region_add');
        } else {
            $this->operator_privilege('region_edit');
        }
        $this->menu_path = array('root' => '/system/','sub' => '/regions/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reviews_regions'],'url' => '/regions/');
        $this->set('title_for_layout', $this->ld['add_edit_page'].'-'.$this->ld['reviews_regions'].'-'.$this->configs['shop_name']);

        if ($this->RequestHandler->isPost()) {
            $this->Region->save($this->data['Region']);
            $region_id = $this->Region->id;
            if (!empty($this->data['RegionI18n'])) {
                foreach ($this->data['RegionI18n'] as $k => $v) {
                    $this->RegionI18n->deleteAll(array('RegionI18n.region_id' => $region_id, 'RegionI18n.locale' => $v['locale']));
                    $v['region_id'] = $region_id;
                    $this->RegionI18n->saveAll($v);
                }
            }
            //操作员日志
//    	    if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
//    	    	$this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'删除系统资源:'.$system_info['SystemResourceI18n']['name'] ,'operation');
//    	    }
            $this->redirect('/regions/');
        }
        $this->data = $this->Region->localeformat($id);
        $this->Region->set_locale($this->backend_locale);
        $region_list = $this->Region->find('all', array('fields' => array('Region.id', 'RegionI18n.name'), 'conditions' => array('Region.id !=' => $id), 'order' => 'Region.parent_id,Region.orderby,Region.id'));
        $this->set('region_list', $region_list);
    }

    public function remove($id = 0)
    {
        $this->operator_privilege('region_remove');
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_attribute_failure'];
        $this->Region->deleteAll(array('Region.id' => $id));
        $this->RegionI18n->deleteAll(array('RegionI18n.region_id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['remove'].' '.$this->ld['reviews_regions'].'id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_attribute_success'];
        die(json_encode($result));
    }

    /**
     *处理区域分集.
     */
    public function choice()
    {

//		if($this->RequestHandler->isPost()){
            $this->Region->set_locale($this->locale);
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
                        if ($a != $this->languages['please_choose']) {
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

//			pr($this->regions_selects);
//		}

       Configure::write('debug', 0);

        $this->layout = 'ajax';
    }
    /**
     *输出区域分集.
     *
     *@param array $tree 区域树
     *@param string $str 要选中ID 用空格分割
     */
    public function children($tree, $str)
    {
        $region_id_array = explode(' ', trim($str));
        $region_str = '';
    //	pr($region_id_array);
        if (sizeof($region_id_array) > 0) {
            foreach ($region_id_array as $k => $v) {
                $region_info = $this->Region->findbyid($v);
                if ($k < sizeof($region_id_array) - 1) {
                    $region_str .= $region_info['Region']['id'].' ';
                } else {
                    $region_str .= $region_info['Region']['id'];
                }
            }
        }
        $region_array = explode(' ', trim($region_str));
        $deep = sizeof($region_array);
// 		pr($region_array);
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
        Configure::write('debug', 0);
        $this->regions_selects[] = $select;
        if ($deep >= 1 && isset($subtree) && sizeof($subtree)) {
            $this->children($subtree, implode(' ', array_slice($region_array, 1)));
        }
    }

    /**
     *格式化区域分集.
     */
    public function twochoice()
    {
        //		if($this->RequestHandler->isPost()){
            $this->Region->set_locale($this->locale);
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
                        if ($a != $this->languages['please_choose']) {
                            $str .= ' '.$a;
                            $this->regions_selects = array();
                            $this->children($regions, $str);
                        }
                    }
                }
            }
        }
        if (isset($_REQUEST['ii'])) {
            $this->set('ii', $_REQUEST['ii']);
        }
        $this->set('regions_selects', $this->regions_selects);
        if (isset($_POST['updateaddress_id'])) {
            $this->set('updateaddress_id', $_POST['updateaddress_id']);
        }
    //		pr($this->regions_selects);
//		}
        Configure::write('debug', 0);
        $this->layout = 'ajax';
    }

    public function regions_list($id = 0)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $this->operator_privilege('region_view');
        $this->Region->set_locale($this->backend_locale);
        $condition = '';
        $condition['Region.parent_id'] = $id;
        //设置查询条件
        $cond = array(
            'conditions' => $condition,
            'order' => 'Region.orderby,Region.id',
            'fields' => array('Region.*','RegionI18n.name'),
        );
        //查询
        $region_list = $this->Region->find('all', $cond);
        $this->set('region_list', $region_list);
        $this->set('region_id', $id);

        if (!empty($region_list)) {
            $region_ids = array();
            foreach ($region_list as $k => $v) {
                $region_ids[] = $v['Region']['id'];
            }
            $region_child_info = $this->Region->find('all', array('conditions' => array('Region.parent_id' => $region_ids), 'fields' => array('Region.parent_id', 'count(*) as data_count'), 'group' => 'Region.parent_id', 'recursive' => -1));
            $region_child_list = array();
            foreach ($region_child_info as $v) {
                $region_child_list[$v['Region']['parent_id']] = $v[0]['data_count'];
            }
            $this->set('region_child_list', $region_child_list);
        }
    }

    public function doload_csv_example()
    {
         $this->menu_path = array('root' => '/web_application/','sub' => '/dictionaries/');
        $this->navigations[] = array('name' => $this->ld['system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['region'].$this->ld['region_view'],'url' => '/regions/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $this->set('title_for_layout', $this->ld['region'].$this->ld['region_view'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
    }
    
    public function upload_csv()
    {
        $fields = array(
        	           'RegionI18n.id',
                        'RegionI18n.locale',
                        'Region.parent_id',
                        'RegionI18n.name',
                        'Region.abbreviated',
                        'Region.param01',
                        'Region.param02',
                        'Region.param03',
                        'Region.orderby',
                        'RegionI18n.description'
                    );
        $fields_array = array($this->ld['language'],
                        $this->ld['superior_region'],
                        $this->ld['z_name'],
                        $this->ld['abbreviated'],
                        $this->ld['subparameter'],
                        $this->ld['subparameter'],
                        $this->ld['subparameter'],
                        $this->ld['sort'] ,
                        $this->ld['description']);
        $newdatas = array();
        $newdatas[] = $fields_array;
                        //查询3条数据
        $this->Region->set_locale($this->backend_locale);
        $Region_all = $this->Region->find('all', array('order' => 'Region.id', 'limit' => 5));

        //循环数组
        foreach ($Region_all as $k => $v) {
            $user_tmp = array();
            foreach ($fields as $ks => $vs) {
                $fields_ks = explode('.', $vs);
                if ($fields_ks[1] != 'parent_name') {
                    $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                } else {
                    $name = $this->RegionI18n->find('first', array('fields' => array('RegionI18n.name'), 'conditions' => array('RegionI18n.region_id' => $v['Region']['parent_id'])));
                    $user_tmp[] = !empty($name['RegionI18n']['name']) ? $name['RegionI18n']['name'] : '';
                }
            }

            $newdatas[] = $user_tmp;
        }

        $nameexl = $this->ld['region'].''.$this->ld['export'].date('Ymd').'.csv';
        $this->Phpcsv->output($nameexl, $newdatas);
        die();
    }

    ////导入
     public function csv_add()
     {
         ////////////判断权限
          $this->operator_privilege('region_add');
              //接收过来的文件不为空
            if (!empty($_FILES['file'])) {
                $this->menu_path = array('root' => '/web_application/','sub' => '/dictionaries/');
                $this->navigations[] = array('name' => $this->ld['system'],'url' => '');
                $this->navigations[] = array('name' => $this->ld['reviews_regions'],'url' => '/regions/');
                $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
                $this->set('title_for_layout', $this->ld['reviews_regions'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
               //文件错误大于0 提示 并且返回
            if ($_FILES['file']['error'] > 0) {
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/attributes/doload_csv_example';</script>";
            } else {
                //文件没有错误 就显示文件
             $handle = @fopen($_FILES['file']['tmp_name'], 'r');
              //定义表的字段数组
               $fields_array = array(
							'RegionI18n.locale',
							'Region.parent_name',
							'RegionI18n.name',
							'Region.abbreviated',
							'Region.param01',
							'Region.param02',
							'Region.param03',
							'Region.orderby',
							'RegionI18n.description', );

                $fieldarray = array(
                                    $this->ld['language'],
                                $this->ld['superior_region'],
                                        $this->ld['z_name'],
                                    $this->ld['abbreviated'],
                                    $this->ld['subparameter'],
                                    $this->ld['subparameter'],
                                    $this->ld['subparameter'],
                                    $this->ld['sort'],
                                      $this->ld['description'], );
             //定义预览标题
             $key_arr = array();
                foreach ($fields_array as $k => $v) {
                    $fields_k = explode('.', $v);
                    $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                }
                $csv_export_code = 'gb2312';
                $i = 0;
                while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                    if ($i == 0) {
                        $check_row = $row[0];
                        $row_count = count($row);
                        $check_row = iconv('GB2312', 'UTF-8', $check_row);
                        $num_count = count($key_arr);
                        ++$i;
                    }
                    $temp = array();
                    foreach ($row as $k => $v) {
                        $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                    }
                    if (!isset($temp) || empty($temp)) {
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/users/uploadusers';</script>";
                        die();
                    }
                    $data[] = $temp;
                }
                fclose($handle);
                $this->set('fieldarray', $fieldarray);
                $this->set('key_arr', $key_arr);
                $this->set('data_list', $data);
            }
            }//第一次if
         //判断点击添加之后

      elseif (!empty($this->data)) {
          $checkbox_arr = $_REQUEST['checkbox'];
             //循环提交过来值数据 
            foreach ($this->data as $key => $v) {
                //判断选择里那些多选框  
                if (!in_array($key, $checkbox_arr)) {
                    continue;
                }
                          //定义两个数组 为添加表的字段 数组 
                          $Region_finds = array('parent_name','abbreviated','param01','param02','param03','orderby');
                $RegionI18n_finds = array('locale','name','description');
                $Region_arr = array();//主表数组
                    $RegionI18n_arr = array();//次表数组

                    //循环 每一条数据
                    foreach ($v as $ks => $vs) {
                        //判断分别分给两个数组
                        if (in_array($ks, $Region_finds)) {
                            $Region_arr[$ks] = $vs;
                        }
                        if (in_array($ks, $RegionI18n_finds)) {
                            $RegionI18n_arr[$ks] = $vs;
                        }
                    }
                unset($this->Region->id);
                unset($this->RegionI18n->id);
                           //先根据自己的名称找到自己
                                                   $region = $this->RegionI18n->find('first', array('conditions' => array('RegionI18n.name' => $RegionI18n_arr['name'], 'RegionI18n.locale' => $RegionI18n_arr['locale'])));
                                //如果有自己的数据 用自己的id
                           if (!empty($region)) {
                               $this->Region->id = $region['RegionI18n']['region_id'];
                               $this->RegionI18n->id = $region['RegionI18n']['id'];
                           }
                                       //上级不为空 
                          if (!empty($Region_arr['parent_name'])) {
                              $parent_region = $this->RegionI18n->find('first', array('conditions' => array('RegionI18n.name' => $Region_arr['parent_name'], 'RegionI18n.locale' => $RegionI18n_arr['locale'])));
                        //判断能不能查询到
                                 if (!empty($parent_region)) {
                                     $parent_id = $parent_region['RegionI18n']['region_id'];
                                 } else {
                                     $parent_id = '-1';
                                 }
                             //上级名称为空 那么为最顶级 parent_id 为空
                          } else {
                              $parent_id = 0;
                          }

                if ($parent_id != '-1') {
                    $Region_arr['parent_id'] = $parent_id;
                    if (empty($Region_arr['orderby'])) {
                        $Region_arr['orderby'] = 50;
                    }
                    $this->Region->save($Region_arr);
                    $RegionI18n_arr['region_id'] = $this->Region->id;
                    $this->RegionI18n->save($RegionI18n_arr);
                }
            }
          $this->redirect('/regions');
      }
     }
   ////////////////////////////////////////////
 public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"')
 {
     $d = preg_quote($d);
     $e = preg_quote($e);
     $_line = '';
     $eof = false;
     while ($eof != true) {
         $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
         $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
         if ($itemcnt % 2 == 0) {
             $eof = true;
         }
     }
     $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
     $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
     preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
     $_csv_data = $_csv_matches[1];
     for ($_csv_i = 0; $_csv_i < count($_csv_data); ++$_csv_i) {
         $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
         $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
     }

     return empty($_line) ? false : $_csv_data;
 }
}
