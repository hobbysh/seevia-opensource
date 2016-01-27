<?php

/*****************************************************************************
 * Seevia 品牌管理
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
 *这是一个名为 BrandsController 的控制器
 *后台品牌管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class BrandsController extends AppController
{
    public $name = 'Brands';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('Brand','BrandI18n','OperatorLog','CategoryType','Product');

    /**
     *显示品牌列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('brands_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/product/','sub' => '/brands/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
//        $this->navigations[]=array('name'=>$this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['manager_brands'],'url' => '');

        $condition = '';
        $brand_keywords = '';     //关键字
        //关键字
        if (isset($this->params['url']['brand_keywords']) && $this->params['url']['brand_keywords'] != '') {
            $brand_keywords = $this->params['url']['brand_keywords'];
            $condition['and']['or']['BrandI18n.name like'] = '%'.$brand_keywords.'%';
            $condition['and']['or']['Brand.code like'] = '%'.$brand_keywords.'%';
            $condition['and']['or']['BrandI18n.description like'] = '%'.$brand_keywords.'%';
            $condition['and']['or']['Brand.id like'] = '%'.$brand_keywords.'%';
        }

        $this->Brand->set_locale($this->backend_locale);
        $total = $this->Brand->find('count', array('conditions' => $condition));//统计全部品牌总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'Brand';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'brands','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Brand');
        $this->Pagination->init($condition, $parameters, $options);

        $fields[] = 'Brand.id';
        $fields[] = 'Brand.code';
        $fields[] = 'Brand.url';
        $fields[] = 'Brand.orderby';
        $fields[] = 'Brand.status';
        $fields[] = 'BrandI18n.name';
        $brand_list = $this->Brand->find('all', array('conditions' => $condition, 'order' => 'Brand.orderby asc,Brand.created desc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));

        if (!empty($brand_list) && sizeof($brand_list) > 0) {
            foreach ($brand_list as $k => $v) {
                $brind_ids[] = $v['Brand']['id'];
            }
            $this->Product->hasOne = array();
            $product_list = $this->Product->find('all', array('conditions' => array('Product.brand_id' => $brind_ids, 'Product.status' => '1'), 'fields' => array('count(Product.id) as countnum', 'Product.brand_id'), 'group' => 'Product.brand_id'));
            $productbrand_list = array();
            foreach ($product_list as $k => $v) {
                $productbrand_list[$v['Product']['brand_id']] = isset($v[0]['countnum']) ? $v[0]['countnum'] : 0;
            }
            $this->set('productbrand_list', $productbrand_list);
        }

        if (!empty($this->params['url'])) {
            $url = $this->params['url']['url'].'?';
            //$url="";
            foreach ($this->params['url'] as $k => $v) {
                if ($k == 'url') {
                } else {
                    $url .= $k.'='.$v.'&';
                }
            }
            //pr($url);
        }
        $_SESSION['index_url'] = $url;
        $this->set('brand_list', $brand_list);//品牌列表
        $this->set('brand_keywords', $brand_keywords);//关键字选中
        $this->set('title_for_layout', $this->ld['manager_brands'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->set('this_page', $page);
    }

    /**
     *品牌 新增/编辑.
     *
     *@param int $id 输入品牌ID
     */
    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('brands_add');
        } else {
            $this->operator_privilege('brands_edit');
        }
        $this->menu_path = array('root' => '/product/','sub' => '/brands/');
        $this->set('title_for_layout', $this->ld['add_edit_brand'].'- '.$this->ld['manager_brands'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
//		$this->navigations[]=array('name'=>$this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['manager_brands'],'url' => '/brands/');
        $this->CategoryType->set_locale($this->backend_locale);
        $category_type_tree = $this->CategoryType->tree();// 类目树
        $this->set('category_type_tree', $category_type_tree);
        if ($this->RequestHandler->isPost()) {
            $code = $this->data['Brand']['code'];
            $rcode = '';
            $result = '';
            $name_code = $this->Brand->find('all', array('fields' => 'Brand.code'));
            if (isset($name_code) && !empty($name_code)) {
                foreach ($name_code as $vv) {
                    $rcode[] = $vv['Brand']['code'];
                }
            } else {
                $result['code'] = '1';
            }
            if (empty($this->data['Brand']['id'])) {
                if (isset($code) && $code != '') {
                    if (in_array($code, $rcode)) {
                        $result['code'] = '0';
                    } else {
                        $result['code'] = '1';
                    }
                } else {
                    $msg = '品牌代码为空';
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	history.go(-1);</script>';
                    die();
                }
            } else {
                $shelf_count = $this->Brand->find('first', array('conditions' => array('Brand.id' => $this->data['Brand']['id'])));
                if ($shelf_count['Brand']['code'] != $code && in_array($code, $rcode)) {
                    $result['code'] = '0';
                } else {
                    $result['code'] = '1';
                }
            }
            if ($result['code'] == 0) {
                $msg = '品牌代码重复';
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	history.go(-1);</script>';
                die();
            }
            if (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 0) {
                $this->data['Brand']['orderby'] = 1;
                // 取出所有导航的 序值加1
                $all_brand = $this->Brand->find('all', array('fields' => 'Brand.id,Brand.orderby', 'order' => 'orderby asc', 'recursive' => '-1'));
                if (!empty($all_brand)) {
                    foreach ($all_brand as $k => $v) {
                        $all_brand[$k]['Brand']['orderby'] = $v['Brand']['orderby'] + 1;
                    }
                    $this->Brand->saveAll($all_brand);
                }
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 1) {
                $store_last = $this->Brand->find('first', array('recursive' => '-1', 'order' => 'orderby desc', 'limit' => '1'));
                $this->data['Brand']['orderby'] = $store_last['Brand']['orderby'] + 1;
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 2) {
                $store_change = $this->Brand->find('first', array('conditions' => array('Brand.id' => $_REQUEST['orderby_sel'])));
                $this->data['Brand']['orderby'] = $store_change['Brand']['orderby'] + 1;
                $all_brand = $this->Brand->find('all', array('conditions' => array('Brand.orderby >' => $store_change['Brand']['orderby']), 'recursive' => '-1'));
                if (!empty($all_brand)) {
                    foreach ($all_brand as $k => $v) {
                        $all_brand[$k]['Brand']['orderby'] = $v['Brand']['orderby'] + 1;
                    }
                    $this->Brand->saveAll($all_brand);
                }
            }
            $this->data['Brand']['flash_config'] = !empty($this->data['Brand']['flash_config']) ? $this->data['Brand']['orderby'] : '0';
            if (isset($this->data['Brand']['id']) && $this->data['Brand']['id'] != '') {
                $this->Brand->save(array('Brand' => $this->data['Brand'])); //关联保存
            } else {
                $this->Brand->saveAll(array('Brand' => $this->data['Brand'])); //关联保存
                $id = $this->Brand->getLastInsertId();
            }
            $this->BrandI18n->deleteall(array('brand_id' => $this->data['Brand']['id'])); //删除原有多语言
            foreach ($this->data['BrandI18n'] as $v) {
                $brandi18n_info = array(
                      'locale' => $v['locale'],
                      'brand_id' => $id,
                       'name' => isset($v['name']) ? $v['name'] : '',
                    'meta_keywords' => isset($v['meta_keywords']) ? $v['meta_keywords'] : '',
                    'meta_description' => isset($v['meta_description']) ? $v['meta_description'] : '',
                      'description' => $v['description'],
                      'img01' => $v['img01'],
                  );
                $this->BrandI18n->saveAll(array('BrandI18n' => $brandi18n_info));//更新多语言
            }
            foreach ($this->data['BrandI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_edit_brand'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data = $this->Brand->localeformat($id);
        $this->set('brand_category_type_id', isset($this->data['Brand']['category_type_id']) ? $this->data['Brand']['category_type_id'] : 0);//类目选中
        //导般 名称设置
        if (!empty($this->data['BrandI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].(isset($this->data['BrandI18n'][$this->backend_locale]['name']) ? $this->data['BrandI18n'][$this->backend_locale]['name'] : ''),'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_brand'],'url' => '');
        }

        //获取所有的品牌的对应关系
        $this->Brand->set_locale($this->backend_locale);
        $all_brand = $this->Brand->find('all');
        $this->set('all_brand', $all_brand);
    }

    public function act_view($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $code = $_POST['code'];
        $rname = '';
        $name_code = $this->Brand->find('all', array('fields' => 'Brand.code'));
        if (isset($name_code) && sizeof($name_code) > 0) {
            foreach ($name_code as $vv) {
                $rname[] = $vv['Brand']['code'];
            }
        } else {
            $result['code'] = '1';
        }
        if ($id == 0) {
            if (isset($code) && $code != '') {
                if (in_array($code, $rname)) {
                    $result['code'] = '0';
                } else {
                    $result['code'] = '1';
                }
            } else {
                $result['code'] = '0';
            }
        } else {
            $Brand_count = $this->Brand->find('first', array('conditions' => array('Brand.id' => $id)));
            if ($Brand_count['Brand']['code'] != $code && in_array($code, $rname)) {
                $result['code'] = '0';
                //   $result['msg'] = "用户名重复";
            } else {
                $result['code'] = '1';
            }
        }
        die(json_encode($result));
    }
    /**
     *品牌 批量操作.
     */
    public function batch_operations()
    {
        $brand_id = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $result['flag'] = 0;
        if ($brand_id != 0) {
            $condition['Brand.id'] = $brand_id;
            $this->Brand->deleteAll($condition);
            $this->BrandI18n->deleteAll(array('BrandI18n.brand_id' => $brand_id));
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
            }
            $result['flag'] = 1;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表品牌名称修改.
     */
    public function update_brand_name()
    {
        $this->Brand->hasMany = array();
        $this->Brand->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->BrandI18n->updateAll(
            array('name' => "'".$val."'"),
            array('brand_id' => $id, 'locale' => $this->locale)
        );
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
     *列表品牌code修改.
     */
    public function update_brand_code()
    {
        $this->Brand->hasMany = array();
        $this->Brand->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->Brand->updateAll(
            array('code' => "'".$val."'"),
            array('id' => $id)
        );
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
     *列表品牌网址修改.
     */
    public function update_brand_url()
    {
        $this->Brand->hasMany = array();
        $this->Brand->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->Brand->updateAll(
            array('url' => "'".$val."'"),
            array('id' => $id)
        );
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
     *列表排序修改.
     */
    public function update_brand_orderby()
    {
        $this->Brand->hasMany = array();
        $this->Brand->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->Brand->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //列表箭头排序
    public function changeorder($updowm, $id, $page = 1)
    {
        //如果值相等重新自动排序
        $a = $this->Brand->query('SELECT * 
			FROM `svoms_brands` as A inner join `svoms_brands` as B
			WHERE A.id<>B.id and A.orderby=B.orderby');
        $brand_one = $this->Brand->find('first', array('conditions' => array('Brand.id' => $id)));

        if (!empty($a)) {
            $all = $this->Brand->find('all', array('recursive' => -1));
            $i = 0;
            foreach ($all as $k => $vv) {
                $all[$k]['Brand']['orderby'] = ++$i;
            }
            $this->Brand->saveAll($all);
        }
        if ($updowm == 'up') {
            $brand_change = $this->Brand->find('first', array('conditions' => array('Brand.orderby <' => $brand_one['Brand']['orderby']), 'order' => 'orderby desc', 'limit' => '1', 'recursive' => -1));
        }
        if ($updowm == 'down') {
            $brand_change = $this->Brand->find('first', array('conditions' => array('Brand.orderby >' => $brand_one['Brand']['orderby']), 'order' => 'orderby asc', 'limit' => '1', 'recursive' => -1));
        }
        $t = $brand_one['Brand']['orderby'];
        $brand_one['Brand']['orderby'] = $brand_change['Brand']['orderby'];
        $brand_change['Brand']['orderby'] = $t;
        $this->Brand->save($brand_one);
        $this->Brand->save($brand_change);

        $condition = '';
        $total = $this->Brand->find('count');//统计全部品牌总数

        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'brands','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Brand');
        $this->Pagination->init($condition, $parameters, $options);

        $fields[] = 'Brand.id';
        $fields[] = 'Brand.code';
        $fields[] = 'Brand.url';
        $fields[] = 'Brand.orderby';
        $fields[] = 'Brand.status';
        $fields[] = 'BrandI18n.name';
        $this->Brand->set_locale($this->backend_locale);
        $brand_list = $this->Brand->find('all', array('order' => 'Brand.orderby asc,Brand.created desc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));

        $this->set('brand_list', $brand_list);
        $this->set('this_page', $page);

        Configure::write('debug', 1);
        $this->render('index');
        $this->layout = 'ajax';
    }
    /**
     *列表推荐修改.
     */
    public function toggle_on_status()
    {
        $this->Brand->hasMany = array();
        $this->Brand->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Brand->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *删除一个品牌.
     *
     *@param int $id 输入品牌ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_brand_failure'];
        $pn = $this->BrandI18n->find('list', array('fields' => array('BrandI18n.brand_id', 'BrandI18n.name'), 'conditions' => array('BrandI18n.brand_id' => $id, 'BrandI18n.locale' => $this->locale)));
        $this->Brand->deleteAll(array('Brand.id' => $id));
        $this->BrandI18n->deleteAll(array('brand_id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_brand'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_brand_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //快速添加品牌
    public function doinsertbrand()
    {
        $this->data1['Brand']['id'] = '';
        $this->data1['Brand']['code'] = isset($_POST['BrandCode']) ? $_POST['BrandCode'] : '';
        $this->Brand->saveAll($this->data1); //关联保存
        $id = $this->Brand->getLastInsertId();
        $this->BrandI18n->deleteall(array('brand_id' => $id)); //删除原有多语言
        foreach ($_POST['data1']['BrandI18n'] as $v) {
            $brandi18n_info = array(
                      'locale' => $v['locale'],
                      'brand_id' => $id,
                       'name' => isset($v['name']) ? $v['name'] : '',
                  );
            $a = $this->BrandI18n->saveAll(array('BrandI18n' => $brandi18n_info));//更新多语言
        }
        if ($a) {
            $result['message'] = $this->ld['complete_success'];
            $result['flag'] = 1;
            $brand_tree = $this->Brand->brand_tree($this->locale);//品牌获取
            $result['brand'] = $brand_tree;
            $result['last_brand'] = $id;
            //操作员日志
            if (isset($this->configs['open_OperatorLog']) && $this->configs['open_OperatorLog'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['quick_add_brand'].':'.$quick_brand_name, $this->admin['id']);
            }
        } else {
            $result['message'] = $this->ld['complete_failure'];
            $result['flag'] = 2;
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //检查品牌名唯一
    public function check_unique($brands_id = 0)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $name = $_POST['name'];
        $brands_id = $_POST['brands_id'];
        $rname = '';
        $this->Brand->set_locale($this->model_locale['brand']);
        $name_code = $this->Brand->find('all', array('fields' => 'BrandI18n.name'));
        if (isset($name_code) && !empty($name_code)) {
            foreach ($name_code as $vv) {
                $rname[] = $vv['BrandI18n']['name'];
            }
        } else {
            $result['code'] = '0';
        }
        if ($brands_id == 0) {
            if (isset($name) && $name != '') {
                if (in_array($name, $rname)) {
                    $result['code'] = '1';
                    //   $result['msg'] = "品牌重复";
                } else {
                    $result['code'] = '0';
                }
            }
        } else {
            $brand_count = $this->Brand->find('first', array('conditions' => array('Brand.id' => $brands_id)));
            if ($brand_count['Operator']['name'] != $name && in_array($name, $rname)) {
                $result['code'] = '1';
            } else {
                $result['code'] = '0';
            }
        }
        die(json_encode($result));
    }

    public function search_brand()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['content'] = '没有相关品牌';
        $keyword = $_REQUEST['val'];
        $keyword = str_replace('_', '/_', $keyword);
        $keyword = str_replace('%', '/%', $keyword);
        $condition['or']['Brand.code like'] = '%'.$keyword.'%';
        $condition['or']['BrandI18n.name like'] = '%'.$keyword.'%';
        $condition['Brand.status'] = 1;
        $condition['BrandI18n.locale'] = $this->backend_locale;
        $brand_list = $this->Brand->find('all', array('conditions' => $condition, 'fields' => array('Brand.id', 'BrandI18n.name', 'Brand.code')));
        if (!empty($brand_list)) {
            $result['flag'] = 1;
            $result['content'] = $brand_list;
        }
        die(json_encode($result));
    }

    public function doinsertbrand2()
    {
        $this->data1['Brand']['id'] = '';
        $this->data1['Brand']['code'] = isset($_POST['BrandCode']) ? $_POST['BrandCode'] : '';
        $this->Brand->saveAll($this->data1); //关联保存
        $id = $this->Brand->getLastInsertId();
        $this->BrandI18n->deleteall(array('brand_id' => $id)); //删除原有多语言
        foreach ($_POST['data1']['BrandI18n'] as $v) {
            $brandi18n_info = array(
                      'locale' => $v['locale'],
                      'brand_id' => $id,
                       'name' => isset($v['name']) ? $v['name'] : '',
                  );
            $a = $this->BrandI18n->saveAll(array('BrandI18n' => $brandi18n_info));//更新多语言
        }
        if ($a) {
            $result['message'] = $this->ld['complete_success'];
            $result['flag'] = 1;
            $result['code'] = $this->data1['Brand']['code'];
            //操作员日志
            if (isset($this->configs['open_OperatorLog']) && $this->configs['open_OperatorLog'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['quick_add_brand'].':'.$_POST['data1']['BrandI18n'][0]['name'], $this->admin['id']);
            }
        } else {
            $result['message'] = $this->ld['complete_failure'];
            $result['flag'] = 2;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
