<?php

App::import('Vendor', 'mobile', array('file' => 'GEncrypt.inc.php'));
//include(ROOT."/vendors/nusoap/nusoap.php");
App::import('Vendor', 'nusoap');

class MobilesController extends Controller
{
    public $name = 'Mobiles';
    public $uses = array('Application','Config','MobileOperatorToken','Order','Product','ProductI18n','Operator','Category','LogisticsCompany','Article','ArticleI18n','OrderProduct','UserMessage','ProductI18n','Region','Language','PhotoCategory','PhotoCategoryI18n','PhotoCategoryGallery','Contact','MobileAppTheme','ConfigI18n','OperatorLog','CategoryProduct');
    public $components = array('RequestHandler','Cookie');//,'Domain'
        public $limitNum = 5;
        //检测登陆
        public function checkSession($token)
        {
            if (isset($_SESSION['Operator_Info'])) {
                $tokenInfo = $this->MobileOperatorToken->find('first', array('conditions' => array('MobileOperatorToken.operator_id' => $this->admin['id'])));
                if (!empty($tokenInfo) && $tokenInfo['MobileOperatorToken']['token'] == $token) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        //登陆
        public function login()
        {
            $name = $_REQUEST['username'];
            $pw = $_REQUEST['password'];
            $GEncrypt = new GEncrypt();
            $token = $GEncrypt->encrypt($name, $pw);
            header('Content-Type: application/json');
            if ($name != '' && $pw != '') {
                $operator_info = $this->Operator->find('first', array('conditions' => array('Operator.name' => $name, 'Operator.password' => $pw)));
            }
            if (isset($operator_info) && !empty($operator_info)) {
                $tokenInfo = $this->MobileOperatorToken->find('first', array('conditions' => array('MobileOperatorToken.operator_id' => $operator_info['Operator']['id'])));
                if (!empty($tokenInfo)) {
                    $tokenInfo['MobileOperatorToken']['token'] = $token;
                } else {
                    $tokenInfo['operator_id'] = $operator_info['Operator']['id'];
                    $tokenInfo['token'] = $token;
                    $tokenInfo['device'] = $_REQUEST['device'];
                    $tokenInfo['geolocation'] = $operator_info['Operator']['id'];
                    $tokenInfo['connection'] = $_REQUEST['connection'];
                    $tokenInfo['app_version'] = $operator_info['Operator']['id'];
                    $tokenInfo['login_time'] = date('Y-m-d H:i:s');
                    $tokenInfo['remote_ip'] = '192.168.0.1';
                    $tokenInfo['last_visit_time'] = date('Y-m-d H:i:s');
                    $tokenInfo['last_page'] = $operator_info['Operator']['id'];
                }
                $this->MobileOperatorToken->save($tokenInfo);
                $operator_info['Operator']['last_login_time'] = date('Y-m-d H:i:s');
                $operator_info['Operator']['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
                $this->Operator->save($operator_info);//更新IP地址  和  登入时间
                $_SESSION['Operator_Info'] = $operator_info;
                $result['success'] = true;
                $result['token'] = $token;
                $result['msg'] = 'This User is authorized';
                $this->Config->set_locale('chi');
                $shopName = $this->Config->find('first', array('conditions' => array('Config.code' => 'shop_name')));
                $result['shopName'] = !empty($shopName['ConfigI18n']['value']) ? $shopName['ConfigI18n']['value'] : '商店名称未设置';
                //$result= '{"success":true,"token":'.json_encode($pw).', "msg":'.json_encode('This User is authorized').'}';
            } else {
                $result['success'] = false;
                $result['msg'] = 'This User is NOT authorized';
//				$result= '{"success":false, "msg":'.
//					json_encode('This User is NOT authorized').
//					', "errors" : { "password" :'.json_encode('Password is required').
//					'}'.
//					', "pwd" :'.json_encode($pw).'}';
            }

            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 2);
            die($result);
        }
        //检测权限
        public function checkApplication()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                //获取应用
                $app_ay = $this->Application->init2('chi');
                if (in_array('APP-SHOP', $app_ay['codes'])) {
                    $result['order'] = true;
                } else {
                    $result['order'] = false;
                }
                if (in_array('APP-VIP', $app_ay['codes'])) {
                    $result['vip'] = true;
                } else {
                    $result['vip'] = false;
                }
                if (in_array('APP-VIP', $app_ay['codes'])) {
                    $result['contact'] = true;
                } else {
                    $result['contact'] = false;
                }
                //获取已读 留言数量
                $read_message = $this->UserMessage->find('count', array('conditions' => array('UserMessage.is_read' => 1)));
                //获取未读 留言数量
                $unread_message = $this->UserMessage->find('count', array('conditions' => array('UserMessage.is_read' => 0, 'UserMessage.parent_id' => 0)));
                $result['read_message'] = $read_message;
                $result['unread_message'] = $unread_message;
                $result['code'] = 1;
                $result['msg'] = 'Success';
                //判断域名是否快到期了  30天
//				$domain_info = $this->Domain->find('first',array('fields'=>'Domain.id'));
//				$did = $domain_info['Domain']['id'];
                $did = $this->configs['shop_domain_id'];
                $result['did'] = $did;
                $check_domain = $this->checkDomain($did);
                $result['expire_date'] = date('Y-m-d', strtotime($check_domain['expire_date']));
                if (!$check_domain['flag']) {
                    $result['expire'] = true;
                } else {
                    $result['expire'] = false;
                }
                //获取颜色配置
                $color_info = $this->MobileAppTheme->find('first');
                if (!empty($color_info)) {
                    $result['color_info'] = json_decode($color_info['MobileAppTheme']['css_array'], 'true');
                }
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //查询商品  商品列表
        public function searchProducts()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $condition = '';
                $html = '';
                $pageNum = isset($_REQUEST['pageNum']) ? $_REQUEST['pageNum'] : '1';
                if (isset($_REQUEST['catId']) && !empty($_REQUEST['catId'])) {
                    $condition['AND']['Product.category_id'] = $_REQUEST['catId'];
                }
                if (isset($_REQUEST['key']) && !empty($_REQUEST['key'])) {
                    $product_keyword = $_REQUEST['key'];
                    if ($product_keyword != '') {
                        $keyword = preg_split('#\s+#', $product_keyword);
                        foreach ($keyword as $k => $v) {
                            $conditions_p18n['AND']['or'][0]['and'][]['ProductI18n.name like'] = "%$v%";
                            $conditions_p18n['AND']['or'][1]['and'][]['ProductI18n.meta_keywords  like'] = "%$v%";
                            $condition['AND']['OR'][]['Product.code like'] = "%$v%";
                        }
                        $product18n_pid = $this->ProductI18n->find_product18n_pid($conditions_p18n);
                        $condition['AND']['OR'][]['Product.id'] = $product18n_pid;
                    }
                }
                $this->Product->set_locale($backend_locale);
                if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'select') {
                    $proInfos = $this->Product->find('all', array('conditions' => $condition, 'order' => 'Product.created desc'));
                    if (!empty($proInfos)) {
                        foreach ($proInfos as $k => $v) {
                            $html[$k]['id'] = $v['Product']['id'];
                            $html[$k]['name'] = $v['ProductI18n']['name'];
                        }
                    }
                } else {
                    $proInfos = $this->Product->find('all', array('conditions' => $condition, 'limit' => $this->limitNum, 'page' => $pageNum, 'order' => 'Product.created desc'));
                    if (($this->limitNum * $pageNum) < count($this->Product->find('all', array('conditions' => $condition)))) {
                        $result['show'] = true;
                    } else {
                        $result['show'] = false;
                    }
                    if (!empty($proInfos)) {
                        foreach ($proInfos as $k => $v) {
                            $html[$k]['id'] = $v['Product']['id'];
                            $html[$k]['name'] = $v['ProductI18n']['name'];
                            $html[$k]['code'] = $v['Product']['code'];
                            $html[$k]['shop_price'] = $v['Product']['shop_price'];
                            $html[$k]['img_thumb'] = $v['Product']['img_thumb'];
                            $html[$k]['quantity'] = $v['Product']['quantity'];
                        }
                    }
                }
                $result['code'] = 1;
                $result['html'] = $html;
                $result['msg'] = 'Success';
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //查询订单  订单列表
        public function searchOrders()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $condition = '';
                $pageNum = isset($_REQUEST['pageNum']) ? $_REQUEST['pageNum'] : '1';
                if (isset($_REQUEST['key']) && !empty($_REQUEST['key'])) {
                    $order_keyword = $_REQUEST['key'];
                    if ($order_keyword != '') {
                        $keyword = preg_split('#\s+#', $order_keyword);
                        foreach ($keyword as $k => $v) {
                            $condition['AND']['OR'][]['Order.order_code like'] = "%$v%";
                        }
                    }
                }

                if (isset($_REQUEST['pay']) && $_REQUEST['pay'] != '') {
                    $condition['AND']['Order.payment_status'] = $_REQUEST['pay'];
                }
                if (isset($_REQUEST['ship']) && $_REQUEST['ship'] != '') {
                    $condition['AND']['Order.shipping_status'] = $_REQUEST['ship'];
                }
                $html = '';
                $orderInfos = $this->Order->find('all', array('conditions' => $condition, 'limit' => $this->limitNum, 'page' => $pageNum, 'order' => 'Order.created desc'));
                if (($this->limitNum * $pageNum) < count($this->Order->find('all', array('conditions' => $condition)))) {
                    $result['show'] = true;
                } else {
                    $result['show'] = false;
                }
                if (!empty($orderInfos)) {
                    foreach ($orderInfos as $k => $v) {
                        if ($v['Order']['payment_status'] == 2) {
                            $payment = '已付款';
                        } else {
                            $payment = '未付款';
                        }
                        $html[$k]['id'] = $v['Order']['id'];
                        $html[$k]['order_code'] = $v['Order']['order_code'];
                        $html[$k]['payment'] = $payment;
                        $html[$k]['created'] = $v['Order']['created'];
                        $html[$k]['total'] = $v['Order']['total'];
                        $html[$k]['id'] = $v['Order']['id'];
                    }
                }
                $result['code'] = 1;
                $result['html'] = $html;
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //查询文章  文章列表
        public function searchArticles()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $condition = '';
                $pageNum = isset($_REQUEST['pageNum']) ? $_REQUEST['pageNum'] : '1';
                if (isset($_REQUEST['catId']) && $_REQUEST['catId'] != '') {
                    $condition['AND']['Article.category_id'] = $_REQUEST['catId'];
                }
                $this->Article->set_locale('chi');
                $artInfos = $this->Article->find('all', array('conditions' => $condition, 'limit' => $this->limitNum, 'page' => $pageNum, 'order' => 'Article.created desc'));
                if (($this->limitNum * $pageNum) < count($this->Article->find('all', array('conditions' => $condition)))) {
                    $result['show'] = true;
                } else {
                    $result['show'] = false;
                }
                $html = '';
                if (!empty($artInfos)) {
                    foreach ($artInfos as $k => $v) {
                        $html[$k]['id'] = $v['Article']['id'];
                        $html[$k]['created'] = $v['Article']['created'];
                        $html[$k]['title'] = $v['ArticleI18n']['title'];
                        $html[$k]['author'] = $v['ArticleI18n']['author'];
                    }
                }
                $result['code'] = 1;
                $result['html'] = $html;
                $result['msg'] = 'Success';
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //删除文章
        public function removeArticle()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $condition = '';
                $id = isset($_REQUEST['aId']) ? $_REQUEST['aId'] : '';
                $this->Article->hasMany = array();
                $this->Article->hasOne = array();
                $pn = $this->ArticleI18n->find('list', array('fields' => array('ArticleI18n.article_id', 'ArticleI18n.title'), 'conditions' => array('ArticleI18n.article_id' => $id, 'ArticleI18n.locale' => 'chi')));
                $this->Article->deleteAll(array('id' => $id));
                $this->ArticleI18n->deleteAll(array('article_id' => $id));
                $result['code'] = 1;
                $result['catId'] = $_REQUEST['catId'];
                $result['msg'] = '删除成功';
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //查询留言
        public function searchMessages()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $condition = '';
                $html = '';
                if (isset($_REQUEST['mId']) && $_REQUEST['mId'] != '') {
                    $condition['AND']['UserMessage.id'] = $_REQUEST['mId'];
                    $msgInfos = $this->UserMessage->find('first', array('conditions' => $condition));
                    if (!empty($msgInfos)) {
                        $result['msg_title'] = $msgInfos['UserMessage']['msg_title'];
                        $result['msg_content'] = $msgInfos['UserMessage']['msg_content'];
                        $result['user_name'] = $msgInfos['UserMessage']['user_name'];
                        $result['msg_created'] = $msgInfos['UserMessage']['created'];
                        //标记为已读
                        if ($msgInfos['UserMessage']['is_read'] == 0) {
                            $msgInfos['UserMessage']['is_read'] = 1;
                            $this->UserMessage->save($msgInfos);
                        }
                        $result['oper'] = false;
                        //查询是否已被回复
                        $opermsgInfos = $this->UserMessage->find('all', array('conditions' => array('UserMessage.parent_id' => $_REQUEST['mId'])));
                        if (!empty($opermsgInfos)) {
                            $oper_html = '';
                            foreach ($opermsgInfos as $k => $v) {
                                $oper_html[$k]['user_name'] = $v['UserMessage']['user_name'];
                                $oper_html[$k]['created'] = $v['UserMessage']['created'];
                                $oper_html[$k]['msg_content'] = $v['UserMessage']['msg_content'];
                            }
                            $result['oper'] = true;
                            $result['oper_html'] = $oper_html;
                        }
                    }
                } else {
                    $condition['AND']['UserMessage.parent_id'] = 0;
                    $pageNum = isset($_REQUEST['pageNum']) ? $_REQUEST['pageNum'] : '1';
                    $msgInfos = $this->UserMessage->find('all', array('conditions' => $condition, 'limit' => $this->limitNum, 'page' => $pageNum, 'order' => 'UserMessage.is_read asc,UserMessage.created desc'));
                    if (($this->limitNum * $pageNum) < count($this->UserMessage->find('all', array('conditions' => $condition)))) {
                        $result['show'] = true;
                    } else {
                        $result['show'] = false;
                    }
                    if (!empty($msgInfos)) {
                        foreach ($msgInfos as $k => $v) {
                            $html[$k]['msg_title'] = $v['UserMessage']['msg_title'];
                            $html[$k]['id'] = $v['UserMessage']['id'];
                            $html[$k]['is_read'] = $v['UserMessage']['is_read'];
                        }
                    }
                    $result['html'] = $html;
                }
                $result['code'] = 1;
                $result['msg'] = 'Success';
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }

