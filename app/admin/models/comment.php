<?php

/*****************************************************************************
 * svoms 评论管理
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
class comment extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'Comment';

    public function getInfoById($id, $locale = 'chi')
    {
        $data = array();
        $cond['Comment.user_id'] = $id;
        $cond['Comment.parent_id'] = 0;
        $_data = $this->find('all', array('conditions' => $cond));
        if (!empty($_data)) {
            $comm_ids = array();
            $user_ids = array();
            $user_ids[$id] = $id;
            $comm_type_ids = array();
            foreach ($_data as $v) {
                $comm_ids[] = $v['Comment']['id'];
                $comm_type_ids[$v['Comment']['type']][$v['Comment']['type_id']] = $v['Comment']['type_id'];
            }
            $commdata = array();
            $comm_data = $this->find('all', array('conditions' => array('Comment.parent_id' => $comm_ids), 'order' => 'Comment.created asc'));
            if (isset($comm_type_ids['P'])) {
                $ProductI18n = ClassRegistry::init('ProductI18n');
                $pro_cond['ProductI18n.product_id'] = $comm_type_ids['P'];
                $pro_cond['ProductI18n.locale'] = $locale;
                $pro_list = $ProductI18n->find('list', array('conditions' => $pro_cond, 'fields' => array('ProductI18n.product_id', 'ProductI18n.name')));
            }
            if (isset($comm_type_ids['A'])) {
                $ArticleI18n = ClassRegistry::init('ArticleI18n');
                $article_cond['ArticleI18n.article_id'] = $comm_type_ids['A'];
                $article_cond['ArticleI18n.locale'] = $locale;
                $article_list = $ArticleI18n->find('list', array('conditions' => $article_cond, 'fields' => array('ArticleI18n.article_id', 'ArticleI18n.title')));
            }
            foreach ($comm_data as $k => $v) {
                $user_ids[$v['Comment']['user_id']] = $v['Comment']['user_id'];
            }
            $User = ClassRegistry::init('User');
            $user_list = $User->find('list', array('conditions' => array('User.id' => $user_ids), 'fields' => array('User.id', 'User.name')));
            foreach ($comm_data as $k => $v) {
                $v['Comment']['user'] = isset($user_list[$v['Comment']['user_id']]) ? $user_list[$v['Comment']['user_id']] : '';
                $commdata[$v['Comment']['parent_id']][] = $v;
            }
            foreach ($_data as $k => $v) {
                if ($v['Comment']['type'] == 'P') {
                    $v['Comment']['object'] = isset($pro_list[$v['Comment']['type_id']]) ? $pro_list[$v['Comment']['type_id']] : '';
                } elseif ($v['Comment']['type'] == 'A') {
                    $v['Comment']['object'] = isset($article_list[$v['Comment']['type_id']]) ? $article_list[$v['Comment']['type_id']] : '';
                } else {
                    $v['Comment']['object'] = '';
                }
                $v['Comment']['user'] = isset($user_list[$v['Comment']['user_id']]) ? $user_list[$v['Comment']['user_id']] : '';
                $v['CommentList'] = isset($commdata[$v['Comment']['id']]) ? $commdata[$v['Comment']['id']] : array();
                $data[$k] = $v;
            }
        }

        return $data;
    }
}
