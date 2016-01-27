<style>
    .am-radio, .am-checkbox{display:inline;}
    em{color:red;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
    @media screen and (max-width:1096px){
        .scan{margin-top:5px;}
    }
</style>
<?php echo $form->create('PageType',array('action'=>'/view/'.$id.(isset($template_Info['Template']['name'])?'/'.$template_Info['Template']['name']:'')));?>
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
                        <th style="padding-top:13px;"><?php echo $ld['type']?></th>
                        <td><select id="type" name="data[PageType][page_type]" data-am-selected value="<?php if(isset($this->data['PageType'])){echo $this->data['PageType']['page_type'];} ?>">
                                <option value=""><?php echo $ld['please_select']?></option>
                                <option value="1" <?php if(isset($this->data['PageType']['page_type']) && $this->data['PageType']['page_type']=="1"){echo "selected";}?>><?php echo $ld['mobilephone']?></option>
                                <option value="0" <?php if(isset($this->data['PageType']['page_type']) && $this->data['PageType']['page_type']=="0"){echo "selected";}?>><?php echo $ld['computer']?></option>
                            </select><em style="top:6px;">*</em></td>
                    </tr>
                    <tr>
                        <th style="padding-top:13px;"><?php echo $ld['template']?></th>
                        <td><select name="data[PageType][code]" data-am-selected>
                                <option value=""><?php echo $ld['please_select']; ?></option>
                                <?php if(isset($template_list)&&sizeof($template_list)>0){foreach($template_list as $k=>$v){ ?>
                                    <option value="<?php echo $k; ?>" <?php if($this->data['PageType']['code']==$k){echo "selected";} ?>><?php echo $k; ?></option>
                                <?php }} ?>
                            </select><em style="top:6px;">*</em>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:18px;"><?php echo $ld['module_type']?></th>
                        <td><input type="text" style="width:150px;float:left;" class="border" name="data[PageType][name]" value="<?php if(isset($this->data['PageType']['name'])){echo $this->data['PageType']['name'];} ?>" /><em>*</em></td>
                    </tr>
                    <tr>
                        <th style="padding-top:18px;"><?php echo $ld['module_style']?></th>
                        <td><input type="text" style="width:150px;float:left;" class="border" name="data[PageType][css]" value="<?php if(isset($this->data['PageType']['css'])){echo $this->data['PageType']['css'];} ?>" /><em>*</em></td>
                    </tr>
                    <tr>
                        <th style="padding-top:13px;"><?php echo $ld['valid']?></th>
                        <td><label class="am-radio am-success"><input type="radio" value="1" name="data[PageType][status]" data-am-ucheck checked/><?php echo $ld['yes']?></label>
                            <label style="margin-left:10px;" class="am-radio am-success"><input type="radio" name="data[PageType][status]" data-am-ucheck value="0" <?php if(isset($this->data['PageType']['status'])&&$this->data['PageType']['status'] == 0){ echo "checked"; } ?>/><?php echo $ld['no']?></label></td>
                    </tr>
                </table>
            </div>
            <div class="btnouter">
                <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
            </div>
            <?php if(isset($this->data['PageType']['status'])){ ?>
                <div>
                    <h2 style="padding-left:22px;"><?php echo $ld['view_page_style']?></h2>
                    <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                        <?php if($svshow->operator_privilege("page_types_add")){?>
                            <p class="am-u-md-12">
                                <?php echo $html->link($ld['add_module_page'],"/page_actions/page_action_view/0?type_id=".$id,array("class"=>"am-btn am-btn-warning am-btn-sm am-fr","target"=>"_blank"),'',false,false);?>
                            </p>
                        <?php }?>
                        <table class="am-table">
                            <thead>
                            <tr>
                                <th style="width:30%;"><?php echo $ld['page_name']?></th>
                                <th style="width:20%;"><?php echo $ld['controller']?></th>
                                <th><?php echo $ld['method']?></th>
                                <th style="width:10%;"><?php echo $ld['status']?></th>
                                <th ><?php echo $ld['operate']?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($pageaction_list)&&sizeof($pageaction_list)>0){
                                foreach($pageaction_list as $k=>$v){
                                    ?>
                                    <tr>
                                        <td><?php echo $v['PageAction']['name'];?></td>
                                        <td><?php echo $v['PageAction']['controller'];?></td>
                                        <td><?php echo $v['PageAction']['action'];?></td>
                                        <td style="text-align: center;">
                                            <?php if($v['PageAction']['status']==1){?>
                                                <?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_page_status", '.$v["PageAction"]["id"].')')) ?>
                                            <?php }elseif($v['PageAction']['status'] == 0){?>
                                                <?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_page_status", '.$v["PageAction"]["id"].')'))?>
                                            <?php }?>
                                        </td>
                                        <td>
                                            <?php if(isset($pagetype_info)&&$pagetype_info['PageType']['page_type']=='1'&&$svshow->operator_privilege("page_types_edit")){?>
                                                <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-view" target='_blank' href="<?php echo $html->url("/".$v['PageAction']['controller']."/".$v['PageAction']['action']."?is_mobile=1",array("target"=>"_blank")); ?>">
                                                    <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                                                </a>
                                            <?php }else{?>
                                                <a class="am-btn am-btn-success am-btn-xs  am-seevia-btn-view" target='_blank' href="<?php echo $html->url("/".$v['PageAction']['controller']."/".$v['PageAction']['action']."?is_mobile=0",array("target"=>"_blank")); ?>">
                                                    <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                                                </a>
                                            <?php }
                                            if($svshow->operator_privilege("page_types_edit")){?>
                                                <a class="scan am-btn am-btn-default am-btn-xs  am-seevia-btn-edit" href="<?php echo $html->url("/page_actions/page_action_view/{$v['PageAction']['id']}?type_id={$id}",array('target'=>'_blank')); ?>">
                                                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                                </a>
                                            <?php }if($svshow->operator_privilege("page_types_remove")){?>
                                                <a class="scan am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(confirm('确认删除该页面样式吗?')){list_delete_submit(admin_webroot+'page_actions/remove/<?php echo $v['PageAction']['id'] ?>');}">
                                                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                                </a>
                                            <?php }?>
                                        </td>
                                    </tr>
                                <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<style type="text/css">
    .action-span{margin-top:8px;}
    .tablemain .listtable th:last-child,.tablemain .listtable tr td{
        text-align:center;
    }
    .tablemain .listtable th:last-child,.tablemain .listtable tr td:last-child{
        text-align:right;
    }
    .tablemain .listtable tr td a{
        color:#000;
        margin:0;
        padding:0 5px;
    }
    .tablemain .listtable tr td:last-child a:first-child{
        border-left:medium none;
    }
    .tablemain .listtable tr td:last-child a{
        border-left:1px solid #000;
    }
</style>