<style>
 .am-form-label{text-align:center;font-weight:bold;margin-left:20px;}
 </style>
<div class="listsearch">
    <?php echo $form->create('UserLike',array('action'=>'/','name'=>"SearchForm",'id'=>"SearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" >
        <li  style="margin-bottom:10px;">
            <label class="am-u-sm-3 am-u-md-3 am-u-lg-3 am-form-label"><?php echo $ld['user_name'] ?></label>
            <div class="am-u-sm-7 am-u-md-7 am-u-lg-7 " style="padding:0 0.5rem;">
                <input type="text" name="user" id="user" value="<?php echo @$user_keyword?>"/>
            </div>
        </li>
        <li  style="margin-bottom:10px;">
            <label class="am-u-sm-3  am-u-md-3 am-u-lg-3  am-form-label"><?php echo $ld['type'] ?></label>
            <div class="am-u-sm-7 am-u-md-7 am-u-lg-7 " style="padding:0 0.5rem;">
                <select name="action" data-am-selected >
                    <option value="0"><?php echo $ld['all_data'];?></option>
                    <option value="0-like" <?php echo isset($action)&&$action=="0-like"?"selected":""; ?>><?php echo $ld['collection'] ?></option>
                    <option value="8-like" <?php echo isset($action)&&$action=="8-like"?"selected":""; ?>><?php echo $ld['cancel_collection'] ?></option>
                    <option value="0-cart" <?php echo isset($action)&&$action=="0-cart"?"selected":""; ?>><?php echo $ld['shopping_cart'] ?></option>
                </select>
            </div>
        </li>
         <li style="margin-bottom:10px;">
        	<label class="am-u-sm-3 am-u-md-3 am-u-lg-3 am-form-label"><?php echo $ld['operation_time']; ?></label>
            <div class="am-u-sm-3 am-u-md-3 am-u-lg-3  " style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date" value="" />
            </div>
            <em class="am-text-center am-u-md-1 am-u-sm-1 am-u-lg-1 " style="padding: 0.35em 0px;">-</em>
            <div class="am-u-sm-3 am-u-md-3 am-u-lg-3  am-u-end" style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date" value="" />
            </div>			
        </li>
       <li style="margin-bottom:10px;" >
            <label class="am-u-sm-3 am-u-md-3 am-u-lg-3 am-form-label"> </label>
            <div class="am-u-sm-7 am-u-md-7 am-u-lg-7 " style="padding:0 0.5rem;">
          	<input class="am-btn  am-btn-success am-radius   am-btn-sm" type="button" value="<?php echo $ld['search'];?>" onclick="formsubmit()" /> 
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
            <th><label style="margin:0 0 0 0;" class="am-checkbox am-success">
          <span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/> </span>
        <?php echo $ld['user_name'] ?></label></th>
            <th style="text-align:center;"><?php echo $ld['type'] ?></th>
            <th style="word-wrap: break-word;"><?php echo $ld['user_like_object'] ?></th>
            <th><?php echo $ld['operation_time']; ?></th>
            <th style="width:150px;"><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($like_list) && sizeof($like_list)>0){foreach($like_list as $k=>$v){?>
            <tr>
                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['UserLike']['id']?>" /></span><?php if(isset($user[$k])&&$user[$k]!=""){ echo $user[$k][0]['User']['name'];}?></label>
                </td>
                <td align="center">
                    <?php
                    if($v['UserLike']['action']=="like"&&$v['UserLike']['type_id']=="0")
                    {
                        echo $ld['collection'];
                    }else if($v['UserLike']['action']=="like"&&$v['UserLike']['type_id']=="8")
                    {
                        echo $ld['cancel_collection'];
                    }else
                    {
                        echo $ld['shopping_cart'];
                    }
                    ?>
                </td>
                <td>
                    <?php if(isset($product[$k])&&$product[$k]!=""){
                        echo $product[$k]['ProductI18n']['name'];
                    }?>
                </td>
                <td><?php echo $v['UserLike']['created']?></td>
                <td><?php
                    if($svshow->operator_privilege("user_likes_remove")){?>
                  <a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="if(j_confirm_delete){list_delete_submit(admin_webroot+'user_likes/remove/<?php echo $v['UserLike']['id'] ?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
                        
                   <?php  }
                    ?></td>
            </tr>
        <?php }}else{?>
            <tr>
                <td colspan="6" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if(isset($like_list) && sizeof($like_list)>0){?>
        <div id="btnouterlist" class="btnouterlist">
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-12 am-hide-sm-down" style="margin-left:-3px;">
                 <div class=" am-u-lg-3 am-u-md-4 am-u-sm-5"><label class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label> 
                </div>
                 <div class=" am-u-lg-5 am-u-md-5 am-u-sm-5  "><select id="select_type" data-am-selected>
                    <option value=""><?php echo $ld['please_select']?></option>
                    <?php if($svshow->operator_privilege("user_likes_remove")){?>
                        <option value="operation_delete"><?php echo $ld['batch_delete']?></option>
                    <?php }?>
                </select>
                </div>
                <div class= " am-u-lg-1 am-u-md-3 am-u-sm-2  am-u-end"><input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" value="<?php echo $ld['submit']?>" onclick="submit_operations()" /></div>
            </div>
            <div class="am-u-lg-6 am-u-md-8 am-u-sm-12"><?php echo $this->element('pagers')?></div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    function formsubmit(){
        var user=document.getElementById('user').value;
        var action=document.getElementsByName('action')[0].value;
        var start_date = document.getElementsByName('start_date')[0].value;
        var end_date = document.getElementsByName('end_date')[0].value;
        var str = '';
        var url = "user="+user+"&action="+action+"&start_date="+start_date+"&end_date="+end_date+str;
        window.location.href = encodeURI(admin_webroot+"user_likes?"+url);
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
        if(opration_select_type=='operation_delete'&&checkboxes==""){
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
        var popcontent=document.getElementById("popup");popcontent.style.display="none";var popdiv=popcontent.firstChild;popdiv.style.display="none";while(popdiv.nextSibling){var popdiv=popcontent.nextSibling;popdiv.style.display="none";}
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