
<?php echo $htmlSeevia->css(array('common'));?>
<div class="jobs_com"  style="font-size:14px;">
  <div class="am-g am-g-fixed">
	<div>
		<ol class="am-breadcrumb">
		  <li><a href="<?php echo $html->url('/jobs/'); ?>"><?php echo $ld['recruitment_page'];?></a></li>
		  <li  class="am-active"><?php echo $ld['recruitment_details'];?></li>
		  <li><a href="<?php echo $html->url('/resumes/'); ?>"><?php echo $ld['fill_resume'];?></a></li>
		</ol>
	</div>
    <div style="background-color:#FFFFFF;margin: 5px 6px;padding-top:6px;">
    <div class="am-u-sm-12" style="background-color:#CCCCCC;"><strong style="font-size:18px;"><?php echo $ld['position_info']; ?></strong></div>
    
    <ul id="jobs" class="am-avg-sm-1 am-avg-md-2 am-thumbnails" style="margin-top:40px;">
    	<!--职位名称-->
    	<li style="border-bottom:1px solid #CCCCCC; padding-top:8px;padding-bottom:0px;">
    		<div  class="am-u-sm-4"><strong><?php echo $ld['job_title'];?>:</strong></div>
    		<div class="am-u-sm-8"><?php echo $job_info['JobI18n']['name'];?></div>
    	</li>
    	<li style="border-bottom:1px solid #CCCCCC; padding-top:8px;padding-bottom:0px;">
    		<div  class="am-u-sm-4">
    			<div><strong>&nbsp;</strong></div>
    		</div>
    		<div>
    			<div>&nbsp;</div>
    		</div>
    	</li>
    	<!--发布日期-->
	    <li style="border-bottom:1px solid #CCCCCC; padding-top:8px;padding-bottom:0px;">
		    <div class="am-u-sm-4"><strong><?php echo $ld['post_date']; ?>：</strong></div>
		    <div class="am-u-sm-8"><?php echo $job_info['Job']['created'];?></div>
	    </li>
	    <!--工作地点-->
	    <li style="border-bottom:1px solid #CCCCCC; padding-top:8px;padding-bottom:0px;">
		    <div class="am-u-sm-4"><strong><?php echo $ld['place_of_work']; ?>：</strong></div>
		    <div class="am-u-sm-8"><?php echo $job_info['JobI18n']['address'];?></div>
	    </li>
	    <!--招聘人数-->
	    <li style="border-bottom:1px solid #CCCCCC; padding-top:8px;padding-bottom:0px;">
		    <div class="am-u-sm-4"><strong><?php echo $ld['recruitment_number']; ?>：</strong></div>
		    <div class="am-u-sm-8"><?php echo $job_info['Job']['number'];?></div>
	   	</li>
	   	<!--工作经验-->
	    <li style="border-bottom:1px solid #CCCCCC; padding-top:8px;padding-bottom:0px;">
		    <div class="am-u-sm-4"><strong ><?php echo $ld['work_experience']; ?>：</strong></div>
		    <div class="am-u-sm-8">
					<?php echo isset($informationresource_infos['experience_type']) && 								isset($informationresource_infos['experience_type'][$job_info['Job']['experience_id']])?$informationresource_infos['experience_type'][$job_info['Job']['experience_id']]:'';?>
		    </div>
	    </li>
	    <!--征才条件-->
	    <li style="border-bottom:1px solid #CCCCCC; padding-top:8px;padding-bottom:0px;">
		    <div class="am-u-sm-4"><strong><?php echo $ld['recruitment_requirements']; ?>：</strong></div>
		    <div class="am-u-sm-8">
		    	<?php echo isset($informationresource_infos['education_type']) && isset($informationresource_infos['education_type'][$job_info['Job']['education_id']])?$informationresource_infos['education_type'][$job_info['Job']['education_id']]:'';?>
		    </div>
	    </li>
	   	<li style="border-bottom:1px solid #CCCCCC; padding-top:8px;padding-bottom:0px;"><div>&nbsp;</div></li>
	    </ul>
	    
	   
	    <ul id="ul2" class="am-avg-sm-1  am-thumbnails" >
	    <li >
		    <div class="am-u-sm-2">
		    	<strong style="display: block;padding: 20px 0 10px 0;margin: 0 5px;"><?php echo $ld['job_description_2']; ?>:
		    	</strong>
		    </div>
		    <div class="am-u-sm-10">
		    	<div  style="padding-bottom:14px;margin: 20px 0px;">
		    		<p align="left"><?php echo $job_info['JobI18n']['detail'];?>&nbsp;</p>
		    	</div>
		    </div>
		    
	    </li>
	    
    </ul>
    <a href="<?php echo $html->url('/resumes'); ?>?job_id=<?php echo $job_info['Job']['id']; ?>" target="_blank" style="margin:0px 0px 15% 18%;>
    		<button type="button" class="am-btn am-btn-primary am-btn-xs" ">
    			<?php echo $ld['apply_now']; ?>
    		</button>
    </a>
		
    </div>
  </div>
</div>
<script type="text/javascript">
var wechat_shareTitle="<?php echo $job_info['JobI18n']['name'] ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($job_info['JobI18n']['detail']); ?>";
var wechat_lineLink=location.href.split('#')[0];
</script>