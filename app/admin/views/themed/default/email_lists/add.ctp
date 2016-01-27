<?php
/*****************************************************************************
 * SV-Cart 添加杂志模板
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
        .am-checkbox {margin-top:0px; margin-bottom:0px;}
        label{font-weight:normal;}
        .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
        .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
    </style>
<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<?php echo $form->create('email_lists',array('action'=>'/add/','onsubmit'=>'return mailtemplates_check();'));?>
<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
    <input name="data[MailTemplateI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo  $v['Language']['locale'];?>">
    <?php break;}}?>
    <div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
        <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
            <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
            <li><a href="#plain_text"><?php echo $ld['plain_text_message_content']?></a></li>
            <li><a href="#html_email"><?php echo $ld['html_email_content']?></a></li>
            <li><a href="#sms"><?php echo $ld['sms_content']?></a></li>
        </ul>
    </div>
    <div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
        <div id="basic_info" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
            </div>
            <div id="basic_information" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <input id="mailid" type="hidden" name="data[MailTemplate][id]" value="<?php echo $this->data['MailTemplate']['id'];?>" />
                    <table class="am-table">
                        <tr><th style="padding-top:15px;"><?php echo $ld['email_code']?></th>
                            <td><input style="width:200px;" type="text"  id="data_mailtemplate_code" name="data[MailTemplate][code]"  /> </td>
                        </tr>
                        <tr>
                            <th style="padding-top:15px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['subject']?></th>
                        </tr>
                            <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                            <tr>
                                <td><input style="width:200px;float:left;" type="text"  id="title<?php echo $v['Language']['locale']?>" name="data[MailTemplateI18n][<?php echo $k;?>][title]" value="" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
                            </tr>
                            <?php }}?>
                        <tr>
                            <th style="padding-top:15px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['email_help']?></th>
                        </tr>
                            <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                            <tr>
                                <td><input style="width:200px;float:left;" type="text"  id="title<?php echo $v['Language']['locale']?>" name="data[MailTemplateI18n][<?php echo $k;?>][description]" value="" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
                            </tr>
                            <?php }}?>
                        <tr>
                            <th style="padding-top:15px;"><?php echo $ld['display']?></th>
                            <td><label class="am-radio am-success"><input type="radio" name="data[MailTemplate][status]" value="1" data-am-ucheck <?php if(empty($this->data['MailTemplate']['status'])||$this->data['MailTemplate']['status']){?>checked<?php }?> > <?php echo $ld['yes'];?></label>
                                <label style="margin-left:10px;" class="am-radio am-success"><input name="data[MailTemplate][status]" type="radio" value="0" data-am-ucheck <?php if(!empty($this->data['MailTemplate']['status'])&&$this->data['MailTemplate']['status']=="0"){?>checked<?php }?> > <?php echo $ld['no']?></label></td>
                        </tr>
                    </table>
                    <div class="btnouter">
                        <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                    </div>
                </div>
            </div>
        </div>
        <div id="plain_text" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title"><?php echo $ld['plain_text_message_content']?></h4>
            </div>
            <div id="plain_text_message_content" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <table class="am-table">
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                            <tr>
                                <td><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span><textarea style="width:650px;" cols="80" id="product_description_id<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][text_body]" rows="10"></textarea></td>
                            </tr>
                            <?php }}?>
                    </table>
                    <div class="btnouter">
                        <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="return cha2()" value="<?php echo $ld['d_submit'];?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                    </div>
                </div>
            </div>
        </div>
        <div id="html_email" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title"><?php echo $ld['html_email_content']?></h4>
            </div>
            <div id="html_email_content" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <table class="am-table">
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                            <tr><td><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span><textarea style="width:650px;" cols="80" id="email_list_id<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][html_body]" rows="10"></textarea>
                                    <?php echo $ckeditor->load("email_list_id".$v['Language']['locale']); ?></td>
                            </tr>
                            <?php }}?>
                    </table>
                    <div class="btnouter">
                        <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="return cha2()" value="<?php echo $ld['d_submit'];?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                    </div>
                </div>
            </div>
        </div>
        <div id="sms" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title"><?php echo $ld['sms_content']?></h4>
            </div>
            <div id="sms_content" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <table class="am-table">
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                            <tr>
                                <td><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span><textarea style="width:650px;" cols="80" id="sms_id<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][sms_body]" rows="10"></textarea></td>
                            </tr>
                            <?php }}?>
                    </table>
                    <div class="btnouter">
                        <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="return cha2()" value="<?php echo $ld['d_submit'];?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo $form->end();?>