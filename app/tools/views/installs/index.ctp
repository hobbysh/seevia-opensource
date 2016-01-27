<link href="/tools/css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/tools/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="/tools/js/common.js"></script>
<script type="text/javascript" src="/tools/js/check.js"></script>
<script type="text/javascript" src="/tools/js/dialog.js"></script>
<script type="text/javascript" src="/tools/js/draggable.js"></script>
<script type="text/javascript" src="/tools/js/json2.js"></script>
<script type="text/javascript" src="/tools/js/setting_<?php echo $installer_lang ?>.js"></script>

<div id="seevia" style="width:660px;height:295px;border:1px solid #ccc;margin:200px auto 0;">
<div style="width:660px;height:30px;background:#E7F0FF;"></div>
<div style="width:660px;height:235px;background:#fff;text-align:left;">
<img src="/tools/img/seevia.png"  style="float:left;margin:55px 0 0 90px;"/>
<div style="float:left;margin:45px 0 0 43px;width:325px;">
	<div style="font-size:25px;font-weight:bold;margin:20px 0;">线上线下推广平台 <?php echo Version;?></div>
	<div id="check_system">
		<div style='margin-left:50px'>
			<input id="system" style="float:left;background:#ccc;border-color:#ccc;" class="button" type="button"  value="正在检测系统..." />
			<img id="ajax-loader" style="width:40px;height:40px;float:left;" src="/tools/img/new_loading.gif" />
		</div>
		<div id="install-btn" style="margin-left:50px;">
		<input type="button" class="button" id="js-recheck" style='background:#e0690c;border-color:#e0690c;color:#fff;' value="<?php echo $lang['recheck'];?>"  />
		<input type="submit" id="js-submit" value="" style="display:none;"/>
		</div>
		<a href='javascript:void(0);' id='error_info' onclick='ErrorInfo()'>>错误明细</a>
	</div>

	<div id="install_ing" style="display:none;">
		<div id="install-btn" style="margin-left:50px;">
		<input type="button" class="button" id="js-pre-step"  value="<?php echo $lang['prev_step'];?><?php echo $lang['welcome_page'];?>" style="display:none;"  />
		<input type="button" class="agree button "  value="立即安装" style="display:block;background:#5eb95e;border-color:#5eb95e;color:#fff;" />
		</div>
		<input type="submit" id="custom_install" value=">自定义安装" class="custom_install" />
	</div>
	<div id="js-monitor" style="display:none;">
	    <h3 id="js-monitor-title" style="display:none;"><?php echo $lang['monitor_title'];?></h3>
	    <div style="background:#fff;width: 210px;overflow: hidden;">
	        <br /><br />
	        <strong id="js-monitor-wait-please" style='color:blue;width:65%;float:left;margin-left:3px;display:none;'></strong>
	        <span id="js-monitor-view-detail" style="color:gray;cursor:pointer;;float:right;margin-right:3px;display:none;"></span>
	    </div>
	    <iframe id="js-monitor-notice" src="templates/notice.htm" style="display:none;"></iframe>
	    <img id="js-monitor-close" src='/tools/img/close.gif' style="display:none;position:absolute;top:10px;right:10px;cursor:pointer;" />
	</div>
	<div>
		<div id="installing"></div>
		<img style="float:left;width:40px;height:40px;display:none;" id="js-monitor-loading" src='/tools/img/new_loading.gif' />
	</div>
	<div id="install_end"></div>
	<input type="hidden" id="front_url" value="<?php echo $server_host;?>" />
	<input type="hidden" id="back_url" value="<?php echo $server_host.'/admin/';?>" />
</div>



