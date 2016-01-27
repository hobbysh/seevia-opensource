/* 初始化一些全局变量 */
var lf = "<br />";
var iframe = null;
var notice = null;
var oriDisabledInputs = [];
var auto=null;
/* Ajax设置 */
//Ajax.onRunning = null;
//Ajax.onComplete = null;

/* 页面加载完毕，执行一些操作 */

window.onload = function () {
    setInputCheckedStatus();
    var f = getid("js-setting");
    f.setAttribute("action", "javascript:install();void 0;");
    f["js-db-name"].onblur = function () {
    	auto=false;
        var list = getDbList(auto);
        for (var i = 0; i < list.length; i++) {
            if (f["js-db-name"].value === list[i]) {
                var answer = confirm("这是一个已经存在的数据库，确定要覆盖该数据库吗？");
                if (answer === false) {
                    f["js-db-name"].value = "";
	            }
            }
        }
    }
    f["js-admin-password"].onblur = function  () {
            var password = f['js-admin-password'].value;
            var confirm_password = f['js-admin-password2'].value;
            if(confirm_password!="" && password!=""){
                if (password==confirm_password)
                {
                    getid("js-install-at-once").removeAttribute("disabled");
                    getid("js-admin-confirmpassword-result").innerHTML="<img src='/tools/img\/yes.gif'>";
                }else{
                    getid("js-install-at-once").setAttribute("disabled", "true");
                    if (confirm_password!=''){
                    	getid("js-admin-confirmpassword-result").innerHTML="<span class='comment'><img src='/tools/img\/no.gif'>密码不相同<\/span>";
                    }
                }
        	}
        }
    f["js-admin-password2"].onblur = function  () {
        var password = f['js-admin-password'].value;
        var confirm_password = f['js-admin-password2'].value;
        if(confirm_password!="" && password!=""){
	        if (password==confirm_password){
	            getid("js-admin-confirmpassword-result").innerHTML="<span class='comment'><img src='/tools/img\/yes.gif'><\/span>";
	            getid("js-install-at-once").removeAttribute("disabled");
	        }else{
	            getid("js-admin-confirmpassword-result").innerHTML="<span class='comment'><img src='/tools/img\/no.gif'>密码不相同<\/span>";
	            getid("js-install-at-once").setAttribute("disabled", "true");
	        }
		}else{
			getid("js-admin-confirmpassword-result").innerHTML="<span class='comment'><img src='/tools/img\/no.gif'>密码不能空<\/span>";
	        getid("js-install-at-once").setAttribute("disabled", "true");
		}
    }
    f["js-admin-password"].onkeyup = function () {
      var pwd = f['js-admin-password'].value;
      var Mcolor = "#FFF",Lcolor = "#FFF",Hcolor = "#FFF";
      var m=0;
      var Modes = 0;
      for (i=0; i<pwd.length; i++)
      {
        var charType = 0;
        var t = pwd.charCodeAt(i);
        if (t>=48 && t <=57){
          charType = 1;
        }else if (t>=65 && t <=90){
          charType = 2;
        }else if (t>=97 && t <=122){
          charType = 4;
        }else{
          charType = 4;
    	}
        Modes |= charType;
      }
      for (i=0;i<4;i++)
      {
        if (Modes & 1) m++;
          Modes>>>=1;
      }
      if (pwd.length<=4)
      {
        m = 1;
      }
      switch(m)
      {
        case 1 :
          Lcolor = "2px solid red";
          Mcolor = Hcolor = "2px solid #DADADA";
        break;
        case 2 :
          Mcolor = "2px solid #f90";
          Lcolor = Hcolor = "2px solid #DADADA";
        break;
        case 3 :
          Hcolor = "2px solid #3c0";
          Lcolor = Mcolor = "2px solid #DADADA";
        break;
        case 4 :
          Hcolor = "2px solid #3c0";
          Lcolor = Mcolor = "2px solid #DADADA";
        break;
        default :
          Hcolor = Mcolor = Lcolor = "";
        break;
      }
      if (document.getElementById("pwd_lower"))
      {
        document.getElementById("pwd_lower").style.borderBottom  = Lcolor;
        document.getElementById("pwd_middle").style.borderBottom = Mcolor;
        document.getElementById("pwd_high").style.borderBottom   = Hcolor;
      }
    }
    f["js-go"].onclick = displayDbList;
    var detail = getid("js-monitor-view-detail")
    detail.innerHTML = "显示细节";
    detail.onclick = function () {
        var mn = getid("js-monitor-notice");
        if (mn.style.display === "block") {
            mn.style.display = "none"
            this.innerHTML = "显示细节";
        } else {
            mn.style.display = "block"
            this.innerHTML = "显示细节";
        }
    };
    iframe = frames[0];
    var d = new Draggable();
    d.bindDragNode("js-monitor", "js-monitor-title");
    getid("js-system-lang-" + getAddressLang()).setAttribute("checked", "checked");
    getid("js-pre-step").onclick = function () {
        location.href = "/tools/installs/index?lang=" + getAddressLang();
    };
    f["js-install-demo"].onclick = switchInputsStatus;
};

