<?php

/**
 *这是一个名为 ArticleCategoriesController 的控制器
 *文章分类控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class ArticleCategoriesController extends AppController
{
    public $name = 'ArticleCategories';
    public $helpers = array('Html','Javascript','Ckeditor');
    public $uses = array('ArticleCategory','Resource','Article','Route','OperatorLog','CategoryArticle','CategoryArticleI18n');
    public $components = array('RequestHandler');
    /**
     *显示文章分类列表.
     */
    public function index()
    {
        $this->operator_privilege('article_categories_view');
        $this->menu_path = array('root' => '/cms/','sub' => '/articles/');
        $this->set('title_for_layout', $this->ld['manage_articles_categories'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_articles'],'url' => '/articles/');
        $this->navigations[] = array('name' => $this->ld['manage_articles_categories'],'url' => '');
        //获取文章分类结构树
        $categories_trees = $this->CategoryArticle->tree('all', $this->backend_locale);
        $article_count = $this->Article->article_counts();//获取分类下的文章个数
        $this->set('categories_trees', $categories_trees);
        $this->set('article_count', $article_count);
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('sub_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
    }

    /**
     *文章分类编辑/新增.
     *
     *@param int $id 输入文章分类ID新增时不传
     */
    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('article_categories_add');
        } else {
            $this->operator_privilege('article_categories_edit');
            //查找映射路径的内容
            $conditions = array('Route.controller' => 'articles','Route.action' => 'category','Route.model_id' => $id);
            $content = $this->Route->find('first', array('conditions' => $conditions));
            $this->set('routecontent', $content);
        }
        $this->menu_path = array('root' => '/cms/','sub' => '/articles/');
        if (!empty($this->data['Route'])) {
            //判断添加的内容是否为空
            $conditions = array('Route.controller' => 'articles','Route.action' => 'category','Route.model_id' => $id);
            $routeurl = $this->Route->find('first', array('conditions' => $conditions));
            $condit = array('Route.url' => $this->data['Route']['url']);//用来判断添加的url不能重复
            $rurl = $this->Route->find('first', array('conditions' => $condit));
            if (empty($rurl)) {
                //判断里面是否添加相同的数据
                if (empty($id)) {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        $this->data['Route']['controller'] = 'articles';
                        $this->data['Route']['url'] = $this->data['Route']['url'];
                        $this->data['Route']['action'] = 'category';
                        $this->data['Route']['model_id'] = $id;
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                } else {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        $this->data['Route']['controller'] = 'articles';
                        $this->data['Route']['url'] = $this->data['Route']['url'];
                        $this->data['Route']['action'] = 'category';
                        $this->data['Route']['model_id'] = $id;
                        $this->data['Route']['id'] = $routeurl['Route']['id'];
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                }
            }
        }
        $this->set('title_for_layout', $this->ld['add'].'/'.$this->ld['edit'].$this->ld['article_categories'].' - '.$this->ld['manager_categories'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_articles_categories'],'url' => '/article_categories/');
        if ($this->RequestHandler->isPost()) {
            $this->data['CategoryArticle']['orderby'] = isset($this->data['CategoryArticle']['orderby']) && $this->data['CategoryArticle']['orderby'] != '' ? $this->data['CategoryArticle']['orderby'] : '50';
            if (isset($this->data['CategoryArticle']['id']) && $this->data['CategoryArticle']['id'] != '') {
                $this->CategoryArticle->save(array('CategoryArticle' => $this->data['CategoryArticle'])); //保存主表信息
                $id = $this->data['CategoryArticle']['id'];
            } else {
                $this->CategoryArticle->saveAll(array('CategoryArticle' => $this->data['CategoryArticle'])); //保存主表信息
                $id = $this->CategoryArticle->getLastInsertId();
            }
            $this->CategoryArticleI18n->deleteAll(array('category_id' => $id)); //删除原有多语言
            foreach ($this->data['CategoryArticleI18n'] as $v) {
                $categoryI18n_info = array(
                    'locale' => $v['locale'],
                    'category_id' => $id,
                    'name' => isset($v['name']) ? $v['name'] : '',
                      'meta_keywords' => isset($v['meta_keywords']) ? $v['meta_keywords'] : '',
                       'meta_description' => isset($v['meta_description']) ? $v['meta_description'] : '',
                    'detail' => $v['detail'],
                  );
                $this->CategoryArticleI18n->saveAll(array('CategoryArticleI18n' => $categoryI18n_info));//更新多语言信息
            }
            foreach ($this->data['CategoryArticleI18n']as $k => $v) {
                if ($v['locale'] == $this->backend_locale) {
                    $userinformation_name = $v['name'];
                }
            }
              //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].' '.$this->ld['article_categories'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/article_categories/');
        }
        $this->data = $this->CategoryArticle->localeformat($id);//分章分类信息
        if (isset($this->data['CategoryArticleI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$this->data['CategoryArticleI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_article_categories'],'url' => '');
        }
        $categories_tree = $this->CategoryArticle->tree('all', $this->backend_locale, $id);//取文章分类树形结构
        $this->set('categories_tree', $categories_tree);
        $Resource_info = $this->Resource->getformatcode(array('sub_type'), $this->backend_locale, false);//资源库信息
        $this->set('Resource_info', $Resource_info);
    }

    /**
     *删除文章分类 前 提一级子分类.
     *
     *@param int $id 输入文章分类ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_article_category_failure'];
        $this->CategoryArticle->hasMany = array();
        $this->CategoryArticle->hasOne = array();
        $pn = $this->CategoryArticleI18n->find('list', array('fields' => array('CategoryArticleI18n.category_id', 'CategoryArticleI18n.name'), 'conditions' => array('CategoryArticleI18n.category_id' => $id, 'CategoryArticleI18n.locale' => $this->backend_locale)));
        $this->CategoryArticle->deleteAll(array('id' => $id));
        $this->CategoryArticleI18n->deleteAll(array('category_id' => $id));
        $category_data = $this->CategoryArticle->find('all', array('conditions' => array('parent_id' => $id)));
        foreach ($category_data as $k => $v) {
            $this->CategoryArticle->save(array('CategoryArticle' => array('id' => $v['CategoryArticle']['id'], 'parent_id' => 0)));
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_article_category'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_article_category_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表状态修改.
     */
    public function toggle_on_status()
    {
        $this->CategoryArticle->hasMany = array();
        $this->CategoryArticle->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->CategoryArticle->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *文章分类批量处理.
     */
    public function batch()
    {
        $art_ids = !empty($_REQUEST['checkbox']) ? $_REQUEST['checkbox'] : 0;
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        if (sizeof($art_ids) > 0) {
            $condition['CategoryArticle.id'] = $art_ids;
            $this->CategoryArticle->deleteAll($condition);
            $this->CategoryArticle->deleteAll(array('CategoryArticle.parent_id' => $art_ids));
            $this->CategoryArticleI18n->deleteAll(array('CategoryArticleI18n.category_id' => $art_ids));
            $this->redirect('/article_categories/');
        } else {
            $this->redirect('/article_categories/');
        }
    }

    public function searchac($id)
    {
        if ($id != 0) {
            $na_one = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $id)));
            $na_info = $this->CategoryArticle->find('all', array('conditions' => array('CategoryArticle.parent_id' => $na_one['CategoryArticle']['id'], 'CategoryArticleI18n.locale' => $this->backend_locale)));
        } else {
            $na_info = $this->CategoryArticle->find('all', array('conditions' => array('CategoryArticle.parent_id' => $id, 'CategoryArticleI18n.locale' => $this->backend_locale)));
        }
        if (isset($na_info) && count($na_info) > 0) {
            $result['na'] = '<label><input  type="radio" name="orderby" value="0"/>'.$this->ld['front'].'</label><label><input type="radio" name="orderby" checked value="1"/>'.$this->ld['final'].'</label>　<label><input type="radio" name="orderby" value="2"/>'.$this->ld['at'].'</label><select id="orderby" name="orderby_sel">';
            foreach ($na_info as $v) {
                $result['na'] .= '<option value="'.$v['CategoryArticle']['id'].'">'.$v['CategoryArticleI18n']['name'].'</option>';
            }
            $result['na'] .= '</select>'.$this->ld['after'];
        } else {
            $result['na'] = $this->ld['no_lower_navigation'];
        }
        $result['message'] = $this->ld['modified_successfully'];
        $result['flag'] = 1;
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
    *列表箭头排序
    */
    public function changeorder($updowm, $id, $nextone)
    {
        //如果值相等重新自动排序
        $a = $this->CategoryArticle->query('SELECT DISTINCT `parent_id` 
			FROM `svcms_category_articles` as CategoryArticle
			GROUP BY `orderby` , `parent_id` 
			HAVING count( * ) >1');
        if (isset($a) && count($a) > 0) {
            foreach ($a as $v) {
                $this->CategoryArticle->Behaviors->attach('Containable');
                $all = $this->CategoryArticle->find('all', array('conditions' => array('CategoryArticle.parent_id' => $v['CategoryArticle']['parent_id']), 'order' => 'CategoryArticle.id asc', 'contain' => false));
                foreach ($all as $k => $vv) {
                    $all[$k]['CategoryArticle']['orderby'] = $k + 1;
                }
                $this->CategoryArticle->saveAll($all);
            }
        }
        if ($nextone == 0) {
            $category_one = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $id)));
            if ($updowm == 'up') {
                $category_change = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.orderby <' => $category_one['CategoryArticle']['orderby'], 'CategoryArticle.parent_id' => 0), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $category_change = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.orderby >' => $category_one['CategoryArticle']['orderby'], 'CategoryArticle.parent_id' => 0), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        if ($nextone == 'next') {
            $category_one = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.id' => $id)));
            if ($updowm == 'up') {
                $category_change = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.orderby <' => $category_one['CategoryArticle']['orderby'], 'CategoryArticle.parent_id' => $category_one['CategoryArticle']['parent_id']), 'order' => 'orderby desc', 'limit' => '1'));
            }
            if ($updowm == 'down') {
                $category_change = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticle.orderby >' => $category_one['CategoryArticle']['orderby'], 'CategoryArticle.parent_id' => $category_one['CategoryArticle']['parent_id']), 'order' => 'orderby asc', 'limit' => '1'));
            }
        }
        $t = $category_one['CategoryArticle']['orderby'];
        $category_one['CategoryArticle']['orderby'] = $category_change['CategoryArticle']['orderby'];
        $category_change['CategoryArticle']['orderby'] = $t;
        if (isset($category_change['CategoryArticle']['status']) && $category_change['CategoryArticle']['type'] != '') {
            $this->CategoryArticle->saveAll($category_one);
            $this->CategoryArticle->saveAll($category_change);
        }

        //获取文章分类结构树
        $categories_trees = $this->CategoryArticle->tree('all', $this->backend_locale);
        $article_count = $this->Article->article_counts();//获取分类下的文章个数
        $this->set('categories_trees', $categories_trees);
        $this->set('article_count', $article_count);
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('sub_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        Configure::write('debug', 1);
        $this->render('index');
        $this->layout = 'ajax';
    }
}
