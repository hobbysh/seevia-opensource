<style>
 .am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
 .am-form-label{font-weight:bold;}
 .am-form-horizontal .am-form-label {
    text-align: left;
    margin-top:4px;
}
 
 
 .am-radio input[type="radio"]{margin-left:0px;padding-top:2px;}
  .am-dropdown{margin-top:10px;} 
 </style>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index:100; width: 15%;max-width:200px;">
		    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion" >
		<?php echo  $form->create('flashs',array('action'=>'view/'.(isset($flash_image_info['FlashImage'])?$flash_image_info['FlashImage']['id']:''),'name'=>"FlashForm","type"=>"POST","onsubmit"=>"return checkform();"));?>
			<input name="data[FlashImage][id]" type="hidden" value="<?php echo isset($flash_image_info['FlashImage']['id'])?$flash_image_info['FlashImage']['id']:'';?>">
			<div id="basic_information" class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information'] ?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
		      				
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['select_language']?></label>
			    		             <div class="am-u-lg-7 am-u-md-7 am-u-sm-7 ">
			    					<select name="data[FlashImage][locale]" data-am-selected>
										<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach($backend_locales as $k => $v){?>
										<option value="<?php echo $v['Language']['locale'];?>"><?php echo $v['Language']['name'];?></option>
										<?php }}?>
									</select>
			    				</div>
			    		    </div>	
					 		<div class="am-form-group ">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['type']?></label>
			    		             <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
			    					<select name="data[Flash][type]" data-am-selected>
										<option value="0" <?php echo  (isset($flashType)&&$flashType=='0')||(isset($flash_info['Flashe']['type'])&&$flash_info['Flashe']['type']==0)?'selected':''; ?>><?php echo $ld['computer'];?></option>
										<option value="1" <?php echo (isset($flashType)&&$flashType=='1')||(isset($flash_info['Flashe']['type'])&&$flash_info['Flashe']['type']==1)?'selected':''; ?>><?php echo $ld['mobile'];?></option>
									</select>
			    				</div>
			    		    	</div>	
						<div class="am-form-group ">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['page'];?></label>
				    			<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
				    			      <select name="data[Flash][page]" id='flashPage' onchange='typechange();'  data-am-selected>
									<?php if(isset($Resource_info["flashtypes"]) && sizeof($Resource_info["flashtypes"])>0){foreach($Resource_info["flashtypes"] as $k => $v){
									?>
									<option value="<?php echo $k;?>" <?php if((!empty($flash_info['Flashe']['page'])&&$k==$flash_info['Flashe']['page'])||(isset($page)&&$k==$page)){echo "selected";}?>><?php echo $v;?></option>
									<?php }}?>
									<?php if(isset($destination_infos) && sizeof($destination_infos)>0){?>
										<option value="D" <?php if((!empty($flash_info['Flashe']['page'])&&'D'==$flash_info['Flashe']['page'])||(isset($page)&&'D'==$page)){echo "selected";}?>><?php echo '目的地';?></option>
									<?php }?>
										</select>
								 <span  id="selects" class="am-u-lg-12 am-u-md-12 am-u-sm-12"></span>
				    			  </div>
						</div>	
								
						<div class="am-form-group"  >
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['title']?></label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7"><div>
							        <input type="text" name="data[FlashImage][title]" value="<?php echo isset($flash_image_info['FlashImage'])?$flash_image_info['FlashImage']['title']:'';?>" />
                                               </div></div> 
						</div>	
					  <div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['url_address']?></label>
			    			<div class="am-u-lg-7 am-u-md-7 am-u-sm-7"><div>
			    			  <input type="text" name="data[FlashImage][url]" value="<?php echo isset($flash_image_info['FlashImage'])?$flash_image_info['FlashImage']['url']:'';?>" /> <?php echo $ld['page_url_desc']; ?>
			    			  </div></div>
			    		</div>	
                                     <div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['picture_show']?></label>
				    			<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
	   							<div>
					    			   <input type="text" name="data[FlashImage][image]" id="flash_image" value="<?php echo isset($flash_image_info['FlashImage'])?$flash_image_info['FlashImage']['image']:'';?>" />
			    					</div>
								<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="select_img('flash_image')" value="<?php echo $ld['choose_picture']?>" style="margin-top:10px;margin-bottom:5px;"><?php echo $ld['choose_picture'];?></button>
									<div class="img_select">
										<?php echo $html->image((isset($flash_image_info['FlashImage']['image'])&&$flash_image_info['FlashImage']['image']!="")?$flash_image_info['FlashImage']['image']:$configs['shop_default_img'],array('id'=>'show_flash_image','onclick'=>"showbigimg(this)",'style'=>"cursor:pointer;"))?>
									</div>
					    			  
				    			</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['description']?></label>
			    			        <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<div>
								<textarea name="data[FlashImage][description]" style="height:150px;"><?php echo isset($flash_image_info['FlashImage'])?$flash_image_info['FlashImage']['description']:'';?></textarea>
								</div>
							</div>
			    			 
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['valid']?></label>
			    			<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<div>
							<label class="am-radio am-success">
							<input type="radio" name="data[FlashImage][status]" data-am-ucheck value="1" <?php echo !isset($flash_image_info['FlashImage']['status'])||(isset($flash_image_info['FlashImage']['status'])&&$flash_image_info['FlashImage']['status']==1)?"checked":"";?> ><?php echo $ld['yes']?>
							</label>&nbsp;&nbsp;
							<label class="am-radio am-success">
							<input name="data[FlashImage][status]" type="radio"  data-am-ucheck value="0" <?php echo isset($flash_image_info['FlashImage']['status'])&&$flash_image_info['FlashImage']['status']==0?"checked":"";?> ><?php echo $ld['no']?>
							</label>
			    			 </div>
			    			</div>
						</div>		
					</div>
							 
			    			<div  class="btnouter">				
				 
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" onclick="clear_flashInfo()" value="" ><?php echo $ld['d_reset']?></button>
					</div> 
				</div>
			</div>
		<?php echo $form->end();?> 
	</div>