/**
 * 显示数据库列表
 */
function displayDbList() {
    var f = getid("js-setting"), dbList = f["js-db-list"];
    dbList.onchange = function () {
        f["js-db-name"].value = dbList.options[dbList.selectedIndex].value;
        f["js-db-name"].focus();
    };
	auto=false;
    var opts = getDbList(auto),
        opt;
    if (opts !== false) {
        dbList.options.length = 1;
        var num = opts.length;
       	var total_num="共 %s 个";
        	text = total_num.replace("%s", num);
        dbList[0] = new Option(text, "", false, false);
        for (var i = 0; i < num; i++) {
            opt = new Option(opts[i], opts[i], false, false);
            dbList[dbList.options.length] = opt;
        }
    }
}

/**
 * 获得数据库列表
 */
function getDbList(auto) {
    var f = getid("js-setting"),
        params="db_host=" + f["js-db-host"].value + "&"
            + "db_port=" + f["js-db-port"].value + "&"
            + "db_user=" + encodeURIComponent(f["js-db-user"].value) + "&"
            + "db_pass=" + encodeURIComponent(f["js-db-pass"].value) + "&"
            + "lang=" + getAddressLang() + "&"
            + "IS_AJAX_REQUEST=yes";
    var result="";
    var list="";
        //var result = Ajax.call("/tools/installs/get_db_list", params, null, "POST", "JSON", false);
        $.ajax({ url: "/tools/installs/get_db_list",
			type:"POST", 
			data: { 'db_host':f["js-db-host"].value,'db_port':f["js-db-port"].value,'db_user':encodeURIComponent(f["js-db-user"].value),'db_pass':encodeURIComponent(f["js-db-pass"].value),'lang':getAddressLang(),'IS_AJAX_REQUEST':"yes"},
			async:false,  // 设置同步方式
        	cache:false,
			success: function(data){
				result=JSON.parse(data);
				if (result.msg=== "OK") {
					//alert(result.list);
					list=result.list.split(",");
			    }else{
		    		if(auto){
		    			$("#custom_install").trigger("click");
					}else{
						alert("连接数据库用户名或密码错误");
					}
			    }
			}
		});
	return list;
}

/**
 * 切换复选框的状态
 */
function switchInputsStatus() {
    var goodsTypes = document.getElementsByName("js-goods-type[]"),
        num = goodsTypes.length;
    if (this.checked) {
        for (var i = 0; i < num; i++) {
            goodsTypes[i].checked = "checked";
            goodsTypes[i].disabled = "true";
        }
    } else {
        for (var i = 0; i < num; i++) {
            goodsTypes[i].checked = "";
            goodsTypes[i].disabled = "";
        }
    }
}

/**
 * 安装程序主函数
 */
function install() {
	//alert("1");
	$(".dialog_clause").hide();
  	$(".lee_dialog_bg").hide();
	 notice = $("#installing");
	 
    lockAllInputs();

    //startNotice();
//    getid("js-install-at-once").setAttribute("disabled", "true");
//    getid("js-install-at-once").style.display = "none";
    getid("js-monitor").style.display = "block";
    getid("install-btn").style.display = "none";
    getid("install_ing").style.display = "none";
    $(".custom_install_table").css("display","none");
    $("#js-monitor-loading").css("display","block");
    notice.css("display","block");
//    try {
	//alert("3");
	setTimeout(function(){createConfigFile()},1000);
        
    //alert("4");
//    } catch (ex) {
//    }
}

