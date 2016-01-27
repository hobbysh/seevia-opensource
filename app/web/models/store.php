<?php

/**
 * 店铺模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 * @var 设置模型关联
 */
class store extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'Store';
    public $hasOne = array('StoreI18n' => array('className' => 'StoreI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'store_id',
        ),
    );

    public function set_locale($locale)
    {
        $conditions = " StoreI18n.locale = '".$locale."'";
        $this->hasOne['StoreI18n']['conditions'] = $conditions;
    }

    public function get_all_stores($locale)
    {
        $stores = $this->find('all', array('conditions' => array('Store.status' => 1, 'Store.store_type' => 1), 'order' => 'Store.orderby asc'),
                        'all_stores_'.$locale);

        return $stores;
    }
}
