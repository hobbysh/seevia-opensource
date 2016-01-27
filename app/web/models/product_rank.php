<?php

/**
 * 商品会员等级价模型.
 */
class ProductRank extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ProductRank';
    public $cacheQueries = true;
    public $cacheAction = '1 day';

    /**
     * 函数findall_ranks,获取商品等级.
     *
     * @param $cache_key 缓存调取商品等级
     * @param $rank_price 商品等级价
     * @param $p_r 所有商品等级
     *
     * @return $rank_price 商品等级对应价格
     */
    public function findall_ranks()
    {
        $cache_key = md5('ProductRank_findall');
        $rank_price = cache::read($cache_key);
        if (!$rank_price) {
            $p_r = $this->findall();
            $rank_price = array();
            if (is_array($p_r) && sizeof($p_r) > 0) {
                foreach ($p_r as $k => $v) {
                    $rank_price[$v['ProductRank']['product_id']][$v['ProductRank']['rank_id']] = $v;
                }
            }
            cache::write($cache_key, $rank_price);

            return $rank_price;
        } else {
            return $rank_price;
        }
    }

    public function find_rank_by_product_ids($ids)
    {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }

        $p_r = $this->find('all', array('conditions' => array('ProductRank.product_id' => $ids), 'fields' => array('ProductRank.is_default_rank', 'ProductRank.rank_id', 'ProductRank.product_id', 'ProductRank.product_id', 'ProductRank.product_price')), 'find_rank_by_product_ids');
        $rank_price = array();
        if (is_array($p_r) && sizeof($p_r) > 0) {
            foreach ($p_r as $k => $v) {
                $rank_price[$v['ProductRank']['product_id']][$v['ProductRank']['rank_id']] = $v;
            }
        }

        return $rank_price;
    }
}
