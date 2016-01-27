<?php

/*****************************************************************************
 * 地图
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id:
*****************************************************************************/
/**
 *这是一个名为MapsController的控制器
 *控制地图显示处理.
 *
 *@var
 *@var
 *@var
 *@var
 */
class MapsController extends AppController
{
    public $name = 'Maps';
    public $helpers = array('Html','Flash','Cache','Pagination');
    public $uses = array();
    public $components = array('RequestHandler','Cookie','Session','Captcha');

    public function index($position = 0)
    {
        $this->layout = null;
        Configure::write('debug', 1);
        if (isset($this->configs['shop_map']) && trim($this->configs['shop_map']) != '') {
            $position = $this->configs['shop_map'];
        }
        if (isset($_REQUEST['position']) && trim($_REQUEST['position']) != '') {
            $position = $_REQUEST['position'];
        }
        $map_locale = 'shop_mapchi';
        if (isset($_REQUEST['map_locale']) && trim($_REQUEST['map_locale']) == 'shop_mapchi') {
            $map_locale = 'shop_mapchi';
        } else {
            $map_locale = 'shop_mapeng';
        }
        $this->set('map_locale', $map_locale);
        //设置定位参数
        $this->set('position', $position);
    }
}