/**
 * 创建配置文件
 */
function createConfigFile() {
    var f = getid("js-setting"),
        tzs = f["js-timezones"],
        tz = tzs ? "timezone=" + tzs[tzs.selectedIndex].value : "",
        tz_val=tzs ?tzs[tzs.selectedIndex].value:"";
        params="db_host=" + f["js-db-host"].value + "&"
            + "db_port=" + f["js-db-port"].value + "&"
            + "db_user=" + encodeURIComponent(f["js-db-user"].value) + "&"
            + "db_pass=" + encodeURIComponent(f["js-db-pass"].value) + "&"
            + "db_name=" + encodeURIComponent(f["js-db-name"].value) + "&"
            + tz + "&"
            + "lang=" + getAddressLang() + "&"
            + "IS_AJAX_REQUEST=yes";
    //notice.innerHTML = $_LANG["create_config_file"];
    notice.html("创建配置文件................");
    $.ajax({ url: "/tools/installs/create_config_file",
		type:"POST", 
		data: { 'db_host':f["js-db-host"].value,'db_port':f["js-db-port"].value,'db_user':encodeURIComponent(f["js-db-user"].value),'db_pass':encodeURIComponent(f["js-db-pass"].value),'db_name':encodeURIComponent(f["js-db-name"].value),'timezone':tz_val,'lang':getAddressLang(),'IS_AJAX_REQUEST':"yes"},
		async:true,  // 设置同步方式
    	cache:false,
		success: function(result){
			if (result.replace(/\s+$/g, '') === "OK") {
	            displayOKMsg();
	            createDatabase();
	        } else {
	            if(result=="create_config_file erro"){
		            message="创建配置文件................"
		            displayErrorMsg(message);
	        	}
	        }
		}
	});
//    Ajax.call("/tools/installs/create_config_file", params, function (result) {
//        if (result.replace(/\s+$/g, '') === "OK") {
//            displayOKMsg();
//            createDatabase();
//        } else {
//            if(result=="create_config_file erro"){
//	            message="创建配置文件................"
//	            displayErrorMsg(message);
//        	}
//        }
//    });
}

/**
 * 初始化数据库
 */
function createDatabase() {
    var f = getid("js-setting"),
        params="db_host=" + f["js-db-host"].value + "&"
            + "db_port=" + f["js-db-port"].value + "&"
            + "db_user=" + encodeURIComponent(f["js-db-user"].value) + "&"
            + "db_pass=" + encodeURIComponent(f["js-db-pass"].value) + "&"
            + "db_name=" + encodeURIComponent(f["js-db-name"].value);
    notice.append("创建数据库....................");
    $.ajax({ url: "/tools/installs/create_database",
		type:"POST", 
		data: { 'db_host':f["js-db-host"].value,'db_port':f["js-db-port"].value,'db_user':encodeURIComponent(f["js-db-user"].value),'db_pass':encodeURIComponent(f["js-db-pass"].value),'db_name':encodeURIComponent(f["js-db-name"].value)},
		async:true,  // 设置同步方式
    	cache:false,
		success: function(result){
			if (result.replace(/\s+$/g, '') === "OK") {
	            displayOKMsg();
	            installBaseData();
	        } else {
	            if(result=="create_database erro"){
		            message="创建数据库....................";
		            displayErrorMsg(message);
	        	}
	        }
		}
	});
//    Ajax.call("/tools/installs/create_database", params, function (result) {
//        if (result.replace(/\s+$/g, '') === "OK") {
//          	displayOKMsg();
//            installBaseData();
//        } else {
//            if(result=="create_database erro"){
//	            message="创建数据库...................."
//	            displayErrorMsg(message);
//        	}
//        }
//    });
}

/**
 * 安装数据
 */
