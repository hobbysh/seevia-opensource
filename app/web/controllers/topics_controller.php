<?php

/*****************************************************************************
 * Seevia 专题管理
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 TopicsController 的信息系统控制器.
 */
class TopicsController extends AppController
{
    /*
    *@var $name
    *@var $helpers
    *@var $uses
    *@var $components
    */
    public $name = 'Topics';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html', 'Form', 'Javascript');
    public $uses = array('Topic','TopicI18n','Brand','ProductType','Product','TopicProduct','ProductLocalePrice','ProductRank','UserRank','TopicArticle');
    public $cacheQueries = true;
    public $cacheAction = '1 hour';
    /**
     *显示.
     */
    public function index($page = 1, $limit = 10, $order_field = 0, $order_type = 0)
    {
        $this->loadModel('Template');
        $template = $this->Template->find('first', array('conditions' => array('is_default' => 1)));
        if (isset($template['Template']['name']) && $template['Template']['name'] == 'seseyoyo') {
            $this->pageTitle = 'image'.' - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];
            $this->ur_heres[] = array('name' => 'image','url' => '/topics/');
        } else {
            $this->pageTitle = $this->ld['topic'].' - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];
            $this->ur_heres[] = array('name' => $this->ld['topic'],'url' => '/topics/');
        }
        $this->layout = 'default_full';

        $params['page'] = $page;
        $params['limit'] = $limit;
        $params['start_time'] = DateTime;
        $params['end_time'] = DateTime;
        $this->page_init($params);
    }
    /**
     *显示.
     *
     *@param $id
     */
    public function view($id)
    {
        $this->layout = 'default_full';
        if (!is_numeric($id) || $id < 1) {
            $this->pageTitle = $this->ld['invalid_id'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['invalid_id'], '/', 5);

            return;
        }
        $conditions = array('Topic.id' => $id,'Topic.status' => '1');
        $topic = $this->Topic->find('first', array('conditions' => $conditions));
        if (empty($topic)) {
            $this->pageTitle = $this->ld['topic'].$this->ld['home'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['topic'].$this->ld['not_exist'], '/', 5);

            return;
        } elseif (!empty($topic)) {
            $this->pageTitle = $topic['TopicI18n']['title'].' - '.$this->configs['shop_title'];
        }
        $params['id'] = $id;
        $params['topicInfo'] = $topic;
        $this->set('meta_description', $topic['TopicI18n']['meta_description'].' '.$this->configs['seo-des']);
        $this->set('meta_keywords', $topic['TopicI18n']['meta_keywords'].' '.$this->configs['seo-key']);
        $this->page_init($params);
        $this->ur_heres[] = array('name' => $this->ld['topic'],'url' => '/topics/');
        $this->ur_heres[] = array('name' => $topic['TopicI18n']['title'],'url' => '');
        $this->pageTitle = $topic['TopicI18n']['title'].' - '.$this->ld['topic'].$this->ld['home'].' - '.$this->configs['shop_title'];
    }

    public function download()
    {
        $this->pageTitle = '实玮网络客户端'.' - '.$this->configs['shop_name'];
        $this->layout = 'default_full';
        $this->page_init();
    }
}