</div>


<span style="display:none"> <span id="select1">
<?php if(isset($category_tree_p) && sizeof($category_tree_p)>0){?>
<select name="data[Flash][page_id]">
	<?php if(isset($category_tree_p) && sizeof($category_tree_p)>0){?>
	<?php foreach($category_tree_p as $first_k=>$first_v){?>
	<option value="<?php echo $first_v['CategoryProduct']['id'];?>" <?php if($flash_info['Flashe']['page_id'] == $first_v['CategoryProduct']['id']){?>selected<?php }?>><?php echo $first_v['CategoryProductI18n']['name'];?></option>
	<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
	<?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
	<option value="<?php echo $second_v['CategoryProduct']['id'];?>" <?php if($flash_info['Flashe']['page_id'] == $second_v['CategoryProduct']['id']){?>selected<?php }?>>&nbsp;&nbsp;<?php echo $second_v['CategoryProductI18n']['name'];?></option>
	<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
	<?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
	<option value="<?php echo $third_v['CategoryProduct']['id'];?>" <?php if($flash_info['Flashe']['page_id'] == $third_v['CategoryProduct']['id']){?>selected<?php }?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryProductI18n']['name'];?></option>
	<?php }}}}}}?>
</select>
<?php }?>
</span> <span id="select2">
<?php if(isset($category_tree_a) && sizeof($category_tree_a)>0){?>
<select name="data[Flash][page_id]" >
	<?php if(isset($category_tree_a) && sizeof($category_tree_a)>0){?>
	<?php foreach($category_tree_a as $first_k=>$first_v){?>
	<option value="<?php echo $first_v['CategoryArticle']['id'];?>" <?php if($flash_info['Flashe']['page_id'] == $first_v['CategoryArticle']['id']){?>selected<?php }?>><?php echo $first_v['CategoryArticleI18n']['name'];?></option>
	<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
	<?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
	<option value="<?php echo $second_v['CategoryArticle']['id'];?>" <?php if($flash_info['Flashe']['page_id'] == $second_v['CategoryArticle']['id']){?>selected<?php }?>>&nbsp;&nbsp;<?php echo $second_v['CategoryArticleI18n']['name'];?></option>
	<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
	<?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
	<option value="<?php echo $third_v['CategoryArticle']['id'];?>" <?php if($flash_info['Flashe']['page_id'] == $third_v['CategoryArticle']['id']){?>selected<?php }?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryArticleI18n']['name'];?></option>
	<?php }}}}}}?>
</select>
<?php }?>
</span> <span id="select3">
<?php if(isset($brand_tree) && sizeof($brand_tree)>0){?>
<select name="data[Flash][page_id]" >
	<?php foreach($brand_tree as $k => $v){?>
	<option value="<?php echo $v['Brand']['id'];?>" <?php if($v['Brand']['id']==$flash_info['Flashe']['page_id'])echo "selected";?> ><?php echo $v['BrandI18n']['name'];?></option>
	<?php }?>
</select>
<?php }?>
</span>
<span id="select4">
<?php if(isset($destination_infos) && sizeof($destination_infos)>0){?>
<select name="data[Flash][page_id]" >
	<?php foreach($destination_infos as $k => $v){ ?>
		<option value="<?php echo $k;?>" <?php if($k==$flash_info['Flashe']['page_id'])echo "selected";?> ><?php echo $v;?></option>
	<?php }?>
</select>
<?php }?>
</span>
<span id="select5">
<select name="data[Flash][page_id]" id="flash_custom_type" >
	<option value=""><?php echo $ld['please_select']?></option>
	<?php if(isset($custom_tree) && sizeof($custom_tree)>0){foreach($custom_tree as $k => $m){?>
		<option value="<?php echo $k;?>" <?php if($k==$flash_info['Flashe']['page_id'])echo "selected";?> ><?php echo $m;?></option>
	<?php }}?>
