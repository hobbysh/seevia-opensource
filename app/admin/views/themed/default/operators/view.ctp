<style type="text/css">
 .am-checkbox {margin-top:0px; margin-bottom:0px;}
 .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
 .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
 .am-radio, .am-checkbox{display:inline;}
 .am-form-label{font-weight:bold;}
 .btnouter{margin:50px;}
</style>
<?php //pr($OperatorActions); ?>
<div class="am-g">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-detail-menu">
	  <ul class="am-list admin-sidebar-list">
	    <li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		<?php if(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']!="all"||!isset($operator_data['Operator']['actions'])){?>
		<li><a data-am-collapse="{parent: '#accordion'}" href="#select_all"><?php echo $ld['select_all']?></a></li>
		<li><a data-am-collapse="{parent: '#accordion'}" href="#roles"><?php echo $ld['operator_roles']?></a></li>
		<?php	if(isset($view_type) && ($view_type=="S"&&$type=="D"&&$view_type_id>0)||($view_type=="D")){foreach($OperatorActions as $k=>$v){ 
				if (isset($dealer_actions[$v['OperatorAction']['code']])&&is_array($dealer_actions[$v['OperatorAction']['code']])) {?>
					<li><a data-am-collapse="{parent: '#accordion'}" href="#"><?php echo $v['OperatorActionI18n']['name'];?></a></li>
		<?php }}}elseif(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']=="all"){}else{foreach($OperatorActions as $k=>$v){?>
			<li>
				<a data-am-collapse="{parent: '#accordion'}" href="#<?php echo $v['OperatorActionI18n']['name'];?>"><?php echo $v['OperatorActionI18n']['name']?></a>		
			</li>
				
		<?php }}}?>	
	  </ul>
	</div>
		  
	<div class="am-panel-group admin-content  am-detail-view" id="accordion">
	<!--基本信息-->
	<?php echo $form->create('Operators',array('action'=>'view/'.(isset($operator_data['Operator']['id'])?$operator_data['Operator']['id']:"0"),'onsubmit'=>'return check_all()','name'=>'userformedit'));?>
		<div class="am-panel am-panel-default">
			<div class="am-panel-hd">
					<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}">
						<label><?php echo $ld['basic_information'] ?></label>
					</h4>
		    </div>
			<div id="basic_information" class="am-panel-collapse am-collapse am-in">
		      	<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
				    <input type="hidden" name="data[OperatorMenu][id]" value="<?php echo isset($this->data['OperatorMenu']['id'])?$this->data['OperatorMenu']['id']:'0'; ?>"/>
				    <ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1 am-thumbnails">
					<?php if(isset($_SESSION['type_id']) && $_SESSION['type_id']=="0"){?>
	  		  		<?php if($_SESSION['type_id']=="0"&&!empty($type)&&!empty($view_type_id)){?>
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 " style="text-align:right;">
							<?php echo $ld['operator_class']?>
						</label>
						<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
							<input name="data[Operator][type]" type="hidden"  value="<?php if(isset($type)){echo $type;}else{echo $_SESSION['type'];}?>"><?php if($type=="S"){echo $ld['system'];}elseif($type=="D"){echo $ld['dealer'];}if($_SESSION['type']=="S"&&$type==""){echo $ld['system'];}elseif($_SESSION['type']=="D"&&$type==""){echo $ld['dealer'];}?>
							<input name="data[Operator][type_id]" id="type_id"  type="hidden"  value="<?php if(isset($view_type_id)){echo $view_type_id;}else{echo $_SESSION['type_id'];};?>" /><?php if($type=="D"){echo isset($dealer_name['Dealer']['name'])?$dealer_name['Dealer']['name']:"";}if($_SESSION['type']=="D"&&$type==""){echo isset($dealer_name['Dealer']['name'])?$dealer_name['Dealer']['name']:"";}?>
						</div>
					</li>
					<li><div class="am-show-lg-only">&nbsp;</div></li>
					
					<?php }elseif(!empty($operator_data['Operator']['id'])){?>
						<li>  
							<div class="am-u-lg-4 am-u-md-4 am-u-sm-4" ><label class="am-form-label am-text-left" style="padding-top:0px"><?php echo $ld['operator_class']?></label></div>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<input name="data[Operator][type]" type="hidden"  value="<?php if($operator_data['Operator']['type_id']=="0"){echo "S";}else{echo "D";}?>"><?php if($operator_data['Operator']['type_id']=="0"){echo $ld['system'];}?>
								<input name="data[Operator][type_id]" id="type_id"  type="hidden"  value="<?php echo $operator_data['Operator']['type_id'];?>" />
							</div>
						</li>
					<li><div class="am-show-lg-only">&nbsp;</div></li>
					
					<?php  }else{?>
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4"  ><?php echo $ld['operator_class']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input name="data[Operator][type]" type="hidden"  value="S"><?php echo $ld['system'];?>
							<input name="data[Operator][type_id]" id="type_id"  type="hidden"  value="0" />
						</div>
					</li>
					<li><div class="am-show-lg-only">&nbsp;</div></li>
					
					<?php }}else{?>
			  		<?php if(isset($_SESSION['type_id']) && $_SESSION['type_id']!="0" && !empty($type) && !empty($view_type_id) || $_SESSION['type_id']!="0" && $type=="D" && $view_type_id!=0){?>
					 	<li>  
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="text-align:right;">
								<?php echo $ld['operator_class']?>
							</label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							 	<input name="data[Operator][type]" type="hidden"  value="<?php echo $_SESSION['type'];?>"><?php echo $ld['dealer'];?>
								<input name="data[Operator][type_id]" id="type_id"  type="hidden"  value="<?php echo $view_type_id;?>" /><?php echo isset($dealer_name['Dealer']['name'])?$dealer_name['Dealer']['name']:"";?>
							</div>
						</li>
					<?php }elseif(!empty($operator_data['Operator']['id'])){?>
						
					 	<li>   
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;">
								<?php echo $ld['operator_class']?>
							</label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<input name="data[Operator][type]" type="hidden"  value="D">
								<input name="data[Operator][type_id]" id="type_id"  type="hidden"  value="<?php echo $operator_data['Operator']['type_id'];?>" />
							</div>
						</li>
					<?php } }?>
					
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:12px;">
							<input name="data[Operator][id]" type="hidden" id="id" value="<?php echo isset($operator_data['Operator']['id'])?$operator_data['Operator']['id']:'';?>">
							<?php echo $ld['operator']?>
						</label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<input name="data[Operator][name]" id="name" onblur="operator_change()" type="text"  value="<?php echo empty($operator_data['Operator']['name'])?'':$operator_data['Operator']['name'];?>" />
						</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:10px;"><em style="color:red;">*</em></div>
					</li>
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:12px;">Email</label>
						<?php if(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']=="all"){?>
							<input name="data[Operator][actions]" id="" type="hidden"  value="all" />
						<?php 	}?>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<input name="data[Operator][email]" id="user_email" type="text"  value="<?php echo empty($operator_data['Operator']['email'])?'':$operator_data['Operator']['email'];?>" />
						</div> 
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:10px;"><em style="color:red;">*</em></div>
					</li>
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:12px;"><?php echo $ld['mobile']?></label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<input name="data[Operator][mobile]" type="text"  value="<?php echo empty($operator_data['Operator']['mobile'])?'':$operator_data['Operator']['mobile'];?>" />
						</div>
					</li>		
					<li>
			 			<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:12px;">
			 				<?php echo $ld['new_password']?>
			 			</label>
			 			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			 				<input name="newpassword" id="user_new_password" type="password" ></div>
			 			<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:10px;"><em style="color:red;">*</em></div>
		 			</li>		
							
					<li> 
		 				<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:12px;">
		 					<?php echo $ld['confirm_password_again']?>
		 				</label>
			 			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			 				<input name="confirmpassword" id="user_new_password2" type="password" >
			 			</div>
			 			<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:10px;"><em style="color:red;">*</em></div>
		 			</li>
					
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;">
							<?php echo $ld['produce_password'];?>
						</label>
			 			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
			 				<button class="am-btn am-btn-warning am-radius am-btn-sm" type="button" value="" onclick="produce_password()"><?php echo $ld['produce']?></button>
			 			</div>
			 			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
			 				<input type="text" name="produce" id="user_produce_password" value="">
			 			</div>
		 			</li>
					
					
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;">
							<?php echo $ld['operator_default_language']?>
						</label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<select name="data[Operator][default_lang]" data-am-selected>
								<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach($backend_locales as $k => $v){?>
								<option value="<?php echo $v['Language']['locale'];?>"<?php if(!empty($operator_data['Operator']['default_lang'])&&$v['Language']['locale']==$operator_data['Operator']['default_lang']){echo "selected";}?> ><?php echo $v['Language']['name']?></option>
								<?php }}?>
							</select>
						</div>
					</li>
					<li></li>
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;">
							<?php echo $ld['templates']?>
						</label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<select name="data[Operator][template_code]" data-am-selected>
								<?php foreach($template_list as $v){ ?>
								<option value="<?php echo $v;?>"<?php if(!empty($operator_data['Operator']['template_code'])&&$v==$operator_data['Operator']['template_code']){echo "selected";}?> ><?php echo $v; ?></option>
								<?php } ?>
							</select>
						</div>
					</li>
					<li></li>
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:11px;">
							<?php echo $ld['diary_record']?>
						</label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<label class="am-radio am-success">
								<input type="radio" name="data[Operator][log_flag]" style="margin-left:0px;" data-am-ucheck value="1" <?php if(!isset($operator_data['Operator']['log_flag'])||!empty($operator_data['Operator']['log_flag'])&&$operator_data['Operator']['log_flag']==1){echo "checked";}?> /><?php echo $ld['valid']?>
							</label>&nbsp;&nbsp;
							<label class="am-radio am-success">
								<input type="radio"  name="data[Operator][log_flag]" style="margin-left:0px;"  data-am-ucheck value="0" <?php if(isset($operator_data['Operator']['log_flag'])&&$operator_data['Operator']['log_flag']==0){echo "checked";}?>   /><?php echo $ld['invalid']?>
							</label>
						</div>
					</li>
					<li></li>	
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top:11px;text-align:right;">
							<?php echo $ld['status']?>
						</label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<label class="am-radio am-success">
								<input type="radio"  name="data[Operator][status]"  style="margin-left:0px;"  data-am-ucheck value="1" <?php if(!isset($operator_data['Operator']['status'])||!empty($operator_data['Operator']['status'])&&$operator_data['Operator']['status']==1){echo "checked";}?> ><?php echo $ld['valid']?>
							</label>&nbsp;&nbsp;
							<label class="am-radio am-success">
								<input type="radio"  name="data[Operator][status]" data-am-ucheck value="0" <?php if(isset($operator_data['Operator']['status'])&&$operator_data['Operator']['status']==0){echo "checked";}?> /><?php echo $ld['invalid']?></label>
						</div>
					</li>
			 		</ul>	
					<?php if( empty($this->data['Operator']['id']) || $this->data['Operator']['id'] !=1 ){?>
						<div class="btnouter">
							<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button>
							<button type="reset" class="am-btn am-btn-default am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
						</div>
					<?php }?>
					<div  style="clear:both;"></div>
				</div>
			</div>
		</div>


	<!--全选-->
		<?php if(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']!="all"||!isset($operator_data['Operator']['actions'])){?>
			<div class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h2 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#select_all'}">
						<label class="am-checkbox am-success" style="font-weight:bold;">
							<input type="checkbox" name="checkbox" data-am-ucheck value="checkbox" class="checkboxall" onclick="checkAll(this.form, this);" />
							<?php echo $ld['select_all']?>
						</label>
					</h2>
				</div>
				<div id="select_all" class="am-panel-collapse am-collapse am-in">
					<div class="am-panel-bd">
						<div class="btnouter">
							<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit']?></button>
							<button type="reset" class="am-btn am-btn-default am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
						</div>
					</div>
				</div>
			</div>
		<?php }?>


		<!--Roles-->
	
	<?php if(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']!="all"||!isset($operator_data['Operator']['actions'])){?>
	<div class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<h2 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#roles'}">
				<label><?php echo $ld['operator_roles']?></label>
			</h2>
		</div>
		<?php if(isset($view_type) && $view_type=="S" && isset($type) && $type!="D" || !isset($type) && $view_type=="S" || $view_type=="S" && isset($type) && $type=="D" && $view_type_id=="0"){?>
		<div class="am-g">		
		<div id="roles" class="am-panel-collapse am-collapse am-in">
			<div class="am-panel-bd">	
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-1 am-form-label" style="text-align:left;margin-left:10%;">
						<?php echo $ld['operator_select_role']?>
					</label>
					<?php if(isset($operator_roles) && sizeof($operator_roles)>0){?>
							<?php foreach($operator_roles as $ov){?>
								<div class="am-u-lg-2 am-u-md-3 am-u-sm-2">
								<label class="am-checkbox am-success am-u-lg-5">
									<input type="checkbox" name="operator_role[]" data-am-ucheck value="<?php echo $ov['OperatorRole']['id']?>" onclick="getOperatorActionByRole()" <?php if(in_array($ov['OperatorRole']['id'],$this->data['Operator']['role_arr'])) echo 'checked';?>   />
									&nbsp;&nbsp;&nbsp;<?php echo $ov['OperatorRole']['name']?>&nbsp;
								</label>
								</div>
							<?php }?>
					<?php }?>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">	
						<label class="am-from-label am-success">
							<?php echo $html->link($ld['operator_roles'],"/roles",array('target'=>"_blank",'class'=>'taobtn '));?>
						</label>
					</div>
				</div>
					<div  style="clear:both;"></div>		

			</div>
		</div>
		</div>	
	</div>
	<?php 	}}?>
		
		
	<?php if(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']!="all"||!isset($operator_data['Operator']['actions'])){?>
	<div class="am-panel am-panel-default">
		<?php if(isset($OperatorActions) && sizeof($OperatorActions)>0){
			if(isset($view_type) && ($view_type=="S"&&$type=="D"&&$view_type_id>0)||($view_type=="D")){foreach($OperatorActions as $k=>$v){ 
				if (isset($dealer_actions[$v['OperatorAction']['code']])&&is_array($dealer_actions[$v['OperatorAction']['code']])) {?>
		<div class="am-panel-hd">
			<h2 class="am-panel-title">
				<label class="am-checkbox am-success "><input type="checkbox"  class="checkboxall" data-am-ucheck onclick='checkall(this)' />
				<?php echo $v['OperatorActionI18n']['name']?></label>
			</h2>
		</div>
		<div id="<?php echo $v['OperatorActionI18n']['name'];?>" class="am-panel-collapse am-collapse am-in">
			<div class="am-panel-bd ">
			<?php if(isset($v['children']) && sizeof($v['children'])>0)foreach($v['children'] as $vv){if ((isset($dealer_actions[$v['OperatorAction']['code']][$vv['OperatorAction']['code']])&&is_array($dealer_actions[$v['OperatorAction']['code']][$vv['OperatorAction']['code']])||isset($dealer_actions[$v['OperatorAction']['code']]['所有']['所有'])&&$dealer_actions[$v['OperatorAction']['code']]['所有']['所有']===true)){?>
			    <div class="am-form-group">
					<label class="am-checkbox am-success" >
						<input class="operactor_actions"  id='operactor_action_id<?php echo $vv["OperatorAction"]["id"] ?>' type="checkbox"  data-am-ucheck name='<?php echo "ops_".$v["OperatorAction"]["id"];?>' onclick="checktr(this)" value="<?php echo $vv['OperatorAction']['id']?>" /><?php echo $vv['OperatorActionI18n']['name']?>
					</label>
					<div>
						<?php if(isset($vv['children']) && sizeof($vv['children'])>0){foreach($vv['children'] as $vvv){if (isset($dealer_actions[$v['OperatorAction']['code']][$vv['OperatorAction']['code']][$vvv['OperatorAction']['code']])&&is_array($dealer_actions[$v['OperatorAction']['code']][$vv['OperatorAction']['code']][$vvv['OperatorAction']['code']])||isset($dealer_actions[$v['OperatorAction']['code']][$vv['OperatorAction']['code']]['所有'])&&$dealer_actions[$v['OperatorAction']['code']][$vv['OperatorAction']['code']]['所有']===true||isset($dealer_actions[$v['OperatorAction']['code']]['所有']['所有'])&&$dealer_actions[$v['OperatorAction']['code']]['所有']['所有']===true||isset($dealer_actions[$v['OperatorAction']['code']][$vv['OperatorAction']['code']][$vvv['OperatorAction']['code']])&&$dealer_actions[$v['OperatorAction']['code']][$vv['OperatorAction']['code']][$vvv['OperatorAction']['code']]===true) {
							?>
							<label class="am-checkbox am-success " ><input type="checkbox" class="operactor_actions" data-am-ucheck  id='operactor_action_id<?php echo $vvv["OperatorAction"]["id"] ?>' name="OperatorAction[]" value="<?php echo $vvv['OperatorAction']['id']?>"<?php if(in_array($vvv['OperatorAction']['id'],$operator_data['Operator']['action_arr'])) echo 'checked';?> /><?php echo $vvv['OperatorActionI18n']['name']?></label>
						<?php }}}?>
					</div>
				</div>
			<?php }}?>
			</div>
		</div>
	</div>
	<?php	}}}elseif(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']=="all"){}else{foreach($OperatorActions as $k=>$v){?>
	<div class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<h2 class="am-panel-title">
				<label class="am-checkbox am-success " style="font-weight:bold;">
					<input type="checkbox" class="checkboxall" data-am-ucheck onclick='checkall(this)' />
					<?php echo $v['OperatorActionI18n']['name']?>
				</label>
			</h2>
		</div>
		<div class="OperatorAction_list ">
			<?php if(isset($v['children']) && sizeof($v['children'])>0)foreach($v['children'] as $vv){?>
			    <div class="am-form-group" style="margin-left:10%;margin-bottom:15px;margin-top:15px;">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-checkbox am-success " style="font-weight:bold;">
							<input class="operactor_actions" id='operactor_action_id<?php echo $vv["OperatorAction"]["id"] ?>' type="checkbox" name='<?php echo "ops_".$v["OperatorAction"]["id"];?>' data-am-ucheck  onclick="checktr(this)" value="<?php echo $vv['OperatorAction']['id']?>" />&nbsp;&nbsp;
							<?php echo $vv['OperatorActionI18n']['name']?>
					</label>
					<div class="am-u-lg-10">
						<?php if(isset($vv['children']) && sizeof($vv['children'])>0){?>
						<ul class="am-avg-lg-5">
						<?php foreach($vv['children'] as $vvv){?>
							<li>
							<label class="am-u-lg-12 am-u-md-3 am-u-sm-3 am-checkbox am-success " style="font-weight:normal;">	
								<input type="checkbox" class="operactor_actions"  id='operactor_action_id<?php echo $vvv["OperatorAction"]["id"] ?>' data-am-ucheck  name="OperatorAction[]" value="<?php echo $vvv['OperatorAction']['id']?>"<?php if(in_array($vvv['OperatorAction']['id'],$operator_data['Operator']['action_arr'])) echo 'checked';?> />
								&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $vvv['OperatorActionI18n']['name']?>
							</label>
							</li>
						<?php }?>
						</ul>
						<?php }?>
					</div>
				</div>
				<div style="clear:both;"></div>
			<?php }?>
		</div>
		<div class="btnouter"  style="margin-top:20px;">
			<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
			<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
		</div>
			
	</div>

	<?php }}}} ?>	
						
			</div>
		
	<?php  echo $form->end();?>
		
	</div>			
