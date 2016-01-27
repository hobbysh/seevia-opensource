<?php

/*****************************************************************************
 * Seevia 用户中心首页
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 TagsController 的标签控制器.
 */
class TagsController extends AppController
{
    /*
    *@var $name
    *@var $helpers
    *@var $uses
    *@var $components
    */
    public $name = 'Tags';
    public $helpers = array('Html','Javascript');
    public $uses = array('Tag');
    public $components = array('RequestHandler');

    /**
     *函数 user_index() 用于进入标签管理页面.
     */
    public function user_index()
    {
        if (!isset($_SESSION['User'])) {
            $this->redirect('/login/');
        }
        $this->page_init();
        $this->ur_heres[] = array('name' => $this->ld['my_tags'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $tags = $this->Tag->find('all', array(
        'fields' => array('Tag.id', 'Tag.type_id', 'Tag.type', 'TagI18n.name'),
        'conditions' => array('Tag.user_id = '.$_SESSION['User']['User']['id']), ));
    //	pr($tags);
        $tags_p = array();
        $tags_a = array();
        if (isset($tags) && sizeof($tags) > 0) {
            foreach ($tags as $k => $v) {
                if ($v['Tag']['type'] == 'P') {
                    $tags_p[] = $v;
                } elseif ($v['Tag']['type'] == 'A') {
                    $tags_a[] = $v;
                }
            }
        }

    //	pr($tags);
        $this->pageTitle = $this->ld['my_tags'].' - '.$this->configs['shop_title'];
        $js_languages = array('confirm_to_remove_label' => $this->ld['confirm_to_remove_label']);
        $this->set('js_languages', $js_languages);
        $this->set('tags_p', $tags_p);
        $this->set('tags_a', $tags_a);
        //$this->layout="default_full";
    }

    /**
     *函数 user_remove() 用于删除标签.
     */
    public function user_remove($id)
    {
        $tag = $this->Tag->get_tag($id, $_SESSION['User']['User']['id']);
        $this->Tag->del($tag);
        $this->redirect('/tags/');
    }
    public function view($condition, $page = '1')
    {
        $conditions = array('LOWER(substr(TagI18n.name,1,2))' => $condition);
        //pr($conditions);
        $tag = $this->Tag->get_tags_by_products($conditions, '120', $page);

        //pr($tag);
        $this->set('tag', $tag);
    }
}
