<?php

/*****************************************************************************
 * Seevia 公众用户管理
* ===========================================================================
* 版权所有  上海实玮网络科技有限公司，并保留所有权利。
* 网站地址: http://www.seevia.cn
* ---------------------------------------------------------------------------
* 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
* 不允许对程序代码以任何形式任何目的的再发布。
* ===========================================================================
* $开发: 上海实玮$
* $Id$*/

class OpenModelsController extends AppController
{
    public $name = 'OpenModels';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination');
    public $uses = array('OpenModel', 'OperatorLog','OpenUser','OpenUserMessage','OpenKeywordError','OpenConfig','OpenConfigsI18n','OpenElement');

    public function index()
    {
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_models/');
        /*判断权限*/
        $this->operator_privilege('open_models_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        /*end*/
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['public_platform_account_manage'],'url' => '');
        $condition = '';
        $page = 1;
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
            $condition['or']['OpenModel.open_type_id like'] = '%'.$_REQUEST['keyword'].'%';
            $condition['or']['OpenModel.app_id like'] = '%'.$_REQUEST['keyword'].'%';
            $condition['or']['OpenModel.app_secret like'] = '%'.$_REQUEST['keyword'].'%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        $total = $this->OpenModel->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'OpenModel';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'OpenModel','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OpenModel');
        $this->Pagination->init($condition, $parameters, $options);
        $model_list = $this->OpenModel->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'OpenModel.created desc'));
        $this->set('model_list', $model_list);
        $this->set('title_for_layout', $this->ld['public_platform_account_manage'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    //编辑
    public function view($id = 0)
    {
        if ($id == 0) {
            $this->operator_privilege('open_models_add');
        } else {
            $this->operator_privilege('open_models_edit');
        }
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_models/');
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['public_platform_account_manage'],'url' => '/open_models/');

        if ($this->RequestHandler->isPost()) {
            $this->data['OpenModel']['id'] = $id;
            $this->data['OpenModel']['token'] = $this->OpenModel->getAccessToken($this->data['OpenModel']['app_id'], $this->data['OpenModel']['app_secret']);
            $this->data['OpenModel']['verify_status'] = !empty($this->data['OpenModel']['verify_status']) ? $this->data['OpenModel']['verify_status'] : '0';//验证状态
            $this->OpenModel->save(array('OpenModel' => $this->data['OpenModel']));

            if (isset($this->data['OpenConfig']) && sizeof($this->data['OpenConfig']) > 0) {
                //预先删除配置表相关配置
                        $open_config_ids = $this->OpenConfig->find('list', array('fields' => 'OpenConfig.id', 'conditions' => array('OpenConfig.open_type_id' => $this->data['OpenModel']['open_type_id'])));
                if (!empty($open_config_ids) && sizeof($open_config_ids) > 0) {
                    $this->OpenConfigsI18n->deleteAll(array('OpenConfigsI18n.open_config_id' => $open_config_ids));
                    $this->OpenConfig->deleteAll(array('OpenConfig.id' => $open_config_ids));
                }
                $open_config_data = array();

                //配置数据整合
                foreach ($this->data['OpenConfig'] as $k => $v) {
                    $open_config_data[$k]['OpenConfig']['open_type'] = $this->data['OpenModel']['open_type'];
                    $open_config_data[$k]['OpenConfig']['open_type_id'] = $this->data['OpenModel']['open_type_id'];
                    $open_config_data[$k]['OpenConfig']['code'] = $k;
                    $open_config_data[$k]['OpenConfig']['status'] = isset($v['status']) ? $v['status'] : '1';
                    foreach ($this->backend_locales as $kk => $vv) {
                        $open_config_data[$k]['OpenConfigsI18n'][$vv['Language']['locale']]['name'] = isset($v['name'][$vv['Language']['locale']]) && $v['name'][$vv['Language']['locale']] != '' ? $v['name'][$vv['Language']['locale']] : 'text';
                        if (isset($v[$vv['Language']['locale']])) {
                            if (is_array($v[$vv['Language']['locale']])) {
                                $open_config_data_value = '';
                                foreach ($v[$vv['Language']['locale']] as $kkk => $vvv) {
                                    $open_config_data_value .= $vvv.';';
                                }
                                if (strlen($open_config_data_value) > 0) {
                                    $open_config_data_value = substr($open_config_data_value, 0, strlen($open_config_data_value) - 1);
                                }
                                $open_config_data[$k]['OpenConfigsI18n'][$vv['Language']['locale']]['value'] = $open_config_data_value;
                            } else {
                                $open_config_data[$k]['OpenConfigsI18n'][$vv['Language']['locale']]['value'] = $v[$vv['Language']['locale']];
                            }
                        }
                    }
                }

                //配置数据保存
                foreach ($open_config_data as $k => $v) {
                    if (isset($v['OpenConfigsI18n'][$this->locale]) && $v['OpenConfigsI18n'][$this->locale] != '') {
                        $this->OpenConfig->saveAll($v['OpenConfig']);
                        $Open_config_id = $this->OpenConfig->id;
                        foreach ($this->backend_locales as $kk => $vv) {
                            $openconfigi18ndata = array();
                            $openconfigi18ndata['open_config_id'] = $Open_config_id;
                            $openconfigi18ndata['locale'] = $vv['Language']['locale'];
                            $openconfigi18ndata['name'] = $v['OpenConfigsI18n'][$vv['Language']['locale']]['name'];
                            $openconfigi18ndata['value'] = isset($v['OpenConfigsI18n'][$vv['Language']['locale']]['value']) ? $v['OpenConfigsI18n'][$vv['Language']['locale']]['value'] : '';
                            $this->OpenConfigsI18n->saveAll(array('OpenConfigsI18n' => $openconfigi18ndata));
                        }
                    }
                }
            }
            if (isset($this->data['old_open_type_id'])) {
                if ($this->data['old_open_type_id'] != $this->data['OpenModel']['open_type_id']) {
                    //判断公众平台账号是否修改
                    //修改相应表中的公众平台账号
                    //公众平台用户表
                    $open_user_list = $this->OpenUser->find('list', array('fields' => 'OpenUser.id', 'conditions' => array('OpenUser.open_type_id' => $this->data['old_open_type_id'])));
                    if (!empty($open_user_list) && sizeof($open_user_list) > 0) {
                        foreach ($open_user_list as $k => $v) {
                            $open_user_list_data['id'] = $v;
                            $open_user_list_data['open_type_id'] = $this->data['OpenModel']['open_type_id'];
                            $this->OpenUser->saveAll(array('OpenUser' => $open_user_list_data));
                        }
                    }

                    //公众平台信息日志表
                    $open_user_messge_list = $this->OpenUserMessage->find('list', array('fields' => 'OpenUserMessage.id', 'conditions' => array('OpenUserMessage.open_type_id' => $this->data['old_open_type_id'])));

                    if (!empty($open_user_messge_list) && sizeof($open_user_messge_list) > 0) {
                        foreach ($open_user_messge_list as $k => $v) {
                            $open_user_messge_list_data['id'] = $v;
                            $open_user_messge_list_data['open_type_id'] = $this->data['OpenModel']['open_type_id'];
                            $this->OpenUserMessage->saveAll(array('OpenUserMessage' => $open_user_messge_list_data));
                        }
                    }

                    //未搜索到关键字表
                    $open_keyworld_error_list = $this->OpenKeywordError->find('list', array('fields' => 'OpenKeywordError.id', 'conditions' => array('OpenKeywordError.open_type_id' => $this->data['old_open_type_id'])));

                    if (!empty($open_keyworld_error_list) && sizeof($open_keyworld_error_list) > 0) {
                        foreach ($open_keyworld_error_list as $k => $v) {
                            $open_keyworld_error_list_data['id'] = $v;
                            $open_keyworld_error_list_data['open_type_id'] = $this->data['OpenModel']['open_type_id'];
                            $this->OpenKeywordError->saveAll(array('OpenKeywordError' => $open_keyworld_error_list_data));
                        }
                    }

                    //删除配置表相关配置
                        $open_config_ids = $this->OpenConfig->find('list', array('fields' => 'OpenConfig.id', 'conditions' => array('OpenConfig.open_type_id' => $this->data['old_open_type_id'])));
                    if (!empty($open_config_ids) && sizeof($open_config_ids) > 0) {
                        $this->OpenConfigsI18n->deleteAll(array('OpenConfigsI18n.open_config_id' => $open_config_ids));
                        $this->OpenConfig->deleteAll(array('OpenConfig.id' => $open_config_ids));
                    }
                }
            }
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['modify'].$this->ld['open_model'].'id:'.$id, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data = $this->OpenModel->find('first', array('conditions' => array('OpenModel.id' => $id)));
        //导般 名称设置
        if (!empty($this->data)) {
            $this->navigations[] = array('name' => $this->ld['edit'].' - '.$this->data['OpenModel']['open_type_id'],'url' => '');
            $open_config_data = $this->OpenConfig->tree($this->data['OpenModel']['open_type_id']);
            if (!empty($open_config_data)) {
                $this->set('open_config_data', $open_config_data);
            }
        } else {
            $this->navigations[] = array('name' => $this->ld['open_model'].$this->ld['add'],'url' => '');
        }
        //表情数组
        $Expression = array('/微笑','/撇嘴','/好色','/发呆','/得意','/流泪','/害羞','/睡觉','/尴尬','/呲牙','/惊讶','/冷汗','/抓狂','/偷笑','/可爱','/傲慢','/犯困','/流汗','/大兵','/咒骂','/折磨/','/衰','/擦汗','/抠鼻','/鼓掌','/坏笑','/左哼哼','/右哼哼','/鄙视','/委屈','/阴险','/亲亲','/可怜','/爱情','/飞吻','/怄火','/回头','/献吻','/左太极');
        $this->set('Expression', $Expression);

        //搜索素材管理所有素材
        $material_list = $this->OpenElement->find('list', array('fields' => array('OpenElement.id', 'OpenElement.title'), 'conditions' => array('OpenElement.parent_id' => 0)));
        $this->set('material_list', $material_list);

        $this->set('title_for_layout', $this->ld['public_platform_account_manage'].$this->ld['edit'].' - '.$this->configs['shop_name']);
    }

    public function toggle_on_status()
    {
        $result = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $open_modelInfo = $this->OpenModel->find('first', array('conditions' => array('id' => $id)));
        $data_info = array('id' => $id,'status' => $val);
        if (is_numeric($val) && $this->OpenModel->save($data_info)) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].$open_modelInfo['OpenModel']['open_type_id'].' '.$this->ld['status'].':'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //删除
    public function remove($id)
    {
        $this->operator_privilege('open_models_remove');
        $result['flag'] = 2;
        $result['message'] = '';
        $this->OpenModel->deleteAll(array('id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除公众平台'.':'.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function uploadimg()
    {
        $result['code'] = 0;
        $result['msg'] = 'not null';
        if ($this->RequestHandler->isPost()) {
            $max_size = 300;
            $max_width = 60;
            $types = array('png','gif','jpg','jpeg','PNG','GIF','JPG','JPEG');
            $imgInfo = $_FILES['OpenModelImg'];
            $info = pathinfo($imgInfo['name']);
            if (!in_array($info['extension'], $types)) {
                $result['msg'] = '类型不支持';
            } elseif ($imgInfo['size'] == 0 || $imgInfo['size'] > $max_size * 1024) {
                $result['msg'] = '图片太大';
            } else {
                $dir_root = WWW_ROOT.'media/admin/files/';
                $this->mkdirs($dir_root);
                $img_name = date('Ymd').rand().'.'.$info['extension'];
                $img_path = $dir_root.$img_name;
                $img_url = '/media/admin/files/'.$img_name;
                if (move_uploaded_file($imgInfo['tmp_name'], $img_path)) {
                    $width = $this->getWidth($img_path);
                    $height = $this->getHeight($img_path);
                    if ($width > $max_width) {
                        $scale = $max_width / $width;
                        $uploaded = $this->resizeImage($img_path, $width, $height, $scale);
                    } else {
                        $scale = 1;
                        $uploaded = $this->resizeImage($img_path, $width, $height, $scale);
                    }
                    $result['code'] = 1;
                    $result['msg'] = '';
                    $result['upload_img_url'] = $img_url;
                } else {
                    $result['code'] = 0;
                    $result['msg'] = '上传失败';
                }
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
    	获取图片高度
    */
    public function getHeight($image)
    {
        $size = getimagesize($image);
        $height = $size[1];

        return $height;
    }

    /*
    	获取图片宽度
    */
    public function getWidth($image)
    {
        $size = getimagesize($image);
        $width = $size[0];

        return $width;
    }

    /*
    	等比例调整图片
    */
    public function resizeImage($image, $width, $height, $scale)
    {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch ($imageType) {
            case 'image/gif':
                $source = imagecreatefromgif($image);
                break;
            case 'image/pjpeg':
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($image);
                break;
            case 'image/png':
            case 'image/x-png':
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);

        switch ($imageType) {
            case 'image/gif':
                imagegif($newImage, $image);
                break;
              case 'image/pjpeg':
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($newImage, $image, 90);
                break;
            case 'image/png':
            case 'image/x-png':
                imagepng($newImage, $image);
                break;
        }

        chmod($image, 0777);

        return $image;
    }

    public function token($id)
    {
        $this->data = $this->OpenModel->findById($id);
        $this->data['OpenModel']['id'] = $id;
        $this->data['OpenModel']['token'] = $this->OpenModel->getAccessToken($this->data['OpenModel']['app_id'], $this->data['OpenModel']['app_secret']);
        if ($this->data['OpenModel']['token']) {
            $this->OpenModel->save(array('OpenModel' => $this->data['OpenModel']));
            $this->redirect('/open_models/view/'.$id);
        } else {
            $msg = $this->ld['open_model_account'].' '.$this->ld['authentication_failed'];
            echo '<meta charset=utf-8 /><script type="text/javascript">alert("'.$msg.'");window.location.href="/admin/open_models/view/'.$id.'";</script>';
            die();
        }
    }

    public function loglist($open_model_id, $page = 1)
    {
        $this->operator_privilege('open_models_loglist');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_models/');
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['public_platform_account_manage'],'url' => '/open_models/');
        $open_model_info = $this->OpenModel->find('first', array('conditions' => array('OpenModel.id' => $open_model_id)));
        if (!empty($open_model_info)) {
            $this->navigations[] = array('name' => $open_model_info['OpenModel']['open_type_id'].' - '.$this->ld['log_platform'],'url' => '');
            $this->pageTitle = $open_model_info['OpenModel']['open_type_id'].' - '.$this->ld['log_platform'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name'];
            $this->set('title_for_layout', $this->pageTitle);
            $condition['OpenUserMessage.open_type_id'] = $open_model_info['OpenModel']['open_type_id'];
            if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
                $condition['and']['or']['OpenUserMessage.message like'] = '%'.$_REQUEST['keywords'].'%';
                $condition['and']['or']['OpenUserMessage.return_message like'] = '%'.$_REQUEST['keywords'].'%';
                $condition['and']['or']['OpenUser.nickname like'] = '%'.urlencode($_REQUEST['keywords']).'%';
                $this->set('keywords', $_REQUEST['keywords']);
            }
            //添加时间
            if (isset($this->params['url']['start_date']) && $this->params['url']['start_date'] != '') {
                $condition['and']['OpenUserMessage.created >='] = $this->params['url']['start_date'].' 00:00:00';
                $start_date = $this->params['url']['start_date'];
                $this->set('start_date', $start_date);
            }
            if (isset($this->params['url']['end_date']) && $this->params['url']['end_date'] != '') {
                $condition['and']['OpenUserMessage.created <='] = $this->params['url']['end_date'].' 23:59:59';
                $end_date = $this->params['url']['end_date'];
                $this->set('end_date', $end_date);
            }
            $joins = array(
                array('table' => 'svsns_open_users',
                      'alias' => 'OpenUser',
                      'type' => 'left',
                      'conditions' => array('OpenUser.id = OpenUserMessage.open_user_id'),
                     ), );

            $total = $this->OpenUserMessage->find('count', array('conditions' => $condition, 'joins' => $joins));
            $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
            if (isset($_GET['page']) && $_GET['page'] != '') {
                $page = $_GET['page'];
            }
            $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
            $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'open_models','action' => 'loglist','page' => $page,'limit' => $rownum);
            $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OpenUserMessage');
            $this->Pagination->init($condition, $parameters, $options);
            $fields = array('OpenUserMessage.*','OpenUser.nickname','OpenUser.id');
            $log_list = $this->OpenUserMessage->find('all', array('conditions' => $condition, 'fields' => $fields, 'joins' => $joins, 'order' => 'OpenUserMessage.created desc', 'limit' => $rownum, 'page' => $page));
            $this->set('open_model_info', $open_model_info);
            $this->set('loglist', $log_list);
        } else {
            $this->redirect('/open_models');
        }
    }

    public function log_view($id, $open_model_id)
    {
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_models/');
        $this->navigations[] = array('name' => '公众平台','url' => '');
        $this->navigations[] = array('name' => '公众平台管理','url' => '/open_models/');
        $open_model_info = $this->OpenModel->find('first', array('conditions' => array('OpenModel.id' => $open_model_id)));
        if (!empty($open_model_info)) {
            $this->navigations[] = array('name' => $open_model_info['OpenModel']['open_type_id'].' - 平台日志','url' => '/open_models/loglist/'.$open_model_id);
            $this->navigations[] = array('name' => '日志详情','url' => '');
            $this->pageTitle = '日志详情'.' - '.$this->configs['shop_name'];
            $this->set('title_for_layout', $this->pageTitle);
            $joins = array(
            array('table' => 'svsns_open_users',
                  'alias' => 'OpenUser',
                  'type' => 'left',
                  'conditions' => array('OpenUser.id = OpenUserMessage.open_user_id'),
                 ), );
            $fields = array('OpenUserMessage.*','OpenUser.nickname');
            $messageInfo = $this->OpenUserMessage->find('first', array('conditions' => array('OpenUserMessage.id' => $id), 'joins' => $joins, 'fields' => $fields));
            if (isset($messageInfo) && !empty($messageInfo)) {
                $this->set('messageInfo', $messageInfo);
            } else {
                $this->redirect('/open_models');
            }
        } else {
            $this->redirect('/open_models/');
        }
    }
}
