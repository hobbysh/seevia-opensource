<style>
.am-form-label{font-weight:bold;}
.btnouter{margin:50px;}
.am-form-horizontal .am-form-label{padding-top:3px;}
.am-form-horizontal .am-checkbox{padding-top:0;position:relative;top:5px;}
</style>
<div>
	<div class="am-u-lg-2 am-u-md-2 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		   	<li><a href="#interface_edit"><?php echo $ld['interface'].$ld['edit']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
		<?php echo $form->create('SynchroApp',array('action'=>'view','id'=>'form1'));?> 
			<input id="AppId" name="data[UserApp][id]" type="hidden" value="<?php echo isset($syn_apps['UserApp']['id'])?$syn_apps['UserApp']['id']:''?>"/>
			<div id="interface_edit"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title"><?php echo $ld['interface'].$ld['edit']?></h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['interface'].$ld['name']?></label>
			    			<div class="am-u-lg-7 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="sname" name="data[UserApp][name]" value="<?php echo isset($syn_apps['UserApp']['id'])?$syn_apps['UserApp']['name']:''?>" />
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:20px;">
			    					<em style="color:red;">*</em>
			    				</label>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label" style="padding-top:18px;"><?php echo $ld['interface'].$ld['type']?></label>
			    			<div class="am-u-lg-7 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select name="data[UserApp][type]" id='app_type' data-am-selected onChange='type_change()'>
										<option value="0"><?php echo $ld['please_select'];?></option>
										<?php if(isset($user_apps_type_lists)&&sizeof($user_apps_type_lists)>0){foreach($user_apps_type_lists as $k=>$v){ ?>
										<option value="<?php echo $k; ?>" <?php if(isset($syn_apps['UserApp']['type']) && $syn_apps['UserApp']['type'] == $k) echo 'selected'?>><?php echo $v; ?></option>
										<?php }} ?>
									</select>
									<p id='type'></p>					
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:20px;">
			    					<em style="color:red;">*</em>
			    				</label>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo 'app_key'?></label>
			    			<div class="am-u-lg-7 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="app_key" name="data[UserApp][app_key]" value="<?php echo isset($syn_apps['UserApp']['id'])?$syn_apps['UserApp']['app_key']:''?>"/>
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:21px;">
			    					<em style="color:red;">*</em>
			    				</label>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo 'app_secret';?></label>
			    			<div class="am-u-lg-7 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="app_id" name="data[UserApp][app_code]" value="<?php echo isset($syn_apps['UserApp']['id'])?$syn_apps['UserApp']['app_code']:''?>"/>
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:20px;">
			    					<em style="color:red;">*</em>
			    				</label>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label" style="padding-top:16px;"><?php echo $ld['status']?></label>
			    			<div class="am-u-lg-7 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<label class="am-checkbox am-success">
			    						<input type="checkbox" id="status" name="data[UserApp][status]" data-am-ucheck value="1" <?php if(isset($syn_apps['UserApp']['status']) && ($syn_apps['UserApp']['status'] != 0)) echo "checked='checked'" ?> />
			    						<?php echo $ld['enabled']?> 
			    					</label>
			    				</div>
			    			</div>
						</div>
					</div>
					<div class="btnouter">
						<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="check_form()" value=""><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					</div>					
				</div>
			</div>

		<?php $form->end();?>
	</div>
</div>

<script type="text/javascript">
	type_change();
	function check_form(){
		if(!check_null('sname')){
			alert('请填写接口名称');
			return false;
		}
		var c=document.getElementById('app_type').value
		if(c == '0'){
			alert('请选择接口类型');
			return false;
		}
		if(!check_null('app_id')){
			alert('请填写接口app_id');
			return false;
		}
		if(!check_null('app_key')){
			alert('请填写接口app_key');
			return false;
		}
		
		var form=document.getElementById('form1');	
		form.submit();
			
	}	
	function type_change(){
			var c=document.getElementById('app_type').value;
			if(c == 'QQWeibo'){$("#type").html('<a href=\"http://dev.t.qq.com/\" target=\"_blank\">腾讯微博接口申请</a>');}
			else if(c == 'SinaWeibo'){$('#type').html('<a href=\"http://open.weibo.com/authentication/\" target=\"_blank\">新浪微博接口申请</a>')}
			else if(c == 'Facebook'){$('#type').html('')}
			else if(c == 'Google'){$('#type').html('')}
			
	/*	YUI().use("io",function(Y) {
			var c=document.getElementById('app_type').value;
			//alert(c);<br>
			if(c == 'QQWeibo'){Y.one('#type').set('innerHTML', '<a href="http://dev.t.qq.com/" target="_blank">腾讯微博接口申请</a>');}
			else if(c == 'SinaWeibo'){Y.one('#type').set('innerHTML','<a href="http://open.weibo.com/authentication/" target="_blank">新浪微博接口申请</a>')}
			else if(c == 'Facebook'){Y.one('#type').set('innerHTML','')}
			else if(c == 'Google'){Y.one('#type').set('innerHTML','')}
		});*/
	}
	
	function check_null(id){	
		var c=document.getElementById(id).value;
		if(c.replace(/(^\s*)|(\s*$)/g,"")==""){return false;}		
		else{return true;}			
	}

</script>