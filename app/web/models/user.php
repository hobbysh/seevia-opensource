<?php

/**
 * 用户模型.
 */
class user extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'User';
    public $hasOne = array('UserConfig' => array(
            'className' => 'UserConfig',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'user_id',
        ),
    );

    public $belongsTo = array(
        'UserRank' => array(
        'className' => 'UserRank',
        'conditions' => 'UserRank.id=User.rank',
        'order' => '',
        'dependent' => true,
        'foreignKey' => '',
        ), );

    public function find_user_by_id($user_id)
    {
        $user = $this->findbyid($user_id); //标记
        return $user;
    }

    /*
    	更新登录用户的$_session['User']信息
    */
    public function changeUserSession()
    {
        $userInfo = array();
        if (isset($_SESSION['User'])) {
            $user_id = $_SESSION['User']['User']['id'];
            $userInfo = $this->find('first', array('conditions' => array('User.id' => $user_id)));
            $_SESSION['User'] = $userInfo;
        }

        return $userInfo;
    }

    public function get_user_list($users_conditions)
    {
        $users = $this->find('list', array('conditions' => $users_conditions,
                    'fields' => array('User.id'), ));

        return $users;
    }

    public function get_user_all($user_conditions)
    {
        $users = $this->find('all', array('conditions' => $user_conditions,
                    'fields' => array('User.id', 'User.name'), ));

        return $users;
    }
    /**
     * get_module_infos方法，获取模块数据.
     *
     * @param  查询参数集合
     *
     * @return $user_infos 根据param，返回数组
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created Desc';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        $conditions['User.status'] = 1;
        $user_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'User.'.$order, 'fields' => 'User.id,User.name,User.img01,User.created'));

        return $user_infos;
    }
}
