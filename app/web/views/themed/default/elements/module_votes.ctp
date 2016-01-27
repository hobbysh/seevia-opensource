<?php //pr($sm);?>
<?php if(!empty($sm['Vote']) ){?>
<div id="<?php echo $sk?>" class="div_<?php echo $sk;?> am-g am-g-fixed">
  <div class="am-u-lg-6 am-u-md-6 am-u-sm-12" >
    <div style="max-width:360px;margin:10px auto 20px; margin-top:-10px;">
	<div class="am-titlebar am-titlebar-default am-no-layout" style="margin:0;" data-am-widget="titlebar">
	  <h2 class="am-titlebar-title"><?php echo $code_infos[$sk]['name'];?></h2>
	</div>
	<?php if(sizeof($sm['Vote']) > 0){?>
	<h3 id="votelist_index"><?php echo $sm['VoteI18n']['name'];?></h3>
	<ul id="vote_options_input" class="vote_options_input am-list">
	  <?php if(sizeof($sm['VoteOption']) > 0){foreach($sm['VoteOption'] as $k=>$v){?>
	  <li>
	  	<label class="<?php echo isset($sm['Vote']['can_multi'])&&$sm['Vote']['can_multi']==0?'am-checkbox':'am-radio';?>">
		<input id="<?php echo $v['id'];?>" type="<?php echo isset($sm['Vote']['can_multi'])&&$sm['Vote']['can_multi']==0?'checkbox':'radio';?>" value="<?php echo $v['id'];?>" name="vote_option_id" data-am-ucheck>
		<input type="hidden" name="option_count" value="<?php echo $v['option_count'];?>" />
		<?php echo $v['name'];?></label>
	  </li>
	  <?php }}?>
	</ul>
	<div id="send_vote_botton_8758">
	  <input class="am-btn am-btn-primary am-btn-sm" type="button" value="<?php echo $ld['vote'];?>" id="set_vote_option" />
	</div>
  <?php }?>
  </div>
  </div>
</div>
	<?php //pr($sm) ?>
<script>
$(function(){
	$("#set_vote_option").click(function(){
		var can_multi=<?php echo $sm['Vote']['can_multi']?'false':'true' ?>;
		var data='';
		var option_count="";
		if(can_multi){//可多选
			var data_ck="";
			//添加选项票数
			$(".vote_options_input input[type=checkbox]").each(function(){
				if($(this).prop("checked")){
					option_count=parseInt($(this).parent().find("input[name=option_count]").val())+1;
					//alert(option_count);
					data_ck=data_ck+";"+$(this).val()+","+option_count;
				}
			});
			if(data_ck!=""){
				data=data_ck.substring(1);
			}
		}else{//单选
			option_count=parseInt($(".vote_options_input input[type=radio]:checked").parent().find("input[name=option_count]").val())+1;
			data=$(".vote_options_input input[type=radio]:checked").val()+","+option_count;
			//alert(option_count);
		}
		if(data==''){
			alert('请先选择投票选项');
		}else{
			//alert(data);
			$.ajax({ url: "/votes/save_vote/<?php echo $sm['Vote']['id'] ?>",
	    		dataType:"json",
	    		type:"POST",
	    		data: { 'vote_option_id': data },
	    		success: function(data){
	    			alert(data.msg);
	    			if(data.type==1){
	    				//$('.vote_count b').html(data.vote_count);
	    			}
	  			}
	  		});
		}
	});
});

</script>
<?php }?>
