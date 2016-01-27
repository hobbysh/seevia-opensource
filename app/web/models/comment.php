<?php

/**
 * 商品评论模型.
 */
class comment extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Comment 商品类型表
     */
    public $name = 'Comment';

    /*
      评论列表
     */

    /**
     * get_list方法，获得列表并按升序排列.
     *
     * @param $type 输入类型
     * @param $id 输入id
     *
     * @return $Lists 返回列表
     *
     * @todo 修改get_list为get_position_list以及相关调用和其中的findAll
     */
    public function get_list($type, $id = '')
    {
        $Lists = array();
        $conditions = "status ='1'";
        $conditions .= " AND type = '".$type."'";
        if ($id != '') {
            $conditions .= " AND type_id = '".$id."'";
        }

        $Lists = $this->findAll($conditions, '', 'modified asc');

        return $Lists;
    }

    public function find_comments($locale)
    {
        $comments = $this->find('all', array('order' => 'Comment.created',
                    'conditions' => array('Comment.type' => 'P', 'Comment.status' => 1), 'limit' => 5, ),
                        'Comment_home_prodcut_'.$locale);

        return $comments;
    }

    public function find_new_comments($id)
    {
        $new_comments = $this->find('all', array('conditions' => array('Comment.user_id' => $id),
                    'fields' => array('Comment.id', 'Comment.type', 'Comment.rank', 'Comment.type_id', 'Comment.content'),
                    'order' => array('Comment.created DESC'),
                    'limit' => 4, ));

        return $new_comments;
    }

    public function get_my_comments($condition)
    {
        $my_comments = $this->find('all', array('order' => 'Comment.created DESC',
                    //	   'fields' => array('Comment.id','Comment.type','Comment.type_id','Comment.title','Comment.user_id','Comment.parent_id','Comment.status','Comment.created','Comment.content'),
                    'conditions' => $condition, )); //,'limit'=>$rownum,'page'=>$page
        return $my_comments;
    }

    public function get_comments($condition, $rownum, $page)
    {
        $my_comments = $this->find('all', array('fields' => array('Comment.id', 'Comment.type', 'Comment.type_id', 'Comment.title', 'Comment.parent_id', 'Comment.status', 'Comment.created', 'Comment.content'),
                    'conditions' => array($condition),
                    'limit' => $rownum,
                    'page' => $page, ));

        return $my_comments;
    }

    public function get_products_comment($products_comment_conditions)
    {
        $products_comment = $this->find('all', array('conditions' => $products_comment_conditions,
                    'fields' => array('Comment.id', 'Comment.type', 'Comment.type_id', 'Comment.title', 'Comment.parent_id', 'Comment.status', 'Comment.created', 'Comment.content'),
                ));

        return $products_comment;
    }

    public function find_comments_by_num($id, $show_comments_number, $type = 'P')
    {
        $comments = $this->find('threaded', array('conditions' => "Comment.type_id = '$id' and Comment.type = '$type' and Comment.status = '1'", 'recursive' => '1', 'order' => 'Comment.created desc', 'limit' => $show_comments_number));

        return $comments;
    }

    public function find_comment_times($id)
    {
        $comment_times = $this->find('all', array('fields' => 'Comment.rank', 'conditions' => array('Comment.type_id' => $id, 'Comment.status' => 1, 'Comment.type' => 'P')));

        return $comment_times;
    }

    public function find_comments_by_list($products_ids_list, $locale)
    {
        $comments = $this->find('all', array('conditions' => array('Comment.type' => 'P', 'Comment.type_id' => $products_ids_list), 'status' => 1, 'limit' => 5), 'Comment_categories_prodcut_'.$locale);

        return $comments;
    }

    //取商品评论平均值和评论人数
    public function find_comment_rank($ids)
    {
        //$id= array('43','45');
        $comments = $this->find('all', array('conditions' => array('Comment.type' => 'P', 'Comment.type_id' => $ids), 'fields' => array('count(rank) as num', 'sum(rank) as addall', 'Comment.type_id'), 'group' => 'Comment.type_id'));
        //pr($comments);
        $comment_assoc = array();
        foreach ($comments as $k => $v) {
            $comment_assoc[$v['Comment']['type_id']] = array('comment_average' => round($v[0]['addall'] / $v[0]['num']), 'comment_num' => $v[0]['num']);
            //$comment=array($v['Comment'],array('comment_average'=>($v[0]['addall']/$v[0]['num']),'comment_num'=>$v[0]['num']));
        }
        //pr($comment_assoc);
        return $comment_assoc;
    }
    /**
     * get_module_infos方法，获取模块数据.
     *
     * @param  查询参数集合
     *
     * @return $link 根据param，返回数组
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'orderby';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        $conditions['Comment.type'] = 'A';
        $conditions['Comment.status'] = 1;
        $comment_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Comment.'.$order, 'fields' => 'Comment.id,Comment.type_id,Comment.name,Comment.content,Comment.created'));

        return $comment_infos;
    }
}
