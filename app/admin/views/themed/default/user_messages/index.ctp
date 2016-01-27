
<style type="text/css">
 .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
 .am-checkbox input[type="checkbox"]{margin-left:0;}
 .am-form-label{font-weight:bold;margin-left:15px;top:-5px;}
 .am-panel-title{font-weight:bold;}
</style>
<div>
	<?php echo $form->create('UserMessages',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","class"=>"am-form am-form-horizontal","type"=>"get",'onsubmit'=>'return formsubmit();'));?>
	
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:14px;"><?php echo $ld['vip'];?>/<?php echo $ld['name'];?>:</label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
					<input type="text" id="mes_keyword"  placeholder="<?php echo $ld['vip']?>Id/<?php echo $ld['member_name'];?>" name="keyword" value="<?php echo isset($keyword)?$keyword:"";?>" class="am-form-field am-radius">
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:12px;"><?php echo $ld['creation_time'];?>:</label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="text" class="am-form-field" placeholder="start" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly/>
				</div>
				<em class=" am-u-lg-1  am-u-md-1 am-u-sm-1  am-text-center" style="padding-top:8px;" >-</em>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="text" class="am-form-field" placeholder="end" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly/>
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-show-lg-only">
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>" ><?php echo $ld['search'];?></button>
				</div>
			</li>
		        <li> 
		        	<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"></label>
		        	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-hide-lg-only">
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>" ><?php echo $ld['search'];?></button>
				</div>
		       </li>
					
					
		</ul>
					
		<input type="hidden" name="blogId" value='<?php echo isset($blogId)?$blogId:""; ?>' />
	<?php echo $form->end();?>
	<?php echo $form->create('user_messages',array('action'=>'/removeAll','name'=>'MessageForm','type'=>'get',"onsubmit"=>"return false;"));?>
		<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
			<?php if($svshow->operator_privilege("user_messages_add")){echo $html->link($ld['log_send_station_letter'],"/user_messages/userview",array("class"=>"addbutton am-btn am-btn-warning am-btn-sm am-radius "));
			}?>
		</div>
		<div class="am-panel-group am-panel-tree"  id="accordion">
			<div class="listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title am-g">
						<div class="am-u-lg-4 am-show-lg-only am-u-md-3 am-u-sm-3">	
							<label class="am-checkbox am-success"  style="font-weight:bold;">
								<input type="checkbox" data-am-ucheck value="checkbox" onclick="listTable.selectAll(this,'checkboxes[]')"/>
							<?php echo $ld['member_name'];?>
							</label>
						</div>
						<div class="am-u-lg-4 am-u-md-6 am-u-sm-5"><?php echo $ld['station_letter_content'];?>&nbsp;</div>
						<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['creation_time'];?>&nbsp;</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><?php echo $ld['operate'];?>&nbsp;</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($MessageInfo)&&sizeof($MessageInfo)>0){foreach($MessageInfo as $k=>$v){?>
				<div>
					<div class=" listtable_div_top am-panel-body">
						<div class="am-panel-bd am-g">
							<div class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-show-lg-only">	
								<label class="am-checkbox am-success">
									<input type="checkbox" data-am-ucheck  name="checkboxes[]" value="<?php echo $v['UserMessage']['id']?>"/>
								<?php echo $v["UserMessage"]["user_name"]; ?>
								</label>
							</div>
							<div class="am-u-lg-4 am-u-md-6 am-u-sm-5"><?php echo $v["UserMessage"]["msg_content"]; ?>&nbsp;</div>
							<div class="am-u-lg-2 am-show-lg-only"><?php echo $v["UserMessage"]["created"]; ?></div>
							<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-btn-gruop-xs">
								<?php if($svshow->operator_privilege("user_messages_edit")){?>
								  <a style="margin-top:2px;" class="am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/user_messages/addmessage/'.$v['UserMessage']['id']); ?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['view']; ?>
                    </a>
							<?php 	 }
								if($svshow->operator_privilege("user_messages_remove")){?>
								     <a style="margin-top:2px;" class="am-btn am-btn-default am-btn-xs am-text-danger   am-seevia-btn-delete" href="javascript: ;" onclick="list_delete_submit(admin_webroot+'user_messages/remove/<?php echo $v['UserMessage']['id'] ?>' );">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
									
										<?php }?>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>
			<?php }}else{?>
					<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
			<?php }?>
		</div>	<?php if(isset($MessageInfo)&&sizeof($MessageInfo)>0){if($svshow->operator_privilege("user_messages_remove")){?>
				
			<div id="btnouterlist" class="btnouterlist">
				    <div class="am-u-lg-3 am-u-md-12 am-u-sm-12">
					        <label class="am-checkbox am-success">
					            <input type="checkbox" onclick="listTable.selectAll(this,'checkboxes[]')" data-am-ucheck />
					            <span><?php echo $ld['select_all'];?></span>
					        </label>&nbsp;&nbsp;
					        <button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="removeAll()"><?php echo $ld['batch_delete'];?></button>
					    <?php }?>
				    </div>
				    <div class="am-u-lg-9 am-u-md-12 am-u-sm-12">
						<?php echo $this->element('pagers');?>
					</div>
	            		<div class="am-cf"></div>
			</div>
		
	<?php }?>
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
function formsubmit()
{
	var keyword=document.getElementById("mes_keyword").value;
	var start_date_time=document.getElementByName("start_date_time")[0].value;
	var end_date_time=document.getElementByName("end_date_time")[0].value;
	
	var url="keyword="+keyword+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time;
}
function removeAll()
{
	var ck=document.getElementsByName('checkboxes[]');
	var j=0;
	for(var i=0;i<=parseInt(ck.length)-1;i++)
	{
		if(ck[i].checked)
		{
			j++;
		}
	}
	if(j>=1){
		if(confirm(j_confirm_delete))
		{
			batch_action()
		}
	}
}
function batch_action()
{
	document.MessageForm.action=admin_webroot+"user_messages/removeAll";
	document.MessageForm.onsubmit= "";
	document.MessageForm.submit();
}
</script>