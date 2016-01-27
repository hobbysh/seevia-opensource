<?php echo $form->create('enquiries',array('action'=>'/'.$Enquiry_list['Enquiry']['id']))?>
<input id="enquiry_id" type="hidden" name="data[Enquiry][id]" value="<?php echo $Enquiry_list['Enquiry']['id'];?>" />
<input type="hidden" name="data[Enquiry][user_id]" value="<?php echo $Enquiry_list['Enquiry']['user_id'];?>" />
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#details"><?php echo $ld['details_view']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  >
    <div id="details" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['details_view']?>
            </h4>
        </div>
        <div id="details_view" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th ><?php echo $ld['name'];?></th>
                        <td><?php echo isset($product_Info['ProductI18n']['name'])?$product_Info['ProductI18n']['name']:"";?></td>
                    </tr>
                    <tr>
                        <th style="padding-top:17px;"><?php echo $ld['attribute'];?></th>
                        <td><input style="width:200px;" type="text" name="data[Enquiry][attribute]" value="<?php echo $Enquiry_list['Enquiry']['attribute'];?>" readonly /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['price'];?></th>
                        <td><input style="width:200px;" type="text" name="data[Enquiry][target_price]" value="<?php echo $Enquiry_list['Enquiry']['target_price'];?>" readonly /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:17px;"><?php echo $ld['app_qty'];?></th>
                        <td><input style="width:200px;" type="text" name="data[Enquiry][qty]" value="<?php echo $Enquiry_list['Enquiry']['qty'];?>" readonly /></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['name_of_member'];?></th>
                        <td><?php echo isset($user_Info)?$user_Info['User']['name']:'' ?></td>
                    </tr>
                    <tr>
                        <th style="padding-top:16px;"><?php echo $ld['contacter'];?></th>
                        <td><input style="width:200px;" type="text" name="data[Enquiry][contact_person]" value="<?php echo $Enquiry_list['Enquiry']['contact_person'];?>" readonly /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['phone'];?></th>
                        <td><input style="width:200px;" type="text" name="data[Enquiry][tel1]" value="<?php echo $Enquiry_list['Enquiry']['tel1'];?>" readonly /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:14px;"><?php echo $ld['status'];?></th>
                        <td><select data-am-selected="{noSelectedText:''}" name="data[Enquiry][status]" id="status">
                                <option value="0" <?php if (isset($enquiry_status)&&$enquiry_status == 0){?>selected<?php }?>><?php echo $ld['unrecognized']?></option>
                                <option value="1" <?php if (isset($enquiry_status)&&$enquiry_status == 1){?>selected<?php }?>><?php echo $ld['confirmed']?></option>
                                <option value="2" <?php if (isset($enquiry_status)&&$enquiry_status == 2){?>selected<?php }?>><?php echo $ld['canceled']?></option>
                                <option value="3" <?php if (isset($enquiry_status)&&$enquiry_status == 3){?>selected<?php }?>><?php echo $ld['complete']?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:17px;"><?php echo $ld['email'];?></th>
                        <td><input style="width:200px;" type="text" name="data[Enquiry][email]" value="<?php echo $Enquiry_list['Enquiry']['email'];?>" readonly/></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['remarks_notes']?></th><td><textarea style="width:200px;" name="data[Enquiry][remark]"  id="data_mailtemplate_code" readonly><?php echo $Enquiry_list['Enquiry']['remark'];?></textarea></td>
                    </tr>
                </table>
                <div class="btnouter">
                    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['submit'];?>">
                    <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['offered_price'];?>" onclick="quote()">
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<script>
    function quote(){
        var enquiry_id=document.getElementById("enquiry_id").value;
        //询价id，传入报价中
        window.location.href=encodeURI(admin_webroot+"quotes/view?enquiry_id="+enquiry_id);
    }
</script>