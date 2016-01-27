<style>
    .am-radio, .am-checkbox{display:inline;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<?php echo $form->create('OpenMenu',array('action'=>'/view/'.$id,'name'=>"OpenMenuForm",'id'=>"OpenMenuForm",'onsubmit' =>'return check_page_style()'));?>
<input id="ide" type="hidden" value="<?php echo $id;?>" name="data[OpenMenu][id]"/>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  >
    <div id="basic_info" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['previous_menu'] ?></th>
                        <td>
                            <select style="max-width:200px;float:left;" onchange="changes()" id="par" name="data[OpenMenu][parent_id]">
                                <?php if($parent_count<3){?><option value="0"><?php echo $ld['top_menu'] ?></option><?php }?>
                                <?php if(isset($parentmenu) && sizeof($parentmenu)>0){foreach($parentmenu as $k=>$v){?>
                                    <option value="<?php echo $v['OpenMenu']['id']?>" <?php if(isset($this->data['OpenMenu']['parent_id'])){if($this->data['OpenMenu']['parent_id'] == $v['OpenMenu']['id']){?>selected<?php }}?>><?php echo $v['OpenMenu']['name']?></option><?php }}?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['menu_name'] ?></th>
                        <td>
                            <input id="mu" style="max-width:200px;float:left;" type="text" name="data[OpenMenu][name]" value="<?php echo isset($this->data['OpenMenu']['name'])?$this->data['OpenMenu']['name']:""?>"/>
                            <em>*</em><span id="sptd" style="font-size:12px;"></span></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['menu'].$ld['type'] ?></th>
                        <td>
                            <select style="max-width:200px;float:left;" onchange="change()" id="seltype" class="border" name="data[OpenMenu][type]" value="<?php if(isset($this->data['OpenMenu']['type'])){echo $this->data['OpenMenu']['type'];} ?>">
                                <option value="view" <?php if($this->data['OpenMenu']['type']=="view"){echo "selected";}?>><?php echo $ld['external_chain'] ?></option>
                                <option value="click" <?php if($this->data['OpenMenu']['type']=="click"){echo "selected";}?>><?php echo $ld['keyword'] ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr id="u1" style="<?php if($this->data['OpenMenu']['type']!=0){echo 'display:none;';}?>">
                        <th style="padding-top:15px;" id="u2"><?php echo $ld['page'] ?> URL</th>
                        <td id="u3">
                            <input style="max-width:400px;float:left;" id="url" type="text" name="data[OpenMenu][url]" value="<?php echo isset($this->data['OpenMenu']['url'])?$this->data['OpenMenu']['url']:""?>"/>
                            <em>*</em><span style="font-size:12px;position:relative;top:5px;">(<?php echo $ld['page_url_empty'] ?>)</span>
                        </td>
                    </tr>
                    <tr id="k1" style="<?php if($this->data['OpenMenu']['type']!=1){echo 'display:none;';}?>">
                        <th>关键词</th>
                        <td >
                            <input style="max-width:400px;float:left;" id="keyword" style="width:400px;" type="text" name="data[OpenMenu][key]" value="<?php echo isset($this->data['OpenMenu']['key'])?$this->data['OpenMenu']['key']:""?>"/>
                            <em>*</em>
                        </td>
                    </tr>
                    <tr id="k2" style="<?php if($this->data['OpenMenu']['type']!=1){echo 'display:none;';}?>">
                        <th></th>
                        <td style="font-size:12px;">
                            如：这里填写内容为“天气@深圳”，表示用户点击自定义菜单时，<br/>
                            效果等同于用户往公众号输入了“天气@深圳”，用户不用做任何输入。<br/>
                            输入相关关键词，这里同样可以触发微官网、会员卡、活动等业务。
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['status'] ?></th>
                        <td>
                            <label class="am-radio am-success" style="padding-top:2px;"><input type="radio" name="data[OpenMenu][status]" value="1" data-am-ucheck <?php echo (isset($this->data['OpenMenu']['status'])&&$this->data['OpenMenu']['status']=='1')||(!isset($this->data['OpenMenu']['status']))?'checked':'' ?> /><?php echo $ld['valid'];?></label>
                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[OpenMenu][status]" value="0" data-am-ucheck <?php echo isset($this->data['OpenMenu']['status'])&&$this->data['OpenMenu']['status']=='0'?'checked':'' ?> /><?php echo $ld['invalid'];?></label>
                        </td>
                    </tr>
                </table>
                <div class="btnouter">
                    <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" onclick="check_page_style()"/> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<script>
    var flag=false;
    change();
    function check_page_style(){
        var parent=document.getElementById("par").value;
        var name=document.getElementById("mu").value;
        var sel=document.getElementById("seltype").value;
        var u=document.getElementById("url").value;
        var k=document.getElementById("keyword").value;
        if(name==""){
            alert("菜单不能为空");
            flag=false;
        }else{
            if(sel=="view"){
                if(u==""){
                    alert("链接不能为空");
                    flag=false;
                }else{
                    if(IsURL(u)){
                        flag=true;
                    }else{
                        alert("请输入正确的链接地址格式");
                        flag=false;
                    }
                }
            }else{
                if(k==""){
                    alert("请输入关键词");
                    flag=false;
                }else{
                    flag=true;
                }
            }
            if(parent=="0"){
                if(len(name)>8){
                    alert("一级菜单可输入8个字符");
                    flag=false;
                }
            }else{
                if(len(name)>14){
                    alert("二级菜单可输入14个字符");
                    flag=false;
                }
            }
        }
        if(flag){
            document.getElementById("OpenMenuForm").submit();
        }
    }

    function len(s) {
        var l = 0;
        var a = s.split("");
        for (var i=0;i<a.length;i++) {
            if (a[i].charCodeAt(0)<299) {
                l++;
            }else{
                l+=2;
            }
        }
        return l;
    }

    function change(){
        var sel=document.getElementById("seltype").value;
        if(sel=="view"){
            document.getElementById("k1").style.display="none";
            document.getElementById("k2").style.display="none";
            document.getElementById("u1").style.display="";
        }else{
            document.getElementById("u1").style.display="none";
            document.getElementById("k1").style.display="";
            document.getElementById("k2").style.display="";
        }
    }

    //网址的正则
    function IsURL(str_url){
        var strRegex = "^((https|http)?://)"
            + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@
            + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184
            + "|" // 允许IP和DOMAIN（域名）
            + "([0-9a-z_!~*'()-]+\.)*" // 域名- www.
            + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名
            + "[a-z]{2,6})" // first level domain- .com or .museum
            + "(:[0-9]{1,4})?" // 端口- :80
            + "((/?)|" // a slash isn't required if there is no file name
            + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";
        var re=new RegExp(strRegex);
        //re.test()
        if (re.test(str_url)){
            return (true);
        }else{
            return (false);
        }
    }

    function changes(){
        var h=document.getElementById("par").value;
        var x=document.getElementById("sptd");
        if(h==0){
            x.innerHTML="一级菜单可输入8个字符！";
        }else{
            x.innerHTML="二级菜单可输入14个字符！";
        }
    }
</script>