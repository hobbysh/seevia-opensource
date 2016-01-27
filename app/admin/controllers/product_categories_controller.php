<?php

/*****************************************************************************
 * Seevia 商品分类管理
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
 *这是一个名为 ProductCategoriesController 的控制器
 *后台商品分类管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class ProductCategoriesController extends AppController
{
    public $name = 'ProductCategories';
    public $components = array('RequestHandler','Phpexcel');
    public $helpers = array('Html','Javascript','Ckeditor');
    public $uses = array('CategoryFilter','ProductType','ProductI18n','ProductAttribute','ProductTypeAttribute','Brand','NavigationI18n','Navigation','CategoryProduct','CategoryProductI18n','Product','ProductsCategory','Resource','Profile','ProfileFiled','ProfilesFieldI18n','Route','OperatorLog','Attribute');

    /**
     *显示商品分类列表.
     */
    public function index()
    {
        $this->operator_privilege('product_categories_view');
        $this->menu_path = array('root' => '/product/','sub' => '/product_categories/');
        $this->set('title_for_layout', $this->ld['manage_categories'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_categories'],'url' => '');

        $product_count = array();
        $product_count1 = $this->Product->product_count();
        $product_count2 = $this->ProductsCategory->product_count();
        foreach ($product_count1 as $k => $v) {
            $product_count[$k] = $v;
        }
        foreach ($product_count2 as $k => $v) {
            $product_count[$k] = isset($product_count[$k]) ? $product_count[$k] + $v : $v;
        }
        $categories_tree = $this->CategoryProduct->tree('P', 'all', $this->backend_locale);
        $category_chirlids = array();
        if (isset($categories_tree) && sizeof($categories_tree) > 0) {
            foreach ($categories_tree as $first_k => $first_v) {
                $product_count[$first_v['CategoryProduct']['id']] = isset($product_count[$first_v['CategoryProduct']['id']]) ? $product_count[$first_v['CategoryProduct']['id']] : 0;
                $category_chirlids[$first_v['CategoryProduct']['id']] = $first_v['CategoryProduct']['id'];
                if (isset($first_v['SubCategory']) && sizeof($first_v['SubCategory']) > 0) {
                    foreach ($first_v['SubCategory'] as $second_k => $second_v) {
                        if (isset($product_count[$second_v['CategoryProduct']['id']])) {
                            $product_count[$first_v['CategoryProduct']['id']] = $product_count[$first_v['CategoryProduct']['id']] + $product_count[$second_v['CategoryProduct']['id']];
                        }
                        $product_count[$second_v['CategoryProduct']['id']] = isset($product_count[$second_v['CategoryProduct']['id']]) ? $product_count[$second_v['CategoryProduct']['id']] : 0;
                        $category_chirlids[$first_v['CategoryProduct']['id']] .= ','.$second_v['CategoryProduct']['id'];
                        $category_chirlids[$second_v['CategoryProduct']['id']] = $second_v['CategoryProduct']['id'];
                        if (isset($second_v['SubCategory']) && sizeof($second_v['SubCategory']) > 0) {
                            foreach ($second_v['SubCategory'] as $third_k => $third_v) {
                                if (isset($product_count[$third_v['CategoryProduct']['id']])) {
                                    $product_count[$first_v['CategoryProduct']['id']] = $product_count[$first_v['CategoryProduct']['id']] + $product_count[$third_v['CategoryProduct']['id']];
                                    $product_count[$second_v['CategoryProduct']['id']] = $product_count[$second_v['CategoryProduct']['id']] + $product_count[$third_v['CategoryProduct']['id']];
                                }
                                $category_chirlids[$first_v['CategoryProduct']['id']] .= ','.$third_v['CategoryProduct']['id'];
                                $category_chirlids[$second_v['CategoryProduct']['id']] .= ','.$third_v['CategoryProduct']['id'];
                            }
                        }
                    }
                }
            }
        }

        $this->set('category_chirlids', $category_chirlids);
        $this->set('categories_tree', $categories_tree);
        $this->set('product_count', $product_count);
        //pr($categories_products_count);
        //$Resource_info = $this->Resource->resource_formated(array("sub_type"),$this->locale);
        //$this->set("Resource_info",$Resource_info);
    }

    /**
     *商品分类 新增/编辑		$this->set('product_count',$product_count);.
     $this->set('categories_products_count',$categories_products_count);
     *@param int $id 输入商品分类ID
     */
    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('product_categories_add');
        } else {
            $this->operator_privilege('product_categories_edit');
            //查找映射路径的内容
            $conditions = array('Route.controller' => 'categories','Route.action' => 'view','Route.model_id' => $id);
            $content = $this->Route->find('first', array('conditions' => $conditions));
            $this->set('routecontent', $content);
        }
        $this->menu_path = array('root' => '/product/','sub' => '/product_categories/');
        $this->set('title_for_layout', $this->ld['manage_categories'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        /*$this->navigations[]=array('name'=>$this->ld['manage_products'],'url' => '/products/');*/
        $this->navigations[] = array('name' => $this->ld['manage_categories'],'url' => '/product_categories/');
        if (!empty($this->data['Route'])) {
            //判断添加的内容是否为空
            $conditions = array('Route.controller' => 'categories','Route.model_id' => $id);
            $routeurl = $this->Route->find('first', array('conditions' => $conditions));
            $condit = array('Route.url' => $this->data['Route']['url']);//用来判断添加的url不能重复
            $rurl = $this->Route->find('first', array('conditions' => $condit));
            if (empty($rurl)) {
                //判断里面是否添加相同的数据
                if (empty($id)) {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        //foreach($this->data[])
                        $this->data['Route']['controller'] = 'categories';
                        $this->data['Route']['url'] = $this->data['Route']['url'];
                        $this->data['Route']['action'] = 'view';
                        $this->data['Route']['model_id'] = $id;
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                } else {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        //foreach($this->data[])
                        $this->data['Route']['controller'] = 'categories';
                        $this->data['Route']['url'] = $this->data['Route']['url'];
                        $this->data['Route']['action'] = 'view';
                        $this->data['Route']['model_id'] = $id;
                        $this->data['Route']['id'] = $routeurl['Route']['id'];
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                }
            }
        }

        $first_price = array();//第一组价格
        $clone_price = array();//多组价格
        $clone_attr = array();//多组属性
        $check_id = array();
        $this->Attribute->set_locale($this->backend_locale);
        $this->ProductType->set_locale($this->backend_locale);
        $product_type = $this->ProductType->find('all', array('conditions' => array(' ProductType.status' => 1, 'ProductType.id !=' => 0)));
        /*
        foreach($product_type as $k => $v){
            if(!empty($v['ProductTypeI18n'])){
                 foreach($v['ProductTypeI18n']as $vv){
                     if($v['ProductTypeI18n']['locale']==$this->locale){
                         $product_type[$k]['ProductType']['name']=$v['ProductTypeI18n']['name'];
                        $product_type[$k]['ProductType']['type_id']=$v['ProductTypeI18n']['type_id'];
                     }
                     else{
                        if($vv['locale']==$this->locale){
                            $product_type[$k]['ProductType']['name']=$vv['name'];
                            $product_type[$k]['ProductType']['type_id']=$vv['type_id'];
                        }
                    }
                }
            }		
        }
        */
        //商品的属性id		
        $category_filter = $this->CategoryFilter->find('first', array('conditions' => array('CategoryFilter.category_id' => $id)));
        if (!empty($category_filter)) {
            $this->set('filer_status', $category_filter['CategoryFilter']['status']);
            foreach ($category_filter as $k => $v) {
                $arrt_group = explode(';', $v['product_attribute']);
            }
        }
        if (!empty($arrt_group)) {
            if ($arrt_group[0] != '') {
                foreach ($arrt_group as $k => $v) {
                    $tem_arr = $this->ProductTypeAttribute->find('first', array('conditions' => array('ProductTypeAttribute.id' => $v)));
                    $tem_id = $tem_arr['ProductTypeAttribute']['product_type_id'];
                    $check_id[$k] = $tem_id;
                    $attr_list = $this->ProductTypeAttribute->find('all', array('conditions' => array('ProductTypeAttribute.product_type_id' => $tem_id)));
                    foreach ($attr_list as $kk => $vv) {
                        if (!empty($vv['ProductTypeAttributeI18n'])) {
                            foreach ($vv['ProductTypeAttributeI18n']as $vvv) {
                                if ($vvv['locale'] == $this->locale) {
                                    $attr_list[$kk]['ProductTypeAttribute']['name'] = $vvv['name'];
                                    $attr_list[$kk]['ProductTypeAttribute']['attr_id'] = $vvv['product_type_attribute_id'];
                                }
                            }
                        }
                    }
                    $data = "<select name='data[CategoryProduct][attr_filter][]' onchange='check_filter(this)'><option value='0'>请选择筛选属性</option>";
                    foreach ($attr_list as $kk => $vv) {
                        $data .= $vv['ProductTypeAttribute']['attr_id'] == $v ? '<option value='.$vv['ProductTypeAttribute']['attr_id'].' selected >'.$vv['ProductTypeAttribute']['name'].'</option>' : '<option value='.$vv['ProductTypeAttribute']['attr_id'].' >'.$vv['ProductTypeAttribute']['name'].'</option>';
                    }
                    $data .= '</select>';
                    $clone_attr[$k] = $data;
                }
            }
        }
        if (!empty($category_filter) && is_array($category_filter)) {
            foreach ($category_filter as $k => $v) {
                $price_group = explode(';', $v['filter_price']);
            }
            if (!empty($price_group)) {
                foreach ($price_group as $k => $v) {
                    $filter_price[$k] = explode('-', $v);
                }
                $first_price = $filter_price[0];
                unset($filter_price[0]);
                if (!empty($filter_price)) {
                    $clone_price = $filter_price;
                }
            }
        }
        $this->set('first_price', $first_price);
        $this->set('clone_price', $clone_price);
        $this->set('product_type', $product_type);
        if (isset($arrt_group)) {
            $this->set('arrt_group', $arrt_group);
        }
        $this->set('check_id', $check_id);
        $this->set('clone_attr', $clone_attr);

        if ($this->RequestHandler->isPost()) {
            $this->data['CategoryProduct']['home_show'] = isset($this->data['CategoryProduct']['home_show']) ? $this->data['CategoryProduct']['home_show'] : 0;
            $this->data['CategoryProduct']['home_show_num'] = isset($this->data['CategoryProduct']['home_show_num']) && !empty($this->data['CategoryProduct']['home_show_num']) ? $this->data['CategoryProduct']['home_show_num'] : 0;
            $parent_id = $this->data['CategoryProduct']['parent_id'];
            if (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 0) {
                $category_first = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.parent_id' => $parent_id), 'order' => 'orderby asc', 'limit' => '1'));
                $this->data['CategoryProduct']['orderby'] = $category_first['CategoryProduct']['orderby'];
                // 取出所有导航的 序值加1
                $ca_all = $this->CategoryProduct->find('all');
                foreach ($ca_all as $k => $v) {
                    $ca_all[$k]['CategoryProduct']['orderby'] = $v['CategoryProduct']['orderby'] + 1;
                }
                $this->CategoryProduct->saveAll($ca_all);
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 1) {
                $category_last = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.parent_id' => $parent_id), 'order' => 'orderby desc', 'limit' => '1'));
                $this->data['CategoryProduct']['orderby'] = $category_last['CategoryProduct']['orderby'] + 1;
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 2) {
                $category_change = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $_REQUEST['orderby_sel'])));
                $this->data['CategoryProduct']['orderby'] = $category_change['CategoryProduct']['orderby'] + 1;
                $ca_all = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.orderby >' => $category_change['CategoryProduct']['orderby'])));
                foreach ($ca_all as $k => $v) {
                    $ca_all[$k]['CategoryProduct']['orderby'] = $v['CategoryProduct']['orderby'] + 1;
                }
                $this->CategoryProduct->saveAll($ca_all);
            } else {
                if (!isset($this->data['CategoryProduct']['id']) && $this->data['CategoryProduct']['id'] == '') {
                    $this->data['CategoryProduct']['orderby'] = 1;
                }
            }
            $this->data['CategoryProduct']['show_info'] = isset($this->data['CategoryProduct']['show_info']) ? implode(';', $this->data['CategoryProduct']['show_info']) : '';
            if (isset($this->data['CategoryProduct']['id']) && $this->data['CategoryProduct']['id'] != '') {
                $this->CategoryProduct->save($this->data['CategoryProduct']);//主表保存
            } else {
                $this->CategoryProduct->saveAll($this->data['CategoryProduct']);//主表保存
                $id = $this->CategoryProduct->getLastInsertId();
            }
            $id = $this->CategoryProduct->id;

            if (!empty($this->data['CategoryProduct']['attr_filter'])) {
                foreach ($this->data['CategoryProduct']['attr_filter'] as $k => $v) {
                    if ($v == '0') {
                        unset($this->data['CategoryProduct']['attr_filter'][$k]);
                    }
                }
            }
            if (!empty($this->data['CategoryProduct']['attr_filter'])) {
                $this->data['CategoryFilter']['product_attribute'] = implode(';', $this->data['CategoryProduct']['attr_filter']);
            } else {
                $this->data['CategoryFilter']['product_attribute'] = '';
            }

            foreach ($this->data['CategoryProduct']['start_price'] as $k => $v) {
                $this->data['CategoryProduct']['price'][$k]['start'] = $v;
                $this->data['CategoryProduct']['price'][$k]['end'] = $this->data['CategoryProduct']['end_price'][$k];
            }

            foreach ($this->data['CategoryProduct']['price'] as $k => $v) {
                if ($v['start'] == '' || $v['end'] == '') {
                    unset($this->data['CategoryProduct']['price'][$k]);
                } else {
                    $this->data['CategoryProduct']['price'][$k] = $v['start'].'-'.$v['end'];
                }
            }
            if (!empty($this->data['CategoryProduct']['price'])) {
                $this->data['CategoryFilter']['filter_price'] = implode(';', $this->data['CategoryProduct']['price']);
            } else {
                $this->data['CategoryFilter']['filter_price'] = '';
            }
            if ($category_filter) {
                $this->data['CategoryFilter']['id'] = $category_filter['CategoryFilter']['id'];
            } else {
                $this->data['CategoryFilter']['category_id'] = $id;
            }

            $this->data['CategoryFilter']['status'] = !empty($this->data['CategoryFilter']['status']) ? $this->data['CategoryFilter']['status'] : '0';
            $this->CategoryFilter->save(array('CategoryFilter' => $this->data['CategoryFilter']));

            $this->CategoryProductI18n->deleteAll(array('category_id' => $id)); //删除原有多语言

            foreach ($this->data['CategoryProductI18n'] as $v) {
                $categoryI18n_info = array(
                       'locale' => $v['locale'],
                    'category_id' => $id,
                    'name' => isset($v['name']) ? $v['name'] : '',
                    'meta_keywords' => isset($v['meta_keywords']) ? $v['meta_keywords'] : '',
                       'meta_description' => isset($v['meta_description']) ? $v['meta_description'] : '',
                       'home_show_keywords' => isset($v['home_show_keywords']) ? $v['home_show_keywords'] : '',
                     'detail' => $v['detail'],
                     'top_detail' => $v['top_detail'],
                     'foot_detail' => $v['foot_detail'],
                );
                $this->CategoryProductI18n->saveAll(array('CategoryProductI18n' => $categoryI18n_info));//更新多语言
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            $url = '/categories/'.$id;
            //导航设置			
            if (isset($this->data['CategoryProduct']['id']) && $this->data['CategoryProduct']['id'] != '') {
                //查找是否已经有数据
                $p_nav = $this->Navigation->find('first', array('conditions' => array('NavigationI18n.url' => $url)));
                //如果存在的情况下 只改状态   不存在的情况洗就插入
                if (isset($p_nav) && $p_nav != '') {
                    if ($_POST['data1']['Navigation']['type'] != '0') {
                        //位置不为零时  		
                        $p_nav['Navigation']['type'] = $_POST['data1']['Navigation']['type'];
                        $this->Navigation->saveAll($p_nav);
                        $nav_info = $this->NavigationI18n->find('all', array('conditions' => array('NavigationI18n.url' => $url)));
                        foreach ($_POST['data']['CategoryProductI18n'] as $v) {
                            foreach ($nav_info as $kk => $vv) {
                                if ($vv['NavigationI18n']['locale'] == $v['locale']) {
                                    $nav_info[$kk]['NavigationI18n']['name'] = isset($v['name']) ? $v['name'] : '';
                                }
                            }
                            $this->NavigationI18n->saveAll($nav_info);//更新多语言
                        }
                    } else {
                        //为零时
                        $id = $p_nav['Navigation']['id'];
                        $this->Navigation->deleteAll(array('Navigation.id' => $id));
                        $this->NavigationI18n->deleteAll(array('navigation_id' => $id));
                    }
                } else {
                    if ($_POST['data1']['Navigation']['type'] != '0') {
                        $this->Navigation->saveAll(array('Navigation' => $_POST['data1']['Navigation']));
                        $nid = $this->Navigation->getLastInsertId();
                        foreach ($_POST['data']['CategoryProductI18n'] as $v) {
                            $navigationI18n_info = array(
                                'locale' => $v['locale'],
                                'navigation_id' => $nid,
                                'name' => isset($v['name']) ? $v['name'] : '',
                                'url' => $url,
                            );
                            $this->NavigationI18n->saveall(array('NavigationI18n' => $navigationI18n_info));//更新多语言
                        }
                    }
                }
            } else {
                if (isset($_POST['data1']['Navigation']['status']) && $_POST['data1']['Navigation']['status'] == 1) {
                    $this->Navigation->saveAll(array('Navigation' => $_POST['data1']['Navigation']));
                    $nid = $this->Navigation->getLastInsertId();
                    foreach ($_POST['data']['CategoryProductI18n'] as $v) {
                        $navigationI18n_info = array(
                            'locale' => $v['locale'],
                            'navigation_id' => $nid,
                            'name' => isset($v['name']) ? $v['name'] : '',
                            'url' => $url,
                        );
                        $this->NavigationI18n->saveall(array('NavigationI18n' => $navigationI18n_info));//更新多语言
                    }
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $log_message = '';
                if ($this->data['CategoryProduct']['id'] == '') {
                    $log_message = $this->ld['log_add_product_category'];
                } else {
                    $log_message = $this->ld['log_edit_product_category'];
                }
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$log_message.':'.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/product_categories/');
        }
        $this->data = $this->CategoryProduct->localeformat($id);
        if (isset($this->data['CategoryProductI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$this->data['CategoryProductI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_category'],'url' => '');
        }
        $url = '/categories/'.$id;
        $p_nav = $this->Navigation->find('first', array('conditions' => array('NavigationI18n.url' => $url)));
        if (isset($p_nav) && $p_nav != '') {
            $this->set('ninfo', $p_nav);
        }
        //取树形结构
        $categories_tree = $this->CategoryProduct->tree('P', 'all', $this->backend_locale);
        $this->set('categories_tree', $categories_tree);
        $this->set('id', $id);
    }

    /**
     *转移分类下面的商品.
     *
     *@param int $category_id 需转移的商品分类ID
     */
    public function move_to($category_id)
    {
        $this->operator_privilege('product_categories_move');
        $this->menu_path = array('root' => '/product/','sub' => '/product_categories/');
        $this->set('title_for_layout', $this->ld['edit_product_category'].' - '.$this->ld['manager_categories'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_categories'],'url' => '/product_categories/index/P');
        $this->navigations[] = array('name' => $this->ld['category_transfer'],'url' => '');

        $categories_tree = $this->CategoryProduct->tree('P', 'all', $this->backend_locale);
        if ($this->RequestHandler->isPost()) {
            $this->Product->hasOne = array();
            $this->Product->hasMany = array();
            $this->Product->updateAll(
                array('category_id' => $_REQUEST['end_category_id']),
                array('category_id' => $_REQUEST['start_category_id'])
            );
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['category_transfer'].'id: '.$category_id, $this->admin['id']);
            }
            $this->redirect('/product_categories/');
        }
        $this->set('categories_tree', $categories_tree);
        $this->set('category_id', $category_id);
    }

    /**
     *删除商品分类 前 提一级子分类.
     *
     *@param int $id 商品分类ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_product_category_failure'];
        $this->CategoryProduct->hasMany = array();
        $this->CategoryProduct->hasOne = array();
        $pn = $this->CategoryProductI18n->find('list', array('fields' => array('CategoryProductI18n.category_id', 'CategoryProductI18n.name'), 'conditions' => array('CategoryProductI18n.category_id' => $id, 'CategoryProductI18n.locale' => $this->backend_locale)));
        $this->CategoryProduct->deleteAll(array('id' => $id));
        $this->CategoryProductI18n->deleteAll(array('category_id' => $id));
        $this->CategoryFilter->deleteAll(array('CategoryFilter.category_id' => $id));
        $category_data = $this->CategoryProduct->find('all', array('conditions' => array('parent_id' => $id), 'fields' => 'id'));
        foreach ($category_data as $k => $v) {
            $this->CategoryProduct->save(array('CategoryProduct' => array('id' => $v['CategoryProduct']['id'], 'parent_id' => 0)));
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_product_category'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_product_category_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //分类
    public function category_products_tree($data, $category, $p, $fields_array, $pat = array(), $attr_info = array(), $brand_names)
    {
        $products = array();
        if (isset($data) && is_array($data)) {
            foreach ($data[$category['CategoryProduct']['id']] as $k => $v) {
                $product = array();
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
                    } else {
                        $product[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                    }
                }
                if (!empty($pat)) {
                    foreach ($pat as $pp) {
                        $product[] = (isset($attr_info[$v['Product']['id']][$pp['id']]) ? $attr_info[$v['Product']['id']][$pp['id']] : ' ');
                    }
                }
                $products[] = $product;
            }
        }

        return $products;
    }
    public function Classification_derived_method($data, $category, $pat, $attr_info, $p, $brand_names)
    {
        $product_names = $this->ProductI18n->find('list', array('fields' => 'ProductI18n.product_id,ProductI18n.name', 'conditions' => array('ProductI18n.locale' => $this->backend_locale)));
        $products = array();
        if (isset($data) && is_array($data)) {
            foreach ($data[$category['CategoryProduct']['id']] as $k => $v) {
                $product = array();
                $product[] = $v['Product']['id'];
                $product[] = $v['Product']['code'];
                $product[] = isset($product_names[$v['Product']['id']]) ? $product_names[$v['Product']['id']] : '';
                $product[] = (isset($brand_names[$v['Product']['brand_id']]) ? $brand_names[$v['Product']['brand_id']] : ' ');
                foreach ($pat as $pp) {
                    $product[] = (isset($attr_info[$v['Product']['id']][$pp['id']]) ? $attr_info[$v['Product']['id']][$pp['id']] : ' ');
                }
                $product[] = $v['Product']['quantity'];
                $product[] = $v['Product']['purchase_price'];
                $product[] = $v['Product']['shop_price'];
                $product[] = $v['Product']['forsale'];
                $product[] = $v['Product']['recommand_flag'];
                $products[] = $product;
            }
        }

        return $products;
    }
    /**
     *商品分类批量导出.
     */
    public function derivedchange($type, $code)
    {
        //pr($type);pr($code);die;
        $this->Profile->hasOne = array();
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $code, 'Profile.status' => 1)));
        $excel = array();
        $cat_ids = isset($_REQUEST['checkbox']) ? $_REQUEST['checkbox'] : 0;
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1, 'ProfilesFieldI18n.locale' => $this->backend_locale), 'order' => 'ProfileFiled.orderby asc'));
//pr($profilefiled_info);die;
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
            $pat = array();
            $pat_ids = array();
            //取出所有公共属性
