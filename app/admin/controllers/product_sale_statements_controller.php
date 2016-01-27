<?php

/*****************************************************************************
 * Seevia 订单商品统计控制器
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
 *这是一个名为 ProductSaleStatementsController 的控制器.
 *
 *@var
 *@var
 */
class ProductSaleStatementsController extends AppController
{
    public $name = 'ProductSaleStatements';
    public $helpers = array('Pagination','Ckeditor');
    public $components = array('Pagination','RequestHandler','Email','Phpexcel','Orderfrom');
    public $uses = array('UserPointLog','Application','Language','PaymentI18n','Payment','UserAddress','OrderProduct','Product','CategoryProduct','CategoryProductI18n','Brand','BrandI18n','Coupon','CouponType','OrderAction','UserBalanceLog','InvoiceType','LogisticsCompany','Payment','Shipping','User','Order','InformationResource','Resource','ResourceI18n','PaymentApiLog','MailTemplate','MailSendQueue','PaymentI18n','Stock','Store','CategoryType','Profile','ProfileFiled','ProfilesFieldI18n');

    public function index($export = 0)
    {
        //TODO 判断购物车应用
        $this->operator_privilege('product_sale_statements_edit');
        $this->menu_path = array('root' => '/oms/','sub' => '/product_sale_category_statements/');
        $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['product_sales_reports'],'url' => '/product_sale_category_statements/');
        $this->navigations[] = array('name' => $this->ld['order_statistics'],'url' => '/product_sale_statements/');

    //?tds=pc,pn,ct,pp,sn,sp,&kw=test&pp=135,&ta=网站,门店,批发,mvptracy,&ca=25&tia=ioco,taobao,&st=2012-02-17&ed=2012-02-01

        if (isset($_REQUEST['tds'])) {
            $tds = explode(',', $_REQUEST['tds']);
        } else {
            $tds = array('pc','pn','ct','pp','sn','sp');
        }
        $this->set('tds', $tds);
    //	pr

        //订单来源
        $this->Orderfrom->get($this);

        //分类
        $this->CategoryProduct->set_locale('chi');
        $category_tree = $this->CategoryProduct->tree('P', 'chi');
        $this->set('category_tree', $category_tree);

        $condition = '';
        $condition['Order.payment_status'] = 2;//搜已付款的订单
    //	$condition['and']["OrderProduct.product_code <>"]='';//搜有货号的商品
        $type_id_arr = array();
        $type_arr = array();
        $ta_str = '';
//		if(isset($_REQUEST['box3'])){
//			$type_id_arr = $_REQUEST['box3'];
//			$condition["Order.type_id"]=$type_id_arr;
//		}
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
//		$this->set("type_id_arr",$type_id_arr);
        $this->set('type_arr', $type_arr);
        $this->set('ta_str', $ta_str);
        //搜索货号用
        $keywords = '';
        if (isset($_REQUEST['kw']) && $_REQUEST['kw'] != '') {
            $keywords = trim($_REQUEST['kw']);
            //$keywords = mb_convert_encoding($keywords,'gbk','utf-8');
            //if(isset($_GET["keywords"]))
                //$keywords = $this->getSafeCode($keywords);
                //$keywords = mb_convert_encoding($keywords,'gbk','utf-8');
            $condition['or']['OrderProduct.product_code like'] = "%$keywords%";
            $condition['or']['OrderProduct.product_name like'] = "%$keywords%";
        }
        $this->set('keywords', $keywords);
        $this->Product->set_locale($this->backend_locale);
        //品牌
        $this->Brand->set_locale($this->backend_locale);
        $brans = $this->Brand->find('list', array('fields' => array('Brand.id'), 'order' => 'orderby'));
        $brand_ids = array();
    //	pr($_REQUEST["pp"]);die;
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
        //类目
        if (isset($_REQUEST['lm'])) {
            $cats = explode(',', $_REQUEST['lm']);
            $this->set('cat', $cats);
            $product_name = $this->Product->find('all', array('conditions' => array('Product.category_type_id' => $cats), 'fields' => 'Product.code'));
            $product_code = array();
            foreach ($product_name as $pn => $pv) {
                $product_code[] = $pv['Product']['code'];
            }
            $condition['OrderProduct.product_code'] = $product_code;
        }
        //分类
        $ca = array();
        if (isset($_REQUEST['ca']) && $_REQUEST['ca'] != '-1') {
            $ca = explode(',', $_REQUEST['ca']);
            $product_ccode = array();
            foreach ($ca as $c) {
                $product_name = $this->Product->find('all', array('conditions' => array('Product.category_id' => $c), 'fields' => 'Product.code'));
                foreach ($product_name as $pn => $pv) {
                    $product_ccode[] = $pv['Product']['code'];
                }
            }
            $condition['and']['OrderProduct.product_code'] = $product_ccode;
        }

