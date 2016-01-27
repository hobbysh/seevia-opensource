<?php
/*****************************************************************************
 * SV-Cart 添加收藏
 *===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 *---------------------------------------------------------------------------
 *这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *不允许对程序代码以任何形式任何目的的再发布。
 *===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
?>
<?php ob_start();?>
<?php
	$local_root_login=$server_host.$this->webroot."users/login";
	if($result['type_flag']=='h'){
		$local_root_list=$server_host.$this->webroot."favorites/hotel_fav";
	}elseif($result['type_flag']=='md'){
		$local_root_list=$server_host.$this->webroot."favorites/md_fav";
	}else{
		$local_root_list=$server_host.$this->webroot."favorites/";
	}
?>
<div id="loginout" class="dialog qatanchu">
  <p style="text-align:right;"><a class="btn closebtn" href="javascript:close_message();"><?php echo $ld['close']?></a></p>
	<?php if($result['type']==0){?>
	<div class="box">
		<?php if($result['type_flag']!='h'&&$result['type_flag']!='md'){ if(isset($product_info['Product']['img_thumb']) && $product_info['Product']['img_thumb'] != ""){?>
		<?php echo $html->image($product_info['Product']['img_thumb'],array("alt"=>$product_info['ProductI18n']['name'],"width"=>75,"height"=>75,"style"=>'float:left'));?>
		<?php }else{?>
		<?php echo $svshow->image('',array("alt"=>$product_info['ProductI18n']['name'],"width"=>75,"height"=>75,"style"=>'float:left'));?>
		<?php }?>
		<p class="collectname"><?php echo $product_info['ProductI18n']['name']?></p>
		<?php }?>
		<p class="collectshow"><?php echo $html->image($themes_host."/theme/default/img/right.png");echo $result['message']." ";?></p>
	</div>
	<p style="text-align:right;"><?php echo $html->link($ld['account_my_wishlist'],$local_root_list,array("class"=>"btn"),false,false);?></p>
	<?php }else{?>
	<div class="box alertinfo">
		<?php if(isset( $ld['favorite_products'])&& $ld['favorite_products']!=""){?>
			<p><?php echo $ld['favorite_products'];?></p>
		<?php }?>
		<?php if($result['type'] != ''){?>
			<p class="collectstyle"><?php if($result['type'] == 1){ echo $svshow->link($result['message'],$local_root_login);}else{echo $result['message'];}?></p>
		<?php }?>
	</div>
	<p style="text-align:right;"><?php echo $html->link($ld['account_my_wishlist'],$local_root_list,array("class"=>"btn"),false,false);?></p>
	<?php }?>
</div>
<?php
	$result['message'] = ob_get_contents();
	ob_end_clean();
	echo json_encode($result);
?>
