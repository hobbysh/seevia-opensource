<?php

/*****************************************************************************
 * Seevia 资源管理
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
 *系统资源管理.
 *
 *对于resource这张表的增删改查
 *
 *@author   weizhngye 
 *
 *@version  $Id$
 */
class ResourcesController extends AppController
{
    public $name = 'Resources';
//	var $helpers = array('Html');
    public $uses = array('Resource','ResourceI18n');
    public $components = array('Pagination','RequestHandler','Phpexcel','Phpcsv'); // Added 
    public $helpers = array('Pagination');

    /**
     *resource主页列表.
     *
     *呈现数据库表resource的数据
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('resources_view');
        /*end*/
        $this->menu_path = array('root' => '/web_application/','sub' => '/resources/');
        $this->set('title_for_layout', $this->ld['resource_manage'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => '系统资源管理','url' => '/resources/');
        $conditions = array();
        //$conditions['and'][]['Resource.parent_id'] = 0;
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $conditions['and']['or']['ResourceI18n.name like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['Resource.code like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['Resource.resource_value like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
        $this->Resource->set_locale($this->backend_locale);
        $cond = array();
        $cond['conditions'] = $conditions;
        $cond['order'] = 'Resource.created desc';
        $resource = $this->Resource->tree($cond);//取所有资源

        $total = sizeof($resource);
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'resources','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Resource');
        $this->Pagination->init($cond, $parameters, $options);

        $start = ($page * $rownum) - $rownum;//当前页开始位置

        $resource = array_slice($resource, $start, $rownum);
        $this->set('resource', $resource);
    }

    /**
     *resource编辑.
     *
     *呈现数据库表resource的增加和更改
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function view($id = 0)
    {
        /*判断权限*/
        if ($id == 0) {
            $this->operator_privilege('resources_add');
        } else {
            $this->operator_privilege('resources_edit');
        }
        /*end*/
        $this->menu_path = array('root' => '/web_application/','sub' => '/resources/');
        $this->pageTitle = '编辑资源 - 资源管理'.' - '.$this->configs['shop_name'];
        $this->set('title_for_layout', $this->pageTitle);
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => '资源管理','url' => '/resources/');
        //$this->navigations[] = array('name'=>'编辑资源','url'=>'');
        $this->set('navigations', $this->navigations);
        $userinformation_name = '';
        if ($this->RequestHandler->isPost()) {
            if ($id != 0) {
                $this->data['Resource']['orderby'] = !empty($this->data['Resource']['orderby']) ? $this->data['Resource']['orderby'] : 50;
                $this->Resource->save($this->data);
                foreach ($this->data['ResourceI18n'] as $v) {
                    $resourceI18n_info = array(
                                 'id' => isset($v['id']) ? $v['id'] : 'null',
                                   'locale' => $v['locale'],
                                   'resource_id' => $id ,
                                   'name' => isset($v['name']) ? $v['name'] : '',
                                'description' => isset($v['description']) ? $v['description'] : '',
                                'modified' => date('Y-m-d H:i:s'),
                 );
                    $this->ResourceI18n->saveAll(array('ResourceI18n' => $resourceI18n_info));//更新多语言
                }
                foreach ($this->data['ResourceI18n'] as $k => $v) {
                    if ($v['locale'] == $this->backend_locales) {
                        $userinformation_name = $v['name'];
                    }
                }
            /*
            //操作员日志
            if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
            $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'编辑系统资源:'.$userinformation_name ,'operation');
            }
            */
        //	$this->flash("资源 ".$userinformation_name." 编辑成功。点击这里继续编辑该菜单。",'/Resources/view/'.$id,10);
            $this->redirect('/resources/');
            } else {
                $this->data['Resource']['orderby'] = !empty($this->data['Resource']['orderby']) ? $this->data['Resource']['orderby'] : 50;
                $this->Resource->saveAll(array('Resource' => $this->data['Resource']));
                $id = $this->Resource->getLastInsertId();
                foreach ($this->data['ResourceI18n'] as $k => $v) {
                    $v['resource_id'] = $id;
                    $this->ResourceI18n->saveAll(array('ResourceI18n' => $v));
                }
                foreach ($this->data['ResourceI18n'] as $k => $v) {
                    if ($v['locale'] == $this->backend_locales) {
                        $userinformation_name = $v['name'];
                    }
                }
            /*
            //操作员日志
            if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
            $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'添加系统资源:'.$userinformation_name ,'operation');
            }
            */
        //	$this->flash("资源 ".$userinformation_name."  添加成功。点击这里继续编辑该资源。",'/resources/view/'.$id,10);
            $this->redirect('/resources/');
            }
        }
        $this->data = $this->Resource->localeformat($id);
        $this->Resource->set_locale($this->backend_locale);
        $parentmenu = $this->Resource->find('all', array('conditions' => array('Resource.parent_id' => '0')));
        $this->set('parentmenu', $parentmenu);
        //leo20090722导航显示
        if ($id != 0) {
            $this->navigations[] = array('name' => $this->data['ResourceI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => '添加资源','url' => '');
        }
        $this->set('navigations', $this->navigations);
        //取版本标识
        $this->Resource->set_locale($this->backend_locale);
        $this->set('section', $this->Resource->find_assoc('section'));
    }

    /**
     *resource删除的方法.
     *
     *呈现数据库表resource的删除
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        /*判断权限*/
        if (!$this->operator_privilege('resources_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        /*end*/
        $system_info = $this->Resource->findById($id);
        $res = $this->Resource->find('count', array('conditions' => array('Resource.parent_id' => $id)));
        $result = array();
        if ($res > 0) {
            //$this->re('删除失败，该资源还有子资源','/Resources/','');
            //$this->redirect('/resources/');
            $result['flag'] = 2;
        } else {
            $this->ResourceI18n->deleteAll(array('ResourceI18n.resource_id' => $id));
            $this->Resource->delete(array('Resource.id' => $id));
            //操作员日志
  //  	    if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
 //   	    $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'删除系统资源:'.$system_info['SystemResourceI18n']['name'] ,'operation');
  //  	    }
            //$this->redirect('/resources/');
            $result['flag'] = 1;
        }
        die(json_encode($result));
    }
////////////////////////////////////////导出
       public function doload_csv_example()
       {
           $this->operator_privilege('resources_add');

           $this->menu_path = array('root' => '/web_application/','sub' => '/dictionaries/');
           $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
           $this->navigations[] = array('name' => $this->ld['resource_manage'],'url' => '/resources/');
           $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
           $this->set('title_for_layout', $this->ld['resource_manage'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
       }

    public function csv_uplods()
    {
        ////////////判断权限
           $this->operator_privilege('resources_add');
                //定义列表名数组
                 $fields = array('Resource.parent_id',
                                                   'Resource.code',
                                     'Resource.resource_value',
                                     'Resource.status',
                                     'Resource.section',
                                 'Resource.orderby',
                                'ResourceI18n.locale',
                                'ResourceI18n.name',
                                'ResourceI18n.description', );
        $fields_array = array(
                                              $this->ld['parent_resource'],
                                                   $this->ld['resource_code'],
                                     $this->ld['z_resource_value'],
                                     $this->ld['status'],
                                     $this->ld['version'],
                                     $this->ld['sort'],
                                $this->ld['z_language'],
                                $this->ld['resource_name'],
                                $this->ld['z_description'], );
        $newdatas = array();
        $newdatas[] = $fields_array;
                               //pr($newdatas);
                $Resource_all = $this->Resource->find('all', array('order' => 'Resource.id ', 'limit' => 5));
        $parent_id_resource = $this->Resource->find('list', array('fields' => array('id', 'code'), 'osder' => 'Resource.id', 'conditions' => array('parent_id' => 0)));
                //pr($parent_id_resource);

                foreach ($Resource_all as $k => $v) {     //pr($v);

                      //pr($v['Resource']['code']);

                    $user_tmp = array();
                    foreach ($fields  as $ks => $vs) {
                        //分解字符串为数组
                                      $fields_ks = explode('.', $vs);
                                     //pr($vs);

                                     if ($fields_ks[1] == 'parent_id') {
                                         $user_tmp[] = isset($parent_id_resource[$v['Resource']['parent_id']]) ? $parent_id_resource[$v['Resource']['parent_id']] : '';
                                     } else {
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                                     }
                    }

                    $newdatas[] = $user_tmp;
                }

        $nameexl = $this->ld['Resource_upload'].date('Ymd').'.csv';

        $this->Phpcsv->output($nameexl, $newdatas);
        die();
            ///////////////////权限
    }

     ////////////////上传文件
     public function csv_add()
     {

        ////////////判断权限
       ////////////判断权限
          $this->operator_privilege('resources_add');
      //获得提交过来的数组
         if (!empty($_FILES['file'])) {
             //echo "<script>alert('OKOKOKOK');</script>";
                  //文件不为空
                 if (!empty($_FILES['file'])) {
                     $this->menu_path = array('root' => '/web_application/','sub' => '/dictionaries/');
                     $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
                     $this->navigations[] = array('name' => $this->ld['resource_manage'] ,'url' => '/resources/');
                     $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
                     $this->set('title_for_layout', $this->ld['resource_manage'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
                               //文件错误大于0 提示 并且返回
                         if ($_FILES['file']['error'] > 0) {
                             echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/resources/csv_add';</script>";
                             die(); //如果不为空 并且没有错误 那么 读取这个文件
                         } else {
                             $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                                       //定义表的字段数组
                                    $fields_array = array(
                                            'Resource.parent_id',
                                                   'Resource.code',
                                     'Resource.resource_value',
                                     'Resource.status',
                                     'Resource.section',
                                     'Resource.orderby',
                                    'ResourceI18n.locale',
                                    'ResourceI18n.name',
                                    'ResourceI18n.description', );

                             $fieldarray = array(
                                              $this->ld['parent_resource'],
                                                   $this->ld['resource_code'],
                                     $this->ld['z_resource_value'],
                                     $this->ld['status'],
                                     $this->ld['version'],
                                     $this->ld['sort'],
                                $this->ld['z_language'],
                                $this->ld['resource_name'],
                                $this->ld['z_description'], );
                             $key_arr = array();
                             foreach ($fields_array as $k => $v) {
                                 $fields_k = explode('.', $v);
                                 $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                             }
                                    //pr($key_arr);
                                                      $csv_export_code = 'gb2312';
                             $i = 0;
                                         //循环		
                                while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                    if ($i == 0) {
                                        $check_row = $row[0];
                                               //pr($check_row);
                                                   $row_count = count($row);
                                        $check_row = iconv('GB2312', 'UTF-8', $check_row);
                                        $num_count = count($key_arr);
                                        ++$i;
                                    }
                                    $temp = array();
                                       //$k=0;循环一次row 是8次 所以说 k= 0 1 2 3 4 5 ........
                                        foreach ($row as $k => $v) {
                                            //pr($v);
                                        $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                        }
                                   //pr($v);
                                   //判断
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
                 }
         } elseif ($_POST['sub2'] == 2) {
             $checkbox_arr = $_REQUEST['checkbox'];

             foreach ($this->data as $key => $v) {
                 if (!in_array($key, $checkbox_arr)) {
                     continue;
                 }
                 pr($v);

                 $Resource_type = array('id','parent_id','code','resource_value','status','section','orderby');
                 $ResourceI18n_type = array('locale', 'name','description');

                 $resources_arr = array();//主表数组
                        $resources_i8n = array();//次表数组
                        foreach ($v as $ks => $vs) {
                            if (in_array($ks, $Resource_type)) {
                                $resources_arr[$ks] = $vs;
                            }
                            if (in_array($ks, $ResourceI18n_type)) {
                                $resources_i8n[$ks] = $vs;
                            }
                        }

                 $arr = array();
                 if (!empty($v['parent_id'])) {
                     $parentarr = $this->Resource->find('first', array('conditions' => array('Resource.code' => $resources_arr['parent_id'])));
                     if (empty($parentarr)) {
                         continue;
                     } else {
                         $conditions = array();
                         $conditions['Resource.parent_id'] = $parentarr['Resource']['id'];
                         $conditions['Resource.resource_value'] = $v['resource_value'];
                         if (!empty($v['code'])) {
                             $conditions['Resource.code'] = $v['code'];
                         }
                         $arr = $this->Resource->find('first', array('conditions' => $conditions));
                         $resources_arr['parent_id'] = $parentarr['Resource']['id'];
                     }
                 } else {
                     $conditions = array();
                     $conditions['Resource.parent_id'] = 0;
                     $conditions['Resource.code'] = $v['code'];
                     $arr = $this->Resource->find('first', array('conditions' => $conditions));
                     $resources_arr['parent_id'] = 0;
                 }

                 $resources_arr['id'] = isset($arr['Resource']['id']) ? $arr['Resource']['id'] : 0;

                 pr($resources_arr);
                 $this->Resource->save($resources_arr);
                 $i = $this->Resource->id;

                 $resources_i8n['resource_id'] = $this->Resource->id;
                 $i8n_arr = $this->
                        Resource->find('first', array('conditions' => array('ResourceI18n.locale' => $resources_i8n['locale'], 'ResourceI18n.resource_id' => $resources_i8n['resource_id'])));

                 $resources_i8n['id'] = isset($i8n_arr['ResourceI18n']['id']) ? $i8n_arr['ResourceI18n']['id'] : 0;
                 pr($resources_i8n);
                 $this->ResourceI18n->save($resources_i8n);
                 $Res18n_id = $this->ResourceI18n->id;
             }

             $this->redirect('/Resources/');
         }

                ////////权限	 
     }

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
