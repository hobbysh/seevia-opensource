<?php echo $form->create('Configvalue',array('action'=>'mail_settings_edit','enctype'=>"multipart/form-data"));?>
<div id="tablemain" class="tablemain">
	<!--邮件信息-->
	<div>
		<h2><?php echo $ld['email_setting']?></h2>
		<div>
			<table class="alonetable">
				<?php $i=0; $n = 1;foreach ($basics as $infoes) { ?>
				<?php foreach ($infoes as $info) { if ($info['Config']['type'] == "select") {foreach ($backend_locales as $k => $v) { ?>
				<tr>
					<th><?php echo $info['ConfigI18n'][$v['Language']['locale']]['name']?></th>
					<td><input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>"> <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $info['Config']['id']; ?>"> <input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['id']))echo $info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
						<select name="data[<?php echo $i; ?>][value]"/>
						
						<?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['options']) && !empty($info['ConfigI18n'][$v['Language']['locale']]['options'])) {
										$options = explode(';', $info['ConfigI18n'][$v['Language']['locale']]['options']);
											foreach ($options as $option) {
												$text = explode(":", $option); ?>
						<option value="<?php echo $text[0]; ?>" <?php if ($text[0] == $info['ConfigI18n'][$v['Language']['locale']]['value'])echo 'selected'; ?>>
						<?php if (@$text[1]) {echo $text[1];} ?>
						</option>
						<?php }	} ?>
						</select>
						<?php echo $html->link(" ", "javascript:config_help(".(empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn')) ?> <span id="config_help_<?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span></td>
				</tr>
				<?php $i++;}} ?>
				<?php if ($info['Config']['type'] == "text") {?>
				<tr>
					<th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $info['ConfigI18n'][$backend_locale]['name']?></th>
				</tr>
				<?php 	foreach ($backend_locales as $k => $v) { ?>
				<tr>
					<td><input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>"> <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $info['Config']['id']; ?>"> <input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['id']))echo $info['ConfigI18n'][$v['Language']['locale']]['id']; ?>"> <input type="text" name="data[<?php echo $i; ?>][value]" id="<?php echo $info['Config']['code']?><?php echo $v['Language']['locale'];?>" value="<?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['value']))echo $info['ConfigI18n'][$v['Language']['locale']]['value']; ?>" /> <?php echo $html->link(" ", "javascript:config_help(".(empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn')) ?> <span id="config_help_<?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span></td>
				</tr>
				<?php $i++;
								}	} ?>
				<?php if ($info['Config']['type'] == "textarea") {?>
				<tr>
					<th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $info['ConfigI18n'][$backend_locale]['name']?></th>
				</tr>
				<?php foreach ($backend_locales as $k => $v) {?>
				<tr>
					<td><input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>"> <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $info['Config']['id']; ?>"> <input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['id']))echo $info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
						<textarea name="data[<?php echo $i; ?>][value]"><?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['value']))echo $info['ConfigI18n'][$v['Language']['locale']]['value']; ?></textarea>
						<?php echo $html->link(" ", "javascript:config_help(".(empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn')) ?> <span id="config_help_<?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span></td>
				</tr>
				<?php $i++;}} ?>
				<?php if ($info['Config']['type'] == "radio") {?>
				<tr>
					<th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $info['ConfigI18n'][$backend_locale]['name']?></th>
				</tr>
				<?php foreach ($backend_locales as $k => $v) { ?>
				<tr>
					<td><input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>"> <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $info['Config']['id']; ?>"> <input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['id']))echo $info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
						<?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['options'])) {
										$options = $info['ConfigI18n'][$v['Language']['locale']]['options'];
										foreach ($options as $option) {
											$text = explode(":", $option);
												if (@$text[1] != "") {
						?><label <?php if($text[0]==1) echo 'style="float:left;*float:none;"'; ?>><input type="radio" name="data[<?php echo $i; ?>][value]" id="<?php echo $info['Config']['code']?><?php echo $v['Language']['locale'];?>" value="<?php echo $text[0]; ?>" <?php if (@$text[0] == $info['ConfigI18n'][$v['Language']['locale']]['value'])echo 'checked'; ?>/><?php if (@$text[1]) {echo $text[1];} ?></label><?php
										}		}
						?>
						<?php echo $html->link(" ", "javascript:config_help(".(empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn')) ?><span id="config_help_<?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span></td>
				</tr>
				<?php $i++;}} ?>
				<?php if ($info['Config']['type'] == "image") {?>
				<tr>
					<th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $info['ConfigI18n'][$backend_locale]['name']?></th>
				</tr>
				<?php foreach ($backend_locales as $k => $v) { ?>
				<tr>
					<td><input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>"> <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $info['Config']['id']; ?>"> <input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['id']))echo $info['ConfigI18n'][$v['Language']['locale']]['id']; ?>"> <input type="text" name="data[<?php echo $i; ?>][value]" id="upload_img_text_<?php echo $i; ?>" value="<?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['value']))echo $info['ConfigI18n'][$v['Language']['locale']]['value']; ?>" /> <?php echo $html->link(" ", "javascript:config_help(".(empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn')) ?> <span id="config_help_<?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span></td>
				</tr>
				<?php $i++;}} ?>
				<?php if ($info['Config']['type'] == "checkbox") {?>
				<tr>
					<th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $info['ConfigI18n'][$backend_locale]['name']?></th>
				</tr>
				<?php foreach ($backend_locales as $k => $v) { ?>
				<tr>
					<td><input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>"> <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $info['Config']['id']; ?>"> <input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['id']))echo $info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
						<?php if (isset($info['ConfigI18n'][$v['Language']['locale']]['options']) && !empty($info['ConfigI18n'][$v['Language']['locale']]['options'])) {
								$checkoptions = explode(';', $info['ConfigI18n'][$v['Language']['locale']]['value']);
								$options = $info['ConfigI18n'][$v['Language']['locale']]['options'];
								foreach ($options as $option) {
									$text = explode(":", $option);
									if (@$text[1] != "") { ?>
						<input type="checkbox" name="data[<?php echo $i; ?>][value][]" value="<?php echo $text[0]; ?>" <?php if (in_array($text[0], $checkoptions))echo 'checked'; ?>/>
						<?php if (@$text[1]) {
								echo $text[1];
										}}} ?>
						<?php echo $html->link(" ", "javascript:config_help(".(empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn')) ?> <span id="config_help_<?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span></td>
				</tr>
				<?php $i++;}} ?>
				<?php } ?>
				<?php } ?>
				<?php } ?>
				<tr>
					<th><?php echo $ld['email']?></th>
					<td><input type="text" id="email_addr" /><input type="button" value="<?php echo $ld['send_test_email']?>" onclick="test_email()" /></td>
				</tr>
			</table>
		</div>
		<div class="btnouter">
			<input type="submit" value="<?php echo $ld['d_submit'];?>" /><input type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
