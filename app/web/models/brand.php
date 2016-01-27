<?php

/**
 * 商品品牌模型.
 *
 * @todo 一些函数改用find list
 */
class brand extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Brand flash图片参数表
     */
    public $name = 'Brand';
    /*
     * @var $cacheQueries boolen 是否开启缓存：是。
     */
    public $cacheQueries = true;
    /*
     * @var $cacheAction 1day 缓存时间：1天。
     */
    public $cacheAction = '1 day';
    /*
     * @var $hasOne array 关联flash多语言表
     */
    public $hasOne = array('BrandI18n' => array('className' => 'BrandI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'brand_id',
        ),
    );

    /**
     *函数set_locale，用于设置商品语言.
     *
     *@param $locale 商品语言类型
     *@param $conditions 商品语言信息
     */
    public function set_locale($locale)
    {
        //echo $locale;
        $this->hasOne['BrandI18n']['conditions'] = array('locale' => $locale);
    }

    /**
     * getlist方法，品牌列表.
     *
     * @return $Brands 返回取得的数组
     */
    public function getlist()
    {
        $Brands_type = array();
        $condition = "status ='1'";
        $Brands = $this->findAll($condition, '', 'orderby asc');

        return $Brands;
    }

//品牌详细
    /**
     * findassoc方法，品牌详细.
     *
     * @param $id 输入id
     *
     * @return $Brands 返回相对应的字段
     */
    public function get_detail($id)
    {
        $Brands = $this->findbyid($id);

        return $Brands;
    }

    //hobby 20081117 取得id=>name的数组
    /**
     * findassoc方法，取得id=>name的数组.
     *
     * @param $locale 输入语言
     *
     * @return $lists_formated 返回取得的数组
     */
    public function findassoc($locale = '')
    {
        $condition = " Brand.status ='1' ";
        $orderby = ' orderby asc ';
        $cache_key = md5($this->name.'_'.$locale);

        $lists_formated = cache::read($cache_key);
        if ($lists_formated) {
            return $lists_formated;
        } else {
            //		$lists=$this->findall($condition,'',$orderby);
            $lists = $this->find('all', array('order' => array($orderby, 'BrandI18n.name asc'),
                        'fields' => array('Brand.id', 'Brand.flash_config', 'Brand.url', 'Brand.img01', 'Brand.modified', 'BrandI18n.img01', 'BrandI18n.name',
                        ),
                        'conditions' => array($condition), ));

            $lists_formated = array();
            if (is_array($lists)) {
                foreach ($lists as $k => $v) {
                    $lists_formated[$v['Brand']['id']] = $v;
                }
            }

            cache::write($cache_key, $lists_formated);

            return $lists_formated;
        }
    }

    public function find_brands($condition)
    {
        $brands = $this->find('all', array('conditions' => array($condition),
                    'fields' => array('Brand.id', 'BrandI18n.name'),
                    'group' => array('Brand.id', 'BrandI18n.name'), ));

        return $brands;
    }
    /**
     * 函数get_module_infos 获取品牌列表信息.
     *
     * @param  参数集合
     *
     * @return $brand_infos 返回品牌列表信息
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        //pr($params);die;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        $conditions['Brand.status'] = 1;
        //分页start
        $total = $this->find('count', array('conditions' => $conditions));
        App::import('Component', 'Paginationmodel');
        $pagination = new PaginationModelComponent();

        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'brands','action' => 'index','page' => $page,'limit' => $limit);
        //分页参数
        $options = array('page' => $page,'show' => $limit,'modelClass' => $this->name,'total' => $total);
        //pr($conditions);die;
        $pages = $pagination->init($conditions, $parameters, $options); // Added
        //pr($pages);
        //分页end

        $brand_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Brand.'.$order, 'fields' => array('Brand.id', 'BrandI18n.name', 'BrandI18n.img01'), 'page' => $page));
        $brand_infos['brand'] = $brand_infos;
        $brand_infos['paging'] = $pages;

        return $brand_infos;
    }
    /**
     * 函数get_module_brand_infos 获取品牌详情.
     *
     * @param  参数集合
     *
     * @return $brand_infos 返回品牌详情
     */
    public function get_module_brand_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created desc';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['id'])) {
            $conditions['Brand.id'] = $params['id'];
        }
        $conditions['Brand.status'] = '1';
        $brand_infos = $this->find('first', array('fields' => array('Brand.id', 'Brand.created', 'BrandI18n.name', 'BrandI18n.description', 'BrandI18n.meta_keywords', 'BrandI18n.img01'), 'conditions' => $conditions, 'order' => $order, 'limit' => $limit));

        return $brand_infos;
    }
    /**
     * 函数get_module_brand_flash 获取品牌轮播内容.
     *
     * @param  参数集合
     *
     * @return $module_flash_infos 返回品牌轮播
     */
    public function get_module_brand_flash($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created desc';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['flash_type'])) {
            $conditions['Flash.page'] = $params['flash_type'];
        }
        if (isset($params['id'])) {
            $conditions['Flash.page_id'] = $params['id'];
        }
        $conditions['Flash.page'] = 'B';
        $conditions['Flash.type'] = '0';
        $Flash = ClassRegistry::init('Flash');
        $module_flash_infos = $Flash->find('first', array('conditions' => $conditions, 'fields' => array('Flash.width', 'Flash.height', 'Flash.page_id')));
        //pr($module_flash_infos);
        return $module_flash_infos;
    }
    /**
     * 函数get_module_brand_category 获取品牌分类内容.
     *
     * @param  参数集合
     *
     * @return $article_infos 返回品牌分类
     */
    public function get_module_brand_category($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created desc';
        if (isset($params['order'])) {
            $order = $params['order'];
        }

        $conditions['Flash.page'] = 'B';
        $conditions['Flash.type'] = '0';
        $CategoryType = ClassRegistry::init('CategoryType');
        $module_category_infos = $CategoryType->find('all', array('conditions' => array('CategoryTypeI18n.locale' => LOCALE)));
        //pr($module_category_infos);
        $module_category_info['category'] = $module_category_infos;
        $brands_info = $this->find('all');
        $module_category_info['brand'] = $brands_info;

        return $module_category_info;
    }
    /**
     * 函数get_module_brand_product 获取当前品牌商品内容.
     *
     * @param  参数集合
     *
     * @return $article_infos 返回品牌商品
     */
    public function get_module_brand_product($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'Product.created desc';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['id'])) {
            $conditions['Product.brand_id'] = $params['id'];
        }
        $conditions['Product.forsale'] = 1;
        $conditions['Product.status'] = 1;
        $Product = ClassRegistry::init('Product');
        $module_brand_product = $Product->find('all', array('conditions' => $conditions, 'fields' => array('Product.id','Product.code', 'Product.img_thumb', 'Product.img_detail', 'ProductI18n.name', 'Product.shop_price', 'Product.like_stat','Product.unit'), 'order' => $order, 'limit' => $limit));
        if (!empty($module_brand_product)) {
        	$product_ids = $Product->getproduct_ids($module_brand_product);
		$product_codes = $Product->getproduct_codes($module_brand_product);
		
		$SkuProduct = ClassRegistry::init('SkuProduct');
		$price_range=$SkuProduct->sku_price_range($product_codes);
		
		$ProductComment = ClassRegistry::init('Comment');
		$comment_num_info = $ProductComment->find("all",array('conditions'=>array('Comment.type'=>'P','Comment.type_id'=>$product_ids),"fields"=>array("Comment.type_id","count(*) as Commentnum"),'group'=>'Comment.type_id'));
		$comment_num_data=array();
		foreach($comment_num_info as $v){
			$comment_num_data[$v['Comment']['type_id']]=$v[0]['Commentnum'];
		}
		foreach ($module_brand_product as $k => $v) {
			$module_brand_product[$k]['Product']['Commentnum'] = isset($comment_num_data[$v['Product']['id']])?$comment_num_data[$v['Product']['id']]:0;
			if(isset($price_range[$v['Product']['code']])){
			   $module_brand_product[$k]['price_range'] = $price_range[$v['Product']['code']];
		  	}
		}
        }
        return $module_brand_product;
    }

    public function get_brand_by_category($category_id)
    {
        $brandinfo = array();
        $CategoryProduct = ClassRegistry::init('CategoryProduct');
        $Product = ClassRegistry::init('Product');
        $category_ids = $CategoryProduct->find('list', array('fields' => 'CategoryProduct.id', 'conditions' => array('CategoryProduct.parent_id' => $category_id)));
        $category_ids[$category_id] = $category_id;
        $brand_ids = $Product->find('list', array('fields' => array('Product.brand_id'), 'conditions' => array('Product.category_id' => $category_ids)));
        $brandinfo = $this->find('all', array('fields' => array('Brand.id', 'BrandI18n.name', 'BrandI18n.img01'), 'conditions' => array('Brand.id' => $brand_ids, 'Brand.status' => '1', 'BrandI18n.img01 !=' => '')));

        return $brandinfo;
    }
}
