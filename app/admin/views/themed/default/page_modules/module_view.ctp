<?php
echo $javascript->link('/skins/default/js/topic');echo $javascript->link('/skins/default/js/calendar/language/'.$backend_locale);echo $javascript->link('/skins/default/js/calendar/calendar');
echo $html->css('/skins/default/css/calendar/calendar');echo $javascript->link('/skins/default/js/utils');
?>
<style type="text/css">
	.show {  }
	.disshow { display:none; }
</style>

<?php echo $form->create('PageModule',array('action'=>'module_view','onsubmit'=>'return modules_check();'));?>
<div id="tablemain" class="tablemain">
	<!--编辑-->
	<div>
		<h2><?php echo $ld['basic_information']?></h2>
		<div>
			<input type="hidden" id="id" name="data[PageModule][id]" value="<?php if(isset($modules_info['PageModule']) && $modules_info['PageModule']['id'] !=0){echo $modules_info['PageModule']['id'];}?>"/>
			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){
			foreach ($backend_locales as $k => $v){?>
				<input id="PageModuleI18n<?php echo $k;?>Locale" name="data[PageModuleI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
				<input id="PageModuleI18n<?php echo $k;?>Id" name="data[PageModuleI18n][<?php echo $k;?>][id]" type="hidden" value="<?php if(isset($modules_info['PageModuleI18n'][$v['Language']['locale']])){echo $modules_info['PageModuleI18n'][$v['Language']['locale']]['id'];}?>">
				<input id="PageModuleI18n<?php echo $k;?>PageModuleI18nId" name="data[PageModuleI18n][<?php echo $k;?>][module_id]" type="hidden" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['id'];}?>">
			<?php }}?>
			<table class="lefttable">
				<!--名称--->
				<tr>
					<th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['module_name']?></th>
				</tr>
				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				<tr>
					<td><input type="text"  class="border" id="name_<?php echo $v['Language']['locale']?>" name="data[PageModuleI18n][<?php echo $k;?>][name]"  <?php if(isset($modules_info['PageModuleI18n'][$v['Language']['locale']])){?>value="<?php echo  $modules_info['PageModuleI18n'][$v['Language']['locale']]['name'];?>"<?php }else{?>value=""<?php }?> /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
				</tr>
				<?php }} ?>


				<!--模块标题--->

				<tr>
					<th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['module_title']?></th>
				</tr>
				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				<tr>
					<td><input type="text" class="border" id="url<?php echo $v['Language']['locale']?>" name="data[PageModuleI18n][<?php echo $k;?>][title]"  <?php if(isset($modules_info['PageModuleI18n'][$v['Language']['locale']])){?>value="<?php echo  $modules_info['PageModuleI18n'][$v['Language']['locale']]['title'];?>"<?php }else{?>value=""<?php }?> /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
				</tr>
				<?php }} ?>

			</table>
			<table class="righttable">
				<tr>
					<th><?php echo '所属样式'?></th>
					<td><select id="page_style_code" name="data[PageModule][page_style_code]" onchange="check_style(this.value)">
							<option value="0"><?php echo $ld['please_select']?></option>
							<?php if(isset($style_list) && sizeof($style_list) > 0){ foreach($style_list as $sk=>$s){ ?>
									<option value="<?php echo $sk?>" <?php if((isset($modules_info['PageModule']['page_style_code']) && $modules_info['PageModule']['page_style_code']==$sk) || (isset($code) && $code == $sk)){ echo 'selected';}?>><?php echo $s?></option>
							<?php }}?>
						</select></td>
				</tr>
				<tr>
					<th><?php echo $ld['module_parent']?></th>
					<td><select id="PageModuleParentId" name="data[PageModule][parent_id]"  onChange="searchPc(this.value);">
							<option value="0"><?php echo $ld['root']?></option>
							<?php if(isset($modules_tree) && sizeof($modules_tree)){foreach($modules_tree as $k=>$v){//第一层 ?>
							<option value="<?php echo $v['PageModule']['id'];?>" <?php echo isset($this->data['PageModule']['parent_id'])&&$v['PageModule']['id']==$this->data['PageModule']['parent_id']?"selected":"";?> ><?php echo $v['PageModuleI18n']['name'];?></option>
							<?php if(isset($v['SubPageModule']) && sizeof($v['SubPageModule'])>0){foreach($v['SubPageModule'] as $kk=>$vv){//第二层?>
							<option value="<?php echo $vv['PageModule']['id'];?>" <?php echo isset($this->data['PageModule']['parent_id'])&&$vv['PageModule']['id']==$this->data['PageModule']['parent_id']?"selected":"";?> >|-- <?php echo $vv['PageModuleI18n']['name'];?></option>
							<?php if(isset($vv['SubPageModule']) && sizeof($vv['SubPageModule'])>0){foreach($v['SubPageModule'] as $kkk=>$vvv){//第二层 ?>
							<?php }}}}}}?>
						</select>
					</td>
				</tr>
				<tr>
					<th><?php echo $ld['module_type']?></th>
					<td><select id='type' name="data[PageModule][type]" onChange="order_type(this.value);">
						<?php foreach( $module_types as $kk=>$vv ){ ?>
						<option value="<?php echo $kk;?>" <?php if(isset($modules_info['PageModule'])&&$modules_info['PageModule']['type']==$kk){echo "selected";}?> ><?php echo $vv;?></option>
						<?php }?>
						</select>
						<div id="type_div" <?php if(isset($modules_info)&&isset($modules_info['PageModule']['type'])&&$modules_info['PageModule']['type']!='module_product'&&$modules_info['PageModule']['type']!='module_article'&&$modules_info['PageModule']['type']!='module_link'&&$modules_info['PageModule']['type']!="module_flash"){?>style="display:none" <?php }?>>
							<span>类别:</span>
							<select id="type_id" name="data[PageModule][type_id]">
							<option value="">请选择！</option>
							<?php if(isset($modules_info)&&isset($modules_info['PageModule']['type'])&&($modules_info['PageModule']['type']=='module_product'||$modules_info['PageModule']['type']=='module_article')){
								if($modules_info['PageModule']['type']=='module_product'){
									$category_tree = $product_category_tree;
								}
								if($modules_info['PageModule']['type']=='module_article'){
									$category_tree = $article_category_tree;
								}
							?>
								<?php if(isset($category_tree) && sizeof($category_tree)>0){foreach($category_tree as $first_k=>$first_v){?>
								<option value="<?php echo $first_v['Category']['id'];?>" <?php if(isset($modules_info['PageModule']['type_id'])&&$modules_info['PageModule']['type_id']== $first_v['Category']['id']){?>selected<?php }?> ><?php echo $first_v['CategoryI18n']['name'];?></option>
								<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
								<?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
								<option value="<?php echo $second_v['Category']['id'];?>" <?php if(isset($modules_info['PageModule']['type_id'])&&$modules_info['PageModule']['type_id'] == $second_v['Category']['id']){?>selected<?php }?> >--<?php echo $second_v['CategoryI18n']['name'];?></option>
								<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
								<?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
								<option value="<?php echo $third_v['Category']['id'];?>" <?php if(isset($modules_info['PageModule']['type_id'])&&$modules_info['PageModule']['type_id'] == $third_v['Category']['id']){?>selected<?php }?> >----<?php echo $third_v['CategoryI18n']['name'];?></option>
								<?php }}}}}}?>
							<?php }elseif($modules_info['PageModule']['type'] == 'module_link'&&isset($link_type)&&!empty($link_type)){  foreach($link_type as $lk=>$l){?>
										<option value="<?php echo $lk?>" <?php if(isset($modules_info['PageModule']['type_id'])&&$modules_info['PageModule']['type_id'] == $lk){?>selected<?php }?> ><?php echo $l?></option>
							<?php }}elseif($modules_info['PageModule']['type'] == 'module_flash'&&isset($flash_type)&&!empty($flash_type)){  foreach($flash_type as $fk=>$f){{?>
								<option value="<?php echo $fk?>" <?php if(isset($modules_info['PageModule']['type_id'])&&$modules_info['PageModule']['type_id'] == $fk){?>selected<?php }?> ><?php echo $f?></option>

							<?php }}}?>
							</select>
						</div>
					</td>
				</tr>
				<tr><input type="hidden" id="orderby_type" name="data[PageModule][orderby_type]" value=""/>
					<th><?php echo $ld['module_orderby_type']?></th>
					<td><?php  if(!isset($modules_info['PageModule'])){$i=1;}else{$i=0;}?>
						<?php foreach($module_ordertype as $k=>$v ){ ?>
							<select id="<?php echo $k;?>" name="module_orderby_type" class="<?php if($i){echo "show";$i=0;}else{if(isset($modules_info['PageModule'])&&$modules_info['PageModule']['type']==$k){echo 'show';}else{echo 'disshow';}}?>">
								<?php foreach($v as $kk=>$vv ){ ?>
										<option value="<?php echo $kk;?>" <?php if(isset($modules_info['PageModule'])&&$modules_info['PageModule']['orderby_type']==$kk){echo "selected";}?> ><?php echo $vv;?></option>
									<?php }?>
								</select>
						<?php }?>
					</td>
				</tr>
				<tr>
					<th><?php echo '方法';?></th>
					<td><input type="text"  id="function" class="border" name="data[PageModule][function]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['function'];} ?>" /></td>
				</tr>
				<tr>
					<th><?php echo 'Model';?></th>
					<td><input type="text"  id="model" class="border" name="data[PageModule][model]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['model'];} ?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['module_code']?></th>
					<td><input type="text"  id="code" class="border" onblur="operator_change()" name="data[PageModule][code]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['code'];} ?>" /><em>*</em></td>
				</tr>
				<tr>
					<th><?php echo '文件夹名称';?></th>
					<td><input type="text"  id="file_name" class="border" name="data[PageModule][file_name]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['file_name'];} ?>" /></td>
				</tr>
				<!--<tr>
					<th><?php echo '模块页面编码'?></th>
					<td><input type="text"  id="page_style_code" class="border" onblur="operator_change()" name="data[PageModule][page_style_code]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['page_style_code'];} ?>" /><em>*</em></td>
				</tr>-->
				<tr>
					<th><?php echo $ld['module_template']?></th>
					<td><input type="text"  id="element_type" class="border" name="data[PageModule][element_type]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['element_type'];} ?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['module_location']?></th>
					<td><select name="data[PageModule][position]">
						<?php foreach( $module_position as $kk=>$vv ){ ?>
						<option value="<?php echo $kk;?>" <?php if(isset($modules_info['PageModule'])&&$modules_info['PageModule']['position']==$kk){echo "selected";}?> ><?php echo $vv;?></option>
						<?php }?>
						</select></td>
				</tr>
				<tr>
					<th><?php echo $ld['module_limit_number']?></th>
					<td><input type="text"  class="border"  name="data[PageModule][limit]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['limit'];} ?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['module_width']?></th>
					<td><input type="text"  id="width" class="border" name="data[PageModule][width]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['width'];} ?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['module_height']?></th>
					<td><input type="text"  class="border"  name="data[PageModule][height]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['height'];} ?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['module_css']?></th>
					<td><textarea class="border" name="data[PageModule][css]" ><?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['css'];} ?></textarea></td>
				</tr>
				<tr>
					<th><?php echo $ld['sort']?></th>
					<td><input type="text" class="input_sort border" name="data[PageModule][orderby]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['orderby'];} ?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['module_float']?></th>
					<td><input type="radio" value="0" name="data[PageModule][float]" checked /><?php echo $ld['module_float_in_entire_row']?>
						<input type="radio" name="data[PageModule][float]" value="1" <?php if(isset($modules_info['PageModule'])&&$modules_info['PageModule']['float'] == 1){ echo "checked"; } ?>/><?php echo $ld['module_left_floating']?>
						<input type="radio" name="data[PageModule][float]" value="2" <?php if(isset($modules_info['PageModule'])&&$modules_info['PageModule']['float'] == 2){ echo "checked"; } ?>/><?php echo $ld['module_right_floating']?>
					</td>
				<tr>
					<th><?php echo $ld['valid']?></th>
					<td><input type="radio" value="1" name="data[PageModule][status]" checked/><?php echo $ld['yes']?>
						<input type="radio" name="data[PageModule][status]" value="0" <?php if(isset($modules_info['PageModule'])&&$modules_info['PageModule']['status'] == 0){ echo "checked"; } ?>/><?php echo $ld['no']?></td>
				</tr>
			</table>
		</div>
		<div class="btnouter">
			<input type="submit" value="<?php echo $ld['d_submit']?>" /> <input type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
	</div>
