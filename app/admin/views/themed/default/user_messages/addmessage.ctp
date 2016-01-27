<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<style type="text/css">
	.btnouter{margin:50px;}

</style>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index:100; width: 15%;max-width:200px;">
		    <li><a href="#station_letter_content"><?php echo $ld['station_letter_content']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">	
	<?php echo $form->create('UserMessages',array('action'=>'/addmessage','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"post",'onsubmit'=>'return formsubmit();','class'=>"am-form am-form-horizontal"));?>
		<div id="station_letter_content" class="am-panel am-panel-default">
	  		<div class="am-panel-hd">
				<h4 class="am-panel-title">
					<?php echo $ld['station_letter_content'] ?>
				</h4>
		    </div>
		    <div class="am-panel-collapse am-collapse am-in">
				<div class="am-form-group">
	    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px;"><?php echo $ld['vip'];?>Id：</label>
	    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
						<input type="text" name="user_id" value="<?php echo isset($userinfo)?$userinfo['User']['id']:""; ?>" readonly style="margin-bottom:10px;" />
	    			</div>
	    		</div>
				<div class="am-form-group">
	    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"  style="padding-top:12px;"><?php echo $ld['member_name'];?>：</label>
	    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	    				<input type="text" name="user_name" value="<?php echo isset($userinfo)?$userinfo['User']['name']:""; ?>" readonly  style="margin-bottom:10px;" />
	    			</div>
	    		</div>
				<div class="am-form-group">
	    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"  style="padding-top:12px;"><?php echo $ld['email'];?>：</label>
	    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	    				<input type="text" name="user_email" value="<?php echo isset($userinfo)?$userinfo['User']['email']:""; ?>" readonly style="margin-bottom:10px;" />
	    			</div>
	    		</div>
				<div class="am-form-group">
	    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"  style="padding-top:12px;"><?php echo $ld['title'];?>：</label>
	    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	    				<input type="text" name="msg_title" value="<?php echo isset($messageInfo)?$messageInfo['UserMessage']['msg_title']:""; ?>" />
	    			</div>
	    		</div>
				<div class="am-form-group">
	    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['station_letter_content'];?>：</label>
	    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	    				<textarea cols="80" id="elm1" name="msg_content" rows="10" style="width:auto;height:300px;"><?php echo isset($messageInfo)?$messageInfo['UserMessage']['msg_content']:""; ?></textarea>
	    			</div>
	    		</div>
				<div class="btnouter">
	    			<?php if(isset($messageInfo)){ ?>
	    				<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['back'];?>"  onclick="window.location.href='/admin/user_messages/';"><?php echo $ld['back'];?></button>
					<?php }else{ ?>
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>	
					<?php } ?>
	    		</div>
	    	</div>
	    </div>
		<input type="hidden" name="msg_id" value="<?php echo isset($msg_id)?$msg_id:""; ?>" />	
	<?php echo $form->end();?>
	</div>
</div>

<script>
var editor;
KindEditor.ready(function(K) {
	editor = K.create('#elm1', {
		langType : '',
		cssPath : '/css/index.css',
		filterMode : false
	});
});
</script>