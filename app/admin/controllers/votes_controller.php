<?php

/*****************************************************************************
 * Seevia 用户管理
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
class VotesController extends AppController
{
    public $name = 'Votes';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination');
    public $uses = array('Vote','VoteI18n','VoteOption','VoteOptionI18n','VoteLog','User','OperatorLog','Language');

    public function index()
    {
        /*判断权限*/
        $this->operator_privilege('votes_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/votes/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['votes_management'],'url' => '/votes/');

        $this->Vote->set_locale($this->locale);
        $condition = '';
        if (isset($this->params['url']['date']) && $this->params['url']['date'] != '') {
            $condition['and']['Vote.start_time <='] = $this->params['url']['date'].' 00:00:00';

            $this->set('date', $this->params['url']['date']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
            $condition['and']['Vote.end_time >='] = $this->params['url']['date2'].' 23:59:59';

            $this->set('date2', $this->params['url']['date2']);
        }
        if (isset($this->params['url']['mystatus']) && $this->params['url']['mystatus'] != '') {
            $condition['and']['Vote.status'] = $this->params['url']['mystatus'];

            $this->set('mystatus', $this->params['url']['mystatus']);
        }

        $total = count($this->Vote->find('all', array('conditions' => $condition, 'fields' => 'DISTINCT Vote.id')));
        $sortClass = 'Vote';
        $page = 1;
        $rownum = isset($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters = array($rownum,$page);
        $options = array();
        $page = $this->Pagination->init($condition, $parameters, $options, $total, $rownum, $sortClass);
        $vote_list = $this->Vote->find('all', array('conditions' => $condition, 'rownum' => $rownum, 'page' => $page));
        $this->set('vote_list', $vote_list);
        $this->set('title_for_layout', $this->ld['votes_management'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function edit($id)
    {
        $this->operator_privilege('votes_edit');
        $this->menu_path = array('root' => '/crm/','sub' => '/votes/');
        $this->set('title_for_layout', $this->ld['votes_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['votes_management'],'url' => '/votes/');
        $this->navigations[] = array('name' => $this->ld['vote_edit'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['Vote']['id'] = $id;
            $this->data['Vote']['end_time'] = $this->data['Vote']['end_time'].' 23:59:59';
            $this->Vote->save(array('Vote' => $this->data['Vote']));
            foreach ($this->data['VoteI18n'] as $k => $v) {
                $v['vote_id'] = $id;
                if ($v['locale'] == $this->locale) {
                    $this_locale_vote_name = $v['name'];
                }
                $this->VoteI18n->save(array('VoteI18n' => $v));
            }
            //保存选项
            if (isset($this->data['VoteOption']) && sizeof($this->data['VoteOption']['orderby']) > 0) {
                $vo_id_arr = $this->VoteOption->find('list', array('conditions' => array('VoteOption.vote_id' => $id), 'fields' => 'VoteOption.id'));
                $this->VoteOptionI18n->deleteAll(array('vote_option_id' => $vo_id_arr));
                $this->VoteOption->deleteAll(array('vote_id' => $id));

                foreach ($this->data['VoteOptionI18n'] as $k => $v) {
                    $a = false;
                    if ($v[$this->locale.'_name'][0] != '') {
                        $a = true;
                    }
                    if (!$a) {
                        continue;
                    }
                    $voteoption['id'] = '';
                    $voteoption['stauts'] = isset($this->data['VoteOption']['status']) && isset($this->data['VoteOption']['status'][$k]) ? $this->data['VoteOption']['status'][$k] : '0';
                    $voteoption['orderby'] = ($this->data['VoteOption']['orderby'][$k] == '') ? '50' : $this->data['VoteOption']['orderby'][$k];
                    $voteoption['vote_id'] = $id;
                    $voteoption['option_count'] = isset($this->data['VoteOption']['option_count']) && isset($this->data['VoteOption']['option_count'][$k]) ? $this->data['VoteOption']['option_count'][$k] : '0';
                    $this->VoteOption->saveAll($voteoption);
                    $vo_id = $this->VoteOption->id;
                    $optionI18n = array();
                    foreach ($this->backend_locales as $lk => $l) {
                        $optionI18n[$lk]['id'] = '';
                        $optionI18n[$lk]['vote_option_id'] = $vo_id;
                        $optionI18n[$lk]['locale'] = $l['Language']['locale'];
                        $optionI18n[$lk]['name'] = '';
                        $optionI18n[$lk]['description'] = '';
                        if (isset($this->data['VoteOptionI18n']) && isset($this->data['VoteOptionI18n'][$k][$l['Language']['locale'].'_name'])) {
                            $optionI18n[$lk]['name'] = $this->data['VoteOptionI18n'][$k][$l['Language']['locale'].'_name'][0];
                        }
                        if (isset($this->data['VoteOptionI18n']) && isset($this->data['VoteOptionI18n'][$k][$l['Language']['locale'].'_description'])) {
                            $optionI18n[$lk]['description'] = $this->data['VoteOptionI18n'][$k][$l['Language']['locale'].'_description'][0];
                        }
                    }
                    $this->VoteOptionI18n->saveAll($optionI18n);
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['vote_edit'].':id '.$id.' '.$this_locale_vote_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->Vote->hasOne = array();
        $this->Vote->hasMany = array('VoteI18n' => array(
            'className' => 'VoteI18n',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'vote_id',
            ),
        );
        $vote_info = $this->Vote->findById($id);
        $this->VoteOption->hasOne = array();
        $this->VoteOption->hasMany = array('VoteOptionI18n' => array(
            'className' => 'VoteOptionI18n',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'vote_option_id',
            ),
        );
        $voteoption_list = $this->VoteOption->find('all', array('conditions' => array('VoteOption.vote_id' => $id), 'order' => 'VoteOption.orderby'));
        foreach ($voteoption_list as $vk => $vo) {
            foreach ($vo['VoteOptionI18n'] as $vov) {
                $voteoption_list[$vk]['VoteOptionI18n'][$vov['locale']] = $vov;
            }
        }
        $this->set('voteoption_list', $voteoption_list);

        foreach ($vote_info['VoteI18n'] as $k => $v) {
            $vote_info['VoteI18n'][$v['locale']] = $v;
        }
        $this->set('vote_info', $vote_info);
        $this->navigations[] = array('name' => $vote_info['VoteI18n'][$this->backend_locale]['name'],'url' => '');
    }

    public function add()
    {
        $this->operator_privilege('votes_add');
        $this->menu_path = array('root' => '/crm/','sub' => '/votes/');
        $this->set('title_for_layout', $this->ld['votes_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['votes_management'],'url' => '/votes/');
        $this->navigations[] = array('name' => $this->ld['vote_add'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['Vote']['end_time'] = $this->data['Vote']['end_time'].' 23:59:59';
            $this->Vote->saveAll(array('Vote' => $this->data['Vote']));
            foreach ($this->data['VoteI18n'] as $k => $v) {
                $v['vote_id'] = $this->Vote->getLastInsertId();
                if ($v['locale'] == $this->locale) {
                    $this_locale_vote_name = $v['name'];
                }
                $this->VoteI18n->saveAll(array('VoteI18n' => $v));
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['vote_add'].':'.$this_locale_vote_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
    }

    public function remove($id)
    {
        $this->Vote->hasOne = array();
        $pn = $this->VoteI18n->find('list', array('fields' => array('VoteI18n.vote_id', 'VoteI18n.name'), 'conditions' => array('VoteI18n.vote_id' => $id, 'VoteI18n.locale' => $this->locale)));
        $this->Vote->deleteAll(array('id' => $id));
        $this->VoteI18n->deleteAll(array('vote_id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_vote'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }

    public function option_list($vote_id)
    {
        $this->operator_privilege('votes_option_list');
        $this->set('title_for_layout', $this->ld['votes_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['votes_management'],'url' => '/votes/');
        $this->Vote->set_locale($this->locale);
        $vote_info = $this->Vote->find(array('Vote.id' => $vote_id));
        $this->navigations[] = array('name' => $this->ld['votes'].'（'.$vote_info['VoteI18n']['name'].'）','url' => '');

        $this->VoteOption->set_locale($this->locale);
        $voteoption_list = $this->VoteOption->find('all', array('conditions' => array('vote_id' => $vote_id)));
        $this->set('voteoption_list', $voteoption_list);
        $this->set('vote_id', $vote_id);
    }

    public function option_add($vote_id)
    {
        $this->operator_privilege('votes_option_list');
        $this->set('title_for_layout', $this->ld['votes_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['votes_management'],'url' => '/votes/');
        $this->Vote->set_locale($this->locale);
        $vote_info = $this->Vote->find(array('Vote.id' => $vote_id));
        $vote_name = empty($vote_info['VoteI18n']['name']) ? '' : $vote_info['VoteI18n']['name'];
        $this->navigations[] = array('name' => $this->ld['vote_add_option'].$vote_info['VoteI18n']['name'],'url' => '');
        $this->set('vote_name', $vote_name);
        if ($this->RequestHandler->isPost()) {
            $name_list = empty($this->params['form']['name_list']) ? array() : $this->params['form']['name_list'];
            if (!empty($name_list)) {
                $id_count = count($name_list[$this->locale]);
                for ($i = 0; $i < $id_count; ++$i) {
                    $this->data['VoteOption']['vote_id'] = $vote_id;
                    $this->data['VoteOption']['id'] = '';
                    $this->VoteOption->saveAll(array('VoteOption' => $this->data['VoteOption']));
                    $vote_option_id = $this->VoteOption->getLastInsertId();
                    foreach ($this->data['VoteOptionI18n'] as $k => $v) {
                        $v['id'] = '';
                        $v['name'] = empty($name_list[$v['locale']][$i]) ? '' : $name_list[$v['locale']][$i];
                        $v['vote_option_id'] = $vote_option_id;
                        if ($v['locale'] == $this->locale) {
                            $this_locale_vote_option_name = $v['name'];
                        }
                        $this->VoteOptionI18n->saveAll(array('VoteOptionI18n' => $v));
                    }
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['vote_add_options'].':'.$this_locale_vote_option_name, $this->admin['id']);
            }
            $this->redirect('/votes/option_list/'.$vote_id);
            $this->VoteOption->set_locale($this->locale);
            $voteoption_list = $this->VoteOption->find('all', array('conditions' => array('vote_id' => $vote_id)));
            $this->set('voteoption_list', $voteoption_list);
            $this->set('vote_id', $vote_id);
        }
    }
    public function option_edit($vote_id, $option_vote_id)
    {
        $this->operator_privilege('votes_option_list');
        $this->set('title_for_layout', $this->ld['votes_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['votes_management'],'url' => '/votes/');
        $this->Vote->set_locale($this->locale);
        $vote_info = $this->Vote->find(array('Vote.id' => $vote_id));
        $this->navigations[] = array('name' => $this->ld['vote_add_option'].$vote_info['VoteI18n']['name'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['VoteOption']['id'] = $option_vote_id;
            $this->data['VoteOption']['vote_id'] = $vote_id;
            $this->VoteOption->save(array('VoteOption' => $this->data['VoteOption']));
            foreach ($this->data['VoteOptionI18n'] as $k => $v) {
                $v['vote_option_id'] = $option_vote_id;
                if ($v['locale'] == $this->locale) {
                    $this_locale_vote_option_name = $v['name'];
                }
                $this->VoteOptionI18n->save(array('VoteOptionI18n' => $v));
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['vote_add_options'].':'.$this_locale_vote_option_name, $this->admin['id']);
            }
            $this->redirect('/votes/option_list/'.$vote_id);
        }

        $this->VoteOption->hasOne = array();
        $this->VoteOption->hasMany = array('VoteOptionI18n' => array(
            'className' => 'VoteOptionI18n',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'vote_option_id',
            ),
        );
        $vote_option_info = $this->VoteOption->findById($option_vote_id);
        foreach ($vote_option_info['VoteOptionI18n'] as $k => $v) {
            $vote_option_info['VoteOptionI18n'][$v['locale']] = $v;
        }

        $this->set('vote_option_info', $vote_option_info);
        $this->set('vote_id', $vote_id);
        $this->set('option_vote_id', $option_vote_id);
    }
    public function option_remove($option_vote_id)
    {
        $this->VoteOption->hasOne = array();
        $pn = $this->VoteOptionI18n->find('list', array('fields' => array('VoteOptionI18n.vote_option_id', 'VoteOptionI18n.name'), 'conditions' => array('VoteOptionI18n.vote_option_id' => $option_vote_id, 'VoteOptionI18n.locale' => $this->locale)));
        $this->VoteOption->deleteAll(array('id' => $option_vote_id));
        $this->VoteOptionI18n->deleteAll(array('vote_option_id' => $option_vote_id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_vote_option'].':'.$pn[$option_vote_id], $this->admin['id']);
        }
        $this->redirect('/votes');
        //$this->flash("",'',10);
    }
    public function vote_logs_remove($vote_log_id)
    {
        $this->VoteLog->hasOne = array();
        $this->VoteLog->deleteAll(array('id' => $vote_log_id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_vote_log'].':'.$vote_log_id, $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }
    //log日志
    public function vote_logs($id)
    {
        $this->operator_privilege('vote_logs_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/votes/');
        $this->set('title_for_layout', $this->ld['vote_log'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['votes_management'],'url' => '/votes/');
        $this->navigations[] = array('name' => $this->ld['vote_log'],'url' => '');

        //用户
        $user_list = $this->User->find('all', array('fields' => 'DISTINCT User.id,User.name'));
        $new_user_list = array();
        foreach ($user_list as $v) {
            $new_user_list[$v['User']['id']] = $v['User']['name'];
        }
        //主题
        $this->Vote->set_locale($this->locale);
        $vote_list = $this->Vote->find('all', array('fields' => 'DISTINCT Vote.id,VoteI18n.name'));
        $new_vote_list = array();
        foreach ($vote_list as $v) {
            $new_vote_list[$v['Vote']['id']] = $v['VoteI18n']['name'];
        }
        //日志
        $vote_logs_list = $this->VoteLog->find('all', array('conditions' => array('VoteLog.vote_id' => $id)));
        foreach ($vote_logs_list as $k => $v) {
            $vote_logs_list[$k]['VoteLog']['vote_option_id_arr'] = explode(',', $vote_logs_list[$k]['VoteLog']['vote_option_id']);
        }
        //选项
        $this->VoteOption->set_locale($this->locale);
        $voteoption_list = $this->VoteOption->find('all');
        $new_voteoption_list = array();
        foreach ($voteoption_list as $v) {
            $new_voteoption_list[$v['VoteOption']['id']] = $v['VoteOptionI18n']['name'];
        }
        $this->set('new_user_list', $new_user_list);//用户
        $this->set('new_vote_list', $new_vote_list);//主题
        $this->set('vote_logs_list', $vote_logs_list);//日志
        $this->set('new_voteoption_list', $new_voteoption_list);//选项
        //leo20090722导航显示

        $this->navigations[] = array('name' => $new_vote_list[$id],'url' => '');
    }

    public function vote_logs_edit($vote_logs_id)
    {
        $this->operator_privilege('vote_logs_view');
        $this->set('title_for_layout', $this->ld['vote_log'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['vote_log'],'url' => '/vote_logs/');
        $this->navigations[] = array('name' => $this->ld['vote_edit_log'],'url' => '');

        $this->set('vote_logs_id', $vote_logs_id);

        //用户
        $user_list = $this->User->find('all', array('fields' => 'DISTINCT User.id,User.name'));
        $new_user_list = array();
        foreach ($user_list as $v) {
            $new_user_list[$v['User']['id']] = $v['User']['name'];
        }
        //主题
        $this->Vote->set_locale($this->locale);
        $vote_list = $this->Vote->find('all', array('fields' => 'DISTINCT Vote.id,VoteI18n.name'));
        $new_vote_list = array();
        foreach ($vote_list as $v) {
            $new_vote_list[$v['Vote']['id']] = $v['VoteI18n']['name'];
        }
        //日志
        $vote_logs_list = $this->VoteLog->findById($vote_logs_id);
        $vote_logs_list['VoteLog']['vote_option_id_arr'] = explode(',', $vote_logs_list['VoteLog']['vote_option_id']);
        if ($this->RequestHandler->isPost()) {
            $this->data['VoteLog']['id'] = $vote_logs_id;
            $this->VoteLog->save($this->data);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['vote_edit_log'].' '.$id, $this->admin['id']);
            }
            $this->redirect('/votes/vote_logs/'.$id);
            //$this->flash("调查日志 ".$new_vote_list[$vote_logs_list["VoteLog"]["vote_id"]]."  编辑成功。点击这里继续编辑该 调查选项。",'/votes/vote_logs_edit/'.$vote_logs_id.'/',10);
        }
        //选项
        $this->VoteOption->set_locale($this->locale);
        $voteoption_list = $this->VoteOption->find('all');
        $new_voteoption_list = array();
        foreach ($voteoption_list as $v) {
            $new_voteoption_list[$v['VoteOption']['id']] = $v['VoteOptionI18n']['name'];
        }
        $this->set('new_user_list', $new_user_list);//用户
        $this->set('new_vote_list', $new_vote_list);//主题
        $this->set('vote_logs_list', $vote_logs_list);//日志
        $this->set('new_voteoption_list', $new_voteoption_list);//选项
    }
    /**
     * 删除.
     */
    public function batch_operations()
    {
        $brand_id = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        pr($_REQUEST);

        if ($brand_id != 0) {
            $condition['Vote.id'] = $brand_id;
            $this->Vote->deleteAll($condition);
            $this->VoteI18n->deleteAll(array('VoteI18n.Vote_id' => $brand_id));

            $result['flag'] = 1;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die();
    }
}
