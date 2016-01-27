<?php

/*****************************************************************************
 * Seevia 文章管理
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
 *这是一个名为 ArticlesController 的控制器
 *文章管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');//加载公共控制器
class ArticlesController extends AppController
{
    public $name = 'Articles';
    public $components = array('Pagination','RequestHandler','Phpexcel','Phpcsv');
    public $helpers = array('Pagination','Html','Form','Javascript','Time','Ckeditor');
    public $uses = array('Article','ArticleI18n','ArticleCategory','CategoryProduct','Document','ArticleGallery','ArticleGalleryI18n','Tag','TagI18n','Route','Brand','ProductArticle','Product','Document','OperatorLog','UserRank','UserRankI18n','CategoryArticle','CategoryArticleI18n','TopicArticle','Profile','ProfileFiled','ProfilesFieldI18n');

    /**
     *显示文章列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('articles_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/cms/','sub' => '/articles/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_articles'],'url' => '');

        $this->set('article_cats', 0);
        $this->set('article_cat_type', 0);
        $condition = '';
        $start_date = '';        //开始时间
        $end_date = '';        //结束时间
        $recommand = '-1';
        $front = '-1';
        if (isset($this->params['url']['article_cat']) && $this->params['url']['article_cat'] != 0) {
            $category_id = $this->params['url']['article_cat'];
            $category_ids = isset($this->CategoryArticle->allinfo['subids'][$category_id]) ? $this->CategoryArticle->allinfo['subids'][$category_id] : $category_id;
            $categories_products = $this->ArticleCategory->findAllbycategory_id($category_ids);
            $pro_id = array();
            if (sizeof($categories_products) > 0) {
                foreach ($categories_products as $k => $v) {
                    $pro_id[] = $v['ArticleCategory']['article_id'];
                }
                $condition['or']['Article.id'] = $pro_id;
                $condition['or']['Article.category_id'] = $category_ids;
            } else {
                $condition['Article.category_id'] = $category_ids;
            }
            $this->set('article_cats', $this->params['url']['article_cat']);
        }
        if (isset($this->params['url']['article_cat_type']) && $this->params['url']['article_cat_type'] != '') {
            $condition['Article.type'] = $this->params['url']['article_cat_type'];
            $this->set('article_cat_type', $this->params['url']['article_cat_type']);
        }
        if (isset($this->params['url']['title']) && $this->params['url']['title'] != '') {
            $condition['ArticleI18n.title LIKE'] = '%'.$this->params['url']['title'].'%';
            $this->set('titles', $this->params['url']['title']);
        }
        if (isset($this->params['url']['start_date']) && $this->params['url']['start_date'] != '') {
            $condition['Article.created >'] = $this->params['url']['start_date'];
            $this->set('start_date', $this->params['url']['start_date']);
            $start_date = $this->params['url']['start_date'];
        }
        if (isset($this->params['url']['end_date']) && $this->params['url']['end_date'] != '') {
            $condition['Article.created <'] = $this->params['url']['end_date'];
            $this->set('end_date', $this->params['url']['end_date']);
            $end_date = $this->params['url']['end_date'];
        }
        if (isset($this->params['url']['recommand']) && $this->params['url']['recommand'] != '-1') {
            $condition['Article.recommand'] = $this->params['url']['recommand'];
            $recommand = $this->params['url']['recommand'];
        }
        $this->set('recommand', $recommand);
        if (isset($this->params['url']['front']) && $this->params['url']['front'] != '-1') {
            $condition['Article.front'] = $this->params['url']['front'];
            $front = $this->params['url']['front'];
        }
        $this->set('front', $front);
        if (isset($this->params['url']['importance']) && $this->params['url']['importance'] != '-1') {
            $condition['Article.importance'] = $this->params['url']['importance'];
        }

        $this->CategoryArticle->set_locale($this->backend_locale);
        $article_cat = $this->CategoryArticle->find('all');
        $article_cat_new = array();
        foreach ($article_cat as $k => $v) {
            $article_cat_new[$v['CategoryArticle']['id']] = $v;
        }
        $article_cat = $article_cat_new;
        $this->Article->set_locale($this->backend_locale);
        $total = $this->Article->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Article');
        $this->Pagination->init($condition, $parameters, $options);
        $article = $this->Article->find('all', array('conditions' => $condition, 'order' => 'Article.orderby asc,Article.created desc', 'limit' => $rownum, 'page' => $page));
        if (@isset($article)) {
            foreach ($article as $k => $v) {
                $article[$k]['Article']['category'] = !empty($article_cat[$v['Article']['category_id']]['CategoryArticleI18n']['name']) ? $article_cat[$v['Article']['category_id']]['CategoryArticleI18n']['name'] : $this->ld['select_categories'];
            }
        }
        $categories_tree = $this->CategoryArticle->tree('all', $this->backend_locale);
        $this->set('categories_tree', $categories_tree);

        $this->set('article_cat', $article_cat);
        $this->set('article', $article);
        $this->set('start_date', $start_date);//开始时间
        $this->set('end_date', $end_date);//结束时间
        if (!empty($this->params['url'])) {
            $url = $this->params['url']['url'].'?';
            foreach ($this->params['url'] as $k => $v) {
                if ($k == 'url') {
                } else {
                    $url .= $k.'='.$v.'&';
                }
            }
        }
        $Resource_info = $this->Resource->getformatcode(array('sub_type'), $this->backend_locale, false);//资源库信息
        $this->set('Resource_info', $Resource_info);
        $_SESSION['index_url'] = $url;
    //	pr($_SESSION['index_url']);
        $this->set('title_for_layout', $this->ld['manage_articles'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    /**
     *文章分类编辑/新增.
     *
     *@param int $id 输入文章ID新增时不传
     */
    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('articles_add');
        } else {
            $this->operator_privilege('articles_edit');
            //查找映射路径的内容
            $conditions = array('Route.controller' => 'articles','Route.action' => 'view','Route.model_id' => $id);
            $content = $this->Route->find('first', array('conditions' => $conditions));
            //pr($content);die;
            $this->set('routecontent', $content);
        }
        $this->menu_path = array('root' => '/cms/','sub' => '/articles/');
        if (!empty($this->data['Route'])) {
            //判断添加的内容是否为空
            $conditions = array('Route.controller' => 'articles','Route.action' => 'view','Route.model_id' => $id);
            $routeurl = $this->Route->find('first', array('conditions' => $conditions));
            $condit = array('Route.url' => $this->data['Route']['url']);//用来判断添加的url不能重复
            $rurl = $this->Route->find('first', array('conditions' => $condit));
            if (empty($rurl)) {
                //判断里面是否添加相同的数据
                if (empty($id)) {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        //foreach($this->data[])
                        $this->data['Route']['controller'] = 'articles';
                        $this->data['Route']['url'] = $this->data['Route']['url'];
                        $this->data['Route']['action'] = 'view';
                        $this->data['Route']['model_id'] = $id;
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                } else {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        //foreach($this->data[])
                        $this->data['Route']['controller'] = 'articles';
                        $this->data['Route']['url'] = $this->data['Route']['url'];
                        $this->data['Route']['action'] = 'view';
                        $this->data['Route']['model_id'] = $id;
                        $this->data['Route']['id'] = $routeurl['Route']['id'];
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                }
            }
        }

        $this->set('title_for_layout', $this->ld['edit'].$this->ld['article'].' - '.$this->ld['manage_articles'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_articles'],'url' => '/articles/');
        $rank_list = $this->UserRank->find('all', array('conditions' => array('UserRankI18n.locale' => $this->backend_locale)));
        $this->set('rank_list', $rank_list);
        if ($this->RequestHandler->isPost()) {
            if (isset($_REQUEST['video_competence'])) {
                $this->data['Article']['video_competence'] = $_REQUEST['video_competence'];
            }
            $_SESSION['article_relation_product'] = isset($_SESSION['article_relation_product']) ? $_SESSION['article_relation_product'] : array();
            $this->data['Article']['orderby'] = $this->data['Article']['orderby'] == '' ? 50 : $this->data['Article']['orderby'];
            //视频上传缩放成功
            $upload_video_status = true;
            //判断是否存在视频上传
            if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                if ($_FILES['upload_video']['error'] > 0) {
                    switch ($_FILES['upload_video']['error']) {
                        case '1':
                            $video_meg = '视频大小超出了服务器的空间大小!';
                            break;
                        case '2':
                            $video_meg = '要上传的视频大小超出浏览器限制!';
                            break;
                        case '3':
                            $video_meg = '视频仅部分被上传!';
                            break;
                        case '4':
                            $video_meg = '没有找到要上传的视频!';
                            break;
                        default:
                            $video_meg = '视频上传失败!';
                            break;
                    }
                    $upload_video_status = false;
                } else {
                    $exname = strtolower(substr($_FILES['upload_video']['name'], (strrpos($_FILES['upload_video']['name'], '.') + 1)));//视频后缀名
                    if ($exname == 'mp4' || $exname == 'MP4') {
                        $file_dir = WWW_ROOT.'/media/video/';//视频文件目录
                        $this->mkdirs($dir_root);
                        $video_filename = md5(substr($_FILES['upload_video']['name'], 0, strripos($_FILES['upload_video']['name'], '.')).time());//视频的文件名
                        if (move_uploaded_file($_FILES['upload_video']['tmp_name'], $file_dir.$video_filename.'.'.$exname)) {
                            //判断该记录是否已上传过视频
                            if (isset($this->data['Article']['upload_video']) && $this->data['Article']['upload_video'] != '') {
                                if (file_exists(WWW_ROOT.$this->data['Article']['upload_video'])) {
                                    unlink(WWW_ROOT.$this->data['Article']['upload_video']);//删除旧视频
                                }
                            }
                            $this->data['Article']['upload_video'] = '/media/video/'.$video_filename.'.'.$exname;//设置保存数据库路径
                        } else {
                            $video_meg = '视频上传失败!';
                            $upload_video_status = false;
                        }
                    } else {
                        $video_meg = '视频格式暂不支持!';
                        $upload_video_status = false;
                    }
                }
            }
            //文件上传
            $upload_file_status = true;
            //判断是否存在视频上传
            if (isset($_FILES['data_file']) && !empty($_FILES['data_file']['name'])) {
                if ($_FILES['data_file']['error'] > 0) {
                    switch ($_FILES['data_file']['error']) {
                        case '1':
                            $video_meg = '文件大小超出了服务器的空间大小!';
                            break;
                        case '2':
                            $video_meg = '要上传的文件大小超出浏览器限制!';
                            break;
                        case '3':
                            $video_meg = '文件仅部分被上传!';
                            break;
                        case '4':
                            $video_meg = '没有找到要上传的文件!';
                            break;
                        default:
                            $video_meg = '文件上传失败!';
                            break;
                    }
                    $upload_file_status = false;
                } else {
                    $data_file_exname = strtolower(substr($_FILES['data_file']['name'], (strrpos($_FILES['data_file']['name'], '.') + 1)));//视频后缀名
                    if ($data_file_exname == 'pdf' || $data_file_exname == 'doc' || $data_file_exname == 'docx' || $data_file_exname == 'xls' || $data_file_exname == 'xlsx' || $data_file_exname == 'PDF' || $data_file_exname == 'DOC' || $data_file_exname == 'DOCX' || $data_file_exname == 'XLSX') {
                        //文件目录
                        $data_file_dir = WWW_ROOT.'/media/files/';
                        $this->mkdirs($data_file_dir);
                        @chmod($data_file_dir, 0777);
                        $data_filename = md5(substr($_FILES['data_file']['name'], 0, strripos($_FILES['data_file']['name'], '.')).time());//文件名
                        if (move_uploaded_file($_FILES['data_file']['tmp_name'], $data_file_dir.$data_filename.'.'.$data_file_exname)) {
                            //判断该记录是否已上传过视频
                            if (isset($this->data['Article']['file']) && $this->data['Article']['file'] != '') {
                                if (file_exists(WWW_ROOT.$this->data['Article']['file'])) {
                                    unlink(WWW_ROOT.$this->data['Article']['file']);//删除旧文件
                                }
                            }
                            $this->data['Article']['file'] = '/media/files/'.$data_filename.'.'.$data_file_exname;//设置保存路径
                        } else {
                            $video_meg = '文件上传失败!';
                            $upload_file_status = false;
                        }
                    } else {
                        $video_meg = '文件格式暂不支持!';
                        $upload_file_status = false;
                    }
                }
            }
            if ($upload_video_status && $upload_file_status) {
                if ($this->data['Article']['id'] == '') {
                    $this->Article->saveAll(array('Article' => $this->data['Article'])); //保存主表文章信息
                    $id = $this->Article->getLastInsertId();
                } else {
                    $this->Article->save(array('Article' => $this->data['Article'])); //保存主表文章信息
                    $id = $this->data['Article']['id'];
                }

                $this->Article->saveAll(array('id' => $id, 'operator_id' => $this->admin['id']));
                $this->ArticleI18n->deleteAll(array('article_id' => $id));

                foreach ($this->data['ArticleI18n'] as $v) {
                    //				 if(!stristr($v["img01"],IMG_HOST)&&!empty($v["img01"])){//$this->img_server_host
    //				 	 $v["img01"]=IMG_HOST.$v["img01"];//下载远程图片保存到本地 @表示上传文件
    //				 }
                    $v['article_id'] = $id;
                    $this->ArticleI18n->saveAll(array('ArticleI18n' => $v));
                }
                $this->ArticleCategory->deleteAll(array('article_id' => $id));//先删除扩展分类
                $article_categories_id_arr = !empty($_REQUEST['article_categories_id']) ? $_REQUEST['article_categories_id'] : array();//获取提交过来的扩展分类
                foreach ($article_categories_id_arr as $k => $v) {
                    if (!empty($v)) {
                        //去掉为空的扩展分类
                        $ArticleCategorie = array(
                            'article_id' => $id,
                            'category_id' => $v,
                        );
                        $this->ArticleCategory->saveAll(array('ArticleCategory' => $ArticleCategorie));//保存扩展分类
                    }
                }

                //先删除所有关联
                $this->ProductArticle->deleteAll(array('article_id' => 0));
                $this->ProductArticle->deleteAll(array('article_id' => $id));
                //保存关联
                foreach ($_SESSION['article_relation_product'] as $k => $v) {
                    $article_relation_product_array_format = array(
                        'article_id' => $id,
                        'product_id' => $v['ProductArticle']['product_id'],
                        'is_double' => $v['ProductArticle']['is_double'],
                        'orderby' => $v['ProductArticle']['orderby'],
                    );
                    $this->ProductArticle->saveAll(array('ProductArticle' => $article_relation_product_array_format));
                }
                foreach ($this->data['ArticleI18n']as $k => $v) {
                    if ($v['locale'] == $this->backend_locale) {
                        $userinformation_name = $v['title'];
                    }
                }

                //文章相册的处理
                if (isset($_POST['data']['ArticleGallery']) && !empty($_POST['data']['ArticleGallery'])) {
                    //删除以前的
                    $ag_ids = $this->ArticleGallery->find('list', array('conditions' => array('ArticleGallery.article_id' => $id), 'fields' => 'ArticleGallery.id'));
                    $this->ArticleGalleryI18n->deleteAll(array('ArticleGalleryI18n.article_gallery_id' => $ag_ids));
                    $this->ArticleGallery->deleteAll(array('ArticleGallery.article_id' => $id));
                    //保存现在的
                    foreach ($_POST['data']['ArticleGallery'] as $ak => $ag) {
                        $ag['id'] = '';
                        $ag['article_id'] = $id;
                        $this->ArticleGallery->save($ag);
                        $ag_id = $this->ArticleGallery->id;
                        if (isset($_POST['data']['ArticleGalleryI18n'][$ak]) && sizeof($_POST['data']['ArticleGalleryI18n'][$ak]) > 0) {
                            $agi_arr = array();
                            foreach ($_POST['data']['ArticleGalleryI18n'][$ak] as $agk => $agi) {
                                $agi['id'] = '';
                                $agi['locale'] = $_POST['data']['GalleryI18n'][$agk]['locale'];
                                $agi['article_gallery_id'] = $ag_id;
                                $agi_arr[] = $agi;
                            }
                            $this->ArticleGalleryI18n->saveAll($agi_arr);
                        }
                    }
                }
                //标签
                if (isset($this->data['TagI18n']) && !empty($this->data['TagI18n'])) {
                    $old_tag_ids = $this->Tag->find('list', array('conditions' => array('Tag.type_id' => $id, 'Tag.type' => 'A'), 'fields' => 'Tag.id'));
                    $this->TagI18n->deleteAll(array('tag_id' => $old_tag_ids));
                    $this->Tag->deleteAll(array('Tag.type_id' => $id, 'Tag.type' => 'A'));
                    foreach ($this->data['TagI18n'] as $tk => $t) {
                        if (isset($t['name']) && sizeof($t['name']) > 0) {
                            $product_tagi18n = array();
                            foreach ($t['name']  as $tnk => $tn) {
                                $product_tag = array();
                                $product_tag['id'] = '';
                                $product_tag['type_id'] = $id;
                                $product_tag['type'] = 'A';
                                $this->Tag->save($product_tag);
                                $tag_id = $this->Tag->getLastInsertId();
                                $product_tagi18n[$tnk]['id'] = '';
                                $product_tagi18n[$tnk]['tag_id'] = $tag_id;
                                $product_tagi18n[$tnk]['name'] = $tn;
                                $product_tagi18n[$tnk]['locale'] = $t['locale'];
                            }
                            $this->TagI18n->saveAll($product_tagi18n);
                        }
                    }
                }
                //操作员日志
                if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].' '.$this->ld['article'].':id '.$id.$userinformation_name, $this->admin['id']);
                }

                $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
