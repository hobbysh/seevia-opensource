<?php echo $javascript->link('/skins/default/js/product');?>
<style type="text/css">
.am-radio, .am-checkbox{display: inline-block;margin-top:0px;}
.am-checkbox input[type="checkbox"]{margin-left:0px;}
.am-yes{color:#5eb95e;}
.am-no{color:#dd514c;}
 
.am-panel-title div{font-weight:bold;}
#SearchForm .am-form-label{padding-top:8px;}
#SearchForm li{margin-bottom:10px;}
 .am-form-horizontal .am-checkbox{padding-top:0px;}
#changeAttr div{float:left;width:150px;}
#changeAttr div .am-checkbox{margin-left:5px;margin-top:-5px;}
#check_box{width:100%;}
#product_type_id{font-size: 1.4rem;}
#attr_cate_id{font-size: 1.4rem;}
#product_attr_id{font-size: 1.4rem;}

</style>
<div>
    <?php echo $form->create('Product',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
    <div>
        <input type="hidden" name="export_act_flag" id="export_act_flag" value=""/>
        <input type="hidden" name="attr_value" id="attr_value" value="<?php if(isset($attr_value)){echo $attr_value;}?>"/>
    </div>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li> 
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['product_categories']; ?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <div class="checkbox" id = 'y1' >
                    <div class="am-dropdown" data-am-dropdown id="check_box">
                        <button  style="width:100%;" class="am-selected-btn am-btn am-dropdown-toggle am-btn-default   am-btn-sm" data-am-dropdown-toggle><span class="am-selected-status am-fl"><?php echo $ld['all_data']?></span><i class="am-selected-icon am-icon-caret-down"></i></button>
                        <ul class="am-dropdown-content am-avg-lg-1 am-avg-md-1 am-avg-sm-1 b1" style="height:300px;overflow-x:hidden;overflow-y:scroll; width:100%;">
                            <li class="bb0" style="padding-left:10px;">
                                <label class="am-checkbox am-success">
                                    <input type="checkbox" name="box" value="-1"  data-am-ucheck <?php if(in_array("-1",$category_arr)) echo 'checked';?>/> <?php echo $ld['unknown_classification']?>
                                </label>
                            </li>
                            <?php foreach($category_name_list as $cak=>$cav){ ?>
                                <li class="bb0" style="margin-left:10px;">
                                    <label class="am-checkbox am-success">
                                        <input type="checkbox" class="checkbox" name="box" value="<?php echo $cak;?>"  data-am-ucheck <?php if(in_array($cak,$category_arr)) echo 'checked';?>/>
                                        <?php echo $cav;?>
                                    </label>
                                </li>
                            <?php }?>
                            <li class="bb1">
                                <div class="am-form-group">
                                    <div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
                                        <label class="am-checkbox am-success">
                                            <input type="checkbox" id="select" class="bb2" data-am-ucheck />
                                            <?php echo $ld['select_all']?>
                                        </label>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </li><!----1--->
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php  echo $ld['product_brand']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <?php echo $this->element('brand_tree');?>
            </div>
        </li><!----2--->
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['for_sale'].$ld['status']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <select name="forsale" id='forsale' data-am-selected="">
                    <option value="-1" selected ><?php echo $ld['all_data']?></option>
                    <option value="1" <?php if($forsale==1){?>selected<?php }?> ><?php echo $ld['for_sale']?></option>
                    <option value="0" <?php if($forsale==0){?>selected<?php }?> ><?php echo $ld['out_of_stock']?></option>
                </select>
            </div>
        </li><!----3--->
        <li>
        	<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['recommend']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="is_recommond" id='is_recommond' data-am-selected>
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <option value="0" <?php if($is_recommond == 0){?>selected<?php }?>><?php echo $ld['no']?></option>
                    <option value="1" <?php if($is_recommond == 1){?>selected<?php }?>><?php echo $ld['yes']?></option>
                </select>
            </div>
        </li><!----4--->
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label">
                <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['product'].' '.$ld['type']:$ld['product'].$ld['type'];?>
            </label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <select name="option_type_id" id='option_type_id' data-am-selected >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <?php foreach($pro_option_type_name as $kk=>$vv){ ?>
                        <option value="<?php echo $kk ?>" <?php if($option_type_id ==$kk){?>selected<?php }?>><?php echo $vv ?></option>
                    <?php } ?>
                </select>
            </div>
        </li><!----5--->
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['operator']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <select name="operator_id" id='operator_id' data-am-selected>
                    <option value="-1" selected ><?php echo $ld['all_data']?></option>
                    <?php foreach($Operator_list as $Opk=>$Opv){?>
                        <option value="<?php echo $Opv['Operator']['id'];?>" <?php if(isset($operator_id)&& $operator_id==$Opv['Operator']['id']){?>selected<?php }?> ><?php echo $Opv['Operator']['name'];?></option>
                    <?php }?>
                </select>
            </div>
        </li><!----6--->      			
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"  ><?php echo $ld['operation_time']?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date_time" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
            </div>
            <div class=" am-text-center am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="end_date_time" value="<?php echo isset($end_date_time)?$end_date_time:"";?>" />
            </div>
        </li>
        	<!----7--->
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['added_time'];?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  name="start_date"  class="am-form-field " readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  value="<?php echo $start_date;?>" />
            </div>
            <div class="  am-text-center  am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="end_date" value="<?php echo $end_date;?>" />
            </div>
        </li><!----8--->
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['price_range']?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  name="min_price" id="min_price" value="<?php echo @$min_price?>"/>
            </div>
            <div class="  am-fl am-text-center" style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  name="max_price" id="max_price" value="<?php echo $max_price?>"/>
            </div>
        </li><!----9--->
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['keyword'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="product_keywords" id="product_keywords" value="<?php echo $product_keywords?>" onkeypress="sv_search_action_onkeypress(this,event)" />
            </div>
           </li>
        <li >
            <label class="am-u-sm-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
            <div class="am-u-sm-8 am-u-md-8 am-u-sm-7 " style="float:left;">
                <input type="button"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
            </div>
        </li>
    </ul>
    <div id="mm"><?php if(isset($product_type_tree)&&!empty($product_type_tree)){echo $this->element('product_type_tree');} ?></div>
    <div class="am-g">
    	     <label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label">&nbsp;</label>
           <div id="changeAttr" class="am-u-lg-11 am-u-md-11 am-u-sm-11"></div>
           <div style="clear:both;"></div>
    </div>
    <?php echo $form->end()?>
</div>
<div>
<div class="am-text-right am-btn-group-xs" style="clear:both;margin:10px auto;">
    <?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['product'].$ld['set_up'],"/products/config",array('target'=>'_blank',"class"=>" mt am-btn am-btn-default am-seevia-btn-view"));} ?>
    <?php if($svshow->operator_privilege("products_trash")){echo $html->link($ld['recycle_bin']."(".$trash_count.")","/trash/",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));}?>
    <?php if($svshow->operator_privilege("category_types_view")){echo $html->link($ld['category_management'],"/category_types/",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));} ?>
    <?php if($svshow->operator_privilege("category_types_view")){echo $html->link($ld['style_manager'],"/product_styles/",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));} ?>
    <?php if($svshow->operator_privilege("products_upload")){echo $html->link($ld['bulk_upload_products'],"/products/uploadproduct",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));}?>
    <?php if($svshow->operator_privilege("products_add")){ ?>
    	<a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/products/add'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
        </a>
        	
    <?php }?>
