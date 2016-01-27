<?php

/**
 * 包装模型.
 */
class packaging extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Packaging 包装选择表
     */
    public $name = 'Packaging';
    /*
     * @var $hasOne array 包装多语言表
     */
    public $hasOne = array('PackagingI18n' => array('className' => 'PackagingI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'packaging_id',
        ),
    );

    public function find_packaging_lists()
    {
        $packaging_lists = $this->find('all', array(
                    'fields' => array('Packaging.id', 'Packaging.img01', 'Packaging.fee', 'Packaging.free_money', 'PackagingI18n.name', 'PackagingI18n.description'),
                    'order' => array('Packaging.created desc'), 'conditions' => array('Packaging.status' => 1), ));

        return $packaging_lists;
    }

    public function get_packagings_info($packagings_info_conditions)
    {
        $packagings_info = $this->find('all', array('fields' => array('Packaging.id', 'Packaging.img01', 'Packaging.fee', 'Packaging.free_money', 'PackagingI18n.name', 'PackagingI18n.description'),
                    'conditions' => $packagings_info_conditions, ));

        return $packagings_info;
    }
}
