<?php

/*****************************************************************************
 * Seevia 图片空间管理
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
 *这是一个名为 ImageSpacesController 的控制器
 *后台图片空间管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class ImageSpacesController extends AppController
{
    public $name = 'ImageSpaces';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination');
    public $uses = array('PhotoCategoryGallery','PhotoCategory','PhotoCategoryI18n','Language','OperatorLog');

    public function index($id_str = '0', $orderby = 0, $cat = 0, $photo_category_id = 0, $search_key_word = '', $page = 1)
    {
        $this->operator_privilege('image_spaces_view');
        $this->menu_path = array('root' => '/cms/','sub' => '/image_spaces/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['image_space'],'url' => '/image_spaces/');
        $condition = '';
        $order = '';
        if ($orderby == 0) {
            //按上传时间从晚到早
            $order = 'created desc';
        }
        if ($orderby == 1) {
            //按上传时间从早到晚
            $order = 'created asc';
        }
        if ($orderby == 2) {
            //按图片从大到小
            $order = 'original_size desc';
        }
        if ($orderby == 3) {
            //按图片从小到大
            $order = 'original_size asc';
        }
        if ($orderby == 4) {
            //按修改时间从晚到早
            $order = 'modified desc';
        }
        if ($orderby == 5) {
            //按修改时间从早到晚
            $order = 'modified asc';
        }
        if ($orderby == 6) {
            //按图片名降序
            $order = 'name desc';
        }
        if ($orderby == 7) {
            //按图片名升序
            $order = 'name asc';
        }
        if ($photo_category_id > 0) {
            $condition['photo_category_id like'] = $photo_category_id;
        }
        //左边筛选
        if ($cat == 1) {
            //今天上传
            $condition['created >='] = Today.' '.StartTime;
            $condition['created <='] = Today.' '.EndTime;
        }
        if ($cat == 2) {
            //近三天上传
            $condition['created >='] = date('Y-m-d H:i:s', strtotime(DateTime) - 3 * 24 * 60 * 60);
            $condition['created <='] = DateTime;
        }
        if ($cat == 3) {
            //近一周上传
            $condition['created >='] = date('Y-m-d H:i:s', strtotime(DateTime) - 7 * 24 * 60 * 60);
            $condition['created <='] = DateTime;
        }
        if ($cat == 4) {
            //近一月上传
            $condition['created >='] = date('Y-m-d H:i:s', strtotime(DateTime) - 31 * 24 * 60 * 60);
            $condition['created <='] = DateTime;
        }
        if ($cat == 5) {
            //一月之前上传
            $condition['created <='] = date('Y-m-d H:i:s', strtotime(DateTime) - 31 * 24 * 60 * 60);
        }

        if ($search_key_word != '') {
            $condition['name like'] = "%$search_key_word%";
        }
        $total = $this->PhotoCategoryGallery->find('count', array('conditions' => $condition));//统计全部品牌总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'PhotoCategoryGallery';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'image_spaces','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'PhotoCategoryGallery');
        $this->Pagination->init($condition, $parameters, $options);
        $photo_category_gallery_list = $this->PhotoCategoryGallery->find('all', array('conditions' => $condition, 'order' => $order, 'limit' => $rownum, 'page' => $page));

        $this->set('photo_category_gallery_list', $photo_category_gallery_list);
        $this->set('id_str', $id_str);
        $this->set('orderby', $orderby);
        $this->set('cat', $cat);
        $this->set('photo_category_id', $photo_category_id);
        $this->set('search_key_word', $search_key_word);
        $photo_category_data = $this->PhotoCategory->tree($this->locale);
        $this->set('photo_category_data', $photo_category_data);
        $this->set('title_for_layout', $this->ld['image_space'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($id = 0)
    {
        $this->operator_privilege('image_spaces_photo');
        $this->set('title_for_layout', $this->ld['image_space'].' - '.$this->configs['shop_name']);
        $this->menu_path = array('root' => '/cms/','sub' => '/image_spaces/');
        $this->navigations[] = array('name' => $this->ld['image_space'],'url' => '/image_spaces/');
        $this->navigations[] = array('name' => $this->ld['image_view'],'url' => '');
        $phpoto_category_gallery_info = $this->PhotoCategoryGallery->find('first', array('conditions' => array('id' => $id)));
        $phpoto_category_gallery_info1 = $this->PhotoCategoryGallery->find('first', array('conditions' => array('id >' => $id), 'order' => 'id asc'));
        $phpoto_category_gallery_info2 = $this->PhotoCategoryGallery->find('first', array('conditions' => array('id <' => $id), 'order' => 'id desc'));
        $phpoto_category_gallery_info = $this->get_abs_url($phpoto_category_gallery_info);
        $phpoto_category_gallery_info1 = $this->get_abs_url($phpoto_category_gallery_info1);
        $phpoto_category_gallery_info2 = $this->get_abs_url($phpoto_category_gallery_info2);
        $cat = $this->PhotoCategory->find('first', array('conditions' => array('PhotoCategory.id' => $phpoto_category_gallery_info['PhotoCategoryGallery']['photo_category_id']), 'fields' => 'PhotoCategoryI18n.name'));
        $this->set('cat', $cat['PhotoCategoryI18n']['name']);
        $this->set('phpoto_category_gallery_info', $phpoto_category_gallery_info);
        $this->set('phpoto_category_gallery_info1', $phpoto_category_gallery_info1);
        $this->set('phpoto_category_gallery_info2', $phpoto_category_gallery_info2);
    }

    public function select_image($id_str = '', $orderby = 0, $cat = 0, $photo_category_id = 0, $search_key_word = '', $page = 1)
    {
        $this->set('title_for_layout', $this->ld['image_space'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['image_space'],'url' => '/image_spaces/');
        $this->navigations[] = array('name' => $this->ld['image_select'],'url' => '');

        $condition = '';
        $order = '';
        if ($orderby == 0) {
            //按上传时间从晚到早
            $order = 'created desc';
        }
        if ($orderby == 1) {
            //按上传时间从早到晚
            $order = 'created asc';
        }
        if ($orderby == 2) {
            //按图片从大到小
            $order = 'original_size desc';
        }
        if ($orderby == 3) {
            //按图片从小到大
            $order = 'original_size asc';
        }
        if ($orderby == 4) {
            //按修改时间从晚到早
            $order = 'modified desc';
        }
        if ($orderby == 5) {
            //按修改时间从早到晚
            $order = 'modified asc';
        }
        if ($orderby == 6) {
            //按图片名降序
            $order = 'name desc';
        }
        if ($orderby == 7) {
            //按图片名升序
            $order = 'name asc';
        }
        if ($photo_category_id > 0) {
            $condition['photo_category_id like'] = $photo_category_id;
        }
        //左边筛选
        if ($cat == 1) {
            //今天上传
            $condition['created >='] = Today.' '.StartTime;
            $condition['created <='] = Today.' '.EndTime;
        }
        if ($cat == 2) {
            //近三天上传
            $condition['created >='] = date('Y-m-d H:i:s', strtotime(DateTime) - 3 * 24 * 60 * 60);
            $condition['created <='] = DateTime;
        }
        if ($cat == 3) {
            //近一周上传
            $condition['created >='] = date('Y-m-d H:i:s', strtotime(DateTime) - 7 * 24 * 60 * 60);
            $condition['created <='] = DateTime;
        }
        if ($cat == 4) {
            //近一月上传
            $condition['created >='] = date('Y-m-d H:i:s', strtotime(DateTime) - 31 * 24 * 60 * 60);
            $condition['created <='] = DateTime;
        }
        if ($cat == 5) {
            //一月之前上传
            $condition['created <='] = date('Y-m-d H:i:s', strtotime(DateTime) - 31 * 24 * 60 * 60);
        }

        if ($search_key_word != '') {
            $condition['name like'] = "%$search_key_word%";
        }
        $total = $this->PhotoCategoryGallery->find('count', array('conditions' => $condition));//统计全部品牌总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'PhotoCategoryGallery';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'image_spaces','action' => 'select_image','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'PhotoCategoryGallery');
        $this->Pagination->init($condition, $parameters, $options);
        $photo_category_gallery_list = $this->PhotoCategoryGallery->find('all', array('conditions' => $condition, 'order' => $order, 'limit' => $rownum, 'page' => $page));
        $this->set('photo_category_gallery_list', $photo_category_gallery_list);
        $this->set('id_str', $id_str);
        $this->set('orderby', $orderby);
        $this->set('cat', $cat);
        $this->set('photo_category_id', $photo_category_id);
        $this->set('search_key_word', $search_key_word);
        $photo_category_data = $this->PhotoCategory->tree($this->locale);
        $this->set('photo_category_data', $photo_category_data);
        if (isset($_REQUEST['type'])) {
            $this->set('type', $_REQUEST['type']);
        }
        $this->layout = 'window';
    }

    public function upload($photo_category_id = 0, $page = 1)
    {
        $this->operator_privilege('image_spaces_upload');
        $this->menu_path = array('root' => '/cms/','sub' => '/image_spaces/');
        $this->set('title_for_layout', $this->ld['image_space'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['image_space'],'url' => '/image_spaces/');
        $this->navigations[] = array('name' => $this->ld['image_upload'],'url' => '/image_spaces/upload/');
        $this->set('la', $this->Language->find('first', array('conditions' => array('Language.is_default' => 1), 'fields' => array('Language.locale'))));
        $session_config_str = 1;
        $small_img_height = (isset($this->configs['small_img_height']) && $this->configs['small_img_height'] > 0) ? $this->configs['small_img_height'] : 140;
        $small_img_width = (isset($this->configs['small_img_width']) && $this->configs['small_img_width'] > 0) ? $this->configs['small_img_width'] : 140;
        $mid_img_height = (isset($this->configs['mid_img_height']) && $this->configs['mid_img_height'] > 0) ? $this->configs['mid_img_height'] : 400;
        $mid_img_width = (isset($this->configs['mid_img_width']) && $this->configs['mid_img_width'] > 0) ? $this->configs['mid_img_width'] : 400;
        $big_img_height = (isset($this->configs['big_img_height']) && $this->configs['big_img_height'] > 0) ? $this->configs['big_img_height'] : 800;
        $big_img_width = (isset($this->configs['big_img_width']) && $this->configs['big_img_width'] > 0) ? $this->configs['big_img_width'] : 800;
        $custom = 0;
        $this->set('small_img_height_sys', $small_img_height);
        $this->set('small_img_width_sys', $small_img_width);
        $this->set('mid_img_height_sys', $mid_img_height);
        $this->set('mid_img_width_sys', $mid_img_width);
        $this->set('big_img_height_sys', $big_img_height);
        $this->set('big_img_width_sys', $big_img_width);
        $session_admin_config_str = 1;
        $cart_back_url = 1;
        $this->set('cart_back_url', $cart_back_url);
        $this->PhotoCategory->set_locale($this->locale);
        $photo_category_list = $this->PhotoCategory->find('all', array('order' => 'orderby'));
        $this->set('photo_category_list', $photo_category_list);
        $this->set('photo_category_id', $photo_category_id);
        if (isset($_REQUEST['img_cat'])) {
            $this->set('img_cat', $_REQUEST['img_cat']);
            foreach ($photo_category_list as $k => $v) {
                if ($v['PhotoCategory']['id'] == $_REQUEST['img_cat'] && $v['PhotoCategory']['custom'] == 1) {
                    $small_img_height = (isset($v['PhotoCategory']['cat_small_img_height']) && $v['PhotoCategory']['cat_small_img_height'] > 0) ? $v['PhotoCategory']['cat_small_img_height'] : $small_img_height;
                    $small_img_width = (isset($v['PhotoCategory']['cat_small_img_width']) && $v['PhotoCategory']['cat_small_img_width'] > 0) ? $v['PhotoCategory']['cat_small_img_width'] : $small_img_height;
                    $mid_img_height = (isset($v['PhotoCategory']['cat_mid_img_height']) && $v['PhotoCategory']['cat_mid_img_height'] > 0) ? $v['PhotoCategory']['cat_mid_img_height'] : $small_img_height;
                    $mid_img_width = (isset($v['PhotoCategory']['cat_mid_img_width']) && $v['PhotoCategory']['cat_mid_img_width'] > 0) ? $v['PhotoCategory']['cat_mid_img_width'] : $small_img_height;
                    $big_img_height = (isset($v['PhotoCategory']['cat_big_img_height']) && $v['PhotoCategory']['cat_big_img_height'] > 0) ? $v['PhotoCategory']['cat_big_img_height'] : $small_img_height;
                    $big_img_width = (isset($v['PhotoCategory']['cat_big_img_width']) && $v['PhotoCategory']['cat_big_img_width'] > 0) ? $v['PhotoCategory']['cat_big_img_width'] : $small_img_height;
                    $custom = 1;
                }
            }
        }
        $this->set('small_img_height', $small_img_height);
        $this->set('small_img_width', $small_img_width);
        $this->set('mid_img_height', $mid_img_height);
        $this->set('mid_img_width', $mid_img_width);
        $this->set('big_img_height', $big_img_height);
        $this->set('big_img_width', $big_img_width);
        $this->set('custom', $custom);
        $condition = '';
        $total = $this->PhotoCategoryGallery->find('count', array('conditions' => $condition));//统计全部品牌总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'PhotoCategoryGallery';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'image_spaces','action' => 'upload','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'PhotoCategoryGallery');
        $this->Pagination->init($condition, $parameters, $options);
        $photo_category_gallery_list = $this->PhotoCategoryGallery->find('all', array('conditions' => $condition, 'limit' => $rownum, 'page' => $page));
        $this->set('photo_category_gallery_list', $photo_category_gallery_list);
        $this->set('doc_root', basename(dirname($_SERVER['DOCUMENT_ROOT'])));
    }

    public function upload2($id_str = '', $page = 1)
    {
        $this->set('title_for_layout', $this->ld['image_space'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['image_space'],'url' => '/image_spaces/');
        $this->navigations[] = array('name' => $this->ld['image_upload'],'url' => '/image_spaces/upload/');
        $this->set('navigations', $this->navigations);
        $this->set('la', $this->Language->find('first', array('conditions' => array('Language.is_default' => 1), 'fields' => array('Language.locale'))));
        $session_config_str = 1;
        $session_admin_config_str = 1;
        $small_img_height = (isset($this->configs['small_img_height']) && $this->configs['small_img_height'] > 0) ? $this->configs['small_img_height'] : 140;
        $small_img_width = (isset($this->configs['small_img_width']) && $this->configs['small_img_width'] > 0) ? $this->configs['small_img_width'] : 140;
        $mid_img_height = (isset($this->configs['mid_img_height']) && $this->configs['mid_img_height'] > 0) ? $this->configs['mid_img_height'] : 400;
        $mid_img_width = (isset($this->configs['mid_img_width']) && $this->configs['mid_img_width'] > 0) ? $this->configs['mid_img_width'] : 400;
        $big_img_height = (isset($this->configs['big_img_height']) && $this->configs['big_img_height'] > 0) ? $this->configs['big_img_height'] : 800;
        $big_img_width = (isset($this->configs['big_img_width']) && $this->configs['big_img_width'] > 0) ? $this->configs['big_img_width'] : 800;
        $custom = 0;
        $this->set('small_img_height_sys', $small_img_height);
        $this->set('small_img_width_sys', $small_img_width);
        $this->set('mid_img_height_sys', $mid_img_height);
        $this->set('mid_img_width_sys', $mid_img_width);
        $this->set('big_img_height_sys', $big_img_height);
        $this->set('big_img_width_sys', $big_img_width);

        if (isset($_REQUEST['img_cat'])) {
            $this->set('img_cat', $_REQUEST['img_cat']);
            foreach ($photo_category_list as $k => $v) {
                if ($v['PhotoCategory']['id'] == $_REQUEST['img_cat'] && $v['PhotoCategory']['custom'] == 1) {
                    $small_img_height = (isset($v['PhotoCategory']['cat_small_img_height']) && $v['PhotoCategory']['cat_small_img_height'] > 0) ? $v['PhotoCategory']['cat_small_img_height'] : $small_img_height;
                    $small_img_width = (isset($v['PhotoCategory']['cat_small_img_width']) && $v['PhotoCategory']['cat_small_img_width'] > 0) ? $v['PhotoCategory']['cat_small_img_width'] : $small_img_height;
                    $mid_img_height = (isset($v['PhotoCategory']['cat_mid_img_height']) && $v['PhotoCategory']['cat_mid_img_height'] > 0) ? $v['PhotoCategory']['cat_mid_img_height'] : $small_img_height;
                    $mid_img_width = (isset($v['PhotoCategory']['cat_mid_img_width']) && $v['PhotoCategory']['cat_mid_img_width'] > 0) ? $v['PhotoCategory']['cat_mid_img_width'] : $small_img_height;
                    $big_img_height = (isset($v['PhotoCategory']['cat_big_img_height']) && $v['PhotoCategory']['cat_big_img_height'] > 0) ? $v['PhotoCategory']['cat_big_img_height'] : $small_img_height;
                    $big_img_width = (isset($v['PhotoCategory']['cat_big_img_width']) && $v['PhotoCategory']['cat_big_img_width'] > 0) ? $v['PhotoCategory']['cat_big_img_width'] : $small_img_height;
                    $custom = 1;
                }
            }
        }
        $this->set('small_img_height', $small_img_height);
        $this->set('small_img_width', $small_img_width);
        $this->set('mid_img_height', $mid_img_height);
        $this->set('mid_img_width', $mid_img_width);
        $this->set('big_img_height', $big_img_height);
        $this->set('big_img_width', $big_img_width);
        $this->set('custom', $custom);
        $cart_back_url = 1;
        $this->set('cart_back_url', $cart_back_url);
        $this->PhotoCategory->set_locale($this->locale);
        $photo_category_list = $this->PhotoCategory->find('all', array('order' => 'orderby'));
        $this->set('photo_category_list', $photo_category_list);
        $this->set('photo_category_id', 0);
        $condition = '';
        $total = $this->PhotoCategoryGallery->find('count', array('conditions' => $condition));//统计全部品牌总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'PhotoCategoryGallery';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'image_spaces','action' => 'upload2','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'PhotoCategoryGallery');
        $this->Pagination->init($condition, $parameters, $options);
        $photo_category_gallery_list = $this->PhotoCategoryGallery->find('all', array('conditions' => $condition, 'limit' => $rownum, 'page' => $page));
        $this->set('photo_category_gallery_list', $photo_category_gallery_list);
        $this->set('doc_root', basename(dirname($_SERVER['DOCUMENT_ROOT'])));
        $this->set('id_str', $id_str);
        if (isset($_REQUEST['type'])) {
            $this->set('type', $_REQUEST['type']);
        }
        $this->layout = 'window';
    }

    public function upload3($product_code = 0, $page = 1)
    {
        $this->operator_privilege('image_spaces_upload');
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->set('title_for_layout', $this->ld['image_space'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => '上传旋转图片','url' => '/image_spaces/upload3/'.$product_code);
        $this->set('la', $this->Language->find('first', array('conditions' => array('Language.is_default' => 1), 'fields' => array('Language.locale'))));
        $session_config_str = 1;
        $custom = 0;
        $this->set('product_code', $product_code);
        $session_admin_config_str = 1;
        $cart_back_url = 1;
        $this->set('cart_back_url', $cart_back_url);
        if (isset($_REQUEST['img_cat'])) {
            $this->set('img_cat', $_REQUEST['img_cat']);
        }
        $this->set('custom', $custom);
        $condition = '';
        $total = $this->PhotoCategoryGallery->find('count', array('conditions' => $condition));//统计全部品牌总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'PhotoCategoryGallery';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'image_spaces','action' => 'upload','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'PhotoCategoryGallery');
        $this->Pagination->init($condition, $parameters, $options);
        $photo_category_gallery_list = $this->PhotoCategoryGallery->find('all', array('conditions' => $condition, 'limit' => $rownum, 'page' => $page));
        $this->set('photo_category_gallery_list', $photo_category_gallery_list);
        $this->set('doc_root', basename(dirname($_SERVER['DOCUMENT_ROOT'])));
    }

    /*
        上传360旋转图片
    */
    public function uploadproductrotation($product_code = '', $page = 1)
    {
        $this->operator_privilege('image_spaces_upload');
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->set('title_for_layout', $this->ld['image_space'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => '上传旋转图片','url' => '/image_spaces/upload3/'.$product_code);
        $this->set('la', $this->Language->find('first', array('conditions' => array('Language.is_default' => 1), 'fields' => array('Language.locale'))));
        $this->set('product_code', $product_code);
        $cart_back_url = 1;
        $this->set('cart_back_url', $cart_back_url);
        $custom = 0;
        $this->set('custom', $custom);
        if (isset($_REQUEST['img_cat'])) {
            $this->set('img_cat', $_REQUEST['img_cat']);
        }
        $condition = '';
        $total = $this->PhotoCategoryGallery->find('count', array('conditions' => $condition));//统计全部品牌总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'PhotoCategoryGallery';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'image_spaces','action' => 'upload','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'PhotoCategoryGallery');
        $this->Pagination->init($condition, $parameters, $options);
        $photo_category_gallery_list = $this->PhotoCategoryGallery->find('all', array('conditions' => $condition, 'limit' => $rownum, 'page' => $page));
        $this->set('photo_category_gallery_list', $photo_category_gallery_list);
        $this->set('doc_root', basename(dirname($_SERVER['DOCUMENT_ROOT'])));

        if ($product_code != '') {
            //遍历目录下文件
            $file_addr = WWW_ROOT.'media/360Rotation/'.$product_code.'/big';
            if (is_dir($file_addr)) {
                $file_data = $this->traverse($file_addr);
                $rotation_img_file = array();//记录现有图片文件路径
                if (!empty($file_data)) {
                    foreach ($file_data as $v) {
                        $rotation_img_file[$v] = '/media/360Rotation/'.$product_code.'/big/'.$v;
                    }
                }
                ksort($rotation_img_file);
                $this->set('rotation_img_file', $rotation_img_file);
            }
        }
    }

    /*
        遍历目录下文件
    */
    public function traverse($path = '.')
    {
        $file_data = array();
        $current_dir = opendir($path);//opendir()返回一个目录句柄,失败返回false
          while (($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
              $sub_dir = $path.DIRECTORY_SEPARATOR.$file;    //构建子目录路径
              if ($file == '.' || $file == '..') {
                  continue;
              } elseif (is_dir($sub_dir)) {    //如果是目录,进行递归
                   continue;
                 //echo 'Directory ' . $sub_dir . ':<br>';
                 //traverse($sub_dir);
              } else {
                  //如果是文件,直接输出
                     $file_data[] = $file;
                 //echo 'File in Directory ' . $path . ': ' . $file . '<br>';
              }
          }

        return $file_data;
    }

    /**
     *图片分类列表。.
     */
    public function category_list($page = 1)
    {
        $this->operator_privilege('image_spaces_category');
        $this->menu_path = array('root' => '/cms/','sub' => '/image_spaces/');
        $this->navigations[] = array('name' => $this->ld['image_space'],'url' => '/image_spaces/');
        $this->navigations[] = array('name' => $this->ld['pictures_category'],'url' => '/image_spaces/category_list');

        $condition = '';
        $this->PhotoCategory->set_locale($this->locale);
        $total = $this->PhotoCategory->find('count', array('conditions' => $condition));//统计总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'PhotoCategory';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'image_spaces','action' => 'category_list','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'PhotoCategory');
        $this->Pagination->init($condition, $parameters, $options);
        $photo_category_list = $this->PhotoCategory->find('all', array('conditions' => $condition, 'order' => 'orderby', 'limit' => $rownum, 'page' => $page));

        $photo_category_no_count = $this->PhotoCategoryGallery->find('count', array('conditions' => array('photo_category_id' => 0)));

        $this->set('photo_category_list', $photo_category_list);
        $this->set('photo_category_no_count', $photo_category_no_count);
        $this->set('title_for_layout', $this->ld['image_space'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function category_view($id = 0)
    {
        $this->operator_privilege('image_spaces_category');
        $this->menu_path = array('root' => '/cms/','sub' => '/image_spaces/');
        $this->set('title_for_layout', $this->ld['image_space'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['image_space'],'url' => '/image_spaces/');
        $this->navigations[] = array('name' => $this->ld['pictures_category'],'url' => '/image_spaces/category_list');
        $this->navigations[] = array('name' => $this->ld['add'].'/'.$this->ld['edit'].$this->ld['pictures_category'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            foreach ($this->data['PhotoCategoryI18n'] as $k => $v) {
                if (trim($v['name']) == '') {
                    $alert = sprintf($this->ld['name_not_be_empty'], $this->ld['picture_category_name']);
                    $this->set('alert', $alert);

                    return;
                }
            }
            $this->data['PhotoCategory']['orderby'] = !empty($this->data['PhotoCategory']['orderby']) ? $this->data['PhotoCategory']['orderby'] : 50;
            if (!empty($this->data['PhotoCategory']['id'])) {
                $this->PhotoCategory->save(array('PhotoCategory' => $this->data['PhotoCategory']));
                $id = $this->data['PhotoCategory']['id'];
            } else {
                $this->PhotoCategory->saveAll(array('PhotoCategory' => $this->data['PhotoCategory']));
                $id = $this->PhotoCategory->getLastInsertId();
            }
            $this->PhotoCategoryI18n->deleteAll(array('photo_category_id' => $id));
            foreach ($this->data['PhotoCategoryI18n'] as $k => $v) {
                $v['photo_category_id'] = $id;
                $this->PhotoCategoryI18n->saveAll(array('PhotoCategoryI18n' => $v));
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].':'.$this->ld['pictures_category'].' id '.$id, $this->admin['id']);
            }
            $this->redirect('/image_spaces/category_list');
        }

        $photo_categories_info = $this->PhotoCategory->localeformat($id);
        $this->set('photo_categories_info', $photo_categories_info);
        $this->set('id', $id);
    }

    public function category_change($id)
    {
        $this->operator_privilege('image_spaces_category');
        $this->set('title_for_layout', $this->ld['image_space'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['image_space'],'url' => '/image_spaces/');
        $this->navigations[] = array('name' => $this->ld['pictures_category'],'url' => '/image_spaces/category_list');
        $this->navigations[] = array('name' => $this->ld['transfer_photos_category'],'url' => '');
        $image_info = $this->PhotoCategoryGallery->findbyid($id);
        $this->set('image_info', $image_info);
        $photo_category_data = $this->PhotoCategory->tree($this->locale);
        $this->set('photo_category_data', $photo_category_data);
        if ($this->RequestHandler->isPost()) {
            if (!empty($this->data['PhotoCategory']['id'])) {
                //$this->PhotoCategoryGallery->save(array("photo_category_id"=>$this->data["PhotoCategory"]["id"]));
                 $this->PhotoCategoryGallery->save(array('PhotoCategoryGallery' => array('id' => $id, 'photo_category_id' => $this->data['PhotoCategory']['id'])));
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['transfer_photos_category'].':id '.$id, $this->admin['id']);
            }
            $this->redirect('/image_spaces/');
        }
    }

    public function replace_img($imgid=0)
    {
        $this->operator_privilege('image_spaces_photo');
        $this->menu_path = array('root' => '/cms/','sub' => '/image_spaces/');
        $image_info = $this->PhotoCategoryGallery->find('first', array('conditions' => array('id' => $imgid)));
        $this->set('la', $this->Language->find('first', array('conditions' => array('Language.is_default' => 1), 'fields' => array('Language.locale'))));
        $this->set('image_info', $image_info);
        $this->set('title_for_layout', $this->ld['image_space'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['image_space'],'url' => '/image_spaces/');
        $this->navigations[] = array('name' => $this->ld['image_replace'],'url' => '/image_spaces/replace_img/'.$imgid);
        $cart_back_url = 1;
        $this->set('cart_back_url', $cart_back_url);
        $server_name = $_SERVER['SERVER_NAME'];
        $md5_info = md5($server_name.'ioco');
        $this->set('md5_info', $md5_info);
        $this->set('doc_root', basename(dirname($_SERVER['DOCUMENT_ROOT'])));
        $this->set('operator_name', $this->admin['name']);
        if ($this->RequestHandler->isPost()) {
            $this->PhotoCategoryGallery->save(array('PhotoCategoryGallery' => array('id' => $imgid)));
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['image_replace'].':'.$imgid, $this->admin['id']);
            }
            $this->redirect('/image_spaces/');
        }
        $this->set('img_server_host', Configure::read('server_host'));
        $this->PhotoCategory->set_locale($this->locale);
        $photo_category_list = $this->PhotoCategory->find('all', array('order' => 'orderby'));
        $this->set('photo_category_list', $photo_category_list);
    }

    /**
     *删除一个图片空间分类.
     *
     *@param int $id 输入图片空间分类ID
     */
    public function category_remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $this->PhotoCategory->hasOne = array();
        $this->PhotoCategory->hasMany = array();
        $pn = $this->PhotoCategoryI18n->find('list', array('fields' => array('PhotoCategoryI18n.photo_category_id', 'PhotoCategoryI18n.name'), 'conditions' => array('PhotoCategoryI18n.photo_category_id' => $id, 'PhotoCategoryI18n.locale' => $this->locale)));
        $this->PhotoCategory->deleteAll(array('id' => $id));
        $this->PhotoCategoryI18n->deleteAll(array('photo_category_id' => $id));
        $this->PhotoCategoryGallery->updateAll(array('photo_category_id' => 0), array('photo_category_id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_image_space_category'].':'.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *保存SWFUPLOAD提交过来的图片数据.
     */
    public function image_data_save()
    {
        $image_data = $_REQUEST['image_data'];
        $image_data = str_replace('\\', '', $image_data);
        $image_data = json_decode($image_data);
        $image_data = (array) $image_data;
        $image_data['img'] = (array) $image_data['img'];
        if (isset($image_data['img']['id']) && $image_data['img']['id'] != '') {
            //$this->PhotoCategoryGallery->Id = $image_data["img"]["id"];
            $this->PhotoCategoryGallery->save(array('PhotoCategoryGallery' => $image_data['img']));
            //$image_data['img']['name']=$image_info['PhotoCategoryGallery']['name'];
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['image_replace'].':'.$image_data['img']['name'], $this->admin['id']);
            }
        //	$image_info = $this->PhotoCategoryGallery->findbyid($image_data["img"]["id"]);
        //	$image_data['img']['img_small']=$image_info['PhotoCategoryGallery']['img_small'];
        //	$image_data['img']['img_original']=$image_info['PhotoCategoryGallery']['img_original'];
        //	$image_data['img']['img_detail']=$image_info['PhotoCategoryGallery']['img_detail'];
        } else {
            $image_data['img']['id'] = 0;
            if ($image_data['error'] == false) {
                $this->PhotoCategoryGallery->saveAll(array('PhotoCategoryGallery' => $image_data['img']));
                $id = $this->PhotoCategoryGallery->getLastInsertId();
                $photo_list = $this->PhotoCategoryGallery->find('first', array('conditions' => array('PhotoCategoryGallery.id' => $id)));
                $image_data['img']['id'] = $id;
                $image_name = isset($photo_list['PhotoCategoryGallery']['name']) ? $photo_list['PhotoCategoryGallery']['name'] : '';
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['image_upload'].':'.$image_name, $this->admin['id']);
                }
            }
        }
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        $this->set('image_data', $image_data);
    }

    public function update_photo_name()
    {
        $this->PhotoCategoryGallery->hasMany = array();
        $this->PhotoCategoryGallery->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if ($this->PhotoCategoryGallery->save(array('id' => $id, 'name' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *删除图片操作.
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_image_failure'];
        $photo_category_gallery_info = $this->PhotoCategoryGallery->find('first', array('conditions' => array('id' => $id)));
        $img_small = $photo_category_gallery_info['PhotoCategoryGallery']['img_small'];
        $img_detail = $photo_category_gallery_info['PhotoCategoryGallery']['img_detail'];
        $img_big = $photo_category_gallery_info['PhotoCategoryGallery']['img_big'];
        $img_original = $photo_category_gallery_info['PhotoCategoryGallery']['img_original'];
        $pattern = "/(http[s]?:\/\/)(\w+\.)+\w+/";
        $img_small_ff = preg_replace($pattern, '', $img_small);
        $img_detail_ff = preg_replace($pattern, '', $img_detail);
        $img_big_ff = preg_replace($pattern, '', $img_big);
        $img_original_ff = preg_replace($pattern, '', $img_original);
//        if(stristr($this->img_server_host,'62585578')){
            $this->remove_image($img_small_ff, $img_detail_ff, $img_big_ff, $img_original_ff);
//        }else{
//        	$bb=file_get_contents($this->img_server_host."/photos/remove_image?img_small=".$img_small_ff."&img_detail=".$img_detail_ff."&img_big=".$img_big_ff."&img_original=".$img_original_ff);
//
//        }

        $this->PhotoCategoryGallery->deleteAll(array('id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_photo'].':'.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_image_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *删除图片操作.
     */
    public function batch_remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_image_failure'];
        $id_arr = explode('-', $id);
        foreach ($id_arr as $k => $v) {
            $photo_category_gallery_info = $this->PhotoCategoryGallery->find('first', array('conditions' => array('id' => $v)));
            $img_small = $photo_category_gallery_info['PhotoCategoryGallery']['img_small'];
            $img_detail = $photo_category_gallery_info['PhotoCategoryGallery']['img_detail'];
            $img_big = $photo_category_gallery_info['PhotoCategoryGallery']['img_big'];
            $img_original = $photo_category_gallery_info['PhotoCategoryGallery']['img_original'];
            $pattern = "/(http[s]?:\/\/)(\w+\.)+\w+/";
            $img_small_ff = preg_replace($pattern, '', $img_small);
            $img_detail_ff = preg_replace($pattern, '', $img_detail);
            $img_big_ff = preg_replace($pattern, '', $img_big);
            $img_original_ff = preg_replace($pattern, '', $img_original);
//	        if(stristr($this->img_server_host,'62585578')){
            $this->remove_image($img_small_ff, $img_detail_ff, $img_big_ff, $img_original_ff);
//        	}else{
//file_get_contents($this->img_server_host."/photos/remove_image?img_small=".$img_small_ff."&img_detail=".$img_detail_ff."&img_big=".$img_big_ff."&img_original=".$img_original_ff);
//			}
        }
        $this->PhotoCategoryGallery->deleteAll(array('id' => $id_arr));
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_image_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function doinsertphotocat()
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['add_failure'];
        foreach ($this->data['PhotoCategoryI18n'] as $k => $v) {
            if (trim($v['name']) == '') {
                die(json_encode($result));
            }
        }
        $this->data['PhotoCategory']['orderby'] = !empty($this->data['PhotoCategory']['orderby']) ? $this->data['PhotoCategory']['orderby'] : 50;
        $this->PhotoCategory->saveAll(array('PhotoCategory' => $this->data['PhotoCategory']));
        $id = $this->PhotoCategory->getLastInsertId();
        $this->PhotoCategoryI18n->deleteAll(array('photo_category_id' => $id));
        foreach ($this->data['PhotoCategoryI18n'] as $k => $v) {
            $v['photo_category_id'] = $id;
            $this->PhotoCategoryI18n->saveAll(array('PhotoCategoryI18n' => $v));
            if ($v['locale'] == $this->locale) {
                $userinformation_name = $v['name'];
            }
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['add_successful'];
        $photo_category_list = $this->PhotoCategory->find('all', array('conditions' => array('PhotoCategoryI18n.locale' => $this->locale), 'order' => 'orderby'));
        $result['cat'] = '<select id="photos_cat_id" onchange="get_size(this.value);" style="width:200px;float:left;"><option value="0">'.$this->ld['select_a_category'].'</option>';
        foreach ($photo_category_list as $k => $v) {
            if ($v['PhotoCategory']['id'] == $id) {
                $result['cat'] .= '<option  value="'.$v['PhotoCategory']['id'].'" selected>'.$v['PhotoCategoryI18n']['name'].'</option>';
            } else {
                $result['cat'] .= '<option  value="'.$v['PhotoCategory']['id'].'">'.$v['PhotoCategoryI18n']['name'].'</option>';
            }
        }
        $result['cat'] .= '</select><input type="button" class="am-btn am-btn-success am-radius am-btn-sm" data-am-modal="{target: \'\#photocat\', closeViaDimmer: 0, width: 400, height: 230}" value="'.$this->ld['quick_add_category'].'" />';
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_quick_add_photo_category'].':'.$userinformation_name, $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function batch()
    {
        //批量处理
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->PhotoCategory->hasOne = array();
            $this->PhotoCategory->hasMany = array();
            $this->PhotoCategory->deleteAll(array('id' => $v));
            $this->PhotoCategoryI18n->deleteAll(array('photo_category_id' => $v));
            $this->PhotoCategoryGallery->updateAll(array('photo_category_id' => 0), array('photo_category_id' => $v));
        }
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], 'img_category');
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }

    //批量加水印
    public function batch_water($id)
    {
        $this->operator_privilege('image_spaces_upload');
        $result['flag'] = 2;
        $result['message'] = $this->ld['add_failure'];
        $id_arr = explode('-', $id);
        $pattern = "/(http[s]?:\/\/)(\w+\.)+\w+\//"; //获取相对路径 i/2011/11/img_ioco_dev/c598200.ioco.dev/small/2/1a1385a0c5d074a2e5188f41dfa3fe9b0.jpg
        $watermark_file = preg_replace($pattern, '', $this->configs['image-watermake-image']);
        $watermark_location = $this->configs['image-watermake-location'];
        $watermark_transparency = $this->configs['image-watermake-transparency'];
        $type = $_GET['type'];
        $text = $type == 2 ? $this->configs['image-watermake-word'] : '';
        $font = $type == 2 ? $this->configs['image-watermake-typeface'] : '';
        $size = $type == 2 ? $this->configs['image-watermake-wordsize'] : '';
        $color = $type == 2 ? $this->configs['image-watermake-wordcolor'] : '';
        $color = substr($color, 1, 6);
        $photos = $this->PhotoCategoryGallery->find('all', array('conditions' => array('id' => $id_arr)));
        $x = '';
        foreach ($photos as $k => $v) {
            $img_original = preg_replace($pattern, '', $v['PhotoCategoryGallery']['img_original']);
            $img_big = preg_replace($pattern, '', $v['PhotoCategoryGallery']['img_big']);
            $x .= file_get_contents($this->server_host.'/admin/photo_category_gallery/add_image_water?img_original='.$img_original.'&wf='.$watermark_file.'&wl='.$watermark_location.'&wt='.$watermark_transparency.'&type='.$type.'&text='.$text.'&font='.$font.'&size='.$size.'&color='.$color.'&time='.time());
        }
        $result['flag'] = 1;
        $result['message'] = sprintf($this->ld['operator_successful'], count($id_arr));
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //删除图片物理文件 yb用
    public function remove_image($img_small_ff, $img_detail_ff, $img_big_ff, $img_original_ff)
    {
        $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);
        if (file_exists($img_dir.$img_small_ff)) {
            unlink($img_dir.$img_small_ff);
        }
        if (file_exists($img_dir.$img_detail_ff)) {
            unlink($img_dir.$img_detail_ff);
        }
        if (file_exists($img_dir.$img_big_ff)) {
            unlink($img_dir.$img_big_ff);
        }
        if (file_exists($img_dir.$img_original_ff)) {
            unlink($img_dir.$img_original_ff);
        }
    }

    //相对路径拼接成绝对路径
    public function get_abs_url($PhotoCategoryGallery)
    {
        if (empty($PhotoCategoryGallery)) {
            return;
        }
//		if(!stristr($PhotoCategoryGallery['PhotoCategoryGallery']['img_small'],IMG_HOST)){
//			$PhotoCategoryGallery['PhotoCategoryGallery']['img_small'] = IMG_HOST.$PhotoCategoryGallery['PhotoCategoryGallery']['img_small'];
//
//		}
//		if(!stristr($PhotoCategoryGallery['PhotoCategoryGallery']['img_detail'],IMG_HOST)){
//			$PhotoCategoryGallery['PhotoCategoryGallery']['img_detail'] = IMG_HOST.$PhotoCategoryGallery['PhotoCategoryGallery']['img_detail'];
//		}
//		if(!stristr($PhotoCategoryGallery['PhotoCategoryGallery']['img_big'],IMG_HOST)){
//			$PhotoCategoryGallery['PhotoCategoryGallery']['img_big'] = IMG_HOST.$PhotoCategoryGallery['PhotoCategoryGallery']['img_big'];
//		}
//		if(!stristr($PhotoCategoryGallery['PhotoCategoryGallery']['img_original'],IMG_HOST)){
//			$PhotoCategoryGallery['PhotoCategoryGallery']['img_original'] = IMG_HOST.$PhotoCategoryGallery['PhotoCategoryGallery']['img_original'];
//		}
        return $PhotoCategoryGallery;
    }

    public function get_cat_size()
    {
        $id = $_POST['cat_id'];
        $this->PhotoCategory->set_locale($this->locale);
        $photo_category = $this->PhotoCategory->find('first', array('conditions' => array('PhotoCategory.id' => $id), 'order' => 'orderby'));
        $result['content']['small_img_height'] = $photo_category['PhotoCategory']['cat_small_img_height'];
        $result['content']['small_img_width'] = $photo_category['PhotoCategory']['cat_small_img_width'];
        $result['content']['mid_img_height'] = $photo_category['PhotoCategory']['cat_mid_img_height'];
        $result['content']['mid_img_width'] = $photo_category['PhotoCategory']['cat_mid_img_width'];
        $result['content']['big_img_height'] = $photo_category['PhotoCategory']['cat_big_img_height'];
        $result['content']['big_img_width'] = $photo_category['PhotoCategory']['cat_big_img_width'];
        $result['flag'] = 1;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
