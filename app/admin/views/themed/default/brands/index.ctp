<style type="text/css">
 .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
 .am-panel-title div{font-weight:bold;}
</style>
<div class="listsearch" style="margin-top:10px;">
	<?php echo $form->create('Brand',array('action'=>'/','name'=>"SeearchForm","type"=>"get" ,"class"=>"am-form-horizontal"));?>
		<div class="am-form-group">
			<label class="am-u-lg-1 am-u-md-1 am-u-sm-3 am-form-label am-text-center" style="font-weight:bold;">
			<?php echo $ld['keyword'];?></label>&nbsp;
			<div  class="am-u-lg-2 am-u-md-3 am-u-sm-6">
				<input type="text" class="am-form-field am-radius"  name="brand_keywords" value="<?php echo @$brand_keywords;?>" onkeypress="sv_search_action_onkeypress(this,event)"  style="white-space:nowrap;"   placeholder="<?php echo $ld['brand_code']?>/<?php echo $ld['brand_name']?>" />
			</div>
			<div class="am-u-lg-1 am-u-md-1 am-u-sm-2">
				<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"  onclick="search_article()" ><?php echo $ld['search'];?></button>
			</div>
		</div>
	<?php echo $form->end()?>
	<div class="am-g am-other_action am-btn-group-xs am-text-right" style="margin-bottom:10px;">
		<?php if($svshow->operator_privilege('brands_add')){?>
			<a class="am-btn am-btn-warning   am-btn-sm am-radius" href="<?php echo $html->url('view/'); ?>"><span class="am-icon-plus"></span> <?php echo $ld['add'] ?></a>
		<?php }?>
	</div>
	<div class="am-panel-group am-panel-tree ">
		<div class="  listtable_div_btm am-panel-header"  >
			<div class="am-panel-hd">
				<div class="am-panel-title am-g">
					<div class="am-u-lg-3 am-u-md-2 am-u-sm-2 am-hide-sm-only">
						<label class="am-checkbox am-success" style="font-weight:bold;"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/>
							<?php echo $ld["brand_code"]?>
						</label>
					</div>
				       <div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['brand_name']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['show']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['sort']?></div>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['operator']?></div>
				</div>
			</div>
		</div>
		<?php if(isset($brand_list) && sizeof($brand_list)>0){foreach($brand_list as $k=>$v){?>
		<div>
		<div class=" listtable_div_top   am-panel-body">
			<div class="am-panel-bd am-g">
				<div class="am-u-lg-3 am-u-md-2 am-u-sm-2 am-hide-sm-only" >
					<label class="am-checkbox am-success">
						<input type="checkbox" name="checkboxes[]" value="<?php echo $v['Brand']['id']?>"  data-am-ucheck/>
						<span onclick="javascript:listTable.edit(this, 'brands/update_brand_code/', <?php echo $v['Brand']['id']?>)">
						<?php echo $v['Brand']['code'];?>
					      </span>&nbsp;
					</label>
				</div>
			       <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" >
					<span onclick="javascript:listTable.edit(this, 'brands/update_brand_name/', <?php echo $v['Brand']['id']?>)"><?php echo $v['BrandI18n']['name'];?></span>&nbsp;
				</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
					<?php if ($v['Brand']['status'] == 1){?>
			                   <span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'brands/toggle_on_status',<?php echo $v['Brand']['id'];?>)"></span>
					 <?php }elseif($v['Brand']['status'] == 0){?>
				  	<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'brands/toggle_on_status',<?php echo $v['Brand']['id'];?>)">&nbsp;</span>
					<?php }?>&nbsp;
				</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"  >
					-
				</div>
				<div class="am-u-lg-3 am-u-md-4 am-u-sm-5  am-btn-group-xs am-action"  >
				<a   class="am-btn am-btn-success am-seevia-btn  am-btn-xs  " target='_blank' href="<?php echo $html->url($server_host.'/brands/view/'.$v['Brand']['id']);; ?>">
					<span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
				</a> 
				<?php if($svshow->operator_privilege('brands_edit')){?>
                        <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo    $html->url('/brands/view/'.$v['Brand']['id']); ?>">
                        <span class='am-icon-pencil-square-o'></span> <?php echo $ld['edit']; ?>
                    	</a>
                   	 <?php } ?>   
			     <?php if($svshow->operator_privilege('brands_remove')){ ?>
                      <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'brands/remove/<?php echo $v['Brand']['id']; ?>')">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
                    <?php } ?>
				</div>
			</div>
		</div>
		</div>
		<?php }}else{?>
		<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
		<?php }?>
	</div>
	<?php if($svshow->operator_privilege('brands_remove')){?>
	<?php if(isset($brand_list) && sizeof($brand_list)){?>
	<div id="btnouterlist" class="btnouterlist"> 
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-12  am-hide-sm-only" >
			<label class="am-checkbox am-success" style="margin-left:8px;">
				<input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)" type="checkbox" data-am-ucheck>
				<?php echo $ld['select_all']?>
			</label>&nbsp;&nbsp;
			<button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="batch_operations()" ><?php echo $ld['batch_delete']?></button>
		</div>
		<div class="am-u-lg-9 am-u-md-9 am-u-sm-12"><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
	</div>
	<?php }?>
	<?php }?>		
</div>
<script type="text/javascript">
//批量操作
function batch_operations(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select_brand']?>");
		return;
	}
	if(confirm("<?php echo $ld['confirm_delete_the_selected_brand']?>")){
		$.ajax({
			type:"POST",
			url:admin_webroot+"brands/batch_operations/",
			data: postData,
			dataType: "json",
			success:function(data){
				window.location.href = window.location.href;
			}
		});
	}
}
function changeOrder(updown,id,page){
 	YUI().use("io",function(Y) {
		var sUrl = "/admin/brands/changeorder/"+updown+"/"+id+"/"+page;//访问的URL地址
		var cfg = {
				method: 'POST'
		};
		var request = Y.io(sUrl, cfg);//开始请求
		var handleSuccess = function(ioId, o){
				var node = Y.one('#tablelist');
				var popcontent = document.createElement('div');
				popcontent.innerHTML = o.responseText;
				var tmp = outerHTML(popcontent.getElementsByTagName('table')[0].parentNode);
				node.set('innerHTML',tmp);
			try{
			}catch (e){
				alert("<?php echo $ld['object_transform_failed']?>");
				alert(o.responseText);
			}
			inita();
		}
		var handleFailure = function(ioId, o){
			alert("<?php echo $ld['asynchronous_request_failed']?>");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
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
