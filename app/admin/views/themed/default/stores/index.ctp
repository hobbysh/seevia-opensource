
<style type="text/css">
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
	.am-panel-title div{font-weight:bold;}
	.am-form-horizontal .am-form-label{padding-top: 0.5em;}
	.am-checkbox {margin-top:0px; margin-bottom:0px;}
	.btnouterlist{overflow: visible;}
	.am-checkbox input[type="checkbox"]{margin-left:0px;}
</style>

<div class=" am-text-right" style="margin-bottom:10px;">
	<?php if($svshow->operator_privilege("stores_add")){?>
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('add/'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a> 
	<?php }?>
</div>
<div id="tablelist">
	<?php echo $form->create('Store',array('action'=>'/','name'=>'StoreForm','type'=>'get',"onsubmit"=>"return false;"));?>
		<div class="am-panel-group am-panel-tree">
			<div class="am-panel am-panel-default am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title">
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
							<label class="am-checkbox am-success" style="font-weight:bold;">
								<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck />
								<?php echo $ld['shop_num']?>
							</label>
						</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['shop_name']?></div>
						<div class="am-u-lg-2 am-u-md-1 am-u-sm-1"><?php echo $ld['contacter']?></div>
						<div class="am-u-lg-2 am-u-md-1 am-u-sm-1"><?php echo $ld['contacter_phone']?></div>
						<div class="am-u-lg-2 am-u-md-1 am-u-sm-1"><?php echo $ld['type']?></div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['sort']?></div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status']?></div>
						<div class="am-u-lg-2 am-u-md-1 am-u-sm-1"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($store_list) && sizeof($store_list)>0){foreach($store_list as $k=>$v){?>
				<div>
					<div class="am-panel am-panel-default am-panel-body">
						<div class="am-panel-bd">
							<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
								<label class="am-checkbox am-success">
									<input type="checkbox" name="checkboxes[]" value="<?php echo $v['Store']['id']?>" data-am-ucheck />
									<?php echo $v['Store']['store_sn']?>
								</label>&nbsp;
							</div>
							<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['StoreI18n']['name'];?>&nbsp;</div>
							<div class="am-u-lg-2 am-u-md-1 am-u-sm-1">
								<?php echo $v['Store']['contact_name'];?><br/><?php echo $v['Store']['contact_email'];?>&nbsp;
							</div>
							<div class="am-u-lg-2 am-u-md-1 am-u-sm-1">
								<?php echo $v['Store']['contact_tele'];?><br/><?php echo $v['Store']['contact_mobile'];?>&nbsp;
							</div>
							<div class="am-u-lg-2 am-u-md-1 am-u-sm-1">
								<?php foreach( $Resource_info["store_type"] as $kk=>$vv ){if($v['Store']['store_type']==$kk){echo $vv;}}?>&nbsp;
							</div>
							<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
								<?php if(count($store_list)==1){echo "-";}elseif($k==0){?>
									<a onclick="changeOrder('down','<?php echo $v['Store']['id'];?>')">&#9660;</a>
								<?php }elseif($k==(count($store_list)-1)){?>
									<a onclick="changeOrder('up','<?php echo $v['Store']['id'];?>')" style="color:#cc0000;">&#9650;</a>
								<?php }else{?>
									<a onclick="changeOrder('up','<?php echo $v['Store']['id'];?>')" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['Store']['id'];?>') ">&#9660;</a>
								<?php }?>&nbsp;
							</div>
							<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
								<?php if($v['Store']['status']==1){?>
									<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'stores/toggle_on_status',<?php echo $v['Store']['id'];?>)"></span>
								<?php }elseif($v['Store']['status'] == 0){?>
									<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'stores/toggle_on_status',<?php echo $v['Store']['id'];?>)"></span>	
								<?php }?>&nbsp;
							</div>
							<div class="am-u-lg-2 am-u-md-1 am-u-sm-1">
								<?php
									if(isset($v['Store']['store_type'])&&$v['Store']['store_type']==0&&!empty($v['Store']['url'])){
										echo $html->link($ld['preview'],"http://".$v['Store']['url'],array('target'=>'_blank','style'=>'text-decoration:underline;color:green;'),false,false).'&nbsp;&nbsp;';
									}
									if(isset($v['Store']['store_type'])&&$v['Store']['store_type']==1){
										echo $html->link($ld['preview'],$server_host.'/shops/'.$v['Store']['id'],array('target'=>'_blank','style'=>'text-decoration:underline;color:green;'),false,false).'&nbsp;&nbsp;';
									}
									if($svshow->operator_privilege("stores_edit")){
									echo $html->link($ld['edit'],"/stores/edit/{$v['Store']['id']}",array("class"=>"am-btn am-radius am-btn-success am-btn-sm ")).'&nbsp;';
									}
									if($svshow->operator_privilege("stores_remove")){
									echo $html->link($ld['remove'],"javascript:;",array("class"=>"am-btn am-btn-danger am-btn-sm am-radius","onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}stores/remove/{$v['Store']['id']}';}"));
									}
								?>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>
			<?php }}else{?>
				<div class="am-text-right" style="margin:50px;"><label><?php echo $ld['no_shops']?></label></div>
			<?php }?>
		</div>
		<?php if(isset($store_list) && sizeof($store_list)){?>
			<div id="btnouterlist" class="btnouterlist">
				<div>
					<label class="am-checkbox am-success" style="display:inline;">
						<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" value="checkbox" data-am-ucheck />
						<?php echo $ld['select_all']?>
					</label>&nbsp;&nbsp;
					<select name="act_type" id="act_type" onchange="operate_change(this)" data-am-selected>
						<option value="0"><?php echo $ld['please_select']?>...</option>
						<option value="delete"><?php echo $ld['delete']?></option>
						<option value="a_status"><?php echo $ld['valid_status']?></option>
					</select>
					<select style="display:none" name="is_yes_no" id="is_yes_no">
						<option value="1"><?php echo $ld['yes']?></option>
						<option value="0"><?php echo $ld['no']?></option>
					</select>&nbsp;&nbsp;
					<input type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="diachange()" value="<?php echo $ld['submit']?>" />
				</div>
				<?php //echo $this->element('pagers')?>
			</div>
		<?php }?>
	<?php echo $form->end();?>