</div>

<script type="text/javascript">
/**
 * 折叠菜单列表
 */

//操作员分批全选
function checktr(obj){
	var checktr = obj.parentNode.parentNode;
	var checkbox = checktr.getElementsByTagName("input");
	var checkStatus = obj.checked;
	for(i=1,len = checkbox.length; i<len;i++)
	{
		checkbox[i].checked = checkStatus;
	}
}
function on_hide(){
  document.getElementById("hide").style.display = (document.getElementById("type").options[1].selected ==true) ? "inline-block" : "none";
}

function checkall(obj){
	var checkTable = $(obj).parent().parent().parent().parent();
	var checkbox = checkTable.find(".OperatorAction_list input[type=checkbox]");
	var checkStatus = obj.checked;
	for(var i=0;i<checkbox.length;i++){
		checkbox[i].checked = checkStatus;
	}
}

function produce_password(){
	$user_new_password=document.getElementById("user_new_password");
	$user_new_password2=document.getElementById("user_new_password2");
	$user_produce_password=document.getElementById("user_produce_password");
	var postData = "";
	postData = "&password="+1;
	$.ajax({
		url:admin_webroot+"operators/produce_password/",
		type:"POST",
		data:postData,
		dataType:"json",
		success:function(data){
			if(data.code.length=="8"){
            	$user_produce_password.value=data.code;
     	  	 	$user_new_password.value=data.code;
     	  	 	$user_new_password2.value=data.code;
			}
		}
	});
/*	YUI().use("io",function(Y) {
		var sUrl = admin_webroot+"operators/produce_password/";//访问的URL地址
		var cfg = {
			method: "POST",
			data: postData
		};
			var request = Y.io(sUrl, cfg);//开始请求
           var handleSuccess = function(ioId, o){
                try{
               	 eval('result='+o.responseText);
                }catch(e){
                     alert("对象转换失败");
                }
             	 if(result.code.length=="8"){
                	$user_produce_password.value=result.code;
         	  	 	$user_new_password.value=result.code;
         	  	 	$user_new_password2.value=result.code;
               	}
           }
           var handleFailure = function(ioId, o){
                alert("异步请求失败");
           }
           Y.on('io:success', handleSuccess);
           Y.on('io:failure', handleFailure);
      });*/
};

