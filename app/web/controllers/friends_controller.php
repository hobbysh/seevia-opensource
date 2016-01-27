<?php

/*****************************************************************************
 * Seevia 用户中心我的好友
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
uses('sanitize');

/**
 *这是一个名为 FriendsController 的控制器
 *好友控制器.
 */
class FriendsController extends AppController
{
    /*
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses
    */
    public $name = 'Friends';
    public $components = array('RequestHandler'); // Added 
    public $helpers = array('Html');
    public $uses = array('UserFriend','UserFriendCat','Region');
    /**
     *函数 user_index 用于进入我的好友分组页面.
     */
    public function user_index()
    {
        //未登录转登录页
           if (!isset($_SESSION['User'])) {
               //	echo "111111111111";exit;
                $this->redirect('/login/');
           }
        $this->page_init();
              //当前位置
              $this->ur_heres[] = array('name' => __($this->ld['my_friends'], true),'url' => '');
        $this->set('ur_heres', $this->ur_heres);

        if ($this->RequestHandler->isPost()) {
            $this->page_init();
                   //新增指定分组的好友
                   $mrClean = new Sanitize();
            if (isset($this->params['form']['action_type']) && $this->params['form']['action_type'] == 'insert_contact') {
                $this->pageTitle = $this->ld['add'].$this->ld['successfully'].' - '.$this->configs['shop_title'];
                $this->UserFriend->save($this->data['UserFriend']);
                $this->redirect('/user/friends/');
                           //$this->flash($this->ld['add'].$this->ld['successfully'],'../friends','');
            }
                    //编辑指定分组的好友
                    if (isset($this->params['form']['action_type']) && $this->params['form']['action_type'] == 'edit_contact') {
                        $this->pageTitle = $this->ld['tips_edit_success'].' - '.$this->configs['shop_title'];
                        $this->UserFriend->save($this->data['UserFriend']);
                        $this->redirect('/user/friends/');
                           //$this->flash($this->ld['tips_edit_success'],'../friends','');
                    }
                     //增加分组
                     if (isset($this->params['form']['action_type']) && $this->params['form']['action_type'] == 'insert_cat') {
                         $this->pageTitle = $this->ld['tips_edit_success'].' - '.$this->configs['shop_title'];
                         $this->UserFriendCat->save($this->data['UserFriendCat']);
                         $this->redirect('/user/friends/');
                            //$this->flash($this->ld['tips_edit_success'],'../friends','');
                     }
        }

        $user_id = $_SESSION['User']['User']['id'];
              //取得所有好友的分类列表
              $friend_cat_list = $this->UserFriendCat->get_user_friend($user_id);
              //取得所有的好友
             $friend_list = $this->UserFriend->findAll(" user_id='".$user_id."' ");
        if (isset($friend_list)) {
            $this->set('friend_num', sizeof($friend_list));
        }
              //pr($friend_cat_list);
              foreach ($friend_cat_list as $k => $v) {
                  $this->data['my_friends_list'][$v['UserFriendCat']['id']] = $v;
                  $this->data['my_friends_list'][$v['UserFriendCat']['id']]['user'] = array();
                  $this->data['my_friends_list'][$v['UserFriendCat']['id']]['count'] = 0;
              }
        foreach ($friend_list as $k => $v) {
            $this->data['my_friends_list'][$v['UserFriend']['cat_id']]['user'][$v['UserFriend']['id']] = $v;
            $this->data['my_friends_list'][$v['UserFriend']['cat_id']]['count'] = count($this->data['my_friends_list'][$v['UserFriend']['cat_id']]['user']);
        }
        $js_languages = array('group_name_can_not_empty' => $this->ld['group'].$this->ld['apellation'].$this->ld['can_not_empty'],
                                    'friend_name_not_empty' => $this->ld['friend'].$this->ld['user_name'].$this->ld['can_not_empty'],
                                    'invalid_email' => $this->ld['email'].$this->ld['format'].$this->ld['not_correct'],
                                    'address_detail_not_empty' => $this->ld['address'].$this->ld['can_not_empty'],
                                    'invalid_tel_number' => $this->ld['telephone'].$this->ld['format'].$this->ld['not_correct'],
                                    'friends_in_group_not_cancelled' => $this->ld['friends_in_group_not_cancelled'],
                                       );
        $this->set('js_languages', $js_languages);
        $this->pageTitle = $this->ld['my_friends'].' - '.$this->configs['shop_title'];
        $this->set('friend_cat_list', $friend_cat_list);
        $this->set('user_id', $user_id);
    }

