<?php

/*****************************************************************************
 * Seevia 评论
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 CommentsController 的评论控制器.
 */
class CommentsController extends AppController
{
    /*
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses
    */
    public $name = 'Comments';
    public $helpers = array('Html','Pagination');
    public $uses = array('Comment','User','Cache','Product','Article','ProductsCategory','CategoryProduct');
    public $components = array('RequestHandler','Captcha','Pagination');

    /**
     *显示评论.
     *
     *@param $page
     */
    public function index($page = 1, $limit = 5)
    {

        //登录验证
        $this->checkSessionUser();

        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                        //页面初始化 

        $this->pageTitle = $this->ld['account_reviews'].' - '.$this->configs['shop_title'];

        //当前位置 
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['account_reviews'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        //获取我的评论
        //$condition=array(" 1=1 and Comment.parent_id = '0'  and Comment.status = '1' and Comment.user_id =".$_SESSION['User']['User']['id']);
        $condition['and']['Comment.status'] = '1';
        $condition['and']['Comment.user_id'] = $_SESSION['User']['User']['id'];
        $condition['and']['Comment.parent_id'] = '0';
        $condition['or'][]['Comment.type'] = 'P';
        $condition['or'][]['Comment.type'] = 'A';
        //$condition['or']['Comment.type'] = 'A';
        //分页start
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'comments', 'action' => 'index', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'Comment');
        $page = $this->Pagination->init($condition, $parameters, $options); // Added
        //分页end
        $my_comments = $this->Comment->get_comments($condition, $limit, $page);//获取我的评论
        $pro_comments = array();
        //获取购买过但未评论的商品
    if (constant('Product') == 'AllInOne') {
        $my_orders = $this->Order->find('all', array('conditions' => array('Order.user_id' => $_SESSION['User']['User']['id'], 'Order.status' => '1', 'Order.shipping_status' => '2', 'Order.payment_status' => '2')));
        foreach ($my_orders as $k => $v) {
            foreach ($v['OrderProduct'] as $kk => $vv) {
                $counts = $this->Comment->find('count', array('conditions' => array('Comment.type_id' => $vv['product_id'], 'Comment.type' => 'P', 'Comment.status' => 1, 'Comment.user_id' => $_SESSION['User']['User']['id'])));//获取我的评论
                 if ($counts == 0) {
                     $pro_first = $this->Product->find('first', array('conditions' => array('Product.id' => $vv['product_id']), 'fields' => 'Product.img_thumb,Product.img_detail'));//获取我的评论
                    $pro_comments[$vv['id']] = $vv;
                     $pro_comments[$vv['id']]['product_img_thumb'] = $pro_first['Product']['img_thumb'];
                     $pro_comments[$vv['id']]['product_img_detail'] = $pro_first['Product']['img_detail'];
                    //获取回复数量
                    $pro_comments[$vv['id']]['count'] = $this->Comment->find('count', array('conditions' => array('Comment.type_id' => $vv['product_id'], 'Comment.type' => 'P', 'Comment.status' => 1)));//获取我的评论
                 }
            }
        }
    }
        if (empty($my_comments)) {
            $my_comments = array();
        }
        $p_ids = array();
        $p_ids[] = 0;
        $a_ids = array();
        $a_ids[] = 0;
       //获取商品编号和评论编号  
       foreach ($my_comments as $k => $v) {
           if ($v['Comment']['type'] == 'P') {
               $p_ids[] = $v['Comment']['type_id'];
           }
           if ($v['Comment']['type'] == 'A') {
               $a_ids[] = $v['Comment']['type_id'];
           }
       }
       //获取我评论的商品信息
       $product_infos = $this->Product->find('all', array('conditions' => array('Product.id' => $p_ids), 'fields' => 'Product.id,ProductI18n.name'));
        $products_list = array();
        if (is_array($product_infos) && sizeof($product_infos) > 0) {
            foreach ($product_infos as $k => $v) {
                $products_list[$v['Product']['id']] = $v;
            }
        }
       //获取我评论的文章信息
       $article_infos = $this->Article->find('all', array('conditions' => array('Article.id' => $a_ids), 'fields' => 'Article.id,ArticleI18n.title'));
        $articles_list = array();
        if (is_array($article_infos) && sizeof($article_infos) > 0) {
            foreach ($article_infos as $k => $v) {
                $articles_list[$v['Article']['id']] = $v;
            }
        }
       //获取评论过还存在的商品
       foreach ($my_comments as $k => $v) {
           if ($v['Comment']['type'] == 'P' && isset($products_list[$v['Comment']['type_id']])) {
               $my_comments[$k]['Product'] = $products_list[$v['Comment']['type_id']]['Product'];
               $my_comments[$k]['ProductI18n'] = $products_list[$v['Comment']['type_id']]['ProductI18n'];
           }
           if ($v['Comment']['type'] == 'A' && isset($articles_list[$v['Comment']['type_id']])) {
               $my_comments[$k]['Article'] = $articles_list[$v['Comment']['type_id']]['Article'];
               $my_comments[$k]['ArticleI18n'] = $articles_list[$v['Comment']['type_id']]['ArticleI18n'];
           }
           //获取该评论的回复数量
           $reply_info = $this->Comment->find('first', array('conditions' => array('Comment.parent_id' => $v['Comment']['id'], 'Comment.status' => 1), 'fields' => 'Comment.content,Comment.name,Comment.created'));
           if (!empty($reply_info)) {
               $my_comments[$k]['Comment']['replay_info'] = $reply_info['Comment'];
           }
           //$my_comments[$k]['Comment']['replay']=$this->Comment->find("count",array("conditions"=>array("Comment.parent_id"=>$v['Comment']['id'],"Comment.status"=>1)));
           $start_time = mktime(0, 0, 0, date('m', strtotime($v['Comment']['created'])), date('d', strtotime($v['Comment']['created'])), date('Y', strtotime($v['Comment']['created'])));            //开始时间
           $end_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));            //结束时间
           $times = $end_time - $start_time;               //开始与结束之间相差多少秒
           $my_comments[$k]['Comment']['now_day'] = $times / (24 * 3600);                    //得出一共有多少
       }
        $this->set('my_comments', $my_comments);
        $this->set('pro_comments', $pro_comments);
    }
    //添加评论
    /**
    *添加评论.
    */
    public function add()
    {
        if ($this->RequestHandler->isPost()) {
            $result['message'] = $this->ld['add'].$this->ld['reviews'].$this->ld['failed'];
            $status = 0;
            $no_error = 1;
            if (isset($this->configs['comment_captcha']) && $this->configs['comment_captcha'] == 1 && isset($_POST['captcha']) && $this->captcha->check($_POST['captcha']) == false) {
                $no_error = 0;
                $result['message'] = $this->ld['verify_code'].$this->ld['not_correct'];
            }
            if (isset($this->configs['enable_user_comment_check']) && $this->configs['enable_user_comment_check'] == 0) {
                $status = 1;
            }
            if (!isset($_POST['is_ajax'])) {
                $comment = $_POST['data']['Comment'];
                $comment['ipaddr'] = $this->RequestHandler->getClientIP();
                $comment['status'] = $status;
                if ($comment['email'] == '' || !$this->is_email($comment['email'])) {
                    $no_error = 0;
                    $result['message'] = $this->ld['e-mail_incorrectly'];
                } elseif ($comment['rank'] == '') {
                    $no_error = 0;
                    $result['message'] = $this->ld['please_select'].$this->ld['comment_rank'];
                } elseif ($comment['content'] == '') {
                    $no_error = 0;
                    $result['message'] = $this->ld['reviews'].$this->ld['can_not_empty'];
                }
            } else {
                $comment = array(
                    'type' => isset($_POST['type'])   ? trim($_POST['type']) : '',
                    'type_id' => isset($_POST['id'])   ? intval($_POST['id'])  : 0,
                    'email' => isset($_POST['email'])   ? trim($_POST['email'])  : '',
                    'status' => $status,//评论是否要审核
                    'content' => isset($_POST['content'])   ? trim($_POST['content'])  : '',
                    'user_id' => isset($_POST['user_id'])   ? intval($_POST['user_id'])  : 0,
                    'name' => isset($_POST['username'])   ? trim($_POST['username'])  : '',
                    'rank' => isset($_POST['rank'])   ? intval($_POST['rank'])  : 0,
                    'ipaddr' => $this->RequestHandler->getClientIP(),
                    );
            }
            if ($no_error) {
                $this->Comment->save(array('Comment' => $comment));
                $result['type'] = '0';
                $result['message'] = $this->ld['add'].$this->ld['reviews'].$this->ld['successfully'];
            } else {
                $result['type'] = '1';
            }
        } else {
            $result['type'] = '1';
            $result['message'] = $this->ld['invalid_operation'];
        }
        if (!isset($_POST['is_ajax'])) {
            $this->page_init();
            $id = isset($comment['type_id']) ? $comment['type_id'] : '';
            $url = ($comment['type_id'] == 'P') ? '/products/' : '/articles/';
            $this->pageTitle = isset($result['message']) ? $result['message'] : ''.' - '.$this->configs['shop_title'];
            $this->flash(isset($result['message']) ? $result['message'] : '', $url.$id, 10);
        }

        $this->set('result', $result);
        $this->layout = 'ajax';
    }
    /**
     *邮件.
     *
     *@param $user_email
     */
    public function is_email($user_email)
    {
        $chars = '/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}$/i';
        if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false) {
            if (preg_match($chars, $user_email)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     *切割字符.
     *
     *@param $str
     *@param $length
     *@param $append
     */
    public function sub_str2($str, $length = 0, $append = true)
    {
        $str = trim($str);
        $strlength = strlen($str);

        if ($length == 0 || $length >= $strlength) {
            return $str;
        } elseif ($length < 0) {
            $length = $strlength + $length;
            if ($length < 0) {
                $length = $strlength;
            }
        }

        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, 0, $length, 'utf-8');
        } elseif (function_exists('iconv_substr')) {
            $newstr = iconv_substr($str, 0, $length, 'utf-8');
        } else {
            //$newstr = trim_right(substr($str, 0, $length));
            $newstr = substr($str, 0, $length);
        }

        if ($append && $str != $newstr) {
            $newstr .= '...';
        }

        return $newstr;
    }
