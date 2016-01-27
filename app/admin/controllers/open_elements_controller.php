<?php

/*****************************************************************************
 * Seevia 素材管理
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
 *素材管理.
 *
 *对于OpenElement这张表的增删改查
 *
 *@author   weizhngye 
 *
 *@version  $Id$
 */
class OpenElementsController extends AppController
{
    /*
    *控制器的名字
    */
    public $name = 'OpenElements';
    /*
    *引用的助手
    */
    public $helpers = array('Html','Pagination','Tinymce','fck','Form','Javascript','Ckeditor');
    /*
    *引用的组件
    */
    public $components = array('Pagination','RequestHandler','Email');
    /*
    *引用的model
    */
    public $uses = array('OpenElement','Resource','SeoKeyword','InformationResource','Template','Template','OperatorLog','OpenUser','OpenModel','OpenUserMessage','OpenMedia');

    /**
     *pagetype主页列表.
     *
     *呈现数据库表OpenElement的数据
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('open_elements_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_elements/');
        /*end*/
        $this->set('title_for_layout', $this->ld['open_elements'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['open_elements'],'url' => '/open_elements/');
        $conditions = array();
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $conditions['and']['or']['OpenElement.title like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
        $conditions['parent_id'] = 0;
        $cond['conditions'] = $conditions;
        //分页
        $total = $this->OpenElement->find('count', $cond);//获取总记录数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];//当前页
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'OpenElement','action' => 'view','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OpenElement');
        $this->Pagination->init($conditions, $parameters, $options);
        $cond['limit'] = $rownum;
        $cond['page'] = $page;
        $cond['order'] = 'OpenElement.created desc';
        $element_list = $this->OpenElement->find('all', $cond);
        $this->set('element_list', $element_list);
        
        //获取微信公众号类型（服务号）及认证状态
        $open_type = $this->OpenModel->find('all', array('conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1)));
        $this->set('open_type', $open_type);
    }

    /**
     *OpenElement修改页和添加页.
     *
     *增加和修改数据库表OpenElement的记录
     *
     *@author   weizhngye 
     *
     *@version  $Id$
     */
    public function view($type = 1, $id = 0)
    {
        /*判断权限*/
        if ($id == 0) {
            $this->operator_privilege('open_elements_add');
        } else {
            $this->operator_privilege('open_elements_edit');
        }
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_elements/');
        $this->set('title_for_layout', $this->ld['add_edit_page'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['open_elements'],'url' => '/open_elements/');
        $this->navigations[] = array('name' => $this->ld['add_edit_page'],'url' => '/open_elements/view/'.$type.'/'.$id);
        if ($this->RequestHandler->isPost()) {
            $parent_id = 0;
            if (isset($this->data['100'])) {
                $data = $this->data['100'];
                $data['OpenElement']['id'] = isset($data['OpenElement']['id']) && $data['OpenElement']['id'] != '' ? $data['OpenElement']['id'] : 0;
                $data['OpenElement']['parent_id'] = '0';
                $this->OpenElement->save($data);
                $parent_id = $this->OpenElement->id;
                $url_link = '';
                if ($data['OpenElement']['url'] == '') {
                    $url_link = $this->server_host.'/open_elements/'.$parent_id;
                    $data['OpenElement']['id'] = $parent_id;
                    $data['OpenElement']['url'] = $url_link;
                    $this->OpenElement->save($data);
                }
                if (sizeof($this->data) > 0 && $parent_id != 0) {
                    foreach ($this->data as $k => $v) {
                        if ($k != '100') {
                            if (trim($v['OpenElement']['title']) != '' && trim($v['OpenElement']['media_url']) != '' && trim($v['OpenElement']['description']) != '') {
                                $v['OpenElement']['parent_id'] = $parent_id;
                                if ($v['OpenElement']['url'] == '') {
                                    $v['OpenElement']['url'] = '';
                                }
                                $this->OpenElement->saveAll($v);
                                $open_element_id = $this->OpenElement->id;
                                if ($v['OpenElement']['url'] == '') {
                                    $v['OpenElement']['id'] = $open_element_id;
                                    $v['OpenElement']['url'] = $this->server_host.'/open_elements/'.$open_element_id;
                                    $this->OpenElement->saveAll($v);
                                }
                            } else {
                                if ($v['OpenElement']['id'] != '' && $v['OpenElement']['id'] != 0) {
                                    $this->OpenElement->delete(array('OpenElement.id' => $v['OpenElement']['id']));
                                }
                            }
                        }
                    }
                }
            }
            /*操作员日志*/
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['modify'].$this->ld['source_material'].':id '.$id, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }

        $this->data = $this->OpenElement->find('first', array('conditions' => array('OpenElement.id' => $id)));
        //拿到id相关的数据

        //分成2个，一个是单图文只要拿出对应的id的内容,多图文拿出对应的id和拿出自己对应的parentid的集合
        if ($type == 2 && $id != 0) {
            //多图文
            $manypic = $this->OpenElement->find('all', array('conditions' => array('OpenElement.parent_id' => $id), 'order' => 'OpenElement.created asc'));
            $this->set('manypic', $manypic);
        }

        if (!empty($this->data)) {
        	$open_media_list=$this->OpenMedia->find('list',array('fields'=>array('OpenMedia.id','OpenMedia.open_type_id'),'conditions'=>array('OpenMedia.open_element_id'=>$id)));
        	
            	$open_model_list = $this->OpenModel->find('list', array('fields' => array('OpenModel.open_type_id'), 'conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1,'OpenModel.open_type_id'=>$open_media_list)));
            	$this->set('open_model_list', $open_model_list);
        }
        $this->set('type', $type);
    }

    public function open_user_list($open_type_id = '')
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $condition['OpenUser.open_type_id'] = isset($_REQUEST['open_type_id']) ? $_REQUEST['open_type_id'] : $open_type_id;
        if (isset($_REQUEST['open_user_keywords']) && $_REQUEST['open_user_keywords'] != '') {
            $condition['or']['OpenUser.nickname like '] = '%'.urlencode($_REQUEST['open_user_keywords']).'%';
            $this->set('open_user_keywords', $_REQUEST['open_user_keywords']);
        }
        $page = 1;
        $total = $this->OpenUser->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'OpenUser';
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'OpenElement','action' => 'view/{$type}/{$id}/{$media_id}','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OpenUser');
        $this->Pagination->init($condition, $parameters, $options);
        $user_list = $this->OpenUser->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'OpenUser.created desc'));
        $this->set('user_list', $user_list);
    }

    /**
     *OpenElement删除的方法.
     *
     *删除OpenElement的记录（如果多图文的话，还要删除对应的父级id）
     *
     *@author   weizhngye 
     *
     *@version  $Id$
     */
    public function remove($id)
    {
        /*判断权限*/
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if (!$this->operator_privilege('open_elements_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }

        //先根据查找有没有对应的父级id，如果有的话删除
        $open_elementsInfo = $this->OpenElement->find('first', array('OpenElement.id' => $id));

        $this->OpenElement->deleteAll(array('OpenElement.parent_id' => $id));
        $this->OpenElement->deleteAll(array('OpenElement.id' => $id));
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除素材:id '.$id.' '.$open_elementsInfo['OpenElement']['title'], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_the_ad_list_success'];
        die(json_encode($result));
    }

    /**
     *OpenElement 批量删除的方法.
     *
     *批量删除OpenElement的记录
     *
     *@author   赵殷程 
     *
     *@version  $Id$
     */
    public function removeall()
    {
        /*判断权限*/
        $this->operator_privilege('open_elements_remove');
        $open_elements_checkboxes = $_REQUEST['checkboxes'];
        $open_elements_Ids = '';
        foreach ($open_elements_checkboxes as $k => $v) {
            $open_elements_Ids = $open_elements_Ids.$v.',';
            $this->OpenElement->deleteAll(array('OpenElement.id' => $v));
            $this->OpenElement->deleteAll(array('OpenElement.parent_id' => $v));
        }
        if ($open_elements_Ids != '') {
            $open_elements_Ids = substr($open_elements_Ids, 0, strlen($open_elements_Ids) - 1);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].'删除所有素材:'.$open_elements_Ids, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
    //上传群发素材
    public function element_upload()
    {
        Configure::write('debug', 1);
        $this->layout="ajax";
        $element_id=isset($_POST['element_id'])?$_POST['element_id']:0;
        $element_type=isset($_POST['element_type'])?$_POST['element_type']:0;
        $open_type=isset($_POST['open_type'])?$_POST['open_type']:'wechat';
        $open_type_id=isset($_POST['open_type_id'])?$_POST['open_type_id']:'';
        $result['code'] = 1;
        $result['msg'] = '';
        $openmodelinfo = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type'=>$open_type,'OpenModel.open_type_id' => $open_type_id)));
        if(empty($openmodelinfo)){
        	$result['code'] = 0;
        	$result['msg'] = 'Data Error';
        	die(json_encode($result));
        }
        $max_size = 1024;
        $types = array('jpg','jpeg','JPG','JPEG','png','PNG');
        if ($element_type == 2) {
            $conditions['or'] = array('OpenElement.id' => $element_id,'OpenElement.parent_id' => $element_id);
        } else {
            $conditions = array('OpenElement.id' => $element_id);
        }
        $element_list = $this->OpenElement->find('all', array('conditions' => $conditions, 'group' => 'parent_id,id'));
        $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);//服务器路径
        
        if (!empty($element_list)) {
            $element_ids=array();
            foreach ($element_list as $ik => $iv) {
            	  $element_ids[]=$iv['OpenElement']['id'];
                $str = $iv['OpenElement']['media_url'];
                if ($str == '' || strlen($str) == 0) {
                    $result['code'] = 0;
                    $result['msg'] = '请检查当前素材中是否都添加了图片';
                    break;
                }
                $img_url = $img_dir.$str;
                $imgInfo = $this->getImagesInfo($img_url);
                if (!empty($imgInfo)) {
                    if (!in_array($imgInfo['type'], $types)) {
                        $result['code'] = 0;
                        $result['msg'] = '当前素材的图片中存在不支持的图片类型';
                        break;
                    } elseif ($imgInfo['size'] == 0 || $imgInfo['size'] > $max_size * 1024) {
                        $result['code'] = 0;
                        $result['msg'] = '当前素材的图片中存在大小异常的图片,图片最大限制1M';
                        break;
                    }
                } else {
                    $result['code'] = 0;
                    $result['msg'] = '素材图片类型、大小获取失败';
                    break;
                }
            }
        } else {
            $result['code'] = 0;
            $result['msg'] = '未找到素材';
        }
        if ($result['code'] == 1) {
        	$media_id="";
        	$img_media_data = array();
        	 $open_media_list=$this->OpenMedia->find('all',array('conditions'=>array('OpenMedia.open_element_id'=>$element_ids,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id)));
            foreach($open_media_list as $v){
            		$img_media_data[$v['OpenMedia']['open_element_id']]=$v['OpenMedia'];
            		$media_id=$v['OpenMedia']['media_id'];
            }
            $result['code'] = 0;
            if (!$this->OpenModel->validateToken($openmodelinfo)) {
			$openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
			$appId = $openmodelinfo['OpenModel']['app_id'];
			$appSecret = $openmodelinfo['OpenModel']['app_secret']; 
                    //无效重新获取
                    $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
                    $openmodelinfo['OpenModel']['token'] = $accessToken;
                    $this->OpenModel->save($openmodelinfo);
            }
            $access_token = $openmodelinfo['OpenModel']['token'];
            $error_message = '';
            
            $img_media = array();
            $uploadimgUrl = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$access_token.'&type=image'; //上传图片
            foreach ($element_list as $v) {
            	  if(isset($img_media_data[$v['OpenElement']['id']])&&$img_media_data[$v['OpenElement']['id']]['image_media_url']==$v['OpenElement']['media_url']){
            	  	  $img_media[$v['OpenElement']['id']] = $img_media_data[$v['OpenElement']['id']]['image_media_id'];
            	  	  continue;
            	  }
                $imgurl = '@'.$img_dir.$v['OpenElement']['media_url'];
                $data = array('media' => $imgurl);
                $data_result = $this->https_request($uploadimgUrl, $data);
                if (isset($data_result['media_id'])) {
                    $img_media[$v['OpenElement']['id']] = $data_result['media_id'];
                } else {
                    $error_message = $data_result['errmsg'];
                    break;
                }
                $this->OpenUserMessage->saveMsg(
                    'upload_img', json_encode($data), 0,
                    $openmodelinfo['OpenModel']['open_type_id'], 0,
                    isset($data_result['media_id']) ? 'ok' : 'no',
                    json_encode($data_result)
                );
            }
            if (!empty($img_media) && $error_message == ''){
            		$data_result=array();
            		if(empty($media_id)){
		                //上传素材
		                $uploadUrl = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.$access_token;
		                $element_data = array();
		                foreach ($element_list as $v) {
		                    $elementdata['thumb_media_id'] = isset($img_media[$v['OpenElement']['id']]) ? $img_media[$v['OpenElement']['id']] : '';
		                    $elementdata['title'] = $v['OpenElement']['title'];
		                    $elementdata['content_source_url'] = $v['OpenElement']['url'];
		                    $element_content=$this->content_image_filtering($openmodelinfo,$v['OpenElement']['description']);
		                    $elementdata['content'] = addslashes($element_content);//内容进行转义处理
		                    $elementdata['show_cover_pic'] = 1;
		                    $element_data[] = $elementdata;
		                }
		                $data = array('articles' => $element_data);
		                $data = $this->to_josn($data);
		                $data_result = $this->https_request($uploadUrl, $data);
		                $this->OpenUserMessage->saveMsg(
		                    'upload_new', $data, 0,
		                    $openmodelinfo['OpenModel']['open_type_id'], 0,
		                    isset($data_result['media_id']) ? 'ok' : 'no',
		                    json_encode($data_result)
		                );
	              }else{
	                	//更新素材
	                	$updateUrl = 'https://api.weixin.qq.com/cgi-bin/material/update_news?access_token='.$access_token;
	                	foreach ($element_list as $k=>$v) {
	                		$element_data = array();
	                		$element_data['media_id'] = $media_id;
	                	      $element_article_data=array();
		                    $element_article_data['thumb_media_id'] = isset($img_media[$v['OpenElement']['id']]) ? $img_media[$v['OpenElement']['id']] : '';
		                    $element_article_data['title'] = $v['OpenElement']['title'];
		                    $element_article_data['content_source_url'] = $v['OpenElement']['url'];
		                    $element_content=$this->content_image_filtering($openmodelinfo,$v['OpenElement']['description']);
		                    $element_article_data['content'] = addslashes($element_content);//内容进行转义处理
		                    $element_article_data['show_cover_pic'] = 1;
		                    $element_data['articles'] = $element_article_data;
		                    $element_data['index'] = $k;
		                    $element_data = $this->to_josn($element_data);
		                    $data_result = $this->https_request($updateUrl, $element_data);
		                    $this->OpenUserMessage->saveMsg(
			                    'update_new', $element_data, 0,
			                    $openmodelinfo['OpenModel']['open_type_id'], 0,
			                    $data_result['errmsg']=='ok'? 'ok' : 'no',
			                    json_encode($data_result)
			                );
		                }
	              }
                	if (isset($data_result['media_id'])) {
                		$media_id=$data_result['media_id'];
                	}else if($media_id!=""&&$data_result['errmsg']!='ok'){
                		$media_id="";
                	}
                	if(!empty($media_id)){
	                     $result['code'] = 1;
	                     $result['media_id'] = $media_id;
	                     $get_element_url="https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=".$access_token;
	                     $element_post_data = array('media_id' => $media_id);
	                     $element_post_data = $this->to_josn($element_post_data);
	                     $element_data=$this->https_request($get_element_url, $element_post_data);
	                     if(!empty($element_data['news_item'])){
	                     	 foreach($element_list as $k=>$v){
	                     	 	$open_media_data=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$v['OpenElement']['id'],'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id)));
		                     	$open_media_data['OpenMedia']['id']=isset($open_media_data['OpenMedia']['id'])?$open_media_data['OpenMedia']['id']:0;
		                     	$open_media_data['OpenMedia']['media_type']='news';
		                     	$open_media_data['OpenMedia']['open_type']=$open_type;
		                     	$open_media_data['OpenMedia']['open_type_id']=$open_type_id;
		                     	$open_media_data['OpenMedia']['open_element_id']=$v['OpenElement']['id'];
		                     	$open_media_data['OpenMedia']['image_media_id']=isset($img_media[$v['OpenElement']['id']])?$img_media[$v['OpenElement']['id']]:'';
		                     	$open_media_data['OpenMedia']['image_media_url']=$v['OpenElement']['media_url'];
		                     	$open_media_data['OpenMedia']['media_id']=$media_id;
		                     	$open_media_data['OpenMedia']['url']=isset($element_data['news_item'][$k]['url'])?$element_data['news_item'][$k]['url']:'';
		                     	$this->OpenMedia->save($open_media_data);
	                     	 }
	                     }
	                } else {
	                    $result['msg'] = $data_result['errmsg'];
	                }
            } else {
                $result['code'] = 0;
                $result['msg'] = '图文上传失败';
            }
        }
        die(json_encode($result));
    }
    
    public function send()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 1);
            $this->layout = 'ajax';
            $result['code'] = 1;
            $result['msg'] = '';
            $open_type_id = isset($_POST['open_type_id']) ? $_POST['open_type_id'] : '';
            $element_id = isset($_POST['element_id']) ? $_POST['element_id'] : 0;
            $send_type = isset($_POST['send_type']) ? $_POST['send_type'] : 'preview';
            $open_media_list=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$element_id,'OpenMedia.open_type_id'=>$open_type_id)));
            $openmodelinfo = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type_id' => $open_type_id)));
            
            if (!empty($openmodelinfo)) {
                $openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
                $appId = $openmodelinfo['OpenModel']['app_id'];
                $appSecret = $openmodelinfo['OpenModel']['app_secret'];
                if (!$this->OpenModel->validateToken($openmodelinfo)) {
                    //无效重新获取
                    $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
                    $openmodelinfo['OpenModel']['token'] = $accessToken;
                    $this->OpenModel->save($openmodelinfo);
                }
                $media_id = isset($open_media_list['OpenMedia']['media_id']) ? $open_media_list['OpenMedia']['media_id'] : '';
                if ($media_id == '') {
                	$result['msg'] = '素材尚未上传到当前公众平台';
                	die(json_encode($result));
                }
                $access_token = $openmodelinfo['OpenModel']['token'];
                $touser = isset($_POST['touser']) ? $_POST['touser'] : '';
                if ($touser != '') {
                	$send_url="";
                	$send_data="";
                	if($send_type=='send'){
                		$send_url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token;
                		$send_data = array(
	                        'touser' => $touser,
	                        'mpnews' => array('media_id' => $media_id),
	                        'msgtype' => 'mpnews',
	                    );
                	}else{
                		$send_url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$access_token;
                		$send_data = array(
	                        'touser' => isset($touser[0])?$touser[0]:'',
	                        'mpnews' => array('media_id' => $media_id),
	                        'msgtype' => 'mpnews',
	                    );
                	}
                    $send_data = json_encode($send_data);
                    $data_result = $this->https_request($send_url, $send_data);
                    $result['code'] = $data_result['errcode'];
                    $result['msg'] = $data_result['errcode'] == '0' ? $this->ld['send_success'] : $this->ld['send_failed'];
			
                    $this->OpenUserMessage->saveMsg(
                        'send_news', $send_data, 0,
                        $openmodelinfo['OpenModel']['open_type_id'], 0,
                        isset($data_result['errcode']) && $data_result['errcode'] == '0' ? 'ok' : 'no',
                        json_encode($data_result)
                    );
                } else {
                    $result['msg'] = '接收者不能为空';
                }
            } else {
                $result['msg'] = '未找到相应的公众平台账号';
            }
            die(json_encode($result));
        } else {
            $this->redirect('/open_elements/');
        }
    }

    /*
        调用接口
    */
    public function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return json_decode($output, true);
    }

    /*
        $data   需要转换josn提交的数据
    */
    public function to_josn($data)
    {
        $this->arrayRecursive($data, 'urlencode');
        $json = json_encode($data);

        return urldecode($json);
    }

    /************************************************************** 
    * 对数组中所有元素做处理,保留中文 
    * @param string &$array 要处理的数组
    * @param string $function 要执行的函数 
    * @return boolean $apply_to_keys_also 是否也应用到key上 
    * @access public 
    * 
    *************************************************************/
    public function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        --$recursive_counter;
    }

    //参数images为图片的绝对地址
    public function getImagesInfo($images)
    {
        $img_info = getimagesize($images);
        switch ($img_info[2]) {
            case 1:
                $imgtype = 'gif';
                break;
            case 2:
                $imgtype = 'jpg';
                break;
            case 3:
                $imgtype = 'png';
                break;
        }
        $img_type = $imgtype;
        //获取文件大小     
        $img_size = ceil(filesize($images) / 1000);//kb
        $new_img_info = array(
            'url' => $images,
            'width' => $img_info[0], //图像宽
            'height' => $img_info[1], //图像高
            'type' => $img_type, //图像类型
            'size' => $img_size, //图像大小
        );
        return $new_img_info;
    }
    
    /*
    	 素材正文图片过滤处理
    */
    public function content_image_filtering($openmodelinfo,$wechat_content){
    		$open_type = $openmodelinfo['OpenModel']['open_type'];
    		$open_type_id = $openmodelinfo['OpenModel']['open_type_id'];
              $access_token = $openmodelinfo['OpenModel']['token'];
    		$pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
    		preg_match_all($pattern,$wechat_content,$img_match);
    		if(!empty($img_match)){
    			$old_img_url=array();
    			$new_img_data=array();
    			$img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);//服务器路径
    			if(isset($img_match[1])&&!empty($img_match[1])){
    				$old_img_url=array_unique($img_match[1]);
    				foreach($old_img_url as $k=>$v){
    					$data_result=array();
    					$imgurl = str_replace($this->server_host,'',$v);
    					$uploadimgUrl = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$access_token; //上传图片
    					$imgurl = $img_dir.$imgurl;
    					if(file_exists($imgurl)){
               				$data = array('media' => "@".$imgurl);
               				$data_result = $this->https_request($uploadimgUrl, $data);
						if (isset($data_result['url'])) {
							$new_img_data[$k] = $data_result['url'];
						}
               			}
    				}
    				foreach($old_img_url as $k=>$v){
    					if(isset($new_img_data[$k])){
    						$wechat_content = str_replace($v,$new_img_data[$k],$wechat_content);
    					}
    				}
    			}
    		}
    		return $wechat_content;
    }
}