        //分类
        if (isset($_REQUEST['cate']) && $_REQUEST['cate'] != '') {
            $selected_categories = (isset($this->CategoryProduct->all_subcat[trim($_REQUEST['cate'])])) ? $this->CategoryProduct->all_subcat[trim($_REQUEST['cate'])] : array();
            $condition['and']['Product.category_id'] = $selected_categories;
            $this->set('selected_categories', $selected_categories);
        } else {
            $this->set('selected_categories', array());
        }

        if (isset($product_bcode) && isset($product_ccode)) {
            $product_code = array_intersect($product_bcode, $product_ccode);
            $condition['and']['OrderProduct.product_code'] = $product_code;
        } elseif (isset($product_bcode) && !isset($product_ccode)) {
            $condition['and']['OrderProduct.product_code'] = $product_bcode;
        } elseif (!isset($product_bcode) && isset($product_ccode)) {
            $condition['and']['OrderProduct.product_code'] = $product_ccode;
        }

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

        //品牌
        //$this->Brand->set_locale($this->backend_locale['brand']);
        $bran_sel = $this->Brand->find('all', array('fields' => array('Brand.id', 'Brand.code', 'BrandI18n.name'), 'order' => 'Brand.orderby, Brand.code asc'));
//		$bran_sel[wu]['Brand']['code']='';
//		$bran_sel[wu]['BrandI18n']['name']='未知品牌';

        $this->set('bran_sel', $bran_sel);
        //类目
            $this->CategoryType->set_locale($this->backend_locale);
        $category_type_tree = $this->CategoryType->tree();
        $cats = array();
        foreach ($category_type_tree as $v) {
            $cats[$v['CategoryType']['id']] = $v['CategoryTypeI18n']['name'];
        }
        $this->set('ct', $cats);
        $this->set('category_type_tree', $category_type_tree);

//		$this->OrderProduct->hasOne = array();

