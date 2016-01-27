<?php

/*****************************************************************************
 * 评分管理
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
class ScoresController extends AppController
{
    public $name = 'Scores';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Tinymce','fck','Ckeditor');
    public $uses = array('Score','ScoreI18n','ScoreLog','Product','ProductI18n','Topic','Article','User','OperatorLog');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('scores_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/comments/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reviews_search'],'url' => '/comments/');
        $this->navigations[] = array('name' => $this->ld['score_management'],'url' => '');
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->set('title_for_layout', $this->ld['score_management'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->Score->set_locale($this->backend_locale);

        /* 评分类型 */
        $score_type_list = array(
            'A' => $this->ld['article'],
            'P' => $this->ld['product'],
            'T' => $this->ld['topics'],
            'C' => $this->ld['comment'],
        );
        $this->set('score_type_list', $score_type_list);
        $conditions = '';
        if (isset($this->params['url']['score_type']) && $this->params['url']['score_type'] != '') {
            $condition['Score.type'] = $this->params['url']['score_type'];
            $this->set('score_type', $this->params['url']['score_type']);
        }
        if (isset($this->params['url']['score_name']) && $this->params['url']['score_name'] != '') {
            $condition['or']['ScoreI18n.name like'] = '%'.$this->params['url']['score_name'].'%';
            $this->set('score_name', $this->params['url']['score_name']);
        }
        $total = $this->Score->find('count', $conditions);
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['route'] = array('controller' => 'scores','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Score');
        $this->Pagination->init($conditions, $parameters, $options);
        $score_list = $this->Score->find('all', array('conditions' => $conditions, 'order' => 'Score.created desc', 'limit' => $rownum, 'page' => $page));
        $this->set('score_list', $score_list);
    }

    public function view($id = 0)
    {
        /*判断权限*/
        $this->menu_path = array('root' => '/crm/','sub' => '/comments/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reviews_search'],'url' => '/comments/');
        $this->navigations[] = array('name' => $this->ld['score_management'],'url' => '');

        /* 评分类型 */
        $score_type_list = array(
            'A' => $this->ld['article'],
            'P' => $this->ld['product'],
            'T' => $this->ld['topics'],
            'C' => $this->ld['comment'],
        );
        $this->set('score_type_list', $score_type_list);

        if ($this->RequestHandler->isPost()) {
            if ($this->data['Score']['id'] == '0') {
                $this->Score->save(array('Score' => $this->data['Score'])); //保存主表文章信息
                $id = $this->Score->getLastInsertId();
            } else {
                $this->Score->save(array('Score' => $this->data['Score'])); //保存主表文章信息
                $id = $this->data['Score']['id'];
            }
            $this->ScoreI18n->deleteAll(array('score_id' => $id));
            $scorename = '';
            foreach ($this->data['ScoreI18n'] as $k => $v) {
                $v['score_id'] = $id;
                $v['locale'] = $k;
                $this->ScoreI18n->saveAll(array('ScoreI18n' => $v));
                if ($v['locale'] == $this->backend_locale) {
                    $scorename = $v['name'];
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].' '.$this->ld['score_management'].':id '.$id.$scorename, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        if ($id == 0) {
            $this->operator_privilege('scores_add');
            $this->pageTitle = $this->ld['score_management'].' - '.$this->ld['add'].' - '.$this->configs['shop_name'];
        } else {
            $this->operator_privilege('scores_edit');
            $this->pageTitle = $this->ld['score_management'].' - '.$this->ld['edit'].' - '.$this->configs['shop_name'];
            $this->data = $this->Score->localeformat($id);
        }
        $this->set('title_for_layout', $this->pageTitle);
    }

    public function remove($id)
    {
        $this->operator_privilege('scores_delete');
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_route_failure'];
        $this->ScoreLog->deleteAll(array('ScoreLog.score_id' => $id));
        $this->ScoreI18n->deleteAll(array('ScoreI18n.score_id' => $id));
        $this->Score->deleteAll(array('Score.id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete'].' '.$this->ld['score_options'].' id:'.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function removeall()
    {
        $this->operator_privilege('scores_delete');
        $result['flag'] = 2;
        $score_checkboxes = $_REQUEST['checkboxes'];
        foreach ($score_checkboxes as $k => $v) {
            $this->ScoreLog->deleteAll(array('ScoreLog.score_id' => $v), false);
            $this->ScoreI18n->deleteAll(array('ScoreI18n.score_id' => $v), false);
            $this->Score->deleteAll(array('Score.id' => $v), false);
            $result['flag'] = 1;
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete'].$this->ld['score_options'], $this->admin['id']);
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function toggle_on_status()
    {
        $this->operator_privilege('scores_edit');
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Score->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->log(date('H:i:s').$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'].' id:'.$id, 'operation');
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function scorelog($page = 1)
    {
        $this->operator_privilege('scores_log_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/comments/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reviews_search'],'url' => '/comments/');
        $this->navigations[] = array('name' => $this->ld['score_management'],'url' => '/scores/');
        $this->navigations[] = array('name' => $this->ld['score_log'],'url' => '');

        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->set('title_for_layout', $this->ld['score_log'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->Score->set_locale($this->backend_locale);
        $this->Product->set_locale($this->backend_locale);

        /* 评分类型 */
        $score_type_list = array(
            'A' => $this->ld['article'],
            'P' => $this->ld['product'],
            'T' => $this->ld['topics'],
            'C' => $this->ld['comment'],
        );
        $this->set('score_type_list', $score_type_list);
        $conditions = '';
        if (isset($this->params['url']['score_type']) && $this->params['url']['score_type'] != '') {
            $conditions['ScoreLog.type'] = $this->params['url']['score_type'];
            $this->set('score_type', $this->params['url']['score_type']);
        }
        if (isset($this->params['url']['score_keyword']) && $this->params['url']['score_keyword'] != '') {
            $_condition['or']['User.name like'] = '%'.$this->params['url']['score_keyword'].'%';
            $_condition['or']['User.first_name like'] = '%'.$this->params['url']['score_keyword'].'%';
            $_condition['or']['User.email like'] = '%'.$this->params['url']['score_keyword'].'%';
            $_condition['or']['User.mobile like'] = '%'.$this->params['url']['score_keyword'].'%';
            $user_ids = $this->User->find('list', array('fields' => array('User.id'), 'conditions' => $_condition));
            if (!empty($user_ids) && sizeof($user_ids) > 0) {
                $conditions['or']['ScoreLog.user_id'] = $user_ids;
            }
            $conditions['or']['ScoreLog.value'] = '%'.$this->params['url']['score_keyword'].'%';
            $this->set('score_keyword', $this->params['url']['score_keyword']);
        }
        $joins = array(
            array('table' => 'svoms_users',
                  'alias' => 'User',
                  'type' => 'left',
                  'conditions' => array('User.id = ScoreLog.user_id'),
                 ),
            array('table' => 'svoms_score_i18ns',
                  'alias' => 'ScoreI18n',
                  'type' => 'left',
                  'conditions' => array('ScoreI18n.score_id = ScoreLog.score_id'),
                 ),
        );
        $conditions['ScoreI18n.locale'] = $this->backend_locale;
        $total = $this->ScoreLog->find('count', array('conditions' => $conditions, 'joins' => $joins));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['route'] = array('controller' => 'scores','action' => 'scorelog','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'ScoreLog');
        $this->Pagination->init($conditions, $parameters, $options);
        $fields = array('ScoreLog.*','ScoreI18n.*','User.name');
        $score_log_list = $this->ScoreLog->find('all', array('conditions' => $conditions, 'order' => 'ScoreLog.created desc', 'joins' => $joins, 'limit' => $rownum, 'page' => $page, 'fields' => $fields));

        $loglist = array();
        if (!empty($score_log_list) && sizeof($score_log_list) > 0) {
            $type_idarr = array();
            $scorelist = array();
            $userNamelist = array();
            foreach ($score_log_list as $v) {
                if (isset($loglist[$v['ScoreLog']['user_id'].'-'.$v['ScoreLog']['type'].'-'.$v['ScoreLog']['type_id']])) {
                    $data = array(
                        'id' => $v['ScoreLog']['id'],
                        'score_id' => $v['ScoreLog']['score_id'],
                        'value' => $v['ScoreLog']['value'],
                    );
                    $loglist[$v['ScoreLog']['user_id'].'-'.$v['ScoreLog']['type'].'-'.$v['ScoreLog']['type_id']]['score'][$v['ScoreLog']['id']] = $data;
                } else {
                    $data = array(
                        'user_id' => $v['ScoreLog']['user_id'],
                        'type' => $v['ScoreLog']['type'],
                        'type_id' => $v['ScoreLog']['type_id'],
                        'time' => $v['ScoreLog']['created'],
                        'score' => array(
                            $v['ScoreLog']['id'] => array(
                                'id' => $v['ScoreLog']['id'],
                                'score_id' => $v['ScoreLog']['score_id'],
                                'value' => $v['ScoreLog']['value'],
                            ),
                        ),
                    );
                    $loglist[$v['ScoreLog']['user_id'].'-'.$v['ScoreLog']['type'].'-'.$v['ScoreLog']['type_id']] = $data;
                }
                $scorelist[$v['ScoreLog']['score_id']] = $v['ScoreI18n']['name'];
                $userNamelist[$v['ScoreLog']['user_id']] = $v['User']['name'];
                if (!isset($type_idarr[$v['ScoreLog']['type']][$v['ScoreLog']['type_id']])) {
                    $type_idarr[$v['ScoreLog']['type']][$v['ScoreLog']['type_id']] = $v['ScoreLog']['type_id'];
                }
            }
            if (isset($type_idarr['P'])) {
                $product_list = $this->ProductI18n->find('list', array('fields' => array('ProductI18n.product_id', 'ProductI18n.name'), 'conditions' => array('ProductI18n.product_id' => $type_idarr['P'], 'ProductI18n.locale' => $this->backend_locale)));
                $this->set('product_list', $product_list);
            }
            $this->set('scorelist', $scorelist);
            $this->set('userNamelist', $userNamelist);

            if (!empty($loglist) && sizeof($loglist) > 0) {
                foreach ($loglist as $k => $v) {
                    if (isset($v['score']) && is_array($v['score']) && sizeof($v['score']) > 0) {
                        foreach ($v['score'] as $kk => $vv) {
                            $loglist[$k]['ids'][] = $vv['id'];
                        }
                        $loglist[$k]['id'] = implode(';', $loglist[$k]['ids']);
                        unset($loglist[$k]['ids']);
                    }
                }
            }
        }
        $this->set('loglist', $loglist);
    }

    public function removelog($id)
    {
        $this->operator_privilege('scores_log_delete');
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_route_failure'];
        $id_arr = split(';', $id);
        if (sizeof($id_arr) > 0) {
            $this->ScoreLog->deleteAll(array('ScoreLog.id' => $id_arr));
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete'].' '.$this->ld['score_log'].' id:'.$id, $this->admin['id']);
            }
            $result['flag'] = 1;
            $result['message'] = $this->ld['deleted_success'];
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function removelogall()
    {
        $this->operator_privilege('scores_log_delete');
        $scorelog_checkboxes = $_REQUEST['checkboxes'];
        $result['flag'] = 2;
        foreach ($scorelog_checkboxes as $k => $v) {
            $id_arr = split(';', $v);
            if (sizeof($id_arr) > 0) {
                $this->ScoreLog->deleteAll(array('ScoreLog.id' => $id_arr), false);
            }
            $result['flag'] = 1;
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete'].$this->ld['score_log'], $this->admin['id']);
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
