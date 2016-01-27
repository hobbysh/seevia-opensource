<style type="text/css">
.attr_select div{
    border: 1px solid #ccc;
    display: block;
    margin: 2px 5px;
    padding:3px 5px;
}
.attr_select div span{
    color:#ccc;
    margin-left:5px;
}
.attr_select div:hover{
    cursor: pointer;
    border: 1px solid #5eb95e;
    color:#5eb95e;
}
.attr_select div:hover span{
    color:#5eb95e;
}
.attr_list div.attr_data{cursor: pointer;border: 1px solid #fff;margin:2px 0px;}
.attr_list div.attr_data:hover{border: 1px solid #5eb95e;}
.attr_list div.attr_data:hover span{cursor: pointer;}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.am-radio input[type="radio"]{margin-left:0px;}
.am-form-label{font-weight:bold;}
</style>
<div class="">
	<!--左侧菜单-->
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-hide-sm-only">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
	    	<?php if(isset($id) && $id!=0) {?><li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li><?php }?>
			<?php if(isset($id) && $id!="") {?>	<li><a href="#associated_attributes"><?php echo $ld['associated_attributes']?></a></li>	<?php }?>
		</ul>
	</div>
	<?php echo 			$form->create('productstypes',array('action'=>'view/'.(isset($this->data['ProductType']['id'])?$this->data['ProductType']['id']:""),'onsubmit'=>'producttype_input_checks();return false;','name'=>"ProductstypeForm","id"=>"ProductstypeEditForm"));?>
		<input name="data[ProductType][id]" type="hidden" id='producttype_id' value="<?php echo isset($this->data['ProductType']['id'])?$this->data['ProductType']['id']:'';?>">
		<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			<input name="data[ProductTypeI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
		<?php }}?>
		<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
			<?php if(!isset($this->data['ProductType'])||$this->data['ProductType']['id']>0){ ?>
			<div id="basic_information" class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<label><?php echo $ld['basic_information']?></label>
					</h4>
		    	</div>
		    	<div class="am-panel-collapse am-collapse am-in">
					<div class="am-panel-bd am-form-detail am-form am-form-horizontal">		
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-view-label"><?php echo $ld['product_type_name']?></label>	
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7" >
							<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach($backend_locales as $k => $v){?>
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">
									<input type="text" id="producttype_name_<?php echo $v['Language']['locale'];?>" name="data[ProductTypeI18n][<?php echo $k;?>][name]" value="<?php echo isset($this->data['ProductTypeI18n'][$v['Language']['locale']]['name'])?$this->data['ProductTypeI18n'][$v['Language']['locale']]['name']:'';?>" />
								</div>
								<?php if(sizeof($backend_locales)>1){?>
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-1  am-text-left am-view-label" style="font-weight:normal;"><?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
								<?php }?>
                            <?php }}?>
                            </div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-view-label"><?php echo $ld['attribute_code']?></label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" name="data[ProductType][code]" id="producttype_code" value="<?php echo isset($this->data['ProductType']['code'])?$this->data['ProductType']['code']:'';?>" />
								</div>
								<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-view-label"><em style="color:red;">*</em></label>	
							</div>	
						</div>	
					<!--	<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['type'] ?></label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<label class="am-radio am-success">
									<input type="radio" value="0" data-am-ucheck name="data[ProductType][type]" <?php echo (isset($this->data['ProductType']['customize'])&&$this->data['ProductType']['type']=='0')||(!isset($this->data['ProductType']['type']))?"checked='checked'":""; ?>><?php echo $ld['unique'] ?>
								</label>&nbsp;&nbsp;
                                <label class="am-radio am-success">
									<input type="radio" value="1" data-am-ucheck name="data[ProductType][type]" <?php echo isset($this->data['ProductType']['type'])&&$this->data['ProductType']['type']=='1'?
									"checked='checked'":""; ?> >Multiple Component
								</label>&nbsp;&nbsp;
                                <label class="am-radio am-success">
									<input type="radio" value="2" data-am-ucheck name="data[ProductType][type]" <?php echo isset($this->data['ProductType']['type'])&&$this->data['ProductType']['type']=='2'?
									"checked='checked'":""; ?> >Multiple Composition
								</label>
                            </div>
						</div>	-->
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-view-label"><?php echo $ld['attribute_group']?></label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<textarea name="data[ProductType][group_code]"><?php echo isset($this->data['ProductType']['group_code'])?$this->data['ProductType']['group_code']:"";?></textarea>
								</div>
							</div>
						</div>	
						<div class="am-form-group"> 
							<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-view-label"><?php echo $ld['whether_custom'] ?></label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><div>
								<label class="am-radio am-success">
									<input type="radio" value="1" data-am-ucheck name="data[ProductType][customize]" <?php echo isset($this->data['ProductType']['customize'])&&$this->data['ProductType']['customize']=='1'?
									"checked='checked'":""; ?> ><?php echo $ld['yes'] ?>
								</label>&nbsp;&nbsp;
								<label class="am-radio am-success">
									<input type="radio" value="0" data-am-ucheck name="data[ProductType][customize]" <?php echo (isset($this->data['ProductType']['customize'])&&$this->data['ProductType']['customize']=='0')||(!isset($this->data['ProductType']['customize']))?"checked='checked'":""; ?>><?php echo $ld['no'] ?>
								</label>
							</div></div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-view-label" style="margin-top:17px;"><?php echo $ld['valid'] ?></label>	
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><div>
								<label class="am-radio am-success">
									<input type="radio" value="1" data-am-ucheck name="data[ProductType][status]" <?php echo (isset($this->data['ProductType']['status'])&&$this->data['ProductType']['status']=='1')||!isset($this->data['ProductType']['status'])?
									"checked='checked'":""; ?>><?php echo $ld['yes'] ?>
								</label>&nbsp;&nbsp;
								<label class="am-radio am-success">
									<input type="radio" value="0" data-am-ucheck name="data[ProductType][status]" <?php echo isset($this->data['ProductType']['status'])&&$this->data['ProductType']['status']=='0'?"checked='checked'":""; ?>><?php echo $ld['no'] ?>
								</label>
							</div></div>
						</div>	
						<div class="btnouter" style="margin:50px;">
							<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="">
								<?php echo $ld['d_submit'];?>
							</button>
							<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" >
								<?php echo $ld['d_reset']?>
							</button>
						</div>	
			    	</div>
		    	</div>
		    </div>
		    <?php } ?>
			<?php if(isset($this->data['ProductType']['id'])){ ?>
		    <div id="associated_attributes" class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<label><?php echo $ld['associated_attributes']?></label>
					</h4>
		    	</div>	
		    	<div class="am-panel-collapse am-collapse am-in">
					<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
							<div class="am-u-lg-4 am-u-md-4 am-u-sm-12 am-view-label">
								<input type="text" name="attr_keywords" id="attr_keywords" value="" />
							</div>
							<div class="am-u-lg-4 am-u-md-4 am-u-sm-12" style="margin-top:10px;">
								   <select name="attr_type" id="attr_type" data-am-selected>
									<option value=""><?php echo $ld['all_types']?></option>
									<?php if(isset($Resource_info['property_type'])&&sizeof($Resource_info['property_type'])>0){ foreach($Resource_info['property_type'] as $k=>$v){?>                                           <option value="<?php echo $k; ?>" <?php echo isset($this->data['']) ?>><?php echo $v; ?></option>
									<?php }} ?>
								</select>
							</div>
							<div class="am-u-lg-4 am-u-md-4 am-u-sm-12 am-view-label">
								<button type="button" class="am-btn am-btn-success am-btn-sm am-radius"  onclick="getattrlist()" value=""><?php echo $ld['search'];?></button>
							</div>
						</div>
						<div class="am-form-group">
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-12"><?php echo $ld['attribute_list']?></div>
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-12"><?php echo $ld['associated_attributes']?></div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 attr_select" id="attr_select">&nbsp;</div>
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 attr_list" id="attr_list">
                            		<?php if(isset($all_attr_list) && sizeof($all_attr_list)>0){foreach($all_attr_list as $k=>$v){?>
                            			<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 attr_data">
                            				<div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
                        						<?php echo $v; ?><input type="hidden" value="<?php echo $k; ?>">
                        					</div>
                        					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-right">
                        						<span onclick="removeattr(this)" class="am-icon-close am-no" style="color:#dd514c;"></span>
                        					</div>
                        				</div>
                                <?php }}?>
                            </div>
                        </div>
                        <div class="btnouter">
                        	<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="">
								<?php echo $ld['d_submit'];?>
							</button>
							<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" >
								<?php echo $ld['d_reset']?>
							</button>
						</div>
					</div>
				</div>
		    </div>
			<?php } ?>	
		</div>
