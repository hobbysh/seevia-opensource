<?php

/*****************************************************************************
 * Seevia 在线调查控制
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 VotesController 的投票控制器.
 */
class JsController extends AppController
{
    /*
        *@var $name
    */
    public $name = 'Js';
    public $uses = array('LanguageDictionary');

    public function selectlang($lang = 'chi')
    {
        //  		$this->layout="ajax";
//  		$ld_js=$this->LanguageDictionary->find('all', array('fields' => array('LanguageDictionary.name', 'LanguageDictionary.value'), 'conditions' => array('locale' => $lang ,'type'=>'js')));
//  		$this->set("ld_js",$ld_js);

        $this->layout = 'ajax';
        header('Content-Type:application/x-javascript');
        //header("Cache-Control:public");
        header('Pragma: cache');
        $offset = 60 * 60 * 24;  //强制缓一天   max-age=
        $ExpStr = 'Expires: '.gmdate('D,d M Y H:i:s', time() + $offset).' GMT';
        $ExpStr2 = 'http/1.1 304 Not Modified';
        header($ExpStr);
        $ld_js = $this->LanguageDictionary->find('all', array('fields' => array('LanguageDictionary.name', 'LanguageDictionary.value'), 'conditions' => array('locale' => $lang, 'type' => 'js')));
        $this->set('ld_js', $ld_js);
    }

    public function showcss($thm = 'default')
    {
        Configure::write('debug', 1);
        $show_css = ClassRegistry::init('Template')->find('first', array('conditions' => array('name' => $thm), 'fields' => array('Template.show_css', 'Template.mobile_css')));
        header('Content-type: text/css; charset: UTF-8');
        $this->loadModel('Template');
        $mobile_status = $this->Template->find('first', array('conditions' => array('is_default' => 1), 'fields' => array('Template.mobile_status', 'Template.name')));
        if ((isset($_SESSION['is_mobile']) && $_SESSION['is_mobile'] == '1') || (($this->RequestHandler->isMobile() && $mobile_status['Template']['mobile_status'] == '1') && !isset($_SESSION['is_mobile']))) {
            $cssInfo = json_decode($show_css['Template']['mobile_css'], true);
            die($cssInfo['mobile_comment_css']);
        } else {
            die($show_css['Template']['show_css']);
        }
    }
}
