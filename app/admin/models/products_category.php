<?php

/*****************************************************************************
 * svoms  ProductsCategory模型
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
class ProductsCategory extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name ProductsCategory ������Ʒ��չ�����ģ��
     */
    public $name = 'ProductsCategory';

    /**
     * findcountassoc��������ȡ�����µ���Ʒ����.
     *
     * @return array $lists_formated ���ظ��������µ���Ʒ����
     */
    public function findcountassoc($cids = '')
    {
        if ($cids != '') {
            $lists = $this->find('all', array('conditions' => array('category_id' => $cids), 'fields' => array('category_id', 'count(*) as count'), 'group' => 'category_id'));
        } else {
            $lists = $this->find('all', array('fields' => array('category_id', 'count(*) as count'), 'group' => 'category_id'));
        }
        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['ProductsCategory']['category_id']] = $v['0']['count'];
            }
        }

        return $lists_formated;
    }

    public function get_product_ids($cids = '')
    {
        if ($cids != '') {
            $lists = $this->find('all', array('conditions' => array('category_id' => $cids), 'fields' => array('category_id', 'product_id')));
        } else {
            $lists = $this->find('all', array('fields' => array('category_id', 'product_id')));
        }

        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['ProductsCategory']['category_id']][] = $v['ProductsCategory']['product_id'];
            }
        }

        return $lists_formated;
    }

    public function product_count($category_id = 0)
    {
        if ($category_id != 0) {
            $conditions['ProductsCategory.category_id'] = $category_id;
        } else {
            $conditions = '';
        }
        $lists = $this->find('all', array('conditions' => $conditions, 'fields' => array('ProductsCategory.category_id', 'count(product_id) as count'), 'group' => 'category_id'));

        $lists_formated = array();
        if (!empty($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['ProductsCategory']['category_id']] = $v['0']['count'];
            }
        }

        return $lists_formated;
    }
}
