<?php
/*****************************************************************************
 * SV-Cart 权限管理
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
?>
<style type="text/css">
    .am-panel-bd {padding: 0.5rem;}
    .am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
    .am-yes{color:#5eb95e;}
    .am-no{color:#dd514c;}
    .am-panel-title div{font-weight:bold;}
</style>
<div class="am-g am-other_action">
    <div class="am-fr am-u-lg-6 am-u-md-6 am-u-sm-3 am-btn-group-xs am-text-right" style="margin-right:11px; margin-bottom:10px;">
        <?php if($svshow->operator_privilege("product_categories_add")){?>
            <a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/product_categories/view/'); ?>"><span class="am-icon-plus"></span> <?php echo $ld['add'] ?></a>
        <?php }?>
    </div>
</div>
<div id="tablelist" class="">
    <?php echo $form->create('ProductCategorie',array('action'=>'/','name'=>'ArticleForm','type'=>'get',"onsubmit"=>"return false;"));?>
    <div class="am-panel-group am-panel-tree" id="accordion">
        <!--标题栏-->
        <div class="   am-panel-header   listtable_div_btm ">
            <div class="am-panel-hd">
                <div class="am-panel-title">
                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-5">
                        <label class="am-checkbox am-success" style="font-weight:bold;">
                            <input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
                            <?php echo $ld['category_name']?>
                        </label>
                    </div>
                    <div class="am-u-lg-2 am-u-md-1 am-u-sm-2"><?php echo $ld['products_number']?></div>
                    <div class="am-u-lg-1 am-u-md-1 am-show-md-up"><?php echo $ld['status']?></div>
                    <div class="am-u-lg-1 am-u-md-1 am-show-md-up"><?php echo $ld['sort']?></div>
                    <div class="am-u-lg-4 am-u-md-4 am-u-sm-5"><?php echo $ld['operate']?></div>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
        <!--一级 菜单-->
        <?php if(isset($categories_tree) && sizeof($categories_tree)>0){$i=0;foreach($categories_tree as $k=>$v){$i++;?>
        <div>
            <div class=" listtable_div_top  am-panel-body" >
                <div class="am-panel-bd fuji">
                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-5"   >
                        <span style="padding-left:8px;" data-am-collapse="{parent: '#accordion', target:'#categoryproduct_<?php echo $v['CategoryProduct']['id']?>'}" class="<?php echo (isset($v['SubCategory'])&&!empty($v['SubCategory']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;
                        <label class="am-checkbox am-success" >
                            <input type="checkbox" data-am-ucheck name="checkbox[]" class=" a<?php echo $i;?>" value="<?php echo $v['CategoryProduct']['id']?>" onclick="javascript:void(0)" style="margin-left:0px;"/><?php echo $v['CategoryProductI18n']['name'];?> 
                             </label>
                          
                    </div>
                    <div class="am-u-lg-2 am-u-md-1 am-u-sm-2" >&nbsp;
                        <?php if(isset($product_count[$v['CategoryProduct']['id']])){
                            if(isset($category_chirlids[$v['CategoryProduct']['id']])){	echo $html->link(@$product_count[$v['CategoryProduct']['id']],"../products/?category_id={$category_chirlids[$v['CategoryProduct']['id']]}",array(),false,false);
                            }else{echo $html->link(@$product_count[$v['CategoryProduct']['id']],"../products/?category_id={$v['CategoryProduct']['id']}",array(),false,false);
                            }
                        }else{echo "0"; } ?>
                    </div>
                    <div class="am-u-lg-1 am-u-md-1 am-show-md-up">&nbsp;
                        <?php if($v['CategoryProduct']['status']) {?>
                            <span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'product_categories/toggle_on_status',<?php echo $v['CategoryProduct']['id'];?>)"></span>
                        <?php }else{?>
                            <span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'product_categories/toggle_on_status',<?php echo $v['CategoryProduct']['id'];?>)">&nbsp;</span>
                        <?php }?>
                    </div>
                    <div class="am-u-lg-1 am-u-md-1 am-show-md-up" >
                        <?php if(count($categories_tree)==1){echo "-";}elseif($k==0){?>
                            <a onclick="changeOrder('down','<?php echo $v['CategoryProduct']['id'];?>','0',this)" style="cursor:pointer;">&#9660;</a>
                        <?php }elseif($k==(count($categories_tree)-1)){?>
                            <a onclick="changeOrder('up','<?php echo $v['CategoryProduct']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer;" >&#9650;</a>
                        <?php }else{?>
                            <a  onclick="changeOrder('up','<?php echo $v['CategoryProduct']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['CategoryProduct']['id'];?>','0',this)" style="cursor:pointer; ">&#9660;</a>
                        <?php }?>
                    </div>
                    <div class="am-u-lg-5 am-u-md-6 am-u-sm-5   am-btn-group-xs am-action" >
                        <?php  $preview_url=$svshow->seo_link_path(array('type'=>'PC','id'=>$v['CategoryProduct']['id'],'name'=>$v['CategoryProductI18n']['name'],'sub_name'=>$ld['preview'])) ?>  <a class="am-btn am-seevia-btn am-btn-xs am-btn-success  am-btns" target='_blank' 
                        	href='<?php echo $preview_url ;?>'> 
                        	      <span class="am-icon-eye"></span> 
                        	 	<?php echo $ld['preview']; ?>
                    	</a> <?php  if($svshow->operator_privilege("products_mgt")){?>
	                         <a class='am-btn am-btn-success am-btn-xs am-btn-default am-seevia-btn'  
                                    	 target='_blank' href='<?php echo $html->url("/products/?category_id={$v['CategoryProduct']['id']};") ?>'>
                                      <span class="am-icon-eye"></span> <?php echo $ld['product_view']; ?>
                    			</a> <?php  }?> 
                    			<?php if($svshow->operator_privilege("product_categories_move")){	echo $html->link($ld['move_products'],"move_to/{$v['CategoryProduct']['id']}",array("class"=>"am-btn am-btn-default  am-btn-xs am-seevia-btn"),false,false) ;}?>
                        	
                               <?php if($svshow->operator_privilege("product_categories_edit")){?>
                                      	<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-btns"
							  href="<?php echo $html->url('/product_categories/view/'.$v['CategoryProduct']['id']); ?>">
                       				 <span class="am-icon-pencil-square-o"></span> 
                        				 <?php echo $ld['edit']; ?>
                        			       </a><?php  }?><?php   if($svshow->operator_privilege("product_categories_remove")){ ?>
                       <a class="am-btn am-btn-default am-btn-xs am-text-danger am-btns  "href="javascript:;" onclick="list_delete_submit(admin_webroot+'product_categories/remove/<?php echo$v['CategoryProduct']['id'] ?>');">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                        </a>
                       
                        <?php }?>
                        
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <!--二级 菜单-->
                <?php if(isset($v['SubCategory']) && sizeof($v['SubCategory'])>0){?>
                    <div class="am-panel-collapse am-collapse am-panel-child" id="categoryproduct_<?php echo $v['CategoryProduct']['id']?>">
                        <?php $j=0;foreach($v['SubCategory'] as $kk=>$vv){$j++;?>
                            <div class="am-panel-bd am-panel-childbd"  style="margin-left:15px;" >
                                <div class="am-u-lg-3 am-u-md-3 am-u-sm-5">
                                    <span data-am-collapse="{parent: '#categoryproduct_<?php echo $v['CategoryProduct']['id']?>', target:'#actionn_<?php echo $vv['CategoryProduct']['id']?>'}" class="<?php echo (isset($vv['SubCategory']) && !empty($vv['SubCategory']))?"am-icon-plus":"am-icon-minus";?>" ></span>&nbsp;      <label class="am-checkbox am-success">
                                        <input type="checkbox" data-am-ucheck name="checkbox[]" style="margin-left:0px;" value="<?php echo $vv['CategoryProduct']['id']?>" class=" b<?php echo $i;?> ba<?php echo $j;?>" />
                                        <?php echo $vv['CategoryProductI18n']['name'];?>
                                    </label>
                                </div>
                                <div class="am-u-lg-2 am-u-md-1 am-u-sm-2">
                                    <?php if(isset($product_count[$vv['CategoryProduct']['id']])){
                                        if(isset($category_chirlids[$vv['CategoryProduct']['id']])){echo $html->link(@$product_count[$vv['CategoryProduct']['id']],"../products/?category_id={$category_chirlids[$vv['CategoryProduct']['id']]}",array(),false,false);}
                                        else{echo $html->link(@$product_count[$vv['CategoryProduct']['id']],"../products/?category_id={$vv['CategoryProduct']['id']}",array(),false,false);}}
                                      else{echo "0";}?>
                                </div>

                                <div class="am-u-lg-1 am-u-md-1 am-hide-sm-down">
                                    <?php if($vv['CategoryProduct']['status']) {?>
                                        <span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'product_categories/toggle_on_status',<?php echo $v['CategoryProduct']['id'];?>)"></span>
                                    <?php }else{ ;?>
                                        <span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'product_categories/toggle_on_status',<?php echo $v['CategoryProduct']['id'];?>)">&nbsp;</span>
                                    <?php } ?>
                                </div>
                                <div class="am-u-lg-1 am-u-md-1 am-hide-sm-down">
                                    <?php if(count($v['SubCategory'])==1){echo "-";}elseif($kk==0){?>
                                        <a onclick="changeOrder('down','<?php echo $vv['CategoryProduct']['id'];?>','next',this)" style="cursor:pointer;">&#9660;</a>
                                    <?php }elseif($kk==(count($v['SubCategory'])-1)){?>
                                       <a onclick="changeOrder('up','<?php echo $vv['CategoryProduct']['id'];?>','next',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a><?php	}else{?>
                                        <a onclick="changeOrder('up','<?php echo $vv['CategoryProduct']['id'];?>','next',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vv['CategoryProduct']['id'];?>','next',this)" style="cursor:pointer;">&#9660;</a><?php }?>
                                </div> 
                                <div class="am-u-lg-5 am-u-md-5 am-u-sm-5   am-btn-group-xs am-action">
                                    <?php  $peview_url=$svshow->seo_link(array('type'=>'PC','id'=>$vv['CategoryProduct']['id'],'name'=>$vv['CategoryProductI18n']['name'],'sub_name'=>$ld['preview'])).'&nbsp;&nbsp;';  ?>  <a class='am-btn am-btn-default am-btn-xs am-btn-success am-seevia-btn-view'  target='_blank' href='<?php echo $preview_url; ?>'>
                                     <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                    			</a>
                                 <?php if($svshow->operator_privilege("products_mgt")){?>
                                     <a class='am-btn am-btn-default am-btn-xs am-btn-success am-seevia-btn '  
                                    	 target='_blank' href='<?php echo $html->url("/products/?category_id={$vv['CategoryProduct']['id']};") ?>'>
                                      <span class="am-icon-eye"></span> <?php echo $ld['product_view']; ?>
                    			</a>
                                     <?php }?>
                                 <?php if($svshow->operator_privilege("product_categories_move")){echo $html->link($ld['move_products'],"move_to/{$vv['CategoryProduct']['id']}",array("class"=>"am-btn am-btn-default am-btn-xs am-seevia-btn"),false,false);}?>
                                <?php   if($svshow->operator_privilege("product_categories_edit")){?>
                                 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/product_categories/view/'.$vv['CategoryProduct']['id']); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a>
                                          <?php }?>
                          <?php   if($svshow->operator_privilege("product_categories_remove")){ ?>
                       <a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete"href="javascript:;" onclick="list_delete_submit(admin_webroot+'product_categories/remove/<?php echo$v['CategoryProduct']['id'] ?>');"><span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?></a><?php }?>
                                </div>
                              <div style="clear:both;"></div>
                            </div>

                            <!--三级 菜单-->
                            <?php if(isset($vv['SubCategory']) && sizeof($vv['SubCategory'])>0){?>
                                <div class="am-panel-collapse am-collapse am-panel-subchild" id="actionn_<?php echo $vv['CategoryProduct']['id']?>">
                                    <?php foreach($vv['SubCategory'] as $lk=>$vvv){?>
                                        <div class="am-panel-bd am-panel-childbd" style="margin-left:39px;'">
                                            <div class="am-u-lg-3 am-u-md-2 am-u-sm-5">
                                                <label class="am-checkbox am-success" >
                                                    <input type="checkbox" data-am-ucheck name="checkbox[]" value="<?php echo $vvv['CategoryProduct']['id']?>" />
                                                    <?php echo $vvv['CategoryProductI18n']['name'];?>
                                                </label>
                                            </div>
                                            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">&nbsp;
                                                <?php echo (isset($categories_products_count[$vvv['CategoryProduct']['id']])||isset($product_count[$vvv['CategoryProduct']['id']]))?@$categories_products_count[$vvv['CategoryProduct']['id']]+@$product_count[$vvv['CategoryProduct']['id']]:0; ?>
                                            </div>
                                            <div class="am-u-lg-1 am-u-md-1 am-show-md-up">&nbsp;
                                                <?php if($vvv['CategoryProduct']['status']) {?>
                                                    <span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'product_categories/toggle_on_status',<?php echo $v['CategoryProduct']['id'];?>)"></span>
                                                <?php }else{?>
                                                    <span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'product_categories/toggle_on_status',<?php echo $v['CategoryProduct']['id'];?>)">&nbsp;</span>
                                                <?php }?>
                                            </div>
                                            <div class="am-u-lg-1 am-u-md-1 am-show-md-up">&nbsp;
                                                <?php if(count($vv['SubCategory'])==1){echo "-";}elseif($kk==0){?>
                                                    <a onclick="changeOrder('down','<?php echo $vvv['CategoryProduct']['id'];?>','next',this)" style="cursor:pointer;">&#9660;</a>
                                                <?php }elseif($kk==(count($vv['SubCategory'])-1)){?>
                                                    <a onclick="changeOrder('up','<?php echo $vvv['CategoryProduct']['id'];?>','next',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>
                                                <?php }else{?>
                                                    <a onclick="changeOrder('up','<?php echo $vvv['CategoryProduct']['id'];?>','next',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vvv['CategoryProduct']['id'];?>','next',this)" style="cursor:pointer;">&#9660;</a>
                                                <?php }?>
                                            </div>
                                            <div class="am-u-lg-4 am-u-md-4 am-u-sm-5 seolink am-btn-group-xs am-action">
                                                <?php   $peview_url=$svshow->seo_link(array('type'=>'PC','id'=>$vvv['CategoryProduct']['id'],'name'=>
                                                        $vvv['CategoryProductI18n']['name'],'sub_name'=>$ld['preview'])).'&nbsp;&nbsp;';?>
                                            <a class='am-btn am-btn-default am-btn-xs am-btn-success am-seevia-btn-view'  target='_blank' href='<?php echo $preview_url; ?>'>
                                     <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?></a> 
                                             <?php    if($svshow->operator_privilege("products_mgt")){echo $html->link(' '.$ld['product_view'],"/products/?category_id={$vvv['CategoryProduct']['id']}",array("target"=>"_blank","class"=>"am-icon-eye am-btn am-btn-default am-btn-xs am-seevia-btn am-btn-success"),false,false).'&nbsp;';}
                                                if($svshow->operator_privilege("product_categories_move")){echo $html->link(' '.$ld['move_products'],"move_to/{$vvv['CategoryProduct']['id']}",array("class"=>"am-btn am-btn-default  am-btn-sm am-seevia-btn  ",),false,false).'&nbsp;';}if($svshow->operator_privilege("product_categories_edit")){echo $html->link(' '.$ld['edit'],"/product_categories/view/{$vvv['CategoryProduct']['id']}",array("class"=>"am-btn am-btn-default  am-btn-sm am-icon-pencil-square-o"),false,false).'&nbsp;';}
                                                if($svshow->operator_privilege("product_categories_remove")){echo $html->link(' '.$ld['delete'],"javascript:;",array("class"=>"am-btn am-text-danger am-btn-sm am-seevia-btn-delete am-icon-trash-o am-btn-default","onclick"=>"list_delete_submit('{$admin_webroot}product_categories/remove/{$vvv['CategoryProduct']['id']}');"));}?>
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
            <?php }}else { ?>
                <div class="no_data_found"><?php echo $ld['no_data_found']?></div>
            <?php }?>
        <?php if($svshow->operator_privilege("product_categories_remove")){?>
            <?php if(isset($categories_tree) && sizeof($categories_tree)){?>
                <div id="btnouterlist" class="btnouterlist" style="overflow: visible;  margin-top:20px; margin-left:13px;">
                    <label class="am-checkbox am-success">
                        <input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
                        <?php echo $ld['select_all']?>
                    </label>&nbsp;&nbsp;
                    <select id="export_csv" data-am-selected>
                        <option value="all_export_csv"><?php echo $ld['all_data']; ?></option>
                        <option value="shelf_export_csv"><?php echo $ld['for_sale']; ?></option>
                        <option value="nextframe_export_csv"><?php echo $ld['out_of_stock']; ?></option>
                    </select>
                    <button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="derivedchange()" value="" data-am-modal="{target: '#placement', closeViaDimmer: 0}"><?php echo $ld['export_goods']?></button>
                    <button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="diachange()" value="">
                        <?php echo $ld['batch_delete']?></button>
                </div>
            <?php }?>
        <?php } ?>
    </div>
    <?php echo $form->end();?>
</div>
        

<div class="am-modal am-modal-no-btn pop tablemain" tabindex="-1" id="placement" >
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['file_allocation'].' '.$ld['templates']:$ld['file_allocation'].$ld['templates'];?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <form id='placementform3' method="POST" class="am-form am-form-horizontal">
                <div class="am-form-group">
                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" >
                        <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['choice_export'].' '.$ld['templates']:$ld['choice_export'].$ld['templates'];?>:
                    </label>
                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                        <select name="profilegroup" id="profilegroup" data-am-selected>
                            <option value="0"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['please_select'].' '.$ld['templates']:$ld['please_select'].$ld['templates'];?></option>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;<em style="color:red;position: absolute;top: 17px;right: -5px;">*</em>
                    </div>
                </div>
                <div><input type="button" id="mod" class="am-btn am-btn-success am-btn-sm am-radius"  name="changeprofileButton" value="<?php echo $ld['confirm']?>" onclick="javascript:changeprofile();"></div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

/*	YUI().use('node', function(Y){
<?php for($i=0;$i<count($categories_tree);$i++){?>
 var navcheck<?php echo $i?> = Y.all('.b<?php echo $i?>');

<?php }?>
 bc = function(){
<?php for($i=1;$i<count($categories_tree);$i++){?>
 var u=<?php echo $i?>;
 //alert(u);
 var onecheckbox<?php echo $i?> = Y.one('.a<?php echo $i?>');
 //alert(onecheckbox<?php echo $i?>);
 if(onecheckbox<?php echo $i?> != null){
 //alert(onecheckbox<?php echo $i?>.get('checked'));
 onecheckbox<?php echo $i?>.get('checked') ? navcheck<?php echo $i?>.set('checked', true) : navcheck<?php echo $i?>.set('checked', false);
 }
<?php }?>
 }
 });*/

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
})
    
