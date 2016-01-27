<style type="text/css">
label{font-weight:normal}
.btnouter{margin:50px;}
 .am-form-horizontal .am-checkbox{padding-top: 0em;}
 .am-checkbox input[type="checkbox"]{margin-left:0px;}
</style>
<div class="">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list">
		   	<li><a data-am-collapse="{parent: '#accordion'}" href="#cronjob_info"><?php echo $ld['cronjob_info']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content" id="accordion">
		<?php echo $form->create('Cronjob',array('action'=>'view/'.(isset($cronjob_info['Cronjob']['id'])?$cronjob_info['Cronjob']['id']:''),'name'=>'userformedit'));?> 
			<input id="id" type="hidden" name="data[Cronjob][id]" value="<?php echo isset($cronjob_info['Cronjob']['id'])?$cronjob_info['Cronjob']['id']:'';?>">	
			<div class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#cronjob_info'}">
						<?php echo $ld['cronjob_info']?>
					</h4>
			    </div>
			    <div id="cronjob_info" class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['task_name']?>:</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="task_name"  maxlength="60" name="data[Cronjob][task_name]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['task_name']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['task_code']?>:</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="task_code"  maxlength="60" name="data[Cronjob][task_code]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['task_code']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['interval_time']?>:</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="interval_time"  maxlength="60" name="data[Cronjob][interval_time]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['interval_time']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['app_code']?>:</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<select class="all" name="data[Cronjob][app_code]" id="cronjob_app" data-am-selected>
									<option value="0"><?php echo $ld['select_app_code']?></option>
									<?php if(isset($appcode_tree) && sizeof($appcode_tree)>0){?>
									<?php foreach($appcode_tree as $k=>$v){?>
									  <option value="<?php echo $v['Application']['code']?>" <?php if((isset($cronjob_info)?$cronjob_info['Cronjob']['app_code']:'') == $v['Application']['code'] && (isset($cronjob_info)?$cronjob_info['Cronjob']['app_code']:'')!=""){?>selected<?php }?>><?php echo $v['Application']['code']?></option>
									<?php }}?>
								</select>
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['param01']?>:</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="param01"  maxlength="60" name="data[Cronjob][param01]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['param01']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['param02']?>:</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="param02"  maxlength="60" name="data[Cronjob][param02]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['param02']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['remark']?>:</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="remark"  maxlength="60" name="data[Cronjob][remark]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['remark']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:0;"><?php echo $ld['status']?>:</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<label class="am-checkbox am-success">
									<input type="checkbox"  id="check" data-am-ucheck onClick="statuschange()"  value="<?php if(isset($cronjob_info['Cronjob']['status'])){echo $cronjob_info['Cronjob']['status'];}?>"  <?php if(isset($cronjob_info['Cronjob']['status'])&&$cronjob_info['Cronjob']['status']==1){echo "checked";}?> />
									<?php echo $ld['valid']?>
								</label>
								<input type="hidden" value="<?php if(isset($cronjob_info['Cronjob']['status'])){echo $cronjob_info['Cronjob']['status'];}?>" name="data[Cronjob][status]" id="status" />
							</div>
						</div>	
					</div>
					<div class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					</div>
				</div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>
<script>
function statuschange(){
 var check=document.getElementById("check");
 var status=document.getElementById("status");
 if(check.checked == true){
 	status.value=1;
 }else{
 	status.value=0;
 }
}
</script>

	