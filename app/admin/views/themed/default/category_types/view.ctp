<style type="text/css">
 .am-radio input[type="radio"]{margin-left:0px;}
.am-form-horizontal .am-radio{padding-top:0;display:inline;position:relative;}
 
 
.related_dt div:hover{cursor: pointer;border:1px solid #5eb95e;color:#5eb95e;}
.related_dt div{border:1px solid #ccc;}
.related_dt div:hover span{color:#5eb95e;}
.related_dt div span{float:none;color: #ccc;padding:3px 2px 0px 2px;margin-right:5px;}
.am-no{color: #dd514c;cursor: pointer;}
.am-dropdown{margin-top:3px;}

</style>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index:100; width: 15%;max-width:200px;">
		    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		    <li><a href="#associated_brand"><?php echo $ld['associated_brand']?></a></li>

		</ul>
	</div>
	<div class="am-panel-group admin-content am-detail-view am-fr" id="accordion"  >
		<?php echo $form->create('CategoryType',array('action'=>'view/'.(isset($typeInfo)?$typeInfo['CategoryType']['id']:""),'class'=>'am-form am-form-horizontal','name'=>'CategoryTypeForm','onsubmit'=>''));?>
			<input type="hidden" id="data[CategoryType][id]" name="data[CategoryType][id]" value="<?php echo isset($typeInfo['CategoryType']['id'])?$typeInfo['CategoryType']['id']:0 ?>" />	
			<!--基本信息-->
			<div id="basic_information" class="am-panel am-panel-default">
		  		<div class="am-panel-hd" style="margin-left:-2px;">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information'] ?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in" style="margin-top:10px;margin-left:12px;">
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld['type_name']?></label>
		    			          <div class="am-u-lg-9 am-u-md-6 am-u-sm-6">
			    			         <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				      <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="hidden"  name="data[CategoryTypeI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'] ?>">
									<input type="text" id="category_type_name<?php echo $v['Language']['locale'];?>" name="data[CategoryTypeI18n][<?php echo $k;?>][name]" value="<?php echo isset($typeInfo['CategoryTypeI18n'][$v['Language']['locale']]['name'])?$typeInfo['CategoryTypeI18n'][$v['Language']['locale']]['name']:'';?>" />
			    				     </div>
			    				<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label"><?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
			    				<?php }?>
			    			<?php }}?>
		    			</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld['description']?></label>
		    			<div class="am-u-lg-9 am-u-md-6 am-u-sm-6">
			    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<textarea id="category_type_desc<?php echo $v['Language']['locale'];?>" name="data[CategoryTypeI18n][<?php echo $k;?>][description]"><?php echo isset($typeInfo['CategoryTypeI18n'][$v['Language']['locale']]['description'])?$typeInfo['CategoryTypeI18n'][$v['Language']['locale']]['description']:'';?></textarea>
			    				</div>
			    				<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label" style="padding-top:12px;"><?php echo $ld[$v['Language']['locale']]?></label>
			    				<?php }?>
			    			<?php }}?>
		    			</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3  am-view-label" style="padding-top:2px;"><?php echo $ld['category_code']?></label>
		    			            <div class="am-u-lg-9 am-u-md-6 am-u-sm-6">	<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    					<input type="text" name="data[CategoryType][code]" value="<?php if(isset($typeInfo)){ echo $typeInfo['CategoryType']['code'];}?>" />
		    			           </div></div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['higher_category']?></label>
		    			<div class="am-u-lg-9 am-u-md-6 am-u-sm-6"><div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
							<select id="CategoryParentId" data-am-selected name="data[CategoryType][parent_id]" <?php if(!isset($typeInfo)){?> onChange="selectCategoryType(this.value);"<?php }?> >
								<option value="0"><?php echo $ld['root']?></option>
								<?php if(isset($category_types_tree) && sizeof($category_types_tree)){foreach($category_types_tree as $k=>$v){ if(isset($typeInfo)&&$v['CategoryType']['id']==$typeInfo['CategoryType']['id']){ continue;}//第一层 ?>
								<option value="<?php echo $v['CategoryType']['id'];?>" <?php echo isset($typeInfo['CategoryType']['parent_id'])&&$v['CategoryType']['id']==$typeInfo['CategoryType']['parent_id']?"selected":"";?> ><?php echo $v['CategoryTypeI18n']['name'];?></option>
								<?php if(isset($v['SubCategory']) && sizeof($v['SubCategory'])>0){foreach($v['SubCategory'] as $kk=>$vv){  if(isset($typeInfo)&&$vv['CategoryType']['id']==$typeInfo['CategoryType']['id']){ continue;}//第二层?>
								<option value="<?php echo $vv['CategoryType']['id'];?>" <?php echo isset($typeInfo['CategoryType']['parent_id'])&&$vv['CategoryType']['id']==$typeInfo['CategoryType']['parent_id']?"selected":"";?> >|-- <?php echo $vv['CategoryTypeI18n']['name'];?></option>
								<?php if(isset($vv['SubCategory']) && sizeof($vv['SubCategory'])>0){foreach($v['SubCategory'] as $kkk=>$vvv){//第二层 ?>
								<?php }}}}}}?>
							</select></div>
		    			</div>
					</div>
				   <?php if(!isset($typeInfo)){?>
					<div  class="am-g">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['sort']?></label>
		    		 	    <div class="am-u-lg-3 am-u-md-4 am-u-sm-3">
		    				<div id="order_div" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
		    					      <label><input type="radio" name="orderby" value="0" checked><?php echo $ld['front'];?></label>
								<label ><input type="radio" name="orderby" value="1"><?php echo $ld['final'];?></label>
								<label><input type="radio" name="orderby" value="2"><?php echo $ld['at'];?></label>
								<select id="orderby" name="orderby_sel"></select>
								<?php echo $ld['after'] ?>
		    				</div>
		    			</div>
					</div>
					<?php }?>

					<div class="am-g"  style="margin-top:14px;">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="padding-top:2px;"><?php echo $ld['whether_to_display']?></label>
			    			             <div class="am-u-lg-9 am-u-md-6 am-u-sm-6" style="padding-top:1px;">
		    				    	       <label class="am-radio am-success" style="padding-top:2px">
		    						<input type="radio" name="data[CategoryType][status]" data-am-ucheck value="1" <?php if((isset($typeInfo)&&$typeInfo['CategoryType']['status']==1)||!isset($typeInfo)){?> checked<?php }?>><?php echo $ld['yes']?>
	    				   	</label>&nbsp;&nbsp;
							<label class="am-radio am-success" style="padding-top:2px">
								<input type="radio" name="data[CategoryType][status]" data-am-ucheck value="0" <?php if(isset($typeInfo)&&$typeInfo['CategoryType']['status']==0){?> checked<?php }?>><?php echo $ld['no']?>
							</label>
		    			      </div>
					</div>
				</div>
                  	<div class="btnouter">
					<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
					<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
				</div>				
			</div>
			<!--关联品牌-->
			<div id="associated_brand" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['associated_brand']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
					<div class="am-form-group">
						<ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1">
							<li style="margin-top:10px;">
								<div class="am-u-lg-6 am-u-md-3 am-u-sm-3 " style="margin-left:10px;"><input type="text" name="brand_keyword" id="brand_keyword" /></div>
								<div class="am-u-lg-1  am-u-md-1 am-u-sm-1 ">
									<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['search']?>" onclick="searchBrands();" />
								</div>
							
							</li>
						</ul>	
					</div>	 
					<div class="am-form-group" style="margin-top:15px;">	
				    	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-center">
							<label><?php echo $ld['optional_brand']?></label>
							<div id="product_select" class="related_dt" class="am-u-lg-12 am-u-md-12 am-u-sm-12"></div>
							
						</div>
				    	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-center">
							<label><?php echo $ld['have_associated_brand']?></label>
							<div id="relative_product">
								<?php if(isset($brand_relation) && sizeof($brand_relation)>0){foreach($brand_relation as $k=>$v){?>
								<div class="am-form-group">
									<div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
										<?php echo $brand_code_list[$v['CategoryTypeRelation']['related_brand_id']]." -- ";echo $brand_name_list[$v['CategoryTypeRelation']['related_brand_id']];?>
									</div>
									<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" id='r<?php echo $v["CategoryTypeRelation"]["id"]?>'>
										<span class="am-icon-close am-no"  onclick="drop_product_relation_product('<?php echo $v['CategoryTypeRelation']['id'];?>')"></span>
									</div>
								</div>
								<?php }}?>
							</div>
						</div>
					</div>	
					<div class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
					</div>			
				</div>				
			</div>				
		<?php echo $form->end();?>	
	</div>