</div>
<div style="clear:both;"></div>
<div style="width:660px;height:30px;background:#E7F0FF;margin:0px 0 0;"></div>
</div>
<!--安装配置-->
<form id="js-setting" name="formsetting">
<div class="custom_install_table" style="display:none">
	<div class="title" style="text-align:left;padding:10px 0 0 10px;font-size:18px;">>自定义安装</div>
	<div class="account">
		<ul>
			<li style="width:600px;">
				<div class="name" style="padding:5px 0 0;"><?php echo $lang['db_name'];?></div>
				<div class="data" style="width:450px;">
					<input type="text" name="js-db-name"  value="seevia" />
			        <select name="js-db-list">
			            <option><?php echo $lang['db_list'];?></option>
			        </select>
			        <input style="width:30px;height:21px;" type="button" name="js-go" class="button" value="<?php echo $lang['go'];?>" />
				
				</div>
			</li>
			<li>
				<div class="name"><?php echo $lang['db_host'];?></div>
				<div class="data">
					<input type="text" name="js-db-host"  value="localhost" />
				</div>
				<div class="name left10" style="width:60px;"><?php echo $lang['db_port'];?></div>
				<div class="data">
					<input type="text" name="js-db-port"  value="3306" />
				</div>
			</li>
			<li>
				<div class="name"><?php echo $lang['db_user'];?></div>
				<div class="data">
					<input type="text" name="js-db-user"  value="root" />
				</div>
				<div class="name left10" style="width:60px;"><?php echo $lang['db_pass'];?></div>
				<div class="data">
					<input type="password" name="js-db-pass"  value="" />
				</div>
			</li>
		</ul>
	</div>
	<div class="master_account">
		<ul>
			<li>
				<div class="name"><?php echo $lang['admin_name'];?></div>
				<div class="data">
					<input type="text" name="js-admin-name"  value="admin" />
				</div>
			</li>
			<li>
				<div class="name"><?php echo $lang['admin_password'];?></div>
				<div class="data">
					<input type="password" name="js-admin-password"  value="password" /><span id="js-admin-password-result"></span>
				</div>
				<div class="name left10" style="width:60px;"><?php echo $lang['admin_password2'];?></div>
				<div class="data" style="width:220px;">
					<input type="password" name="js-admin-password2"  value="password" /><span id="js-admin-confirmpassword-result"></span>
				</div>
			</li>
			<li>
				<div class="name"><?php echo $lang['password_intensity'];?></div>
				<div class="data">
					<table width="132" cellspacing="0" cellpadding="1" border="0">
		              <tbody><tr align="center">
		                <td width="33%" id="pwd_lower" style="border-bottom: 2px solid red;"><?php echo $lang['pwd_lower'];?></td>
		                <td width="33%" id="pwd_middle" style="border-bottom: 2px solid rgb(218, 218, 218);"><?php echo $lang['pwd_middle'];?></td>
		                <td width="33%" id="pwd_high" style="border-bottom: 2px solid rgb(218, 218, 218);"><?php echo $lang['pwd_high'];?></td>
		              </tr>
		            </tbody></table>
				</div>
			</li>
			<li>
				<div class="name" style="width:200px;">
					        <input style="float:left;" type="checkbox" name="js-system-lang" id="js-system-lang-en_us" value="en_us" /><label style="float:left;"  for="js-system-lang-en_us">多语言 英语<?php //echo $lang['americanese'];?></label></div>
				
				<input name="userinterface" type="hidden" value="<?php echo $userinterface; ?>" />
				<input name="ucapi" type="hidden" value="<?php echo $ucapi; ?>" />
				<input name="ucfounderpw" type="hidden" value="<?php echo $ucfounderpw; ?>" />
				<input type="hidden" name="js-system-lang" id="js-system-lang-zh_cn" value="zh_cn" />
			</li>
			<li>
				<div class="name" style="width:200px;"><label><input type="checkbox" class="p" name="js-install-demo" style="float:left;" /><span style="float:left;">安装测试数据<?php //echo $lang['install_demo'];?></span></label></div>
				<div class="data"></div>
			</li>
		</ul>
	</div>
