<?php echo $form->create('ProductCategories',array('action'=>'/move_to/'.$category_id,'class'=>'am-form am-form-horizontal','name'=>"theForm","enctype"=>"multipart/form-data"));?>
<div class="am-panel-group" id="accordion">
    <div id="basic_information" class="am-panel am-panel-default">
        <div class="am-panel-hd">
		    <h4 class="am-panel-title"><?php echo $ld['basic_information'] ?></h4>
		</div>
        <div class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                <div class="am-form-group">
				    <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">
				    	<?php $tmpcategories = "";
								if(isset($categories_tree) && sizeof($categories_tree)>0){
									foreach($categories_tree as $first_k=>$first_v){
										if($category_id == $first_v['CategoryProduct']['id']){
											$tmpcategories .= $first_v['CategoryProductI18n']['name'];
										}
										if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){
											foreach($first_v['SubCategory'] as $second_k=>$second_v){
												if($category_id == $second_v['CategoryProduct']['id']){
													$tmpcategories .= $second_v['CategoryProductI18n']['name'];
												}
												if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){
													foreach($second_v['SubCategory'] as $third_k=>$third_v){
														if($category_id == $third_v['CategoryProduct']['id']){
															$tmpcategories .= $third_v['CategoryProductI18n']['name'];
								}	}	}	}	}	}	}
						?><input type="hidden" name="start_category_id" value="<?php echo $category_id;?>"><?php printf($ld['category_move_to'],$tmpcategories);?>
				    </label>
				    <div class="am-u-lg-4 am-u-md-6 am-u-sm-6">
					    <select class="all" name="end_category_id" id="category_id" data-am-selected>
							<option value="0"><?php echo $ld['select_categories']?></option>
							<?php if(isset($categories_tree) && sizeof($categories_tree)>0){?>
							<?php 	foreach($categories_tree as $first_k=>$first_v){?>
							<option value="<?php echo $first_v['CategoryProduct']['id'];?>" <?php if($category_id == $first_v['CategoryProduct']['id']){?>selected<?php }?>><?php echo $first_v['CategoryProductI18n']['name'];?></option>
							<?php 		if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
							<?php 			foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
							<option value="<?php echo $second_v['CategoryProduct']['id'];?>" <?php if($category_id == $second_v['CategoryProduct']['id']){?>selected<?php }?>>&nbsp;&nbsp;<?php echo $second_v['CategoryProductI18n']['name'];?></option>
							<?php 				if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
							<?php 					foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
							<option value="<?php echo $third_v['CategoryProduct']['id'];?>" <?php if($category_id == $third_v['CategoryProduct']['id']){?>selected<?php }?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryProductI18n']['name'];?></option>
							<?php }	}	}	}	}	}?>
						</select>
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
<?php echo $form->end();?> 