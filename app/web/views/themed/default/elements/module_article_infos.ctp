<?php //pr($sm);?>
<div id="article_info" class="am-u-md-9 am-fl">
  <?php if($code_infos[$sk]['type']=="module_article_infos"){?>
  <article class="blog-main">
    <h3 class="am-article-title blog-title"><?php echo $sm['ArticleI18n']['title'];?></h3>
<!--    <h4 class="am-article-meta blog-meta"><?php echo $ld['time']?>:<time><?php echo date("Y-m-d", strtotime($sm['Article']['created']));?></time>&nbsp;&nbsp;<?php if(!empty($sm['ArticleI18n']['author'])){echo $sm['ArticleI18n']['author'];}?></h4>-->
    <div class="blog-content">
	  <div class="am-u-lg-12 auto_zoom">
        <?php echo $sm['ArticleI18n']['content'];?>
	  </div>
	  <div>
	  <?php if($sm['Article']['id']=="122"){?>
		<div class="">
		<?php if(isset($configs['customer-fax']) && !empty($configs['customer-fax'])){?>
		<div class="" style="clear:both;">
	      <label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['fax'] ?></label>
	      <div class="am-u-lg-6 am-u-md-8 am-u-sm-8 am-form-label am-text-left">
	    	<?php echo isset($configs['customer-fax'])?$configs['customer-fax']:"";?>
	      </div>
	    </div>
		<?php }?>
		<?php if(isset($configs['customer-email']) && !empty($configs['customer-email'])){?>
		  <div class="" style="clear:both;">
	        <label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['service_email'] ?></label>
	        <div class="am-u-lg-6 am-u-md-8 am-u-sm-8 am-form-label am-text-left">
	    	  <?php echo isset($configs['customer-email'])?$configs['customer-email']:"";?>
	    	</div>
	      </div>
		<?php }?>
		<?php if(isset($configs['customer-zipcode']) && !empty($configs['customer-zipcode'])){?>
		  <div class="" style="clear:both;">
	        <label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['zip'] ?></label>
	        <div class="am-u-lg-6 am-u-md-8 am-u-sm-8 am-form-label am-text-left">
	    	  <?php echo isset($configs['customer-zipcode'])?$configs['customer-zipcode']:"";?>
	    	</div>
	      </div>
		<?php }?>
		</div>
	  <?php }?>
	  </div>
	</div>
  </article>
  <?php }?>
</div>
<script type="text/javascript">
var wechat_shareTitle="<?php echo $sm['ArticleI18n']['title'] ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($sm['ArticleI18n']['content']); ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if($svshow->imgfilehave($server_host.(str_replace($server_host,'',$sm['ArticleI18n']['img01'])))){ ?>
var wechat_imgUrl="<?php echo $server_host.(str_replace($server_host,'',$sm['ArticleI18n']['img01'])); ?>";
<?php } ?>
</script>