//			if(in_array('APP-PRODUCTS',$this->apps['codes'])){
//				$this->ProductTypeAttribute->set_locale($this->backend_locale);
//				$pubile_attr_info = $this->ProductTypeAttribute->find('all',array('conditions'=>array('ProductTypeAttribute.product_type_id'=>0,'ProductTypeAttribute.status'=>1),'fields'=>'ProductTypeAttribute.id,ProductTypeAttributeI18n.name'));		
//				if(!empty($pubile_attr_info)){
//					foreach($pubile_attr_info as $k=>$p){
//						$pat[$k]['id'] = $p['ProductTypeAttribute']['id'];
//						$pat[$k]['name'] = $p['ProductTypeAttributeI18n']['name'];
//					}
//				}	
//				$pat_ids = array();
//				if(!empty($pubile_attr_info)){
//					foreach($pubile_attr_info as $pa){
//						$pat_ids[] = $pa['ProductTypeAttribute']['id'];
//					}
//				}
//				foreach ($pat as $pp){
//					$tmp[]=$pp['name'];
//				}
//			}
            $excel[] = $tmp;
            $this->Product->hasOne = array();
            $this->Product->hasOne = array('ProductI18n' => array(
                                    'className' => 'ProductI18n',
                                    'order' => '',
                                    'dependent' => true,
                                    'foreignKey' => 'product_id',
                                ));
            $this->Product->set_locale($this->backend_locale);
            $conditon = '';
            $order = '';
            $conditon['Product.status'] = 1;
            if ($type == 'shelf_export_csv') {
                $conditon['Product.forsale'] = 1;
                if ($this->configs['product_order'] == 'category') {
                    $order = 'Product.category_id';
                } else {
                    $order = 'Product.'.$this->configs['product_order'];
                }
            } elseif ($type == 'nextframe_export_csv') {
                $conditon['Product.forsale'] = 0;
                if ($this->configs['product_order'] == 'category') {
                    $order = 'Product.category_id';
                } else {
                    $order = 'Product.'.$this->configs['product_order'];
                }
            } elseif ($type == 'all_export_csv') {
                if ($this->configs['product_order'] == 'category') {
                    $order = 'Product.category_id';
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
            if (in_array('APP-PRODUCTS', $this->apps['codes'])) {
                $attr_info = $this->ProductAttribute->product_list_format($p_ids, $pat_ids, $this->backend_locale);
            }
            foreach ($p as $k => $v) {
                $data[$v['Product']['category_id']][] = $v;
            }
            $category_tree = $this->CategoryProduct->tree('P', 'all', $this->backend_locale);
            foreach ($category_tree as $k => $v) {
                $row = array();
                if (!empty($data[$v['CategoryProduct']['id']])) {
                    if (in_array($v['CategoryProduct']['id'], $cat_ids)) {
                        $row[] = $v['CategoryProductI18n']['name'];
                        $excel[] = $row;
                        if (isset($data[$v['CategoryProduct']['id']])) {
                            $tmp = $this->category_products_tree($data, $v, $p, $fields_array, $pat, $attr_info, $brand_names);
                            $excel = array_merge($excel, $tmp);
                        }
                    }
                }
                if (isset($v['SubCategory'])) {
                    foreach ($v['SubCategory'] as $kk => $vv) {
                        $row = array();
                        if (!empty($data[$vv['CategoryProduct']['id']])) {
                            if (in_array($vv['CategoryProduct']['id'], $cat_ids)) {
                                //	 $row[]=$vv['CategoryProductI18n']['name'];
                                 $row[] = $v['CategoryProductI18n']['name'].' '.$vv['CategoryProductI18n']['name'];
                                $excel[] = $row;
                                if (isset($data[$vv['CategoryProduct']['id']])) {
                                    $tmp = $this->category_products_tree($data, $vv, $p, $fields_array, $pat, $attr_info, $brand_names);
                                    $excel = array_merge($excel, $tmp);
                                }
                            }
                        }
                        if (isset($vv['SubCategory'])) {
                            foreach ($vv['SubCategory'] as $kkk => $vvv) {
                                $row = array();
                                if (!empty($data[$vvv['CategoryProduct']['id']])) {
                                    if (in_array($vvv['CategoryProduct']['id'], $cat_ids)) {
                                        //   $row[]=$vvv['CategoryProductI18n']['name'];
                                       $row[] = $v['CategoryProductI18n']['name'].' '.$vv['CategoryProductI18n']['name'].' '.$vvv['CategoryProductI18n']['name'];
                                        $excel[] = $row;
                                        if (isset($data[$vvv['CategoryProduct']['id']])) {
                                            $tmp = $this->category_products_tree($data, $vvv, $p, $fields_array, $pat, $attr_info, $brand_names);
                                            $excel = array_merge($excel, $tmp);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->Phpexcel->output('Productcategories_export'.date('YmdHis').'.xls', $excel);
        die;
    }
    public function derivedchange11($type)
    {
        //pr($this->configs['product_order']);die;
          // pr($_REQUEST["checkbox"]);	   	 
         //获取的id(array)
        $cat_ids = isset($_REQUEST['checkbox']) ? $_REQUEST['checkbox'] : 0;
        $this->ProductTypeAttribute->set_locale($this->backend_locale);
        $pubile_attr_info = $this->ProductTypeAttribute->find('all', array('conditions' => array('ProductTypeAttribute.product_type_id' => 0, 'ProductTypeAttribute.status' => 1), 'fields' => 'ProductTypeAttribute.id,ProductTypeAttributeI18n.name'));
    //	pr($pubile_attr_info);
    //	die;
        $this->Brand->set_locale($this->backend_locale);
        $bran_sel = $this->Brand->find('all', array('fields' => array('Brand.id', 'Brand.code', 'BrandI18n.name'), 'order' => 'Brand.orderby,Brand.code'));
        $brand_names = array();
        foreach ($bran_sel as $bk => $bv) {
            $brand_names[$bv['Brand']['id']] = $bv['BrandI18n']['name'];
        }
        $pat = array();
        if (!empty($pubile_attr_info)) {
            foreach ($pubile_attr_info as $k => $p) {
                $pat[$k]['id'] = $p['ProductTypeAttribute']['id'];
                $pat[$k]['name'] = $p['ProductTypeAttributeI18n']['name'];
            }
        }
        $pat_ids = array();
        if (!empty($pubile_attr_info)) {
            foreach ($pubile_attr_info as $pa) {
                $pat_ids[] = $pa['ProductTypeAttribute']['id'];
            }
        }
        $excel = array();
              //title数组
            $row = array();
        $row[] = $this->ld['number'];
        $row[] = $this->ld['sku'];
        $row[] = $this->ld['name'];
        $row[] = $this->ld['brand'];
        foreach ($pat as $pp) {
            $row[] = $pp['name'];
        }
        $row[] = $this->ld['quantity'];
        $row[] = $this->ld['purchase_price'];
        $row[] = $this->ld['shop_price'];
        $row[] = $this->ld['for_sale'];
        $row[] = $this->ld['recommend'];
           //放进大的数组
             $excel[] = $row;

        $this->Product->set_locale($this->backend_locale);
        if ($type == 'shelf_export_csv') {
            if ($this->configs['product_order'] == 'category') {
                $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1, 'Product.forsale' => 1), 'recursive' => -1, 'order' => 'Product.category_id'));
            } else {
                $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1, 'Product.forsale' => 1), 'recursive' => -1, 'order' => 'Product.'.$this->configs['product_order']));
            }
        } elseif ($type == 'nextframe_export_csv') {
            if ($this->configs['product_order'] == 'category') {
                $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1, 'Product.forsale' => 0), 'recursive' => -1, 'order' => 'Product.category_id'));
            } else {
                $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1, 'Product.forsale' => 0), 'recursive' => -1, 'order' => 'Product.'.$this->configs['product_order']));
            }
        } elseif ($type == 'all_export_csv') {
            if ($this->configs['product_order'] == 'category') {
                $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1), 'recursive' => -1, 'order' => 'Product.category_id'));
            } else {
                $p = $this->Product->find('all', array('conditions' => array('Product.status' => 1), 'recursive' => -1, 'order' => 'Product.'.$this->configs['product_order']));
            }
        }
        $p_ids = array();
        if (!empty($p)) {
            foreach ($p as $s) {
                $p_ids[] = $s['Product']['id'];
            }
        }
        $attr_info = $this->ProductAttribute->product_list_format($p_ids, $pat_ids, $this->backend_locale);
        foreach ($p as $k => $v) {
            $data[$v['Product']['category_id']][] = $v;
        }
        $category_tree = $this->CategoryProduct->tree('P', 'all', $this->backend_locale);
        foreach ($category_tree as $k => $v) {
            $row = array();
            if (!empty($data[$v['CategoryProduct']['id']])) {
                if (in_array($v['CategoryProduct']['id'], $cat_ids)) {
                    $row[] = $v['CategoryProductI18n']['name'];
                    $excel[] = $row;
                    if (isset($data[$v['CategoryProduct']['id']])) {
                        $tmp = $this->Classification_derived_method($data, $v, $pat, $attr_info, $p, $brand_names);
                        $excel = array_merge($excel, $tmp);
                    }
                }
            }
            if (isset($v['SubCategory'])) {
                foreach ($v['SubCategory'] as $kk => $vv) {
                    $row = array();
                    if (!empty($data[$vv['CategoryProduct']['id']])) {
                        if (in_array($vv['CategoryProduct']['id'], $cat_ids)) {
                            $row[] = $v['CategoryProductI18n']['name'].' '.$vv['CategoryProductI18n']['name'];
                            $excel[] = $row;
                            if (isset($data[$vv['CategoryProduct']['id']])) {
                                $tmp = $this->Classification_derived_method($data, $vv, $pat, $attr_info, $p, $brand_names);
                                $excel = array_merge($excel, $tmp);
                            }
                        }
                    }
                    if (isset($vv['SubCategory'])) {
                        foreach ($vv['SubCategory'] as $kkk => $vvv) {
                            $row = array();
                            if (!empty($data[$vvv['CategoryProduct']['id']])) {
                                if (in_array($vvv['CategoryProduct']['id'], $cat_ids)) {
                                    $row[] = $v['CategoryProductI18n']['name'].' '.$vv['CategoryProductI18n']['name'].' '.$vvv['CategoryProductI18n']['name'];
                                    $excel[] = $row;
                                    if (isset($data[$vvv['CategoryProduct']['id']])) {
                                        $tmp = $this->Classification_derived_method($data, $vvv, $pat, $attr_info, $p, $brand_names);
                                        $excel = array_merge($excel, $tmp);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->Phpexcel->output('Productcategories_export'.date('YmdHis').'.xls', $excel);
        die;
    }
    /**
     *商品分类批量处理.
     */
    public function batch()
    {
        $art_ids = !empty($_REQUEST['checkbox']) ? $_REQUEST['checkbox'] : 0;
        if (sizeof($art_ids) > 0) {
            $condition['CategoryProduct.id'] = $art_ids;
            $this->CategoryProduct->deleteAll($condition);
            $this->CategoryProduct->deleteAll(array('CategoryProduct.parent_id' => $art_ids));
            $this->CategoryProductI18n->deleteAll(array('CategoryProductI18n.category_id' => $art_ids));
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
            }
            $this->redirect('/product_categories/');
        } else {
            $this->redirect('/product_categories/');
        }
    }
    /**
     *列表名称修改.
     */
    public function update_category_name()
    {
        $this->CategoryProduct->hasMany = array();
        $this->CategoryProduct->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];

        $request = $this->CategoryProductI18n->updateAll(
            array('name' => "'".$val."'"),
            array('category_id' => $id, 'locale' => $this->backend_locale)
        );
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_modify_product_category_name'].':id '.$id.' '.$val, $this->admin['id']);
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
     *列表状态修改.
     */
    public function toggle_on_status()
    {
        $this->CategoryProduct->hasMany = array();
        $this->CategoryProduct->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->CategoryProduct->save(array('id' => $id, 'status' => $val))) {
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

    /**
     *列表修改排序.
     */
    public function update_category_orderby()
    {
        $this->CategoryProduct->hasMany = array();
        $this->CategoryProduct->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->CategoryProduct->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_modify_product_category_sort'].':'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->render('index');
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //快速添加分类
    public function doinsertproductcat()
    {
        $this->data1['CategoryProduct']['id'] = '';
        $this->data1['CategoryProduct']['parent_id'] = $_POST['parent_id'];
        $this->CategoryProduct->saveAll($this->data1);//主表保存
        $id = $this->CategoryProduct->id;
        $this->CategoryProductI18n->deleteAll(array('category_id' => $id)); //删除原有多语言
        foreach ($_POST['data1']['CategoryProductI18n'] as $v) {
            $categoryI18n_info = array(
                       'locale' => $v['locale'],
                    'category_id' => $id,
                    'name' => isset($v['name']) ? $v['name'] : '',
                );

            $a = $this->CategoryProductI18n->saveAll(array('CategoryProductI18n' => $categoryI18n_info));//更新多语言
        }
        foreach ($_POST['data1']['CategoryProductI18n'] as $k => $v) {
            if ($v['locale'] == $this->backend_locale) {
                $quick_product_category_name = $v['name'];
            }
        }
        if ($a) {
            $result['message'] = $this->ld['complete_success'];
            $result['flag'] = 1;
            $category_tree = $this->CategoryProduct->tree('P', 'all', $this->backend_locale);
            $result['cat'] = $category_tree;
            $result['last_categor_id'] = $id;
            $result['select_categories'] = $this->ld['select_categories'];

            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_quick_add_product_category'].':'.$quick_product_category_name, $this->admin['id']);
            }
        } else {
            $result['message'] = $this->ld['complete_failure'];
            $result['flag'] = 2;
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //检查分类唯一
    public function check_unique($productcat)
    {
        if ($this->RequestHandler->isPost()) {
            $count = $this->CategoryProductI18n->find('count', array('conditions' => array('CategoryProductI18n.name' => $productcat)));
            if ($count) {
                $result['code'] = 1;
            } else {
                $result['code'] = 0;
            }
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            die(json_encode($result));
        }
    }
    //排序下拉框
    public function searchpc($id)
    {
        if ($id != 0) {
            $na_one = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $id)));
            $na_info = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.parent_id' => $na_one['CategoryProduct']['id'], 'CategoryProductI18n.locale' => $this->backend_locale)));
        } else {
            $na_info = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.parent_id' => $id, 'CategoryProductI18n.locale' => $this->backend_locale, 'CategoryProduct.type' => 'P')));
        }
        if (isset($na_info) && count($na_info) > 0) {
            $result['flag'] = 1;
            $orderby_data = array();
            foreach ($na_info as $v) {
                $orderby_data[] = array(
                    'id' => $v['CategoryProduct']['id'],
                    'value' => $v['CategoryProductI18n']['name'],
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
    //取属性
    public function getattr($id = '')
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $attr_id_list = $this->ProductTypeAttribute->find('list', array('fields' => array('ProductTypeAttribute.attribute_id'), 'conditions' => array('ProductTypeAttribute.product_type_id' => $id)));
        $this->Attribute->set_locale($this->backend_locale);
        $attr_list = $this->Attribute->find('all', array('fields' => array('Attribute.id', 'AttributeI18n.name'), 'conditions' => array('Attribute.id' => $attr_id_list, 'Attribute.status' => 1)));
        $result['flag'] = 0;
        if (!empty($attr_list)) {
            $result['flag'] = 1;
            $result['attr_list'] = $attr_list;
        }
        die(json_encode($result));
    }

    //列表箭头排序
    public function changeorder($updowm, $id, $nextone)
    {
        //如果值相等重新自动排序
        $a = $this->CategoryProduct->query('SELECT DISTINCT `parent_id` 
			FROM `svoms_category_products` as CategoryProduct
			GROUP BY `orderby` , `parent_id` 
			HAVING count( * ) >1');
        foreach ($a as $v) {
            $this->CategoryProduct->Behaviors->attach('Containable');
            $all = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.parent_id' => $v['CategoryProduct']['parent_id']), 'order' => 'CategoryProduct.id asc', 'contain' => false));
            foreach ($all as $k => $vv) {
                $all[$k]['CategoryProduct']['orderby'] = $k + 1;
            }
            $this->CategoryProduct->saveAll($all);
        }
        if ($nextone == 0) {
            $category_one = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $id)));
            if ($updowm == 'up') {
                $category_change = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.orderby <' => $category_one['CategoryProduct']['orderby'], 'CategoryProduct.parent_id' => 0, 'CategoryProduct.type' => 'P'), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $category_change = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.orderby >' => $category_one['CategoryProduct']['orderby'], 'CategoryProduct.parent_id' => 0, 'CategoryProduct.type' => 'P'), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        if ($nextone == 'next') {
            $category_one = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $id)));
            if ($updowm == 'up') {
                $category_change = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.orderby <' => $category_one['CategoryProduct']['orderby'], 'CategoryProduct.parent_id' => $category_one['CategoryProduct']['parent_id'], 'CategoryProduct.type' => 'P'), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $category_change = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.orderby >' => $category_one['CategoryProduct']['orderby'], 'CategoryProduct.parent_id' => $category_one['CategoryProduct']['parent_id'], 'CategoryProduct.type' => 'P'), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        $t = $category_one['CategoryProduct']['orderby'];
        $category_one['CategoryProduct']['orderby'] = $category_change['CategoryProduct']['orderby'];
        $category_change['CategoryProduct']['orderby'] = $t;
        if (isset($category_change['CategoryProduct']['status']) && $category_change['CategoryProduct']['type'] != '') {
            $this->CategoryProduct->saveAll($category_one);
            $this->CategoryProduct->saveAll($category_change);
            $arr_ = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $category_change['CategoryProduct']['id'])));
        }

        $product_count = $this->Product->product_count();
        foreach ($product_count as $k => $v) {
            $cInfo1 = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $k)));
            if ($cInfo1['CategoryProduct']['parent_id'] != 0) {
                $num = isset($product_count[$cInfo1['CategoryProduct']['parent_id']]) ? $product_count[$cInfo1['CategoryProduct']['parent_id']] : 0;
                $product_count[$cInfo1['CategoryProduct']['parent_id']] = $num + $v;
                $cInfo2 = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $cInfo1['CategoryProduct']['parent_id'])));
                if ($cInfo2['CategoryProduct']['parent_id'] != 0) {
                    $num = isset($product_count[$cInfo2['CategoryProduct']['parent_id']]) ? $product_count[$cInfo2['CategoryProduct']['parent_id']] : 0;
                    $product_count[$cInfo2['CategoryProduct']['parent_id']] = $num + $v;
                }
            }
        }
        $categories_products_count = $this->ProductsCategory->findcountassoc();
        $categories_tree = $this->CategoryProduct->tree('P', 'all', $this->backend_locale);
        $this->set('categories_tree', $categories_tree);
        $this->set('product_count', $product_count);
        $this->set('categories_products_count', $categories_products_count);
        Configure::write('debug', 1);
        $this->render('index');
        $this->layout = 'ajax';
    }
}
