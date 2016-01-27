<?php

/*****************************************************************************
 * Seevia 外部链接
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
/**
 *这是一个名为 LinksController 的控制器
 *后台友情链接控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class LinksController extends AppController
{
    public $name = 'Links';
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler');
    public $uses = array('Link','LinkI18n','Resource','OperatorLog');

    /**
     *显示友情链接列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('links_view');
        $this->menu_path = array('root' => '/cms/','sub' => '/links/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['links'],'url' => '');

        //$this->Link->set_locale($this->backend_locale);
        $condition = array('LinkI18n.locale' => $this->backend_locale);
        $total = $this->Link->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'links','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Link');
        $this->Pagination->init($condition, $parameters, $options);
        $data = $this->Link->find('all', array('page' => $page, 'limit' => $rownum, 'conditions' => $condition, 'order' => 'Link.orderby,Link.created,Link.id'));
        $this->set('links', $data);
        $this->set('title_for_layout', $this->ld['links'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    /**
     *友情链接 新增/编辑.
     *
     *@param int $id 输入友情链接ID
     */
    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('links_add');
        } else {
            $this->operator_privilege('links_edit');
        }
        $this->menu_path = array('root' => '/cms/','sub' => '/links/');
        $this->set('title_for_layout', $this->ld['add_edit_links_links'].' - '.$this->ld['links'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['link_list'],'url' => '/links/');
        if ($this->RequestHandler->isPost()) {
            $this->data['Link']['orderby'] = isset($this->data['Link']['orderby']) && $this->data['Link']['orderby'] != '' ? $this->data['Link']['orderby'] : 50;
            if (isset($this->data['Link']['id']) && $this->data['Link']['id'] != '') {
                $this->Link->save($this->data['Link']); //关联保存
                $id = $this->data['Link']['id'];
            } else {
                $this->Link->saveAll($this->data['Link']); //关联保存
                $id = $this->Link->getLastInsertId();
            }
            $this->LinkI18n->deleteall(array('link_id' => $id)); //删除原有多语言
            foreach ($this->data['LinkI18n'] as $v) {
                $linkI18n_info = array(
                      'locale' => $v['locale'],
                       'link_id' => $id,
                      'name' => isset($v['name']) ? $v['name'] : '',
                       'url' => $v['url'],
                      'description' => $v['description'],
                    'img01' => $v['img01'],
                );
                $this->LinkI18n->saveAll(array('LinkI18n' => $linkI18n_info));//更新多语言
            }
            foreach ($this->data['LinkI18n'] as $k => $v) {
                if ($v['locale'] == $this->backend_locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_edite_link'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/links/');
        }
        $link = $this->Link->localeformat($id);
        $this->set('link', $link);
        if (isset($link['LinkI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$link['LinkI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_link'],'url' => '');
        }

        $Resource_info = $this->Resource->getformatcode(array('link_type'), $this->backend_locale);
        $this->set('link_type', !empty($Resource_info['link_type']) ? $Resource_info['link_type'] : array());
    }

    /**
     *友情链接列表更新名称.
     */
    public function update_link_name()
    {
        $this->Link->hasMany = array();
        $this->Link->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->LinkI18n->updateAll(
            array('name' => "'".$val."'"),
            array('link_id' => $id, 'locale' => $this->backend_locale)
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
     *友情链接列表更新超链.
     */
    public function update_link_url()
    {
        $this->Link->hasMany = array();
        $this->Link->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->LinkI18n->updateAll(
            array('url' => "'".$val."'"),
            array('link_id' => $id, 'locale' => $this->backend_locale)
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
     *友情链接列表更新超链.
     */
    public function update_link_orderby()
    {
        $this->Link->hasMany = array();
        $this->Link->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->Link->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *友情链接列表推荐修改.
     */
    public function toggle_on_status()
    {
        $this->Link->hasMany = array();
        $this->Link->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Link->save(array('id' => $id, 'status' => $val))) {
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
     *删除一个友情链接.
     *
     *@param $id 输入友情链接ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $this->Link->deleteall(array('Link.id' => $id));
        $this->LinkI18n->deleteall(array('LinkI18n.link_id' => $id));
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

    /*
        批量删除
    */
    public function removeall()
    {
        $link_checkboxes = $_REQUEST['checkboxes'];
        $link_Ids = '';
        foreach ($link_checkboxes as $k => $v) {
            $link_Ids = $link_Ids.$v.',';
            $this->Link->deleteAll(array('Link.id' => $v), false);
            $this->LinkI18n->deleteAll(array('LinkI18n.link_id' => $v), false);
        }
        if ($link_Ids != '') {
            $link_Ids = substr($link_Ids, 0, strlen($link_Ids) - 1);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_link'].':'.$link_Ids, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }
}
