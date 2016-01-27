<?php

/**
 * 门户管理模型.
 */
class portal extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'default';
    /*
     * @var $name WeiboRb 
     */
    public $name = 'Portal';

    //按img重组数组
    public function img_array($Portal_list)
    {
        $new_list = array();
        if (!empty($Portal_list)) {
            foreach ($Portal_list as $k => $v) {
                $Portal_list[$k]['Portal']['id'] = $v['Portal']['type'].$v['Portal']['id'];
                $new_list[$v['Portal']['type'].$v['Portal']['id']] = $Portal_list[$k]['Portal']['img'];
            }
        }

        return $new_list;
    }

    //按id重组数组
    public function type_array($Portal_list)
    {
        $new_list = array();
        if (!empty($Portal_list)) {
            foreach ($Portal_list as $k => $v) {
                $Portal_list[$k]['Portal']['id'] = $v['Portal']['type'].$v['Portal']['id'];
                $new_list[$v['Portal']['type'].$v['Portal']['id']] = $Portal_list[$k]['Portal'];
            }
        }

        return $new_list;
    }

    //按list重组数组
    public function list_array($Portal_list)
    {
        $new_list = array();
        foreach ($Portal_list as $k => $v) {
            if ($v['Portal']['default_list'] == 'list1') {
                $new_arr1 = array();
                $new_arr1['id'] = $v['Portal']['type'].$v['Portal']['id'];
                if ($v['Portal']['default_min'] == 0) {
                    $new_arr1['min'] = 'true';
                } else {
                    $new_arr1['min'] = 'false';
                }
                $new_list['list1'][$new_arr1['id']] = $new_arr1;
            }
            if ($v['Portal']['default_list'] == 'list2') {
                $new_arr2 = array();
                $new_arr2['id'] = $v['Portal']['type'].$v['Portal']['id'];
                if ($v['Portal']['default_min'] == 0) {
                    $new_arr2['min'] = 'true';
                } else {
                    $new_arr2['min'] = 'false';
                }
                $new_list['list2'][$new_arr2['id']] = $new_arr2;
            }
            if ($v['Portal']['default_list'] == 'list3') {
                $new_arr3 = array();
                $new_arr3['id'] = $v['Portal']['type'].$v['Portal']['id'];
                if ($v['Portal']['default_min'] == 0) {
                    $new_arr3['min'] = 'true';
                } else {
                    $new_arr3['min'] = 'false';
                }
                $new_list['list3'][$new_arr3['id']] = $new_arr3;
            }
        }

        return $new_list;
    }
}
