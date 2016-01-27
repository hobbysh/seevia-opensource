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
class AttributesController extends AppController
{
    public $name = 'Attributes';
    public $components = array('Pagination','RequestHandler','Phpexcel','Phpcsv');
    public $helpers = array('Html','Pagination','Ckeditor');
    public $uses = array('Attribute','AttributeI18n','AttributeOption','ProductTypeAttribute','ProductType','Resource','OperatorLog');

    /**
     *显示类型列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('attribute_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/product/','sub' => '/attributes/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['attributes_manage'],'url' => '');
        $this->Attribute->set_locale($this->backend_locale);
        $this->ProductType->set_locale($this->backend_locale);
        $condition = '';
        if (isset($_REQUEST['keywords']) && trim($_REQUEST['keywords']) != '') {
            $condition['or']['AttributeI18n.name like'] = '%'.trim($_REQUEST['keywords']).'%';
            $condition['or']['Attribute.code like'] = '%'.trim($_REQUEST['keywords']).'%';
            $this->set('attr_keywords', trim($_REQUEST['keywords']));
        }
        if (isset($_REQUEST['attr_type']) && trim($_REQUEST['attr_type']) != '') {
            $condition['and']['Attribute.type'] = trim($_REQUEST['attr_type']);
            $this->set('attr_type', trim($_REQUEST['attr_type']));
        }
        if (isset($_REQUEST['productstype']) && $_REQUEST['productstype'] != '') {
            $attr_ids = $this->ProductTypeAttribute->find('list', array('fields' => array('ProductTypeAttribute.attribute_id'), 'conditions' => array('ProductTypeAttribute.product_type_id' => $_REQUEST['productstype']), 'order' => 'ProductTypeAttribute.id'));
            $condition['and']['Attribute.id'] = $attr_ids;
            $this->set('productstype', $_REQUEST['productstype']);
        }

        $total = $this->Attribute->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'attributes','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Attribute');
        $this->Pagination->init($condition, $parameters, $options);
        $attribute_list = $this->Attribute->find('all', array('page' => $page, 'limit' => $rownum, 'conditions' => $condition, 'order' => 'Attribute.orderby,Attribute.created,Attribute.id'));
        $this->set('attribute_list', $attribute_list);
        $this->set('title_for_layout', $this->ld['attributes_manage'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('property_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);

        //属性组列表
        $productstype_list = $this->ProductType->find('all', array('fields' => array('ProductType.id', 'ProductTypeI18n.name'), 'conditions' => array('ProductType.status' => 1, 'ProductType.id !=' => 0), 'order' => 'ProductType.orderby,ProductType.created,ProductType.id'));
        $this->set('productstype_list', $productstype_list);
    }

    public function view($id = 0)
    {
        $this->menu_path = array('root' => '/product/','sub' => '/attributes/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['attributes_manage'],'url' => '/attributes/');
        if ($this->RequestHandler->isPost()) {
            $userinformation_name = '';
            $this->Attribute->save($this->data['Attribute']);
            $id = $this->Attribute->id;
            $this->AttributeI18n->deleteAll(array('attribute_id' => $id));
            foreach ($this->data['AttributeI18n'] as $v) {
                $data = array(
                    'attribute_id' => $id,
                    'locale' => $v['locale'],
                    'name' => $v['name'],
                    'default_value' => $v['default_value'],
                    'attr_value' => isset($v['attr_value'])?$v['attr_value']:'',
                    'description' => $v['description'],
                );
                $this->AttributeI18n->saveAll($data);
                if ($v['locale'] == $this->backend_locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_attribute'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $attr_info = $this->Attribute->localeformat($id);
        $this->set('attribute', $attr_info);

        if ($id == 0 || empty($attr_info)) {
            $this->operator_privilege('attribute_add');
            $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
            $this->set('title_for_layout', $this->ld['add'].' - '.$this->ld['attributes_manage'].' - '.$this->configs['shop_name']);
        } else {
            $this->operator_privilege('attribute_edit');
            $this->navigations[] = array('name' => $this->ld['edit'].' - '.$attr_info['AttributeI18n'][$this->backend_locale]['name'],'url' => '');
            $this->set('title_for_layout', $this->ld['edit'].' - '.$this->ld['attributes_manage'].' - '.$this->configs['shop_name']);
        }

        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('property_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
    }

    public function attribute_option($attribute_id = 0, $page = 1)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';

        $attr_info = $this->Attribute->localeformat($attribute_id);
        $this->set('attribute', $attr_info);
        $this->set('attribute_id', $attribute_id);

        $condition['AttributeOption.attribute_id'] = $attribute_id;
        $total = $this->AttributeOption->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'attributes','action' => 'attribute_option/'.$attribute_id,'page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'AttributeOption');
        $this->Pagination->init($condition, $parameters, $options);
        $attr_option_list = $this->AttributeOption->find('all', array('page' => $page, 'limit' => $rownum, 'conditions' => $condition, 'order' => 'AttributeOption.created,AttributeOption.locale'));

        $this->set('attr_option_list', $attr_option_list);
        $option_language = array();
        foreach ($this->backend_locales as $k => $v) {
            $option_language[$v['Language']['locale']] = $v['Language']['name'];
        }
        $this->set('option_language', $option_language);
    }

    public function attribute_option_view($attribute_id = 0, $option_id = 0)
    {
        $this->menu_path = array('root' => '/product/','sub' => '/attributes/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['attributes_manage'],'url' => '/attributes/');

        $attr_info = $this->Attribute->localeformat($attribute_id);
        if (empty($attr_info)) {
            $this->redirect('/attributes/');
        }
        $this->set('attribute', $attr_info);
        $this->navigations[] = array('name' => $attr_info['AttributeI18n'][$this->backend_locale]['name'],'url' => '/attributes/'.$attr_info['Attribute']['id']);

        if ($this->RequestHandler->isPost()) {
            $this->AttributeOption->save($this->data['AttributeOption']);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_attribute'].': '.$attr_info['AttributeI18n'][$this->backend_locale]['name'].' '.$this->ld['edit'].' '.$this->data['AttributeOption']['option_name'], $this->admin['id']);
            }
            $this->redirect('/attributes/view/'.$attribute_id);
        }

        $this->set('attribute_id', $attribute_id);

        $attribute_option_data = $this->AttributeOption->find('first', array('conditions' => array('AttributeOption.id' => $option_id)));
        $this->set('attribute_option_data', $attribute_option_data);

        if (empty($attribute_option_data)) {
            $this->navigations[] = array('name' => $this->ld['add'].' - '.$this->ld['option_list'],'url' => '');
            $this->set('title_for_layout', $this->ld['add'].' - '.$this->ld['option_list'].' - '.$this->configs['shop_name']);
        } else {
            $this->navigations[] = array('name' => $this->ld['edit'].' - '.$this->ld['option_list'],'url' => '');
            $this->set('title_for_layout', $this->ld['edit'].' - '.$this->ld['option_list'].' - '.$this->configs['shop_name']);
        }

        $option_language = array();
        foreach ($this->backend_locales as $k => $v) {
            $option_language[$v['Language']['locale']] = $v['Language']['name'];
        }
        $this->set('option_language', $option_language);
    }

    /*
    	修改属性是否可选
    */
    public function toggle_on_attrtype()
    {
        $this->operator_privilege('attribute_edit');
        $this->Attribute->hasMany = array();
        $this->Attribute->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Attribute->save(array('id' => $id, 'attr_type' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);

            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].$this->ld['edit'].''.$this->ld['optional_attribute'].':'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
    	修改属性状态
    */
    public function toggle_on_status()
    {
        $this->operator_privilege('attribute_edit');
        $this->Attribute->hasMany = array();
        $this->Attribute->hasOne = array();

        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Attribute->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].$this->ld['edit'].''.$this->ld['valid_status'].':'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
    	属性删除
    */
    public function remove($id = 0)
    {
        $this->operator_privilege('attribute_remove');
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_attribute_failure'];
        $this->Attribute->deleteAll(array('Attribute.id' => $id));
        $this->AttributeI18n->deleteAll(array('AttributeI18n.attribute_id' => $id));
        $this->ProductTypeAttribute->deleteAll(array('ProductTypeAttribute.attribute_id' => $id));
        $this->AttributeOption->deleteAll(array('AttributeOption.attribute_id' => $id));
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_attribute_success'];
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['remove'].' '.$this->ld['attribute'].'id '.$id, $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /*
    	批量删除
    */
    public function removeAll()
    {
        $this->operator_privilege('attribute_remove');
        if ($this->RequestHandler->isPost()) {
            $attr_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
            $this->Attribute->deleteAll(array('Attribute.id' => $attr_ids));
            $this->AttributeI18n->deleteAll(array('AttributeI18n.attribute_id' => $attr_ids));
            $this->ProductTypeAttribute->deleteAll(array('ProductTypeAttribute.attribute_id' => $attr_ids));
            $this->AttributeOption->deleteAll(array('AttributeOption.attribute_id' => $attr_ids));
            $attr_ids_arr = implode(',', $attr_ids);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['remove'].' '.$this->ld['attribute'].'id '.$attr_ids_arr, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        } else {
            $this->redirect('/attributes/index');
        }
    }

    /*
    	修改属性选项状态
    */
    public function toggle_on_option_status()
    {
        $this->operator_privilege('attribute_edit');

        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->AttributeOption->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].$this->ld['edit'].''.$this->ld['option_list'].'id '.$id.' '.$this->ld['valid_status'].':'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
        属性选项删除
    */
    public function remove_attr_option($id = 0)
    {
        $this->operator_privilege('attribute_remove');
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        if ($this->AttributeOption->deleteAll(array('AttributeOption.id' => $id))) {
            $result['flag'] = 1;
            $result['message'] = $this->ld['deleted_success'];

            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['remove'].' '.$this->ld['option_list'].'id '.$id, $this->admin['id']);
            }
        }
        die(json_encode($result));
    }

