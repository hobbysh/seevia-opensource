<style>
    .am-radio, .am-checkbox{display: inline-block;margin-top:0px;}
    .am-checkbox input[type="checkbox"]{margin-left:0px;}
    .am-form-label{font-weight:bold;}
    .a1{width:100px;}
</style>
<div>
	<div>
		<ul class="am-avg-lg-4 am-avg-md-2 am-avg-sm-1 am-form am-form-horizontal">
			<li style="margin-bottom:10px;">
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					<div class="am-dropdown checkbox" data-am-dropdown  id="checks_boxs">
					  	<button class="am-btn am-btn-default am-dropdown-toggle a1"  id="reffer_name" data-am-dropdown-toggle>
							<?php echo $ld['order_reffer']?><span class="am-icon-caret-down"></span>
						</button>
					  	<ul class="am-dropdown-content am-avg-lg-1 am-avg-md-1 am-avg-sm-1">
							<?php $i=0; foreach($order_type as $k=>$v){$i++;?>
								<li class="dd0" style="margin-left:10px;">
									<div class='bb00 check<?php echo $i; ?>'>
										<label class="am-checkbox am-success" style="padding-top:0px;">
											<input type="checkbox" class = 'checkbox' id="checkbox1" value="0" name="<?php echo $k; ?>"  data-am-ucheck /><span><?php echo $order_type_arr[$k];?></span>
										</label>
									</div>
								</li>
								<?php foreach ($v as $kk=>$vv) {?>
									<li class="dd0" style="margin-left:20px;">
										<label class="am-checkbox am-success" style="padding-top:0px;">
											<input type="checkbox" class = 'checkbox1' data-am-ucheck   value="<?php echo $k.":".$kk; ?>" name="box" <?php if(in_array($k.":".$kk,$type_arr)) echo 'checked';?>><span><?php echo $vv?></span>
										</label>
									</li>	
								<?php }?>
								<li class="dd1">
							    	<div class="am-form-group" style="margin:10px 0px;padding-left:5px;">
	                                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                                        <label class="am-checkbox am-success" style="padding-top:0px;">
	                                            <input type="checkbox" id="selected" class="bb2" data-am-ucheck />
	                                            <?php echo $ld['select_all']?>
	                                        </label>
	                                    </div>
		                                <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
											<input type="button" class="btn am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['submit']?>" onclick="ck_checkbox()" />
	                                    </div>
	                                </div>
							    </li>		
							<?php }?>
						    
					  	</ul>
					</div>
				</div>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					<div class="checkbox checkboxie6" id = 'y2'>
						<div class="am-dropdown" data-am-dropdown  id="check_box"> 
						  	<button class="am-btn am-btn-default am-dropdown-toggle a1" data-am-dropdown-toggle>
								<?php echo $ld['brand']?> <span class="am-icon-caret-down">
							</button>
						  	<ul class="am-dropdown-content am-avg-lg-1 am-avg-md-1 am-avg-sm-1" style="height:300px; overflow:auto;">
							    <li class="bb0"  style="margin-left:10px;">
									<label class="am-checkbox am-success" style="padding-top:0px;;">
										<input type="checkbox" class = 'checkbox' value="0" data-am-ucheck  name="box2" <?php if(in_array('0',$brand_ids)) echo "checked";?>>
										<?php echo $ld['unknown_brand']?>
									</label>
								</li>
								<?php foreach($bran_sel as $k=>$v){?>
							    	<li class="bb0">
							    		<label class="am-checkbox am-success" style="margin-left:10px;padding-top:0px;">
											<input type="checkbox" class = 'checkbox' value="<?php echo $v['Brand']['id'];?>" name="box2" data-am-ucheck  <?php if(in_array($v['Brand']['id'],$brand_ids)) echo "checked";?>>
											<?php echo $v['BrandI18n']['name'];?>
										</label>
							    	</li>
							    <?php }?>
							    <li class="bb1">
	                                <div class="am-form-group" style="margin:5px 0px;padding-left:5px;">
	                                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                                        <label class="am-checkbox am-success" style="padding-top:0px;">
	                                            <input type="checkbox" id="select" class="bb2" data-am-ucheck />
	                                            <?php echo $ld['select_all']?>
	                                        </label>
	                                    </div>
	                                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                                        <input type="button" id="" class="btn am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['submit']?>" onclick="checkbox2()" />
	                                    </div>
	                                </div>
	                            </li>
						  	</ul>
						</div>
					</div>
				</div>
			</li>
									
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['time_of_payment']?></label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="text"  name="start_date" value="<?php echo $start_date;?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding-top:7px;">-</div>	
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="text" name="end_date" value="<?php echo $end_date;?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"/>
				</div>
			</li>
			
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3  am-form-label"><?php echo $ld['keyword']?></label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
					<input type="text" name="keywords" id="keywords" value="<?php echo @$keywords?>"/>
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
					<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['search']?>" onclick='form_st()'/>
				</div>
			</li>
		</ul>
	</div>
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php
		  	if($svshow->operator_privilege('product_sale_statements_detail')) { echo $html->link($ld['view_all_commodity_statistics'],"/product_sale_statements/",array("class"=>"am-btn am-btn-default","target"=>"_blank",'escape'=>false)).'&nbsp;'; 
		}?>
		<?php
			if($svshow->operator_privilege('product_sale_statements_detail')){ echo $html->link($ld['view_all_goods_subsidiary'],"/product_sale_statements/all_detail/?st=".$start_date."&ed=".$end_date."&ta=".urlencode($ta_str),array("class"=>"am-btn am-btn-default","target"=>"_blank",'escape'=>false)); 
		}?>
	</div>
	<div class="am-panel-group am-panel-tree" id="accordion">
        <!--标题栏-->
        <div class="am-panel am-panel-default am-panel-header">
            <div class="am-panel-hd">
                <div class="am-panel-title">
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $ld['category_name']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['sales_quantity']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['sales']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<!--一级 菜单-->			
		<?php if(isset($categories_tree) && sizeof($categories_tree)>0){foreach($categories_tree as $k=>$v){?>
			<div>
				<div class="am-panel am-panel-default am-panel-body" >
                	<div class="am-panel-bd fuji">
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<span data-am-collapse="{parent: '#accordion', target:'#statements_<?php echo $v['CategoryProduct']['id']?>'}" class="<?php echo (isset($v['SubCategory'])&&!empty($v['SubCategory']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;
							<label><?php echo $v['CategoryProductI18n']['name'];?></label>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
							<?php
								if(isset($categories_sum_format[$v['CategoryProduct']['id']]['sum_quantity'])){
									echo $categories_sum_format[$v['CategoryProduct']['id']]['sum_quantity'];
								}else{
									echo "0";
								}
							?>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
							<?php
								if(isset($categories_sum_format[$v['CategoryProduct']['id']]['sum_price'])){
									echo $categories_sum_format[$v['CategoryProduct']['id']]['sum_price'];
								}else{
									echo "0";
							}?>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
							<?php
				      			if($svshow->operator_privilege("product_sale_statements_edit")){
				          		 echo $html->link($ld['view_details'],"/product_sale_statements/?tds=pc,pn,pp,sn,sp,&cate=".$v['CategoryProduct']['id']."&st=".$start_date."&ed=".$end_date."&ta=".urlencode($ta_str),array("class"=>"am-btn am-btn-default am-btn-xs","target"=>"_blank",'escape' => false));}?>
						</div>
						<div style="clear:both;"></div>
					</div>
					<!--二级 菜单-->
					<?php if(isset($v['SubCategory']) && sizeof($v['SubCategory'])>0){foreach($v['SubCategory'] as $kk=>$vv){?>
						<div>
							<div class="am-panel-collapse am-collapse am-panel-child" id="statements_<?php echo $v['CategoryProduct']['id']?>">
								<div class="am-panel-bd am-panel-childbd">
									<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
										<span  style="margin-left:20px;" data-am-collapse="{parent: '#statements_<?php echo $v['CategoryProduct']['id']?>', target:'#statementsss_<?php echo $vv['CategoryProduct']['id']?>'}" class="<?php echo (isset($vv['SubCategory']) && !empty($vv['SubCategory']))?"am-icon-plus":"am-icon-minus";?>" ></span>&nbsp;
										<label><?php echo $vv['CategoryProductI18n']['name'];?></label>
									</div>
									<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
										<?php if(isset($categories_sum_format[$vv['CategoryProduct']['id']]['sum_quantity'])){echo 												$categories_sum_format[$vv['CategoryProduct']['id']]['sum_quantity'];
											}else{echo "0";	}
										?>	
									</div>
									<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
										<?php if(isset($categories_sum_format[$vv['CategoryProduct']['id']]['sum_price'])){
											echo $categories_sum_format[$vv['CategoryProduct']['id']]['sum_price'];
											}else{echo "0";}
										?>
									</div>
									<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
										<?php if($svshow->operator_privilege("product_sale_statements_edit")){
											echo $html->link($ld['view_details'],"/product_sale_statements/?tds=pc,pn,pp,sn,sp,&cate=".$vv['CategoryProduct']['id']."&st=".$start_date."&ed=".$end_date."&ta=".urlencode($ta_str),array("class"=>"am-btn am-btn-default am-btn-xs","target"=>"_blank",'escape' => false));
										}?>
									</div>
									<div style="clear:both;"></div>
								</div>
								<!--三级 菜单-->
								<?php if(isset($vv['SubCategory']) && sizeof($vv['SubCategory'])>0){foreach($vv['SubCategory'] as $kkk=>$vvv){?>
									<div>	
										<div class="am-panel-collapse am-collapse am-panel-subchild" id="statementsss_<?php echo $vv['CategoryProduct']['id']?>">
											<div class="am-panel-bd am-panel-childbd">
												<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
													<label style="margin-left:50px;"><?php echo $vvv['CategoryProductI18n']['name'];?></label>
												</div>
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
													<?php
														if(isset($categories_sum_format[$vvv['CategoryProduct']['id']]['sum_quantity'])){
															echo $categories_sum_format[$vvv['CategoryProduct']['id']]['sum_quantity'];
														}else{
															echo "0";
														}
													?>
												</div>
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
													<?php if(isset($categories_sum_format[$vvv['CategoryProduct']['id']]['sum_price'])){
														echo $categories_sum_format[$vvv['CategoryProduct']['id']]['sum_price'];
													}else{
														echo "0";
													}?>
					
												</div>
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
													<?php
												        if($svshow->operator_privilege("product_sale_statements_edit")){
											         	echo $html->link($ld['view_details'],"/product_sale_statements/?tds=pc,pn,pp,sn,sp,&cate=".$vvv['CategoryProduct']['id']."&st=".$start_date."&ed=".$end_date."&ta=".urlencode($ta_str),array('style'=>'text-decoration:underline;color:green;',"target"=>"_blank",'escape' => false));
													}?>
												</div>
												<div style="clear:both;"></div>							
											</div>
										</div>
									</div>
								<?php }}?>
							</div>
						</div>
					<?php }}?>
				</div>
			</div>
		<?php }?>	
		<div>
			<div class="am-panel am-panel-default am-panel-body">
				<div class="am-panel-bd">
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"></span><label><?php echo $ld['order_total'];?></label></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
						<?php
							if(isset($categories_sum_format[0]['sum_quantity'])){
								echo $categories_sum_format[0]['sum_quantity'];
							}else{
								echo "0";
							}
						?>
					</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
						<?php
							if(isset($categories_sum_format[0]['sum_quantity'])){
								echo $categories_sum_format[0]['sum_quantity'];
							}else{
								echo "0";
							}
						?>
					</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">&nbsp;</div>
					<div style="clear:both;"></div>	
				</div>
			</div>
		</div>
		<?php }else{ ?>
			<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
		<?php }?>
	</div>		
