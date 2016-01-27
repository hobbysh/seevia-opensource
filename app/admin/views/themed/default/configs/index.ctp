<?php 
/*****************************************************************************
 * SV-Cart 商店设置管理列表
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
 
	.am-panel-title div{font-weight:bold;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}	
</style>
<div class="" style="margin-top:10px;">
	<?php echo $form->create('Config',array('action'=>'/','name'=>'PointForm','id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();'));?>
		<div>
			<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1 am-thumbnails">
				<li>
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label am-text-left"  style="font-weight:normal;">
							<?php echo $ld['group']?>:
					</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						<select  id="show_name" data-am-selected>
							<option value="all"><?php echo $ld['all']?></option>
							<?php if(isset($config_group_code) && sizeof($config_group_code)>0){?>
							<?php foreach( $config_group_code as $k=>$v ){?>
								<option value="<?php echo $v['SystemResource']['code']?>" <?php if($v['SystemResource']['code'] == $show_name){echo "selected";}?>><?php echo $v['SystemResourceI18n']['name']?></option>
							<?php }?><?php }?>
						</select>&nbsp;&nbsp;
					</div>
				</li>
				<li>
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-4  am-form-label am-text-left" style="font-weight:normal;">
							html <?php echo $ld['type']?>:
					</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-4 ">
						<select  id="log_type" data-am-selected>
							<option value="all"><?php echo $ld['all']?></option>
							<option value="text" <?php if($log_type == "text"){echo "selected";}?>>text</option>
							<option value="radio"<?php if($log_type == "radio"){echo "selected";}?>>radio</option>
							<option value="select"<?php if($log_type == "select"){echo "selected";}?>>select</option>
							<option value="checkbox"<?php if($log_type == "checkbox"){echo "selected";}?>>checkbox</option>
							<option value="textarea"<?php if($log_type == "textarea"){echo "selected";}?>>textarea</option>
							<option value="image"<?php if($log_type == "image"){echo "selected";}?>>image</option>
							<option value="hidden"<?php if($log_type == "hidden"){echo "selected";}?>>hidden</option>
							<option value="map"<?php if($log_type == "map"){echo "selected";}?>>map</option>
							<option value="send_email_test" <?php if($log_type=="send_email_test"){echo "selected";} ?> >
								send email test</option>
						</select>&nbsp;&nbsp;
					</div>
				</li>
				<li>
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-4  am-form-label am-text-left" style="font-weight:normal;">
							<?php echo $ld['versions']?>:
					</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						<select  id="version" data-am-selected>
							<option value="all"><?php echo $ld['all']?></option>
							<option value="O2O" <?php if($version == "O2O"){echo "selected";}?>>O2O</option>
							<option value="AllInOne"<?php if($version == "AllInOne"){echo "selected";}?>>AllInOne</option>
						</select>&nbsp;&nbsp;
					</div>
				</li>
				<li>
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-4  am-form-label am-text-left" style="font-weight:normal;">
							<?php echo $ld['subparameter'];?>: 
					</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						<input type="text" name="sub_group" id="sub_group" class="am-form-field am-radius am-input-sm" value="<?php echo $sub_group;?>" />
					</div>
				</li>
				<li>
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-4  am-form-label am-text-left" style="font-weight:normal;">
							<?php echo $ld['keyword'];?>: 
					</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						<input type="text" name="config_keywords" id="config_keywords" class="am-form-field am-radius am-input-sm" value="<?php echo $config_keywords;?>"  placeholder="<?php echo $ld['code']?>/<?php echo $ld['name']?>"/>
					</div>
				</li>
				<li>
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-4 ">
						<button type="button" class="am-btn am-btn-success am-btn-sm am-radius search_article"  value="" onclick="search_html()"><?php echo $ld['search'];?></button>
					</div>
				</li>
			</ul>
		</div>
	<?php echo $form->end();?>
	<div class="am-g action-span" style="text-align:right;margin-bottom:10px;">				
		<?php echo $html->link( isset($backend_locale)&&$backend_locale=='eng'?$ld['add'].' '.$ld['shop_configs']:$ld['add'].$ld['shop_configs'],"/configs/view/0",array("class"=>"addbutton am-btn am-btn-warning am-btn-sm am-radius"));?>				
	</div>
	<?php echo $form->create('',array('action'=>'','name'=>'UserForm',"onsubmit"=>"return false","type"=>"get"));?>
		<div class="am-panel-group am-panel-tree">
			<div class="am-panel am-panel-default am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title">
						<div class="am-hide"><?php echo $ld['number']?></div>
						<div class="am-u-lg-2 am-u-md-3 am-hide-sm-only"><?php echo $ld['group']?> / <?php echo $ld['subparameter']?></div>
						<div class="am-u-lg-3 am-show-lg-only"><?php echo $ld['code']?></div>
						<div class="am-u-lg-2 am-u-md-4 am-u-sm-5"><?php echo $ld['name']?></div>
						<div class="am-hide"><?php echo $ld['default_value']?></div>
						<div class="am-hide"><?php echo $ld['versions']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['type']?></div>
						<div class="am-hide"><?php echo $ld['readonly']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-3"><?php echo $ld['status']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['sort']?></div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($configs_list) && sizeof($configs_list)>0){?><?php foreach($configs_list as $k=>$v){ ?>
				<div <?php if((abs($k)+2)%2!=1){?>class="tr_bgcolor"<?php }else{?>class=""<?php }?> >
					<div class="am-panel am-panel-default am-panel-body">
						<div class="am-panel-bd">		
							<div class="am-hide">&nbsp;<?php echo $v['Config']['id'] ?></div>
							<div class="am-u-lg-2 am-u-md-3 am-hide-sm-only">
								&nbsp;<?php echo $v['Config']['group_code'] ?> / <?php echo $v['Config']['subgroup_code'] ?>
							</div>
							<div class="am-u-lg-3 am-show-lg-only">&nbsp;<?php echo $v['Config']['code'] ?></div>
							<div class="am-u-lg-2 am-u-md-4 am-u-sm-5">&nbsp;<?php echo $v['ConfigI18n']['name'] ?></div>
							<div class="am-hide">&nbsp;<?php echo $v['ConfigI18n']['default_value'];?></div>
							<div class="am-hide">&nbsp;<?php echo $v['Config']['section'] ?></div>
							<div class="am-u-lg-1 am-show-lg-only">&nbsp;<?php echo $v['Config']['type'] ?></div>
							<div class="am-hide">
								<?php if ($v['Config']['readonly'] == 1){?>
								<!--<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "configs/toggle_on_readonly", '.$v["Config"]["id"].')')) ?>&nbsp;-->
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'configs/toggle_on_readonly',<?php echo $v['Config']['id'];?>)"></span>
								<?php }elseif($v['Config']['readonly'] == 0){?>
								<!--<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "configs/toggle_on_readonly", '.$v["Config"]["id"].')'))?>&nbsp;-->
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'configs/toggle_on_readonly',<?php echo $v['Config']['id'];?>)">&nbsp;</span>
								<?php }?>	
							</div>
							<div class="am-u-lg-1 am-u-md-2 am-u-sm-3">
								<?php if( $v['Config']['status'] == 1){ ?>
								&nbsp;
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'configs/toggle_on_status',<?php echo $v['Config']['id'];?>)"></span>
								
								<?php }elseif($v['Config']['status'] == 0){ ?> 
										<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'configs/toggle_on_status',<?php echo $v['Config']['id'];?>)">&nbsp;</span>
								<?php }?>
							</div>
							<div class="am-u-lg-1 am-show-lg-only">
								<span onclick="javascript:listTable.edit(this, 'configs/update_config_orderby/', <?php echo $v['Config']['id']?>)"><?php echo $v['Config']['orderby'] ?></span>
							</div>
							<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
								<?php echo $html->link($ld['edit'],"/configs/view/{$v['Config']['id']}",array("class"=>"am-btn am-btn-success am-btn-sm am-radius"));?>&nbsp;
								<?php echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-danger am-btn-sm am-radius","onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}configs/remove/{$v['Config']['id']}';}"));?>&nbsp;
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>	
			<?php }} ?>
			<div class="btnouterlist" style="position:relative">
				<?php echo $this->element('pagers', array('cache'=>'+0 hour'));?>
			</div>
		</div>
	<?php echo $form->end();?>
</div>




<style>
.tablelist td{text-overflow: ellipsis;}
</style>
<?php
function utf8Substr($str, $from, $len){
	return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF] ){0,'.$from.'}'.
	'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF] ){0,'.$len.'}).*#s',
	'$1',$str);
}
?>
<script type="text/javascript">
var label_obj="";
function update_orderby(obj,config_id){
	if(!isNaN(obj.innerHTML-0)){
		var orderby_num = obj.innerHTML;
		label_obj = obj;
		obj.innerHTML = "<input type='text' size='2' onBlur='update_orderby_out(this,"+config_id+")' value="+orderby_num+" >";
	}

}
function update_orderby_out(input_obj,config_id){
	label_obj.innerHTML = input_obj.value;
	var sUrl = webroot_dir+"configs/update_orderby/"+input_obj.value+"/"+config_id;
	var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, "");
}


function update_color(obj){
	obj.style.background="#21964D";
}
function update_color_out(obj){
	obj.style.background="#FFFFFF";

}
function search_html(){
	var log_type = document.getElementById('log_type').value; 
	var show_name = document.getElementById('show_name').value;
	var version = document.getElementById('version').value; 
	var sub_group = document.getElementById('sub_group').value;
	var config_keywords = document.getElementById('config_keywords').value;
	var url="show_name="+show_name+"&log_type="+log_type+"&version="+version+"&sub_group="+sub_group+"&config_keywords="+config_keywords;
	window.location.href = encodeURI(admin_webroot+"configs?"+url);
} 
function batch_action() 
{ 
document.UserForm.action=webroot_dir+"configs/batch"; 
document.UserForm.onsubmit=""; 
document.UserForm.submit(); 
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