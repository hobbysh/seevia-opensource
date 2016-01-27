<?php
/*****************************************************************************
 * SV-Cart 在线管理新增
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
?>
 
<?php echo $form->create('upload_files',array('action'=>'/edit/'.$uploadfiles["Document"]["id"],'onsubmit'=>'return files_check();',"enctype"=>"multipart/form-data"));?>
<input type="hidden" id="is_upload" value="" />
	<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" >
	    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
	        <li><a href="#tablemain"><?php echo $ld['basic_information']?></a></li>
	    </ul>
	</div>
<div class="am-panel-group   am-u-lg-9 am-u-md-9 am-u-sm-9  am-fr" id="accordion"  >
    <div id="tablemain" class="am-panel am-panel-default">
	        <div class="am-panel-hd">
	            <h4 class="am-panel-title">
	                <?php echo $ld['basic_information']?>
	            </h4>
	        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal"  >
                
                   <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['file_name']?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end"><div>
                             <input type="text"  id="file_name" name="data[Document][name]" value="<?php echo $uploadfiles['Document']['name'];?>"  />
                            <input type="hidden" id="file_id"  name="data[Document][id]" value="<?php echo $uploadfiles['Document']['id']?>" />
                            <input type="hidden" id="file_path"  name="data[Document][file_path]" value="<?php echo $uploadfiles['Document']['file_path']?>" />
                            <input type="hidden" id="filename"  name="filename" value="<?php echo $filename?>" /></div>
                         </div>
                    </div>

                   <div class="am-form-group" style="margin-top:10px;">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['file_size']?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end"> <?php echo  ceil($uploadfiles['Document']['file_size']/1024);?> KB </div>
                    </div>
                   <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3  "><?php echo $ld['type']?></label> 
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end"> <?php echo $uploadfiles['Document']['type'];?>  </div>
                    </div>
                   <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['file_fath']?></label>
                        <div><div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end"><input type="text"   readonly='readonly' id="file_url" name="data[Document][file_url]" value="<?php echo $uploadfiles['Document']['file_url'];?>"  />
                           </div> </div>
 		  </div>
			 <div class="am-form-group">
				<label  class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-view-label"> </label> 
				    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end" ><?php if($svshow->operator_privilege("upload_files_mgt")){?>
                                        <a  class="am-btn am-btn-default am-btn-xs" href="javascript:;"   data-am-Modal="{target:'#tip-copy1',closeViaDimmer: 0,width:400,height:200}"  onclick="photo_copy(event,'<?php echo $uploadfiles['Document']['file_url'];?>')"><?php echo $ld['copy']?>
                                       </a>
                                <?php }?> 
                            </div>
			</div>
 
                    
                   <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['sort']?></label>
                                 <div><div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end"> 
                             <input type="text"  name="data[Document][orderby]" value="<?php echo $uploadfiles['Document']['orderby']; ?>" onkeyup="check_input_num(this)" /> 
                            <p class="msg"><?php echo $ld['role_sort_default_num']?></p>
                         </div></div>
                    </div>
                   <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['posted_time']?></label>
                         <div><div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end">
                      <input type="text"  id="created" readonly='readonly'  data-am-datepicker name="data[Document][created]" value="<?php echo $uploadfiles['Document']['created'];?>"  />
                        </div></div>
                    </div>
                   <div class="am-form-group" style="margin-top:10px;">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3  "><?php echo $ld['replace_file']?></label>
	                         	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end">
	                            <input type="file" style="width:306px;" size="40" name="data[upload_file]" id="data[upload_file]" value="" onchange="fileChange(this,'data[upload_file]')"/>
	                            <p>
	                                <?php echo $ld['file_optional_format']?>:<?php echo empty($file_types)? '':$file_types; ?>
	                            </p>
	                            <p>
	                                <?php echo $ld['single_size_exceed']?>
	                            </p>
	                        </div> 
                    </div>
              
                <div class="btnouter">
                    <input type="submit" value="<?php echo $ld['d_submit']?>" class="am-btn am-btn-success am-radius am-btn-sm" /> <input class="am-btn am-btn-success  am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
</div>
								  <!-------ID--tip-copy1---->
			<div class="am-modal am-modal-no-btn" tabindex="-1" id="tip-copy1">
			<div class="am-modal-dialog">
			<div class="am-modal-hd"><input type="text" value="<?php echo $uploadfiles['Document']['file_url'];?>"/>
			<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
			</div>
			<div class="am-modal-bd">
			浏览器不支持 请手动 Copy 一下吧！
			</div>
			</div>
			</div>
                    <!------------------> 
<?php echo $form->end();?>
<script type="text/javascript">
    //复制
    function photo_copy(ev,src){
        if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){//alert('jjjj');
            var event= ev || window.event;
            var div = document.getElementById('tip-copy1');
            div.className=div.className.replace("hidden"," ");
            div.style.left = event.clientX - 100 + 'px';
            div.style.top = (document.documentElement.scrollTop + event.clientY) + 'px';
            document.getElementById('tip-copy1-text').value = src;
        }
        else{
            window.clipboardData.setData("Text",src);
            alert(j_replicate_successfully);
        }
    }

    function files_check(){
        if(document.getElementById('file_name').value==''){
            alert("<?php echo $ld['file_name_not_empty']?>");
            return false;
        }
        var is_upload=document.getElementById("is_upload").value;
        if(is_upload=="0"){
            alert("<?php echo $ld['file_attachment_size_exceeds'] ?>");
            return false;
        }
        return true;
    }

    function show_intro(url) {
        window.location.href=admin_webroot+"upload_files/edit/"+url;
    }

    function checkUploadFile(arg,obj){
        var fileName;
        var fileSize;
        var bro = getBroswerType();
        if(bro.firefox){
            fileSize = arg.files.item(0).fileSize
        }else if(bro.ie){
////		var fso = new ActiveXObject();
////		var xml=new ActiveXObject("Msxml2.XMLHTTP");
//		var fso = new ActiveXObject("Scripting.FileSystemObject");
//		//fileSize =fso.GetFile(arg.value).size;
//
//
//		alert(fso);
        }
        if(lastname(obj))
        {
            if(fileSize > 500*1024){
                arg.value="";
                alert("<?php echo $ld['file_attachment_size_exceeds']?>");
            }
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
        var tp = "<?php echo empty($file_types)? '':$file_types; ?>";
//	var yfname =document.getElementById('file_name').value;
//	var yftype =document.getElementById('file_type').value;
        var yfn=document.getElementById('filename').value;
        if(one ==yfn){
            alert("<?php echo $ld['has_uploaded_upload_another']?>");
            document.getElementById(obj).value = "";
            document.getElementById(obj).outerHTML=document.getElementById(obj).outerHTML.replace(/(value=\").+\"/i,"$1\"");
            return false;
        }
        //返回符合条件的后缀名在字符串中的位置
        var rs=tp.indexOf(last);
        //如果返回的结果大于或等于0，说明包含允许上传的文件类型
        if(rs>=0){
            return true;
        }else{
            alert("<?php echo $ld['file_format_error']?>");
            document.getElementById(obj).value = "";
            //document.getElementById(obj).outerHTML=document.getElementById(obj).outerHTML.replace(/(value=\").+\"/i,"$1\"");
            return false;
        }
    }

    function getBroswerType(){
        var Sys = {};
        var ua = navigator.userAgent.toLowerCase();
        var s;
        (s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
            (s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
                (s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
                    (s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
                        (s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
        return Sys
    }

    function fileChange(target,obj) {
        try{
            var isIE = /msie/i.test(navigator.userAgent) && !window.opera;
            var max_file_size=10000;
            if(lastname(obj)){
                var is_upload=document.getElementById("is_upload");
                is_upload.value="";
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
                    alert("<?php echo $ld['file_attachment_size_exceeds']?>");
                    is_upload.value="0";
                }else{
                    is_upload.value="1";
                }
            }
        }catch(e){
            alert(ie_getfilesize_error);
        }
    }
</script>