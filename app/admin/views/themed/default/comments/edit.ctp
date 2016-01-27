<?php
/*****************************************************************************
 * SV-Cart 新增评论
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
?>
<?php echo $form->create('Comment',array('action'=>'edit/'.$comment['Comment']['id']));?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#detail"><?php echo $ld['comment_detail']?></a></li>
        <li><a href="#reply"><?php echo $ld['reply_comments']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="detail" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['comment_detail']?></h4>
        </div>
        <div id="comment_detail" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr><th><?php echo $ld['comment_object']?></th><td><?php echo $comment['Comment']['type_name']; ?></td></tr>
                    <tr><th><?php echo $ld['comment_content']?></th><td><?php echo $comment['Comment']['content']; ?></td></tr>
                    <tr><th><?php echo $ld['the_user_comments']?></th><td><?php echo isset($comment['User']['name'])?$comment['User']['name']:$comment['Comment']['name']; ?></td></tr>
                    <tr><th><?php echo $ld['time_to_comment']?></th><td><?php echo $comment['Comment']['created']; ?></td></tr>
                    <tr><th><?php echo $ld['comment_rank']?></th><td><?php echo $comment['Comment']['rank']; ?></td></tr>
                    <tr><th><?php echo $ld['ip_address']?></th><td><?php echo $comment['Comment']['ipaddr']; ?></td></tr>
                    <?php if(isset($restore) && sizeof($restore)>0){foreach($restore as $k=>$v){?>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr><th><?php echo $ld['administrator']?></th><td><?php echo $v['Comment']['name']; ?></td></tr>
                        <tr><th><?php echo $ld['resply_time']?></th><td><?php echo $v['Comment']['created']; ?></td></tr>
                        <tr><th><?php echo $ld['reply_content']?></th><td><?php echo $v['Comment']['content']; ?></td></tr>
                        <tr><th><?php echo $ld['ip_address']?></th><td><?php echo $v['Comment']['ipaddr']; ?></td></tr>
                    <?php }}?>
                </table>
            </div>
        </div>
    </div>
    <div id="reply" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['reply_comments']?></h4>
        </div>
        <div id="reply_comments" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <input type="hidden" id="comment_id" name="data[Comment][parent_id]" value="<?php echo $comment['Comment']['id']; ?>">
                <table class="am-table">
                    <tr><th style="padding-top:14px;"><?php echo $ld['user_name']?></th><td><input style="width:200px;" type="text" name="data[Comment][name]" value="<?php echo $admin['name']?>" readonly /></td></tr>
                    <tr><th style="padding-top:14px;">Email</th><td><input style="width:200px;" type="text" name="data[Comment][email]" value="<?php echo $admin['email']?>" readonly /></td></tr>
                    <tr><th><?php echo $ld['reply_content']?></th><td><textarea style="width:200px;" name="data[Comment][content]"></textarea></td></tr>
                    <?php if(isset($restore) && sizeof($restore)>0){?>
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="3"><?php echo $ld['note_message_replied']?></td>
                        </tr>
                    <?php }?>
                    <tr><td></td><td><span style="display:<?php if($comment['Comment']['status']==1){echo 'none';}?>;" id="commentshow"><input type="button" value="<?php echo $ld['allows_visible']?>" onclick="commentverify(1)"/></span><span style="display:<?php if($comment['Comment']['status']==0){echo 'none';}?>;" id="commenthidden"><input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['invisible']?>" onclick="commentverify(0)" /></span> <input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['submit']?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['reset']?>" /></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>

<script type="text/javascript">
    function commentverify(status){
        var comment_id = document.getElementById('comment_id').value;
        var sUrl = "/admin/comments/commentverify/"+comment_id+"/"+status+"/";
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            success: function (result) {
                if(result.flag==1){
                    window.location.reload();
                }
                if(result.flag==2){
                    alert(result.message);
                }
            }
        });
    }
</script>