<style type="text/css">
 
	am-form-inline{
	padding: 0em 0;
}
</style>
 
<div class="listsearch">
    <?php echo $form->create('upload_files',array('action'=>'/','name'=>'searchfile','type'=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-form-label-text"><?php echo $ld['file_name']?></label>
            <div class="am-u-sm-7 am-u-lg-8 am-u-md-8 " style="padding:0 0.5rem;">
                <input type="text" name="name" id="name" value="<?php echo @$name;?>" />
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-form-label-text"><?php echo $ld['posted_time']?></label>
            <div class="am-u-sm-3 am-u-lg-3 am-u-md-3 " style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date" value="<?php echo @$date;?>" />
            </div>
            <em class="am-u-sm-1 am-u-lg-1 am-u-md-1 am-text-center " style="padding: 0.35em 0px;">-</em>
            <div class="am-u-sm-3 am-u-lg-3 am-u-md-3 " style="padding:0 0.5rem;">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date2" value="<?php echo @$date2;?>" />
            </div>
        </li>
   <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-form-label-text"> </label>
            <div class="am-u-sm-7 am-u-lg-8 am-u-md-8 " style="padding:0 0.5rem;">
           	<input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['search'];?>"/>
            </div>
        </li>
       
    </ul>
    <?php echo $form->end();?>
</div>
<p class="am-u-md-12 am-btn-group-xs  am-text-right">
				<?php if($svshow->operator_privilege("upload_files_add")){?>
		
				<a class="am-btn am-btn-sm am-btn-warning am-radius" href="<?php echo $html->url('add/');?>">
					<span class="am-icon-plus"</span> <?php echo $ld['file_uplaod'] ?>
				</a>
					<?php }?>
				
 </p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12 am-u-lg-12">
    <table class="am-table  table-main">
        <thead>
        <tr >
            <th><label style="margin:0 0 0 0;" class="am-checkbox am-success am-hide-sm-only"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['file_name']?> </b></label>
                    <label style="margin:0 0 0 0;" class="am-checkbox am-success am-show-sm-only"><b><?php echo $ld['file_name']?> </b></label> 
        </th>
         <th ><?php echo $ld['file_type']?></th>
            <th class="thwrap am-hide-sm-down"><?php echo $ld['file_size']?></th>
            <th claSS="am-hide-sm-down"><?php echo $ld['sort']?></th>
            <th><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($uploadfiles) && sizeof($uploadfiles)>0){?>
            <?php foreach($uploadfiles as $k=>$v){?>
                <tr>
                    <td><label style="margin:0 0 0 0;" class="am-checkbox am-success am-hide-sm-only"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Document']['id']?>" /> <?php echo $v['Document']['name'];?></label>
                    <label style="margin:0 0 0 0;" class="am-checkbox am-success am-show-sm-only">  <?php echo $v['Document']['name'];?></label>
                    </td>
                    <td><?php echo $v['Document']['type'];?></td><?php// pr($v); ?>
                    <td claSS="am-hide-sm-down">
                    <?php  echo ceil ($v['Document']['file_size']/1024);?> KB                  
                    </td>
                   <td  claSS="am-hide-sm-down"><?php echo $v['Document']['orderby'];?></td>
                    <td class="am-btn-group-xs am-action"> 
                     <?php
                     
                        if($svshow->operator_privilege("upload_files_mgt")){
                            ?><a href="javascript:;"   class="am-btn am-btn-default am-btn-xs am-seevia-btn-edit   js-modal-open" onclick="photo_copys('<?php echo $v['Document']['file_url'];?>')" ><span class="am-icon-copy"></span> <?php echo $ld['copy']?></a><?php
                        } ?> 
                           		
                           		
                           		
                          <?php if($svshow->operator_privilege("upload_files_edit")){?><a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/upload_files/edit/'.$v['Document']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                
                    <?php  }?> <a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="if(confirm(j_confirm_delete)){list_delete_submit(admin_webroot+'upload_files/remove/<?php echo $v['Document']['id'] ?>');}">
                        			<span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?>
                      			</a>
                         </td>
                </tr>
            <?php }?>
        <?php }else{?>
            <tr>
                <td colspan="7"  class="no_data_found"><?php echo $ld['no_data_found']?> </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if($svshow->operator_privilege("upload_files_remove")){?>
        <?php if(isset($uploadfiles) && sizeof($uploadfiles)){?>
            <div id="btnouterlist" class="btnouterlist">
                <div class="am-u-lg-3 am-u-md-5 am-u-sm-12 am-hide-sm-only" style="margin-left:2px;">
                    <label style="margin-right:5px;float:left;margin-top:6px;" class="am-checkbox am-success">
                <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
                    <input type="button" class="am-btn am-btn-danger  am-radius am-btn-sm" value="<?php echo $ld['batch_delete']?>" onclick="batch_operations()" />
                </div>
                <div class="am-u-lg-9 am-u-md-6 am-u-sm-12"><?php echo $this->element('pagers')?></div>
                <div class="am-cf"></div>
            </div>
        <?php }?>
    <?php }?>
</div>
	
	  <div class="am-modal am-modal-no-btn" tabindex="-1" id="tip-copy1">
                <div class="am-modal-dialog">
                    <div class="am-modal-hd">
                        <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                    </div>
                    <div class="am-modal-bd">
                        <input type="text" value="" id="tip-copy1-text">
                        <p><?php echo $ld['do_not_copy']?></p>
                    </div>
                </div>
            </div>
<script>
    //复制
   
    function photo_copy(ev,src){alert('ll');
        if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){
         var event= ev || window.event;
         var div = document.getElementById('tip-copy1');
         div.className=div.className.replace("hidden"," ");
         div.style.left = event.clientX - 100 + 'px';
         div.style.top = (document.documentElement.scrollTop + event.clientY) + 'px';
         document.getElementById('tip-copy1-text').value = src;
         }
         else if(isIE=navigator.userAgent.indexOf("MSIE")>0)
        {
        var div = document.getElementById('tip-copy1');
         div.className=div.className.replace("hidden"," ");
         div.style.left = event.clientX - 100 + 'px';
         div.style.top = (document.documentElement.scrollTop + event.clientY) + 'px';
         document.getElementById('tip-copy1-text').value = src;
        	
            if(window.clipboardData&&clipboardData.setData){
                clipboardData.setData("Text", src);
                alert(j_replicate_successfully);
            }
        }
        else{
            /*window.clipboardData.setData("Text",src);*/
            alert('请使用IE6.0或以上版本浏览本页');
        }
    }
    /////////////////////////
    function photo_copys(src){
	if(navigator.userAgent.search("MSIE") != -1){
		window.clipboardData.setData("Text",src);
		alert(j_replicate_successfully);
	}else{
	       var $url=$('#tip-copy1-text').val(src);
	         var $modal = $('#tip-copy1');
                   $modal.modal('toggle');
       }
}
 /////////////////////////////
    function batch_operations(){
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                checkboxes.push(bratch_operat_check[i].value);
            }
        }
        if( checkboxes=="" ){
            alert("<?php echo $ld['please_select'] ?>");
            return;
        }
        if(confirm("<?php echo $ld['confirm_delete']?>")){
            var sUrl = admin_webroot+"upload_files/batch_operations/";//访问的URL地址
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
 
 
</script>

 
 