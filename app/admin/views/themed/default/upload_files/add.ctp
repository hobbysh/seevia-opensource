<p class="am-u-md-12" style="margin:10px 0 0 0"><?php echo $html->link($ld['file_ma'],"/upload_files/",array("class"=>"am-btn am-btn-warning am-radius am-btn-sm am-fr"),false,false);?></p>
<?php echo $form->create('upload_files',array('action'=>'/add/','name'=>"theForm","enctype"=>"multipart/form-data","onsubmit"=>"return checkfile();"));?>
<input type="hidden" id="is_upload" value="" />
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#uplaod"><?php echo $ld['file_uplaod']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="uplaod" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['file_uplaod']?>
            </h4>
        </div>
        <div id="file_uplaod" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th  ><?php echo $ld['file_uplaod']?></th>
                        <td><input type="file" style="width:306px" size="40" name="data[Document][file_url]" id="data[Document][file_url]" value="" onchange="fileChange(this,'data[Document][file_url]')"/>
                            <p>
                                <?php echo $ld['file_optional_format']?>:<?php echo empty($file_types)? '':$file_types; ?>
                            </p>
                            <p>
                                <?php echo $ld['single_size_exceed']?>
                            </p>
                        </td>
                        
                    </tr>
                     <tr> 
                        <th><lable class="am-u-lg-6 am-form-label" style="padding-top:10px;">文件名称</lable></th>
                        <td class="am-u-lg-6"><input  type="text" name="file_nick" placeholder="自定义文件名称"/></td>
                    </tr>
                    <tr>
                     <th><lable class="am-u-lg-6 am-form-label" >排序</lable></th>
                        <td class="am-u-lg-6"><input placeholder="排序" name="file_sort" type="text"/></td>
                    </tr>
                </table>
                <div class="btnouter"><input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" /></div>
            </div>
        </div>
    </div>
</div>
<?php $form->end();?>
<script type="text/javascript">
    var max_file_size=10000;
    var isIE = /msie/i.test(navigator.userAgent) && !window.opera;
    function fileChange(target,obj) {
        try{
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

    function show_intro(url){
        window.location.href=admin_webroot+"upload_files/add/"+url;
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
//	var tp ="ico sql";
//	alert(filename);alert(tp);alert(last);
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

    function checkfile(){
        var is_upload=document.getElementById("is_upload").value;
        if(is_upload=="1"){
            return true;
        }else if(is_upload==""){
            alert("<?php echo $ld['file_can_not_empty'] ?>");
            return false;
        }else if(is_upload=="0"){
            alert("<?php echo $ld['file_attachment_size_exceeds'] ?>");
            return false;
        }
    }
</script>