</div>
<?php $n++;} ?>
<?php $form->end(); ?>
<script type="text/javascript">
function test_email(){
	
	YUI().use("io",function(Y) {
		var email_addr = document.getElementById('email_addr');
		var smtp_pass = document.getElementById('smtp_pass'+backend_locale);
		var smtp_host = document.getElementById('smtp_host'+backend_locale);
		var smtp_user = document.getElementById('smtp_user'+backend_locale);
		var smtp_port = document.getElementById('smtp_port'+backend_locale);
		var smtp_ssl = document.getElementById('smtp_ssl'+backend_locale);


		if(!smtp_ssl.checked){
			if(smtp_ssl.value==1){
				smtp_ssl_value = 0;
			}else{
				smtp_ssl_value = 1;
			
			}
		}else{
			smtp_ssl_value = smtp_ssl.value;
		}
		

		var sUrl = admin_webroot+"configvalues/test_email/"+email_addr.value+"/"+smtp_host.value+"/"+smtp_user.value+"/"+smtp_port.value+"/"+smtp_ssl_value;

		var cfg = {
			method: "POST",
			data: "smtp_pass="+smtp_pass.value
		};
		var request = Y.io(sUrl, cfg);//开始请求
 
		var handleSuccess = function(ioId, o){
			
			if(o.responseText==1){
				alert("<?php echo $ld['congratulations_message_successfully_sent']?> "+document.getElementById('email_addr').value);
			}else{
				alert(o.responseText);
			}
		}
		var handleFailure = function(ioId, o){
			alert("<?php echo $ld['asynchronous_request_failed']?>");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});
}
</script>
