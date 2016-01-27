<?php

/*****************************************************************************
 * Seevia 用户收藏管理
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
 *这是一个名为 UserLikesController 的控制器
 *后台用户收藏管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class UserLikesController extends AppController
{
    public $name = 'UserLikes';
    public $components = array('Pagination','RequestHandler','Phpexcel','Orderfrom');
    public $helpers = array('Pagination');
    public $uses = array('UserLike','Product','Resource','User','Application','OperatorLog');

    /**
     * 函数index,用于显示收藏列表.
     *
     * @param $page 当前页
     */
    public function index($page = 1)
    {
        $this->operator_privilege('user_likes_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/user_likes/');
        $this->navigations[] = array('name' => $this->ld['user_like'],'url' => '');
        $condition = array();
        //喜欢搜索条件
        if (isset($_REQUEST['user']) && $_REQUEST['user'] != '') {
            $user_keyword = isset($_REQUEST['user']) ? $_REQUEST['user'] : '';
            $conditions2 = array();
            $conditions2['User.name like'] = '%'.$user_keyword.'%';
            $user_list = $this->User->find('all', array('conditions' => $conditions2));
            $ids = array();
            foreach ($user_list as $k => $v) {
                $ids[$k] = $v['User']['id'];
            }
            $condition['UserLike.user_id'] = $ids;
            $this->set('user_keyword', $_REQUEST['user']);
        }
        if (isset($_REQUEST['action']) && $_REQUEST['action'] != '0') {
            if ($_REQUEST['action'] == '0-cart') {
                $condition['UserLike.action'] = 'cart';
                $condition['UserLike.type_id'] = '0';
            } else {
                if ($_REQUEST['action'] == '0-like') {
                    $condition['UserLike.action'] = 'like';
                    $condition['UserLike.type_id'] = '0';
                } else {
                    $condition['UserLike.action'] = 'like';
                    $condition['UserLike.type_id'] = '8';
                }
            }
            $this->set('action', $_REQUEST['action']);
        }
        if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') {
            $condition['UserLike.created  >='] = $_REQUEST['start_date'];
            $this->set('start_date', $_REQUEST['start_date']);
        }
        if (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') {
            $condition['UserLike.created  <='] = $_REQUEST['end_date'].' 23:59:59';
            $this->set('end_date', $_REQUEST['end_date']);
        }
        $total = $this->UserLike->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'UserChat';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'users','action' => 'userlike','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserLike');
        $this->Pagination->init($condition, $parameters, $options);
        $like_list = $this->UserLike->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'created desc'));
        $user = array();
        foreach ($like_list as $k => $v) {
            $user[] = $this->User->find('all', array('conditions' => array('User.id' => $v['UserLike']['user_id'])));
        }
        $product = array();
        foreach ($like_list as $k => $v) {
            $product[$k] = $this->Product->find('first', array('conditions' => array('Product.id' => $v['UserLike']['type'])));
        }
        $this->set('like_list', $like_list);
        $this->set('product', $product);
        $this->set('user', $user);
        //设置页面标题
        $this->set('title_for_layout', $this->ld['user_like'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    /**
     * 函数remove,用于删除收藏记录.
     *
     * @param $id 收藏记录Id
     */
    public function remove($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if (!$this->operator_privilege('user_likes_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $this->UserLike->deleteAll(array('id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 删除喜欢:id '.$id.' ', $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        die(json_encode($result));
    }

    /**
     * 函数removeAll,用于批量删除收藏记录.
     */
    public function removeAll()
    {
        if ($this->RequestHandler->isPost()) {
            $result['flag'] = 2;
            $chat_checkboxes = $_POST['checkboxes'];
            $this->UserLike->deleteAll(array('UserLike.id' => $chat_checkboxes));
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 删除喜欢', $this->admin['id']);
            }
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $result['flag'] = 1;
            die(json_encode($result));
        }
        $this->redirect('/user_likes/');
    }
}
