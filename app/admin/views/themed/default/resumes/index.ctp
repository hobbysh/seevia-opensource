<style>
   li{margin-top:10px;}
	 
</style>
<div class="listsearch">
    <?php echo $form->create('resume',array('action'=>'/','name'=>"SeearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3">
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['job_title'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" >
                <select name="job_id" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
                    <option value="" selected><?php echo $ld['all_data']?></option>
                    <?php if(isset($job_list)&&sizeof($job_list)>0){ foreach($job_list as $k=>$v){?>
                        <option value="<?php echo $v['Job']['id'];?>" <?php echo isset($job_id)&&$job_id == $v['Job']['id']?'selected':'';?>><?php echo $v['JobI18n']['name'];?></option>
                    <?php }}?>
                </select>
            </div>
        </li>
        <li  >
            <label class=" am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text"><?php echo $ld['keyword'];?> </label>
            <div class=" am-u-lg-7 am-u-md-8 am-u-sm-8 am-u-end"  >
                <input type="text" name="keywords" value="<?php echo @$keywords;?>" placeholder="<?php echo $ld['name']; ?>/<?php echo $ld['email']; ?>/<?php echo $ld['mobile']; ?>/"/>
            </div>
        </li>
        <li>
            <label class=" am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['year_of_work_experience']; ?> </label>
            <div class=" am-u-lg-7 am-u-md-8 am-u-sm-8 am-u-end"  >
                <select name="experience_id" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
                    <option value="-1" selected ><?php echo $ld['all_data']?></option>
                    <?php if(isset($informationresource_info['experience_type'])&&sizeof($informationresource_info['experience_type'])>0){ foreach($informationresource_info['experience_type'] as $k=>$v){?>
                        <option value="<?php echo $k;?>" <?php echo isset($experience_id)&&$experience_id == $k?'selected':'';?>><?php echo $v;?></option>
                    <?php }}?>
                </select>
            </div>
        </li>
        <li>
         <label class=" am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
        	<div class="am-u-lg-8 am-u-md-8 am-u-sm-8  am-u-end">		
             <input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['search'];?>"/>
             </div>        
         </li>
    </ul>
    <?php echo $form->end()?>
</div>
<p class="am-u-md-12 am-text-right am-btn-group-xs ">

	<?php if($svshow->operator_privilege('resumes_add')){echo $html->link($ld['position_management'],"/jobs/",array("class"=>"am-btn am-radius am-btn-sm am-btn-default "),'',false,false);}?>&nbsp;
			<?php if($svshow->operator_privilege('resumes_add')){?>
		         <a class="am-btn  am-btn-warning am-radius" href="<?php echo $html->url('/jobs/view/')?>">
		         	  <span class="am-icon-plus"></span>&nbsp;<?php echo $ld['add_position'] ?>
		        </a>
	<?php }?>
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table id="t1" class="am-table  table-main">
        <thead>
        <tr>
            <th class="am-show-lg-only"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['name']; ?></b></label></th>
           
            <th><?php echo $ld['job_title']; ?></th>
            <th><?php echo $ld['gender']?></th>
            <th class="am-show-lg-only"><?php echo $ld['email']; ?></th>
            <th class="am-show-lg-only"><?php echo $ld['mobile']; ?></th>
            <th class="am-show-lg-only"><?php echo $ld['residency']; ?></th>
            <th class="am-show-lg-only"><?php echo $ld['year_of_work_experience']; ?></th>
            <th><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($resume_list) && sizeof($resume_list)>0){foreach($resume_list as $k=>$v){?>
            <tr>
                <td class="am-show-lg-only"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Resume']['id']?>" /><?php echo $v['Resume']['name']?></label></td>
                
                <td><?php echo isset($job_list_data[$v['Resume']['job_id']])?$job_list_data[$v['Resume']['job_id']]:'' ?></td>
                <td><?php echo ($v['Resume']['sex'] == 0)?$ld['male']:$ld['female'];?></td>
                <td class="am-show-lg-only"><?php echo $v['Resume']['email']?></td>
                <td class="am-show-lg-only"><?php echo $v['Resume']['mobile']?></td>
                <td class="am-show-lg-only"><?php echo $v['Resume']['apartments']?></td>
                <td class="am-show-lg-only"><?php echo isset($informationresource_info['experience_type'])&&isset($informationresource_info['experience_type'][$v['Resume']['experience_id']])?$informationresource_info['experience_type'][$v['Resume']['experience_id']]:'';?></span></td>
                <td>
			<a class=" am-seevia-btn mt am-btn am-btn-success am-btn-xs  am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/resumes/'.$v['Resume']['id']); ?>"> <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
			</a>
			<a class="mt am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'resumes/remove/<?php echo $v['Resume']['id'] ?>')"> <span class="am-icon-trash-o"></span> <?php echo $ld['delete'] ?>
			</a>
                    	
                    </td>
            </tr>
        <?php }}else{?>
            <tr>
                <td colspan="9" class="no_data_found"><?php echo $ld['no_data_found']; ?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if(isset($resume_list) && sizeof($resume_list)){?>
        <div id="btnouterlist" class="btnouterlist">
            <div class="am-u-lg-3 am-u-md-12 am-u-sm-12">
                <label style="margin-right:5px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>&nbsp;&nbsp;
                <input class="am-btn am-btn-danger am-radius am-btn-sm" type="button" value="<?php echo $ld['batch_delete']?>" onclick="batch_operations()" />
            </div>
            <div class="am-u-lg-9 am-u-md-12 am-u-sm-12">
            	<?php echo $this->element('pagers')?>
            </div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
</div>
<script type="text/javascript">
    //批量操作
    function batch_operations(){
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                checkboxes.push(bratch_operat_check[i].value);
            }
        }
        if( checkboxes=="" ){
            alert("<?php echo $ld['select_related_data']; ?>");
            return;
        }
        if(confirm("<?php echo $ld['confirm_delete'] ?>")){
            var sUrl = admin_webroot+"resumes/batch_operations/";//访问的URL地址
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'json',
                data: {checkboxes:checkboxes},
                success: function (result) {
                     
                        window.location.href = window.location.href;
                    
                }
            });
        }
    }
</script>