<?php

/*****************************************************************************
 * Seevia 门户管理
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
 *	门户管理.
 *
 *对于Portail这张表的增删改查
 *
 *@author   zhaoyincheng 
 *
 *@version  $Id$
 */
class PortalsController extends AppController
{
    public $name = 'Portals';
    public $components = array('Pagination','RequestHandler','Phpexcel');
    public $helpers = array('Pagination');
    public $uses = array('Resource','Application','Portal');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('portals_view');
        $this->menu_path = array('root' => '/system/','sub' => '/portals/');
        /*end*/

        $this->set('title_for_layout', $this->ld['portal_management'].' - '.$page.' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['portal_management'],'url' => '/portals/');

        $condition = array();
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $condition['and']['or']['Portal.name like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
        if (isset($_REQUEST['selecttype']) && $_REQUEST['selecttype'] != '') {
            $condition['and']['Portal.type'] = $_REQUEST['selecttype'];
            $this->set('selecttype', $_REQUEST['selecttype']);
        }
        if (isset($_REQUEST['selectdefaultlist']) && $_REQUEST['selectdefaultlist'] != '') {
            $condition['and']['Portal.default_list'] = $_REQUEST['selectdefaultlist'];
            $this->set('selectdefaultlist', $_REQUEST['selectdefaultlist']);
        }
        $total = $this->Portal->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'portals','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Portal');
        $this->Pagination->init($condition, $parameters, $options);
        $portal_list = $this->Portal->find('all', array('conditions' => $condition, 'order' => 'Portal.created desc', 'limit' => $rownum, 'page' => $page));
        $this->set('portal_list', $portal_list);
    }

    public function view($id = 0)
    {
        /*判断权限*/
        if ($id == 0) {
            $this->operator_privilege('portals_add');
        } else {
            $this->operator_privilege('portals_edit');
        }
        /*end*/
        $this->menu_path = array('root' => '/system/','sub' => '/portals/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['portal_management'],'url' => '/portals/');

        if ($this->RequestHandler->isPost()) {
            $this->Portal->save($this->data);
            $this->redirect('/portals/');
        }
        if ($id == 0) {
            $this->set('title_for_layout', $this->ld['add'].' - '.$this->ld['portal_management'].' - '.$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['add'].' - '.$this->ld['portal_management'],'url' => '');
        } else {
            $portalInfo = $this->Portal->find('first', array('conditions' => array('Portal.id' => $id)));
            if (empty($portalInfo)) {
                $this->redirect('/portals/view/0');
            } else {
                $this->set('title_for_layout', $this->ld['edit'].' - '.$portalInfo['Portal']['name'].' - '.$this->configs['shop_name']);
                $this->navigations[] = array('name' => $this->ld['edit'].' - '.$portalInfo['Portal']['name'],'url' => '');
                $this->set('portalInfo', $portalInfo);
            }
        }
    }

    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if (!$this->operator_privilege('portals_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }

        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $portalInfo = $this->Portal->find('first', array('conditions' => array('Portal.id' => $id)));
        $this->Portal->delete(array('id' => $id));
        $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
        if ($portalInfo['Portal']['img'] != '') {
            $Portal_image = $img_dir.$portalInfo['Portal']['img'];
            if (file_exists($Portal_image)) {
                unlink($Portal_image);
            }
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete'].':id '.$id.' '.$portalInfo['Portal']['name'], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        die(json_encode($result));
    }

    public function removeAll()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if (!$this->operator_privilege('portals_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $portal_checkboxes = $_REQUEST['checkboxes'];
        $portalInfo_list = $this->Portal->find('list', array('fields' => array('Portal.id', 'Portal.img'), 'conditions' => array('Portal.id' => $portal_checkboxes)));
        $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
        foreach ($portalInfo_list as $k => $v) {
            $this->Portal->deleteAll(array('id' => $k));
            if ($v != '') {
                $Portal_image = $img_dir.$v;
                if (file_exists($Portal_image)) {
                    unlink($Portal_image);
                }
            }
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        die(json_encode($result));
    }

    public function toggle_on_status()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $portalInfo = $this->Portal->find('first', array('conditions' => array('id' => $id)));
        $portal_info = array('id' => $id,'status' => $val);
        $result = array();
        if (is_numeric($val) && $this->Portal->save($portal_info)) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑'.$portalInfo['Portal']['name'].' '.$this->ld['status'].':'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function uploadimg()
    {
        $result['code'] = 0;
        $result['msg'] = '上传文件不存在';
        if ($this->RequestHandler->isPost()) {
            $max_size = 300;
            $max_width = 16;
            $types = array('png','gif','jpg','jpeg','PNG','GIF','JPG','JPEG');
            $imgInfo = $_FILES['PortalImg'];
            $info = pathinfo($imgInfo['name']);
            if (!in_array($info['extension'], $types)) {
                $result['msg'] = '类型不支持';
            } elseif ($imgInfo['size'] == 0 || $imgInfo['size'] > $max_size * 1024) {
                $result['msg'] = '图片太大';
            } else {
                $dir_root = WWW_ROOT.'media/admin/files/';
                $this->mkdirs($dir_root);
                if (!is_dir($dir_root.'portals/')) {
                    @mkdir($dir_root.'portals/', 0777);
                    @chmod($dir_root.'portals/', 0777);
                } else {
                    @chmod($dir_root.'portals/', 0777);
                }
                $img_name = date('Ymd').rand().'.'.$info['extension'];
                $img_path = $dir_root.'portals/'.$img_name;
                $img_url = '/media/admin/files/portals/'.$img_name;
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
}
