<?php

/*****************************************************************************
 * Seevia 文件管理
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
 *这是一个名为 UploadArticlesController 的控制器
 *后台文章批量上传控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class UploadFilesController extends AppController
{
    public $name = 'UploadFiles';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript');
    public $uses = array('Document','Resource','Dictionary','OperatorLog');

    public function index()
    {
        $this->operator_privilege('upload_files_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/cms/','sub' => '/upload_files/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['file_ma'],'url' => '/upload_files/');

        $condition = '';
        if (isset($this->params['url']['name']) && $this->params['url']['name'] != '') {
            $keyword = trim($this->params['url']['name']);
            $keyword = str_replace('_', '[_]', $keyword);
            $keyword = str_replace('%', '[%]', $keyword);

            $condition['Document.name LIKE'] = '%'.$keyword.'%';
            $this->set('name', $this->params['url']['name']);
        }
        if (isset($this->params['url']['date']) && $this->params['url']['date'] != '') {
            $condition['and']['Document.created >='] = $this->params['url']['date'].' 00:00:00';

            $this->set('date', $this->params['url']['date']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
            $condition['and']['Document.created <='] = $this->params['url']['date2'].' 23:59:59';

            $this->set('date2', $this->params['url']['date2']);
        }
        $total = $this->Document->find('count', array('conditions' => $condition));
        $page = 1;
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'upload_files','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Document');
        $this->Pagination->init($condition, $parameters, $options);
        $uploadfiles = $this->Document->find('all', array('conditions' => $condition, 'order' => 'Document.orderby asc', 'limit' => $rownum, 'page' => $page));
        $this->set('uploadfiles', $uploadfiles);
        $this->set('title_for_layout', $this->ld['file_list'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        }
    /**
     *显示上传界面.
     */
    public function add()
    {
        $this->operator_privilege('upload_files_add');
        $this->menu_path = array('root' => '/cms/','sub' => '/upload_files/');
        $this->set('title_for_layout', $this->ld['file_uplaod'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['file_ma'],'url' => '/upload_files/');
        $this->navigations[] = array('name' => $this->ld['file_uplaod'],'url' => '');

        $file_types = isset($this->configs['files_format']) ? $this->configs['files_format'] : 'txt,pdf,doc,docx,xls,xlsx,zip,rar';
        //pr($this->configs['files_format']);
        $this->set('file_types', $file_types);
        $Resource_info = $this->Resource->getformatcode(array('csv_export_code'), $this->locale, false);//资源库信息
        $this->set('Resource_info', $Resource_info);
        $filesizes = 0;
        $uploadfiles = $this->Document->find('all', array('fileds' => 'Document.file_size'));
        foreach ($uploadfiles as $fk => $fv) {
            $filesizes = $fv['Document']['file_size'] + $filesizes;
        }
        if ($this->RequestHandler->isPost()) {
        
            $files = $_FILES; 
            $files_size = isset($files['data']['size']['Document']['file_url']) ? $files['data']['size']['Document']['file_url'] : 0;
            if ($files_size == 0) {
                $msg = $this->ld['file_can_not_empty'];
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/admin/upload_files/add/"</script>';
                die();
            } else {
                $filesizes = $filesizes + $files_size;
                if ($filesizes > 200 * 1024 * 1024) {
                    $msg = $this->ld['total_size_exceed'];
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/admin/upload_files/add/"</script>';
                    die();
                }
            }
            if ($files_size > 10 * 1024 * 1024) {
                $msg = $this->ld['file_attachment_size_exceeds'];
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/admin/upload_files/add/"</script>';
                die();
            } else {
                foreach ($this->data as $k => $v) {
                    if (isset($v['file_url']) && is_array($v['file_url'])) {
                        if (isset($v['file_url']['tmp_name']) && !empty($v['file_url']['tmp_name'])) {
                            $file_name = $v['file_url']['name'];
                            $file_types = explode('.', $file_name);
                            $dian_count = count($file_types) - 1;
                            $file_type = isset($file_types[$dian_count]) ? $file_types[$dian_count] : '';
                            $file_t = '.'.$file_type;
                            $filename = str_replace($file_t, '', $file_name);
                            if (!empty($file_type)) {
                                $Document = $this->Document->find('first', array('conditions' => array('Document.name' => $filename, 'Document.type' => $file_type)));
                                if (!empty($Document)) {
                                    $msg = $this->ld['has_uploaded_upload_another'];
                                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/admin/upload_files/add/"</script>';
                                    die();
                                }
                            }
                            $dir_root = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
                            $this->mkdirs($dir_root.'/media/files/');
                            move_uploaded_file($v['file_url']['tmp_name'], $dir_root.'/media/files/'.date('Ymd').$file_name);
                            @chmod($dir_root.'/media/files/'.date('Ymd').$file_name, 0777);
                            $file_path = $dir_root.'/media/files/'.date('Ymd').$file_name;
                            $v['file_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/media/files/'.date('Ymd').$file_name;
                        } else {
                            $v['file_url'] = implode(';', $v['file_url']);
                        }
                    }
                    $file_url = $v['file_url'];
                    $files_info = array(
                                   'id' => isset($file_id) ? $file_id : '',
                                   'file_url' => isset($file_url) ? $file_url : '',
                                   'type' => isset($file_type) ? $file_type : '',
                                   'name' => isset($_POST['file_nick'])&&$_POST['file_nick']!=""?$_POST['file_nick']:(!empty($filename)?$filename:''),
                                   'file_size' => isset($this->data['Document']['file_url']['size']) ? $this->data['Document']['file_url']['size'] : '',
                                   'file_path' => isset($file_path) ? $file_path : '',
                                   'orderby' =>isset($_POST['file_sort'])&&$_POST['file_sort']!=""?$_POST['file_sort']:50,

                             );
                             //pr($files_info);die();
                    $this->Document->save(array('Document' => $files_info));//更新多语言
               $id = isset($file_id) ? $file_id : $this->Document->getLastInsertId();
                }

            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['file_uplaod'].':'.$file_name, $this->admin['id']);
            }
                $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
            }
        }
    }
    //ajax上传图片
    public function ajax_add()
    {
        $filesizes = 0;
        $uploadfiles = $this->Document->find('all', array('fileds' => 'Document.file_size'));
        foreach ($uploadfiles as $fk => $fv) {
            $filesizes = $fv['Document']['file_size'] + $filesizes;
        }
        if ($this->RequestHandler->isPost()) {
            $result['flag'] = 1;
            $files = $_FILES;
            $files_size = isset($files['product_file_url']['size']) ? $files['product_file_url']['size'] : 0;
            if ($files_size == 0) {
                $result['flag'] = 0;
                $result['msg'] = $this->ld['file_can_not_empty'];
                Configure::write('debug', 0);
                $this->layout = 'ajax';
                die(json_encode($result));
            } else {
                $filesizes = $filesizes + $files_size;
                if ($filesizes > 200 * 1024 * 1024) {
                    $result['msg'] = $this->ld['total_size_exceed'];
                    Configure::write('debug', 0);
                    $this->layout = 'ajax';
                    die(json_encode($result));
                }
            }
            if ($files_size > 10 * 1024 * 1024) {
                $result['flag'] = 0;
                $result['msg'] = $this->ld['file_attachment_size_exceeds'];
                Configure::write('debug', 0);
                $this->layout = 'ajax';
                die(json_encode($result));
            } else {
                if (isset($files['product_file_url']) && is_array($files['product_file_url'])) {
                    if (isset($files['product_file_url']['tmp_name']) && !empty($files['product_file_url']['tmp_name'])) {
                        $file_name = $files['product_file_url']['name'];
                        $file_types = explode('.', $file_name);
                        $dian_count = count($file_types) - 1;
                        $file_type = isset($file_types[$dian_count]) ? $file_types[$dian_count] : '';
                        $file_t = '.'.$file_type;
                        $filename = str_replace($file_t, '', $file_name);
                        if (!empty($file_type)) {
                            $Document = $this->Document->find('first', array('conditions' => array('Document.name' => $filename, 'Document.type' => $file_type)));
                            if (!empty($Document)) {
                                $result['msg'] = $this->ld['has_uploaded_upload_another'];
                                $result['flag'] = 0;
                                Configure::write('debug', 0);
                                $this->layout = 'ajax';
                                die(json_encode($result));
                            }
                        }
                        $dir_root = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
                        $this->mkdirs($dir_root.'/media/files/');
                        move_uploaded_file($files['product_file_url']['tmp_name'], $dir_root.'/media/files/'.date('Ymd').$file_name);
                        @chmod($dir_root.'/media/files/'.date('Ymd').$file_name, 0777);
                        $file_path = $dir_root.'/media/files/'.date('Ymd').$file_name;
                        $files['file_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/media/files/'.date('Ymd').$file_name;
                    } else {
                        $files['file_url'] = implode(';', $files['file_url']);
                    }
                }
                $file_url = $files['file_url'];
                $result['file_url'] = $file_url;
                $files_info = array(
                                   'id' => isset($file_id) ? $file_id : '',
                                   'file_url' => isset($file_url) ? $file_url : '',
                                   'type' => isset($file_type) ? $file_type : '',
                                   'name' => isset($filename) ? $filename : '',
                                   'file_size' => isset($files['product_file_url']['size']) ? $files['product_file_url']['size'] : '',
                                   'file_path' => isset($file_path) ? $file_path : '',
                                   'orderby' => 50,

                             );
                $this->Document->save(array('Document' => $files_info));//更新多语言
               $id = isset($file_id) ? $file_id : $this->Document->getLastInsertId();
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['file_uplaod'].':'.$file_name, $this->admin['id']);
            }
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function edit($id=0)
    {
        $this->operator_privilege('upload_files_edit');
        $this->menu_path = array('root' => '/cms/','sub' => '/upload_files/');
        $this->set('title_for_layout', $this->ld['file_edit'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['file_ma'],'url' => '/upload_files/');
        $this->navigations[] = array('name' => $this->ld['file_edit'],'url' => '');

        $file_types = isset($this->configs['files_format']) ? $this->configs['files_format'] : 'pdf,doc,docx,xls,xlsx,zip,rar';
        $this->set('file_types', $file_types);
        $uploadfiles = $this->Document->find('first', array('conditions' => array('Document.id' => $id)));
        $filename = $uploadfiles['Document']['name'].'.'.$uploadfiles['Document']['type'];
        $this->set('filename', $filename);
        $this->set('uploadfiles', $uploadfiles);
        $filesizes = 0;
        $uploadfiles = $this->Document->find('all', array('conditions' => array('Document.id <>' => $id), 'fileds' => 'Document.file_size'));
        foreach ($uploadfiles as $fk => $fv) {
            $filesizes = $fv['Document']['file_size'] + $filesizes;
        }
        if ($this->RequestHandler->isPost()) {
            $this->data['Document']['orderby'] = !empty($this->data['Document']['orderby']) ? $this->data['Document']['orderby'] : 50;
            $id =isset($this->data['Document']['id'])&&$this->data['Document']['id']!=""?$this->data['Document']['id']:"";
            if (isset($this->data['upload_file']['tmp_name']) && !empty($this->data['upload_file']['tmp_name'])) {
                $files_size = isset($this->data['upload_file']['size']) ? $this->data['upload_file']['size'] : 0;
                $filesizes = $filesizes + $files_size;
                if ($filesizes > 200 * 1024 * 1024) {
                    $result['msg'] = $this->ld['total_size_exceed'];
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$result['msg'].'");	window.location.href="/admin/upload_files/edit/'.$id.'"</script>';
                    die();
                } elseif ($files_size > 10 * 1024 * 1024) {
                    $result['msg'] = $this->ld['file_attachment_size_exceeds'];
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$result['msg'].'");	window.location.href="/admin/upload_files/edit/'.$id.'"</script>';
                    die();
                } else {
                    $file_name = $this->data['upload_file']['name'];
                    $file_types = explode('.', $file_name);
                    $dian_count = count($file_types) - 1;
                    $file_type = isset($file_types[$dian_count]) ? $file_types[$dian_count] : '';
                    $file_path_old = isset($this->data['Document']['file_path']) ? $this->data['Document']['file_path'] : '';
                    unlink($file_path_old);
                    $dir_root = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
                    $this->mkdirs($dir_root.'/media/files/');
                    $tmp_name = isset($this->data['upload_file']['tmp_name']) ? $this->data['upload_file']['tmp_name'] : '';
                    move_uploaded_file($tmp_name, $dir_root.'/media/files/'.date('Ymd').$file_name);
                    @chmod($dir_root.'/media/files/'.date('Ymd').$file_name, 0777);
                    $file_path = $dir_root.'/media/files/'.date('Ymd').$file_name;
                    $file_url = 'http://'.$_SERVER['HTTP_HOST'].'/media/files/'.date('Ymd').$file_name;
                    $files_info = array(
                                       'id' => isset($this->data['Document']['id']) ? $this->data['Document']['id'] : '',
                                       'file_url' => isset($file_url) ? $file_url : '',
                                       'type' => isset($file_type) ? $file_type : '',
                                       'name' => isset($this->data['Document']['name']) ? $this->data['Document']['name'] : '',
                                       'file_size' => isset($this->data['upload_file']['size']) ? $this->data['upload_file']['size'] : '',
                                       'file_path' => isset($file_path) ? $file_path : '',
                                       'orderby' => $this->data['Document']['orderby'],

                                 );
                    $this->Document->save(array('Document' => $files_info));//更新多语言
                }
            } else {
                $this->Document->save(array('Document' => $this->data['Document']));//更新多语言
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['file_edit'].':'.$this->data['Document']['name'], $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
    }
    public function remove($id)
    {
        $this->operator_privilege('upload_files_remove');
        $Document = $this->Document->find('first', array('conditions' => array('Document.id' => $id)));
        unlink($Document['Document']['file_path']);
//		unlink(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))."/data/files/".$Document["Document"]["name"].".".$Document["Document"]["type"]);
        $this->Document->deleteAll(array('id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_file'].':'.$Document['Document']['name'], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }
    public function batch_operations()
    {
        $file_checkboxes = $_REQUEST['checkboxes'];
        foreach ($file_checkboxes as $k => $v) {
            $Document = $this->Document->find('first', array('conditions' => array('Document.id' => $v)));
            unlink($Document['Document']['file_path']);
            $this->Document->deleteAll(array('Document.id' => $v));
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['flag'] = '1';
        die(json_encode($result));
    }
    
    public function file_size()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $files = $_FILES;
            var_dump($files);
            $files_size = isset($files['data']['size']['Document']['file_url']) ? $files['data']['size']['Document']['file_url'] : '';
            if ($files_size > 500 * 1024) {
                $result['code'] = '0';
                die(json_encode($result));
            } else {
                $result['code'] = '1';
                die(json_encode($result));
            }
        }
         // die(json_encode($result));
    }
}
