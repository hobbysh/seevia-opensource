
<div class="am-contact">
	<div class="am-cf am-contact-us">
		<h3><?php echo $ld['contact_us'] ?></h3>
		<hr>
	</div>
	<?php echo $form->create('Contacts', array('id'=>"contact_form",'action' => '/index/','name'=>'ContactForm','class'=>'am-form am-form-horizontal','onsubmit'=>'return(check_form(this));')); ?>
	<div class="am-form-detail">
        <?php if(!isset($contact_us_type)&&isset($contact_us_type_data)&&is_array($contact_us_type_data)&&sizeof($contact_us_type_data)>0){ ?>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['type'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<select name="data[Contact][type]">
                <?php foreach($contact_us_type_data as $k=>$v){ ?><option value="<?php echo $k; ?>" <?php echo isset($contact_us_type)&&$contact_us_type==$k?'selected':''; ?>><?php echo $v; ?></option><?php } ?>
            </select>
    	  </div>
        </div>
        <?php }else if(isset($contact_us_type)&&isset($contact_us_type_data)&&is_array($contact_us_type_data)&&sizeof($contact_us_type_data)>0){ ?>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['type'] ?></label>
          <label class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-form-label am-text-left">
            <?php echo isset($contact_us_type_data[$contact_us_type])?$contact_us_type_data[$contact_us_type]:'' ?>
            <input type="hidden" name="data[Contact][type]" value="<?php echo $contact_us_type; ?>" />
    	  </label>
        </div>
        <?php } ?>
            
		<div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['company_name'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<input type="text" size="32" class="input" name="data[Contact][company]" id="ContactCompany" chkRules="nnull:<?php echo $ld['no_empty_company']?>" defaultNote="<?php echo $ld['enter_company']?>" value="" /><em class="l1"><font color="red">*</font></em>
    	  </div>
        </div>
    	
    	<div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['domain'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<input type="text" size="32" class="input" name="data[Contact][web]" id="ContactCompany" chkRules="domain:<?php echo $ld['domain_format']?>;" value="" /><em class="l1"><font color="red"></font> <font></font>&nbsp;</em>
    	  </div>
        </div>
        
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['industry'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<select id="ContactCompanyType" name="data[Contact][company_type]" chkRules="nnull:<?php echo $ld['select_industry']?>">
	          <option value=""><?php echo $ld['please_select']?></option>
	          <?php $arr=array(); foreach ($industry as $k => $v) {
							if (trim($v)!="") { $arr=explode(":",trim($v));?>
	          <option value="<?php echo $arr[0]; ?>" <?php if(isset($configs['contacts-industry']) && trim($configs['contacts-industry']) == $arr[0]){echo "selected";}?>><?php echo $arr[1]; ?></option>
	          <?php }	}?>
	        </select><em class="l1" style="left:0px;"><font color="red">*</font></em>
    	  </div>
        </div>
        		
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['contact'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<input type="text" chkRules="nnull:<?php echo $ld['no_empty_contactor']?>" defaultNote="<?php echo $ld['enter_contact']?>" onkeyup="this.value=this.value.replace(/[^\u4E00-\u9FA5A-Za-z]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\u4E00-\u9FA5A-Za-z]/g,''))" size="32" class="input" name="data[Contact][contact_name]" id="ContactContactName" value="" /><em class="l1"><font color="red">*</font> <font></font>&nbsp;</em>
    	  </div>
        </div>
        	
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['e-mail'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		 <input type="text" size="32" class="input" chkRules="nnull:<?php echo $ld['e-mail_empty']?>;email:<?php $ld['e-mail_incorrectly']?>;" name="data[Contact][email]" id="ContactEmail" value="" /><em class="l1"><font color="red">*</font> <font></font>&nbsp;</em>
    	  </div>
        </div>
        
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['mobile'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<input type="text" size="32" class="input" name="data[Contact][mobile]" chkRules="nnull:<?php echo $ld['phone_can_not_be_empty']?>;mobile:<?php echo $ld['phone_incorrectly_completed']?>;length11:<?php echo $ld['mobile_number_length']?>" id="ContactMobile" value="" /><em class="l1"><font color="red">*</font> <font></font>&nbsp;</em>
    	  </div>
        </div>
        	
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['qq'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<input name="data[Contact][qq]" type="text" />
    	  </div>
        </div>
        	
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['msn'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<input name="data[Contact][msn]" type="text" />
    	  </div>
        </div>
        	
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['skype'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<input name="data[Contact][skype]" type="text" />
    	  </div>
        </div>
        <?php if (isset($learn_us) && sizeof($learn_us)>0) {?>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['how_did_you_learn_about_us'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<select id="ContactFrom" name="data[Contact][from]" class="" chkRules="nnull:<?php echo $ld['choose_informed'] ?>">
	          <option value=""><?php echo $ld['please_select'] ?></option>
	          <?php foreach ($learn_us as $k => $v) {
						if (trim($v)!="") { $arr1=explode(":",trim($v));?>
	          <option value="<?php echo $arr1[0]; ?>" <?php if(isset($configs['contacts-learn-us']) && trim($configs['contacts-learn-us']) == $arr1[0]){echo "selected";}?>><?php echo $arr1[1]; ?></option>
	          <?php }	}?>
	        </select><em class="l1" style="left:0px;"><font color="red">*</font></em>
    	  </div>
        </div>
        <?php } ?>
        
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['message'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<textarea name="data[Contact][content]" rows="" cols="" ></textarea>
    	  </div>
        </div>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label">&nbsp;</label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
            <input id="save_btn" class="am-btn am-btn-primary am-btn-sm am-fl" type="button" value="<?php echo $ld['submit']?>" />
    	  </div>
        </div>
	</div>
	<?php echo $form->end();?>
</div>
<?php if(isset($configs['shop_map'])&&!empty($configs['shop_map'])){$address_arr=explode(",",$configs['shop_map']);}?>
<div class="am-g am-g-fixed">
	<div data-am-widget="map" class="am-map am-map-default" data-name=""
	data-address="<?php if(isset($configs['company_address'])&&!empty($configs['company_address'])){echo $configs['company_address'];}?>" data-longitude="<?php if(isset($address_arr['0'])&&!empty($address_arr['0'])){echo $address_arr['0'];}?>" data-latitude="<?php if(isset($address_arr['1'])&&!empty($address_arr['1'])){echo $address_arr['1'];}?>"
	data-scaleControl="" data-zoomControl="true" data-setZoom="17" data-icon="http://amuituku.qiniudn.com/mapicon.png">
	  <div id="bd-map"></div>
	</div>
</div>
<script type="text/javascript">
auto_check_form("contact_form",false);
$("#save_btn").click(function(){
  if(check_form(document.getElementById("contact_form"))){
	  $.ajax({
	    type: "POST",
	    url:"/contacts/index/",
	    data:$('#contact_form').serialize(),// 你的formid
		dataType:"json",
	    async: false,
	    success: function(data) {
    			alert(data.msg);
			if(data.code==1){
				location.reload(true);
			}
	    }
	  });
  }
});
//****************************************************************
// Description: sInputString 为输入字符串，iType为类型，分别为
// 0 - 去除前后空格; 1 - 去左边空格; 2 - 去右边空格
//****************************************************************
function cTrim(sInputString,iType)
{
	var sTmpStr = ' '
	var i = -1
	if(iType == 0 || iType == 1)
	{
	while(sTmpStr == ' ')
	{
	++i
	sTmpStr = sInputString.substr(i,1)
	}
	sInputString = sInputString.substring(i)
	}
	if(iType == 0 || iType == 2)
	{
	sTmpStr = ' '
	i = sInputString.length
	while(sTmpStr == ' ')
	{
	--i
	sTmpStr = sInputString.substr(i,1)
	}
	sInputString = sInputString.substring(0,i+1)
	}
	return sInputString
}
</script>