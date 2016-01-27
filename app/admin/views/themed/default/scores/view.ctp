<?php echo $form->create('Score',array('action'=>'view/'.(isset($this->data['Score']['id'])?$this->data['Score']['id']:0),'onsubmit'=>'return check_all()'));?>
<input type="hidden" name="data[Score][id]" value="<?php echo isset($this->data['Score']['id'])?$this->data['Score']['id']:0;?>">
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#tablemain"><?php echo $ld['basic_information']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="tablemain" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th style="padding-top:15px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['name'] ?></th>
                    </tr>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k=>$v){?>
                        <tr>
                            <td><input style="width:200px;float:left;" type="text" id="score_name_<?php echo $v['Language']['locale'];?>"  maxlength="50" name="data[ScoreI18n][<?php echo $v['Language']['locale'];?>][name]" value="<?php echo isset($this->data['ScoreI18n'][$v['Language']['locale']])?$this->data['ScoreI18n'][$v['Language']['locale']]['name']:'';?>" />
                                <?php if(sizeof($backend_locales)>1){?>
                                    <span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em style="color:red;">*</em>
                            </td>
                        </tr>
                        <?php }}?>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['type'] ?></th>
                        <td>
                            <select id="score_type" name="data[Score][type]" data-am-selected="{noSelectedText:''}">
                                <option value=""><?php echo $ld['please_select']?></option>
                                <?php if(isset($score_type_list)&&sizeof($score_type_list)>0){foreach($score_type_list as $k=>$v){ ?>
                                    <option value="<?php echo $k; ?>" <?php if(@$this->data['Score']['type']==$k){echo "selected";}?> ><?php echo $v; ?></option>
                                <?php }} ?>
                            </select><em style="color:red;top:5px;">*</em>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['option_list']?></th>
                    </tr>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td>
                                <textarea style="width:200px;float:left;" id="score_value_<?php echo $v['Language']['locale'];?>"  name="data[ScoreI18n][<?php echo $v['Language']['locale'];?>][value]"><?php echo isset($this->data['ScoreI18n'][$v['Language']['locale']])?$this->data['ScoreI18n'][$v['Language']['locale']]['value']:'';?></textarea>
                                <?php if(sizeof($backend_locales)>1){?><span class="lang" style="top:15px"><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em style="color:red;top:15px;">*</em>
                            </td>
                        </tr>
                        <?php }}?>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['status'] ?></th>
                        <td><label class="am-radio-inline"><input type="radio" name="data[Score][status]" value="1" <?php echo !isset($this->data['Score']['status'])||(isset($this->data['Score']['status'])&&$this->data['Score']['status']==1)?"checked":""; ?> /><?php echo $ld['yes']?></label>
                            <label class="am-radio-inline"><input type="radio" name="data[Score][status]" value="0" <?php echo isset($this->data['Score']['status'])&&$this->data['Score']['status']==0?"checked":"";?> /><?php echo $ld['no']?></label>
                        </td>
                    </tr>
                </table>
                <div class="btnouter">
                    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" /> <input class="am-btn am-btn-success  am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    function check_all(){
        var score_name=document.getElementById("score_name_"+backend_locale).value;
        if(score_name==""){
            alert("<?php printf($ld['name_not_be_empty'],$ld['name']); ?>");
            return false;
        }
        var score_type=document.getElementById("score_type").value;
        if(score_type==""){
            alert("<?php echo $ld['please_select'].$ld['type']; ?>");
            return false;
        }
        var score_value=document.getElementById("score_value_"+backend_locale).value;
        if(score_value==""){
            alert("<?php printf($ld['name_not_be_empty'],$ld['option_list']); ?>");
            return false;
        }
        return true;
    }
</script>