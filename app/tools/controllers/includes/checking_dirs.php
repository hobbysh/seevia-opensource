<?php

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$checking_dirs = array(
                    /* 取消检测uc_client */
                    //'uc_client/data',
                    '/data',
                    '/data/tools',
                    '/media'
                    );

?>