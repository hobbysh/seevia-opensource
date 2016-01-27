<style type="text/css">
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
</style>
<div id="tablelist" class="tablelist tablebang">
<?php	$arr = array(
		't' => 'top_navigation',
		'm' => 'middle_navigation',
		'b' => 'bottom_navigation',
		'h' => 'help_navigation',
		'pb'=>'mobile_bottom_navigation',
		'pm'=>'mobile_middle_navigation'
		);
		foreach ($arr as $ark =>$arv ) {?>
	<div id='navigations_data_<?php echo $ark; ?>'>	
		<div>
		
		       <div class="am-text-right am-btn-group-xs">
			<a class="am-btn am-btn-warning am-btn-sm am-radius " href="<?php echo $html->url('/navigations/view/0/'.(strtoupper($ark))); ?>"  style="margin-bottom:10px;">
				<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
			</a>
		      </div>
			<div style="clear:both;"></div>
		</div>	<label><?php echo $ld[$arv]?></label>
		<div class="am-panel-group am-panel-tree" id="accordion">
		<!--标题栏-->
			<div class="am-panel am-panel-default am-panel-header">
			    <div class="am-panel-hd">
			      	<div class="am-panel-title">
						<div class="am-u-lg-2 am--u-md-2 am-u-sm-2"><?php echo $ld['navigation_name']?></div>
						<div class="am-u-lg-3 am--u-md-3 am-u-sm-3"><?php echo $ld['url']?></div>
						<div class="am-u-lg-1 am--u-md-1 am-u-sm-1"><?php echo $ld['show']?></div>
						<div class="am-u-lg-1 am--u-md-1 am-u-sm-1"><?php echo $ld['new_window']?></div>
						<div class="am-u-lg-2 am--u-md-2 am-u-sm-2"><?php echo $ld['sort']?></div>
						<div class="am-u-lg-3 am--u-md-3 am-u-sm-3"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset(${"navigations_data_".$ark}) && sizeof(${"navigations_data_".$ark})>0){foreach(${"navigations_data_".$ark} as $k=>$v){ ?>
			<div>			
				<div class="am-panel am-panel-default am-panel-body">
					<div class="am-panel-bd am-g">
						<div class="am-u-lg-2 am--u-md-2 am-u-sm-2">
							<span data-am-collapse="{parent: '#accordion', target:'#navigation_<?php echo $v['Navigation']['id']?>'}" class="<?php echo (isset($v['SubNavigation'])&&!empty($v['SubNavigation']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;<?php echo $v['NavigationI18n']['name'];?>&nbsp;
						</div>
						<div class="am-u-lg-3 am--u-md-3 am-u-sm-3">
							<a href="<?php echo $v['NavigationI18n']['url'] ?>" target="_blank"><?php echo $v['NavigationI18n']['url'] ?></a>&nbsp;
						</div>
						<div class="am-u-lg-1 am--u-md-1 am-u-sm-1">
							<?php if ($v['Navigation']['status'] == 1){?>
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_status',<?php echo $v['Navigation']['id'];?>)">&nbsp;</span>
							<?php }elseif($v['Navigation']['status'] == 0){?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_status',<?php echo $v['Navigation']['id'];?>)">&nbsp;</span>	
							<?php }?>
						</div>
						<div class="am-u-lg-1 am--u-md-1 am-u-sm-1">
							<?php if ($v['Navigation']['target'] == '_blank'){?>
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_target',<?php echo $v['Navigation']['id'];?>)">&nbsp;</span>								
							<?php }elseif($v['Navigation']['target'] == '_self'){?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_target',<?php echo $v['Navigation']['id'];?>)">&nbsp;</span>
							<?php }?>
						</div>
						<div class="am-u-lg-2 am--u-md-2 am-u-sm-2">
							<?php if(count(${"navigations_data_".$ark})==1){echo "-";}elseif($k==0){?>
								<a onclick="changeOrder('down','<?php echo $v['Navigation']['id'];?>','0',this,'<?php echo $ark ?>')">&#9660;</a>&nbsp;
							<?php }elseif($k==(count(${"navigations_data_".$ark})-1)){?>
								<a onclick="changeOrder('up','<?php echo $v['Navigation']['id'];?>','0',this,'<?php echo $ark ?>')" style="color:#cc0000;">&#9650;</a>&nbsp;
							<?php }else{?>
								<a onclick="changeOrder('up','<?php echo $v['Navigation']['id'];?>','0',this,'<?php echo $ark ?>')" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['Navigation']['id'];?>','0',this,'<?php echo $ark ?>') ">&#9660;</a>&nbsp;
							<?php }?>
						</div>
						<div class="am-u-lg-3 am--u-md-3 am-u-sm-3 am-btn-group-xs am-action">
							<?php if($svshow->operator_privilege('navigations_edit')){  ?>
								<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/navigations/view/'.$v['Navigation']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
								
							<?php 	}?>
								
						<?php 	if($svshow->operator_privilege('navigations_remove')){?>
							
							  	<a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'/navigations/remove/<?php echo$v['Navigation']['id'] ?>');">
                        					<span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      					</a>
									
							<?php }?>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(!empty($v['SubNavigation']) && sizeof($v['SubNavigation'])>0){ ?>
				<div class="am-panel-collapse am-collapse am-panel-child" id="navigation_<?php echo $v['Navigation']['id']?>">
					<div class="am-panel am-panel-default am-panel-body">
                <?php foreach($v['SubNavigation'] as $kk=>$vv){ ?>
						<div class="am-panel-bd am-g">
							<div class="am-u-lg-2 am--u-md-2 am-u-sm-2">
								<span class="am-icon-minus" id="<?php echo $vv['Navigation']['id']?>"></span>&nbsp;<?php echo $html->link($vv['NavigationI18n']['name'],$server_host.$vv['NavigationI18n']['url'],array("target"=>"_blank"),false,false);?>
							</div>
							<div class="am-u-lg-3 am--u-md-3 am-u-sm-3">
								<?php echo $html->link($vv['NavigationI18n']['url'],$server_host.$vv['NavigationI18n']['url'],array("target"=>"_blank"),false,false);?>
							</div>
							<div class="am-u-lg-1 am--u-md-1 am-u-sm-1">
								<?php if ($vv['Navigation']['status'] == 1){?>
    								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_status',<?php echo $vv['Navigation']['id'];?>)">&nbsp;</span>
    							<?php }elseif($vv['Navigation']['status'] == 0){?>
    								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_status',<?php echo $vv['Navigation']['id'];?>)">&nbsp;</span>	
    							<?php }?>
							</div>
							<div class="am-u-lg-1 am--u-md-1 am-u-sm-1">
								<?php if ($v['Navigation']['target'] == '_blank'){?>
    								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_target',<?php echo $vv['Navigation']['id'];?>)">&nbsp;</span>								
    							<?php }elseif($v['Navigation']['target'] == '_self'){?>
    								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_target',<?php echo $vv['Navigation']['id'];?>)">&nbsp;</span>
    							<?php }?>
							</div>
							<div class="am-u-lg-2 am--u-md-2 am-u-sm-2">
								<?php if(count($v['SubNavigation'])==1){echo "-";}elseif($kk==0){?>
									<a onclick="changeOrder('down','<?php echo $vv['Navigation']['id'];?>','next',this,'<?php echo $ark ?>')">&#9660;</a>
								<?php }elseif($kk==(count($v['SubNavigation'])-1)){?>
									<a onclick="changeOrder('up','<?php echo $vv['Navigation']['id'];?>','next',this,'<?php echo $ark ?>')" style="color:#cc0000;">&#9650;</a>
								<?php }else{?>
									<a onclick="changeOrder('up','<?php echo $vv['Navigation']['id'];?>','next',this,'<?php echo $ark ?>')" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vv['Navigation']['id'];?>','next',this,'<?php echo $ark ?>')">&#9660;</a>
								<?php }?>
							</div>
							<div class="am-u-lg-3 am--u-md-3 am-u-sm-3">
								<?php if($svshow->operator_privilege('navigations_edit')){
							echo $html->link(' '.$ld['edit'],"/navigations/view/{$vv['Navigation']['id']}",array('class'=>'am-icon-pencil-square-o  am-seevia-btn-edit am-btn am-btn am-btn-default am-btn-xs  ')).'&nbsp;';}
							if($svshow->operator_privilege('navigations_remove')){
							echo $html->link(' '.$ld['delete'],"javascript:;",array("class"=>"am-icon-trash-o am-seevia-btn-delete am-btn am-text-danger am-btn-xs am-btn-default","onclick"=>"list_delete_submit('{$admin_webroot}navigations/remove/{$vv['Navigation']['id']}')"));}?>
							</div>
						</div>
                <?php } ?>
					</div>
				</div>
			<?php }?>
			<?php }}?>	
		</div>
	</div>	
