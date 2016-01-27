<style>
 .am-form-label{font-weight:bold;}
 .am-panel-title{font-weight:bold;}
 .am-form-horizontal .am-form-label{padding-top: 0.4em;}
</style>
<div>
	<?php echo $form->create('UserMessages',array('action'=>'/userview','name'=>"SeearchForm",'id'=>"SearchForm","class"=>"am-form am-form-horizontal","type"=>"get",'onsubmit'=>'return formsubmit();'));?>
		<ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1">
			<li>
				<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:10px;"><?php echo $ld['vip'];?>Id/<?php echo $ld['member_name'];?>/<?php echo $ld['email'];?>：</label>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					<input type="text" class="am-form-field am-radius" placeholder="" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:"";?>" />
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="搜索" >搜索</button>
				</div>
			</li>
		</ul>
	<?php echo $form->end();?>
		<div class="am-panel-group am-panel-tree"  id="accordion" style="margin-top:30px;">
			<div class=" listtable  am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title am-g">
						 
						<div class="am-u-lg-5 am-u-md-5 am-u-sm-5"><?php echo $ld['member_name'];?></div>
						<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $ld['email'];?></div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['operate'];?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($userinfo)&&count($userinfo)>0){foreach($userinfo as $k=>$v){?>
				<div>
					<div class="listtable_div_top am-panel-body am-btn-group-xs">
						<div class="am-panel-bd am-g"> 
					             <div class="am-u-lg-5 am-u-md-5 am-u-sm-5"><?php echo $v["User"]["name"]; ?></div>
							<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $v["User"]["email"]; ?></div>
							<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
								<a class="am-btn am-btn-default am-btn-xs "href='/admin/user_messages/addmessage/?user_id=<?php echo $v["User"]["id"]; ?>'><?php echo $ld['log_send_station_letter'];?></a>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>
			<?php }}else{?>
				<div style="margin:50px;text-align:center;">
					<div>没有找到会员</div>
				</div>
			<?php }?>	
		</div>
		<div id="btnouterlist" class="btnouterlist">
			<?php echo $this->element('pagers');?>
		</div>
	</div>