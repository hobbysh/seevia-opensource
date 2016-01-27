<?php

/**
 * 商品下载日志模型.
 */
class ProductDownloadLog extends AppModel
{
    /*
     * @var $name 用来解决PHP4中的一些奇怪的类名
     */
    public $name = 'ProductDownloadLog';

    public function get_download_log_allow($order, $product, $user_id)
    {
        $conditions = "ProductDownloadLog.order_id='".$order."'and ProductDownloadLog.product_id='".$product."'and ProductDownloadLog.user_id='".$user_id."'";
        $allow = $this->findCount($conditions);

        return $allow;
    }
}
