<?php

/*****************************************************************************
 * svoms 品牌模型
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
class brand extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Brand 关于品牌的模块
     */
    public $name = 'Brand';
    public $hasOne = array('BrandI18n' => array('className' => 'BrandI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'brand_id',
                        ),
                  );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale 语言代码
     */
    public function set_locale($locale)
    {
        $conditions = " BrandI18n.locale = '".$locale."'";
        $this->hasOne['BrandI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，品牌数组结构调整.
     *
     * @param int $id 输入文章编号
     *
     * @return array $lists_formated 返回品牌所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Brand.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Brand'] = $v['Brand'];
            $lists_formated['BrandI18n'][] = $v['BrandI18n'];
            foreach ($lists_formated['BrandI18n'] as $key => $val) {
                $lists_formated['BrandI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    /**
     * brand_tree方法，品牌列表.
     *
     * @param string $locale 语言代码
     *
     * @return array $brand_list 返回品牌信息
     */
    public function brand_tree($locale = 'chi')
    {
        $brand_list = $this->find('all', array('conditions' => array('Brand.status' => 1, 'BrandI18n.locale' => $locale), 'fields' => array('Brand.id', 'BrandI18n.name', 'Brand.code'), 'order' => 'Brand.orderby, Brand.code asc'));

        return $brand_list;
    }
    public function getbrandformat()
    {
        $lists = $this->find('all', array('fields' => array('Brand.id,BrandI18n.name,BrandI18n.locale'), 'order' => 'Brand.orderby asc,BrandI18n.name asc'));

        return $lists;
    }

    public function brand_names($locale = 'chi')
    {
        $brands = $this->find('all', array('conditions' => array('Brand.status' => 1, 'BrandI18n.locale' => $locale), 'fields' => array('Brand.id', 'BrandI18n.name')));
        $brand_list = array();
        foreach ($brands as $k => $v) {
            $brand_list[$v['Brand']['id']] = $v['BrandI18n']['name'];
        }

        return $brand_list;
    }
}
