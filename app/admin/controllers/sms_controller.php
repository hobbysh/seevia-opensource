<?php

/*****************************************************************************
 * Seevia 短信日志
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
class SmsController extends AppController
{
    public $name = 'Sms';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination');
    public $uses = array('Sms','SmsSendHistory','OperatorLog');

    public function index($page = 1)
    {

        /*if(!in_array('APP-SMS',$this->apps['codes'])){
            $this->redirect('/applications/');
        }*/
        $this->operator_privilege('sms_view');
        $this->menu_path = array('root' => '/system/','sub' => '/log_managements/');
        $this->navigations[] = array('name' => $this->ld['sms_logs']);
        $this->navigations[] = array('name' => $this->ld['sms_list']);

        $condition = '';
        $phone = '';
        $flag = '';
        $send_date = '';
        $content = '';
        if (isset($this->params['url']['content']) && $this->params['url']['content'] != '') {
            $content = $this->params['url']['content'];
            $condition['content like'] = "%$content%";
            $this->set('content', $content);
        }
        if (isset($this->params['url']['phone']) && $this->params['url']['phone'] != '') {
            $phone = $this->params['url']['phone'];
            $condition['phone like'] = "%$phone%";
            $this->set('phone', $phone);
        }
        if (isset($this->params['url']['date']) && $this->params['url']['date'] != '') {
            $condition['and']['send_date >='] = $this->params['url']['date'].' 00:00:00';
            $this->set('date', $this->params['url']['date']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
            $condition['and']['send_date <='] = $this->params['url']['date2'].' 23:59:59';
            $this->set('date2', $this->params['url']['date2']);
        }
        //发送错误次数
        if (isset($this->params['url']['flag']) && $this->params['url']['flag'] != '') {
            $flag = $this->params['url']['flag'];
            $condition['and']['flag >='] = $flag;
            $this->set('flag', $flag);
        }
        if (isset($this->params['url']['page']) && $this->params['url']['page'] != '') {
            $page = $this->params['url']['page'];
        }
        $total = $this->Sms->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Sms');
        $this->Pagination->init($condition, $parameters, $options);
        $Sms_log_data = $this->Sms->find('all', array('page' => $page, 'limit' => $rownum, 'order' => 'id desc', 'conditions' => $condition));
        $this->set('Sms_log_data', $Sms_log_data);
        $this->set('title_for_layout', $this->ld['sms_list'].$this->ld['view'].$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    public function view($id)
    {
        /*if(!in_array('APP-SMS',$this->apps['codes'])){
            $this->redirect('/applications/');
        }*/
        $this->operator_privilege('sms_view');
        $this->menu_path = array('root' => '/system/','sub' => '/log_managements/');
        $this->navigations[] = array('name' => $this->ld['sms_logs']);
        $this->navigations[] = array('name' => $this->ld['sms_list'],'url' => '/Sms/');
        $this->navigations[] = array('name' => $this->ld['view']);
        $this->navigations[] = array('name' => $id);

        $data = $this->Sms->find('first', array('conditions' => array('id' => $id)));
        $this->set('data', $data);
        $this->set('title_for_layout', $this->ld['sms_list']);
    }
    public function batch()
    {
        /*if(!in_array('APP-SMS',$this->apps['codes'])){
            $this->redirect('/applications/');
        }*/
        $this->operator_privilege('sms_remove');
        //批量处理
        $result['flag'] = 2;
        $result['message'] = '删除失败';
        $user_checkboxes = $_REQUEST['checkboxes'];
        $this->Sms->deleteAll(array('id' => $user_checkboxes));
        $result['flag'] = 1;
        $result['message'] = '删除成功';
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function delete($id)
    {
        /*if(!in_array('APP-SMS',$this->apps['codes'])){
            $this->redirect('/applications/');
        }*/
        $this->operator_privilege('sms_remove');
        if ($this->Sms->delete(array('id' => $id))) {
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'短信删除：id '.$id, $this->admin['id']);
            }
            $this->redirect('/Sms/');
        }
    }
    public function histories($page = 1)
    {
        /*if(!in_array('APP-SMS',$this->apps['codes'])){
            $this->redirect('/applications/');
        }*/
        $this->operator_privilege('sms_histories_view');
        $this->menu_path = array('root' => '/system/','sub' => '/log_managements/');
        //$this->navigations[]=array('name'=>'短信日志');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['log_management'],'url' => '/log_managements/');
        $this->navigations[] = array('name' => $this->ld['sms_logs']);

        $condition = '';
        $phone = '';
        $flag = '';
        $send_date = '';
        $content = '';
        if (isset($this->params['url']['content']) && $this->params['url']['content'] != '') {
            $content = $this->params['url']['content'];
            $condition['content like'] = "%$content%";
            $this->set('content', $content);
        }
        if (isset($this->params['url']['phone']) && $this->params['url']['phone'] != '') {
            $phone = $this->params['url']['phone'];
            $condition['phone like'] = "%$phone%";
            $this->set('phone', $phone);
        }
        if (isset($this->params['url']['date']) && $this->params['url']['date'] != '') {
            $condition['and']['send_date >='] = $this->params['url']['date'].' 00:00:00';
            $this->set('date', $this->params['url']['date']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
            $condition['and']['send_date <='] = $this->params['url']['date2'].' 23:59:59';
            $this->set('date2', $this->params['url']['date2']);
        }
        //发送错误次数
        if (isset($this->params['url']['flag']) && $this->params['url']['flag'] != '') {
            $flag = $this->params['url']['flag'];
            $condition['and']['flag >='] = $flag;
            $this->set('flag', $flag);
        }
        if (isset($this->params['url']['page']) && $this->params['url']['page'] != '') {
            $page = $this->params['url']['page'];
        }
        $total = $this->SmsSendHistory->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Sms_histories');
        $this->Pagination->init($condition, $parameters, $options);
        $Sms_histories_data = $this->SmsSendHistory->find('all', array('page' => $page, 'limit' => $rownum, 'order' => 'id desc', 'conditions' => $condition));
        $this->set('Sms_histories_data', $Sms_histories_data);
        $this->set('title_for_layout', $this->ld['sms_logs'].$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    public function histories_view($id)
    {
        /*if(!in_array('APP-SMS',$this->apps['codes'])){
            $this->redirect('/applications/');
        }*/
        $this->operator_privilege('sms_histories_view');
        $this->navigations[] = array('name' => $this->ld['sms_logs']);
        $this->navigations[] = array('name' => $this->ld['sms_logs'].$this->ld['view'],'url' => '/Sms/histories');
        $this->navigations[] = array('name' => $this->ld['view']);
        $this->navigations[] = array('name' => $id);

        $data = $this->SmsSendHistory->find('first', array('conditions' => array('id' => $id)));
        $this->set('data', $data);
        $this->set('title_for_layout', $this->ld['sms_logs'].$this->ld['view']);
    }
    public function histories_batch()
    {
        /*if(!in_array('APP-SMS',$this->apps['codes'])){
            $this->redirect('/applications/');
        }*/
        $this->operator_privilege('sms_histories_remove');
        //批量处理
        $result['flag'] = 2;
        $result['message'] = '删除失败';
        $user_checkboxes = $_REQUEST['checkboxes'];
        $this->SmsSendHistory->deleteAll(array('id' => $user_checkboxes));
        $result['flag'] = 1;
        $result['message'] = '删除成功';
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function histories_delete($id)
    {
        /*if(!in_array('APP-SMS',$this->apps['codes'])){
            $this->redirect('/applications/');
        }*/
        $this->operator_privilege('sms_histories_remove');
        if ($this->SmsSendHistory->delete(array('id' => $id))) {
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'短信历史删除：id '.$id, $this->admin['id']);
            }
            $this->redirect('/Sms/histories');
        }
    }
    //清空短信日志
    public function clearall()
    {
        $this->operator_privilege('sms_histories_clear');
        $this->SmsSendHistory->query('TRUNCATE TABLE `svedi_sms_send_histories`');
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'清空短信日志', $this->admin['id']);
        }
        $this->redirect('histories');
    }
}