</div>
           <div class="listtable_div_btm">
		<div class="am-g">
		<div class="am-u-lg-1 am-u-md-1 am-hide-sm-down" ><label class="am-checkbox am-success" ><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" />&nbsp; <?php echo $ld['thumbnail'];?> </label></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['sku'];?>/<?php echo $ld['name'] ?></div>
		<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['brand'];?>/<?php echo $ld['classification'] ?></div>
		<div class="am-u-lg-1   am-hide-md-down"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['product'].' '.$ld['type']:$ld['product'].$ld['type'];?></div>
		<?php if(isset($configs['show_public_attr'])&&$configs['show_public_attr']==1&&isset($public_attr_info)&&sizeof($public_attr_info)>0){ foreach($public_attr_info as $pa){?>
		<div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo $pa['AttributeI18n']['name']?></div> 
		<?php }}?>
		<div  class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo $ld['quantity']; ?></div>
		<?php if(isset($apps['Applications']['APP-API-WEBSERVICE'])&&!empty($ec_product_sku)){ ?>
		<div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo  $ld['virtual_inventory'];?></div>
		<?php }?>
		<?php if (isset($configs["show_purchase_price"]) && $configs["show_purchase_price"]==1){ ?>
		<div class="am-u-lg-1 am-u-md-1   am-hide-sm-only"><?php echo $ld['purchase_price']?></div>
		<?php }?>
		<div class="am-u-lg-1 am-u-md-1  am-hide-sm-only"> <?php echo $ld['price']?></div>
		<div class="am-u-lg-1 am-u-md-1   am-hide-sm-only"> <?php echo $ld['for_sale']?></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-5"> <?php echo $ld['operate']?></div>
		</div>
