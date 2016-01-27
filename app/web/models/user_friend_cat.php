<?php

/**
 * 用户好友分类模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 */
class UserFriendCat extends AppModel
{
    public $name = 'UserFriendCat';

    public function get_user_friend($user_id)
    {
        $friend_cat_list = $this->find('all', array('conditions' => "UserFriendCat.user_id='".$user_id."' or UserFriendCat.user_id = '0'",
                    'order' => 'UserFriendCat.user_id ASC', ));

        return $friend_cat_list;
    }
}
