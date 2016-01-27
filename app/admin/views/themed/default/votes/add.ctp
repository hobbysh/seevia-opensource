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
echo $javascript->link('/skins/default/js/calendar/language/'.$backend_locale);
?>
<style>
    .am-radio, .am-checkbox{display:inline;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<?php echo $form->create('votes',array('action'=>'/add/','id'=>'add_vote_form'));?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
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
                <table class="am-table">
                    <tr>
                        <th style="padding-top:15px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['vote_investigat_subject']?></th>
                    </tr>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><input style="width:200px;float:left;" type="text" name="data[VoteI18n][<?php echo $k?>][name]" />
                                <input type="hidden" name="data[VoteI18n][<?php echo $k?>][locale]" value="<?php echo $v['Language']['locale']?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
                        </tr>
                        <?php }}?>
                    <tr>
                        <th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['subject_description']?></th>
                    </tr>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><textarea style="width:280px;float:left;" id="<?php echo $v['Language']['locale'];?>_txt" name="data[VoteI18n][<?php echo $k?>][description]" ></textarea><?php if(sizeof($backend_locales)>1){?><span class="lang" style="margin-top:10px;"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
                        </tr>
                        <?php }}?>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['start_date']?></th>
                        <td><input style="width:200px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="data[Vote][start_time]" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['end_date']?></th>
                        <td><input style="width:200px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="data[Vote][end_time]" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['choose_more']?></th>
                        <td><label class="am-radio am-success"><input type="radio" name="data[Vote][can_multi]" value="0" data-am-ucheck checked />是</label>
                            <label style="margin-left:10px;" class="am-radio am-success"><input type="radio" name="data[Vote][can_multi]" value="1" data-am-ucheck />否<em style="top:3px">*</em></label></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['valid']?></th>
                        <td><label class="am-radio am-success"><input type="radio" name="data[Vote][status]" value="1" data-am-ucheck checked /><?php echo $ld['yes']?></label>
                            <label style="margin-left:10px;" class="am-radio am-success"><input type="radio" name="data[Vote][status]" value="0" data-am-ucheck /><?php echo $ld['no']?><em style="top:3px">*</em></label></td>
                    </tr>
                </table>
                <div class="btnouter">
                    <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" onclick='chrck_form()'/> <input class="am-btn am-btn-success am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
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
        var form=document.getElementById('add_vote_form');
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
</script>