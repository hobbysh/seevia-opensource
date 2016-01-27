<?php

/*****************************************************************************
 * Seevia 渠道管理
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
class ChannelsController extends AppController
{
    public $name = 'Channels';
    public $components = array('Pagination','RequestHandler','Email');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('Channel','ChannelActive','OperatorLog');
    public $state = array('0' => '需求中','1' => '进行中','2' => '完结');
    public function index($page = 1)
    {
        $this->operator_privilege('channels_view');
        /*判断权限*/
        $this->navigations[] = array('name' => '渠道管理','url' => '');
        $this->navigations[] = array('name' => '活动渠道','url' => '/channels/');

        $condition = '';
           //渠道名称
        $name = '';
        if (isset($this->params['url']['name']) && $this->params['url']['name'] != '') {
            $name = $this->params['url']['name'];
            $condition['name like'] = "%$name%";
            $this->set('name', $name);
        }
        //联系人
        $contact_name = '';
        if (isset($this->params['url']['contact_name']) && $this->params['url']['contact_name'] != '') {
            $contact_name = $this->params['url']['contact_name'];
            $condition['Channel.contact_name like'] = "%$contact_name%";
            $this->set('contact_name', $contact_name);
        }
        //Email
        $contact_email = '';
        if (isset($this->params['url']['contact_email']) && $this->params['url']['contact_email'] != '') {
            $keywords = $this->params['url']['contact_email'];

            $condition['contact_email like'] = $keywords;
            $this->set('contact_email', $keywords);
        }
        //联系电话
        $contact_tele = '';
        if (isset($this->params['url']['contact_tele']) && $this->params['url']['contact_tele'] != '') {
            $keywords = $this->params['url']['contact_tele'];
            $condition['contact_tele like'] = $keywords;
            $this->set('contact_tele', $keywords);
        }
        //联系人手机
        $contact_mobile = '';
        if (isset($this->params['url']['contact_mobile']) && $this->params['url']['contact_mobile'] != '') {
            $keywords = $this->params['url']['contact_mobile'];
            $condition['contact_mobile like'] = $keywords;
            $this->set('contact_mobile', $keywords);
        }
        //联系地址
        $contact_address = '';
        if (isset($this->params['url']['contact_address']) && $this->params['url']['contact_address'] != '') {
            $keywords = $this->params['url']['contact_address'];
            $condition['contact_address like'] = "%$keywords%";
            $this->set('contact_address', $keywords);
        }

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

        $total = $this->Channel->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'channels','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Channel');
        $this->Pagination->init($condition, $parameters, $options);
        $chanels_list = $this->Channel->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'id desc'));
        $this->set('chanels_list', $chanels_list);
        $this->set('title_for_layout', ' 渠道管理'.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function view($id = 0)
    {

        /*判断权限*/
        if (!empty($id)) {
            $this->operator_privilege('channels_edit');
        } else {
            $this->operator_privilege('channels_add');
        }

        $this->pageTitle = '添加/编辑渠道商 - 渠道商'.' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => '渠道管理','url' => '');
        $this->navigations[] = array('name' => '渠道商管理','url' => '/channels/');
        $this->navigations[] = array('name' => '添加/编辑渠道商','url' => '/channels/view/');

        $this->set('title_for_layout', '添加/编辑渠道商'.' - '.$this->configs['shop_name']);
        if ($this->RequestHandler->isPost()) {
            if (!empty($this->data['Channel']['name'])) {
                //($this->data['Channel']['name']);
                $this->data['Channel']['orderby'] = !empty($this->data['Channel']['orderby']) ? $this->data['Channel']['orderby'] : 50;
                $this->Channel->saveall($this->data); //保存
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑渠道商:id '.$id.' '.$this->data['Channel']['name'], $this->admin['id']);
            }
                $this->redirect('/'.$_SESSION['index_url']);
            }
        }
        $this->data = $this->Channel->find('first', array('conditions' => array('Channel.id' => $id)));
    }
    //删除
    public function remove($id)
    {
        $this->operator_privilege('channels_remove');
        /*判断权限*/
        $pn = $this->Channel->find('list', array('fields' => array('Channel.id', 'Channel.name'), 'conditions' => array('Channel.id' => $id)));
        $this->Channel->deleteAll(array('Channel.id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.'操作员'.$this->admin['name'].' '.'删除渠道商:id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function activeremove($id)
    {
        /*判断权限*/
        $this->operator_privilege('channels_actives_remove');
        $result['flag'] = 0;
        $result['message'] = '删除失败';
        $pn = $this->ChannelActive->find('list', array('fields' => array('ChannelActive.id', 'ChannelActive.active_name'), 'conditions' => array('ChannelActive.id' => $id)));
        $this->ChannelActive->deleteAll(array('ChannelActive.id' => $id));
        //操作员日记
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.'操作员'.$this->admin['name'].' '.'删除活动:id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = '删除成功';
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //批量操作
    public function batch_operations()
    {
        //批量处理
        /*判断权限*/
        $this->operator_privilege('channels_remove');
        $result['flag'] = 2;
        $result['message'] = '删除失败';
        $user_checkboxes = $_REQUEST['checkboxes'];
        $this->Channel->deleteAll(array('id' => $user_checkboxes));
        $this->ChannelActive->deleteAll(array('id' => $user_checkboxes));
        $result['flag'] = 1;
        $result['message'] = '删除成功';
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //活动
    public function actives($id)
    {
        /*判断应用*/
        /*判断权限*/
        $this->operator_privilege('channels_active');
        $this->pageTitle = '渠道活动'.' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => '渠道管理','url' => '/channels/');
        $this->navigations[] = array('name' => '查看渠道活动','url' => '');

        $this->set('title_for_layout', '渠道活动'.' - '.$this->configs['shop_name']);
        $channel_list = $this->Channel->find('first', array('conditions' => array('Channel.id' => $id)));
        $this->set('channel_name', $channel_list['Channel']['name']);
        $this->set('id', $id);
        $condition = '';
        //关键字
        $active_name = '';
        if (isset($this->params['url']['active_name']) && $this->params['url']['active_name'] != '') {
            $active_name = $this->params['url']['active_name'];
            $condition['active_name like'] = "%$active_name%";
            $this->set('active_name', $active_name);
        }
            //	预售数
        $in_advance = '';
        $end_in_advance = '';
        if (isset($this->params['url']['in_advance']) && $this->params['url']['in_advance'] != '') {
            $in_advance = $this->params['url']['in_advance'];
            $condition['in_advance >='] = $in_advance;
            $this->set('in_advance', $in_advance);
        }
        if (isset($this->params['url']['end_in_advance']) && $this->params['url']['end_in_advance'] != '') {
            $end_in_advance = $this->params['url']['end_in_advance'];
            $condition['in_advance <='] = $end_in_advance;
            $this->set('end_in_advance', $end_in_advance);
        }
        //	冻结库存数
        $frozen_stock = '';
        $end_frozen_stock = '';
        if (isset($this->params['url']['frozen_stock']) && $this->params['url']['frozen_stock'] != '') {
            $frozen_stock = $this->params['url']['frozen_stock'];
            $condition['frozen_stock >='] = $frozen_stock;
            $this->set('frozen_stock', $frozen_stock);
        }
        if (isset($this->params['url']['end_frozen_stock']) && $this->params['url']['end_frozen_stock'] != '') {
            $end_frozen_stock = $this->params['url']['end_frozen_stock'];
            $condition['frozen_stock <='] = $end_frozen_stock;
            $this->set('end_frozen_stock', $end_frozen_stock);
        }
        // 退货数 
        $return_number = '';
        $end_return_number = '';
        if (isset($this->params['url']['return_number']) && $this->params['url']['return_number'] != '') {
            $return_number = $this->params['url']['return_number'];
            $condition['return_number >='] = $return_number;
            $this->set('return_number', $return_number);
        }
        if (isset($this->params['url']['end_return_number']) && $this->params['url']['end_return_number'] != '') {
            $end_return_number = $this->params['url']['end_return_number'];
            $condition['return_number <='] = $end_return_number;
            $this->set('end_return_number', $end_return_number);
        }
        //	销售数
        $sales_number = '';
        $end_sales_number = '';
        if (isset($this->params['url']['sales_number']) && $this->params['url']['sales_number'] != '') {
            $sales_number = $this->params['url']['sales_number'];
            $condition['sales_number >='] = $sales_number;
            $this->set('sales_number', $sales_number);
        }
        if (isset($this->params['url']['end_sales_number']) && $this->params['url']['end_sales_number'] != '') {
            $end_sales_number = $this->params['url']['end_sales_number'];
            $condition['sales_number <='] = $end_sales_number;
            $this->set('end_sales_number', $end_sales_number);
        }
        //	损益数
        $statement_number = '';
        $end_statement_number = '';
        if (isset($this->params['url']['statement_number']) && $this->params['url']['statement_number'] != '') {
            $statement_number = $this->params['url']['statement_number'];
            $condition['statement_number >='] = $statement_number;
            $this->set('statement_number', $statement_number);
        }
        if (isset($this->params['url']['end_statement_number']) && $this->params['url']['end_statement_number'] != '') {
            $end_statement_number = $this->params['url']['end_statement_number'];
            $condition['statement_number <='] = $end_statement_number;
            $this->set('end_statement_number', $end_statement_number);
        }
        //费用
        $cost = '';
        $end_cost = '';
        if (isset($this->params['url']['cost']) && $this->params['url']['cost'] != '') {
            $cost = $this->params['url']['cost'];
            $condition['cost >='] = $cost;
            $this->set('cost', $cost);
        }
        if (isset($this->params['url']['end_cost']) && $this->params['url']['end_cost'] != '') {
            $end_cost = $this->params['url']['end_cost'];
            $condition['cost <='] = $end_cost;
            $this->set('end_cost', $end_cost);
        }
        $condition['ChannelActive.channel_id'] = $id;
        $total = $this->ChannelActive->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $page = 1;
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
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
        $_SESSION['admin_url'] = $url;

        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'channels','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'ChannelActive');
        $this->Pagination->init($condition, $parameters, $options);
        $active_list = $this->ChannelActive->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'id desc'));
        $this->set('active_list', $active_list);
    }
    //添加活动
    public function activeadd($channel_id = '', $id = '')
    {
        //权限判断
        if (!empty($id) && !empty($channel_id)) {
            $this->operator_privilege('channels_actives_edit');
        } else {
            $this->operator_privilege('channels_actives_add');
        }
        $this->pageTitle = '渠道活动'.' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => '渠道管理','url' => '/channels/');
        $this->navigations[] = array('name' => '查看渠道活动','url' => '/channels/actives/'.$channel_id);
        $this->navigations[] = array('name' => '添加/编辑渠道活动','url' => '');

        if (!empty($id)) {
            $channl_active = $this->ChannelActive->find('first', array('conditions' => array('ChannelActive.id' => $id)));
            $this->set('channl_active', $channl_active);
        }
        $this->set('title_for_layout', ' 添加/编辑活动');
        //活动状态
        $this->set('state', $this->state);
        $this->set('channel_id', $channel_id);
        if ($this->RequestHandler->isPost()) {
            //	pr($this->data);die;
           $this->data['ChannelActive']['end_time'] = $this->data['ChannelActive']['end_time'].' 11:59:59';
            $this->ChannelActive->save($this->data);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.'操作员'.$this->admin['name'].' '.'添加/编辑渠道活动:id '.$id.' '.$pn[$id], $this->admin['id']);
            }
        //   $this->redirect("actives/".$this->data['ChannelActive']['channel_id']);
             $this->redirect('/'.$_SESSION['admin_url']);
        }
    }
}