<?php }?>
</div>
<script type="text/javascript">
function changeOrder(updown,id,next,thisbtn,position){
	$.ajax({
		url:"/admin/navigations/changeorder/"+updown+"/"+id+"/"+next+"/"+position,
		type:"POST",
		data:{ },
		dataType:"html",
		success:function(data){
			var p;
			if(position=='t') {	var node = $('#navigations_data_t');  p='foldtablelist_t';}
			if(position=='b') { var node = $('#navigations_data_b');  p='foldtablelist_b';}
			if(position=='m') { var node = $('#navigations_data_m');  p='foldtablelist_m';}
			if(position=='h') {	var node = $('#navigations_data_h');  p='foldtablelist_h';}
			if(position=='pb'){	var node = $('#navigations_data_pb'); p='foldtablelist_pb';}
			if(position=='pm'){	var node = $('#navigations_data_pm'); p='foldtablelist_pm';}
			node.html(data);
		}
	});
}
function change_state(obj,func,id){
	var ClassName=$(obj).attr('class');
	var val = (ClassName.match(/yes/i)) ? 0 : 1;
	var postData = "val="+val+"&id="+id;
	$.ajax({
		url:admin_webroot+func,
		Type:"POST",
		data: postData,
		dataType:"json",
		success:function(data){
			if(data.flag == 1){
				if(val==0){
					$(obj).removeClass("am-icon-check am-yes");
					$(obj).addClass("am-icon-close am-no");
				}
				if(val==1){
					$(obj).removeClass("am-icon-close am-no");
					$(obj).addClass("am-icon-check am-yes");
				}
			}
		}	
	});
}

</script>
