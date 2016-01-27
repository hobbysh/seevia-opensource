<?php

/*****************************************************************************
 * Seevia 店铺管理管理
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
class StoresController extends AppController
{
    public $name = 'Stores';
    public $helpers = array('Html','Pagination','Tinymce','fck','Form','Javascript','Ckeditor');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('Store','StoreI18n','Resource','SeoKeyword','Operator','Shop','OperatorLog');

    public function index()
    {
        /*判断权限*/
        $this->operator_privilege('stores_view');
        /*end*/
        $this->menu_path = array('root' => '/oms/','sub' => '/allshops/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['shop_manage'],'url' => '/allshops/');
        $this->navigations[] = array('name' => $this->ld['entity_manage'],'url' => '/stores/');

        $this->Store->set_locale($this->locale);
        $condition = '';
        //$condition["status"]=1;
        $total = $this->Store->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'Store';
        $page = 1;
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);

        $parameters = array($rownum,$page);
        $options = array();
        $page = $this->Pagination->init($condition, $parameters, $options, $total, $rownum, $sortClass);
        $store_list = $this->Store->find('all', array('conditions' => $condition, 'rownum' => $rownum, 'page' => $page, 'order' => 'Store.orderby'));
        $this->set('store_list', $store_list);
        //资源库信息
        $this->Resource->set_locale($this->locale);
        $Resource_info = $this->Resource->getformatcode('store_type', $this->locale, false);
        $this->set('Resource_info', $Resource_info);
        $this->set('title_for_layout', $this->ld['shop_manage'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function remove($id)
    {
        /*判断权限*/
        //$this->operator_privilege('stores_view');
        /*end*/
        $pn = $this->StoreI18n->find('list', array('fields' => array('StoreI18n.store_id', 'StoreI18n.name'), 'conditions' => array('StoreI18n.store_id' => $id, 'StoreI18n.locale' => $this->locale)));
        $type = $this->Store->find('first', array('fields' => array('Store.store_type'), 'conditions' => array('Store.id' => $id)));

        $this->Shop->updateAll(array('Shop.status' => '2'), array('type' => $type['Store']['store_type'], 'type_id' => $id));
        $this->Store->updateAll(array('Store.status' => '2'), array('Store.id' => $id));
        $this->redirect('/stores');
    }
    public function edit($id)
    {
        /*判断权限*/
        $this->operator_privilege('stores_edit');
        /*end*/
        $this->menu_path = array('root' => '/oms/','sub' => '/allshops/');
        $this->set('title_for_layout', $this->ld['shop_edit_shop'].' - '.$this->ld['shop_manage'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['shop_manage'],'url' => '/allshops/');
        $this->navigations[] = array('name' => $this->ld['shop_edit_shop'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['Store']['orderby'] = !empty($this->data['Store']['orderby']) ? $this->data['Store']['orderby'] : 50;
            $operator = '';
            if (isset($_POST['operator']) && !empty($_POST['operator'])) {
                foreach ($_POST['operator'] as $k => $v) {
                    $operator .= $v.',';
                }
                $operator = trim($operator, ',');
            }
            $this->data['Store']['operator_id'] = $operator;
            $this->Store->save($this->data); //保存
            $type_id = $this->Store->id;

            $shop_data = $this->Shop->find('first', array('conditions' => array('Shop.type' => array('0', '1'), 'Shop.type_id' => $type_id)));
            $shop_info['id'] = isset($shop_data['Shop']['id']) ? $shop_data['Shop']['id'] : '';
            $shop_info['type'] = $this->data['Store']['store_type'];
            $shop_info['type_id'] = $type_id;
            $shop_info['shop_name'] = isset($this->data['StoreI18n'][0]['name']) ? $this->data['StoreI18n'][0]['name'] : '';
            $shop_info['shop_nick'] = isset($this->data['StoreI18n'][0]['name']) ? $this->data['StoreI18n'][0]['name'] : '';
            $shop_info['shop_url'] = isset($this->data['Store']['url']) ? $this->data['Store']['url'] : '';
            $shop_info['status'] = $this->data['Store']['status'];
            $this->Shop->save($shop_info);

            foreach ($this->data['StoreI18n'] as $v) {
                $storeI18n_info = array(
                                   'id' => isset($v['id']) ? $v['id'] : '',
                                   'locale' => $v['locale'],
                                   'store_id' => isset($v['store_id']) ? $v['store_id'] : $this->data['Store']['id'],
                                   'name' => isset($v['name']) ? $v['name'] : '',
                                   'meta_keywords' => isset($v['meta_keywords']) ? $v['meta_keywords'] : '',
                                   'description' => isset($v['description']) ? $v['description'] : '',
                                   'address' => isset($v['address']) ? $v['address'] : '',
                                   'map' => isset($v['map']) ? $v['map'] : '',
                                   'transport' => isset($v['transport']) ? $v['transport'] : '',
                                   'url' => isset($v['url']) ? $v['url'] : '',
                                   'img01' => isset($v['img01']) ? $v['img01'] : '',
                                   'zipcode' => isset($v['zipcode']) ? $v['zipcode'] : '',
                                   'meta_keywords' => isset($v['meta_keywords']) ? $v['meta_keywords'] : '',
                                   'meta_description' => isset($v['meta_description']) ? $v['meta_description'] : '',

                             );
                $this->StoreI18n->save(array('StoreI18n' => $storeI18n_info));//更新多语言
            }

            foreach ($this->data['StoreI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['shop_edit_shop'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/allshops');
        }
        $this->data = $this->Store->localeformat($id);
        if (empty($this->data['Store'])) {
            $this->redirect('/allshops');
        }
        $this->set('stores_info', $this->data);
        //leo20090722导航显示
        $this->data['StoreI18n'][$this->backend_locale]['name'] = empty($this->data['StoreI18n'][$this->backend_locale]['name']) ? '' : $this->data['StoreI18n'][$this->backend_locale]['name'];
        $this->navigations[] = array('name' => $this->data['StoreI18n'][$this->backend_locale]['name'],'url' => '');

        //资源库信息
        $this->Resource->set_locale($this->locale);
        $Resource_info = $this->Resource->getformatcode('store_type', $this->locale, false);
        $this->set('Resource_info', $Resource_info);
            //关键字
        $seokeyword_data = $this->SeoKeyword->find('all', array('conditions' => array('status' => 1)));
        $this->set('seokeyword_data', $seokeyword_data);

        //门店 操作员权限
        $operators = $this->Operator->find('all', array('conditions' => array('Operator.status' => 1)));
        $this->set('operators', $operators);
        $store_operators = array();
        if (!empty($this->data['Store'])) {
            $store_operators = explode(',', $this->data['Store']['operator_id']);
        }
        $this->set('store_operators', $store_operators);
    }

    public function add()
    {
        /*判断权限*/
        $this->operator_privilege('stores_add');
        $this->menu_path = array('root' => '/oms/','sub' => '/allshops/');
        /*end*/
        $this->set('title_for_layout', $this->ld['shop_edit_shop'].' - '.$this->ld['shop_manage'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['shop_manage'],'url' => '/allshops/');
        $this->navigations[] = array('name' => $this->ld['shop_edit_shop'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            if (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 0) {
                $this->data['Store']['orderby'] = 1;
                // 取出所有导航的 序值加1
                $all_store = $this->Store->find('all', array('fields' => 'Store.id,Store.orderby', 'order' => 'orderby asc', 'recursive' => '-1'));
                if (!empty($all_store)) {
                    foreach ($all_store as $k => $v) {
                        $all_store[$k]['Store']['orderby'] = $v['Store']['orderby'] + 1;
                    }
                    $this->Store->saveAll($all_store);
                }
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 1) {
                $store_last = $this->Store->find('first', array('recursive' => '-1', 'order' => 'orderby desc', 'limit' => '1'));
                $this->data['Store']['orderby'] = $store_last['Store']['orderby'] + 1;
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 2) {
                $store_change = $this->Store->find('first', array('conditions' => array('Store.id' => $_REQUEST['orderby_sel'])));
                $this->data['Store']['orderby'] = $store_change['Store']['orderby'] + 1;
                $all_store = $this->Store->find('all', array('conditions' => array('Store.orderby >' => $store_change['Store']['orderby']), 'recursive' => '-1'));
                if (!empty($all_store)) {
                    foreach ($all_store as $k => $v) {
                        $all_store[$k]['Store']['orderby'] = $v['Store']['orderby'] + 1;
                    }
                    $this->Store->saveAll($all_store);
                }
            }
            $operator = '';
            if (isset($_POST['operator']) && !empty($_POST['operator'])) {
                foreach ($_POST['operator'] as $k => $v) {
                    $operator .= $v.',';
                }
                $operator = trim($operator, ',');
            }
            $this->data['Store']['operator_id'] = $operator;
            $this->data['Store']['orderby'] = !empty($this->data['Store']['orderby']) ? $this->data['Store']['orderby'] : 50;
            $this->Store->save($this->data); //保存
            $id = $this->Store->id;
            $shop_data = $this->Shop->find('first', array('conditions' => array('Shop.type' => array('0', '1'), 'Shop.type_id' => $id)));

            $shop_info['id'] = isset($shop_data['Shop']['id']) ? $shop_data['Shop']['id'] : '';
            $shop_info['type'] = $this->data['Store']['store_type'];
            $shop_info['type_id'] = $id;
            $shop_info['shop_name'] = isset($this->data['StoreI18n'][0]['name']) ? $this->data['StoreI18n'][0]['name'] : '';
            $shop_info['shop_nick'] = isset($this->data['StoreI18n'][0]['name']) ? $this->data['StoreI18n'][0]['name'] : '';
            $shop_info['shop_url'] = isset($this->data['Store']['url']) ? $this->data['Store']['url'] : '';
            $shop_info['status'] = $this->data['Store']['status'];
            $this->Shop->save($shop_info);

            if (is_array($this->data['StoreI18n'])) {
                foreach ($this->data['StoreI18n'] as $k => $v) {
                    $v['store_id'] = $id;
                    $this->StoreI18n->id = '';
                    $this->StoreI18n->save($v);
                }
            }
            foreach ($this->data['StoreI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['shop_edit_shop'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/allshops');
        }
        //门店操作员权限
        $operators = $this->Operator->find('all', array('conditions' => array('Operator.status' => 1)));
        $this->set('operators', $operators);
        //资源库信息
        $this->Resource->set_locale($this->locale);
        $Resource_info = $this->Resource->getformatcode('store_type', $this->locale, false);
        $this->set('Resource_info', $Resource_info);
        //关键字
        $seokeyword_data = $this->SeoKeyword->find('all', array('conditions' => array('status' => 1)));
        $this->set('seokeyword_data', $seokeyword_data);
        //获取所有的店铺的对应关系
        $this->Store->set_locale($this->locale);
        $all_store = $this->Store->find('all');
        $this->set('all_store', $all_store);
    }
    public function act_view($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $store_sn = $_POST['store_sn'];
        $rname = '';
        $name_code = $this->Store->find('all', array('fields' => 'Store.store_sn'));
        if (isset($name_code) && !empty($name_code)) {
            foreach ($name_code as $vv) {
                $rname[] = $vv['Store']['store_sn'];
            }
        } else {
            $result['code'] = '1';
        }
        if ($id == 0) {
            if (isset($store_sn) && $store_sn != '') {
                if (in_array($store_sn, $rname)) {
                    $result['code'] = '0';
                } else {
                    $result['code'] = '1';
                }
            }
        } else {
            $Store_count = $this->Store->find('first', array('conditions' => array('Store.id' => $id)));
            if ($Store_count['Store']['store_sn'] != $store_sn && in_array($store_sn, $rname)) {
                $result['code'] = '0';
                //   $result['msg'] = "用户名重复";
            } else {
                $result['code'] = '1';
            }
        }
        die(json_encode($result));
    }
    public function toggle_on_status()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Store->save(array('id' => $id, 'status' => $val))) {
            $shop_data = $this->Shop->find('first', array('conditions' => array('Shop.type' => array('0', '1'), 'Shop.type_id' => $id)));
            if (!empty($shop_data)) {
                $shop_data['Shop']['status'] = $val;
                $this->Shop->save($shop_data['Shop']);
            }
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *批量处理.
     */
    public function batch()
    {
        $art_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        if (isset($this->params['url']['act_type']) && $this->params['url']['act_type'] != '0') {
            if ($this->params['url']['act_type'] == 'delete') {
                $condition['Store.id'] = $art_ids;
                $this->Store->deleteAll($condition);
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
                }
                $this->redirect('/stores/');
            }
            if ($this->params['url']['act_type'] == 'a_status') {
                $condition['Store.id'] = $art_ids;
                $this->Store->updateAll(array('Store.status' => $_REQUEST['is_yes_no']), array('Store.id' => $art_ids));
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
                }
                $this->redirect('/stores/');
            }
        } else {
            $this->redirect('/stores/');
        }
    }

    //列表箭头排序
    public function changeorder($updowm, $id)
    {
        $this->Store->hasOne = array();
        //如果值相等重新自动排序
        $joins = array(
            array('table' => 'stores',
                  'alias' => 'Store2',
                  'type' => 'inner',
                  'conditions' => 'Store.id <> Store2.id and Store.orderby=Store2.orderby',
                 ), );
        $a = $this->Store->find('all', array('joins' => $joins));

        $store_one = $this->Store->find('first', array('conditions' => array('Store.id' => $id)));
        if (!empty($a)) {
            $all = $this->Store->find('all', array('recursive' => -1));
            $i = 0;
            foreach ($all as $k => $vv) {
                $all[$k]['Store']['orderby'] = ++$i;
            }
            $this->Store->saveAll($all);
        }
        if ($updowm == 'up') {
            $store_change = $this->Store->find('first', array('conditions' => array('Store.orderby <' => $store_one['Store']['orderby']), 'order' => 'orderby desc', 'limit' => '1', 'recursive' => -1));
        }
        if ($updowm == 'down') {
            $store_change = $this->Store->find('first', array('conditions' => array('Store.orderby >' => $store_one['Store']['orderby']), 'order' => 'orderby asc', 'limit' => '1', 'recursive' => -1));
        }
        $t = $store_one['Store']['orderby'];
        $store_one['Store']['orderby'] = $store_change['Store']['orderby'];
        $store_change['Store']['orderby'] = $t;
        $this->Store->save($store_one);
        $this->Store->save($store_change);
        //资源库信息		
        $Resource_info = $this->Resource->getformatcode('store_type', $this->locale, false);
        $this->set('Resource_info', $Resource_info);

        $this->Store->hasOne = array('StoreI18n' => array('className' => 'StoreI18n',
                              'conditions' => '',
                              'order' => 'Store.id',
                              'dependent' => true,
                              'foreignKey' => 'store_id',
                        ),
                  );
        $this->Store->set_locale($this->locale);
        $store_list = $this->Store->find('all', array('order' => 'Store.orderby'));
        $this->set('store_list', $store_list);
        Configure::write('debug', 0);
        $this->render('index');
        $this->layout = 'ajax';
    }
}
