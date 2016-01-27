<?php

/**
 * 商品类型属性模型.
 */
class attribute extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Attribute 商品属性模型
     */
    public $name = 'Attribute';

    /*
     * @var $hasOne array 关联商品类型多语言表
     */
    public $hasOne = array('AttributeI18n' => array('className' => 'AttributeI18n',
                              'conditions' => array('AttributeI18n.locale' => LOCALE),
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'attribute_id',
                             ),
                  );
    public $hasMany = array(
                        'AttributeOption' => array(
                        'className' => 'AttributeOption',
                        'conditions' => array('AttributeOption.locale' => LOCALE),
                        'order' => 'AttributeOption.attribute_id',
                        'fields' => 'AttributeOption.*',
                        'dependent' => true,
                        'foreignKey' => 'attribute_id',
                    ),
        );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " AttributeI18n.locale = '".$locale."'";
        $this->hasOne['AttributeI18n']['conditions'] = $conditions;
        $this->hasMany['AttributeOption']['conditions'] = "AttributeOption.locale = '".$locale."' and AttributeOption.status=1";
    }

    /**
     * 函数findassoc,商品协议.
     *
     * @param $condition 商品属性
     * @param $lists 商品列表
     * @param $lists_formated 商品属性
     *
     * @return $lists_formated 商品属性
     */
    public function findassoc()
    {
        $condition = "Attribute.status ='1'";

        $lists = $this->findAll($condition);
        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['Attribute']['id']] = $v;
                $lists_formated[$v['Attribute']['id']]['Attribute']['name'] = '';
                foreach ($lists_formated[$v['Attribute']['id']]['AttributeI18n'] as $key => $val) {
                    $lists_formated[$v['Attribute']['id']]['Attribute']['name'] .= $val['name'].' | ';
                }
            }
        }

        return $lists_formated;
    }

    /**
     * 函数get_list,获取商品列表.
     *
     * @param $category_id 商品分类号
     * @param $Lists 商品列表
     * @param $condition 商品类型
     *
     * @return $Lists 商品类型列表
     */
    public function get_list($category_id)
    {
        $Lists = array();
        $condition = "Attribute.status ='1'";
        if ($category_id != '') {
            $condition .= ' AND Attribute.id in ('.$category_id.')';
        }

        $Lists = $this->findAll($condition, '', 'id asc');

        return $Lists;
    }

    /**
     * 函数find_all_att,获取所有商品信息.
     *
     * @param $locale 商品语言
     * @param $params 商品属性
     * @param $Lists 商品列表
     * @param $product_type_atts 所有商品
     *
     * @return $product_type_atts 商品数组
     */
    public function find_all_att($locale)
    {
        $params = array('order' => 'Attribute.id desc',
            'conditions' => array('Attribute.status' => 1),
        );
        $Lists = $this->find('all', $params, $this->name.$locale);
        $product_type_atts = array();
        if (is_array($Lists) && sizeof($Lists) > 0) {
            foreach ($Lists as $k => $v) {
                $product_type_atts[$v['Attribute']['id']] = $v;
            }
        }

        return $product_type_atts;
    }

    /**
     * 函数find_product_att 所有商品.
     *
     * @param $locale 商品语言
     * @param $product_type_id 商品类型号
     * @param $params 商品排列
     * @param $product_type_atts 商品类型
     *
     * @return $product_type_atts 商品类型信息
     */
    public function find_product_att($locale, $product_type_id)
    {
        $ProductTypeAttribute = ClassRegistry::init('ProductTypeAttribute');
        $attr_ids = $ProductTypeAttribute->getattrids(array($product_type_id, 0));

        $params = array('order' => 'Attribute.id desc',
            'conditions' => array('Attribute.status' => 1, 'Attribute.id' => $attr_ids),
        );
        $Lists = $this->find('all', $params, $this->name.$locale);
        $product_type_atts = array();
        if (is_array($Lists) && sizeof($Lists) > 0) {
            foreach ($Lists as $k => $v) {
                $product_type_atts[$v['Attribute']['id']] = $v;
                $product_type_atts[$v['Attribute']['id']]['Attribute']['attr_value'] = $v['AttributeI18n']['attr_value'];
                $product_type_atts[$v['Attribute']['id']]['Attribute']['default_value'] = $v['AttributeI18n']['default_value'];
            }
        }

        return $product_type_atts;
    }

    public function find_product_type_attributes($product_attribute_list)
    {
        $product_type_attributes = $this->find('all', array('conditions' => array('Attribute.id' => $product_attribute_list), 'order' => 'Attribute.id desc'));

        return $product_type_attributes;
    }

    public function get_product_attribute_codes()
    {
        $all_infos = $this->find('all', array('fields' => 'Attribute.id,Attribute.code', 'recursive' => '-1'));
        $codes = array();
        if (!empty($all_infos)) {
            foreach ($all_infos as $v) {
                $codes[$v['Attribute']['id']] = $v['Attribute']['code'];
            }
        }

        return $codes;
    }
    public function find_product_attr_type_list($locale = 'chi')
    {
        $ProductTypeAttribute = ClassRegistry::init('ProductTypeAttribute');
        $pro_type_ids = array();
        $pro_type_infos = $ProductTypeAttribute->find('all', array('fields' => array('ProductTypeAttribute.product_type_id', 'ProductTypeAttribute.attribute_id'), 'group' => 'ProductTypeAttribute.product_type_id,ProductTypeAttribute.attribute_id'));
        foreach ($pro_type_infos as $v) {
            $pro_type_ids[$v['ProductTypeAttribute']['product_type_id']][] = $v['ProductTypeAttribute']['attribute_id'];
        }
        $this->set_locale($locale);
        $attr_infos = array();
        $all_infos = $this->find('all', array('fields' => array('Attribute.id', 'AttributeI18n.name')));
        foreach ($all_infos as $v) {
            $attr_infos[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
        }
        $attr_type_names = array();
        if (!empty($pro_type_ids) && !empty($attr_infos)) {
            foreach ($pro_type_ids as $k => $v) {
                if (is_array($v) && sizeof($v) > 0) {
                    foreach ($v as $vv) {
                        if (!isset($attr_infos[$vv])) {
                            continue;
                        }
                        $attr_type_names[$k][$attr_infos[$vv]] = $k;
                    }
                }
            }
        }

        return $attr_type_names;
    }
}
