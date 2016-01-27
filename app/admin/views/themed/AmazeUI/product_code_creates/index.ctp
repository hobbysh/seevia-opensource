<style type="text/css">
  .am-form-label{font-weight:bold;}
  .am-form-horizontal .am-form-label{padding-top:4px;}
  .btnouter{margin:50px;}
  .admin-content{overflow: initial;}
</style>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index:100; width: 15%;max-width:200px;">
		    <li><a href="#product_code_creates"><?php echo $ld['product_code_creates']?></a></li>
		</ul>
	</div>	
	<div class="am-panel-group admin-content  am-detail-view">
			<div id="product_code_creates" class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['product_code_creates']?>
					</h4>
			    </div>
				<div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
				            <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label"><?php echo $ld['product_brand']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<select id="brand_code" data-am-selected="{maxHeight:300}">
									<option value=''><?php echo $ld['please_select'] ?></option>
									<?php if(isset($brand_tree)&&sizeof($brand_tree)>0){foreach($brand_tree as $v){ ?>
									<option value="<?php echo $v['Brand']['code']; ?>"><?php echo $v['BrandI18n']['name'] ?></option>
									<?php }} ?>
								</select>
							</div>
			            </div>
			            <div class="am-form-group">
				            <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label">
						    	<?php echo $ld["prod_category_commodity"];?>
						    </label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<select id="category_type_id" data-am-selected="{maxHeight:300}">
									<option value=''><?php echo $ld['please_select'] ?></option>
									<?php if(isset($category_type_tree)&&sizeof($category_type_tree)>0){foreach($category_type_tree as $v){ ?>
									<option value="<?php echo $v['CategoryType']['id']; ?>"><?php echo $v['CategoryTypeI18n']['name'] ?></option>
										<?php if(isset($v['SubCategory'])&&sizeof($v['SubCategory'])>0){foreach($v['SubCategory'] as $vv){ ?>
										<option value="<?php echo $vv['CategoryType']['id']; ?>">|--<?php echo $vv['CategoryTypeI18n']['name'] ?></option>
										<?php }} ?>
									<?php }} ?>
								</select>
							</div>
			            </div>

			             <div class="am-form-group">
				            <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label">
						    	<?php echo $ld['product_categories']; ?>
						    </label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<select id="category_id" data-am-selected="{maxHeight:300}">
									<option value=''><?php echo $ld['please_select'] ?></option>
									<?php if(isset($category_tree)&&sizeof($category_tree)>0){foreach($category_tree as $v){ ?>
									<option value="<?php echo $v['CategoryProduct']['id']; ?>"><?php echo $v['CategoryProductI18n']['name'] ?></option>
										<?php if(isset($category_type_tree)&&sizeof($category_type_tree)>0){foreach($category_type_tree as $v){ ?>
									<option value="<?php echo $v['CategoryType']['id']; ?>"><?php echo $v['CategoryTypeI18n']['name'] ?></option>
										<?php if(isset($v['SubCategory'])&&sizeof($v['SubCategory'])>0){foreach($v['SubCategory'] as $vv){ ?>
											<option value="<?php echo $vv['CategoryType']['id']; ?>">|--<?php echo $vv['CategoryTypeI18n']['name'] ?></option>
											<?php }} ?>
										<?php }} ?>
									<?php }} ?>
								</select>
							</div>
			            </div>

			               <div class="am-form-group">
				            <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label">
						    </label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="button" class="am-btn am-btn-success am-radius am-btn-sm" style="margin-top:10px" value="<?php echo $ld['prod_auto_code']?>" onclick="auto_code()" />
							</div>
			            </div>
			    	     
			    	         <div class="am-form-group" style="margin-top:10px">
				            <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" >
						    <?php echo $ld['product_code']?>
						    </label>
							<div class="am-u-lg-4 am-u-md-6 am-u-sm-6" style="margin-top:7px">
								<input type="text" id="product_num" value="" readonly>
							</div>
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" >
        					<input type="button"  style="margin-left:10px;margin-top:10px" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['copy']?>" onclick="copy_code(event)" />
							</div>
			            </div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function auto_code(){
	$("#product_num").val('');
	var brand_code=$("#brand_code").val();
	var category_type_id=$("#category_type_id").val();
	var category_id=$("#category_id").val();
	$.ajax({
		url:admin_webroot+"products/auto_code/",//访问的URL地址
		type:"POST",
		data: {'brand_code':brand_code,'category_type_id':category_type_id,'category_id':category_id},
		dataType:"json",
		success:function(data){
			try{
				if(data.flag == 1){
					var product_code = data.code.trim();
					$("#product_num").val(product_code);
				}else{
					alert(data.code);
				}
			}catch(e){
				alert(j_object_transform_failed);
				alert(data);
			}
		}
	});
}

function copy_code(ev){
	var code = document.getElementById('product_num').value;
	if(code.trim()==""){
		return false;
	}
	if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){
	   	var event= ev || window.event;
	   	alert("<?php echo $ld['do_not_copy']; ?>");
	}
	else{
	    window.clipboardData.setData("Text",code);
	    alert(j_replicate_successfully);
	}
}
</script>



