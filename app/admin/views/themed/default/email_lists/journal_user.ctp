<style type="text/css">
	.tablelist .thname span { float: right;cursor: pointer; }
	.tablelist .thname span:hover { color:orange; }
	.tablelist tbody .thname div { display:none; }
	.tablelist table table,
	.tablelist tr tr td,
	.tablelist tr:hover tr td { background:white; }
	.tablelist tr tr:nth-of-type(2n) td { background: #EEE; }
	.tablelist tr tr:hover td { background: #DDD; }
	.tablelist tr.show2table>td { padding: 0;background: white; }
	.tablelist .shownone { display:none; }
	.tablelist .show2table:hover>td {}
	.tablelist tr.show2table th { height: auto;background: #EEE;color: #333; }
	.tablelist tr.show2table td { height: auto;line-height: 1.8; }
	.btnouterlist label{margin-left: -3px;}
	.btnouterlist input{position: relative;bottom: 3px;*position:static;}
</style>
<p class="action-span"><?php  if($svshow->operator_privilege('users_upload')){echo $html->link($ld['batch_upload_user'],'/upload_users/index',array(),false,false);} if($svshow->operator_privilege("users_add")){echo $html->link($ld['add_user'],'/users/add',array("class"=>"addbutton"),false,false);}?></p>
<?php echo $form->create('',array('action'=>'/batch_user_print/',"name"=>"UserForm",'onsubmit'=>"return false"));?>
<div id="tablelist" class="tablelist">
	<table id="t1">
		<thead>
			<tr>
				<th class="thcode"><label><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" /><?php echo $ld['number']."/".$ld['user_reffer'];?></label></th>
				<th class="thname" style="width:200px;"><?php echo $ld['name_of_member'];?></th>
				<th class="thname"><?php echo $ld['member_name']?></th>
				<th><?php echo $ld['email']?></th>
				<th><?php echo $ld['mobile']?></th>
				<th><?php echo $ld['status']?></th>
				<th><?php echo $ld['discount']?></th>
				<th class="thdate"><?php echo $ld['registration_time']?></th>
				<th>杂志订阅会员</th>
				<th><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($users_list) && sizeof($users_list)>0){foreach($users_list as $k=>$v){?>
			<tr>
				<td><label><input type="checkbox" name="checkboxes[]" value="<?php echo $v['User']['id']?>" /><?php echo $v['User']['id'].'<br>';?>
				<?php  if(isset($order_type_arr[$v['User']['type']])){
							if(isset($v['User']['type'])&&$v['User']['type']=='dealer'){
								$type_id_name=isset($dealers_list[$v['User']['user_type_id']])?$dealers_list[$v['User']['user_type_id']]:$v['User']['user_type_id'];
								if($type_id_name=='网站'){
								echo $order_type_arr[$v['User']['type']]."-".$ld['website'];}else
								{
								echo $order_type_arr[$v['User']['type']]."-".$type_id_name;
								}
							}else{
								if($v['User']['type_id']=='网站'){
								echo $order_type_arr[$v['User']['type']]."-".$ld['website'];}else
								{
								echo $order_type_arr[$v['User']['type']]."-".$v['User']['user_type_id'];
								}
							}
						  }else{if(isset($v['User']['type'])&&$v['User']['type']=='dealer'&&isset($order_type['dealer'][$v['User']['user_type_id']])){echo $order_type['dealer'][$v['User']['user_type_id']];}else{echo $v['User']['user_type_id'];}}?>
				</label>
				</td>
				<td>
					<?php if(isset($user_order_infos[$v['User']['id']])){?>
					<span onclick="showorder(this);return false;" ><?php printf($ld["order_num_of_user"],count($user_order_infos[$v['User']['id']]));?>&#0187;</span>
					<div>
					</div>
					<?php }?>
					<?php echo $v['User']['first_name']?>
				</td>
				<td><?php echo $v['User']['name']?></td>
				<td><?php
						if($v['User']['email']==""){
							echo '';
						}else{
							echo "<span style='min-width: 110px;display: inline-block;'>";
							echo $v['User']['email'];
							echo "</span>";
							echo "<span style='padding: 0 3px;color: #666;'>";
							echo ($v['User']['verify_status']==1)?$ld['status_certified']:$ld['status_uncertified'];
							echo "</span>";
						}
				?></td>
				<td><?php echo $v['User']['mobile']?></td>
				<td><?php
					if($v['User']['status']==0){
						echo $ld['invalid'];
					}elseif($v['User']['status']==1){
						echo $ld['valid'];
					}elseif($v['User']['status']==2){
						echo $ld['status_frozen'];
					}elseif($v['User']['status']==3){
						echo $ld['status_logout'];
				}?></td>
				<td><?php echo $v['User']['admin_note2']?></td>
				<td><?php $time=explode(" ",$v['User']['created']);echo $time[0].'<br>'.$time[1];?></td>

				<td><?php if(isset($v['User']['email_flag'])){
							if($v['User']['email_flag']==0){echo '未订阅';}
							if($v['User']['email_flag']==1){echo '订阅中';}
							if($v['User']['email_flag']==2){echo '已订阅';}
					} ?></td>

				<td><?php
					
					if($svshow->operator_privilege("users_edit")){
						echo $html->link($ld['edit'],"/users/{$v['User']['id']}");
					}
					if($svshow->operator_privilege("users_remove")){
						echo $html->link($ld['delete'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete_user']}')){list_delete_submit('{$admin_webroot}users/remove/{$v['User']['id']}');}"));
					}
				?></td>
			</tr>
			<?php }}else{?>
			<tr>
				<td colspan="10" style="text-align:center;height:100px;vertical-align:middle;"><?php echo $ld['no_users']?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>

	<?php if(isset($users_list) && sizeof($users_list)){?>
	<div id="btnouterlist" class="btnouterlist">
		<div>
		    <label><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" /><span><?php echo $ld['select_all']?></span></label>
			<select id="select_type">
				<option value=""><?php echo $ld['please_select']?></option>
				<?php if($svshow->operator_privilege("users_remove")){?>
				<option value="operation_delete"><?php echo $ld['delete']?></option>
				<?php }?>
				<?php if( $svshow->operator_privilege('email_lists_view')){?>
				<option value="search_result">根据搜索结果订阅</option>
				<?php }?>
				<option value="export_act"><?php echo $ld['export']?></option>
			</select>
			<input type="button" value="<?php echo $ld['submit']?>" onclick="submit_operations()" />
		</div>
	
		<!--<div>
			<label><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" /><span><?php echo $ld['select_all']?></span></label>
			<?php //if($svshow->operator_privilege("users_remove")){?>
			<input type="button" value="<?php echo $ld['delete']?>" onclick="batch_operations()" />
			<?php //}?>
		</div>
		-->
		<?php echo $this->element('pagers')?>
	</div>
	<?php }?>
</div>
<?php echo $form->end();?>
<div class="pop tablemain" name="placement" id="placement">
	<h2><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['file_allocation'].' '.$ld['templates']:$ld['file_allocation'].$ld['templates'];?></h2>
	<form id='placementform3' method="POST">
		<table>
			<tr>
				<th rowspan="2"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['choice_export'].' '.$ld['templates']:$ld['choice_export'].$ld['templates'];?>:</th>
			</tr>
			<tr>
				<td>
					<select name="profilegroup" id="profilegroup" >
						<option value="0"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['please_select'].' '.$ld['templates']:$ld['please_select'].$ld['templates'];?></option>
						
					</select><em>*</em>
					
				</td>
			</tr>
		</table>
		<input type="button"  name="changeprofileButton" value="<?php echo $ld['confirm']?>" onclick="javascript:changeprofile();">
						<!--<?php if(isset($group_tree) && sizeof($group_tree)>0){foreach($group_tree as $k=>$v ){?>
						<option value='<?php echo $v?>'<?php if($v==$group){echo "selected";}?>><?php echo 'group'.$v;?></option>
						<?php }}?>-->
	</form>
</div>
<script type="text/javascript">
function submit_operations(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var opration_select_type = document.getElementById("select_type").value;
	if(opration_select_type==''){
		alert(j_select_operation_type+" !");
		return;
	}
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if(opration_select_type=='operation_delete'&&postData=="" ){
		alert(j_select_user);
		return;
	}
	if(opration_select_type=="export_act"&&postData==''){
		alert(j_select_user);
		return false;
	}else{
		var func="/profiles/getdropdownlist/";
		var group="User";
			
			//ajax传值绑定下拉
			YUI().use("io",function(Y){
				//POST数据
				var postData = "group="+group;
				var cfg = {
					method: "POST",
					data: postData
				};
				var sUrl = admin_webroot+func;//访问的URL地址
				var request = Y.io(sUrl, cfg);//开始请求
				var handleSuccess = function(ioId,o){
					try{
						eval('result='+o.responseText);
					}catch(e){
						alert(j_object_transform_failed);
						alert(o.responseText);
					}
					
					if(result.flag == 1){
						var result_content = (result.flag == 1) ? result.content : "";
						strbind(result_content);
						
						//document.getElementById("changeid").value=result.id;
					}
					if(result.flag == 2){
						alert(result.content);
					}
				}
				var handleFailure = function(ioId, o){
					alert("异步请求失败!");
					//obj.innerHTML = org;
				}

				Y.on('io:success', handleSuccess);
				Y.on('io:failure', handleFailure);
			});
			popup('placement');
	}
	if(opration_select_type=='search_result'){
		if(confirm("确定订阅杂志")){
			search_result();
		}
	}else if(opration_select_type=='operation_delete'){
		if(confirm(j_confirm_delete_user)){
			YUI().use("io",function(Y) {
				var sUrl = admin_webroot+"users/batch_operations/";//访问的URL地址
				var cfg = {
					method: "POST",
					data: postData
				};

				var request = Y.io(sUrl, cfg);//开始请求
				var handleSuccess = function(ioId, o){
					window.location.href = window.location.href;
				}

				var handleFailure = function(ioId, o){
					//alert("异步请求失败!");
				}

				Y.on('io:success', handleSuccess);
				Y.on('io:failure', handleFailure);
			});
		}
	}else if(opration_select_type.value=='export_act'){
		
	}
}
function search_result(){
	//document.getElementById('email_flag').value='1';
	var form=document.getElementById('SearchForm');
		form.action='/admin/users/index/?email_flag=1';
//		form.target="_self";
		form.method="post";
		form.submit();
}
//绑定下拉
function strbind(arr){
	//先清空下拉中的值
	//alert(arr.length);
	var profilegroup=document.getElementById("profilegroup");
	//var selectOptions = profilegroup.options; 
	for(var i=0;i <profilegroup.options.length;)  
    {  
       profilegroup.removeChild(profilegroup.options[i]);  
    } 
    var optiondefault=document.createElement("option");
	    profilegroup.appendChild(optiondefault);
	    optiondefault.value="0";
	    optiondefault.text=j_templates;
	for(var i=0;i<arr.length;i++){
		var option=document.createElement("option");
	    profilegroup.appendChild(option);
	    option.value=arr[i]['Profile']['code'];
	    option.text=arr[i]['ProfileI18n']['name'];
	}
	
}
//弹窗
function popup(id){

	if(!document.getElementById("popup")){
		var popcontent=document.createElement('div');
		popcontent.id='popup';
		popcontent.className='popup';
		document.body.appendChild(popcontent);
	}
	var popcontent=document.getElementById("popup");
	if(arguments.length==0){popcontent.style.display="block";return;}
	var idPop=document.getElementById(id);idPop.style.display="block";
	if(arguments.length>=1){
		//alert(btn);
		if(!idPop.getElementsByTagName("span")[0]||idPop.getElementsByTagName("span")[0].className!="closebtn"){
			var popCloseBtn=document.createElement("span");
			popCloseBtn.className="closebtn";
			popCloseBtn.innerHTML="×";
			idPop.insertBefore(popCloseBtn,idPop.firstChild);
		}
	}
	if(document.getElementById(id).parentNode.id!="popup"){
		var tmp=outerHTML(idPop);
		idPop.parentNode.removeChild(idPop);
		popcontent.innerHTML+=tmp;
	}
	if(arguments.length>=1){
		if(document.getElementById(id).firstChild.onclick==null){
			document.getElementById(id).firstChild.onclick=function click(event){
				btnClose1();
			};
		}
	}
    popcontent.style.display="block";		
}
//关闭弹窗
function btnClose1(){
var popcontent=document.getElementById("popup");popcontent.style.display="none";var popdiv=popcontent.firstChild;popdiv.style.display="none";while(popdiv.nextSibling){var popdiv=popcontent.nextSibling;popdiv.style.display="none";}
}
//修改档案分类导出
function changeprofile(){
	var select_type = document.getElementById("select_type");
	

	var code=document.getElementById("profilegroup").value;
	if(code==0){
		alert("请选择导出方式");
		return false;	
	}
	var strsel = select_type.options[select_type.selectedIndex].text;
	if(confirm(confirm_exports+" "+strsel+"？")){
		if(select_type.value=='search_result'){
			search_result(code);
		}else if(select_type.value=='export_act'){
			export_act(code);
		}
	}
	btnClose1();
}
function export_act(code){
	
	document.UserForm.action=admin_webroot+"users/export_act/"+code;
    document.UserForm.onsubmit= "";
    document.UserForm.submit();
}	
</script>
<script>
	function checkbox(){
	var str=document.getElementsByName("box");
	var leng=str.length;
	var chestr="";
	for(i=0;i<leng;i++){
		if(str[i].checked == true)
	  {
	   chestr+=str[i].value+",";
	  };
	};

	return chestr;
//	alert(chestr);
	};
	YUI().use('node', function(Y){
	var all=Y.one('.a1'),
	bll=Y.one('.b1'),
	cll=Y.one('.btn'),
	allclick = function(){
	if(bll.getAttribute("class")!="b1"){bll.removeClass('c1');all.removeClass('up');
	}
	else{bll.addClass('c1');all.addClass('up');}

		//alert(bll.getAttribute("class"));
		//bll.addClass('c1');
	},
	removeclick = function(){
		all.removeClass('up');
		bll.removeClass('c1');
//			var a = checkbox.get('checked',true);
//	alert(a);
	};
	var e = Y.one('.d1'),
		f = Y.one('.f1'),
		btn = Y.one('.btn1'),
	eclick = function(){
		f.addClass('c1');
	},
	eremove = function(){
		f.removeClass('c1');
	};
	var checkbox = Y.all('.b1 .checkbox'),
		navcheck = Y.all('.check1 .checkbox1'),
		navcheck2 = Y.all('.check2 .checkbox1'),
		navcheck3 = Y.all('.check3 .checkbox1'),
		navcheck4 = Y.all('.check4 .checkbox1'),
		select = Y.one('.b1 #select'),
		checkboxControl = function(){
			Y.Array.indexOf(checkbox.get('checked'), false) < 0 ? select.set('checked', true) : select.set('checked', false);
			var onecheckbox = Y.one('.check1 .checkbox');
			var twocheckbox = Y.one('.check2 .checkbox');
			var threecheckbox = Y.one('.check3 .checkbox');
			var fourcheckbox = Y.one('.check4 .checkbox');
			onecheckbox.get('checked') ? navcheck.set('checked', true) : navcheck.set('checked', false);
			twocheckbox.get('checked') ? navcheck2.set('checked', true) : navcheck2.set('checked', false);
			threecheckbox.get('checked') ? navcheck3.set('checked', true) : navcheck3.set('checked', false);
			fourcheckbox.get('checked') ? navcheck4.set('checked', true) : navcheck4.set('checked', false);
		},

		selectControl = function(){
			select.get('checked') ? checkbox.set('checked', true) : checkbox.set('checked', false);
			select.get('checked') ? navcheck.set('checked', true) : navcheck.set('checked', false);
			select.get('checked') ? navcheck2.set('checked', true) : navcheck2.set('checked', false);
			select.get('checked') ? navcheck3.set('checked', true) : navcheck3.set('checked', false);
			select.get('checked') ? navcheck4.set('checked', true) : navcheck4.set('checked', false);
		};
//
//		checkbox.on('click', checkboxControl);
//		select.on('click', selectControl);
//		cll.on('click', removeclick);
//		all.on('click', allclick);
	//	e.on('mouseout', eremove);
	//	e.on('mouseover', eclick);

	});
</script>					