</div>

    <?php if(isset($product_list) && sizeof($product_list)>0){foreach($product_list as $k=>$v){?>
        <div class="am-g">
           	<div class="listtable_div_top" >
                <div style="margin-top:10px;margin-left:0px;margin-bottom:10px;" class="am-g">
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-hide-sm-only" >
           <label class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['Product']['id']?>"  data-am-ucheck />&nbsp;<img src="<?php echo empty($v['Product']['img_thumb'])?$configs['shop_default_img']:$v['Product']['img_thumb']; ?>" width="50px" height="50px"></label></div>  
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php if($svshow->operator_privilege('products_edit')){ ?><span onclick="javascript:listTable.edit(this, 'products/update_product_code/', <?php echo $v['Product']['id']?>)"><?php }else{echo "<span>";} echo $v['Product']['code']; ?>&nbsp;</span><br >
                <?php if($svshow->operator_privilege('products_edit')){ ?><span onclick="javascript:listTable.edit(this, 'products/update_product_name/', <?php echo $v['Product']['id']?>)"><?php }else{echo "<span>";} echo $v['ProductI18n']['name'];?></span>&nbsp;</div>
           <div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo isset($brand_names[$v['Product']['brand_id']])?$brand_names[$v['Product']['brand_id']]:"-";?><br ><?php echo isset($product_category_tree[$v['Product']['category_id']])?$product_category_tree[$v['Product']['category_id']]:"&nbsp;";?></div>
           <div class="am-u-lg-1  am-hide-md-down" ><?php echo $pro_option_type_name[$v['Product']['option_type_id']]; ?>&nbsp;</div>
            <?php if(isset($configs['show_public_attr'])&&$configs['show_public_attr']==1&&isset($public_attr_info)&&sizeof($public_attr_info)>0){ foreach($public_attr_info as $pa){?>
           <div class="am-u-lg-1 am-u-md-1 am-u-sm-2" >
                    <?php if($v['Product']['option_type_id']==0){								$_attr_info_name=isset($attr_info)&&(isset($attr_info[$v['Product']['id']][$pa['Attribute']['id']]))&&trim($attr_info[$v['Product']['id']][$pa['Attribute']['id']]!="")?$attr_info[$v['Product']['id']][$pa['Attribute']['id']]:'-';
                        if(strlen($_attr_info_name)>8){
                            if($svshow->operator_privilege('products_edit')){
                                $ddn_id="DDN_".$v['Product']['id'].'_'.$pa['Attribute']['id'];
                                $_ID_STR="document.getElementById('".$ddn_id."')";
                                echo $html->image('/admin/skins/default/img/note.png',array('style'=>'cursor:pointer;','title'=>$_attr_info_name,"onclick"=>"setInput('".$ddn_id."')"));
                                ?>
                                <span id="<?php echo $ddn_id; ?>" onclick="javascript:listTable.editattr(<?php echo $_ID_STR ?>, 'products/update_product_attr/', '<?php echo $v['Product']['id'].';';?><?php echo $pa['Attribute']['id']?>')" style="display:none;"><?php echo $_attr_info_name; ?></span>
                            <?php }else{echo $html->image('/admin/skins/default/img/note.png',array('style'=>'cursor:pointer;','title'=>$_attr_info_name));}			}else{if($svshow->operator_privilege('products_edit')){?>
                            <span onclick="javascript:listTable.editattr(this, 'products/update_product_attr/', '<?php echo $v['Product']['id'].';';?><?php echo $pa['Attribute']['id']?>')"><?php echo $_attr_info_name; ?></span>
                        <?php }else{echo $_attr_info_name;
                        }
                        }
                    }
                ?>&nbsp;
            </div>
            <?php }}?>
           <div class="am-u-lg-1 am-u-md-1 am-u-sm-2"  ><?php if($svshow->operator_privilege('products_edit')){?><span onclick="javascript:listTable.edit(this, 'products/update_product_quantity/', <?php echo $v['Product']['id']?>)"><?php } echo $v['Product']['quantity']?></span>&nbsp;</div>
            <?php if(isset($apps['Applications']['APP-API-WEBSERVICE'])&&!empty($ec_product_sku)){ ?>
		<div class="am-u-lg-1 am-u-md-1 am-u-sm-2" >
			<?php if(isset($ec_product_sku[$v['Product']['code']])){
				$ec_quantity=0;
					foreach($ec_product_sku[$v['Product']['code']] as $eck=>$ecv){
					if($ecv['warhouse_name']=='虚拟仓'){
					$ec_quantity= $ec_quantity+$ecv['product_quantity'];
					}
				}
			echo $ec_quantity;
		}?>&nbsp;
		</div>
            <?php } ?>
            <?php if (isset($configs["show_purchase_price"]) && $configs["show_purchase_price"]==1){ ?>
           <div class="am-u-lg-1 am-u-md-1 am-u-sm-2 am-hide-sm-only" >
                <?php if($svshow->operator_privilege('products_edit')){?><span onclick="javascript:listTable.edit(this, 'products/update_product_purchase_price/', <?php echo $v['Product']['id']?>)"><?php } echo $v['Product']['purchase_price']?></span>&nbsp;
            </div>
            <?php }?>
           <div class="am-u-lg-1 am-u-md-1 am-u-sm-2 am-hide-sm-only" ><?php if($svshow->operator_privilege('products_edit')){?><span onclick="javascript:listTable.edit(this, 'products/update_product_price/', <?php echo $v['Product']['id']?>)"><?php } echo $v['Product']['shop_price']?></span>&nbsp;</div>
           <div class="am-u-lg-1 am-u-md-1 am-u-sm-2 am-hide-sm-only" ><?php if ($v['Product']['forsale'] == 1){?>
	                        <?php if($svshow->operator_privilege('products_edit')){?>
	                            <span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'products/toggle_on_forsale',<?php echo $v['Product']['id'];?>)"></span>
	                        <?php }elseif($opertor_type=="D"){?>
	                            <span class="am-icon-check am-yes"></span>
	                        <?php }else{?>
	                            <span class="am-icon-check am-yes"></span>
	                        <?php }?>
	                    <?php }elseif($v['Product']['forsale'] == 0){?>
	                        <?php if($svshow->operator_privilege('products_edit')){?>
	                            <span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'products/toggle_on_forsale',<?php echo $v['Product']['id'];?>)"></span>
	                        <?php }elseif($opertor_type=="D"){?>
	                            <span class="am-icon-close am-no"></span>
	                        <?php }else{?>
	                            <span class="am-icon-close am-no"></span>
	                        <?php }?>
	                    <?php }?> 
			</div>

           <div class="am-u-lg-3 am-u-md-3 am-u-sm-5"  >

 <?php      $preview_url=$svshow->seo_link_path(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>$ld['preview']));?>
                    <a class="mt am-btn am-btn-success am-btn-xs  am-seevia-btn-view" target='_blank' href="<?php echo $preview_url; ?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                    </a> 
                <?php if($svshow->operator_privilege("products_edit")){ ?>
                    <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/products/view/'.$v['Product']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                    <?php } ?> 
                    <?php if($svshow->operator_privilege("products_copy")){ ?>
                    <a class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-edit" href="<?php echo $html->url('/products/copy_product/'.$v['Product']['id']); ?>"><span class="am-icon-copy"></span> <?php echo $ld['copy'];?>
                     </a>
                    <?php } ?>
                      <?php if($svshow->operator_privilege("products_recycle_bin")){ ?>
                      <a class="mt am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'products/recycle_bin/<?php echo $v['Product']['id'] ?>','<?php echo $ld['confirm_products_to_recycle_bin'] ?>');"><span class="am-icon-trash-o"></span> <?php echo $ld['move_to_recycle_bin']; ?></a>
                     <?php } ?>
		  </div>
		</div></div></div>
        <?php }}else{?>
			<div>
				<div class="no_data_found" ><?php echo $ld['no_data_found']?></div>
			</div>
		<?php }?>
               
 
