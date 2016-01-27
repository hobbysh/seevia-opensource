<style>
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
</style>	
	
	
<div>
	<div class="am-panel-group am-panel-tree">
		<div class="am-panel am-panel-default am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					<div class="am-u-lg-1 am-show-lg-only"></div>
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $ld['language_name']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['language_icon']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['language_code']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['default_option']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['front_using']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['background_using']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($languages) && sizeof($languages)>0){foreach($languages as $k=>$language){?>
			<?php if(!isset($apps['Applications'][strtoupper('app-lang-'.$language['Language']['locale'])]) ||  $apps['Applications'][strtoupper('app-lang-'.$language['Language']['locale'])]['status']==0) continue;?>
			<div>
			<div class="am-panel am-panel-default am-panel-body">
				<div class="am-panel-bd">	
					<div class="am-u-lg-1 am-show-lg-only"></div>
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $language['Language']['name']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php if($language['Language']['img01'])echo $html->image($language['Language']['img01'])?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php if($language['Language']['map'])echo $language['Language']['locale'];?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
						<?php if($language['Language']['is_default']){?><span class="am-icon-check am-yes"></span><?php }else{?><span class="am-icon-close am-no"></span>
					<?php }?>
					</div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
						<?php if($language['Language']['front']){?><span class="am-icon-check am-yes"></span><?php } else{?><span class="am-icon-close am-no"></span><?php }?>
					</div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
						<?php if($language['Language']['backend']){?><span class="am-icon-check am-yes"></span><?php } else{?><span class="am-icon-close am-no"></span><?php }?>
					</div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-btn-group-xs am-action">
						<?php if($svshow->operator_privilege("languages_edit")){?>
		<!--echo $html->link($ld['edit'],"/languages/view/{$language['Language']['id']}",array("class"=>"am-btn am-btn-success am-radius am-btn-sm"));-->
		
							 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/languages/view/'.$language['Language']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
						<?php 	}?>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
			</div>
		<?php }}?>
		<?php if(!empty($lost) && 1==2){foreach($lost as $k=>$v){?>
			<div>
				<div class="am-panel am-panel-default am-panel-body">
					<div class="am-panel-bd">	
						<div class="am-u-lg-1 am-show-lg-only">--</div>
						<div class="am-u-lg-1 am-u-md-4 am-u-sm-4"><?php echo $v["name"];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php if($v['img01'])echo $html->image($v['img01'])?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php if($v['map'])echo $v['locale'];?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $html->image('no.gif');?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $html->image('no.gif');?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $html->image('no.gif');?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $html->link($ld['install'],"/languages/install/{$v['locale']}");?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		<?php }}?>			
	</div>
</div>