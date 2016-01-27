<style type="text/css">
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
 .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
</style>
<div>
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php if($svshow->operator_privilege('topics_add')){?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/topics/view'); ?>"><span class="am-icon-plus"></span> <?php echo $ld['add'] ?></a>
		<?php }?>
	</div>
	<div  id="accordion">	
	<?php echo $form->create('',array('action'=>'','name'=>"TopicForm","type"=>"post",'onsubmit'=>"return false"));?>
		<div class="am-panel-group am-panel-tree">
			<div class="listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title am-g">
						<div class="am-u-lg-4 am-u-md-6 am-u-sm-5 am-u-md-2">
							<label class="am-checkbox am-success am-hide-sm-only" style="font-weight:bold;"><input type="checkbox" name="chkall" value="checkbox" onclick="listTable.selectAll(this,'checkboxes[]');" data-am-ucheck/>
								<?php echo $ld['topic_name']?>
							</label>
		                                	<label class="am-checkbox am-success am-show-sm-only" style="font-weight:bold;"> 
								<?php echo $ld['topic_name']?>
							</label>
						</div>
				            <div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['start_time']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['end_time']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-text-center"><?php echo $ld['status']?></div>
						<div class="am-u-lg-2 am-show-lg-only am-text-center"><?php echo $ld['sort']?></div>
						<div class="am-u-lg-3 am-u-md-4 am-u-sm-5"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($topics) && sizeof($topics) > 0){foreach($topics as $k=>$topic){?>
				<div>
					<div class=" listtable_div_top am-panel-body">
						<div class="am-panel-bd am-g">
							<div class="am-u-lg-4 am-u-md-6 am-u-sm-5 am-u-md-2">
								<label class="am-checkbox am-success am-hide-sm-only">
									<input type="checkbox" name="checkboxes[]" value="<?php echo $topic['Topic']['id']?>"  data-am-ucheck/>
									<?php echo $topic['TopicI18n']['title']?>
								</label>
					                    <label class="am-checkbox am-success am-show-sm-only  ">
								  <?php echo $topic['TopicI18n']['title']?>
								</label>
							</div>
						 
							<div class="am-u-lg-1 am-show-lg-only">
								<?php if(isset($topic['Topic']['start_time'])&& $topic['Topic']['start_time']!="0000-00-00 00:00:00"){ echo date("Y-m-d",strtotime($topic['Topic']['start_time']));}else{ echo "-";}?>
							</div>
							<div class="am-u-lg-1 am-show-lg-only">
								<?php if(isset($topic['Topic']['end_time'])&& $topic['Topic']['end_time']!="0000-00-00 00:00:00"){ echo date("Y-m-d",strtotime($topic['Topic']['end_time']));}else{ echo "-";}?>
							</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-text-center">
							<?php if($topic['Topic']['status']==1){?>
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this, 'Topics/toggle_on_status', '<?php echo $topic['Topic']['id'];?>')">&nbsp;</span>
								<?php }elseif($topic['Topic']['status'] == 0){?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'Topics/toggle_on_status','<?php echo $topic['Topic']['id'];?>')">&nbsp;</span>	
							<?php }?>
						</div>
							<div class="am-u-lg-2 am-show-lg-only am-text-center">
								<?php if($svshow->operator_privilege("topics_edit")){
									if(sizeof($topics)==1){echo "-";}else if($k==0){ ?>
										<a onclick="changeOrder('down','<?php echo $topic['Topic']['id'];?>','0',this)">&#9660;</a>
								<?php }else if($k==sizeof($topics)-1){ ?>
										<a onclick="changeOrder('up','<?php echo $topic['Topic']['id'];?>','0',this)" style="color:#cc0000;">&#9650;</a>
								<?php }else{ ?>
										<a onclick="changeOrder('up','<?php echo $topic['Topic']['id'];?>','0',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $topic['Topic']['id'];?>','0',this) ">&#9660;</a>
								<?php }}else{echo $topic['Topic']['orderby'];}
								?>
							</div>
							<div class="am-u-lg-3 am-u-md-4 am-u-sm-5 am-btn-group-xs am-action">
 							 
								<?php 
                                
