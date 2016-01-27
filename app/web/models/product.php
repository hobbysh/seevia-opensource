<?php

/**
 * 商品模型.
 */

class product extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */

    public $useDbConfig = 'oms';
    public $name = 'Product';
    public $cacheQueries = true;
    public $cacheAction = '1 day';
    public $hasOne = array(
        'ProductI18n' => array(
            'className' => 'ProductI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'product_id',
            ),
    );
    public $hasMany = array(
                        'ProductAttribute' => array(
                        'className' => 'ProductAttribute',
                        'conditions' => array('ProductAttribute.locale' => LOCALE),
                        'order' => 'ProductAttribute.attribute_id',
                        'fields' => 'ProductAttribute.id,ProductAttribute.attribute_id,ProductAttribute.attribute_value,ProductAttribute.attribute_price',
                        'dependent' => true,
                        'foreignKey' => 'product_id',
                    ),
        );
    public $belongsTo = array(
        'Brand' => array(
        'className' => 'Brand',
        'conditions' => 'Product.brand_id=Brand.id',
        'order' => '',
        'dependent' => true,
        ), );

    /**
     *函数set_locale，用于设置商品语言.
     *
     *@param $locale 商品语言类型
     *@param $conditions 商品语言信息
     */
    public function set_locale($locale)
    {
        $this->hasOne['ProductI18n']['conditions'] = array('locale' => $locale);
        $this->hasMany['ProductAttribute']['conditions'] = array('ProductAttribute.locale' => $locale);
    }

    public $ids = array();//所有商品id
    public $viewVars = array();
    public $sku_arr = array();
    /**
     * 函数return_lists,用于返回商品列表.
     *
     * @param $ids 商品号
     * @param $conditions 商品编号
     * @param $products 商品相关信息
     * @param $product_lists 商品列表
     *
     * @return $product_lists 商品列表内容
     */
    public function return_lists($ids)
    {
        $conditions = array('Product.id' => $ids);
        $products = $this->find('all', array('cache' => $this->short, 'conditions' => $conditions,
                    'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb', 'Product.market_price', 'Product.shop_price', 'Product.point', 'Product.promotion_price', 'Product.promotion_start', 'Product.promotion_end', 'Product.promotion_status', 'Product.code', 'Product.product_rank_id', 'ProductI18n.name', 'Product.freeshopping',
                    ),
                ));
        $product_lists = array();
        if (isset($products) && sizeof($products) > 0) {
            foreach ($products as $k => $v) {
                $product_lists[$v['Product']['id']] = $v;
                $this->ids[$v['Product']['id']] = $v['Product']['id'];
            }
        }

        return $product_lists;
    }

    /**
     * 函数find_category,用于分类商品.
     */
    public function find_category()
    {
    }

    /**
     * 函数get_list,用于获得商品列表.
     *
     * @param $products_id 商品ID号
     * @param $locale 商品语言
     * @param $status 状态
     * @param $groupby 分组
     * @param $orderby 排序
     * @param $lists 一个空数组
     * @param $condition 中间变量
     * @param $params 排序完的数组
     *
     * @return $Lists 商品列表内容
     */
    public function get_list($products_id, $locale, $status = '1', $groupby = '', $orderby = 'Product.modified desc')
    {
        $Lists = array();
        $condition = ' 1 ';
        if ($products_id != '') {
            $condition .= ' AND Product.id in ('.$products_id.') ';
        }
        if ($status != '') {
            $condition .= " AND Product.status='".$status."'";
        }
        if ($groupby != '') {
            $condition .= ' GROUP BY  '.$groupby;
        }
        //	$Lists=$this->findAll($condition,'',$orderby);
        $params = array('cache' => $this->short,'order' => array($orderby),
            'fields' => array('Product.id'
                , 'Product.recommand_flag'
                , 'Product.status'
                , 'Product.img_detail'
                , 'Product.img_thumb'
                , 'Product.market_price'
                , 'Product.shop_price'
                , 'Product.promotion_price'
                , 'Product.promotion_start'
                , 'Product.promotion_end'
                , 'Product.promotion_status'
                , 'Product.code'
                , 'Product.brand_id'
                , 'Product.category_id'
                , 'Product.product_rank_id'
                , 'Product.quantity', 'ProductI18n.name',
            ),
            'conditions' => array($condition),
        );
        $Lists = $this->find('all', $params, $this->name.$locale);
        foreach ($Lists as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $Lists;
    }

    /**
     * 函数promotion 用于获取商品信息.
     *
     * @param $number 商品数量
     * @param $locale 商品语言
     * @param $category_ids 商品分类号
     * @param $datetime 日期
     * @param $condition 商品信息
     * @param $params 设置排序
     * @param $products 获取商品信息
     * @param $products 商品信息
     */
    public function promotion($options = array(), $filter_condition = array())
    {
        $condition['Product.alone'] = 1;
        $condition['Product.status'] = 1;
        $condition['Product.forsale'] = 1;
        $condition['Product.promotion_status'] = array('1','2');
          //$condition['Product.promotion_start >'] = substr(DateTime,0,-2) . "00";
       //$condition['Product.promotion_end <'] = substr(DateTime,0,-2) . "00";
        $condition['Product.promotion_start >'] = date('Y-m-d H:00:00');
        $condition['Product.promotion_end <'] = date('Y-m-d H:59:59');
        if (!empty($options['category_id'])) {
            $condition['Product.category_id'] = $options['category_id'];
        }
        if (!empty($filter_condition)) {
            $condition[] = $filter_condition;
        }
        $params = array('cache' => $this->short,'order' => array('Product.modified DESC'),
            'recursive' => -1,
            'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb','Product.img_detail'
                , 'Product.market_price'
                , 'Product.shop_price'
                 , 'Product.category_id'
                , 'Product.promotion_price'
                , 'Product.promotion_start'
                , 'Product.promotion_end'
                , 'Product.promotion_status'
                , 'Product.code'
                , 'Product.quantity'
                , 'Product.product_rank_id', 'Product.freeshopping','Product.unit'
            ),
            'conditions' => $condition,
            'limit' => empty($options['limit']) ? 15 : $options['limit'],
        );
        $products = $this->find('all', $params);

        if (!empty($products) && sizeof($products) > 0) {
            foreach ($products as $v) {
                $this->ids[$v['Product']['id']] = $v['Product']['id'];
            }
            $product_ids = $this->getproduct_ids($products);
            $product_codes = $this->getproduct_codes($products);
            $comment = ClassRegistry::init('Comment');
            $user_like = ClassRegistry::init('UserLike');
            $ProductI18n = ClassRegistry::init('ProductI18n');
            $SkuProduct = ClassRegistry::init('SkuProduct');
            $price_range=$SkuProduct->sku_price_range($pro_codes);
            
            $comment_num = $comment->find('all', array('conditions' => array('Comment.type_id' => $product_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
            $like_num = $user_like->find('all', array('conditions' => array('UserLike.type' => 'P', 'UserLike.type_id' => $product_ids), 'fields' => array('UserLike.type_id', 'count(UserLike.type_id) as num'), 'group' => 'UserLike.type_id'));
            $name = $ProductI18n->find('all', array('conditions' => array('ProductI18n.product_id' => $product_ids), 'fields' => array('ProductI18n.name', 'ProductI18n.product_id')));

            foreach ($products as $k => $v) {
                $this->ids[$v['Product']['id']] = $v['Product']['id'];
                foreach ($name as $name_k => $name_v) {
                    if ($products[$k]['Product']['id'] == $name_v['ProductI18n']['product_id']) {
                        $products[$k]['Product']['name'] = $name_v['ProductI18n']['name'];
                    }
                }
                foreach ($like_num as $like_k => $like_v) {
                    if ($products[$k]['Product']['id'] == $like_v['UserLike']['type_id']) {
                        $products[$k]['Product']['like_num'] = $like_v[0]['num'];
                    }
                }
                foreach ($comment_num as $com_k => $com_v) {
                    if ($products[$k]['Product']['id'] == $com_v['Comment']['type_id']) {
                        $products[$k]['Product']['Commentnum'] = $com_v[0]['Commentnum'];
                    }
                }
		  if(isset($price_range[$v['Product']['code']])){
			   $products[$k]['price_range'] = $price_range[$v['Product']['code']];
		  }
            }
            if (!empty($options['set'])) {
                $this->viewVars[1][$options['set']] = $products;
            } else {
                return $products;
            }
        }
    }

    /**
     * 函数is_promotion 用于判断是否是有效促销商品.
     *
     * @param $datetime 时间
     * @param $product 商品信息
     *
     * @return true  是促销商品
     * @return false 不是促销商品
     */
    public function is_promotion($product)
    {
        //  $datetime = date("Y-m-d H:i:s");
        if (isset($product['Product']) && ($product['Product']['promotion_status'] == 1 || $product['Product']['promotion_status'] == 2) && $product['Product']['promotion_start'] <= date('y-m-d 00:00:00') && $product['Product']['promotion_end'] >= date('y-m-d 23:59:59')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 函数newarrival 获取新商品.
     *
     * @param $number 商品数量
     * @param $locale 商品语言
     * @param $category_ids 分类商品号
     * @param $condition 商品信息
     * @param $params 设置排序
     * @param $products 商品信息
     *
     * @return $products 商品数组列表
     */
    public function newarrival($options = array(), $filter_condition = array())
    {
        $condition = array('Product.status' => '1',
            'Product.alone' => '1',
            'Product.forsale' => '1',
        );
        if (!empty($options['category_id'])) {
            $condition['or']['Product.category_id'] = $options['category_id'];
        }
        if (!empty($options['expand_pids'])) {
            $condition['or']['Product.id'] = $options['expand_pids'];
        }
        if (!empty($filter_condition)) {
            $condition[] = $filter_condition;
        }
        //pr($this->short);
        $params = array(
            'cache' => $this->short,'order' => array('Product.modified DESC'),
            'recursive' => -1,
            'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb','Product.img_detail'
                , 'Product.market_price'
                , 'Product.shop_price'
                , 'Product.category_id'
                , 'Product.promotion_price'
                , 'Product.promotion_start'
                , 'Product.promotion_end'
                , 'Product.promotion_status'
                , 'Product.code'
                , 'Product.product_rank_id'
                , 'Product.quantity', 'Product.freeshopping','Product.unit'
            ),
            'conditions' => $condition,
            'limit' => empty($options['limit']) ? 15 : $options['limit'],
        );
        $products = $this->find('all', $params);
        if (!empty($products) && sizeof($products) > 0) {
            $product_ids = $this->getproduct_ids($products);
            $product_codes = $this->getproduct_codes($products);
            $comment = ClassRegistry::init('Comment');
            $user_like = ClassRegistry::init('UserLike');
            $ProductI18n = ClassRegistry::init('ProductI18n');
            $SkuProduct = ClassRegistry::init('SkuProduct');
            $price_range=$SkuProduct->sku_price_range($product_codes);
            
            $comment_num = $comment->find('all', array('conditions' => array('Comment.type_id' => $product_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
            $like_num = $user_like->find('all', array('conditions' => array('UserLike.type' => 'P', 'UserLike.type_id' => $product_ids), 'fields' => array('UserLike.type_id', 'count(UserLike.type_id) as num'), 'group' => 'UserLike.type_id'));
            $name = $ProductI18n->find('all', array('conditions' => array('ProductI18n.product_id' => $product_ids, 'ProductI18n.locale' => LOCALE), 'fields' => array('ProductI18n.name', 'ProductI18n.product_id')));
            //pr($name);die;
            foreach ($products as $k => $v) {
                $this->ids[$v['Product']['id']] = $v['Product']['id'];
                foreach ($name as $name_k => $name_v) {
                    if ($products[$k]['Product']['id'] == $name_v['ProductI18n']['product_id']) {
                        $products[$k]['Product']['name'] = $name_v['ProductI18n']['name'];
                    }
                }
                foreach ($like_num as $like_k => $like_v) {
                    if ($products[$k]['Product']['id'] == $like_v['UserLike']['type_id']) {
                        $products[$k]['Product']['like_num'] = $like_v[0]['num'];
                    }
                }
                foreach ($comment_num as $com_k => $com_v) {
                    if ($products[$k]['Product']['id'] == $com_v['Comment']['type_id']) {
                        $products[$k]['Product']['Commentnum'] = $com_v[0]['Commentnum'];
                    }
                }
                if(isset($price_range[$v['Product']['code']])){
			   $products[$k]['price_range'] = $price_range[$v['Product']['code']];
		  }
            }
            if (!empty($options['set'])) {
                $this->viewVars[1][$options['set']] = $products;
            } else {
                return $products;
            }
        }
    }

    /**
     * 函数recommand 推荐商品.
     *
     * @param $number 商品数量
     * @param $locale 商品语言
     * @param $category_ids 分类商品号
     * @param $condition 商品信息
     * @param $params 设置商品排序
     * @param $products 获取商品信息
     *
     * @return $products 商品列表
     */
    public function recommand($options = array(), $filter_condition = array())
    {
        $condition = array('Product.status' => '1',
            'Product.alone' => '1',
            'Product.forsale' => '1',
            'Product.recommand_flag' => '1',
        );
        if (!empty($options['category_id'])) {
            $condition['or']['Product.category_id'] = $options['category_id'];
        }
        if (!empty($options['expand_pids'])) {
            $condition['or']['Product.id'] = $options['expand_pids'];
        }
        if (!empty($filter_condition)) {
            $condition[] = $filter_condition;
        }
        $params = array('cache' => $this->short,'order' => array('Product.modified DESC'),
            'recursive' => -1,
            'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb','Product.img_detail'
                , 'Product.market_price'
                 , 'Product.category_id'
                , 'Product.shop_price'
                , 'Product.promotion_price'
                , 'Product.promotion_start'
                , 'Product.promotion_end'
                , 'Product.promotion_status'
                , 'Product.code'
                , 'Product.quantity'
                , 'Product.product_rank_id', 'Product.freeshopping','Product.unit'
            ),
            'conditions' => $condition,
            'limit' => empty($options['limit']) ? 15 : $options['limit'],
        );
        $products = $this->find('all', $params);
        if (!empty($products) && sizeof($products) > 0) {
            $product_ids = $this->getproduct_ids($products);
            $product_codes = $this->getproduct_codes($products);
            $comment = ClassRegistry::init('Comment');
            $user_like = ClassRegistry::init('UserLike');
            $ProductI18n = ClassRegistry::init('ProductI18n');
            $product_codes = $this->getproduct_codes($products);
            $SkuProduct = ClassRegistry::init('SkuProduct');
            $price_range=$SkuProduct->sku_price_range($product_codes);
            
            $comment_num = $comment->find('all', array('conditions' => array('Comment.type_id' => $product_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
            $like_num = $user_like->find('all', array('conditions' => array('UserLike.type' => 'P', 'UserLike.type_id' => $product_ids), 'fields' => array('UserLike.type_id', 'count(UserLike.type_id) as num'), 'group' => 'UserLike.type_id'));
            $name = $ProductI18n->find('all', array('conditions' => array('ProductI18n.product_id' => $product_ids, 'ProductI18n.locale' => LOCALE), 'fields' => array('ProductI18n.name', 'ProductI18n.product_id')));

            foreach ($products as $k => $v) {
                $this->ids[$v['Product']['id']] = $v['Product']['id'];
                foreach ($name as $name_k => $name_v) {
                    if ($products[$k]['Product']['id'] == $name_v['ProductI18n']['product_id']) {
                        $products[$k]['Product']['name'] = $name_v['ProductI18n']['name'];
                    }
                }
                foreach ($like_num as $like_k => $like_v) {
                    if ($products[$k]['Product']['id'] == $like_v['UserLike']['type_id']) {
                        $products[$k]['Product']['like_num'] = $like_v[0]['num'];
                    }
                }
                foreach ($comment_num as $com_k => $com_v) {
                    if ($products[$k]['Product']['id'] == $com_v['Comment']['type_id']) {
                        $products[$k]['Product']['Commentnum'] = $com_v[0]['Commentnum'];
                    }
                }
                if(isset($price_range[$v['Product']['code']])){
			   $products[$k]['price_range'] = $price_range[$v['Product']['code']];
		  }
            }
            if (!empty($options['set'])) {
                $this->viewVars[1][$options['set']] = $products;
            } else {
                return $products;
            }
        }
    }

    /**
     * 函数recommand 销量商品排行列表.
     *
     * @param $category_ids 分类商品号
     * @param $options 商品信息
     * @param $params 设置商品排序
     * @param $filter_condition 筛选条件
     *
     * @return $products 商品列表
     */
    public function sale_stat($options = array(), $filter_condition = array())
    {
        $condition = array('Product.status' => '1',
            'Product.alone' => '1',
            'Product.forsale' => '1',
        );
        if (!empty($options['category_id'])) {
            $condition['or']['Product.category_id'] = $options['category_id'];
        }
        if (!empty($options['expand_pids'])) {
            $condition['or']['Product.id'] = $options['expand_pids'];
        }
        if (!empty($filter_condition)) {
            $condition[] = $filter_condition;
        }
        $params = array('cache' => $this->short,'order' => array('Product.sale_stat DESC'),
            'recursive' => -1,
            'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb','Product.img_detail'
                , 'Product.market_price'
                 , 'Product.category_id'
                , 'Product.shop_price'
                , 'Product.promotion_price'
                , 'Product.promotion_start'
                , 'Product.promotion_end'
                , 'Product.promotion_status'
                , 'Product.code'
                , 'Product.quantity'
                , 'Product.product_rank_id', 'Product.freeshopping','Product.unit'
            ),
            'conditions' => $condition,
            'limit' => empty($options['limit']) ? 15 : $options['limit'],
        );
        $products = $this->find('all', $params);
        if (!empty($products) && sizeof($products) > 0) {
            $product_ids = $this->getproduct_ids($products);
            $product_codes = $this->getproduct_codes($products);
            $comment = ClassRegistry::init('Comment');
            $user_like = ClassRegistry::init('UserLike');
            $ProductI18n = ClassRegistry::init('ProductI18n');
            $SkuProduct = ClassRegistry::init('SkuProduct');
            $price_range=$SkuProduct->sku_price_range($product_codes);
            
            $comment_num = $comment->find('all', array('conditions' => array('Comment.type_id' => $product_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
            $like_num = $user_like->find('all', array('conditions' => array('UserLike.type' => 'P', 'UserLike.type_id' => $product_ids), 'fields' => array('UserLike.type_id', 'count(UserLike.type_id) as num'), 'group' => 'UserLike.type_id'));
            $name = $ProductI18n->find('all', array('conditions' => array('ProductI18n.product_id' => $product_ids, 'ProductI18n.locale' => LOCALE), 'fields' => array('ProductI18n.name', 'ProductI18n.product_id')));
            foreach ($products as $k => $v) {
                $this->ids[$v['Product']['id']] = $v['Product']['id'];
                foreach ($name as $name_k => $name_v) {
                    if ($products[$k]['Product']['id'] == $name_v['ProductI18n']['product_id']) {
                        $products[$k]['Product']['name'] = $name_v['ProductI18n']['name'];
                    }
                }
                foreach ($like_num as $like_k => $like_v) {
                    if ($products[$k]['Product']['id'] == $like_v['UserLike']['type_id']) {
                        $products[$k]['Product']['like_num'] = $like_v[0]['num'];
                    }
                }
                foreach ($comment_num as $com_k => $com_v) {
                    if ($products[$k]['Product']['id'] == $com_v['Comment']['type_id']) {
                        $products[$k]['Product']['Commentnum'] = $com_v[0]['Commentnum'];
                    }
                }
                if(isset($price_range[$v['Product']['code']])){
			   $products[$k]['price_range'] = $price_range[$v['Product']['code']];
		  }
            }
            if (!empty($options['set'])) {
                $this->viewVars[1][$options['set']] = $products;
            } else {
                return $products;
            }
        }
    }

    public function price($options = array(), $filter_condition = array())
    {
        $condition = array('Product.status' => '1',
            'Product.alone' => '1',
            'Product.forsale' => '1',
        );
        if (!empty($options['category_id'])) {
            $condition['or']['Product.category_id'] = $options['category_id'];
        }
        if (!empty($options['expand_pids'])) {
            $condition['or']['Product.id'] = $options['expand_pids'];
        }
        if (!empty($filter_condition)) {
            $condition[] = $filter_condition;
        }
        $params = array('cache' => $this->short,'order' => array('Product.shop_price DESC'),
            'recursive' => -1,
            'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb','Product.img_detail'
                , 'Product.market_price'
                 , 'Product.category_id'
                , 'Product.shop_price'
                , 'Product.promotion_price'
                , 'Product.promotion_start'
                , 'Product.promotion_end'
                , 'Product.promotion_status'
                , 'Product.code'
                , 'Product.quantity'
                , 'Product.product_rank_id', 'Product.freeshopping','Product.unit'
            ),
            'conditions' => $condition,
            'limit' => empty($options['limit']) ? 15 : $options['limit'],
        );
        $products = $this->find('all', $params);

        if (!empty($products) && sizeof($products) > 0) {
            $product_ids = $this->getproduct_ids($products);
            $product_codes = $this->getproduct_codes($products);
            
            $comment = ClassRegistry::init('Comment');
            $user_like = ClassRegistry::init('UserLike');
            $ProductI18n = ClassRegistry::init('ProductI18n');
            $SkuProduct = ClassRegistry::init('SkuProduct');
            $price_range=$SkuProduct->sku_price_range($product_codes);
            $comment_num = $comment->find('all', array('conditions' => array('Comment.type_id' => $product_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
            $like_num = $user_like->find('all', array('conditions' => array('UserLike.type' => 'P', 'UserLike.type_id' => $product_ids), 'fields' => array('UserLike.type_id', 'count(UserLike.type_id) as num'), 'group' => 'UserLike.type_id'));
            $name = $ProductI18n->find('all', array('conditions' => array('ProductI18n.product_id' => $product_ids), 'fields' => array('ProductI18n.name', 'ProductI18n.product_id')));
            foreach ($products as $k => $v) {
                $this->ids[$v['Product']['id']] = $v['Product']['id'];
                foreach ($name as $name_k => $name_v) {
                    if ($products[$k]['Product']['id'] == $name_v['ProductI18n']['product_id']) {
                        $products[$k]['Product']['name'] = $name_v['ProductI18n']['name'];
                    }
                }
                foreach ($like_num as $like_k => $like_v) {
                    if ($products[$k]['Product']['id'] == $like_v['UserLike']['type_id']) {
                        $products[$k]['Product']['like_num'] = $like_v[0]['num'];
                    }
                }
                foreach ($comment_num as $com_k => $com_v) {
                    if ($products[$k]['Product']['id'] == $com_v['Comment']['type_id']) {
                        $products[$k]['Product']['Commentnum'] = $com_v[0]['Commentnum'];
                    }
                }
                if(isset($price_range[$v['Product']['code']])){
			   $products[$k]['price_range'] = $price_range[$v['Product']['code']];
		  }
            }
            if (!empty($options['set'])) {
                $this->viewVars[1][$options['set']] = $products;
            } else {
                return $products;
            }
        }
    }

    /**
     * 函数products_tab 商品分种类（新品，促销，销量，推荐等）列表.
     *
     * @param $options 查询条件
     * @param $sale 列表中是否包含销量
     * @param $price 列表中是否包含单价
     * @param $mode 商品信息
     * @param $filter_condition 过滤条件
     *
     * @return $products_tab 商品分类列表
     */
    public function products_tab($options = array(), $sale = true, $price = true, $recommend = true, $new_arrival = true, $mode = 'w', $filter_condition = array())
    {
        $products_tab = array();
        $sub_options = array();
        if (!empty($options['category_id'])) {
            $sub_options['category_id'] = $options['category_id'];
        }
        if (!empty($options['ControllerObj'])) {
            //控制器对象
            if (isset($options['ControllerObj']->configs['promotion_count'])) {
                $sub_options['limit'] = $options['ControllerObj']->configs['promotion_count'];
            }
        }
        if ($new_arrival) {
            $products_tab['new_arrival'] = $this->newarrival($sub_options, $filter_condition);
        }
        $products_tab['promotion'] = $this->promotion($sub_options, $filter_condition);
        if ($recommend) {
            $products_tab['recommend'] = $this->recommand($sub_options, $filter_condition);
        }
        //pr($products_tab['recommend']);die;
        if ($sale) {
            //pr($sale);die;
            $products_tab['sales'] = $this->sale_stat($sub_options, $filter_condition);
        }

        if ($price) {
            $products_tab['price'] = $this->price($sub_options, $filter_condition);
        }
        if ($mode == 'w') {
            if (!empty($products_tab)) {
                foreach ($products_tab as $k => $v) {
                    if (empty($v)) {
                        unset($products_tab[$k]);
                    }
                }
            }
        }
        if (!empty($options['set'])) {
            $this->viewVars[2][$options['set']] = $products_tab;
        } else {
            return $products_tab;
        }
    }

    /**
     * 函数search 搜索商品.
     *
     * @param $locale 商品语言
     * @param $keyword 关键字
     * @param $num 数量
     * @param $condition 商品信息
     * @param $params 设置排序
     * @param $result 获取所需商品
     *
     * @return $result 获取要搜索的商品
     */
    public function search($locale, $keyword, $num = 10)
    {
        $condition = array(
            'OR' => array(
                array("Product.code like '%$keyword%' "),
                array("ProductI18n.name like '%$keyword%' "),
                array("ProductI18n.description like '%$keyword%' "),
            ),
            'AND' => array('Product.status' => '1',
                'Product.alone' => '1',
                'Product.forsale' => '1', ),
        );
        $params = array('cache' => $this->short,'order' => array('Product.modified DESC'),
            'fields' => array('Product.id', 'Product.img_thumb','Product.img_detail', 'Product.code', 'ProductI18n.name', 'Product.freeshopping'),
            'conditions' => $condition,
            'limit' => $num,
        );
        $result = $this->find('all', $params, $this->name.'_search_'.$locale);
        foreach ($result as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }
        //  $result=$this->findall($condition);


        return $result;
    }
    /**
     * 函数search 搜索商品.
     *
     * @param $keyword 关键字
     * @param $num 数量
     * @param $condition 商品信息
     * @param $params 设置排序
     * @param $result 获取所需商品
     *
     * @return $result 获取要搜索的商品
     */
    public function auto_search($keyword, $num = 10)
    {
        $condition = array(
            'OR' => array(
                array("Product.code like '%$keyword%' "),
                array("ProductI18n.name like '%$keyword%' "),
                array("ProductI18n.description like '%$keyword%' "),
            ),
            'AND' => array('Product.status' => '1',
                'Product.forsale' => '1', ),
        );
        $params = array('order' => array('Product.modified DESC'),
            'fields' => array('Product.id' , 'Product.code' ,'ProductI18n.name'),
            'conditions' => $condition,
            'limit' => $num,
            'recursive' => 0,
        );
        $result = $this->find('all', $params);

        return $result;
    }
    /**
     * 函数findassoc,获取商品协议.
     *
     * @param $locale 商品语言
     * @param $condition 商品身份
     * @param $lists  商品列表
     * @param $lists_formated 商品号列表
     *
     * @return $lists_formated 商品号列表
     */
    public function findassoc($locale)
    {
        $condition = "Product.status ='1'";

        $lists = $this->findAll($condition);
        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['Product']['id']] = $v;
                if (!in_array($v['Product']['id'], $this->ids)) {
                    $this->ids[$v['Product']['id']] = $v['Product']['id'];
                }
            }
        }

        return $lists_formated;
    }

    /**
     * 函数sub_str 用于截取商品内容加以处理.
     *
     * @todo 公共函数放到controllers中
     *
     * @param $str 商品内容
     * @param $length 长度
     * @param $append 路径可用
     * @param $strlength 计算长度
     * @param $newstr 截取后的商品内容
     *
     * @return $newstr 截取后的商品内容
     */
    public function sub_str($str, $length = 0, $append = true)
    {
        $str = trim($str);
        $strlength = strlen($str);

        if ($length == 0 || $length >= $strlength) {
            return $str;
        } elseif ($length < 0) {
            $length = $strlength + $length;
            if ($length < 0) {
                $length = $strlength;
            }
        }

        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, 0, $length, 'utf-8');
        } elseif (function_exists('iconv_substr')) {
            $newstr = iconv_substr($str, 0, $length, 'utf-8');
        } else {
            //$newstr = trim_right(substr($str, 0, $length));
            $newstr = substr($str, 0, $length);
        }

        if ($append && $str != $newstr) {
            $newstr .= '...';
        }

        return $newstr;
    }

    /**
     * 函数 localeformat 商品陈列形式.
     *
     * @todo $db 要注明
     *
     * @param $id 商品号
     * @param $db 商品数据
     * @param $lists 商品列表
     * @param $lists_formated 商品陈列的形式
     * @param $category_info 商品分类信息
     * @reurn $lists_formated 商品列表形式
     */
    public function localeformat($id, $db)
    {
        $lists = $this->findAll("Product.id = '".$id."'");

        //	pr($lists);
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $category_info = $db->ProductsCategory->cache_find('ProductsCategory.product_id ='.$v['Product']['id'].' and ProductsCategory.category_id ='.$v['Product']['category_id']);
            $v['Product']['promotion_start'] = substr($v['Product']['promotion_start'], 0, 10);
            $v['Product']['promotion_end'] = substr($v['Product']['promotion_end'], 0, 10);
            $lists_formated['Product'] = $v['Product'];
            if (isset($category_info['ProductsCategory'])) {
                $lists_formated['ProductsCategory'] = $category_info['ProductsCategory'];
            }
            //	 $lists_formated['ProviderProduct']=$v['ProviderProduct'];
            $lists_formated['ProductI18n'][] = $v['ProductI18n'];
            foreach ($lists_formated['ProductI18n'] as $key => $val) {
                $lists_formated['ProductI18n'][$val['locale']] = $val;
            }
        }
        //	pr($lists_formated);
        return $lists_formated;
    }

    /**
     * 函数 user_price 用户等级对应的商品价格.
     *
     * @todo $k ,$v,$db 不明了
     *
     * @param $k 用户等级
     * @param $v 商品号
     * @param $db 数据库内容
     * @param $product_rank 商品等级
     * @param $user_rank_list 商品目录
     * @param $is_rank 商品排序
     *
     * @return $products 商品信息
     */
    public function user_price($k, $v, $db)
    {
        $product_rank = $db->ProductRank->findall('ProductRank.product_id ='.$v['Product']['id']);
        $user_rank_list = $db->UserRank->findrank();
        if (isset($product_rank) && sizeof($product_rank) > 0) {
            $is_rank = array();
            foreach ($product_rank as $a => $b) {
                $is_rank[$b['ProductRank']['rank_id']]['is_default_rank'] = $b['ProductRank']['is_default_rank'];
                $is_rank[$b['ProductRank']['rank_id']]['price'] = $b['ProductRank']['product_price'];
            }
        }
        foreach ($user_rank_list as $a => $b) {
            if (isset($is_rank[$b['UserRank']['id']]) && $is_rank[$b['UserRank']['id']]['is_default_rank'] == 0) {
                $user_rank_list[$a]['UserRank']['user_price'] = $is_rank[$b['UserRank']['id']]['price'];
            } else {
                $user_rank_list[$a]['UserRank']['user_price'] = ($user_rank_list[$a]['UserRank']['discount'] / 100) * ($v['Product']['shop_price']);
            }
            if (isset($_SESSION['User']['User']['rank']) && $b['UserRank']['id'] == $_SESSION['User']['User']['rank']) {
                $products[$k]['Product']['user_price'] = $user_rank_list[$a]['UserRank']['user_price'];
                //$this->set('my_product_rank',$user_rank_list[$kk]['UserRank']['user_price']);
            }
        }
        if (isset($products[$k]['Product']['user_price'])) {
            return $products[$k]['Product']['user_price'];
        } else {
            return;
        }
    }

    /**
     * 函数locale_price 商品陈列价.
     *
     * @param $id 商品号
     * @param $shop_price 商品店价
     * @param $db 商品数据信息
     * @param $product_price 商品本质价格
     *
     * @return $shop_price 商品入店后价格
     */
    public function locale_price($id, $shop_price, $db)
    {
        return $shop_price;
        if (isset($db->configs['mlti_currency_module']) && $db->configs['mlti_currency_module'] == 1) {
            $product_price = $db->ProductLocalePrice->cache_find('ProductLocalePrice.product_id ='.$id." and ProductLocalePrice.status = '1' and ProductLocalePrice.locale = '".$db->locale."'");
            if (isset($product_price['ProductLocalePrice']['product_price'])) {
                return $product_price['ProductLocalePrice']['product_price'];
            } else {
                return $shop_price;
            }
        } else {
            return $shop_price;
        }
    }

    /**
     * 函数top_products 顶级商品.
     *
     * @param $locale 商品语言
     * @param $size 商品大小
     * @param $top_products 顶级商品信息
     *
     * @return $top_products 顶级商品
     */
    public function top_products($locale, $size)
    {
        $top_products = $this->find('all', array('cache' => $this->short, 'order' => array('Product.sale_stat desc'),
                    'fields' => array('Product.id', 'Product.code', 'Product.market_price', 'Product.img_thumb', 'Product.img_detail', 'Product.img_detail', 'Product.shop_price',
                        'ProductI18n.name', 'Product.freeshopping',
                    ),
                    'conditions' => array('Product.status' => '1',
                        'Product.forsale' => '1',
                        'Product.sale_stat >' => '0',
                    ),
                    'limit' => $size,
                ));
        foreach ($top_products as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $top_products;
    }

    /**
     * 函数home_category_products 国内商品分类.
     *
     * @param $home_category_ids 国内商品号
     * @param $locale 商品语言
     * @param $size 商品大小
     * @param $params 商品排序
     * @param $home_category_products 国内商品分类
     * @param $home_category_products_list 国内商品目录
     *
     * @return $home_category_products_list 国内商品目录
     */
    public function home_category_products($home_category_ids, $locale, $size)
    {
        $params = array('cache' => $this->short,'order' => array('Product.modified DESC'),
            'conditions' => array('Product.status' => 1, 'Product.forsale' => '1', 'Product.category_id' => $home_category_ids),
        );
        $home_category_products = $this->find('all', $params, $this->name.$locale.'home_category_products');
        $home_category_products_list = array();
        if (sizeof($home_category_products) > 0) {
            foreach ($home_category_products as $k => $v) {
                $home_category_products_list[$v['Product']['category_id']][] = $v;
                $this->ids[$v['Product']['id']] = $v['Product']['id'];
            }
        }

        return $home_category_products_list;
    }

    /**
     * 函数find_total 全部商品.
     *
     * @param $params 商品排列
     * @param $locale 商品语言
     * @param $total 商品总和
     *
     * @return $total 商品信息
     * */
    public function find_total($params, $locale)
    {
        $total = $this->find('count', $params, $this->name.'_find_total_'.$locale);

        return $total;
    }

    /* --------------------------------------------------------------------------------------------------------------------------------------------- */

    public function get_products($condition, $rownum, $page)
    {
        $products = $this->find('all', array('cache' => $this->short, 'recursive' => -1,
                    'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb', 'Product.img_detail', 'Product.market_price', 'Product.shop_price', 'Product.code', 'Product.point', 'Product.point_fee', 'Product.product_rank_id', 'Product.quantity', 'Product.freeshopping',
                    ),
                    'conditions' => array($condition), 'order' => array("Product.$orderby"), 'limit' => $rownum, 'page' => $page, ));
        foreach ($products as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $products;
    }

    public function find_get_products($p_ids)
    {
        $products = $this->find('all', array('fields' => array('Product.id', 'ProductI18n.name', 'Product.freeshopping'),
                    'conditions' => array('Product.id' => $p_ids), ));
        foreach ($products as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $products;
    }

    public function find_products_list($orderby, $condition, $rownum_sql, $page)
    {
        $products_list = $this->find('all', array('cache' => $this->short,
                    'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb', 'Product.img_detail', 'Product.market_price', 'Product.brand_id', 'Product.shop_price', 'Product.category_id', 'Product.promotion_price', 'Product.promotion_start', 'Product.promotion_end', 'Product.promotion_status', 'Product.code', 'Product.product_rank_id', 'Product.quantity', 'Product.freeshopping',
                        //, 'ProductDownload.url'
                    ),
                    'order' => array("Product.$orderby asc "),
                    'conditions' => array($condition),
                    'limit' => $rownum_sql,
                    'page' => $page, ));
        foreach ($products_list as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $products_list;
    }

    public function get_cache_total($condition, $locale)
    {
        $total = $this->find('count', array('cache' => $this->short, 'fields' => 'DISTINCT Product.id', 'recursive' => -1,
                    'conditions' => array($condition), ),
                        'Product_find_total_'.$locale);

        return $total;
    }

    public function find_svcart_products($id)
    {
        $svcart_products = $this->find('all',
                        array('cache' => $this->short,
                            'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb', 'Product.img_detail', 'Product.market_price', 'Product.shop_price', 'Product.promotion_price', 'Product.promotion_start', 'Product.promotion_end', 'Product.promotion_status', 'Product.code', 'Product.point', 'Product.point_fee', 'Product.product_rank_id', 'Product.freeshopping', 'Product.quantity', 'Product.freeshopping', 'Product.wholesale', 'ProductI18n.name', 'Product.extension_code', 'Product.weight', 'Product.frozen_quantity', 'Product.product_type_id', 'Product.brand_id', 'Product.coupon_type_id', 'Product.category_id',
                            ),
                            'conditions' => array('Product.id' => $id), ));
        foreach ($svcart_products as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $svcart_products;
    }

    public function find_svcart_products_list($id)
    {
        $svcart_products_list = array();
        $svcart_products = $this->find('all',
                        array('cache' => $this->short,
                            'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb', 'Product.img_detail', 'Product.file_url', 'Product.market_price', 'Product.shop_price', 'Product.promotion_price', 'Product.promotion_start', 'Product.promotion_end', 'Product.promotion_status', 'Product.code', 'Product.point', 'Product.point_fee', 'Product.product_rank_id', 'Product.freeshopping', 'Product.quantity', 'Product.freeshopping', 'Product.wholesale', 'ProductI18n.name', 'Product.extension_code', 'Product.weight', 'Product.frozen_quantity', 'Product.product_type_id', 'Product.brand_id', 'Product.coupon_type_id', 'Product.category_id', 'Product.option_type_id',
                            ),
                            'conditions' => array('Product.id' => $id), ));
        foreach ($svcart_products as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
            if ($v['Product']['option_type_id'] == '2') {
                $this->sku_arr[] = $v['Product']['id'];
            }
        }
        if (isset($svcart_products) && sizeof($svcart_products) > 0) {
            $PackageProduct = ClassRegistry::init('PackageProduct');
            foreach ($svcart_products as $k => $v) {
                $svcart_products_list[$v['Product']['id']] = $v;
                //查询套装商品
                /*$ProductPackage_infos = $PackageProduct->find('all',array('conditions'=>array("PackageProduct.product_id"=>$v['Product']['id'])));
                $svcart_products_list[$v['Product']['id']]['package_product']=$ProductPackage_infos;*/
                $params['id'] = $v['Product']['id'];
                $svcart_products_list[$v['Product']['id']]['package_product'] = $this->get_product_package_list($params);
            }
        }

        return $svcart_products_list;
    }
    public function find_pro_products($id)
    {
        $pro_products = $this->find('all', array('cache' => $this->short, 'fields' => array('Product.id', 'Product.img_thumb', 'Product.img_detail', 'Product.market_price', 'ProductI18n.name', 'Product.shop_price', 'Product.code'), 'conditions' => array('Product.id' => $id)));
        foreach ($pro_products as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $pro_products;
    }

    public function get_fav_products($condition, $orderby, $rownum_sql, $page)
    {
        $fav_products = $this->find('all', array('cache' => $this->short, 'conditions' => array($condition),
                    'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb', 'Product.img_detail', 'Product.market_price', 'Product.freeshopping', 'Product.shop_price', 'Product.promotion_price', 'Product.promotion_start', 'Product.promotion_end', 'Product.promotion_status', 'Product.code', 'Product.created', 'Product.product_rank_id', 'Product.quantity', 'ProductI18n.name',
                    ),
                    'order' => array("Product.$orderby"), 'limit' => $rownum_sql, 'page' => $page, ));
        foreach ($fav_products as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $fav_products;
    }

    public function find_topic_pro($id, $locale)
    {
        $topic_pro = $this->find('all', array('cache' => $this->short, 'order' => array('Product.modified DESC'),
                    'recursive' => -1,
                    'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb', 'Product.img_detail', 'Product.market_price', 'Product.shop_price', 'Product.freeshopping', 'Product.promotion_price', 'Product.promotion_start', 'Product.promotion_end', 'Product.promotion_status', 'Product.code', 'Product.product_rank_id', 'Product.quantity',
                    ),
                    'conditions' => array('Product.id' => $id,
                        'Product.status' => '1',
                        'Product.alone' => '1',
                        'Product.forsale' => '1',
                        ), ), 'topic_pro_'.$locale
        );
        foreach ($topic_pro as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $topic_pro;
    }

    public function find_vancl_pro($number)
    {
        $vancl_pro = $this->find('all', array('cache' => $this->short, 'order' => array('Product.modified DESC'),
                    'recursive' => -1,
                    'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb', 'Product.img_detail', 'Product.market_price', 'Product.shop_price', 'Product.promotion_price', 'Product.promotion_start', 'Product.promotion_end', 'Product.freeshopping', 'Product.promotion_status', 'Product.code', 'Product.product_rank_id', 'Product.quantity',
                    ),
                    'conditions' => array('Product.category_id' => '29',
                        'Product.status' => '1',
                        'Product.alone' => '1',
                        'Product.forsale' => '1',
                    ), 'limit' => $number, )
        );
        foreach ($vancl_pro as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $vancl_pro;
    }

    public function find_product_infos($p_ids)
    {
        $product_infos = $this->find('all', array('cache' => $this->short, 'conditions' => array('Product.id' => $p_ids), 'fields' => array('ProductI18n.name', 'Product.id')));
        foreach ($product_infos as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $product_infos;
    }

    public function get_all_point_products($locale)
    {
        $products = $this->find('all', array('conditions' => array('Product.status' => 1,
                        'Product.forsale' => 1,
                        'Product.point >' => 0, ),
                    'order' => 'Product.created DESC',
                    'fields' => array('Product.id', 'ProductI18n.name', 'Product.freeshopping'), ),
                        'all_point_products_'.$locale);
        foreach ($products as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $products;
    }

    public function get_category_brand_list($locale)
    {
        $all_category_brand_list = $this->find('all', array('cache' => $this->short, 'conditions' => array('Product.status' => 1,
                        'Product.forsale' => 1,
                        'Brand.status' => 1,
                        'Product.brand_id > ' => 0,
                        'Product.category_id <>' => 0, ),
                    'order' => array('Brand.orderby ASC', 'BrandI18n.name ASC'),
                    'group' => array('Product.brand_id', 'Brand.id', 'Product.category_id'),
                    'fields' => array('Product.brand_id', 'Product.category_id', 'Product.freeshopping','Product.unit'), ),
                        'all_category_brand_'.$locale);
        foreach ($all_category_brand_list as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $all_category_brand_list;
    }

    public function find_products($p_ids)
    {
        $products = $this->find('all', array('cache' => $this->short,
                    'fields' => array('Product.id', 'ProductI18n.name', 'Product.freeshopping'),
                    'conditions' => array('Product.id' => $p_ids), ));
        foreach ($products as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $products;
    }

    public function find_first_products_error()
    {
        $products_error = $this->find('all', array('cache' => $this->short, 'conditions' => array('Product.status' => 1, 'Product.forsale' => 1), 'limit' => 5));
        foreach ($products_error as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $products_error;
    }

    public function find_second_products_error($category_error)
    {
        $products_error = $this->find('all', array('conditions' => array('cache' => $this->short, 'Product.category_id' => $category_error, 'Product.status' => 1, 'Product.forsale' => 1), 'limit' => 5));
        foreach ($products_error as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $products_error;
    }

    public function get_product_infos_all($product_conditions, $rownum, $page)
    {
        $product_infos = $this->find('all', array('cache' => $this->short, 'conditions' => $product_conditions,
                    'limit' => $rownum,
                    'page' => $page, ));
        foreach ($product_infos as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $product_infos;
    }

    public function find_colors_gallery($product_info_code)
    {
        $colors_gallery = $this->find('all', array('cache' => $this->short, 'conditions' => array('Product.style_code' => $product_info_code, 'Product.is_colors_gallery' => 1, 'Product.status' => 1, 'Product.forsale' => '1'),
                    'order' => 'Product.modified DESC',
                    'fields' => array('Product.colors_gallery', 'Product.id', 'Product.code', 'ProductI18n.style_name', 'Product.freeshopping'),
                ));
        foreach ($colors_gallery as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $colors_gallery;
    }

    public function find_alsoboughts($id)
    {
        $alsoboughts = $this->find('all', array('cache' => $this->short, 'conditions' => array('Product.id' => $id),
                    'fields' => array('Product.id', 'Product.img_thumb', 'Product.market_price', 'Product.shop_price', 'Product.quantity', 'Product.code', 'ProductI18n.name', 'Product.freeshopping',
                    ),
                    'limit' => 4,
                ));
        foreach ($alsoboughts as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $alsoboughts;
    }

    public function find_products_by_sql($condition, $orderby, $rownum_sql, $page)
    {
        $products = $this->find('all', array('cache' => $this->short,
                    //	'recursive' => -1,
                    'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb', 'Product.market_price', 'Product.freeshopping', 'Product.shop_price', 'Product.promotion_price', 'Product.promotion_start', 'Product.brand_id', 'Product.promotion_end', 'Product.promotion_status', 'Product.code', 'Product.bestbefore', 'Product.product_rank_id', 'Product.quantity', 'Product.category_id', 'ProductI18n.id', 'ProductI18n.name', 'ProductI18n.product_id',
                    ),
                    'conditions' => $condition, 'order' => array("$orderby"),
                    'limit' => $rownum_sql, 'page' => $page, ));
        foreach ($products as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $products;
    }

    public function find_sub_category($id, $locale)
    {
        $sub_category = $this->find('all', array('cache' => $this->short, 'conditions' => array('Product.status' => 1, 'Product.alone' => '1', 'Product.forsale' => '1', 'Product.recommand_flag' => '1', 'Product.category_id' => $id), 'limit' => 4, 'fields' => array('Product.id', 'Product.shop_price', 'Product.img_thumb', 'ProductI18n.name', 'Product.code', 'Product.market_price'), 'order' => 'Product.created DESC'), 'sub_category_product_'.$id.'_'.$locale);
        foreach ($sub_category as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $sub_category;
    }

    public function find_category_products($condition, $order, $locale)
    {
        $category_new_products = $this->find('all', array('cache' => $this->short,
                    'conditions' => $condition,
                    'order' => $order,
                    'fields' => array('Product.id', 'Product.img_thumb', 'Product.freeshopping', 'Product.market_price', 'Product.shop_price', 'Product.quantity', 'Product.code', 'ProductI18n.name',
                    ), 'limit' => 8,
                        ), 'find_category_products_'.$locale);
        foreach ($category_new_products as $v) {
            $this->ids[$v['Product']['id']] = $v['Product']['id'];
        }

        return $category_new_products;
    }

    public function find_all_products($options)
    {
        $products = $this->find('all', array('cache' => $this->short,
                    'fields' => array('Product.id','Product.code', 'Product.recommand_flag', 'Product.status', 'Product.freeshopping', 'Product.img_detail', 'Product.img_thumb', 'Product.market_price', 'Product.shop_price', 'Product.custom_price', 'Product.promotion_price', 'Product.promotion_start', 'Product.promotion_end', 'Product.promotion_status', 'Product.code', 'Product.brand_id', 'Product.category_id', 'Product.product_rank_id', 'Product.quantity', 'Product.alone', 'Product.wholesale', 'Product.like_stat', 'ProductI18n.name', 'ProductI18n.seller_note', 'ProductI18n.description', 'ProductI18n.description02','Product.unit'
                    ),
                          //'order' => $options['order'], 
                    'conditions' => $options['conditions'], 'limit' => $options['limit'], 'page' => $options['page']));
        if (!empty($products)) {
        	$product_codes = $this->getproduct_codes($products);
        	$SkuProduct = ClassRegistry::init('SkuProduct');
		$price_range=$SkuProduct->sku_price_range($product_codes);
		
            foreach ($products as $k=>$v) {
			$this->ids[$v['Product']['id']] = $v['Product']['id'];
			if(isset($price_range[$v['Product']['code']])){
				$products[$k]['price_range'] = $price_range[$v['Product']['code']];
			}
            }
        }
        if (!empty($options['set'])) {
            $this->viewVars[1][$options['set']] = $products;//pr($options);
        } elseif (!empty($options['set2'])) {
            $this->viewVars[1][$options['set2']] = $products;//pr("asfd22");
        } else {
            return $products;
        }
    }

    //取上架商品数量
    public function find_forsale_products()
    {
        $id = $this->find('all', array('cache' => $this->short, 'conditions' => array('Product.forsale' => '1', 'Product.status' => '1')));
        $forsale_products = count($id);

        return $forsale_products;
    }

    //商品多语言，价格等本地标准化 todo
    public function product_locale_format($product = array(), $product_name_length = 0)
    {
        if ($product_name_length == 0) {
            $product_name_length = $this->products_name_length;
        }
        //多语言
        if (!empty($product['ProductI18n'])) {
        } elseif (isset($this->productI18ns_list[$product['Product']['id']])) {
            $product['ProductI18n'] = $this->productI18ns_list[$product['Product']['id']]['ProductI18n'];
        } else {
            $product['ProductI18n']['name'] = '';
        }
        //截取商品名称长度
        $product['ProductI18n']['sub_name'] = $this->sub_str($product['ProductI18n']['name'], $product_name_length);

        //多货币
        //if(isset($this->configs['mlti_currency_module']) && $this->configs['mlti_currency_module'] == 1 && isset($locale_price_list[$product['Product']['id']]['ProductLocalePrice']['product_price'])){
        //	$product['Product']['shop_price'] = $locale_price_list[$product['Product']['id']]['ProductLocalePrice']['product_price'];
        //}
        //用户等级价

        if (isset($this->product_ranks[$product['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($this->product_ranks[$product['Product']['id']][$_SESSION['User']['User']['rank']])) {
            if (isset($this->product_ranks[$product['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $this->product_ranks[$product['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0) {
                $product['Product']['user_price'] = $this->product_ranks[$product['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
            } elseif (isset($this->user_rank_list[$_SESSION['User']['User']['rank']])) {
                $product['Product']['user_price'] = ($this->user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount'] / 100) * ($product['Product']['shop_price']);
            }
        }
        //促销价
        if ($this->is_promotion($product)) {
            $product['Product']['shop_price'] = $product['Product']['promotion_price'];
        }

        return $product;
    }

    /**
     * 函数sale_rank 销售排行.
     *
     * @return $total 销售排行商品
     * */
    public function sale_rank()
    {
        $ranklist = $this->find('all', array('cache' => $this->short, 'conditions' => 'Product.status != 2 and Product.sale_stat > 0', 'order' => 'sale_stat desc', 'limit' => 3, 'fields' => array('Product.id', 'Product.img_thumb', 'Product.img_detail', 'ProductI18n.name')));

        return $ranklist;
    }
     /**
      * 函数sale_rget_product_ids 获取商品数组里商品id的集合.
      *
      * @return $p_ids id的集合
      * */
     public function get_product_ids($cart_products)
     {
         $p_ids = array();
         foreach ($cart_products as $k => $v) {
             if (!in_array($v['Cart']['product_id'], $p_ids)) {
                 $p_ids[] = $v['Cart']['product_id'];
             }
         }
         if (empty($p_ids)) {
             $p_ids[] = 0;
         }

         return $p_ids;
     }
     /**
      * 函数sale_rget_product_ids 获取商品数组里商品货号集合.
      *
      * @return $p_ids id的集合
      * */
     public function get_product_codes($cart_products)
     {
         $p_codes = array();
         foreach ($cart_products as $k => $v) {
             if (!in_array($v['Cart']['product_code'], $p_codes)) {
                 $p_codes[] = $v['Cart']['product_code'];
             }
         }

         return $p_codes;
     }

    /**
     * undocumented function.
     *
     * @param $cat_id
     *
     * @return $p_ids id的集合
     *
     * @author chenfan
     **/
    public function get_cat_product_ids($cat_ids)
    {
        $p_ids = $this->find('list', array('conditions' => array('Product.category_id' => $cat_ids, 'Product.status' => 1, 'Product.forsale' => 1), 'fields' => 'Product.id'));

        return $p_ids;
    }
     /**
      * undocumented function.
      *
      * @param $products
      * @param $length
      *
      * @return $products id的集合
      *
      * @author chenfan
      **/
     public function get_products_sub_names($products, $length)
     {
         foreach ($products as $kk => $vv) {
             $products[$kk]['ProductI18n']['sub_name'] = $this->sub_str($vv['ProductI18n']['name'], 20);
         }

         return $products;
     }
    /**
     *函数get_module_infos 获取首页商品信息.
     *
     * @param  查询参数集合
     *
     * @return $product_infos 根据param，返回首页商品信息数组
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['Product.category_id'] = $params['type_id'];
        }
        $conditions['Product.status'] = 1;
        $conditions['Product.forsale'] = 1;
        $conditions['Product.alone'] = 1;
        $product_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Product.'.$order, 'fields' => 'Product.id,Product.code,Product.market_price,Product.shop_price,Product.category_id,Product.img_thumb,Product.img_detail,Product.like_stat,ProductI18n.name,Product.unit'));

        $product_ids = $this->find('list', array('conditions' => $conditions, 'fields' => 'Product.id'));
        $comment = ClassRegistry::init('Comment');
        $comment_num = $comment->find('all', array('conditions' => array('Comment.type_id' => $product_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
        if (!empty($product_infos)) {
            $pro_codes=array();
            foreach ($product_infos as $k => $v) {
            	  $pro_codes[]=$v['Product']['code'];
                foreach ($comment_num as $com_k => $com_v) {
                    if ($product_infos[$k]['Product']['id'] == $com_v['Comment']['type_id']) {
                        $product_infos[$k]['Product']['Commentnum'] = $com_v[0]['Commentnum'];
                    }
                }
            }
            $SkuProduct = ClassRegistry::init('SkuProduct');
            $price_range=$SkuProduct->sku_price_range($pro_codes);
            if(!empty($price_range)){
            		foreach ($product_infos as $k => $v) {
            			if(isset($price_range[$v['Product']['code']])){
            				$product_infos[$k]['price_range'] = $price_range[$v['Product']['code']];
            			}
            		}
            }
        }
        $product_infos = $this->get_products_sub_names($product_infos, 20);
        return $product_infos;
    }
    /**
     *函数get_module_pro_info 获取商品信息.
     *
     * @param  查询参数集合
     *
     * @return $product_infos 根据param，返回商品信息数组
     */
    public function get_module_pro_info($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['Product.category_id'] = $params['type_id'];
        }
        if (isset($params['id'])) {
            $conditions['Product.id'] = $params['id'];
        }
        $conditions['Product.status'] = 1;
        $conditions['Product.forsale'] = 1;
        if ($this->check_product($params['id'])) {
            $pro_fields = array('Product.*,ProductI18n.name,ProductI18n.description,ProductI18n.description02');
            $product_infos = $this->find('first', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Product.'.$order, 'fields' => $pro_fields));
        //判断商品套装
        $PackageProduct = ClassRegistry::init('PackageProduct');
            $conditions2['PackageProduct.product_id'] = $params['id'];
            $conditions2['Product.status'] = '1';
            $cond['conditions'] = $conditions2;
            $cond['fields'] = array('Product.quantity,Product.product_type_id,Product.option_type_id,PackageProduct.package_product_qty');
            $cond['order'] = 'PackageProduct.orderby asc,Product.quantity asc';
            $cond['joins'] = array(
            array('table' => 'svoms_products',
                  'alias' => 'Product',
                  'type' => 'inner',
                  'conditions' => array('PackageProduct.package_product_id = Product.id'),
                 ),
        );

            $PackageProduct_list = $PackageProduct->find('all', $cond);
        //循环（总库存模除套装商品数量）比较大小，取最小值为显示的套装库存
        //pr($PackageProduct_list);

        $qty_arr = array();
            foreach ($PackageProduct_list as $pk => $pv) {
                $temp_qty = $pv['Product']['quantity'] / $pv['PackageProduct']['package_product_qty'];
            //pr(intval($temp_qty));
            array_push($qty_arr, intval($temp_qty));
            }
            if (!empty($PackageProduct_list) && !empty($qty_arr)) {
                $product_infos['Product']['quantity'] = min($qty_arr);
            }

            if ($product_infos['Product']['brand_id'] != 0) {
                //获取商品的品牌信息
            $Brand = ClassRegistry::init('Brand');
                $BrandInfo = $Brand->find('first', array('conditions' => array('Brand.id' => $product_infos['Product']['brand_id'])));
                if (!empty($BrandInfo)) {
                    $product_infos['BrandInfo'] = $BrandInfo;
                }
            }

        //判断是否是销售属性商品
        if ($product_infos['Product']['option_type_id'] == 2) {
            //属性组名称
            $ProductType = ClassRegistry::init('ProductType');
            $type_name = $ProductType->get_type_name($product_infos['Product']['product_type_id']);
            $product_infos['type_name'] = $type_name;
            //获取商品的销售属性
            $ProductTypeAttribute = ClassRegistry::init('ProductTypeAttribute');
            $Attribute = ClassRegistry::init('Attribute');
            $AttributeOption = ClassRegistry::init('AttributeOption');

            $joins = array(
                array(
                          'table' => 'svoms_attributes',
                          'alias' => 'Attribute',
                          'type' => 'left',
                          'conditions' => array("Attribute.status='1' and ProductTypeAttribute.attribute_id=Attribute.id"),
                    ),
                array(
                          'table' => 'svoms_attribute_i18ns',
                          'alias' => 'AttributeI18n',
                          'type' => 'left',
                          'conditions' => array("AttributeI18n.locale='".LOCALE."' and AttributeI18n.attribute_id=Attribute.id"),
                    ),
            );
            $sale_pro = $ProductTypeAttribute->find('all', array('fields' => array('Attribute.*', 'AttributeI18n.name', 'AttributeI18n.attr_value', 'AttributeI18n.default_value'), 'conditions' => array('ProductTypeAttribute.product_type_id' => $product_infos['Product']['product_type_id'], 'Attribute.type !=' => 'basic'), 'joins' => $joins, 'order' => 'ProductTypeAttribute.orderby,ProductTypeAttribute.id'));
            $attr_ids = array();
            foreach ($sale_pro as $v) {
                $attr_ids[] = $v['Attribute']['id'];
            }
            $attr_option_list = $AttributeOption->find('all', array('conditions' => array('AttributeOption.attribute_id' => $attr_ids), 'order' => 'AttributeOption.attribute_id'));
            $attr_option_info = array();
            foreach ($attr_option_list as $v) {
                $attr_option_info[$v['AttributeOption']['attribute_id']][] = $v['AttributeOption'];
            }
            foreach ($sale_pro as $k => $v) {
                $sale_pro[$k]['AttributeOption'] = isset($attr_option_info[$v['Attribute']['id']]) ? $attr_option_info[$v['Attribute']['id']] : array();
            }
//			$attr_ids=$ProductTypeAttribute->getattrids($product_infos['Product']['product_type_id']);
//			$Attribute->set_locale(LOCALE);
//			$attr_cond['Attribute.id']=$attr_ids;
//			$attr_cond['Attribute.status']=1;
//			$sale_pro=$Attribute->find('all',array('conditions'=>$attr_cond));
            if (!empty($sale_pro) && sizeof($sale_pro) > 0) {
                $attr_code_list = array();
                $attr_Id_list = array();
                foreach ($sale_pro as $v) {
                    $attr_code_list[$v['Attribute']['code']] = $v['AttributeI18n']['name'];
                    $attr_Id_list[$v['Attribute']['id']] = $v['Attribute']['code'];
                }
                //获取商品添加的销售属性商品
                $SkuProduct = ClassRegistry::init('SkuProduct');
                $product_code = $product_infos['Product']['code'];
                $sku_pro_list = $SkuProduct->find('all', array('fields' => array('SkuProduct.id', 'SkuProduct.sku_product_code', 'SkuProduct.price'), 'conditions' => array('SkuProduct.product_code' => $product_code), 'order' => 'SkuProduct.created'));
                if (!empty($sku_pro_list)) {
            		$price_range=$SkuProduct->sku_price_range($product_code);
            		if(!empty($price_range)){
            			$product_infos['price_range']=$price_range;
            		}
                    $sku_pro_code_list = array();
                    foreach ($sku_pro_list as $v) {
                        $sku_pro_code_list[] = $v['SkuProduct']['sku_product_code'];
                    }
                    $sku_product_cond['Product.code'] = $sku_pro_code_list;
                    $sku_product_cond['Product.product_type_id'] = $product_infos['Product']['product_type_id'];
                    $sku_product_cond['Product.status'] = '1';
                    $sku_product_fields = array('Product.id','Product.code','Product.shop_price','Product.quantity','Product.img_thumb','Product.img_detail','Product.img_original','Product.img_big','ProductI18n.name','Product.unit');
                    $sku_pro_infos = $this->find('all', array('fields' => $sku_product_fields, 'conditions' => $sku_product_cond, 'order' => 'Product.id'));
                    $attr_group_Info = array();
                    $pro_have_attr = array();
                    foreach ($sku_pro_infos as $v) {
                        foreach ($v['ProductAttribute'] as $vv) {
                            if (!isset($pro_have_attr[$vv['attribute_id']]) || !in_array($vv['attribute_value'], $pro_have_attr[$vv['attribute_id']])) {
                                $pro_have_attr[$vv['attribute_id']][] = $vv['attribute_value'];
                            }
                            $attr_group_Info[$v['Product']['id']][] = $vv['attribute_value'];
                        }
                    }
                    $check_attr_txt = array();
                    foreach ($attr_group_Info as $v) {
                        $check_attr_txt[] = implode(';', $v);
                    }
                    $pro_have_attr_data = array();
                    foreach ($pro_have_attr as $v) {
                        foreach ($v as $vv) {
                            foreach ($check_attr_txt as $vvv) {
                                if (strstr($vvv, $vv)) {
                                    $str_txt_arr = explode(';', $vvv);
                                    if (is_array($str_txt_arr) && sizeof($str_txt_arr) > 0) {
                                        foreach ($str_txt_arr as $attr_txt) {
                                            if ($attr_txt != $vv) {
                                                $pro_have_attr_data[$vv][] = $attr_txt;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $attrInfo = array();
                    foreach ($sale_pro as $v) {
                        $attr_Id = $v['Attribute']['id'];
                        if (isset($pro_have_attr[$attr_Id])) {
                            $attrInfo_data = array();
                            $attrInfo_data['Attribute'] = $v['Attribute'];
                            $attrInfo_data['AttributeI18n'] = $v['AttributeI18n'];
                            $attrInfo_data['AttributeI18n']['attr_value'] = implode("\n", $pro_have_attr[$attr_Id]);
                            $attrInfo[] = $attrInfo_data;
                        }
                    }
                    //pr($attrInfo);
                    $product_infos['sale_attr'] = $attrInfo;
                    $product_infos['attr_check'] = $pro_have_attr_data;
                }
            }
        } elseif ($product_infos['Product']['option_type_id'] == 0) {
            //获取商品基本属性
            $ProductAttribute = ClassRegistry::init('ProductAttribute');
            $Attribute = ClassRegistry::init('Attribute');
            $Attribute->set_locale(LOCALE);
            $attr_ids = $ProductAttribute->find('list', array('fields' => array('ProductAttribute.attribute_id'), 'conditions' => array('ProductAttribute.product_id' => $product_infos['Product']['id'])));

            $attr_cond['Attribute.id'] = $attr_ids;
            $attr_cond['Attribute.type'] = array('basic','special');
            $comm_pro_attr = $Attribute->find('all', array('conditions' => $attr_cond));
            if (!empty($comm_pro_attr)) {
                $comm_pro_attr_info = array();
                foreach ($comm_pro_attr as $v) {
                    $comm_pro_attr_info[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
                }
                $product_infos['comm_pro_attr'] = $comm_pro_attr_info;
            }
        }
        //用于循环套装商品的销售属性
        if (count($PackageProduct_list) > 0) {
            $pkg_sale = array();
            $sale_attr = array();
            foreach ($PackageProduct_list as $kk => $vv) {
                //判断是否是销售属性商品
                if ($vv['Product']['option_type_id'] == 2) {
                    //获取商品的销售属性
                    $ProductTypeAttribute = ClassRegistry::init('ProductTypeAttribute');
                    $Attribute = ClassRegistry::init('Attribute');
                    $ProductType = ClassRegistry::init('ProductType');
                    $AttributeOption = ClassRegistry::init('AttributeOption');
                    $type_name = $ProductType->get_type_name($vv['Product']['product_type_id']);

                    $joins = array(
                        array(
                                  'table' => 'svoms_attributes',
                                  'alias' => 'Attribute',
                                  'type' => 'left',
                                  'conditions' => array("Attribute.status='1' and ProductTypeAttribute.attribute_id=Attribute.id"),
                            ),
                        array(
                                  'table' => 'svoms_attribute_i18ns',
                                  'alias' => 'AttributeI18n',
                                  'type' => 'left',
                                  'conditions' => array("AttributeI18n.locale='".LOCALE."' and AttributeI18n.attribute_id=Attribute.id"),
                            ),
                    );
                    $pkg_sale_pro = $ProductTypeAttribute->find('all', array('fields' => array('Attribute.*', 'AttributeI18n.name', 'AttributeI18n.attr_value', 'AttributeI18n.default_value'), 'conditions' => array('ProductTypeAttribute.product_type_id' => $vv['Product']['product_type_id']), 'joins' => $joins, 'order' => 'ProductTypeAttribute.orderby,ProductTypeAttribute.id'));
                    $attr_ids = array();
                    foreach ($pkg_sale_pro as $v) {
                        $attr_ids[] = $v['Attribute']['id'];
                    }
                    $attr_option_list = $AttributeOption->find('all', array('conditions' => array('AttributeOption.attribute_id' => $attr_ids), 'order' => 'AttributeOption.attribute_id'));
                    $attr_option_info = array();
                    foreach ($attr_option_list as $v) {
                        $attr_option_info[$v['AttributeOption']['attribute_id']][] = $v['AttributeOption'];
                    }
                    foreach ($pkg_sale_pro as $k => $v) {
                        $pkg_sale_pro[$k]['AttributeOption'] = isset($attr_option_info[$v['Attribute']['id']]) ? $attr_option_info[$v['Attribute']['id']] : array();
                    }
//					$attr_ids=$ProductTypeAttribute->getattrids($vv['Product']['product_type_id']);
//					$Attribute->set_locale(LOCALE);
//					$attr_cond['Attribute.id']=$attr_ids;
//					$attr_cond['Attribute.status']=1;
//					$pkg_sale_pro=$Attribute->find('all',array('conditions'=>$attr_cond));
                    if (!empty($pkg_sale_pro) && sizeof($pkg_sale_pro) > 0) {
                        $attr_code_list = array();
                        $attr_Id_list = array();

                        foreach ($pkg_sale_pro as $v) {
                            $attr_code_list[$v['Attribute']['code']] = $v['AttributeI18n']['name'];
                            $attr_Id_list[$v['Attribute']['id']] = $v['Attribute']['code'];
                            if ($v['Attribute']['type'] == 'buy') {
                                $sale_attr[] = $v;
                            }
                        }
                    }
                    $pkg_sale[$type_name] = $pkg_sale_pro;
                }
            }
            if (!empty($sale_attr)) {
                foreach ($sale_attr as $sk => $sv) {
                    if (!empty($sv['AttributeOption'])) {
                        $temp_arr = array();
                        foreach ($sv['AttributeOption'] as $skk => $svv) {
                            array_push($temp_arr, $svv['option_value']);
                        }
                        //pr($temp_arr);
                        asort($temp_arr);
                        $sale_attr[$sk]['AttributeI18n']['attr_value'] = implode("\n", $temp_arr);
                    }
                }
            }
            //pr($sale_attr);
            $product_infos['sale_attr'] = $sale_attr;
            $product_infos['pkg_attr'] = $pkg_sale;
        }
        //pr($pkg_sale);
        if (isset($params['id'])) {
            $p_id = $params['id'];
            //批发价数据
            $ProductVolume = ClassRegistry::init('ProductVolume');
            $pv_infos = $ProductVolume->find('all', array('conditions' => array('ProductVolume.product_id' => $p_id), 'order' => 'ProductVolume.volume_number', 'fields' => 'ProductVolume.volume_number,ProductVolume.volume_price'));
        }
        //pr($sale_pro);
        $product_infos['attr'] = $sale_pro;
            $product_infos['select'] = $p_attr;

            return $product_infos;
        }
    }
    /**
     *函数get_module_pro_image 获取商品大图.
     *
     * @param  查询参数集合
     *
     * @return $product_image 根据param，返回商品大图数组
     */
    public function get_module_pro_image($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['Product.category_id'] = $params['type_id'];
        }
        if (isset($params['id'])) {
            $conditions['Product.id'] = $params['id'];
        }
        $conditions['Product.status'] = 1;
        $conditions['Product.forsale'] = 1;
        if ($this->check_product($params['id'])) {
            $product_image = $this->find('first', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Product.'.$order, 'fields' => 'Product.id,Product.code,Product.img_thumb,Product.img_detail,Product.img_big'));
            /*
                评分选项
            */
            $Score = ClassRegistry::init('Score');
            $Score->set_locale(LOCALE);
            $score_conditions['Score.status'] = 1;
            $score_conditions['Score.type'] = 'P';
            $score_conditions['ScoreI18n.value !='] = '';
            $Score_list = $Score->find('all', array('conditions' => $score_conditions));
            $product_image['Score'] = $Score_list;

            /*
                计算平均分
            */
            $ScoreLog = ClassRegistry::init('ScoreLog');
            $_scorelog_list = $ScoreLog->find('all', array('fields' => array('count(value) as countnum', 'sum(value) as sumnum', 'ScoreLog.score_id'), 'conditions' => array('ScoreLog.type' => 'P', 'ScoreLog.type_id' => $params['id']), 'group' => 'ScoreLog.score_id'));
            $scorelog_list = array();
            if (!empty($_scorelog_list) && sizeof($_scorelog_list) > 0) {
                foreach ($_scorelog_list as $k => $v) {
                    $v[0]['average'] = $v[0]['sumnum'] / $v[0]['countnum'];
                    $scorelog_list[$v['ScoreLog']['score_id']] = $v[0];
                }
            }
            $product_image['Average'] = $scorelog_list;

            return $product_image;
        }
    }
    /**
     *函数get_module_pro_detail 获取商品详细描述.
     *
     * @param  查询参数集合
     *
     * @return $product_detail 根据param，返回商品详细数组
     */
    public function get_module_pro_detail($params)
    {
    	 $result=array();
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['Product.category_id'] = $params['type_id'];
        }
        if (isset($params['id'])) {
            $conditions['Product.id'] = $params['id'];
        }
        $conditions['Product.status'] = 1;
        $conditions['Product.forsale'] = 1;
        if ($this->check_product($params['id'])) {
            $product_detail = $this->find('first', array('conditions' => $conditions, 'order' => 'Product.'.$order, 'fields' => 'Product.id,Product.category_id,ProductI18n.name,ProductI18n.seller_note,ProductI18n.description'));
            $result['product_detail']=$product_detail;
            if(!empty($product_detail)){
            		$category_id=$product_detail['Product']['category_id'];
            		$ProductsCategory = ClassRegistry::init('ProductsCategory');
            		$other_product_category=$ProductsCategory->find('list',array('fields'=>array('ProductsCategory.category_id'),'conditions'=>array('ProductsCategory.product_id'=>$product_id)));

            		$cate_cond=$other_product_category;
            		if(!empty($category_id)){
            			$cate_cond[]=$category_id;
            		}
            		if(!empty($cate_cond)){
            			$CategoryProduct = ClassRegistry::init('CategoryProduct');
            			$category_infos=$CategoryProduct->tree('P');
            			$category_parent_data=array();
            			if(!empty($category_infos)){
            				foreach($category_infos['tree'] as $v){
            					if(!empty($v['SubCategory'])){
            						foreach($v['SubCategory'] as $vv){
            							$category_parent_data[$vv['CategoryProduct']['id']]=$v['CategoryProduct']['id'];
            							if(!empty($vv['SubCategory'])){
            								foreach($vv['SubCategory'] as $vvv){
            									$category_parent_data[$vvv['CategoryProduct']['id']]=$vv['CategoryProduct']['id'];
            								}
            							}
            						}
            					}
            				}
            				foreach($cate_cond as $v){
            					$cate_id=0;
            					if(isset($category_parent_data[$v])){
            						$cate_id=$category_parent_data[$v];
            						$cate_cond[]=$cate_id;
            						if(isset($category_parent_data[$cate_id])){
            							$cate_id=$category_parent_data[$cate_id];
    								$cate_cond[]=$cate_id;
    								if(isset($category_parent_data[$cate_id])){
    									$cate_id=$category_parent_data[$cate_id];
    									$cate_cond[]=$cate_id;
    								}
            						}
            					}
            				}
            				sort($cate_cond);
            				$category_data=array();
            				foreach($cate_cond as $v){
            					if(isset($category_infos['assoc'][$v])){
            						$category_data[]=$category_infos['assoc'][$v];
            					}
            				}
            				$result['product_category_data']=$category_data;
            			}
            		}
            }
        }
        return $result;
    }
    /**
     *函数get_module_pro_bestbefore 获取过往精品.
     *
     * @param  查询参数集合
     *
     * @return $product_detail 根据param，返回过往精品数组
     */
    public function get_module_pro_bestbefore($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['Product.category_id'] = $params['type_id'];
        }
        $conditions['Product.forsale'] = 0;
        //$conditions['Product.sale_stat'] = 0;
        $conditions['Product.alone'] = 1;
        $conditions['Product.status'] = 1;
        $conditions['Product.bestbefore'] = 1;//是过往商品
        if ($this->check_product($params['id'])) {
            $bestbefore = $this->find('all', array('conditions' => $conditions));
            $product_ids = $this->find('list', array('conditions' => $conditions, 'fields' => 'Product.id'));
            $comment = ClassRegistry::init('Comment');
            $comment_num = $comment->find('all', array('conditions' => array('Comment.type_id' => $product_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
            if (!empty($bestbefore)) {
                foreach ($bestbefore as $k => $v) {
                    foreach ($comment_num as $com_k => $com_v) {
                        if ($bestbefore[$k]['Product']['id'] == $com_v['Comment']['type_id']) {
                            $bestbefore[$k]['Product']['Commentnum'] = $com_v[0]['Commentnum'];
                        }
                    }
                }

                return $bestbefore;
            }
        }
    }

    /**
     *函数get_module_pro_relation 获取关联商品信息.
     *
     * @param  查询参数集合
     *
     * @return $product_relation 根据param，返回关联商品信息数组
     */
    public function get_module_pro_relation($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['Product.category_id'] = $params['type_id'];
        }
        if (isset($params['id'])) {
            $product_id = $params['id'];
        }
        if ($this->check_product($params['id'])) {
            $ProductRelation = ClassRegistry::init('ProductRelation');
            $ProductRelation_info = $ProductRelation->get_related_categories($product_id);
            if (!empty($ProductRelation_info) && sizeof($ProductRelation_info) > 0) {
                $product_relation = $this->find('all', array('fields' => array('Product.id', 'ProductI18n.name', 'Product.img_thumb', 'Product.img_detail', 'Product.shop_price', 'Product.market_price', 'Product.like_stat'), 'conditions' => array('Product.id' => $ProductRelation_info, 'Product.status' => '1')));
                $product_ids = $this->find('list', array('conditions' => array('Product.id' => $ProductRelation_info, 'Product.status' => '1'), 'fields' => 'Product.id'));
                $comment = ClassRegistry::init('Comment');
                $comment_num = $comment->find('all', array('conditions' => array('Comment.type_id' => $product_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
                if (!empty($product_relation)) {
                    foreach ($product_relation as $k => $v) {
                        foreach ($comment_num as $com_k => $com_v) {
                            if ($product_relation[$k]['Product']['id'] == $com_v['Comment']['type_id']) {
                                $product_relation[$k]['Product']['Commentnum'] = $com_v[0]['Commentnum'];
                            }
                        }
                    }

                    return $bestbefore;
                }
            }
        }
    }

    /**
     *函数get_div_product_category 获取该商品所在分类下的其他商品.
     *
     * @param  查询参数集合
     *
     * @return $product_relation 根据param，返回其他商品数组
     */
    public function get_module_pro_category($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['ControllerObj'])) {
            if (isset($params['ControllerObj']->configs['related_products_number'])) {
                $limit = $params['ControllerObj']->configs['related_products_number'];
            }
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['pass'])) {
            $conditions['Product.category_id'] = $params['pass'];
        }
        if (isset($params['id'])) {
            $conditions['Product.id <>'] = $params['id'];
        }
        $conditions['Product.forsale'] = 1;
        $conditions['Product.alone'] = 1;
        $conditions['Product.status'] = 1;
        if ($this->check_product($params['id'])) {
            $cproducts = $this->find('all', array('conditions' => $conditions, 'fields' => 'Product.id,Product.img_thumb,Product.img_detail,ProductI18n.name,Product.like_stat,Product.shop_price,Product.unit', 'limit' => $limit));
            if (!empty($cproducts)) {
                $cproduct_ids = $this->find('list', array('conditions' => $conditions, 'fields' => 'Product.id', 'limit' => $limit));
                $comment = ClassRegistry::init('Comment');
                $comment_num = $comment->find('all', array('conditions' => array('Comment.type_id' => $cproduct_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
                foreach ($cproducts as $k => $v) {
                    foreach ($comment_num as $kk => $vv) {
                        if ($cproducts[$k]['Product']['id'] == $vv['Comment']['type_id']) {
                            $cproducts[$k]['Product']['Commentnum'] = $vv[0]['Commentnum'];
                        }
                    }
                }
            }

            return $cproducts;
        }
    }
    /**
     * 函数get_module_products_comment_infos方法，获取模块商品评论数据.
     *
     * @param  查询参数集合
     *
     * @return $comment_infos 根据param，返回商品评论数组
     */
    public function get_module_pro_comment_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['ControllerObj'])) {
            if (isset($params['ControllerObj']->configs['comments_number'])) {
                $limit = $params['ControllerObj']->configs['comments_number'];
            }
        }
        $order = 'created desc';
        if (isset($params['order'])) {
            $order = 'created desc';
        }
        if (isset($params['id'])) {
            $conditions['Comment.type_id'] = $params['id'];
        }
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        $conditions['Comment.type'] = 'P';
        $conditions['Comment.parent_id'] = 0;
        $conditions['Comment.status'] = 1;
        if ($this->check_product($params['id'])) {
            $ProductComment = ClassRegistry::init('Comment');
            //分页start
            $total = $ProductComment->find('count', array('conditions' => $conditions));
            App::import('Component', 'Paginationmodel');
            $pagination = new PaginationModelComponent();
            //get参数
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
            $parameters['route'] = array('controller' => 'products','action' => 'view/'.$params['id'],'page' => $page,'limit' => $limit);
            //分页参数
            $options = array('page' => $page,'show' => $limit,'modelClass' => $this->name,'total' => $total);
            $pages = $pagination->init($conditions, $parameters, $options); // Added
            //分页end
            $comment_infos['comment'] = $ProductComment->find('all', array('conditions' => $conditions, 'limit' => $limit, 'page' => $page, 'order' => 'Comment.'.$order, 'fields' => 'Comment.id,Comment.type_id,Comment.title,Comment.parent_id,Comment.user_id,Comment.img,Comment.content,Comment.is_public,Comment.status,Comment.user_id,Comment.created'));
            //拼装用户信息
            if (!empty($comment_infos)) {
                $User = ClassRegistry::init('User');
                $comment_users = $ProductComment->find('list', array('conditions' => $conditions, 'limit' => $limit, 'page' => $page, 'order' => 'Comment.'.$order, 'fields' => 'Comment.user_id'));
                $comm_user_info = $User->find('all', array('conditions' => array('User.id' => $comment_users), 'fields' => 'User.id,User.name,User.img01'));
                $comment_lists = $ProductComment->find('list', array('conditions' => $conditions, 'limit' => $limit, 'page' => $page, 'order' => 'Comment.'.$order, 'fields' => 'Comment.id'));
                $reply_info = $ProductComment->find('all', array('conditions' => array('Comment.parent_id' => $comment_lists), 'order' => 'created desc'));
                $reply_user = $ProductComment->find('list', array('conditions' => array('Comment.parent_id' => $comment_lists), 'order' => 'created desc', 'fields' => 'Comment.user_id'));
                $reply_user_info = $User->find('all', array('conditions' => array('User.id' => $reply_user), 'fields' => 'User.id,User.name,User.img01'));
                foreach ($comment_infos['comment'] as $k => $v) {
                    $comment_infos['comment'][$k]['Reply'] = array();
                    foreach ($comm_user_info as $user_k => $user_v) {
                        if ($comment_infos['comment'][$k]['Comment']['user_id'] == $user_v['User']['id']) {
                            $comment_infos['comment'][$k]['User'] = $user_v['User'];
                        }
                    }
                    foreach ($reply_info as $rep_k => $rep_v) {
                        if ($comment_infos['comment'][$k]['Comment']['id'] == $rep_v['Comment']['parent_id']) {
                            array_push($comment_infos['comment'][$k]['Reply'], $rep_v);
                        }
                    }
                    if (!empty($comment_infos['comment'][$k]['Reply'])) {
                        foreach ($comment_infos['comment'][$k]['Reply'] as $kk => $vv) {
                            foreach ($reply_user_info as $user_kk => $user_v) {
                                if ($comment_infos['comment'][$k]['Reply'][$kk]['Comment']['user_id'] == $user_v['User']['id']) {
                                    $comment_infos['comment'][$k]['Reply'][$kk]['User'] = $user_v['User'];
                                }
                            }
                        }
                    }
                }
            }
            //表情数组
            $Expression = array('/微笑','/撇嘴','/好色','/发呆','/得意','/流泪','/害羞','/睡觉','/尴尬','/呲牙','/惊讶','/冷汗','/抓狂','/偷笑','/可爱','/傲慢','/犯困','/流汗','/大兵','/咒骂','/折磨/','/衰','/擦汗','/抠鼻','/鼓掌','/坏笑','/左哼哼','/右哼哼','/鄙视','/委屈','/阴险','/亲亲','/可怜','/爱情','/飞吻','/怄火','/回头','/献吻','/左太极');
            $comment_infos['expression'] = $Expression;
            $BlockWord = ClassRegistry::init('BlockWord');
            $word = $BlockWord->find('all');
            $comment_infos['word'] = $word;
            $comment_infos['product_id'] = $params['id'];
            $comment_infos['paging'] = $pages;

            /*
                评分选项
            */
            $Score = ClassRegistry::init('Score');
            $Score->set_locale(LOCALE);
            $score_conditions['Score.status'] = 1;
            $score_conditions['Score.type'] = 'P';
            $score_conditions['ScoreI18n.value !='] = '';
            $Score_list = $Score->find('all', array('conditions' => $score_conditions));
            $comment_infos['Score'] = $Score_list;

            /*
                计算平均分
            */
            $ScoreLog = ClassRegistry::init('ScoreLog');
            $_scorelog_list = $ScoreLog->find('all', array('fields' => array('count(value) as countnum', 'sum(value) as sumnum', 'ScoreLog.score_id'), 'conditions' => array('ScoreLog.type' => 'P', 'ScoreLog.type_id' => $params['id']), 'group' => 'ScoreLog.score_id'));
            $scorelog_list = array();
            if (!empty($_scorelog_list) && sizeof($_scorelog_list) > 0) {
                foreach ($_scorelog_list as $k => $v) {
                    $v[0]['average'] = $v[0]['sumnum'] / $v[0]['countnum'];
                    $scorelog_list[$v['ScoreLog']['score_id']] = $v[0];
                }
            }
            //是否可以评分
            $is_score = 1;
            if (isset($_SESSION['User'])) {
                $is_score_list = $ScoreLog->find('count', array('conditions' => array('ScoreLog.user_id' => $_SESSION['User']['User']['id'], 'ScoreLog.type' => 'P', 'ScoreLog.type_id' => $params['id'])));
                if ($is_score_list > 0) {
                    $is_score = 0;
                }
            }
            $comment_infos['is_score'] = $is_score;
            $comment_infos['ScoreLog'] = $scorelog_list;

            if (empty($comment_infos)) {
                $comment_infos = 1;
            }

            return $comment_infos;
        }
    }
    /**
     * 函数get_module_pro_message方法，获取模块商品提问数据.
     *
     * @param  查询参数集合
     *
     * @return $product_message_list 根据param，返回商品提问数组
     */
    public function get_module_pro_message($params)
    {
        $showflag = true;
        if (isset($params['ControllerObj'])) {
            if (isset($params['ControllerObj']->configs['messages-shopissue'])) {
                $showflag = $params['ControllerObj']->configs['messages-shopissue'];
            }
        }
        $conditions = '';
        $limit = 10;
        $product_id = 0;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'orderby';
        $product_message_list = array();
        if ($showflag) {
            //判断是否显示商品提问
            if (isset($params['order'])) {
                $order = $params['order'];
            }
            if (isset($params['id'])) {
                $product_id = $params['id'];
            }
            $product_message_list['product_id'] = $product_id;
            if ($this->check_product($params['id'])) {
                $product_message = array();
                //商品提问
                $UserMessage = ClassRegistry::init('UserMessage');
                $product_message = $UserMessage->find_product_message($product_id);
                $my_messages_parent_id = array();
                if (isset($product_message) && sizeof($product_message) > 0) {
                    foreach ($product_message as $k => $v) {
                        $my_messages_parent_id[] = $v['UserMessage']['id'];
                    }
                    $replies_list = $UserMessage->find_replies_list($my_messages_parent_id);//model
                    $replies_list_format = array();
                    if (is_array($replies_list) && sizeof($replies_list) > 0) {
                        foreach ($replies_list as $k => $v) {
                            $replies_list_format[$v['UserMessage']['parent_id']][] = $v;
                        }
                    }
                    foreach ($product_message as $k => $v) {
                        if (isset($replies_list_format[$v['UserMessage']['id']])) {
                            $product_message[$k]['Reply'] = $replies_list_format[$v['UserMessage']['id']];
                        }
                    }
                    $product_message_list['product_message'] = $product_message;
                }
            }
        }

        return $product_message_list;
    }

    /*
     * 函数get_products_lists方法，获取商品列表
     * @params  查询参数集合
     * @return $products_list 根据param，返回商品列表数组
     */
    public function get_products_lists($params)
    {
        $products_list = array();
        $page = 1;
        $limit = 10;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'Product.online_time';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        $order .= ' desc';
        $conditions = '';
        if (isset($params['category_id'])) {
            $conditions['Product.category_id'] = $params['category_id'];
        }
        $conditions['Product.status'] = '1';
        $conditions['Product.forsale'] = '1';
        //分页start
        $total = $this->find('count', array('conditions' => $conditions));
        App::import('Component', 'Paginationmodel');
        $pagM = new PaginationModelComponent();

        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'products','action' => 'index','page' => $page,'limit' => $limit);
        //分页参数
        $options = array('page' => $page,'show' => $limit,'modelClass' => $this->name,'total' => $total);
        //pr($conditions);die;
        $pages = $pagM->init($conditions, $parameters, $options); // Added
        //pr($pages);
        //分页end

        $products_list = $this->find('all', array('conditions' => $conditions, 'order' => $order, 'page' => $page, 'limit' => $limit));
        $product['products_list'] = $products_list;
        $product['paging'] = $pages;

        return $product;
    }

    /*
    * 函数get_product_package_list方法，获取当前商品的套装商品列表
    * @params  查询参数集合
    * @return $PackageProduct_list 根据param，返回当前商品的套装商品列表数组
    */
    public function get_product_package_list($params)
    {
        $PackageProduct_list = array();
        $product_id = isset($params['id']) ? $params['id'] : '';
        $order = 'PackageProduct.orderby';
        //商品套装
        $PackageProduct = ClassRegistry::init('PackageProduct');
        $conditions['PackageProduct.product_id'] = $product_id;
        $conditions['Product.status'] = '1';
        //$conditions['Product.forsale']='1';
        $conditions['ProductI18n.locale'] = LOCALE;
        $cond['conditions'] = $conditions;
        $cond['fields'] = array('Product.id','Product.img_thumb','Product.img_detail','Product.shop_price','Product.product_type_id','PackageProduct.package_product_qty','PackageProduct.package_product_name');
        $cond['joins'] = array(
            array('table' => 'svoms_products',
                  'alias' => 'Product',
                  'type' => 'right',
                  'conditions' => array('PackageProduct.package_product_id = Product.id'),
                 ),
            array('table' => 'svoms_product_i18ns',
                  'alias' => 'ProductI18n',
                  'type' => 'right',
                  'conditions' => array('Product.id = ProductI18n.product_id'),
                 ),
        );
        $cond['order'] = $order;
        $PackageProduct_list = $PackageProduct->find('all', $cond);
        $ProductTypeAttribute = ClassRegistry::init('ProductTypeAttribute');
        foreach ($PackageProduct_list as $pk => $pv) {
            $result = $ProductTypeAttribute->find('all', array('conditions' => array('ProductTypeAttribute.product_type_id' => $pv['Product']['product_type_id']), 'fields' => 'ProductTypeAttribute.product_type_id,ProductTypeAttribute.attribute_id'));
            $attr_ids = array();
            foreach ($result as $k => $v) {
                array_push($attr_ids, $v['ProductTypeAttribute']['attribute_id']);
            }
            $PackageProduct_list[$pk]['ProductTypeAttribute'] = $attr_ids;
        }
        //pr($PackageProduct_list);
        return $PackageProduct_list;
    }

    /*
    * 函数get_mobile_product_view方法，获取mobile页面商品信息
    * @params  查询参数集合
    * @return $products_list 根据param，返回mobile页面商品信息数组
    */
    public function get_mobile_product_view($params)
    {
        $productInfo = array();
        $conditions = '';
        if (isset($params['id'])) {
            $conditions['Product.id'] = $params['id'];
        }
        $conditions['Product.status'] = '1';
        $conditions['Product.forsale'] = '1';
        $productInfo = $this->find('first', array('conditions' => $conditions));
        if (!empty($productInfo)) {
            //商品Id
            $product_id = $productInfo['Product']['id'];

            //分类Id
            $category_id = $productInfo['Product']['category_id'];

            //查询商品相册
            $ProductGallery = ClassRegistry::init('ProductGallery');
            $ProductGallery_infos = $ProductGallery->find('all', array('conditions' => array('ProductGallery.product_id' => $product_id, 'ProductGallery.status' => 1)));
            if (!empty($ProductGallery)) {
                $productInfo['ProductGallery'] = $ProductGallery_infos;
            }
            //商品属性
            $ProductTypeAttribute = ClassRegistry::init('ProductTypeAttribute');
            $p_type = $ProductTypeAttribute->find('all', array('conditions' => array('ProductTypeAttribute.product_type_id' => array($productInfo['Product']['product_type_id'], 0))));
            $productInfo['attr'] = $p_type;

            //关联商品
            $ProductRelation = ClassRegistry::init('ProductRelation');
            $ProductRelation_info = $ProductRelation->get_related_categories($product_id);
            if (!empty($ProductRelation_info) && sizeof($ProductRelation_info) > 0) {
                $productInfo['Relation'] = $this->find('all', array('fields' => array('Product.id', 'ProductI18n.*', 'Product.img_thumb', 'Product.img_detail', 'Product.shop_price', 'Product.market_price'), 'conditions' => array('Product.id' => $ProductRelation_info, 'Product.status' => '1', 'Product.forsale' => '1')));
            }

            //商品评论
            $ProductComment = ClassRegistry::init('Comment');
            $ProductComment_list = $ProductComment->find('all', array('conditions' => array('Comment.Type' => 'P', 'Comment.type_id' => $product_id, 'Comment.status' => '1'), 'order' => 'Comment.created desc'));
            if (!empty($ProductComment_list) && sizeof($ProductComment_list) > 0) {
                foreach ($ProductComment_list as  $k => $v) {
                    $userId_arr[] = $v['Comment']['user_id'];
                }
                //查找用户信息
                $User = ClassRegistry::init('User');
                $User_list = $User->find('all', array('fields' => array('User.id', 'User.name', 'User.img01'), 'conditions' => array('User.id' => $userId_arr)));

                foreach ($User_list as $k => $v) {
                    foreach ($ProductComment_list as $kk => $vv) {
                        if ($v['User']['id'] == $vv['Comment']['user_id']) {
                            $ProductComment_list[$kk]['User'] = $v['User'];
                        }
                    }
                }
            }
            $productInfo['Comment'] = $ProductComment_list;

            //商品提问
            $UserMessage = ClassRegistry::init('UserMessage');
            $product_message = $UserMessage->find_product_message($product_id);
            $my_messages_parent_id = array();
            if (isset($product_message) && sizeof($product_message) > 0) {
                foreach ($product_message as $k => $v) {
                    $my_messages_parent_id[] = $v['UserMessage']['id'];
                }
                $replies_list = $UserMessage->find_replies_list($my_messages_parent_id);//model
                $replies_list_format = array();
                if (is_array($replies_list) && sizeof($replies_list) > 0) {
                    foreach ($replies_list as $k => $v) {
                        $replies_list_format[$v['UserMessage']['parent_id']][] = $v;
                    }
                }
                foreach ($product_message as $k => $v) {
                    if (isset($replies_list_format[$v['UserMessage']['id']])) {
                        $product_message[$k]['Reply'] = $replies_list_format[$v['UserMessage']['id']];
                    }
                }
            }
            $productInfo['Message'] = $product_message;

            //同类商品
            $cproducts = $this->find('all', array('conditions' => array('Product.id <>' => $product_id, 'Product.forsale' => '1', 'Product.status' => '1', 'Product.category_id' => $category_id), 'fields' => array('Product.id,Product.img_thumb,Product.img_detail,ProductI18n.name', 'Product.shop_price', 'Product.market_price'), 'limit' => 6));
            $productInfo['Cproducts'] = $cproducts;
        }

        return $productInfo;
    }
    /*
    *check_product 判断产品是否存在
    *@params $id 商品id
    */
    public function check_product($id, $ld, $configs)
    {
        $product_infos = $this->find('first', array('conditions' => array('Product.id' => $id)));
        //pr($product_infos);die;
        $result['title'] = '';
        $result['url'] = '';
        $result['flag'] = false;
        if (empty($product_infos)) {
            $this->pageTitle = $ld['products'].$ld['not_exist'].' - '.$configs['shop_title'];
            $products_error = $this->find_first_products_error();//model
            //$this->set('products_error',$products_error);
            //$this->flash($ld['products'].$ld['not_exist'],isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"/","","");
            $result['title'] = $ld['products'].$ld['not_exist'];
            $result['url'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
            $result['flag'] = false;

            return $result;
        } elseif ($product_infos['Product']['status'] != 1) {
            $this->pageTitle = $ld['products'].$ld['not_exist'].' - '.$configs['shop_title'];
            $products_error = $this->find_first_products_error();//model
            //$this->set('products_error',$products_error);
            //$this->flash($ld['products'].$ld['not_exist'],isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"/","","");
            $result['title'] = $ld['products'].$ld['not_exist'];
            $result['url'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
            $result['flag'] = false;

            return $result;
        } elseif ($product_infos['Product']['forsale'] != 1) {
            $this->pageTitle = $product_infos['ProductI18n']['name'].':'.$ld['product_out_of_sale'].' - '.$configs['shop_title'];
            if ($product_infos['Product']['category_id'] > 0) {
                $CategoryProduct = ClassRegistry::init('CategoryProduct');
                $navigations = $CategoryProduct->tree('P', $product_infos['Product']['category_id'], LOCALE, 1);//,$this
                //pr($navigations);die;
            //	$category_error =$this->CategoryProduct->allinfo['P']['subids'][$product['Product']['category_id']];
            //	$products_error = $this->Product->find_second_products_error( $category_error );//model
            //	$this->set('products_error',$products_error);
            }
            //$this->flash($product_infos['ProductI18n']['name'].":".$ld['product_out_of_sale'],isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"/","");
            $result['title'] = $product_infos['ProductI18n']['name'].':'.$ld['product_out_of_sale'];
            $result['url'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
            $result['flag'] = false;

            return $result;
        }
        if (!empty($product_infos)) {
            //title
            $this->pageTitle = $product_infos['ProductI18n']['name'].' - '.$configs['shop_title'];
            $result['title'] = $product_infos['ProductI18n']['name'].' - '.$configs['shop_title'];
            $result['url'] = '';
            $result['flag'] = true;

            return $result;
        }

        return $result;
    }

    /*
        根据商品查询结果输出Id数组
    */
    public function getproduct_ids($product_list)
    {
        $product_ids = array();
        if (!empty($product_list) && sizeof($product_list) > 0) {
            foreach ($product_list as $k => $v) {
                $product_ids[] = $v['Product']['id'];
            }
        }
        return $product_ids;
    }
    
    /*
        根据商品查询结果输出Id数组
    */
    public function getproduct_codes($product_list)
    {
        $product_codes = array();
        if (!empty($product_list) && sizeof($product_list) > 0) {
            foreach ($product_list as $k => $v) {
                $product_codes[] = $v['Product']['code'];
            }
        }
        return $product_codes;
    }
    
    /*
        根据商品货号查询结果输出Id数组
    */
    public function get_product_id_by_code($code)
    {
        $product_id = array();
        if (!empty($code) && sizeof($code) > 0) {
            $product_id = $this->find('first', array('conditions' => array('Product.code' => $code), 'fields' => 'Product.id'));
        }

        return $product_id;
    }

    /*
        订单库存修改
    */
    public function updateskupro($pro_code, $quantity, $flag)
    {
        $SkuProduct = ClassRegistry::init('SkuProduct');
        $code_list = $SkuProduct->find('list', array('conditions' => array('SkuProduct.sku_product_code' => $pro_code), 'fields' => array('SkuProduct.product_code')));
        if (!empty($code_list)) {
            $pro_list = $this->find('all', array('conditions' => array('Product.code' => $code_list), 'fields' => array('Product.id', 'Product.quantity')));
            foreach ($pro_list as $v) {
                if ($flag == true) {
                    $data['quantity'] = $v['Product']['quantity'] - $quantity;
                    $data['id'] = $v['Product']['id'];
                } elseif ($flag == false) {
                    $data['quantity'] = $v['Product']['quantity'] + $quantity;
                    $data['id'] = $v['Product']['id'];
                } else {
                    $data['quantity'] = $v['Product']['quantity'];
                    $data['id'] = $v['Product']['id'];
                }
                $this->save(array('Product' => $data));
            }
        }
    }

    /*
        商品浏览历史查询
    */
    public function pro_view_log($params)
    {
        $pro_data = array();
        $showflag = false;
        if (isset($params['ControllerObj'])) {
            if (isset($params['controller']) && isset($params['action'])) {
                if ($params['controller'] == 'products' && $params['action'] == 'view') {
                    if (isset($params['ControllerObj']->configs['phistory-pstatus']) && $params['ControllerObj']->configs['phistory-pstatus'] == '1') {
                        $showflag = true;
                    }
                } elseif ($params['controller'] == 'categories' && $params['action'] == 'view') {
                    if (isset($params['ControllerObj']->configs['phistory-cstatus']) && $params['ControllerObj']->configs['phistory-cstatus'] == '1') {
                        $showflag = true;
                    }
                } elseif ($params['controller'] == 'users' && $params['action'] == 'index') {
                    if (isset($params['ControllerObj']->configs['phistory-ustatus']) && $params['ControllerObj']->configs['phistory-ustatus'] == '1') {
                        $showflag = true;
                    }
                }
            }
        }
        if ($showflag && isset($_COOKIE['pro_view_log']) && !empty($_COOKIE['pro_view_log'])) {
            //获取商品浏览历史
            $pro_ids = explode(';', $_COOKIE['pro_view_log']);
            if (!empty($pro_ids)) {
                $conditions = '';
                $limit = 10;
                if (isset($params['limit'])) {
                    $limit = $params['limit'];
                }
                $order = 'created';
                if (isset($params['order'])) {
                    $order = $params['order'];
                }
                if (isset($params['category_id'])) {
                    $CategoryProduct = ClassRegistry::init('CategoryProduct');
                    $category_ids = $CategoryProduct->find('list', array('conditions' => array('CategoryProduct.parent_id' => $params['category_id'])));
                    $category_ids[] = $params['category_id'];
                    $conditions['Product.category_id'] = $category_ids;
                }
                $conditions['Product.id'] = $pro_ids;
                $conditions['Product.forsale'] = 1;
                $conditions['Product.alone'] = 1;
                $conditions['Product.status'] = 1;
                $pro_data = $this->find('all', array('conditions' => $conditions, 'fields' => 'Product.id,Product.code,Product.img_thumb,Product.img_detail,ProductI18n.name,Product.like_stat,Product.shop_price,Product.market_price,Product.unit', 'limit' => $limit));
                if (!empty($pro_data)) {
                	$product_codes = $this->getproduct_codes($pro_data);
                	
                	$SkuProduct = ClassRegistry::init('SkuProduct');
			$price_range=$SkuProduct->sku_price_range($product_codes);
                	
                    $comment = ClassRegistry::init('Comment');
                    $comment_num = $comment->find('all', array('conditions' => array('Comment.type_id' => $pro_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
                    foreach ($pro_data as $k => $v) {
				foreach ($comment_num as $kk => $vv) {
					if ($pro_data[$k]['Product']['id'] == $vv['Comment']['type_id']) {
						$pro_data[$k]['Product']['Commentnum'] = $vv[0]['Commentnum'];
					}
				}
				if(isset($price_range[$v['Product']['code']])){
					$pro_data[$k]['price_range'] = $price_range[$v['Product']['code']];
				}
                    }
                }
            }
        }

        return $pro_data;
    }

    public function getOrderProductPriceList($pro_id, $pro_code)
    {
        $idPrices = array();
        $pro_cond['OR']['Product.id'] = $pro_id;
        $pro_cond['OR']['Product.code'] = $pro_code;
        $all_pro_info = $this->find('all', array('fields' => array('Product.id', 'Product.code', 'Product.shop_price'), 'conditions' => $pro_cond, 'recursive' => -1));
        if (!empty($all_pro_info)) {
            $pro_sku_list = array();
            foreach ($all_pro_info as $v) {
                $pro_sku_list[$v['Product']['code']] = $v['Product']['id'];
                $idPrices[$v['Product']['id'].$v['Product']['code']] = $v['Product']['shop_price'];
            }
            $skuPrice = array();
            $SkuProduct = ClassRegistry::init('SkuProduct');
            $sku_cond['SkuProduct.sku_product_code'] = $pro_code;
            $sku_pro_info = $SkuProduct->find('all', array('conditions' => $sku_cond));
            foreach ($sku_pro_info as $v) {
                if (!isset($pro_sku_list[$v['SkuProduct']['product_code']])) {
                    continue;
                }
                $key_str = $pro_sku_list[$v['SkuProduct']['product_code']].$v['SkuProduct']['sku_product_code'];
                $idPrices[$key_str] = $v['SkuProduct']['price'];
            }
        }

        return $idPrices;
    }

    public function getOrderProductPrice($pro_id = 0, $pro_code = '')
    {
        $OrderProductPrice = '0.00';
        $pro_info = $this->find('first', array('conditions' => array('Product.id' => $pro_id)));
        if (!empty($pro_info)) {
            $OrderProductPrice = $pro_info['Product']['shop_price'];
            if ($pro_code != '' && $pro_info['Product']['code'] != $pro_code) {
                $SkuProduct = ClassRegistry::init('SkuProduct');
                $sku_cond['SkuProduct.product_code'] = $pro_info['Product']['code'];
                $sku_cond['SkuProduct.sku_product_code'] = $pro_code;
                $sku_pro_info = $SkuProduct->find('first', array('conditions' => $sku_cond));
                if (!empty($sku_pro_info)) {
                    $OrderProductPrice = $sku_pro_info['SkuProduct']['price'];
                }
            }
        }

        return $OrderProductPrice;
    }
}
