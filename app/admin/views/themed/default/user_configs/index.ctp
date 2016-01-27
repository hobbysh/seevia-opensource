<style>
.am-checkbox{margin-top:0px; margin-bottom:0px;display: inline-block;vertical-align: top;}
.am-panel-title div{font-weight:bold;}
.am-form-horizontal .am-form-label{padding-top: 0.5em;font-weight:bold;}
.am-checkbox input[type="checkbox"]{margin-left:0px;}
.btnouterlist .am-checkbox{margin-top:6px;display: inline-block;}
</style>
<div>
	<div class="listsearch">
		<?php echo $form->create('UserConfig',array('action'=>'/','name'=>"SearchForm",'id'=>"SearchForm","type"=>"get","class"=>"am-form am-form-horizontal"));?>
			<ul class="am-avg-lg-4 am-avg-md-2 am-avg-sm-1">
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-center"><?php echo $ld['type']?></label>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
						<select name="user_config_type" onchange="user_config_type_change(this.value)" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
							<option value="0"><?php echo $ld['all_data'];?></option>
							<?php if(isset($Resource_info['user_config_type'])&&!empty($Resource_info['user_config_type'])){foreach($Resource_info['user_config_type'] as $kk=>$vv){ ?>
							<option value="<?php echo $kk; ?>"<?php if(@isset($user_config_type) && $user_config_type== "$kk"){echo "selected";}?>><?php echo $vv;?></option>
							<?php }} ?>
						</select>
					</div>
				</li>
                <li style="margin-bottom:10px;">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-center"><?php echo $ld['group']?></label>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
						<select name="user_config_group_code" id="user_config_group_code" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
							<option value=""><?php echo $ld['all_data'];?></option>
						</select>
					</div>
				</li>
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-center"><?php echo $ld['keyword']?></label>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
						<input type="text" name="user_config_keyword" class="am-form-field" id="user_config_keyword"  placeholder="<?php echo $ld['code']?>/<?php echo $ld['name']?>"  value="<?php echo @$user_config_keyword; ?>"/>
					</div>
				</li>
				 <li >
				  <label class="am-u-lg-1 am-u-md-3 am-u-sm-3 am-form-label am-text-center"> </label>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" >
						<input type="submit"  class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search'];?>"/>
					</div>
				</li>
			</ul>
		<?php echo $form->end();?>
	</div>
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php if($svshow->operator_privilege("users_add")){?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/user_configs/view'); ?>">
				<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
			</a> 
		<?php }?>
	</div>
	<div class="am-panel-group am-panel-tree">
		<div class="listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-hide-sm-only">
						<label class="am-checkbox am-success" style="font-weight:bold;">
							<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"  data-am-ucheck/>
						<?php echo $ld['code']?>
						</label>
					</div>
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-4"><?php echo $ld['name']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['type']?></div>
                    <div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['group']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($users_config_list) && sizeof($users_config_list)>0){foreach($users_config_list as $k =>$v){?>
			<div>
				<div class=" listtable_div_top am-panel-body">
					<div class="am-panel-bd" >
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-hide-sm-only">
							<label class="am-checkbox am-success">
								<input type="checkbox" name="checkboxes[]" value="<?php echo $v['UserConfig']['id']?>"   data-am-ucheck/>
								<?php echo $v['UserConfig']['code'];?>&nbsp;
							</label>&nbsp;
						</div>
						<div class="am-u-lg-3 am-u-md-4 am-u-sm-4"><?php echo $v['UserConfigI18n']['name'];?>&nbsp;</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-3">
							<?php echo isset($Resource_info['user_config_type'][$v['UserConfig']['type']])?$Resource_info['user_config_type'][$v['UserConfig']['type']]:$v['UserConfig']['type'];?>
						</div>
                        <div class="am-u-lg-2 am-show-lg-only"><?php echo isset($Resource_group_list[$v['UserConfig']['group_code']])?$Resource_group_list[$v['UserConfig']['group_code']]:'-';?></div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-btn-group-xs am-action">
							 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/user_configs/view/'.$v['UserConfig']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
			<a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'/user_configs/remove/<?php echo $v['UserConfig']['id'] ?>');">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
						</div>
						<div style="clear:both;"></div>						
					</div>
				</div>
			</div>
		<?php }} else{?>
			<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
		<?php }?>
	</div>
	<?php if(isset($users_config_list) && sizeof($users_config_list)){?>
		<div id="btnouterlist" class="btnouterlist am-form-group">
		 <div class="am-u-lg-3 am-u-md-5 am-u-sm-12 am-hide-sm-down" style="margin-left:7px;">
				<label class="am-checkbox am-success">
					<input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox" />
					<?php echo $ld['select_all']?>
				</label>&nbsp;&nbsp;
				<input type="button" value="<?php echo $ld['batch_delete']?>" class="am-btn am-radius am-btn-danger am-btn-sm"  onclick="batch_delete()" />
	      </div>	
	      	 <div class="am-u-lg-8 am-u-md-6 am-u-sm-12">
				<?php echo $this->element('pagers'); ?>
			</div>
            <div class="am-cf"></div>
		</div>
	<?php }?>
</div>
<script type="text/javascript">
var UserConfig_group_code="<?php echo isset($user_config_group_code)?$user_config_group_code:''; ?>";
function batch_delete(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		$.ajax({
			url:admin_webroot+"user_configs/removeall/",
			type:"POST",
			data:postData,
			dataType:"json",
			success:function(data){
				window.location.href = window.location.href;	
			}
		});
	}
}


function user_config_type_change(config_type){
    $("#user_config_group_code option").remove();
    $("<option></option>").val('').text(j_please_select).appendTo("#user_config_group_code");
    if(config_type!='0'){
        $.ajax({
            url:admin_webroot+"user_configs/user_configs_group",
            type:"POST",
            data: {'user_config_type':config_type},
            dataType:"json",
            success:function(data){
                if(data.flag == 1){
                    $.each(data.group_data,function(value,text){
                        if(UserConfig_group_code==value){
                            $("<option selected='selected'></option>").val(value).text(text).selected('selected').appendTo("#user_config_group_code");
                        }else{
                            $("<option></option>").val(value).text(text).appendTo("#user_config_group_code");
                        }
                    })
                    
                }
                $("#user_config_group_code").selected();
            }
        });
    }else{
        $("#user_config_group_code").selected();
    }
}
</script>