$preview_url=$svshow->seo_link_path(array('type'=>'T','id'=>$topic['Topic']['id'],'name'=>$topic['TopicI18n']['title'],'sub_name'=>$ld['preview']));                  ?>
								    <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-view" target='_blank' href="<?php echo $preview_url; ?>"><span class="am-icon-eye"></span> <?php echo $ld['preview']; ?></a>
								 <?php  if($svshow->operator_privilege("topics_edit")){?>
									<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/topics/view/'.$topic['Topic']['id']); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a>
								<?php }?>
								<?php if($svshow->operator_privilege("topics_remove")){?>
									<a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'topics/remove/<?php echo $topic['Topic']['id'] ?>');"><span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?></a>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
			<?php }}else{?>
				<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
			<?php }?>
		</div>
		<?php if($svshow->operator_privilege("topics_remove")){if(isset($topics) && sizeof($topics)){?>
			<div id="btnouterlist" class="btnouterlist" >
				   <div class="am-u-lg-3 am-u-md-5 am-u-sm-3 am-hide-sm-only" style="margin-left:7px;">
					<label class="am-checkbox am-success">
						<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" value="checkbox" data-am-ucheck>
						<?php echo $ld['select_all']?>
					</label>&nbsp;&nbsp; 
					<button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" value="" onclick="diachange()" >
						<?php echo $ld['batch_delete']?></button>
				 </div>
				<div class="am-u-lg-8 am-u-md-6 am-u-sm-9  ">		
					<?php echo $this->element('pagers')?>
				</div><div class="am-cf"></div>
			</div>
			<?php }?>
			<?php }?>
	<?php echo $form->end();?>
	</div>
</div>
<script type="text/javascript">
function diachange(){
    var id=document.getElementsByName('checkboxes[]');
    var i;
    var j=0;
    var image="";
    for( i=0;i<=parseInt(id.length)-1;i++ ){
      if(id[i].checked){
        j++;
      }
    }
    if( j>=1 ){
    // layer_dialog_show('确定删除?','batch_action()',5);
      if(confirm("<?php echo $ld['confirm_delete']?>"))
      {
        batch_action();
      }
    }else{
    // layer_dialog_show('请选择！','batch_action()',3);
      if(confirm(j_please_select))
      {
        return false;
      }
    }
  }
function batch_action()
{
	document.TopicForm.action=admin_webroot+"topics/batch";
	document.TopicForm.onsubmit= "";
	document.TopicForm.submit();
}
function changeOrder(updown,id,next,thisbtn){
	//changeHtml(thisbtn);
    var page="<?php echo $datapage; ?>";
		$.ajax({
		url:"/admin/topics/changeorder/"+updown+"/"+id+"/"+next+"/"+page,
		type:"POST",
		data:{ },
		dataType:"html",
		success:function(data){
			var popcontent = document.createElement('div');
				popcontent.innerHTML = data;
				var tmp = $(popcontent).find('#tablelist').html();
				$("#tablelist").html(tmp);
				$("#tablelist input[type=checkbox]").uCheck();
		}
	});
}
function change_state(obj,func,id){
	var ClassName=$(obj).attr('class');
	var val = (ClassName.match(/yes/i)) ? 0 : 1;
	var postData = "val="+val+"&id="+id;
	$.ajax({
		url:admin_webroot+func,
		Type:"POST",
		data: postData,
		dataType:"json",
		success:function(data){
			if(data.flag == 1){
				if(val==0){
					$(obj).removeClass("am-icon-check am-yes");
					$(obj).addClass("am-icon-close am-no");
				}
				if(val==1){
					$(obj).removeClass("am-icon-close am-no");
					$(obj).addClass("am-icon-check am-yes");
				}
			}
		
		}	
	});
}

</script>