<?php

/**
 * 发票类型模型.
 */
class InvoiceType extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name InvoiceType 发票表
     */
    public $name = 'InvoiceType';
    /*
     * @var $cacheQueries true 是否开启缓存：是。
     */
    public $cacheQueries = true;
    /*
     * @var $cacheAction 1day 缓存时间：1天。
     */
    public $cacheAction = '1 day';
    /*
     * @var $hasOne array 关联发票多语言表
     */
    public $hasOne = array('InvoiceTypeI18n' => array('className' => 'InvoiceTypeI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'invoice_type_id',
        ),
    );

    public function get_cache_invoice_type($locale)
    {
        $invoice_type = $this->find('all', array('condition' => array('InvoiceType.status' => '1'), 'order' => 'InvoiceType.created DESC'), 'checkout_invoice_'.$locale);

        return $invoice_type;
    }
}
