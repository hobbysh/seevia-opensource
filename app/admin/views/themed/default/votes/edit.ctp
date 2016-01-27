<?php
/*****************************************************************************
 * SV-Cart 在线管理新增
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
<style>
	.am-radio, .am-checkbox{display:inline;}
	 em{color:red;}
	.am-checkbox {margin-top:0px; margin-bottom:0px;}
	label{font-weight:normal;}
	.am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
	.am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<?php echo $form->create('votes',array('action'=>'/edit/'.$vote_info["Vote"]["id"],'id'=>'vote_form'));?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
        <?php if($svshow->operator_privilege("votes_option_list")){?>
            <li><a href="#vote"><?php echo $ld['vote_options']?></a></li>
        <?php }?>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  >
    <div id="basic_info" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['basic_information']?>
            </h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th  style="padding-top:15px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['vote_investigat_subject']?></th>
                    </tr>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                            <td>
                                <input style="width:200px;float:left;" type="text"  name="data[VoteI18n][<?php echo $k?>][name]" value="<?php echo @$vote_info['VoteI18n'][$v['Language']['locale']]['name']?>" /></dd></dl>
                                <input type="hidden" name="data[VoteI18n][<?php echo $k?>][id]" value="<?php echo @$vote_info['VoteI18n'][$v['Language']['locale']]['id']?>" />
                                <input type="hidden" name="data[VoteI18n][<?php echo $k?>][locale]" value="<?php echo $v['Language']['locale']?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em>*</em>
                            </td>
                    </tr>
                        <?php }}?>
                    <tr>
                        <th style="padding-top:15px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['subject_description']?></th>
                    </tr>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                            <td><textarea style="width:200px;float:left;" id="<?php echo $v['Language']['locale'];?>_txt" name="data[VoteI18n][<?php echo $k?>][description]" ><?php echo @$vote_info['VoteI18n'][$v['Language']['locale']]['description']?></textarea><?php if(sizeof($backend_locales)>1){?><span class="lang" style="margin-top:10px;" ><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em>*</em></td>
                    </tr>
                        <?php }}?>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['start_date']?></th>
                        <td><input style="width:200px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="data[Vote][start_time]" value="<?php echo date('Y-m-d',strtotime($vote_info['Vote']['start_time']));?>" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['end_date']?></th>
                        <td><input style="width:200px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="data[Vote][end_time]" value="<?php echo date('Y-m-d',strtotime($vote_info['Vote']['end_time']));?>" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['choose_more']?></th>
                        <td><label class="am-radio am-success" style="padding-top:2px;"><input type="radio"  name="data[Vote][can_multi]" value="0" data-am-ucheck <?php if($vote_info['Vote']['can_multi']==0){ echo "checked";}?>  /><?php echo $ld['yes']?></label>
                        	<label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[Vote][can_multi]" value="1" data-am-ucheck <?php if($vote_info['Vote']['can_multi']==1){ echo "checked";}?> /><?php echo $ld['no']?><em style="top:3px;">*</em></label></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['valid']?></th>
                        <td><label class="am-radio am-success" style="padding-top:2px;"><input type="radio"  name="data[Vote][status]" value="1" data-am-ucheck <?php if($vote_info['Vote']['status']==1){ echo "checked";}?>  /><?php echo $ld['yes']?></label>
                        	<label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[Vote][status]" value="0" data-am-ucheck <?php if($vote_info['Vote']['status']==0){ echo "checked";}?> /><?php echo $ld['no']?><em style="top:2px;">*</em></label></td>
                    </tr>
                </table>
                <div class="btnouter">
                    <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['d_submit']?>" onclick='chrck_form()'/> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
    <?php if($svshow->operator_privilege("votes_option_list")){?>
        <div id="vote" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title">
                    <?php echo $ld['vote_options']?>
                </h4>
            </div>
            <div id="vote_options" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding:0px;">
                    <table id="attrTable" class="am-table">
                        <thead>
                        <tr>
                            <th align="left" style="padding: 2px 0px 2px 10px;"><?php echo $ld['status']?></th>
                            <th align="left"><?php echo $ld['title']?></th>
                            <th align="left" class="am-hide-sm-only"><?php echo $ld['options_des']?></th>
                            <th align="left"><?php echo $ld['option_votes']?></th>
                            <th align="left"><?php echo $ld['sort']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($voteoption_list)&&sizeof($voteoption_list)>0){ foreach($voteoption_list as $vk=>$vo){?>
                            <tr>
                                <td>
                                    <a href='javascript:;' onclick='removeaddr(this)' style="width:35px;">[-]</a>
                                    <input type="checkbox" name="data[VoteOption][status][<?php echo $vk;?>]" value="1" <?php if($vo['VoteOption']['status']==1){ echo "checked";}?>>
                                </td>
                                <td>
                                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                                        <p><input style="width:200px;float:left;" type="text" name="data[VoteOptionI18n][<?php echo $vk;?>][<?php echo $v['Language']['locale'];?>_name][]" value="<?php echo $vo['VoteOptionI18n'][$v['Language']['locale']]['name']?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em style="color:red;">*</em></p>
                                        <?php }} ?>
                                </td>
                                <td class="am-hide-sm-only">
                                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                                        <p><textarea style="width:200px;"  name="data[VoteOptionI18n][<?php echo $vk?>][<?php echo $v['Language']['locale'];?>_description][]" ><?php echo $vo['VoteOptionI18n'][$v['Language']['locale']]['description']?></textarea><?php if(sizeof($backend_locales)>1){?><span style="position:relative;top:-40px;left:210px;" class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?></p>
                                        <?php }} ?>
                                </td>
                                <td>
                                    <input type="hidden" name="data[VoteOption][option_count][<?php echo $vk?>]" value="<?php echo $vo['VoteOption']['option_count'];?>">
                                    <span><?php echo $vo['VoteOption']['option_count'];?></span>
                                </td>
                                <td style="padding-top:15px">
                                    <input style="width: 2.5em;" type="text" name="data[VoteOption][orderby][<?php echo $vk?>]" value="<?php echo $vo['VoteOption']['orderby']?>" >
                                </td>
                            </tr>
                        <?php }} if(isset($vk)){ $vk++; }else{ $vk=0; }?>
                        <tr>
                            <td>
                                <a href='javascript:;' onclick='addaddr(this,<?php echo $vk?>)' style="width:35px;">[+]</a>
                                <input type="checkbox" name="data[VoteOption][status][<?php echo $vk?>]" value="1">
                            </td>
                            <td>
                                <?php
                                if(isset($backend_locales)&&sizeof($backend_locales)>0){
                                    foreach ($backend_locales as $k => $v){?>
                                        <p><input style="width:200px;float:left;" type="text" name="data[VoteOptionI18n][<?php echo $vk?>][<?php echo $v['Language']['locale'];?>_name][]" value=""/><?php if(sizeof($backend_locales)>1){?><span sclass="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?>
                                            <em style="color:red;top:3px;">*</em></p>
                                        <?php }}?>
                            </td>
                            <td class="am-hide-sm-only" >
                                <?php
                                if(isset($backend_locales)&&sizeof($backend_locales)>0){
                                    foreach ($backend_locales as $k => $v){?>
                                        <p><textarea style="width:200px;" name="data[VoteOptionI18n][<?php echo $vk?>][<?php echo $v['Language']['locale'];?>_description][]" ></textarea><?php if(sizeof($backend_locales)>1){?><span class="lang" style="position:relative;top:-40px;left:210px;"><?php echo $ld[$v['Language']['locale']];?></span><?php }?></p>
                                        <?php }}?>
                            </td>
                            <td style="padding-top:15px;">
                                 <span>0</span>
                            </td>
                            <td>
                                <input style="width: 2.5em;" type="text" name="data[VoteOption][orderby][<?php echo $vk?>]" value="" >
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="btnouter">
                        <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" onclick='chrck_form()'/> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    function chrck_form(){
        if(exist('chi_txt')){
            if(!check_null('chi_txt')){alert('请填写中文主题描述');return;}
        }else if(exist('eng_txt')){
            if(!check_null('eng_txt')){alert('请填写英文主题描述');return;}
        } else if(exist('jpn_txt')){
            if(!check_null('jpn_txt')){alert('请填写日文文主题描述');return;}
        }
        var form=document.getElementById('vote_form');
        form.submit();
    }

    function check_null(id){
        var c=document.getElementById(id).value;
        if(c.replace(/(^\s*)|(\s*$)/g,"")==""){return false;}
        else{return true;}
    }

    function exist(id){
        var s=document.getElementById(id);
        if(s){return true}
        else{return false}
    }

    /**
     * 新增一个规格
     */
    function addaddr(obj,k){
        var src = obj.parentNode.parentNode;
        var idx = rowindex(src);
        var tbl = document.getElementById('attrTable');
        var row = tbl.insertRow(idx + 1);
        var cell = row.insertCell(-1);
        var img_str = src.cells[0].innerHTML.replace(/(.*)(addaddr)(.*)(\[)(\+)/i, "$1removeaddr$3$4-").replace("data[VoteOption][status]["+k+"]", "data[VoteOption][status]["+(parseInt(tbl.rows.length)-2)+"]");
        cell.innerHTML = img_str;
        var t1 = '['+k+']';
        for(var i=1;i<5;i++){
            if(i==1||i==2||i==4){
                //var reg = eval("/"+k+"/g");
                //row.insertCell(-1).innerHTML = src.cells[i].innerHTML.replace(reg, (parseInt(tbl.rows.length)-2));
                row.insertCell(-1).innerHTML = src.cells[i].innerHTML.replace(t1, '['+(parseInt(tbl.rows.length)-2)+']').replace(t1, '['+(parseInt(tbl.rows.length)-2)+']').replace(t1, '['+(parseInt(tbl.rows.length)-2)+']');
            }else{
                row.insertCell(-1).innerHTML=src.cells[i].innerHTML;
            }
        }
    }

    function removeaddr(obj){
        var row = rowindex(obj.parentNode.parentNode);
        var tbl = document.getElementById('attrTable');
        tbl.deleteRow(row);
    }
</script>