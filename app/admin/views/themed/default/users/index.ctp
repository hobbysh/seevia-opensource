
<style>
	div>a{margin-top:10px;}	
     .mr{margin-right:4px;}
.am-checkbox .am-icon-checked, .am-checkbox .am-icon-unchecked, .am-checkbox-inline .am-icon-checked, .am-checkbox-inline .am-icon-unchecked, .am-radio .am-icon-checked, .am-radio .am-icon-unchecked, .am-radio-inline .am-icon-checked, .am-radio-inline .am-icon-unchecked {
    background-color: transparent;
    display: inline-table;
    left: 0;
    margin: 0;
    position: absolute;
    top: 5px;
    transition: color 0.25s linear 0s;
}
  .top{  
    
    
    top: 0px;
   }
   .ellipsis{
   	margin-top:25px;
   }
</style>
<div class="am-user">
	<div class="am-g">
	  <div class="am-cf">
		<?php echo $form->create('User',array('action'=>'/','name'=>"SearchForm",'id'=>"SearchForm","type"=>"get"));?>
	
	      
	        <div  style="margin:0px 0 10px 0;" class="am-u-lg-3 am-u-md-4 am-u-sm-5">
	        <input type="text" class="am-form-field am-input-sm" name="user_keyword" id="user_keyword" placeholder="<?php echo $ld['user_name'].' / '.$ld['mobile'].' / '.$ld['email'] ?>" value="<?php echo @$user_keyword?>" />
	        </div>
	        <div style="margin:0px 0 10px 0;" class="am-u-lg-4 am-u-md-4 am-u-sm-4">
	              <input  class="am-btn am-btn-success am-btn-sm am-radius" type="submit" onclick="formsubmit()" value="<?php echo $ld['search'];?>" />
	            </div>
	   
		<?php echo $form->end();?>
			
        <div class="am-cf"></div>
		<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
			<?php echo $html->link($ld['user_config_management'],"/user_configs/",array("class"=>"am-btn am-btn-default am-seevia-btn-view")); ?>&nbsp;
			<?php if($svshow->operator_privilege("synchro_apps_view")){echo $html->link($ld['user_management'],"/synchro_apps/",array("class"=>"am-btn am-btn-default am-seevia-btn-view"));} ?>&nbsp;
			<?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['vip'].$ld['set_up'],"/users/config",array('target'=>'_blank',"class"=>"am-btn am-btn-default am-seevia-btn-view"));} ?>&nbsp;
			<?php if($svshow->operator_privilege("user_ranks_view")){echo $html->link($ld['member_level'],"/user_ranks/",array("class"=>"am-btn am-btn-default am-seevia-btn-view"));} ?>&nbsp;
			<?php if($svshow->operator_privilege("user_messages_view")){echo $html->link($ld['station_letter_manage'],"/user_messages/",array("class"=>"am-btn am-btn-default am-seevia-btn-view"));} ?>&nbsp;
			<?php  if($svshow->operator_privilege('users_upload')){echo $html->link($ld['batch_upload_user'],'/users/uploadusers',array("class"=>"am-btn am-btn-default am-seevia-btn-view"),false,false);} ?>&nbsp;
			<a class="am-btn am-btn-warning am-radius am-btn-sm mr"  href="<?php echo $html->url('/users/add'); ?>">
				<span class="am-icon-plus"></span>
				 <?php echo $ld['add'] ?>
		      </a>
	    </div>
	  </div>
	</div>
    <?php echo $form->create('',array('action'=>'/batch_user_print/',"name"=>"UserForm",'onsubmit'=>"return false"));?>
          <table class="am-table  table-main" style="margin-top:30px;">
        	  <thead>
	              <tr>
                    <th class="am-hide-sm-down"><label class="am-checkbox am-success top" style="margin:0px;top:0px;"><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" /><?php echo $ld['avatar']?></label></th>
                    <th ><?php echo $ld['member_name']?><br /><?php echo $ld['name_of_member'];?></th>
                    <th class="am-hide-sm-down" ><?php echo $ld['email']; ?><br /><?php echo $ld['mobile']; ?></th>
                    <th ><?php echo $ld['status']; ?></th>
                    <th class="am-text-left"><?php echo $ld['operate']?></th>
	              </tr>
	          </thead>
	          <tbody>
	    		<?php if(isset($users_list) && sizeof($users_list)>0){foreach($users_list as $k=>$v){?>
	    		  <tr>
		                     <td class="am-hide-sm-down"><label class="am-checkbox am-success" style="margin:0px;"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['User']['id']?>" data-am-ucheck /><img src="<?php echo $v['User']['img01']!=''?$v['User']['img01']:'/theme/AmazeUI/img/no_head.png'; ?>" style="width:100px;height:100px;" /><span><?php echo isset($rank_data[$v['User']['rank']])?$rank_data[$v['User']['rank']]:''; ?></span></label>
		                     </td>
		    		   
		    		  	<td ><div class="ellipsis" title="<?php echo $v['User']['name'].'&#10;'.$v['User']['first_name']; ?>"><?php echo $v['User']['name']; ?><br /><?php echo $v['User']['first_name']; ?>&nbsp;</div>
		    		  	</td>
		    		        <td class="am-hide-sm-down"><div class="ellipsis" title="<?php echo $v['User']['email'].'&#10;'.$v['User']['mobile']; ?>"><?php echo $v['User']['email']; ?><br /><?php echo $v['User']['mobile']; ?>&nbsp;</div>
		    		  	</td>
		    		  		
	                    <td><?php echo isset($Resource_info['verify_status'][$v['User']['verify_status']])?$Resource_info['verify_status'][$v['User']['verify_status']]:$v['User']['verify_status']; ?></div>
		    		  	</td>
		    		  		
		    		  	<td style="min-width:230px;" class="am-action am-btn-group-xs am-text-left">
		    		  	  <?php if($svshow->operator_privilege("users_edit")){?>
		    		  	  <a class="am-btn am-btn-default am-seevia-btn-edit  am-btn-xs am-text-secondary" href="<?php echo $html->url('/users/view/'.$v['User']['id']); ?>" ><span class="am-icon-pencil-square-o"></span>&nbsp;<?php echo $ld['edit']; ?></a>

<?php } if($svshow->operator_privilege("users_remove")){?>
	                          <a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'users/remove/<?php echo $v['User']['id'] ?>');">
	                            <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                          </a>
	                          <?php } if($svshow->operator_privilege("orders_add")&&constant("Product")=="AllInOne"){ ?><a class="am-btn am-btn-default am-btns am-seevia-btn " href="<?php echo $html->url('/orders/add/?user_id='.$v['User']['id']); ?>" ><?php echo $ld['user_place_order']; ?></a><?php } ?>
		    		  	</td>
	    		  		
	    		  </tr> 
	    		<?php }}  else{?> 
	    			 <tr>   
	    		 	<td colspan="5"  style=" text-align:center;height:100px;vertical-align:middle; padding-top:30px; margin-top:-24px;"><?php echo $ld['no_data_found']?>
	    		    </td>
	    		    	  </tr>
	    			<?php }?>	
	    					
	    		</tbody> </table> 
	    						 
          <?php if(isset($users_list) && sizeof($users_list)){?>
          <div id="btnouterlist" class="btnouterlist am-form-group">
               <div class="am-u-lg-7 am-u-md-5  am-hide-sm-down">
			                     <div class="am-fl">
			                        <label class="am-checkbox am-success" style="margin-right:5px; display: inline"><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label>&nbsp;&nbsp;
			                    </div>
		                    <div class="am-fl  am-u-lg-5 am-u-md-7  ">
		                        <select id="select_type" data-am-selected>
		            				<option value="0" selected><?php echo $ld['please_select']?></option>
		            				<?php if($svshow->operator_privilege("users_remove")){?>
		            				<option value="operation_delete"><?php echo $ld['batch_delete']?></option>
		            				<?php }?>
		            				<?php if($svshow->operator_privilege('email_lists_view')){?>
		            				<option value="search_result"><?php echo $ld['search_results_subscribe'] ?></option>
		            				<?php }?>
		            				<option value="export_act"><?php echo $ld['batch_export']?></option>
		            			</select>
		                    </div> 
                    	<div  class=" am-u-lg-3 am-u-md-3  am-u-end"><input type="button" value="<?php echo $ld['submit']?>" class="am-btn am-btn-sm am-btn-danger am-btn-radius"  onclick="submit_operations()" /></div>
               </div>
               <div class="am-u-lg-5 am-u-md-6 am-u-sm-12"><?php echo $this->element('pagers'); ?></div>
               <div class="am-cf"></div>
          </div>
          <?php } ?>
     <?php echo $form->end();?>	