    /*
        属性选项批量删除
    */
    public function remove_attr_option_all()
    {
        $this->operator_privilege('attribute_add');
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if ($this->RequestHandler->isPost()) {
            $result['flag'] = 2;
            $result['message'] = $this->ld['delete_failure'];

            $option_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
            if ($this->AttributeOption->deleteAll(array('AttributeOption.id' => $option_ids))) {
                $result['flag'] = 1;
                $result['message'] = $this->ld['deleted_success'];

                $option_ids_arr = implode(',', $option_ids);

                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['remove'].' '.$this->ld['option_list'].'id:'.$option_ids_arr, $this->admin['id']);
                }
            }
            die(json_encode($result));
        } else {
            $this->redirect('/attributes/index');
        }
    }

    /*
    	验证code是否重复
    */
    public function check_attr_unique()
    {
        if ($this->RequestHandler->isPost()) {
            $attr_id = isset($_POST['attr_id']) ? $_POST['attr_id'] : 0;
            $attr_code = isset($_POST['attr_code']) ? $_POST['attr_code'] : '';
            $count_num = $this->Attribute->find('count', array('conditions' => array('Attribute.code' => $attr_code, 'Attribute.id !=' => $attr_id)));

            if ($count_num > 0) {
                $result['code'] = 0;
                $result['msg'] = $this->ld['code_already_exists'];
            } else {
                $result['code'] = 1;
                $result['msg'] = '';
            }
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            die(json_encode($result));
        } else {
            $this->redirect('/attributes/index');
        }
    }

    public function getattrlist($pro_type_id = '')
    {
        if ($this->RequestHandler->isPost()) {
            $result['flag'] = 0;
            $this->Attribute->set_locale($this->backend_locale);
            if ($pro_type_id != '') {
                $attr_ids = $this->ProductTypeAttribute->find('list', array('fields' => array('ProductTypeAttribute.attribute_id'), 'conditions' => array('ProductTypeAttribute.product_type_id' => $pro_type_id), 'order' => 'ProductTypeAttribute.id'));
                $condition['NOT']['Attribute.id'] = $attr_ids;
            }
            $condition['and']['Attribute.status'] = 1;
            if (isset($_REQUEST['attr_keywords']) && trim($_REQUEST['attr_keywords']) != '') {
                $condition['or']['AttributeI18n.name like'] = '%'.trim($_REQUEST['attr_keywords']).'%';
                $condition['or']['Attribute.code like'] = '%'.trim($_REQUEST['attr_keywords']).'%';
            }
            if (isset($_REQUEST['attr_type']) && trim($_REQUEST['attr_type']) != '') {
                $condition['and']['Attribute.type'] = trim($_REQUEST['attr_type']);
            }
            $attribute_list = $this->Attribute->find('all', array('fields' => array('Attribute.id', 'AttributeI18n.name'), 'conditions' => $condition, 'order' => 'Attribute.created,Attribute.id'));
            if (!empty($attribute_list)) {
                $result['flag'] = 1;
                $result['content'] = $attribute_list;
            } else {
                $result['content'] = $this->ld['not_found'].$this->ld['attribute'];
            }
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            die(json_encode($result));
        } else {
            $this->redirect('/attributes/index');
        }
    }

    public function update_option_name()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->AttributeOption->updateAll(
            array('option_name' => "'".$val."'"),
            array('id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        die(json_encode($result));
    }

    public function update_option_value()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->AttributeOption->updateAll(
            array('option_value' => "'".$val."'"),
            array('id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        die(json_encode($result));
    }

    public function update_option_price()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->AttributeOption->updateAll(
            array('price' => "'".$val."'"),
            array('id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        die(json_encode($result));
    }
    ////////导出
    public function doload_csv_example()
    {
        $this->operator_privilege('attribute_add');
        //是显示导入导出的页面
          $this->menu_path = array('root' => '/web_application/','sub' => '/dictionaries/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['attributes_manage'],'url' => '/attributes/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $this->set('title_for_layout', $this->ld['attributes_manage'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
    }
    ////////具体导出方法
    public function upload_csv()
    {
        $this->operator_privilege('attribute_add');
        $fields = array('Attribute.code','Attribute.type','Attribute.status','Attribute.attr_input_type','Attribute.attr_type','Attribute.orderby','AttributeI18n.locale','AttributeI18n.name','AttributeI18n.description','AttributeI18n.default_value');
        $fields_array = array($this->ld['code'],
           $this->ld['attribute_type'],
           $this->ld['status'],
           $this->ld['attribute_input_type'],
           $this->ld['optional_attribute'],
          $this->ld['sort'],
           $this->ld['language'],
           $this->ld['attribute_name'],
           $this->ld['description'],
           $this->ld['default_value'], );
        $newdatas = array();
        $newdatas[] = $fields_array;
        $Attributes_all = $this->Attribute->find('all', array('order' => 'Attribute.id ', 'limit' => 10));
        foreach ($Attributes_all as $k => $v) {
            $user_tmp = array();
            foreach ($fields as $ks => $vs) {
                $fields_ks = explode('.', $vs);
                $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
            }
            $newdatas[] = $user_tmp;
        }
        $nameexl = $this->ld['attribute'].''.$this->ld['export'].date('Ymd').'.csv';
        $this->Phpcsv->output($nameexl, $newdatas);
        die();
    }
///////导入
public function csv_add()
{
    ////////////判断权限
           $this->operator_privilege('attribute_add');

         //接收过来的文件不为空
            if (!empty($_FILES['file'])) {
                $this->menu_path = array('root' => '/web_application/','sub' => '/dictionaries/');
                $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
                $this->navigations[] = array('name' => $this->ld['attributes_manage'],'url' => '/attributes/');
                $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
                $this->set('title_for_layout', $this->ld['attributes_manage'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
               //文件错误大于0 提示 并且返回
            if ($_FILES['file']['error'] > 0) {
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/attributes/doload_csv_example';</script>";
            } else {
                //文件没有错误 就显示文件
             $handle = @fopen($_FILES['file']['tmp_name'], 'r');
              //定义表的字段数组
               $fields_array = array(
                                'Attribute.code',
                                'Attribute.type',
                                'Attribute.status',
                                'Attribute.attr_input_type',
                                'Attribute.attr_type',
                                'Attribute.orderby',
                                'AttributeI18n.locale',
                                'AttributeI18n.name',
                                'AttributeI18n.description',
                                'AttributeI18n.default_value', );

                $fieldarray = array(
                                $this->ld['code'],
                                   $this->ld['attribute_type'],
                                   $this->ld['status'],
                                   $this->ld['attribute_input_type'],
                                   $this->ld['optional_attribute'],
                                  $this->ld['sort'],
                                   $this->ld['language'],
                                   $this->ld['attribute_name'],
                                   $this->ld['description'],
                                   $this->ld['default_value'], );
             //定义预览标题
             $key_arr = array();
                foreach ($fields_array as $k => $v) {
                    $fields_k = explode('.', $v);
                    $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                }
                $csv_export_code = 'gb2312';
                $i = 0;
                while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                    if ($i == 0) {
                        $check_row = $row[0];
                        $row_count = count($row);
                        $check_row = iconv('GB2312', 'UTF-8', $check_row);
                        $num_count = count($key_arr);
                        ++$i;
                    }
                    $temp = array();
                    foreach ($row as $k => $v) {
                        $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                    }
                    if (!isset($temp) || empty($temp)) {
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/users/uploadusers';</script>";
                        die();
                    }
                    $data[] = $temp;
                }
                fclose($handle);
                $this->set('fieldarray', $fieldarray);
                $this->set('key_arr', $key_arr);
                $this->set('data_list', $data);
            }
            }//第一次if
         //判断点击添加之后

      elseif (!empty($this->data)) {
          $checkbox_arr = $_REQUEST['checkbox'];
          foreach ($this->data as $key => $v) {
              //pr($this->data);
               //把提交数组 分成 2 个数组 
                $Attribute_finds = array('id','code','code','type','status','attr_input_type','attr_type','orderby');
              $AttributeI18n_finds = array('locale','attribute_id','name','description','attr_value','default_value');
                //定义两个数组 分别存 两张表
                $Attribute_arr = array();//主表数组
                $AttributeI18n_arr = array();//次表数组
                    foreach ($v as $ks => $vs) {
                        if (in_array($ks, $Attribute_finds)) {
                            $Attribute_arr[$ks] = $vs;
                        }
                        if (in_array($ks, $AttributeI18n_finds)) {
                            $AttributeI18n_arr[$ks] = $vs;
                        }
                    }
              $arr = $this->Attribute->find('first', array('conditions' => array('Attribute.code' => $Attribute_arr['code'])));
              $Attribute_arr['id'] = isset($arr['Attribute']['id']) ? $arr['Attribute']['id'] : 0;
              pr($Attribute_arr);
              $this->Attribute->save($Attribute_arr);
              $AttributeI18n_arr['attribute_id'] = $this->Attribute->id;

              $i8n_arr = $this->
                            AttributeI18n->find('first', array('conditions' => array('AttributeI18n.locale' => $AttributeI18n_arr['locale'], 'AttributeI18n.attribute_id' => $AttributeI18n_arr['attribute_id'])));
              $AttributeI18n_arr['id'] = isset($i8n_arr['AttributeI18n']['id']) ? $i8n_arr['AttributeI18n']['id'] : 0;
              pr($AttributeI18n_arr);

              $this->AttributeI18n->save($AttributeI18n_arr);
                             //$Res18n_id=$this->AttributeI18n->id;
          }
          $this->redirect('/attributes');
      }

             /////////////////////////////////权限
}
/////////
 public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"')
 {
     $d = preg_quote($d);
     $e = preg_quote($e);
     $_line = '';
     $eof = false;
     while ($eof != true) {
         $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
         $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
         if ($itemcnt % 2 == 0) {
             $eof = true;
         }
     }
     $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
     $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
     preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
     $_csv_data = $_csv_matches[1];
     for ($_csv_i = 0; $_csv_i < count($_csv_data); ++$_csv_i) {
         $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
         $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
     }

     return empty($_line) ? false : $_csv_data;
 }
}
