<style type="text/css">
.am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
.am-checkbox input[type="checkbox"]{margin-left:0;}
 
.am-panel-title{font-weight:bold;}
.am-yes{color:#5eb95e;}
.am-no{color:#dd514c;}
.am-flash-checkbox .am-ucheck-icons{margin-top:15px;}
.am-checkbox .am-icon-checked, .am-checkbox .am-icon-unchecked, .am-checkbox-inline .am-icon-checked, .am-checkbox-inline .am-icon-unchecked, .am-radio .am-icon-checked, .am-radio .am-icon-unchecked, .am-radio-inline .am-icon-checked, .am-radio-inline .am-icon-unchecked {
    background-color: transparent;
    display: inline-table;
    left: 0;
    margin: 0;
    position: absolute;
    top: 5px;
    transition: color 0.25s linear 0s;
}
 
</style>
<div>
	<div style="margin-left:10px;">
		<?php echo $form->create('flashs',array('action'=>'/','name'=>"SeearchForm","class"=>"am-form am-form-horizontal","type"=>"get",'onsubmit'=>'return search_article();'));?>
	
			<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
				<!-----类型----->
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['type'];?></label>
					<div class="am-u-lg-9 am-u-md-8 am-u-sm-7">
						<select name="type" data-am-selected>
							<option value="0" <?php echo !isset($flash_type)||(isset($flash_type)&&$flash_type=='0')?'selected':''; ?>><?php echo $ld['computer'];?></option>
							<option value="1" <?php echo isset($flash_type)&&$flash_type=='1'?'selected':''; ?>><?php echo $ld['mobile'];?></option>
						</select>
					</div>
				</li>
				<!------页面---->
				
				<li style="margin-bottom:10px;">
				 
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4  am-form-label" ><?php echo $ld['page'].$ld['type'];?></label>
					<div class="am-u-lg-5 am-u-md-5 am-u-sm-4 am-u-end">
						<select name="flashPage" id='flashPage' onchange='typechange();' data-am-selected >
							<?php if(isset($Resource_info["flashtypes"]) && sizeof($Resource_info["flashtypes"])>0){foreach($Resource_info["flashtypes"] as $k => $v){
							?> <option value="<?php echo $k;?>" <?php echo isset($flashPage)&&$flashPage==$k?"selected":''; ?>><?php echo $v;?></option> <?php }}?> <?php if(isset($destination_infos) && sizeof($destination_infos)>0){?> <option value="D" <?php echo isset($flashPage)&&$flashPage=='D'?"selected":''; ?>><?php echo '目的地';?></option>
							<?php }?>
						</select>
					</div>
				     
				  <!----页面关联框-->
					     <div  class="  am-u-lg-3 am-u-md-3 am-u-sm-3 am-u-end" id="selects"> </div>
					       </li>
			        <!-----轮播----->
			         <li style="margin-bottom:10px;">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['circle_language'];?></label>
					<div class="am-u-lg-7 am-u-md-8 am-u-sm-7">
						<select name="language" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
							<option value=''   ><?php echo $ld['all_data']?></option>
							<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach($backend_locales as $k => $v){?>
							<option value="<?php echo $v['Language']['locale'];?>" <?php if($v['Language']['locale']==$language) { } ?>><?php echo $v['Language']['name'];?></option>
							<?php }}?>
						</select>	
					</div> 
				</li>
					<!------搜索---->		
					<li style="margin-bottom:10px;" class="am-show-md-only">
					<div class=" am-u-lg-8 am-u-md-8 am-u-sm-8">
					<label class="am-u-lg-5 am-u-md-5 am-u-sm-6  "></label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-6 am-u-end">
					 	<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>" >
						  <?php echo $ld['search'];?> </button> </div> </div>	
				      	  <div  class="  am-u-lg-4 am-u-md-4 am-u-sm-3 am-u-end" id="selects"> 
					</div>
					</li>
				   <li style="margin-bottom:10px;" class="am-hide-md-only">
				 	<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-form-label"></label>
					<div class="am-u-lg-8 am-u-md-6 am-u-sm-7">
						 	<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>" >
						 	<?php echo $ld['search'];?> </button>
					</div>
					 <div class="am-u-lg-1 am-u-md-2">
					  </div>
				 </li>
			</ul>
							
		<?php echo $form->end()?>	
	</div>
	<div class="am-text-right" style="margin-bottom:10px;">
		<?php if($svshow->operator_privilege("flashs_add")){?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius " href="<?php echo $html->url('javascript:doflash()'); ?>" onclick="doflash()">
				<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
			</a>
		<?php }?>
	</div>
	<div id="tablelist" class="">
	<div class="am-panel-group am-panel-tree"  id="accordion">
		<div class="listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title am-g">
					<div class="am-u-lg-3 am-u-md-2 am-u-sm-4">	
		
						<label class="am-checkbox am-success  am-hide-sm-only" style="font-weight:bold;">
							<input type="checkbox" name="checkbox" data-am-ucheck value="checkbox" onclick='listTable.selectAll(this,"checkboxes[]")'/> <?php echo $ld['picture']?>
						</label>
		                <label class=" am-show-sm-only" style="font-weight:bold;">
						<?php echo $ld['picture']?>
						</label>
					</div>
					<div   class="am-u-lg-2  am-u-md-2 am-u-sm-2"><?php echo $ld['type']?></div>
					<div class="am-u-lg-2  am-u-md-2 am-u-sm-2"><?php echo $ld['page']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-hide-md-down"><?php echo $ld['language']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-hide-sm-only"><?php echo $ld['valid']?></div>
					<div class="am-u-lg-1  am-u-md-2  am-u-sm-2 am-hide-sm-only"><?php echo $ld['sort']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-4"><?php echo $ld['operate']?></div>
					<div style="clear:both"></div>
				</div>	
			</div>
		</div>
		<?php if(isset($flash_image_data) && sizeof($flash_image_data)>0){foreach($flash_image_data as $k=>$v){?>
			<div>
				<div class=" listtable_div_table am-panel-body  ">
					<div class="am-panel-bd am-g">
						<div class="am-u-lg-3 am-u-md-2 am-u-sm-4  ">
                            <label class="am-checkbox am-success am-flash-checkbox am-hide-sm-only">
                                <input type="checkbox" name="checkboxes[]" value="<?php echo $v['FlashImage']['id']?>" data-am-ucheck />
                              <?php if($v['FlashImage']['image']){?>
                                <img src="<?php echo $v['FlashImage']['image']?>"style="height:50px;width:80px;padding:8px 0 0"  > 
                                <?php }?> 
                            </label>
						  	<label class="am-show-sm-only" >
								 <?php if($v['FlashImage']['image']){?>
								<img src="<?php echo $v['FlashImage']['image']?>" style="height:50px;width:80px;padding:8px 0 0" >
							<?php }?>
							</label>
						</div>
							<div class="am-u-lg-2  am-u-md-2 am-u-sm-2" style="margin-top:17px;">
							<?php echo $flash_info['Flashe']['type']=='0'?$ld['computer']:$ld['mobile']; ?>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="margin-top:17px;">
							<?php echo @$Resource_info["flashtypes"][$flash_info["Flashe"]["page"]];?>
						</div>
						<div class="am-u-lg-1  am-u-md-2 am-u-sm-2  am-hide-md-down" style="margin-top:17px;">
							<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach($backend_locales as $vv){ if($vv['Language']['locale']==$v['FlashImage']['locale']){echo $vv['Language']['name'];} }}?>
						</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2  am-hide-sm-only" style="margin-top:17px;">
							<?php if ($v['FlashImage']['status'] == 1){?>
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'flashs/toggle_on_status',<?php echo $v['FlashImage']['id'];?>)"></span>
							<?php }elseif($v['FlashImage']['status'] == 0){?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'flashs/toggle_on_status',<?php echo $v['FlashImage']['id'];?>)">&nbsp;</span>										
							<?php }?>
						</div>
						<div class="am-u-lg-1  am-u-md-2 am-u-sm-2  am-hide-sm-only"  style="margin-top:17px;">
							<?php if(count($flash_image_data)==1){echo "-";}elseif($k==0){?>
								<a onclick="changeOrder('down','<?php echo $v['FlashImage']['id'];?>','0',this)" style="cursor:pointer;">&#9660;</a>
							<?php }elseif($k==(count($flash_image_data)-1)){?>
								<a onclick="changeOrder('up','<?php echo $v['FlashImage']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer">&#9650;</a>
							<?php }else{?>
								<a onclick="changeOrder('up','<?php echo $v['FlashImage']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['FlashImage']['id'];?>','0',this) " style="cursor:pointer;">&#9660;</a>
							<?php }?>
						</div>
					    
					 <div class="am-u-lg-5 am-u-md-2 am-u-sm-4" style="max-width:150px; margin-top:12px"> 
				 
						 
							<?php if($svshow->operator_privilege("flashs_edit")){?>
						 	<a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/flashs/view/'.$v['FlashImage']['id']); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a>
							<?php }
								if($svshow->operator_privilege("flashs_remove")){?>
								   <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'flashs/remove/<?php echo $v['FlashImage']['id'] ?>');"><span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?></a> <?php 	}?>
						</div>
						<div  class="am-cf"></div>
					</div>
				</div>
			</div>
		<?php }}else{?>
			<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
		<?php }?>
	</div>
		<?php if(isset($flash_image_data) && sizeof($flash_image_data)){?>
		<div id="btnouterlist" class="btnouterlist">
				<div class="am-u-lg-3 am-u-md-5 am-u-sm-12 am-hide-sm-only" style="margin-left:9px;">
					<label class="am-checkbox am-success" >
					<input type="checkbox" name="checkbox" value="checkbox" onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck/>
						<?php echo $ld['select_all']?>
					</label>&nbsp;&nbsp;  
				<input type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="diachange()" value="<?php echo $ld['batch_delete']?>">
				</div>
				<div class="am-u-lg-8 am-u-md-6 am-u-sm-12">
					<?php echo $this->element('pagers')?>
				</div>
	        	<div class="am-cf"></div>
		</div>
			<?php }?>		
	</div>				
