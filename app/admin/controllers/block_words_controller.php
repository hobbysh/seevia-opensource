<?php

/*****************************************************************************
 * Seevia 屏蔽关键字
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id: 
*****************************************************************************/
/**
 *这是一个名为 BlockWordsController 的控制器
 *屏蔽关键字管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class BlockWordsController extends AppController
{
    public $name = 'BlockWords';
    public $uses = array('BlockWord');
    public $components = array('Pagination','RequestHandler','Phpexcel','EcFlagWebservice');//,'EcFlagWebservice'
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('block_words_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/messages/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['message_search'],'url' => '/messages/');
        $this->navigations[] = array('name' => '屏蔽关键字','url' => '');

        $conditions = '';
        //关键字
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
            $conditions['and']['word like'] = '%'.$_REQUEST['keyword'].'%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        $cond['conditions'] = $conditions;

        //分页
        $total = $this->BlockWord->find('count', $cond);//获取总记录数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];//当前页
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'block_words','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Blog');
        $this->Pagination->init($conditions, $parameters, $options);

        $cond['limit'] = $rownum;
        $cond['page'] = $page;
        $cond['order'] = 'created desc';

        $wordsinfo = $this->BlockWord->find('all', $cond);
        $this->set('wordsinfo', $wordsinfo);

        //设置页面标题
        $title = $this->ld['shielding_keyword'];
        $this->set('title_for_layout', $title.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    /*
        添加、编辑关键字
    */
    public function view($id = 0)
    {
        /*判断权限*/
        if ($id == 0) {
            $this->operator_privilege('block_words_add');
        } else {
            $this->operator_privilege('block_words_edit');
        }
        $this->menu_path = array('root' => '/crm/','sub' => '/messages/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['message_search'],'url' => '/messages/');
        $this->navigations[] = array('name' => '屏蔽关键字','url' => '/block_words/');

        //保存数据库
        if ($this->RequestHandler->isPost()) {
            $this->BlockWord->save($this->data);

            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].' '.$this->ld['shielding_keyword'].':'.$this->data['BlockWord']['word'], $this->admin['id']);
            }

            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $wordinfo = $this->BlockWord->find('first', array('conditions' => array('id' => $id)));
        if (!empty($wordinfo)) {
            $title = $this->ld['edit'].' - '.$this->ld['keyword'];
            $this->set('wordinfo', $wordinfo);
        } else {
            $id = 0;
            $title = $this->ld['add'].' - '.$this->ld['keyword'];
        }

        $this->navigations[] = array('name' => '添加关键字','url' => '');//设置路径
        $this->set('id', $id);

        //设置页面标题
        $this->set('title_for_layout', $title.' - '.$this->configs['shop_name']);
    }

    /*
        删除关键字
    */
    public function remove($id)
    {
        $this->operator_privilege('block_words_delete');
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_route_failure'];
        if ($this->BlockWord->delete($id)) {
            $result['flag'] = 1;
            $result['message'] = $this->ld['delete_article_success'];

            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete'].' '.$this->ld['shielding_keyword'].':id-'.$id, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
        批量删除关键字
    */
    public function removeAll()
    {
        $this->operator_privilege('block_words_delete');
        $art_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $this->BlockWord->deleteAll(array('id' => $art_ids));

        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete'].' '.$this->ld['shielding_keyword'].':id-'.$art_ids, $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }
}
