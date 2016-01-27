<?php

/*****************************************************************************
 * svoms �û����Թ���
 * ===========================================================================
 * ��Ȩ����  �Ϻ�ʵ������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�ã�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ===========================================================================
 * $����: �Ϻ�ʵ��$
 * $Id$
*****************************************************************************/
class UserMessage extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'oms';
    public $name = 'UserMessage';

    public function getInfoById($id, $locale = 'chi')
    {
        $data = array();
        $cond['UserMessage.from_id'] = $id;
        $cond['UserMessage.to_id'] = 0;
        $cond['UserMessage.parent_id'] = 0;
        $_data = $this->find('all', array('conditions' => $cond, 'order' => 'UserMessage.created desc'));
        if (!empty($_data)) {
            $user_message_ids = array();
            $user_ids = array();
            $user_ids[$id] = $id;
            $user_message_type_ids = array();
            foreach ($_data as $v) {
                $user_message_ids[] = $v['UserMessage']['id'];
                $user_message_type_ids[$v['UserMessage']['type']][$v['UserMessage']['value_id']] = $v['UserMessage']['value_id'];
            }
            if (isset($user_message_type_ids['P'])) {
                $ProductI18n = ClassRegistry::init('ProductI18n');
                $pro_cond['ProductI18n.product_id'] = $user_message_type_ids['P'];
                $pro_cond['ProductI18n.locale'] = $locale;
                $pro_list = $ProductI18n->find('list', array('conditions' => $pro_cond, 'fields' => array('ProductI18n.product_id', 'ProductI18n.name')));
            }
            if (isset($user_message_type_ids['A'])) {
                $ArticleI18n = ClassRegistry::init('ArticleI18n');
                $article_cond['ArticleI18n.article_id'] = $user_message_type_ids['A'];
                $article_cond['ArticleI18n.locale'] = $locale;
                $article_list = $ArticleI18n->find('list', array('conditions' => $article_cond, 'fields' => array('ArticleI18n.article_id', 'ArticleI18n.title')));
            }
            $commdata = array();
            $comm_data = $this->find('all', array('conditions' => array('UserMessage.parent_id' => $user_message_ids), 'order' => 'UserMessage.created desc'));
            foreach ($comm_data as $k => $v) {
                $user_ids[$v['UserMessage']['user_id']] = $v['UserMessage']['user_id'];
            }
            $User = ClassRegistry::init('User');
            $user_list = $User->find('list', array('conditions' => array('User.id' => $user_ids), 'fields' => array('User.id', 'User.name')));
            foreach ($comm_data as $k => $v) {
                $v['UserMessage']['user_name'] = isset($user_list[$v['UserMessage']['user_id']]) ? $user_list[$v['UserMessage']['user_id']] : '';
                $commdata[$v['UserMessage']['parent_id']][] = $v;
            }
            foreach ($_data as $k => $v) {
                if ($v['UserMessage']['type'] == 'P') {
                    $v['UserMessage']['object'] = isset($pro_list[$v['UserMessage']['value_id']]) ? $pro_list[$v['UserMessage']['value_id']] : '';
                } elseif ($v['UserMessage']['type'] == 'A') {
                    $v['UserMessage']['object'] = isset($article_list[$v['UserMessage']['value_id']]) ? $article_list[$v['UserMessage']['value_id']] : '';
                } else {
                    $v['UserMessage']['object'] = '';
                }
                $v['UserMessage']['user_name'] = isset($user_list[$v['UserMessage']['user_id']]) ? $user_list[$v['UserMessage']['user_id']] : '';
                $v['CommentList'] = isset($commdata[$v['UserMessage']['id']]) ? $commdata[$v['UserMessage']['id']] : array();
                $data[$k] = $v;
            }
        }

        return $data;
    }
}
