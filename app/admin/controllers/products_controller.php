<?php

/**
 *这是一个名为 ProductsController 的控制器
 *后台商品管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Vendor', 'Ecflag', array('file' => 'ec_flag_webservice.php'));
App::import('Controller', 'Commons');//加载公共控制器
unset($this->ld);
class ProductsController extends AppController
{
    public $name = 'Products';
    public $components = array('Pagination','RequestHandler','Phpexcel','Phpcsv','EcFlagWebservice');//,'EcFlagWebservice'
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('ProductVolume','Language','Operator','Application','Product','ProductI18n','Brand','BrandI18n','ProductType','Profile','ProfileFiled','TopicProduct','ProductTypeAttribute','Attribute','AttributeOption','ProductRelation','ProductArticle','Article','ProductAttribute','ProductsCategory','PhotoCategory','PhotoCategoryGallery','ProductGallery','ProductGalleryI18n','InformationResource','InformationResourceI18n','CategoryType','CategoryTypeI18n','ProductDownload','UploadFile','Tag','TagI18n','Config','Route','ProductTypeI18n','SystemResource','PackageProduct','CategoryProduct','CategoryArticle','SkuProduct','ProfilesFieldI18n','Resource','ConfigI18n','Material','MaterialI18n','ProductMaterial');

    /**
     *显示商品列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('products_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->Product->Behaviors->attach('Containable');
        $trash_count = $this->Product->find('count', array('conditions' => array('Product.status' => 2), 'recursive' => -1));
        $Operator_list = $this->Operator->find('all');
        $this->set('Operator_list', $Operator_list);
        $this->set('trash_count', $trash_count);

        $pro_option_type_name = array($this->ld['ordinary'].$this->ld['product'],$this->ld['package_product'],$this->ld['sales_attribute'].$this->ld['product']);
        $this->set('pro_option_type_name', $pro_option_type_name);

        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        //$this->set('navigations',$this->navigations);
        //分类树
        $product_category_tree = array();
        $category_tree = $this->CategoryProduct->tree('P', $this->backend_locale);
        $category_name_list = array();
        if (isset($category_tree) && sizeof($category_tree) > 0) {
            foreach ($category_tree as $first_k => $first_v) {
                $category_name_list[$first_v['CategoryProduct']['id']] = $first_v['CategoryProductI18n']['name'];
                $product_category_tree[$first_v['CategoryProduct']['id']] = $first_v['CategoryProductI18n']['name'];
                if (isset($first_v['SubCategory']) && sizeof($first_v['SubCategory']) > 0) {
                    foreach ($first_v['SubCategory'] as $second_k => $second_v) {
                        $category_name_list[$second_v['CategoryProduct']['id']] = '--'.$second_v['CategoryProductI18n']['name'];
                        $product_category_tree[$second_v['CategoryProduct']['id']] = $second_v['CategoryProductI18n']['name'];
                        if (isset($second_v['SubCategory']) && sizeof($second_v['SubCategory']) > 0) {
                            foreach ($second_v['SubCategory'] as $third_k => $third_v) {
                                $category_name_list[$third_v['CategoryProduct']['id']] = '----'.$third_v['CategoryProductI18n']['name'];
                                $product_category_tree[$third_v['CategoryProduct']['id']] = $third_v['CategoryProductI18n']['name'];
                            }
                        }
                    }
                }
            }
        }
        $this->set('category_name_list', $category_name_list);
        $this->set('product_category_tree', $product_category_tree);
        $log_flag = $this->Operator->find('first', array('fields' => array('Operator.log_flag', 'Operator.type'), 'conditions' => array('Operator.name' => $this->admin['name'])));
        $this->admin['log_flag'] = $log_flag['Operator']['log_flag'];
        $this->admin['type'] = $log_flag['Operator']['type'];
        $this->set('opertor_type', $log_flag['Operator']['type']);

        $this->Attribute->set_locale($this->backend_locale);

        $condition = '';
        $condition['Product.status'] = '1';
        //索搜 参数 初始化
        $category_id = '';        //分类
        $brand_id = 0;            //品牌
        $product_type_id = 0;    //商品属性
        $is_recommond = '-1';    //推荐
        $forsale = '-1';        //上架
        $operator_id = '-1';        //操作员
        $product_keywords = ''; //关键字
        $min_price = '';        //开始价格
        $max_price = '';        //结束价格
        $start_date = '';        //开始时间
        $end_date = '';        //结束时间
        $start_date_time = '';        //开始修改时间
        $end_date_time = '';        //修改完成时间
        $option_type_id = '-1';        //商品类型

        //品牌
        if (isset($this->params['url']['brand_id']) && $this->params['url']['brand_id'] != '0') {
            if ($this->params['url']['brand_id'] == -1) {
                $brand_ids_array = array();
                $code_brand_list = $this->Product->find('list', array('fields' => array('Product.brand_id', 'Product.brand_id'), 'group' => 'Product.brand_id'));
                $brand_id_list = $this->Brand->find('list', array('fields' => array('Brand.id', 'Brand.id')));
                $brand_ids_array = array_diff($code_brand_list, $brand_id_list);
                $condition['and']['Product.brand_id'] = $brand_ids_array;
            } else {
                $condition['and']['Product.brand_id ='] = $this->params['url']['brand_id'];
            }
            $brand_id = $this->params['url']['brand_id'];
        }
        $attr_cate = array();
        //类型
        if (isset($this->params['url']['product_type_id']) && $this->params['url']['product_type_id'] != '0') {
            $condition['and']['Product.product_type_id'] = $this->params['url']['product_type_id'];
            $product_type_id = $this->params['url']['product_type_id'];
            $attr_categry_list = $this->Product->find('list', array('conditions' => array('Product.product_type_id' => $this->params['url']['product_type_id']), 'fields' => array('Product.category_id')));
            $attr_cat_list = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.id' => $attr_categry_list), 'fields' => array('CategoryProduct.id', 'CategoryProduct.parent_id')));

            if (isset($attr_cat_list) && !empty($attr_cat_list)) {
                foreach ($attr_cat_list as $k => $v) {
                    if ($v['CategoryProduct']['parent_id'] == '0') {
                        $attr_cat_id[] = $v['CategoryProduct']['id'];
                    } else {
                        $attr_parent = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $v['CategoryProduct']['parent_id']), 'fields' => array('CategoryProduct.id')));
                        $attr_cat_id[] = $attr_parent['CategoryProduct']['id'];
                    }
                }
                $attr_cate_condition['and']['CategoryProduct.id'] = $attr_cat_id;
                $attr_cate = $this->CategoryProduct->find('all', array('conditions' => $attr_cate_condition));
                unset($attr_cate_condition);
            }
        }

        //推荐
        if (isset($this->params['url']['is_recommond']) && $this->params['url']['is_recommond'] != '-1') {
            $condition['and']['Product.recommand_flag'] = $this->params['url']['is_recommond'];
            $is_recommond = $this->params['url']['is_recommond'];
        }
        //推荐
        if (isset($this->params['url']['option_type_id']) && $this->params['url']['option_type_id'] != '-1') {
            $condition['and']['Product.option_type_id'] = $this->params['url']['option_type_id'];
            $option_type_id = $this->params['url']['option_type_id'];
        }
        //促销
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '-1') {
            $condition['and']['Product.status'] = $this->params['url']['status'];
        }
        if (isset($this->params['url']['promotion_status']) && $this->params['url']['promotion_status'] != '-1') {
            $condition['and']['Product.promotion_status'] = $this->params['url']['promotion_status'];
            $condition['and']['promotion_end >'] = DateTime;
        }

        //操作员
        if (isset($this->params['url']['operator_id']) && $this->params['url']['operator_id'] != '-1') {
            $condition['and']['Product.operator_id'] = $this->params['url']['operator_id'];
            $operator_id = $this->params['url']['operator_id'];
            $this->set('operator_id', $operator_id);
        }

        //上架
        if (isset($this->params['url']['forsale']) && $this->params['url']['forsale'] != '-1') {
            $condition['and']['Product.forsale'] = $this->params['url']['forsale'];
            $forsale = $this->params['url']['forsale'];
        }
        //关键字
        if (isset($this->params['url']['product_keywords']) && $this->params['url']['product_keywords'] != '') {
            $product_keyword = trim($this->params['url']['product_keywords']);
            $product_keyword = str_replace('_', '[_]', $product_keyword);
            $product_keyword = str_replace('%', '[%]', $product_keyword);
            if ($product_keyword != '') {
                $keyword = preg_split('#\s+#', $product_keyword);
                foreach ($keyword as $k => $v) {
                    $conditions_p18n['AND']['or'][0]['and'][]['ProductI18n.name like'] = "%$v%";
                    $conditions_p18n['AND']['or'][1]['and'][]['ProductI18n.meta_keywords  like'] = "%$v%";
                }
                $product18n_pid = $this->ProductI18n->find_product18n_pid($conditions_p18n); //model
                   $condition['AND']['OR']['Product.id'] = $product18n_pid;
                $condition['AND']['OR']['Product.code like'] = "%$v%";
            }
            $product_keywords = $this->params['url']['product_keywords'];
        }
        //属性搜索
        $attr_str = '';
        if (isset($_REQUEST['attr_value']) && $_REQUEST['attr_value'] != '0' && $_REQUEST['attr_value'] != '') {
            $attr_str = $_REQUEST['attr_value'];
            $attr_cate_condition = array();
            $attr_cate_condition['and']['CategoryProduct.type'] = 'P';
            $attr_cate_condition['and']['CategoryProductI18n.locale'] = $this->backend_locale;
            $attr_cate_condition['and']['CategoryProduct.parent_id'] = '0';
            $attr_arr = array();
            $attr_arr_k = array();
            $product_ids = array();

            $attr_arr = explode(',', $_REQUEST['attr_value']);
            foreach ($attr_arr as $k => $v) {
                $attr_arr_detail = explode(';', $v);
                if (sizeof($attr_arr_detail) == 2) {
                    if (in_array($attr_arr_detail[0], $attr_arr_k)) {
                        $attr_condition[$attr_arr_detail[0]]['and']['or'][$k]['ProductAttribute.attribute_id'] = $attr_arr_detail[0];
                        $attr_condition[$attr_arr_detail[0]]['and']['or'][$k]['ProductAttribute.attribute_value'] = $this->js_unescape($attr_arr_detail[1]);
                    } else {
                        $attr_arr_k[] = $attr_arr_detail[0];
                        $attr_condition[$attr_arr_detail[0]]['and']['or'][$k]['ProductAttribute.attribute_id'] = $attr_arr_detail[0];
                        $attr_condition[$attr_arr_detail[0]]['and']['or'][$k]['ProductAttribute.attribute_value'] = $this->js_unescape($attr_arr_detail[1]);
                    }
                }
            }
            $i = 0;
            foreach ($attr_condition as $attr_con) {
                $product_ids[$i] = $this->ProductAttribute->find('list', array('conditions' => $attr_con, 'fields' => 'ProductAttribute.product_id'));
                ++$i;
            }
            if (count($product_ids) >= 2) {
                $new_product_ids = array_intersect($product_ids[0], $product_ids[1]);
                for ($j = 2;$j < count($product_ids);++$j) {
                    $new_product_ids = array_intersect($new_product_ids, $product_ids[$j]);
                }
                $product_ids = $new_product_ids;
            } else {
                $product_ids = $product_ids[0];
            }
            $attr_str = $_REQUEST['attr_value'];
            $condition['Product.id'] = $product_ids;
            $attr_categry_list = $this->Product->find('list', array('conditions' => array('Product.id' => $product_ids), 'fields' => array('Product.category_id')));
            $attr_cat_list = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.id' => $attr_categry_list), 'fields' => array('CategoryProduct.id', 'CategoryProduct.parent_id')));
            if (isset($attr_cat_list) && !empty($attr_cat_list)) {
                foreach ($attr_cat_list as $k => $v) {
                    if ($v['CategoryProduct']['parent_id'] == '0') {
                        $attr_cat_id[] = $v['CategoryProduct']['id'];
                    } else {
                        $attr_parent = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $v['CategoryProduct']['parent_id']), 'fields' => array('CategoryProduct.id')));
                        $attr_cat_id[] = $attr_parent['CategoryProduct']['id'];
                    }
                }
                $attr_cate_condition['and']['CategoryProduct.id'] = $attr_cat_id;
                $attr_cate = $this->CategoryProduct->find('all', array('conditions' => $attr_cate_condition));
            }
            foreach ($attr_arr as $k => $v) {
                $attr_arr_detail = explode(';', $v);
                if (sizeof($attr_arr_detail) == 2) {
                    if (in_array($attr_arr_detail[0], $attr_arr_k)) {
                        $attr_condition[$attr_arr_detail[0]]['and']['or'][$k]['ProductAttribute.attribute_id'] = $attr_arr_detail[0];
                        $attr_condition[$attr_arr_detail[0]]['and']['or'][$k]['ProductAttribute.attribute_value'] = $this->js_unescape($attr_arr_detail[1]);
                    } else {
                        $attr_arr_k[] = $attr_arr_detail[0];
                        $attr_condition[$attr_arr_detail[0]]['and']['or'][$k]['ProductAttribute.attribute_id'] = $attr_arr_detail[0];
                        $attr_condition[$attr_arr_detail[0]]['and']['or'][$k]['ProductAttribute.attribute_value'] = $this->js_unescape($attr_arr_detail[1]);
                    }
                }
            }
        }
        $this->set('attr_cate', $attr_cate);
        $this->set('attr_value', $attr_str);
        //开始价格
        if (isset($this->params['url']['min_price']) && $this->params['url']['min_price'] != '') {
            $condition['and']['Product.shop_price >='] = $this->params['url']['min_price'];
            $min_price = $this->params['url']['min_price'];
        }
        //结束价格
        if (isset($this->params['url']['max_price']) && $this->params['url']['max_price'] != '') {
            $condition['and']['Product.shop_price <='] = $this->params['url']['max_price'];
            $max_price = $this->params['url']['max_price'];
        }
        //添加时间
        if (isset($this->params['url']['start_date']) && $this->params['url']['start_date'] != '') {
            $condition['and']['Product.created >='] = $this->params['url']['start_date'].' 00:00:00';
            $start_date = $this->params['url']['start_date'];
        }
        if (isset($this->params['url']['end_date']) && $this->params['url']['end_date'] != '') {
            $condition['and']['Product.created <='] = $this->params['url']['end_date'].' 23:59:59';
            $end_date = $this->params['url']['end_date'];
        }
        //修改时间
        if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
            $condition['and']['Product.last_update_time >='] = $this->params['url']['start_date_time'].' 00:00:00';
            $start_date_time = $this->params['url']['start_date_time'];
            $this->set('start_date_time', $start_date_time);
        }
        if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
            $condition['and']['Product.last_update_time <='] = $this->params['url']['end_date_time'].' 23:59:59';
            $end_date_time = $this->params['url']['end_date_time'];
            $this->set('end_date_time', $end_date_time);
        }

        //商品分类搜索
        $category_arr = array();
        if (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != '') {
            $category_arr = array();
            $category_arr_id = explode(',', $_REQUEST['category_id']);
            foreach ($category_arr_id as $k => $v) {
                if ($v != '') {
                    $categry_parent_list = $this->CategoryProduct->find('list', array('conditions' => array('CategoryProduct.parent_id' => $v), 'fields' => array('CategoryProduct.id')));
                    if (count($categry_parent_list) > 0) {
                        foreach ($categry_parent_list as $kk => $vv) {
                            $category_arr[] = $vv;
                        }
                    }
                    $category_arr[] = $v;
                }
            }
            $category_ids = array();
            if (in_array('-1', $category_arr)) {
                $code_categry_list = $this->Product->find('list', array('fields' => array('Product.category_id', 'Product.category_id'), 'group' => 'Product.category_id'));
                $categry_id_list = $this->CategoryProduct->find('list', array('conditions' => array('CategoryProduct.type' => 'P'), 'fields' => array('CategoryProduct.id', 'CategoryProduct.id')));
                $category_ids = array_diff($code_categry_list, $categry_id_list);
                foreach ($category_ids as $k => $v) {
                    $category_arr[] = $v;
                }
            }
            $condition['and']['Product.category_id'] = $category_arr;
        }
        if (isset($this->params['url']['export_act_flag']) && $this->params['url']['export_act_flag'] == 1) {
            if (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] !== 'forsale' && $this->configs['product_order'] !== 'category') {
                $order = 'Product.'.$this->configs['product_order'];
            } elseif (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] !== 'forsale' && $this->configs['product_order'] == 'category') {
                $order = 'Product.category_id,Product.id';
            } else {
                $order = 'Product.forsale desc,Product.id desc';
            }
            $this->search_act_result($condition, $order, 'search_result', 'product_export');
        }
        $this->set('category_arr', $category_arr);

        if (isset($_REQUEST['attr_cate_id']) && $_REQUEST['attr_cate_id'] != '') {
            $cat_parent_list = $this->CategoryProduct->find('list', array('conditions' => array('CategoryProduct.parent_id' => $_REQUEST['attr_cate_id']), 'fields' => array('CategoryProduct.id')));
            if (count($cat_parent_list) > 0) {
                foreach ($cat_parent_list as $kk => $vv) {
                    $cat_arr[] = $vv;
                }
            }
            $cat_arr[] = $_REQUEST['attr_cate_id'];
            $condition['and']['Product.category_id'] = $cat_arr;
            $this->set('attr_cate_sel', $_REQUEST['attr_cate_id']);
        }

        $this->Product->set_locale($this->backend_locale);
        $total = $this->Product->find('count', array('conditions' => $condition));//统计全部商品总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        //$sortClass="Product";
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'products','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Product');
        $this->Pagination->init($condition, $parameters, $options);
        $fields[] = 'Product.id';
        $fields[] = 'Product.code';
        $fields[] = 'Product.shop_price';
        $fields[] = 'Product.quantity';
        $fields[] = 'Product.recommand_flag';
        $fields[] = 'Product.forsale';
        $fields[] = 'ProductI18n.name';
        $fields[] = 'Product.option_type_id';
        $fields[] = 'operator_name';//最后编辑人名称
        $fields[] = 'last_update_time';//最后修改时间
        $fields[] = 'Product.purchase_price';//进货
        $fields[] = 'Product.brand_id'; //品牌名称
        $fields[] = 'Product.category_id'; //分类id
        $fields[] = 'Product.like_stat'; //
        $fields[] = 'Product.img_thumb'; //缩略图
        //排序
        if (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] !== 'forsale' && $this->configs['product_order'] !== 'category') {
            $product_list = $this->Product->find('all', array('conditions' => $condition, 'order' => 'Product.'.$this->configs['product_order'], 'fields' => $fields, 'limit' => $rownum, 'page' => $page));
        } elseif (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] !== 'forsale' && $this->configs['product_order'] == 'category') {
            $product_list = $this->Product->find('all', array('conditions' => $condition, 'order' => 'Product.category_id,Product.id', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));
        } else {
            $product_list = $this->Product->find('all', array('conditions' => $condition, 'order' => 'Product.forsale desc,Product.id desc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));
        }
        if (in_array('APP-API-WEBSERVICE', $this->apps)) {
            $ec_product_code = '';
            foreach ($product_list as $k => $v) {
                $ec_product_code .= $v['Product']['code'].'|';
            }
            if (!empty($ec_product_code)) {
                $ec_product_sku = array();
                $this->EcFlagWebservice->startup($this);
                $ec_product_sku = $this->EcFlagWebservice->GetProductInventory($ec_product_code);
                $this->set('ec_product_sku', $ec_product_sku);
            }
        }
        //品牌获取
        $brand_tree = $this->Brand->brand_tree($this->backend_locale);
        if (is_array($brand_tree)) {
            $brand_names = array();
            foreach ($brand_tree as $k => $v) {
                $brand_names[$v['Brand']['id']] = $v['Brand']['id'];
                $brand_names[$v['Brand']['id']] = $v['BrandI18n']['name'];
            }
            $this->set('brand_names', $brand_names);
        }
        //属性类型树
        $product_type_tree = $this->ProductType->product_type_tree($this->backend_locale);
        if (!in_array('/APP-PRODUCTS/', $this->apps)) {
            $this->set('no_upload_product', true);
        }
        //取出所有公共属性
        $this->Attribute->set_locale($this->backend_locale);
        $public_attr_ids = $this->ProductTypeAttribute->getattrids(0);
        $public_attr_info = array();
        $public_attr_list = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $public_attr_ids, 'Attribute.status' => 1)));
        foreach ($public_attr_list as $v) {
            $public_attr_info[$v['Attribute']['id']] = $v;
        }
        $p_ids = array();
        if (!empty($product_list)) {
            foreach ($product_list as $p) {
                $p_ids[] = $p['Product']['id'];
            }
        }
        $pat_ids = $this->Attribute->find('list', array('fields' => array('Attribute.id'), 'conditions' => array('Attribute.status' => 1, 'Attribute.id' => $public_attr_ids)));
        $attr_info = $this->ProductAttribute->product_list_format($p_ids, $pat_ids, $this->backend_locale);
        $this->set('public_attr_info', $public_attr_info);
        $this->set('attr_info', $attr_info);
        $this->set('page', $page);
        $this->set('product_list', $product_list);//商品列表
        $this->set('category_tree', $category_tree);//商品分类树pubile_attr_info
        $this->set('brand_tree', $brand_tree);//商品品牌树
        $this->set('product_type_tree', $product_type_tree);//属性类型树
        $this->set('category_id', $category_id);//索搜分类选中
        $this->set('brand_id', $brand_id);//索搜品牌选中
        $this->set('product_type_id', $product_type_id);//索搜属性类型选中
        $this->set('is_recommond', $is_recommond);//索搜属性类型选中
        $this->set('option_type_id', $option_type_id);//商品类型选中
        $this->set('forsale', $forsale);//上架
        $this->set('product_keywords', $product_keywords);//关键字
        $this->set('min_price', $min_price);//开始价格
        $this->set('max_price', $max_price);//结束价格
        $this->set('start_date', $start_date);//开始时间
        $this->set('end_date', $end_date);//结束时间
        $this->set('title_for_layout', $this->ld['manage_products'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        if (!empty($this->params['url'])) {
            $url = $this->params['url']['url'].'?';
            //$url="";
            foreach ($this->params['url'] as $k => $v) {
                if ($k == 'url') {
                } else {
                    $url .= $k.'='.$v.'&';
                }
            }
        }
        $_SESSION['index_url'] = $url;

        if (constant('Product') == 'AllInOne') {
            //分销平台产品线
            $this->loadModel('FenxiaoProductcat');
            $fenxiao_productcat_list = $this->FenxiaoProductcat->find('list', array('fields' => array('FenxiaoProductcat.id', 'FenxiaoProductcat.name')));
            $this->set('fenxiao_productcat_list', $fenxiao_productcat_list);
        }
    }

    public function getCate()
    {
        $result = array();
        if ($this->RequestHandler->isPost()) {
            if (isset($_REQUEST['attrval']) && $_REQUEST['attrval'] != '0') {
                $this->CategoryProduct->set_locale($this->backend_locale);
                $attr_categry_list = $this->Product->find('list', array('conditions' => array('Product.product_type_id' => $_REQUEST['attrval']), 'fields' => array('Product.category_id')));
                $attr_cat_list = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.id' => $attr_categry_list), 'fields' => array('CategoryProduct.id', 'CategoryProduct.parent_id')));
                if (isset($attr_cat_list) && !empty($attr_cat_list)) {
                    foreach ($attr_cat_list as $k => $v) {
                        if ($v['CategoryProduct']['parent_id'] == '0') {
                            $attr_cat_id[] = $v['CategoryProduct']['id'];
                        } else {
                            $attr_parent = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $v['CategoryProduct']['parent_id']), 'fields' => array('CategoryProduct.id')));
                            $attr_cat_id[] = $attr_parent['CategoryProduct']['id'];
                        }
                    }
                    $attr_cate_condition['and']['CategoryProduct.id'] = $attr_cat_id;
                    $attr_cate = $this->CategoryProduct->find('all', array('conditions' => $attr_cate_condition));
                }
                if (!empty($attr_cate)) {
                    foreach ($attr_cate as $k => $v) {
                        $result[$k]['id'] = $v['CategoryProduct']['id'];
                        $result[$k]['name'] = $v['CategoryProductI18n']['name'];
                    }
                }
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function update_package_qty()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if (!$this->operator_privilege('products_edit', false)) {
            die(json_encode(array('flag' => 2, 'content' => $this->ld['have_no_operation_perform'])));
        }
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        //查询库存是否可以修改
        $package_pid = $this->PackageProduct->find('first', array('conditions' => array('PackageProduct.id' => $id), 'fields' => 'PackageProduct.package_product_id,PackageProduct.package_product_qty'));
        if (!empty($package_pid)) {
            $qty = $this->Product->find('first', array('conditions' => array('Product.id' => $package_pid['PackageProduct']['package_product_id']), 'fields' => 'Product.quantity'));
            if ($val < $qty['Product']['quantity'] && $val > 0) {
                $request = $this->PackageProduct->updateAll(
                    array('package_product_qty' => "'".$val."'"),
                    array('id' => $id)
                );
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑套装商品id'.'.'.$id.':数量'.$val, $this->admin['id']);
                }
            } else {
                $val = $package_pid['PackageProduct']['package_product_qty'];
            }
            $result = array();
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            die(json_encode($result));
        }
    }

        /*
        添加商品页面选择商品分类
    */
    public function add()
    {
        $this->set('title_for_layout', $this->ld['add'].$this->ld['product'].' - '.$this->configs['shop_name']);
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['add'].$this->ld['product'],'url' => '');
    }

    /**
     *编辑商品信息 新增/编辑.
     *
     *@param int $id 输入商品ID
     */
    public function view($id = 0, $page = 1, $category_id = 0){
        //商品类型 0:普通商品，1:套装商品，2:销售属性
        $productType = 0;
        $pro_option_type_name = array($this->ld['ordinary'].$this->ld['product'],$this->ld['package_product'],$this->ld['sales_attribute'].$this->ld['product']);
        $this->set('pro_option_type_name', $pro_option_type_name);
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        if (empty($id) || $id == 0) {
            $this->operator_privilege('products_add');
            if (isset($_REQUEST['productType']) && $_REQUEST['productType'] != '') {
                switch ($_REQUEST['productType']) {
                    case 'p0':
                        $productType = 0;
                        break;
                    case 'p1':
                        $productType = 1;
                        break;
                    case 'p2':
                        $productType = 2;
                        break;
                    default:
                        $productType = 0;
                        break;
                }
            }
        } else {
            $this->operator_privilege('products_edit');
            //查找映射路径的内容
            $conditions = array('Route.controller' => 'products','Route.action' => 'view','Route.model_id' => $id);
            $content = $this->Route->find('first', array('conditions' => $conditions));
            $this->set('routecontent', $content);
        }
        $this->set('id', $id);
        $this->set('title_for_layout', $this->ld['edit_product'].'-'.$this->ld['manage_products'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        if (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != 0) {
            $this->set('fromcategory_id', $_REQUEST['category_id']);
        }
        $is_sku = false;//记录商品是否为子商品
        if ($this->RequestHandler->isPost()) {
            //操作员日志判断是新增还是编辑
            $is_new = $this->Product->check_code_unique($this->data['Product']['code']);
            $page = isset($_POST['page']) ? $_POST['page'] : '1';
            $_SESSION['product_relation_product'] = isset($_SESSION['product_relation_product']) ? $_SESSION['product_relation_product'] : array();
            $_SESSION['product_relation_articles'] = isset($_SESSION['product_relation_articles']) ? $_SESSION['product_relation_articles'] : array();
            //获取商品信息
            $product_info = $this->Product->localeformat($id);
            $this->data['Product']['purchase_price'] = !empty($this->data['Product']['purchase_price']) ? $this->data['Product']['purchase_price'] : '0';//进货价
            $this->data['Product']['market_price'] = !empty($this->data['Product']['market_price']) ? $this->data['Product']['market_price'] : '0';//市场价
            $this->data['Product']['shop_price'] = !empty($this->data['Product']['shop_price']) ? $this->data['Product']['shop_price'] : '0';//本店价
            $this->data['Product']['point'] = !empty($this->data['Product']['point']) ? $this->data['Product']['point'] : '0';//积分
            $this->data['Product']['promotion_price'] = !empty($this->data['Product']['promotion_price']) ? $this->data['Product']['promotion_price'] : '0';//促俏价
            //判断该商品是否为销售属性主商品
            $is_sku_pro = $this->SkuProduct->check_sku_pro($this->data['Product']['code']);
            if (!$is_sku_pro) {
                $this->data['Product']['quantity'] = !empty($this->data['Product']['quantity']) ? $this->data['Product']['quantity'] : '0';//库存
            } else {
                $this->data['Product']['quantity'] = '0';//库存
            }
            $this->data['Product']['warn_quantity'] = !empty($this->data['Product']['warn_quantity']) ? $this->data['Product']['warn_quantity'] : '0';//库存警告
            //$this->data['Product']['sale_stat']=!empty($this->data['Product']['sale_stat']) ? $this->data['Product']['sale_stat']: "0";//销售次数
            //属性处理
            $this->data['Product']['product_type_id'] = !empty($this->data['Product']['product_type_id']) ? $this->data['Product']['product_type_id'] : '0';//属性处理
            //$this->data['Product']['product_type_id']=isset($_POST['product_sku_type'])&&$_POST['product_sku_type']!=''?$_POST['product_sku_type']:0;
            //checkbox数据处理
            $this->data['Product']['alone'] = !empty($this->data['Product']['alone']) ? $this->data['Product']['alone'] : '0';//能作为普通商品销售
            $this->data['Product']['forsale'] = !empty($this->data['Product']['forsale']) ? $this->data['Product']['forsale'] : '0';//上架
            $this->data['Product']['recommand_flag'] = !empty($this->data['Product']['recommand_flag']) ? $this->data['Product']['recommand_flag'] : '0';//加入推荐
            $this->data['Product']['promotion_status'] = !empty($this->data['Product']['promotion_status']) ? $this->data['Product']['promotion_status'] : '0';//促销价状态
            $this->data['Product']['promotion_start'] = !empty($_REQUEST['start_date']) ? $_REQUEST['start_date'] : null;//促俏时间
            $this->data['Product']['promotion_end'] = !empty($_REQUEST['end_date']) ? $_REQUEST['end_date'] : null;//促俏时间
            $this->data['Product']['min_buy'] = !empty($this->data['Product']['min_buy']) ? $this->data['Product']['min_buy'] : '1';//最小数量
            $this->data['Product']['max_buy'] = !empty($this->data['Product']['max_buy']) ? $this->data['Product']['max_buy'] : '100';//最大数量
            //重量处理
            $this->data['Product']['weight'] = !empty($this->data['Product']['weight']) ? $this->data['Product']['weight'] * $_POST['weight_unit'] : '0';//重量
            //商品类型
            $this->data['Product']['option_type_id'] = !empty($this->data['Product']['option_type_id']) ? $this->data['Product']['option_type_id'] : '0';
            $this->data['Product']['operator_id'] = $this->admin['id'];
            $this->data['Product']['last_update_time'] = date('Y-m-d H:i:s');
            //保存商品基本数据
            if (!empty($this->data['Product']['id']) && $this->data['Product']['id'] != '0') {
                $log_flag = $this->Operator->find('first', array('fields' => array('Operator.log_flag'), 'conditions' => array('Operator.name' => $this->admin['name'])));
                if ($log_flag['Operator']['log_flag'] == '1') {
                    $this->Product->save(array('last_update_time' => date('Y-m-d H:i:s'), 'operator_name' => $this->admin['name'], 'id' => $id, 'operator_id' => $this->admin['id']));
                }
                if ($product_info['Product']['quantity'] != $this->data['Product']['quantity'] || $product_info['Product']['shop_price'] != $this->data['Product']['shop_price'] || $product_info['ProductI18n'][0]['name'] != $this->data['ProductI18n'][0]['name'] || $product_info['Product']['purchase_price'] != $this->data['Product']['purchase_price']) {
                    $this->data['Product']['code'] = !empty($this->data['Product']['code']) ? trim($this->data['Product']['code']) : $this->generate_product_code($id);//设置商品ID
                    if (isset($this->configs['products-str'])) {
                        if ($this->configs['products-str'] == 'upper') {
                            $this->data['Product']['code'] = strtoupper($this->data['Product']['code']);
                        } elseif ($this->configs['products-str'] == 'lowe') {
                            $this->data['Product']['code'] = strtolower($this->data['Product']['code']);
                        }
                    }
//					$log_flag=$this->Operator->find("first",array("fields"=>array("Operator.log_flag"),"conditions"=>array("Operator.name"=>$this->admin['name'])));
//					if($log_flag['Operator']['log_flag']=="1"){
                    $this->data['Product']['last_update_time'] = date('Y-m-d H:i:s');
//					$this->data["Product"]["operator_name"]=$this->admin['name'];
//					$this->data["Product"]["operator_id"]=$this->admin['id'];
//					}

                    $this->Product->save(array('Product' => $this->data['Product']));
                    $id = $this->data['Product']['id'];
                } else {
                    $this->data['Product']['code'] = !empty($this->data['Product']['code']) ? trim($this->data['Product']['code']) : $this->generate_product_code($id);//设置商品ID
                        if (isset($this->configs['products-str'])) {
                            if ($this->configs['products-str'] == 'upper') {
                                $this->data['Product']['code'] = strtoupper($this->data['Product']['code']);
                            } elseif ($this->configs['products-str'] == 'lowe') {
                                $this->data['Product']['code'] = strtolower($this->data['Product']['code']);
                            }
                        }
                    $this->Product->save(array('Product' => $this->data['Product']));
                    $id = $this->data['Product']['id'];
                }
            } else {
                $log_code = isset($this->data['Product']['code']) && trim($this->data['Product']['code']) != '' ? $this->data['Product']['code'] : '';
                if ((!empty($this->data['Product']['code']) && trim($this->data['Product']['code']) == '') || (empty($this->data['Product']['code']))) {
                    $this->data['Product']['code'] = isset($this->configs['products_code_prefix']) ? 'sv'.$this->configs['products_code_prefix'] : 'sv'.time();
                }
                if (isset($this->configs['product_str'])) {
                    if ($this->configs['product_str'] == 'upper') {
                        $this->data['Product']['code'] = strtoupper($this->data['Product']['code']);
                    } elseif ($this->configs['product_str'] == 'lowe') {
                        $this->data['Product']['code'] = strtolower($this->data['Product']['code']);
                    }
                }
                $this->Product->saveAll(array('Product' => $this->data['Product']));
                $id = $this->Product->id;
                $this->data['Product']['id'] = $id;
                if ($log_code == '') {
                    $this->data['Product']['code'] = $this->generate_product_code($id);//设置商品ID
                    $this->Product->saveAll(array('Product' => $this->data['Product']));
                }
            }
            //保存路径控制器的基本数据
            if (!empty($this->data['Route'])) {
                //判断添加的内容是否为空
                    $conditions = array('Route.controller' => 'products','Route.action' => 'view','Route.model_id' => $id);
                $routeurl = $this->Route->find('first', array('conditions' => $conditions));
                $condit = array('Route.url' => $this->data['Route']['url']);//用来判断添加的url不能重复
                    $rurl = $this->Route->find('first', array('conditions' => $condit));
                    //pr($condit);die;
                    if (empty($rurl)) {
                        //判断里面是否添加相同的数据
                        if (empty($id)) {
                            if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                                //foreach($this->data[])
                                $this->data['Route']['controller'] = 'products';
                                $this->data['Route']['url'] = $this->data['Route']['url'];
                                $this->data['Route']['action'] = 'view';
                                $this->data['Route']['model_id'] = $id;
                                $this->Route->save(array('Route' => $this->data['Route']));
                            }
                        } else {
                            if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                                $this->data['Route']['controller'] = 'products';
                                $this->data['Route']['url'] = $this->data['Route']['url'];
                                $this->data['Route']['action'] = 'view';
                                $this->data['Route']['model_id'] = $id;
                                $this->data['Route']['id'] = $routeurl['Route']['id'];
                                $this->Route->save(array('Route' => $this->data['Route']));
                            }
                        }
                    }
            }

            foreach ($this->data['ProductI18n'] as $k => $v) {
                $v['id'] = !empty($product_info['ProductI18n'][$v['locale']]['id']) ? $product_info['ProductI18n'][$v['locale']]['id'] : '';
                $v['product_id'] = $id;
                //$v["description"] = addslashes($v["description"]);
                $this->ProductI18n->save(array('ProductI18n' => $v));
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            foreach ($this->data['ProductDownload'] as $k => $v) {
                $v['id'] = !empty($product_info['ProductDownload'][$v['locale']]['id']) ? $product_info['ProductDownload'][$v['locale']]['id'] : '';
                $v['product_id'] = $id;
                //$v["description"] = addslashes($v["description"]);
                $this->ProductDownload->save(array('ProductDownload' => $v));
            }
            if (isset($_POST['volume_number'])) {
                $this->ProductVolume->deleteAll(array('ProductVolume.product_id' => $id));
            //保存批发价相关数据
            foreach ($_POST['volume_number'] as $k => $v) {
                if (empty($v)) {
                    continue;
                }
                $pv[$k]['product_id'] = $id;
                $pv[$k]['volume_number'] = $v;
                $pv[$k]['volume_price'] = $_POST['volume_price'][$k];
            }
                if (isset($pv)) {
                    $this->ProductVolume->saveAll($pv);
                }
            }

            //扩展分类
            if (!empty($_POST['other_cat'])) {
                $other_cat = $_POST['other_cat'];
                $this->ProductsCategory->deleteAll(array('ProductsCategory.product_id' => $id));
                foreach ($other_cat as $k => $v) {
                    if ($v != 0) {
                        $productcategory_arr = array(
                                'category_id' => $v,
                                'product_id' => $id,
                        );
                        $this->ProductsCategory->saveAll(array('ProductsCategory' => $productcategory_arr));
                    }
                }
            }
            //材料
            if (!empty($_POST['material_code'])) {
                $material_code = $_POST['material_code'];
                $this->ProductMaterial->deleteAll(array('ProductMaterial.product_code' => $this->data['Product']['code']));
                foreach ($material_code as $k => $v) {
                    if ($v != '0') {
                        $quantity = 0;
                        if (!empty($_POST['material_qty'])) {
                            $quantity = $_POST['material_qty'][$k];
                        }
                        $product_material_arr = array(
                            'product_material_code' => $v,
                            'product_code' => $this->data['Product']['code'],
                            'quantity' => $quantity,
                        );

                        $this->ProductMaterial->saveAll(array('ProductMaterial' => $product_material_arr));
                    }
                }
            }
            //相册保存开始
            $product_gallery_data = $this->data['product_gallery_data'];
            $gallery = true;
            foreach ($product_gallery_data as $k => $v) {
                if ($gallery && isset($v['ProductGallery']['img_thumb'])) {
                    $now_image_arr = array(
                        'product_id' => $id,
                        'img_thumb' => $v['ProductGallery']['img_thumb'],
                        'img_detail' => $v['ProductGallery']['img_detail'],
                        'img_original' => $v['ProductGallery']['img_original'],
                        'img_big' => $v['ProductGallery']['img_big'],
                    );
                    $this->Product->save(array('Product' => $now_image_arr));
                    $gallery = false;
                }
                $v['ProductGallery']['product_id'] = $id;
                if (!isset($v['ProductGallery']['id']) || $v['ProductGallery']['id'] == '') {
                    if (isset($v['ProductGallery']['img_thumb']) && $v['ProductGallery']['img_thumb'] != '') {
                        $v['ProductGallery']['orderby'] = (isset($v['ProductGallery']['orderby']) && $v['ProductGallery']['orderby'] != '') ? $v['ProductGallery']['orderby'] : $k + 1;
                        $this->ProductGallery->saveAll(array('ProductGallery' => $v['ProductGallery'])); //关联保存
                        $v['ProductGallery']['id'] = $this->ProductGallery->getLastInsertId();
                    }
                } else {
                    $v['ProductGallery']['orderby'] = (isset($v['ProductGallery']['orderby']) && $v['ProductGallery']['orderby'] != '') ? $v['ProductGallery']['orderby'] : $k + 1;
                    $this->ProductGallery->saveAll(array('ProductGallery' => $v['ProductGallery'])); //关联保存
                }
                $v['ProductGalleryI18n'] = empty($v['ProductGalleryI18n']) ? array() : $v['ProductGalleryI18n'];
                foreach ($v['ProductGalleryI18n'] as $kk => $vv) {
                    if (isset($vv['description']) && $vv['description'] != '') {
                        $this->ProductGalleryI18n->deleteAll(array('product_gallery_id' => $v['ProductGallery']['id'], 'locale' => $vv['locale']));
                        $product_gallery_i18n_array = array(
                            'product_gallery_id' => $v['ProductGallery']['id'],
                            'description' => $vv['description'],
                            'locale' => $vv['locale'],  
                            
                        );
                        $this->ProductGalleryI18n->saveAll(array('ProductGalleryI18n' => $product_gallery_i18n_array));
                    }
                }
            }
            //商品属性值保存
            $this->ProductAttribute->deleteAll(array('ProductAttribute.product_id' => $id));//,$cascade = false
            //判断 传过来的值是否有值 
            $attr_id_list = empty($this->params['form']['attr_id_list']) ? array() : $this->params['form']['attr_id_list'];
            if (!empty($attr_id_list)) {   
              //如果有值  循环值 
                foreach ($attr_id_list as $key => $attr_id) { 
                      foreach ($this->front_locales as $lok => $lov) {   
                        $localek = $lov['Language']['locale'];//获得 chi  eng
                        //判断语言有没有填写 没有写 就 不保存  
                        if (empty($this->params['form']['attr_value_list'][$key][$localek]) || $this->params['form']['attr_value_list'][$key][$localek] == '') {
                            continue;
                        }
                     
                        if (isset($this->params['form']['attr_value_upload_size']) && isset($this->params['form']['attr_value_upload_size'][$key][$localek])) {
                            $value = $this->params['form']['attr_value_list'][$key][$localek].':'.$this->params['form']['attr_value_upload_size'][$key][$localek];
                        } else {
                        	  //如果不存在 就
                            $value = $this->params['form']['attr_value_list'][$key][$localek];
                        }    
                        $pattr = array(
                            'product_id' => $id,
                            'locale' => $this->params['form']['attr_locale_list'][$key][$localek],
                            'orderby' => isset($this->params['form']['attr_orderby_list'][$key][$localek]) ? $this->params['form']['attr_orderby_list'][$key][$localek] : '50',
                            'attribute_id' => $attr_id[$localek],
                            'attribute_value' => $value,
                            'attribute_price' => empty($this->params['form']['attr_price_list'][$key][$localek]) ? 0 : $this->params['form']['attr_price_list'][$key][$localek],
                            'attribute_color_css' => empty($this->params['form']['attr_color_css_list'][$key][$localek]) ? '' : $this->params['form']['attr_color_css_list'][$key][$localek],
                            'attribute_shell_num' => empty($this->params['form']['attr_shell_num_list'][$key][$localek]) ? '' : $this->params['form']['attr_shell_num_list'][$key][$localek],
                            'attribute_image_path' => empty($this->params['form']['attr_image_path_list'][$key][$localek]) ? 0 : $this->params['form']['attr_image_path_list'][$key][$localek],
                            'attribute_back_image_path' => empty($this->params['form']['attr_back_image_path_list'][$key][$localek]) ? 0 : $this->params['form']['attr_back_image_path_list'][$key][$localek],
                            'attribute_related_image_path' => empty($this->params['form']['attr_related_image_path_list'][$key][$localek]) ? 0 : $this->params['form']['attr_related_image_path_list'][$key][$localek],
                            'attribute_related_back_image_path' => empty($this->params['form']['attr_related_back_image_path_list'][$key][$localek]) ? 0 : $this->params['form']['attr_related_back_image_path_list'][$key][$localek],

                        );
                        $this->ProductAttribute->saveAll(array('ProductAttribute' => $pattr));
                    }
                }
            }
            /*
                销售属性保存
            */
            //删除原有关联属性
            $this->SkuProduct->deleteAll(array('SkuProduct.product_code' => $this->data['Product']['code']));
            $quantity = $this->data['Product']['quantity'];//记录库存
            if (isset($this->data['SkuProduct']) && sizeof($this->data['SkuProduct']) > 0) {
                //pr($this->data['SkuProduct']);
                $quantity = 0;
                foreach ($this->data['SkuProduct']['sku_product_code'] as $k => $v) {
                    $sku_data = array();
                    $sku_data['SkuProduct']['product_code'] = $this->data['Product']['code'];
                    $sku_data['SkuProduct']['sku_product_code'] = $v;
                    $sku_data['SkuProduct']['price'] = isset($this->data['SkuProduct']['price'][$k]) && trim($this->data['SkuProduct']['price'][$k]) != '' ? $this->data['SkuProduct']['price'][$k] : '0';
                    $this->SkuProduct->saveAll($sku_data);

                    $quantity += isset($this->data['SkuProduct']['quantity'][$k]) && trim($this->data['SkuProduct']['quantity'][$k]) != '' ? $this->data['SkuProduct']['quantity'][$k] : '0';
                }
                //调整商品库存
                $this->Product->save(array('Product' => array('id' => $id, 'quantity' => $quantity)));
            }
            /* 套装商品保存 */
            //删除原有套装商品
            $this->PackageProduct->deleteAll(array('PackageProduct.product_id' => $id));
            if (isset($this->data['PackageProduct']) && sizeof($this->data['PackageProduct']) > 0) {
                //pr($this->data['PackageProduct']);die;
                foreach ($this->data['PackageProduct'] as $pk => $pv) {
                    $pkg_data = array();
                    $pkg_data['package_product_id'] = $pv['package_product_id'];
                    $pkg_data['package_product_code'] = $pv['package_product_code'];
                    $pkg_data['package_product_name'] = $pv['package_product_name'];
                    $pkg_data['package_product_qty'] = $pv['package_product_qty'];
                    $pkg_data['orderby'] = $pv['orderby'];
                    $pkg_data['product_code'] = $this->data['Product']['code'];
                    $pkg_data['product_id'] = $id;
                    //pr($pkg_data);
                    $this->PackageProduct->saveAll($pkg_data);
                }
            }
            $clone_attr_id_list = empty($this->params['form']['clone_attr_id_list']) ? array() : $this->params['form']['clone_attr_id_list'];
            if (!empty($clone_attr_id_list)) {
                foreach ($clone_attr_id_list as $key => $attr_id) {
                    foreach ($this->front_locales as $lok => $lov) {
                        $localek = $lov['Language']['locale'];
                        if (empty($this->params['form']['clone_attr_value_list'][$key][$localek]) || $this->params['form']['clone_attr_value_list'][$key][$localek] == '') {
                            continue;
                        }
                        if (isset($this->params['form']['clone_attr_value_upload_size']) && isset($this->params['form']['clone_attr_value_upload_size'][$key][$localek])) {
                            $value = $this->params['form']['clone_attr_value_list'][$key][$localek].':'.$this->params['form']['clone_attr_value_upload_size'][$key][$localek];
                        } else {
                            $value = $this->params['form']['clone_attr_value_list'][$key][$localek];
                        }
                        $pattr = array(
                            'product_id' => $id,
                            'locale' => $this->params['form']['clone_attr_locale_list'][$key][$localek],
                            'orderby' => isset($this->params['form']['clone_attr_orderby_list'][$key][$localek]) ? $this->params['form']['clone_attr_orderby_list'][$key][$localek] : '',
                            'attribute_id' => $attr_id[$localek],
                            'attribute_value' => $value,
                            'attribute_price' => empty($this->params['form']['clone_attr_price_list'][$key][$localek]) ? 0 : $this->params['form']['clone_attr_price_list'][$key][$localek],
                            'attribute_color_css' => empty($this->params['form']['attr_color_css_list'][$key][$localek]) ? '' : $this->params['form']['attr_color_css_list'][$key][$localek],
                            'attribute_shell_num' => empty($this->params['form']['attr_shell_num_list'][$key][$localek]) ? '' : $this->params['form']['attr_shell_num_list'][$key][$localek],
                            'attribute_image_path' => empty($this->params['form']['attr_image_path_list'][$key][$localek]) ? 0 : $this->params['form']['attr_image_path_list'][$key][$localek],
                            'attribute_back_image_path' => empty($this->params['form']['attr_back_image_path_list'][$key][$localek]) ? 0 : $this->params['form']['attr_back_image_path_list'][$key][$localek],
                            'attribute_related_image_path' => empty($this->params['form']['attr_related_image_path_list'][$key][$localek]) ? 0 : $this->params['form']['attr_related_image_path_list'][$key][$localek],
                            'attribute_related_back_image_path' => empty($this->params['form']['attr_related_back_image_path_list'][$key][$localek]) ? 0 : $this->params['form']['attr_related_back_image_path_list'][$key][$localek],
                        //	"attribute_image_path"=>empty($this->params['form']['clone_attr_image_path_list'][$key])?0:$this->params['form']['clone_attr_image_path_list'][$key]
                        );

                        $this->ProductAttribute->saveAll(array('ProductAttribute' => $pattr));
                    }
                }
            }

            //先删除所有关联
            $this->ProductRelation->deleteAll(array('product_id' => 0));
            $this->ProductRelation->deleteAll(array('product_id' => $id));
            //保存关联
            foreach ($_SESSION['product_relation_product'] as $k => $v) {
                $product_relation_product_array_format = array(
                    'product_id' => $id,
                    'related_product_id' => $v['ProductRelation']['related_product_id'],
                    'is_double' => $v['ProductRelation']['is_double'],
                    'orderby' => $v['ProductRelation']['orderby'],
                );
                $this->ProductRelation->saveAll(array('ProductRelation' => $product_relation_product_array_format));
            }

            //先删除所有关联
            $this->ProductArticle->deleteAll(array('product_id' => 0));
            $this->ProductArticle->deleteAll(array('product_id' => $id));
            //保存关联
            foreach ($_SESSION['product_relation_articles'] as $k => $v) {
                $product_relation_articles_array_format = array(
                    'product_id' => $id,
                    'article_id' => $v['ProductArticle']['article_id'],
                    'is_double' => $v['ProductArticle']['is_double'],
                    'orderby' => $v['ProductArticle']['orderby'],
                );
                $this->ProductArticle->saveAll(array('ProductArticle' => $product_relation_articles_array_format));
            }
            //标签
            if (isset($this->data['TagI18n']) && !empty($this->data['TagI18n'])) {
                $old_tag_ids = $this->Tag->find('list', array('conditions' => array('Tag.type_id' => $id, 'Tag.type' => 'P'), 'fields' => 'Tag.id'));
                $this->TagI18n->deleteAll(array('tag_id' => $old_tag_ids));
                $this->Tag->deleteAll(array('Tag.type_id' => $id, 'Tag.type' => 'P'));
                foreach ($this->data['TagI18n'] as $tk => $t) {
                    if (isset($t['name']) && sizeof($t['name']) > 0) {
                        $product_tagi18n = array();
                        foreach ($t['name']  as $tnk => $tn) {
                            $product_tag = array();
                            $product_tag['id'] = '';
                            $product_tag['type_id'] = $id;
                            $product_tag['type'] = 'P';
                            $this->Tag->save($product_tag);
                            $tag_id = $this->Tag->getLastInsertId();
                            $product_tagi18n[$tnk]['id'] = '';
                            $product_tagi18n[$tnk]['tag_id'] = $tag_id;
                            $product_tagi18n[$tnk]['name'] = $tn;
                            $product_tagi18n[$tnk]['locale'] = $t['locale'];
                        }
                        $this->TagI18n->saveAll($product_tagi18n);
                    }
                }
            }
            //淘宝销售渠道保存
            if (isset($this->data['TaobaoItem']) && !empty($this->data['TaobaoItem']) && constant('Product') == 'AllInOne') {
                //加载淘宝相关模型
                $this->loadModel('TaobaoShop');
                $this->loadModel('TaobaoItemType');
                $this->loadModel('TaobaoItem');
                $this->loadModel('TaobaoSellercat');
                $this->loadModel('TaobaoProduct');
                $this->loadModel('TaobaoDeliveryTemplateRelation');
                $this->loadModel('TaobaoDeliveryTemplate');
                $this->loadModel('TaobaoItemimg');
                $this->Product->set_locale($this->backend_locale);
                $product_data = $this->Product->find('first', array('conditions' => array('Product.id' => $id)));
                foreach ($this->data['TaobaoItem'] as $k => $v) {
                    if (isset($v['is_have']) && $v['is_have'] == 1) { //勾选了

                            $taobao_item_data = $this->TaobaoItem->find('first', array('conditions' => array('outer_id' => $_REQUEST['old_code'], 'nick' => $v['shop_nick'])));
                        $taobao_item_data = $taobao_item_data['TaobaoItem'];

                            //上下架
                            if (isset($v['taobao_onsale']) && $v['taobao_onsale'] == 1) {
                                $taobao_item_data['approve_status'] = 'onsale';
                            } else {
                                $taobao_item_data['approve_status'] = 'instock';
                            }
                            //自定义商品类型
                            if (isset($v['taobao_type']) && !empty($v['taobao_type'])) {
                                $taobao_item_data['type_id'] = $v['taobao_type'];
                                $taobao_type_data = $this->TaobaoItemType->find('first', array('conditions' => array('id' => $v['taobao_type'])));

                                //分类属性
                                $taobao_item_data['props'] = $taobao_type_data['TaobaoItemType']['props'];
                                $taobao_item_data['sku_properties'] = $taobao_type_data['TaobaoItemType']['sku_properties'];
//								$taobao_item_data['input_str'] = $taobao_type_data['TaobaoItemType']['input_str'];
//								$taobao_item_data['input_pids'] = $taobao_type_data['TaobaoItemType']['input_pids'];

                                $input_arr = $this->TaobaoItemType->formart_input($taobao_type_data['TaobaoItemType']['input_pids'], $taobao_type_data['TaobaoItemType']['input_str'], $product_data['Product']['code']);
//								pr($input_arr);die;
                                $taobao_item_data['input_pids'] = $input_arr['pid'];
                                $taobao_item_data['input_str'] = $input_arr['str'];
                                $taobao_item_data['type'] = $taobao_type_data['TaobaoItemType']['type'];//发布类型
                                $taobao_item_data['stuff_status'] = $taobao_type_data['TaobaoItemType']['stuff_status'];//新旧程度
                                $taobao_item_data['location_state'] = $taobao_type_data['TaobaoItemType']['location_state'];//所在地省份
                                $taobao_item_data['location_city'] = $taobao_type_data['TaobaoItemType']['location_city'];//所在地城市
                                $taobao_item_data['cid'] = $taobao_type_data['TaobaoItemType']['taobao_cid'];//商品所属淘宝类目
//								$taobao_item_data["approve_status"] = $taobao_type_data["TaobaoItemType"]["approve_status"];//上下架状态
//								$taobao_item_data["freight_payer"] = $taobao_type_data["TaobaoItemType"]["freight_payer"];//邮费承担者
                                $taobao_item_data['valid_thru'] = $taobao_type_data['TaobaoItemType']['valid_thru'];//有效期
                                $taobao_item_data['has_invoice'] = $taobao_type_data['TaobaoItemType']['has_invoice'];//发票
                                $taobao_item_data['has_warranty'] = $taobao_type_data['TaobaoItemType']['has_warranty'];//保修
                                $taobao_item_data['auto_repost'] = $taobao_type_data['TaobaoItemType']['auto_repost'];//自动重发
                                $taobao_item_data['has_showcase'] = $taobao_type_data['TaobaoItemType']['has_showcase'];//橱窗推荐
                                $taobao_item_data['has_discount'] = $taobao_type_data['TaobaoItemType']['has_discount'];//会员打折

                                $taobao_item_data['list_time'] = $taobao_type_data['TaobaoItemType']['list_time'];//上架时间
                                $taobao_item_data['auction_point'] = $taobao_type_data['TaobaoItemType']['auction_point'];//积分返点比例
                            } else {
                                //								echo "<script>alert('".$v['shop_nick']." 淘宝商品类型不能为空');history.go(-1);</script>";
//								die();
                            }

                            //运费
                            $taobao_item_data['freight_payer'] = $v['freight_payer'];
                        $taobao_item_data['post_fee'] = empty($v['post_fee']) ? 5 : $v['post_fee'];
                        $taobao_item_data['express_fee'] = empty($v['express_fee']) ? 5 : $v['express_fee'];
                        $taobao_item_data['ems_fee'] = empty($v['ems_fee']) ? 5 : $v['ems_fee'];

                        $postage = $this->TaobaoDeliveryTemplateRelation->find('first', array('conditions' => array('delivery_template_id' => $v['postage_id'], 'nick' => $v['shop_nick'])));
                        if (!empty($postage)) {
                            $taobao_item_data['postage_id'] = $postage['TaobaoDeliveryTemplateRelation']['template_id'];//邮费模板
                        } else {
                            $taobao_item_data['postage_id'] = 0;
                        }

                            //自定义类目
                            if (!empty($v['taobao_item_seller_cids'])) {
                                $taobao_item_data['seller_cids'] = implode(',', $v['taobao_item_seller_cids']);
                            } else {
                                $taobao_item_data['seller_cids'] = '';
                            }
                        $taobao_item_data['num'] = $product_data['Product']['quantity'];//商品数量; 淘宝临时表不改，库存更新定时器能检测到
                            $taobao_item_data['outer_id'] = $product_data['Product']['code'];//货号
                            $taobao_item_data['price'] = $product_data['Product']['shop_price'];//价格;
                            $taobao_item_data['title'] = $product_data['ProductI18n']['name'];//宝贝标题
                            $taobao_item_data['nick'] = $v['shop_nick'];//昵称
                            $taobao_item_data['pic_path'] = $product_data['Product']['img_original'];
//							$desc_middle = $product_data["ProductI18n"]["description"];
//							$desc_top = '';
//							$desc_footer = '';
//
//							$desc_top.= empty($category_info["CategoryI18n"]["top_detail"])?"":$category_info["CategoryI18n"]["top_detail"];
//							$desc_top.= empty($category_info2["CategoryI18n"]["top_detail"])?"":$category_info2["CategoryI18n"]["top_detail"];
//							$desc_top.= empty($category_info3["CategoryI18n"]["top_detail"])?"":$category_info3["CategoryI18n"]["top_detail"];
//
//							$desc_footer.= empty($category_info["CategoryI18n"]["foot_detail"])?"":$category_info["CategoryI18n"]["foot_detail"];
//							$desc_footer.= empty($category_info2["CategoryI18n"]["foot_detail"])?"":$category_info2["CategoryI18n"]["foot_detail"];
//							$desc_footer.= empty($category_info3["CategoryI18n"]["foot_detail"])?"":$category_info3["CategoryI18n"]["foot_detail"];
//
//							$taobao_item_data["desc"] = $desc_top.$desc_middle.$desc_footer; //宝贝描述

                            $taobao_item_data['desc'] = $product_data['ProductI18n']['description']; //宝贝描述
                            if (strlen($taobao_item_data['desc']) < 5) {
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/><script>alert('淘宝发布商品描述长度必须大于5个字符');history.go(-1);</script>";
                                die();
                            }
                        $taobao_item_data['modified'] = date('Y-m-d H:i:s');
                        $taobao_item_data['taobao_modified'] = date('Y-m-d H:i:s');

                        $taobao_item_data['upload_img_status'] = (isset($_REQUEST['upload_img_status']) && $_REQUEST['upload_img_status'] == 1) ? 0 : 1;
                        $taobao_item_data['error_count'] = 0;
                        $taobao_item_data['is_new'] = 0;
                        $taobao_item_data['is_update_product'] = 1;
                        $taobao_item_data['is_update_taobao'] = 0;

                        if (!empty($taobao_item_data['id'])) {
                            $this->TaobaoItem->save(array('TaobaoItem' => $taobao_item_data));
                            $item_id = $taobao_item_data['id'];
                        } else {
                            if ($this->TaobaoItem->getCount($v['shop_nick']) < $this->TaobaoShop->getItemLimit($v['shop_nick']) || $this->TaobaoShop->getItemLimit($v['shop_nick']) == 0) {
                                $this->TaobaoItem->saveAll(array('TaobaoItem' => $taobao_item_data));
                                $item_id = $this->TaobaoItem->getLastInsertId();
                            } else {
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/><script>alert('".$v['shop_nick']." 商品发布数量超过上限，无法发布');history.go(-1);</script>";
                                die();
                            }
                        }
                    }
                }
                //产品发布店铺
                if (!empty($this->data['TaobaoItem']['product_publish'])) {
                    $taobaoproduct = $this->TaobaoProduct->find('first', array('conditions' => array('TaobaoProduct.code' => $product_data['Product']['code'])));
                    if (empty($taobaoproduct)) {
                        $product_publish['code'] = $product_data['Product']['code'];
                        $product_publish['nick'] = $this->data['TaobaoItem']['product_publish'];
                        $this->TaobaoProduct->saveAll(array('TaobaoProduct' => $product_publish));
                    } else {
                        $this->TaobaoProduct->updateAll(array('TaobaoProduct.nick' => "'".$this->data['TaobaoItem']['product_publish']."'"), array('TaobaoProduct.code' => $product_data['Product']['code']));
                    }
                } else {
                    $this->TaobaoProduct->deleteAll(array('TaobaoProduct.code' => $product_data['Product']['code']));
                }
            }
            if (isset($this->data['JingdongWare']) && !empty($this->data['JingdongWare']) && in_array('APP-JINGDONG', $this->apps) && constant('Product') == 'AllInOne') {
                //	$this->loadModel('JingdongShop');
                $this->loadModel('JingdongWare');
                $this->Product->set_locale($this->backend_locale);
                $product_data = $this->Product->find('first', array('conditions' => array('Product.id' => $id)));
                foreach ($this->data['JingdongWare'] as $k => $v) {
                    if (isset($v['is_have']) && $v['is_have'] == 1) { //勾选了
                            $jingdong_item_data = $this->JingdongWare->find('first', array('conditions' => array('item_num' => $_REQUEST['old_code'])));
                        if (isset($jingdong_item_data['JingdongWare'])) {
                            $jingdong_item_data = $jingdong_item_data['JingdongWare'];
                        }
                            //上下架
                            if (isset($v['taobao_onsale']) && $v['taobao_onsale'] == 1) {
                                $jingdong_item_data['ware_state'] = 'ON_SHELF';
                            } else {
                                $jingdong_item_data['ware_state'] = 'NEVER_UP';
                            }
                        $jingdong_item_data['stock_num'] = $product_data['Product']['quantity'];//商品数量; 淘宝临时表不改，库存更新定时器能检测到
                            $jingdong_item_data['item_num'] = $product_data['Product']['code'];//货号
                            $jingdong_item_data['jd_price'] = $product_data['Product']['shop_price'];//价格;
                            $jingdong_item_data['market_price'] = $product_data['Product']['market_price'];//市场价
                            $jingdong_item_data['cost_price'] = $product_data['Product']['purchase_price'];//进货价
                            $jingdong_item_data['title'] = $product_data['ProductI18n']['name'];

                        if (!empty($product_data['Product']['img_original']) && !stristr($product_data['Product']['img_original'], IMG_HOST)) {
                            $jingdong_item_data['logo'] = IMG_HOST.$product_data['Product']['img_original'];
                        } elseif (!empty($product_data['Product']['img_original'])) {
                            $jingdong_item_data['logo'] = $product_data['Product']['img_original'];
                        }
                        $jingdong_item_data['weight'] = $product_data['Product']['weight'];
                        $jingdong_item_data['desc'] = $product_data['ProductI18n']['description']; //宝贝描述
                            if (strlen($jingdong_item_data['desc']) < 5) {
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/><script>alert('京东发布商品描述长度必须大于5个字符');history.go(-1);</script>";
                                die();
                            }

                        $jingdong_item_data['vender_id'] = '28782';
                        $jingdong_item_data['shop_id'] = '27986';
                        $jingdong_item_data['creator'] = $this->admin['name'];
                        $jingdong_item_data['status'] = 'VALID';

//							$jingdong_item_data["ware_id"] = 0;
//							$jingdong_item_data["transport_id"] = 0;//运费模版
                            $jingdong_item_data['modified'] = date('Y-m-d H:i:s');
                        $jingdong_item_data['error_count'] = 0;
                        $jingdong_item_data['is_update_jindong'] = 0;
                        if (!empty($jingdong_item_data['id'])) {
                            $this->JingdongWare->save(array('JingdongWare' => $jingdong_item_data));
                            //	$item_id = $jingdong_item_data["id"];
                        } else {
                            $this->JingdongWare->saveAll(array('JingdongWare' => $jingdong_item_data));
                            //	$item_id = $this->JingdongWare->getLastInsertId();
                        }
                    }
                }
            }

            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $action = '';
                if (isset($is_new)) {
                    if ($is_new) {
                        $action = $this->ld['edit_product'];
                    } else {
                        $action = $this->ld['add_product'];
                    }
                }
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$action.':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }

        $category_tree = $this->CategoryProduct->tree('P', $this->locale);//分类树
        $this->CategoryType->set_locale($this->backend_locale);
        $attribute_type_tree = $this->ProductType->find('all', array('conditions' => array('ProductType.status' => 1, 'ProductType.id !=' => 0)));
        $category_type_tree = $this->CategoryType->tree();// 类目树
        $category_tree_articles = $this->CategoryArticle->tree('all', $this->locale);//文章分类树
        $brand_tree = $this->Brand->brand_tree($this->backend_locale);//品牌获取
        $material_tree = $this->Material->find('all', array('conditions' => array('Material.status' => 1, 'MaterialI18n.locale' => $this->backend_locale)));
        $this->set('category_tree', $category_tree);
        $this->set('attribute_type_tree', $attribute_type_tree);
        $this->set('category_type_tree', $category_type_tree);//商品类目树
        $this->set('category_tree_articles', $category_tree_articles);//文章分类树
        $this->set('brand_tree', $brand_tree);//商品品牌树
        $this->set('material_tree', $material_tree);//商品品牌树
        /*
            获取商品信息
        */
        $product_info = $this->Product->localeformat($id);

        //商品重量数字处理
        $product_info['Product']['weight'] = isset($product_info['Product']['weight']) ? $product_info['Product']['weight'] : 0;
        $product_info['Product']['weight'] = ($product_info['Product']['weight'] >= 1) ? $product_info['Product']['weight'] : ($product_info['Product']['weight'] / 0.001);
        $this->set('product_info', $product_info);//获取商品信息

        //属性组列表
        $product_type_list = $this->product_type_list(isset($product_info['Product']['product_type_id']) ? $product_info['Product']['product_type_id'] : 0);
        //商品属性
                $products_attr_html = $this->Attribute->build_attr_html(isset($product_info['Product']['product_type_id']) ? $product_info['Product']['product_type_id'] : 0, $id);
        $this->set('product_type_list', $product_type_list);//商品属性类型列表
        $this->set('products_attr_html', $products_attr_html);//商品属性HTML内容

        //统计360图片数量
        $product_Rotation_img_count = 0;
        if (isset($product_info['Product']['code']) && $product_info['Product']['code'] != '') {
            $Rotation_img_file_addr = WWW_ROOT.'media/360Rotation/'.$product_info['Product']['code'].'/big';
            if (is_dir($Rotation_img_file_addr)) {
                $rotation_img_data = $this->traverse($Rotation_img_file_addr);
                $product_Rotation_img_count = !empty($rotation_img_data) ? sizeof($rotation_img_data) : 0;
            }
        }
        $this->set('product_Rotation_img_count', $product_Rotation_img_count);

        /*
            商品关联数据查询 start
        */
        if (!empty($product_info['Product']['id'])) {
            //获取套装商品信息
            $package_products = $this->PackageProduct->find_package_product($id);
            $show_package_products = '';
            if (!empty($package_products)) {
                foreach ($package_products as $pk => $pv) {
                    $package_product = $this->Product->find('first', array('conditions' => array('Product.id' => $pv['PackageProduct']['package_product_id']), 'fields' => 'Product.img_thumb,Product.shop_price,Product.quantity'));
                    if (!empty($package_products)) {
                        $package_products[$pk]['PackageProduct']['img'] = $package_product['Product']['img_thumb'];
                        $package_products[$pk]['PackageProduct']['price'] = $package_product['Product']['shop_price'];
                        $package_products[$pk]['PackageProduct']['quantity'] = $package_product['Product']['quantity'];
                        $show_package_products .= $pv['PackageProduct']['package_product_id'].';';
                    }
                }
            }
            $this->set('package_products', $package_products);
            $this->set('show_package_products', $show_package_products);

            //商品关联商品信息
            $product_relation_product = $this->ProductRelation->get_product_relation_product($id);//获取商品关联商品信息
            $product_relation_product_format = array();
            foreach ($product_relation_product as $k => $v) {
                $v['ProductRelation']['id'] = $v['ProductRelation']['related_product_id'];
                $product_relation_product_format[$v['ProductRelation']['related_product_id']] = $v;
            }
            $_SESSION['product_relation_product'] = $product_relation_product_format;
            $this->set('product_relation_product', $product_relation_product_format);

            //处理商品关联文章
            $product_relation_articles = $this->ProductArticle->get_product_relation_articles($id);//获取商品关联文章信息
            $product_relation_articles_format = array();
            foreach ($product_relation_articles as $k => $v) {
                $v['ProductArticle']['id'] = $v['ProductArticle']['article_id'];
                $product_relation_articles_format[$v['ProductArticle']['article_id']] = $v;
            }
            $_SESSION['product_relation_articles'] = $product_relation_articles_format;
            $this->set('product_relation_articles', $product_relation_articles_format);//商品关联文章信息

            //扩展分类
            $other_category_list = $this->ProductsCategory->find('all', array('conditions' => array('product_id' => $id)));
            $this->set('other_category_list', $other_category_list);
            //使用材料和单位
            $unit_list = $this->Material->find('all', array('conditions' => array('Material.status' => 1), 'fields' => 'Material.code,Material.unit'));
            $product_material_list = $this->ProductMaterial->find('all', array('conditions' => array('product_code' => $product_info['Product']['code'])));
            if (count($product_material_list) > 0) {
                foreach ($product_material_list as $pmk => $pmv) {
                    foreach ($unit_list as $uk => $uv) {
                        if ($uv['Material']['code'] == $pmv['ProductMaterial']['product_material_code']) {
                            $product_material_list[$pmk]['ProductMaterial']['unit'] = $uv['Material']['unit'];
                        }
                    }
                }
            }
            //pr($product_material_list);die;
            $this->set('product_material_list', $product_material_list);
            //批发价数据
            $pv_infos = $this->ProductVolume->find('all', array('conditions' => array('ProductVolume.product_id' => $id), 'order' => 'ProductVolume.volume_number'));
            $this->set('pv_infos', $pv_infos);

            //商品相册列表获取
            $product_gallery = $this->ProductGallery->product_gallery_format($id);
            $this->set('product_gallery', $product_gallery);

            $is_sku = $this->SkuProduct->check_sku($product_info['Product']['code']);
        }
        /*
            商品关联数据查询 end
        */
        $this->set('is_sku', $is_sku);

        //if(in_array('APP-TAG',$this->apps)){
            $tag_infos = $this->Tag->localeformat($id, 'P');
        $this->set('tag_infos', $tag_infos);
        //}
        $this->set('option_type_id', isset($product_info['Product']['option_type_id']) ? $product_info['Product']['option_type_id'] : $productType);//当前商品类型
        $this->set('product_category_id', isset($product_info['Product']['category_id']) ? $product_info['Product']['category_id'] : 0);//分类选中
        $this->set('product_category_type_id', isset($product_info['Product']['category_type_id']) ? $product_info['Product']['category_type_id'] : 0);//类目选中
        $this->set('product_brand_id', isset($product_info['Product']['brand_id']) ? $product_info['Product']['brand_id'] : 0);//品牌选中
        $this->set('category_id', 0);//关联分类选中
        $this->set('brand_id', 0);//关联品牌选中
        $this->set('article_category_id', 0);//关联文章分类选中
        $this->set('start_date', isset($product_info['Product']['promotion_start']) ? $product_info['Product']['promotion_start'] : '');//促俏时间
        $this->set('end_date', isset($product_info['Product']['promotion_end']) ? $product_info['Product']['promotion_end'] : '');//促俏时间
        $this->set('weight_unit', isset($product_info['Product']['weight']) && $product_info['Product']['weight'] >= 1 ? '1' : '0.001');//重量下拉框 处理

        //图片空间开始
        $this->PhotoCategory->set_locale($this->locale);
        $photo_category_info = $this->PhotoCategory->find('all', array('fields' => array('PhotoCategory.id', 'PhotoCategoryI18n.name')));//获取图片空间分类
        $this->set('photo_category_info', $photo_category_info);//图片空间分类信息
        //获取图片空间  图片
        $condition = '';
        $total = $this->PhotoCategoryGallery->find('count', array('conditions' => $condition));//统计全部图片总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'PhotoCategoryGallery';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        //$sortClass="Product";
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'products','action' => 'view','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'PhotoCategoryGallery');
        $this->Pagination->init($condition, $parameters, $options);
        $fields[] = 'id';
        $fields[] = 'name';
        $fields[] = 'img_small';
        $photo_category_gallery_info = $this->PhotoCategoryGallery->find('all', array('conditions' => $condition, 'order' => 'id desc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));

        $this->set('photo_category_gallery_info', $photo_category_gallery_info);

        if (isset($product_info['ProductI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$product_info['ProductI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_product'],'url' => '');
        }

        //淘宝销售渠道
        if (constant('Product') == 'AllInOne') {
            //加载淘宝相关模型
            $this->loadModel('TaobaoShop');
            $this->loadModel('TaobaoItemType');
            $this->loadModel('TaobaoItem');
            $this->loadModel('TaobaoSellercat');
            $this->loadModel('TaobaoProduct');
            $this->loadModel('TaobaoDeliveryTemplate');
            $this->loadModel('TaobaoDeliveryTemplateRelation');
            //查有效淘宝店铺
            $taobao_shops = $this->TaobaoShop->find('all', array('conditions' => array('status' => 1), 'fields' => array('id', 'nick', 'title', 'is_taobao_mall')));
            //查淘宝商品
            $taobao_items = array();
            foreach ($taobao_shops as $k => $taobao_shop) {
                $taobao_item = array();
                if (!empty($product_info['Product']['code'])) {
                    $taobao_item = $this->TaobaoItem->find('first', array('conditions' => array('TaobaoItem.outer_id' => $product_info['Product']['code'], 'TaobaoItem.nick' => $taobao_shop['TaobaoShop']['nick']), 'fields' => array('id', 'nick', 'num_iid', 'outer_id', 'cid', 'type_id', 'approve_status', 'seller_cids', 'freight_payer', 'post_fee', 'express_fee', 'ems_fee', 'postage_id')));
                }
                $taobao_items[$k] = $taobao_item;
                $taobao_items[$k]['shop_title'] = $taobao_shop['TaobaoShop']['title'];
                $taobao_items[$k]['shop_nick'] = $taobao_shop['TaobaoShop']['nick'];
                $taobao_item_seller_cids_list = isset($taobao_item['TaobaoItem']['seller_cids']) ? trim($taobao_item['TaobaoItem']['seller_cids'], ',') : '';
                $taobao_items[$k]['seller_cids_list'] = explode(',', $taobao_item_seller_cids_list);
                $taobao_sellercat_tree = $this->TaobaoSellercat->tree(array('nick' => $taobao_shop['TaobaoShop']['nick']));
                $taobao_items[$k]['taobao_sellercat_tree'] = $taobao_sellercat_tree;
                if (!empty($taobao_item)) {
                    $post = $this->TaobaoDeliveryTemplateRelation->find('first', array('conditions' => array('TaobaoDeliveryTemplateRelation.template_id' => $taobao_item['TaobaoItem']['postage_id'])));
                    $taobao_items[$k]['TaobaoItem']['postage_id'] = $post['TaobaoDeliveryTemplateRelation']['delivery_template_id'];
                }
            }
            //运费模板
            $delivery_templates = $this->TaobaoDeliveryTemplate->find('all', array('conditions' => array('status' => 1)));
            $this->set('delivery_templates', $delivery_templates);
            //查淘宝商品自定义类型
            $taobao_types_arr = $this->TaobaoItemType->find('all', array('conditions' => array('status' => 1), 'fields' => array('id', 'name', 'taobao_cid')));
            if (!empty($product_info['Product']['code'])) {
                $product_publish = $this->TaobaoProduct->find('first', array('conditions' => array('code' => $product_info['Product']['code'])));
                $this->set('product_publish', $product_publish['TaobaoProduct']['nick']);
            }
            $t_mall = array();
            foreach ($taobao_shops as $k => $v) {
                if ($v['TaobaoShop']['is_taobao_mall'] == 1) {
                    $t_mall[] = $v['TaobaoShop']['nick'];
                }
            }
            $this->set('t_mall', $t_mall);
            $this->set('taobao_items', $taobao_items);
            $this->set('taobao_shops', $taobao_shops);
            $this->set('taobao_types_arr', $taobao_types_arr);
        }
        //京东销售渠道
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('JingdongShop');
            $this->loadModel('JingdongWare');
            $jingdong_shops = $this->JingdongShop->find('all', array('conditions' => array('status' => 1)));
            $jingdong_item = array();
            if (!empty($product_info['Product']['code'])) {
                $jingdong_item = $this->JingdongWare->find('first', array('conditions' => array('JingdongWare.item_num' => $product_info['Product']['code'])));
            }
            $jingdong_item['shop_title'] = '艾婷家居京东店';
            $jingdong_item['shop_nick'] = '艾婷家居京东店';
            $this->set('jingdong_item', $jingdong_item);
            $this->set('jingdong_shops', $jingdong_shops);
        }
        //烟波商品类目
        $yb_cid = $this->InformationResource->information_formated('yb_cid', 'chi', false);
        if (!empty($yb_cid)) {
            $this->set('yb_cid', $yb_cid['yb_cid']);
            if (!empty($product_info['Product']['code'])) {
                $product_cid = substr($product_info['Product']['code'], 4, 2);
                $this->set('product_cid', $product_cid);
            }
        }
        if (in_array('APP-WEIBO', $this->apps)) {
            $weithm = ClassRegistry::init('WeiboThm')->find('all', array('conditions' => array('WeiboThm.status' => 1), 'fields' => array('WeiboThm.weibo_template_name', 'id')));
            $this->set('weithm', $weithm);
        }
        $file_types = isset($this->configs['files_format']) ? $this->configs['files_format'] : '';
        $this->set('file_types', $file_types);
    }

    public function product_log_save($id)
    {
        $log_flag = $this->Operator->find('first', array('fields' => array('Operator.log_flag'), 'conditions' => array('Operator.id' => $this->admin['id'])));
        if (isset($log_flag['Operator']['log_flag']) && $log_flag['Operator']['log_flag'] == '1') {
            $now = date('Y-m-d H:i:s');
            $operator_name = $this->admin['name'];
            $operator_id = $this->admin['id'];
            $this->Product->updateAll(array('Product.operator_name' => "'".$operator_name."'", 'Product.last_update_time' => "'".$now."'", 'Product.operator_id' => $operator_id),
                                    array('Product.id' => $id)
                                    );
          //  $this->Product->save(array("last_update_time"=>date("Y-m-d H:i:s"),"operator_name"=>$this->admin['name'],"id"=>$id,"operator_id"=>$this->admin['id']));
        }
    }

    /**
     *选图片空间中的图片用.
     */
    public function select_photo_galleries()
    {
        $condition = '';
        if (isset($this->params['url']['photo_category_id']) && $this->params['url']['photo_category_id'] != '0') {
            $condition['photo_category_id'] = $this->params['url']['photo_category_id'];
        }
        if (isset($_REQUEST['photo_key_word']) && $_REQUEST['photo_key_word'] != '') {
            $photo_key_word = trim($_REQUEST['photo_key_word']);
            $condition['name like'] = "%$photo_key_word%";
        }
        $total = $this->PhotoCategoryGallery->find('count', array('conditions' => $condition));//统计全部图片总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'PhotoCategoryGallery';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        //$sortClass="Product";
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'products','action' => 'select_photo_galleries','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'PhotoCategoryGallery');
        $this->Pagination->init($condition, $parameters, $options);
        $fields[] = 'id';
        $fields[] = 'name';
        $fields[] = 'img_small';
        $photo_category_gallery_info = $this->PhotoCategoryGallery->find('all', array('conditions' => $condition, 'order' => 'id desc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));
        $this->set('photo_category_gallery_info', $photo_category_gallery_info);
        $this->layout = 'ajax';
        Configure::write('debug', 0);
    }

    /**
     *详细页属性方法.
     *
     *@param int $id 属性ID
     *@param string $product_type 属性类型编号
     */
    public function get_attr($product_id = 0, $product_type = 0)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';

        $this->loadModel('Language');

        $this->Language->getinfo();
        $this->backend_locales = $this->Language->info['backend_locales'];
        $this->front_locales = $this->Language->info['front_locales'];
        $lan_count = sizeof($this->backend_locales);

        $this->set('front_locales', $this->front_locales);
        $this->set('lan_count', $lan_count);

        $product_id = empty($product_id) ? 0 : intval($product_id);
        $product_type = empty($product_type) ? 0 : intval($product_type);
        //$attr_html=$this->Attribute->build_attr_html($product_type,$product_id);

        $attr_hasOne['ProductAttribute'] = array(
                      'className' => 'ProductAttribute',
                      'conditions' => 'ProductAttribute.product_id='.$product_id,
                      'dependent' => true,
                      'foreignKey' => 'attribute_id',
                    );
        $this->Attribute->bindModel(array('hasOne' => $attr_hasOne));
        $attr_ids = $this->ProductTypeAttribute->getattrids(array($product_type, 0));
        $attr = $this->Attribute->get_attr_list($attr_ids, $product_id, $this->backend_locale);

        $lan_count = sizeof($this->backend_locales);
        foreach ($attr as $key => $val) {
            if (!empty($val['ProductAttribute'])) {
                if ($lan_count != sizeof($val['ProductAttribute'])) {
                    $data_log_locale = array();
                    foreach ($val['ProductAttribute'] as $kk => $vv) {
                        $data_log_locale[] = $vv['locale'];
                    }
                    foreach ($this->front_locales as $k => $v) {
                        if (!in_array($v['Language']['locale'], $data_log_locale)) {
                            $data_log = $val['ProductAttribute'][0];
                            unset($data_log['created']);
                            unset($data_log['modified']);
                            $data_log['locale'] = $v['Language']['locale'];
                            $data_log['product_type_attribute_value'] = '';
                            $attr[$key]['ProductAttribute'][] = $data_log;
                        }
                    }
                }
            }
        }
        $this->set('attr_data', $attr);
    }

    /**
     *生成产品货号用.
     *
     *@param int $product_id 商品ID
     */
    public function generate_product_code($product_id)
    {
        $products_code_prefix = isset($this->configs['products_code_prefix']) ? $this->configs['products_code_prefix'] : 'sv';
        $product_code = $products_code_prefix.str_repeat('0', 6 - strlen($product_id)).$product_id;
        $product_info = $this->Product->find('all', array('conditions' => array('Product.code' => $product_code, 'Product.id !=' => $product_id), 'fields' => 'Product.id'));
        $code_list = array();
        foreach ($product_info as $k => $v) {
            if (isset($v['Product']['code'])) {
                $code_list[$k] = $v['Product']['code'];
            }
        }
        if (in_array($product_code, $code_list)) {
            $max = pow(10, strlen($code_list[0]) - strlen($product_code) + 1) - 1;
            $new_sn = $product_code.mt_rand(0, $max);
            while (in_array($new_sn, $code_list)) {
                $new_sn = $product_code.mt_rand(0, $max);
            }
            $product_code = $new_sn;
        }

        return $product_code;
    }

    /**
     *商品类型列表.
     *
     *@param string $selected 选择的内容
     */
    public function product_type_list($selected = 0)
    {
        $condition = '';
        $this->ProductType->set_locale($this->backend_locale);
        $product_type_info = $this->ProductType->find('all', array('conditions' => array('ProductType.status' => 1, 'ProductType.id !=' => 0), 'fields' => array('ProductType.id', 'ProductTypeI18n.name')));

        $product_type_info_formated = array();
        if (is_array($product_type_info)) {
            foreach ($product_type_info as $k => $v) {
                $product_type_info_formated[$k]['ProductType']['name'] = $v['ProductTypeI18n']['name'];
                $product_type_info_formated[$k]['ProductType']['id'] = $v['ProductType']['id'];
            }
        }
        $lst = '';
        foreach ($product_type_info_formated as $k => $v) {
            $lst .= '<option value='.$v['ProductType']['id'].'';
            $lst .= ($selected == $v['ProductType']['id']) ? ' selected=true' : '';
            $lst .= '>'.htmlspecialchars($v['ProductType']['name']).'</option>';
        }

        return $lst;
    }

    /**
     *删除商品关联商品.
     *
     *@param int $id 商品ID
     *@param int $product_id 关联的商品ID
     */
    public function drop_product_relation_product($id, $product_id)
    {
        $result['flag'] = 1;//2 失败 1成功
        $result['content'] = $this->ld['unknown_reasons'];
        unset($_SESSION['product_relation_product'][$id]);
        $content_array = array();
        foreach ($_SESSION['product_relation_product'] as $k => $v) {
            $content_array[] = $v;
        }
        $result['content'] = $content_array;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *删除商品关联文章.
     *
     *@param int $id 商品ID
     *@param int $product_id 关联的商品ID
     */
    public function drop_product_relation_article($id, $product_id)
    {
        $result['flag'] = 1;//2 失败 1成功
        $result['content'] = $this->ld['deleted_success'];
        unset($_SESSION['product_relation_articles'][$id]);
        $content_array = array();
        foreach ($_SESSION['product_relation_articles'] as $k => $v) {
            $content_array[] = $v;
        }
        $result['content'] = $content_array;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *搜索套装商品供AJAX使用.
     */
    public function search_package_products()
    {
        $condition = '';
        //设置返回初始参数
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['no_products_result'];
        //搜索条件
        $category_id = empty($_REQUEST['category_id']) ? '0' : $_REQUEST['category_id'];//商品分类ID
        $brand_id = empty($_REQUEST['brand_id']) ? '0' : $_REQUEST['brand_id'];//商品品牌ID
        $product_keyword = empty($_REQUEST['product_keyword']) ? '' : trim($_REQUEST['product_keyword']);//关键字
        $productid = empty($_REQUEST['productid']) ? 0 : $_REQUEST['productid'];//商品品牌ID
        if (isset($_REQUEST['min_price']) && !empty($_REQUEST['min_price'])) {
            $condition['and']['Product.shop_price >='] = $_REQUEST['min_price'];
        }
        if (isset($_REQUEST['max_price']) && !empty($_REQUEST['max_price'])) {
            $condition['and']['Product.shop_price <='] = $_REQUEST['max_price'];
        }
        //设置语言
        $this->Product->set_locale($this->backend_locale);
        //初始化条件
        $condition['and']['Product.forsale'] = '1';
        $condition['and']['Product.status'] = '1';
        $condition['and']['Product.quantity >'] = 0;
        $condition['and']['ProductI18n.locale'] = $this->backend_locale;
        if ($product_keyword != '') {
            $keyword = preg_split('#\s+#', $product_keyword);
            foreach ($keyword as $k => $v) {
                $conditions_p18n['AND']['or'][0]['and'][]['ProductI18n.name like'] = "%$v%";
                $conditions_p18n['AND']['or'][1]['and'][]['ProductI18n.meta_keywords  like'] = "%$v%";
            }
            $product18n_pid = $this->ProductI18n->find_product18n_pid($conditions_p18n); //model
            $condition['AND']['OR']['Product.id'] = $product18n_pid;
            $condition['AND']['OR']['Product.code like'] = "%$v%";
        }
        if ($category_id != '0') {
            $condition['and']['Product.category_id'] = $category_id;
        }
        if ($brand_id != '0') {
            $condition['and']['Product.brand_id'] = $brand_id;
        }
        if ($productid != 0) {
            $condition['and']['Product.id !='] = $productid;
        }
        $fields[] = 'Product.id';
        $fields[] = 'Product.code';
        $fields[] = 'ProductI18n.name';
        $fields[] = 'Product.img_thumb';
        $fields[] = 'Product.shop_price';
        $fields[] = 'Product.quantity';
        $product_tree = $this->Product->find('all', array('conditions' => $condition, 'order' => 'Product.id desc', 'fields' => $fields));
        if (count($product_tree) > 0) {
            $result['flag'] = 1;
            $result['content'] = $product_tree;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *搜索商品供AJAX使用.
     */
    public function searchProducts()
    {
        $condition = '';
        //设置返回初始参数
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['no_products_result'];
        //搜索条件
        $category_id = empty($_REQUEST['category_id']) ? '0' : $_REQUEST['category_id'];//商品分类ID
        $brand_id = empty($_REQUEST['brand_id']) ? '0' : $_REQUEST['brand_id'];//商品品牌ID
        $product_keyword = empty($_REQUEST['product_keyword']) ? '' : trim($_REQUEST['product_keyword']);//关键字
        $productid = empty($_REQUEST['productid']) ? 0 : $_REQUEST['productid'];//商品品牌ID
        if (isset($_REQUEST['min_price']) && !empty($_REQUEST['min_price'])) {
            $condition['and']['Product.shop_price >='] = $_REQUEST['min_price'];
        }
        if (isset($_REQUEST['max_price']) && !empty($_REQUEST['max_price'])) {
            $condition['and']['Product.shop_price <='] = $_REQUEST['max_price'];
        }
        //设置语言
        $this->Product->set_locale($this->backend_locale);
        //初始化条件
        $condition['and']['Product.forsale'] = '1';
        $condition['and']['Product.status'] = '1';
 
 

        if ($product_keyword != '') {
            $keyword = preg_split('#\s+#', $product_keyword);
            foreach ($keyword as $k => $v) {
                $conditions_p18n['AND']['or'][0]['and'][]['ProductI18n.name like'] = "%$v%";
                $conditions_p18n['AND']['or'][1]['and'][]['ProductI18n.meta_keywords  like'] = "%$v%";
            }
            $product18n_pid = $this->ProductI18n->find_product18n_pid($conditions_p18n); //model
            $condition['AND']['OR']['Product.id'] = $product18n_pid;
            $condition['AND']['OR']['Product.code like'] = "%$v%";
        }
        if ($category_id != '0') {
            $condition['and']['Product.category_id'] = $category_id;
        }
        if ($brand_id != '0') {
            $condition['and']['Product.brand_id'] = $brand_id;
        }
        if ($productid != 0) {
            $condition['and']['Product.id !='] = $productid;
        }
        $fields[] = 'Product.id';
        $fields[] = 'Product.code';
        $fields[] = 'ProductI18n.name';
        $fields[] = 'Product.img_thumb';
        $fields[] = 'Product.shop_price';
        $fields[] = 'Product.quantity';
        $product_tree = $this->Product->find('all', array('conditions' => $condition, 'order' => 'Product.id desc', 'fields' => $fields));
        if (count($product_tree) > 0) {
            $result['flag'] = 1;
            $result['content'] = $product_tree;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *搜索文章供AJAX使用.
     */
    public function searchArticles()
    {
        //设置返回初始参数
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['search_no_articles'];
        //搜索条件
        $article_category_id = $_REQUEST['article_category_id'];//文章分类ID
        $article_keyword = $_REQUEST['article_keyword'];//关键字
        //设置语言
        $this->Article->set_locale($this->locale);
        //初始化条件
        $condition['and']['Article.status ='] = '1';
        if ($article_keyword != '') {
            $condition['and']['or']['ArticleI18n.title like'] = "%$article_keyword%";
            $condition['and']['or']['ArticleI18n.meta_keywords like'] = "%$article_keyword%";
        }
        if ($article_category_id != '0') {
            $condition['and']['Article.category_id'] = $article_category_id;
        }
        $fields[] = 'Article.id';
        $fields[] = 'ArticleI18n.title';
        $article_tree = $this->Article->find('all', array('conditions' => $condition, 'order' => 'Article.id desc', 'fields' => $fields));
        if (count($article_tree) > 0) {
            $result['flag'] = 1;
            $result['content'] = $article_tree;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *编辑页 关联商品 添加.
     */
    public function add_product_relation_product()
    {
        //设置返回初始参数
        $product_select = array();
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['unknown_reasons'];
        $product_id = $_REQUEST['product_id'];
        //$product_select = $_REQUEST["product_select"];
        $product_select = explode(',', $_REQUEST['product_select']);
        $is_single_value = $_REQUEST['is_single_value'];
        foreach ($product_select as $k => $v) {
            $this->ProductRelation->deleteAll(array('product_id' => $product_id, 'related_product_id' => $v));
            $linkproduct_info = array('ProductRelation' => array('product_id' => $product_id,'id' => $v,'related_product_id' => $v,'is_double' => $is_single_value,'orderby' => '50'));
            $this->Product->set_locale($this->backend_locale);
            $product_info = $this->Product->find('first', array('conditions' => array('Product.id' => $linkproduct_info['ProductRelation']['related_product_id']), 'fields' => array('Product.id', 'Product.code', 'ProductI18n.name')));
            $linkproduct_info['ProductRelation']['name'] = $product_info['Product']['code'].'--'.$product_info['ProductI18n']['name'].'--['.($linkproduct_info['ProductRelation']['is_double'] == 1 ? $this->ld['each_other_ralation'] : $this->ld['unidirectional']).']';
            $_SESSION['product_relation_product'][$v] = $linkproduct_info;
        }
        $result['flag'] = 1;//2 失败 1成功
        $content_array = array();
        foreach ($_SESSION['product_relation_product'] as $k => $v) {
            $content_array[] = $v;
        }
        $result['content'] = $content_array;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *编辑页 关联文章 添加.
     */
    public function add_product_relation_article()
    {
        //设置返回初始参数
        $article_select = array();
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['unknown_reasons'];
        $product_id = $_REQUEST['product_id'];
        //$article_select = $_REQUEST["article_select"];
        $article_select = explode(',', $_REQUEST['article_select']);
        $is_single2_value = $_REQUEST['is_single2_value'];
        //$_SESSION["product_relation_articles"] = array();
        foreach ($article_select as $k => $v) {
            $this->ProductArticle->deleteAll(array('product_id' => $product_id, 'article_id' => $v));
            $link_article_info = array('ProductArticle' => array('product_id' => $product_id,'article_id' => $v,'id' => $v,'is_double' => $is_single2_value,'orderby' => '50'));
            $this->Article->set_locale($this->locale);
            $article_info = $this->Article->find('first', array('conditions' => array('Article.id' => $link_article_info['ProductArticle']['article_id']), 'fields' => array('Article.id', 'ArticleI18n.title')));
            $link_article_info['ProductArticle']['title'] = $article_info['ArticleI18n']['title'].'--['.($link_article_info['ProductArticle']['is_double'] == 1 ? $this->ld['each_other_ralation'] : $this->ld['unidirectional']).']';
            $_SESSION['product_relation_articles'][$v] = $link_article_info;
        }
        $result['flag'] = 1;//2 失败 1成功
        $content_array = array();
        foreach ($_SESSION['product_relation_articles'] as $k => $v) {
            $content_array[] = $v;
        }
        $result['content'] = $content_array;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表放入回收站操作.
     */
    public function recycle_bin($id)
    {
        $this->operator_privilege('products_recycle_bin');
        $result['flag'] = 2;
        $result['message'] = $this->ld['move_to_recycle_bin_failure'];
        $this->Product->hasOne = array();
        $this->Product->updateAll(array('Product.status' => '2'), array('Product.id' => $id));
        $pn = $this->ProductI18n->find('list', array('fields' => array('ProductI18n.product_id', 'ProductI18n.name'), 'conditions' => array('ProductI18n.product_id' => $id, 'ProductI18n.locale' => $this->backend_locale)));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_into_recycle_bin'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $this->product_log_save($id);
        $result['flag'] = 1;
        $result['message'] = $this->ld['move_to_recycle_bin_success_'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表复制一个产品.
     */
    public function copy_product($original_pro_id)
    {
        $this->operator_privilege('products_copy');
        $this->Product->hasOne = array('ProductI18n' => array(
                        'className' => 'ProductI18n',
                        'order' => '',
                        'dependent' => true,
                        'foreignKey' => 'product_id',
                    ));
        $original_pro = $this->Product->find('all', array('conditions' => array('Product.id' => $original_pro_id)));
        foreach ($original_pro as $k => $v) {
            $max_product = $this->Product->find('all', array('conditions' => array(), 'order' => 'Product.id DESC', 'fields' => 'id'));
            if (!empty($max_product)) {
                $max_id = $max_product[0]['Product']['id'] + 1;
            } else {
                $max_id = 1;
            }
            $v['Product']['code'] = $this->generate_product_code($max_id);
            $v['Product']['id'] = $max_id;
            $v['Product']['promotion_price'] = '0';
            $v['Product']['promotion_status'] = '0';
            $v['Product']['recommand_flag'] = '0';
            $v['Product']['forsale'] = '0';
            $v['Product']['quantity'] = '0';
            $v['ProductI18n']['id'] = '';
            $v['ProductI18n']['product_id'] = $max_id;
            $products['Product'] = $v['Product'];
            $products['ProductI18n'][] = $v['ProductI18n'];
        }
        $this->Product->saveAll(array('Product' => $products['Product']));
        foreach ($products['ProductI18n']as $ka => $va) {
            $this->ProductI18n->saveAll(array('ProductI18n' => $va));
            if ($va['locale'] == $this->backend_locale) {
                $userinformation_name = $va['name'];
            }
        }
        //判断额外属性应用
        if (isset($this->apps['Applications']['APP-PRODUCTS']) && $this->apps['Applications']['APP-PRODUCTS']['status'] == 1) {
            $attr_info = $this->ProductAttribute->find('all', array('conditions' => array('ProductAttribute.product_id' => $original_pro_id)));
            if (isset($attr_info)) {
                foreach ($attr_info as $ka => $va) {
                    $products['ProductAttribute'][$ka] = $va['ProductAttribute'];
                    $products['ProductAttribute'][$ka]['id'] = '';
                    $products['ProductAttribute'][$ka]['product_id'] = $max_id;
                }
                if (isset($attr_info['ProductAttribute'])) {
                    foreach ($attr_info['ProductAttribute'] as $ka => $va) {
                        $this->ProductAttribute->saveAll(array('ProductAttribute' => $va));
                    }
                }
            }
            $this->product_log_save($original_pro_id);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_copy_product'].':id '.$original_pro_id.' '.$userinformation_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
    }

 //导出方法封装
  public function exprot_out($export_csv, $export_type, $export_type_re, $code)
  {
      if ($export_csv == 'choice_export') {
          if (isset($_POST['method'])) {
              $product_check = explode('&checkboxes[]=', $_POST['method']);
          }
          foreach ($product_check as $k => $v) {
              if (!empty($v)) {
                  $product_checkboxes[] = $v;
              }
          }
      }
      $condition = '';
      $order = '';
      $condition['Product.status'] = 1;
      if (!empty($product_checkboxes) && $export_csv == 'choice_export') {
          $condition['Product.id'] = $product_checkboxes;
          if ($this->configs['product_order'] == 'category') {
              $order = 'Product.category_id,Product.id';
          } else {
              $order = 'Product.'.$this->configs['product_order'];
          }
      }
      if ($export_csv == 'all_export_csv') {
          if ($export_type == 'for_sale') {
              $condition['Product.forsale'] = 1;
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          } elseif ($export_type == 'out_of_stock') {
              $condition['Product.forsale'] = 0;
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          } elseif ($export_type == 'all_product') {
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          }
          if ($export_type_re == 'recommend') {
              $condition['Product.recommand_flag'] = 1;
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          } elseif ($export_type_re == 'not_recommended') {
              $condition['Product.recommand_flag'] = 0;
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          } elseif ($export_type_re == 'all_product') {
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          }
      }
      $this->search_act_result($condition, $order, 'products', $code);
  }

  //无配置文档时导出
  public function noprofile_exprot_out($export_csv, $export_type, $export_type_re)
  {
      $this->Product->hasOne = array();
      $product_checkboxes = array();
      $product_check = array();
        //取出所有公共属性
        $this->Attribute->set_locale($this->backend_locale);
      $public_attr_ids = $this->ProductTypeAttribute->getattrids(0);
      $pubile_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $public_attr_ids, 'Attribute.status' => 1), 'fields' => 'Attribute.id,AttributeI18n.name'));
      $bran_sel = $this->Brand->find('all', array('fields' => array('Brand.id', 'Brand.code', 'BrandI18n.name'), 'order' => 'Brand.orderby,Brand.code'));
      $brand_names = array();
      foreach ($bran_sel as $bk => $bv) {
          $brand_names[$bv['Brand']['id']] = $bv['BrandI18n']['name'];
      }
      $pat = array();
      if (!empty($pubile_attr_info)) {
          foreach ($pubile_attr_info as $k => $p) {
              $pat[$k]['id'] = $p['Attribute']['id'];
              $pat[$k]['name'] = $p['AttributeI18n']['name'];
          }
      }
      $pat_ids = array();
      if (!empty($pubile_attr_info)) {
          foreach ($pubile_attr_info as $pa) {
              $pat_ids[] = $pa['Attribute']['id'];
          }
      }
      $str = $this->ld['number'].','.$this->ld['sku'].','.$this->ld['name'].','.$this->ld['brand'].',';
      foreach ($pat as $pp) {
          $str .= $pp['name'].',';
      }
      $str .= $this->ld['log_operation'].','.$this->ld['quantity'].','.$this->ld['purchase_price'].','.$this->ld['shop_price'].','.$this->ld['for_sale'].','.$this->ld['recommend']."\n";
      if ($export_csv == 'choice_export') {
          if (isset($_POST['method'])) {
              //	$product_check=explode("&",$_POST["method"]) ;
                $product_check = explode('&checkboxes[]=', $_POST['method']);
          }
          foreach ($product_check as $k => $v) {
              if (!empty($v)) {
                  $product_checkboxes[] = $v;
              }
          }
      }
      if (!empty($product_checkboxes) && $export_csv == 'choice_export') {
          if ($this->configs['product_order'] == 'category') {
              $p = $this->Product->find('all', array('conditions' => array('Product.id' => $product_checkboxes), 'recursive' => -1, 'order' => 'Product.category_id,Product.id'));
          } else {
              $p = $this->Product->find('all', array('conditions' => array('Product.id' => $product_checkboxes), 'recursive' => -1, 'order' => 'Product.'.$this->configs['product_order']));
          }
      }
        //	$this->Product->set_locale($this->backend_locale);
        if ($export_csv == 'all_export_csv') {
            if ($export_type == 'for_sale') {
                if ($this->configs['product_order'] == 'category') {
                    $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1, 'Product.forsale' => 1), 'recursive' => -1, 'order' => 'Product.category_id,Product.id,Product.id'));
                } else {
                    $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1, 'Product.forsale' => 1), 'recursive' => -1, 'order' => 'Product.'.$this->configs['product_order']));
                }
            } elseif ($export_type == 'out_of_stock') {
                if ($this->configs['product_order'] == 'category') {
                    $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1, 'Product.forsale' => 0), 'recursive' => -1, 'order' => 'Product.category_id,Product.id'));
                } else {
                    $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1, 'Product.forsale' => 0), 'recursive' => -1, 'order' => 'Product.'.$this->configs['product_order']));
                }
            } elseif ($export_type == 'all_product') {
                if ($this->configs['product_order'] == 'category') {
                    $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1), 'recursive' => -1, 'order' => 'Product.category_id,Product.id'));
                } else {
                    $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1), 'recursive' => -1, 'order' => 'Product.'.$this->configs['product_order']));
                }
            }
        }
      $p_ids = array();
      if (!empty($p)) {
          foreach ($p as $s) {
              $p_ids[] = $s['Product']['id'];
          }
      }
      $newdatas = array();
      $attr_info = $this->ProductAttribute->product_list_format($p_ids, $pat_ids, $this->backend_locale);
      $datas = array();
      $datas[] = array($str);
      foreach ($datas[0] as $v) {
          $newdatas[] = explode(',', $v);
      }
      if (!empty($p)) {
          foreach ($p as $v) {
              $product = $this->ProductI18n->find('first', array('conditions' => array('ProductI18n.product_id ' => $v['Product']['id'], 'ProductI18n.locale' => $this->backend_locale), 'recursive' => -1));
              $newdata = array();
              $newdata[] = $v['Product']['id'];
              $newdata[] = $v['Product']['code'];
              $newdata[] = htmlspecialchars_decode($product['ProductI18n']['name']);
              $newdata[] = (isset($brand_names[$v['Product']['brand_id']]) ? $brand_names[$v['Product']['brand_id']] : ' ');
              foreach ($pat as $pp) {
                  $newdata[] = (isset($attr_info[$v['Product']['id']][$pp['id']]) ? $attr_info[$v['Product']['id']][$pp['id']] : ' ');
              }
              $times = date('mdy', strtotime($v['Product']['last_update_time']));
              $newdata[] = (isset($v['Product']['operator_name']) ? $v['Product']['operator_name'] : ' ').'-'.$times;
              $newdata[] = $v['Product']['quantity'];
              $newdata[] = $v['Product']['purchase_price'];
              $newdata[] = $v['Product']['shop_price'];
              $newdata[] = $v['Product']['forsale'];
              $newdata[] = $v['Product']['recommand_flag'];
              $newdatas[] = $newdata;
          }
      }
      $this->Phpexcel->output('products'.date('YmdHis').'.xls', $newdatas);
      exit;
  }

    public function search_act_result($condition = '', $order = '', $out_type = '', $code)
    {
        //取出所有公共属性
//  	    if(in_array('APP-PRODUCTS',$this->apps)){
            $this->Attribute->set_locale($this->backend_locale);
        $pubile_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.status' => 1, 'Attribute.type !=' => 'customize'), 'fields' => 'Attribute.id,AttributeI18n.name'));
        $pat = array();
        if (!empty($pubile_attr_info)) {
            foreach ($pubile_attr_info as $k => $p) {
                $pat[$k]['id'] = $p['Attribute']['id'];
                $pat[$k]['name'] = $p['AttributeI18n']['name'];
            }
        }
        $pat_ids = array();
        if (!empty($pubile_attr_info)) {
            foreach ($pubile_attr_info as $pa) {
                $pat_ids[] = $pa['Attribute']['id'];
            }
        }
//		}
        $this->Profile->hasOne = array();
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $code, 'Profile.status' => 1)));
        $newdatas = array();
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            $tmp = array();
            $fields_array = array();
            foreach ($profilefiled_info as $k => $v) {
                $tmp[] = $v['ProfilesFieldI18n']['description'];
                $fields_array[] = $v['ProfileFiled']['code'];
            }
            $brand_names = array();
            if (in_array('Product.brand_id', $fields_array)) {
                $this->Brand->set_locale($this->backend_locale);
                $bran_sel = $this->Brand->find('all', array('fields' => array('Brand.id', 'Brand.code', 'BrandI18n.name'), 'order' => 'Brand.orderby,Brand.code'));
                foreach ($bran_sel as $bk => $bv) {
                    $brand_names[$bv['Brand']['id']] = $bv['BrandI18n']['name'];
                }
            }
            $product_category_names = array();
            if (in_array('Product.category_id', $fields_array)) {
                $this->CategoryProduct->set_locale($this->backend_locale);
                $category_tree = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.status' => 1), 'fields' => array('CategoryProduct.id', 'CategoryProductI18n.name')));
                foreach ($category_tree as $v) {
                    $product_category_names[$v['CategoryProduct']['id']] = $v['CategoryProductI18n']['name'];
                }
            }
            $product_type_names = array();
            if (in_array('Product.product_type_id', $fields_array)) {
                $this->ProductType->set_locale($this->backend_locale);
                $attribute_type_tree = $this->ProductType->find('all', array('conditions' => array('ProductType.status' => 1, 'ProductType.id !=' => 0)));
                foreach ($attribute_type_tree as $v) {
                    $product_type_names[$v['ProductType']['id']] = $v['ProductTypeI18n']['name'];
                }
            }
            //取出所有公共属性
