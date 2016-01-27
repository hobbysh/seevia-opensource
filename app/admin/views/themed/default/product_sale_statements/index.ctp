<style>
    .am-radio, .am-checkbox{display: inline-block;margin-top:0px;}
    .am-checkbox input[type="checkbox"]{margin-left:0px;}
	.am-form-label{font-weight:bold;}
	.am-panel-title{font-weight:bold;}
    .a1{width:100px;}
    td{height:45px;}
    th{height:40px;}
</style>
<div>
	<form id="SearchForm" accept-charset="utf-8" action="/admin/product_sale_statements/" method="get" enctype="multipart/form-data" name="SearchForm" class="am-form am-form-horizontal" >
		<div style="margin-bottom:15px;">
			<label class="am-checkbox am-success" style="padding-top:0px;">
				<input type="checkbox" id="pc" name="tds[]" data-am-ucheck  value="pc" <?php if(in_array('pc',$tds)) echo "checked='checked'" ?> /><?php echo $ld['product_code']?></label>&nbsp;&nbsp;&nbsp;
			<label class="am-checkbox am-success" style="padding-top:0px;">
				<input type="checkbox" id="pn" name="tds[]" data-am-ucheck  value="pn" <?php if(in_array('pn',$tds)) echo "checked='checked'" ?> /><?php echo $ld['product_name']?></label>&nbsp;&nbsp;&nbsp;
			<label class="am-checkbox am-success" style="padding-top:0px;">
				<input type="checkbox" id="pp" name="tds[]" data-am-ucheck  value="pp" <?php if(in_array('pp',$tds)) echo "checked='checked'" ?> /><?php echo $ld['brand']?></label>&nbsp;&nbsp;&nbsp;
			<label class="am-checkbox am-success" style="padding-top:0px;">
				<input type="checkbox" id="sn" name="tds[]" data-am-ucheck  value="sn" <?php if(in_array('sn',$tds)) echo "checked='checked'" ?> /><?php echo $ld['sales_quantity']?></label>&nbsp;&nbsp;&nbsp;
			<label class="am-checkbox am-success" style="padding-top:0px;">
				<input type="checkbox" id="sp" name="tds[]" data-am-ucheck  value="sp" <?php if(in_array('sp',$tds)) echo "checked='checked'" ?> /><?php echo $ld['price']?></label>
		</div>
		<div>
			<ul class="am-avg-lg-3 am-avg-md-3 am-avg-sm-2">
				<li style="margin-bottom:15px;">
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					<!--订单来源 下拉-->
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
								
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					<!--品牌 下拉-->
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
									</div>
							    </li>
						  	</ul>
						</div>
					</div>
				</li>
						
				<li style="margin-bottom:15px;">
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					<!--分类1 下拉-->
						<div class="am-dropdown" data-am-dropdown id="check_box3">
						  	<button class="am-btn am-btn-default am-dropdown-toggle a1" data-am-dropdown-toggle>
								<?php echo $ld['classification']?><span class="am-icon-caret-down"></span>
							</button>
						  	<ul class="am-dropdown-content am-avg-lg-1 am-avg-md-1 am-avg-sm-1" style="height:300px; overflow:auto;">
							    <li class="cc0" style="margin-left:10px;" >
									<label class="am-checkbox am-success" style="padding-top:0px;">
										<input type="checkbox" class = 'checkbox' data-am-ucheck  value="0" name="box3" <?php if(in_array('0',$selected_categories)) echo "checked";?>>
										<?php echo $ld['unknown_classification']?>
									</label>
								</li>
								<?php $i=0; foreach($category_tree as $k=>$v){$i++; ?>
									    <li class="cc0" style="margin-left:10px;">
											<label class="am-checkbox am-success" style="padding-top:0px;">
												<input type="checkbox" class = 'checkbox3'  data-am-ucheck  value="<?php echo $v['CategoryProduct']['id'];?>" name="box3" <?php if(in_array($v['CategoryProduct']['id'],$selected_categories)) echo "checked";?>/>
												<span>
													<?php echo $v['CategoryProductI18n']['name'];?>
												</span>
											</label>
									<?php if(isset($v['SubCategory']) && sizeof($v['SubCategory'])>0){?>
										<ul class="am-avg-lg-1 am-avg-md-1 am-avg-sm-1">
											<?php foreach($v['SubCategory'] as $kk=>$vv){?>
												<li class="cc0" style="margin-left:25px;">
													<label class="am-checkbox am-success" style="padding-top:0px;">
														<input type="checkbox" class = 'checkboxc' data-am-ucheck  value="<?php echo $vv['CategoryProduct']['id']?>" name="box3" <?php if(in_array($vv['CategoryProduct']['id'],$selected_categories)) echo "checked";?>/>
														<?php echo $vv['CategoryProductI18n']['name'];?>
													</label>
													<?php if(isset($vv['SubCategory']) && sizeof($vv['SubCategory'])>0){?>
														<ul class="am-avg-lg-1 am-avg-md-1 am-avg-sm-1">
															<?php foreach($vv['SubCategory'] as $kkk=>$vvv){?>
																<li class="cc0" style="margin-left:40px;">	
																	<label class="am-checkbox am-success" style="padding-top:0px;">
																		<input type="checkbox" class = 'checkboxcc' data-am-ucheck  value="<?php echo $vvv['CategoryProduct']['id']?>" name="box3" <?php if(in_array($vvv['CategoryProduct']['id'],$selected_categories)) echo "checked";?>/>
																		<?php echo $vvv['CategoryProductI18n']['name'];?>
																	</label>
																</li>
															<?php }?>
														</ul>
													<?php }?>
												</li>
											<?php }?>
										</ul>
									<?php }?>
								<?php }?>
								
							    <li>
							    	<div class="am-form-group" style="margin:5px 0px;padding-left:5px;">
	                                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
									    	<label class="am-checkbox am-success" style="padding-top:0px;">
									    		<input type="checkbox" id="select3" data-am-ucheck  class="bb2" />
												<?php echo $ld['select_all']?>
											</label>
										</div>
	                                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
											<input type="button" class="btn am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['submit']?>" onclick="checkbox3()" />
										</div>
									</div>
							    </li>
						  	</ul>
						</div>
					</div>
									
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					
					<!--分类2 下拉-->
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
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['time_of_payment']?></label>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
						<input type="text"  name="start_date" value="<?php echo $start_date;?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
					</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-center" style="font-weight:normal;margin-left:0px;">-</div>	
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
						<input type="text"  name="end_date" value="<?php echo $end_date;?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"/>
					</div>
				</li>
						
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-left"><?php echo $ld['keyword']?></label>
					<div class="am-u-lg-5 am-u-md-7 am-u-sm-6">
						<input type="text" name="keywords" id="keywords" value="<?php echo @$keywords?>" placeholder="名称/货号"/>
					</div>
					<div class="am-u-lg-4 am-u-md-2 am-u-sm-1">
						<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['search']?>" onclick='form_st()'/>
					</div>
					
				</li>
						
				<li style="margin-bottom:10px;">
					<label class="am-hide-lg-only am-u-md-1 am-u-sm-1 am-form-label">&nbsp;</label>
					<div class="am-u-lg-4 am-u-md-5 am-u-sm-3">
						<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" id="daochu" value="<?php echo $ld['export']?>" onclick="export_act('placement')" />
					</div>
					<div class="am-u-lg-4 am-u-md-5 am-u-sm-3">
						<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['print']?>" onclick="print_act()" />
					</div>
				</li>
			</ul>
		</div>
		<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
			<?php echo $html->link($ld['product_sales_reports'],"/product_sale_category_statements/",array("class"=>"am-btn am-btn-default ","target"=>"_blank",'escape'=>false)).'&nbsp;';    ?>
			<?php  if($svshow->operator_privilege('product_sale_statements_detail')){echo $html->link($ld['view_all_goods_subsidiary'],"/product_sale_statements/all_detail/?st=".$start_date."&ed=".$end_date."&ta=".urlencode($ta_str),array("class"=>"am-btn am-btn-default ","target"=>"_blank",'escape' => false));}?>
		</div>
	</form>