</div>

<?php echo $form->end();?>
<script type="text/javascript">
  function add_to_seokeyword(obj,keyword_id){

	var keyword_str = GetId(keyword_id).value;
	var keyword_str_arr = keyword_str.split(",");
	for( var i=0;i<keyword_str_arr.length;i++ ){
		if(keyword_str_arr[i]==obj.value){
			return false;
		}
	}
	if(keyword_str!=""){
		GetId(keyword_id).value+= ","+obj.value;
	}else{
		GetId(keyword_id).value+= obj.value;
	}
}
function modules_check(){
	var module_type = document.getElementById("type").value;
	if(module_type !='module_parent'){
		var orby_type = document.getElementById(module_type).value;
		document.getElementById("orderby_type").value=orby_type;
	}
	if(document.getElementById("name_"+backend_locale).value==''){
		alert("<?php echo $ld['module_name_can_not_empty']?>");
		return false;
	}
	if(document.getElementById('code').value==''){
		alert("<?php echo $ld['module_code_can_not_empty']?>");
		return false;
	}
	return true;
}
function operator_change(){
	var code = document.getElementById("code").value;
	if(code!=""){
           YUI().use("io",function(Y) {
           var code=document.getElementById('code');
           var id=document.getElementById('id').value;
           if(id==''){
           	   var id=0;
           }
           var sUrl = admin_webroot+"page_modules/act_view/"+id;
           var cfg = {
           method: "POST",
           data: "code="+code.value
           };
           var request = Y.io(sUrl, cfg);

           var handleSuccess = function(ioId, o){
                try{
                     eval('result='+o.responseText);
                     if(result.code==1){

                     }else{
                          alert("<?php echo $ld['module_code_already_exists']?>");
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
          //return false;
 }
function order_type(obj){
	var orderby_type = document.getElementsByName("module_orderby_type");
	for( var i=0;i<orderby_type.length;i++ ){
		var type=orderby_type[i].id;
		if(type==obj){
			document.getElementsByName("module_orderby_type")[i].className = "show";
		}else{
			document.getElementsByName("module_orderby_type")[i].className = "disshow";
		}
	}

	var type = '';
	if(obj == 'module_product'){
		type = 'P';
	}
	if(obj == 'module_article'){
		type = 'A';
	}
	if(obj == 'module_link'){
		type = 'L';
	}
	if(obj == 'module_flash'){
		type = 'F';
	}
	if(type == ''){
		document.getElementById('type_div').style.display ='none';
		return;
	}
	if(type!=""){
           YUI().use("io",function(Y) {
           if(id==''){
           	   var id=0;
           }
           var sUrl = admin_webroot+"page_modules/getCats/";
           var cfg = {
           method: "POST",
           data: "type="+type
           };
           var request = Y.io(sUrl, cfg);

           var handleSuccess = function(ioId, o){
                try{
                     eval('result='+o.responseText);
                     if(result.category_tree){
	                     var option="<option value=''>"+j_please_select+"</option>";
	                     if(type == 'L'||type =='F'){
		                     Y.each(result.category_tree,function(v,k){
		                     	option +='<option value='+k+'>'+v+'</option>';
		                     })
	                     } else {
		                     for (i = 0; i < result.category_tree.length; i++ ){
		                          option +='<option value='+result.category_tree[i]['Category']['id']+'>'+result.category_tree[i]['CategoryI18n']['name']+'</option>';
		                          if(result.category_tree[i]['SubCategory']&&result.category_tree[i]['SubCategory'].length>0){
			                          for(j = 0; j <result.category_tree[i]['SubCategory'].length; j++){
			                              option +='<option value='+result.category_tree[i]['SubCategory'][j]['Category']['id']+'>--'+result.category_tree[i]['SubCategory'][j]['CategoryI18n']['name']+'</option>';
			                              if(result.category_tree[i]['SubCategory'][j]['SubCategory']&&result.category_tree[i]['SubCategory'][j]['SubCategory'].length>0){
					                          for(m = 0; m <result.category_tree[i]['SubCategory'][j]['SubCategory'].length; m++){
					                              option +='<option value='+result.category_tree[i]['SubCategory'][j]['SubCategory'][m]['Category']['id']+'>----'+result.category_tree[i]['SubCategory'][j]['SubCategory'][m]['CategoryI18n']['name']+'</option>';
					                          }
				                          }
			                          }
		                          }
		                     }
	                     }
	                     Y.one("#type_id").set('innerHTML','');
	                     Y.one("#type_id").append(option);
	                     Y.one('#type_id').set('selectedIndex',0);
						 Y.one("#type_div").setStyles({
						 	 display : 'block'
						 });
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
function check_style(code){
    YUI().use("io",function(Y) {
           var sUrl = admin_webroot+"page_modules/check_style/";
           var cfg = {
           method: "POST",
           data: "code="+code
           };
           var request = Y.io(sUrl, cfg);
           var handleSuccess = function(ioId, o){
           	   try{
	           	   eval('result='+o.responseText);
	               if(result.flag){
	                     var option="<option value='0'>"+'<?php echo $ld['root']?>'+"</option>";
	                     Y.each(result.modules_tree,function(v,k){
	                     	option +='<option value='+v['PageModule']['id']+'>'+v['PageModuleI18n']['name']+'</option>';
	                     })
	                     Y.one("#PageModuleParentId").set('innerHTML','');
	                     Y.one("#PageModuleParentId").append(option);
	                     Y.one('#PageModuleParentId').set('selectedIndex',0);
	               }else{
						var option="<option value='0'>"+'<?php echo $ld['root']?>'+"</option>";
	                     Y.one("#PageModuleParentId").set('innerHTML','');
	                     Y.one("#PageModuleParentId").append(option);
	                     Y.one('#PageModuleParentId').set('selectedIndex',0);
	               }
 			   }catch(e){
                     alert("<?php echo $ld['object_transform_failed']?>");
                     alert(o.responseText);
                }
           }
           var handleFailure = function(ioId, o){

           }
           Y.on('io:success', handleSuccess);
           Y.on('io:failure', handleFailure);
    });
}
<?php if(!isset($modules_info)||empty($modules_info)){?>
YUI().use('node',function(Y){
	Y.on('domready',function(){
		var type = Y.one('#type').get('value');
		order_type(type);
	});
});
<?php }?>
</script>
