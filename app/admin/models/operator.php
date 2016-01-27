<?php

    /*****************************************************************************
 * svsys 操作员模型
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
class operator extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';
    /*
     * @var $name Operator 操作员源库
     */
    public $name = 'Operator';
    /**
     * operator_name_list方法，获取操作员名称.
     *
     * @return array $operator_data_format 返回操作员数据
     */
    public function operator_name_list($ids = '')
    {
        //条件
        $condition = '';
        //$condition["status"] = 1;
        if ($ids != '') {
            $condition['id'] = $ids;
        }
        $fields = array('id','name');//查找的字段
        $operator_data = $this->find('all', array('conditions' => $condition, 'fields' => $fields));
        $operator_data_format = array();
        foreach ($operator_data as $k => $v) {
            $operator_data_format[$v['Operator']['id']] = $v['Operator']['name'];
        }

        return $operator_data_format;
    }

    /**
     * logout方法，操作员退出登录.
     */
    public function logout()
    {
        $cookie = new CookieComponent();
        $cookie->delete('session');
        unset($_SESSION['session']);
    }

    /**
     * check_login方法，获取操作员名称.
     *
     * @return bool 返回成功失败
     */
    public function check_login()
    {
        $conf = new Configure();
        $cookie = new CookieComponent();
        $cookie->key = $conf->read('Security.salt');
        $cookie_session = $cookie->read('session');
        $session = isset($cookie_session) && $cookie_session != '' ? $cookie_session : session_id();
        $operator = $this->findBySession($session);
        if (isset($operator['Operator']['id'])) {
            return $operator['Operator'];
        } else {
            return false;
        }
    }
}
