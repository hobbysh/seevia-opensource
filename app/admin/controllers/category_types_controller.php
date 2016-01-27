<?php

/**
 *这是一个名为 CategoryTitlesController 的控制器.
 *
 *@var
 *@var
 *@ 类目管理 chenfan 2012/02/17
 */
class CategoryTypesController extends AppController
{
    public $name = 'CategoryTypes';
    public $uses = array('CategoryType','CategoryTypeI18n','Brand','BrandI18n','CategoryTypeRelation','OperatorLog');

    public function index()
    {
        $this->operator_privilege('category_types_view');
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->set('title_for_layout', $this->ld['category_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['category_management'],'url' => '');
        $this->CategoryType->set_locale($this->backend_locale);
        $category_typies_tree = $this->CategoryType->tree();
        $this->set('category_types_tree', $category_typies_tree);
    }

    public function view($id = 0)
    {
        if ($id == 0) {
            $this->operator_privilege('category_types_add');
        } else {
            $this->operator_privilege('category_types_edit');
        }
        $this->set('title_for_layout', $this->ld['catalogue_editor'].' - '.$this->configs['shop_name']);
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->set('title_for_layout', $this->ld['category_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['category_management'],'url' => '/category_types/');
        $this->navigations[] = array('name' => $this->ld['catalogue_editor'],'url' => '');
        $this->Brand->set_locale($this->backend_locale);
        $bran_sel = $this->Brand->find('all', array('fields' => array('Brand.id', 'Brand.code', 'BrandI18n.name')));
        $brands = isset($this->params['url']['b']) ? $this->params['url']['b'] : array();
        $this->set('bran_sel', $bran_sel);
        $this->set('brand', $brands);
        $brand_name_list = array();
        $brand_code_list = array();
        foreach ($bran_sel as $k => $v) {
            $brand_name_list[$v['Brand']['id']] = $v['BrandI18n']['name'];
            $brand_code_list[$v['Brand']['id']] = $v['Brand']['code'];
        }
        $this->set('brand_name_list', $brand_name_list);
        $this->set('brand_code_list', $brand_code_list);
        $brand_relation = $this->CategoryTypeRelation->find('all', array('conditions' => array('category_type_id' => $id), 'order' => 'modified desc'));
        $this->set('brand_relation', $brand_relation);
        if ($this->RequestHandler->isPost()) {
            $parent_id = $this->data['CategoryType']['parent_id'];
            if (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 0) {
                $category_first = $this->CategoryType->find('first', array('conditions' => array('CategoryType.parent_id' => $parent_id), 'order' => 'orderby asc', 'limit' => '1'));
                $this->data['CategoryType']['orderby'] = $category_first['CategoryType']['orderby'];
                // 取出所有导航的 序值加1
                $ca_all = $this->CategoryType->find('all');
                foreach ($ca_all as $k => $v) {
                    $ca_all[$k]['CategoryType']['orderby'] = $v['CategoryType']['orderby'] + 1;
                }
                $this->CategoryType->saveAll($ca_all);
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 1) {
                $category_last = $this->CategoryType->find('first', array('conditions' => array('CategoryType.parent_id' => $parent_id), 'order' => 'orderby desc', 'limit' => '1'));
                $this->data['CategoryType']['orderby'] = $category_last['CategoryType']['orderby'] + 1;
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 2) {
                $category_change = $this->CategoryType->find('first', array('conditions' => array('CategoryType.id' => $_REQUEST['orderby_sel'])));
                $this->data['CategoryType']['orderby'] = $category_change['CategoryType']['orderby'] + 1;
                $ca_all = '';
                $ca_all = $this->CategoryType->find('all', array('conditions' => array('CategoryType.orderby >' => $category_change['CategoryType']['orderby'])));
                foreach ($ca_all as $k => $v) {
                    $ca_all[$k]['CategoryType']['orderby'] = $v['CategoryType']['orderby'] + 1;
                }
                $this->CategoryType->saveAll($ca_all);
            } else {
                if (!isset($this->data['CategoryType']['id']) && $this->data['CategoryType']['id'] == '') {
                    $this->data['CategoryType']['orderby'] = 1;
                }
            }
            $this->CategoryType->saveAll($this->data['CategoryType']);
            $id = $this->CategoryType->id;
            $this->CategoryTypeI18n->deleteAll(array('CategoryTypeI18n.category_type_id' => $this->CategoryType->id));
            $CategoryTypeName = '';
            foreach ($this->data['CategoryTypeI18n'] as $v) {
                if ($v['locale'] == $this->backend_locale) {
                    $CategoryTypeName = isset($v['name']) ? $v['name'] : '';
                }
                $CategoryTypeI18n_info = array(
                      'locale' => $v['locale'],
                      'category_type_id' => $id,
                       'name' => isset($v['name']) ? $v['name'] : '',
                       'description' => isset($v['description']) ? $v['description'] : '',
                  );
                $this->CategoryTypeI18n->saveAll(array('CategoryTypeI18n' => $CategoryTypeI18n_info));//更新多语言
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑类目:id '.$id.' '.$CategoryTypeName, $this->admin['id']);
            }
            $this->redirect('/category_types/');
        }
        if ($id != 0) {
            $typeInfo = $this->CategoryType->localeformat($id);
            $this->set('typeInfo', $typeInfo);
        }
        $this->CategoryType->set_locale($this->backend_locale);
        $category_typies_tree = $this->CategoryType->tree();
        $this->set('category_types_tree', $category_typies_tree);
        //pr($category_typies_tree);
    }
    //列表箭头排序
    public function changeorder($updowm, $id, $nextone)
    {
        //如果值相等重新自动排序
        $a = $this->CategoryType->query('SELECT DISTINCT `parent_id`
			FROM `svoms_category_types` as CategoryType
			GROUP BY `orderby` , `parent_id`
			HAVING count( * ) >1');
        foreach ($a as $v) {
            $all = $this->CategoryType->find('all', array('conditions' => array('CategoryType.parent_id' => $v['CategoryType']['parent_id']), 'order' => 'CategoryType.id asc'));
            foreach ($all as $k => $vv) {
                $all[$k]['CategoryType']['orderby'] = $k + 1;
            }
            $this->CategoryType->saveAll($all);
        }
        if ($nextone == 0) {
            $category_one = $this->CategoryType->find('first', array('conditions' => array('CategoryType.id' => $id)));
            if ($updowm == 'up') {
                $category_change = $this->CategoryType->find('first', array('conditions' => array('CategoryType.orderby <' => $category_one['CategoryType']['orderby'], 'CategoryType.parent_id' => 0), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $category_change = $this->CategoryType->find('first', array('conditions' => array('CategoryType.orderby >' => $category_one['CategoryType']['orderby'], 'CategoryType.parent_id' => 0), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        if ($nextone == 'next') {
            $category_one = $this->CategoryType->find('first', array('conditions' => array('CategoryType.id' => $id)));
            if ($updowm == 'up') {
                $category_change = $this->CategoryType->find('first', array('conditions' => array('CategoryType.orderby <' => $category_one['CategoryType']['orderby'], 'CategoryType.parent_id' => $category_one['CategoryType']['parent_id']), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $category_change = $this->CategoryType->find('first', array('conditions' => array('CategoryType.orderby >' => $category_one['CategoryType']['orderby'], 'CategoryType.parent_id' => $category_one['CategoryType']['parent_id']), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        $t = $category_one['CategoryType']['orderby'];
        $category_one['CategoryType']['orderby'] = $category_change['CategoryType']['orderby'];
        $category_change['CategoryType']['orderby'] = $t;
        if (isset($category_change['CategoryType']['status'])) {
            $this->CategoryType->saveAll($category_one);
            $this->CategoryType->saveAll($category_change);
        }
        $this->CategoryType->set_locale($this->backend_locale);
        $category_types_tree = $this->CategoryType->tree();
        $this->set('category_types_tree', $category_types_tree);
        Configure::write('debug', 1);
        $this->render('index');
        $this->layout = 'ajax';
    }

    public function searchtypes($id)
    {
        $this->CategoryType->set_locale($this->backend_locale);
        if ($id != 0) {
            $na_one = $this->CategoryType->find('first', array('conditions' => array('CategoryType.id' => $id)));
            $na_info = $this->CategoryType->find('all', array('conditions' => array('CategoryType.parent_id' => $na_one['CategoryType']['id'])));
        } else {
            $na_info = $this->CategoryType->find('all', array('conditions' => array('CategoryType.parent_id' => $id)));
        }
        if (isset($na_info) && count($na_info) > 0) {
            $result['flag'] = 1;
            $orderby_data = array();
            foreach ($na_info as $v) {
                $orderby_data[] = array(
                    'id' => $v['CategoryType']['id'],
                    'value' => $v['CategoryTypeI18n']['name'],
                );
            }
            $result['na'] = $orderby_data;
        } else {
            $result['flag'] = 2;
            $result['na'] = $this->ld['no_lower_navigation'];
        }
        $result['message'] = $this->ld['modified_successfully'];
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //删除
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if (!$this->operator_privilege('category_types_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_product_category_failure'];
        $this->CategoryType->deleteAll(array('CategoryType.id' => $id));
        $this->CategoryTypeI18n->deleteAll(array('CategoryTypeI18n.category_type_id' => $id));
        $category_data = $this->CategoryType->find('all', array('conditions' => array('parent_id' => $id), 'fields' => 'id'));
        if (!empty($category_data)) {
            foreach ($category_data as $k => $v) {
                $this->CategoryType->save(array('CategoryType' => array('id' => $v['CategoryType']['id'], 'parent_id' => 0)));
            }
        }
        //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'类目删除:id '.$id, $this->admin['id']);
            }
        $result['flag'] = 1;
        $result['message'] = '删除成功';
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //商品类目批量删除
    public function batch()
    {
        $this->operator_privilege('category_types_remove');
        $ct_ids = !empty($_REQUEST['checkbox']) ? $_REQUEST['checkbox'] : 0;
        if (sizeof($ct_ids) > 0) {
            $condition['CategoryType.id'] = $ct_ids;
            $this->CategoryType->deleteAll($condition);
            $this->CategoryTypeI18n->deleteAll(array('category_type_id' => $ct_ids));
            $chid_ids = $this->CategoryType->find('list', array('conditions' => array('CategoryType.parent_id' => $ct_ids)));
            $this->CategoryType->deleteAll(array('CategoryType.id' => $chid_ids));
            $this->CategoryTypeI18n->deleteAll(array('category_type_id' => $chid_ids));
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
            }
        }
        $this->redirect('/category_types/');
    }

    //搜品牌
    public function searchBrands()
    {
        $condition = '';
    //	$brand_ids = trim(',',$_POST['brand_id']);
        $keyword = $_POST['brand_keyword'];
        $this->Brand->set_locale($this->backend_locale);
        $brand_ids = $this->BrandI18n->find('list', array('conditions' => array('BrandI18n.name like' => "%$keyword%"), 'fields' => array('BrandI18n.brand_id')));

        if (!empty($brand_ids)) {
            $condition['Brand.id'] = $brand_ids;
        }
        if ($keyword != '') {
            $condition['Brand.code like'] = "%$keyword%";
        }
        $brands = $this->Brand->find('all', array('conditions' => $condition, 'fields' => array('Brand.id', 'Brand.code', 'BrandI18n.name')));
        if (!empty($brands)) {
            $result['code'] = 1;
            $result['content'] = $brands;
            die(json_encode($result));
        } else {
            $result['code'] = 2;
            $result['content'] = '没有搜索到品牌';
            die(json_encode($result));
        }
    }

    public function add_brand_relation_categorytype()
    {

        //设置返回初始参数
        $product_select = array();
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['unknown_reasons'];
        $ct_id = $_REQUEST['product_id'];
        $product_select = explode(',', $_REQUEST['product_select']);
        $is_single_value = $_REQUEST['is_single_value'];
        $this->Brand->set_locale($this->backend_locale);
        $content_array = array();
        foreach ($product_select as $k => $v) {
            $category_type = $this->CategoryTypeRelation->find('first', array('conditions' => array('related_brand_id' => $v)));

            $brand = $this->Brand->find('first', array('conditions' => array('Brand.id' => $v)));
            if (!empty($category_type) && $category_type['CategoryTypeRelation']['category_type_id'] != $ct_id) {
                $this->CategoryTypeRelation->updateAll(array('category_type_id' => $ct_id), array('related_brand_id' => $v));
                $content_array[$k]['id'] = $category_type['CategoryTypeRelation']['id'];
                $content_array[$k]['brand_code'] = $brand['Brand']['code'];
                $content_array[$k]['brand_name'] = $brand['BrandI18n']['name'];
            } elseif (empty($category_type)) {
                $this->CategoryTypeRelation->saveAll(array('CategoryTypeRelation' => array('category_type_id' => $ct_id, 'related_brand_id' => $v)));
                $content_array[$k]['id'] = $this->CategoryTypeRelation->getLastInsertId();
                $content_array[$k]['brand_code'] = $brand['Brand']['code'];
                $content_array[$k]['brand_name'] = $brand['BrandI18n']['name'];
            }
        }

        $result['flag'] = 1;//2 失败 1成功
        $result['content'] = $content_array;

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *删除商品关联商品.
     *
     *@param int $id 商品ID
     *@param int $product_id 关联的商品ID
     */
    public function drop_product_relation_product()
    {
        $result['flag'] = 1;//2 失败 1成功
        $result['content'] = $this->ld['unknown_reasons'];
        $this->CategoryTypeRelation->deleteAll(array('CategoryTypeRelation.id' => $_POST['ct_id']));
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //快速添加类目
    public function doinsertcattype()
    {
        $this->data1['CategoryType']['id'] = '';
        $this->data1['CategoryType']['parent_id'] = '0';
        $this->data1['CategoryType']['status'] = '1';
        $this->data1['CategoryType']['code'] = isset($_POST['CattypeCode']) ? $_POST['CattypeCode'] : '';
        $a = $this->CategoryType->saveAll($this->data1); //关联保存
        $id = $this->CategoryType->getLastInsertId();
        $this->data2['CategoryTypeI18n']['locale'] = $this->backend_locale;
        $this->data2['CategoryTypeI18n']['category_type_id'] = $id;
        $this->data2['CategoryTypeI18n']['name'] = isset($_POST['CattypeName']) ? $_POST['CattypeName'] : '';
        $this->CategoryTypeI18n->saveAll($this->data2);

        if ($a) {
            $result['message'] = $this->ld['complete_success'];
            $result['flag'] = 1;
            $result['last_category_type'] = $id;
            $this->CategoryType->set_locale($this->backend_locale);
            $category_type_tree = $this->CategoryType->tree();
            $result['cattype'] = $category_type_tree;
            $result['select_categories'] = $this->ld['select_categories'];
            //操作员日志
 //           if(isset($this->configs['open_OperatorLog']) && $this->configs['open_OperatorLog'] == 1){
//				$this->OperatorLog->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['quick_add_category_type'].':'.$quick_cattype_name,$this->admin['id']);
//			}
        } else {
            $result['message'] = $this->ld['complete_failure'];
            $result['flag'] = 2;
        }

        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
