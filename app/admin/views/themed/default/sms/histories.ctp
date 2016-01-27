<?php
/*****************************************************************************
 * SV-Cart 查看短信历史
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
<style type="text/css">
 .am-form-label{font-weight:bold; text-align:center; margin-top:-4px;margin-left:20px;}
</style>
<div class="listsearch">
	<?php echo $form->create('',array('action'=>'/histories','name'=>"SearchSmsForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
	<ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
  		<li style="margin:0 0 10px 0">
  			<label class="am-u-sm-3 am-u-md-3  am-u-lg-2 am-form-label"><?php echo $ld['send']?></label>
			<div class="am-u-sm-7 am-u-md-7  am-u-lg-8">
				<input type="text" class="am-form-field am-input-sm" id="flag" name="flag"  placeholder="<?php echo $ld['send'].$ld['error'].$ld['count']?>" value="<?php if(isset($flag))echo $flag?>"/>
			</div>
  		</li>
		<li style="margin:0 0 10px 0">
  			<label class="am-u-sm-3 am-u-md-3  am-u-lg-3 am-form-label"><?php echo $ld['send'].$ld['time']?></label>
			<div class="am-u-sm-3 am-u-md-3  am-u-lg-3" style="padding:0 0.5rem;">
	        	<input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date" value="<?php echo @$date;?>" />
	      	</div>
		  	<em class="am-u-sm-1 am-u-md-1  am-u-lg-1 am-text-center" style="padding: 0.35em 0px;">-</em>
		  	<div class="am-u-sm-3 am-u-md-3  am-u-lg-3" style="padding:0 0.5rem;">
				<input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date2" value="<?php echo @$date2;?>" />
	      	</div>
  		</li>
  	  <li style="margin:0 0 10px 0">
  			<label class="am-u-sm-3 am-u-md-3  am-u-lg-2 am-form-label"><?php echo $ld['mobile']?></label>
			<div class="am-u-sm-7 am-u-md-7  am-u-lg-8">
				<input type="text" name="phone" id="phone" value="<?php if(isset($phone))echo $phone;?>" />
			</div>
  		</li>
  		<li style="margin:0 0 10px 0">
  			<label class="am-u-sm-3 am-u-md-3  am-u-lg-2 am-form-label"><?php echo $ld['keyword']?></label>
			<div class="am-u-sm-7 am-u-md-7  am-u-lg-8 ">
				<input placeholder="<?php echo $ld['sms_content']?>" type="text" id="content" name="content"  float:left;margin-right: 20px;"  value="<?php if(isset($content))echo $content?>"/>
			 
			</div>
  		</li>
  		<li style="margin:0 0 10px 0">
  		   	<label class="am-u-sm-3 am-u-md-3  am-u-lg-3 am-form-label"></label>
			<div class="am-u-sm-7 am-u-md-3  am-u-lg-7 ">
				 
				<input class="am-btn am-btn-success  am-radius am-btn-sm" type="submit" value="<?php echo $ld['search']?>" />
			</div>		
  		</li>
	</ul>
	<?php echo $form->end()?>
</div>
<p class="am-u-md-12 am-btn-group-xs">
	<?php if($svshow->operator_privilege('sms_view')){echo $html->link($ld['sms_list'],"/sms/",array("class"=>"am-btn am-btn-default am-radius am-btn-sm am-fr"),false,false);}?>
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
	<table class="am-table  table-main">
		<thead>
			<tr>
				<th><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b> <?php echo $ld['mobile']?></b></label></th> 
	                   <th><?php echo $ld['sms_content']?></th>
				<th><?php echo $ld['send'].$ld['time']?></th>
				<th><?php echo $ld['send'].$ld['status']?></th>
				<th><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($Sms_histories_data) && sizeof($Sms_histories_data)>0){foreach($Sms_histories_data as  $k=>$v){?>
			<tr>
				<td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['SmsSendHistory']['id']?>" /> <?php echo $v['SmsSendHistory']['phone'];?></td> 
				<td><?php echo $v['SmsSendHistory']['content'];?></td>
				<td><?php echo $v['SmsSendHistory']['send_date'];?></td>
				<td><?php if($v['SmsSendHistory']['flag']=0){echo "未发送";}else{echo "已发送";};?></td>
				<td><?php
					if($svshow->operator_privilege("sms_histories_mgt")){
						echo $html->link(' '.$ld['view'],'/sms/histories_view/'.$v['SmsSendHistory']['id'],array("target"=>"_blank","class"=>"mt am-icon-eye am-btn am-btn-success am-btn-xs am-radius"),false,false).'&nbsp;&nbsp;';
					}
					if($svshow->operator_privilege("sms_histories_remove")){
						echo $html->link(' '.$ld['remove'],"javascript:;",array("class"=>"mt am-icon-trash-o am-btn am-btn-default am-text-danger am-btn-xs am-radius","onclick"=>"if(confirm('你确定要删除吗？')){window.location.href='{$admin_webroot}Sms/histories_delete/{$v['SmsSendHistory']['id']}';}"));
					}
				?></td>
			</tr>
			<?php }}else{?>
					<tr>
					<td colspan="6" class="no_data_found"><?php echo $ld['no_data_found'];?></td>
					</tr>
			<?php }?>
		</tbody>
	</table>
	<?php if(isset($Sms_histories_data) && sizeof($Sms_histories_data)){?>
	<div id="btnouterlist" class="btnouterlist">
		<?php if($svshow->operator_privilege("sms_histories_remove")){?>
		<div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
			<label style="margin-right:5px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
			<input type="button" class="am-btn am-btn-danger am-radius am-btn-sm" value="<?php echo $ld['batch_delete'] ?>" onclick=" batch_operations(this)" />
		</div>
		<?php }?>
		<div class="am-u-lg-6 am-u-md-12 am-u-sm-12"><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
	</div>
	<?php }?>
</div>
<?php echo $form->end();?>
<script>
function batch_operations(obj){
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
	disablebtn(obj);
	if(confirm("<?php echo $ld['confirm_delete']?>")){
		var sUrl = admin_webroot+"/sms/histories_batch/";//访问的URL地址
		$.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {checkboxes:checkboxes},
            success: function (result) {
                if(result.flag==1){
					window.location.href = window.location.href;
				}
				if(result.flag==2){
					alert(result.message);
				}
            }
        });
	}
	disablebtn(obj);
}
</script>