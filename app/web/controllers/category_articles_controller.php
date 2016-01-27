<?php

/*****************************************************************************
 * Seevia 用户收藏
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 CategoryArticlesController 的控制器
 *文章分类控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class  CategoryArticlesController extends AppController
{
    public $name = 'CategoryArticles';
    public $components = array('Pagination'); // Added
    public $helpers = array('Pagination'); // Added
    public $uses = array('ArticleCategory','Article','CategoryArticle','Tag');
    /**
     *显示.
     *
     *@param $cat_id 
     *@param $orderby 
     *@param $rownum 
     */
    public function view($cat_id, $orderby = 'orderby', $rownum = '')
    {
        $this->page_init();
        if (isset($this->configs['article_category_page_list_number'])) {
            $rownum = $this->configs['article_category_page_list_number'];
        } elseif (!empty($rownum)) {
            $rownum = $rownum;
        } else {
            $rownum = 5;
        }

        if (isset($this->configs['articles_list_orderby'])) {
            $orderby = $this->configs['articles_list_orderby'];
        } elseif (!empty($orderby)) {
            $orderby = $orderby;
        } else {
            $orderby = 'created';
        }
        $flag = 1;
        //文章分类信息列表
        if ($cat_id != 'hot') {
            $cat_detail = $this->CategoryArticle->findbyid($cat_id);
            if (empty($cat_detail)) {
                $this->pageTitle = $this->ld['classificatory'].$this->ld['not_exist'].' - '.$this->configs['shop_title'];
                $this->flash($this->ld['classificatory'].$this->ld['not_exist'], '/', 5);
                $flag = 0;
            }
        }
        if ($flag == 1) {
            //文章列表
/*        $condition = " ArticleCategory.category_id ='$cat_id' ";
        $total = $this->ArticleCategory->findCount($condition,0);
        $sortClass='Product'; */
         //pr($parameters);
         //修改
        if ($cat_id == 'hot') {
            $condition = '1=1';
            $this->pageTitle = $this->ld['hot'].$this->ld['article'].' - '.$this->configs['shop_title'];
            $hot_list = $this->Article->hot_list('', '');
            $total = count($hot_list);
            $sortClass = 'Articles';
        } else {

          //  $condition = " ArticleCategory.category_id =".$cat_id ;
         //   $total = $this->ArticleCategory->findCount($condition,0);
            $this->CategoryArticle->tree('A', $cat_id, LOCALE);
            $category_ids = $this->CategoryArticle->allinfo['A']['subids'][$cat_id];
            $condition = array('Article.category_id' => $category_ids);
            $total = $this->Article->findCount($condition, 0);
            $sortClass = 'Articles';
        }
            $now = date('Y-m-d H:i:s');
            $yestoday = date('Y-m-d H:i:s', strtotime('-1 day'));
            $filter = '1=1';
            $filter .= " and  Article.status = '1' and Article.created <= '".$now."' and  Article.created >='".$yestoday."' ";
            if ($cat_id != 'hot') {
                $filter .= 'and Article.category_id = '.$cat_id;
            }

            $today = $this->Article->find('all', array('conditions' => array($filter), 'fields' => array('Article.id'), 'recursive' => -1));
            $this->set('today', count($today));

         //修改end
        $page = 1;
            $parameters = array($orderby,$rownum,$page);
            $options = array();
            list($page) = $this->Pagination->init($condition, $parameters, $options, $total, $rownum, $sortClass); // Added
        //ArticleCategory信息
 //       $list_by_cat=$this->ArticleCategory->findAll($condition,''," ArticleCategory.$orderby asc ","$rownum",$page);
         $list_by_cat = $this->Article->find_list_by_cat($condition, $rownum, $page);
            $article_id = '';
            foreach ($list_by_cat as $key => $v) {
                $article_id .= $v['Article']['id'].',';
            }
            if ($article_id != '') {
                $article_id = substr($article_id, 0, -1);
                $article_list = $this->Article->get_list($article_id, '');
                foreach ($article_list as $key => $val) {
                    $article_list[$key]['Article']['created'] = substr($val['Article']['created'], 0, 10);
                    $article_list[$key]['Article']['modified'] = substr($val['Article']['modified'], 0, 10);
                    if (isset($this->configs['products_name_length']) && $this->configs['products_name_length'] > 0) {
                        $article_list[$key]['ArticleI18n']['sub_title'] = $this->Article->sub_str($val['ArticleI18n']['title'], $this->configs['article_title_length']);
                    }
                }
                $this->set('article_list', $article_list);
            }
    //    }

      $ur_heres = array();
            $ur_heres[] = array('name' => $this->ld['home'],'url' => '/');

      //$ur_heres[]=array('name'=>$this->ld['article_home_page'],'url'=>"/articles/index/1");

      $navigate = $this->CategoryArticle->tree('A', $cat_id);
            $cat_navigate = $navigate['assoc'];
            krsort($cat_navigate);

            $category_arr = $this->CategoryArticle->findbyid($cat_id);
            if ($cat_id == 'hot') {
                $ur_heres[] = array('name' => $this->ld['hot'].$this->ld['article'],'url' => '/articles/index/hot');
            } else {
                if ($category_arr['CategoryArticle']['parent_id'] == 0) {
                    if (!empty($category_arr['CategoryArticle']['link'])) {
                        $ur_heres[] = array('name' => $category_arr['CategoryArticleI18n']['name'],'url' => "/{$category_arr['CategoryArticle']['link']}");
                    } else {
                        $ur_heres[] = array('name' => $category_arr['CategoryArticleI18n']['name'],'url' => '/category_articles/'.$category_arr['CategoryArticle']['id']);
                    }
                }
                if ($category_arr['CategoryArticle']['parent_id'] > 0) {
                    $main_arr = $this->CategoryArticle->findbyid($category_arr['CategoryArticle']['parent_id']);
                    if (!empty($main_arr['CategoryArticle']['link'])) {
                        $ur_heres[] = array('name' => $main_arr['CategoryArticleI18n']['name'],'url' => "/{$main_arr['CategoryArticle']['link']}");
                    } else {
                        $ur_heres[] = array('name' => $main_arr['CategoryArticleI18n']['name'],'url' => '/category_articles/'.$main_arr['CategoryArticle']['id']);
                    }
                    if (!empty($category_arr['CategoryArticle']['link'])) {
                        $ur_heres[] = array('name' => $category_arr['CategoryArticleI18n']['name'],'url' => "/{$category_arr['CategoryArticle']['link']}");
                    } else {
                        $ur_heres[] = array('name' => $category_arr['CategoryArticleI18n']['name'],'url' => '/category_articles/'.$category_arr['CategoryArticle']['id']);
                    }
                }
                $this->pageTitle = $category_arr['CategoryArticleI18n']['name'].' - '.$this->configs['shop_title'];
            }

            $this->set('categories_tree', $navigate['tree']);
            $this->set('category_type', 'A'); //判断是文章
      $this->set('ur_heres', $ur_heres);
      //排序方式,显示方式,分页数量限制
      $this->set('orderby', $orderby);
            $this->set('rownum', $rownum);
            $this->set('total', $total);
        }//flag end


      //set js 语言
      $js_languages = array('page_number_expand_max' => $this->ld['page_number'].$this->ld['not_exist']);
        $this->set('js_languages', $js_languages);
        $this->set('meta_description', isset($cat_detail['CategoryArticleI18n']['meta_description']) ? $cat_detail['CategoryArticleI18n']['meta_description'] : $this->ld['hot'].$this->ld['article']);
        $this->set('meta_keywords', isset($cat_detail['CategoryArticleI18n']['meta_keywords']) ? $cat_detail['CategoryArticleI18n']['meta_keywords'] : $this->ld['hot'].$this->ld['article']);

        /* 设置模板布局 */
        if (!empty($category_arr['CategoryArticle']['layout'])) {
            $this->layout = $category_arr['CategoryArticle']['layout'];
        }
        /* 设置模板 */
        if (!empty($category_arr['CategoryArticle']['template'])) {
            $this->render($category_arr['CategoryArticle']['template'], $this->layout);
        }
    }
    /**
     *标签.
     *
     *@param $tag 
     *@param $orderby 
     *@param $rownum 
     */
    public function tag($tag, $orderby = 'orderby', $rownum = '')
    {
        $tag = UrlDecode($tag);
        $this->page_init();
        if (isset($this->configs['article_category_page_list_number'])) {
            $rownum = $this->configs['article_category_page_list_number'];
        } elseif (!empty($rownum)) {
            $rownum = $rownum;
        } else {
            $rownum = 5;
        }

        if (isset($this->configs['articles_list_orderby'])) {
            $orderby = $this->configs['articles_list_orderby'];
        } elseif (!empty($orderby)) {
            $orderby = $orderby;
        } else {
            $orderby = 'created';
        }

        $now = date('Y-m-d H:i:s');
        $yestoday = date('Y-m-d H:i:s', strtotime('-1 day'));

        $filter = '1=1';
        $filter .= " and  Article.status = '1' and Article.created <= '".$now."' and  Article.created >='".$yestoday."'";

        $today = $this->Article->findall($filter);
        $this->set('today', count($today));
        $article_id = array();
        if (isset($this->configs['use_tag']) && $this->configs['use_tag'] == 1) {
            $tags = $this->Tag->findall(" TagI18n.name = '".$tag."' and TagI18n.locale = '".LOCALE."' and Tag.status ='1'");
            if (is_array($tags) && sizeof($tags) > 0) {
                foreach ($tags as $k => $v) {
                    $article_id[] = $v['Tag']['type_id'];
                }
            }
        }

        $conditions = array('Article.id' => $article_id,'Article.status' => '1');

        $total = $this->Article->findCount($conditions, 0);
        $sortClass = 'Article';
        $page = 1;
        $parameters = array($orderby,$rownum,$page);
        $options = array();
        $options = array('page' => $page,'show' => $rownum,'modelClass' => 'Article');
        $page = $this->Pagination->init($conditions, $parameters, $options); // Added
        if ($article_id != '') {
            $article_list = $this->Article->findall($conditions, '', "Article.$orderby", "$rownum", $page);
            foreach ($article_list as $key => $val) {
                $article_list[$key]['Article']['created'] = substr($val['Article']['created'], 0, 10);
                $article_list[$key]['Article']['modified'] = substr($val['Article']['modified'], 0, 10);
                if (isset($this->configs['products_name_length']) && $this->configs['products_name_length'] > 0) {
                    $article_list[$key]['ArticleI18n']['sub_title'] = $this->Article->sub_str($val['ArticleI18n']['title'], $this->configs['article_title_length']);
                }
            }
            $this->set('article_list', $article_list);
        }

        $ur_heres = array();
        $ur_heres[] = array('name' => $this->ld['home'],'url' => '/');
        $ur_heres[] = array('name' => $tag,'url' => '/articles/index/hot');
        $this->set('ur_heres', $ur_heres);
              //排序方式,显示方式,分页数量限制
              $this->set('orderby', $orderby);
        $this->set('rownum', $rownum);
        $this->set('total', $total);

        $this->pageTitle = $tag.' - '.$this->configs['shop_title'];
              //set js 语言
              $js_languages = array('page_number_expand_max' => $this->ld['page_number'].$this->ld['not_exist']);
        $this->set('js_languages', $js_languages);
        $this->set('meta_description', $tag);
        $this->set('meta_keywords', $tag);
    }
}