<?php echo $form->end();?>
</div>
<script type="text/javascript">
function producttype_input_checks(){
	var producttype_name_obj = document.getElementById("producttype_name_"+backend_locale);
	if(producttype_name_obj.value==""){
		alert("<?php echo $ld['enter_attribute_name']?>");
		return false;
	}
	var producttype_id=document.getElementById("producttype_id").value;
	var producttype_code=document.getElementById("producttype_code").value;
	if(producttype_code==""){
		alert("<?php printf($ld['name_not_be_empty'],$ld['attribute_code']) ?>");
		return false;
	}else{
		check_unique(producttype_code,producttype_id);
	}
}
function check_unique(producttype_code,producttype_id){
	$.ajax({
		url:"/admin/productstypes/check_producttype_unique",
		data:"producttype_code="+producttype_code+"&producttype_id="+producttype_id,
		method: 'POST',
		dataType:'json',
		success:function(data){
			if(data.code==1){
					document.ProductstypeForm.submit();
				}else{				
					alert(data.msg);
				}
		}
	});
}
function remove_pta(pt_id){
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
		if(confirm("<?php echo $ld['confirm_delete']?>"))
		{
			batch_action(pt_id);
		}
	}else{
		if(confirm(j_please_select))
		{
			return false;
		}
	}
}

