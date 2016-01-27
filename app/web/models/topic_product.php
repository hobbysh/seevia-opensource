<?php

/* * ***************************************************************************
 * Seevia 专题管理
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
 * *************************************************************************** */

/**
 * 这是一个名为TopicProduct的模型用来进行数据的访问。.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 */
class TopicProduct extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    public $name = 'TopicProduct';

    /**
     * 函数get_topic_product 获取主题商品.
     *
     * @param $topic_product_conditions 获取主题商品条件
     */
    public function get_topic_product($topic_product_conditions)
    {
        $topic_products = $this->find('all', array('conditions' => $topic_product_conditions,
                    'fields' => array('TopicProduct.id', 'TopicProduct.topic_id', 'TopicProduct.product_id', 'TopicProduct.price'), ));

        return $topic_products;
    }

    public function find_first_topic_products($id)
    {
        $topic_products = $this->find('all', array('conditions' => array('TopicProduct.topic_id' => $id, 'TopicProduct.status' => 1), 'fields' => array('TopicProduct.id', 'TopicProduct.topic_id', 'TopicProduct.product_id', 'TopicProduct.price')));

        return $topic_products;
    }
    public function find_topic_product_ids($id)
    {
        $topic_products = $this->find('all', array('conditions' => array('TopicProduct.topic_id' => $id, 'TopicProduct.status' => 1), 'fields' => array('TopicProduct.product_id')));
        $t_ids = array();
        foreach ($topic_products as $v) {
            $t_ids[] = $v['TopicProduct']['product_id'];
        }

        return $t_ids;
    }
}
