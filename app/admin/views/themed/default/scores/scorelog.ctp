<style>
    .ellipsis{text-transform:capitalize;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:200px;}
    .am-form-label{font-weight:bold; left:16px;}
</style>
<div class="listsearch">
    <?php echo $form->create('score',array('action'=>'/scorelog','name'=>'ScoreLogForm','type'=>'get','class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-lg-2 am-form-label"><?php echo $ld['user_name'];?></label>
            <div class="am-u-sm-7 am-u-lg-7 ">
                <input type="text" class="name" name="score_keyword" value="<?php echo @$score_keyword; ?>" />
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3  am-u-lg-2 am-form-label"><?php echo $ld['type']?></label>
            <div class="am-u-sm-7  am-u-lg-7 ">
                <select name="score_type" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']?>'}">
                    <option value="" selected><?php echo $ld['all_data']?></option>
                    <?php if(isset($score_type_list)&&sizeof($score_type_list)>0){foreach($score_type_list as $k=>$v){ ?>
                        <option value="<?php echo $k; ?>" <?php if(@$score_type==$k){echo "selected";}?> ><?php echo $v; ?></option>
                    <?php }} ?>
                </select>
            </div>
            	 
        </li>
        <li style="margin:0 0 10px 0"  >
        			<label class="am-u-sm-3  am-u-lg-3 am-form-label "> </label>
        			<div class="am-u-sm-3  am-u-lg-7 ">
        			<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>" />
        			</div>
       </li>
    </ul>
    <?php echo $form->end();?>
</div>
<p class="am-u-md-12 am-btn-group-xs">
    <?php if($svshow->operator_privilege("scores_view")){ echo $html->link($ld['score_management'],"/scores/",array("class"=>"am-btn am-btn-warning am-radius am-btn-sm am-fr"));}?>
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th class="thwrap am-hide-md-down"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['user_name']?></b></label></th>
            <!--th class="ellipsis" style="width:100px;"></th-->
            <th class="thwrap am-hide-md-down"><?php echo $ld['type']?></th>
            <th class="ellipsis"><?php echo $ld['score_object'] ?></th>
            <th class="thwrap am-hide-md-down" style="width:150px;"><?php echo $ld['score_options'] ?></th>
            <th><?php echo $ld['score_value'] ?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['score_time'] ?></th>
            <?php if($svshow->operator_privilege("scores_log_delete")){ ?>
                <th><?php echo $ld['operate']?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($loglist)&&sizeof($loglist)>0){foreach($loglist as $k=>$v){ ?>
        <tr>
            <td class="thwrap am-hide-md-down"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['id']; ?>" /></td>
            <!--td><?php echo isset($userNamelist[$v['user_id']])?$userNamelist[$v['user_id']]:$v['user_id']; ?></td-->
            <td class="thwrap am-hide-md-down"><?php echo isset($score_type_list[$v['type']])?$score_type_list[$v['type']]:$v['type']; ?></td>
            <td><?php echo $v['type']=='P'&&isset($product_list[$v['type_id']])?$product_list[$v['type_id']]:$v['type_id']; ?></td>
            <?php if(isset($v['score'])&&sizeof($v['score'])>0){ ?>
            <td class="thwrap am-hide-md-down">
                <?php foreach($v['score'] as $kk=>$vv){ ?>
                    <div><?php echo isset($scorelist[$vv['score_id']])?$scorelist[$vv['score_id']]:$vv['score_id']; ?></div>
                <?php } ?>
            </td>
            <td>
                <?php foreach($v['score'] as $kk=>$vv){ ?>
                    <div><?php echo $vv['value']; ?></div>
                <?php } ?>
            </td>
            <td class="thwrap am-hide-md-down"><?php echo $v['time']; ?></td>
            <td><?php if($svshow->operator_privilege("scores_log_delete")){
        echo $html->link(' '.$ld['remove'],"javascript:void(0);",array("class"=>"am-btn am-icon-trash-o am-btn-default am-text-danger am-btn-xs am-radius","onclick"=>"if(confirm('{$ld['confirm_delete']}')){list_delete_submit('{$admin_webroot}scores/removelog/{$v['id']}');}"));} ?></div>
    </td>
<?php } ?>
    </tr>
<?php }}else{ ?>
    <tr>
        <td colspan="8" class="no_data_found"><?php echo $ld['no_data_found']?></td>
    </tr>
<?php } ?>
</tbody>
</table>
<?php if(isset($loglist)&&sizeof($loglist)>0){ ?>
    <div id="btnouterlist" class="btnouterlist">
        <div class="am-u-lg-3 am-u-md-12 am-u-sm-12">
            <label style="margin-right:5px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
            <input class="am-btn am-btn-danger am-radius am-btn-sm" type="button" value="<?php echo $ld['batch_delete']?>" onclick="batch_delete()" />
        </div>
        <div class="am-u-lg-9 am-u-md-12 am-u-sm-12"><?php  echo $this->element('pagers');?></div>
        <div class="am-cf"></div>
    </div>
<?php } ?>
</div>
<script type="text/javascript">
    function batch_delete(){
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                checkboxes.push(bratch_operat_check[i].value);
            }
        }
        if(checkboxes==""){
            alert("<?php echo $ld['please_select'].' '.$ld['score_options'] ?>");
            return;
        }
        if(confirm("<?php echo $ld['confirm_delete'] ?>")){
            var sUrl = admin_webroot+"scores/removelogall/";//访问的URL地址
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'json',
                data: {checkboxes:checkboxes},
                success: function (json) {
                    if(result.flag==1){
                        window.location.href = window.location.href;
                    }
                }
            });
        }
    }
</script>