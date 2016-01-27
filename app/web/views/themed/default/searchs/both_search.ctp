

<?php
	$serach_title=false;
	if(!isset($searchtype)||isset($searchtype)&&$searchtype==''){$serach_title=true;}
?>
<div class="am-search-show">
    <?php if(isset($searchtype)&&$searchtype!='A'){ ?>
    <div class="am-g am-g-fixed">
        <div style="margin-top:10px;" class="am-panel am-panel-default">
	        <div class="am-panel-hd my-head"><?php echo $ld['product'] ?>(<?php echo !empty($products)?sizeof($products):0; ?>)</div>
	        <div class="am-panel-bd">
                    <div>
                        <!-- search condition -->
                        <?php if(isset($search_categories)||isset($search_brand)||isset($search_attribute)){ ?>
        				<div class="am-g search_condition_list">
        				  <div class="am-u-lg-1 am-u-md-2 am-u-sm-2">已选条件:</div>
        				  <div class="am-u-lg-9 am-u-md-8 am-u-sm-7">
        				  	<?php if(isset($search_categories)&&trim($search_categories)!=""){
        							$other_condition="1=1";
        							if(isset($_GET['searchtype'])){
        								$other_condition.="&searchtype=".$_GET['searchtype'];
        							}
        							if(isset($_GET['keyword'])){
        								$other_condition.="&keyword=".$_GET['keyword'];
        							}
        							if(isset($search_brand)&&trim($search_brand)!=""){
        								$other_condition.="&search_brand=".$search_brand;
        							}
        							if(isset($search_attribute)&&!empty($search_attribute)){
        								foreach($search_attribute as $kk=>$vv){
                                                $other_condition.="&search_attribute[]=".$kk.";".$vv;
                                        }
        							}
        							if(isset($search_price_start)&&trim($search_price_start)!=""){
        								$other_condition.="&search_price_start=".$search_price_start;
        							}
        							if(isset($search_price_end)&&trim($search_price_end)!=""){
        								$other_condition.="&search_price_end=".$search_price_end;
        							}
        							$search_categories_str=split(";",$search_categories);
        							?>
        						<a href="<?php echo $html->url('/searchs/keyword/'.$keyword.'?'.$other_condition) ?>"><?php echo $ld['categories'] ?>:<em><?php echo $search_categories_str[1]; ?></em><span class='am-icon-close'></span></a>
        					<?php } ?>
        					<?php if(isset($search_attribute)&&!empty($search_attribute)){
        							$other_condition="1=1";
        							if(isset($_GET['searchtype'])){
        								$other_condition.="&searchtype=".$_GET['searchtype'];
        							}
        							if(isset($_GET['keyword'])){
        								$other_condition.="&keyword=".$_GET['keyword'];
        							}
        							if(isset($search_categories)&&trim($search_categories)!=""){
        								$other_condition.="&search_categories=".$search_categories;
        							}
        							if(isset($search_brand)&&trim($search_brand)!=""){
        								$other_condition.="&search_brand=".$search_brand;
        							}
        							if(isset($search_price_start)&&trim($search_price_start)!=""){
        								$other_condition.="&search_price_start=".$search_price_start;
        							}
        							if(isset($search_price_end)&&trim($search_price_end)!=""){
        								$other_condition.="&search_price_end=".$search_price_end;
        							}
                                    foreach($search_attribute as $k=>$v){
                                        if(!isset($product_attribute_datas[$k])){continue;}
                                        foreach($search_attribute as $kk=>$vv){
                                            if($kk!=$k){
                                                $other_condition.="&search_attribute[]=".$kk.";".$vv;
                                            }
                                        }
        							?>
        						<a href="<?php echo $html->url('/searchs/keyword/'.$keyword.'?'.$other_condition) ?>"><?php echo $product_attribute_datas[$k]['name']; ?>:<em><?php echo $v; ?></em><span class='am-icon-close'></span></a>
                                    <?php } ?>
                                        
        					<?php }?>
        					<?php if(isset($search_brand)&&trim($search_brand)!=""){ 
        							$other_condition="1=1";
        							if(isset($_GET['searchtype'])){
        								$other_condition.="&searchtype=".$_GET['searchtype'];
        							}
        							if(isset($_GET['keyword'])){
        								$other_condition.="&keyword=".$_GET['keyword'];
        							}
        							if(isset($search_categories)&&trim($search_categories)!=""){
        								$other_condition.="&search_categories=".$search_categories;
        							}
        							if(isset($search_attribute)&&!empty($search_attribute)){
        								foreach($search_attribute as $kk=>$vv){
                                            $other_condition.="&search_attribute[]=".$kk.";".$vv;
                                        }
        							}
        							if(isset($search_price_start)&&trim($search_price_start)!=""){
        								$other_condition.="&search_price_start=".$search_price_start;
        							}
        							if(isset($search_price_end)&&trim($search_price_end)!=""){
        								$other_condition.="&search_price_end=".$search_price_end;
        							}
        							$search_brand_str=split(";",$search_brand);
        							?>
        						<a href="<?php echo $html->url('/searchs/keyword/'.$keyword.'?'.$other_condition) ?>"><?php echo $ld['brand_mfg'] ?>:<em><?php echo $search_brand_str[1]; ?></em><span class='am-icon-close'></span></a>
        					<?php }?>
        				  </div>
        				</div>
        				<?php } ?>
        				
        				
        				<?php if(isset($categories)&&sizeof($categories)>0&&!isset($search_categories)){ ?>
        				<div class="am-g search_condition">
        				  <div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['categories'] ?>:</div>
        				  <div class="am-u-lg-9 am-u-md-8 am-u-sm-7">
        				    <?php
        					$other_condition="";
        					if(isset($_GET['searchtype'])){
        						$other_condition.="&searchtype=".$_GET['searchtype'];
        					}
        					if(isset($_GET['keyword'])){
        						$other_condition.="&keyword=".$_GET['keyword'];
        					}
        					if(isset($search_brand)&&trim($search_brand)!=""){
        						$other_condition.="&search_brand=".$search_brand;
        					}
        					if(isset($search_attribute)&&!empty($search_attribute)){
								foreach($search_attribute as $kk=>$vv){
                                    $other_condition.="&search_attribute[]=".$kk.";".$vv;
                                }
							}
        					if(isset($search_price_start)&&trim($search_price_start)!=""){
        						$other_condition.="&search_price_start=".$search_price_start;
        					}
        					if(isset($search_price_end)&&trim($search_price_end)!=""){
        						$other_condition.="&search_price_end=".$search_price_end;
        					}
        					foreach($categories as $k=>$v){ ?>
        							<a href="<?php echo $html->url('/searchs/keyword/'.$keyword.'?search_categories='.$k.';'.$v.$other_condition) ?>"><?php echo $v; ?></a>
        						<?php } ?>
        				  </div>
        				</div>
        				<?php } ?>
        					  
        				<?php if(isset($brand_names)&&sizeof($brand_names)>0&&!isset($search_brand)){ ?>
        				<div class="am-g search_condition">
        				  <div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['brand_mfg'] ?>:</div>
        				  <div class="am-u-lg-9 am-u-md-8 am-u-sm-7">
        				    <?php
        						$other_condition="";
        						if(isset($_GET['searchtype'])){
        							$other_condition.="&searchtype=".$_GET['searchtype'];
        						}
        						if(isset($_GET['keyword'])){
        							$other_condition.="&keyword=".$_GET['keyword'];
        						}
        						if(isset($search_categories)&&trim($search_categories)!=""){
        							$other_condition.="&search_categories=".$search_categories;
        						}
	        					if(isset($search_attribute)&&!empty($search_attribute)){
    								foreach($search_attribute as $kk=>$vv){
                                        $other_condition.="&search_attribute[]=".$kk.";".$vv;
                                    }
							    }
        						if(isset($search_price_start)&&trim($search_price_start)!=""){
        							$other_condition.="&search_price_start=".$search_price_start;
        						}
        						if(isset($search_price_end)&&trim($search_price_end)!=""){
        							$other_condition.="&search_price_end=".$search_price_end;
        						}
        						foreach($brand_names as $k=>$v){ ?>
        							<a class="search_brand" href="<?php echo $html->url('/searchs/keyword/'.$keyword.'?search_brand='.$k.';'.$v.$other_condition) ?>"><?php echo $v; ?></a>
        						<?php } ?>
        				  </div>
        				</div>
        				<?php } ?>
        					  
        				<?php if(isset($product_attribute_datas)&&sizeof($product_attribute_datas)>0){foreach($product_attribute_datas as $v){ 
                                if(isset($search_attribute[$v['id']])){continue;}
                        ?>
        				<div class="am-g search_condition">
        				  <div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $v['name'] ?>:</div>
        				  <div class="am-u-lg-9 am-u-md-8 am-u-sm-7">
        				    <?php foreach($v['option'] as $vv){ ?>
                                <a class="search_attribute" href="<?php echo $html->url('/searchs/keyword/'.$keyword.'?search_attribute[]='.$v['id'].';'.$vv.$other_condition) ?>"><?php echo $vv; ?></a>
                            <?php } ?>
        				  </div>
        				</div>
        				<?php }} ?>
        				
        				<div class="am-g search_condition">
        				  <div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['price'] ?>:</div>
        				  <div class="am-u-lg-9 am-u-md-8 am-u-sm-7" style="padding:0;">
        				    	<?php
        								$other_condition="";
        								if(isset($_GET['searchtype'])){
        									$other_condition.="&searchtype=".$_GET['searchtype'];
        								}
        								if(isset($_GET['keyword'])){
        									$other_condition.="&keyword=".$_GET['keyword'];
        								}
        								if(isset($search_categories)&&trim($search_categories)!=""){
        									$other_condition.="&search_categories=".$search_categories;
        								}
        								if(isset($search_brand)&&trim($search_brand)!=""){
        									$other_condition.="&search_brand=".$search_brand;
        								}
        								if(isset($search_attribute)&&!empty($search_attribute)){
            								foreach($search_attribute as $kk=>$vv){
                                                $other_condition.="&search_attribute[]=".$kk.";".$vv;
                                            }
        							    }
        						?>
        								<div class="am-u-lg-2 am-u-md-2 am-u-sm-4">
        									<input type="text" class="am-form-field am-input-sm" id="search_price_start" value="<?php echo isset($search_price_start)?$search_price_start:'' ?>">
        								</div>
        						    	<div class="am-u-lg-1" style="width:10px;max-width:10px;min-width:10px;padding:0px;margin:0;">-</div>
        						    	<div class="am-u-lg-2 am-u-md-2 am-u-sm-4">
        						        <input type="text" class="am-form-field am-input-sm" id="search_price_end" value="<?php echo isset($search_price_end)?$search_price_end:'' ?>">
        						      </div>
        						      <button class="am-btn am-btn-default am-btn-sm" type="button" onclick="search_price('<?php echo $html->url('/searchs/keyword/'.$keyword.'?'.$other_condition) ?>')"><?php echo $ld['confirm'] ?></button>
        				  </div>
        				</div>
                        <!-- search condition -->
                        
                        
                        <?php if(!empty($products)){ ?>
				<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-overlay" data-am-gallery="{ }">
				<?php foreach($products as $k=>$v){ ?>
					<li>
						<div class="am-gallery-item">
						<?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
							<span class="like_icon am-gallery-like" style="">
							  <img id="<?php echo $v['Product']['id'];?>" style="width:15px;height:15px;" src="/theme/default/img/like_icon.png" />
							  <span id="<?php echo 'like_num'.$v['Product']['id'];?>" class="like_num">
							    <?php if(isset($v['Product']['like_stat'])){echo $v['Product']['like_stat'];}else{echo '0';}?>
							  </span>
							</span>
						<?php } ?>
							<?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>($v['Product']['img_detail']!=''?$v['Product']['img_detail']:$configs['products_default_image']),'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
							<h3 class="am-gallery-title">
					          <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
					      	</h3>
					      </div>
					      <div class="am-g pro_price pro_unit">
			                      	<?php if(isset($configs['show_product_price_onlist'])&&$configs['show_product_price_onlist']=='1'){ if(isset($v['price_range'])){echo $svshow->price_format($v['price_range']['min_price'],$configs['price_format'])." -".$svshow->price_format($v['price_range']['max_price'],$configs['price_format']);}else{echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']);}} ?>
			                      	<?php if(isset($configs['show_product_unit'])&&$configs['show_product_unit']=='1'){ echo $v['Product']['unit'];} ?>
			            		</div>
					</li>
				<?php } ?>
				</ul>
					<!-- 商品列表分页 -->
					<?php if($pages_list['pageCount']>=1){?>
					<div class="pages">
						<?php
						if($pagination->setPaging($pages_list)):
							$leftArrow = " < ".$ld['previous'];
							$rightArrow = $ld['next']." >";
							$prev = $pagination->prevPage($leftArrow,false);
							$prev = $prev?$prev:$leftArrow;
							$next = $pagination->nextPage($rightArrow,false);
							$next = $next?$next:$rightArrow;
							$pages = $pagination->pageNumbers("	 ");
							echo $prev." ".$pages." ".$next;
						endif;
						?>
					</div>
					<?php }?>
					<!-- 商品列表分页 end  -->
				<?php }else{ ?>
					<p style="margin: 30px;text-indent: 4em;font-size: 18px;"><?php
						if(isset($keyword)){
								printf($ld['no_find_related_products'],$keyword);
						}else{
							echo $ld['no_related_products'];
						}
					?></p>
				<?php } ?>
                        
                        
                    </div>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <?php if(isset($searchtype)&&$searchtype!='P'){ ?>
    <div class="am-g am-g-fixed">
        <div style="margin-top:10px;" class="am-panel am-panel-default">
            <?php if($serach_title){ ?>
            <div class="am-panel-hd my-head"><?php echo $ld['article'] ?>(<?php echo !empty($articles)?sizeof($articles):0; ?>)</div>
            <?php } ?>
            <div class="am-panel-bd">
                <div>
                    
                    <?php if(!empty($articles)){?>
    					<ul class="am-list am-list-striped">
    						<?php foreach($articles as $k=>$v){ ?>
    							<li><?php echo $svshow->seo_link(array('type'=>'A', 'name'=>$v['ArticleI18n']['title'], 'sub_name'=>$v['ArticleI18n']['title'], 'id'=>$v['Article']['id']));?></li>
    						<?php } ?>
    					</ul>
    				<?php }else{ ?>
    					<p style="margin: 30px;text-indent: 4em;font-size: 18px;"><?php echo $ld['common_001'];?></p>
    				<?php } ?>
    				<?php echo $this->element('pager');?>
                    
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
     
</div>

<script type="text/javascript">
$(function(){
	var Window_H=$(window).height();
	$(".am-search-show .am-tabs .am-tabs-bd").css("min-height",((Window_H*0.6).toFixed(2))+"px");
	$(".detail-h3 img").each(function(){
		var _thisImgObj=$(this);
		var img_src=$(this).attr("src");
		if(img_src==""){
			_thisImgObj.attr("src","<?php echo $configs['shop_default_img'] ?>");
		}else{
			var ImgObj=new Image();
			ImgObj.onerror=function(){
				_thisImgObj.attr("src","<?php echo $configs['shop_default_img'] ?>");
			}
			ImgObj.src=img_src;
		}
	});
})
</script>