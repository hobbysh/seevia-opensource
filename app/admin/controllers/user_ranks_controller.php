<?php

/*****************************************************************************
 * Seevia 会员等级
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
*****************************************************************************/
class UserRanksController  extends AppController
{
    public $name = 'UserRanks';

    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('UserRank','UserRankI18n');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('user_ranks_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        /*end*/
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->set('title_for_layout', $this->ld['member_level'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['member_level'],'url' => '/user_ranks');
        $this->set('navigations', $this->navigations);
        $this->UserRank->set_locale($this->backend_locale);
        $conditions = '';
        $cond['conditions'] = $conditions;
        //分页
        $total = $this->UserRank->find('count', $cond);//获取总记录数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];//当前页
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'UserRank','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserRank');
        $this->Pagination->init($conditions, $parameters, $options);
        $cond['limit'] = $rownum;
        $cond['page'] = $page;
        $cond['order'] = 'UserRank.id';
        $userrank_list = $this->UserRank->find('all', $cond);
        $this->set('userrank_list', $userrank_list);
    }

    public function view($id = 0)
    {
        if ($id == '0') {
            $this->operator_privilege('user_ranks_add');
        } else {
            $this->operator_privilege('user_ranks_edit');
        }
        /*end*/
        $this->menu_path = array('root' => '/crm/','sub' => '/users/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['member_level'],'url' => '/user_ranks');
        if ($id == '0') {
            $this->set('title_for_layout', $this->ld['member_level'].' - '.$this->ld['add'].$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['member_level'].' - '.$this->ld['add'],'url' => '');
        } else {
            $this->set('title_for_layout', $this->ld['member_level'].' - '.$this->ld['edit'].$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['member_level'].' - '.$this->ld['edit'],'url' => '');
        }
        $this->set('navigations', $this->navigations);
        if ($this->RequestHandler->isPost()) {
            if ($this->data['UserRank']['id'] == '0') {
                $data['UserRank']['created'] = $this->data['UserRank']['allow_buy'];
            } else {
                $data['UserRank']['id'] = $this->data['UserRank']['id'];
            }
            $data['UserRank']['code'] = $this->data['UserRank']['code'] != '' ? $this->data['UserRank']['code'] : '';
            $data['UserRank']['balance'] = $this->data['UserRank']['balance'] != '' ? $this->data['UserRank']['balance'] : '0';
            $data['UserRank']['min_points'] = $this->data['UserRank']['min_points'] != '' ? $this->data['UserRank']['min_points'] : '0';
            $data['UserRank']['max_points'] = $this->data['UserRank']['max_points'] != '' ? $this->data['UserRank']['max_points'] : '0';
            $data['UserRank']['discount'] = $this->data['UserRank']['discount'] != '' ? $this->data['UserRank']['discount'] : '0';
            $data['UserRank']['show_price'] = $this->data['UserRank']['show_price'];
            $data['UserRank']['allow_buy'] = $this->data['UserRank']['allow_buy'];
            $data['UserRank']['special_rank'] = $this->data['UserRank']['special_rank'];
            $data['UserRank']['modified'] = $this->data['UserRank']['allow_buy'];

            if ($this->data['UserRank']['id'] == '0') {
                //添加
                $this->UserRank->save($data['UserRank']);
                $id = $this->UserRank->id;//获取上一个插入记录的id
            } else {
                //编辑
                $this->UserRank->save($data['UserRank']);
            }
            $this->UserRankI18n->deleteall(array('user_rank_id' => $this->data['UserRank']['id'])); //删除原有多语言
            foreach ($this->data['UserRankI18n'] as $v) {
                $rankI18n_info = array(
                    'id' => isset($v['id']) ? $v['id'] : '',
                    'locale' => $v['locale'],
                    'user_rank_id' => isset($v['user_rank_id']) ? $v['user_rank_id'] : $id,
                    'name' => isset($v['name']) ? $v['name'] : '',
                );
                $this->UserRankI18n->saveall(array('UserRankI18n' => $rankI18n_info)); //更新多语言
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $log_message = '';
                if ($this->data['UserRank']['id'] == '0') {
                    $log_message = $this->ld['log_add_user_rank'];
                } else {
                    $log_message = $this->ld['log_edit_user_rank'];
                }
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$log_message.':id '.$id, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $userrank = $this->UserRank->localeformat($id);
        $this->set('id', $id);
        $this->set('userrank', $userrank);
    }

    /*
        删除等级
    */
    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('user_ranks_remove');
        /*end*/
        $data['UserRankI18n']['user_rank_id'] = $id;
        $data['UserRank']['user_rank_id'] = $id;
        $this->UserRankI18n->delete($data['UserRankI18n']);
        $this->UserRank->delete($data['UserRank']);
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_remove_user_rank'].':id '.$id, $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }

    /*
        批量删除
    */
    public function removeAll()
    {
        if ($this->RequestHandler->isPost()) {
            $art_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
            foreach ($art_ids as $k => $v) {
                $this->UserRankI18n->delete(array('UserRankI18n.user_rank_id' => $v));
                $this->UserRank->delete(array('UserRank.id' => $v));
            }
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['batch_delete'].':'.$this->ld['log_remove_user_rank'], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }

    public function check_code()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['code'] = 0;
        $result['msg'] = '';
        $UserRank_Id = isset($_POST['UserRank_Id']) ? $_POST['UserRank_Id'] : 0;
        $UserRank_code = isset($_POST['UserRank_code']) ? $_POST['UserRank_code'] : '';
        $data_count = $this->UserRank->find('count', array('conditions' => array('UserRank.code' => $UserRank_code, 'UserRank.id !=' => $UserRank_Id)));
        if ($data_count == 0) {
            $result['code'] = 1;
            $result['msg'] = '';
        } else {
            $result['msg'] = $this->ld['code_already_exists'];
        }
        die(json_encode($result));
    }
}
