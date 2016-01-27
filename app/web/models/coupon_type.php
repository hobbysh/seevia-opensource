<?php

/**
 * 优惠券类型模型.
 */
class CouponType extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
    *@var $name CouponType 优惠券表
    */
    public $name = 'CouponType';
    /*
    *@var $hasOne array 优惠券多语言表
    */
    public $hasOne = array('CouponTypeI18n' => array('className' => 'CouponTypeI18n',
                              'conditions' => array('locale' => LOCALE),
                              'order' => 'CouponType.id',
                              'dependent' => true,
                              'foreignKey' => 'coupon_type_id',
                        ),
                  );

    //数组结构调整
    /**
    *localeformat方法，返回语言所对应的数据.
    *
    *@param $id 输入id
    *
    *@return $lists_formated 检索对应id的数据，
    */
    public function localeformat($id)
    {
        $lists = $this->findAll("CouponType.id = '".$id."'");
    //	pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['CouponType'] = $v['CouponType'];
            $lists_formated['CouponTypeI18n'][] = $v['CouponTypeI18n'];
            foreach ($lists_formated['CouponTypeI18n'] as $key => $val) {
                $lists_formated['CouponTypeI18n'][$val['locale']] = $val;
            }
        }
    //	pr($lists_formated);
        return $lists_formated;
    }
    public function find_coupon_type_arr()
    {
        $coupon_type_arr = $this->find('all', array('conditions' => array('1=1'), 'fields' => array('CouponType.id', 'CouponType.money', 'CouponType.prefix', 'CouponType.use_end_date', 'CouponType.use_start_date', 'CouponTypeI18n.name')));

        return $coupon_type_arr;
    }
    public function find_order_coupon_type($now)
    {
        $order_coupon_type = $this->find('all', array(
                    'fields' => array('CouponType.id', 'CouponType.min_products_amount', 'CouponType.money', 'CouponType.prefix', 'CouponType.use_end_date', 'CouponType.use_start_date', 'CouponTypeI18n.name'),
                    'conditions' => array("CouponType.send_type = '2' and CouponType.send_start_date <= '".$now."' and CouponType.send_end_date >= '".$now."'"), ));

        return $order_coupon_type;
    }
    //获取name
    public function getCouponName($id)
    {
        $lists = $this->find('all', array('conditions' => array('CouponType.id' => $id), 'fields' => 'CouponType.id,CouponTypeI18n.name'));
        $lists_formated = array();
        if (!empty($lists)) {
            foreach ($lists as $l) {
                $lists_formated[$l['CouponType']['id']] = $l['CouponTypeI18n']['name'];
            }
        }

        return $lists_formated;
    }
}