</div>
<span style="display:none"><span id="select1">
<?php if(isset($category_tree_p) && sizeof($category_tree_p)>0){?>
<select name="page_type_id">
	<?php if(isset($category_tree_p) && sizeof($category_tree_p)>0){?>
	<?php foreach($category_tree_p as $first_k=>$first_v){?>
	<option value="<?php echo $first_v['CategoryProduct']['id'];?>" <?php if($page_type_id == $first_v['CategoryProduct']['id']){?>selected<?php }?>><?php echo $first_v['CategoryProductI18n']['name'];?></option>
	<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
	<?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
	<option value="<?php echo $second_v['CategoryProduct']['id'];?>" <?php if($page_type_id == $second_v['CategoryProduct']['id']){?>selected<?php }?>>&nbsp;&nbsp;<?php echo $second_v['CategoryProductI18n']['name'];?></option>
	<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
	<?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
	<option value="<?php echo $third_v['CategoryProduct']['id'];?>" <?php if($page_type_id == $third_v['CategoryProduct']['id']){?>selected<?php }?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryProductI18n']['name'];?></option>
	<?php }}}}}}?>
</select>
<?php }?>
</span><span id="select2">
<?php if(isset($category_tree_a) && sizeof($category_tree_a)>0){?>
<select name="page_type_id">
	<?php if(isset($category_tree_a) && sizeof($category_tree_a)>0){?>
	<?php foreach($category_tree_a as $first_k=>$first_v){?>
	<option value="<?php echo @$first_v['CategoryArticle']['id'];?>" <?php if(isset($first_v['CategoryArticle']['id']) && $page_type_id == $first_v['CategoryArticle']['id']){?>selected<?php }?>><?php echo @$first_v['CategoryArticleI18n']['name'];?></option>
	<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
	<?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
	<option value="<?php echo $second_v['CategoryArticle']['id'];?>" <?php if($page_type_id == $second_v['CategoryArticle']['id']){?>selected<?php }?>>&nbsp;&nbsp;<?php echo $second_v['CategoryArticleI18n']['name'];?></option>
	<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
	<?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
	<option value="<?php echo $third_v['CategoryArticle']['id'];?>" <?php if($page_type_id == $third_v['CategoryArticle']['id']){?>selected<?php }?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryArticleI18n']['name'];?></option>
	<?php }}}}}}?>