</div>
</form>
<!--安装配置end-->
<table id="error_table" border="0" cellpadding="0" cellspacing="0" style="width:660px;margin:0 auto 100px;display:none;">
<tr>
<td valign="top">
	<div id="wrapper">
  	<h3><?php echo $lang['system_environment'];?></h3>
        <div class="list error_msg1"> 
			<?php //pr($system_info);?>
			<?php foreach($system_info as $info_item): ?>
			<div style="float:left;width:20px;height:20px;">
	          <?php if($info_item[1]=="关闭" || $info_item[1] =="NO"){?>
					<img src="/tools/img/l.jpg" style="width:14px;height:14px;"/>
			  <?php }?>
			</div>
			<div style="float:left;width:480px;height:20px;">
		  		<?php echo $info_item[0];?>...............................................................................................<?php echo $info_item[1];?>
			</div>
			<br />
          	<?php endforeach;?> 
		</div>
        <h3 style="text-indent:73px"><?php echo $lang['dir_priv_checking'];?><a href="javascript:void(0);" class="do_fun" style="font-size:12px;"><img src="/tools/img/right.png" style="width:16px;height:13px;margin:3px 10px 0px 10px"/>解决方法</a></h3>
        <div class="list error_msg2">
			<?php foreach($dir_checking as $checking_item): ?>
			<div style="float:left;width:20px;height:20px;">
	          <?php if($checking_item[1]=="不可写" || $checking_item[1] =="No writable"){?>
					<img src="/tools/img/x.jpg" style="width:14px;height:14px;"/>
			  <?php }?>
			</div>
			<div style="float:left;width:480px;height:20px;">
	          <?php echo $checking_item[0];?>............................................................................................
	              <?php if ($checking_item[1] == $lang['can_write']):?>
	                <span style="color:green;"><?php echo $checking_item[1];?></span>
	             <?php else:?>
	                <span style="color:red;"><?php echo $checking_item[1];?></span>
	              <?php endif;?>
			</div><br />
         	<?php endforeach;?>
		</div>
        <h3 ><?php echo $lang['template_writable_checking'];?><?php if ($has_unwritable_tpl == "yes"):?><a href="javascript:void(0);" class="do_fun2" style="font-size:12px;"><img src="/tools/img/right.png" style="width:16px;height:13px;margin:3px 10px 0px 10px"/>解决方法</a><?php endif;?></h3>
        <div class="list error_msg3">
         	<?php if ($has_unwritable_tpl == "yes"):?>
              	<?php foreach($template_checking as $checking_item): ?>
                    <span style="color:red;"><?php echo $checking_item;?></span><br />
              	<?php endforeach; ?>
          	<?php else:?>
            	<div style="color:green;"><?php echo $template_checking;?></div>
          	<?php endif;?>
		</div>
        <?php if (!empty($rename_priv)) :?>
        <h3><?php echo $lang['rename_priv_checking']; ?></h3>
        <div class="list">
          <?php foreach($rename_priv as $checking_item): ?>
          <span style="color:red;"><?php echo $checking_item;?></span><br />
          <?php endforeach; ?>
        </div>
        <?php endif;?>
	</div>
	<div id="check_lock">
		<h3><span class='lock_title'>无法安装，您的安装已被锁定！</span><a href="javascript:void(0);" class="do_fun3" style="font-size:12px;"><img src="/tools/img/right.png" style="width:16px;height:13px;margin:3px 10px 0px 10px"/>解决方法</a></h3>
	</div>
</td>
<!--<td width="227" valign="top" style="background:url(/tools/img/install-bg.gif) repeat-y;"><img src="/tools/img/install-step2-<?php echo $installer_lang;?>.gif" alt="" /></td>-->
</tr>
<tr>
  <td>
<!--<div id="install-btn"><input type="button" class="button" id="js-pre-step" class="button" value="<?php echo $lang['prev_step'];?><?php echo $lang['welcome_page'];?>"  />
      <input type="button" class="button" id="js-recheck" class="button" value="<?php echo $lang['recheck'];?>"  />
      <input type="submit" class="button" id="js-submit"  class="button" value="<?php echo $lang['next_step'] . $lang['config_system'];?>" <?php echo $disabled;?> /></div>-->
  </td>
  <td></td>
