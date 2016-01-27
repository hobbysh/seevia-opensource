<style type="text/css">
	.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align:text-top;}
	.am-checkbox input[type="checkbox"]{margin-left:0px;}
	.am-form-label{font-weight:bold;margin-left:10px;}
	.am-panel-title div{font-weight:bold;}
       .am-form-label-text{margin-left:10px;}
</style>
<div>
	<?php echo $form->create('WarnList',array('action'=>'/','name'=>"SearchForm","type"=>"get","class"=>"am-form-horizontal"));?>
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label  ">类型</label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
					<select id="warn_type" name="warn_type" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}">
						<option value="all"><?php echo $ld['all_data']; ?></option>
						<?php foreach($warn_type_arr as $k=>$v){?>
						<option value="<?php echo $k;?>" <?php if(isset($warn_type)&&$warn_type==$k){echo 'selected';}?>><?php echo $v;?></option>
						<?php }?>
					</select>
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label-text"><?php echo $ld['last_alarmtime']?></label>	
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="text" class="am-form-field" placeholder=""  name="start_date" value="<?php echo $start_date;?>" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly/>
				</div>
				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center"><em>-</em></label>	
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="text" class="am-form-field" placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="end_date" value="<?php echo $end_date;?>" readonly/>
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['severity_level']?></label>	
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
					<select id="level" name="level" data-am-selected>
						<option value="all"><?php echo $ld['all_data']?></option>
						<?php foreach($warn_level_arr as $k=>$v){?>
						<option value="<?php echo $k;?>" <?php if(isset($level)&&$level==$k) echo 'selected';?>><?php echo $v;?></option>
						<?php }?>
					</select>
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label  "><?php echo $ld['processing_status']?></label>	
				<div class="am-u-lg-8 am-u-md-7 am-u-sm-7">
					<select id="status" name="status" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}"> 
						
						<option value="-1" <?php if(isset($status)&&$status==-1) echo 'selected';?>><?php echo $ld['all_data']?></option>
						<?php foreach($warn_status_arr as $k=>$v){?>
						<option value="<?php echo $k;?>" <?php if(isset($status)&&$status==$k) echo ' ';?>><?php echo $v;?></option>
						<?php }?>
					</select>
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['alarm_number']?></label>	
				<div class="am-u-lg-7 am-u-md-8 am-u-sm-7">
					<input type="text" id="times" name="times"  class="am-form-field am-radius"  value="<?php echo @$times;?>"/>
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label-text "><?php echo $ld['deal_people']?></label>	
				<div class="am-u-lg-8 am-u-md-7 am-u-sm-7">
    				<input type="text" id="operator_name"  class="am-form-field am-radius"  name="operator_name" value="<?php echo @$operator_name;?>" />
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text "><?php echo $ld['keyword']?></label>	
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
					<input type="text" name="keywords"  class="am-form-field am-radius"  placeholder="<?php echo $ld['remark']?>/<?php echo $ld['main_parameters']?>/<?php echo $ld['backup_parameters']?>"   id="keywords" value="<?php echo @$keywords?>"/>	
				</div>
			</li>
			 <li style="margin-bottom:10px;">
	     	<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label  "> </label>	
			       <div class="am-u-lg-7 am-u-md-7 am-u-sm-7"> 		
					<input type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['search']?>" onclick=" " />
				</div>
			</li>
		</ul>
	<?php echo $form->end();?>
	<?php echo $form->create('WarnList',array('action'=>'/','name'=>"WarnListForm","type"=>"get"));?>
		<div class="am-panel-group am-panel-tree">
			<div class="listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title">
						<div  class="am-u-lg-3  am-u-md-5 am-u-sm-5 am-show-lg-only" >
							<label class="am-checkbox am-success" style="font-weight:bold;">
								<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/>
								<?php echo $ld['processing_status']?>
							</label>
						</div>
						
						<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><?php echo $ld['type']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['remark']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['main_parameters']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['backup_parameters']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['question_time']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['severity_level']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['alarm_number']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['last_alarmtime']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['deal_people']?></div>
						<div style="clear:both"></div>
					</div>
				</div>
			</div>
			<?php if(isset($warn_list) && sizeof($warn_list)){foreach($warn_list as $k=>$v){?>			
				<div>
					<div class="listtable_div_top am-panel-body" >
				    	<div class="am-panel-bd">
							<div  class="am-u-lg-3 am-u-md-5 am-u-sm-5 am-show-lg-only" style="padding-top:5px;">
								<label class="am-checkbox am-success" style="font-weight:bold;">
									<input  type="checkbox" name="checkboxes[]" value="<?php echo $v['WarnList']['id']?>" data-am-ucheck/>
										<select id="act_status" name="act_status" data-am-selected="{btnWidth:100,btnSize:'sm'}">
								 	<option value="0" <?php if(isset($v['WarnList']['status'])&&$v['WarnList']['status']==0) echo 'selected';?>><?php echo $ld['untreated']?></option>
									<option value="1" <?php if(isset($v['WarnList']['status'])&&$v['WarnList']['status']==1) echo 'selected';?>><?php echo $ld['processing']?></option>
									<option value="2" <?php if(isset($v['WarnList']['status'])&&$v['WarnList']['status']==2) echo 'selected';?>><?php echo $ld['processed']?></option>
								</select>
								<?php if($svshow->operator_privilege("warn_lists_status")){?>
								<input type="button" class="am-btn am-btn-sm am-btn-success am-radius" onclick="change_status(<?php echo $v['WarnList']['id'];?>)" value="<?php echo $ld['submit']?>" />
								<?php }?>&nbsp;
								</label>
							</div>
							
							<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"style="padding-top:10px;">
								<?php if(isset($v['WarnList']['type'])&&!empty($warn_type_arr)){echo isset($warn_type_arr[$v['WarnList']['type']])?$warn_type_arr[$v['WarnList']['type']]:''; }?>&nbsp;
							</div>
							<div class="am-u-lg-1 am-show-lg-only" style="padding-top:10px;"><?php echo $v['WarnList']['note']?>&nbsp;</div>
							<div class="am-u-lg-1 am-show-lg-only" style="padding-top:10px;"><?php echo $v['WarnList']['type_id']?>&nbsp;</div>
							<div class="am-u-lg-1 am-show-lg-only" style="padding-top:10px;"><?php echo $v['WarnList']['type_param']?>&nbsp;</div>
							<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $v['WarnList']['created']?>&nbsp;</div>
							<div class="am-u-lg-1 am-u-md-2 am-u-sm-2" style="padding-top:10px" >
								<?php if(isset($v['WarnList']['level'])&&!empty($warn_level_arr)){echo isset($warn_level_arr[$v['WarnList']['level']])?$warn_level_arr[$v['WarnList']['level']]:'';}?>&nbsp;
							</div>
							<div class="am-u-lg-1 am-show-lg-only" style="padding-top:10px"><?php echo $v['WarnList']['times']?>&nbsp;</div>
							<div class="am-u-lg-1 am-show-lg-only" ><?php echo $v['WarnList']['last_time']?>&nbsp;</div>
							<div class="am-u-lg-1 am-show-lg-only" style="padding-top:10px"><?php echo $v['WarnList']['operator_name']?>&nbsp;</div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>	
			<?php }}else{?>
			<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
			<?php }?>			
		</div>
		<?php if($svshow->operator_privilege("warn_lists_status")){
			if(isset($warn_list) && sizeof($warn_list)){?>
		<div id="btnouterlist" class="btnouterlist">
			<div class="am-u-lg-6 am-u-md-4 am-u-sm-12">
				<label class="am-checkbox am-success  am-u-lg-2 am-u-md-5 am-u-sm-3" style="margin-top:5px;margin-left:5px;">
					<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck>
					<span style="margin-left:15px;"><?php echo $ld['select_all']?></span>
				</label>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-fl">
				<select name="act_type" id="act_type" onchange="operate_change(this)" data-am-selected>
					<option value="all"><?php echo $ld['please_select']?></option>
					<option value="0"><?php echo $ld['untreated']?></option>
					<option value="1"><?php echo $ld['processing']?></option>
					<option value="2"><?php echo $ld['processed']?></option>
				</select>
			</div>
			<div class="am-u-lg-3 am-u-md-2 am-u-sm-3">
				<input type="button" onclick="diachange()" class="am-btn am-btn-success am-radius am-btn-sm"  value="<?php echo $ld['submit']?>" />
			</div>
			</div>
			<div  class="am-u-lg-6 am-u-md-12 am-u-sm-12">
				<?php echo $this->element('pagers')?>
			</div>
			<div class="am-cf"></div>
		</div>
	<?php }}?>
	<?php echo $form->end();?>
