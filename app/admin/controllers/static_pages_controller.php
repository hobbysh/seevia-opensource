<?php

/**
 *这是一个名为 StaitcPagesController 的控制器
 *后台首页控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class StaticPagesController extends AppController
{
    public $name = 'StaticPages';
    public $components = array('RequestHandler','Pagination');
    public $helpers = array('Html','Javascript','Pagination','Ckeditor');
    public $uses = array('Operator','Config','Application','Page','PageI18n','Route');

    /**
     *显示后台首页.
     */
    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('static_page_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/cms/','sub' => '/static_pages/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['static_page_manage'],'url' => '');
        //pr($this->backend_locale);
        $this->Page->set_locale($this->backend_locale);
        $condition = '';
        if (isset($this->params['url']['title']) && $this->params['url']['title'] != '') {
            $condition['PageI18n.title LIKE'] = '%'.$this->params['url']['title'].'%';
            $this->set('titles', $this->params['url']['title']);
        }
        $sortClass = 'Page';
        $total = $this->Page->find('count', $condition);
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'pages','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Page');
        $this->Pagination->init($condition, $parameters, $options);
        $data = $this->Page->find('all', array('conditions' => $condition, 'order' => 'Page.orderby', 'limit' => $rownum, 'page' => $page));
        $page_ids = array();
        foreach ($data as $v) {
            array_push($page_ids, $v['Page']['id']);
        }
        $page_urls = $this->Route->find('all', array('conditions' => array('Route.model_id' => $page_ids, 'Route.controller' => 'static_pages', 'Route.action' => 'view', 'Route.url <>' => '/')));
        //pr($page_urls);
        foreach ($data as $dk => $dv) {
            foreach ($page_urls as $pk => $pv) {
                if ($pv['Route']['model_id'] == $dv['Page']['id']) {
                    $data[$dk]['Page']['url'] = $pv['Route']['url'];
                }
            }
        }
        //pr($data);
        $this->set('pages', $data);
        $this->set('title_for_layout', $this->ld['static_page_manage'].' - '.$this->configs['shop_name']);
    }

    public function view($id = 0)
    {
        $this->menu_path = array('root' => '/cms/','sub' => '/static_pages/');
        /*判断权限*/
        if (empty($id)) {
            $this->operator_privilege('static_page_add');
        } else {
            $this->operator_privilege('static_page_edit');
                //查找映射路径的内容
            $conditions = array('Route.controller' => 'static_pages','Route.action' => 'view','Route.model_id' => $id,'Route.url <>' => '/');
            $content = $this->Route->find('first', array('conditions' => $conditions));

            $this->set('routecontent', $content);
        }
        /*end*/
        if (!empty($this->data['Route']) && $id != 0) {
            //判断添加的内容是否为空
            $conditions = array('Route.controller' => 'static_pages','Route.action' => 'view','Route.model_id' => $id,'Route.url <>' => '/');
            $routeurl = $this->Route->find('first', array('conditions' => $conditions));
            $condit = array('Route.url' => $this->data['Route']['url']);//用来判断添加的url不能重复
            $rurl = $this->Route->find('first', array('conditions' => $condit));
            if (empty($rurl)) {
                //判断里面是否添加相同的数据
                if (empty($id)) {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        //foreach($this->data[])
                        $this->data['Route']['controller'] = 'static_pages';
                        $this->data['Route']['url'] = $this->data['Route']['url'];
                        $this->data['Route']['action'] = 'view';
                        $this->data['Route']['model_id'] = $id;
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                } else {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        if ($this->data['Route']['url'] != '') {
                            $this->data['Route']['controller'] = 'static_pages';
                            $this->data['Route']['url'] = $this->data['Route']['url'];
                            $this->data['Route']['action'] = 'view';
                            $this->data['Route']['model_id'] = $id;
                            $this->data['Route']['id'] = $routeurl['Route']['id'];
                            $this->Route->save(array('Route' => $this->data['Route']));
                        } else {
                            $this->Route->deleteAll(array('Route.id' => $routeurl['Route']['id']));
                        }
                    }
                }
            }
        }

        $this->set('title_for_layout', $this->ld['edit'].'-'.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['static_page_manage'],'url' => '/static_pages/');

        if ($this->RequestHandler->isPost()) {
            $this->data['Page']['status'] = isset($this->data['Page']['status']) ? $this->data['Page']['status'] : 0;
            if (isset($this->data['Page']['id']) && $this->data['Page']['id'] != '') {
                $this->Page->save(array('Page' => $this->data['Page'])); //关联保存
            } else {
                $this->Page->saveAll(array('Page' => $this->data['Page'])); //关联保存
                $id = $this->Page->getLastInsertId();
            }
            $this->PageI18n->deleteall(array('page_id' => $this->data['Page']['id'])); //删除原有多语言
            foreach ($this->data['PageI18n'] as $v) {
                $pageI18n_info = array(
                    'id' => isset($v['id']) ? $v['id'] : $this->data['Page']['id'],
                    'locale' => $v['locale'],
                    'page_id' => isset($v['page_id']) ? $v['page_id'] : $id,
                    'title' => isset($v['title']) ? $v['title'] : '',
                    'subtitle' => isset($v['subtitle']) ? $v['subtitle'] : '',
                    'content' => isset($v['intro']) ? $v['intro'] : '',
                    'img01' => isset($v['img01']) ? $v['img01'] : '',
                    'img02' => isset($v['img02']) ? $v['img02'] : '',
                    'meta_keywords' => isset($v['meta_keywords']) ? $v['meta_keywords'] : '',
                    'meta_description' => isset($v['meta_description']) ? $v['meta_description'] : '',
                );
                $this->PageI18n->saveall(array('PageI18n' => $pageI18n_info)); //更新多语言
            }
            foreach ($this->data['PageI18n']as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['title'];
                }
            }
            $id = $this->Page->id;
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $url = '/static_pages/'.$id;
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data = $this->Page->localeformat($id);
        $wh['page_id'] = !empty($this->data['Page']['id']) ? $this->data['Page']['id'] : '';
        
        if (!empty($this->data['PageI18n'][$this->backend_locale]['title'])) {
            $this->navigations[] = array('name' => $this->data['PageI18n'][$this->backend_locale]['title'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        }
    }

    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_article_failure'];
        if (!$this->operator_privilege('static_page_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $page_info = $this->Page->findById($id);
        $this->Page->deleteAll("Page.id = '".$id."'", false);
        $this->Page->deleteAll("PageI18n.page_id = '".$id."'", false); //删除原有多语言
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete_article_failure'].':id '.$id.' '.$page_info['PageI18n']['title'], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_article_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *静态页面列表修改有效.
     */
    public function toggle_on_status()
    {
        $this->Page->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Page->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], 'operation');
            }
        }

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表页面标题修改.
     */
    public function update_page_title()
    {
        $this->Page->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->PageI18n->updateAll(
            array('title' => "'".$val."'"),
            array('page_id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *文章列表排序修改.
     */
    public function update_page_orderby()
    {
        $this->Page->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->Page->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //批量处理
    public function batch()
    {
        $page_ids = !empty($_REQUEST['checkbox']) ? $_REQUEST['checkbox'] : 0;
        if (isset($this->params['url']['act_type']) && $this->params['url']['act_type'] != '0') {
            if ($this->params['url']['act_type'] == 'delete') {
                $this->PageI18n->deleteAll(array('page_id' => $page_ids));
                $this->Page->deleteAll(array('Page.id' => $page_ids));
            }
            if ($this->params['url']['act_type'] == 'a_status') {
                $condition['Page.id'] = $page_ids;
                $this->Page->updateAll(array('Page.status' => $_REQUEST['is_yes_no']), array('Page.id' => $page_ids));
                if ($this->configs['operactions-log'] == 1) {
                    $this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], 'operation');
                }
            }
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }
}