</div>


<div class="am-modal am-modal-no-btn tablemain" tabindex="-1" id="placement" >
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['file_allocation'].' '.$ld['templates']:$ld['file_allocation'].$ld['templates'];?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
	            <form id='placementform3' method="POST" class="am-form am-form-horizontal">
	                <div class="am-form-group">
	                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
	                        <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['choice_export'].' '.$ld['templates']:$ld['choice_export'].$ld['templates'];?>:
	                    </label>
	                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                        <select name="profilegroup" id="profilegroup" data-am-selected>
	                            <option value="0"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['please_select'].' '.$ld['templates']:$ld['please_select'].$ld['templates'];?></option>
	                        </select>&nbsp;&nbsp;&nbsp;&nbsp;<em style="color:red;">*</em>
	                    </div>
	                </div>
	                <div><input type="button" id="mod" class="am-btn am-btn-success am-btn-sm am-radius"  name="changeprofileButton" value="<?php echo $ld['confirm']?>" onclick="javascript:changeprofile();"></div>
	            </form>
        </div>
    </div>
</div>

<style type="text/css">
.am-user{padding:10px 0;}
.am-user .am-table > thead > tr > th,.am-user .am-table > tbody > tr > td{vertical-align:middle;}
.am-user .am-table > thead > tr > th:last-child{width:20%;text-align:right;}
.am-user .am-table > tbody > tr > td:last-child{text-align:right;}
.am-user .am-table > tbody > tr > td:first-child img{width:50px;height:50px;}
.am-user .am-table > tbody > tr > td:first-child span{margin:0 5px;}
.ellipsis{width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
</style>
<script type="text/javascript">
function formsubmit(){
	var user_keyword=document.getElementById('user_keyword').value;
	var url = "user_keyword="+user_keyword;
	window.location.href = encodeURI(admin_webroot+"users?"+url);
}
function change_user_status(type,user_id){
	if(confirm("<?php echo $ld['confirm_verify_the_user'];?>")){
		$.ajax({url: "/admin/users/user_status/"+type+"/"+user_id,
			type:"POST",
			data:{},
			dataType:"json",
			success: function(data){
				try{
					window.location.reload(true); 
					//$("#send_coupon_list").html(data);
				}catch (e){
					alert(j_object_transform_failed);
				}
	  		}
	  	});
  	}
}

function submit_operations(){
    var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var opration_select_type = document.getElementById("select_type").value;
    if(opration_select_type=='0'){
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
	}else if(opration_select_type=="export_act"){
        var func="/profiles/getdropdownlist/";
		var group="User";
        $.ajax({url: admin_webroot+func,
			type:"POST",
			data:{group:group},
			dataType:"json",
			success: function(result){
				try{
					if(result.flag == 1){
    					var result_content = (result.flag == 1) ? result.content : "";
                        if(result_content!=""){
                            strbind(result_content);
                        }
                        $("#placement").modal("open");
    				}
    				if(result.flag == 2){
    					alert(result.content);
    				}
				}catch(e){
					alert(j_object_transform_failed);
					alert(o.responseText);
				}
	  		}
	  	});
    }else if(opration_select_type=='search_result'){
        if(confirm("确定订阅杂志")){
			search_result();
		}
    }else if(opration_select_type=='operation_delete'){
        if(confirm(j_confirm_delete_user)){
            $.ajax({
                url: admin_webroot+"users/batch_operations/",
    			type:"POST",
    			data:postData,
    			dataType:"html",
    			success: function(result){
    				window.location.href = window.location.href;
    	  		}
    	  	});
        }
    }
}

function strbind(arr){
	//先清空下拉中的值
	var profilegroup=document.getElementById("profilegroup");
    $("#profilegroup option").remove();
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
	$("profilegroup").trigger('changed.selected.amui');
}

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
    $("#placement").modal("close");
}
function export_act(code){
	
	document.UserForm.action=admin_webroot+"users/export_act/"+code;
    document.UserForm.onsubmit= "";
    document.UserForm.submit();
}

function search_result(){
	var form=document.getElementById('SearchForm');
	form.action='/admin/users/index/?email_flag=1';
	form.method="post";
	form.submit();
}
</script>