<?php if(isset($product_list) && sizeof($product_list)){?>
    <div id="btnouterlist" class="btnouterlist am-form-group am-hide-sm-only">
        <?php if($svshow->operator_privilege("products_batch")){ ?>
            <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
                <div class="am-fl">
                    <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;"><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label>&nbsp;
                </div>
                <div class="am-fl">
                    <select id="barch_opration_select" data-am-selected onchange="barch_opration_select_onchange(this)">
                        <option value="0"><?php echo $ld['batch_operate']?></option>
                        <?php if($svshow->operator_privilege("products_recycle_bin")){?>
                            <option value="recycle_bin"><?php echo $ld['batch_move_to_recycle_bin']?></option>
                        <?php }?>
                        <?php if($svshow->operator_privilege("product_categories_move")){?>
                            <option value="transfer_category"><?php echo $ld['transferred_to_classification']?></option>
                        <?php }?>
                        <option value="batch_onsale"><?php echo $ld['batch_onsale']?></option>
                        <option value="batch_notsale"><?php echo $ld['batch_notsale']?></option>
                        <option value="export_csv"><?php echo $ld['batch_export']?></option>
                        <?php if(constant("Product")=="AllInOne"){ ?>
                            <option value="product_cat"><?php echo $ld['batch_add_product_line']?></option>
                        <?php } ?>
                    </select>&nbsp;
                </div>
                <!--选 -->
                <div class="am-fl" style="display:none;">
                    <select id="export_csv" data-am-selected name="barch_opration_select_onchange" onchange="order_opration_select_onchange(this)">
                        <option value=""><?php echo $ld['all']?></option>
                        <option value="all_export_csv"><?php echo  $ld['all_export']?></option>
                        <option value="choice_export"><?php echo $ld['choice_export']?></option>
                        <option value="category_export"><?php echo $ld['category_export']?></option>
                        <option value="search_result"><?php echo $ld['search_export']?></option>
                    </select>&nbsp;
                </div>
                <!-- 全选 -->
                <div class="am-fl" style="display:none;">
                    <select id="export_type" data-am-selected name="all_order_opration_select_onchange">
                        <option value="all_product"><?php echo $ld['all']?></option>
                        <option value="for_sale"><?php echo $ld['for_sale_export']; ?></option>
                        <option value="out_of_stock"><?php echo $ld['out_of_stock_export']; ?></option>
                    </select>&nbsp;
                </div>
                <div class="am-fl" style="display:none;">
                    <select id="export_type_re" data-am-selected name="all_order_opration_select_onchange">
                        <option value="all_product"><?php echo $ld['all']?></option>
                        <option value="recommend"><?php echo $ld['recommend']; ?></option>
                        <option value="not_recommended"><?php echo 'Not recommended'; ?></option>
                    </select>&nbsp;
                </div>
                <div class="am-fl" style="display:none;">
                    <select id="transfer_category" data-am-selected name="barch_opration_select_onchange[]">
                        <option value="0"><?php echo $ld['select_categories']?></option>
                        <?php if(isset($category_tree) && sizeof($category_tree)>0){
                            foreach($category_tree as $first_k=>$first_v){?>
                                <option value="<?php echo $first_v['CategoryProduct']['id'];?>"><?php echo $first_v['CategoryProductI18n']['name'];?></option>
                                <?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){
                                    foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
                                        <option value="<?php echo $second_v['CategoryProduct']['id'];?>">&nbsp;&nbsp;<?php echo $second_v['CategoryProductI18n']['name'];?></option>
                                        <?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){
                                            foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
                                                <option value="<?php echo $third_v['CategoryProduct']['id'];?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryProductI18n']['name'];?></option>
                                            <?php }	}	}	}	}	}?>
                    </select>&nbsp;
                </div>
                <?php if(constant("Product")=="AllInOne"){ ?>
                <div class="am-fl" style="display:none">
                    <select id="product_cat" data-am-selected name="product_cat">
                        <option value="0"><?php echo $ld['all']; ?></option>
                        <?php if(isset($fenxiao_productcat_list) && !empty($fenxiao_productcat_list)){ foreach ($fenxiao_productcat_list as $key=>$v){?>
                            <option value="<?php echo $key?>"><?php echo $v;?></option>
                        <?php }}?>
                    </select>&nbsp;
                </div>
                <?php } ?>
                <div class="am-fl">
                    <input type="button" id="btn" value="<?php echo $ld['submit']?>" class="am-btn am-btn-sm am-btn-danger am-btn-radius" onclick="batch_operations()" />&nbsp;
                </div>
            </div>
        <?php }?>
        <div class="am-u-lg-6 am-u-md-12 am-u-sm-12"><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
    </div>
