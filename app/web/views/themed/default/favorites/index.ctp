<div class="am-cf am-user">
  <h3><?php echo $ld["favorites"]?></h3>
</div>
<div class="progress">
<?php if(sizeof($fav_products)>0){?>
  <ul class="am-list" style="margin:0;">
	<li><hr/>
	  <em><?php echo $ld["all_goods"]?>
		<?php if(isset($paging['total'])){?>
		(<?php echo $paging['total'];?>)
		<?php }?>
	  </em>
	</li>
  </ul>
<?php }?>
<?php if(sizeof($fav_products)>0) {?>
  <table name="fav" class="am-table am-table-striped am-table-hover">
	<tr>
	  <th width="30"><input type="checkbox" name="chkall" value="checkbox" /></th>
	  <th><?php echo $ld["product_name"]?>/<?php echo $ld["sku"]?></th>
	  <th width="100" class="am-hide-sm-only am-text-center"><?php echo $ld["add_time"]?></th>
<!--	  <th width="75"><?php echo $ld['market_price']?></th>-->
	  <th class="am-text-center"><?php echo $ld['shop_price']?></th>
	  <th class="am-text-right operationstyle"><?php echo $ld["operation"]?></th>
	</tr>
	<?php foreach ($fav_products as $k=>$v){?>
	<form name="buy_nowproduct<?php echo $v['Product']['id']?>" id="buy_nowproduct<?php echo $v['Product']['id']?>" method="post" action="<?php echo $this->webroot;?>carts/buy_now">
	  <input type="hidden" name="type" value="product" />
	  <input type="hidden" name="id" value="<?php echo $v['Product']['id'];?>" />
	  <input type="hidden" name="quantity" value="1" />
	<tr>
	  <td><input type="checkbox" name="checkbox[]" value="<?php echo $v['UserFavorite']['id']?>" /></td>
	  <td>
		<table class="descri">
		  <tr>
			<td>
			  <div class="imgout">
			    <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>$v['Product']['img_thumb'],'name'=>$v['ProductI18n']['name']));?>
			  </div>
			</td>
			<td style="vertical-align:top;"><p class="pro_name am-hide-sm-only"><?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name']));?></p></td>
		  </tr>
		</table>
	  </td>
	  <td class="am-hide-sm-only"><?php echo date("Y-m-d",strtotime($v['UserFavorite']['created']))?></td>
<!--	  <td align="center"><?php if($v['Product']['market_price']==$v['Product']['shop_price']){echo '--';}else{?><span class="deleteline"><?php echo $svshow->price_format($v['Product']['market_price'],$configs['price_format']);?></span><?php }?></td>-->
	  <td align="center"><nobr><?php echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']);?>
		<?php if(isset($v['Product']['off'])&&sizeof($v['Product']['off']&&$v['Product']['off']!=100)>0){ printf($ld['sale_off'],$v['Product']['off']);echo $svshow->image("/img/green/redjiantou.jpg",array("alt"=>""));  }?>
		</nobr>
	  </td>
	  <td class="am-text-right">
		<span class="am-btn am-btn-secondary am-btn-xs" onclick="buy_now_no_ajax(<?php echo $v['Product']['id']?>,1,'product')"><?php echo $ld["buy"]?></span><br>
		<a class="am-btn am-btn-secondary am-btn-xs am-del" href="javascript:del_fav_products(<?php echo $v['Product']['id']?>,'<?php echo $user_id?>','p','<?php echo $this->webroot;?>')"><?php echo $ld["delete"]?></a>
	  </td>
	</tr>
	</form>
	<?php }?>
  </table>
<?php }else{?>
  <table name="fav" class="am-table"><tr><td colspan="6" align="center"><?php echo $ld['not_products_collection'];?>！</td></tr></table>
<?php }?>
<?php if(sizeof($fav_products)>0){?>
  <div class="pagenum">
	<!--<div class="btncss margintop8"><div class="btnl"></div><span class="btncon fl"><?php echo $ld['check_product_purchase']?></span><div class="btnr"></div></div> (<?php echo $ld['check_product_purchase']?>)-->
	<div class="am-btn am-btn-secondary am-btn-xs" style="margin-left:45px;">
		<span class="btncon fl deletehook"  onclick="diachange('delete')" ><?php echo $ld["check_the_products_deleted"]?></span>
	</div>
    <div class="pages am-pagination-right">
	  <?php if(isset($paging['total'])){ echo $this->element('pager');}?>
  	</div>
  </div>
<?php }?>
</div>

<script type="text/javascript">


function diachange(obj){
	if(obj!=''){
		var id=document.getElementsByName('checkbox[]');
		var i;
		var j=0;
		var aa="";
		for( i=0;i<=parseInt(id.length)-1;i++ ){
			if(id[i].checked){
				aa+=","+id[i].value
				j++;
			}
		}
		if( j>=1 ){
			if(confirm('<?php echo $ld['ok']?>'+obj+'?'))
			{
				batch_action(aa.substring(1),obj);
			}
		}else{
			alert('<?php echo $ld['please_select']?>!');
		}
	}
	}



var batch_Success = function(data){
	//var result = eval('('+data+')');//把返回的Jason text转换成object(array类型)
	//box.Close();
//	msg_box.Show();
	if(data=="1")
	{
		location.reload();
	}
	else
	{
		alert("<?php echo $ld['delete_failed']?>!");
	}
//	document.getElementById('message_content').innerHTML = data.message;
}
</script>


<script type="text/javascript">
	//操作员复选框全部选取
$("input[type=checkbox][name='chkall']").click(function() {
    $('input[name*="checkbox"]').prop("checked",this.checked);
});
var $subBox = $("input[name*='checkbox']");
$subBox.click(function(){
    $("input[type=checkbox][name=chkall]").prop("checked",$subBox.length == $("input[name*='checkbox']:checked").length ? true : false);
});
function batch_action(aa,obj){
	//box.Show(); ;
	var sUrl = "/favorites/batch/"+aa+"/"+obj;
	var postData ={
		is_ajax:1
	};
	$.post(sUrl, postData, batch_Success,'text')
}
</script>