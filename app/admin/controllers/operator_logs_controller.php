<?php

/*****************************************************************************
 * Seevia 操作日志
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
class OperatorLogsController extends AppController
{
    public $name = 'OperatorLogs';
    public $components = array('Pagination','RequestHandler','Email');
    public $helpers = array('Pagination','Html','Form','Javascript','Tinymce');

    public $uses = array('Config','OperatorLog','Operator','ConfigI18n');

    public function index()
    {
        /*判断权限*/
        $this->operator_privilege('operator_logs_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['log_operation'],'url' => '/OperatorLogs/');

        $condition = '';
        if (isset($this->params['url']['date1']) && $this->params['url']['date1'] != '') {
            $condition['and']['OperatorLog.created >='] = $this->params['url']['date1'].' 00:00:00';
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
            $condition['and']['OperatorLog.created <='] = $this->params['url']['date2'].' 23:59:59';
        }
        if (isset($this->params['url']['operator_id']) && $this->params['url']['operator_id'] != '') {
            $condition['and']['OperatorLog.operator_id'] = $this->params['url']['operator_id'];
        }
        if (isset($this->params['url']['keywords']) && $this->params['url']['keywords'] != '') {
            $condition['and']['or']['OperatorLog.info like'] = '%'.$this->params['url']['keywords'].'%';
            $condition['and']['or']['OperatorLog.action_url like'] = '%'.$this->params['url']['keywords'].'%';
            $condition['and']['or']['OperatorLog.ipaddress like'] = '%'.$this->params['url']['keywords'].'%';
        }
        $total = $this->OperatorLog->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $page = 1;

        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'OperatorLogs','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OperatorLog');
        $this->Pagination->init($condition, $parameters, $options);
        $OperatorLog_info = $this->OperatorLog->find('all', array('conditions' => $condition, 'order' => 'OperatorLog.id desc', 'page' => $page, 'limit' => $rownum));
        $operator_list = $this->Operator->find('all');
        $date1 = isset($this->params['url']['date1']) ? $this->params['url']['date1'] : '';
        $date2 = isset($this->params['url']['date2']) ? $this->params['url']['date2'] : '';
        $operator_id = isset($this->params['url']['operator_id']) ? $this->params['url']['operator_id'] : '';
        $keywords = isset($this->params['url']['keywords']) ? $this->params['url']['keywords'] : '';
        $this->set('keywords', $keywords);
        $this->set('operator_id', $operator_id);
        $this->set('date1', $date1);
        $this->set('date2', $date2);
        $this->set('OperatorLog_info', $OperatorLog_info);
        $this->set('operator_list', $operator_list);
        $this->set('title_for_layout', $this->ld['log_operation'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    public function remove($id)
    {

        /*判断权限*/
        $this->operator_privilege('operator_logs_view');
        /*end*/
        $this->OperatorLog->deleteAll("OperatorLog.id='$id'");
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_operator_log'].':id '.$id, $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }
    public function batch()
    {
        //批量处理
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->OperatorLog->deleteAll(array('id' => $v));
        }
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }
    public function index1()
    {
        $this->pageTitle = $this->ld['log_operation'].' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['log_operation'],'url' => '/OperatorLogs/');

        $path = dirname(dirname(__FILE__)).'\tmp\logs\operation.log';
        $log_array = array();
        $log_array1 = array();
        $file_handle = fopen($path, 'a+');
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            $log_array[] = $line;
        }
        fclose($file_handle);
        foreach ($log_array as $k => $v) {
            $line1 = explode('Operation:', $v);
            $line2 = explode(' ', trim(@$line1[1]));

            $line3[0] = $line1[0];
            $line3[1] = substr($line2[0], 9);
            $line3[2] = @$line2[1];

            foreach ($line3 as $kk => $vv) {
                $log_array1[$k][$kk] = $vv;
            }
        }
        $this->set('log_array', $log_array1);
    }

    //清空日志
    public function clearall()
    {
        $this->operator_privilege('operator_logs_view');
        $this->OperatorLog->query('TRUNCATE TABLE `svsys_operator_logs`');
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 清空'.$this->ld['log_operation'], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }
}