   /**
    *函数 user_del_friends 删除好友.
    */
   public function user_del_friends($friend_id)
   {
       $this->UserFriend->del($friend_id);
        //显示的页面
        $this->redirect('/user/friends/');
   }

   /**
    *函数 user_modifycat 修改分组名称.
    */
   public function user_modifycat($cat_id = '', $new_name = '')
   {
       if ($new_name != '') {
           $is_ajax = 1;
       } else {
           $is_ajax = 0;
       }
       $no_error = 1;
       if (isset($_POST['cat_name'])) {
           $new_name = $_POST['cat_name'];
       }
       if (isset($_POST['cat_id'])) {
           $cat_id = $_POST['cat_id'];
       }
       $new_name = UrlDecode($new_name);
       $cat_info = array(
              'id' => isset($cat_id)   ? intval($cat_id)  : 0 ,
               'cat_name' => isset($new_name)   ? trim($new_name)  : '',
        );
       if (trim($new_name) == '') {
           $no_error = 0;
           $result['msg'] = $this->ld['group'].$this->ld['apellation'].$this->ld['can_not_empty'];
       }
       if ($no_error) {
           $this->UserFriendCat->save(array('UserFriendCat' => $cat_info));
           $result['msg'] = ''.$this->ld['tips_edit_success'].'';
       }
       if ($is_ajax == 0) {
           $this->page_init();
           $this->pageTitle = $result['msg'];
           $flash_url = $this->server_host.$this->user_webroot.'friends';
           $this->flash($result['msg'], $flash_url, 10);
       }
       $this->layout = 'ajax';
   }

   /**
    *函数 user_del_cat 删除分组.
    */
   public function user_del_cat($cat_id)
   {
       $this->UserFriendCat->del($cat_id);
        //显示的页面
        $this->redirect('/user/friends/');
   }
   /**
    *函数 user_add_cat 添加分组.
    */
   public function user_add_cat()
   {
       $flash_url = $this->server_host.$this->user_webroot.'friends';
       $no_error = 1;
       $result['type'] = 2;
       $result['msg'] = $this->ld['add'].$this->ld['failed'];
       if (isset($_SESSION['User']['User'])) {
           $result['type'] = 0;
           if ($this->RequestHandler->isPost()) {
               $cat = array(
                            'id' => '',
                            'cat_name' => $_POST['cat_name'],
                            'user_id' => $_POST['user_id'],
                            );
               $result['msg'] = $this->ld['add'].$this->ld['successfully'];
               if (trim($_POST['cat_name']) == '') {
                   $no_error = 0;
                   $result['msg'] = $this->ld['group'].$this->ld['apellation'].$this->ld['can_not_empty'];
               }

               if ($no_error) {
                   $this->UserFriendCat->save($cat);
               }
           }
       } else {
           $result['msg'] = $this->ld['time_out_relogin'];
           if (!isset($_POST['is_ajax'])) {
               $this->page_init();
               $this->pageTitle = ''.$result['msg'].'';
               $this->flash($result['msg'], $flash_url.'/../', 10);
           }
       }
       if (!isset($_POST['is_ajax'])) {
           $this->page_init();
           $this->pageTitle = ''.$result['msg'].'';
           $this->flash($result['msg'], $flash_url, 10);
       }
       $this->set('result', $result);
       $this->layout = 'ajax';
   }

    /**
     *函数 user_recommend 推荐好友.
     */
    public function user_recommend()
    {
        $result['type'] = 2;
        if (isset($_SESSION['User']['User'])) {
            if ($this->RequestHandler->isPost()) {
                $result['type'] = 0;
            }
        } else {
            $result['msg'] = $this->ld['please_login'].$this->ld['login'];
        }
        $this->set('result', $result);
        $this->layout = 'ajax';
    }
    /**
     *函数 user_new_group 新的好友分组.
     */
    public function user_new_group()
    {
        $result['type'] = 2;
        if (isset($_SESSION['User']['User'])) {
            if ($this->RequestHandler->isPost()) {
                $result['type'] = 0;
                $this->set('user_id', $_SESSION['User']['User']['id']);
            }
        } else {
            $result['msg'] = $this->ld['please_login'].$this->ld['login'];
        }
        $this->set('result', $result);
        $this->layout = 'ajax';
    }
}
