<style>
 .am-radio, .am-checkbox{margin-top:0px;margin-bottom:0px;display: inline-block;vertical-align: text-top;}
 .am-checkbox input[type="checkbox"]{margin-left:0px;}
 .am-checkbox, .am-radio{margin-bottom:0px;}
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
</style>

<div class="action-span am-text-right" style="margin-bottom:10px;">
	<a class="am-btn am-btn-warning am-btn-sm am-radius addbutton" href="<?php echo $html->url('/attributes/attribute_option_view/'.$attribute_id); ?>">
		<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
	</a> 
</div>

<div id="attribute_option_data" class="am-panel-group am-panel-tree">
	<div class="am-panel am-panel-default am-panel-header">
		<div class="am-panel-hd">
			<div class="am-panel-title">
				<div class="am-u-lg-1 am-show-lg-only">
					<label class="am-checkbox am-success"  style="white-space:nowrap;">
						<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck >
						<?php echo $ld['number']?>
					</label>
				</div>
				
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['option_name'] ?></div>
				<div class="am-u-lg-3 am-show-lg-only"><?php echo $ld['option_value'] ?></div>
				<?php if($attribute['Attribute']['type']=='customize'){ ?><div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['price']?></div><?php } ?>
				<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['status']?></div>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><?php echo $ld['operate']?></div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>
	<?php if(isset($attr_option_list)&&sizeof($attr_option_list)>0){foreach($attr_option_list as $v){ ?>
	<div>
		<div class="am-panel am-panel-default am-panel-body">
			<div class="am-panel-bd">			
				<div class="am-u-lg-1 am-show-lg-only">
					<label class="am-checkbox am-success">
						<input type="checkbox" name="checkboxes[]" value="<?php echo $v['AttributeOption']['id']?>" data-am-ucheck /><?php echo $v['AttributeOption']['id']?>
					</label>
				</div>
				
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<span onclick="javascript:listTable.edit(this, 'attributes/update_option_name/', <?php echo $v['AttributeOption']['id']?>)"><?php echo $v['AttributeOption']['option_name'] ?></span>
				</div>
				<div class="am-u-lg-3 am-show-lg-only">
					<span onclick="javascript:listTable.edit(this, 'attributes/update_option_value/', <?php echo $v['AttributeOption']['id']?>)"><?php echo $v['AttributeOption']['option_value'] ?></span>
				</div>
				<?php if($attribute['Attribute']['type']=='customize'){ ?><div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
					<span onclick="javascript:listTable.edit(this, 'attributes/update_option_price/', <?php echo $v['AttributeOption']['id']?>)"><?php echo $v['AttributeOption']['price'] ?></span>
				</div><?php } ?>
				<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
					<?php if ($v['AttributeOption']['status'] == 1){?>
					<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'attributes/toggle_on_option_status',<?php echo $v['AttributeOption']['id'];?>)"></span>
					<?php }else{?>
					<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'attributes/toggle_on_option_status',<?php echo $v['AttributeOption']['id'];?>)"></span>	
					<?php }?>
				</div>
				<div class="am-u-lg-2 am-u-md-6 am-u-sm-4">
					<?php if($svshow->operator_privilege("attribute_edit")){echo $html->link($ld['edit'],"/attributes/attribute_option_view/{$attribute_id}/".$v['AttributeOption']['id'],array('class'=>'am-btn am-radius am-btn-default am-btn-sm')).'&nbsp;';}if($svshow->operator_privilege("attribute_remove")){echo $html->link($ld['delete'],"javascript:void(0);",array('class'=>'am-btn am-text-danger am-btn-default am-btn-sm am-radius','onclick'=>"removeAttrOption({$v['AttributeOption']['id']})"));} ?>
				</div>
				<div style="clear:both;"></div>				
			</div>
		</div>
	</div>
	<?php }}else{ ?>
        <div class="no_data_found"><?php echo $ld['no_data_found']?></div>
    <?php } ?>   
</div>
<?php if(isset($attr_option_list) && sizeof($attr_option_list)>0){ ?>
	<div id="btnouterlist" class="btnouterlist">
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
			<label class="am-checkbox am-success"  style="white-space:nowrap;">
				<input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)" data-am-ucheck type="checkbox">
				<?php echo $ld['select_all']?>
			</label>&nbsp;&nbsp;
			<button type="button" class="am-btn am-radius am-btn-danger am-btn-sm" onclick="removeAttrOptionAll()" value="<?php echo $ld['delete']?>">
			<?php echo $ld['batch_delete']?></button>
		</div>
		<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			<?php echo $this->element('pagers')?>
		</div>
	</div>
<?php }?>
<style type="text/css">
.tablelist tr td:last-child a{color:#333;}
</style>
<script type="text/javascript">
$("#attribute_option_data").parent().find("#btnouterlist .pages a").click(function(){
    var link_url=$(this).attr('href');
    loadAttrOptionPage(link_url);
    return false;
});
function loadAttrOptionPage(link_url){
    $.ajax({ url: link_url,
			type:"POST", 
            dataType:"html",
			data: { },
			success: function(data){
				try{
                    $("#attribute_option_data").parent().html(data);
                    $("input[type='checkbox']").uCheck();
    			}catch (e){
    				alert(data);
    			}
      		}
      	});
}

function removeAttrOption(id){
    if(confirm(j_confirm_delete)){
        $.ajax({ 
        	url: "/admin/attributes/remove_attr_option/"+id,
			type:"POST", 
            dataType:"json",
			data: { },
			success: function(data){
                if(data.flag==1){
                    loadAttrOptionPage("/admin/attributes/attribute_option/<?php echo $attribute_id; ?>");
                }else{
                    alert(data.msg);
                }
      		}
      	});
    }
}

function removeAttrOptionAll(){
	var id=document.getElementsByName('checkboxes[]');
	var j=0;
    var data="";
	for(var i=0;i<=parseInt(id.length)-1;i++ ){
		if(id[i].checked){
			j++;
            data+="&checkboxes[]="+id[i].value;
		}
	}
	if(j>=1){
        data="?1=1"+data;
		if(confirm(j_confirm_delete)){
		    $.ajax({ url: "/admin/attributes/remove_attr_option_all/"+data,
    			type:"POST", 
                dataType:"json",
    			data: { },	
    			success: function(data){
                    if(data.flag==1){
                        loadAttrOptionPage("/admin/attributes/attribute_option/<?php echo $attribute_id; ?>");
                    }else{
                        alert(data.msg);
                    }
          		}
          	});
		}
	}
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