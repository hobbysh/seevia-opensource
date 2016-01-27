<?php 
/*****************************************************************************
 * SV-Cart 编辑权限
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
?>
<style type="text/css">
.am-panel-group .am-panel-hd + .am-panel-collapse .am-panel-bd {border-top: 0px solid #ddd;}
label{text-align:right;}
.am-form-horizontal .am-form-label, .am-form-horizontal .am-radio, .am-form-horizontal .am-checkbox, .am-form-horizontal .am-radio-inline, .am-form-horizontal .am-checkbox-inline {padding-top:0px;}
.am-radio, .am-checkbox{display:inline;}

</style>
<div class="am-g">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
	  	<ul class="am-list admin-sidebar-list">
	    	<li><a data-am-collapse="{parent: '#accordion'}" href="#action"><?php echo $ld['action'];?></a></li>
	 	</ul>
	</div>

	<?php echo $form->create('OperatorAction',array('action'=>'view/'.(isset($operator_action_data['OperatorAction']['id'])?$operator_action_data['OperatorAction']['id']:'0'),'onsubmit'=>'return operator_form_check()'));?>
	<div class="am-panel-group admin-content" id="accordion">
		<div class="am-panel am-panel-default">
			<div class="am-panel-hd">
					<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#action'}">
						<?php echo $ld['action'] ?>
					</h4>
		    </div
		    <div id="action" class="am-panel-collapse am-collapse am-in">
		      	<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
					<input id="Operator_emnuId" name="data[OperatorAction][id]" type="hidden" value="<?php echo  isset($operator_action_data['OperatorAction']['id'])?$operator_action_data['OperatorAction']['id']:0;?>">
						<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<input name="data[OperatorActionI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
						<?php }}?>
							<div class="am-form-group">
								<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" ><?php echo $ld['previous_menu'];?>:</label>
								<div class="am-u-lg-7 am-u-md-7 am-u-sm-9" >
									<select data-am-selected="{maxHeight:300}" id="ActionParentId" name="data[OperatorAction][parent_id]" >
										<option value="0"><?php echo $ld['root']?></option>
										<?php if(isset($action_tree) && sizeof($action_tree)){foreach($action_tree as $k=>$v){//第一层 ?>
										<option value="<?php echo $v['OperatorAction']['id'];?>" <?php echo isset($operator_action_data['OperatorAction']['parent_id'])&&$v['OperatorAction']['id']==$operator_action_data['OperatorAction']['parent_id']?"selected":"";?> ><?php echo $v['OperatorActionI18n']['name'];?></option>
										<?php if(isset($v['SubAction']) && sizeof($v['SubAction'])>0){foreach($v['SubAction'] as $kk=>$vv){//第二层?>
										<option value="<?php echo $vv['OperatorAction']['id'];?>" <?php echo isset($operator_action_data['OperatorAction']['parent_id'])&&$vv['OperatorAction']['id']==$operator_action_data['OperatorAction']['parent_id']?"selected":"";?> >|-- <?php echo $vv['OperatorActionI18n']['name'];?></option>
										<?php if(isset($vv['SubAction']) && sizeof($vv['SubAction'])>0){foreach($v['SubAction'] as $kkk=>$vvv){//第二层 ?>
										<?php }}}}}}?>
									</select>
								</div>
							</div>
										
							<div class="am-form-group">
								<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['z_action_name'];?>:</label>
								<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
									<div class="am-u-lg-9 am-u-md-7 am-u-sm-9" style="margin-top:5px;">
									<input type="text" id="menu_name_<?php echo $v['Language']['locale']?>"  name="data[OperatorActionI18n][<?php echo $k;?>][name]" value="<?php echo isset($operator_action_data['OperatorActionI18n'][$v['Language']['locale']])?$operator_action_data['OperatorActionI18n'][$v['Language']['locale']]['name']:'';?>" />
									</div>
									<?php if(sizeof($backend_locales)>1){?>	
										<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left">	
											<?php echo $ld[$v['Language']['locale']];?>&nbsp;<em>*</em>
										</label>
									<?php }?>
								<?php }}?>	
								</div>						
							</div>
							<div class="am-form-group">
								<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['code'];?>:</label>
								<div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
									<input id="action_code" name="data[OperatorAction][code]" type="text" value="<?php echo isset($operator_action_data['OperatorAction']['code'])?$operator_action_data['OperatorAction']['code']:'';?>">
								</div>
								<span class="am-u-lg-1 am-u-md-1 am-u-sm-1"><em>*</em></span>
							</div>
							<div class="am-form-group">
								<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['versions'];?>:</label>
								<div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
									<input name="data[OperatorAction][section]" type="text" value="<?php echo isset($operator_action_data['OperatorAction']['section'])?$operator_action_data['OperatorAction']['section']:'';?>">
								</div>	
							</div>
							<div class="am-form-group">
								<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['status'];?>:</label>
								<div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
									<label class="am-radio am-success">
										<input type="radio" name="data[OperatorAction][status]" style="margin-left:0px;" data-am-ucheck value="1" <?php if((isset($operator_action_data['OperatorAction']['status'])&&$operator_action_data['OperatorAction']['status'] == 1)||!isset($operator_action_data['OperatorAction']['status'])){echo "checked";}?> /><?php echo $ld['yes']?>
									</label>&nbsp;&nbsp;&nbsp;
									<label class="am-radio am-success">
										<input type="radio" name="data[OperatorAction][status]" style="margin-left:0px;"  data-am-ucheck value="0" <?php if(isset($operator_action_data['OperatorAction']['status'])&&$operator_action_data['OperatorAction']['status'] != 1){echo "checked";} ?> />
										<?php echo $ld['no']?>
									</label>
								</div>
							</div>
							<div class="am-form-group">
								<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['orderby'];?>:</label>
								<div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
									<input type="text" style="width:113px;border:1px solid #649776" name="data[OperatorAction][orderby]" value="<?php echo isset($operator_action_data['OperatorAction']['orderby'])?$operator_action_data['OperatorAction']['orderby']:50 ?>" onkeyup="check_input_num(this)"/><br /><?php echo $ld['sorting_prompt']?>
								</div>		
							</div>
							<div class="btnouter">
								<button type="submit" class="am-btn am-btn-success am-radius" value="" ><?php echo $ld['d_submit'];?></button>
								<button type="reset"  class="am-btn am-btn-default am-radius" value=""  ><?php echo $ld['d_reset']?></button>
							</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $form->end();?>				
</div>	
<!--ConfigValues End-->
<script>
function operator_form_check(){
  	var operator_action_name = document.getElementById("menu_name_"+backend_locale);
	var operator_action_code = document.getElementById("action_code");
	if(operator_action_name.value==""){
		alert("请输入名称");
		return false;
	}
	if(operator_action_code.value==""){
		alert("请输入代码");
		return false;
	}
	return true;
}
</script>