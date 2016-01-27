<?php

/**
 * 用户相册模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 */
class UserProductGallery extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'UserProductGallerie';

    public function find_allgalleries($condition)
    {
        $allgalleries = $this->find('all', array(
                    'fields' => array('UserProductGallery.id', 'UserProductGallery.product_id',
                        'UserProductGallery.user_id', 'UserProductGallery.img', 'UserProductGallery.created', ),
                    'conditions' => array($condition), 'order' => 'UserProductGallery.created DESC', ));

        return $allgalleries;
    }
}
