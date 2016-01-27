<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#detail"><?php echo $ld['message_detail']?></a></li>
        <li><a href="#reply"><?php echo $ld['reply_message']?></a></li>
    </ul>
</div>
<?php echo $form->create('Message',array('action'=>'view/'.$usermessage['UserMessage']['id']));?>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="detail" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['message_detail']?></h4>
        </div>
        <div id="message_detail" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th><?php echo $ld['message_products']?></th>
                        <td><?php echo $usermessage['UserMessage']['msg_title']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['message_content']?></th>
                        <td><?php echo $usermessage['UserMessage']['msg_content']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['the_message_user']?></th>
                        <td><?php echo $usermessage['UserMessage']['user_name']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['message_time']?></th>
                        <td><?php echo $usermessage['UserMessage']['created']?></td>
                    </tr>
                    <?php if( isset( $restore ) ){ foreach($restore as $k=>$v){?>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <th><?php echo $ld['administrator']?></th>
                            <td><?php echo $v['UserMessage']['user_name'] ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $ld['reply_content']?></th>
                            <td><?php echo $v['UserMessage']['msg_content'] ?></td>
                        </tr>

                        <tr>
                            <th><?php echo $ld['resply_time']?></th>
                            <td><?php echo $v['UserMessage']['created'] ?></td>
                        </tr>
                    <?php }}?>
                </table>
                <input type="hidden" name="data[UserMessage][parent_id]" value="<?php echo $usermessage['UserMessage']['id']; ?>">
            </div>
        </div>
    </div>
    <div id="reply" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['reply_message']?></h4>
        </div>
        <div id="reply_message" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th><?php echo $ld['administrator']?></th>
                        <td colspan="3"><?php echo $admin['name']?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['reply_content']?></th>
                        <td colspan="3"><textarea name="data[UserMessage][msg_content]" style="width:353px;overflow-y:scroll;height:62px;"></textarea></td>
                    </tr>
                    <?php if( isset( $restore ) && false){?>
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="3"><?php echo $ld['note_message_replied']?></td>
                        </tr>
                    <?php }?>
                </table>
                <div class="btnouter">
                    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="return cha2()" value="<?php echo $ld['d_submit']?>" /> <input class="am-btn am-btn-success am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>