<?php

/*****************************************************************************
 * svoms  快递公司模型
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
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
        $logistics_company_list = $this->find('all', array('conditions' => array('fettle' => 1), 'fields' => $fields));

        return $logistics_company_list;
    }
}
