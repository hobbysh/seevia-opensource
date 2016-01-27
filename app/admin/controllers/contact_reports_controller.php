<?php

/*****************************************************************************
 * Seevia 订单工厂报表控制器
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
 *这是一个名为 OrderFactoryReportsController 的控制器
 *后台订单工厂报表控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class ContactReportsController extends AppController
{
    public $name = 'ContactReports';
    public $helpers = array('Pagination');
    public $components = array('Pagination','RequestHandler','Email','Orderfrom','EcFlagWebservice','Phpexcel');
    public $uses = array('Operator','Application','ConfigI18n','Language','UserAddress','Order','OrderProduct','PurchaseOrder','Contact');
    public $dear_id = array();

    public function index()
    {
        //$this->operator_privilege('contacts_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/reports/','sub' => '/contact_reports/');
        $this->navigations[] = array('name' => $this->ld['report'],'url' => '');
        $this->navigations[] = array('name' => '预约报表','url' => '/contact_reports/');

        $contact_condition = '';//预约时间日期条件
        $predict_condition = '';//预计发货条件
        //预约时间开始日期
        $start_date = date('Y-m-d', strtotime('-6 day'));
        if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') {
            $start_date = trim($_REQUEST['start_date']);
        }
        $this->set('start_date', $start_date);
        //预约时间结束日期
        $end_date = date('Y-m-d', time());
        if (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') {
            $end_date = trim($_REQUEST['end_date']);
        }
        $this->set('end_date', $end_date);
        //时间段
        $time_quantum = array('10:30-12:30','12:30-14:30','14:30-16:30','16:30-18:30','18:30-20:30');
        $time_type = '1';//默认按每日生成报表

        $date_arr = '';
        $time_format = '%Y-%m-%d';//按每日
        $date_arr = $this->prDates($start_date, $end_date);//日期区间数组
        $this->set('time_type', $time_type);
        $contact_list = $this->Contact->query("select time,count(time) ,`parameter_02` from (
select date_format(`parameter_01`,'".$time_format."') time,`parameter_02`  from svcms_contacts  where `parameter_01` <= '".$end_date."' and `parameter_01` >= '".$start_date."' ) as t group by time,`parameter_02`");
        $order_list = $this->Order->query("select time,count(time) ,`schedule_time` from (
select date_format(`schedule_date`,'".$time_format."') time,`schedule_time`  from svoms_orders  where `schedule_date` <= '".$end_date."' and `schedule_date` >= '".$start_date."' ) as t group by time,`schedule_time`");
        //pr($order_list);
        $data_contact = array();
        $data_order = array();
        foreach ($contact_list as $k => $v) {
            $data_contact[$v['t']['time']][$v['t']['parameter_02']] = $v['0']['count(time)'];
        }

        foreach ($date_arr as $ak => $av) {
            if (!array_key_exists($av, $data_contact)) {
                $data_contact[$av] = array('10:30-12:30' => 0,'12:30-14:30' => 0,'14:30-16:30' => 0,'16:30-18:30' => 0,'18:30-20:30' => 0);
            } else {
                foreach ($time_quantum as $tk => $tv) {
                    //pr($data_contact[$av]);
                    if (!array_key_exists($tv, $data_contact[$av])) {
                        $data_contact[$av][$tv] = 0;
                    }
                }
            }
        }

        //排序
        $data_contact_arr = array();
        foreach ($date_arr as $ak => $av) {
            foreach ($data_contact as $dk => $dv) {
                if ($av == $dk) {
                    $data_contact_arr[$av] = $dv;
                }
            }
        }
        foreach ($order_list as $k => $v) {
            $data_order[$v['t']['time']][$v['t']['schedule_time']] = $v['0']['count(time)'];
        }
        foreach ($date_arr as $ak => $av) {
            if (!array_key_exists($av, $data_order)) {
                $data_order[$av] = array('10:30-12:30' => 0,'12:30-14:30' => 0,'14:30-16:30' => 0,'16:30-18:30' => 0,'18:30-20:30' => 0);
            } else {
                foreach ($time_quantum as $tk => $tv) {
                    //pr($data_order[$av]);
                    if (!array_key_exists($tv, $data_order[$av])) {
                        $data_order[$av][$tv] = 0;
                    }
                }
            }
        }
        //排序
        $data_order_arr = array();
        foreach ($date_arr as $ak => $av) {
            foreach ($data_order as $dk => $dv) {
                if ($av == $dk) {
                    $data_order_arr[$av] = $dv;
                }
            }
        }
        //pr($data_contact_arr);
        //pr($data_order_arr);
        $this->set('data_order_arr', $data_order_arr);
        $this->set('data_contact_arr', $data_contact_arr);
        $this->set('time_quantum', $time_quantum);
        $this->set('date_arr', $date_arr);
        //格式化数据
        $order_codes = '';
        $user_id_array = array();
        $this->set('title_for_layout', '报表 - '.$this->ld['page'].' - '.$this->configs['shop_name']);
    }

    public function prDates($start, $end)
    {
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        $date_arr = array();
        while ($dt_start <= $dt_end) {
            //echo date('Y-m-d',$dt_start)."\n";
            array_push($date_arr, date('Y-m-d', $dt_start));
            $dt_start = strtotime('+1 day', $dt_start);
        }

        return $date_arr;
    }
    public function prMonths($start, $end)
    {
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        $date_arr = array();
        while ($dt_start <= $dt_end) {
            //echo date('Y-m-d',$dt_start)."\n";
            array_push($date_arr, date('Y-m', $dt_start));
            $dt_start = strtotime('+31 day', $dt_start);
        }

        return $date_arr;
    }
}
