<?php

/*****************************************************************************
 * svoms 用户留言管理
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
class UserLike extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    public $name = 'UserLike';

    public function getInfoById($id, $locale = 'chi')
    {
        $data = array();
        $_data = $this->find('all', array('conditions' => array('UserLike.user_id' => $id)));
        if (!empty($_data)) {
            $pro_ids = array();
            foreach ($_data as $v) {
                if ($v['UserLike']['type'] == 'P') {
                    $pro_ids[] = $v['UserLike']['type_id'];
                }
            }
            $ProductI18n = ClassRegistry::init('ProductI18n');
            $pro_cond['ProductI18n.product_id'] = $pro_ids;
            $pro_cond['ProductI18n.locale'] = $locale;
            $pro_list = $ProductI18n->find('list', array('conditions' => $pro_cond, 'fields' => array('ProductI18n.product_id', 'ProductI18n.name')));
            foreach ($_data as $k => $v) {
                if ($v['UserLike']['type'] == 'P') {
                    $v['UserLike']['object'] = isset($pro_list[$v['UserLike']['type_id']]) ? $pro_list[$v['UserLike']['type_id']] : $v['UserLike']['type_id'];
                } else {
                    $v['UserLike']['object'] = $v['UserLike']['type_id'];
                }
                $data[$k] = $v;
            }
        }

        return $data;
    }
}
