<?php
    $result=array();
    $result_event=array();
    if(!empty($comment_infos)){
        foreach($comment_infos as $k=>$v){
            $result_event[$k]=$v['Comment'];
            $result_event[$k]['user_name']=isset($v['User']['name'])&&isset($v['Comment']['is_public'])&&$v['Comment']['is_public']=='0'?$v['User']['name']:'匿名';
            $result_event[$k]['user_img']=!empty($v['User']['img01'])?$v['User']['img01']:'/theme/default/img/no_head.png';
        }
    }
    $result['page']=$page;
    $result['total']=$comment_total;
    $result['start']=$start;
    $result['count']=$limit;
    $result['events']=$result_event;
    $result_txt=$callback."(".json_encode($result).")";
    die($result_txt);
?>