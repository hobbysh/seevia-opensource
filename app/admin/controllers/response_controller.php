<?php

/*****************************************************************************
 * Seevia 	淘宝店铺管理
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
//App::import('Vendor','Topapi' ,array('file'=>'Topapi.class.php'));
App::import('Vendor', 'Topsdk', array('file' => 'TopSdk.php'));
class ResponseController extends AppController
{
    public $name = 'Response';
    public $helpers = array('Html','Pagination','fck','Tinymce');
    public $components = array('Pagination','RequestHandler','Cookie');
    public $uses = array('TaobaoShop','TaobaoItem','User','OperatorLog');
    public function update_session()
    {
        //	pr($_REQUEST);die;
        $taobao_shop_info = $this->TaobaoShop->findbyapp_key($_REQUEST['top_appkey']);
        $taobao_shop_info['TaobaoShop']['top_session'] = $_REQUEST['top_session'];
        $this->TaobaoShop->save($taobao_shop_info);
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'更新淘宝网SESSION', $this->admin['id']);
        }
        $msg = '更新成功 APPKEY:'.$_REQUEST['top_appkey'].'  TOP_SESSION:'.$_REQUEST['top_session'];
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/admin/taobao_shops/'.'"</script>';
        die();
    //	die("更新成功 APPKEY:".$_REQUEST["top_appkey"]."  TOP_SESSION:".$_REQUEST["top_session"]);
    }
}
