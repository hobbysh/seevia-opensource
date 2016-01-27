<?php

    /*
        svsns_user_fans
        用户互动管理
        可根据用户ID/名称和登陆时间范围搜索，列表显示 用户ID,用户头像，用户名称，用户粉丝数，关注数，日记数，同步设置的
        ICO(同步了就彩色，失效了则黑白) ，查看明细页，显示该用户的粉丝清单头像，和关注清单头像 根据最新关注时间降序排列
    */
class UserFansController extends AppController
{
    public $name = 'UserFans';
    public $uses = array('UserFan','User','Blog');
    public $components = array('Pagination','RequestHandler','Phpexcel','EcFlagWebservice');//,'EcFlagWebservice'
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');

    /*
        用户互动管理展示页面
    */
    public function index($page = 1)
    {
        $this->operator_privilege('user_fans_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/user_fans/');//设置导航栏
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');//设置路径1
        $this->navigations[] = array('name' => $this->ld['member_interaction_management'],'url' => '/user_fans/');//设置路径2
        //设置页面标题
        $this->set('title_for_layout', $this->ld['member_interaction_management'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $conditions = '';
        //用户ID/用户名
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
            $conditions['or']['User.name like'] = '%'.$_REQUEST['keyword'].'%';
            $conditions['or']['User.email like'] = '%'.$_REQUEST['keyword'].'%';
            $conditions['or']['User.first_name like'] = '%'.$_REQUEST['keyword'].'%';
            $conditions['or']['User.last_name like'] = '%'.$_REQUEST['keyword'].'%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        //开始时间
        if (isset($_REQUEST['start_date_time']) && $_REQUEST['start_date_time'] != '') {
            $conditions['and']['last_login_time >='] = $_REQUEST['start_date_time'];
            $this->set('start_date_time', $_REQUEST['start_date_time']);
        }
        //结束时间
        if (isset($_REQUEST['end_date_time']) && $_REQUEST['end_date_time'] != '') {
            $conditions['and']['last_login_time <='] = $_REQUEST['end_date_time'];
            $this->set('end_date_time', $_REQUEST['end_date_time']);
        }

        //记录粉丝数
        $fansArr = $this->UserFan->find('all', array('fields' => array('user_id', 'count(user_id) as count'), 'group' => array('user_id')));
        //记录关注数
        $attentionArr = $this->UserFan->find('all', array('fields' => array('fan_id', 'count(fan_id) as count'), 'group' => array('fan_id')));
        //记录日记数
        $diaryArr = $this->Blog->find('all', array('fields' => array('user_id', 'count(user_id) as count'), 'conditions' => array('status' => 1), 'group' => array('user_id')));

        $user_ids = array();
        foreach ($fansArr as $k => $v) {
            $user_ids[$v['UserFan']['user_id']] = $v['UserFan']['user_id'];
        }
        foreach ($attentionArr as $k => $v) {
            $user_ids[$v['UserFan']['fan_id']] = $v['UserFan']['fan_id'];
        }
        foreach ($diaryArr as $k => $v) {
            $user_ids[$v['Blog']['user_id']] = $v['Blog']['user_id'];
        }
        if (!empty($user_ids) && sizeof($user_ids) > 0) {
            $conditions['and']['User.id'] = $user_ids;
            $cond['conditions'] = $conditions;
            //分页
            $total = $this->User->find('count', $cond);//获取总记录数
            $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
            if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
                $page = $_REQUEST['page'];//当前页
            }
            $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
            $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'UserFan','action' => 'index','page' => $page,'limit' => $rownum);
            $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserFan');
            $this->Pagination->init($conditions, $parameters, $options);
            $cond['limit'] = $rownum;
            $cond['page'] = $page;
            $cond['order'] = 'id';
            $cond['fields'] = array('id','name','last_login_time','img01');
            $userInfo = $this->User->find('all', $cond);
            //粉丝数
            foreach ($userInfo as $k => $v) {
                foreach ($fansArr as $a => $b) {
                    if ($b['UserFan']['user_id'] == $v['User']['id']) {
                        $userInfo[$k]['User']['fancount'] = $b[0]['count'];
                        continue;
                    }
                }
            }
            //关注数
            foreach ($userInfo as $k => $v) {
                foreach ($attentionArr as $a => $b) {
                    if ($b['UserFan']['fan_id'] == $v['User']['id']) {
                        $userInfo[$k]['User']['attentioncount'] = $b[0]['count'];
                        continue;
                    }
                }
            }
            //日记数
            foreach ($userInfo as $k => $v) {
                foreach ($diaryArr as $a => $b) {
                    if ($b['Blog']['user_id'] == $v['User']['id']) {
                        $userInfo[$k]['User']['diarycount'] = $b[0]['count'];
                        continue;
                    }
                }
            }
            $this->set('userInfo', $userInfo);
        }
    }

    /*
        明细页
    */
    public function showDetailed($id = 0)
    {
        $this->operator_privilege('user_fans_view');
        if ($id != 0 && $id != '') {
            $this->set('id', $id);
            $his = isset($_REQUEST['his']) ? $_REQUEST['his'] : '1';
            $order = 'created';

            if (isset($_REQUEST['order'])) {
                ++$his;
                if ($_REQUEST['order'] != '1') {
                    $order = 'created desc';
                }
            }
            $this->set('his', $his);
            $this->set('order', $order == 'created' ? '2' : '1');
            $this->set('orderType', $order == 'created' ? '降序' : '升序');
            $fans = $this->UserFan->find('all', array('fields' => array('fan_id', 'created'), 'conditions' => array('user_id' => $id), 'order' => array($order)));//粉丝
            $attention = $this->UserFan->find('all', array('fields' => array('user_id', 'created'), 'conditions' => array('fan_id' => $id), 'order' => array($order)));//关注用户
            $userImg = $this->User->find('all', array('fields' => array('id', 'name', 'img01')));//所有用户id、图片
            foreach ($fans as $k => $v) {
                foreach ($userImg as $a => $b) {
                    if ($fans[$k]['UserFan']['fan_id'] == $userImg[$a]['User']['id']) {
                        $fans[$k]['UserFan']['img'] = $userImg[$a]['User']['img01'];
                        $fans[$k]['UserFan']['name'] = $userImg[$a]['User']['name'];
                        continue;
                    }
                }
            }
            foreach ($attention as $k => $v) {
                foreach ($userImg as $a => $b) {
                    if ($attention[$k]['UserFan']['user_id'] == $userImg[$a]['User']['id']) {
                        $attention[$k]['UserFan']['img'] = $userImg[$a]['User']['img01'];
                        $attention[$k]['UserFan']['name'] = $userImg[$a]['User']['name'];
                        continue;
                    }
                }
            }
            $this->set('fans', $fans);
            $this->set('att', $attention);
            $username = $this->User->find('all', array('fields' => array('name'), 'conditions' => array('id' => $id)));
            //设置页面标题
            $title = '用户互动明细['.$username[0]['User']['name'].']';
            $this->set('title_for_layout', $title.' - '.$this->configs['shop_name']);
            $this->menu_path = array('root' => '/crm/','sub' => '/user_fans/');//设置导航栏
            $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');//设置路径1
            $this->navigations[] = array('name' => '用户互动管理','url' => '/user_fans/');//设置路径2
            $this->navigations[] = array('name' => $title,'url' => '');//设置路径3
        } else {
            $this->redirect('/user_fans/');
        }
    }
}