</div>

<script type="text/javascript">
<?php $ua = $_SERVER["HTTP_USER_AGENT"];if(preg_match("/Firefox/", $ua)){?>

window.onkeydown=function(e){
	if(e.keyCode==13){
		form_st();
		return false;
	}
}
<?php }else{?>
　　document.onkeypress=function(e)
　　{
　　var code;
　　if  (!e)
　　{
　　var e=window.event;
　　}
　　if(e.keyCode)
　　{
　　code=e.keyCode;
　　}
　　else if(e.which)
　　{
　　code   =   e.which;
　　}
　　if(code==13)

　　{
		form_st();
		return false;
　　}
　　}
<?php }?>



	function form_st(){
		var str=get_str();
		window.location.href=encodeURI("/admin/product_sale_category_statements/"+str);
	}
	function get_str(){
		//订单来源
		var ta = ck_checkbox();
		//品牌
		var pp = checkbox2();

		//关键字
		var kw = document.getElementById("keywords").value;
		//时间
		var st = document.getElementsByName('start_date')[0];
		var ed = document.getElementsByName('end_date')[0];

		var str = '?';
	//	str+="tds=pc,pn,pp,sn,sp,";
		if(kw != '')
			str +="&"+"kw=" +kw;
		if(pp != '')
			str +="&"+"pp=" +pp.substring(pp,pp.length-1);
		if(ta != '')
			str +="&"+"ta=" +ta.substring(ta,ta.length-1);

		str +="&"+"st="+st.value;
		str +="&"+"ed="+ed.value;
		//		alert(str);
		return str;
	}



	function checkbox2(){
	    var dropdown = $('#check_box'),
	        data = dropdown.data('amui.dropdown');
	    if(data.active){
	        dropdown.dropdown('close');
	    }
	    var str=document.getElementsByName("box2");
	    var leng=str.length;
	    var chestr="";
	    for(var i=0;i<leng;i++){
	        if(str[i].checked == true)
	        {
	            chestr+=str[i].value+",";
	        };
	        //    alert(chestr);
	    };
	    return chestr;
	}
	
	
	function ck_checkbox(){
	    var dropdown = $('#checks_boxs'),
	        data = dropdown.data('amui.dropdown');
	    if(data.active){
	        dropdown.dropdown('close');
	    }
	    var str=document.getElementsByName("box");
	    var leng=str.length;
	    var chestr="";
	    for(var i=0;i<leng;i++){
	        if(str[i].checked == true)
	        {
	            chestr+=str[i].value+",";
	        };
	    };
	    return chestr;
	}

	var all=$('#y2 .a1');
	bll=$('#y2 .b1');
	cll=$('#y2 .btn');
	allclick = function(){
	if(bll.getAttribute("class")!="b1"){bll.removeClass('c1');all.removeClass('up');
	}
	else{bll.addClass('c1');all.addClass('up');}
	};
	removeclick = function(){
		all.removeClass('up');
		bll.removeClass('c1');
	};

	var checkbox = $('#y2 .y1 .checkbox');
		select = $('#y2 .y1 #select');
		$("#select").click(function(){
			$(".bb0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
		});
		$("#checkbox1").click(function(){
			$(".dd0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
		});
	$("#selected").click(function(){
			$(".dd0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
		});
		

</script>