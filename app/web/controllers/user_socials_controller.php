<?php

/*****************************************************************************
 * Seevia 我的推荐
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 UserSocialsController 的推荐商品控制器.
 *////////////////////////////////////////////////////////////////
App::import('Vendor', 'weibo2', array('file' => 'saetv2.php'));
App::import('Vendor', 'qq', array('file' => 'Tencent.php'));
class UserSocialsController extends AppController
{
    public $name = 'UserSocials';
    public $helpers = array('Html','Flash','Cache','Pagination');
    public $uses = array('User','UserFans','UserVisitors','UserAction','Blog','UserLike','Comment','UserChat','UserMessage','UserConfig','SynchroUser','OauthLog','BlockWord','Product','ProductI18n','Topic','TopicI18n','UserApp','SynchroUser','LanguageDictionary');
    public $components = array('RequestHandler','Cookie','Session','Captcha','Email','Pagination');
    //首页  对应首页ctp
    public function index($id = 0, $pagetype = 'all', $page = 1, $limit = 9)
    {
    	 $id=intval($id);
    	 $pagetype=$this->clean_xss($pagetype);
    	 $page=intval($limit);
    	 $page=intval($limit);
    	 $_GET=$this->clean_xss($_GET);
    	 
        $this->page_init();
        $this->layout = 'usersocial';//引入模版
        $this->set('ur_heres', $this->ur_heres);
        //判断用户ID是否是当前登录用户，显示操作按钮与发布框
        $id = isset($id) ? $id : $_SESSION['User']['User']['id'];
        $this->set('id', $id);
        $user = $this->User->find_user_by_id($id);
        if (!empty($user)) {
            $this->pageTitle = $user['User']['name'].$this->ld['user_socials_page_title'].' - '.$this->configs['shop_title'];
            $this->ur_heres[] = array('name' => ($user['User']['name'].$this->ld['user_socials_page_title']), 'url' => '');
        } else {
            $this->flash("<div style='min-height:150px;'><font size='5' color='red'>用户不存在!</font></div>", array('controller' => '/'), '');

            return;
        }
        $this->set('user', $user);
        $this->set('pagetype', $pagetype);

        //表情数组
        $Expression = array('/微笑','/撇嘴','/好色','/发呆','/得意','/流泪','/害羞','/睡觉','/尴尬','/呲牙','/惊讶','/冷汗','/抓狂','/偷笑','/可爱','/傲慢','/犯困','/流汗','/大兵','/咒骂','/折磨/','/衰','/擦汗','/抠鼻','/鼓掌','/坏笑','/左哼哼','/右哼哼','/鄙视','/委屈','/阴险','/亲亲','/可怜','/爱情','/飞吻','/怄火','/回头','/献吻','/左太极');
        $this->set('Expression', $Expression);

        if (isset($_SESSION['User']['User']['id'])) {
            $user_list = $this->UserFans->find('all', array('conditions' => array('UserFans.fan_id' => $_SESSION['User']['User']['id'])));
            $this->set('user_list', $user_list);
        }
        $session_userid = isset($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : 0;
        if ($id != $session_userid && $id != '') {
            //判断该访客是否曾经访问过
            $user_visitor = $this->UserVisitors->find('first', array('conditions' => array('user_id' => $id, 'visitor_id' => $session_userid)));
            if (empty($user_visitor)) {
                //添加访客用户
                $this->data['UserVisitors']['user_id'] = $id;
                $this->data['UserVisitors']['visitor_id'] = $session_userid;
                $this->data['UserVisitors']['url'] = $this->UserVisitors->AbsoluteUrl();
                $this->data['UserVisitors']['modified'] = date('Y-m-d H:i:s');
                $this->data['UserVisitors']['created'] = date('Y-m-d H:i:s');
                $this->UserVisitors->save($this->data['UserVisitors']);
            } else {
                $user_visitor['UserVisitors']['modified'] = date('Y-m-d H:i:s');
                $this->UserVisitors->save($user_visitor);
            }
        }
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($id);
        $this->set('fans', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($id);
        $this->set('blog', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($id);
        $this->set('focus', $focus);

        //同步授权分享图片
        $UserAppImg['QQWeibo'] = 'qq.jpg';
        $UserAppImg['QQ'] = 'qie.png';
        $UserAppImg['SinaWeibo'] = 'sina.png';
        $UserAppImg['Google'] = 'google.png';
        $UserAppImg['Facebook'] = 'bule_face.png';
        //同步授权分享
        $UserApp_list = $this->UserApp->find('all', array('fields' => array('UserApp.type'), 'conditions' => array('UserApp.status' => '1', 'UserApp.type !=' => 'Wechat')));
        //同步授权分享状态
        if (isset($_SESSION['User']['User']['id'])) {
            $SynchroUser_list = $this->SynchroUser->find('all', array('conditions' => array('SynchroUser.User_id' => $_SESSION['User']['User']['id'])));
            foreach ($UserApp_list as $k => $v) {
                $UserApp_list[$k]['status'] = '0';
                $UserApp_list[$k]['img'] = $UserAppImg[$v['UserApp']['type']];
                foreach ($SynchroUser_list as $kk => $vv) {
                    if ($v['UserApp']['type'] == $vv['SynchroUser']['type']) {
                        $UserApp_list[$k]['status'] = $vv['SynchroUser']['status'];
                    }
                }
            }
        }
        $this->set('UserApp_list', $UserApp_list);
        //最新活动
        $topicInfo = $this->Topic->find('all', array('fields' => array('Topic.id', 'TopicI18n.title'), 'limit' => 6, 'conditions' => array('Topic.status' => 1, 'Topic.start_time  <=' => DateTime, 'Topic.end_time >=' => DateTime), 'order' => 'Topic.created desc'));
        $this->set('topicInfo', $topicInfo);

        //访客的id
        $visitors = $this->UserVisitors->find_visitors_byuserid($id);
        $cond['joins'] = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('UserVisitors.visitor_id = User.id'),
                         ), );
        $condition = array('user_id' => $id);
        $cond['conditions'] = $condition;
        $cond['limit'] = 24;
        $cond['order'] = 'modified desc';
        $cond['fields'] = array('UserVisitors.*','User.id','User.img01');
        $visitor_list = $this->UserVisitors->find('all', $cond);
        $this->set('visitor_list', $visitor_list);

        $word = $this->BlockWord->find('all');
        $this->set('word', $word);

        //日记列表(我和我关注人的日志)
        //设置登录者的id
        $user_id = isset($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '';
        $this->set('user_id', $user_id);
        //查询我关注的人的id
        $cond2['joins'] = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('Blog.user_id = User.id'),
                         ), );
        $condition2 = array('Blog.user_id' => $id,'Blog.status' => 1,'Blog.parent_id' => 0);
        $cond2['conditions'] = $condition2;
        $cond2['order'] = 'created desc';
        $cond2['fields'] = array('Blog.*','User.id','User.img01','User.name');
        $blog_list = $this->Blog->find('all', $cond2);

        //对blog_list做处理增加每条日志的评论数量
        foreach ($blog_list as $k => $v) {
            $blog_list[$k]['Blog']['comment_num'] = $this->Blog->find('count', array('conditions' => array('Blog.parent_id' => $v['Blog']['id'])));
        }
        $this->set('blog_list', $blog_list);

        //动作记录列表
        $cond_action['fields'] = array('UserAction.*','User.id','User.img01','User.name');
        $cond_action['conditions']['user_id'] = $id;
        $cond_action['order'] = 'created desc';
        $cond_action['joins'] = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('UserAction.user_id = User.id'),
                         ), );
        $action_list = $this->UserAction->find('all', $cond_action);
        $this->set('action_list', $action_list);

        $all_Info = array();
        $_all_Info = array();
        foreach ($blog_list as $k => $v) {
            $tmp['Info'] = $v;
            $tmp['time'] = $v['Blog']['created'];
            $_all_Info[] = $tmp;
        }
        foreach ($action_list as $k => $v) {
            $tmp['Info'] = $v;
            $tmp['time'] = $v['UserAction']['created'];
            $_all_Info[] = $tmp;
        }
        $_all_Info = $this->array_sort($_all_Info, 'time');
        foreach ($_all_Info as $k => $v) {
            $all_Info[] = $v['Info'];
        }
        $this->set('all_list', $all_Info);

        $this->set('userlikenum', sizeof($all_Info));

        //我的隐私设置（保密：除了自己别人都看不到;仅朋友：自己和自己的粉丝能看;公开:所有人都可以看到）;
        //读取我的隐私设置(like)
        $user_privacyset = $this->UserConfig->find('all', array('conditions' => array('UserConfig.user_id' => $id, 'type' => 'privacy')));
        //未设置隐私默认公开
        if (empty($user_privacyset)) {
            $user_privacyset[0]['UserConfig']['code'] = 'mylike';
            $user_privacyset[0]['UserConfig']['value'] = 2;
            $user_privacyset[1]['UserConfig']['code'] = 'mycomment';
            $user_privacyset[1]['UserConfig']['value'] = 2;
            $user_privacyset[2]['UserConfig']['code'] = 'mylog';
            $user_privacyset[2]['UserConfig']['value'] = 2;
        }
        if (constant('Product') == 'AllInOne') {
            //购买过的商品
            $this->loadModel('Order');
            $buy_order = $this->Order->find('all', array('conditions' => array('Order.payment_status' => '2', 'Order.user_id' => $id)));
            $buy_list = array();
            foreach ($buy_order as $k => $v) {
                foreach ($v['OrderProduct'] as $kk => $vv) {
                    $buy_list[] = $this->Product->find('first', array('conditions' => array('Product.id' => $vv['product_id'])));
                }
            }
            $this->set('buy_list', $buy_list);
        }
        //喜欢宝贝的隐私设置(循环获取),日志的隐私
        $like_set = '';
        $log_set = '';
        $comment_set = '';
        foreach ($user_privacyset as $k => $v) {
            if ($v['UserConfig']['code'] == 'mylike') {
                $like_set = $v['UserConfig']['value'];
            }
            if ($v['UserConfig']['code'] == 'mylog') {
                $log_set = $v['UserConfig']['value'];
            }
            if ($v['UserConfig']['code'] == 'mycomment') {
                $comment_set = $v['UserConfig']['value'];
            }
        }
        $this->set('like_set', $like_set);
        $this->set('log_set', $log_set);
        $this->set('comment_set', $comment_set);
        //当likeset=1时，别的用户访问时判断是否是该userid的粉丝，是的可见，不是隐藏
        $fan_like = $this->UserFans->find('first', array('conditions' => array('UserFans.user_id' => $id, 'UserFans.fan_id' => $user_id)));
        if ($user_id == $id) {
            $fan_like = 1;
            $log_set = 1;
            $comment_set = 1;
        }
        $this->set('fan_like', $fan_like);

        //我所喜欢的宝贝（当前用户id）-连商品表
        if (isset($this->configs['show_product_like']) && $this->configs['show_product_like'] == '1') {
            $cond_pro['joins'] = array(
                                       array('table' => 'svoms_products',
                                          'alias' => 'Product',
                                          'type' => 'inner',
                                          'conditions' => array('UserLike.type_id = Product.id'),
                                         ),
                                );
            $condition_pro = array('UserLike.user_id' => $id,'UserLike.type' => 'P','UserLike.action' => 'like');
            $cond_pro['conditions'] = $condition_pro;
            $cond_pro['limit'] = $limit;
            $cond_pro['page'] = $page;
            $cond_pro['fields'] = array('UserLike.*','Product.id','Product.shop_price','Product.img_original','Product.img_detail');
            $like_list = $this->UserLike->find('all', $cond_pro);

            $product_ids = $this->Product->getproduct_ids($like_list);
            $product_I18N_list = $this->ProductI18n->find('list', array('fields' => array('ProductI18n.product_id', 'ProductI18n.name'), 'conditions' => array('ProductI18n.locale' => $this->locale, 'ProductI18n.product_id' => $product_ids)));
            $comment_num = $this->Comment->find('all', array('conditions' => array('Comment.type_id' => $product_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
            $like_num = $this->UserLike->find('all', array('conditions' => array('UserLike.type' => 'P', 'UserLike.type_id' => $product_ids), 'fields' => array('UserLike.type_id', 'count(UserLike.type_id) as num'), 'group' => 'UserLike.type_id'));
            foreach ($like_list as $k => $v) {
                $like_list[$k]['ProductI18n']['name'] = isset($product_I18N_list[$v['Product']['id']]) ? $product_I18N_list[$v['Product']['id']] : '';
                foreach ($like_num as $like_k => $like_v) {
                    if ($like_list[$k]['Product']['id'] == $like_v['UserLike']['type_id']) {
                        $like_list[$k]['Product']['like_num'] = $like_v[0]['num'];
                    }
                }
                foreach ($comment_num as $com_k => $com_v) {
                    if ($like_list[$k]['Product']['id'] == $com_v['Comment']['type_id']) {
                        $like_list[$k]['Product']['Commentnum'] = $com_v[0]['Commentnum'];
                    }
                }
            }
            $this->set('like_list', $like_list);
            //分页start
            //get参数

            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'user_socials','keyword' => 'index/'.$id.'/'.'baobei','page' => $page);
            //分页参数
            //pr($parameters['route']);
            $page_options = array('page' => $page,'show' => $limit,'modelClass' => 'UserLike');
            $pages = $this->Pagination->init($condition_pro, $parameters, $page_options); // Added
            $this->set('like', $pages);
            //分页
        }
    }

    public function array_sort($arr, $keys, $type = 'desc')
    {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $arr[$k];
        }

        return $new_array;
    }

    //发布操作
    public function release()
    {
        //图片，表情，分享，可能增加对应处理方法
        if ($this->RequestHandler->isPost()) {
            $userid = $_SESSION['User']['User']['id'];
            $share_list = $this->SynchroUser->find('all', array('conditions' => array('SynchroUser.user_id' => $_SESSION['User']['User']['id'])));
            $userid = $this->data['Blog']['user_id'];
            $this->data['Blog']['User_id'] = !empty($this->data['Blog']['user_id']) ? $this->data['Blog']['user_id'] : '';//用户id
            $this->data['Blog']['content'] = !empty($this->data['Blog']['content']) ? $this->data['Blog']['content'] : '';//用户日志
            $this->data['Blog']['created'] = date('Y-m-d H:i:s');//用户创建时间
            $this->data['Blog']['modified'] = date('Y-m-d H:i:s');//用户修改时间
            $this->data['Blog']['status'] = 1;//日志默认状态（有效)

            //替换表情
            $oauth_content = $this->data['Blog']['content'];
            $oauth_content = preg_replace("/<img.+?\/>/", '', $oauth_content);
            $oauth_content = strlen($oauth_content) == 0 || $oauth_content == '' ? 'http://www.seevia.cn/' : $oauth_content;

            //图片处理
            if (isset($_FILES['upfile']['tmp_name']) && !empty($_FILES['upfile']['tmp_name'])) {
                //图片上传处理
//    		if(isset($_FILES["user_img"])&&!empty($_FILES["user_img"]["name"]))
//    		{
                $imgname_arr = explode('.', $_FILES['upfile']['name']);//获取文件名

                if ($imgname_arr[1] == 'jpg' || $imgname_arr[1] == 'gif' || $imgname_arr[1] == 'png' || $imgname_arr[1] == 'jpeg' || $imgname_arr[1] == 'JPEG' || $imgname_arr[1] == 'JPG' || $imgname_arr[1] == 'GIF' || $imgname_arr[1] == 'PNG') {
                    //判断文件格式（限制图片格式）
                    $img_thumb_name = md5($imgname_arr[0].time());
                    $image_name = $img_thumb_name.'.'.$imgname_arr[1];
                    $imgaddr = WWW_ROOT.'media/blog/'.date('Ym').'/';
                    $image_width = 180;
                    $image_height = 180;
                    $img_detail = str_replace($image_name, '', $imgaddr);
                    $this->mkdirs($imgaddr);
                    move_uploaded_file($_FILES['upfile']['tmp_name'], $imgaddr.$image_name);
//    				$this->make_thumb($imgaddr.$image_name,$image_width,$image_height,"#FFFFFF",$img_thumb_name,$img_detail,$imgname_arr[1]);//缩略图
//					是判断用户上传图片是唯一的
//    				if(isset($this->data)&&!empty($this->data['Blog']['img']))
//    				{
//    					if(file_exists(WWW_ROOT.$this->data['Blog']['img'])){
//    						unlink(WWW_ROOT.$this->data['Blog']['img']);
//    					}
//    				}
                    $this->data['Blog']['img'] = '/media/blog/'.date('Ym').'/'.$image_name;
                } else {
                    $this->flash("<div style='min-height:150px;'><font size='5' color='red'>图片格式错误!</font></div>", array('controller' => '/user_socials/index/'.$_SESSION['User']['User']['id']), '');

                    return;
                }
//    		}
            } else {
                $this->data['Blog']['img'] = '';
            }
            $sina_list = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.User_id' => $_SESSION['User']['User']['id'], 'SynchroUser.type' => 'SinaWeibo', 'SynchroUser.status' => '1')));
            $qq_list = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.User_id' => $_SESSION['User']['User']['id'], 'SynchroUser.type' => 'QQWeibo', 'SynchroUser.status' => '1')));
            if (!empty($sina_list)) {
                if ($this->data['Blog']['img'] == '') {
                    $this->statuses_update($oauth_content, $sina_list);
                } else {
                    $this->statuses_upload($oauth_content, $this->data['Blog']['img'], $sina_list);
                }
            }
            if (!empty($qq_list)) {
                $this->add_weibo_pic($oauth_content, $this->data['Blog']['img'], $qq_list);
            }
            $this->Blog->save(array('Blog' => $this->data['Blog']));
            $this->redirect('/user_socials/index/'.$userid);
        } else {
            $this->redirect('/user_socials/index/'.$userid);
        }
    }

    //创建路径
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

    //日志评论
    public function article_comment($acticle_id)
    {
        //ajax刷新还是页面刷新?
        //新增评论
        if ($this->RequestHandler->isPost()) {
            $this->data['Blog']['user_id'] = !empty($_POST['user_id']) ? $_POST['user_id'] : '';//用户id
            $this->data['Blog']['parent_id'] = !empty($_POST['parent_id']) ? $_POST['parent_id'] : 0;//用户id
            $this->data['Blog']['content'] = !empty($_POST['content']) ? $_POST['content'] : '';//用户日志
            $this->data['Blog']['created'] = date('Y-m-d H:i:s');//用户创建时间
            $this->data['Blog']['modified'] = date('Y-m-d H:i:s');//用户修改时间
            $this->data['Blog']['status'] = 1;//日志默认状态（有效）
            $this->Blog->save(array('Blog' => $this->data['Blog']));
        }
        //查询该日志的评论
        $cond['joins'] = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('Blog.user_id = User.id'),
                         ), );
        $condition = array('Blog.parent_id' => $acticle_id,'Blog.status' => 1);
        $cond['conditions'] = $condition;
        $cond['order'] = 'modified desc';
        $cond['limit'] = 16;
        $cond['fields'] = array('Blog.*','User.id','User.img01','User.name');
        $reply_list = $this->Blog->find('all', $cond);
        //该日志的评论数量
        $comment_num = $this->Blog->find('count', array('conditions' => array('Blog.parent_id' => $acticle_id, 'Blog.status' => 1)));
        $this->set('reply_list', $reply_list);
        $this->set('comment_num', $comment_num);
        $this->set('acticle_id', $acticle_id);
        if (isset($acticle_id)) {
            $this->layout = 'ajax';
            $this->render('comment');
        }
    }

    //信息页  对应消息（私信，站内信）ctp
    public function message_index($page = 1, $limit = 0)
    {
        //登录验证
        $this->checkSessionUser();
        $this->pageTitle = $this->ld['message_list'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/');
        $this->ur_heres[] = array('name' => $this->ld['message_list'], 'url' => '');
        $id = isset($_GET['id']) ? $_GET['id'] : isset($_SESSION['User']) ? $_SESSION['User']['User']['id'] : '';
        $this->set('id', $id);
        $word = $this->BlockWord->find('all');
        $this->set('word', $word);
        $user_list = $this->UserFans->find('all', array('conditions' => array('UserFans.fan_id' => $id)));
        $this->set('user_list', $user_list);
        $user = $this->User->find_user_by_id($id);
        $this->set('user', $user);
        $fans = $this->UserFans->find_fanscount_byuserid($id);
        $this->set('fans', $fans);
        $focus = $this->UserFans->find_focuscount_byuserid($id);
        $this->set('focus', $focus);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($id);
        $this->set('blog', $blog);

        //最新活动
        $topicInfo = $this->Topic->find('all', array('fields' => array('Topic.id', 'TopicI18n.title'), 'limit' => 6, 'conditions' => array('Topic.status' => 1, 'Topic.start_time  <=' => DateTime, 'Topic.end_time >=' => DateTime), 'order' => 'Topic.created desc'));
        $this->set('topicInfo', $topicInfo);

        //访客的id
        $visitors = $this->UserVisitors->find_visitors_byuserid($id);
        //访客列表
        ///分页
        $cond['joins'] = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('UserVisitors.visitor_id = User.id'),
                         ), );
        $condition = array('user_id' => $id);
        $cond['conditions'] = $condition;
        $cond['limit'] = 24;
        $cond['order'] = 'modified desc';
        $cond['fields'] = array('UserVisitors.*','User.id','User.img01');
        $visitor_list = $this->UserVisitors->find('all', $cond);
        //$visitor_list=$this->User->find("all",array("conditions"=>array("User.id"=>$visitors),"fields"=>array("User.id","User.img01")));
        $this->set('visitor_list', $visitor_list);
        $this->layout = 'usersocial';//引入模版
