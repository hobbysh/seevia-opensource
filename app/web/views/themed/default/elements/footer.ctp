
<?php if(!empty($links)){?>
<div>
<div class="am-g-fixed am-g am-u-sm-centered" style="width:100%;">
  <ul  style="width:100%;" class="am-gallery am-avg-sm-6  am-avg-md-9 am-avg-lg-12 am-gallery-default "data-am-widget="gallery" data-am-gallery="{ pureview: false }" >
  <?php foreach($links as $k=>$v){?>
    <li  class="am-thumbnail   am-icon-sm" style="border:0;">
	    <?php if($v['LinkI18n']['img01']==""){ ?>
			 	  <div style="max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"> <?php echo $svshow->link($v['LinkI18n']['name'],$v['LinkI18n']['url'],array('target'=>$v['Link']['target'])); ?></div>
			  <?php  }else{  ?>
		 <?php echo 	$svshow->seo_link(array('type'=>'IMG','class'=>"dd",'url'=>$v['LinkI18n']['url'],'img'=>$v['LinkI18n']['img01'],'name'=>$v['LinkI18n']['name'],'sub_name'=>$v['LinkI18n']['name'],'class'=>'am-img-sx','target'=>$v['Link']['target']));?>
		  <?php }?>
 </li>
  <?php }?>
  </ul>  
</div>
	</div>
<?php }?>	
<div class="bottom_navigations am-hide-sm-down">
<div class="am-topbar am-container" style="padding:0;margin:0px auto 0;border-bottom:none;">
  <?php if(isset($navigations['B'])){?>
  <ul class="am-nav am-nav-pills am-topbar-nav" >
    <?php $navigations_t_count=count($navigations['B']);
      foreach($navigations['B'] as $k=>$v){ ?>
      <?php if(isset($v['SubMenu']) && sizeof($v['SubMenu']) >0) {  //含二级菜单 ?>
    <li class="am-dropdown am-dropdown-up" data-am-dropdown>
      <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
        <?php echo (isset($v['NavigationI18n']['name']))?$v['NavigationI18n']['name']:"-";?><span class="am-icon-caret-up"></span>
      </a>
      <ul class="am-dropdown-content">
        <li class="am-dropdown-header"><?php echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
        <?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
          <li><?php echo $svshow->link($vv['NavigationI18n']['name'],$vv['NavigationI18n']['url'],array('target'=>$vv['Navigation']['target']));?></li>
        <?php }  // foreach top2?>
      </ul>
    </li>
	<?php }?>
	<?php if(!isset($v['SubMenu']) ) { ?>
	<li><?php echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
    <?php }?>
  <?php } // foreach top1?>
  </ul>
  <?php }?>
</div>
</div>	
<!-- footer -->
<footer class="am-footer am-footer-default ">
  <div class="am-footer-miscs am-hide-sm-down">
    <p class="am-text-center">
        ©&nbsp;<?php echo date('Y');?>&nbsp;<?php echo $configs['shop_name'];?><br class="am-show-sm-only"><?php echo "&nbsp;".$ld['copright'];?>
        <?php echo isset($configs['icp_number'])?$configs['icp_number']:$configs['icp_number'];?>
        <?php if(isset($configs['copyright-display-status'])&&$configs['copyright-display-status']==0){?>Powered by <a href="http://www.seevia.cn" target="_blank">SEEVIA</a><?php }?>
        <?php if(isset($configs['page_loading_info'])&&$configs['page_loading_info']==1){?>
	    <?php echo $ld['footprint']?>&nbsp;<?php echo $this->data['memory_useage'];?>KB&nbsp;<?php echo $ld['system_response_time']?>&nbsp;<?php echo round((getMicrotime() - $GLOBALS['TIME_START'])*1000, 4) . "ms"?><?php }?>
    </p>
    <?php echo isset($configs['google-js'])?$configs['google-js']:''; ?>
  </div>
</footer>
<!-- footer end -->
<div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
  <a href="#top" title=""><i class="am-gotop-icon am-icon-chevron-up"></i></a>
</div>

<!-- 客服 -->
<?php if(isset($configs['customer-open'])&&$configs['customer-open']=='1'){ ?>
<script src="/theme/default/js/kefu.js" type="text/javascript"></script>
<div id="kuzhan" class="customer_service am-hide-sm-only" onmouseover="toBig()" onmouseout="toSmall()">
	<div class="services am-fr">
		<div class="con">
			<ul>
				<?php if(isset($configs['customer-tel'])&&!empty($configs['customer-tel'])){
				$tel_arr=explode(';',$configs['customer-tel']);?>
				<?php foreach($tel_arr as $v){?>
					<li class="phone">
						<a href="tel:<?php echo $v;?>"><?php echo $v;?></a>
					</li>
				<?php }}?>
				<?php if(isset($configs['customer-qq'])&&!empty($configs['customer-qq'])){
				$qq_arr=explode(';',$configs['customer-qq']);
				foreach($qq_arr as $v){ ?>
				<li class="qq">
					<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $v;?>&site=qq&menu=yes">
						<img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $v;?>:41" alt="点击这里给我发消息" style="word-break:break-all" title="点击这里给我发消息">
					</a>
				</li>
				<?php }}?>
				
				<?php if(isset($configs['customer-msn'])&&!empty($configs['customer-msn'])){
						$msn_arr=explode(';',$configs['customer-msn']);
						foreach($msn_arr as $v){?>
					<li class="msn">
						<a target="_blank" href="msnim:chat?contact=<?php echo $v;?>">
							<img style="border-style: none;" src="http://thm.ioco.cn/themed/default/img/msn_messenger.png" style="word-break:break-all"/>
						</a>
		 			</li>
					<?php }}?>
					<?php if(isset($configs['customer-taobao'])&&!empty($configs['customer-taobao'])){
						$taobao_arr=explode(';',$configs['customer-taobao']);
						foreach($taobao_arr as $v){
					?>
						<li class="taobao">
							<a target="_blank" href="http://www.taobao.com/webww/ww.php?ver=3&touid=<?php echo $v;?>&siteid=cntaobao&status=1&charset=utf-8">
								<img border="0" src="http://amos.alicdn.com/realonline.aw?v=2&uid=<?php echo $v;?>&site=cntaobao&s=1&charset=utf-8" style="word-break:break-all" alt="点击这里给我发消息" />
							</a>
		 			</li>
					<?php }}?>
		             			<?php if(isset($configs['customer-skype'])&&!empty($configs['customer-skype'])){
						$skype_arr=explode(';',$configs['customer-skype']);
						foreach($skype_arr as $v){?>
					<li class="skype">
							<a target="_blank" href="callto:<?php echo $v;?>" onclick="return skypeCheck();"><span class="am-icon-skype"></span>&nbsp;Skype</a>
		 			</li>
					<?php }}?>  
					<?php if(isset($configs['customer-yahoo'])&&!empty($configs['customer-yahoo'])){
						$yahoo_arr=explode(';',$configs['customer-yahoo']);
						foreach($yahoo_arr as $v){
						?>
					<li class="yahoo">
							<a href="http://edit.yahoo.com/config/send_webmesg?.target=<?php echo $v;?>&.src=pg" target="_blank" alt="Yahoo Messenger">
		 			</li>
					<?php }}?>
			</ul>
		</div>
	</div>
	<div class="Obtn"><?php echo $ld['contact_customer_service'] ?></div>
</div>
<?php } ?>
<!-- 客服 -->
<script type="text/javascript">
$(".am-img-sx img").addClass("am-img-responsive");
</script>