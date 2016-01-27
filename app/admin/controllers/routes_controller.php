<?php

/*****************************************************************************
 * Seevia 网址控制器管理
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
class RoutesController extends AppController
{
    public $name = 'Routes';
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('Resource','Operator','Dictionary','Config','ConfigI18n','Template','Route','OperatorLog');

    public function index($page = 1)
    {
        $this->operator_privilege('routes_view');//定时器管理权限
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/routes/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['routes'],'url' => '/routes/');
        $condition = '';
        $brand_keywords = '';     //关键字
           //关键字
        if (isset($this->params['url']['route_keywords']) && $this->params['url']['route_keywords'] != '') {
            $brand_keywords = $this->params['url']['route_keywords'];
            $condition['and']['or']['Route.controller like'] = '%'.$brand_keywords.'%';
            $condition['and']['or']['Route.action like'] = '%'.$brand_keywords.'%';
            $condition['and']['or']['Route.url like'] = '%'.$brand_keywords.'%';
        }
        //$this->Route->set_locale($this->model_locale['route']);
           if (isset($_GET['page']) && $_GET['page'] != '') {
               $page = $_GET['page'];
           }
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'Route','action' => 'index','page' => $page,'limit' => $rownum);
        $total = $this->Route->find('count', array('conditions' => $condition));//统计全部网址控制器总数
        $fields[] = 'Route.controller';
        $fields[] = 'Route.action';
        $fields[] = 'Route.url';
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
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Route');
        $this->Pagination->init($condition, $parameters, $options);
        $routes = $this->Route->find('all', array('conditions' => $condition, 'order' => 'Route.id ASC', 'page' => $page, 'limit' => $rownum));
        $this->set('routes', $routes);
        $this->set('route_keywords', $brand_keywords);//关键字选中
        $this->set('title_for_layout', $this->ld['routes'].'-'.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    //视图
    public function view($id = 0)
    {
        $this->menu_path = array('root' => '/system/','sub' => '/routes/');
        if (empty($id)) {
            $this->operator_privilege('routes_add');
            $this->set('title_for_layout', $this->ld['add_route'].'- '.$this->ld['routes'].' - '.$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['routes'],'url' => '/routes/');
            $this->navigations[] = array('name' => $this->ld['add_route'],'url' => '');
            if ($this->RequestHandler->isPost()) {
                $this->data['Route']['controller'] = !empty($this->data['Route']['controller']) ? $this->data['Route']['controller'] : '';
                $this->data['Route']['url'] = !empty($this->data['Route']['url']) ? $this->data['Route']['url'] : '';
                $this->data['Route']['action'] = !empty($this->data['Route']['action']) ? $this->data['Route']['action'] : '';
                $this->data['Route']['model_id'] = !empty($this->data['Route']['model_id']) ? $this->data['Route']['model_id'] : '';
                $this->data['Route']['options'] = !empty($this->data['Route']['options']) ? $this->data['Route']['options'] : '';
                $this->data['Route']['status'] = !empty($this->data['Route']['status']) ? $this->data['Route']['status'] : '';
                //$this->data['Route']['key_words']=str_replace("，",",",$this->data['Route']['key_words']);
                $this->Route->saveAll(array('Route' => $this->data['Route']));
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_route'], $this->admin['id']);
                }
                $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
            }
        } else {
            $this->operator_privilege('routes_edit');
            $this->set('title_for_layout', $this->ld['edit_route'].'- '.$this->ld['routes'].' - '.$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['routes'],'url' => '/routes/');
            $this->navigations[] = array('name' => $this->ld['edit_route'],'url' => '');
            $route = $this->Route->find('first', array('conditions' => array('Route.id' => $id)));
            $this->set('route', $route);
            if ($this->RequestHandler->isPost()) {
                $this->Route->save(array('Route' => $this->data['Route']));
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_route'].':id '.$id, $this->admin['id']);
                }
                $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
            }
        }
    }

    /**
     *删除网址控制器.
     *
     *@param int $id 输入文章ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_route_failure'];
        $this->Route->hasMany = array();
        $this->Route->hasOne = array();
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 删除网站控制器:id '.$id, $this->admin['id']);
        }
        $this->Route->deleteAll(array('id' => $id));
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_route_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *网址控制器批量处理.
     */
    public function batch()
    {
        $art_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;

        if (sizeof($art_ids) > 0) {
            $condition['Route.id'] = $art_ids;
            $this->Route->deleteAll($condition);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        } else {
            $this->redirect('/routes/');
        }
    }

    /**
     *列表推荐修改.
     */
    public function toggle_on_status()
    {
        $this->Route->hasMany = array();
        $this->Route->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Route->save(array('id' => $id, 'status' => $val))) {
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

    //检验映射地址是否存在
    public function select_route_url()
    {
        $condition = '';
        $condition['Route.url'] = $_REQUEST['route_url'];
        $infos = $this->Route->find('all', array('conditions' => $condition, 'recursive' => -1));
        if (!empty($infos)) {
            $result['type'] = '1';
            $result['message'] = $this->ld['url_already_exist'];
        } else {
            $result['type'] = '0';
            $result['message'] = $this->ld['url_can_be_used'];
        }
        Configure::write('debug', 0);
        echo json_encode($result);
        die();
    }
}
