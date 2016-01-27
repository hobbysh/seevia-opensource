<?php

    /*
        svsns_blogs
        用户评论
    */
class blog extends AppModel
{
    public $useDbConfig = 'sns';
    public $name = 'Blog';

    public function getInfoById($id)
    {
        $data = array();
        $cond['Blog.user_id'] = $id;
        $cond['Blog.parent_id'] = 0;
        $_data = $this->find('all', array('conditions' => $cond));
        if (!empty($_data)) {
            $blog_ids = array();
            $user_ids = array();
            $user_ids[$id] = $id;
            foreach ($_data as $v) {
                $blog_ids[] = $v['Blog']['id'];
            }
            $commdata = array();
            $comm_data = $this->find('all', array('conditions' => array('Blog.parent_id' => $blog_ids), 'order' => 'Blog.created asc'));
            foreach ($comm_data as $k => $v) {
                $user_ids[$v['Blog']['user_id']] = $v['Blog']['user_id'];
            }
            $User = ClassRegistry::init('User');
            $user_list = $User->find('list', array('conditions' => array('User.id' => $user_ids), 'fields' => array('User.id', 'User.name')));
            foreach ($comm_data as $k => $v) {
                $v['Blog']['user'] = isset($user_list[$v['Blog']['user_id']]) ? $user_list[$v['Blog']['user_id']] : '';
                $commdata[$v['Blog']['parent_id']][] = $v;
            }
            foreach ($_data as $k => $v) {
                $v['Blog']['user'] = isset($user_list[$v['Blog']['user_id']]) ? $user_list[$v['Blog']['user_id']] : '';
                $v['CommentList'] = isset($commdata[$v['Blog']['id']]) ? $commdata[$v['Blog']['id']] : array();
                $data[$k] = $v;
            }
        }

        return $data;
    }
}
