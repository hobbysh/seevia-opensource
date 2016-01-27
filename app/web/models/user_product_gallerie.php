<?php

/**
 * 用户相册模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 */
class UserProductGallerie extends AppModel
{
    public $name = 'UserProductGallerie';

    public function find_allgalleries($condition)
    {
        $allgalleries = $this->find('all', array(
                    'fields' => array('UserProductGallerie.id', 'UserProductGallerie.product_id',
                        'UserProductGallerie.user_id', 'UserProductGallerie.img', 'UserProductGallerie.created', ),
                    'conditions' => array($condition), 'order' => 'UserProductGallerie.created DESC', ));

        return $allgalleries;
    }
}
