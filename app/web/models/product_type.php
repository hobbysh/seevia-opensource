<?php

/**
 * 商品类型模型.
 */
class ProductType extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ProductType';
    public $hasOne = array('ProductTypeI18n' => array('className' => 'ProductTypeI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'type_id',
        ),
    );

    /**
     * 函数gettypeformat,获取商品类型.
     *
     * @param $id 商品号
     * @param $condition 商品类型号
     * @param $lists 商品列表
     * @param $lists_formated 商品类型内容
     *
     * @return $lists_formated 商品内容数组
     */
    public function gettypeformat($id = 0)
    {
        if ($id == 0) {
            $condition = '';
        } else {
            $condition = "ProductType.id = '".$id."' ";
        }

        $lists = $this->findAll($condition);
        //	pr($lists);
        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$k]['ProductType']['name'] = '';
                foreach ($v['ProductTypeI18n'] as $key => $val) {
                    $lists_formated[$k]['ProductType']['name'] .= $val['name'].' | ';
                    $lists_formated[$k]['ProductType']['id'] = $val['type_id'];
                }
            }
        }
        //pr($lists_formated);
        return $lists_formated;
    }
    /*
        获取当前属性组名称
    */
    public function get_type_name($pro_type_id = 0)
    {
        $type_name = array();
        $type_name = $this->find('first', array('conditions' => array('ProductType.id' => $pro_type_id), 'fields' => 'ProductTypeI18n.name'));

        return !empty($type_name) ? $type_name['ProductTypeI18n']['name'] : 0;
    }
}
