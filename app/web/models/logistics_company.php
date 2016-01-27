<?php

/**
 * 快递公司模型.
 */
class LogisticsCompany extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name LogisticsCompany 快递公司
     */
    public $name = 'LogisticsCompany';

    /**
     * logistics_company_effective_list方法，获取有效的快递公司.
     *
     * @return string $logistics_company_list 返回有效的快递公司
     */
    public function logistics_company_effective_list()
    {
        $fields = array('id','code','name','express_code');
        $logistics_company_list = $this->find('all', array('fields' => $fields));

        return $logistics_company_list;
    }
}