        $order_product_codes = $this->OrderProduct->find('all', array('conditions' => $condition, 'group' => 'OrderProduct.product_code', 'fields' => array('count(product_code) as count,product_code')));
        $total = count($order_product_codes);
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'OrderProduct';
        $page = 1;
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
//pr ($condition);
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'product_sale_statements','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OrderProduct');
        $this->Pagination->init($condition, $parameters, $options);
        $fields = array('OrderProduct.order_id','OrderProduct.product_id','OrderProduct.product_code','OrderProduct.product_name','Order.id','Order.order_code','Order.payment_time','Order.point_fee','Order.discount','Order.consignee','Order.telephone','Order.shipping_status','Order.payment_status','Order.status','Order.created','Order.type','SUM((OrderProduct.product_quntity-OrderProduct.refund_quantity)*(OrderProduct.product_price)+OrderProduct.adjust_fee) AS sum_price','SUM(OrderProduct.product_quntity-OrderProduct.refund_quantity) AS sum_quantity','Product.brand_id','Product.category_id');//需用到的字段
        if (isset($export) && ($export === 'export' || $export === 'print')) {
            $orderproducts_list = $this->OrderProduct->find('all', array('conditions' => $condition, 'fields' => $fields, 'group' => 'product_code', 'order' => 'OrderProduct.created DESC'));
        } else {
            $orderproducts_list = $this->OrderProduct->find('all', array('conditions' => $condition, 'fields' => $fields, 'group' => 'product_code', 'order' => 'OrderProduct.created DESC', 'page' => $page, 'limit' => $rownum));
        }
    //	$orderproducts_list=$this->OrderProduct->find("all",array("conditions"=>$condition,"fields"=>$fields,"group"=>"OrderProduct.product_code","order"=>"OrderProduct.created DESC","page"=>$page,"limit"=>$rownum));
    //	pr($condition);pr($orderproducts_list);
        $quntity_total = 0;
        $price_total = 0;
        $this->Product->set_locale($this->backend_locale);
        $product_brands = $this->Product->find('list', array('fields' => array('Product.code', 'Product.brand_id')));
        $product_categorys = $this->Product->find('list', array('fields' => array('Product.code', 'Product.category_id')));
        $brand_names = $this->BrandI18n->find('list', array('fields' => array('BrandI18n.brand_id', 'BrandI18n.name'), 'conditions' => array('BrandI18n.locale' => 'chi')));
        $category_names = $this->CategoryProductI18n->find('list', array('fields' => array('CategoryProductI18n.category_id', 'CategoryProductI18n.name'), 'conditions' => array('CategoryProductI18n.locale' => 'chi')));
        //pr($product_categorys);die;
        foreach ($orderproducts_list as $k => $v) {
            $product_quntity_total = 0;
            $product_price_total = 0;
            $this->OrderProduct->hasOne = array();
             //$products_list=$this->OrderProduct->find("all",array("conditions"=>array("OrderProduct.product_code"=>$v['OrderProduct']['product_code']),"fields"=>array("OrderProduct.product_quntity","OrderProduct.product_price")));
             //pr($products_list);
             $quntity_total = $quntity_total + $v[0]['sum_quantity'];
            $price_total = $price_total + $v[0]['sum_price'];
            $orderproducts_list[$k]['OrderProduct']['product_quntity_total'] = $v[0]['sum_quantity'];
            $orderproducts_list[$k]['OrderProduct']['product_price_total'] = $v[0]['sum_price'];

            $brand_id = $v['Product']['brand_id'];
            $orderproducts_list[$k]['OrderProduct']['brand_name'] = isset($brand_names[$brand_id]) ? $brand_names[$brand_id] : '';

            $category_id = $v['Product']['category_id'];
            $orderproducts_list[$k]['OrderProduct']['category_name'] = isset($category_names[$category_id]) ? $category_names[$category_id] : '';
        }
        $this->set('orderproducts_list', $orderproducts_list);//订单列表
        $this->set('quntity_total', $quntity_total);
        $this->set('price_total', $price_total);

        if (isset($export) && $export === 'export') {
            //  $filename=$this->ld['order_statistics'].date('Ymd').'.xls';
            $filename = '订单商品统计导出'.date('Ymd').'.xls';
            $this->Profile->set_locale($this->locale);
            $code = $_REQUEST['code'];
            $this->Profile->hasOne = array();
            $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $code, 'Profile.status' => 1)));
        //$profile_info=$this->Profile->find("all",array('conditions'=>array("Profile.code"=>"product_sale_statements_export","ProfileFiled.status"=>1),"order"=>'ProfileFiled.orderby asc'));
            $tmp = array();
            $fields_array = array();
            $data = array();
            if (isset($profile_id) && !empty($profile_id)) {
                $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
                foreach ($profilefiled_info as $k => $v) {
                    $tmp[] = $v['ProfilesFieldI18n']['description'];
                    $fields_array[] = $v['ProfileFiled']['code'];
                }
            }
            $data[] = $tmp;
          // $data[] = array($this->ld['business_code'],$this->ld['product_name'],$this->ld['sales_volume'],$this->ld['price']);
            foreach ($orderproducts_list as $key => $val) {
                $data_tmp = array();
                foreach ($fields_array as $kk => $vv) {
                    $fields_kk = explode('.', $vv);
                    $data_tmp[] = isset($val[$fields_kk[0]][$fields_kk[1]]) ? $val[$fields_kk[0]][$fields_kk[1]] : '';
                }
                $data[] = $data_tmp;
//		    	$data[$key+1][]=$val["OrderProduct"]["product_code"];
//		    	$data[$key+1][]=$val["OrderProduct"]["product_name"];
//		    	$data[$key+1][]=$val["OrderProduct"]["product_quntity_total"];
//		    	$data[$key+1][]=$val["OrderProduct"]["product_price_total"];
            }
            $data[] = array('','',$this->ld['total_amount'].' '.$quntity_total,$this->ld['order_abbr_total'].' '.$price_total);
            $this->Phpexcel->output($filename, $data);
            exit;
        }
        $this->set('title_for_layout', $this->ld['order_statistics'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    public function getSafeCode($value)
    {
        $value_1 = $value;
        $value_2 = @iconv('utf-8 ', 'gb2312 ', $value_1);
        $value_3 = @iconv('gb2312 ', 'utf-8 ', $value_2);

        if (strlen($value_1)   ==   strlen($value_3)) {
            return   $value_2;
        } else {
            return   $value_1;
        }
    }
    public function view($product_code, $export = 0)
    {
        $this->operator_privilege('product_sale_statements_see');
        $this->set('title_for_layout', $this->ld['sales_details'].'-'.$product_code.' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['product_sales_reports'],'url' => '/product_sale_statements/');
        $this->navigations[] = array('name' => $this->ld['sales_details'].'-'.$product_code,'url' => '/product_sale_statements/view/'.$product_code);

        $this->set('product_code', $product_code);
        $condition = '';
        $condition['Order.payment_status'] = 2;//搜已付款的订单
        $order_id_array = array();
//		$order_product=$this->OrderProduct->find("all",array("conditions"=>array("OrderProduct.product_code"=>$product_code),'fields'=>'OrderProduct.product_code,OrderProduct.order_id,OrderProduct.refund_quantity'));
//		foreach($order_product as $odk=>$odv){
//			if(!empty($odv["OrderProduct"]["order_id"])){
//				$order_id_array[]=$odv["OrderProduct"]["order_id"];
//			}
//		}
//		if(!empty($order_id_array)){
//			$condition["Order.id"]=$order_id_array;
//		}
        $condition['OrderProduct.product_code'] = $product_code;
    //	pr($condition);die;

        //订单来源
        $this->Orderfrom->get($this);

        $type_id_arr = array();
        $type_arr = array();
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

        $this->Order->hasMany = array();//去掉关联
        //搜索订单号用
        $keywords = '';
        if (isset($_REQUEST['kw']) && $_REQUEST['kw'] != '') {
            $keywords = trim($_REQUEST['kw']);
            $condition['Order.order_code like'] = "%$keywords%";
        //	$condition["Order.order_code"] = $keywords;
        }
        $this->set('keywords', $keywords);

        //付款时间
        $start_date = '';
        if (isset($_REQUEST['st']) && $_REQUEST['st'] != '') {
            $start_date = trim($_REQUEST['st']);
            $condition['Order.payment_time >'] = $start_date.' 00:00:00';
        }
        $this->set('start_date', $start_date);
        $end_date = '';
        if (isset($_REQUEST['ed']) && $_REQUEST['ed'] != '') {
            $end_date = trim($_REQUEST['ed']);
            $condition['Order.payment_time <'] = $end_date.' 23:59:59';
        }
        $this->set('end_date', $end_date);
        $total = $this->OrderProduct->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'Order';
        $page = 1;
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'product_sale_statements','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Order','total' => $total);
        $this->Pagination->init($condition, $parameters, $options);
        $fields = array('Order.id','Order.order_code','Order.payment_time','Order.point_fee','Order.payment_id','Order.discount','Order.consignee','Order.telephone','Order.shipping_status','Order.payment_status','Order.status','Order.created','Order.modified','Order.type','Order.type_id');//需用到的字段
        if (isset($export) && $export) {
            $order_list = $this->OrderProduct->find('all', array('conditions' => $condition, 'order' => 'Order.created DESC'));
        } else {
            $order_list = $this->OrderProduct->find('all', array('conditions' => $condition, 'order' => 'Order.created DESC', 'page' => $page, 'limit' => $rownum));
        }
//		pr($condition);
//		pr($order_list);
        $payment_names = $this->PaymentI18n->find('list', array('fields' => array('PaymentI18n.payment_id', 'PaymentI18n.name'), 'conditions' => array('PaymentI18n.locale' => 'chi')));
        $this->set('payment_names', $payment_names);

//		foreach($order_list as $k => $v){
//			$this->OrderProduct->hasOne = array();
//			$order_product=$this->OrderProduct->find("first",array("conditions"=>array("OrderProduct.product_code"=>$product_code,"OrderProduct.order_id"=>$v['Order']['id']),'fields'=>array('OrderProduct.product_price','OrderProduct.adjust_fee','OrderProduct.product_quntity','OrderProduct.product_code','OrderProduct.product_name','OrderProduct.refund_quantity')));
//			$order_list[$k]['OrderProduct']=$order_product['OrderProduct'];
//			$order_list[$k]['Order']['payment_name']=isset($payment_names[$v["Order"]["payment_id"]])?$payment_names[$v["Order"]["payment_id"]]:"";
//		}
        //pr($order_list);die;
        $this->set('order_list', $order_list);//订单列表
        if (isset($export) && $export) {
            $filename = $this->ld['product_sales_reports'].'_'.$product_code.'_'.date('Ymd').'.xls';
            $data = array();
            $data[] = array($this->ld['order_reffer'],$this->ld['order_code'],$this->ld['business_code'],$this->ld['product_name'],$this->ld['sales_volume'],$this->ld['price'],$this->ld['paymengts'],$this->ld['time_of_payment'],$this->ld['consignee']);
            foreach ($order_list as $ok => $ov) {
                $type = isset($order_type_arr[$ov['Order']['type_id']]) ? $order_type_arr[$ov['Order']['type_id']] : $ov['Order']['type_id'];
                if ($ov['Order']['type'] == 'store') {
                    $data[$ok + 1][] = $this->ld['order_store'].' - '.$type;
                } elseif ($ov['Order']['type'] == 'taobao') {
                    $data[$ok + 1][] = $this->ld['taobao'].' - '.$type;
                } elseif ($ov['Order']['type'] == 'ioco') {
                    $data[$ok + 1][] = $this->ld['order_site'].' - '.$type;
                }
                $data[$ok + 1][] = $ov['Order']['order_code'];
                $data[$ok + 1][] = $product_code;
                $data[$ok + 1][] = $ov['OrderProduct']['product_name'];
                $data[$ok + 1][] = ($ov['OrderProduct']['product_quntity'] - $ov['OrderProduct']['refund_quantity']);
                $data[$ok + 1][] = ($ov['OrderProduct']['product_price']) * ($ov['OrderProduct']['product_quntity'] - $ov['OrderProduct']['refund_quantity']) + $ov['OrderProduct']['adjust_fee'];
                $data[$ok + 1][] = isset($payment_names[$ov['Order']['payment_id']]) ? $payment_names[$ov['Order']['payment_id']] : '';
                $data[$ok + 1][] = $ov['Order']['payment_time'];
                $data[$ok + 1][] = $ov['Order']['consignee'];
            }
            //pr($data);
            //die;
            $this->Phpexcel->output($filename, $data);
            exit;
        }
    }

    public function product_print()
    {
        //	pr($_REQUEST);
        if (isset($_REQUEST['start_date'])) {
            $select_arr = array();
            foreach ($_REQUEST as $pk => $pv) {
                if ($pv == 1) {
                    if ($pk == 'ioco') {
                        $select_arr[] = $this->ld['order_site'];
                    } else {
                        $select_arr[] = $pk;
                    }
                }
            }
            $order_types = implode(',', $select_arr);
            $start_date = trim($_REQUEST['start_date']);
            $end_date = trim($_REQUEST['end_date']);
            $keywords = trim($_REQUEST['keywords']);
        //	$order_types = array_unique($order_types);//去除重复
        }

        $this->layout = 'print';
        $this->set('title_for_layout', $this->ld['product_sales_reports'].' '.$this->ld['print']);
        $filename = $this->ld['product_sales_reports'].date('Ymd').'.html';
        $print_for_layout = $this->requestAction('product_sale_statements/index/print/', array('return'));
        //pr($print_for_layout);die();

        $ex_data = '<table class="oneorder"><tbody><tr>';
        if (!empty($order_types)) {
            $ex_data .= '<td class="onelabel">'.$this->ld['order_reffer'].':'.$order_types.'</td>';
        }
        if (!empty($start_date) || !empty($end_date)) {
            $ex_data .= '<td class="onelabel">'.$this->ld['payment_time'].':'.$start_date.'--'.$end_date.'</td>';
        }
        $ex_data .= '</tr><tr>';
        if (!empty($keywords)) {
            $ex_data .= '<td class="onelabel">'.$this->$ld['sku'].'</td>';
        }
        $ex_data .= '</tr></tbody></table>';

        $print_for_layout = $ex_data.$print_for_layout;
        $this->set('print_for_layout', $print_for_layout);
    }

    public function all_detail($export = 0)
    {
        $this->operator_privilege('product_sale_statements_detail');
        $this->menu_path = array('root' => '/oms/','sub' => '/product_sale_category_statements/');
        $this->set('title_for_layout', $this->ld['sales_details'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['product_sales_reports'],'url' => '/product_sale_statements/');
        $this->navigations[] = array('name' => $this->ld['all_sales_details'],'url' => '/product_sale_statements/all_detail/');

        $condition = '';
        $condition['Order.payment_status'] = 2;//搜已付款的订单
        $order_id_array = array();

    //	pr($condition);die;
        //订单来源
        $this->Orderfrom->get($this);
        $type_id_arr = array();
        $type_arr = array();
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

        $this->Order->hasMany = array();//去掉关联
        //搜索订单
        $order_code = '';
        if (isset($_REQUEST['oc']) && $_REQUEST['oc'] != '') {
            $order_code = trim($_REQUEST['oc']);
            $condition['Order.order_code like'] = "%$order_code%";
        }
        $this->set('order_code', $order_code);
        //搜索商品
        $keywords = '';
        if (isset($_REQUEST['kw']) && $_REQUEST['kw'] != '') {
            $keywords = trim($_REQUEST['kw']);
            $condition['or']['OrderProduct.product_code like'] = "%$keywords%";
            $condition['or']['OrderProduct.product_name like'] = "%$keywords%";
        }
        $this->set('keywords', $keywords);

        //搜索类型
            $cate = '';
        if (isset($_REQUEST['cate']) && $_REQUEST['cate'] != '') {
            $cate = trim($_REQUEST['cate']);
            $product_code = array();
            $categories_tree = $this->CategoryProduct->tree('P', $this->backend_locale);//分类树
            $product_name = $this->Product->find('all', array('conditions' => array('Product.category_id' => $cate), 'fields' => 'Product.code'));
            foreach ($product_name as $pn => $pv) {
                $product_code[] = $pv['Product']['code'];
            }

            foreach ($categories_tree as $v => $c) {
                //一级分类

                if (isset($c['SubCategory'])) {
                    //有二级子集

                    foreach ($c['SubCategory'] as $vv => $ca) {
                        if ($c['CategoryProduct']['parent_id'] == $cate) {
                            $product_name2 = $this->Product->find('all', array('conditions' => array('Product.category_id' => $ca['CategoryProduct']['id']), 'fields' => 'Product.code'));
                            foreach ($product_name2 as $pn2 => $pv2) {
                                $product_code[] = $pv2['Product']['code'];
                            }
                        }
                        if (isset($ca['SubCategory'])) {
                            //有三级子集

                                foreach ($ca['SubCategory'] as $vvv => $cat) {
                                    if ($ca['CategoryProduct']['parent_id'] == $cate) {
                                        $product_name3 = $this->Product->find('all', array('conditions' => array('Product.category_id' => $cat['CategoryProduct']['id']), 'fields' => 'Product.code'));
                                foreach ($product_name3 as $pn3 => $pv3) {
                                    $product_code[] = $pv3['Product']['code'];
                                }
                                    }
                                }
                        }
                    }
                }
            }//结尾
            $condition['and']['OrderProduct.product_code'] = $product_code;
        }
        $this->set('cate', $cate);

        //品牌
        //$this->Brand->set_locale($this->backend_locale['brand']);
        $bran_sel = $this->Brand->find('all', array('fields' => array('Brand.id', 'Brand.code', 'BrandI18n.name'), 'order' => 'Brand.orderby, Brand.code asc'));
        $this->set('bran_sel', $bran_sel);
        $brand_ids = array();
        if (isset($_REQUEST['pp'])) {
            $brand_ids = explode(',', $_REQUEST['pp']);
            $product_name = $this->Product->find('all', array('conditions' => array('Product.brand_id' => $brand_ids), 'fields' => 'Product.code'));
            $product_bcode = array();
            foreach ($product_name as $pn => $pv) {
                $product_bcode[] = $pv['Product']['code'];
            }
        }
        $this->set('brand_ids', $brand_ids);
        //类目

            $this->CategoryType->set_locale($this->backend_locale);
        $category_type_tree = $this->CategoryType->tree();
        $cats = array();
        foreach ($category_type_tree as $v) {
            $cats[$v['CategoryType']['id']] = $v['CategoryTypeI18n']['name'];
        }
        $this->set('ct', $cats);
        $this->set('category_type_tree', $category_type_tree);

        if (isset($_REQUEST['lm'])) {
            $cats = explode(',', $_REQUEST['lm']);
            $this->set('cat', $cats);
            $product_name = $this->Product->find('all', array('conditions' => array('Product.category_type_id' => $cats), 'fields' => 'Product.code'));
            $product_code = array();
            foreach ($product_name as $pn => $pv) {
                $product_code[] = $pv['Product']['code'];
            }
            $condition['OrderProduct.product_code'] = $product_code;
        }
        if (isset($product_bcode) && isset($product_ccode)) {
            $product_code = array_intersect($product_bcode, $product_ccode);
            $condition['and']['OrderProduct.product_code'] = $product_code;
        } elseif (isset($product_bcode) && !isset($product_ccode)) {
            $condition['and']['OrderProduct.product_code'] = $product_bcode;
        } elseif (!isset($product_bcode) && isset($product_ccode)) {
            $condition['and']['OrderProduct.product_code'] = $product_ccode;
        }
        //付款时间
        $start_date = '';
        if (isset($_REQUEST['st']) && $_REQUEST['st'] != '') {
            $start_date = trim($_REQUEST['st']);
            $condition['Order.payment_time >'] = $start_date.' 00:00:00';
        }
        $this->set('start_date', $start_date);
        $end_date = '';
        if (isset($_REQUEST['ed']) && $_REQUEST['ed'] != '') {
            $end_date = trim($_REQUEST['ed']);
            $condition['Order.payment_time <'] = $end_date.' 23:59:59';
        }
        $this->set('end_date', $end_date);
        $total = $this->OrderProduct->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'Order';
        $page = 1;
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'product_sale_statements','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Order','total' => $total);
        $this->Pagination->init($condition, $parameters, $options);
        $fields = array('Order.id','Order.order_code','Order.payment_time','Order.point_fee','Order.payment_id','Order.discount','Order.consignee','Order.telephone','Order.shipping_status','Order.payment_status','Order.status','Order.created','Order.modified','Order.type','Order.type_id');//需用到的字段

        if (isset($export) && $export) {
            $order_list = $this->OrderProduct->find('all', array('conditions' => $condition, 'order' => 'Order.created DESC'));
        } else {
            $order_list = $this->OrderProduct->find('all', array('conditions' => $condition, 'order' => 'Order.created DESC', 'page' => $page, 'limit' => $rownum));
        }

    //	pr($order_list);die;
        $payment_names = $this->PaymentI18n->find('list', array('fields' => array('PaymentI18n.payment_id', 'PaymentI18n.name'), 'conditions' => array('PaymentI18n.locale' => 'chi')));
        $this->set('payment_names', $payment_names);
        $brand_names = $this->Brand->brand_names();

        $this->CategoryType->set_locale($this->backend_locale);
        $category_typies_tree = $this->CategoryType->tree();
        $category_type_names = array();
        foreach ($category_typies_tree as $v) {
            $category_type_names[$v['CategoryType']['id']] = $v['CategoryTypeI18n']['name'];
        }
        $this->set('category_type_names', $category_type_names);
    //	pr($category_type_names);
        $this->set('brand_names', $brand_names);

        //pr($order_list);die;
        $this->set('order_list', $order_list);//订单列表
        if (isset($export) && $export) {
            $filename = $this->ld['product_sales_reports'].'_'.date('Ymd').'.xls';
            $data = array();
            $data[] = array($this->ld['order_reffer'],$this->ld['order_code'],$this->ld['time_of_payment'],$this->ld['business_code'],$this->ld['product_name'],$this->ld['category'],$this->ld['brand'],$this->ld['sales_volume'],$this->ld['marked_price'],$this->ld['discount_rate'],$this->ld['selling_price'],$this->ld['paymengts']);
            foreach ($order_list as $ok => $ov) {
                $type = isset($order_type_arr[$ov['Order']['type_id']]) ? $order_type_arr[$ov['Order']['type_id']] : $ov['Order']['type_id'];
                if ($ov['Order']['type'] == 'store') {
                    $data[$ok + 1][] = $this->ld['order_store'].' - '.$type;
                } elseif ($ov['Order']['type'] == 'taobao') {
                    $data[$ok + 1][] = $this->ld['taobao'].' - '.$type;
                } elseif ($ov['Order']['type'] == 'ioco') {
                    $data[$ok + 1][] = $this->ld['order_site'].' - '.$type;
                }
                $data[$ok + 1][] = $ov['Order']['order_code'];
                $data[$ok + 1][] = $ov['Order']['payment_time'];
                $data[$ok + 1][] = $ov['OrderProduct']['product_code'];
                $data[$ok + 1][] = $ov['OrderProduct']['product_name'];
                $data[$ok + 1][] = isset($category_type_names[$ov['Product']['category_type_id']]) ? $category_type_names[$ov['Product']['category_type_id']] : '';
                $data[$ok + 1][] = isset($brand_names[$ov['Product']['brand_id']]) ? $brand_names[$ov['Product']['brand_id']] : '';
                $data[$ok + 1][] = ($ov['OrderProduct']['product_quntity'] - $ov['OrderProduct']['refund_quantity']);
                $data[$ok + 1][] = ($ov['OrderProduct']['product_price']) * ($ov['OrderProduct']['product_quntity'] - $ov['OrderProduct']['refund_quantity']);
                $data[$ok + 1][] = @sprintf('%01.2f', ((1 + $ov['OrderProduct']['adjust_fee'] / (($ov['OrderProduct']['product_price']) * ($ov['OrderProduct']['product_quntity'] - $ov['OrderProduct']['refund_quantity']))) * 100)).'%';
                $data[$ok + 1][] = ($ov['OrderProduct']['product_price']) * ($ov['OrderProduct']['product_quntity'] - $ov['OrderProduct']['refund_quantity']) + $ov['OrderProduct']['adjust_fee'];
                $data[$ok + 1][] = isset($payment_names[$ov['Order']['payment_id']]) ? $payment_names[$ov['Order']['payment_id']] : '';
            }
            $this->Phpexcel->output($filename, $data);
            exit;
        }
    }
}
