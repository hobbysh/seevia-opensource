<?php

class csv extends AppModel
{
    /*
     * @var $name Profile 
     */
    public $name = 'Csv';
    public $useTable = false;

    /**
     * getProfile方法 获取字段对应关系.
     *
     * @param string $csv_tyep
     *
     * @return string[] $new_key_arr 
     */
    public function getProfile($csv_type)
    {
        if ($csv_type != '') {
            $new_key_arr['SVCART'] = array(
            '0' => 'name',//商品名称
            '1' => 'meta_keywords',//
            '2' => 'meta_description',//
            '3' => 'description',//
            '4' => 'code',//货号
            '5' => 'brand',//品牌
            '6' => 'provider',//供应商ID
            '7' => 'shop_price',//本店价
            '8' => 'market_price',//市场价
            '9' => 'weight',//重量
            '10' => 'quantity',//数量
            '11' => 'recommand_flag',//推荐标致1有效0无效
            '12' => 'forsale',//上架
            '13' => 'alone',//能作为普通商品销售
            '14' => 'extension_code',//虚拟卡标致
            '15' => 'img_thumb',//缩略图
            '16' => 'img_detail',//详细图
            '17' => 'img_original',//原图
            '18' => 'min_buy',//最小购买数
            '19' => 'max_buy',//最大购买数
            '20' => 'point',//送积分
            '21' => 'point_fee',//积分费用
            '22' => 'purchase_price',//进货价
            '23' => 'category_type_code',//类目code

            );
            $new_key_arr['ECSHOP'] = array(
                '商品名称' => 'name',
                '商品货号' => 'code',
                '商品品牌' => 'brand',
                '市场售价' => 'market_price',
                '本店售价' => 'shop_price',
                '积分购买额度' => 'point',
                '商品原始图' => 'img_original',
                '商品图片' => 'img_detail',
                '商品缩略图' => 'img_thumb',
                '商品关键词' => 'meta_keywords',
                '简单描述' => 'meta_description',
                '详细描述' => 'description',
                '商品重量（kg）' => 'weight',
                '库存数量' => 'quantity',
                '库存警告数量' => '',
                '是否精品' => '',
                '是否新品' => '',
                '是否热销' => 'recommand_flag',
                '是否上架' => 'forsale',
                '能否作为普通商品销售' => 'alone',
                '是否实体商品' => 'extension_code',
            );
            $key_arr = array('name','meta_keywords','meta_description','description','code','brand','provider','shop_price','market_price','weight','quantity','recommand_flag','forsale','alone','extension_code','img_thumb','img_detail','img_original','min_buy','max_buy','point','point_fee');

            return $new_key_arr[$csv_type];
        }
    }
}
