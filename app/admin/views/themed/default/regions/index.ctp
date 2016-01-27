<style>
 .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
 .am-panel-title div{font-weight:bold;}
</style>
<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
    <?php if($svshow->operator_privilege('region_add')){?>
	<a class="am-btn am-btn-default"href="<?php echo $html->url('/regions/doload_csv_example'); ?>"><?php echo $ld['bulk_upload']; ?></a>
	<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/regions/view/0'); ?>">
		<span class="am-icon-plus"></span> <?php echo $ld['add']; ?>
	</a>
	<?php } ?>
</div>
<div class="am-panel-group am-panel-tree">
	<div class="am-panel am-panel-default am-panel-header">
		<div class="am-panel-hd">
			<div class="am-panel-title">	
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $ld['name'];?></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['abbreviated'] ?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-1"><?php echo $ld['orderby'] ?></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-4"><?php echo $ld['operate'] ?></div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>
	<?php if(isset($region_list) && sizeof($region_list)>0){foreach($region_list as $k=>$v){?>						
		<div>					
			<div class="am-panel am-panel-default am-panel-body">
				<div class="am-panel-bd">					
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="margin-top:7px;">
						<?php echo $v['RegionI18n']['name'] ?>&nbsp;
						<?php if(isset($region_child_list[$v['Region']['id']])&&sizeof($region_child_list[$v['Region']['id']])>0){ ?>
            			<a onclick="region('<?php echo $v['Region']['id']; ?>',this)"  style="cursor:pointer;"><?php echo $ld['view_sub_region']?></a>
            			<?php }?>
					</div>	
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" ><?php echo $v['Region']['abbreviated'] ?>&nbsp;</div>	
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-1" style="margin-top:7px;"><?php echo $v['Region']['orderby'] ?>&nbsp;</div>	
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-btn-group-xs am-action">
						<?php 
							if($svshow->operator_privilege('region_view')){?>
							<!--echo $html->link($ld['edit'],"/regions/{$v['Region']['id']}",array("class"=>"am-btn am-btn-success am-btn-sm am-radius"))--><a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/regions/'.$v['Region']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
							<?php }
							if($svshow->operator_privilege('region_edit')){?>
							<!--echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-danger am-btn-sm am-radius","onclick"=>"if(confirm(j_confirm_delete)){list_delete_submit('{$admin_webroot}regions/remove/{$v['Region']['id']}');}"));-->
							
							   <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript: ;" onclick=" list_delete_submit(admin_webroot+'regions/remove/<?php echo $v['Region']['id'] ?>' );">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
						<?php 	}?>&nbsp;
					</div>	
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
	<?php }}?>
</div>
<?php //if($svshow->operator_privilege('region_remove')){?>
	<?php if(isset($region_list) && sizeof($region_list)){?>
	<div id="btnouterlist" class="btnouterlist">
		<div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
			<?php echo $this->element('pagers');?>
		</div>
        <div class="am-cf"></div>
	</div>
	<?php }?>
	<?php //}?>	
<script type="text/javascript">

function region(id,obj){
    var div=$(obj).parent().parent();
    if($(".sub_region_"+id).length==0){
    	$.ajax({
    		url:admin_webroot+"regions/regions_list/"+id,
    		type:"POST",
    		data:{},
    		dataType:"html",
    		success:function(data){
                $(div).after(data);
                $(".sub_region_"+id+" input[type='checkbox']").uCheck();
    		}
    	});
    }else{
        if($(".sub_region_"+id).css("display")=='none'){
            $(".sub_region_"+id).css("display",'block');
        }else{
            $(".sub_region_"+id).css("display",'none');
        }
    }
}
	
function submit_operations(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var opration_select_type = document.getElementById("select_type").value;
	if(opration_select_type=='-1'){
		alert(j_select_operation_type+"!");
		return;
	}
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if(opration_select_type=='operation_delete'&&postData!="" ){
		if(confirm(j_confirm_delete)){
			
		}
	}
}
</script>