<?php }?>
</div>

 

<div class="am-modal am-modal-no-btn pop tablemain" tabindex="-1" id="placement" >
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['file_allocation'].' '.$ld['templates']:$ld['file_allocation'].$ld['templates'];?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <form id='placementform3' method="POST" class="am-form am-form-horizontal">
                <div class="am-form-group">
                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                        <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['choice_export'].' '.$ld['templates']:$ld['choice_export'].$ld['templates'];?>:
                    </label>
                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                        <select name="profilegroup" id="profilegroup">
                            <option value="0"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['please_select'].' '.$ld['templates']:$ld['please_select'].$ld['templates'];?></option>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;<em style="color:red;">*</em>
                    </div>
                </div>
                <div><input type="button" id="mod" class="am-btn am-btn-success am-btn-sm am-radius"  name="changeprofileButton" value="<?php echo $ld['confirm']?>" onclick="javascript:changeprofile();">
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
function ck_checkbox(){
    var dropdown = $('#check_box'),
        data = dropdown.data('amui.dropdown');
    if(data.active){
        dropdown.dropdown('close');
    }
    var str=document.getElementsByName("box");
    var leng=str.length;
    var chestr="";
    for(var i=0;i<leng;i++){
        if(str[i].checked == true)
        {
            chestr+=str[i].value+",";
        };
    };
    return chestr;
}

