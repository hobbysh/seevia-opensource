<?php

/**
 *这是一个名为 UploadsController 的控制器
 *后台文章批量上传控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class UploadsController extends AppController
{
    public $name = 'Uploads';
    public $components = array('Pagination','RequestHandler','Phpexcel');
    public $helpers = array('Pagination','Html','Form','Javascript');
    public $uses = array('CategoryType','ProductTypeAttribute','ProductAttribute','Product','ProductI18n','Brand','ProductsCategory','BrandI18n','CategoryProduct','User','UserAddress','RegionI18n','NewsletterList','Article','ArticleI18n','ArticleCategory','CategoryArticle','Resource','Dictionary','Profile','ProfileFiled','ProductGallery','PhotoCategoryGallery','OperatorLog');

    /**
     *显示文章批量上传界面.
     */
    public function index($type = '')
    {
        if ($type == 'A') {
            $this->operator_privilege('articles_upload');
            $this->menu_path = array('root' => '/cms/','sub' => '/articles/');
            $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['manage_articles'],'url' => '/articles/');
            $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
            $flag_code = 'articles_export';
            $categories_tree = $this->CategoryArticle->tree('all', $this->locale);
            $this->set('categories_tree', $categories_tree);
        }
        if ($type == 'M') {
            $this->operator_privilege('newsletter_upload');
            $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
            $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
            $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists/');
            $this->navigations[] = array('name' => $this->ld['batch_upload_user'],'url' => '');
            $flag_code = 'user_export';
        }
        if ($type == 'U') {
            $this->operator_privilege('users_upload');
            $this->menu_path = array('root' => '/crm/','sub' => '/users/');
            $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
            $this->navigations[] = array('name' => $this->ld['batch_upload_user'],'url' => '');
            $flag_code = 'user_export';
        }
        if ($type == 'P') {
            $this->operator_privilege('products_upload');
            $this->menu_path = array('root' => '/product/','sub' => '/products/');
            $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
            $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
            $flag_code = 'product_import';
            $categories_tree = $this->CategoryProduct->tree('P', $this->locale);
            $this->set('categories_tree', $categories_tree);
        }
        if ($type == 'O') {
            $this->operator_privilege('orders_upload');
            $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
            $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '/orders/');
            $this->navigations[] = array('name' => $this->ld['order_bulk_upload_orders'],'url' => '');
            $flag_code = 'order_import';
        }
        $profilefiled_codes = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
        $Resource_info = $this->Resource->getformatcode(array('csv_export_code'), $this->locale, false);//资源库信息
        $this->Profile->set_locale($this->locale);
        if (isset($profilefiled_codes) && !empty($profilefiled_codes)) {
            $this->set('profilefiled_codes', $profilefiled_codes);
        }
        $this->set('Resource_info', $Resource_info);
        $this->set('type', $type);
    }

    //检测上传订单商品  chenfan
    public function check_order_products($data)
    {
        $this->loadModel('Store');
        $this->loadModel('LogisticsCompany');
        $this->loadModel('Warehouse');
        $this->loadModel('Stock');
        $this->loadModel('Outbound');
        $this->loadModel('OutboundProduct');
        $this->loadModel('Order');
        $this->loadModel('OrderProduct');
        $this->loadModel('OrderAction');
        $operator_id = $this->admin['id'];
        //导入订单所有信息数据
        $product_codes = array();
        $payment_names = array();
        $sub_payment_names = array();
        $shipping_names = array();
        $types = array();
        $order_status = array('未付款', '未发货', '已发货');
        $logistics_companys = array();
        $warehouse_codes = array();
        $code_quantity_infos = array();
        $warehouse_code_quantity_infos = array();
        //订单不存在的信息数据
        $without_product_codes = array();//不存在的商品货号
        $without_quantity_product_codes = array();//商品可售不足的商品货号
        $without_warehouse_quantity_product_codes = array();//商品仓库库存不足的商品货号
        $without_payment_names = array();//不存在的支付方式
        $without_sub_payment_names = array();//不存在的二级支付方式
        $without_shipping_names = array();//不存的配送方式
        $without_types = array();//不存在的订单来源
        $without_order_status = array();//不存在的订单状态
        $without_logistics_companys = array();//不存在的物流公司
        $without_warehouse_codes = array();//不存在的仓库code
        $without_types_permission = array();//没有权限的订单来源
        $without_warehouse_permission = array();//没有权限的仓库code
        foreach ($data as $k => $v) {
            if ($k == 0) {
                continue;
            }
            $data[$k]['product_quntity'] = (int) ($v['product_quntity']);
            //统计订单商品库存 判断可售数量 判断仓库库存
            if (isset($v['product_code']) && $v['product_code'] != '') {
                if (!isset($code_quantity_info[$v['product_code']])) {
                    $code_quantity_infos[$v['product_code']] = $data[$k]['product_quntity'];
                } else {
                    $code_quantity_infos[$v['product_code']] += $data[$k]['product_quntity'];
                }
                if ($v['status'] == '已发货') {
                    if (!isset($warehouse_code_quantity_infos[$v['warehouse_code']][$v['product_code']])) {
                        $warehouse_code_quantity_infos[$v['warehouse_code']][$v['product_code']] = $data[$k]['product_quntity'];
                    } else {
                        $warehouse_code_quantity_infos[$v['warehouse_code']][$v['product_code']] += $data[$k]['product_quntity'];
                    }
                }
            }
            $data[$k]['product_price'] = (int) ($v['product_price']);
            $data[$k]['subtotal'] = (int) ($v['subtotal']);
            $data[$k]['subtotal'] = (int) ($v['subtotal']);
            $data[$k]['money_paid'] = (int) ($v['money_paid']);
            $data[$k]['payment_fee'] = (int) ($v['payment_fee']);
            $data[$k]['shipping_fee'] = (int) ($v['shipping_fee']);
            if (isset($v['product_code']) && $v['product_code'] != '' && !in_array($v['product_code'], $product_codes)) {
                $product_codes[] = $v['product_code'];
            }
            if (isset($v['payment_name']) && $v['payment_name'] != '' && !in_array($v['payment_name'], $payment_names)) {
                $payment_names[] = $v['payment_name'];
            }
//	        if (isset($v['sub_payment_name']) && $v['sub_payment_name'] != "" && !in_array($v['sub_payment_name'], $payment_names))
//	        $sub_payment_names[] = $v['payment_name'].'-'.$v['sub_payment_name'];
            if (isset($v['shipping_name']) && $v['shipping_name'] != '' && !in_array($v['shipping_name'], $shipping_names)) {
                $shipping_names[] = $v['shipping_name'];
            }
            if (isset($v['type_id']) && $v['type_id'] != '' && !in_array($v['type_id'], $types)) {
                $types[] = $v['type_id'];
            }
//	        if ((isset($v['status']) && $v['status'] != "" && !in_array($v['status'], $order_status)) && !in_array($v['status'], $without_order_status))
//	        $without_order_status[] = $v['status'];
            if (isset($v['logistics_company_id']) && $v['logistics_company_id'] != '' && !in_array($v['logistics_company_id'], $logistics_companys)) {
                $logistics_companys[] = $v['logistics_company_id'];
            }
            if (isset($v['warehouse_code']) && $v['warehouse_code'] != '' && !in_array($v['warehouse_code'], $warehouse_codes)) {
                $warehouse_codes[] = $v['warehouse_code'];
            }
        }
        $msg = '';
        //判断类型是否存在
        $taobao_shop_arr = array();
        $this->loadModel('TaobaoShop');
        $taobao_shop_arr = $this->TaobaoShop->find('list', array('conditions' => array('status' => 1), 'order' => 'orderby asc', 'fields' => array('nick')));
        $store_arr = array();
        $store_permissions = array();
        $this->Store->set_locale($this->backend_locale);
        $stores = $this->Store->find('all', array('conditions' => array('status' => 1), 'fields' => array('store_sn', 'Store.operator_id', 'StoreI18n.name'), 'order' => 'orderby'));
        if (!empty($stores)) {
            foreach ($stores as $sk => $sv) {
                $store_arr[] = $sv['StoreI18n']['name'];
                $store_permissions[$sv['StoreI18n']['name']] = explode(',', $sv['Store']['operator_id']);
            }
        }
        if (!empty($types)) {
            foreach ($types as $ty) {
                //判断格式
                if ($ty == '本站' || $ty == '门店' || $ty == '批发' || in_array($ty, $taobao_shop_arr) || in_array($ty, $store_arr)) {
                    //$ty == "网站" ||
                    //判断权限//$ty == "网站" ||
                    if ($ty == '本站' || $ty == '门店' || in_array($ty, $taobao_shop_arr) || !isset($store_permissions[$ty]) || !in_array($operator_id, $store_permissions[$ty])) {
                        $without_types_permission[] = $ty;
                    }
                    continue;
                } elseif ($ty != '网站') {
                    $without_types[] = $ty;
                }
            }
        }
        //判断商品是否存在
        if (empty($product_codes)) {
            $msg .= '商品货号不能为空';
        } else {
            //判断商品是否存在  判断商品可售数是否充足
            foreach ($product_codes as $pro) {
                $product = $this->Product->find('first', array('conditions' => array('Product.code' => $pro), 'fields' => 'Product.id,Product.code,Product.quantity'));
                if (empty($product)) {
                    $without_product_codes[] = $pro;
                } else {
                    if ($product['Product']['quantity'] < $code_quantity_infos[$product['Product']['code']]) {
                        $without_quantity_product_codes[] = $pro;
                    }
                }
            }
        }
        //判断支付方式是否存在
        if (empty($payment_names)) {
            $msg .= '支付方式不能为空';
        } else {
            foreach ($payment_names as $pay) {
                $this->Payment->set_locale($this->backend_locale);
                $payment = $this->Payment->find('first', array('conditions' => array('PaymentI18n.name' => $pay, 'Payment.status' => 1), 'fields' => 'Payment.id'));
                if (empty($payment)) {
                    $without_payment_names[] = $pay;
                }
            }
        }
        //二级支付方式是否存在
        if (!empty($sub_payment_names)) {
            foreach ($sub_payment_names as $sub_pay) {
                $pay = explode('-', $sub_pay);
                if ($pay[0] == '') {
                    $without_sub_payment_names[] = $pay[1];
                    continue;
                }
                $this->Payment->set_locale($this->backend_locale);
                $payment = $this->Payment->find('first', array('conditions' => array('PaymentI18n.name' => $pay), 'fields' => 'Payment.config,Payment.id'));
                $x = $payment['Payment']['config'];
                if ($x != '') {
                    $x = unserialize($x);
                    if (isset($x['bank']['bb'])) {
                        unset($x['bank']['bb']);
                    }
                    $a = true;
                    foreach ($x['bank'] as $v) {
                        if (trim($v) == trim($pay[1])) {
                            $a = false;
                        }
                    }
                    if ($a) {
                        $without_sub_payment_names[] = $pay[1];
                    }
                }
            }
        }
        //判断配送方式是否存在
        if (empty($shipping_names)) {
            $msg .= '配送方式不能为空';
        } else {
            foreach ($shipping_names as $ship) {
                $this->Shipping->set_locale($this->backend_locale);
                $shipping = $this->Shipping->find('first', array('conditions' => array('ShippingI18n.name' => $ship, 'Shipping.status' => 1), 'fields' => 'Shipping.id'));
                if (empty($shipping)) {
                    $without_shipping_names[] = $ship;
                }
            }
        }
        //判断物流公司是否存在
        if (!empty($logistics_companys)) {
            foreach ($logistics_companys as $lc) {
                $logistics_company = $this->LogisticsCompany->find('first', array('conditions' => array('LogisticsCompany.id' => $lc), 'fields' => 'LogisticsCompany.id'));
                if (empty($logistics_company)) {
                    $without_logistics_companys[] = $lc;
                }
            }
        }
        //判断仓库是否存在
        if (in_array('APP-WAREHOUSE', $this->apps['codes'])) {
            if (!empty($warehouse_codes)) {
                foreach ($warehouse_codes as $wa) {
                    $warehouse = $this->Warehouse->find('first', array('conditions' => array('Warehouse.code' => $wa), 'fields' => array('Warehouse.code', 'Warehouse.warehouse_name', 'Warehouse.operator_id')));
                    if (empty($warehouse)) {
                        $without_warehouse_codes[] = $wa;
                    } else {
                        //判断权限 仓库
                    $operator_ids = explode(',', $warehouse['Warehouse']['operator_id']);
                        if (!in_array($operator_id, $operator_ids)) {
                            $without_warehouse_permission[] = $wa;
                        } else {
                            //判断商品仓库库存
                        foreach ($warehouse_code_quantity_infos[$wa] as $wk => $wpq) {
                            if (in_array($wk, $without_product_codes)) {
                                continue;
                            }
                            $stock = $this->Stock->find('first', array('conditions' => array('Stock.warehouse_code' => $wa, 'Stock.product_code' => $wk)));
                            if (empty($stock) || ((($stock['Stock']['quantity'] - $wpq) < 0))) {
                                if (isset($without_warehouse_quantity_product_codes[$wa])) {
                                    $without_warehouse_quantity_product_codes[$wa] .= ','.$wk;
                                } else {
                                    $without_warehouse_quantity_product_codes[$wa] = $wk;
                                }
                            }
                        }
                        }
                    }
                }
            }
        }
        if (!empty($without_types)) {
            $msg_types = implode(',', $without_types);
            $msg .= $msg_types.' 订单来源不存在';
        }
        if (!empty($without_types_permission)) {
            $msg_permission_types = implode(',', $without_types_permission);
            $msg .= $msg_permission_types.' 订单来源无权限';
        }
        if (!empty($without_product_codes)) {
            $msg_codes = implode(',', $without_product_codes);
            $msg .= $msg_codes.' 货号不存在';
        }
        if (!empty($without_quantity_product_codes)) {
            $msg_codes = implode(',', $without_quantity_product_codes);
            $msg .= $msg_codes.' 商品可售数不足';
        }
        if (!empty($without_payment_names)) {
            $msg_pays = implode(',', $without_payment_names);
            $msg .= $msg_pays.' 支付方式不存在';
        }
        if (!empty($without_sub_payment_names)) {
            $msg_pays = implode(',', $without_sub_payment_names);
            $msg .= $msg_pays.'二级支付方式不存在';
        }
        if (!empty($without_shipping_names)) {
            $msg_ships = implode(',', $without_shipping_names);
            $msg .= $msg_ships.' 配送方式不存在';
        }
        if (!empty($without_order_status)) {
            $msg_status = implode(',', $without_order_status);
            $msg .= $msg_status.' 订单状态不正确';
        }
        if (!empty($without_logistics_companys)) {
            $msg_lc_name = implode(',', $without_logistics_companys);
            $msg .= $msg_lc_name.' 物流公司不存在';
        }
        if (in_array('APP-WAREHOUSE', $this->apps['codes'])) {
            if (!empty($without_warehouse_codes)) {
                $msg_warehouse = implode(',', $without_warehouse_codes);
                $msg .= $msg_warehouse.' 仓库不存在';
            }
            if (!empty($without_warehouse_permission)) {
                $msg_warehouse_permission = implode(',', $without_warehouse_permission);
                $msg .= $msg_warehouse_permission.' 仓库无权限';
            }
            if (!empty($without_warehouse_quantity_product_codes)) {
                foreach ($without_warehouse_quantity_product_codes as $wk => $wp) {
                    $msg .= '仓库 '.$wk.':'.$wp.' ';
                }
                $msg .= ' 库存不足';
            }
        }
        if ($msg != '') {
            $msg .= '请重新上传！';
        }

        return $msg;
    }

    /**
     *批量新增预览后的订单.
     */
    public function batch_add_orders()
    {
        if (!empty($this->data)) {
            $checkbox_arr = $_REQUEST['checkbox'];
            $tmp = array();
            $product_codes = array();

            $store_arr = array();
            $stores = $this->Store->find('all', array('conditions' => array('status' => 1)));
            foreach ($stores as $sk => $sv) {
                $store_arr[$sv['StoreI18n']['name']] = $sv['Store']['store_sn'];
            }

            foreach ($this->data as $key => $v) {
                if (!in_array($key, $checkbox_arr)) {
                    unset($this->data[$key]);
                    continue;
                }
                if ($this->data[$key]['status'] == '') {
                    $this->data[$key]['status'] = $status;
                }
                $status = $this->data[$key]['status'];
            }
            $msg = $this->check_order_products($this->data);
            if ($msg != '') {
                $msg = str_replace("\n",  '\\n',  $msg);
                $msg = str_replace("\r",  '\\r',  $msg);
                echo "<meta charset='utf-8' /><script>alert('".$msg."');window.location.href='/admin/upload_orders/';</script>";
                die();
            }
            foreach ($this->data as $key => $data) {
                if (!in_array($key, $checkbox_arr)) {
                    continue;
                }
                //判断商品是否存在
                $this->Product->set_locale($backend_locale);
            //	$product=$this->Product->find('first',array('conditions'=>array('Product.code'=>$data['product_code'],'ProductI18n.name'=>$data['product_name']),"fields"=>"Product.id"));
                $product = $this->Product->find('first', array('conditions' => array('Product.code' => $data['product_code']), 'fields' => 'Product.id'));
                if (empty($product)) {
                    $product_codes[] = $data['product_code'];
                    continue;
                }
                $order = array();
                if ((!empty($tmp) && empty($data['consignee'])) || (!empty($tmp) && $data['consignee'] == $tmp['consignee'])) {
                    $order_id = $this->Order->find('first', array('conditions' => array('Order.order_code' => $tmp['order_code']), 'fields' => 'Order.subtotal,Order.total,Order.id'));
                    $order = $tmp;
                    $order['id'] = $order_id['Order']['id'];
                    $order['subtotal'] += $data['product_quntity'] * $data['product_price'];
                    $order['total'] += $data['product_quntity'] * $data['product_price'];
                } else {
                    if ($data['mobile'] != '' || $data['email'] != '') {
                        $user = array();
                        $uId = '';
                        $aId = '';
                        if ($data['mobile'] == '') {
                            $user['user_sn'] = $data['email'];
                        } else {
                            $user['user_sn'] = $data['mobile'];
                        }
                        $user['first_name'] = $data['consignee'];
                        $user['name'] = $data['consignee'];
                        //判断用户是否存在
                        $info = $this->User->find('first', array('conditions' => array('User.user_sn' => $user['user_sn'])));
                        if (!empty($info)) {
                            $user['id'] = $info['User']['id'];
                            $uId = $info['User']['id'];
                        }
                        $user['password'] = md5('123456');
                        $user['email'] = $data['email'];
                        $user['mobile'] = $data['mobile'];
                        $user['sex'] = 0;
                        $this->User->saveAll($user);
                        if (empty($info)) {
                            $uId = $this->User->id;
                        }
                        $user_address = array();
                        $user_address['user_id'] = $uId;
                        $user_address['consignee'] = $data['consignee'];
                        $user_address['email'] = $data['email'];
                        $user_address['mobile'] = $data['mobile'];
                        //获取区域ID
                        $order_country_id = '';
                        $order_province_id = '';
                        $order_city_id = '';
                        $country = $this->RegionI18n->find('first', array('conditions' => array('RegionI18n.locale' => $this->locale, 'RegionI18n.name' => $data['country']), 'fields' => 'RegionI18n.region_id'));
                        if (!empty($country)) {
                            $order_country_id = $country['RegionI18n']['region_id'];
                        }
                        $province = $this->RegionI18n->find('first', array('conditions' => array('RegionI18n.locale' => $this->locale, 'RegionI18n.name' => $data['province']), 'fields' => 'RegionI18n.region_id'));
                        if (!empty($province)) {
                            $order_province_id = $province['RegionI18n']['region_id'];
                        }
                        $city = $this->RegionI18n->find('first', array('conditions' => array('RegionI18n.locale' => $this->locale, 'RegionI18n.name' => $data['city']), 'fields' => 'RegionI18n.region_id'));
                        if (!empty($city)) {
                            $order_city_id = $city['RegionI18n']['region_id'];
                        }
                        $user_address['country'] = $order_country_id;
                        $user_address['province'] = $order_province_id;
                        $user_address['city'] = $order_city_id;
                        $user_address['address'] = $data['address'];
                        $this->UserAddress->saveAll($user_address);
                        $aId = $this->UserAddress->id;
                        $this->User->updateAll(array('User.address_id' => $aId), array('User.id' => $uId));
                    }
                    if (isset($uId) && $uId != '') {
                        $order['user_id'] = $uId;
                    }
                    $order['id'] = '';
                    $order['order_code'] = $this->get_order_code();
                    $order['operator_id'] = $this->admin['id'];
                    $order['order_locale'] = $this->backend_locale;
                    if ($data['type_id'] != '') {
                        if (isset($store_arr[$data['type_id']])) {
                            $order['type_id'] = $store_arr[$data['type_id']];
                        } else {
                            $order['type_id'] = $data['type_id'];
                        }
                    }
                    if (!empty($data['created'])) {
                        $order['created'] = $data['created'];
                    }
                    $order['subtotal'] = $data['product_quntity'] * $data['product_price'];
                    $order['total'] = $data['subtotal'];
                    if ($data['payment_fee'] != '') {
                        $order['payment_fee'] = $data['payment_fee'];
                        $order['total'] += $data['payment_fee'];
                    }
                    $order['money_paid'] = $data['money_paid'];
                    $order['payment_name'] = $data['payment_name'];
                    if (isset($data['sub_payment_name'])) {
                        $order['sub_pay'] = isset($data['sub_payment_name']) ? $data['sub_payment_name'] : '';
                    }
                    $this->Payment->set_locale($this->backend_locale);
                    $payment = $this->Payment->find('first', array('conditions' => array('PaymentI18n.name' => $data['payment_name']), 'fields' => 'Payment.id'));
                    if (isset($payment['Payment']['id'])) {
                        $order['payment_id'] = $payment['Payment']['id'];
                    } else {
                        $order['payment_id'] = '0';
                    }
                    $order['shipping_name'] = $data['shipping_name'];
                    $this->Shipping->set_locale($this->backend_locale);
                    $shipping = $this->Shipping->find('first', array('conditions' => array('ShippingI18n.name' => $data['shipping_name']), 'fields' => 'Shipping.id'));
                    if (isset($shipping['Shipping']['id'])) {
                        $order['shipping_id'] = $shipping['Shipping']['id'];
                    } else {
                        $order['shipping_id'] = '0';
                    }
                    if ($data['shipping_fee'] != '') {
                        $order['shipping_fee'] = $data['shipping_fee'];
                        $order['total'] += $data['shipping_fee'];
                    }
                    $order['consignee'] = $data['consignee'];
                    $order['mobile'] = $data['mobile'];
                    $order['note'] = $data['note'];
                    $order['email'] = $data['email'];
                    $order['country'] = $data['country'];
                    $order['province'] = $data['province'];
                    $order['city'] = $data['city'];
                    $order['address'] = $data['address'];
                    $order['telephone'] = $data['telephone'];
                    $order['invoice_payee'] = $data['invoice_payee'];
                    $order['invoice_type'] = $data['invoice_type'];
                    //$order['payment_status']=$data['payment_status'];
                    $order['status'] = 1;
                    if ($data['status'] == '未发货') {
                        //已付款未发货
                        $order['payment_status'] = 2;
                        $order['shipping_status'] = 0;
                        //做库存处理
                    } elseif ($data['status'] == '已发货') {
                        //已付款已发货
                        $order['payment_status'] = 2;
                        $order['shipping_status'] = 1;
                        if (!empty($data['logistics_company'])) {
                            $logistics_company = $this->LogisticsCompany->find('first', array('conditions' => array('LogisticsCompany.id' => $data['logistics_company_id']), 'fields' => 'LogisticsCompany.id'));
                            if (empty($logistics_company)) {
                                $submsg = '找不到物流公司:'.$data['logistics_company'];
                                echo "<meta charset='utf-8' /><script>alert('".$submsg."');window.location.href='/admin/upload_orders/'</script>";
                            }
                        }
                        $order['logistics_company_id'] = isset($logistics_company['LogisticsCompany']['id']) ? $logistics_company['LogisticsCompany']['id'] : 0;
                        $order['invoice_no'] = $data['invoice_type'];
                    } else {
                        $order['payment_status'] = 0;
                        $order['shipping_status'] = 0;
                    }
                    $tmp = $order;
                }
                $this->Order->save($order);
                $order_id = $this->Order->id;
                $order_product = array();
                $order_product['id'] = '';
                $order_product['order_id'] = $order_id;
                $order_product['product_id'] = $product['Product']['id'];
                $order_product['product_code'] = $data['product_code'];
                $order_product['product_name'] = $data['product_name'];
                $order_product['product_quntity'] = $data['product_quntity'];
                $order_product['product_price'] = $data['product_price'];
                $this->OrderProduct->saveAll($order_product);
                $order_info = $this->Order->findbyid($order_id);
                $user_id = $order_info['Order']['user_id'];
                $shipping_status = $order_info['Order']['shipping_status'];
                $order_status = $order_info['Order']['status'];
                $payment_status = $order_info['Order']['payment_status'];
                $operation_notes = '批量新增订单';
                $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, $shipping_status, $operation_notes);
                //已付款 未发货的商品库存处理  付款时
                //下单
                if (($order['payment_status'] == 2 && isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 0) || (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 1)) {
                    $order_product_info = $this->Product->find('first', array('conditions' => array('Product.code' => $data['product_code']), 'recursive' => -1));
                    if (!empty($order_product_info)) {
                        $order_product_info['Product']['frozen_quantity'] = $order_product_info['Product']['frozen_quantity'] + $data['product_quntity'];
                        $order_product_info['Product']['quantity'] = $order_product_info['Product']['quantity'] - $data['product_quntity'];
                            //下架处理
                            if ($order_product_info['Product']['quantity'] <= 0) {
                                $order_product_info['Product']['forsale'] = 0;
                            }
                        $this->Product->save($order_product_info);
                    }
                }
                //直接发货的
                if ($order['shipping_status'] == 1) {
                    if (in_array('APP-WAREHOUSE', $this->apps['codes'])) {
                        if (empty($data['warehouse_code']) && isset($apps['Applications']['APP-WAREHOUSE']['configs']['APP-WAREHOUSE-ALLOW-NO']) && $apps['Applications']['APP-WAREHOUSE']['configs']['APP-WAREHOUSE-ALLOW-NO'] == 0) {
                            $msg = '已发货订单仓库代码不能为空';
                        }
                        if (!empty($data['warehouse_code']) && $data['product_code'] != '') {
                            $outbound_infos[$data['warehouse_code']][$key]['OutboundProduct']['product_code'] = $data['product_code'];
                            $outbound_infos[$data['warehouse_code']][$key]['OutboundProduct']['order_code'] = $order['order_code'];
                            $outbound_infos[$data['warehouse_code']][$key]['OutboundProduct']['quantity'] = $data['product_quntity'];
                            //查询该仓库该商品的库存
                            $stock_info = $this->Stock->find('first', array('conditions' => array('Stock.warehouse_code' => $data['warehouse_code'], 'Stock.product_code' => $data['product_code']), 'fields' => 'Stock.quantity'));
                            $outbound_infos[$data['warehouse_code']][$key]['OutboundProduct']['before_out'] = $stock_info['Stock']['quantity'];
                        }
                    }
                }
            }
            //出库操作
            if (isset($outbound_infos) && !empty($outbound_infos)) {
                $this->outbound_action($outbound_infos);
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'批量上传订单', $this->admin['id']);
            }
            if (!empty($product_codes)) {
                $msg_codes = implode(',', $product_codes);
                $msg = $msg_codes.' 货号不存在';
            } else {
                $msg = $this->ld['import_success'];
            }
        //	$msg=$this->ld['import_success'];
           echo "<meta charset='utf-8' /><script>alert('".$msg."');window.location.href='/admin/upload_orders/'</script>";
        }
    }

    //订单有出库进行出库处理
    public function outbound_action($outbound_infos)
    {
        $operator_id = $this->admin['id'];
        $this->Product->hasOne = array();
        $this->Product->hasMany = array();
        $this->Stock->hasOne = array();
        foreach ($outbound_infos as $code => $out) {
            $total_quantity = 0;
            //出货日志
            $info['Outbound']['created_operator_id'] = $operator_id;
            $info['Outbound']['reason'] = '订单导入出库';
            $info['Outbound']['warehouse_code'] = $code;
            $info['Outbound']['batch_id'] = $this->get_batch_id();
            $info['Outbound']['outbound_type'] = 0;
            $this->Outbound->saveAll($info);
            $outbound_id = $this->Outbound->getLastInsertId();
            foreach ($out as $key => $data) {
                //日志明细
                $data['OutboundProduct']['outbound_id'] = $outbound_id;
                $this->OutboundProduct->saveAll($data);
                //库存表
                $stock = $this->Stock->find('first', array('conditions' => array('Stock.warehouse_code' => $code, 'Stock.product_code' => $data['OutboundProduct']['product_code'])));
                if (empty($stock)) {
                    echo '<meta charset="utf-8"><script>alert("商品 '.$data['OutboundProduct']['product_code'].' 不在此仓库，无法出库")</script>';
                    die;
                } else {
                    $stock['Stock']['quantity'] -= $data['OutboundProduct']['quantity'];
                    if ($stock['Stock']['quantity'] < 0) {
                        echo '<meta charset="utf-8"><script>alert("商品 '.$data['OutboundProduct']['product_code'].' 库存不足，无法出库")</script>';
                        die;
                    }
                    $this->Stock->save($stock);
                }
                //$this->Outbound->updateAll(array('Outbound.stock_price'=>$data['OutboundProduct']['shop_price']*$data['OutboundProduct']['quantity']),array('id'=>$outbound_id));
                //产品表 库存表中所有该货号数量总和
                $this->Stock->hasOne = array();
                $total_stock = $this->Stock->find('all', array('fields' => array('SUM(Stock.quantity) AS total_stock'), 'conditions' => array('Stock.product_code' => $data['OutboundProduct']['product_code'])));
                if (!in_array($data['OutboundProduct']['product_code'], $this->get_xu_list())) {
                    $this->Product->up_under_foz($total_stock[0][0]['total_stock'], $data['OutboundProduct']['product_code']);
                }
                //总数 总价
                $total_quantity += $data['OutboundProduct']['quantity'];
            }
            //出库日志
            $this->Outbound->updateAll(array('Outbound.quantity' => $total_quantity), array('id' => $outbound_id));
        }
    }

    public function get_batch_id()
    {
        $y = date('Y-m-d', time());
        $y .= ' 00:00:00';
        $this->Outbound->hasOne = array();
        $x = $this->Outbound->find('count', array('conditions' => array('Outbound.created >' => $y)));
        if ($x > 9999) {
            return false;
        }
        $x = str_pad($x, 4, '0', STR_PAD_LEFT);
        $y = date('Ymd', time());

        return $y.'02'.$x;
    }

    /**
     *批量上传预览.
     */
    public function bulk_upload_preview($type = '')
    {
        set_time_limit(300);
        $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
        if ($type == 'A') {
            $this->menu_path = array('root' => '/cms/','sub' => '/articles/');
            $this->navigations[] = array('name' => $this->ld['manage_articles'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
            $flag_code = 'articles_export';
            $this->set('extension_code', array('' => $this->ld['real_article'], 'virtual_card' => $this->ld['virtual_cards']));
        }
        if ($type == 'M') {
            $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
            $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
            $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists/');
            $this->navigations[] = array('name' => $this->ld['batch_upload_user'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
            $flag_code = 'newsletter_export';
            $this->set('extension_code', array('' => $this->ld['real_product'], 'virtual_card' => $this->ld['virtual_cards']));
        }
        if ($type == 'U') {
            $this->menu_path = array('root' => '/crm/','sub' => '/users/');
            $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
            $this->navigations[] = array('name' => $this->ld['batch_upload_user'],'url' => '/upload_users/');
            $this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
            $flag_code = 'user_export';
        }
        if ($type == 'P') {
            $this->menu_path = array('root' => '/product/','sub' => '/products/');
            $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
            $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
            $flag_code = 'product_import';
            if (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != '') {
                $this->set('category_id', $_REQUEST['category_id']);
            }
            $this->set('extension_code', array('' => $this->ld['real_product'], 'virtual_card' => $this->ld['virtual_cards']));
        }
        if ($type == 'O') {
            $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
            $this->navigations[] = array('name' => $this->ld['order_bulk_upload_orders'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '/orders/');
            $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
            $flag_code = 'order_import';
            $this->set('extension_code', array('' => $this->ld['real_product'], 'virtual_card' => $this->ld['virtual_cards']));
        }
        if (!empty($_FILES['file'])) {
            if ($_FILES['file']['error'] > 0) {
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/uploads/index/".$type."'</script>";
            } elseif (empty($_POST['category_id']) && $type == 'A') {
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['select_article_category']."');window.location.href='/admin/uploads/index/A'</script>";
                $this->set('category_id', $_POST['category_id']);
            } elseif (empty($_POST['category_id']) && $type == 'P') {
                echo "<script>alert('".$this->ld['select_product_category']."');window.location.href='/admin/uploads/index/P'</script>";
                $this->set('category_id', $_POST['category_id']);
            } else {
                $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                if ($type == 'P') {
                    $attr_code_arr = array();
                    $attr_code_arr = $this->ProductTypeAttribute->find('list', array('conditions' => array('ProductTypeAttribute.product_type_id' => 0), 'fields' => 'ProductTypeAttribute.code'));
                    $show_attr_code_arr = array();
                }
                $this->Profile->set_locale($this->locale);
                $profile_info = $this->Profile->find('all', array('conditions' => array('Profile.code' => $flag_code, 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
                $key_arr = array();
                foreach ($profile_info as $k => $v) {
                    $fields_k = explode('.', $v['ProfileFiled']['code']);
                    $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                }
                $csv_export_code = 'gb2312';
                $i = 0;
                while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                    if ($i == 0) {
                        $check_row = $row[0];
                        $row_count = count($row);
                        if ($type == 'P') {
                            foreach ($row as $k => $v) {
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
                        } else {
                            $check_row = iconv('GB2312', 'UTF-8', $check_row);
                            $num_count = count($profile_info);
                            if ($row_count > $num_count || $check_row != $profile_info[0]['ProfileFiled']['description']) {
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('上传文件格式不标准');window.location.href='/admin/upload_articles/'</script>";
                            }
                        }
                        ++$i;
                    }
                    $temp = array();
                    if ($type == 'P') {
                        foreach ($row as $k => $v) {
                            if (isset($key_arr[$k]) && !empty($key_arr[$k])) {
                                $temp[$key_arr[$k]] = $v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                            } else {
                                foreach ($attr_code_arr as $atv) {
                                    if (strcasecmp($v, $atv) == 0) {
                                        $a_pegva = 1;
                                    }
                                }
                                if (isset($a_pegva) && $a_pegva == 1) {
                                    $temp[$v] = $v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                    unset($a_pegva);
                                }
                            }
                        }
                    } else {
                        foreach ($row as $k => $v) {
                            $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                        }
                    }
                    if (!isset($temp) || empty($temp)) {
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/uploads/index/".$type."'</script>";
                    }
                    //$temp['content']=htmlspecialchars($temp['content']);
                    $data[] = $temp;
                }
                fclose($handle);
                if ($type == 'P') {
                    $check_row = iconv('GB2312', 'UTF-8', $check_row);
                    $num_count = count($profile_info) + count($show_attr_code_arr);
                    if ($row_count > $num_count || $check_row != $profile_info[0]['ProfileFiled']['description']) {
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('上传文件格式不标准');window.location.href='/admin/uploads/index/".$type."'</script>";
                    }
                    $this->set('attr_code_arr', $show_attr_code_arr);
                    $this->set('category_id', $_POST['category_id']);
                }
                if ($type == 'O') {
                    foreach ($data as $k => $v) {
                        if ($k == 0) {
                            continue;
                        }
                        if ($v['status'] == '') {
                            $data[$k]['status'] = $data[$k - 1]['status'];
                        }
                    }
                    $msg = $this->check_order_products($data);
                    $this->set('msg', $msg);
                }
                $this->set('profile_info', $profile_info);
                $this->set('uploads_list', $data);
                $this->set('type', $type);
                if ($type == 'U') {
                    $i = 0;
                    $discount = array();
                    $info = array();
                    foreach ($data as $k => $v) {
                        if ($k == 0) {
                            continue;
                        }
                        if ($v['mobile'] != '') {
                            $info = $this->User->find('first', array('conditions' => array('User.mobile' => $v['mobile'])));
                        }
                        if (empty($info)) {
                            $info = $this->User->find('first', array('conditions' => array('User.email' => $v['email'])));
                        }
                        if (empty($info)) {
                            $info = $this->User->find('first', array('conditions' => array('User.name' => $v['name'])));
                        }
                        if (!empty($info)) {
                            $discount[$k] = 'discount';
                            ++$i;
                        }
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('导入数据有".$i."条与本站重复');</script>";
                    $this->set('discount', $discount);
                    $this->set('extension_code', array('' => $this->ld['real_product'], 'virtual_card' => $this->ld['virtual_cards']));
                }
            }
        }
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

    /**
     *批量新增预览后的文章.
     */
    public function batch_add_articles()
    {
        if (!empty($this->data)) {
            $category_id = $_POST['category_id'];
            $checkbox_arr = $_REQUEST['checkbox'];
            $this->Article->hasOne = array();
            $this->Article->hasMany = array();
            foreach ($this->data as $key => $data) {
                if (!in_array($key, $checkbox_arr)) {
                    continue;
                }
                $ArticleI18n['title'] = $data['title'];
                $ArticleI18n['content'] = $data['content'];
                $ArticleI18n['meta_keywords'] = $data['meta_keywords'];
                $ArticleI18n['meta_description'] = $data['meta_description'];
                $ArticleI18n['author'] = $data['author'];
                $Article = empty($article_info['Article']) ? array() : $article_info['Article'];
                $Article['category_id'] = $category_id;
                $Article['type'] = empty($data['type']) ? G : $data['type'];
                $Article['importance'] = empty($data['importance']) ? 1 : $data['importance'];
                $Article['status'] = empty($data['status']) ? 1 : $data['status'];
                $Article['front'] = empty($data['front']) ? 1 : $data['front'];
                $Article['comment'] = empty($data['comment']) ? 1 : $data['comment'];
                $Article['recommand'] = empty($data['recommand']) ? 1 : $data['recommand'];
                if (empty($Article['id'])) {
                    $max_article = $this->Article->find('', '', 'Article.id DESC');
                    $max_id = $max_article['Article']['id'] + 1;
                }
                if (empty($Article['orderby'])) {
                    $Article['orderby'] = 50;
                }
                if (empty($Article['id'])) {
                    $this->Article->saveAll(array('Article' => $Article));
                    $id = $this->Article->id;
                } else {
                    $this->Article->save(array('Article' => $Article));
                    $id = $Article['id'];
                }
                $Article['id'] = $id;
                if (!empty($Article['id'])) {
                    $ArticleI18n['article_id'] = $id;
                    if (is_array($this->backend_locales)) {
                        foreach ($this->backend_locales as $k => $v) {
                            $ArticleI18n = $this->ArticleI18n->find('first', array('conditions' => array('locale' => $v['Language']['locale'], 'article_id' => $id)));
                            $ArticleI18n = $ArticleI18n['ArticleI18n'];
                            $ArticleI18n['title'] = $data['title'];
                            $ArticleI18n['content'] = $data['content'];
                            $ArticleI18n['meta_keywords'] = $data['meta_keywords'];
                            $ArticleI18n['meta_description'] = $data['meta_description'];
                            $ArticleI18n['author'] = $data['author'];
                            $ArticleI18n['article_id'] = $id;
                            $ArticleI18n['locale'] = $v['Language']['locale'];
                            $ArticleI18n['img01'] = $data['img01'];
                            $ArticleI18n['img02'] = $data['img02'];
                            $ArticleI18n['file_url'] = $data['file_url'];
                            if (!empty($ArticleI18n['id'])) {
                                $this->ArticleI18n->save($ArticleI18n);
                            } else {
                                $this->ArticleI18n->saveAll($ArticleI18n);
                            }
                        }
                    }
                }
            }
               //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['bulk_upload_article'], $this->admin['id']);
            }
            $this->redirect('/articles/');
        }
    }

    /**
     *批量新增预览后的会员.
     */
    public function batch_add_users()
    {
        if (!empty($this->data)) {
            $checkbox_arr = $_REQUEST['checkbox'];
            foreach ($this->data as $key => $data) {
                if (!in_array($key, $checkbox_arr)) {
                    continue;
                }
                if ($data['NewsletterList']['mobile'] == '' && $data['NewsletterList']['email'] == '') {
                    continue;
                }
                $this->NewsletterList->saveAll($data['NewsletterList']);
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'批量上传会员', $this->admin['id']);
            }
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['import_success']."');window.location.href='/admin/newsletter_lists/'</script>";
        }
    }

    /**
     *批量新增预览后的会员.
     */
    public function batch_add_user()
    {
        if (!empty($this->data)) {
            $checkbox_arr = $_REQUEST['checkbox'];
            $i = 0;
            $j = 0;
            foreach ($this->data as $key => $data) {
                if (!in_array($key, $checkbox_arr)) {
                    continue;
                }
                if ($data['email'] == '') {
                    continue;
                }
                $info = array();
                if ($data['mobile'] != '') {
                    $info = $this->User->find('first', array('conditions' => array('User.mobile' => $data['mobile'])));
                }
                if (empty($info)) {
                    $info = $this->User->find('first', array('conditions' => array('User.email' => $data['email'])));
                }
                if (empty($info)) {
                    $info = $this->User->find('first', array('conditions' => array('User.name' => $data['name'])));
                }
                if (!empty($info)) {
                    $data['User'] = $info['User'];
                    ++$j;
                } else {
                    $data['User']['id'] = '';
                    ++$i;
                }
                $this->User->saveAll($data['User']);
                $user_id = $this->User->id;
                if (isset($data['UserAddress'])) {
                    $user_address['UserAddress'] = $data['UserAddress'];
                    $user_address['UserAddress']['user_id'] = $user_id;
                    if (empty($data['UserAddress']['consignee']) && isset($data['User']['name'])) {
                        $user_address['UserAddress']['consignee'] = $data['User']['name'];
                    }
                    if (empty($data['UserAddress']['email']) && isset($data['User']['email'])) {
                        $user_address['UserAddress']['email'] = $data['User']['email'];
                    }
                    if (empty($data['UserAddress']['mobile']) && isset($data['User']['mobile'])) {
                        $user_address['UserAddress']['mobile'] = $data['User']['mobile'];
                    }
                    //获取区域ID
                    $order_country_id = '';
                    $order_province_id = '';
                    $order_city_id = '';
                    if (isset($data['UserAddress']['country'])) {
                        $country = $this->RegionI18n->find('first', array('conditions' => array('RegionI18n.locale' => $this->locale, 'RegionI18n.name' => $data['UserAddress']['country']), 'fields' => 'RegionI18n.region_id'));
                        if (!empty($country)) {
                            $order_country_id = $country['RegionI18n']['region_id'];
                            $user_address['UserAddress']['country'] = $order_country_id;
                        }
                    }
                    if (isset($data['UserAddress']['province'])) {
                        $province = $this->RegionI18n->find('first', array('conditions' => array('RegionI18n.locale' => $this->locale, 'RegionI18n.name' => $data['UserAddress']['province']), 'fields' => 'RegionI18n.region_id'));
                        if (!empty($province)) {
                            $order_province_id = $province['RegionI18n']['region_id'];
                            $user_address['UserAddress']['province'] = $order_province_id;
                        }
                    }
                    if (isset($data['UserAddress']['city'])) {
                        $city = $this->RegionI18n->find('first', array('conditions' => array('RegionI18n.locale' => $this->locale, 'RegionI18n.name' => $data['UserAddress']['city']), 'fields' => 'RegionI18n.region_id'));
                        if (!empty($city)) {
                            $order_city_id = $city['RegionI18n']['region_id'];
                            $user_address['UserAddress']['city'] = $order_city_id;
                        }
                    }
                    $this->UserAddress->saveAll($user_address);
                    $aId = $this->UserAddress->id;
                    $this->User->updateAll(array('User.address_id' => $aId), array('User.id' => $user_id));
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'批量上传会员', $this->admin['id']);
            }
            $count_k = $i + $j;
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$count_k.'条'.$this->ld['import_success'].' 新增'.$i.'条 编辑'.$j."条');window.location.href='/admin/users'</script>";
        }
    }

    /**
     *批量新增预览后的商品.
     */
    public function batch_add_products()
    {
        if (!empty($this->data) && !empty($_POST['category_id'])) {
            $category_id = $_POST['category_id'];
            $checkbox_arr = $_REQUEST['checkbox'];
            $name = '';
            $result['error_msg'] = $this->ld['error_message'].':\\r\\n';
            $i = 0;
            $this->Product->hasOne = array();
            $this->Product->hasMany = array();
               //取出所有属性code的集合
                   $attr_infos = $this->ProductTypeAttribute->find('list', array('fields' => array('ProductTypeAttribute.code')));
            $attr_id_infos = $this->ProductTypeAttribute->find('list', array('fields' => array('ProductTypeAttribute.code', 'ProductTypeAttribute.id')));
            foreach ($this->data as $key => $data) {
                if (!in_array($key, $checkbox_arr)) {
                    continue;
                }
                //如果有类目自动保存
                if (isset($data['category_type_code']) && $data['category_type_code'] != '') {
                    $CategoryType = '';
                    $CategoryType['code'] = $data['category_type_code'];
                    $info = $this->CategoryType->find('first', array('conditions' => array('CategoryType.code' => $data['category_type_code'])));
                    if (empty($info)) {
                        $this->CategoryType->saveAll($CategoryType);
                        $Product['category_type_id'] = $this->CategoryType->id;
                    } else {
                        $Product['category_type_id'] = $info['CategoryType']['id'];
                    }
                }

                $ProductI18n['name'] = $data['name'];
                $ProductI18n['meta_keywords'] = isset($data['meta_keywords']) ? $data['meta_keywords'] : '';
                $ProductI18n['meta_description'] = isset($data['meta_description']) ? $data['meta_description'] : '';
                $ProductI18n['description'] = isset($data['description']) ? $data['description'] : '';
                $ProductsCategory['category_id'] = $category_id;
                $name = isset($data['img_thumb']) ? $data['img_thumb'] : '';
                $ProductGallery['img_thumb'] = isset($data['img_thumb']) ? $data['img_thumb'] : '';
                $ProductGallery['img_detail'] = isset($data['img_detail']) ? $data['img_detail'] : '';
                $ProductGallery['img_original'] = isset($data['img_original']) ? $data['img_original'] : '';
                $product_info = $this->Product->find('first', array('conditions' => array('Product.code' => $data['code'])));
                $Product = empty($product_info['Product']) ? array() : $product_info['Product'];
                $Product['code'] = $data['code'];
                $Product['status'] = 1;
                $Product['category_id'] = $category_id;
                $data['shop_price'] = isset($data['shop_price']) ? trim($data['shop_price']) : 0;
                if (isset($data['shop_price']) && !is_numeric($data['shop_price'])) {
                    $Product['shop_price'] = 0;
                    $Product['market_price'] = 0;
                    $Product['custom_price'] = $data['shop_price'];
                } else {
                    $Product['shop_price'] = isset($data['shop_price']) && $data['shop_price'] != '' ? $data['shop_price'] : (isset($data['market_price']) && $data['market_price'] != '' ? $data['market_price'] : 0);
                    $Product['market_price'] = isset($data['market_price']) && $data['market_price'] != '' ? $data['market_price'] : (isset($data['shop_price']) && $data['shop_price'] != '' ? $data['shop_price'] : 0);
                }
                $Product['weight'] = $data['weight'] ? $data['weight'] : 0;
                $Product['quantity'] = $data['quantity'];
                $Product['purchase_price'] = (isset($data['purchase_price']) && !empty($data['purchase_price'])) ? $data['purchase_price'] : 0;
                $Product['recommand_flag'] = isset($data['recommand_flag']) ? $data['recommand_flag'] : 0;
                $Product['forsale'] = isset($data['forsale']) ? $data['forsale'] : 0;
                $Product['alone'] = isset($data['alone']) ? $data['alone'] : 0;
                $Product['extension_code'] = '';
                $Product['img_thumb'] = isset($data['img_thumb']) ? $data['img_thumb'] : '';
                $Product['img_detail'] = isset($data['img_detail']) ? $data['img_detail'] : '';
                $Product['img_original'] = isset($data['img_original']) ? $data['img_original'] : '';
                $Product['min_buy'] = $data['min_buy'] ? $data['min_buy'] : 1;
                $Product['max_buy'] = $data['max_buy'] ? $data['max_buy'] : 100;
                $Product['point'] = !empty($data['point']) ? $data['point'] : 0;
                $Product['point_fee'] = !empty($data['point_fee']) ? $data['point_fee'] : 0;
                if (isset($data['brand_id'])) {
                    $brand = $this->BrandI18n->findByName($data['brand_id']);
                }
                $Product['brand_id'] = $brand ? $brand['BrandI18n']['brand_id'] : 0;
                if (empty($Product['quantity'])) {
                    $Product['quantity'] = $this->configs['default_stock'];
                }
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
                    foreach ($attr_infos as $ak => $a) {
                        if (isset($data[$a])) {
                            foreach ($this->front_locales as $k => $v) {
                                $att['product_id'] = $id;
                                $att['locale'] = $v['Language']['locale'];
                                $att['product_type_attribute_id'] = isset($attr_id_infos[$a]) ? $attr_id_infos[$a] : 0;
                                $att['product_type_attribute_value'] = $data[$a];
                                $this->ProductAttribute->saveAll($att);
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
                            $ProductI18n['description'] = !empty($data['description']) ? $data['description'] : '';
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
                                    $imgaddr_original = WWW_ROOT.'/img/photos/'.date('Ym').'/'.$category_id.'/'.$this->admin['id'].'/original/';   //saas/src/prod/htdocs/20111101/49/1/orginal/
                                    $imgaddr_detail = WWW_ROOT.'/img/photos/'.date('Ym').'/'.$category_id.'/'.$this->admin['id'].'/detail/';
                                    $imgaddr_big = WWW_ROOT.'/img/photos/'.date('Ym').'/'.$category_id.'/'.$this->admin['id'].'/big/';
                                    $imgaddr_small = WWW_ROOT.'/img/photos/'.date('Ym').'/'.$category_id.'/'.$this->admin['id'].'/small/';
                                    $this->mkdirs($imgaddr_original);
                                    $this->mkdirs($imgaddr_detail);
                                    $this->mkdirs($imgaddr_big);
                                    $this->mkdirs($imgaddr_small);
                                    $result_url = copy($path, $imgaddr_original.$image_name);
                                    if ($result_url) {
                                        $img_original = $imgaddr_original.$image_name;//原图地址   /saas/src/prod/htdocs/20111101/49/1/orginal/xxxx.jpg
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
                                        $ProductGallery['img_thumb'] = isset($photo_img_small) ? $photo_img_small : '';
                                        $ProductGallery['img_detail'] = isset($photo_img_detail) ? $photo_img_detail : '';
                                        $ProductGallery['img_original'] = isset($photo_img_original) ? $photo_img_original : '';
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
                                            $result['error_msg'] .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['thumbnail_generate_failed'].'\\r\\n';
                                        }
                                        if (!file_exists($img_detail)) {
                                            $result['error_msg'] .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['detail_image_generate_failed'].'\\r\\n';
                                        }
                                        if (!file_exists($img_original)) {
                                            $result['error_msg'] .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['original_image_build_failure'].'\\r\\n';
                                        }
                                        if (!file_exists($img_big)) {
                                            $result['error_msg'] .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['big_image_generate_failure'].'\\r\\n';
                                        }
                                    }
                                } else {
                                    //图片格式不正确，返回报错
                                    $result['error_msg'] .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['file_format_valid'].'\\r\\n';
//									echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_format_valid'].":".$name."');window.location.href='/admin/products/'</script>";
//								    die();
                                }
                            } else {
                                //路径不存在，报错
                                $result['error_msg'] .= $this->ld['code'].$code.' '.$this->ld['picture'].$ProductGallery['img_thumb'].$this->ld['file_fath'].$this->ld['not_exist'].'\\r\\n';
//								echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['error_message'].":".$this->ld['file_fath'].$name."');window.location.href='/admin/products/'</script>";
//							    die();
                            }
                        }
//	                    $ProductGallery['product_id']=$id;
//	                    $this->ProductGallery->id='';
//	                    $this->ProductGallery->save(array("ProductGallery"=>$ProductGallery));
                    }
                    $ProductsCategory['product_id'] = $id;
//	                $this->ProductsCategory->id='';
                    //$this->ProductsCategory->save($ProductsCategory);
                }
            }
               //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['bulk_upload_products'], $this->admin['id']);
            }
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['import_success'].':'.$i.'\\r\\n'.$result['error_msg']."');window.location.href='/admin/products/'</script>";
            die();
        } else {
            $this->redirect('/products/');
        }
    }

    /**
     *下载CSV 带实例文件.
     */
    public function download_csv_example($type = '')
    {
        $this->Profile->set_locale($this->locale);
        $this->Profile->hasOne = array();
        if ($type == 'A') {
            $flag_code = 'articles_export';
        }
        if ($type == 'M') {
            $flag_code = 'newsletter_export';
        }
        if ($type == 'U') {
            $flag_code = 'user_export';
        }
        if ($type == 'P') {
            $flag_code = 'product_import';
        }
        if ($type == 'O') {
            $flag_code = 'order_import';
        }
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $tmp = array();
        $fields_array = array();
        $newdatas = array();
        if ($type == 'P') {
            $filename = '商品导出'.date('Ymd').'.xls';
                //取出所有公共属性
                $this->ProductTypeAttribute->set_locale($this->backend_locale);
            $pubile_attr_info = $this->ProductTypeAttribute->find('all', array('conditions' => array('ProductTypeAttribute.product_type_id' => 0, 'ProductTypeAttribute.status' => 1), 'fields' => 'ProductTypeAttribute.id,ProductTypeAttributeI18n.name'));
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

            $product_ids = $this->Product->find('list', array('fields' => array('Product.code', 'Product.id'), 'conditions' => array('Product.status' => 1)));
        }
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfileFiled.description'), 'conditions' => array('ProfileFiledI18n.locale' => $this->locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            foreach ($profilefiled_info as $k => $v) {
                $tmp[] = $v['ProfileFiled']['description'];
                $fields_array[] = $v['ProfileFiled']['code'];
            }
        }
        if ($type == 'P') {
            foreach ($pat as $pp) {
                $tmp[] = $pp['name'];
            }
            $attr_info = $this->ProductAttribute->product_list_format($product_ids, $pat_ids, $this->backend_locale);
        }
        $newdatas[] = $tmp;
        if ($type == 'P') {
            $product_all = $this->Product->find('all', array('fields' => $fields_array, 'conditions' => array('Product.status' => 1, 'ProductI18n.locale' => $this->backend_locale), 'limit' => 10));

            foreach ($product_all as $k => $v) {
                $user_tmp = array();
                foreach ($fields_array as $kk => $vv) {
                    $fields_kk = explode('.', $vv);
                    $user_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                }
                if (isset($product_ids[$v['Product']['code']])) {
                    foreach ($pat as $pp) {
                        $user_tmp[] = (isset($attr_info[$product_ids[$v['Product']['code']]][$pp['id']]) ? $attr_info[$product_ids[$v['Product']['code']]][$pp['id']] : ' ');
                    }
                }
                $newdatas[] = $user_tmp;
            }
        }
        if ($type == 'A') {
            $filename = '文章导出'.date('Ymd').'.xls';
            $article_all = $this->Article->find('all', array('fields' => $fields_array, 'conditions' => array('status' => 1, 'ArticleI18n.locale' => $this->locale), 'limit' => 10));
            foreach ($article_all as $k => $v) {
                $user_tmp = array();
                foreach ($fields_array as $kk => $vv) {
                    $fields_kk = explode('.', $vv);
                    $user_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                }
                $newdatas[] = $user_tmp;
            }
        }
        if ($type == 'O') {
            $this->loadModel('Order');
            $this->loadModel('OrderProduct');
            $filename = '订单导出csv实例'.date('Ymd').'.xls';
            $this->Order->hasMany = array();
            $this->OrderProduct->hasOne = array();
            $this->Order->hasOne = array('OrderProduct' => array(
                'className' => 'OrderProduct',
                'order' => '',
                'dependent' => true,
                'foreignKey' => 'order_id',
            ));
            $order_all = $this->Order->find('all', array('fields' => $fields_array, 'conditions' => array('Order.status' => 1, 'Order.type' => 'ioco', 'Order.order_locale' => 'chi'), 'limit' => 2));
            foreach ($order_all as $k => $v) {
                $user_tmp = array();
                foreach ($fields_array as $kk => $vv) {
                    $fields_kk = explode('.', $vv);
                    $user_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                }
                $newdatas[] = $user_tmp;
            }
        }
        if ($type == 'M') {
            $filename = '杂志订阅用户导出'.date('Ymd').'.xls';
            $user_info = $this->NewsletterList->find('all', array('order' => 'NewsletterList.id desc', 'limit' => 10));
            foreach ($user_info as $k => $v) {
                $user_tmp = array();
                foreach ($fields_array as $kk => $vv) {
                    $fields_kk = explode('.', $vv);
                    $user_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                }
                $newdatas[] = $user_tmp;
            }
        }
        if ($type == 'U') {
            $user_info = $this->User->find('all', array('order' => 'User.id desc', 'limit' => 10));//'recursive'=>-1
            $user_ids = array();
            foreach ($user_info as $k => $v) {
                $user_ids[] = $v['User']['id'];
            }
            $filename = '会员导出'.date('Ymd').'.xls';
            $useradd_info = $this->UserAddress->find('all', array('conditions' => array('UserAddress.user_id' => $user_ids)));
            foreach ($user_info as $k => $v) {
                foreach ($useradd_info as $kk => $vv) {
                    if ($vv['UserAddress']['user_id'] == $v['User']['id']) {
                        $user_info[$k]['UserAddress'] = $vv['UserAddress'];
                    }
                }
            }

            foreach ($user_info as $k => $v) {
                $user_tmp = array();
                foreach ($fields_array as $kk => $vv) {
                    $fields_kk = explode('.', $vv);
                    $user_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                }
                $newdatas[] = $user_tmp;
            }
        }
        $this->Phpexcel->output($filename, $newdatas);
        exit;
    }

    //获得订单号
    public function get_order_code()
    {
        mt_srand((double) microtime() * 1000000);
        $sn = date('Ymd').str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $a = 0;
        $b = 0;
        $c = 0;
        for ($i = 1;$i <= 12;++$i) {
            if ($i % 2) {
                $b += substr($sn, $i - 1, 1);
            } else {
                $a += substr($sn, $i - 1, 1);
            }
        }
        $c = (10 - ($a * 3 + $b) % 10) % 10;

        return $sn.$c;
    }

    //判断字符串是否是utf8编码
    public function is_utf8($string)
    {
        if (preg_match('/^(['.chr(228).'-'.chr(233).']{1}['.chr(128).'-'.chr(191).']{1}['.chr(128).'-'.chr(191).']{1}){1}/', $string) == true
                || preg_match('/(['.chr(228).'-'.chr(233).']{1}['.chr(128).'-'.chr(191).']{1}['.chr(128).'-'.chr(191).']{1}){1}$/', $string) == true
                || preg_match('/(['.chr(228).'-'.chr(233).']{1}['.chr(128).'-'.chr(191).']{1}['.chr(128).'-'.chr(191).']{1}){2,}/', $string) == true
        ) {
            return '1';
        }

        return '0';
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
}
