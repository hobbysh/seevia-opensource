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
<style>
 .am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;} 
 .am-form-horizontal .am-checkbox{padding-top:0;}
</style>
<?php //pr($backend_locales);?>
<div class="am-g">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list">
	    	<li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>
	  	</ul>
	</div>

<?php echo $form->create('InformationResource',array('action'=>'view'))?>
	<div class="am-panel-group am-u-lg-10 am-u-md-9 am-u-sm-8" id="accordion">
		 <div class="am-panel am-panel-default">
		    <div class="am-panel-hd">
		      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}"><?php echo $ld['basic_information'] ?></h4>
		    </div>
		    <div id="basic_information" class="am-panel-collapse am-collapse am-in">
		      	<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
					<input type="hidden"  name="data[InformationResource][id]" value="<?php echo isset($this->data['InformationResource']['id'])?$this->data['InformationResource']['id']:'';?>"> 
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['resource_name'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
				  		<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10" style="margin-bottom:10px;">
								<input type="text" id="name<?php echo $v['Language']['locale']?>"  maxlength="60" name="data[InformationResourceI18n][<?php echo $k;?>][name]" value="<?php if(isset($this->data['InformationResourceI18n'][$v['Language']['locale']]['name'])){echo $this->data['InformationResourceI18n'][$v['Language']['locale']]['name'];}?>" />
							</div>
								<?php if(sizeof($backend_locales)>1){?>
								<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
									<?php echo $ld[$v['Language']['locale']];?>&nbsp;<em style="color:red;">*</em>
								</div>
								<?php }?>
								<input type="hidden" name="data[InformationResourceI18n][<?php echo $k?>][locale]" value="<?php echo $v['Language']['locale']?>" />
								<input id="InformationResourceI18n<?php echo $k;?>Id" name="data[InformationResourceI18n][<?php echo $k;?>][id]" type="hidden" value="<?php if(isset($this->data['InformationResourceI18n'][$v['Language']['locale']]['id'])){ echo $this->data['InformationResourceI18n'][$v['Language']['locale']]['id'];}?>">
								<input id="InformationResourceI18n<?php echo $k;?>InformationResourceI18n" name="data[InformationResourceI18n][<?php echo $k;?>][resource_id]" type="hidden" value="<?php if(isset($this->data['InformationResourceI18n'][$v['Language']['locale']]['resource_id'])){ echo $this->data['InformationResourceI18n'][$v['Language']['locale']]['resource_id'];}?>">
						<?php }}?>
						</div>
					</div>
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['parent_resource'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
							<select data-am-selected="{btnWidth:200,maxHeight:300, btnSize: 'default ', btnStyle: '#CCCCCC'}" name="data[Resource][parent_id]">
									<option value="000"><?php echo $ld['top_level_resource'];?></option>
									<?php if(isset($parentmenu) && sizeof($parentmenu)>0){foreach($parentmenu as $kk=>$vv){?>
									<option value="<?php echo $vv['InformationResource']['id']?>" <?php if(isset($this->data['InformationResource']['parent_id'])){if($this->data['InformationResource']['parent_id'] == $vv['InformationResource']['id']){?>selected<?php }}?>><?php echo $vv['InformationResourceI18n']['name']?></option><?php }}?>
							</select>
						</div>
					</div>
								
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['r_description'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
								<input type="text" id="<?php echo $v['Language']['locale']?>" name="data[InformationResourceI18n][<?php echo $k;?>][description]" value="<?php if(isset($this->data['InformationResourceI18n'][$v['Language']['locale']]['description'])){echo $this->data['InformationResourceI18n'][$v['Language']['locale']]['description'];}?>"/>
							</div>
						</div>
					</div>
								
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['resource_code'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
								<input type="text" name="data[InformationResource][code]" value="<?php echo isset($this->data['InformationResource']['code'])?$this->data['InformationResource']['code']:""?>"/>
							</div>
						</div>
					</div>	
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['z_resource_value'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10">				
								<input type="text" name="data[InformationResource][information_value]" value="<?php echo isset($this->data['InformationResource']['information_value'])?$this->data['InformationResource']['information_value']:''?>"/>
							</div>
						</div>
					</div>
					<!--是否可用-->
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['availability'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
								<label class="am-radio am-success"><input type="radio" class="radio" data-am-ucheck name="data[Resource][status]" style="margin-left:0px;" value="1" checked="true" <?php if(isset($this->data['InformationResource']['status'])){if($this->data['InformationResource']['status']==1){echo "checked";}}?>/><?php echo $ld['yes'];?></label>&nbsp;&nbsp;&nbsp;
								<label class="am-radio am-success"><input type="radio" class="radio" data-am-ucheck  name="data[InformationResource][status]" style="margin-left:0px;" value="0" <?php if(isset($this->data['InformationResource']['status'])){if($this->data['InformationResource']['status']==0){echo "checked";}}?>/><?php echo $ld['no'];?></label>
						</div>
					</div>
					<!--排序-->
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['sort'];?></div> 
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
								<input type="text"  name="data[InformationResource][orderby]" value="<?php echo isset($this->data['InformationResource']['orderby'])?$this->data['InformationResource']['orderby']:"" ?>" onkeyup="check_input_num(this)"/>							
							</div><br /><br />
								<p><?php echo $ld['sorting_prompt'];?></p> 
						</div>
					</div>
			</div>
			<div class="btnouter">
				<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button>
				<button type="reset" class="am-btn am-btn-default am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
			</div>		
		</div>
  	  </div>
  	 </div>
<?php echo $form->end();?>

</div>