function installBaseData() {
    var f = getid("js-setting"),
        params = "db_host=" + f["js-db-host"].value + "&"
            + "db_name=" + f["js-db-name"].value + "&"
            + "db_user=" + encodeURIComponent(f["js-db-user"].value) + "&"
            + "db_pass=" + encodeURIComponent(f["js-db-pass"].value);
    notice.append("安装数据........................");
    $.ajax({ url: "/tools/installs/install_base_data",
		type:"POST", 
		data: { 'db_host':f["js-db-host"].value,'db_name':f["js-db-name"].value,'db_user':encodeURIComponent(f["js-db-user"].value),'db_pass':encodeURIComponent(f["js-db-pass"].value)},
		async:true,  // 设置同步方式
    	cache:false,
		success: function(result){
	        if (result.replace(/\s+$/g, '') === "OK") {
	          	displayOKMsg();
	            createAdminPassport();
	        } else {
	            if(result=="install_base_data erro"){
		            message="安装数据........................"
		            displayErrorMsg(message);
	        	}
	        }
		}
	});
//    Ajax.call("/tools/installs/install_base_data", params, function (result) {
//        if (result.replace(/\s+$/g, '') === "OK") {
//          	displayOKMsg();
//            createAdminPassport();
//        } else {
//            if(result=="install_base_data erro"){
//	            message="安装数据........................"
//	            displayErrorMsg(message);
//        	}
//        }
//    });
}

/**
 * 创建管理员帐号
 */
function createAdminPassport() {
    var f = getid("js-setting"),
        params="db_host=" + f["js-db-host"].value + "&"
            + "db_name=" + f["js-db-name"].value + "&"
            + "db_user=" + encodeURIComponent(f["js-db-user"].value) + "&"
            + "db_pass=" + encodeURIComponent(f["js-db-pass"].value) + "&"
        	+ "admin_name=" + encodeURIComponent(f["js-admin-name"].value) + "&"
            + "admin_password=" + encodeURIComponent(f["js-admin-password"].value) + "&"
            + "admin_password2=" + encodeURIComponent(f["js-admin-password2"].value);
    notice.append("创建管理员帐号............");
    $.ajax({ url: "/tools/installs/create_admin_passport",
		type:"POST", 
		data: { 'db_host':f["js-db-host"].value,'db_name':f["js-db-name"].value,'db_user':encodeURIComponent(f["js-db-user"].value),'db_pass':encodeURIComponent(f["js-db-pass"].value),'admin_name':encodeURIComponent(f["js-admin-name"].value),'admin_password':encodeURIComponent(f["js-admin-password"].value),'admin_password2':encodeURIComponent(f["js-admin-password2"].value)},
		async:true,  // 设置同步方式
    	cache:false,
		success: function(result){
			if (result.replace(/\s+$/g, '') === "OK") {
	            displayOKMsg();
	            doOthers();
	        } else {
	            if(result=="create_admin_passport erro"){
		            message="创建管理员帐号............";
		            displayErrorMsg(message);
	        	}
	        }
		}
	});
//    Ajax.call("/tools/installs/create_admin_passport", params, function (result) {
//        if (result.replace(/\s+$/g, '') === "OK") {
//            displayOKMsg();
//            doOthers();
//        } else {
//            if(result=="create_admin_passport erro"){
//	            message="创建管理员帐号............"
//	            displayErrorMsg(message);
//        	}
//        }
//    });
}

/**
 * 处理其它的操作
 */
