<?php

/*****************************************************************************
 * svoms  ProductStyle 版型表模型
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
class ProductStyle extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product 商品
     */
    public $name = 'ProductStyle';
    /*
     * @var $hasOne array 版型表模型
     */
    public $hasOne = array('ProductStyleI18n' => array(
                        'conditions' => array('locale' => LOCALE),
                        'className' => 'ProductStyleI18n',
                        'order' => '',
                        'dependent' => true,
                        'foreignKey' => 'style_id',
                    ),
                  );

    /**
     * localeformat方法，商品版型数组结构调整.
     *
     * @param int $id 输入商品版型编号
     *
     * @return array $product_info_formated 返回商品版型数组
     */
    public function localeformat($id)
    {
        $product_style_info = $this->find('all', array('conditions' => array('ProductStyle.id' => $id)));
        $product_style_info_formated = array();
        foreach ($product_style_info as $k => $v) {
            $product_style_info_formated['ProductStyle'] = $v['ProductStyle'];
            $product_style_info_formated['ProductStyleI18n'][] = $v['ProductStyleI18n'];
            foreach ($product_style_info_formated['ProductStyleI18n'] as $key => $val) {
                $product_style_info_formated['ProductStyleI18n'][$val['locale']] = $val;
            }
        }

        return $product_style_info_formated;
    }

    /**
     * product_type_tree方法，类型树.
     *
     * @param string $locale 语言代码
     *
     * @return string $product_type_list 返回类型树
     */
    public function product_style_tree($locale = 'chi')
    {
        $product_style_tree = $this->find('all', array('conditions' => array('ProductStyle.status' => 1, 'ProductStyleI18n.locale' => $locale), 'fields' => array('ProductStyle.id', 'ProductStyleI18n.style_name'), 'order' => 'ProductStyle.orderby'));

        return $product_style_tree;
    }
}
