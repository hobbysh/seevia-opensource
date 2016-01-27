<style type="text/css">
	.am-checkbox {margin-top:0px; margin-bottom:0px;}
    
	.btnouterlist{overflow: visible;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
	.am-panel-title div{font-weight:bold;}
	.am-form-horizontal .am-form-label{padding-top: 0.5em;}
       .am-checkbox .am-icon-checked, .am-checkbox .am-icon-unchecked, .am-checkbox-inline .am-icon-checked, .am-checkbox-inline .am-icon-unchecked, .am-radio .am-icon-checked, .am-radio .am-icon-unchecked, .am-radio-inline .am-icon-checked, .am-radio-inline .am-icon-unchecked {
    background-color: transparent;
    display: inline-table;
    left: 0;
    margin: 0;
    position: absolute;
    top: 3px;
    transition: color 0.25s linear 0s;
}
</style>
<div style="margin-top:10px;">
					<?php echo $form->create('StaticPage',array('action'=>'/','name'=>'SPageForm','type'=>'get','class'=>'am-form-horizontal'));?>
				 
					<ul class="am-avg-lg-2 am-avg-md-2 am-avg-sm-1">
						<li>
							<label class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-form-label-text "><?php echo $ld['title'];?></label> 
								<div class="am-u-lg-6  am-u-md-6 am-u-sm-6"  >
								<input type="text" name="title" class="am-form-field am-radius"  value="<?php echo @$titles;?>" placeholder="<?php echo $ld['title']?>" />
								</div>
								<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" >
									<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"  onclick="search_page()"><?php echo $ld['search'];?></button>
								</div>
						</li>
    		</ul>
	<?php echo $form->end();?><br/>
	<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php if($svshow->operator_privilege('static_page_add')){?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/static_pages/view/0'); ?>">
				<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
			</a> 
		<?php }?>
	</div>
	<?php echo $form->create('StaticPage',array('action'=>'/','name'=>'PageForm','type'=>'get',"onsubmit"=>"return false;"));?>
	<div class="am-panel-group am-panel-tree">
		<div class="  listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title am-g">
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-5">
						<label class="am-checkbox am-success  am-hide-sm-only" style="font-weight:bold;padding-top:0">
							<input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
							<?php echo $ld["title"]?>
						</label>
		                        	<label class="am-checkbox am-success  am-show-sm-only" style="font-weight:bold;padding-top:0">
							  <?php echo $ld["title"]?>
						</label>
					</div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['subtitle']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-hide-sm-only"><?php echo $ld['url']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo $ld['valid']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['sort']?></div>
					<!--<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['added_time']?></div>-->
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-5"><?php echo $ld['operator']?></div>
				</div>
			</div>
		</div>
		<?php if(isset($pages) && sizeof($pages)>0){foreach($pages as $k=>$v){?>
		<div>
		<div class="listtable_div_top am-panel-body">
			<div class="am-panel-bd am-g">
				<div class="am-u-lg-3 am-u-md-4 am-u-sm-5">
					<label class="am-checkbox am-success  am-hide-sm-only" style="padding-top:0">
						<input type="checkbox" name="checkbox[]" data-am-ucheck value="<?php echo $v['Page']['id']?>" />
						<span onclick="javascript:listTable.edit(this, 'static_pages/update_page_title/', <?php echo $v['Page']['id']?>)"><?php echo $v['PageI18n']['title'] ?></span>
					</label>
			              <label class="am-checkbox am-success  am-show-sm-only" style="padding-top:0">
						 
						<span ><?php echo $v['PageI18n']['title'] ?></span>
					</label>
				</div>
				<div class="am-u-lg-2 am-show-lg-only">&nbsp;<?php echo $v['PageI18n']['subtitle'] ?></div>
				<div class="am-u-lg-2 am-u-md-3 am-hide-sm-only">
					&nbsp;<?php if(isset($v['Page']['url'])){echo $v['Page']['url'];}else{echo "/pages/".$v['Page']['id'];}?>
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-2">
					<?php if( $v['Page']['status'] == 1){?>
					&nbsp;<!--<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "static_pages/toggle_on_status", '.$v["Page"]["id"].')'))?>-->
						<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'static_pages/toggle_on_status',<?php echo $v['Page']['id'];?>)"></span>
					<?php }else{ ?>&nbsp;
						<!--<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "static_pages/toggle_on_status", '.$v["Page"]["id"].')')); ?>-->
						<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'static_pages/toggle_on_status',<?php echo $v['Page']['id'];?>)"></span>	
					<?php }?>
				</div>
				<div class="am-u-lg-1 am-show-lg-only">
					<span onclick="javascript:listTable.edit(this, 'static_pages/update_page_orderby/', <?php echo $v['Page']['id']?>)"><?php echo $v['Page']['orderby']?></span>
				</div>
				<!--<div class="am-u-lg-2 am-show-lg-only"><?php echo date('Y-m-d',strtotime($v['Page']['created'])) ?></div>-->
				<div class="am-u-lg-3 am-u-md-4 am-u-sm-5 seolink am-btn-group-xs am-action">
					<?php 
					if(isset($v['Page']['url'])){
						$url=$v['Page']['url']=="/"?"pages/".$v['Page']['id']:$v['Page']['url'];
						 $preview_url=$svshow->seo_link_path(array('type'=>'SM','id'=>"/".$url,'name'=>$v['PageI18n']['title'],'sub_name'=>$ld['preview']));
					}else{
					$preview_url=$svshow->seo_link_path(array('type'=>'SM','id'=>"/pages/".$v['Page']['id'],'name'=>$v['PageI18n']['title'],'sub_name'=>$ld['preview']));
					}?>
					
					<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-view" target='_blank' href="<?php echo $preview_url; ?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                    </a>
				<?php 	if($svshow->operator_privilege("static_page_edit")){?>
				 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/static_pages/view/'.$v['Page']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                      </a>
                     <?php ?>
						
				
				<?php 	if($svshow->operator_privilege("static_page_remove")){?>
						<a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
						href="javascript:;" 
						onclick="list_delete_submit(admin_webroot+'static_pages/remove/<?php echo $v['Page']['id'] ?>')">
                        			<span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      			</a>
					<?php }?>
				</div>
			</div>
		</div>
		</div>
		<?php }}}else{?>
			<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
			        
		<?php }?>
	</div>
	<?php if($svshow->operator_privilege("static_page_view")){?>
	<?php if(isset($pages) && sizeof($pages)){?>
	<div id="btnouterlist" class="btnouterlist" > 
		<div class="am-u-lg-5 am-u-md-6 am-u-sm-12 am-hide-sm-only" style="margin-left:7px;">
            <div class="am-fl">
    			<label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;">
    				<input onclick='listTable.selectAll(this,"checkbox[]")' data-am-ucheck type="checkbox">
    				<?php echo $ld['select_all']?>
    			</label>
            </div>
            <div class="am-fl">
	    			<select name="act_type" id="act_type" onchange="operate_change(this)" data-am-selected>
	    				<option value="0"><?php echo $ld['all_data']?></option>
	    				<option value="delete"><?php echo $ld['batch_delete']?></option>
	    				<option value="a_status"><?php echo $ld['log_batch_change_status']?></option>
	    			</select>
		             <div  style="display:none;margin-left::5px;margin-bottom:5px;margin-top:5px;">
	    			<select name="is_yes_no" id="is_yes_no" data-am-selected>
	    				<option value="1"><?php echo $ld['yes']?></option>
	    				<option value="0"><?php echo $ld['no']?></option>
	    			</select>
	                 </div> 
                     <div class="am-fr" style="margin-left:3px;"><button type="button" class="am-btn am-radius am-btn-danger am-btn-sm" onclick="diachange()"><?php echo $ld['submit']?></button></div>
            </div> 
        </div>
		<div class="am-u-lg-6 am-u-md-12 am-u-sm-12">		
			<?php echo $this->element('pagers');?>
		</div>
		<div class="am-cf"></div>
	</div>
	<?php }?>
	<?php }?>
	<?php echo $form->end();?>
</div>
<script>
function operate_change(obj){
	if(obj.value=="delete" || obj.value=="0"){
        $("#is_yes_no").parent().hide();
	}
	if(obj.value=="a_status"){
		$("#is_yes_no").parent().show();
	}
}
function diachange(){
	var a=document.getElementById("act_type");
	if(a.value!='0'){
		for(var j=0;j<a.options.length;j++){
			if(a.options[j].selected){
				var vals = a.options[j].text ;
			}
		}
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
		//	layer_dialog_show('确定'+vals+'?','batch_action()',5);
			if(confirm("<?php echo $ld['submit']?>"+vals+'?'))
			{
				batch_action();
			}
		}else{
		//	layer_dialog_show('请选择！！','batch_action()',3);
			if(confirm(j_please_select))
			{
				batch_action();
			}
		}
	}
}
function batch_action()
{
document.PageForm.action=admin_webroot+"static_pages/batch";
document.PageForm.onsubmit= "";
document.PageForm.submit();
}
function search_page()
{
document.SPageForm.action=admin_webroot+"static_pages/";
document.SPageForm.onsubmit= "";
document.SPageForm.submit();
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