<?php

/*****************************************************************************
 * 材料管理
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id:
*****************************************************************************/
/**
 *这是一个名为MaterialsController的控制器
 *控制材料管理显示处理.
 *
 *@var
 *@var
 *@var
 *@var
 */
class MaterialsController extends AppController
{
    public $name = 'Materials';
    public $components = array('Pagination','RequestHandler','Phpexcel');
    public $helpers = array('Html','Javascript','Form','Pagination');
    public $uses = array('Material','MaterialI18n');

    public function index($page = 1)
    {
        $this->operator_privilege('material_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/product/','sub' => '/materials/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['material_manage'],'url' => '/materials/');

        $condition = '';
        $name_keywords = '';     //关键字
        $code_keywords = '';
        $material_keywords = '';
        $condition = array('MaterialI18n.locale' => $this->backend_locale);
        //关键字
        if (isset($this->params['url']['material_keywords']) && $this->params['url']['material_keywords'] != '') {
            $material_keywords = $this->params['url']['material_keywords'];
            $condition['and']['or']['Material.code like'] = '%'.$material_keywords.'%';
            //$condition["and"]["or"]["Material.quantity like"] = $material_keywords;
            $condition['and']['or']['MaterialI18n.name like'] = '%'.$material_keywords.'%';
        }

        $total = $this->Material->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'Material';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->Material->set_locale($this->backend_locale);
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'materials','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Material');
        $this->Pagination->init($condition, $parameters, $options);

	$result = $this->Material->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'Material.id'));
	$this->set('material_keywords', $material_keywords);//关键字选中
	$this->set('result', $result);
	$this->set('title_for_layout', $this->ld['material_manage'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('material_add');
        } else {
            $this->operator_privilege('material_edit');
        }
        $this->menu_path = array('root' => '/product/','sub' => '/materials/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['material_manage'],'url' => '/materials/');
        if ($this->RequestHandler->isPost()) {
            $this->data['Material']['orderby'] = !empty($this->data['Material']['orderby']) ? $this->data['Material']['orderby'] : 50;
            $this->data['Material']['quantity'] = !empty($this->data['Material']['quantity']) ? $this->data['Material']['quantity'] : 0;
            if (isset($this->data['Material']['id']) && $this->data['Material']['id'] != '') {
                $this->Material->save($this->data['Material']); //关联保存
                $id = $this->data['Material']['id'];
            } else {
                $this->Material->saveAll($this->data['Material']); //关联保存
                $id = $this->Material->getLastInsertId();
            }
            $this->MaterialI18n->deleteall(array('product_material_id' => $id)); //删除原有多语言
            foreach ($this->data['MaterialI18n'] as $v) {
                $materialI18n_info = array(
                    'locale' => $v['locale'],
                       'product_material_id' => $id,
                       'description' => $v['description'],
                      'name' => isset($v['name']) ? $v['name'] : '',
                );
                $this->MaterialI18n->saveAll(array('MaterialI18n' => $materialI18n_info));//更新多语言
            }
            $this->redirect('/materials/');
        }
        $this->data = $this->Material->localeformat($id);
        if (isset($this->data['MaterialI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$this->data['MaterialI18n'][$this->backend_locale]['name'],'url' => '');
            $this->set('title_for_layout', $this->ld['edit'].'-'.$this->data['MaterialI18n'][$this->backend_locale]['name'].' - '.$this->configs['shop_name']);
        } else {
            $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
            $this->set('title_for_layout', $this->ld['add']." - ".$this->ld['material_manage'].' - '.$this->configs['shop_name']);
        }
        $this->set('Mater', $this->data);
    }

    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $this->Material->deleteall(array('Material.id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_link'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function batch_operations()
    {
        $material_checkboxes = $_REQUEST['checkboxes'];
        $material_Ids = '';
        foreach ($material_checkboxes as $k => $v) {
            $material_Ids = $material_Ids.$v.',';
            $this->Material->deleteAll(array('Material.id' => $v), false);
            $this->MaterialI18n->deleteAll(array('MaterialI18n.product_material_id' => $v));
        }
        if ($material_Ids != '') {
            $material_Ids = substr($material_Ids, 0, strlen($material_Ids) - 1);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_link'].':'.$material_Ids, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
