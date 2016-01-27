<?php

/*****************************************************************************
 * Seevia 专题介绍
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
class TopicsController extends AppController
{
    public $name = 'Topics';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Tinymce','fck','Ckeditor');
    public $uses = array('Topic','TopicI18n','Brand','ProductType','CategoryArticle','Product','TopicProduct','SeoKeyword','Navigation','NavigationI18n','TopicArticle','Article','Route','OperatorLog','CategoryProduct');
    //var $layout="default";
    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('topics_view');
        $this->menu_path = array('root' => '/cms/','sub' => '/topics/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['topics_introduction'],'url' => '');

        $this->Topic->set_locale($this->backend_locale);
        $condition = '';
        $sortClass = 'Topic';
        $total = $this->Topic->find('count', $condition);
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->set('datapage', $page);
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'topics','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Topic');
        $this->Pagination->init($condition, $parameters, $options);
        $data = $this->Topic->find('all', array('conditions' => $condition, 'order' => 'Topic.orderby,Topic.start_time', 'limit' => $rownum, 'page' => $page));
        $this->set('topics', $data);
        $this->set('title_for_layout', $this->ld['topics_introduction'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function view($id = 0)
    {
        $this->menu_path = array('root' => '/cms/','sub' => '/topics/');
        /*判断权限*/
        if (empty($id)) {
            $this->operator_privilege('topics_add');
        } else {
            $this->operator_privilege('topics_edit');
                //查找映射路径的内容
            $conditions = array('Route.controller' => 'topics','Route.action' => 'view','Route.model_id' => $id);
            $content = $this->Route->find('first', array('conditions' => $conditions));
            //pr($content);die;
            $this->set('routecontent', $content);
        }
        /*end*/
        if (!empty($this->data['Route'])) {
            //判断添加的内容是否为空
            $conditions = array('Route.controller' => 'topics','Route.action' => 'view','Route.model_id' => $id);
            $routeurl = $this->Route->find('first', array('conditions' => $conditions));
            $condit = array('Route.url' => $this->data['Route']['url']);//用来判断添加的url不能重复
            $rurl = $this->Route->find('first', array('conditions' => $condit));
            if (empty($rurl)) {
                //判断里面是否添加相同的数据
                if (empty($id)) {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        //foreach($this->data[])
                        $this->data['Route']['controller'] = 'topics';
                        $this->data['Route']['url'] = $this->data['Route']['url'];
                        $this->data['Route']['action'] = 'view';
                        $this->data['Route']['model_id'] = $id;
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                } else {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        //foreach($this->data[])
                        $this->data['Route']['controller'] = 'topics';
                        $this->data['Route']['url'] = $this->data['Route']['url'];
                        $this->data['Route']['action'] = 'view';
                        $this->data['Route']['model_id'] = $id;
                        $this->data['Route']['id'] = $routeurl['Route']['id'];
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                }
            }
        }

        $this->set('title_for_layout', $this->ld['edit_topics'].'-'.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['topics_list'],'url' => '/topics/');
        //$this->navigations[]=array('name'=>'专题编辑','url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['Topic']['front'] = isset($this->data['Topic']['front']) ? $this->data['Topic']['front'] : 0;
            $this->data['Topic']['front_num'] = isset($this->data['Topic']['front_num']) && !empty($this->data['Topic']['front_num']) ? $this->data['Topic']['front_num'] : 0;
            $this->data['Topic']['status'] = isset($this->data['Topic']['status']) ? $this->data['Topic']['status'] : 0;
            $this->data['Topic']['end_time'] = $this->data['Topic']['end_time'].' 23:59:59';
            $_SESSION['topic'] = isset($_SESSION['topic']) ? $_SESSION['topic'] : array();
            //pr($this->data["Topic"]);exit();
            if (isset($this->data['Topic']['id']) && $this->data['Topic']['id'] != '') {
                $this->Topic->save(array('Topic' => $this->data['Topic'])); //关联保存
            } else {
                $this->Topic->saveAll(array('Topic' => $this->data['Topic'])); //关联保存
                $id = $this->Topic->getLastInsertId();
            }
            $this->TopicI18n->deleteall(array('topic_id' => $this->data['Topic']['id'])); //删除原有多语言
            foreach ($this->data['TopicI18n'] as $v) {
                $topicI18n_info = array(
                    'id' => isset($v['id']) ? $v['id'] : '',
                    'locale' => $v['locale'],
                    'topic_id' => isset($v['topic_id']) ? $v['topic_id'] : $id,
                    'title' => isset($v['title']) ? $v['title'] : '',
                    'intro' => isset($v['intro']) ? $v['intro'] : '',
                    'mobile_intro' => isset($v['mobile_intro']) ? $v['mobile_intro'] : '',
                    'img01' => isset($v['img01']) ? $v['img01'] : '',
                    'img02' => isset($v['img02']) ? $v['img02'] : '',
                    'img03' => isset($v['img03']) ? $v['img03'] : '',
                    'meta_keywords' => isset($v['meta_keywords']) ? $v['meta_keywords'] : '',
                    'meta_description' => isset($v['meta_description']) ? $v['meta_description'] : '',
                );
                $this->TopicI18n->saveall(array('TopicI18n' => $topicI18n_info)); //更新多语言
            }
            //先删除所有关联
            $this->TopicProduct->deleteAll(array('topic_id' => 0));
            $this->TopicProduct->deleteAll(array('topic_id' => $id));
            
       
            foreach ($_SESSION['topic'] as $k => $v) {
                $topic_relation_product_array_format = array(
                    'topic_id' => $id,
                    'product_id' => $v['id'],
                    //'product_id' => $v['TopicProduct']['product_id'],
                    //'orderby' => $v['TopicProduct']['orderby'],
                );
                $this->TopicProduct->saveAll(array('TopicProduct' => $topic_relation_product_array_format));
            }

            //先删除所有关联
            $this->TopicArticle->deleteAll(array('topic_id' => 0));
            $this->TopicArticle->deleteAll(array('topic_id' => $id));
             if(isset($_SESSION['topic_relation_article'])){
	            foreach ($_SESSION['topic_relation_article'] as $k => $v) {
	                $topic_relation_article_array_format = array(
	                    'topic_id' => $id,
	                    'article_id' => $v['TopicArticle']['article_id'],
	                    'orderby' => 50,
	                );
	                $this->TopicArticle->saveAll(array('TopicArticle' => $topic_relation_article_array_format));
	            }
             }
            //pr($this->data);die();
           // $this->Topic->save($this->data); //保存
            foreach ($this->data['TopicI18n']as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['title'];
                }
            }
            $id = $this->Topic->id;
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_topics'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }

            $url = '/topics/'.$id;
            //导航设置
            if (isset($this->data['Topic']['id']) && $this->data['Topic']['id'] != '') {
                //查找是否已经有数据
                $p_nav = $this->Navigation->find('first', array('conditions' => array('NavigationI18n.url' => $url)));
                //如果存在的情况下 只改状态   不存在的情况洗就插入
                if (isset($p_nav) && $p_nav != '') {
                    if ($_POST['data1']['Navigation']['type'] != '0') {
                        //位置不为零时
                        $p_nav['Navigation']['type'] = $_POST['data1']['Navigation']['type'];
                        $this->Navigation->saveAll($p_nav);
                        $nav_info = $this->NavigationI18n->find('all', array('conditions' => array('NavigationI18n.url' => $url)));
                        foreach ($_POST['data']['TopicI18n'] as $v) {
                            foreach ($nav_info as $kk => $vv) {
                                if ($vv['NavigationI18n']['locale'] == $v['locale']) {
                                    $nav_info[$kk]['NavigationI18n']['name'] = isset($v['title']) ? $v['title'] : '';
                                }
                            }
                            $this->NavigationI18n->saveAll($nav_info);//更新多语言
                        }
                    } else {
                        //为零时
                        $id = $p_nav['Navigation']['id'];
                        $this->Navigation->deleteAll(array('Navigation.id' => $id));
                        $this->NavigationI18n->deleteAll(array('navigation_id' => $id));
                    }
                } else {
                    if ($_POST['data1']['Navigation']['type'] != '0') {
                        $this->Navigation->saveAll(array('Navigation' => $_POST['data1']['Navigation']));
                        $nid = $this->Navigation->getLastInsertId();
                        foreach ($_POST['data']['TopicI18n'] as $v) {
                            $navigationI18n_info = array(
                                'locale' => $v['locale'],
                                'navigation_id' => $nid,
                                'name' => isset($v['title']) ? $v['title'] : '',
                                'url' => $url,
                            );
                            $this->NavigationI18n->saveall(array('NavigationI18n' => $navigationI18n_info));//更新多语言
                        }
                    }
                }
            } else {
                if (isset($_POST['data1']['Navigation']['status']) && $_POST['data1']['Navigation']['status'] == 1) {
                    $this->Navigation->saveAll(array('Navigation' => $_POST['data1']['Navigation']));
                    $nid = $this->Navigation->getLastInsertId();
                    foreach ($_POST['data']['TopicI18n'] as $v) {
                        $navigationI18n_info = array(
                            'locale' => $v['locale'],
                            'navigation_id' => $nid,
                            'name' => isset($v['title']) ? $v['title'] : '',
                            'url' => $url,
                        );
                        $this->NavigationI18n->saveall(array('NavigationI18n' => $navigationI18n_info));//更新多语言
                    }
                }
            }
            $this->redirect('/topics/');
        }
        $this->data = $this->Topic->localeformat($id);
        $categories_tree = $this->CategoryProduct->tree('P', $this->locale);
        $brands_tree = $this->Brand->getbrandformat();
        $types_tree = $this->ProductType->gettypeformat();
        $wh['topic_id'] = !empty($this->data['Topic']['id']) ? $this->data['Topic']['id'] : '';
        $topicproduct = $this->TopicProduct->find($wh);
        //echo "<pre>";print_r($topicproduct);
        //
        //$topicproduct=$this->requestAction("/commons/get_linked_topic_products/".$topicproduct['TopicProduct']['topic_id']."");
        $topicproduct = $this->get_linked_topic_products($topicproduct['TopicProduct']['topic_id']);
        //pr($topicproduct);die();
        $topic_relation_product_format = array();
        foreach ($topicproduct as $k => $v) {
            //$v['TopicProduct']['id'] = $v['TopicProduct']['product_id'];
               $v['id'] = $v['TopicProduct']['product_id'];
            
            $topic_relation_product_format[$v['TopicProduct']['product_id']] = $v;
        }
        $_SESSION['topic'] = $topic_relation_product_format;
        
         //pr($_SESSION['topic'] );

        $url = '/topics/'.$id;
        $p_nav = $this->Navigation->find('first', array('conditions' => array('NavigationI18n.url' => $url)));
        if (isset($p_nav) && $p_nav != '') {
            $this->set('ninfo', $p_nav);
        }
         
         //查询对应的商品图片
          //$img_thumb_list=$this->Product->find('list',array('fields'=>'id,img_thumb'));
          //pr($img_thumb_list);
  
        $this->set('categories_tree', $categories_tree);
        $this->set('brands_tree', $brands_tree);
        $this->set('types_tree', $types_tree);
        $this->set('topicproduct', $topicproduct);
       // pr($topicproduct); 

        if (!empty($this->data['TopicI18n'][$this->backend_locale]['title'])) {
            $this->navigations[] = array('name' => $this->data['TopicI18n'][$this->backend_locale]['title'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_topic'],'url' => '');
        }

        //关键字
        $_SESSION['topic_relation_article'] = array();
        $seokeyword_data = $this->SeoKeyword->find('all', array('conditions' => array('status' => 1)));
        $this->set('seokeyword_data', $seokeyword_data);
        $category_tree_articles = $this->CategoryArticle->tree('all', $this->locale);//关联文章分类
        $this->set('category_tree_articles', $category_tree_articles);
        $this->set('article_category_id', 0);//关联文章分类选中
        $topic_relation_article = $this->TopicArticle->find('list', array('conditions' => array('topic_id' => $id), 'fields' => 'id,article_id'));
        $this->set('topic_relation_article', $topic_relation_article);
        if (!empty($topic_relation_article)) {
            $article_infos = $this->Article->relation_articles($topic_relation_article);
            $this->set('article_infos', $article_infos);
            $topic_relation_article_format = array();
            foreach ($topic_relation_article as $k => $v) {
                $info = array();
                $info['TopicArticle']['article_id'] = $v;
                $info['TopicArticle']['topic_id'] = $id;
                $info['TopicArticle']['title'] = $article_infos[$v];
                $topic_relation_article_format[$v] = $info;
            }
            $_SESSION['topic_relation_article'] = $topic_relation_article_format;
        }
    }

    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_article_failure'];
        /*判断权限*/
        if (!$this->operator_privilege('topics_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        /*end*/

        $topic_info = $this->Topic->findById($id);
        $this->Topic->deleteAll("Topic.id = '".$id."'", false);
        $this->TopicI18n->deleteAll("TopicI18n.topic_id = '".$id."'", false); //删除原有多语言
        $this->TopicProduct->deleteAll("TopicProduct.topic_id = '".$id."'");//删除关联商品
        $this->TopicArticle->deleteAll("TopicArticle.topic_id = '".$id."'");//删除关联文章
        //删除该专题的导航
        $url = '/topics/'.$id;
        $p_nav = $this->Navigation->find('first', array('conditions' => array('NavigationI18n.url' => $url)));
        if (isset($p_nav) && !empty($p_nav)) {
            $id = $p_nav['Navigation']['id'];
            $this->Navigation->deleteAll(array('Navigation.id' => $id));
            $this->NavigationI18n->deleteAll(array('navigation_id' => $id));
        }

        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_article_success'];

        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_topic'].':id '.$id.' '.$topic_info['TopicI18n']['title'], $this->admin['id']);
        }

        die(json_encode($result));
    }
    //批量处理
    public function batch()
    {
        $this->operator_privilege('topics_remove');
        if ($this->RequestHandler->isPost()) {
            foreach ($_REQUEST['checkboxes'] as $k => $v) {
                $this->Topic->deleteAll(array('Topic.id' => $v));
            }
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        $this->redirect('/topics');
    }

    //取得专题关联商品
    /*function get_linked_topic_products($topic_id){
        $wh["TopicProduct.topic_id"]="";
        $this->Product->hasMany = array();
        $this->Product->hasOne = array('ProductI18n'     =>array
                                                (
                                                  'className'    => 'ProductI18n',
                                                  'order'        => '',
                                                  'dependent'    =>  true,
                                                  'foreignKey'   => 'product_id'
                                                 ) ,

                        );

        $this->Product->set_locale($backend_locale);
        if(!empty($topic_id)){
            $wh["TopicProduct.topic_id"]=$topic_id;
        }
        $topic_products=$this->TopicProduct->find('all',array("conditions"=>$wh,"order"=>"TopicProduct.orderby desc"));

        $product_id_arr = array();
        foreach( $topic_products as $k=>$v ){
            $product_id_arr[] = $v["TopicProduct"]["product_id"];
        }

        $res=$this->Product->find("all",array("conditions"=>array("Product.id"=>$product_id_arr)));

        foreach($res as $k => $v){
            $products[$v['Product']['id']]=$v;
        }
        foreach($topic_products as $k => $v){
            if(is_array($products[$v['TopicProduct']['product_id']])){
                $topic_products[$k]['Product']=$products[$v['TopicProduct']['product_id']]['Product'];
                $topic_products[$k]['Product']['name']='';
                if(isset($products[$v['TopicProduct']['product_id']]['ProductI18n']) && is_array($products[$v['TopicProduct']['product_id']]['ProductI18n'])){
                    $topic_products[$k]['Product']['name']=$products[$v['TopicProduct']['product_id']]['ProductI18n']['name'];
                    $topic_products[$k]['Product']['name']=$products[$v['TopicProduct']['product_id']]['Product']['code']."  ".$topic_products[$k]['Product']['name'];
                }
                $topic_products[$k]['ProductI18n']=$products[$v['TopicProduct']['product_id']]['ProductI18n'];
            }
            //$linked_type = $v['TopicProduct']['is_double'] == 0 ? '单项关联' : '双向关联';
            $topic_products[$k]['ProductI18n']['name']=$topic_products[$k]['ProductI18n']['name'];
        }
        //	pr($topic_products);
        return $topic_products;
    }*/
    /**
     * 取得文章关联商品.
     *
     * @param int $article_id 文章ID
     *
     * @return array 关联商品信息 
     */
    public function get_linked_topic_products($topic_id)
    {
        $topic_relation_product_list = $this->TopicProduct->find('all', array('conditions' => array('topic_id' => $topic_id), 'order' => array('orderby asc')));
        //获取文章关联商品的ID数组
        $topic_relation_product_id_array = array();
        foreach ($topic_relation_product_list as $k => $v) {
            $topic_relation_product_id_array[] = $v['TopicProduct']['product_id'];
        }
        //获取关联商品数据
        $this->Product->set_locale($this->backend_locale);
        $relation_product_data = $this->Product->find('all', array('conditions' => array('Product.id' => $topic_relation_product_id_array, 'Product.status' => 1), 'fields' => array('Product.id', 'Product.code', 'ProductI18n.name','Product.img_detail')));
         
        $relation_product_data_format = array();//格式化数组
        foreach ($relation_product_data as $k => $v) {
            $relation_product_data_format[$v['Product']['id']] = $v;
        }
       
        //商品名称处理
        foreach ($topic_relation_product_list as $k => $v) {
        	if(!isset($relation_product_data_format[$v['TopicProduct']['product_id']])){unset($topic_relation_product_list[$k]);continue;}
            $topic_relation_product_list[$k]['TopicProduct']['name'] = '';
            if (isset($relation_product_data_format[$v['TopicProduct']['product_id']]) && is_array($relation_product_data_format[$v['TopicProduct']['product_id']])) {
                $linked_type = $this->ld['unidirectional'];
                $topic_relation_product_list[$k]['TopicProduct']['name'] = $relation_product_data_format[$v['TopicProduct']['product_id']]['Product']['code'].'--'.$relation_product_data_format[$v['TopicProduct']['product_id']]['ProductI18n']['name']." -- [$linked_type]";
            } else {
                $topic_relation_product_list[$k]['TopicProduct']['name'] = '';
            }
                $topic_relation_product_list[$k]['TopicProduct']['img_detail']=$relation_product_data_format[$v['TopicProduct']['product_id']]['Product']['img_detail'];
            
        }
        return $topic_relation_product_list;
    }

    /**
     *编辑页 关联商品 添加.
     */
    public function add_topic_relation_product()
    {
        //设置返回初始参数
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['unknown_reasons'];
        $topic_id = $_REQUEST['topic_id'];
         $product_select=$_REQUEST['product_select'];
        foreach($product_select as $ks=>$vs)
        {
        	 if($vs!=""){ $product_id['product_id'][]=$vs;}
        }
           //pr($product_id);
          $this->TopicProduct->deleteAll(array('topic_id' =>$topic_id, 'product_id' => $product_select));
          $this->Product->set_locale($this->backend_locale);
          if($_SESSION['topic']!=""){
           foreach( $_SESSION['topic'] as $x=>$vx){
           	   if(isset($vx['TopicProduct']['id'])&&$vx['TopicProduct']['id']!=""){
           	     $product_id['product_id'][]=$vx['TopicProduct']['product_id'];
           	     $product_select[]=$vx['TopicProduct']['product_id'];
           	   }
              }
          }
           
          $product_info = $this->Product->find('all', array('conditions' =>$product_id,'fields' => array('Product.id', 'Product.code','img_detail', 'ProductI18n.name')));
           //pr($product_id ); 
        
      //pr($product_info );
           foreach($product_info as $xc=>$vb){
           	     foreach($product_id['product_id'] as $c=>$x){   
           	  
           	   if($x==$vb['Product']['id']){
           	   $linkproduct_info['id']=$vb['Product']['id'];
           	   $linkproduct_info['name']=$vb['ProductI18n']['name'];
           	   $linkproduct_info['code']=$vb['Product']['code'];
           	    $linkproduct_info['img_detail']=$vb['Product']['img_detail'];
           	    $_SESSION['topic'][$vb['Product']['id']] = $linkproduct_info;
           	    
           	   }
            }
              
        }  
       
              
        //pr($_SESSION['topic']);die();
        //echo "<pre>";print_r($_SESSION["topic_relation_product"]);die();
        $result['flag'] = 1;//2 失败 1成功
        $content_array = array();
        foreach ($_SESSION['topic'] as $k => $v) {
            $content_array[] = $v;
        }
        //echo "<pre>";print_r( $content_array);die();
        $result['content'] = $content_array;
       
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑/添加专题关联商品:专题id '.$topic_id, $this->admin['id']);
        }
        //////商品格式 
        //unset($_SESSION["topic"]);
        die(json_encode($result));
    }
    /**
     *删除文章关联商品.////////////////////////////////////////////////////////////////////
     */
    public function drop_topic_relation_product($id)
    {
    
        $result['flag'] = 1;//2 失败 1成功
       $result['content'] = $this->ld['deleted_success'];
        unset($_SESSION['topic'][$id]);
     
             //pr($_SESSION['topic']);
         foreach($_SESSION['topic'] as $k=>$v)
         {
         	    if(isset($v['TopicProduct'])&&$v['TopicProduct']!=""){
				$kv['id']=$v['TopicProduct']['product_id'];
				$kv['product_id']=$v['TopicProduct']['product_id'];
				$kv['name']=$v['TopicProduct']['name'];
				$kv['img_detail']=$v['TopicProduct']['img_detail'];
			       $_SESSION['topic'][$k]=$kv;
			    }	
		          
         }
           //pr($_SESSION['topic']);
        
        $content_array = array();
        foreach ($_SESSION['topic'] as $k => $v) {
            $content_array[] = $v;
        }
		$result['content'] = $content_array;
		///////////添加后——————》删除 返回的格式是  商品的   [id] => 841930 [name] => HHHHHH  [code] => 11140  [img_detail] => 
                // pr($result);die();
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		die(json_encode($result));
    }
    
    
    
    public function toggle_on_status()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Topic->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除专题关联商品 ', $this->admin['id']);
        }
        die(json_encode($result));
    }
    /*
    *增加关联文章
    */
    public function add_topic_relation_article()
    {
        //设置返回初始参数
        $article_select = array();
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['unknown_reasons'];
        $topic_id = $_REQUEST['topic_id'];
        $article_select = explode(',', $_REQUEST['article_select']);
        $is_single2_value = $_REQUEST['is_single2_value'];
        $this->TopicArticle->deleteAll(array('topic_id' => $topic_id, 'article_id' => $article_select));
        foreach ($article_select as $k => $v) {
            $link_article_info = array('TopicArticle' => array('topic_id' => $topic_id,'article_id' => $v,'orderby' => 50));
            $this->Article->set_locale($this->backend_locale);
            $article_info = $this->Article->find('first', array('conditions' => array('Article.id' => $v), 'fields' => array('Article.id', 'ArticleI18n.title')));
            $link_article_info['TopicArticle']['title'] = $article_info['ArticleI18n']['title'];
            $_SESSION['topic_relation_article'][$v] = $link_article_info;
        }
        $result['flag'] = 1;//2 失败 1成功
        $content_array = array();
        foreach ($_SESSION['topic_relation_article'] as $k => $v) {
            $content_array[] = $v;
        }
        $result['content'] = $content_array;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'增加专题关联文章:专题id '.$topic_id, $this->admin['id']);
            }
        die(json_encode($result));
    }
    /*
    *删除关联文章
    */
    public function drop_topic_relation_article($artilce_id, $topic_id)
    {
        $result['flag'] = 1;//2 失败 1成功
        //echo "ffff";die();
        $result['content'] = $this->ld['deleted_success'];
        unset($_SESSION['topic_relation_article'][$artilce_id]);
        $content_array = array();
        foreach ($_SESSION['topic_relation_article'] as $k => $v) {
            $content_array[] = $v;
        }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除专题关联文章:专题id '.$topic_id, $this->admin['id']);
            }
        $result['content'] = $content_array;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
        排序
    */
    public function changeorder($updowm, $id, $nextone, $page = 1)
    {
        $this->Topic->set_locale($this->backend_locale);
        //如果值相等重新自动排序
        $a = $this->Topic->query('SELECT * 
			FROM `svcms_topics` as A inner join `svcms_topics` as B
			WHERE A.id<>B.id and A.orderby=B.orderby');
        $topic_one = $this->Topic->find('first', array('conditions' => array('Topic.id' => $id)));
        if (!empty($a)) {
            $all = $this->Topic->find('all');
            $i = 0;
            foreach ($all as $k => $vv) {
                $all[$k]['Topic']['orderby'] = ++$i;
            }
            $this->Topic->saveAll($all);
        }
        if ($updowm == 'up') {
            $topic_change = $this->Topic->find('first', array('conditions' => array('Topic.orderby <' => $topic_one['Topic']['orderby']), 'order' => 'Topic.orderby+0 desc', 'limit' => '1'));
        }
        if ($updowm == 'down') {
            $topic_change = $this->Topic->find('first', array('conditions' => array('Topic.orderby >' => $topic_one['Topic']['orderby']), 'order' => 'Topic.orderby+0 asc', 'limit' => '1'));
        }
        $t = $topic_one['Topic']['orderby'];
        $topic_one['Topic']['orderby'] = $topic_change['Topic']['orderby'];
        $topic_change['Topic']['orderby'] = $t;
        $this->Topic->save($topic_one);
        $this->Topic->save($topic_change);

        $condition = '';
        $sortClass = 'Topic';
        $total = $this->Topic->find('count', $condition);
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $this->set('datapage', $page);
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'topics','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Topic');
        $this->Pagination->init($condition, $parameters, $options);
        $data = $this->Topic->find('all', array('conditions' => $condition, 'order' => 'Topic.orderby+0,Topic.start_time', 'limit' => $rownum, 'page' => $page));

        $this->set('topics', $data);
        Configure::write('debug', 0);
        $this->render('index');
        $this->layout = 'ajax';
    }
}
