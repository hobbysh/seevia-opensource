<?php

    /*
        svsns_blogs表
        用户评论:
        可根据用户ID/名称和创建时间范围搜索，列表显示，用户ID，用户名，评论对象，评论内容，创建时间，可以删除
        
        用户日记:
        可根据用户ID/名称和创建时间范围搜索，列表显示，用户ID，用户名，日记内容，评论数，创建时间，同步ICO，可以删除，查看评论列表
        右上角有所有评论的超链，可到所有评论页
    */
class BlogsController extends AppController
{
    public $name = 'Blogs';
    public $uses = array('Blog','User');
    public $components = array('Pagination','RequestHandler','Phpexcel','EcFlagWebservice');//,'EcFlagWebservice'
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');

    /*
        用户评论
    */
    public function index($page = 1)
    {
        $this->operator_privilege('showblogs_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/blogs/');//设置导航栏
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');//设置路径1
        $this->navigations[] = array('name' => $this->ld['blog_management'],'url' => '/blogs/showBlog');//设置路径2
        $this->navigations[] = array('name' => $this->ld['blog_comment'],'url' => '');//设置路径3
        $conditions['and']['status'] = 1;
        $conditions['and']['parent_id <>'] = 0;
        //用户ID/用户名
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
            $conditions['or']['user_id like'] = '%'.$_REQUEST['keyword'].'%';
            $_cond['or']['User.name like'] = '%'.$_REQUEST['keyword'].'%';
            $_cond['or']['User.email like'] = '%'.$_REQUEST['keyword'].'%';
            $_cond['or']['User.first_name like'] = '%'.$_REQUEST['keyword'].'%';
            $_cond['or']['User.last_name like'] = '%'.$_REQUEST['keyword'].'%';
            $user_ids = $this->User->find('list', array('fields' => 'User.id', 'conditions' => $_cond));
            $conditions['or']['Blog.user_id'] = $user_ids;
            $conditions['or']['Blog.content like'] = '%'.$_REQUEST['keyword'].'%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
           //开始时间
        if (isset($_REQUEST['start_date_time']) && $_REQUEST['start_date_time'] != '') {
            $conditions['and']['created >='] = $_REQUEST['start_date_time'];
            $this->set('start_date_time', $_REQUEST['start_date_time']);
        }
        //结束时间
        if (isset($_REQUEST['end_date_time']) && $_REQUEST['end_date_time'] != '') {
            $conditions['and']['created <='] = $_REQUEST['end_date_time'];
            $this->set('end_date_time', $_REQUEST['end_date_time']);
        }
        //查看某条日记的评论评论
        if (isset($_REQUEST['blogId']) && $_REQUEST['blogId'] != '') {
            $conditions['and']['parent_id'] = $_REQUEST['blogId'];
            $this->set('blogId', $_REQUEST['blogId']);
        }
        $cond['conditions'] = $conditions;
        //分页
        $total = $this->Blog->find('count', $cond);//获取总记录数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];//当前页
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'Blog','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Blog');
        $this->Pagination->init($conditions, $parameters, $options);
        $cond['limit'] = $rownum;
        $cond['page'] = $page;
        $cond['order'] = 'id';
        $bloginfo = $this->Blog->find('all', $cond);
        $userId = array();//记录用户Id
        $parentId = array();//记录PId
        foreach ($bloginfo as $k) {
            $userId[] = $k['Blog']['user_id'];
            $parentId[] = $k['Blog']['parent_id'];
        }
        //用户信息
        $userInfo = $this->User->find('all', array('conditions' => array('id' => $userId)));
        foreach ($bloginfo as $k => $v) {
            foreach ($userInfo as $a => $b) {
                if ($userInfo[$a]['User']['id'] == $bloginfo[$k]['Blog']['user_id']) {
                    $bloginfo[$k]['Blog']['user_name'] = $userInfo[$a]['User']['name'];
                    continue;
                }
            }
        }
        //评论对象(父级)
        $parentIdInfo = $this->Blog->find('all', array('conditions' => array('id' => $parentId, 'status' => 1)));
        foreach ($bloginfo as $k => $v) {
            foreach ($parentIdInfo as $a => $b) {
                if ($parentIdInfo[$a]['Blog']['id'] == $bloginfo[$k]['Blog']['parent_id']) {
                    $bloginfo[$k]['Blog']['parentinfo'] = $parentIdInfo[$a]['Blog']['content'];
                }
            }
        }
        $this->set('bloginfo', $bloginfo);
        //设置页面标题
        $this->set('title_for_layout', $this->ld['blog_comment'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    /*
        删除评论/日记
    */
    public function remove($id)
    {
        $this->operator_privilege('showblogs_reomve');
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_route_failure'];
        $blogInfo['id'] = $id;
        $blogInfo['status'] = 2;
        if ($this->Blog->save($blogInfo)) {
            $result['flag'] = 1;
            $result['message'] = $this->ld['delete_article_success'];
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_remove_blogcomment'].':id '.$id, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
        批量删除评论/日记
    */
    public function removeAll()
    {
        $this->operator_privilege('showblogs_reomve');
        $art_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $condition['Blog.id'] = $art_ids;
        $this->Blog->updateAll(array('Blog.status' => '2'), array('Blog.id' => $art_ids));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['batch_delete'].':'.$this->ld['log_remove_blogcomment'], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }

    /*
        日记管理
    */
    public function showBlog($page = 1)
    {
        $this->operator_privilege('showblogs_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/blogs/showBlog');//设置导航栏
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');//设置路径1
        $this->navigations[] = array('name' => $this->ld['blog_management'],'url' => '');//设置路径2
        $conditions['and']['status'] = 1;
        $conditions['and']['parent_id'] = 0;
        //用户ID/用户名
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
            $conditions['or']['user_id like'] = '%'.$_REQUEST['keyword'].'%';
            $this->set('keyword', $_REQUEST['keyword']);
            $userarr = $this->User->find('all', array('conditions' => array('name like ' => '%'.$_REQUEST['keyword'].'%')));
            if (count($userarr) > 0) {
                $userIdarr = array();
                foreach ($userarr as $k) {
                    $userIdarr[] = $k['User']['id'];
                }
                $conditions['or']['user_id'] = $userIdarr;
            }
        }
           //开始时间
        if (isset($_REQUEST['start_date_time']) && $_REQUEST['start_date_time'] != '') {
            $conditions['and']['created >='] = $_REQUEST['start_date_time'];
            $this->set('start_date_time', $_REQUEST['start_date_time']);
        }
        //结束时间
        if (isset($_REQUEST['end_date_time']) && $_REQUEST['end_date_time'] != '') {
            $conditions['and']['created <='] = $_REQUEST['end_date_time'];
            $this->set('end_date_time', $_REQUEST['end_date_time']);
        }
        $cond['conditions'] = $conditions;
        //分页
        $total = $this->Blog->find('count', $cond);//获取总记录数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];//当前页
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'Blog','action' => 'showBlog','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Blog');
        $this->Pagination->init($conditions, $parameters, $options);
        $cond['limit'] = $rownum;
        $cond['page'] = $page;
        $cond['order'] = 'id';
        $bloginfo = $this->Blog->find('all', $cond);
        $userId = array();//记录用户Id
        $blogIdarr = array();//记录id
        foreach ($bloginfo as $k) {
            $userId[] = $k['Blog']['user_id'];
            $blogIdarr[] = $k['Blog']['id'];
        }
        //用户信息
        $userInfo = $this->User->find('all', array('conditions' => array('id' => $userId)));
        //评论数量
        $replycount_list = $this->Blog->find('all', array('fields' => array('parent_id', 'count(id) as replycount'), 'conditions' => array('parent_id' => $blogIdarr, 'status' => 1), 'group' => 'parent_id'));
        foreach ($bloginfo as $k => $v) {
            foreach ($userInfo as $a => $b) {
                if ($userInfo[$a]['User']['id'] == $bloginfo[$k]['Blog']['user_id']) {
                    $bloginfo[$k]['Blog']['user_name'] = $userInfo[$a]['User']['name'];
                    continue;
                }
            }
            foreach ($replycount_list as $kk => $vv) {
                if ($bloginfo[$k]['Blog']['id'] == $vv['Blog']['parent_id']) {
                    $bloginfo[$k]['Blog']['countblog'] = $vv[0]['replycount'];
                }
            }
        }
        $this->set('bloginfo', $bloginfo);
        //设置页面标题
        $this->set('title_for_layout', $this->ld['blog_management'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
}