$("#foldtablelist .all_fold .foldbtn").click(function(){
        if($(".tr1").css("display")=="none"){
            $(".tr1").css("display","table-row");
            $(".tr2").css("display","table-row");
            $(".foldbtn").addClass("unfold");
            $("th.all_fold .foldbtn").removeClass("unfold");
        }else{
            $(".tr1").css("display","none");
            $(".tr2").css("display","none");
            $(".foldbtn").removeClass("unfold");
            $("th.all_fold .foldbtn").addClass("unfold");
        }
    }
)

function derivedchange(){
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
        var func="/profiles/getdropdownlist/";
        var group="ProductCategory";
        
        $.ajax({url: admin_webroot+func,
			type:"POST",
			data:{group:group},
			dataType:"json",
			success: function(result){
				try{
					if(result.flag == 1){
    					var result_content = (result.flag == 1) ? result.content : "";
                        if(result_content!=""){
                            strbind(result_content);
                        }
                        $("#placement").modal("open");
    				}
    				if(result.flag == 2){
    					alert(result.content);
    				}
				}catch(e){
					alert(j_object_transform_failed);
					alert(o.responseText);
				}
	  		}
	  	});
    }else{
        if(confirm(j_please_select+" "+j_category_product))
        {
            return false;
        }
    }
}

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
    document.ArticleForm.action=admin_webroot+"product_categories/batch";
    document.ArticleForm.onsubmit= "";
    document.ArticleForm.submit();
}


