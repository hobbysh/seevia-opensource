<?php

/*****************************************************************************
 * Seevia 一维码图片显示
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 BarcodesController 的获取图片控制器.
 */
class BarcodesController extends AppController
{
    /*
    *@var $name
    *@var $uses
    */
    public $name = 'Barcodes';
    public $uses = array();
    /**
     *显示页.
     */
    public function view($code)
    {
        App::Import('Vendor', 'barcode', array('file' => 'barcode.class.php'));
        $b = new BarCode();
        $this->layout = 'blank';
        $encoding = '128';
        $scale = '2';
        $mode = 'png';
        $b->barcode_print($code, $encoding, $scale, $mode);
    }
}
