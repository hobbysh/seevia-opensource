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
 *这是一个名为BlogsController的控制器
 *管理博客.
 *
 *@var
 *@var
 *@var
 *@var
 */
class BlogsController extends AppController
{
    public $name = 'Blogs';
    public $helpers = array('Html');
    public $uses = array('BlogPost','BlogPostI18n','BlogComment');
    public $components = array('RequestHandler','Email');

    /**
     *首页.
     */
    public function home()
    {
        $this->pageTitle = '首页 - '.$this->configs['shop_title'];
        $meta_description = '';
        $meta_keywords = '';
        /* 文章详细 */
        $post = $this->BlogPost->find_first_post();//model调用

        /* 文章评论列表 */
        $post_comments = $this->BlogComment->find_post_comments($post['BlogPost']['id']);//model调用
        /* 文章列表 */
        $post_list = $this->BlogPost->find_first_post_list();//model调用
        /* 月份列表  */
        $month_arr = $this->get_month_arr();
        $this->page_init();
        //$this->get_current_nav('/');
        $this->set('post_comments', $post_comments);
        $this->set('month_arr', $month_arr);
        $this->set('post', $post);
        $this->set('post_list', $post_list);
        $js_languages = array('name_not_empty' => $this->ld['user_name'].$this->ld['can_not_empty'],
                            'email_not_empty' => $this->ld['e-mail_empty'],
                            'website_not_empty' => $this->ld['website'].$this->ld['can_not_empty'],
                            'email_not_empty' => $this->ld['e-mail_empty'],
                            'reply_content_not_empty' => $this->ld['reply'].$this->ld['content'].$this->ld['can_not_empty'],
                             );
        $this->set('js_languages', $js_languages);
        //pr($post_list);
        $this->layout = 'blog';
    }
    /**
     *列表页.
     */
    public function index()
    {
        $this->pageTitle = '博客文章列表 - '.$this->configs['shop_title'];
        /* 文章列表 */
        $post_list = $this->BlogPost->find_first_post_list();//model调用
        /* 月份列表 */
        $month_arr = $this->get_month_arr();
        $this->page_init();
        //$this->get_current_nav('/blogs/index');
        $this->set('month_arr', $month_arr);
        $this->set('post_list', $post_list);
        $this->layout = 'blog';
    }
    /**
     *详细页兼评论提交页.
     *
     *@param $id 输入id
     */
    public function view($id)
    {
        /* 文章详细 */
        $post = $this->BlogPost->findById($id);
        if (empty($post)) {
            $this->pageTitle = $this->ld['article'].$this->ld['not_exist'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['article'].$this->ld['not_exist'], '/', '', '');

            return;
        }
        /* 文章评论 */
        if ($this->RequestHandler->isPost()) {
            $comment = array('BlogComment' => array(
                                                'post_id' => $_POST['post_id'],
                                                'author' => $_POST['author'],
                                                'author_email' => $_POST['author_email'],
                                                'author_url' => $_POST['author_url'],
                                                'content' => $_POST['content'],
                                                'agent' => '',
                                                'user_id' => empty($_SESSION['User']['User']['id']) ? 0 : $_SESSION['User']['User']['id'],
                                                'author_ip' => $this->real_ip(),
                                                'approved' => '1',
                            ),
            );
            //pr($comment);
            $this->BlogComment->save($comment);
            $blogpost_update = array('BlogPost' => array(
                                                'id' => $_POST['post_id'],
                                                'comment_count' => $post['BlogPost']['comment_count'] + 1,
                            ),
            );
            $this->BlogPost->save($blogpost_update);
        }
        /* 评论列表 */
        $post_comments = $this->BlogComment->find_post_comments($id);//model调用
        //pr($post_comments);
        /* 文章列表 */
        $post_list = $this->BlogPost->find_post_list();//model调用
        $this->pageTitle = $post['BlogPostI18n']['title'].' - '.$this->configs['shop_title'];
        /* 月份列表 */
        $month_arr = $this->get_month_arr();
        $this->page_init();
        //$this->get_current_nav('/blogs/index');
        $this->set('month_arr', $month_arr);
        $this->set('post', $post);
        //pr($post_comments);
        $this->set('post_comments', $post_comments);
        $this->set('post_list', $post_list);
        $js_languages = array('name_not_empty' => $this->ld['user_name'].$this->ld['can_not_empty'],
                            'email_not_empty' => $this->ld['e-mail_empty'],
                            'website_not_empty' => $this->ld['website'].$this->ld['can_not_empty'],
                            'email_not_empty' => $this->ld['e-mail_empty'],
                            'reply_content_not_empty' => $this->ld['reply'].$this->ld['content'].$this->ld['can_not_empty'],
                             );
        $this->set('js_languages', $js_languages);
        $this->layout = 'blog';
    }
    /**
     *搜索结果页.
     *
     *@param $month 输入月份
     *@param $keywords 输入关键字
     */
    public function search($month, $keywords = '')
    {
        //$month = '2009-11';
        /* 检索条件 */
        $this->pageTitle = '搜索结果 - '.$this->configs['shop_title'];
        $conditions = array();
        $conditions['AND'][] = "BlogPost.status='publish'";
        if ($month != 'all' && strtotime($month)) {
            $time_start = $month.'-01';
            $time_end = $month.'-31 23:59:59';
            $conditions['AND'][] = "BlogPost.created >= '$time_start'";
            $conditions['AND'][] = "BlogPost.created <= '$time_end'";
        }

        if (!empty($keywords)) {
            $keywords = urldecode($keywords);
            $conditions['OR'][]['BlogPostI18n.title like'] = '%'.$keywords.'%';
            $conditions['OR'][]['BlogPostI18n.content like'] = '%'.$keywords.'%';
            $conditions['OR'][]['BlogPostI18n.excerpt like '] = '%'.$keywords.'%';
            $this->set('keywords', $keywords);
            //$conditions['BlogPost.created <= '] = $time_end;
        }
        /* 文章列表 */
        $post_list = $this->BlogPost->find_second_post_list($conditions);//model调用
        /* 月份列表 */
        $month_arr = $this->get_month_arr();
        $this->page_init();
        //$this->get_current_nav('/blogs/index');
        $this->set('month_arr', $month_arr);
        //pr($post_list);
        $this->set('post_list', $post_list);
        $this->layout = 'blog';
    }
    /**
     *分类页.
     */
    public function category()
    {
        $this->pageTitle = '分类 - '.$this->configs['shop_title'];
        $month_arr = $this->get_month_arr();
        $this->page_init();
        //$this->get_current_nav('/blogs/index');
        $this->set('month_arr', $month_arr);
    }
    /**
     *日期交互.
     *
     *@param $date 输入时间
     */
    public function dateformate($date)
    {
    }

    /**
     *获取月份的日期列表.
     *
     * @return $month_arr 返回数组形式的 日期列表
     */
    public function get_month_arr()
    {
        $old_created = $this->BlogPost->find_old_created();//model调用
        $min_month = date('Y-m', strtotime($old_created['BlogPost']['created']));
        //$min_month = '2007-11';
        $month_count = 12;
        $i = 1;
        $month_arr = array(date('Y-m'));
        $next_month = mktime(0, 0, 0, date('m') - 1, date('d'),   date('Y'));
        while ($next_month > strtotime($min_month) && $i < $month_count) {
            $month_arr[] = date('Y-m', $next_month);
            $next_month = mktime(0, 0, 0, date('m', $next_month) - 1, date('d', $next_month),   date('Y', $next_month));
            ++$i;
        }

        return $month_arr;
    }
    /**
     * 获得用户的真实IP地址.
     *
     * @return string
     */
    public function real_ip()
    {
        static $realip = null;
        if ($realip !== null) {
            return $realip;
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

        return $realip;
    }
}
