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
class WbmktSupersController extends AppController
{
    public $name = 'WbmktSupers';
    public $uses = array('CategoryProductI18n','WbmktSuper','WbmktModel','ProductVolume','Language','Operator','Application','Product','ProductI18n','CategoryProduct','Brand','ProductType','Profile','ProfileFiled','TopicProduct','ProductTypeAttribute','ProductRelation','ProductArticle','Article','ProductAttribute','ProductsCategory','PhotoCategory','PhotoCategoryGallery','ProductGallery','ProductGalleryI18n','InformationResource','InformationResourceI18n','Stock','CategoryType','ProductDownload','UploadFile','Tag','TagI18n','ProductTypeAttributeI18n','Config');

    public function index()
    {
        $this->navigations[] = array('name' => '微营销管理','url' => '');
        $this->navigations[] = array('name' => '超级微营销','url' => '/wbmkt_supers/');
        $this->set('title_for_layout', '超级微营销'.' - '.$this->configs['shop_name']);

        $WbmktSuper_list = $this->WbmktSuper->find('all');
        foreach ($WbmktSuper_list as $k => $v) {
            $condition['WbmktModel.super_id'] = $v['WbmktSuper']['id'];
            $model = $this->WbmktModel->find('all', array('conditions' => $condition, 'fields' => 'WbmktModel.content'));
            $model_count = $this->WbmktModel->find('count', array('conditions' => $condition, 'fields' => 'WbmktModel.content'));
            $WbmktSuper_list[$k]['WbmktSuper']['model'] = $model;
            $WbmktSuper_list[$k]['WbmktSuper']['model_count'] = $model_count;
            $wb_type = $v['WbmktSuper']['wb_type'];
            $condition_type['CategoryProductI18n.category_id'] = explode(',', $wb_type);
            $super_category = $this->CategoryProductI18n->find('all', array('conditions' => $condition_type, 'fields' => 'CategoryProductI18n.category_id,CategoryProductI18n.name'));
            $WbmktSuper_list[$k]['WbmktSuper']['type_name'] = $super_category;
        }
        $this->set('WbmktSuper_list', $WbmktSuper_list);
    }

    //宝贝类目
    public function ui_all_box()
    {
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
    }

    //修改状态
    public function toggle_on_status()
    {
        $this->WbmktSuper->hasMany = array();
        $this->WbmktSuper->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->WbmktSuper->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function updata($id)
    {
        $this->data['WbmktSuper']['id'] = $id;
        $this->data['WbmktModel']['super_id'] = $id;
        if (!empty($_REQUEST['super_type'])) {
            $this->data['WbmktSuper']['wb_type'] = implode(',', $_REQUEST['super_type']);
        } else {
            $this->data['WbmktSuper']['wb_type'] = '';
        }
        if (empty($this->data['WbmktSuper']['discount'])) {
            $this->data['WbmktSuper']['discount'] = '0';
        }
        if (empty($this->data['WbmktSuper']['recommend'])) {
            $this->data['WbmktSuper']['recommend'] = '0';
        }
        if (empty($this->data['WbmktSuper']['shelves'])) {
            $this->data['WbmktSuper']['shelves'] = '0';
        }
        if (empty($this->data['WbmktSuper']['sales'])) {
            $this->data['WbmktSuper']['sales'] = '';
        }
        if (isset($this->data['WbmktSuper']['days_type']) && $this->data['WbmktSuper']['days_type'] == 'week') {
            $this->data['WbmktSuper']['days'] = implode(',', $_REQUEST['week']);
        }
        if (isset($this->data['WbmktSuper']['days_type']) && $this->data['WbmktSuper']['days_type'] == 'date') {
            $this->data['WbmktSuper']['days'] = $_REQUEST['start_date_time'].';'.$_REQUEST['end_date_time'];
        }
        $this->WbmktModel->deleteAll(array('super_id' => $id));
        if (isset($_REQUEST['model'])) {
            foreach ($_REQUEST['model'] as $v) {
                $this->data['WbmktModel']['content'] = $v;
                $this->WbmktModel->saveAll($this->data['WbmktModel']);
            }
        }
        $this->WbmktSuper->save($this->data['WbmktSuper']);
        $this->redirect('/wbmkt_supers/');
    }

    public function del($id)
    {
        $this->WbmktModel->deleteAll(array('super_id' => $id));
        $this->WbmktSuper->deleteAll(array('id' => $id));
        $this->redirect('/wbmkt_supers/');
    }

    public function add()
    {
        $this->data['WbmktSuper']['title'] = '我的微营销计划';
        $this->data['WbmktSuper']['status'] = '0';
        $this->data['WbmktSuper']['sales'] = '';
        $this->WbmktSuper->save($this->data['WbmktSuper']);
        $id = $this->WbmktSuper->getLastInsertId();
        $this->data['WbmktModel']['super_id'] = $id;
        $this->data['WbmktModel']['content'] = '#上新#，这款新品很不错，分享一下：[宝贝标题]， 价格：￥[宝贝价格]元，购买链接：[宝贝链接]，更多宝贝请看：[店铺链接]';
        $this->WbmktModel->saveAll($this->data['WbmktModel']);
        $this->redirect('/wbmkt_supers/');
    }
}
