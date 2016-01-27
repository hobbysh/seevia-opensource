<style>
    .action-span {margin-top: -25px;}
    .am-form-label{font-weight:bold;  margin-top:-3px;margin-left:15px;}
    
.am-table > tbody > tr > td, .am-table > tbody > tr > th, .am-table > tfoot > tr > td, .am-table > tfoot > tr > th, .am-table > thead > tr > td, .am-table > thead > tr > th {
    border-top: 1px solid #ddd;
    line-height: 1.6;
    padding: 0.7rem;
    vertical-align: 0;
}
</style>
<div class="listsearch">
    <?php echo $form->create('OpenElement',array('action'=>'/','name'=>"SeearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-3 am-avg-lg-3" style="margin:10px 0 0 0">
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-2  am-u-lg-2   am-u-md-2    am-form-label"><?php echo $ld['title'];?></label>
            <div class="am-u-sm-7  am-u-lg-8 am-u-md-8 " style="padding:0 0.5rem;">
                <input placeholder="<?php echo $ld['title']?>" type="text" name="keywords" id="keywords" value="<?php echo isset($keywords)?$keywords:'';?>" />
            </div>
    	       <div class="am-u-sm-1  am-u-lg-1 am-u-md-1 am-hide-sm-only">
        	 <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search'];?>" />
        	</div>
           </li>
   
          <li style="margin:0 0 10px 0" class="am-show-sm-only">
            <label class="am-u-sm-2  am-u-lg-2   am-u-md-2    am-form-label"> </label>
            <div class="am-u-sm-7  am-u-lg-8 am-u-md-8 " style="padding:0 0.5rem;">
              <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search'];?>" />
            </div>
        </li>
    </ul>
    <?php echo $form->end()?>
</div>
<p class="am-u-md-12 am-text-right am-btn-group-xs">
                       <?php if($svshow->operator_privilege("open_elements_add")){?>
	                   <a class="am-btn am-btn-warning am-radius am-btn-sm" href="<?php echo $html->url('view/2'); ?>">
				  <span class="am-icon-plus"></span>
				  <?php echo $ld['manypictures'] ?>
				</a>
		          <?php }?>
		         <?php if($svshow->operator_privilege("open_elements_add")){?>
		          <a class="am-btn am-btn-warning am-radius am-btn-sm" href="<?php echo $html->url('view/1'); ?>">
				  <span class="am-icon-plus"></span>
				  <?php echo $ld['onepicture'] ?>
				</a>
		       <?php  }?>
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <?php echo $form->create('OpenElement',array('action'=>'/','name'=>'PageForm','type'=>'get',"onsubmit"=>"return false;"));?>
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th class="thwrap  "><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['picture_show'];?></b></label></th>
            <th > <?php echo $ld['title']?> </th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['custom_path']?></th>
            <th class="thwrap am-hide-sm-down"><?php echo $ld['type']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['time']?></th>
            <th ><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($element_list) && sizeof($element_list)>0){foreach($element_list as $k=>$v){?>
            <tr>
                <td class="thwrap  "><label style="margin:0 0 0 0;" class="am-checkbox am-success">
                   <span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['OpenElement']['id']?>" /></span>
                <?php if(!empty($v['OpenElement']['media_url'])){?><img style="width:105px;" class="media_Img" src="<?php echo $v['OpenElement']['media_url'].'?date='.time() ?>" class="media_url" /><?php }?></label></td>
              
                <td><?php echo $v['OpenElement']['title'];?></td>
                <td class="thwrap am-hide-md-down"><?php echo $v['OpenElement']['url'];?></td>
                <td class="thwrap am-hide-sm-down"><?php echo $v['OpenElement']['element_type']=='1'?$ld['onepicture']:$ld['manypictures']; ?></td>
                <td class="thwrap am-hide-md-down"><?php echo $v['OpenElement']['created'];?>
                </td>
                <td class="am-action">
                     <a class="am-btn am-btn-success am-btn-xs am-seevia-btn" target="_blank" href="<?php echo $html->url($server_host.'/open_elements/preview/'.$v['OpenElement']['id']);?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                    </a>
                   	<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="upload_element('<?php echo $v['OpenElement']['id']; ?>','<?php echo $v['OpenElement']['element_type']; ?>')" href="javascript:void(0);">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['upload']; ?>
                    </a>
                   <?php  if($svshow->operator_privilege("open_elements_edit")){?> 
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/open_elements/view/'.$v['OpenElement']['element_type'].'/'.$v['OpenElement']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                    <?php }
                    if($svshow->operator_privilege("open_elements_remove")){?> 
                    <a class="am-btn am-btn-default am-btn-xs am-text-danger   am-seevia-btn-delete" href="javascript: ;" onclick="list_delete_submit(admin_webroot+'open_elements/remove/<?php echo $v['OpenElement']['id'] ?>');">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
                    <?php }
                    ?>
                </td>
            </tr>
        <?php }}else{?>
            <tr>
                <td colspan="6" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    		<?php if(isset($element_list) && sizeof($element_list)>0){?>
	    <div id="btnouterlist" class="btnouterlist" style="height:45px;">
		        <div class="am-u-lg-3 am-u-md-4  am-u-sm-12 am-hide-sm-down">
		            <label style="margin:5px 5px 5px 0px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><?php echo $ld['select_all']?></label>
		            <input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" value="<?php echo $ld['batch_delete']?>" onclick="batch_delete()" />
		        </div>
			    <div class="am-u-lg-9 am-u-md-7 am-u-sm-12">
		             <?php  echo $this->element('pagers');?>
		          </div>
		    <div class='am-cf'></div>
	        </div>
	        <?php }?>
    <?php echo $form->end();?>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="upload_element">
  <div class="am-modal-dialog">
    <div class="am-modal-hd"><?php echo $ld['upload'] ?>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
    		<form id="upload_element_form">
    			<input type="hidden" name="element_id" value="0">
    			<input type="hidden" name="element_type" value="0">
	    		<table class="am-table">
	    			<tr>
	    				<td><select  name="open_type_id"  data-am-selected>
		                		<option value="0"><?php echo $ld['please_select'].$ld['open_model_account'] ?></option>
		                		<?php foreach($open_type as $k=>$v){ ?>
		                    	<option value="<?php echo $v['OpenModel']['open_type_id'] ?>"><?php echo $v['OpenModel']['open_type_id'] ?></option>
		                		<?php } ?>
		            </select></td>
	    			</tr>
	    			<tr>
	    				<td>
	    					<input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['confirm']?>" onclick="ajax_upload_element(this)" />
	    				</td>
	    			</tr>
	    		</table>
    		</form>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
    $(".media_Img").each(function(){
        set_img($(this));
    });
});

function set_img(obj){
    var W=H=100;
    var img_src=obj.attr("src");
    var img=new Image();
    img.onload=function(){
        img_w=100;
        img_h=100;
        if(img.width/img.height >= img_w/img_h){
            if(img.width > img_w)
            {
                W=img_w;
                H=(img.height*img_w)/img.width;
            }
            else
            {
                W=(img.width*img_h)/img.height;
                H=img_h;
            }
        }else{
            if(img.width > img_w)
            {
                W=img_w;
                H=(img.height*img_w)/img.width;
            }else{
                W=(img.width*img_h)/img.height;
                H=img_h;
            }
        }
        if(W>100){
            W=100;
            H=(img.height*100)/img.width;
        }else if(H>100){
            W=(img.width*100)/img.height;
            H=100;
        }
        obj.css("width",W+"px").css("height",H+"px");
    }
    img.src=img_src;
}

function batch_delete(){
    var bratch_operat_check = document.getElementsByName("checkboxes[]");
    var checkboxes=new Array();
    for(var i=0;i<bratch_operat_check.length;i++){
        if(bratch_operat_check[i].checked){
            checkboxes.push(bratch_operat_check[i].value);
        }
    }
    if(confirm("<?php echo $ld['confirm_delete'] ?>")){
        var sUrl = admin_webroot+"open_elements/removeall/";//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {checkboxes:checkboxes},
            success: function (result) {
                
                    window.location.href = window.location.href;
               
            }
        });
    }
}

function upload_element(element_id,element_type){
	$('#upload_element').modal();
	$("#upload_element input[name='element_id']").val(element_id);
	$("#upload_element input[name='element_type']").val(element_type);
}

function ajax_upload_element(btn_obj){
	var open_type_id=$("#upload_element select[name='open_type_id']").val();
	if(open_type_id!='0'){
		if(confirm(confirm_operation)){
			 $(btn_obj).button('loading');
			 var sUrl = admin_webroot+"open_elements/element_upload/";//访问的URL地址
		        $.ajax({
		            type: "POST",
		            url: sUrl,
		            dataType: 'json',
		            data: $("#upload_element_form").serialize(),
		            success: function (result) {
		                	if(result.code=='1'){
		                		alert(operation_success);
		                	}else{
		                		alert(result.msg);
		                	}
		            },
		            error:function(){
		            		alert(j_object_transform_failed);
		            },
		            complete: function(XMLHttpRequest, textStatus) {
		            		$(btn_obj).button('reset');
		            		if(XMLHttpRequest.status!=200){
		            			alert(j_object_transform_failed);
		            		}
		            }
		        });
		}
	}
}
</script>