//				if(isset($this->data["Article"]["category_id"])&&!empty($this->data["Article"]["category_id"])){
//					if(isset($_SESSION['index_url'])){
//						$this->redirect('/'.$_SESSION['index_url']);
//					}else{
//						$this->redirect('/articles/?article_cat='.$this->data["Article"]["category_id"]);
//					}
//				}else{
//					if(isset($_SESSION['index_url'])){
//						$this->redirect('/'.$_SESSION['index_url']);
//					}else{
//						$this->redirect('/articles/');
//					}
//				}	
            } else {
                die($video_meg);
            }
        }
        //if(in_array('APP-TAG',$this->apps['codes'])){
            $tag_infos = $this->Tag->localeformat($id, 'A');
        $this->set('tag_infos', $tag_infos);
        //}
        $categories_tree_A = $this->CategoryArticle->tree('all', $this->backend_locale);//文章分类树
        $category_tree = $this->CategoryProduct->tree('P', $this->backend_locale);//商品分类树
        $brand_tree = $this->Brand->brand_tree($this->backend_locale);//品牌获取


        $article_relation_products = $this->ProductArticle->get_article_relation_products($id);//获取文章关联商品信息

        $article_relation_products_format = array();
        foreach ($article_relation_products as $k => $v) {
            $article_relation_products_format[$v['ProductArticle']['product_id']] = $v;
        }
        $_SESSION['article_relation_product'] = $article_relation_products_format;

        $article = $this->Article->localeformat($id);
        //取文章相册信息
        //$article_galleries = $this->ArticleGallery->find('all',array('conditions'=>array('ArticleGallery.article_id'=>$id)));
        $article_galleries = $this->ArticleGallery->localeformat($id);
        $this->set('article_galleries', $article_galleries);
        if (isset($article['ArticleI18n'][$this->backend_locale]['title'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$article['ArticleI18n'][$this->backend_locale]['title'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_article'],'url' => '');
        }

        $category_arr = $this->ArticleCategory->find('all', array('conditions' => array('article_id' => $id)));//扩展分类
        $this->set('categories_tree_A', $categories_tree_A);
        $this->set('category_tree', $category_tree);//商品分类树
        $this->set('brand_tree', $brand_tree);//品牌获取
        $this->set('category_arr', $category_arr);
        $this->set('article', $article);//文章基本信息

        $this->set('article_relation_products', $article_relation_products_format);//文章关联商品信息

        $this->set('id', $id);
        if (isset($article['Article']) && $article['Article']['category_id'] != 0) {
            $this->set('category_id', $article['Article']['category_id']);
        } elseif (isset($_GET['article_cat']) && is_numeric($_GET['article_cat'])) {
            $this->set('category_id', $_GET['article_cat']);
        } else {
            $this->set('category_id', 0);
        }

        $this->set('brand_id', 0);

        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('sub_type'), $this->backend_locale, false);//资源库信息
        $this->set('Resource_info', $Resource_info); 
        $uploadfiles = $this->Document->find('list', array('order' => 'Document.orderby asc', 'fields' => 'Document.name'));
        $this->set('uploadfiles', $uploadfiles);
    }

    /**
     *文章批量处理.
     */
    public function batch()
    {
        $art_ids = !empty($_REQUEST['checkbox']) ? $_REQUEST['checkbox'] : 0;
        if (isset($this->params['url']['act_type']) && $this->params['url']['act_type'] != '0') {
            if ($this->params['url']['act_type'] == 'delete') {
                $condition['Article.id'] = $art_ids;
                $this->Article->deleteAll($condition);
                $this->ArticleI18n->deleteAll(array('article_id' => $art_ids));
                $this->ArticleCategory->deleteAll(array('ArticleCategory.article_id' => $art_ids));
                $this->ArticleGallery->deleteAll(array('ArticleGallery.article_id' => $art_ids));
                //操作员日志
                if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
                }
                $this->redirect('/articles/');
            }
            if ($this->params['url']['act_type'] == 'sub_type') {
                $condition['Article.id'] = $art_ids;
                $this->Article->updateAll(array('Article.type' => "'".$_REQUEST['sub_type']."'"), array('Article.id' => $art_ids));
                if ($this->configs['operactions-log'] == 1) {
                    $this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_transfer_article_type'], 'operation');
                }
                $this->redirect('/articles/');
            }
            if ($this->params['url']['act_type'] == 'a_category') {
                $condition['Article.id'] = $art_ids;
                $this->Article->updateAll(array('Article.category_id' => $_REQUEST['article_cat_o']), array('Article.id' => $art_ids));
                if ($this->configs['operactions-log'] == 1) {
                    $this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_transfer_article_category'], 'operation');
                }
                $this->redirect('/articles/');
            }

            if ($this->params['url']['act_type'] == 'a_status') {
                $condition['Article.id'] = $art_ids;
                $this->Article->updateAll(array('Article.status' => $_REQUEST['is_yes_no']), array('Article.id' => $art_ids));
                if ($this->configs['operactions-log'] == 1) {
                    $this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], 'operation');
                }
                $this->redirect('/articles/');
            }
            if ($this->params['url']['act_type'] == 'a_f_status') {
                $condition['Article.id'] = $art_ids;
                $this->Article->updateAll(array('Article.front' => $_REQUEST['is_yes_no']), array('Article.id' => $art_ids));
                if ($this->configs['operactions-log'] == 1) {
                    $this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_home_show'], 'operation');
                }
                $this->redirect('/articles/');
            }
            if ($this->params['url']['act_type'] == 'a_c_status') {
                $condition['Article.id'] = $art_ids;
                $this->Article->updateAll(array('Article.recommand' => $_REQUEST['is_yes_no']), array('Article.id' => $art_ids));
                if ($this->configs['operactions-log'] == 1) {
                    $this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_recommended'], 'operation');
                }
                $this->redirect('/articles/');
            }
            if ($this->params['url']['act_type'] == 'update_article') {
                $this->clear_cache_files(array('Seevia'));
                $condition['Article.id'] = $art_ids;
                foreach ($art_ids as $k => $v) {
                    $this->delDirAndFile('../articles/'.$v);
                    foreach ($this->languages as $kk => $vv) {
                        file_get_contents($this->server_host.'/articles/'.$v.'/'.$vv['Language']['locale']);
                    }
                }
                $this->redirect('/articles/');
            }
        } else {
            $this->redirect('/articles/');
        }
    }

    /**
     *编辑页 关联商品 添加.
     */
    public function add_article_relation_product()
    {
        //设置返回初始参数
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['unknown_reasons'];
        $article_id = $_REQUEST['article_id'];
        $product_select = $_REQUEST['product_select'];
        $is_single_value = $_REQUEST['is_single_value'];
        //$_SESSION["article_relation_product"] = array();
        $this->ProductArticle->deleteAll(array('article_id' => $article_id, 'product_id' => $product_select));

        $linkproduct_info = array('ProductArticle' => array('article_id' => $article_id,'id' => $product_select,'product_id' => $product_select,'is_double' => $is_single_value,'orderby' => '50'));
        $this->Product->set_locale($this->backend_locale);
        $product_info = $this->Product->find('first', array('conditions' => array('Product.id' => $linkproduct_info['ProductArticle']['product_id']), 'fields' => array('Product.id', 'Product.code', 'ProductI18n.name')));

        $linkproduct_info['ProductArticle']['name'] = $product_info['Product']['code'].'--'.$product_info['ProductI18n']['name'].'--['.($linkproduct_info['ProductArticle']['is_double'] == 1 ? $this->ld['each_other_relation'] : $this->ld['unidirectional']).']';

        $_SESSION['article_relation_product'][$product_select] = $linkproduct_info;

        $result['flag'] = 1;//2 失败 1成功
        $content_array = array();
        foreach ($_SESSION['article_relation_product'] as $k => $v) {
            $content_array[] = $v;
        }
        $result['content'] = $content_array;

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *删除文章关联商品.
     */
    public function drop_article_relation_product($id, $article_id)
    {
        $result['flag'] = 1;//2 失败 1成功

        $result['content'] = $this->ld['deleted_success'];
        unset($_SESSION['article_relation_product'][$id]);
        $content_array = array();
        foreach ($_SESSION['article_relation_product'] as $k => $v) {
            $content_array[] = $v;
        }
        $result['content'] = $content_array;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *文章关联商品排序修改.
     */
    public function article_relation_product_orderby()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->ProductArticle->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *文章列表修改有效.
     */
    public function toggle_on_status()
    {
        $this->Article->hasMany = array();
        $this->Article->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Article->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], 'operation');
            }
        }

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *文章列表修改首页.
     */
    public function toggle_on_front()
    {
        $this->Article->hasMany = array();
        $this->Article->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Article->save(array('id' => $id, 'front' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_home_show'], 'operation');
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *文章列表修改推荐.
     */
    public function toggle_on_recommand()
    {
        $this->Article->hasMany = array();
        $this->Article->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Article->save(array('id' => $id, 'recommand' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_recommended'], 'operation');
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *文章列表排序修改.
     */
    public function update_article_orderby()
    {
        $this->Article->hasMany = array();
        $this->Article->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->Article->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *删除文章.
     *
     *@param int $id 输入文章ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_route_failure'];
        $this->Article->hasMany = array();
        $this->Article->hasOne = array();
        $pn = $this->ArticleI18n->find('list', array('fields' => array('ArticleI18n.article_id', 'ArticleI18n.title'), 'conditions' => array('ArticleI18n.article_id' => $id, 'ArticleI18n.locale' => $this->backend_locale)));
        $this->Article->deleteAll(array('Article.id' => $id));
        $this->ArticleI18n->deleteAll(array('article_id' => $id));
        $this->ArticleCategory->deleteAll(array('ArticleCategory.article_id' => $id));
        $this->ArticleGallery->deleteAll(array('ArticleGallery.article_id' => $id));
        $this->TopicArticle->deleteAll(array('TopicArticle.article_id' => $id));
        //操作员日志
        /*if($this->configs['operactions-log']== 1){
    	$this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_article'].' '.$pn[$id],'operation');
    	}*/
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_article'].' '.$pn[$id].' id:'.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_article_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表文章标题修改.
     */
    public function update_article_title()
    {
        $this->Article->hasMany = array();
        $this->Article->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->ArticleI18n->updateAll(
            array('title' => "'".$val."'"),
            array('article_id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //创建路径
    public function mkdirs($path, $mode = 0777)
    {
        $dirs = explode('/', $path);
        $pos = strrpos($path, '.');
        if ($pos === false) {
            $subamount = 0;
        } else {
            $subamount = 1;
        }

        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
            $thispath = '';
            for ($cc = 0; $cc <= $c; ++$cc) {
                $thispath .= $dirs[$cc].'/';
            }
            if (!file_exists($thispath)) {
                mkdir($thispath, $mode);
                chmod($thispath, $mode);
            } else {
                @chmod($thispath, $mode);
            }
        }
    }

    public function uploadarticles()
    {
        $this->operator_privilege('articles_upload');
        $this->menu_path = array('root' => '/cms/','sub' => '/articles/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_articles'],'url' => '/articles/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $flag_code = 'articles_export';
        $categories_tree = $this->CategoryArticle->tree('all', $this->locale);
        $this->set('categories_tree', $categories_tree);
        $this->Profile->set_locale($this->locale);
        $profilefiled_codes = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
        if (isset($profilefiled_codes) && !empty($profilefiled_codes)) {
            $this->set('profilefiled_codes', $profilefiled_codes);
        }
    }

    public function uploadarticlespreview()
    {
        $this->operator_privilege('articles_upload');
        if ($this->RequestHandler->isPost()) {
            $this->menu_path = array('root' => '/cms/','sub' => '/articles/');
            $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['manage_articles'],'url' => '/articles/');
            $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
            $this->set('title_for_layout', $this->ld['preview'].' - '.$this->configs['shop_name']);
            $flag_code = 'articles_export';
            $this->Profile->set_locale($this->locale);
            set_time_limit(300);
            if (!empty($_FILES['file'])) {
                if ($_FILES['file']['error'] > 0) {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/articles/uploadarticles';</script>";
                    die();
                } elseif (empty($_POST['category_id'])) {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['select_article_category']."');window.location.href='/admin/articles/uploadarticles'</script>";
                    die();
                } else {
                    $this->set('category_id', $_POST['category_id']);
                    $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                    $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
                    $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
                    if (empty($profilefiled_info)) {
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/articles/uploadarticles';</script>";
                        die();
                    }
                    $key_arr = array();
                    $key_desc=array();
                    $key_code=array();
                    foreach ($profilefiled_info as $k => $v) {
				$fields_k=array();
				$fields_k = explode('.', $v['ProfileFiled']['code']);
				$key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
				$key_desc[]= $v['ProfilesFieldI18n']['description'];
				$key_code[$v['ProfilesFieldI18n']['description']]=isset($fields_k[1]) ? $fields_k[1] : '';
                    }
                    $this->set('key_code',$key_code);
                    $preview_key=array();
                    $csv_export_code = 'gb2312';
                    $i = 0;
                    while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                        if ($i == 0) {
                        		foreach ($row as $k => $v) {
						$preview_key[]=iconv('GB2312', 'UTF-8', $v);
	                                if (isset($key_arr[$k]) && !empty($key_arr[$k])) {
	                                    continue;
	                                } 
	                            }
                            $check_row = $row[0];
                            $row_count = count($row);
                            $check_row = iconv('GB2312', 'UTF-8', $check_row);
                            $num_count = count($profilefiled_info);
                            if ($row_count > $num_count || $check_row != $profilefiled_info[0]['ProfilesFieldI18n']['description']) {
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('上传文件格式不标准');window.location.href='/admin/articles/uploadarticles';</script>";
                            }
                            ++$i;
                        }
                        $temp = array();
                        foreach ($row as $k => $v) {
                            $data_key_code=isset($key_code[$preview_key[$k]])?$key_code[$preview_key[$k]]:'';
                            $temp[$preview_key[$k]] = ($v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v));
                            if (isset($key_arr[$k]) && !empty($key_arr[$k])) {
                               	$temp[$data_key_code] = ($v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v));
                            }
                        }
                        if (!isset($temp) || empty($temp)) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/articles/uploadarticles';</script>";
                            die();
                        }
                        $data[] = $temp;
                    }
                    fclose($handle);
                    $this->set('profilefiled_info', $profilefiled_info);
                    $this->set('uploads_list', $data);
                }
            }
        } else {
            $this->redirect('/articles/');
        }
    }

    public function batch_add_articles()
    {
        if ($this->RequestHandler->isPost()) {
            $category_id = $_POST['category_id'];
            $checkbox_arr = $_REQUEST['checkbox'];
            $this->Article->hasOne = array();
            $this->Article->hasMany = array();
            foreach ($this->data as $key => $data) {
                if (!in_array($key, $checkbox_arr)) {
                    continue;
                }
                $ArticleI18n['title'] = $data['title'];
                $ArticleI18n['content'] = $data['content'];
                $ArticleI18n['meta_keywords'] = $data['meta_keywords'];
                $ArticleI18n['meta_description'] = $data['meta_description'];
                $ArticleI18n['author'] = $data['author'];
                $Article = empty($article_info['Article']) ? array() : $article_info['Article'];
                $Article['category_id'] = $category_id;
                $Article['type'] = empty($data['type']) ? G : $data['type'];
                $Article['importance'] = empty($data['importance']) ? 1 : $data['importance'];
                $Article['status'] = empty($data['status']) ? 1 : $data['status'];
                $Article['front'] = empty($data['front']) ? 1 : $data['front'];
                $Article['comment'] = empty($data['comment']) ? 1 : $data['comment'];
                $Article['recommand'] = empty($data['recommand']) ? 1 : $data['recommand'];
                if (empty($Article['id'])) {
                    $max_article = $this->Article->find('', '', 'Article.id DESC');
                    $max_id = $max_article['Article']['id'] + 1;
                }
                if (empty($Article['orderby'])) {
                    $Article['orderby'] = 50;
                }
                if (empty($Article['id'])) {
                    $this->Article->saveAll(array('Article' => $Article));
                    $id = $this->Article->id;
                } else {
                    $this->Article->save(array('Article' => $Article));
                    $id = $Article['id'];
                }
                $Article['id'] = $id;
                if (!empty($Article['id'])) {
                    $ArticleI18n['article_id'] = $id;
                    if (is_array($this->backend_locales)) {
                        foreach ($this->backend_locales as $k => $v) {
                            $ArticleI18n = $this->ArticleI18n->find('first', array('conditions' => array('locale' => $v['Language']['locale'], 'article_id' => $id)));
                            $ArticleI18n = $ArticleI18n['ArticleI18n'];
                            $ArticleI18n['title'] = $data['title'];
                            $ArticleI18n['content'] = $data['content'];
                            $ArticleI18n['meta_keywords'] = $data['meta_keywords'];
                            $ArticleI18n['meta_description'] = $data['meta_description'];
                            $ArticleI18n['author'] = $data['author'];
                            $ArticleI18n['article_id'] = $id;
                            $ArticleI18n['locale'] = $v['Language']['locale'];
                            $ArticleI18n['img01'] = $data['img01'];
                            $ArticleI18n['img02'] = $data['img02'];
                            $ArticleI18n['file_url'] = $data['file_url'];
                            if (!empty($ArticleI18n['id'])) {
                                $this->ArticleI18n->save($ArticleI18n);
                            } else {
                                $this->ArticleI18n->saveAll($ArticleI18n);
                            }
                        }
                    }
                }
            }
               //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['bulk_upload_article'], $this->admin['id']);
            }
        }
        $this->redirect('/articles/');
    }

    public function download_csv_example()
    {
        $this->Profile->set_locale($this->locale);
        $this->Profile->hasOne = array();
        $flag_code = 'articles_export';
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $tmp = array();
        $fields_array = array();
        $newdatas = array();
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            foreach ($profilefiled_info as $k => $v) {
                $tmp[] = $v['ProfilesFieldI18n']['description'];
                $fields_array[] = $v['ProfileFiled']['code'];
            }
        }
        $newdatas[] = $tmp;
        $filename = '文章导出'.date('Ymd').'.csv';
        $article_all = $this->Article->find('all', array('fields' => $fields_array, 'conditions' => array('Article.status' => 1, 'ArticleI18n.locale' => $this->locale), 'limit' => 10));
        foreach ($article_all as $k => $v) {
            $user_tmp = array();
            foreach ($fields_array as $kk => $vv) {
                $fields_kk = explode('.', $vv);
                $user_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
            }
            $newdatas[] = $user_tmp;
        }
        $this->Phpcsv->output($filename, $newdatas);
        exit;
    }

    public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"')
    {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = '';
        $eof = false;
        while ($eof != true) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) {
                $eof = true;
            }
        }
        $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for ($_csv_i = 0; $_csv_i < count($_csv_data); ++$_csv_i) {
            $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
            $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }

        return empty($_line) ? false : $_csv_data;
    }
}
