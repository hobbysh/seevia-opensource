<style type="text/css">
.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;}
.am-panel-title div{font-weight:bold;}
</style>
<div class="listsearch" style="margin-top:10px;" ><?php echo $form->create('Operators',array('action'=>'/','name'=>"SearchForm","type"=>"get"));?>
<p style="margin-left:17px;">
<?php if($svshow->operator_privilege('dealer_list')){?>
				<?php if($_SESSION['type']=="S"){?>
				 
				<select data-am-selected="{ btnSize: 'sm', btnStyle: '#CCCCCC'}" name="type" id="type" onchange="on_hide()">
					  <option value="all_export_csv"><?php echo $ld['all_data']; ?></option>
					<option value="S" id="check1" <?php if(isset($type)&&$type=="S"){echo "selected";}?>>
						<?php echo $ld['system']?>
					</option>
				
				</select>
				       <label id="labden" style="<?php if(isset($type)&&$type=='D'){echo 'inline-block';}else{echo 'display:none;';}?>">
					<input type="text" id="deal" style="width:80px"/>
					<button type="button" id="add_dealer_button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"  onclick="search_dealer();"><?php echo $ld['search'];?></button> 
				</label>
				<label id="dealer_id_select"  style="<?php if(isset($type)&&$type=='D'){echo 'inline-block';}else{echo 'display:none;';}?>">
					<select data-am-selected="{btnWidth:130, btnSize: 'xs', btnStyle: '#CCCCCC'}" name="type_id" id="dealer_id" style="<?php if(isset($type)&&$type=='D'){echo 'inline-block';}else{echo 'display:none;';}?>">
						<option value="0"><?php echo $ld['please_select']?></option>
					</select>
				</label>
		<?php }else{?>
				<select  name="type" id="type" onchange="on_hide()" style="width:100px">
					<option value="D" id="checkbox" <?php if(isset($type)&&$type=="D"){echo "selected";}?>>
						<span><?php echo $ld['dealer']?></span>
					</option>
				</select>
				<label id="labden" style="<?php if(isset($type)&&$type=='D'){echo 'inline-block';}else{echo 'display:none;';}?>">
					<input type="text" id="deal" style="80px"/>
					<input type="button" id="add_dealer_button"  onclick="search_dealer();" value="<?php echo $ld['search']?>"/>
				</label>
				<label id="dealer_id_select" style="<?php if(isset($type)&&$type=='D'){echo 'inline-block';}else{echo 'display:none;';}?>">
					<select data-am-selected="{btnWidth:130, btnSize: 'xs', btnStyle: '#CCCCCC'}" name="type_id" id="dealer_id" style="<?php if(isset($type)&&$type=='D'){echo 'inline-block';}else{echo 'display:none;';}?>">
						<option value="0"><?php echo $ld['please_select']?></option>
					</select>
				</label>
			<?php }?>
		<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"  onclick="changeOperators()"><?php echo $ld['search'];?></button>
<?php }?>
</p>
	<?php echo $form->end();?> 
