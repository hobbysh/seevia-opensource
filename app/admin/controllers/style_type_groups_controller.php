<?php

/**
 *这是一个名为 StyleTypeGroupsController 的控制器.
 *
 *@var
 *@var
 *@ 版型规格管理 hechang 2015/01/19
 */
class StyleTypeGroupsController extends AppController
{
    public $name = 'StyleTypeGroups';
    public $uses = array('CategoryType','CategoryTypeI18n','CategoryTypeRelation','OperatorLog','ProductStyle','ProductStyleI18n','StyleTypeGroup','ProductType','ProductTypeI18n','ProductTypeAttribute','Attribute','StyleTypeGroupAttributeValue');

    public function view($id = 0)
    {
        if ($id == 0) {
            $this->operator_privilege('category_types_add');
            if (isset($_REQUEST['style_id']) && $_REQUEST['style_id'] != 0) {
                $this->set('style_id', $_REQUEST['style_id']);
            }
        } else {
            $this->operator_privilege('category_types_edit');
            $style_type_group = $this->StyleTypeGroup->find('first', array('conditions' => array('StyleTypeGroup.id' => $id)));
            if (isset($style_type_group['StyleTypeGroup']['style_id']) && $style_type_group['StyleTypeGroup']['style_id'] != 0) {
                $this->set('style_id', $style_type_group['StyleTypeGroup']['style_id']);
            }
            $this->set('style_type_group', $style_type_group);
            //商品版型规格尺寸
            $style_type_group_attr = $this->StyleTypeGroupAttributeValue->find('all', array('conditions' => array('StyleTypeGroupAttributeValue.style_id' => $style_type_group['StyleTypeGroup']['style_id'], 'StyleTypeGroupAttributeValue.type_id' => $style_type_group['StyleTypeGroup']['type_id'], 'StyleTypeGroupAttributeValue.style_type_group_id' => $id)));
            $this->set('style_type_group_attr', $style_type_group_attr);
            //pr($style_type_group_attr);
            $default_style_attr = array();
            foreach ($style_type_group_attr as $sk => $sv) {
                array_push($default_style_attr, $sv['StyleTypeGroupAttributeValue']['attribute_id']);
            }
            $this->set('default_style_attr', $default_style_attr);

            $this->Attribute->set_locale($this->backend_locale);
            $attr_ids = $this->ProductTypeAttribute->getattrids($style_type_group['StyleTypeGroup']['type_id']);
            $attr_group = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids, 'Attribute.status' => 1, 'Attribute.attr_type' => 2, 'AttributeI18n.locale' => $this->backend_locale), 'fields' => 'Attribute.id,Attribute.code,AttributeI18n.name,AttributeI18n.default_value', 'orderby' => 'Attribute.id'));
            $this->set('attr_group', $attr_group);
            //pr($attr_group);
        }

        //版型下拉
        $this->ProductStyle->set_locale($this->backend_locale);
        $style_list = $this->ProductStyle->find('all', array('conditions' => array('ProductStyle.status' => 1)));
        $this->set('style_list', $style_list);
        //属性组下拉
        $this->ProductType->set_locale($this->backend_locale);
        $type_list = $this->ProductType->find('all', array('conditions' => array('ProductType.id !=' => 0, 'ProductType.status' => 1)));
        $this->set('type_list', $type_list);

        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->set('title_for_layout', $this->ld['style_manager'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['style_manager'],'url' => '/product_styles/');
        $this->navigations[] = array('name' => '版型规格','url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->StyleTypeGroup->saveAll($this->data['StyleTypeGroup']);
            //pr($this->data['StyleTypeGroup']);die;
            $id = $this->StyleTypeGroup->id;
            /*保存商品版型规格尺寸*/
            if (isset($this->data['StyleTypeGroupAttributeValue']) && sizeof($this->data['StyleTypeGroupAttributeValue']) > 0) {
                //删除原有关联属性
                $this->StyleTypeGroupAttributeValue->deleteAll(array('StyleTypeGroupAttributeValue.style_id' => $this->data['StyleTypeGroup']['style_id'], 'StyleTypeGroupAttributeValue.type_id' => $this->data['StyleTypeGroup']['type_id'], 'StyleTypeGroupAttributeValue.style_type_group_id' => $id));
                //pr($this->data);die;
                foreach ($this->data['StyleTypeGroupAttributeValue'] as $k => $v) {
                    $attr_data = array();
                    $attr_data['StyleTypeGroupAttributeValue']['style_id'] = $this->data['StyleTypeGroup']['style_id'];
                    $attr_data['StyleTypeGroupAttributeValue']['type_id'] = $this->data['StyleTypeGroup']['type_id'];
                    $attr_data['StyleTypeGroupAttributeValue']['style_type_group_id'] = $id;
                    $attr_data['StyleTypeGroupAttributeValue']['attribute_id'] = $v['attribute_id'];
                    $attr_data['StyleTypeGroupAttributeValue']['attribute_code'] = $v['attribute_code'];
                    $attr_data['StyleTypeGroupAttributeValue']['default_value'] = $v['default_value'];
                    $attr_data['StyleTypeGroupAttributeValue']['select_value'] = $v['select_value'];
                    $this->StyleTypeGroupAttributeValue->saveAll($attr_data);
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑版型规格:id '.$id.' ', $this->admin['id']);
            }
            $this->redirect('/product_styles/view/'.$this->data['StyleTypeGroup']['style_id']);
        }
    }
    public function show_attribute()
    {
        $result['flag'] = 0;
        if ($this->RequestHandler->isPost()) {
            $type_id = $_POST['type_id'];
            if (!empty($type_id)) {
                $result['flag'] = 1;
                $this->Attribute->set_locale($this->backend_locale);
                $attr_ids = $this->ProductTypeAttribute->getattrids($type_id);
                $result['type_list'] = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids, 'Attribute.status' => 1, 'Attribute.attr_type' => 2, 'AttributeI18n.locale' => $this->backend_locale), 'fields' => 'Attribute.id,Attribute.code,AttributeI18n.name,AttributeI18n.default_value', 'orderby' => 'Attribute.id'));
            }
            die(json_encode($result));
        }
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
            $result['na'] = '<label><input  type="radio" name="orderby" value="0"/>'.$this->ld['front'].'</label> <label><input  checked type="radio" name="orderby" value="1"　 />'.$this->ld['final'].'</label> <label><input type="radio" name="orderby" value="2"/>'.$this->ld['at'].'</label><select id="orderby" name="orderby_sel">';
            foreach ($na_info as $v) {
                $result['na'] .= '<option value="'.$v['CategoryType']['id'].'">'.$v['CategoryTypeI18n']['name'].'</option>';
            }
            $result['na'] .= '</select>'.$this->ld['after'];
        } else {
            $result['na'] = $this->ld['no_lower_navigation'];
        }
        $result['message'] = $this->ld['modified_successfully'];
        $result['flag'] = 1;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //删除
    public function remove($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if (!$this->operator_privilege('category_types_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_product_category_failure'];
        $this->StyleTypeGroup->deleteAll(array('StyleTypeGroup.id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'版型删除:id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = '删除成功';
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
        Configure::write('debug', 0);
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
            $this->CategoryType->set_locale($this->backend_locale);
            $category_type_tree = $this->CategoryType->tree();
            $result['cattype'] = '<select name="data[Product][category_type_id]" id="product_category_type_id">';
            $result['cattype'] .= '<option value="0">'.$this->ld['select_categories'].'</option>';
            if (isset($category_type_tree) && sizeof($category_type_tree) > 0) {
                foreach ($category_type_tree as $first_k => $first_v) {
                    if ($first_v['CategoryType']['id'] == $id) {
                        $quick_cattype_name = $first_v['CategoryTypeI18n']['name'];
                        $result['cattype'] .= '<option value="'.$first_v['CategoryType']['id'].'" selected>'.$first_v['CategoryTypeI18n']['name'].'</option>';
                    } else {
                        $result['cattype'] .= '<option value="'.$first_v['CategoryType']['id'].'">'.$first_v['CategoryTypeI18n']['name'].'</option>';
                    }
                }
            }
            $result['cattype'] .= '</select><input type="button" onclick="popOpen(\'productcattype\');" value="'.$this->ld['quick_add_category_type'].'" />';
            //操作员日志
 //           if(isset($this->configs['open_OperatorLog']) && $this->configs['open_OperatorLog'] == 1){
//				$this->OperatorLog->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['quick_add_category_type'].':'.$quick_cattype_name,$this->admin['id']);
//			}
        } else {
            $result['message'] = $this->ld['complete_failure'];
            $result['flag'] = 2;
        }

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
