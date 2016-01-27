<style type="text/css">
	.am-form-label{font-weight:bold;}
	.am-panel-title{font-weight:bold;}
    .a1{width:100px;}
</style>
<div>
	<?php echo $form->create('ProductSaleStatement',array('action'=>'/all_detail/','name'=>"SearchForm","type"=>"get","class"=>"am-form am-form-horizontal" ));?>
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
			<li style="margin-bottom:10px;">
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
					<div class="am-dropdown checkbox" data-am-dropdown  id="check_box1">
					  	<button class="am-btn am-btn-default am-dropdown-toggle a1"  id="reffer_name" data-am-dropdown-toggle>
							<?php echo $ld['order_reffer']?><span class="am-icon-caret-down"></span>
						</button>
					  	<ul class="am-dropdown-content am-avg-lg-1 am-avg-md-1 am-avg-sm-1">
							<?php $i=0; foreach($order_type as $k=>$v){$i++;?>
								<li class="aa0" style="margin-left:10px;">
									<div class='bb00 check<?php echo $i; ?>'>
										<label class="am-checkbox am-success" style="padding-top:0px;">
											<input type="checkbox" class = 'checkbox' id="checkbox1" value="0" name="<?php echo $k; ?>"  data-am-ucheck /><span><?php echo $order_type_arr[$k];?></span>
										</label>
									</div>
								</li>
								<?php foreach ($v as $kk=>$vv) {?>
									<li class="aa0" style="margin-left:20px;">
										<label class="am-checkbox am-success" style="padding-top:0px;">
											<input type="checkbox" class = 'checkbox1' data-am-ucheck   value="<?php echo $k.":".$kk; ?>" name="box1" <?php if(in_array($k.":".$kk,$type_arr)) echo 'checked';?>><span><?php echo $vv?></span>
										</label>
									</li>	
								<?php }?>
								<li class="dd1">
							    	<div class="am-form-group" style="margin:10px 0px;padding-left:5px;">
	                                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                                        <label class="am-checkbox am-success" style="padding-top:0px;">
	                                            <input type="checkbox" id="select1" class="bb2" data-am-ucheck />
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
							
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
					<div class="am-dropdown" data-am-dropdown id="check_box2">
					  	<button class="am-btn am-btn-default am-dropdown-toggle a1" data-am-dropdown-toggle>
							<?php echo $ld['brand']?><span class="am-icon-caret-down"></span>
						</button>
					  	<ul class="am-dropdown-content am-avg-lg-1 am-avg-md-1 am-avg-sm-1" style="height:300px; overflow:auto;">
						    <li class="bb0" style="margin-left:10px;">
								<label class="am-checkbox am-success" style="padding-top:0px;">
									<input type="checkbox" class = 'checkbox' data-am-ucheck  value="0" name="box2" <?php if(in_array('0',$brand_ids)) echo "checked";?>>
									<?php echo $ld['unknown_brand']?>
								</label>
							</li>
							
							<?php foreach($bran_sel as $k=>$v){?>
							    <li class="bb0" style="margin-left:10px;">
									<label class="am-checkbox am-success" style="padding-top:0px;">
										<input type="checkbox" class = 'checkbox' data-am-ucheck  value="<?php echo $v['Brand']['id'];?>" name="box2" <?php if(in_array($v['Brand']['id'],$brand_ids)) echo "checked";?>>
										<?php echo $v['BrandI18n']['name'];?>
									</label>
								</li>
							<?php }?>
									
						    <li style="margin-left:10px;">
						    	<div class="am-form-group" style="margin:5px 0px;padding-left:5px;">
                                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
										<label class="am-checkbox am-success" style="padding-top:0px;">
											<input type="checkbox" id="select2" class="bb2"  data-am-ucheck />
											<?php echo $ld['select_all']?>
										</label>
									</div>
                                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
										<input type="button" class="btn am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['submit']?>" onclick="checkbox2()" />
									</div>
						    </li>
					  	</ul>
					</div>
				</div>
								
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
					<div class="am-dropdown" data-am-dropdown id="check_box4">
					  	<button class="am-btn am-btn-default am-dropdown-toggle a1" data-am-dropdown-toggle>
							<?php echo $ld['classification']?><span class="am-icon-caret-down"></span>
						</button>
					  	<ul class="am-dropdown-content am-avg-lg-1 am-avg-md-1 am-avg-sm-1">
							<?php $i=0;foreach($category_type_tree as $first_k=>$first_v){?>
							    <li class="dd0" style="margin-left:10px;">
									<label class="am-checkbox am-success" style="padding-top:0px;">
										<input type="checkbox" class = 'checkbox4' data-am-ucheck  value="<?php echo $first_v['CategoryType']['id'];?>" name="box_one[]" <?php if(isset($cat) && in_array($first_v['CategoryType']['id'],$cat)) echo "checked";?>>
										<?php echo $first_v['CategoryTypeI18n']['name'];?>
									</label>
									<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
										<ul class="am-avg-lg-1 am-avg-md-1 am-avg-sm-1">
											<?php foreach ($first_v['SubCategory'] as $second_k=>$second_v) {?>
										    	<li class="dd0" style="margin-left:20px;">
										    		<label class="am-checkbox am-success" style="padding-top:0px;">
														<input type="checkbox" class = 'checkboxd' data-am-ucheck  value="<?php echo $second_v['CategoryType']['id'];?>" name="box4" <?php if(isset($cat) && in_array($second_v['CategoryType']['id'],$cat)) echo "checked";?>><span><?php echo $second_v['CategoryTypeI18n']['name'];?></span>
													</label>
										    		<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
										    			<ul class="am-avg-lg-1 am-avg-md-1 am-avg-sm-1">
															<?php foreach ($second_v['SubCategory'] as $third_k=>$third_v) {?>
												    			<li class="dd0" style="margin-left:35px;">
																	<label class="am-checkbox am-success" style="padding-top:0px;">
																		<input type="checkbox" class = 'checkboxdd' data-am-ucheck  value="<?php echo $third_v['CategoryType']['id'];?>" name="box4" <?php if(isset($cat) && in_array($third_v['CategoryType']['id'],$cat)) echo "checked";?>><span><?php echo $third_v['CategoryTypeI18n']['name'];?></span>
																	</label>
																</li>
															<?php }?>
														</ul>
													<?php }?>
												</li>
											<?php }?>
										</ul>		
									<?php }?>
								</li>
							<?php }?>
								
							<li>
								<div class="am-form-group" style="margin:5px 0px;padding-left:5px;">
                                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
										<label class="am-checkbox am-success" style="padding-top:0px;">
											<input type="checkbox" id="select4" data-am-ucheck  class="bb2" />
											<?php echo $ld['select_all']?>
										</label>
									</div>
                                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
										<input type="button" class="btn am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['submit']?>" onclick="checkbox4()" />
									</div>
								</div>	
							</li>
					  	</ul>
					</div>
				</div>
			</li>

			<li style="margin-bottom:10px;">
				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['time_of_payment']?></label>
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
					<input type="text" name="start_date" value="<?php echo $start_date;?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center">-</div>	
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
					<input type="text" name="end_date" value="<?php echo $end_date;?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['order_code']?></label>
				<div class="am-u-lg-9 am-u-md-6 am-u-sm-9">
					<input type="text" name="order_code" onkeypress="if(event.keyCode==13)formsubmit()" id="order_code" value="<?php echo @$keywords?>"/>
				</div>
			</li>
				
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['keyword']?></label>
				<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
					<input type="text" name="keywords" onkeypress="if(event.keyCode==13)formsubmit()" id="keywords" value="<?php echo @$keywords?>" placeholder="<?php echo $ld['product_name']?>"/>
				</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
					<input type="button" onclick='formsubmit()' class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['search']?>" />
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
					<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['export']?>" onclick="export_act()" />
				</div>
			</li>
		</ul>
	<?php echo $form->end();?>
				
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php echo $html->link($ld['product_sales_reports'],"/product_sale_category_statements/",array("class"=>"am-btn am-btn-default ","target"=>"_blank",'escape'=>false)).'&nbsp;';    ?>
		<?php echo $html->link($ld['view_all_goods_subsidiary'],"/product_sale_statements/",array("class"=>"am-btn am-btn-default ","target"=>"_blank",'escape' => false));?>
	</div>
	
	<div class="am-panel-group am-panel-tree">
		<div class="am-panel am-panel-default am-panel-header">
		    <div class="am-panel-hd">
		        <div class="am-panel-title">
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['order_reffer']?></div>
					<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><?php echo $ld['order_code']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['time_of_payment']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['product_code']?></div>
					<div class="am-u-lg-1 am-u-md-5 am-u-sm-5"><?php echo $ld['product_name']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['category']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['brand']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['sales_quantity']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['marked_price']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['discount_rate']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['price']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['paymengts']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($order_list) && sizeof($order_list)){foreach($order_list as $k=>$v){?>
			<div>
				<div class="am-panel am-panel-default am-panel-body">
					<div class="am-panel-bd">
						<div class="am-u-lg-1 am-show-lg-only">
							<?php echo $order_type_arr[$v['Order']['type']];//pr($order_type_arr);
						  echo " - ";
					      echo isset($order_type_arr[$v['Order']['type_id']])?$order_type_arr[$v['Order']['type_id']]:$v['Order']['type_id'];
					      //pr($order_type_arr);
					      ?>&nbsp;
						</div>
						<div class="am-u-lg-1 am-u-md-3 am-u-sm-3" style="word-wrap:break-word;word-break:normal;">
							<?php echo $v['Order']['order_code']?>
							<a style="border-bottom: 1px solid #21964D;display:inline-block;" href='/admin/orders/view/<?php echo $v["Order"]["id"]?>' target="_blank">
								<?php echo $ld['view_station']?>
							</a>
							<?php if($v['Order']['type']=='taobao'){?>
								<a style="border-bottom: 1px solid #21964D;" href='http://trade.taobao.com/trade/detail/trade_item_detail.htm?bizOrderId=<?php echo $v["Order"]["order_code"]?>' target='_blank'><?php echo $ld['view_taobao']?></a>
							<?php }?>&nbsp;
							  
						</div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['Order']['payment_time']?>&nbsp;</div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['OrderProduct']['product_code']?>&nbsp;</div>
						<div class="am-u-lg-1 am-u-md-5 am-u-sm-5" style="word-wrap:break-word;word-break:normal;">
							<?php echo $v['OrderProduct']['product_name']?>&nbsp;
						</div>
						<div class="am-u-lg-1 am-show-lg-only">
							<?php echo isset($category_type_names[$v['Product']['category_type_id']])?$category_type_names[$v['Product']['category_type_id']]:''?>&nbsp;
						</div>
						<div class="am-u-lg-1 am-show-lg-only">
							<?php echo isset($brand_names[$v['Product']['brand_id']])?$brand_names[$v['Product']['brand_id']]:''?>&nbsp;	
						</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 num">
							<?php echo $v['OrderProduct']['product_quntity']-$v['OrderProduct']['refund_quantity']?>&nbsp;	
						</div>
						<div class="am-u-lg-1 am-show-lg-only">
							<?php echo ($v['OrderProduct']['product_price'])*($v['OrderProduct']['product_quntity']-$v['OrderProduct']['refund_quantity'])?>&nbsp;	
						</div>
						<div class="am-u-lg-1 am-show-lg-only">
							<?php echo @sprintf("%01.2f",((1+$v['OrderProduct']['adjust_fee']/(($v['OrderProduct']['product_price'])*($v['OrderProduct']['product_quntity']-$v['OrderProduct']['refund_quantity'])))*100))?>%	&nbsp;
						</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 price">
							<?php echo $v['OrderProduct']['product_price']*($v['OrderProduct']['product_quntity']-$v['OrderProduct']['refund_quantity'])+$v['OrderProduct']['adjust_fee']?>	&nbsp;
						</div>
						<div class="am-u-lg-1 am-show-lg-only">
							<?php echo isset($payment_names[$v['Order']['payment_id']])?$payment_names[$v['Order']['payment_id']]:''?>	&nbsp;
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		<?php }}else{?>
			<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
		<?php }?>
		
		<?php if(isset($order_list) && sizeof($order_list)){?>
			<div>
				<div class="am-panel am-panel-default am-panel-body">
					<div class="am-panel-bd">
						<div class="am-u-lg-1 am-u-md-5 am-u-sm-5"><?php echo $ld['order_total']?></div>
						<div class="am-u-lg-1 am-show-lg-only">&nbsp;</div>
						<div class="am-u-lg-1 am-show-lg-only">&nbsp;</div>
						<div class="am-u-lg-1 am-u-md-3 am-u-sm-2">&nbsp;</div>
						<div class="am-u-lg-1 am-show-lg-only">&nbsp;</div>
						<div class="am-u-lg-1 am-show-lg-only">&nbsp;</div>
						<div class="am-u-lg-1 am-show-lg-only">&nbsp;</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><span id="total_num">0</span>&nbsp;</div>
						<div class="am-u-lg-1 am-show-lg-only">&nbsp;</div>
						<div class="am-u-lg-1 am-show-lg-only">&nbsp;</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><span id="total_price">0</span>&nbsp;</div>
						<div class="am-u-lg-1 am-show-lg-only">&nbsp;</div>
						<div style="clear:both;"></div>		
					</div>
				</div>
			</div>
		<?php }?>
	</div>
	<?php if(isset($order_list) && sizeof($order_list)){?>
		<div id="btnouterlist" class="btnouterlist noprint"><?php echo $this->element('pagers')?> </div>
	<?php }?>
