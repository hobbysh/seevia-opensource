<?php
/*判断数据是否为json格式*/
function is_not_json($str){
    return is_null(json_decode($str));
}
?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="basic_info" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <?php if(isset($messageInfo)&&!empty($messageInfo)){ ?>
                    <table class="am-table">
                        <tr>
                            <th><?php echo '公众平台类型'?></th>
                            <td><?php echo $messageInfo['OpenUserMessage']['open_type']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo '公众平台帐号'?></th>
                            <td><?php echo $messageInfo['OpenUserMessage']['open_type_id']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo '用户'?></th>
                            <td><?php echo $html->link(urldecode($messageInfo['OpenUser']['nickname']),"/open_users/view/{$messageInfo['OpenUserMessage']['open_user_id']}"); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo '发送对象'?></th>
                            <td><?php echo $messageInfo['OpenUserMessage']['send_from']=="0"?"用户":$ld['system']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo '消息类型'?></th>
                            <td><?php echo $messageInfo['OpenUserMessage']['msgtype']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo '消息内容'?></th>
                            <td><?php
                                if(is_not_json($messageInfo['OpenUserMessage']['message'])){
                                    echo $messageInfo['OpenUserMessage']['message'];
                                }else{
                                    pr(json_decode($messageInfo['OpenUserMessage']['message']));
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo '回复内容'?></th>
                            <td>
                                <div style="width:90%;overflow:hidden;">
                                    <?php
                                    if(is_not_json($messageInfo['OpenUserMessage']['return_message'])){
                                        echo $messageInfo['OpenUserMessage']['return_message'];
                                    }else{
                                        pr(json_decode($messageInfo['OpenUserMessage']['return_message']));
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo '发送时间'?></th>
                            <td><?php echo $messageInfo['OpenUserMessage']['created']; ?></td>
                        </tr>
                    </table>
                <?php }else{ ?>
                    记录不存在
                <?php } ?>
            </div>
        </div>
    </div>
</div>