function doOthers() {
    var f = getid("js-setting"),
        //disableCaptcha = f["js-disable-captcha"].checked ? 0 : 1,
        installDemo = f["js-install-demo"].checked ? 1 : 0,
        installLang = f["js-system-lang"].checked ? 1 : 0,
//      params = "disable_captcha=" + disableCaptcha + "&"
        params ="db_host=" + f["js-db-host"].value + "&"
            + "db_name=" + f["js-db-name"].value + "&"
            + "db_user=" + encodeURIComponent(f["js-db-user"].value) + "&"
            + "db_pass=" + encodeURIComponent(f["js-db-pass"].value) + "&"
            + "install_demo=" + installDemo + "&"
            + "install_lang=" + installLang + "&"
            + "userinterface=" + f["userinterface"].value + "&"
            + "lang=" + getAddressLang();
    notice.append("处理其它........................");
    $.ajax({ url: "/tools/installs/do_others",
		type:"POST", 
		data: { 'db_host':f["js-db-host"].value,'db_name':f["js-db-name"].value,'db_user':encodeURIComponent(f["js-db-user"].value),'db_pass':encodeURIComponent(f["js-db-pass"].value),'install_demo':installDemo,'install_lang':installLang,'userinterface': f["userinterface"].value,'lang':getAddressLang()},
		async:true,  // 设置同步方式
    	cache:false,
		success: function(result){
			if (result.replace(/\s+$/g, '') === "OK") {
	            displayOKMsg();
	            goToDone();
	        } else {
	            if(result=="do_others erro"){
		            message="处理其它........................";
		            displayErrorMsg(message);
	        	}
	        }
		}
	});
//    Ajax.call("/tools/installs/do_others", params, function (result) {
//        if (result.replace(/\s+$/g, '') === "OK") {
//            displayOKMsg();
//            goToDone();
//        } else {
//            if(result=="do_others erro"){
//	            message="处理其它........................"
//	            displayErrorMsg(message);
//        	}
//        }
//    });
}

/**
 * 转到完成页
 */
function goToDone() {
    stopNotice();
	$("#js-monitor").css("display","none");
	$("#installing").css("display","none");
	$("#install_end").css("display","block");
	var admin_name=$("input[name=js-admin-name]").val();
	var admin_pwd=$("input[name=js-admin-password]").val();
	var front_url=$("#front_url").val();
	var back_url=$("#back_url").val();
	var end_html="<div class='finish'>安装完成！</div><div class='admin_info'>管理员账号:"+admin_name+"&nbsp;&nbsp;&nbsp;&nbsp; 登录密码:"+admin_pwd+"</div><div class='end_link'><a target='_blank' class='button' href='"+front_url+"'>访问前台</a><a style='margin-left:20px;' target='_blank' class='button' href='"+back_url+"'>访问后台</a></div>";
	$("#install_end").html(end_html);
}

/* 在安装过程中调用该方法 */
function startNotice() {
    getid("js-monitor-loading").src = "/tools/img/new_loading.gif";
    getid("js-monitor-wait-please").innerHTML = "<strong style='color:blue'>'正在安装中，请稍候…………'</strong>";
};

/* 安装完毕调用该方法 */
function stopNotice() {
    $("#js-monitor-loading").css("display","none");
};

/**
 * 锁定所有的输入组件
 */
function lockAllInputs() {
    recOriDisabledInputs();
    var elems = getid("js-setting").elements;
    for (var i = 0; i < elems.length; i++) {
        elems[i].disabled = "true";
    }
}

/**
 * 解锁某些输入组件
 */
function unlockSpecInputs() {
    var elems = getid("js-setting").elements;
    for (var i = 0; i < elems.length; i++) {
        if (oriDisabledInputs.inArray(elems[i]))  {
            continue;
        }
        elems[i].removeAttribute("disabled");
    }
}

/**
 * 记录那些原先就被锁定的输入组件
 */
function recOriDisabledInputs() {
    var elems = getid("js-setting").elements;
    for (var i = 0; i < elems.length; i++) {
       if (elems[i].disabled) {
            oriDisabledInputs.push(elems[i]);
       }
    }
}

/**
 * 给数组的原型定义一个方法，判断元素是不是属于某个数组
 */
Array.prototype.inArray = function (unit) {
    var length = this.length;
    for (var i = 0; i < length; i++) {
        if (unit === this[i])  {
            return true;
        }
    }
    return false;
}

/**
 * 显示完成信息
 */
function displayOKMsg() {
    notice.append("<span style='color:green;'>成功</span>" + lf);
}

/**
 * 显示错误信息
 */
function displayErrorMsg(result) {
    stopNotice();
    notice.append("<span style='color:red;'>失败</span>" + lf + lf);
    //$("js-monitor-view-detail"). innerHTML = $_LANG["hide_detail"];
    /*getid("js-monitor-view-detail"). innerHTML = "隐藏细节";
    getid("js-monitor-notice").style.display = "block";*/
    /*notice.innerHTML += "<strong style='color:red'>" + result + "</strong>";*/
}