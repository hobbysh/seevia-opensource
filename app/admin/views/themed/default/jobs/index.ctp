<style>
 .am-checkbox input[type="checkbox"]{margin-left:0;}
 .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-bottom;}
 .look{text-decoration:underline;color:green;}
 .am-panel-title{font-weight:bold;}
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
 
</style>
<div>
	<div>
		<?php echo $form->create('Job',array('action'=>'/','name'=>"SJobForm","class"=>"am-form am-form-horizontal","type"=>"get"));?>
			<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">	
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['job_category']; ?></label>
					<div class="am-u-lg-8 am-u-md-6 am-u-sm-6">
						<select name="type_id" data-am-selected>
							<option value="-1" selected ><?php echo $ld['please_select']?></option>
							<?php if(isset($informationresource_info['job_type'])&&sizeof($informationresource_info['job_type'])>0){ foreach($informationresource_info['job_type'] as $k=>$v){?>
					        	<option value="<?php echo $k;?>" <?php echo isset($type_id)&&$type_id == $k?'selected':'';?>><?php echo $v;?></option>
					        <?php }}?>
						</select>
					</div>
				</li>	
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['department']; ?></label>
					<div class="am-u-lg-8 am-u-md-6 am-u-sm-6">
						<select name="department_id" data-am-selected>
							<option value="-1" selected ><?php echo $ld['please_select'];?></option>
							<?php if(isset($informationresource_info['department_type'])&&sizeof($informationresource_info['department_type'])>0){ foreach($informationresource_info['department_type'] as $k=>$v){?>
					        	<option value="<?php echo $k;?>" <?php echo isset($department_id)&&$department_id == $k?'selected':'';?>><?php echo $v;?></option>
					        <?php }}?>
						</select>
					</div>
				</li>	
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['degree']; ?></label>
					<div class="am-u-lg-8 am-u-md-6 am-u-sm-6">
						<select name="education_id" data-am-selected>
							<option value="-1" selected ><?php echo $ld['please_select']?></option>
							<?php if(isset($informationresource_info['education_type'])&&sizeof($informationresource_info['education_type'])>0){ foreach($informationresource_info['education_type'] as $k=>$v){?>
					        	<option value="<?php echo $k;?>" <?php echo isset($education_id)&&$education_id == $k?'selected':'';?>><?php echo $v;?></option>
					        <?php }}?>
						</select>
					</div>
				</li>	
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['year_of_work_experience']; ?></label>
					<div class="am-u-lg-8 am-u-md-6 am-u-sm-6">
						<select name="experience_id" data-am-selected>
							<option value="-1" selected ><?php echo $ld['please_select']?></option>
							<?php if(isset($informationresource_info['experience_type'])&&sizeof($informationresource_info['experience_type'])>0){ foreach($informationresource_info['experience_type'] as $k=>$v){?>
					        	<option value="<?php echo $k;?>" <?php echo isset($experience_id)&&$experience_id == $k?'selected':'';?>><?php echo $v;?></option>
					        <?php }}?>
						</select>
					</div>
				</li>	
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text"><?php echo $ld['keyword'];?></label>
					<div class="am-u-lg-8 am-u-md-6 am-u-sm-6">
						<input type="text" name="keywords" class value="<?php echo @$keywords;?>" placeholder="<?php echo $ld['job_title']; ?>" />
					</div>
				</li>
				<li style="margin-bottom:10px;">
					 <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"> </label>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
						<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm"  onclick="search_jobs()" value="<?php echo $ld['search']?>" >
							<?php echo $ld['search'];?></button>
					</div>		
					</li>
			</ul>
		<?php echo $form->end()?>
	</div>
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;"> 
		<a href="<?php echo $html->url('/resumes/'); ?>" class="am-btn am-btn-default "><?php echo $ld['resume_list'] ?></a>&nbsp;
		<a class="am-btn am-btn-warning am-btn-sm am-radius addbutton" href="<?php echo $html->url('view/'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a>
	</div>
	<div class="am-panel-group am-panel-tree"  id="accordion">
		<div class="listtable_div_btm  am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title am-g">
					<div class="am-u-lg-2  am-u-md-2 am-u-sm-4">
						<label class="am-checkbox am-success  am-hide-sm-only" style="font-weight:bold;">
							<input type="checkbox"  data-am-ucheck value="checkbox" onclick='listTable.selectAll(this,"checkboxes[]")'/>
							<?php echo $ld['job_title']; ?>
						</label>
				             <label class="am-show-sm-only" style="font-weight:bold;">
							  	<?php echo $ld['job_title']; ?>
						</label>
					</div>
			      	<div class="am-u-lg-2  am-hide-md-down"><?php echo $ld['job_category']; ?></div>
					<div class="am-u-lg-1 am-hide-md-down"><?php echo $ld['department']; ?></div>
					<div class="am-u-lg-1  am-u-md-2 am-hide-sm-only"><?php echo $ld['education_requirements']; ?></div> 
					<div class="am-u-lg-1 am-u-md-2 am-hide-sm-only"><?php echo $ld['year_of_work_experience']; ?></div>
					<div class="am-u-lg-1 am-u-md-2 am-hide-sm-only"><?php echo $ld['place_of_work']; ?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-3"><?php echo $ld['status']?></div>
					<div class="am-u-lg-3 am-u-md-2 am-u-sm-5"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($job_list) && sizeof($job_list)>0){foreach($job_list as $k=>$v){?>
			<div>
				<div class="listtable_div_top am-panel-body">
					<div class="am-panel-bd am-g">
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-4">
							<label class="am-checkbox am-success am-hide-sm-only">
								<input type="checkbox" name="checkboxes[]" value="<?php echo $v['Job']['id']?>"  data-am-ucheck/>
								<?php echo $v['JobI18n']['name'];?> 
							</label>
							<label class=" am-show-sm-only">
								 <?php echo $v['JobI18n']['name'];?>&nbsp;
							</label>
						</div>
						<div class="am-u-lg-2 am-hide-md-down">
							<?php echo isset($informationresource_info['job_type'])&&isset($informationresource_info['job_type'][$v['Job']['type_id']])?$informationresource_info['job_type'][$v['Job']['type_id']]:'';?>&nbsp;
						</div>
						<div class="am-u-lg-1   am-hide-md-down">
							<?php echo isset($informationresource_info['department_type'])&&isset($informationresource_info['department_type'][$v['Job']['department_id']])?$informationresource_info['department_type'][$v['Job']['department_id']]:'';?>&nbsp;
						</div>
						<div class="am-u-lg-1 am-u-md-2 am-hide-sm-only">
							<?php echo isset($informationresource_info['education_type'])&&isset($informationresource_info['education_type'][$v['Job']['education_id']])?$informationresource_info['education_type'][$v['Job']['education_id']]:'';?>&nbsp;
						</div>
						<div class="am-u-lg-1 am-u-md-2  am-hide-sm-only">
							<?php echo isset($informationresource_info['experience_type'])&&isset($informationresource_info['experience_type'][$v['Job']['experience_id']])?$informationresource_info['experience_type'][$v['Job']['experience_id']]:'';?>&nbsp;
						</div>
						<div class="am-u-lg-1 am-u-md-2 am-hide-sm-only"><?php echo $v['JobI18n']['address'];?> </div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-3">
							<?php if ($v['Job']['status'] == 1){?>
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'jobs/toggle_on_status',<?php echo $v['Job']['id'];?>)"></span>
							<?php }elseif($v['Job']['status'] == 0){?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'jobs/toggle_on_status',<?php echo $v['Job']['id'];?>)"></span>	
							<?php }?>
						</div>
						<div class="am-u-lg-3 am-u-md-2 am-u-sm-5 am-action">
							<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-view" target='_blank' href="<?php echo $html->url($server_host.'/jobs/view/'.$v['Job']['id'])?>"><span class="am-icon-eye"></span> <?php echo $ld['view']; ?></a>
 
			                          <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/jobs/view/'.$v['Job']['id']); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a>
			                          <a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'jobs/remove/<?php echo$v['Job']['id'] ?>');"> <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>                      </a>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		<?php }}else{?>
		 
				<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
			 
		<?php }?>
	</div>
	<?php if(isset($job_list) && sizeof($job_list)){?>
		<div id="btnouterlist" class="btnouterlist">
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-hide-sm-only" style="margin-left:7px;">
				<label class="am-checkbox am-success">
					<input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox" value="checkbox">
					<?php echo $ld['select_all']?>
				</label>&nbsp;&nbsp;
				<button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="jobs_delete()" value="<?php echo $ld['delete']?>">
					<?php echo $ld['batch_delete']?></button>
			</div>
			<div class="am-u-lg-7 am-u-md-7 am-u-sm-12">
				<?php echo $this->element('pagers')?>
			</div>
            <div class="am-cf"></div>
		</div>
	<?php }?>				
</div>
<script type="text/javascript">
//批量操作
function jobs_delete(){
            var bratch_operat_check = document.getElementsByName("checkboxes[]");
   	   	var postData = "";
			for(var i=0;i<bratch_operat_check.length;i++){
				if(bratch_operat_check[i].checked){
				postData+="&checkboxes[]="+bratch_operat_check[i].value;
				}
			}

			if( postData=="" ){
			alert("<?php echo "请选择"?>");
			return;
			}
				if(confirm("<?php echo '确定删除吗？' ?>")){
				$.ajax({
				type:"POST",
				url:admin_webroot+"jobs/batch_operations/",
				data:postData,
				datatype: "json",
				success:function(data){
				window.location.href = window.location.href;
				}
				});
   	    	 	}
		 
}

function search_jobs()
{
document.SJobForm.action=admin_webroot+"jobs/";
document.SJobForm.onsubmit= "";
document.SJobForm.submit();
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
