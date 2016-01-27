<?php


uses('sanitize');

/**
 * 这是一个名为 BookingProductsController 的订单控制器.
 */
class BookingProductsController extends AppController
{
    public $name = 'BookingProducts';
    public $uses = array('BookingProduct','Product','ProductI18n','User','UserFans','Blog','UserApp');
    public $helpers = array('Html', 'Ajax','Pagination');
    public $components = array('Pagination');
    public $paginate = array('limit' => 10,'order' => '');

    public function index($page = 1)
    {

        //登录验证
        $this->checkSessionUser();

        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                        //页面初始化 

        //当前位置 
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['account_booking'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);

        $user_id = $_SESSION['User']['User']['id'];
        if (isset($_SESSION['User']['User']['id'])) {
            //分享绑定显示判断
            $app_share = $this->UserApp->app_status();
            $this->set('app_share', $app_share);
            //pr($_SESSION['User']['User']['id']);
            $id = $_SESSION['User']['User']['id'];
            $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
            $this->set('user_list', $user_list);
            //pr($user_list);
            //粉丝数量
            $fans = $this->UserFans->find_fanscount_byuserid($id);
            $this->set('fanscount', $fans);
            //日记数量
            $blog = $this->Blog->find_blogcount_byuserid($id);
            $this->set('blogcount', $blog);
            //关注数量
            $focus = $this->UserFans->find_focuscount_byuserid($id);
            $this->set('focuscount', $focus);
        }
      /***************缺货登记*************/

          $conditions = array();
        $conditions = array('BookingProduct.user_id' => $user_id);

        $myproduct = $this->BookingProduct->find('all', array('conditions' => $conditions, 'order' => 'BookingProduct.created desc'));//ȱƷ

          //查看缺货登记的物品现在的数量
          foreach ($myproduct as $v) {
              $p = $this->Product->find('first', array('conditions' => array('Product.id' => $v['BookingProduct']['product_id'])));
              $quantity[$v['BookingProduct']['product_id']] = $p['Product']['quantity'];
          }
          //已到货商品
          $conditions = array('Product.quantity >' => '0','BookingProduct.user_id' => $user_id);
        $myproducts_y = array();
        $myproducts_y = $this->BookingProduct->find('all', array('conditions' => $conditions, 'order' => 'BookingProduct.created desc'));

        foreach ($myproduct as $k => $v) {
            //判断是否促销产品
                if ($this->Product->is_promotion($v)) {
                    $myproduct[$k]['Product']['off'] = floor((1 - ($v['Product']['promotion_price'] / $v['Product']['shop_price'])) * 100);
                    //$vancl_pro[$k]['Product']['shop_price'] = $vancl_pro[$k]['Product']['promotion_price'];
                }

            $myproduct[$k]['ProductI18n'] = $this->ProductI18n->find('first', array('conditions' => array('ProductI18n.product_id' => $v['Product']['id'])));
        }
        foreach ($myproducts_y as $k => $v) {
            //判断是否促销产品
                if ($this->Product->is_promotion($v)) {
                    $myproducts_y[$k]['Product']['off'] = floor((1 - ($v['Product']['promotion_price'] / $v['Product']['shop_price'])) * 100);
                    //$vancl_pro[$k]['Product']['shop_price'] = $vancl_pro[$k]['Product']['promotion_price'];
                }
            $myproducts_y[$k]['ProductI18n'] = $this->ProductI18n->find('first', array('conditions' => array('ProductI18n.product_id' => $v['Product']['id'])));
        }
        $this->pageTitle = $this->ld['account_booking'].' - '.$page.' - '.$this->configs['shop_title'];//

          if (isset($quantity) && $quantity != '') {
              $this->set('quantity', $quantity);
          }
        $this->set('user_id', $user_id);
        $this->set('myproducts_y', $myproducts_y);
        $this->set('myproduct', $myproduct);
    }

        //批量处理
    public function batch($checked, $obj)
    {
        Configure::write('debug', 0);
        $result['type'] = '0';
        if ($checked != '') {
            if ($obj == 'delete') {
                //ɾ
                $condition['BookingProduct.id'] = explode(',', $checked);
               // $this->BookingProduct->deleteAll($condition);
                   $result['type'] = '1';
            }
        }
        die($result['type']);
    }
    /**
     *函数 del_products_t 用于删除缺货商品.
     *
     *@param $user_id 
     */
    public function del_products_t($user_id)
    {
        //登录验证
        $this->checkSessionUser();

        $this->BookingProduct->delete($user_id);
        //显示的页面
        $this->redirect('/booking_products');
    }
}
