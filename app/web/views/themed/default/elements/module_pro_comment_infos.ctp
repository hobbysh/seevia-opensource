<?php if(constant("Product")=="AllInOne"){?>
<?php echo $htmlSeevia->css(array('embed.default')); ?>
<!--屏蔽的关键字-->
<?php $wordarr=""; if(isset($sm['word'])&&$sm['word']!=""){
	foreach($sm['word'] as $k=>$v){
		$wordarr.=$v['BlockWord']['word'].",";
	}
}?>
<script src="/plugins/AmazeUI/js/handlebars.min.js" type="text/javascript"></script>
<input type="hidden" id="word" value="<?php echo $wordarr;?>">
<div class="am-g am-g-fixed">
  <div class="am-panel am-panel-default" style="margin-top:10px;">
	<div class="am-panel-hd my-head"><?php echo $code_infos[$sk]['name'];?></div>
	<div  class="am-panel-bd">
	  <div class="listbox am-padding-horizontal-sm">
      <!--商品评论-->
      <?php if($code_infos[$sk]['type']=="module_pro_comment_infos"){?>
    	<div class="comment" style="width:100%;margin:0 auto 16px;">
    		<?php if(!empty($_SESSION['User']['User']['id'])&&(isset($can_comment)&&$can_comment)){?>
			<form id="comment_form" name="comment_form" action="/products/add_comment" enctype="multipart/form-data" method="POST">
				<!-- 评分 -->
				<?php if(isset($sm['Score'])&&sizeof($sm['Score'])>0){ ?>
				<div id="comment_title">评分</div>
				<div class="score">
					<ul class="score_list">
						<?php
								$scoreflag=isset($sm['is_score'])&&$sm['is_score']==0?"disabled":"";//是否可以评分
								foreach($sm['Score'] as $sk=>$sv){
							$ScoreValue_list=split("\n",trim($sv['ScoreI18n']['value']));
							if(empty($ScoreValue_list)||sizeof($ScoreValue_list)==0){continue;} 
						?>
							<li class='score_item'><input type="hidden" class="score_item_option" value="<?php echo $sv['Score']['id'] ?>">
								<div class="score_name"><?php echo $sv['ScoreI18n']['name'];?>&nbsp;(平均&nbsp;<?php echo isset($sm['ScoreLog'][$sv['Score']['id']]['average'])?$sm['ScoreLog'][$sv['Score']['id']]['average']:0 ?>):</div>
								<div class="score_value"><?php foreach($ScoreValue_list as $k=>$v){?>
									<input type="radio" class="score_value_<?php echo $sv['Score']['id'] ?>" name="data[Score][<?php echo $sv['Score']['id'] ?>]" value="<?php echo trim($v);?>" <?php echo $scoreflag." ";echo $k==0?"checked='checked'":''; ?> /><span><?php echo trim($v); ?></span>
									<?php } ?>
								</div><div style="clear:both;"></div>
							</li>
						<?php } ?>
					</ul>
				</div>
				<?php } ?>
				<!-- /评分 -->
			<div id="comment_title"><?php echo $ld['comment'] ?></div>
			<div id="ds-thread">
			  <div id="ds-reset">
				<div class="ds-textarea-wrapper ds-rounded-top" >
				  <input type="hidden" name="data[Comment][type_id]" value="<?php echo isset($sm['product_id'])?$sm['product_id']:'0'; ?>">
				  <textarea  style="resize:none;font-size:1.3rem;" onkeyup="strLenCalc($(this),'checklen',280);" class="am-input-sm" id="contenttext"  title="" <?php if(empty($_SESSION['User']['User']['id'])){echo " disabled='disabled'";}?>></textarea>
				  <input type="hidden" name="data[Comment][content]" id="hid" value="" />
				  <input type="hidden" name="user_id" value="<?php if(!empty($_SESSION['User']['User']['id'])){echo $_SESSION['User']['User']['id'];}?>">
				  <input type="hidden" name="rank" value="5">
				</div>
                <div class="ds-other-data">
                     <div class="am-u-lg-4 am-u-sm-6">
                        <div class="kind_detail">
    						<a class="S_func1" href="javascript:void(0);">
    						<i id="biaoqing" class="W_ico16 icon_sw_face"></i>
    						</a>
    						<a class="S_func1" style="margin-left:5px;" href="javascript:void(0);">
    						<i class="W_ico16 icon_sw_img" id="pictureUploadButton"></i>
    							<div style="width:250px;display:none; position:absolute;z-index: 9999;top:30px;" class="expression ">
    							<?php foreach($sm['expression'] as $k=>$v){
    									echo "<div class='picks' id='[@F_".($k+1)."@]' style='background:#fff;width:24px;height:24px; float:left; border:1px solid #CECECE;cursor:pointer;'><img style='margin-left:0;vertical-align: top;' src='/theme/default/img/gif/F_".($k+1).".gif' title='".$v."' /></div>";
    								}?>
    								<div style="clear:both"></div>
    							</div>
    						<div id="pictureFile" style="display:none;width:auto;position: absolute;background:#ffffff;z-index:9999;top:30px;" name="upfile">
    							<div id="pictureFilebox" name="upfile" style="width:100%;background:#fff;border:1px solid #ccc;">
    								<span style="cursor: pointer; height: 24px;float:right;top: 5px; width: 24px;"><img src="/theme/default/img/btn_close_img.png"/></span>
    								<div id="pictureFileName"  name="upfile" style="clear: both;text-align:center;padding:10px 10px 15px 10px;" ><input id="uppic" type="file" name="upfile" onchange="filesize(this,'uppic')" /></div>
    							</div>
    						</div>
    						</a>
    					</div>
                        <div class="am-cf"></div>
                     </div>
                     <div class="am-u-lg-4 am-u-sm-6 am-text-right">
                        <?php if(!empty($_SESSION['User']['User']['id']) && isset($UserApp_list) && sizeof($UserApp_list)>0){?>
                        <div class="func">
                            <span class="tongbu"><span style="font-size:11px;font-family:verdana;position:relative;"><?php echo $ld['simultaneously_published_to'] ?></span>
                            <?php 	foreach($UserApp_list as $k=>$v){ ?>
                            <a style="margin-left:1px;cursor: pointer;position:relative;"  onclick="checktoken('<?php echo $v['UserApp']['type']; ?>')" href="javascript:void(0);"><img id="<?php echo $v['UserApp']['type']; ?>_icon" src="/theme/default/img/<?php echo $v['img'] ?>" style="width:16px;height:16px;<?php echo $v['status']=='0'?'filter: Alpha(opacity=10);-moz-opacity:.1;opacity:0.3;':''; ?>" /></a>
                            <?php } ?>
                            </span>
                        </div>
                        <?php }?>
                        <div class="am-cf"></div>
                     </div>
                     <div class="am-u-lg-4 am-u-sm-12 am-text-right">
                        <div class="comm_is_public">
    						<input type="radio" name="data[Comment][is_public]" value="0" checked /><span><?php echo $ld['public'];?></span>
    						<input type="radio" name="data[Comment][is_public]" value="1" /><span><?php echo $ld['anonymity'];?></span>
    					</div>
                        <div class="am-cf"></div>
                     </div>
                     <div class="am-cf"></div>
                </div>
				<div class="ds-post-toolbar">
				  <div class="ds-post-options ds-gradient-bg">
					<?php if(isset($configs['comment_captcha'])&&$configs['comment_captcha']=='1'){ ?>
					<div class="authentication">
						<img id='authnum_comment' align='absmiddle' src="/securimages/index/?1234" alt="<?php echo $ld['not_clear']?>" title="<?php echo $ld['not_clear']?>" onclick="change_captcha('authnum_comment');" />
					</div>
					<div class="am-form-icon am-form-feedback">
						<input type="hidden" id="ck_authnum" value="" />
						<input type="text" style="width:65px;border: 1px solid #ccc;height:20px;padding-left: 0.5em !important;padding-right:0em !important;" class="am-form-field" name="data[Comment][authnum]" id="authnums_Comment" /><span style="line-height:0;right: 0;"></span>
					</div>
					<div class="nane" style="float:right;width:42px;height:23px;padding:8px 0 0 0px;"><?php echo $ld['verify_code'] ?> </div>
					<?php } ?>
				  </div>
				  <button id="res_btn" class="ds-post-button" type="button" onclick="add_comment()"><?php echo $ld['comment'] ?></button>
				  <div class="ds-toolbar-buttons">
				  </div>
				</div>
			  </div>
			</div>
			
			</form>
			<?php }else{?>
			  <?php if(empty($_SESSION['User']['User']['id'])){echo $ld['please_login']." <a href='javascript:void(0)' onclick='ajax_login_show();'>".$ld['login']."</a> ".$ld['perhaps']." <a href='javascript:void(0)' onclick='ajax_login_show();'>".$ld['register']."</a>";}else{?>
				<p><?php //echo $ld['please_buy_before_comment'];?></p>
			  <?php }?>
			<?php }?>
		</div>
		
		<div class="am-comments-list am-comments-list-flip" id="product_comments">
            
            <div class="am-list-news-bd">
                <ul id="maodian" class="am-comments-list am-comments-list-flip events-list">
                </ul>
            </div>
            <div class="pull-action pull-up am-hide am-text-center am-btn-block am-btn-default" style="cursor: pointer;">More...</div>
		</div>
		<?php }?>
	  </div>
	</div>
  </div>
</div>

<script type="text/javascript">
var j_no_comments="<?php echo $ld['no_comments']; ?>";
//评论分享
function checktoken(type){
	$.ajax({ url: "/synchros/checktoken/"+type,
		dataType:"json",
		type:"POST",
		success: function(data){
			if(data.flag==0){
				window.location.href='/synchros/opauth/'+type;
			}else if(data.status=='1'){
				$("#"+type+"_icon").attr("style","");
			}else if(data.status=='0'){
				$("#"+type+"_icon").attr("style","filter: Alpha(opacity=10);-moz-opacity:.1;opacity:0.3;");
			}
	    }
	});
}
//显示表情框
var clicks = true;
$("#biaoqing").click(function(){
	if($(".expression").css("display")=="block"){
		$(".expression").css("display","none");
	}
	else{
		$(".expression").css("display","block");
		clicks=false;
	}
});
document.body.onclick = function(){
    if(clicks){
       	$(".expression").css("display","none");
    }
    clicks = true;
}
//显示上传图片框
$("#pictureUploadButton").click(function(){
	$("#pictureFile").css("display","block");
});
var dobj=$("#pictureFile");
$(document).mousedown(function(event){
  	if(event.target.name!=$(dobj).attr("name")){
		$(dobj).hide(100);
 	}
});
$("#pictureFilebox span").click(function(){
	$("#pictureFile").css("display","none");
});
//选中表情事件
$(".picks").click(function(){
	var ids=$(this).attr("id");
	var titles=$(this).children().attr("title");
	if($("#contenttext").val()==""){
		$("#contenttext").val(titles);
		$("#contenttext").html(titles);
		//strLenCalc($(".input_detail"),'checklen',280);
	}
	else{
		$("#contenttext").val($("#contenttext").val()+titles);
		$("#contenttext").html($("#contenttext").val()+titles);
	}
});
//检测评论字数
function strLenCalc(obj, checklen, maxlen) {
	var v = obj.val(), charlen = 0, maxlen = !maxlen ? 200 : maxlen, curlen = maxlen, len = v.length;
	for(var i = 0; i < v.length; i++) {
		if(v.charCodeAt(i) < 0 || v.charCodeAt(i) > 255) {
			curlen -= 1;
		}
	}
	if(curlen >= len) {
		$("#"+checklen).html(Math.floor((curlen-len)/2)).css('color', '#000000');
		if(img_size1){
			$("#res_btn").bind("click",add_comment);
		}else{
			$("#res_btn").unbind("click",add_comment);
		}
	} else {
		$("#"+checklen).html(Math.ceil((len-curlen)/2)).css('color', '#FF0000');
		$("#res_btn").unbind("click",add_comment);
	}
}
var Url="/theme/default/img/gif/";//表情图片路径
//表情数组
var Expression=new Array("/微笑","/撇嘴","/好色","/发呆","/得意","/流泪","/害羞","/睡觉","/尴尬","/呲牙","/惊讶","/冷汗","/抓狂","/偷笑","/可爱","/傲慢","/犯困","/流汗","/大兵","/咒骂","/折磨/","/衰","/擦汗","/抠鼻","/鼓掌","/坏笑","/左哼哼","/右哼哼","/鄙视","/委屈","/阴险","/亲亲","/可怜","/爱情","/飞吻","/怄火","/回头","/献吻","/左太极");
//多次替换
String.prototype.replaceAll = function (findText, repText){
    var newRegExp = new RegExp(findText, 'gm');
    return this.replace(newRegExp, repText);
}
//表情文字替换
function replace_content(con){
	var content="";
	for(var i=0;i<Expression.length;i++){
		content = con.replaceAll(Expression[i], "<img src=" + Url + "F_"+(i+1)+".gif />");
	}
	return content;
}
var img_size1=true;
function filesize(target,obj) {
	var max_file_size=2000;
	var isIE = /msie/i.test(navigator.userAgent) && !window.opera;
	try{
		if(lastname(obj)){
			var fileSize = 0; 
			if (isIE && !target.files) { 
				var filePath = target.value; 
				var fileSystem = new ActiveXObject("Scripting.FileSystemObject"); 
				var file = fileSystem.GetFile (filePath); 
				fileSize = file.Size; 
			} else { 
				fileSize = target.files[0].size; 
			} 
			var size = fileSize / 1024; 
			if(size>max_file_size){
				alert("图片最大限制2M");
				img_size1=false;
			}else{
				img_size1=true;
			}
		}
	}catch(e){
		img_size1=false;
		alert("请将工具 -- internet选项 -- 安全 -- 自定义级别对未标记为可安全执行脚本的activex空间初始化并执行脚本  设置为启用");
	}
}
function lastname(obj){ 
	var filepath = document.getElementById(obj).value;  
	var re = /(\\+)/g; 
	var filename=filepath.replace(re,"#");
	//对路径字符串进行剪切截取
	var one=filename.split("#");
	//获取数组中最后一个，即文件名
	var two=one[one.length-1];
	//再对文件名进行截取，以取得后缀名
	var three=two.split(".");
	 //获取截取的最后一个字符串，即为后缀名
	var last=three[three.length-1];
	//添加需要判断的后缀名类型
	var tp = "jpg、jpeg、gif、png、JPG、JPEG、GIF、PNG";
	var rs=tp.indexOf(last);
	if(rs>=0){
	 return true;
	 }else{
	 	alert("文件格式错误");
	 	document.getElementById(obj).value = "";
	 	return false;
	  }
}
function add_comment(){
	if($("#contenttext").val()==""){
		alert("<?php echo $ld['comment_content_empty']?>");
		return false;
	}else{
		//判断是否含有屏蔽的词
		var word=$("#word").val();
		var con=CheckKeyword(word,$("#contenttext").val());
		con=replace_content(con);
		$("#hid").val(con);
		if(img_size1){
			var authnum_msg="Error";
			//var authnum_msg_div=$("#authnums_Comment").parent().parent().parent().find(".authnum_msg");
			var authnum_val=$("#authnums_Comment").val().trim();
			var ck_auth_num=$("#authnums_Comment").parent().parent().find("input[id=ck_authnum]").length;
			
			if(authnum_val.length==0){
				$("#authnums_Comment").parent().removeClass("am-form-success");
				$("#authnums_Comment").parent().removeClass("am-form-error");
				$("#authnums_Comment").parent().addClass("am-form-warning");
				$("#authnums_Comment").parent().find("span").removeClass("am-icon-times").removeClass("am-icon-check");
				$("#authnums_Comment").parent().find("span").addClass("am-icon-warning").css("display","block");
			}else if(ck_auth_num>0){
				var ck_auth=$("#authnums_Comment").parent().parent().find("input[id=ck_authnum]").val();
				if(ck_auth.trim().length>0){
					if(authnum_val.toLowerCase()!=ck_auth){
		    			$("#authnums_Comment").parent().removeClass("am-form-success");
		    			$("#authnums_Comment").parent().removeClass("am-form-warning");
		    			$("#authnums_Comment").parent().addClass("am-form-error");
		    			$("#authnums_Comment").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-check");
		    			$("#authnums_Comment").parent().find("span").addClass("am-icon-times").css("display","block");
					}else{
		    			$("#authnums_Comment").parent().removeClass("am-form-error");
		    			$("#authnums_Comment").parent().removeClass("am-form-warning");
		    			$("#authnums_Comment").parent().addClass("am-form-success");
		    			$("#authnums_Comment").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-times");
		    			$("#authnums_Comment").parent().find("span").addClass("am-icon-check").css("display","block");
						authnum_msg="";
					}
				}
			}
			if(authnum_msg==""){
				$("#comment_form").submit();
			}
			
		}
	}
}
//评论验证码
change_captcha('authnum_comment',true);
$("#authnums_Comment").blur(function(){
	var authnum_msg="Error";
	//var authnum_msg_div=$("#authnums_Comment").parent().parent().parent().find(".authnum_msg");
	var authnum_val=$("#authnums_Comment").val().trim();
	var ck_auth_num=$("#authnums_Comment").parent().parent().find("input[id=ck_authnum]").length;
	if(authnum_val.length==0){
		$("#authnums_Comment").parent().removeClass("am-form-success");
		$("#authnums_Comment").parent().removeClass("am-form-error");
		$("#authnums_Comment").parent().addClass("am-form-warning");
		$("#authnums_Comment").parent().find("span").removeClass("am-icon-times").removeClass("am-icon-check");
		$("#authnums_Comment").parent().find("span").addClass("am-icon-warning").css("display","block");
	}else if(ck_auth_num>0){
		var ck_auth=$("#authnums_Comment").parent().parent().find("input[id=ck_authnum]").val();
		if(ck_auth.trim().length>0){
			if(authnum_val.toLowerCase()!=ck_auth){
    			$("#authnums_Comment").parent().removeClass("am-form-success");
    			$("#authnums_Comment").parent().removeClass("am-form-warning");
    			$("#authnums_Comment").parent().addClass("am-form-error");
    			$("#authnums_Comment").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-check");
    			$("#authnums_Comment").parent().find("span").addClass("am-icon-times").css("display","block");
			}else{
    			$("#authnums_Comment").parent().removeClass("am-form-error");
    			$("#authnums_Comment").parent().removeClass("am-form-warning");
    			$("#authnums_Comment").parent().addClass("am-form-success");
    			$("#authnums_Comment").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-times");
    			$("#authnums_Comment").parent().find("span").addClass("am-icon-check").css("display","block");
				authnum_msg="";
			}
		}
	}
});

//商品评论ajax加载
(function($) {
    var EventsList = function(element, options) {
      var $main = $('#product_comments');
      var $list = $main.find('.events-list');
      var $pullDown = $main.find('.pull-down');
      var $pullDownLabel = $main.find('.pull-down-label');
      var $pullUp = $main.find('.pull-up');
      var topOffset = -$pullDown.outerHeight();

      this.compiler = Handlebars.compile($('#tpi-list-item').html());
      this.prev = this.next = this.start = options.params.start;
      this.total = null;

      this.getURL = function(params) {
        var queries = ['callback=?'];
        for (var key in  params) {
          if (key !== 'start') {
            queries.push(key + '=' + params[key]);
          }
        }
        queries.push('start=');
        return options.api + '?' + queries.join('&');
      };

      this.renderList = function(start, type) {
        var _this = this;
        var $el = $pullDown;

        if (type === 'load') {
          $el = $pullUp;
        }

        $.getJSON(this.URL + start).then(function(data) {
          console.log(data);
          _this.total = data.total;
          if(data.total==0){
          	  $("#product_comments").html(j_no_comments);
          }else if(data.total<=5){
          	$($pullUp).remove();
          }else{
          	$($pullUp).removeClass("am-hide");
          }
          var html = _this.compiler(data.events);
          if (type === 'refresh') {
              console.log(type);
            $list.children('li').first().before(html);
          } else if (type === 'load') {
            $list.append(html);
          } else {
            $list.html(html);
          }

          // refresh iScroll
          setTimeout(function() {
            //_this.iScroll.refresh();
          }, 100);
        }, function() {
          console.log('Error...')
        }).always(function() {
          _this.resetLoading($el);
          if (type !== 'load') {
            //_this.iScroll.scrollTo(0, topOffset, 800, $.AMUI.iScroll.utils.circular);
          }
        });
      };

      this.setLoading = function($el) {
        $el.addClass('loading');
      };

      this.resetLoading = function($el) {
        $el.removeClass('loading');
      };

      this.init = function() {
        var _this = this;
        var pullFormTop = false;
        var pullStart;

        this.URL = this.getURL(options.params);
        this.renderList(options.params.start);
        
        $pullUp.on('click',function(){
          setTimeout(function() {
            _this.handlePullUp();
          }, 100);
        });
      };

      this.handlePullDown = function() {
        console.log('handle pull down');
        if (this.prev > 0) {
          this.setLoading($pullDown);
          this.prev -= options.params.count;
          this.renderList(this.prev, 'refresh');
        } else {
            $($pullUp).remove();
            console.log('End');
        }
      };

      this.handlePullUp = function() {
        console.log('handle pull up');
        if (this.next < this.total) {
          this.setLoading($pullUp);
          this.next += options.params.count;
          this.renderList(this.next, 'load');
        } else {
            $($pullUp).remove();
            console.log(this.next);
        }
      }
    };

    $(function() {
      var app = new EventsList(null, {
        api: '/products/ajax_product_comment',
        params: {
          product_id:$("#product_id").val(),
          start: 1,
          count: 5
        }
      });
      app.init();
    });

//    document.addEventListener('touchmove', function(e) {
//      e.preventDefault();
//    }, false);
  })(window.jQuery);
  
</script>
<script type="text/x-handlebars-template" id="tpi-list-item">
  {{#each this}}
  <li class="am-comment">
	<a  href="javascript:void(0);">
	  <img title="{{user_name}}" src="{{user_img}}" alt="{{user_name}}" class="am-comment-avatar" width="48" height="48"/>
	</a>
	<div class="am-comment-main">
      <header class="am-comment-hd">
    	<div class="am-comment-meta">
          <a href="javascript:void(0);" class="am-comment-author">{{user_name}}</a>
          评论于 <time>{{created}}</time>
    	</div>
      </header>
      <div class="am-comment-bd">
		{{content}}
        
        {{#if img}}
        <figure data-am-widget="figure" class="am-figure am-figure-default am-u-lg-4" data-am-figure="{ pureview: 'auto'}">
	  	  <img width="100%" src="{{img}}" data-rel="{{img}}" />
	  	</figure>
        {{/if}}
	  </div>
  	</div>
  </li>
  {{/each}}
</script>
<?php }?>