</div>
<script>
function operate_change(obj){

}
function change_status(obj){
	var a=document.getElementById("act_status");
	//var status=a.value;
	for(var j=0;j<a.options.length;j++){
		if(a.options[j].selected){
			var vals = a.options[j].text ;
		}
	}
	if( j>=1 ){
		if(confirm("<?php echo $ld['submit']?>"+vals+'?')){
			document.WarnListForm.action=admin_webroot+"warn_lists/change/"+obj+"/"+a.value;
			document.WarnListForm.onsubmit= "";
			document.WarnListForm.submit();
		}
	}
}
function diachange(){
	var a=document.getElementById("act_type");
	if(a.value!='all'){
		for(var j=0;j<a.options.length;j++){
			if(a.options[j].selected){
				var vals = a.options[j].text ;
			}
		}
		var id=document.getElementsByName('checkboxes[]');
		var i;
		var j=0;
		var image="";
		for( i=0;i<=parseInt(id.length)-1;i++ ){
			if(id[i].checked){
				j++;
			}
		}
		if( j>=1 ){
		//	layer_dialog_show('确定'+vals+'?','batch_action()',5);
			if(confirm("<?php echo $ld['submit']?>"+vals+'?'))
			{
				batch_action();
			}
		}else{
		//	layer_dialog_show('请选择！！','batch_action()',3);
			if(confirm(j_please_select))
			{
				batch_action();
			}
		}
	}
}
function batch_action()
{
document.WarnListForm.action=admin_webroot+"warn_lists/batch";
document.WarnListForm.onsubmit= "";
document.WarnListForm.submit();
}

</script>