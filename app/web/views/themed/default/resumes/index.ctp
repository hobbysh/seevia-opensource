<script>var resume_default = "<?php echo ($this->theme=='evergreen')?$themes_host.'/themes/'.$this->theme.'/img/no_head.png':$themes_host.'/theme/default/img/no_head.png'; ?>";</script> 
<script type="text/javascript" src="/plugins/ajaxfileupload.js"></script>

<div class="am-g am-g-fixed" style=" font-size:14px;">
<div>
	<ol class="am-breadcrumb">
	  <li><a href="<?php echo $html->url('/jobs/') ?>"><?php echo $ld['recruitment_page'];?></a></li>
	  <li><a href="<?php echo $html->url('/resumes/') ?>"><?php echo $ld['recruitment_details'];?></a></li>
	  <li class="am-active"><?php echo $ld['fill_resume'];?></li>
	</ol>
</div>
	<!--个人信息-->
		<div class="cont_r_1">
			<input type="hidden" name="lan" id="lan" value="<?php echo LOCALE;?>" />
			<input type="hidden" name="data[Resume][id]" id="resume_id" value="<?php echo isset($data)&&isset($data['Resume'])?$data['Resume']['id']:'';?>" />
			<section data-am-widget="accordion" class="am-accordion am-accordion-gapped" data-am-accordion='{  }'>
			  <dl class="am-accordion-item am-active">
			    <dt class="am-accordion-title">	
				<?php echo $ld['personal_info']; ?><span class="am-fr"><?php echo $ld['shrink']; ?></span>
				</dt>
		    <dd class="am-accordion-bd am-collapse am-in">
		      <div class="am-accordion-content">
			<form id="base_info_form" class="am-form am-form-horizontal"  name="base_info_form"  method="POST">
			<div  class="am-form-group">
				  <!--保存按钮-->
				  <div style="float:right;">
				   	 <span class="btn_save" onclick="save_base_info()">
				  		<button type="button"  class="am-btn am-btn-primary am-btn-xs">
				  			<?php echo $ld['user_save'] ;?>
				  		</button>
				  	</span>
				  <div style="clear:both;"></div>
				  </div>
				 <ul id="user_info" class="am-avg-sm-1 am-avg-md-2  am-thumbnails">
				  <li>
				  <div class="am-u-sm-12 am-u-md-8 am-u-lg-6">
		   				<input type="hidden" name="data[Resume][avatar]" id="avatar_path" value="<?php echo isset($data)&&isset($data['Resume'])&&$data['Resume']['avatar']?$data['Resume']['avatar']:(($this->theme=="evergreen")?$themes_host.'/themes/'.$this->theme.'/img/no_head.png':$themes_host.'/theme/default/img/no_head.png');?>">
				    	<img id="r_photo" src="<?php echo isset($data)&&isset($data['Resume'])&&$data['Resume']['avatar']?$data['Resume']['avatar']:(($this->theme=="evergreen")?$themes_host.'/themes/'.$this->theme.'/img/no_head.png':$themes_host.'/theme/default/img/no_head.png');?>" alt=""/><br/>
				  <!--编辑按钮-->
				  <div style="margin-top:10px;">
				       <button type="button" data-am-modal="{target: '#doc-modal-1'}" class="am-btn am-btn-primary am-btn-xs" ><?php echo $ld['edit'] ;?>
				       </button>
			    	   <button type="button" class="am-btn am-btn-primary am-btn-xs"  onclick="$('#r_photo').attr('src',resume_default);$('#avatar_path').val('')" style="margin-left:10px;"><?php echo $ld['delete'];?>
			    	   </button>
			     </div>
				  		<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
									  <div class="am-modal-dialog">
									    <div class="am-modal-hd"> 
									     <h2><?php echo $ld['upload_photo']; ?></h2>
										      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
									    </div>
									    <div class="am-modal-bd">
									      <div class="am-form-group" style="margin-top:10px;">
										    <label class="am-u-lg-5 am-u-md-5 am-u-sm-5 am-form-label am-text-right" style="padding-top:0;"><?php echo $ld['upload_path']; ?></label>
											<div class="am-u-lg-7  am-u-md-7 am-u-sm-7">
										    	<input type="file" id="avatar_file" name="avatar_file" style="margin-bottom:5px;"/>
											</div>
	 					   				  </div>
	 										<button type="button" class="am-btn am-btn-default" onclick="ajaxFileUpload()" ><?php echo $ld['upload']; ?></button>
											<p><?php echo $ld['suggest_photo_size']; ?></p
									    </div>
									  </div>
									</div>
								</div>

				    	</div>
				  </li>
				  <li></li>
				  <!--姓名-->
				  <li><label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['name']; ?></label>
					  <div class="am-u-sm-8">
					  	  <input type="text" name="data[Resume][name]" id="name" required value="<?php echo isset($data)&&isset($data['Resume'])?$data['Resume']['name']:'';?>"/>
					  </div>
				  </li>
				  <!--性别-->
				  <li>
				    <label class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['gender']; ?></label>
				    <div class="am-u-sm-8">
					    <label class="am-form-label"> 
					  	  	<input type="radio" name="data[Resume][sex]" value="0" <?php echo isset($data)&&isset($data['Resume'])&&$data['Resume']['sex']==0?'checked':'';?>/>
					  		<?php echo $ld['user_male'];?>
					    </label>&nbsp;&nbsp;
					  	<label class="am-form-label">
					    	<input type="radio" name="data[Resume][sex]" value="1" <?php echo isset($data)&&isset($data['Resume'])&&$data['Resume']['sex']==1?'checked':'';?> />       
					    	<?php echo $ld['user_female'];?>
					    </lable>
				    </div>
				  	
				    </li>
				    <!--出生日期-->
				   <li>
				   	<label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['date_of_birth'];?></label>
					<div class="am-u-sm-8">
						<input type="text" name="data[Resume][birthday]"  class="am-form-field" placeholder="2000-01-01" data-am-datepicker readonly/>
					</div>
				  </li>
				  <!--工作年限-->
				  <li>
				  	<label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['year_of_work_experience']; ?></label>
				  	<div class="am-u-sm-8">
			  			<select name="data[Resume][experience_id]" id="experience_id">
								<option value=""><?php echo $ld['please_select'];?></option>
								<?php if(isset($informationresource_infos['experience_type'])){ foreach($informationresource_infos['experience_type'] as $ek=>$et){?>
								<option value="<?php echo $ek?>"  <?php echo isset($data)&&$data['Resume']['experience_id']==$ek?'selected':''?>>
									<?php echo $et?>
									<?php if($et>1){echo $ld['years'];}else{echo $ld['year_year'];}?>
								</option>
								<?php }}?>
							</select>
					</div>
				  </li>
				  <!--证件类型-->
				  <li>
				  	<label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['id_type']; ?></label>
				  	<div class="am-u-sm-8">
			  			<select name="data[Resume][certificate_id]" id="certificate_id">
							<option  value=""><?php echo $ld['please_select'];?></option>
							<?php if(isset($informationresource_infos['certificate_type'])){ foreach($informationresource_infos['certificate_type'] as $ck=>$ct){?>
							<option value="<?php echo $ck?>" <?php echo isset($data)&&$data['Resume']['certificate_id']==$ck?'selected':''?>><?php echo $ct?>
							</option>
							<?php }}?>
						</select>
					</div>			
				  </li>
				  <!--证件号-->
				  <li>
				  <label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['id']; ?></label>
				  <div class="am-u-sm-8">
				  			<input type="text" name="data[Resume][certificate_num]"  id="certificate_num" required value="<?php echo isset($data)&&$data['Resume']['certificate_num']?$data['Resume']['certificate_num']:''?>"/>
				  	
				   </div>
				   </li>
				   <!--居住地-->
				   <li>
				   	   <label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['residency']; ?></label>
				   	   <div class="am-u-sm-8"><input type="text" name="data[Resume][apartments]" id="apartments" required value="<?php echo isset($data)&&$data['Resume']['apartments']?$data['Resume']['apartments']:''?>">
				   	   </div>
				   </li>
				   <!--Email-->
				   <li>
				   	<label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><em>*</em>Email</label>
				   	<div class="am-u-sm-8"><input type="email" name="data[Resume][email]" id="email" required value="<?php echo isset($data)&&$data['Resume']['email']?$data['Resume']['email']:''?>"/>
				   	   </div>	   
				   </li>
				   <!--目前年薪-->
				   <li>
				 	<label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><?php echo $ld['current_annual_salary']; ?></label>
				 	<div class="am-u-sm-8">
				 	   <input type="text" name="data[Resume][current_salary]" value="<?php echo isset($data)&&$data['Resume']['current_salary']?$data['Resume']['current_salary']:''?>"/>
				 	   </div>  
				  </li>
				  <!--户口-->
				  <li>
				  	<label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><?php echo $ld['registered_residence']; ?></label>
				  	<div class="am-u-sm-8">
				  	   <input type="text" name="data[Resume][registers]" id="registers" value="<?php echo isset($data)&&$data['Resume']['registers']?$data['Resume']['registers']:''?>"/>
				  	 </div>	   
				  </li>
				  <!--应聘职位-->
				   <li>
				   	<label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['position_want_to_apply']; ?></label>
				   	<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				   	   <!--	<?php $_job_id=isset($data['Resume']['job_id'])?$data['Resume']['job_id']:isset($job_id)?$job_id:''; ?>-->
				   	   	<?php $_job_id=isset($data['Resume']['job_id'])?$data['Resume']['job_id']:(isset($job_id)?$job_id:''); ?>
							<select id="job_name" name="data[Resume][job_id]">
								<option value=""><?php echo $ld['please_select'];?></option>
							<?php if(isset($job_list)&&sizeof($job_list)>0){foreach($job_list as $k=>$v){ ?>
								<option <?php echo $_job_id==$v['Job']['id']?'selected':''; ?> value="<?php echo $v['Job']['id']; ?>"><?php echo $v['JobI18n']['name'];?></option>
									
							<?php }} ?>
							</select>
					</div>	   
				   </li>
				   				
				   <li></li>
				   	 <!--联系方式-->
				   <li>
				   	<label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['contacts'];?></label>
				   	<label for="doc-ipt-3" class="am-u-sm-8 am-form-label"><em><?php echo $ld['fill_in_at_least_one']; ?></em></label>	   
				   </li>
				   
				   <li></li>
				   <!--手机号码-->
				   <li>
				   	<label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><?php echo $ld['mobile_number']; ?></label>
				   	<div class="am-u-sm-8">
				   		<input type="text" name="data[Resume][mobile]" id="mobile" value="<?php echo isset($data)&&$data['Resume']['mobile']?$data['Resume']['mobile']:''?>"/>
				   	</div>	   
				   </li>
				   	   <!--家庭电话-->
				   <li>
				   	<label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><?php echo $ld['home_phone_number']; ?></label>
				   	<div class="am-u-sm-8">
				   		<input type="text" name="data[Resume][telephone]" id="telephone" value="<?php echo isset($data)&&$data['Resume']['telephone']?$data['Resume']['telephone']:''?>"/>
				   	</div>	   
				   </li>
				   	   <!--自我介绍-->
				   <li>
				   	<label for="doc-ipt-3" class="am-u-sm-4 am-form-label"><?php echo $ld['self_introduction']; ?></label>
				   	<div class="am-u-sm-8">
		   				<textarea name="data[Resume][introduce]">
		   					<?php echo isset($data)&&$data['Resume']['introduce']?str_replace("<br />", "\n", $data['Resume']['introduce']):''?>
		   				</textarea>
		   				<p><span><?php echo $ld['fill_in_personal_statement']; ?></span></p>
					</div>	   
				   </li>
				   <li>
				   	<div class="am-u-sm-4"></div>
				   	<div class="am-u-sm-8"></div>	   
				   </li>
				  </ul>
			</div>
			</form>
			</div>
		  </dd>
		</dl>
		</section>
		</div>
		<!--工作经验-->
		<div class="cont_r_3">
		  <section data-am-widget="accordion" class="am-accordion am-accordion-gapped" data-am-accordion='{  }'>
			  <dl class="am-accordion-item am-active">
			    <dt class="am-accordion-title">
			  <?php echo $ld['work_experience']; ?><span class="am-fr"><?php echo $ld['shrink']; ?></span>
			</dt>
		    <dd class="am-accordion-bd am-collapse am-in">
		      <div class="am-accordion-content">
		        <span id="r_experience"></span>
			<?php $k="";if(isset($all_experience_infos) && sizeof($all_experience_infos)>0){ foreach($all_experience_infos as $k=>$e){ $start_time = explode('-',$e['ResumeExperience']['start_time']);
				if($e['ResumeExperience']['end_time']==""||$e['ResumeExperience']['end_time']=="-"){
				$end_time=array();
				$end_time[0]=date('Y');
				$end_time[1]=date('m');
				}else{
				$end_time = explode('-',$e['ResumeExperience']['end_time']);
				}
			?>
			<form id="experience_form_<?php echo $k;?>" class="am-form am-form-horizontal" name="experience_form_<?php echo $k;?>"  method="POST">
			<div>
			  <div class="btn_content">
				<input type="hidden" name="data[ResumeExperience][id]" id="experience_form_<?php echo $k; ?>_id" value="<?php echo $e['ResumeExperience']['id'];?>"/>
				<button type="button" class="am-btn am-btn-primary am-btn-xs" onclick = "save_info('experience','experience_form_<?php echo $k;?>')"><?php echo $ld['user_save'] ;?></button>
				<button type="button" class="am-btn am-btn-primary am-btn-xs"  onclick = "delete_info('experience','experience_form_<?php echo $k;?>','<?php echo $e['ResumeExperience']['id'];?>')" style="margin-left:10px;"><?php echo $ld['delete'];?></button>
			  </div>
			  <ul class="am-avg-sm-1 am-avg-md-2  am-thumbnails">
			  <li>
			   	<div class="am-u-sm-4"><em>*</em><?php echo $ld['time']; ?></div> 
			    <div class="am-u-sm-8">
				   	<select name="start_year" style="width:49%;display:inline">
							<option value=""><?php echo $ld['year']; ?></option>
							<?php $year=date('Y'); for($i=$year;$i>=($year-70);$i--){?>
							<option value="<?php echo $i;?>" <?php echo ($start_time[0] == $i)?'selected':''?>><?php echo $i;?></option>
							<?php }?>
					</select>
					 <select name="start_month" style="width:49%;display:inline">
							<option  value=""><?php echo $ld['month']; ?></option>
							<?php for($i=1;$i<=12;$i++){?>
							<option value="<?php echo $i;?>" <?php echo ($start_time[1] == $i)?'selected':''?>><?php echo $i;?></option>
							<?php }?>
					 </select>
					 <span><?php echo $ld['to']; ?></span>

				</div>
			 	<div class="am-u-sm-4">&nbsp;</div>
			  	<div class="am-u-sm-8">
			  		<select name="end_year" style="width:49%;display:inline">
							<option  value=""><?php echo $ld['year']; ?></option>
							<?php $year=date('Y'); for($i=$year;$i>=($year-70);$i--){?>
							<option value="<?php echo $i;?>" <?php echo ($end_time[0] == $i)?'selected':''?>><?php echo $i;?></option>
							<?php }?>
					 </select>
					 <select name="end_month" style="width:49%;display:inline">
							<option  value=""><?php echo $ld['month']; ?></option>
							<?php for($i=1;$i<=12;$i++){?>
							<option value="<?php echo $i;?>" <?php echo ($end_time[1] == $i)?'selected':''?>><?php echo $i;?></option>
							<?php }?>
					 </select>
			 	 </div>
			 	</li>
				<li>
					<div>
			  			<span><?php echo $ld['not_fill_in_latter_two']; ?></span>
			  		</div>
			   </li>  
			  
			  <li>
			   	<div class="am-u-sm-4"><em>*</em><?php echo $ld['company']; ?></div> 
			    <div class="am-u-sm-8">
			      <input type="text" name="data[ResumeExperience][company_name]" value="<?php echo $e['ResumeExperience']['company_name']?>" />
			    </div> 
			   </li>  
			  <li>
			   	<div class="am-u-sm-4"><em>*</em><?php echo $ld['industry']; ?></div> 
			    <div class="am-u-sm-8">
			      <select name="data[ResumeExperience][company_type]">
							 <option value=""><?php echo $ld['please_select'];?></option>
							 <?php if(isset($informationresource_infos['job_type'])){ foreach($informationresource_infos['job_type'] as $ek=>$et){?>
							 <option value="<?php echo $ek?>"  <?php echo $e['ResumeExperience']['company_type']==$ek?'selected':''?>>
								 <?php echo $et?>
							 </option>
							 <?php }}?>
					</select>
				</div> 
			   </li>  
			  <li>
			   	<div class="am-u-sm-4"><em>*</em><?php echo $ld['department']; ?></div> 
			    <div class="am-u-sm-8">
			    	<input type="text"  name="data[ResumeExperience][department]" value="<?php echo $e['ResumeExperience']['department']?>"/>
			    </div> 
			   </li>  
			  <li>
			   	<div class="am-u-sm-4"><em>*</em><?php echo $ld['position']; ?></div> 
			    <div class="am-u-sm-8">
			    <input type="text" name="data[ResumeExperience][position]" value="<?php echo $e['ResumeExperience']['position']?>" />
			    </div> 
			   </li>  
			  <li>
			   	<div class="am-u-sm-4"><em>*</em><?php echo $ld['job_description']; ?></div> 
			    <div class="am-u-sm-8">
		    		<textarea name="data[ResumeExperience][description]" maxlength="2000">
			    		<?php echo str_replace("<br />", "\n", $e['ResumeExperience']['description']);?>
			    	</textarea>
				    <p>
					<span>
						<?php echo $ld['responsibilities_tasks_achievements']; ?>
					</span>
					</p>
					<p><span><?php echo $ld['limit_2000']; ?></span><span></span></p>
				</div> 
			   </li>  
			   
			  </ul>
		
			</div>
			</form>
			<?php }}?>
			<p class="btn_add_more">
				<span onclick="addinfo('experience','<?php echo isset($k)?$k:'';?>')"><button type="button" class="am-btn am-btn-primary am-btn-xs"><?php echo '+'.$ld['add']; ?></button></span>
			</p>
		    </div>
		  </dd>
		</dl>
		</section>
		</div>
		<!--教育经历-->
		<div class="cont_r_2">
			<section data-am-widget="accordion" class="am-accordion am-accordion-gapped" data-am-accordion='{  }'>
			  <dl class="am-accordion-item am-active">
			    <dt class="am-accordion-title">
						<?php echo $ld['education']; ?><span class="am-fr"><?php echo $ld['shrink']; ?></span>
			</dt>
		    <dd class="am-accordion-bd am-collapse am-in">
		      <div class="am-accordion-content">
		        <span id="r_education" ></span>
			<?php $k=""; if(isset($all_education_infos) && sizeof($all_education_infos)>0){ foreach($all_education_infos as $k=>$e){ $start_time = explode('-',$e['ResumeEducation']['start_time']);
				if($e['ResumeEducation']['end_time']==""||$e['ResumeEducation']['end_time']=="-"){
			   		$end_time=array();
					$end_time[0]=date('Y');
					$end_time[1]=date('m');
				}else{
					$end_time = explode('-',$e['ResumeEducation']['end_time']);
				}
			?>
			<form id="education_form_<?php echo $k;?>" class="am-form am-form-horizontal"  name="education_form_<?php echo $k;?>"  method="POST">
			<div>
				<div class="btn_content">
					<input type="hidden" name="data[ResumeEducation][id]"  id="education_form_<?php echo $k?>_id" value="<?php echo $e['ResumeEducation']['id'];?>"/>
					<button type="button" class="am-btn am-btn-primary am-btn-xs" onclick = "save_info('education','education_form_<?php echo $k;?>')"><?php echo $ld['user_save'] ;?></button>
					<button type="button" class="am-btn am-btn-primary am-btn-xs"  onclick = "delete_info('education','education_form_<?php echo $k;?>','<?php echo $e['ResumeEducation']['id'];?>')" style="margin-left:10px;"><?php echo $ld['delete'];?></button>
				</div>
			 <ul class="am-avg-sm-1 am-avg-md-2  am-thumbnails">
			  <li>
			   	<div class="am-u-sm-4"><em>*</em><?php echo $ld['time']; ?></div>
			   	<div class="am-u-sm-8">
			   		<select name="start_year" style="width:49%;display:inline">
						<option value=""><?php echo $ld['year']; ?></option>
						<?php $year=date('Y'); for($i=$year;$i>=($year-70);$i--){?>
						<option value="<?php echo $i;?>" <?php echo ($start_time[0] == $i)?'selected':''?>><?php echo $i;?></option>
						<?php }?>
					 </select>
					 <select name="start_month" style="width:49%;display:inline">
							<option  value=""><?php echo $ld['month']; ?></option>
							<?php for($i=1;$i<=12;$i++){?>
							<option value="<?php echo $i;?>" <?php echo ($start_time[1] == $i)?'selected':''?>><?php echo $i;?></option>
							<?php }?>
					 </select>
					 <span><?php echo $ld['to']; ?></span>
				</div>
				<div class="am-u-sm-4">&nbsp;</div>
				<div class="am-u-sm-8">
					<select name="end_year" style="width:49%;display:inline">
							<option  value=""><?php echo $ld['year']; ?></option>
							<?php $year=date('Y'); for($i=$year;$i>=($year-70);$i--){?>
							<option value="<?php echo $i;?>" <?php echo ($end_time[0] == $i)?'selected':''?>><?php echo $i;?></option>
							<?php }?>
					 </select>
					 <select name="end_month" style="width:49%;display:inline">
							<option  value=""><?php echo $ld['month']; ?></option>
							<?php for($i=1;$i<=12;$i++){?>
							<option value="<?php echo $i;?>" <?php echo ($end_time[1] == $i)?'selected':''?>><?php echo $i;?></option>
							<?php }?>
					 </select>
			</div>
			  </li> 
			  					 
			 <li><span><?php echo $ld['not_fill_in_latter_two']; ?></span></li>
			 	 
			  <li>
			  	<div class="am-u-sm-4"><em>*</em><?php echo $ld['school']; ?></div>
			  	<div class="am-u-sm-8">
			  					 <input type="text" name="data[ResumeEducation][school_name]" value="<?php echo $e['ResumeEducation']['school_name']?>"/>
			  	</div>
			  </li> 
			  	  <li></li>
			  	  
			  <li>
			  	<div class="am-u-sm-4"><em>*</em><?php echo $ld['major']; ?></div>
			  	<div class="am-u-sm-8"><input type="text"  name="data[ResumeEducation][major_type]" value="<?php echo $e['ResumeEducation']['major_type']?>"/></div>
			  </li> 
			  	  <li></li>
			  	  
			  <li>
			  	<div class="am-u-sm-4"><em>*</em><?php echo $ld['degree']; ?></div>
			  	<div class="am-u-sm-8"><select name="data[ResumeEducation][education_id]">
								 <option value=""><?php echo $ld['please_select'];?></option>
								 <?php if(isset($informationresource_infos['education_type'])){ foreach($informationresource_infos['education_type'] as $ek=>$et){?>
								 <option value="<?php echo $ek?>"  <?php echo $e['ResumeEducation']['education_id']==$ek?'selected':''?>>
									 <?php echo $et?>
								 </option>
								 <?php }}?>
								 </select></div>
			  </li> 
			  						 <li></li>
			  <li>
			  	<div class="am-u-sm-4"><?php echo $ld['major_description']; ?></div>
			  	<div class="am-u-sm-8"><textarea name="data[ResumeEducation][description]" maxlength="2000"><?php echo str_replace("<br />", "\n", $e['ResumeEducation']['description']);?></textarea><p><span><?php echo $ld['course_design_etc']; ?>
							</span></p><p><span><?php echo $ld['limit_2000']; ?></span><span></span></p></div>
			  </li> 
			  						 <li></li>
			  <li>
			  	<div class="am-u-sm-4"><?php echo $ld['study_abroad_experience']; ?></div>
			  	<div class="am-u-sm-8">
			  	  <div class="am-input-group input-box">
				    <span class="am-input-group-label">
				      <input type="checkbox" onclick="input_box_set(this,'input[type=text]')">
				      <input id="abroad" name="data[ResumeEducation][abroad]" type="hidden" value="0" >
				    </span>
				    <input type="text" class="am-form-field" disabled />
				  </div>
			  	</div>
			  </li> 
		 </ul>
		</div>
			</form>
			<?php }}?>
			<p class="btn_add_more">
				<span onclick="addinfo('education','<?php echo isset($k)?$k:'';?>')"><button type="button" class="am-btn am-btn-primary am-btn-xs"><?php echo '+'.$ld['add']; ?></button></span>
			</p>
		    </div>
		  </dd>
		</dl>
		</section>
		</div>
		<!--语言能力-->
		<div class="cont_r_4">
		<section data-am-widget="accordion" class="am-accordion am-accordion-gapped" data-am-accordion='{  }'>
			  <dl class="am-accordion-item am-active">
			    <dt class="am-accordion-title">
				<?php echo $ld['language_skills']; ?><span  class="am-fr"><?php echo $ld['shrink']; ?></span>
			    </dt>
		    <dd class="am-accordion-bd am-collapse am-in">
		      <div class="am-accordion-content">
		        <span id="r_language" ></span>			      
				<?php $k=""; if(isset($all_language_infos) && sizeof($all_language_infos)>0){ foreach($all_language_infos as $k=>$l){?>
			<form id="language_form_<?php echo $k;?>" class="am-form am-form-horizontal"  name="language_form_<?php echo $k;?>"  method="POST">
			<div>
				<div class="btn_content">
				<input type="hidden" name="data[ResumeLanguage][id]" id="language_form_<?php echo $k; ?>_id" value="<?php echo $l['ResumeLanguage']['id'];?>"/>
					<button type="button" class="am-btn am-btn-primary am-btn-xs" onclick = "save_info('language','language_form_<?php echo $k;?>')"><?php echo $ld['user_save'] ;?></button>
					<button type="button" class="am-btn am-btn-primary am-btn-xs"  onclick = "delete_info('language','language_form_<?php echo $k;?>','<?php echo $l['ResumeLanguage']['id'];?>')" style="margin-left:10px;"><?php echo $ld['delete'];?></button>
				</div>
				<ul class="am-avg-sm-1 am-avg-md-2  am-thumbnails">
				<li>
				<div class="am-u-sm-4"><em>*</em><?php echo $ld['language']; ?></div>
				<div class="am-u-sm-8">
								 <select name="data[ResumeLanguage][language_id]">
									 <option value=""><?php echo $ld['please_select'];?></option>
									 <?php if(isset($informationresource_infos['language_type'])){ foreach($informationresource_infos['language_type'] as $ek=>$et){?>
									 <option value="<?php echo $ek?>"  <?php echo $l['ResumeLanguage']['language_id']==$ek?'selected':''?>>
										 <?php echo $et;?>
									 </option>
									 <?php }}?>
								 </select>
				</div>
				</li>
				<li>
				<div class="am-u-sm-4"><em>*</em><?php echo $ld['language_mastery']; ?></div>
				<div class="am-u-sm-8">
								 <select name="data[ResumeLanguage][master_id]">
									 <option value=""><?php echo $ld['please_select'];?></option>
									 <?php if(isset($informationresource_infos['language_master_type'])){ foreach($informationresource_infos['language_master_type'] as $ek=>$et){?>
									 <option value="<?php echo $ek?>"  <?php echo $l['ResumeLanguage']['master_id']==$ek?'selected':''?>>
										 <?php echo $et;?>
									 </option>
									 <?php }}?>
								 </select></div>
				</li>
				<li>
				<div class="am-u-sm-4"><em>*</em><?php echo $ld['read_write_level']; ?></div>
				<div class="am-u-sm-8">
									 <select name="data[ResumeLanguage][rw_id]">
									 <option value=""><?php echo $ld['please_select'];?></option>
									 <?php if(isset($informationresource_infos['language_master_type'])){ foreach($informationresource_infos['language_master_type'] as $ek=>$et){?>
									 <option value="<?php echo $ek?>"  <?php echo $l['ResumeLanguage']['rw_id']==$ek?'selected':''?>>
										 <?php echo $et;?>
									 </option>
									 <?php }}?>
								 </select></div>
				</li>
				<li>
				<div class="am-u-sm-4"><em>*</em><?php echo $ld['listen_speak_level']; ?></div>
				<div class="am-u-sm-8">
									 <select name="data[ResumeLanguage][hs_id]">
									 <option value=""><?php echo $ld['please_select'];?></option>
									 <?php if(isset($informationresource_infos['language_master_type'])){ foreach($informationresource_infos['language_master_type'] as $ek=>$et){?>
									 <option value="<?php echo $ek?>"  <?php echo $l['ResumeLanguage']['hs_id']==$ek?'selected':''?>>
										 <?php echo $et;?>
									 </option>
									 <?php }}?>
								 </select></div>
				</li>				
				</ul>
				
				
				
			
			</div>
			</form>
			<?php }}?>
			<p class="btn_add_more">
				<span onclick="addinfo('language','<?php echo !isset($k)?$k:'';?>')"><button type="button" class="am-btn am-btn-primary am-btn-xs"><?php echo '+'.$ld['add']; ?></button></span>
			</p>
		   </div>
		  </dd>
		</dl>
		</section>
		</div>
		<div class="cont_r_5">
			<p class="btn_add_more" style="margin-left:2%;">
				<a href="<?php echo $html->url('/resumes/view');?>" target="_blank"><button type="button" class="am-btn am-btn-primary am-btn-sm"><?php echo $ld['preview']; ?></button></a>
			</p>
		</div>
	</div>
