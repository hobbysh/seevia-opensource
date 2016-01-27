<?php echo $form->create('NewsletterList',array('action'=>'view/'.(isset($cronjob_info['NewsletterList']['id'])?$cronjob_info['NewsletterList']['id']:''),'name'=>'userformedit'));?>
    <input id="id" type="hidden" name="data[NewsletterList][id]" value="<?php echo isset($cronjob_info['NewsletterList']['id'])?$cronjob_info['NewsletterList']['id']:'';?>">
    <div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
        <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
            <li><a href="#magazine"><?php echo $ld['magazine'].' '.$ld['magazine_user'] ?></a></li>
        </ul>
    </div>
    <div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
        <div id="magazine" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title">
                    <?php echo $ld['magazine'].' '.$ld['magazine_user'] ?>
                </h4>
            </div>
            <div id="magazine_user" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <table class="am-table">
                        <tbody>
                        <tr>
                            <th style="padding-top:15px;"><?php echo $ld['email']?>:</th>
                            <td><input style="width:200px;" type="text" id="email"  maxlength="60" name="data[NewsletterList][email]" value="<?php echo isset($cronjob_info)?$cronjob_info['NewsletterList']['email']:'';?>" /></td>
                        </tr>
                        <tr>
                            <th style="padding-top:15px;"><?php echo $ld['mobile'];?>:</th>
                            <td><input style="width:200px;" type="text" id="mobile"  maxlength="60" name="data[NewsletterList][mobile]" value="<?php echo isset($cronjob_info)?$cronjob_info['NewsletterList']['mobile']:'';?>" /></td>
                        </tr>
                        <tr>
                            <th style="padding-top:12px;"><?php echo $ld['user_group'];?>:</th>
                            <td>
                                <select data-am-selected="{noSelectedText:''}" name="data[NewsletterList][group_id]" id="group_id">
                                    <option value="" ><?php echo $ld['please_select']?></option>
                                    <?php if(isset($group_list) && sizeof($group_list)>0){foreach($group_list as $gk=>$gv){?>
                                        <option value="<?php echo $gk;?>" <?php if(isset($cronjob_info) && $cronjob_info['NewsletterList']['group_id']==$gk){echo "selected";}?> ><?php echo $gv;?></option>
                                    <?php }}?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo $ld['status']?>:</th>
                            <td><label style="padding-top:0px;" class="am-checkbox am-success"><input id="check" onClick="statuschange()"  value="<?php if(isset($cronjob_info['NewsletterList']['status'])){echo $cronjob_info['NewsletterList']['status'];}?>"  <?php if(isset($cronjob_info['NewsletterList']['status'])&&$cronjob_info['NewsletterList']['status']==1){echo "checked";}?>  type="checkbox" data-am-ucheck/><?php echo $ld['valid']?></label>
                                <input type="hidden" value="<?php if(isset($cronjob_info['NewsletterList']['status'])){echo $cronjob_info['NewsletterList']['status'];}?>" name="data[NewsletterList][status]" id="status" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="btnouter">
                        <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit'];?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function statuschange(){
            var check=document.getElementById("check");
            var status=document.getElementById("status");
            if(check.checked == true){
                status.value=1;
            }else{
                status.value=2;
            }
        }
    </script>
<?php echo $form->end();?>