</select>
<input type="button" onclick="searchInforationresources('flash_custom_type')" value="<?php echo $ld['region_view']?>" />
</span>
</span>
<div id="public_dialog" class="pop tablemain">

</div>
<script type="text/javascript">
function typechange(){
	var	selects = document.getElementById('selects');
	var	select1 = document.getElementById('select1');
	var	select2 = document.getElementById('select2');
	var select3 = document.getElementById('select3');
	var select4 = document.getElementById('select4');
	var select5 = document.getElementById('select5');
	var type = document.getElementById('flashPage');
	switch(type.value){
		case 'PC':selects.innerHTML = select1.innerHTML;break;
		case 'AC':selects.innerHTML = select2.innerHTML;break;
		case 'B':selects.innerHTML = select3.innerHTML;break;
		case 'D':selects.innerHTML = select4.innerHTML;break;
		case 'M':selects.innerHTML = select5.innerHTML;break;
		default:selects.innerHTML = "";
	}
	$("#selects select").selected({maxHeight:250});
}
typechange();


	//查询资源表数据
	function searchInforationresources(code){
		if(code==""){
			return;
		}
		YUI().use('io',function(Y){
			var sUrl = "/admin/information_resources/searchInforationresources/";//访问的URL地址
			var postData = "code="+code;
			var cfg = {
					method: 'POST',
					data: postData
			};
			var request = Y.io(sUrl, cfg);//开始请求
			var handleSuccess = function(ioId, o){
				try{
					Y.one('#public_dialog').set('innerHTML','');
					Y.one('#public_dialog').append(o.responseText);
					popOpen('public_dialog');
				}catch (e){
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
	//删除资源表数据
	function removeInforationresources(id){
		YUI().use('io',function(Y){
			var sUrl = "/admin/information_resources/removeInforationresources/";//访问的URL地址
			var postData = "id="+id;
			var cfg = {
					method: 'POST',
					data: postData
			};
			var request = Y.io(sUrl, cfg);//开始请求
			var handleSuccess = function(ioId, o){
				try{
					eval('result='+o.responseText);
					if(result.flag==1){
						var code = Y.one('#code').get('value');
						updateInformationresources(code);
						searchInforationresources(code);
					}
				}catch (e){
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
	//编辑新增资源表数据
	function editInforationresources(id){
		YUI().use('io',function(Y){
			var sUrl = "/admin/information_resources/editInforationresources/";//访问的URL地址
			if(id!=""){
				var name = Y.one("#informationresource_value_"+id).get('value');
				var postData = "id="+id+"&name="+name;
				var cfg = {
						method: 'POST',
						data: postData
				};
			}else{
				var cfg = {
						method: 'POST',
						form: {
							id: 'information_form',
							useDisabled: true
						}
				};
			}
			var request = Y.io(sUrl, cfg);//开始请求
			var handleSuccess = function(ioId, o){
				try{
					var code = Y.one('#code').get('value');
					updateInformationresources(code);
					searchInforationresources(code);
				}catch (e){
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
	//更新资源表数据
	function updateInformationresources(code){
		YUI().use('io',function(Y){
			var sUrl = "/admin/information_resources/updateInformationresources/";//访问的URL地址
			var postData = "code="+code;
			var cfg = {
				method: 'POST',
				data: postData
			};
			var request = Y.io(sUrl, cfg);//开始请求
			var handleSuccess = function(ioId, o){
				try{
					eval('result='+o.responseText);
					var code = Y.one('#code').get('value');
					var node = Y.one('#'+code);
					node.set('innerHTML', '');
					var option = "<option value=''>"+j_please_select+"</option>";
					if(result.flag){
						if(result.data!=""){
							Y.each(result.data,function(v,k){
								option += '<option value='+k+'>'+v+'</option>';
							})
						}
					}
					node.append(option);
					node.set('selectedIndex',0);
					node.setStyle('display','inline');

				}catch (e){
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
	function showInput(key){
		YUI().use("io",function(Y) {
			Y.one('#informationresource_span_'+key).addClass('status');
			Y.one('#informationresource_value_'+key).removeClass('status');
		})
	}
	function checkform(){
		var flashImg=document.getElementById("show_flash_image");
		if(flashImg.src==""||flashImg.src.indexOf("/media/default_no_photo.png")>0){
			alert('请选择轮播图片');
			return false;
		}
	}
	function showbigimg(e){
		if(e.src!=""){
			window.open(e.src,"_blank");
		}
	}
	
	//重置时清空轮播图片
	function clear_flashInfo(){
		var show_flash_image=document.getElementById("show_flash_image");
		var span=show_flash_image.previousSibling.previousSibling;
		show_flash_image.style.display="none";
		span.style.display="block";
		show_flash_image.src="/media/default_no_photo.png";
	}
</script> 