<script type='text/javascript'>
	var tmpNum = 0;
	var j_shrink = "<?php echo $ld['shrink']; ?>";
	var j_expand = "<?php echo $ld['expand']; ?>";
	var j_number_entered = <?php echo $ld['number_entered']; ?>;
	
	function save_base_info(){
		if($('#name').val()==""){
			alert("<?php echo $ld['fill_in_name']; ?>");
			$('#name').focus();
			return;
		}
		if($('#certificate_id').val()==""){
			alert("<?php echo $ld['select_year_of_work'].'!'; ?>");
			$('#certificate_id').focus();
			return;
		}
		if($('#experience_id').val()==""){
			alert("<?php echo $ld['select_year_of_work']; ?>");
			$('#experience_id').focus();
			return;
		}
		if($('#year').val()=="" || $('#month').val()=="" ||$('#day').val()==""){
			alert("<?php echo $ld['select_date_of_birth']; ?>");
			$('#year').focus();
			return;
		}
		if($('#apartments').val() == ""){
			alert("<?php echo $ld['fill_in_residency']; ?>");
			$('#apartments').focus();
			return;
		}
		if($('#email').val()==""){
			alert("<?php echo $ld['fill_in_email_address']; ?>");
			$('#email').focus();
			return;
		}
		if($("#job_name").val()==""){
			alert("<?php echo $ld['select_job']; ?>");
			$('#job_name').focus();
			return;
		}
		if($('#mobile').val()=="" && $('#telephone').val()==""){
			alert("<?php echo $ld['fill_in_contact_info']; ?>");
			$('#mobile').focus();
			return;
		}
   		var resume_id = $('#resume_id').val();
   		var sUrl = "/resumes/save_base_info";
		var postData = $('#base_info_form').serialize()+"&resume_id="+resume_id+"&is_ajax=1";
		var save_base_info_Success = function(result){
			if(result.resume_id){
				alert("<?php echo $ld['saved_successfully']; ?>");
				$('#resume_id').val(result.resume_id);
			}
		}
		$.post(sUrl,postData,save_base_info_Success,"json");
	}
	
	//<?php echo $ld['user_save'] ;?>信息
	function save_info(info_type,id){
		var resume_id = $('#resume_id').val();
		if(resume_id == ''){
			alert("<?php echo $ld['save_basic_info']; ?>");
			return;
		}
		if(info_type == "education"){
			var start_year = $('#'+id+' [name="start_year"]').val();
			var start_month = $('#'+id+' [name="start_month"]').val();
			var school_name = $('#'+id+' [name="data[ResumeEducation][school_name]"]').val();
			var major_type = $('#'+id+' [name="data[ResumeEducation][major_type]"]').val();
			var education_id = $('#'+id+' [name="data[ResumeEducation][education_id]"]').val();
			if(start_year == "" || start_month == ""){
				alert("<?php echo $ld['select_start_date']; ?>");
				return ;
			}
			if(school_name == ""){
				alert("<?php echo $ld['fill_in_school_name']; ?>");
				return ;
			}
			if(major_type == ""){
				alert("<?php echo $ld['fill_in_major']; ?>");
				return ;
			}
			if(education_id == ""){
				alert("<?php echo $ld['select_degree']; ?>")
				return ;
			}
		}else if(info_type == "experience"){
			var start_year = $('#'+id+' [name="start_year"]').val();
			var start_month = $('#'+id+' [name="start_month"]').val();
			var company_name = $('#'+id+' [name="data[ResumeExperience][company_name]"]').val();
			var company_type = $('#'+id+' [name="data[ResumeEducation][company_type]"]').val();
			var department = $('#'+id+' [name="data[ResumeExperience][department]"]').val();
			var position = $('#'+id+' [name="data[ResumeExperience][position]"]').val();
			var description = $('#'+id+' [name="data[ResumeExperience][description]"]').val();
			if(start_year == "" || start_month == ""){
				alert("<?php echo $ld['select_start_date']; ?>");
				return ;
			}
			if(company_name == ""){
				alert("<?php echo $ld['fill_in_company_name']; ?>");
				return ;
			}
			if(company_type == ""){
				alert("<?php echo $ld['fill_in_industry']; ?>");
				return ;
			}
			if(department == ""){
				alert("<?php echo $ld['fill_in_department']; ?>")
				return ;
			}
			if(position == ""){
				alert("<?php echo $ld['fill_in_position']; ?>")
				return ;
			}
			if(description == ""){
				alert("<?php echo $ld['fill_in_job_description']; ?>")
				return ;
			}
		}else if(info_type == "language"){
			var language_id = $('#'+id+' [name="data[ResumeLanguage][language_id]"]').val();
			var master_id = $('#'+id+' [name="data[ResumeLanguage][master_id]"]').val();
			var rw_id = $('#'+id+' [name="data[ResumeLanguage][rw_id]"]').val();
			var hs_id = $('#'+id+' [name="data[ResumeLanguage][hs_id]"]').val();
			if(language_id == ""){
				alert("<?php echo $ld['select_language']; ?>");
				return ;
			}
			if(master_id == ""){
				alert("<?php echo $ld['select_language_mastery']; ?>");
				return ;
			}
			if(rw_id == ""){
				alert("<?php echo $ld['select_read_write']; ?>")
				return ;
			}
			if(hs_id == ""){
				alert("<?php echo $ld['select_listen_speak']; ?>")
				return ;
			}
		}
   		var sUrl = "/resumes/save_info";
   		var postData = $('#base_info_form').serialize()+"&resume_id="+resume_id+"&is_ajax=1";
		var save_info_Success = function(result){
			$('#'+id+'_id').val(result.id);
			alert("<?php echo $ld['saved_successfully']; ?>");
		}
		$.post(sUrl,postData,save_info_Success,"json");
	}
	//<?php echo $ld['delete'];?>信息
	function delete_info(info_type,form_id,info_id){
		if(!confirm("<?php echo $ld['confirm_delete']; ?>")){
			return ;
		}
  		$('#'+form_id).remove();
  		var resume_id = $('#resume_id').val();
		if(info_type != ""){
	   		var sUrl = "/resumes/delete_info";
			var postData = {resume_id:resume_id,info_type:info_type,info_id:info_id,is_ajax:1};
			var delete_info_Success = function(result){
				//alert('<?php echo $ld['user_save'] ;?>成功！');
			}
			$.post(sUrl,postData,delete_info_Success,"json");
		}
	}
</script>