</select>
<?php }?>
</span><span id="select3">
<?php if(isset($brand_tree) && sizeof($brand_tree)>0){?>
<select name="page_type_id">
	<?php if(isset($brand_tree) && sizeof($brand_tree)>0){foreach($brand_tree as $k => $v){?>
	<option value="<?php echo $v['Brand']['id'];?>" <?php if($v['Brand']['id']==$page_type_id)echo "selected";?> ><?php echo $v['BrandI18n']['name'];?></option>
	<?php }}?>
</select>
<?php }?>
</span>
<span id="select4">
<?php if(isset($destination_infos) && sizeof($destination_infos)>0){?>
<select name="page_type_id">
	<?php if(isset($destination_infos) && sizeof($destination_infos)>0){foreach($destination_infos as $k => $v){ ?>
		<option value="<?php echo $k;?>" <?php if($k==$page_type_id)echo "selected";?> ><?php echo $v;?></option>
	<?php }}?>
</select>
<?php }?>
</span>
<span id="select5">
<?php if(isset($custom_tree) && sizeof($custom_tree)>0){?>
<select name="page_type_id" id="page_type_id">
	<?php if(isset($custom_tree) && sizeof($custom_tree)>0){foreach($custom_tree as $k => $m){?>
		<option value="<?php echo $k;?>" <?php if($k==$flash_info['Flashe']['type_id'])echo "selected";?> ><?php echo $m;?></option>
	<?php }}?>
</select>
<?php }?>
</span>	</span>
<script type="text/javascript">
function search_article(){
	var flashsForm=document.getElementById("flashs/Form");
	var type=document.getElementsByName("type")[0].value;
	var page=document.getElementById("flashPage").value;
	var page_id;
	if(document.getElementById("selects").innerHTML!=""){
		type_id=document.getElementsByName("page_type_id")[0].value;
	}else{
		type_id="";
	}
	flashsForm.action+="?type="+type+"&page"+page+"&page_type_id="+type_id;
	return true;
}
function typechange(){
	//alert("TNT");
	//获取 id 
	var	selects = document.getElementById('selects');
	var	select1 = document.getElementById('select1');
	var	select2 = document.getElementById('select2');
	var select3 = document.getElementById('select3');
	var select4 = document.getElementById('select4');
	var select5 = document.getElementById('select5');
	var type = document.getElementById('flashPage');
	 
	//switch 指定id 的值 判断赋值
	switch(type.value){
		case 'PC':selects.innerHTML = select1.innerHTML; break;
		case 'AC':selects.innerHTML = select2.innerHTML;break;
		case 'B':selects.innerHTML = select3.innerHTML;break;
		case 'D':selects.innerHTML = select4.innerHTML;break;
		case 'M':selects.innerHTML = select5.innerHTML;break;
		default:selects.innerHTML = "";
	}
  
     //指定id 转换需要的样式的样式
     $("#selects select").selected({maxHeight:250});
}
  //页面加载调用该方法