/*		$parameters['get'] = array();
        $limit=6;
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller'=>'user_socials','page'=>$page);
        //分页参数
        //$condition['UserChat.']
        $condition=array();
        $page_options = Array('page'=>$page,'show'=>$limit,'modelClass'=>'UserChat');
        $page = $this->Pagination->init($condition, $parameters, $page_options); // Added
        //$this->UserChat->find("all",array("conditions"=>$condition));*/
        $condition = array();
        $condition['or']['UserChat.to_user_id'] = $id;
        $condition['or']['UserChat.user_id'] = $id;
        $condition['UserChat.status'] = 1;
        $chat_list = $this->UserChat->find('all', array('conditions' => $condition, 'order' => 'created desc'));
        $user_arr = $this->User->find('all', array('fields' => array('User.id', 'User.name', 'User.img01')));
        foreach ($chat_list as $k => $v) {
            foreach ($user_arr as $kk => $vv) {
                if ($v['UserChat']['user_id'] == $vv['User']['id']) {
                    $chat_list[$k]['User'] = $vv['User'];
                }
            }
        }
        $chat_list_n = $chat_list;
        //记录该用户存在消息记录
        $chaters = array();
        foreach ($chat_list_n as $k => $v) {
            if ($v['UserChat']['user_id'] == $id) {
                if (!in_array($v['UserChat']['to_user_id'], $chaters)) {
                    $chaters[] = $v['UserChat']['to_user_id'];
                } else {
                    unset($chat_list_n[$k]);
                }
            }
            if ($v['UserChat']['to_user_id'] == $id) {
                if (!in_array($v['UserChat']['user_id'], $chaters)) {
                    $chaters[] = $v['UserChat']['user_id'];
                } else {
                    unset($chat_list_n[$k]);
                }
            }
        }

        //记录来自该用户的未读信息数量
        $count_arr = array();
        foreach ($chaters as $k => $v) {
            foreach ($chat_list as $kk => $vv) {
                if ($v == $vv['UserChat']['user_id'] && $vv['UserChat']['read'] == '0') {
                    if (isset($count_arr[$v]['num'])) {
                        ++$count_arr[$v]['num'];
                    } else {
                        $count_arr[$v]['num'] = 1;
                    }
                }
            }
        }
        foreach ($chat_list_n as $k => $v) {
            foreach ($count_arr as $kk => $vv) {
                if ($v['UserChat']['user_id'] == $kk || $v['UserChat']['to_user_id'] == $kk) {
                    $chat_list_n[$k]['no_read'] = $vv['num'];
                }
            }
        }
        //整理来往信息
        $message_list = array();
        foreach ($chaters as $k => $v) {
            foreach ($chat_list as $kk => $vv) {
                if ($vv['UserChat']['user_id'] == $v || $vv['UserChat']['to_user_id'] == $v) {
                    $message_list[$v][] = $vv;
                }
            }
        }
        foreach ($chat_list_n as $k => $v) {
            foreach ($message_list as $kk => $vv) {
                if ($v['UserChat']['user_id'] == $kk || $v['UserChat']['to_user_id'] == $kk) {
                    $chat_list_n[$k]['message_list'] = $vv;
                }
            }
        }
        //检查最后发信人是否为当前用户
        foreach ($chat_list_n as $k => $v) {
            if ($v['UserChat']['user_id'] == $id) {
                //为当前用户，用户信息改为收件人信息
                foreach ($user_arr as $kk => $vv) {
                    if ($v['UserChat']['to_user_id'] == $vv['User']['id']) {
                        $chat_list_n[$k]['User'] = $vv['User'];
                    }
                }
            }
        }
        $this->set('chat_list', $chat_list_n);

        $message = $this->UserMessage->find('all', array('conditions' => array('UserMessage.to_id' => $id, 'UserMessage.status' => '1')));
        $this->set('message', $message);

        //将站内信标记为已读
        if (!empty($message)) {
            foreach ($message as $k => $v) {
                $message_data['is_read'] = '1';
                $message_data['id'] = $v['UserMessage']['id'];
                $this->UserMessage->saveAll($message_data);
            }
        }
    }

    public function get_message_count()
    {
        $id = !empty($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '';
        $c_count = $this->UserChat->find('count', array('conditions' => array('UserChat.status' => '1', 'UserChat.read' => '0', 'UserChat.to_user_id' => $id)));
        $m_count = $this->UserMessage->find('count', array('conditions' => array('UserMessage.status' => '1', 'UserMessage.is_read' => '0', 'UserMessage.to_id' => $id)));
        $this->set('c_count', $c_count);
        $this->set('m_count', $m_count);
        $this->layout = 'ajax';
        $this->render('c_count');
    }

    //ajax管理是否已读
    public function is_read()
    {
        $id = !empty($_POST['id']) ? $_POST['id'] : '';
        $condition['UserChat.user_id'] = $id;
        $condition['UserChat.to_user_id'] = $_SESSION['User']['User']['id'];
        $chats = $this->UserChat->find('all', array('conditions' => $condition));
        foreach ($chats as $k => $v) {
            $this->data['UserChat']['id'] = $v['UserChat']['id'];
            $this->data['UserChat']['modified'] = date('Y-m-d H:i:s');//用户修改时间
                $this->data['UserChat']['read'] = '1';//用户修改时间
            $this->UserChat->save(array('UserChat' => $this->data['UserChat']));
        }
        $this->layout = 'ajax';
        $this->render('is_read');
    }

    public function private_comment()
    {
        //私信回复，图片，表情，分享，可能增加对应处理方法
        //ajax刷新还是页面刷新?
        if ($this->RequestHandler->isPost()) {
            $this->layout = 'ajax';
            Configure::write('debug', 1);

            $this->data['UserChat']['user_id'] = !empty($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '';
            $this->data['UserChat']['to_user_id'] = !empty($_POST['to_user_id']) ? $_POST['to_user_id'] : '';//用户id
            $this->data['UserChat']['content'] = !empty($_POST['content']) ? $_POST['content'] : '';
            $this->data['UserChat']['created'] = date('Y-m-d H:i:s');//用户创建时间
            $this->data['UserChat']['status'] = '1';
            $this->data['UserChat']['modified'] = date('Y-m-d H:i:s');//用户修改时间
            $this->data['UserChat']['read'] = '0';//用户修改时间
            $flag = 0;
            if ($this->data['UserChat']['user_id'] != '' && $this->data['UserChat']['to_user_id'] != '' && $this->data['UserChat']['user_id'] != $this->data['UserChat']['to_user_id']) {
                if ($this->UserChat->save($this->data['UserChat'])) {
                    $flag = 1;
                }
            }
            die(json_encode($flag));
        } else {
            $this->redirect('/');
        }
    }

    //私信聊天室
    public function chatroom($id = 0, $page = 1, $limit = 0)
    {
        //登录验证
        $this->checkSessionUser();
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/');
        $this->ur_heres[] = array('name' => $this->ld['message_list'], 'url' => '/user_socials/message_index');
        $this->layout = 'usersocial';//引入模版
        $id = isset($id) ? $id : '';
        $ids = $_SESSION['User']['User']['id'];
        $this->set('id', $ids);
        $this->set('ids', $id);

        $id_arr = array($ids,$id);
        //获取用户信息
        $userInfo_list = $this->User->find('all', array('conditions' => array('User.id' => $id_arr)));

        //获取聊天室用户信息
        if (!empty($userInfo_list) && sizeof($userInfo_list) >= 0) {
            foreach ($userInfo_list as $k => $v) {
                if ($v['User']['id'] == $id) {
                    $userInfo = $v;
                }
                if ($v['User']['id'] == $ids) {
                    $user = $v;
                }
            }
        }

        if (isset($userInfo) && isset($user)) {
            $this->pageTitle = $userInfo['User']['name'].' - '.$this->ld['because_of_record'].' - '.$this->configs['shop_title'];
            $this->ur_heres[] = array('name' => $userInfo['User']['name'].' - '.$this->ld['because_of_record'], 'url' => '');
            $this->set('userInfo', $userInfo);
            $this->set('user', $user);
        } else {
            //跳转到提示页
            $this->flash($this->ld['user_not_exist'], '/user_socials/message_index/', '');
        }
        $word = $this->BlockWord->find('all');
        $this->set('word', $word);

        $fans = $this->UserFans->find_fanscount_byuserid($ids);
        $this->set('fans', $fans);
        $focus = $this->UserFans->find_focuscount_byuserid($ids);
        $this->set('focus', $focus);
        $user_list = $this->UserFans->find('all', array('conditions' => array('UserFans.fan_id' => $_SESSION['User']['User']['id'])));
        $this->set('user_list', $user_list);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($ids);
        $this->set('blog', $blog);

        //最新活动
        $topicInfo = $this->Topic->find('all', array('fields' => array('Topic.id', 'TopicI18n.title'), 'limit' => 6, 'conditions' => array('Topic.status' => 1, 'Topic.start_time  <=' => DateTime, 'Topic.end_time >=' => DateTime), 'order' => 'Topic.created desc'));
        $this->set('topicInfo', $topicInfo);

        //访客的id
        $visitors = $this->UserVisitors->find_visitors_byuserid($ids);
        //访客列表
        ///分页
        $cond['joins'] = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('UserVisitors.visitor_id = User.id'),
                         ), );
        $condition = array('user_id' => $ids);
        $cond['conditions'] = $condition;
        $cond['limit'] = 24;
        $cond['order'] = 'modified desc';
        $cond['fields'] = array('UserVisitors.*','User.id','User.img01');
        $visitor_list = $this->UserVisitors->find('all', $cond);
        $this->set('visitor_list', $visitor_list);

        $cond['joins'] = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('UserChat.user_id = User.id'),
                         ), );
        $arr = array($id,$_SESSION['User']['User']['id']);
        $conditions['UserChat.user_id'] = $arr;
        $conditions['UserChat.to_user_id'] = $arr;
        $conditions['UserChat.status'] = 1;
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $order_type = 'desc';
        $order_field = 'UserChat.created';
        $limit = 12;
        $parameters['route'] = array('controller' => 'UserSocials','action' => 'chatroom','id' => $id,'page' => $page,'limit' => $limit,'order_field' => $order_field,'order_type' => $order_type);
        $rownum = 12;
        $options = array('page' => $page,'show' => $rownum,'modelClass' => 'UserChat');
        $page = $this->Pagination->init($conditions, $parameters, $options);
        $options = array();
        $cond['conditions'] = $conditions;
        $cond['order'] = 'created desc';
        $cond['limit'] = 12;
        $cond['fields'] = array('UserChat.*','User.id','User.img01','User.name');
        $cond['page'] = $page;
        $chat_history = $this->UserChat->find('all', $cond);
        $this->set('chat_history', $chat_history);
    }

    //私信删除
    public function private_delete($message_id)
    {
        //获取当前私信id$message_id，查询是哪2个用户
        $user_list = $this->UserChat->find('first', array('conditions' => array('UserChat.id' => $message_id), 'fields' => array('UserChat.user_id', 'UserChat.to_user_id')));
        $conditions['or']['UserChat.user_id'] = $user_list['UserChat']['user_id'];
        $conditions['or']['UserChat.user_id'] = $user_list['UserChat']['to_user_id'];
        $conditions['or']['UserChat.to_user_id'] = $user_list['UserChat']['user_id'];
        $conditions['or']['UserChat.to_user_id'] = $user_list['UserChat']['to_user_id'];
        $id = $this->UserChat->query("UPDATE `svsns_user_chats` AS `UserChat` SET `UserChat`.`status` = 2 WHERE ((`UserChat`.`user_id` = '".$user_list['UserChat']['user_id']."') AND (`UserChat`.`to_user_id` = '".$user_list['UserChat']['to_user_id']."')) OR ((`UserChat`.`user_id` = '".$user_list['UserChat']['to_user_id']."') AND (`UserChat`.`to_user_id` = '".$user_list['UserChat']['user_id']."')) ");
        //$this->Blog->updateAll(array('Blog.status'=>2),array('Blog.id'=>$id));
        if (!empty($user_list)) {
            $this->redirect('/user_socials/message_index/?id='.$_SESSION['User']['User']['id']);
        }
    }

    //私信批量删除
    public function private_deleteall($message_id)
    {
    }

    //站内信删除
    public function system_delete()
    {
        $message_id = !empty($_POST['message_id']) ? $_POST['message_id'] : '';
        $message = $this->UserMessage->find('first', array('conditions' => array('UserMessage.id' => $message_id)));
        if ($this->RequestHandler->isPost() && !empty($message)) {
            $this->data['UserMessage']['id'] = $message['UserMessage']['id'];
            $this->data['UserMessage']['status'] = 0;
            $this->data['UserMessage']['modified'] = date('Y-m-d H:i:s');//用户修改时间
            $this->UserMessage->save(array('UserMessage' => $this->data['UserMessage']));
        }
        $this->layout = 'ajax';
        $this->render('ajax_deletemessage');
    }

    public function fanslist($id = 0, $type = 1)
    {
        $this->layout = 'usersocial';    //引入模板
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/');
        $id = isset($_GET['id']) ? $_GET['id'] : $id;
        $type = isset($_GET['type']) ? $_GET['type'] : $type;
        if ($type == 2) {
            $this->pageTitle = $this->ld['fans'].' - '.$this->configs['shop_title'];
            $this->ur_heres[] = array('name' => $this->ld['fans'], 'url' => '');
        } else {
            $this->pageTitle = $this->ld['focus'].' - '.$this->configs['shop_title'];
            $this->ur_heres[] = array('name' => $this->ld['focus'], 'url' => '');
        }
        $this->set('fanslist_type', $type);
        $this->set('id', $id);
        $word = $this->BlockWord->find('all');
        $this->set('word', $word);
        $user = $this->User->find_user_by_id($id);
        $this->set('user', $user);
        $fans = $this->UserFans->find_fanscount_byuserid($id);
        $this->set('fans', $fans);
        $focus = $this->UserFans->find_focuscount_byuserid($id);
        $this->set('focus', $focus);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($id);
        $this->set('blog', $blog);
        //最新活动
        $topicInfo = $this->Topic->find('all', array('fields' => array('Topic.id', 'TopicI18n.title'), 'limit' => 6, 'conditions' => array('Topic.status' => 1, 'Topic.start_time  <=' => DateTime, 'Topic.end_time >=' => DateTime), 'order' => 'Topic.created desc'));
        $this->set('topicInfo', $topicInfo);

        //访客列表
        $cond['joins'] = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('UserVisitors.visitor_id = User.id'),
                         ), );
        $condition = array('user_id' => $id);
        $cond['conditions'] = $condition;
        $cond['limit'] = 24;
        $cond['order'] = 'modified desc';
        $cond['fields'] = array('UserVisitors.*','User.id','User.img01');
        $visitor_list = $this->UserVisitors->find('all', $cond);
        $this->set('visitor_list', $visitor_list);

        if (isset($_SESSION['User']['User']['id'])) {
            $user_list = $this->UserFans->find('all', array('conditions' => array('UserFans.fan_id' => $_SESSION['User']['User']['id'])));
            $this->set('user_list', $user_list);
        }

        //判断当前用户是否为登录用户
        $is_me = isset($_SESSION['User']) && $_SESSION['User']['User']['id'] == $id ? 1 : isset($_SESSION['User']) && $_SESSION['User']['User']['id'] != $id ? 2 : 0;
        $this->set('is_me', $is_me);

        $joins1 = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('UserFans.fan_id = User.id'),
                         ), );
        $joins2 = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('UserFans.user_id = User.id'),
                         ), );
        $fans_list = $this->UserFans->find('all', array('fields' => array('UserFans.*', 'User.id', 'User.name', 'User.img01'), 'conditions' => array('UserFans.user_id' => $id), 'joins' => $joins1));
        $focus_list = $this->UserFans->find('all', array('fields' => array('UserFans.*', 'User.id', 'User.name', 'User.img01'), 'conditions' => array('UserFans.fan_id' => $id), 'joins' => $joins2));

        //记录用户Id
        $user_ids = array();
        foreach ($fans_list as $k => $v) {
            if (!in_array($v['UserFans']['fan_id'], $user_ids)) {
                $user_ids[] = $v['UserFans']['fan_id'];
            }
        }
        foreach ($focus_list as $k => $v) {
            if (!in_array($v['UserFans']['user_id'], $user_ids)) {
                $user_ids[] = $v['UserFans']['user_id'];
            }
        }
        $fanscount = $this->UserFans->find_fanscount_byuseridarr($user_ids);
        $focuscount = $this->UserFans->find_focuscount_byuseridarr($user_ids);
        $blogcount = $this->Blog->find_blogcount_byuseridarr($user_ids);
        foreach ($fans_list as $k => $v) {
            foreach ($focuscount as $kk => $vv) {
                if ($v['UserFans']['fan_id'] == $vv['UserFans']['fan_id']) {
                    $fans_list[$k]['fans_info']['focus'] = $vv[0]['focuscount'];
                }
            }
            foreach ($fanscount as $kk => $vv) {
                if ($v['UserFans']['fan_id'] == $vv['UserFans']['user_id']) {
                    $fans_list[$k]['fans_info']['fans'] = $vv[0]['fanscount'];
                }
            }
            foreach ($blogcount as $kk => $vv) {
                if ($v['UserFans']['fan_id'] == $vv['Blog']['user_id']) {
                    $fans_list[$k]['fans_info']['blog'] = $vv[0]['blogcount'];
                }
            }
        }

        foreach ($focus_list as $k => $v) {
            foreach ($focuscount as $kk => $vv) {
                if ($v['UserFans']['user_id'] == $vv['UserFans']['fan_id']) {
                    $focus_list[$k]['fans_info']['focus'] = $vv[0]['focuscount'];
                }
            }
            foreach ($fanscount as $kk => $vv) {
                if ($v['UserFans']['user_id'] == $vv['UserFans']['user_id']) {
                    $focus_list[$k]['fans_info']['fans'] = $vv[0]['fanscount'];
                }
            }
            foreach ($blogcount as $kk => $vv) {
                if ($v['UserFans']['user_id'] == $vv['Blog']['user_id']) {
                    $focus_list[$k]['fans_info']['blog'] = $vv[0]['blogcount'];
                }
            }
        }
        $this->set('fans_list', $fans_list);
        $this->set('focus_list', $focus_list);
        if ($is_me == 2) {
            $user_id = $_SESSION['User']['User']['id'];
            $this->set('user_id', $user_id);
            $my_focus_list = $this->UserFans->find('list', array('fields' => array('UserFans.user_id'), 'conditions' => array('UserFans.fan_id' => $user_id)));
            $this->set('my_focus_list', $my_focus_list);
        }
    }

    //添加粉丝
    public function fans_add()
    {
        if ($this->RequestHandler->isPost()) {
            $this->layout = 'ajax';
            Configure::write('debug', 1);

            $this->data['UserFans']['user_id'] = !empty($_POST['fans_id']) ? $_POST['fans_id'] : '';
            $this->data['UserFans']['fan_id'] = !empty($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '';//用户id
            $this->data['UserFans']['created'] = date('Y-m-d H:i:s');//用户创建时间
            $this->data['UserFans']['modified'] = date('Y-m-d H:i:s');//用户修改时间
            $this->UserFans->save(array('UserFans' => $this->data['UserFans']));
            $result['code'] = 1;
            die(json_encode($result));
        }
        die();
    }

    //取消关注操作
    public function unfo()
    {
        if ($this->RequestHandler->isPost()) {
            $this->layout = 'ajax';
            Configure::write('debug', 1);
            $user_id = !empty($_POST['fans_id']) ? $_POST['fans_id'] : '';
            $fan_id = !empty($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '';//用户id
            $this->UserFans->deleteAll(array('user_id' => $user_id, 'fan_id' => $fan_id));

            $result['code'] = 1;
            die(json_encode($result));
        }
        die();
    }

    //删除日志
    public function del_comment($id)
    {
        //删除相关日志及评论
        $this->data['Blog']['id'] = $id;//用户id
        $this->data['Blog']['status'] = 2;
        $this->data['Blog']['parent_id'] = $id;
        $this->data['Blog']['modified'] = date('Y-m-d H:i:s');//用户修改时间
        $conditions['or']['id'] = $id;
        $conditions['or']['parent_id'] = $id;
        $this->Blog->updateAll(array('Blog.status' => 2), array('Blog.parent_id' => $id));
        $id = $this->Blog->updateAll(array('Blog.status' => 2), array('Blog.id' => $id));
        if (!empty($id)) {
            $this->redirect('/user_socials/index/'.$_SESSION['User']['User']['id']);
        }
    }

    //添加喜欢产品的ajax方法(type_id为0的是喜欢的产品)
    public function ajax_like()
    {
    	//登录验证
        $this->checkSessionUser();
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['code'] = '0';
        if(isset($_POST)){
        	$_POST=$this->clean_xss($_POST);
        }
        if (isset($_POST['loacle']) && $_POST['loacle'] != '' && $this->locale != $_POST['loacle']) {
            $this->locale = $_POST['loacle'];
            $this->ld = $this->LanguageDictionary->getformatcode($this->locale);
            $this->Product->set_locale($this->locale);
        }
        if (isset($_SESSION['User'])) {
            $result['code'] = '1';
            $result['is_login'] = '1';
            $userid = $_SESSION['User']['User']['id'];
            $type_id = !empty($_POST['type_id']) ? $_POST['type_id'] : '';
            //判断该用户是否喜欢过该产品(type)
            $like = $this->UserLike->find('first', array('conditions' => array('user_id' => $userid, 'type' => 'P', 'type_id' => $type_id, 'action' => 'like')));

            if (empty($like)) {
                $this->data['UserLike']['user_id'] = $userid;//用户id
                $this->data['UserLike']['type'] = 'P';
                $this->data['UserLike']['type_id'] = $type_id;
                $this->data['UserLike']['action'] = 'like';
                $this->data['UserLike']['created'] = date('Y-m-d H:i:s');//用户创建时间
                $this->data['UserLike']['modified'] = date('Y-m-d H:i:s');//用户修改时间
                $this->UserLike->save(array('UserLike' => $this->data['UserLike']));

                $like_id = $this->UserLike->id;

                //与商品表like_stat like次数联动
                $procountInfo = $this->Product->find('all', array('fields' => array('id', 'like_stat'), 'conditions' => array('Product.id' => $type_id)));
                if (count($procountInfo) > 0) {
                    $procount = $procountInfo[0]['Product']['like_stat'];
                    $proInfo['id'] = $type_id;
                    $proInfo['like_stat'] = $procount + 1;
                    $this->Product->save($proInfo);
                }

                //用户积分处理
                $user_point = $this->User->find('first', array('conditions' => array('User.id' => $userid)));
                if (!empty($user_point['User']['user_point'])) {
                    $user_point['User']['user_point'] = $user_point['User']['user_point'] + 1;
                    $user_point['User']['id'] = $userid;
                    $user_point['User']['modified'] = date('Y-m-d H:i:s');
                    if ($user_point['User']['user_point'] >= 205) {
                        $user_point['User']['type'] = 2;
                    } else {
                        $user_point['User']['type'] = 0;
                    }
                    $this->User->save(array('User' => $user_point['User']));
                }
                //获取商品信息
                $pro_info = $this->Product->find('first', array('fields' => array('Product.id,ProductI18n.name'), 'conditions' => array('Product.id' => $type_id, 'ProductI18n.locale' => $this->locale)));
                if (isset($pro_info['Product'])) {
                    //动作记录
                    $action_data['UserAction']['user_id'] = $userid;
                    $action_data['UserAction']['type'] = 'like';
                    $action_data['UserAction']['type_id'] = $like_id;
                    $action_data['UserAction']['content'] = $_SESSION['User']['User']['name'].' '.$this->ld['like_the'].' '.$this->ld['product']."<a href='/products/".$pro_info['Product']['id']."'>".$pro_info['ProductI18n']['name'].'</a>';
                    $action_data['UserAction']['created'] = date('Y-m-d H:i:s');//用户创建时间
                    $action_data['UserAction']['modified'] = date('Y-m-d H:i:s');//用户修改时间
                    $this->UserAction->save($action_data['UserAction']);
                }
            }
            //判断该产品的喜欢次数
               $like_num = $this->UserLike->find('count', array('conditions' => array('type' => 'P', 'type_id' => $type_id, 'action' => 'like')));
            $result['like_num'] = $like_num;
        } else {
            $result['msg'] = $this->ld['please_login'].$this->ld['login'];
            $result['is_login'] = '0';
        }
        die(json_encode($result));
    }

    //取消喜欢产品的ajax方法(type_id为0的是喜欢的产品)
    public function ajax_dislike()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if (isset($_POST['loacle']) && $_POST['loacle'] != '' && $this->locale != $_POST['loacle']) {
            $this->locale = $_POST['loacle'];
            $this->ld = $this->LanguageDictionary->getformatcode($this->locale);
            $this->Product->set_locale($this->locale);
        }
        $userid = !empty($_POST['user_id']) ? $_POST['user_id'] : '';
        $type_id = !empty($_POST['type_id']) ? $_POST['type_id'] : '';
        //判断该用户是否喜欢过该产品(type)
        $like = $this->UserLike->find('first', array('conditions' => array('user_id' => $userid, 'type' => 'P', 'type_id' => $type_id, 'action' => 'like')));
        if ($this->RequestHandler->isPost() && !empty($like)) {
            $this->data['UserLike']['id'] = $like['UserLike']['id'];
            $this->data['UserLike']['type_id'] = $type_id;
            $this->data['UserLike']['action'] = 'unlike';
            $this->data['UserLike']['modified'] = date('Y-m-d H:i:s');//用户修改时间
            $num = $this->UserLike->save(array('UserLike' => $this->data['UserLike']));
            //与商品表like_stat like次数联动
            $procountInfo = $this->Product->find('all', array('fields' => array('id', 'like_stat'), 'conditions' => array('Product.id' => $type_id)));
            if (count($procountInfo) > 0) {
                $procount = $procountInfo[0]['Product']['like_stat'];
                if ($procount > 0) {
                    $proInfo['id'] = $type_id;
                    $proInfo['like_stat'] = $procount - 1;
                    $this->Product->save($proInfo);
                }
            }
            //用户积分处理
            $user_point = $this->User->find('first', array('conditions' => array('User.id' => $userid)));
            if (!empty($user_point['User']['user_point'])) {
                $user_point['User']['user_point'] = $user_point['User']['user_point'] - 1;
                $user_point['User']['id'] = $userid;
                $user_point['User']['modified'] = date('Y-m-d H:i:s');
                if ($user_point['User']['user_point'] >= 205) {
                    $user_point['User']['type'] = 2;
                } else {
                    $user_point['User']['type'] = 0;
                }
                $this->User->save(array('User' => $user_point['User']));
            }

            //获取商品信息
            $pro_info = $this->Product->find('first', array('fields' => array('Product.id,ProductI18n.name'), 'conditions' => array('Product.id' => $type_id, 'ProductI18n.locale' => $this->locale)));
            if (isset($pro_info['Product'])) {
                //动作记录
                $action_data['UserAction']['user_id'] = $userid;
                $action_data['UserAction']['type'] = 'dislike';
                $action_data['UserAction']['type_id'] = $like['UserLike']['id'];
                $action_data['UserAction']['content'] = $_SESSION['User']['User']['name'].' '.$this->ld['canceled'].' '.$this->ld['like_the'].' '.$this->ld['product']."<a href='/products/".$pro_info['Product']['id']."'>".$pro_info['ProductI18n']['name'].'</a>';
                $action_data['UserAction']['created'] = date('Y-m-d H:i:s');//用户创建时间
                $action_data['UserAction']['modified'] = date('Y-m-d H:i:s');//用户修改时间
                $this->UserAction->save($action_data['UserAction']);
            }
        }
        $result['dislike_id'] = $like['UserLike']['id'];
        if (isset($this->configs['show_product_like']) && $this->configs['show_product_like'] == '1') {
            $page = 1;
            $limit = 9;
            $cond_pro['joins'] = array(
                                       array('table' => 'svoms_products',
                                          'alias' => 'Product',
                                          'type' => 'inner',
                                          'conditions' => array('UserLike.type_id = Product.id'),
                                         ),
                                );
            $condition_pro = array('UserLike.user_id' => $userid,'UserLike.type' => 'P','UserLike.action' => 'like');
            $cond_pro['conditions'] = $condition_pro;
            $cond_pro['limit'] = $limit;
            $cond_pro['page'] = $page;
            $cond_pro['fields'] = array('UserLike.*','Product.id','Product.shop_price','Product.img_original','Product.img_detail');
            $like_list = $this->UserLike->find('all', $cond_pro);

            $product_ids = $this->Product->getproduct_ids($like_list);
            $product_I18N_list = $this->ProductI18n->find('list', array('fields' => array('ProductI18n.product_id', 'ProductI18n.name'), 'conditions' => array('ProductI18n.locale' => $this->locale, 'ProductI18n.product_id' => $product_ids)));
            $comment_num = $this->Comment->find('all', array('conditions' => array('Comment.type_id' => $product_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
            $like_num = $this->UserLike->find('all', array('conditions' => array('UserLike.type' => 'P', 'UserLike.type_id' => $product_ids), 'fields' => array('UserLike.type_id', 'count(UserLike.type_id) as num'), 'group' => 'UserLike.type_id'));
            foreach ($like_list as $k => $v) {
                $like_list[$k]['ProductI18n']['name'] = isset($product_I18N_list[$v['Product']['id']]) ? $product_I18N_list[$v['Product']['id']] : '';
                foreach ($like_num as $like_k => $like_v) {
                    if ($like_list[$k]['Product']['id'] == $like_v['UserLike']['type_id']) {
                        $like_list[$k]['Product']['like_num'] = $like_v[0]['num'];
                    }
                }
                foreach ($comment_num as $com_k => $com_v) {
                    if ($like_list[$k]['Product']['id'] == $com_v['Comment']['type_id']) {
                        $like_list[$k]['Product']['Commentnum'] = $com_v[0]['Commentnum'];
                    }
                }
            }
            $this->set('like_list', $like_list);
            //分页start
            //get参数

            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'user_socials','keyword' => 'index/'.$userid.'/'.'baobei','page' => $page);
            //分页参数
            //pr($parameters['route']);
            $page_options = array('page' => $page,'show' => $limit,'modelClass' => 'UserLike');
            $pages = $this->Pagination->init($condition_pro, $parameters, $page_options); // Added
            $this->set('like', $pages);
            //分页
        }
        //die(json_encode($result));
    }

    //隐私设置
    public function privacy_settings()
    {
        //登录验证
        $this->checkSessionUser();
        $this->page_init();
        $this->layout = 'usercenter';
        $this->pageTitle = $this->ld['privacy_settings'].' - '.$this->configs['shop_title'];
        //页面导航
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/edit');
        $this->ur_heres[] = array('name' => $this->ld['privacy_settings'], 'url' => '/users/edit');

        $userid = $_SESSION['User']['User']['id'];

        //分享绑定显示判断
        $app_share = $this->UserApp->app_status();
        $this->set('app_share', $app_share);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($userid);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($userid);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($userid);
        $this->set('focuscount', $focus);
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $userid)));
        $this->set('user_list', $user_list);

        //查询配置是否存在该用户的设置
        if ($this->RequestHandler->isPost()) {
            if (!empty($this->data['UserConfig'])) {
                $this->UserConfig->deleteAll(array('UserConfig.type' => 'privacy', 'UserConfig.user_id' => $userid));
                foreach ($this->data['UserConfig'] as $k => $v) {
                    $user_config_data['UserConfig']['user_id'] = $userid;
                    $user_config_data['UserConfig']['code'] = $k;
                    $user_config_data['UserConfig']['type'] = 'privacy';
                    $user_config_data['UserConfig']['value'] = $v;
                    $user_config_data['UserConfig']['created'] = date('Y-m-d H:i:s');
                    $user_config_data['UserConfig']['modified'] = date('Y-m-d H:i:s');
                    $this->UserConfig->saveAll($user_config_data);
                }
            }
            //跳转到提示页
            $this->flash($this->ld['set_successfully'], '/user_socials/privacy_settings', '');
        }
        $this->UserConfig->set_locale($this->locale);
        //查询我的隐私设置
        $default_user_config_list = array();
        $user_config_list = array();
        $privacy_info = $this->UserConfig->find('all', array('conditions' => array('UserConfig.user_id' => array(0, $userid), 'UserConfig.type' => 'privacy')));
        foreach ($privacy_info as $k => $v) {
            if ($v['UserConfig']['user_id'] == 0) {
                $default_user_config_list[$v['UserConfig']['code']]['name'] = $v['UserConfigI18n']['name'];
                $default_user_config_list[$v['UserConfig']['code']]['value_type'] = $v['UserConfig']['value_type'];
                $default_user_config_list[$v['UserConfig']['code']]['user_config_values'] = $v['UserConfigI18n']['user_config_values'];
                $default_user_config_list[$v['UserConfig']['code']]['value'] = $v['UserConfig']['value'];
            } else {
                $user_config_list[$v['UserConfig']['code']] = $v['UserConfig']['value'];
            }
        }
        $this->set('default_user_config_list', $default_user_config_list);
        $this->set('user_config_list', $user_config_list);
    }

    //分享设置
    public function share_settings()
    {
        //登录验证
        $this->checkSessionUser();
        $this->page_init();
        //页面导航
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/edit');
        $this->ur_heres[] = array('name' => $this->ld['share_settings'], 'url' => '/users/edit');
        $this->layout = 'usercenter';
        $this->pageTitle = $this->ld['share_settings'].' - '.$this->configs['shop_title'];
        //分享绑定显示判断
        $app_share = $this->UserApp->app_status();
        $this->set('app_share', $app_share);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($_SESSION['User']['User']['id']);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($_SESSION['User']['User']['id']);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($_SESSION['User']['User']['id']);
        $this->set('focuscount', $focus);
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
        $this->set('user_list', $user_list);
        //同步授权分享
        $UserApp_list = $this->UserApp->find('all', array('fields' => array('UserApp.type'), 'conditions' => array('UserApp.status' => '1')));
        $user_app_array = array();
        foreach ($UserApp_list as $ak => $av) {
            $user_app_array[$ak] = $av['UserApp']['type'];
        }
        $this->set('user_app_array', $user_app_array);
        //查询我的分享设置
        $share_list = $this->SynchroUser->find('all', array('conditions' => array('SynchroUser.user_id' => $_SESSION['User']['User']['id'], 'SynchroUser.type' => $user_app_array)));
        $this->set('share_list', $share_list);

        //查询配置是否存在该用户的设置
        if ($this->RequestHandler->isPost()) {
            $share_set = $this->SynchroUser->find('all', array('conditions' => array('SynchroUser.user_id' => $_POST['data']['Users']['id'])));
            $userid = $_POST['data']['Users']['id'];
            foreach ($share_set as $k => $v) {
                $this->data['SynchroUser']['id'] = $share_set[$k]['SynchroUser']['id'];
                $this->data['SynchroUser']['status'] = $_POST['data']['SynchroUser'][$share_set[$k]['SynchroUser']['type']];
                $this->data['SynchroUser']['modified'] = date('Y-m-d H:i:s');
                $this->SynchroUser->saveAll(array('SynchroUser' => $this->data['SynchroUser']));
            }
            //跳转到提示页
            $this->flash($this->ld['set_successfully'], '/user_socials/share_settings', '');
        }
    }

    /**
     * 发表带图片的微博 qq.
     *
     * @param object $sdk     OpenApiV3 Object
     * @param string $openid  openid
     * @param string $openkey openkey
     * @param string $pf      平台
     *
     * @return array 微博接口调用结果
     */
    public function add_weibo_pic($status = '新内容', $pic = '', $qq_list)
    {
        $_SESSION['t_access_token'] = $qq_list['SynchroUser']['oauth_token'];
        $_SESSION['t_openid'] = $qq_list['SynchroUser']['account'];
        $t_client_id = $this->UserApp->find('first', array('conditions' => array('UserApp.type' => 'QQWeibo')));
        $_SESSION['t_client_id'] = $t_client_id['UserApp']['app_key'];
        $sdk = new Tencent();
        $params = array(
            'content' => $status,
        );
        if ($pic != '') {
            $multi = array('pic' => WWW_ROOT.$pic);
            $r = $sdk->api('t/add_pic', $params, 'POST', $multi);
        } else {
            $r = $sdk->api('t/add', $params, 'POST');
        }
        $_r = json_decode($r, true);
        if (!empty($_r) && isset($_r['data']['id']) && $_r['msg'] == 'ok') {
            //分享记录
            $this->data['OauthLog']['user_id'] = $_SESSION['User']['User']['id'];
            $this->data['OauthLog']['oauth_type'] = 'qq';
            $this->data['OauthLog']['content'] = $status;
            $this->data['OauthLog']['modified'] = date('Y-m-d H:i:s');
            $this->OauthLog->saveAll(array('OauthLog' => $this->data['OauthLog']));
        }

        return $r;
    }

    /**
     * 发表带图片的微博 sina.
     *
     * @param object $SaeTOAuthV2 SaeTOAuthV2 Object
     * @param string $status      发布内容
     * @param string $pic         发布图片
     * @param array  $sina_list   用户参数
     *
     * @return array 微博接口调用结果
     */
    public function statuses_upload($status = '新内容', $pic = '', $sina_list)
    {
        $SaeTOAuthV2 = $this->saetoauthv2($sina_list);
        $url = 'statuses/upload';
        $parameters = array();
        $parameters['status'] = $status;//要发布的微博文本内容，必须做URLencode，内容不超过140个汉字。
        if ($pic != '') {
            $parameters['pic'] = '@'.$this->server_host.$pic;//要上传的图片，仅支持JPEG、GIF、PNG格式，图片大小小于5M。
        }
        $wb_result = $SaeTOAuthV2->post($url, $parameters, true);
        if (isset($wb_result['id']) && isset($wb_result['user'])) {
            //分享记录
            $this->data['OauthLog']['user_id'] = $_SESSION['User']['User']['id'];
            $this->data['OauthLog']['oauth_type'] = 'sina';
            $this->data['OauthLog']['content'] = $status;
            $this->data['OauthLog']['modified'] = date('Y-m-d H:i:s');
            $this->OauthLog->saveAll(array('OauthLog' => $this->data['OauthLog']));
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    /**
     * 发表不带图片的微博 sina.
     *
     * @param object $SaeTOAuthV2 SaeTOAuthV2 Object
     * @param string $status      发布内容
     * @param array  $sina_list   用户参数
     *
     * @return array 微博接口调用结果
     */
    public function statuses_update($status = '新内容', $sina_list)
    {
        $SaeTOAuthV2 = $this->saetoauthv2($sina_list);
        $url = 'statuses/update';
        $parameters = array();
        $parameters['status'] = $status;//要发布的微博文本内容，必须做URLencode，内容不超过140个汉字。
        $wb_result = $SaeTOAuthV2->post($url, $parameters);
        if (isset($wb_result['id']) && isset($wb_result['user'])) {
            //分享记录
            $this->data['OauthLog']['user_id'] = $_SESSION['User']['User']['id'];
            $this->data['OauthLog']['oauth_type'] = 'sina';
            $this->data['OauthLog']['content'] = $status;
            $this->data['OauthLog']['modified'] = date('Y-m-d H:i:s');
            $this->OauthLog->saveAll(array('OauthLog' => $this->data['OauthLog']));
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    public function saetoauthv2($sina_list)
    {
        $SaeTOAuthV2 = new SaeTOAuthV2($sina_list['SynchroUser']['account'], '', $sina_list['SynchroUser']['oauth_token']);

        return $SaeTOAuthV2;
    }
}
