<?php

/*****************************************************************************
 * svcms 留言管理
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
class contact extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    public $name = 'Contact';

    /*
    *获取行业、获知方式数组
    * @param  查询参数集合
    * @return $sel_list 根据param，返回行业、获知方式数组
    */
    public function get_contact_sel_list($params)
    {
        $sel_list = array();
        if (isset($params['industry'])) {
            $industry = preg_split('/[\n\r\t\s]+/i', $params['industry']);
            $sel_list['industry'] = array_filter($industry);
        }
        if (isset($params['learn_us'])) {
            $learn_us = preg_split('/[\n\r\t\s]+/i', $params['learn_us']);
            $sel_list['learn_us'] = array_filter($learn_us);
        }

        return $sel_list;
    }
}