</tr>
</table>
<input name="userinterface" id="userinterface" type="hidden" value="<?php echo $userinterface; ?>" />
<input name="ucapi" type="hidden" value="<?php echo $ucapi; ?>" />
<input name="ucfounderpw" type="hidden" value="<?php echo $ucfounderpw; ?>" />
<!--软件条款-->
<div class="dialog_clause" style="width:555px;height:423px;background:#fff;display:none;padding:0;">
	<div class="x" style="margin-top:-1px;width:555px;height:25px;text-align: right;line-height:25px;">
		<a class="close" href="javascript:void(0);" style="margin:0 10px 0 0;color:#ff0000;">X</a>
	</div>
	<div>
		<div class="popup_title" style="width:500px;margin:0 auto;text-align:center;padding:0px 0 20px;font-size:20px;">软件条款</div>
		<textarea style="width:500px;height:250px;resize:none;padding:6px;font-size:14px;margin: 0 auto;border: 1px solid #ccc;line-height:21px;" readonly="true"><?php if(isset($agreement)){echo $agreement;}?></textarea>
		<div class="popup_btn" style="width:500px;margin:20px auto;text-align:center;">
			<input style="width:80px;height:30px;color:#ffffff;background-color:#5eb95e;border-color:#5eb95e;" type="button" class="button" id="js-install-at-once" value="同意"  />
			<input style="width:80px;height:30px;margin:0 0 0 40px;color:#ffffff;background-color: #dd514c;border-color: #dd514c;" type="button" class="button close" id="no_agree" value="不同意"  />
		</div>
	</div>
</div>
<div class="dialog_fun" style="width:555px;height:423px;background:#fff;display:none;padding:0;">
	<div class="x" style="margin-top:-1px;width:555px;height:25px;text-align: right;line-height:25px;">
		<a class="close" href="javascript:void(0);" style="margin:0 10px 0 0;color:#ff0000;">X</a>
	</div>
	<div>
		<div class="popup_title" style="width:500px;margin:0 auto;text-align:center;padding:0px 0 20px;font-size:18px;">解决参考</div>
		<?php
			$message1="1.检查以下目录权限，看看目录是否存在并且均为可写，将目录权限改为可读可写<br>";
			foreach($dir_checking as $checking_item):
		 		if($checking_item[1]=="不可写" || $checking_item[1] =="No writable"){ 
					$message1.=dirname(ROOT_PATH).$checking_item[0].'<br>'; 
				}
			endforeach;
			$message1.="2.也可到终端服务器上执行以下命令修改文件权限<br>";
			foreach($dir_checking as $checking_item1):
			 	if($checking_item1[1]=="不可写" || $checking_item1[1] =="No writable"){
					$message1.="chmod -Rf 777 ".dirname(ROOT_PATH).$checking_item1[0].'<br>';
				}
			endforeach;
		?>
		<div style="width:500px;height:250px;resize:none;padding:2px;font-size:14px;margin: 0 auto;border: 1px solid #ccc;text-align:left;" readonly="true"><?php echo $message1; ?></div>
		<div class="popup_btn" style="width:500px;margin:20px auto;text-align:center;"><input type="button" class="button js-recheck-second"  style="background:#F37B1D;border-color:#F37B1D;color:#fff;font-size:14px;" value="已修改完成,<?php echo $lang['recheck'];?>"  /></div>
	</div>
