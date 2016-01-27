<?php if($code_infos[$sk]['type']=="module_product"){ ?>
<div class="am-g am-g-fixed">
  <div class="am-panel am-panel-default" style="margin-top:10px;margin-bottom:-10px;">
	<div class="am-panel-hd my-head"><?php echo $code_infos[$sk]['title'];?></div>
     	<div  class="am-panel-bd">
         	<div class="am-slider am-slider-default am-slider-carousel" data-am-flexslider="{itemWidth: 200,itemHeight:180, itemMargin: 5, slideshow: false}">
              <ul class="am-slides" id="sm-width-sm">
                <?php foreach($sm as $k=>$p){?>
            	  <li>
            	    <div class="am-gallery-item">
            		  <?php echo $svshow->seo_link(array('type'=>'P','class'=>"am-col",'id'=>$p['Product']['id'],'img'=>($p['Product']['img_detail']!=''?$p['Product']['img_detail']:$configs['products_default_image']),'name'=>$p['ProductI18n']['name'],'sub_name'=>$p['ProductI18n']['name']));?>
                      <?php echo $svshow->seo_link(array('type'=>'P','id'=>$p['Product']['id'],'name'=>$p['ProductI18n']['name'],'sub_name'=>$p['ProductI18n']['name']));?>
                      <div class="am-g pro_price pro_unit" style="padding:0 10px;">
                      	<?php if(isset($configs['show_product_price_onlist'])&&$configs['show_product_price_onlist']=='1'){ if(isset($p['price_range'])){echo $svshow->price_format($p['price_range']['min_price'],$configs['price_format'])." -".$svshow->price_format($p['price_range']['max_price'],$configs['price_format']);}else{echo $svshow->price_format($p['Product']['shop_price'],$configs['price_format']);}} ?>
                      	<?php if(isset($configs['show_product_unit'])&&$configs['show_product_unit']=='1'&&!empty($p['Product']['unit'])){ echo "/".$p['Product']['unit'];} ?>
                      </div>
        	    </div>
            	  </li>
            	<?php }?>
              </ul>
            </div>
    	</div>
    </div>
</div>
<?php }?>
<style type="text/css">
.am-slider-default .am-control-nav{display:none;}
@media only screen and (max-width:640px){
   #sm-width-sm li{
     width:120px !important;
   }
}


</style>