function formsubmit(){
    var export_act_flag=document.getElementById('export_act_flag').value;
    if(document.getElementById('brand_id')!=null){
        var brand_id=document.getElementById('brand_id').value;
    }else{
        var brand_id=0;
    }
    if(document.getElementById('product_type_id')!=null){
        var product_type_id=document.getElementById('product_type_id').value;
    }else{
        var product_type_id=0;
    }
    var operator_id=document.getElementById('operator_id').value;
    var forsale=document.getElementById('forsale').value;
    var start_date_time = document.getElementsByName('start_date_time')[0].value;
    var end_date_time = document.getElementsByName('end_date_time')[0].value;
    var product_keywords=document.getElementById('product_keywords').value;
    var is_recommond=document.getElementById('is_recommond').value;
    var start_date = document.getElementsByName('start_date')[0].value;
    var end_date = document.getElementsByName('end_date')[0].value;
    var min_price=document.getElementById('min_price').value;
    var max_price=document.getElementById('max_price').value;
    var option_type_id=document.getElementById('option_type_id').value;
    if(document.getElementById('attr_cate_id')){
        var attr_cate_id=document.getElementById('attr_cate_id').value;
    }else{
        var attr_cate_id=0;
    }
    var ta = ck_checkbox();
    var str = '';
    str +="&"+"category_id=" +ta.substring(ta,ta.length-1);
    var attr=attr_checkbox();
    str +="&"+"attr_value=" +attr.substring(attr,attr.length-1);
    var url = "operator_id="+operator_id+"&export_act_flag="+export_act_flag+"&brand_id="+brand_id+"&product_type_id="+product_type_id+"&forsale="+forsale+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time+"&product_keywords="+product_keywords+"&is_recommond="+is_recommond+"&start_date="+start_date+"&end_date="+end_date+"&min_price="+min_price+"&max_price="+max_price+str+"&option_type_id="+option_type_id+"&attr_cate_id="+attr_cate_id;
    window.location.href = encodeURI(admin_webroot+"products?"+url);
}

