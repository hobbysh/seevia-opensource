<?php

/**
 *这是一个名为 WeiboRbsController 的控制器
 *后台商品管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Vendor', 'webo', array('file' => 'weibooauth.php'));//to add weibo class
App::import('Vendor', 'weibo2', array('file' => 'saetv2.php'));
//include(ROOT."/vendors/nusoap/nusoap.php");
App::import('Vendor', 'nusoap');
App::import('Controller', 'Commons');//加载公共控制器
class WeiboRbsController extends AppController
{
    public $name = 'WeiboRbs';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Html','Pagination');
    public $uses = array('WeiboRb','WeiboOp','WeiboOpLog','SynchroOperator','Resource','Application','ApplicationConfig','ApplicationConfigI18n','WeiboTeam','WeiboLog','WeiboThm','Product','ProductI18n','CategoryProduct18n','OperatorLog');

    /**
     *显示商品列表.
     */
    public function index($page = 1)
    {
        if (!in_array('APP-WEIBO', $this->apps['codes'])) {
            $this->redirect('/');
        }
        $this->operator_privilege('weibo_rbs_view');
        $this->navigations[] = array('name' => $this->ld['marketing_management'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reply_content'],'url' => '/weibo_rbs/');

        $condition = '';
        if (isset($this->params['url']['key_words']) && $this->params['url']['key_words'] != '') {
            $condition['WeiboRb.key_words LIKE'] = '%'.$this->params['url']['key_words'].'%';
            $this->set('key_words', $this->params['url']['key_words']);
        }
        if (isset($this->params['url']['start_time']) && $this->params['url']['start_time'] != '') {
            $condition['WeiboRb.start_time <='] = $this->params['url']['start_time'];
            $this->set('start_time', $this->params['url']['start_time']);
        }
        if (isset($this->params['url']['end_time']) && $this->params['url']['end_time'] != '') {
            $condition['WeiboRb.end_time >='] = $this->params['url']['end_time'].' 23:59:59';
            $this->set('end_time', $this->params['url']['end_time']);
        }

        $total = $this->WeiboRb->find('count', array('conditions' => $condition));
        //$sortClass='Article';
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'orders','action' => 'index');
        $parameters = array($rownum,$page);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'WeiboRb');
        $page = $this->Pagination->init($condition, $parameters, $options);
        $weiborb_infos = $this->WeiboRb->find('all', array('conditions' => $condition, 'order' => 'WeiboRb.created desc', 'limit' => $rownum, 'page' => $page));
        $this->set('weiborb_infos', $weiborb_infos);
        $this->set('title_for_layout', $this->ld['reply_content'].' - '.$this->ld['page'].' '.$page.' - '.$this->ld['marketing_management']);
        //pr($weiborb_infos);die();
        //zhou add group sina user
        //pr($this->WeiboOp->find('all',array('fields'=>array('DISTINCT WeiboOp.name','WeiboOp.sid')),'false'));die();
        if (!empty($this->params['url'])) {
            $url = $this->params['url']['url'].'?';
            //$url="";
            foreach ($this->params['url'] as $k => $v) {
                if ($k == 'url') {
                } else {
                    $url .= $k.'='.$v.'&';
                }
            }
        }
        $_SESSION['index_url'] = $url;
    }
    //编辑页
    public function edit($id)
    {
        $this->operator_privilege('weibo_rbs_edit');
        $this->set('title_for_layout', $this->ld['edit_reply_content'].'- '.$this->ld['reply_content'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['marketing_management'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reply_content'],'url' => '/weibo_rbs/');
        $this->navigations[] = array('name' => $this->ld['edit_reply_content'],'url' => '');

        $weibo = $this->WeiboRb->find('first', array('conditions' => array('WeiboRb.id' => $id)));
        //pr($weibo);
        $this->set('weibo', $weibo);
        if ($this->RequestHandler->isPost()) {
            $this->WeiboRb->save(array('WeiboRb' => $this->data['WeiboRb']));
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_reply_content'].':id '.$id, $this->admin['id']);
            }
        //	$this->redirect('/weibo_rbs/');
           $this->redirect('/'.$_SESSION['index_url']);
        }
    }

    public function add()
    {
        $this->operator_privilege('weibo_rbs_add');
        $this->set('title_for_layout', $this->ld['add_reply_content'].' - '.$this->ld['reply_content'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['marketing_management'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reply_content'],'url' => '/weibo_rbs/');
        $this->navigations[] = array('name' => $this->ld['add_reply_content'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['WeiboRb']['key_words'] = !empty($this->data['WeiboRb']['key_words']) ? $this->data['WeiboRb']['key_words'] : '';
            $this->data['WeiboRb']['comment'] = !empty($this->data['WeiboRb']['comment']) ? $this->data['WeiboRb']['comment'] : '';
            $this->data['WeiboRb']['key_words'] = str_replace('，', ',', $this->data['WeiboRb']['key_words']);
            $this->WeiboRb->saveAll(array('WeiboRb' => $this->data['WeiboRb']));
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_reply_content'], $this->admin['id']);
            }
            $this->redirect('/weibo_rbs/');
        }
    }
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $this->WeiboRb->deleteAll(array('WeiboRb.id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_reply_content'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function batch_operations()
    {
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->WeiboRb->deleteAll(array('id' => $v));
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }
    /**
     *编辑商品信息 新增/编辑.
     *
     *@param int $id 输入商品ID
     */
    public function op_index($page = 1)
    {
        $this->operator_privilege('weibo_ops_view');
        $this->set('title_for_layout', $this->ld['collect_information'].' - '.$this->ld['weibo_management']);
        $this->navigations[] = array('name' => $this->ld['weibo_management'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['collect_information'],'url' => '/weibo_rbs/op_index');

         //zhou add to see the sina user
        $oper = $this->check_weibo_op();

        $this->set('oper', $oper);
        $condition = '';
        if (isset($this->params['url']['name']) && $this->params['url']['name'] != '') {
            $condition['WeiboOp.name LIKE'] = '%'.$this->params['url']['name'].'%';
            $this->set('name', $this->params['url']['name']);
        }
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '') {
            $condition['WeiboOp.status'] = $this->params['url']['status'];
            $this->set('status', $this->params['url']['status']);
        }
        //pr($condition);
        $total = $this->WeiboOp->find('count', array('conditions' => $condition));
        //$sortClass='Article';
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'orders','action' => 'index');
        $parameters = array($rownum,$page);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'WeiboOp');
        $page = $this->Pagination->init($condition, $parameters, $options);
        $weiboop_infos = $this->WeiboOp->find('all', array('conditions' => $condition, 'order' => 'WeiboOp.created desc', 'limit' => $rownum, 'page' => $page));
        $this->set('weiboop_infos', $weiboop_infos);
        //zhou add group sina user
        $this->set('group_user', $this->WeiboOp->find('all', array('fields' => array('DISTINCT WeiboOp.name', 'WeiboOp.sid')), 'false'));

    //	pr($weiboop_infos);die();
    }

    //模版列表
    public function thm_index($page = 1)
    {
        $this->operator_privilege('weibo_ops_view');
        $this->navigations[] = array('name' => $this->ld['weibo_management'],'url' => '');
        $this->navigations[] = array('name' => '微博模版','url' => '/weibo_rbs/thm_index');

         //zhou add to see the sina user
        $oper = $this->check_weibo_op();
        //
        $this->set('oper', $oper);
        $condition = '';
        if (isset($this->params['url']['name']) && $this->params['url']['name'] != '') {
            $condition['WeiboThm.name LIKE'] = '%'.$this->params['url']['name'].'%';
            $this->set('name', $this->params['url']['name']);
        }
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '') {
            $condition['WeiboThm.status'] = $this->params['url']['status'];
            $this->set('status', $this->params['url']['status']);
        }
        //pr($condition);
        $total = $this->WeiboThm->find('count', array('conditions' => $condition));
        //$sortClass='Article';
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'orders','action' => 'index');
        $parameters = array($rownum,$page);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'WeiboThm');
        $page = $this->Pagination->init($condition, $parameters, $options);
        $weiboop_infos = $this->WeiboThm->find('all', array('conditions' => $condition, 'order' => 'WeiboThm.created desc', 'limit' => $rownum, 'page' => $page));
        //pr($weiboop_infos);die();
        $this->set('weiboop_infos', $weiboop_infos);
        //zhou add group sina user
        $this->set('title_for_layout', '微博模版'.' - '.$this->ld['page'].' '.$page.' - '.$this->ld['weibo_management']);

    //	pr($weiboop_infos);die();
    }

    public function thm_view($id = '')
    {
        $this->operator_privilege('weibo_ops_view');
        $this->set('title_for_layout', '微博模版'.' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['weibo_management'],'url' => '');
        $this->navigations[] = array('name' => '微博模版','url' => '/weibo_rbs/thm_index/');
        $shop_name = $this->configs['shop_name'];

        if ($this->RequestHandler->isPost()) {
            $this->data['WeiboThm']['type'] = 'template';
            if ($this->data['WeiboThm']['is_default'] == '1') {
                $this->WeiboThm->updateAll(array('WeiboThm.is_default' => 0));
            }

            if (isset($this->data['WeiboThm']['id']) && $this->data['WeiboThm']['id'] != '') {
                $this->WeiboThm->save(array('WeiboThm' => $this->data['WeiboThm'])); //保存
            } else {
                $this->WeiboThm->save(array('WeiboThm' => $this->data['WeiboThm'])); //保存
                $id = $this->WeiboThm->id();
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'微博模版更新成功:id '.$id, $this->admin['id']);
            }
            $this->redirect('/weibo_rbs/thm_index');
        }
        $this->data = $this->WeiboThm->find('first', array('conditions' => array('WeiboThm.id' => $id)));
        //pr($this->data);
        if (isset($this->data['WeiboThm']['weibo_template_name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].$this->ld['microblog_template'].'-'.$this->data['WeiboThm']['weibo_template_name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['microblog_template'],'url' => '');
        }
    }

    public function weib_pro($id = '')
    {
        //找商品的信息对应模版
        if (empty($id)) {
            return false;
        }
        $pro = $this->Product->find('first', array('conditions' => array('Product.id' => $id), 'fields' => array('Product.weithm', 'Product.img_original', 'Product.category_id', 'Product.shop_price')), false);
        $p_name = $this->ProductI18n->find('first', array('conditions' => array('ProductI18n.product_id' => $id, 'ProductI18n.locale' => $this->locale), 'fields' => array('ProductI18n.name')), false);
        $lins = $this->get_pro_link($p_name['ProductI18n']['name'], $id);
        $cat = $this->CategoryProduct18n->find('first', array('conditions' => array('CategoryProduct18n.category_id' => $pro['Product']['category_id'], 'CategoryProduct18n.locale' => $this->locale), 'fields' => array('CategoryProduct18n.name')), false);
        //echo $lins;
        //$lins=urlencode($lins);
        $this->check_weibo_op();
        //没有的选默认
        $thm = $this->weithm_get($pro['Product']['weithm']);

        if ($thm) {
            $thm = str_replace('$shop', $this->configs['shop_name'], $thm);
            $thm = str_replace('$product', $p_name['ProductI18n']['name'], $thm);
            $thm = str_replace('$link', $lins, $thm);
            $thm = str_replace('$cat', $cat['CategoryProduct18n']['name'], $thm);
            $thm = str_replace('$price', $pro['Product']['shop_price'], $thm);
            //echo $thm;
            $p = $this->build_sina_oper();
//			发送
            $return_code = $this->weithm_send($p, $thm, $pro['Product']['img_original']);
            $code = $this->move_thm_log($return_code, $thm);
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$code.'");	window.location.href="/admin/products/"</script>';
            die();
        } else {
            $code = '请加模板';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$code.'");	window.location.href="/admin/pages/home"</script>';
            die();
        }
    }

    //功能函数
    public function thm_add($thm)
    {
        if (empty($thm)) {
            return false;
        } else {
            return $this->$WeiboThm->save($thm);
        }
    }

    public function thm_edit($thm)
    {
        if (empty($thm)) {
            return false;
        } else {
            return $this->$WeiboThm->save($thm);
        }
    }

    public function weithm_send($p, $content, $img)
    {
        return $p->upload($content, $img);
    }

    public function weithm_get($id)
    {
        $thm = $this->WeiboThm->find('first', array('conditions' => array('WeiboThm.id' => $id), 'fields' => array('WeiboThm.weibo_thm_body')));

        if (!$thm) {
            $thm = $this->weithm_get_def();
        }
        if (isset($thm['WeiboThm']['weibo_thm_body'])) {
            return $thm['WeiboThm']['weibo_thm_body'];
        } else {
            return false;
        }
    }

    public function weithm_get_def()
    {
        return $this->WeiboThm->find('first', array('conditions' => array('WeiboThm.is_default' => '1'), 'fields' => array('WeiboThm.weibo_thm_body')));
    }

    public function reply($id)
    {
        $this->operator_privilege('weibo_ops_replay');
        $condition = '';
        $condition['WeiboOp.status'] = 2;
        if (!empty($id)) {
            $condition['WeiboOp.id'] = $id;
        }
        $weiboop_infos = $this->WeiboOp->find('all', array('conditions' => $condition));
        if (!empty($weiboop_infos)) {
            $p = $this->build_sina_oper();
            foreach ($weiboop_infos as $wkk => $wvv) {
                //$result=$this->conmment_weibo($p,$wvv['WeiboOp']['wid'],$wvv['WeiboOp']['to_comment']);
                $result = $this->repost_weibo($p, $wvv['WeiboOp']['wid'], $wvv['WeiboOp']['to_comment']);
                if (empty($result) || isset($result['error_code'])) {
                    $weibo_op_log_data = array(
                        'sid' => $wvv['WeiboOp']['sid'],
                        'wid' => $wvv['WeiboOp']['wid'],
                        'name' => $wvv['WeiboOp']['name'],
                        'content' => $wvv['WeiboOp']['to_comment'],
                        'robort_id' => $wvv['WeiboOp']['robort_id'],
                        'status' => 2,
                    );
                    $error = 'result is null';
                    if (isset($result['error_code'])) {
                        $error = 'request:'.$result['request'].';'.$result['error_code'].':'.$result['error'];
                    }
                    $weibo_op_log_data['error_msg'] = $error;
                } else {
                    $weibo_op_log_data = array(
                        'sid' => $wvv['WeiboOp']['sid'],
                        'wid' => $wvv['WeiboOp']['wid'],
                        'name' => $wvv['WeiboOp']['name'],
                        'content' => $wvv['WeiboOp']['to_comment'],
                        'robort_id' => $wvv['WeiboOp']['robort_id'],
                        'status' => 3,
                    );
                }

                $name = $wvv['WeiboOp']['name'];
                $this->WeiboOpLog->saveAll(array('WeiboOpLog' => $weibo_op_log_data));
                $this->WeiboOp->deleteAll(array('WeiboOp.id' => $wvv['WeiboOp']['id']));
            }
        }
        if (empty($id)) {
            $this->redirect('/weibo_op_logs/');
        //	$weiboop_logs=$this->WeiboOpLog->find("all");
        } else {
            $this->redirect('/weibo_op_logs?name='.$name);
        //	$weiboop_logs=$this->WeiboOpLog->find("all",array("conditions"=>array('WeiboOpLog.id'=>$id)));
        }
    //	$this->set('weiboop_logs',$weiboop_logs);
    }

    public function reply2($id)
    {
        $this->operator_privilege('weibo_ops_replay');
        $condition = '';
        $condition['WeiboOp.status'] = 2;
        if (!empty($id)) {
            $condition['WeiboOp.id'] = $id;
        }
        $weiboop_infos = $this->WeiboOp->find('all', array('conditions' => $condition));
    //	pr($weiboop_infos);
        if (!empty($weiboop_infos)) {
            $p = $this->build_sina_oper();
            foreach ($weiboop_infos as $wkk => $wvv) {
                //pr($p);exit;
                //$result=$this->conmment_weibo($p,$wvv['WeiboOp']['wid'],$wvv['WeiboOp']['to_comment']);
                $result = $this->conmment_weibo($p, $wvv['WeiboOp']['wid'], $wvv['WeiboOp']['to_comment']);
                if (empty($result) || isset($result['error_code'])) {
                    $weibo_op_log_data = array(
                    'sid' => $wvv['WeiboOp']['sid'],
                    'wid' => $wvv['WeiboOp']['wid'],
                    'name' => $wvv['WeiboOp']['name'],
                    'content' => $wvv['WeiboOp']['to_comment'],
                    'robort_id' => $wvv['WeiboOp']['robort_id'],
                    'status' => 2,
                );
                } else {
                    $weibo_op_log_data = array(
                        'sid' => $wvv['WeiboOp']['sid'],
                        'wid' => $wvv['WeiboOp']['wid'],
                        'name' => $wvv['WeiboOp']['name'],
                        'content' => $wvv['WeiboOp']['to_comment'],
                        'robort_id' => $wvv['WeiboOp']['robort_id'],
                        'status' => 3,
                    );
                }

                $name = $wvv['WeiboOp']['name'];
                $this->WeiboOpLog->saveAll(array('WeiboOpLog' => $weibo_op_log_data));
                $this->WeiboOp->deleteAll(array('WeiboOp.id' => $wvv['WeiboOp']['id']));
            }
        }
        if (empty($id)) {
            $this->redirect('/weibo_op_logs/');
        //	$weiboop_logs=$this->WeiboOpLog->find("all");
        } else {
            $this->redirect('/weibo_op_logs?name='.$name);
        //	$weiboop_logs=$this->WeiboOpLog->find("all",array("conditions"=>array('WeiboOpLog.id'=>$id)));
        }
    //	$this->set('weiboop_logs',$weiboop_logs);
    }

    public function op_edit($id)
    {
        $this->operator_privilege('weibo_ops_edit');
        $this->set('title_for_layout', $this->ld['edit_collect_information'].' - '.$this->ld['weibo_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['weibo_management'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['collect_information'],'url' => '/weibo_rbs/op_index');
        $this->navigations[] = array('name' => $this->ld['weibo_management'],'url' => '');

        $weibo = $this->WeiboOp->find('first', array('conditions' => array('WeiboOp.id' => $id)));
        //pr($weibo);
        $this->set('weibo', $weibo);
        if ($this->RequestHandler->isPost()) {
            //pr($this->data['WeiboOp']);
            if (empty($this->data['WeiboOp']['to_comment'])) {
                $this->data['WeiboOp']['status'] = 1;
            } else {
                $this->data['WeiboOp']['status'] = 2;
            }
            $this->WeiboOp->save(array('WeiboOp' => $this->data['WeiboOp']));
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_collect_information'].':id '.$id, $this->admin['id']);
            }
            $this->redirect('/weibo_rbs/op_index');
        }
    }
    public function op_remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $this->WeiboOp->deleteAll(array('WeiboOp.id' => $id));
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_collected_information'].':id '.$id, $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function op_batch_operations()
    {
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->WeiboOp->deleteAll(array('WeiboOp.id' => $v));
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }

    public function thm_remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $this->WeiboThm->deleteAll(array('WeiboThm.id' => $id));
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function thm_batch_operations()
    {
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->WeiboThm->deleteAll(array('WeiboThm.id' => $v));
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }
    //to get 10 page rows weibo
    //抓取 10 页的微博内容
    public function get_weibo_by_once()
    {
        $this->operator_privilege('weibo_ops_get_weibo');
        $p = $this->build_sina_oper_v2();
        for ($i = 1;$i < 10;++$i) {
            $ts = array();
            $timeline = $p->home_timeline($i);
            if (isset($timeline['error']) && !empty($timeline['error'])) {
                break;
            }

            //pr($timeline);

            if (empty($timeline)) {
                continue;
            }
            $ts = array();
            if (isset($timeline['statuses'])) {
                foreach ($timeline['statuses'] as $k => $v) {
                    $ts[$v['user']['id']]['text'] = $v['text'];
                    $ts[$v['user']['id']]['name'] = $v['user']['name'];
                    $ts[$v['user']['id']]['wid'] = $v['mid'];
                }
            }
            foreach ($ts as $k1 => $v1) {
                $tmp_arr = array(
                    'sid' => $k1,
                    'wid' => $v1['wid'],
                    'name' => $v1['name'],//ereg_replace("(// @.*|//@.*)",'',$xx['WeiboOp']['content']);
                    'content' => ereg_replace('(// @.*|//@.*)', '', $v1['text']),
                    'robort_id' => 0,
                    'status' => 1,
                    );
//			 	if(empty($tmp_arr['content']))
//			 		continue;
                $a = $this->WeiboOp->find('first', array('conditions' => array('WeiboOp.content' => $v1['text']), 'fields' => array('WeiboOp.id')));
                if (empty($a) && !empty($tmp_arr['content'])) {
                    $this->WeiboOp->save($tmp_arr);
                    $this->WeiboOp->id = false;
                }
            }
        }
        //die();
        $this->redirect('/weibo_rbs/op_index');
    }

    //get comment for weibo_content 
    //匹配微博内容，自动得到回复
    public function get_comment1()
    {
        $a = $this->WeiboRb->find('all', array('fields' => array('WeiboRb.key_words', 'WeiboRb.id', 'WeiboRb.comment')));
        $b = $this->WeiboOp->find('all', array('fields' => array('WeiboOp.content', 'WeiboOp.id')));
        foreach ($b as $k => $v) {
            foreach ($a as $rk => $rv) {
                //pr($v);
                if ($this->to_rule($v['WeiboOp']['content'], $rv['WeiboRb']['key_words']) == 0) {
                    //updateAll(array('User.balance' => 'User.balance + '.$_POST['balance']),array('User.id =' => "$id"));
                    echo 1;
                    $this->WeiboOp->updateAll(array('WeiboOp.status' => 2, 'WeiboOp.to_comment' => "'".$rv['WeiboRb']['comment']."'", 'WeiboOp.robort_id' => $rv['id']), array('WeiboOp.id' => $v['WeiboOp']['id']));
                }
            }
        }
        $this->redirect('/weibo_rbs/op_index');
    }

    //get comment for weibo_content 
    //匹配微博内容，自动得到回复 得到匹配了多少关键字
    public function get_comment2()
    {
        $this->operator_privilege('weibo_ops_keword');

        $now = time();
        $test1 = date('Y-m-d H:i:s', $now);
//		var_dump(date("Y-m-d H:i:s",$now));
        $test2 = '0000-00-00 00:00:00';
//		var_dump($test1>$test2);
        $condition = '';
        $condition['WeiboRb.status'] = 1;
        $condition['or']['and']['WeiboRb.start_time <'] = $test1;
        $condition['or']['and']['WeiboRb.end_time >'] = $test1;
        $condition['or']['WeiboRb.start_time'] = $test2;
        $condition['or']['WeiboRb.end_time'] = $test2;

        $a = $this->WeiboRb->find('all', array('conditions' => $condition, 'fields' => array('WeiboRb.key_words', 'WeiboRb.id', 'WeiboRb.comment', 'WeiboRb.is_fg')));
        $b = $this->WeiboOp->find('all', array('conditions' => array('WeiboOp.status' => 1), 'fields' => array('WeiboOp.content', 'WeiboOp.id', 'WeiboOp.sid')));
        $p = $this->build_sina_oper();
        //$fp=$this->get_fp($p);		
        $fp = $this->get_fp();
        foreach ($b as $k => $v) {
            foreach ($a as $rk => $rv) {
                $keys = $this->to_rule2($v['WeiboOp']['content'], $rv['WeiboRb']['key_words']);
                if ($keys != 0) {
                    if ($rv['WeiboRb']['is_fg'] == '1') {
                        echo 1;
                        $f_random = $this->random_frinds($fp);
                        $rvc = $this->content_as_fp($rv['WeiboRb']['comment'], $f_random);
                    } else {
                        echo 2;
                        $rvc = $rv['WeiboRb']['comment'];
                    }
                    $this->WeiboOp->updateAll(array('WeiboOp.status' => 2, 'WeiboOp.to_comment' => "'".$rvc."'", 'WeiboOp.marry_point' => "'".$keys."'", 'WeiboOp.robort_id' => $rv['WeiboRb']['id']), array('WeiboOp.id' => $v['WeiboOp']['id']));
                }
            }
        }    //die();
        $this->redirect('/weibo_rbs/op_index');
    }

    //to find a rule for weibo_content
    //根据微博内容和关键字匹配
    //$con 微博内容
    //$rule 关键字
    public function to_rule($con, $rule)
    {
        $rule = explode(',', $rule);
        $rs = 0;
        foreach ($rule as $k => $v) {
            $tmp = '|'.$v.'|is';
            if (!preg_match($tmp, $con)) {
                $rs = 1;
                break;
            }
        }

        return $rs;
    }

    //to find a rule for weibo_content(返回匹配字数)
    //根据微博内容和关键字匹配
    //$con 微博内容
    //$rule 关键字	 
    public function to_rule2($con, $rule)
    {
        //die();
        $rule = explode(',', $rule);
        $rs = count($rule);
        $i = 0;
        foreach ($rule as $k => $v) {
            $tmp = '|'.$v.'|is';
            if (preg_match($tmp, $con)) {
                ++$i;
            }
        }
        //echo $i;die();
        //if($i/$rs>=0)
        return $i.'/'.$rs;
    }
    //to get app sina per
    //创建微博对象
    public function build_sina_oper()
    {
        $ioco_apper = $this->get_sina_ioco();
        $app_key = $ioco_apper['app_key'];
        $app_secret = $ioco_apper['app_secret'];
        $access_token = $ioco_apper['access_token'];
        $p = new SaeTClientV2($app_key, $app_secret, $access_token);
        var_dump($p);

        return $p;
    }

    public function build_sina_oper_v2()
    {
        $ioco_apper = $this->get_sina_ioco();
        $app_key = $ioco_apper['app_key'];
        $app_secret = $ioco_apper['app_secret'];
        $access_token = $ioco_apper['access_token'];
        $p = new SaeTClientV2($app_key, $app_secret, $access_token);
        var_dump($p);

        return $p;
    }

    //to conmment a weibo to sina
    //$p 微博对象
    //$wid 微博id
    //$con 回复内容
    public function conmment_weibo($p, $wid, $con)
    {
        return $p->send_comment($wid, $con);
    }

    //to repost  a weibo to sina
    //$p 微博对象
    //$wid 微博id
    public function repost_weibo($p, $wid, $con)
    {
        usleep(500000);

        return $p->repost($wid, $con);
    }

    //to build a weibo_op report
    //创建授权对象链接
    public function sina_sender()
    {
        //die();
        $t2 = $this->get_sina_ioco();
        $_SESSION['app_key'] = $t2['app_key'];
        $_SESSION['app_secret'] = $t2['app_secret'];
        $o = new WeiboOAuth($t2['app_key'], $t2['app_secret']);
        $keys = $o->getRequestToken();
        if (!isset($keys['oauth_token'])) {
            $keys = $o->getRequestToken();
        }
        $aurl = $o->getAuthorizeURL($keys['oauth_token'], false, 'http://'.$_SERVER['HTTP_HOST'].'/admin/weibo_rbs/opscallback');
        $_SESSION['keys'] = $keys;
        $this->redirect($aurl);
    }

    public function sina_sender_v2()
    {
        //die();
        $t2 = $this->get_sina_ioco();
        $_SESSION['app_key'] = $t2['app_key'];
        $_SESSION['app_secret'] = $t2['app_secret'];
        $o = new SaeTOAuthV2($t2['app_key'], $t2['app_secret']);
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/admin/weibo_token_gets/response';
        $aurl = $o->getAuthorizeURL($url);

        //echo ";
        $_SESSION['keys'] = $keys;
        $this->redirect($aurl);
    }

    //to load a weibo_op
    //创建授权对象
    public function opscallback()
    {
        if (isset($_REQUEST['oauth_verifier'])) {
            //var_dump($_SESSION["app_key"]);
            $o = new WeiboOAuth($_SESSION['app_key'], $_SESSION['app_secret'], $_SESSION['keys']['oauth_token'], $_SESSION['keys']['oauth_token_secret']);
            //$o = new WeiboOAuth('11111','11111',$_SESSION['keys']['oauth_token'],$_SESSION['keys']['oauth_token_secret']);
            $last_key = $o->getAccessToken($_REQUEST['oauth_verifier']);

            if (empty($last_key['error_code'])) {
                //var_dump($last_key);
                $c = new WeiboClient($_SESSION['app_key'], $_SESSION['app_secret'], $last_key['oauth_token'], $last_key['oauth_token_secret']);
                //var_dump($c);
                $ms = $c->home_timeline(); // done
                $me = $c->verify_credentials();
                //var_dump($me);
                $ot_info = array(
                    'operator_id' => 0,
                    'email' => $me['name'],
                    'account' => $me['id'],
                    'oauth_token' => $last_key['oauth_token'],
                    'oauth_token_secret' => $last_key['oauth_token_secret'],
                    'type' => 'weibo_op',
                    );
                $ever_list = $this->SynchroOperator->find('first', array('conditions' => array('SynchroOperator.account' => $me['id'], 'SynchroOperator.type' => 'weibo_op')));
                if (empty($ever_list)) {
                    $this->SynchroOperator->deleteAll(array('SynchroOperator.type' => 'weibo_op'));
                    $this->SynchroOperator->save($ot_info);
                } else {
                    $this->SynchroOperator->updateAll(array('SynchroOperator.oauth_token' => $last_key['oauth_token'], 'SynchroOperator.oauth_token_secret'), array('SynchroOperator.account' => $me['id']));
                }

                $app_foo = $this->Application->find('first', array('conditions' => array('Application.code' => 'APP-WEIBO'), 'fields' => array('Application.id')), false);
                $this->redirect('/applications/view/'.$app_foo['Application']['id']);
            }
        }
        $msg = $this->ld['sina_interface_abnormal'];
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/pages/home"</script>';
        die();
    }

    //to load a weibo_op
    //创建授权对象
    public function opscallback_v2()
    {
        if (isset($_REQUEST['code'])) {
            //die();
            //var_dump($_SESSION["app_key"]);
            $o = new SaeTOAuthV2($_SESSION['app_key'], $_SESSION['app_secret']);

            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = 'http://'.$_SERVER['HTTP_HOST'].'/admin/weibo_rbs/opscallback_v2';
            try {
                $last_key = $o->getAccessToken('code', $keys);
            } catch (OAuthException2 $e) {
            }
            $c = new SaeTClientV2($_SESSION['app_key'], $_SESSION['app_secret'], $last_key['access_token']);
            $uid_get = $c->get_uid();

            if (isset($uid_get['uid'])) {
                //var_dump($last_key);
                //var_dump($me);
                $uid = $uid_get['uid'];
                $me = $c->show_user_by_id($uid);
                $ot_info = array(
                    'operator_id' => 0,
                    'email' => $me['name'],
                    'account' => $me['id'],
                    'oauth_token' => $last_key['access_token'],
                    'oauth_token_secret' => 'v2.0',
                    'type' => 'weibo_op',
                    );
                $ever_list = $this->SynchroOperator->find('first', array('conditions' => array('SynchroOperator.account' => $me['id'], 'SynchroOperator.type' => 'weibo_op')));
                if (empty($ever_list)) {
                    $this->SynchroOperator->deleteAll(array('SynchroOperator.type' => 'weibo_op'));
                    $this->SynchroOperator->save($ot_info);
                } else {
                    $this->SynchroOperator->updateAll(array('SynchroOperator.oauth_token' => $last_key['access_token']), array('SynchroOperator.account' => $me['id']));
                }

                $app_foo = $this->Application->find('first', array('conditions' => array('Application.code' => 'APP-WEIBO'), 'fields' => array('Application.id')), false);
                $this->redirect('/applications/view/'.$app_foo['Application']['id']);
            }
        }
        $msg = $this->ld['sina_interface_abnormal'];
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/pages/home"</script>';
        die();
    }

    //to get app from ioco_g
    //拿官网新浪应用app
    public function get_sina_ioco()
    {
        $shop_oper = $this->SynchroOperator->find('first', array('conditions' => array('SynchroOperator.status' => 1)));
        $result = $shop_oper['SynchroOperator'];
//		$soap_api = "http://".IOCOMGT."/soap/webservices/wsdl";
//		$client = new nusoap_client($soap_api, true);
//					
//		$client->soap_defencoding = 'utf-8';
//		$client->decode_utf8 = false;
//		$client->xml_encoding = 'utf-8';
//		$arr=array(
//			'strUserPass'=>'iocosina'
//			);
//		$result = $client->call('get_ioco_sina',$arr); 
        return $result;
    }

    public function check_weibo_op()
    {
        $suser = $this->SynchroOperator->find('first', array('conditions' => array('SynchroOperator.status' => 1), 'fields' => array('SynchroOperator.email')));
        if (empty($suser)) {
            $msg = '请授权您的sina账号';
            $aid = $this->Application->find('first', array('conditions' => array('Application.code' => 'APP-WEIBO'), 'fields' => array('Application.id')));
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/admin/applications/view/'.$aid['Application']['id'].'"</script>';
            die();
        } else {
            return $suser;
        }
    }

    //get friends group from sina 
    //in:$p a weibo object
    public function get_fp($p = '')
    {
        //	$foo = $p->friends_timeline();
        $shop_oper = $this->SynchroOperator->find('first', array('conditions' => array('SynchroOperator.status' => 1)));
        $app_key = $shop_oper['SynchroOperator']['app_key'];
        $app_secret = $shop_oper['SynchroOperator']['app_secret'];
        $access_token = $shop_oper['SynchroOperator']['access_token'];
        $SaeTOAuthV2 = new SaeTOAuthV2($app_key, $app_secret, $access_token);
        $url = 'statuses/friends_timeline';
        $usparm['count'] = 50;
        $usparm['page'] = 1;
        $foo = $SaeTOAuthV2->get($url, $usparm);
        if (empty($foo) || isset($foo['error_code'])) {
            return false;
        }
        $friends_gp = array();
        if (isset($foo['statuses'])) {
            foreach ($foo['statuses'] as $k => $v) {
                $friends_gp[] = $v['user']['name'];
            }
        }

        return    $friends_gp;
    }

    //get friends group from sina 
    //in:$p a weibo object
    //in:$sid a weibo id
    public function get_fp2($p, $sid)
    {
        $foo = $p->friends(false, false, $sid);
        if (empty($foo) || isset($foo['error_code'])) {
            return false;
        }
        $friends_gp = array();
        foreach ($foo as $k => $v) {
            $friends_gp[] = $v['name'];
        }

        return    $friends_gp;
    }

    //get random friends from sina 
    //in1:$friends_gp array
    //in2:$ct int default 3
    public function random_frinds($friends_gp, $ct = 3)
    {
        shuffle($friends_gp);

        return array_slice($friends_gp, 0, $ct);
    }

    public function content_as_fp($content, $fp)
    {
        foreach ($fp as $k => $v) {
            $content = $content.'@'.$v.' ';
        }

        return $content;
    }

    public function move_thm_log($result, $content)
    {
        if (empty($result) || isset($result['error_code'])) {
            $log_data = array(
                'content' => $content,
                'status' => 2,
            );
            $error = 'result is null';
            if (isset($result['error_code'])) {
                $error = 'request:'.$result['request'].';'.$result['error_code'].':'.$result['error'];
            }
            $log_data['error_msg'] = $error;
        } else {
            $log_data = array(
                'content' => $content,
                'status' => 1,
            );
            $log_data['error_msg'] = 'success!';
        }
        $this->WeiboLog->save($log_data);

        return $log_data['error_msg'];
    }

    //链接地址特殊字符格式化
    public function url_name_format($name)
    {
        $name = str_replace('\\', '-', $name);
        $name = str_replace(',', '-', $name);
        $name = str_replace('&', '-', $name);
        $name = str_replace('?', '-', $name);
        $name = str_replace('/', '-', $name);
        $name = str_replace(' ', '-', $name);

        return urlencode($name);
    }

    public function get_pro_link($name, $id)
    {
        $links = 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->url_name_format($name).'-P'.$id.'.html';

        return urlencode($links);
    }

    public function test()
    {
        $now = time();
        $test1 = date('Y-m-d H:i:s', $now);
//		var_dump(date("Y-m-d H:i:s",$now));
        $test2 = '0000-00-00 00:00:00';
//		var_dump($test1>$test2);
        $condition = '';
        $condition['WeiboRb.status'] = 1;
        $condition['or']['and']['WeiboRb.start_time <'] = $test1;
        $condition['or']['and']['WeiboRb.end_time >'] = $test1;
        $condition['or']['WeiboRb.start_time'] = $test2;
        $condition['or']['WeiboRb.end_time'] = $test2;

        $a = $this->WeiboRb->find('all', array('conditions' => $condition, 'fields' => array('WeiboRb.key_words', 'WeiboRb.id', 'WeiboRb.comment', 'WeiboRb.is_fg')));
        pr($a);

    //	pr($this->CategoryProduct18n->find('first',array('conditions'=>array('CategoryProduct18n.category_id'=>5,'CategoryProduct18n.locale'=>$this->locale),'fields'=>array('CategoryProduct18n.name')),false));
//		$this->WeiboOp->updateAll(array('WeiboOp.status'=>1,'WeiboOp.to_comment'=>"''",'WeiboOp.marry_point'=>"''",'WeiboOp.robort_id'=>"''"),array('WeiboOp.status'=>2));
//		pr($this->WeiboOpLog->find('all'));	
        //die();	
    }
}
