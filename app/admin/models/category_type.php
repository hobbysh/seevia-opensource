<?php

/*****************************************************************************
 * SV-CategoryType 类目管理
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
*****************************************************************************/
class CategoryType extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'CategoryType';
    /*
     * @var $hasOne array 类目表//注释download会报错！
     */
    public $hasOne = array('CategoryTypeI18n' => array(
                        'className' => 'CategoryTypeI18n',
                        'order' => '',
                        'dependent' => true,
                        'foreignKey' => 'category_type_id',
                    ),
                  );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = '';
        $conditions = " CategoryTypeI18n.locale = '".$locale."'";
        $this->hasOne['CategoryTypeI18n']['conditions'] = $conditions;
    }

    public $categories_parent_format = array();
    /**
     * tree方法，类目树.
     *
     * @param string $type   输入类型
     * @param string $locale 输入语言
     * @param int    $id     输入id
     *
     * @return array $this->allinfo[$type] 返回所有的输入值，如果不存在则从数据库中取出相对应的数据
     */
    public function tree()
    {
        $this->categories_parent_format = array();
        $categories = $this->find('all', array('order' => 'CategoryType.orderby asc,CategoryType.created asc'));
        if (is_array($categories)) {
            foreach ($categories as $k => $v) {
                $this->categories_parent_format[$v['CategoryType']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }

    /**
     * subcat_get方法，获得subcat.
     *
     * @param int $category_type_id 输入id
     *
     * @return array $subcat 根据id检索相对应的数据并返回
     */
    public function subcat_get($category_type_id)
    {
        $subcat = array();
        if (isset($this->categories_parent_format[$category_type_id]) && is_array($this->categories_parent_format[$category_type_id])) {
            //判断parent_id = 0 的数据
            foreach ($this->categories_parent_format[$category_type_id]as $k => $v) {
                $CategoryType = $v; //parent_id 为 0 的数据
                if (isset($this->categories_parent_format[$v['CategoryType']['id']]) && is_array($this->categories_parent_format[$v['CategoryType']['id']])) {
                    $CategoryType['SubCategory'] = $this->subcat_get($v['CategoryType']['id']);
                }
                $subcat[$k] = $CategoryType;
            }
        }

        return $subcat;
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 输入分类编号
     *
     * @return array $lists_formated 返回类目所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('CategoryType.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['CategoryType'] = $v['CategoryType'];
            $lists_formated['CategoryTypeI18n'][] = $v['CategoryTypeI18n'];
            foreach ($lists_formated['CategoryTypeI18n']as $key => $val) {
                $lists_formated['CategoryTypeI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
}
