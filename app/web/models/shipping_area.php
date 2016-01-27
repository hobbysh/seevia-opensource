<?php

/**
 * 配送方式区域模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 */
class ShippingArea extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ShippingArea';

    /**
     * 函数fee_calculation_other 额外的配送费用计算.
     *
     * @param $weight 商品重量
     * @param $info 商品信息
     * @param $subtotal 费用和
     * @param $fee 费用
     * @param $fee_areas 区域费用
     * @param $weight_fees 总费用
     * @param $weight_fee_areas 净费用
     *
     * @return $fee 配送费用
     */
    public function fee_calculation_other($weight, $info, $subtotal)
    {
        $fee = 0;
        if ($subtotal < $info['free_subtotal'] || $info['free_subtotal'] == 0) {
            $fee_areas = explode(';', $info['fee_configures']);
            foreach ($fee_areas as $k => $v) {
                $weight_fees = explode(':', $v);
                if (is_array($weight_fees) && $weight_fees[0] >= 0 && isset($weight_fees[1])) {
                    $weight_fee_areas[$weight_fees[0]] = $weight_fees[1];
                }
            }
        }

        if (isset($weight_fee_areas)) {
            ksort($weight_fee_areas);
            foreach ($weight_fee_areas as $k => $v) {
                if ($weight > $k) {
                    $fee = $v;
                }
            }
        }
        if ($fee == '') {
            $fee = 0;
        }

        return $fee;
    }

    /**
     * 函数fee_calculation 配送费用计算.
     *
     * @param $weight 包裹重量
     * @param $info 包裹信息
     * @param $subtotal 费用和
     * @param $fee_info 费用信息
     * @param $fee 费用
     * @param $other_fee 另外的费用
     *
     * @return $fee 包裹费用
     */
    public function fee_calculation($weight, $info, $subtotal)
    {
        $weight *= 1000; //kg=>g
        $fee_info = @unserialize(StripSlashes($info['fee_configures']));
        $fee = 0;
        if ($subtotal < $info['free_subtotal'] || $info['free_subtotal'] == 0) {
            if ($weight <= 1000 && isset($fee_info['0']['value'])) {
                return $fee_info['0']['value'];
            } elseif ($weight <= 5000 && isset($fee_info['0']['value']) && isset($fee_info['1']['value'])) {
                $other_fee = ceil(($weight - 1000) / 500) * $fee_info['1']['value'];

                return $fee_info['0']['value'] + $other_fee;
            } elseif (isset($fee_info['0']['value']) && isset($fee_info['2']['value'])) {
                $other_fee = ceil(($weight - 1000) / 500) * $fee_info['2']['value'];

                return $fee_info['0']['value'] + $other_fee;
            }
        }

        return $fee;
    }
}
