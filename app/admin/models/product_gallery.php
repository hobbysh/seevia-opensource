<?php

/**
 * 商品相册模型.
 */
class ProductGallery extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name ProductGallery 商品相册
     */
    public $name = 'ProductGallery';

    /*
     * @var $hasOne array 关联商品相册多语言表
     */
    public $hasOne = array('ProductGalleryI18n' => array('className' => 'ProductGalleryI18n',
                            'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'product_gallery_id',
                        ),
                  );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " ProductGalleryI18n.locale = '".$locale."'";
        $this->hasOne['ProductGalleryI18n']['conditions'] = $conditions;
    }

    /**
     * product_gallery_format方法，商品相册数组结构调整.
     *
     * @param int $product_id 输入商品编号
     *
     * @return array $product_gallery_format 返回商品相册数组
     */
    public function product_gallery_format($product_id)
    {
        $lists = $this->find('all', array('conditions' => array('ProductGallery.product_id' => $product_id), 'order' => 'ProductGallery.orderby asc'));
        $product_gallery_format = array();
        foreach ($lists as $k => $v) {
            $product_gallery_format[$v['ProductGallery']['id']]['ProductGallery'] = $v['ProductGallery'];
            $product_gallery_format[$v['ProductGallery']['id']]['ProductGalleryI18n'][$v['ProductGalleryI18n']['locale']] = $v['ProductGalleryI18n'];
        }
        $product_gallery_format_new = array();
        foreach ($product_gallery_format as $k => $v) {
            $product_gallery_format_new[] = $v;
        }

        return $product_gallery_format_new;
    }
}
