<style type="text/css">
.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: block;vertical-align: top;}
.am-checkbox input[type="checkbox"]{margin-left:0px;}
label{font-weight:normal}
.am-panel-title{font-weight:bold;}
.am-form-label{font-weight:bold;top:-5px;left:10px;}
</style>
<div class="">
	<div class="listsearch">
	<?php echo $form->create('operator_logs',array('action'=>'/','class'=>'am-form am-form-horizontal','name'=>'searchtrash','type'=>"get"));?>
	<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1 am-thumbnails">
	
		<li style="margin-top:10px;">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3  am-form-label  "><?php echo $ld['operation_time']?></label>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
				<input type="text" name="date1"  class="am-form-field"  data-am-datepicker="{theme:'success',locale:'<?php echo $backend_locale; ?>'}"  value="<?php echo @$date1;?>" placeholder="start" readonly>
			</div>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
				<input type="text" name="date2"  class="am-form-field" data-am-datepicker="{theme:'success',locale:'<?php echo $backend_locale; ?>'}"  value="<?php echo @$date2;?>" placeholder="end" readonly>
			</div>
		</li>
				
		<li style="margin-top:10px;">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label  "><?php echo $ld['operator']?></label>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			<select name="operator_id" data-am-selected="{placeholder:'',maxHeight: 300}">
				<option value="0"><?php echo $ld['please_select']?></option>
				<?php foreach( $operator_list as $k=>$v ){?>
				<option value="<?php echo $v['Operator']['id']?>" <?php if($operator_id == $v['Operator']['id']){echo "selected";}?>><?php echo $v['Operator']['name']?>
				</option>
			<?php }?>
			</select>
		</div>
		</li>
				
		<li style="margin-top:10px;">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-3  am-form-label  "><?php echo $ld['keyword']?></label>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			<input type="text" class="am-form-field am-radius" name="keywords" id="keywords" value="<?php echo @$keywords?>" placeholder="<?php echo $ld['ip_address']?>/<?php echo $ld['operation_records']?>" />
		</div>
	
		 
	
		</li>
		<li  style="margin-top:10px;">		
			<label class="am-u-lg-3 am-u-md-4 am-u-sm-4  am-form-label  "> </label>
		     <div  class="am-u-lg-1 am-u-md-1 am-u-sm-1">
		       <button type="submit" class="am-btn am-btn-success am-radius am-btn-sm search_article" value="<?php echo $ld['search']?>">
				<?php echo $ld['search'];?>
			</button> 
			</div>
	     </li>
					
	</ul>	
	<?php echo $form->end();?>
	</div>
	<div class="am-text-right am-btn-group-xs" style="margin:10px 0px;;">			
		<?php if($svshow->operator_privilege("operator_logs_clear")){echo $html->link($ld['operator_logs_clear'],"clearall",array("class"=>"am-btn am-btn-warning am-btn-sm am-radius b_none"),false,false);}?>
	</div>			
	<!--列表-->			
	<?php echo $form->create('operator_logs',array('action'=>'','name'=>"OperatorLogForm","type"=>"get",'onsubmit'=>"return false"));?>
		<div class="am-panel-group am-panel-tree">
			<div class="listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title">
						<div class="am-u-lg-3  am-u-md-3 am-u-sm-2 am-hide-sm-only">
							<label class="am-checkbox am-success" style="font-weight:bold;"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/>
								<?php echo $ld['operation_time']?>
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['ip_address']?></div>
						<div class="am-u-lg-3 am-u-md-4 am-u-sm-3"><?php echo $ld['operation_records']?></div>
						<div class="am-u-lg-3 am-show-lg-only"><?php echo $ld['access_address']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-3"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($OperatorLog_info) && sizeof($OperatorLog_info)>0){foreach($OperatorLog_info as $k=>$v){?>
            <div>
				<div class="listtable_div_btm am-panel-body">
					<div class="am-panel-bd">
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-hide-sm-only">
							<label class="am-checkbox am-success">
								<input type="checkbox" data-am-ucheck name="checkboxes[]" value="<?php echo $v['OperatorLog']['id']?>" />
								<?php $time=explode(" ",$v["OperatorLog"]["created"]);echo $time[0].'<br>'.$time[1];?>
							</label>
						</div>
					
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $v["OperatorLog"]["ipaddress"]; ?></div>
						<div class="am-u-lg-3 am-u-md-4 am-u-sm-3"   style="word-wrap:break-word;"><?php echo $v["OperatorLog"]["info"]; ?></div>
						<div class="am-u-lg-3 am-show-lg-only"  style="word-wrap:break-word;">
							<?php echo str_replace($server_host,"",$v["OperatorLog"]["action_url"]); ?>
						</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-3">
		 
							
						<a class="am-btn am-btn-default am-btn-xs am-text-danger   am-seevia-btn-delete" href="javascript:void(0);" onclick="if(confirm(j_confirm_delete)){window.location.href=admin_webroot+'operator_logs/remove/<?php echo $v['OperatorLog']['id'] ?>';}">
						<span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?>
						</a>

							
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
            </div>
			<?php }}else{?>
				<div class="am-text-center" style="margin:50px;"><?php echo $ld['no_records']?></div>
			<?php }?>
		</div>
		<?php if(isset($OperatorLog_info) && sizeof($OperatorLog_info)>0 && count($OperatorLog_info)>0){?>
			<div id="btnouterlist" class="btnouterlist">
				<div class="am-u-lg-6 am-u-md-12 am-u-sm-12 am-hide-sm-only">
					<label class="am-checkbox am-success" style="display: inline;">
						<input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox" value="checkbox">
						<?php echo $ld['select_all']?>
					</label>&nbsp;
					<button type="button" class="am-btn am-btn-danger  am-btn-sm am-radius" value="" onclick="batch_operations()" ><?php echo $ld['delete']?></button>
				</div>
				<div class="am-u-lg-6 am-u-md-12 am-u-sm-12"><?php echo $this->element('pagers')?></div>
                <div class="am-cf"></div>
			</div>
	<?php } ?>	
	<?php echo $form->end();?>
</div>
<script>
function batch_operations(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select'] ?>");
		return;
	}
	if(confirm("<?php echo $ld['confirm_delete']?>")){
		$.ajax({
			url:admin_webroot+"operator_logs/batch/",
			type:"POST",
			data: postData,
			dataType:"json",
			success:function(data){
				window.location.href = window.location.href;
			}
		});
	}
}
</script>