<?php

/*****************************************************************************
 * Seevia 回收商品管理
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
class TrashController extends AppController
{
    public $name = 'Trash';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript');
    public $uses = array('CategoryProduct','Product','ProductI18n','ProductGallery','ProductsCategory','ProductAttribute',
                    'ProductRank','ProductDownload','ProductService','OperatorLog','PackageProduct', );
    //"ProviderProduct",
    public function index($page = 1)
    {
        $this->operator_privilege('products_trash');
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['recycle_bin'],'url' => '/trash/');
        $this->Product->hasMany = array();
        $this->Product->set_locale($this->backend_locale);
        $condition['Product.status'] = '2';
        if (true) {
            if (isset($this->params['url']['category_id']) && $this->params['url']['category_id'] != 0) {
                $category_id = $this->params['url']['category_id'];
                $this->CategoryProduct->hasOne = array('CategoryProductI18n' => array('className' => 'CategoryProductI18n',
                                  'order' => '',
                                  'dependent' => true,
                                  'foreignKey' => 'category_id',
                            ),
                      );
                $this->CategoryProduct->tree('P', $this->locale, $category_id);
                $category_ids = isset($this->CategoryProduct->allinfo['subids'][$category_id]) ? $this->CategoryProduct->allinfo['subids'][$category_id] : $category_id;
                $condition['and']['Product.category_id'] = $category_ids;
            }
            if (isset($this->params['url']['keywords']) && $this->params['url']['keywords'] != '') {
                $keywords = $this->params['url']['keywords'];
                $condition['and']['or']['Product.code like'] = "%$keywords%";
                $condition['and']['or']['ProductI18n.name like'] = "%$keywords%";
                $condition['and']['or']['ProductI18n.description like'] = "%$keywords%";
                $condition['and']['or']['Product.id like'] = "%$keywords%";
                $this->set('keywords1', $this->params['url']['keywords']);
            }
            if (isset($this->params['url']['date']) && $this->params['url']['date'] != '') {
                $condition['and']['Product.modified >='] = $this->params['url']['date'].' 00:00:00';
                $this->set('date', $this->params['url']['date']);
            }
            if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
                $condition['and']['Product.modified <='] = $this->params['url']['date2'].' 23:59:59';
                $this->set('date2', $this->params['url']['date2']);
            }
        }
        $total = $this->Product->find('count', array('conditions' => $condition)); //pr($total);
        $sortClass = 'Product';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'trash','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Product');
        $this->Pagination->init($condition, $parameters, $options);
        $fields[] = 'Product.id';
        $fields[] = 'Product.code';
        $fields[] = 'Product.shop_price';
        $fields[] = 'Product.quantity';
        $fields[] = 'Product.recommand_flag';
        $fields[] = 'Product.modified';
        $fields[] = 'Product.forsale';
        $fields[] = 'ProductI18n.name';
        $products_list = $this->Product->find('all', array('conditions' => $condition, 'fields' => $fields, 'order' => 'Product.created DESC', 'limit' => $rownum, 'page' => $page));

        $keywords = isset($this->params['form']['keywords']) ? $this->params['form']['keywords'] : '';
        if (!empty($products_list)) {
            foreach ($products_list as $k => $v) {
                $products_list[$k]['Product']['format_shop_price'] = '';
                $products_list[$k]['Product']['format_shop_price'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $v['Product']['shop_price']));
            }
        }
        $this->CategoryProduct->hasOne = array('CategoryProductI18n' => array('className' => 'CategoryProductI18n',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'category_id',
                        ),
                  );

        $categories_tree = $this->CategoryProduct->tree('P', $this->locale);
        $category_id = isset($this->params['url']['category_id']) ? $this->params['url']['category_id'] : '0';

        $this->set('category_id', $category_id);
        $this->set('categories_tree', $categories_tree);
        $this->set('products_list', $products_list);
        $this->set('keywords', $keywords);
        $this->set('title_for_layout', $this->ld['recycle_bin'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    //单独处理回收站商品---还原商品
    public function revert($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['resume_failture'];

        $this->Product->updateAll(array('Product.status' => '1'), array('Product.id' => $id));
        $pn = $this->ProductI18n->find('list', array('fields' => array('ProductI18n.product_id', 'ProductI18n.name'), 'conditions' => array('ProductI18n.product_id' => $id, 'ProductI18n.locale' => $this->locale)));
        $result['flag'] = 1;
        $result['message'] = $this->ld['resume_success'];
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['resume_products'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
//    	$this->set("type",true);
//        $this->flash($this->ld['product_has_restored'],'/trash/',10);
                Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //单独处理回收站商品---彻底删除商品
    public function com_delete($id)
    {
        $this->operator_privilege('products_recycle_bin');
        $this->Product->hasOne = array();
        $this->Product->hasMany = array();
        $product_info = $this->Product->findById($id);

        if (empty($product_info)) {
            $this->set('type', false);
            $this->flash($this->ld['product_does_not_exist'], '/trash/', 10);
        } else {
            if ($product_info['Product']['status'] != 2) {
                $this->set('type', false);
                $this->flash($this->ld['product_not_put_into_recycle_bin'], '/trash/', 10);
            } else {
                $result['flag'] = 2;
                $result['message'] = $this->ld['delete_failure'];

                if ($product_info['Product']['extension_code'] == 'download_product') {
                    $this->ProductDownload->deleteAll(array('ProductDownload.product_id' => $id));
                }
                if ($product_info['Product']['extension_code'] == 'services_product') {
                    $this->ProductService->deleteAll(array('ProductService.product_id' => $id));
                }
                $pn = $this->ProductI18n->find('list', array('fields' => array('ProductI18n.product_id', 'ProductI18n.name'), 'conditions' => array('ProductI18n.product_id' => $id, 'ProductI18n.locale' => $this->locale)));
                /* 删除商品 */
                $this->Product->delete($id);
                /* 删除相关表记录 */
                $this->ProductsCategory->deleteAll(array('ProductsCategory.product_id' => $id));
                $this->ProductAttribute->deleteAll(array('ProductAttribute.product_id' => $id));
                $this->ProductRank->deleteAll(array('ProductRank.product_id' => $id));
                $condition['or']['PackageProduct.product_id'] = $id;
                $condition['or']['PackageProduct.package_product_id'] = $id;
                $this->PackageProduct->deleteAll($condition);//删除套装商品
                if (constant('Product') == 'AllInOne') {
                    $this->loadModel('ProductShippingFee');
                    $this->ProductShippingFee->deleteAll(array('ProductShippingFee.product_id' => $id));
                }
                $this->ProductGallery->deleteAll(array('ProductGallery.product_id' => $id));
                $this->delDirAndFile('../img/products/'.$product_info['Product']['code']);

                $result['flag'] = 1;
                $result['message'] = $this->ld['deleted_success'];

                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete_product'].':id '.$id.' '.$pn[$id], $this->admin['id']);
                }
                $result['flag'] = 1;
                $result['message'] = $this->ld['delete_article_success'];
                Configure::write('debug', 0);
                $this->layout = 'ajax';
                die(json_encode($result));
            }
        }
    }

    //批量处理回收站的商品
    public function batch()
    {
        $pro_ids = !empty($this->params['form']['checkbox']) ? $this->params['form']['checkbox'] : array();
        if ($this->params['form']['act_type'] == 'rev') {
            $this->Product->updateAll(array('Product.status' => '1'), array('Product.id' => $pro_ids));
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['batch_reduction_products'], $this->admin['id']);
            }
            $this->redirect('/trash/');
            //$this->set("type",true);
            //$this->flash("商品已还原",'/trash/','');
        }

        if ($this->params['form']['act_type'] == 'del') {
            $this->operator_privilege('products_recycle_bin');
            $this->Product->hasOne = array();
            $this->Product->hasMany = array();
                /* 删除相关表记录 */
                $this->ProductsCategory->deleteAll(array('ProductsCategory.product_id' => $pro_ids));
            $this->ProductAttribute->deleteAll(array('ProductAttribute.product_id' => $pro_ids));
            $this->ProductRank->deleteAll(array('ProductRank.product_id' => $pro_ids));
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('ProductShippingFee');
                $this->ProductShippingFee->deleteAll(array('ProductShippingFee.product_id' => $pro_ids));
            }
            $this->ProductGallery->deleteAll(array('ProductGallery.product_id' => $pro_ids));

            $product_info = $this->Product->find('all', array('conditions' => array('id' => $pro_ids)));
            //pr($product_info);die();
            foreach ($product_info as $k => $v) {
                if ($v['Product']['extension_code'] == 'download_product') {
                    $this->ProductDownload->deleteAll(array('ProductDownload.product_id' => $v['Product']['id']));
                }
                if ($v['Product']['extension_code'] == 'services_product') {
                    $this->ProductService->deleteAll(array('ProductService.product_id' => $v['Product']['id']));
                }
                //pr("-=-=-=-=-=");
                /* 删除商品 */
                $this->Product->deleteAll(array('Product.id' => $v['Product']['id']));
                $this->ProductI18n->deleteAll(array('ProductI18n.product_id' => $v['Product']['id']));
                $this->delDirAndFile('../img/products/'.$v['Product']['code']);

                //$this->set("type",true);
                //$this->flash("商品已删除",'/trash/','');
            }
        }
             //操作员日志
             if ($this->configs['operactions-log'] == 1) {
                 $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['batch_delete_products'], $this->admin['id']);
             }
        $this->redirect('/trash/');
    }
    //删除文件和目录
    public function delDirAndFile($dirName)
    {
        if (file_exists($dirName) && $handle = opendir("$dirName")) {
            while (false !== ($item = readdir($handle))) {
                if ($item != '.' && $item != '..') {
                    if (is_dir("$dirName/$item")) {
                        $this->delDirAndFile("$dirName/$item");
                    } else {
                        unlink("$dirName/$item");
                    }
                }
            }
            closedir($handle);
            rmdir($dirName);
        }
    }
}
