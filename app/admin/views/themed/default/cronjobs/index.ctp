<style>
	.am-panel-title{font-weight:bold;}
	.am-form-label{font-weight:bold;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
</style>
<div class="am-g" style="margin-top:10px;">
	<div class="listsearch">
		<?php echo $form->create('Cronjob',array('action'=>'/','name'=>"SearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();',"class"=>"am-form am-form-horizontal"));?>
		<input type="hidden" name="export_act_flag" id="export_act_flag" value=""/>
			<ul class="am-avg-lg-4 am-avg-md-3 am-avg-sm-1">
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-3 am-form-label"> </label>
					<div class="am-u-lg-8 am-u-md-7 am-u-sm-7">
						<select class="all" name="cronjob_app" id="cronjob_app" data-am-selected="{noSelectedText:''}">
							<option value=""><?php echo $ld['select_app_code']?></option>
							<?php if(isset($appcode_tree) && sizeof($appcode_tree)>0){?><?php foreach($appcode_tree as $k=>$v){?>
					  		<option value="<?php echo $v['Application']['code']?>" <?php if($cronjob_app == $v['Application']['code'] && $cronjob_app!=""){?>selected<?php }?>><?php echo $v['Application']['code']?></option>
							<?php }}?>
						</select>
					</div>
				</li>
						
				<li>
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-3 am-form-label"><?php echo $ld['keyword'];?></label>
					<div class="am-u-lg-8 am-u-md-7 am-u-sm-7">
						<input type="text" class="am-form-field am-radius" name="cronjob_keywords" id="cronjob_keywords" value="<?php echo $cronjob_keywords?>"  placeholder="<?php echo $ld['task_name']?>"/>
					</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
					<button type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"  onclick="formsubmit()">
						<?php echo $ld['search'];?>
					</button>
					</div>
				</li>
			</ul>
			<div class="action-span add am-text-right" style="margin:0px 15px 10px 0px;">
				<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('view'); ?>">
					<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
				</a>
			</div>
		<?php echo $form->end();?>
	</div>	
	<div class="am-panel-group am-panel-tree">
		<div class="am-panel am-panel-default am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['app_code']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['task_name']?></div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['task_code']?></div>
					<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><?php echo $ld['last_time']?></div>
					<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><?php echo $ld['next_time']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['interval_time']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status']?></div>
					<div class="am-u-lg-3 am-u-md-2 am-u-sm-3"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($cronjobs) && sizeof($cronjobs)>0){foreach($cronjobs as $k=>$v){;?>	
		<div>		
			<div class="am-panel am-panel-default am-panel-body">
				<div class="am-panel-bd">
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['Cronjob']['app_code'] ?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['Cronjob']['task_name'] ?>&nbsp;</div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $v['Cronjob']['task_code'] ?>&nbsp;</div>
					<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><?php echo $v['Cronjob']['last_time'] ?>&nbsp;</div>
					<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><?php echo $v['Cronjob']['next_time'] ?>&nbsp;</div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['Cronjob']['interval_time'] ?>&nbsp;</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
						<?php if( $v['Cronjob']['status']==1 ){?>
							<?php if($svshow->operator_privilege('cronjob_edit')){?>
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'cronjobs/toggle_on_status',<?php echo $v['Cronjob']['id'];?>)"></span>
							<?php }elseif($opertor_type=="D"){?>
								<span class="am-icon-check am-yes" ></span>
							<?php }else{?>
								<span class="am-icon-check am-yes" ></span>
							<?php }?>
						<?php }elseif($v['Cronjob']['status'] == 0){?>
							<?php if($svshow->operator_privilege('cronjob_edit')){?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'cronjobs/toggle_on_status',<?php echo $v['Cronjob']['id'];?>)"></span>
									
							<?php }elseif($opertor_type=="D"){?>
								<span class="am-icon-close am-no"></span>
							<?php }else{?>
								<span class="am-icon-close am-no"></span>
							<?php }?>
						<?php }?>
					</div>
					<div class="am-u-lg-3 am-u-md-2 am-u-sm-3">
						<?php echo 	$html->link($ld['run'],"javascript:;",array("onclick"=>"addexec('{$v['Cronjob']['task_name']}','{$shop_name}')","class"=>"am-btn am-radius am-btn-success am-btn-sm ")).'&nbsp'; ?>
						<?php
							if($svshow->operator_privilege("cronjob_edit")){echo $html->link($ld['edit'],"/cronjobs/view/{$v['Cronjob']['id']}",array("class"=>"am-btn am-radius am-btn-success am-btn-sm ")).'&nbsp;';}
							if($svshow->operator_privilege("cronjob_remove")){echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-radius am-btn-danger am-btn-sm ","onclick"=>"list_delete_submit('{$admin_webroot}cronjobs/remove/{$v['Cronjob']['id']}');"));}?> 
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php }}else{?>
			<div style="margin:50px;">
				<div style="text-align:center;"><label><?php echo $ld['no_Cronjob']?></label></div>
			</div>
		<?php }?>			
	</div>
	<?php if(isset($cronjobs) && sizeof($cronjobs)>0){ ?>
		<div id="btnouterlist" class="btnouterlist">
			<div class="am-u-lg-4 am-u-md-3 am-hide-sm-only">&nbsp;</div>
			<div class="am-u-lg-8 am-u-md-9 am-u-sm-12"> 
				<?php echo $this->element('pagers')?>
			</div>
		</div>
	<?php }?>
</div>	
<script>
	function formsubmit(){
		var cronjob_keywords=document.getElementById('cronjob_keywords').value;
		var cronjob_app=document.getElementById('cronjob_app').value;
		var url = "cronjob_keywords="+cronjob_keywords+"&cronjob_app="+cronjob_app;
		window.location.href = encodeURI(admin_webroot+"cronjobs?"+url);
	}
	function addexec(taskname,shopname){
		window.location.href = encodeURI(admin_webroot+"cronjobs/execute?taskname="+taskname+"&shopname="+shopname);
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
			