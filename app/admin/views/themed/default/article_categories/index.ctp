<style type="text/css">
	.am-panel-bd {padding: 0.5rem;}
	.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
	.seolink a:first-child{text-decoration:underline;color:green;}
	.am-checkbox input[type="checkbox"]{margin-left:0;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
	.am-panel-title div{font-weight:bold;}
</style>

<div class="am-g am-other_action am-btn-group-xs" >
	<div class="am-fr am-u-lg-6 am-u-md-6 am-u-sm-3" style="text-align:right;margin-right:10px;margin-bottom:10px;">
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/article_categories/view/0'); ?>"><span class="am-icon-plus"></span> <?php echo $ld['add'] ?></a>
	</div>
</div>
<div id="tablelist" class="">
<?php echo $form->create('ArticleCategorie',array('action'=>'/','name'=>'ArticleForm','type'=>'get',"onsubmit"=>"return false;"));?>
	<div class="am-panel-group am-panel-tree" id="accordion">
	<!--标题栏-->
		<div class="listtable_div_btm">
		    <div class="am-panel-hd">
		      	<div class="am-panel-title">
				 	<div class="am-u-lg-3 am-u-md-2 am-u-sm-2 am-hide-sm-only">
						<label class="am-checkbox am-success" style="font-weight:bold;">
							<input type="checkbox" name="chkall" data-am-ucheck value="checkbox" onclick="listTable.selectAll(this,'checkbox[]');" />
							<?php echo $ld['category_name']?>
						</label>
					</div>
					<div class="am-u-lg-3 am-u-md-2 am-u-sm-2 am-show-sm-only">
						<label  style="font-weight:bold;"><?php echo $ld['category_name']?></label>
					</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['system_type']?></div>
		   			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['article_numbers']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['sort']?></div>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 "><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
			    </div>
			</div>
		</div>
	<!--一级菜单-->
		<?php if(isset($categories_trees) && sizeof($categories_trees)>0){$i=0;foreach($categories_trees as $k=>$v){$i++;?>
		<div>
		<div class="listtable_div_top am-panel-body" >
		    <div class="am-panel-bd fuji">
				<div class="am-u-lg-3 am-u-md-2 am-u-sm-2">
					<label class="am-checkbox am-success am-hide-sm-only">
						<input type="checkbox" name="checkbox[]" data-am-ucheck class=" a<?php echo $i;?>" value="<?php echo $v['CategoryArticle']['id']?>" /><span data-am-collapse="{parent: '#accordion', target: '#article_<?php echo $v['CategoryArticle']['id']?>'}" class="<?php echo (isset($v['SubCategory'])&&!empty($v['SubCategory']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;<?php echo $v['CategoryArticleI18n']['name'];?>
					</label>
                                <label class="am-show-sm-only"><?php echo $v['CategoryArticleI18n']['name'];?></label>
				</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
					<?php echo $Resource_info["sub_type"][$v['CategoryArticle']['sub_type']]; ?>&nbsp;
				</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
					<?php
					if(isset($article_count[$v['CategoryArticle']['id']])){
						echo $html->link(@$article_count[$v['CategoryArticle']['id']],"../articles/?article_cat={$v['CategoryArticle']['id']}",array(),false,false);
					}else{echo "0";	}?>
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
					<?php if($v['CategoryArticle']['status']) {?>
						<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'article_categories/toggle_on_status',<?php echo $v['CategoryArticle']['id'];?>)"></span>
				  <?php }else{ ?>
						<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'article_categories/toggle_on_status',<?php echo $v['CategoryArticle']['id'];?>)">&nbsp;</span>	
				  <?php }?>
				</div>
				<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
					<?php if(count($categories_trees)==1){echo "-";}elseif($k==0){?>
						<a onclick="changeOrder('down','<?php echo $v['CategoryArticle']['id'];?>','0',this)" style="cursor:pointer;">&#9660;</a>
					<?php }elseif($k==(count($categories_trees)-1)){?>
						<a onclick="changeOrder('up','<?php echo $v['CategoryArticle']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>
					<?php }else{?>
						<a onclick="changeOrder('up','<?php echo $v['CategoryArticle']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>&nbsp;
						<a onclick="changeOrder('down','<?php echo $v['CategoryArticle']['id'];?>','0',this) " style="cursor:pointer;">&#9660;</a>
					<?php }?>
				</div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-action">
					<?php   $preview_url=$svshow->seo_link_path(array('type'=>'AC','id'=>$v['CategoryArticle']['id'],'name'=>$v['CategoryArticleI18n']['name'],'sub_name'=>$ld['preview']));?>                           <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-view" target='_blank' href="<?php echo $preview_url; ?>"> <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?> </a>
					
				<?php 	if($svshow->operator_privilege("article_categories_view")){?> 
				 <a class="am-btn am-btn-default am-btn-xs am-btn-success am-seevia-btn" href="<?php echo $html->url('/articles/?article_cat='.$v['CategoryArticle']['id']); ?>"><span class="am-icon-eye"></span> <?php echo $ld['articles_view']; ?> </a>
					<?php }
					if($svshow->operator_privilege("article_categories_edit")){ ?> 
					 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/article_categories/view/'.$v['CategoryArticle']['id']); ?>"> <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                </a>
					<?php }
					if($svshow->operator_privilege("article_categories_remove")){?> 
					<a class="am-btn am-btn-default am-btn-xs am-text-danger   am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'article_categories/remove/<?php echo $v['CategoryArticle']['id'] ?>' )"> <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
			 <?php }?>
				</div>
				<div style="clear:both;"></div>
			</div>
		<!--二级 菜单-->			
			<?php if(isset($v['SubCategory'])&& sizeof($v['SubCategory'])>0){?>
				<div class="am-panel-collapse am-collapse am-panel-child" id="article_<?php echo $v['CategoryArticle']['id']?>">	
					<?php $j=0; foreach($v['SubCategory'] as $kk=>$vv){$j++;?>
					<div class="am-panel-bd am-panel-childbd">
						<div class="am-u-lg-3 am-u-md-2 am-u-sm-2">
							<label class="am-checkbox am-success" style="margin-left:30px;">
								<input type="checkbox" data-am-ucheck name="checkbox[]" style="margin-left:0px;" value="<?php echo $vv['CategoryArticle']['id']?>" class=" b<?php echo $i;?> ba<?php echo $j;?>" /><span data-am-collapse="{parent: '#article_<?php echo $v['CategoryArticle']['id']?>', target:'#actionn_<?php echo $vv['CategoryArticle']['id']?>'}" class="<?php echo (isset($v['SubCategory']) && !empty($v['SubCategory']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;<?php echo $vv['CategoryArticleI18n']['name'];?></label>
							<label class="am-show-sm-only" style="margin-left:30px;"><?php echo $vv['CategoryArticleI18n']['name'];?></label>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
							<?php echo $Resource_info["sub_type"][$vv['CategoryArticle']['sub_type']]; ?>&nbsp;
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
							<?php if(isset($article_count[$vv['CategoryArticle']['id']])){?>
							<?php echo $html->link(@$article_count[$vv['CategoryArticle']['id']],"../articles/?article_cat={$vv['CategoryArticle']['id']}",array(),false,false);?>
							<?php }else{?>
							0
							<?php }?>&nbsp;
						</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
							<?php if($vv['CategoryArticle']['status']) {?>
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'article_categories/toggle_on_status',<?php echo $v['CategoryArticle']['id'];?>)"></span>
					       <?php }else{?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'article_categories/toggle_on_status',<?php echo $v['CategoryArticle']['id'];?>)">&nbsp;</span>		
						   
							<?php }?>&nbsp;
						</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
							<?php
							if(count($v['SubCategory'])==1){echo "-";}
							elseif($kk==0){
								?><a onclick="changeOrder('down','<?php echo $vv['CategoryArticle']['id'];?>','next',this)" style="cursor:pointer;">&#9660;</a><?php
							}elseif($kk==(count($v['SubCategory'])-1)){
								?><a onclick="changeOrder('up','<?php echo $vv['CategoryArticle']['id'];?>','next',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a><?php
							}else{
								?><a onclick="changeOrder('up','<?php echo $vv['CategoryArticle']['id'];?>','next',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vv['CategoryArticle']['id'];?>','next',this)" style="cursor:pointer;">&#9660;</a><?php
							}?>&nbsp;
						</div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-action">
							<?php   $preview_url=$svshow->seo_link_path(array('type'=>'AC','id'=>$vv['CategoryArticle']['id'],'name'=>$vv['CategoryArticleI18n']['name'],'sub_name'=>$ld['preview']));?>                           <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-view" target='_blank' href="<?php echo $preview_url; ?>"> <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?> </a>
					
				<?php 	if($svshow->operator_privilege("article_categories_view")){?> 
				 <a class="am-btn am-btn-default am-btn-xs am-btn-success am-seevia-btn" href="<?php echo $html->url('/articles/?article_cat='.$vv['CategoryArticle']['id']); ?>"><span class="am-icon-eye"></span> <?php echo $ld['articles_view']; ?> </a>
					<?php }
					if($svshow->operator_privilege("article_categories_edit")){ ?> 
					 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/article_categories/view/'.$vv['CategoryArticle']['id']); ?>"> <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                </a>
					<?php }
					if($svshow->operator_privilege("article_categories_remove")){?> 
					<a class="am-btn am-btn-default am-btn-xs am-text-danger   am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'article_categories/remove/<?php echo $vv['CategoryArticle']['id'] ?>' )"> <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
			 <?php }?>
						</div>
						<div style="clear:both;"></div>
					</div>
				<!--三级 菜单-->
					<?php if(isset($vv['SubCategory'])&& sizeof($vv['SubCategory'])>0){?>
					<div class="am-panel-collapse am-collapse am-panel-subchild" id="actionn_<?php echo $vv['CategoryArticle']['id']?>">	
					<?php foreach($vv['SubCategory'] as $kkk=>$vvv){?>		
						<div class="am-panel-bd am-panel-childbd">
							<div class="am-u-lg-3 am-u-md-2 am-u-sm-2" >
								<label class="am-checkbox am-success" style="margin-left:50px;">
									<input type="checkbox" data-am-ucheck name="checkbox[]" value="<?php echo $vvv['CategoryArticle']['id']?>" />
									<?php echo $vvv['CategoryArticleI18n']['name'];?>
								</label>
							</div>
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">		
								<?php echo $Resource_info["sub_type"][$vvv['CategoryArticle']['sub_type']]; ?>&nbsp;
							</div>	
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">	
								<?php echo (isset($article_count[$vvv['CategoryArticle']['id']]))?@$article_count[$vvv['CategoryArticle']['id']]:0; ?>&nbsp;
							</div>	
							<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">			
								<?php if($vvv['CategoryArticle']['status']) {?>
									<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'article_categories/toggle_on_status',<?php echo $v['CategoryArticle']['id'];?>)"></span>
								<?php }else{?>
									<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'article_categories/toggle_on_status',<?php echo $v['CategoryArticle']['id'];?>)">&nbsp;</span>
								<?php }?>&nbsp;
							</div>	
							<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">			
								<?php
								if(count($vv['SubCategory'])==1){echo "-";}
								elseif($kkk==0){
									?><a onclick="changeOrder('down','<?php echo $vvv['CategoryArticle']['id'];?>','next',this)">&#9660;</a><?php }elseif($kkk==(count($vv['SubCategory'])-1)){?><a onclick="changeOrder('up','<?php echo $vvv['CategoryArticle']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a><?php
								}else{
									?><a onclick="changeOrder('up','<?php echo $vvv['CategoryArticle']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vvv['CategoryArticle']['id'];?>','next',this)">&#9660;</a><?php
								}?>&nbsp;
							</div>
							<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-action">
								<?php   $preview_url=$svshow->seo_link_path(array('type'=>'AC','id'=>$vvv['CategoryArticle']['id'],'name'=>$vvv['CategoryArticleI18n']['name'],'sub_name'=>$ld['preview']));?>                           <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-view" target='_blank' href="<?php echo $preview_url; ?>"> <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?> </a>
						
					<?php 	if($svshow->operator_privilege("article_categories_view")){?> 
					 <a class="am-btn am-btn-default am-btn-xs am-btn-success am-seevia-btn" href="<?php echo $html->url('/articles/?article_cat='.$vvv['CategoryArticle']['id']); ?>"><span class="am-icon-eye"></span> <?php echo $ld['articles_view']; ?> </a>
						<?php }
						if($svshow->operator_privilege("article_categories_edit")){ ?> 
						 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/article_categories/view/'.$vvv['CategoryArticle']['id']); ?>"> <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
	                                </a>
						<?php }
						if($svshow->operator_privilege("article_categories_remove")){?> 
						<a class="am-btn am-btn-default am-btn-xs am-text-danger   am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'article_categories/remove/<?php echo $vvv['CategoryArticle']['id'] ?>' )"> <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                      </a>
				 <?php }?>
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
			<div class="am-text-center" style="margin:25px 0px;"><?php echo $ld['no_article_type']?></div>
		<?php }?>
		<?php if($svshow->operator_privilege("article_categories_remove")){?>
		<?php if(isset($categories_trees) && sizeof($categories_trees)){?>
		<div id="btnouterlist" class="btnouterlist am-hide-sm-only">
			<label class="am-checkbox am-success">
			 <input type="checkbox" name="chkall" value="checkbox"  data-am-ucheck  onclick="listTable.selectAll(this,'checkbox[]');" /> 
				<?php echo $ld['select_all']?>
			</label>&nbsp;&nbsp;
			<button type="submit" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="diachange()"><?php echo $ld['batch_delete']?></button>
		</div>
		<?php }?>
		<?php }?>	
	</div>