</div>
<div id="tablelist">
	<table class="am-panel-group am-panel-tree" width="1320px;" >
		<thead class="am-panel am-panel-default am-panel-header">
		    <tr class="am-panel-hd">
		    		<?php if(in_array('pn',$tds)){?>
						<th style="padding-left:20px;"><?php echo $ld['product_name']?></th>
					<?php }?>
								
						<th><?php echo $ld['classification']?></th>
						
					<?php if(in_array('pp',$tds)){?>	
						<th><?php echo $ld['brand']?></th>
					<?php }?>	
					
					<?php if(in_array('sn',$tds)){?>		
						<th><?php echo $ld['sales_quantity']?></th>
					<?php }?>
							
					<?php if(in_array('sp',$tds)){?>				
						<th><?php echo $ld['sales']?></th>
					<?php }?>
									
					<th class="operate"><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		
		<?php if(isset($orderproducts_list) && sizeof($orderproducts_list)){foreach($orderproducts_list as $k=>$v){?>	
			<tbody>
				<tr style="border:1px solid #cccccc;">
						<?php if(in_array('pn',$tds)){?>
							<td style="padding-left:20px;">
								<?php echo $v['OrderProduct']['product_name'];
								if(in_array('pc',$tds)){
									echo '<br/>'.$ld['business_code'].':';
									echo $v['OrderProduct']['product_code'];
								}?>&nbsp;
							</td>
						<?php }?>
								
						<td><?php echo $v['OrderProduct']['category_name']?>&nbsp;</td>
							
						<?php if(in_array('pp',$tds)){?>
							<td><?php echo $v['OrderProduct']['brand_name']?>&nbsp;</td>
						<?php }?>
								
						<?php if(in_array('sn',$tds)){?>
							<td><?php echo $v['OrderProduct']['product_quntity_total']?>&nbsp;</td>
						<?php }?>
								
						<?php if(in_array('sp',$tds)){?>
							<td><?php echo $v['OrderProduct']['product_price_total']?>&nbsp;</td>
						<?php }?>
								
						<td class="operate am-btn-group-xs">
							<?php if($svshow->operator_privilege("product_sale_statements_see")){?> 
				  <a class="am-btn-default am-btn " href="<?php echo $html->url('/product_sale_statements/view/'.$v["OrderProduct"]["product_code"].'?st='.$start_date."&ed=".$end_date."&ta=".urlencode($ta_str)); ?>"><?php echo $ld['view_details'];?></a>
						<?php 	}?>
						</td>
					</tr>
				</div>
			</tbody>
		<?php }}else{?>
			<tr><td colspan="6"  class="no_data_found"><?php echo $ld['no_data_found']?></td></tr>
		<?php }?>
		<?php if(isset($orderproducts_list) && sizeof($orderproducts_list)){?>
			<tfoot>
				<tr style="border:1px solid #cccccc;">
						<td style="padding-left:20px;"><?php echo $ld['order_total']?></td>
						<?php if(in_array('pn',$tds)){?>
							<td>&nbsp;</td>
						<?php }?>
							
						<?php if(in_array('pp',$tds)){?>
							<td>&nbsp;</td>
						<?php }?>
					
						<?php if(in_array('sn',$tds)){?>
							<td><?php echo isset($quntity_total)?$quntity_total:'';?>&nbsp;</td>
						<?php }?>
							
						<?php if(in_array('sp',$tds)){?>
							<td><?php echo isset($price_total)?$price_total:'';?>&nbsp;</td>
						<?php }?>
						<td class="operate">&nbsp;</td>
				</tr>
			</tfoot>	
		<?php }?>	
	</table>
	<?php if(isset($orderproducts_list) && sizeof($orderproducts_list)){?>
		<div id="btnouterlist" class="btnouterlist"> <?php echo $this->element('pagers')?> </div>
	<?php }?>
