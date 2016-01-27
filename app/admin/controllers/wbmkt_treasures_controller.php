<?php

/*****************************************************************************
 * Seevia 短信日志
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
App::import('Vendor', 'weibo2', array('file' => 'saetv2.php'));
class WbmktTreasuresController extends AppController
{
    public $name = 'WbmktTreasures';
    public $components = array('Pagination','RequestHandler','Phpexcel','EcFlagWebservice');//,'EcFlagWebservice'
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');//分页样式
    public $uses = array('WbmktTreasure','SynchroOperator','ProductVolume','Language','Operator','Application','Product','ProductI18n','CategoryProduct','Brand','ProductType','Profile','ProfileFiled','TopicProduct','ProductTypeAttribute','ProductRelation','ProductArticle','Article','ProductAttribute','ProductsCategory','PhotoCategory','PhotoCategoryGallery','ProductGallery','ProductGalleryI18n','InformationResource','InformationResourceI18n','Stock','CategoryType','ProductDownload','UploadFile','Tag','TagI18n','ProductTypeAttributeI18n','Config');

    public function index($page = 1)
    {
        $trash_count = $this->Product->find('count', array('conditions' => array('Product.status' => 2), 'recursive' => -1));
        $this->set('trash_count', $trash_count);
        $this->navigations[] = array('name' => '微营销管理','url' => '');
        $this->navigations[] = array('name' => '微博荐宝','url' => '/wbmkt_treasures/');
        $this->set('title_for_layout', '微博荐宝'.' - '.$this->configs['shop_name']);

        //分类树	
        $category_tree = $this->CategoryProduct->tree('P', $this->backend_locale);
        $category_name_list = array();
        if (isset($category_tree) && sizeof($category_tree) > 0) {
            foreach ($category_tree as $first_k => $first_v) {
                $category_name_list[$first_v['CategoryProduct']['id']] = $first_v['CategoryProductI18n']['name'];
                if (isset($first_v['SubCategory']) && sizeof($first_v['SubCategory']) > 0) {
                    foreach ($first_v['SubCategory'] as $second_k => $second_v) {
                        $category_name_list[$second_v['CategoryProduct']['id']] = '--'.$second_v['CategoryProductI18n']['name'];
                        if (isset($second_v['SubCategory']) && sizeof($second_v['SubCategory']) > 0) {
                            foreach ($second_v['SubCategory'] as $third_k => $third_v) {
                                $category_name_list[$third_v['CategoryProduct']['id']] = '----'.$third_v['CategoryProductI18n']['name'];
                            }
                        }
                    }
                }
            }
        }
        $this->set('category_name_list', $category_name_list);
        $condition = '';
        $condition['Product.status'] = '1';
        //关键字
        $keywords = '';
        if (isset($this->params['url']['keywords']) && $this->params['url']['keywords'] != '') {
            $keywords = $this->params['url']['keywords'];
            $condition['and']['ProductI18n.name like'] = "%$keywords%";
            $this->set('keywords', $keywords);
        }
        //分类
        $product_type = '';
        if (isset($this->params['url']['product_type']) && $this->params['url']['product_type'] != '') {
            $product_type = $this->params['url']['product_type'];
            $condition['and']['Product.category_id'] = $product_type;
            $this->set('product_type', $product_type);
        }
        //分页
        $total = $this->Product->find('count', array('conditions' => $condition));//统计全部商品总数
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $rownum = '25';
        //pr($total);
        //$sortClass="Product";
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'products','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Product');
        $this->Pagination->init($condition, $parameters, $options);
        $fields[] = 'Product.id';
        $fields[] = 'Product.code';
        $fields[] = 'ProductI18n.name';
        $fields[] = 'Product.category_id'; //分类id
        $fields[] = 'Product.img_thumb';
        $product_list = $this->Product->find('all', array('conditions' => $condition, 'order' => 'Product.id desc', 'limit' => $rownum, 'page' => $page, 'fields' => $fields));
        $product_count = $this->Product->find('count', array('conditions' => $condition));
        $this->set('product_list', $product_list);
        $this->set('product_count', $product_count);
        //pr($category_name_list);die;
    }

    public function ui_all_box($id)
    {
        $condition['and']['Product.id'] = $id;
        $prod_list = $this->Product->find('all', array('conditions' => $condition));
    //	pr($prod_list);
        $this->set('prod_list', $prod_list);
    }
    public function history($user_id = 1)
    {
        $this->navigations[] = array('name' => '微营销管理','url' => '');
        $this->navigations[] = array('name' => '微博荐宝','url' => '/wbmkt_treasures/');
        $this->set('title_for_layout', '微博荐宝'.' - '.$this->configs['shop_name']);

        $condition = '';
        $wb_relog_count = $this->WbmktTreasure->find('count');

        $total = $wb_relog_count;
        $page = 1;
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $rownum = 10;
        $parameters['get'] = array();
        $parameters['route'] = array('controller' => 'wbmktContents','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'WbmktTreasure');
        $this->Pagination->init($condition, $parameters, $options);

        $wb_treasure = $this->WbmktTreasure->find('all', array('order' => 'modified desc', 'limit' => $rownum, 'page' => $page));
        $this->set('wb_treasure', $wb_treasure);
        $this->set('wb_relog_count', $wb_relog_count);
    }
    public function update()
    {
        $result = array();
        $content = $_REQUEST['treasure_content'];
        $treasure_img = empty($_REQUEST['treasure_img']) ? '' : $_REQUEST['treasure_img'];
        $product_link = 'http://'.$_SERVER['HTTP_HOST'].'/products/view/'.$_REQUEST['product_id'];
        if (isset($_REQUEST['link_status']) && $_REQUEST['link_status'] == 1) {
            $link_status = 1;
            $content = $content.$product_link;
            $content_status = $content.URLencode($product_link);
        } else {
            $link_status = 0;
            $content_status = $content;
        }
        if (isset($_REQUEST['vendor_status']) && $_REQUEST['vendor_status'] == 1) {
            $timing = $_REQUEST['release_date'].' '.$_REQUEST['release_hours'].':'.$_REQUEST['release_min'].':00';
            $now = time();
            if ($now >= strtotime($timing)) {
                $result['flag'] = 0;
                $result['msg'] = '定时发布时间设置错误，请设置为当前时间 5 分钟后的某个时间。';
            } else {
                $web_treasure = array(
                    'id' => '',
                    'user_id' => $this->admin['id'],
                    'upload_type' => 0,
                    'product_id' => $_REQUEST['product_id'],
                    'release_time' => $timing,
                    'interval_time' => $_REQUEST['interval_time'],
                    'content' => $content,
                    'img' => $treasure_img,
                    'product_link' => $product_link,
                    'link_status' => $link_status,
                    'status' => 0,
                );
                $this->WbmktTreasure->save($web_treasure);
                $result['flag'] = 1;
                $result['msg'] = '已成功加入发布队列';
            }
        } else {
            $SaeTOAuthV2 = $this->saetoauthv2();
            $url = 'statuses/update';
            $parameters = array();
            $parameters['status'] = $content_status;//要发布的微博文本内容，必须做URLencode，内容不超过140个汉字。
            $wb_result = $SaeTOAuthV2->post($url, $parameters);
            if (isset($wb_result['id']) && isset($wb_result['user'])) {
                $result['flag'] = 1;
                $result['msg'] = '发布成功';
                $web_treasure = array(
                    'id' => '',
                    'user_id' => $this->admin['id'],
                    'upload_type' => 1,
                    'product_id' => $_REQUEST['product_id'],
                    'release_time' => '',
                    'interval_time' => $_REQUEST['interval_time'],
                    'content' => $content,
                    'img' => $treasure_img,
                    'product_link' => $product_link,
                    'link_status' => $link_status,
                    'status' => 1,
                );
            } else {
                $result['flag'] = 0;
                $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
                $result['msg'] = '发布失败:'.$msg;
                $web_treasure = array(
                    'id' => '',
                    'user_id' => $this->admin['id'],
                    'upload_type' => 1,
                    'product_id' => $_REQUEST['product_id'],
                    'release_time' => '',
                    'interval_time' => $_REQUEST['interval_time'],
                    'content' => $content,
                    'img' => $treasure_img,
                    'product_link' => $product_link,
                    'link_status' => $link_status,
                    'status' => 2,
                );
            }
            $this->WbmktTreasure->save($web_treasure);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
//		$this->redirect("/wbmkt_treasures/");
    }
    public function saetoauthv2()
    {
        $shop_oper = $this->SynchroOperator->find('first', array('conditions' => array('SynchroOperator.status' => 1)));
        $app_key = $shop_oper['SynchroOperator']['app_key'];
        $app_secret = $shop_oper['SynchroOperator']['app_secret'];
        $access_token = $shop_oper['SynchroOperator']['access_token'];
        $SaeTOAuthV2 = new SaeTOAuthV2($app_key, $app_secret, $access_token);

        return $SaeTOAuthV2;
    }
}