</div>


<script type="text/javascript">
window.onload=function(){ 
	var num =$(".num");
	var price =$(".price");
	var total_num=0;
	var total_price=0;
	for( var i=0;i<num.length;i++){
		total_num+=parseFloat(num[i].innerHTML);
	}
	for( var j=0;j<price.length;j++){
		total_price+=parseFloat(price[j].innerHTML);
	}
//	alert(total_num);
//	alert(total_price);
	$("#total_num").html(total_num);
	$("#total_price").html(total_price);
}


	function formsubmit(){
		var str=get_str();
		window.location.href=encodeURI("/admin/product_sale_statements/all_detail/"+str);
	}
	function export_act(){
		var str=get_str();
		url ="/admin/product_sale_statements/all_detail/export/"+str;
		window.location.href=encodeURI(url);
	}
	function get_str(){
		//订单来源2
		var ta = ck_checkbox();
		ta = ta.substring(ta,ta.length-1);
		//品牌
		//var pp = checkbox2();
		//类目
		<?php if(!empty($ct)){?>
		var lm=checkbox4();
		<?php }?>

		//关键字
		var kw = document.getElementById("keywords").value;
		//时间
		var st = document.getElementsByName('start_date')[0];
		var ed = document.getElementsByName('end_date')[0];
		//分类
//		var cns = document.getElementById("cat").value;
		//订单号
		var oc = document.getElementById("order_code").value;
		var str = '?';
		if(kw != '')
			str +="&"+"kw=" +kw;
		
		if(ta != '')
			str +="&"+"ta=" +ta;
		if(oc != '')
			str +="&"+"oc=" +oc;


		str +="&"+"st="+st.value;
		
		str +="&"+"ed="+ed.value;
		<?php if(!empty($ct)){?>
		if(lm != '')
			str +="&"+"lm=" +lm;
		<?php }?>
		return str;
	}
	
	//全选
	//a 订单来源
	$("#select1").click(function(){
		$(".aa0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
	});
	$("#checkbox1").click(function(){
		$(".aa0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
	});
	
	//b 品牌
	$("#select2").click(function(){
		$(".bb0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
	});
	//d 分类
	$("#select4").click(function(){
		$(".dd0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
	});
	$(".checkbox4").click(function(){
		$(this).parent().parent().find(".checkboxd").prop("checked",$(this).prop('checked'));
		$(this).parent().parent().find(".checkboxdd").prop("checked",$(this).prop('checked'));
	});
	
	
	//下拉收起
	//a 订单来源
	function ck_checkbox(){
	    var dropdown = $('#check_box1'),
	        data = dropdown.data('amui.dropdown');
	    if(data.active){
	        dropdown.dropdown('close');
	    }
	    var str=document.getElementsByName("box1");
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
	//品牌
	function checkbox2(){
	    var dropdown = $('#check_box2'),
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
	    };
	    return chestr;
	}

	//类目
	<?php if(!empty($ct)){?>
		function checkbox4(){
		    var dropdown = $('#check_box4'),
		        data = dropdown.data('amui.dropdown');
		    if(data.active){
		        dropdown.dropdown('close');
		    }
		    var str=document.getElementsByName("box4");
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
	<?php }?>
</script>