<?php

/**
 * @category  PHP
 *
 * @author    Bo Huang <hobbysh@seevia.cn>
 * @copyright 2015 上海实玮网络科技有限公司
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 *
 * @version   Release: 1.0
 *
 * @link      http://www.seevia.cn
 */

/**
 *这是一个名为 PagesController 的控制器
 *后台首页控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class PagesController extends AppController
{
    public $name = 'Pages';
    public $components = array('RequestHandler','Cookie','Pagination');
    public $helpers = array('Html','Javascript','Pagination','Ckeditor');
    public $uses = array('Operator','Article','Navigation','Config','Application','Portal','Brand','Product','BrandI18n','CategoryType');

    /**
     *显示后台首页.
     */
    public function home()
    {
        //pr($this->backend_locale);
        $this->set('title_for_layout', $this->ld['manager_home'].' - '.$this->configs['shop_name']);
        if ($this->configs['shop_temporal_closed'] == 2) {
            $this->redirect('/pages/closed');
        } //被系统关店，不能进入后台
        //如果是定制站点 不执行相关操作

        $Portal_list = $this->Portal->find('all', array('conditions' => array('status' => 1)));
        $new_list = $this->Portal->type_array($Portal_list);
        $list_arr = $this->Portal->list_array($Portal_list);
        $img_list = $this->Portal->img_array($Portal_list);
        $this->set('Portal_list', $new_list);
        $this->set('list_arr', $list_arr);
        $this->set('img_list', $img_list);
    }
    public function home2($page = 1)
    {
        //pr($this->backend_locale);
//        $this->set("title_for_layout",$this->ld['manager_home']." - ".$this->configs['shop_name']);
//        if($this->configs['shop_temporal_closed']==2){$this->redirect('/pages/closed');} //被系统关店，不能进入后台
//        //如果是定制站点 不执行相关操作
//        if(($this->configs['use_app'])&&$this->configs['use_app']=='1'){
//        }
//        $Portal_list=$this->Portal->find("all",array("conditions"=>array("status"=>1)));
//        $new_list=$this->Portal->type_array($Portal_list);
//        $list_arr=$this->Portal->list_array($Portal_list);
//        $img_list=$this->Portal->img_array($Portal_list);
//        $this->set("Portal_list",$new_list);
//        $this->set("list_arr",$list_arr);
//        $this->set("img_list",$img_list);
        $this->operator_privilege('brands_view');
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
        $this->layout = 'default2';
    }
    /**
     *品牌 新增/编辑.
     *
     *@param int $id 输入品牌ID
     */
    public function test_view($id = 0)
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
            $this->redirect('/'.$_SESSION['index_url']);
        }
        $this->data = $this->Brand->localeformat($id);
        $this->set('brand_category_type_id', isset($this->data['Brand']['category_type_id']) ? $this->data['Brand']['category_type_id'] : 0);//类目选中
        //导般 名称设置
        if (!empty($this->data['BrandI18n'][$this->locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].(isset($this->data['BrandI18n'][$this->locale]['name']) ? $this->data['BrandI18n'][$this->locale]['name'] : ''),'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_brand'],'url' => '');
        }
        $this->layout = 'default2';
        //获取所有的品牌的对应关系
        $this->Brand->set_locale($this->backend_locale);
        $all_brand = $this->Brand->find('all');
        $this->set('all_brand', $all_brand);
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
        $this->render('home2');
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
     *后台分页数Cookie记录.
     */
    public function pagers_num($number)
    {
        $this->Cookie->write('pagers_num_cookies', $number);
        Configure::write('debug', 0);
        die();
    }

    /**
     *登入页显示.
     */
    public function login()
    {
        $this->set('title_for_layout', $this->ld['shop_management_system_login_page']);
        $this->set('count_login', $this->Cookie->read('count_login'));

        $this->admin = $this->Operator->check_login();
        if ($this->admin) {
            $this->redirect('/pages/home');
        }

        if (isset($_SESSION['msg']) && $_SESSION['msg'] == '1') {
        } else {
            unset($_SESSION['msg']);
        }
        unset($_SESSION['login_is_msg']);
        $this->set('backend_locale', $this->backend_locale);
        $this->pageTitle = $this->ld['operator_log'].' - '.$this->configs['shop_name'];
        $this->layout = 'page';

        $count_login = $this->Cookie->read('count_login');
        $this->set('count_login', $count_login);
    }

    public function logout()
    {
        //重新设置session_id
        session_regenerate_id(true);
        $this->Operator->logout();
        unset($_SESSION['url']);
        unset($_SESSION['template_operator']);
        $this->redirect('/login');
    }

    public function closed()
    {
        $this->layout = '';
    }

    //用户密码修改
    public function edit()
    {
        $this->navigations[] = array('name' => $this->ld['change_password'],'url' => '');
        $this->set('navigations', $this->navigations);
        $this->set('user_name', $this->admin['name']);
        $this->set('title_for_layout', $this->ld['change_password'].' - '.$this->configs['shop_name']);

        if ($this->RequestHandler->isPost()) {
            $pwd = $_REQUEST['old_pwd'];
            $new_pwd = $_REQUEST['new_pwd'];
            $md5_new_pwd = md5($new_pwd);
            $md5_pwd = md5($pwd);
            $operator_id = $this->admin['id'];
            if ($pwd != '' && $new_pwd != '') {
                $userinfo = $this->Operator->find('all', array('conditions' => array('id' => $this->admin['id'])));
                $operator_password = $userinfo['0']['Operator']['password'];
                if ($md5_pwd != $operator_password) {
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$this->ld['password_error'].'");location.href="/admin/pages/edit"</script>';

                    return;
                } else {
                    $this->Operator->updateAll(
                                    array('Operator.password' => "'$md5_new_pwd'"),
                                    array('Operator.id' => $userinfo['0']['Operator']['id'])
                             );
                    $this->admin['password'] = $md5_new_pwd;
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$this->ld['modified_successfully_login_again'].'");location.href="/admin/pages/logout";</script>';

                    return;
                }
            }
        }
    }

    /* 清除缓存 */
    public function clear_cache()
    {
        //$result['count'] = $this->clear_cache_files();
        $result['count'] = $this->clear_cache_files();
        $result['msg'] = sprintf($this->ld['total_clear_cache_files'], $result['count']);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /* 清除缓存文件 */
    public function clear_cache_files()
    {
        $cache_dirs[] = TMPCO.'cache/models/';
        $cache_dirs[] = TMPCO.'cache/persistent/';
        $cache_dirs[] = TMPCO.'cache/views/';
        $cache_dirs[] = TMP.'cache/models/';
        $cache_dirs[] = TMP.'cache/persistent/';
        $cache_dirs[] = TMP.'cache/views/';
        //$cache_dirs[]=TMPCO.'cache/';
       // $cache_dirs[]=TMP.'cache/';
       $count = 0;
        foreach ($cache_dirs as $dir) {
            $folder = @opendir($dir);
            if ($folder === false) {
                continue;
            }
            while ($file = readdir($folder)) {
                if ($file == '.' || $file == '..' || $file == '.svn' || $file == 'empty') {
                    continue;
                }
                if (is_file($dir.$file)) {
                    //  echo $dir . $file.'<br>';
                    if (@unlink($dir.$file)) {
                        ++$count;
                    }
                }
            }
            closedir($folder);
        }

        return $count;
    }
}
