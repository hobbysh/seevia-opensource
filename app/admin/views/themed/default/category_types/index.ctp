<style type="text/css">
    .am-panel-bd {padding: 0.5rem;}
    .am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
    .seolink a:first-child{text-decoration:underline;color:green;}
    .am-yes{color:#5eb95e;}
    .am-no{color:#dd514c;}
    .am-panel-title div{font-weight:bold;}
     .am-checkbox input[type="checkbox"]{margin-left:0px;}
</style>
<div>
	<div class="am-text-right  am-btn-group-xs" style="margin-right:1px;margin-bottom:10px;">
		<?php if($svshow->operator_privilege('category_types_add')){?>
			<a href="<?php echo $html->url('/category_types/view'); ?>" class="addbutton am-btn am-btn-warning am-btn-sm am-radius">
				<span class="am-icon-plus"></span><?php echo $ld['add']?></a>
		<?php }?>
	</div>
	<form name="CategoryTypeForm" onsubmit="return false;" id="CategoryTypesForm" method="get" action="/admin/category_types" accept-charset="utf-8">
	<div id="tablelist">
			<div class="am-panel-group am-panel-tree" id="accordion">
		        <!--标题栏-->
		        <div class="  listtable_div_btm  am-panel-header">
		            <div class="am-panel-hd">
		                <div class="am-panel-title">
							<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-hide-sm-only">
								<label class="am-checkbox am-success" style="font-weight:bold;">
                                                    <input  type="checkbox" data-am-ucheck onclick="listTable.selectAll(this,&quot;checkbox[]&quot;)"/>
                                                   <?php echo $ld['type_name']?>
		                                 </label>
							</div>
							
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['code']?></div>
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['status']?></div>
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['sort']?></div>
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['operate']?></div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>
				<!--一级 菜单-->
				<?php if(isset($category_types_tree) && sizeof($category_types_tree)>0){foreach($category_types_tree as $k=>$v){?>			
				<div>			
					<div class="listtable_div_top am-panel-body" >
		                <div class="am-panel-bd fuji">
		                    <div class="am-u-lg-4 am-u-md-4 am-hide-sm-only" style="padding-top:2px;" >
		            			<span style="margin-left:10px" data-am-collapse="{parent: '#accordion', target:'#categorytype_<?php echo $v['CategoryType']['id']?>'}" class="<?php echo (isset($v['SubCategory']) && sizeof($v['SubCategory'])>0)?"am-icon-plus":"am-icon-minus";?>" id="<?php echo $v['CategoryType']['id']?>"></span>&nbsp;
		            			<label class="am-checkbox am-success">
		            				<input type="checkbox" data-am-ucheck  name="checkbox[]" value="<?php echo $v['CategoryType']['id']?>" />
		            					<?php echo $v['CategoryTypeI18n']['name'];?>
		            			</label>&nbsp;
		            		</div>
                           <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:3px;"><?php echo $v['CategoryType']['code'];?>&nbsp;</div>
		                    <!--状态-->
		                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:3px;">
		                    	<?php if($v['CategoryType']['status']) {?>
					 	        	<span class="am-icon-check am-yes"></span>
					 	         <?php }else{?>
					 	         	<span class="am-icon-close am-no"></span>
					 	         <?php }?>&nbsp;
			                </div>
			                <!--排序-->
		                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:3px;">
		                    	<?php if(count($category_types_tree)==1){echo "-";}elseif($k==0){?>
								<a onclick="changeOrder('down','<?php echo $v['CategoryType']['id'];?>','0',this)" style="cursor:pointer;">&#9660;</a>
								<?php }elseif($k==(count($category_types_tree)-1)){?>
								<a onclick="changeOrder('up','<?php echo $v['CategoryType']['id'];?>','0',this)" style="cursor:pointer;color:#cc0000;">&#9650;</a>
								<?php }else{?>
								<a onclick="changeOrder('up','<?php echo $v['CategoryType']['id'];?>','0',this)" style="cursor:pointer;color:#cc0000;">&#9650;</a>&nbsp;<a style="cursor:pointer;" onclick="changeOrder('down','<?php echo $v['CategoryType']['id'];?>','0',this)" >&#9660;</a>
								<?php }?>&nbsp;
		                    </div>
		                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-btn-default-xs am-action" style="padding-top:3px;">
		                    	<?php	if($svshow->operator_privilege("category_types_edit")){ ?>
								<!-- 编辑 -->							
							 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit"
							  href="<?php echo $html->url('/category_types/view/'.$v['CategoryType']['id']); ?>">
                       				 <span class="am-icon-pencil-square-o"></span> 
                        				 <?php echo $ld['edit']; ?>
                        			       </a>
                    				<?php } ?>	
                    						
								<?php 
									if($svshow->operator_privilege("category_types_remove")){?>
								
								<!--删除-->	
								<a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 					href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'category_types/remove/<?php echo $v['CategoryType']['id'] ?>');">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                                    </a>
									
							     <?php }?>	
		                    </div>
		                    <div style="clear:both;"></div>
		                </div>
		                <!--二级 菜单-->
		                <?php if(isset($v['SubCategory']) && sizeof($v['SubCategory'])>0){?>
		                	<div class="am-panel-collapse am-collapse am-panel-child" id="categorytype_<?php echo $v['CategoryType']['id']?>">	
		                		<?php foreach($v['SubCategory'] as $kk=>$vv){?>
		                			<div class="am-panel-bd am-panel-childbd">
										<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-hide-sm-only" >
											<span  style="margin-left:20px;" data-am-collapse="{parent: '#categorytype_<?php echo $v['CategoryType']['id']?>', target:'#actionn_<?php echo $vv['CategoryType']['id']?>'}" class="<?php echo (isset($vv['SubCategory']) && sizeof($vv['SubCategory'])>0)?"am-icon-plus":"am-icon-minus";?>" id="<?php echo $vv['CategoryType']['id']?>"></span>
											<label class="am-checkbox am-success">
												<input type="checkbox" name="checkbox[]" value="<?php echo $vv['CategoryType']['id']?>" data-am-ucheck /><?php echo $vv['CategoryTypeI18n']['name'];?>
											</label>
										</div>
	                                                    
										<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $vv['CategoryType']['code'];?>&nbsp;</div>
										<!--状态-->
										<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
											<?php if($vv['CategoryType']['status']) {?>
												<span class="am-icon-check am-yes"></span>	
											<?php }else{?>
												<span class="am-icon-close am-no" ></span>
											<?php }?>&nbsp;
										</div>
			                			<!--排序-->
										<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
											<?php if(count($v['SubCategory'])==1){echo "-";}elseif($kk==0){?>
												<a onclick="changeOrder('down','<?php echo $vv['CategoryType']['id'];?>','next',this)" style="cursor:pointer;">&#9660;</a>
											<?php }elseif($kk==(count($v['SubCategory'])-1)){?>
												<a onclick="changeOrder('up','<?php echo $vv['CategoryType']['id'];?>','next',this)" style="cursor:pointer;color:#cc0000;">&#9650;</a>
											<?php }else{?>
												<a onclick="changeOrder('up','<?php echo $vv['CategoryType']['id'];?>','next',this)" style="cursor:pointer;color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vv['CategoryType']['id'];?>','next',this)"  style="cursor:pointer;">&#9660;</a>
											<?php }?>&nbsp;
										</div>
										<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-action">
											<?php
												if($svshow->operator_privilege("category_types_edit")){?>
													 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit"
							  href="<?php echo $html->url('/category_types/view/'.$v['CategoryType']['id']); ?>">
                       				 <span class="am-icon-pencil-square-o"></span> 
                        				 <?php echo $ld['edit']; ?>
                    				</a>
											<?php 	}?>
									
												<?php 	if($svshow->operator_privilege("category_types_remove")){?>
											
												 <a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 							href="javascript:void(0);"
											    onclick="list_delete_submit(admin_webroot+'category_types/remove/<?php echo $v['CategoryType']['id'] ?>');">
                       									 <span class="am-icon-trash-o"></span> 
                        										<?php echo $ld['delete']; ?>
                              </a>
											
											<?php	}?>
										</div>
										<div style="clear:both;"></div>
		                			</div>
		                			<!--三级 菜单-->
		                			<?php if(isset($vv['SubCategory']) && sizeof($vv['SubCategory'])>0){?>
		                				<div class="am-panel-collapse am-collapse am-panel-subchild" id="actionn_<?php echo $vv['CategoryType']['id']?>">	
		                					<?php foreach($vv['SubCategory'] as $kkk=>$vvv){?>
		                					<?php// pr($vv['SubCategory']);?>
		                						<div class="am-panel-bd am-panel-childbd">
		                							<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
			                							<label class="am-checkbox am-success" style="margin-left:50px;">
		                                                    <input type="checkbox" name="checkbox[]" value="<?php echo $vvv['CategoryType']['id']?>" data-am-ucheck  />
		                                                    <?php echo $vvv['CategoryTypeI18n']['name'];?>
		                                                </label>
		                							</div>
		                							<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $vvv['CategoryType']['code'];?>&nbsp;</div>
		                							<!--状态-->
		                							<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
		                								<?php if($vvv['CategoryType']['status']) {?>
		                									<span class="am-icon-check am-yes"></span>
		                								<?php }else{ ?>
		                									<span class="am-icon-close am-no"></span>
		                								<?php }?>&nbsp;
		                							</div>
			               							<!--排序-->
		                							<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
														<?php if(count($vv['SubCategory'])==1){echo "-";}elseif($kkk==0){?>
															<a onclick="changeOrder('down','<?php echo $vvv['CategoryType']['id'];?>','next',this)" style="cursor:pointer;">&#9660;</a>
															<?php }elseif($kkk==(count($vv['SubCategory'])-1)){?>
															<a onclick="changeOrder('up','<?php echo $vvv['CategoryType']['id'];?>','next',this)" style="cursor:pointer;color:#cc0000;">&#9650;</a>
															<?php }else{?>
															<a onclick="changeOrder('up','<?php echo $vvv['CategoryType']['id'];?>','next',this)" style="cursor:pointer;color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vvv['CategoryType']['id'];?>','next',this)" style="cursor:pointer;">&#9660;</a>
														<?php }?>&nbsp;
													</div>
		                							<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
		                								<?php if($svshow->operator_privilege("category_types_edit")){
														echo $html->link($ld['edit'],"/category_types/view/{$vvv['CategoryType']['id']}",array("class"=>"am-btn am-btn-success am-btn-sm am-radius"),false,false).'&nbsp;';}
														if($svshow->operator_privilege("category_types_remove")){
														echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-danger am-btn-sm am-radius","onclick"=>"list_delete_submit('{$admin_webroot}category_types/remove/{$vvv['CategoryType']['id']}')"));}?>
		                							</div>
		                							<div style="clear:both;"></div>
		                						</div>
											<?php }?>
										</div>		
									<?php }?>
		                		<?php }?>
		                	</div>
		                <?php }?>
					</div>		
				</div>			
				<?php }}else{?>
					<div   class="no_data_found"><?php echo $ld['no_data_found']?></div>
				<?php }?>			
			</div>
		</div>
		<div>
			<?php if(isset($category_types_tree) && sizeof($category_types_tree) && $svshow->operator_privilege('category_types_remove')){?>
				<div id="btnouterlist" class="btnouterlist am-hide-sm-only">
					<div>
						<label class="am-checkbox am-success"style="margin:5px 5px 5px 13px;">
							<input type="checkbox" onclick="listTable.selectAll(this,&quot;checkbox[]&quot;)" data-am-ucheck>
							<?php echo $ld['select_all']?>
						</label>&nbsp;&nbsp;
						<button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="diachange()"><?php echo $ld['batch_delete']?></button> 
					</div>
							
				</div>
			<?php }?>
		</div>
	</form>
</div>

<script>
function diachange(){
		var id=document.getElementsByName('checkbox[]');
		var i;
		var j=0;
		var image="";
		for( i=0;i<=parseInt(id.length)-1;i++ ){
			if(id[i].checked){
				j++;
			}
		}
		if( j>=1 ){
		//	layer_dialog_show('确定删除?','batch_action()',5);
			if(confirm("<?php echo $ld['confirm_delete']?>"))
			{
				batch_action();
			}
		}else{
		//	layer_dialog_show('请选择！！','batch_action()',3);
			if(confirm(j_please_select))
			{
				return false;
			}
		}
}
function batch_action()
{
	document.CategoryTypeForm.action=admin_webroot+"category_types/batch";
	document.CategoryTypeForm.onsubmit= "";
	document.CategoryTypeForm.submit();
}
function changeOrder(updown,id,next,thisbtn){
	$.ajax({
		url:admin_webroot+"category_types/changeorder/"+updown+"/"+id+"/"+next,
		type:"POST",
		data:{ },
		dataType:"html",
		success:function(data){
            var popcontent = document.createElement('div');
			popcontent.innerHTML = data;
            var tmp = $(popcontent).find('#tablelist').html();
       		$("#tablelist").html(tmp);
            $("#tablelist input[type='checkbox']").uCheck();
		}
	});
}
$(function(){
    var $collapse =  $('.am-panel-child');
    var $subchild =  $('.am-panel-subchild');
    $collapse.on('opened.collapse.amui', function() {
        var parentbody=$(this).parent().find(".fuji");
        var collapseoobj=parentbody.find(".am-icon-plus");
        collapseoobj.removeClass("am-icon-plus");
        collapseoobj.addClass("am-icon-minus");
    });
    $collapse.on('closed.collapse.amui', function() {
        var parentbody=$(this).parent().find(".fuji");
        var collapseoobj=parentbody.find(".am-icon-minus");
        collapseoobj.removeClass("am-icon-minus");
        collapseoobj.addClass("am-icon-plus")
    });

    $subchild.on('opened.collapse.amui', function() {
        var am_panel_child_className=$(this).attr('id');
        var parentbody2=$(this).parent().find("."+am_panel_child_className);
        var collapseoobj2=parentbody2.find(".am-icon-plus");
        collapseoobj2.removeClass("am-icon-plus");
        collapseoobj2.addClass("am-icon-minus")
    });
    $subchild.on('closed.collapse.amui', function() {
        var am_panel_child_className=$(this).attr('id');
        var parentbody2=$(this).parent().find("."+am_panel_child_className);
        var collapseoobj2=parentbody2.find(".am-icon-minus");
        collapseoobj2.removeClass("am-icon-minus");
        collapseoobj2.addClass("am-icon-plus")
    });
});


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