<style>
    .am-radio, .am-checkbox{display:inline;}
    em{color:red;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<?php echo $form->create('PageAction',array('action'=>'/page_action_view/'.$id,'onsubmit' =>'return check_page_style()'));?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" >
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information'];?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="basic_info" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['basic_information']?>
            </h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table" id="hotel_img_ul">
                    <tr>
                        <th><?php echo $ld['module']?></th>
                        <td>
                            <input type="hidden" name="data[PageAction][page_type_id]" value="<?php echo isset($type_id)?$type_id:'0'; ?>" />
                            <?php echo $pagetype_info['PageType']['name'];?>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['page_name']?></th>
                        <td><input type="text" style="width:200px;float:left;" name="data[PageAction][name]" value="<?php if(isset($page_action_info['PageAction'])){echo $page_action_info['PageAction']['name'];} ?>" /><em>*</em></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['controller']?></th>
                        <td><input type="text" style="width:200px;float:left;" name="data[PageAction][controller]" value="<?php if(isset($page_action_info['PageAction'])){echo $page_action_info['PageAction']['controller'];} ?>" /><em>*</em></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['function']?></th>
                        <td><input type="text" style="width:200px;float:left;" name="data[PageAction][action]" value="<?php if(isset($page_action_info['PageAction'])){echo $page_action_info['PageAction']['action'];} ?>" /><em>*</em></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;">布局</th>
                        <td><input type="text" style="width:200px;float:left;" name="data[PageAction][layout]" value="<?php if(isset($page_action_info['PageAction'])){echo $page_action_info['PageAction']['layout'];} ?>" /><em>*</em></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['valid']?></th>
                        <td><label class="am-radio am-success"><input type="radio" value="1" name="data[PageAction][status]" data-am-ucheck checked/><?php echo $ld['yes']?></label>
                            <label style="margin-left:10px;" class="am-radio am-success"><input type="radio" name="data[PageAction][status]" data-am-ucheck value="0" <?php if(isset($page_action_info['PageAction'])&&$page_action_info['PageAction']['status'] == 0){ echo "checked"; } ?>/><?php echo $ld['no']?></label>
                        </td>
                    </tr>
                </table>
                <div class="btnouter">
                    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
            <?php
            if($id!=0){
                ?>
                <div>
                    <h2 style="padding-left:22px;"><?php echo $ld['stylelist']?></h2>
                    <div id="tablelist" class="am-panel-bd am-form-detail am-form am-form-horizontal">
                        <?php if($svshow->operator_privilege("page_types_add")){?>
                            <p class="am-u-md-12">
                                <?php echo $html->link($ld['add'].$ld['module'],"page_module_view/0?action_id=".$id,array("class"=>"am-btn am-btn-warning am-btn-sm am-fr"),'',false,false);?>
                            </p>
                        <?php }?>
                        <input type="hidden" id="type_id" value="<?php echo isset($id)?$id:''; ?>">
                        <table id="foldtablelist" class="am-table">
                            <thead>
                            <tr>
                                <th><?php echo $ld['module_name']?></th>
                                <th><?php echo $ld['module_title']?></th>
                                <th class="am-hide-md-down"><?php echo $ld['module_code']?></th>
                                <th class="am-hide-md-down"><?php echo $ld['module_location']?></th>
                                <th class="am-hide"><?php echo $ld['module_width']?></th>
                                <th class="am-hide"><?php echo $ld['module_height']?></th>
                                <th><?php echo $ld['module_float']?></th>
                                <th><?php echo $ld['sort']?></th>
                                <th><?php echo $ld['status']?></th>
                                <th><?php echo $ld['operate']?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($pagemodule_list)&&sizeof($pagemodule_list)>0){
                                foreach($pagemodule_list as $k=>$v){
                                    ?>
                                    <tr class="tr0">
                                        <td><span class="<?php echo (isset($v['SubPageModule']) && sizeof($v['SubPageModule'])>0)?"foldbtn":"foldbtnnone";?>" id="<?php echo $v['PageModule']['id']?>"></span><?php echo $v['PageModuleI18n']['name']; ?></td>
                                        <td><?php echo $v['PageModuleI18n']['title']; ?></td>
                                        <td class="am-hide-md-down"><?php echo $v['PageModule']['code']; ?></td>
                                        <td class="am-hide-md-down"><?php echo $v['PageModule']['position']; ?></td>
                                        <td class="am-hide"><?php echo $v['PageModule']['width']; ?></td>
                                        <td class="am-hide"><?php echo $v['PageModule']['height']; ?></td>
                                        <td>
                                            <?php if($v['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($v['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($v['PageModule']['float']==2){echo $ld['module_right_floating'];}?>
                                        </td>
                                        <td >
                                            <?php if(count($pagemodule_list)==1){echo "-";}elseif($k==0){?>
                                                <a onclick="changeOrder('down','<?php echo $v['PageModule']['id'];?>','0',this)">&#9660;</a>
                                            <?php }elseif($k==(count($pagemodule_list)-1)){?>
                                                <a onclick="changeOrder('up','<?php echo $v['PageModule']['id'];?>','0',this)" style="color:#cc0000;">&#9650;</a>
                                            <?php }else{?>
                                                <a onclick="changeOrder('up','<?php echo $v['PageModule']['id'];?>','0',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['PageModule']['id'];?>','0',this)">&#9660;</a>
                                            <?php }?>
                                        </td>
                                        <td style="text-align: center;"><?php if($v['PageModule']['status']==1){?>
                                                <?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$v["PageModule"]["id"].')')) ?>
                                            <?php }elseif($v['PageModule']['status'] == 0){?>
                                                <?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$v["PageModule"]["id"].')'))?>
                                            <?php }?>
                                        </td>
                                        <td>
                                            <?php if($svshow->operator_privilege("page_types_edit")){?>
                                                <a class="am-btn am-btn-default am-btn-xs  am-seevia-btn-edit" href="<?php echo $html->url("/page_actions/page_module_view/{$v['PageModule']['id']}"); ?>">
                                                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                                </a>
                                            <?php }if($svshow->operator_privilege("page_types_remove")){?>
                                                <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(confirm('{$ld['confirm_delete']}')){list_delete_submit(admin_webroot+'page_actions/module_remove/<?php echo $v['PageModule']['id']."/".$id;?>');}">
                                                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                                </a>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php if(isset($v['SubPageModule']) && sizeof($v['SubPageModule'])>0){
                                        foreach($v['SubPageModule'] as $kk=>$vv){
                                            ?>
                                            <tr class="tr1">
                                                <td><span class="<?php echo (isset($vv['SubPageModule']) && sizeof($vv['SubPageModule'])>0)?"foldbtn":"foldbtnnone";?>" id="<?php echo $vv['PageModule']['id']?>"></span><?php echo $vv['PageModuleI18n']['name']; ?></td>
                                                <td><?php echo $vv['PageModuleI18n']['title']; ?></td>
                                                <td class="am-hide-md-down"><?php echo $vv['PageModule']['code']; ?></td>
                                                <td class="am-hide-md-down" ><?php echo $vv['PageModule']['position']; ?></td>
                                                <td class="am-hide"><?php echo $vv['PageModule']['width']; ?></td>
                                                <td class="am-hide"><?php echo $vv['PageModule']['height']; ?></td>
                                                <td>
                                                    <?php if($vv['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($vv['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($vv['PageModule']['float']==2){echo $ld['module_right_floating'];}?>
                                                </td>
                                                <td>
                                                    <?php if(count($v['SubPageModule'])==1){echo "-";}elseif($kk==0){?>
                                                        <a onclick="changeOrder('down','<?php echo $v['PageModule']['id'];?>','next',this)">&#9660;</a>
                                                    <?php }elseif($kk==(count($v['SubPageModule'])-1)){?>
                                                        <a onclick="changeOrder('up','<?php echo $vv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>
                                                    <?php }else{?>
                                                        <a onclick="changeOrder('up','<?php echo $vv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vv['PageModule']['id'];?>','next',this)">&#9660;</a>
                                                    <?php }?>
                                                </td>
                                                <td style="text-align: center;"><?php if($vv['PageModule']['status']==1){?>
                                                        <?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vv["PageModule"]["id"].')')) ?>
                                                    <?php }elseif($vv['PageModule']['status'] == 0){?>
                                                        <?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vv["PageModule"]["id"].')'))?>
                                                    <?php }?>
                                                </td>
                                                <td>
                                                    <?php if($svshow->operator_privilege("page_types_edit")){?>
                                                        <a class="am-btn am-btn-default am-btn-xs  am-seevia-btn-edit" href="<?php echo $html->url("/page_actions/page_module_view/{$vv['PageModule']['id']}"); ?>">
                                                            <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                                        </a>
                                                    <?php }if($svshow->operator_privilege("page_types_remove")){?>
                                                        <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(confirm('{$ld['confirm_delete']}')){list_delete_submit(admin_webroot+'page_actions/module_remove/<?php echo $vv['PageModule']['id']."/".$id;?>');}">
                                                            <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                                        </a>
                                                    <?php }?>
                                                </td>
                                            </tr>
                                            <?php
                                            if(isset($vv['SubPageModule']) && sizeof($vv['SubPageModule'])>0){
                                                foreach($vv['SubPageModule'] as $kkk=>$vvv){
                                                    ?>
                                                    <tr class="tr2">
                                                        <td><span class="<?php echo (isset($vvv['SubPageModule']) && sizeof($vvv['SubPageModule'])>0)?"foldbtn":"foldbtnnone";?>" id="<?php echo $vvv['PageModule']['id']?>"></span><?php echo $vvv['PageModuleI18n']['name']; ?></td>
                                                        <td><?php echo $vvv['PageModuleI18n']['title']; ?></td>
                                                        <td><?php echo $vvv['PageModule']['code']; ?></td>
                                                        <td><?php echo $vvv['PageModule']['position']; ?></td>
                                                        <td><?php echo $vvv['PageModule']['width']; ?></td>
                                                        <td><?php echo $vvv['PageModule']['height']; ?></td>
                                                        <td>
                                                            <?php if($vvv['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($vvv['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($vvv['PageModule']['float']==2){echo $ld['module_right_floating'];}?>
                                                        </td>
                                                        <td>
                                                            <?php if(count($vv['SubPageModule'])==1){echo "-";}elseif($kkk==0){?>
                                                                <a onclick="changeOrder('down','<?php echo $vvv['PageModule']['id'];?>','next',this)">&#9660;</a>
                                                            <?php }elseif($kkk==(count($vv['SubPageModule'])-1)){?>
                                                                <a onclick="changeOrder('up','<?php echo $vvv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>
                                                            <?php }else{?>
                                                                <a onclick="changeOrder('up','<?php echo $vvv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vvv['PageModule']['id'];?>','next',this)">&#9660;</a>
                                                            <?php }?>
                                                        </td>
                                                        <td style="text-align: center;"><?php if($vvv['PageModule']['status']==1){?>
                                                                <?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vvv["PageModule"]["id"].')')) ?>
                                                            <?php }elseif($vvv['PageModule']['status'] == 0){?>
                                                                <?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vvv["PageModule"]["id"].')'))?>
                                                            <?php }?>
                                                        </td>
                                                        <td>
                                                            <?php if($svshow->operator_privilege("page_types_edit")){?>
                                                                <a class="am-btn am-btn-default am-btn-xs  am-seevia-btn-edit" href="<?php echo $html->url("/page_actions/page_module_view/{$vvv['PageModule']['id']}"); ?>">
                                                                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                                                </a>
                                                            <?php }if($svshow->operator_privilege("page_types_remove")){?>
                                                                <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(confirm('{$ld['confirm_delete']}')){list_delete_submit(admin_webroot+'page_actions/module_remove/<?php echo $vvv['PageModule']['id']."/".$id;?>');}">
                                                                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                                                </a>
                                                            <?php }?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            }
                                        }
                                        ?>
                                    <?php
                                    }
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<script>
    window.onload=function(){
        <?php if(isset($id)&&$id!=0){ ?>
        document.getElementById("tablelist").style.display="block";
        <?php } ?>
    };
    function check_page_style(){
        var name=document.getElementsByName("data[PageAction][name]")[0].value;
        var controller=document.getElementsByName("data[PageAction][controller]")[0].value;
        var action=document.getElementsByName("data[PageAction][action]")[0].value;
        if(name==""){
            alert("页面名称不能为空！");
            return false;
        }else if(controller==""){
            alert("控制器名称不能为空！");
            return false;
        }else if(action==""){
            alert("方法名称不能为空！");
            return false;
        }
        return true;
    }

    function changeOrder(updown,id,next,thisbtn){
        var type_id = document.getElementById("type_id").value;
        changeHtml(thisbtn);
        var sUrl = "/admin/page_actions/changeorder/"+updown+"/"+id+"/"+next+"/"+type_id;//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            success: function (result) {
                $("#tablelist").html(result.content);
            }
        });
    }
</script>
