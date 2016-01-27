<style>
    .ellipsis{text-transform:capitalize;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:120px;}
     .am-form-label{font-weight:bold; left:16px;}
</style>
<div class="listsearch">
    <?php echo $form->create('ScoreForm',array('action'=>'/','name'=>'ScoreForm','type'=>'get','class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-2 am-u-md-2 am-u-lg-2 am-form-label"><?php echo $ld['name'];?></label>
            <div class="am-u-sm-7 am-u-md-7 am-u-lg-7">
                <input type="text" class="name" name="score_name" value="<?php echo @$score_name; ?>" />
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-2 am-u-md-2 am-u-lg-2 am-form-label"><?php echo $ld['type']?></label>
            <div class="am-u-sm-7 am-u-md-7 am-u-lg-7" stryle="float:left;">
                <select name="score_type" data-am-selected="{noSelectedText:''}">
                    <option value=""><?php echo $ld['please_select']?></option>
                    <?php if(isset($score_type_list)&&sizeof($score_type_list)>0){foreach($score_type_list as $k=>$v){ ?>
                        <option value="<?php echo $k; ?>" <?php if(@$score_type==$k){echo "selected";}?> ><?php echo $v; ?></option>
                    <?php }} ?>
                </select>
            </div>
             <div class="am-u-sm-2 am-u-md-2 am-u-lg-2 am-show-lg-only">
        	<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>" />
        	</div>		
        </li>
        <li style="margin:0 0 10px 0">
           <label class="am-u-sm-2 am-u-md-2 am-u-lg-2 am-form-label"> </label>
        	 <div class="am-u-sm-2 am-u-md-2 am-u-lg-2 am-hide-lg-only">
        	<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>" />
        	</div>
        </li>
    </ul>
    <?php echo $form->end();?>
</div>
<p class="am-btn-group-xs  am-text-right">
    <?php  
     if($svshow->operator_privilege("scores_add")){?>
      <!-- echo $html->link($ld['add'],"/scores/0",array("class"=>"am-btn am-btn-warning am-radius am-btn-sm "));-->
        <a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/scores/0'); ?>">
				<span class="am-icon-plus">&nbsp;</span><?php echo $ld['add'] ?>
			</a>
 <?php   }?>
 <?php 
    if($svshow->operator_privilege("scores_log_view")){
        echo $html->link($ld['score_log'],"/scores/scorelog",array("class"=>"am-btn am-radius am-btn-sm am-btn-default"));
    }
  
    ?>  
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th><label style="margin:0 0 0 0;" class="am-checkbox am-success  "><span class=" am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['name']?></b></label></th>
        
            <th><?php echo $ld['type']?></th>
            <th class="ellipsis  am-hide-sm-down" ><?php echo $ld['option_list']?></th>
            <th><?php echo $ld['status']?></th>
            <th><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($score_list)&&sizeof($score_list)>0){foreach($score_list as $k=>$v){ ?>
            <tr>
                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class=" am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Score']['id']?>" /></span><?php echo $v['ScoreI18n']['name'] ?></td>
               
                <td ><?php echo isset($score_type_list[$v['Score']['type']])?$score_type_list[$v['Score']['type']]:$v['Score']['type']; ?></td>
                <td><?php echo $v['ScoreI18n']['value']; ?></td>
                <td  ><?php
                    if($svshow->operator_privilege("scores_edit")){
                        if($v['Score']['status'] == 1){
                            echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "scores/toggle_on_status", '.$v["Score"]["id"].')'));
                        }else{
                            echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "scores/toggle_on_status", '.$v["Score"]["id"].')'));
                        }
                    }else{
                        if($v['Score']['status'] == 1){
                            echo $html->image('yes.gif',array('style'=>'cursor:pointer;'));
                        }else{
                            echo $html->image('no.gif',array('style'=>'cursor:pointer;'));
                        }
                    }
                    ?>
                </td>
                <td class="am-action"><?php if($svshow->operator_privilege("scores_edit")){?>
                                 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/scores/'.$v['Score']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                  <?php   }
                    if($svshow->operator_privilege("scores_delete")){?>
                  
                        		<a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:void(0);" onclick="if(j_confirm_delete){list_delete_submit(admin_webroot+'scores/remove/<?php echo $v['Score']['id'] ?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?>
                      </a>
                    <?php }
                    ?></td>
            </tr>
        <?php }}else{ ?>
            <tr>
                <td colspan="6"class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php if(isset($score_list)&&sizeof($score_list)>0){ ?>
    <div id="btnouterlist" class="btnouterlist">
        <div class="am-u-lg-3 am-u-md-4 am-u-sm-12   am-hide-sm-down ">
            <label style="margin-right:5px;float:left; margin-top:5px;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
            <?php if($svshow->operator_privilege("scores_delete")){ ?>
                <input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" value="<?php echo $ld['batch_delete']?>" onclick="batch_delete()" />
            <?php } ?>
        </div>
        <div class="am-u-lg-8 am-u-md-7 am-u-sm-12"><?php  echo $this->element('pagers');?></div>
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
            var sUrl = admin_webroot+"scores/removeall/";//访问的URL地址
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