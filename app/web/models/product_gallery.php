<?php

/**
 * 商品相册模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 * @var 设置模型关联
 */
class ProductGallery extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ProductGallery';
    public $hasOne = array('ProductGalleryI18n' => array('className' => 'ProductGalleryI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'product_gallery_id',
        ),
    );

    public function get_product_gallery($type_id)
    {
        $galleries = $this->findall("ProductGallery.product_id = '$type_id'", null, 'orderby');

        return $galleries;
    }
    public function getProductPhotos($p_ids)
    {
        $allInfos = $this->find('all', array('conditions' => array('ProductGallery.product_id' => $p_ids), 'fields' => 'ProductGallery.product_id,ProductGallery.img_original'));
        $imgs = array();
        foreach ($allInfos as $v) {
            $imgs[$v['ProductGallery']['product_id']][] = $v['ProductGallery']['img_original'];
        }

        return $imgs;
    }
    /**
     *函数get_module_pro_photos 获取产品相册.
     */
    public function get_module_pro_photos($params)
    {
        $Product = ClassRegistry::init('Product');
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['ControllerObj'])) {
            if (isset($params['ControllerObj']->configs['products_detail_page_gallery_number'])) {
                $limit = $params['ControllerObj']->configs['products_detail_page_gallery_number'];
            }
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['id'])) {
            $conditions['ProductGallery.product_id'] = $params['id'];
        }
        if (isset($params['code']) && isset($params['option_type_id']) && $params['option_type_id'] == '2') {
            $SkuProduct = ClassRegistry::init('SkuProduct');
            $sku_list = $SkuProduct->find('list', array('fields' => array('SkuProduct.sku_product_code'), 'conditions' => array('SkuProduct.product_code' => $params['code'])));
            $sku_pids = $Product->find('list', array('fields' => array('Product.id'), 'conditions' => array('Product.code' => $sku_list)));
            if (isset($params['id'])) {
                $sku_pids[] = $params['id'];
            }
            $conditions['ProductGallery.product_id'] = $sku_pids;
        }
        $conditions['ProductGallery.status'] = 1;

        if ($Product->check_product($params['id'])) {
            $p = $Product->find('first', array('conditions' => array('Product.id' => $params['id'])));
            $product_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'ProductGallery.'.$order, 'fields' => 'ProductGallery.product_id,ProductGallery.img_thumb,ProductGallery.img_detail,ProductGallery.img_big,ProductGallery.img_original'));
            foreach ($product_infos as $k => $v) {
                $product_infos[$k]['Product'] = isset($p['Product']) ? $p['Product'] : array();
            }
            if (empty($product_infos)) {
                $product_infos[0]['ProductGallery']['product_id'] = $params['id'];
                $product_infos[0]['ProductGallery']['img_thumb'] = isset($p['Product']['img_thumb']) ? $p['Product']['img_thumb'] : '';
                $product_infos[0]['ProductGallery']['img_detail'] = isset($p['Product']['img_detail']) ? $p['Product']['img_detail'] : '';
                $product_infos[0]['ProductGallery']['img_big'] = isset($p['Product']['img_big']) ? $p['Product']['img_big'] : '';
                $product_infos[0]['ProductGallery']['img_original'] = isset($p['Product']['img_original']) ? $p['Product']['img_original'] : '';
                $product_infos[0]['Product']['video'] = '';
            }

            return $product_infos;
        }
    }
}
