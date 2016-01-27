<?php
/*****************************************************************************
 * Seevia 资源库管理
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
    .seolink a:first-child{text-decoration:underline;color:green;}
    .am-panel-title div{font-weight:bold;}

   @media screen and (max-width:500px){
   .shanchu{margin-top:5px;margin-left:7px;}
}
</style>
<div class="am-g am-other_action" style="margin:10px 0 0 0">
    <div class="am-text-right  am-btn-group-xs ">
       <?php if(isset($open_type) && !empty($open_type)){ ?>
            <?php if($svshow->operator_privilege("open_menus_add")){echo $html->link($ld['synchronization_to_micro_message'],"javascript:void(0);",array("data-am-modal"=>"{target: '#open_type_select', closeViaDimmer: 0, width: 400, height: 225}","class"=>"am-btn am-btn-warning am-radius am-btn-sm "));}?>
        <?php }?> 
        	<?php if($svshow->operator_privilege("open_menus_add")){?>
     		
    		             <a class="am-btn am-btn-warning am-radius am-btn-sm " href="<?php echo $html->url('view/'); ?>">
				  <span class="am-icon-plus"></span>
				  <?php echo $ld['add'] ?>
				</a>
	    <?php	}?>
        	
    </div><br/>
</div>
<div id="tablelist" class="">
    <?php echo $form->create('OpenMenus',array('action'=>'','name'=>"OpenMenuForm","type"=>"get","onsubmit"=>"return false"));?>
    <div class="am-panel-group am-panel-tree" id="accordion">
        <!--标题栏-->
        <div class="listtable_div_btm  am-panel-header">
            <div class="am-panel-hd">
                <div class="am-panel-title">
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['menu_name']?></div>
                    <div class="am-u-lg-4 am-u-md-6 am-u-sm-6"><?php echo $ld['menu_content']?></div>
                    <div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-hide-md-down"><?php echo $ld['menu'].$ld['type']?></div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-hide-md-down"><?php echo $ld['status']?></div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-hide-md-down"><?php echo $ld['sort']?></div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
        <!--一级 菜单-->
        <?php if(isset($menu) && sizeof($menu)>0){$i=0;foreach($menu as $k => $v){$i++;?>
        <div>
            <div class="listtable_div_top am-panel-body" >
                <div class="am-panel-bd fuji">
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                        <span data-am-collapse="{parent: '#accordion', target:'#openMenu_<?php echo $v['OpenMenu']['id']?>'}" class="<?php echo (isset($v['SubMenu'])&&!empty($v['SubMenu']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;
                        <?php echo $v['OpenMenu']['name'];?>
                    </div> 
                    <div class="am-u-lg-4 am-u-md-6 am-u-sm-6">&nbsp;
                        <?php if($v['OpenMenu']['type']=="click"){echo $v['OpenMenu']['key'];}else{echo $v['OpenMenu']['url'];}?>
                    </div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-2 am-hide-md-down">&nbsp;
                        <?php if($v['OpenMenu']['type']=="view"){echo $ld['external_chain'];}else if($v['OpenMenu']['type']=="click"){echo $ld['keyword'];}?>
                    </div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-hide-md-down" >
                        <?php if($svshow->operator_privilege("open_menus_edit")){
                            if($v['OpenMenu']['status']=='1'){
                                echo '<div style="cursor:pointer;color:#5eb95e" class="am-icon-check" onclick=listTable.toggle(this,"open_menus/toggle_on_status",'.$v["OpenMenu"]["id"].')></div>';
                            }else{
                                echo '<div style="cursor:pointer;color:#dd514c" class="am-icon-close" onclick=listTable.toggle(this,"open_menus/toggle_on_status",'.$v["OpenMenu"]["id"].')></div>';
                            }
                        }else{
                            if($v['OpenMenu']['status']=='1'){
                                echo '<div style="color:#5eb95e" class="am-icon-check"></div>';
                            }else{
                                echo '<div style="color:#dd514c" class="am-icon-close"></div>';
                            }
                        } ?>
                    </div>
                    <div class="  am-u-md-1 am-u-sm-1 am-hide-md-down"  >
                        <?php if(count($menu)==1){echo "-";}elseif($k==0){?>
                            <a class="downBtn" style="cursor:pointer;" onclick="changeOrder('down','<?php echo $v['OpenMenu']['id'];?>','0',this)">&#9660;</a>
                        <?php }elseif($k==(count($menu)-1)){?>
                            <a onclick="changeOrder('up','<?php echo $v['OpenMenu']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>
                        <?php }else{?>
                            <a onclick="changeOrder('up','<?php echo $v['OpenMenu']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>&nbsp;<a style="cursor:pointer;" onclick="changeOrder('down','<?php echo $v['OpenMenu']['id'];?>','0',this)">&#9660;</a>
                        <?php }?>
                    </div>
                    <div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-action" > 
                        <?php if($svshow->operator_privilege("open_menus_edit")){?>
	                         <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/open_menus/view/'.$v['OpenMenu']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a> <?php 	}?> <?php if($svshow->operator_privilege("open_menus_remove")){?>
		                            <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(j_confirm_delete){list_delete_submit(admin_webroot+'open_menus/remove/<?php echo $v['OpenMenu']['id'] ?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
		                         <?php 	 }?>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <!--二级 菜单-->
                <?php if(isset($v['SubMenu']) && !empty($v['SubMenu'])>0){?>
                    <div class="am-panel-collapse am-collapse am-panel-child" id="openMenu_<?php echo $v['OpenMenu']['id']?>">
                        <?php $j=0;foreach($v['SubMenu'] as $kk=>$vv){$j++;?>
                            <div class="am-panel-bd am-panel-childbd">
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                    <span style="margin-left:20px;" data-am-collapse="{parent: '#openMenu_<?php echo $v['OpenMenu']['id']?>', target:'#<?php echo $v['OpenMenu']['id']?>'}" class="<?php echo (isset($vv['SubMenu']) && sizeof($vv['SubMenu'])>0)?"am-icon-plus":"am-icon-minus";?>" ></span>&nbsp;
                                    <?php echo $vv['OpenMenu']['name'];?>
                                </div>
                                <div class="am-u-lg-4 am-u-md-6 am-u-sm-6">&nbsp;
                                    <?php if($vv['OpenMenu']['type']=="click"){echo $vv['OpenMenu']['key'];}else{echo $vv['OpenMenu']['url'];}?>
                                </div>
                                <div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-hide-md-down">&nbsp;
                                    <?php if($vv['OpenMenu']['type']=="view"){echo $ld['external_chain'];}else if($vv['OpenMenu']['type']=="click"){echo $ld['keyword'];}?>
                                </div>
                                <div class="am-u-md-1 am-u-sm-1 am-hide-md-down " >&nbsp;
                                    <?php if($svshow->operator_privilege("open_menus_edit")){
                                        if($vv['OpenMenu']['status']=='1'){
                                            echo '<div style="cursor:pointer;color:#5eb95e" class="am-icon-check" onclick=listTable.toggle(this,"open_menus/toggle_on_status",'.$vv["OpenMenu"]["id"].')></div>';
                                        }else{
                                            echo '<div style="cursor:pointer;color:#dd514c" class="am-icon-close" onclick=listTable.toggle(this,"open_menus/toggle_on_status",'.$vv["OpenMenu"]["id"].')></div>';
                                        }
                                    }else{
                                        if($vv['OpenMenu']['status']=='1'){
                                            echo '<div style="color:#5eb95e" class="am-icon-check"></div>';
                                        }else{
                                            echo '<div style="color:#dd514c" class="am-icon-close"></div>';
                                        }
                                    } ?>
                                </div>
                                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-hide-md-down">&nbsp;
                                    <?php
                                    if(count($v['SubMenu'])==1){echo "-";}
                                    elseif($kk==0){
                                        ?><a style="cursor:pointer;" onclick="changeOrder('down','<?php echo $vv['OpenMenu']['id'];?>','next',this)">&#9660;</a><?php
                                    }elseif($kk==(count($v['OpenMenu'])-1)){
                                        ?><a onclick="changeOrder('up','<?php echo $vv['OpenMenu']['id'];?>','next',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a><?php
                                    }else{
                                        ?><a onclick="changeOrder('up','<?php echo $vv['OpenMenu']['id'];?>','next',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>&nbsp;<a style="cursor:pointer;" onclick="changeOrder('down','<?php echo $vv['OpenMenu']['id'];?>','next',this)">&#9660;</a><?php
                                    }
                                    ?>
                                </div>
                                <div class="am-u-lg-2 am-u-md-3 am-u-sm-4" style="padding-left:0px;">&nbsp;
                             <?php if($svshow->operator_privilege("open_menus_edit")){?>
                             <a class="am-btn am-btn-default am-btn-xs am-radius" href="<?php echo $html->url('/open_menus/view/'.$vv['OpenMenu']['id']); ?>">
                             <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                              </a> 
                            <?php  }?>

                            <?php if($svshow->operator_privilege("open_menus_remove")){?>
                                    <a class="am-btn am-btn-default am-btn-xs am-text-danger shanchu am-seevia-btn-delete" href="javascript:;" onclick="if(j_confirm_delete){list_delete_submit(admin_webroot+'open_menus/remove/<?php echo $vv['OpenMenu']['id'] ?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
                                 <?php   }?>
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                        <?php }?>
                    </div>
                <?php }?>
            </div>
            <?php }}else { ?>
                <div class="no_data_found"><?php echo $ld['no_data_found']?></div>
            <?php }?>
        </div>
    </div>
    <?php echo $form->end();?>
</div>

<!-- 菜单选择更新 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" name="open_type_select" id="open_type_select">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"><?php echo $ld['synchronization_to_micro_message']?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <table class="am-table">
                <tr>
                    <td style="text-align: right"><?php echo $ld['open_model_account'] ?></td>
                    <td style="text-align: left"><select id="open_type_id">
                            <option value=""><?php echo $ld['please_select'] ?></option>
                            <?php foreach($open_type as $k=>$v){ ?>
                                <option value="<?php echo $v['OpenModel']['open_type_id'] ?>"><?php echo $v['OpenModel']['open_type_id'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    
                    <td style="text-align:center"colspan=2><input class="am-btn  am-btn-success am-radius am-btn-sm" type="button" onclick="api_menu_action()" value="<?php echo $ld['d_submit']?>" /></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<!-- 菜单选择更新-->

<script type="text/javascript">
    function api_menu_action(){
        var open_type_id=document.getElementById("open_type_id").value;
        if(open_type_id!=""){
            window.location.href="/admin/open_menus/api_menu_action/"+open_type_id;
        }
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
    })

    function BrowserisIE(){
        if(navigator.userAgent.search("Opera")>-1){
            return false;
        }
        if(navigator.userAgent.indexOf("Mozilla/5.")>-1){
            return false;
        }
        if(navigator.userAgent.search("MSIE")>0){
            return true;
        }
    }

    function list_delete_submit1(sUrl){
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            success: function (result) {
                if(result.flag==1){
                    window.location.reload();
                }
                if(result.flag==2){
                    alert("删除失败，该菜单还有子菜单");
                }
            }
        });
    }

    function changeOrder(updown,id,next,thisbtn){
        var sUrl = "/admin/open_menus/changeorder/"+updown+"/"+id+"/"+next;//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            data:{},
            dataType: 'html',
            success: function (data) {
				var popcontent = document.createElement('div');
	     		popcontent.innerHTML = data;
	       		var tmp = $(popcontent).find('#tablelist').html();
	       		$("#tablelist").html(tmp);
	       		$("#tablelist input[type=checkbox]").uCheck();
            }
        });
    }
</script>