</div>
<div class="dialog_fun2" style="width:555px;height:423px;background:#fff;display:none;padding:0;">
	<div class="x" style="margin-top:-1px;width:555px;height:25px;text-align: right;line-height:25px;">
		<a class="close" href="javascript:void(0);" style="margin:0 10px 0 0;color:#ff0000;">X</a>
	</div>
	<div>
		<div class="popup_title" style="width:500px;margin:0 auto;text-align:center;padding:0px 0 20px;font-size:18px;">解决参考2</div>
		<?php
			$message2="1.检查以下目录权限，看看目录是否存在并且均为可写，将目录权限改为可读可写<br>";
			foreach($templates_root as $dir_root):
				$message2.="  ".$dir_root."<br>";
			endforeach;
			$message2.="2.也可到终端服务器上执行以下命令修改目录权限<br>";
			foreach($templates_root as $dir_root):
				$message2.="  chmod -Rf 777 ".$dir_root."<br>";
			endforeach;
		?>
		<div style="width:500px;height:250px;resize:none;padding:2px;font-size:14px;margin: 0 auto;border: 1px solid #ccc;text-align:left;" readonly="true"><?php echo $message2; ?></div>
		<div class="popup_btn" style="width:500px;margin:20px auto;text-align:center;"><input type="button" class="button js-recheck-second" style="background:#F37B1D;border-color:#F37B1D;color:#fff;font-size:14px;"  value="已修改完成,<?php echo $lang['recheck'];?>"  /></div>
	</div>
</div>
<div class="dialog_fun3" style="width:555px;height:423px;background:#fff;display:none;padding:0;">
	<div class="x" style="margin-top:-1px;width:555px;height:25px;text-align: right;line-height:25px;">
		<a class="close" href="javascript:void(0);" style="margin:0 10px 0 0;color:#ff0000;">X</a>
	</div>
	<div>
		<div class="popup_title" style="width:500px;margin:0 auto;text-align:center;padding:0px 0 20px;font-size:18px;">解决参考3</div>
		<?php
			$message3="1、到安装所在的服务器上，找到对应的文件，将其删除即可<br>";
			$message3.="  ".$_SERVER['DOCUMENT_ROOT']."/data/install.lock<br>";
			$message3.="2、也可到终端服务器上执行命令将文件删除<br>";
			$message3.="  命令：  rm -Rf ".$_SERVER['DOCUMENT_ROOT']."/data/install.lock";
		?>
		<div style="width:500px;height:250px;resize:none;padding:2px;font-size:14px;margin: 0 auto;border: 1px solid #ccc;" readonly="true"><?php echo $message3; ?></div>
		<div class="popup_btn" style="width:500px;margin:20px auto;text-align:center;"><input type="button" class="button js-recheck-second" style="background:#F37B1D;border-color:#F37B1D;color:#fff;font-size:14px;" value="已修改完成,<?php echo $lang['recheck'];?>"  /></div>
	</div>
