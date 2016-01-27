<?php 
/*****************************************************************************
 * SV-Cart 编辑菜单
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
.am-radio, .am-checkbox{display:inline;}
.am-form-horizontal .am-form-label, .am-form-horizontal .am-radio, .am-form-horizontal .am-checkbox, .am-form-horizontal .am-radio-inline, .am-form-horizontal .am-checkbox-inline {padding-top:0px;}
</style>
<div class="am-g">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
	  <ul class="am-list admin-sidebar-list">
	    <li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>
	  </ul>
	</div>
	<?php echo $form->create('Menu',array('action'=>'view/'.(isset($this->data['OperatorMenu']['id'])?$this->data['OperatorMenu']['id']:0),'onsubmit'=>'return menus_check()'));?>
	<div class="am-panel-group am-u-lg-10 am-u-md-9 am-u-sm-8" id="accordion">
		  
		 <div class="am-panel am-panel-default">
		    <div class="am-panel-hd">
		      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}"><?php echo $ld['basic_information'] ?></h4>
		    </div>
		    <div id="basic_information" class="am-panel-collapse am-collapse am-in">
		      <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
		        <input type="hidden" name="data[OperatorMenu][id]" value="<?php echo isset($this->data['OperatorMenu']['id'])?$this->data['OperatorMenu']['id']:'0'; ?>"/>
		        	<div class="am-form-group">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['previous_menu'] ?></label>
			          <div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
			            <select name="data[OperatorMenu][parent_id]" data-am-selected>
							<option value="0"><?php echo $ld['top_menu'] ?></option>
							<?php if(isset($parentmenu) && sizeof($parentmenu)>0){?>
							<?php foreach($parentmenu as $k=>$v){?>
							<option value="<?php echo $v['OperatorMenu']['id']?>" <?php if(isset($this->data['OperatorMenu']['parent_id'])&&$v['OperatorMenu']['id'] == $this->data['OperatorMenu']['parent_id']) echo "selected";?>><?php echo $v['OperatorMenuI18n']['name']?></option><?php }}?>
						</select>
					  </div>
			        </div>
			        
			        <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['menu_name']; ?></label>
                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
			        	<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				          <div class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="margin-bottom:5px;">
				            <input type="text" id="menu_name_<?php echo $v['Language']['locale']?>" maxlength="60" name="data[OperatorMenuI18n][<?php echo $v['Language']['locale'];?>][name]" value="<?php echo isset($this->data['OperatorMenuI18n'][$v['Language']['locale']])?$this->data['OperatorMenuI18n'][$v['Language']['locale']]['name']:'';?>" />
						  </div>
					  	<?php }} ?>
                        </div>
			        </div>
					
			        
			        <div class="am-form-group">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['code'] ?></label>
			          <div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
			            <input type="text" name="data[OperatorMenu][operator_action_code]" value="<?php echo isset($this->data['OperatorMenu']['operator_action_code'])?$this->data['OperatorMenu']['operator_action_code']:''; ?>"/>
					  </div>
			        </div>
			        	
			        <div class="am-form-group">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['type'] ?></label>
			          <div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
			            <input type="text" name="data[OperatorMenu][type]" value="<?php echo isset($this->data['OperatorMenu']['type'])?$this->data['OperatorMenu']['type']:''; ?>"/>
					  </div>
			        </div>
					
					<div class="am-form-group">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['link_address'] ?></label>
			          <div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
			            <input type="text" name="data[OperatorMenu][link]" value="<?php echo isset($this->data['OperatorMenu']['link'])?$this->data['OperatorMenu']['link']:''; ?>"/>
					  </div>
			        </div>
			        
			        <div class="am-form-group">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['versions'] ?></label>
			          <div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
			            <input type="text" name="data[OperatorMenu][section]" value="<?php echo isset($this->data['OperatorMenu']['section'])?$this->data['OperatorMenu']['section']:''; ?>"/>
					  </div>
			        </div>
			        
			        <div class="am-form-group">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['status'] ?></label>
			          <div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
			            <label class="am-radio am-success"><input type="radio" name="data[OperatorMenu][status]" data-am-ucheck <?php if(isset($this->data['OperatorMenu']['status'])&&$this->data['OperatorMenu']['status'] == 1){?>checked="checked"<?php }?> value="1"/><?php echo $ld['yes']?></label>
						<label class="am-radio am-success"><input type="radio" name="data[OperatorMenu][status]" data-am-ucheck <?php if((isset($this->data['OperatorMenu']['status'])&&$this->data['OperatorMenu']['status'] == 0)||!isset($this->data['OperatorMenu']['status'])){?>checked="checked"<?php }?> value="0"/><?php echo $ld['no']?></label>
					  </div>
			        </div>
			        
			        <div class="am-form-group">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['orderby'] ?></label>
			          <div class="am-u-lg-1 am-u-md-2 am-u-sm-3">
			            <input type="text" name="data[OperatorMenu][orderby]" value="<?php echo isset($this->data['OperatorMenu']['orderby'])?$this->data['OperatorMenu']['orderby']:'50'; ?>"/>
					  </div>
			        </div>
		      </div>
		      <div class="btnouter">
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button><button type="reset" class="am-btn am-btn-default am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
			  </div>
		    </div>
	  	 </div>
	  				  
	</div>
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
function menus_check(){
	var menu_name=$("#menu_name_<?php echo $backend_locale ?>").val();
	if(menu_name==""){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['menu_name']); ?>");
		return false;
	}
}
</script>