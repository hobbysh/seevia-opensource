<?php

/*****************************************************************************
 * Seevia 轮播管理
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
class FlashsController extends AppController
{
    public $name = 'Flashs';
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler');
    public $uses = array('Flashe','FlashImage','Resource','Brand','PageModule','InformationResource','OperatorLog','CategoryProduct','CategoryArticle');

    public function index($page = 1)
    {
        $this->operator_privilege('flashs_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/cms/','sub' => '/flashs/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['circle_image'],'url' => '');
        $condition = '';
        $condition['type'] = 0;//默认电脑页面
        $condition['page'] = 'H';//默认首页
        $condition['page_id'] = 0;//默认首页
        $flashType = '0';
        $flashPage = 'H';
        $page_type_id = '0';
        $language = '';
        if (isset($this->params['url']['type']) && $this->params['url']['type'] != '') {
            $flashType = $this->params['url']['type'];
            $condition['type'] = $flashType;
        }
        if (isset($this->params['url']['flashPage']) && $this->params['url']['flashPage'] != '') {
            $flashPage = $this->params['url']['flashPage'];
            $condition['page'] = $flashPage;
        }
        if (isset($this->params['url']['page_type_id']) && $this->params['url']['page_type_id'] != '') {
            $page_type_id = $this->params['url']['page_type_id'];
            $condition['page_id'] = $page_type_id;
        }
        //获取参数表数据
        $flash_info = $this->Flashe->find('first', array('conditions' => $condition));
        $flash_id = isset($flash_info['Flashe']['id']) ? $flash_info['Flashe']['id'] : '';//flash参数表ID
        $this->set('flash_type', $condition['type']);
        $this->set('flashPage', $condition['page']);
        $this->set('page_id', $condition['page_id']);
        $condition = '';
        $condition['flash_id'] = $flash_id;
        if (isset($this->params['url']['language']) && $this->params['url']['language'] != '') {
            $language = $this->params['url']['language'];
            $condition['locale'] = $language;
        }
        $total = $this->FlashImage->find('count', array('conditions' => $condition));//统计全部轮播总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'FlashImage';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'flashs','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'FlashImage');
        $this->Pagination->init($condition, $parameters, $options);
        //pr($this->Pagination->init($condition,$parameters,$options));
        $fields = array('id','locale','url','orderby','status','image');
        $flash_image_data = $this->FlashImage->find('all', array('conditions' => $condition, 'order' => 'orderby asc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));
        $this->set('flash_image_data', $flash_image_data);
        $this->set('flash_info', $flash_info);
        $this->set('flashType', $flashType);
        $this->set('page_type_id', $page_type_id);
        $this->set('language', $language);
        //商品分类树
        $category_tree_p = $this->CategoryProduct->tree('P', $this->locale);
        $this->set('category_tree_p', $category_tree_p);
        //文章分类树
        $category_tree_a = $this->CategoryArticle->tree('all', $this->locale);
        $this->set('category_tree_a', $category_tree_a);
        //品牌获取
        $brand_tree = $this->Brand->brand_tree($this->locale);
        $this->set('brand_tree', $brand_tree);
        //自定义类型获取
        $informationresource_info = $this->InformationResource->information_formated(array('flash_custom_type'), $this->locale);
        if (!empty($informationresource_info)) {
            $this->set('custom_tree', $informationresource_info['flash_custom_type']);
        }
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('flashtypes'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        //查询所有的目的地
        if (in_array('APP-TRAVEL', $this->apps['codes'])) {
            $this->loadModel('TravelDestination');
            $destination_infos = $this->TravelDestination->find('list', array('fields' => 'TravelDestination.id,TravelDestination.name'));
            $this->set('destination_infos', $destination_infos);
        }
        $this->set('title_for_layout', $this->ld['circle_image'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($id = '')
    {
        if (empty($id)) {
            $this->operator_privilege('flashs_add');
        } else {
            $this->operator_privilege('flashs_edit');
        }
        $this->menu_path = array('root' => '/cms/','sub' => '/flashs/');
        if (isset($_GET['type']) && $_GET['type'] != '') {
            $flashType = $_GET['type'];
            $this->set('flashType', $flashType);
        }
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
            $this->set('page', $page);
        }
        $this->set('title_for_layout', $this->ld['flashs_edit'].'-'.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['circle_image_list'],'url' => '/flashs/');
        if ($this->RequestHandler->isPost()) {
            $this->data['FlashImage']['orderby'] = !empty($this->data['FlashImage']['orderby']) ? $this->data['FlashImage']['orderby'] : '50';
            if ($this->data['Flash']['page'] == 'H') {
                $this->data['Flash']['page_id'] = 0;
            }
            $flash_data = $this->Flashe->find('first', array('conditions' => $this->data['Flash'], 'fields' => array('id')));
          
            if (!isset($flash_data['Flashe']['id'])) {
                $this->Flashe->save($this->data['Flash']);
                $flash_id = $this->Flashe->id;
            } else {
                $flash_id = $flash_data['Flashe']['id'];
            }
            $this->data['FlashImage']['flash_id'] = $flash_id;
            if (isset($this->data['FlashImage']['id']) && $this->data['FlashImage']['id'] != '') {
                $this->FlashImage->save(array('FlashImage' => $this->data['FlashImage'])); //关联保存
            } else {
                $this->FlashImage->saveAll(array('FlashImage' => $this->data['FlashImage'])); //关联保存
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['circle_image_list'].':id '.$id, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
            //$this->redirect('/flashs/?type='.$this->data["Flash"]["type"].'&page='.$this->data["Flash"]["page"].'&page_type_id='.$this->data["Flash"]["page"]);
        }
        //详细页内容
        $flash_image_info = $this->FlashImage->find('first', array('conditions' => array('id' => $id))); 
         //pr($flash_image_info); 
        $this->set('flash_image_info', $flash_image_info);
        $flash_info = $this->Flashe->find('first', array('conditions' => array('id' => $flash_image_info['FlashImage']['flash_id'])));
        if (isset($flash_image_info['FlashImage']['title'])) {
            $this->navigations[] = array('name' => $flash_image_info['FlashImage']['title'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_carousel'],'url' => '');
        }
        $this->set('flash_info', $flash_info);
        //商品分类树
        $category_tree_p = $this->CategoryProduct->tree('P', $this->locale);
        $this->set('category_tree_p', $category_tree_p);
        //文章分类树
        $category_tree_a = $this->CategoryArticle->tree('all', $this->locale);
        $this->set('category_tree_a', $category_tree_a);
        //品牌获取
        $brand_tree = $this->Brand->brand_tree($this->locale);
        $this->set('brand_tree', $brand_tree);
        //自定义类型获取
        $informationresource_info = $this->InformationResource->information_formated(array('flash_custom_type'), $this->locale);
        if (!empty($informationresource_info)) {
            $this->set('custom_tree', $informationresource_info['flash_custom_type']);
        }
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('flashtypes'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        //查询所有的目的地
        if (in_array('APP-TRAVEL', $this->apps['codes'])) {
            $this->loadModel('TravelDestination');
            $destination_infos = $this->TravelDestination->find('list', array('fields' => 'TravelDestination.id,TravelDestination.name'));
            $this->set('destination_infos', $destination_infos);
        }
    }

    public function type_edit($type, $type_id)
    {
        $this->operator_privilege('flashs_edit');
        $this->set('title_for_layout', $this->ld['circle_image'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['circle_image'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['circle_image'],'url' => '/flashs/');
        $this->navigations[] = array('name' => $this->ld['circle_image_parameters'],'url' => '');
        if ($this->RequestHandler->isPost()) {
            $this->Flashe->save($this->data);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['circle_image_parameters'], $this->admin['id']);
            }
            $this->redirect('/flashs/');
        }
        $flash_info = $this->Flashe->find('first', array('conditions' => array('type' => $type, 'type_id' => $type_id)));
        $this->set('flash_info', $flash_info);
        $this->set('type', $type);
        $this->set('type_id', $type_id);
    }

    /**
     *删除一个falsh.
     *
     *@param int $id 输入falshID
     */
    public function remove($id)
    {
        $this->operator_privilege('flashs_remove');
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_falsh_failure'];
        //获取该轮播的flash_id
        $flash_id = $this->FlashImage->find('first', array('conditions' => array('FlashImage.id' => $id), 'fields' => 'FlashImage.flash_id'));
        $check_flashes = $this->FlashImage->find('all', array('conditions' => array('FlashImage.flash_id' => $flash_id['FlashImage']['flash_id']), 'fields' => 'FlashImage.id'));
        //pr($check_flashes);die;
        $this->FlashImage->deleteAll(array('id' => $id));
        if (!(sizeof($check_flashes) > 1)) {
            $this->Flashe->deleteAll(array('id' => $flash_id['FlashImage']['flash_id']));
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_falsh_success'];
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_imgae_circle'].':id '.$id, $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //列表箭头排序
    public function changeorder($updowm, $id)
    {
        //如果值相等重新自动排序
        $a = $this->FlashImage->query('SELECT DISTINCT `flash_id`
			FROM `svcms_flash_images` as FlashImage
			GROUP BY `orderby` , `flash_id`
			HAVING count( * ) >1');
        if (!empty($a)) {
            foreach ($a as $v) {
                $all = $this->FlashImage->find('all', array('conditions' => array('FlashImage.flash_id' => $v['FlashImage']['flash_id']), 'order' => 'FlashImage.id asc'));
                foreach ($all as $k => $vv) {
                    $all[$k]['FlashImage']['orderby'] = $k + 1;
                }
                $this->FlashImage->saveAll($all);
            }
        }
        $flashimage_one = $this->FlashImage->find('first', array('conditions' => array('FlashImage.id' => $id)));
        if ($updowm == 'up') {
            $flashimage_change = $this->FlashImage->find('first', array('conditions' => array('FlashImage.orderby <' => $flashimage_one['FlashImage']['orderby'], 'FlashImage.flash_id' => $flashimage_one['FlashImage']['flash_id']), 'order' => 'orderby desc', 'limit' => '1'));
        }
        if ($updowm == 'down') {
            $flashimage_change = $this->FlashImage->find('first', array('conditions' => array('FlashImage.orderby >' => $flashimage_one['FlashImage']['orderby'], 'FlashImage.flash_id' => $flashimage_one['FlashImage']['flash_id']), 'order' => 'orderby asc', 'limit' => '1'));
        }
        $t = $flashimage_one['FlashImage']['orderby'];
        $flashimage_one['FlashImage']['orderby'] = $flashimage_change['FlashImage']['orderby'];
        $flashimage_change['FlashImage']['orderby'] = $t;
        $this->FlashImage->saveAll($flashimage_one);
        $this->FlashImage->saveAll($flashimage_change);
        $Resource_info = $this->Resource->getformatcode(array('flashtypes'), $this->locale);
        $this->set('Resource_info', $Resource_info);
        $flash_image_data = $this->FlashImage->find('all', array('conditions' => array('FlashImage.flash_id' => $flashimage_one['FlashImage']['flash_id']), 'order' => 'orderby asc'));
        $this->set('flash_image_data', $flash_image_data);
        $flash_info = $this->Flashe->find('first', array('conditions' => array('Flashe.id' => $flashimage_one['FlashImage']['flash_id'])));
        $this->set('flash_info', $flash_info);
        Configure::write('debug', 1);
        $this->layout = 'ajax';
    }

    //轮播JS有效切换
    public function toggle_on_status()
    {
        $this->operator_privilege('flashs_edit');
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $flash_id = $this->FlashImage->find('first', array('fields' => array('FlashImage.flash_id'), 'conditions' => array('FlashImage.id' => $id)));
        $flash_info = $this->Flashe->find('first', array('conditions' => array('Flashe.id' => $flash_id['FlashImage']['flash_id'])));
        $flash_type = $flash_info['Flashe']['type'] == '0' ? '电脑页面' : '手机页面';
        //轮播资源信息
        $Resource_info = $this->Resource->getformatcode(array('flashtypes'), $this->locale);
        $flash_page = @$Resource_info['flashtypes'][$flash_info['Flashe']['page']];
        $FlashImage_info = array('id' => $id,'status' => $val,'modified' => date('Y-m-d H:i:s'));
        $result = array();
        if (is_numeric($val) && $this->FlashImage->save($FlashImage_info)) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑'.$flash_type.$flash_page.'轮播有效性'.'.'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /*
   *批量删除
   */
    public function batch_operations()
    {
        $brand_id = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        pr($_REQUEST);

        if ($brand_id != 0) {
            $condition['Flashe.id'] = $brand_id;
            $this->Flashe->deleteAll($condition);
            $this->FlashImage->deleteAll(array('FlashImage.id' => $brand_id));
            $result['flag'] = 1;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die();
    }
}