/**
 *切割字符.
 *
 *@param $string
 *@param $length
 *@param $dot
 */
public function sub_str($string, $length, $dot = ' ...')
{
    global $charset;
    $oldstr = strlen($string);
    if (strlen($string) <= $length) {
        return $string;
    }

    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
    if (function_exists('mb_substr')) {
        $string = mb_substr($string, 0, $length, 'utf-8');
        $charset = 'utf-8';
    } elseif (function_exists('iconv_substr')) {
        $string = iconv_substr($string, 0, $length, 'utf-8');
        $charset = 'utf-8';
    }
    $strcut = '';
    if (strtolower($charset) == 'utf-8') {
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                ++$n;
                ++$noc;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                ++$n;
            }

            if ($noc >= $length) {
                break;
            }
        }
        if ($noc > $length) {
            $n -= $tn;
        }

        $strcut = substr($string, 0, $n);
    } else {
        for ($i = 0; $i < $length; ++$i) {
            $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
        }
    }

    $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
    if ($oldstr > strlen($strcut)) {
        return $strcut.$dot;
    }

    return $strcut;
}

/**
 *函数 user_del_my_comments 用于删除评论.
 *
 *@param $comments_id
 */
public function user_del_my_comments($comments_id)
{
    $this->Comment->del($comments_id);
    //显示的页面
    $this->redirect('/comments/');
}

    /**
     *函数 user_product_comment 用于获取所购商品的分类信息.
     *
     *@param $rownum
     *@param $showtype
     *@param $orderby
     */
    public function user_product_comment($rownum = '', $showtype = '', $orderby = '')
    {
        $orderby = UrlDecode($orderby);
        $rownum = UrlDecode($rownum);
        $showtype = UrlDecode($showtype);
            //未登录转登录页
            if (!isset($_SESSION['User'])) {
                //	echo "111111111111";exit;
                 $this->redirect('/login/');
            }
        $this->page_init();

            //当前位置
            $this->ur_heres[] = array('name' => __($this->ld['purchased'].$this->ld['information'], true),'url' => '');
        $this->set('ur_heres', $this->ur_heres);

        $user_id = $_SESSION['User']['User']['id'];
        if (empty($rownum)) {
            $rownum = isset($this->configs['products_list_num']) ? $this->configs['products_list_num'] : ((!empty($rownum)) ? $rownum : 20);
        }
        if (empty($showtype)) {
            $showtype = isset($this->configs['products_list_showtype']) ? $this->configs['products_list_showtype'] : ((!empty($showtype)) ? $showtype : 'L');
        }
        if (empty($orderby)) {
            $orderby = isset($this->configs['products_category_page_orderby_type']) ? $this->configs['products_category_page_orderby_type'].' '.$this->configs['products_category_page_orderby_method'] : ((!empty($orderby)) ? $orderby : 'created '.$this->configs['products_category_page_orderby_method']);
        }
            //取得我的所有订单id
            $condition = ' user_id='.$user_id;
        $my_orders = $this->Order->findAll($condition);
        $orders_id = array();
        foreach ($my_orders as $k => $v) {
            $orders_id[$k] = $v['Order']['id'];
        }
        if (empty($orders_id)) {
            $orders_id[] = 0;
        }
            // pr($orders_id);
            //取得我购买的商品
            $condition = array('OrderProduct.order_id' => $orders_id," ProductI18n.locale='".LOCALE."' ");
        $total = $this->OrderProduct->find('count', array('conditions' => $condition));
        $sortClass = 'OrderProduct';
        $page = 1;
        $parameters = array($rownum,$page);
        $options = array();
        $page = $this->Pagination->init($condition, '', $options, $total, $rownum, $sortClass);
           // $my_orders_products=$this->OrderProduct->findAll($condition,'',"","$rownum",$page);
            $my_orders_products = $this->OrderProduct->get_orders_products($condition, $orderby, $rownum, $page);

          //  pr($my_orders_products);

            if (empty($my_orders_products)) {
                $my_orders_products = array();
            }
           // pr($my_orders_products);
            //商品品牌分类
           $res_c = $this->CategoryProduct->findassoc(LOCALE);
        $res_b = $this->Brand->findassoc(LOCALE);

        $products_ids_list = array();
        $orders_ids_list = array();
        $orders_ids_list[] = 0;
        $products_ids_list[] = 0;
        if (is_array($my_orders_products) && sizeof($my_orders_products) > 0) {
            foreach ($my_orders_products as $k => $v) {
                $products_ids_list[] = $v['OrderProduct']['product_id'];
                $orders_ids_list[] = $v['OrderProduct']['order_id'];
            }
        }

            //products_ids_list
            if (!empty($products_ids_list)) {
                $products_comment_conditions = array('Comment.type_id' => $products_ids_list,
                                                    'Comment.type' => 'P',
                                                    'Comment.user_id' => $_SESSION['User']['User']['id'],
                                                    'Comment.parent_id' => 0, );
                $products_comment = $this->Comment->get_products_comment($products_comment_conditions);
                $my_comments_id[] = 0;
                if (isset($products_comment) && sizeof($products_comment) > 0) {
                    foreach ($products_comment as $k => $v) {
                        $my_comments_id[] = $v['Comment']['id'];
                    }
                }
                $my_comments_replies = $this->Comment->find('all', array('conditions' => array('Comment.parent_id' => $my_comments_id)));
                $replies_list = array();
                if (is_array($my_comments_replies) && sizeof($my_comments_replies) > 0) {
                    foreach ($my_comments_replies as $kk => $vv) {
                        $replies_list[$vv['Comment']['parent_id']][] = $vv;
                    }
                }
                $products_comment_list = array();
                if (isset($products_comment) && sizeof($products_comment) > 0) {
                    foreach ($products_comment as $k => $v) {
                        if (isset($replies_list[$v['Comment']['id']])) {
                            $products_comment[$k]['Reply'] = $replies_list[$v['Comment']['id']];
                        }
                        $products_comment_list[$v['Comment']['type_id']][] = $products_comment[$k];
                    }
                }
            }
        $this->set('products_comment_list', $products_comment_list);
        $order_ids_conditions = array('Order.id' => $orders_ids_list);
        $p_order_infos = $this->Order->get_order_infos($order_ids_conditions);
        $order_lists = array();
        if (is_array($p_order_infos) && sizeof($p_order_infos) > 0) {
            foreach ($p_order_infos as $k => $v) {
                $order_lists[$v['Order']['id']] = $v;
            }
        }

        $product_category_conditions = array('ProductsCategory.product_id' => $products_ids_list);
        $product_category_infos = $this->ProductsCategory->get_product_category_infos($product_category_conditions);

        $product_category_lists = array();

        if (is_array($product_category_infos) && sizeof($product_category_infos) > 0) {
            foreach ($product_category_infos as $k => $v) {
                $product_category_lists[$v['ProductsCategory']['product_id']] = $v;
            }
        }

        foreach ($my_orders_products as $k => $v) {
            //$order_info = $this->Order->findbyid($v['OrderProduct']['order_id']);
                if (isset($order_lists[$v['OrderProduct']['order_id']])) {
                    $order_info = $order_lists[$v['OrderProduct']['order_id']];
                }

            $my_orders_products[$k]['OrderProduct']['order_code'] = isset($order_info['Order']['id']) ? $order_info['Order']['id'] : '';

            if (isset($product_category_lists[$v['Product']['id']])) {
                $product_category = $product_category_lists[$v['Product']['id']];
            }

            if (isset($product_category) && isset($res_c[$product_category['ProductsCategory']['id']]['Category']['id'])) {
                $my_orders_products[$k]['Category'] = $res_c[$res_c[$product_category['ProductsCategory']['id']]['Category']['id']]['Category'];
                $my_orders_products[$k]['CategoryI18n'] = $res_c[$res_c[$product_category['ProductsCategory']['id']]['Category']['id']]['CategoryI18n'];
            }
            if (isset($res_b[$v['Product']['brand_id']]['Brand']['id'])) {
                $my_orders_products[$k]['Brand'] = $res_b[$v['Product']['brand_id']]['Brand'];
                $my_orders_products[$k]['BrandI18n'] = $res_b[$v['Product']['brand_id']]['BrandI18n'];
            }
            if ($v['Product']['id'] == '') {
                unset($my_orders_products[$k]);
            }
        }

        $this->pageTitle = $this->ld['purchased'].$this->ld['information'].' - '.$this->configs['shop_title'];
              //一步购买
          if (!empty($this->configs['enable_one_step_buy']) && $this->configs['enable_one_step_buy'] == 1) {
              $js_languages = array('enable_one_step_buy' => '1','page_number_expand_max' => $this->ld['page_number'].$this->ld['not_exist']);
              $this->set('js_languages', $js_languages);
          } else {
              $js_languages = array('enable_one_step_buy' => '0','page_number_expand_max' => $this->ld['page_number'].$this->ld['not_exist']);
              $this->set('js_languages', $js_languages);
          }
    //	  pr($my_orders_products);
          $this->set('my_orders_products', $my_orders_products);
        $this->set('total', $total);
        $this->set('user_id', $user_id);
          //排序方式,显示方式,分页数量限制
          $this->set('orderby', $orderby);
        $this->set('rownum', $rownum);
        $this->set('showtype', $showtype);
    }
}
