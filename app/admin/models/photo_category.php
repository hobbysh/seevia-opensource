<?php

/**
 * 分类相册分类模型.
 */
class PhotoCategory extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name Payment 支付方式
     */
    public $name = 'PhotoCategory';

    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array(
        'PhotoCategoryI18n' => array(
            'className' => 'PhotoCategoryI18n',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'photo_category_id',
        ),
    );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " PhotoCategoryI18n.locale = '".$locale."'";
        $this->hasOne['PhotoCategoryI18n']['conditions'] = $conditions;
    }

    public function tree($locale)
    {
        return $this->find('all', array('conditions' => array('PhotoCategoryI18n.locale' => $locale), 'fields' => array('PhotoCategory.id', 'PhotoCategoryI18n.name')));
    }
    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 输入分类编号
     *
     * @return array $lists_formated 返回图片空间分类所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('PhotoCategory.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['PhotoCategory'] = $v['PhotoCategory'];
            $lists_formated['PhotoCategoryI18n'][] = $v['PhotoCategoryI18n'];
            foreach ($lists_formated['PhotoCategoryI18n']as $key => $val) {
                $lists_formated['PhotoCategoryI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
}
