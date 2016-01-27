<?php

/**
 * 商品分类关联模型.
 */
class ProductsCategory extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ProductsCategory';

    //hobby 20081120 取得id=>count
    /**
     * 函数findcountassoc 用于计算分类.
     *
     * @param $lists 商品列表
     * @param $lists_formated 目录表
     *
     * @return $lists_formated 目录表
     */
    public function findcountassoc()
    {
        $lists = $this->find('all', array('fields' => array('category_id', 'count(*) as count'), 'group' => 'category_id'));
        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['ProductsCategory']['category_id']] = $v['0']['count'];
            }
        }

        return $lists_formated;
    }

    //
    /**
     * 函数handle_other_cat 扩展分类.
     *
     * @param $product_id 商品号
     * @param $cat_list 分类目录
     * @param $res 查询现有的扩展分类
     * @param $exist_list 存在目录
     * @param $delete_list 删除不再有的分类
     * @param $condition 商品信息
     * @param $add_list 添加目录
     * @param $other_cat_info 扩展分类
     *
     * @return true 扩展分类成功
     */
    public function handle_other_cat($product_id, $cat_list)
    {
        //查询现有的扩展分类
        $res = $this->findAll('ProductsCategory.product_id = '.$product_id.'');
        $exist_list = array();
        foreach ($res as $k => $v) {
            $exist_list[$k] = $v['ProductsCategory']['category_id'];
        }
        //删除不再有的分类
        $delete_list = array_diff($exist_list, $cat_list);
        if ($delete_list) {
            $condition = array('ProductsCategory.category_id' => $delete_list, 'ProductsCategory.product_id = '.$product_id.'');
            $this->deleteAll($condition);
        }
        //添加新加的分类
        $add_list = array_diff($cat_list, $exist_list, array(0));
        foreach ($add_list as $k => $cat_id) {
            $other_cat_info = array(
                'product_id' => $product_id,
                'category_id' => $add_list[$k],
            );
            $this->saveAll(array('ProductsCategory' => $other_cat_info));
        }

        return true;
    }

    public function get_product_category_infos($conditions)
    {
        $product_category_infos = $this->find('all', array('conditions' => $conditions));

        return $product_category_infos;
    }

    public function find_products_category_info($id, $category_id)
    {
        $category_info = $this->find('ProductsCategory.product_id ='.$id.' and ProductsCategory.category_id ='.$category_id);

        return $category_info;
    }

    public function find_relation_ids($conditions)
    {
        $relation_ids = $this->find('all', array('fields' => array('ProductRelation.product_id', 'ProductRelation.related_product_id'), 'conditions' => $conditions, 'recursive' => '1', 'order' => 'ProductRelation.orderby'));

        return $relation_ids;
    }

    public function find_product_category_infos($page_products_id)
    {
        $product_category_infos = $this->find('all', array('conditions' => array('ProductsCategory.product_id' => $page_products_id)));

        return $product_category_infos;
    }

    /*
        查询关联当前分类的商品id
    */
    public function getproids_bycategory($category_id)
    {
        $pro_ids = $this->find('list', array('fields' => array('ProductsCategory.product_id'), 'conditions' => array('ProductsCategory.category_id' => $category_id)));

        return $pro_ids;
    }
}