//            if(in_array('APP-PRODUCTS',$this->apps)){
                foreach ($pat as $pp) {
                    $tmp[] = $pp['name'];
                }
            $product_ids = $this->Product->find('list', array('fields' => array('Product.code', 'Product.id'), 'conditions' => array('Product.status' => 1)));
            $attr_info = $this->ProductAttribute->product_list_format($product_ids, $pat_ids, $this->backend_locale);
//			}
        }
        $fields_array[] = 'Product.id';
        $this->Product->hasOne = array();
        $this->Product->hasOne = array('ProductI18n' => array(
                                'className' => 'ProductI18n',
                                'order' => '',
                                'dependent' => true,
                                'foreignKey' => 'product_id',
                                'fields' => array('ProductI18n.name'),
                            ));
        $this->Product->set_locale($this->backend_locale);
        $productlist = $this->Product->find('all', array('conditions' => $condition, 'order' => $order, 'fields' => $fields_array));//'recursive'=>-1
        $newdatas[] = $tmp;
        foreach ($productlist as $k => $v) {
            $product_tmp = array();
            foreach ($fields_array as $kk => $vv) {
                $fields_kk = explode('.', $vv);
                if ($vv == 'Product.brand_id') {
                    if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                        $product_tmp[] = isset($brand_names[$v['Product']['brand_id']]) ? $brand_names[$v['Product']['brand_id']] : '';
                    } else {
                        $product_tmp[] = '';
                    }
                } elseif ($vv == 'Product.product_type_id') {
                    if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                        $product_tmp[] = isset($product_type_names[$v['Product']['product_type_id']]) ? $product_type_names[$v['Product']['product_type_id']] : '';
                    } else {
                        $product_tmp[] = '';
                    }
                } elseif ($vv == 'Product.category_id') {
                    if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                        $product_tmp[] = isset($product_category_names[$v['Product']['category_id']]) ? $product_category_names[$v['Product']['category_id']] : '';
                    } else {
                        $product_tmp[] = '';
                    }
                } elseif ($vv == 'Product.last_update_time') {
                    $last_update_time = date('mdy', strtotime($v['Product']['last_update_time']));
                    if ($last_update_time != '010108') {
                        $product_tmp[] = $last_update_time;
                    } else {
                        $product_tmp[] = '-';
                    }
                } elseif ($vv == 'Product.id') {
                    continue;
                } else {
                    $product_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                }
            }
