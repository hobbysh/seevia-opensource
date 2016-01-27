<style>
	.am-radio, .am-checkbox{display:inline;}
	 em{color:red;}
	.am-checkbox {margin-top:0px; margin-bottom:0px;}
	label{font-weight:normal;}
	.am-form-horizontal .am-radio{padding-top:0;}
	.am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<?php echo $form->create('ProfileFiled',array('action'=>'/view/'.$id.'/'.$uid,'name'=>"ProfileFiledForm","type"=>"POST",'onsubmit'=>'return check();'));?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3  am-detail-menu" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#tablemain"><?php echo $ld['export_configuration'];?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9  am-detail-view" id="accordion" >
    <div id="tablemain" class="am-panel am-panel-default">
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
                        <th style="padding-top:15px;" rowspan="2"><?php echo $ld['code'];?></th>
                    </tr>
                    <tr>
                        <td>
                            <input style="width:200px;float:left;" type="text" id="name" name="data[ProfileFiled][code]" value="<?php echo empty($profilefiled_data['ProfileFiled']['code'])?'':$profilefiled_data['ProfileFiled']['code']?>" /> <em>*</em>
                            <input type="hidden" id="id" name="data[ProfileFiled][profile_id]" value="<?php echo empty($profilefiled_data['ProfileFiled']['profile_id'])?$id:$profilefiled_data['ProfileFiled']['profile_id']?>">
                            <input type="hidden" id="id" name="data[ProfileFiled][id]" value="<?php echo empty($profilefiled_data['ProfileFiled']['id'])?'':$profilefiled_data['ProfileFiled']['id']?>">
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;" rowspan="<?php echo isset($backend_locales)?count($backend_locales)+1:1;?>"><?php echo $ld['name'];?></th>
                    </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td><input type="hidden"  name="data[ProfilesFieldI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'] ?>"><input style="width:200px;float:left;" type="text" id="profilefiled_name<?php echo $v['Language']['locale'];?>" name="data[ProfilesFieldI18n][<?php echo $k;?>][name]" value="<?php echo isset($profilefiled_data['ProfilesFieldI18n'][$v['Language']['locale']]['name'])?$profilefiled_data['ProfilesFieldI18n'][$v['Language']['locale']]['name']:'';?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
                    </tr>
                    <?php }}?>
                    <tr>
                        <th style="padding-top:15px;" rowspan="<?php echo isset($backend_locales)?count($backend_locales)+1:1;?>"><?php echo $ld['description'];?></th>
                    </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td><textarea style="width:400px;float:left;" id="profilefiled_description<?php echo $v['Language']['locale'];?>" name="data[ProfilesFieldI18n][<?php echo $k;?>][description]"><?php echo isset($profilefiled_data['ProfilesFieldI18n'][$v['Language']['locale']]['description'])?$profilefiled_data['ProfilesFieldI18n'][$v['Language']['locale']]['description']:'';?></textarea><?php if(sizeof($backend_locales)>1){?><div style="margin-top:10px;"><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></div></td>
                    </tr>
                    <?php }}?>
                    <tr>
                        <th style="padding-top:15px;" rowspan="2"><?php echo $ld['prod_type_format'];?></th>
                    </tr>
                    <tr>
                        <td>
                            <input style="width:200px;float:left;" type="text" id="name" name="data[ProfileFiled][format]" value="<?php echo empty($profilefiled_data['ProfileFiled']['format'])?'':$profilefiled_data['ProfileFiled']['format']?>"> <em>*</em>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;" rowspan="2"><?php echo $ld['sort'];?></th>
                    </tr>
                    <tr>
                        <td>
                            <input style="width:200px;float:left;" type="text" id="name" name="data[ProfileFiled][orderby]" value="<?php echo empty($profilefiled_data['ProfileFiled']['orderby'])?'50':$profilefiled_data['ProfileFiled']['orderby']?>"> <em>*</em>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:9px"><?php echo $ld['status'];?></th>
                        <td>
                            <label class="am-radio am-success" style="padding-top:2px;"><input type="radio" value="1" data-am-ucheck name="data[ProfileFiled][status]" checked/><?php echo $ld['yes'];?></label>
                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[ProfileFiled][status]" value="0" data-am-ucheck <?php if(isset($profilefiled_data['ProfileFiled'])&&$profilefiled_data['ProfileFiled']['status'] == 0){ echo "checked"; } ?>/><?php echo $ld['no'];?></label>
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
</div>
<?php echo $form->end();?>