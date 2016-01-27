<div class="am-g am-g-fixed" style="font-size:14px;">
<div>
	<ol class="am-breadcrumb">
	  <li class="am-active"><?php echo $ld['recruitment_page'];?></li>
	  <li><a href="javascript:void(0);"><?php echo $ld['recruitment_details'];?></a></li>
	  <li><a href="<?php echo $html->url('/resumes/'); ?>"><?php echo $ld['fill_resume'];?></a></li>
	</ol>
</div>
<div class="am-u-lg-3">
	<ul class="am-list">
	  <li>
		<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
		  <h2 class="am-titlebar-title "><?php echo $ld['using_concept_human']; ?></h2>
		</div>
	  </li>
	  <li class="visited">
	<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
		  <h2 class="a m-titlebar-title "><a href="<?php echo $html->url('/jobs/'); ?>"><?php echo $ld['talent_wanted_2']; ?></a></h2>
		</div>
	  </li>
	</ul>
</div>
<div class="am-u-lg-9">
	<div class="recruitment_search">
		<h2 class="zhaopin_title" style="margin-top:50px;"  title="<?php echo $ld['talent_wanted_2']; ?>"><?php echo $ld['talent_wanted_2']; ?></h2>
		<h3><?php echo $ld['instructions']; ?></h3>
		<p><?php echo $ld['click_on_job_title']; ?></p>
		<a target="_blank" href="<?php echo $html->url('/resumes/');?>" title="<?php echo $ld['fill_in_resume']; ?>"><button type="button" class="am-btn am-btn-primary am-btn-sm"><?php echo $ld['fill_in_resume']; ?></button></span></a>
		<h3><?php echo $ld['job_search_merge']; ?></h3>
		<?php echo $form->create('jobs',array('action'=>'index','type'=>'get','id'=>"SearchForm",'class'=>"am-form am-form-horizontal",'onsubmit'=>'return formsubmit();'));?>
			
			<div class="am-form-group" >
				<ul class="am-avg-sm-1 am-thumbnails">
				<li>
				<div class="am-u-sm-2 am-u-md-2 am-u-sm-4"><?php echo $ld['department_name']; ?></div>
				<div class="am-u-sm-6 am-u-md-6 am-u-sm-6">
					<select id="department_id" name="department_id">
						<option value = "-1" ><?php echo $ld['please_select'];?></option>
						<?php if(isset($informationresource_infos['department_type']) && sizeof($informationresource_infos['department_type'])>0){ foreach($informationresource_infos['department_type'] as $dk=>$d){?>
							<option value = "<?php echo $dk?>" <?php echo isset($department_id)&&$department_id==$dk?'selected':''?>><?php echo $d?></option>
						<?php }}?>
					</select>
				</div>
				</li>
				<li>
				<div class="am-u-sm-2 am-u-md-2 am-u-sm-4"><?php echo $ld['job_title']; ?></div>
				<div class="am-u-sm-6 am-u-md-6 am-u-sm-6">
						<input type="text" id="job_name" name="job_name" value="<?php echo isset($job_name)?$job_name:'';?>" />	
				</div>
				</li>
				<li>
				<div class="am-u-sm-2 am-u-md-2 am-u-sm-4"><?php echo $ld['job_category']; ?></div>
				<div class="am-u-sm-6 am-u-md-6 am-u-sm-6">
						<select id="type_id" name="type_id">
							<option value = "-1"><?php echo $ld['please_select'];?></option>
							<?php if(isset($informationresource_infos['job_type']) && sizeof($informationresource_infos['job_type'])>0){ foreach($informationresource_infos['job_type'] as $jk=>$j){?>
								<option value = "<?php echo $jk?>" <?php echo isset($type_id)&&$type_id==$jk?'selected':''?>><?php echo $j?></option>
							<?php }}?>
						</select>
				</div>
				</li>
				<li>
				<div class="am-u-sm-2 am-u-md-2 am-u-sm-4"><?php echo $ld['place_of_work']; ?></div>
				<div class="am-u-sm-6 am-u-md-6 am-u-sm-6">
						<select id="address" name="address">
							<option value = "-1"><?php echo $ld['please_select'];?></option>
							<?php if(isset($addrss_infos) && sizeof($addrss_infos)){foreach($addrss_infos as $a){?>
							<option value = "<?php echo $a;?>" <?php echo isset($address)&&$address==$a?'selected':''?>><?php echo $a;?></option>
							<?php }}?>
						</select>
				</div>
				</li>
				<li>
				<div class="am-u-sm-2 am-u-md-2 am-u-sm-4"><?php echo $ld['sort_data']; ?></div>
				<div class="am-u-sm-6 am-u-md-6 am-u-sm-6">
						<select id="order" name="order">
							<option value = "-1"><?php echo $ld['please_select'];?></option>
							<option value = "department_id" <?php echo isset($order)&&$order=='department_id'?'selected':''?>><?php echo $ld['sort_by_department']; ?></option>
							<option value = "education_id"  <?php echo isset($order)&&$order=='education_id'?'selected':''?>><?php echo $ld['sort_by_recruitment']; ?></option>
						</select>
				</div>
				</li>
				<li style="margin:40px 0px;">
					<div class="am-u-sm-2 am-u-md-2 am-u-sm-4">&nbsp;</div>		
					<div class="am-u-sm-6 am-u-md-6 am-u-sm-6">
						<a  href="javascript:void(0);" onclick="formsubmit()" title="<?php echo $ld['go']?>">
						<button type="button" class="am-btn am-btn-primary am-btn-sm"><?php echo $ld['go']?></button>
						</a>
					</div>
				</li>
				</ul>
			
				</div>
			</div>
		<?php echo $form->end(); ?>
	</div>
	<br />
	<br />
	<p><hr data-am-widget="divider" style="color:#336699; height:2px;" class="am-divider am-divider-default" /></p>
	<p class="list_num" style="margin-left:30%;"><span><?php echo $ld['job_list_total']; ?></span><span class="list_sum"><?php echo $counts;?></span></p>
	<div class="recruitment_list">
		<?php if(isset($job_infos)&&sizeof($job_infos)>0){ ?>
		<table class="am-table" border="0">
			<th bgcolor="#CCCCCC"><?php echo $ld['job_title_zp']; ?></th>
			<th bgcolor="#CCCCCC"><?php echo $ld['recruitment_requirements']; ?></th>
			<th bgcolor="#CCCCCC"><?php echo $ld['place_of_work']; ?></th>
		<?php foreach($job_infos as $j){ ?>
			<tr>
				<td><?php echo $svshow->link($j['JobI18n']['name'],"/jobs/view/".$j['Job']['id']); echo isset($informationresource_infos['department_type']) && isset($informationresource_infos['department_type'][$j['Job']['department_id']])?'('.$informationresource_infos['department_type'][$j['Job']['department_id']].')':'';?></td>
				<td><?php echo isset($informationresource_infos['education_type']) && isset($informationresource_infos['education_type'][$j['Job']['education_id']])?$informationresource_infos['education_type'][$j['Job']['education_id']]:'';?></td>
				<td><?php echo $j['JobI18n']['address']?></td>
			</tr>
		<?php }?>
		</table>
		<?php }else{?>
			<li><?php echo $ld['no_related_position']; ?></li>
		<?php }?>
		<?php echo  $this->element('pager');?>
	</div>
	<div class="pagenum" style="margin:20px 0px 40px 0px;"><a href="#" style="margin-left: 40px;"></a></div>
</div>
</div>
<script>
	function formsubmit(){
		var job_name=document.getElementById('job_name').value;
		var department_id=document.getElementById('department_id').value;
		var type_id=document.getElementById('type_id').value;
		var address=document.getElementById('address').value;
		var order=document.getElementById('order').value;
		var url = "job_name="+job_name+"&department_id="+department_id+"&type_id="+type_id+"&address="+address+"&order="+order;
		window.location.href = encodeURI("<?php echo $html->url('/jobs/');?>?"+url);
	}
</script>