<div>



<div class="am-modal am-modal-no-btn" tabindex="-1" id="statements">
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
						<select name="profilegroup" id="profilegroup" >
							<option value="0"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['please_select'].' '.$ld['templates']:$ld['please_select'].$ld['templates'];?></option>
							
						</select>
					</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left" style="padding-top:10px;"><em style="color:red;">*</em></div>
				</div>
				<input type="button"  name="changeprofileButton"  class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['confirm']?>" onclick="javascript:changeprofile();">
			</form>
	    </div>
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
		window.location.href=encodeURI("/admin/product_sale_statements/"+str);
	}
	function get_str(){
		var pc = document.getElementById("pc");//商品货号
		var pn = document.getElementById("pn");//商品名称
		var pp = document.getElementById("pp");//品牌
		var sn = document.getElementById("sn");//销售数量
		var sp = document.getElementById("sp");//价格
		var tds =''
		if(pc.checked){
			tds +='pc'+",";
		}
		if(pn.checked){
			tds +='pn'+",";
		}
		if(pp.checked){
			tds +='pp'+",";
		}
		if(sn.checked){
			tds +='sn'+",";
		}
		if(sp.checked){
			tds +='sp'+",";
		}

		//订单来源
		var ta = ck_checkbox();
		//alert(ta);
		//品牌
		var pp = checkbox2();
		//分类
		var ca = checkbox3();
		//类目
		<?php if( !empty($ct)){?>
		var lm=checkbox4();
		<?php }?>

		//关键字
		var kw = document.getElementById("keywords").value;
		//时间
		var st = document.getElementsByName('start_date')[0];
		var ed = document.getElementsByName('end_date')[0];
		//分类
//		var cns = document.getElementById("cat").value;
		var str = '?';
		str+="tds="+tds;
		if(kw != '')
			str +="&"+"kw=" +kw;
		if(pp != '')
			str +="&"+"pp=" +pp.substring(pp,pp.length-1);
		if(ta != '')
			str +="&"+"ta=" +ta.substring(ta,ta.length-1);
//		if(tia != '')
//			str +="&"+"tia=" +tia;
		if(ca != '')
			str +="&"+"ca=" +ca;

		str +="&"+"st="+st.value;
//		alert(str);
		str +="&"+"ed="+ed.value;
		<?php if( !empty($ct)){?>
		if(lm != '')
			str +="&"+"lm=" +lm;
		<?php }?>
		return str;
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
/**/

//修改档案分类导出
function changeprofile(){
	var code=document.getElementById("profilegroup").value;
	if(code==0){
		alert("请选择导出方式");
		return false;	
	}	
	var str=get_str();
	window.location.href=encodeURI("/admin/product_sale_statements/index/export/"+str+"&code="+code);
	$("#statements").modal('close');
}	
	function export_act(id){
		
		var group="ProductSale";
		var func="/profiles/getdropdownlist/";
		//ajax传值绑定下拉
		$.ajax({
			url:admin_webroot+func,
			type:"POST",
			data:{group:group},
			dataType:"json",
			success:function(data){
				if(data.flag == 1){
				var result_content = (data.flag == 1) ? data.content : "";
				strbind(result_content);
				}
				if(data.flag == 2){
					alert(data.content);
				}
			}
		});	
		$("#statements").modal('open');
	}
	
	
	
	function print_act(){
		var str=get_str();
		var win=window.open('','_blank');
		win.location.href=encodeURI("/admin/product_sale_statements/product_print/"+str);
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
	
	//c 分类1
	$("#select3").click(function(){
		$(".cc0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
	});
	$(".checkbox3").click(function(){
		$(this).parent().parent().find(".checkboxc").prop("checked",$(this).prop('checked'));
		$(this).parent().parent().find(".checkboxcc").prop("checked",$(this).prop('checked'));
	});
	$(".checkboxc").click(function(){
		$(this).parent().parent().find(".checkboxcc").prop("checked",$(this).prop('checked'));
	});
	
	//d 分类2
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
	
	function checkbox3(){
	    var dropdown = $('#check_box3'),
	        data = dropdown.data('amui.dropdown');
	    if(data.active){
	        dropdown.dropdown('close');
	    }
	    var str=document.getElementsByName("box3");
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