//    		if(in_array('APP-PRODUCTS',$this->apps)){
                foreach ($pat as $pp) {
                    $product_tmp[] = (isset($attr_info[$v['Product']['id']][$pp['id']]) ? $attr_info[$v['Product']['id']][$pp['id']] : ' ');
                }
//		    }
            $newdatas[] = $product_tmp;
        }
        $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        exit;
    }

    public function category_products_tree($data, $category, $p, $fields_array, $pat = array(), $attr_info = array(), $brand_names, $product_type_names)
    {
        $products = array();
        if (isset($data) && is_array($data)) {
            foreach ($data[$category['CategoryProduct']['id']] as $k => $v) {
                $product = array();
                $product[] = '';
                foreach ($fields_array as $kk => $vv) {
                    $fields_kk = explode('.', $vv);
                    if ($vv == 'Product.brand_id') {
                        if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                            $product[] = isset($brand_names[$v['Product']['brand_id']]) ? $brand_names[$v['Product']['brand_id']] : '';
                        } else {
                            $product[] = '';
                        }
                    } elseif ($vv == 'Product.last_update_time') {
                        $last_update_time = date('mdy', strtotime($v['Product']['last_update_time']));
                        if ($last_update_time != '010108') {
                            $product[] = $last_update_time;
                        } else {
                            $product[] = '-';
                        }
                    } elseif ($vv == 'Product.product_type_id') {
                        if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                            $product[] = isset($product_type_names[$v['Product']['product_type_id']]) ? $product_type_names[$v['Product']['product_type_id']] : '';
                        } else {
                            $product[] = '';
                        }
                    } else {
                        $product[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                    }
                }

                if (!empty($pat)) {
                    foreach ($pat as $pp) {
                        $attrInfo = (isset($attr_info[$v['Product']['id']][$pp['id']]) ? $attr_info[$v['Product']['id']][$pp['id']] : '');
                        $product[] = $attrInfo;
                    }
                }
                $products[] = $product;
            }
        }

        return $products;
    }

    /**
     *列表批量操作.
     *
     *@param string $type 类型
     */
    public function batch_operations($type)
    {
        $this->Product->hasOne = array();
        $product_checkboxes = array();
        if ($type != 'export_csv') {
            $product_checkboxes = $_REQUEST['checkboxes'];
            foreach ($product_checkboxes as $k => $v) {
                if ($type == 'recycle_bin') {
                    $this->operator_privilege('products_recycle_bin');
                    $this->Product->updateAll(array('status' => '2'), array('id' => $v));
                }
                if ($type == 'transfer_category') {
                    $category_id = $_REQUEST['category_id'];
                    $this->Product->updateAll(array('category_id' => $category_id), array('id' => $v));
                }
                if ($type == 'batch_onsale') {
                    $this->Product->updateAll(array('forsale' => '1'), array('id' => $v));
                }
                if ($type == 'batch_notsale') {
                    $this->Product->updateAll(array('forsale' => '0'), array('id' => $v));
                }
            }
            if ($type == 'recycle_bin') {
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_recycle_bin'], $this->admin['id']);
                }
            }
            if ($type == 'transfer_category') {
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_operation'], $this->admin['id']);
                }
            }
            if ($type == 'batch_onsale') {
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['batch_onsale'], $this->admin['id']);
                }
            }
            if ($type == 'batch_notsale') {
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['batch_notsale'], $this->admin['id']);
                }
            }
            $this->product_log_save($product_checkboxes);
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        } elseif ($type == 'export_csv') {
            if ($_REQUEST['export_csv'] == 'category_export') {
                $this->export_by_category($_REQUEST['export_type'], $_REQUEST['export_type_re']);
            } elseif ($_REQUEST['export_csv'] == 'search_result') {
                $this->export_by_search_result($_REQUEST['export_csv']);
            } else {
                $this->exprot_out($_REQUEST['export_csv'], $_REQUEST['export_type'], $_REQUEST['export_type_re'], $_REQUEST['code']);
            }
        }
        exit();
    }

    /**
     *列表名称修改.
     */
    public function update_product_name()
    {
        $this->operator_privilege('products_edit');
        $this->Product->hasMany = array();
        $this->Product->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $product_code = $this->Product->find('first', array('fields' => array('code'), 'conditions' => array('id' => $id)));
        $request = $this->ProductI18n->updateAll(
            array('name' => "'".$val."'"),
            array('product_id' => $id, 'locale' => $this->backend_locale)
        );
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('TaobaoItem');
            $p = $this->Product->find('list', array('conditions' => array('Product.id' => $id), 'fields' => 'Product.code'));
            $this->TaobaoItem->updateAll(array('TaobaoItem.title' => "'".$val."'", 'TaobaoItem.is_update_taobao' => 0), array('TaobaoItem.outer_id' => $p[$id]));
        }
