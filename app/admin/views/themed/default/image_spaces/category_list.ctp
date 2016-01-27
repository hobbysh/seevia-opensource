<p class="am-g am-text-right am-btn-group-xs"style="margin-right:10px;">
    <a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url("category_view/"); ?>">
		<span class="am-icon-plus"></span> <?php echo $ld['add_photos_category'] ?>
	</a> 
</p>
<?php echo $form->create('category_list',array('action'=>'','name'=>"CategoryListForm","type"=>"get",'onsubmit'=>"return false"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12" >
    <table class="am-table  table-main">
        <thead>
            <tr>
                <th ><label style="margin:0px" class="am-checkbox am-success  am-hide-sm-only"><input  onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['category_name']?></b></label></th>
     <th ><label style="margin:0px" class="am-checkbox am-success  am-show-sm-only"> <b><?php echo $ld['category_name']?></b></label></th>
                 
                <th><?php echo $ld['sort']?></th>
                <th style="width:350px;"><?php echo $ld['operate']?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(isset($photo_category_list) && sizeof($photo_category_list)){foreach($photo_category_list as $k=>$v){?>
            <tr>
                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success  am-hide-sm-only"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['PhotoCategory']['id']?>" />
                       <span onclick="javascript:listTable.edit(this, 'photo_categories/update_photo_categories_name/', <?php echo $v['PhotoCategory']['id']?>)"><?php echo $v["PhotoCategoryI18n"]["name"];?></span> </label>
                </td>
                
                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success  am-show-sm-only"> 
                       <span onclick="javascript:listTable.edit(this, 'photo_categories/update_photo_categories_name/', <?php echo $v['PhotoCategory']['id']?>)"><?php echo $v["PhotoCategoryI18n"]["name"];?></span> </label></td>
                <td><span onclick="javascript:listTable.edit(this, 'photo_categories/update_photo_categories_orderby/', <?php echo $v['PhotoCategory']['id']?>)"><?php echo $v["PhotoCategory"]["orderby"];?></span></td>
                <td class="am-action"><?php echo $html->link($ld['view_category_image'],"/image_spaces/index/0/0/0/{$v['PhotoCategory']['id']}/",array("class"=>"am-btn am-btn-default  am-btn-xs am-radius"));?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/image_spaces/category_view/'.$v['PhotoCategory']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                    <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'image_spaces/category_remove/<?php echo$v['PhotoCategory']['id']; ?>')">
                                  <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                     </a>
                    <?php echo $html->link($ld['upload_picture'],"/image_spaces/upload?img_cat={$v['PhotoCategory']['id']}",array("class"=>"am-btn am-btn-default  am-btn-xs am-radius"));?>
                    </td>
            </tr>
        <?php }}else{?>
            <tr>
                <td colspan="4" style="text-align:center;height:100px;vertical-align:middle;"><?php echo $ld['no_category']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if(isset($photo_category_list) && sizeof($photo_category_list)>0 && count($photo_category_list)>0){?>
        <div id="btnouterlist" class="btnouterlist  am-hide-sm-only">
	            <div class="am-u-lg-5 am-u-md-5 am-u-sm-12" style="margin-left:2px;">
	                <label style="margin-top:7px;float:left;margin-right:7px;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
	                <input type="button" class="am-btn am-btn-danger am-btn-xs am-radius" value="<?php echo $ld['batch_delete']?>" onclick="batch_operations()" />
	            </div>
	            <div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
	                    <?php echo $this->element('pagers'); ?>
	            </div>
            <div class="am-cf"></div>
        </div>
    <?php } ?>
</div>
<?php echo $form->end();?>
<script>
    function batch_operations(){
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                checkboxes.push(bratch_operat_check[i].value);
            }
        }
        if(checkboxes=="" ){
            alert("<?php echo $ld['please_select'] ?>");
            return;
        }
        if(confirm("<?php echo $ld['confirm_delete']?>")){
            var sUrl = admin_webroot+"image_spaces/batch/"; 
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'html',
                data: {checkboxes:checkboxes},
                success: function (result) {
                     
                        window.location.href = window.location.href;
                     
                }
            });
        }
    }
</script>