</div>
<script>
function operate_change(obj){
	if(obj.value=="delete"){
		document.getElementById("is_yes_no").style.display="none";
	}
	if(obj.value=="a_status"){
		document.getElementById("is_yes_no").style.display="inline";
	}
	if(obj.value=="0"){
		document.getElementById("is_yes_no").style.display="none";
	}
}
function diachange(){
	var a=document.getElementById("act_type");
	if(a.value!='0'){
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
			if(confirm("<?php echo $ld['submit']?>"+vals+'?'))
			{
				batch_action();
			}
		}else{
			if(confirm(j_please_select))
			{
				batch_action();
			}
		}
	}
}

function batch_action()
{
document.StoreForm.action=admin_webroot+"stores/batch";
document.StoreForm.onsubmit= "";
document.StoreForm.submit();
}
function changeOrder(updown,id){
	$.ajax({
		url:admin_webroot+"stores/changeorder/"+updown+"/"+id,
		type:"POST",
		data:{},
		dataType:"html",
		success:function(data){
			//var node = $('#tablelist');
			var popcontent = document.createElement('div');
			popcontent.innerHTML =data;
			var tmp = $(popcontent).find('#tablelist').html();
			$('#tablelist').html(tmp);
       		$("#tablelist input[type=checkbox]").uCheck();
       		$("#act_type").selected();
		}
	});
	
 /*	YUI().use("io",function(Y) {
		var sUrl = "/admin/stores/changeorder/"+updown+"/"+id;//访问的URL地址
		var cfg = {
				method: 'POST'
		};
		var request = Y.io(sUrl, cfg);//开始请求
		var handleSuccess = function(ioId, o){
			try{
				var node = Y.one('#tablelist');
				var popcontent = document.createElement('div');
				popcontent.innerHTML = o.responseText;
				var tmp = outerHTML(popcontent.getElementsByTagName('table')[0].parentNode);
				node.set('innerHTML',tmp);
			}catch (e){
				alert("<?php echo $ld['object_transform_failed']?>");
				alert(o.responseText);
			}
			inita();
		}
		var handleFailure = function(ioId, o){
			alert("<?php echo $ld['asynchronous_request_failed']?>");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});*/
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