function a_derivedchange(type,code)
{
    document.ArticleForm.action=admin_webroot+"product_categories/derivedchange/"+type+"/"+code;
    document.ArticleForm.onsubmit= "";
    document.ArticleForm.submit();
}

function changeOrder(updown,id,next,thisbtn){
//	alert(admin_webroot);
    $.ajax({
        url:admin_webroot+"product_categories/changeorder/"+updown+"/"+id+"/"+next,
        type:"POST",
        data:{},
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

//绑定下拉
function strbind(arr){
    //先清空下拉中的值
	var profilegroup=document.getElementById("profilegroup");
    $("#profilegroup option").remove();
    var optiondefault=document.createElement("option");
	    profilegroup.appendChild(optiondefault);
	    optiondefault.value="0";
	    optiondefault.text=j_templates;
	for(var i=0;i<arr.length;i++){
		var option=document.createElement("option");
	    profilegroup.appendChild(option);
	    option.value=arr[i]['Profile']['code'];
	    option.text=arr[i]['ProfileI18n']['name'];
	}
	$("profilegroup").trigger('changed.selected.amui');
}

//修改档案分类导出
function changeprofile(){
    var select_type = document.getElementById("select_type");
    var code=document.getElementById("profilegroup").value;
    if(code==0){
        alert("请选择导出方式");
        return false;
    }
    var export_csv = document.getElementById("export_csv");
    var type=export_csv.value;
    if(confirm("<?php echo $ld['confirm_export']."?"; ?>")){
        if(type!=""){
            a_derivedchange(type,code);
            $("#placement").modal("close");
        }else{
            alert(j_select_operation_type);
            return false;
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