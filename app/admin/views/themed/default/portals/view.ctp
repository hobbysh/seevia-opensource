<script src="/plugins/ajaxfileupload.js" type="text/javascript"></script>
<?php echo $form->create('Portal',array('action'=>'view/'.(isset($portalInfo['Portal']['id'])?$portalInfo['Portal']['id']:'0'),'name'=>'PortalForm','onsubmit'=>'return form_checks();'));?>
<?php if(isset($portalInfo['Portal']['id'])){ ?>
<input type="hidden" name="data[Portal][id]" value="<?php echo $portalInfo['Portal']['id']; ?>" />
<?php } ?>
    
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic"><?php echo $ld['basic_information'];?></a></li>
    </ul>
</div>
    
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="basic" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['basic_information']?>
            </h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
        				<th><?php echo $ld['title'];?></th>
        				<td>
        					<input type="text" style="width:200px;float:left;"  name="data[Portal][name]" id="Portalname" value="<?php echo isset($portalInfo['Portal']['name'])?$portalInfo['Portal']['name']:''; ?>" /><em>*</em>
        				</td>
       				</tr>
       				<tr>
        				<th><?php echo $ld['type'];?></th>
        				<td>
        					<select name="data[Portal][type]" data-am-selected>
    							<option value="iframe" <?php echo isset($portalInfo['Portal']['type'])&&$portalInfo['Portal']['type']=='iframe'?'selected':''; ?>>Iframe</option>
    							<option value="html" <?php echo isset($portalInfo['Portal']['type'])&&$portalInfo['Portal']['type']=='html'?'selected':''; ?>>html</option>
    						</select>
        				</td>
       				</tr>
       				<tr>
        				<th><?php echo $ld['order_web'];?></th>
        				<td>
        					<input type="text" style="width:50%;"  name="data[Portal][url]" value="<?php echo isset($portalInfo['Portal']['url'])?$portalInfo['Portal']['url']:''; ?>" />
        				</td>
       				</tr>
       				<tr>
        				<th><?php echo $ld['picture'];?></th>
        				<td>
        					<img src="<?php echo isset($portalInfo['Portal']['img'])?$portalInfo['Portal']['img']:''; ?>" style="margin-left:10px;<?php echo (isset($portalInfo['Portal']['img'])&&$portalInfo['Portal']['img']==''||!(isset($portalInfo['Portal'])))?'display:none;':''; ?>" />
        					<input type="file" name="PortalImg" id="PortalImg" onchange="ajaxFileUpload()" />
        					<input type="hidden" name="data[Portal][img]" id="PortalImgHid" value="<?php echo isset($portalInfo['Portal']['img'])?$portalInfo['Portal']['img']:''; ?>" />
        				</td>
       				</tr>
       				<tr>
        				<th><?php echo $ld['default'].$ld['spread'];?></th>
        				<td>
        					<label class="am-radio am-success"><input type="radio" data-am-ucheck  name="data[Portal][default_min]" value="0" <?php echo (isset($portalInfo['Portal']['default_min'])&&$portalInfo['Portal']['default_min']=='0')||(!isset($portalInfo['Portal']['default_min']))?'checked':''; ?> /><?php echo $ld['yes'];?></label>
        					<label class="am-radio am-success"><input type="radio" data-am-ucheck  name="data[Portal][default_min]" value="1" <?php echo isset($portalInfo['Portal']['default_min'])&&$portalInfo['Portal']['default_min']=='1'?'checked':''; ?> /><?php echo $ld['no'];?></label>
        				</td>
       				</tr>
       				<tr>
        				<th><?php echo $ld['default'].$ld['list'];?></th>
        				<td>
        					<select name="data[Portal][default_list]" data-am-selected>
    							<option value="list1" <?php echo isset($portalInfo['Portal']['default_list'])&&$portalInfo['Portal']['default_list']=='list1'?'selected':''; ?>><?php echo $ld['list']."1"; ?></option>
    							<option value="list2" <?php echo isset($portalInfo['Portal']['default_list'])&&$portalInfo['Portal']['default_list']=='list2'?'selected':''; ?>><?php echo $ld['list']."2"; ?></option>
    							<option value="list3" <?php echo isset($portalInfo['Portal']['default_list'])&&$portalInfo['Portal']['default_list']=='list3'?'selected':''; ?>><?php echo $ld['list']."3"; ?></option>
    						</select>
        				</td>
       				</tr>
       				<tr>
        				<th><?php echo $ld['status'];?></th>
        				<td>
        					<label class="am-radio am-success"><input type="radio" data-am-ucheck  name="data[Portal][status]" value="1" <?php echo (isset($portalInfo['Portal']['status'])&&$portalInfo['Portal']['status']=='1')||(!isset($portalInfo['Portal']['default_min']))?'checked':''; ?> /><?php echo $ld['valid'];?></label>
        					<label class="am-radio am-success"><input type="radio" data-am-ucheck  name="data[Portal][status]" value="0" <?php echo isset($portalInfo['Portal']['status'])&&$portalInfo['Portal']['status']=='0'?'checked':''; ?> /><?php echo $ld['invalid'];?></label>
        				</td>
       				</tr>
                </table>
            </div>
        </div>
        <div class="btnouter">
            <input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['submit'];?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['reset'];?>" onclick="resertinput()" />
        </div>
    </div>
</div>
<?php echo $form->end();?>
<style>
.ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    text-transform: capitalize;
    white-space: nowrap;
    width: 300px;
}
.am-radio, .am-checkbox{display:inline;}
.am-checkbox {margin-top:0px; margin-bottom:0px;}
label{font-weight:normal;}
.am-form-horizontal .am-radio{padding-top:0;position:relative;top:0px;}
.am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<script type='text/javascript'>
function trim(str){ //删除左右两端的空格
	return str.replace(/(^\s*)|(\s*$)/g, "");
}
function form_checks(){
	var Portalname=document.getElementById("Portalname").value;
	Portalname=trim(Portalname);
	if(Portalname==""||Portalname.length==0){
		alert('标题不能为空');
		return false;
	}else{
		return true;
	}
}
function ajaxFileUpload(){
	 $.ajaxFileUpload({
		  url:'/admin/portals/uploadimg/', //你处理上传文件的服务端
		  secureuri:false,
		  fileElementId:'PortalImg',
		  dataType: 'json',
		  success: function (result){
			if(result.code=='1'){
				$("#PortalImg").parent().find("img").attr("src",result.upload_img_url);
				$("#PortalImg").parent().find("img").css("display","block");
				$("#PortalImg").parent().find("input[type=hidden]").val(result.upload_img_url);
			}else{
				alert(result.msg);
			}
		  },
		  error: function (data, status, e)//服务器响应失败处理函数
		  {
		  	  alert('上传失败');
          }
	 });
	return false;
}
function resertinput(){
	document.getElementById("PortalImgHid").value="";
}
</script>