function batch_action(pt_id){
	document.ProducttypeForm.action=admin_webroot+"productstypes/lookremove_batch/"+pt_id;
	document.ProducttypeForm.onsubmit= "";
	document.ProducttypeForm.submit();
}

function getattrlist(action_flag){
	var producttype_id=document.getElementById("producttype_id").value;
	var attr_keywords=document.getElementById("attr_keywords").value;
	var attr_type=document.getElementById("attr_type").value;
    
    $.ajax({
		url:"/admin/attributes/getattrlist/"+producttype_id,
		data:{'attr_keywords':attr_keywords,'attr_type':attr_type},
		method: 'POST',
		dataType:'json',
		success:function(data){
            try{
                var attr_select = document.getElementById('attr_select');
                attr_select.innerHTML = "";
                if(data.flag=="1"){
                    if(data.content){
						var selhtml="";
						for(i=0;i<data.content.length;i++){
							selhtml+="<div class='am-u-lg-5 am-u-md-5 am-u-sm-5' onclick=\"add_associated_attributes('"+data.content[i]['Attribute'].id+"')\"><span class='am-icon-plus'></span> "+data.content[i]['AttributeI18n'].name+"</div>";
						}
                        selhtml+="&nbsp;";
						attr_select.innerHTML = selhtml;
			        }
					return false;
                }else{
                    attr_select.innerHTML = "&nbsp;";
    				if(typeof(action_flag)=="undefined"){
    					alert(data.content);
    				}
    			}
            }catch (e){
				alert("<?php echo $ld['asynchronous_request_failed']?>");
			}
		}
	});
}

function add_associated_attributes(attr_select_value){
	var producttype_id=document.getElementById("producttype_id").value;
    if(attr_select_value!=""&&producttype_id!=""){
        $.ajax({
    		url:"/admin/productstypes/add_associated_attributes/"+producttype_id,
    		data:{'attr_select_value':attr_select_value},
    		method: 'POST',
    		dataType:'json',
    		success:function(data){
                try{
                    var attr_list=document.getElementById("attr_list");
					var newhtml = '';
                    if(data.flag=="1"){
						if(data.content){
							for(i=0;i<data.content.length;i++){
								newhtml+="<div class='am-u-lg-12 am-u-md-12 am-u-sm-12 attr_data'><div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+data.content[i]['AttributeI18n'].name+"<input type='hidden' value='"+data.content[i]['Attribute'].id+"'></div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-right'><span class='am-icon-close am-no' onclick='removeattr(this)'></span></div></div>";
							}
				         }
                         newhtml+="&nbsp;";
				         attr_list.innerHTML=newhtml;
					}else{
						alert(data.content);
					}
                    getattrlist(true);
                }catch (e){
    				alert("<?php echo $ld['asynchronous_request_failed']?>");
    			}
    		}
    	});
    }
}
function removeattr(obj){
	var producttype_id=document.getElementById("producttype_id").value;
	var attr_id=$(obj).parent().parent().find("input[type=hidden]").val();
	if(attr_id!=""&&producttype_id!=""){
        $.ajax({
    		url:"/admin/productstypes/remove_associated_attributes/"+producttype_id+"/"+attr_id,
    		data:{},
    		method: 'POST',
    		dataType:'json',
    		success:function(data){
                try{
                    var attr_list=document.getElementById("attr_list");
					var newhtml = '';
                    if(data.flag=="1"){
						if(data.content){
							for(i=0;i<data.content.length;i++){
								newhtml+="<div class='am-u-lg-12 am-u-md-12 am-u-sm-12 attr_data'><div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+data.content[i]['AttributeI18n'].name+"<input type='hidden' value='"+data.content[i]['Attribute'].id+"'></div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-right'><span class='am-icon-close am-no' onclick='removeattr(this)'></span></div></div>";
							}
				         }
                         newhtml+="&nbsp;";
				         attr_list.innerHTML=newhtml;
					}else if(data.flag=="2"){
                        attr_list.innerHTML="&nbsp;";
                    }else{
						alert(data.content);
					}
                    getattrlist(true);
                }catch (e){
    				alert("<?php echo $ld['asynchronous_request_failed']?>");
    			}
    		}
    	});
	}
}
</script>