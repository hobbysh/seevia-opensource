<div class="am-g">
    <div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
	  <ul class="am-list admin-sidebar-list">
	    <li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['change_password']?></a></li>
	  </ul>
	</div>
    <?php echo $form->create('pages',array('action'=>'edit/',"data-am-validator"=>''));?>
    <div class="am-panel-group am-u-lg-10 am-u-md-9 am-u-sm-8" id="accordion">
      <div class="am-panel am-panel-default">
        <div class="am-panel-hd">
	      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}"><?php echo $ld['change_password'] ?></h4>
	    </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                <div class="am-form-group">
                    <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label am-text-right"><?php echo $ld['user_name'] ?></label>
                    <div class="am-u-lg-4 am-u-md-5 am-u-sm-7 am-form-label am-text-left"><?php if(isset($user_name)){echo $user_name;}?></div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">&nbsp;</div>
                </div>
                <div class="am-form-group">
    				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label am-text-right"><?php echo $ld['old_password']?></label>
    				<div class="am-u-lg-4 am-u-md-5 am-u-sm-7"><input type="password" name="old_pwd" id="old_pwd" required /></div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><em style="color:red;">*</em></div>
    			</div>
    			<div class="am-form-group">
    				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label am-text-right"><?php echo $ld['new_password']?></label>
    				<div class="am-u-lg-4 am-u-md-5 am-u-sm-7"><input type="password" name="new_pwd" id="new_pwd" required /></div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><em style="color:red;">*</em></div>
    			</div>
                <div class="am-form-group">
                    <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label am-text-right"><?php echo $ld['confirm_password_again']?></label>
    		        <div class="am-u-lg-4 am-u-md-5 am-u-sm-7"><input type="password" name="new_pwd_confirm" id="new_pwd_confirm" data-equal-to="#new_pwd"  required /></div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><em style="color:red;">*</em></div>
    			</div>
            </div>
            <div class="btnouter">
                <button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button><button type="reset" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"> <?php echo $ld['d_reset'] ?></button>
            </div>
        </div>
      </div>
    </div>
    <?php echo $form->end();?>
<div>