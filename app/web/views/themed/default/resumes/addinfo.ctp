<?php ob_start();?>
<style type="text/css">
.education_form em{color:#ff0000; }
.education_form .am-form-label{text-align:left;}
</style>
<?php if($info_type == 'education'){?>
	  <form id="education_form_<?php echo $count?>" name="education_form_<?php echo $count?>" class="am-form am-form-horizontal education_form"  method="POST">
            <div id="education_form1">
                <div class="btn_content">
                    <input type="hidden" name="data[ResumeEducation][id]" id="education_form_<?php echo $count?>_id" value=""/>
                    <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick = "save_info('education','education_form_<?php echo $count?>')"><?php echo $ld['user_save'] ;?></button>
                    <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick = "delete_info('education','education_form_<?php echo $count?>','')" style="margin-left:10px;"><?php echo $ld['delete'];?></button>
                </div>
                <ul class="am-avg-sm-1 am-avg-md-2 am-thumbnails">
                <li>
                        <div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['time']; ?></div>
                        <div class="am-u-sm-8">
                                <select name="start_year" style="width:49%;display:inline">
                                        <option value=""><?php echo $ld['year']; ?></option>
                                        <?php $year=date('Y'); for($i=$year;$i>=($year-70);$i--){?>
                                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php }?>
                                </select>
                                <select name="start_month" style="width:49%;display:inline">
                                        <option  value=""><?php echo $ld['month']; ?></option>
                                        <?php for($i=1;$i<=12;$i++){?>
                                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php }?>
                                </select>
                        </div>
                </li>
                <li>
                	  <div class="am-u-lg-1 am-u-sm-4 am-form-label"><?php echo $ld['to']; ?></div>
                	  <div class="am-u-sm-8">
                                <select name="end_year" style="width:49%;display:inline">
                                        <option  value=""><?php echo $ld['year']; ?></option>
                                        <?php $year=date('Y'); for($i=$year;$i>=($year-70);$i--){?>
                                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php }?>
                                </select>
                                <select name="end_month" style="width:49%;display:inline">
                                        <option  value=""><?php echo $ld['month']; ?></option>
                                        <?php for($i=1;$i<=12;$i++){?>
                                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php }?>
                                </select>
                       </div>
                       <div class="am-show-sm-only am-u-sm-4">&nbsp;</div>
                	  <div class="am-u-lg-3 am-u-sm-8"><?php echo $ld['not_fill_in_latter_two']; ?></div>
                 </li>
                <li>
                        <div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['school']; ?></div>
                        <div class="am-u-sm-8"><input type="text" name="data[ResumeEducation][school_name]"/></div>
                </li>
                <li></li>
                <li>
                        <div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['major']; ?></div>
                        <div class="am-u-sm-8"><input type="text"  name="data[ResumeEducation][major_type]"/></div>
                </li>
                <li></li>
                <li>
                        <div class="am-u-sm-4"><em>*</em><?php echo $ld['degree']; ?></div>
                        <div class="am-u-sm-8">
                            	 <select name="data[ResumeEducation][education_id]">
                                    	 <option  value=""><?php echo $ld['please_select'];?></option>
                                	     <?php if(isset($informationresource_infos['education_type'])){ foreach($informationresource_infos['education_type'] as $ek=>$et){?>
                                    	 <option value="<?php echo $ek?>" >
                                    	     <?php echo $et?>
                                    	 </option>
                            	<?php }}?>
                            	 </select>
                         </div>
                </li>
                <li></li>
                <li>
                        <div class="am-u-sm-4 am-form-label"><?php echo $ld['major_description']; ?></div>
                        <div class="am-u-sm-8"><textarea name="data[ResumeEducation][description]" maxlength="2000"></textarea><p><span><?php echo $ld['course_design_etc']; ?>
                                    </span></p><p><span><?php echo $ld['limit_2000']; ?></span><span></span></p></div>
                </li>
                <li></li>
                <li>
                  <div class="am-u-sm-4 am-form-label"><?php echo $ld['study_abroad_experience']; ?></div>
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
<?php }elseif($info_type == "experience"){?>
	  <form id="experience_form_<?php echo $count?>" name="experience_form_<?php echo $count?>" class="am-form am-form-horizontal education_form"  method="POST">
            <div>
                <div class="btn_content">
                    <input type="hidden" name="data[ResumeExperience][id]" id="experience_form_<?php echo $count; ?>_id" value=""/>
                    <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick = "save_info('experience','experience_form_<?php echo $count;?>')"><?php echo $ld['user_save'] ;?></button>
                    <button type="button" class="am-btn am-btn-primary am-btn-xs"  onclick = "delete_info('experience','experience_form_<?php echo $count;?>','')" style="margin-left:10px;"><?php echo $ld['delete'];?></button>
                </div>
               <ul class="am-avg-sm-1 am-avg-md-2 am-thumbnails">
                <li>
                        <div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['time']; ?></div>
                        <div class="am-u-sm-8">
	          					<select name="start_year" style="width:49%;display:inline">
		                                <option value=""><?php echo $ld['year']; ?></option>
		                                <?php $year=date('Y'); for($i=$year;$i>=($year-70);$i--){?>
		                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
		                                <?php }?>
	                            </select>
	                            <select name="start_month" style="width:49%;display:inline">
		                                <option  value=""><?php echo $ld['month']; ?></option>
		                                <?php for($i=1;$i<=12;$i++){?>
		                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
		                                <?php }?>
	                            </select>
                         </div>
                </li>
                <li>
               	  <div class="am-u-lg-1 am-u-sm-4 am-form-label"><?php echo $ld['to']; ?></div>
                	  <div class="am-u-sm-8">
                                <select name="end_year" style="width:49%;display:inline">
                                        <option  value=""><?php echo $ld['year']; ?></option>
                                        <?php $year=date('Y'); for($i=$year;$i>=($year-70);$i--){?>
                                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php }?>
                                </select>
                                <select name="end_month" style="width:49%;display:inline">
                                        <option  value=""><?php echo $ld['month']; ?></option>
                                        <?php for($i=1;$i<=12;$i++){?>
                                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php }?>
                                </select>
                       </div>
                       <div class="am-show-sm-only am-u-sm-4">&nbsp;</div>
                	  <div class="am-u-lg-3 am-u-sm-8"><?php echo $ld['not_fill_in_latter_two']; ?></div>
               </li>
               <li>
			   	<div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['company']; ?></div> 
			    <div class="am-u-sm-8">
			    	 <input type="text" name="data[ResumeExperience][company_name]" value="" />				 
			    </div>
			  </li>  
			  <li>
			   	<div class="am-u-sm-4 am-form-label"><em >*</em><?php echo $ld['industry']; ?></div> 
			    <div class="am-u-sm-8">
			      <select>
                            	 <option  value=""><?php echo $ld['please_select'];?></option>
                        	     <?php if(isset($informationresource_infos['job_type'])){ foreach($informationresource_infos['job_type'] as $ek=>$et){?>
                            	 <option value="<?php echo $ek?>">
                            	     <?php echo $et?>
                            	 </option>
                            	 <?php }}?>
                            	 </select>
				</div> 
			   </li>  
                <li>
                 <div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['department']; ?></div>
                  <div class="am-u-sm-8"><input type="text"  name="data[ResumeExperience][department]" value=""/></div>
                </li>
                <li>
                <div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['position']; ?></div>
                <div class="am-u-sm-8"><input type="text" name="data[ResumeExperience][position]" value="" /></div>
                </li>
                <li>
                <div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['job_description']; ?></div>
                <div class="am-u-sm-8"><textarea name="data[ResumeExperience][description]" maxlength="2000"></textarea><p><span><?php echo $ld['responsibilities_tasks_achievements']; ?>
                            </span></p><p><span><?php echo $ld['limit_2000']; ?></span><span></span></p></div>
                </li>
                
                </ul>
              
            </div>
      </form>
<?php }elseif($info_type == "language"){?>
            <form id="language_form_<?php echo $count;?>" name="language_form_<?php echo $count;?>" class="am-form am-form-horizontal education_form"  method="POST">
            <div>
                <div class="btn_content">
                    <input type="hidden" name="data[ResumeLanguage][id]" id="language_form_<?php echo $count; ?>_id" value=""/>
                    <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick = "save_info('language','language_form_<?php echo $count;?>')"><?php echo $ld['user_save'] ;?></button>
                    <button type="button" class="am-btn am-btn-primary am-btn-xs"  onclick = "delete_info('language','language_form_<?php echo $count;?>','')" style="margin-left:10px;"><?php echo $ld['delete'];?></button>
                </div>
                 <ul class="am-avg-sm-1 am-avg-md-2 am-thumbnails">
                 <li>
                         <div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['language']; ?></div>
                         <div class="am-u-sm-8">
                                 <select name="data[ResumeLanguage][language_id]">
                                             <option value=""><?php echo $ld['please_select'];?></option>
        	                    	         <?php if(isset($informationresource_infos['language_type'])){ foreach($informationresource_infos['language_type'] as $ek=>$et){?>
        	                            	 <option value="<?php echo $ek?>" >
        	                            	     <?php echo $et;?>
        	                            	 </option>
        	                            	 <?php }}?>
                                         </select>
                        </div>
                 </li>
                 <li>
                         <div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['language_mastery']; ?></div>
                         <div class="am-u-sm-8"><select name="data[ResumeLanguage][master_id]">
                                     <option value=""><?php echo $ld['please_select'];?></option>
	                    	         <?php if(isset($informationresource_infos['language_master_type'])){ foreach($informationresource_infos['language_master_type'] as $ek=>$et){?>
	                            	 <option value="<?php echo $ek?>" >
	                            	     <?php echo $et;?>
	                            	 </option>
	                            	 <?php }}?>
                                 </select></div>
                 </li>
                 <li>
                         <div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['read_write_level']; ?></div>
                         <div class="am-u-sm-8"><select name="data[ResumeLanguage][rw_id]">
                                     <option value=""><?php echo $ld['please_select'];?></option>
	                    	         <?php if(isset($informationresource_infos['language_master_type'])){ foreach($informationresource_infos['language_master_type'] as $ek=>$et){?>
	                            	 <option value="<?php echo $ek?>" >
	                            	     <?php echo $et;?>
	                            	 </option>
	                            	 <?php }}?>
                                 </select></div>
                 </li>
                 <li>
                         <div class="am-u-sm-4 am-form-label"><em>*</em><?php echo $ld['listen_speak_level']; ?></div>
                         <div class="am-u-sm-8">
                         <select name="data[ResumeLanguage][hs_id]">
                                     <option value=""><?php echo $ld['please_select'];?></option>
	                    	         <?php if(isset($informationresource_infos['language_master_type'])){ foreach($informationresource_infos['language_master_type'] as $ek=>$et){?>
	                            	 <option value="<?php echo $ek?>" >
	                            	     <?php echo $et;?>
	                            	 </option>
	                            	 <?php }}?>
                                 </select></div>
                 </li>
                 
                 
                 </ul>
                
            </div>
<?php }?>
<?php
	$result['data'] = ob_get_contents();
	ob_end_clean();
	echo json_encode($result);
?>