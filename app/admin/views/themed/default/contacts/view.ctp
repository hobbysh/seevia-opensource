<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#detail"><?php echo $ld['details_view']?></a></li>
        <li><a href="#quick_reply"><?php echo $ld['quick_reply']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  >
    <div id="detail" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['details_view']?></h4>
        </div>
        <div id="details_view" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <?php if(!empty($Resource_info['contact_us_type'])){ ?>
                        <tr>
                            <th ><?php echo $ld['type'];?></th>
                            <td><?php echo isset($Resource_info['contact_us_type'][$this->data['Contact']['contact_type']])?$Resource_info['contact_us_type'][$this->data['Contact']['contact_type']]:$this->data['Contact']['contact_type']; ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['company_name'];?></th>
                        <td><input style="width:60%;" type="text" id="data_mailtemplate_code" name="data[Contact][company]" value="<?php echo $this->data['Contact']['company'];?>" readonly /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['order_web']?></th>
                        <td><input style="width:60%;" type="text" id="data_mailtemplate_code" name="data[Contact][company_url]" value="<?php echo $this->data['Contact']['company_url'];?>" readonly/></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['company_type'];?></th>
                        <td><input style="width:60%;" type="text" id="data_mailtemplate_code" name="data[Contact][company_type]" value="<?php echo $this->data['Contact']['company_type'];?>" readonly/></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['contact_from']?></th>
                        <td><input style="width:60%;" type="text" id="data_mailtemplate_code" name="data[Contact][from]" value="<?php echo $this->data['Contact']['from']?>" readonly/></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['contacter'];?></th>
                        <td><input style="width:60%;" type="text" id="data_mailtemplate_code" name="data[Contact][contact_name]" value="<?php echo $this->data['Contact']['contact_name'];?>" readonly/></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['email']?></th>
                        <td><input  style="width:60%;"  type="text" id="data_mailtemplate_code" name="data[Contact][email]" value="<?php echo $this->data['Contact']['email'];?>" readonly/></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['phone'];?></th>
                        <td><input style="width:60%;" type="text" id="data_mailtemplate_code" name="data[Contact][mobile]" value="<?php echo $this->data['Contact']['mobile'];?>" readonly />
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['address'];?></th>
                        <td><input style="width:60%;" type="text" id="data_mailtemplate_code" name="data[Contact][address]" value="<?php echo $this->data['Contact']['address'];?>" readonly />
                    </tr>
                    <tr>
                        <th style="padding-top:15px">QQ</th>
                        <td><input style="width:60%;" type="text" id="data_mailtemplate_code" name="data[Contact][qq]" value="<?php echo $this->data['Contact']['qq'];?>" readonly/></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px">MSN</th>
                        <td><input style="width:60%;" type="text" id="data_mailtemplate_code" name="data[Contact][msn]" value="<?php echo $this->data['Contact']['msn'];?>" readonly/></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px">SKYPE</th>
                        <td><input style="width:60%;" type="text" id="data_mailtemplate_code" name="data[Contact][skype]" value="<?php echo $this->data['Contact']['skype'];?>" readonly/></td>
                    </tr>
                    <tr>
                        <th style="padding-top:25px"><?php echo $ld['message_content'];?></th>
                        <td><textarea style="width:60%;"><?php echo $this->data['Contact']['content'];?></textarea></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['ip_address'];?></th>
                        <td><?php echo $this->data['Contact']['ip_address'];?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['growser_version'];?></th>
                        <td><?php echo $this->data['Contact']['browser'];?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['time'];?></th>
                        <td><?php echo $this->data["Contact"]["parameter_01"]."&nbsp;".$this->data["Contact"]["parameter_02"];?></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['added_time'];?></th>
                        <td><?php echo $this->data['Contact']['created'];?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div id="quick_reply" class="am-panel am-panel-default">
	<div class="am-panel-hd">
		<h4 class="am-panel-title"><?php echo $ld['quick_reply']?></h4>
	</div>
	<div class="am-panel-collapse am-collapse am-in">
		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
			<?php echo $form->create('',array('action'=>'/view'.$this->data['Contact']['id'],'id'=>'contact_form','onsubmit'=>'return false;'));?>
				<input type="hidden" name="contact_id" value="<?php echo $this->data['Contact']['id']; ?>">
			<div class="am-form-group">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-view-label"><?php echo $ld["content"]?></label>
				<div class="am-u-lg-9 am-u-sm-9 am-u-sm-8">
					<textarea id="quick_reply_content" name="quick_reply_content"></textarea>
				</div>
			</div>
			<div class="am-cf">&nbsp;</div>
			<div class="btnouter">
		    		 	<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="quick_reply()">
		    			<?php echo $ld['reply'];?></button>
		    	            <button type="reset" class="am-btn am-btn-default am-btn-sm am-radius">
				 	<?php echo $ld['d_reset']?></button>
			    </div>
			<?php echo $form->end();?>
		</div>
	</div>
    </div>
</div>
<script type='text/javascript'>
var editor;
KindEditor.ready(function(K) {
	editor = K.create('#quick_reply_content', {
		width:'98%',
		langType : "<?php echo $locale_google_translate_code; ?>",
		filterMode : false,
		afterBlur: function () { this.sync(); }
	});
});

function quick_reply(){
	var quick_reply_content=editor.html();
	if(quick_reply_content!=""){
		var PostData=$("#contact_form").serialize();
		$.ajax({
			url:admin_webroot+"contacts/quick_reply",
			data:PostData,
			dataType:'json',
			method:'post',
			success:function(data){
				alert(data.message);
				if(data.flag){
					window.location.href=admin_webroot+"contacts/index";
				}
			}
		});
	}
}
</script>