<?php if($route=="PRODUCT"){?>
	<?php if(isset($info)&&$info!=""&&count($info)>0){?>
	<select id='next_homepage' onChange="setHomepage()"> 
		<?php foreach($info as $v){?>
			<option value="<?php echo $v['Product']['id'];?>"><?php echo $v['ProductI18n']['name'];?></option>
		<?php }?>
	</select>
	<?php }elseif(isset($info)&&$info!=""&&count($info)==0){ 
		echo "当前无数据!";
	}?>
	<input type="text" id="keyword" name="p_key" value="" style="width:200px;"><input type="button" class="am-btn am-btn-success am-btn-sm" onclick="changeHome()" value="搜索商品" />
<?php }elseif($route=="ARTICLE"){?>
	<?php if(isset($info)&&$info!=""&&count($info)>0){?>
	<select id='next_homepage' onChange="setHomepage()"> 
		<?php foreach($info as $v){?>
			<option value="<?php echo $v['Article']['id'];?>"><?php echo $v['ArticleI18n']['title'];?></option>
		<?php }?>
	</select>
	<?php }elseif(isset($info)&&$info!=""&&count($info)==0){ 
		echo "当前无数据!";
	}?>
	<input type="text" id="keyword" name="a_key" value="" style="width:200px;"><input type="button" class="am-btn am-btn-success am-btn-sm" onclick="changeHome()" value="搜索文章" />
<?php }else{?>
	<?php if(isset($info)&&$info!=""&&count($info)>0){?>
	<select id='next_homepage' onChange="setHomepage()"> 
	<?php foreach($info as $v){?>
		<?php if($route=="PROMOTION"){?><option value="<?php echo $v['Promotion']['id'];?>"><?php echo $v['PromotionI18n']['title'];?></option><?php }?>
		<?php if($route=="TOPIC"){?><option value="<?php echo $v['Topic']['id'];?>"><?php echo $v['TopicI18n']['title'];?></option><?php }?>
		<?php if($route=="PC"){?><option value="<?php echo $v['CategoryProduct']['id'];?>"><?php echo $v['CategoryProductI18n']['name'];?></option><?php }?>
		<?php if($route=="AC"){?><option value="<?php echo $v['CategoryArticle']['id'];?>"><?php echo $v['CategoryArticleI18n']['name'];?></option><?php }?>
	<?php }?>
	</select>
	<?php }else{ 
		echo "当前无数据!";
	}?>
<?php }?>