<div class="listsearch">
    <form class="am-form am-form-inline am-form-horizontal">
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3">
        <li style="margin-bottom:10px;">
            <label class="am-u-sm-4 am-form-label"><?php echo $ld['keyword'];?>:</label>
            <div class="am-u-sm-6">
                <input type="text" id="open_user_keywords" value="<?php echo @$open_user_keywords; ?>" />
            </div>
        </li>
        <li style="margin-bottom:10px;">
            <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="open_user_search()" value="<?php echo $ld['search']; ?>">
        </li>
    </ul>
    </form>
</div>
<table id="t1" class="am-table">
    <thead>
    <tr>
        <th><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" /><b><?php echo $ld['number']?></b></label></th>
        <th><?php echo $ld['avatar'];?></th>
        <th><?php echo $ld['open_model'];?></th>
        <th><?php echo 'OpenId';?></th>
        <th><?php echo $ld['nickname'];?></th>
        <th><?php echo $ld['gender'];?></th>
        <th><?php echo $ld['status'];?></th>
    </tr>
    </thead>
    <tbody>
    <?php if(isset($user_list) && sizeof($user_list)>0){foreach($user_list as $k=>$v){?>
        <tr>
            <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['OpenUser']['openid']?>" /><?php echo $v['OpenUser']['id']?></label></td>
            <td><img style="width:60px;height:60px;" src="<?php echo $v['OpenUser']['headimgurl']?>"></td>
            <td><?php echo ($v['OpenUser']['open_type'] == 'wechat')?$ld['wechat']: $v['OpenModel']['open_type'];?></td>
            <td><?php echo $v['OpenUser']['openid']?></td>
            <td><?php echo urldecode($v['OpenUser']['nickname']);?></td>
            <td><?php echo ($v['OpenUser']['sex']==1)?$ld['male']:$ld['female'] ?></td>
            <td><?php echo ($v['OpenUser']['subscribe']==1)?$ld['attention']:$ld['cancel_attention']?></td>
        </tr>
    <?php }}else{?>
        <tr>
            <td colspan="7" class="no_data_found"><?php echo $ld['no_data_found']?></td>
        </tr>
    <?php }?>
    </tbody>
</table>
<div id="btnouterlist" class="btnouterlist">
    <div>
        <label><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)" type="checkbox"><span><?php echo $ld['select_all']?></span></label>
        <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['send']?>" onclick="batch_send()" />
        <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['preview']?>" onclick="batch_preview()" />
    </div>
    <?php echo $this->element('pagers')?>
</div>
<script type="text/javascript">
    $("#open_user_list .pages a").click(function(){
        var href_url=$(this).attr("href");
        $(this).attr("href","javascript:void(0);");
        $.ajax({ url: encodeURI(href_url),
            cache: false,
            success: function(data){
                $("#open_user_list").html(data);
            }
        });
        return false;
    });

    function open_user_search(){
        var open_type_id=$("#open_type_id").val();
        $.ajax({ url: "/admin/open_elements/open_user_list/"+encodeURI(open_type_id),
            data:{'open_user_keywords':$('#open_user_keywords').val()},
            dataType:"html",
            cache: false,
            success: function(data){
                $("#open_user_list").html(data);
            }
        });
    }
</script>