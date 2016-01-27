
	  <div class=" am-btn-group-xs am-text-right " style="margin-right:5px;">
	<?php echo $html->link($ld['picture_category_list'],"/image_spaces/category_list",array("class"=>"am-btn am-btn-sm am-btn-default"),false,false);?>
        </div>

<style type="text/css">
.am-view-label {
    font-weight: bold;
    margin-top: 20px;
}
    .size input[type='text'].c_size{width:50px;float:left;}
    .size .c_txt{float:left;margin:5px;}
    .am-radio, .am-checkbox{display:inline;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
.am-table > tbody > tr > td, .am-table > tbody > tr > th, .am-table > tfoot > tr > td, .am-table > tfoot > tr > th, .am-table > thead > tr > td, .am-table > thead > tr > th {
    border-top: 1px solid #ddd;
    line-height: 1.6;
    padding: 0.7rem;
    vertical-align:middle;
}
 .am-form-group > div > div {
    margin-top:10px;
}
</style>
<?php echo $form->create('image_spaces',array('action'=>'category_view/'.(isset($photo_categories_info['PhotoCategory'])?$photo_categories_info['PhotoCategory']['id']:''),'onsubmit'=>'return check()'));?>
<input name="data[PhotoCategory][id]" type="hidden" value="<?php echo isset($photo_categories_info['PhotoCategory']['id'])?$photo_categories_info['PhotoCategory']['id']:'';?>">
<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
    <input name="data[PhotoCategoryI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
<?php }}?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#tablemain"><?php echo $ld['basic_information']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="tablemain" class="tablemain am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['basic_information']?>
            </h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
             <div class="am-form-group">
			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['picture_category_name']?></label>
			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				<div class="am-u-lg-10 am-u-md-10 am-u-sm-10">	<input e name="data[PhotoCategoryI18n][<?php echo $k;?>][name]" type="text" value="<?php echo isset($photo_categories_info['PhotoCategoryI18n'][$v['Language']['locale']])?$photo_categories_info['PhotoCategoryI18n'][$v['Language']['locale']]['name']:'';?>"></div>
					<?php if(sizeof($backend_locales)>1){?>
						<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-view-label"><?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
					<?php }?>
				<?php }}?>
			</div>
		</div>
		<div class="am-form-group">
                    <label class=" am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-radio-label"><?php echo $ld['thumbnail_size']?></label>
                    <div class="am-u-lg-10 am-u-md-10 am-u-sm-9"><div>
                        <label class="am-radio am-success"><input data-am-ucheck name='data[PhotoCategory][custom]' type='radio' value='0' checked onclick='custom_size(this.value)'><?php echo $ld['system_default']?></label>
                        <label style="margin-left:10px;" class="am-radio am-success"><input data-am-ucheck name='data[PhotoCategory][custom]' type='radio' value='1' onclick='custom_size(this.value)' <?php if(isset($photo_categories_info['PhotoCategory']['custom'])&&$photo_categories_info['PhotoCategory']['custom']==1) echo 'checked'?>><?php echo $ld['custom']?></label></div>
                    </div>
             </div>
             <div class="am-form-group" name='size' style='display:none'>
                    <label class=" am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-radio-label" style="margin-top:17px;"><?php echo $ld['big_picture']?></label>
                	 <div class="am-u-lg-10 am-u-md-10 am-u-sm-9 size"><div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
                		<p class="c_txt" style="margin-top:8px;"><?php echo $ld['width']?></p><input class='c_size' type='text' name="data[PhotoCategory][cat_big_img_width]" value="<?php echo empty($photo_categories_info['PhotoCategory']['cat_big_img_width'])?'800':$photo_categories_info['PhotoCategory']['cat_big_img_width']?>"/><p class="c_txt" style="margin-top:8px;"> X <?php echo $ld['height']?></p><input class='c_size' type='text' name="data[PhotoCategory][cat_big_img_height]" value="<?php echo empty($photo_categories_info['PhotoCategory']['cat_big_img_height'])?'800':$photo_categories_info['PhotoCategory']['cat_big_img_height']?>"/><p class="c_txt" style="margin-top:8px;"><?php echo $ld['pixel']?></p></div>
                     </div>
             </div>
	       <div class="am-form-group" name='size' style='display:none'>
                    <label class=" am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-radio-label" style="margin-top:17px"><?php echo $ld['middle_picture']?></label>
                    <div class="am-u-lg-10 am-u-md-10 am-u-sm-9 size"><div class="am-u-lg-12 am-u-md-12 am-u-sm-12"><p class="c_txt" style="margin-top:8px;"><?php echo $ld['width']?></p><input class='c_size' type='text' name="data[PhotoCategory][cat_mid_img_width]" value="<?php echo empty($photo_categories_info['PhotoCategory']['cat_mid_img_width'])?'400':$photo_categories_info['PhotoCategory']['cat_mid_img_width']?>"/><p class="c_txt" style="margin-top:8px;"> X <?php echo $ld['height']?></p><input class='c_size' type='text' name="data[PhotoCategory][cat_mid_img_height]" value="<?php echo empty($photo_categories_info['PhotoCategory']['cat_mid_img_height'])?'400':$photo_categories_info['PhotoCategory']['cat_mid_img_height']?>"/><p class="c_txt" style="margin-top:8px;"><?php echo $ld['pixel']?></p></div></div>
	        </div>
	        <div class="am-form-group" name='size' style='display:none'>
                    <label class=" am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-radio-label" style="margin-top:17px"><?php echo $ld['small_picture']?></label>
	              <div class="am-u-lg-10 am-u-md-10 am-u-sm-9 size"><div class="am-u-lg-12 am-u-md-12 am-u-sm-12"><p class="c_txt" style="margin-top:8px;"><?php echo $ld['width']?></p><input class='c_size' type='text' name="data[PhotoCategory][cat_small_img_width]" value="<?php echo empty($photo_categories_info['PhotoCategory']['cat_small_img_width'])?'140':$photo_categories_info['PhotoCategory']['cat_small_img_width']?>"/><p class="c_txt" style="margin-top:8px;"> X <?php echo $ld['height']?></p><input class='c_size' type='text' name="data[PhotoCategory][cat_small_img_height]" value="<?php echo empty($photo_categories_info['PhotoCategory']['cat_small_img_height'])?'140':$photo_categories_info['PhotoCategory']['cat_small_img_height']?>"/><p class="c_txt" style="margin-top:8px;"><?php echo $ld['pixel']?></p></div></div>
	        </div>
              <div class="am-form-group">
			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label" style="margin-top:17px;"><?php echo $ld['sort']?></label>
			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9"><div class="am-u-lg-12 am-u-md-12 am-u-sm-12"><input style="width:200px;" type="text" name="data[PhotoCategory][orderby]" value="<?php echo isset($photo_categories_info['PhotoCategory'])?$photo_categories_info['PhotoCategory']['orderby']:'';?>" /></div>
			</div>
		  </div>
            <div class="btnouter">
                <input type="submit" class="am-btn am-btn-success am-btn-sm" value="<?php echo $ld['d_submit']?>" />  <input type="reset" class="am-btn am-btn-success am-btn-sm" value="<?php echo $ld['d_reset']?>" onclick="resetInfo()" />
            </div>
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<script>
    <?php if(isset($alert)){?>
    	alert("<?php echo $alert;?>");
    <?php	}?>
    function resetInfo(){
        var trs = document.getElementsByName('size');
        for (var i=0; i < trs.length; i++) {
            trs[i].style.display='none';
        };
    }

    function custom_size(check){
        var trs = document.getElementsByName('size');
        if(check==1){
            for (var i=0; i < trs.length; i++) {
                trs[i].style.display='';
            };
        }else{
            for (var i=0; i < trs.length; i++) {
                trs[i].style.display='none';
            };
        }
    }

    window.onload = function(){
        custom_size(<?php echo @$photo_categories_info['PhotoCategory']['custom']?>)
    }
</script>