function attr_checkbox(){
    var str=document.getElementsByName("attr_box");
    var chestr="";
    if(str.length>0){
        var leng=str.length;
        for(i=0;i<leng;i++){
            if(str[i].checked == true){
                chestr+=str[i].value+",";
            };
        };
    }else{
        chestr=document.getElementById('product_dropdown_id').value+";";
    }
    return chestr;
}
var all=$('#y1 .a1');
bll=$('#y1 .b1'),
    cll=$('#y1 .btn'),
    allclick = function(){
        if(bll.prop("class")!="b1"){bll.removeClass('c1');all.removeClass('up');
        }
        else{bll.addClass('c1');all.addClass('up');}
    },
    removeclick = function(){
        all.removeClass('up');
        bll.removeClass('c1');
    };
var checkbox =$('#y1 .b1 .checkbox');
select = $('#y1 .b1 #select');
$("#select").click(function(){
    $(".bb0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
});



checkboxControl = function(){
    //	$.Array.indexOf(checkbox.get('checked'), false) < 0 ? select.attr('checked', true) : select.attr('checked', false);
},
    selectControl = function(){
        //select.attr('checked') ? checkbox.attr('checked', true) : checkbox.attr('checked', false);
    };

checkbox.on('click', checkboxControl);
select.on('click', selectControl);
cll.on('click', removeclick);
all.on('click', allclick);

/*
 属性搜索
 */
getAttronlond();

function getAttronlond(){
    if(document.getElementById('product_type_id')){
        var attr_val=document.getElementById('product_type_id').value;
    }else{
        var attr_val=0;
    }
    if(document.getElementById('attr_cate_id')){
        var attr_cate_id=document.getElementById('attr_cate_id').value;
    }else{
        var attr_cate_id=0;
    }
    var attr_Data="attrval="+attr_val+"&attr_cate_id="+attr_cate_id+"&<?php echo time(); ?>";
    var attr_value=document.getElementById('attr_value').value;

    var attr_html="";
    $.ajax({
        url:admin_webroot+"productstypes/getAttrInfo/?"+attr_Data,
        type:"POST",
        dataType:"json",
        success:function(data){
            if(attr_value==""){
                var attr_html="";
                document.all('product_attr_id').options.length = 0;
                document.all('product_dropdown_id').options.length = 0;
                document.getElementById("product_attr_id").options.add(new Option("<?php echo $ld['select_attribute']?>", 0));
                if(data.msg.length>0){
                    alert(data.msg.length);

                    for(var i=0;i<data.msg.length;i++){
                        var attr_value=data.msg[i].value.split("\r\n");
                        document.getElementById("product_attr_id").add(new Option(data.msg[i].name,data.msg[i].id));
                        document.getElementById('product_attr_id').style.display="inline-block";
                    }
                }else{
                    document.getElementById("product_attr_id").style.display='none';
                }
            }else{
                document.getElementById('changeAttr').innerHTML="";
                var attr_html="";
                document.all('product_attr_id').options.length = 0;
                document.all('product_dropdown_id').options.length = 0;
                document.getElementById("product_attr_id").options.add(new Option("<?php echo $ld['select_attribute']?>", 0));
                if(data.msg.length>0){
                    for(var i=0;i<data.msg.length;i++){
                        if(data.msg[i].value!=null){
                            var attr_value=data.msg[i].value.split("\r\n");
                        }else{
                            var attr_value=new Array();
                        }
                        document.getElementById("product_attr_id").add(new Option(data.msg[i].name,data.msg[i].id));
                        document.getElementById('product_attr_id').style.display="inline-block";
                        attr_html+="<div>";
                        attr_html+=data.msg[i].name+"<br />";
                        for(var j=0;j<attr_value.length;j++){
                            attr_html+="<label class='am-checkbox am-success' style='margin-top:0px'><input class='checkbox' onclick='formsubmit()' type='checkbox' name='attr_box' value='"+data.msg[i].id+";"+escape(attr_value[j])+"'/>"+attr_value[j]+"</label><br/>";
                        }
                        attr_html+="</div>";
                    }
                }else{
                    document.getElementById("product_attr_id").style.display='none';
                }
                document.getElementById('changeAttr').innerHTML=attr_html+"<div style='clear:both;'></div>";
                $("#changeAttr input[type='checkbox']").uCheck();
                select_attr();
            }
        }
    });
}
function getAttr(){
    getAttronlond();
    if(document.getElementById('product_type_id')){
        var attr_val=document.getElementById('product_type_id').value;
    }else{
        var attr_val=0;
    }
    $.ajax({
        url:admin_webroot+"products/getCate/",
        type:"POST",
        data:{attrval:attr_val},
        dataType:"json",
        success:function(data){
            if(data.length>0){
                var attr_html="";
                document.all('attr_cate_id').options.length = 0;
                document.getElementById("attr_cate_id").options.add(new Option("关联属性分类", 0));
                for(var i=0;i<data.length;i++){
                    var attr_value=data[i].name.split("\r\n");
                    document.getElementById('attr_cate_id').style.display="inline-block";
                    document.getElementById("attr_cate_id").add(new Option(data[i].name,data[i].id));
                }
            }else{
                document.getElementById("attr_cate_id").style.display='none';
                document.getElementById('product_dropdown_id').style.display="none";
            }
        }
    });
}

//2级属性联动	
function getDropdownInfo(){
    var optionlist=document.getElementById('product_attr_id').value;
    var attr_val=document.getElementById('product_type_id').value;
    var attr_cate_id=document.getElementById('attr_cate_id').value;
    if(optionlist!='0'){
        //	var list_Data="optionlist="+optionlist+"&attr_cate_id="+attr_cate_id+"&attrval="+attr_val;
        var attr_html="";
        $.ajax({
            url:admin_webroot+"productstypes/getdropdownInfo/",
            type:"POST",
            data:{optionlist:optionlist,attr_cate_id:attr_cate_id,attrval:attr_val},
            dataType:"json",
            success:function(data){
                document.getElementById('product_dropdown_id').style.display="none";
                document.all('product_dropdown_id').options.length = 0;
                document.getElementById("product_dropdown_id").options.add(new Option("<?php echo $ld['select_attribute_value']?>", 0));
                for(var i=0;i<data.msg.length;i++){
                    var attr_value=data.msg[i].value.split("\r\n");

                    document.getElementById('product_dropdown_id').style.display="inline-block";
                    for(var j=0;j<attr_value.length;j++){
                        document.getElementById("product_dropdown_id").add(new Option(attr_value[j],data.msg[i].id+";"+escape(attr_value[j])));
                    }
                }

                select_attr();
            }
        });
    }else{
        document.getElementById('product_dropdown_id').style.display="none";
    }
}
function select_attr(){
    var str=document.getElementsByName("attr_box");
    var attr_id=document.getElementsByName("product_attr_id");
    var sel_attr=document.getElementById("attr_value").value;
    sel_atrr=sel_attr.split(",");
    if(str){
        for(i=0;i<str.length;i++){
            for(j=0;j<sel_atrr.length;j++){
                if(str[i].value==sel_atrr[j]){
                    str[i].checked = true;
                }
            }
        }
    }
}

//点击属性图标编辑事件
function setInput(id){
    var obj=document.getElementById(id);
    if(obj.style.display=='block'){
        obj.style.display='none';
    }else{
        obj.style.display='block';
    }
}


function change_state(obj,func,id){
    var ClassName=$(obj).attr('class');
    var val = (ClassName.match(/yes/i)) ? 0 : 1;
    var postData = "val="+val+"&id="+id;
    $.ajax({
        url:admin_webroot+func,
        Type:"POST",
        data: postData,
        dataType:"json",
        success:function(data){
            if(data.flag == 1){
                if(val==0){
                    $(obj).removeClass("am-icon-check am-yes");
                    $(obj).addClass("am-icon-close am-no");
                }
                if(val==1){
                    $(obj).removeClass("am-icon-close am-no");
                    $(obj).addClass("am-icon-check am-yes");
                }
            }

        }
    });
}
</script>