<?php echo $form->end();?>
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
		//	layer_dialog_show('请选择！','batch_action()',3);
			if(confirm(j_please_select))
			{
				batch_action();
			}
		}
	}
	function batch_action() {
		document.ArticleForm.action=admin_webroot+"article_categories/batch";
		document.ArticleForm.onsubmit= "";
		document.ArticleForm.submit();
	}
	function changeOrder(updown,id,next,thisbtn){
		
		$.ajax({
			url: "/admin/article_categories/changeorder/"+updown+"/"+id+"/"+next,
			type:"POST",
			data:{},
			dataType:"html",
			success:function(data){
				var popcontent = document.createElement('div');
				popcontent.innerHTML =data;
				var tmp = $(popcontent).find("#tablelist").html();
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
		var parentbody2=$(this).parent().find(".am-panel-childbd");
		var collapseoobj2=parentbody2.find(".am-icon-plus");
		collapseoobj2.removeClass("am-icon-plus");
		collapseoobj2.addClass("am-icon-minus")
	});
	$subchild.on('closed.collapse.amui', function() {
		var am_panel_child_className=$(this).attr('id');
		var parentbody2=$(this).parent().find(".am-panel-childbd");
		var collapseoobj2=parentbody2.find(".am-icon-minus");
		collapseoobj2.removeClass("am-icon-minus");
		collapseoobj2.addClass("am-icon-plus");
	});
})

</script>
