<?php

/*****************************************************************************
 * Seevia 字典语言管理
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
class DictionariesController extends AppController
{
    public $name = 'Dictionaries';
    public $helpers = array('Html','Pagination');
      //调用插件                                                                     //插件名称
    public $components = array('Pagination','RequestHandler','Phpexcel','Phpcsv');
    public $uses = array('Language','Dictionary','SystemResource','SystemResourceI18n');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('dictionary_view');
        /*end*/
        //$this->pageTitle = "字典管理"." - ".$this->configs['shop_name'];

        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['dictionar'],'url' => '/dictionaries/');
        $this->menu_path = array('root' => '/web_application/','sub' => '/dictionaries/');
        unset($is_select_locale);
        unset($is_select_type);
        unset($_SESSION['is_select_locale']);
        unset($_SESSION['is_select_type']);
        unset($_SESSION['is_keywords']);
        unset($_SESSION['is_location']);
        $language = $this->Language->find('all');
        if (isset($language) && count($language) > 0 && isset($_GET['locale'])) {
            foreach ($language as $k => $v) {
                if ($v['Language']['locale'] == $_GET['locale']) {
                    $is_select_locale = $_GET['locale'];
                    $_SESSION['is_select_locale'] = $is_select_locale;
                    $this->set('is_select_locale', $is_select_locale);
                }
            }
        }
        //资源库信息
