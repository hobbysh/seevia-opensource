<?php

/*****************************************************************************
 * Seevia 日志管理
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
 *这是一个名为 LogManagementController 的控制器
 *后台定时器设置控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class LogManagementsController extends AppController
{
    public $name = 'LogManagements';
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('SystemResource','Config','ConfigI18n','LanguageDictionary','SmsSendHistory','MailStatistic','Shop');

    /**
     *显示商店日志内容列表.
     */
    public function index()
    {
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['log_management'],'url' => '/log_managements/');
        $this->set('navigations', $this->navigations);
        $this->menu_path = array('root' => '/system/','sub' => '/log_managements/');

        //短信日志数量
        $now = date('Y-m-d');//当前时间
        $sms_condition = '';
        $sms_fields = array('SmsSendHistory.id','SmsSendHistory.flag','SmsSendHistory.send_date');
        $sms_count = $this->SmsSendHistory->find('count', array('conditions' => $sms_condition));
        $sms_success = $this->SmsSendHistory->find('count', array('conditions' => array('SmsSendHistory.flag' => 0, "SmsSendHistory.send_date>= '".$now."'")));//成功
        $sms_error = $this->SmsSendHistory->find('count', array('conditions' => array('SmsSendHistory.flag' => 1, "SmsSendHistory.send_date>= '".$now."'")));//失败
        $this->set('sms_count', $sms_count);//短信日志数量
        $this->set('sms_success', $sms_success);
        $this->set('sms_error', $sms_error);
        //物流日志
        if (array_key_exists('APP-API-WEBSERVICE', $this->apps['Applications']) && constant('Product') == 'AllInOne') {
            $this->loadModel('WebserviceLog');
            $web_condition = '';
            $web_fields = array('WebserviceLog.id','WebserviceLog.status','WebserviceLog.created');
            $web_count = $this->WebserviceLog->find('count', array('conditions' => $web_condition));
            $web_success = $this->WebserviceLog->find('count', array('conditions' => array('WebserviceLog.status' => 1, "WebserviceLog.created>= '".$now."'")));//成功
            $web_error = $this->WebserviceLog->find('count', array('conditions' => array('WebserviceLog.status' => 0, "WebserviceLog.created>= '".$now."'")));//失败
            $this->set('web_count', $web_count);
            $this->set('web_success', $web_success);
            $this->set('web_error', $web_error);
        }
        if (array_key_exists('APP-WBMKT', $this->apps['Applications']) && constant('Product') == 'AllInOne') {
            $this->loadModel('WeiboOpLog');
            //微营销日志
            $wb_condition = '';
            $wb_fields = array('WeiboOpLog.id','WeiboOpLog.status','WeiboOpLog.created');
            $wb_count = $this->WeiboOpLog->find('count', array('conditions' => $wb_condition));
            $wb_success = $this->WeiboOpLog->find('count', array('conditions' => array('WeiboOpLog.error_msg IS NULL', "WeiboOpLog.created>= '".$now."'")));//成功
            $wb_error = $this->WeiboOpLog->find('count', array('conditions' => array('WeiboOpLog.error_msg IS NOT NULL', "WeiboOpLog.created>= '".$now."'")));
            $this->set('wb_count', $wb_count);
            $this->set('wb_success', $wb_success);
            $this->set('wb_error', $wb_error);
        }
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('TaobaoUpdateLog');
            $this->loadModel('JingdongUpdateLog');
            $tbs_count = $this->Shop->find('count', array('conditions' => 'Shop.type=2'));//淘宝店铺数量
            $tb_condition = '';
            $tb_fields = array('TaobaoUpdateLog.id','TaobaoUpdateLog.status','TaobaoUpdateLog.created');
            $tb_count = $this->TaobaoUpdateLog->find('count', array('conditions' => $tb_condition));
            $tb_success = $this->TaobaoUpdateLog->find('count', array('conditions' => array('TaobaoUpdateLog.status' => 1, "TaobaoUpdateLog.created>= '".$now."'")));
            $tb_error = $this->TaobaoUpdateLog->find('count', array('conditions' => array('TaobaoUpdateLog.status' => 0, "TaobaoUpdateLog.created>= '".$now."'")));

            //京东日志
            $jds_count = $this->Shop->find('count', array('conditions' => 'Shop.type=3'));//京东店铺数量
            $jd_condition = '';
            $jd_fields = array('JingdongUpdateLog.id','JingdongUpdateLog.status','JingdongUpdateLog.created');
            $jd_count = $this->JingdongUpdateLog->find('count', array('conditions' => $tb_condition));
            $jd_success = $this->JingdongUpdateLog->find('count', array('conditions' => array('JingdongUpdateLog.status' => 1, "JingdongUpdateLog.created>= '".$now."'")));//成功
            $jd_error = $this->JingdongUpdateLog->find('count', array('conditions' => array('JingdongUpdateLog.status' => 0, "JingdongUpdateLog.created>= '".$now."'")));
            //邮件日志
            $mail_condition = '';
            $mail_fields = array('MailStatistic.id','MailStatistic.value','MailStatistic.mail_date');
            $mail_count = $this->MailStatistic->find('count', array('conditions' => ''));//邮件统计数量
            $mail_success = $this->MailStatistic->find('count', array('conditions' => array('MailStatistic.value>=1', "MailStatistic.mail_date>= '".$now."'")));//发布成功
            $mail_error = $this->MailStatistic->find('count', array('conditions' => array('MailStatistic.value' => 0, "MailStatistic.mail_date>= '".$now."'")));
            $this->set('mail_count', $mail_count);
            $this->set('mail_success', $mail_success);
            $this->set('mail_error', $mail_error);
            $this->set('tb_count', $tb_count);
            $this->set('tb_success', $tb_success);
            $this->set('tb_error', $tb_error);
            $this->set('tbs_count', $tbs_count);
            $this->set('jds_count', $jds_count);
            $this->set('jd_count', $jd_count);
            $this->set('jd_success', $jd_success);
            $this->set('jd_error', $jd_error);
        }

        if (constant('Product') == 'AllInOne') {
            $this->loadModel('WebserviceLog');
            $wsl_count = $this->WebserviceLog->find('count');//物流日志数量
            $this->set('wsl_count', $wsl_count);
        }

        $this->set('start_time', $now);
        $this->set('end_time', $now);
        $this->set('title_for_layout', $this->ld['log_management'].'-'.$this->configs['shop_name']);
    }
}
