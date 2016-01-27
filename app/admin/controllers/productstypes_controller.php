<?php

/*****************************************************************************
 * Seevia 属性管理
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
 *这是一个名为 ProductstypesController 的控制器
 *后台类型管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class ProductstypesController extends AppController
{
    public $name = 'Productstypes';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Html','Pagination','Ckeditor');
    public $uses = array('ProductType','ProductTypeI18n','ProductTypeAttribute','Resource','OperatorLog','Product','ProductAttribute','CategoryProduct','Attribute');

    /**
     *显示类型列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('productstypes_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        //$this->menu_path = array('root'=>'/product/','sub'=>'/productstypes/');
        $this->menu_path = array('root' => '/product/','sub' => '/attributes/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['attributes_manage'],'url' => '/attributes/');
        $this->navigations[] = array('name' => $this->ld['product_type_management'],'url' => '');
        $condition = '';
        $this->ProductType->set_locale($this->backend_locale);
        $page = 1;
        $total = $this->ProductType->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'productstypes','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'ProductType');
        $this->Pagination->init($condition, $parameters, $options);
        $productstype_list = $this->ProductType->find('all', array('page' => $page, 'limit' => $rownum, 'conditions' => $condition, 'order' => 'ProductType.orderby,ProductType.created,ProductType.id'));

        $product_type_id = array();
        foreach ($productstype_list as $k => $v) {
            $product_type_id[] = $v['ProductType']['id'];
        }
        $product_type_attribute_group = $this->ProductTypeAttribute->get_attr_count_array($product_type_id);//获取属性个数数组
        //数量的复值
        foreach ($productstype_list as $k => $v) {
            $productstype_list[$k]['ProductType']['num'] = empty($product_type_attribute_group[$v['ProductType']['id']]) ? 0 : $product_type_attribute_group[$v['ProductType']['id']];
        }

        $this->set('productstype_list', $productstype_list);
        $this->set('title_for_layout', $this->ld['product_type_management'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    /**
     *显示类型 新增/编辑.
     *
     *@param int $id 输入类型ID
     */
    public function view($id = '')
    {
        if (empty($id) || $id == '') {
            $this->operator_privilege('productstypes_add');
            $this->set('title_for_layout', $this->ld['add'].' - '.$this->ld['product_type_management'].' - '.$this->configs['shop_name']);
        } else {
            $this->operator_privilege('productstypes_edit');
            $this->set('title_for_layout', $this->ld['edit'].' - '.$this->ld['product_type_management'].' - '.$this->configs['shop_name']);
        }
        $this->set('id', $id);
        //$this->menu_path = array('root'=>'/product/','sub'=>'/productstypes/');
        $this->menu_path = array('root' => '/product/','sub' => '/attributes/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['attributes_manage'],'url' => '/attributes/');
        $this->navigations[] = array('name' => $this->ld['product_type_management'],'url' => '/productstypes/');

        if ($this->RequestHandler->isPost()) {
            if (isset($this->data['ProductType']) && $this->data['ProductType']['id'] != '0') {
                $this->ProductType->save(array('ProductType' => $this->data['ProductType']));
                $id = $this->ProductType->id;
                $this->ProductTypeI18n->deleteAll(array('type_id' => $id));//删除原始多语言
                //保存最新的多语言
                foreach ($this->data['ProductTypeI18n'] as $k => $v) {
                    $v['type_id'] = $id;
                    if ($v['locale'] == $this->locale) {
                        $userinformation_name = $v['name'];
                    }
                    $this->ProductTypeI18n->saveAll(array('ProductTypeI18n' => $v));
                }
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_edit_product_attribute'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
                }
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data = $this->ProductType->localeformat($id);
        //导航显示
        if (isset($this->data['ProductTypeI18n'][$this->locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$this->data['ProductTypeI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        }
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('property_type'), $this->locale);
        $this->set('Resource_info', $Resource_info);
        if (!empty($this->data)) {
            //当前属性组关联属性
            $attr_ids = $this->ProductTypeAttribute->find('list', array('fields' => array('ProductTypeAttribute.id', 'ProductTypeAttribute.attribute_id'), 'conditions' => array('ProductTypeAttribute.product_type_id' => $id), 'order' => 'ProductTypeAttribute.orderby,ProductTypeAttribute.id'));
            //pr($attr_ids);
            $all_attr_list = array();
            $all_attr_id = array();
            if (!empty($attr_ids)) {
                $this->Attribute->set_locale($this->backend_locale);
                $attr_infos = $this->Attribute->find('all', array('conditions' => array('Attribute.status' => 1, 'Attribute.id' => $attr_ids)));
//		        foreach($attr_infos as $v){
//		        	$all_attr_list[$v['Attribute']['id']]=$v['AttributeI18n']['name'];
//		        }
                foreach ($attr_ids as $av => $ak) {
                    $all_attr_id[$ak] = $av;
                    foreach ($attr_infos as $v) {
                        if ($v['Attribute']['id'] == $ak) {
                            $all_attr_list[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
                        }
                    }
                }
            }
            $this->set('all_attr_id', $all_attr_id);
            $this->set('all_attr_list', $all_attr_list);
        }
    }

    /**
     *属性列表状态修改.
     */
    public function toggle_on_typestatus()
    {
        $this->ProductType->hasMany = array();
        $this->ProductType->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->ProductType->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function add_associated_attributes($pro_type_id = '')
    {
        if ($this->RequestHandler->isPost()) {
            $result['flag'] = 0;
            if ($pro_type_id != '') {
                $attr_select_value = isset($_REQUEST['attr_select_value']) ? $_REQUEST['attr_select_value'] : '';
                $attr_select_arr = split(',', $attr_select_value);
                if (!empty($attr_select_arr)) {
                    $attr_ids = $this->ProductTypeAttribute->find('list', array('fields' => array('ProductTypeAttribute.attribute_id'), 'conditions' => array('ProductTypeAttribute.product_type_id' => $pro_type_id), 'order' => 'ProductTypeAttribute.id'));

                    foreach ($attr_select_arr as $v) {
                        if (!in_array($v, $attr_ids)) {
                            $this->ProductTypeAttribute->saveAll(array('attribute_id' => $v, 'product_type_id' => $pro_type_id));
                        }
                    }
                }
                $attribute_list = $this->ProductTypeAttribute->get_associated_attributes($pro_type_id, $this->backend_locale);
                if (!empty($attribute_list)) {
                    $result['flag'] = 1;
                    $result['content'] = $attribute_list;
                } else {
                    $result['content'] = $this->ld['not_product_attributes'];
                }
            } else {
                $result['content'] = $this->ld['no_products_type'];
            }
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            die(json_encode($result));
        } else {
            $this->redirect('/attributes/index');
        }
    }

    public function remove_associated_attributes($pro_type_id = '', $attr_id = 0)
    {
        $result['flag'] = 0;
        if ($pro_type_id != '') {
            $this->ProductTypeAttribute->deleteAll(array('attribute_id' => $attr_id, 'product_type_id' => $pro_type_id));
            $attribute_list = $this->ProductTypeAttribute->get_associated_attributes($pro_type_id, $this->backend_locale);
            if (!empty($attribute_list)) {
                $result['flag'] = 1;
                $result['content'] = $attribute_list;
            } else {
                $result['flag'] = 2;
                $result['content'] = $this->ld['not_product_attributes'];
            }
        } else {
            $result['content'] = $this->ld['no_products_type'];
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //快速添加属性
    public function doinsertattribute()
    {
        $this->data1['ProductType']['id'] = '';
        $this->data1['ProductType']['code'] = isset($_POST['attributetypeCode']) ? $_POST['attributetypeCode'] : '';
        $this->data1['ProductType']['group_code'] = isset($_POST['attributetypegroupCode']) ? $_POST['attributetypegroupCode'] : '';
        $this->ProductType->saveAll($this->data1); //关联保存
        $id = $this->ProductType->getLastInsertId();
        $this->ProductTypeI18n->deleteall(array('type_id' => $id)); //删除原有多语言
        //	pr($_POST['data1']['ProductTypeI18n']);die;
        foreach ($_POST['data1']['ProductTypeI18n'] as $v) {
            $productTypeI18n_info = array(
                      'locale' => $v['locale'],
                      'type_id' => $id,
                       'name' => isset($v['name']) ? $v['name'] : '',
                  );
            $a = $this->ProductTypeI18n->saveAll(array('ProductTypeI18n' => $productTypeI18n_info));//更新多语言
        }
        if ($a) {
            $result['message'] = $this->ld['complete_success'];
            $result['flag'] = 1;
            $attribute_tree = $this->ProductType->find('all');
            $result['arrtibute'] = '<select name="data[Product][attribute_id]" style="height:auto;" size="5" id="product_attribute_id">';
            $result['arrtibute'] .= '<option value="0">'.$this->ld['select_attribute'].'</option>';
            if (isset($attribute_tree) && sizeof($attribute_tree) > 0) {
                //pr($attribute_tree);die;
                    foreach ($attribute_tree as $first_k => $first_v) {
                        if ($first_v['ProductTypeI18n']['locale'] == 'chi') {
                            if ($first_v['ProductType']['id'] == $id) {
                                $quick_attribute_name = $first_v['ProductTypeI18n']['name'];
                                $result['arrtibute'] .= '<option value="'.$first_v['ProductType']['id'].'" selected>'.$first_v['ProductTypeI18n']['name'].'</option>';
                            } else {
                                $result['arrtibute'] .= '<option value="'.$first_v['ProductType']['id'].'">'.$first_v['ProductTypeI18n']['name'].'</option>';
                            }
                        }
                    }
            }
            $result['arrtibute'] .= '</select><input type="button" onclick="popOpen("productattribute")" value="'.$this->ld['quick_add_brand'].'" />';
            //操作员日志
            if (isset($this->configs['open_OperatorLog']) && $this->configs['open_OperatorLog'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['quick_add_attribute'].':'.$quick_attribute_name, $this->admin['id']);
            }
        } else {
            $result['message'] = $this->ld['complete_failure'];
            $result['flag'] = 2;
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *删除属性类型.
     *
     *@param int $id 输入属性类型ID
     */
    public function remove($id)
    {
        //$_REQUEST[$id];die;
         //echo $id;die;
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_attribute_type_failure'];
        $this->ProductType->deleteAll(array('ProductType.id' => $id));
        $this->ProductTypeAttribute->deleteAll(array('product_type_id' => $id));

        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_attribute_type_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
            //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除属性类型:id '.$id, $this->admin['id']);
        }
        die(json_encode($result));
    }

    /**
     *列表推荐修改.
     */
    public function toggle_on_status()
    {
        $this->ProductTypeAttribute->hasMany = array();
        $this->ProductTypeAttribute->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->ProductTypeAttribute->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].'  修改列表推荐', $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function toggle_on_attrtype()
    {
        $this->ProductTypeAttribute->hasMany = array();
        $this->ProductTypeAttribute->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->ProductTypeAttribute->save(array('id' => $id, 'attr_type' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *删除商品类型 批量操作.
     */
    public function remove_batch()
    {
        $pt_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $this->ProductType->deleteAll(array('ProductType.id' => $pt_ids));
        $this->ProductTypeAttribute->deleteAll(array('product_type_id' => $pt_ids));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 批量删除商品类型', $this->admin['id']);
        }
        $this->redirect('/productstypes/');
    }

    //检查属性Code唯一
    public function check_producttype_unique()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 1);
            $this->layout = 'ajax';
            $producttype_code = isset($_POST['producttype_code']) ? $_POST['producttype_code'] : '';
            $producttype_id = isset($_POST['producttype_id']) ? $_POST['producttype_id'] : '';
            $result['code'] = 0;
            $result['msg'] = $this->ld['parameters'].' '.$this->ld['not_exist'];
            if ($producttype_code != '') {
                if ($producttype_id != '') {
                    $conditions['ProductType.id <>'] = $producttype_id;
                }
                $conditions['ProductType.code'] = $producttype_code;
                $product_typeInfo = $this->ProductType->find('first', array('conditions' => $conditions));
                if (empty($product_typeInfo)) {
                    $result['code'] = 1;
                    $result['msg'] = '';
                } else {
                    $result['msg'] = $this->ld['code_already_exists'];
                }
            }
            die(json_encode($result));
        }
        die();
    }

    public function check_producttypeattr_unique()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 1);
            $this->layout = 'ajax';
            $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : '';
            $producttypeattr_code = isset($_POST['producttypeattr_code']) ? $_POST['producttypeattr_code'] : '';
            $producttypeattr_id = isset($_POST['producttypeattr_id']) ? $_POST['producttypeattr_id'] : '';

            $result['code'] = 0;
            $result['msg'] = $this->ld['parameters'].' '.$this->ld['not_exist'];

            if ($producttypeattr_code != '') {
                if ($producttypeattr_id != '') {
                    $conditions['ProductTypeAttribute.id <>'] = $producttypeattr_id;
                }
                $conditions['ProductTypeAttribute.code'] = $producttypeattr_code;
                $conditions['ProductTypeAttribute.product_type_id'] = $product_type_id;
                $producttypeattr_Info = $this->ProductTypeAttribute->find('first', array('conditions' => $conditions));
                if (empty($producttypeattr_Info)) {
                    $result['code'] = 1;
                    $result['msg'] = '';
                } else {
                    $result['msg'] = $this->ld['code_already_exists'];
                }
            }
            die(json_encode($result));
        }
        die();
    }

    //获得所有属性
    public function getAttrInfo()
    {
        $result['msg'] = '';
        if ($this->RequestHandler->isPost()) {
            if (isset($_REQUEST['attrval'])) {
                $this->Attribute->set_locale($this->backend_locale);

                $pro_ids_cond['Product.status'] = 1;
                $pro_ids_cond['Product.product_type_id'] = $_REQUEST['attrval'];
                if (isset($_REQUEST['attr_cate_id']) && trim($_REQUEST['attr_cate_id']) != '') {
                    $pro_ids_cond['or']['Product.category_id'] = $_REQUEST['attr_cate_id'];
                    $pro_cate_ids = $this->CategoryProduct->find('list', array('conditions' => array('CategoryProduct.type' => 'P', 'CategoryProduct.parent_id' => $_REQUEST['attr_cate_id']), 'fields' => array('CategoryProduct.id')));
                    $pro_ids_cond['or']['Product.category_id'] = $pro_cate_ids;
                }
                $pro_ids = $this->Product->find('list', array('fields' => array('Product.id'), 'conditions' => $pro_ids_cond));
                $attr_ids = $this->ProductTypeAttribute->getattrids($_REQUEST['attrval']);

                $attr_group = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids, 'attr_input_type' => '1', 'status' => '1'), 'order' => 'name'));
                if (!empty($attr_group)) {
                    $all_pro_attr_value = $this->ProductAttribute->find('all', array('fields' => array('ProductAttribute.attribute_id', 'ProductAttribute.attribute_value'), 'conditions' => array('ProductAttribute.attribute_id' => $attr_ids, 'ProductAttribute.product_id' => $pro_ids, 'ProductAttribute.locale' => $this->backend_locale)));
                    $all_pro_attr = array();
                    foreach ($all_pro_attr_value as $k => $v) {
                        if (trim($v['ProductAttribute']['attribute_value']) != '') {
                            $all_pro_attr[$v['ProductAttribute']['attribute_id']][] = $v['ProductAttribute']['attribute_value'];
                        }
                    }
                    foreach ($attr_group as $k => $v) {
                        $result['msg'][$k]['attr'] = $this->Attribute->localeformat($v['Attribute']['id']);//获取属性信息
                        $result['msg'][$k]['id'] = $result['msg'][$k]['attr']['Attribute']['id'];
                        $result['msg'][$k]['name'] = $result['msg'][$k]['attr']['AttributeI18n'][$this->backend_locale]['name'];

                        $_value = $result['msg'][$k]['attr']['AttributeI18n'][$this->backend_locale]['attr_value'];
                        if (isset($all_pro_attr[$v['Attribute']['id']]) && is_array($all_pro_attr[$v['Attribute']['id']])) {
                            $value_arr = explode("\r\n", $_value);
                            foreach ($value_arr as $kk => $vv) {
                                if (!in_array($vv, $all_pro_attr[$v['Attribute']['id']])) {
                                    unset($value_arr[$kk]);
                                }
                            }
                            $_value = implode("\r\n", $value_arr);
                        }
                        $result['msg'][$k]['value'] = $_value;
                        unset($result['msg'][$k]['attr']);
                    }
                }
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //获得属性type为dropdown中的optionlist
    public function getdropdownInfo()
    {
        $result['msg'] = '';
        if ($this->RequestHandler->isPost()) {
            $this->Attribute->set_locale($this->backend_locale);
            if (isset($_REQUEST['optionlist'])) {
                $pro_ids_cond['Product.status'] = 1;
                if (isset($_REQUEST['attrval'])) {
                    $pro_ids_cond['Product.product_type_id'] = $_REQUEST['attrval'];
                }
                if (isset($_REQUEST['attr_cate_id']) && trim($_REQUEST['attr_cate_id']) != '') {
                    $pro_ids_cond['or']['Product.category_id'] = $_REQUEST['attr_cate_id'];
                    $pro_cate_ids = $this->CategoryProduct->find('list', array('conditions' => array('CategoryProduct.type' => 'P', 'CategoryProduct.parent_id' => $_REQUEST['attr_cate_id']), 'fields' => array('CategoryProduct.id')));
                    $pro_ids_cond['or']['Product.category_id'] = $pro_cate_ids;
                }
                $pro_ids = $this->Product->find('list', array('fields' => array('Product.id'), 'conditions' => $pro_ids_cond));

                $attr_group = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $_REQUEST['optionlist'], 'attr_input_type' => '1'), 'order' => 'name'));
                if (!empty($attr_group)) {
                    $pro_type_attr_ids = array();
                    foreach ($attr_group as $k => $v) {
                        $pro_type_attr_ids[$v['Attribute']['id']] = $v['Attribute']['id'];
                    }
                    $all_pro_attr_value = $this->ProductAttribute->find('all', array('fields' => array('ProductAttribute.attribute_id', 'ProductAttribute.attribute_value'), 'conditions' => array('ProductAttribute.attribute_id' => $pro_type_attr_ids, 'ProductAttribute.product_id' => $pro_ids, 'ProductAttribute.locale' => $this->backend_locale)));
                    $all_pro_attr = array();
                    foreach ($all_pro_attr_value as $k => $v) {
                        if (trim($v['ProductAttribute']['attribute_value']) != '') {
                            $all_pro_attr[$v['ProductAttribute']['attribute_id']][] = $v['ProductAttribute']['attribute_value'];
                        }
                    }
                    foreach ($attr_group as $k => $v) {
                        $result['msg'][$k]['attr'] = $this->Attribute->localeformat($v['Attribute']['id']);//获取属性信息
                        $result['msg'][$k]['id'] = $result['msg'][$k]['attr']['Attribute']['id'];
                        $result['msg'][$k]['name'] = $result['msg'][$k]['attr']['AttributeI18n'][$this->backend_locale]['name'];

                        $_value = $result['msg'][$k]['attr']['AttributeI18n'][$this->backend_locale]['attr_value'];
                        if (isset($all_pro_attr[$v['Attribute']['id']]) && is_array($all_pro_attr[$v['Attribute']['id']])) {
                            $value_arr = explode("\r\n", $_value);
                            foreach ($value_arr as $kk => $vv) {
                                if (!in_array($vv, $all_pro_attr[$v['Attribute']['id']])) {
                                    unset($value_arr[$kk]);
                                }
                            }
                            $_value = implode("\r\n", $value_arr);
                        }
                        $result['msg'][$k]['value'] = $_value;
                        unset($result['msg'][$k]['attr']);
                    }
                }
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /*
        排序
    */
    public function changeorder($updowm, $id, $nextone, $producttype_id = 1)
    {
        //如果值相等重新自动排序
        $a = $this->ProductTypeAttribute->query('SELECT * 
			FROM `svoms_product_type_attributes` as A inner join `svoms_product_type_attributes` as B
			WHERE A.id<>B.id and A.orderby=B.orderby and A.product_type_id='.$producttype_id.'');
        $topic_one = $this->ProductTypeAttribute->find('first', array('conditions' => array('ProductTypeAttribute.id' => $id)));
        if (!empty($a)) {
            $all = $this->ProductTypeAttribute->find('all', array('conditions' => array('ProductTypeAttribute.product_type_id' => $producttype_id)));
            $i = 0;
            foreach ($all as $k => $vv) {
                $all[$k]['ProductTypeAttribute']['orderby'] = ++$i;
            }
            $this->ProductTypeAttribute->saveAll($all);
        }
        if ($updowm == 'up') {
            $topic_change = $this->ProductTypeAttribute->find('first', array('conditions' => array('ProductTypeAttribute.product_type_id' => $producttype_id, 'ProductTypeAttribute.orderby <' => $topic_one['ProductTypeAttribute']['orderby']), 'order' => 'ProductTypeAttribute.orderby+0 desc', 'limit' => '1'));
        }
        if ($updowm == 'down') {
            $topic_change = $this->ProductTypeAttribute->find('first', array('conditions' => array('ProductTypeAttribute.product_type_id' => $producttype_id, 'ProductTypeAttribute.orderby >' => $topic_one['ProductTypeAttribute']['orderby']), 'order' => 'ProductTypeAttribute.orderby+0 asc', 'limit' => '1'));
        }
        //pr($a);
        $t = $topic_one['ProductTypeAttribute']['orderby'];
        $topic_one['ProductTypeAttribute']['orderby'] = $topic_change['ProductTypeAttribute']['orderby'];
        $topic_change['ProductTypeAttribute']['orderby'] = $t;

        $this->ProductTypeAttribute->save($topic_one);
        $this->ProductTypeAttribute->save($topic_change);

        $condition = array('ProductTypeAttribute.product_type_id' => $producttype_id);
        $sortClass = 'ProductTypeAttribute';
        $total = $this->ProductTypeAttribute->find('count', $condition);
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $attr_data = $this->ProductTypeAttribute->find('all', array('conditions' => $condition, 'order' => 'ProductTypeAttribute.orderby+0'));
        $attr_ids = array();
        foreach ($attr_data as $v) {
            $attr_ids[] = $v['ProductTypeAttribute']['attribute_id'];
        }
        $this->Attribute->set_locale($this->backend_locale);
        $attribute_list = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids)));
        foreach ($attr_data as $k => $v) {
            foreach ($attribute_list as $ak => $av) {
                if ($v['ProductTypeAttribute']['attribute_id'] == $av['Attribute']['id']) {
                    $attr_data[$k]['ProductTypeAttribute']['attribute_name'] = $av['AttributeI18n']['name'];
                }
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($attr_data));
    }
}
