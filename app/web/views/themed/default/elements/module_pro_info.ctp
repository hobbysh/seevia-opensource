<!--myproduct-->
<style type="text/css">
.likes{  margin-top:5px;height:30px;padding-left:0;}
.dialog_likeit{border:1px solid rgb(206,206,206); position:absolute; margin-right:0; z-index:1000;height:auto; margin-top:0px; background:#fff;display:none;border-radius:8px;padding-top:8px; padding-bottom:5px}
.date_attr{font-weight:100;width:94%;}
.comm_pro_attr{padding-left:0;}
.comm_pro_attr li{height: 20px;list-style: outside none none;margin: 10px 0;}
#cart_product_customize{display:none;}
.cart_product_customize{margin-bottom:0.5rem;cursor:pointer;display:none;}
.cart_product_customize label{margin-bottom:0;cursor:pointer;}
#accessory_price,#total_prices{clear:both;}
.unit{margin-left:5px;}
h3 a,h3 a:hover{color:#000;}
#CartsBuyNowForm{font-size:1.5rem;}
.post_enquiries,.post_enquiries:hover,.post_enquiries:focus{background:#3bb4f2;color:#fff;}
.post_favorites{background:#3bb4f2;color:#fff;}
.post_favorites:hover,.post_favorites:focus{color:#fff;outline:none;}
.nowbuy{background:#0e90d2;color:#fff;border:none;}
.nowbuy:hover,.nowbuy:focus,{color:#fff;background:#f60;outline:none;}
.post_enquiries{min-width:150px;height:35px;border-radius:5px;margin-top:5px;margin-bottom:5px;}
h3 a:visited{color:#000;}


</style>
<?php if($code_infos[$sk]['type']=="module_pro_info"){?>
<div class="am-u-lg-7 am-u-md-7 am-u-sm-12">
  <!-- 购买form框开始 -->
  <form id="CartsBuyNowForm" method="post" action="<?php echo $html->url('/carts/buy_now');?>" accept-charset="utf-8">
	<div style="display:none;"><input type="hidden" name="_method" value="POST" /></div>
	<input type="hidden" name="type" value="product" />
	<input type="hidden" name="id" value="<?php echo $sm['Product']['id'];?>" />
	<input type="hidden" name="code" value="<?php echo $sm['Product']['code'];?>" />
	<h3 style="margin:0;" ><?php echo $svshow->seo_link(array('type'=>'P','id'=>$sm['Product']['id'],'name'=>$sm['ProductI18n']['name']));?></h3>
	<?php if(isset($configs['show_product_code'])&&$configs['show_product_code']=='1'){ ?>
	<div class="sku"><?php echo $ld['sku'];?>: <?php echo $sm['Product']['code'];?></div>
	<?php } ?>
	<?php if(isset($configs['show_brand'])&&$configs['show_brand']=='1'&&isset($sm['BrandInfo'])){ ?>
	<div class="brand"><?php echo $ld['brand'];?>: <?php echo $sm['BrandInfo']['BrandI18n']['name'];?></div>
	<?php } ?>
	<?php if(isset($configs['show_weight'])&&$configs['show_weight']=='1'&&$sm['Product']['weight']!=0){ ?>
	<div class="product_weight"><?php echo $ld['product_weight'];?>: <?php echo $sm['Product']['weight'];?>kg</div>
	<?php } ?>
	<?php if(isset($configs['show_view_stat'])&&$configs['show_view_stat']=='1'){ ?>
	<div class="view_stat"><?php echo $ld['browse_count'];?>: <?php echo $sm['Product']['view_stat'];?></div>
	<?php } ?>
	<?php if(isset($configs['show_onsale_time'])&&$configs['show_onsale_time']=='1'){ ?>
	<div class="sale_time"><?php echo $ld['sale_time'];?>: <?php echo $sm['Product']['online_time']=="0000-00-00 00:00:00"?date("Y-m-d",strtotime($sm['Product']['created'])):date("Y-m-d",strtotime($sm['Product']['online_time']));?></div>
	<?php } ?>
	<?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
	<div class="am-g">
	<div class="likes am-u-sm-8" >
	  <a id="<?php echo $sm['Product']['id'];?>" class="my_like_icon" style="cursor:pointer;margin:0;position: static;">
		<img src="/theme/default/img/like_icon.png">&nbsp;
		<font style="color:rgb(199,76,64); "><?php echo $ld['like']?></font>
	  </a>
	  <font class="my_like_num"><?php echo $like_num;?></font>
	  <span class="liker" style=" float:right">
		<a href="javascript:void(0)" class="likeit">
		  <?php foreach($like_list as $k=>$v){ ?>
			<?php if(!empty($v['User'])){ ?>
			  <a target="_blank" href="<?php echo $html->url('/user_socials/index/'.$v['UserLike']['user_id']); ?>">
				<img style="width:22px; height:22px" src="<?php echo  $v['User']['img01']==null?'/theme/default/img/no_head.png':$v['User']['img01']?>">
			  </a>
			<?php }?>
		  <?php if($k==7){break;} }?>
		</a>
		<div class="dialog_likeit" style="margin:0; width:<?php if(isset($like_num)){if($like_num>7){echo '218';}else{echo 27*$like_num;}}?>px;">
		  <div style="margin:0;">
			<ul style="list-style:none;margin:0;padding:0;">
			<?php foreach($like_list as $k=>$v){?>
		      <?php if(!empty($v['User'])){ ?>
			  <li style="float:left; width:22px; height:22px; margin:0;padding-right:2px; margin-right:3px;">
				<a target="_blank" href="<?php echo $html->url('/user_socials/index/'.$v['UserLike']['user_id']); ?>">				
				  <img style="width:22px;margin: 0 2px;height:22px" src="<?php echo $v['User']['img01']==null?'/theme/default/img/no_head.png':$v['User']['img01'];?>">
				</a>
			  </li>
			  <?php }?>	
			<?php if($k==23){break;} }?>
			</ul>

		  </div>
		</div>
	  </span>
	</div>
	<?php
			if($favorites_flag){
				$status="";
				$statustxt=$ld['favorites'];
				$css="";
			}else{
				$status=" disabled='true'";
				$statustxt=$ld['favorited'];
				$css=" style='background:gray;cursor:default;border:none;'";
			}
		?>
		<div class="addtofavorites am-u-sm-4 am-text-right">
        
           <!-- 收藏 -->
			<button type="button" style="outline:none" onclick="ajax_login_show()" class="am-btn am-btn-sm post_favorites" <?php echo $status.$css; ?>><?php echo $statustxt; ?></button>
		</div>
	</div>
	  <?php if(isset($configs['share_js'])&&!empty($configs['share_js'])){echo $configs['share_js'];}?>
	<?php } ?>
	<?php if(isset($configs['show_product_price'])&&$configs['show_product_price']==1){?>
	  <?php if(isset($sm['sale_attr_prodcut'])&&sizeof($sm['sale_attr_prodcut'])>0){$price_arr=array();foreach($sm['sale_attr_prodcut'] as $psk=>$psv){array_push($price_arr,$psv['SkuProduct']['shop_price']);}
		$max_index=array_search(max($price_arr),$price_arr);$min_index=array_search(min($price_arr),$price_arr);$max_price=$price_arr[$max_index];$min_price=$price_arr[$min_index];?>
		<hr style="margin:10px 0" />
	  <div class="price"><?php echo $ld['price'];?>: <i><b>￥<font><?php echo $min_price.'-'.$max_price;?></font><input id="pro_price" type="hidden" value="<?php echo $min_price.'-'.$max_price;?>" /></b><del></del></i></div>
	  <?php }else{?>
	  <hr style="margin:10px 0" />
	  <div class="price"><?php echo $ld['price'];?>: <b><font style="color:#f60;font-size:1.7rem"><?php
	  	  		if(isset($sm['price_range'][$sm['Product']['code']])){
	  	  			$price_range=$sm['price_range'][$sm['Product']['code']];
	  	  			echo $svshow->price_format($price_range['min_price'],$configs['price_format'])." -".$svshow->price_format($price_range['max_price'],$configs['price_format']);
	  	  		}else{
			  	   echo $svshow->price_format(strlen($sm['Product']['shop_price'])<8?$sm['Product']['shop_price']:substr($sm['Product']['shop_price'],0,7),$configs['price_format']); } ?></font><input id="pro_price" type="hidden" value="<?php echo $sm['Product']['shop_price'];?>" /></b><span class="unit"><?php if(isset($configs['show_product_unit'])&&$configs['show_product_unit']=='1'&&!empty($sm['Product']['unit'])){echo "/".$sm['Product']['unit']; } ?></span><del style="display:inline;color:#ccc;"><?php if(isset($configs['show_market_price'])&&$configs['show_market_price']=='1'){ ?><font style="padding-left:15px"><?php echo $svshow->price_format(strlen($sm['Product']['market_price'])<8?$sm['Product']['market_price']:substr($sm['Product']['market_price'],0,7),$configs['price_format']);?></font><?php } ?></del><span class="unit"><?php if(isset($configs['show_product_unit'])&&$configs['show_product_unit']=='1'&&!empty($sm['Product']['unit'])){echo "/".$sm['Product']['unit']; } ?></span></div>
	  <?php }?>
	<?php }?>
	<?php if(isset($configs['show_product_type'])&&$configs['show_product_type']=='1'){?>
	  <div class="rule" style="margin:0px">
		<?php if(isset($sm['ProductAttribute'])&&!empty($sm['ProductAttribute'])&&isset($sm['comm_pro_attr'])){ ?>
		<ul class='comm_pro_attr'>
			<?php  foreach($sm['ProductAttribute'] as $ak=>$av){ if(empty($sm['comm_pro_attr'][$av['attribute_id']])){continue;} ?>
			<li>
				<span><?php echo isset($sm['comm_pro_attr'][$av['attribute_id']])?$sm['comm_pro_attr'][$av['attribute_id']]:$av['attribute_id'];?>:</span>
				<span><?php echo $av['attribute_value']; ?></span>
			</li>
			<?php }?>
		</ul>
		<?php }?>
		<?php if(isset($sm['sale_attr'])){?>
		<ul class="sale_attr_ul">
			<?php $sa_flag=0; foreach($sm['sale_attr'] as $sk => $sv){?>
				<li class="am-u-sm-12 am-padding-left-0 attr_code attr_code_<?php echo $sa_flag; ?>" id="<?php echo $sv['Attribute']['code'];?>">
                  
					<div style="float:left;margin:0 15px 0 0"><?php echo $sv['AttributeI18n']['name'];?>: <input type="hidden" name="product_sku_attr_buy_<?php echo $sk; ?>" value="" /></div>
					<?php if(!empty($sv['AttributeI18n']['attr_value'])&&$sv['Attribute']['attr_input_type']!='4'){$arr=explode("\n",$sv['AttributeI18n']['attr_value']);?>
					<?php if(isset($arr)&&sizeof($arr)>0){foreach($arr as $ak => $av){?>
					<div class="sku_img" style="background:#f5f5f5;color:#000;margin-left:0px;border-radius:5px;">
						<?php $check_attr_txt=isset($sm['attr_check'][trim($av)])?implode(";",$sm['attr_check'][trim($av)]):''; ?>
						<a  href="javascript:void(0);" alt="<?php echo $sv['Attribute']['id'];?>,<?php echo $check_attr_txt; ?>" class="av0" ><?php echo trim($av);?></a>
					</div>
					<?php }}}else if(!empty($sv['AttributeI18n']['attr_value'])&&$sv['Attribute']['attr_input_type']=='4'){
								$arr=explode("\n",$sv['AttributeI18n']['attr_value']);
								$attr_date_time=isset($arr[0])?$arr[0]:'';
								$check_attr_txt=isset($sm['attr_check'][$attr_date_time])?implode(";",$sm['attr_check'][$attr_date_time]):''; 
						?>
					<label class="label_calendar"><div class="sv_selected" style="display:none;"></div><input type="text" class="date_attr" data="<?php echo $sv['Attribute']['id'];?>,<?php echo $check_attr_txt; ?>" readonly id="date_attr" value="<?php echo $attr_date_time; ?>" /></label>
					<?php }?>
				
				</li>

			<?php $sa_flag++;}?>
			<div class="am-cf"></div>
		</ul>
		<?php }?>
	  </div>
	<?php }?>
	<div class="amount am-u-sm-6" style="margin:0;padding:0;"><span style="margin-right:4px;"><?php echo $ld['stock'];?>: </span><font><?php echo $sm['Product']['quantity'];?></font><input id="pro_quantity" type="hidden" value="<?php echo $sm['Product']['quantity'];?>" /></div>
	<?php }?>
	<?php if(constant("Product")=="AllInOne"){?>
    <div class="pro_cart_quantity am-u-sm-6 ">
		<span style="float:left;margin-right:8px;"><?php echo $ld['quantity']?>:</span>
		<a class="am-fl am-icon-minus" href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #ccc;margin:0;"></a>
		<input class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center am-padding-0" style="height:25px;width:25px;border:1px solid #ccc;border-radius:0;border-left:none;border-right:none;-webkit-appearance:none;" type="text" value="1" name="quantity" id="quantity"/>
		<a class="am-fl am-icon-plus" href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #ccc;margin:0;"></a>
		<div style="clear:both;"></div>
    </div>
	<?php }?>
	<?php if(isset($configs['show_sale_stat'])&&$configs['show_sale_stat']=='1'){ ?>
	<div class="sale_stat" style="margin:0;padding:0;margin-bottom:10px;clear:both;"><?php echo $ld['sales_quantity'];?>: <?php echo $sm['Product']['sale_stat'];?></div>
	<?php } ?>


	
	<?php if(isset($configs['show_product_quantity'])&&$configs['show_product_quantity']==1){?>
	
	  <div class="amount_message" style="display:none;color:#C74C40;"></div>
	<div id="attr_price_list" style="display:none;">
	  <div id="basic_price"></div>
	  <div id="accessory_price"></div>
	  <div id="total_prices"></div>
	</div>
	<?php if(!$sm['Product']['quantity']>0){;?>
	<div id="nobuy" style="color:#ff0000;clear:both;font-weight:600;font-size:1.6rem">商品库存不足</div>
	<?php } ?>

  <div  class="am-buy am-hide-sm-only" style="margin-top:10px;">
     <?php if(constant("Product")=="AllInOne"){?>
	<!--如果购买了购物车 切物品数量大于零才显示-->
		<?php if($sm['Product']['quantity']>0){?>
		<div style="clear:both">
		
			<button type="button" class="am-btn am-btn-primary am-btn-sm nowbuy addtobuy" onclick="buy_at_once()" ><?php echo $ld['buy_now']; ?></button>
		
			<button type="button"  onclick="buy_at_once()" class="am-btn-sm addtocart am-btn am-btn-secondary nowbuy"><img src="/theme/default/img/gouwuche_lv.png" style="width:18px;height:12px;margin-right:3px;position:relative;top:-1px;"><?php echo $ld['add_to_cart']; ?></button>
			<?php }?>
			  <!-- 询价 -->
		<?php if(!$sm['Product']['quantity']>0){?>
			<?php if(isset($configs['open_enquiry'])&&$configs['open_enquiry']==1){ ?>
			<button type="button" class="am-btn  am-btn-sm post_enquiries"><?php echo $ld['enquiry']; ?></button>		
			<?php } ?>
		<?php } ?>

	
		
	<?php } ?>
	</div>
	
</div>

<!-- 立即购买 加入购物车 固定底部 -->
	<div  class="am-buy am-bottom-buy am-show-sm-only">
     <?php if(constant("Product")=="AllInOne"){?>
	<!--如果购买了购物车 切物品数量大于零才显示-->
		<?php if($sm['Product']['quantity']>0){?>
		<div>
		
				<button type="button" class="am-btn am-btn-sm am-btn-primary nowbuy addtobuy" onclick="buy_at_once()" ><?php echo $ld['buy_now']; ?></button>
	
	
			<button type="button"  onclick="buy_at_once()" class="am-btn-sm addtocart am-btn nowbuy am-btn-secondary"><img src="/theme/default/img/gouwuche_lv.png" style="width:18px;height:12px;margin-right:3px;position:relative;top:-1px;"><?php echo $ld['add_to_cart']; ?></button>
			<?php }?>
			  <!-- 询价 -->
			  <?php if(!$sm['Product']['quantity']>0){?>
				<?php if(isset($configs['open_enquiry'])&&$configs['open_enquiry']==1){ ?>
			<button type="button" class="am-btn  am-btn-sm post_enquiries"><?php echo $ld['enquiry']; ?></button>		
				<?php } ?>
			  <?php } ?>
	
	<?php } ?>
	</div>

</div>
	

		
</div>
	</form>
		<?php if(!isset($configs)){
			if($sm['Product']['forsale']==1&&isset($sm['Product']['bestbefore'])){
				echo "<div class='appear_oldclassics ao'>";
			}else{
				echo "<div class='no_appear_oldclassics ao'>";
			}
			echo $configs['share_js'];
			echo "</div><a href='javascript:void(0)' id='share'>".$ld['share']."</a></div>";
			}
		?>	

		<!-- 购买form框结束 -->
		<?php if(isset($sm['sale_attr_prodcut'])){?>
		<ul class="sale_attr_pro_ul" style="display:none;">
			<?php $sa_flag=0; foreach($sm['sale_attr_prodcut'] as $sk => $sv){?>
				<li>
					<div class="sku_product_img" style="">						
						<a class="sale_attr_img" href="<?php echo $html->root_url($sv['SkuProduct']['img_original']);?>">
						<img width="35" height="35" src="<?php echo $sv['SkuProduct']['img_thumb']?>" alt="<?php echo $sv['SkuProduct']['attr_value1']?>" class='img cloudzoom-gallery' data-cloudzoom = "useZoom: '.cloudzoom', image: '<?php echo $sv['SkuProduct']['img_original'];?>', zoomImage: '<?php echo $sv['SkuProduct']['img_original'];?>' "/></a>
						<input type="hidden" name="price" value="<?php echo $sv['SkuProduct']['shop_price']?>">
						<input type="hidden" name="product_id" value="<?php echo $sv['SkuProduct']['sku_product_id']?>">
						<input type="hidden" name="product_quantity" value="<?php echo $sv['SkuProduct']['quantity']?>">
						<input type="hidden" name="img_detail" value="<?php echo ($sv['Product']['img_detail']!=''?$sv['Product']['img_detail']:$configs['products_default_image']);?>">
						<input type="hidden" name="img_original" value="<?php echo $html->root_url($sv['SkuProduct']['img_original']);?>">
						<input type="hidden" name="attr_value1" value="<?php echo $sv['SkuProduct']['attr_value1']?>">
						<input type="hidden" name="attr_value2" value="<?php echo $sv['SkuProduct']['attr_value2']?>">
					</div>
				</li>
			<?php }?>
		</ul>
		<?php }?>
</div>
<?php }?>
<style>

.attr_code div{margin-top:5px;}
.am-bottom-buy{
	position:fixed;bottom:0;left:0;z-index:100;width:100%;background:#f5f5f5;text-align:center}
.pro_cart_quantity a{color:#000;padding:0px 5px;}
.pro_cart_quantity a:hover{color:#000;}
#CartsBuyNowForm .price,#CartsBuyNowForm .amount{margin: 0;}
/*#CartsBuyNowForm .rule{margin-top:-10px;padding-bottom: 10px;}*/
#CartsBuyNowForm .buy{margin-top:5px;clear:both;}
.addtocart{border-radius:5px;margin-top:5px;margin-bottom:5px;margin-left:5px;min-width:150px;height:35px;background:#3bb4f2;color:#fff;outline:none}
.addtobuy{border-radius:5px;margin-left:0px;margin-top: 5px;margin-bottom: 5px;min-width:150px;height:35px;color:#fff;outline:none}
.sale_attr_ul{padding-left:0px;margin:0px;margin-bottom:5px;}
.sale_attr_ul li{list-style:none;margin:5px 0;height:20px;}
.sale_attr_ul .sku_img{float:left;width:auto;min-width:40px;height:auto;margin:0 5px 0;/*cursor:pointer;*/border:2px solid #fff;text-align:center;position: relative;top: -2px;}
.sale_attr_ul .sku_img a{padding:0.3rem 1rem;color:#000;}
.sku_img:hover{border:2px solid #f60;}

.label_calendar{position: relative;top: -3px;}
.sale_attr_ul .sv_selected{border:2px solid #f60;}
.sale_attr_ul .un_selected{border:2px solid #fff;}
.sale_attr_ul .un_selected a{cursor:text;color:#ccc;}
.sale_attr_ul .un_selected a:hover{cursor:text;color:#ccc;}
.dialog_share{height:auto;}
.dialog_share .tab{
	border-bottom: 2px solid #65c5b3;
    clear: both;
    overflow: hidden;
    position: relative;
    margin-top:-28px;
}
.dialog_share .tab li.crr{
	-moz-border-bottom-colors: none;
	-moz-border-left-colors: none;
	-moz-border-right-colors: none;
	-moz-border-top-colors: none;
	background: none repeat scroll 0 0 #65c5b3;
	border-color: #65c5b3 #65c5b3 -moz-use-text-color;
	border-image: none;
	border-style: solid solid none;
	border-width: 1px 1px medium;
	border-radius:5px 5px 0 0;
    color: #ffffff;
    float: left;
    font-size: 14px;
    height: 20px;
    line-height: 20px;
    margin: 0 5px 0 -1px;
    padding: 6px;
    text-align: center;
    width: auto;
}
.dialog_share .x{
	position: relative;
	right: -3px;
	top: -5px;
	z-index:5;
}
</style>

<script type="text/javascript">
var wechat_shareTitle="<?php echo $sm['ProductI18n']['name'] ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if($svshow->imgfilehave($server_host.(str_replace($server_host,'',$sm['Product']['img_thumb'])))){ ?>
var wechat_imgUrl="<?php echo $server_host.(str_replace($server_host,'',$sm['Product']['img_thumb'])); ?>";
<?php } ?>

var lang="<?php echo sizeof($languages)>1?'/'.$languages[LOCALE]['Language']['map']:'';?>";
var id=$("input[type=hidden][name=id]").val();
var img_src=$("#pro_img_old").attr("src");
var pro_code=$("input[type=hidden][name=code]").val();
var pro_id=$("input[type=hidden][name=id]").val();

function default_sku_select(){
	$(".sale_attr_ul li div.sku_img:eq(0) a.av0").each(function(){
		if(!$(this).hasClass("av0")){
			return false;
		}
		$(".sale_attr_ul").find("input[type=hidden]").val("");
		var attr_div=$(this).parent().parent().parent();
		var attr_li=$(this).parent().parent();
		var attrlength=attr_div.find("li").length;
		var select_attr_code=attr_li.attr("id");
		
		if($(this).parent().hasClass("sv_selected")){
			attr_div.find("div.sku_img").removeClass("un_selected");
			attr_div.find("div.sku_img a").addClass("av0");
			$(this).parent().removeClass("sv_selected");
		}else{
			attr_li.find("div").removeClass("sv_selected");
			attr_li.find("div").removeClass("un_selected");
			$(this).parent().addClass("sv_selected");
			var select_attr_id_txt_arr=$(this).attr("alt").split(",");
			var select_attr_id=select_attr_id_txt_arr[0];
			var select_attr_val=$(this).html();
			var ck_select_attr=select_attr_id_txt_arr.length>0?select_attr_id_txt_arr[1]:"";
			var ck_select_attr_arr=ck_select_attr!=""?ck_select_attr.split(";"):[];
			attr_li.find("a").addClass("av0");
			attr_div.find("li div a").each(function(){
				var _attr_code=$(this).parent().parent().attr("id");
				if(_attr_code==select_attr_code){
					return true;
				}
				var other_attr_val=$(this).html();
				if(!in_array(other_attr_val,ck_select_attr_arr)){
					$(this).removeClass("av0");
					$(this).parent().removeClass("sv_selected");
					$(this).parent().addClass("un_selected");
				}else{
					$(this).addClass("av0");
					$(this).parent().removeClass("un_selected");
				}
			});
		}
		var select_attr_ids=[];
		var select_attr_vals=[];
		attr_div.find("div.sku_img.sv_selected a").each(function(){
			var select_attr_alts_txt_arr=$(this).attr("alt").split(",");
			select_attr_ids.push(select_attr_alts_txt_arr[0]);
			select_attr_vals.push($(this).html());
			$(this).parent().parent().find("input[type=hidden]").val(select_attr_alts_txt_arr[0]);
		});
		if(document.getElementById('date_attr')&&$("#date_attr").val()!=""){
			var select_attr_alts_txt=$("#date_attr").attr("data");
			if(select_attr_alts_txt!="undefined"){
				var select_attr_alts_txt_arr=select_attr_alts_txt.split(",");
				select_attr_ids.push('');
				select_attr_vals.push('');
				$("#date_attr").parent().parent().parent().find("input[type=hidden]").val(select_attr_alts_txt_arr[0]);
			}
		}
		twins_sale_attr(pro_code,pro_id,select_attr_ids,select_attr_vals,attrlength);
	});
}
default_sku_select();

$(".sale_attr_ul div.sku_img a.av0").click(function(){
	if(!$(this).hasClass("av0")){
		return false;
	}
	$(".sale_attr_ul").find("input[type=hidden]").val("");
	var attr_div=$(this).parent().parent().parent();
	var attr_li=$(this).parent().parent();
	var attrlength=attr_div.find("li").length;
	var select_attr_code=attr_li.attr("id");
	
	if($(this).parent().hasClass("sv_selected")){
		attr_div.find("div.sku_img").removeClass("un_selected");
		attr_div.find("div.sku_img a").addClass("av0");
		$(this).parent().removeClass("sv_selected");
	}else{
		attr_li.find("div").removeClass("sv_selected");
		attr_li.find("div").removeClass("un_selected");
		$(this).parent().addClass("sv_selected");
		var select_attr_id_txt_arr=$(this).attr("alt").split(",");
		var select_attr_id=select_attr_id_txt_arr[0];
		var select_attr_val=$(this).html();
		var ck_select_attr=select_attr_id_txt_arr.length>0?select_attr_id_txt_arr[1]:"";
		var ck_select_attr_arr=ck_select_attr!=""?ck_select_attr.split(";"):[];
		attr_li.find("a").addClass("av0");
		attr_div.find("li div a").each(function(){
			var _attr_code=$(this).parent().parent().attr("id");
			if(_attr_code==select_attr_code){
				return true;
			}
			var other_attr_val=$(this).html();
			if(!in_array(other_attr_val,ck_select_attr_arr)){
				$(this).removeClass("av0");
				$(this).parent().removeClass("sv_selected");
				$(this).parent().addClass("un_selected");
			}else{
				$(this).addClass("av0");
				$(this).parent().removeClass("un_selected");
			}
		});
	}
	var select_attr_ids=[];
	var select_attr_vals=[];
	attr_div.find("div.sku_img.sv_selected a").each(function(){
		var select_attr_alts_txt_arr=$(this).attr("alt").split(",");
		select_attr_ids.push(select_attr_alts_txt_arr[0]);
		select_attr_vals.push($(this).html());
		$(this).parent().parent().find("input[type=hidden]").val(select_attr_alts_txt_arr[0]);
	});
	if(document.getElementById('date_attr')&&$("#date_attr").val()!=""){
		var select_attr_alts_txt=$("#date_attr").attr("data");
		if(select_attr_alts_txt!="undefined"){
			var select_attr_alts_txt_arr=select_attr_alts_txt.split(",");
			select_attr_ids.push('');
			select_attr_vals.push('');
			$("#date_attr").parent().parent().parent().find("input[type=hidden]").val(select_attr_alts_txt_arr[0]);
		}
	}
	twins_sale_attr(pro_code,pro_id,select_attr_ids,select_attr_vals,attrlength);
});

function in_array(stringToSearch, arrayToSearch){
 for (s = 0; s < arrayToSearch.length; s++) {
  thisEntry = arrayToSearch[s].toString();
  if (thisEntry == stringToSearch) {
   return true;
  }
 }
 return false;
}
var ck_sku_code=true;
function twins_sale_attr(pro_code,pro_id,select_attr_ids,select_attr_vals,attrlength){
	$(".customize_attrInfo").css("display","none");
	if(!document.getElementById("parent_cart_product_customize")){
		var customizehtml="<div id='parent_cart_product_customize' class='cart_product_customize am-btn am-btn-secondary am-btn-xs'></div>";
		$(".rule").append(customizehtml);
	}
	$(".cart_product_customize").css("display","none").html("");
	if(select_attr_ids.length==attrlength&&select_attr_vals.length==attrlength){
		ck_sku_code=false;
		$.ajax({ url: "/products/check_sales_attribute/"+pro_code,
			data:{"pro_id":pro_id,"attr_id":select_attr_ids,"attr_value":select_attr_vals},
			dataType:"json",
			type:"POST",
			success: function(data){
				if(data.flag!=0){
					var pro_data=data.data;
					$("input[type=hidden][name=code]").val(pro_data.Product.code);
					$(".amount font").html(pro_data.Product.quantity);
					
					var price_html=$("#pro_price").parent().parent();
					price_html.find("b font").html(sprintf(js_config_price_format,pro_data.Product.shop_price));
					$("#pro_price").val(pro_data.Product.shop_price);
					
					price_html.find("del font").html(sprintf(js_config_price_format,pro_data.Product.market_price));
					
					if(data.is_customize==1){
						var customizehtml="<label><input type='checkbox' name='cart_product_customize["+pro_data.Product.code+"]' id='cart_product_customize' value='1' onclick=\"set_cart_attr(this,'"+pro_data.Product.code+"')\" ><span>是否定制</span></label>";
						$(".cart_product_customize").append(customizehtml).css("display","inline-block");
					}
				}else{
					alert(data.data);
				}
				ck_sku_code=true;
		    }
		});
	}
}
function set_cart_attr(obj,pro_code){
	if(!document.getElementById("customizeupdatehtml")){
		var customizeupdatehtml="<div class='customizeupdatehtml' id='customizeupdatehtml'></div>";
		$(".rule").append(customizeupdatehtml);
	}
	if($(obj).prop("checked")){
		if(document.getElementById("customize_attrInfo"+pro_code)){
			$("#customize_attrInfo"+pro_code).css("display","block");
		}else{
			var pro_id=$("input[type=hidden][name=id]").val();
			var data={pro_code:pro_code};
			$.ajax({ url: "/products/set_cart_product_value/"+pro_id,
				type:"POST",
				data:data,
				dataType:"html", 
				success: function(data){
					$("#customizeupdatehtml").append(data);
				}
			});	
		}
	}else{
		$("#customize_attrInfo"+pro_code).css("display","none");
	}
}
function buy_at_once(){
	if(js_login_user_data==null){
		//未登录
    	if($(".am-ajax-login").css("display") =="none"){
    		$("#popup_login").click();
    		change_captcha('authnum_popup_login',true);
    		return false;
    	}
	}
	if($(".rule .sale_attr_ul li").length>0){
		if($(".rule .sale_attr_ul div.sv_selected").length==$(".rule .sale_attr_ul li").length){
			$('#CartsBuyNowForm').submit(); return false;
		}else{
			alert("请先选择销售属性！");
		}
	}else{
		$('#CartsBuyNowForm').submit(); return false;
	}
}
//购买数量和库存对比ajax
function buy_quantity(){
    var quantity=$("#quantity").val();
    var product_code=$("input[type=hidden][name=code]").val();
	$.ajax({ url: "/products/buy_quantity/",
		data:{"quantity":quantity,"pro_code":product_code},	
		dataType:"json",
		type:"POST",
		success: function(data){
			if(data.flag=="0"){
				$(".amount_message").css("display","none");
				$(".amount_message").html("");
				$(".addtobuy").attr("onclick","buy_at_once()");
				$(".addtocart a").attr("class","ajax_cart");
			}else{
				$(".amount_message").css("display","block");
				$(".amount_message").html(js_understock);
				$(".addtobuy").attr("onclick","");
				$(".addtocart a").attr("class","dis_ajax_cart");
			}
	    }
	});
}

$("#quantity").keyup(function(){
	buy_quantity();
});

$(".liker").mouseover(function(){
	$(".dialog_likeit").show();
});	
$(".liker").mouseleave(function(){
	$(".dialog_likeit").hide();

});	

$('#share').click(function(){
    if(js_login_user_data==null){
	    $(".denglu").click();
    }else{
        
    }
});

function changePrice(attr_id,type_attr_id){
	//根据属性id获取属性的图片
	if($("#attr_img_"+attr_id+" img").attr("src")!="0"){
		$("#attr_img_"+attr_id).click();
	}
	//根据属性id获取属性的价格
	if(attr_id==0){
		$("#TypeAttribute_"+type_attr_id).html('');
		return;
	}
	var pro_id=$("#product_id").val();
	var sUrl = "/products/select_attr_price";
	var postData = {attr_id:attr_id,pro_id:pro_id};
	var select_attr_price_success = function(result){
		if(result.price>0){
			$("#TypeAttribute_"+type_attr_id).html('+'+result.price);
			$total=result.total+parseFloat($('#f_shop_price').val());
			document.getElementById("shop_price").innerHTML=document.getElementById("shop_price").innerHTML.replace(/[\d\.]+/g,$total);
		}else{
			if($("#TypeAttribute_"+type_attr_id)){
				$("#TypeAttribute_"+type_attr_id).html('');
			}
			$total=result.total+parseFloat($('#f_shop_price').val());
			document.getElementById("shop_price").innerHTML=document.getElementById("shop_price").innerHTML.replace(/[\d\.]+/g,$total);
		}
		if(result.total>0){
			$('#attr_total').html('+$'+result.total);
		}
		var p_url = document.domain;
		$("#pro_img").attr("src","http://"+p_url+"/products/createPic/?"+Math.random());
		$("#pro_back_img").attr("src","http://"+p_url+"/products/createBackPic/?"+Math.random());
	}
	$.post(sUrl,postData,select_attr_price_success,"json");
}
		var click=0;
		//ajax_cart添加购物车
    	$(".ajax_infocart").click(function(){
    		if(js_login_user_data==null){
    			//未登录
    			$("#popup_login").click();
    		}else{
    			var id=$(this).attr("id");//产品id
        		var type="product";//类型：产品
        		var quantity=$("#quantity").val();//数量：1
        		var flag=false;
    			if($(".rule .sale_attr_ul li").length>0){
    				if($(".rule .sale_attr_ul li div.sv_selected").length==$(".rule .sale_attr_ul li").length){
    					if(click==0){
    						click=1;
    		    			$.ajax({ url: "/carts/buy_now/?ajax=2",
    		    					type:"POST",
    		    					data:$("#CartsBuyNowForm").serialize(),
    		    					dataType:"json", 
    		    					//data: { 'type': type, 'id':id,'quantity':quantity},
    		    					success: function(data){
    									/*alert(data);
    		    						$("#shoppingcart a").html("购物车("+data.sum_quantity+")");*/
    									alert("购物车添加成功！");
    									click=0;
    									flag=true;
    									if(flag){
    											ajax_cart();
    										}
    									}
    		      					});	
    		      		}
    				}else{
    					alert("请先选择销售属性！");
    				}
    			}else{
    				if(click==0){
    					click=1;
    	    			$.ajax({ url: "/carts/buy_now/?ajax=2",
    	    					type:"POST",
    	    					data:$("#CartsBuyNowForm").serialize(),
    	    					dataType:"json", 
    	    					//data: { 'type': type, 'id':id,'quantity':quantity},
    	    					success: function(data){
    								/*alert(data);
    	    						$("#shoppingcart a").html("购物车("+data.sum_quantity+")");*/
    								alert("购物车添加成功！");
    								click=0;
    								flag=true;
    								if(flag){
    										ajax_cart();
    									}
    								}
    	      					});	
    	      		}
    			}
      		}
    	});

function ajax_cart(){
	$.ajax({ url: "/carts/index/?ajax=1",dataType:"json", context: $("#shoppingcart a"), success: function(data){
			//alert(data.sum_quantity);
			$("#shoppingcart a").html("购物车("+data.sum_quantity+")");
	}});
}
//评论分享
function checktoken(type){
	$.ajax({ url: "/synchros/checktoken/"+type,
		dataType:"json",
		type:"POST",
		success: function(data){
			if(data.flag==0){
				window.location.href='/synchros/opauth/'+type.toLowerCase();
			}else if(data.status=='1'){
				$("#"+type+"_icon").attr("style","");
			}else if(data.status=='0'){
				$("#"+type+"_icon").attr("style","filter: Alpha(opacity=10);-moz-opacity:.1;opacity:0.3;");
			}
	    }
	});
}

$("#share_pr").click(function(){
	$("#ShareForm").submit();
});
$(".post_enquiries").click(function(){
	var product_id=$("input[type=hidden][name=id]").val();
	//判断是否有销售属性
	if($(".rule .sale_attr_ul li").length>0){
		//判断是否选中销售属性
		if($(".rule .sale_attr_ul li div.sv_selected").length==$(".rule .sale_attr_ul li").length){
			var attr_txt="";
			$(".rule .sale_attr_ul div.sv_selected a").each(function(){
				attr_txt+=$(this).html()+" ";
			});
			window.location.href=lang+'/enquiries?product_id='+product_id+'&attr='+attr_txt;
			return false;
		}else{
			alert("请先选择销售属性！");
		}
	}else{
		window.location.href='/enquiries?product_id='+product_id;
	}
});
$(".post_favorites").click(function(){
    if(js_login_user_data==null){
		$("#popup_login").click();
		return false;
	}else{
    	var product_id=$("input[type=hidden][name=id]").val();
    	var type="p";
    	//用户id
    	$.ajax({ url: "/favorites/add/"+type+"/"+product_id,
    			type:"POST",
    			dataType:"json", 
    			success: function(data){
    				/*alert(data);
    				$("#shoppingcart a").html("购物车("+data.sum_quantity+")");*/
    				if(data.type==0){
    					$(".post_favorites").attr('disabled',true).css("background",'gray').css("cursor","default").val("<?php echo $ld['favorited'] ?>");
    					alert("<?php echo $ld['collection_success'] ?>！");
    				}
    			}
    	});	
	}
});

$(".pro_cart_quantity a.am-icon-minus").click(function(){
    var pro_quantity=$("#pro_quantity").val();
    pro_quantity=parseInt(pro_quantity);
    var cart_quantity=$("#quantity").val();
    if(cart_quantity==""){
        cart_quantity=1;
    }else{
        cart_quantity=parseInt(cart_quantity);
    }
    $("#quantity").val(cart_quantity-1>=1?cart_quantity-1:1);
    buy_quantity();
});

$(".pro_cart_quantity a.am-icon-plus").click(function(){
    var pro_quantity=$("#pro_quantity").val();
    pro_quantity=parseInt(pro_quantity);
    var cart_quantity=$("#quantity").val();
    if(cart_quantity==""){
        cart_quantity=1;
    }else{
        cart_quantity=parseInt(cart_quantity);
    }
    $("#quantity").val(cart_quantity+1<=pro_quantity?cart_quantity+1:cart_quantity);
    buy_quantity();
});
</script>