//			$log_flag=$this->Operator->find("first",array("fields"=>array("Operator.log_flag"),"conditions"=>array("Operator.name"=>$this->admin['name'])));
//			if($log_flag['Operator']['log_flag']=="1"){
//				$this->Product->save(array("last_update_time"=>date("Y-m-d H:i:s"),"operator_name"=>$this->admin['name'],"id"=>$id,"operator_id"=>$this->admin['id']));
//			}

        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑货号'.'.'.$product_code['Product']['code'].' '.'商品名'.'.'.$val, $this->admin['id']);
        }
        $this->product_log_save($id);
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *套装列表名称修改.
     */
    public function update_packageproduct_name()
    {
        $this->operator_privilege('products_edit');
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        /*$product_code=$this->Product->find("first",array("fields"=>array("code"),"conditions"=>array("id"=>$id)));*/
        $request = $this->PackageProduct->updateAll(
            array('package_product_name' => "'".$val."'"),
            array('id' => $id)
        );
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑套装商品:'.$val, $this->admin['id']);
        }
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表属性修改.
     */
    public function update_product_attr()
    {
        Configure::write('debug', 0);
        $this->Product->hasMany = array();
        $this->Product->hasOne = array();
        $id = explode(';', $_REQUEST['id']);
        $val = $_REQUEST['val'];
        $product_attr_info = $this->ProductAttribute->find('first', array('conditions' => array('ProductAttribute.product_id' => $id[0], 'ProductAttribute.product_type_attribute_id' => $id[1], 'ProductAttribute.locale' => $this->backend_locale)));
        if (isset($product_attr_info['ProductAttribute']['id'])) {
            $request = $this->ProductAttribute->updateAll(
                array('ProductAttribute.product_type_attribute_value' => "'".$val."'"),
                array('ProductAttribute.product_id' => $id[0], 'ProductAttribute.product_type_attribute_id' => $id[1], 'ProductAttribute.locale' => $this->backend_locale)
            );
//	    	if($SESSION['log_flag']=="1"){
//	    		$this->Product->save(array("last_update_time"=>date("Y-m-d H:i:s"),"operator_name"=>$this->admin['name'],"id"=>$id[0],"operator_id"=>$this->admin['id']));
//    		}
        } else {
            foreach ($this->backend_locales as $v) {
                $data['locale'] = $v['Language']['locale'];
                $data['product_type_attribute_value'] = $val;
                $data['product_id'] = $id[0];
                $data['product_type_attribute_id'] = $id[1];
                $request = $this->ProductAttribute->saveAll($data);
            }
        }
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        if ($val == '') {
            $result['content'] = '-';
        }
        $result['flag'] = 1;
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表货号修改.
     */
    public function update_product_code()
    {
        $this->operator_privilege('products_edit');
        $this->Product->hasMany = array();
        $this->Product->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        $product_info = $this->Product->find('all', array('conditions' => array('code' => $val, 'id !=' => $id), 'fields' => 'id,code'));
        $p = $this->Product->find('list', array('conditions' => array('Product.id' => $id), 'fields' => 'Product.code'));
        //判断是否在仓库里 如果在不允许该货号
        if (in_array('APP-WAREHOUSE', $this->apps['codes']) && constant('Product') == 'AllInOne') {
            $this->loadModel('Stock');
            $stockInfo = $this->Stock->find('first', array('conditions' => array('Stock.product_code' => $p[$id]), 'callbacks' => 0));
            if (!empty($stockInfo)) {
                $result['flag'] = 2;
                $result['content'] = '该商品在仓库已存在,不能修改货号!';
                Configure::write('debug', 0);
                $this->layout = 'ajax';
                die(json_encode($result));
            }
        }
        if (!empty($product_info)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['sku_exists_not_repeated'];
        }
        if (isset($this->configs['products-str'])) {
            if ($this->configs['products-str'] == 'upper') {
                $val = strtoupper($val);
            } elseif ($this->configs['products-str'] == 'lowe') {
                $val = strtolower($val);
            }
        }
        if (empty($product_info) && $this->Product->save(array('id' => $id, 'code' => $val, 'operator_id' => $this->admin['id']))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('TaobaoItem');
                $this->TaobaoItem->updateAll(array('TaobaoItem.outer_id' => "'".$val."'", 'TaobaoItem.is_update_taobao' => 0), array('TaobaoItem.outer_id' => $p[$id]));
            }
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_modify_product_code'].':'.$val, $this->admin['id']);
            }
            $this->product_log_save($id);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表库存修改.
     */
    public function update_product_quantity()
    {
        $this->operator_privilege('products_edit');
        $this->Product->hasMany = array();
        $this->Product->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $product_code = $this->Product->find('first', array('fields' => array('code', 'quantity'), 'conditions' => array('id' => $id)));
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_quantity_data'];
        }
        if (is_numeric($val) && $this->Product->save(array('id' => $id, 'quantity' => $val, 'operator_id' => $this->admin['id']))) {
            if ($this->SkuProduct->check_sku($product_code['Product']['code'])) {
                //当前商品为子商品
                $pro_sku_Info = $this->SkuProduct->get_pro_code($product_code['Product']['code']);//获取主商品货号
                if (!empty($pro_sku_Info)) {
                    $pro_ids = $this->SkuProduct->get_sku_pro_ids($pro_sku_Info);//获取主商品下子商品Id
                    $this->Product->set_product_quantity($pro_sku_Info, $pro_ids);
                }
            }
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('TaobaoItem');
                $p = $this->Product->find('list', array('conditions' => array('Product.id' => $id), 'fields' => 'Product.code'));
                $this->TaobaoItem->updateAll(array('TaobaoItem.num' => "'".$val."'", 'TaobaoItem.is_update_taobao' => 0), array('TaobaoItem.outer_id' => $p[$id]));
            }
            //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑货号'.'.'.$product_code['Product']['code'].' '.'库存'.'.'.$val, $this->admin['id']);
                }
            $this->product_log_save($id);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表价格修改.
     */
    public function update_product_price()
    {
        $this->operator_privilege('products_edit');
        $this->Product->hasMany = array();
        $this->Product->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $product_code = $this->Product->find('first', array('fields' => array('code'), 'conditions' => array('id' => $id)));
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_price'];
        }
        if (is_numeric($val) && $this->Product->save(array('id' => $id, 'shop_price' => $val, 'market_price' => $val * 1.2, 'operator_id' => $this->admin['id']))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
//			$log_flag=$this->Operator->find("first",array("fields"=>array("Operator.log_flag"),"conditions"=>array("Operator.name"=>$this->admin['name'])));
//			if($log_flag['Operator']['log_flag']=="1"){
//				$this->Product->save(array("last_update_time"=>date("Y-m-d H:i:s"),"operator_name"=>$this->admin['name'],"id"=>$id,"operator_id"=>$this->admin['id']));
//			}
    if (constant('Product') == 'AllInOne') {
        $this->loadModel('TaobaoItem');
        $p = $this->Product->find('list', array('conditions' => array('Product.id' => $id), 'fields' => 'Product.code'));
        $this->TaobaoItem->updateAll(array('TaobaoItem.price' => "'".$val."'", 'TaobaoItem.is_update_taobao' => 0), array('TaobaoItem.outer_id' => $p[$id]));
    }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑货号'.'.'.$product_code['Product']['code'].' '.'价格'.'.'.$val, $this->admin['id']);
            }
            $this->product_log_save($id);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表进货价格修改.
     */
    public function update_product_purchase_price()
    {
        $this->operator_privilege('products_edit');
        $this->Product->hasMany = array();
        $this->Product->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_price'];
        }
        if (is_numeric($val) && $this->Product->save(array('id' => $id, 'purchase_price' => $val, 'operator_id' => $this->admin['id']))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);

