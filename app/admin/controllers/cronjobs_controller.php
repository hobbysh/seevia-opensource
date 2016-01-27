<?php

/*****************************************************************************
 * Seevia 定时器管理
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
   *这是一个名为 CronjobsController 的控制器
   *后台定时器设置控制器.
   *
   *@var
   *@var
   *@var
   *@var
   */
  App::import('Vendor', 'nusoap');
App::import('Controller', 'Commons');//加载公共控制器
class CronjobsController extends AppController
{
    //var $name = 'Cronjobs';
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('Resource','Config','ConfigI18n','Dictionary','Config','ConfigI18n','Cronjob','OperatorLog');

    /**
     *显示商店的各个内容.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('cronjobs_view');//定时器管理权限
        $this->menu_path = array('root' => '/web_application/','sub' => '/cronjobs/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_cronjob'],'url' => '/cronjobs/');

        //$fields=array("Cronjob.id","Cronjob.task_name","Cronjob.last_time","Cronjob.next_time","Cronjob.status","Cronjob.interval_time","Cronjob.app_code","Cronjob.param01","Cronjob.param02","Cronjob.remark");
        $appcode_tree = $this->getappcodeformat();
        //初始化参数
        $condition = '';
        $cronjob_keywords = '';
        $cronjob_app = '';
        //应用下拉
        //$cronjob_app=isset($this->params['url']['cronjob_app']) ? $this->params['url']['cronjob_app']:'';
         if (isset($this->params['url']['cronjob_app']) && $this->params['url']['cronjob_app'] != '') {
             $condition['and']['Cronjob.app_code'] = $this->params['url']['cronjob_app'];
             $cronjob_app = $this->params['url']['cronjob_app'];
         }
        //关键字

        if (isset($this->params['url']['cronjob_keywords']) && $this->params['url']['cronjob_keywords'] != '') {
            $cronjob_keywords = $this->params['url']['cronjob_keywords'];
            $condition['and']['Cronjob.task_name like'] = "%$cronjob_keywords%";
            //$condition['or']['Cronjob.app_code like'] = "%$cronjob_keywords%";
        }

        //pr($condition);die;
        $cronjob_id = $this->Cronjob->find('all', array('fields' => array('Cronjob.id'), 'conditions' => $condition));
        $cj_id = array();//定时器id数组
            foreach ($cronjob_id as $v) {
                array_splice($cj_id, count($cj_id), 0, $v['Cronjob']['id']);
            }
        $condition['and']['Cronjob.id'] = $cj_id;

        //pr($app_name);die;

        //pr($appcode_tree);die;
        //分页
        $total = $this->Cronjob->find('count', array('conditions' => $condition));//统计全部的定时器总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取定时器的默认显示定时器个数数据	
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'Cronjob','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Cronjob');
        $this->Pagination->init($condition, $parameters, $options);

        $cronjobs = $this->Cronjob->find('all', array('conditions' => $condition, 'order' => 'Cronjob.id ASC', 'page' => $page, 'limit' => $rownum));
        $this->set('cronjob_app', $cronjob_app);
        $this->set('cronjob_keywords', $cronjob_keywords);
        $this->set('appcode_tree', $appcode_tree);
        $this->set('cronjobs', $cronjobs);
        $this->set('title_for_layout', $this->ld['manage_cronjob'].'-'.$this->configs['shop_name']);
        //pr($_SERVER['HTTP_HOST']);die;
        $this->set('shop_name', $_SERVER['HTTP_HOST']);
    }

    /**
     *定时器编辑/新增.
     *
     *@param int $id 输入定时器ID，新增时不传
     */
    public function view($id = 0)
    {
        //pr($this->configs);
        $this->menu_path = array('root' => '/web_application/','sub' => '/cronjobs/');
        //应用下拉
        $cronjob_app = isset($this->params['url']['cronjob_app']) ? $this->params['url']['cronjob_app'] : '';
        if (isset($this->params['url']['cronjob_app']) && $this->params['url']['cronjob_app'] != 0) {
            $condition['and']['Cronjob.app_code ='] = $this->params['url']['cronjob_app'];
        }
        $appcode_tree = $this->getappcodeformat();
        $this->set('appcode_tree', $appcode_tree);
        $this->set('cronjob_app', $cronjob_app);
        if (empty($id)) {
            $this->operator_privilege('cronjob_add');
            $this->set('title_for_layout', $this->ld['add_cronjob'].'- '.$this->ld['manage_cronjob'].' - '.$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['manage_cronjob'],'url' => '/cronjobs/');
            $this->navigations[] = array('name' => $this->ld['add_cronjob'],'url' => '');
            if ($this->RequestHandler->isPost()) {
                $this->data['Cronjob']['task_name'] = !empty($this->data['Cronjob']['task_name']) ? $this->data['Cronjob']['task_name'] : '';//任务名称
            $this->data['Cronjob']['interval_time'] = !empty($this->data['Cronjob']['interval_time']) ? $this->data['Cronjob']['interval_time'] : '0';//间隔时间
            $this->data['Cronjob']['app_code'] = !empty($this->data['Cronjob']['app_code']) ? $this->data['Cronjob']['app_code'] : '';//应用名称
            $this->data['Cronjob']['param01'] = !empty($this->data['Cronjob']['param01']) ? $this->data['Cronjob']['param01'] : '';//参数1名称
            $this->data['Cronjob']['param02'] = !empty($this->data['Cronjob']['param02']) ? $this->data['Cronjob']['param02'] : '';//参数2名称
            $this->data['Cronjob']['remark'] = !empty($this->data['Cronjob']['remark']) ? $this->data['Cronjob']['remark'] : '';//说明
            //checkbox数据处理
            $this->data['Cronjob']['status'] = !empty($this->data['Cronjob']['status']) ? $this->data['Cronjob']['status'] : '0';//任务状态*/
            $this->Cronjob->save(array('Cronjob' => $this->data['Cronjob']));
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_cronjob'], $this->admin['id']);
            }
                $this->redirect('/cronjobs/');
            }
        } else {
            $this->operator_privilege('cronjob_edit');
            $this->set('title_for_layout', $this->ld['edit_cronjob'].'-'.$this->ld['manage_cronjob'].' - '.$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['manage_cronjob'],'url' => '/cronjobs/');
            $cronjob_info = $this->Cronjob->find('first', array('conditions' => array('Cronjob.id' => $id)));
            $this->set('cronjob_info', $cronjob_info);
            if ($this->RequestHandler->isPost()) {
                //pr($this->data["Cronjob"]);die;
                $this->Cronjob->save(array('Cronjob' => $this->data['Cronjob']));
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_cronjob_content'].':id '.$id, $this->admin['id']);
            }
            //	$this->redirect('/weibo_rbs/');
               $this->redirect('/cronjobs/');
            }
        }
    }
    /**
     *列表状态修改.
     */
    public function toggle_on_status()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        //定时器编辑权限
        if (!$this->operator_privilege('cronjob_edit', false)) {
            die(json_encode(array('flag' => 1, 'content' => $this->ld['have_no_operation_perform'])));
        }
        $this->Cronjob->hasMany = array();
        $this->Cronjob->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        if ($val == 1) {
            $cronjob_info = array('id' => $id,
                'status' => $val,
                'online_time' => date('Y-m-d H:i:s'),
                'operator_id' => $this->admin['id'], );
        } elseif ($val == 0) {
            $cronjob_info = array('id' => $id,
                'status' => $val,
                'operator_id' => $this->admin['id'], );
        }

        $result = array();
        if (is_numeric($val) && $this->Cronjob->save(array('id' => $id, 'status' => $val))) {
            //$this->Product->save(array("id"=>$id,"forsale"=>$val,"operator_id" => $this->admin['id']))
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if (isset($this->configs['operactions-log']) &&  $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'定时器id'.'.'.$id.' '.'状态'.'.'.$val, $this->admin['id']);
            }
            //$this->product_log_save($id);
        }
        die(json_encode($result));
    }

    //删除
    public function remove($id)
    {
        //pr($id);die;
        // $this->operator_privilege('cronjob_remove');
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_route_failure'];
        $this->Cronjob->hasMany = array();
        $this->Cronjob->hasOne = array();
        $this->Cronjob->deleteAll(array('Cronjob.id' => $id));
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_article_success'];
        if (isset($this->configs['operactions-log']) &&  $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除定时器id：'.$id, $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //单独启用定时器
    public function execute()
    {
        $shopname = $_GET['shopname'];
        $taskname = $_GET['taskname'];
        //php /saas/src/dev/core/cake/console/db_cake.php user_cronjob -app /saas/src/dev/core/user -db /saas/src/dev/htdocs/vhosts/c60216d.ioco.dev/data/database.php -task asd
        $command = 'php /saas/src/dev/core/cake/console/db_cake.php user_cronjob -app /saas/src/dev/core/user -db /saas/src/dev/htdocs/vhosts/'.$shopname.'/data/database.php -task '.$taskname;
        //pr($command);die;
        $reoutput = array();
        $return_var = 1;
        $re = exec($command, $reoutput, $return_var);
        $this->set('reoutput', $reoutput);
        /*if($re = exec($command,$reoutput,$return_var)){
             //$reoutput=nl2br($output);
             
         }else{
             $this->set("reoutput",$reoutput);
         }*/
        $this->set('title_for_layout', $this->ld['manage_cronjob'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_cronjob'],'url' => '/cronjobs/');
    }
    public function getappcodeformat()
    {
        $fields = array('Application.id','Application.code');
        $lists = $this->Application->find('all', array('fields' => $fields));

        return $lists;
    }
}
