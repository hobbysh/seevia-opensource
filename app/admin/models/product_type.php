<?php

/*****************************************************************************
 * svoms  商品类型模型
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
class ProductType extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name ProductType 商品类型
     */
    public $name = 'ProductType';

    /*
     * @var $hasOne array 关联商品类型多语言表
     */
    public $hasOne = array('ProductTypeI18n' => array('className' => 'ProductTypeI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'type_id',
                        ),
                    );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " ProductTypeI18n.locale = '".$locale."'";
        $this->hasOne['ProductTypeI18n']['conditions'] = $conditions;
    }

    /**
     * product_type_tree方法，类型树.
     *
     * @param string $locale 语言代码
     *
     * @return string $product_type_list 返回类型树
     */
    public function product_type_tree($locale = 'chi')
    {
        $product_type_list = $this->find('all', array('conditions' => array('ProductType.id !=' => 0, 'ProductType.status' => 1, 'ProductTypeI18n.locale' => $locale), 'fields' => array('ProductType.id', 'ProductTypeI18n.name')));

        return $product_type_list;
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 类型ID
     *
     * @return array $lists_formated 返回类型树
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('ProductType.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['ProductType'] = $v['ProductType'];
            $lists_formated['ProductTypeI18n'][] = $v['ProductTypeI18n'];
            foreach ($lists_formated['ProductTypeI18n'] as $key => $val) {
                $lists_formated['ProductTypeI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
    public function gettypeformat($id = 0)
    {
        if ($id == 0) {
            $condition = '';
        } else {
            $condition = "ProductType.id = '".$id."' ";
        }

        $lists = $this->find('all', array('conditions' => $condition));

        $lists_formated = array();
        //pr($lists);die();
        if (is_array($lists)) {
            $i = 0;
        }
        foreach ($lists as $k => $v) {

                 //$lists_formated[$k]['ProductType']['name']='';
                 //$lists_formated[$k]['ProductType']['id']=9;
                 //pr($v);die();
                 //foreach($v['ProductTypeI18n'] as $key=>$val){
                      $lists_formated[$k]['ProductType']['name'] = $v['ProductTypeI18n']['name'];
            $lists_formated[$k]['ProductType']['id'] = $v['ProductTypeI18n']['type_id'];
                 //}
        }
        //pr($lists_formated);
        return $lists_formated;
    }
}
