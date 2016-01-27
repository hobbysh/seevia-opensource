<?php

/**
 * 商品通用模板模型.
 *
 * @todo 要去除，待确认
 */
class ProductPublicTemplate extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ProductPublicTemplate';
    public $hasOne = array('ProductPublicTemplateI18n' => array('className' => 'ProductPublicTemplateI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => 'ProductPublicTemplate.id',
            'dependent' => true,
            'foreignKey' => 'product_public_template_id',
        ),
    );

    /**
     * 函数localeformat 商品陈列样式.
     *
     * @param $id 商品号
     * @param $lists 所有商品
     * @param $lists_formated 商品陈列样式
     *
     * @return $lists_formated 商品陈列样式
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('ProductPublicTemplate.id' => $id)));
        foreach ($lists as $k => $v) {
            $lists_formated['ProductPublicTemplate'] = $v['ProductPublicTemplate'];
            $lists_formated['ProductPublicTemplateI18n'][] = $v['ProductPublicTemplateI18n'];
            foreach ($lists_formated['ProductPublicTemplateI18n'] as $key => $val) {
                $lists_formated['ProductPublicTemplateI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    /**
     * 函数get_productpublictemplate_format 公共商品样式.
     *
     * @return 公共商品样式
     */
    public function get_productpublictemplate_format()
    {
        return $this->find('all', array('conditons' => array('ProductPublicTemplate.status' => 1), 'fields' => array('ProductPublicTemplateI18n.title', 'ProductPublicTemplate.id')));
    }
}
