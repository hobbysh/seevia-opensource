<?php

/*****************************************************************************
 * Seevia 产品销售报表管理控制器
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
 *这是一个名为 ProductSaleCategoryStatementsController 的控制器.
 *
 *@var
 *@var
 */
class ProductSaleCategoryStatementsController extends AppController
{
    public $name = 'ProductSaleCategoryStatements';
    public $helpers = array('Pagination','Ckeditor');
    public $components = array('Pagination','RequestHandler','Email','Phpexcel','Orderfrom');
    public $uses = array('VoteI18n','VoteOption','VoteOptionI18n','VoteLog','User','NavigationI18n','Navigation','CategoryProduct','CategoryProductI18n','Product','ProductsCategory','Resource','OrderProduct','Brand');

    public function index()
    {
        $this->operator_privilege('product_sale_statements_view');
        $this->menu_path = array('root' => '/reports/','sub' => '/product_sale_category_statements/');
        //	$this->pageTitle = "分销统计" ." - ".$this->configs['shop_name'];
            $this->navigations[] = array('name' => $this->ld['report'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['product_sales_reports'],'url' => '/product_sale_category_statements/');

        $this->set('title_for_layout', $this->ld['product_sales_reports'].' - '.$this->configs['shop_name']);

        $categories_tree = $this->CategoryProduct->tree('P', $this->backend_locale);//分类树
            $this->set('categories_tree', $categories_tree);

            //订单来源
            $this->Orderfrom->get($this);

        $bran_sel = $this->Brand->find('all', array('fields' => array('Brand.id', 'Brand.code', 'BrandI18n.name'), 'order' => 'Brand.orderby, Brand.code asc'));
        $this->set('bran_sel', $bran_sel);

        $condition = '';
        $condition['Order.payment_status'] = 2;//搜已付款的订单

            $type_id_arr = array();
        $type_arr = array();
        $ta_str = '';

        if (isset($_REQUEST['ta']) && $_REQUEST['ta'] != '') {
            $type_arr = explode(',', $_REQUEST['ta']);
            foreach ($type_arr as $k => $v) {
                $type_arr_detail = explode(':', $v);
                    //pr($type_arr_detail);
                    if (sizeof($type_arr_detail) == 2) {
                        $condition['and']['or'][$k]['Order.type'] = $type_arr_detail[0];
                        $condition['and']['or'][$k]['Order.type_id'] = $type_arr_detail[1];
                    }
                $ta_str = $_REQUEST['ta'];
            }
        }

        $this->set('type_arr', $type_arr);
        $this->set('ta_str', $ta_str);
                //品牌
            $this->Brand->set_locale($this->backend_locale);
        $brans = $this->Brand->find('list', array('fields' => array('Brand.id'), 'order' => 'orderby'));
        $brand_ids = array();

        if (isset($_REQUEST['pp'])) {
            $brand_ids = explode(',', $_REQUEST['pp']);
            if (in_array('0', $brand_ids)) {
                $brand_ids_array = array();
                $code_brand_list = $this->Product->find('list', array('fields' => array('Product.brand_id', 'Product.brand_id'), 'group' => 'Product.brand_id'));
                $brand_ids_array = array_diff($code_brand_list, $brans);
                $brand_ids = array_merge($brand_ids_array, $brand_ids);
            }
            $product_name = $this->Product->find('all', array('conditions' => array('Product.brand_id' => $brand_ids), 'fields' => 'Product.code'));
            $product_bcode = array();
            foreach ($product_name as $pn => $pv) {
                $product_bcode[] = $pv['Product']['code'];
            }
        }
        $this->set('brand_ids', $brand_ids);

            //付款时间
            $start_date = '';
        $end_date = '';
        if (empty($_REQUEST['st']) && empty($_REQUEST['ed'])) {
            //	$start_date=date('Y-m-d',strtotime('-1 month'));
                  $start_date = date('Y-m-01');
            $condition['Order.payment_time >'] = $start_date.' 00:00:00';
            $end_date = date('Y-m-d');
            $condition['Order.payment_time <'] = $end_date.' 23:59:59';
        }
        if (isset($_REQUEST['st']) && $_REQUEST['st'] != '') {
            $start_date = trim($_REQUEST['st']);
            $condition['Order.payment_time >'] = $start_date.' 00:00:00';
        }
        if (isset($_REQUEST['ed']) && $_REQUEST['ed'] != '') {
            $end_date = trim($_REQUEST['ed']);
            $condition['Order.payment_time <'] = $end_date.' 23:59:59';
        }
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);

        $fields = array('Product.category_id','SUM((OrderProduct.product_quntity-OrderProduct.refund_quantity)*(OrderProduct.product_price)+OrderProduct.adjust_fee) AS sum_price','SUM(OrderProduct.product_quntity-OrderProduct.refund_quantity) AS sum_quantity');//准备去掉需用到的字段

               $categories_list = $this->OrderProduct->find('all', array('conditions' => $condition, 'fields' => $fields, 'group' => 'Product.category_id'));

        if (isset($categories_list)) {
            foreach ($categories_list as $k => $v) {
                $categories_sum_format[$v['Product']['category_id']] = $v[0];
            }
        }
        if (isset($categories_sum_format)) {
            $this->CategoryProduct->sum_list = $categories_sum_format;
            $categories_sum_format = $this->CategoryProduct->subcat_get(0);
            $this->set('categories_sum_format', $categories_sum_format);
        }
    }
}