//			if($SESSION['log_flag']=="1"){
//             	$this->Product->save(array("last_update_time"=>date("Y-m-d H:i:s"),"operator_name"=>$this->admin['name'],"id"=>$id,"operator_id"=>$this->admin['id']));
//			}
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_modify_product_price'].':'.$val, $this->admin['id']);
            }
            $this->product_log_save($id);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表上架修改.
     */
    public function toggle_on_forsale()
    {
        $this->operator_privilege('products_edit');
        $this->Product->hasMany = array();
        $this->Product->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        if ($val == 1) {
            $product_info = array('id' => $id,
                'forsale' => $val,
                'online_time' => date('Y-m-d H:i:s'),
                'operator_id' => $this->admin['id'], );
        } elseif ($val == 0) {
            $product_info = array('id' => $id,
                'forsale' => $val,
                'operator_id' => $this->admin['id'], );
        }
        $product_code = $this->Product->find('first', array('fields' => array('code'), 'conditions' => array('id' => $id)));
        $result = array();
        if (is_numeric($val) && $this->Product->save($product_info)) {
            //$this->Product->save(array("id"=>$id,"forsale"=>$val,"operator_id" => $this->admin['id']))
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑货号'.'.'.$product_code['Product']['code'].' '.'上架'.'.'.$val, $this->admin['id']);
            }
            $this->product_log_save($id);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表推荐修改.
     */
    public function toggle_on_recommand_flag()
    {
        $this->operator_privilege('products_edit');
        $this->Product->hasMany = array();
        $this->Product->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $product_code = $this->Product->find('first', array('fields' => array('code'), 'conditions' => array('id' => $id)));
        $result = array();
        if (is_numeric($val) && $this->Product->save(array('id' => $id, 'recommand_flag' => $val, 'operator_id' => $this->admin['id']))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑货号'.'.'.$product_code['Product']['code'].' '.'推荐'.'.'.$val, $this->admin['id']);
            }
            $this->product_log_save($id);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *关联页 商品关联商品 排序修改.
     */
    public function product_relation_product_orderby()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->ProductRelation->save(array('id' => $id, 'orderby' => $val, 'operator_id' => $this->admin['id']))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_modify_related_product_sort'].':'.$val, $this->admin['id']);
            }
            $this->product_log_save($id);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *设为默认图片.
     */
    public function set_default_picture($id, $product_id)
    {
        $this->ProductGallery->hasOne = array();
//		$product_gallery_info = $this->ProductGallery->find("first",array("conditions"=>array("id"=>$id),"fields"=>array("img_thumb","img_detail","img_original")));
//		$product_gallery_info["ProductGallery"]["id"] = $product_id;
//		$this->Product->save(array("Product"=>$product_gallery_info["ProductGallery"]));
        $galleries = $this->ProductGallery->find('all', array('conditions' => array('ProductGallery.product_id' => $product_id), 'order' => 'ProductGallery.orderby ASC'));
        $i = 0;
        foreach ($galleries as $k => $v) {
            if ($id == $v['ProductGallery']['id']) {
                $product_gallery_info['ProductGallery']['id'] = $product_id;
                $product_gallery_info['ProductGallery']['img_thumb'] = $v['ProductGallery']['img_thumb'];
                $product_gallery_info['ProductGallery']['img_detail'] = $v['ProductGallery']['img_detail'];
                $product_gallery_info['ProductGallery']['img_original'] = $v['ProductGallery']['img_original'];
                $this->Product->save(array('Product' => $product_gallery_info['ProductGallery']));
                $v['ProductGallery']['orderby'] = 1;
            } else {
                ++$i;
                $v['ProductGallery']['orderby'] = 1 + $i;
            }
            $this->ProductGallery->save(array('ProductGallery' => $v['ProductGallery']));
        }
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_set_default_picture'].':id '.$id, $this->admin['id']);
        }
        $this->product_log_save($product_id);
        $this->redirect('/products/view/'.$product_id);
    }

    public function update_orderby($type)
    {
        $relation_id = $_REQUEST['id'];
        $sort_value = $_REQUEST['val'];
        if ($type == 'P') {
            $this->ProductRelation->updateAll(array('ProductRelation.orderby' => $sort_value), array('ProductRelation.id' => $relation_id));
        } elseif ($type == 'A') {
            $this->ProductArticle->updateAll(array('ProductArticle.orderby' => $sort_value), array('ProductArticle.id' => $relation_id));
        } elseif ($type == 'T') {
            $this->TopicProduct->updateAll(array('TopicProduct.orderby' => $sort_value), array('TopicProduct.id' => $relation_id));
        } elseif ($type == 'PA') {
            $this->ProductArticle->updateAll(array('ProductArticle.orderby' => $sort_value), array('ProductArticle.id' => $relation_id));
        }
        Configure::write('debug', 0);
        $result['type'] = '0';
        die(json_encode($result));
    }

    //检验货号是否存在
    public function select_product_code()
    {
        //检验手动填写商品货号是否存在
        $condition = '';
        $condition['Product.code'] = $_REQUEST['product_code'];
        if (isset($_REQUEST['pId']) && $_REQUEST['pId'] != '0') {
            $condition['Product.id <>'] = $_REQUEST['pId'];
        }
        //判断是否在仓库里 如果在不允许该货号
        if (in_array('APP-WAREHOUSE', $this->apps) && isset($_REQUEST['pId']) && $_REQUEST['pId'] != 0 && constant('Product') == 'AllInOne') {
            $this->loadModel('Stock');
            $p = $this->Product->find('list', array('conditions' => array('Product.id' => $_REQUEST['pId']), 'fields' => 'Product.code'));
            $stockInfo = $this->Stock->find('first', array('conditions' => array('Stock.product_code' => $p[$_REQUEST['pId']]), 'callbacks' => 0));
            if (!empty($stockInfo)) {
                $result['type'] = 2;
                $result['code'] = $p[$_REQUEST['pId']];
                $result['message'] = '该商品在仓库已存在,不能修改货号!';
                Configure::write('debug', 0);
                $this->layout = 'ajax';
                die(json_encode($result));
            }
        }
        $infos = $this->Product->find('all', array('conditions' => $condition, 'recursive' => -1));
        if (!empty($infos)) {
            $result['type'] = '1';
            $result['message'] = $this->ld['code_already_exist'];
        } else {
            $result['type'] = '0';
            $result['message'] = $this->ld['code_can_be_used'];
        }
        Configure::write('debug', 0);
        echo json_encode($result);
        die();
    }

    /*
        商品相册排序
    */
    public function changeorder($p_id, $id)
    {
    	Configure::write('debug', 0);
        $this->layout = 'ajax';
        //商品相册列表获取
        $product_gallery_one = $this->ProductGallery->find('first', array('conditions' => array('ProductGallery.id' => $id), 'fields' => 'ProductGallery.orderby,ProductGallery.id'));
        $product_gallery_change = $this->ProductGallery->find('first', array('conditions' => array('ProductGallery.orderby >' => $product_gallery_one['ProductGallery']['orderby'], 'ProductGallery.product_id' => $p_id), 'fields' => 'ProductGallery.orderby,ProductGallery.id', 'order' => 'orderby ASC', 'limit' => '1'));
        $t = $product_gallery_one['ProductGallery']['orderby'];
        $product_gallery_one['ProductGallery']['orderby'] = $product_gallery_change['ProductGallery']['orderby'];
        $product_gallery_change['ProductGallery']['orderby'] = $t;
        $this->ProductGallery->saveAll($product_gallery_one);
        $this->ProductGallery->saveAll($product_gallery_change);
        //商品相册列表获取
        $product_info = $this->Product->localeformat($p_id);
        $product_gallery = $this->ProductGallery->product_gallery_format($p_id);
        $this->set('product_gallery', $product_gallery);
        $this->set('product_info', $product_info);
        $this->set('id', $id);
        
    }

    public function change_product_code()
    {
        $result['flag'] = 2;
        $val = $_REQUEST['val'];
        if (!empty($val)) {
            $brand = $this->Brand->find('first', array('conditions' => array('Brand.id' => $val)));
            if (!empty($brand['Brand']['code'])) {
                $result['code'] = $brand['Brand']['code'];
                $result['flag'] = 1;
            }
        }
        die(json_encode($result));
    }

    //根据品牌类目自动生成货号
    public function auto_code()
    {
        $result['flag'] = 2;
        $val = isset($_REQUEST['val']) ? $_REQUEST['val'] : '';
        $code1 = isset($_REQUEST['brand_code']) ? $_REQUEST['brand_code'] : 0;//品牌code
        $code2 = isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : 0;//分类
        $code3 = isset($_REQUEST['category_type_id']) ? $_REQUEST['category_type_id'] : 0;//类目
        $len = empty($this->configs['product-autocode-codelength']) ? 7 : $this->configs['product-autocode-codelength'];
        $autoby = empty($this->configs['products-auto']) ? '2' : $this->configs['products-auto'];
        if (!empty($val)) {
            if (strlen($val) == $len) {
                $result['code'] = '货号长度已达设定值，无法计算';
                $result['flag'] = 2;
                die(json_encode($result));
            }
            $code = $this->Product->find('first', array('conditions' => array('Product.code like' => "$val%"), 'order' => 'Product.id desc', 'fields' => array('MAX(Product.code) as max_code')));
            if (is_numeric(trim($code[0]['max_code']))) {
                $result['code'] = $code[0]['max_code'] + 1;
                $result['flag'] = 1;
                die(json_encode($result));
            } elseif (empty($code[0]['max_code'])) {
                $result['code'] = $val.str_pad(1, $len - strlen($val), '0', STR_PAD_LEFT);
                $result['flag'] = 1;
                die(json_encode($result));
            } else {
                $result['code'] = '同类货号包含字符，无法计算';
                $result['flag'] = 2;
                die(json_encode($result));
            }
            if (!empty($code)) {
                $result['code'] = $val;
                $z = substr($code['Product']['code'], strlen($val));
                if (is_numeric($z)) {
                    $z = $z + 1;
                    $result['flag'] = 1;
                } else {
                    $result['code'] = '货号前缀不匹配';
                    die(json_encode($result));
                }
                $y = isset($len) ? ($len - strlen($val) - strlen($z)) : 0;
                for ($i = 0; $i < $y; ++$i) {
                    $result['code'] .= '0';
                }
                $result['code'] .= $z;
                $result['flag'] = 1;
            } else {
                $x = strlen($val);
                $y = isset($len) ? ($len - $x) : 0;
                if ($y <= 0) {
                    $result['code'] = '货号长度已达设定值，无法计算';
                    die(json_encode($result));
                }
                $result['code'] = $val;
                for ($i = 0; $i < $y - 1; ++$i) {
                    $result['code'] .= '0';
                }
                $result['code'] .= '1';
                $result['flag'] = 1;
            }
        } else {
            $code = $this->Product->find('first', array('order' => 'Product.id desc', 'fields' => array('Product.id')));
            $code0 = isset($this->configs['products_code_prefix']) ? $this->configs['products_code_prefix'] : 'IOCO';
            $code4 = $code['Product']['id'] + 1;
            //$x = strlen($code1)+strlen($code2);
            //$y = isset($len)?($len-$x):0;
            //$code3 = str_pad($code2,$y,'0',STR_PAD_LEFT);
            if ($autoby == '0') {
                $result['code'] = $code0.$code1.$code3.$code2.$code4;//品牌+类目+分类
            } elseif ($autoby == '1') {
                $result['code'] = $code0.$code1.$code2.$code3.$code4;//品牌+分类+类目
            } elseif ($autoby == '2') {
                $result['code'] = $code0.$code2.$code3.$code1.$code4;//分类+类目+品牌
            } elseif ($autoby == '3') {
                $result['code'] = $code0.$code2.$code1.$code3.$code4;//分类+品牌+类目
            } elseif ($autoby == '4') {
                $result['code'] = $code0.$code3.$code1.$code2.$code4;//类目+品牌+分类
            } elseif ($autoby == '5') {
                $result['code'] = $code0.$code3.$code2.$code1.$code4;//类目+分类+品牌
            }
            $result['flag'] = 1;
        }
        die(json_encode($result));
    }

    //删除商品图片
    public function delProImg($product_id)
    {
        if (isset($_POST['Id']) && $_POST['Id'] != '') {
            $this->ProductGalleryI18n->deleteAll(array('product_gallery_id' => $_POST['Id']));
            $this->ProductGallery->delete(array('id' => $_POST['Id']));
            if (!empty($product_id)) {
                $this->Product->hasOne = array();
                $this->ProductGallery->hasOne = array();
                $product_info = $this->Product->find('first', array('conditions' => array('Product.id' => $product_id), 'fields' => array('Product.id', 'Product.img_thumb', 'Product.img_detail', 'Product.img_original')));
                $galleries = $this->ProductGallery->find('all', array('conditions' => array('ProductGallery.product_id' => $product_id), 'order' => 'ProductGallery.orderby ASC'));
                $default_img = 1;
                foreach ($galleries as $k => $v) {
                    if (isset($product_info['Product']['img_thumb']) && $product_info['Product']['img_thumb'] == $v['ProductGallery']['img_thumb']) {
                        $default_img = 0;
                    }
                    if ($k == 0) {
                        $product_data['Product']['id'] = $product_id;
                        $product_data['Product']['img_thumb'] = $v['ProductGallery']['img_thumb'];
                        $product_data['Product']['img_detail'] = $v['ProductGallery']['img_detail'];
                        $product_data['Product']['img_original'] = $v['ProductGallery']['img_original'];
                    }
                    $v['ProductGallery']['orderby'] = 1 + $k;
                    $this->ProductGallery->save(array('ProductGallery' => $v['ProductGallery']));
                }
                if (isset($product_data) && $default_img == 1) {
                    $this->Product->save(array('Product' => $product_data['Product']));
                }
            }
            $result['code'] = 1;
            $result['msg'] = 'success';
        } else {
            $result['code'] = 0;
            $result['msg'] = 'failed';
        }
        die(json_encode($result));
    }

    //分类导出方法封装
  public function export_by_category($export_type, $export_type_re)
  {
      $this->Profile->hasOne = array();
      $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => 'product_categories_export', 'Profile.status' => 1)));
      $excel = array();
      if (isset($profile_id) && !empty($profile_id)) {
          $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
          $tmp = array();
          $fields_array = array();
          $tmp[] = 'product category';
          foreach ($profilefiled_info as $k => $v) {
              $tmp[] = $v['ProfilesFieldI18n']['description'];
              $fields_array[] = $v['ProfileFiled']['code'];
          }
          $brand_names = array();
          if (in_array('Product.brand_id', $fields_array)) {
              $this->Brand->set_locale($this->backend_locale);
              $bran_sel = $this->Brand->find('all', array('fields' => array('Brand.id', 'Brand.code', 'BrandI18n.name'), 'order' => 'Brand.orderby,Brand.code'));
              foreach ($bran_sel as $bk => $bv) {
                  $brand_names[$bv['Brand']['id']] = $bv['BrandI18n']['name'];
              }
          }

          $product_type_names = array();
          if (in_array('Product.product_type_id', $fields_array)) {
              $this->ProductType->set_locale($this->backend_locale);
              $attribute_type_tree = $this->ProductType->find('all', array('conditions' => array('ProductType.status' => 1, 'ProductType.id !=' => 0)));
              foreach ($attribute_type_tree as $v) {
                  $product_type_names[$v['ProductType']['id']] = $v['ProductTypeI18n']['name'];
              }
          }
          $pat = array();
            //取出所有公共属性
//			if(in_array('APP-PRODUCTS',$this->apps)){
                $this->Attribute->set_locale($this->backend_locale);
          $all_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.status' => 1, 'Attribute.type !=' => 2), 'fields' => 'Attribute.id,AttributeI18n.name'));
          if (!empty($all_attr_info)) {
              foreach ($all_attr_info as $k => $p) {
                  $pat[$k]['id'] = $p['Attribute']['id'];
                  $pat[$k]['name'] = $p['AttributeI18n']['name'];
              }
          }
          $pat_ids = array();
          if (!empty($all_attr_info)) {
              foreach ($all_attr_info as $pa) {
                  $pat_ids[] = $pa['Attribute']['id'];
              }
          }
          foreach ($pat as $pp) {
              $tmp[] = $pp['name'];
          }
//			}
            $excel[] = $tmp;
          $this->Product->hasOne = array();
          $this->Product->hasOne = array('ProductI18n' => array(
                                    'className' => 'ProductI18n',
                                    'order' => '',
                                    'dependent' => true,
                                    'foreignKey' => 'product_id',
                                    'fields' => array('ProductI18n.name'),
                                ));
          $this->Product->set_locale($this->backend_locale);
          $conditon = '';
          $order = '';
          $conditon['Product.status'] = 1;
          if ($export_type == 'for_sale') {
              $conditon['Product.forsale'] = 1;
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          } elseif ($export_type == 'out_of_stock') {
              $conditon['Product.forsale'] = 0;
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          } elseif ($export_type == 'all_product') {
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          }
          if ($export_type_re == 'recommend') {
              $conditon['Product.recommand_flag'] = 1;
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          } elseif ($export_type_re == 'not_recommended') {
              $conditon['Product.recommand_flag'] = 0;
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          } elseif ($export_type_re == 'all_product') {
              if ($this->configs['product_order'] == 'category') {
                  $order = 'Product.category_id,Product.id';
              } else {
                  $order = 'Product.'.$this->configs['product_order'];
              }
          }
          $p = $this->Product->find('all', array('conditions' => $conditon, 'order' => $order));
          $p_ids = array();
          if (!empty($p)) {
              foreach ($p as $s) {
                  $p_ids[] = $s['Product']['id'];
              }
          }
          $attr_info = array();
//			  if(in_array('APP-PRODUCTS',$this->apps)){
                $attr_info = $this->ProductAttribute->product_list_format($p_ids, $pat_ids, $this->backend_locale);
//			  }
              foreach ($p as $k => $v) {
                  $data[$v['Product']['category_id']][] = $v;
              }
          $category_tree = $this->CategoryProduct->tree('P', $this->backend_locale);
          foreach ($category_tree as $k => $v) {
              $row = array();
              if (!empty($data[$v['CategoryProduct']['id']])) {
                  $row[] = $v['CategoryProductI18n']['name'];
                  $excel[] = $row;
                  if (isset($data[$v['CategoryProduct']['id']])) {
                      $tmp = $this->category_products_tree($data, $v, $p, $fields_array, $pat, $attr_info, $brand_names, $product_type_names);
                      $excel = array_merge($excel, $tmp);
                  }
              }
              if (isset($v['SubCategory'])) {
                  foreach ($v['SubCategory'] as $kk => $vv) {
                      $row = array();
                      if (!empty($data[$vv['CategoryProduct']['id']])) {
                          $row[] = $v['CategoryProductI18n']['name'].'-'.$vv['CategoryProductI18n']['name'];
                          $excel[] = $row;
                          if (isset($data[$vv['CategoryProduct']['id']])) {
                              $tmp = $this->category_products_tree($data, $vv, $p, $fields_array, $pat, $attr_info, $brand_names, $product_type_names);
                              $excel = array_merge($excel, $tmp);
                          }
                      }
                      if (isset($vv['SubCategory'])) {
                          foreach ($vv['SubCategory'] as $kkk => $vvv) {
                              $row = array();
                              if (!empty($data[$vvv['CategoryProduct']['id']])) {
                                  $row[] = $v['CategoryProductI18n']['name'].'-'.$vv['CategoryProductI18n']['name'].'-'.$vvv['CategoryProductI18n']['name'];
                                  $excel[] = $row;
                                  if (isset($data[$vvv['CategoryProduct']['id']])) {
                                      $tmp = $this->category_products_tree($data, $vvv, $p, $fields_array, $pat, $attr_info, $brand_names, $product_type_names);
                                      $excel = array_merge($excel, $tmp);
                                  }
                              }
                          }
                      }
                  }
              }
          }
      }
      unset($fields_array);
      unset($data);
      $this->Phpexcel->output('products_by_category'.date('YmdHis').'.xls', $excel);
      exit;
  }

    //添加套装商品
    public function add_package_product()
    {
        if ($this->RequestHandler->isPost()) {
            if (isset($_POST['selected_package'])) {
                $arr = explode(';', $_POST['selected_package']);
                //过滤空值
                $new_arr = array_filter($arr);
                if (!empty($new_arr)) {
                    //查询商品名称
                    foreach ($new_arr as $k => $v) {
                        $package_products[] = $this->Product->find('first', array('conditions' => array('Product.id' => $v, 'ProductI18n.locale' => $this->locale), 'fields' => 'Product.id,Product.code,Product.img_thumb,Product.shop_price,Product.quantity,ProductI18n.name'));
                    }
                    //pr($add_arr);die;
                }
//					$this->redirect("/products/view/".$_POST['product_id']);
                    $show_package_products = '';
                if (!empty($package_products)) {
                    foreach ($package_products as $pk => $pv) {
                        $show_package_products .= $pv['Product']['id'].';';
                    }
                }
                $this->set('show_package_products', $show_package_products);
                $this->set('package_products', $package_products);

                Configure::write('debug', 0);
                $this->layout = 'ajax';
                $this->render('ajax_package_list');
            }
        }
        $this->redirect('/products/');
    }

    //删除套装商品
    public function del_package_product()
    {
        if ($this->RequestHandler->isPost()) {
            if (isset($_POST['id'])) {
                //查询要删除的记录
                $del_id_arr = $this->PackageProduct->find('first', array('conditions' => array('PackageProduct.id' => $_POST['id']), 'fields' => 'PackageProduct.package_product_id'));
                $del_id = '';
                if (!empty($del_id_arr)) {
                    $del_id = $del_id_arr['PackageProduct']['package_product_id'];
                }
                $result = $this->PackageProduct->deleteAll(array('id' => $_POST['id']));
                $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
                if ($product_id != '') {
                    $package_products = $this->PackageProduct->find_package_product($product_id);
                    $show_package_products = '';
                    if (!empty($package_products)) {
                        foreach ($package_products as $pk => $pv) {
                            $package_product = $this->Product->find('first', array('conditions' => array('Product.id' => $pv['PackageProduct']['package_product_id']), 'fields' => 'Product.img_thumb,Product.shop_price'));
                            if (!empty($package_products)) {
                                $package_products[$pk]['PackageProduct']['img'] = $package_product['Product']['img_thumb'];
                                $package_products[$pk]['PackageProduct']['price'] = $package_product['Product']['shop_price'];
                                $show_package_products .= $pv['PackageProduct']['package_product_id'].';';
                            }
                        }
                    }
                    $this->set('show_package_products', $show_package_products);
                    $this->set('package_products', $package_products);
                    $this->set('del_id', $del_id);
                    $this->set('id', $product_id);
                }
                Configure::write('debug', 0);
                $this->layout = 'ajax';
                $this->render('ajax_package_list');
            }
        }
    }

    /*
    * 获取销售属性
    */
    public function getskutype()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $this->operator_privilege('products_edit');
            $product_id = $_POST['pro_id'];
            $pro_type = $_POST['pro_type'];
            $this->Product->set_locale($this->backend_locale);
            $this->Attribute->set_locale($this->backend_locale);
            $attr_ids = $this->ProductTypeAttribute->getattrids($pro_type);
            $attr_conditions['Attribute.type'] = 'buy';
            $attr_conditions['Attribute.id'] = $attr_ids;
            $attr_conditions['Attribute.status'] = '1';
            $attr_cond['conditions'] = $attr_conditions;
            $attr_cond['fields'] = array('Attribute.id','Attribute.code','Attribute.type','AttributeI18n.name','AttributeI18n.attr_value','AttributeI18n.default_value');
            $skutype_list = $this->Attribute->find('all', $attr_cond);
            $this->set('skutype_list', $skutype_list);
            $sku_attr_codelist = array();
            $sku_attr_Idlist = array();
            $sku_attr_code = array();
            if (!empty($skutype_list) && sizeof($skutype_list) > 0) {
                foreach ($skutype_list as $k => $v) {
                    $sku_attr_codelist[$v['Attribute']['code']] = $v['AttributeI18n']['name'];
                    $sku_attr_Idlist[$v['Attribute']['code']] = $v['Attribute']['id'];
                    $sku_attr_code[] = $v['Attribute']['code'];
                }
                $this->set('sku_attr_codelist', $sku_attr_codelist);
                $this->set('sku_pro_type_id', $pro_type);
                $this->set('sku_attr_code', $sku_attr_code);
                $product_info = $this->Product->find('first', array('conditions' => array('Product.id' => $product_id)));
                if (!empty($product_info)) {
                    $product_code = $product_info['Product']['code'];
                    $sku_pro_list = $this->SkuProduct->find('all', array('fields' => array('SkuProduct.id', 'SkuProduct.sku_product_code', 'SkuProduct.price'), 'conditions' => array('SkuProduct.product_code' => $product_code), 'order' => 'SkuProduct.created'));
                    $sku_pro_data = array();
                    if (!empty($sku_pro_list)) {
                        $sku_pro_code_list = array();
                        foreach ($sku_pro_list as $v) {
                            $sku_pro_code_list[] = $v['SkuProduct']['sku_product_code'];
                        }
                        $pro_infos = $this->Product->find('all', array('fields' => array('Product.id', 'Product.code', 'Product.shop_price', 'Product.quantity', 'ProductI18n.name'), 'conditions' => array('Product.product_type_id' => $pro_type, 'Product.code' => $sku_pro_code_list)));
                        if (!empty($pro_infos)) {
                            $p_ids = array();
                            $pro_data_info = array();
                            foreach ($pro_infos as $v) {
                                $p_ids[$v['Product']['code']] = $v['Product']['id'];
                                $pro_data_info[$v['Product']['id']] = $v;
                            }
                            $attrInfo = $this->ProductAttribute->getAttrInfo($p_ids, $this->backend_locale);
                            foreach ($sku_pro_list as $v) {
                                $_pid = isset($p_ids[$v['SkuProduct']['sku_product_code']]) ? $p_ids[$v['SkuProduct']['sku_product_code']] : 0;
                                $sku_data = array();
                                $sku_data['id'] = $v['SkuProduct']['id'];
                                $sku_data['code'] = $v['SkuProduct']['sku_product_code'];
                                $sku_data['price'] = $v['SkuProduct']['price'];
                                $sku_data['name'] = isset($pro_data_info[$_pid]['ProductI18n']['name']) ? $pro_data_info[$_pid]['ProductI18n']['name'] : '';
                                $sku_data['shop_price'] = isset($pro_data_info[$_pid]['Product']['shop_price']) ? $pro_data_info[$_pid]['Product']['shop_price'] : '';
                                $sku_data['quantity'] = isset($pro_data_info[$_pid]['Product']['quantity']) ? $pro_data_info[$_pid]['Product']['quantity'] : '';
                                foreach ($sku_attr_codelist as $kk => $vv) {
                                    $_attr_id = isset($sku_attr_Idlist[$kk]) ? $sku_attr_Idlist[$kk] : 0;
                                    $sku_data['AttrInfo'][$kk] = isset($attrInfo[$_pid][$_attr_id]) ? $attrInfo[$_pid][$_attr_id] : '';
                                }
                                $sku_pro_data[] = $sku_data;
                            }
                        }
                    }
                    $this->set('sku_pro_data', $sku_pro_data);
                }
            }
        } else {
            $this->redirect('/products/');
        }
    }

    /*
        销售属性商品搜索
    */
    public function sku_search_pro()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $this->layout = 'ajax';

            $this->Product->set_locale($this->backend_locale);
            $pro_name = isset($_POST['sku_search_pro_name']) ? trim($_POST['sku_search_pro_name']) : '';
            $pro_type = isset($_POST['pro_type']) ? $_POST['pro_type'] : 0;

            $this->Attribute->set_locale($this->backend_locale);
            $attr_ids = $this->ProductTypeAttribute->getattrids($pro_type);
            $attr_conditions['Attribute.id'] = $attr_ids;
            $attr_conditions['Attribute.type'] = 'buy';
            $attr_conditions['Attribute.status'] = '1';
            $attr_cond['conditions'] = $attr_conditions;
            $attr_cond['fields'] = array('Attribute.id','Attribute.code','Attribute.type','AttributeI18n.name','AttributeI18n.attr_value','AttributeI18n.default_value');
            $skutype_list = $this->Attribute->find('all', $attr_cond);
            $sku_attr_codelist = array();
            $sku_attr_Idlist = array();
            $sku_attr_code = array();
            foreach ($skutype_list as $k => $v) {
                $sku_attr_codelist[$v['Attribute']['code']] = $v['AttributeI18n']['name'];
                $sku_attr_Idlist[$v['Attribute']['code']] = $v['Attribute']['id'];
            }
            $this->set('sku_attr_codelist', $sku_attr_codelist);
            $pids = $this->ProductAttribute->find('list', array('conditions' => array('ProductAttribute.attribute_id' => $sku_attr_Idlist, 'ProductAttribute.attribute_value !=' => ''), 'fields' => array('ProductAttribute.product_id'), 'group' => 'ProductAttribute.product_id'));
            if (!empty($pids)) {
                $pro_cond['Product.id'] = $pids;
            }
            $pro_cond['or']['Product.code like'] = '%'.$pro_name.'%';
            $pro_cond['or']['ProductI18n.name like'] = '%'.$pro_name.'%';
            $pro_cond['Product.option_type_id'] = '0';
            $pro_cond['Product.product_type_id'] = $pro_type;
            $pro_cond['Product.status'] = '1';
            $pro_list = $this->Product->find('all', array('fields' => array('Product.id', 'Product.code', 'Product.shop_price', 'Product.quantity', 'ProductI18n.name'), 'conditions' => $pro_cond, 'order' => 'Product.id'));
            $pro_ids = array();
            foreach ($pro_list as $k => $v) {
                $pro_ids[] = $v['Product']['id'];
            }
            $pro_AttrInfo = $this->ProductAttribute->getAttrInfo($pro_ids, $this->backend_locale);
            $pro_data = array();
            foreach ($pro_list as $k => $v) {
                $pro_data_info = array();
                $pro_data_info['id'] = $v['Product']['id'];
                $pro_data_info['name'] = $v['ProductI18n']['name'];
                $pro_data_info['code'] = $v['Product']['code'];
                $pro_data_info['shop_price'] = $v['Product']['shop_price'];
                $pro_data_info['quantity'] = $v['Product']['quantity'];
                foreach ($sku_attr_codelist as $kk => $vv) {
                    $pro_data_info[$vv]['id'] = $sku_attr_Idlist[$kk];
                    $pro_data_info[$vv]['value'] = isset($pro_AttrInfo[$v['Product']['id']][$sku_attr_Idlist[$kk]]) ? $pro_AttrInfo[$v['Product']['id']][$sku_attr_Idlist[$kk]] : '';
                }
                $pro_data[] = $pro_data_info;
            }
            $this->set('pro_data', $pro_data);
        } else {
            $this->redirect('/products/');
        }
    }

    /*
    * 删除旋转图片
    */
    public function delete_img()
    {
        $result['flag'] = 0;
        if ($this->RequestHandler->isPost()) {
            $this->operator_privilege('products_edit');
            $product_code = $_POST['product_code'];
            $img_dir = WWW_ROOT.'media/360Rotation/'.$product_code;
            if ($this->deldir($img_dir)) {
                $result['flag'] = 1;
            } else {
                $result['flag'] = 0;
            }
            die(json_encode($result));
        }
    }

    public function deldir($dir)
    {
        //先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != '.' && $file != '..') {
                $fullpath = $dir.'/'.$file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *@套装商品列表箭头排序
     *@$updowm 排序上下操作
     *@$id id
     *@$nextone ?nextone
     *@$product_id 套装主商品id
     *
     *@author   hechang 
     */
    public function change_packageorder($updowm, $id, $nextone, $product_id)
    {
        //如果值相等重新自动排序
        $a = $this->PackageProduct->query('SELECT DISTINCT `product_id`
			FROM `svoms_package_products` as PackageProduct
			WHERE `product_id` = "'.$product_id.'"
			GROUP BY `orderby`,`product_id`
			HAVING count( * ) >1');
        foreach ($a as $v) {
            $all = $this->PackageProduct->find('all', array('conditions' => array('PackageProduct.product_id' => $v['PackageProduct']['product_id']), 'order' => 'PackageProduct.id asc', 'contain' => false));
            foreach ($all as $k => $vv) {
                $all[$k]['PackageProduct']['orderby'] = $k + 1;
            }
            $this->PackageProduct->saveAll($all);
        }
        if ($nextone == 0) {
            $module_one = $this->PackageProduct->find('first', array('conditions' => array('PackageProduct.id' => $id)));
            if ($updowm == 'up') {
                $module_change = $this->PackageProduct->find('first', array('conditions' => array('PackageProduct.orderby <' => $module_one['PackageProduct']['orderby'], 'PackageProduct.product_id' => $module_one['PackageProduct']['product_id']), 'order' => 'orderby desc'));
            }
            if ($updowm == 'down') {
                $module_change = $this->PackageProduct->find('first', array('conditions' => array('PackageProduct.orderby >' => $module_one['PackageProduct']['orderby'], 'PackageProduct.product_id' => $module_one['PackageProduct']['product_id']), 'order' => 'orderby asc'));
            }
        }
        //交换排序值
        $t = $module_one['PackageProduct']['orderby'];
        $module_one['PackageProduct']['orderby'] = $module_change['PackageProduct']['orderby'];
        $module_change['PackageProduct']['orderby'] = $t;
        //交换排序值end
        if ($module_change['PackageProduct']['orderby'] != '') {
            $this->PackageProduct->saveAll($module_one);
            $this->PackageProduct->saveAll($module_change);
        }
        //$conditions['PackageProduct.product_id'] = $product_id;
        $package_products = $this->PackageProduct->find_package_product($product_id);
        $show_package_products = '';
        if (!empty($package_products)) {
            foreach ($package_products as $pk => $pv) {
                $package_product = $this->Product->find('first', array('conditions' => array('Product.id' => $pv['PackageProduct']['package_product_id']), 'fields' => 'Product.img_thumb,Product.shop_price'));
                if (!empty($package_products)) {
                    $package_products[$pk]['PackageProduct']['img'] = $package_product['Product']['img_thumb'];
                    $package_products[$pk]['PackageProduct']['price'] = $package_product['Product']['shop_price'];
                    $show_package_products .= $pv['PackageProduct']['package_product_id'].';';
                }
            }
        }
        $this->set('show_package_products', $show_package_products);
        $this->set('package_products', $package_products);
        $del_id = '';
        $this->set('del_id', $del_id);
        $this->set('id', $product_id);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->render('ajax_package_list');
    }

    public function uploadproduct()
    {
        $this->operator_privilege('products_upload');
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $flag_code = 'product_import';
        $categories_tree = $this->CategoryProduct->tree('P', $this->locale);
        $this->set('categories_tree', $categories_tree);
        $this->Profile->set_locale($this->backend_locale);
        $profilefiled_codes = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
        if (isset($profilefiled_codes) && !empty($profilefiled_codes)) {
            $this->set('profilefiled_codes', $profilefiled_codes);
        }
    }

    public function uploadproductpreview()
    {
        $this->operator_privilege('products_upload');
        if ($this->RequestHandler->isPost()) {
            $this->menu_path = array('root' => '/product/','sub' => '/products/');
            $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
            $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
            $this->set('title_for_layout', $this->ld['preview'].' - '.$this->configs['shop_name']);
            $this->set('extension_code', array('' => $this->ld['real_product'], 'virtual_card' => $this->ld['virtual_cards']));
            $flag_code = 'product_import';
            $this->Profile->set_locale($this->locale);
            set_time_limit(300);
            if (!empty($_FILES['file'])){
                if ($_FILES['file']['error'] > 0) {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/products/uploadproduct';</script>";
                } else {
                    $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                    $this->set('category_id', $_POST['category_id']);
                    $attr_code_arr = array();
                    $this->Attribute->set_locale($this->backend_locale);
                    $attr_code_arr = $this->Attribute->find('list', array('conditions' => array('Attribute.status' => 1, 'Attribute.type !=' => 'customize'), 'fields' => 'Attribute.code'));
                    $show_attr_code_arr = array();
                    $this->Profile->set_locale($this->locale);
                    $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
                    $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
                    if (empty($profilefiled_info)) {
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/products/uploadproduct';</script>";
                        die();
                    }
                    $key_arr = array();
                    $key_desc=array();
                    $key_code=array();
                    foreach ($profilefiled_info as $k => $v) {
                        $fields_k = explode('.', $v['ProfileFiled']['code']);
                        $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                        $key_desc[]= $v['ProfilesFieldI18n']['description'];
                        $key_code[$v['ProfilesFieldI18n']['description']]=isset($fields_k[1]) ? $fields_k[1] : '';
                    }
                    $this->set('key_code',$key_code);
                    $preview_key=array();
                    $csv_export_code = 'gb2312';
                    $i = 0;
                    while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                        if ($i == 0) {
                            $check_row = $row[0];
                            $row_count = count($row);
                            foreach ($row as $k => $v) {
                                $preview_key[]=iconv('GB2312', 'UTF-8', $v);
                                if (isset($key_arr[$k]) && !empty($key_arr[$k])) {
                                    continue;
                                } else {
                                  foreach ($attr_code_arr as $atv) {
                                        if (strcasecmp($v, $atv) == 0) {
                                           $a_peg = 1;
                                        }
                                   }
                                    if (isset($a_peg) && $a_peg == 1) {
                                        $key_arr[$k] = $v;
                                      $show_attr_code_arr[] = $v;
                                       unset($a_peg);
                                   }
                                }
                            }
                            ++$i;
                        }
                        $temp = array(); 
                        foreach ($row as $k => $v) {
                        	   $data_key_code=isset($key_code[$preview_key[$k]])?$key_code[$preview_key[$k]]:'';
                            $temp[$preview_key[$k]] = $v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                          if (isset($key_arr[$k]) && !empty($key_arr[$k])) {
                               $temp[$data_key_code] = $v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                            } else {
                                foreach ($attr_code_arr as $atv) {
                                   if (strcasecmp($v, $atv) == 0) {                                       $a_pegva = 1;
                                 }
                               }                              if (isset($a_pegva) && $a_pegva == 1) {
                                   $temp[$v] = $v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                   unset($a_pegva);
                               }
                           }
                        }
                        if (!isset($temp) || empty($temp)) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/products/uploadproduct';</script>";
                        }
                        $data[] = $temp;
                    }
                    fclose($handle);
                    $check_row = iconv('GB2312', 'UTF-8', $check_row);
                    $num_count = count($profilefiled_info) + count($show_attr_code_arr) + count($attr_code_arr);
                    
                    if(empty($preview_key)){
                       echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('上传文件格式不标准');window.location.href='/admin/products/uploadproduct';</script>";
                    }
                    $this->set('attr_code_arr', $show_attr_code_arr);
                     $this->set('show_attr_code_arr',$attr_code_arr);
                    $this->set('profilefiled_info', $profilefiled_info);
                    $this->set('uploads_list', $data);
                      
                      $titer_arr="";
                      $ip=0;
                       foreach($data as $dak => $dav){
                       	$ip++;
                    	if($ip==1){continue;}  
                         foreach($attr_code_arr as $cc=>$tv){
                         	 if(isset($dav[$tv])){
                         	  $ss=trim($dav[$tv]);
                         	if($ss!=""){$titer_arr[]=$tv;}
                          	}
                                  
                            }
                          }
                  $this->set('titer_arr',$titer_arr);//记录填写的属性
                }
            }
        } else {
            $this->redirect('/products/');
        }
    }

    public function batch_add_products()
    {
        if ($this->RequestHandler->isPost()) {
            $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : 0;
            $checkbox_arr = $_REQUEST['checkbox'];
            $name = '';
            $error_msg = '';
            $i = 0;
            $this->Product->hasOne = array();
            $this->Product->hasMany = array();
            //取出所有属性code的集合
            $attr_infos = array();
            $attr_id_infos = array();
             $this->Attribute->set_locale($this->backend_locale);
            $attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.status' => 1, 'Attribute.type !=' => 'customize')));
           
            foreach ($attr_info as $lk=> $v) {
                $attr_infos[] = $v['Attribute']['code'];
                $attr_id_infos[$v['Attribute']['code']] = $v['Attribute']['id'];
            } 
            //pr($attr_infos);
               //取出品牌
               $brand_list = array();
            $this->Brand->set_locale($this->backend_locale);
            $bran_sel = $this->Brand->find('all', array('conditions' => array('Brand.status' => 1), 'fields' => array('Brand.id', 'BrandI18n.name'), 'order' => 'Brand.id'));
            foreach ($bran_sel as $v) {
                $brand_list[$v['BrandI18n']['name']] = $v['Brand']['id'];
            } 
               //取出商品分类
               $product_category_names = array();
            $this->CategoryProduct->set_locale($this->backend_locale);
            $category_tree = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.status' => 1), 'fields' => array('CategoryProduct.id', 'CategoryProductI18n.name')));
            foreach ($category_tree as $v) {
                $product_category_names[$v['CategoryProductI18n']['name']] = $v['CategoryProduct']['id'];
            }
            //取出商品属性组
               $product_type_names = array();
            $this->ProductType->set_locale($this->backend_locale);
            $attribute_type_tree = $this->ProductType->find('all', array('conditions' => array('ProductType.status' => 1, 'ProductType.id !=' => 0)));
            foreach ($attribute_type_tree as $v) {
                $product_type_names[$v['ProductTypeI18n']['name']] = $v['ProductType']['id'];
            }
            $name_not_be_empty = substr($this->ld['name_not_be_empty'], 2);
           
            foreach ($this->data as $key => $data) {
                if (!in_array($key, $checkbox_arr)) {
                    continue;  /////////////判断是否被选中    
                }
                // pr($data); 
                /////////////判断是否有 code  没有code 
                if (!isset($data['code']) || $data['code'] == '') {
                    $msg = $this->ld['sku'].$name_not_be_empty.'\\r\\n';
                    $error_msg .= $this->ld['number'].$key.' '.$msg;
                    continue;
                }/////////////判断是否有 name  
                if (!isset($data['name']) || $data['name'] == '') {
                    $msg = $this->ld['product_name'].$name_not_be_empty.'\\r\\n';
                    $error_msg .= $this->ld['number'].$key.' '.$msg;
                    continue;
                }
                   //如果有类目自动保存pr($data);
                if (isset($data['category_type_code']) && $data['category_type_code'] != '') {
                    $CategoryType = '';
                    $CategoryType['code'] = $data['category_type_code'];
                    $info = $this->CategoryType->find('first', array('conditions' => array('CategoryType.code' => $data['category_type_code'])));
                    if (empty($info)) {
                        $this->CategoryType->saveAll($CategoryType);
                        $Product['category_type_id'] = $this->CategoryType->id;
                        $CategoryTypeI18n['name'] = $data['category_type_code'];
                        $CategoryTypeI18n['locale'] = $this->backend_locale;
                        $CategoryTypeI18n['category_type_id'] = $this->CategoryType->id;
                        $this->CategoryTypeI18n->saveAll($CategoryTypeI18n);
                    } else {
                        $Product['category_type_id'] = $info['CategoryType']['id'];
                    }
                }////如果有类目自动保存OVER

                $ProductI18n['name'] = $data['name'];
                $ProductI18n['meta_keywords'] = isset($data['meta_keywords']) ? $data['meta_keywords'] : '';
                $ProductI18n['meta_description'] = isset($data['meta_description']) ? $data['meta_description'] : '';
                $ProductI18n['description'] = isset($data['description']) ? $data['description'] : '';
                $name = isset($data['img_thumb']) ? $data['img_thumb'] : '';
                $ProductGallery['img_thumb'] = null;
                $ProductGallery['img_detail'] = null;
                $ProductGallery['img_original'] = null;
                $ProductGallery['img_big'] = null;
                if (isset($data['img_thumb']) && !empty($data['img_thumb'])) {
                    $ProductGallery['img_thumb'] = $data['img_thumb'];
                }
                if (isset($data['img_detail']) && !empty($data['img_detail'])) {
                    $ProductGallery['img_detail'] = $data['img_detail'];
                }
                if (isset($data['img_original']) && !empty($data['img_original'])) {
                    $ProductGallery['img_original'] = $data['img_original'];
                }
                if (isset($data['img_big']) && !empty($data['img_big'])) {
                    $ProductGallery['img_big'] = $data['img_big'];
                }
                $product_info = $this->Product->find('first', array('conditions' => array('Product.code' => $data['code'])));
                $Product = empty($product_info['Product']) ? array() : $product_info['Product'];
                $Product['code'] = $data['code'];
                $Product['option_type_id']=isset($data['option_type_id'])&&$data['option_type_id']!=''&&$data['option_type_id']>=0&&$data['option_type_id']<=2?$data['option_type_id']:0;
                
                $Product['status'] = 1;
                $Product['category_id'] = isset($data['category_id']) ? (isset($product_category_names[$data['category_id']]) ? $product_category_names[$data['category_id']] : $category_id) : $category_id;
                $data['shop_price'] = isset($data['shop_price']) ? trim($data['shop_price']) : (isset($Product['shop_price']) ? $Product['shop_price'] : 0);
                if (isset($data['shop_price']) && !is_numeric($data['shop_price'])) {
                    $Product['shop_price'] = 0;
                    $Product['market_price'] = 0;
                    $Product['custom_price'] = $data['shop_price'];
                } else {
                    $Product['shop_price'] = isset($data['shop_price']) && $data['shop_price'] != '' ? $data['shop_price'] : (isset($data['market_price']) && $data['market_price'] != '' ? $data['market_price'] : 0);
                    $Product['market_price'] = isset($data['market_price']) && $data['market_price'] != '' ? $data['market_price'] : (isset($data['shop_price']) && $data['shop_price'] != '' ? $data['shop_price'] : 0);
                }
                $Product['weight'] = !empty($data['weight']) ? $data['weight'] : (isset($Product['weight']) ? $Product['weight'] : 0);
                $Product['quantity'] = !empty($data['quantity']) ? $data['quantity'] : (isset($Product['quantity']) ? $Product['quantity'] : 0);
                $Product['purchase_price'] = (isset($data['purchase_price']) && !empty($data['purchase_price'])) ? $data['purchase_price'] : (isset($Product['purchase_price']) ? $Product['purchase_price'] : 0);
                $Product['recommand_flag'] = !empty($data['recommand_flag']) ? $data['recommand_flag'] : (isset($Product['recommand_flag']) ? $Product['recommand_flag'] : 0);
                $Product['forsale'] = !empty($data['forsale']) ? $data['forsale'] : (isset($Product['forsale']) ? $Product['forsale'] : 0);
                $Product['alone'] = !empty($data['alone']) ? $data['alone'] : (isset($Product['alone']) ? $Product['alone'] : 0);
                $Product['extension_code'] = '';

                $Product['img_thumb'] = isset($data['img_thumb']) && !empty($data['img_thumb']) ? $data['img_thumb'] : (isset($Product['img_thumb']) ? $Product['img_thumb'] : '');
                $Product['img_detail'] = isset($data['img_detail']) && !empty($data['img_detail']) ? $data['img_detail'] : (isset($Product['img_detail']) ? $Product['img_detail'] : '');
                $Product['img_original'] = isset($data['img_original']) && !empty($data['img_original']) ? $data['img_original'] : (isset($Product['img_original']) ? $Product['img_original'] : '');
                $Product['img_big'] = isset($data['img_big']) && !empty($data['img_big']) ? $data['img_big'] : (isset($Product['img_big']) ? $Product['img_big'] : '');
                $Product['min_buy'] = !empty($data['min_buy']) ? $data['min_buy'] : (isset($Product['min_buy']) ? $Product['min_buy'] : 1);
                $Product['max_buy'] = !empty($data['max_buy']) ? $data['max_buy'] : (isset($Product['max_buy']) ? $Product['max_buy'] : 100);
                $Product['point'] = !empty($data['point']) ? $data['point'] : (isset($Product['point']) ? $Product['point'] : 0);
                $Product['point_fee'] = !empty($data['point_fee']) ? $data['point_fee'] : (isset($Product['point_fee']) ? $Product['point_fee'] : 0);
                $brand = 0;
                if (isset($data['brand_id'])) {
                    $brand = isset($brand_list[$data['brand_id']]) ? $brand_list[$data['brand_id']] : 0;
                }
                $Product['brand_id'] = $brand;
                $Product['product_type_id'] = isset($data['product_type_id']) && isset($product_type_names[$data['product_type_id']]) ? $product_type_names[$data['product_type_id']] : 0;
                //去查询关联的属性ID
                
                //pr($Product);
              if (empty($Product['quantity'])) {
                    $Product['quantity'] = $this->configs['default_stock'];
                }
                
                //pr($Product);die();
                if (empty($Product['id'])) {
                    $this->Product->saveAll(array('Product' => $Product));
                    $id = $this->Product->id;
                } else {
                    $this->Product->save(array('Product' => $Product));
                    $id = $Product['id'];
                }
                ++$i;
                //属性保存  公共属性
                if (is_array($this->front_locales)) {
                   $this->ProductAttribute->deleteAll(array('ProductAttribute.product_id' => $id));
                   if($Product['product_type_id']!=0){
                   $attbute_id=$this->ProductTypeAttribute->find('list',array('fields'=>'ProductTypeAttribute.attribute_id','conditions'=>array('ProductTypeAttribute.product_type_id'=>$Product['product_type_id'])));
                   
                   // pr($attbute_id);
                   //pr($data);//数据
                   //pr($attribtueID);//属性组ID
                   if(!empty($attbute_id)){
                      $option_value=$this->AttributeOption->find('all',array('find'=>'AttributeOption.option_value','conditions'=>array('AttributeOption.attribute_id'=>$attbute_id)));
                   //pr($option_value);//属性组的子级
                      $attbutes_array=array();
                     foreach($option_value as $cc=>$dd){
                                 $attbutes_array[$dd['AttributeOption']['attribute_id']][]=$dd['AttributeOption']['option_value'];
                                }
                        //pr($attbutes_array);die();
                        
                    foreach($this->front_locales as $k =>$v){ 
                                foreach ($attr_infos as $ak =>$a){ 
                                if (isset($data['ProductAttribute'][$a]) && !empty($data['ProductAttribute'][$a])) {
                                $att = array();
                                $att['product_id'] = $id;
                                $att['locale'] = $v['Language']['locale'];
                                $att['attribute_id'] = isset($attr_id_infos[$a]) ? $attr_id_infos[$a] : 0; 
                                $att['attribute_value'] = $data['ProductAttribute'][$a];
                                if(in_array($att['attribute_id'],$attbute_id)&&in_array($att['attribute_value'],$attbutes_array[$att['attribute_id']])||$att['attribute_value']=='')
                                {
                                	//pr($att);
                                $this->ProductAttribute->saveAll($att);
                                }
                            } 
                            
                        } 
                      }
                   }
                   }
                } 
                
                $Product['id'] = $id;
                if (!empty($Product['id'])) {
                    $ProductI18n['product_id'] = $id;
                    if (is_array($this->front_locales)) {
                        foreach ($this->front_locales as $k => $v) {
                            $ProductI18n = $this->ProductI18n->find('first', array('conditions' => array('locale' => $v['Language']['locale'], 'product_id' => $id)));
                            $ProductI18n = $ProductI18n['ProductI18n'];
                            $ProductI18n['name'] = $data['name'];
                            $ProductI18n['meta_keywords'] = $data['meta_keywords'];
                            $ProductI18n['meta_description'] = $data['meta_description'];
                            $ProductI18n['description'] = !empty($data['description'])?$data['description']: '';
                            $ProductI18n['product_id'] = $id;
                            $ProductI18n['locale'] = $v['Language']['locale'];
                            if (!empty($ProductI18n['id'])) {
                                $this->ProductI18n->save($ProductI18n);
                            } else {
                                $this->ProductI18n->saveAll($ProductI18n);
                            }
                        }
                    }
                    if (!empty($ProductGallery['img_thumb']) || !empty($ProductGallery['img_detail']) || !empty($ProductGallery['img_original'])) {
                        //货号对应图片
                        if (!empty($ProductGallery['img_thumb'])) {
                            $path = $_SERVER['DOCUMENT_ROOT'].'/data/import'.$ProductGallery['img_thumb'];
                            //路径存在，图片空间上传图片流程
                            $image_name = substr($path, strrpos($path, '/') + 1);
                            //获取图片格式(后缀)
                            $type = substr($path, strrpos($path, '.'));
                            $files_type = '*.gif; *.jpg; *.png; *.jpeg; *.GIF; *.JPG; *.PNG; *.JPEG';
                            $check_type = strstr($files_type, $type);
                            //获取图片名(不包括后缀)
                            $name = substr($image_name, 0, strrpos($image_name, '.'));
                            $code = isset($Product['code']) ? $Product['code'] : '';
                            if (file_exists($path)) {
                                if ($check_type != '') {
                                    //图片格式正确，创建图片目录，移动图片
                                    //列表缩略图宽度
                                    $thumbl_image_width = isset($this->configs['small_img_width']) ? $this->configs['small_img_width'] : 160;
                                    //列表缩略图高度
                                    $thumb_image_height = isset($this->configs['small_img_height']) ? $this->configs['small_img_height'] : 160;
                                    //中图宽度
                                    $image_width = isset($this->configs['mid_img_width']) ? $this->configs['mid_img_width'] : 400;
                                    //中图高度
                                    $image_height = isset($this->configs['mid_img_height']) ? $this->configs['mid_img_height'] : 400;
                                    //大图宽度
                                    $image_width_big = isset($this->configs['big_img_width']) ? $this->configs['big_img_width'] : 800;
                                    //大图高度
                                    $image_height_big = isset($this->configs['big_img_height']) ? $this->configs['big_img_height'] : 800;
                                    $imgaddr_original = WWW_ROOT.'/img/photos/'.date('Ym').'/'.$category_id.'/'.$this->admin['id'].'/original/';
                                    $imgaddr_detail = WWW_ROOT.'/img/photos/'.date('Ym').'/'.$category_id.'/'.$this->admin['id'].'/detail/';
                                    $imgaddr_big = WWW_ROOT.'/img/photos/'.date('Ym').'/'.$category_id.'/'.$this->admin['id'].'/big/';
                                    $imgaddr_small = WWW_ROOT.'/img/photos/'.date('Ym').'/'.$category_id.'/'.$this->admin['id'].'/small/';
                                    $this->mkdirs($imgaddr_original);
                                    $this->mkdirs($imgaddr_detail);
                                    $this->mkdirs($imgaddr_big);
                                    $this->mkdirs($imgaddr_small);
                                    $result_url = copy($path, $imgaddr_original.$image_name);
                                    if ($result_url) {
                                        $img_original = $imgaddr_original.$image_name;//原图地址  
                                        $img_detail = $imgaddr_detail.$image_name;//详细图 中图地址
                                        $img_thumb = $imgaddr_small.$image_name;//缩略图地址
                                        $img_big = $imgaddr_big.$image_name;//大图地址
                                        //商品缩略图
                                        $image_name = $this->make_thumb($img_original, $thumbl_image_width, $thumb_image_height, '#FFFFFF', $name, $imgaddr_small, $type);
                                        $image_name = $this->make_thumb($img_original, $image_width, $image_height, '#FFFFFF', $name, $imgaddr_detail, $type);
                                        $image_name = $this->make_thumb($img_original, $image_width_big, $image_height_big, '#FFFFFF', $name, $imgaddr_big, $type);
                                        //保存到图片空间数据库
                                        $photo_img_small = str_replace(WWW_ROOT, '', $img_thumb);
                                        $photo_img_detail = str_replace(WWW_ROOT, '', $img_detail);
                                        $photo_img_original = str_replace(WWW_ROOT, '', $img_original);
                                        $photo_img_big = str_replace(WWW_ROOT, '', $img_big);
                                        $photo_img_original_info = getimagesize($imgaddr_original.$image_name);
                                        /*$photo_name[0]	= substr($_FILES["Filedata"]["name"],0,strripos($_FILES["Filedata"]["name"],"."));
                                        $photo_name[1]	= substr($_FILES["Filedata"]["name"],strripos($_FILES["Filedata"]["name"],".")+1);*/
                                        $themes_host = Configure::read('themes_host');
                                        $photo_category_galleries = array(
                                            'photo_category_id' => isset($category_id) ? $category_id : '0',
                                            'name' => $name,
                                            'type' => substr($type, 1, strlen($type) - 1),
                                            'original_size' => intval(filesize($imgaddr_original.$image_name) / 1024),
                                            'original_pixel' => $photo_img_original_info[0].'*'.$photo_img_original_info[1],
                                            'img_small' => $photo_img_small,
                                            'img_detail' => $photo_img_detail,
                                            'img_original' => $photo_img_original,
                                            'img_big' => $photo_img_big,
                                            'orderby' => '50',
                                        );
                                        //保存到图片空间表
                                        $this->PhotoCategoryGallery->saveAll($photo_category_galleries);
                                        $Product['img_thumb'] = isset($photo_img_small) ? $photo_img_small : '';
                                        $Product['img_detail'] = isset($photo_img_detail) ? $photo_img_detail : '';
                                        $Product['img_original'] = isset($photo_img_original) ? $photo_img_original : '';
                                        $Product['img_big'] = isset($photo_img_big) ? $photo_img_big : '';
                                        $ProductGallery['img_thumb'] = isset($photo_img_small) ? $photo_img_small : '';
                                        $ProductGallery['img_detail'] = isset($photo_img_detail) ? $photo_img_detail : '';
                                        $ProductGallery['img_original'] = isset($photo_img_original) ? $photo_img_original : '';
                                        $ProductGallery['img_big'] = isset($photo_img_big) ? $photo_img_big : '';
                                        $ProductGallery['product_id'] = $id;
                                        if (!empty($ProductI18n['id'])) {
                                            $this->Product->save($Product);
                                            $ProductGallery['product_id'] = $this->Product->id;
                                            $this->ProductGallery->saveAll(array('ProductGallery' => $ProductGallery));
                                        } else {
                                            $this->Product->saveAll($Product);
                                            $this->ProductGallery->saveAll(array('ProductGallery' => $ProductGallery));
                                        }

                                        $result['error'] = false;
                                        if (!file_exists($img_thumb)) {
                                            $error_msg .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['thumbnail_generate_failed'].'\\r\\n';
                                        }
                                        if (!file_exists($img_detail)) {
                                            $error_msg .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['detail_image_generate_failed'].'\\r\\n';
                                        }
                                        if (!file_exists($img_original)) {
                                            $error_msg .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['original_image_build_failure'].'\\r\\n';
                                        }
                                        if (!file_exists($img_big)) {
                                            $error_msg .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['big_image_generate_failure'].'\\r\\n';
                                        }
                                    }
                                } else {
                                    //图片格式不正确，返回报错
                                    $error_msg .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['file_format_valid'].'\\r\\n';
//									echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_format_valid'].":".$name."');window.location.href='/admin/products/'</script>";
//								    die();
                                }
                            } else {
                                //路径不存在，报错
                                $error_msg .= $this->ld['code'].$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['file_fath'].$this->ld['not_exist'].'\\r\\n';
                            }
                        }
                    }
                }
            }
               //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['bulk_upload_products'], $this->admin['id']);
            }
            if ($error_msg != '') {
                $result['error_msg'] = $this->ld['error_message'].':\\r\\n'.$error_msg;
            } else {
                $result['error_msg'] = '';
            }
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".$this->ld['import_success'].':'.$i.'\\r\\n'.$result['error_msg']."');window.location.href='/admin/products/'</script>";
            die();
        }
       $this->redirect('/products/');
    }

    public function download_csv_example()
    {
        $this->Profile->set_locale($this->locale);
        $this->Profile->hasOne = array();
        $flag_code = 'product_import';
        //查询档案配置主表ID
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));//pr($profile_id);
        $tmp = array();//详细描述
        $fields_array = array();
        $newdatas = array();
        $filename = '商品导出'.date('Ymd').'.csv';
        //取出所有属性  
        $this->Attribute->set_locale($this->backend_locale);
        $pubile_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.status' => 1, 'Attribute.type !=' => 'customize'), 'fields' => 'Attribute.id,Attribute.code'));
        //pr($pubile_attr_info);die();
        $pat = array();
        if (!empty($pubile_attr_info)) {
            foreach ($pubile_attr_info as $k => $p) {
                $pat[$k]['id'] = $p['Attribute']['id'];
                $pat[$k]['name'] = $p['Attribute']['code'];
            }
        }
        $pat_ids = array();
        if (!empty($pubile_attr_info)) {
            foreach ($pubile_attr_info as $pa) {
                $pat_ids[] = $pa['Attribute']['id'];
            }
        }
        $brand_names = array();//商品品牌
        $product_category_names = array();//商品分类
        $product_type_names = array();//商品属性组
         //获得状态为 1 的 所有的商品表 商品编号 （code）商品id（id）
        $product_ids = $this->Product->find('list', array('fields' => array('Product.code', 'Product.id'), 'conditions' => array('Product.status' => 1)));
         //查询档案配置表　　查到的是　档案配置的　（代码）　和　（描述）
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            foreach ($profilefiled_info as $k => $v) {
                $tmp[] = $v['ProfilesFieldI18n']['description']; //description(描述)
                $fields_array[] = $v['ProfileFiled']['code'];         //获取字段
            }   
             //判断XXX 如果在 档案配置 代码里面 查询商品属性组 
            if (in_array('Product.product_type_id', $fields_array)) {
                $this->ProductType->set_locale($this->backend_locale);
                $attribute_type_tree = $this->ProductType->find('all', array('conditions' => array('ProductType.status' => 1, 'ProductType.id !=' => 0)));
                //获得商品分类名称
                foreach ($attribute_type_tree as $v) {
                    $product_type_names[$v['ProductType']['id']] = $v['ProductTypeI18n']['name'];
                }
            }	
                 //如果 商品分类id 在这个数组里面  就查询 产品分类表
            if (in_array('Product.category_id', $fields_array)) {
                $this->CategoryProduct->set_locale($this->backend_locale);//指定这个模型 查询出来的语言（后台语言）
                $category_tree = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.status' => 1), 'fields' => array('CategoryProduct.id', 'CategoryProductI18n.name')));
                //获得 产品分类的表 的 分类名称
                foreach ($category_tree as $v) {
                    $product_category_names[$v['CategoryProduct']['id']] = $v['CategoryProductI18n']['name'];
                }
            }      //pr($product_category_names);
            //判断 品牌ID 字段 是否在档案配置 代码里面   
            if (in_array('Product.brand_id', $fields_array)) {
                $this->Brand->set_locale($this->backend_locale);
                $bran_sel = $this->Brand->find('all', array('conditions' => array('Brand.status' => 1), 'fields' => array('Brand.id', 'Brand.code', 'BrandI18n.name'), 'order' => 'Brand.orderby,Brand.code'));
                //获得 品牌名称
                foreach ($bran_sel as $bk => $bv) {
                    $brand_names[$bv['Brand']['id']] = $bv['BrandI18n']['name'];
                }
            }
         }  
        foreach ($pat as $pp) {
            $tmp[] = $pp['name'];
        }
        $attr_info = $this->ProductAttribute->product_list_format($product_ids, $pat_ids, $this->backend_locale);
        $newdatas[] = $tmp;
           
             $product_fields=$fields_array;
             $product_fields[]="Product.id";
            
           $product_all = $this->Product->find('all', array('fields' =>$product_fields, 'conditions' => array('Product.status' => 1, 'ProductI18n.locale' => $this->backend_locale),'limit'=>10)); 
           //$product_all = $this->Product->find('all', array('fields' =>$product_fields, 'conditions' => array('Product.id' =>841918, 'ProductI18n.locale' => $this->backend_locale))); 
            $product_id=array();
        foreach ($product_all as $k => $v) {  
            $product_id[]= $v['Product']['id'];
        }
         //去查询所有商品ID　的　商品属性　存入一个集合
         $ProductAttribute_arr=$this->ProductAttribute->find('all',array('conditions'=>array("ProductAttribute.product_id"=>$product_id)));
         $ProductAttribute="";
         foreach($ProductAttribute_arr as $ks=>$vs){
            $ProductAttribute[$vs['ProductAttribute']['product_id']][$vs['ProductAttribute']['attribute_id']]=$vs['ProductAttribute']['attribute_value']; 
         }
         foreach ($product_all as $k => $v) {
                $user_tmp = array(); 	
	            foreach ($fields_array as $kk => $vv) { 
                    if ($vv == 'Product.category_id') {
                        $user_tmp[] = isset($product_category_names[$v['Product']['category_id']]) ? $product_category_names[$v['Product']['category_id']] : '';
                    } elseif ($vv == 'Product.brand_id') {
                        $user_tmp[] = isset($brand_names[$v['Product']['brand_id']]) ? $brand_names[$v['Product']['brand_id']] : '';
                    } elseif ($vv == 'Product.product_type_id') {
                        $user_tmp[] = isset($product_type_names[$v['Product']['product_type_id']]) ? $product_type_names[$v['Product']['product_type_id']] : '';
                    }else {
                        $fields_kk = explode('.', $vv);
                        $user_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                    }
              }
              //循环属性
              foreach($pat as $kc=>$vc){ 
		          $user_tmp[] = isset($ProductAttribute[$v['Product']['id']][$vc['id']])?$ProductAttribute[$v['Product']['id']][$vc['id']]:" ";
		      }
              $newdatas[] = $user_tmp;
          }
          //pr($newdatas); die();
          $this->Phpcsv->output($filename,$newdatas);
          exit();   
    }

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

    public function mkdirs($path, $mode = 0777)
    {
        $dirs = explode('/', $path);
        $pos = strrpos($path, '.');
        if ($pos === false) {
            $subamount = 0;
        } else {
            $subamount = 1;
        }
        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
            $thispath = '';
            for ($cc = 0; $cc <= $c; ++$cc) {
                $thispath .= $dirs[$cc].'/';
            }
            if (!file_exists($thispath)) {
                mkdir($thispath, $mode);
            }
        }
    }

    /**
     * 创建图片的缩略图.
     *
     * @param string $img          原始图片的路径
     * @param int    $thumb_width  缩略图宽度
     * @param int    $thumb_height 缩略图高度
     * @param int    $filename     图片名..
     * @param strint $dir          指定生成图片的目录名
     *
     * @return mix 如果成功返回缩略图的路径，失败则返回false
     */
    public function make_thumb($img, $thumb_width = 0, $thumb_height = 0, $bgcolor = '#FFFFFF', $filename, $dir, $imgname)
    {
        //echo $filename;
        /* 检查缩略图宽度和高度是否合法 */
        if ($thumb_width == 0 && $thumb_height == 0) {
            return false;
        }
        /* 检查原始文件是否存在及获得原始文件的信息 */
        $org_info = @getimagesize($img);
        if (!$org_info) {
            return false;
        }

        $img_org = $this->img_resource($img, $org_info[2]);
        /* 原始图片以及缩略图的尺寸比例 */
        $scale_org = $org_info[0] / $org_info[1];
        /* 处理只有缩略图宽和高有一个为0的情况，这时背景和缩略图一样大 */
        if ($thumb_width == 0) {
            $thumb_width = $thumb_height * $scale_org;
        }
        if ($thumb_height == 0) {
            $thumb_height = $thumb_width / $scale_org;
        }

        /* 创建缩略图的标志符 */
        $img_thumb = @imagecreatetruecolor($thumb_width, $thumb_height);//真彩

        /* 背景颜色 */

        if (empty($bgcolor)) {
            $bgcolor = $bgcolor;
        }
        $bgcolor = trim($bgcolor, '#');
        sscanf($bgcolor, '%2x%2x%2x', $red, $green, $blue);
        $clr = imagecolorallocate($img_thumb, $red, $green, $blue);
        imagefilledrectangle($img_thumb, 0, 0, $thumb_width, $thumb_height, $clr);

        if ($org_info[0] / $thumb_width > $org_info[1] / $thumb_height) {
            $lessen_width = $thumb_width;
            $lessen_height = $thumb_width / $scale_org;
        } else {
            /* 原始图片比较高，则以高度为准 */
            $lessen_width = $thumb_height * $scale_org;
            $lessen_height = $thumb_height;
        }
        $dst_x = ($thumb_width  - $lessen_width)  / 2;
        $dst_y = ($thumb_height - $lessen_height) / 2;

        /* 将原始图片进行缩放处理 */
        imagecopyresampled($img_thumb, $img_org, $dst_x, $dst_y, 0, 0, $lessen_width, $lessen_height, $org_info[0], $org_info[1]);
        /* 生成文件 */
        if (function_exists('imagejpeg')) {
            $filename .= $imgname;
            imagejpeg($img_thumb, $dir.$filename, 100);
            /*pr($img_thumb);
            pr($filename);die;*/
        } elseif (function_exists('imagegif')) {
            $filename .= imgname;
            imagegif($img_thumb, $dir.$filename, 100);
        } elseif (function_exists('imagepng')) {
            $filename .= $imgname;
            imagepng($img_thumb, $dir.$filename, 100);
        } else {
            return false;
        }
        imagedestroy($img_thumb);
        imagedestroy($img_org);
        //确认文件是否生成
        if (file_exists($dir.$filename)) {
            return  $filename;
        } else {
            return false;
        }
    }

    /**
     * 根据来源文件的文件类型创建一个图像操作的标识符.
     *
     * @param string $img_file  图片文件的路径
     * @param string $mime_type 图片文件的文件类型
     *
     * @return resource 如果成功则返回图像操作标志符，反之则返回错误代码
     */
    public function img_resource($img_file, $mime_type)
    {
        switch ($mime_type) {

            case 1:
            case 'image/gif':
            $res = imagecreatefromgif($img_file);
            break;

            case 2:
            case 'image/pjpeg':
            case 'image/jpeg':
            $res = imagecreatefromjpeg($img_file);
            break;

            case 3:
            case 'image/x-png':
            case 'image/png':
            $res = imagecreatefrompng($img_file);
            break;

            default:
            return false;
        }

        return $res;
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
              } else {
                  //如果是文件,直接输出
                     $file_data[] = $file;
              }
          }

        return $file_data;
    }

     /*
        解析JS 函数escape转义后的字符串
    */
    public function js_unescape($str)
    {
        $ret = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; ++$i) {
            if ($str[$i] == '%' && $str[$i + 1] == 'u') {
                $val = hexdec(substr($str, $i + 2, 4));
                if ($val < 0x7f) {
                    $ret .= chr($val);
                } elseif ($val < 0x800) {
                    $ret .= chr(0xc0 | ($val >> 6)).chr(0x80 | ($val & 0x3f));
                } else {
                    $ret .= chr(0xe0 | ($val >> 12)).chr(0x80 | (($val >> 6) & 0x3f)).chr(0x80 | ($val & 0x3f));
                }
                $i += 5;
            } elseif ($str[$i] == '%') {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else {
                $ret .= $str[$i];
            }
        }

        return $ret;
    }

    public function config()
    {
        $this->operator_privilege('configvalues_view');
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products');
        $this->navigations[] = array('name' => $this->ld['product'].$this->ld['set_up'],'url' => '');
        $this->set('title_for_layout', $this->ld['product'].$this->ld['set_up'].' - '.$this->configs['shop_name']);

        if ($this->RequestHandler->isPost()) {
            if (!empty($this->data)) {
                foreach ($this->data as $vv) {
                    $data = array();
                    $vv['value'] = isset($vv['value']) ? $vv['value'] : 0;
                    $data = $vv;
                    $this->ConfigI18n->saveAll($data);
                }
            }
            $this->redirect('/products');
        }

        $resource_code = 'product_set';
        $group_code = 'product';

        $Resource_info = $this->Resource->find('first', array('conditions' => array('Resource.code' => $resource_code, 'Resource.status' => 1)));
        if (!empty($Resource_info)) {
            $resource_cond['Resource.parent_id'] = $Resource_info['Resource']['id'];
            $resource_cond['Resource.status'] = 1;
            $resource_cond['ResourceI18n.locale'] = $this->backend_locale;
            $Resource_list_info = $this->Resource->find('all', array('conditions' => $resource_cond, 'order' => 'orderby'));
            $resource_list = array();
            foreach ($Resource_list_info as $v) {
                $resource_list[$v['Resource']['code']] = $v['ResourceI18n']['name'];
            }

            $this->Config->hasOne = array();
            $this->Config->hasMany = array('ConfigI18n' => array('className' => 'ConfigI18n',
                                  'conditions' => '',
                                  'order' => '',
                                  'dependent' => true,
                                  'foreignKey' => 'config_id',
                            ),
                      );

            $conditions['Config.group_code'] = $group_code;
            $conditions['Config.status'] = 1;
            $conditions['Config.readonly'] = 0;
            $configs = $this->Config->find('all', array('conditions' => $conditions, 'order' => 'Config.orderby,Config.group_code'));
            $config_group_list = array();
            $val = array();
            foreach ($configs as $k => $v) {
                $val['Config'] = $v['Config'];
                foreach ($v['ConfigI18n'] as $kk => $vv) {
                    if ($vv['locale'] == $this->backend_locale) {
                        $val['Config']['name'] = @$vv['name'];
                    }
                    $val['ConfigI18n'][$vv['locale']] = $vv;
                    if ($v['Config']['type'] == 'radio' || $v['Config']['type'] == 'checkbox' || $v['Config']['type'] == 'image') {
                        $val['ConfigI18n'][$vv['locale']]['options'] = explode("\n", $vv['options']);
                    }
                }
                $config_groups[$v['Config']['subgroup_code']][] = $val;
            }
            $this->set('resource_list', $resource_list);
            $this->set('config_groups', $config_groups);
        } else {
            $this->redirect('/products');
        }
    }
}