function checkall2(obj)
{
	var checkboxs = document.getElementsByName(obj);
	for(var i=checkboxs.length;i--;){
		checkboxs[i].click();
	}
}

//操作员复选框全部选取
function checkAll(frm, checkbox){
	for(i = 0; i < frm.elements.length; i++){
		if( frm.elements[i].type == "checkbox" ){
			frm.elements[i].checked = checkbox.checked;
		}
	}
}

function check_all(){
	if(document.getElementById('name').value==''){
		alert("<?php echo $ld['status']?><?php echo $ld['fill_in_user_name'];?>");
		return false;
	}
	if(document.getElementById('user_email').value==''){
		alert("<?php echo $ld['please_fill_user_email']?>");
		return false;
	}
	 var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	 var email=document.getElementById('user_email').value;
	if(!myreg.test(email)){
 		alert("<?php echo $ld['enter_valid_email']?>");
 		return false;
 	}
	var newpass = document.getElementById('user_new_password').value;
	var newpasssec = document.getElementById('user_new_password2').value;
	var id = document.getElementById("id").value;
		if(id==''&& newpass == ''){
		alert("<?php echo $ld['please_fill_user_password']?>");
		return false;
	}
	if( newpass != '' || newpasssec != ''){
		if(newpass == '' || newpasssec == ''){
			alert("<?php echo $ld['please_fill_user_password']?>");
			return false;
		}else if( newpass != newpasssec ){
			alert("<?php echo $ld['password_different']?>");
			return false;
		}else if(id!=''&& newpass == newpasssec){
			if(document.getElementById("user_old_password").value==''){
				alert("<?php echo $ld['old_password_not_empty'];?>");
				return false;
			}
		}
	}
	return true;
}