typechange();
function doflash(){
	var type=document.getElementsByName("type")[0].value;
	var page=document.getElementById('flashPage').value;
	window.location=admin_webroot+'flashs/view/?type='+type+"&page="+page;
}
function changeOrder(updown,id,next,thisbtn){
	$.ajax({
		url:"/admin/flashs/changeorder/"+updown+"/"+id,
		type:"POST",
		data:{},
		dataType:"html",
		cache: false,
		success:function(data){
			$("#accordion").html(data);
			$("#tablelist input[type=checkbox]").uCheck();
		}
	});
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


function diachange(){  
   var bratch_operat_check = document.getElementsByName("checkboxes[]");
		var postData = "";
			   		for(var i=0;i<bratch_operat_check.length;i++){
		     if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
	  	     }
 		}
	
		if( postData=="" ){
		alert("<?php echo "请选择"?>");
		return;
   	    }
	if(confirm("<?php echo '确定删除吗？' ?>")){
   	    		$.ajax({
   	    			type:"POST",
   	    		       url:admin_webroot+"flashs/batch_operations/",
   	    			data:postData,
   	    		      datatype: "json",
   	    			success:function(data){
				window.location.href = window.location.href;
		  		}
   	    		});
   	    	
   	    	
   	    	}
   	    	
	
	
}
</script>
