<style>
	article table * {line-height:2;}
	table img { margin-top:5px; }
</style>

<table width="778" border="0" cellpadding="0" align="center">
    <tbody>
        <tr>
            <td>
            <table style="BORDER-TOP-WIDTH: 0px; BORDER-LEFT-WIDTH: 0px; BORDER-BOTTOM-WIDTH: 0px; MARGIN: 0px auto; BORDER-RIGHT-WIDTH: 0px" cellspacing="0" cellpadding="0" width="760" align="center" border="0">
                <tbody>
                    <tr>
                        <td style="BORDER: #93B0ED 2px solid;" valign="top" colspan="2">
                        <table cellspacing="0" cellpadding="0" width="760" align="center" border="0">
                            <tbody>
                                <tr>
                                    <td valign="top">
                                    <?php if(isset($resume_info) && sizeof($resume_info)>0) {?>
                                    <table style="BORDER: #3076BC 1px solid; PADDING-RIGHT: 0px; PADDING-LEFT: 8px; BACKGROUND: #F5FAFE; PADDING-BOTTOM: 0px; MARGIN: 8px auto;  LINE-HEIGHT: 22px; PADDING-TOP: 8px; " cellspacing="0" cellpadding="0" width="97%" align="center" border="0">
                                        <tbody>
                                            <tr>
                                                <td style="BORDER-BOTTOM: #88b4e0 1px dashed" height="30"><span style="FONT-SIZE: 25px; LINE-HEIGHT: 30px; HEIGHT: 30px"> <strong><?php echo $resume_info['Resume']['name']; ?></strong> </span></td>
                                            </tr>
                                            <tr>
                                                <td valign="top">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                        <tr>
                                                            <td height="26" colspan="4">
                                        <span class="blue1"><b>
                                        <?php echo $ld['year_of_work_experience']; ?>:
                                        <?php echo isset($informationresource_info['experience_type'])&&isset($informationresource_info['experience_type'][$resume_info['Resume']['experience_id']])?$informationresource_info['experience_type'][$resume_info['Resume']['experience_id']]:$ld['other'];?>&nbsp;&nbsp;|&nbsp;&nbsp;
                                        <?php echo ($resume_info['Resume']['sex']==1)?$ld['female']:$ld['male']; ?>&nbsp;&nbsp;|&nbsp;&nbsp;
                                        	<?php $birthday = explode('-' , $resume_info['Resume']['birthday']);?><?php if(isset($birthday[0])){echo $year-$birthday[0];};?>
                                        		<?php echo $ld['years_old']; ?>(<?php if( isset($birthday[0])){echo $birthday[0];};?><?php //echo $ld['year']; ?>
                                        			<?php if( isset($birthday[1])){echo $birthday[1]; };?><?php echo $ld['month']; ?><?php if( isset($birthday[2])){echo $birthday[2];};?>
                                        				<?php echo $ld['day']; ?>) </b> </span></td>
                                                            <td width="17%" rowspan="6" align="center" valign="middle">
                                                            <?php echo $html->image(($resume_info['Resume']['avatar']!='')?$resume_info['Resume']['avatar']:$admin_webroot.'/themed'.$admin_webroot.'img/app/default.png',array('id'=>'avatar','width'=>"110" ,'height'=>"110"))?>
                                                            <span style="display:block;"> (ID:<?php echo $resume_info['Resume']['id']; ?>) </span>
                                                            </td>
                                                        </tr>
                                       					<tr>
                                                            <td height="20"><?php echo $ld['id_type']; ?>：</td>
                                                            <td height="20" colspan="3"><?php echo isset($informationresource_info['certificate_type'])&&isset($informationresource_info['certificate_type'][$resume_info['Resume']['certificate_id']])?$informationresource_info['certificate_type'][$resume_info['Resume']['certificate_id']]:''; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td height="20"><?php echo $ld['id']; ?>：</td>
                                                            <td height="20" colspan="3"><?php echo $resume_info['Resume']['certificate_num']; ?></td>
                                                        </tr>
                                        				<tr>
                                                            <td width="15%" height="20"><?php echo $ld['job_title_yp']; ?>：</td>
                                                            <td width="42%" height="20"><?php echo isset($job_list_data[$resume_info['Resume']['job_id']])?$job_list_data[$resume_info['Resume']['job_id']]:''; ?></td>
                                                            <td width="11%" height="20"></td><td width="20%" height="20"></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="10%" height="20"><?php echo $ld['residency']; ?>：</td>
                                                            <td width="42%" height="20"><?php echo $resume_info['Resume']['apartments']; ?></td>
                                                            <td width="11%" height="20"></td><td width="20%" height="20"></td>
                                                        </tr>
                                                        <tr>
                                                            <td height="20"><?php echo $ld['phone']?>：</td>
                                                            <td height="20" colspan="3"><?php if($resume_info['Resume']['mobile'] != ""){ echo $resume_info['Resume']['mobile'].'('.$ld['mobile'].')'; }?><?php if($resume_info['Resume']['telephone'] !="" ){ echo $resume_info['Resume']['telephone'].'('.$ld['phone'].')';} ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td height="20">E-mail：</td>
                                                            <td height="20" colspan="3"><a href="mailto:<?php echo $resume_info['Resume']['email'] ?>
                                                            " class="blue1"> <?php echo $resume_info['Resume']['email'] ?> </a></td>
                                                        </tr>
                                                    </tbody>
                                                </table></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php }?>
                                    <hr>
                                    <table width="97%" border="0" align="center" cellspacing="0" cellpadding="0" style="margin-left: 23px;">
                                        <tbody>
                                            <tr>
                                                <td align="left" valign="middle" class="cvtitle" style="color:#322D20;font-weight: bold;"><?php echo $ld['work_experience']; ?></td>
                                            </tr>
                                            <tr>
                                                <td align="middle" valign="middle" height="4"><img src="http://img01.51jobcdn.com/im/2009/resumetemplate/line1_1.gif" width="100%" height="4" style="padding-left: 1px"></td>
                                            </tr>
                                            <tr>
                                                <td height="10" align="left" valign="middle"></td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle">
			                                   <?php if(isset($resume_experience_infos) && sizeof($resume_experience_infos)>0){ foreach($resume_experience_infos as $k=>$e){ $start_time = explode('-',$e['ResumeExperience']['start_time']);
			                                    	      	  if($e['ResumeExperience']['end_time']==""||$e['ResumeExperience']['end_time']=="-"){
			                                    	      	      $end_time=array();
			                                    	      	      $end_time[0]=date('Y');
			                                    	      	      $end_time[1]=date('m');
			                                    	      	  }else{
			                                    	      	  	  $end_time = explode('-',$e['ResumeExperience']['end_time']);
			                                    	      	  }
			                                            $months = (($end_time[0] - $start_time[0])*12 - $start_time[1] + $end_time[1]);?>
	                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_set" style="margin-top:10px;">
	                                                    <tbody>
	                                                        <tr>
	                                                            <td colspan="2" class="text_left"><?php echo str_replace('-',' /',$e['ResumeExperience']['start_time']);?>--<?php echo ($e['ResumeExperience']['end_time']!='')? str_replace('-',' /',$e['ResumeExperience']['end_time']):$ld['till_now'];?>：<?php echo $e['ResumeExperience']['company_name'];?>
	                                                            [ <?php echo intval($months/12);?><?php echo $ld['year']; ?><?php echo intval($months%12);?><?php echo $ld['months']; ?>] </td>
	                                                        </tr>
	                                                        <tr>
	                                                            <td width="22%" class="text_left"><?php echo $ld['industry_belong']; ?>：</td>
	                                                            <td width="78%" class="text"><?php echo isset($informationresource_info['job_type'])&&isset($informationresource_info['job_type'][$e['ResumeExperience']['company_type']])?$informationresource_info['job_type'][$e['ResumeExperience']['company_type']]:$ld['other'];?></td>
	                                                        </tr>
	                                                        <tr>
	                                                            <td class="text_left"><b><?php echo $e['ResumeExperience']['department']?></b></td>
	                                                            <td class="text"><b><?php echo $e['ResumeExperience']['position']?></b></td>
	                                                        </tr>
	                                                        <tr>
	                                                            <td class="text_left" colspan="2"><?php echo $ld['job_description']; ?>：</td>
	                                                        </tr>
	                                                        <tr>
	                                                            <td id="Cur_Val" colspan="2" valign="top"><?php echo $e['ResumeExperience']['description'];?></td>
	                                                        </tr>
	                                                    </tbody>
	                                                </table>
			                                   <?php }}?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="20" align="left" valign="middle"></td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="cvtitle" style="color:#322D20;font-weight: bold;"><?php echo $ld['education']; ?></td>
                                            </tr>
                                            <tr>
                                                <td align="middle" valign="middle" height="4"><img src="http://img01.51jobcdn.com/im/2009/resumetemplate/line1_1.gif" width="100%" height="4" style="padding-left: 1px"></td>
                                            </tr>
                                            <tr>
                                                <td height="10" align="left" valign="middle"></td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle">
			                                   <?php if(isset($resume_education_infos) && sizeof($resume_education_infos)>0){ foreach($resume_education_infos as $k=>$e){?>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_set">
                                                    <tbody>
                                                        <tr>
                                                            <td width="26%" class="text_left"><?php echo str_replace('-',' /',$e['ResumeEducation']['start_time']);?>--<?php echo ($e['ResumeEducation']['end_time']!='')? str_replace('-',' /',$e['ResumeEducation']['end_time']):$ld['till_now'];?></td>
                                                            <td width="30%" class="text"><?php echo $e['ResumeEducation']['school_name']?></td>
                                                            <td width="30%" class="text"><?php echo $e['ResumeEducation']['major_type']?></td>
                                                            <td width="14%" class="text"><?php echo isset($informationresource_info['education_type'])&&isset($informationresource_info['education_type'][$e['ResumeEducation']['education_id']])?$informationresource_info['education_type'][$e['ResumeEducation']['education_id']]:$ld['other'];?></td>
                                                        </tr>
                                                        <tr>
                                                            <td id="Cur_Val" colspan="4" valign="top" height="30"><?php echo $e['ResumeEducation']['description']?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
			                                   <?php }}?>
			                                   	</td>
                                            </tr>
                                            <tr>
                                                <td height="20" align="left" valign="middle"></td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="cvtitle" style="color:#322D20;font-weight: bold;"><?php echo $ld['language_skills']; ?></td>
                                            </tr>
                                            <tr>
                                                <td align="middle" valign="middle" height="4"><img src="http://img01.51jobcdn.com/im/2009/resumetemplate/line1_1.gif" width="100%" height="4" style="padding-left: 1px"></td>
                                            </tr>
                                            <tr>
                                                <td height="10" align="left" valign="middle"></td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle">
			                                   <?php if(isset($resume_language_infos) && sizeof($resume_language_infos)>0){ foreach($resume_language_infos as $k=>$l){?>
                                                <table border="0" cellspacing="0" cellpadding="0" style="width:100%">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:85%">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_set">
                                                                <tbody>
                                                                    <tr height="25">
                                                                        <td width="130" class="text_left">
                                                                        <?php echo isset($informationresource_info['language_type'])&&isset($informationresource_info['language_type'][$l['ResumeLanguage']['language_id']])?$informationresource_info['language_type'][$l['ResumeLanguage']['language_id']]:$ld['other'];?>
                                                                       （<?php echo isset($informationresource_info['language_master_type'])&&isset($informationresource_info['language_master_type'][$l['ResumeLanguage']['master_id']])?$informationresource_info['language_master_type'][$l['ResumeLanguage']['master_id']]:$ld['other'];?>）
                                                                        </td>
                                                                        <td class="text"> <?php echo $ld['listen_speak']; ?>（<?php echo isset($informationresource_info['language_master_type'])&&isset($informationresource_info['language_master_type'][$l['ResumeLanguage']['hs_id']])?$informationresource_info['language_master_type'][$l['ResumeLanguage']['hs_id']]:$ld['other'];?>），<?php echo $ld['read_write']; ?>（<?php echo isset($informationresource_info['language_master_type'])&&isset($informationresource_info['language_master_type'][$l['ResumeLanguage']['rw_id']])?$informationresource_info['language_master_type'][$l['ResumeLanguage']['rw_id']]:$ld['other'];?>） </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
			                                   <?php }}?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="20" align="left" valign="middle"></td>
                                            </tr>
                                        </tbody>
                                    </table></td>
                                </tr>
                            </tbody>
                        </table></td>
                    </tr>
                    <tr style="HEIGHT: 10px">
                        <td style="HEIGHT: 10px" colspan="2"></td>
                    </tr>
                    <tr>
                        <td style="WIDTH: 100%; HEIGHT: 20px" align="middle" colspan="2"></td>
                    </tr>
                </tbody>
            </table></td>
        </tr>
    </tbody>
</table>