</div>

<script>
function selectCategoryType(id){
	var a=document.getElementById("data[CategoryType][id]").value;
	if(a!="0"&&a!=""){return;}
	$.ajax({
		url:admin_webroot+"category_types/searchtypes/"+id,
		type:"POST",
		data:{},
		dataType:"json",
		success:function(data){
//			if(data.flag==1){
//					datahtml="<label><input  type='radio' name='orderby' value='0'/><?php echo $ld['front'];?></label>　<label><input type='radio' name='orderby' value='1' /><?php echo $ld['final'];?></label>　<label><input type='radio' name='orderby' value='2'/><?php echo $ld['at'];?></label><select id='orderby' name='orderby_sel' style='display:none;'></select><?php echo $ld['after'] ?>";
//                    $("#order_div").html(datahtml);
//                    var node = document.getElementById("orderby");
//                    var optiondata=data.na;
//                    for(var i=0;i<optiondata.length;i++){
//                        var option=document.createElement("option");
//                        node.appendChild(option);
//                        option.value=optiondata[i]['id'];
//                        option.text=optiondata[i]['value'];
//                        if(optiondata[i]['id']==id){
//                            option.selected=true;
//                        }
//                    }
//                    $("#orderby").selected();
//                    $("input[name='orderby']").parent().addClass("am-radio am-success am-form-group-label");
//                    $("input[name='orderby']").uCheck();
//					}
//			if(data.flag==2){
//					alert(data.message);
//				}
				if(data.flag==1){
                    var order_div_html="<label><input  type='radio' name='orderby' value='0'/><?php echo $ld['front'];?></label>　<label><input type='radio' name='orderby' value='1' /><?php echo $ld['final'];?></label>　<label><input type='radio' name='orderby' value='2'/><?php echo $ld['at'];?></label><select id='orderby' name='orderby_sel' style='display:none;'></select><?php echo $ld['after'] ?>";
                    $("#order_div").html(order_div_html);
                    var node = document.getElementById("orderby");
                    var optiondata=data.na;
                    for(var i=0;i <optiondata.length;i++){
                        var option=document.createElement("option");
                        node.appendChild(option);
                        option.value=optiondata[i]['id'];
                        option.text=optiondata[i]['value'];
                        if(optiondata[i]['id']==id){
                            option.selected=true;
                        }
                        
                    $("#orderby").selected();
                    $("input[name='orderby']").parent().addClass("am-radio am-success am-form-group-label");
                    $("input[name='orderby']").uCheck();
                    }
                }else if(data.flag==2){
                    $("#order_div").html("<label class='am-form-group-label'>"+data.na+"</label>");
                }else{
                    alert(data);
                }
		}
	});
}
	window.load=selectCategoryType(document.getElementById("CategoryParentId").value);

