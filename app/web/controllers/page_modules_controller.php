<?php

/**
 *ModulesController.
 */
class PageModulesController extends AppController
{
    /*
    *@var $name
    *@var $helpers
    *@var $uses
    *@static $top_module_infos
    *@static $right_module_infos
    *@static $right_module_infos
    */
    public $name = 'PageModules';
    public $helpers = array('Html','Flash','Cache');
    public $uses = array('PageModule','Flash','Article','Product','CategoryArticle','Brand','Topic','Promotion','CategoryProduct');
    public static $top_module_infos = array();
    public static $right_module_infos = array();
    public static $left_module_infos = array();
    public static $subPageModules = array();
    //
    public function test()
    {
        $this->layout = 'default_full';
        $this->PageModule->set_locale($this->locale);
        //$module_infos = $this->PageModule->find('all',array('conditions'=>array('PageModule.status'=>1),'order'=>'PageModule.orderby','fields'=>''));
        $module_infos = $this->PageModule->tree('chi');
        $subPageModules = array();
        $code_infos = array();
        foreach ($module_infos as $m) {
            if (isset($m['SubPageModule']) && !empty($m['SubPageModule'])) {
                $conditions = array();
                foreach ($m['SubPageModule'] as $subm) {
                    $conditions = array();
                    $code_infos[$subm['PageModule']['code']]['type'] = $subm['PageModule']['type'];
                    $code_infos[$subm['PageModule']['code']]['type_id'] = $subm['PageModule']['type_id'];
                    $code_infos[$subm['PageModule']['code']]['name'] = $subm['PageModuleI18n']['name'];
                    $code_infos[$subm['PageModule']['code']]['title'] = $subm['PageModuleI18n']['title'];
                    $code_infos[$subm['PageModule']['code']]['element_type'] = $subm['PageModule']['element_type'];
                        //文章模块
                        if ($subm['PageModule']['type'] == 'module_article') {
                            if ($subm['PageModule']['type_id'] != '') {
                                $conditions['Article.category_id'] = $subm['PageModule']['type_id'];
                            }
                            $conditions['Article.status'] = 1;
                            //$article_infos= $this->Article->find('all',array('conditions'=>array(),'limit'=>$subm['PageModule']['limit'],'order'=>'Article.'.$subm['PageModule']['orderby']));
                            $article_infos = $this->Article->get_module_articles($conditions, $subm['PageModule']['limit'], $subm['PageModule']['orderby_type']);
                        //	$article_infos= $this->Article->find('all',array('conditions'=>$conditions,'limit'=>$subm['PageModule']['limit'],'order'=>'Article.'.$subm['PageModule']['orderby_type'],'fields'=>'Article.id,Article.category_id,Article.created,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.content'));
                            $subPageModules[$m['PageModule']['code']][$subm['PageModule']['position']][$subm['PageModule']['code']] = $article_infos;
                        }
                        //商品模块
                        if ($subm['PageModule']['type'] == 'module_product') {
                            if ($subm['PageModule']['type_id'] != '') {
                                $conditions['Product.category_id'] = $subm['PageModule']['type_id'];
                            }
                            $conditions['Product.status'] = 1;
                            $product_infos = $this->Product->find('all', array('conditions' => $conditions, 'limit' => $subm['PageModule']['limit'], 'order' => 'Product.'.$subm['PageModule']['orderby_type'], 'fields' => 'Product.id,Product.category_id,Product.img_thumb,Product.img_detail,Product.shop_price,Product.market_price,ProductI18n.name'));
                            $product_infos = $this->Product->get_products_sub_names($product_infos, 20);
                            $subPageModules[$m['PageModule']['code']][$subm['PageModule']['position']][$subm['PageModule']['code']] = $product_infos;
                        }
                        //文章分类模块
                        if ($subm['PageModule']['type'] == 'module_article_category') {
                            $product_category_infos = $this->CategoryArticle->tree('A', 0, $this->locale, $subm['PageModule']['limit']);
                            $subPageModules[$m['PageModule']['code']][$subm['PageModule']['position']][$subm['PageModule']['code']] = $product_category_infos['tree'];
                        }
                        //商品分类模块
                        if ($subm['PageModule']['type'] == 'module_product_category') {
                            $product_category_infos = $this->CategoryProduct->tree('P', 0, $this->locale, $subm['PageModule']['limit']);
                            $subPageModules[$m['PageModule']['code']][$subm['PageModule']['position']][$subm['PageModule']['code']] = $product_category_infos['tree'];
                        }
                        //品牌模块
                        if ($subm['PageModule']['type'] == 'module_brand') {
                            $conditions['Brand.status'] = 1;
                            $brand_infos = $this->Brand->find('all', array('conditions' => $conditions, 'limit' => $subm['PageModule']['limit'], 'order' => 'Brand.'.$subm['PageModule']['orderby_type'], 'fields' => array('Brand.id', 'BrandI18n.name')));
                            $subPageModules[$m['PageModule']['code']][$subm['PageModule']['position']][$subm['PageModule']['code']] = $brand_infos;
                        }
                        //帮助中心模块
                        if ($subm['PageModule']['type'] == 'module_help_information') {
                            $conditions['Article.status'] = 1;
                            $conditions['Article.type'] = 'H';
                            $submodule_help_infos = $this->Article->get_module_articles($conditions, $subm['PageModule']['limit'], $subm['PageModule']['orderby_type']);
                            //$submodule_help_infos=$this->Article->find('all',array("conditions"=>$conditions,'limit'=>$subm['PageModule']['limit'],"fields"=>"Article.id,Article.category_id,Article.created,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.content","order"=>"Article.".$subm['PageModule']['orderby_type']));
                            $subPageModules[$m['PageModule']['code']][$subm['PageModule']['position']][$subm['PageModule']['code']] = $submodule_help_infos;
                        }
                        //轮播
                        if ($subm['PageModule']['type'] == 'module_flash') {
                            $conditions['Flash.type'] = 'H';
                            $submodule_flash_infos = $this->Flash->find('first', array('conditions' => $conditions, 'fields' => array('Flash.width', 'Flash.height')));
                            $subPageModules[$m['PageModule']['code']][$subm['PageModule']['position']][$subm['PageModule']['code']] = $submodule_flash_infos;
                        }
                        //专题模块
                        if ($subm['PageModule']['type'] == 'module_topic') {
                            $conditions['Topic.status'] = 1;
                            $conditions = array('Topic.start_time <=' => DateTime,'Topic.end_time >=' => DateTime);
                            $submodule_topic_infos = $this->Topic->find('all', array('conditions' => $conditions, 'limit' => $subm['PageModule']['limit'], 'fields' => 'Topic.id,TopicI18n.topic_id,TopicI18n.img01,TopicI18n.title', 'order' => 'Topic.'.$subm['PageModule']['orderby_type']));
                            $subPageModules[$m['PageModule']['code']][$subm['PageModule']['position']][$subm['PageModule']['code']] = $submodule_flash_infos;
                        }
                        //促销模块
                        if ($subm['PageModule']['type'] == 'module_promotion') {
                            $conditions['Promotion.status'] = 1;
                            $conditions = array('Promotion.start_time <=' => DateTime,'Promotion.end_time >=' => DateTime);
                            $submodule_promotion_infos = $this->Promotion->find('all', array('conditions' => $conditions, 'limit' => $subm['PageModule']['limit'], 'fields' => '', 'order' => 'Promotion.'.$subm['PageModule']['orderby_type']));
                            $subPageModules[$m['PageModule']['code']][$subm['PageModule']['position']][$subm['PageModule']['code']] = $submodule_promotion_infos;
                        }
                }
            }
            $conditions = array();
            $code_infos[$m['PageModule']['code']]['type'] = $m['PageModule']['type'];
            $code_infos[$m['PageModule']['code']]['type_id'] = $m['PageModule']['type_id'];
            $code_infos[$m['PageModule']['code']]['name'] = $m['PageModuleI18n']['name'];
            $code_infos[$m['PageModule']['code']]['title'] = $m['PageModuleI18n']['title'];
            $code_infos[$m['PageModule']['code']]['element_type'] = $m['PageModule']['element_type'];
            //父模块
            if ($m['PageModule']['type'] == 'module_parent') {
                $this->all_module_infos('', $m['PageModule']['position'], $m['PageModule']['code']);
            }
            //文章模块
            if ($m['PageModule']['type'] == 'module_article') {
                if ($m['PageModule']['type_id'] != '') {
                    $conditions['Article.category_id'] = $m['PageModule']['type_id'];
                }
                $conditions['Article.status'] = 1;
                //$article_infos= $this->Article->find('all',array('conditions'=>array(),'limit'=>$m['PageModule']['limit'],'order'=>'Article.'.$m['PageModule']['orderby']));
                $article_infos = $this->Article->get_module_articles($conditions, $m['PageModule']['limit'], $m['PageModule']['orderby_type']);
                //$article_infos= $this->Article->find('all',array('conditions'=>$conditions,'limit'=>$m['PageModule']['limit'],'order'=>'Article.'.$m['PageModule']['orderby_type'],'fields'=>'Article.id,Article.category_id,Article.created,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.content'));
                $this->all_module_infos($article_infos, $m['PageModule']['position'], $m['PageModule']['code']);
            }
            //商品模块
            if ($m['PageModule']['type'] == 'module_product') {
                if ($m['PageModule']['type_id'] != '') {
                    $conditions['Product.category_id'] = $m['PageModule']['type_id'];
                }
                $conditions['Product.status'] = 1;
                $product_infos = $this->Product->find('all', array('conditions' => $conditions, 'limit' => $m['PageModule']['limit'], 'order' => 'Product.'.$m['PageModule']['orderby_type'], 'fields' => 'Product.id,Product.img_thumb,Product.img_detail,ProductI18n.name'));
                $product_infos = $this->Product->get_products_sub_names($product_infos, 20);
                $this->all_module_infos($product_infos, $m['PageModule']['position'], $m['PageModule']['code']);
            }
            //文章分类模块
            if ($m['PageModule']['type'] == 'module_article_category') {
                $product_category_infos = $this->CategoryArticle->tree('A', 0, $this->locale, $m['PageModule']['limit']);
                $this->all_module_infos($product_category_infos['tree'], $m['PageModule']['position'], $m['PageModule']['code']);
            }
            //商品分类模块
            if ($m['PageModule']['type'] == 'module_product_category') {
                $product_category_infos = $this->CategoryProduct->tree('P', 0, $this->locale, $m['PageModule']['limit']);
                $this->all_module_infos($product_category_infos['tree'], $m['PageModule']['position'], $m['PageModule']['code']);
            }
            //品牌模块
            if ($m['PageModule']['type'] == 'module_brand') {
                $conditions['Brand.status'] = 1;
                $brand_infos = $this->Brand->find('all', array('conditions' => $conditions, 'limit' => $m['PageModule']['limit'], 'order' => 'Brand.'.$m['PageModule']['orderby_type'], 'fields' => array('Brand.id', 'BrandI18n.name')));
                $this->all_module_infos($brand_infos, $m['PageModule']['position'], $m['PageModule']['code']);
            }
            //帮助中心模块
            if ($m['PageModule']['type'] == 'module_help_information') {
                $conditions['Article.status'] = 1;
                $conditions['Article.type'] = 'H';
                //$module_help_infos=$this->Article->find('all',array("conditions"=>$conditions,'limit'=>$m['PageModule']['limit'],"fields"=>"Article.id,Article.category_id,Article.created,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.content","order"=>"Article.".$m['PageModule']['orderby_type']));
                $module_help_infos = $this->Article->get_module_articles($conditions, $m['PageModule']['limit'], $m['PageModule']['orderby_type']);
                $this->all_module_infos($module_help_infos, $m['PageModule']['position'], $m['PageModule']['code']);
            }
            //轮播
            if ($m['PageModule']['type'] == 'module_flash') {
                $conditions['Flash.type'] = 'H';
                $module_flash_infos = $this->Flash->find('first', array('conditions' => $conditions, 'fields' => array('Flash.width', 'Flash.height')));
                $this->all_module_infos($module_flash_infos, $m['PageModule']['position'], $m['PageModule']['code']);
            }
            //专题模块
            if ($m['PageModule']['type'] == 'module_topic') {
                $conditions['Topic.status'] = 1;
                $conditions = array('Topic.start_time <=' => DateTime,'Topic.end_time >=' => DateTime);
                $module_topic_infos = $this->Topic->find('all', array('conditions' => $conditions, 'limit' => $m['PageModule']['limit'], 'fields' => 'Topic.id,TopicI18n.topic_id,TopicI18n.img01,TopicI18n.title', 'order' => 'Topic.'.$m['PageModule']['orderby_type']));
                $this->all_module_infos($module_topic_infos, $m['PageModule']['position'], $m['PageModule']['code']);
            }
            //促销模块
            if ($m['PageModule']['type'] == 'module_promotion') {
                $conditions['Promotion.status'] = 1;
                $conditions = array('Promotion.start_time <=' => DateTime,'Promotion.end_time >=' => DateTime);
                $module_promotion_infos = $this->Promotion->find('all', array('conditions' => $conditions, 'limit' => $m['PageModule']['limit'], 'fields' => '', 'order' => 'Promotion.'.$m['PageModule']['orderby_type']));
                $this->all_module_infos($module_promotion_infos, $m['PageModule']['position'], $m['PageModule']['code']);
            }
        }
        $this->set('code_infos', $code_infos);
        $this->set('top_module_infos', isset($this->top_module_infos) ? $this->top_module_infos : '');
        $this->set('right_module_infos', isset($this->right_module_infos) ? $this->right_module_infos : '');
        $this->set('left_module_infos', isset($this->left_module_infos) ? $this->left_module_infos : '');
        $this->set('module_infos', $module_infos);
        $this->set('subPageModules', $subPageModules);
        //pr($subPageModules);
        //pr($right_module_infos);
    }

    public function all_module_infos($infos, $position, $code)
    {
        if ($position == 'top') {
            $this->top_module_infos[$code] = $infos;
        }
        if ($position == 'right') {
            $this->right_module_infos[$code] = $infos;
        }
        if ($position == 'left') {
            $this->left_module_infos[$code] = $infos;
        }
    }
}
