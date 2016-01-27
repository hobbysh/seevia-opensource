<?php

/**
 * 用户日志模型.
 *
 * @todo 一些函数改用find list
 */
class blog extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    /*
     * @var $name Blog flash图片参数表
     */
    public $name = 'Blog';
    /*查询一个用户的日志（日记）数量*/
    public function find_blogcount_byuserid($user_id)
    {
        $blog = $this->find('count', array('conditions' => array('user_id' => $user_id, 'status' => 1, 'parent_id' => 0))); //用户的粉丝数量
        return $blog;
    }

    /*查询用户关注数量*/
    public function find_blogcount_byuseridarr($user_idarr)
    {
        $blog = $this->find('all', array('fields' => array('user_id', 'count(id) as blogcount'), 'conditions' => array('user_id' => $user_idarr, 'status' => 1, 'parent_id' => 0), 'group' => 'user_id')); //用户的粉丝数量
        return $blog;
    }

    /**
     * getlist方法，日志（日记）列表.
     *
     * @return $blog_list 返回取得的数组
     */
    public function getlist($user_id)
    {
        $blog_list = $this->find('all', array('conditions' => array('user_id' => $user_id, 'status' => 1)));

        return $blog_list;
    }
}