function operator_change(){
	var name = document.getElementById("name").value;
	if(name!=""){
		var id=document.getElementById('id').value;
        if(id==''){
           	   var id=0;
           }
		$.ajax({
		url: admin_webroot+"operators/act_view/"+id,
		type:"POST",
		data: {"name":name},
		dataType:"json",
		success:function(data){
			try{
                     if(data.code==1){

                     }else{
                          alert("<?php echo $ld['user_exist']?>");
                     }
                }catch(e){
                     alert("<?php echo $ld['object_transform_failed']?>");
                }
		}
		});
	
         
	}
 }
 
 function operator_passname(){
 	 var old_password = document.getElementById("user_old_password").value;
	 if(old_password!=""){
           YUI().use("io",function(Y) {
           var id=document.getElementById('id');
           var user_old_password=document.getElementById('user_old_password');
           var sUrl = admin_webroot+"operators/act_passview/"+id.value;
           var cfg = {
           method: "POST",
           data: "user_old_password="+user_old_password.value
           };
           var request = Y.io(sUrl, cfg);
           var handleSuccess = function(ioId, o){
                try{
                     eval('result='+o.responseText);
                     if(result.code==1){

                     }else{
                        alert("<?php echo $ld['old_password'];?><?php echo $ld['error']?>");
                     }
                }catch(e){
                     alert("<?php echo $ld['object_transform_failed']?>");
                     alert(o.responseText);
                }
           }
           var handleFailure = function(ioId, o){
                alert("<?php echo $ld['asynchronous_request_failed']?>");
           }
           Y.on('io:success', handleSuccess);
           Y.on('io:failure', handleFailure);
      });
	 }
 }
 
 function getOperatorActionByRole(){
 	var operator_role_ids="";
 	var operator_role=document.getElementsByName("operator_role[]");
 	for(i=0,len = operator_role.length; i<len;i++){
		if(operator_role[i].checked){
			operator_role_ids+=operator_role[i].value+";";
		}
	}
	if(operator_role_ids!=""){
		operator_role_ids=operator_role_ids.substring(0,operator_role_ids.length-1);
		$.ajax({ 
			url: "/admin/roles/getOperatorActionByRole",
			type:"POST", 
			data: {operator_role_ids:operator_role_ids},
			dataType:"json",
			success: function(data){
				if(data.code==0){
					alert(data.msg);
				}else if(data.code==1){
					$(".operactor_actions").each(function(){
						$(this).attr("checked",false);
					});
				}else{
					var operator_action_ids=data.msg;
                    var operator_action_ids_arr=operator_action_ids.split(";");
					$(".operactor_actions").each(function(){
						if($.inArray($(this).val(),operator_action_ids_arr)>=0){
							$(this).attr("checked",true);
							$(this).uCheck('check');
							
						}else{
							$(this).attr("checked",false);
							$(this).uCheck('uncheck');
						}
					});
				}
		  	}
		});
	}else{
        $(".operactor_actions").each(function(){
			$(this).attr("checked",false);
			$(this).uCheck('uncheck');
		});
    }
}
</script>