</div>
<div class="am-g am-other_action ">
	<div class="am-fr am-u-lg-12 am-btn-group-xs" style="text-align:right;margin-bottom:10px;margin-right:15px;">
		<a class="am-btn am-btn-sm am-btn-default" href="<?php echo $html->url('/operator_logs'); ?>">
			<?php echo $ld['log_operation'] ?>
		</a>
		<a class="am-btn am-btn-sm am-btn-default" href="<?php echo $html->url('/roles'); ?>">
			<?php echo $ld['operator_roles'] ?>
		</a>
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('//operators/view/0'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a>
	</div>
</div>
  <div class="am-panel-group am-panel-tree">
		<div class="listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title am-g">
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-5">
						<label class="am-checkbox am-success" style="font-weight:bold;"><div class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></div>
							<?php echo $ld['operator']?>
						</label>
					</div>
					<div class="am-u-lg-2  am-u-md-3 am-hide-sm-only">Email</div>
					<div class="am-u-lg-1  am-u-md-2 am-u-sm-3"><?php echo $ld['mobile']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status']?></div>
					<div class="am-u-lg-2  am-show-lg-only"><?php echo $ld['added_time']?></div>
					<div class="am-u-lg-2  am-show-lg-only"><?php echo $ld['user_last_login_time']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['operate']?></div>
				</div>
			</div>
		</div>
		
		
		<?php if(isset($operator_data) && sizeof($operator_data)>0){foreach($operator_data as $k=>$v){?>
		<div>
			<div class="listtable_div_top am-panel-body">
				<div class="am-panel-bd am-g">
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-5">
						<label class="am-checkbox am-success">
							<?php if(($_SESSION['type_id'] > $v['Operator']

['type_id']) || ($_SESSION['id']==$v['Operator']['id']) || isset($dealer_name['Dealer']['parent_id']) && 

($dealer_name['Dealer']['parent_id']!="0")){?>
							<div class="am-hide-sm-down"><input type="checkbox" style="visibility: hidden;" data-am-ucheck/></div>
							<?php }else{ ?>
							<div class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php 

echo $v['Operator']['id']?>" /></div>
							<?php } ?><?php echo $v['Operator']['name'];?>&nbsp;
						</label>
					</div>
					<div class="am-u-lg-2  am-u-md-3 am-hide-sm-only"><?php echo $v['Operator']['email'];?>&nbsp;</div>
					<div class="am-u-lg-1  am-u-md-2 am-u-sm-3">
						<?php echo $v['Operator']['mobile'];?>&nbsp;
					</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
						<span class="<?php echo (!empty($v['Operator']['status'])&&$v['Operator']['status'])?'am-icon-check am-yes am-text-success':'am-icon-close am-no am-text-danger'; ?> "></span>&nbsp;
					</div>
					<div class="am-u-lg-2  am-show-lg-only"><?php echo $v['Operator']['created'];?>&nbsp;</div>
					<div class="am-u-lg-2  am-show-lg-only"><?php echo $v['Operator']['last_login_time'];?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-action"><?php if($_SESSION['type_id']>$v['Operator']['type_id'] || $_SESSION['id']==$v['Operator']['id'] || isset($dealer_name['Dealer']['parent_id']) &&$dealer_name['Dealer']['parent_id']!="0"){
					}
					else
					{
						if($svshow->operator_privilege("operators_edit")){ ?>
						
						 <a class="am-icon-pencil-square-o am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/operators/view/'.$v['Operator']['id']); ?>"> <?php echo $ld['edit']; ?>
						</a>
						 
					<?php 	}
						if($v['Operator']['id']!=1)
						{
							if($svshow->operator_privilege("operators_remove")){ ?>
					      <a class="am-icon-trash-o am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:void(0);" onclick="if(j_confirm_delete){list_delete_submit(admin_webroot+'operators/remove/<?php echo $v['Operator']['id'] ?>');}"> <?php echo $ld['delete']; ?>
						</a>
					 	
						<?php }
						 }
					 }
					if($_SESSION['id']==$v['Operator']['id']){
					if($svshow->operator_privilege("operators_edit"))
						{?>
				 <a class="am-icon-pencil-square-o am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/operators/view/'.$v['Operator']['id']); ?>"> <?php echo $ld['edit']; ?>
                    </a>
                    			<?php	}
					} ?> 
						</div>
					
				</div>
			</div>
		</div>
			<?php }}else{?>
				<div style="text-align:center;"><b><?php echo $ld['no_operators'];?></b></div>
			<?php }?>
	</div>
	
	<?php if($svshow->operator_privilege("operators_remove")){?>
	<?php if(isset($operator_data) && sizeof($operator_data)){?>
	<div id="btnouterlist" class="btnouterlist">
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-hide-sm-down" style="left:6px;">
			<label class="am-checkbox am-success" style="display: inline;">
				<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
value="checkbox" data-am-ucheck><span><?php echo $ld['select_all']?></span></label>&nbsp;&nbsp;
			<button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" value="" onclick="batch_operations()" ><?php echo $ld['batch_delete']?></button>
		</div>
		<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			<?php echo $this->element('pagers')?>
		</div>
        <div class="am-cf"></div>
	</div>
	<?php }?>
	<?php }?>

<script type="text/javascript">
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
			url:admin_webroot+"operators/batch_operations/",
			type:"GET",
			dataType:"json",
			data: postData,
			success:function(data){
				window.location.href = window.location.href;
			}
		});
	}
}	
	
function on_hide(){
  //document.getElementById("dealer_id_select").style.display = (document.getElementById("type").options[1].selected ==true) ? "inline-block" : "none";
  //document.getElementById("labden").style.display = (document.getElementById("type").options[1].selected ==true) ? "inline-block" : "none";
}
function changeOperators(){

	 var Obj=document.getElementById("check1");
	 var Obj1=document.getElementById("checkbox");
	 if(Obj.checked==true){
	 	 var  str=document.getElementById("check1").value;
	  window.location.href = encodeURI(admin_webroot+"operators/index/"+"1"+"/"+str);
	 }
	 if(Obj1!=null&&Obj1.checked==true){
	  	  var  str=document.getElementById("checkbox").value;
	  		window.location.href = encodeURI(admin_webroot+"operators/index/"+"2"+"/"+str);
	 }
}

function search_dealer(){
	var obj = document.getElementById("add_dealer_button");
	var keywords=Trim(document.getElementById("deal").value);
	var postData = {"keywords":keywords};
	
	$.ajax({ 
			url:admin_webroot+"orders/search_dealer/",
			type:"POST",
			dataType:"json",
			data: postData,
			success: function(data){
				window.location.href = window.location.href;
			}
		});
		alert("aa");
}
</script>