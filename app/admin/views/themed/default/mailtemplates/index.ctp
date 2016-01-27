 
<p class="am-u-md-12 am-text-right am-btn-group-xs">
	<?php if($svshow->operator_privilege("mailtemplates_add")){?>
	   <a class="am-btn am-btn-warning am-radius am-btn-sm" href="<?php echo $html->url('view'); ?>">
				  <span class="am-icon-plus"></span>
				  <?php echo $ld['add_email_template'] ?>
				    </a>
				    <?php }?>
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
	<table class="am-table  table-main">
		<thead>
			<tr>
				<th class="am-hide-sm-down"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['email_code']?></b></label></th>
				
				<th><?php echo $ld['email_template_name']?></th>
				<th class="thwrap am-hide-md-down"><?php echo $ld['email_help']?></th>
				<th><?php echo $ld['valid']?></th>
				<th><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($mailtemplate_list) && sizeof($mailtemplate_list)>0){foreach( $mailtemplate_list as $k=>$v ){ ?>
			<tr>
				<td  class="am-hide-sm-down"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['MailTemplate']['id']?>" /><?php echo $v["MailTemplate"]["code"]?></td>
				
				<td><?php echo $v["MailTemplateI18n"]["title"]?></td>
				<td class="am-hide-md-down"><?php echo $v["MailTemplateI18n"]["description"]?></td>
				<td><?php if ($v['MailTemplate']['status'] == 1){?>
					<div style="color:#5eb95e" class="am-icon-check"></div>
					<?php }elseif($v['MailTemplate']['status'] == 0){?>
					<div style="color:#dd514c" class="am-icon-close"></div>
					<?php }?></td>
				<td class="am-action"><?php
					if($svshow->operator_privilege("mailtemplates_edit")){?>
						<a class=" btn_agreeds am-text-center">
							<a  class="am-icon-pencil-square-o am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/mailtemplates/view/'.$v['MailTemplate']['id']); ?>"> <?php echo $ld['edit']; ?>
							</a>
						</a>	
				<?php } if($svshow->operator_privilege("mailtemplates_remove")){?>
					<a class="btn_agreeds am-text-center"> 
						<a class="am-icon-trash-o am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(j_confirm_delete){list_delete_submit(admin_webroot+'mailtemplates/remove/<?php echo $v['MailTemplate']['id'] ?>');}"> <?php echo $ld['delete']; ?> 
						</a>
					</a>
				 <?php	} ?>
				 </td>
			</tr>
			<?php }}else{ ?>
            <tr>
                <td colspan="6" style="text-align:center;height:100px;vertical-align:middle;"><?php echo $ld['no_page_data'];?></td>
            </tr>
            <?php } ?>
		</tbody>
	</table>
	<?php if(isset($mailtemplate_list) && sizeof($mailtemplate_list)){?>
	<div id="btnouterlist" class="btnouterlist">
		<div class="am-u-lg-6 am-u-md-4 am-u-sm-12 am-hide-sm-only">
		    <label style="margin:5px 5px 5px 0px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
			<input class="am-btn am-btn-danger am-radius am-btn-sm" type="button" value="<?php echo $ld['batch_delete']?>" onclick="batch_delete()" />
		</div>
        <div class="am-u-lg-6 am-u-md-7 am-u-sm-12"><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
	</div>
	<?php }?>
</div>
<script>
function batch_delete(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var checkboxes=new Array();
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			checkboxes.push(bratch_operat_check[i].value);
		}
	}
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		var sUrl = admin_webroot+"mailtemplates/removeall/";//���ʵ�URL��ַ
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