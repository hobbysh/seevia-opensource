<style>
.am-form-label{font-weight:bold;margin-top:5px;margin-left:15px;}
 </style>
 
<div class="listsearch">
    <?php echo $form->create('OpenKeyword',array('action'=>'/nottosearchkeyword','name'=>"SeearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0">
        
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-lg-2  am-u-md-3 am-form-label"><?php echo $ld['keyword'] ?></label>
            <div class="am-u-sm-7   am-u-lg-8   am-u-md-7 ">
                <input placeholder="<?php echo $ld['keyword'];?>" type="text" name="keywords" id="keywords" value="<?php echo isset($keywords)?$keywords:'';?>" />
            </div>
        </li><!--1--->
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-form-label"><?php echo $ld['reply'].''.$ld['status'] ?></label>
            <div class="am-u-sm-7 am-u-lg-7 am-u-md-7">
                <select name="selectstatus" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?>'}">
                    <option value=""><?php echo $ld['all_data'] ?></option>
                    <option value="0" <?php echo isset($selectstatus)&&$selectstatus=='0'?'selected':''; ?>><?php echo $ld['unreplied'] ?></option>
                    <option value="1" <?php echo isset($selectstatus)&&$selectstatus=='1'?'selected':''; ?>><?php echo $ld['replied'] ?></option>
                </select>
            </div>
        </li><!--\2--->
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3  am-u-lg-3  am-u-md-3 am-form-label"><?php echo $ld['added_time'];?></label>
            <div class="am-u-sm-3  am-u-lg-3 am-u-md-3 " style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date" value="<?php echo isset($start_date)?$start_date:'';?>" />
            </div>
            <em class=" am-u-lg-1  am-u-sm-1 am-u-md-1 am-text-center " style="padding: 0.35em 0px;">-</em>
            <div class="am-u-lg-3 am-u-sm-3    am-u-md-3 am-u-end" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date" value="<?php echo isset($end_date)?$end_date:'';?>" />
            </div>
        </li>
        		<!--3--->
         <li style="margin:0 0 10px 0">
        	<label class="am-u-sm-3 am-u-lg-2 am-u-md-3 am-form-label"> </label>
            <div class="am-u-sm-7 am-u-lg-8 am-u-md-7">
        	 <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search'];?>"/>
        	</div>
        	</li>
    </ul>
    <?php echo $form->end()?>
</div>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <?php echo $form->create('OpenKeyword',array('action'=>'/removesearchkeywordAll/','name'=>'KeywordForm','type'=>'get',"onsubmit"=>"return false;"));?>
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th class="am-hide-sm-only"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['open_model'];?></b></label></th>
           
            <th><?php echo $ld['user_name'];?></th>
            <th><?php echo $ld['keyword'];?></th>
            <th><?php echo $ld['reply'].' '.$ld['status']; ?></th>
            <th class="am-hide-sm-only"><?php echo $ld['added_time']; ?></th>
            <th style="width:150px;"><?php echo $ld['operate']; ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($open_keyword_list)&&sizeof($open_keyword_list)>0){ foreach($open_keyword_list as $k=>$v){ ?>
            <tr>
                <td class="am-hide-sm-only"><label style="margin:0 0 0 0;" class="am-checkbox am-defau"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['OpenKeywordError']['id']?>" /><?php echo $v['OpenKeywordError']['open_type_id']; ?></label></td>
               
                <td><?php echo urldecode($v['OpenUser']['nickname']);?></td>
                <td><div class="ellipsis"><?php echo $v['OpenKeywordError']['keyword']; ?></div></td>
                <td ><?php echo $v['OpenKeywordError']['status']=='1'?$ld['replied']:$ld['unreplied']; ?></td>
                <td class="am-hide-sm-only"><?php echo $v['OpenKeywordError']['created']; ?></td>
                <td style="min-width:150px;"><?php echo $html->link($ld['quick_reply'],"javascript:void(0);",array("class"=>"am-btn am-btn-default am-btn-xs am-radius","data-am-modal"=>"{target: '#quick_reply', closeViaDimmer: 0, width: 430, height: 330}",'onclick'=>"showquickreply(".$v['OpenKeywordError']['open_user_id'].",".$v['OpenKeywordError']['id'].")")); ?></a>&nbsp;&nbsp;<?php if($svshow->operator_privilege("delete_open_keywords_error")){ ?><a href="javascript:;" class="am-btn am-btn-default am-text-danger am-btn-xs am-radius" onclick='removeKeyword(<?php echo $v['OpenKeywordError']['id'];?>)'><?php echo $ld['delete'];?></a><?php } ?></td>
            </tr>
        <?php }}else{ ?>
            <tr>
                <td   colspan="7" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php if(isset($open_keyword_list)&&sizeof($open_keyword_list)>0){?>
    <div id="btnouterlist" class="btnouterlist">
        <?php if($svshow->operator_privilege("delete_open_keywords_error")){?>
            <div class="am-u-lg-6 am-u-md-12 am-u-sm-12" >
                <label style="margin-right:5px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/>
                    <span><?php echo $ld['select_all']?></span>
                </label>
                <input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" onclick="removeAll()" value="<?php echo $ld['batch_delete']?>" />
            </div>
        <?php }?>
        <div class="am-u-lg-6 am-u-md-12 am-u-sm-12"><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
    </div>
    <?php }?>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" name="quick_reply" id="quick_reply">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"><?php echo $ld['quick_reply'];?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div id="quick_reply_div">

        </div>
    </div>
</div>
<style type="text/css">
    .ellipsis {
        overflow: hidden;
        text-overflow: ellipsis;
        text-transform: capitalize;
        white-space: nowrap;
        width:auto;
    }
</style>
<script type="text/javascript">
    function showquickreply(open_user_id,keyword_error_id){
        $.ajax({ url: "/admin/open_users/quickreply/"+open_user_id,
            type:"POST",
            data:{keyword_error_id:keyword_error_id},
            dataType:"html",
            success: function(data){
                $("#quick_reply_div").html(data);
            }
        });
    }

    function removeKeyword(id){
        if(confirm("<?php echo $ld['confirm_delete'] ?>")){
            var func="open_keywords/removesearchkeyword";
            var sUrl = admin_webroot+func;//访问的URL地址
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'json',
                data: {id:id},
                success: function (result) {
                    if(result.flag == 1){
                        alert(j_deleted_success);
                        window.location.reload();
                    }
                    if(result.flag == 2){
                        alert(result.message);
                    }
                }
            });
        }
    }

    function removeAll(){
        var ck=document.getElementsByName('checkboxes[]');
        var j=0;
        for(var i=0;i<=parseInt(ck.length)-1;i++)
        {
            if(ck[i].checked)
            {
                j++;
            }
        }
        if(j>=1){
            if(confirm("<?php echo $ld['confirm_delete'] ?>"))
            {
                document.KeywordForm.action=admin_webroot+"open_keywords/removesearchkeywordAll/";
                document.KeywordForm.onsubmit= "";
                document.KeywordForm.submit();
            }
        }
    }
</script>