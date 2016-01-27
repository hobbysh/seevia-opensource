<?php

/**
 *这是一个名为 CategoryTitlesController 的控制器.
 *
 *@var
 *@var
 *@ 类目管理 chenfan 2012/02/17
 */
class ProductStylesController extends AppController
{
    public $name = 'ProductStyles';
    public $uses = array('CategoryType','CategoryTypeI18n','CategoryTypeRelation','OperatorLog','ProductStyle','ProductStyleI18n','StyleTypeGroup','ProductType','ProductTypeI18n');

    public function index()
    {
        $this->operator_privilege('product_style_view');
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->set('title_for_layout', $this->ld['style_manager'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['style_manager'],'url' => '');
        $this->ProductStyle->set_locale($this->backend_locale);
        $product_style = $this->ProductStyle->find('all', array('order' => 'ProductStyle.orderby asc'));
        $this->set('product_style', $product_style);
    }

    public function view($id = 0)
    {
        if ($id == 0) {
            $this->operator_privilege('product_style_add');
        } else {
            $this->operator_privilege('product_style_edit');
            //版型
            $product_style = $this->ProductStyle->localeformat($id);
            $this->set('product_style', $product_style);
            $product_type = $this->ProductType->product_type_tree($this->backend_locale);
            //版型规格
            $style_type_group = $this->StyleTypeGroup->find('all', array('conditions' => array('StyleTypeGroup.style_id' => $id), 'order' => 'StyleTypeGroup.type_id,StyleTypeGroup.orderby'));
            foreach ($style_type_group as $sk => $sv) {
                foreach ($product_type as $pk => $pv) {
                    if ($sv['StyleTypeGroup']['type_id'] == $pv['ProductType']['id']) {
                        $style_type_group[$sk]['StyleTypeGroup']['group_code'] = $pv['ProductTypeI18n']['name'];
                    }
                }
            }
            $this->set('style_type_group', $style_type_group);
        }
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->set('title_for_layout', $this->ld['style_manager'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['style_manager'],'url' => '/product_styles/');
        if ($this->RequestHandler->isPost()) {
            $this->ProductStyle->saveAll($this->data['ProductStyle']);
            $id = $this->ProductStyle->id;
            $this->ProductStyleI18n->deleteAll(array('ProductStyleI18n.style_id' => $this->ProductStyle->id));
            $ProductStyleName = '';
            foreach ($this->data['ProductStyleI18n'] as $v) {
                if ($v['locale'] == $this->backend_locale) {
                    $ProductStyleName = isset($v['style_name']) ? $v['style_name'] : '';
                }
                $ProductStyleI18n_info = array(
                      'locale' => $v['locale'],
                      'style_id' => $id,
                       'style_name' => isset($v['style_name']) ? $v['style_name'] : '',
                  );
                $this->ProductStyleI18n->saveAll(array('ProductStyleI18n' => $ProductStyleI18n_info));//更新多语言
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑版型:id '.$id.' '.$ProductStyleName, $this->admin['id']);
            }
            $this->redirect('/product_styles/');
        }
    }
    //列表箭头排序
    public function changeorder($updowm, $id, $nextone)
    {
        $this->ProductStyle->set_locale($this->backend_locale);
        //如果值相等重新自动排序
        $a = $this->ProductStyle->query('SELECT * 
			FROM `svoms_product_styles` as A inner join `svoms_product_styles` as B
			WHERE A.id<>B.id and A.orderby=B.orderby');

        $topic_one = $this->ProductStyle->find('first', array('conditions' => array('ProductStyle.id' => $id)));
        if (!empty($a)) {
            $all = $this->ProductStyle->find('all', array('recursive' => -1));
            $i = 0;
            foreach ($all as $k => $vv) {
                $all[$k]['ProductStyle']['orderby'] = ++$i;
            }
            $this->ProductStyle->saveAll($all);
        }
        if ($updowm == 'up') {
            $topic_change = $this->ProductStyle->find('first', array('conditions' => array('ProductStyle.orderby <' => $topic_one['ProductStyle']['orderby']), 'order' => 'ProductStyle.orderby desc', 'limit' => '1', 'recursive' => -1));
        }
        if ($updowm == 'down') {
            $topic_change = $this->ProductStyle->find('first', array('conditions' => array('ProductStyle.orderby >' => $topic_one['ProductStyle']['orderby']), 'order' => 'ProductStyle.orderby asc', 'limit' => '1', 'recursive' => -1));
        }
        $t = $topic_one['ProductStyle']['orderby'];
        $topic_one['ProductStyle']['orderby'] = $topic_change['ProductStyle']['orderby'];
        $topic_change['ProductStyle']['orderby'] = $t;
        $this->ProductStyle->save($topic_one);
        $this->ProductStyle->save($topic_change);
        $data = $this->ProductStyle->find('all', array('order' => 'ProductStyle.orderby'));
        $this->set('product_style', $data);
        Configure::write('debug', 1);
        $this->render('index');
        $this->layout = 'ajax';
    }

    //删除
    public function remove($id)
    {
        if (!$this->operator_privilege('product_style_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $this->ProductStyle->deleteAll(array('ProductStyle.id' => $id));
        $this->ProductStyleI18n->deleteAll(array('ProductStyleI18n.style_id' => $id));
        //删除版型下的规格
        $this->StyleTypeGroup->deleteAll(array('StyleTypeGroup.style_id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'版型删除:id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = '删除成功';
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //商品类目批量删除
    public function batch()
    {
        $this->operator_privilege('product_style_remove');
        $ct_ids = !empty($_REQUEST['checkbox']) ? $_REQUEST['checkbox'] : 0;
        if (sizeof($ct_ids) > 0) {
            $this->ProductStyle->deleteAll(array('ProductStyle.id' => $ct_ids));
            $this->ProductStyleI18n->deleteAll(array('ProductStyleI18n.style_id' => $ct_ids));
                //删除版型下的规格
                $this->StyleTypeGroup->deleteAll(array('StyleTypeGroup.style_id' => $ct_ids));

            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
            }
        }
        $this->redirect('/product_styles/');
    }
}
