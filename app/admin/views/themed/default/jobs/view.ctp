<style>
	.am-radio, .am-checkbox{display:inline;vertical-align: initial;}
	.am-checkbox {margin-top:0px; margin-bottom:0px;}
 
	.btnouter{margin:50px;}
	.am-form-horizontal .am-radio{padding-top:0;position:relative;top:0px;}
	.am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<?php echo $form->create('jobs',array('action'=>'view/'.(isset($this->data['Job'])?$this->data['Job']['id']:''),'name'=>'JobForm','onsubmit'=>'return job_input_checks();'));?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic"><?php echo $ld['basic_information']?></a></li>
    </ul>
</div>
<div class="am-panel-group   am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="basic" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <input id="id" name="data[Job][id]" type="hidden" value="<?php echo isset($this->data['Job']['id'])?$this->data['Job']['id']:'';?>">
                <?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <input name="data[JobI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
                <?php }}?>
            <div class="am-form-group">
                        <label  class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['job_title'];?></label>
                        <div class="am-u-lg-10 am-u-md-10 am-u-sm-8">
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        	<div class="am-u-lg-8 am-u-md-8 am-u-sm-10">
                             <input id="job_name_<?php echo $v['Language']['locale'];?>" name="data[JobI18n][<?php echo $k;?>][name]" type="text" value="<?php echo isset($this->data['JobI18n'][$v['Language']['locale']])?$this->data['JobI18n'][$v['Language']['locale']]['name']:'';?>" >
                          </div> <?php if(sizeof($backend_locales)>1){?>
                            	<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-view-label"><?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
                            <?php }?>
                                <?php }}?>
                    	</div>
                    </div>
             <div class="am-form-group">
                        <label  class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['detail_description']?></label>
                       <div class="am-u-lg-10 am-u-md-10 am-u-sm-8">
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                              <div class="am-u-lg-10 am-u-md-10 am-u-sm-12">
                        <?php
                        if($configs["show_edit_type"]){?>
                              <div><span class="ckeditorlanguage  "><?php echo $v['Language']['name'];?></span></div>
                                    <textarea   id="elm<?php echo $v['Language']['locale'];?>" name="data[JobI18n][<?php echo $k;?>][detail]" rows="20" ><?php echo isset($this->data['JobI18n'][$v['Language']['locale']]['detail'])?$this->data['JobI18n'][$v['Language']['locale']]['detail']:"";?></textarea>
                                    <script>
                                        var editor;
                                        KindEditor.ready(function(K) {
                                            editor = K.create('#elm<?php echo $v['Language']['locale'];?>', {width:'100%',
                                                langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
                                        });
                                    </script>
                             
                        <?php }else{?>
                         
                                     <span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
                                    <textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[JobI18n][<?php echo $k;?>][detail]" rows="10"><?php echo isset($this->data['JobI18n'][$v['Language']['locale']]['detail'])?$this->data['JobI18n'][$v['Language']['locale']]['detail']:"";?></textarea>
                                    <?php echo $ckeditor->load("elm".$v['Language']['locale']); ?> 
                        
                        <?php }?>
                                        </div>
                    <?php }}?>
                    </div></div>

               <div class="am-form-group">
                        <label  class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['place_of_work'];?></label>
                        <div class="am-u-lg-10 am-u-md-10 am-u-sm-8">
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        	<div class="am-u-lg-8 am-u-md-8 am-u-sm-10">
                            <input id="job_address_<?php echo $v['Language']['locale'];?>" name="data[JobI18n][<?php echo $k;?>][address]" type="text" value="<?php echo isset($this->data['JobI18n'][$v['Language']['locale']])?$this->data['JobI18n'][$v['Language']['locale']]['address']:'';?>"  >
                          </div> <?php if(sizeof($backend_locales)>1){?>
                            	<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-view-label"><?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
                            <?php }?>
                                <?php }}?>
                    	</div>
                    </div>

                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['job_category'];?></label>
                        <div class="am-u-lg-10 am-u-md-10 am-u-sm-8">	<div class="am-u-lg-8 am-u-md-8 am-u-sm-10">
                            <select name="data[Job][type_id]" id="job_type" data-am-selected="{noSelectedText: '<?php echo $ld['please_select']?>'}">
                                <option value=""><?php echo $ld['please_select']?></option>
                                <?php if(isset($job_types) && sizeof($job_types)>0){foreach($job_types as $k => $m){?>
                                    <option value="<?php echo $k;?>" <?php if(isset($this->data['Job']['type_id'])&&$this->data['Job']['type_id']==$k){echo 'selected';}?> ><?php echo $m;?></option>
                                <?php }}?>
                            </select> 
                            <button style="margin-top:10px;" type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="searchInforationresources('job_type')" value="<?php echo $ld['region_view']?>" >
							<?php echo $ld['region_view'];?></button><em style="color:red;">*</em>
                        </div></div>
                    </div>


                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['year_of_work_experience']; ?></label>
                        <div class="am-u-lg-10 am-u-md-10 am-u-sm-8">
                                	<div class="am-u-lg-8 am-u-md-8 am-u-sm-10">
                            <select name="data[Job][experience_id]" id="experience_type" data-am-selected="{noSelectedText: '<?php echo $ld['please_select']?>'}">
                                <option value=""><?php echo $ld['please_select']?></option>
                                <?php if(isset($experience_types) && sizeof($experience_types)>0){foreach($experience_types as $k => $m){?>
                                    <option value="<?php echo $k;?>" <?php if(isset($this->data['Job']['experience_id'])&&$this->data['Job']['experience_id']==$k){echo 'selected';}?> ><?php echo $m;?></option>
                                <?php }}?>
                            </select> 
                            <button  style="margin-top:10px;"   type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="searchInforationresources('experience_type')" value="<?php echo $ld['region_view']?>" >
							<?php echo $ld['region_view'];?></button>
                        </div></div>
                    </div>

                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['education_requirements']; ?></label>
                        <div class="am-u-lg-10 am-u-md-10 am-u-sm-8">
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-10">
                            <select name="data[Job][education_id]" id="education_type" data-am-selected="{noSelectedText: '<?php echo $ld['please_select']?>'}">
                                <option value=""><?php echo $ld['please_select']?></option>
                                <?php if(isset($education_types) && sizeof($education_types)>0){foreach($education_types as $k => $m){?>
                                    <option value="<?php echo $k;?>" <?php if(isset($this->data['Job']['education_id'])&&$this->data['Job']['education_id']==$k){echo 'selected';}?> ><?php echo $m;?></option>
                                <?php }}?>
                            </select> 
                            <button type="button" style="margin-top:10px;"  class="am-btn am-btn-success am-radius am-btn-sm" onclick="searchInforationresources('education_type')" value="<?php echo $ld['region_view']?>" ><?php echo $ld['region_view'];?></button>
                        </div>
                       </div>
                    </div>


                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['department_belong']; ?></label>
                        <div   class="am-u-lg-10 am-u-md-10 am-u-sm-8">
                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-10">
                            <select name="data[Job][department_id]" id="department_type" data-am-selected="{noSelectedText: '<?php echo $ld['please_select']?>'}">
                                <option value=""><?php echo $ld['please_select']?></option>
                                <?php if(isset($department_types) && sizeof($department_types)>0){foreach($department_types as $k => $m){?>
                                    <option value="<?php echo $k;?>" <?php if(isset($this->data['Job']['department_id'])&&$this->data['Job']['department_id']==$k){echo 'selected';}?> ><?php echo $m;?></option>
                                <?php }}?>
                            </select> 
                            <button style="margin-top:10px;" type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="searchInforationresources('department_type')" value="<?php echo $ld['region_view']?>" ><?php echo $ld['region_view'];?></button><em style="color:red;">*</em>
                        </div>
                       </div>
                    </div>

			<div class="am-form-group" >
				<label  class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label "><?php echo $ld['recruitment_number']; ?></label>
				<div class="am-u-lg-10 am-u-md-10 am-u-sm-8">
					<div  class="am-u-lg-8 am-u-md-8 am-u-sm-10">
						<input type="text" name="data[Job][number]"id="job_number" value="<?php echo isset($this->data['Job']['number'])?$this->data['Job']['number']:$ld['number_of'];?>" />
					</div>
				</div>
			</div>


                    <div class="am-form-group" style="margin-top:10px;">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4"><?php echo $ld['status']?></label>
                              <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
						<label class="am-radio am-success">
					<input type="radio" name="data[Job][status]" value="1" data-am-ucheck <?php echo !isset($this->data['Job']['status'])||(isset($this->data['Job']['status'])&&$this->data['Job']['status']==1)?"checked":"";?> ><?php echo $ld['yes']?></label>
					<label style="margin-left:10px;" class="am-radio am-success">
						<input name="data[Job][status]" type="radio" value="0" data-am-ucheck <?php echo isset($this->data['Job']['status'])&&$this->data['Job']['status']==0?"checked":"";?> >
						<?php echo $ld['no']?>
					</label>
                        </div>
                    </div>

                    <div>
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4" style="margin-top:9px;"><?php echo $ld["sort"]?></label>
			        <div class="am-u-lg-10 am-u-md-10 am-u-sm-8">
                                   <div class="am-u-lg-8 am-u-md-8 am-u-sm-10">
                                  <input type="text" class="input_sort" id="orderby" name="data[Job][orderby]" value="<?php echo isset($this->data['Job']['orderby'])?$this->data['Job']['orderby']:'50';?>"  />
                                     </div>
				</div> 
                    </div>

                
                <div class="btnouter">
				<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
				<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="public_dialog">
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    function job_input_checks(){
        var job_name_obj = document.getElementById("job_name_"+backend_locale);
        if(job_name_obj.value==""){
            alert("<?php echo $ld['fill_in_job_title']; ?>");
            return false;
        }
        var job_address_obj = document.getElementById("job_address_"+backend_locale);
        if(job_address_obj.value==""){
            alert("<?php echo $ld['fill_in_place_of_work']; ?>");
            return false;
        }
        if(document.getElementById("job_type").value==""){
            alert("<?php echo $ld['select_job_category']; ?>");
            return false;
        }
        if(document.getElementById("department_type").value==""){
            alert("<?php echo $ld['select_department']; ?>");
            return false;
        }
        return true;
    }

    //查询资源表数据
    function searchInforationresources(code){
        if(code==""){
            return false;
        }
        $.ajax({
        	url:"/admin/information_resources/searchInforationresources/",
        	type:"POST",
        	data:{'code':code},
            datatype:'html',
        	success:function(data){
                $('#public_dialog').html('');
                $('#public_dialog').append(data);
                $('#public_dialog').modal('open');
        	}
        });
        
    }
    
    //删除资源表数据
    function removeInforationresources(id){
    	var postData={'id':id};
    	$.ajax({
    		url:"/admin/information_resources/removeInforationresources/",
    		type:"POST",
    		data:postData,
    		dataType:"json",
    		success:function(data){
    			if(data.flag==1){
                    var code = $('#code').val();
                    updateInformationresources(code);
                    searchInforationresources(code);
                }
    		}
    	});
    }
    //编辑新增资源表数据
    function editInforationresources(id){
	     if(id!=""){
	     	 var name = $("#informationresource_value_"+id).val();
	     	var postData ={'id':id,'name':name};
	     }else{
	     	var postData =$("#information_form").serialize();
	     }
    	$.ajax({
    		url:"/admin/information_resources/editInforationresources/",
    		type:"POST",
    		data:postData,
    		dataType:"json",
    		success:function(data){
    			var code = $('#code').val();
                    updateInformationresources(code);
                    searchInforationresources(code);
    		}
    	});
    	
    }
    //更新资源表数据
    function updateInformationresources(code){
        	var	postData="code="+code;
    		$.ajax({
        		url:"/admin/information_resources/updateInformationresources/",
        		type:"POST",
        		data:postData,
                dataType:"json",
        		success:function(data){
        			$('#'+code+" option").remove();
                    if(data.flag==1){
                        $.each(data.data, function (n, value) {
                           $('#'+code).append("<option value='"+n+"'>"+value+"</option>");
                        });
                    }
                    $('#'+code).trigger('changed.selected.amui');
        		}
        	});
    }
    function showInput(key){
    		$("#informationresource_span_"+key).addClass('status');
            $("#informationresource_value_"+key).removeClass('status');
    		$("#informationresource_value_"+key).parent().css("display","inline-block");
    }
</script>