<?php echo $htmlSeevia->css(array('embed.default'));?>
<!--屏蔽的关键字-->	
<?php $wordarr=""; if(isset($sm['word'])&&$sm['word']!=""){
	foreach($sm['word'] as $k=>$v){
		$wordarr.=$v['BlockWord']['word'].",";
	}
}?>



<input type="hidden" id="word" value="<?php echo $wordarr;?>">
<div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
  <div class="am-panel am-panel-default" style="margin-top:10px;">
	<div class="am-panel-hd my-head"><?php echo $code_infos[$sk]['name'];?></div>
	<div  class="am-panel-bd">
	  <div class="listbox am-padding-horizontal-sm" style="<?php echo isset($comment_condition)&&$comment_condition==0?'display:none':'';?>">
      <?php echo $form->create('/articles',array('action'=>'add_video_comment','id'=>'comment_form','name'=>'comment_form','type'=>'POST','enctype'=>'multipart/form-data'));?>
		<div id="comment_title"><?php echo $ld['comment'] ?></div>
		<div id="ds-thread">
		  <div id="ds-reset">
			<div class="ds-textarea-wrapper ds-rounded-top" >
			  <textarea id="contenttext" <?php if(empty($_SESSION['User']['User']['id'])&& isset($comment_condition) && $comment_condition==2){echo " disabled='disabled'";}?>></textarea>
			  <input type="hidden" name="data[Comment][content]" id="hid" value="" />
			  <input type="hidden" name="user_id" value="<?php if(!empty($_SESSION['User']['User']['id'])){echo $_SESSION['User']['User']['id'];}else{echo '0';}?>">
			  <input type="hidden" name="data[Comment][type_id]" value="<?php echo isset($sm['product_id'])?$sm['product_id']:"";?>">
			  <input type="hidden" name="rank" value="5">
			</div>
			<div class="ds-post-toolbar">
			  <div class="ds-post-options ds-gradient-bg">
				<span class="kind_detail">
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
				</span>
			  </div>
			  <button id="res_btn" class="ds-post-button" type="button" onclick="add_comment()"><?php echo $ld['comment'] ?></button>
			  <div class="ds-toolbar-buttons">
			  </div>
			</div>
		  </div>
		</div>
	  <?php echo $form->end();?>
	  </div>
	  <div id="shipin_pinglun">
		<ul class="am-comments-list am-comments-list-flip">
		<?php $i=0;if(!empty($sm['comment'])&&$sm['comment']!=1){foreach($sm['comment'] as $k=>$v){$i++; ?>
		  <li class="am-comment">
			<a target="_blank" title="<?php echo $v['User']['name'];?>" href="<?php echo $html->url('/user_socials/index/'.$v['Comment']['user_id']); ?>">
    		  <img src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=""?$v['User']['img01']:'/theme/default/img/no_head.png';?>" alt="<?php echo $v['User']['name'];?>" class="am-comment-avatar" width="48" height="48"/>
  			</a>
			<div class="am-comment-main">
		      <header class="am-comment-hd">
		    	<div class="am-comment-meta">
				  <input type="hidden" value="<?php echo $v['Comment']['id'];?>" id="comment_<?php echo $k;?>" />
		          <a href="#link-to-user" class="am-comment-author"><?php echo empty($v['User']['name'])?$ld['anonymity']:$v['User']['name']; ?></a>
		          发布于 <time><?php $time=explode(" ",$v['Comment']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
		    	</div>
		      </header>
		      <div class="am-comment-bd">
				<div class="am-fl"><?php echo $v['Comment']['content'];?></div>
			    <?php if(isset($v['Comment']['img']) && !empty($v['Comment']['img']) && file_exists(WWW_ROOT.$v['Comment']['img'])){?>
			  	<figure data-am-widget="figure" class="am am-figure am-figure-default am-u-lg-4 " data-am-figure="{pureview: 'auto'}">
  			  	  <img width="100%" src="<?php echo $v['Comment']['img']?>" data-rel="<?php echo $v['Comment']['img']?>" alt="" />
			  	</figure>
			  	<?php } ?>
			    <div class="WB_handle">
				  <a style="<?php if(empty($_SESSION['User']['User']['id'])){echo 'disabled:disabled';}?>" href="javascript:void(0);" class="reply" id="<?php echo 'reply'.($k)?>"><?php echo $ld['reply'] ?></a>
			  	</div>
			  	<div id="<?php echo 'answer'.($k);?>" style="display:none;">
				  <div id="ds-thread" >
  				  	<div id="ds-reset">
				      <div class="ds-replybox ds-inline-replybox " >
					  	<div class="ds-textarea-wrapper ds-rounded-top reply_answer">
					      <textarea id="reply_to_user_<?php echo $k;?>" name='answer'></textarea>
					  	</div>

					  	<div id="comments_button" class="ds-post-toolbar huifupinglun">
					      <div class="ds-post-options ds-gradient-bg"></div>
					      <button id="reply_to_user_buttom_<?php echo $k?>" class="ds-post-button reply_to_user" type="button">
							<?php echo $ld['reply_comments'] ?>
						  </button>
					  	</div>
				      </div>
				  	</div>
				  <div class="comments_list" id="comment_list_<?php echo $k; ?>">
					<ul class="am-comments-list am-comments-list-flip">
					  <?php  $comment=count($v['Reply']);$i=0; if($v['Reply']!=""){ foreach($v['Reply'] as $kk=>$vv){ $i++;?>
					  <li class="am-comment">
					    <a target="_blank" title="<?php echo $vv['User']['name'];?>" href="<?php echo $html->url('/user_socials/index/'.$vv['Comment']['user_id']); ?>">
						  <img src="<?php echo isset($vv['User']['img01'])&&$vv['User']['img01']!=""?$vv['User']['img01']:'/theme/default/img/no_head.png';?>" alt="<?php echo $vv['User']['name'];?>" class="am-comment-avatar" width="48" height="48"/>
					    </a>
						<div class="am-comment-main">
					      <header class="am-comment-hd">
					        <div class="am-comment-meta">
					          <a href="<?php echo $html->url('/user_socials/index/'.$vv['Comment']['user_id']); ?>" class="am-comment-author"><?php echo $vv['User']['name'];?></a>
					          评论于 <time><?php $time=explode(" ",$vv['Comment']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
					        </div>
					      </header>
						  <div class="am-comment-bd">
						    <?php echo $vv['Comment']['content'];?>
						  </div>
						</div>
					  </li>
					  <?php }}?>
					</ul>
				  </div>
			  	</div>
			  </div>
		  	</div>
		  </li>
		<?php }}?>
		</ul>
		<?php echo $this->element('pager'); ?>
	  </div>
	</div>
  </div>
</div>

<script>
    var user_dl = navigator.userAgent.toLowerCase();
    var llq;
    var llqbb = {};
//    (llq = user_dl.match(/msie ([\d.]+)/)) ? llqbb.ie = llq[1] :
//    (llq = user_dl.match(/firefox\/([\d.]+)/)) ? llqbb.firefox = llq[1] :
//    (llq = user_dl.match(/chrome\/([\d.]+)/)) ? llqbb.chrome = llq[1] :
//    (llq = user_dl.match(/opera.([\d.]+)/)) ? llqbb.opera = llq[1] :
    (llq = user_dl.match(/version\/([\d.]+).*safari/)) ? llqbb.safari = llq[1] : 0;
//    if (llqbb.ie) document.write('<style>CSS在这里</style>');
//    if (llqbb.firefox) document.write('<style>CSS在这里</style>');
//    if (llqbb.chrome) document.write('<style>CSS在这里</style>');
//    if (llqbb.opera) document.write('<style>CSS在这里</style>');
    if (llqbb.safari){$(".reply_answer").css("margin","margin:0 0 0 50px");} 
</script>
<script>
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
  		//alert($(dobj).attr("name"));
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
		if(img_size){
			$("#res_btn").bind("click",foo);
		}else{
			$("#res_btn").unbind("click",foo);
		}
	} else {
		$("#"+checklen).html(Math.ceil((len-curlen)/2)).css('color', '#FF0000');
		$("#res_btn").unbind("click",foo);
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
	for(var i=0;i<Expression.length;i++){
		con = con.replaceAll(Expression[i], "<img src=" + Url + "F_"+(i+1)+".gif />");
	}
	return con;
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
	//判断登录
	<?php if(empty($_SESSION['User']['User']['id']) && isset($comment_condition) && $comment_condition==2){?>
		$(".denglu").click();
	<?php }else{?>
	if($("#contenttext").val()==""){
		alert("<?php echo $ld['comment_content_empty']?>");
		return false;
	}else{
		//判断是否含有屏蔽的词
		var con=CheckKeyword(word,$("#contenttext").val());
		con=replace_content(con);
		$("#hid").val(con);
		if(img_size1){
			$("#comment_form").submit();
		}
	}
	<?php }?>
}
	//回复内容显示
	$(".reply").click(function(){
		
		var id=$(this).attr("id");
		id=id.replace("reply","answer");
		var comment_id=id.replace("answer","reply_to_user_buttom_");
		var comment_btn=id.replace("answer","reply_to_user_");
		//alert(comment_id+" "+comment_btn)
		if($("#"+id).css("display")=="none")
		{
			$("#"+id).css("display","block");
			$("#"+comment_id).parent().css("display","block");
			$("#"+comment_btn).parent().css("display","block");
		}
		else
		{
			$("#"+id).css("display","none");
			$("#"+comment_id).parent().css("display","none");
			$("#"+comment_btn).parent().css("display","none");
		}
	});	
	//回复评论功能
	$(".reply_to_user").click(function(){	
		//判断登录
		<?php if(empty($_SESSION['User']['User']['id'])){?>
			$(".denglu").click();
		<?php }else{?>		
		var id=$(this).attr("id");
		//var pic=id.replace("reply_to_user_buttom","uppic");
		//alert(($("#"+pic).val()));			
		var textid=id.replace("reply_to_user_buttom","reply_to_user");
		var text=$("#"+textid).val();
		var comment_list=id.replace("reply_to_user_buttom","comment_list");
		//alert(comment_list);
		//alert(text);
		if(text==""){
			alert("评论不能为空，写点什么吧!");
		}
		else{	
			//text=CheckKeyword(word,text);
//			text = text.replace(/\[@/g, "<img src=" + Url + "");
//			text = text.replace(/\@]/g, ".gif />");
			//alert(text);
			var comment=id.replace("reply_to_user_buttom","comment");
			var video_id=$("#video_id").val();
			//var img=$("#uppic").val();
			//alert(img);
			comment_id=$("#"+comment).val();
			$.ajax({ url: "/articles/reply_comment/",
	    		dataType:"json",
	    		type:"POST",
	    		context: $("#"+comment_list),
	    		data: { 'parent_id': comment_id, 'content': text },
	    		success: function(data){
				//alert(data.sum_quantity);
					$("#"+comment_list).html("");
	    			$("#"+comment_list).html(data.comment);
	    			$("#"+textid).val("");
	    			$("#"+id).parent().css("display","none");
	    			$("#"+textid).parent().css("display","none");
	    			//$("#"+pic).val("");
	  			}
	  		});
		}
		<?php }?>
	});
	
var scaleImage = function(o, w, h){
	var img = new Image();
	img.src = o.src;
	if(img.width >0 && img.height>0)
	{
	if(img.width/img.height >= w/h)
	{
		if(img.width > w)
		{
		o.width = w;
		o.height = (img.height*w) / img.width;
		}
		else
		{
		o.width = img.width;
		o.height = img.height;
		}
		o.alt = img.width + "x" + img.height;
	}
	else
	{
		if(img.height > h)
		{
		o.height = h;
		o.width = (img.width * h) / img.height;
		}
		else
		{
		o.width = img.width;
		o.height = img.height;
		}
		o.alt = img.width + "x" + img.height;
		}
	}
}

</script>