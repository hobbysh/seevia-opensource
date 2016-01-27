<?php
class MobilesController extends Controller
{
    public $name = 'Mobiles';
    public $uses = array('Language','Product','ProductI18n','Category','CategoryI18n','Article','ArticleI18n','Application','Config','ApplicationConfig','Application','Contact','MailTemplate','ProductGallery','Topic','TopicProduct','MobileAppThemes');
    public $components = array('Email');
    public $limitNum = 20;
    public $locale = 'chi';
        //检测是否装了手机WAP
        public function checkMobileWap()
        {
            $this->languages = $this->Language->findalllang();
            foreach ($this->languages as $k => $v) {
                if ($v['Language']['is_default'] == 1) {
                    $default_lng = $v['Language']['locale'];
                }
            }
            if (!isset($default_lng)) {
                foreach ($this->languages as $k => $v) {
                    //pr($v);
                    if ($v['Language']['front'] == 1) {
                        $default_lng = $v['Language']['locale'];
                    }
                    break;
                }
            }
            if (!defined('LOCALE')) {
                define('LOCALE', $default_lng);
            }
            $this->locale = $default_lng;
            $info = array();
            $info = $this->Application->find('first', array('conditions' => array('Application.code' => 'APP-MOBILE-WAP', 'Application.status' => 1)));
            if (empty($info)) {
                return false;
            } else {
                return true;
            }
        }
        //查询专题
        public function getTopics()
        {
            $topic_infos = array();
            $conditions = array();
            $conditions = array('Topic.start_time <=' => DateTime,'Topic.end_time >=' => DateTime);
            $conditions['Topic.status'] = 1;
            $topic_infos = $this->Topic->find('all', array('conditions' => $conditions, 'fields' => 'Topic.id,TopicI18n.title,TopicI18n.img02'));
            if (!empty($topic_infos)) {
                foreach ($topic_infos as $tk => $t) {
                    $topic_infos[$tk]['TopicI18n']['title'] = $this->Article->cutstr($t['TopicI18n']['title'], 70);
                }
            }
            $result['code'] = 1;
            $result['topic_infos'] = $topic_infos;
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //查询专题下商品 介绍
        public function searchTopicInfo()
        {
            $_GET=$this->clean_xss($_GET);
            $id = $_GET['tid'];
            $result['msg'] = 'Success';
            //$result['Products']=$Products;
            $t_info = $this->Topic->find('first', array('conditions' => array('Topic.id' => $id), 'fields' => 'TopicI18n.title,TopicI18n.mobile_intro'));
            $result['topicDes'] = $t_info['TopicI18n']['mobile_intro'];
            $result['topicName'] = $t_info['TopicI18n']['title'];
            $result['code'] = 1;
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //查询文章分类并显示
        public function getAcats()
        {
            $ArticleCats = '';
            $categories_tree = $this->Category->tree('A');
            if (!empty($categories_tree['assoc'])) {
                $i = 0;
                foreach ($categories_tree['assoc'] as $k => $v) {
                    if (!isset($v['CategoryI18n']) || empty($v['CategoryI18n'])) {
                        continue;
                    }
                    $ArticleCats[$i]['id'] = $v['Category']['id'];
                    $ArticleCats[$i]['name'] = $v['CategoryI18n']['name'];
                    ++$i;
                }
            }
            $result['code'] = 1;
            $result['ArticleCats'] = $ArticleCats;
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //查询文章列表
        public function searchArticles()
        {
            if (!$this->checkMobileWap()) {
                $result['code'] = 2;
                $result['msg'] = '手机WAP应用未安装';
                $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
                $result = $callback.'('.json_encode($result).')';
                Configure::write('debug', 1);
                die($result);
            }
            $_GET=$this->clean_xss($_GET);
            $condition = '';
            $pageNum = isset($_GET['pageNum']) ? $_GET['pageNum'] : '1';
            if (isset($_GET['catId']) && $_GET['catId'] != '') {
                $condition['AND']['Article.category_id'] = $_GET['catId'];
                //$limit=$pageNum*$this->limitNum;
            }
            if (isset($_GET['key']) && $_GET['key'] != '') {
                $condition['AND']['or']['ArticleI18n.title LIKE'] = '%'.$_GET['key'].'%';
                $condition['AND']['or']['ArticleI18n.content LIKE'] = '%'.$_GET['key'].'%';
            }
            $artInfos = $this->Article->find('all', array('conditions' => $condition, 'limit' => $this->limitNum, 'page' => $pageNum, 'order' => 'Article.created desc'));
            if (isset($_GET['order'])) {
                $homeArtInfos = $this->Article->find('all', array('limit' => 10, 'order' => 'Article.'.$_GET['order'].' desc', 'page' => $pageNum));
            } else {
                $homeArtInfos = $this->Article->find('all', array('limit' => 4, 'order' => 'Article.created desc'));
            }
            if (($this->limitNum * $pageNum) < count($this->Article->find('all', array('conditions' => $condition)))) {
                $result['show'] = true;
            } else {
                $result['show'] = false;
            }
            $Articles = '';
            $HomeArticles = '';
            if (!empty($artInfos)) {
                foreach ($artInfos as $k => $v) {
                    $Articles[$k]['id'] = $v['Article']['id'];
                    $Articles[$k]['created'] = $v['Article']['created'];
                    $Articles[$k]['title'] = $v['ArticleI18n']['title'];
                    $Articles[$k]['author'] = $v['ArticleI18n']['author'];
                }
            }
            if (!empty($homeArtInfos)) {
                foreach ($homeArtInfos as $k => $v) {
                    $HomeArticles[$k]['id'] = $v['Article']['id'];
                    $HomeArticles[$k]['created'] = $v['Article']['created'];
                    $HomeArticles[$k]['title'] = $v['ArticleI18n']['title'];
                    $HomeArticles[$k]['author'] = $v['ArticleI18n']['author'];
                }
            }
            $result['code'] = 1;
            $result['msg'] = 'Success';
            $result['Articles'] = $Articles;
            $result['HomeArticles'] = $HomeArticles;
            $shopName = $this->Config->find('first', array('conditions' => array('Config.code' => 'shop_name')));
            $result['shopName'] = !empty($shopName['ConfigI18n']['value']) ? $shopName['ConfigI18n']['value'] : '商店名称未设置';

            $appInfo = $this->ApplicationConfig->find('first', array('conditions' => array('ApplicationConfig.code' => 'APP-MOBILE-WAP-ABOUTUS')));
            if (!empty($appInfo)) {
                foreach ($appInfo['ApplicationConfigI18n'] as $v) {
                    if ($v['locale'] == $this->locale) {
                        $aboutus = str_replace("\n", '<br/>', $v['value']);
                        //$result['aboutus'] = str_replace(" ","&nbsp;",$aboutus);
                        $result['aboutus'] = $aboutus;
                    }
                }
            } else {
                $result['aboutus'] = '';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //文章内容显示
        public function articleView()
        {
            $_GET=$this->clean_xss($_GET);
            $html = '';
            $result['title'] = '';
            $result['content'] = '';
            if (isset($_GET['aId']) && !empty($_GET['aId'])) {
                $aId = $_GET['aId'];
                $article_info = $this->Article->find('first', array('conditions' => array('Article.id' => $aId)));
                if (!empty($article_info)) {
                    $result['title'] = $article_info['ArticleI18n']['title'];
                    $content2 = str_replace("\n", '<br/>', $article_info['ArticleI18n']['content2']);
                    $result['content'] = str_replace(' ', '&nbsp;', $content2);
                }
                $result['code'] = 1;
                $result['msg'] = 'success';
            } else {
                $result['code'] = 2;
                $result['msg'] = 'failed';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //查询商品分类并显示
        public function getPcats()
        {
            $ProductCats = '';
            $categories_tree = $this->Category->tree('P');
            //pr($categories_tree);exit();
            if (!empty($categories_tree)) {
                foreach ($categories_tree['assoc'] as $k => $v) {
                    if ($v['Category']['parent_id'] != 0 || !isset($v['CategoryI18n']) || empty($v['CategoryI18n'])) {
                        continue;
                    } else {
                        $ProductCats[$k]['id'] = $v['Category']['id'];
                        $ProductCats[$k]['name'] = $v['CategoryI18n']['name'];
                        if ($v['CategoryI18n']['meta_description'] != '') {
                            $ProductCats[$k]['desc'] = $v['CategoryI18n']['meta_description'];
                        }
                        $ProductCats[$k]['img'] = isset($v['Category']['img01']) && !empty($v['Category']['img01']) ? $v['Category']['img01'] : 'images/default.jpg';
                    }
                }
            }

            $result['code'] = 1;
            $result['ProductCats'] = $ProductCats;
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //查询商品  商品列表
        public function searchProducts()
        {
            $condition = '';
            $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : '3';
            if ($orderBy == '1') {
                $order = 'Product.shop_price desc';
            }
            if ($orderBy == '2') {
                $order = 'Product.sale_stat desc';
            }
            if ($orderBy == '3') {
                $order = 'Product.created desc';
            }
            $result['ProductCats'] = '';
            $result['catId'] = '';
            $pageNum = isset($_GET['pageNum']) ? $_GET['pageNum'] : '1';
            if (isset($_GET['catId']) && !empty($_GET['catId'])) {
                $result['catId'] = $_GET['catId'];
                //判断是否是有分类
                $nextInfos = $this->Category->find('all', array('conditions' => array('Category.parent_id' => $_GET['catId'])));
                if (!empty($nextInfos)) {
                    foreach ($nextInfos as $k => $v) {
                        $ProductCats[$k]['id'] = $v['Category']['id'];
                        $ProductCats[$k]['name'] = $v['CategoryI18n']['name'];
                        $ProductCats[$k]['img'] = isset($v['Category']['img01']) && !empty($v['Category']['img01']) ? $v['Category']['img01'] : 'images/default.jpg';
                    }
                    $result['ProductCats'] = $ProductCats;
                }
                $condition['AND']['Product.category_id'] = $_GET['catId'];
                $cInfo = $this->CategoryI18n->find('first', array('conditions' => array('CategoryI18n.category_id' => $_GET['catId'], 'CategoryI18n.locale' => $this->locale)));
                $result['catName'] = $cInfo['CategoryI18n']['name'];
            }
            $condition['AND']['Product.status'] = 1;
            $condition['AND']['Product.forsale'] = 1;
            if (isset($_GET['key']) && !empty($_GET['key'])) {
                $product_keyword = $_GET['key'];
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
            $condition['Product.status'] = 1;
            $condition['Product.forsale'] = 1;
            $proInfos = $this->Product->find('all', array('conditions' => $condition, 'limit' => $this->limitNum, 'page' => $pageNum, 'order' => $order));
            $homeProInfos = $this->Product->find('all', array('conditions' => array('Product.status' => 1, 'Product.recommand_flag' => 1), 'limit' => 15, 'order' => 'Product.created desc'));
            $Products = '';
            $HomeProducts = '';
            $config_info = $this->Config->getformatcode();

            if (!empty($proInfos)) {
                foreach ($proInfos as $k => $v) {
                    $Products[$k]['id'] = $v['Product']['id'];
                    $Products[$k]['name'] = $v['ProductI18n']['name'];
                    $Products[$k]['img_thumb'] = $v['Product']['img_thumb'];
                    if ($v['Product']['shop_price'] > 0) {
                        $Products[$k]['shop_price'] = sprintf($config_info['price_format'], $v['Product']['shop_price']);
                    }
                    $Products[$k]['desc'] = $v['ProductI18n']['description02'];
                }
            }
            if (!empty($homeProInfos)) {
                foreach ($homeProInfos as $k => $v) {
                    $HomeProducts[$k]['id'] = $v['Product']['id'];
                    $HomeProducts[$k]['name'] = $v['ProductI18n']['name'];
                    $HomeProducts[$k]['img_thumb'] = $v['Product']['img_thumb'];
                    if ($v['Product']['shop_price'] > 0) {
                        $HomeProducts[$k]['shop_price'] = sprintf($config_info['price_format'], $v['Product']['shop_price']);
                    }
                }
            }
            if (($this->limitNum * $pageNum) < count($this->Product->find('all', array('conditions' => $condition)))) {
                $result['show'] = true;
            } else {
                $result['show'] = false;
            }
            $result['code'] = 1;
            $result['msg'] = 'Success';
            $result['Products'] = $Products;
            if (!isset($_GET['catId'])) {
                $result['HomeProducts'] = $HomeProducts;
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
        //商品详细页
        public function proView()
        {
            $pId = $_REQUEST['pId'];
            $product_info = $this->Product->find('first', array('conditions' => array('Product.id' => $pId)));
            $last_product_info = $this->Product->find('first', array('conditions' => array('Product.status' => 1, 'Product.category_id' => $product_info['Product']['category_id'], 'Product.created >' => $product_info['Product']['created']), 'fields' => 'Product.shop_price,Product.id,Product.img_detail,ProductI18n.name', 'limit' => 1, 'order' => 'Product.created asc'));
            $next_product_info = $this->Product->find('first', array('conditions' => array('Product.status' => 1, 'Product.category_id' => $product_info['Product']['category_id'], 'Product.created <' => $product_info['Product']['created']), 'fields' => 'Product.shop_price,Product.id,Product.img_detail,ProductI18n.name', 'limit' => 1, 'order' => 'Product.created desc'));
            $result['last_product_id'] = '';
            $result['next_product_id'] = '';
            $config_info = $this->Config->getformatcode();
            //获取上一个商品的id 和 下一个商品的 id
            if (!empty($last_product_info)) {
                $result['last_product_id'] = $last_product_info['Product']['id'];
                $result['last_product_name'] = $last_product_info['ProductI18n']['name'];
                $result['last_product_img'] = $last_product_info['Product']['img_detail'];
                $result['last_product_price'] = $last_product_info['Product']['shop_price'];
                if ($last_product_info['Product']['shop_price'] > 0) {
                    $result['last_product_price'] = sprintf($config_info['price_format'], $last_product_info['Product']['shop_price']);
                }
            }
            if (!empty($next_product_info)) {
                $result['next_product_id'] = $next_product_info['Product']['id'];
                $result['next_product_name'] = $next_product_info['ProductI18n']['name'];
                $result['next_product_img'] = $next_product_info['Product']['img_detail'];
                if ($next_product_info['Product']['shop_price'] > 0) {
                    $result['next_product_price'] = sprintf($config_info['price_format'], $next_product_info['Product']['shop_price']);
                }
            }
            if (!empty($product_info)) {
                $imgpath = empty($product_info['Product']['img_thumb']) ? 'images/default.jpg' : $product_info['Product']['img_thumb'];
                $result['product_img'] = $imgpath;
                $result['product_name'] = $product_info['ProductI18n']['name'];
                $result['product_code'] = $product_info['Product']['code'];
                if ($product_info['Product']['shop_price'] > 0) {
                    $result['product_price'] = sprintf($config_info['price_format'], $product_info['Product']['shop_price']);
                }
                if ($product_info['Product']['market_price'] > 0) {
                    $result['product_market_price'] = sprintf($config_info['price_format'], $product_info['Product']['market_price']);
                }
                $result['product_quantity'] = $product_info['Product']['quantity'];
                $result['product_desc'] = $product_info['ProductI18n']['description02'];

                //商品相册
                $show_gallery_number = 5;
                $galleries = $this->ProductGallery->find('all', array('conditions' => array('ProductGallery.product_id' => $pId), 'order' => 'ProductGallery.orderby ASC , ProductGallery.img_thumb ASC', 'limit' => $show_gallery_number, 'recursive' => -1));
                if (!empty($galleries)) {
                    $result['galleries'] = $galleries;
                }
                //查看是否装了淘宝应用 装了
                $app_infos = $this->Application->init2();
                if (in_array('APP-SHOP', $this->all_app_codes)) {
                    $result['taobao'] = true;
                    $this->loadModel('TaobaoItem');
                    $ti_info = $this->TaobaoItem->find('first', array('conditions' => array('TaobaoItem.outer_id' => $product_info['Product']['code'])));
                    if (!empty($ti_info)) {
                        $result['taobao_product_id'] = $ti_info['TaobaoItem']['num_iid'];
                    } else {
                    }
                } else {
                    $result['taobao'] = false;
                }
                $x = ClassRegistry::init('ApplicationConfig')->find('first', array('conditions' => array('ApplicationConfig.code' => 'APP-MOBILE-WAP-BUY')));
                if (!empty($x)) {
                    foreach ($x['ApplicationConfigI18n'] as $x) {
                        if ($x['value'] != '') {
                            $buy = $x['value'];
                        }
                    }
                }
                if (isset($buy)) {
                    $result['buy'] = true;
                    $result['bh'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.$product_info['ProductI18n']['name'].'-P'.$pId.'.html';
                    //商---品--名称--------1---281-P1783.html
                } else {
                    $result['buy'] = false;
                }
                $result['code'] = 1;
            } else {
                $result['code'] = 0;
            }

            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }

        //提交反馈
        public function checkFeedback()
        {
            $contact_info['Contact']['company'] = $_REQUEST['ContactCompany'];
            $contact_info['Contact']['company_url'] = $_REQUEST['ContactDomain'];
            $contact_info['Contact']['company_type'] = $_REQUEST['ContactCompanyType'];
            $contact_info['Contact']['contact_name'] = $_REQUEST['ContactContactName'];
            $contact_info['Contact']['email'] = $_REQUEST['ContactEmail'];
            $contact_info['Contact']['mobile'] = $_REQUEST['ContactMobile'];
            $contact_info['Contact']['qq'] = $_REQUEST['ContactQQ'];
            $contact_info['Contact']['msn'] = $_REQUEST['ContactMSN'];
            $contact_info['Contact']['skype'] = $_REQUEST['ContactSkype'];
            $contact_info['Contact']['content'] = $_REQUEST['ContactContent'];
            //$contact_info['Contact']['from'] = $_REQUEST['ContactFrom'];
            $contact_info['Contact']['locale'] = $this->locale;
            if ($this->Contact->save($contact_info)) {
                $result['code'] = 1;
                $result['msg'] = '提交成功!';
                $app_infos = $this->Application->init2($this->locale);
                $app_infos = $app_infos['Applications'];
                //如果装过邮件模版
                if (isset($app_infos['APP-VIP']) && $app_infos['APP-VIP']['configs']['APP-CONTACTS-EMAIL'] != '') {
                    $send_date = date('Y-m-d');
                    $email_text = '公司名称:'.$contact_info['Contact']['company'].'<br>';
                    $email_text .= '网址:'.$contact_info['Contact']['company_url'].'<br>';
                    $email_text .= '行业:'.$contact_info['Contact']['company_type'].'<br>';
                    $email_text .= '联系人:'.$contact_info['Contact']['contact_name'].'<br>';
                    $email_text .= '邮箱:'.$contact_info['Contact']['email'].'<br>';
                    $email_text .= 'QQ:'.$contact_info['Contact']['qq'].'<br>';
                    $email_text .= 'MSN:'.$contact_info['Contact']['msn'].'<br>';
                    $email_text .= 'SKYPE:'.$contact_info['Contact']['skype'].'<br>';
                    //$email_text.='如何获知:'.$contact_info['Contact']['from'].'<br>';
                    $email_text .= '留言:'.$contact_info['Contact']['content'].'<br><br>';
                    $email_text .= '日期:'.$send_date;
                    $template = $this->MailTemplate->find("code = 'contact_us' and status = 1");
                    $template_str = $template['MailTemplateI18n']['html_body'];
                    $this->Email->smtpHostNames = ''.$this->configs['mail-smtp'].'';
                    $this->Email->smtpUserName = ''.$this->configs['mail-account'].'';
                    $this->Email->smtpPassword = ''.$this->configs['mail-password'].'';
                    $this->Email->is_ssl = $this->configs['mail-ssl'];
                    $this->Email->is_mail_smtp = $this->configs['mail-service'];
                    $this->Email->smtp_port = $this->configs['mail-port'];
                    $this->Email->from = ''.$this->configs['mail-account'].'';
                    //从应用表查处要发的邮件地址
                    $this->Email->to = ''.$contact_info['Contact']['email'].'';
                    $shopName = $this->Config->find('first', array('conditions' => array('Config.code' => 'shop_name')));
                    $this->Email->fromName = $shopName;
                    $this->Email->html_body = ''.$template_str.'';
                    $text_body = $template['MailTemplateI18n']['text_body'];
                    $this->Email->text_body = $text_body;
                    $subject = $template['MailTemplateI18n']['title'];
                    eval("\$subject = \"$subject\";");
                    $mail_send_queue = array(
                                            'id' => '',
                                            'sender_name' => $shopName['ConfigI18n']['value'],
                                            'receiver_email' => $app_infos['APP-VIP']['configs']['APP-CONTACTS-EMAIL'].';'.$app_infos['APP-VIP']['configs']['APP-CONTACTS-EMAIL'],
                                            'cc_email' => ';',
                                            'bcc_email' => ';',
                                            'title' => '联系我们通知邮件',
                                            'html_body' => $email_text,
                                            'text_body' => $email_text,

                                            'sendas' => 'html',
                                            'flag' => 0,
                                            'pri' => 0,
                                       );
                    $this->Email->send_mail($this->locale, $this->configs['mail-encode'], $mail_send_queue);
                }
            } else {
                $result['code'] = 2;
                $result['msg'] = '提交失败,请重试!';
            }
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }

        //获取相关商店设置数据
        public function getSystemConfigs()
        {
            //$this->Config->set_locale($this->locale);
            //商店颜色数据  wap专用
            $color_info = $this->Config->find('first', array('conditions' => array('Config.code' => 'shop_wap_color')));
            if (!empty($color_info) && $color_info['ConfigI18n']['value'] != '') {
                $result['shop_color'] = $color_info['ConfigI18n']['value'];
            } else {
                $result['shop_color'] = '';
            }
            //查看联系我们安装了没..
            $info = $this->Application->find('first', array('conditions' => array('Application.code' => 'APP-VIP', 'Application.status' => 1)));
            if (empty($info)) {
                $result['contact'] = false;
            } else {
                $result['contact'] = true;
            }
            //查看颜色配置
            $theme_info = $this->MobileAppThemes->find('first', array('conditions' => array('MobileAppThemes.id' => 1)));
            if (!empty($theme_info)) {
                $result['theme_info'] = $theme_info['MobileAppThemes'];
            }
            $result['code'] = 1;
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }

        //获取行业 如何获知我们
        public function getFeedbackPageInfo()
        {
            $app_infos = $this->Application->init2($this->locale);
            $contact_info = $app_infos['Applications'];
            $industry = explode("\n", isset($contact_info['APP-VIP']['configs']['APP-CONTACTS-INDUSTRY']) ? $contact_info['APP-VIP']['configs']['APP-CONTACTS-INDUSTRY'] : '');
            $learn_us = explode("\n", isset($contact_info['APP-VIP']['configs']['APP-CONTACTS-LEARN-US']) ? $contact_info['APP-VIP']['configs']['APP-CONTACTS-LEARN-US'] : '');
            $result['industry'] = array_filter($industry);
            $result['learn_us'] = array_filter($learn_us);
            $result['code'] = 1;
            $callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
            $result = $callback.'('.json_encode($result).')';
            Configure::write('debug', 1);
            die($result);
        }
}
