<style>
    .am-radio, .am-checkbox{display:inline;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<?php echo $form->create('Profile',array('action'=>'/view/','name'=>"ProfileForm","type"=>"POST","onsubmit"=>"return checkfrom();"));?>
<input type="hidden" id="data[CategoryType][id]"  name="data[Profile][id]" value="<?php echo isset($profile_data['Profile']['id'])?$profile_data['Profile']['id']:0 ?>" />
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3  am-detail-menu" style="margin:10px 0 0 0">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#export"><?php echo $ld['export_configuration'];?></a></li>
        <?php if(!empty($profile_data['Profile'])){ ?>
            <li><a href="#field"><?php echo $ld['field_list'];?></a></li>
        <?php }?>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9  am-detail-view" id="accordion"  >
    <div id="export" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['export_configuration']?>
            </h4>
        </div>
        <div id="export_configuration" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tbody>
                    <tr>
                        <th style="padding-top:15px;" rowspan="2"><?php echo $ld['classification'];?></th>
                    </tr>
                    <tr>
                        <td>
                            <input style="width:200px;float:left;" type="text" id="group" name="data[Profile][group]" value="<?php echo empty($profile_data['Profile']['group'])?'':$profile_data['Profile']['group']?>"><em>*</em>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;" rowspan="2"><?php echo $ld['code'];?></th>
                    </tr>
                    <td>
                        <input style="width:200px;float:left;" type="text" id="code" name="data[Profile][code]" value="<?php echo empty($profile_data['Profile']['code'])?'':$profile_data['Profile']['code']?>"><em>*</em>
                    </td>
                    </tr>
                    <tr>
                        <th style="padding-top:17px;" rowspan="<?php echo isset($backend_locales)?count($backend_locales)+1:1;?>"><?php echo $ld['name'];?></th>
                    </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><input type="hidden" name="data[ProfileI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'] ?>"><input style="width:200px;float:left;" type="text" id="profile_name<?php echo $v['Language']['locale'];?>" name="data[ProfileI18n][<?php echo $k;?>][name]" value="<?php echo isset($profile_data['ProfileI18n'][$v['Language']['locale']]['name'])?$profile_data['ProfileI18n'][$v['Language']['locale']]['name']:'';?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
                        </tr>
                    <?php }}?>
                    <tr>
                        <th  style="padding-top:15px;" rowspan="<?php echo isset($backend_locales)?count($backend_locales)+1:1;?>"><?php echo $ld['description'];?></th>
                    </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><textarea style="width:400px;float:left;" id="profile_description<?php echo $v['Language']['locale'];?>" name="data[ProfileI18n][<?php echo $k;?>][description]"><?php echo isset($profile_data['ProfileI18n'][$v['Language']['locale']]['description'])?$profile_data['ProfileI18n'][$v['Language']['locale']]['description']:'';?></textarea><?php if(sizeof($backend_locales)>1){?><span class="lang" style="margin-top:12px;"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
                        </tr>
                    <?php }}?>
                    <tr>
                        <th style="padding-top:15px;" rowspan="2"><?php echo $ld['sort'];?></th>
                    </tr>
                    <tr>
                        <td>
                            <input style="width:200px;" type="text" id="name" name="data[Profile][orderby]" value="<?php echo empty($profile_data['Profile']['orderby'])?'50':$profile_data['Profile']['orderby']?>">
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:14px"><?php echo $ld['status'];?></th>
                        <td>
                            <label class="am-radio am-success" style="padding-top:2px;"><input type="radio" value="1" data-am-ucheck name="data[Profile][status]" checked/><?php echo $ld['yes'];?></label>
                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[Profile][status]" value="0" data-am-ucheck <?php if(isset($profile_data['Profile'])&&$profile_data['Profile']['status'] == 0){ echo "checked"; } ?>/><?php echo $ld['no'];?></label>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="btnouter">
                    <input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['submit'];?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['reset'];?>" />
                </div>
            </div>
        </div>
    </div>
    <?php if(!empty($profile_data['Profile'])){ ?>
        <div id="field" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title">
                    <?php echo $ld['field_list']?>
                </h4>
            </div>
            <div id="field_list" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <p class="am-u-md-12">
                        <?php if(isset($profilefiled) && sizeof($profilefiled)>=0){echo $html->link($ld['add'],'/profile_fileds/view/'.$id,array("class"=>"am-btn am-btn-warning am-btn-sm am-fr"));}else{echo "<div><br></div>";}?>
                    </p>
                    <table class="am-table">
                        <thead>
                        <tr>
                            <th class="thcode am-hide-sm-only" ><?php echo $ld['number'];?></th>
                            <th style="width:100px;"><?php echo $ld['code'];?></th>
                            <th><?php echo $ld['name'];?></th>
                            <th class="am-hide-sm-only" style="width:200px;"><?php echo $ld['prod_type_format'];?></th>
                            <th class="thicon am-hide-sm-only"><?php echo $ld['status'];?></th>
                            <th class="thsort am-hide-sm-only"><?php echo $ld['sort'];?></th>
                            <th style="width:150px;"><?php echo $ld['operate'];?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($profilefiled) && sizeof($profilefiled)>0){foreach($profilefiled as $t){?>
                            <tr>
                                <td><?php
                                    echo  $t['ProfileFiled']['id'];?>
                                </td>
                                <td>
                                    <?php
                                    echo $t['ProfileFiled']['code'];  ?></td>
                                <td class="am-hide-sm-only">
                                    <?php
                                    echo $t['ProfilesFieldI18n']['name'];  ?></td>
                                <td class="am-hide-sm-only">
                                    <?php
                                    echo $t['ProfileFiled']['format'];  ?></td>
                                <td class="am-hide-sm-only">
                                    <?php
                                    if($t['ProfileFiled']['status']=="0" ){
                                        echo '<div style="color:#dd514c" class="am-icon-close"></div>';
                                    }else{
                                        echo '<div style="color:#5eb95e" class="am-icon-check"></div>';
                                    }?>
                                </td>
                                <td class="am-hide-sm-only"><?php
                                    echo  $t['ProfileFiled']['orderby'];?>
                                </td>

                                <td>
                                    <?php
                                    if($svshow->operator_privilege("profiles_seeall_update")){
                                        echo $html->link($ld['edit'],"/profile_fileds/view/".$id."/".$t['ProfileFiled']['id'],array("class"=>"am-btn am-btn-default am-btn-xs am-radius")).'&nbsp;&nbsp;';
                                        echo $html->link($ld['remove'],"javascript:;",array("class"=>"am-btn am-btn-default am-text-danger am-btn-xs am-radius","onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}/profile_fileds/remove/".$id."/{$t['ProfileFiled']['id']}';}"));
                                    }

                                    ?>
                                </td>
                            </tr>
                        <?php }}else{ ?>
                            <tr>
                                <td colspan="7" class="no_data_found"><?php echo $ld['no_data_found']?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                    <div id="btnouterlist" class="btnouterlist">
                        <?php if(isset($profilefiled) && sizeof($profilefiled)>0){ echo $this->element('pagers');}?>
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    var formflag=true;
    function checkfrom(){
        if(formflag){
            formflag=false;
            return true;
        }
        return false;
    }
</script>