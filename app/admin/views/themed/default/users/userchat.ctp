<style>.am-form-label{font-weight:bold; magrin-top:-5px; left:20px;}</style>
<div class="listsearch">
    <?php echo $form->create('User',array('action'=>'/','name'=>"SearchForm",'id'=>"SearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3"  >
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-form-label"><?php echo $ld['sender'] ?></label>
            <div class="am-u-sm-7 am-u-lg-7 am-u-md-7 " style="padding:0 0.5rem; ">
                <input type="text" name="sender" id="sender" value="<?php echo @$sender?>"/>
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-lg-3 am-u-md-3  am-form-label"><?php echo $ld['recipient'] ?></label>
            <div class="am-u-sm-7 am-u-lg-7 am-u-md-7"  >
                <input type="text" name="receiver" id="receiver" value="<?php echo @$receiver?>"/>
            </div>
        </li>
    <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-form-label"><?php echo $ld['create_time'] ?></label>
            <div class="am-u-sm-3 am-u-lg-3 am-u-md-3 "  >
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date" value="" />
            </div>
            <em class=" am-text-center am-u-sm-1 am-u-lg-1 am-u-md-1 " style="padding: 0.35em 0px;">-</em>
            <div class="am-u-sm-3 am-u-lg-3  am-u-md-3  am-u-end"  >
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date" value="" />
            </div>
        </li>
         <li style="margin:0 0 10px 0">
           <label class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-form-label"> </label>
            <div class="am-u-sm-7 am-u-lg-7 " style="padding:0 0.5rem; ">
                         <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
            </div>

         </li>		
    </ul>
    <?php echo $form->end();?>
</div>
<?php echo $form->create('',array('action'=>'/batch_user_print/',"name"=>"UserForm",'onsubmit'=>"return false"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table id="t1" class="am-table  table-main">
        <thead>
        <tr>
            <th ><label style="margin:0 0 0 0;" class="am-checkbox am-success">
               <span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span>
             <b><?php echo $ld['sender'] ?></b></label></th>
            <th><?php echo $ld['recipient'] ?></th>
            <th style="word-wrap: break-word; "class="am-hide-sm-down"><?php echo $ld['because_of_the_content'] ?></th>
            <th class="thwrap am-hide-sm-down"><?php echo $ld['create_time'] ?></th>
            <th style="width:100px"><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($chat_list) && sizeof($chat_list)>0){foreach($chat_list as $k=>$v){?>
            <tr>
                <td >
                	<label style="margin:0 0 0 0;" class="am-checkbox am-success">  <span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['UserChat']['id']?>" /></span>
                    <?php echo isset($v['UserChat']['user_name'])?$v['UserChat']['user_name']:"";?>
                </td>
                <td>
                    <?php echo isset($v['UserChat']['to_user_name'])?$v['UserChat']['to_user_name']:"";?>
                </td>
                <td class="am-hide-sm-down"><?php echo $v['UserChat']['content']?></td>
                <td class="thwrap am-hide-sm-down"><?php echo $v['UserChat']['created']?></td>
                <td><?php

                    if($svshow->operator_privilege("userchat_remove")){ 
                        echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-default am-text-danger am-btn-xs am-radius","onclick"=>"if(confirm('".$ld['confirm_delete']."')){list_delete_submit('{$admin_webroot}users/removechat/{$v['UserChat']['id']}');}"));
                 
                    } ?></td>
            </tr>
        <?php }}else{?>
            <tr>
                <td  colspan="5" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if(isset($chat_list) && sizeof($chat_list)){?>
        <div id="btnouterlist" class="btnouterlist">
	            <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-hide-sm-only" style="margin:0 0 0 -10px;">
	        
	                <div class="am-u-lg-2 am-u-md-3 am-u-sm-2 am-fl"><label style="margin:5px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label></div>
	                <div class=" am-u-lg-5 am-u-md-5 am-u-sm-2 am-fl">
	                	<select id="select_type" data-am-selected>
	                    <option value=""><?php echo $ld['please_select']?></option>
	                    <?php if($svshow->operator_privilege("userchat_remove")){?>
	                        <option value="operation_delete"><?php echo $ld['batch_delete']?></option>
	                    <?php }?>
	                </select>
	                </div>
	                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-u-end"><input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" value="<?php echo $ld['submit']?>" onclick="submit_operations()" /></div>
	              </div>
	            		
	           <div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
            <?php echo $this->element('pagers')?>
              </div>
             <div class="am-cf"></div>
        </div>
    <?php }?>
</div>
<?php echo $form->end();?>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">档案分类
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <form id='placementform3' method="POST">
                <table class="am-table">
                    <tr>
                        <th rowspan="2">选择分类：</th>
                    </tr>
                    <tr>
                        <td>
                            <select name="profilegroup" id="profilegroup" >
                                <option value="0">请选择分类</option>
                            </select><em>*</em>
                        </td>
                    </tr>
                </table>
                <input type="button" name="changeprofileButton" value="<?php echo $ld['confirm']?>" onclick="javascript:changeprofile();">
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function formsubmit(){
        var sender=document.getElementById('sender').value;
        var receiver=document.getElementById('receiver').value;
        var start_date = document.getElementsByName('start_date')[0].value;
        var end_date = document.getElementsByName('end_date')[0].value;
        var str = '';
        var url = "sender="+sender+"&receiver="+receiver+"&start_date="+start_date+"&end_date="+end_date+str;
        window.location.href = encodeURI(admin_webroot+"users/userchat?"+url);
    }

    function submit_operations(){
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var opration_select_type = document.getElementById("select_type").value;
        if(opration_select_type==''){
            alert(j_select_operation_type+" !");
            return;
        }
        var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                checkboxes.push(bratch_operat_check[i].value);
            }
        }
        if(opration_select_type=='operation_delete'&&checkboxes=="" ){
            alert(j_select_user);
            return;
        }
        if(opration_select_type=='operation_delete'){
            if(confirm("<?php echo $ld['confirm_delete']; ?>")){
                var sUrl = admin_webroot+"user_likes/removeAll";//访问的URL地址
                $.ajax({
                    type: "POST",
                    url: sUrl,
                    dataType: 'json',
                    data: {checkboxes:checkboxes},
                    success: function (result) {
                        if(result.flag==1){
                            window.location.href = window.location.href;
                        }
                    }
                });
            }
        }
    }

    function search_result(){
        var form=document.getElementById('SearchForm');
        form.action='/admin/users/index/?email_flag=1';
        form.method="post";
        form.submit();
    }

    //绑定下拉
    function strbind(arr){
        //先清空下拉中的值
        var profilegroup=document.getElementById("profilegroup");
        for(var i=0;i <profilegroup.options.length;)
        {
            profilegroup.removeChild(profilegroup.options[i]);
        }
        var optiondefault=document.createElement("option");
        profilegroup.appendChild(optiondefault);
        optiondefault.value="0";
        optiondefault.text="请选择分类";
        for(var i=0;i<arr.length;i++){
            var option=document.createElement("option");
            profilegroup.appendChild(option);
            option.value=arr[i]['Profile']['code'];
            option.text=arr[i]['Profile']['name'];
        }
    }

    //弹窗
    function popup(id){
        if(!document.getElementById("popup")){
            var popcontent=document.createElement('div');
            popcontent.id='popup';
            popcontent.className='popup';
            document.body.appendChild(popcontent);
        }
        var popcontent=document.getElementById("popup");
        if(arguments.length==0){popcontent.style.display="block";return;}
        var idPop=document.getElementById(id);idPop.style.display="block";
        if(arguments.length>=1){
            if(!idPop.getElementsByTagName("span")[0]||idPop.getElementsByTagName("span")[0].className!="closebtn"){
                var popCloseBtn=document.createElement("span");
                popCloseBtn.className="closebtn";
                popCloseBtn.innerHTML="×";
                idPop.insertBefore(popCloseBtn,idPop.firstChild);
            }
        }
        if(document.getElementById(id).parentNode.id!="popup"){
            var tmp=outerHTML(idPop);
            idPop.parentNode.removeChild(idPop);
            popcontent.innerHTML+=tmp;
        }
        if(arguments.length>=1){
            if(document.getElementById(id).firstChild.onclick==null){
                document.getElementById(id).firstChild.onclick=function click(event){
                    btnClose1();
                };
            }
        }
        popcontent.style.display="block";
    }

    //关闭弹窗
    function btnClose1(){
        var popcontent=document.getElementById("popup");popcontent.style.display="none";var 		popdiv=popcontent.firstChild;popdiv.style.display="none";while(popdiv.nextSibling){var popdiv=popcontent.nextSibling;popdiv.style.display="none";}
    }

    //修改档案分类导出
    function changeprofile(){
        var select_type = document.getElementById("select_type");
        var code=document.getElementById("profilegroup").value;
        if(code==0){
            alert("请选择导出方式");
            return false;
        }
        var strsel = select_type.options[select_type.selectedIndex].text;
        if(confirm(confirm_exports+" "+strsel+"？")){
            if(select_type.value=='search_result'){
                search_result(code);
            }else if(select_type.value=='export_act'){
                export_act(code);
            }
        }
        btnClose1();
    }

    function export_act(code){
        document.UserForm.action=admin_webroot+"users/export_act/"+code;
        document.UserForm.onsubmit= "";
        document.UserForm.submit();
    }
</script>