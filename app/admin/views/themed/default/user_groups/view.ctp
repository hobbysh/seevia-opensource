<?php
/*****************************************************************************
 * SV-Cart  添加会员等级
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 *****************************************************************************/
?>
<style>
    .am-radio, .am-checkbox{display:inline;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<?php echo $form->create('UserGroup',array('action'=>'/view/'.$id,'name'=>"SeearchForm",'id'=>"SearchForm","type"=>"post",'onsubmit'=>'return formsubmit();'));?>
<input type="hidden" name="data[UserGroup][id]" value="<?php echo $id; ?>" />
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#tablemain"><?php echo $ld['user_group']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="tablemain" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['user_group']?></h4>
        </div>
        <div id="user_group" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['name']?>:</th>
                        <td >
                            <lable><input style="width:200px;float:left;" id="group_name" type="text" name="data[UserGroup][name]" value="<?php echo isset($UserGroup['UserGroup']['name'])?$UserGroup['UserGroup']['name']:''; ?>" /></lable><em><font color='red'>*</font></em>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['description']?>:</th>
                        <td>
                            <textarea style="width:200px;" type="text" name="data[UserGroup][description]" ><?php echo isset($UserGroup['UserGroup'])?$UserGroup['UserGroup']['description']:'';?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['status']?></th>
                        <td><label class="am-radio am-success" style="padding-top:2px;"><input type="radio" name="data[UserGroup][status]" value="1" data-am-ucheck <?php echo (isset($UserGroup['UserGroup']['status'])&&$UserGroup['UserGroup']['status']=='1')||(!isset($UserGroup['UserGroup']['status']))?'checked':''; ?>/><?php echo $ld['yes']?></label>
                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[UserGroup][status]" value="0" data-am-ucheck <?php echo (isset($UserGroup['UserGroup']['status'])&&$UserGroup['UserGroup']['status']=='0')?'checked':''; ?> /><?php echo $ld['no']?></label>
                        </td>
                    </tr>
                </table>
                <div class="btnouter">
                    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['submit']?>" /> <input class="am-btn am-btn-success am-btn-sm" type="reset" value="<?php echo $ld['reset']?>" />
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo $form->end();
?>
<script type="text/javascript">
    function formsubmit(){
        var name=document.getElementById("group_name");
        if(!name.value.length>0){
            alert('请输入名称！');
            return false;
        }
        return true;
    }

    //只能输入数字
    function check_number(e){
        if((e.keyCode>=48&&e.keyCode<=57)||(e.keyCode>=96&&e.keyCode<=105)||e.keyCode==8){
            return true;
        }else{
            return false;
        }
    }

    //金额输入
    function check_balance(e){
        if((e.keyCode>=48&&e.keyCode<=57)||(e.keyCode>=96&&e.keyCode<=105)||e.keyCode==8||e.keyCode==110||e.keyCode==190){
            return true;
        }else{
            return false;
        }
    }
</script>