</div>
<!--[if IE]>
<style>
input[type=checkbox]{margin:-4px 5px 0 0;}
.button{border: 1px solid #2d5082; border-radius: 4px;height: 25px;margin: 2px;padding: 11px 45px;position: relative;}
a {huerreson:expression(onfocus=function(){this.blur()});}
img{border:none;}
h3{padding:10px 0 0px;}
</style>
<![endif]-->
<div class="lee_dialog_bg" style="width:100%;height:100%;background:#000;opacity:0.35;filter:alpha(opacity=70);position:fixed;left:0;top:0;z-index:2147483600;display:none;"></div>
<script type="text/javascript">
$(document).ready(function(){
	$.ajax({ url: "/tools/installs/",
			type:"POST", 
			data: { 'is_ajax':1},
			success: function(data){
				result=JSON.parse(data);
				if(result.lock){
					setTimeout(function(){
						$("#ajax-loader").css("display","none");
						$("#system").css("display","none");
						$("#error_info").css("display","block");
						$("#js-recheck").css("display","block");
						$("#error_info").click();
						$("#wrapper").hide();
						$("#check_lock").show();
					},1000);
				}else{
					$("#check_lock").hide();
					$("#wrapper").show();
					if (result.disabled == '""') {
						setTimeout(function(){
							$("#ajax-loader").css("display","none");
							$("#check_system").css("display","none");
							$("#install_ing").css("display","block");
							var auto=true;
							getDbList(auto);
						},1000);
				    }else{
			    		setTimeout(function(){
							$("#ajax-loader").css("display","none");
							$("#system").css("display","none");
							$("#error_info").css("display","block");
							$("#js-recheck").css("display","block");
							$("#error_info").click();
						},1000);
						
				    }
				}
			}
		});
//	var flag=<?php echo $disabled;?>;
//	if(flag == ""){
//		
//	}else{
//			
//	}
	$("#error_info").toggle(
		function(){
			$("#error_table").show();
			$("#seevia").css("margin-top","100px");
		},
		function(){
			$("#error_table").hide();
			$("#seevia").css("margin-top","200px");
		}
	);
	$("#js-install-at-once").click(function(){
		var auto=false;
		var success=getDbList(auto);
		//alert(success.length);
		if (success.length>0) {
			$("#js-setting").submit();
		}else{
			$("#no_agree").click();
		}
	});
	$("#custom_install").toggle(
		function(){
			$("#js-setting").find(".custom_install_table").css("display","block").css("margin-bottom","100px");
			$("#seevia").css("margin-top","100px");
		},
		function(){
			$("#js-setting").find(".custom_install_table").css("display","none");
			$("#seevia").css("margin-top","200px");
		}
	);
    $("#js-recheck").click(function(){
		$("#ajax-loader").css("display","block");
		$("#system").css("display","block");
		$("#error_info").css("display","none");
		$("#js-recheck").css("display","none");
		$("#error_table").css("display","none");
        $.ajax({ url: "/tools/installs/",
			type:"GET", 
			data: { 'is_ajax':1},
			cache:false,
			success: function(data){
				result=JSON.parse(data);
				//alert(result.dir_checking);
				var e_msg1="";
				var e_msg2="";
				var e_msg3="";
				if(result.lock){
					setTimeout(function(){
						$("#ajax-loader").css("display","none");
						$("#system").css("display","none");
						$("#error_info").css("display","block");
						$("#js-recheck").css("display","block");
						$("#error_info").click();
						$("#wrapper").hide();
						$("#check_lock").show();
					},1000);
				}else{
					$("#check_lock").hide();
					$("#wrapper").show();
					if (result.disabled == '""') {
						setTimeout(function(){
							$("#ajax-loader").css("display","none");
							$("#check_system").css("display","none");
							$("#install_ing").css("display","block");
							var auto=true;
							getDbList(auto);
						},1000);
				    }else{
						$.each(result.system_info,function (i,item ){ 
							//alert(item['0']+" "+item['1']);
							e_img=(item['1']=="关闭" || item['1']=="NO")?"<div class='e_img1'><img src='/tools/img/l.jpg' style='width:14px;height:14px;'/></div>":"<div class='e_img1'></div>";
							e_msg1+=e_img+"<div class='e_msg1'>"+item['0']+".........................................................................."+item['1']+"</div><br>";
						});
						$(".error_msg1").html(e_msg1);
						$.each(result.dir_checking,function (i,item ){ 
							//alert(item['0']+" "+item['1']);
							e_img=(item['1']=="不可写" || item['1']=="No writable")?"<div class='e_img1'><img src='/tools/img/x.jpg' style='width:14px;height:14px;'/></div>":"<div class='e_img1'></div>";
							e_span=(item['1']=="不可写"|| item['1']=="不存在" || item['1']=="No writable")?"<span class='red'>"+item['1']+"</span>":"<span class='green'>"+item['1']+"</span>";
							e_msg2+=e_img+"<div class='e_msg1'>"+item['0']+".........................................................................."+e_span+"</div><br>";
						});
						$(".error_msg2").html(e_msg2);
						if(result.has_unwritable_tpl=="yes"){
							$.each(result.dir_checking,function (i,item ){ 
								e_msg3+="<span class='red'>"+item['0']+"</span><br>";
							});
						}else{
							e_msg3="<div class='green'>所有模板，全部可写</div>";
						}
						$(".error_msg3").html(e_msg3);
			    		setTimeout(function(){
							$("#ajax-loader").css("display","none");
							$("#system").css("display","none");
							$("#error_info").css("display","block");
							$("#js-recheck").css("display","block");
							$("#error_info").click();
						},1000);
						
				    }
				}
			}
		});
    });
    $(".js-recheck-second").click(function(){
		$(".close").click();
		$("#ajax-loader").css("display","block");
		$("#system").css("display","block");
		$("#error_info").css("display","none");
		$("#js-recheck").css("display","none");
		$("#error_table").css("display","none");
        $.ajax({ url: "/tools/installs/",
			type:"GET", 
			data: { 'is_ajax':1},
			cache:false,
			success: function(data){
				result=JSON.parse(data);
				//alert(result.dir_checking);
				var e_msg1="";
				var e_msg2="";
				var e_msg3="";
				if(result.lock){
					setTimeout(function(){
						$("#ajax-loader").css("display","none");
						$("#system").css("display","none");
						$("#error_info").css("display","block");
						$("#js-recheck").css("display","block");
						$("#error_info").click();
						$("#wrapper").hide();
						$("#check_lock").show();
					},1000);
				}else{
					$("#check_lock").hide();
					$("#wrapper").show();
					if (result.disabled == '""') {
						setTimeout(function(){
							$("#ajax-loader").css("display","none");
							$("#check_system").css("display","none");
							$("#install_ing").css("display","block");
							var auto=true;
							getDbList(auto);
						},1000);
				    }else{
						$.each(result.system_info,function (i,item ){ 
							//alert(item['0']+" "+item['1']);
							e_img=(item['1']=="关闭" || item['1']=="NO")?"<div class='e_img1'><img src='/tools/img/l.jpg' style='width:14px;height:14px;'/></div>":"<div class='e_img1'></div>";
							e_msg1+=e_img+"<div class='e_msg1'>"+item['0']+".........................................................................."+item['1']+"</div><br>";
						});
						$(".error_msg1").html(e_msg1);
						$.each(result.dir_checking,function (i,item ){ 
							//alert(item['0']+" "+item['1']);
							e_img=(item['1']=="不可写" || item['1']=="No writable")?"<div class='e_img1'><img src='/tools/img/x.jpg' style='width:14px;height:14px;'/></div>":"<div class='e_img1'></div>";
							e_span=(item['1']=="不可写" || item['1']=="No writable")?"<span class='red'>"+item['1']+"</span>":"<span class='green'>"+item['1']+"</span>";
							e_msg2+=e_img+"<div class='e_msg1'>"+item['0']+".........................................................................."+e_span+"</div><br>";
						});
						$(".error_msg2").html(e_msg2);
						if(result.has_unwritable_tpl=="yes"){
							$.each(result.dir_checking,function (i,item ){ 
								e_msg3+="<span class='red'>"+item['0']+"</span><br>";
							});
						}else{
							e_msg3="<div class='green'>所有模板，全部可写</div>";
						}
						$(".error_msg3").html(e_msg3);
			    		setTimeout(function(){
							$("#ajax-loader").css("display","none");
							$("#system").css("display","none");
							$("#error_info").css("display","block");
							$("#js-recheck").css("display","block");
							$("#error_info").click();
						},1000);
						
				    }
				}
			}
		});
    });
});

function ErrorInfo() {
    var error_table=  getid("error_table");

    if (!error_table) {
        return;
    }
	$("#error_table").attr("style","display:block;width:660px;margin:0px auto 100px;");
	$("#seevia").attr("style","width:660px;border:1px solid #ccc;margin:80px auto 0px;");

};
$('.do_fun').lee_dialog({dialog:'.dialog_fun',close:'.close'});
$('.do_fun2').lee_dialog({dialog:'.dialog_fun2',close:'.close'});
$('.do_fun3').lee_dialog({dialog:'.dialog_fun3',close:'.close'});
$(".agree").lee_dialog({dialog:'.dialog_clause',close:'.close'});
</script>
