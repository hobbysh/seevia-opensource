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
<?php echo $form->create('email_lists',array('action'=>'/edit/'.$this->data['MailTemplate']['id'],"name"=>"email_lists",'onsubmit'=>'return mailtemplates_check();'));?>
<?php if(isset($backend_locales) && sizeof($backend_locales)>0){
    foreach ($backend_locales as $k => $v){?>
        <input id="MailTemplateI18n<?php echo $k;?>Locale" name="data[MailTemplateI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo  $v['Language']['locale'];?>">
        <?php if(isset($this->data['MailTemplateI18n'][$v['Language']['locale']])){?>
            <input id="MailTemplateI18n<?php echo $k;?>Id" name="data[MailTemplateI18n][<?php echo $k;?>][id]" type="hidden" value="<?php echo  $this->data['MailTemplateI18n'][$v['Language']['locale']]['id'];?>">
        <?php }?>
        <input id="MailTemplateI18n<?php echo $k;?>MailTemplateId" name="data[MailTemplateI18n][<?php echo $k;?>][mail_template_id]" type="hidden" value="<?php echo  $this->data['MailTemplate']['id'];?>">
        <?php }}?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
        <li><a href="#plain_text"><?php echo $ld['plain_text_message_content']?></a></li>
        <li><a href="#html_email"><?php echo $ld['html_email_content']?></a></li>
        <li><a href="#sms"><?php echo $ld['sms_content']?></a></li>
        <li><a href="#send"><?php echo $ld['send_and_test']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  >
    <div id="basic_info" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <input id="mailid" type="hidden" name="data[MailTemplate][id]" value="<?php echo $this->data['MailTemplate']['id'];?>" />
                <table class="am-table">
                    <tr><th style="padding-top:15px;"><?php echo $ld['email_code']?></th>
                        <td><input style="width:200px;" type="text"  id="data_mailtemplate_code" name="data[MailTemplate][code]" value="<?php echo $this->data['MailTemplate']['code'];?>" /> </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['subject']?></th>
                    </tr>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><input style="width:200px;float:left;" type="text" id="title<?php echo $v['Language']['locale']?>" name="data[MailTemplateI18n][<?php echo $k;?>][title]" value="<?php echo @$this->data['MailTemplateI18n'][$k]['title'];?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
                        </tr>
                        <?php }}?>
                    <tr>
                        <th style="padding-top:15px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['email_help']?></th>
                    </tr>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><input style="width:200px;float:left;" type="text"  id="title<?php echo $v['Language']['locale']?>" name="data[MailTemplateI18n][<?php echo $k;?>][description]" value="<?php echo @$this->data['MailTemplateI18n'][$k]['description'];?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
                        </tr>
                        <?php }}?>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['display']?></th>
                        <td><label class="am-radio am-success"><input type="radio" name="data[MailTemplate][status]" style="margin-left:0px;" data-am-ucheck value="1" <?php if($this->data['MailTemplate']['status']){?>checked<?php }?> ><?php echo $ld['yes'];?></label>
                            <label style="margin-left:10px;" class="am-radio am-success"><input name="data[MailTemplate][status]" type="radio" value="0" data-am-ucheck <?php if($this->data['MailTemplate']['status']==0){?>checked<?php }?> ><?php echo $ld['no']?></label>
                        </td>
                    </tr>
                </table>
                <div class="btnouter"style="margin-bottom:25px;">
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
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){
                        if($configs["show_edit_type"]){?>
                            <tr> 
                                <td><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span><textarea cols="80" id="email_list_text_id<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][text_body]" rows="10" style="width:auto;height:300px;"><?php echo isset($this->data['MailTemplateI18n'][$k]['text_body'])?$this->data['MailTemplateI18n'][$k]['text_body']:"";?></textarea>
                                    <script>
                                        var editor;
                                        KindEditor.ready(function(K) {
                                            editor = K.create('#email_list_text_id<?php echo $v['Language']['locale'];?>', {
                                            	width:'80%',
                                                langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
                                        });
                                    </script>
                                </td>
                            </tr>
                        <?php }else{?>
                            <tr>
                                <td><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span><textarea cols="80" id="email_list_text_id<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][text_body]" rows="10"><?php echo isset($this->data['MailTemplateI18n'][$k]['text_body'])?$this->data['MailTemplateI18n'][$k]['text_body']:"";?></textarea>
                            </tr>
                        <?php }?>
                        <?php }}?>
                </table>
                <div class="btnouter" style="margin-bottom:25px;">
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
                        <tr>
                            <td><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span><textarea cols="80" id="email_list_id<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][html_body]" rows="20" style="width:auto;"><?php echo $this->data['MailTemplateI18n'][$k]['html_body'];?></textarea>
                                <script>
                                    var editor;
                                    KindEditor.ready(function(K) {
                                        editor = K.create('#email_list_id<?php echo $v['Language']['locale'];?>', {width:'80%',
                                                items:['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy',
                                                    'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter','justifyright', 'justifyfull',
                                                    'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat',
                                                    'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                                                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage','table',
                                                    'hr', 'emoticons', 'baidumap', 'pagebreak','link', 'unlink', '|', 'about'],
                                                langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false
                                            }
                                        );
                                    });
                                </script>
                            </td>
                        </tr>
                        <?php }}?>
                </table>
                <div class="btnouter" style="margin-bottom:25px;">
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
                            <td><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span><textarea cols="80" id="sms_list_id<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][sms_body]" rows="20" style="width:auto;"><?php echo $this->data['MailTemplateI18n'][$k]['sms_body'];?></textarea>
                                <script>
                                    var editor;
                                    KindEditor.ready(function(K) {
                                        editor = K.create('#sms_list_id<?php echo $v['Language']['locale'];?>', {	width:'80%',
                                                items:['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy',
                                                    'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter','justifyright', 'justifyfull',
                                                    'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat',
                                                    'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                                                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage','table',
                                                    'hr', 'emoticons', 'baidumap', 'pagebreak','link', 'unlink', '|', 'about'],
                                                langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false
                                            }
                                        );
                                    });
                                </script>
                            </td>
                        </tr>
                        <?php }}?>
                </table>
                <div class="btnouter" style="margin-bottom:25px;">
                    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="return cha2()" value="<?php echo $ld['d_submit'];?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
    <div id="send" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['send_and_test']?></h4>
        </div>
        <div id="send_and_test" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['email']?></th>
                        <td>
                            <input style="width:200px;float:left;margin-right:5px;" type="text" id="email" name="email">  <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" onclick="test_allemail()" value="<?php echo $ld['send_test_email']?>"  name="saveedit" />
                            (例:zhangsan@seevia.cn)
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:18px"><?php echo $ld['mobile']?></th>
                        <td>
                            <input style="width:200px;float:left;margin-right:5px;" type="text" id="mobile" name="mobile">  <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" onclick="checksms()" value="<?php echo $ld['test']?>"  name="saveedit" />
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:18px"><?php echo $ld['send_object']?></th>
                        <td >
                            <input id="toppri" value="0" type="hidden" name="data[MailTemplate][toppri]">
                            <select style="width:200px;float:left;margin-right:5px;" id="usermode" name="data[MailTemplate][usermode]" onchange="check_user(this)">
                                <option value="user_email_flag"><?php echo $ld['subscriber']?></option>
                                <option value="newsletter_user"><?php echo $ld['magazine_subscribers']?></option>
                                <option value="user_all"><?php echo $ld['all_users']?></option>
                            </select>
                            <select style="width:200px;float:left;margin-right:5px;display:none;" name="group_id" id="group_id">
                                <option value=""><?php echo $ld['please_select']?></option>
                                <?php if(isset($group_list) && sizeof($group_list)>0){foreach($group_list as $gk=>$gv){?>
                                    <option value="<?php echo $gk;?>" ><?php echo $gv;?></option>
                                <?php }}?>
                            </select>
                            <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['just_send_mail']?>" name="only_email" onclick="only_send_email()"> <span style="margin-left:10px;"><input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['just_send_sms']?>" onclick="only_send_sms()"><span style="margin-left:10px;"><input type="button" id="emlandmsg" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['send_email_and_sms']?>" onclick="send_emailandsms()">
                        </td>
                    </tr>
                </table>
                <div class="btnouter" style="margin-bottom:25px;">
                    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="return cha2()" value="<?php echo $ld['d_submit'];?>"  name="saveedit" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<style>