        //回复留言
        public function checkMessage()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $condition = '';
                $html = '';
                if (isset($_REQUEST['mId']) && $_REQUEST['mId'] != '') {
                    $opermsgInfos['UserMessage']['parent_id'] = $_REQUEST['mId'];
                    $opermsgInfos['UserMessage']['msg_content'] = $_REQUEST['content'];
                    $opermsgInfos['UserMessage']['user_name'] = $this->admin['name'];
                    if ($this->UserMessage->save($opermsgInfos)) {
                        $result['code'] = 1;
                        $result['msg'] = 'Success';
                    } else {
                        $result['code'] = 2;
                        $result['msg'] = '回复失败,请重试!';
                    }
                } else {
                    $result['code'] = 2;
                    $result['msg'] = '信息错误!';
                }
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            if ($result['code'] == 1) {
                $this->UserMessage->updateAll(
                          array('UserMessage.status' => '1'),
                          array('UserMessage.id' => $_REQUEST['mId'])
                          );
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //查询留言
        public function searchContacts()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $condition = '';
                $html = '';
                if (isset($_REQUEST['cId']) && $_REQUEST['cId'] != '') {
                    $condition['AND']['Contact.id'] = $_REQUEST['cId'];
                    $contactInfos = $this->Contact->find('first', array('conditions' => $condition));
                    if (!empty($contactInfos)) {
                        $result['contact_company'] = $contactInfos['Contact']['company'];
                        $result['contact_domain'] = $contactInfos['Contact']['company_url'];
                        $result['contact_company_type'] = $contactInfos['Contact']['company_type'];
                        $result['contact_name'] = $contactInfos['Contact']['contact_name'];
                        $result['contact_email'] = $contactInfos['Contact']['email'];
                        $result['contact_mobile'] = $contactInfos['Contact']['mobile'];
                        $result['contact_content'] = $contactInfos['Contact']['content'];
                        $result['contact_qq'] = $contactInfos['Contact']['qq'];
                        $result['contact_msn'] = $contactInfos['Contact']['msn'];
                        $result['contact_skype'] = $contactInfos['Contact']['skype'];
                        $result['contact_created'] = $contactInfos['Contact']['created'];
                    }
                } else {
                    //$condition['AND']['UserMessage.parent_id'] = 0;
                    $pageNum = isset($_REQUEST['pageNum']) ? $_REQUEST['pageNum'] : '1';
                    $contactInfos = $this->Contact->find('all', array('limit' => $this->limitNum, 'page' => $pageNum, 'order' => 'Contact.created desc'));
                    if (($this->limitNum * $pageNum) < ($this->Contact->find('count', array('conditions' => $condition)))) {
                        $result['show'] = true;
                    } else {
                        $result['show'] = false;
                    }
                    if (!empty($contactInfos)) {
                        foreach ($contactInfos as $k => $v) {
                            $html[$k]['contact_id'] = $v['Contact']['id'];
                            $html[$k]['contact_company'] = $v['Contact']['company'];
                            $html[$k]['contact_name'] = $v['Contact']['contact_name'];
                            $html[$k]['contact_email'] = $v['Contact']['email'];
                            $html[$k]['contact_mobile'] = $v['Contact']['mobile'];
                        }
                    }
                    $result['html'] = $html;
                }
                $result['code'] = 1;
                $result['msg'] = 'Success';
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //查询商品分类并显示
        public function getPcats()
        {
            $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
            $result['server_host'] = 'http://'.$host;
            $result['operator_name'] = $this->admin['name'];
            $result['doc_root'] = basename(dirname($_SERVER['DOCUMENT_ROOT']));
            $result['session_id'] = $this->admin['id'];
            $result['img_server_host'] = Configure::read('img_host');
            //判断是否有手机分类
            $pohtoCatInfo = $this->PhotoCategoryI18n->find('first', array('conditions' => array('PhotoCategoryI18n.name' => 'mobile')));
            if (!empty($pohtoCatInfo)) {
                $result['photo_category_id'] = $pohtoCatInfo['PhotoCategoryI18n']['photo_category_id'];
            } else {
                $pohtoCatInfo['PhotoCategory.id'] = '';
                $pohtoCatInfo['PhotoCategory.orderby'] = '50';
                $a = $this->PhotoCategory->save($pohtoCatInfo);
                $id = $this->PhotoCategory->id;
                $languages = $this->Language->find('all', array('conditions' => array('Language.front' => 1)));
                foreach ($languages as $k => $v) {
                    $pohtoCatI18nInfo[$k]['PhotoCategoryI18n']['photo_category_id'] = $id;
                    $pohtoCatI18nInfo[$k]['PhotoCategoryI18n']['locale'] = $v['Language']['locale'];
                    $pohtoCatI18nInfo[$k]['PhotoCategoryI18n']['name'] = 'mobile';
                }
                $this->PhotoCategoryI18n->saveAll($pohtoCatI18nInfo);
                $result['photo_category_id'] = $id;
            }
            if ($this->checkSession($_REQUEST['token'])) {
                $html = '';
                $categories_tree = $this->CategoryProduct->tree('P', 'chi');
                if (!empty($categories_tree)) {
                    foreach ($categories_tree as $k => $v) {
                        $html[$k]['id'] = $v['CategoryProduct']['id'];
                        $html[$k]['img'] = empty($v['CategoryProduct']['img01']) ? '/images/default.jpg' : $v['CategoryProduct']['img01'];
                        $html[$k]['name'] = $v['CategoryI18n']['name'];
                    }
                }
                $result['code'] = 1;
                $result['html'] = $html;
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }

        //查询文章分类并显示
        public function getAcats()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $html = '';
                $categories_tree = $this->Category->tree('A', 'chi');
                if (!empty($categories_tree)) {
                    foreach ($categories_tree as $k => $v) {
                        $html[$k]['id'] = $v['Category']['id'];
                        $html[$k]['name'] = $v['CategoryI18n']['name'];
                    }
                }
                $result['code'] = 1;
                $result['html'] = $html;
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //编辑文章 商品分类
        public function editCategory()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $html = '';
                $this->CategoryProduct->set_locale('chi');
                $category_info = $this->CategoryProduct->find('first', array('conditions' => array('CategoryProduct.id' => $_REQUEST['catId'])));
                $result['code'] = 1;
                $result['name'] = $category_info['CategoryI18n']['name'];
                $result['img'] = $category_info['CategoryProduct']['img01'];
                $result['order'] = $category_info['CategoryProduct']['orderby'];
                $result['catId'] = $category_info['CategoryProduct']['id'];
                //$result['html']=$html;
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //添加编辑文章 商品分类
        public function editCategoryConfirm()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                if (isset($_REQUEST['catId']) && $_REQUEST['catId'] != '') {
                    $this->Category->set_locale('chi');
                    $catInfo = $this->Category->find('first', array('conditions' => array('Category.id' => $_REQUEST['catId']), 'fields' => 'Category.id,Category.orderby,CategoryI18n.id,CategoryI18n.locale,CategoryI18n.category_id,CategoryI18n.name'));
                    $catInfo['Category']['orderby'] = $_REQUEST['catOrder'];
                    if (isset($_REQUEST['catImg']) && $_REQUEST['catImg'] != '') {
                        $catInfo['Category']['img01'] = $_REQUEST['catImg'];
                    }
                    $catInfo['CategoryI18n']['locale'] = 'chi';
                    $catInfo['CategoryI18n']['name'] = $_REQUEST['catName'];
                    $a = $this->Category->saveAll($catInfo);
                } else {
                    $catInfo = array();
                    $catInfo['Category']['type'] = $_REQUEST['catType'];
                    $catInfo['Category']['orderby'] = $_REQUEST['catOrder'];
                    if (isset($_REQUEST['catImg']) && $_REQUEST['catImg'] != '') {
                        $catInfo['Category']['img01'] = $_REQUEST['catImg'];
                    }
                    $catInfo['CategoryI18n']['locale'] = 'chi';
                    $catInfo['CategoryI18n']['name'] = $_REQUEST['catName'];
                    $this->Category->saveAll($catInfo);
                }
                $result['code'] = 1;
                $result['msg'] = 'success!';
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //订单状态列表
        public function getOrders()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $html = '';
                $unpaid = $this->Order->find('count', array('conditions' => array('Order.payment_status' => 0)));
                $unfilled = $this->Order->find('count', array('conditions' => array('Order.payment_status' => 2, 'Order.shipping_status' => 0)));
                $filled = $this->Order->find('count', array('conditions' => array('Order.shipping_status' => 1)));
                $confirmfilled = $this->Order->find('count', array('conditions' => array('Order.shipping_status' => 2)));
                $html['unpaid'] = $unpaid;
                $html['unfilled'] = $unfilled;
                $html['filled'] = $filled;
                $html['confirmfilled'] = $confirmfilled;
                $result['code'] = 1;
                $result['html'] = $html;
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //商品详细页
        public function proView()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $pId = $_REQUEST['pId'];
                $html = '';
                $this->Product->set_locale($backend_locale);
                $product_info = $this->Product->find('first', array('conditions' => array('Product.id' => $pId)));
                if (!empty($product_info)) {
                    $imgpath = empty($product_info['Product']['img_thumb']) ? 'images/default.jpg' : $product_info['Product']['img_thumb'];
                    $result['pro_img'] = $imgpath;
                    $result['pro_name'] = $product_info['ProductI18n']['name'];
                    $result['pro_desc'] = $product_info['ProductI18n']['description02'];
                    $result['pro_code'] = $product_info['Product']['code'];
                    $result['pro_price'] = $product_info['Product']['shop_price'];
                    $result['pro_quantity'] = $product_info['Product']['quantity'];
                }
                $result['code'] = 1;
                $result['html'] = $html;
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //订单详细页
        public function orderView()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                if ($this->RequestHandler->isPost() || true) {
                    $result = '';
                    if (isset($_REQUEST['oId']) && !empty($_REQUEST['oId'])) {
                        $oId = $_REQUEST['oId'];
                        $order_html = '';
                        $product_html = '';
                        $ship_select_html = '';
                        $ship_html = '';
                        $address_html = '';
                        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $oId)));
                        if (!empty($order_info)) {
                            //订单号  没有为空
                            $invoice_no = $order_info['Order']['invoice_no'];
                            if ($order_info['Order']['payment_status'] == 2) {
                                $payment = '已付款';
                            } else {
                                $payment = '未付款';
                            }

                            $info['code'] = $order_info['Order']['order_code'];
                            $info['created'] = $order_info['Order']['created'];
                            $info['status'] = $payment;
                            $info['total'] = $order_info['Order']['total'];
                            $info['shipping_fee'] = $order_info['Order']['shipping_fee'];
                            $info['discount'] = $order_info['Order']['discount'];
                            $info['to_buyer'] = $order_info['Order']['to_buyer'];
                            $addressInfo = $order_info['Order']['country'].' '.$order_info['Order']['province'].' '.$order_info['Order']['city'].' '.$order_info['Order']['address'];
                            $contact = '';
                            if (!empty($order_info['Order']['mobile']) && !empty($order_info['Order']['telephone'])) {
                                $contact = $order_info['Order']['mobile'].'/'.$order_info['Order']['telephone'];
                            } elseif (!empty($order_info['Order']['mobile'])) {
                                $contact = $order_info['Order']['mobile'];
                            } elseif (!empty($order_info['Order']['telephone'])) {
                                $contact = $order_info['Order']['telephone'];
                            }
                            $address_html['consignee'] = $order_info['Order']['consignee'];
                            $address_html['addressInfo'] = $addressInfo;
                            $address_html['zipcode'] = $order_info['Order']['zipcode'];
                            $address_html['contact'] = $contact;
                            $address_html['email'] = $order_info['Order']['email'];
                            if (isset($order_info['OrderProduct']) && $order_info['OrderProduct'] != '') {
                                foreach ($order_info['OrderProduct'] as $k => $v) {
                                    $proInfos = $this->Product->find('first', array('conditions' => array('Product.id' => $v['product_id'])));
                                    if (!empty($proInfos) && !empty($proInfos['Product']['img_thumb'])) {
                                        $product_html[$k]['img_thumb'] = $proInfos['Product']['img_thumb'];
                                    } else {
                                        $product_html[$k]['img_thumb'] = 'images/default.jpg';
                                    }
                                    $product_html[$k]['product_name'] = $v['product_name'];
                                    $product_html[$k]['product_code'] = $v['product_code'];
                                    $product_html[$k]['product_price'] = $v['product_price'];
                                    $product_html[$k]['product_quntity'] = $v['product_quntity'];
                                    $product_html[$k]['total'] = $v['product_quntity'] * $v['product_price'];
                                }
                            }
                            if ($order_info['Order']['shipping_status'] == 1 || $order_info['Order']['shipping_status'] == 2) {
                                $lcinfo = $this->LogisticsCompany->find('first', array('conditions' => array('LogisticsCompany.id' => $order_info['Order']['logistics_company_id'])));
                                $ship_html['name'] = $lcinfo['LogisticsCompany']['name'];
                                $ship_html['invoice_no'] = $order_info['Order']['invoice_no'];
                            } else {
                                $allLcinfos = $this->LogisticsCompany->logistics_company_effective_list();
                                if (!empty($allLcinfos)) {
                                    foreach ($allLcinfos as $k => $v) {
                                        $select = '';
                                        if ($v['LogisticsCompany']['id'] == $order_info['Order']['logistics_company_id']) {
                                            $select = 'selected';
                                        }
                                        $ship_select_html[$k]['id'] = $v['LogisticsCompany']['id'];
                                        $ship_select_html[$k]['select'] = $select;
                                        $ship_select_html[$k]['name'] = $v['LogisticsCompany']['name'];
                                    }
                                }
                            }
                        }
                        $result['code'] = 1;
                        $result['order_info'] = $info;
                        $result['order_html'] = $order_html;
                        $result['product_html'] = $product_html;
                        $result['address_html'] = $address_html;
                        if ($ship_html != '') {
                            $result['ship_html'] = $ship_html;
                        }
                        if ($ship_select_html != '') {
                            $result['ship_select_html'] = $ship_select_html;
                        }
                        $result['invoice_no'] = $invoice_no;
                    } else {
                        $country_html = '';
                        $ship_select_html = '';
                        $result['code'] = 1;
                        //获取物流公司
                        $allLcinfos = $this->LogisticsCompany->logistics_company_effective_list();
                        if (!empty($allLcinfos)) {
                            foreach ($allLcinfos as $k => $v) {
                                $ship_select_html[$k]['id'] = $v['LogisticsCompany']['id'];
                                $ship_select_html[$k]['name'] = $v['LogisticsCompany']['name'];
                            }
                        }
                        //获取国家
                        $this->Region->set_locale('chi');
                        $country = $this->Region->find('all', array('conditions' => array('Region.parent_id' => 0)));
                        if (!empty($country)) {
                            foreach ($country as $k => $v) {
                                $country_html[$k]['id'] = $v['Region']['id'];
                                $country_html[$k]['name'] = $v['RegionI18n']['name'];
                            }
                        }
                        $result['country_html'] = $country_html;
                        if ($ship_select_html != '') {
                            $result['ship_select_html'] = $ship_select_html;
                        }
                    }
                }
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //获取地区
        public function getRegions()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                if (isset($_REQUEST['pId']) && $_REQUEST['pId'] != '') {
                    $result['reg'] = $_REQUEST['reg'];
                    $select_html = '';
                //获取地区
                $this->Region->set_locale('chi');
                    $country = $this->Region->find('all', array('conditions' => array('Region.parent_id' => $_REQUEST['pId'])));
                    if (!empty($country)) {
                        foreach ($country as $k => $v) {
                            $select_html[$k]['id'] = $v['Region']['id'];
                            $select_html[$k]['name'] = $v['RegionI18n']['name'];
                        }
                    }
                    $result['code'] = 1;
                    $result['select_html'] = $select_html;
                } else {
                    $result['code'] = 2;
                }
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //文章新增 编辑
        public function articleView()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $html = '';
                $result['title'] = '';
                $result['content'] = '';
                $result['page'] = '新增文章';
                if (isset($_REQUEST['aId']) && !empty($_REQUEST['aId'])) {
                    $aId = $_REQUEST['aId'];
                    $this->Article->set_locale('chi');
                    $article_info = $this->Article->find('first', array('conditions' => array('Article.id' => $aId)));
                    if (!empty($article_info)) {
                        $result['title'] = $article_info['ArticleI18n']['title'];
                        $result['author'] = $article_info['ArticleI18n']['author'];
                        $result['content'] = $article_info['ArticleI18n']['content2'];
                        $result['page'] = '编辑文章';
                    }
                }
                $categories_tree = $this->Category->tree('A', 'chi');
                if (!empty($categories_tree)) {
                    foreach ($categories_tree as $k => $v) {
                        $select = '';
                        if (isset($article_info) && $v['Category']['id'] == $article_info['Article']['category_id']) {
                            $select = 'selected';
                        }
                        $html[$k]['id'] = $v['Category']['id'];
                        $html[$k]['name'] = $v['CategoryI18n']['name'];
                        $html[$k]['select'] = $select;
                    }
                }
                $result['code'] = 1;
                $result['html'] = $html;
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }

        //商品新增 编辑
        public function proEdit()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $result['code'] = 2;
                $result['msg'] = 'Failed';
                if (isset($_REQUEST['pId']) && !empty($_REQUEST['pId'])) {
                    $pId = $_REQUEST['pId'];
                    $this->Product->set_locale($backend_locale);
                    $this->Product->hasOne = array('ProductI18n' => array(
                                                              'className' => 'ProductI18n',
                                                              'order' => '',
                                                              'dependent' => true,
                                                              'foreignKey' => 'product_id',
                                                             ),
                                    );
                    $pInfo = $this->Product->find('first', array('conditions' => array('Product.id' => $pId)));
                    $pInfo['Product']['code'] = $_REQUEST['code'];
                    $pInfo['ProductI18n']['name'] = $_REQUEST['name'];
                    $pInfo['ProductI18n']['description02'] = $_REQUEST['desc'];
                    $pInfo['Product']['shop_price'] = $_REQUEST['price'];
                    $pInfo['Product']['quantity'] = $_REQUEST['quantity'] + $pInfo['Product']['quantity'];
                    if ($_REQUEST['img_thumb'] != '') {
                        $pInfo['Product']['img_thumb'] = $_REQUEST['img_thumb'];
                    }
                    if ($_REQUEST['img_detail'] != '') {
                        $pInfo['Product']['img_detail'] = $_REQUEST['img_detail'];
                    }
                    if ($_REQUEST['img_original'] != '') {
                        $pInfo['Product']['img_original'] = $_REQUEST['img_original'];
                    }
                    if ($this->Product->saveAll($pInfo)) {
                        $result['code'] = 1;
                        $result['msg'] = 'Success';
                    } else {
                        $result['code'] = 2;
                        $result['msg'] = 'Save Error!';
                    }
                } else {
                    $this->Product->hasOne = array('ProductI18n' => array(
                                                              'className' => 'ProductI18n',
                                                              'order' => '',
                                                              'dependent' => true,
                                                              'foreignKey' => 'product_id',
                                                             ),
                                    );
                        //检测货号是否存在
                        $isHave = $this->Product->find('all', array('conditions' => array('Product.code' => $_REQUEST['proCode'])));
                    if (empty($isHave)) {
                        $pInfo['Product']['id'] = '';
                        $pInfo['Product']['category_id'] = $_REQUEST['proCat'];
                        $pInfo['Product']['code'] = $_REQUEST['proCode'];
                        $pInfo['Product']['shop_price'] = $_REQUEST['proPrice'];
                        $pInfo['Product']['quantity'] = $_REQUEST['proQuantity'];
                        $pInfo['Product']['img_thumb'] = $_REQUEST['img_thumb'];
                        $pInfo['Product']['img_detail'] = $_REQUEST['img_detail'];
                        $pInfo['Product']['img_original'] = $_REQUEST['img_original'];

                        if ($this->Product->save($pInfo)) {
                            $pId = $this->Product->id;
                            $result['pId'] = $pId;
                            $pInfo = '';
                            $languages = $this->Language->find('all', array('conditions' => array('Language.front' => 1)));
                            foreach ($languages as $k => $v) {
                                $p18nInfo[$k]['ProductI18n']['product_id'] = $pId;
                                $p18nInfo[$k]['ProductI18n']['locale'] = $v['Language']['locale'];
                                $p18nInfo[$k]['ProductI18n']['name'] = $_REQUEST['proName'];
                                $p18nInfo[$k]['ProductI18n']['description02'] = $_REQUEST['proDes'];
                            }
                            if ($this->ProductI18n->saveAll($p18nInfo)) {
                                $result['code'] = 1;
                                $result['msg'] = 'Success';
                            } else {
                                $result['code'] = 2;
                                $result['msg'] = 'ProductI18n Save Error!';
                            }
                        } else {
                            $result['code'] = 2;
                            $result['msg'] = 'Product Save Error!';
                        }
                    } else {
                        $result['code'] = 2;
                        $result['msg'] = '货号已经存在,请重新输入!';
                    }
                }
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }

        //检验货号是否存在
        public function checkProductCode()
        {
            $proCode = $_REQUEST['proCode'];
            //检验手动填写商品货号是否存在
            $info = $this->Product->find('all', array('conditions' => array('Product.code' => $proCode)));
            if (empty($info)) {
                $result['code'] = 1;
                $result['msg'] = 'can use';
            } else {
                $result['code'] = 0;
                $result['msg'] = '货号已经存在';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //检验订单号是否存在
        public function checkOrderCode()
        {
            $orderCode = $_REQUEST['orderCode'];
            //检验手动填写商品货号是否存在
            $info = $this->Order->find('all', array('conditions' => array('Order.order_code' => $orderCode)));
            if (empty($info)) {
                $result['code'] = 1;
                $result['msg'] = 'can use';
            } else {
                $result['code'] = 0;
                $result['msg'] = '订单号已经存在';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //订单编辑
        public function orderEdit()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $result['code'] = 2;
                $result['msg'] = 'Failed';
                if (isset($_REQUEST['oId']) && !empty($_REQUEST['oId'])) {
                    $oId = $_REQUEST['oId'];
                    $orderInfo = $this->Order->find('first', array('conditions' => array('Order.id' => $oId), 'recursive' => -1));
                    if (isset($_REQUEST['invoice_no']) && !empty($_REQUEST['invoice_no'])) {
                        $orderInfo['Order']['invoice_no'] = $_REQUEST['invoice_no'];
                    }
                    if (isset($_REQUEST['logistics_company_id']) && !empty($_REQUEST['logistics_company_id'])) {
                        $orderInfo['Order']['logistics_company_id'] = $_REQUEST['logistics_company_id'];
                        $orderInfo['Order']['shipping_status'] = 1;
                    }
                    if (isset($_REQUEST['to_buyer']) && !empty($_REQUEST['to_buyer'])) {
                        $orderInfo['Order']['to_buyer'] = $_REQUEST['to_buyer'];
                    }
                    if (isset($_REQUEST['to_buyer']) && !empty($_REQUEST['to_buyer'])) {
                        $orderInfo['Order']['to_buyer'] = $_REQUEST['to_buyer'];
                    }
                    if (isset($_REQUEST['discount']) && !empty($_REQUEST['discount'])) {
                        $orderInfo['Order']['total'] = $orderInfo['Order']['total'] + $orderInfo['Order']['discount'] - $_REQUEST['discount'];
                        $orderInfo['Order']['discount'] = $_REQUEST['discount'];
                    }
                    if (isset($_REQUEST['shipping_fee']) && !empty($_REQUEST['shipping_fee'])) {
                        $orderInfo['Order']['total'] = $orderInfo['Order']['total'] - $orderInfo['Order']['shipping_fee'] + $_REQUEST['shipping_fee'];
                        $orderInfo['Order']['shipping_fee'] = $_REQUEST['shipping_fee'];
                    }
                    if ($this->Order->save($orderInfo)) {
                        $result['code'] = 1;
                        $result['msg'] = 'Success';
                    }
                } elseif (isset($_REQUEST['pId']) && !empty($_REQUEST['pId'])) {
                    $this->Product->set_locale($backend_locale);
                    $pInfo = $this->Product->find('first', array('conditions' => array('Product.id' => $_REQUEST['pId'])));
                    if (!empty($pInfo)) {
                        $orderInfo = '';
                        $orderInfo['Order']['locale'] = 'chi';
                        $orderInfo['Order']['order_currency'] = 'RMB';
                        $orderInfo['Order']['order_code'] = $this->get_order_code();
                        $orderInfo['Order']['status'] = 1;
                        $orderInfo['Order']['user_id'] = 0;
                        $orderInfo['Order']['payment_status'] = $_REQUEST['payment_status'];
                        $orderInfo['Order']['shipping_status'] = $_REQUEST['shipping_status'];
                        $orderInfo['Order']['shipping_fee'] = $_REQUEST['shipping_fee'];
                        $orderInfo['Order']['consignee'] = $_REQUEST['consignee'];
                        $orderInfo['Order']['country'] = $_REQUEST['country'];
                        $orderInfo['Order']['province'] = $_REQUEST['province'];
                        $orderInfo['Order']['discount'] = $_REQUEST['discount'];
                        $orderInfo['Order']['city'] = $_REQUEST['city'];
                        $orderInfo['Order']['address'] = $_REQUEST['address'];
                        $orderInfo['Order']['zipcode'] = $_REQUEST['zipcode'];
                        $orderInfo['Order']['mobile'] = $_REQUEST['mobile'];
                        $orderInfo['Order']['email'] = $_REQUEST['email'];
                        $orderInfo['Order']['to_buyer'] = $_REQUEST['to_buyer'];
                        $orderInfo['Order']['subtotal'] = $_REQUEST['pNum'] * $pInfo['Product']['shop_price'];
                        $orderInfo['Order']['total'] = $_REQUEST['pNum'] * $pInfo['Product']['shop_price'] - $_REQUEST['discount'] + $_REQUEST['shipping_fee'];
                        if (isset($_REQUEST['invoice_no']) && !empty($_REQUEST['invoice_no'])) {
                            $orderInfo['Order']['invoice_no'] = $_REQUEST['invoice_no'];
                        }
                        if (isset($_REQUEST['logistics_company_id']) && !empty($_REQUEST['logistics_company_id'])) {
                            $orderInfo['Order']['logistics_company_id'] = $_REQUEST['logistics_company_id'];
                        }
                        if ($this->Order->save($orderInfo)) {
                            $orderproduct = '';
                            $oId = $this->Order->id;
                            $orderproduct['OrderProduct']['order_id'] = $oId;
                            $orderproduct['OrderProduct']['product_id'] = $_REQUEST['pId'];
                            $orderproduct['OrderProduct']['product_name'] = $pInfo['ProductI18n']['name'];
                            $orderproduct['OrderProduct']['product_price'] = $pInfo['Product']['shop_price'];
                            $orderproduct['OrderProduct']['product_code'] = $pInfo['Product']['code'];
                            if (isset($pInfo['Product']['weight']) && $pInfo['Product']['weight'] != '') {
                                $orderproduct['OrderProduct']['product_weight'] = $_REQUEST['pNum'] * $pInfo['Product']['weight'];
                            }
                            $orderproduct['OrderProduct']['extension_code'] = $pInfo['Product']['extension_code'];
                            $orderproduct['OrderProduct']['product_attrbute'] = $_REQUEST['pAttr'];
                            $orderproduct['OrderProduct']['product_quntity'] = $_REQUEST['pNum'];
                            if ($this->OrderProduct->save($orderproduct)) {
                                $result['code'] = 1;
                                $result['msg'] = 'Success!';
                            } else {
                                $result['code'] = 2;
                                $result['msg'] = 'Order Product Save Error!';
                            }
                        } else {
                            $result['code'] = 2;
                            $result['msg'] = 'Order Save Error!';
                        }
                    }
                }
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //文章编辑 增加
        public function artEdit()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $result['code'] = 2;
                $result['msg'] = 'Failed';
                    //全部语言
                    $languages = $this->Language->find('all', array('conditions' => array('Language.front' => 1)));
                if (isset($_REQUEST['aId']) && !empty($_REQUEST['aId'])) {
                    $aId = $_REQUEST['aId'];
                    $result['aId'] = $aId;
                        //$this->Article->set_locale('chi');
                        $aInfo = $this->Article->find('all', array('conditions' => array('Article.id' => $aId)));
                    foreach ($aInfo as $k => $v) {
                        $aInfo[$k]['Article']['category_id'] = $_REQUEST['catId'];
                        $aInfo[$k]['ArticleI18n']['title'] = $_REQUEST['title'];
                        $aInfo[$k]['ArticleI18n']['author'] = $_REQUEST['author'];
                        $aInfo[$k]['ArticleI18n']['content2'] = $_REQUEST['content'];
                        if ($this->Article->saveAll($aInfo[$k])) {
                            $result['code'] = 1;
                            $result['msg'] = 'Success';
                        } else {
                            $result['code'] = 2;
                            $result['msg'] = 'Save Error!';
                        }
                    }
                } else {
                    $aInfo['Article']['id'] = '';
                    $aInfo['Article']['category_id'] = $_REQUEST['catId'];
                    $this->Article->save($aInfo);
                    $aId = $this->Article->id;
                    $result['aId'] = $aId;
                    foreach ($languages as $k => $v) {
                        $a18nInfo[$k]['ArticleI18n']['article_id'] = $aId;
                        $a18nInfo[$k]['ArticleI18n']['locale'] = $v['Language']['locale'];
                        $a18nInfo[$k]['ArticleI18n']['title'] = $_REQUEST['title'];
                        $a18nInfo[$k]['ArticleI18n']['subtitle'] = '';
                        $a18nInfo[$k]['ArticleI18n']['author'] = $_REQUEST['author'];
                        $a18nInfo[$k]['ArticleI18n']['content2'] = $_REQUEST['content'];
                    }
                    if ($this->ArticleI18n->saveAll($a18nInfo)) {
                        $result['code'] = 1;
                        $result['msg'] = 'Success';
                    } else {
                        $result['code'] = 2;
                        $result['msg'] = 'Save Error!';
                    }
                }
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }

        //提交反馈
        public function checkFeedback()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $info['title'] = $_REQUEST['title'];
                $info['content'] = $_REQUEST['content'];
//				$did=$this->Domain->find('first');
//				$info['did']=$did['Domain']['id'];
                $info['did'] = $this->configs['shop_domain_id'];
                if ($this->sendFeedback($info)) {
                    $result['code'] = 1;
                    $result['msg'] = '提交成功!';
                } else {
                    $result['code'] = 2;
                    $result['msg'] = '提交失败,请重试!';
                }
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //去官网插入反馈记录
        public function sendFeedback($info)
        {
            $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
            $client = new nusoap_client($soap_api, true);
            $client->soap_defencoding = 'utf-8';
            $client->decode_utf8 = false;
            $client->xml_encoding = 'utf-8';
            $eninfo = json_encode($info);
            $result = $client->call('addmessage', array('eninfo' => $eninfo));

            return $result['app'];
        }
        //检测域名是否到期 提前一个月提醒
        public function checkDomain($did)
        {
            $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
            $client = new nusoap_client($soap_api, true);
            $client->soap_defencoding = 'utf-8';
            $client->decode_utf8 = false;
            $client->xml_encoding = 'utf-8';
            $result = $client->call('getExpiration', array('did' => $did));
            $now = date('Y-m-d H:i:s');
            $days = (int) ((strtotime($result['expire_date']) - strtotime($now)) / 3600 / 24);
            if ($days <= 30) {
                $result['flag'] = false;
            } else {
                $result['flag'] = true;
            }

            return $result;
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
        /**
         *保存SWFUPLOAD提交过来的图片数据.
         */
        public function image_data_save()
        {
            $image_data = $_REQUEST['image_data'];
            $image_data = str_replace('\\', '', $image_data);
            $image_data = json_decode($image_data);
            $image_data = (array) $image_data;
            $image_data['img'] = (array) $image_data['img'];
            if (isset($image_data['img']['id']) && $image_data['img']['id'] != '') {
                $this->PhotoCategoryGallery->Id = $image_data['img']['id'];
                $this->PhotoCategoryGallery->save(array('PhotoCategoryGallery' => $image_data['img']));
                $image_data['img']['name'] = $image_info['PhotoCategoryGallery']['name'];
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['image_replace'].':'.$image_data['img']['name'], $this->admin['id']);
                }
            } else {
                $image_data['img']['id'] = 0;
                if ($image_data['error'] == false) {
                    $this->PhotoCategoryGallery->saveAll(array('PhotoCategoryGallery' => $image_data['img']));
                    $id = $this->PhotoCategoryGallery->getLastInsertId();
                    $photo_list = $this->PhotoCategoryGallery->find('first', array('conditions' => array('PhotoCategoryGallery.id' => $id)));
                    $image_name = isset($photo_list['PhotoCategoryGallery']['name']) ? $photo_list['PhotoCategoryGallery']['name'] : '';
                    //操作员日志
                    if ($this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['image_upload'].':'.$image_name, $this->admin['id']);
                    }
                }
            }
            $result['code'] = 1;
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //获取网店的logo数据
        public function getLogo()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $this->Config->set_locale('chi');
                $logo_info = $this->Config->find('first', array('conditions' => array('Config.code' => 'shop_logo')));
                $result['code'] = 1;
                if (!empty($logo_info) && $logo_info['ConfigI18n']['value'] != '') {
                    $result['img'] = $logo_info['ConfigI18n']['value'];
                } else {
                    $result['img'] = '';
                }
                //商店颜色数据  wap专用
                $color_info = $this->Config->find('first', array('conditions' => array('Config.code' => 'shop_wap_color')));
                if (!empty($color_info) && $color_info['ConfigI18n']['value'] != '') {
                    $result['shop_color'] = $color_info['ConfigI18n']['value'];
                } else {
                    $result['shop_color'] = '';
                }
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
    public function logo_data_save()
    {
        if ($this->checkSession($_REQUEST['token'])) {
            $path = $_REQUEST['path'];
            $this->Config->set_locale('chi');
            $info = $this->Config->find('first', array('conditions' => array('Config.code' => 'shop_logo')));
            $info['ConfigI18n']['value'] = $path;
            $this->Config->saveAll($info);
            $result['code'] = 1;
        } else {
            $result['code'] = 0;
            $result['msg'] = '登陆超时,请重新登陆!';
        }
        $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
        Configure::write('debug', 1);
        die($result);
    }
        //保存商店前台颜色 wap专用
        public function color_data_save()
        {
            if ($this->checkSession($_REQUEST['token'])) {
                $value = $_REQUEST['shop_color'];
                //$this->Config->set_locale('chi');
                $info = $this->Config->find('first', array('conditions' => array('Config.code' => 'shop_wap_color'), 'recursive' => -1));
                $this->ConfigI18n->updateAll(array('ConfigI18n.value' => "'".$value."'"), array('ConfigI18n.config_id' => $info['Config']['id']));
                //$info['ConfigI18n']['value']=$value;
                //$this->Config->saveAll($info);
                $result['code'] = 1;
            } else {
                $result['code'] = 0;
                $result['msg'] = '登陆超时,请重新登陆!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //保存定制颜色数据
        public function color_change($sele, $attr, $valu, $valu2 = '')
        {
            if ($valu == '') {
                return '';
            }
            if ($attr == 'text-shadow') {
                //return $sele."{".$attr.":".$valu."0 1px 0!important}";
                //$tmp = $sele."{".$attr.":"."none!important}";
                $tmp = $sele.'{'.$attr.':'.$valu.'!important}';

                return $tmp;
            } elseif ($valu2 == '') {
                return $sele.'{'.$attr.':'.$valu.'!important}';
            } else {
                if ($attr == 'color') {
                    return $sele.'{'.$attr.':'.$valu.'!important}';
                } elseif ($attr == 'background') {
                    $tmp = $sele.'{'.$attr.':'.'-webkit-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                    $tmp .= $sele.'{'.$attr.':'.'-moz-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                    $tmp .= $sele.'{'.$attr.':'.'-ms-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                    $tmp .= $sele.'{'.$attr.':'.'-o-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                    $tmp .= $sele.'{'.$attr.':'.'linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';

                    return $tmp;
                } elseif ($attr == 'border-color') {
                    return $sele.'{'.$attr.':'.$valu.'!important}';
                }
            }
        }

    public function save_color_config()
    {
        if ($this->checkSession($_REQUEST['token']) || true) {
            unset($_REQUEST['url']);
            unset($_REQUEST['callback']);
            unset($_REQUEST['token']);
            $color_config = array();
            $color_config['id'] = 1;
            $color_config['css_array'] = json_encode($_REQUEST);
            $css = '';
            $css .= $this->color_change('.ui-title', 'color', $_REQUEST['header_font_color']);
            $css .= $this->color_change('.ui-title', 'text-shadow', $_REQUEST['header_font_shadow_color']);
            $css .= $this->color_change('.ui-header', 'background', $_REQUEST['header_background_color1'], $_REQUEST['header_background_color2']);
            $css .= $this->color_change('.ui-header', 'border-color', $_REQUEST['header_frame_color']);

            $css .= $this->color_change('.ui-collapsible-heading .ui-btn', 'color', $_REQUEST['title_font_color']);
            $css .= $this->color_change('.ui-collapsible-heading .ui-btn', 'text-shadow', $_REQUEST['title_font_shadow_color']);
            $css .= $this->color_change('.ui-collapsible-heading .ui-btn', 'background', $_REQUEST['title_background_color1'], $_REQUEST['title_background_color2']);
            $css .= $this->color_change('.ui-collapsible-heading .ui-btn,.ui-collapsible-content', 'border-color', $_REQUEST['title_frame_color']);

            $css .= $this->color_change('.ui-collapsible-content .ui-listview .ui-btn .ui-link-inherit', 'color', $_REQUEST['home_list_font_color']);
            $css .= $this->color_change('.ui-collapsible-content .ui-listview .ui-btn .ui-link-inherit', 'text-shadow', $_REQUEST['home_list_font_shadow_color']);
            $css .= $this->color_change('.ui-collapsible-content .ui-listview .ui-btn', 'background', $_REQUEST['home_list_background_color1'], $_REQUEST['home_list_background_color2']);
            $css .= $this->color_change('.ui-collapsible-content .ui-listview .ui-btn', 'border-color', $_REQUEST['home_list_frame_color']);

            $css .= $this->color_change('.ui-footer .ui-link', 'color', $_REQUEST['foot_font_color']);
            $css .= $this->color_change('.ui-footer .ui-link', 'text-shadow', $_REQUEST['foot_font_shadow_color']);
            $css .= $this->color_change('.ui-footer', 'background', $_REQUEST['foot_background_color1'], $_REQUEST['foot_background_color2']);
            $css .= $this->color_change('.ui-footer', 'border-color', $_REQUEST['foot_frame_color']);
            $css .= $this->color_change('.ui-footer .workselected', 'background', $_REQUEST['foot_hightlight_background_color1'], $_REQUEST['foot_hightlight_background_color2']);//底部高亮背景
                $css .= $this->color_change('.ui-footer .workselected .ui-link', 'color', $_REQUEST['foot_hightlight_font_color']);
                //$css .= $this->color_change(".ui-footer .workselected .ui-link","text-shadow",$_REQUEST['foot_hightlight_shadow_color']);


                $css .= $this->color_change('.homeproducttopic .ui-collapsible-content', 'background', $_REQUEST['home_product_background_color1'], $_REQUEST['home_product_background_color2']);
            $css .= $this->color_change('.homeproducttopic .ui-collapsible-content a', 'border-color', $_REQUEST['home_product_frame_color']);
            $css .= $this->color_change('.homeproducttopic .ui-collapsible-content a span', 'background', $_REQUEST['home_product_price_background_color1'], $_REQUEST['home_product_price_background_color2']);
            $css .= $this->color_change('.homeproducttopic .ui-collapsible-content a span', 'color', $_REQUEST['home_product_price_font_color']);
            $css .= $this->color_change('.homeproducttopic .ui-collapsible-content a span', 'text-shadow', $_REQUEST['home_product_price_font_shadow_color']);

            $css .= $this->color_change('.ui-header .ui-btn, .ui-footer .ui-btn', 'background', $_REQUEST['head_button_background_color1'], $_REQUEST['head_button_background_color2']);
            $css .= $this->color_change('.ui-header .ui-btn, .ui-footer .ui-btn', 'color', $_REQUEST['head_button_font_color']);
            $css .= $this->color_change('.ui-header .ui-btn, .ui-footer .ui-btn', 'text-shadow', $_REQUEST['head_button_font_shadow_color']);
            $css .= $this->color_change('.ui-header .ui-btn, .ui-footer .ui-btn', 'border-color', $_REQUEST['head_button_frame_color']);

            $css .= $this->color_change('.ui-listview .ui-btn', 'background', $_REQUEST['list_background_color1'], $_REQUEST['list_background_color2']);
            $css .= $this->color_change('.ui-listview .ui-btn .ui-link-inherit', 'color', $_REQUEST['list_font_color']);
            $css .= $this->color_change('.ui-listview .ui-btn .ui-link-inherit', 'text-shadow', $_REQUEST['list_font_shadow_color']);
            $css .= $this->color_change('.pro_img', 'background', $_REQUEST['product_img_background_color1'], $_REQUEST['product_img_background_color2']);
            $css .= $this->color_change('.pro_img', 'border-color', $_REQUEST['product_img_background_frame_color']);
            $css .= $this->color_change('.pro_img span', 'border-color', $_REQUEST['product_img_frame_color']);
            $css .= $this->color_change('.picdate', 'background', $_REQUEST['product_attr_background_color1'], $_REQUEST['product_attr_background_color2']);
            $css .= $this->color_change('.picdate', 'color', $_REQUEST['product_attr_font_color']);
            $css .= $this->color_change('.picdate', 'text-shadow', $_REQUEST['product_attr_font_shadow_color']);
            $css .= $this->color_change('.picdate .newpic i', 'color', $_REQUEST['product_attr_price_color']);
            $css .= $this->color_change('.picdate .newpic i', 'text-shadow', $_REQUEST['product_attr_price_shadow_color']);
            $css .= $this->color_change('.picdate', 'border-color', $_REQUEST['product_attr_frame_color']);//详细页属性边框
                $css .= $this->color_change('.pro_date', 'background', $_REQUEST['product_desc_background_color1'], $_REQUEST['product_desc_background_color2']);
            $css .= $this->color_change('.pro_date', 'color', $_REQUEST['product_desc_font_color']);
            $css .= $this->color_change('.pro_date', 'text-shadow', $_REQUEST['product_desc_font_shadow_color']);
            $css .= $this->color_change('.pro_date', 'border-color', $_REQUEST['product_desc_frame_color']);

            $css .= $this->color_change('.per_date span, .next_date span', 'color', $_REQUEST['next_product_name_color']);
            $css .= $this->color_change('.per_date span, .next_date span', 'text-shadow', $_REQUEST['next_product_name_shadow_color']);
            $css .= $this->color_change('#last_pro_price, #next_pro_price', 'color', $_REQUEST['next_product_price_color']);
            $css .= $this->color_change('#last_pro_price, #next_pro_price', 'text-shadow', $_REQUEST['next_product_price_shadow_color']);
                //$_REQUEST['next_shadow_color']
                $css .= $this->color_change('.per_date .ui-btn, .next_date .ui-btn', 'color', $_REQUEST['next_color']);//详细页按钮背景
                $css .= $this->color_change('.per_date .ui-btn .ui-btn-text, .next_date .ui-btn .ui-btn-text', 'text-shadow', $_REQUEST['next_shadow_color']);
            $css .= $this->color_change('.per_date .ui-btn, .next_date .ui-btn', 'background', $_REQUEST['product_button_background_color1'], $_REQUEST['product_button_background_color2']);
            $css .= $this->color_change('.per_date .ui-btn, .next_date .ui-btn', 'border-color', $_REQUEST['product_button_frame_color']);
            $css .= $_REQUEST['custom_css'];
            $color_config['css'] = $css;
            $this->MobileAppTheme->save($color_config);
            $result['code'] = 1;
            $result['msg'] = '保存成功!';
        } else {
            $result['code'] = 0;
            $result['msg'] = '登陆超时,请重新登陆!';
        }
        $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
        Configure::write('debug', 1);
        die($result);
    }
}
