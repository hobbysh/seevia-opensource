<?php

/**
 *这是一个名为 InformationResourcesController 的控制器
 *后台订单管理控制器.
 *
 *@var
 *@var
 *@var
 */
class InformationResourcesController extends AppController
{
    public $name = 'InformationResources';
    public $components = array('Pagination','RequestHandler');
    public $uses = array('InformationResource','InformationResourceI18n');
    public $helpers = array('Pagination');

    /**
     *查询资源表的数据.
     *
     *@param string code 
     */
    public function searchInforationresources()
    {
        $this->InformationResource->set_locale($this->locale);
        $info = $this->InformationResource->find('first', array('conditions' => array('InformationResource.code' => $_REQUEST['code']), 'fields' => 'InformationResource.id,InformationResource.code,InformationResourceI18n.name'));
        $this->set('name', $info['InformationResourceI18n']['name']);
        $this->set('parent_id', $info['InformationResource']['id']);
        //资源库信息
        $this->InformationResource->hasOne = array();
        $this->InformationResource->hasMany = array('InformationResourceI18n' => array('className' => 'InformationResourceI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'information_resource_id',
                        ),
                    );

        $informationresource_info = $this->InformationResource->all_information_formated($_REQUEST['code']);
        if (!empty($informationresource_info)) {
            $this->set('informationresource_info', $informationresource_info[$_REQUEST['code']]);
            $this->set('informationresource_id_info', $informationresource_info[$_REQUEST['code'].'_id_array']);
        }
        $this->set('code', $_REQUEST['code']);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
    }
    /**
     *resource主页列表.
     *
     *呈现数据库表resource的数据
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function index($page = 1)
    {
        /*判断权限*/
        //$this->operator_privilege('resources_view');
        /*end*/
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/web_application/','sub' => '/information_resources/');
        $this->set('title_for_layout', $this->ld['resource_manage'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => '信息资源管理','url' => '');
        $conditions = array();
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $conditions['and']['or']['InformationResourceI18n.name like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['InformationResource.code like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['InformationResource.information_value like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
        $this->InformationResource->set_locale($this->backend_locale);
        $cond = array();
        $cond['conditions'] = $conditions;
        $cond['order'] = 'InformationResource.created desc';
        $resource = $this->InformationResource->tree($cond);//取所有资源

        $total = sizeof($resource);
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'information_resources','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'InformationResource');
        $this->Pagination->init($cond, $parameters, $options);
        $start = ($page * $rownum) - $rownum;//当前页开始位置
        $resource = array_slice($resource, $start, $rownum);
        $this->set('resource', $resource);
    }

    /**
     *resource编辑.
     *
     *呈现数据库表resource的增加和更改
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function view($id = 0)
    {
        /*判断权限*/
        if ($id == 0) {
            //$this->operator_privilege('resources_add');
        } else {
            //$this->operator_privilege('resources_edit');
        }
        /*end*/
        $this->menu_path = array('root' => '/web_application/','sub' => '/information_resources/');
        $this->pageTitle = '编辑资源 - 信息资源管理'.' - '.$this->configs['shop_name'];
        $this->set('title_for_layout', $this->pageTitle);
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => '信息资源管理','url' => '/information_resources/');
        $this->set('navigations', $this->navigations);
        $userinformation_name = '';
        if ($this->RequestHandler->isPost()) {
            if ($id != 0) {
                $this->data['InformationResource']['orderby'] = !empty($this->data['InformationResource']['orderby']) ? $this->data['InformationResource']['orderby'] : 50;
                $this->InformationResource->save($this->data);
                foreach ($this->data['InformationResourceI18n'] as $v) {
                    $resourceI18n_info = array(
                                 'id' => isset($v['id']) ? $v['id'] : 'null',
                                   'locale' => $v['locale'],
                                   'information_resource_id' => $id ,
                                   'name' => isset($v['name']) ? $v['name'] : '',
                                'description' => isset($v['description']) ? $v['description'] : '',
                                'modified' => date('Y-m-d H:i:s'),
                 );
                    $this->InformationResourceI18n->saveAll(array('InformationResourceI18n' => $resourceI18n_info));//更新多语言
                }
                foreach ($this->data['InformationResourceI18n'] as $k => $v) {
                    if ($v['locale'] == $this->backend_locales) {
                        $userinformation_name = $v['name'];
                    }
                }
            /*
            //操作员日志
            if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
            $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'编辑系统资源:'.$userinformation_name ,'operation');
            }
            */
        //	$this->flash("资源 ".$userinformation_name." 编辑成功。点击这里继续编辑该菜单。",'/Resources/view/'.$id,10);
            $this->redirect('/information_resources/');
            } else {
                $this->data['InformationResource']['orderby'] = !empty($this->data['InformationResource']['orderby']) ? $this->data['InformationResource']['orderby'] : 50;
                $this->InformationResource->saveAll(array('InformationResource' => $this->data['InformationResource']));
                $id = $this->InformationResource->getLastInsertId();
                foreach ($this->data['InformationResourceI18n'] as $k => $v) {
                    $v['information_resource_id'] = $id;
                    $this->InformationResourceI18n->saveAll(array('InformationResourceI18n' => $v));
                }
                foreach ($this->data['InformationResourceI18n'] as $k => $v) {
                    if ($v['locale'] == $this->backend_locales) {
                        $userinformation_name = $v['name'];
                    }
                }
            /*
            //操作员日志
            if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
            $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'添加系统资源:'.$userinformation_name ,'operation');
            }
            */
        //	$this->flash("资源 ".$userinformation_name."  添加成功。点击这里继续编辑该资源。",'/resources/view/'.$id,10);
            $this->redirect('/information_resources/');
            }
        }
        $this->data = $this->InformationResource->localeformat($id);
        $this->InformationResource->set_locale($this->backend_locale);
        $parentmenu = $this->InformationResource->find('all', array('conditions' => array('InformationResource.parent_id' => '0')));
        $this->set('parentmenu', $parentmenu);
        if ($id != 0) {
            $this->navigations[] = array('name' => $this->data['InformationResourceI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => '添加资源','url' => '');
        }
        $this->set('navigations', $this->navigations);
    }

    /**
     *删除资源表的数据.
     *
     *@param int id 
     */
    public function remove($id = 0)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        /*判断权限*/
        if (!$this->operator_privilege('resources_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        /*end*/
        $system_info = $this->InformationResource->findById($id);
        $res = $this->InformationResource->find('count', array('conditions' => array('InformationResource.parent_id' => $id)));
        $result = array();
        if ($res > 0) {
            //$this->re('删除失败，该资源还有子资源','/Resources/','');
            //$this->redirect('/resources/');
            $result['flag'] = 2;
        } else {
            $this->InformationResourceI18n->deleteAll(array('InformationResourceI18n.information_resource_id' => $id));
            $this->InformationResource->delete(array('InformationResource.id' => $id));
            //操作员日志
  //  	    if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
 //   	    $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'删除系统资源:'.$system_info['SystemResourceI18n']['name'] ,'operation');
  //  	    }
            //$this->redirect('/resources/');
            $result['flag'] = 1;
        }
        die(json_encode($result));
    }

    /**
     *删除资源表的数据.
     *
     *@param int id 
     */
    public function removeInforationresources()
    {
        $informationresource_info = $this->InformationResourceI18n->find('first', array('conditions' => array('InformationResourceI18n.id' => $_REQUEST['id'])));
        if (!empty($informationresource_info)) {
            $parent_id = $informationresource_info['InformationResourceI18n']['information_resource_id'];
        }
        $this->InformationResource->deleteall(array('InformationResource.id' => $parent_id));
        $this->InformationResourceI18n->deleteall(array('InformationResourceI18n.information_resource_id' => $parent_id));
        $result['flag'] = 1;
        $result['msg'] = 'success';
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *编辑 新增 资源表的数据.
     *
     *@param int id 
     */
    public function editInforationresources()
    {
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
            $name = $_REQUEST['name'];
            $informationresource_info = $this->InformationResourceI18n->find('first', array('conditions' => array('InformationResourceI18n.id' => $_REQUEST['id'])));
            $informationresource_info['InformationResourceI18n']['name'] = $_REQUEST['name'];
            $this->InformationResourceI18n->save($informationresource_info);
        } else {
            $last_info = $this->InformationResource->find('first', array('conditions' => array('InformationResource.parent_id' => $_REQUEST['parent_id']), 'fields' => 'InformationResource.information_value', 'order' => 'InformationResource.information_value desc', 'limit' => 1));
            $informationresource_info = array();
            $informationresource_info['InformationResource']['parent_id'] = $_REQUEST['parent_id'];
            if (!empty($last_info)) {
                $informationresource_info['InformationResource']['information_value'] = $last_info['InformationResource']['information_value'] + 1;
            } else {
                $informationresource_info['InformationResource']['information_value'] = 1;
            }
            $this->InformationResource->save($informationresource_info);
            $id = $this->InformationResource->getLastInsertId();
            $resource_i18n_array = array();
            foreach ($this->front_locales as $k => $l) {
                if (isset($_REQUEST[$l['Language']['locale']]) && $_REQUEST[$l['Language']['locale']] != '') {
                    $resource_i18n_array[$k]['locale'] = $l['Language']['locale'];
                    $resource_i18n_array[$k]['name'] = $_REQUEST[$l['Language']['locale']];
                    $resource_i18n_array[$k]['information_resource_id'] = $id;
                }
            }
            if (!empty($resource_i18n_array)) {
                $this->InformationResourceI18n->saveAll($resource_i18n_array);
            }
        }
        $result['flag'] = 1;
        $result['msg'] = 'success';
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *获取最新的 资源表的数据.
     *
     *@param int id 
     */
    public function updateInformationresources()
    {
        $informationresource_info = $this->InformationResource->information_formated($_REQUEST['code'], $this->locale);
        if (!empty($informationresource_info) && !empty($informationresource_info[$_REQUEST['code']])) {
            $result['flag'] = 1;
            $result['data'] = $informationresource_info[$_REQUEST['code']];
        } else {
            $result['flag'] = 0;
            $result['msg'] = 'success';
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