@media screen and (max-width: 881px){
#emlandmsg{
    margin-top:10px;
    margin-left:-10px;
}
} 

</style>
<script>
    function check_user(e){
        if(e.value=="newsletter_user"){
            document.getElementById("group_id").style.display="inline";
        }else{
            document.getElementById("group_id").style.display="none";
        }
    }

    function checkemail(){
        var email = document.getElementById("email").value;
        var mailid = document.getElementById("mailid").value;
        if(email==""){
            alert('E-mail不能为空');
        }else{
            document.email_lists.action=admin_webroot+"email_lists/send_email_test/"+email+"/"+mailid+"/";
            document.email_lists.onsubmit= "";
            document.email_lists.submit();
        }
    }

    function checksms(){
        var sms = document.getElementById("mobile").value;
        var mailid = document.getElementById("mailid").value;
        if(sms==""){
            alert('手机号不能为空');
        }else{
            document.email_lists.action=admin_webroot+"email_lists/send_sms_test/"+sms+"/"+mailid+"/";
            document.email_lists.onsubmit= "";
            document.email_lists.submit();
        }
    }

    function only_send_email(){
        if(confirm(confirm_exports+""+"仅发送邮件"+"？")){
            document.email_lists.action=admin_webroot+"email_lists/insert_email_queue/only_send_email";
            document.email_lists.onsubmit= "";
            document.email_lists.submit();
        }
    }

    function only_send_sms(){
        if(confirm(confirm_exports+""+"仅发送短信"+"？")){
            document.email_lists.action=admin_webroot+"email_lists/insert_email_queue/only_send_sms";
            document.email_lists.onsubmit= "";
            document.email_lists.submit();
        }
    }

    function send_emailandsms(){
        if(confirm(confirm_exports+""+"发送邮件及短信"+"？")){
            document.email_lists.action=admin_webroot+"email_lists/insert_email_queue/send_emailandsms";
            document.email_lists.onsubmit= "";
            document.email_lists.submit();
        }
    }

    //发送测试邮件
    function test_allemail(){
        var receiver_emails = document.getElementById('email');
        var email=receiver_emails.value+";"+receiver_emails.value;
        var sUrl = admin_webroot+"configvalues/test_allemail";
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {receiver_emails: email},
            success: function (json) {
                alert("<?php echo $ld['congratulations_message_successfully_sent']?> "+document.getElementById('email').value);
            }
        });
    }

    //发送订阅邮件通知
    function mail_notice(){
        var email_id = document.getElementById('mailid').value;
        var sUrl = admin_webroot+"email_lists/test_mail_notice";
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {email_id: email_id},
            success: function (json) {
                alert("<?php echo '订阅邮件已发送';?> ");
            }
        });
    }
</script>