/**
  * 搜索品牌
  */
function searchBrands(){
	var brand_keyword = document.getElementById("brand_keyword");//搜索关键字
	$.ajax({
		url:admin_webroot+"category_types/searchBrands/",
		type:"POST",
		data:{brand_keyword:brand_keyword.value},
		dataType:"json",
		success:function(data){
				if(data.code=="1"){
					var product_select_sel = document.getElementById('product_select');
					$(product_select_sel).html();
					if(data.content){
						var selhtml="";
						for(i=0;i<data.content.length;i++){
							selhtml+="<div class=\"am-u-lg-5 am-u-md-5 am-u-sm-5\" style=\"margin-right:20px;margin-bottom:10px;\" onclick=\"add_brand_relation_categorytype('"+data.content[i]['Brand'].id+"')\">"+"<span  class='am-icon-plus' ></span>"+data.content[i]['Brand'].code+"--"+data.content[i]['BrandI18n'].name+"</div>";
						}
						$(product_select_sel).html(selhtml);
			         }
					return;
				}
				if(data.flag=="2"){
					alert(data.content);
				}
	}
	});
}

//编辑页 关联商品 添加
function add_brand_relation_categorytype(intvalue){
	if(document.getElementById("data[CategoryType][id]")){
		var CategoryType_id=document.getElementById("data[CategoryType][id]").value;
	}else{
		var CategoryType_id=0;
	}
	
	var is_single = document.getElementsByName("is_single[]");
	var is_single_value = 0;
	for( var i=0;i<is_single.length;i++ ){
		if(is_single[i].checked){
			is_single_value = is_single[i].value;
		}
	}
	if(intvalue==""){
		alert(j_please_select);
		return;
	}
	var newhtml=document.getElementById("relative_product").innerHTML;
	$.ajax({
	url:admin_webroot+"category_types/add_brand_relation_categorytype/",
	type:"POST",
	data:{product_select:intvalue,product_id:CategoryType_id,is_single_value:is_single_value},
	dataType:"json",
	success:function(data){
		if(data.flag=="1"){
					for(i=0;i<data.content.length;i++){
                		newhtml+="<div class='am-form-group'><div id='r"+data.content[i].id+"' class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+data.content[i].brand_code+" -- "+data.content[i].brand_name+"</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span  class='am-icon-close am-no' onclick=\"drop_product_relation_product('"+data.content[i].id+"');\"></span></div></div>";
					}
					$("#relative_product").html(newhtml);
					alert('添加成功');
					return;
				}
				if(data.flag=="2"){
					alert(data.content);
				}
	 }
	});
}

function drop_product_relation_product(ct_id){
	var newhtml="";
	$.ajax({
		url:admin_webroot+"category_types/drop_product_relation_product/",
		type:"POST",
		data:{ct_id:ct_id},
		dataType:"json",
		success:function(data){
			if(data.flag=="1"){				
					alert('删除成功');
					var obj=document.getElementById('r'+ct_id);
	   	     		//obj.parentNode.removeChild(obj);
	   	     		$(obj).parent().remove();
	   	     		
					return;
				}
				if(data.flag=="2"){
					alert(j_failed_delete);
				}
	 }
	});
}
</script>