//        $this->SystemResource->set_locale($this->locale);
//        $language_type = $this->SystemResource->find('language_dictionary_type');
//		pr($language_type);die;
//		if(isset($language_type) && count($language_type)>0 && isset($_GET['language_type'])){
//			foreach($language_type as $k=>$v){
//				if($v['SystemResource']['resource_value'] == $_GET['language_type']){
//					$is_select_type = $_GET['language_type'];
//					$this->set('is_select_type',$is_select_type);
//					$_SESSION['is_select_type'] = $is_select_type; 
//				}
//			}
//		}
//		$language_type_assoc = $this->SystemResource->find_assoc('language_dictionary_type');
//		//pr($language_type_assoc);
//		$this->set('language_type_assoc',$language_type_assoc);
//		$this->set('language_type',$language_type);

        if (!empty($is_select_locale)) {
            $condition['AND'][0] = "Dictionary.locale = '$is_select_locale' ";

            if (!empty($is_select_type) && $is_select_type != 'all_type') {
                $condition['AND'][1] = "Dictionary.type = '$is_select_type' ";
            }
            if (isset($_GET['language_location'])) {
                $is_select_location = $_GET['language_location'];
                $_SESSION['is_select_location'] = $is_select_location;
                if ($_GET['language_location'] != 'all_location') {
                    $condition['AND'][2] = "Dictionary.location = '$is_select_location' ";
                }
            }
            if (isset($_GET['language_type'])) {
                $language_type = $_GET['language_type'];
                if ($_GET['language_type'] != 'all_type') {
                    $condition['AND'][3] = "Dictionary.type = '$language_type' ";
                }
                $_SESSION['language_type'] = $language_type;
            }
            if (isset($_GET['keywords'])) {
                $keywords = $_GET['keywords'];
                $condition['OR'][0] = "Dictionary.name like '%$keywords%' ";
                $condition['OR'][1] = "Dictionary.description like '%$keywords%' ";
                $condition['OR'][2] = "Dictionary.value like '%$keywords%' ";
                $_SESSION['is_keywords'] = $keywords;
            }

            $total = $this->Dictionary->find('count', array('conditions' => $condition));
            $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
            if (isset($_GET['page']) && $_GET['page'] != '') {
                $page = $_GET['page'];
            }
            $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
            $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'dictionaries','action' => 'index','page' => $page,'limit' => $rownum);
            $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Dictionary');
            $this->Pagination->init($condition, $parameters, $options);
            $language_dictionaries = $this->Dictionary->find('all', array('conditions' => $condition, 'order' => 'Dictionary.id desc', 'limit' => $rownum, 'page' => $page));
            $this->set('language_dictionaries', $language_dictionaries);
        }
        $this->set('locale', $this->locale);
        $this->set('language', $language);
        $this->set('navigations', $this->navigations);
        $this->set('title_for_layout', $this->ld['dictionar'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function export()
    {
        if (isset($_REQUEST['export']) && $_REQUEST['export'] === 'export') {
            $is_select_locale = $_REQUEST['export_locale'];

            $is_select_type = $_REQUEST['export_type'];

            if (!empty($is_select_locale)) {
                $condition['AND'][0] = "Dictionary.locale = '$is_select_locale' ";

                if (!empty($is_select_type) && $is_select_type != 'all_type') {
                    $condition['AND'][1] = "Dictionary.type = '$is_select_type' ";
                }
                if (isset($_REQUEST['export_location']) && $_REQUEST['export_location'] != '') {
                    $is_select_location = $_REQUEST['export_location'];
                    if ($_REQUEST['export_location'] != 'all_location') {
                        $condition['AND'][2] = "Dictionary.location = '$is_select_location' ";
                    }
                }
                if (isset($_REQUEST['export_keyword']) && $_REQUEST['export_keyword'] != '') {
                    $keywords = $_REQUEST['export_keyword'];
                    $condition['OR'][0] = "Dictionary.name like '%$keywords%' ";
                    $condition['OR'][1] = "Dictionary.description like '%$keywords%' ";
                    $condition['OR'][2] = "Dictionary.value like '%$keywords%' ";
                }
        //		pr($condition);exit;
                $language_dictionaries = $this->Dictionary->find('all', array('conditions' => array($condition), 'order' => 'Dictionary.id ASC'));
                $filename = '字典语言导出'.date('Ymd').'.csv';
                $ex_data = '字典语言,';
                $ex_data .= '日期,';
                $ex_data .= date('Y-m-d')."\n";
                $ex_data .= '编号,';
                $ex_data .= '名称,';
                $ex_data .= '语言编码,';
                $ex_data .= '位置,';
                $ex_data .= '类型,';
                $ex_data .= '内容,';
                $ex_data .= "描述\n";
                foreach ($language_dictionaries as $k => $v) {
                    $ex_data .= $v['Dictionary']['id'].',';
                    $ex_data .= $v['Dictionary']['name'].',';
                    $ex_data .= $v['Dictionary']['locale'].',';
                    $ex_data .= $v['Dictionary']['location'].',';
                    $ex_data .= $v['Dictionary']['type'].',';
                    $ex_data .= '"'.$v['Dictionary']['value'].'",';
                    $ex_data .= '"'.$v['Dictionary']['description']."\"\n";
                }
                Configure::write('debug', 0);
                header('Content-type: text/csv; charset=gb2312');
                header('Content-Disposition: attachment; filename='.iconv('utf-8', 'gb2312', $filename));
                header('Cache-Control:   must-revalidate,   post-check=0,   pre-check=0');
                header('Expires:   0');
                header('Pragma:   public');
                echo iconv('utf-8', 'gb2312', $ex_data."\n");
                exit;
            }
        }
    }

    public function import()
    {
        $this->pageTitle = '字典语言批量上传'.' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => '功能管理','url' => '');
        $this->navigations[] = array('name' => '字典管理','url' => '/dictionaries/');
        $this->navigations[] = array('name' => '字典语言批量上传','url' => '');
        $this->set('navigations', $this->navigations);
        if (!empty($_FILES['file'])) {
            if ($_FILES['file']['error'] > 0) {
                $this->flash('文件上传错误', '/dictionaries', '');
            } else {
                $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                $key_arr = array('id','name','locale','location','type','value','description','','');
                while ($row = fgetcsv($handle, 10000, ',')) {
                    foreach ($row as $k => $v) {
                        $temp[$key_arr[$k]] = iconv('gb2312', 'utf-8', $v);
                    }
                 // $temp['description']=htmlspecialchars($temp['description']);
                    $data[] = $temp;
                }
            }
        }

        if (isset($data) && sizeof($data) > 0) {
            $all_lang = $this->Dictionary->getcode($this->locale);
            foreach ($data as $k => $v) {
                if (isset($v['id']) && $v['id'] != '' && isset($v['name']) && $v['name'] != '' && !in_array($v['name'], $all_lang) && isset($v['locale']) && $v['locale'] != '' && isset($v['location']) && $v['location'] != '' && isset($v['type']) && $v['type'] != '' && isset($v['value']) && $v['value'] != '' && isset($v['description']) && $v['description'] != '') {
                    $mew = array('id' => '',
                                    'name' => $v['name'],
                                    'locale' => $v['locale'],
                                    'location' => $v['location'],
                                    'type' => $v['type'],
                                    'value' => $v['value'],
                                    'description' => $v['description'],
                                    );
                    $this->Dictionary->save($mew);
                }
            }
        }
        $this->flash('上穿成功', '/dictionaries', '');
    }

    public function add()
    {
        /*判断权限*/
        $this->operator_privilege('dictionary_add');
        /*end*/
        if ($this->RequestHandler->isPost()) {
            //pr($_REQUEST);die;
            //验证是否存在相同字典（name，location都相同）
            $lang['name'] = $_REQUEST['name'];
            $lang['type'] = $_REQUEST['type'];
            $lang['location'] = $_REQUEST['location'];
            $dic_info = $this->Dictionary->find('first', array('conditions' => array('Dictionary.name' => $lang['name'], 'Dictionary.location' => $lang['location'])));
            if (!empty($dic_info)) {
                Configure::write('debug', 1);
                $this->layout = 'ajax';
                $result['code'] = 1;
                $result['msg'] = $this->ld['name'].$this->ld['used'];
                die(json_encode($result));
            } else {
                foreach ($this->data['Dictionary'] as $k => $v) {
                    $lang['id'] = '';
                    $lang['locale'] = $k;
                    $lang['value'] = $v['value'];
                    $lang['description'] = $v['description'];
                    if ($lang['value'] != '') {
                        $this->Dictionary->save($lang);
                    }
                }
                $localeUrl = '?';
                if (isset($_SESSION['is_select_locale'])) {
                    $localeUrl .= 'locale='.$_SESSION['is_select_locale'].'&';
                }
                if (isset($_SESSION['is_select_type'])) {
                    $localeUrl .= 'language_type='.$_SESSION['is_select_type'].'&';
                }
                if (isset($_SESSION['is_keywords'])) {
                    $localeUrl .= 'keywords='.$_SESSION['is_keywords'].'&';
                }
                if (isset($_SESSION['is_select_location'])) {
                    $localeUrl .= 'language_location='.$_SESSION['is_select_location'].'&';
                }
                //操作员日志
                if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加字典语言: '.$lang['name'], $this->admin['id']);
                }
                //$this->flash('添加成功',"/dictionaries/".$localeUrl,10 );
                //$this->redirect("/dictionaries/".$localeUrl);
                $url = '/dictionaries/'.$localeUrl;
                Configure::write('debug', 1);
                $this->layout = 'ajax';
                $result['code'] = 0;
                $result['msg'] = $this->ld['add'].$this->ld['succeed'];
                $result['url'] = $url;
                die(json_encode($result));
            }
        }
    }

    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('dictionary_remove');
        /*end*/
        $this->Dictionary->deleteAll("Dictionary.id='$id'");
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除字典语言:id '.$id, $this->admin['id']);
        }
        $localeUrl = '?';
        if (isset($_SESSION['is_select_locale'])) {
            $localeUrl .= 'locale='.$_SESSION['is_select_locale'].'&';
        }
        if (isset($_SESSION['is_select_type'])) {
            $localeUrl .= 'language_type='.$_SESSION['is_select_type'].'&';
        }
        if (isset($_SESSION['is_keywords'])) {
            $localeUrl .= 'keywords='.$_SESSION['is_keywords'].'&';
        }
        if (isset($_SESSION['is_select_location'])) {
            $localeUrl .= 'language_location='.$_SESSION['is_select_location'].'&';
        }
        $this->redirect('/dictionaries/'.$localeUrl);
        //$this->flash('删除成功',"/language_dictionaries/".$localeUrl,10 );
    }
    /**
     *字典列表名称修改.
     */
    public function update_dictionaries_name()
    {
        /*判断权限*/
        $this->operator_privilege('dictionary_edit');
        /*end*/
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        //判断修改的name是否在字典里 如果在不允许改name
        $dictionaries_info = $this->Dictionary->find('first', array('conditions' => array('Dictionary.id' => $id), 'fields' => 'Dictionary.id,Dictionary.name,Dictionary.location'));
        if (!empty($dictionaries_info)) {
            //name已存在
            $name = $this->Dictionary->find('first', array('conditions' => array('Dictionary.name' => $val, 'Dictionary.location' => $dictionaries_info['Dictionary']['location'])));
            if (!empty($name)) {
                $result['flag'] = 2;
                $result['content'] = $this->ld['name'].$this->ld['used'];
            } else {
                if (!empty($val)) {
                    $this->Dictionary->save(array('id' => $id, 'name' => $val));
                    //操作员日志
                    if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑字典语言name：'.$val, $this->admin['id']);
                    }
                    $result['flag'] = 1;
                    $result['content'] = stripslashes($val);
                }
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *字典列表内容修改.
     */
    public function update_dictionaries_value()
    {
        /*判断权限*/
        $this->operator_privilege('dictionary_edit');
        /*end*/
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        //判断修改的value是否为空
        if (empty($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['content'].'不能为空';
        } else {
            $this->Dictionary->save(array('id' => $id, 'value' => $val));
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑字典语言内容：id'.$id, $this->admin['id']);
            }
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function go_input()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $result['id'] = $_REQUEST['id'];
            $result['value'] = $_REQUEST['value'];
            $result['len'] = $_REQUEST['len'];
            $result['style'] = $_REQUEST['type'];
            if ($result['style'] == 'type') {
                $result['change_type'] = 'select';
                //资源库信息
                $this->SystemResource->set_locale($this->locale);
                $result['language_type'] = $this->SystemResource->find_tree('language_dictionary_type');
                $result['language_type_assoc'] = $this->SystemResource->find_assoc('language_dictionary_type');
            } else {
                $result['change_type'] = 'input';
            }
            $result['type'] = 0;
            $result['message'] = 'test';
        }
    //	die(json_encode($result));
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    public function update_lang_dictionarie()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $result['id'] = $_POST['id'];
            $result['value'] = $_POST['value'];
            $result['style'] = $_POST['type'];
            $result['len'] = $_POST['len'];
            $lang_dictionarie['id'] = $_REQUEST['id'];
            $lang_dictionarie[$_REQUEST['type']] = $_POST['value'];
            if (trim($_POST['value']) != '') {
                $this->Dictionary->save($lang_dictionarie);
            } else {
                $language_dictionary = $this->Dictionary->findbyid($_POST['id']);
                $result['value'] = $language_dictionary['Dictionary'][$_POST['type']];
            }
            $result['type'] = 0;
            $result['message'] = 'test';
        }
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    public function translate()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $sl = $_POST['sl'];
            $lang_value = urlencode($_POST['value']);
            $tl = $_POST['google'];
            $url = 'http://translate.google.cn/translate_a/t?client=t&ie=utf8'.'&sl='.$sl.'&tl='.$tl.'&text='.$lang_value;
            $req_value = $this->translates($url);
            if ($req_value[0] != '"') {
                $result['value'] = $req_value;
            } else {
                $n = strlen($req_value);
                $result['value'] = substr($req_value, 1, $n - 2);
            }
            $result['locale'] = $_POST['locale'];
            //$language = $this->Language->findall();
        //	$g = 0;
            //pr($this->loacle);exit;
            /*
            foreach($language as $k=>$v){
            //	print($v['Language']['locale']."-".$_REQUEST['locale']."<br />");
                if($v['Language']['locale'] != $_REQUEST['locale']){
                //	print($v['Language']['google_translate_code']."<br />");
                    $url = "http://translate.google.cn/translate_a/t?client=t&ie=utf8"."&sl=".$sl."&tl=".$v['Language']['google_translate_code']."&text=".$lang_value;
                    $req_value = $this->translates($url);
                    if($req_value[0] !="\""){
                    $result['value'][$g] = $req_value;
                    }else{
                    $n = strlen($req_value);
                    $result['value'][$g] = substr($req_value,1,$n-2);
                    }
                    $result['locale'][$g] = $v['Language']['locale'];
                    $g ++;
                }
            }*/
            //$result['num'] = count($language)-1;
            $result['type'] = 0;
            //$result['locale'] = $_REQUEST['locale'];
        }
        //print("result:");
        //pr($result);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    public function translates($url)
    {
        $handle = file_get_contents($url);
        $handle = mb_convert_encoding($handle, 'UTF-8', 'GBK');
        if (preg_match("/.*(\[).*/", $handle)) {
            $r = json_decode($handle);
            $value = $r[0];
        //	$desc= json_encode($r[0]);
        //	$desc = preg_replace("#\\\u([0-9a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", $desc);
        } else {
            $value = $handle;
            $desc = '';
        }
        $result[0] = $value;

        return $value;
    }

    public function upload()
    {
        $this->menu_path = array('root' => '/web_application/','sub' => '/dictionaries/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['dictionar'],'url' => '/dictionaries/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $this->set('title_for_layout', $this->ld['dictionar'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
    }

    public function uploadpreview()
    {
        ////////////判断权限
            if ($this->operator_privilege('dictionary_add')) {
                if (isset($_POST['sub1']) && $_POST['sub1'] == 1 && !empty($_FILES['file'])) {
                    $this->menu_path = array('root' => '/web_application/','sub' => '/dictionaries/');
                    $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
                    $this->navigations[] = array('name' => $this->ld['dictionar'],'url' => '/dictionaries/');
                    $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
                    $this->set('title_for_layout', $this->ld['dictionar'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/dictionaries/upload';</script>";
                            die();
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                            $fields_array = array('Dictionary.locale',
                            'Dictionary.location',
                            'Dictionary.name',
                            'Dictionary.type',
                            'Dictionary.description',
                            'Dictionary.value', );

                            $fields = array($this->ld['z_language'],
                              $this->ld['z_position'],
                                $this->ld['z_name'],
                                $this->ld['z_type'],
                             $this->ld['z_description'],
                                  $this->ld['z_content'], );
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
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        $Dictionary_first = $this->Dictionary->find('first', array('conditions' => array('Dictionary.locale' => $v['locale'], 'Dictionary.location' => $v['location'], 'Dictionary.name' => $v['name'])));
                        $v['id']=isset($Dictionary_first['Dictionary']['id'])?$Dictionary_first['Dictionary']['id']:0;
                        $s=$this->Dictionary->save($v);
                    }
                    $this->redirect('/dictionaries/');
                } else {
                    $this->redirect('/dictionaries/');
                }
            } ///////权限判断结束
    }
    //导出字典
      public function download_csv_example()
      {

              //定义一个数组
        $fields_array = array('Dictionary.locale',
                            'Dictionary.location',
                            'Dictionary.name',
                            'Dictionary.type',
                            'Dictionary.description',
                            'Dictionary.value', );
          $fields = array($this->ld['z_language'],
                              $this->ld['z_position'],
                                $this->ld['z_name'],
                                $this->ld['z_type'],
                             $this->ld['z_description'],
                                  $this->ld['z_content'], );
          $newdatas = array();
          $newdatas[] = $fields;
          //查询所有表里面所有信息 查询 5 条信息
          $Dictionary_info = $this->Dictionary->find('all', array('order' => 'Dictionary.id desc', 'limit' => 5));
          foreach ($Dictionary_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['dictionaries'].$this->ld['export'].date('Ymd').'.csv';
          $this->Phpcsv->output($nameexl, $newdatas);
          die();
      }

      /////////////////////////////////////////////
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
